<?php

namespace App\Services;

use App\Repositories\E_Class_RepositoryInterface;
use App\Repositories\E_Member_RepositoryInterface;
use App\Services\Class_Query_ServiceInterface;
use App\Services\Member_Query_ServiceInterface;
use App\Services\User_Query_ServiceInterface;
use Carbon\Carbon;

class Class_Service implements Class_ServiceInterface
{
  private $class_repository;
  private $class_query_service;
  private $member_query_service;
  private $user_query_service;

  public function __construct(
    E_Class_RepositoryInterface $class_repository,
    E_Member_RepositoryInterface $member_repository,
    Class_Query_ServiceInterface $class_query_service,
    Member_Query_ServiceInterface $member_query_service,
    User_Query_ServiceInterface $user_query_service
  ) {
    $this->class_repository = $class_repository;
    $this->member_repository = $member_repository;
    $this->class_query_service = $class_query_service;
    $this->member_query_service = $member_query_service;
    $this->user_query_service = $user_query_service;
  }

  public function classes_menu($user_id)
  {
    $items = $this->member_query_service->get_user_to_class($user_id);
    return $this->class_query_service->get_class_to_menu($items);
  }

  public function class_list($e_groups_id)
  {
    return $this->class_query_service->get_class_list($e_groups_id);
  }

  public function class_show($id)
  {
    return $this->class_repository->show($id);
  }

  public function class_create($request, $user_id)
  {
    $e_class_name = $this->class_query_service->get_class_name($request);
    if ($request->pass_code != null) $e_class_pass_code = $this->class_query_service->get_pass_code($request);
    else $e_class_pass_code = null;
    if($e_class_name != null || $e_class_pass_code != null) return response($request, 400);
    $this->class_repository->create($request->all());
    unset($item);
    $e_class_name = $this->class_query_service->get_class_name($request);
    $item['user_id'] = $user_id;
    $item['e_classes_id'] = $e_class_name->id;
    $this->member_repository->create($item);
    return response($item, 201);
  }

  public function class_update($id, $request)
  {
    $e_class_name = $this->class_query_service->get_class_name_me_not($id, $request);
    if ($request->pass_code != null) $e_class_pass_code = $this->class_query_service->get_class_pass_code_me_not($id, $request);
    else $e_class_pass_code = null;
    if($e_class_name != null || $e_class_pass_code != null) return response($request, 400);
    $item = $request->all();
    $item['updated_at'] = Carbon::now();
    $this->class_repository->update($id, $item);
    return response($request, 200);
  }

  public function class_delete($id)
  {
    return $this->class_repository->delete($id);
  }

  public function member_list($e_classes_id)
  {
    return $this->member_query_service->get_member_list($e_classes_id);
  }

  public function member_delete($e_classes_id, $user_id)
  {
    return $this->member_repository->delete($e_classes_id, $user_id);
  }

  public function member_list_menu($e_classes_id)
  {
    return $this->member_query_service->get_member_list_menu($e_classes_id);
  }

  public function member_join_list($e_classes_id, $keyword)
  {
    $e_member = $this->member_query_service->get_class_to_user($e_classes_id);
    return $this->user_query_service->get_member_join_list($e_member, $keyword);
  }

  public function member_join($e_classes_id, $request)
  {
    $user = $this->member_query_service->get_member_join($e_classes_id, $request);
    if($user == null){
      $item = array();
      $item['user_id'] = $request->id;
      $item['e_classes_id'] = $e_classes_id;
      $this->member_repository->create($item);
      return response($request, 201);
    }
    else return response($request, 400);
  }

  public function member_join_self($request, $user_id)
  {
    if($request->pass_code != null){
      $e_class = $this->class_query_service->get_pass_code($request);
      $user = $this->member_query_service->get_class_to_user_one($e_class, $user_id);
      if($e_class == null || $e_class->updated_at < date("Y-m-d",strtotime("-10 day")) || $user != null) return response($request, 400);
      else{
        $item = array();
        $item['user_id'] = $user_id;
        $item['e_classes_id'] = $e_class->id;
        $this->member_repository->create($item);
        return response($item, 201);
      }
    }
  }

}
