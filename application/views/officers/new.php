<?php Section::start('content') ?>

<?php if (Session::has('errors')): ?>
  <?php foreach(Session::get('errors') as $error): ?>
    <?= $error ?><br />
  <?php endforeach; ?>
<?php endif; ?>

<h3>New Officer</h3>

<form action="<?= route('officers') ?>" method="POST">

  <label>Email:</label>
  <input type="text" name="user[email]" />

  <label>Name:</label>
  <input type="text" name="officer[name]" />

  <label>Title:</label>
  <input type="text" name="officer[title]" />

  <label>Agency:</label>
  <input type="text" name="officer[agency]" />

  <label>Phone:</label>
  <input type="text" name="officer[phone]" />

  <label>Fax:</label>
  <input type="text" name="officer[fax]" />


  <br /><br />

  <input type="submit" />

</form>

<?php Section::stop() ?>
