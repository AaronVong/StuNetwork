<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                "name" => "student"
            ],
            [
                "name" => "lecturer"
            ],
            [
                "name" => "administrator"
            ]
            ];
        DB::table("roles")->insert($roles);
    }
}
