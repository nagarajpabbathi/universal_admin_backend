<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CategoryTypeController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\LuckydrawController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ShopMediaController;
use App\Http\Controllers\ShopServiceController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
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




Route::middleware('auth:api')->group(function () {
    Route::delete('user-delete/{id}', [UserController::class, 'destroy']);
    Route::put('/update-profile', [UserController::class, 'updateProfile']);
    Route::put('/update-password', [UserController::class, 'updatePassword']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/all-users', [UserController::class, 'allUsers']);
    Route::get('/unverified-users', [UserController::class, 'unVerifiedUsers']);
    Route::put('users/verify/{user_id}', [UserController::class, 'verifyUser']);

    Route::resource('categories', CategoryController::class);
    Route::resource('shops', ShopController::class);
    Route::get('/shops-options', [ShopController::class, 'fetchShopOptions']);

    Route::resource('shop-service', ShopServiceController::class);
    Route::resource('shop-media', ShopMediaController::class);
    Route::resource('category-types', CategoryTypeController::class);

    Route::resource('feedbacks', FeedbackController::class);
    Route::resource('promotions', PromotionController::class);



    Route::resource('/news', NewsController::class);
    Route::resource('/luckydraw', LuckydrawController::class);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

