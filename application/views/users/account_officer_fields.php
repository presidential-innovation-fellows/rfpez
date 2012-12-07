<?php if (!isset($officer) && Auth::user() && Auth::user()->officer): ?>
  <?php $officer = Input::old('officer') ?: Auth::user()->officer->to_array() ?>
<?php endif; ?>
<?php if (isset($signup) && $signup): ?>
  <div class="control-group">
    <label>Email</label>
    <input type="text" name="user[email]" value="<?php echo e($user['email']); ?>" />
    <label class="checkbox">
      <input type="checkbox" name="user[send_emails]" <?php echo e(isset($user['send_emails']) && !$user['send_emails'] ? '' : 'checked'); ?> />
      Yes, email me with updates about EasyBid
    </label>
  </div>
<?php else: ?>
  <label class="larger">
    <?php echo e(Auth::user()->email); ?>
    <a class="smaller" href="<?php echo e(route('change_email')); ?>">change email</a>
  </label>
  <label class="larger">
    (password hidden)
    <a class="smaller" href="<?php echo e(route('change_password')); ?>">change password</a>
  </label>
  <label class="larger">Role: <?php echo e($officer['role_text']); ?></label>
  <label class="checkbox">
    <input type="checkbox" name="user[send_emails]" <?php echo e(Auth::user()->send_emails ? 'checked' : ''); ?> />
    Yes, email me with updates about EasyBid
  </label>
<?php endif; ?>
<div class="control-group">
  <label>Name</label>
  <input type="text" name="officer[name]" value="<?php echo e($officer['name']); ?>" />
</div>
<div class="control-group">
  <label>Title</label>
  <input type="text" name="officer[title]" value="<?php echo e($officer['title']); ?>" />
</div>
<div class="control-group">
  <label>Agency</label>
  <input type="text" name="officer[agency]" value="<?php echo e($officer['agency']); ?>" />
</div>
<div class="control-group">
  <label>Phone</label>
  <input type="text" name="officer[phone]" value="<?php echo e($officer['phone']); ?>" />
</div>