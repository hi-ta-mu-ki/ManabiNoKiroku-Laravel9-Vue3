<?php

namespace App\Repositories;

use App\Models\E_Answer;

class E_Answer_Repository implements E_Answer_RepositoryInterface
{
  public function create(array $item)
  {
    return E_Answer::create($item);
  }

  public function delete($id)
  {
    return E_answer::where('user_id', $id)->delete();
  }
}
