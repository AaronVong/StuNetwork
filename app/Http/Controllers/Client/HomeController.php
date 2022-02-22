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
        $followingList = auth()->user()->followings()->pluck("following_id");
        $followingList[] = auth()->user()->id;
        $toasts = Toast::with(["user:id,username","ownerProfile:user_id,id,fullname,avatarUrl", "files"])->withCount(["likes as liked" => function (Builder $query){
            $query->where("user_id", auth()->user()->id);
        }, "toastComments as commentsCount", "ownerProfile as followed" => function(Builder $query){
            $query->whereHas("followers", function($follower){
                $follower->where("follower_id", auth()->user()->id);
            });
        },"likes as likesCount"])->whereIn("user_id", $followingList)->orderBy("created_at", "desc")->paginate(5)->items();
        return view("client/pages.home",["toasts" => $toasts]);
    }

    public function homeOther(Request $request){
        $followingList = auth()->user()->followings()->pluck("following_id");
        $followingList[] = auth()->user()->id;
        $toasts = Toast::with(["user:id,username","ownerProfile:user_id,id,fullname,avatarUrl", "files"])->withCount(["likes as liked" => function (Builder $query){
            $query->where("user_id", auth()->user()->id);
        }, "toastComments as commentsCount", "ownerProfile as followed" => function(Builder $query){
            $query->whereHas("followers", function($follower){
                $follower->where("follower_id", auth()->user()->id);
            });
        },"likes as likesCount"])->whereNotIn("user_id", $followingList)->orderBy("created_at", "desc")->paginate(5)->items();
        if($request->ajax()){
            return count($toasts) > 0 ? response(["toasts" => $toasts], 200) : response(["message" => "Không còn toast"], 204);
        }
        return view("client/pages.home",["toasts" => $toasts]);
    }
}
