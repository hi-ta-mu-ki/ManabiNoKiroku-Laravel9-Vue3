<?php

namespace App\Services;

interface User_ServiceInterface
{
  public function all();
  public function search($keyword);
  public function create($item);
  public function show($id);
  public function update($id, $item);
  public function delete($id);
  public function import($request);
  public function import2($request);
}