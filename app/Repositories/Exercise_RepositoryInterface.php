<?php

namespace App\Repositories;

interface Exercise_RepositoryInterface
{
  public function all();
  public function create(array $item);
  public function show($id);
  public function update($id, array $item);
  public function delete($id);
}