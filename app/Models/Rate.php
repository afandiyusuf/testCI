<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    public function user()
    {
    	return $this->belongsTo('App\Models\User');
    }

}
