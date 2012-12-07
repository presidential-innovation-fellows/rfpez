<?php $parsed = $notification->parsed(); ?>

<tbody class="notification-item <?= $notification->read ? '' : 'unread' ?>" data-notification-id="<?= $notification->id ?>">
  <tr>
    <td class="unread-td">
      <a class="btn btn-small btn-primary btn-circle mark-as-read">&nbsp;</a>
      <a class="btn btn-small btn-circle mark-as-unread">&nbsp;</a>
    </td>
    <td>
      <div class="line1"><?= $parsed["line1"] ?></div>
      <div class="line2"><?= $parsed["line2"] ?></div>
      <div class="timestamp"><?= Helper::timeago($notification->created_at) ?></div>
    </td>
  </tr>
</tbody>