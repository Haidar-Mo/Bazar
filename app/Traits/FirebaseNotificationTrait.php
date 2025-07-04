<?php

namespace App\Traits;

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

        $notification = Notification::create($request->title, $request->body,$request->type);
        $message = CloudMessage::new();
        $message = CloudMessage::withTarget('token', $token)
            ->withNotification($notification);
        $this->messaging->send($message);
        return $message;
    }

    public function subscribeToTopic($deviceToken, $topic)
    {
        $this->initializeFirebase();

        try {
            $this->messaging->subscribeToTopic($topic, $deviceToken);
            Log::info("Subscribed to topic: $topic with device token: $deviceToken");
            return ['success' => true, 'message' => 'Subscribed to topic successfully!'];
        } catch (\Exception $e) {
            Log::error('Firebase Topic Subscription Error: ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to subscribe to topic'];
        }
    }


    public function unsubscribeFromTopic($deviceToken, $topic)
    {
        $this->initializeFirebase();

        try {
            $this->messaging->unsubscribeFromTopic($topic, $deviceToken);
            Log::info('Unsubscribed from topic: ' . $topic);
            return ['success' => true];
        } catch (\Exception $e) {
            Log::error('Unsubscribe error: ' . $e->getMessage());
            return ['success' => false];
        }
    }


  public function sendNotificationToTopic($topic, $title, $body, $advertisementId = null)
{
    if (empty($topic)) {
        Log::error('Topic is empty');
        return ['success' => false, 'message' => 'Topic is empty'];
    }

    $this->initializeFirebase();

    try {
        $message = CloudMessage::withTarget('topic', $topic)
            ->withNotification([
                'title' => $title,
                'body' => $body,
            ])
            ->withData([ 
                'advertisement_id' => $advertisementId ?? '',
            ]);

        $this->messaging->send($message);

        Log::info('Notification sent successfully to topic: ' . $topic, [
            'title' => $title,
            'body' => $body,
            'advertisement_id' => $advertisementId,
        ]);

        return ['success' => true, 'message' => 'Notification sent successfully!'];
    } catch (\Exception $e) {
        Log::error('Firebase Topic Notification Error: ' . $e->getMessage(), [
            'topic' => $topic,
            'title' => $title,
            'body' => $body,
            'advertisement_id' => $advertisementId,
        ]);
        return ['success' => false, 'message' => 'Failed to send notification'];
    }
}


}
