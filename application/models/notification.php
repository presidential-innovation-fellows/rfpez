<?php

class Notification extends Eloquent {

  public static $timestamps = true;

  public $includes_in_array = array('parsed');

  public function target() {
    return $this->belongs_to('User', 'target_id');
  }

  public function actor() {
    return $this->belongs_to('User', 'actor_id');
  }

  public function get_payload() {
    return json_decode($this->attributes['payload'], true);
  }

  public function set_payload($value) {
    $this->attributes['payload'] = json_encode($value);
  }

  public function mark_as_read() {
    $this->read = true;
    $this->save();
  }

  public function mark_as_unread() {
    $this->read = false;
    $this->save();
  }

  public function parsed() {
    return NotificationParser::parse($this);
  }

  public static function send($notification_type, $attributes, $send_email = true) {
    $notification = new Notification(array('notification_type' => $notification_type));

    if ($notification->notification_type == "Dismissal") {
      $bid = $attributes["bid"];
      $notification->fill(array('target_id' => $bid->vendor->user_id,
                                'actor_id' => $attributes["actor_id"],
                                'payload' => array('bid' => $bid->to_array()),
                                'payload_type' => 'bid',
                                'payload_id' => $bid->id));

    } elseif ($notification->notification_type == "Undismissal") {
      $bid = $attributes["bid"];
      $notification->fill(array('target_id' => $bid->vendor->user_id,
                                'actor_id' => $attributes["actor_id"],
                                'payload' => array('bid' => $bid->to_array()),
                                'payload_type' => 'bid',
                                'payload_id' => $bid->id));

    } elseif ($notification->notification_type == "Award") {
      $bid = $attributes["bid"];
      $notification->fill(array('target_id' => $bid->vendor->user_id,
                                'actor_id' => $attributes["actor_id"],
                                'payload' => array('bid' => $bid->to_array()),
                                'payload_type' => 'bid',
                                'payload_id' => $bid->id));

    } elseif ($notification->notification_type == "BidSubmit") {
      $bid = $attributes["bid"];
      $notification->fill(array('target_id' => $attributes["target_id"],
                                'actor_id' => $bid->vendor->user_id,
                                'payload' => array('bid' => $bid->to_array()),
                                'payload_type' => 'bid',
                                'payload_id' => $bid->id));

    } elseif ($notification->notification_type == "ProjectCollaboratorAdded") {
      $project = $attributes["project"];
      $officer = $attributes["officer"];
      $notification->fill(array('target_id' => $officer->user_id,
                                'actor_id' => $attributes["actor_id"],
                                'payload' => array('project' => $project->to_array(), 'officer' => $officer->to_array()),
                                'payload_type' => 'project',
                                'payload_id' => $project->id));

    } elseif ($notification->notification_type == "Comment") {
      $comment = $attributes["comment"];
      $notification->fill(array('target_id' => $attributes["target_id"],
                                'actor_id' => $comment->officer->user->id,
                                'payload' => array('comment' => $comment->to_array()),
                                'payload_type' => 'comment',
                                'payload_id' => $comment->id));

    } else {
      throw new \Exception("Don't know how to handle that notification type.");
    }

    $notification->save();
    if ($send_email) Mailer::send("Notification", array('notification' => $notification));
  }

}