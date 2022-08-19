<?php

namespace App\Services;

use App\Services\Owner_Query_ServiceInterface;
use App\Models\E_owner;

class Owner_Query_Service implements Owner_Query_ServiceInterface
{
  public function get_user_to_owner($user_id)
  {
    return E_owner::where('user_id', $user_id)->select('e_groups_id')->getQuery();
  }

  public function get_owner_list($e_groups_id)
  {
    return E_owner::where('e_groups_id', $e_groups_id)->with('user')->orderBy('user_id', 'asc')->get();
  }

  public function get_e_groups_to_user($e_groups_id)
  {
    return E_owner::where('e_groups_id', $e_groups_id)->select('user_id')->getQuery();
  }

  public function get_owner_join($e_groups_id, $request)
  {
    return E_owner::where('e_groups_id', $e_groups_id)->where('user_id', $request->id)->first();
  }

}