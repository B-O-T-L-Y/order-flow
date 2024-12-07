<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api(append: [
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class,
        ]);

        $middleware->alias(['admin' => \App\Http\Middleware\AdminMiddleware::class]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->renderable(function (ModelNotFoundException $exception, Request $request) {
            return response()->json([
                'errors' => [
                    'message' => 'The requested resource could not be found.',
                    'status' => \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND,
                ],
            ]);
        });

        $exceptions->renderable(function (NotFoundHttpException $exception, Request $request) {
            return response()->json([
                'errors' => [
                    'message' => 'Route not found.',
                    'status' => \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND,
                ],
            ]);
        });

        $exceptions->renderable(function (AuthorizationException $exception, Request $request) {
            return response()->json([
                'errors' => [
                    'message' => 'You are not authorized to perform this action.',
                    'status' => \Symfony\Component\HttpFoundation\Response::HTTP_FORBIDDEN,
                ],
            ]);
        });
        //
    })->create();
