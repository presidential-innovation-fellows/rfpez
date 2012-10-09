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

  public function get_parsed() {
    $return_array = array();

    if ($this->notification_type == "Dismissal") {
      $bid = $this->payload["bid"];
      $return_array["subject"] = "Your bid on ".$bid["contract"]["title"]." has been dismissed.";
      $return_array["line1"] = "Your bid on <a href='".route('bid', array($bid["contract"]["id"], $bid["id"]))."'>".$bid["contract"]["title"].
                "</a> has been dismissed.";
      $return_array["line2"] = "Dismissal reason: \"" . $this->payload["bid"]["dismissal_reason"]."\"";

    } elseif ($this->notification_type == "Undismissal") {
      $bid = $this->payload["bid"];
      $return_array["subject"] = "Your bid on ".$bid["contract"]["title"]." has been un-dismissed.";
      $return_array["line1"] = "Your bid on <a href='".route('bid', array($bid["contract"]["id"], $bid["id"]))."'>".$bid["contract"]["title"].
                "</a> has been un-dismissed.";
      $return_array["line2"] = "Congrats, you're back in the running!";

    } elseif ($this->notification_type == "BidSubmit") {
      $bid = $this->payload["bid"];
      $return_array["subject"] = $bid["vendor"]["company_name"]." has submitted a bid for ".$bid["contract"]["title"].".";
      $return_array["line1"] = $bid["vendor"]["company_name"]." has <a href='".route('bid', array($bid["contract"]["id"], $bid["id"])).
                "'>submitted a bid</a> for ".$bid["contract"]["title"].".";
      $return_array["line2"] = Helper::truncate($bid["approach"], 20);
    }

    return $return_array;
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
    $notification->send_email();
  }

  public function send_email() {
    $transport = Config::get('mailer.transport');
    $mailer = Swift_Mailer::newInstance($transport);
    $message = Swift_Message::newInstance();
    $message->setFrom(array('noreply@sba.gov'=>'EasyBid'));

    $message->setSubject($this->parsed["subject"])
            ->setTo($this->target->email)
            ->addPart(View::make('mailer.notification_text')->with('notification', $this), 'text/plain')
            ->setBody(View::make('mailer.notification_html')->with('notification', $this), 'text/html');

    // If mailer.send_all_to is set in the config files, ignore the original
    // recipient and instead, send to the email address specified.
    if (Config::has('mailer.send_all_to')) {
      $message->setSubject("(".$message->getHeaders()->get('To').") ".$message->getSubject());
      $message->setTo(Config::get('mailer.send_all_to'));
    }

    $mailer->send($message);
  }

}