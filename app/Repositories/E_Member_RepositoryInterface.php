<?php

namespace App\Repositories;

interface E_Member_RepositoryInterface
{
  public function create(array $item);
  public function delete($e_classes_id, $user_id);
}