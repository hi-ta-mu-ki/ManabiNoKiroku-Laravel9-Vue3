<?php

namespace App\Repositories;

interface E_Answer_RepositoryInterface
{
  public function create(array $item);
  public function delete($id);
}