<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Toast;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ToastController extends Controller
{

    private $textExtension= ["txt", "doc", "docx", "ppt", "pptx", "xls", "xlsx", "pdf"];
    private $rules=[
        "content" => ["required"],
        "files_upload" =>["array", "max:4"],
        "files_upload.*"=> ["mimes:jpg,jpeg,png,txt,docx,doc,ppt,pptx,xls,xlsx,pdf", "max:3192", "mimetypes:image/jpg,image/jpeg,image/png,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/msword,application/vnd.ms-powerpoint,application/vnd.openxmlformats-officedocument.presentationml.presentation,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/plain,application/pdf"],
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
        // $toast = Toast::with(["user:id,username","user.profile:user_id,id,fullname,avatarUrl", "files", "likes"])->withCount("toastComments")->where("id", $id)->get();
        $toast = Toast::with(["user:id,username","ownerProfile:user_id,id,fullname,avatarUrl", "files"])->withCount(["likes as liked" => function (Builder $query){
            $query->where("user_id", auth()->user()->id);
        }, "toastComments as commentsCount", "ownerProfile as followed" => function(Builder $query){
            $query->whereHas("followers", function($follower){
                $follower->where("follower_id", auth()->user()->id);
            });
        },"likes as likesCount"])->where("id", $id)->get();
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
        $toasts = Toast::with(["user:id,username","ownerProfile:user_id,id,fullname,avatarUrl", "files"])->withCount(["likes as liked" => function (Builder $query){
            $query->where("user_id", auth()->user()->id);
        }, "toastComments as commentsCount", "ownerProfile as followed" => function(Builder $query){
            $query->whereHas("followers", function($follower){
                $follower->where("follower_id", auth()->user()->id);
            });
        },"likes as likesCount"])->where("user_id", $id)->orderBy("created_at", "desc")->paginate(5)->items();
        return $toasts;
    }

    public function getToastsLikedByUserId($id){
        $toasts = Toast::with(["user:id,username","ownerProfile:user_id,id,fullname,avatarUrl", "files"])->withCount(["likes as liked" => function (Builder $query){
            $query->where("user_id", auth()->user()->id);
        }, "toastComments as commentsCount", "ownerProfile as followed" => function(Builder $query){
            $query->whereHas("followers", function($follower){
                $follower->where("follower_id", auth()->user()->id);
            });
        },"likes as likesCount"])->whereHas("likes", function ($query) use($id) {return $query->where("user_id", $id);})->orderBy("created_at", "desc")->paginate(5)->items();
        return $toasts;
    }

    public function index(Request $request, $id){
        $toast = $this->getToastById($id);
        if($toast->count() <= 0){
            return throw new HttpException(404);
        }
        $replies = $toast->first()->toastComments()->whereNotNull("child_id")->get();
        $comments = $toast->first()->toastComments()->whereNull("child_id")->get();
 
        return view("client/pages.toast", ["toast" => $toast, "comments"=>$comments, "replies" => $replies]) ;
    }

    public function paginate(Request $request){
        if(!$request->ajax()){
            throw new HttpException(404);
        }
        # Lấy danh sách toast của các user đã theo dõi và của chính mình
        $followingList = auth()->user()->followings()->pluck("following_id");
        $followingList[] = auth()->user()->id;
        $toasts = Toast::with(["user:id,username","ownerProfile:user_id,id,fullname,avatarUrl", "files"])->withCount(["likes as liked" => function (Builder $query){
            $query->where("user_id", auth()->user()->id);
        }, "toastComments as commentsCount", "ownerProfile as followed" => function(Builder $query){
            $query->whereHas("followers", function($follower){
                $follower->where("follower_id", auth()->user()->id);
            });
        },"likes as likesCount"])->whereIn("user_id", $followingList)->orderBy("created_at", "desc")->paginate(5)->items();
        return count($toasts) > 0 ? response(["toasts" => $toasts], 200) : response(["message" => "Không còn toast"], 204);
    }

    # Lưu trữ toast
    public function store(Request $request){
        if(!$request->ajax()){
            throw new HttpException(404);
        }

        $canUploadToast = Gate::inspect('canUploadToast',$request->user());
        # Không có quyền đăng toast
        if($canUploadToast->denied()){
            return response(["message" => $canUploadToast->message()],$canUploadToast->code());
        }

        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        
        # Validate thất bại
        if($validator->fails()){
            return response(["validates" => $validator->errors()], 422);
        }

        # Tạo toast
        $toast = $request->user()->toasts()->create([
            "content" => $request->content
        ]);
        
        # Tạo thất bại
        if($toast==null){
            return response(["message" => "Không thể tạo toast!"], 500);
        }

        # Upload file lên Drive, đồng thời lưu trữ thông tin file vào CSDL
        $canUploadFiles = Gate::inspect('canUploadFiles', $request->user());
        # Nếu có tồn file được upload và có quyền upload file
        if($request->hasFile("files_upload") && $canUploadFiles->allowed()){
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
        return response(["message"=>$canUploadFiles->allowed() ? "Toast đã được đăng" : $canUploadFiles->message() ,"createdToast" => $this->getToastById($toast->id)->first()],200);
    }

    # Xóa toast
    public function destroy(Request $request, $id){
        
        if(!$request->ajax()){
            throw new HttpException(404);
        }
        $canDeleteToast = Gate::inspect('canDeleteToast', $request->user());
        $canDeleteFile = Gate::inspect('canDeleteFile', $request->user());
        $toast = Toast::where("id", $id)->first();
        # Không có quyền xóa toast
        if($canDeleteToast->denied() || $canDeleteFile->denied()){
            return response(["message" => count($toast->files) <= 0 ? "Tài khoản đã bị khóa chức năng xóa file hoặc bài viết" :$canDeleteToast->message()." - ".$canDeleteFile->message()],$canDeleteToast->allowed() ? $canDeleteFile->code() : $canDeleteToast->code());
        } 
        $message = ""; 

        # Tìm toast
        
        if($toast!= null){
            $response = Gate::inspect("delete",$toast);
            # Là chủ sở hữu của toast này
            if($response->allowed()){
                $files = $toast->files;
                $folderID = env("GOOGLE_DRIVE_TOAST_FILES_FOLDER_ID");
                if($canDeleteFile->denied()){
                    $message = $canDeleteFile->message();
                }else{
                    foreach ($files as $key => $file) {
                        Storage::delete($folderID."/".$file->id);
                    }
                    $message = "Toast đã được xóa";
                }
                $toast->delete();
                return response(["message" => $message, "deletedID" => $toast->id], 200);
            }else{
                return response(["message"=>$response->message()], $response->code());
            }
        }
        return response(["message"=>"Toast đã bị xóa hoặc không tồn tại!"], 404);
    }

    # Cập nhật toast
    public function update(Request $request, $id){
        if(!$request->ajax()){
            throw new HttpException(404);
        }

        $canEditToast = Gate::inspect('canEditToast', $request->user());
        if($canEditToast->denied()){
            return response(["message" => $canEditToast->message()],$canEditToast->code());
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

        $canDeleteFile = Gate::inspect('canDeleteFile', $request->user());
        if($canDeleteFile->allowed()){
            # Xóa file bị xóa
            $deletedFiles = json_decode($request->deletedFiles); # mảng id file đã bị xóa
            $folderID = env("GOOGLE_DRIVE_TOAST_FILES_FOLDER_ID");
            foreach($deletedFiles as $key => $value){
                Storage::delete($folderID."/".$value);
                $toast->files()->where("id", "like", $value)->delete();
            }
        }
        
        $toast->content = $request->content;
        # thêm hình ảnh mới (nếu có)
        $canUploadFiles = Gate::inspect('canUploadFiles', $request->user());
        if($request->hasFile("files_upload") && $canUploadFiles->allowed()){
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
        return response(["message"=>$canUploadFiles->allowed() ? "Cập nhật toast thành công" : $canUploadFiles->message() ,"updatedToast" => $this->getToastById($toast->id)->first()],200);
    }

    public function like(Request $request){
        if(!$request->ajax()){
            throw new HttpException(404);
        }
        $toast = Toast::find($request->id);
        if($toast==null){
            return response(["message" => "Không tìm thấy toast"], 404);
        }
        # return true -> delete like
        # return Like::class -> insert like
        $result = $request->user()->toggleLike($toast);
        return response(["likes" => $toast->likes, "toastID" => $toast->id, "liked" => $result === true ? false : true],200);
    }

    public function toastsUploadedById(Request $request){
        if(!$request->ajax()){
            throw new HttpException(404);
        }
        $toasts = $this->getToastsUploadedByUserId($request->user_id);
        return count($toasts)>0 ? response(["toasts" => $toasts, "followings" => $request->user()->followings], 200):response([], 204);
    }

    public function toastsLikedById(Request $request){
        if(!$request->ajax()){
            throw new HttpException(404);
        }
        $toasts = $this->getToastsLikedByUserId($request->user_id);
        return count($toasts) > 0 ? response(["toasts" => $toasts, "followings" => $request->user()->followings], 200) : response([], 204);
    }
}
