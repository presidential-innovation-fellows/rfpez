<?php Section::inject('page_title', 'Reports') ?>
<hr />
<h1><?php echo e($total_signups); ?> new businesses have signed up, <?php echo e($total_new_to_contracting); ?> of which are new to gov contracting.</h1>
<h6>Sign-ups per day since Jan. 18</h6>
<div id="signups-chart"></div>
<hr />
<h1>Projects have received an average of <?php echo e(round($avg_bids_per_project, 1)); ?> bids.</h1>
<h6>Bids per project</h6>
<div id="num-bids-chart"></div>
<hr />
<h1>The average bid across all projects is <?php echo e(round($avg_price_total)); ?>.</h1>
<h6>Average bid per project</h6>
<div id="price-bids-chart"></div>
<script>
  $.getScript('/js/vendor/raphael.js', function(){
    Rfpez.reporting_stats({ signupsPerDay : <?php echo json_encode($signups_per_day); ?>, bidsPerProject : <?php echo json_encode($bids_per_project); ?>, avgPrices: <?php echo json_encode($avg_prices); ?>, avgPriceTotal : <?php echo json_encode($avg_price_total); ?> });
  });
</script>