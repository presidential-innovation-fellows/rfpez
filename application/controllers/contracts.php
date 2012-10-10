<?php

class Contracts_Controller extends Base_Controller {

  public function __construct() {
    parent::__construct();

    $this->filter('before', 'officer_only')->except(array('index', 'show'));

    $this->filter('before', 'contract_exists')->only(array('edit', 'update', 'admin', 'add_collaborator',
                                                           'destroy_collaborator', 'show'));

    $this->filter('before', 'owner_only')->only(array('edit', 'update', 'destroy_collaborator'));

    $this->filter('before', 'owner_or_collaborator')->only(array('admin', 'add_collaborator'));

    $this->filter('before', 'collaborator_exists')->only(array('destroy_collaborator'));
  }

  public function action_index() {
    $view = View::make('contracts.index');
    $view->contracts = Contract::open_contracts()->get();
    $this->layout->content = $view;
  }

  public function action_show() {
    $view = View::make('contracts.show');
    $view->contract = Config::get('contract');
    $this->layout->content = $view;
    if (Auth::user()) Auth::user()->view_notification_payload('contract', $view->contract->id);
  }

  public function action_admin() {
    $view = View::make('contracts.admin');
    $view->contract = Config::get('contract');
    $this->layout->content = $view;
  }

  public function action_add_collaborator() {
    $contract = Config::get('contract');
    $user = User::where_email(Input::get('email'))->first();
    if (!$user) return Response::json(array("status" => "error"));
    if ($user->id === Auth::user()->id) return Response::json(array("status" => "can't add yourself"));
    if ($user->officer->collaborates_on($contract->id)) return Response::json(array("status" => "already exists"));

    $contract->collaborators()->attach($user->officer->id);
    Notification::send("ContractCollaboratorAdded", array("contract" => $contract,
                                              "officer" => $user->officer));
    return Response::json(array("status" => "success",
                                "html" => View::make("partials.media.contract_collaborator_tr")
                                              ->with('officer', $user->officer)
                                              ->with('contract', $contract)
                                              ->render() ));
  }

  public function action_destroy_collaborator() {
    $collaborator = Config::get('collaborator');
    $collaborator->delete();
    return Response::json(array("status" => "success"));
  }

  public function action_new() {
    $view = View::make('contracts.new');
    $this->layout->content = $view;
  }

  public function action_mine() {
    $view = View::make('contracts.mine');
    $view->contracts = Auth::user()->officer->my_contracts_including_collaborating_on()->get();
    $this->layout->content = $view;
  }

  public function action_create() {
    $solnbr = Input::get('solnbr');

    if (!preg_match('/^[0-9A-Za-z\-\_\s]+$/', $solnbr)) {
      Session::flash('errors', array('Invalid Sol Nbr.'));
      return Redirect::to_route('new_contracts')->with_input();
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
      return Redirect::to_route('new_contracts')->with_input();
    }
    $json = json_decode($contents, true);

    if (count($json["results"]) === 0) {
      Session::flash('errors', array("Couldn't find contract on FBO or FBO API."));
      return Redirect::to_route('new_contracts')->with_input();
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
      return Redirect::to_route('new_contracts')->with_input();
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
    return Redirect::to('/contracts/' . $contract->id);
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

Route::filter('collaborator_exists', function() {
  $contract_id = Request::$route->parameters[0];
  $officer_id = Request::$route->parameters[1];
  $collaborator = ContractCollaborator::where_contract_id($contract_id)
                                      ->where_officer_id($officer_id)
                                      ->first();
  if (!$collaborator) return Redirect::to('/');
  Config::set('collaborator', $collaborator);
});

Route::filter('owner_only', function() {
  $id = Request::$route->parameters[0];
  $contract = Contract::find($id);
  if ($contract->officer->id != Auth::user()->officer->id) return Redirect::to('/');
  Config::set('contract', $contract);
});

Route::filter('owner_or_collaborator', function() {
  $id = Request::$route->parameters[0];
  $contract = Contract::find($id);
  if ($contract->officer->id != Auth::user()->officer->id && !Auth::user()->officer->collaborates_on($contract->id))
    return Redirect::to('/');
  Config::set('contract', $contract);
});
