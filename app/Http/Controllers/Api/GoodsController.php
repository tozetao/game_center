<?php

namespace App\Http\Controllers\Api;

use App\Filters\GoodsFilter;
use App\Models\Api\Goods;
use App\Services\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Resources\GoodsResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class GoodsController extends Controller
{
    public function search(Request $request, GoodsFilter $filter)
    {
        $this->validateSearch($request->all());

        $goods = new Goods();
        $collection = $goods->search($filter, $request->input('page'));

        return GoodsResource::collection($collection);
    }

    // 参数验证
    private function validateSearch($params)
    {
        $validator = Validator::make($params, [
            'is_exchange' => 'in:0,1',
            'title'        => 'string|max:20',
            'category_id'  => 'integer'
        ]);

        if ($validator->fails()) {
            return ApiResponse::throwValidationException(
                $validator,
                ApiResponse::CHECK_ERROR,
                $validator->errors()->first()
            );
        }
    }


    // 查询商品总数
    public function total(GoodsFilter $filter)
    {
        $goods = new Goods();
        return ['total' => $goods->total($filter)];
    }

    public function show($goodsId)
    {
        $goods = Goods::find($goodsId);

        if (empty($goods)) {
            return ApiResponse::failed(ApiResponse::NOT_FOUND);
        }

        return new GoodsResource($goods);
    }
}
