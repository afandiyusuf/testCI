<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Favorite;
use Storage;

class Comic extends Model
{
    protected $fillable = ['published'];
    public function get_all_data($user_id)
    {
        $retData = ['comics'=>[]];
        $retData['thumb_domain'] = url('/').Storage::url('cover/');
        $retData['hires_domain'] = url('/').Storage::url('cover/');
        $category = $this->get_all_category();

        for($i=0;$i<count($category);$i++)
        {
            $comic = $this->get_by_category($category[$i]->id,20);
            for($j=0;$j<count($comic);$j++)
            {
                $comic[$j]['is_favorite'] = $this->isFavorite($user_id,$comic[$j]['id']);
                $comic[$j]['rating'] = floatval($this->find($comic[$j]['id'])->rating()->avg('rate'));
                if($comic[$j]['author_id'] != 0)
                    $comic[$j]['author'] = Author::find($comic[$j]['author_id'])->first()->name;
                else
                     $comic[$j]['author'] = "";
            }
            if(count($comic)>0){
                $comicData = [
                    'total' => count($comic),
                    'category' => $category[$i]->category,
                    'data' => $comic,   
                ];
                $retData['comics'][] = $comicData;
            }
        }
        return $retData;
    }

    public function get_by_category($cat_id,$limit=99)
    {
        return $this->take($limit)
                ->where('comic_categories_id','=',$cat_id)
                ->where('published','=',1)
                ->get();
    }

    public function get_all_category()
    {
        $category = new Category();
        return $category->orderBy('id','desc')->get();
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function isFavorite($id_user,$id_comic)
    {

        $fav = new Favorite();
        $fav = $fav->where('user_id',$id_user)
                ->where('comic_id',$id_comic)
                ->get();
        if(count($fav) == 1)
        {
            return true;
        }else{
            return false;
        }
    }

    public function rating()
    {
        return $this->hasMany('App\Models\Rate');
    }

    public function author()
    {
        return $this->hasOne('App\Models\Author');
    }
}
