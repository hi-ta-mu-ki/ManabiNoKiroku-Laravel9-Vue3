<?php

namespace App\Repositories;

use App\Models\Exercise;

class Exercise_Repository implements Exercise_RepositoryInterface
{
  public function all()
  {
    return Exercise::all();
  }

  public function create(array $item)
  {
    return Exercise::create($item);
  }

  public function show($id)
  {
    return Exercise::findOrFail($id);
  }

  public function update($id, array $item)
  {
    return Exercise::findOrFail($id)->update($item);
  }

  public function delete($id)
  {
    return Exercise::destroy($id);
  }
}
