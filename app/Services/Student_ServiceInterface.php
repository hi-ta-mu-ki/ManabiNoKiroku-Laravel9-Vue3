<?php

namespace App\Services;

interface Student_ServiceInterface
{
  public function st_menu($e_classes_id);
  public function question_randomize($e_classes_id, $no);
  public function answer_create($item);
  public function answer_list2($id, $e_classes_id);
  public function answer_delete($id);

}