<?php

namespace App\Http\Requests;

use App\Models\Backend\Category;
use App\Models\Backend\SpecificationAttr;
use App\Rules\ValidateSpecAttr;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * spec参数
 *     spec是一个二维数组，包含着要新增或更新的销售规格属性。
 *     每个元素代表着一个销售规格属性，键name是销售规格名字，键attr_values是销售属性值。
 *     在新增销售规格属性时，spec参数的索引没有意义，在更新销售规格属性时，spec参数的索引代表着数据库中原有的记录id。
 */
class CategoryRequest extends FormRequest
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

        switch($this->method())
        {
            case 'POST':
            case 'PUT':
                $rules = [
                    'name' => 'required',
                    'spec' => ['required', new ValidateSpecAttr()]
                ];
        }

        return $rules;
    }

    public function performCreate(Category $category)
    {
        try {
            DB::beginTransaction();

            // 创建商品分类
            $category->name = $this->input('name');
            $category->save();

            $this->insertSpecAttrs($category->id);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::info('code: ' . $e->getCode() . ', message: ' . $e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    protected function insertSpecAttrs($categoryId)
    {
        // 创建该分类的销售属性值
        $specAttrs = [];

        $spec = $this->input('spec');

        foreach ($spec as $row) {
            $specAttrs[] = [
                'category_id' => $categoryId,
                'spec_name'   => $row['name'],
                'attr_values' => json_encode(explode(',', $row['attr_values'])),
                'is_mulit' => 0,
                'is_custom' => 0
            ];
        }

        DB::table('specification_attrs')->insert($specAttrs);
    }

    public function performUpdate(Category $category)
    {
        try {
            DB::beginTransaction();

            // 创建商品分类
            $category->name = $this->input('name');
            $category->save();

            $this->updateSpecAttrs($category->id);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            Log::info('code: ' . $e->getCode() . ', message: ' . $e->getMessage());
            DB::rollBack();
            return false;
        }
    }

    protected function updateSpecAttrs($categoryId)
    {
        $specAttrs = SpecificationAttr::allByCategory($categoryId);

        // 原有规格id
        $originalIDs = $specAttrs->pluck('spec_id')->toArray();

        // 当前规格id
        $currentIDs  = array_keys($this->input('spec'));

        // 要更新的
        $intersect = array_intersect($originalIDs, $currentIDs);

        foreach ($this->input('spec') as $key => $row) {
            if (in_array($key, $intersect)) {
                $specAttr = $this->findSpecAttr($specAttrs, $key);

                if ($specAttr != null) {
                    $specAttr->spec_name = $row['name'];
                    $specAttr->attr_values = json_encode(explode(',', $row['attr_values']));
                    $specAttr->save();
                }
            }
        }

        // 移除的
        $remove = array_diff($originalIDs, $currentIDs);
        SpecificationAttr::whereIn('spec_id', $remove)->delete();

        // 新增的
        $addition = array_diff($currentIDs, $originalIDs);

        if (!empty($addition)) {
            $data = [];

            foreach ($this->input('spec') as $key => $row) {
                if (in_array($key, $addition)) {
                    $data[] = [
                        'category_id' => $categoryId,
                        'spec_name'   => $row['name'],
                        'attr_values' => json_encode(explode(',', $row['attr_values'])),
                        'is_mulit' => 0,
                        'is_custom' => 0
                    ];
                }
            }
            $data != null && DB::table('specification_attrs')->insert($data);
        }
    }

    private function findSpecAttr(&$specAttrs, $specId)
    {
        foreach ($specAttrs as $specAttr) {
            if ($specAttr->spec_id == $specId) {
                return $specAttr;
            }
        }

        return null;
    }
}
