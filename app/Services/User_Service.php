<?php

namespace App\Services;

use App\Repositories\User_RepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Facades\Csv;
use SplFileObject;
use App\Library\DataFormat;

class User_Service implements User_ServiceInterface
{
  private $user_repository;

  public function __construct(
    User_RepositoryInterface $user_repository
  ) {
    $this->user_repository = $user_repository;
  }

  public function all()
  {
    return $this->user_repository->all();
  }

  public function search($keyword)
  {
    return $this->user_repository->search($keyword);
  }

  public function show($id)
  {
    return $this->user_repository->show($id);
  }

  public function create($request)
  {
    $item = DataFormat::user_format($request);
    return $this->user_repository->create($item);
  }

  public function update($id, $request)
  {
    $item = DataFormat::user_format($request);
    return $this->user_repository->update($id, $item);
  }

  public function delete($id)
  {
    return $this->user_repository->delete($id);
  }

  public function import($request)
  {
    Log::Debug(__CLASS__ . ':' . __FUNCTION__, $request->all());
    // 拡張子チェックがうまく動かないことがあるので独自で実施
    // -- https://api.symfony.com/3.0/Symfony/Component/HttpFoundation/File/UploadedFile.html
    $file = $request->file('csvfile');
    if ($file->getClientOriginalExtension() != 'csv') {
      Log::Debug(__CLASS__ . ':' . __FUNCTION__ . ' File Name: ' . $file->getClientOriginalName());
      Log::Debug(__CLASS__ . ':' . __FUNCTION__ . ' File Extension: ' . $file->getClientOriginalExtension());
      Log::Debug(__CLASS__ . ':' . __FUNCTION__ . ' ClientMimeType: ' . $file->getClientMimeType());
      Log::Debug(__CLASS__ . ':' . __FUNCTION__ . ' MimeType: ' . $file->getMimeType());
      return new JsonResponse(['errors' => ['csvfile' => 'CSVファイルを指定してください']], 422);
    }
    // CSV をパース
    try {
      $rows = Csv::parse($file);
    } catch (\Exception $e) {
      Log::Debug(__CLASS__ . ':' . __FUNCTION__ . ' parse Exception : ' . $e->getMessage());
      return new JsonResponse(
        ['errors' => [
          'csvfile' => 'CSVファイルの読み込みでエラーが発生しました',
          'exception' => $e->getMessage()
        ]],
        422
      );
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
      Log::Debug(__CLASS__ . ':' . __FUNCTION__ . ' INSERT line(' . $line . ') ' . $value['name']);
      $user = array();
      $user['name'] = $value['name'];
      $user['email'] = $value['email'];
      $user['password'] = Hash::make($value['password']);
      if ($value['role'] > 5)
        $user['password_raw'] = $value['password'];
      $user['role'] = $value['role'];
      $this->user_repository->create($user);
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
    foreach ($file as $row) {
      if ($row === [null]) continue;
      if ($row_count > 1) {
        for ($i = 0; $i < 4; $i++) {
          if ($row[$i] == null) $row[$i] = null;
        }
        $name = mb_convert_encoding($row[0], 'UTF-8', 'SJIS');
        $email = mb_convert_encoding($row[1], 'UTF-8', 'SJIS');
        $password = Hash::make(mb_convert_encoding($row[2], 'UTF-8', 'SJIS'));
        $role = mb_convert_encoding($row[3], 'UTF-8', 'SJIS');
        if ($role > 5) $password_raw = mb_convert_encoding($row[2], 'UTF-8', 'SJIS');
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
    if ($array_count < 500) {
      // 配列をまるっとインポート(バルクインサート)
      $this->user_repository->create($array);
    } else {
      // 追加した配列が500以上なら、array_chunkで500ずつ分割する
      $array_partial = array_chunk($array, 500); //配列分割
      // 分割した数を数えて
      $array_partial_count = count($array_partial); //配列の数
      // 分割した数の分だけインポートを繰り替えす
      for ($i = 0; $i <= $array_partial_count - 1; $i++) {
        $this->user_repository->create($array_partial[$i]);
      }
    }
    return response($request, 201);
  }
}
