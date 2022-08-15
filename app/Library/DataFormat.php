<?php

namespace App\Library;

use Illuminate\Support\Facades\Hash;
use App\models\E_group;

class DataFormat
{
  public static function user_format($request)
  {
    $item = $request->all();
    $item['password'] = Hash::make($item['password_raw']);
    if ($item['role'] < 6)
      $item['password_raw'] = "";
    return $item;
  }

  public static function group_format($request)
  {
    $item = $request->all();
    $e_group = E_group::where('name', $item['name'])->first();
    if($e_group != null) return response($item, 400);
    return $item;
  }

}