<?php

namespace App\Repositories;

use App\Models\E_Group;

class E_Group_Repository implements E_Group_RepositoryInterface
{
  public function create(array $item)
  {
    return E_Group::create($item);
  }

  public function show($id)
  {
    return E_Group::findOrFail($id);
  }

  public function update($id, array $item)
  {
    return E_Group::findOrFail($id)->update($item);
  }

  public function delete($id)
  {
    return E_Group::destroy($id);
  }
}
