<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\JsonResponse;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->renderable(function (Throwable $e) {
            return $this->handleApiException($e);
        });
    }

    private function handleApiException(Throwable $e): ?JsonResponse
    {
        if (request()->expectsJson()) {
            if ($e instanceof ModelNotFoundException) {
                return response()->json([
                    'message' => 'Recurso no encontrado',
                    'error' => 'Not Found'
                ], 404);
            }

            if ($e instanceof ValidationException) {
                return response()->json([
                    'message' => 'Los datos proporcionados no son válidos.',
                    'errors' => $e->errors()
                ], 422);
            }


            return response()->json([
                'message' => 'Ha ocurrido un error interno.'
            ], 500);
        }

        return null;
    }
}
