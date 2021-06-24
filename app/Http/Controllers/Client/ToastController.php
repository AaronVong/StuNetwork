<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Toast;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ToastController extends Controller
{

    private $textExtension= ["txt", "doc", "docx", "ppt", "pptx", "xls", "xlsx"];
    private $rules=[
        "content" => ["required"],
        "files_upload" =>["array", "max:4"],
        "files_upload.*"=> ["mimes:jpg,jpeg,png,txt,docx,doc,ppt,pptx,xls,xlsx", "max:3192", "mimetypes:image/jpg,image/jpeg,image/png,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain"],
    ];
    private $messages =[
        "content.required" => "Nội dung không thể để trống",
        "files_upload.array" => "Không thể xử lý hình ảnh",
        "files_upload.max" => "Số lượng hình ảnh được phép upload là :max",
        "files_upload.*.mimes"=>"Định dạng file không được hộ trợ",
        "files_upload.*.mimetypes" => "Định dạng file chưa được hộ trợ",
        "files_upload.*.max" => "Kích thước file quá lớn > :max",
    ];
    function __construct()
    {
        $this->middleware(["auth"]);
    }

    public function getToastById($id){
        $toast = Toast::with(["user:id,username","user.profile:user_id,id,fullname,avatarUrl", "files", "likes"])->where("id", $id)->get();
        return $toast;
    }

    public function getToast(Request $request, $id){
        if(!$request->ajax()){
            return throw new HttpException(404);
        }
        $toast = $this->getToastById($id);
        return $toast ? response(["toast" => $toast->first()],200) : response(["message" => "Toast không tồn tại",404]);
    }

    public function getToastsUploadedByUserId($id){
        $toasts = Toast::with(["user:id,username","user.profile:user_id,id,fullname,avatarUrl", "files", "likes"])->where("user_id", $id)->orderBy("created_at", "desc")->get();
        return $toasts;
    }

    public function getToastsLikedByUserId($id){
        $toasts = Toast::with(["user:id,username","user.profile:user_id,id,fullname,avatarUrl", "files", "likes"])->whereHas("likes", function ($query) use($id) {return $query->where("user_id", $id);})->orderBy("created_at", "desc")->get();
        return $toasts;
    }

    public function index(Request $request, $id){
        $toast = $this->getToastById($id);
        $replies = $toast->first()->toastComments()->whereNotNull("child_id")->get();
        $comments = $toast->first()->toastComments()->whereNull("child_id")->get();
 
        return $toast ? view("client/pages.toast", ["toast" => $toast, "comments"=>$comments, "replies" => $replies]) : throw new HttpException(404);
    }

    public function paginate(Request $request){
        if(!$request->ajax()){
            throw new HttpException(404);
        }
        $toasts = Toast::with(["user:id,username","user.profile:user_id,id,fullname,avatarUrl", "files", "likes"])->orderBy("created_at", "desc")->paginate(10)->items();
        return count($toasts) > 0 ? response(["toasts" => $toasts], 200) : response(["message" => "Không còn toast"], 204);
    }

    # Lưu trữ toast
    public function store(Request $request){
        if(!$request->ajax()){
            throw new HttpException(404);
        }
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        
        if($validator->fails()){
            return response(["validates" => $validator->errors()], 422);
        }

        $toast = $request->user()->toasts()->create([
            "content" => $request->content
        ]);
        if($toast==null){
            return response(["message" => "Không thể tạo toast!"], 500);
        }
        # Upload file lên Drive, đồng thời lưu trữ thông tin file vào CSDL
        if($request->hasFile("files_upload")){
            $fileArray = $request->file("files_upload");
            foreach ($fileArray as $key => $file) {
                $folderID = env("GOOGLE_DRIVE_TOAST_FILES_FOLDER_ID");
                $result = $this->uploadFile($file, $folderID);
                if($result !== false){
                    $type = array_search($result["extension"], $this->textExtension);
                    $toast->files()->create([
                        "id" => $result["fileID"],
                        "url" => $result["url"],
                        "type" => $type === false ? "image" : "file",
                        "name" => $file->getClientOriginalName()
                    ]);
                }
                # Chưa bắt trường hợp upload fail
            }
        }
        # Trả về toast vừa tạo
        return response(["message"=>"Toast đã được đăng","createdToast" => $this->getToastById($toast->id)->first()],200);
    }

    # Xóa toast
    public function destroy(Request $request, $id){
        if(!$request->ajax()){
            throw new HttpException(404);
        }
        # Tìm toast
        $toast = Toast::where("id", $id)->first();
        if($toast!= null){
            # kiểm tra quyền
            $response = Gate::inspect("delete",$toast);
            if($response->allowed()){
                $files = $toast->files;
                $folderID = env("GOOGLE_DRIVE_TOAST_FILES_FOLDER_ID");
                foreach ($files as $key => $file) {
                    Storage::delete($folderID."/".$file->id);
                }
                $toast->delete();
                return response(["message" => "Toast đã được xóa", "deletedID" => $toast->id], 200);
            }else{
                return response(["message"=>$response->message()], 401);
            }
        }
        return response(["message"=>"Toast đã bị xóa hoặc không tồn tại!"], 404);
    }

    # Cập nhật toast
    public function update(Request $request, $id){
        if(!$request->ajax()){
            throw new HttpException(404);
        }
        # Validate dữ liệu
        $validator = Validator::make($request->only("content", "files_upload"), $this->rules, $this->messages);
        
        # Validate thất bại
        if($validator->fails()){
            return response(["validates" => $validator->errors], 422);    
        }
        $toast = Toast::find($id);
        # Toast không tồn tại
        if(!$toast){
            return response(["message" => "Toast không tồn tại!"], 404);

        }

        # Kiểm tra quyền
        $response = Gate::inspect("update", $toast);
        if(!$response->allowed()){
            return response(["message" => $response->message()],401);
        }

        # Xóa file bị xóa
        $deletedFiles = json_decode($request->deletedFiles); # mảng id file đã bị xóa
        $folderID = env("GOOGLE_DRIVE_TOAST_FILES_FOLDER_ID");
        foreach($deletedFiles as $key => $value){
            Storage::delete($folderID."/".$value);
            $toast->files()->where("id", "like", $value)->delete();
        }
        $toast->content = $request->content;
        # thêm hình ảnh mới (nếu có)
        if($request->hasFile("files_upload")){
            $fileArray = $request->file("files_upload");
            foreach ($fileArray as $key => $file) {
                $result = $this->uploadFile($file, $folderID);
                if($result !== false){
                    $type = array_search($result["extension"], $this->textExtension);
                    $toast->files()->create([
                        "id" => $result["fileID"],
                        "url" => $result["url"],
                        "type" => $type === false ? "image" : "file",
                        "name" => $file->getClientOriginalName()
                    ]);
                }
            }
        }
        $toast->save();
        return response(["message"=>"Toast đã được cập nhật","updatedToast" => $this->getToastById($toast->id)->first()],200);
    }

    public function like(Request $request){
        if(!$request->ajax()){
            throw new HttpException(404);
        }
        $toast = Toast::find($request->id);
        if($toast==null){
            return response(["message" => "Không tìm thấy toast"], 404);
        }
        $request->user()->toggleLike($toast);
        return response(["likes" => $toast->likes, "toastID" => $toast->id],200);
    }

    public function toastsUploadedById(Request $request){
        if(!$request->ajax()){
            throw new HttpException(404);
        }
        $toasts = $this->getToastsUploadedByUserId($request->user_id);
        return $toasts ? response(["toasts" => $toasts, "followings" => $request->user()->followings], 200):response(["message" => "Không tìm thấy người dùng!", "toasts"=>[]], 404);
    }

    public function toastsLikedById(Request $request){
        if(!$request->ajax()){
            throw new HttpException(404);
        }
        $toasts = $this->getToastsLikedByUserId($request->user_id);
        return $toasts ? response(["toasts" => $toasts, "followings" => $request->user()->followings], 200):response(["message" => "Không tìm thấy người dùng!", "toasts"=>[]], 404);
    }
}
