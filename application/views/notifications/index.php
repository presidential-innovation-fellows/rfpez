<?php Section::inject('page_title', 'Notifications') ?>
<?php if (count($notifications->results) > 0): ?>
  <table class="notifications-table table">
    <?php foreach ($notifications->results as $notification): ?>
      <?php echo Jade\Dumper::_html(View::make('notifications.partials.notification')->with('notification', $notification)); ?>
    <?php endforeach; ?>
  </table>
  <div class="pagination-wrapper">
    <?php echo Jade\Dumper::_html($notifications->links()); ?>
  </div>
<?php else: ?>
  <p><?php echo Jade\Dumper::_text(__("r.notifications.index.no_notifications")); ?></p>
<?php endif; ?>