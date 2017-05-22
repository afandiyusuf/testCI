<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Comic;
use App\Models\Comment;
use Illuminate\Support\Facades\Input;
use \DB;

class AdminCommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request,$page=1,$total=10)
    {
        
        //DB::enableQueryLog();
        if($request->has('srt_name'))
        {
            $srt_name = $request->get('srt_name');
        }else{
            $srt_name = "null";
        }

        if($request->has('srt_comment'))
        {
            $srt_comment = $request->get('srt_comment');
        }else{
            $srt_comment = "null";
        }

        if($request->has('srt_judul'))
        {
            $srt_judul = $request->get('srt_judul');
        }else{
            $srt_judul = "null";
        }

         if($request->has('srt_tanggal'))
        {
            $srt_tanggal = $request->get('srt_tanggal');
        }else{
            $srt_tanggal = "null";
        }
        
        $filter = $request->get('filter');
        $current_page = $page;
        $allData = DB::table('comments')
        ->select('comments.id','users.name','comments.comment','users.name as username','comments.created_at','comics.title as comic_title','parent.id as id_parent','parent_user.username as parent_username','parent.user_id as parent_user_id','comments.is_deleted','comics.id as comic_id')
        ->join('users', 'comments.user_id', '=', 'users.id', 'left')
        ->join('comics','comments.comic_id','=','comics.id','left')
        ->join('comments as parent','parent.parent_id','=','comments.id','left')
        ->join('users as parent_user','parent.user_id', '=','parent_user.id','left');
        if($request->has('srt_name'))
        {
            if($request->get('srt_name') != "null"){
                $allData->orderBy('users.name',$request->get('srt_name'));
            }
            else{
                $allData->orderBy('users.name','asc');
            }
        }
        if($request->has('srt_judul'))
        {
            if($request->get('srt_judul') != "null"){
                $allData->orderBy('comic_title',$request->get('srt_judul'));
            }
        }

        if($request->has('srt_comment'))
        {
            if($request->get('srt_comment') != "null"){
                $allData->orderBy('comments.comment',$request->get('srt_comment'));
            }
        }

        if($request->has('srt_tanggal'))
        {
            if($request->get('srt_tanggal') != "null"){
                $allData->orderBy('comments.created_at',$request->get('srt_tanggal'));
            }
        }

        
        if ($request->has('single_id')) {
            $allData->where('comments.id',$request->get('single_id'));
            $single_id = $request->get('singel_id');
        }else{
            $single_id = "null";
        }

        if($request->has('filter')){
            if($request->get('filter') == "published"){
                $allData->where('comments.is_deleted',0);
                $total_page = ceil(Comment::where('is_deleted',0)->count()/$total);
            }else if($request->get('filter') == "deleted")
            {
                 $allData->where('comments.is_deleted',1);
                 $total_page = ceil(Comment::withTrashed()->where('is_deleted',1)->count()/$total);
            }else{
                $total_page = ceil(Comment::withTrashed()->count()/$total);
            }
        }else{
                $total_page = ceil(Comment::withTrashed()->count()/$total);
        }
        $allData->limit($total)
        ->offset((($page-1) * $total));
        

        $allData = $allData->get();
        $total_deleted = new Comment();
        $total_deleted = Comment::withTrashed()->where('is_deleted',1)->count();
        $total_published = Comment::count();
        $total_all = Comment::withTrashed()->count();

        

        //return $allData;
        // id,name,comment,username,created_at,comic_title,id_parent,parent_username,parent_user_id

       //  $allData = Comment::orderBy('created_at','desc')->get();
       //  for($i=0;$i<count($allData);$i++)
       //  {
       //      $allData[$i]['comic_title'] = $allData[$i]->comic['title'];
       //      $allData[$i]['username']= $allData[$i]->user['name'];
       //      $allData[$i]['reply_from'] = $allData[$i]->reply_from($allData[$i]['parent_id']);
       //  }
       // return $allData;
    	return view('admin.comment.index',['data'=>$allData,
            'total_deleted'=>$total_deleted,
            'total_published'=>$total_published,
            'total_all'=>$total_all,
            'filter'=>$filter,
            'page'=>$page,
            'total_page'=>$total_page,
            'current_page'=>$current_page,
            'total_show'=>$total,
            'srt_name'=>$srt_name,
            'srt_comment'=>$srt_comment,
            'srt_judul'=>$srt_judul,
            'srt_tanggal'=>$srt_tanggal,
            'single_id'=>$single_id]);
    }

    public function delete_comment(Comment $comment)
    {   
       
        $comment->update(['is_deleted'=>1]);
        $comment->delete();
        return redirect()->back();
    }

    public function restore_comment($comment)
    {   
        $comment = Comment::withTrashed()->find($comment);

        $comment->update(['is_deleted'=>0]);
        $comment->restore();
        return redirect()->back();
    }

    public function prepare_edit(Comment $comment)
    {
        $comment->name = $comment->user->name;
        return view('admin.comment.prep_edit',$comment);
    }

    public function edit(Request $request,Comment $comment)
    {   
        $comment->update(['comment'=>$request->get('comment')]);
        return redirect('admin/comment');
    }
}
