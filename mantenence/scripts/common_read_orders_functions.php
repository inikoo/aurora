<?php 
function get_dates($filedate,$header_data,$tipo_order,$new_file=true){

  $datetime_updated=date("Y-m-d H:i:s",$filedate);
  $time_updated_menos30min=date("H:i:s",$filedate-1800);

  list($date_updated,$time_updated)=preg_split('/\s/',$datetime_updated);
  if($new_file){
    if($tipo_order==2  or $tipo_order==6 or $tipo_order==7 or $tipo_order==9  or $tipo_order==8   ){
      
      //print_r($header_data);
      if($header_data['date_inv']=='' or $header_data['date_inv']=='1970-01-01')
	$header_data['date_inv']=$header_data['date_order'];
      
      if($date_updated ==$header_data['date_inv']){
	
	$date_charged=$date_updated." ".$time_updated;

	$date_processed=$header_data['date_order']." 09:30:00";
	if(strtotime($date_processed)>strtotime($date_charged))
	  $date_processed=$header_data['date_order']." ".$time_updated_menos30min;

      }else{
	$date_charged=$header_data['date_inv']." 16:30:00";
	$date_processed=$header_data['date_order']." 09:30:00";
      }
      $date_index=$date_charged;
    }else{


      $date_charged="NULL";



      if($date_updated ==$header_data['date_order']){
	//print $header_data['date_order']." xssssssssssssxx";
	$date_processed=$date_updated." ".$time_updated;
	// print "$date_processed  xssssssssssssxx\n";

      }
      else
	$date_processed=$header_data['date_order']." 08:30:00";
      $date_index=$date_processed;

      // print $date_index." xxx\n";

    }
  }
  //  print "$date_index,$date_processed,$date_charged\n";
  return array($date_index,$date_processed,$date_charged);

}

?>