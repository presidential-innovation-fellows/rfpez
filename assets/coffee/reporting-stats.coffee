Rfpez.reporting_stats = (stats) ->
  flatSignupsArray = for own day, signUps of stats.signupsPerDay
    signUps

  signupsChart = Raphael('signups-chart', 920, 140)
  signupsChart.barchart(0, 20, 910, 120, [flatSignupsArray])

  projectLabels = []
  avgPrices = []
  flatBidsArray = for project in stats.bidsPerProject
    projectLabels.push(project.project_title)
    avgPrices.push(parseInt(project.avg_price))
    parseInt(project.num_bids, 10)


  numProjects = flatBidsArray.length
  chartSize = numProjects*30

  numBidsChart = Raphael('num-bids-chart', 460, chartSize + 10)
  numBidsChart.hbarchart(250, 0, 210, chartSize, [flatBidsArray])
  Raphael.g.axis(250,chartSize - 65,chartSize - 50,null,null,numProjects,1,projectLabels.reverse(), "|", 0, bidsChart)

  priceBidsChart = Raphael('price-bids-chart', 460, chartSize + 10)
  priceBidsChart.hbarchart(0, 0, 450, chartSize, [avgPrices])
  Raphael.g.axis(250,chartSize - 65,chartSize - 50,null,null,numProjects,1,projectLabels.reverse(), "|", 0, bidsChart)