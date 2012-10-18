<?php Section::inject('page_title', 'Sign In') ?>
<form class="form-horizontal" action="<?php echo Jade\Dumper::_text( route('signin') ); ?>" method="POST">
  <input type="hidden" name="redirect_to" value="<?php echo Jade\Dumper::_text(Input::old('redirect_to') ?: Session::get('redirect_to')); ?>" />
  <div class="control-group">
    <label class="control-label">Email</label>
    <div class="controls">
      <input type="text" name="email" value="<?php echo Jade\Dumper::_text(Input::old('email')); ?>" data-onload-focus="data-onload-focus" />
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">Password</label>
    <div class="controls">
      <input type="password" name="password" />
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <label class="checkbox">
        <input type="checkbox" checked="checked" name="remember" />
        Remember Me?
      </label>
    </div>
  </div>
  <div class="control-group">
    <label class="control-label">&nbsp;</label>
    <div class="controls">
      <a href="<?php echo Jade\Dumper::_text(route('forgot_password')); ?>" data-pjax="data-pjax">Forgot Password?</a>
    </div>
  </div>
  <div class="form-actions">
    <button class="btn btn-primary" type="submit">Sign In</button>
  </div>
</form>