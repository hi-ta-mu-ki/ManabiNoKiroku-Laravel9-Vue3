<?php

namespace App\Services;

interface Answer_Query_ServiceInterface
{
  public function get_class_to_answer($e_classes_id, $no);
  public function get_class_to_answer_graph($e_classes_id, $no, $q_no);
  public function get_user_to_answer($user_id, $e_classes_id);
}