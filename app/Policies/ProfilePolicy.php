<?php

namespace App\Policies;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
class ProfilePolicy
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

    public function update(User $actor, Profile $profile){
        return $actor->id == $profile->user_id ? Response::allow() : Response::deny("Bạn không có quyền chỉnh sửa profile này!");
    }

    public function follow(User $actor, Profile $profile){
        return ($actor->profile->id != $profile->id) ? Response::allow() : Response::deny("Không thể theo dõi profile này");
    }
}
