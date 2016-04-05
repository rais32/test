<?php

use Illuminate\Database\Seeder;

class userapp_seeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users_app')->insert([
            'name' => 'asdf',
            'phone_number' => '111111'
        ]);
    }
}
