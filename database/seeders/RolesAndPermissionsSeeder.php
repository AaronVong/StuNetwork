<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'upload toast']);
        Permission::create(['name' => 'upload file']);
        Permission::create(['name' => 'edit toast']);
        Permission::create(['name' => 'delete file']);
        Permission::create(['name' => 'delete toast']);
        Permission::create(['name' => 'comment toast']);
        Permission::create(['name' => 'like']);
        Permission::create(['name' => 'follow']);
        Permission::create(['name' => 'login']);
        Permission::create(['name' => 'send message']);
        Permission::create(['name' => 'delete message']);
        Permission::create(['name' => 'create role']);
        Permission::create(['name' => 'create user']);
        Permission::create(['name' => 'edit profile']);
        
        // create roles and assign created permissions
        $role = Role::create(['name' => 'user']);
        $role->givePermissionTo([
            "upload toast",
            "upload file",
            "edit toast",
            "delete toast",
            "delete file",
            "comment toast",
            "like",
            "follow",
            "send message",
            "delete message",
            "edit profile",
            "login"
        ]);

        $role = Role::create(['name' => 'super-admin']);
        $role->givePermissionTo(["create user", "create role"]);
    }
}
