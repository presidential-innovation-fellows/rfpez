<?php Section::inject('page_title', 'My Account') ?>
<form action="<?php echo Jade\Dumper::_text(route('account')); ?>" method="POST" class="account-form account-form-<?php echo Jade\Dumper::_text(Auth::user()->vendor ? 'vendor' : 'officer'); ?>">
  <?php if (Auth::user()->vendor): ?>
    <?php echo Jade\Dumper::_html(View::make('users.account_vendor_fields')); ?>
  <?php else: ?>
    <?php echo Jade\Dumper::_html(View::make('users.account_officer_fields')); ?>
  <?php endif; ?>
  <div class="form-actions">
    <input class="btn btn-primary" type="submit" value="Save Changes" />
  </div>
</form>