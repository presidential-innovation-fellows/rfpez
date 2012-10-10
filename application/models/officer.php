<?php

class Officer extends Eloquent {

  public static $timestamps = true;

  public static $accessible = array('user_id', 'phone', 'fax', 'name', 'title', 'agency');

  public $validator = false;

  public function validator() {
    if ($this->validator) return $this->validator;

    $rules = array('phone' => 'required',
                   'fax' => 'required',
                   'name' => 'required',
                   'title' => 'required',
                   'agency' => 'required');

    $validator = Validator::make($this->attributes, $rules);
    $validator->passes(); // hack to populate error messages

    return $this->validator = $validator;
  }

  public function contracts() {
    return $this->has_many('Contract');
  }

  public function user() {
    return $this->belongs_to('User');
  }

  public function my_contracts_including_collaborating_on() {
    $my_collaborating_ids = ContractCollaborator::where_officer_id($this->id)->lists('contract_id');
    $query = Contract::where_officer_id(Auth::user()->officer->id);
    if ($my_collaborating_ids) $query = $query->or_where_in('id', $my_collaborating_ids);
    return $query;
  }

  public function collaborates_on($contract_id) {
    return in_array($contract_id, $this->contracts_collaborating_on()->lists('id'));
  }

  public function collaborates_on_sow($sow_id) {
    return in_array($sow_id, $this->sows_collaborating_on()->lists('id'));
  }

  public function sows_collaborating_on() {
    return $this->has_many_and_belongs_to('Sow', 'sow_collaborators');
  }

  public function contracts_collaborating_on() {
    return $this->has_many_and_belongs_to('Contract', 'contract_collaborators');
  }

  public function is_verified() {
    return $this->verified_solnbr ? true : false;
  }

  public function verify_with_solnbr($solnbr) {
    $this->verified_solnbr = $solnbr;
    $this->verified_at = new \DateTime;
    $this->save();
  }

}
