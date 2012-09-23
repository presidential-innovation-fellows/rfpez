<?php Section::start('content') ?>

<?php if (Session::has('errors')): ?>
  <?php foreach(Session::get('errors') as $error): ?>
    <?= $error ?><br />
  <?php endforeach; ?>
<?php endif; ?>

<h3>Reset password for <?= $user->email ?></h3>

<form action="<?= route('reset_password', array($user->reset_password_token)) ?>" method="POST">

  <label>New Password</label>
  <input type="password" name="password" />

  <br /><br />

  <input type="submit" />

</form>

<?php Section::stop() ?>
