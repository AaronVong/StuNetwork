<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Toast;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;

class ToastCommentController extends Controller
{
    private $rules=[
        "comment" => ["required", "string", "max:255"],
    ];
    private $messages=[
        "comment.required" => "Nội dung bình luận không thể bỏ trống",
        "comment.string" => "Sai định dạng",
        "comment.max" => "Nội dung comment quá dài",
    ];
    public function __construct()
    {
        $this->middleware(["auth"]);
    }

    public function store(Request $request, $id){
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if($validator->fails()){
            return response(["validates" => $validator->errors()], 422);
        }
        
        $toast = Toast::find($id);
        if($toast == null){
            return response(["message" => "Toast không tồn tại"], 404);
        }

        try{
            $commentClass = config("comments.model");
            $comment = new $commentClass;
            $comment->commenter()->associate(Auth::user());
            $comment->commentable()->associate($toast);
            $comment->comment = $request->comment;
            $comment->approved = !Config::get('comments.approval_required');
            $comment->save();
        }catch(Exception $error){
            return response(["message" =>$error->getMessage()], 500);
        }
        return response(["comment" => $commentClass::find($comment->id)], 200);
    }
    public function reply(Request $request, $id){
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if($validator->fails()){
            return response(["validates" => $validator->errors()], 422);
        }

        $commentClass = Config::get("comments.model");
        $comment = $commentClass::find($id);
        if($comment != null){
            try{
                $reply = new $commentClass;
                $reply->commenter()->associate(Auth::user());
                $reply->commentable()->associate($comment->commentable);
                $reply->parent()->associate($comment);
                $reply->comment = $request->comment;
                $reply->approved = !Config::get('comments.approval_required');
                $reply->save();
                return response(["comment" => $commentClass::find($reply->id)],200);
            }catch(Exception $error){
                return response(["message" =>$error->getMessage()], 500);
            }
        }
        return response(["message" => "Không tìm thấy comment"], 404);
    }
    public function update(Request $request, $id){
        $commentClass = Config::get("comments.model");
        $comment = $commentClass::find($id);
        if($comment == null){
            return response(["message" => "Comment đã bị xóa hoặc không tồn tại"], 404);
        }

        $response = Gate::inspect("update", $comment);
        if($response->denied()){            
            return response(["message" => $response->message()], $response->code());
        }
        
        try{
            $validator =  Validator::make($request->all(), $this->rules, $this->messages);
            if($validator->fails()){
                return response(["validates"=>$validator->errors()], 422);
            }
            $comment->update([
                'comment' => $request->comment
            ]);
            return response(["message" => "Comment đã được cập nhật", "comment" => $commentClass::find($id)], 200);
        }catch(Exception $error){
            return response(["message" => $error->getMessage()], 500);
        }
    }
    public function destroy(Request $request, $id){
        $commentClass = Config::get("comments.model");
        $comment = $commentClass::find($id);
        if($comment == null){
            return response(["message" => "Comment đã bị xóa hoặc không tồn tại"], 404);
        }
        $response = Gate::inspect("delete", $comment);
        if($response->denied()){
            return response(["message" => $response->message()], $response->code());
        }
        try{
            if (Config::get('comments.soft_deletes') == true) {
                $comment->delete();
            }
            else {
                $comment->forceDelete();
		    }
            return response(["message"=> "Comment đã được xóa", "comment" => $comment], 200);
        }catch(Exception $error){
            return response(["message" => $error->getMessage()], 500);
        }
    }
}
