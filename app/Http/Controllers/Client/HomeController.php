<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Toast;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth"]);
    }

    public function index(Request $request){
        $toasts = Toast::with(["user:id,username","user.profile:user_id,fullname,avatarUrl", "files", "likes"])->orderBy("created_at", "desc")->paginate(10)->items();
        return view("client/pages.home",["toasts" => $toasts]);
    }
}
