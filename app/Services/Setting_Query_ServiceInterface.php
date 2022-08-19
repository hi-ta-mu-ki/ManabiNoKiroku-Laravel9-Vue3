<?php

namespace App\Services;

interface Setting_Query_ServiceInterface
{
  public function get_select_title($e_classes_id);
  public function get_setting_to_title_one($e_classes_id, $section_title);
  public function get_setting_to_title($e_classes_id);
}