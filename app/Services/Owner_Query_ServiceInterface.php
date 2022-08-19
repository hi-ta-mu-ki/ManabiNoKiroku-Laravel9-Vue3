<?php

namespace App\Services;

interface Owner_Query_ServiceInterface
{
  public function get_user_to_owner($user_id);
  public function get_owner_list($e_groups_id);
  public function get_e_groups_to_user($e_groups_id);
  public function get_owner_join($e_groups_id, $request);
}