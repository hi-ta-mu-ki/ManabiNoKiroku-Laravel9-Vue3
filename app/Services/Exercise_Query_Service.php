<?php

namespace App\Services;

use App\Services\Exercise_Query_ServiceInterface;
use App\Models\Exercise;

class Exercise_Query_Service implements Exercise_Query_ServiceInterface
{
  public function get_question_menu($e_groups_id)
  {
    return Exercise::where('q_no', 0)->where('e_groups_id', $e_groups_id)->get();
  }

  public function get_question_list($e_groups_id, $no)
  {
    return Exercise::where('e_groups_id', $e_groups_id)->where('no', $no)->get();
  }

  public function get_class_to_qestion($e_groups_id)
  {
    return Exercise::where('q_no', 0)->whereIn('e_groups_id', $e_groups_id)->get();
  }

  public function get_group_to_title($e_groups_id)
  {
    return Exercise::whereIn('e_groups_id', $e_groups_id)->where('q_no', 0)->get();
  }

  public function get_group_to_menu($e_groups_id, $selected_titles)
  {
    return Exercise::where('q_no', 0)->whereIn('e_groups_id', $e_groups_id)->whereIn('no', $selected_titles)->get();
  }

  public function get_group_to_question($e_groups_id, $no)
  {
    return Exercise::whereIn('e_groups_id', $e_groups_id)->where('no', $no)->where('q_no', '>', '0')->get();
  }

  public function get_answer_to_question($answer)
  {
    return Exercise::where('no', $answer->no)->where('q_no', 0)->first();
  }
}