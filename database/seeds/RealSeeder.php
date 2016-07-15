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

        $permArray = ['users','usertype'] ;
        $permissions = [];
        $id = 1;
        for ($i = 1; $i <= count($permArray); $i++) {
            $permissions[] = [
                'id' => $id++,
                'name' => $permArray[$i-1].".view",
            ];
            $permissions[] = [
                'id' => $id++,
                'name' => $permArray[$i-1].".create",
            ];
            $permissions[] = [
                'id' => $id++,
                'name' => $permArray[$i-1].".edit",
            ];
            $permissions[] = [
                'id' => $id++,
                'name' => $permArray[$i-1].".delete",
            ];
        }
        \DB::table('permissions')->delete();
        \DB::table('permissions')->insert($permissions);


        $permRole = [];
        $id = 1;
        for ($i = 1; $i <= count($permissions); $i++) {
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
