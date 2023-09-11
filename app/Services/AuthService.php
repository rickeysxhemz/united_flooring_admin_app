<?php

namespace App\Services;

use App\Libs\Response\GlobalApiResponseCodeBook;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Helper\Helper;
use App\Models\User;
use App\Models\Setting;
use Exception;
use Spatie\Permission\Models\Role;

class AuthService extends BaseService
{
    public function register($request)
    {
        try {
            DB::beginTransaction();
            
            // $userexist = User::where('email', $request->email)->first();
            // // dd($userexist);
            // if($userexist &&  $userexist->phone_verified_at == null){
               
            //     $phoneexist = User::where('phone_no', $request->phone_no)->first();
                
            //     if($phoneexist &&  $phoneexist->phone_verified_at == null){
                     
            //         $user = User::find($phoneexist->id);
            //         $user->username = $request->username;
            //         // $user->email = $request->email;
            //         $user->password = Hash::make($request->password);
            //         $user->phone_no = $request->phone_no;
            //         $user->zipcode = '97836';
            //         $user->image_url = 'storage/profileImages/default-profile-image.png';
            //         $user->cv_url = null;
            //         $user->save();
                    
            //         $otp = new OTP();
            //         $otp->user_id = $user->id;
            //         $otp->otp_value = random_int(100000, 999999);
            //         // $otp->otp_value = '123456';
            //         $otp->save();
                    
            //         $account_sid = 'AC60d20bdd51da17c92e5dd29c9f22e521';
            //         $auth_token = 'bb3720d64d89358fe6915c168f5474d4';
            //         $twilio_number = '+13158478569';
                    
            //         // $receiverNumber = $request->phone_number;
            //         // $message = 'this is your code';
            //         // $client = new Client($account_sid, $auth_token);
            //         // $client->messages->create($receiverNumber, [
            //         //     'from' => $twilio_number]);
                    
            //         $receiverNumber = $request->phone_no;
            //         $message = 'This message from Nails2u here is your six digit otp  ' . $otp->otp_value;
            //         $client = new Client($account_sid, $auth_token);
            //         $client->messages->create($receiverNumber, [
            //             'from' => $twilio_number, 
            //             'body' => $message]);
                    
            //         DB::commit();
            //         return $user;
            //     }
    
            //     $user = User::find($userexist->id);
            //     $user->username = $request->username;
            //     // $user->email = $request->email;
            //     $user->password = Hash::make($request->password);
            //     $user->phone_no = $request->phone_no;
            //     $user->zipcode = '97836';
            //     $user->image_url = 'storage/profileImages/default-profile-image.png';
            //     $user->cv_url = null;
            //     $user->save();

            //     $otp = new OTP();
            //     $otp->user_id = $user->id;
            //     $otp->otp_value = random_int(100000, 999999);
            //     // $otp->otp_value = '123456';
            //     $otp->save();
                
            //     $account_sid = 'AC60d20bdd51da17c92e5dd29c9f22e521';
            //     $auth_token = 'bb3720d64d89358fe6915c168f5474d4';
            //     $twilio_number = '+13158478569';
                
            //     // $receiverNumber = $request->phone_number;
            //     // $message = 'this is your code';
            //     // $client = new Client($account_sid, $auth_token);
            //     // $client->messages->create($receiverNumber, [
            //     //     'from' => $twilio_number]);
                
            //     $receiverNumber = $request->phone_no;
            //     $message = 'This message from Nails2u here is your six digit otp   ' . $otp->otp_value;
            //     $client = new Client($account_sid, $auth_token);
            //     $client->messages->create($receiverNumber, [
            //         'from' => $twilio_number, 
            //         'body' => $message]);

            //     DB::commit();
            //     return $user;
            // }
            
            // if($userexist &&  $userexist->phone_verified_at !== null){
            //     return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_ALREADY_EXISTS['outcomeCode'], ['The email has already been taken.']);
            // }
            // $phoneexist = User::where('phone_no', $request->phone_no)->first();
            // if($phoneexist &&  $phoneexist->phone_verified_at !== null){
            //     return Helper::returnRecord(GlobalApiResponseCodeBook::RECORD_ALREADY_EXISTS['outcomeCode'], ['The Phone has already been taken.']);
            // }
            
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            // $user->phone_no = $request->phone_no;
            // $user->zipcode = '97836';
            // $user->image_url = 'storage/profileImages/default-profile-image.png';
            // $user->cv_url = null;
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

            // $verify_email_token = Str::random(140);
            // $email_verify = new EmailVerify;
            // $email_verify->email = $request->email;
            // $email_verify->token = $verify_email_token;
            // $email_verify->save();

            // $mail_data = [
            //     'email' => $request->email,
            //     'token' => $verify_email_token
            // ];

            // SendEmailVerificationMail::dispatch($mail_data);

            // $otp = new OTP();
            // $otp->user_id = $user->id;
            // $otp->otp_value = random_int(100000, 999999);
            // $otp->otp_value = '123456';
            // $otp->save();

            // $account_sid = 'AC60d20bdd51da17c92e5dd29c9f22e521';
            // $auth_token = 'bb3720d64d89358fe6915c168f5474d4';
            // $twilio_number = '+13158478569';
            
            // $receiverNumber = $request->phone_no;
            // $message = 'This message from Nails2u here is your six digit otp  ' . $otp->otp_value;
            // $client = new Client($account_sid, $auth_token);
            // $client->messages->create($receiverNumber, [
            //     'from' => $twilio_number, 
            //     'body' => $message]);

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
                $q->where('name', 'user');
            })
                ->where('email', '=', $credentials['email'])
                ->with('setting', 'FavouriteArtist')
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
                        'expires_in' => $this->guard()->factory()->getTTL() * 60,
                        'user' => Auth::user()->only('id', 'username', 'email', 'phone_no', 'address', 'experience', 'cv_url', 'image_url', 'total_balance', 'absolute_cv_url', 'absolute_image_url'),
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