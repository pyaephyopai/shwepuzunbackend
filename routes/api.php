<?php

use App\Http\Controllers\API\AttachmentController;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BlogController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ContactController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\RatingController;
use App\Http\Controllers\API\RoleController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\VerificationController;
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

Route::group(['middleware' => 'auth:sanctum'], function () {

    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/roles', [RoleController::class, 'getRoles']);

    Route::get('users', [UserController::class, 'index']);

    Route::post('/users', [UserController::class, 'store']);

    Route::get('/users/orders', [OrderController::class, 'userOrders']);

    Route::get('/users/{id}', [UserController::class, 'show']);

    Route::post('users/{id}', [UserController::class, 'update']);

    Route::delete('users/{id}', [UserController::class, 'destory']);

    Route::get('/blogs', [BlogController::class, 'index']);

    Route::post('/blogs', [BlogController::class, 'store']);

    Route::get('/blogs/{id}', [BlogController::class, 'show']);

    Route::post('/blogs/{id}', [BlogController::class, 'update']);

    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);

    Route::apiResource('/categories', CategoryController::class);

    Route::get('/products', [ProductController::class, 'index']);

    Route::post('/products', [ProductController::class, 'store']);

    Route::get('/products/{id}', [ProductController::class, 'show']);

    Route::post('/products/{id}', [ProductController::class, 'update']);

    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    Route::delete('attachment/{id}', [AttachmentController::class, 'attachmentDelete']);

    Route::get('/products/list', [ProductController::class, 'productListShow']);

    Route::post('/rating', [RatingController::class, 'store']);

    Route::apiResource('orders', OrderController::class);

    Route::post('orders/change-status/{id}', [OrderController::class, 'changeStatus']);

    Route::post('users-update/{id}', [UserController::class, 'userInformationUpdate']);

    Route::post('user-change-password', [UserController::class, 'userChangePassword']);

    Route::get('dashboard', [DashboardController::class, 'dashboard']);

    Route::get('dashboard/monthly-sales', [DashboardController::class, 'monthlySales']);

    Route::get('dashboard/monthly-users', [DashboardController::class, 'monthlyUsers']);

    Route::get('/contacts', [ContactController::class, 'index']);

});

//email verification
Route::get('email/verify/{id}', [VerificationController::class, 'verify'])->name('verification.verify');

Route::get('email/resend', [VerificationController::class, 'resend'])->name('verification.resend');

Route::post('/auth/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/category-list', [CategoryController::class, 'categoryList']);
Route::get('/product-list', [ProductController::class, 'productList']);
Route::get('/product-list/{id}', [ProductController::class, 'productListShow']);
Route::get('/blog-list', [BlogController::class, 'blogList']);
Route::get('/blog-list/{id}', [BlogController::class, 'blogListShow']);
Route::get('/blog-list-random/{id}', [BlogController::class, 'blogListRandom']);
Route::get('/product-list-random/{id}', [ProductController::class, 'productListRandom']);

Route::post('/contacts', [ContactController::class, 'store']);