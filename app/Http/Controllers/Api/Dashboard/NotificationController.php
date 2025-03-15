<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\FirebaseNotificationTrait;
use App\Traits\ResponseTrait;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    use ResponseTrait;
    use FirebaseNotificationTrait;


    /**
     * Get all notifications
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $user = Auth::user();
        $notifications = $user->notifications;
        return $this->showResponse($notifications, 'Notification retrieved successfully !!', 200);
    }

    /**
     * Mark notification as read
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAsRead(string $id)
    {
        $user = User::find(Auth::user()->id);
        $notification = $user->notifications()->findOrFail($id);
        $notification->markAsRead();
        return $this->showMessage('Notification marked as read', 200);
    }


    /**
     * Mark all notifications as read
     * @return \Illuminate\Http\JsonResponse
     */
    public function markAllAsRead()
    {
        $user = User::find(Auth::user()->id);
        $user->unreadNotifications->markAsRead();
        return $this->showMessage('All notifications marked as read', 200);
    }


    /**
     * Delete specific notification
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $notification = $user->notifications()->findOrFail($id);
        $notification->delete();
        return $this->showMessage('Notification deleted successfully !!', 200);
    }


    /**
     * Send notification to specific user
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function unicastNotification(Request $request, string $id)
    {
        $token = User::findOrFail($id)->device_token ?? null;
        try {
            $message = $this->unicast($request, $token);
            return $this->showResponse($message, 'Notification sent successfully !!', 200);
        } catch (Exception $e) {
            report($e);
            return $this->showError($e, 'An error occur while sending notification');
        }
    }
}
