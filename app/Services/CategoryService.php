<?php
namespace App\Services;

use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Libs\Response\GlobalApiResponse;
use App\Helper\Helper;
use App\Models\Category;
use Illuminate\Support\Facades\DB;
class CategoryService extends BaseService
{
    public function addCategory($request)
    {
        try
        {
        DB::beginTransaction();
        $category = new Category();
        $category->name = $request->name;
        $category->image_url =Helper::storeImageUrl($request,null,'storage/categoryImages');
        $category->save();
        DB::commit();
        return $category;
    }catch(Exception $e){
        DB::rollback();
        $error = "Error: Category: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
        Helper::errorLogs("CategoryService: addCategory", $error);
        return false;     
             }
        }
    public function getCategories()
    {
        try
        {
            $categories=Category::all();
            return $categories;
        }catch(Exception $e){
            DB::rollback();
            $error = "Error: Category: " . $e->getMessage() . " File: " . $e->getFile() . " Line #: " . $e->getLine();
            Helper::errorLogs("CategoryService: getCategories", $error);
            return false;     
        }
    }
}
