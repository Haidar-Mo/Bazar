<?php

namespace App\Traits;

use Exception;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Illuminate\Support\Facades\Log;

trait FirebaseNotificationTrait
{
    protected $messaging;

    public function initializeFirebase()
    {
        if (!$this->messaging) {
            $firebase = (new Factory)
                ->withServiceAccount(config('services.firebase.credentials.file'));

            $this->messaging = $firebase->createMessaging();
        }
    }

    /**
     * Sending message to a single User
     * @param mixed $request
     * @param mixed $token
     * @return CloudMessage
     */
    public function unicast($request, $token)
    {
        $this->initializeFirebase();

        $notification = Notification::create($request->title, $request->body);
        $message = CloudMessage::new();
        $message->toToken($token)
            ->withNotification($notification);
        $this->messaging->send($message);
        return $message;
    }

    public function subscribeToTopic($deviceToken, $topic)
    {
        $this->initializeFirebase();

        try {
            $this->messaging->subscribeToTopic($topic, $deviceToken);
            Log::info('Subscribed to topic: ' . $topic . ' with device token: ' . $deviceToken);
            return ['success' => true, 'message' => 'Subscribed to topic successfully!'];
        } catch (Exception $e) {
            Log::error('Firebase Topic Subscription Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to subscribe to topic'];
        }
    }

    public function sendNotificationToTopic($topic, $title, $body)
    {
        $this->initializeFirebase();

        try {
            $notification = Notification::create($title, $body);
            $message = CloudMessage::new();
            $message->toTopic($topic)
                ->withNotification($notification);

            $this->messaging->send($message);
            Log::info('Notification sent successfully to topic: ' . $topic);
            return ['success' => true, 'message' => 'Notification sent successfully!'];
        } catch (Exception $e) {
            Log::error('Firebase Topic Notification Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to send notification'];
        }
    }
}
