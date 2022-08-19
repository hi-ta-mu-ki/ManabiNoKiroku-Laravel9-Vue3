<?php

namespace App\Services;

use App\Services\Answer_Query_ServiceInterface;
use App\Models\E_answer;

class Answer_Query_Service implements Answer_Query_ServiceInterface
{
  public function get_class_to_answer($e_classes_id, $no)
  {
    return E_answer::whereIn('e_classes_id', $e_classes_id)->where('no', $no)->orderBy('s_id', 'asc')->orderBy('user_id', 'asc')->orderBy('q_no', 'asc')->with('user')->get();
  }

  public function get_class_to_answer_graph($e_classes_id, $no, $q_no)
  {
    return E_answer::whereIn('e_classes_id', $e_classes_id)->where('no', $no)->where('q_no', $q_no)->get();
  }

  public function get_user_to_answer($user_id, $e_classes_id)
  {
    return E_answer::where('user_id', $user_id)->where('e_classes_id', $e_classes_id)->orderBy('no', 'asc')->orderBy('s_id', 'desc')->orderBy('q_no', 'asc')->get();
  }
}