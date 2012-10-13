<?php $parsed = $notification->parsed; ?>

<li class="notification-item <?= $notification->read ? '' : 'unread' ?>" data-notification-id="<?= $notification->id ?>">
  <div class="unread-button-wrapper">
    <a class="btn btn-small btn-primary btn-circle mark-as-read">&nbsp;</a>
  </div>
  <div class="read-button-wrapper">
    <a class="btn btn-small btn-circle mark-as-unread">&nbsp;</a>
  </div>
  <div class="line1"><?= $parsed["line1"] ?></div>
  <div class="line2"><?= $parsed["line2"] ?></div>
  <div class="timestamp"><?= RelativeTime::format($notification->created_at) ?></div>
</li>