<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\DashboardController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('auth')->group(function () {

    //    Social Lite Routes
    Route::get('login/{provider}', [AuthController::class, 'redirectToProvider']);
    Route::get('login/{provider}/callback', [AuthController::class, 'handleProviderCallback']);

    //Public Routes
    Route::post('register', [AuthController::class, 'register']);
    Route::post('verify-phone', [AuthController::class, 'verifyPhone']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('resend/{id}', [AuthController::class, 'resendOtpCode']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('verify-code', [AuthController::class, 'verifyCode']);
    Route::post('reset-password', [AuthController::class, 'resetPassword']); 
    Route::group(['middleware' => ['auth:api', 'role:admin']], function () {
        Route::get('logout', [AuthController::class, 'logout']);
    });
});
Route::group(['middleware' => ['auth:api', 'role:admin', 'check-user-status']], function () {
    
    Route::prefix('dashboard')->group(function () {
        Route::get('user-data',[DashboardController::class,'getUserData']);
        Route::get('recent-projects',[DashboardController::class,'recentProjects']);
        });

    Route::prefix('project')->group(function () {
    Route::post('add', [ProjectController::class, 'addProject']);
    Route::post('upload-images', [ProjectController::class, 'uploadImages']);
    Route::get('status-all',[ProjectController::class,'statusAll']);
    Route::post('comment',[ProjectController::class,'comment']);
    Route::post('get-comments',[ProjectController::class,'getComments']);
    Route::post('info',[ProjectController::class,'info']);
    Route::get('recent',[ProjectController::class,'recentProjects']);
    Route::get('get-all',[ProjectController::class,'getProjects']);
    });
    Route::prefix('category')->group(function () {
        Route::post('add', [CategoryController::class, 'addCategory']);
        Route::get('get',[CategoryController::class,'getCategories']);     
    });
    Route::prefix('message')->group(function () {
        Route::post('send', [MessageController::class, 'sendMessage']);
        Route::get('get-chats',[MessageController::class,'getChats']);
        Route::post('get-messages',[MessageController::class,'getMessages']);
        
    });
    Route::prefix('setting')->group(function () {
        Route::post('edit-profile', [SettingController::class, 'editProfile']);
        Route::post('change-password', [SettingController::class, 'changePassword']);
        Route::post('profile-image', [SettingController::class, 'profileImage']);
    });
        
});

Route::any(
    '{any}',
    function () {
        return response()->json([
            'status_code' => 404,
            'message' => 'Page Not Found. Check method type Post/Get or URL',
        ], 404);
    }
)->where('any', '.*');