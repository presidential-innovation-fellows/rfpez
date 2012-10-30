<?php

class User extends Eloquent {

  public static $timestamps = true;

  public static $hidden = array('reset_password_token', 'reset_password_sent_at', 'updated_at', 'created_at',
                                'invited_by', 'encrypted_password', 'sign_in_count', 'current_sign_in_at',
                                'last_sign_in_at', 'current_sign_in_ip', 'last_sign_in_ip', 'new_email',
                                'new_email_confirm_token');

  public $unread_notifications = false;

  public function _find($id, $columns = array('*'))
  {
    // Uncomment this to switch back to three query version...
    // return parent::_find($id, $columns);

    $columns = array();
    $columns[] = 'users.*';
    $columns = array_merge($columns, $this->get_officer_columns());
    $columns = array_merge($columns, $this->get_vendor_columns());

    $record = DB::table('users')->left_join('vendors', 'users.id', '=', 'vendors.user_id')
                                ->left_join('officers', 'users.id', '=', 'officers.user_id')
                                ->where('users.id', '=', $id)
                                ->select($columns)
                                ->first();

    if (is_null($record)) return;

    $user = new User(array(), true);
    $user->relationships['officer'] = new Officer(array(), true);
    $user->relationships['vendor'] = new Vendor(array(), true);

    // Map the aliased columns back into their proper models...
    foreach ((array) $record as $column => $value)
    {
        if (starts_with($column, 'officer_alias_'))
        {
          $user->relationships['officer']->{str_replace('officer_alias_', '', $column)} = $value;
        }
        elseif (starts_with($column, 'vendor_alias_'))
        {
          $user->relationships['vendor']->{str_replace('vendor_alias_', '', $column)} = $value;
        }
        else
        {
          $user->$column = $value;
        }
    }

    // The "sync" method forces to model to assume a "clean" state and
    // think that no attributes have ever been modified like it was
    // just pulled fresh out of the database...
    if (is_null($user->relationships['officer']->id)) {
      $user->relationships['officer'] = null;
    } else {
      $user->relationships['officer']->sync();
    }
    if (is_null($user->relationships['vendor']->id)) {
      $user->relationships['vendor'] = null;
    } else {
      $user->relationships['vendor']->sync();
    }

    return $user;
  }

  protected static function get_officer_columns()
  {
    $columns = array(
      'id', 'user_id', 'phone', 'fax', 'name', 'title', 'agency', 'verified_at',
      'verified_solnbr', 'created_at', 'updated_at', 'role'
    );

    return array_map(function($v) { return 'officers.'.$v.' as officer_alias_'.$v; }, $columns);
  }

  protected static function get_vendor_columns()
  {
    $columns = array(
      'id', 'user_id', 'company_name', 'contact_name', 'address', 'city', 'state',
      'zip', 'latitude', 'longitude', 'ballpark_price', 'portfolio_url', 'more_info',
      'created_at', 'updated_at', 'homepage_url', 'sourcecode_url', 'image_url', 'duns',
      'sam_entity_name', 'dsbs_user_id', 'dsbs_name',
    );

    return array_map(function($v) { return 'vendors.'.$v.' as vendor_alias_'.$v; }, $columns);
  }

  public $validator = false;

  public function validator($password_required = true, $dotgov_only = false) {
    if ($this->validator) return $this->validator;

    $rules = array();
    $rules['email'] = $this->id ? 'required|email|unique:users,email,'.$this->id : 'required|email|unique:users';
    if ($dotgov_only) $rules['email'] .= '|dotgovonly';
    if ($password_required) $rules["password"] = "required|min:8";

    $validator = Validator::make($this->attributes, $rules);
    $validator->passes(); // hack to populate error messages

    return $this->validator = $validator;
  }

  public function vendor() {
    return $this->has_one('Vendor');
  }

  public function officer() {
    return $this->has_one('Officer');
  }

  public function unread_notifications() {
    if ($this->unread_notifications !== false) return $this->unread_notifications;
    return $this->unread_notifications = $this->notifications_received()->where_read(false)->get();
  }

  public function unread_notification_count() {
    return $this->notifications_received()->where_read(false)->count();
  }

  public function notifications_received() {
    return $this->has_many('Notification', 'target_id');
  }

  public function notifications_sent() {
    return $this->has_many('Notification', 'actor_id');
  }

  public function unread_notification_for_payload($payload_type, $payload_id) {
    foreach ($this->unread_notifications() as $notification) {
      if ($notification->payload_type == $payload_type && $notification->payload_id == $payload_id) return $notification;
    }
    return false;
  }

  public function view_notification_payload($key, $val, $mark_as) {
    $query = $this->notifications_received()->where_payload_type($key);

    if (is_array($val)) {
      if (empty($val)) return;
      $query = $query->where_in('payload_id', $val);
    } else {
      $query = $query->where_payload_id($val);
    }

    $notifications = $query->get();

    if (count($notifications) == 0) return;

    if ($mark_as == "read") {
      foreach($notifications as $notification) $notification->mark_as_read();
    } else {
      foreach($notifications as $notification) $notification->mark_as_unread();
    }
  }

  public function account_type() {
    return $this->vendor ? 'vendor' : 'officer';
  }

  public function track_signin() {
    $this->sign_in_count++;
    $this->current_sign_in_ip = Request::ip();
    $this->current_sign_in_at = new \DateTime;
    if (!$this->last_sign_in_ip) $this->last_sign_in_ip = $this->current_sign_in_ip;
    if (!$this->last_sign_in_at) $this->last_sign_in_at = $this->current_sign_in_at;
    $this->save();
  }

  public function generate_reset_password_token() {
    $this->reset_password_token = Str::random(36);
    $this->reset_password_sent_at = new \DateTime;
    $this->save();
  }

  public function reset_password_to($new_password) {
    $this->password = $new_password;

    if ($this->validator()->passes()) {
      $this->reset_password_token = null;
      $this->reset_password_sent_at = null;
      $this->save();
      return true;
    } else {
      return false;
    }
  }

  public function confirm_new_email() {
    $this->email = $this->new_email;
    $this->new_email = NULL;
    $this->new_email_confirm_token = NULL;
    $this->save();
  }

  public static function new_officer_from_invite($email, $invited_by, $project) {
    if (!preg_match('/\.gov$/', $email)) return false;

    $user = new User(array('email' => $email,
                           'invited_by' => $invited_by->id));

    $officer = new Officer();
    $user->generate_reset_password_token();
    $user->officer()->insert($officer);

    Mailer::send("NewOfficerInvited", array('new_user' => $user,
                                            'invited_by' => $invited_by,
                                            'project' => $project));

    return $user;
  }

}

Event::listen('eloquent.saving: User', function($model) {
  // Hash the password and store it in the encrypted_password column.
  if (isset($model->attributes["password"])) {
    $model->attributes["encrypted_password"] = Hash::make($model->attributes["password"]);
    unset($model->attributes["password"]);
  }
});
