<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function canUploadToast(User $user){
        return $user->hasPermissionTo('upload toast') ? Response::allow('',200) : Response::deny("Tài khoản đã bị khóa chức năng upload toast",403);
    }
    
    public function canEditToast(User $user){
        return $user->hasPermissionTo('edit toast') ? Response::allow('',200) : Response::deny("Tài khoản đã bị khóa chức năng edit toast",403);
    }

    public function canUploadFiles(User $user){
        return $user->hasPermissionTo('upload file') ? Response::allow('',200) : Response::deny("Tài khoản đã bị khóa chức năng upload file",403);
    }

    public function canDeleteToast(User $user){
        return $user->hasPermissionTo('delete toast') ? Response::allow('',200) : Response::deny("Tài khoản đã bị khóa chức năng delete file",403);
    }
}
