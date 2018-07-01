<?php

use Illuminate\Database\Seeder;
use App\Role;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $roles = [
        	['title' => 'Admin'],
        	['title' => 'Cooker'],
        	['title' => 'Cashier'],
        	['title' => 'Waiter'],
        	['title' => 'Part-time'],
        ];
        Role::insert($roles);
    }
}
