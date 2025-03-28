<?php

namespace App\Console\Commands;
use App\Models\Advertisement;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use App\Models\Subscription;
use GPBMetadata\Google\Rpc\Status;

class expiresSubscription extends Command
{
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
            }

        }

    }


}
