<?php

class TimeSeries  {

  public $freq=false;
  public $name=false;
  public $name_key=0;
  public $name_key2=0;
  public $values=array();
  function TimeSeries($arg){


  if(!is_array($arg) or !(count($arg)==2  or count($arg)==3)  )
      return;
   
    foreach($arg as $key=>$value){
      if($key==0 or preg_match('/freq|freq|f/i',$key)){
	$this->freq=$value;
	if(preg_match('/month|monthly|^m$/i',$value)){
	  $this->freq='Monthly';
	  $this->frequency=12;
	}
	if(preg_match('/week|weekly|^w$/i',$value)){
	  $this->freq='Weekly';
	   $this->frequency=52;
	}
	if(preg_match('/day|daily|^d$/i',$value)){
	    $this->freq='Daily';
	    $this->frequency=365;
	}
	if(preg_match('/day|quarterly|^q$/i',$value)){
	    $this->freq='Qaeterly';
	    $this->frequency=4;
	}
	if(preg_match('/annualy|year|yearly|^y$/i',$value)){
	    $this->freq='yearly';
	    $this->frequency=1;
	}
      }
      if($key==1 or preg_match('/name|n/i',$key)){
	$this->name=$value;
      }
      if($key==3 or preg_match('/key|k|id/i',$key)){
	$this->name_key=$value;
      }

    }
    
    if(!$this->name or !$this->freq)
      return;
  

    if(preg_match('/invoices?/i',$this->name)){
       $this->name='invoices';
       $this->date_field='Invoice Date';
       $this->table='Invoice Dimension';
       $this->value_field='Invoice Total Net Amount';
       $this->max_forecast_bins=12;
      }

  }



function get_values(){
  switch($this->freq){
  case('Monthly'):
    $this->get_values_per_month();

  }

}


function save_values(){
  
  $sql=sprintf("update `Time Series Dimension` set `Time Series Tag`='D' where `Time Series Name` in (%s) and `Time Series Frequency`=%s and `Time Series Name Key`=%d , `Time Series Name Second Key`=%d"
	       ,prepare_mysql($this->name)
	       ,prepare_mysql($this->freq)
	       ,$this->name_key
	       ,$this->name_key2
	       );

$sql=sprintf("insert into `Time Series Dimension` values (%s,%s,%s,%d,%d,%f,%d,'First','','')   ON DUPLICATE KEY UPDATE  `Time Series Value`=%f ,`Time Series Count`=%d ,`Time Series Type`='Data' ,`Time Series Tag`='' "
		,prepare_mysql($this->first['date'])
		,prepare_mysql($this->freq)
		,prepare_mysql($this->name)
		,$this->name_key
		,$this->name_key2
		,$this->first['value']
		,$this->first['count']
		,$this->first['value']
		,$this->first['count']
	       );
   mysql_query($sql);


  foreach($this->values as $date=>$data){
   $sql=sprintf("insert into `Time Series Dimension` values (%s,%s,%s,%d,%d,%f,%d,'Data','','')   ON DUPLICATE KEY UPDATE  `Time Series Value`=%f ,`Time Series Count`=%d ,`Time Series Type`='Data' ,`Time Series Tag`='' "
		,prepare_mysql($date)
		,prepare_mysql($this->freq)
		,prepare_mysql($this->name)
		,$this->name_key
		,$this->name_key2
		,$data['value']
		,$data['count']
		,$data['value']
		,$data['count']
	       );
   mysql_query($sql);
  
  }
  $sql=sprintf("insert into `Time Series Dimension` values (%s,%s,%s,%d,%d,%f,%d,'Current','','')   ON DUPLICATE KEY UPDATE  `Time Series Value`=%f ,`Time Series Count`=%d ,`Time Series Type`='Data' ,`Time Series Tag`='' "
		,prepare_mysql($this->current['date'])
		,prepare_mysql($this->freq)
		,prepare_mysql($this->name)
		,$this->name_key
		,$this->name_key2
		,$this->current['value']
		,$this->current['count']
		,$this->current['value']
		,$this->current['count']
	       );
   mysql_query($sql);

   
  $sql=sprintf("delete from `Time Series Dimension`  where `Time Series Name` in (%s) and `Time Series Frequency`=%s and `Time Series Name Key`=%d and `Time Series Name Second Key`=%d and `Time Series Tag`='D'"
	       ,prepare_mysql($this->name)
	       ,prepare_mysql($this->freq)
	       ,$this->name_key
	       ,$this->name_key2
	       );
  
  mysql_query($sql);
}

function save_forecast(){
  
 $sql=sprintf("delete from `Time Series Dimension`  where `Time Series Name` in (%s) and `Time Series Frequency`=%s and `Time Series Name Key`=%d and `Time Series Name Second Key`=%d and `Time Series Type`='Forecast'"
	       ,prepare_mysql($this->name)
	       ,prepare_mysql($this->freq)
	       ,$this->name_key
	       ,$this->name_key2
	       );
  
  mysql_query($sql);

  foreach($this->forecast as $date=>$data){
   $sql=sprintf("insert into `Time Series Dimension` values (%s,%s,%s,%d,%d,%f,%d,'Forecast','',%s)    "
		,prepare_mysql($date)
		,prepare_mysql($this->freq)
		,prepare_mysql($this->name)
		,$this->name_key
		,$this->name_key2
		,$data['value']
		,$data['count']
		,prepare_mysql($data['deviation'])
	       );
   mysql_query($sql);
   // print $sql;
  }
  
   
 
}


function forecast(){
  
  $this->R_script();

}


function R_script(){



  $values='';
  $count='';
  foreach($this->values as $key=>$data){
    $values.=sprintf(',%f',$data['value']);
    $count.=sprintf(',%d',$data['count']);

  }
  $values=preg_replace('/^,/','',$values);
  $count=preg_replace('/^,/','',$count);

  $script=sprintf("library(forecast,quietly );values=c(%s);",$values);
  $script.=sprintf("ts= ts(values, start=c(%d,%d),frequency = %d);",$this->start_year,$this->start_bin,$this->frequency);
  $script.="arimafit <- auto.arima(ts);fcast <- forecast(arimafit);print(fcast) ;print ('--count data--');";
  $script.=sprintf("values=c(%s);",$count);
  $script.=sprintf("ts= ts(values, start=c(%d,%d),frequency = %d);",$this->start_year,$this->start_bin,$this->frequency);
  $script.="arimafit <- auto.arima(ts);fcast <- forecast(arimafit);print(fcast) ;";
  $cmd = "echo \"$script\" |  R --vanilla --slave -q";
 
$handle = popen($cmd, "r");
  $ret = "";
  do{
    $data = fread($handle, 8192);
    if(strlen($data) == 0){
      break;
    }
    $ret .= $data;
  }
  while(true);
  pclose($handle);

  $ret_data = preg_split('/--count data-/',$ret);
  
  $values_forecast_data=$ret_data[0];
  $count_forecast_data=$ret_data[1];

 $values_forecast_data = preg_split('/\n/',$values_forecast_data);
  $count_forecast_data = preg_split('/\n/',$count_forecast_data);

$read=false;
 $forecast=array();
 
 
 //print_r($values_forecast_data);
 // print_r($count_forecast_data);
$forecast_bins=0;
 foreach($values_forecast_data as $line){
    if($forecast_bins>$this->max_forecast_bins)
        break;
    $line=_trim($line);
    if($read and $line!=''){
         $regex='/^[a-z]{3}\s\d{4}\s*/i';
     if(!preg_match($regex,$line,$match))
       continue;
     $line=preg_replace($regex,'',$line);
     $date=date("Y-m-d",strtotime($match[0]));
     $data=preg_split('/\s/',$line);
     if($data[0]==0)
       $uncertainty=0;
     else
       $uncertainty=($data[4]-$data[3])/(2*$data[0]);
     
     $forecast[$date]=array(
		       'date'=>$date
		       ,'value'=>$data[0]
		       ,'deviation'=>$data[1].','.$data[2].','.$data[3].','.$data[4].','.$uncertainty
		       );
$forecast_bins++;
   }
   
   
   if(preg_match('/Point Forecast/i',$line))
     $read=true;
 } 
 

$forecast_bins=0;
foreach($count_forecast_data as $line){
  if($forecast_bins>$this->max_forecast_bins)
        break;
   $line=_trim($line);
   if($read and $line!=''){
     $regex='/^[a-z]{3}\s\d{4}\s*/i';
      if(!preg_match($regex,$line,$match))
       continue;
     $line=preg_replace($regex,'',$line);
     $date=date("Y-m-d",strtotime($match[0]));
     $data=preg_split('/\s/',$line);
     if($data[0]==0)
       $uncertainty=0;
     else
       $uncertainty=($data[4]-$data[3])/(2*$data[0]);

     $forecast[$date]['count']=round($data[0]);
     $forecast[$date]['deviation'].='|'.round($data[1]).','.round($data[2]).','.round($data[3]).','.round($data[4]).','.$uncertainty;
       $forecast_bins++;

   }
   
   
   if(preg_match('/Point Forecast/i',$line))
     $read=true;
 } 


//print_r($forecast);

 $this->forecast=$forecast;
 $this->save_forecast();


}



function get_values_per_month(){
  
$this->first_complete_month();
  $sql=sprintf("SELECT count(*) as number,`%s` as date ,substring(`%s`, 1,7) AS dd ,sum(`%s`) as value FROM `%s` where `%s`>%s  and `%s`<%s  GROUP BY dd limit 10000"
	       ,$this->date_field,$this->date_field
	       ,$this->value_field
	       ,$this->table
	       ,$this->date_field,prepare_mysql($this->start_date)
	       ,$this->date_field,prepare_mysql(date("Y-m-d"))
	     );
 

 
  $res=mysql_query($sql);
  $this->values=array();
  while($row=mysql_fetch_array($res)){
    if($row['dd']==$this->start_year.'-'.$this->start_bin)
        $this->first=array('date'=>$row['dd'].'-01 12:00:00','count'=>$row['number'],'value'=>$row['value']);
    else if($row['dd']==date("Y-m"))
        $this->current=array('date'=>$row['dd'].'-01 12:00:00','count'=>$row['number'],'value'=>$row['value']);   
    else
        $this->values[$row['dd'].'-01 12:00:00']=array('count'=>$row['number'],'value'=>$row['value']);
  }
  
  print_r($this->values);
  

}



function first_complete_month(){
  $sql=sprintf("select MONTH(`Invoice Date`) as m,YEAR(`Invoice Date`) as y from `Invoice Dimension` order by `Invoice Date` limit 1  ");
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($res)){
    $time=mktime(0, 0, 0, date($row["m"]) , 1, date($row["y"]));
    $this->start_date=date("Y-m-d", $time); 
    $this->start_year=date("Y", $time); 
    $this->start_bin=date("m", $time); 
    $time=mktime(0, 0, 0, date($row["m"])+1 , 1, date($row["y"]));
    $this->first_complete_date=date("Y-m-d", $time); 
    $this->first_complete_year=date("Y", $time); 
    $this->first_complete_bin=date("m", $time); 
   
    
    
  }
  

}

 function last_complete_month(){
   //  return "2009-07-31 23:59:59";
   $sql=sprintf("select MONTH(`Invoice Date`) as m,YEAR(`Invoice Date`) as y from `Invoice Dimension` order by `Invoice Date` desc limit 1  ");
   $res=mysql_query($sql);
  if($row=mysql_fetch_array($res)){
    $last_time=mktime(0, 0, 0, date($row["m"])-1 , -1, date($row["y"]));
    if($last_time>mktime(0, 0, 0, date("m") , date("d"), date("y") ))
      $last_time=mktime(0, 0, 0, date("m") , -1, date("y"));
    return date("Y-m-d 23:59:59", $last_time); 
  }
}
    
}
?>