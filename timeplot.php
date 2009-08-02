<?php
$frequency='day';
if(isset($_REQUEST['f'])){
$_f=$_REQUEST['f'];
if($_f=='day' or $_f=='d'){
$frequency='daily';
}elseif($_f=='weekly' or $_f=='w'){
$frequency='weekly';
}elseif($_f=='month' or $_f=='m'){
$frequency='monthly';
}

}


$url="timeplot_data.php?tipo=".$frequency."_net_sales";
if(isset($_REQUEST['from']))
   $url.='&from='.$_REQUEST['from'];
if(isset($_REQUEST['until']))
   $url.='&until='.$_REQUEST['until'];


?>

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
 gridColor: "#000000",
        axisLabelsPlacement: "left",

}),

showValues: true


})
];
            
  timeplot = Timeplot.create(document.getElementById("my-timeplot"), plotInfo);
timeplot.loadText("<?php echo $url?>", ",", eventSource);

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
