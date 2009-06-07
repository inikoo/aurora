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
switch($tipo){
case('daily_net_sales'):

  //  $sql="select min(`Invoice Date`) from `Invoice Dimension`";
  

  //$sql=sprintf("select D.`Date` as date,(select sum(`Invoice Total Net Amount`) from `Invoice Dimension` where DATE(`Invoice Date`)=D.Date ) as net from `Date Dimension` D where Date>(select min(`Invoice Date`) from `Invoice Dimension`) and Date<=NOW()";
  $sql=sprintf("select D.`Date` as date from `Date Dimension` D where Date>(select min(`Invoice Date`) from `Invoice Dimension`) and Date<=NOW()");
  $res = mysql_query($sql); 
  while($row=mysql_fetch_array($res)) {
    $data[$row['date']]=0;
  }
  
 $sql=sprintf("select DATE(`Invoice Date`)   as date, sum(`Invoice Total Net Amount`) as net  from `Invoice Dimension`  group by DATE(`Invoice Date`) ");
  $res = mysql_query($sql); 
  while($row=mysql_fetch_array($res)) {
    $data[$row['date']]=$row['net'];
  }

  foreach($data as $key => $value){
    print "$key,$value\n";
  }

}
?>