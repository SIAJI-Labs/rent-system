<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeederCategory extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        Category::truncate();
        Schema::enableForeignKeyConstraints();

        $category = [
            'Kamera DSLR',
            'Webcam',
            'Lensa',
            'Tripod',
            'Stabilizer'
        ];
        foreach($category as $item){
            Category::create([
                'name' => $item,
            ]);
        }
    }
}
