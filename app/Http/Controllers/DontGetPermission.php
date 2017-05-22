<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permission;

class DontGetPermission extends Controller
{
    public function index(Request $request, $permission)
    {
    	return view('errors.permission',['permission'=>$permission]);
    }
}
