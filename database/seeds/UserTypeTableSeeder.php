<?php

use Illuminate\Database\Seeder;

class UserTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('user_type')->delete();

        \DB::table('user_type')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'name' => 'ผู้ดูแลระบบ',
                    'created_by' => 0,
                    'updated_by' => 0,
                    'deleted_by' => 0,
                    'deleted_at' => NULL,
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'ผู้บริหาร',
                    'created_by' => 0,
                    'updated_by' => 0,
                    'deleted_by' => 0,
                    'deleted_at' => NULL,
                ),
            2 =>
                array (
                    'id' => 3,
                    'name' => 'แพทย์',
                    'created_by' => 0,
                    'updated_by' => 0,
                    'deleted_by' => 0,
                    'deleted_at' => NULL,
                ),
            3 =>
                array (
                    'id' => 4,
                    'name' => 'ผู้ช่วยหัตถการ',
                    'created_by' => 0,
                    'updated_by' => 0,
                    'deleted_by' => 0,
                    'deleted_at' => NULL,
                ),
            4 =>
                array (
                    'id' => 5,
                    'name' => 'พนักงานทั่วไป',
                    'created_by' => 0,
                    'updated_by' => 0,
                    'deleted_by' => 0,
                    'deleted_at' => NULL,
                ),
            5 =>
                array (
                    'id' => 6,
                    'name' => 'ธุรการ',
                    'created_by' => 0,
                    'updated_by' => 0,
                    'deleted_by' => 0,
                    'deleted_at' => NULL,
                ),
            6 =>
                array (
                    'id' => 7,
                    'name' => 'พนักงานขาย',
                    'created_by' => 0,
                    'updated_by' => 0,
                    'deleted_by' => 0,
                    'deleted_at' => NULL,
                ),
        ));
    }
}
