<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\{
    FirebaseNotificationTrait,
    ResponseTrait
};
use App\Models\{
    Category,
    User,
    NotificationSetting
};
use Illuminate\Support\Facades\{
    DB,
    Auth
};
use App\Http\Requests\Mobile\NotificationSettingsRequest;

class NotificationSettings extends Controller
{
    use ResponseTrait, FirebaseNotificationTrait;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return $this->showResponse($user->NotificationSettings()->get(), 'done successfully...!');
    }



    public function store(NotificationSettingsRequest $request)
    {

        DB::transaction(function () use ($request) {

            $user = Auth::user();
            $currentSettings = $user->notificationSettings;
            $currentCategoryIds = $currentSettings->pluck('category_id')->unique();

            if ($currentCategoryIds->isNotEmpty()) {
                $topicsToUnsubscribe = Category::whereIn('id', $currentCategoryIds)
                    ->pluck('name');

                foreach ($topicsToUnsubscribe as $topic) {
                    if ($user->device_token) {
                        $this->unsubscribeFromTopic($user->device_token, $topic);
                    }
                }
            }

            $user->notificationSettings()->delete();

            $newCategoryIds = $request->input('categories', []);

            foreach ($newCategoryIds as $categoryId) {
                $user->NotificationSettings()->create([
                    'category_id' => $categoryId,

                ]);
            }

            if (!empty($newCategoryIds)) {
                $newTopics = Category::whereIn('id', $newCategoryIds)
                    ->pluck('name');

                foreach ($newTopics as $topic) {
                    if ($user->device_token) {
                        $this->subscribeToTopic($user->device_token, $topic);
                    }
                }
            }
        });

        return $this->showMessage('done successfully...!');
    }
}
