<?php

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
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
        $exceptions->renderable(function (\Symfony\Component\HttpKernel\Exception\HttpException $e, $request) {
            return response()->json([
                'error' => [
                    'message' => 'HTTP error occurred.',
                    'code' => 'HTTP_ERROR'
                ]
            ], $e->getStatusCode());
        });

        $exceptions->renderable(function (ModelNotFoundException $e, $request) {
            return response()->json([
                'error' => [
                    'message' => 'Resource not found.',
                    'code' => 'RESOURCE_NOT_FOUND'
                ]
            ], \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        });

        $exceptions->renderable(function (NotFoundHttpException $e, $request) {
            return response()->json([
                'error' => [
                    'message' => 'Route not found.',
                    'code' => 'ROUTE_NOT_FOUND'
                ]
            ], \Symfony\Component\HttpFoundation\Response::HTTP_NOT_FOUND);
        });

        $exceptions->renderable(function (AuthorizationException $e, $request) {
            return response()->json([
                'error' => [
                    'message' => 'You are not authorized to perform this action.',
                    'code' => 'UNAUTHORIZED_ACTION'
                ]
            ], \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        });

        $exceptions->renderable(function (ValidationException $e, $request) {
            return response()->json([
                'error' => [
                    'message' => 'Validation error',
                    'code' => 'VALIDATION_ERROR',
                    'details' => $e->errors()
                ]
            ], \Symfony\Component\HttpFoundation\Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        $exceptions->renderable(function (AccessDeniedHttpException $e, $request) {
            return response()->json([
                'error' => [
                    'message' => 'You are not authorized to perform this action.',
                    'code' => 'UNAUTHORIZED_ACTION'
                ]
            ], \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        });

        $exceptions->renderable(function (AuthenticationException $e, $request) {
            return response()->json([
                'error' => [
                    'message' => 'You must be authenticated to access this resource.',
                    'code' => 'AUTHENTICATION_REQUIRED'
                ]
            ], \Symfony\Component\HttpFoundation\Response::HTTP_UNAUTHORIZED);
        });

        $exceptions->renderable(function (MethodNotAllowedHttpException $e, $request) {
            return response()->json([
                'error' => [
                    'message' => 'Method not allowed for this route.',
                    'code' => 'METHOD_NOT_ALLOWED'
                ]
            ], \Symfony\Component\HttpFoundation\Response::HTTP_METHOD_NOT_ALLOWED);
        });
        //
    })->create();
