<?php

namespace App\Http\Controllers;
use App\Libs\Response\GlobalApiResponse;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Services\ProjectService;
use Illuminate\Http\Request;
use App\Http\Requests\ProjectRequest\CommentRequest;
use App\Http\Requests\ProjectRequest\GetCommentRequest;
use App\Http\Requests\ProjectRequest\AddProjectRequest;
use App\Http\Requests\ProjectRequest\InfoRequest;
class ProjectController extends Controller
{
    public function __construct(ProjectService $ProjectService, GlobalApiResponse $GlobalApiResponse)
    {
        $this->project_service = $ProjectService;
        $this->global_api_response = $GlobalApiResponse;
    }
    public function addProject(AddProjectRequest $request){
        $add_project = $this->project_service->addProject($request);
        if (!$add_project)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Project did not added!", $add_project));
        return ($this->global_api_response->success(1, "Project added successfully!", $add_project));
    }
    public function statusAll()
    {
        $status = $this->project_service->statusAll();
        if (!$status)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Status did not fetched!", $status));
        return ($this->global_api_response->success(1, "Status fetched successfully!", $status));
    }
    public function comment(CommentRequest $request)
    {
        $comment = $this->project_service->comment($request);
        if (!$comment)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Comment did not added!", $comment));
        return ($this->global_api_response->success(1, "Comment added successfully!", $comment));
    }
    public function getComments(GetCommentRequest $request)
    {
        $get_comments = $this->project_service->getComments($request);
        if (!$get_comments)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Comments did not fetched!", $get_comments));
        return ($this->global_api_response->success(1, "Comments fetched successfully!", $get_comments));
    }
    public function uploadImages(Request $request)
    {
        $upload_images = $this->project_service->uploadImages($request);
        if (!$upload_images)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Images did not uploaded!", $upload_images));
        return ($this->global_api_response->success(1, "Images uploaded successfully!", $upload_images));
    }
    public function info(InfoRequest $request)
    {
        $info = $this->project_service->info($request);
        if (!$info)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Project Update Info did not fetched!", $info));
        return ($this->global_api_response->success(1, "Product Update Info fetched successfully!", $info));
    }
    public function recentProjects()
    {
        $recent_projects = $this->project_service->recentProjects();
        if (!$recent_projects)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Recent Projects did not fetched!", $recent_projects));
        return ($this->global_api_response->success(1, "Recent Projects fetched successfully!", $recent_projects));
    }
    public function getProjects()
    {
        $get_projects = $this->project_service->getProjects();
        if (!$get_projects)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Projects did not fetched!", $get_projects));
        return ($this->global_api_response->success(1, "Projects fetched successfully!", $get_projects));
    }
    public function readComment(Request $request)
    {
        $read_comment = $this->project_service->readComment($request);
        if (!$read_comment)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Comment did not read!", $read_comment));
        return ($this->global_api_response->success(1, "Comment read successfully!", $read_comment));
    }
}
