<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AuthRequests\LoginRequest;
use App\Libs\Response\GlobalApiResponse;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Services\AuthService;
use App\Http\Requests\AuthRequests\RegisterRequest;
class AuthController extends Controller
{
    public function __construct(AuthService $AuthService, GlobalApiResponse $GlobalApiResponse)
    {
        $this->auth_service = $AuthService;
        $this->global_api_response = $GlobalApiResponse;
    }
    
    public function register(RegisterRequest $request)
    {
        $register = $this->auth_service->register($request);
        
        if ($register['outcomeCode'] === GlobalApiResponseCodeBook::RECORD_ALREADY_EXISTS['outcomeCode'])
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::RECORD_ALREADY_EXISTS, "Record Already Exist!", $register['record']));
        if (!$register)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "User did not registered!", $register));

        return ($this->global_api_response->success(1, "Artist registered successfully!", $register));
    }

    public function login(LoginRequest $request)
    {
        $login = $this->auth_service->login($request);

        if ($login['outcomeCode'] == GlobalApiResponseCodeBook::INVALID_CREDENTIALS['outcomeCode'])
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INVALID_CREDENTIALS, "Your email or password is invalid!", 'Your email or password is invalid!'));

        if ($login['outcomeCode'] == GlobalApiResponseCodeBook::EMAIL_NOT_VERIFIED['outcomeCode'])
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::EMAIL_NOT_VERIFIED, "Your email is not verified!", $login['record']));

        if (!$login)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Login not successful!", $login['record']));

        return ($this->global_api_response->success(1, "Login successfully!", $login['record']));
    }
}
