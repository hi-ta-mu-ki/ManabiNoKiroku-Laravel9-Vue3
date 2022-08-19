<?php

namespace App\Services;

interface Member_Query_ServiceInterface
{
  public function get_user_to_class($user_id);
  public function get_member_list($e_classes_id);
  public function get_member_list_menu($e_classes_id);
  public function get_class_to_user($e_classes_id);
  public function get_member_join($e_classes_id, $request);
  public function get_class_to_user_one($e_class, $user_id);
}