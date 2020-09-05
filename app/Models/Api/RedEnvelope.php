<?php

namespace App\Models\Api;

use App\Services\Pay\Unify;
use Illuminate\Database\Eloquent\Model;

class RedEnvelope extends Model implements Unify
{
    public $dateFormat = 'U';

    public $table = 'red_envelope_log';

    public $timestamps = false;

    public static function findByNo($number)
    {
        return self::where('number', $number)->first();
    }

    public function pay()
    {
        $this->status = config('type.red_envelope.paymented');
        $this->pay_at = time();
        return $this->save();
    }

    public function failed()
    {
        $this->status = config('type.red_envelope.failed');
        $this->pay_at = time();
        return $this->save();
    }

    public function send()
    {
        $this->status = config('type.red_envelope.sent');
        $this->sent_at = time();
        return $this->save();
    }

    public function getBody()
    {
        return '红包赠送';
    }

    public function getTotalFee()
    {
        return $this->real_val;
    }

    public function getNumber()
    {
        return $this->number;
    }
}
