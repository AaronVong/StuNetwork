<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    # Validation rules
    private $rules = [
        "fullname" => ["required","max:255"],
        "username" => ["required", "max:50", "min:5", "unique:App\Models\User,username" ,"regex:/^[a-zA-Z]([._-](?![._-])|[a-zA-Z0-9]){5,}[a-zA-Z0-9]$/"],
        "password" => ["required", "max:255","min:8", "confirmed"],
        "email" => ["required", "max:255", "email", "unique:App\Models\User,email", "regex:/^(((dh|cd|lt)\d{8})|(([a-z]+)\.([a-z]+)))@(student\.)?(stu|[a-z]+)\.(edu\.vn)$/i", "not_regex:/^.+\s.+$/"]
    ];

    # Validation error messages
    private $messages = [
            "fullname.required" => "Họ tên không thể để trống",
            "fullname.max"=> "Họ tên bạn quá dài",
            "username.required" => "Tên tài khoản không thể bỏ trống",
            "username.unique" => "Tên tài khoản đã được sử dụng",
            "username.max" => "Tên tài khoản chi chứa tối đa :min ký tự",
            "username.min" => "Tên tài khoản cần ít nhất :min ký tự",
            "username.regex" => "Tên tài khoản phải bắt đầu bằng ký tự và chỉ được chứa ký tự . _ - và có tối thiếu 7 ký tự",
            "email.required" => "Email không thể bỏ trống",
            "email.unique" => "Email đã được đăng ký",
            "email.max" => "Email quá dài",
            "email.regex" => "Email không nằm trong hệ thống đào tạo",
            "email.not_regex" => "Email không thể chứa khoảng trắng",
            "email.email" => "Email không tồn tại",
            "password.required" => "Mật khẩu không thể bỏ trống",                       
            "password.min" => "Mật khẩu cần ít nhất :min ký tự",            
            "password.max" => "Mật khẩu quá dài",
            "password.confirmed" => "Mật khẩu không trùng khợp",
    ];

    public function __construct(){
        # chỉ truy cập khi chưa đăng nhập
        $this->middleware(["guest"]);
    }

    # View đăng ký
    public function index(){
        return view("client/pages/auth.register");
    }

    # Xử lý yêu cầu đăng ký
    public function store(Request $req){
        # Validate dữ liệu
        $validator = Validator::make($req->all(), $this->rules, $this->messages);
        if($validator->fails()){
            return response(["validates" => $validator->errors()], 422);
        }
        try{
            # Tạo User
            $user = User::create([
                "email" => $req->email,
                "username" => $req->username,
                "password" => Hash::make($req->password)
            ]);
            $user->profile()->create([
                "fullname" => $req->fullname,
            ]);
            # Đăng nhập User vừa tạo thành công
            if(Auth::attempt($req->only(["email", "password"])) !== false){
                $user = auth()->user();
                # emit sự kiện Register và gửi verification link 
                event(new Registered($user));

                # Chuyển hướng người dùng sang trang yêu cầu xác minh
                return response(["next" => route('verification.notice')], 200);
            }
        }catch(\Exception $e){
            return response(["message" => "Xảy ra lôi trong quá trình đăng ký, vui lòng thử lại sau"],406);
        }
    }
}
