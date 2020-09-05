<?php

namespace App\Http\Requests;

class CheckpointRequest extends ApiRequest
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
        if ($this->method() == 'POST') {
            return [
                'archive_id'  => 'required|integer|exists:archives,id',
                'number' => 'required|in:1,2,3'
            ];
        }

        return [];
    }

}
