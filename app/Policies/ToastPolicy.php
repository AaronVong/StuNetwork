<?php

namespace App\Policies;

use App\Models\Toast;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class ToastPolicy
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

    public function delete(User $deleter, Toast $toast){
        return $deleter->id === $toast->user_id ? Response::allow('',200) : Response::deny("Bạn không có quyền xóa toast này!",403);
    }

    public function owned(User $actor, Toast $toast){
        return $actor->id === $toast->user_id ? Response::allow('',200) : Response::deny("Bạn không sở hữu toast này!",403);
    }

    public function update(User $actor, Toast $toast){
        return $actor->id === $toast->user_id ? Response::allow('',200) : Response::deny("Bạn không có quyền cập nhật toast này!",403);
    }
}
