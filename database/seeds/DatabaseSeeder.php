<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\User::create([
            'name'     => 'Diego Mengarda',
            'email'    => 'diegormengarda@gmail.com',
            'password' => bcrypt('102030'),
        ]);
    }
}
