<?php
include_once('class.Store.php');
include_once('class.Family.php');
include_once('class.Department.php');


Class TimeSeries  {

  public $freq=false;
  public $name=false;
  public $name_key=0;
  public $name_key2=0;
  public $parent_key=0;
  public $values=array();
  public $error=false;
  public $label='';
  public $no_negative_values=true;
  public $no_data=true;
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
	    $this->freq='Quaterly';
	    $this->frequency=4;
	}
	if(preg_match('/annualy|year|yearly|^y$/i',$value)){
	    $this->freq='Yearly';
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
       $this->count='count(*)';
       $this->date_field='`Invoice Date`';
       $this->table='`Invoice Dimension`';
       $this->value_field='`Invoice Total Net Amount`';
       $this->max_forecast_bins=12;
       $this->where='';
       $this->label=_('Sales');
    }elseif(preg_match('/(product department|department) \((\d|,)+\) sales?/i',$this->name,$match)){
     
      $department_key_array=array();
      if(preg_match('/\(.+\)/',$match[0],$keys)){
	  $keys=preg_replace('/\(|\)/','',$keys[0]);
	  $keys=preg_split('/\s*,\s*/',$keys);

	  $department_keys='(';
	 
	  foreach($keys as $key){
	    if(is_numeric($key)){
	      $department_keys.=sprintf("%d,",$key);
	      $department_key_array[]=$key;

	    }
	  }
	  

	  $department_keys=preg_replace('/,$/',')',$department_keys);
	  
      }
      $num_keys=count($department_key_array);
      if( $num_keys==0){
	$this->error=true;
	return;
      }
      // print "--------";

      if($num_keys>1){
	$this->name='PDS'.$department_keys;
	foreach( $department_key_array as $key){
	  $department=new Department($this->name_key);
	  $this->label.=','.$department->data['Product Department Code'];
	}
	$this->label=preg_replace('/^,/','',$this->label);
      }else{
	$this->name='PDS';
	$this->name_key=preg_replace('/\(|\)/','',$department_keys);
	$department=new Department($this->name_key);
	$this->label=$department->data['Product Department Code'];
	$this->parent_key=$department->data['Product Department Store Key'];
      }

      $this->count='count(Distinct `Order Key`)';
      $this->date_field='`Invoice Date`';
      $this->table='`Order Transaction Fact` OTF left join `Product Dimension` P  on (OTF.`Product Key`=P.`Product Key`)  ';
      $this->value_field="`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`";
      $this->where=sprintf(" and `Product Main Department Key` in %s ",$department_keys);
      $this->max_forecast_bins=12;
    }elseif(preg_match('/(product family|family) \((\d|,)+\) sales?/i',$this->name,$match)){
     
      $family_key_array=array();
      if(preg_match('/\(.+\)/',$match[0],$keys)){
	  $keys=preg_replace('/\(|\)/','',$keys[0]);
	  $keys=preg_split('/\s*,\s*/',$keys);

	  $family_keys='(';
	 
	  foreach($keys as $key){
	    if(is_numeric($key)){
	      $family_keys.=sprintf("%d,",$key);
	      $family_key_array[]=$key;

	    }
	  }
	  

	  $family_keys=preg_replace('/,$/',')',$family_keys);
	  
      }
      $num_keys=count($family_key_array);
      if( $num_keys==0){
	$this->error=true;
	return;
      }


    

      if($num_keys>1){
	$this->name='PFS'.$family_keys;
	foreach( $family_key_array as $key){
	  $family=new Family($this->name_key);
	  $this->label.=','.$family->data['Product Family Code'];
	}
	$this->label=preg_replace('/^,/','',$this->label);
      }else{
	$this->name='PFS';
	$this->name_key=preg_replace('/\(|\)/','',$family_keys);
	$family=new Family($this->name_key);
	$this->label=$family->data['Product Family Code'];
	$this->parent_key=$family->data['Product Family Main Department Key'];
      }

      $this->count='count(Distinct `Order Key`)';
      $this->date_field='`Invoice Date`';
      $this->table='`Order Transaction Fact` OTF left join `Product Dimension` P  on (OTF.`Product Key`=P.`Product Key`)  ';
      $this->value_field="`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`";
      $this->where=sprintf(" and `Product Family Key` in %s ",$family_keys);
      $this->max_forecast_bins=12;
    }elseif(preg_match('/store \((\d|,)+\) sales?/i',$this->name,$match)){
     
      $store_key_array=array();
      if(preg_match('/\(.+\)/',$match[0],$keys)){
	  $keys=preg_replace('/\(|\)/','',$keys[0]);
	  $keys=preg_split('/\s*,\s*/',$keys);

	  $store_keys='(';
	 
	  foreach($keys as $key){
	    if(is_numeric($key)){
	      $store_keys.=sprintf("%d,",$key);
	      $store_key_array[]=$key;

	    }
	  }
	  

	  $store_keys=preg_replace('/,$/',')',$store_keys);
	  
      }
      
      $num_keys=count($store_key_array);
      if($num_keys==0){
	$this->error=true;
	return;
      }
  
      if($num_keys>1){
	$this->name='SS'.$store_keys;
	foreach( $store_key_array as $key){
	  $store=new Store($this->name_key);
	  $this->label.=','.$store->data['Store Code'];
	}
	$this->label=preg_replace('/^,/','',$this->label);
      }else{
	$this->name='SS';
	$this->name_key=preg_replace('/\(|\)/','',$store_keys);
	$store=new Store($this->name_key);
	$this->label=$store->data['Store Code'];
      }

      $this->count='count(Distinct `Order Key`)';
      $this->date_field='`Invoice Date`';
      $this->table='`Order Transaction Fact` OTF left join `Product Dimension` P  on (OTF.`Product Key`=P.`Product Key`)  ';
      $this->value_field="`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`";
      $this->where=sprintf(" and `Store Key` in %s ",$store_keys);
      $this->max_forecast_bins=12;
    }elseif(preg_match('/product id \((\d|,)+\) sales?/i',$this->name,$match)){
      $product_key_array=array();
      if(preg_match('/\(.+\)/',$match[0],$keys)){
	  $keys=preg_replace('/\(|\)/','',$keys[0]);
	  $keys=preg_split('/\s*,\s*/',$keys);
	  $product_keys='(';
	  foreach($keys as $key){
	    if(is_numeric($key)){
	      $product_keys.=sprintf("%d,",$key);
	      $product_key_array[]=$key;
	    }
	  }
	  $product_keys=preg_replace('/,$/',')',$product_keys);
      }
      $num_keys=count($product_key_array);
      if( $num_keys==0){
	$this->error=true;
	return;
      }

      if($num_keys>1){
	$this->name='PidS'.$product_keys;
	foreach( $product_key_array as $key){
	  $product=new Product($this->name_key);
	  $this->label.=','.$product->data['Product Code']." (".$product->data['Product ID'].")";
	}
	$this->label=preg_replace('/^,/','',$this->label);
      }else{
	$this->name='PidS';
	$this->name_key=preg_replace('/\(|\)/','',$product_keys);
	$product=new Product($this->name_key);
	$this->label=$product->data['Product Code']." (".$product->data['Product ID'].")";
	$this->parent_key=$product->data['Product Family Key'];
      }

      $this->count='count(Distinct `Order Key`)';
      $this->date_field='`Invoice Date`';
      $this->table='`Order Transaction Fact` OTF left join `Product Dimension` P  on (OTF.`Product Key`=P.`Product Key`)  ';
      $this->value_field="`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`";
      $this->where=sprintf(" and `Product ID` in %s ",$product_keys);
      $this->max_forecast_bins=12;
    }elseif(preg_match('/product code \((\d|,)+\) sales?/i',$this->name,$match)){
      $product_key_array=array();
      if(preg_match('/\(.+\)/',$match[0],$keys)){
	  $keys=preg_replace('/\(|\)/','',$keys[0]);
	  $keys=preg_split('/\s*,\s*/',$keys);
	  $product_keys='(';
	  foreach($keys as $key){
	    if(is_numeric($key)){
	      $product_keys.=sprintf("%d,",$key);
	      $product_key_array[]=$key;
	    }
	  }
	  $product_keys=preg_replace('/,$/',')',$product_keys);
      }
      $num_keys=count($product_key_array);
      if( $num_keys==0){
	$this->error=true;
	return;
      }

      if($num_keys>1){
	$this->name='PcodeS'.$product_keys;
	$product_codes="(";
	foreach( $product_key_array as $key){
	  $product=new Product($this->name_key);
	  $this->label.=','.$product->data['Product Code'];
	  $product_codes.=prepare_mysql($product->data['Product Code']).",";
	}
	$product_codes=preg_replace('/,$/',')',$product_codes);
	$this->label=preg_replace('/^,/','',$this->label);
      }else{
	$this->name='PcodeS';
	$this->name_key=preg_replace('/\(|\)/','',$product_keys);
	$product=new Product($this->name_key);
	$this->label=$product->data['Product Code'];
	$this->parent_key=$product->data['Product Family Key'];
	$product_codes="(".prepare_mysql($product->data['Product Product Code']).")";
      }

      $this->count='count(Distinct `Order Key`)';
      $this->date_field='`Invoice Date`';
      $this->table='`Order Transaction Fact` OTF left join `Product Dimension` P  on (OTF.`Product Key`=P.`Product Key`)  ';
      $this->value_field="`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`";
      $this->where=sprintf(" and `Product Code` in %s ",$product_codes);
      $this->max_forecast_bins=12;
    }


  }







function get_values(){
  switch($this->freq){
  case('Monthly'):
    $this->get_values_per_month();
    break;
  case('Yearly'):
    $this->get_values_per_year();
    break;
  case('Quarterly'):
    $this->get_values_per_month();
    break;
  }

}


function save_values(){

  if($this->no_data)
    return;
  
  $sql=sprintf("update `Time Series Dimension` set `Time Series Tag`='D' where `Time Series Name` in (%s) and `Time Series Frequency`=%s and `Time Series Name Key`=%d  and `Time Series Name Second Key`=%d  "
	       ,prepare_mysql($this->name)
	       ,prepare_mysql($this->freq)
	       ,$this->name_key
	       ,$this->name_key2

	       );

$sql=sprintf("insert into `Time Series Dimension` values (%s,%s,%s,%d,%d,%d,%s,%f,%d,'First','','')   ON DUPLICATE KEY UPDATE  `Time Series Value`=%f ,`Time Series Count`=%d ,`Time Series Type`='First' ,`Time Series Tag`='',`Time Series Parent Key`=%d "
	     ,prepare_mysql($this->first['date'])
	     ,prepare_mysql($this->freq)
	     ,prepare_mysql($this->name)
	     ,$this->name_key
	     ,$this->name_key2
	     ,$this->parent_key
	     ,prepare_mysql($this->label)
	     ,$this->first['value']
	     ,$this->first['count']
	     ,$this->first['value']
	     ,$this->first['count']
	     ,$this->parent_key
	     );
   mysql_query($sql);
   

  foreach($this->values as $date=>$data){
   $sql=sprintf("insert into `Time Series Dimension` values (%s,%s,%s,%d,%d,%d,%s,%f,%d,'Data','','')   ON DUPLICATE KEY UPDATE  `Time Series Value`=%f ,`Time Series Count`=%d ,`Time Series Type`='Data' ,`Time Series Tag`='' ,`Time Series Parent Key`=%d "
		,prepare_mysql($date)
		,prepare_mysql($this->freq)
		,prepare_mysql($this->name)
		,$this->name_key
		,$this->name_key2
		,$this->parent_key
		,prepare_mysql($this->label)
		,$data['value']
		,$data['count']
		,$data['value']
		,$data['count']
		,$this->parent_key
	       );
   mysql_query($sql);
   //print "$sql<br>";
  } 
  $sql=sprintf("insert into `Time Series Dimension` values (%s,%s,%s,%d,%d,%d,%s,%f,%d,'Current','','')   ON DUPLICATE KEY UPDATE  `Time Series Value`=%f ,`Time Series Count`=%d ,`Time Series Type`='Current' ,`Time Series Tag`='' ,`Time Series Parent Key`=%d "
		,prepare_mysql($this->current['date'])
		,prepare_mysql($this->freq)
		,prepare_mysql($this->name)
		,$this->name_key
		,$this->name_key2
	       	,$this->parent_key
 ,prepare_mysql($this->label)
		,$this->current['value']
		,$this->current['count']
		,$this->current['value']
		,$this->current['count']
	       	,$this->parent_key
	       );
   mysql_query($sql);
 
   
  $sql=sprintf("delete from `Time Series Dimension`  where `Time Series Name` in (%s) and `Time Series Frequency`=%s and `Time Series Name Key`=%d and `Time Series Name Second Key`=%d and `Time Series Tag`='D'"
	       ,prepare_mysql($this->name)
	       ,prepare_mysql($this->freq)
	       ,$this->name_key
	       ,$this->name_key2
 
	       );
  
  mysql_query($sql);
  // exit;
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
   $sql=sprintf("insert into `Time Series Dimension` values (%s,%s,%s,%d,%d,%d,%s,%f,%d,'Forecast','',%s)    "
		,prepare_mysql($date)
		,prepare_mysql($this->freq)
		,prepare_mysql($this->name)
		,$this->name_key
		,$this->name_key2
		,$this->parent_key
		,prepare_mysql($this->label)		
		,$data['value']
		,$data['count']
		,prepare_mysql($data['deviation'])
	       );
   mysql_query($sql);
   // print $sql;
  }
  
   
 
}


function forecast(){

  if($this->no_data)
    return;

  $only_zero_values=true;
  foreach($this->values as $key=>$data){
    if($data['value']!=0 or $data['count']!=0){
      $only_zero_values=false;
      break;
    }
  }
  if($only_zero_values){
    
  }else{
    if($this->freq=='Monthly')
      $this->R_script();
    else{
      $this->forecast_using_monthly_data();

    }
  }
}

function forecast_using_monthly_data(){
  exit("todo");
}

function R_script(){

$read=false;
 $forecast=array();

  $values='';
  $count='';
  foreach($this->values as $key=>$data){
    $values.=sprintf(',%f',$data['value']);
    $count.=sprintf(',%d',$data['count']);
  }
  
  $number_values=count($this->values);
  print "values : $number_values\n";

  if($number_values<=1)
    return;
  elseif($number_values<=3){
    $number_period_for_forecasting=1;
  }elseif($number_values<=5){
    $number_period_for_forecasting=2;
  }elseif($number_values<=7){
    $number_period_for_forecasting=3;
  }elseif($number_values<=9){
    $number_period_for_forecasting=4;
  }elseif($number_values<=11){
    $number_period_for_forecasting=6;
   if(date("m")==5 )
      $number_period_for_forecasting=7;
  }elseif($number_values<=48){
    $number_period_for_forecasting=12;
    if(date("m")==12 )
      $number_period_for_forecasting=13;

  }elseif($number_values<=72){
    $number_period_for_forecasting=24;
     if(date("m")==12 )
      $number_period_for_forecasting=15;
  }else{
    $number_period_for_forecasting=36;
    if(date("m")==12 )
      $number_period_for_forecasting=37;

  }


  $values=preg_replace('/^,/','',$values);
  $count=preg_replace('/^,/','',$count);

  // print_r($this->values);
  //print $values;
  $script=sprintf("library(forecast,quietly );values=c(%s);",$values);
  
  $script.=sprintf("ts= ts(values, start=c(%d,%d),frequency = %d);",$this->first_complete_year,$this->first_complete_bin,$this->frequency);
  $script.="fcast =forecast(ts,$number_period_for_forecasting);print(fcast) ;print ('--count data--');";
  $script.=sprintf("values=c(%s);",$count);
  $script.=sprintf("ts= ts(values, start=c(%d,%d),frequency = %d);",$this->first_complete_year,$this->first_complete_bin,$this->frequency);
  $script.="fcast = forecast(ts,$number_period_for_forecasting);print(fcast) ;";
  print $script;
  exit;
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
  // print $ret;
  $ret_data = preg_split('/--count data-/',$ret);
  
  $values_forecast_data=$ret_data[0];
  $count_forecast_data=$ret_data[1];

 $values_forecast_data = preg_split('/\n/',$values_forecast_data);
  $count_forecast_data = preg_split('/\n/',$count_forecast_data);


 
 
 //print_r($values_forecast_data);
 // print_r($count_forecast_data);
$forecast_bins=0;
 foreach($values_forecast_data as $line){
    if($forecast_bins>$number_period_for_forecasting)
        break;
    $line=_trim($line);
    if($read and $line!=''){
         $regex='/^[a-z]{3}\s\d{4}\s*/i';
     if(!preg_match($regex,$line,$match))
       continue;
     $line=preg_replace($regex,'',$line);
     $date=date("Y-m-d",strtotime($match[0]));
     $data=preg_split('/\s/',$line);

       if($this->no_negative_values){
       foreach($data as $_key=>$_value){
	 if(is_numeric($_value) and $_value<0)
	   $data[$_key]=0;
       }
     }

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
  if($forecast_bins>$number_period_for_forecasting)
        break;
   $line=_trim($line);
   if($read and $line!=''){
     $regex='/^[a-z]{3}\s\d{4}\s*/i';
      if(!preg_match($regex,$line,$match))
       continue;
     $line=preg_replace($regex,'',$line);
     $date=date("Y-m-d",strtotime($match[0]));
     $data=preg_split('/\s/',$line);

     if($this->no_negative_values){
       foreach($data as $_key=>$_value){
	 if(is_numeric($_value) and $_value<0)
	   $data[$_key]=0;
       }
     }

     if($data[0]==0)
       $uncertainty=0;
     else
       $uncertainty=($data[4]-$data[3])/(2*$data[0]);

     //print $date."\n";
     $forecast[$date]['count']=round($data[0]);
     $forecast[$date]['deviation'].='|'.round($data[1]).','.round($data[2]).','.round($data[3]).','.round($data[4]).','.$uncertainty;
       $forecast_bins++;

   }
   // print_r($forecast);
   
   if(preg_match('/Point Forecast/i',$line))
     $read=true;
 } 


//print_r($forecast);

 $this->forecast=$forecast;
 $this->save_forecast();



}



function get_values_per_year(){
  
$this->first_complete_year();


$start_year=$this->start_year;
$last_year=date("Y");

if($last_year<$start_year){
  $this->error=true;
  return;
}
for($year=$start_year;$year<=$last_year;$year++  ){
  if($year==$this->start_year)
    $this->first=array('date'=>"$year-01-01",'count'=>0,'value'=>0);
  else if($year==$last_year)
    $this->current=array('date'=>"$year-01-01",'count'=>0,'value'=>0);
  else
    $this->values["$year-01-01"]=array('count'=>0,'value'=>0);
}


   $sql=sprintf("SELECT %s as number,%s as date ,YEAR(%s) AS year ,sum(%s) as value FROM %s where YEAR(%s)>%s  and YEAR(%s)<=%s %s  GROUP BY year limit 10000"
	       ,$this->count
	       ,$this->date_field,$this->date_field
	       ,$this->value_field
	       ,$this->table
	       ,$this->date_field,prepare_mysql($start_year)
	       ,$this->date_field,prepare_mysql($last_year)
	       ,$this->where
	     );
 
  // print "$sql\n";
 
  $res=mysql_query($sql);
  
  while($row=mysql_fetch_array($res)){
    $year=$row['year'];
    if($year==$this->start_year)
        $this->first=array('date'=>"$year-01-01",'count'=>$row['number'],'value'=>$row['value']);
   else if($year==$last_year)
        $this->current=array('date'=>"$year-01-01",'count'=>$row['number'],'value'=>$row['value']);   
    else
        $this->values["$year-01-01"]=array('count'=>$row['number'],'value'=>$row['value']);
  }
  
  
}




function get_values_per_month(){
  
$this->first_complete_month();
if($this->no_data)
  return;

$sql=sprintf("SELECT `First Day` as date ,substring(`First Day`, 1,7) AS dd  FROM kbase.`Month Dimension` where `First Day`>%s  and `First Day`<%s  GROUP BY dd order by `First Day` " 
	     ,prepare_mysql($this->start_date)
	     ,prepare_mysql(date("Y-m-d"))
	     );
//print $sql;
  $res=mysql_query($sql);
  $this->values=array();
   while($row=mysql_fetch_array($res)){
     if($row['dd']==$this->start_year.'-'.$this->start_bin)
       $this->first=array('date'=>$row['dd'].'-01','count'=>0,'value'=>0);
     else if($row['dd']==date("Y-m"))
        $this->current=array('date'=>$row['dd'].'-01','count'=>0,'value'=>0);
      else
        $this->values[$row['dd'].'-01']=array('count'=>0,'value'=>0);
   }

   $sql=sprintf("SELECT %s as number,%s as date ,substring(%s, 1,7) AS dd ,sum(%s) as value FROM %s where %s>%s  and %s<%s %s  GROUP BY dd limit 10000"
	       ,$this->count
	       ,$this->date_field,$this->date_field
	       ,$this->value_field
	       ,$this->table
	       ,$this->date_field,prepare_mysql($this->start_date)
	       ,$this->date_field,prepare_mysql(date("Y-m-d"))
	       ,$this->where
	     );
 
  // print "$sql\n";
 
  $res=mysql_query($sql);
  
  while($row=mysql_fetch_array($res)){
    if($row['dd']==$this->start_year.'-'.$this->start_bin)
        $this->first=array('date'=>$row['dd'].'-01','count'=>$row['number'],'value'=>$row['value']);
    else if($row['dd']==date("Y-m"))
        $this->current=array('date'=>$row['dd'].'-01','count'=>$row['number'],'value'=>$row['value']);   
    else
        $this->values[$row['dd'].'-01']=array('count'=>$row['number'],'value'=>$row['value']);
  }
  
  //  print_r($this->values);
  //exit;

}



function first_complete_month(){
  $sql=sprintf("select MONTH(%s) as m,YEAR(%s) as y from %s  where %s IS NOT NULL %s   order by %s limit 1  "
	       ,$this->date_field
	       ,$this->date_field
	       ,$this->table
	       ,$this->date_field
	       ,$this->where
	       ,$this->date_field
	       );
  
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($res)){
    $time=mktime(0, 0, 0, $row["m"] , 1, $row["y"]);
    $this->start_date=date("Y-m-d", $time); 
    $this->start_year=date("Y", $time); 
    $this->start_bin=date("m", $time); 
    $time=mktime(0, 0, 0, $row["m"]+1 , 1, $row["y"]);
    $this->first_complete_date=date("Y-m-d", $time); 
    $this->first_complete_year=date("Y", $time); 
    $this->first_complete_bin=date("m", $time); 
    $this->no_data=false;
  }else{
    $this->no_data=true;

  }
}


function first_complete_year(){
  $sql=sprintf("select MONTH(%s) as m,YEAR(%s) as y from %s  where %s IS NOT NULL %s   order by %s limit 1  "
	       ,$this->date_field
	       ,$this->date_field
	       ,$this->table
	       ,$this->date_field
	       ,$this->where
	       ,$this->date_field
	       );
 
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($res)){
    $time=mktime(0, 0, 0, 1 , 1, $row["y"]);
    $this->start_date=date("Y-m-d", $time); 
    $this->start_year=date("Y", $time); 
    $this->start_bin=1; 
    $time=mktime(0, 0, 0,1, 1, $row["y"]+1);
    $this->first_complete_date=date("Y-m-d", $time); 
    $this->first_complete_year=date("Y", $time); 
    $this->first_complete_bin=1; 
  }
}

function first_complete_quarter(){
  $sql=sprintf("select  MONTH(%s) as m,QUARTER(%s) as q,YEAR(%s) as y from %s  where %s IS NOT NULL %s   order by %s limit 1  "
	       ,$this->date_field
	       ,$this->date_field
	       ,$this->table
	       ,$this->date_field
	       ,$this->where
	       ,$this->date_field
	       );
 
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($res)){
    $time=mktime(0, 0, 0, $row["m"] , 1, $row["y"]);
    $this->start_date=date("Y-m-d", $time); 
    $this->start_year=date("Y", $time); 
    $this->start_bin=date("m", $time); 
    $time=mktime(0, 0, 0,$row["m"]+3, 1, $row["y"]);
    $this->first_complete_date=date("Y-m-d", $time); 
    $this->first_complete_year=date("Y", $time); 
    if($row['m']<=3)
      $quarter=2;
     elseif($row['m']<=6)
       $quarter=3;
     elseif($row['m']<=9)
       $quarter=4;
     else
       $quarter=1;
    
    $this->first_complete_bin=$quarter; 
  }
}




 function last_complete_month(){
   //  return "2009-07-31 23:59:59";
   $sql=sprintf("select MONTH(%s) as m,YEAR(%s) as y from %s where %s IS NOT NULL %s  order by %s desc limit 1  "
		,$this->date_field
		,$this->date_field
		
		,$this->table
		,$this->date_field
		,$this->where
		,$this->date_field
		);
   // print $sql;
   $res=mysql_query($sql);
   if($row=mysql_fetch_array($res)){
     $last_time=mktime(0, 0, 0, date($row["m"])-1 , -1, date($row["y"]));
     if($last_time>mktime(0, 0, 0, date("m") , date("d"), date("y") ))
       $last_time=mktime(0, 0, 0, date("m") , -1, date("y"));
     return date("Y-m-d", $last_time); 
  }
}

 function last_complete_year(){
   $sql=sprintf("select YEAR(%s) as y from %s where %s IS NOT NULL %s  order by %s desc limit 1  "
		,$this->date_field
		,$this->date_field
		,$this->table
		,$this->date_field
		,$this->where
		,$this->date_field
		);
   $res=mysql_query($sql);
   if($row=mysql_fetch_array($res)){
     $last_time=mktime(0, 0, 0, 1 , 1, date($row["y"])-1 );
     return date("Y-m-d", $last_time); 
  }
}

function last_complete_quarter(){
   $sql=sprintf("select MONTH(%s) as m,YEAR(%s) as y from %s where %s IS NOT NULL %s  order by %s desc limit 1  "
		,$this->date_field
		,$this->date_field
		,$this->table
		,$this->date_field
		,$this->where
		,$this->date_field
		);
   $res=mysql_query($sql);
   if($row=mysql_fetch_array($res)){
     
     if($row['m']<=3)
       $last_time=mktime(0, 0, 0, 10 , 1, date("y")-1);
     elseif($row['m']<=6)
       $last_time=mktime(0, 0, 0, 1 , 1, date("y"));
     elseif($row['m']<=9)
       $last_time=mktime(0, 0, 0, 4 , 1, date("y"));
     else
       $last_time=mktime(0, 0, 0, 7 , 1, date("y"));

     return date("Y-m-d", $last_time); 
  }
}
    

 function  plot_data(){
   if( $this->freq=='Monthly')
     return $this->plot_data_per_month();
 }
 
 function plot_data_per_month(){

   $data=array();

   $tipo='';
   $suffix='';
   
 if($this->name=='invoices'){
     
   $tipo='SI';
   $suffix='';
   
 }elseif(preg_match('/^(PDS|SS|PFS|PrS)/',$this->name)){
   $tipo='PI';
   $suffix=preg_replace('/.*\(/','',$this->name_key);
   $suffix=preg_replace('/\)/','',$suffix);
   $suffix=preg_replace('/,/','_',$suffix);
 }

 $sql=sprintf("SELECT `Time Series Label`,`Time Series Type`,`Time Series Value` as value,MONTH(`Time Series Date`) as month,`Time Series Count` as count ,UNIX_TIMESTAMP(`Time Series Date`) as date ,substring(`Time Series Date`, 1,7) AS dd from `Time Series Dimension` where `Time Series Name`=%s and `Time Series Name Key`=%d and `Time Series Name Second Key`=%d order by `Time Series Date`,`Time Series Type` desc"
		,prepare_mysql($this->name)
		,$this->name_key
		,$this->name_key2
		);
   // print "$sql<br>";

  $prev_month='';
  $prev_year=array();
  $forecast_region=false;
  $data_region=false;
  $res = mysql_query($sql); 
  while($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
    if(is_numeric($prev_month)){
      $diff=$row['value']-$prev_month;
      if($diff==0)
	$diff_prev_month=_('No change from last month')."\n";
      else
	$diff_prev_month=percentage($diff,$prev_month,1,'NA','%',true)." "._('change (last month)')."\n";
    }else
      $diff_prev_month='';
 
    if(isset($prev_year[$row['month']])){
      $diff=$row['value']-$prev_year[$row['month']];
      if($diff==0)
	$diff_prev_year=_('No change from last year')."\n";
      else
      $diff_prev_year=percentage($diff,$prev_year[$row['month']],1,'NA','%',true)." "._('change (last year)')."\n";
    }else{
      $diff_prev_year='';
    }
 
    
    if($tipo=='SI' or $tipo=='PI'){
      $tip=$row['Time Series Label'].' '._('Sales')." ".strftime("%B %Y", strtotime('@'.$row['date']))."\n".money($row['value'])."\n".$diff_prev_month.$diff_prev_year."(".$row['count']." "._('Invoices').")";

    }elseif($tipo="PO"){
       $tip=_('Sales')." ".strftime("%B %Y", strtotime('@'.$row['date']))."\n".money($row['value'])."\n".$diff_prev_month.$diff_prev_year."(".$row['count']." "._('Outers Shipped').")";
       
    }

    $data[$row['dd']]=array(
			    'date'=>strftime("%m/%y", strtotime('@'.$row['date']))
			    );
    // print $row['dd']."<br>\n";
    if($row['Time Series Type']=='First'){
	$first_value=array($row['dd'],$row['value'],$tip) ;

    }
  if($row['Time Series Type']=='Current'){
	$current_value=array($row['dd'],$row['value'],$tip) ;
    }
    if($row['Time Series Type']=='Data'){
      if(!$data_region){

	$data[$first_value[0]]['tails'.$suffix]=(float) $first_value[1];
	$data[$first_value[0]]['tip_tails'.$suffix]=$first_value[2];
	$data[$row['dd']]['tails'.$suffix]=(float) $row['value'];
	$data[$row['dd']]['tip_tails'.$suffix]=$tip;
       
      }
      $data_region=true;
      
      $data[$row['dd']]['value'.$suffix]=(float) $row['value'];
      $data[$row['dd']]['tip_value'.$suffix]=$tip;
      $last_complete_value=array($row['dd'],$row['value'],$tip) ;
    }
    if($row['Time Series Type']=='Forecast'){
     
      if(!$forecast_region){
        $data[$last_complete_value[0]]['forecast'.$suffix]=(float) $last_complete_value[1];
	
	$data[$last_complete_value[0]]['tails'.$suffix]=(float) $last_complete_value[1];
	$data[$last_complete_value[0]]['tip_tails.$suffix']='';
	$data[$current_value[0]]['tails'.$suffix]=(float) $current_value[1];
	$data[$current_value[0]]['tip_tails'.$suffix]= $current_value[2];

      }
      $forecast_region=true;
      $data[$row['dd']]['forecast'.$suffix]=(float) $row['value'];
      $data[$row['dd']]['tip_forecast'.$suffix]=$tip;
    }	   

   
    
    $prev_month=$row['value'];
    $prev_year[$row['month']]=$row['value'];
  }
  mysql_free_result($res);
  //$_data=array();
  //$i=0;
  
  //foreach($data as $__data)
  //   $_data[]=$__data;
  
  return $data;

 }

}
?>