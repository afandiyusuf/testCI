<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Reply;
use App\Models\Favorite;
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

class CommentController extends Controller
{
    public function get_comment(Request $request,$comic_id)
    {
    	$retData = new ReturnData();
    	$comments = new Comment();
    	$comments = $comments->where('comic_id',$comic_id)
                            ->where('parent_id',0)->get();

    	for ($i= 0;$i<count($comments);$i++)
    	{
    		$comment = new Comment();
    		$comments[$i]['user'] = $comment->find($comments[$i]['id'])->user->name;

			$comments[$i]['total_reply'] = $comment->where('parent_id',$comments[$i]['id'])->count();;
    		
            //alter comment if comment deleted
    		if($comments[$i]['is_deleted'] == 1){
    			$comments[$i]['comment'] = __('api.comment_deleted');
    		}
    			$reply = new Comment();
    			$user_reply = $reply->where('comic_id',$comic_id)
                                ->where('parent_id',$comments[$i]['id'])->get();
            	
            //alter reply if deleted
                for($j=0;$j<count($user_reply);$j++)
                {
                	$user_reply[$j]['comment_id'] = $user_reply[$j]['parent_id'];
                    $name = new User();
                    $name = $name->where('id',$user_reply[$j]['user_id'])->first()->name;
                	$user_reply[$j]['user'] = $name;
                    if($user_reply[$j]['is_deleted'] == 1)
                        $user_reply[$j]['comment'] = __('api.comment_deleted');
                }
    		$comments[$i]['reply'] = $user_reply;
    	}
    	$retData->set('Success',200,$comments);
    	return response()->json($retData,$retData->code);
    }

    public function post_comment(Request $request)
    {
    	$retData = new ReturnData();
    	$validator = Validator::make($request->all(),[
    		"text" => "required|min:10",
    		"id" => "required",
            "comic_id"=>"required"
    	]);

        if ($validator->fails())
        {
            $retData->set(__('api.validation_error'),406,array("message"=>$validator->errors()));
        }else{
            $comic = new Comic();
            $total = $comic->where('id',Input::get('comic_id'))->count();
            
            if($total == 0){
                $retData->set(__('api.data_not_found'),404,array("message"=>$validator->errors()));
               
            }else {
            	$comment = new Comment;
            	if(Input::has('comment_id') && Input::get('comment_id') != 0)
	            {
                     //jika client kirim comment_id dan comment id bukan 0, berarti user sedang reply
	                $comment->parent_id = Input::get('comment_id');
	            }else{
	                //kalau gak ada berarti user sedang comment  
	                $comment->parent_id = 0;
	            }
	            $comment->comic_id = Input::get('comic_id');
                $comment->user_id = Input::get('id');
                $comment->parent_id = Input::get('comment_id');
                $comment->comment = Input::get('text');
                $comment->save();
                $retData->set(__('api.success'),200,$comment);
        	}
        }  
    	return response()->json($retData,$retData->code);
    }

    public function update_comment(Request $request)
    {
    	$validator = Validator::make($request->all(),[
    		"comment" => "required|min:10",
            "comment_id" => "required"
    	]);

    	$user_id = Input::get('id');
    	$comment_update = Input::get('comment');
    	$retData = new ReturnData();

    	if ($validator->fails())
		{
    	 	$retData->set(__('api.validation_error'),406,array("message"=>$validator->errors()));
    	}else{
            $comment = Comment::where('id',Input::get('comment_id'))->first();

            if(count($comment)==0)
            {
                $retData->set(__('api.data_not_found'),404,[]);
                //check if this is user's comment
            }else if($comment->user_id != Input::get('id'))
            {
                $retData->set(__('api.forbidden'),404,[]);
            }else{
                $comment->comment = Input::get('comment');
                $comment->save();
                $retData->set(__('api.success'),200,$comment);
            }
        }
    	
    	return response()->json($retData,$retData->code);
    }

    public function delete_comment(Request $request)
    {
    	$validator = Validator::make($request->all(),[
            "comment_id" => "required"
        ]);
        
        $retData = new ReturnData();
        if ($validator->fails())
        {
            $retData->set(__('api.validation_error'),406,array("message"=>$validator->errors()));
        }else{
            $comment_update = Input::get('comment');
            $user_id = Input::get('id');
			$comment = Comment::where('id',Input::get('comment_id'))->first();

            if(count($comment)==0)
            {
                $retData->set(__('api.data_not_found'),404,[]);
                //check if this is user's comment
            }else if($comment->user_id != Input::get('id'))
            {
                $retData->set(__('api.forbidden'),404,[]);
            }else{
                $comment->is_deleted = true;
                $comment->deleted_at = Carbon::now();
                $comment->save();
                $comment->comment = __('api.comment_deleted');
                $retData->set(__('api.success'),200,$comment);
            }
        }
        return response()->json($retData,$retData->code);
    }
}
