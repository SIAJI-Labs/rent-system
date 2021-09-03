<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductPict;
use App\Models\ProductDetail;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeederProduct extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        ProductDetail::truncate();
        ProductPict::truncate();
        Product::truncate();
        Schema::enableForeignKeyConstraints();

        $product = [
            [
                'brand' => 1,
                'category' => 4,
                'name' => 'Logitech G102',
                'price' => 75000,
                'serial_number' => [
                    [
                        'store' => 1,
                        'amount' => 5,
                        'prefix' => 'LGT102'
                    ], [
                        'store' => 2,
                        'amount' => 0,
                        'prefix' => 'LGTG102'
                    ], [
                        'store' => 3,
                        'amount' => 3,
                        'prefix' => 'LGG102'
                    ], 
                ]
            ], [
                'brand' => 4,
                'category' => 2,
                'name' => 'Fantech Legionare',
                'price' => 150000,
                'serial_number' => [
                    [
                        'store' => 1,
                        'amount' => 1,
                        'prefix' => 'LGR'
                    ], [
                        'store' => 2,
                        'amount' => 10,
                        'prefix' => 'FLG'
                    ], [
                        'store' => 3,
                        'amount' => 5,
                        'prefix' => 'FLG'
                    ], 
                ]
            ]
        ];

        foreach($product as $item){
            $data = \App\Models\Product::create([
                'category_id' => $item['category'],
                'brand_id' => $item['brand'],
                'name' => $item['name'],
                'price' => $item['price'],
                'description' => null
            ]);

            // Serial Number
            foreach($item['serial_number'] as $serialNumber){
                for($i = 0; $i < $serialNumber['amount']; $i++){
                    $sn = \App\Models\ProductDetail::create([
                        'store_id' => $serialNumber['store'],
                        'product_id' => $data->id,
                        'serial_number' => $serialNumber['prefix'].str_pad($i, 6, '0', STR_PAD_LEFT),
                        'status' => true,
                        'note' => null
                    ]); 
                }  
            }
        }
    }
}
