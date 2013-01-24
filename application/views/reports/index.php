<?php Section::inject('page_title', 'Reports') ?>
<hr />
<h1><?php echo e($total_signups); ?> new businesses have signed up, <?php echo e($total_new_to_contracting); ?> of which are new to gov contracting.</h1>
<div id="signups-chart">
  <img src="//chart.googleapis.com/chart?chxs=0,676767,11.167,0.333,l,676767&chxt=y&chbh=a&chs=920x120&cht=bvs&chco=F69400&chds=a&chd=t:<?php echo implode(",", $signups_per_day_flat); ?>&chg=-1,0,0,4&chtt=Sign-ups+per+day+since+Jan.+18" width="920" height="120" alt="Sign-ups per day since Jan. 18" />
</div>
<hr />
<h1>Projects have received an average of <?php echo e(round($avg_bids_per_project, 1)); ?> bids.</h1>
<h6>Bids per project</h6>
<div id="num-bids-chart"></div>
<hr />
<h1>The average bid across all projects is <?php echo e(round($avg_price_total)); ?>.</h1>
<h6>Average bid per project</h6>
<div id="price-bids-chart"></div>
<script>
  console.log(<?php echo json_encode($signups_per_day_flat); ?>);
</script>