<?php

use App\Http\Controllers\Admin\CategoryController;
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

        Route::group([
            'prefix' => 'product',
            'as' => 'product.'
        ], function(){
            // Product - Brand
            Route::resource('brand', \BrandController::class);
            // Product - Category
            Route::resource('category', \CategoryController::class);

            // Product - Detail
            Route::resource('{product}/serial-number', \ProductDetailController::class)->only([
                'create', 'store', 'edit', 'update'
            ]);
        });
        Route::resource('product', \ProductController::class);

        // Store
        Route::resource('store', \StoreController::class);

        // JSON
        Route::group([
            'prefix' => 'json',
            'as' => 'json.'
        ], function(){
            // Select2
            Route::group([
                'prefix' => 'select2',
                'as' => 'select2.'
            ], function(){
                Route::group([
                    'prefix' => 'product',
                    'as' => 'product.'
                ], function(){
                    // Product - Brand
                    Route::get('brand', [\App\Http\Controllers\Admin\BrandController::class, 'select2'])->name('brand.all');
                    // Product - Category
                    Route::get('category', [\App\Http\Controllers\Admin\CategoryController::class, 'select2'])->name('category.all');
                });

                // Toko
                Route::get('store', [\App\Http\Controllers\Admin\StoreController::class, 'select2'])->name('store.all');
            });

            // Datatable
            Route::group([
                'prefix' => 'datatable',
                'as' => 'datatable.'
            ], function(){
                Route::group([
                    'prefix' => 'product',
                    'as' => 'product.'
                ], function(){
                    // Product - Brand
                    Route::get('brand', [\App\Http\Controllers\Admin\BrandController::class, 'datatableAll'])->name('brand.all');
                    // Product - Category
                    Route::get('category', [\App\Http\Controllers\Admin\CategoryController::class, 'datatableAll'])->name('category.all');
                    // Product - Serial Number / Detail
                    Route::get('{product}/serial-number', [\App\Http\Controllers\Admin\ProductDetailController::class, 'datatableAll'])->name('serial-number.all');
                });
                Route::get('product', [\App\Http\Controllers\Admin\ProductController::class, 'datatableAll'])->name('product.all');

                // Store
                Route::get('store', [\App\Http\Controllers\Admin\StoreController::class, 'datatableAll'])->name('store.all');
            });
        });
    });
});