<?php

use App\Models\Backend\Administrator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdministratorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('administrators')->truncate();

        Administrator::create([
            'account' => 'admin',
            'role_id' => 1,
            'password' => bcrypt('123123'),
            'creator_id' => 0,
            'remember_token' => ''
        ]);
    }
}
