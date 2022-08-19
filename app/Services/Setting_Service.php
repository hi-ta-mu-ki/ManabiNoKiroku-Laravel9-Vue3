<?php

namespace App\Services;

use App\Repositories\E_Setting_RepositoryInterface;
use App\Services\Class_Query_ServiceInterface;
use App\Services\Exercise_Query_ServiceInterface;
use App\Services\Setting_Query_ServiceInterface;

class Setting_Service implements Setting_ServiceInterface
{
  private $settingrepository;
  private $class_query_service;
  private $exercise_query_service;
  private $setting_query_service;

  public function __construct(
    E_Setting_RepositoryInterface $settingrepository,
    Class_Query_ServiceInterface $class_query_service,
    Exercise_Query_ServiceInterface $exercise_query_service,
    Setting_Query_ServiceInterface $setting_query_service
  ) {
    $this->settingrepository = $settingrepository;
    $this->class_query_service = $class_query_service;
    $this->exercise_query_service = $exercise_query_service;
    $this->setting_query_service = $setting_query_service;
  }

  public function section_menu($e_classes_id)
  {
    $e_groups_id = $this->class_query_service->get_class_to_group($e_classes_id);
    return $this->exercise_query_service->get_class_to_qestion($e_groups_id);
  }

  public function select_title($e_classes_id)
  {
    $selected_titles = $this->setting_query_service->get_select_title($e_classes_id);
    $setting = array();
    $i = 0;
    foreach($selected_titles as $selected_title){
      $setting[$i] = $selected_title->no;
      $i++;
    }
    return $setting;
  }

  public function question_setting($e_classes_id, $request)
  {
    $selected_titles = $request->input();
    $i = 0;
    $e_groups_id = $this->class_query_service->get_class_to_group($e_classes_id);
    $section_titles = $this->exercise_query_service->get_group_to_title($e_groups_id);
    foreach($section_titles as $section_title){
      $item = $this->setting_query_service->get_setting_to_title_one($e_classes_id, $section_title);
      if($item == null){
        if($i < count($selected_titles)){
          if($section_title->no == $selected_titles[$i]){
            $titles['e_classes_id'] = $e_classes_id;
            $titles['no'] = $selected_titles[$i];
            $this->settingrepository->create($titles);
            $i++;
          }
        }
      }else if($section_title->no == $item->no){
        if($i < count($selected_titles)){
          if($section_title->no == $selected_titles[$i]) $i++;
        }else $this->settingrepository->delete($e_classes_id, $section_title->no);
      }
    }
    return response($request, 201);
  }
}
