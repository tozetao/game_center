<?php

namespace App\Http\Requests;

use App\Models\Api\PropOrder;
use App\Models\Api\Order;
use App\Models\Api\Prop;
use App\Services\Generator\OrderNoGenerator;
use App\Services\Logger;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PropOrderRequest extends ApiRequest
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
                $rules = [
                    'prop_id' => 'required|exists:props,prop_id'
                ];
        }

        return $rules;
    }

    public function performCreate($props)
    {
        try {
            DB::beginTransaction();

            // 创建订单
            $generator = new OrderNoGenerator();

            $data = [
                'user_id' => Session::get('user')->uid,
                'number'  => $generator->generate(),
                'payment_type' => config('type.pay_channel.wechat_jssdk'),
                'status'  => config('type.order.unpaid')
            ];

            $order = new PropOrder();
            $order->createOrder($data);

            // 创建道订单主体
            $rows = [];
            foreach ($props as $prop) {
                $rows[] = [
                    'prop_id' => $prop->prop_id,
                    'prop_name' => $prop->prop_name,
                    'image' => $prop->image,
                    'price' => $prop->price,
                    'prop_order_id' => $order->id,
                ];
            }
            DB::table('prop_order_body')->insert($rows);

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollback();
            Logger::info($e);
            return false;
        }
    }
}
