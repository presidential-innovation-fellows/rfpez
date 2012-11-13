<div id="dismiss-modal" class="modal hide" tabindex="-1" role="dialog">
  <form class="form-horizontal">
    <div class="modal-header">
      <button class="close" type="button" data-dismiss="modal">×</button>
      <h3>
        Dismiss bid from
        <span class="company-name"></span>
      </h3>
    </div>
    <div class="modal-body">
      <div class="alert alert-info">
        These fields are <strong>optional</strong>, and will not be shown to the vendor. They may be useful to log,
        however, in case of a future contest.
      </div>
      <div class="control-group">
        <label class="control-label">Dismissal Reason:</label>
        <div class="controls">
          <select name="reason">
            <option value="">-- Select a Reason --</option>
            <?php foreach (Bid::dismissal_reasons() as $reason): ?>
              <option value="<?php echo Jade\Dumper::_text($reason); ?>"><?php echo Jade\Dumper::_text($reason); ?></option>
            <?php endforeach; ?>
            <option value="Other">Other</option>
          </select>
          <input type="text" name="reason_other" />
        </div>
      </div>
      <div class="control-group">
        <div class="controls">
          <textarea name="explanation" placeholder="Dismissal Explanation"></textarea>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal">Cancel</button>
      <button class="btn btn-primary dismiss-btn" data-loading-text="Dismissing...">Dismiss</button>
    </div>
  </form>
</div>