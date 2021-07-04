<?php

namespace App\Http\Controllers\Client;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    private $rules = ["message" => "required","max:255", "receiver_id" => "required",];
    private $messages= ["message.required" => "Tin nhắn không thể để trống", "message.max" => "Tin nhắn quá dài", "receiver" => "Mã người nhận bị bỏ trống"];
    public function __construct()
    {
        $this->middleware(["auth"]);
    }

    public function index(Request $request){
        return view("client/pages.chat");
    }

    public function fetchMessages(Request $request, $receiver_id){
        if(User::find($receiver_id)==null){
            return response(["message" => "Không tìm thấy cuộc trò chuyện"], 404);
        }
        $messages = Message::with("sender", "receiver")->where(["receiver_id"=> $receiver_id, "sender_id" => auth()->user()->id])->orWhere(function ($query) use($receiver_id){
            $query->where(["sender_id"=> $receiver_id,"receiver_id" => auth()->user()->id]);
        })->orderby("created_at", "asc")->paginate(10)->items();
        return count($messages) > 0 ? response(["messages" => $messages], 200 ): response([],204);
    }

    public function sendMessage(Request $request, $receiver_id){
        $validator = Validator::make($request->all(), $this->rules, $this->messages);
        if($validator->fails()){
            return response(["validates" => $validator->errors()], 422);
        }
        # Check Policy
        # Store to database
        try{
            $message = auth()->user()->messages()->create([
                "message" => $request->message,
                "receiver_id" => $receiver_id,
            ]);
            // Emit event
            broadcast(new MessageSent(auth()->user(),$message->load("sender")))->toOthers();
            return response(["message" => $message],200);
        }
        catch(Exception $error){
            return response(["message" => $error->getMessage()],500);
        } 
    }
}
