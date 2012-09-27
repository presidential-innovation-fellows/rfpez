<?php
  if (!isset($officer) && Auth::user() && Auth::user()->officer) $officer = Auth::user()->officer->to_array();
?>

<?php if (isset($signup) && $signup): ?>
  <label>Email:</label>
  <input type="text" name="user[email]" value="<?= $user["email"] ?>" />
<?php endif; ?>

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
