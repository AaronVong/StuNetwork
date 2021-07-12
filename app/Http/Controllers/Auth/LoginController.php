<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
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
            if(!auth()->user()->hasPermissionTo('login')){
                Auth::logout();
                return response(["message" => "Tài khoản của bạn đã bị khóa, email minh.vongquyen@student.stu.edu.vn để biết thêm chi tiết"], 403);
            }
            return auth()->user()->hasRole('super-admin') ? response(["next" => route("dashboard")],200):  response(["next" => route("home")],200);
        }
        return response(["message" => "Email hoặc mật khẩu không đúng!"], 406);
    }

    public function destroy(){
        Auth::logout();
        return redirect()->route("login");
    }
}
