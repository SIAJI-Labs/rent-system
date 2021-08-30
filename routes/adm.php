<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group([
    'as' => 'adm.'
], function(){
    Auth::routes([
        'verify' => true
    ]);

    // Need Auth
    Route::group([
        'middleware' => ['web', 'auth:admin']
    ], function(){
        Route::get('/', 'HomeController')->name('index');
    });
});