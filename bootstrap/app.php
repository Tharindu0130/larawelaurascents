<?php

<<<<<<< HEAD
// Enable error reporting to see any errors during app creation
error_reporting(E_ALL);
ini_set('display_errors', 1);

=======
>>>>>>> 48820d73d693238422ed1311b0652368a9c335d5
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

<<<<<<< HEAD
try {
    return Application::configure(basePath: dirname(__DIR__))
        ->withRouting(
            web: __DIR__.'/../routes/web.php',
            api: __DIR__.'/../routes/api.php',
            commands: __DIR__.'/../routes/console.php',
            health: '/up',
        )
        // admin middleware alias removed during admin-side cleanup
        ->withMiddleware(function (Middleware $middleware): void {
            // intentionally left empty
        })
        ->withExceptions(function (Exceptions $exceptions): void {
            //
        })->create();
} catch (Throwable $e) {
    echo "Bootstrap Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . " Line: " . $e->getLine() . "\n";
    echo "Trace: " . $e->getTraceAsString();
    exit(1);
}
=======
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\AdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
>>>>>>> 48820d73d693238422ed1311b0652368a9c335d5
