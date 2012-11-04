<?php if (Request::header('x-pjax')): ?>
  <title><?php echo Jade\Dumper::_text(Helper::full_title(Section::yield('page_title'), Section::yield('page_action'))); ?></title>
<?php endif; ?>
<?php echo Jade\Dumper::_html(Section::yield('page_scripts')); ?>
<input id="current-page" type="hidden" value="<?php echo Jade\Dumper::_text(Section::yield('current_page')); ?>" />
<?php echo Jade\Dumper::_html(View::make('partials.topnav')); ?>
<div class="container">
  <?php if (Auth::guest()): ?>
    <?php echo Jade\Dumper::_html(View::make('partials.signin_modal')); ?>
  <?php endif; ?>
  <?php if (Session::has('errors')): ?>
    <div class="alert alert-error">
      <ul>
        <?php foreach(Session::get('errors') as $error): ?>
          <li><?php echo Jade\Dumper::_text($error); ?></li>
        <?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>
  <?php if (Session::has('notice')): ?>
    <div class="alert alert-success"><?php echo Jade\Dumper::_text(Session::get('notice')); ?></div>
  <?php endif; ?>
  <?php if (!Section::yield('no_page_header')) { ?>
    <h4>
      <?php echo Jade\Dumper::_text(Section::yield('page_title')); ?>
      <?php echo Jade\Dumper::_html(Section::yield('inside_header')); ?>
    </h4>
  <?php } ?>
  <?php echo Jade\Dumper::_html(Section::yield('subnav')); ?>
  <?php echo Jade\Dumper::_html($content); ?>
</div>