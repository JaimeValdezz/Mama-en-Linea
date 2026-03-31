<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// ESTO ES LO QUE QUITA EL ERROR DE gRPC DESDE LA RAÍZ
putenv('GOOGLE_CLOUD_SUPPRESS_GRPC_WARNINGS=true');
putenv('FIREBASE_HTTP_CLIENT_OPTIONS_GUZZLE_HANDLER=true');

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();