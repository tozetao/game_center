<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class GoodsResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $images = [];

        foreach ($this->images as $image) {
            $images[] = $image->path;
        }

        $goodsSpecs = [];

        foreach ($this->goodsSpecs as $goodsSpec) {
            $specName = isset($goodsSpec->specAttr) ? $goodsSpec->specAttr->spec_name : '';

            $goodsSpecs[] = [
                'spec_id' => $goodsSpec->spec_id,
                'spec_name' => $specName,
                'attr_id' => $goodsSpec->attr_id,
                'attr_val' => $goodsSpec->attr_val
            ];
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'first_image' => $this->image,
            'desc' => $this->desc,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'is_exchange' => $this->is_exchange,
            'points' => $this->points,
            'on_sale' => $this->on_sale,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
            'images' => $images,
            'specs' => $goodsSpecs,
        ];
    }
}
