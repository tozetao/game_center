<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            PrivilegesSeeder::class,
            AdministratorsSeeder::class,
//            CategoriesTableSeeder::class,
//            SpecificationAttrsTableSeeder::class,
//            GoodsSeeder::class,
            PropsSeeder::class,
            ExpressSeeder::class,
            AreasSeeder::class
        ]);
    }
}
