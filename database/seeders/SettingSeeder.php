<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $st1= ["name" => "student message"];
        $st2 = ["name" => "teacher message"];
        $st3= ["name" => "stranger message"];
        DB::table("settings")->insert([$st1, $st2, $st3]);
    }
}
