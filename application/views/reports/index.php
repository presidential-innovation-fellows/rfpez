<?php Section::inject('page_title', 'Reports') ?>
<p>Reports!</p>
<h5>signups_per_day</h5>
<pre><?php echo json_encode($signups_per_day); ?></pre>
<h5>new_to_contracting</h5>
<pre><?php echo json_encode($new_to_contracting); ?></pre>
<h5>bids_per_project</h5>
<pre><?php echo json_encode($bids_per_project); ?></pre>
<h5>avg_bids_per_project</h5>
<pre><?php echo json_encode($avg_bids_per_project); ?></pre>
<h5>avg_prices</h5>
<pre><?php echo json_encode($avg_prices); ?></pre>
<h5>avg_price_total</h5>
<pre><?php echo json_encode($avg_price_total); ?></pre>