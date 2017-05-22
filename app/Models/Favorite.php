<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
	protected $dates = ['deleted_at'];
    protected $table = 'favorite';

    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }
    
    public function comic()
    {
    	return $this->belongsTo('App\Models\Comic');
    }
}
