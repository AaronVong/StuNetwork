<?php

namespace App\Http\Controllers;

use App\Models\Toast;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public function index(Request $request){
        $userCount = User::all()->whereNotNull("email_verified_at")->count();
        $toastCount = Toast::all()->count();
        $memeberCount = User::role("super-admin")->count();
        # danh sách user không phải là admin
        $usersNotAdmin = User::with(["profile", "roles", "permissions:name"])->whereDoesntHave('roles', function($q){
            $q->where('name', 'super-admin');
        })->paginate(6);
        $toasts = Toast::with(["user", "files"])->orderBy("id", "desc")->paginate(6);
        return view("admin.dashboard",["userCount" => $userCount, "toastCount" => $toastCount, "memberCount" => $memeberCount, "users" => $usersNotAdmin, "toasts"=>$toasts]);
    }

   public function getListUsers(Request $request){
       if($request->user()->hasRole("super-admin")){
            $usersNotAdmin = User::with(["profile", "roles"])->whereDoesntHave('roles', function($q){
                $q->where('name', 'super-admin');
            })->paginate(10);
            return response(["users" => $usersNotAdmin], 200);
        }
       return response(["message" => "Không có quyền truy cập"], 403);
   }

   public function getListToasts(Request $request){
        if($request->user()->hasRole("super-admin")){
            $toasts = Toast::with(["user", "files"])->orderBy("id", "desc")->paginate(6);
            return $toasts->currentPage() <= $toasts->lastPage() ? response(["toasts" => $toasts], 200) : response([],204);
        }
       return response(["message" => "Không có quyền truy cập"], 403);
   }
   public function userDetail(Request $request, $username){
        if($request->user()->hasRole("super-admin")){
           $user = User::with(["profile","rolesWithPermissions"])->where("username", $username)->first();
           $userPermissions = Role::findByName("user")->permissions;
            return view("admin.user-detail",["user" => $user, "userPermissions" => $userPermissions]);
        }
   }

   public function loginPermission(Request $requesy, $username){
       $user = User::where("username", $username)->first();
       if(!$user){
           return response(["message" => "Không tìm thấy tài khoản"], 404);
       }

       $revoke = false;
       if($user->hasPermissionTo('login')){
           $user->revokePermissionTo('login');
           $revoke = true;
       }
       else{
           $user->givePermissionTo('login');
       }
       return response(["message" => "Thành công", "revoke" => $revoke], 200);
   }

   public function editUserAccount(Request $request, $username){
       $user = User::where("username",$username)->first();
       if(!$user){
            return response(["message" => "Không tìm thấy tài khoản"], 404);
       }

       foreach($request->updatedPermissions as $key => $value){
           if($value["checked"]===true){
                $user->givePermissionTo($key);
           }else{
               $user->revokePermissionTo($key);
           }
       }
       return response(["message"=>"thành công"],200);
   }
}
