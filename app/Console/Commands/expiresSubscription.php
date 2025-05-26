<?php

namespace App\Console\Commands;
use App\Models\Advertisement;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Subscription;
use GPBMetadata\Google\Rpc\Status;
use Illuminate\Support\Facades\Log;
use App\Traits\FirebaseNotificationTrait;
use App\Notifications\SubscriptiondNotification;
class expiresSubscription extends Command
{
    use FirebaseNotificationTrait;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expires-subscription';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscriptions=Subscription::where('Status','running')->get();
        foreach($subscriptions as $subscription){
            if($subscription->ends_at < Carbon::now()){
                $subscription->status='ended';
                $subscription->save();

                 //! Notification

                 $user=$subscription->user;
                 $token=$user->device_token;

                 if($token){
                    try {
                        $Request = (object) [
                            'title' => 'انهاء الاشتراك',
                            'body' => 'تم الغاء اشتراكك بالباقة',
                            'type'=>'subscription-plan',
                        ];

                        $this->unicast($Request, $token);
                    } catch (\Exception $e) {
                        Log::error('فشل إرسال الإشعار: ' . $e->getMessage());
                    }
                 }
                 $user->notify(new SubscriptiondNotification($subscription->plan->name,'الغاء اشتراك','تم الغاء اشتراكك من الباقة '));

            }



        }

    }


}
