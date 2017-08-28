<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Produto extends Model
{
     public function pontovenda()
	{
		return $this
		->belongsToMany('App\PontoVenda')
		->withTimestamps();
	}
}
