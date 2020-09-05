<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class OrderResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $orderSpecs = [];

        foreach ($this->orderSpecs as $orderSpec) {
            $orderSpecs[] = [
                'spec_name' => $orderSpec->spec_name,
                'attr_val'  => $orderSpec->attr_val
            ];
        }

        return [
            'order_no' => $this->order_number,
            'title' => $this->title,
            'image' => $this->image,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'payment_type' => $this->payment_type,
            'quantity' => $this->quantity,
            'created_at' => $this->created_at->timestamp,
            'paid_at' => $this->paid_at,
            'status' => $this->status,
            'order_specs' => $orderSpecs
        ];
    }
}
