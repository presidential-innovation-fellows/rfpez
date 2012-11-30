<?php if (Request::header('x-pjax')): ?>
  <title><?php echo e(Helper::full_title(Section::yield('page_title'), Section::yield('page_action'))); ?></title>
<?php endif; ?>
<input id="current-page" type="hidden" value="<?php echo e(Section::yield('current_page')); ?>" />
<?php echo View::make('partials.topnav'); ?>
<div class="container">
  <?php if (Auth::guest()): ?>
    <?php echo View::make('partials.signin_modal'); ?>
  <?php endif; ?>
  <?php if (Session::has('errors')): ?>
    <div class="alert alert-error">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <ul>
        <?php foreach(Session::get('errors') as $error): ?>
          <li><?php echo e($error); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
  <?php if (Session::has('notice')): ?>
    <div class="alert alert-success">
      <?php echo e(Session::get('notice')); ?>
      <button type="button" class="close" data-dismiss="alert">×</button>
    </div>
  <?php endif; ?>
  <?php if (!Section::yield('no_page_header')) { ?>
    <h4>
      <?php echo e(Section::yield('page_title')); ?>
      <?php echo Section::yield('inside_header'); ?>
    </h4>
  <?php } ?>
  <?php echo Section::yield('subnav'); ?>
  <?php echo $content; ?>
</div>