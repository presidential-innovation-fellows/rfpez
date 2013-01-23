Rfpez.reporting_stats = (stats) ->
  signupsChart = Raphael('signups-chart', 920, 140)
  signupsChart.barchart(0, 20, 910, 120, [[3,6,12,7,0,4,0,35,0]])
  # Raphael.g.axis(120,153,165,null,null,6,1,stats.raceLabels.reverse(), "|", 0, raceChart)