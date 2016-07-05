<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('permissions')->delete();
        
        \DB::table('permissions')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'users.view',
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'users.create',
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'users.edit',
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
                'deleted_at' => NULL,
            ),
           
        ));
        
        
    }
}
