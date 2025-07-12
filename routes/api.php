<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InspectionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::middleware('api')->prefix('v1')->group(function () {
    // Test route
    Route::get('/test', function () {
        return response()->json([
            'status' => 'success',
            'message' => 'API is working!',
            'timestamp' => now()
        ]);
    });

    // Inspection routes
    Route::apiResource('inspections', InspectionController::class);
    Route::post('inspections/{id}/signature', [InspectionController::class, 'addSignature']);
    Route::get('inspections/{id}/pdf', [InspectionController::class, 'generatePdf']);
});