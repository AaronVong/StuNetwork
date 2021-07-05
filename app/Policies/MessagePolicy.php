<?php

namespace App\Policies;

use App\Models\Message;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class MessagePolicy
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

    public function delete(User $actor, Message $message){
        return $actor->id == $message->sender_id ? Response::allow("", 200) : Response::deny("Bạn không có quyền xóa tin nhắn này", 401);
    }
}
