<?php

class Project extends Eloquent {

  const STATUS_WRITING_SOW = 1;
  const STATUS_ACCEPTING_BIDS = 2;
  const STATUS_REVIEWING_BIDS = 3;
  const STATUS_CONTRACT_AWARDED = 4;

  public static $timestamps = true;

  public static $my_project_ids = false;

  public $winning_bid = false;

  public function officers() {
    return $this->has_many_and_belongs_to('Officer', 'project_collaborators')->order_by('owner', 'desc');
  }

  public function comments() {
    return $this->has_many('Comment')->order_by('created_at');
  }

  public function project_type() {
    return $this->belongs_to('ProjectType');
  }

  public function forked_from_project() {
    return $this->belongs_to('Project', 'forked_from_project_id');
  }

  public function owner() {
    return $this->officers()->where_owner(true)->first();
  }

  public function i_am_owner() {
    return (Auth::officer() && Auth::officer()->id == $this->owner()->id) ? true : false;
  }

  public function bids() {
    return $this->has_many('Bid');
  }

  public function winning_bid() {
    if ($this->winning_bid !== false) return $this->winning_bid;
    return $this->winning_bid = $this->bids()->where_not_null('awarded_at')->first();
  }

  public function questions() {
    return $this->has_many('Question')->order_by('created_at', 'desc');
  }

  public function is_mine() {
    if (!Auth::user() || !Auth::user()->officer) return false;
    if (self::$my_project_ids === false)
      self::$my_project_ids = ProjectCollaborator::where_officer_id(Auth::officer()->id)
                                                 ->lists('project_id');

    if (in_array($this->id, self::$my_project_ids))
      return true;

    return false;
  }

  public function my_bid() {
    if (!Auth::user() || !Auth::user()->vendor) return false;

    if ($bid = Auth::user()->vendor->bids()
                           ->where_project_id($this->id)
                           ->where_deleted_by_vendor(false)
                           ->first()) {
      return $bid;
    }

    return false;
  }

  public function status() {
    if (!$this->fbo_solnbr) {
      return self::STATUS_WRITING_SOW;
    } elseif (strtotime($this->proposals_due_at) > time()) {
      return self::STATUS_ACCEPTING_BIDS;
    } elseif (!$this->winning_bid()) {
      return self::STATUS_REVIEWING_BIDS;
    } else {
      return self::STATUS_CONTRACT_AWARDED;
    }
  }

  public function is_open_for_bids() {
    return $this->status() == self::STATUS_ACCEPTING_BIDS;
  }

  public function status_text() {
    return self::status_to_text($this->status());
  }

  public static function status_to_text($status) {
    switch ($status) {
      case self::STATUS_WRITING_SOW:
        return "Writing SOW";
      case self::STATUS_ACCEPTING_BIDS:
        return "Accepting bids";
      case self::STATUS_REVIEWING_BIDS:
        return "Reviewing bids";
      case self::STATUS_CONTRACT_AWARDED:
        return "Contract Awarded";
    }
  }

  public function current_bid_from($vendor) {
    $bid = Bid::where('project_id', '=', $this->id)
              ->where('vendor_id', '=', $vendor->id)
              ->where('deleted_by_vendor', '!=', true)
              ->where_not_null('submitted_at')
              ->first();

    return $bid ? $bid : false;
  }

  public function current_bid_draft_from($vendor) {
    $bid = Bid::where('project_id', '=', $this->id)
              ->where('vendor_id', '=', $vendor->id)
              ->where('deleted_by_vendor', '!=', true)
              ->where_null('submitted_at')
              ->first();

    return $bid ? $bid : false;
  }

  public function my_current_bid() {
    if (!Auth::user() || !Auth::user()->vendor) return false;
    return $this->current_bid_from(Auth::user()->vendor);
  }

  public function my_current_bid_draft() {
    if (!Auth::user() || !Auth::user()->vendor) return false;
    return $this->current_bid_draft_from(Auth::user()->vendor);
  }

  public function submitted_bids() {
    return $this->bids()
                ->where_deleted_by_vendor(false)
                ->where_not_null('submitted_at');
  }

  public function open_bids() {
    return $this->submitted_bids()
                ->where_null('dismissal_reason')
                ->where_null('awarded_at');
  }

  public function dismissed_bids() {
    return $this->submitted_bids()
                ->where_not_null('dismissal_reason');
  }

  public function available_templates() {
    return Project::where_project_type_id($this->project_type_id)
                  ->where('id', '!=', $this->id)
                  ->where_public(true)
                  ->order_by('fork_count', 'desc');
  }

  public function fork_from($template) {
    $this->forked_from_project_id = $template->id;
    $this->background = $template->background;
    $this->sections = $template->sections;
    $this->deliverables = $template->deliverables;
    $this->save();
  }

  //////////// GETTERS AND SETTERS FOR SERIALIZED FIELDS ////////////

  public function get_sections() {
    return json_decode($this->get_attribute('sections'), true);
  }

  public function set_sections($sections) {
    if (is_array($sections)) $sections = json_encode($sections);
    $this->set_attribute('sections', $sections);
  }

  public function get_variables() {
    return json_decode($this->get_attribute('variables'), true);
  }

  public function set_variables($vars) {
    if (is_array($vars)) $vars = json_encode($vars);
    $this->set_attribute('variables', $vars);
  }

  public function get_deliverables() {
    return json_decode($this->get_attribute('deliverables'), true);
  }

  public function set_deliverables($deliverables) {
    if (is_array($deliverables)) $deliverables = json_encode($deliverables);
    $this->set_attribute('deliverables', $deliverables);
  }

  //////////// STATIC FUNCTIONS ////////////

  public static function open_projects() {
    return self::with(array('sow', 'sow.sow_sections'))->where('proposals_due_at', '>', \DB::raw('NOW()'));
  }

}
