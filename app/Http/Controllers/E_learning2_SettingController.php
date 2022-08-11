<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;
use App\Models\E_setting;
use App\Models\E_class;

class E_learning2Controller extends Controller
{

  public function select_title($e_classes_id)
  {
    $selected_titles = E_setting::where('e_classes_id', $e_classes_id)->get('no');
    $setting = array();
    $i = 0;
    foreach($selected_titles as $selected_title){
      $setting[$i] = $selected_title->no;
      $i++;
    }
    return $setting;
  }

  public function question_setting($e_classes_id, Request $request)
  {
    $selected_titles = $request->input();
    $i = 0;
    $e_groups_id = E_class::where('id', $e_classes_id)->select('e_groups_id')->getQuery();
    $section_titles = Exercise::whereIn('e_groups_id', $e_groups_id)->where('q_no', 0)->get();
    foreach($section_titles as $section_title){
      $item = E_setting::where('e_classes_id', $e_classes_id)->where('no', $section_title->no)->first();
      if($item == null){
        if($i < count($selected_titles)){
          if($section_title->no == $selected_titles[$i]){
            $titles = new E_setting;
            $titles->e_classes_id = $e_classes_id;
            $titles->no = $selected_titles[$i];
            $titles->save();
            $i++;
          }
        }
      }else if($section_title->no == $item->no){
        if($i < count($selected_titles)){
          if($section_title->no == $selected_titles[$i]) $i++;
        }else E_setting::where('e_classes_id', $e_classes_id)->where('no', $section_title->no)->delete();
      }
    }
    return response($request, 201);
  }

}