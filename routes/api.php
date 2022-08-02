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
Route::get('/e_learning2/section_menu1/{e_groups_id}', 'E_learning2Controller@section_menu1');
Route::get('/e_learning2/section_menu2/{e_classes_id}', 'E_learning2Controller@section_menu2');
Route::get('/e_learning2/question/{e_groups_id}/{no}', 'E_learning2Controller@question_list');
Route::get('/e_learning2/question/{id}', 'E_learning2Controller@question_show');
Route::post('/e_learning2/question', 'E_learning2Controller@question_store');
Route::put('/e_learning2/question/{id}', 'E_learning2Controller@question_update');
Route::delete('/e_learning2/question/{id}', 'E_learning2Controller@question_destroy');
Route::post('/e_learning2/question/import', 'E_learning2Controller@question_import');
Route::post('/e_learning2/question/import2', 'E_learning2Controller@question_import2');
Route::post('/e_learning2/question/upload', 'E_learning2Controller@question_upload');
Route::get('/e_learning2/question/answer/{e_groups_id}/{no}', 'E_learning2Controller@question_answer_list');
Route::get('/e_learning2/question/answer_g/{e_groups_id}/{no}/{q_no}', 'E_learning2Controller@question_answer_graph');
//グループ
Route::get('/e_learning2/groups_menu', 'E_learning2Controller@groups_menu');
Route::get('/e_learning2/group', 'E_learning2Controller@group_index');
Route::get('/e_learning2/group/{id}', 'E_learning2Controller@group_show');
Route::post('/e_learning2/group', 'E_learning2Controller@group_store');
Route::put('/e_learning2/group/{id}', 'E_learning2Controller@group_update');
Route::delete('/e_learning2/group/{id}', 'E_learning2Controller@group_destroy');
Route::get('/e_learning2/owner_list/{e_groups_id}', 'E_learning2Controller@owner_list');
Route::delete('/e_learning2/owner_list/{user_id}', 'E_learning2Controller@owner_list_delete');
Route::get('/e_learning2/group_join/{e_groups_id}/{keyword}', 'E_learning2Controller@group_user_index');
Route::post('/e_learning2/group_join/{e_groups_id}', 'E_learning2Controller@group_join');
//クラス
Route::get('/e_learning2/classes_menu', 'E_learning2Controller@classes_menu');
Route::get('/e_learning2/class_list/{e_groups_id}', 'E_learning2Controller@class_index');
Route::get('/e_learning2/class/{id}', 'E_learning2Controller@class_show');
Route::post('/e_learning2/class', 'E_learning2Controller@class_store');
Route::put('/e_learning2/class/{id}', 'E_learning2Controller@class_update');
Route::delete('/e_learning2/class/{id}', 'E_learning2Controller@class_destroy');
Route::get('/e_learning2/member_list/{e_classes_id}', 'E_learning2Controller@member_list');
Route::delete('/e_learning2/member_list/{user_id}', 'E_learning2Controller@member_list_delete');
Route::get('/e_learning2/member_list2/{e_classes_id}', 'E_learning2Controller@member_list2');
Route::get('/e_learning2/class_join1/{e_classes_id}/{keyword}', 'E_learning2Controller@class_user_index');
Route::post('/e_learning2/class_join1/{e_classes_id}', 'E_learning2Controller@class_join1');
Route::post('/e_learning2/class_join2', 'E_learning2Controller@class_join2');
//実施設定
Route::get('/e_learning2/select_title/{e_classes_id}', 'E_learning2Controller@select_title');
Route::put('/e_learning2/question_setting/{e_classes_id}', 'E_learning2Controller@question_setting');
//生徒
Route::get('/e_learning2/st_menu/{e_classes_id}', 'E_learning2Controller@st_menu');
Route::get('/e_learning2/st/{e_classes_id}/{no}', 'E_learning2Controller@rdm_list');
Route::get('/e_learning2/st/rand/{a_ptn}', 'E_learning2Controller@a_ptn');
Route::post('/e_learning2/st/answer', 'E_learning2Controller@answer_record');
Route::get('/e_learning2/st/answer/{id}/{e_classes_id}', 'E_learning2Controller@answer_list2');
Route::delete('/e_learning2/st/answer/{id}', 'E_learning2Controller@answer_delete');
//ユーザ管理
Route::get('/e_learning2/ad', 'E_learning2Controller@user_index');
Route::get('/e_learning2/ad/{id}', 'E_learning2Controller@user_show');
Route::post('/e_learning2/ad', 'E_learning2Controller@user_store');
Route::put('/e_learning2/ad/{id}', 'E_learning2Controller@user_update');
Route::delete('/e_learning2/ad/{id}', 'E_learning2Controller@user_destroy');
Route::post('/e_learning2/ad/import', 'E_learning2Controller@user_import');
Route::post('/e_learning2/ad/import2', 'E_learning2Controller@user_import2');
// トークンリフレッシュ
Route::get('/e_learning2/reflesh-token', function (Illuminate\Http\Request $request) {
  $request->session()->regenerateToken();
  return response()->json();
});
