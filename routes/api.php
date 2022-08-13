<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

//ログイン・ログオフ・認証
Route::post('/e_learning2/login', 'Auth\LoginController@login')->name('login');
Route::post('/e_learning2/logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/e_learning2/user', fn () => Auth::user())->name('user');
//問題
Route::get('/e_learning2/section_menu1/{e_groups_id}', 'E_learning2_QuestionController@question_menu');
Route::get('/e_learning2/question/{e_groups_id}/{no}', 'E_learning2_QuestionController@question_list');
Route::get('/e_learning2/question/{id}', 'E_learning2_QuestionController@question_show');
Route::post('/e_learning2/question', 'E_learning2_QuestionController@question_create');
Route::put('/e_learning2/question/{id}', 'E_learning2_QuestionController@question_update');
Route::delete('/e_learning2/question/{id}', 'E_learning2_QuestionController@question_delete');
Route::post('/e_learning2/question/import', 'E_learning2_QuestionController@question_import');
Route::post('/e_learning2/question/import2', 'E_learning2_QuestionController@question_import2');
Route::post('/e_learning2/question/upload', 'E_learning2_QuestionController@question_upload');
Route::get('/e_learning2/question/answer/{e_groups_id}/{no}', 'E_learning2_QuestionController@answer_list');
Route::get('/e_learning2/question/answer_g/{e_groups_id}/{no}/{q_no}', 'E_learning2_QuestionController@answer_graph');
//グループ
Route::get('/e_learning2/groups_menu', 'E_learning2_GroupController@groups_menu');
Route::get('/e_learning2/group', 'E_learning2_GroupController@group_index');
Route::get('/e_learning2/group/{id}', 'E_learning2_GroupController@group_show');
Route::post('/e_learning2/group', 'E_learning2_GroupController@group_store');
Route::put('/e_learning2/group/{id}', 'E_learning2_GroupController@group_update');
Route::delete('/e_learning2/group/{id}', 'E_learning2_GroupController@group_delete');
Route::get('/e_learning2/owner_list/{e_groups_id}', 'E_learning2_GroupController@owner_list');
Route::delete('/e_learning2/owner_list/{user_id}', 'E_learning2_GroupController@owner_list_delete');
Route::get('/e_learning2/group_join/{e_groups_id}/{keyword}', 'E_learning2_GroupController@group_user_index');
Route::post('/e_learning2/group_join/{e_groups_id}', 'E_learning2_GroupController@group_join');
//クラス
Route::get('/e_learning2/classes_menu', 'E_learning2_ClassController@classes_menu');
Route::get('/e_learning2/class_list/{e_groups_id}', 'E_learning2_ClassController@class_index');
Route::get('/e_learning2/class/{id}', 'E_learning2_ClassController@class_show');
Route::post('/e_learning2/class', 'E_learning2_ClassController@class_store');
Route::put('/e_learning2/class/{id}', 'E_learning2_ClassController@class_update');
Route::delete('/e_learning2/class/{id}', 'E_learning2_ClassController@class_delete');
Route::get('/e_learning2/member_list/{e_classes_id}', 'E_learning2_ClassController@member_list');
Route::delete('/e_learning2/member_list/{user_id}', 'E_learning2_ClassController@member_list_delete');
Route::get('/e_learning2/member_list2/{e_classes_id}', 'E_learning2_ClassController@member_list2');
Route::get('/e_learning2/class_join1/{e_classes_id}/{keyword}', 'E_learning2_ClassController@class_user_index');
Route::post('/e_learning2/class_join1/{e_classes_id}', 'E_learning2_ClassController@class_join1');
Route::post('/e_learning2/class_join2', 'E_learning2_ClassController@class_join2');
//実施設定
Route::get('/e_learning2/section_menu2/{e_classes_id}', 'E_learning2_SettingController@section_menu2');
Route::get('/e_learning2/select_title/{e_classes_id}', 'E_learning2_SettingController@select_title');
Route::put('/e_learning2/question_setting/{e_classes_id}', 'E_learning2_SettingController@question_setting');
//生徒
Route::get('/e_learning2/st_menu/{e_classes_id}', 'E_learning2_StudentController@st_menu');
Route::get('/e_learning2/st/{e_classes_id}/{no}', 'E_learning2_StudentController@rdm_list');
Route::get('/e_learning2/st/rand/{a_ptn}', 'E_learning2_StudentController@a_ptn');
Route::post('/e_learning2/st/answer', 'E_learning2_StudentController@answer_record');
Route::get('/e_learning2/st/answer/{id}/{e_classes_id}', 'E_learning2_StudentController@answer_list2');
Route::delete('/e_learning2/st/answer/{id}', 'E_learning2_StudentController@answer_delete');
//ユーザ管理
Route::get('/e_learning2/ad', 'E_learning2_UserController@user_index');
Route::get('/e_learning2/ad/{keyword}', 'E_learning2_UserController@user_search');
Route::get('/e_learning2/ad/{id}', 'E_learning2_UserController@user_show');
Route::post('/e_learning2/ad', 'E_learning2_UserController@user_create');
Route::put('/e_learning2/ad/{id}', 'E_learning2_UserController@user_update');
Route::delete('/e_learning2/ad/{id}', 'E_learning2_UserController@user_delete');
Route::post('/e_learning2/ad/import', 'E_learning2_UserController@user_import');
Route::post('/e_learning2/ad/import2', 'E_learning2_UserController@user_import2');
// トークンリフレッシュ
Route::get('/e_learning2/reflesh-token', function (Illuminate\Http\Request $request) {
  $request->session()->regenerateToken();
  return response()->json();
});
