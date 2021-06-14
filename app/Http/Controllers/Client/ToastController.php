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

    # Lấy toast theo id
    public function index(Request $req, $id){
        # Tìm toast
        $toast = Toast::with(["user:id,username","user.profile:user_id,fullname,avatarUrl", "files"])->where("id", $id)->first();
        # Trả về kết quả
        return throw new HttpException(404);
    }

    public function paginate(Request $req){
        if(!$req->ajax()){
            throw new HttpException(403);
        }
        $toasts = Toast::with(["user:id,username","user.profile:user_id,fullname,avatarUrl", "files", "likes"])->orderBy("created_at", "desc")->paginate(10)->items();
        if(count($toasts) > 0){
            return response(["toasts" => $toasts], 200);
        }
        return response([], 204);
    }

    # Lưu trữ toast
    public function store(Request $req){
        $validator = Validator::make($req->all(), $this->rules, $this->messages);
        
        if($validator->fails()){
            return response(["validates" => $validator->errors()], 422);
        }

        $toast = $req->user()->toasts()->create([
            "content" => $req->content
        ]);
        if($toast==null){
            return response(["message" => "Không thể tạo toast!"], 500);
        }
        # Upload file lên Drive, đồng thời lưu trữ thông tin file vào CSDL
        if($req->hasFile("files_upload")){
            $fileArray = $req->file("files_upload");
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
        return response(["status"=> true, "toast" => Toast::with(["user:id,username","user.profile:user_id,fullname", "files", "likes"])->where("id", $toast->id)->first()],200);
    }

    # Xóa toast
    public function destroy(Request $req, $id){
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
                return response(["message" => "Xóa thành công", "toastID" => $toast->id], 200);
            }else{
                return response(["message"=>$response->message()], 401);
            }
        }
        return response(["message"=>"Toast đã bị xóa hoặc không tồn tại!"], 404);
    }

    # Cập nhật toast
    public function update(Request $req, $id){
        # Validate dữ liệu
        $validator = Validator::make($req->only("content", "files_upload"), $this->rules, $this->messages);
        
        # Validate thất bại
        if($validator->fails()){
            return response(["validates" => $validator->errors], 422);    
        }
        $toast = Toast::find($id);
        # Toast không tồn tại
        if(!$toast){
            return response(["message" => "Không tìm thấy bài đăng!"], 404);

        }

        # Xóa file bị xóa
        $deletedFiles = json_decode($req->deletedFiles); # mảng id file đã bị xóa
        $folderID = env("GOOGLE_DRIVE_TOAST_FILES_FOLDER_ID");
        foreach($deletedFiles as $key => $value){
            # xóa khỏi drive
            Storage::delete($folderID."/".$value);
            # xóa khỏi database
            $toast->files()->where("id", "like", $value)->delete();
        }
        
        $toast->content = $req->content;
        # thêm hình ảnh mới (nếu có)
        if($req->hasFile("files_upload")){
            $fileArray = $req->file("files_upload");
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
                # Chưa bắt trường hợp upload fail
            }
        }
        $toast->save();
        return $this->getToast($req, $toast->id);
    }

    public function getToast(Request $req, $id){
        if(!$req->ajax()){
            return throw new HttpException(404);
        }
        $toast = Toast::with(["user:id,username","user.profile:user_id,fullname", "files"])->where("id", $id)->first();
        return response(["toast" => $toast], 200);
    }

    public function like(Request $request){
        $toast = Toast::find($request->id);
        if($toast==null){
            return response(["message" => "Không tìm thấy toast"], 404);
        }
        $request->user()->toggleLike($toast);
        return response(["likes" => $toast->likes, "toast_id" => $toast->id],200);
    }
}
