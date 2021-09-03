<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeederBrand extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Brand::truncate();
        Schema::enableForeignKeyConstraints();

        $category = [
            'Logitech',
            'Benq',
            'AOC',
            'Fantech',
            'Secret Lab'
        ];
        foreach($category as $item){
            Brand::create([
                'name' => $item,
            ]);
        }
    }
}
