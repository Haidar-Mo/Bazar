<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::prefix('v1/')->group(function () {
    //! Dashboard section
    include __DIR__ . "/Dashboard/V1/Ads.php";
    include __DIR__ . "/Dashboard/V1/Category.php";
    include __DIR__ . "/Dashboard/V1/Notification.php";
    include __DIR__ . "/Dashboard/V1/Plan.php";
    include __DIR__ . "/Dashboard/V1/Report.php";
    include __DIR__ . "/Dashboard/V1/User.php";
    include __DIR__ . "/Dashboard/V1/Rule.php";
    include __DIR__ . "/Dashboard/V1/Official.php";
    include __DIR__ . "/Dashboard/V1/Permission.php";
    include __DIR__ . "/Dashboard/V1/Chat.php";
    include __DIR__ . "/Dashboard/V1/City.php";


    //! Mobile section

    Route::prefix('mobile')->group(function () {
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
        include __DIR__ . "/Mobile/V1/Chat.php";
        include __DIR__ . "/Mobile/V1/Meesage.php";
        include __DIR__ . "/Mobile/V1/City.php";
        include __DIR__ . "/Mobile/V1/Rule.php";


    });
});
