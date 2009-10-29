<?php

Class CurrencyExchange  {

  var $exchange=false;


  function CurrencyExchange($action,$currency_pair=false,$date=false,$date2=false){
    
    if($action=='get'){
      $this->currency_pair=$currency_pair;
      $this->parse_dates($date,$date2);
      $this->get_exchange();
      return $this->$exchange;
      return;
    }    
    if($action=='load'){
       if($this->is_currency_pair($currency_pair)){
	 $this->currency_pair=$currency_pair;
	 $this->parse_dates($date,$date2);
	 $this->load_currency_exchange();
	   return;
       }
    }
    if($this->is_currency_pair($action)){
       $this->parse_dates($currency_pair,$date);
       $this->get_exchange();
       return;
    }

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

 function get_data_array($load_on_unknown=false){
   
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
    $this->exchange=false
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
      $this->from=date('Y-d-m');
      $this->to=date('Y-d-m');
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



  function load_currency_exchange(){
    $random=md5(mt_rand());
    $tmp_file="tmp/currency_$random.txt";
    $days=100;

    $from=date("Ymd",strtotime($this->from));
    $to=date("Ymd",strtotime($this->to));


    exec(sprintf("./mantenence/scripts/get_currency_exchange.py  %s %s %s=X > %s",$from,$to,$this->currency_pair,$tmp_file));


  }

  function is_currency_pair($currency_pair){
    
    if(!preg_match('/^[a-z]{6}$/i',$currency_pair))
      return false;
    $currency1=substr($currency_pair)
    
  }

}

?>