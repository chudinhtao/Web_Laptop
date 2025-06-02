<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\Home_client;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\OrderAdminController; // Di chuyển lên đây
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductTypesController;

use App\Http\Controllers\BrandsController;


use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ThongkeController;



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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/upload', [UploadController::class, 'upload']);

//Quản lý loại sản phẩm
Route::apiResource('product_types', ProductTypesController::class);

//Giỏ hàng trang chủ
Route::get('/products', [Home_client::class, 'getByLoai']);
Route::get('/products_mouse', [Home_client::class, 'getAccessory']);
Route::get('/laptops/{id}', [Home_client::class, 'getLaptopById']);
Route::get('/accessory/{id}', [Home_client::class, 'getAccessoryById']);

Route::post('/cart/add', [CartController::class, 'addToCart']);
Route::get('/cart/{userId}', [CartController::class, 'getCartByUser']);
Route::put('/cart/{cartId}', [CartController::class, 'updateCart']);
Route::delete('/cart/{cartId}', [CartController::class, 'deleteCart']);

// Routes cho đơn hàng client
Route::post('/orders', [OrderController::class, 'store']);


//Quản lý đơn hàng
//Admin (tất cả đơn hàng) - ĐẶT TRƯỚC routes có tham số
Route::get('/admin/orders', [OrderAdminController::class, 'index']);
Route::put('/admin/orders/{id}/status', [OrderAdminController::class, 'updateStatus']);

//Client (đơn hàng theo userID)
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/user/{userId}', [OrderController::class, 'getOrderByUser']);
Route::get('/orders/{id}', [OrderController::class, 'getOrderDetailByOrderId']);
//Hủy đơn hàng 
Route::put('/orders/{id}', [OrderController::class, 'updateStatus']);




Route::apiResource('brands', App\Http\Controllers\BrandsController::class);

//Login/Logout
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout']);

//Register
Route::post('/register', [RegisterController::class, 'register']);

//Users
Route::get('/users', [UserController::class, 'index']);
Route::get('/users/{id}', [UserController::class, 'show']);
Route::post('/users', [UserController::class, 'store']);
Route::put('/users/{id}', [UserController::class, 'update']);
Route::delete('/users/{id}', [UserController::class, 'destroy']);

// Thống kê 
Route::get('/thongke', [ThongkeController::class, 'dashboard']);



