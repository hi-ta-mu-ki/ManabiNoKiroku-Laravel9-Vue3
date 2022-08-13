<?php

namespace App\Repositories;

interface E_Group_RepositoryInterface
{
  public function create(array $item);
  public function show($id);
  public function update($id, array $item);
  public function delete($id);
}