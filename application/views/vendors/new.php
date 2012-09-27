<?php Section::inject('page_title', 'New Vendor'); ?>

<h1> Company Profile </h1>

<form action="<?= route('vendors') ?>" method="POST">

  <?=
    View::make('users.account_vendor_fields')
        ->with('vendor', Input::get('vendor'))
        ->with('user', Input::get('user'))
        ->with('services', Input::get('services'))
        ->with('signup', true)
  ?>

<hr />
<input class="btn btn-primary" type="submit" value="Save Profile" />
</form>
