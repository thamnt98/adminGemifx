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
    'middleware' => 'auth',
    'prefix' => 'admin',
], function () {
    Route::get('/logout', 'LogoutController@main')->name('logout');
    Route::get('/dashboard', 'DashboardController@main')->name('dashboard');
    Route::get('/', 'DashboardController@main')->name('home');
    Route::group([
        'namespace' => 'User',
        'prefix' => 'user',
    ], function () {
        Route::get('/list', 'ListController@main')->name('user.list');
        Route::get('/create', 'CreateController@main')->name('user.create');
        Route::get('/detail/{id}', 'DetailController@main')->name('user.detail');
        Route::post('/delete/{id}', 'DeleteController@main')->name('user.delete');
    });
    Route::group([
        'namespace' => 'Account',
        'prefix' => 'account',
    ], function () {
        Route::get('/live', 'LiveListController@main')->name('account.live');
        Route::post('/delete/{login}', 'DeleteLiveAccountController@main')->name('account.live.delete');
        Route::get('/create', 'CreateLiveAccountController@main')->name('account.live.create');
        Route::get('/detail/{id}', 'DetailLiveAccountController@main')->name('account.live.detail');
    });
});
