<?php

namespace App\Repositories;

use App\Models\E_Member;

class E_Member_Repository implements E_Member_RepositoryInterface
{
  public function create(array $item)
  {
    return E_Member::create($item);
  }

  public function delete($e_classes_id, $user_id)
  {
    return E_Member::where('e_classes_id', $e_classes_id)->where('user_id', $user_id)->delete();
  }
}
