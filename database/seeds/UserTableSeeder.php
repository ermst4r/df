<?php

use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'company' => 'Dfbuilder.com',
            'name' => 'admin',
            'email' => 'admin@dfbuilder.com',
            'password' => bcrypt('dfbuilder'),
        ]);
    }
}
