<?php

namespace App\Services;

use App\Services\Class_Query_ServiceInterface;
use App\Models\E_class;

class Class_Query_Service implements Class_Query_ServiceInterface
{
  public function get_class_to_menu($items)
  {
    return E_class::whereIn('id', $items)->get();
  }

  public function get_class_list($e_groups_id)
  {
    return E_class::where('e_groups_id', $e_groups_id)->get();
  }

  public function get_class_name($request)
  {
    return E_class::where('name', $request->name)->first();
  }

  public function get_pass_code($request)
  {
    return E_class::where('pass_code', $request->pass_code)->first();
  }

  public function get_class_name_me_not($id, $request)
  {
    return E_class::where('id', '<>', $id)->where('name', $request->name)->first();
  }

  public function get_class_pass_code_me_not($id, $request)
  {
    return E_class::where('id', '<>', $id)->where('pass_code', $request->pass_code)->first();
  }

  public function get_group_to_class($e_groups_id)
  {
    return E_class::where('e_groups_id', $e_groups_id)->select('id')->getQuery();
  }

  public function get_class_to_groups($items)
  {
    return E_class::whereIn('id', $items)->select('e_groups_id')->getQuery();
  }

  public function get_class_to_group($e_classes_id)
  {
    return E_class::where('id', $e_classes_id)->select('e_groups_id')->getQuery();
  }
}