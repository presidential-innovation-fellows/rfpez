<?php Section::start('content') ?>

<?php if ($finish_signup): ?>
  <h3>Create password for <?= $user->email ?></h3>
<?php else: ?>
  <h3>Reset password for <?= $user->email ?></h3>
<?php endif; ?>

<form action="<?= route('reset_password', array($user->reset_password_token)) ?>" method="POST">

  <label>New Password</label>
  <input type="password" name="password" />

  <br /><br />

  <input type="submit" />

</form>

<?php Section::stop() ?>
