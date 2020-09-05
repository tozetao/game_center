<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateSpecAttr implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($value as $row) {
            if (empty($row['name']) || empty($row['attr_values'])) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '错误的销售规格属性值.';
    }
}
