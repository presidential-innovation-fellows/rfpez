<div id="signinModal" class="modal hide" tabindex="-1" role="dialog">
  <div class="modal-header">
    <button class="close" type="button" data-dismiss="modal">&times;</button>
    <h3>Login to EasyBid</h3>
  </div>
  <div class="modal-body">
    <form action="<?php echo Jade\Dumper::_text(route('signin')); ?>" method="POST">
      <input type="hidden" name="modal" value="true" />
      <div class="control-group">
        <label class="control-label">Email</label>
        <div class="controls">
          <input id="email" class="span3" type="email" name="email" />
        </div>
      </div>
      <div class="control-group">
        <label class="control-label">Password</label>
        <div class="controls">
          <input class="span3" type="password" name="password" />
          <a class="forgot" href="<?php echo Jade\Dumper::_text(route('forgot_password')); ?>">Forgot Password?</a>
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
      <p>
        <button class="btn btn-primary" type="submit">Sign in</button>
      </p>
    </form>
  </div>
  <div class="modal-footer">
    Are you new? Register as
    <a href="/vendors/new">a business
</a>
    or
    <a href="/officers/new">a government officer</a>
  </div>
</div>