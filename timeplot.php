<html>
  <head>

    <script src="http://api.simile-widgets.org/timeplot/1.1/timeplot-api.js" 
       type="text/javascript"></script>

<script>
var timeplot;

function onLoad() {
var eventSource = new Timeplot.DefaultEventSource();

  var plotInfo = [
    Timeplot.createPlotInfo({
      id: "plot1",
dataSource: new Timeplot.ColumnSource(eventSource,1),
 timeGeometry: new Timeplot.DefaultTimeGeometry({
        gridColor: "#000000",
        axisLabelsPlacement: "top"
      }),

valueGeometry: new Timeplot.DefaultValueGeometry({
min: 000,
max: 1000
}),

showValues: true


})
];
            
  timeplot = Timeplot.create(document.getElementById("my-timeplot"), plotInfo);
timeplot.loadText("timeplot_data.php?tipo=daily_net_sales", ",", eventSource);

}

var resizeTimerID = null;
function onResize() {
    if (resizeTimerID == null) {
        resizeTimerID = window.setTimeout(function() {
            resizeTimerID = null;
            timeplot.repaint();
        }, 100);
    }
}

</script>

  </head>
  <body  onload="onLoad();" onresize="onResize();" >
<div id="my-timeplot" style="height: 250px;"></div>
  </body>
</html>
