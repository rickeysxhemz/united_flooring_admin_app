<?php

namespace App\Http\Requests\ProjectRequest;

use Illuminate\Foundation\Http\FormRequest;

class AddProjectRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'client_name'=>'required|string',
            'email'=>'required|email',
            'phone'=>'required|string',
            'project_name'=>'required|string',
            'Description'=>'required|string',
            'priority'=>'required|in:low,medium,high',
            'image_url'=>'required|image',
            'status'=>'required|in:in_progress,completed,cancel',
            'category'=>'required|array',
        ];
    }
}
