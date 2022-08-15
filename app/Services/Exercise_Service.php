<?php

namespace App\Services;

use App\Repositories\Exercise_RepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Facades\Csv;
use SplFileObject;
use Illuminate\Support\Facades\Storage;
use App\Models\Exercise;
use App\Models\E_class;
use App\Models\E_answer;

class Exercise_Service implements Exercise_ServiceInterface
{
  private $exercise_repository;

  public function __construct(
    Exercise_RepositoryInterface $exercise_repository
  ) {
    $this->exercise_repository = $exercise_repository;
  }

  public function menu($e_groups_id)
  {
    return Exercise::where('q_no', 0)->where('e_groups_id', $e_groups_id)->get();
  }

  public function list($e_groups_id, $no)
  {
    return Exercise::where('e_groups_id', $e_groups_id)->where('no', $no)->get();
  }

  public function show($id)
  {
    return $this->exercise_repository->show($id);
  }

  public function create($request)
  {
    return $this->exercise_repository->create($request->all());
  }

  public function update($id, $request)
  {
    return $this->exercise_repository->update($id, $request->all());
  }

  public function delete($id)
  {
    return $this->exercise_repository->delete($id);
  }

  public function import($request)
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
      $this->exercise_repository->create($value);
      //      $ret['insert'][] = ['line' => $line, 'message' => $value['name']];
    } // １行ずつ処理
    // 結果を戻す
    return response($request, 201);
    //    return ['import' => $ret];
  }

  public function import2($request)
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
      $this->exercise_repository->create($array);
    } else {
      //追加した配列が500以上なら、array_chunkで500ずつ分割する
      $array_partial = array_chunk($array, 500); //配列分割
      //分割した数を数えて
      $array_partial_count = count($array_partial); //配列の数
      //分割した数の分だけインポートを繰り替えす
      for ($i = 0; $i <= $array_partial_count - 1; $i++){
        $this->exercise_repository->create($array_partial[$i]);
      }
    }
    return response($request, 201);
  }

  public function upload($request)
  {
    $exists = Storage::disk('public')->exists($request->photo->getClientOriginalName());
    $filename = $request->photo->getClientOriginalName();
    if($exists == true)
      Storage::disk('public')->delete($filename);
    Storage::disk('public')->putFileAs('', $request->photo, $filename);
    return response($request, 201);
  }

  public function answer_list($e_groups_id, $no)
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

  public function answer_graph($e_groups_id, $no, $q_no)
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

}
