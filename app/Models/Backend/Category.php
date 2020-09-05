<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';

    public $timestamps = false;

    // 类目关联的销售规格
    public function specificationAttrs()
    {
        return $this->hasMany(SpecificationAttr::class, 'category_id');
    }
}
