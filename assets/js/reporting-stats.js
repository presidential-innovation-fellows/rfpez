var __hasProp = {}.hasOwnProperty;

Rfpez.reporting_stats = function(stats) {
  var avgPrices, day, drawChart, flatBidsArray, flatSignupsArray, project, projectLabels, signUps;
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
  drawChart = function() {
    var chart, data, options;
    data = google.visualization.arrayToDataTable([['Year', 'Sales', 'Expenses'], ['2004', 1000, 400], ['2005', 1170, 460], ['2006', 660, 1120], ['2007', 1030, 540]]);
    options = {
      title: 'Company Performance',
      vAxis: {
        title: 'Year',
        titleTextStyle: {
          color: 'red'
        }
      }
    };
    chart = new google.visualization.BarChart(document.getElementById('signups-chart'));
    return chart.draw(data, options);
  };
  google.load("visualization", "1", {
    packages: ["corechart"]
  });
  google.setOnLoadCallback(drawChart);
  projectLabels = [];
  avgPrices = [];
  return flatBidsArray = (function() {
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
};
