var __hasProp = {}.hasOwnProperty;

Rfpez.reporting_stats = function(stats) {
  var avgPrices, chartSize, day, flatBidsArray, flatSignupsArray, numBidsChart, numProjects, priceBidsChart, project, projectLabels, signUps, signupsChart;
  flatSignupsArray = (function() {
    var _ref, _results;
    _ref = stats.signupsPerDay;
    _results = [];
    for (day in _ref) {
      if (!__hasProp.call(_ref, day)) continue;
      signUps = _ref[day];
      _results.push(signUps);
    }
    return _results;
  })();
  signupsChart = Raphael('signups-chart', 920, 140);
  signupsChart.barchart(0, 20, 910, 120, [flatSignupsArray]);
  projectLabels = [];
  avgPrices = [];
  flatBidsArray = (function() {
    var _i, _len, _ref, _results;
    _ref = stats.bidsPerProject;
    _results = [];
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      project = _ref[_i];
      projectLabels.push(project.project_title);
      avgPrices.push(parseInt(project.avg_price));
      _results.push(parseInt(project.num_bids, 10));
    }
    return _results;
  })();
  numProjects = flatBidsArray.length;
  chartSize = numProjects * 30;
  numBidsChart = Raphael('num-bids-chart', 460, chartSize + 10);
  numBidsChart.hbarchart(250, 0, 210, chartSize, [flatBidsArray]);
  Raphael.g.axis(250, chartSize - 65, chartSize - 50, null, null, numProjects, 1, projectLabels.reverse(), "|", 0, bidsChart);
  priceBidsChart = Raphael('price-bids-chart', 460, chartSize + 10);
  priceBidsChart.hbarchart(0, 0, 450, chartSize, [avgPrices]);
  return Raphael.g.axis(250, chartSize - 65, chartSize - 50, null, null, numProjects, 1, projectLabels.reverse(), "|", 0, bidsChart);
};
