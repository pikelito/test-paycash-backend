<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\PeopleController;


Route::prefix('people')->group(function () {
    Route::get('/', [PeopleController::class, 'index']);
    Route::post('/', [PeopleController::class, 'store']);
    Route::get('/{id}', [PeopleController::class, 'show']);
    Route::put('/{id}', [PeopleController::class, 'update']);
    Route::delete('/{id}', [PeopleController::class, 'destroy']);
});