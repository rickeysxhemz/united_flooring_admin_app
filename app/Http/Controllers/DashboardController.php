<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Response\GlobalApiResponse;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Services\DashboardService;

class DashboardController extends Controller
{
    public function __construct(DashboardService $DashboardService, GlobalApiResponse $GlobalApiResponse)
    {
        $this->dashboard_service = $DashboardService;
        $this->global_api_response = $GlobalApiResponse;
    }
    public function getUserData()
    {
        $user_data = $this->dashboard_service->getUserData();
        if (!$user_data)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "User data did not fetched!", $user_data));
        return ($this->global_api_response->success(1, "User data fetched successfully!", $user_data));
    }
    public function recentProjects()
    {
        $recent_projects = $this->dashboard_service->recentProjects();
        if (!$recent_projects)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Recent projects did not fetched!", $recent_projects));
        return ($this->global_api_response->success(1, "Recent projects fetched successfully!", $recent_projects));
    }
}
