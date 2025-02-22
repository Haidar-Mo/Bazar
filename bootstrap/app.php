<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append([
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            // \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // $middleware->group('api', [
        //     \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        //     \Illuminate\Routing\Middleware\ThrottleRequests::class . ':api',
        //     \Illuminate\Routing\Middleware\SubstituteBindings::class,
        // ]);

        $middleware->alias([
            'is-admin' => \App\Http\Middleware\IsAdminMiddleware::class,
            'is-client' => \App\Http\Middleware\IsClientMiddleware::class,
            'has-subscription' => \App\Http\Middleware\HasAdsSubscriptionMiddleware::class,
            'ability' => \Laravel\Sanctum\Http\Middleware\CheckAbilities::class,

        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
