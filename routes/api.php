<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompteController;
use App\Http\Controllers\TransactionsController;
use App\Http\Controllers\AuthController;

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

Route::group(['middleware' => ['auth:sanctum']], function () {
    // logout route api code here
    Route::post('/auth/logout', [AuthController::class,'logout']);
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

Route::controller(CompteController::class)
    ->prefix('comptes')
    ->as('comptes.')
    ->group(function (){
        Route::get('','index')->name('index');
        Route::get('/{compte}','show')->name('show');
        Route::post('','store')->name('store');
        Route::put('/{compte}','update')->name('put');
        Route::delete('/{compte}','destroy')->name('destroy');
    });


Route::controller(TransactionsController::class)
    ->prefix('transactions')
    ->as('transactions.')
    ->group(function (){
        Route::get('','index')->name('index');
        Route::get('/{transaction}','show')->name('show');
        Route::post('','store')->name('store');
        Route::put('/{transaction}','update')->name('put');
        Route::delete('/{transaction}','destroy')->name('destroy');
    });

    Route::post('/auth/login', [AuthController::class,'login']);


