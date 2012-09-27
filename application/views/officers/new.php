<?php Section::inject('page_title', 'New Officer'); ?>

<h3>New Officer</h3>

<form action="<?= route('officers') ?>" method="POST">

  <?php $user = Input::get('user'); ?>
  <?php $officer = Input::get('officer'); ?>

  <?=
    View::make('users.account_officer_fields')
        ->with('officer', Input::get('officer'))
        ->with('user', Input::get('user'))
        ->with('signup', true)
  ?>

  <br /><br />

  <input type="submit" />

</form>
