<?php

namespace App\Services;

use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Models\SocialIdentity;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\PasswordReset;
use App\Helper\Helper;
use App\Models\User;
use App\Models\OTP;
use Laravel\Socialite\Facades\Socialite;
use Spatie\Permission\Models\Role;
use Exception;
use Twilio\Rest\Client;
use App\Models\Setting;
use App\Models\PhoneVerify;
class AuthService extends BaseService
{
    public function register($request)
    {
        try {
            DB::beginTransaction();
            
            $userexist = User::where('email', $request->email)->first();
            // // dd($userexist);
            if($userexist &&  $userexist->phone_verified_at == null){
               
                $phoneexist = User::where('phone_no', $request->phone_no)->first();
                
                if($phoneexist &&  $phoneexist->phone_verified_at == null){
                     
                    $user = User::find($phoneexist->id);
                    $user->name = $request->name;
                    $user->email = $request->email;
                    $user->password = Hash::make($request->password);
                    $user->phone_no = $request->phone_no;
                    $user->save();
                    
                    $otp = new OTP();
                    $otp->user_id = $user->id;
                    // $otp->otp_value = random_int(100000, 999999);
                    $otp->otp_value = '1234';
                    $otp->save();
                    
                    try{
                        $account_sid = 'AC60d20bdd51da17c92e5dd29c9f22e521';
                        $auth_token = 'bb3720d64d89358fe6915c168f5474d4';
                        $twilio_number = '+13158478569';
                        
                        $receiverNumber = $request->phone_no;
                        $message = 'This message from united flooring here is your six digit otp  ' . $otp->otp_value;
                        $client = new Client($account_sid, $auth_token);
                        $client->messages->create($receiverNumber, [
                            'from' => $twilio_number, 
                            'body' => $message]);
                        }
                    catch (Exception $e) {
                        DB::rollBack();
                        $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
                            Helper::errorLogs("AuthService: register", $error);
                            return Helper::returnRecord(GlobalApiResponseCodeBook::INVALID_FORM_INPUTS['outcomeCode'], ['The Phone has not exist.']);
                    }
                    DB::commit();
                    return $user;
                }
    
                $user = User::find($userexist->id);
                $user->name = $request->name;
                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->phone_no = $request->phone_no;
                $user->save();

                $otp = new OTP();
                $otp->user_id = $user->id;
                // $otp->otp_value = random_int(100000, 999999);
                $otp->otp_value = '1234';
                $otp->save();
                
                try{
                    $account_sid = 'AC60d20bdd51da17c92e5dd29c9f22e521';
                    $auth_token = 'bb3720d64d89358fe6915c168f5474d4';
                    $twilio_number = '+13158478569';
                    
                    $receiverNumber = $request->phone_no;
                    $message = 'This message from united flooring here is your six digit otp  ' . $otp->otp_value;
                    $client = new Client($account_sid, $auth_token);
                    $client->messages->create($receiverNumber, [
                        'from' => $twilio_number, 
                        'body' => $message]);
                    }
                catch (Exception $e) {
                    DB::rollBack();
                    $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
                        Helper::errorLogs("AuthService: register", $error);
                        return Helper::returnRecord(GlobalApiResponseCodeBook::INVALID_FORM_INPUTS['outcomeCode'], ['The Phone has not exist.']);
                }

                DB::commit();
                return $user;
            }
          
            if($userexist){
                return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_ALREADY_EXISTS['outcomeCode'], ['The email has already been taken.']);
            }

            if($userexist &&  $userexist->phone_verified_at !== null){
                return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_ALREADY_EXISTS['outcomeCode'], ['The email has already been taken.']);
            }
            $phoneexist = User::where('phone_no', $request->phone_no)->first();
            if($phoneexist &&  $phoneexist->phone_verified_at !== null){
                return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_ALREADY_EXISTS['outcomeCode'], ['The Phone has already been taken.']);
            }
            
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone_no = $request->phone_no;
            $user->save();

            $setting = new Setting();
            $setting->user_id = $user->id;
            $setting->private_account = 0;
            $setting->secure_payment = 1;
            $setting->sync_contact_no = 0;
            $setting->app_notification = 1;
            $setting->save();

            $user_role = Role::findByName('admin');
            $user_role->users()->attach($user->id);
            
            $otp = new OTP();
            $otp->user_id = $user->id;
            // $otp->otp_value = random_int(100000, 999999);
            $otp->otp_value = '1234';
            $otp->save();
            try{
                $account_sid = 'AC60d20bdd51da17c92e5dd29c9f22e521';
                $auth_token = 'bb3720d64d89358fe6915c168f5474d4';
                $twilio_number = '+13158478569';
                
                $receiverNumber = $request->phone_no;
                $message = 'This message from united flooring here is your six digit otp  ' . $otp->otp_value;
                $client = new Client($account_sid, $auth_token);
                $client->messages->create($receiverNumber, [
                    'from' => $twilio_number, 
                    'body' => $message]);
                }
            catch (Exception $e) {
                DB::rollBack();
                $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
                    Helper::errorLogs("AuthService: register", $error);
                    return Helper::returnRecord(GlobalApiResponseCodeBook::INVALID_FORM_INPUTS['outcomeCode'], ['The Phone has not exist.']);
            }

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            // dd($e);
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("AuthService: register", $error);
            return false;
        }
    }

    public function login($request)
    {
        try {
         
            $credentials = $request->only('email', 'password');

            $user = User::whereHas('roles', function ($q) {
                $q->where('name', 'admin');
            })
                ->where('email', '=', $credentials['email'])
                ->first();
            if(isset($user->phone_verified_at) && $user->phone_verified_at !== null){
                if (
                    Hash::check($credentials['password'], isset($user->password) ? $user->password : null)
                    &&
                    $token = $this->guard()->attempt($credentials)
                ) {
    
                    $roles = Auth::user()->roles->pluck('name');
                    $data = Auth::user()->toArray();
                    unset($data['roles']);
    
                    $data = [
                        'access_token' => $token,
                        'token_type' => 'bearer',
                        'expires_in' => $this->guard()->factory()->getTTL() * 60 * 24 * 30,
                        'user' => Auth::user()->only('id', 'name', 'email', 'phone_no','profile_image'),
                        'roles' => $roles,
                        'settings' => Auth::user()->setting->only('user_id', 'private_account', 'secure_payment', 'sync_contact_no', 'app_notification', 'language')
                    ];
                    return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], $data);
                }
                return Helper::returnRecord(GlobalApiResponseCodeBook::INVALID_CREDENTIALS['outcomeCode'], []);
            }
            return Helper::returnRecord(GlobalApiResponseCodeBook::INVALID_CREDENTIALS['outcomeCode'], []);
        } catch (Exception $e) {
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("AuthService: login", $error);
            return false;
        }
    }
    
    public function forgotPassword($request)
    {
        try {
            DB::beginTransaction();
            
                $user = User::whereHas('roles', function ($q) {
                                $q->where('name', 'admin');
                            })
                            ->where('phone_no', $request->phone_number)
                            ->first();
                
                if($user){
                    
                    $otp = new OTP();
                    $otp->user_id = $user->id;
                    // $otp->otp_value = random_int(100000, 999999);
                    $otp->otp_value = '1234';
                    $otp->save();
                    try{
                    $account_sid = 'AC60d20bdd51da17c92e5dd29c9f22e521';
                    $auth_token = 'bb3720d64d89358fe6915c168f5474d4';
                    $twilio_number = '+13158478569';
                    
                    $receiverNumber = $request->phone_number;
                    $message = 'This message from United floor app here is your six digit otp  ' . $otp->otp_value;
                    // dd($message);
                    $client = new Client($account_sid, $auth_token);
                    $client->messages->create($receiverNumber, [
                        'from' => $twilio_number, 
                        'body' => $message]);
    
                    $response = [
                        "message" => "six digit code send your number!",
                        "phone_number" => $request->phone_number
                    ];
                }
                catch (Exception $e) {
                    DB::rollBack();
                    $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
                        Helper::errorLogs("AuthService: register", $error);
                        return Helper::returnRecord(GlobalApiResponseCodeBook::INVALID_FORM_INPUTS['outcomeCode'], ['The Phone has not exist.']);
                }
       
                    DB::commit();
                    return Helper::returnRecord(GlobalApiResponseCodeBook::SUCCESS['outcomeCode'], $response);
                } else {
                    return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode'], ['invalid number!']);
                    
            }
            // return $response;
        } catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("AuthService: forgotPassword", $error);
            return false;
        }
    }

     public function verifyCode($request)
    {
        try {
            if($request->has('email') && isset($request->email))
            {
                $user = User::where('email', $request->email)->first();
            }
            else 
            {
                $user = User::where('phone_no', $request->phone_number)->first();
            }
            $otp = OTP::where('user_id', $user->id)->where('otp_value', $request->code)->first();
            if($otp && $request->has('code') && isset($request->code)){
                
                $user->phone_verified_at = now();
                $user->save();
                OTP::where('user_id', $user->id)->latest()->delete();
                return true;
            }
            
        return false;
        } catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("AuthService: forgotPassword", $error);
            return false;
        }
    }


    public function resetPassword($request)
    {
        try {
            DB::beginTransaction();
            if($request->has('email') && isset($request->email))
            {
                $user = User::where('email', $request->email)->first();
                
                if ($user && Hash::check($request->password, $user->password)) {
                    return intval(GlobalApiResponseCodeBook::RECORD_ALREADY_EXISTS['outcomeCode']);
                }
            }
            else
            {
                $user = User::where('phone_no', $request->phone_number)->first();
                
                if ($user && Hash::check($request->password, $user->password)) {
                    return intval(GlobalApiResponseCodeBook::RECORD_ALREADY_EXISTS['outcomeCode']);
                }
            }
            $record = OTP::where('user_id', $user->id)
                ->where('otp_value', $request->code)->latest()->first();
            if ($record) {
                // $user = User::where('email', $email)->first();
                $user->password = Hash::make($request->password);
                $user->save();
                OTP::where('user_id', $user->id)->latest()->delete();
                
                if($request->has('email') && isset($request->email))
                {
                    $mail_data = [
                        "email" => $request->email
                    ];
                    PasswordResetSuccessfull::dispatch($mail_data);
                }
                

                $response = [
                    'message' => 'Password has been resetted!',
                ];
                DB::commit();
                return $response;
            }
            return intval(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode']);
        } catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("AuthService: resetPassword", $error);
            return false;
        }
    }

    public function logout()
    {
        try {
            Auth::logout();
            return true;
        } catch (Exception $e) {

            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("AuthService: logout", $error);
            return false;
        }
    }
    
    public function resendOtpCode($id)
    {
        try {
            DB::beginTransaction();
            $otp = OTP::where('user_id', $id)->first();
            if ($otp) {
                $otp->otp_value = random_int(100000, 999999);
                $otp->save();

                $user = User::find($id);
                try{
                $account_sid = 'AC60d20bdd51da17c92e5dd29c9f22e521';
                $auth_token = 'bb3720d64d89358fe6915c168f5474d4';
                $twilio_number = '+13158478569';
                
                $receiverNumber = $user->phone_no;
                $message = 'this is your password reset verification code' . $otp->otp_value;
                $client = new Client($account_sid, $auth_token);
                $client->messages->create($receiverNumber, [
                    'from' => $twilio_number, 
                    'body' => $message]);

                $response = [
                    "message" => "six digit code send your number!",
                    "phone_number" => $user->phone_no
                ];
            }
            catch (Exception $e) {
                DB::rollBack();
                $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
                    Helper::errorLogs("AuthService: register", $error);
                    return Helper::returnRecord(GlobalApiResponseCodeBook::INVALID_FORM_INPUTS['outcomeCode'], ['The Phone has not exist.']);
            }

                DB::commit();
                return $response;
            }
            return intval(GlobalApiResponseCodeBook::RECORD_NOT_EXISTS['outcomeCode']);
        } catch (Exception $e) {
            DB::rollBack();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("AuthService: resendOtpCode", $error);
            return false;
        }
    }

    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

     /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }
}