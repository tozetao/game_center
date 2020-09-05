<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecificationAttrsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'specification_attrs';

        DB::table($table)->truncate();

        DB::table($table)->insert([
            'category_id' => 1,
            'spec_name' => '尺码',
            'attr_values' => '["XS", "S", "M", "L", "XL"]',
            'is_mulit' => 0,
            'is_custom' => 1,
            'spec_id' => 1,
        ]);

        DB::table($table)->insert([
            'category_id' => 1,
            'spec_name' => '颜色',
            'attr_values' => '["红色", "白色", "黑色"]',
            'is_mulit' => 0,
            'is_custom' => 1,
            'spec_id' => 2,
        ]);

        DB::table($table)->insert([
            'category_id' => 2,
            'spec_name' => '颜色',
            'attr_values' => '["金色", "银色"]',
            'is_mulit' => 0,
            'is_custom' => 1,
            'spec_id' => 3,
        ]);

        DB::table($table)->insert([
            'category_id' => 3,
            'spec_name' => '尺码',
            'attr_values' => '["36码", "37码", "38码", "39码", "40码", "41码", "42码", "43码"]',
            'is_mulit' => 0,
            'is_custom' => 1,
            'spec_id' => 4,
        ]);
    }
}
