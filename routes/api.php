<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::controller(UserController::class)
    ->prefix('users')
    ->as('users.')
    ->group(function (){
        Route::get('','index')->name('index');
        Route::get('/{user}','show')->name('show');
        Route::post('','store')->name('store');
        Route::put('/{user}','update')->name('put');
        Route::delete('/{user}','destroy')->name('destroy');
    });
