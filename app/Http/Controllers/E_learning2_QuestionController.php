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
    return $this->exercise_service->question_menu($e_groups_id);
  }

  public function question_list($e_groups_id, $no)
  {
    return $this->exercise_service->question_list($e_groups_id, $no);
  }

  public function question_show($id)
  {
    return $this->exercise_service->question_show($id);
  }

  public function question_create(Request $request)
  {
    $this->exercise_service->question_create($request);
    return response($request, 201);
  }

  public function question_update(Request $request, $id)
  {
    $this->exercise_service->question_update($id, $request);
    return response($request, 200);
  }

  public function question_delete($id)
  {
    $this->exercise_service->question_delete($id);
    return response(200);
  }

  public function question_import(UploadCsvFile $request)
  {
    $this->exercise_service->question_import($request);
    return response($request, 201);
  }

  public function question_import2(UploadCsvFile $request)
  {
    $this->exercise_service->question_import2($request);
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