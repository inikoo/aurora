<?php

Class CurrencyExchange  {

  var $exchange=false;


  function CurrencyExchange($action,$currency_pair=false,$date=false,$date2=false){
    
    $this->currency_pair=$currency_pair;
    $this->parse_dates($date,$date2);

 /*    if($action=='get'){ */
/*       $this->currency_pair=$currency_pair; */
/*       if($date=='now'){ */
/*       return $this->current_exchange(); */
/*       } */
      
/*       $this->parse_dates($date,$date2); */
/*       $this->get_exchange(); */
/*       return $this->exchange; */
     
/*     }     */
/*     if($action=='load'){ */
/*        if($this->is_currency_pair($currency_pair)){ */
/* 	 $this->currency_pair=$currency_pair; */
/* 	 $this->parse_dates($date,$date2); */
/* 	 $this->load_currency_exchange(); */
/* 	   return; */
/*        } */
/*     } */
/*     if($this->is_currency_pair($action)){ */
/*        $this->parse_dates($currency_pair,$date); */
/*        $this->get_exchange(); */
/*        return; */
/*     } */

  }

  function get_data(){
    if($this->from==$this->to){
      $this->get_data_scalar(true);
    }else{
      $sql=sprintf("select `Date` from kbase.`Date Dimension` where `Date`>=%s and `Date`<=%s "
		   ,prepare_mysql($this->from)
		   ,prepare_mysql($this->to)
		   );
      $res=mysql_query($sql);
      while($row=mysql_fetch_array($res)){
	$this->exchange[$row['Date']]=0;
      }
      $this->get_data_array(true);
    }
  }


  function get_exchange(){
  
    $this->get_data_scalar();
    if(!$this->exchange){
      
      $this-> load_currency_exchange();
      $this->get_data_scalar();
      
    }
    return $this->exchange;
    

  }

 function get_data_array($load_on_unknown=false){
   $this->exchange =array();
     $sql=sprintf("select `Exchange`,`Date` from kbase.`History Currency Exchange Dimension` where `Currency Pair`=%s and `Date`>=%s and `Date`<=%s     "
		  ,prepare_mysql($this->currency_pair)
		  ,prepare_mysql($this->from)
		  ,prepare_mysql($this->to)
		  );
      $res3=mysql_query($sql);
      //print $sql;
      while($row3=mysql_fetch_array($res3, MYSQL_ASSOC)){
	$this->exchange[$row3['Date']]=$row3['Exchange'];
      }
      $ok=true;
      foreach($this->exchange as $key=>$value){
	if(!$value){
	  $ok=false;
	  break;
	}
	  
      }


      if(!$ok and $load_on_unknown)
	if($this->is_currency_pair($this->currency_pair)){
	  $this->load_currency_exchange();
	  
	}else{
	  $this->error=true;
	  $this->msg=$this->currency_pair." is not a currency pair";
	}

  }





  function get_data_scalar($load_on_unknown=false){
    $this->exchange=false;
     $sql=sprintf("select `Exchange` from kbase.`History Currency Exchange Dimension` where `Currency Pair`=%s and `Date`=DATE(%s)     "
		  ,prepare_mysql($this->currency_pair)
		  ,prepare_mysql($this->from));
      $res3=mysql_query($sql);
      //print $sql;
      if($row3=mysql_fetch_array($res3, MYSQL_ASSOC)){
	$this->exchange=$row3['Exchange'];
      }


      if(!$this->exchange and $load_on_unknown)
	if($this->is_currency_pair($this->currency_pair)){
	  $this->load_currency_exchange();
	  
	}else{
	  $this->error=true;
	  $this->msg=$this->currency_pair." is not a currency pair";
	}

  }


  

  
  function parse_dates($date,$date2){
     if(!$date){
      $this->from=date('Y-m-d');
      $this->to=date('Y-m-d');
    }else{
      $this->from=$date;
      if(!$date2)
	$this->to=$this->from;
      else{
	$this->to=$date2;
      }
	
     }
     if(strtotime($this->to)<strtotime($this->from))
      $this->from=$this->to;
  }



  function load_currency_exchange($from=false,$to=false,$fixing_date=false){
    $random=md5(mt_rand());
    $tmp_file="app_files/tmp/currency_$random.txt";
    $days=100;
   
    if(!$from)
      $from=date("Ymd",strtotime($this->from));
    if(!$to)
      $to=date("Ymd",strtotime($this->to));

    //print sprintf("./mantenence/scripts/get_currency_exchange.py  %s %s %s=X > %s",$from,$to,$this->currency_pair,$tmp_file);
    exec(sprintf("./mantenence/scripts/get_currency_exchange.py  %s %s %s=X > %s",$from,$to,$this->currency_pair,$tmp_file));
    
    $rows = 0;
    $handle = fopen($tmp_file, "r");
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      
      $num = count($data);
      $pair=preg_replace('/=X/','',$data[0]);
      $date=date("Y-m-d",strtotime($data[1]));
      $exchange=$data[2];
      
  
      
      
      $sql=sprintf("insert into kbase.`History Currency Exchange Dimension` values (%s,%s,%f)  "
		   ,prepare_mysql($date) ,prepare_mysql($pair),$exchange);
      //print "$sql\n";
      mysql_query($sql);
      $rows++;
    }
    fclose($handle);
    unset($tmp_file);


    if($from==$to and $rows==0  ){
      // this meas that yahoo do not have this day try the day before
      $this->load_currency_missing_day(date("Y-m-d",strtotime($this->from)));

    }

  }

  function load_currency_missing_day($day){
   $random=md5(mt_rand());
    $tmp_file="app_files/tmp/currency_$random.txt";
   
  
      $from=date("Ymd",strtotime($day.' -1 day'));
      $to=$from;

    // print sprintf("./mantenence/scripts/get_currency_exchange.py  %s %s %s=X > %s",$from,$to,$this->currency_pair,$tmp_file);
    exec(sprintf("./mantenence/scripts/get_currency_exchange.py  %s %s %s=X > %s",$from,$to,$this->currency_pair,$tmp_file));
    
    $rows = 0;
    $handle = fopen($tmp_file, "r");
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
      
      $num = count($data);
      $pair=preg_replace('/=X/','',$data[0]);
      $date=$day;
      $exchange=$data[2];
      
  
      
      
      $sql=sprintf("insert into kbase.`History Currency Exchange Dimension` values (%s,%s,%f)  "
		   ,prepare_mysql($date) ,prepare_mysql($pair),$exchange);
      //print "$sql\n";
      mysql_query($sql);
      $rows++;
    }
    fclose($handle);
    unset($tmp_file);
    
  
  }



  function is_currency_pair($currency_pair){
    
    if(!preg_match('/^[a-z]{6}$/i',$currency_pair))
      return false;
    $this->currency1=substr($currency_pair,3);
    $this->currency2=substr($currency_pair,3,3);
    return true;
  }

function current_exchange(){
$url = "http://quote.yahoo.com/d/quotes.csv?s=". $this->currency1 . $this->currency2 . "=X". "&f=l1&e=.csv";
  print $url;
  
  $handle = fopen($url, "r");
  $contents = fread($handle,2000);
  fclose($handle);
  if(is_numeric($contents) and $contents>0){
    $exchange_rate=$contents;
    $sql=sprintf("insert into kbase.`History Currency Exchange Dimension` values (%s,%s,%f) on duplicate update `Exchange`=%f "
    ,prepare_mysql(date('Y-m-d')) ,prepare_mysql($pair),$exchange,$exchange);
    print "$sql\n";
    mysql_query($sql);
      
return $exchange;
  }
return $false;


}
}

?>