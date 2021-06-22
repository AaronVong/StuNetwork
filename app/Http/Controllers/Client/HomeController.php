<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Toast;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth"]);
    }

    public function index(Request $request){
        $toasts = Toast::with(["user:id,username","user.profile", "files", "likes"])->orderBy("created_at", "desc")->paginate(5)->items();
        return view("client/pages.home",["toasts" => $toasts]);
    }
}
