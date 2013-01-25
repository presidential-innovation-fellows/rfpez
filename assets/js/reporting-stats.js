
Rfpez.reporting_stats = function(stats) {
  var bidChart, bidData, bidOptions, bidsDataForGchart, priceChart, priceData, priceDataForGchart, priceOptions, project, _i, _j, _len, _len1, _ref, _ref1;
  priceDataForGchart = [["project title", "average price"]];
  _ref = stats.avgPrices;
  for (_i = 0, _len = _ref.length; _i < _len; _i++) {
    project = _ref[_i];
    priceDataForGchart.push([project.project_title, parseInt(project.avg_price, 10)]);
  }
  bidsDataForGchart = [["project title", "average bids"]];
  _ref1 = stats.bidsPerProject;
  for (_j = 0, _len1 = _ref1.length; _j < _len1; _j++) {
    project = _ref1[_j];
    bidsDataForGchart.push([project.project_title, parseInt(project.num_bids, 10)]);
  }
  bidData = google.visualization.arrayToDataTable(bidsDataForGchart);
  bidOptions = {
    title: 'Bids per project',
    legend: {
      position: 'none'
    }
  };
  bidChart = new google.visualization.BarChart(document.getElementById('num-bids-chart'));
  bidChart.draw(bidData, bidOptions);
  priceData = google.visualization.arrayToDataTable(priceDataForGchart);
  priceOptions = {
    title: 'Average price of bid per project',
    legend: {
      position: 'none'
    }
  };
  priceChart = new google.visualization.BarChart(document.getElementById('price-bids-chart'));
  return priceChart.draw(priceData, priceOptions);
};
