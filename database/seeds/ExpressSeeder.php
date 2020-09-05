<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ExpressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('express')->truncate();

        DB::table('express')->insert([
            [
                'express_id' => 1,
                'express_name' => '顺丰'
            ],
            [
                'express_id' => 2,
                'express_name' => '圆通'
            ],
            [
                'express_id' => 3,
                'express_name' => '中通'
            ],
            [
                'express_id' => 4,
                'express_name' => '申通'
            ],
            [
                'express_id' => 5,
                'express_name' => '韵达'
            ]
        ]);
    }
}
