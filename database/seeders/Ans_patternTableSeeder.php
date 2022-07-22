<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Ans_pattern;

class Ans_patternTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      for ($i = 1; $i <= 4; $i++) {
        for ($j = 1; $j <= 4; $j++) {
          for ($k = 1; $k <= 4; $k++) {
            for ($l = 1; $l <= 4; $l++) {
              if ($i != $j && $i != $k && $i != $l && $j != $k && $j != $l && $k != $l){
                Ans_pattern::create([
                  'ans1' => $i,
                  'ans2' => $j,
                  'ans3' => $k,
                  'ans4' => $l
                ]);
            }
            }
          }
        }
      }
    }
}
