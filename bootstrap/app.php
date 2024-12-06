<?php

use App\Http\Middleware\AdminRedirectIfAuthenticated;
use App\Http\Middleware\SetLocale;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminAuthenticate;
use App\Http\Middleware\TrackUtmMiddleware;
use App\Http\Middleware\LanguageMiddleware;
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // $middleware->append(AdminAuthenticate::class);
        // // $middleware->append(AdminRedirectIfAuthenticated::class);
        $middleware->web(append : [
            
            // Second::class,
            LanguageMiddleware::class
        ]);
        // $middleware->append(TrackUtmMiddleware::class);
        $middleware->alias([

            'admin.auth' => AdminAuthenticate::class, 
            'admin.guest' => AdminRedirectIfAuthenticated::class, 
            
            // 'setLocale' => SetLocale::class
            
        ]);
        // $middleware->alias([
            
        //     'admin.guest' => AdminRedirectIfAuthenticated::class,  
    
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
