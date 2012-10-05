<?php

if ($notification->notification_type == "Dismissal") {
  $bid = $notification->payload["bid"];
  $line1 = "Your bid on <a href='".route('contract', array($bid["contract_id"]))."'>".$bid["contract"]["title"].
            "</a> has been dismissed.";
  $line2 = "Dismissal reason: \"" . $notification->payload["bid"]["dismissal_reason"]."\"";

} elseif ($notification->notification_type == "Undismissal") {
  $bid = $notification->payload["bid"];
  $line1 = "Your bid on <a href='".route('contract', array($bid["contract_id"]))."'>".$bid["contract"]["title"].
            "</a> has been un-dismissed.";
  $line2 = "Congrats, you're back in the running!";

} elseif ($notification->notification_type == "BidSubmit") {
  $bid = $notification->payload["bid"];
  $line1 = $bid["vendor"]["company_name"]." has <a href='".route('bid', array($bid["contract"]["id"], $bid["id"])).
            "'>submitted a bid</a> for ".$bid["contract"]["title"].".";
  $line2 = Helper::truncate($bid["approach"], 20);

}

if (!isset($line2)) $line2 = "";

?>

<li class="notification-item <?= $notification->read ? '' : 'unread' ?>" data-notification-id="<?= $notification->id ?>">
  <div class="unread-button-wrapper">
    <a class="btn btn-small btn-primary btn-circle mark-as-read">&nbsp;</a>
  </div>
  <div class="read-button-wrapper">
    <a class="btn btn-small btn-circle mark-as-unread">&nbsp;</a>
  </div>
  <div class="line1"><?= $line1 ?></div>
  <div class="line2"><?= $line2 ?></div>
  <div class="timestamp"><?= RelativeTime::format($notification->created_at) ?></div>
</li>