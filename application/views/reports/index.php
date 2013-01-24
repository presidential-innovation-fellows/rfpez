<?php Section::inject('page_title', 'Reports') ?>
<hr />
<h1><?php echo e($total_signups); ?> new businesses have signed up, <?php echo e($total_new_to_contracting); ?> are probably new to gov contracting</h1>
<div id="signups-chart">
  <img src="//chart.googleapis.com/chart?chxs=0,676767,11.167,0.333,l,676767&chxt=y&chbh=a&chs=920x120&cht=bvs&chco=F69400&chds=a&chd=t:<?php echo implode(",", $signups_per_day_flat); ?>&chg=-1,0,0,4&chtt=Sign-ups+per+day+since+Jan.+18" width="920" height="120" alt="Sign-ups per day since Jan. 18" />
</div>
<hr />
<div class="row">
  <div class="span-5">
    <h1>Projects average <?php echo e(round($avg_bids_per_project, 1)); ?> bids</h1>
    <div id="num-bids-chart"></div>
  </div>
  <div class="span-5">
    <h1>Bids average $<?php echo e(round($avg_price_total)); ?></h1>
    <div id="price-bids-chart"></div>
  </div>
</div>
<script src="http://www.google.com/jsapi"></script>
<script>
  google.load('visualization', '1', {packages: ['corechart']});
  Rfpez.reporting_stats({ bidsPerProject : <?php echo json_encode($bids_per_project); ?>, avgPrices: <?php echo json_encode($avg_prices); ?>, avgPriceTotal : <?php echo json_encode($avg_price_total); ?> });
</script>