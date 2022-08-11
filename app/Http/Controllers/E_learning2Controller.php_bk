<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\Exercise;
use App\Models\Ans_pattern;
use App\Models\User;
use App\Models\E_answer;
use App\Models\E_setting;
use App\Models\E_group;
use App\Models\E_owner;
use App\Models\E_class;
use App\Models\E_member;
use App\Facades\Csv;
use App\Http\Requests\UploadCsvFile;
use App\Http\Requests\StorePhoto;
use SplFileObject;

class E_learning2Controller extends Controller
{

  public function section_menu1($e_groups_id)
  {
    return Exercise::where('q_no', 0)->where('e_groups_id', $e_groups_id)->get();
  }

  public function section_menu2($e_classes_id)
  {
    $e_groups_id = E_class::where('id', $e_classes_id)->select('e_groups_id')->getQuery();
    return Exercise::where('q_no', 0)->whereIn('e_groups_id', $e_groups_id)->get();
  }

  public function question_list($e_groups_id, $no)
  {
    return Exercise::where('e_groups_id', $e_groups_id)->where('no', $no)->get();
  }

  public function question_show(Exercise $id)
  {
    return $id;
  }

  public function question_store(Request $request)
  {
    return Exercise::create($request->all());
  }

  public function question_update(Request $request, Exercise $id)
  {
    $id->update($request->all());
    return $id;
  }

  public function question_destroy(Exercise $id)
  {
    $id->delete();
    return $id;
  }

  public function question_import(UploadCsvFile $request)
  {
    Log::Debug(__CLASS__.':'.__FUNCTION__, $request->all());
    // 拡張子チェックがうまく動かないことがあるので独自で実施
    // -- https://api.symfony.com/3.0/Symfony/Component/HttpFoundation/File/UploadedFile.html
    $file = $request -> file('csvfile');
    if ($file ->getClientOriginalExtension() != 'csv') {
      Log::Debug(__CLASS__.':'.__FUNCTION__.' File Name: '. $file ->getClientOriginalName());
      Log::Debug(__CLASS__.':'.__FUNCTION__.' File Extension: '. $file ->getClientOriginalExtension());
      Log::Debug(__CLASS__.':'.__FUNCTION__.' ClientMimeType: '. $file ->getClientMimeType());
      Log::Debug(__CLASS__.':'.__FUNCTION__.' MimeType: '. $file ->getMimeType());
      return new JsonResponse(['errors' => [ 'csvfile' => 'CSVファイルを指定してください']], 422);
    }
    // CSV をパース
    try {
      $rows = Csv::parse($file);
    } catch (\Exception $e) {
      Log::Debug(__CLASS__.':'.__FUNCTION__.' parse Exception : '. $e -> getMessage());
      return new JsonResponse(['errors' => [
        'csvfile' => 'CSVファイルの読み込みでエラーが発生しました',
        'exception' => $e -> getMessage()
        ]]
      , 422);
    }
    // １行ずつ処理
    $ret = array();
    foreach ($rows as $line => $value) {
      // 行データに対してのバリデート（必須・内容の確認）
      //      $validator = $this->validator($value);
      // データに問題があればエラーを記録 => 処理は継続
      //            if ($validator -> fails()) {
      //                foreach ($validator -> errors() -> all() as $message) {
      //                    Log::Debug(__CLASS__.':'.__FUNCTION__.' ERROR line('.$line.') '.$message);
      //                    $ret['errors'][] = ['line' => $line, 'message' => $message];
      //                }
      //                continue;
      //            }
      Log::Debug(__CLASS__.':'.__FUNCTION__.' INSERT line('.$line.') '.$value['quest']);
      Exercise::create($value);
      //      $ret['insert'][] = ['line' => $line, 'message' => $value['name']];
    } // １行ずつ処理
    // 結果を戻す
    return response($request, 201);
    //    return ['import' => $ret];
  }

  public function question_import2(UploadCsvFile $request)
  {
    // CSVimport::truncate();
    setlocale(LC_ALL, 'ja_JP.UTF-8');
    $uploaded_file = $request->file('csvfile');
    $file_path = $request->file('csvfile');
    $file = new SplFileObject($file_path);
    $file->setFlags(SplFileObject::READ_CSV);
    //配列の箱を用意
    $array = [];
    $row_count = 1;
    foreach ($file as $row)
    {
      if ($row === [null]) continue; 
      if ($row_count > 1)
      {
        for($i = 0; $i < 13; $i++){
          if($row[$i] == null) $row[$i] = null;
        }
        $e_groups_id = mb_convert_encoding($row[0], 'UTF-8', 'SJIS');
        $no = mb_convert_encoding($row[1], 'UTF-8', 'SJIS');
        $q_no = mb_convert_encoding($row[2], 'UTF-8', 'SJIS');
        $quest = mb_convert_encoding($row[3], 'UTF-8', 'SJIS');
        $ans1 = mb_convert_encoding($row[4], 'UTF-8', 'SJIS');
        $ans2 = mb_convert_encoding($row[5], 'UTF-8', 'SJIS');
        $ans3 = mb_convert_encoding($row[6], 'UTF-8', 'SJIS');
        $ans4 = mb_convert_encoding($row[7], 'UTF-8', 'SJIS');
        $ans = mb_convert_encoding($row[8], 'UTF-8', 'SJIS');
        $exp1 = mb_convert_encoding($row[9], 'UTF-8', 'SJIS');
        $exp2 = mb_convert_encoding($row[10], 'UTF-8', 'SJIS');
        $exp3 = mb_convert_encoding($row[11], 'UTF-8', 'SJIS');
        $exp4 = mb_convert_encoding($row[12], 'UTF-8', 'SJIS');
        $exp0 = mb_convert_encoding($row[13], 'UTF-8', 'SJIS');
        $csvimport_array = [
          'e_groups_id' => $e_groups_id,
          'no' => $no,
          'q_no' => $q_no,
          'quest' => $quest,
          'ans1' => $ans1,
          'ans2' => $ans2,
          'ans3' => $ans3,
          'ans4' => $ans4,
          'ans' => $ans,
          'exp1' => $exp1,
          'exp2' => $exp2,
          'exp3' => $exp3,
          'exp4' => $exp4,
          'exp0' => $exp0
        ];
        // つくった配列の箱($array)に追加
        array_push($array, $csvimport_array);
      }
      $row_count++;
    }
    //追加した配列の数を数える
    $array_count = count($array);
    //もし配列の数が500未満なら
    if ($array_count < 500){
      //配列をまるっとインポート(バルクインサート)
      Exercise::insert($array);
    } else {
      //追加した配列が500以上なら、array_chunkで500ずつ分割する
      $array_partial = array_chunk($array, 500); //配列分割
      //分割した数を数えて
      $array_partial_count = count($array_partial); //配列の数
      //分割した数の分だけインポートを繰り替えす
      for ($i = 0; $i <= $array_partial_count - 1; $i++){
        Exercise::insert($array_partial[$i]);
      }
    }
    return response($request, 201);
  }

  public function question_upload(StorePhoto $request)
  {
    $exists = Storage::disk('public')->exists($request->photo->getClientOriginalName());
    $filename = $request->photo->getClientOriginalName();
    if($exists == true)
      Storage::disk('public')->delete($filename);
    Storage::disk('public')->putFileAs('', $request->photo, $filename);
    return response($request, 201);
  }

  public function question_answer_list($e_groups_id, $no)
  {
    $e_classes_id = E_class::where('e_groups_id', $e_groups_id)->select('id')->getQuery();
    $answers = E_answer::whereIn('e_classes_id', $e_classes_id)->where('no', $no)->orderBy('s_id', 'asc')->orderBy('user_id', 'asc')->orderBy('q_no', 'asc')->with('user')->get();
    $answer_lists = array(array());
    $i = -1;
    $tmp_s_id = 0;
    $tmp_user_id = 0;
    foreach($answers as $answer){
      if($tmp_s_id != $answer->s_id || $tmp_user_id != $answer->user_id){
        $i++;
        $tmp_s_id = $answer->s_id;
        $tmp_user_id = $answer->user_id;
        $answer_lists[$i][0] = $answer->s_id;
        $answer_lists[$i][1] = $answer->user->name;
        $answer_lists[$i][2] = $answer->user->email;
        $answer_lists[$i][3] = $answer->created_at->format('Y年m月d日 H時i分s秒');
        $j = 4;
      }
      $answer_lists[$i][$j] = $answer->correct;
      $j++;
    }
    return $answer_lists;
  }

  public function question_answer_graph($e_groups_id, $no, $q_no)
  {
    $e_classes_id = E_class::where('e_groups_id', $e_groups_id)->select('id')->getQuery();
    $answers = E_answer::whereIn('e_classes_id', $e_classes_id)->where('no', $no)->where('q_no', $q_no)->get();
    $answer_count = array(array());
    for($i = 0; $i < 4; $i++){
      $answer_count[$i] = 0;
    }
    foreach($answers as $answer){
      $answer_count[$answer->ans - 1]++;
    }
    return $answer_count;
  }

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

  public function select_title($e_classes_id)
  {
    $selected_titles = E_setting::where('e_classes_id', $e_classes_id)->get('no');
    $setting = array();
    $i = 0;
    foreach($selected_titles as $selected_title){
      $setting[$i] = $selected_title->no;
      $i++;
    }
    return $setting;
  }

  public function question_setting($e_classes_id, Request $request)
  {
    $selected_titles = $request->input();
    $i = 0;
    $e_groups_id = E_class::where('id', $e_classes_id)->select('e_groups_id')->getQuery();
    $section_titles = Exercise::whereIn('e_groups_id', $e_groups_id)->where('q_no', 0)->get();
    foreach($section_titles as $section_title){
      $item = E_setting::where('e_classes_id', $e_classes_id)->where('no', $section_title->no)->first();
      if($item == null){
        if($i < count($selected_titles)){
          if($section_title->no == $selected_titles[$i]){
            $titles = new E_setting;
            $titles->e_classes_id = $e_classes_id;
            $titles->no = $selected_titles[$i];
            $titles->save();
            $i++;
          }
        }
      }else if($section_title->no == $item->no){
        if($i < count($selected_titles)){
          if($section_title->no == $selected_titles[$i]) $i++;
        }else E_setting::where('e_classes_id', $e_classes_id)->where('no', $section_title->no)->delete();
      }
    }
    return response($request, 201);
  }

  public function st_menu($e_classes_id)
  {
    $selected_titles = E_setting::where('e_classes_id', $e_classes_id)->select('no')->getQuery();
    $e_groups_id = E_class::where('id', $e_classes_id)->select('e_groups_id')->getQuery();
    return Exercise::where('q_no', 0)->whereIn('e_groups_id', $e_groups_id)->whereIn('no', $selected_titles)->get();
  }

  public function rdm_list($e_classes_id, $no)
  {
    $e_groups_id = E_class::where('id', $e_classes_id)->select('e_groups_id')->getQuery();
    $questions = Exercise::whereIn('e_groups_id', $e_groups_id)->where('no', $no)->where('q_no', '>', '0')->get();
    foreach($questions as $question){
      $a_ptn = mt_rand(1, 24);
      $a_ptn_w = Ans_pattern::where('id', $a_ptn)->first();
      $a_ptn_list = array();
      $a_ptn_list[0] = $a_ptn_w->ans1;
      $a_ptn_list[1] = $a_ptn_w->ans2;
      $a_ptn_list[2] = $a_ptn_w->ans3;
      $a_ptn_list[3] = $a_ptn_w->ans4;
      $ans_list = array();
      $ans_list[$a_ptn_list[0] - 1] = $question->ans1;
      $ans_list[$a_ptn_list[1] - 1] = $question->ans2;
      $ans_list[$a_ptn_list[2] - 1] = $question->ans3;
      $ans_list[$a_ptn_list[3] - 1] = $question->ans4;
      $question->ans1 = $ans_list[0];
      $question->ans2 = $ans_list[1];
      $question->ans3 = $ans_list[2];
      $question->ans4 = $ans_list[3];
      $question->ans = $a_ptn_list[$question->ans - 1];
      $exp_list = array();
      $exp_list[$a_ptn_list[0] - 1] = $question->exp1;
      $exp_list[$a_ptn_list[1] - 1] = $question->exp2;
      $exp_list[$a_ptn_list[2] - 1] = $question->exp3;
      $exp_list[$a_ptn_list[3] - 1] = $question->exp4;
      $question->exp1 = $exp_list[0];
      $question->exp2 = $exp_list[1];
      $question->exp3 = $exp_list[2];
      $question->exp4 = $exp_list[3];
    }
    return $questions;
  }

  public function a_ptn($a_ptn)
  {
    return Ans_pattern::where('id', $a_ptn)->get();
  }

  public function answer_record(Request $request)
  {
    $e_answer = new E_answer;
    $e_answer->user_id = $request->user_id;
    $e_answer->e_classes_id = $request->e_classes_id;
    $e_answer->s_id = $request->s_id;
    $e_answer->no = $request->no;
    $e_answer->q_no = $request->q_no;
    $e_answer->ans = $request->ans;
    $e_answer->correct = $request->correct;
    $e_answer->save();
    return  response($request, 201);
  }

  public function answer_list2($id, $e_classes_id)
  {
    $answers = E_answer::where('user_id', $id)->where('e_classes_id', $e_classes_id)->orderBy('no', 'asc')->orderBy('s_id', 'desc')->orderBy('q_no', 'asc')->get();
    $answer_lists = array(array());
    $i = -1;
    $tmp_s_id = 0;
    foreach($answers as $answer){
      if($tmp_s_id != $answer->s_id){
        $i++;
        $tmp_s_id = $answer->s_id;
        $answer_lists[$i][0] = $answer->s_id;
        $sections = Exercise::where('no', $answer->no)->where('q_no', 0)->first();
        $answer_lists[$i][1] = $sections->quest;
        $answer_lists[$i][2] = $answer->created_at->format('Y年m月d日 H時i分s秒');
        $j = 3;
      }
      $answer_lists[$i][$j] = $answer->correct;
      $j++;
    }
    return $answer_lists;
  }

  public function answer_delete($id)
  {
    E_answer::where('user_id', $id)->delete();
  }

  public function user_index()
  {
    return User::all();
  }

  public function user_show(User $id)
  {
    return $id;
  }

  public function user_store(Request $request)
  {
    $user = new User;
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);
    if($request->role > 5)
      $user->password_raw = $request->password;
    $user->role = $request->role;
    $user->save();
    return response($request, 201);
  }

  public function user_update(Request $request, User $id)
  {
    $password_raw = $request->password;
    $request->merge(['password' => Hash::make($request->password)]);
    $id->update($request->all());
    if($request->role > 5){
      $id->password_raw = $password_raw;
      $id->save();
    }
    return $id;
  }

  public function user_destroy(User $id)
  {
    $id->delete();
    return $id;
  }

  public function user_import(UploadCsvFile $request)
  {
    Log::Debug(__CLASS__.':'.__FUNCTION__, $request->all());
    // 拡張子チェックがうまく動かないことがあるので独自で実施
    // -- https://api.symfony.com/3.0/Symfony/Component/HttpFoundation/File/UploadedFile.html
    $file = $request -> file('csvfile');
    if ($file ->getClientOriginalExtension() != 'csv') {
      Log::Debug(__CLASS__.':'.__FUNCTION__.' File Name: '. $file ->getClientOriginalName());
      Log::Debug(__CLASS__.':'.__FUNCTION__.' File Extension: '. $file ->getClientOriginalExtension());
      Log::Debug(__CLASS__.':'.__FUNCTION__.' ClientMimeType: '. $file ->getClientMimeType());
      Log::Debug(__CLASS__.':'.__FUNCTION__.' MimeType: '. $file ->getMimeType());
      return new JsonResponse(['errors' => [ 'csvfile' => 'CSVファイルを指定してください']], 422);
    }
    // CSV をパース
    try {
      $rows = Csv::parse($file);
    } catch (\Exception $e) {
      Log::Debug(__CLASS__.':'.__FUNCTION__.' parse Exception : '. $e -> getMessage());
      return new JsonResponse(['errors' => [
        'csvfile' => 'CSVファイルの読み込みでエラーが発生しました',
        'exception' => $e -> getMessage()
        ]]
      , 422);
    }
    // １行ずつ処理
    $ret = array();
    foreach ($rows as $line => $value) {
      // 行データに対してのバリデート（必須・内容の確認）
      //            $validator = $this->validator($value);
      // データに問題があればエラーを記録 => 処理は継続
      //            if ($validator -> fails()) {
      //                foreach ($validator -> errors() -> all() as $message) {
      //                    Log::Debug(__CLASS__.':'.__FUNCTION__.' ERROR line('.$line.') '.$message);
      //                    $ret['errors'][] = ['line' => $line, 'message' => $message];
      //                }
      //                continue;
      //            }
      Log::Debug(__CLASS__.':'.__FUNCTION__.' INSERT line('.$line.') '.$value['name']);
      $user = new User;
      $user->name = $value['name'];
      $user->email = $value['email'];
      $user->password = Hash::make($value['password']);
      if($value['role'] > 5)
        $user->password_raw = $value['password'];
      $user->role = $value['role'];
      $user->save();
      //      $ret['insert'][] = ['line' => $line, 'message' => $value['name']];
    } // １行ずつ処理
    // 結果を戻す
    return response($request, 201);
    //    return ['import' => $ret];
  }

  public function user_import2(UploadCsvFile $request)
  {
    // CSVimport::truncate();
    setlocale(LC_ALL, 'ja_JP.UTF-8');
    $uploaded_file = $request->file('csvfile');
    $file_path = $request->file('csvfile');
    $file = new SplFileObject($file_path);
    $file->setFlags(SplFileObject::READ_CSV);
    //配列の箱を用意
    $array = [];
    $row_count = 1;
    foreach ($file as $row)
    {
      if ($row === [null]) continue; 
      if ($row_count > 1)
      {
        for($i = 0; $i < 4; $i++){
          if($row[$i] == null) $row[$i] = null;
        }
        $name = mb_convert_encoding($row[0], 'UTF-8', 'SJIS');
        $email = mb_convert_encoding($row[1], 'UTF-8', 'SJIS');
        $password = Hash::make(mb_convert_encoding($row[2], 'UTF-8', 'SJIS'));
        $role = mb_convert_encoding($row[3], 'UTF-8', 'SJIS');
        if($role > 5) $password_raw = mb_convert_encoding($row[2], 'UTF-8', 'SJIS');
        else $password_raw = null;
        $csvimport_array = [
          'name' => $name,
          'email' => $email,
          'password' => $password,
          'password_raw' => $password_raw,
          'role' => $role,
        ];
        // つくった配列の箱($array)に追加
        array_push($array, $csvimport_array);
      }
      $row_count++;
    }
    // 追加した配列の数を数える
    $array_count = count($array);
    // もし配列の数が500未満なら
    if ($array_count < 500){
      // 配列をまるっとインポート(バルクインサート)
      User::insert($array);
    } else {
      // 追加した配列が500以上なら、array_chunkで500ずつ分割する
      $array_partial = array_chunk($array, 500); //配列分割
      // 分割した数を数えて
      $array_partial_count = count($array_partial); //配列の数
      // 分割した数の分だけインポートを繰り替えす
      for ($i = 0; $i <= $array_partial_count - 1; $i++){
        User::insert($array_partial[$i]);
      }
    }
    return response($request, 201);
  }


}