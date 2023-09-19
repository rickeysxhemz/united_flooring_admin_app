<?php

namespace App\Helper;

use App\Models\ErrorLog;
class Helper
{
    public static function errorLogs($function_name, $error)
    {
        $error_log = new ErrorLog;
        $error_log->function_name = $function_name;
        $error_log->exception = $error;
        $error_log->save();
    }

    public static function storeImageUrl($request, $user, $path)
    {
        if (!$request->hasFile('image_url')) {
            return false;
        }
        if ($user) {
            if ($user->image_url != 'storage/profileImages/default-profile-image.png') {
                unlink(base_path() . '/public/' . $user->image_url);
            }
        }
        $file = $request->File('image_url');
        $file_name = $file->hashName();
        $request->image_url->move(public_path($path), $file_name);
        $destination = $path . '/' . $file_name;
        return $destination;
    }
    public static function storeImage($request, $path)
    {
        if (!$request->hasFile('image_url')) {
            return false;
        }
        $file = $request->File('image_url');
        $file_name = $file->hashName();
        $request->image_url->move(public_path($path), $file_name);
        $destination = $path . '/' . $file_name;
        return $destination;
    }

    
    public static function returnRecord($outCome = null, $record = null)
    {
        return ['outcomeCode' => intval($outCome), 'record' => $record];
    }

}