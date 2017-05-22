<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
	protected $dates = ['deleted_at'];
	protected $fillable = ['is_deleted','comment'];
    protected $table = 'comments';

    public function reply_from($id)
    {
        if($id != 0)
    	   return $this->where('id',$id)->first();
        else
            return null;
    }

    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }

    public function comic()
    {
        return $this->belongsTo('App\Models\Comic');
    }

}
