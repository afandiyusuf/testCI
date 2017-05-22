<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordReset;
use \Session;
use \Validator;
use \Mail;
use \Mailgun\Mailgun;

class ForgotPassword extends Controller
{
    public function index()
    {
    	return view('auth.passwords.email');
    }

    public function checkEmail(Request $request)
    {
    	$emailUser =  $request->get('email');
    	$user = new User();
    	$user = $user->where('email',$emailUser)->get();
    	
    	Session::flash('password_status','cek email untuk penggantian password');

    	if(count($user)==1)
    	{
    		$token = $this->generateRandomString(40);
    		$password = new PasswordReset();
    		$password->email = $request->get('email');
    		$password->token = $token;
    		$password->save();

    		//send email confirmation here
	    	$link = url('forgot/attemp/'.$emailUser.'/'.$token);
            $mgClient = new Mailgun('key-f762b77b37d9005d600ea6eb74d4cd94');
            $domain = "komikng.com";

            # Make the call to the client.
            $result = $mgClient->sendMessage($domain, array(
                'from'    => 'komikngSistem <sistem@komikng.com>',
                'to'      => 'Lovely User <'.$emailUser.'>',
                'subject' => 'Password Reset',
                'html'    => 'Kamu baru saja request password reset, klik link ini untuk langkah selanjutnya <br> <a href="'.$link.'"> '.$link.'</a>'
            ));
    	}

    	return back();
    }

    public function attemp(Request $request,$email,$token)
    {
    	$password = new PasswordReset();
    	$check = $password->where('email',$email)
    	->where('token',$token)
    	->count();
    	$accept = false;
    	if($check == 1)
    	{
    		$accept = true;
    	}
    	return view('auth.passwords.reset',
    		[
    			'accept'=>$accept,
    			'email' =>$email
    		]);
    }

    public function reset(Request $request)
    {
    	$rules = array(
        	'password'				=> 'required|confirmed',
        	'email'					=> 'required'
    	);
    	$validator = Validator::make($request->all(), $rules);

    	if ($validator->fails()) {
		    // send back to the page with the input data and errors
		    return back()->withErrors($validator);
		  }else {
	    	//destroy token session
	    	$pass = new PasswordReset();
	    	$pass = $pass->where('email',$request->get('email'));
	    	$pass->delete();

	    	$user = new User();
	    	$user = $user->where('email',$request->get('email'));
	    	$user->update(['password'=>bcrypt($request->get('password'))]);

	    	return view('auth.passwords.success');
	    }
    }
    public function generateRandomString($length = 10) {
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
    	return $randomString;
	}
}
