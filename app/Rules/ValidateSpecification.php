<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateSpecification implements Rule
{
    /**
     * 验证销售属性字段
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $values)
    {
        // 字段值是键值对的数组，key是销售规格id，因此要确保存储在表中。

        foreach ($values as $key => $value) {
            if (!is_numeric($key)) {
                return false;
            }

            if (empty($value)) {
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
        return '空或者无效的销售属性!';
    }
}
