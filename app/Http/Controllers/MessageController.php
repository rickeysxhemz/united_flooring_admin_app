<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Response\GlobalApiResponse;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Services\MessageService;
use App\Http\Requests\MessageRequest\MessageRequest;
use App\Http\Requests\MessageRequest\ChatRequest;

class MessageController extends Controller
{
    public function __construct(MessageService $MessageService, GlobalApiResponse $GlobalApiResponse)
    {
        $this->message_service = $MessageService;
        $this->global_api_response = $GlobalApiResponse;
    }
    public function sendMessage(MessageRequest $request){
        $send_message = $this->message_service->sendMessage($request);
        if (!$send_message)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Message did not sent!", $send_message));
        return ($this->global_api_response->success(1, "Message sent successfully!", $send_message));
    }
    public function getChats()
    {
        $get_chats = $this->message_service->getChats();
        if (!$get_chats)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Chats did not fetched!", $get_chats));
        return ($this->global_api_response->success(1, "Chats fetched successfully!", $get_chats));
    }
    public function getMessages(ChatRequest $request)
    {
        $get_messages = $this->message_service->getMessages($request);
        if (!$get_messages)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Messages did not fetched!", $get_messages));
        return ($this->global_api_response->success(1, "Messages fetched successfully!", $get_messages));
    }
}
