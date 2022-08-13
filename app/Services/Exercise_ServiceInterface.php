<?php

namespace App\Services;

interface Exercise_ServiceInterface
{
  public function menu($e_groups_id);
  public function list($e_groups_id, $no);
  public function create($item);
  public function show($id);
  public function update($id, $item);
  public function delete($id);
  public function import($request);
  public function import2($request);
  public function upload($request);
  public function answer_list($e_groups_id, $no);
  public function answer_graph($e_groups_id, $no, $q_no);
}