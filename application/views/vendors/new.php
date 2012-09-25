<?php Section::inject('page_title', 'New Vendor'); ?>
<?php Section::start('content') ?>

<h3>New Vendor</h3>

<form action="<?= route('vendors') ?>" method="POST">

  <?php $vendor = Input::get('vendor'); ?>
  <?php $user = Input::get('user'); ?>
  <?php $services = Input::get('services'); ?>

  <label>Company Name</label>
  <input type="text" name="vendor[company_name]" value="<?= $vendor["company_name"] ?>" />

  <label>Contact Name</label>
  <input type="text" name="vendor[contact_name]" value="<?= $vendor["contact_name"] ?>" />

  <label>Email</label>
  <input type="text" name="user[email]" value="<?= $user["email"] ?>" />

  <label>Choose a Password</label>
  <input type="password" name="user[password]" />

  <label>Address</label>
  <input type="text" name="vendor[address]" value="<?= $vendor["address"] ?>" />

  <label>City</label>
  <input type="text" name="vendor[city]" value="<?= $vendor["city"] ?>" />

  <label>State</label>
  <select name="vendor[state]">
    <?php foreach(Helper::all_us_states() as $code => $state): ?>
      <option value="<?= $code ?>" <?php if ($vendor["state"] == $code) echo "selected"; ?>><?= $state ?></option>
    <?php endforeach; ?>
  </select>

  <label>Zip</label>
  <input type="text" name="vendor[zip]" value="<?= $vendor["zip"] ?>" />

  <label>Ballpark Price</label>
  <select type="text" name="vendor[ballpark_price]">
    <?php foreach(Vendor::$ballpark_prices as $id => $ballpark_price): ?>
      <option value="<?= $id ?>" <?php if ($vendor["ballpark_price"] == $id) echo "selected"; ?>><?= $ballpark_price ?></option>
    <?php endforeach; ?>
  </select>

  <label>Portfolio URL</label>
  <input type="text" name="vendor[portfolio_url]" value="<?= $vendor["portfolio_url"] ?>" />

  <label>More Info</label>
  <textarea name="vendor[more_info]"><?= $vendor["more_info"] ?></textarea>

  <h5>Services</h5>
  <?php foreach(Service::all() as $service): ?>
    <label><input type="checkbox" name="services[<?= $service->id ?>]" <?php if (isset($services[$service->id])) echo "checked"; ?> />
      <?= $service->name ?></label>
  <?php endforeach; ?>

  <input type="submit" />

</form>

<?php Section::stop() ?>
