<?php

namespace App\Http\Controllers;

use App\Services\Setting_ServiceInterface;
use Illuminate\Http\Request;

class E_learning2_SettingController extends Controller
{
  private Setting_ServiceInterface $settingservice;

  public function __construct(Setting_ServiceInterface $settingservice)
  {
    $this->settingservice = $settingservice;
  }

  public function section_menu2($e_classes_id)
  {
    return $this->settingservice->section_menu2($e_classes_id);
  }

  public function select_title($e_classes_id)
  {
    return $this->settingservice->select_title($e_classes_id);
  }

  public function question_setting($e_classes_id, Request $request)
  {
    $selected_titles = $request->input();
    $this->settingservice->question_setting($e_classes_id, $selected_titles);
    return response($request, 201);
  }

}