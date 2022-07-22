<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ans_pattern extends Model
{
	protected $guarded = ['id'];

	public function scopeId($query, $id){
		return $query->where('id', $id);
	}

}
