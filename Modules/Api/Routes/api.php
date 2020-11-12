<?php

use Illuminate\Http\Request;

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

Route::middleware('auth:api')->get('/api', function (Request $request) {
    return $request->user();
});

Route::prefix('admin/v1')->group(function() {
    Route::post('/make-category', 'ApiController@create');
    Route::get('/get_menu', 'ApiController@getPanigationMenu');
    Route::post('/insert_data', 'ApiController@insert');
});