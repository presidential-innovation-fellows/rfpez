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
      <label class="required">Company Name</label>
      <input type="text" name="vendor[company_name]" value="<?php echo e( $vendor['company_name'] ); ?>" />
    </div>
    <div class="control-group">
      <label class="required">Person to Contact</label>
      <input type="text" name="vendor[contact_name]" value="<?php echo e( $vendor['contact_name'] ); ?>" />
    </div>
    <div class="control-group">
      <label class="required">Your company in 50 words or less</label>
      <textarea class="input-xlarge" name="vendor[more_info]" rows="7"><?php echo e($vendor['more_info']); ?></textarea>
    </div>
    <div class="control-group">
      <label class="required">What kind of work does your company do?</label>
      <?php foreach(Service::all() as $service): ?>
        <label class="checkbox">
          <input type="checkbox" name="services[<?php echo e( $service->id ); ?>]" <?php echo e( (isset($services[$service->id])) ? "checked" : "" ); ?> />
          <?php echo e($service->name); ?>
        </label>
      <?php endforeach; ?>
    </div>
    <div class="control-group">
      <label class="required">Ballpark average project price</label>
      <select type="text" name="vendor[ballpark_price]">
        <?php foreach(Vendor::$ballpark_prices as $id => $ballpark_price): ?>
          <option value="<?php echo e( $id ); ?>" <?php echo e( ($vendor['ballpark_price'] == $id) ? "selected" : "" ); ?>> <?php echo e( $ballpark_price ); ?> </option>
        <?php endforeach; ?>
      </select>
    </div>
  </fieldset>
  <fieldset class="span5 offset1">
    <?php if ($signup): ?>
      <h5>Contact Info</h5>
      <div class="control-group">
        <label class="required">Email</label>
        <input type="text" name="user[email]" value="<?php echo e( isset($user) ? $user['email'] : '' ); ?>" />
      </div>
      <div class="control-group">
        <label class="checkbox">
          <input type="checkbox" name="user[send_emails]" <?php echo e(isset($user['send_emails']) && !$user['send_emails'] ? '' : 'checked'); ?> />
          Send email updates about new projects open for bids
        </label>
      </div>
      <div class="control-group">
        <label class="required">Choose a Password</label>
        <input type="password" name="user[password]" />
      </div>
    <?php else: ?>
      <h5>Credentials</h5>
      <label class="larger">
        <?php echo e(Auth::user()->email); ?>
        <a class="smaller" href="<?php echo e(route('change_email')); ?>">change email</a>
      </label>
      <label class="larger">
        (password hidden)
        <a class="smaller" href="<?php echo e(route('change_password')); ?>">change password</a>
      </label>
      <h5>Contact Info</h5>
    <?php endif; ?>
    <?php if (!$signup): ?>
      <div class="control-group">
        <label class="checkbox">
          <input type="checkbox" name="user[send_emails]" <?php echo e(Auth::user()->send_emails ? 'checked' : ''); ?> />
          Send email updates about new projects open for bids
        </label>
      </div>
      <div class="control-group">
        <label>Registered in DSBS?</label>
        <?php if ($vendor["dsbs_name"]): ?>
          <div class="green">Yes, under "<?php echo e($vendor["dsbs_name"]); ?>"
</div>
          <?php echo View::make('vendors.partials.dsbs_certifications')->with('user_id', $vendor["dsbs_user_id"]); ?>
        <?php else: ?>
          <div class="red">No</div>
        <?php endif; ?>
      </div>
    <?php endif; ?>
    <div class="control-group">
      <label class="required">Address</label>
      <input type="text" name="vendor[address]" value="<?php echo e( $vendor['address'] ); ?>" />
    </div>
    <div class="control-group">
      <label class="required">City</label>
      <input type="text" name="vendor[city]" value="<?php echo e( $vendor['city'] ); ?>" />
    </div>
    <div class="control-group">
      <label class="required">State</label>
      <select name="vendor[state]">
        <?php foreach(Helper::all_us_states() as $code => $state): ?>
          <option value="<?php echo e( $code ); ?>" <?php echo e( ($vendor['state'] == $code) ? "selected" : "" ); ?>> <?php echo e($state); ?> </option>
        <?php endforeach; ?>
      </select>
    </div>
    <div class="control-group">
      <label class="required">Zip</label>
      <input type="text" name="vendor[zip]" value="<?php echo e( $vendor['zip'] ); ?>" />
    </div>
    <div class="control-group">
      <label>
        DUNS Number (optional)
        <?php echo Helper::helper_tooltip(__("r.users.account_vendor_fields.duns_help"), "left"); ?>
      </label>
      <input type="text" name="vendor[duns]" value="<?php echo e( $vendor['duns'] ); ?>" />
    </div>
  </fieldset>
</div>
<h5>Company links</h5>
<div class="row">
  <div class="span6">
    <div class="control-group">
      <label class="required">Home page</label>
      <input class="input-xlarge" type="text" name="vendor[homepage_url]" value="<?php echo e( $vendor['homepage_url'] ); ?>" />
    </div>
    <div class="vendor-image-url">
      <div class="control-group">
        <label class="required">Link to an image of your best work (400 x 300px)</label>
        <div class="input-append">
          <input class="input-xlarge" type="text" name="vendor[image_url]" value="<?php echo e( $vendor['image_url'] ); ?>" />
          <button id="prev-img-btn" class="btn btn-primary disabled" type="button">Preview</button>
        </div>
      </div>
    </div>
    <div class="control-group">
      <label>Portfolio (optional)</label>
      <input class="input-xlarge" type="text" name="vendor[portfolio_url]" value="<?php echo e( $vendor['portfolio_url'] ); ?>" />
    </div>
    <div class="control-group">
      <label>Public source (optional, e.g. github)</label>
      <input class="input-xlarge" type="text" name="vendor[sourcecode_url]" value="<?php echo e( $vendor['sourcecode_url'] ); ?>" />
    </div>
  </div>
  <div class="span6">
    <label class="vendor-image-preview hide">(Preview)</label>
    <div class="vendor-image-preview vendor-image-preview-frame hide">
      <img />
    </div>
  </div>
</div>
<h5>Small Business Program Representations</h5>
<div class="row">
  <div class="span12">
    <div class="control-group">
      <label class="checkbox">
        <input type="checkbox" name="vendor[sba_b2]" <?php echo e(isset($vendor['sba_b2']) && $vendor['sba_b2'] ? 'checked' : ''); ?> value="1" />
        Are you a registered as a Small Disadvantaged Business 8(a)?
        <a href="http://www.sba.gov/content/8a-business-development-1" target="_blank">What's this?</a>
      </label>
      <label>
        If yes, please select the category in which your ownership falls:
      </label>
      <select type="text" name="vendor[sba_b9]">
        <option value="none" <?php echo e(@$vendor['sba_b9'] == "none" ? "selected" : ""); ?>></option>
        <option value="black_american" <?php echo e(@$vendor['sba_b9'] == "black_american" ? "selected" : ""); ?>>Black American</option>
        <option value="hispanic_american" <?php echo e(@$vendor['sba_b9'] == "hispanic_american" ? "selected" : ""); ?>>Hispanic American</option>
        <option value="native_american" <?php echo e(@$vendor['sba_b9'] == "native_american" ? "selected" : ""); ?>>Native American</option>
        <option value="asian_pacific_american" <?php echo e(@$vendor['sba_b9'] == "asian_pacific_american" ? "selected" : ""); ?>>Asian-Pacific American</option>
        <option value="asian_indian_american" <?php echo e(@$vendor['sba_b9'] == "asian_indian_american" ? "selected" : ""); ?>>Asian (Asian-Indian) American</option>
        <option value="individual_concern_other" <?php echo e(@$vendor['sba_b9'] == "individual_concern_other" ? "selected" : ""); ?>>Individual/concern, other than one of the preceding.</option>
      </select>
    </div>
    <div class="control-group">
      <label class="checkbox">
        <input type="checkbox" name="vendor[sba_b3]" <?php echo e((isset($vendor['sba_b3']) && $vendor['sba_b3']) ? 'checked' : ''); ?> value="1" />
        Are you a Woman Owned Small Business?
      </label>
    </div>
    <div class="control-group">
      <label class="checkbox">
        <input type="checkbox" name="vendor[sba_b4i]" <?php echo e((isset($vendor['sba_b4i']) && $vendor['sba_b4i']) ? 'checked' : ''); ?> value="1" />
        Have you registered for the Woman Owned Small Business program?
        <a href="http://www.sba.gov/content/contracting-opportunities-women-owned-small-businesses" target="_blank">What's this?</a>
      </label>
    </div>
    <div class="control-group">
      <label class="checkbox">
        <input type="checkbox" name="vendor[sba_b5i]" <?php echo e((isset($vendor['sba_b5i']) && $vendor['sba_b5i']) ? 'checked' : ''); ?> value="1" />
        Are you an Economically Disadvantaged Woman Owned Small Business?
        <a href="http://www.sba.gov/content/contracting-opportunities-women-owned-small-businesses" target="_blank">What's this?</a>
      </label>
    </div>
    <div class="control-group">
      <label class="checkbox">
        <input type="checkbox" name="vendor[sba_b6]" <?php echo e((isset($vendor['sba_b6']) && $vendor['sba_b6']) ? 'checked' : ''); ?> value="1" />
        Are you a Veteran-Owned Small Business?
        <a href="http://www.sba.gov/content/veteran-service-disabled-veteran-owned" target="_blank">What's this?</a>
      </label>
    </div>
    <div class="control-group">
      <label class="checkbox">
        <input type="checkbox" name="vendor[sba_b7]" <?php echo e((isset($vendor['sba_b7']) && $vendor['sba_b7']) ? 'checked' : ''); ?> value="1" />
        Are you a Service-Disabled Veteran-Owned Small Business?
        <a href="http://www.sba.gov/content/veteran-service-disabled-veteran-owned" target="_blank">What's this?</a>
      </label>
    </div>
    <div class="control-group">
      <label class="checkbox">
        <input type="checkbox" name="vendor[sba_b8i]" <?php echo e((isset($vendor['sba_b8i']) && $vendor['sba_b8i']) ? 'checked' : ''); ?> value="1" />
        Are you in a HUBZone?
        <a href="http://www.sba.gov/hubzone/" target="_blank">What's this?</a>
      </label>
    </div>
  </div>
</div>