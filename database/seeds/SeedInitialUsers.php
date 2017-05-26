<?php

use Illuminate\Database\Seeder;

class SeedInitialUsers extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user           = new \App\Models\User();
        $user->name     = 'Admin';
        $user->email    = 'admin@ipv6tunnel.io';
        $user->password = bcrypt('admin');
        $user->role     = 'admin';
        $user->save();
    }
}
