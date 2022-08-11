<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\E_group;
use App\Models\E_owner;
use App\Models\E_class;
use App\Models\E_member;

class E_learning2Controller extends Controller
{

  public function groups_menu()
  {
    $user_id = Auth::user()->id;
    $items1 = E_member::where('user_id', $user_id)->select('e_classes_id')->getQuery();
    $items2 = E_class::whereIn('id', $items1)->select('e_groups_id')->getQuery();
    return E_group::whereIn('id', $items2)->get();
  }

  public function group_index()
  {
    $user_id =Auth::user()->id;
    $items = E_owner::where('user_id', $user_id)->select('e_groups_id')->getQuery();
    return E_group::whereIn('id', $items)->get();
  }

  public function group_show(E_group $id)
  {
    return $id;
  }

  public function group_store(Request $request)
  {
    $e_group = E_group::where('name', $request->name)->first();
    if($e_group != null) return response($request, 400);
    $group = new E_group;
    $group->name = $request->name;
    $group->save();
    $e_group = E_group::where('name', $request->name)->first();
    $e_owner = new E_owner;
    $e_owner->user_id = Auth::user()->id;
    $e_owner->e_groups_id = $e_group->id;
    $e_owner->save();
    return response($request, 201);
  }

  public function group_update(Request $request, E_group $id)
  {
    $e_group = E_group::where('name', $request->name)->first();
    if($e_group != null) return response($request, 400);
    $id->update($request->all());
    return $id;
  }

  public function group_destroy(E_group $id)
  {
    $id->delete();
    return $id;
  }

  public function owner_list($e_groups_id)
  {
    return E_owner::where('e_groups_id', $e_groups_id)->with('user')->orderBy('user_id', 'asc')->get();
  }

  public function owner_list_delete($id)
  {
    E_owner::where('user_id', $id)->delete();
  }

  public function group_user_index($e_groups_id, $keyword)
  {
    $e_owner = E_owner::where('e_groups_id', $e_groups_id)->select('user_id')->getQuery();
    $add_owner = User::where('role', '!=', 1)->where('role', '!=', 10)->whereNotIn('id', $e_owner)->where('name', 'like', '%' . $keyword . '%')->select('id', 'name')->get();
    return $add_owner;
  }

  public function group_join($e_groups_id, Request $request)
  {
    $user = E_owner::where('e_groups_id', $e_groups_id)->where('user_id', $request->id)->first();
    if($user == null){
      $e_owner = new E_owner;
      $e_owner->user_id = $request->id;
      $e_owner->e_groups_id = $e_groups_id;
      $e_owner->save();
      return response($request, 201);
    }
    else return response($request, 400);
  }

}