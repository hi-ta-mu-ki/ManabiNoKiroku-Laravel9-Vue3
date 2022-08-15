<?php

namespace App\Http\Controllers;

use App\Services\Group_ServiceInterface;
use Illuminate\Http\Request;

class E_learning2_GroupController extends Controller
{
  private Group_ServiceInterface $group_service;

  public function __construct(Group_ServiceInterface $group_service)
  {
    $this->group_service = $group_service;
  }

  public function groups_menu($user_id)
  {
    return $this->group_service->groups_menu($user_id);
  }

  public function group_list($user_id)
  {
    return $this->group_service->group_list($user_id);
  }

  public function group_show($id)
  {
    return $this->group_service->group_show($id);
  }

  public function group_create(Request $request, $user_id)
  {
    return $this->group_service->group_create($request, $user_id);
    
  }

  public function group_update(Request $request, $id)
  {
    return $this->group_service->group_update($id, $request);
  }

  public function group_delete($id)
  {
    return $this->group_service->group_delete($id);
  }

  public function owner_list($e_groups_id)
  {
    return $this->group_service->owner_list($e_groups_id);
  }

  public function owner_delete($e_groups_id, $user_id)
  {
    return $this->group_service->owner_delete($e_groups_id, $user_id);
  }

  public function owner_join_list($e_groups_id, $keyword)
  {
    return $this->group_service->owner_join_list($e_groups_id, $keyword);
  }

  public function owner_join($e_groups_id, Request $request)
  {
    return $this->group_service->owner_join($e_groups_id, $request);
  }

}