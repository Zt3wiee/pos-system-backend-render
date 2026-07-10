<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SummaryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;


Route::post('login', [AuthController::class, 'Login']);


Route::middleware(['auth:sanctum'])->group(function () {

    Route::middleware('role:admin')->group(function () {
        
        // Route::apiResource('products', ProductController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::get('reports/daily', [ReportController::class, 'dailyReport']);
        Route::get('reports/monthly', [ReportController::class, 'monthLyReport']);
        Route::apiResource('users', UserController::class);
        Route::get('reports/top-products', [ReportController::class, 'topProducts']);
        Route::get('reports/summary', [SummaryController::class, 'summary']);
        
        Route::get('/db-test', function () {
    $start = microtime(true);

    DB::select('SELECT 1');

    return [
        'time' => microtime(true) - $start
    ];
});
    });

    Route::middleware('role:admin,cashier')->group(function () {
        Route::apiResource('sales', SaleController::class)->only(['store']);
        Route::get('user', [UserController::class, 'currentUser']);
        Route::get('reports/orders', [ReportController::class, 'index']);
        Route::apiResource('products', ProductController::class);
    });
    Route::post('logout', [AuthController::class, 'logout']);
});
