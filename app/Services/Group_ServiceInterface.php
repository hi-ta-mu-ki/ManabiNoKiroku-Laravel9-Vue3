<?php

namespace App\Services;

interface Group_ServiceInterface
{
  public function groups_menu();
  public function group_list();
  public function group_create($item);
  public function group_show($id);
  public function group_update($id, $item);
  public function group_delete($id);
  public function owner_list($e_groups_id);
  public function owner_delete($id);
  public function owner_join_list($e_groups_id, $keyword);
  public function owner_join($e_groups_id, $item);
}