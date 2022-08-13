<?php

namespace App\Repositories;

interface E_Member_RepositoryInterface
{
  public function create(array $item);
  public function delete($id);
}