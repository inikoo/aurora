<?
/*
file: map_url.php
returns the url of the map
 */

require_once 'common.php';
require_once 'classes/Product.php';

function get_map_url($tipo=false){
  switch($tipo){
  default:
   $base="http://chart.apis.google.com/chart?chs=440x220&cht=t&chtm=europe&chco=d5c37b,e2ede0,00b52f&chf=bg,s,adc7ed";
   
   $sql="select sum(`Invoice Total Net Amount`) as max from `Invoice Dimension` where `Invoice Billing Country 2 Alpha Code` not in ('XX','GB')   group by `Invoice Billing Country 2 Alpha Code` order by max desc limit 1";
   $res = mysql_query ( $sql );

   if ($row = mysql_fetch_array ( $res, MYSQL_ASSOC )) {
     $MAX_VALUE=$row['max'];
   }

   if($MAX_VALUE!=0){
     $HISTOGRAM_BRACKET = 100;
     $START_HISTOGRAM = 0;
     $TOP_HISTOGRAM = 100; 
     $HISTOGRAM_FACTOR = $MAX_VALUE/$TOP_HISTOGRAM;
     
     $sql="select `Invoice Billing Country 2 Alpha Code` as code, sum(`Invoice Total Net Amount`) as value from `Invoice Dimension` where `Invoice Billing Country 2 Alpha Code` not in ('XX','GB')  group by `Invoice Billing Country 2 Alpha Code`";
   
     $res = mysql_query ( $sql );
     
     
     // print "$MAX_VALUE ";
     $codes='&chld=';
     $values='&chd=t:';
     $division = array();
     while ($row = mysql_fetch_assoc($res)) {
       if ($row['value'] >= $START_HISTOGRAM) {
	 $n_value = ceil($HISTOGRAM_BRACKET*$row['value'] /$MAX_VALUE);
	 //	 print $row['code']." ".$row['value']."\n";
	 //$division[$row['code']]=$n_value;
	 $codes.=$row['code'];
	 $values.=$n_value.',';
       }
     } 
     
     $values=preg_replace('/\,$/','',$values);
   }
   $base.=$codes.$values;
   
   return $base;
  }

}


