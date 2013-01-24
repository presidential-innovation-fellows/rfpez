Rfpez.reporting_stats = (stats) ->


  priceDataForGchart = for project in stats.avgPrices
    [project.project_title, parseInt(project.avg_price, 10)]

  bidsDataForGchart = for project in stats.bidsPerProject
    [project.project_title, parseInt(project.num_bids, 10)]

  drawCharts = ->

    bidData = google.visualization.arrayToDataTable bidsDataForGchart
    bidOptions =
      title: 'Average number of bids per project'
      legend:
        position: 'none'
      vAxis:
        title: 'Project'

    bidChart = new google.visualization.BarChart(document.getElementById('num-bids-chart'));
    bidChart.draw bidData, bidOptions

    priceData = google.visualization.arrayToDataTable priceDataForGchart
    priceOptions =
      title: 'Average price of bid per project'
      legend:
        position: 'none'
      vAxis:
        title: 'Project'

    priceChart = new google.visualization.BarChart(document.getElementById('price-bids-chart'));
    priceChart.draw priceData, priceOptions

  google.load "visualization", "1", {packages:["corechart"]}
  google.setOnLoadCallback drawCharts

