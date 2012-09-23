<?php Section::start('content') ?>

<?php if (Session::has('errors')): ?>
  <?php foreach(Session::get('errors') as $error): ?>
    <?= $error ?><br />
  <?php endforeach; ?>
<?php endif; ?>

<h3>Forgot Password</h3>

<form action="<?= route('forgot_password') ?>" method="POST">

  <label>Email address</label>
  <input type="text" name="email" />

  <input type="submit" />

</form>

<?php Section::stop() ?>
