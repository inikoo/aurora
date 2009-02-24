<?

class supplierproduct{
  
  var $db;
  var $id=false;
  var $most_recent=false;
  var $new=false;
  var $new_id=false;
  function __construct($a1,$a2=false) {
    // $this->db =MDB2::singleton();


    if(is_numeric($a1) and !$a2){
      $this->get_data('id',$a1);
    }
    else if(($a1=='new' or $a1=='create') and is_array($a2) ){
      $this->msg=$this->create($a2);
      
    } else
      $this->get_data($a1,$a2);

  }
  


  
  function get_data($tipo,$tag){
    if($tipo=='id'){
      $sql=sprintf("select * from `Supplier Product Dimension` where `Supplier Product Key`=%d ",$tag);
      $result=mysql_query($sql);
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
	$this->id=$this->data['Supplier Product Key'];
      return;
      
    }elseif($tipo='supplier-code-name-cost'){

      $auto_add=$tag['auto_add'];
      $sql=sprintf("select * from `Supplier Product Dimension` where `Supplier Product Code`=%s  and `Supplier Product Name`=%s and `Supplier Product Cost`=%s and `Supplier Product Supplier Key`=%s "
		   ,prepare_mysql($tag['supplier product code'])
		   ,prepare_mysql($tag['supplier product name'])
		   ,prepare_mysql($tag['supplier product cost'])
		   ,prepare_mysql($tag['supplier product supplier key'])
		   
		   );

      $result=mysql_query($sql);
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$this->id=$this->data['Supplier Product Key'];
	


	if(strtotime($this->data['Supplier Product Valid To'])<strtotime($tag['supplier product valid to'])  ){
	  $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Valid To`=%s where `Supplier Product Key`=%d",prepare_mysql($tag['supplier product valid to']),$this->id);
	  $this->data['Supplier Product Valid To']=$tag['supplier product valid to'];
	  mysql_query($sql);
	}
	if(strtotime($this->data['Supplier Product Valid From'])>strtotime($tag['supplier product valid from'])  ){
	  $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Valid From`=%s where `Supplier Product Key`=%d",prepare_mysql($tag['supplier product valid from']),$this->id);
	  mysql_query($sql);
	  $this->data['Supplier Product Valid From']=$tag['supplier product valid from'];
	}
	return;
      }      
      
      
      if(!$auto_add)
	return;


      $diff_price=true;
      $diff_name=true;
      $this->new_id=false;
      $this->new=true;
      $this->new_code=false;
      
      $sql=sprintf("select count(*) as num from `Supplier Product Dimension` where `Supplier Product Code`=%s  and `Supplier Product Supplier Key`=%d"
		   ,prepare_mysql($tag['supplier product code'])
		   ,prepare_mysql($tag['supplier product supplier key'])
		   );

      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$number_sp=$row['num'];
      }
      if($number_sp==0){
	$this->new_code=true;
	$tag['supplier product id']=$this->new_id();
	$tag['supplier product most recent']='Yes';
	$tag['supplier product most recent key']='';
	$this->create($tag);
      
      }else{
	$sql=sprintf("select * from `Supplier Product Dimension` where `Supplier Product Most Recent`='Yes',`Supplier Product Code`=%s  and `Supplier Product Supplier Key`=%d  and   `Supplier Product Name`=%s   "
		     ,prepare_mysql($tag['supplier product code'])
		     ,prepare_mysql($tag['supplier product supplier key'])
		     ,prepare_mysql($tag['supplier product name'])
		     );
	
	$result=mysql_query($sql);
	if($same_id_data=mysql_fetch_array($result, MYSQL_ASSOC)){
	  // just price difference
	  $diff_name=false;
	  if($tag['supplier product cost']==$same_id_data['Supplier Product Cost'])
	    $diff_price=false;
	  $this->new_id=false;
	  $tag['supplier product id']=$same_id_data['Supplier Product Id'];
	  

	  
	  $sql=sprintf("select * from  `Supplier Product Dimension` where `Supplier Product Valid To`<%s and `Supplier Product Most Recent`='Yes' and `Supplier Product ID`=%d  ",$tag['supplier product valid to'],$tag['supplier product most recent key']);
	  
	  
	  $result=mysql_query($sql);
	  if($last_data=mysql_fetch_array($result, MYSQL_ASSOC)){
	    $tag['supplier product most recent']='No';
	    $tag['supplier product most recent key']=$last_data['Product Key'];
	  }else{
	    $tag['supplier product most recent']='Yes';
	  }
	  $this->create($tag);
	  
	  
	}else{
	  $this->new_id=true;
	  $diff_price=false;
	  $diff_name=false;
	  $this->new_code=true;
	  $tag['supplier product id']=$this->new_id();
	  $tag['supplier product most recent']='Yes';
	  $tag['supplier product most recent key']='';
	  $this->create($tag);
	  
	}
	
      }
      
    
    }
  
  }




  
  function create($data){
    
    $base_data=array(
		     'supplier product supplier key'=>'',
		     'supplier product id'=>'',
		     'supplier product code'=>'',
		     'supplier product name'=>'',
		     'supplier product description'=>'',
		     'supplier product cost'=>'',
		     'supplier product valid from'=>date("Y-m-d H:i:s"),
		     'supplier product valid to'=>date("Y-m-d H:i:s"),
		     'supplier product most recent'=>'Yes',
		     'supplier product most recent key'=>''
		     );
    foreach($data as $key=>$value){
      if(isset($base_data[strtolower($key)]))
	$base_data[strtolower($key)]=_trim($value);
    }
    
    if(!$this->valid_id($base_data['supplier product id'])  ){
      $base_data['supplier product id']=$this->new_id();
    }
    
    if(preg_match('/^yes$/i',$base_data['supplier product most recent']))
      $base_data['supplier product most recent']='Yes';
    else
      $base_data['supplier product most recent']='No';
    
    
    $keys='(';$values='values(';
    foreach($base_data as $key=>$value){
      $keys.="`$key`,";
      $values.=prepare_mysql($value).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    $sql=sprintf("insert into `Supplier Product Dimension` %s %s",$keys,$values);
    //print "$sql\\n\nn";
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      
      if($base_data['supplier product most recent']=='Yes'){
	
	$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Most Recent`='No' where `Supplier Product ID`=%d  and `Product Supplier Key`!=%d",$base_data['supplier product id'],$this->id);
	mysql_query($sql);
      
	$sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Most Recent Key`=%d where `Supplier Product Key`=%d",$this->id,$this->id);
	mysql_query($sql);
      }
      

      
      
      $this->get_data('id',$this->id);
    }else{
      print "Error can not create Product Supplier\n";exit;
    }
    
    
    
    }
  
  function load($data_to_be_read,$args=''){
    switch($data_to_be_read){
    case('used in'):

      $used_in_products='';
      $sql=sprintf("select `Product Same Code Most Recent Key`,`Product Code` from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`) left join `Product Part List` PPL on (SPPL.`Part SKU`=PPL.`Part SKU`) left join `Product Dimension` PD on (PPL.`Product ID`=PD.`Product ID`) where `Supplier Product Key`=%d group by `Product Code`;",$this->data['Supplier Product Key']);
      print $sql;
      exit;

      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$used_in_products.=sprintf(', <a href="product.php?id=%d">%s</a>',$row['Product Same Code Most Recent Key'],$row['Product Code']);
      }
      $used_in_products=preg_replace('/^, /','',$used_in_products);
      
      $used_in_parts='';
      $sql=sprintf("select `Part Key`,PD.`Part SKU` from `Supplier Product Dimension` SPD left join `Supplier Product Part List` SPPL on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`)  left join `Part Dimension` PD on (SPPL.`Part SKU`=PD.`Part SKU`) where `Supplier Product Key`=%d group by PD.`Part SKU`;",$this->data['Supplier Product Key']);
      $result=mysql_query($sql);
      $num_parts=0;
      //print "$sql\n";
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$used_in_parts.=sprintf(', <a href="part.php?id=%d">%s</a>',$row['Part Key'],$row['Part SKU']);
	$num_parts++;
      }
      $used_in_parts=preg_replace('/^, /','',$used_in_parts);
      
      if($num_parts==0)
	$used_in_parts='';
      elseif($num_parts==1)
	$used_in_parts='(SKU:'.$used_in_parts.')';
      else
	$used_in_parts='(SKUs:'.$used_in_parts.')';

      $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product XHTML Used In`=%s where `Supplier Product Key`=%d",prepare_mysql(_trim($used_in_products.' '.$used_in_parts)),$this->id);
      //print "$sql\n";
      mysql_query($sql);
      
	
      break;
    case('sales'):
      $sold=0;
      $required=0;
      $provided=0;
      $given=0;
      $amount_in=0;
      $value=0;
      $value_free=0;
      $margin=0;
      $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transition Fact` where `Supplier Product ID`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s   ",prepare_mysql($this->data['Supplier Product ID']),prepare_mysql($this->data['Supplier Product Valid From']),prepare_mysql($this->data['Supplier Product Valid To'])  );
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
      if($amount_in==0)
	$margin=0;
      else
	$margin=($value-$value_free)/$amount_in;


      $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product Total Parts Required`=%f ,`Supplier Product Total Parts Provided`=%f,`Supplier Product Total Parts Used`=%f ,`Supplier Product Total Sold Amount`=%f ,`Supplier Product Total Parts Profit`=%f  where `Supplier Product Key`=%d "
		   ,$required
		   ,$provided
		   ,$given+$provided
		   ,$amount_in
		   ,$profit_sold
		   ,$this->id);
      //          print "$sql\n";
      if(!mysql_query($sql))
	exit("error con not uopdate product part when loading sales");

      $sold=0;
      $required=0;
      $provided=0;
      $given=0;
      $amount_in=0;
      $value=0;
      $value_free=0;
      $margin=0;
      $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transition Fact` where `Supplier Product ID`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s and `Date`>=%s    ",prepare_mysql($this->data['Supplier Product ID']),prepare_mysql($this->data['Supplier Product Valid From']),prepare_mysql($this->data['Supplier Product Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 year")))  );
      // print "$sql\n";
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
      if($amount_in==0)
	$margin=0;
      else
	$margin=($value-$value_free)/$amount_in;


      $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product 1 Year Acc Parts Required`=%f ,`Supplier Product 1 Year Acc Parts Provided`=%f,`Supplier Product 1 Year Acc Parts Used`=%f ,`Supplier Product 1 Year Acc Sold Amount`=%f ,`Supplier Product 1 Year Acc Parts Profit`=%f  where `Supplier Product Key`=%d "
		   ,$required
		   ,$provided
		   ,$given+$provided
		   ,$amount_in
		   ,$profit_sold
		   ,$this->id);
      //    print "$sql\n";
      if(!mysql_query($sql))
	exit("error con not uopdate product part when loading sales");
 $sold=0;
      $required=0;
      $provided=0;
      $given=0;
      $amount_in=0;
      $value=0;
      $value_free=0;
      $margin=0;
      $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transition Fact` where `Supplier Product ID`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s and `Date`>=%s    ",prepare_mysql($this->data['Supplier Product ID']),prepare_mysql($this->data['Supplier Product Valid From']),prepare_mysql($this->data['Supplier Product Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -3 month")))  );
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
      if($amount_in==0)
	$margin=0;
      else
	$margin=($value-$value_free)/$amount_in;


      $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product 1 Quarter Acc Parts Required`=%f ,`Supplier Product 1 Quarter Acc Parts Provided`=%f,`Supplier Product 1 Quarter Acc Parts Used`=%f ,`Supplier Product 1 Quarter Acc Sold Amount`=%f ,`Supplier Product 1 Quarter Acc Parts Profit`=%f  where `Supplier Product Key`=%d "
		   ,$required
		   ,$provided
		   ,$given+$provided
		   ,$amount_in
		   ,$profit_sold
		   ,$this->id);
      //                  print "$sql\n";
      if(!mysql_query($sql))
	exit("error con not uopdate product part when loading sales");

 $sold=0;
      $required=0;
      $provided=0;
      $given=0;
      $amount_in=0;
      $value=0;
      $value_free=0;
      $margin=0;
      $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transition Fact` where `Supplier Product ID`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s and `Date`>=%s    ",prepare_mysql($this->data['Supplier Product ID']),prepare_mysql($this->data['Supplier Product Valid From']),prepare_mysql($this->data['Supplier Product Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 month")))  );
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
      if($amount_in==0)
	$margin=0;
      else
	$margin=($value-$value_free)/$amount_in;


      $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product 1 Month Acc Parts Required`=%f ,`Supplier Product 1 Month Acc Parts Provided`=%f,`Supplier Product 1 Month Acc Parts Used`=%f ,`Supplier Product 1 Month Acc Sold Amount`=%f ,`Supplier Product 1 Month Acc Parts Profit`=%f  where `Supplier Product Key`=%d "
		   ,$required
		   ,$provided
		   ,$given+$provided
		   ,$amount_in
		   ,$profit_sold
		   ,$this->id);
      //                  print "$sql\n";
      if(!mysql_query($sql))
	exit("error con not uopdate product part when loading sales");
 $sold=0;
      $required=0;
      $provided=0;
      $given=0;
      $amount_in=0;
      $value=0;
      $value_free=0;
      $margin=0;
      $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(`Inventory Transaction Quantity`),0) as qty, ifnull(sum(`Inventory Transaction Amount`),0) as value from  `Inventory Transition Fact` where `Supplier Product ID`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s and `Date`>=%s    ",prepare_mysql($this->data['Supplier Product ID']),prepare_mysql($this->data['Supplier Product Valid From']),prepare_mysql($this->data['Supplier Product Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 week")))  );
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
      if($amount_in==0)
	$margin=0;
      else
	$margin=($value-$value_free)/$amount_in;


      $sql=sprintf("update `Supplier Product Dimension` set `Supplier Product 1 Week Acc Parts Required`=%f ,`Supplier Product 1 Week Acc Parts Provided`=%f,`Supplier Product 1 Week Acc Parts Used`=%f ,`Supplier Product 1 Week Acc Sold Amount`=%f ,`Supplier Product 1 Week Acc Parts Profit`=%f  where `Supplier Product Key`=%d "
		   ,$required
		   ,$provided
		   ,$given+$provided
		   ,$amount_in
		   ,$profit_sold
		   ,$this->id);
      //                  print "$sql\n";
      if(!mysql_query($sql))
	exit("error con not uopdate product part when loading sales");

      break;
      
 }
  }
  
  function get($key=''){
    
    if(array_key_exists($key,$this->data))
      return $this->data[$key];
    
    $_key=preg_replace('/^Supplier Product /','',$key);
    if(isset($this->data[$_key]))
      return $this->data[$key];
    
    
    switch($key){

    }
    
    return false;
  }
  

  function valid_id($id){
    if(is_numeric($id) and $id>0 and $id<9223372036854775807)
      return true;
    else
      return false;
  }
  
  function used_id($id){
    $sql="select count(*) as num from `Supplier Product Dimension` where `Supplier Product ID`=".prepare_mysql($id);
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      if($row['num']>0)
	  return true;
    }
    return false;
  }

    function new_id(){
      $sql="select max(`Supplier Product ID`) as id from `Supplier Product Dimension`";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	return $row['id']+1;
      }else
	return 1;

    }


    function new_part_list($product_list_id,$part_list){
      
      if(!$this->valid_id($product_list_id))
	$product_list_id=$this->new_part_list_id();
      
      $_base_data=array(
			'supplier product id'=>$this->data['Supplier Product ID'],
			'supplier key'=>$this->data['Supplier Product Supplier Key'],
			'part sku'=>'',
			'factor supplier product'=>'',
			'supplier product units per part'=>'',
			'supplier product part valid from'=>date('Y-m-d H:i:s'),
			'supplier product part valid to'=>date('Y-m-d H:i:s'),
			'supplier product part most recent'=>'Yes',
			'supplier product part most recent key'=>'',
			);
      foreach($part_list as $data){
	$base_data=$_base_data;
	foreach($data as $key=>$value){
	  if(isset($base_data[strtolower($key)]))
	    $base_data[strtolower($key)]=_trim($value);
	}
      
	$base_data['supplier product part id']=$product_list_id;

	$keys='(';$values='values(';
	foreach($base_data as $key=>$value){
	  $keys.="`$key`,";
	  if(($key='supplier product part valid from' or $key=='supplier product part valid to') and preg_match('/now/i',$value))
	    $values.="NOW(),";
	  else
	    $values.=prepare_mysql($value).",";
	}
	$keys=preg_replace('/,$/',')',$keys);
	$values=preg_replace('/,$/',')',$values);
	$sql=sprintf("insert into `Supplier Product Part List` %s %s",$keys,$values);

	 if(mysql_query($sql)){
	   $id = mysql_insert_id();
	   
	   if($base_data['supplier product part most recent']=='Yes'){
	     $sql=sprintf("update `Supplier Product Part List`  set `Supplier Product Part Most Recent`='No',`Supplier Product Part Most Recent Key`=%d where `Supplier Product Part ID`=%d and `Product Part Key`!=%d  ",$id,$base_data['supplier product part id'],$id);
	     mysql_query($sql);
	     $sql=sprintf('update `Supplier Product Part List` set `Supplier Product Part Most Recent Key`=%d where `Supplier Product Part Key`=%d',$id,$id);
	     
	     mysql_query($sql);
	   }
	 }else{
	   print "Error can not create new Supplier Product Part\n";exit;
	 }

      }
  
    }




function new_part_list_id(){
  $sql="select max(`Product Part ID`) as id from `Product Part List`";
 $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $id=$row['id']+1;
  }else{
    $id=1;
  }  
  return $id;
}






  }