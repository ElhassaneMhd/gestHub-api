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
Route::post('/register', [AuthController::class,'register']);
Route::POST('/login', [AuthController::class, 'login']);
Route::get('/session', [AuthController::class, 'session']);
Route::get('/offers/visible', [OfferController::class,'index']);
Route::get('/settings', [GeneralController::class,'getSettings']);
Route::get('/offers/{id}', [OfferController::class,'show']);

// protected Routes
Route::group([
    'middleware' => ['api', 'auth:api', 'jwt.auth','online'],
], function ($router) {
    Route::post('/generate/attestation/{id}', [AttestationController::class,'generateOneAttestation']);
    Route::POST('/logout', [AuthController::class, 'logout']);
    Route::POST('/session/{id}/abort', [AuthController::class, 'forgotSession']);
    Route::GET('/user', [AuthController::class, 'user']);
    Route::POST('/settings', [GeneralController::class, 'setAppSettings']);
    
    //get all data => projects , admins , tasks ,supervisors , users ( NB data must be pluriel)
    Route::get('/users/accepted', [GeneralController::class, 'getAcceptedUsers']);
    Route::post('/multiple/{data}/{action}', [GeneralController::class, 'multipleActions']);
    Route::get('/{data}', [GeneralController::class, 'index']);
    Route::get('/{data}/{id}', [GeneralController::class, 'show']);
    
    //CRUD all profiles Routes
    Route::apiResource('profiles', ProfileController::class);
    Route::post('profiles/{id}/password', [ProfileController::class,'updatePassword']);
    Route::post('/files/{id}', [ProfileController::class,'storeFile']);
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
