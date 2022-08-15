<?php

namespace App\Repositories;

interface E_Answer_RepositoryInterface
{
  public function create(array $item);
  public function delete($user_id, $e_classes_id);
}