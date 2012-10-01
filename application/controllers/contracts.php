<?php

class Contracts_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'officer_only')->only(array('new', 'create', 'edit', 'update', 'mine'));
    $this->filter('before', 'correct_officer')->only(array('edit', 'update'));
    $this->filter('before', 'contract_exists')->only(array('show'));
  }

  public function action_index() {
    $view = View::make('contracts.index');
    $view->contracts = Contract::all();
    $this->layout->content = $view;
  }

  public function action_show() {
    $view = View::make('contracts.show');
    $view->contract = Config::get('contract');
    $this->layout->content = $view;
  }

  public function action_new() {
    $view = View::make('contracts.new');
    $this->layout->content = $view;
  }

  public function action_mine() {
    $view = View::make('contracts.mine');
    $view->contracts = Contract::where_officer_id(Auth::user()->officer->id)->get();
    $this->layout->content = $view;
  }

  public function action_create() {
    $solnbr = Input::get('solnbr');

    if (!preg_match('/^[0-9A-Za-z\-\_\s]+$/', $solnbr)) {
      Session::flash('errors', array('Invalid Sol Nbr.'));
      return $this->action_new();
    }

    $context = stream_context_create(array('http'=>array('timeout' => 20)));
    $contents = @file_get_contents('http://rfpez-apis.presidentialinnovationfellows.org/opportunities/fbozombie/'
                                              . $solnbr, false, $context);
    if ($contents === false) {
      Session::flash('errors', array("FBO timed out."));
      return $this->try_fbo_api($solnbr);
    }
    $response = json_decode($contents, true);

    if ($contract_id = $this->trySavingContract($response)) {
      return Redirect::to_route('edit_contract', array($contract_id));
    } else {
      return $this->try_fbo_api($solnbr);
    }
  }

  public function try_fbo_api($solnbr) {
    $contents = file_get_contents('http://rfpez-apis.presidentialinnovationfellows.org/opportunities?SOLNBR='.$solnbr);
    if ($contents === false) {
      Session::flash('errors', array("FBO timed out and FBO API timed out."));
      return $this->action_new();
    }
    $json = json_decode($contents, true);

    if (count($json["results"]) === 0) {
      Session::flash('errors', array("Couldn't find contract on FBO or FBO API."));
      return $this->action_new();
    }

    $result = $json["results"][0];

    if (preg_match('/[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}/i', $result["CONTACT"], $matches)) {
       $email = $matches[0];
    } else {
      $email = "";
    }



    if ($contract_id = $this->trySavingContract(array('solnbr' => $result["SOLNBR"],
                                                      'email' => $email,
                                                      'agency' => @$result["AGENCY"],
                                                      'office' => @$result["OFFICE"],
                                                      'title' => $result["SUBJECT"],
                                                      'statement_of_work' => $result["DESC"],
                                                      'set_aside' => "",
                                                      'classification_code' => @$result["CLASSCOD"],
                                                      'naics' => @$result["NAICS"],
                                                      'response_date' => @$result["RESPDATE"],
                                                      'posted_date' => @$result["DATE"]))) {
      return Redirect::to_route('edit_contract', array($contract_id));
    } else {
      return $this->action_new();
    }

  }

  public function action_edit() {
    $view = View::make('contracts.edit');
    $view->contract = Config::get('contract');
    $this->layout->content = $view;
  }

  public function action_update() {
    $contract = Config::get('contract');
    $contract->fill(Input::get('contract'));
    $contract->save();
    return Redirect::to_route('edit_contract', array($contract->id));
  }

  public function trySavingContract($attributes) {
    if (!isset($attributes["solnbr"])) {
      Session::flash('errors', array("Couldn't find that contract on FBO."));
      return false;
    } else if (Contract::where_fbo_solnbr($attributes["solnbr"])->first()) {
      Session::flash('errors', array("That contract already exists in the system."));
      return false;
    } else if (!preg_match('/'.preg_quote(Auth::user()->email).'/i', implode($attributes))) {
      Session::flash('errors', array("Couldn't verify email address."));
      return false;
    }

    if (!Auth::user()->officer->is_verified()) {
      Auth::user()->officer->verify_with_solnbr($attributes["solnbr"]);
    }

    $contract = new Contract();
    $contract->fbo_solnbr = $attributes["solnbr"];
    $contract->officer_id = Auth::user()->officer->id;
    $contract->agency = $attributes["agency"];
    $contract->office = $attributes["office"];
    $contract->title = $attributes["title"];
    $contract->statement_of_work = $attributes["statement_of_work"];
    $contract->set_aside = $attributes["set_aside"];
    $contract->classification_code = $attributes["classification_code"];
    $contract->naics_code = $attributes["naics"];

    if ($due_at = strtotime($attributes["response_date"])) {
      $contract->proposals_due_at = date_timestamp_set(new \DateTime(), $due_at);
    }

    if ($posted_at = strtotime($attributes["posted_date"])) {
      $contract->posted_at = date_timestamp_set(new \DateTime(), $posted_at);
    }

    $contract->save();

    Session::flash('errors', array());
    return $contract->id;
  }

}

Route::filter('contract_exists', function() {
  $id = Request::$route->parameters[0];
  $contract = Contract::find($id);
  if (!$contract) return Redirect::to('/');
  Config::set('contract', $contract);
});

Route::filter('correct_officer', function() {
  $id = Request::$route->parameters[0];
  $contract = Contract::find($id);
  if (!$contract || $contract->officer->id != Auth::user()->officer->id) return Redirect::to('/');
  Config::set('contract', $contract);
});
