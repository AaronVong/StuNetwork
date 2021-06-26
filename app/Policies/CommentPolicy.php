<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;
use Laravelista\Comments\Comment;

class CommentPolicy
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
    

    public function delete(User $actor, Comment $comment){
        # commenter_id default is String
        return $actor->id == $comment->commenter_id ? Response::allow("", 200) : Response::deny("Bạn không có quyền xóa comment này!", 401);
    }

    public function update(User $actor, Comment $comment){
        # commenter_id default is String
        return $actor->id == $comment->commenter_id ? Response::allow("",200) : Response::deny("Bạn không có quyền chỉnh sửa comment này!", 401);
    }
}
