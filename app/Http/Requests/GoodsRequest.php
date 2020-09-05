<?php

namespace App\Http\Requests;

use App\Models\Backend\Goods;
use App\Models\Backend\GoodsImage;
use App\MOdels\Backend\GoodsSpecification;
use App\Models\Backend\SpecificationAttr;
use App\Rules\ValidateSpecification;
use App\Services\Converter\RMBConverter;
use App\Services\Flash;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class GoodsRequest extends FormRequest
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
        $rules = [];

        switch ($this->method())
        {
            case 'POST':
            case 'PUT':
                $rules = [
                    'goods_number' => 'required|string|min:2|max:20',
                    'title' => 'required|string|min:2|max:45',
                    'desc' => 'required|string|min:2|max:100',
                    'image_list' => 'required|json',
                    'price' => 'required|numeric|min:0.1',
                    'discount_price' => 'required|numeric|min:0.1',
                    'is_exchange' => 'in:0,1',
                    'points' => 'required_if:is_exchange,1|integer|min:0',
                    'category_id' => 'required|integer',
                    'on_sale' => 'in:0,1',
                    'stock' => 'required|integer|min:1',
                    'spec' => ['required', new ValidateSpecification()],
                    'postage_id' => 'integer'
                ];
        }

        return $rules;
    }

    public function performCreate(Goods $goods)
    {
        try {
            DB::beginTransaction();

            $this->saveGoods($goods);

            $this->insertGoodsImages($goods->id);

            $this->insertGoodsSpec($goods->id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::info('code: ' . $e->getCode() . ', message: ' . $e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    public function performUpdate(Goods $goods)
    {
        try {
            DB::beginTransaction();

            $this->saveGoods($goods);

            GoodsSpecification::where('goods_id', $goods->id)->delete();

            $this->insertGoodsSpec($goods->id);

            GoodsImage::where('goods_id', $goods->id)->delete();

            $this->insertGoodsImages($goods->id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::info('code: ' . $e->getCode() . ', message: ' . $e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    protected function saveGoods(Goods $goods)
    {
        $imageList = json_decode($this->input('image_list'), true);

        // 处理商品基本数据
        $goods->fill($this->all());

        // 索引0是默认主图
        $goods->image       = $imageList[0];
        $goods->is_exchange = $this->input('is_exchange', 0);
//        $goods->points  = $this->input('points') != '' ?  $this->input('points') : 0;
        $goods->on_sale = $this->input('on_sale', 0);
        $goods->postage_id = $this->input('postage_id', 0);
        $goods->points  = $this->input('points', 0);
        $goods->price = RMBConverter::yuantofen($this->input('price'));
        $goods->discount_price = RMBConverter::yuantofen($this->input('discount_price'));
        $goods->save();
    }

    // 插入商品图片
    protected function insertGoodsImages($goodsId)
    {
        $imageList = json_decode($this->input('image_list'), true);

        // 处理商品图片
        $goodsImages = [];

        for ($i = 1; $i < count($imageList); $i++)
        {
            $goodsImages[] = [
                'goods_id' => $goodsId,
                'path' => $imageList[$i]
            ];
        }

        DB::table('goods_images')->insert($goodsImages);
    }

    // 处理销售属性
    protected function insertGoodsSpec($goodsId)
    {
        $specAttrs = [];

        foreach ($this->input('spec') as $key => $attr) {
            $specAttrs[] = [
                'attr_val' => $attr,
                'goods_id' => $goodsId,
                'spec_id'  => $key
            ];
        }

        DB::table('goods_specifications')->insert($specAttrs);
    }
}
