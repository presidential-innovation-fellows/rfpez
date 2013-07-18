<?php $parsed = $notification->parsed(); ?>

<tbody class="notification-item <?php echo $notification->read ? '' : 'unread'; ?>" data-notification-id="<?php echo $notification->id; ?>">
  <tr>
    <td class="unread-td">
      <a class="btn btn-small btn-primary btn-circle mark-as-read">&nbsp;</a>
      <a class="btn btn-small btn-circle mark-as-unread">&nbsp;</a>
    </td>
    <td>
      <div class="line1"><?php echo $parsed["line1"]; ?></div>
      <div class="line2"><?php echo $parsed["line2"]; ?></div>
      <div class="timestamp"><?php echo Helper::timeago($notification->created_at); ?></div>
    </td>
  </tr>
</tbody>