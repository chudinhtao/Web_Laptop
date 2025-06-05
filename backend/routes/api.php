<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\Home_client;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Payment_OrderController;
use App\Http\Controllers\UploadController;
use App\Http\Controllers\OrderAdminController; // Di chuyển lên đây
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductTypesController;

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\AuthController;
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



Route::apiResource('products', ProductController::class);
Route::apiResource('brands', BrandsController::class);





//Quản lý đơn hàng
//Admin (tất cả đơn hàng) - ĐẶT TRƯỚC routes có tham số
Route::get('/admin/orders', [OrderAdminController::class, 'index']);
Route::put('/admin/orders/{id}/status', [OrderAdminController::class, 'updateStatus']);

//Client (đơn hàng theo userID)
Route::get('/orders', [OrderController::class, 'index']);


Route::apiResource('brands', App\Http\Controllers\BrandsController::class);

//Login/Logout
// Route::post('/login', [LoginController::class, 'login']);
// Route::post('/logout', [LoginController::class, 'logout']);

// //Register
Route::post('/register', [RegisterController::class, 'register']);

// //Users
// Route::get('/users', [UserController::class, 'index']);
// Route::get('/users/{id}', [UserController::class, 'show']);
// Route::post('/users', [UserController::class, 'store']);
// Route::put('/users/{id}', [UserController::class, 'update']);
// Route::delete('/users/{id}', [UserController::class, 'destroy']);

// Thống kê 
// Route::get('/thongke', [ThongkeController::class, 'dashboard']);

Route::get('/thongke', [ThongkeController::class, 'dashboard']);
//

Route::post('login', [AuthController::class, 'login']);
Route::middleware(['jwt.auth'])->group(function () {
@@ -100,18 +100,3 @@
Route::delete('/cart/{cartId}', [CartController::class, 'deleteCart']);
Route::post('/buy', [Payment_OrderController::class, 'store']);
});

Route::middleware('jwt.auth')->group(function () {
//Users
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
//Loại sản phẩm
    Route::get('product_types', [ProductTypesController::class, 'index']);Add commentMore actions
    Route::post('product_types', [ProductTypesController::class, 'store']);
    Route::get('product_types/{id}', [ProductTypesController::class, 'show']);
    Route::put('product_types/{id}', [ProductTypesController::class, 'update']);
    Route::delete('product_types/{id}', [ProductTypesController::class, 'destroy']);

//thongke
    Route::get('/thongke', [ThongkeController::class, 'dashboard']);
});
