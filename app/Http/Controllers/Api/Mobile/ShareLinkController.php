<?php

namespace App\Http\Controllers\Api\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Advertisement;

class ShareLinkController extends Controller
{

    public function handle($id)
    {
        $ad = Advertisement::findOrFail($id);
        $userAgent = request()->header('User-Agent');
        $isAndroid = preg_match('/android/i', $userAgent);
        $isIOS = preg_match('/iphone|ipad|ipod/i', $userAgent);
        $appStoreUrl = $isIOS
            ? 'https://apps.apple.com/app/idYOUR_APP_ID'
            : 'https://play.google.com/store/apps/details?id=com.example.bazar';

        $appLink = "bazarapp://share-link/advertisements/$id";
        return view('ads/share-link', compact('appStoreUrl', 'ad', 'appLink'));
    }
}
