<div id="time_series" style="width: 100%; height: 250px;padding:20px"></div>

<script>


var request='ar_timeseries.php?tipo=store&parent_key={$store->id}'
console.log(request)
d3.csv(request)
  .row(function(d) {
    d.date = new Date(d.Timestamp * 1000);
    return d;
  })
  .get(function(error, rows) { renderChart(rows); });

function renderChart(data) {
  var chart = fc.chart.linearTimeSeries()
        .xDomain(fc.util.extent(data, 'date'))
        .yDomain(fc.util.extent(data, ['open', 'close']));

  var area = fc.series.area()
        .yValue(function(d) { return d.open; });

  chart.plotArea(area);

  d3.select('#time_series')
        .datum(data)
        .call(chart);
}


</script>
