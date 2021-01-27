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
        Route::post('/create', 'StoreController@main')->name('user.store');
        Route::get('/detail/{id}', 'DetailController@main')->name('user.detail');
        Route::post('/update/{id}', 'UpdateController@main')->name('user.update');
        Route::post('/delete/{id}', 'DeleteController@main')->name('user.delete');
    });
    Route::group([
        'namespace' => 'Account',
        'prefix' => 'account',
    ], function () {
        Route::get('/live', 'LiveListController@main')->name('account.live');
        Route::post('/delete/{login}', 'DeleteLiveAccountController@main')->name('account.live.delete');
        Route::get('/create/{id}', 'CreateLiveAccountController@main')->name('account.live.create');
        Route::post('/create', 'OpenLiveAccountController@main')->name('account.live.open');
        Route::get('/detail/{id}', 'DetailLiveAccountController@main')->name('account.live.detail');
        Route::post('/detail/{id}', 'UpdateLiveAccountController@main')->name('account.live.update');
    });
    Route::group([
        'namespace' => 'Deposit',
        'prefix' => 'deposit',
        'middleware' => 'role.admin'
    ], function () {
        Route::get('/list', 'ListController@main')->name('deposit.list');
        Route::post('/approve/{id}', 'ApproveController@main')->name('deposit.approve');
    });
    Route::group([
        'namespace' => 'Withdrawal',
        'prefix' => 'withdrawal',
        'middleware' => 'role.admin'
    ], function () {
        Route::get('/list', 'ListController@main')->name('withdrawal.list');
        Route::post('/approve/{id}', 'ApproveController@main')->name('withdrawal.approve');
    });
});
