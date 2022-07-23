<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class E_answer extends Model
{
  protected $guarded = ['id'];

  public function user()
  {
    return $this->belongsTo('App\Models\User');
  }
}
