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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


//Route::get('/post/{slug}', 'BlogController@getPostBySlug')->where('slug', '[a-z0-9\-]+');

Route::apiResource('post', 'BlogController')->parameters(['post'=>'blog']);
//Route::get('/post', 'BlogController@index');
//Route::get('/post/{blog}', 'BlogController@show')->where('blog', '[0-9]+');
//Route::post('/post', 'BlogController@store');
//Route::put('/post/{blog}', 'BlogController@update');
//Route::delete('/post/{blog}', 'BlogController@destroy');
