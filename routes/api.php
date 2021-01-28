<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Category;
use App\http\Controllers\rel_user_category;
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

Route::group(['prefix' => 'messages'], function () {
	Route::get('/', ['as' => 'messages', 'uses' => 'MessagesController@index']);
	Route::get('create', ['as' => 'messages.create', 'uses' => 'MessagesController@create']);
	Route::post('/', ['as' => 'messages.store', 'uses' => 'MessagesController@store']);
	Route::get('{id}', ['as' => 'messages.show', 'uses' => 'MessagesController@show']);
	Route::put('{id}', ['as' => 'messages.update', 'uses' => 'MessagesController@update']);
});

Route::group([    
    'namespace' => 'Auth',    
    'middleware' => 'api',    
    'prefix' => 'password'
], function () {    
    Route::post('create', 'PasswordResetController@create');
    Route::get('find/{token}', 'PasswordResetController@find');
    Route::post('reset', 'PasswordResetController@reset');
});

//unauthenticated APIs routes
Route::post('/login',[UserController::class, 'login']);
Route::post('/register',[UserController::class, 'register']);
Route::post('/logout',[UserController::class, 'logout']);

Route::post('reset_password_without_token', 'AccountsController@validatePasswordRequest');
Route::post('reset_password_with_token', 'AccountsController@resetPassword');


//authenticated APIs routes
Route::group(['middleware'=>'auth:api'],function (){
	Route::post('/order',[OrderController::class, 'store']);
	Route::get('/orders',[OrderController::class, 'index']);
	Route::post('/order/{order}',[OrderController::class, 'update']);
	Route::put('/orders/{order}/status',[OrderController::class, 'updateStatus']);
	Route::delete('/order/{order}',[OrderController::class, 'destroy']);
	Route::delete('/service/{service}',[rel_user_category::class, 'destroy']);
	Route::get('/user/stations',[UserController::class, 'getdeliveringstations']);
	Route::get('/orders/pending',[UserController::class, 'getpendingorders']);
	Route::get('/categories/user/available',[Category::class, 'getAvailableCategories']);
	Route::get('/rel_user_category',[rel_user_category::class, 'index']);
	Route::post('/category/user',[rel_user_category::class, 'store']);
	Route::get('/categories',[Category::class, 'index']);
	Route::post('/category',[Category::class, 'store']);
	Route::put('/category/{category}',[Category::class, 'update']);
	Route::get('/user/{user}',[rel_user_category::class, 'get']);
	Route::post('/user/location',[UserController::class, 'updateLocation']);
	Route::get('/orders/mpending',[OrderController::class, 'getmypendingorders']);
	Route::put('/rel_user_category/status',[rel_user_category::class, 'updateStatus']);
	Route::post('/users/update',[UserController::class, 'update']);
	Route::post('generate_token','ChatMessagesController@generateToken');
	Route::post('get_channel','ChatMessagesController@getChannel');
});