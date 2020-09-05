<?php

namespace App\Http\Requests;

use App\Models\Api\Goods;
use App\Models\Api\Order;
use App\Models\Api\OrderAddr;
use App\Models\Api\UserAddr;
use App\Services\Generator\OrderNoGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class OrderRequest extends ApiRequest
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
        $payRule = sprintf(
            'required|in:%d,%d',
            config('type.pay_channel.wechat_jssdk'),
            config('type.pay_channel.points')
        );

        $rules = [];
        
        switch ($this->method())
        {
            case 'GET':
            case 'POST':
                $rules = [
                    'goods_id' => 'required|exists:goods,id',
                    'quantity' => 'required|integer|min:1',
                    'user_addr_id' => 'required|exists:user_addr,id',
                    'payment_type' => $payRule
                ];
        }

        return $rules;
    }

    public function performCreate(Goods $goods)
    {
        try {
            DB::beginTransaction();

            $order = $this->createOrder($goods);

            $this->createOrderSpecs($goods, $order->order_id);

            $this->createOrderAddr($order->order_id);

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            $log = sprintf('code: %s, message: %s, file: %s, line: %s',
                $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
            Log::info($log);
            DB::rollBack();
            return false;
        }
    }

    private function createOrder(Goods $goods)
    {
        // 插入订单
        $generator = new OrderNoGenerator();

        $data = [
            'uid' => Session::get('user')->uid,
            'order_number'   => $generator->generate(),
            'goods_id'       => $goods->id,
            'title'          => $goods->title,
            'image'          => $goods->image,
            'price'          => $goods->price,
            'discount_price' => $goods->discount_price,
            'category_id'    => $goods->category_id,
            'points'         => $goods->points,
            'quantity'       => $this->input('quantity'),
            'payment_type'   => $this->input('payment_type')
        ];

        $order = new Order();
        $order->createOrder($data);
        return $order;
    }

    private function createOrderSpecs(Goods $goods, $orderId)
    {
        // 插入销售属性规格
        $orderSpecs = [];

        foreach ($goods->goodsSpecs as $goodsSpec) {
            $orderSpecs[] = [
                'order_id' => $orderId,
                'spec_id' => $goodsSpec->spec_id,
                'spec_name' => $goodsSpec->specAttr->spec_name,
                'attr_id' => $goodsSpec->attr_id,
                'attr_val' => $goodsSpec->attr_val
            ];
        }

        DB::table('order_specifications')->insert($orderSpecs);
    }

    private function createOrderAddr($orderId)
    {
        // 插入订单地址
        $userAddr = UserAddr::findOrFail($this->input('user_addr_id'));

        $orderAddr = new OrderAddr();

        $data = [
            'order_id' => $orderId,
            'province' => $userAddr->province,
            'city' => $userAddr->city,
            'area' => $userAddr->area,
            'info' => $userAddr->info,
            'addressee' => $userAddr->addressee,
            'phone' => $userAddr->phone,
        ];

        return $orderAddr->createOrderAddr($data);
    }

}
