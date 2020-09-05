<?php

namespace App\Models\Backend;

use Illuminate\Database\Eloquent\Model;

class SpecificationAttr extends Model
{
    public $table = 'specification_attrs';

    public $primaryKey = 'spec_id';

    public $timestamps = false;

    // 根据分类查询所有销售属性
    public static function allByCategory($categoryId)
    {
        if (empty($categoryId)) {
            return [];
        }

        return self::select('spec_id', 'spec_name', 'category_id', 'attr_values', 'is_mulit', 'is_custom')
            ->when(!empty($categoryId), function($query) use($categoryId) {
                $query->where('category_id', $categoryId);
            })->get();
    }

    public function translateAttrValues()
    {
        $attrValues = json_decode($this->attr_values, true);

        if (is_array($attrValues)) {
            return implode(',', $attrValues);
        }

        return '';
    }
}
