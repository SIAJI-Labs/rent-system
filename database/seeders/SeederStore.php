<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Store;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeederStore extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Store::truncate();
        Schema::enableForeignKeyConstraints();

        $store = [
            'Malioboro',
            'Gedong Kuning',
            'Pleret'  
        ];
        $color = [
            '#F0F465',
            '#50C5B7',
            '#533A71'
        ];
        foreach($store as $key => $item){
            $store = Store::create([
                'name' => $item,
                'invoice_prefix' => substr(strtoupper(str_replace(' ', '', $item)), 0, 6),
                'chart_hex_color' => $color[$key],
                'chart_rgb_color' => convertHexToRgb($color[$key]),
                'is_active' => true
            ]);

            // Create User
            $staff = Admin::create([
                'store_id' => $store->id,
                'name' => 'Toko '.$item,
                'username' => strtolower(str_replace(' ', '-', $item)),
                'email' => 'staff.'.strtolower(str_replace(' ', '-', $item)).'@mail.com',
                'password' => bcrypt(strtolower(str_replace(' ', '-', $item))),
                'raw_password' => strtolower(str_replace(' ', '-', $item)),
                'is_active' => true,
                'is_admin' => false
            ]);

            $basicPermission = [
                'brand-list',
                'category-list',
                'product-list',
                'product_detail-list',
                'transaction-list',
                'transaction-create',
                'transaction-edit'
            ];
            $staff->givePermissionTo($basicPermission);
        }
    }
}
