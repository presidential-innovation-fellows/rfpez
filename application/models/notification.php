<?php

class Notification extends Eloquent {

  public static $timestamps = true;

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

  public static function send($notification_type, $attributes) {
    // @todo More info can be "cached" in the payload for better performance when reading notifications.
    $notification = new Notification(array('notification_type' => $notification_type));

    if ($notification->notification_type == "Dismissal") {
      $bid = $attributes["bid"];
      $notification->fill(array('target_id' => $bid->vendor->user_id,
                                'actor_id' => $bid->contract->officer->user_id,
                                'payload' => array('bid' => $bid->to_array())));

    } elseif ($notification->notification_type == "Undismissal") {
      $bid = $attributes["bid"];
      $notification->fill(array('target_id' => $bid->vendor->user_id,
                                'actor_id' => $bid->contract->officer->user_id,
                                'payload' => array('bid' => $bid->to_array())));

    } elseif ($notification->notification_type == "BidSubmit") {
      $bid = $attributes["bid"];
      $notification->fill(array('target_id' => $bid->contract->officer->user_id,
                                'actor_id' => $bid->vendor->user_id,
                                'payload' => array('bid' => $bid->to_array())));

    } else {
      throw new \Exception("Don't know how to handle that notification type.");
    }

    $notification->save();
  }

}
