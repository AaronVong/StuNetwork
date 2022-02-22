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
        return $user->hasPermissionTo('edit toast') ? Response::allow('',200) : Response::deny("Tài khoản đã bị khóa chức năng chỉnh sửa toast",403);
    }

    public function canUploadFiles(User $user){
        return $user->hasPermissionTo('upload file') ? Response::allow('',200) : Response::deny("Tài khoản đã bị khóa chức năng upload file",403);
    }

    public function canDeleteToast(User $user){
        return $user->hasPermissionTo('delete toast') ? Response::allow('',200) : Response::deny("Tài khoản đã bị khóa chức năng xóa toast",403);
    }

    public function canEditProfile(User $user){
        return $user->hasPermissionTo('edit profile') ? Response::allow('',200) : Response::deny("Tài khoản đã bị khóa chức năng cập nhật profile",403);
    }

    public function canCommentToast(User $user){
        return $user->hasPermissionTo('comment toast') ? Response::allow('',200) : Response::deny("Tài khoản đã bị khóa chức năng bỉnh luận toast",403);
    }

    public function canDeleteFile(User $user){
        return $user->hasPermissionTo('delete file') ? Response::allow('',200) : Response::deny("Tài khoản đã bị khóa chức năng xóa file",403);
    }

    public function canSendMessage(User $user){
        return $user->hasPermissionTo('send message') ? Response::allow('',200) : Response::deny("Tài khoản đã bị khóa chức năng gửi tin nhắn",403);
    }

    public function canDeleteMessage(User $user){
        return $user->hasPermissionTo('delete message') ? Response::allow('',200) : Response::deny("Tài khoản đã bị khóa chức năng xóa tin nhắn",403);
    }

    public function changePassword(User $user){
        return $user->id == auth()->user()->id ? Response::allow('',200) : Response::deny("Xác minh thất bại",403);
    }
}
