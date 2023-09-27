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

class MessageService extends BaseService
{
    use CommonTrait;
    public function sendMessage($request)
    {
        try
        {
            DB::beginTransaction();
            $message = new Message();
            $message->sender_id = Auth::id();
            $message->receiver_id = $request->receiver_id;
            $message->message = $request->message;
            $message->save();
            $user = User::find(Auth::id());
                $title = 'new message';
                $body = $user->name.' send a message';

                $data = [
                        'status' => 'chat', 
                        'sender' =>  Auth::id(), 
                        'receiver' => $request->receiver_id, 
                        'message' => $request->message
                    ];

                $this->pusher($request->receiver_id, $title, $body, $data);
            DB::commit();
            return $message;
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
            $authUserId = auth()->user()->id;
            $chats = Message::with(['sender', 'receiver'])
                ->where(function ($query) use ($authUserId) {
                    $query->where('sender_id', $authUserId)
                          ->orWhere('receiver_id', $authUserId);
                })
                ->get();
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
            $messages = Message::where(function ($query) use ($request) {
                $query->where('sender_id', $request->sender_id)
                    ->where('receiver_id', $request->receiver_id);
            })->orWhere(function ($query) use ($request) {
                $query->where('sender_id', $request->receiver_id)
                    ->where('receiver_id', $request->sender_id);
            })->get();
            return Helper::returnRecord(GlobalApiResponseCodeBook::RECORDS_FOUND['outcomeCode'], $messages);
        }catch(Exception $e){
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("MessageService: getMessages", $error);
            return false;
            
        }
    }
}