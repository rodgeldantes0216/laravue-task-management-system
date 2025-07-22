<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;
use Illuminate\Auth\AuthenticationException;


return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {

        // Register route middleware aliases
        $middleware->alias([
            'admin' => \App\Http\Middleware\CheckAdmin::class,
        ]);

        // Configure API middleware group
        $middleware->api(prepend: [
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class, // ✅ Needed for session!
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            EnsureFrontendRequestsAreStateful::class,
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (AuthenticationException $e, $request) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated.'], 401)
                : abort(401, 'Unauthenticated.');
        });
    })
    ->withCommands([
        \App\Console\Commands\CleanOldTasks::class,
    ])
    ->withSchedule(function ($schedule) {
        // Example: schedule commands
        $schedule->command('tasks:cleanup')->daily();
    })
    ->create();
