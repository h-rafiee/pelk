<?php

use Illuminate\Database\Seeder;

class AdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('administrators')->insert([
            'name' => 'Mr.Admin',
            'email' => 'admin@administrators.com',
            'username' => 'admin',
            'password' => \Hash::make('admin'),
            'mobile' => '09388916366'
        ]);
    }
}
