<?php

use App\Http\Controllers\AdminLevelController;
use App\Http\Controllers\ReimbursementController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserLevelController;
use Illuminate\Support\Facades\Route;


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
    Route::post('/roles', [AdminLevelController::class, 'AddadminUser']);
    Route::put('/roles/{level_id}', [AdminLevelController::class, 'updateAdminUser']);
    Route::get('/roles', [AdminLevelController::class, 'getAdminUser']);

    // User admin
    Route::get('/users/roles', [UserLevelController::class, 'getlistUserAdmin']);
    Route::post('/users/roles', [UserLevelController::class, 'addUsertoAdmin']);
    Route::put('/users/roles/{id}', [UserLevelController::class, 'updateRoleuserAdmin']);

    // Category
    Route::post('/categories', [CategoryController::class, 'createCategories']);
    Route::get('/categories', [CategoryController::class, 'getCategory']);
    Route::put('/categories/{category_id}', [CategoryController::class, 'updateCategory']);

    // Reimburse
    Route::post('/reimbursements', [ReimbursementController::class, 'reqReimburse']);
    Route::get('/reimbursements', [ReimbursementController::class, 'getReimburse']);
    Route::get('/my-reimbursements', [ReimbursementController::class, 'getMyReimburse']);
    Route::get('/reimbursements/{reimburse_id}', [ReimbursementController::class, 'ReimburseSpec']);
    Route::put('/reimbursements/{reimburse_id}', [ReimbursementController::class, 'updateReimburse']);
    Route::put('/reimbursements/{reimburse_id}/status', [ReimbursementController::class, 'approveReimburse']);
});
