<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserFollow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProfileController extends Controller
{
    private $rules = [
        "fullname" => ["required", "max:255"],
        "phone" => ["nullable","digits_between:10,11"],
        "address" => ["nullable","max:255"],
        "birthday" => ["nullable","date",],
        "gender" => ["required","boolean"],
        "avatar" => ["nullable","mimes:jpg,jpeg,png", "max:3192", "mimetypes:image/jpg,image/jpeg,image/png"],
        "background" => ["nullable","mimes:jpg,jpeg,png", "max:3192", "mimetypes:image/jpg,image/jpeg,image/png"]
    ];
    private $messages = [
        "fullname.required" => "Họ tên không thể để trống",
        "fullname.max" => "Họ tên quá dài",
        "phone.digits_between" => "Số điện thoại không hợp lệ",
        "phone.required" => "Số điện thoại không thể để trống",
        "address.max" => "Địa chỉ quá dài",
        "address.required" => "Địa chỉ không thể để trống",
        "birthday.date" => "Ngày sinh không hợp lệ",
        "birthday.required" => "Ngày sinh không thể để trống",
        "gender.boolean" => "Giá trị không hợp lệ",
        "gender.required" => "Giới tính không thể để trống",
        "avatar.mimetypes" => "Định dạng file chưa được hộ trợ",
        "background.mimetypes" => "Định dạng file chưa được hộ trợ",
        "avatar.mimes" => "Định dạng file không được hộ trợ",
        "avatar.max" => "Kích thước hình ảnh quá lớn > :max kilobytes!",
        "background.mimes" => "Định dạng file không được hộ trợ",
        "background.max" => "Kích thước hình ảnh quá lớn > :max kilobytes!"
    ];
    function __construct()
    {
        $this->middleware(["auth"]);
    }

    function index($username){
        $user = User::with(["profile"])->where("username", $username)->first();
        if($user!=null){
            $profile = $user->profile()->with(["user:id,username"])->first();
            return view("client/pages.profile", ["profile"=>$profile]);
        }
        throw new HttpException(404, "Không tìm thấy profile này!");
    }

    function getProfile(Request $req, $username){
        if($req->ajax()){
            return response(["profile" => User::where("username" , $username)->first()->profile],200);
        }
        return response(["next" => route("home")], 400);
    }

    function updateProfile(Request $req, $username){
        # Chỉ chấp nhận Ajax request
        if(!$req->ajax()){
            return response(["message" => "Không hộ trợ phương thúc"], 400);
        }
        # Tìm profile
        $user = User::where("username", $username)->first();
        # Profile không tồn tại ?
        if(!$user){ 
            return response(["message" => "Không tìm thấy profile của người dùng"],404);
        }else{
            $profile = $user->profile()->with(["user:id,username"])->first();
            $response = Gate::inspect("update", $profile);  
            # Kiểm tra quyền cập nhật      
            if($response->allowed()){
                # Validate dữ liệu nếu khi có quyền cập nhật
                $validator = Validator::make($req->all(), $this->rules, $this->messages);
                // Validate dữ liệu fail
                if($validator->fails()){
                    return response(["validates" => $validator->errors()], 422);
                }
                # Cập nhật dữ liệu nếu validate success
                try{
                    $folderId= env("GOOGLE_DRIVE_USER_FILES_FOLDER_ID");
                    $profile->fullname = $req->fullname;
                    $profile->phone = $req->phone;
                    $profile->address = $req->address;
                    $profile->gender = $req->gender;
                    $profile->birthday = $req->birthday;
                    if($req->hasFile("avatar")){
                        $result = $this->uploadFile($req->file("avatar"),$folderId);
                        if($profile->avatarUrl != null){
                            Storage::delete($folderId."/".$this->getGoogleDriveFileId($profile->avatarUrl));
                        }
                        $profile->avatarUrl = $result["url"];
                    }
                    if($req->hasFile("background")){
                        $result = $this->uploadFile($req->file("background"),$folderId);
                        if($profile->backgroundUrl != null){
                            Storage::delete($folderId."/".$this->getGoogleDriveFileId($profile->backgroundUrl));
                        }
                        $profile->backgroundUrl = $result["url"];
                    }
                    $profile->save();
                    return response(["profile" => $profile, "message" => "Cập nhật profile thành công"], 200);
                }catch(\Exception $e){
                    return response(["message" => $e->getMessage()], 404);
                }
            }else{
                return response(["message" => $response->message()],402);
            }
        }
    }

    public function follow(Request $request){
        $profile = Profile::find($request->profile_id);
        if($profile){
            $response = Gate::inspect("follow", $profile);
            if($response->allowed()){
                $isFollowed = $request->user()->followings()->where("following_id", $request->profile_id)->first() == null ? false : true;
                if($isFollowed){
                    $request->user()->followings()->detach($request->profile_id);
                    $isFollowed = false;
                }
                else{
                    $request->user()->followings()->attach($request->profile_id);
                    $isFollowed = true;
                }
                return response(["followed" => $isFollowed, "profileId" => $request->profile_id],200);
            }else{
                return response(["message" => $response->message()], 403);
            }
        }
        return response(["message" => "Profile không tồn tại"], 404);
    }

    # kiểm tra đã follow profile này chưa
    public function followed(Request $request){
        $user = User::find($request->profile_id)->first();
        if($user){
            $profile = $user->profile;
            $response = Gate::inspect("follow", $profile);
            if($response->allow()){
                $isFollowed = $request->user()->followings()->where("following_id", $request->profile_id)->first() == null ? false : true;
                return response(["followed" =>  $isFollowed], 200);
            }
            return response(["message" => $response->message()], 403);
        }
        return response(["message" => "Profile không tồn tại"], 404);
    }
}
