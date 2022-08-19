<?php

namespace App\Services;

interface Exercise_ServiceInterface
{
  public function question_menu($e_groups_id);
  public function question_list($e_groups_id, $no);
  public function question_create($request);
  public function question_show($id);
  public function question_update($id, $request);
  public function question_delete($id);
  public function question_import($request);
  public function question_import2($request);
  public function upload($request);
  public function answer_list($e_groups_id, $no);
  public function answer_graph($e_groups_id, $no, $q_no);
}