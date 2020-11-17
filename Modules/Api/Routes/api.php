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
    Route::get('/list-menu', 'IDController@get_menu');
    Route::post('/up-id', 'IDController@insert');
});

Route::prefix('admin/v2')->group(function() {
    Route::post('/make-category', 'Api1Controller@create');
    Route::get('/get_menu', 'Api1Controller@getPanigationMenu');
    Route::post('/insert_data', 'Api1Controller@insert');
    Route::get('/list_main', 'Api1Controller@check');
});