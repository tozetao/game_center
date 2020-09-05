<?php

use Illuminate\Database\Seeder;
use App\Models\Backend\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->truncate();

        Role::create([
            'id' => 1,
            'name' => 'admin',
            'created_at' => time(),
            'creator_id' => 0
        ]);
    }
}
