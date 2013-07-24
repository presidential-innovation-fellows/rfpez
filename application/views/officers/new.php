<?php Section::inject('page_title', 'New Officer') ?>
<div class="subheader"></div>
<div class="container inner-container">
  <form id="new-officer-form" action="<?php echo e(route('officers')); ?>" method="post">
    <?php $user = Input::get('user'); ?>
    <?php $officer = Input::get('officer'); ?>

    <?php echo View::make('users.account_officer_fields')->with('officer', Input::old('officer'))->with('user', Input::old('user'))->with('signup', true); ?>

    <div class="control-group">
      <label>How did you hear about RFP-EZ?</label>
      <input class="input-xxlarge" type="text" name="user[how_hear]" value="" />
    </div>

    <div>
      <button class="btn btn-primary" type="submit">Submit</button>
    </div>

  </form>
</div>