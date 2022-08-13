<?php

namespace App\Repositories;

use App\Models\E_Owner;

class E_Owner_Repository implements E_Owner_RepositoryInterface
{
  public function create(array $item)
  {
    return E_Owner::create($item);
  }

  public function delete($id)
  {
    return E_Owner::where('user_id', $id)->delete();
  }
}
