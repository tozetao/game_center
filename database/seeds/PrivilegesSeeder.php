<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PrivilegesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $table = 'role_privilege';

        DB::table($table)->truncate();

        DB::table($table)->insert([
            ['role_id' => 1, 'privilege' => 1],
            ['role_id' => 1, 'privilege' => 2],
            ['role_id' => 1, 'privilege' => 3],
            ['role_id' => 1, 'privilege' => 4],
            ['role_id' => 1, 'privilege' => 5],
            ['role_id' => 1, 'privilege' => 6],
            ['role_id' => 1, 'privilege' => 7],
            ['role_id' => 1, 'privilege' => 8],
            ['role_id' => 1, 'privilege' => 9],

            ['role_id' => 1, 'privilege' => 10],
            ['role_id' => 1, 'privilege' => 11],
            ['role_id' => 1, 'privilege' => 12],
            ['role_id' => 1, 'privilege' => 13],

            ['role_id' => 1, 'privilege' => 14],
            ['role_id' => 1, 'privilege' => 15],
            ['role_id' => 1, 'privilege' => 16],


            ['role_id' => 1, 'privilege' => 20],
            ['role_id' => 1, 'privilege' => 21],

            ['role_id' => 1, 'privilege' => 40],
            ['role_id' => 1, 'privilege' => 41],
        ]);
    }
}
