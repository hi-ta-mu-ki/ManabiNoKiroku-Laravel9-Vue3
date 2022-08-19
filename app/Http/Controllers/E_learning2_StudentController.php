<?php

namespace App\Http\Controllers;

use App\Services\Student_ServiceInterface;
use Illuminate\Http\Request;

class E_learning2_StudentController extends Controller
{
  private Student_ServiceInterface $student_service;

  public function __construct(Student_ServiceInterface $student_service)
  {
    $this->student_service = $student_service;
  }

  public function st_menu($e_classes_id)
  {
    return $this->student_service->st_menu($e_classes_id);
  }

  public function question_randomize($e_classes_id, $no)
  {
    return $this->student_service->question_randomize($e_classes_id, $no);
  }

  public function answer_record(Request $request)
  {
    return $this->student_service->answer_create($request);
  }

  public function answer_my_list($user_id, $e_classes_id)
  {
    return $this->student_service->answer_my_list($user_id, $e_classes_id);
  }

  public function answer_delete($user_id, $e_classes_id)
  {
    $this->student_service->answer_delete($user_id, $e_classes_id);
  }

}