<?php

namespace App\Services;

interface User_ServiceInterface
{
  public function all();
  public function search($keyword);
  public function create($request);
  public function show($id);
  public function update($id, $request);
  public function delete($id);
  public function import($request);
  public function import2($request);
}