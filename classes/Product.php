<?
include_once('Deal.php');
include_once('SupplierProduct.php');
include_once('Part.php');

class product{
 
  var $product=array();
  var $categories=array();

  var $parents=array();
  var $childs=array();
  var $supplier=false;
  var $locations=false;
  var $notes=array();
  var $images=false;
  var $weblink=false;

  var $new=false;
  var $new_id=false;
  var $location_to_update=false;
  var $id=false;
  var $unknown_txt='Unknown';

  function __construct($a1,$a2=false,$a3=false) {
    //    $this->db =MDB2::singleton();
    if(is_numeric($a1) and !$a2){
      $this->get_data('id',$a1);
    }
    else if(($a1=='new' or $a1=='create') and is_array($a2) ){
      $this->msg=$this->create($a2);
    } else
      $this->get_data($a1,$a2,$a3);
  }



  function get_data($tipo,$tag,$extra=false){
    global $_shape,$_units_tipo,$_units_tipo_abr,$_units_tipo_plural;
    if($tipo=='id'){
      $sql=sprintf("select * from `Product Dimension` where `Product Key`=%d ",$tag);

      $result=mysql_query($sql);

      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )


	$this->id=$this->data['Product Key'];
      return;
    }elseif($tipo=='code'){
      $sql=sprintf("select * from `Product Dimension` where `Product Code`=%s and `Product Most Recent`='Yes' ",prepare_mysql($tag));
      $result=mysql_query($sql);
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
	$this->id=$this->data['Product Key'];
      }
      return;
      
    }elseif($tipo=='code-name-units-price'){
      $auto_add=$tag['auto_add'];
      

  //     if($tag['product code']=='wsl-123')
//  	print_r($tag);

      $sql=sprintf("select * from `Product Dimension` where `Product Code`=%s and `Product Name`=%s and `Product Units Per Case`=%s and `Product Unit Type`=%s  and `Product Price`=%s  "
		   ,prepare_mysql($tag['product code'])
		   ,prepare_mysql($tag['product name'])
		   ,prepare_mysql($tag['product units per case'])
		   ,prepare_mysql($tag['product unit type'])
		   ,prepare_mysql($tag['product price'])
		   ); 
      $result=mysql_query($sql);
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){

	//	print $tag['product code']." FOUND OLS\n";

	$this->id=$this->data['Product Key'];

	if(strtotime($this->data['Product Valid To'])<strtotime($tag['date2'])  ){
	  $sql=sprintf("update `Product Dimension` set `Product Valid To`=%s where `Product Key`=%d",prepare_mysql($tag['date2']),$this->id);
	  $this->data['product valid to']=$tag['date2'];
	  mysql_query($sql);
	  
	}
	if(strtotime($this->data['Product Valid From'])>strtotime($tag['date'])  ){
	  $sql=sprintf("update `Product Dimension` set `Product Valid From`=%s where `Product Key`=%d",prepare_mysql($tag['date']),$this->id);
	  mysql_query($sql);
	  $this->data['Product Valid From']=$tag['date'];
	}

	//found the part


	$sql=sprintf("select  count(Distinct `Product Part ID`) as lists  from `Product Part List`  where  `Product ID`=%s  " ,$this->data['Product ID']);
	$result2=mysql_query($sql);
	if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
	  $num_lists=$row2['lists'];
	  if($num_lists!=1)
	    exit("$sql  error wrong numer if lists\n");
	}

 	$sql=sprintf("select  `Part SKU`, `Product Part ID`  from `Product Part List`  where  `Product ID`=%s  " ,$this->data['Product ID']);
 	$result2=mysql_query($sql);
 	$part_skus=array();
	// only whorls with single ones
	if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
	  $part_sku=$row2['Part SKU'];
	  $part_skus[]=$part_sku;
	  $this->part_sku=$row2['Part SKU'];
	  $this->product_part_id=$row2['Product Part ID'];
	  $sql=sprintf("update `Part Dimension`  set `Part Valid From`=%s  where `Part SKU`=%s and `Part Valid From`>%s"
		       ,prepare_mysql($tag['date'])
		       ,prepare_mysql($part_sku)
		       ,prepare_mysql($tag['date'])
		       );
	  mysql_query($sql);
	  
	  $sql=sprintf("update `Part Dimension`  set `Part Valid To`=%s  where `Part SKU`=%s and `Part Valid To`<%s"
		       ,prepare_mysql($tag['date2'])
		       ,prepare_mysql($part_sku)
		       ,prepare_mysql($tag['date2'])
		       );
	  mysql_query($sql);
	}

	$sqldd=sprintf("update `Product Part List`  set `Product Part Valid From`=%s  where `Product Part ID`=%s and `Product Part Valid From`>%s"
		     ,prepare_mysql($tag['date'])
		     ,$this->product_part_id
		     ,prepare_mysql($tag['date'])
		     );
	mysql_query($sqldd);

	$sqlee=sprintf("update `Product Part List`  set `Product Part Valid To`=%s  where `Product Part ID`=%s and `Product Part Valid To`<%s"
		     ,prepare_mysql($tag['date2'])
		     ,$this->product_part_id
		     ,prepare_mysql($tag['date2'])
		     );
	mysql_query($sqlee);
	

	$supplier=new Supplier('code',$tag['supplier code']);
	if(!$supplier->id){
	  $data=array(
		      'name'=>$tag['supplier name'],
		      'code'=>$tag['supplier code'],
		      'from'=>$tag['date'],
		      'to'=>$tag['date2']
		      );
	$supplier=new Supplier('new',$data);
	}	
	$sp_data=array(
		       'supplier product supplier key'=>$supplier->id,
		       'supplier product supplier code'=>$supplier->data['Supplier Code'],
		       'supplier product supplier name'=>$supplier->data['Supplier Name'],
		       'supplier product code'=>$tag['supplier product code'],
		       'supplier product cost'=>$tag['supplier product cost'],
		       'supplier product name'=>$tag['supplier product name'],
		       'auto_add'=>true,
		       'supplier product valid from'=>$tag['date'],
		       'supplier product valid to'=>$tag['date2']
		       );
	//  print_r($sp_data);
	$supplier_product=new SupplierProduct('supplier-code-cost',$sp_data);
	
	if(!$supplier_product->id)
	  exit("error culnt create supplier producr Product.php 185\n");
	$this->supplier_product_key=$supplier_product->id;
	
	if($supplier_product->new_id ){
	  //make new part list
	  //print "caca";
	  foreach($part_skus as $sku){
	    $part=new Part('sku',$sku);
	    if(!$part->id)
	      exit("error culnt create part Product.php 193\n");
	    $rules[]=array(
			   'Part SKU'=>$part->data['Part SKU']
			   ,'Supplier Product Units Per Part'=>$this->data['Product Units Per Case']
			   ,'supplier product part most recent'=>'Yes'
			   ,'supplier product part valid from'=>$tag['date']
			   ,'supplier product part valid to'=>$tag['date2']
			   ,'factor supplier product'=>1
			   );
	    $supplier_product->new_part_list('',$rules);
	    $supplier_product->load('used in');
	    $part->load('supplied by');
	  }
	  $this->load('parts');

	}else{
	  //check if the sppl exist if not create
	  $sql=sprintf('select `Supplier Product Code`,`Supplier Product Valid From`,`Supplier Product Valid To`,`Supplier Product Key`,SPD.`Supplier Product ID`,`Supplier Product Units Per Part`,`Supplier Product Cost` from  `Supplier Product Dimension`   SPD left join `Supplier Product Part List` SPPL  on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`) where `Part SKU`=%d  and `Supplier Product Key`=%d',$this->part_sku,$supplier_product->id);
	  $result_=mysql_query($sql);
	  if(!$row2=mysql_fetch_array($result_, MYSQL_ASSOC)){
	    $part=new Part('sku',$this->part_sku);
	    if(!$part->id)
	      exit("error culnt create part Product.php 193\n");

	    $rules[]=array(
			   'Part SKU'=>$this->part_sku
			   ,'Supplier Product Units Per Part'=>$this->data['Product Units Per Case']
			   ,'supplier product part most recent'=>'Yes'
			   ,'supplier product part valid from'=>$tag['date']
			   ,'supplier product part valid to'=>$tag['date2']
			   ,'factor supplier product'=>1
			   );
	    $supplier_product->new_part_list('',$rules);
	    $supplier_product->load('used in');
	    $part->load('supplied by');
	    $this->load('parts');
	    

	  }

	}

	$sqlw=sprintf("update `Supplier Product Dimension`  set `Supplier Product Valid From`=%s  where `Supplier Product ID`=%s and `Supplier Product Valid From`>%s"
			 ,prepare_mysql($tag['date'])
			  ,$supplier_product->id
			 ,prepare_mysql($tag['date'])
			 );

	mysql_query($sqlw);
	
	$sqlaa=sprintf("update `Supplier Product Dimension`  set `Supplier Product Valid To`=%s  where `Supplier Product ID`=%s and `Supplier Product Valid To`<%s"
		       ,prepare_mysql($tag['date2'])
		       ,$supplier_product->id
		       ,prepare_mysql($tag['date2'])
		       );
	mysql_query($sqlaa);
	
	

	
	
	
	return;
      }



      if(!$auto_add)
	return;
      

      
      $different_price=true;
      $different_name=true;
      $different_units=true;
      $different_units_type=true;
      $this->new=true;
      $this->new_code=false;
      $this->new_id=false;
      $this->new_part=false;
      $this->new_supplier_product=false;

      $sql=sprintf("select count(*) as num from `Product Dimension` where `Product Code`=%s  "
		   ,prepare_mysql($tag['product code'])
		 
		   );

      //$result2 =& $this->db->query($sql);
      $result2=mysql_query($sql);
      
      if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
	$number_sp=$row2['num'];
      }

      // print "$number_sp\n";
      if($number_sp==0){
	// ****************************************  NEW CODE ************************************************
	//	print "NEW Cpde\n";
	$this->new_code=true;
	$tag['product id']=$this->new_id();
	$tag['product most recent']='Yes';
	$tag['Part Most Recent']='Yes';
	$tag['product valid to']=$tag['date2'];
	$tag['product valid from']=$tag['date'];
	$tag['part valid to']=$tag['date2'];
	$tag['part valid from']=$tag['date'];
	$this->create($tag);
	$tag['Part XHTML Currently Used In']=sprintf('<a href="product.php?%d">%s</a>',$this->id,$this->data['Product Code']);
	$tag['Part XHTML Description']=preg_replace('/\(.*\)\s*$/i','',$this->get('Product XHTML Short Description'));
	$part=new Part('new',$tag);
	if(!$part->id)
	      exit("error culnt create part Product.php 278\n");

	$part_list[]=array(
			   'Product ID'=>$this->get('Product ID'),
			   'Part SKU'=>$part->get('Part SKU'),
			   'Product Part Id'=>1,
			   'requiered'=>'Yes',
			   'Parts Per Product'=>1,
			   'Product Part Type'=>'Simple Pick'
			   );
	$this->product_part_id=$this->new_part_list('',$part_list);
	$this->part_sku=$part->get('Part SKU');
	

	$part->load('used in');
	$this->load('parts');
	$supplier=new Supplier('code',$tag['supplier code']);
	if(!$supplier->id){
	  $data=array(
		      'name'=>$tag['supplier name'],
		      'code'=>$tag['supplier code'],
		      'from'=>$tag['date'],
		      'to'=>$tag['date2']
		      );
	$supplier=new Supplier('new',$data);
	}	
	$sp_data=array(
		       'supplier product supplier key'=>$supplier->id,
		       'supplier product supplier code'=>$supplier->data['Supplier Code'],
		       'supplier product supplier name'=>$supplier->data['Supplier Name'],
		       'supplier product code'=>$tag['supplier product code'],
		       'supplier product cost'=>$tag['supplier product cost'],
		       'supplier product name'=>$tag['supplier product name'],
		       'auto_add'=>true,
		       'supplier product valid from'=>$tag['date'],
		       'supplier product valid to'=>$tag['date2']
		       );
	//  print_r($sp_data);
	$supplier_product=new SupplierProduct('supplier-code-cost',$sp_data);
	if(!$supplier_product->id)
	  exit("error culnt create supplier producr Product.php 310\n");
	$this->supplier_product_key=$supplier_product->id;
	
	$rules[]=array(
		       'Part SKU'=>$part->data['Part SKU']
		       ,'Supplier Product Units Per Part'=>$this->data['Product Units Per Case']
		       ,'supplier product part most recent'=>'Yes'
		       ,'supplier product part valid from'=>$tag['date']
		       ,'supplier product part valid to'=>$tag['date2']
		       ,'factor supplier product'=>1
		       );
	$supplier_product->new_part_list('',$rules);
	$supplier_product->load('used in');
	$part->load('supplied by');
	$this->load('parts');
	return;
      }else{
	//old code
	$sql=sprintf("select * from `Product Dimension` where `Product Code`=%s and  `Product Units Per Case`=%s"
		     ,prepare_mysql($tag['product code'])
		     ,prepare_mysql($tag['product units per case'])
		     );
	//print "$sql\n";
	$result2=mysql_query($sql);
	if($same_id_data=mysql_fetch_array($result2, MYSQL_ASSOC)){
	  //print "new price \n";
	  // Price  or name change, same units per case
	  $different_name=false;
	  $different_units=false;
	  $different_units_type=false;
	  $this->new_id=false;
	  $tag['product id']=$same_id_data['Product ID'];
	  $sql=sprintf("select *  from  `Product Dimension` where  `Product Most Recent`='Yes' and `Product ID`=%d  ",$same_id_data['Product ID']);
	  $result3=mysql_query($sql);
	  $result3=mysql_query($sql);
	  if($row3=mysql_fetch_array($result3, MYSQL_ASSOC) ){
	    $last_day=$row3['Product Valid To'];
	    if(strtotime($last_day)<strtotime($tag['date'])){
	      $tag['product most recent']='Yes';
	    }else{
	      $tag['product most recent']='No';
	      $tag['product most recent key']=$row3['Product Key'];
	    }
	  }else{
	    print_r($tag);
	    print "$sql\n";
	    exit("error in product ceratuon 45667303");
	  }
	  $tag['product valid to']=$tag['date2'];
	  $tag['product valid from']=$tag['date'];
	  $this->create($tag);


	  $sql=sprintf("select  count(Distinct `Product Part ID`) as lists  from `Product Part List`  where  `Product ID`=%s  " ,$this->data['Product ID']);
	  $result2111=mysql_query($sql);
	  if($row2111=mysql_fetch_array($result2111, MYSQL_ASSOC)){
	    $num_lists=$row2111['lists'];
	    if($num_lists!=1)
	      exit("$sql  error wrong numer if lists BIS\n");
	}

	  $sql=sprintf("select  `Product Part ID`,`Part SKU`  from `Product Part List`  where  `Product ID`=%s  " ,$this->data['Product ID']);
	  $result2111=mysql_query($sql);
	  
	  if($row2111=mysql_fetch_array($result2111, MYSQL_ASSOC)){
	    $this->product_part_id=$row2111['Product Part ID'];
	    $this->part_sku=$row2111['Part SKU'];
	  }

	  // find sku 
	
	}else{
	  //  print "new price new part\n";
	  // Different units that meabs niew id and new product part list
	  $this->new_id=true;
	  $tag['product id']=$this->new_id();
	  $tag['product most recent']='Yes';
	  
	  
	  $sql=sprintf("select * from `Product Dimension` where `Product Code`=%s   ",prepare_mysql($tag['product code']));
	  $result4=mysql_query($sql);
	  if($most_recent_product_data=mysql_fetch_array($result4, MYSQL_ASSOC) ){
	    $tag['product family key']=$most_recent_product_data['Product Family Key'];
	    $tag['product family code']=$most_recent_product_data['Product Family Code'];
	    $tag['product family name']=$most_recent_product_data['Product Family Name'];
	    $tag['product main department key']=$most_recent_product_data['Product Main Department Key'];
	    $tag['product main department name']=$most_recent_product_data['Product Main Department Name'];
	    $tag['product main department code']=$most_recent_product_data['Product Main Department Code'];
	  }
	  $tag['product valid to']=$tag['date2'];
	  $tag['product valid from']=$tag['date'];
	  
	  $this->create($tag);
 	  $tag['Part Most Recent']='Yes';
 	  $tag['Part Valid From']=$tag['date'];
 	  $tag['Part Valid To']=$tag['date2'];
 	  $tag['Part XHTML Currently Used In']=sprintf('<a href="product.php?%d">%s</a>',$this->id,$this->data['Product Code']);
	  $tag['Part XHTML Description']=preg_replace('/\(.*\)\s*$/i','',$this->get('Product XHTML Short Description'));

 	  $this->new_part=true;
	  $part=new Part('new',$tag);
	   if(!$part->id)
	      exit("error culnt create part Product.php 193\n");
	  $part_list[]=array(
			     'Product ID'=>$this->get('Product ID'),
			     'Part SKU'=>$part->data['Part SKU'],
			     'Product Part Id'=>1,
			     'requiered'=>'Yes',
			     'Parts Per Product'=>1,
			     'Product Part Type'=>'Simple Pick'
			     );
	  $this->product_part_id=$this->new_part_list('',$part_list);
	  $this->part_sku=$part->data['Part SKU'];
	  $this->load('parts');
	  $part->load('used in');


	}
      }
    
      


      
      $supplier=new Supplier('code',$tag['supplier code']);
      if(!$supplier->id){
	$data=array(
		    'name'=>$tag['supplier name'],
		    'code'=>$tag['supplier code'],
		    'from'=>$tag['date'],
		    'to'=>$tag['date2']
		    );


	$supplier=new Supplier('new',$data);

      }
	
      $sp_data=array(
		     'supplier product supplier key'=>$supplier->id,
		     'supplier product supplier code'=>$supplier->data['Supplier Code'],
		     'supplier product supplier name'=>$supplier->data['Supplier Name'],
		     'supplier product code'=>$tag['supplier product code'],
		     'supplier product cost'=>$tag['supplier product cost'],
		     'supplier product name'=>$tag['supplier product name'],
		     'auto_add'=>true,
		     'supplier product valid from'=>$tag['date'],
		     'supplier product valid to'=>$tag['date2']
		     );
      //  print_r($sp_data);
      $supplier_product=new SupplierProduct('supplier-code-cost',$sp_data);
      if(!$supplier_product->id)
	exit("error culnt create supplier producr Product.php 185\n");
      $this->supplier_product_key=$supplier_product->id;

      $this->load('parts');

      if($supplier_product->new_id or $this->new_part){
	if($this->new_part){
	  $rules[]=array(
			 'Part SKU'=>$part->data['Part SKU']
			 ,'Supplier Product Units Per Part'=>$this->data['Product Units Per Case']
			 ,'supplier product part most recent'=>'Yes'
			 ,'supplier product part valid from'=>$tag['date']
			 ,'supplier product part valid to'=>$tag['date2']
			 ,'factor supplier product'=>1
			 );
	  $supplier_product->new_part_list('',$rules);
	  $supplier_product->load('used in');
	  $part->load('supplied by');
	  $this->load('parts');
	}else{
	  
	  $sql=sprintf("select  `Part SKU`  from `Product Part List`  where  `Product Part ID`=%s group by `Part SKU`" ,$this->product_part_id);
	  $result211=mysql_query($sql);
	  $part_skus=array();
	  while($row211=mysql_fetch_array($result211, MYSQL_ASSOC)){
	    $part_sku=$row211['Part SKU'];
	    $this->part_sku=$part_sku;
	    $part_skus[]=$part_sku;
	  }
	  
	  foreach($part_skus as $sku){
	    $part=new Part('sku',$sku);
	    if(!$part->id)
	      exit("error culnt create part Product.php 193\n");
	    $rules[]=array(
			   'Part SKU'=>$part->data['Part SKU']
			    ,'Supplier Product Units Per Part'=>$this->data['Product Units Per Case']
			   ,'supplier product part most recent'=>'Yes'
			   ,'supplier product part valid from'=>$tag['date']
			    ,'supplier product part valid to'=>$tag['date2']
			   ,'factor supplier product'=>1
			    );
	     $supplier_product->new_part_list('',$rules);
	     $supplier_product->load('used in');
	     $part->load('supplied by');
	   }
	  $this->load('parts');
	}
      }else{

	 //check if the sppl exist if not create
	  $sql=sprintf('select `Supplier Product Code`,`Supplier Product Valid From`,`Supplier Product Valid To`,`Supplier Product Key`,SPD.`Supplier Product ID`,`Supplier Product Units Per Part`,`Supplier Product Cost` from  `Supplier Product Dimension`   SPD left join `Supplier Product Part List` SPPL  on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`) where `Part SKU`=%d  and `Supplier Product Key`=%d',$this->part_sku,$supplier_product->id);
	  $result_=mysql_query($sql);
	  if(!$row2=mysql_fetch_array($result_, MYSQL_ASSOC)){
	    $part=new Part('sku',$this->part_sku);
	    if(!$part->id)
	      exit("error culnt create part Product.php 193\n");

	    $rules[]=array(
			   'Part SKU'=>$this->part_sku
			   ,'Supplier Product Units Per Part'=>$this->data['Product Units Per Case']
			   ,'supplier product part most recent'=>'Yes'
			   ,'supplier product part valid from'=>$tag['date']
			   ,'supplier product part valid to'=>$tag['date2']
			   ,'factor supplier product'=>1
			   );
	    $supplier_product->new_part_list('',$rules);
	    $supplier_product->load('used in');
	    $part->load('supplied by');
	    $this->load('parts');
	    

	  }

      }
      //      if($supplier_product->new){
      //print_r($supplier_product->data);
      //exit;
      // }

  }
  }



  


  function get($key='',$data=false){
    
    if(array_key_exists($key,$this->data))
      return $this->data[$key];
    

    switch($key){
    case('Parts SKU'):
      $sql=sprintf("select `Part SKU` from `Product Part List` where `Product ID`=%d and `Product Part Most Recent`='Yes';",$this->data['Product ID']);
      $result=mysql_query($sql);
      $parts=array();
     while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       $parts[]=$row['Part SKU'];
     }
     return $parts;
      break;
    case('Product Total Invoiced Net Amount'):
      return $this->data['Product Total Invoiced Gross Amount']-$this->data['Product Total Invoiced Discount Amount'];
    case('formated total net sales'):
      return money($this->data['Product Total Invoiced Gross Amount']-$this->data['Product Total Invoiced Discount Amount']);
    case('Formated Product Total Quantity Invoiced'):
      return number($this->data['Product Total Quantity Invoiced']);
    case('formated price'):
      return money($this->data['Product Price']);
      break;
    case('formated unitary rrp'):
      return money($this->data['Product Unitary RRP']);
      break;
 case('Formated Weight'):
      return number($this->data['Product Net Weight'])."Kg";
      break;

    case('short description'):
      global $myconf;
      $desc='';
      if($this->get('Product Units Per Case')>1){
	$desc=number($this->get('Product Units Per Case')).'x ';
	}
      $desc.=' '.$this->get('Product Name');
      if($this->get('Product Price')>0){
	$desc.=' ('.money($this->get('Product Units Per Case')).')';
	}
      
      return _trim($desc);

    case('xhtml short description'):
      global $myconf;
      $desc='';
      if($this->get('Product Units Per Case')>1){
	$desc=number($this->get('Product Units Per Case')).'x ';
	}
      $desc.=' <span class="prod_sdesc">'.$this->get('Product Name').'</span>';
      if($this->get('Product Price')>0){
	$desc.=' ('.money($this->get('Product Units Per Case')).')';
	}
      
      return _trim($desc);
    case('p2l_id'):
      $key=key($data);
      if(!$this->locations)
	$this->load('locations');
      foreach($this->locations['data'] as $_id=>$_loc){
	if($_loc[$key]==$data[$key])
	  return $_id;
      }
      return false;
    case('weblinks'):
      if(!$this->weblink)
	$this->load('weblinks');
      return $this->weblink;
      break;
    case('num_links'):
    case('num_weblinks'):
      if(!$this->weblink)
	$this->load('weblinks');
      return count($this->weblink);
      break;
    case('new_image'):
      if(isset($this->changing_img)  and isset($this->images[$this->changing_img]))
	return $this->images[$this->changing_img];
      else
	return false;
      break;
    case('tsall'):
    case('tsy'):
    case('tsq'):
    case('tsm'):
    case('tsw'):
    case('tsoall'):
    case('tsoy'):
    case('tsoq'):
    case('tsom'):
    case('tsow'):
    case('awtsall'):
    case('awtsy'):
    case('awtsq'):
    case('awtsm'):
    case('awtsoall'):
    case('awtsoy'):
    case('awtsoq'):
    case('awtsom'):
   //    if(!isset($this->data['sales'][$key]))
// 	$this->get('sales');


//       return $this->data['sales'][$key];
      break;
    case('sales'):
    //   $this->data['sales']=array();
//       $sql=sprintf("select * from sales  where tipo='prod' and tipo_id=%d",$this->id);
//       if($result =& $this->db->query($sql)){
// 	$this->data['sales']=$result->fetchRow();
//       }else{
// 	$this->load('sales');
// 	$this->save('sales');
	
//       }
      break;
    case('img_caption'):
      return $this->images[$this->changing_img]['caption'];
      break;
    case('img_new'):
      return $this->images[0];
      break;
    case('vol'):
      $this->data['vol']=volumen($this->data['dim_tipo'],$this->data['dim']);
      break;
    case('ovol'):
      $this->data['ovol']=volumen($this->data['odim_tipo'],$this->data['odim']);
      break;
    case('a_dim'):
      if($this->data['dim']!='')
	$a_dim=array($this->data['dim']);
      split('x',$this->data['dim']);
    case('mysql_first_date'):
      return  date("Y-m-d",strtotime("@".$this->data['first_date']));;
      break;
    case('formated product for sale since date'):
      $date=strtotime($this->data['Product For Sale Since Date']);
      if($date)
	return date("d/m/Y",$date);
      else
	return $this->unknown_txt;
      break;
    case('weeks'):
      if(!isset( $this->data['weeks'])){

	if(is_numeric($this->data['first_date'])){
	  $date1=date('d-m-Y',strtotime('@'.$this->data['first_date']));
	  $day1=date('N')-1;
	  $date2=date('d-m-Y');
	  $days=datediff('d',$date1,$date2);
	  $weeks=number_weeks($days,$day1);
	}else
	  $weeks=0;
	$this->data['weeks']=$weeks;
      }

      return $this->data['weeks'];
      
      break;
    case('num_suppliers'):
    case('number_of_suppliers'):
      if(!$this->supplier)
	$this->load('suppliers');
      return  count($this->supplier);
    case('num_pics'):
    case('num_images'):
      if(!$this->images)
	$this->load('images');
      return count($this->images);
    case('dimension'):
      global $_shape;
      if($this->data['dim']!='')
	return $_shape[$this->data['dim_tipo']]." (".$this->data['dim'].")".$this->dim_units;
      else
	return '';
      break;
    case('odimension'):
      global $_shape;
      if($this->data['odim']!='')
	return $_shape[$this->data['odim_tipo']]." (".$this->data['odim'].")".$this->dim_units;
      else
	return '';
      break;  
    case('max_units_per_location'):
      $p2l_id=false;
      $_key=key($data);
      if($_key=='id'){
	$p2l_id=$data[$_key];
      }
      if(isset($this->locations['data'][$p2l_id]['max_units']))
	if($this->locations['data'][$p2l_id]['max_units']=='')
	  return _('Not set');
	else
	  return $this->locations['data'][$p2l_id]['max_units'];
      else
	return false;
      break;
    case('pl2_id'):
      if(!$this->locations)
	$this->load('locations');
      $_key=key($data);
      if($_key=='id'){
	foreach($this->locations['data'] as $p2l_id=>$loc_data){
	  // print "$p2l_id *******8\n";
	  if($loc_data['location_id']==$data[$_key])
	    return $p2l_id;
	}
      }
      return false;
      break;
    }
    $_key=ucwords($key);
    if(isset($this->data[$_key]))
      return $this->data[$_key];
    print "Error $key not found in get from Product\n";
   
    return false;

  }

 




function new_id(){
  $sql="select max(`Product id`) as id from `Product Dimension`";
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $id=$row['id']+1;
  }else{
    $id=1;
  }  
  return $id;
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



function valid_id($id){
  if(is_numeric($id) and $id>0 and $id<9223372036854775807)
    return true;
  else
    return false;
}



  function create($data){

    $base_data=array(
		     'product sales state'=>'Unknown',
		     'product id'=>'',
		     'product code file as'=>'',
		     'product code'=>'',
		     'product price'=>'',
		     'product rrp'=>'',
		     'product name'=>'',
		     'product short description'=>'',
		     'product xhtml short description'=>'',
		     'product special characteristic'=>'',
		     'product description'=>'',
		     'product brand name'=>'',
		     'product family key'=>'',
		     'product family code'=>'',
		     'product family name'=>'',
		     'product main department key'=>'',
		     'product main department code'=>'',
		     'product main department name'=>'',
		     'product package type description'=>'Unknown',
		     'product package size metadata'=>'',
		     'product net weight'=>'',
		     'product gross weight'=>'',
		     'product units per case'=>'1',
		     'product unit type'=>'Piece',
		     'product unit container'=>'',
		     'product unit xhtml description'=>'',
		     'product availability state'=>'Unknown',
		     'product valid from'=>date("Y-m-d H:i:s"),
		     'product valid to'=>date("Y-m-d H:i:s"),
		     'product most recent'=>'Yes',
		     'product most recent key'=>''
		     );
    
    foreach($data as $key=>$value){

      if(isset($base_data[strtolower($key)]))
	$base_data[strtolower($key)]=_trim($value);
    }

    if(!$this->valid_id($base_data['product id'])  ){
       $base_data['product id']=$this->new_id();
     }
    $base_data['product code file as']=$this->normalize_code($base_data['product code']);

    if(!is_numeric($base_data['product units per case']) or $base_data['product units per case']<1)
      $base_data['product units per case']=1;

    $family=false;$new_family=false;

    if($base_data['product family code']!='' and $base_data['product family key']==''){
      $family=new Family('code',$base_data['product family code']);
      if(!$family->id){
	$fam_data=array(
			'code'=>$base_data['product family code'],
			'name'=>$base_data['product family name'],
			);
	$family=new Family('create',$fam_data);
	$new_family=true;
      }
      $base_data['product family key']=$family->id;
      $base_data['product family code']=$family->data['Product Family Code'];
      $base_data['product family name']=$family->data['Product Family Name'];
    }
    $department=false;$new_department=false;
    // print $base_data['product main department code']."\n";
    if($base_data['product main department code']!='' and $base_data['product main department key']==''){
      $department=new Department('code',$base_data['product main department code']);
      if(!$department->id){
	$dept_data=array(
			'code'=>$base_data['product main department code'],
			'name'=>$base_data['product main department name'],
			);
	$department=new Department('create',$dept_data);

	$new_department=true;
      }
      $base_data['product main department key']=$department->id;
      $base_data['product main department code']=$department->data['Product Department Code'];
      $base_data['product main department name']=$department->data['Product Department Name'];

      
    }

    
    
    

    
    $keys='(';$values='values(';
    foreach($base_data as $key=>$value){
      $keys.="`$key`,";
      $values.=prepare_mysql($value).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    $sql=sprintf("insert into `Product Dimension` %s %s",$keys,$values);


    // print "$sql\n\n";    

     //   if(preg_match('/abp-01/i',$base_data['product code'])){
// 	 print "$sql\n\n"; 
//        }
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
	$this->get_data('id',$this->id);


      if($base_data['product most recent']=='Yes'){
	$sql=sprintf("update `Product Dimension` set `Product Most Recent`='No' where `Product ID`=%d  and `Product Key`!=%d",$base_data['product id'],$this->id);
	mysql_query($sql);
	
	$sql=sprintf("update  `Product Dimension` set `Product Most Recent`='Yes',`Product Most Recent Key`=%d where `Product Key`=%d",$this->id,$this->id);
	mysql_query($sql);
      }
      $this->get_data('id',$this->id);
      
      $sql=sprintf("update  `Product Dimension` set `Product Short Description`=%s ,`Product XHTML Short Description`=%s where `Product Key`=%d",prepare_mysql($this->get('short description')),prepare_mysql($this->get('xhtml short description')),$this->id);
      mysql_query($sql);

      if(isset($data['deals']) and is_array($data['deals'])){


	foreach($data['deals'] as $deal_data){
	  //	print_r($deal_data);
	  if($deal_data['deal trigger']=='Family')
	    $deal_data['deal trigger key']=$this->data['Product Family Key'];
	  if($deal_data['deal trigger']=='Product')
	    $deal_data['deal trigger key']=$this->id;
	  if($deal_data['deal allowance target']=='Product')
	    $deal_data['deal allowance target key']=$this->id;
	  $deal=new Deal('create',$deal_data);
	  
	}
      }
      //   exit;
      
      $this->get_data('id',$this->id);
      $this->msg='Product Created';
      $this->new=true;

      if($new_family){
	$family->add_product($this->id,'principal');
	
	if(is_object($department) and $department->id)
	  $department->add_family($family->id,'principal noproducts');
      }
      
      if(is_object($department) and $department->id){
	$department->add_product($this->id,'principal');
      }
      

      $sql="select count(*) as num from `Product Department Bridge` where `Product Key`=".$this->id;
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$sql=sprintf("update  `Product Dimension` set `Product Department Degeneration`=%s where `Product Key`=%d",$row['num'],$this->id);
	mysql_query($sql);
       }



      
    }else{
      print "Error Product cannot be created\n";
      exit;
    }


    //$family->add_product($this->id,'principal');
    //	$department->add_family($family->id,'principal noproducts');
    //  $department->add_product($this->id,'principal');
    //$this->fix_todotransaction();
    //$this->set_stock(true);
    //$this->set_sales(true);
  }
  



  function new_part_list($product_list_id,$part_list){
    
    if(!$this->valid_id($product_list_id))
      $product_list_id=$this->new_part_list_id();
    
    $_base_data=array(
		      'product id'=>$this->data['Product ID'],
		      'part sku'=>'',
		      'requiered'=>'',
		      'parts per product'=>'',
		      'product part note'=>'',
		      'product part type'=>'',
		      'product part metadata'=>'',
		      'product part valid from'=>date('Y-m-d H:i:s'),
		      'product part valid to'=>date('Y-m-d H:i:s'),
		      'product part most recent'=>'Yes',
		      'product part most recent key'=>''
		      );
    
    
    
    foreach($part_list as $data){

      
      $base_data=$_base_data;
      foreach($data as $key=>$value){
	if(isset($base_data[strtolower($key)]))
	  $base_data[strtolower($key)]=_trim($value);
      }
      
      $base_data['product part id']=$product_list_id;
      
      $keys='(';
      $values='values(';
      foreach($base_data as $key=>$value){
	$keys.="`$key`,";
	$values.=prepare_mysql($value).',';
      }
      $keys=preg_replace('/,$/',')',$keys);
      $values=preg_replace('/,$/',')',$values);
      $sql=sprintf("insert into `Product Part List` %s %s",$keys,$values);
      //   print "$sql\n";exit;

      if(mysql_query($sql)){

	$id=mysql_insert_id();
	if($base_data['product part most recent']=='Yes'){
	  
	  $sql=sprintf("update `Product Part List` set `Product Part Most Recent`='No',`Product Part Most Recent Key`=%d where `Product ID`=%d  and `Product Part ID`!=%d      ",$id,$base_data['product id'],$base_data['product part id']);
	  mysql_query($sql);
	  
	  $sql=sprintf('update `Product Part List` set `Product Part Most Recent Key`=%d where `Product Part Key`=%d',$id,$id);
	  mysql_query($sql);
	}
	return $base_data['product part id'];

      }else{
	print "$sql\n can not create part list";
	exit;
      }
      
    }
  }



function normalize_code($code){
    $ncode=$code;
    $c=split('-',$code);
    if(count($c)==2){
      if(is_numeric($c[1]))
	$ncode=sprintf("%s-%05d",strtolower($c[0]),$c[1]);
      else{
	if(preg_match('/^[^\d]+\d+$/',$c[1])){
	  if(preg_match('/\d*$/',$c[1],$match_num) and preg_match('/^[^\d]*/',$c[1],$match_alpha)){
	    $ncode=sprintf("%s-%s%05d",strtolower($c[0]),strtolower($match_alpha[0]),$match_num[0]);
	    return $ncode;
	  }
	}
	if(preg_match('/^\d+[^\d]+$/',$c[1])){
	  if(preg_match('/^\d*/',$c[1],$match_num) and preg_match('/[^\d]*$/',$c[1],$match_alpha)){
	    $ncode=sprintf("%s-%05d%s",strtolower($c[0]),$match_num[0],strtolower($match_alpha[0]));
	    return $ncode;
	  }
	}
	

	$ncode=sprintf("%s-%s",strtolower($c[0]),strtolower($c[1]));
      }

    }if(count($c)==3){
      if(is_numeric($c[1]) and is_numeric($c[2])){
	$ncode=sprintf("%s-%05d-%05d",strtolower($c[0]),$c[1],$c[2]);
	return $ncode;
      }
      if(!is_numeric($c[1]) and is_numeric($c[2])){
	$ncode=sprintf("%s-%s-%05d",strtolower($c[0]),strtolower($c[1]),$c[2]);
	return $ncode;
      }
      if(is_numeric($c[1]) and !is_numeric($c[2])){
	$ncode=sprintf("%s-%05d-%s",strtolower($c[0]),$c[1],strtolower($c[2]));
	return $ncode;
      }



    }
    

    return $ncode;
  }
  
 function group_by($key){
   switch($key){
   case('code'):
     $sql=sprintf("select sum(`Product Total Quantity Invoiced`) as `Product Total Quantity Invoiced`,sum(`Product Total Invoiced Gross Amount`) as `Product Total Invoiced Gross Amount`, sum(`Product Total Invoiced Discount Amount`) as `Product Total Invoiced Discount Amount` from `Product Dimension` where `Product Code`=%s ",prepare_mysql($this->data['Product Code']));
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       foreach($row as $_key=>$value)
	 $this->data[$_key]=$value;
     }

     }

 }


 function load($key){

   switch($key){
   case('same code data'):
     $sql=sprintf("select * from `Product Dimension` where  `Product Key`=%d",$this->data['Product Same Code Most Recent Key']);
     //  print "$sql\n";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){

       $fam=sprintf('<a href="family.php?id=%d">%s</a>',$row['Product Family Key'],$row['Product Family Code']);
       $dept=sprintf('<a href="department.php?id=%d">%s</a>',$row['Product Main Department Key'],$row['Product Main Department Code']);
       $sql=sprintf("update `Product Dimension` set `Product Same Code XHTML Family`=%s, `Product Same Code Family Code`=%s,  `Product Same Code XHTML Main Department`=%s,  `Product Same Code Main Department Code`=%s ,`Product Same Code Tariff Code`=%s,`Product Same Code XHTML Short Description`=%s,`Product Same Code XHTML Parts`=%s,`Product Same Code XHTML Supplied By`=%s ,`Product Same Code XHTML Picking`=%s ,`Product Same Code Main Picking Location`=%s where `Product Key`=%d "
		    ,prepare_mysql($fam)
		    ,prepare_mysql($row['Product Family Code'])
		    ,prepare_mysql($dept)
		    ,prepare_mysql($row['Product Main Department Code'])

		    ,prepare_mysql($row['Product Tariff Code'])
		    ,prepare_mysql($row['Product XHTML Short Description'])
		    ,prepare_mysql($row['Product XHTML Parts'])
		    ,prepare_mysql($row['Product XHTML Supplied By'])
		    ,prepare_mysql($row['Product XHTML Picking'])
		    ,prepare_mysql($row['Product Main Picking Location'])
		    ,$this->id
		    );
       //  print "$sql\n";
       if(!mysql_query($sql))
	 exit("$sql can not update prioduct ame code data\n");
 
     }

     break;
   case('parts'):
     $parts='';
     $mysql_where='';
     $sql=sprintf("select `Part SKU` from `Product Part List` where `Product ID`=%d and `Product Part Most Recent`='Yes';",$this->data['Product ID']);
     $result=mysql_query($sql);
     while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       $parts.=sprintf(', <a href="part.php?sku=%d">%s</a>',$row['Part SKU'],$row['Part SKU']);
       $mysql_where.=', '.$row['Part SKU'];
     }
     $parts=preg_replace('/^, /','',$parts);
     $mysql_where=preg_replace('/^, /','',$mysql_where);

       $supplied_by='';
       $sql=sprintf("select  (select `Supplier Product Code` from `Supplier Product Dimension` where `Supplier Product ID`=SPPL.`Supplier Product ID` and `Supplier Product Most Recent` limit 1) as `Supplier Product Code`,(select `Supplier Product Key` from `Supplier Product Dimension` where `Supplier Product ID`=SPPL.`Supplier Product ID` and `Supplier Product Most Recent` limit 1) as `Supplier Product Key` ,  SD.`Supplier Key`,`Supplier Code` from `Supplier Product Part List` SPPL   left join `Supplier Dimension` SD on (SD.`Supplier Key`=SPPL.`Supplier Key`)   where `Part SKU` in (%s) order by `Supplier Key`;",$mysql_where);
      $result=mysql_query($sql);
      //      print "$sql\n";
      // exit;
      $supplier=array();
      $current_supplier='_';
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$_current_supplier=$row['Supplier Key'];
	if($_current_supplier!=$current_supplier){
	  $supplied_by.=sprintf(', <a href="supplier.php?id=%d">%s</a>(<a href="supplier_product.php?id=%d">%s</a>',$row['Supplier Key'],$row['Supplier Code'],$row['Supplier Product Key'],$row['Supplier Product Code']);
	  $current_supplier=$_current_supplier;
	}else{
	   $supplied_by.=sprintf(', <a href="supplier_product.php?id=%d">%s</a>',$row['Supplier Product Key'],$row['Supplier Product Code']);

	}
	
      }
      $supplied_by.=")";

      $supplied_by=_trim(preg_replace('/^, /','',$supplied_by));
      if($supplied_by=='')
	$supplied_by=_('Unknown');



      $sql=sprintf("update `Product Dimension` set `Product XHTML Parts`=%s  , `Product XHTML Supplied By`=%s where `Product Key`=%d",prepare_mysql(_trim($parts)),prepare_mysql(_trim($supplied_by)),$this->id);
     //print "$sql\n";
     if(!mysql_query($sql))
       exit("$sql  eerror can not updat eparts pf product 1234234\n");
     
     


     break;
   case('avalilability'):
   case('stock'):

     $stock_forecast_method='basic1';
     $stock_tipo_method='basic1';

     // get parts;
     $sql=sprintf(" select `Part Current Stock`,`Parts Per Product` from `Part Dimension` PD left join `Product Part List` PPL on (PD.`Part SKU`=PPL.`Part SKU`)  where `Product ID`=%s  and `Product Part Most Recent`='Yes' group by PD.`Part SKU`  ",prepare_mysql($this->data['Product ID']));
     //print "$sql\n";
     $result=mysql_query($sql);
     $stock=99999999999;
     $change=false;
     $stock_error=false;
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       if(is_numeric($row['Part Current Stock']) and is_numeric($row['Parts Per Product'])  and $row['Parts Per Product']>0 ) {
	 $_stock=$row['Part Current Stock']/$row['Parts Per Product'];
	 if($stock>$_stock){
	   $stock=$_stock;
	   $change=true;
	 }
       }else{
	 $stock=0;
	 $stock_error=true;
       }
	 
     }

     if(!$change or $stock_error)
       $stock='NULL';
     
     if(is_numeric($stock) and $stock<0)
       $stock='NULL';

     $sql=sprintf("update `Product Dimension` set `Product Availability`=%s where `Product key`=%d",$stock,$this->id);
     mysql_query($sql);
     $days_available='NULL';
     $avg_day_sales=0;


     
     switch($stock_forecast_method){
     case('basic1'):
       
       $sql=sprintf("select sum(`Product 1 Year Acc Quantity Invoiced`) as sales from `Product Dimension` where `Product Code`=%s",prepare_mysql($this->data['Product Code']));
       $res = mysql_query($sql);
       $sales=0;
       if($row=mysql_fetch_array($res, MYSQL_ASSOC)) 
	 $sales=$row['sales'];
       if($sales<=0){
	 $days_available='NULL';
	 $avg_day_sales=0;
       }else{
	 if($this->data['Product Same Code 1 Year Acc Days On Sale']>0){
	   $avg_day_sales=$sales/$this->data['Product Same Code 1 Year Acc Days On Sale'];
	   if($stock=='NULL' or $avg_day_sales<=0)
	     $days_available='NULL';
	   else{
	     $days_available=$sales/$avg_day_sales;
	     
	   }
	 }else{
	   $days_available='NULL';
	   $avg_day_sales=0;
	 }
       }
       
       break;
     }
     
     
     //   print "State ".$this->data['Product Sales State']."\n";
     
     if($this->data['Product Sales State']=='Discontinued'){
       if($stock=='NULL')
	 $tipo='No applicable';
       else
	 $tipo='Optimal';
       
       // print "$tipo\n";
     }else if($this->data['Product Sales State']=='For sale'){
       if($stock=='NULL' or $stock<0)
	 $tipo='Unknown';
       else if($stock==0)
	 $tipo='Out of stock';
       else{
	 if(is_numeric($days_available)){
	   
	   switch($stock_tipo_method){
	   case('basic1'):
	     if($days_available<7)
	       $tipo='Critical';
	     elseif($days_available>182.50)
	       $tipo='Surplus';
	     elseif($days_available<21)
	       $tipo='Low';
	     else
	       $tipo='Optimal';
	     break;
	   }
	 }else
	   $tipo='Unknown';
       }
     }else{
       $tipo='No applicable';
     }


     $sql=sprintf("update `Product Dimension` set `Product Availability State`=%s,`Product Available Days Forecast`=%s where `Product key`=%d",prepare_mysql($tipo),$days_available,$this->id);
     if(!mysql_query($sql))
       exit("can no update stock prod product.php l 1311\n");
     break;
   case('days'):
     $tdays = (strtotime($this->data['Product Valid To']) - strtotime($this->data['Product Valid From'])) / (60 * 60 * 24);
     

     if(strtotime($this->data['Product Valid To'])<strtotime('today -1 year'))
       $ydays=0;
     else{
       $_to=strtotime($this->data['Product Valid To']);
       if(strtotime($this->data['Product Valid From'])<strtotime('today -1 year'))
	 $_from=strtotime('today -1 year');
       else
	 $_from=strtotime($this->data['Product Valid From']);
       $ydays=($_to-$_from)/ (60 * 60 * 24);
     } 
     
     
     if(strtotime($this->data['Product Valid To'])<strtotime('today -3 month'))
       $qdays=0;
     else{
       $_to=strtotime($this->data['Product Valid To']);
       if(strtotime($this->data['Product Valid From'])<strtotime('today -3 month'))
	 $_from=strtotime('today -3 month');
       else
	 $_from=strtotime($this->data['Product Valid From']);
       $qdays=($_to-$_from)/ (60 * 60 * 24);
     }

     if(strtotime($this->data['Product Valid To'])<strtotime('today -1 month'))
       $mdays=0;
     else{
       $_to=strtotime($this->data['Product Valid To']);
       if(strtotime($this->data['Product Valid From'])<strtotime('today -1 month'))
	 $_from=strtotime('today -1 month');
       else
	 $_from=strtotime($this->data['Product Valid From']);
       $mdays=($_to-$_from)/ (60 * 60 * 24);
     }
     if(strtotime($this->data['Product Valid To'])<strtotime('today -1 week'))
       $wdays=0;
     else{
       $_to=strtotime($this->data['Product Valid To']);
       if(strtotime($this->data['Product Valid From'])<strtotime('today -1 week'))
	 $_from=strtotime('today -1 week');
       else
	 $_from=strtotime($this->data['Product Valid From']);
       $wdays=($_to-$_from)/ (60 * 60 * 24);
     }
     
     
     $for_sale_since=$this->data['Product Valid From'];
     $last_sold_date=$this->data['Product Valid To'];
     

     
     $sql=sprintf("update `Product Dimension` set `Product Total Days On Sale`=%f , `Product 1 Year Acc Days On Sale`=%f ,`Product 1 Quarter Acc Days On Sale`=%f ,`Product 1 Month Acc Days On Sale`=%f ,`Product 1 Week Acc Days On Sale`=%f ,`Product For Sale Since Date`=%s ,`Product Last Sold Date`=%s where `Product Key`=%d "
		  ,$tdays
		  ,$ydays
		  ,$qdays
		  ,$mdays
		  ,$wdays
		  ,prepare_mysql($for_sale_since)
		  ,prepare_mysql($last_sold_date)
		  
		  ,$this->id
		  );

 if(!mysql_query($sql))
       exit("$sql\ncan not update product days\n");
   
     //same code
 $total_days=array();
 $y_days=array();
 $q_days=array();
 $m_days=array();
 $w_days=array();



$sql=sprintf("select `Product Key`,`Product For Sale Since Date`,`Product Last Sold Date`,`Product Sales State` from `Product Dimension` where `Product Code`=%s",prepare_mysql($this->data['Product Code']));
 $result=mysql_query($sql);
 
 while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
   $from=strtotime($row['Product For Sale Since Date']);
   $to=strtotime($row['Product Last Sold Date']);
       
       if($row['Product Sales State']=='For sale')
	 $to=strtotime('today');

       if($from>$to){
	 print "Error ".$this->data['Product Code']." (".$row['Product Key'].")  wrong dates skipping \n";
	 continue;
       }

       $start_date = date("Y-m-d",$from);
       $check_date = $start_date;
       $end_date =date("Y-m-d",$to);
       

       
       $i=0;
       while ($check_date != $end_date) {
	 
	 if(isset($total_days[$check_date]))
	   $total_days[$check_date]++;
	 else
	   $total_days[$check_date]=1;

	 $_date=strtotime($check_date);
	 
	 if($_date>strtotime('today - 1 year')){
	   if(isset($y_days[$check_date]))
	     $y_days[$check_date]++;
	   else
	     $y_days[$check_date]=1;
	 }
	 if($_date>strtotime('today - 3 month')){
	   if(isset($q_days[$check_date]))
	     $q_days[$check_date]++;
	   else
	     $q_days[$check_date]=1;
	 }
	  if($_date>strtotime('today - 1 month')){
	   if(isset($m_days[$check_date]))
	     $m_days[$check_date]++;
	   else
	     $m_days[$check_date]=1;
	 }
	  if($_date>strtotime('today - 3 month')){
	   if(isset($w_days[$check_date]))
	     $w_days[$check_date]++;
	   else
	     $w_days[$check_date]=1;
	 } 


	 $check_date = date ("Y-m-d", strtotime ("+1 day", strtotime($check_date)));
	 $i++;

	 if ($i > 50000) { die ("$start_date  $end_date   to many days Error a!"); } 
       }
       //   print "$start_date $end_date ".count($total_days)."\n";
 }
 // print_r($days);
 $total_days=count($total_days);
$y_days=count($y_days);
 $q_days=count($y_days);
 $m_days=count($y_days);
 $w_days=count($y_days);

 $sql=sprintf("update `Product Dimension` set `Product Same Code Total Days On Sale`=%f ,`Product Same Code 1 Year Acc Days On Sale`=%f , `Product Same Code 1 Quarter Acc Days On Sale`=%f, `Product Same Code 1 Month Acc Days On Sale`=%f , `Product Same Code 1 Week Acc Days On Sale`=%f where  `Product Key`=%d "
	      ,$total_days
	      ,$y_days
	      ,$q_days
	      ,$m_days
	      ,$w_days
	      ,$this->id
	      );
 
 if(!mysql_query($sql))
       exit("$sql\ncan not update product same code total days\n");
 

 $total_days=array();
 $y_days=array();
 $q_days=array();
 $m_days=array();
 $w_days=array();



$sql=sprintf("select `Product Key`,`Product For Sale Since Date`,`Product Last Sold Date`,`Product Sales State` from `Product Dimension` where `Product ID`=%s",prepare_mysql($this->data['Product ID']));
 $result=mysql_query($sql);
 
 while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
   $from=strtotime($row['Product For Sale Since Date']);
   $to=strtotime($row['Product Last Sold Date']);
       
       if($row['Product Sales State']=='For sale')
	 $to=strtotime('today');


   if($from>$to){
	 print "Error ".$this->data['Product Code']." (".$row['Product Key'].")   wrong dates  ".$row['Product For Sale Since Date']." - ".$row['Product Last Sold Date']."  skipping \n";
	 continue;
       }


       $start_date = date("Y-m-d",$from);
       $check_date = $start_date;
       $end_date =date("Y-m-d",$to);
       
       //  print $this->data['Product Code']." $start_date $end_date  $check_date  \n";
       
       $i=0;
       while ($check_date != $end_date) {
	 
	 if(isset($total_days[$check_date]))
	   $total_days[$check_date]++;
	 else
	   $total_days[$check_date]=1;

	 $_date=strtotime($check_date);
	 
	 if($_date>strtotime('today - 1 year')){
	   if(isset($y_days[$check_date]))
	     $y_days[$check_date]++;
	   else
	     $y_days[$check_date]=1;
	 }
	 if($_date>strtotime('today - 3 month')){
	   if(isset($q_days[$check_date]))
	     $q_days[$check_date]++;
	   else
	     $q_days[$check_date]=1;
	 }
	  if($_date>strtotime('today - 1 month')){
	   if(isset($m_days[$check_date]))
	     $m_days[$check_date]++;
	   else
	     $m_days[$check_date]=1;
	 }
	  if($_date>strtotime('today - 3 month')){
	   if(isset($w_days[$check_date]))
	     $w_days[$check_date]++;
	   else
	     $w_days[$check_date]=1;
	 } 


	  $check_date = date ("Y-m-d", strtotime ("+1 day", strtotime($check_date)));
	 $i++;
	 if ($i > 50000) { die ("$start_date  $end_date   to many days Error! b\n"); } 
	 
       }
       //         print "$start_date $end_date ".count($days)."\n";
 }
 // print_r($days);
 $total_days=count($total_days);
$y_days=count($y_days);
 $q_days=count($y_days);
 $m_days=count($y_days);
 $w_days=count($y_days);
 $sql=sprintf("update `Product Dimension` set `Product Same ID Total Days On Sale`=%f ,`Product Same ID 1 Year Acc Days On Sale`=%f , `Product Same ID 1 Quarter Acc Days On Sale`=%f, `Product Same ID 1 Month Acc Days On Sale`=%f , `Product Same ID 1 Week Acc Days On Sale`=%f where  `Product Key`=%d "
	      ,$total_days
	      ,$y_days
	      ,$q_days
	      ,$m_days
	      ,$w_days
	      ,$this->id
	      );
 
 if(!mysql_query($sql))
       exit("$sql\ncan not update product same id total days\n");



 
 break;
   case('sales'):
     $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact` where `Consolidated`='Yes' and `Product Key`=%d",$this->id);
   
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
     
       $this->data['Product Total Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Total Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Total Invoiced Amount']=$row['gross']-$row['disc'];
	      
       $this->data['Product Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       $this->data['Product Total Quantity Ordered']=$row['ordered'];
       $this->data['Product Total Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Total Quantity Delivered']=$row['delivered'];
     }else{
       $this->data['Product Total Invoiced Gross Amount']=0;
       $this->data['Product Total Invoiced Discount Amount']=0;
       $this->data['Product Total Invoiced Amount']=0;
       $this->data['Product Total Profit']=0;
       $this->data['Product Total Quantity Ordered']=0;
       $this->data['Product Total Quantity Invoiced']=0;
       $this->data['Product Total Quantity Delivered']=0;


     }
     // print "$sql\n";

       // print $row['cost_sup']." ".$this->id."  \n";
  
     $sql=sprintf("update `Product Dimension` set `Product Total Invoiced Gross Amount`=%.2f,`Product Total Invoiced Discount Amount`=%.2f,`Product Total Invoiced Amount`=%.2f,`Product Total Profit`=%.2f, `Product Total Quantity Ordered`=%s , `Product Total Quantity Invoiced`=%s,`Product Total Quantity Delivered`=%s  where `Product Key`=%d "
		  ,$this->data['Product Total Invoiced Gross Amount']
		  ,$this->data['Product Total Invoiced Discount Amount']
		  ,$this->data['Product Total Invoiced Amount']

		  ,$this->data['Product Total Profit']
		  ,prepare_mysql($this->data['Product Total Quantity Ordered'])
		  ,prepare_mysql($this->data['Product Total Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Total Quantity Delivered'])
		  ,$this->id
		  );
   
     
     if(!mysql_query($sql))
       exit("$sql\ncan not update product sales\n");
     
     $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact` where `Product Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));
     //  print "$sql\n\n";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       
       $this->data['Product 1 Year Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product 1 Year Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product 1 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];

       $this->data['Product 1 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       $this->data['Product 1 Year Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product 1 Year Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product 1 Year Acc Quantity Delivered']=$row['delivered'];
     }else{
	 $this->data['Product 1 Year Acc Invoiced Gross Amount']=0;
	 $this->data['Product 1 Year Acc Invoiced Discount Amount']=0;
	 $this->data['Product 1 Year Acc Profit']=0;$this->data['Product 1 Year Acc Invoiced Amount']=0;
	 $this->data['Product 1 Year Acc Quantity Ordered']=0;
	 $this->data['Product 1 Year Acc Quantity Invoiced']=0;
	 $this->data['Product 1 Year Acc Quantity Delivered']=0;
       }
       
       $sql=sprintf("update `Product Dimension` set `Product 1 Year Acc Invoiced Gross Amount`=%.2f,`Product 1 Year Acc Invoiced Discount Amount`=%.2f,`Product 1 Year Acc Invoiced Amount`=%.2f,`Product 1 Year Acc Profit`=%.2f, `Product 1 Year Acc Quantity Ordered`=%s , `Product 1 Year Acc Quantity Invoiced`=%s,`Product 1 Year Acc Quantity Delivered`=%s  where `Product Key`=%d "
		    ,$this->data['Product 1 Year Acc Invoiced Gross Amount']
		    ,$this->data['Product 1 Year Acc Invoiced Discount Amount']
		    ,$this->data['Product 1 Year Acc Invoiced Amount']
		    ,$this->data['Product 1 Year Acc Profit']
		    ,prepare_mysql($this->data['Product 1 Year Acc Quantity Ordered'])
		    ,prepare_mysql($this->data['Product 1 Year Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product 1 Year Acc Quantity Delivered'])
		    ,$this->id
		    );
       if(!mysql_query($sql))
	 exit("$sql\ncan not update product sales 1 yr acc\n");
       
       $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact` where `Product Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));
 //print "$sql\n\n";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
     
       $this->data['Product 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product 1 Quarter Acc Invoiced Amount']=$row['gross']-$row['disc'];

       $this->data['Product 1 Quarter Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       $this->data['Product 1 Quarter Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product 1 Quarter Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product 1 Quarter Acc Quantity Delivered']=$row['delivered'];
      }else{
	 $this->data['Product 1 Quarter Acc Invoiced Gross Amount']=0;
	 $this->data['Product 1 Quarter Acc Invoiced Discount Amount']=0;
	 $this->data['Product 1 Quarter Acc Profit']=0; $this->data['Product 1 Quarter Acc Invoiced Amount']=0;
	 $this->data['Product 1 Quarter Acc Quantity Ordered']=0;
	 $this->data['Product 1 Quarter Acc Quantity Invoiced']=0;
	 $this->data['Product 1 Quarter Acc Quantity Delivered']=0;
       }
     $sql=sprintf("update `Product Dimension` set `Product 1 Quarter Acc Invoiced Gross Amount`=%.2f,`Product 1 Quarter Acc Invoiced Discount Amount`=%.2f,`Product 1 Quarter Acc Invoiced Amount`=%.2f,`Product 1 Quarter Acc Profit`=%.2f, `Product 1 Quarter Acc Quantity Ordered`=%s , `Product 1 Quarter Acc Quantity Invoiced`=%s,`Product 1 Quarter Acc Quantity Delivered`=%s  where `Product Key`=%d "
		  ,$this->data['Product 1 Quarter Acc Invoiced Gross Amount']
		  ,$this->data['Product 1 Quarter Acc Invoiced Discount Amount']
		  ,$this->data['Product 1 Quarter Acc Invoiced Amount']
		  ,$this->data['Product 1 Quarter Acc Profit']
		  ,prepare_mysql($this->data['Product 1 Quarter Acc Quantity Ordered'])
		  ,prepare_mysql($this->data['Product 1 Quarter Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product 1 Quarter Acc Quantity Delivered'])
		  ,$this->id
		  );
     if(!mysql_query($sql))
       exit("$sql\ncan not update product sales 1 qtr acc\n");
     

 $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact` where `Product Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));
 //    print "$sql\n\n";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
     
       $this->data['Product 1 Month Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product 1 Month Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product 1 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];
       $this->data['Product 1 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       $this->data['Product 1 Month Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product 1 Month Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product 1 Month Acc Quantity Delivered']=$row['delivered'];
     }else{
       $this->data['Product 1 Month Acc Invoiced Gross Amount']=0;
       $this->data['Product 1 Month Acc Invoiced Discount Amount']=0;$this->data['Product 1 Month Acc Invoiced Amount']=0;
       $this->data['Product 1 Month Acc Profit']=0;
       $this->data['Product 1 Month Acc Quantity Ordered']=0;
       $this->data['Product 1 Month Acc Quantity Invoiced']=0;
       $this->data['Product 1 Month Acc Quantity Delivered']=0;
       }
     $sql=sprintf("update `Product Dimension` set `Product 1 Month Acc Invoiced Gross Amount`=%.2f,`Product 1 Month Acc Invoiced Discount Amount`=%.2f,`Product 1 Month Acc Invoiced Amount`=%.2f,`Product 1 Month Acc Profit`=%.2f, `Product 1 Month Acc Quantity Ordered`=%s , `Product 1 Month Acc Quantity Invoiced`=%s,`Product 1 Month Acc Quantity Delivered`=%s  where `Product Key`=%d "
		  ,$this->data['Product 1 Month Acc Invoiced Gross Amount']
		  ,$this->data['Product 1 Month Acc Invoiced Discount Amount'],$this->data['Product 1 Month Acc Invoiced Amount']
		  ,$this->data['Product 1 Month Acc Profit']
		  ,prepare_mysql($this->data['Product 1 Month Acc Quantity Ordered'])
		  ,prepare_mysql($this->data['Product 1 Month Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product 1 Month Acc Quantity Delivered'])
		  ,$this->id
		  );
     if(!mysql_query($sql))
       exit("$sql\ncan not update product sales 1 qtr acc\n");
     


     $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact` where `Product Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));
 //    print "$sql\n\n";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
     
       $this->data['Product 1 Week Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product 1 Week Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product 1 Week Acc Invoiced Amount']=$row['gross']-$row['disc'];
       $this->data['Product 1 Week Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       $this->data['Product 1 Week Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product 1 Week Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product 1 Week Acc Quantity Delivered']=$row['delivered'];
      }else{
	 $this->data['Product 1 Week Acc Invoiced Gross Amount']=0;
	 $this->data['Product 1 Week Acc Invoiced Discount Amount']=0; $this->data['Product 1 Week Acc Invoiced Amount']=0;
	 $this->data['Product 1 Week Acc Profit']=0;
	 $this->data['Product 1 Week Acc Quantity Ordered']=0;
	 $this->data['Product 1 Week Acc Quantity Invoiced']=0;
	 $this->data['Product 1 Week Acc Quantity Delivered']=0;
       }
     $sql=sprintf("update `Product Dimension` set `Product 1 Week Acc Invoiced Gross Amount`=%.2f,`Product 1 Week Acc Invoiced Discount Amount`=%.2f,`Product 1 Week Acc Invoiced Amount`=%.2f,`Product 1 Week Acc Profit`=%.2f, `Product 1 Week Acc Quantity Ordered`=%s , `Product 1 Week Acc Quantity Invoiced`=%s,`Product 1 Week Acc Quantity Delivered`=%s  where `Product Key`=%d "
		  ,$this->data['Product 1 Week Acc Invoiced Gross Amount']
		  ,$this->data['Product 1 Week Acc Invoiced Discount Amount']
		   ,$this->data['Product 1 Week Acc Invoiced Amount']
		  ,$this->data['Product 1 Week Acc Profit']
		  ,prepare_mysql($this->data['Product 1 Week Acc Quantity Ordered'])
		  ,prepare_mysql($this->data['Product 1 Week Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product 1 Week Acc Quantity Delivered'])
		  ,$this->id
		  );
     if(!mysql_query($sql))
       exit("$sql\ncan not update product sales 1 week acc\n");
     
     
     break;

   }
   

 }


}
?>
