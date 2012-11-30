<?php Section::inject('page_title', 'Forgot Password') ?>
<form class="form-horizontal" action="<?php echo e(route('forgot_password')); ?>" method="POST">
  <div class="control-group">
    <label class="control-label">Email address</label>
    <div class="controls">
      <input type="text" name="email" value="<?php echo e(Input::old('email')); ?>" />
    </div>
  </div>
  <div class="form-actions">
    <button class="btn btn-primary" type="submit">Submit</button>
  </div>
</form>