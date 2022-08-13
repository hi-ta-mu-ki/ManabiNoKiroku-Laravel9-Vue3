<?php

namespace App\Services;

use App\Repositories\E_Class_RepositoryInterface;
use App\Repositories\E_Member_RepositoryInterface;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\E_class;
use App\Models\E_member;

class Class_Service implements Class_ServiceInterface
{
  private $class_repository;

  public function __construct(
    E_Class_RepositoryInterface $class_repository,
    E_Member_RepositoryInterface $member_repository
  ) {
    $this->class_repository = $class_repository;
    $this->member_repository = $member_repository;
  }

  public function classes_menu()
  {
    $user_id = Auth::user()->id;
    $items = E_member::where('user_id', $user_id)->select('e_classes_id')->getQuery();
    return E_class::whereIn('id', $items)->get();
  }

  public function class_list($e_groups_id)
  {
    return E_class::where('e_groups_id', $e_groups_id)->get();
  }

  public function class_show($id)
  {
    return $this->class_repository->show($id);
  }

  public function class_create($item)
  {
    if($item['pass_code'] != null){
      $e_class = E_class::where('pass_code', $item['pass_code'])->first();
      if($e_class != null) return response($item, 400);
    }
    $e_class = E_class::where('name', $item['name'])->first();
    if($e_class != null) return response($item, 400);
    $this->class_repository->create($item);
    $e_class = E_class::where('pass_code', $item['pass_code'])->first();
    unset($item);
    $item['user_id'] = Auth::user()->id;
    $item['e_classes_id'] = $e_class->id;
    $this->member_repository->create($item);
    return response($item, 201);
  }

  public function class_update($id, $item)
  {
    if($item['pass_code'] != null){
      $e_class = E_class::where('pass_code', $item['pass_code'])->first();
      if($e_class != null) return response($item, 400);
    }
    $e_class = E_class::where('name', $item['name'])->first();
    if($e_class == null) return response($item, 400);
    $item['e_classes_id'] = $e_class->e_classes_id;
    $this->class_repository->update($id, $item);
    return response($item, 200);
  }

  public function class_delete($id)
  {
    return $this->class_repository->delete($id);
  }

  public function member_list($e_classes_id)
  {
    return E_member::where('e_classes_id', $e_classes_id)->with('user')->orderBy('user_id', 'asc')->get();
  }

  public function member_delete($id)
  {
    return $this->member_repository->delete($id);
  }

  public function member_list2($e_classes_id)
  {
    return E_member::select('user_id','name')->join('users', 'users.id','=','e_members.user_id')->where('e_classes_id', $e_classes_id)->where('role', 10)->orderBy('user_id', 'asc')->get();
  }

  public function member_join_list($e_classes_id, $keyword)
  {
    $e_member = E_member::where('e_classes_id', $e_classes_id)->select('user_id')->getQuery();
    return User::where('role', '!=', 1)->whereNotIn('id', $e_member)->where('name', 'like', '%' . $keyword . '%')->select('id', 'name')->get();
  }

  public function member_join($e_classes_id, $user_id)
  {
    $user = E_member::where('e_classes_id', $e_classes_id)->where('user_id', $user_id)->first();
    if($user == null){
      $item = array();
      $item['user_id'] = $user_id;
      $item['e_classes_id'] = $e_classes_id;
      $this->member_repository->create($item);
      return response($user_id, 201);
    }
    else return response($user_id, 400);
  }

  public function member_join2($pass_code)
  {
    if($pass_code != null){
      $e_class = E_class::where('pass_code', $pass_code)->first();
      $user = E_member::where('e_classes_id', $e_class->id)->where('user_id', Auth::user()->id)->first();
      if($e_class == null || $e_class->updated_at < date("Y-m-d",strtotime("-10 day")) || $user != null) return response($pass_code, 400);
      else{
        $item = array();
        $item['user_id'] = Auth::user()->id;
        $item['e_classes_id'] = $e_class->id;
        $this->member_repository->create($item);
        return response($item, 201);
      }
    }
  }

}
