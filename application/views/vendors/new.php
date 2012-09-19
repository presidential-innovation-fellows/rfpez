<?php Section::start('content') ?>

<?php if (Session::has('errors')): ?>
  <?php foreach(Session::get('errors') as $error): ?>
    <?= $error ?><br />
  <?php endforeach; ?>
<?php endif; ?>

<h3>New Vendor</h3>

<form action="<?= route('vendors') ?>" method="POST">

  <label>Company Name</label>
  <input type="text" name="vendor[company_name]" />

  <label>Contact Name</label>
  <input type="text" name="vendor[contact_name]" />

  <label>Email</label>
  <input type="text" name="user[email]" />

  <label>Choose a Password</label>
  <input type="password" name="user[password]" />

  <label>Address</label>
  <input type="text" name="vendor[address]" />

  <label>City</label>
  <input type="text" name="vendor[city]" />

  <label>State</label>
  <select name="vendor[state]">
    <option value="CA">CA</option>
  </select>

  <label>Zip</label>
  <input type="text" name="vendor[zip]" />

  <label>Ballpark Price</label>
  <select type="text" name="vendor[ballpark_price]">
    <?php foreach(Vendor::$ballpark_prices as $id => $ballpark_price): ?>
      <option value="<?= $id ?>"><?= $ballpark_price ?></option>
    <?php endforeach; ?>
  </select>

  <label>Portfolio URL</label>
  <input type="text" name="vendor[portfolio_url]" />

  <label>More Info</label>
  <textarea name="vendor[more_info]"></textarea>

  <h5>Services</h5>
  <?php foreach(Service::all() as $service): ?>
    <label><input type="checkbox" name="services[<?= $service->id ?>]" /><?= $service->name ?></label>
  <?php endforeach; ?>

  <input type="submit" />

</form>

<?php Section::stop() ?>
