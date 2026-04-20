<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'check.auth'  => \App\Http\Middleware\CheckAuth::class,
            'check.guest' => \App\Http\Middleware\CheckGuest::class,
            'check.admin' => \App\Http\Middleware\CheckAdmin::class, // ADD THIS
        ]);
    })
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function ($middleware) {
        $middleware->alias([
            'check.auth' => \App\Http\Middleware\CheckAuth::class,
            'check.guest' => \App\Http\Middleware\CheckGuest::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
