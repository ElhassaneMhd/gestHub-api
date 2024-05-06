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
        Permission::create(['guard_name'=>'sanctum','name' => 'store admins']);

        Role::create(['name' => 'supervisor']);
        Role::create(['guard_name'=>'sanctum', 'name' => 'supervisor']);

        Role::create(['name' => 'admin']);
        Role::create(['guard_name'=>'sanctum','name' => 'admin']);
        
        Role::create(['name' => 'intern']);
        Role::create(['guard_name'=>'sanctum','name' => 'intern']);

        Role::create(['name' => 'user']);
        Role::create(['guard_name'=>'sanctum','name' => 'user']);
        
        Role::create(['name' => 'super-admin'])->givePermissionTo('store admins');
        Role::create(['guard_name'=>'sanctum','name' => 'super-admin'])->givePermissionTo('store admins');
    }
}