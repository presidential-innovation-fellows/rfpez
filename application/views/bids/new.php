<?php Section::inject('page_title', $project->title) ?>
<?php Section::inject('page_action', 'New Bid') ?>
<?php Section::inject('current_page', 'new-bid') ?>
<div class="row new-bid-page">
  <div class="span6">
    <?php echo View::make('projects.partials.full_sow')->with('project', $project); ?>
  </div>
  <div class="span6">
    <h5>New Bid</h5>
    <div class="alert alert-success">
      <strong>Eligibility:
</strong>
      This project is reserved for small businesses. In this case, the government defines small as having annual revenues of less than <strong>$<?php echo e(intval($project->project_type->threshold)); ?> million</strong>.
    </div>
    <form class="new-bid-form" action="<?php echo e( route('bids', array($project->id)) ); ?>" method="POST">
      <input type="hidden" name="submit_now" value="true" />
      <?php $draft = $project->my_current_bid_draft() ?>
      <?php $draft_array = $draft ? $draft->to_array() : false ?>
      <?php $bid = Input::old('bid') ?: $draft_array ?>
      <?php if ($draft): ?>
        <div class="alert alert-success"><?php echo __("r.bids.new.editing_draft", array("date" => $draft->updated_at)); ?></div>
      <?php endif; ?>
      <div class="control-group">
        <label>Your Approach</label>
        <textarea name="bid[approach]" placeholder="<?php echo e(__('r.bids.new.approach_placeholder')); ?>"><?php echo e( $bid["approach"] ); ?></textarea>
      </div>
      <div class="control-group">
        <label>Previous Work</label>
        <textarea name="bid[previous_work]" placeholder="<?php echo e(__('r.bids.new.previous_work_placeholder')); ?>"><?php echo e( $bid["previous_work"] ); ?></textarea>
      </div>
      <div class="control-group">
        <label>Employees who would work on this project</label>
        <textarea name="bid[employee_details]" placeholder="<?php echo e(__('r.bids.new.employee_details_placeholder')); ?>"><?php echo e( $bid["employee_details"] ); ?></textarea>
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
          <?php $deliverable_names = Input::old('deliverable_names') ?: $draft_deliverable_names ?: $project->deliverable_names() ?>
          <?php if ($deliverable_names): ?>
            <?php foreach($deliverable_names as $deliverable_name): ?>
              <tr class="deliverables-row">
                <td>
                  <input class="span3" type="text" name="deliverable_names[]" value="<?php echo e( $deliverable_name ); ?>" />
                </td>
                <td>
                  <div class="input-prepend">
                    <span class="add-on">$</span>
                    <input class="deliverable-price" type="text" name="deliverable_prices[]" value="<?php echo e( $deliverable_prices[$i] ); ?>" />
                    <?php if ($project->price_type == Project::PRICE_TYPE_HOURLY): ?>
                      <span class="add-on">/hr</span>
                    <?php endif; ?>
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
                <input class="span3" type="text" name="deliverable_names[]" />
              </td>
              <td>
                <div class="input-prepend">
                  <span class="add-on">$</span>
                  <input class="deliverable-price" type="text" name="deliverable_prices[]" />
                  <?php if ($project->price_type == Project::PRICE_TYPE_HOURLY): ?>
                    <span class="add-on">/hr</span>
                  <?php endif; ?>
                </div>
              </td>
              <td>
                <a class="btn btn-danger btn-small remove-deliverable">
                  <i class="icon-white icon-trash"></i>
                </a>
              </td>
            </tr>
          <?php endif; ?>
          <tfoot>
            <?php if ($project->price_type == Project::PRICE_TYPE_FIXED): ?>
              <tr class="total-price-row">
                <th>Total Price:</th>
                <td id="total-price" colspan="2"></td>
              </tr>
            <?php endif; ?>
            <tr>
              <td colspan="3">
                <a id="add-deliverable-button" class="btn btn-mini">Add Custom Deliverable</a>
              </td>
            </tr>
          </tfoot>
        </tbody>
      </table>
      <div class="form-actions">
        <button class="btn btn-primary" type="submit">Submit Bid</button>
        <a id="save-draft-button" class="btn" data-loading-text="All Changes Saved">Save Draft</a>
        <span class="help-inline"><?php echo e(__("r.bids.new.no_edit_warning")); ?></span>
      </div>
    </form>
  </div>
</div>