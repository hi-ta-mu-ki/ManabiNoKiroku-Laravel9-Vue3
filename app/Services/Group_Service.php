<?php

namespace App\Services;

use App\Repositories\E_Group_RepositoryInterface;
use App\Repositories\E_Owner_RepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\E_group;
use App\Models\E_owner;
use App\Models\E_class;
use App\Models\E_member;

class Group_Service implements Group_ServiceInterface
{
  private $group_repository;

  public function __construct(
    E_Group_RepositoryInterface $group_repository,
    E_Owner_RepositoryInterface $owner_repository
  ) {
    $this->group_repository = $group_repository;
    $this->owner_repository = $owner_repository;
  }

  public function groups_menu()
  {
    $user_id = Auth::user()->id;
    $items1 = E_member::where('user_id', $user_id)->select('e_classes_id')->getQuery();
    $items2 = E_class::whereIn('id', $items1)->select('e_groups_id')->getQuery();
    return E_group::whereIn('id', $items2)->get();
  }

  public function group_list()
  {
    $user_id = Auth::user()->id;
    $items = E_owner::where('user_id', $user_id)->select('e_groups_id')->getQuery();
    return E_group::whereIn('id', $items)->get();
  }

  public function group_show($id)
  {
    return $this->group_repository->show($id);
  }

  public function group_create($item)
  {
    $e_group = E_group::where('name', $item['name'])->first();
    if($e_group != null) return response($item, 400);
    $this->group_repository->create($item);
    $e_group = E_group::where('name', $item['name'])->first();
    unset($item);
    $item['user_id'] = Auth::user()->id;
    $item['e_groups_id'] = $e_group->id;
    $this->owner_repository->create($item);
    return response($item, 201);;
  }

  public function group_update($id, $item)
  {
    $e_group = E_group::where('name', $item['name'])->first();
    if($e_group != null) return response($item, 400);
    return $this->group_repository->update($id, $item);
  }

  public function group_delete($id)
  {
    return $this->group_repository->delete($id);
  }

  public function owner_list($e_groups_id)
  {
    return E_owner::where('e_groups_id', $e_groups_id)->with('user')->orderBy('user_id', 'asc')->get();
  }

  public function owner_delete($id)
  {
    $this->owner_repository->delete($id);
  }

  public function owner_join_list($e_groups_id, $keyword)
  {
    $e_owner = E_owner::where('e_groups_id', $e_groups_id)->select('user_id')->getQuery();
    return User::where('role', '!=', 1)->where('role', '!=', 10)->whereNotIn('id', $e_owner)->where('name', 'like', '%' . $keyword . '%')->select('id', 'name')->get();
  }

  public function owner_join($e_groups_id, $user_id)
  {
    $user = E_owner::where('e_groups_id', $e_groups_id)->where('user_id', $user_id)->first();
    if($user == null){
      $item = array();
      $item['user_id'] = $user_id;
      $item['e_groups_id'] = $e_groups_id;
      $this->owner_repository->create($item);
      return response($user_id, 201);
    }
    else return response($user_id, 400);
  }

}