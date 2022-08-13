<?php

namespace App\Http\Controllers;

use App\Services\Exercise_ServiceInterface;
use Illuminate\Http\Request;
use App\Http\Requests\UploadCsvFile;
use App\Http\Requests\StorePhoto;

class E_learning2_QuestionController extends Controller
{
  private Exercise_ServiceInterface $exercise_service;

  public function __construct(Exercise_ServiceInterface $exercise_service)
  {
    $this->exercise_service = $exercise_service;
  }

  public function question_menu($e_groups_id)
  {
    return $this->exercise_service->menu($e_groups_id);
  }

  public function question_list($e_groups_id, $no)
  {
    return $this->exercise_service->list($e_groups_id, $no);
  }

  public function question_show($id)
  {
    return $this->exercise_service->show($id);
  }

  public function question_create(Request $request)
  {
    $item = $request->only([
      'e_groups_id', 'no', 'q_no', 'quest',
      'ans1', 'ans2', 'ans3', 'ans4', 'ans',
      'exp1', 'exp2', 'exp3', 'exp4', 'exp0']);
    $this->exercise_service->create($item);
    return response($request, 201);
  }

  public function question_update(Request $request, $id)
  {
    $item = $request->only([
      'e_groups_id', 'no', 'q_no', 'quest',
      'ans1', 'ans2', 'ans3', 'ans4', 'ans',
      'exp1', 'exp2', 'exp3', 'exp4', 'exp0']);
    $this->exercise_service->update($id, $item);
    return response($request, 200);
  }

  public function question_delete($id)
  {
    $this->exercise_service->delete($id);
    return response(200);
  }

  public function question_import(UploadCsvFile $request)
  {
    $this->exercise_service->import($request);
    return response($request, 201);
  }

  public function question_import2(UploadCsvFile $request)
  {
    $this->exercise_service->import2($request);
    return response($request, 201);
  }

  public function question_upload(StorePhoto $request)
  {
    $this->exercise_service->upload($request);
    return response($request, 201);
  }

  public function answer_list($e_groups_id, $no)
  {
    return $this->exercise_service->answer_list($e_groups_id, $no);
  }

  public function answer_graph($e_groups_id, $no, $q_no)
  {
    return $this->exercise_service->answer_graph($e_groups_id, $no, $q_no);
  }

}