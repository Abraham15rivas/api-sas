<?php

use Illuminate\Support\Facades\Route;

// Class Controllers
use App\Http\Controllers\Diary\DiaryController;

// Group route: User Auth
Route::middleware('auth.jwt')->group(function () {
    // Group route: v1
    Route::prefix('v1')->group(function () {
        // Group route: Admin
        Route::group([
            'prefix'     => 'admin',
            'middleware' => 'admin',
        ], function () {
            // Diary
            Route::post('diaries', [DiaryController::class, 'index']);
            
            // export report format excel
            Route::post('report/diary', [DiaryController::class, 'exportReportExcel']);
        });
        Route::post('diaries-self', [DiaryController::class, 'indexByUser']);
        Route::get('diary/{id}', [DiaryController::class, 'show']);
        Route::post('diary', [DiaryController::class, 'store']);
        Route::put('diary/{id}', [DiaryController::class, 'update']);
        Route::delete('diary/{diary}', [DiaryController::class, 'destroy']);
    });
});
