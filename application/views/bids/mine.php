<?php Section::inject('page_title', 'My Bids') ?>
<div class="subheader"></div>
<div class="container inner-container">
  <?php if ($bids): ?>
    <table class="table my-bid-table">
      <thead>
        <tr>
          <th>Project</th>
          <th>Total Price</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($bids as $bid): ?>
          <tr class="bid bid-<?php echo e(str_replace(' ', '-', strtolower($bid->status))); ?>">
            <td>
              <a href="<?php echo e($bid->submitted_at ? route('bid', array($bid->project->id, $bid->id)) : route('new_bids', array($bid->project->id))); ?>"><?php echo e($bid->project->title); ?></a>
            </td>
            <td><?php echo e($bid->display_price()); ?></td>
            <td class="status">
              <?php echo e($bid->status); ?>
              <?php if ($bid->is_amended()): ?>
                &nbsp; &nbsp; 
                <span class="bid-amended">Note: this project was amended on <?php echo e($bid->amended_at()); ?>.</span>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p>No bids.</p>
    <p>
      <a class="btn btn-success" href="<?php echo e(route('projects')); ?>"><?php echo __('r.bids.mine.find_projects'); ?></a>
    </p>
  <?php endif; ?>
</div>