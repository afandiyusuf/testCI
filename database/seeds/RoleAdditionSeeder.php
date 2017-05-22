<?php

use Illuminate\Database\Seeder;
use \App\Models\User;
use \App\Permission;
use \App\Role;
use \App\Models\Permission_role;
use Carbon\Carbon;

class RoleAdditionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	$permission = new Permission();
    	$developer_super_admin = Role::where('name','developer-super-admin')->first();
    	if($permission->where('name','=','open-role')->count() == 0){
        	$openRole = new Permission();
			$openRole->name = "open-role";
			$openRole->display_name ="open role";
			$openRole->description = "open page role di admin panel";
			$openRole->save();
			$developer_super_admin->attachPermission($openRole);
		}

		if($permission->where('name','=','open-role')->count() == 0){
			$deleteRole = new Permission();
			$deleteRole->name = "delete-role";
			$deleteRole->display_name = "delete role";
			$deleteRole->description = "delete role yang sudah dibuat";
			$deleteRole->save();
			$developer_super_admin->attachPermission($deleteRole);
		}
		
		
		
		for($i=0;$i<36;$i++)
		{
			$permission = Permission::where('id',$i)->first();
			if(Permission_role::where('permission_id',$i)->where('role_id',$developer_super_admin->id)->count() == 0){
				if(count($permission) == 1){
					$developer_super_admin->attachPermission($permission);
				}
			}
		}
    }
}
