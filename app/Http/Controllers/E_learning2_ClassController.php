<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\E_class;
use App\Models\E_member;

class E_learning2Controller extends Controller
{

  public function classes_menu()
  {
    $user_id = Auth::user()->id;
    $items = E_member::where('user_id', $user_id)->select('e_classes_id')->getQuery();
    return E_class::whereIn('id', $items)->get();
  }

  public function class_index($e_groups_id)
  {
    return E_class::where('e_groups_id', $e_groups_id)->get();
  }

  public function class_show(E_class $id)
  {
    return $id;
  }

  public function class_store(Request $request)
  {
    if($request->pass_code != null){
      $e_class = E_class::where('pass_code', $request->pass_code)->first();
      if($e_class != null) return response($request, 400);
    }
    $e_class = E_class::where('name', $request->name)->first();
    if($e_class != null) return response($request, 400);
    $class = new E_class;
    $class->e_groups_id = $request->e_groups_id;
    $class->name = $request->name;
    $class->pass_code = $request->pass_code;
    $class->save();
    $e_class = E_class::where('pass_code', $request->pass_code)->first();
    $e_member = new E_member;
    $e_member->user_id = Auth::user()->id;
    $e_member->e_classes_id = $e_class->id;
    $e_member->save();
    return response($request, 201);
  }

  public function class_update(Request $request, E_class $id)
  {
    if($request->pass_code != null){
      $e_class = E_class::where('pass_code', $request->pass_code)->first();
      if($e_class != null) return response($request, 400);
    }
    $e_class = E_class::where('name', $request->name)->first();
    if($e_class == null) return response($request, 400);
    $id->update($request->all());
    return response($id, 200);
  }

  public function class_destroy(E_class $id)
  {
    $id->delete();
    return $id;
  }

  public function member_list($e_classes_id)
  {
    return E_member::where('e_classes_id', $e_classes_id)->with(['user' => function ($query) {$query->orderBy('role', 'asc');}])->orderBy('user_id', 'asc')->get();
  }

  public function member_list_delete($id)
  {
    E_member::where('user_id', $id)->delete();
  }

  public function member_list2($e_classes_id)
  {
    return E_member::select('user_id','name')->join('users', 'users.id','=','e_members.user_id')->where('e_classes_id', $e_classes_id)->where('role', 10)->orderBy('user_id', 'asc')->get();
  }

  public function class_user_index($e_classes_id, $keyword)
  {
    $e_member = E_member::where('e_classes_id', $e_classes_id)->select('user_id')->getQuery();
    $add_member = User::where('role', '!=', 1)->whereNotIn('id', $e_member)->where('name', 'like', '%' . $keyword . '%')->select('id', 'name')->get();
    return $add_member;
  }

  public function class_join1($e_classes_id, Request $request)
  {
    $user = E_member::where('e_classes_id', $e_classes_id)->where('user_id', $request->id)->first();
    if($user == null){
      $e_member = new E_member;
      $e_member->user_id = $request->id;
      $e_member->e_classes_id = $e_classes_id;
      $e_member->save();
      return response($request, 201);
    }
    else return response($request, 400);
  }

  public function class_join2(Request $request)
  {
    if($request->pass_code != null){
      $e_class = E_class::where('pass_code', $request->pass_code)->first();
      $user = E_member::where('user_id', Auth::user()->id)->first();
      if($e_class == null || $e_class->updated_at < date("Y-m-d",strtotime("-10 day")) || $user != null) return response($request, 400);
      else{
        $e_member = new E_member;
        $e_member->user_id = Auth::user()->id;
        $e_member->e_classes_id = $e_class->id;
        $e_member->save();
        return response($request, 201);
      }
    }
  }

}