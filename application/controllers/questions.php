<?php

class Questions_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'vendor_only')->only(array('create'));
  }

  public function action_create() {
    $question = new Question(array('contract_id' => Input::get('contract_id'),
                                   'question' => Input::get('question')));
    $question->vendor_id = Auth::user()->vendor->id;

    if ($question->validator()->passes()) {
      $question->save();
      return Response::json(array("status" => "success", "question" => $question->to_array()));
    } else {
      return Response::json(array("status" => "error", "errors" => $question->validator()->errors->all()));
    }
  }

}
