<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EditorJsController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout']);

Route::middleware('jwt.auth')->group(function () {

    Route::get('blogs', [BlogController::class, 'index']);

    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('add/category', [CategoryController::class, 'store']);
    Route::get('edit/category/{id}', [CategoryController::class, 'show']);
    Route::put('update/category/{id}', [CategoryController::class, 'update']);
    Route::delete('delete/category/{id}', [CategoryController::class, 'destroy']);

    Route::post('add/blog', [BlogController::class, 'store']);

});
Route::post('upload/blog/image', [BlogController::class, 'uploadBlogImage']);
Route::get('fetch/url/metadata', [EditorJsController::class, 'fetchUrlMetadata']);
