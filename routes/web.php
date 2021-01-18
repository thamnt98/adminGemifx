<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/register', 'Auth\RegisterController@main')->name('register');
Route::post('/register', 'Auth\HandleRegisterController@main')->name('register');
Route::get('/login', 'Auth\LoginController@main')->name('login');
Route::post('/login', 'Auth\HandleLoginController@main')->name('login');

Route::group([
    'namespace' => 'Admin',
    'middleware' => 'auth'
], function () {
    Route::get('/logout', 'LogoutController@main')->name('logout');
    Route::get('/dashboard', 'DashboardController@main')->name('dashboard');
    Route::get('/', 'DashboardController@main')->name('home');
});
