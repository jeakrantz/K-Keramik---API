<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;

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

//Documenthantering, produkthantering, kategorihantering, kräver inlogg.
Route::resource('documents', DocumentController::class)->middleware('auth:sanctum');
Route::resource('products', ProductController::class)->middleware('auth:sanctum');
Route::resource('categories', CategoryController::class)->middleware('auth:sanctum');

//Hantera kategorier och produkter i dessa kategorier, samt söka efter produkter, kräver inlogg
Route::get('/products/search/name/{name}', [ProductController::class, 'searchProduct'])->middleware('auth:sanctum');
Route::post('/addcategory', [CategoryController::class, 'addCategory'])->middleware('auth:sanctum');
Route::post('/addproduct/{id}', [CategoryController::class, 'addProduct'])->middleware('auth:sanctum');
Route::post('/editproduct/{id}', [ProductController::class, 'editProduct'])->middleware('auth:sanctum');
Route::get('/getproducts/{id}', [CategoryController::class, 'getProductsByCategory'])->middleware('auth:sanctum');

//Logga ut en användare, kräver inlogg
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//För att registrera användare och logga in
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
