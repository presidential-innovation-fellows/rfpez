<?php Section::inject('page_title', 'Change Email') ?>
<form class="form-horizontal" action="<?php echo e(route('change_email')); ?>" method="post">
  <div class="control-group">
    <label class="control-label">New Email</label>
    <div class="controls">
      <input type="text" name="new_email" value="<?php echo e(Input::old('new_email')); ?>" />
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Verify Password</label>
    <div class="controls">
      <input type="password" name="password" />
    </div>
  </div>
  <div class="form-actions">
    <button class="btn btn-primary" type="submit">Submit</button>
  </div>
</form>