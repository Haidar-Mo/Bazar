<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('v1/')->group(function () {

    include __DIR__ . "/Dashboard/v1/User.php";
    include __DIR__ . "/Dashboard/v1/Notification.php";
    include __DIR__ . "/Dashboard/V1/Plan.php";

    include __DIR__ . "/Mobile/V1/Auth.php";
    include __DIR__ . "/Mobile/V1/Profile.php";
    include __DIR__ . "/Mobile/V1/Ads.php";
    include __DIR__ . "/Mobile/V1/VerificationRequest.php";
    include __DIR__ . "/Mobile/V1/Favorite.php";
    include __DIR__ . "/Mobile/V1/FavoriteListItem.php";
    include __DIR__ . "/Mobile/V1/HomePage.php";
    include __DIR__ . "/Mobile/V1/Category.php";
    include __DIR__ . "/Mobile/V1/Report.php";
    include __DIR__ . "/Mobile/V1/Rate.php";
    include __DIR__ . "/Mobile/V1/Subscription.php";

});
