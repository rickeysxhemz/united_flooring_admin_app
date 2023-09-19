<?php

namespace App\Services;

use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Libs\Response\GlobalApiResponse;
use App\Models\Project;
use App\Models\User;
use App\Models\Setting;
use App\Models\Comment;
use App\jobs\SendEmailJob;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Exception;
use App\Helper\Helper;

class ProjectService extends BaseService
{
    public function addProject($request)
    {
        // dd($request->all());
        try{
        $user_exist=User::where('email',$request->email)
        ->whereNotNull('remember_token')
        ->first();
        if($user_exist){
            DB::beginTransaction();
            $project = new Project();
            $project->user_id = $user_exist->id;
            $project->name = $request->project_name;
            $project->priority = $request->priority;
            $project->Description = $request->Description;
            $project->logo = Helper::storeImageUrl($request,null,'storage/projectImages');
            $project->status = $request->status;
            $project->save();
            foreach($request->category as $category){
                $project->ProjectCategories()->attach($category);
            }
            DB::commit();
            $mail_data = [
                'email' => $user_exist->email,
                'token' => $user_exist->remember_token,
            ];
            // SendEmailJob::dispactch($mail_data);

            return $project;
        }else {
        DB::beginTransaction();
        $user=new User();
        $user->name=$request->client_name;
        $user->email=$request->email;
        $rm_token=md5(rand(1,10000));
        $user->remember_token=$rm_token;
        $user->password=Hash::make($rm_token);
        $user->save();
        $user->assignRole('user');
        
        $setting=new Setting();
        $setting->user_id = $user->id;
        $setting->private_account = 0;
        $setting->secure_payment = 1;
        $setting->sync_contact_no = 0;
        $setting->app_notification = 1;
        $setting->save();
            
        $project = new Project();
        $project->user_id = $user->id;
        $project->name = $request->project_name;
        $project->priority = $request->priority;
        $project->Description = $request->Description;
        $project->logo = Helper::storeImageUrl($request,null,'storage/projectImages');
        $project->status = $request->status;
        $project->save();
        foreach($request->category as $category){
            $project->ProjectCategories()->attach($category);
        }
        DB::commit();
        $mail_data = [
            'email' => $user->email,
            'token' => $user->remember_token,
        ];
        // SendEmailJob::dispactch($mail_data);
        return $project;
           }
        }catch(Exception $e){
            DB::rollback();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ProjectService: Add-Project", $error);
            return false;
        }
    }
    public function statusAll()
    {
        try{
            $status=[];
            $inProgress=Project::with('ProjectCategories','user')
                        ->where('status','in_progress')
                        ->orderBy('created_at','desc')
                        ->get();
            $completed=Project::with('ProjectCategories','user')
                        ->where('status','completed')
                        ->orderBy('created_at','desc')
                        ->get();
            $cancel=Project::with('ProjectCategories','user')
                        ->where('status','cancel')
                        ->orderBy('created_at','desc')
                        ->get();
            $status['in_progress']=$inProgress;
            $status['completed']=$completed;
            $status['cancel']=$cancel;            
            return $status;
        }catch(Exception $e){
            DB::rollback();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ProjectService: statusAll", $error);
            return false;
        }
    }
    public function Comment($request)
    {
        try{
           
                    DB::beginTransaction();
                    $comment = new Comment();
                    $comment->sender_id = $request->sender_id;
                    $comment->receiver_id = $request->receiver_id;
                    $comment->project_id = $request->project_id;
                    $comment->comment = $request->comment;
                    $comment->save();
                    DB::commit();
                    return $comment;
        }catch(Exception $e){
            DB::rollback();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ProjectService: Comment", $error);
            return false;
        }
    }
    public function getComments($request)
    {
        try{
            $comments=Comment::with('sender','receiver')
            ->where('project_id',$request->project_id)
                        ->orderBy('created_at','desc')
                        ->get();
            return $comments;
        }catch(Exception $e){
            DB::rollback();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ProjectService: getComments", $error);
            return false;
        }
    }
}
