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
        $followings = $request->user()->followings;
        return view("client/pages.chat", ["followings" => $followings]);
    }
}
