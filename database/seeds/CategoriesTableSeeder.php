<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'categories';

        DB::table($table)->truncate();

        DB::table($table)->insert([
            'id' => 1,
            'name' => '女装',
            'pid'  => 0
        ]);

        DB::table($table)->insert([
            'id' => 2,
            'name' => '饰品',
            'pid'  => 0
        ]);

        DB::table($table)->insert([
            'id' => 3,
            'name' => '鞋靴',
            'pid'  => 0
        ]);
    }
}
