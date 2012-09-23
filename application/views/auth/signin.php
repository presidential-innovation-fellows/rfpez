<?php Section::start('content') ?>

<h3>Sign In</h3>

<a href="<?= route('forgot_password') ?>">forgot password?</a><br /><br />

<form action="<?= route('signin') ?>" method="POST">
<label>Email:</label><input type="text" name="email" />
<label>Password:</label><input type="password" name="password" />
<input type="submit" />
</form>

<?php Section::stop(); ?>