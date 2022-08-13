<?php

namespace App\Http\Controllers;

use App\Services\Class_ServiceInterface;
use Illuminate\Http\Request;

class E_learning2_ClassController extends Controller
{
  private Class_ServiceInterface $class_service;

  public function __construct(Class_ServiceInterface $class_service)
  {
    $this->class_service = $class_service;
  }

  public function classes_menu()
  {
    return $this->class_service->classes_menu();
  }

  public function class_list($e_groups_id)
  {
    return $this->class_service->class_list($e_groups_id);
  }

  public function class_show($id)
  {
    return $this->class_service->class_show($id);
  }

  public function class_create(Request $request)
  {
    $item = $request->only(['name', 'pass_code']);
    return $this->class_service->class_create($item);
  }

  public function class_update(Request $request, $id)
  {
    $item = $request->only(['name', 'pass_code']);
    return $this->class_service->class_update($id, $item);
  }

  public function class_delete($id)
  {
    return $this->class_service->class_delete($id);
  }

  public function member_list($e_classes_id)
  {
    return $this->class_service->member_list($e_classes_id);
  }

  public function member_delete($id)
  {
    return $this->class_service->member_delete($id);
  }

  public function member_list2($e_classes_id)
  {
    return $this->class_service->member_list2($e_classes_id);
  }

  public function member_join_list($e_classes_id, $keyword)
  {
    return $this->class_service->member_join_list($e_classes_id, $keyword);
  }

  public function member_join1($e_classes_id, Request $request)
  {
    $user_id = $request->id;
    return $this->class_service->member_join($e_classes_id, $user_id);
  }

  public function member_join2(Request $request)
  {
    $pass_code = $request->pass_code;
    return $this->class_service->member_join2($pass_code);
  }

}