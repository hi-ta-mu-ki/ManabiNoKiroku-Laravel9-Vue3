<?php

namespace App\Services;

interface Class_Query_ServiceInterface
{
  public function get_class_to_menu($items);
  public function get_class_list($e_groups_id);
  public function get_class_name($request);
  public function get_pass_code($request);
  public function get_class_pass_code_me_not($id, $request);
  public function get_class_name_me_not($id, $request);
  public function get_group_to_class($e_groups_id);
  public function get_class_to_groups($items);
  public function get_class_to_group($e_classes_id);
}