<?php

namespace App\Services;

interface Group_Query_ServiceInterface
{
  public function get_group_to_menu($items);
  public function get_group_name($request);
}