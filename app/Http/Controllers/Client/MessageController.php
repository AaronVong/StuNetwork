<?php

namespace App\Http\Controllers\Client;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth"]);
    }

    public function index(Request $request){
        $followings = User::with(["followings:user_id,id,avatarUrl,fullname"])->where("id", auth()->user()->id)->first();
        return view("client/pages.chat", ["followings" => $followings]);
    }
}
