<?php Section::inject('page_title', 'My Account'); ?>

<h1>My Account</h1>

<form action="<?= route('account') ?>" method="POST">

  <label>Email:</label>
  <?= Auth::user()->email ?> - <a href="#">Change email</a>
  <br /><br />

  <label>Password:</label>
  <a href="#">Change Password</a>

  <br /><br />

  <?php if (Auth::user()->vendor): ?>
    <h1>Vendor Profile</h1>
    <?= View::make('users.account_vendor_fields') ?>
  <?php else: ?>
    <?= View::make('users.account_officer_fields') ?>
  <?php endif; ?>
  <br /><br />
  <input class="btn btn-primary" type="submit" value="Save Changes " />
</form>
