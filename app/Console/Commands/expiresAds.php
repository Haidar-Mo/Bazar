<?php

namespace App\Console\Commands;
use App\Models\Advertisement;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Traits\FirebaseNotificationTrait;
use App\Notifications\AdExpiredNotification;
use Illuminate\Support\Facades\{
    Log
};
class expiresAds extends Command
{
    use FirebaseNotificationTrait;

    protected $signature = 'app:expires-ads';
    protected $description = 'Command description';

    public function handle()
    {
        $expiredAds = Advertisement::where('expiry_date', '<', Carbon::now() )
            ->get();
        foreach ($expiredAds as $ad) {
            if($ad->status!='inactive'){
            $ad->status = 'inactive';
            $ad->save();
            //$ad->delete();

            //! Notification

            $user = $ad->user;
            $token = $user->device_token;
            if ($token) {
                try {
                    $Request = (object) [
                        'title' => 'إعلان منتهي الصلاحية',
                        'body' => 'تم تعطيل إعلانك "'.$ad->title.'" بسبب انتهاء فترة النشر.',
                        'type'=>'delete-ads',
                    ];

                    $this->unicast($Request, $token);
                } catch (\Exception $e) {
                    Log::error('فشل إرسال الإشعار: ' . $e->getMessage());
                }
            }
            $user->notify(new AdExpiredNotification($ad->title));
        }
    }

    }
}
