<?php

namespace App\Console\Commands;
use App\Models\Advertisement;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
class expiresAds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expires-ads';

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
        $expiredAds = Advertisement::where('expiry_date', '<', Carbon::now() )
            ->get();
        foreach ($expiredAds as $ad) {
            if($ad->status!='inactive'){
            $ad->status = 'inactive';
            $ad->save();
            $ad->delete();
        }
    }

    }
}
