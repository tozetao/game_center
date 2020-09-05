<?php

namespace App\Http\Requests;

use App\Services\ApiResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

/**
 * FormRequest是由FormRequestServiceProvider提供的。
 */
class ApiRequest extends FormRequest
{
    protected function failedValidation(Validator $validator)
    {
        $data = ApiResponse::build(
            ApiResponse::CHECK_ERROR,
            $validator->errors()->first()
        );

        $respone = new Response(json_encode($data));

        throw (new ValidationException($validator, $respone))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }
}