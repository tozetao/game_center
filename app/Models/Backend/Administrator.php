<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Administrator extends Authenticatable
{
    public $timestamps = false;

    protected $fillable = ['account', 'role_id'];

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
