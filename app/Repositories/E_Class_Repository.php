<?php

namespace App\Repositories;

use App\Models\E_Class;

class E_Class_Repository implements E_Class_RepositoryInterface
{
  public function create(array $item)
  {
    return E_Class::create($item);
  }

  public function show($id)
  {
    return E_Class::findOrFail($id);
  }

  public function update($id, array $item)
  {
    return E_Class::findOrFail($id)->update($item);
  }

  public function delete($id)
  {
    return E_Class::destroy($id);
  }
}
