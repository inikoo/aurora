<?
/*
 File: timeplot_data.php 

 returns plain text data for the time serioes plots

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
require_once 'common.php';

if (!$LU or !$LU->isLoggedIn()) {
  $response=array('state'=>402,'resp'=>_('Forbidden'));
  echo json_encode($response);
  exit;
 }


if(!isset($_REQUEST['tipo']))
  {
    exit;
  }

$tipo=$_REQUEST['tipo'];

$just_values=false;
if(isset($_REQUEST['just_values']) or isset($_REQUEST['values_only']))
  $just_values=true;


if(isset($_REQUEST['until']) and  preg_match('/^\d{8}$/',$_REQUEST['until'])  ){
  $a=$_REQUEST['until'];
  $y=substr($a, 0, 4);
  $m=substr($a, 4, 2);
  $d=substr($a, 6, 2);
  $until=strtotime("$y-$m-$d");
}else
  $until=strtotime("today");

$from=0;
if(isset($_REQUEST['from']) and  preg_match('/^\d{8}$/',$_REQUEST['from']) ){
  $a=$_REQUEST['from'];
  $y=substr($a, 0, 4);
  $m=substr($a, 4, 2);
  $d=substr($a, 6, 2);
  $from=strtotime("$y-$m-$d");
}



switch($tipo){
case('daily_net_sales'):

  if($from)
    $sql=sprintf("select D.`Date` as date from `Date Dimension` D where Date>'%s' and Date<='%s'",date("Y-m-d",$from),date("Y-m-d",$until));
  else
    $sql=sprintf("select D.`Date` as date from `Date Dimension` D where Date>(select min(`Invoice Date`) from `Invoice Dimension`) and Date<=%s",date("Y-m-d",$until));
  
    $sql=sprintf("select D.`Date` as date from `Date Dimension` D where Date>'%s' and Date<='%s'",date("Y-m-d",$from),date("Y-m-d",$until));
  
    //  print $sql;
    $res = mysql_query($sql); 
  while($row=mysql_fetch_array($res)) {
    $data[$row['date']]=0;
  }
  
 if($from)
 $sql=sprintf("select DATE(`Invoice Date`)   as date, sum(`Invoice Total Net Amount`) as net  from `Invoice Dimension`  where  `Invoice Date`>='%s' and `Invoice Date`<='%s'   group by DATE(`Invoice Date`) ",date("Y-m-d",$from),date("Y-m-d",$until));
  else

  $sql=sprintf("select DATE(`Invoice Date`)   as date, sum(`Invoice Total Net Amount`) as net  from `Invoice Dimension`  where `Invoice Date`<='%s'   group by DATE(`Invoice Date`) ",date("Y-m-d",$until));
  $res = mysql_query($sql); 
  while($row=mysql_fetch_array($res)) {
    $data[$row['date']]=$row['net'];
  }

  foreach($data as $key => $value){
    if($just_values)
      print "$value\n";
    else
      print "$key,$value\n";
  }
  break;
case('first_month'):
  $sql=sprintf("select  DATE_FORMAT(MIN(`Invoice Date`),'%%Y%%m') as date from `Invoice Dimension`");
  $res = mysql_query($sql); 
  if($row=mysql_fetch_array($res)) {
    print $row['date'];
  }else
    print date("Ym");
  break;
case('monthly_net_sales'):

if($from)
    $sql=sprintf("select `Year Month` as date,`Last Day` from `Month Dimension` D where `Year Month`>='%s' and `Year Month`<='%s'",date("Ym",$from),date("Ym",$until));
  else
  $sql=sprintf("select `Year Month` as date,`Last Day` from `Month Dimension` D where `Year Month`>=(select  DATE_FORMAT(MIN(`Invoice Date`),'%%Y%%m') from `Invoice Dimension`) and `Year Month`<='%s'",date("Ym",$until));
//print $sql;
  $res = mysql_query($sql); 
  while($row=mysql_fetch_array($res)) {
    $data[$row['date']]=0;
    $_date[$row['date']]=$row['Last Day'];
  }
  
if($from)
  $sql=sprintf("select DATE_FORMAT(`Invoice Date`,'%%Y%%m')   as date, sum(`Invoice Total Net Amount`) as net  from `Invoice Dimension`   where `Invoice Date`>='%s'    group by  DATE_FORMAT(`Invoice Date`,'%%Y%%m')   ",date("Y-m-d",$from));
else
 $sql=sprintf("select DATE_FORMAT(`Invoice Date`,'%%Y%%m')   as date, sum(`Invoice Total Net Amount`) as net  from `Invoice Dimension`  group by  DATE_FORMAT(`Invoice Date`,'%%Y%%m')   ");
 // print $sql;
  $res = mysql_query($sql); 
  while($row=mysql_fetch_array($res)) {
     $data[$row['date']]=$row['net'];
  }

  //  print_r($data);
  foreach($data as $key => $value){
    if($just_values)
      print "$value\n";
    else
      print $_date[$key].",$value\n";
  }
  break;
case('weekly_net_sales'):

  
  if($from)
    $sql=sprintf("select `Year Week Normalized` ,`Year Week`,`Last Day` from `Week Dimension` D where `Year Week`>='%s'  and  `Year Week`<='%s'",date("YW",$from),yearweek(date("Y-m-d",$until)));
  else
    $sql=sprintf("select `Year Week Normalized` ,`Year Week`,`Last Day` from `Week Dimension` D where `Year Week`>=(select YEARWEEK(MIN(`Invoice Date`),3)  from `Invoice Dimension`) and  `Year Week`<='%s'",date("YW",$until));
  // print "---> ".yearweek(date("Y-m-d",$until))." $sql\n";exit;
  $res = mysql_query($sql); 
  while($row=mysql_fetch_array($res)) {
    $yearweek[$row['Year Week']]=$row['Year Week Normalized'];
    $_date[$row['Year Week Normalized']]=$row['Last Day'];
    $data[$row['Year Week Normalized']]=0;
  }
  
  
  if($from)
    $sql=sprintf("select  `Invoice Date`,YEARWEEK(`Invoice Date`,3)    as date, sum(`Invoice Total Net Amount`) as net  from `Invoice Dimension`   where YEARWEEK(`Invoice Date`,3)>='%s' and  YEARWEEK(`Invoice Date`,3)<='%s'   group by  YEARWEEK(`Invoice Date`,3)   ",date("YW",$from),yearweek(date("Y-m-d",$until)));
  else
    $sql=sprintf("select  `Invoice Date`,YEARWEEK(`Invoice Date`,3)    as date, sum(`Invoice Total Net Amount`) as net  from `Invoice Dimension` and  YEARWEEK(`Invoice Date`,3)<='%s'  group by  YEARWEEK(`Invoice Date`,3)   ",date("Y-m-d",$until));
  // print $sql;
  $res = mysql_query($sql); 
  while($row=mysql_fetch_array($res)) {
     $data[$yearweek[$row['date']]]=$row['net'];
  }

  //  print_r($data);
  foreach($data as $key => $value){
    if($just_values)
      print "$value\n";
    else
      print $_date[$key].",$value\n";
  }
  break;


}
?>