<?php

use Illuminate\Auth\Middleware\RedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\EnsureUserRole::class,
            'force.password.change' => \App\Http\Middleware\ForcePasswordChange::class,
        ]);

        RedirectIfAuthenticated::redirectUsing(function () {
            $user = auth()->user();
            return $user?->role === 'admin'
                ? route('admin.dashboard')
                : route('portal.loans');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
