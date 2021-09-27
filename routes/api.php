<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\GigsController;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', 'Auth\AuthController@login')->name('login');
    Route::post('register', 'Auth\AuthController@register');
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('user', 'Auth\AuthController@user');
        Route::put('/update/{id}', 'Auth\AuthController@update');
        Route::get('logout', 'Auth\AuthController@logout');
    });
});


Route::group([
    'middleware' => 'auth:api'
], function () {
    // Route::apiResource('companies', 'CompaniesController');
    Route::get('companies', [CompaniesController::class, 'index']);
    Route::get('companies/{company}', [CompaniesController::class, 'show']);
    Route::post('companies', [CompaniesController::class, 'store']);
    Route::put('/companies/{company}', [CompaniesController::class, 'update']);
    Route::delete('/companies/{company}', [CompaniesController::class, 'destroy']);


    Route::get('gigs', [GigsController::class, 'index']);
    Route::get('gigs/{gig}', [GigsController::class, 'show']);
    Route::post('gigs', [GigsController::class, 'store']);
    Route::put('/gigs/{gig}', [GigsController::class, 'update']);
    Route::delete('/gigs/{gig}', [GigsController::class, 'destroy']);
});


