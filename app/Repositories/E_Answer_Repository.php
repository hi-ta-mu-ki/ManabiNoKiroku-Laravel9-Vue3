<?php

namespace App\Repositories;

use App\Models\E_Answer;

class E_Answer_Repository implements E_Answer_RepositoryInterface
{
  public function create(array $item)
  {
    return E_Answer::create($item);
  }

  public function delete($user_id, $e_classes_id)
  {
    return E_answer::where('user_id', $user_id)->where('e_classes_id', $e_classes_id)->delete();
  }
}
