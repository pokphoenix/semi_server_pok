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
        // $this->call(UsersTableSeeder::class);

//        $this->call('PermissionsTableSeeder');
        $this->call('RolesTableSeeder');
        $this->call('UserTypeTableSeeder');
        $this->call('BranchesTableSeeder');
        $this->call('RealSeeder');
        $this->call('UsersTableSeeder');
    }
}
