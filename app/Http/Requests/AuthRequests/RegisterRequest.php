<?php

namespace App\Http\Requests\AuthRequests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'bail|required|string|max:40',
            //  'email' => 'bail|required|email|unique:users,email|max:255|min:4',
            'email' => 'bail|required|email|max:255|min:4',
            'password' => 'bail|required|string|max:255|min:8',
            // 'phone_no' => 'bail|required|unique:users,phone_no',
            // 'phone_no' => 'bail|required',
        ];
    }
}
