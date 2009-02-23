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
		      'part status'=>'In Use',
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
		     );
     foreach($data as $key=>$value){
       if(isset( $base_data[strtolower($key)]) )
	 $base_data[strtolower($key)]=_trim($value);
     }
 
     //    if(!$this->valid_sku($base_data['part sku']) ){
     $base_data['part sku']=$this->new_sku();
       // }

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

    //   if($base_data['part most recent']=='Yes')
//       	$sql=sprintf("update  `Part Dimension` set `Part Most Recent Key`=%d where `Part Key`=%d",$this->id,$this->id);
// 	mysql_query($sql);

      $this->get_data('id',$this->id);
    }else{
      print "Error Part can not be created\n";exit;
    }

 }

  function load($data_to_be_read,$args=''){
    switch($data_to_be_read){
    case('stock'):
      $stock='';
      $value='';
      $sql=sprintf("select sum(`Quantity On Hand`) as stock,sum(`Value At Cost`) as value from `Inventory Spanshot Fact` where  `Part SKU`=%d and `Date`=%s",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime('today -1 day'))));
      // print "$sql\n";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$stock=$row['stock'];
	$value=$row['value'];
      }

      if(!is_numeric($stock))
	$stock='NULL';
       if(!is_numeric($value))
	$value='NULL';

       $sql=sprintf("update `Part Dimension` set `Part Current Stock`=%s ,`Part Current Stock Cost`=%s  where `Part Key`=%d "
		    ,$stock
		    ,$value
		   ,$this->id);
      //    print "$sql\n";
      if(!mysql_query($sql))
       	exit("  errorcant not uopdate parts stock");

      break;
    case('stock_history'):
      $astock=0;
      $avalue=0;
      
      $sql=sprintf("select ifnull(avg(`Quantity On Hand`),'ERROR') as stock,avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where  `Part SKU`=%d and `Date`>=%s and `Date`<=%s group by `Date`",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid From']))),prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid To']))  ));
      // print "$sql\n";
      $result=mysql_query($sql);
      $days=0;
      $errors=0;
      $outstock=0;
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	if(is_numeric($row['stock']))
	  $astock+=$row['stock'];
	if(is_numeric($row['value']))
	  $avalue+=$row['value'];
	$days++;

	  if(is_numeric($row['stock']) and $row['stock']==0)
	  $outstock++;
	if($row['stock']=='ERROR')
	  $errors++;
      }
      
      $days_ok=$days-$errors;
      
      $gmroi='NULL';
      if($days_ok>0){
	$astock=$astock/$days_ok;
	$avalue=$avalue/$days_ok;
	if($avalue>0)
	  $gmroi=$this->data['Part Total Profit When Sold']/$avalue;
      }else{
	$astock='NULL';
	$avalue='NULL';
      }

      $tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
      //print "$tdays $days o: $outstock e: $errors \n";
      $unknown=$tdays-$days_ok;
       $sql=sprintf("update `Part Dimension` set `Part Total AVG Stock`=%s ,`Part Total AVG Stock Value`=%s,`Part Total Keeping Days`=%f ,`Part Total Out of Stock Days`=%f , `Part Total Unknown Stock Days`=%s, `Part Total GMROI`=%s where `Part Key`=%d"
		    ,$astock
		    ,$avalue
		    ,$tdays
		    ,$outstock
		    ,$unknown
		    ,$gmroi
		    ,$this->id);
       // print "$sql\n";
       if(!mysql_query($sql))
	 exit("$sql  errot con not update part stock history all");

       $astock=0;
       $avalue=0;
       
       $sql=sprintf("select ifnull(avg(`Quantity On Hand`),'ERROR') as stock,avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where   `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid From']))),prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid To']))  )  ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 year")))  );
       // print "$sql\n";
      $result=mysql_query($sql);
      $days=0;
      $errors=0;
      $outstock=0;
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	if(is_numeric($row['stock']))
	  $astock+=$row['stock'];
	if(is_numeric($row['value']))
	  $avalue+=$row['value'];
	$days++;

	  if(is_numeric($row['stock']) and $row['stock']==0)
	  $outstock++;
	if($row['stock']=='ERROR')
	  $errors++;
      }
      
      $days_ok=$days-$errors;
      
      $gmroi='NULL';
      if($days_ok>0){
	$astock=$astock/$days_ok;
	$avalue=$avalue/$days_ok;
	if($avalue>0)
	  $gmroi=$this->data['Part 1 Year Acc Profit When Sold']/$avalue;
      }else{
	$astock='NULL';
	$avalue='NULL';
      }

      $tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
      //print "$tdays $days o: $outstock e: $errors \n";
      $unknown=$tdays-$days_ok;
       $sql=sprintf("update `Part Dimension` set `Part 1 Year Acc AVG Stock`=%s ,`Part 1 Year Acc AVG Stock Value`=%s,`Part 1 Year Acc Keeping Days`=%f ,`Part 1 Year Acc Out of Stock Days`=%f , `Part 1 Year Acc Unknown Stock Days`=%s, `Part 1 Year Acc GMROI`=%s where `Part Key`=%d"
		    ,$astock
		    ,$avalue
		    ,$tdays
		    ,$outstock
		    ,$unknown
		    ,$gmroi
		    ,$this->id);
       //   print "$sql\n";
       if(!mysql_query($sql))
	 exit("errot con not update part stock history yr aa");


  $astock=0;
       $avalue=0;
       
       $sql=sprintf("select ifnull(avg(`Quantity On Hand`),'ERROR') as stock,avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where   `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid From']))),prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid To']))  )  ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -3 month")))  );
       // print "$sql\n";
      $result=mysql_query($sql);
      $days=0;
      $errors=0;
      $outstock=0;
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	if(is_numeric($row['stock']))
	  $astock+=$row['stock'];
	if(is_numeric($row['value']))
	  $avalue+=$row['value'];
	$days++;

	  if(is_numeric($row['stock']) and $row['stock']==0)
	  $outstock++;
	if($row['stock']=='ERROR')
	  $errors++;
      }
      
      $days_ok=$days-$errors;
      
      $gmroi='NULL';
      if($days_ok>0){
	$astock=$astock/$days_ok;
	$avalue=$avalue/$days_ok;
	if($avalue>0)
	  $gmroi=$this->data['Part 1 Quarter Acc Profit When Sold']/$avalue;
      }else{
	$astock='NULL';
	$avalue='NULL';
      }

      $tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
      //print "$tdays $days o: $outstock e: $errors \n";
      $unknown=$tdays-$days_ok;
       $sql=sprintf("update `Part Dimension` set `Part 1 Quarter Acc AVG Stock`=%s ,`Part 1 Quarter Acc AVG Stock Value`=%s,`Part 1 Quarter Acc Keeping Days`=%f ,`Part 1 Quarter Acc Out of Stock Days`=%f , `Part 1 Quarter Acc Unknown Stock Days`=%s, `Part 1 Quarter Acc GMROI`=%s where `Part Key`=%d"
		    ,$astock
		    ,$avalue
		    ,$tdays
		    ,$outstock
		    ,$unknown
		    ,$gmroi
		    ,$this->id);
       //   print "$sql\n";
       if(!mysql_query($sql))
	 exit("errot con not update part stock history yr bb");

  $astock=0;
       $avalue=0;
       
       $sql=sprintf("select ifnull(avg(`Quantity On Hand`),'ERROR') as stock,avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid From']))),prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid To']))  )  ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 month")))  );
       // print "$sql\n";
      $result=mysql_query($sql);
      $days=0;
      $errors=0;
      $outstock=0;
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	if(is_numeric($row['stock']))
	  $astock+=$row['stock'];
	if(is_numeric($row['value']))
	  $avalue+=$row['value'];
	$days++;

	  if(is_numeric($row['stock']) and $row['stock']==0)
	  $outstock++;
	if($row['stock']=='ERROR')
	  $errors++;
      }
      
      $days_ok=$days-$errors;
      
      $gmroi='NULL';
      if($days_ok>0){
	$astock=$astock/$days_ok;
	$avalue=$avalue/$days_ok;
	if($avalue>0)
	  $gmroi=$this->data['Part 1 Month Acc Profit When Sold']/$avalue;
      }else{
	$astock='NULL';
	$avalue='NULL';
      }

      $tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
      //print "$tdays $days o: $outstock e: $errors \n";
      $unknown=$tdays-$days_ok;
       $sql=sprintf("update `Part Dimension` set `Part 1 Month Acc AVG Stock`=%s ,`Part 1 Month Acc AVG Stock Value`=%s,`Part 1 Month Acc Keeping Days`=%f ,`Part 1 Month Acc Out of Stock Days`=%f , `Part 1 Month Acc Unknown Stock Days`=%s, `Part 1 Month Acc GMROI`=%s where `Part Key`=%d"
		    ,$astock
		    ,$avalue
		    ,$tdays
		    ,$outstock
		    ,$unknown
		    ,$gmroi
		    ,$this->id);
       //   print "$sql\n";
       if(!mysql_query($sql))
	 exit("errot con not update part stock history yr cc");


  $astock=0;
       $avalue=0;
       
       $sql=sprintf("select ifnull(avg(`Quantity On Hand`),'ERROR') as stock,avg(`Value At Cost`) as value from `Inventory Spanshot Fact` where `Part SKU`=%d and `Date`>=%s and `Date`<=%s  and `Date`>=%s    group by `Date`",$this->data['Part SKU'],prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid From']))),prepare_mysql(date("Y-m-d",strtotime($this->data['Part Valid To']))  )  ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 week")))  );
       // print "$sql\n";
      $result=mysql_query($sql);
      $days=0;
      $errors=0;
      $outstock=0;
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	if(is_numeric($row['stock']))
	  $astock+=$row['stock'];
	if(is_numeric($row['value']))
	  $avalue+=$row['value'];
	$days++;

	  if(is_numeric($row['stock']) and $row['stock']==0)
	  $outstock++;
	if($row['stock']=='ERROR')
	  $errors++;
      }
      
      $days_ok=$days-$errors;
      
      $gmroi='NULL';
      if($days_ok>0){
	$astock=$astock/$days_ok;
	$avalue=$avalue/$days_ok;
	if($avalue>0)
	  $gmroi=$this->data['Part 1 Week Acc Profit When Sold']/$avalue;
      }else{
	$astock='NULL';
	$avalue='NULL';
      }

      $tdays = (strtotime($this->data['Part Valid To']) - strtotime($this->data['Part Valid From'])) / (60 * 60 * 24);
      //print "$tdays $days o: $outstock e: $errors \n";
      $unknown=$tdays-$days_ok;
       $sql=sprintf("update `Part Dimension` set `Part 1 Week Acc AVG Stock`=%s ,`Part 1 Week Acc AVG Stock Value`=%s,`Part 1 Week Acc Keeping Days`=%f ,`Part 1 Week Acc Out of Stock Days`=%f , `Part 1 Week Acc Unknown Stock Days`=%s, `Part 1 Week Acc GMROI`=%s where `Part Key`=%d"
		    ,$astock
		    ,$avalue
		    ,$tdays
		    ,$outstock
		    ,$unknown
		    ,$gmroi
		    ,$this->id);
       //   print "$sql\n";
       if(!mysql_query($sql))
	 exit("errot con not update part stock history yr");

      break;
    case("used in"):
       $used_in_products='';
      $sql=sprintf("select `Product Same Code Most Recent Key`,`Product Code` from `Product Part List` PPL left join `Product Dimension` PD on (PD.`Product ID`=PPL.`Product ID`)  where `Part SKU`=%d group by `Product Code`;",$this->data['Part SKU']);
      $result=mysql_query($sql);
      //      print "$sql\n";
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
      $margin=0;
      $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transition Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s   ",prepare_mysql($this->data['Part SKU']),prepare_mysql($this->data['Part Valid From']),prepare_mysql($this->data['Part Valid To'])  );
      //       print "$sql\n\n\n";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$required=$row['required'];
	$provided=$row['qty'];
	$given=$row['given'];
	$amount_in=floatval($row['amount_in']);
	$value=floatval($row['value']);
	$value_free=floatval($row['value_free']);
	$sold=$row['qty']-$row['given'];
      }
      $abs_profit=$amount_in-$value;
      $profit_sold=$amount_in-$value+$value_free;
      if($amount_in==0)
	$margin=0;
      else{
	$margin=$profit_sold/$amount_in;
	//	$margin=($value-$value_free)/$amount_in;
	//	$margin=sprintf("%.6f",($value)*$tmp);
	$margin=preg_replace('/:/','1',$margin);
	//$margin=$value/$amount_in;
      }

  //     var_dump( $value );
//       var_dump(  $amount_in);
//       var_dump( 0.7/7 );
      $sql=sprintf("update `Part Dimension` set `Part Total Required`=%f ,`Part Total Provided`=%f,`Part Total Given`=%f ,`Part Total Sold Amount`=%f ,`Part Total Absolute Profit`=%f ,`Part Total Profit When Sold`=%f , `Part Total Sold`=%f , `Part Total Margin`=%f  where `Part Key`=%d "
		   ,$required
		   ,$provided
		   ,$given
		   ,$amount_in
		   ,$abs_profit
		   ,$profit_sold,$sold,$margin
		   ,$this->id);
      //    print "$sql\n";
      if(!mysql_query($sql))
       	exit("  error a $margin b $value c $value_free d $amount_in  con not uopdate product part when loading sales");
	
      $sold=0;
      $required=0;
      $provided=0;
      $given=0;
      $amount_in=0;
      $value=0;
      $value_free=0;
      $margin=0;
      $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transition Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s  and `Date`>=%s     ",prepare_mysql($this->data['Part SKU']),prepare_mysql($this->data['Part Valid From']),prepare_mysql($this->data['Part Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 year")))  );
      // print "$sql\n";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$required=$row['required'];
	$provided=$row['qty'];
	$given=$row['given'];
	$amount_in=$row['amount_in'];
	$value=$row['value'];
	$value_free=$row['value_free'];
	$sold=$row['qty']-$row['given'];
      }
      $abs_profit=$amount_in-$value;
      $profit_sold=$amount_in-$value+$value_free;
      if($amount_in==0)
	$margin=0;
      else
	$margin=$profit_sold/$amount_in;
      $sql=sprintf("update `Part Dimension` set `Part 1 Year Acc Required`=%f ,`Part 1 Year Acc Provided`=%f,`Part 1 Year Acc Given`=%f ,`Part 1 Year Acc Sold Amount`=%f ,`Part 1 Year Acc Absolute Profit`=%f ,`Part 1 Year Acc Profit When Sold`=%f , `Part 1 Year Acc Sold`=%f , `Part 1 Year Acc Margin`=%s where `Part Key`=%d "
		   ,$required
		   ,$provided
		   ,$given
		   ,$amount_in
		   ,$abs_profit
		   ,$profit_sold,$sold,$margin
		   ,$this->id);
      //  print "$sql\n";
      if(!mysql_query($sql))
	exit(" $sql\n error con not uopdate product part when loading sales");
      
      $sold=0;
       $required=0;
      $provided=0;
      $given=0;
      $amount_in=0;
      $value=0;
      $value_free=0;
      $margin=0;
      $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transition Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s  and `Date`>=%s     ",prepare_mysql($this->data['Part SKU']),prepare_mysql($this->data['Part Valid From']),prepare_mysql($this->data['Part Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -3 month")))  );
      //      print "$sql\n";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$required=$row['required'];
	$provided=$row['qty'];
	$given=$row['given'];
	$amount_in=$row['amount_in'];
	$value=$row['value'];
	$value_free=$row['value_free'];
	$sold=$row['qty']-$row['given'];
      }
      $abs_profit=$amount_in-$value;
      $profit_sold=$amount_in-$value+$value_free;

      if($amount_in==0)
	$margin=0;
      else
	$margin=$profit_sold/$amount_in;

      $sql=sprintf("update `Part Dimension` set `Part 1 Quarter Acc Required`=%f ,`Part 1 Quarter Acc Provided`=%f,`Part 1 Quarter Acc Given`=%f ,`Part 1 Quarter Acc Sold Amount`=%f ,`Part 1 Quarter Acc Absolute Profit`=%f ,`Part 1 Quarter Acc Profit When Sold`=%f  , `Part 1 Quarter Acc Sold`=%f  , `Part 1 Quarter Acc Margin`=%s where `Part Key`=%d "
		   ,$required
		   ,$provided
		   ,$given
		   ,$amount_in
		   ,$abs_profit
		   ,$profit_sold,$sold,$margin
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
      $margin=0;
      $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transition Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s  and `Date`>=%s     ",prepare_mysql($this->data['Part SKU']),prepare_mysql($this->data['Part Valid From']),prepare_mysql($this->data['Part Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 month")))  );
      //      print "$sql\n";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$required=$row['required'];
	$provided=$row['qty'];
	$given=$row['given'];
	$amount_in=$row['amount_in'];
	$value=$row['value'];
	$value_free=$row['value_free'];
	$sold=$row['qty']-$row['given'];
      }
      $abs_profit=$amount_in-$value;
      $profit_sold=$amount_in-$value+$value_free;

      if($amount_in==0)
	$margin=0;
      else
	$margin=$profit_sold/$amount_in;


      $sql=sprintf("update `Part Dimension` set `Part 1 Month Acc Required`=%f ,`Part 1 Month Acc Provided`=%f,`Part 1 Month Acc Given`=%f ,`Part 1 Month Acc Sold Amount`=%f ,`Part 1 Month Acc Absolute Profit`=%f ,`Part 1 Month Acc Profit When Sold`=%f  , `Part 1 Month Acc Sold`=%f , `Part 1 Month Acc Margin`=%s  where `Part Key`=%d "
		   ,$required
		   ,$provided
		   ,$given
		   ,$amount_in
		   ,$abs_profit
		   ,$profit_sold,$sold,$margin
		   ,$this->id);
      //            print "$sql\n";
      if(!mysql_query($sql))
	exit(" $sql\n error con not uopdate product part when loading sales");

  $sold=0;
         $required=0;
      $provided=0;
      $given=0;
      $amount_in=0;
      $value=0;
      $value_free=0;
      $margin=0;
      $sql=sprintf("select   ifnull(sum(`Given`*`Inventory Transaction Amount`/(`Inventory Transaction Quantity`)),0) as value_free,   ifnull(sum(`Required`),0) as required, ifnull(sum(`Given`),0) as given, ifnull(sum(`Amount In`),0) as amount_in, ifnull(sum(-`Inventory Transaction Quantity`),0) as qty, ifnull(sum(-`Inventory Transaction Amount`),0) as value from  `Inventory Transition Fact` where `Part SKU`=%s and `Inventory Transaction Type`='Sale' and `Date`>=%s  and `Date`<=%s  and `Date`>=%s     ",prepare_mysql($this->data['Part SKU']),prepare_mysql($this->data['Part Valid From']),prepare_mysql($this->data['Part Valid To']) ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now -1 week")))  );
      //      print "$sql\n";
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$required=$row['required'];
	$provided=$row['qty'];
	$given=$row['given'];
	$amount_in=$row['amount_in'];
	$value=$row['value'];
	$value_free=$row['value_free'];$sold=$row['qty']-$row['given'];
      }
      $abs_profit=$amount_in-$value;
      $profit_sold=$amount_in-$value+$value_free;
      if($amount_in==0)
	$margin=0;
      else
	$margin=$profit_sold/$amount_in;

      $sql=sprintf("update `Part Dimension` set `Part 1 Week Acc Required`=%f ,`Part 1 Week Acc Provided`=%f,`Part 1 Week Acc Given`=%f ,`Part 1 Week Acc Sold Amount`=%f ,`Part 1 Week Acc Absolute Profit`=%f ,`Part 1 Week Acc Profit When Sold`=%f  , `Part 1 Week Acc Sold`=%f , `Part 1 Week Acc Margin`=%s where `Part Key`=%d "
		   ,$required
		   ,$provided
		   ,$given
		   ,$amount_in
		   ,$abs_profit
		   ,$profit_sold,$sold,$margin
		   ,$this->id);
      //            print "$sql\n";
      if(!mysql_query($sql))
	exit(" $sql\n error con not uopdate product part when loading sales");



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