<?php

use App\Http\Controllers\AttestationController;
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\taskController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\ProjectController;

// public routes
Route::post('/register', [ProfileController::class,'register']);
Route::POST('/login', [AuthController::class, 'login']);
Route::get('/offers/visible', [OfferController::class,'index']);
Route::get('/offers/{id}', [OfferController::class,'show']);

// protected Routes
Route::middleware('checkorigin')->middleware('auth:sanctum')->group(function () {
    Route::post('/generate/attestations', [AttestationController::class,'generateAttestations']);
    Route::post('/generate/attestation/{id}', [AttestationController::class,'generateAttestation']);
    Route::POST('/logout', [AuthController::class, 'logout']);
    Route::GET('/user', [AuthController::class, 'user']);
    Route::POST('/settings', [GeneralController::class, 'setAppSettings']);
    
    //get all data => projects , admins , tasks ,supervisors , users ( NB data must be pluriel)
    Route::get('/users/accepted', [GeneralController::class, 'getAcceptedUsers']);
    Route::post('/users/accept', [GeneralController::class, 'storeNewIntern']);
    Route::get('/{data}', [GeneralController::class, 'index']);
    Route::get('/{data}/{id}', [GeneralController::class, 'show']);

    //CRUD all profiles Routes
    Route::apiResource('profiles', ProfileController::class);
    Route::post('profiles/{id}/password', [ProfileController::class,'updatePassword']);
    Route::post('/files/{id}', [ProfileController::class,'storeAvatar']);
    //Offers
    Route::apiResource('offers', OfferController::class);
   
    Route::apiResource('applications', ApplicationController::class);
    Route::put('applications/{id}/read', [ApplicationController::class,'markAsRead']);
    //approve rejectApplication
    Route::put('/applications/{id}/{traitement}', [ApplicationController::class,'accepteApplication']);
    // Project
    Route::apiResource('projects', ProjectController::class);
    //tasks
    Route::apiResource('tasks', taskController::class);
});
