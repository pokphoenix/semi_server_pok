<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('roles')->delete();
        
        \DB::table('roles')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'admin',
                'created_at' => '2015-10-21 10:15:35',
                'updated_at' => '2015-10-28 11:58:28',
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'sale',
                'created_at' => '2015-10-21 10:57:23',
                'updated_at' => '2015-10-27 10:32:28',
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}
