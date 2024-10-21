<?php

use App\Http\Middleware\AdminRedirectIfAuthenticated;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminAuthenticate;

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
        // $middleware->web(append : [
        //     AdminAuthenticate::class,
        //     AdminRedirectIfAuthenticated::class,
        //     // Second::class,
        // ]);
        $middleware->alias([

            'admin.auth' => AdminAuthenticate::class, 
            'admin.guest' => AdminRedirectIfAuthenticated::class, 
            
        ]);
        // $middleware->alias([
            
        //     'admin.guest' => AdminRedirectIfAuthenticated::class,  
    
        // ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
