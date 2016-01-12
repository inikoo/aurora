<div style="padding:5px 20px">
<h1>Plot</h1>
<div style='margin-right: 40px;' class="plot">
    <svg id='time-series' style='height: 400px; width: 100%;  overflow: visible'>
      <defs>
        <linearGradient id="area-gradient"
                        x1="0%" y1="0%"
                        x2="0%" y2="100%">
           <stop offset="0%" stop-opacity="0.3" stop-color="#fff" />
          <stop offset="100%" stop-opacity="0" stop-color="#1a9af9" />
        </linearGradient>
      </defs>
    </svg>
  </div>
</div>
<script>


var request = '/ar_timeseries.php?tipo=csv&id={$timeseries->id}'

d3.csv(request)
  .row(function(d) {
    d.date = new Date(d.timestamp * 1000);
    d.open = parseFloat(d.open)
    d.volume = parseFloat(d.volume)
    return d;
  })
  .get(function(error, rows) { renderChart(rows); });
 
var yAxisWidth = 40,
    xAxisHeight = 20;
 
//var dateFormat = d3.time.format('%');
 
function renderChart(data) {
  
  var movingAverage = fc.indicator.algorithm.exponentialMovingAverage()
        .value(function(d) { return d.open; })
        .windowSize(20);
 
  movingAverage(data);
  
  var container = d3.select('#time-series');
  var volumeContainer = container.selectAll('g.volume')
        .data([data]);
  volumeContainer.enter()
      .append('g')
      .attr({
          'class': 'volume',
      })
      .layout({
          position: 'absolute',
          top: 300,
          bottom: xAxisHeight,
          right: yAxisWidth,
          left: 0
      });

  var layout = fc.layout();
  container.layout();
    
  var volumeScale = d3.scale.linear()
    .domain([0, d3.max(data, function (d) { return Number(d.volume); })])
    .range([volumeContainer.layout('height'), 0]);
  
  
    var emaLine = fc.series.line()
        .yValue(function(d) { return d.exponentialMovingAverage; })
        .decorate(function(g) {
          g.classed('ema', true);
        });
  
  var chart = fc.chart.linearTimeSeries()
        .xDomain(fc.util.extent(data, 'date'))
        .yDomain([0, d3.max(data, function (d) { return Number(d.open); })])
     //   .xTickFormat(dateFormat)
        .yTicks(5)
        .yNice(5)
        .yOrient('right')
        .yTickSize(yAxisWidth, 0)
        .xTickSize(xAxisHeight)
        .xTicks(3);
 
  var area = fc.series.area()
        .y0Value(chart.yDomain()[0])
        .yValue(function(d) { return d.open; });
 
  var line = fc.series.line()
        .yValue(function(d) { return d.open; });
  

 
  var gridlines = fc.annotation.gridline()
        .yTicks(5)
        .xTicks(0);
 
  var multi = fc.series.multi()
        .series([gridlines, emaLine,area]);
        
  chart.plotArea(multi);
 
  d3.select('#time-series')
        .datum(data)
        .call(chart);

  var volume = fc.series.bar()
      .xScale(chart.xScale())
      .yScale(volumeScale)
      .yValue(function(d) { return d.volume; });

  volumeContainer
      .datum(data)
      .call(volume);
        
  d3.selectAll('.y-axis text')
      .style('text-anchor', 'end')
      .attr('transform', 'translate(-3, -8)');
 
  d3.selectAll('.x-axis text')
      .attr('dy', undefined)
      .style({ 'text-anchor': 'start', 'dominant-baseline': 'central'})
      .attr('transform', 'translate(3, -' + (xAxisHeight / 2 + 3) + ' )');
}

</script>