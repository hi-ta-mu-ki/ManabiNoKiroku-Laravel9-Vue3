<?php

namespace App\Services;

interface Class_ServiceInterface
{
  public function classes_menu();
  public function class_list($e_groups_id);
  public function class_create($item);
  public function class_show($id);
  public function class_update($id, $item);
  public function class_delete($id);
  public function member_list($e_classes_id);
  public function member_delete($id);
  public function member_list2($e_classes_id);
  public function member_join_list($e_classes_id, $keyword);
  public function member_join($e_classes_id, $item);
  public function member_join2($pass_code);
}