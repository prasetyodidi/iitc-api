<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\ItemNotFoundException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry') && env('APP_ENV') == 'prod') {
                app('sentry')->captureException($e);
            }
        });

        $this->renderable(function (ValidationException $exception) {
            return response()->json([
                'status' => 0,
                'message' => $exception->getMessage(),
                'data' => [
                    'errors' => $exception->errors(),
                ]
            ], 422);
        });

        $this->renderable(function (AccessDeniedHttpException $exception) {
            return response()->json([
                'status' => 0,
                'message' => env('APP_ENV') == 'local' ? $exception->getMessage() : 'unauthorize',
            ], 401);
        });

        $this->renderable(function (AuthenticationException $exception) {
            $data = [
                "status" => 0,
                "message" => "wrong password",
            ];
            return response()->json($data, 401);
        });

        $this->renderable(function (ItemNotFoundException $exception) {
            return response()->json([
                'status' => 0,
                'message' => env('APP_ENV') == 'local' ? $exception->getMessage() : 'item not error',
            ], 400);
        });

        $this->renderable(function (NotFoundHttpException $exception) {
            return response()->json([
                'status' => 0,
                'message' => env('APP_ENV') == 'local' ? $exception->getMessage() : 'item not found',
            ], 400);
        });

        $this->renderable(function (Exception $exception) {
            return response()->json([
                'status' => 0,
                'message' => env('APP_ENV') == 'local' ? $exception->getMessage() : 'request error',
            ], 400);
        });

    }

}
