<?php Section::inject('page_title', 'Change Password') ?>
<form id="change-password-form" class="form-horizontal" action="<?php echo e(route('change_password')); ?>" method="post">
  <div class="control-group">
    <label class="control-label">Old Password</label>
    <div class="controls">
      <input type="password" name="old_password" />
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">New Password</label>
    <div class="controls">
      <input id="new-password-input" type="password" name="new_password" />
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Confirm New Password</label>
    <div class="controls">
      <input type="password" name="confirm_new_password" />
    </div>
  </div>
  <div class="form-actions">
    <button class="btn btn-primary" type="submit">Submit</button>
  </div>
</form>