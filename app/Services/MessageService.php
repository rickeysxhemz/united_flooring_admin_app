<?php
namespace App\Services;

use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Libs\Response\GlobalApiResponse;
use Illuminate\Support\Facades\DB;
use App\Models\Message;
use App\Helper\Helper;
use App\Models\User;
use Exception;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\CommonTrait;
use App\Models\Conversation;

class MessageService extends BaseService
{
    use CommonTrait;
    public function sendMessage($request)
    {
        try
        {
            DB::beginTransaction();
            
            $conversation_exists=Conversation::where('admin_id',Auth::id())->where('user_id',$request->receiver_id)->first();
            
            if($conversation_exists){
                $exists = Conversation::find($conversation_exists->id);
                $exists->message=$request->message;
                $exists->read=false;
                $exists->unread_messages_count=$exists->unread_messages_count+1;
                $exists->save();
                $message = new Message();
                $message->conversation_id = $exists->id;
                $message->sender_id = Auth::id();
                $message->receiver_id = $request->receiver_id;
                $message->message = $request->message;
                $message->save();
                $user = User::find(Auth::id());
                    $title = 'new message';
                    $body = $user->name.' send a message';
                    $user='admin';
                    $data = [
                            'status' => 'chat', 
                            'sender' =>  Auth::id(), 
                            'receiver' => $request->receiver_id, 
                            'message' => $request->message
                        ];
    
                    $this->pusher($request->receiver_id, $title, $body, $data);
                    $this->notifications($request->receiver_id, $user, $title, $body, $data);
                    DB::commit();
                    return $message;
            }else
            {
            $conversation=new Conversation();
            $conversation->admin_id=Auth::id();
            $conversation->user_id=$request->receiver_id;
            $conversation->message=$request->message;
            $conversation->read=false;
            $conversation->unread_messages_count=1;
            $conversation->save();

            $message = new Message();
            $message->conversation_id = $conversation->id;
            $message->sender_id = Auth::id();
            $message->receiver_id = $request->receiver_id;
            $message->message = $request->message;
            $message->save();
            $user = User::find(Auth::id());
                    $title = 'new message';
                    $body = $user->name.' send a message';
                    $user='admin';
                    $data = [
                            'status' => 'chat', 
                            'sender' =>  Auth::id(), 
                            'receiver' => $request->receiver_id, 
                            'message' => $request->message
                        ];
    
                    $this->pusher($request->receiver_id, $title, $body, $data);
                    $this->notifications($request->receiver_id,$user, $title, $body, $data);
            DB::commit();
            return $message;
                }
        }catch(Exception $e){
            DB::rollback();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("MessageService: sendMessage", $error);
            return false;
            
        }
    }
    public function getChats()
    {
        try
        {
            $chats=[];
            $UnReadChats=Conversation::where('read', false)->count();
            $all_chats = Conversation::with('user')
            ->where('admin_id',Auth::id())
            ->orderBy('created_at','desc')
            ->get();
            $chats['unread_chats']=$UnReadChats;
            $chats['all_chats']=$all_chats;
            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORDS_FOUND['outcomeCode'], $chats);
        }catch(Exception $e){
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("MessageService: getChats", $error);
            return false;
            
        }
    }
    public function getMessages($request)
    {
        try
        {
            $messages = Message::with('sender','receiver')
            ->where('conversation_id',$request->conversation_id)
            ->orderBy('created_at','desc')
            ->get();
            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORDS_FOUND['outcomeCode'], $messages);
        }catch(Exception $e){
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("MessageService: getMessages", $error);
            return false;
            
        }
    }
    public function read($request)
    {
        try
        {   
            DB::beginTransaction();
            $read_msg = Conversation::where('admin_id',Auth::id())
                        ->where('user_id',$request->user_id)->first();
            $read_msg->read = true;
            $read_msg->unread_messages_count = 0;
            $read_msg->save();
            DB::commit();
            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORDS_FOUND['outcomeCode'], $read_msg);
        }catch(Exception $e){
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("MessageService: read", $error);
            return false;
            
        }
    }
}