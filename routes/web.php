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

Route::group([
    'as' => 'public.'
], function(){
    Route::get('/', function () {
        return view('content.public.homepage.index');
    })->name('index');
});

Auth::routes();

Route::group([
    'middleware' => ['web', 'auth']
], function(){
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});