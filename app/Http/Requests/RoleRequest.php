<?php

namespace App\Http\Requests;

use App\Rules\PrivilegeSet;
use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'privileges' => ['required', new PrivilegeSet()]
        ];

        switch($this->method())
        {
            case 'POST':
            case 'PUT':
            case 'PATCH':
                return $rules;
            default:
                break;
        }
    }
}
