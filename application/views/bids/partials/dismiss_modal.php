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
      <div class="control-group">
        <label class="control-label">Dismissal Reason:</label>
        <div class="controls">
          <select name="reason">
            <?php foreach (Bid::$dismissal_reasons as $reason): ?>
              <option value="<?php echo Jade\Dumper::_text($reason); ?>"><?php echo Jade\Dumper::_text($reason); ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      <div class="control-group">
        <div class="controls">
          <textarea name="explanation" placeholder="Dismissal Explanation"></textarea>
          <div class="alert alert-error">(The vendor will see this message)</div>
        </div>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal">Cancel</button>
      <button class="btn btn-primary dismiss-btn" data-loading-text="Dismissing...">Dismiss</button>
    </div>
  </form>
</div>