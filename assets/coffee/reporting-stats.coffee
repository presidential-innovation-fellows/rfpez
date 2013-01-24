Rfpez.reporting_stats = (stats) ->
  flatSignupsArray = for own day, signUps of stats.signupsPerDay
    signUps

  drawChart = ->
    data = google.visualization.arrayToDataTable([
      ['Year', 'Sales', 'Expenses'],
      ['2004',  1000,      400],
      ['2005',  1170,      460],
      ['2006',  660,       1120],
      ['2007',  1030,      540]
    ]);

    options =
      title: 'Company Performance'
      vAxis:
        title: 'Year'
        titleTextStyle:
          color: 'red'

    chart = new google.visualization.BarChart(document.getElementById('signups-chart'));
    chart.draw(data, options);


  google.load "visualization", "1", {packages:["corechart"]}
  google.setOnLoadCallback drawChart

  projectLabels = []
  avgPrices = []
  flatBidsArray = for project in stats.bidsPerProject
    projectLabels.push(project.project_title)
    avgPrices.push(parseInt(project.avg_price))
    parseInt(project.num_bids, 10)
