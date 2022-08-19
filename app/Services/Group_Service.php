<?php

namespace App\Services;

use App\Repositories\E_Group_RepositoryInterface;
use App\Repositories\E_Owner_RepositoryInterface;
use App\Services\Class_Query_ServiceInterface;
use App\Services\Group_Query_ServiceInterface;
use App\Services\Member_Query_ServiceInterface;
use App\Services\Owner_Query_ServiceInterface;
use App\Services\User_Query_ServiceInterface;

class Group_Service implements Group_ServiceInterface
{
  private $group_repository;
  private $class_query_service;
  private $group_query_service;
  private $member_query_service;
  private $owner_query_service;
  private $user_query_service;

  public function __construct(
    E_Group_RepositoryInterface $group_repository,
    E_Owner_RepositoryInterface $owner_repository,
    Class_Query_ServiceInterface $class_query_service,
    Group_Query_ServiceInterface $group_query_service,
    Member_Query_ServiceInterface $member_query_service,
    Owner_Query_ServiceInterface $owner_query_service,
    User_Query_ServiceInterface $user_query_service
  ) {
    $this->group_repository = $group_repository;
    $this->owner_repository = $owner_repository;
    $this->class_query_service = $class_query_service;
    $this->group_query_service = $group_query_service;
    $this->member_query_service = $member_query_service;
    $this->owner_query_service = $owner_query_service;
    $this->user_query_service = $user_query_service;
  }

  public function groups_menu($user_id)
  {
    $items = $this->member_query_service->get_user_to_class($user_id);
    $items = $this->class_query_service->get_class_to_groups($items);
    return $this->group_query_service->get_group_to_menu($items);
  }

  public function group_list($user_id)
  {
    $items = $this->owner_query_service->get_user_to_owner($user_id);
    return $this->group_query_service->get_group_to_menu($items);
  }

  public function group_show($id)
  {
    return $this->group_repository->show($id);
  }

  public function group_create($request, $user_id)
  {
    $e_group = $this->group_query_service->get_group_name($request);
    if($e_group != null) return response($request, 400);
    $this->group_repository->create($request->all());
    $e_group = $this->group_query_service->get_group_name($request);
    unset($item);
    $item['user_id'] = $user_id;
    $item['e_groups_id'] = $e_group->id;
    $this->owner_repository->create($item);
    return response($item, 201);;
  }

  public function group_update($id, $request)
  {
    $e_group = $this->group_query_service->get_group_name($request);
    if($e_group != null) return response($request, 400);
    return $this->group_repository->update($id, $request->all());
  }

  public function group_delete($id)
  {
    return $this->group_repository->delete($id);
  }

  public function owner_list($e_groups_id)
  {
    return $this->owner_query_service->get_owner_list($e_groups_id);
  }

  public function owner_delete($e_groups_id, $user_id)
  {
    $this->owner_repository->delete($e_groups_id, $user_id);
  }

  public function owner_join_list($e_groups_id, $keyword)
  {
    $e_owner = $this->owner_query_service->get_e_groups_to_user($e_groups_id);
    return $this->user_query_service->get_owner_join_list($e_owner, $keyword);

  }

  public function owner_join($e_groups_id, $request)
  {
    $user = $this->owner_query_service->get_owner_join($e_groups_id, $request);

    if($user == null){
      $item = array();
      $item['user_id'] = $request->id;
      $item['e_groups_id'] = $e_groups_id;
      $this->owner_repository->create($item);
      return response($request, 201);
    }
    else return response($request, 400);
  }

}
