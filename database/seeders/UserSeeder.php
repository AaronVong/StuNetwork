<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        # Tạo user
        $user1 = [
            "username" => "minhvong",
            "email"=>"minh.vongquyen@student.stu.edu.vn",
            "password" => Hash::make("vongquyenminh"),
            "email_verified_at" => Carbon::now()->toDateTimeString(),
        ];
        $user2= [
            "username" => "hieule",
            "email"=>"hieu.levan@student.stu.edu.vn",
            "password" => Hash::make("vongquyenminh"),
            "email_verified_at" => Carbon::now()->toDateTimeString(),
        ];
        $user3=[
            "username" => "anhnguyen",
            "email"=>"anh.chaunguyenquoc@student.stu.edu.vn",
            "password" => Hash::make("vongquyenminh"),
            "email_verified_at" => Carbon::now()->toDateTimeString(),
        ];
        DB::table("users")->insert([$user1, $user2, $user3]);

        # Cấp quyền cho từng user
        $userRole = Role::findByName("user");
        $userPermissions = $userRole->permissions()->get();
        $admin = User::find(1);
        $admin->assignRole(["super-admin"]);
        $admin->givePermissionTo($userPermissions);
        User::find(2)->givePermissionTo($userPermissions);
        User::find(3)->givePermissionTo($userPermissions);

        # Tạo profile cho từng user
        $user1Profile = [
            "user_id" => 1,
            "fullname" => "Vòng Quyền Minh"
        ];
        $user2Profile = [
            "user_id" => 2,
            "fullname" => "Lê Văn Hiếu"
        ];
        $user3Profile = [
            "fullname" => "Chẫu Nguyễn Quốc Anh",
            "user_id" => 3,
        ];
        DB::table("profiles")->insert([$user1Profile, $user2Profile, $user3Profile]);

        # Gán setting cho từng user
        $settings = Setting::all();
        $users = User::all();
        foreach($users as $user){
            foreach($settings as $setting){
                $user->settings()->attach($setting->id, ["value"=> true]);
            }
        }
    }
}
