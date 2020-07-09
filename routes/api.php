<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('uploadFile', 'VideosController@uploadFile');


Route::group(['prefix' => 'video'], function () {

    Route::post('saveVideoToServer', 'VideosController@saveVideoToServer');
    Route::post('getRecommendedVideos', 'VideosController@getRecommendedVideos');
    Route::post('getUserVideos', 'VideosController@getUserVideos');
    Route::post('getSoundVideos', 'VideosController@getSoundVideos');
    Route::post('getAudio', 'VideosController@getAudio');
});
Route::group(['prefix' => 'user'], function () {

    Route::post('registerTempUser', 'UserController@registerTempUser');
    Route::post('loginUser', 'UserController@loginUser');
});
Route::group(['prefix' => 'sounds'], function () {

    Route::post('getAllSounds', 'SoundsController@getAllSounds');
});
Route::group(['prefix' => 'comments'], function () {

    Route::post('getVideoComments', 'CommentsController@getVideoComments');
    Route::post('postComment', 'CommentsController@postComment');
});