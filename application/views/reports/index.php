<?php Section::inject('page_title', 'Reports') ?>
<h1><?php echo e($total_signups); ?> new businesses have signed up, <?php echo e($total_new_to_contracting); ?> of which are new to gov contracting.</h1>
<h6>Sign-ups per day since Jan. 18</h6>
<div id="signups-chart"></div>
<?php //h5 new_to_contracting ?>
<?php //code !{json_encode($new_to_contracting)} ?>
<h1>Projects have received an average of <?php echo e(round($avg_bids_per_project, 1)); ?> bids.</h1>
<?php //h5 bids_per_project ?>
<?php //code !{json_encode($bids_per_project)} ?>
<?php //h5 avg_bids_per_project ?>
<?php //pre !{json_encode($avg_bids_per_project)} ?>
<?php //h5 avg_prices ?>
<?php //pre !{json_encode($avg_prices)} ?>
<?php //h5 avg_price_total ?>
<?php //pre !{json_encode($avg_price_total)} ?>
<script>
  $.getScript('/js/vendor/raphael.js', function(){
    var signupsPerDay = <?php echo json_encode($signups_per_day); ?>;
    var flatSignupsArray = [];
    for (var day in signupsPerDay) {
      flatSignupsArray.push(signupsPerDay[day]);
    }
    Rfpez.reporting_stats({ signupsPerDay : flatSignupsArray });
  });
</script>