
Rfpez.reporting_stats = function(stats) {
  var bidsDataForGchart, drawCharts, priceDataForGchart, project;
  priceDataForGchart = (function() {
    var _i, _len, _ref, _results;
    _ref = stats.avgPrices;
    _results = [];
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      project = _ref[_i];
      _results.push([project.project_title, parseInt(project.avg_price, 10)]);
    }
    return _results;
  })();
  bidsDataForGchart = (function() {
    var _i, _len, _ref, _results;
    _ref = stats.bidsPerProject;
    _results = [];
    for (_i = 0, _len = _ref.length; _i < _len; _i++) {
      project = _ref[_i];
      _results.push([project.project_title, parseInt(project.num_bids, 10)]);
    }
    return _results;
  })();
  drawCharts = function() {
    var bidChart, bidData, bidOptions, priceChart, priceData, priceOptions;
    bidData = google.visualization.arrayToDataTable(bidsDataForGchart);
    bidOptions = {
      title: 'Average number of bids per project',
      legend: {
        position: 'none'
      },
      vAxis: {
        title: 'Project'
      }
    };
    bidChart = new google.visualization.BarChart(document.getElementById('num-bids-chart'));
    bidChart.draw(bidData, bidOptions);
    priceData = google.visualization.arrayToDataTable(priceDataForGchart);
    priceOptions = {
      title: 'Average price of bid per project',
      legend: {
        position: 'none'
      },
      vAxis: {
        title: 'Project'
      }
    };
    priceChart = new google.visualization.BarChart(document.getElementById('price-bids-chart'));
    return priceChart.draw(priceData, priceOptions);
  };
  google.load("visualization", "1", {
    packages: ["corechart"]
  });
  return google.setOnLoadCallback(drawCharts);
};
