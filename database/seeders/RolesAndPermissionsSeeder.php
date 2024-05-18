<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
    
        Permission::create(['name' => 'store admins']);

        Role::create(['name' => 'supervisor']);

        Role::create(['name' => 'admin']);
        
        Role::create(['name' => 'intern']);

        Role::create(['name' => 'user']);
        
        Role::create(['name' => 'super-admin'])->givePermissionTo('store admins');
    }
}