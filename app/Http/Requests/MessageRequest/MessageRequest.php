<?php

namespace App\Http\Requests\MessageRequest;

use Illuminate\Foundation\Http\FormRequest;
use App\Rules\UserRoleValidation;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use App\Libs\Response\GlobalApiResponseCodeBook;
use App\Helper\Helper;
class MessageRequest extends FormRequest
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
            // "sender_id" => "required|exists:users,id",
            'receiver_id' => ['required', 'exists:users,id', new UserRoleValidation('user')],
            "message" => "required|string",    
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        $response = Helper::returnRecord(
            GlobalApiResponseCodeBook::INVALID_FORM_INPUTS['httpResponseCode'],
            "this receiver is not user"
        );

        throw new HttpResponseException(
            JsonResponse::create($response, 422) // 422 is the HTTP status code for Unprocessable Entity
        );
    }
}
