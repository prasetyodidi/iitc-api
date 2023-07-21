<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
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
            //
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
               'message' => $exception->getMessage(),
            ], 401);
        });

        $this->renderable(function (Exception $exception) {
            return response()->json([
                'status' => 0,
                'message' => $exception->getMessage(),
            ], 400);
        });
    }

}
