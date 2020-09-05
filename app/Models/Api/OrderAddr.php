<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;

class OrderAddr extends Model
{
    public $timestamps = false;

    public $fillable = [
        'order_id',
        'province',
        'city',
        'area',
        'info',
        'addressee',
        'phone',
    ];

    public function createOrderAddr($data)
    {
        $this->fill($data);
        return $this->save();
    }
}
