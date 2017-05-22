<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Comic;
use \Validator;

class AdminAuthorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	$author = new Author();
    	$author = $author->get();
    	return view('admin.author.index',['author'=>$author]);
    }

    public function delete(Request $request, Author $author)
    {
    	$comic = new Comic();
    	$comic = $comic->where('author_id',$author->id);
    	$comic->update(['author_id'=>0]);

    	$author->delete();

    	return redirect('/admin/author');
    }

    public function create(Request $request)
    {
    	$rules = array(
        	'name'				=> 'required'
    	);
    	$validator = Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
		    // send back to the page with the input data and errors
		    return redirect('/admin/author')->withErrors($validator);
		 }else {
		 	$author = new Author();
		 	$author->name = $request->name;
		 	$author->save();
		 	return redirect('/admin/author');
		 }
    }

    public function edit(Request $request, Author $author)
    {

    	return view('admin.author.edit',[
    		'author'=>$author
    	]);
    }

    public function update(Request $request, Author $author)
    {
    	$rules = array(
        	'name'				=> 'required'
    	);
    	$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			// send back to the page with the input data and errors
			return redirect('/admin/author/'.$author->id.'/edit')->withErrors($validator);
		}else {
			$author->name = $request->name;
			$author->save();
			return redirect('/admin/author');
		}
    }
}
