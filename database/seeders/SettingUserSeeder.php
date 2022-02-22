<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hieule_st1 = [
            "setting_id" => 1,
            "user_id" => 2,
            "value" => true
        ];
        $hieule_st2 = [
            "setting_id" => 2,
            "user_id" => 2,
            "value" => true
        ];
        DB::table("setting_user")->insert([$hieule_st1,$hieule_st2]);
    }
}
