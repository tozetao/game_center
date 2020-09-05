<?php
/**
 * User: zetao
 * Date: 2019/8/15
 * Time: 18:21
 */

namespace App\Services;

use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class ApiResponse
{
    const SUCCESS       = 0;
    const FAILED        = 10000;

    const UPLOAD_FAILED = 20001;
    const INVALID_FILE  = 20002;
    const FORBIDDEN_FILE_TYPE = 20003;
    const FILE_SIZE_LIMIT = 20004;

    const ORDER_CREATE_FAIL = 20105;
    const UNIFY_ORDER_ERROR      = 20106;
    const ORDER_ILLEGAL      = 20107;
    const ORDER_PAY_FAIL     = 20108;
    const UNPAID_ORDER       = 20109;

    const UNAUTH       = 30001;
    const CHECK_ERROR  = 30002;
    const ARCHIVE_LIMIT = 30003;

    const NOT_FOUND     = 40001;

    public static $MessageMap = [
        self::SUCCESS               => '操作成功!',
        self::FAILED                => '操作失败!',

        self::UPLOAD_FAILED         => '上传失败.',
        self::INVALID_FILE          => '无效的文件.',
        self::FORBIDDEN_FILE_TYPE   => '禁止的文件类型',
        self::FILE_SIZE_LIMIT       => '文件大小超出限制',

        self::ORDER_CREATE_FAIL     => '订单创建失败.',
        self::ORDER_ILLEGAL         => '非法订单或订单状态异常.',
        self::ORDER_PAY_FAIL        => '订单支付失败!',
        self::UNPAID_ORDER          => '未支付的订单',

        self::UNAUTH                => '未认证的用户!',
        self::CHECK_ERROR           => '参数验证错误!',

        self::NOT_FOUND             => '找不到web资源!',
        self::ARCHIVE_LIMIT         => '存档数量超出限制.'
    ];

    public static function success()
    {
        return response()->json([
            'err_code' => 0,
            'err_msg'  => '操作成功'
        ]);
    }

    public static function failed($code, $msg = null)
    {
        if (empty($msg)) {
            $msg = self::$MessageMap[$code];
        }

        return response()->json([
            'err_code' => $code,
            'err_msg'  => $msg
        ]);
    }

    public static function build($code, $msg = null)
    {
        if (empty($msg)) {
            $msg = self::$MessageMap[$code];
        }

        return [
            'err_code' => $code,
            'err_msg'  => $msg
        ];
    }

    public static function throwValidationException($validator, $code, $msg = null)
    {
        $data = self::build($code, $msg);
        $response = new Response(json_encode($data));
        throw new ValidationException($validator, $response);
    }
}