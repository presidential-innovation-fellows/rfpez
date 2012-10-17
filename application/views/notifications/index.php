<?php Section::inject('page_title', 'Notifications') ?>
<?php if ($notifications): ?>
  <ul class="notification-list">
    <?php foreach ($notifications as $notification): ?>
      <?php echo Jade\Dumper::_html(View::make('notifications.partials.notification')->with('notification', $notification)); ?>
    <?php endforeach; ?>
  </ul>
<?php else: ?>
  <p>No notifications to show.</p>
<?php endif; ?>