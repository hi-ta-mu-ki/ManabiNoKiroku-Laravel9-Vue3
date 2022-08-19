<?php

namespace App\Services;

interface User_Query_ServiceInterface
{
  public function get_member_join_list($e_member, $keyword);
  public function get_owner_join_list($e_owner, $keyword);
}