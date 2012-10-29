<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', 'New Bid') ?>
<?php Section::inject('current_page', 'new-bid') ?>
<div class="row new-bid-page">
  <div class="span6">
    <?php echo Jade\Dumper::_html(View::make('projects.partials.full_sow')->with('project', $project)); ?>
  </div>
  <div class="span6">
    <h5>New Bid</h5>
    <form class="new-bid-form" action="<?php echo Jade\Dumper::_text( route('bids', array($project->id)) ); ?>" method="POST">
      <input type="hidden" name="submit_now" value="true" />
      <?php $draft = $project->my_current_bid_draft() ?>
      <?php $draft_array = $draft ? $draft->to_array() : false ?>
      <?php $bid = Input::old('bid') ?: $draft_array ?>
      <?php if ($draft): ?>
        <div class="alert alert-success">You are editing a draft saved on <?php echo Jade\Dumper::_text($draft->updated_at); ?>.</div>
      <?php endif; ?>
      <div class="control-group">
        <label>Your Approach</label>
        <textarea name="bid[approach]" placeholder="Give us some quick details of the tools, techniques, and processes you'd use to create a great solution."><?php echo Jade\Dumper::_text( $bid["approach"] ); ?></textarea>
      </div>
      <div class="control-group">
        <label>Previous Work</label>
        <textarea name="bid[previous_work]" placeholder="Where possible, please provide links"><?php echo Jade\Dumper::_text( $bid["previous_work"] ); ?></textarea>
      </div>
      <div class="control-group">
        <label>Employees who would work on this project</label>
        <textarea name="bid[employee_details]" placeholder="We just need to make sure nobody has been put on a list of people disallowed to work on government contracts"><?php echo Jade\Dumper::_text( $bid["employee_details"] ); ?></textarea>
      </div>
      <h5>Prices</h5>
      <table class="table prices-table">
        <thead>
          <tr>
            <th>Deliverable</th>
            <th>Price</th>
            <th>&nbsp;</th>
          </tr>
        </thead>
        <tbody>
          <?php $i = 0; ?>
          <?php $draft_deliverable_names = $draft ? $draft->deliverable_names() : false ?>
          <?php $draft_deliverable_prices = $draft ? $draft->deliverable_prices() : false ?>
          <?php $deliverable_prices = Input::old('deliverable_prices') ?: $draft_deliverable_prices ?>
          <?php $deliverable_names = Input::old('deliverable_names') ?: $draft_deliverable_names ?: array_keys($project->deliverables) ?>
          <?php if ($deliverable_names): ?>
            <?php foreach($deliverable_names as $deliverable_name): ?>
              <tr class="deliverables-row">
                <td>
                  <input type="text" name="deliverable_names[]" value="<?php echo Jade\Dumper::_text( $deliverable_name ); ?>" />
                </td>
                <td>
                  <div class="input-prepend">
                    <span class="add-on">$</span>
                    <input class="deliverable-price" type="text" name="deliverable_prices[]" value="<?php echo Jade\Dumper::_text( $deliverable_prices[$i] ); ?>" />
                  </div>
                </td>
                <td>
                  <a class="btn btn-danger btn-small remove-deliverable">
                    <i class="icon-white icon-trash"></i>
                  </a>
                </td>
              </tr>
              <?php $i++; ?>
            <?php endforeach; ?>
          <?php else: ?>
            <tr class="deliverables-row">
              <td>
                <input type="text" name="deliverable_names[]" />
              </td>
              <td>
                <div class="input-prepend">
                  <span class="add-on">$</span>
                  <input class="deliverable-price" type="text" name="deliverable_prices[]" />
                </div>
              </td>
              <td>
                <a class="btn btn-danger btn-small remove-deliverable">
                  <i class="icon-white icon-trash"></i>
                </a>
              </td>
            </tr>
          <?php endif; ?>
        </tbody>
        <tfoot>
          <tr class="total-price-row">
            <th>Total Price:</th>
            <td id="total-price" colspan="2"></td>
          </tr>
          <tr>
            <td colspan="3">
              <a id="add-deliverable-button" class="btn btn-mini">Add Custom Deliverable</a>
            </td>
          </tr>
        </tfoot>
      </table>
      <div class="form-actions">
        <button class="btn btn-primary" type="submit">Submit Bid</button>
        <a id="save-draft-button" class="btn" data-loading-text="All Changes Saved">Save Draft</a>
        <span class="help-inline">note: bids cannot be edited once submitted!</span>
      </div>
    </form>
  </div>
</div>