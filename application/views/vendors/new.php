<?php Section::inject('page_title', 'New Company') ?>
<form id="new-vendor-form" action="<?php echo Jade\Dumper::_text(route('vendors')); ?>" method="POST">
  <?php echo Jade\Dumper::_html(View::make('users.account_vendor_fields')->with('vendor', Input::old('vendor'))->with('user', Input::old('user'))->with('services', Input::old('services'))->with('signup', true)); ?>
  <div class="form-actions">
    <button class="btn btn-primary" type="submit">Save Profile</button>
  </div>
</form>
<div id="explain-duns-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="explain-duns-modal-la" aria-hidden="true">
  <div class="modal-header">
    <button class="close" type="button" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="explain-duns-modal-label">DUNS Number</h3>
  </div>
  <div class="modal-body">
    <p>
      If your company doesn't have a DUNS number, or you don't even know what one is, that's okay.
      You don't have to worry about it for now. Eventually you'll need one, but it's a fairly painless
      process and doesn't cost any money.
    </p>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal" aria-hidden="true">Okay</button>
  </div>
</div>