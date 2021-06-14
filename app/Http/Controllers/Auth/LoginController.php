<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    private $rules = [
        "password" => ["required"],
        "email" => ["required","email"]
    ];

    private $message = [
        "email.max" => "Email quá dài",
        "email.email" => "Email không hợp lệ",
        "email.required" => "Email không thể bỏ trống",
        "password.required" => "Mật khẩu không thể bỏ trống", 
    ];
    public function __construct()
    {
        $this->middleware(["guest"])->only(["index", "store"]);
        $this->middleware(["auth"])->only("destroy");
    }
    
    public function index(){
        return view("client/pages/auth.login");
    }

    public function store(Request $req){
        $validator = Validator::make($req->only("email","password"), $this->rules, $this->message);
        
        if($validator->fails()){
            return response(["validates" => $validator->errors()], 422);
        }
        if(Auth::attempt($req->only(["email", "password"]), $req->remember) !== false){
            return response(["next" => route("home")],200);
        }
        return response(["message" => "Email hoặc mật khẩu không đúng!"], 406);
    }

    public function destroy(){
        Auth::logout();
        return redirect()->route("login");
    }
}
