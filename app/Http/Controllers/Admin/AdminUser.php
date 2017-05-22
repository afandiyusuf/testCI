<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Input;

class AdminUser extends Controller
{
	public $DataPerPage = 50;
	public function __construct()
    {
        $this->middleware('auth');
    }

    public function list_data($page = 1,$data = 20)
    {	
    	
        //return $user;
    	return view('admin.user.list');
    }

    public function get_user()
    {
        $retData = array();

        $start = Input::get('start');
        $length = Input::get('length');
        $draw = Input::get('draw');

        if($start != 0)
            $user = User::take($length)->skip($start)->get();
        else
            $user = User::take($length)->get();
        
        $total = User::count();
        $retData = array('draw'=>++$draw,'recordsTotal'=>$total,'recordsFiltered'=>$total,'data'=>[]);

        $data = [];

        for($i = 0;$i<count($user);$i++)
        {
            $userData = [];
            $userData['No'] = $i+1  + $start;
            $userData['Name'] = $user[$i]->name;
            $userData['Username'] = $user[$i]->username;
            $userData['Email'] = $user[$i]->email;
            $userData['Gender'] = $user[$i]->gender;
            $userData['Alamat'] = $user[$i]->alamat;
            $userData['No Hp'] = $user[$i]->no_hp;
            $data[] = $userData;
        }
        $retData['data'] = $data;
        return response()->json($retData);
    }
}


