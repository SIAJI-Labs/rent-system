<?php

namespace Database\Seeders;

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
        foreach($store as $item){
            Store::create([
                'name' => $item,
                'invoice_prefix' => substr(strtoupper(str_replace(' ', '', $item)), 0, 6),
                'is_active' => true
            ]);
        }
    }
}
