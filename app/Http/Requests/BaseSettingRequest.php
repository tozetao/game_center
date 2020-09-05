<?php

namespace App\Http\Requests;

use App\Models\Api\BaseSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class BaseSettingRequest extends ApiRequest
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
        // archive_id, my_name, other_name, hair, clothes

        if ($this->method() == 'POST') {
            $hairRule = ['integer'];
            $clothesRule = $hairRule;

            $ignoreProps = [1, 11, 21, 31];

            if (!in_array($this->post('hair'), $ignoreProps)) {
                $hairRule[] = Rule::exists('my_props', 'prop_id')->where(function($query) {
                    $query->where('uid', Session::get('user')->uid);
                });
            }

            if (!in_array($this->post('clothes'), $ignoreProps)) {
                $clothesRule[] = Rule::exists('my_props', 'prop_id')->where(function($query) {
                    $query->where('uid', Session::get('user')->uid);
                });
            }

            return [
                'archive_id' => 'required|integer|exists:archives,id',
                'my_name'    => 'string|max:30',
                'other_name' => 'string|max:30',
                'hair'       => $hairRule,
                'clothes'    => $clothesRule
            ];
        }

        return [];
    }

    public function performUpdate(BaseSetting $model)
    {
        $user = Session::get('user');

        $model->fill($this->all());
        $model->master_id = $user->uid;

        return $model->save();
    }
}
