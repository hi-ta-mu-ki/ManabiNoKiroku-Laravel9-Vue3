<?php

namespace App\Services;

use App\Services\User_Query_ServiceInterface;
use App\Models\User;

class User_Query_Service implements User_Query_ServiceInterface
{
  public function get_member_join_list($e_member, $keyword)
  {
    return User::where('role', '!=', User::ADMIN)->whereNotIn('id', $e_member)->where('name', 'like', '%' . $keyword . '%')->select('id', 'name')->get();
  }
  
  public function get_owner_join_list($e_owner, $keyword)
  {
    return User::where('role', '!=', User::ADMIN)->where('role', '!=', User::STUDENT)->whereNotIn('id', $e_owner)->where('name', 'like', '%' . $keyword . '%')->select('id', 'name')->get();
  }
}