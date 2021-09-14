<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SeederPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        \DB::table('model_has_permissions')->truncate();
        \Spatie\Permission\Models\Permission::truncate();
        app()->make(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
        Schema::enableForeignKeyConstraints();

        $permissions = config('global.permission');
        foreach($permissions as $key => $permission){
            foreach($permission['permission'] as $data){
                $value = $key.'-'.$data['value'];
                $dbPermission = \Spatie\Permission\Models\Permission::whereName($value)->first();

                // \Log::debug("Check on Permission Seeder", [
                //     'data' => $data,
                //     'permission' => $permission,
                //     'value' => $value
                // ]);

                if(empty($dbPermission)){
                    \Spatie\Permission\Models\Permission::create(['name' => $value]);
                }
            }
        }
    }
}
