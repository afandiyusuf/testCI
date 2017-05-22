<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\ReturnData; //class object for return data
use Illuminate\Http\Response;
use \Firebase\JWT\JWT;
use \Validator;


class UserController extends Controller
{

    public function register(Request $request){
    	$retData = new ReturnData();
    	
    	$validator =  Validator::make($request->all(), [
	        'name' => 'required|max:100|min:5',
	        'alamat' => 'required',
	        'gender' => 'required|digits:1',
	        'no_hp' => 'required|digits_between:5,20',
	        'username' => 'required|unique:users',
	        'password' => 'required',
	        'email' => 'required|unique:users|email'
    	]);

    	if ($validator->fails())
		{
    	 	$retData->set(__('api.validation_error'),406,array("message"=>$validator->errors()));
		}else{
			$reqData = $request->all();

            $reqData['password'] = bcrypt($reqData['password']);
			$requestData = $request->all();
			$userModel = new User($reqData);
            $userModel->save();
            $reqData['id'] = $userModel->id;

			$userModel['access_token'] = $this->Generate_JWT($reqData);
			


			$retData->set('Success',200,$userModel);
		}

		return response()->json($retData,$retData->code);
    }

    public function login(Request $request){
    
    	$retData = new ReturnData();
    	
    	$validator = Validator::make($request->all(),[
    		"username" => "required",
    		"password" => "required"
    	]);
        

    	if ($validator->fails())
		{
			$retData->set(__('api.validation_error'),406,array("message"=>$validator->errors()));
    	}else{
            $input = $request->all();
    		$user = new User;
            $user = $user->get_data_by_username_password($input['username'],$input['password']);

    		if(empty($user))
    		{
    			$retData = new ReturnData();
    			$retData->set(__('api.login_error'),406,[]);
    			//password or username salah
    		}else{
    			$user['access_token'] = $this->Generate_JWT($user);
    			$retData = new ReturnData();
    			$retData->set(__('api.login_success'),200,$user);
    		}
    	}

    	return response()->json($retData,$retData->code);
    }

    public function update(Request $request)
    {
      
        // return $request;
        
        $retData = new ReturnData();
        //validate input from users
        $validator =  Validator::make($request->all(), [
            'name' => 'required|max:100|min:5',
            'alamat' => 'required',
            'gender' => 'required|digits:1',
            'no_hp' => 'required|digits_between:5,20'
        ]);

        if ($validator->fails())
        {
            $retData->set(__('api.validation_error'),406,array("message"=>$validator->errors()));
        }else{
            $inputData = $request->all();
            $userModel = User::find($inputData['id']);
            $userModel->name = $inputData['name'];
            $userModel->alamat = $inputData['alamat'];
            $userModel->gender = $inputData['gender'];
            $userModel->no_hp = $inputData['no_hp'];
            $userModel->save();
            $retData->set(__('api.success'),200,$userModel);
        }

        return response()->json($retData,$retData->code);
    }

    public function Hash_Password($password)
    {
    	return bcrypt($password);
    }
    public function Generate_JWT($data)
    {
    	$key = env('JWT_SECRET','no kes');
    	$date = new \DateTime;
        unset($data['password']);
		$token = array(
		    "sub" => "access_token",
		    "iat" => $date->format('U'),
		    "data" => $data
		);
		$jwt = JWT::encode($token, $key);
		return $jwt;
    }
  
}
