<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use \Validator;
use \Session;
use \Redirect;

class FileManagerController extends Controller
{
    public function test()
    {
    	
    	return view('pages.upload');
    }

    public function upload()
    {
		    	 // getting all of the post data
		  $file = array('image' => Input::file('image'));
		  // setting up rules
		  $rules = array('image' => 'required',); //mimes:jpeg,bmp,png and for max size max:10000
		  // doing the validation, passing post data, rules and the messages
		  $validator = Validator::make($file, $rules);
		  if ($validator->fails()) {
		    // send back to the page with the input data and errors
		    return Redirect::to('admin/file/test')->withInput()->withErrors($validator);
		  }
		  else {
		  	
		    // checking file is valid.
		    if (Input::file('image')->isValid()) {
		      $destinationPath = 'uploads'; // upload path
		      $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
		      $fileName = rand(11111,99999).'.'.$extension; // renameing image
		      Storage::disk('public')->put('avatar/1',Input::file('image'));
		      // sending back with message
		      Session::flash('success', 'Upload successfully'); 
		      return Redirect::to('admin/file/test');
		    }
		    else {
		      // sending back with error message.
		      Session::flash('error', 'uploaded file is not valid');
		      return Redirect::to('admin/file/test');
		    }
		  }
    }
}
