<?php

namespace App\Services;

use App\Repositories\E_Answer_RepositoryInterface;
use App\Models\Exercise;
use App\Models\Ans_pattern;
use App\Models\E_answer;
use App\Models\E_setting;
use App\Models\E_class;

class Student_Service implements Student_ServiceInterface
{
  private $answer_repository;

  public function __construct(
    E_Answer_RepositoryInterface $answer_repository
  ) {
    $this->answer_repository = $answer_repository;
  }

  public function st_menu($e_classes_id)
  {
    $selected_titles = E_setting::where('e_classes_id', $e_classes_id)->select('no')->getQuery();
    $e_groups_id = E_class::where('id', $e_classes_id)->select('e_groups_id')->getQuery();
    return Exercise::where('q_no', 0)->whereIn('e_groups_id', $e_groups_id)->whereIn('no', $selected_titles)->get();
  }

  public function question_randomize($e_classes_id, $no)
  {
    $e_groups_id = E_class::where('id', $e_classes_id)->select('e_groups_id')->getQuery();
    $questions = Exercise::whereIn('e_groups_id', $e_groups_id)->where('no', $no)->where('q_no', '>', '0')->get();
    foreach($questions as $question){
      $a_ptn = mt_rand(1, 24);
      $a_ptn_w = Ans_pattern::where('id', $a_ptn)->first();
      $a_ptn_list = array();
      $a_ptn_list[0] = $a_ptn_w->ans1;
      $a_ptn_list[1] = $a_ptn_w->ans2;
      $a_ptn_list[2] = $a_ptn_w->ans3;
      $a_ptn_list[3] = $a_ptn_w->ans4;
      $ans_list = array();
      $ans_list[$a_ptn_list[0] - 1] = $question->ans1;
      $ans_list[$a_ptn_list[1] - 1] = $question->ans2;
      $ans_list[$a_ptn_list[2] - 1] = $question->ans3;
      $ans_list[$a_ptn_list[3] - 1] = $question->ans4;
      $question->ans1 = $ans_list[0];
      $question->ans2 = $ans_list[1];
      $question->ans3 = $ans_list[2];
      $question->ans4 = $ans_list[3];
      $question->ans = $a_ptn_list[$question->ans - 1];
      $exp_list = array();
      $exp_list[$a_ptn_list[0] - 1] = $question->exp1;
      $exp_list[$a_ptn_list[1] - 1] = $question->exp2;
      $exp_list[$a_ptn_list[2] - 1] = $question->exp3;
      $exp_list[$a_ptn_list[3] - 1] = $question->exp4;
      $question->exp1 = $exp_list[0];
      $question->exp2 = $exp_list[1];
      $question->exp3 = $exp_list[2];
      $question->exp4 = $exp_list[3];
    }
    return $questions;
  }

  public function answer_create($request)
  {
    $item = $request->only(['user_id', 'e_classes_id', 's_id', 'no', 'q_no', 'ans', 'correct']);
    $this->answer_repository->create($item);
    return  response($item, 201);
  }

  public function answer_list2($user_id, $e_classes_id)
  {
    $answers = E_answer::where('user_id', $user_id)->where('e_classes_id', $e_classes_id)->orderBy('no', 'asc')->orderBy('s_id', 'desc')->orderBy('q_no', 'asc')->get();
    $answer_lists = array(array());
    $i = -1;
    $tmp_s_id = 0;
    foreach($answers as $answer){
      if($tmp_s_id != $answer->s_id){
        $i++;
        $tmp_s_id = $answer->s_id;
        $answer_lists[$i][0] = $answer->s_id;
        $sections = Exercise::where('no', $answer->no)->where('q_no', 0)->first();
        $answer_lists[$i][1] = $sections->quest;
        $answer_lists[$i][2] = $answer->created_at->format('Y年m月d日 H時i分s秒');
        $j = 3;
      }
      $answer_lists[$i][$j] = $answer->correct;
      $j++;
    }
    return $answer_lists;
  }

  public function answer_delete($user_id, $e_classes_id)
  {
    return $this->answer_repository->delete($user_id, $e_classes_id);
  }

}
