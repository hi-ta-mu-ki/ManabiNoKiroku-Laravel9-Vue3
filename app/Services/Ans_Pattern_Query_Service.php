<?php

namespace App\Services;

use App\Services\Ans_pattern_Query_ServiceInterface;
use App\Models\Ans_pattern;

class Ans_pattern_Query_Service implements Ans_pattern_Query_ServiceInterface
{
  public function get_ans_ptn($a_ptn)
  {
    return Ans_pattern::where('id', $a_ptn)->first();
  }
}