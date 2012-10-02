<?php
  if (!isset($vendor) && Auth::user() && Auth::user()->vendor) {
    $vendor = Input::old('vendor') ?: Auth::user()->vendor->to_array();
  }

  if (!isset($services) && Auth::user() && Auth::user()->vendor) {
    $services_list = Auth::user()->vendor->services()->lists('id');
    $services = array();
    foreach ($services_list as $key => $val) {
      $services[intval($val)] = true;
    }
  }
?>

<div class="row">

    <fieldset class="span5">
    <h3>Work and Capabilities</h3>

    <div class="control-group">
      <label><h5>Your company in 50 words or less</h5></label>
      <textarea name="vendor[more_info]"><?= $vendor["more_info"] ?></textarea>
    </div>

    <div class="control-group">
      <h5>What kind of work does your company do?</h5>
      <?php foreach(Service::all() as $service): ?>
        <label><input type="checkbox" name="services[<?= $service->id ?>]"
                      <?php if (isset($services[$service->id])) echo "checked"; ?> />
          <?= $service->name ?></label>
      <?php endforeach; ?>
    </div>

    <div class="control-group">
      <label><h5>Ballpark average project price</h5></label>
      <select type="text" name="vendor[ballpark_price]">
        <?php foreach(Vendor::$ballpark_prices as $id => $ballpark_price): ?>
          <option value="<?= $id ?>" <?php if ($vendor["ballpark_price"] == $id) echo "selected"; ?>><?= $ballpark_price ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <h5>Company links</h5>

    <div class="control-group">
      <label>Home page</label>
      <input type="text" name="vendor[homepage_url]" value="<?= $vendor["homepage_url"] ?>" />
    </div>

    <div class="control-group">
      <label>Portfolio</label>
      <input type="text" name="vendor[portfolio_url]" value="<?= $vendor["portfolio_url"] ?>" />
    </div>

    <div class="control-group">
      <label>Public source (e.g. github)</label>
      <input type="text" name="vendor[sourcecode_url]" value="<?= $vendor["sourcecode_url"] ?>" />
    </div>

    <div class="vendor-image-url">
      <div class="control-group">
        <label>An image of your best work</label>
        <input type="text" name="vendor[image_url]" value="<?= $vendor["image_url"] ?>" />
      </div>

      <label class="vendor-image-preview hide">(Preview)</label>
      <div class="vendor-image-preview vendor-image-preview-frame hide">
        <img />
      </div>
    </div>
  </fieldset>

  <fieldset class="span5">

    <?php if (isset($signup) && $signup): ?>
      <h3>Contact Info</h3>

      <div class="control-group">
        <label>Email</label>
        <input type="text" name="user[email]" value="<?= isset($user) ? $user["email"] : "" ?>" />
      </div>

      <div class="control-group">
        <label>Choose a Password</label>
        <input type="password" name="user[password]" />
      </div>

    <?php else: ?>
      <h3>Credentials</h3>

      <label class="larger"> <?= Auth::user()->email ?>
        <a class="smaller" href="<?= route('change_email') ?>">change email</a>
      </label>

      <label class="larger"> (password hidden)
        <a class="smaller" href="<?= route('change_password') ?>">change password</a>
      </label>

      <h3>Contact Info</h3>

    <?php endif; ?>

    <div class="control-group">
      <label>Company Name</label>
      <input type="text" name="vendor[company_name]" value="<?= $vendor["company_name"] ?>" />
    </div>

    <div class="control-group">
      <label>Person to Contact</label>
      <input type="text" name="vendor[contact_name]" value="<?= $vendor["contact_name"] ?>" />
    </div>

    <div class="control-group">
      <label>Address</label>
      <input type="text" name="vendor[address]" value="<?= $vendor["address"] ?>" />
    </div>

    <div class="control-group">
      <label>City</label>
      <input type="text" name="vendor[city]" value="<?= $vendor["city"] ?>" />
    </div>

    <div class="control-group">
      <label>State</label>
      <select name="vendor[state]">
        <?php foreach(Helper::all_us_states() as $code => $state): ?>
          <option value="<?= $code ?>" <?php if ($vendor["state"] == $code) echo "selected"; ?>><?= $state ?></option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="control-group">
      <label>Zip</label>
      <input type="text" name="vendor[zip]" value="<?= $vendor["zip"] ?>" />
    </div>

  </fieldset>

</div>