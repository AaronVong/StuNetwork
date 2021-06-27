<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Toast;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth"]);
    }

    public function index(Request $request){
        // $toasts = Toast::with(["user:id,username","user.profile:user_id,id,fullname,avatarUrl", "files", "likes"])->withCount("toastComments","likes")->orderBy("created_at", "desc")->paginate(10)->items();
        $toasts = Toast::with(["user:id,username","ownerProfile:user_id,id,fullname,avatarUrl", "files"])->withCount(["likes as liked" => function (Builder $query){
            $query->where("user_id", auth()->user()->id);
        }, "toastComments as commentsCount", "ownerProfile as followed" => function(Builder $query){
            $query->whereHas("followers", function($follower){
                $follower->where("follower_id", auth()->user()->id);
            });
        },"likes as likesCount"])->orderBy("created_at", "desc")->paginate(10)->items();
        return view("client/pages.home",["toasts" => $toasts]);
    }
}
