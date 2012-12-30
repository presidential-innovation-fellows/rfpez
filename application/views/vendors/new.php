<?php Section::inject('page_title', 'New Company') ?>
<hr />
<form id="new-vendor-form" action="<?php echo e(route('vendors')); ?>" method="POST">
  <?php echo View::make('users.account_vendor_fields')->with('vendor', Input::old('vendor'))->with('user', Input::old('user'))->with('services', Input::old('services'))->with('signup', true); ?>
  <h5>How did you hear about RFP-EZ?</h5>
  <div class="control-group">
    <input class="input-xlarge" type="text" name="user[how_hear]" value="" placeholder="optional" />
  </div>
  <div class="form-actions">
    <button class="btn btn-primary" type="submit">Create Profile</button>
  </div>
</form>