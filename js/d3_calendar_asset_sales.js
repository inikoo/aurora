function d3_calendar_init(){
var t=Dom.get('valid_from').value.split(/[- :]/);
from = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);

var t=Dom.get('valid_to').value.split(/[- :]/);
to = new Date(t[0], t[1]-1, t[2], t[3], t[4], t[5]);



var width = 960,
    height = 136,
    cellSize = 17; // cell size

var day = d3.time.format("%w"),
    week = d3.time.format("%U"),
    percent = d3.format(".1%"),
    format = d3.time.format("%Y-%m-%d");

var color = d3.scale.quantize()
    .domain([0, Dom.get('sales_max_sample_domain').value])
    .range(d3.range(9).map(function(d) { return "q" + d + "-9"; }));

var svg = d3.select("#d3_calendar_asset_sales").selectAll("svg")
    .data(d3.range(from.getFullYear(),to.getFullYear()+1).reverse())
  .enter().append("svg")
    .attr("width", width)
    .attr("height", height)
    .attr("class", "PuBuGn")
  .append("g")
    .attr("transform", "translate(" + ((width - cellSize * 53) / 2) + "," + (height - cellSize * 7 - 1) + ")");


svg
  .append('defs')
  .append('pattern')
    .attr('id', 'diagonalHatchx')
    .attr('patternUnits', 'userSpaceOnUse')
    .attr('width', cellSize/4)
    .attr('height', cellSize/4)
    .attr('x', 0)
    .attr('y', 0)
     .append('circle')

    .attr('cx', cellSize/7)
    .attr('cy', cellSize/7)
    .attr('r', cellSize/12)
    .attr('fill', "#ddd")
    
    svg
  .append('defs')
  .append('pattern')
    .attr('id', 'diagonalHatch')
    .attr('patternUnits', 'userSpaceOnUse')
    .attr('width', 8)
    .attr('height', 8)
    
     .append('path')

    .attr('d', "M-1,1 l4,-4 M0,8 l8,-8 M7,10 l4,-4")
       .attr('stroke', "#ddd")
    .attr('stroke-width','1')

svg.append("text")
    .attr("transform", "translate(-6," + cellSize * 3.5 + ")rotate(-90)")
    .style("text-anchor", "middle")
    .text(function(d) { return d; });

var rect = svg.selectAll(".day")
    .data(function(d) { return d3.time.days(new Date(d, 0, 1), new Date(d + 1, 0, 1)); })
  .enter().append("rect")
    .attr("class", function(d)  {if(d>from && d<to){return "day"}else{ return "day_outside"}} )
    .attr('fill', 'url(#diagonalHatch)')
    .attr("width", cellSize)
    .attr("height", cellSize)
    .attr("x", function(d) { return week(d) * cellSize; })
    .attr("y", function(d) { return day(d) * cellSize; })
    .datum(format);
    
  

rect.append("title")
    .text(function(d) { return d; });


svg.selectAll(".month")
    .data(function(d) { return d3.time.months(new Date(d, 0, 1), new Date(d + 1, 0, 1)); })
  .enter().append("path")
    .attr("class", "month")
    .attr("d", monthPath);


request="csv/assets.csv.php?tipo=asset_sales&parent="+Dom.get('subject').value+'&parent_key='+Dom.get('subject_key').value+"&from=&to="
//alert(request)

d3.csv(request, function(error, csv) {
  var data = d3.nest()
    .key(function(d) {return d.date; })
    .rollup(function(d) { return d[0].sales })
    .map(csv);

  rect.filter(function(d) { return d in data; })
      .attr("class", function(d) { return "day " + color(data[d]); })
    .select("title")
      .text(function(d) { return d + ": " + data[d]; });
});


function monthPath(t0) {
  var t1 = new Date(t0.getFullYear(), t0.getMonth() + 1, 0),
      d0 = +day(t0), w0 = +week(t0),
      d1 = +day(t1), w1 = +week(t1);
   mpath="M" + (w0 + 1) * cellSize + "," + d0 * cellSize
      + "H" + w0 * cellSize + "V" + 7 * cellSize
      + "H" + w1 * cellSize + "V" + (d1 + 1) * cellSize
      + "H" + (w1 + 1) * cellSize + "V" + 0
      + "H" + (w0 + 1) * cellSize + "Z";
//alert(mpath)
return mpath;
}

d3.select(self.frameElement).style("height", "2910px");


}

YAHOO.util.Event.onDOMReady(d3_calendar_init);