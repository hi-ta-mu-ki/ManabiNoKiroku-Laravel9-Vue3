<?php

namespace App\Repositories;

use App\Models\E_Owner;

class E_Owner_Repository implements E_Owner_RepositoryInterface
{
  public function create(array $item)
  {
    return E_Owner::create($item);
  }

  public function delete($e_groups_id, $user_id)
  {
    return E_Owner::where('e_groups_id', $e_groups_id)->where('user_id', $user_id)->delete();
  }
}
