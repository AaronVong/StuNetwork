<?php

namespace App\Http\Controllers\Client;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;


class MessageController extends Controller
{
    private $rules = ["message" => "required","max:255", "receiver_id" => "required",];
    private $messages= ["message.required" => "Tin nhắn không thể để trống", "message.max" => "Tin nhắn quá dài", "receiver" => "Mã người nhận bị bỏ trống"];
    public function __construct()
    {
        $this->middleware(["auth"]);
    }

    public function index(Request $request){
        $followings = auth()->user()->followings()->pluck("following_id");
        $strangers = User::with("profile")->whereHas("messages",function($query) use($followings){
             return $query->where("receiver_id", auth()->user()->id) && $query->whereNotIn("sender_id" , $followings);
        })->get();
        return view("client/pages.chat", ["strangers" => $strangers]);
    }

    public function findStranger(Request $request, $id){
        $stranger = User::with("profile")->where("id", $id)->first();
        return response(["stranger" => $stranger],200);
    }

    public function fetchMessages(Request $request, $receiver_id){
        if(User::find($receiver_id)==null){
            return response(["message" => "Không tìm thấy cuộc trò chuyện"], 404);
        }
        $messages = Message::with("sender", "receiver")->where(["receiver_id"=> $receiver_id, "sender_id" => auth()->user()->id])->orWhere(function ($query) use($receiver_id){
            $query->where(["sender_id"=> $receiver_id,"receiver_id" => auth()->user()->id])->orderBy("created_at", "DESC");
        })->orderby("created_at", "DESC")->paginate(10)->items();
        return count($messages) > 0 ? response(["messages" => $messages], 200 ): response([],204);
    }

    # GỬI TIN NHẮN
    public function sendMessage(Request $request, $receiver_id){
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if($validator->fails()){
            return response(["validates" => $validator->errors()], 422);
        }

        # Xét điều kiện nhận của người nhận tin nhắn
        $receiver = User::find($receiver_id);
        $sender = $request->user();
        $senderIsStudent = $sender->isStudent();
        $accepted = false;

        # Nhận tin nhắn từ sinh viên hay giảng viên ?
        if($senderIsStudent !== false && $accepted != true){
            if($receiver->acceptMessageFromStudent()!=null){
                $accepted = true;
            }
        }else{
            if($receiver->acceptMessageFromTeacher()!=null){
                $accepted = true;
            }
        }
        
        # Nhận tin nhắn từ người lạ ?
        if($receiver->acceptMessageFromStranger() != null && $accepted != true){
            $accepted = true;
        }else{
            if($receiver->followings()->where("following_id", $sender->profile->id)->first() != null){
                $accepted = true;
            }
        }

        if($accepted != true){
            return response(["message" => $receiver->username." từ chối nhận tin nhắn"],403);
        }

        # Kiểm tra quyền
        $canSendMessage = Gate::inspect("canSendMessage", auth()->user());
        if($canSendMessage->denied()){
            return response(["message" => $canSendMessage->message()], $canSendMessage->code());
        }
        # Lưu vào cơ sở dữ liệu
        try{
            $message = auth()->user()->messages()->create([
                "message" => $request->message,
                "receiver_id" => $receiver_id,
            ]);
            // Phát sự kiện
            broadcast(new MessageSent(auth()->user(),$message->load("sender")))->toOthers();
            return response(["message" => $message],200);
        }
        catch(Exception $error){
            return response(["message" => $error->getMessage()],500);
        } 
    }

    public function deleteMessage(Request $request, $receiver_id, $message_id){
        $message = Message::where(["id" => $message_id, "receiver_id" => $receiver_id, "sender_id" => auth()->user()->id])->first();
        if(!$message){
            return response(["message" => "Tin nhắn không tồn tại"], 404);
        }
        
        $canDeleteMessage = Gate::inspect("canDeleteMessage", auth()->user());
        if($canDeleteMessage->denied()){
            return response(["message" => $canDeleteMessage->message()], $canDeleteMessage->code());
        }

        $response = Gate::inspect("delete", $message);
        if($response->allowed()){
            $receiver= $message->receiver; 
            $message->delete();
            return response(["message_id" => $message_id, "receiver" => $receiver], 200);
        }
        return response(["message" => $response->message()], $response->code());
    }
}
