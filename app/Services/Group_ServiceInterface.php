<?php

namespace App\Services;

interface Group_ServiceInterface
{
  public function groups_menu($user_id);
  public function group_list($user_id);
  public function group_create($request, $user_id);
  public function group_show($id);
  public function group_update($id, $request);
  public function group_delete($id);
  public function owner_list($e_groups_id);
  public function owner_delete($e_groups_id, $user_id);
  public function owner_join_list($e_groups_id, $keyword);
  public function owner_join($e_groups_id, $request);
}