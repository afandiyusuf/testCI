<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Comic;
use App\Models\Author;
use \Validator;

class AdminCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	$category = new Category();
    	$category = $category->get();
    	return view('admin.category.index',['category'=>$category]);
    }
    public function create(Request $request)
    {
    	$rules = array(
        	'category'				=> 'required'
    	);
    	$validator = Validator::make($request->all(), $rules);
    	if ($validator->fails()) {
		    // send back to the page with the input data and errors
		    return redirect('/admin/category')->withErrors($validator);
		 }else {
		 	$category = new Category();
		 	$category->category = $request->category;
		 	$category->save();
		 	return redirect('/admin/category');
		 }
    }

    public function delete(Request $request, Category $category)
    {
    	$comic = new Comic();
    	$comic = $comic->where('comic_categories_id',$category->id);
    	$comic->update(['comic_categories_id'=>0]);

    	$category->delete();

    	return redirect('/admin/category');
    }

   

    public function edit(Request $request, Category $category)
    {

    	return view('admin.category.edit',[
    		'category'=>$category
    	]);
    }

    public function update(Request $request, Category $category)
    {
    	$rules = array(
        	'category'				=> 'required'
    	);
    	$validator = Validator::make($request->all(), $rules);
		if ($validator->fails()) {
			// send back to the page with the input data and errors
			return redirect('/admin/category/'.$category->id.'/edit')->withErrors($validator);
		}else {
			$category->category = $request->category;
			$category->save();
			return redirect('/admin/category');
		}
    }
}
