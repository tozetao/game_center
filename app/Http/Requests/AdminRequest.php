<?php

namespace App\Http\Requests;

use App\Models\Backend\Administrator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdminRequest extends FormRequest
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
        switch($this->method())
        {
            case 'POST':
                return [
                    'account' => 'required|max:20',
                    'role_id' => 'required|integer',
                    'password' => 'required'
                ];
            case 'PUT':
                return [
                    'account' => 'required|max:20',
                    'role_id' => 'required|integer'
                ];
        }

        return [];
    }

    public function performCreate(Administrator $model)
    {
        $model->fill($this->all());
        $model->logged_in = 0;
        $model->created_at = time();
        $model->password = bcrypt($this->input('password'));
        $model->creator_id = Auth::guard('backend')->id();
        $model->remember_token = '';
        return $model->save();
    }
}
