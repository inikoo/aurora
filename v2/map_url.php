<?php
/*
file: map_url.php
returns the url of the map
 */

require_once 'common.php';
require_once 'class.Product.php';

function get_map_url($region='world',$exclude=false){
  
  
  $exclude="'XX'";
  if(is_array($exclude)){
    foreach($exclude as $code){
      if(preg_match('/[a-z]{2}/i',$code))
	$exclude.=",'".$code."'";
    }
  }  
  $where_region='';
  if(preg_match('/africa|europe|south_america|asia/',$region))
    $region=$region;
    //$where_region=" and `Country Map Region`='".$region."'";
  else
    $region='world';
  
  if($region=='north_america')
    $region='usa';

  $base="http://chart.apis.google.com/chart?chs=440x220&cht=t&chtm=".$region."&chco=d5c37b,e2ede0,00b52f&chf=bg,s,adc7ed";
  
  $sql="select sum(`Invoice Total Net Amount`) as max from `Invoice Dimension` I left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=I.`Invoice Billing Country 2 Alpha Code`)  where I.`Invoice Billing Country 2 Alpha Code` not in (".$exclude.") ".$where_region."   group by `Invoice Billing Country 2 Alpha Code` order by max desc limit 1";
  // print $sql;
  $res = mysql_query ( $sql );
  $MAX_VALUE=0;
   if ($row = mysql_fetch_array ( $res, MYSQL_ASSOC )) {
     $MAX_VALUE=$row['max'];
   }
   $codes='&chld=';
     $values='&chd=t:';
   if($MAX_VALUE!=0){
     $HISTOGRAM_BRACKET = 100;
     $START_HISTOGRAM = 0;
     $TOP_HISTOGRAM = 100; 
     $HISTOGRAM_FACTOR = $MAX_VALUE/$TOP_HISTOGRAM;
     
     $sql="select `Invoice Billing Country 2 Alpha Code` as code, sum(`Invoice Total Net Amount`) as value from `Invoice Dimension` I left join kbase.`Country Dimension` C on (C.`Country 2 Alpha Code`=I.`Invoice Billing Country 2 Alpha Code`)  where `Invoice Billing Country 2 Alpha Code` not in (".$exclude.")  ".$where_region." group by `Invoice Billing Country 2 Alpha Code`";
   
     $res = mysql_query ( $sql );
     
     
     // print "$MAX_VALUE ";
     
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


