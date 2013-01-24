<div class="navbar navbar-static-top">
  <div class="navbar-inner">
    <div class="container">
      <a class="btn btn-navbar" type="button" data-toggle="collapse" data-target=".nav-collapse">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </a>
      <a class="brand" href="<?php echo e(route('root')); ?>" data-no-turbolink="data-no-turbolink">RFP-EZ</a>
      <div class="nav-collapse collapse">
        <?php if (Auth::check()): ?>
          <ul class="nav">
            <?php if (Auth::user()->officer): ?>
              <li>
                <a href="<?php echo e( route('vendors') ); ?>">Vendors</a>
              </li>
              <li>
                <a href="<?php echo e( route('my_projects') ); ?>">Projects</a>
              </li>
              <?php if (Auth::officer()->is_role_or_higher(Officer::ROLE_ADMIN)): ?>
                <li>
                  <a href="<?php echo e(route('reports')); ?>">Reports</a>
                </li>
                <li>
                  <a href="<?php echo e(route('admin_home')); ?>">Admin</a>
                </li>
              <?php endif; ?>
              <?php if (Auth::officer()->is_role_or_higher(Officer::ROLE_SUPER_ADMIN)): ?>
                <li>
                  <a href="/superadmin">Super Admin</a>
                </li>
              <?php endif; ?>
            <?php else: ?>
              <li>
                <a href="<?php echo e( route('my_bids') ); ?>">Bids</a>
              </li>
              <li>
                <a href="<?php echo e( route('projects') ); ?>">Projects</a>
              </li>
            <?php endif; ?>
          </ul>
          <ul class="nav pull-right">
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <?php echo e(Auth::user()->email); ?>
                <b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li>
                  <a href="<?php echo e(route('account')); ?>">Account Settings</a>
                </li>
                <li>
                  <a href="<?php echo e( route('signout') ); ?>" data-no-turbolink="data-no-turbolink">Sign Out</a>
                </li>
              </ul>
            </li>
            <li class="hidden-desktop">
              <a href="<?php echo e(route('notifications')); ?>">
                <i class="icon-white icon-envelope"></i>
                Notifications (<?php echo e(Auth::user()->unread_notification_count()); ?> Unread)
              </a>
            </li>
            <li class="dropdown notification-nav-item visible-desktop">
              <a id="notifications-dropdown-trigger" class="dropdown-toggle" data-toggle="dropdown" href="#">
                &nbsp;
                <i class="icon-white icon-envelope"></i>
                <?php $count = Auth::user()->unread_notification_count() ?>
                &nbsp;
                <span class="badge badge-inverse unread-notification-badge <?php echo e($count == 0 ? 'hide' : ''); ?>"><?php echo e($count); ?></span>
              </a>
              <ul id="notifications-dropdown" class="dropdown-menu loading">
                <li class="no-notifications"><?php echo e(__("r.partials.topnav.no_notifications")); ?></li>
              </ul>
            </li>
          </ul>
        <?php else: ?>
          <ul class="nav">
            <li>
              <a href="<?php echo e( route('projects') ); ?>">Browse Projects</a>
            </li>
          </ul>
          <ul class="nav pull-right">
            <li>
              <a href="#signinModal" data-toggle="modal">Sign In</a>
            </li>
          </ul>
        <?php endif; ?>
      </div>
    </div>
  </div>
</div>