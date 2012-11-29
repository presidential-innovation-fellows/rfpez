<?php Section::inject('page_title', 'My Bids') ?>
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
        <tr class="bid bid-<?php echo strtolower($bid->status); ?>">
          <td>
            <a href="<?php echo $bid->submitted_at ? route('bid', array($bid->project->id, $bid->id)) : route('new_bids', array($bid->project->id)); ?>" data-pjax="data-pjax"><?php echo $bid->project->title; ?></a>
          </td>
          <td><?php echo $bid->display_price(); ?></td>
          <td class="status"><?php echo $bid->status; ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
<?php else: ?>
  <p>No bids.</p>
  <p>
    <a class="btn btn-success" href="<?php echo route('projects'); ?>" data-pjax="data-pjax"><?php echo __('r.bids.mine.find_projects'); ?></a>
  </p>
<?php endif; ?>