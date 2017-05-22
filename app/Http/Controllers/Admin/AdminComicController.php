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

class AdminComicController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
    	return view('admin.comic.init');
    }

    public function create()
    {

    	$authors = Author::get();
    	$categories = Category::get();
    	return view('admin.comic.create',[
    		'authors'=>$authors,
    		'categories'=>$categories
    		]
    	);
    }
    public function setPublishComic(Request $request,Comic $comic_id,$val)
    {
        $comic_id->update(['published'=>$val]);
        return redirect('admin/comic/'.$comic_id->id.'/page');
    }
    public function search(Request $request,$is_publish=99,$current_page = 0,$total = 20)
    {

    	$allComics = [];

    	if(Input::has('keyword')){
    		$query = "SELECT a.thumb_url,a.id,a.title,a.cover_url,a.comic_categories_id,a.description,a.author_id,a.comic_tags,b.name,c.category FROM comics a LEFT JOIN author b ON a.author_id = b.id LEFT JOIN comic_categories c ON a.comic_categories_id = c.id WHERE";
            
	        $arrKeyword = explode(" ", Input::get('keyword'));
	        $query .= " a.title LIKE '%".Input::get('keyword')."%' ";
	        $query .= "OR a.description LIKE '%".Input::get('keyword')."%' ";
	        $query .= "OR b.name LIKE '%".Input::get('keyword')."%' ";
	        $query .= "OR c.category LIKE '%".Input::get('keyword')."%' ";
            
	 		foreach ($arrKeyword as $keyword) {
	 			$query.= "OR a.title LIKE '%".$keyword."%' ";
	 			$query.= "OR a.description LIKE '%".$keyword."%' ";
	 			$query.= "OR b.name LIKE '%".$keyword."%' ";
	 			$query.= "OR c.category LIKE '%".$keyword."%' ";
	 		}
           
            
	 		$allComics = DB::select($query);
            $total_comic = count($allComics);
            $total_pagination = ceil($total_comic/$total);

            $query .= "LIMIT ".$current_page*$total." ,".$total;
            $allComics = DB::select($query);

	 		$allComics = array_map(function ($value) {
	            return (array)$value;
	        }, $allComics);
	 		$page = new Page();
	        for($i=0;$i<count($allComics);$i++)
	        {
	        	$total_page = $page->where('comic_id',$allComics[$i]['id'])->count();
	            $allComics[$i]['total_page'] = $total_page;
	        }

    	}else{
    		$comics = new Comic;
            
            if($is_publish != 99){
    		  $allComics = $comics->where('published',$is_publish)->skip($current_page*$total)->limit($total)->get();
              $total_comic = Comic::where('published',$is_publish)->count();
            }else{
                $allComics = $comics->skip($current_page*$total)->limit($total)->get();
                $total_comic = Comic::count();
            }
            $total_pagination = ceil($total_comic/$total);

    		$page = new Page;
    		$author = new Author;
    		for($i=0;$i<count($allComics);$i++)
	        {
                $total_page = $page->where('comic_id',$allComics[$i]['id'])->count();
                $allComics[$i]['total_page'] = $total_page;
                if($author->where('id',$allComics[$i]['author_id'])->count()>0){
                    
                    $allComics[$i]['name'] =  $author->where('id',$allComics[$i]['author_id'])->first()->name;
                }else{
                    $allComics[$i]['name'] = "NOT SET";
                }
	        }
     
    	}
         
    	return view('admin.comic.search',[
    		'comics'=>$allComics,
    		'base_url'=>url('storage/cover'),
            'thumb_url'=>url('storage/cover'),
            'page' => $page,
            'current_page'=>$current_page,
            'total_pagination'=>$total_pagination,
            'total_comic'=>$total_comic,
            'is_publish'=>$is_publish,
            'total'=>$total,
            'keyword'=>$request->get('keyword')
    	]);
    }

    public function delete_comic(Request $request, Comic $comic_id)
    {
    	//delete all page with comic id like comic
    	$page = new Page();
    	$page = $page->where('comic_id',$comic_id->id)->get();
    	foreach($page as $p)
    	{
    		try{
    			unlink(storage_path('app/public/page/'.$p->image_name));
    		}catch(\ErrorException $e)
    		{};
    	}
    	$page = new Page();
    	$page = $page->where('comic_id',$comic_id->id);
    	$page->delete();
    	try{
    		unlink($comic_id->cover_url);
    		unlink($comic_id->thumb_url);
    	}catch(\ErrorException $e)
    	{};
    	$comic_id->delete();

    	return $this->search();
    }




    public function add_page(Request $request, Comic $comic_id)
    {
    	$comic = $comic_id;
    	$file = array('image' => Input::file('image'));
    	$rules = array(
        	'image'				=> 'required'
    	);
    	$validator = Validator::make($file, $rules);
    	if ($validator->fails()) {
		    // send back to the page with the input data and errors
		    return redirect('/admin/comic/'.$comic->id.'/page')->withErrors($validator);
		  }else {
	    	if($request->hasFile('image')){
			    if(Input::file('image')->isValid()) {
					$extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
					if($extension == "pdf"){
						//function for counting page
						function count_pages($pdfname) {
						  $pdftext = file_get_contents($pdfname);
						  $num = preg_match_all("/\/Page\W/", $pdftext, $dummy);
						  return $num;
						}
						//save to temp folder
						$storagePath = Storage::disk('public')->put('temp',Input::file('image'));
						//get path of temp file pdf
						$path = storage_path('app/public/temp/'.basename($storagePath));
						//get name without extension
						$realname = substr(basename($storagePath),0,count(basename($storagePath))-5);

						$total_page = count_pages($path);
						$save = storage_path('app/public/page/'.$realname.'.png');
						//execute exec command for start converting
                        $myurl = $path;
                        $img = new Imagick();
                        $img->setResolution( 200, 200 );
                        $img->readImage($path);
                        $img->setImageDepth(24);
                        $compression_type = Imagick::COMPRESSION_NO;
                        
                        
                        $img->setImageFormat( "png" );
                        
                        $num_pages = $img->getNumberImages();
                        $img->setImageBackgroundColor('white');
                        
                        
                        $img->setImageCompression($compression_type);
                        $img->setImageCompressionQuality(100);
                        // Convert PDF pages to images
                        for($i = 0;$i < $num_pages; $i++) {         

                            // Set iterator postion
                            $img->setIteratorIndex($i);

                            // Set image format
                            $img->setImageFormat('png');

                            // Write Images to temp 'upload' folder     
                            $img->writeImage("png24:".storage_path('app/public/page/'.$realname.'-'.$i.'.png'));
                        }

                        $img->destroy();
						//exec("sudo convert -density 150 ".$path." -resize 100% -quality 100 ".$save);

						for($i = 0;$i<$total_page;$i++)
						{
							$page = new Page();
							$page->comic_id = $comic->id;
							$page->page_num = Input::get('num')+$i;
							$page->image_name = $realname."-".$i.".png";
							$page->panel_data = "";
							$page->total_panel = 0;
							$page->save();
						}
						try{
							unlink($path);
						}catch(\ErrorException $e)
						{}
						return redirect('/admin/comic/'.$comic->id.'/page');
					}else if($extension == "png")
					{
						$storagePath = Storage::disk('public')->put('page',Input::file('image'));
						$page = new Page();
						$page->comic_id = $comic->id;
						$page->page_num = Input::get('num');
						$page->image_name = basename($storagePath);
						$page->panel_data = "";
						$page->total_panel = 0;
						$page->save();
						return redirect('/admin/comic/'.$comic->id.'/page');
					}else{
						return redirect('/admin/comic/'.$comic->id.'/page');
					}
			    }else{
			    	return redirect('/admin/comic/'.$comic->id.'/page');
			    }
			}else{
				return redirect('/admin/comic/'.$comic->id.'/page');
			}
		}
    }

    public function prepareEdit(Request $request, Comic $comic_id)
    {

        $comic = $comic_id;
        $allPage = new Page();
        $allPage = $allPage->where('comic_id',$comic['id'])->get();
        $comic['pages'] = $allPage;
        $comic['author'] = Author::where('id',$comic['author_id'])->first();
        $comic['category'] = Category::where('id',$comic['comic_categories_id'])->first();
        $authors = Author::get();
        $categories = Category::get();

        return view('admin.comic.prepare_edit',[
            'comics'=>$comic,
            'cover_url'=>url('storage/cover'),
            'authors'=>$authors,
            'categories'=>$categories
            ]);

    }
    public function edit_comic(Request $request,Comic $comic_id)
    {
    	$rules = array(
        	'title'             => 'required',                        // just a normal required validation
        	'old_author'            => '',     // required and must be unique in the ducks table
        	'old_category'         	=> '',
        	'description'		=> ''
    	);
    	//if user select create new author so new author field must be not null
    	if(Input::get('old_author') == "new")
    		$rules['name'] = 'required|unique:author';
    	if(Input::get('old_category') == "new")
    		$rules['category'] = 'required|unique:comic_categories';

    	$validator = Validator::make(Input::all(), $rules);
    	if ($validator->fails()) {

	        // get the error messages from the validator
	        $messages = $validator->messages();

	        // redirect our user back to the form with the errors from the validator
	      return redirect()->back()->withErrors($validator)->withInput();

    	} else {
    		$category_id = Input::get('old_category');
    		$author_id = Input::get('old_author');
    		$comic = $comic_id;
    		//input new author if admin select new author
    		if(Input::get('old_author') == "new")
    		{
    			$author = new Author();
    			$author->name = Input::get('name');
    			$author->save();
    			$author_id = $author->id;
    		}
    		if(Input::get("old_category") == "new")
    		{
    			$category = new Category();
    			$category->category = Input::get('category');
    			$category->save();
    			$category_id = $category->id;
    		}
    		//file handler
    		// getting all of the post data
		  	$file = array('image' => Input::file('image'));
    		// checking file is valid.
    		if($request->hasFile('image')){
			    if(Input::file('image')->isValid()) {
                    $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
                    $fileName = rand(11111,99999).'.'.$extension; // renameing image
                    $storagePath = Storage::disk('public')->put('cover',Input::file('image'));
                    $url = storage_path('app/public/cover/'.basename($storagePath));
                    $image = new Imagick();
                    //get width & height
                    
                    
                    $image->readImage($url);
                    $image->setImageDepth(24);
                    $width = $image->getImageWidth();
                    $height = $image->getImageHeight();
                    //resize Width
                    $compression_type = Imagick::COMPRESSION_NO;
                    $image->setImageCompression($compression_type);
                    $image->resizeImage(300,(300/$width)*$height,Imagick::FILTER_LANCZOS,1);
                    $image->setImageFormat("png");
                    $image->writeImage("png24:".storage_path('app/public/cover/thumb_'.basename($storagePath)));

                    $comic->cover_url = url('storage/cover')."/".basename($storagePath);
                    $comic->thumb_url = url('storage/cover').'/thumb_'.basename($storagePath);
			  }
			}
			 
			$comic->title = Input::get('title');
			$comic->comic_categories_id = $category_id;
			$comic->description = Input::get('description');
			$comic->author_id = $author_id;
			$comic->custom_prop = 1;

			$comic->update();
			return redirect('/admin/comic/'.$comic->id.'/page');
		}
    }
    public function insert(Request $request)
    {
    	
    	$rules = array(
        	'title'             => 'required',                        // just a normal required validation
    	   'old_author'         => 'required',     // required and must be unique in the ducks table
    	   'old_category'       => 'required',
        	'image'				=> 'required',
        	'description'		=> 'required'
    	);

    	//if user select create new author so new author field must be not null
    	if(Input::get('old_author') == "new")
    		$rules['name'] = 'required|unique:author';
    	if(Input::get('old_category') == "new")
    		$rules['category'] = 'required|unique:comic_categories';

    	$validator = Validator::make(Input::all(), $rules);
    	if ($validator->fails()) {

	        // get the error messages from the validator
	        $messages = $validator->messages();

	        // redirect our user back to the form with the errors from the validator
	      return redirect()->back()->withErrors($validator)->withInput();

    	} else {
    		$category_id = Input::get('old_category');
    		$author_id = Input::get('old_author');
    		//input new author if admin select new author
    		if(Input::get('old_author') == "new")
    		{
    			$author = new Author();
    			$author->name = Input::get('name');
    			$author->save();
    			$author_id = $author->id;
    		}
    		if(Input::get("old_category") == "new")
    		{
    			$category = new Category();
    			$category->category = Input::get('category');
    			$category->save();
    			$category_id = $category->id;
    		}

    		//file handler
    		// getting all of the post data
		  	$file = array('image' => Input::file('image'));
    		// checking file is valid.
    		if($request->hasFile('image')){
			    if(Input::file('image')->isValid()) {
			      $extension = Input::file('image')->getClientOriginalExtension(); // getting image extension
			      $fileName = rand(11111,99999).'.'.$extension; // renameing image
			      $storagePath = Storage::disk('public')->put('cover',Input::file('image'));
			      $url = storage_path('app/public/cover/'.basename($storagePath));
			      
                  $image = new Imagick();
			      //get width & height
			      
                  $image->readimage($url);
                  
                  $image->setType(Imagick::IMGTYPE_TRUECOLOR);
                  $width = $image->getImageWidth();
                  $height = $image->getImageHeight();
                  $image->resizeImage(300,(300/$width)*$height,Imagick::FILTER_LANCZOS,1);
                  $image->setImageFormat("png");
                  $image->writeImage("png24:".storage_path('app/public/cover/thumb_'.basename($storagePath)));


			      $comic = new Comic();
			      $comic->title = Input::get('title');
			      $comic->cover_url = url('storage/cover')."/".basename($storagePath);
			      $comic->thumb_url = url('storage/cover').'/thumb_'.basename($storagePath);
			      $comic->comic_categories_id = $category_id;
			      $comic->description = Input::get('description');
			      $comic->author_id = $author_id;
			      $comic->custom_prop = 1;

			      $comic->save();
			       return redirect('/admin/comic/'.$comic->id.'/page');
			    }
			}else{
				return redirect('/admin/comic/create');
			}
    	}
    }
}
