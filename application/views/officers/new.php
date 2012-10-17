<?php Section::inject('page_title', 'New Officer') ?>
<form id="new-officer-form" action="<?php echo Jade\Dumper::_text(route('officers')); ?>" method="post">
  <?php $user = Input::get('user'); ?>
  <?php $officer = Input::get('officer'); ?>
  <?php echo Jade\Dumper::_html(View::make('users.account_officer_fields')->with('officer', Input::old('officer'))->with('user', Input::old('user'))->with('signup', true)); ?>
  <div class="form-actions">
    <button class="btn btn-primary" type="submit">Submit</button>
  </div>
</form>