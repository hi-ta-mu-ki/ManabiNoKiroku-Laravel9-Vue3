<?php

namespace App\Services;

interface Setting_ServiceInterface
{
  public function section_menu2($e_classes_id);
  public function select_title($e_classes_id);
  public function question_setting($e_classes_id, $request);
}