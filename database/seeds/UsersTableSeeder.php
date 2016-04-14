<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@localhost.com',
            'remember_token' => Hash::make('admin@localhost.com'),
            'password' => Hash::make('123456')
        ]);
    }
}
