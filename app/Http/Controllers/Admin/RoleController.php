<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Role;
use App\Permission;
use App\Models\Permission_role;
use App\Models\Role_user;
use \Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create(Request $request)
    {
    	//get all Role
    	$allRole = new Role();
    	$allRoles = $allRole->get();
        $role_permission;
        $comic_permission;
        
      

    	for($i=0;$i<count($allRoles);$i++)
    	{
    		$permissionRole =  new Permission_role();
    		$userPermissions = $permissionRole->where('role_id',$allRoles[$i]['id'])->get();
    		$permissions = [];

    		for($j=0;$j<count($userPermissions);$j++)
    		{
    			$permission = new Permission();
    			$permissions[] = $permission->where('id',$userPermissions[$j]['permission_id'])->first();
    		}

    		$allRoles[$i]['permission'] = $permissions;
    	}
    	$allPermission = new Permission();
    	$allPermission = $allPermission->get();

        $sinopsis_permission = [];
        $comment_permission = [];
        $promo_permission = [];
        $report_permission = [];
        $author_permission = [];
        $category_permission = [];
        $subscribtion_permission = [];
        $user_permission = [];
        $role_permission = [];
        //sorting permission

        foreach($allPermission as $permission)
        {
            //get name permission
            $name = explode('-',$permission->name);
            $name = $name[count($name)-1];

            if($name == "sinopsis")
                $sinopsis_permission[] = $permission;

            if($name == "comment")
                $comment_permission[] = $permission;

            if($name == "promo")
                $promo_permission[] = $permission;

            if($name == "report")
                $report_permission[] = $permission;

            if($name == "author")
                $author_permission[] = $permission;

            if($name == "category")
                $category_permission[] = $permission;
            
            if($name == "user")
                $user_permission[] = $permission;

            if($name == "role")
                $role_permission[] = $permission;
        }
        $allPermission = [];
        $allPermission[0]['name'] = 'sinopsis';
        $allPermission[0]['data'] = $sinopsis_permission;
        
        $allPermission[1]['name'] = 'comment';
        $allPermission[1]['data'] = $comment_permission;
       
        $allPermission[2]['name'] = 'promo';
        $allPermission[2]['data'] = $promo_permission;
        
        $allPermission[3]['name'] = 'report';
        $allPermission[3]['data'] = $report_permission;
        
        $allPermission[4]['name'] = 'author';
        $allPermission[4]['data'] = $author_permission;
        
        $allPermission[5]['name'] = 'category';
        $allPermission[5]['data'] = $category_permission;
        
        $allPermission[7]['name'] = 'user';
        $allPermission[7]['data'] = $user_permission;
        
        $allPermission[8]['name'] = 'role';
        $allPermission[8]['data'] = $role_permission;
    
    	return view('admin.role.create',["allRole"=>$allRoles,"allPermission"=>$allPermission]);
    }

    public function insert(Request $request)
    {
    	$input = $request->all();
    	$rules = array(
        	'role'				=> 'required',
        	'permission'		=> 'required',
        	'description'		=> 'required'
    	);
    	$validator = Validator::make($input, $rules);
    	if ($validator->fails()) {
		    // send back to the page with the input data and errors
		    return back()->withErrors($validator);
		  }else {
		  	$permissions = $request->get('permission');
		  	$role = new Role();
		  	$role->name = $request->get('role');
		  	$role->description = $request->get('description');
		  	$role->save();
		  	for($i =0;$i<count($permissions);$i++)
		  	{
		  		$role->attachPermission($permissions[$i]);
		  	}
		}
		$perms = new Permission();
		$perms = $perms->where('name','base-admin')->first();
		$role->attachPermission($perms);
		
    	return redirect()->back();
    }

    public function delete(Request $request,Role $role)
    {
    	if($role->name == 'developer-super-admin')
    	{
    		return redirect()->back();
    	}else{
    		$permisionRole = Permission_role::where('role_id',$role->id)->delete();
    		$role->delete();
    		return redirect()->back();
    	}
    }

    public function assign(Request $request,Role $role)
    {
    	$userData = "NULL";
    	$current_roles = "NULL";
    	if($request->has('email'))
    	{
    		$user = new User();
    		$user = $user->where('email',$request->get('email'))->first();
    		if(count($user)>0)
    		{
    			$userData = $user;
    			$role_user = new Role_user();
    			$current_role = $role_user->where('user_id',$user->id)->get();
    			$roles = [];

    			for($i=0;$i<count($current_role);$i++)
    			{
    				$role = new Role();
    				$role = $role->where('id',$current_role[$i]['role_id'])->first();
    				$roles[] = $role;
    				$current_roles = $roles;
    			}

    		}
    	}
    	$role = new Role();
    	$allRole = $role->get();

    	$allRoleUser = new Role_user();
    	$allRoleUser = $allRoleUser->groupBy('user_id')->get();
    	$allUserWithRole = [];
    	foreach($allRoleUser as $role_user)
    	{
    		$user = new User();
    		$user = $user->where('id',$role_user->user_id)->first();
    		$allUserWithRole[] = $user;
    	}
    	for($i=0;$i<count($allUserWithRole);$i++)
    	{
    		$allUserWithRole[$i]['role'] = [];

    		$role_user = new Role_user();
			$current_role2 = $role_user->where('user_id',$allUserWithRole[$i]['id'])->get();
			$allUserWithRole[$i]['role'] = [];
			$roles2 = [];
			for($j=0;$j<count($current_role2);$j++)
			{
				$role = new Role();
				$role = $role->where('id',$current_role2[$j]['role_id'])->first();
				$roles2[] = $role;
			}
			$allUserWithRole[$i]['role']= $roles2;
    	}
    	return view('admin.role.assign',["allRole"=>$allRole,"user"=>$userData,"current_role"=>$current_roles,'allUserWithRole'=>$allUserWithRole]);
    }

    public function assign_to_user(Request $request,User $user)
    {
    	$role = Role::where("id",$request->get('role'))->first();
    	$user->attachRole($role);
    	return redirect()->back();
    }

    public function unassign_to_user(Request $request, User $user,Role $role)
    {
    	$role_user = new Role_user();
    	$role_user = $role_user->where('user_id',$user->id)
    		->where('role_id',$role->id)->delete();
    	return redirect()->back();
    }
}
