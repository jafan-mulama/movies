<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MovieController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CastCrewController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\SubscriptionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Public routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth routes
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('user', [AuthController::class, 'user']);

    // Movie routes
    Route::apiResource('movies', MovieController::class);
    Route::post('movies/{movie}/upload-thumbnail', [MovieController::class, 'uploadThumbnail']);
    Route::get('movies/featured', [MovieController::class, 'featured']);
    Route::get('movies/latest', [MovieController::class, 'latest']);
    Route::get('movies/popular', [MovieController::class, 'popular']);

    // Category routes
    Route::apiResource('categories', CategoryController::class);
    Route::get('categories/{category}/movies', [CategoryController::class, 'movies']);

    // Cast & Crew routes
    Route::apiResource('cast-crew', CastCrewController::class);
    Route::get('cast-crew/{castCrew}/movies', [CastCrewController::class, 'movies']);

    // Review routes
    Route::apiResource('movies.reviews', ReviewController::class)->shallow();
    Route::patch('reviews/{review}/approve', [ReviewController::class, 'approve'])
        ->middleware('can:approve-reviews');

    // Subscription routes
    Route::get('subscription-plans', [SubscriptionController::class, 'index']);
    Route::post('subscribe', [SubscriptionController::class, 'subscribe']);
    Route::get('subscription', [SubscriptionController::class, 'current']);
});
