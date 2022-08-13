<?php

namespace App\Repositories;

interface E_Setting_RepositoryInterface
{
  public function create(array $item);
  public function delete($e_classes_id, $section_title_no);
}