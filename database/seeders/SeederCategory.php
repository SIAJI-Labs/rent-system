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
            'Monitor',
            'Mechanical Keyboard',
            'Kursi Gaming',
            'Mouse'
        ];

        // Get all files in a directory
        $file_location = public_path().'/'.'images/category';
        $files = \File::files($file_location);
        $directories = \File::directories($file_location);
        // Delete Files
        foreach($files as $file){
            \File::delete($file);
        }
        foreach($directories as $dir){
            \File::deleteDirectory($dir);
        }
        // Check if Target Dir is Exists
        if(!(\File::exists($file_location))){
            // Create Directory
            \File::makeDirectory($file_location, $mode = 0777, true, true);
        }

        foreach($category as $key => $item){
            // Fake Image
            $faker_file = public_path().'/'.'assets/images/siaji-logo.jpeg';
            $faker_extension = pathinfo($faker_file, PATHINFO_EXTENSION);

            Category::create([
                'name' => $item,
                'pict' => 'seeder-category_'.$key.'.'.$faker_extension
            ]);

            \File::copy($faker_file, $file_location.'/'.'seeder-category_'.$key.'.'.$faker_extension);
        }
    }
}
