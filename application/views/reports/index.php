<?php Section::inject('page_title', 'Reports') ?>
<p>Reports!</p>
<h5>signups_per_day</h5>
<code><?php echo json_encode($signups_per_day); ?></code>
<h5>new_to_contracting</h5>
<code><?php echo json_encode($new_to_contracting); ?></code>
<h5>bids_per_project</h5>
<code><?php echo json_encode($bids_per_project); ?></code>
<h5>avg_bids_per_project</h5>
<code><?php echo json_encode($avg_bids_per_project); ?></code>