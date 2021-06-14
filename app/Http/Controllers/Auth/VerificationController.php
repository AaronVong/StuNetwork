<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;


class VerificationController extends Controller
{
    function __construct()
    {
        $this->middleware(["auth"]);
        $this->middleware(['signed'])->only("emailVerification");
        $this->middleware(["throttle:6,1"])->only("sendEmailVerification");
    }

    # View nhắc người dùng xác minh email
    public function index(){
        return view("client/pages/auth.verifyemail");
    }

    # Xử lý yêu cầu xác minh email
    function emailVerification(EmailVerificationRequest $request) {
        if($request->user()->email_verified_at == null){
            if($request->user()->isStudent() !== false){
                $request->user()->roles()->attach(1);
            }else{
                $request->user()->roles()->attach(2);
            }
            $request->fulfill();
        }
        return redirect()->route("home");
    }

    # Xử lý yêu câu gửi lại mail xác minh
    function sendEmailVerification(Request $request){
        if($request->user()->email_verified_at != null){
            return response(["status" => false, "next" => route("home")], 200);
        }
        $request->user()->sendEmailVerificationNotification();
        return response(["message" => "Đường dẫn xác minh đã được gủi!", "status" => true], 200);
    }
}
