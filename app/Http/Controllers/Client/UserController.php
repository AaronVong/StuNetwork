<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Rules\CurrentPassword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
    private $rules = [
        "email" => ["required", "max:255", "email", "regex:/^(((dh|cd|lt)\d{8})|(([a-z]+)\.([a-z]+)))@(student\.)?(stu|[a-z]+)\.(edu\.vn)$/i", "not_regex:/^.+\s.+$/"]
    ];

    private $messages = [
        "email.required" => "Email không thể bỏ trống",
        "email.max" => "Email quá dài",
        "email.regex" => "Email không nằm trong hệ thống đào tạo",
        "email.not_regex" => "Email không thể chứa khoảng trắng",
        "email.email" => "Email không tồn tại",
    ];

    public function __construct()
    {
        $this->middleware(["guest"])->only(["resetPasswordForm", "resetPassword", "forgotPassword", "forgotPasswordForm"]);
    }

    # View quên mật khẩu
    public function forgotPasswordForm(){
        return view("client/pages/auth.forgotpassword");
    }

    # Xử lý yêu cầu quên mật khẩu
    public function forgotPassword(Request $req){
        $validator = Validator::make($req->only("email"), $this->rules, $this->messages);
        if($validator->fails()){
            return response(["validates" => $validator->errors()], 422);
        }
        $user = User::where("email" ,$req->email)->first();
        # Kiểm tra email nhập có trong hệ thống hay không
        if($user == null){
            return response(["message" => "Không tìm thấy tài khoản phù hợp với địa chỉ email này."], 404);
        }
        # Gửi đường dẫn reset password đến email
        $status = Password::sendResetLink($req->only("email"));

        # Gửi email thành công
        if($status === Password::RESET_LINK_SENT)
            return response(["message" => __($status)], 200);
        return response(["message" => [__($status)]], 500);
    }

    # View khôi phục mật khẩu
    public function resetPasswordForm($token){
        return view("client/pages/auth.resetpassword", ["token" => $token]);
    }

    public function resetPassword(Request $req){
        $additionalRules = [            
            "token" => ["required"],
            "password" => ["required", "max:255","min:8", "confirmed"]
        ];
        $additionalMessages = [
            "password.required" => "Mật khẩu không thể bỏ trống",                       
            "password.min" => "Mật khẩu cần ít nhất :min ký tự",            
            "password.max" => "Mật khẩu quá dài",
            "password.confirmed" => "Mật khẩu không trùng khợp",    
        ];
        $validator = Validator::make($req->all(), array_merge($this->rules, $additionalRules), array_merge($this->messages, $additionalMessages));
        if($validator->fails()){
            return response(["validates" => $validator->errors()], 422);
        }
        # Xử lý update mật khẩu
        $status = Password::reset(
            $req->only(["email", "password", "password_confirmation", "token"]),
            function ($user, $password) use($req){
                $user->forceFill([
                    "password" => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );
        // Reset password thành công?
        return $status == Password::PASSWORD_RESET
                ? response(['message'=> __($status), "next" => route('login')],200)
                : response(['message' => [__($status)]], 500);
    }

    public function profilesFollowedById(Request $request){
        # need fix 
        $user = User::find($request->id);
        if($user){
            $followings = DB::table("profiles")->join("users", "users.id", "=","profiles.user_id")->join("user_follows", "user_follows.following_id", "=","profiles.id")->where("user_follows.follower_id","=",$request->id)->select("profiles.*","users.username")->get();
            return response(["followings" => $followings], 200);
        }
        return response(["message" => "Không tìm thấy người dùng"],404);
    }

    public function getUser(){
        return response(["user" => auth()->user()], 200);
    }


    public function searchUserbyUsername(Request $request, $username){
        $users = User::with(["profile:user_id,id,fullname,avatarUrl"])->withCount(["profile as followed" => function(Builder $query){
            $query->whereHas("followers", function($follower){
                $follower->where("follower_id", auth()->user()->id);
            });
        }])->where([["username",'like' ,'%'.$username.'%']])->get();
        return response(["users" => $users],200);
    }

    public function changePassword(Request $request){
        $response = Gate::inspect("changePassword",$request->user());
        if($response->denied()){
            return response(["message" => $response->message()], $response->code());
        }
        
        $validator = Validator::make($request->all(),[
            "old_password" => ["required", "max:255", new CurrentPassword],
            "password" => ["required", "max:255","min:8", "confirmed"],
        ],[
            "old_password.required" => "Hãy nhập mật khẩu cũ vào",                             
            "old_password.max" => "Mật khẩu cũ không hợp lệ",
            "password.required" => "Mật khẩu không thể bỏ trống",                       
            "password.min" => "Mật khẩu cần ít nhất :min ký tự",            
            "password.max" => "Mật khẩu quá dài",
            "password.confirmed" => "Mật khẩu không trùng khợp",   
        ]);
        if($validator->fails()){
            return response(["validates" => $validator->errors()], 422);
        }

        $user = Auth::user();
        $user->password = Hash::make($request->password);
        $user->save();
        return response(["message" => "Cập nhật mật khẩu thành cồng"], 200);
    }
}
