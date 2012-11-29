<?php Section::inject('page_title', 'New Officer') ?>
<form id="new-officer-form" action="<?php echo route('officers'); ?>" method="post">
  <?php $user = Input::get('user'); ?>
  <?php $officer = Input::get('officer'); ?>
  <?php echo View::make('users.account_officer_fields')->with('officer', Input::old('officer'))->with('user', Input::old('user'))->with('signup', true); ?>
  <div class="form-actions">
    <div class="control-group form-inline">
      <label>How did you hear about RFP-EZ?</label>
      <input class="input-xlarge" type="text" name="user[how_hear]" value="" />
    </div>
    <button class="btn btn-primary" type="submit">Submit</button>
  </div>
</form>