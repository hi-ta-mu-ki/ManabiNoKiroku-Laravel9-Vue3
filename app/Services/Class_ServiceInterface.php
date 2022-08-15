<?php

namespace App\Services;

interface Class_ServiceInterface
{
  public function classes_menu($user_id);
  public function class_list($e_groups_id);
  public function class_create($request, $user_id);
  public function class_show($id);
  public function class_update($id, $request);
  public function class_delete($id);
  public function member_list($e_classes_id);
  public function member_delete($e_classes_id, $user_id);
  public function member_list_menu($e_classes_id);
  public function member_join_list($e_classes_id, $keyword);
  public function member_join($e_classes_id, $request);
  public function member_join_self($request, $user_id);
}