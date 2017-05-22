<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	protected $dates = ['deleted_at'];
    protected $table = 'reply';

    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }
}
