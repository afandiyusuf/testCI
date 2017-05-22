<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;
use File;
use Response;
use App\Models\Page;
use App\ReturnData; //class object for return data
use Illuminate\Support\Facades\Input;
use Illuminate\Routing\UrlGenerator;

class PageController extends Controller
{
    public function index()
    {

    }

    public function show(Request $request)
    {
    	$val = $request->header('access_token');
    	$val = $request->header('user_id');
    	$val = $request->header('email');
    	return $val;

    	$path = storage_path('app/public/1.jpg');
    	$file = File::get($path);
    	$type = File::mimeType($path);
        $response = Response::make($file, 200);
    	$response->header("Content-Type", $type);
    	return $response;
    }

    public function get_page(Request $request)
    {
    	$retData = new ReturnData();
    	$page = new Page;
    	$data = [];

    	$page_all =  $page->where('comic_id',Input::get('comic_id'))->get();

    	//make url encode filename
    	foreach( $page_all as $page)
    	{
    		$page['image_name'] = $page['image_name'];
    	}
    	$data['page'] = $page_all;
    	$data['base_url'] = url('/').Storage::url('page/');
    	$retData->set('Success',200,$data);
    	return response()->json($retData,$retData->code);
    }
}
