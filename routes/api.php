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
Route::post('register', 'UserController@register');
Route::post('login', 'UserController@authenticate');
Route::post('x', 'UserController@x');
Route::get('getUser', "UserController@getAuthenticatedUser");
Route::get('redirectTo', "UserController@redirectTo");
Route::get('token', "UserController@token");
 Route::get('userIndex', "UserController@userIndex");

Route::get('/', function () {
    return view('home');
});
Route::get('/denegado', function () {
    return view('noPermisos');
});

Route::group(['prefix' => 'admin','middleware' => ['role:admin','jwt.verify' ]], function() {
    /*AÑADE AQUI LAS RUTAS QUE QUIERAS PROTEGER CON JWT*/
    Route::get('/users', function () {
        return view('users');
    });

});
Route::group(['prefix' => 'cliente','middleware' => ['role:cliente', 'jwt.verify']], function() {
    /*AÑADE AQUI LAS RUTAS QUE QUIERAS PROTEGER CON JWT*/
    Route::get('/home', function () {
        return view('clientes');
    });

});
Route::group(['middleware' => 'jwt.verify'], function () {
    Route::post('/logout', 'UserController@logout');
});
Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();


});
