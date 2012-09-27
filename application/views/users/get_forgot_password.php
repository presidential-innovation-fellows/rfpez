<?php Section::inject('page_title', 'Forgot Password'); ?>

<h3>Forgot Password</h3>

<form action="<?= route('forgot_password') ?>" method="POST">

  <label>Email address</label>
  <input type="text" name="email" />

  <input type="submit" />

</form>
