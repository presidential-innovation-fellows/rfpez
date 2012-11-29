<?php if (!isset($vendor) && Auth::user() && Auth::user()->vendor) { ?>
  <?php $vendor = Input::old('vendor') ?: Auth::vendor()->to_array(); ?>
<?php } ?>
<?php if (!isset($services) && Auth::user() && Auth::user()->vendor) { ?>
  <?php $services_list = Auth::user()->vendor->services()->lists('id') ?>
  <?php $services = array(); ?>
  <?php foreach ($services_list as $key => $val) { ?>
    <?php $services[intval($val)] = true; ?>
  <?php } ?>
<?php } ?>
<?php $signup = isset($signup) && $signup; ?>
<div class="row">
  <fieldset class="span5">
    <h5>Work and Capabilities</h5>
    <div class="control-group">
      <label>
        <strong>Your company in 50 words or less</strong>
      </label>
      <textarea class="input-xlarge" name="vendor[more_info]" rows="7"><?php echo $vendor['more_info']; ?></textarea>
    </div>
    <div class="control-group">
      <strong>What kind of work does your company do?</strong>
      <?php foreach(Service::all() as $service): ?>
        <label class="checkbox">
          <input type="checkbox" name="services[<?php echo  $service->id ; ?>]" <?php echo  (isset($services[$service->id])) ? "checked" : "" ; ?> />
          <?php echo $service->name; ?>
        </label>
      <?php endforeach; ?>
    </div>
    <div class="control-group">
      <label>
        <strong>Ballpark average project price</strong>
      </label>
      <select type="text" name="vendor[ballpark_price]">
        <?php foreach(Vendor::$ballpark_prices as $id => $ballpark_price): ?>
          <option value="<?php echo  $id ; ?>" <?php echo  ($vendor['ballpark_price'] == $id) ? "selected" : "" ; ?>> <?php echo  $ballpark_price ; ?> </option>
        <?php endforeach; ?>
      </select>
    </div>
    <hr />
    <h5>Company links</h5>
    <div class="control-group">
      <label>Home page</label>
      <input class="input-xlarge" type="text" name="vendor[homepage_url]" value="<?php echo  $vendor['homepage_url'] ; ?>" />
    </div>
    <div class="control-group">
      <label>Portfolio (optional)</label>
      <input class="input-xlarge" type="text" name="vendor[portfolio_url]" value="<?php echo  $vendor['portfolio_url'] ; ?>" />
    </div>
    <div class="control-group">
      <label>Public source (optional, e.g. github)</label>
      <input class="input-xlarge" type="text" name="vendor[sourcecode_url]" value="<?php echo  $vendor['sourcecode_url'] ; ?>" />
    </div>
    <div class="vendor-image-url">
      <div class="control-group">
        <label>Link to an image of your best work (400 x 300px)</label>
        <div class="input-append">
          <input class="input-xlarge" type="text" name="vendor[image_url]" value="<?php echo  $vendor['image_url'] ; ?>" />
          <button id="prev-img-btn" class="btn btn-primary disabled" type="button">Preview</button>
        </div>
      </div>
      <label class="vendor-image-preview hide">(Preview)</label>
      <div class="vendor-image-preview vendor-image-preview-frame hide">
        <img />
      </div>
    </div>
  </fieldset>
  <fieldset class="span5 offset1">
    <?php if ($signup): ?>
      <h5>Contact Info</h5>
      <div class="control-group">
        <label>Email</label>
        <input type="text" name="user[email]" value="<?php echo  isset($user) ? $user['email'] : '' ; ?>" />
      </div>
      <div class="control-group">
        <label>Choose a Password</label>
        <input type="password" name="user[password]" />
      </div>
    <?php else: ?>
      <h5>Credentials</h5>
      <label class="larger">
        <?php echo Auth::user()->email; ?>
        <a class="smaller" href="<?php echo route('change_email'); ?>" data-pjax="data-pjax">change email</a>
      </label>
      <label class="larger">
        (password hidden)
        <a class="smaller" href="<?php echo route('change_password'); ?>" data-pjax="data-pjax">change password</a>
      </label>
      <h5>Contact Info</h5>
    <?php endif; ?>
    <?php if (!$signup): ?>
      <div class="control-group">
        <label>Registered in SAM.gov?</label>
        <?php if ($vendor["sam_entity_name"]): ?>
          <div class="green">Yes, under "<?php echo $vendor["sam_entity_name"]; ?>"</div>
        <?php else: ?>
          <div class="red">No</div>
        <?php endif; ?>
      </div>
      <div class="control-group">
        <label>Registered in DSBS?</label>
        <?php if ($vendor["dsbs_name"]): ?>
          <div class="green">Yes, under "<?php echo $vendor["dsbs_name"]; ?>"
</div>
          <?php echo View::make('vendors.partials.dsbs_certifications')->with('user_id', $vendor["dsbs_user_id"]); ?>
        <?php else: ?>
          <div class="red">No</div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <div class="control-group">
      <label>Company Name</label>
      <input type="text" name="vendor[company_name]" value="<?php echo  $vendor['company_name'] ; ?>" />
    </div>
    <div class="control-group">
      <label>Person to Contact</label>
      <input type="text" name="vendor[contact_name]" value="<?php echo  $vendor['contact_name'] ; ?>" />
    </div>
    <div class="control-group">
      <label>Address</label>
      <input type="text" name="vendor[address]" value="<?php echo  $vendor['address'] ; ?>" />
    </div>
    <div class="control-group">
      <label>City</label>
      <input type="text" name="vendor[city]" value="<?php echo  $vendor['city'] ; ?>" />
    </div>
    <div class="control-group">
      <label>State</label>
      <select name="vendor[state]">
        <?php foreach(Helper::all_us_states() as $code => $state): ?>
          <option value="<?php echo  $code ; ?>" <?php echo  ($vendor['state'] == $code) ? "selected" : "" ; ?>> <?php echo $state; ?> </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="control-group">
      <label>Zip</label>
      <input type="text" name="vendor[zip]" value="<?php echo  $vendor['zip'] ; ?>" />
    </div>
    <div class="control-group">
      <label>
        DUNS Number (optional)
        <?php echo Helper::helper_tooltip(__("r.users.account_vendor_fields.duns_help"), "left"); ?>
      </label>
      <input type="text" name="vendor[duns]" value="<?php echo  $vendor['duns'] ; ?>" />
    </div>
  </fieldset>
</div>