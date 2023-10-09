<?php
namespace App\Services;

use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Libs\Response\GlobalApiResponse;
use App\Helper\Helper;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\DB;


class DashboardService extends BaseService
{
    public function getUserData()
    {
        try{
            $user_data=[];
            $user=auth()->user();
            $PreeningProjectsCount = Project::where('admin_id', auth()->user()->id)
            ->where('status', 'in_progress')
            ->count();
            $user_data['PreeningProjectsCount']=$PreeningProjectsCount;
            $user_data['user']=$user;
            return $user_data;
        }catch(Exception $e){
            DB::rollback();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("DashboardService: getUserData", $error);
            return false;
        }
    }
    public function recentProjects()
    {
        try{
            $recent_projects=Project::where('admin_id', auth()->user()->id)
                            ->with('ProjectCategories','user')
                            ->where('status','in_progress')
                            ->latest('created_at')
                            ->get();
            return $recent_projects;
        }catch(Exception $e){
            DB::rollback();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("ProjectService: getProjects", $error);
            return false;
        }
    }
    public function userDeviceToken($request)
    {
        try{
            DB::beginTransaction();
            $user=auth()->user();
            $user->device_token=$request->device_token;
            $user->save();
            DB::commit();
            return $user;
        }catch(Exception $e){
            DB::rollback();
            $error = "Error: Message: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("DashboardService: userDeviceToken", $error);
            return false;
        }
    }
}