<?php

namespace App\Services;

interface Exercise_Query_ServiceInterface
{
  public function get_question_menu($e_groups_id);
  public function get_question_list($e_groups_id, $no);
  public function get_class_to_qestion($e_groups_id);
  public function get_group_to_title($e_groups_id);
  public function get_group_to_menu($e_groups_id, $selected_titles);
  public function get_group_to_question($e_groups_id, $no);
  public function get_answer_to_question($answer);
}