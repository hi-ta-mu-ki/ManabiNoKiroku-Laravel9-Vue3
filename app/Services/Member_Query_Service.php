<?php

namespace App\Services;

use App\Services\Member_Query_ServiceInterface;
use App\Models\E_member;
use App\Models\User;

class Member_Query_Service implements Member_Query_ServiceInterface
{
  public function get_user_to_class($user_id)
  {
    return E_member::where('user_id', $user_id)->select('e_classes_id')->getQuery();
  }

  public function get_member_list($e_classes_id)
  {
    return E_member::where('e_classes_id', $e_classes_id)->with('user')->orderBy('user_id', 'asc')->get();
  }

  public function get_member_list_menu($e_classes_id)
  {
    return E_member::select('user_id','name')->join('users', 'users.id','=','e_members.user_id')->where('e_classes_id', $e_classes_id)->where('role', User::STUDENT)->orderBy('user_id', 'asc')->get();
  }

  public function get_class_to_user($e_classes_id)
  {
    return E_member::where('e_classes_id', $e_classes_id)->select('user_id')->getQuery();
  }

  public function get_member_join($e_classes_id, $request)
  {
    return E_member::where('e_classes_id', $e_classes_id)->where('user_id', $request->id)->first();
  }

  public function get_class_to_user_one($e_class, $user_id)
  {
    return E_member::where('e_classes_id', $e_class->id)->where('user_id', $user_id)->first();
  }
}