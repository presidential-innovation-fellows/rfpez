<div class="modal hide fade" id="dismiss-modal" tabindex="-1" role="dialog">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h3>Dismiss bid from <span class="company-name"></span></h3>
  </div>
  <div class="modal-body">
    Dismissal Reason: <select name="reason"><option value="price too high">Price too high</option></select>
    <textarea name="explanation" placeholder="Dismissal Explanation"></textarea>
  </div>
  <div class="modal-footer">
    <button class="btn" data-dismiss="modal">Cancel</button>
    <button class="btn btn-primary dismiss-btn" data-loading-text="Dismissing...">Dismiss</button>
  </div>
</div>