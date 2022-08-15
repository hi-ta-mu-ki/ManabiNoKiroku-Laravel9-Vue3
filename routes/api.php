<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'e_learning2'], function () {
  //ログイン・ログオフ・認証
  Route::post('login', 'Auth\LoginController@login')->name('login');
  Route::post('logout', 'Auth\LoginController@logout')->name('logout');
  Route::get('user', fn () => Auth::user())->name('user');

  //問題
  Route::controller(E_learning2_QuestionController::class)->group(function(){
    Route::get('section_menu1/{e_groups_id}', 'question_menu');
    Route::get('question/{e_groups_id}/{no}', 'question_list');
    Route::get('question/{id}', 'question_show');
    Route::post('question', 'question_create');
    Route::put('question/{id}', 'question_update');
    Route::delete('question/{id}', 'question_delete');
    Route::post('question/import', 'question_import');
    Route::post('question/import2', 'question_import2');
    Route::post('question/upload', 'question_upload');
    Route::get('question/answer/{e_groups_id}/{no}', 'answer_list');
    Route::get('question/answer_g/{e_groups_id}/{no}/{q_no}', 'answer_graph');
  });
//グループ
  Route::controller(E_learning2_GroupController::class)->group(function(){
    Route::get('groups_menu/{user_id}', 'groups_menu');
    Route::get('group_list/{user_id}', 'group_list');
    Route::get('group/{id}', 'group_show');
    Route::post('group/{user_id}', 'group_create');
    Route::put('group/{id}', 'group_update');
    Route::delete('group/{id}', 'group_delete');
    Route::get('owner_list/{e_groups_id}', 'owner_list');
    Route::delete('owner_list/{$e_groups_id}/{user_id}', 'owner_delete');
    Route::get('group_join/{e_groups_id}/{keyword}', 'owner_join_list');
    Route::post('group_join/{e_groups_id}', 'owner_join');
  });
  //クラス
  Route::controller(E_learning2_ClassController::class)->group(function(){
    Route::get('classes_menu/{user_id}', 'classes_menu');
    Route::get('class_list/{e_groups_id}', 'class_list');
    Route::get('class/{id}', 'class_show');
    Route::post('class/{user_id}', 'class_create');
    Route::put('class/{id}', 'class_update');
    Route::delete('class/{id}', 'class_delete');
    Route::get('member_list/{e_classes_id}', 'member_list');
    Route::delete('member_list/{e_classes_id}/{user_id}', 'member_delete');
    Route::get('member_list_menu/{e_classes_id}', 'member_list_menu');
    Route::get('class_join/{e_classes_id}/{keyword}', 'member_join_list');
    Route::post('class_join/{e_classes_id}', 'member_join');
    Route::post('class_join_self/{user_id}', 'member_join_self');
  });
  //実施設定
  Route::controller(E_learning2_SettingController::class)->group(function(){
    Route::get('section_menu2/{e_classes_id}', 'section_menu2');
    Route::get('select_title/{e_classes_id}', 'select_title');
    Route::put('question_setting/{e_classes_id}', 'question_setting');
  });
  //生徒
  Route::controller(E_learning2_StudentController::class)->group(function(){
    Route::get('st_menu/{e_classes_id}', 'st_menu');
    Route::get('st/{e_classes_id}/{no}', 'question_randomize');
    Route::post('st/answer', 'answer_record');
    Route::get('st/answer/{user_id}/{e_classes_id}', 'answer_list2');
    Route::delete('st/answer/{user_id}/{e_classes_id}', 'answer_delete');
  });
  //ユーザ管理
  Route::controller(E_learning2_UserController::class)->group(function(){
    Route::get('ad', 'user_list');
    Route::get('ad/search/{keyword}', 'user_search');
    Route::get('ad/{id}', 'user_show');
    Route::post('ad', 'user_create');
    Route::put('ad/{id}', 'user_update');
    Route::delete('ad/{id}', 'user_delete');
    Route::post('ad/import', 'user_import');
    Route::post('ad/import2', 'user_import2');
  });
  // トークンリフレッシュ
  Route::get('reflesh-token', function (Illuminate\Http\Request $request) {
    $request->session()->regenerateToken();
    return response()->json();
  });
});
