
Rfpez.reporting_stats = function(stats) {
  var signupsChart;
  signupsChart = Raphael('signups-chart', 920, 140);
  return signupsChart.barchart(0, 20, 910, 120, [[3, 6, 12, 7, 0, 4, 0, 35, 0]]);
};
