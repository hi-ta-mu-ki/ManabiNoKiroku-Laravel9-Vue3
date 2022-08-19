<?php

namespace App\Services;

use App\Services\Group_Query_ServiceInterface;
use App\Models\E_group;

class Group_Query_Service implements Group_Query_ServiceInterface
{
  public function get_group_to_menu($items)
  {
    return E_group::whereIn('id', $items)->get();
  }

  public function get_group_name($request)
  {
    return E_group::where('name', $request->name)->first();
  }
}