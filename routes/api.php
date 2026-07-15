<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobOfferController;
use Illuminate\Support\Facades\Route;

// ─── Routes publiques ─────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);

// ─── Routes protégées (token obligatoire) ─────────────────
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // ─── Offres d'emploi ──────────────────────────────────
    // apiResource crée automatiquement les 5 routes CRUD :
    //   GET    /api/job-offers          → index
    //   POST   /api/job-offers          → store
    //   GET    /api/job-offers/{id}     → show
    //   PUT    /api/job-offers/{id}     → update
    //   DELETE /api/job-offers/{id}     → destroy
    Route::apiResource('job-offers', JobOfferController::class);

        // ─── Candidatures ─────────────────────────────────────
    Route::get('/applications', [\App\Http\Controllers\ApplicationController::class, 'index']);
    Route::post('/job-offers/{jobOfferId}/apply', [\App\Http\Controllers\ApplicationController::class, 'store']);
    Route::put('/applications/{application}/status', [\App\Http\Controllers\ApplicationController::class, 'update']);

});
