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
    public function addProject(Request $request){
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
}
