<?php Section::inject('page_title', 'New Officer'); ?>
<?php Section::start('content') ?>

<h3>New Officer</h3>

<form action="<?= route('officers') ?>" method="POST">

  <?php $user = Input::get('user'); ?>
  <?php $officer = Input::get('officer'); ?>

  <label>Email:</label>
  <input type="text" name="user[email]" value="<?= $user["email"] ?>" />

  <label>Name:</label>
  <input type="text" name="officer[name]" value="<?= $officer["name"] ?>" />

  <label>Title:</label>
  <input type="text" name="officer[title]" value="<?= $officer["title"] ?>" />

  <label>Agency:</label>
  <input type="text" name="officer[agency]" value="<?= $officer["agency"] ?>" />

  <label>Phone:</label>
  <input type="text" name="officer[phone]" value="<?= $officer["phone"] ?>" />

  <label>Fax:</label>
  <input type="text" name="officer[fax]" value="<?= $officer["fax"] ?>" />

  <br /><br />

  <input type="submit" />

</form>

<?php Section::stop() ?>
