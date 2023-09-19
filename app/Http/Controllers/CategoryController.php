<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Libs\Response\GlobalApiResponse;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    public function __construct(CategoryService $CategoryService, GlobalApiResponse $GlobalApiResponse)
    {
        $this->category_service = $CategoryService;
        $this->global_api_response = $GlobalApiResponse;
    }
    public function addCategory(Request $request){
        $add_category = $this->category_service->addCategory($request);
        if (!$add_category)
            return ($this->global_api_response->error(GlobalApiResponseCodeBook::INTERNAL_SERVER_ERROR, "Category did not added!", $add_category));
        return ($this->global_api_response->success(1, "Category added successfully!", $add_category));
    }
}
