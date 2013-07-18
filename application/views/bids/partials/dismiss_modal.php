<div id="dismiss-modal" class="modal hide" tabindex="-1" role="dialog">
  <form class="form-horizontal">
    <div class="modal-header">
      <button class="close" type="button" data-dismiss="modal">×</button>
      <h3>
        Decline bid from
        <span class="company-name"></span>
      </h3>
    </div>
    <div class="modal-body">
      <div class="alert alert-info"><?php echo __('r.bids.partials.dismiss_modal.optional_fields'); ?></div>
      <div class="control-group">
        <label class="control-label">Reason Declined:</label>
        <div class="controls">
          <select name="reason">
            <option value="">-- Select a Reason --</option>
            <?php foreach (Bid::dismissal_reasons() as $reason): ?>
              <option value="<?php echo e($reason); ?>"><?php echo e($reason); ?></option>
            <?php endforeach; ?>
            <option value="Other">Other</option>
          </select>
          <input type="text" name="reason_other" />
        </div>
      </div>
      <div class="control-group">
        <div class="controls">
          <textarea name="explanation" placeholder="Explanation for Declining"></textarea>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal">Cancel</button>
      <button class="btn btn-primary dismiss-btn" data-loading-text="Declining...">Decline</button>
    </div>
  </form>
</div>