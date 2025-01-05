<?php

namespace App\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait ExceptionHandler
{
    protected function handleException(\Throwable $e): JsonResponse
    {
        if ($e instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Recurso no encontrado',
                'error' => 'Not Found'
            ], 404);
        }

        // En producción, registrar el error para debugging
        \Log::error($e->getMessage());

        return response()->json([
            'message' => 'Ha ocurrido un error interno.',
            'error' => 'Internal Server Error'
        ], 500);
    }
}
