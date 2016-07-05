<?php

use Illuminate\Database\Seeder;

class RealSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        $permissions = 3 ;
        $permRole = [];
        $id = 1;
        for ($i = 1; $i <= $permissions; $i++) {
            $permRole[] = [
                'id' => $id++,
                'role_id' => 1,
                'permission_id' => $i,
            ];
            $permRole[] = [
                'id' => $id++,
                'role_id' => 2,
                'permission_id' => $i,
            ];
        }
        \DB::table('perm_role')->delete();
        \DB::table('perm_role')->insert($permRole);
        
        
    }
}
