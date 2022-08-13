<?php

namespace App\Repositories;

use App\Models\E_Setting;

class E_Setting_Repository implements E_Setting_RepositoryInterface
{
  public function create(array $item)
  {
    return E_setting::create($item);
  }

  public function delete($e_classes_id, $section_title_no)
  {
    return E_setting::where('e_classes_id', $e_classes_id)->where('no', $section_title_no)->delete();
  }
}
