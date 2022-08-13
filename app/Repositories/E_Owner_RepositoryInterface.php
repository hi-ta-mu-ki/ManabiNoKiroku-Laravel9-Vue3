<?php

namespace App\Repositories;

interface E_Owner_RepositoryInterface
{
  public function create(array $item);
  public function delete($id);
}