<?php

use Illuminate\Database\Seeder;

class BranchesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('branches')->delete();
        
        \DB::table('branches')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'สาขาสยามสแควร์',
                'email' => 'info@masterpiececlinic.com',
                'phone' => '026580531',
                'fax' => '026580503',
                'address' => '199/6,201 ถ.พระราม1 แขวงปทุมวัน เขตปทุมวัน กรุงเทพ 10330',
                'desc' => 'สาขาแรก',
                'created_at' => '2016-02-10 20:33:56',
                'updated_at' => '0000-00-00 00:00:00',
                'created_by' => 0,
                'updated_by' => 0,
                'deleted_by' => 0,
                'deleted_at' => NULL,
            ),
        ));
        
    }
}
