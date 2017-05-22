<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Category;
use App\Models\Comic;
use App\Models\Page;
use \Validator;
use \Redirect;
use Illuminate\Support\Facades\Input;
use \Session;
use Illuminate\Support\Facades\Storage;
use App\ReturnData; //class object for return data
use Illuminate\Support\Facades\DB;
use \Imagick;

class AdminPageController extends Controller
{
	public $base_domain_page;

	public function __construct()
    {
    	$this->base_domain_page = url('storage/page');
        $this->middleware('auth');
    }
	
	public function edit_page(Request $request, Comic $comic_id, Page $page_id)
	{
        $new_page = new Page();
        $total_next = $new_page->where('page_num',($page_id->page_num+1))
        ->where('comic_id',$comic_id->id)->count();
        $total_prev = $new_page->where('page_num',($page_id->page_num-1))
        ->where('comic_id',$comic_id->id)->count();
        $next_id = "null";
        $prev_id = "null";
        $total_page = $new_page->where('comic_id',$comic_id->id)->count();

        if($total_next == 1)
        {
            $next_id = $new_page->where('page_num',($page_id->page_num+1))->where('comic_id',$comic_id->id)->first();
            $next_id = $next_id->id;
        }
        if($total_prev == 1)
        {
            $prev_id = $new_page->where('page_num',($page_id->page_num-1))->where('comic_id',$comic_id->id)->first();
            $prev_id = $prev_id->id;
        }
		return view('admin.page.show',[
			'page'=>$page_id,
            'comic'=>$comic_id,
			'domain_page'=>$this->base_domain_page,
            'prev_id'=>$prev_id,
            'next_id'=>$next_id,
            'total_page'=>$total_page,
            'page_num'=>$page_id->page_num
		]);
	}

	public function show_all_page(Request $request, Comic $comic_id)
    {
    	$comic = $comic_id;
    	$allPage = new Page();
    	$allPage = $allPage->where('comic_id',$comic['id'])->get();
    	$comic['pages'] = $allPage;
    	$comic['author'] = Author::where('id',$comic['author_id'])->first();
    	$comic['category'] = Category::where('id',$comic['comic_categories_id'])->first();
    	$authors = Author::get();
    	$categories = Category::get();

    	return view('admin.page.index',[
    		'comics'=>$comic,
    		'page_url'=>$this->base_domain_page,
    		'cover_url'=>url('storage/cover'),
    		'authors'=>$authors,
    		'categories'=>$categories
    		]);
    }

    public function delete_page(Request $request, Comic $comic_id, Page $page_id)
    {
    	$page = $page_id;
    	$comic = $comic_id;

    	$page_num = $page->page_num;
    	try{
    		unlink(storage_path('app/public/page/'.$page->image_name));
    	}catch(\ErrorException $e)
    	{}
    	$page = $page->where('comic_id',$comic->id)
    	->where('page_num','>',$page_num)
    	->update([
    		'page_num'=>DB::raw('page_num - 1')
    	]);
    	$page_id->delete();
    	return redirect('/admin/comic/'.$comic->id.'/page');
    }

}
