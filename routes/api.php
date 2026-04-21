<?php

use App\Http\Controllers\AdminLevelController;
use App\Http\Controllers\ReimbursementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserLevelController;
use Illuminate\Support\Facades\Route;
use Termwind\Components\Raw;

// Auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

// Reimburse
// Route::get('/reimburse', [ReimbursementController::class, 'getReimburse']);

Route::middleware('auth:sanctum')->group(function () {
    // Admin level
    Route::post('/role/add', [AdminLevelController::class, 'AddadminUser']);
    Route::post('/role/{level_id}/update', [AdminLevelController::class, 'updateAdminUser']);
    Route::get('/role', [AdminLevelController::class, 'getAdminUser']);

    // User admin
    Route::get('/user', [UserLevelController::class, 'getlistUserAdmin']);
    Route::post('/user/add', [UserLevelController::class, 'addUsertoAdmin']);
    Route::post('/user/{user_id}/update', [UserLevelController::class, 'updateRoleuserAdmin']);

    // Category
    Route::post('/category/create', [CategoryController::class, 'createCategories']);
    Route::get('/category', [CategoryController::class, 'getCategory']);
    Route::post('/category/{category_id}/update', [CategoryController::class, 'updateCategory']);

    // Reimburse
    Route::post('/reimburse', [ReimbursementController::class, 'reqReimburse']);
    Route::get('/reimburse', [ReimbursementController::class, 'getReimburse']);
    Route::get('/{reimburse_id}/reimburse', [ReimbursementController::class, 'ReimburseSpec']);
    Route::post('/reimburse/{reimburse_id}/update', [ReimbursementController::class, 'getReimburse']);
});
