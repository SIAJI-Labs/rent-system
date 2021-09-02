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
            'Condong Catur',
            'Kotagede',
            'Taman Siswa'  
        ];
        foreach($store as $item){
            Store::create([
                'name' => $item,
                'is_active' => true
            ]);
        }
    }
}
