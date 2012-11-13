<div id="award-modal" class="modal hide" tabindex="-1" role="dialog">
  <form class="form-horizontal">
    <div class="modal-header">
      <button class="close" type="button" data-dismiss="modal">×</button>
      <h3>
        Award bid from
        <span class="company-name"></span>
      </h3>
    </div>
    <div class="modal-body">
      <p>
        <strong>You've selected a vendor!
</strong>
        When you award this contract, we'll send the message below to the vendor
        that you have accepted their bid and are ready to start working with them.
        Make sure all your "i"s are dotted and "t"s are crossed before you hit the button below.
        We will also automatically dismiss all other bids on this project.
      </p>
      <p>
        Awarding contracts is for <strong>registered contracting officers</strong> only.
        If you're not a CO, turn back now.
      </p>
      <?php if ($project->is_open_for_bids()): ?>
        <div class="alert alert-danger"><strong>Careful!</strong> The due date for proposals hasn't passed. Awarding now may yield a protest.</div>
      <?php endif; ?>
      <label class="bold-label">Message to vendor: (will be sent to <em class="vendor-email"></em>)</label>
      <textarea class="awarded-message" name="awarded_message"><?php echo Jade\Dumper::_html(View::make('bids.partials.award_message')->with('project', $project)->with('officer', Auth::officer())); ?></textarea>
      <label class="checkbox">
        No thanks, I'd prefer to send an email to the vendor by myself
        <input class="manual-awarded-message-checkbox" type="checkbox" />
      </label>
    </div>
    <div class="modal-footer">
      <button class="btn" data-dismiss="modal">Cancel</button>
      <button class="btn btn-primary award-btn" data-loading-text="Awarding...">Award</button>
    </div>
  </form>
</div>