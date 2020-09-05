<?php
/**
 * 角色权限设置的验证规则
 * @package App\Rules
 */
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class PrivilegeSet implements Rule
{
    /**
     * 验证授予的权限是否都是当前账号所拥有的
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $privileges)
    {
        if (!is_array($privileges)) {
            return false;
        }

        $user = Auth::guard('backend')->user();
        $role = $user->role;

        foreach ($privileges as $val) {
            if (!$role->hasPrivilege($val)) {
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
        return '授予的权限包含不属于本账号拥有的权限.';
    }
}
