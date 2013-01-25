Rfpez.reporting_stats = (stats) ->

  priceDataForGchart = [["project title", "average price"]]

  for project in stats.avgPrices
    priceDataForGchart.push [project.project_title, parseInt(project.avg_price, 10)]

  bidsDataForGchart = [["project title", "bids"]]

  for project in stats.bidsPerProject
    bidsDataForGchart.push [project.project_title, parseInt(project.num_bids, 10)]

  bidData = google.visualization.arrayToDataTable bidsDataForGchart
  bidOptions =
    title: 'Bids per project'
    legend:
      position: 'none'

  bidChart = new google.visualization.BarChart(document.getElementById('num-bids-chart'));
  bidChart.draw bidData, bidOptions

  priceData = google.visualization.arrayToDataTable priceDataForGchart
  priceOptions =
    title: 'Average price of bid per project'
    legend:
      position: 'none'

  priceChart = new google.visualization.BarChart(document.getElementById('price-bids-chart'));
  priceChart.draw priceData, priceOptions



  data = [['Date', '# of signups', '% of vendors new to contracting']]

  for key, val of stats.signupsPerDay
    data.push [key, val, stats.newVendorPercentage[key]]

  data = google.visualization.arrayToDataTable data

  options =
    title: "Vendor Signups"
    vAxes:
      0: {logScale: false}
      1: {logScale: false, maxValue: 1}
    series:
      0: {targetAxisIndex:0}
      1: {targetAxisIndex:1}



  signupsLineChart = new google.visualization.LineChart(document.getElementById('signups-line-chart'))
  signupsLineChart.draw(data, options)
