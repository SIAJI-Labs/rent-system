<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(\Database\Seeders\SeederUser::class);
        $this->call(\Database\Seeders\SeederAdmin::class);
        $this->call(\Database\Seeders\SeederStore::class);
        $this->call(\Database\Seeders\SeederBrand::class);
        $this->call(\Database\Seeders\SeederCategory::class);
    }
}
