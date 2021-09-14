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
        // Get Protected Image
        Route::get('protected-image/{type}/{filename}', function($type, $filename){
            $path = null;
            switch($type){
                case 'customer':
                    $path = 'files/customer';
                    break;
            }

            $path .= '/'.$filename;
            if(\Storage::exists($path)){
                $type = pathinfo($path, PATHINFO_EXTENSION);
                // $image = $base64 = 'data:image/' . $type . ';base64,' . base64_encode(\Storage::get($real_path));
                header('Content-Type: image/'.$type);
                return \Storage::get($path);
            }
            
            return response()->json([
                'status' => false,
                'message' => 'Maaf, anda tidak memiliki ijin untuk mengakses data terkait'
            ]);
        })->name('protected.images');

        // Home / Dashboard
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

        // Customer
        Route::resource('customer', \CustomerController::class);
        // Store
        Route::resource('store', \StoreController::class);
        // Staff
        Route::resource('staff', \StaffController::class);

        // Transaction
        Route::resource('transaction', \TransactionController::class);

        // Accounting
        Route::get('accounting/{year}/{month}/{date}', [\App\Http\Controllers\Admin\AccountingController::class, 'detail'])->name('accounting.detail');
        Route::get('accounting/{year}/{month}', [\App\Http\Controllers\Admin\AccountingController::class, 'daily'])->name('accounting.daily');
        Route::get('accounting/{year}', [\App\Http\Controllers\Admin\AccountingController::class, 'monthly'])->name('accounting.monthly');
        Route::get('accounting', [\App\Http\Controllers\Admin\AccountingController::class, 'yearly'])->name('accounting.yearly');

        // Website Configuration
        Route::resource('website-configuration', \WebsiteConfiguration::class)->only([
            'index', 'store'
        ]);

        /**
         * Json Data
         * 
         */
        Route::group([
            'prefix' => 'json',
            'as' => 'json.'
        ], function(){
            // Home - Statistic
            Route::group([
                'prefix' => 'statistic',
                'as' => 'statistic.'
            ], function(){
                // Transaction
                Route::get('transaction', [\App\Http\Controllers\Admin\HomeStatisticController::class, 'jsonTransactionStatistic'])->name('transaction');
                // Cashflow
                Route::get('cashflow', [\App\Http\Controllers\Admin\HomeStatisticController::class, 'jsonCashflowStatistic'])->name('cashflow');
            });
            Route::get('transaction-list', [\App\Http\Controllers\Admin\HomeStatisticController::class, 'jsonTransactionList'])->name('transaction.list');

            Route::get('transaction/{transactionId}/item', [\App\Http\Controllers\Admin\TransactionItemController::class, 'jsonIndex'])->name('transaction.item.index');
            Route::get('transaction/{transactionId}/item/{id}', [\App\Http\Controllers\Admin\TransactionItemController::class, 'jsonShow'])->name('transaction.item.show');

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
                    // Product - SN
                    Route::get('serial-number', [\App\Http\Controllers\Admin\ProductDetailController::class, 'select2'])->name('serial-number.all');
                });
                // Produk
                Route::get('product', [\App\Http\Controllers\Admin\ProductController::class, 'select2'])->name('product.all');

                // Toko
                Route::get('store', [\App\Http\Controllers\Admin\StoreController::class, 'select2'])->name('store.all');
                // Kostumer
                Route::get('customer', [\App\Http\Controllers\Admin\CustomerController::class, 'select2'])->name('customer.all');
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

                // Customer
                Route::get('customer', [\App\Http\Controllers\Admin\CustomerController::class, 'datatableAll'])->name('customer.all');
                // Store
                Route::get('store', [\App\Http\Controllers\Admin\StoreController::class, 'datatableAll'])->name('store.all');
                // Staff
                Route::get('staff', [\App\Http\Controllers\Admin\StaffController::class, 'datatableAll'])->name('staff.all');

                // Transaction Item
                Route::get('transaction/{transactionId}/item', [\App\Http\Controllers\Admin\TransactionItemController::class, 'datatableAll'])->name('transaction.item.all');
                // Transaction Accounting
                Route::get('transaction/{transactionId}/accounting', [\App\Http\Controllers\Admin\TransactionController::class, 'datatableAccounting'])->name('transaction.accounting.all');
                // Transaction
                Route::get('transaction', [\App\Http\Controllers\Admin\TransactionController::class, 'datatableAll'])->name('transaction.all');

                // Accounting
                Route::get('accounting/{year}/{month}/{date}', [\App\Http\Controllers\Admin\AccountingController::class, 'datatableDetail'])->name('accounting.detail');
                Route::get('accounting/{year}/{month}', [\App\Http\Controllers\Admin\AccountingController::class, 'datatableDaily'])->name('accounting.daily');
                Route::get('accounting/{year}', [\App\Http\Controllers\Admin\AccountingController::class, 'datatableMonthly'])->name('accounting.monthly');
                Route::get('accounting', [\App\Http\Controllers\Admin\AccountingController::class, 'datatableYearly'])->name('accounting.yearly');
            });
        });
    });
});