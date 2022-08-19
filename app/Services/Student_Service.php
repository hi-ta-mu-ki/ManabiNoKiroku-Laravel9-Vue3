<?php

namespace App\Services;

use App\Repositories\E_Answer_RepositoryInterface;
use App\Services\Ans_pattern_Query_ServiceInterface;
use App\Services\Answer_Query_ServiceInterface;
use App\Services\Class_Query_ServiceInterface;
use App\Services\Exercise_Query_ServiceInterface;
use App\Services\Setting_Query_ServiceInterface;

class Student_Service implements Student_ServiceInterface
{
  private $answer_repository;
  private $ans_pattern_query_service;
  private $answer_query_service;
  private $class_query_service;
  private $exercise_query_service;
  private $setting_query_service;

  public function __construct(
    E_Answer_RepositoryInterface $answer_repository,
    Ans_pattern_Query_ServiceInterface $ans_pattern_query_service,
    Answer_Query_ServiceInterface $answer_query_service,
    Class_Query_ServiceInterface $class_query_service,
    Exercise_Query_ServiceInterface $exercise_query_service,
    Setting_Query_ServiceInterface $setting_query_service
  ) {
    $this->answer_repository = $answer_repository;
    $this->ans_pattern_query_service = $ans_pattern_query_service;
    $this->answer_query_service = $answer_query_service;
    $this->class_query_service = $class_query_service;
    $this->exercise_query_service = $exercise_query_service;
    $this->setting_query_service = $setting_query_service;
  }

  public function st_menu($e_classes_id)
  {
    $selected_titles = $this->setting_query_service->get_setting_to_title($e_classes_id);
    $e_groups_id =$this->class_query_service->get_class_to_group($e_classes_id);
    return $this->exercise_query_service->get_group_to_menu($e_groups_id, $selected_titles);
  }

  public function question_randomize($e_classes_id, $no)
  {
    $e_groups_id = $this->class_query_service->get_class_to_group($e_classes_id);
    $questions = $this->exercise_query_service->get_group_to_question($e_groups_id, $no);
    foreach($questions as $question){
      $a_ptn = mt_rand(1, 24);
      $a_ptn_w = $this->ans_pattern_query_service->get_ans_ptn($a_ptn);
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

  public function answer_my_list($user_id, $e_classes_id)
  {
    $answers = $this->answer_query_service->get_user_to_answer($user_id, $e_classes_id);
    $answer_lists = array(array());
    $i = -1;
    $tmp_s_id = 0;
    foreach($answers as $answer){
      if($tmp_s_id != $answer->s_id){
        $i++;
        $tmp_s_id = $answer->s_id;
        $answer_lists[$i][0] = $answer->s_id;
        $sections = $this->exercise_query_service->get_answer_to_question($answer);
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
