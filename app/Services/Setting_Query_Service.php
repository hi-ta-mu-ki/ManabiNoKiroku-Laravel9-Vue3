<?php

namespace App\Services;

use App\Services\Setting_Query_ServiceInterface;
use App\Models\E_setting;

class Setting_Query_Service implements Setting_Query_ServiceInterface
{
  public function get_select_title($e_classes_id)
  {
    return E_setting::where('e_classes_id', $e_classes_id)->get('no');
  }

  public function get_setting_to_title_one($e_classes_id, $section_title)
  {
    return E_setting::where('e_classes_id', $e_classes_id)->where('no', $section_title->no)->first();
  }

  public function get_setting_to_title($e_classes_id)
  {
    return E_setting::where('e_classes_id', $e_classes_id)->select('no')->getQuery();
  }
}