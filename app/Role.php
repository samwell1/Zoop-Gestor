<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
	//Relacionamento com os níveis de permissão.
    public function users()
	{
		return $this
		->belongsToMany('App\User')
		->withTimestamps();
	}
}
