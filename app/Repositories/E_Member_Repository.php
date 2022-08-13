<?php

namespace App\Repositories;

use App\Models\E_Member;

class E_Member_Repository implements E_Member_RepositoryInterface
{
  public function create(array $item)
  {
    return E_Member::create($item);
  }

  public function delete($id)
  {
    return E_Member::where('user_id', $id)->delete();
  }
}
