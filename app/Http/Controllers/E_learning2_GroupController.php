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

  public function groups_menu()
  {
    return $this->group_service->groups_menu();
  }

  public function group_list()
  {
    return $this->group_service->group_list();
  }

  public function group_show($id)
  {
    return $this->group_service->group_show($id);
  }

  public function group_create(Request $request)
  {
    $item = $request->only(['name']);
    return $this->group_service->group_create($item);
    
  }

  public function group_update(Request $request, $id)
  {
    $item = $request->only(['name']);
    return $this->group_service->group_update($id, $item);
  }

  public function group_delete($id)
  {
    return $this->group_service->group_delete($id);
  }

  public function owner_list($e_groups_id)
  {
    return $this->group_service->owner_list($e_groups_id);
  }

  public function owner_delete($id)
  {
    return $this->group_service->owner_delete($id);
  }

  public function owner_join_list($e_groups_id, $keyword)
  {
    return $this->group_service->owner_join_list($e_groups_id, $keyword);
  }

  public function owner_join($e_groups_id, Request $request)
  {
    $user_id = $request->id;
    return $this->group_service->owner_join($e_groups_id, $user_id);
  }

}