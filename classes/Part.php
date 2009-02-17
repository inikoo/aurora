<?

class part{
  

  var $id=false;

  function __construct($a1,$a2=false) {



    if(is_numeric($a1) and !$a2){
      $this->get_data('id',$a1);
    }
    else if(($a1=='new' or $a1=='create') and is_array($a2) ){
      $this->msg=$this->create($a2);
      
    } else
      $this->get_data($a1,$a2);

  }
  



  function get_data($tipo,$tag){
    if($tipo=='id')
      $sql=sprintf("select * from `Part Dimension` where `Part Key`=%d ",$tag);
    else
      return;

    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->id=$this->data['Part Key'];
    }
    

  }
  
  function create($data){
    // print_r($data);
     $base_data=array(
		     'part type'=>'Physical',
		     'part sku'=>'',
		     'part xhtml currently used in'=>'',
		     'part xhtml currently supplied by'=>'',
		     'part xhtml description'=>'',
		     'part unit description'=>'',
		     'part package size metadata'=>'',
		     'part package volume'=>'',
		     'part package minimun orthogonal volume'=>'',
		     'part gross weight'=>'',
		     'part valid from'=>'',
		     'part valid to'=>'',
		     'part most recent'=>'',
		     'part most recent key'=>''
		     );
     foreach($data as $key=>$value){
       if(isset( $base_data[strtolower($key)]) )
	 $base_data[strtolower($key)]=_trim($value);
     }
 
     if(!$this->valid_sku($base_data['part sku']) ){

       $base_data['part sku']=$this->new_sku();
     }

     $keys='(';$values='values(';
    foreach($base_data as $key=>$value){
      $keys.="`$key`,";
      $values.=prepare_mysql($value).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    
    $sql=sprintf("insert into `Part Dimension` %s %s",$keys,$values);
    // print "$sql\n";
    // exit;
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();

      if($base_data['part most recent']=='Yes')
      	$sql=sprintf("update  `Part Dimension` set `Part Most Recent Key`=%d where `Part Key`=%d",$this->id,$this->id);
	mysql_query($sql);

      $this->get_data('id',$this->id);
    }else{
      print "Error Part can not be created\n";exit;
    }

 }

  function load($data_to_be_read,$args=''){
    switch($data_to_be_read){

    case("used in"):
       $used_in_products='';
      $sql=sprintf("select `Product Same Code Most Recent Key`,`Product Code` from `Product Part List` PPL on (PD.`Product ID`=PPL.`Product ID`)  where `Part SKU`=%d group by `Product Code`;",$this->data['Part SKU']);
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$used_in_products.=sprintf(', <a href="product.php?id=%d">%s</a>',$row['Product Same Code Most Recent Key'],$row['Product Code']);
      }
      $used_in_products=preg_replace('/^, /','',$used_in_products);
       $sql=sprintf("update `Part Dimension` set `Part XHTML Currently Used In`=%s where `Part Key`=%d",prepare_mysql(_trim($used_in_products)),$this->id);
      //print "$sql\n";
      mysql_query($sql);
      break;
    case("sales"):
      // the product wich this one is 
      $sold=0;
      $required=0;
      $provided=0;
      $given=0;
      $amount_in=0;
      $value=0;
      $value_free=0;
      $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Given`+`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transition Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s   ",prepare_mysql($this->data['Part SKU']),prepare_mysql($this->data['Part Valid From']),prepare_mysql($this->data['Part Valid To'])  );
      //      print "$sql\n";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$required=$row['required'];
	$provided=-$row['qty'];
	$given=$row['given'];
	$amount_in=$row['amount_in'];
	$value=$row['value'];
	$value_free=$row['value_free'];
	$sold=-$row['qty']-$row['given'];
      }
      $abs_profit=$amount_in+$value;
      $profit_sold=$amount_in+$value-$value_free;

      $sql=sprintf("update `Part Dimension` set `Part Total Required`=%f ,`Part Total Provided`=%f,`Part Total Given`=%f ,`Part Total Sold Amount`=%f ,`Part Total Absolute Profit`=%f ,`Part Total Profit When Sold`=%f , `Part Total Sold`=%f where `Part Key`=%d "
		   ,$required
		   ,$provided
		   ,$given
		   ,$amount_in
		   ,$abs_profit
		   ,$profit_sold,$sold
		   ,$this->id);
      //            print "$sql\n";
      if(!mysql_query($sql))
	exit("error con not uopdate product part when loading sales");
	
      $sold=0;
      $required=0;
      $provided=0;
      $given=0;
      $amount_in=0;
      $value=0;
      $value_free=0;
      $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Given`+`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transition Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s  and `Date`>=%s     ",prepare_mysql($this->data['Part SKU']),prepare_mysql($this->data['Part Valid From']),prepare_mysql($this->data['Part Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 year")))  );
      //      print "$sql\n";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$required=$row['required'];
	$provided=-$row['qty'];
	$given=$row['given'];
	$amount_in=$row['amount_in'];
	$value=$row['value'];
	$value_free=$row['value_free'];
	$sold=-$row['qty']-$row['given'];
      }
      $abs_profit=$amount_in+$value;
      $profit_sold=$amount_in+$value-$value_free;

      $sql=sprintf("update `Part Dimension` set `Part 1 Year Acc Required`=%f ,`Part 1 Year Acc Provided`=%f,`Part 1 Year Acc Given`=%f ,`Part 1 Year Acc Sold Amount`=%f ,`Part 1 Year Acc Absolute Profit`=%f ,`Part 1 Year Acc Profit When Sold`=%f , `Part 1 Year Acc Sold`=%f where `Part Key`=%d "
		   ,$required
		   ,$provided
		   ,$given
		   ,$amount_in
		   ,$abs_profit
		   ,$profit_sold,$sold
		   ,$this->id);
      //            print "$sql\n";
      if(!mysql_query($sql))
	exit("error con not uopdate product part when loading sales");
      
      $sold=0;
       $required=0;
      $provided=0;
      $given=0;
      $amount_in=0;
      $value=0;
      $value_free=0;
      $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Given`+`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transition Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s  and `Date`>=%s     ",prepare_mysql($this->data['Part SKU']),prepare_mysql($this->data['Part Valid From']),prepare_mysql($this->data['Part Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -3 month")))  );
      //      print "$sql\n";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$required=$row['required'];
	$provided=-$row['qty'];
	$given=$row['given'];
	$amount_in=$row['amount_in'];
	$value=$row['value'];
	$value_free=$row['value_free'];$sold=-$row['qty']-$row['given'];
      }
      $abs_profit=$amount_in+$value;
      $profit_sold=$amount_in+$value-$value_free;

      $sql=sprintf("update `Part Dimension` set `Part 1 Quarter Acc Required`=%f ,`Part 1 Quarter Acc Provided`=%f,`Part 1 Quarter Acc Given`=%f ,`Part 1 Quarter Acc Sold Amount`=%f ,`Part 1 Quarter Acc Absolute Profit`=%f ,`Part 1 Quarter Acc Profit When Sold`=%f  , `Part 1 Quarter Acc Sold`=%f  where `Part Key`=%d "
		   ,$required
		   ,$provided
		   ,$given
		   ,$amount_in
		   ,$abs_profit
		   ,$profit_sold,$sold
		   ,$this->id);
      //            print "$sql\n";
      if(!mysql_query($sql))
	exit("error con not uopdate product part when loading sales");
      
  $sold=0;
       $required=0;
      $provided=0;
      $given=0;
      $amount_in=0;
      $value=0;
      $value_free=0;
      $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Given`+`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transition Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s  and `Date`>=%s     ",prepare_mysql($this->data['Part SKU']),prepare_mysql($this->data['Part Valid From']),prepare_mysql($this->data['Part Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 month")))  );
      //      print "$sql\n";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$required=$row['required'];
	$provided=-$row['qty'];
	$given=$row['given'];
	$amount_in=$row['amount_in'];
	$value=$row['value'];
	$value_free=$row['value_free'];$sold=-$row['qty']-$row['given'];
      }
      $abs_profit=$amount_in+$value;
      $profit_sold=$amount_in+$value-$value_free;

      $sql=sprintf("update `Part Dimension` set `Part 1 Month Acc Required`=%f ,`Part 1 Month Acc Provided`=%f,`Part 1 Month Acc Given`=%f ,`Part 1 Month Acc Sold Amount`=%f ,`Part 1 Month Acc Absolute Profit`=%f ,`Part 1 Month Acc Profit When Sold`=%f  , `Part 1 Month Acc Sold`=%f  where `Part Key`=%d "
		   ,$required
		   ,$provided
		   ,$given
		   ,$amount_in
		   ,$abs_profit
		   ,$profit_sold,$sold
		   ,$this->id);
      //            print "$sql\n";
      if(!mysql_query($sql))
	exit("error con not uopdate product part when loading sales");

  $sold=0;
         $required=0;
      $provided=0;
      $given=0;
      $amount_in=0;
      $value=0;
      $value_free=0;
      $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Given`+`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transition Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s  and `Date`>=%s     ",prepare_mysql($this->data['Part SKU']),prepare_mysql($this->data['Part Valid From']),prepare_mysql($this->data['Part Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 week")))  );
      //      print "$sql\n";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$required=$row['required'];
	$provided=-$row['qty'];
	$given=$row['given'];
	$amount_in=$row['amount_in'];
	$value=$row['value'];
	$value_free=$row['value_free'];$sold=-$row['qty']-$row['given'];
      }
      $abs_profit=$amount_in+$value;
      $profit_sold=$amount_in+$value-$value_free;

      $sql=sprintf("update `Part Dimension` set `Part 1 Week Acc Required`=%f ,`Part 1 Week Acc Provided`=%f,`Part 1 Week Acc Given`=%f ,`Part 1 Week Acc Sold Amount`=%f ,`Part 1 Week Acc Absolute Profit`=%f ,`Part 1 Week Acc Profit When Sold`=%f  , `Part 1 Week Acc Sold`=%f  where `Part Key`=%d "
		   ,$required
		   ,$provided
		   ,$given
		   ,$amount_in
		   ,$abs_profit
		   ,$profit_sold,$sold
		   ,$this->id);
      //            print "$sql\n";
      if(!mysql_query($sql))
	exit("error con not uopdate product part when loading sales");



      }
  }
  
 function get($key=''){
   
    if(array_key_exists($key,$this->data))
      return $this->data[$key];

     $_key=preg_replace('/^part /','',$key);
    if(isset($this->data[$_key]))
      return $this->data[$key];

    
    switch($key){
      
    }
    
    return false;
  }
  

 function valid_sku($sku){
   // print "validadndo sku $sku";
   if(is_numeric($sku) and $sku>0 and $sku<9223372036854775807)
     return true;
   else
     return false;
 }

function used_sku($sku){
  $sql="select count(*) as num from `Part Dimension` where `Part SKU`=".prepare_mysql($sku);
  // print "$sql\n";
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    if($row['num']>0)
      return true;
  }
  return false;
}

 function new_sku(){
   $sql="select max(`Part SKU`) as sku from `Part Dimension`";
   //   print "$sql\n";
   $result=mysql_query($sql);
   if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
     return $row['sku']+1;
   }else
     return 1;
   
 }
 

}