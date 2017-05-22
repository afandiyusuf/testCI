<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Storage;
use App\Models\Reply;
use App\Models\Favorite;
use App\Models\Page;
use App\ReturnData; //class object for return data
use App\Models\Comic;
use App\Models\Comment;
use App\Models\Rate;
use Illuminate\Http\Response;
use \Firebase\JWT\JWT;
use \Validator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class ComicController extends Controller
{
    public function get_all()
    {
    	$retData = new ReturnData();
    	$comic = new Comic;
    	$comic_all =  $comic->get_all_data(Input::get('id'));
    	$retData->set('Success',200,$comic_all);
    	return response()->json($retData,$retData->code);
    }
    
    public function favorite(Request $request)
    {
        $validator = Validator::make($request->all(),[
            "comic_id" => "required"
        ]);

        $user_id = Input::get('id');
        $comic_id = Input::get('comic_id');
        $retData = new ReturnData();

        if ($validator->fails())
        {
            $retData->set(__('api.validation_error'),406,array("message"=>$validator->errors()));
        }else{
           
           $comic = new Comic();
           $comic = $comic->where('id',$comic_id)->first();
           
            if(count($comic)==0)
            {
                $retData->set(__('api.data_not_found'),404,[]);
                //check if this is user's comment
            }else{

                //cek if user already favorite comic
                $favorite = new Favorite();
                $favorite = $favorite->where('user_id',Input::get('id'))
                                ->where('comic_id',$comic_id)->first();
                //user belum favorite, buat favorite
                if($favorite == null)
                {
                    $favorite = new Favorite();
                    $favorite->comic_id = $comic_id;
                    $favorite->user_id = Input::get('id');
                    $favorite->save();
                     $retData->set(__('api.success'),201,$favorite);
                }else{
                    $favorite->delete();
                    $favorite = [];
                     $retData->set(__('api.success'),202,$favorite);
                }
               
            }
        }
        
        return response()->json($retData,$retData->code);
    }

    public function get_favorite(Request $request)
    {
        $retData = new ReturnData();
        $user_id = Input::get('id');
        $favorites = new Favorite();
        $favorites = $favorites->where('user_id',$user_id)->get();
        $data = ['comics'=>[]]; 

        for($i=0;$i<count($favorites);$i++)
        {
            $comic = new comic();
            $favorites[$i]['comic'] = $comic->where('id',$favorites[$i]['comic_id'])->first();
            $favorites[$i]['comic']['is_favorite'] = true;
            $data['comics'][] = $favorites[$i];
        }           

            
        $data['thumb_domain'] = url('/').Storage::url('');
        $data['hires_domain'] = url('/').Storage::url('');
        $retData->set(__('api.success'),200,["favorites"=>$data]);
        return response()->json($retData,$retData->code);
    }

    public function post_rating(Request $request)
    {
        $retData = new ReturnData();
        $validator = Validator::make($request->all(),[
            "comic_id" => "required",
            "rate"=>"digits:1|required"
        ]);

        if ($validator->fails())
        {
            $retData->set(__('api.validation_error'),406,array("message"=>$validator->errors()));
        }else{
            $rate = new Rate();
            $rate_data = $rate->where('user_id',Input::get('id'))
                            ->where('comic_id',Input::get('comic_id'))
                            ->get();

            //user belum pernah kasih rating
            if(count($rate_data) == 0)
            {
                //lakukan operasi insert
                $rate = new Rate();
                $rate->comic_id = Input::get('comic_id');
                $rate->user_id = Input::get('id');
                $type = "insert";
            }else{
                //lakukan operasi update
               $rate = new Rate();
               $rate = $rate->where('id',$rate_data[0]['id'])->first();
               $type = "update";
            }
            //save to database
            $rate->rate = Input::get('rate');
            $rate->save();


            $comic_rating = Comic::find(Input::get('comic_id'))->rating()->avg('rate');
            $retData->set
                    (__('api.success'),
                        200,
                        [
                            "type"=>$type,
                            "comic_rating" => floatval($comic_rating),
                            "user_rating"=>floatval(Input::get('rate'))
                        ]
                    );
        }

        return response()->json($retData,$retData->code);
    }

    public function search (Request $request)
    {
        $retData = new ReturnData();
        $comic = new Comic();
        $arrKeyword = explode(" ", Input::get('keyword'));
        $query = "SELECT a.id,a.title,a.cover_url,a.thumb_url,a.comic_categories_id,a.description,a.author_id,a.comic_tags,b.name,c.category FROM comics a LEFT JOIN author b ON a.author_id = b.id LEFT JOIN comic_categories c ON a.comic_categories_id = c.id WHERE";

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
        $allComics = array_map(function ($value) {
            return (array)$value;
        }, $allComics);
        $temp_comic = new Comic();
        for($i=0;$i<count($allComics);$i++)
        {
            $allComics[$i]['author'] = $allComics[$i]['name'];
            $allComics[$i]['is_favorite'] = $temp_comic->isFavorite(Input::get('id'),$allComics[$i]['id']);
        }
        $data = [];
        $data['comics'] = $allComics;
        $data['total'] = count($allComics);
        $data['thumb_domain'] = url('/').Storage::url('cover/');
        $data['hires_domain'] = url('/').Storage::url('cover/');
        $retData->set(__('api.success'),200,$data);
        
        return response()->json($retData,$retData->code);
    }


    public function add_Panel()
    {
        $id_page = Input::get('id_page');
        $panel_data = Input::get('jsondatas');

        $page = Page::find($id_page);
        $page->panel_data = $panel_data;
        $page->total_panel = Input::get('total_panel');
        $page->save();

        return "success";
    }

    public function get_panel(Request $request,Comic $comic_id, Page $page_id)
    {
        $array = [];
        $array['panel_data'] = [];
        $array['panel_data'] = $page_id->panel_data;
        return response()->json($array,200);
    }
}
