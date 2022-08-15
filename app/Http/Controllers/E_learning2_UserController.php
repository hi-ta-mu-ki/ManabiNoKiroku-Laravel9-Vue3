<?php

namespace App\Http\Controllers;

use App\Services\User_ServiceInterface;
use Illuminate\Http\Request;
use App\Http\Requests\UploadCsvFile;

class E_learning2_UserController extends Controller
{
  private User_ServiceInterface $user_service;

  public function __construct(User_ServiceInterface $user_service)
  {
    $this->user_service = $user_service;
  }

  public function user_list()
  {
    return $this->user_service->all()->paginate(10);
  }

  public function user_search($keyword)
  {
    return $this->user_service->search($keyword)->paginate(10);
  }

  public function user_show($id)
  {
    return $this->user_service->show($id);
  }

  public function user_create(Request $request)
  {
    $this->user_service->create($request);
    return response($request, 201);
  }

  public function user_update($id, Request $request)
  {
    $this->user_service->update($id, $request);
    return response($request, 200);
  }

  public function user_delete($id)
  {
    $this->user_service->delete($id);
    return response(200);
  }

  public function user_import(UploadCsvFile $request)
  {
    $this->user_service->import($request);
    return response($request, 201);
  }

  public function user_import2(UploadCsvFile $request)
  {
    $this->user_service->import2($request);
    return response($request, 201);
  }

}