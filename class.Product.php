<?php
/*
 File: Product.php 

 This file contains the Product Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('class.DB_Table.php');
include_once('class.Deal.php');
include_once('class.SupplierProduct.php');
include_once('class.Part.php');
include_once('class.Store.php');

/* class: product
   Class to manage the *Product Family Dimension* table
*/
// JFA


class product extends DB_Table{

  public $product=array();
  public $categories=array();
  public $parents=array();
  public $childs=array();
  public $supplier=false;
  public $locations=false;
  public $notes=array();
  public $images=false;
  public $weblink=false;
  public $parts=false;
  public $parts_skus=false;
  public $parts_location=false;
  public $mode='id';
  public $system_format=true;
  // Variable: new
  // Indicate if a new product was created
  
  public $new_id=false;
  public $location_to_update=false;
   // Variable: id
  // Reference tothe Product Key
  
  public $unknown_txt='Unknown';

  /* 
   Constructor: Product
   Initializes the object.
 
   Parameters:
   a1 - Tag or Product Key
  */
  function Product($a1,$a2=false,$a3=false) {
    
    $this->table_name='Product';
    $this->ignore_fields=array(
			       'Product Key'
			       );

    if(is_numeric($a1) and !$a2){
      $this->get_data('id',$a1);
    }
    else if(($a1=='new' or $a1=='create') and is_array($a2) ){
      $this->msg=$this->create($a2);
     
    } else
      $this->get_data($a1,$a2,$a3);
  }


  /*
    Function: get_data
    Obtiene los datos de la tabla Product Dimension de acuerdo al Id o al codigo del producto, y de la tabla Product Part List, además actualiza valores de las tablas: Supplier Product Dimension, Product Part List y Part Dimension.
*/
// JFA

  function get_data($tipo,$tag,$extra=false){
    
    //  print_r($tag['editor']);
    if(isset($tag['editor']) and is_array($tag['editor'])){

	foreach($tag['editor'] as $key=>$value){

	  if(array_key_exists($key,$this->editor))
	    $this->editor[$key]=$value;
		    
	}
      }

   
    if($tipo=='id' or $tipo=='key'){
      $sql=sprintf("select * from `Product History Dimension` where `Product Key`=%d ",$tag);
      $result=mysql_query($sql);
      if( ($this->data=mysql_fetch_array($result, MYSQL_ASSOC))){
	   
	    $this->id=$this->data['Product Key'];
	    $this->pid=$this->data['Product ID'];
      }Else
	return;
      mysql_free_result($result);
       $sql=sprintf("select * from `Product Dimension` where `Product ID`=%d ",$this->pid);
      $result=mysql_query($sql);
      if( $row=mysql_fetch_array($result, MYSQL_ASSOC)){
	 $this->locale=$row['Product Locale'];
	 
      }else
	return;
      mysql_free_result($result);


      return;
    }else if($tipo=='pid'){
      $sql=sprintf("select * from `Product Dimension` where `Product ID`=%d    ",$tag);
      $this->mode='id';
      $result=mysql_query($sql);

      if( ($this->data=mysql_fetch_array($result, MYSQL_ASSOC))){
	$this->locale=$this->data['Product Locale'];
	$this->id=$this->data['Product Most Recent Key'];
	$this->pid=$this->data['Product ID'];
      }
      mysql_free_result($result);
     

      Return;
    }elseif($tipo=='code'){
      $this->mode='code';
      $sql=sprintf("select `Product Code` from `Product Same Code Dimension` where `Product Code`=%s  ",prepare_mysql($tag));
      $result=mysql_query($sql);
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
	$this->code=$this->data['Product Code'];
      }

      return;
      
    } if($tipo=='code_store' or $tipo=='code-store'){
     
      $sql=sprintf("select * from `Product Dimension` where `Product Most Recent`='Yes' and `Product Code`=%s and `Product Store Key`=%d",prepare_mysql($tag),$extra);
      //      print $sql;
      $result=mysql_query($sql);
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$this->id=$this->data['Product Most Recent Key'];
	$this->locale=$this->data['Product Locale'];
      }
	return;
      
    }elseif($tipo=='code-name-units-price-store'){
      $auto_add=$tag['auto_add'];
      
     
      $sql=sprintf("select * from `Product Dimension` where `Product Code`=%s and `Product Name`=%s and `Product Units Per Case`=%f and `Product Unit Type`=%s  and `Product Price`=%.2f and `Product Store Key`=%d "
		   ,prepare_mysql($tag['product code'])
		   ,prepare_mysql($tag['product name'])
		   ,$tag['product units per case']
		   ,prepare_mysql($tag['product unit type'])
		   ,$tag['product price']
		   ,$tag['Product Store Key']
		   ); 
      // print "$sql\n";
      $result=mysql_query($sql);
      // print "----------------DONE\n";
      if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)){
	//------------------------------------------------------------------------------
	//    FOUND IT
	//-------------------------------------------------------------------------------

	$this->id=$this->data['Product Key'];
	if(strtotime($this->data['Product Valid To'])<strtotime($tag['date2'])  ){
	  $sql=sprintf("update `Product Dimension` set `Product Valid To`=%s where `Product Key`=%d",prepare_mysql($tag['date2']),$this->id);
	  $this->data['product valid to']=$tag['date2'];
	  mysql_query($sql);
	  $this->update_same_id_valid_dates();
	  $this->update_same_code_valid_dates();
	}
	if(strtotime($this->data['Product Valid From'])>strtotime($tag['date'])  ){
	  $sql=sprintf("update `Product Dimension` set `Product Valid From`=%s where `Product Key`=%d",prepare_mysql($tag['date']),$this->id);
	  mysql_query($sql);
	  $this->data['Product Valid From']=$tag['date'];
	  $this->update_same_id_valid_dates();
	  $this->update_same_code_valid_dates();
	}

	//found the part


	$sql=sprintf("select  count(Distinct `Product Part ID`) as lists  from `Product Part List`  where  `Product ID`=%s  " ,$this->data['Product ID']);
	$result2=mysql_query($sql);
	if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
	  $num_lists=$row2['lists'];
	  if($num_lists!=1)
	    print("$sql  Warning wrong number if lists\n");
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
	
}else{
exit("sku not found   $sql\n");

}

	$data=array(
		    'Supplier Name'=>$tag['supplier name'],
		    'Supplier Code'=>$tag['supplier code'],
		    'from'=>$tag['date'],
		      'to'=>$tag['date2'],
		    'editor'=>$this->editor
		    );
	$supplier=new Supplier('find create',$data);

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
	  exit("error culnt create supplier product Product.php 185\n");
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
	  $sql=sprintf('select `Supplier Product Code`,`Supplier Product Valid From`,`Supplier Product Valid To`,`Supplier Product Key`,SPD.`Supplier Product ID`,`Supplier Product Units Per Part`,`Supplier Product Cost` 
	  from  `Supplier Product Dimension`   SPD left join `Supplier Product Part List` SPPL  on (SPD.`Supplier Product ID`=SPPL.`Supplier Product ID`) where `Part SKU`=%d  
	  and `Supplier Product Key`=%d',$this->part_sku,$supplier_product->id);
	 
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

      $sql=sprintf("select count(*) as num from `Product Dimension` where `Product Code`=%s and `Product Store Key`=%d "
		   ,prepare_mysql($tag['product code'])
		   ,$tag['Product Store Key']
		 
		   );

      $result2=mysql_query($sql);
      
      if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
	$number_sp=$row2['num'];
      }

      // print "$number_sp\n";
      if($number_sp==0){
	// ****************************************  NEW CODE ************************************************
	
	$this->new_code=true;
	$tag['product id']=$this->new_id();
	$tag['product most recent']='Yes';
	$tag['Part Most Recent']='Yes';
	$tag['product valid to']=$tag['date2'];
	$tag['product valid from']=$tag['date'];
	$tag['part valid to']=$tag['date2'];
	$tag['part valid from']=$tag['date'];
	$tag['product record type']='Normal';
	$tag['product web state']='Online Auto';
	
	
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

	$data=array(
		    'name'=>$tag['supplier name'],
		      'code'=>$tag['supplier code'],
		      'from'=>$tag['date'],
		      'to'=>$tag['date2'],
		      'editor'=>$this->editor
		      );
	$supplier=new Supplier('find create',$data);

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
	// ****************************************  OLd CODE ************************************************
	//old code
	$sql=sprintf("select * from `Product Dimension` where `Product Code`=%s and  `Product Units Per Case`=%f and `Product Store Key`=%d" 
		     ,prepare_mysql($tag['product code'])
		     ,$tag['product units per case']
		     ,$tag['Product Store Key']
		     );
	//print "$sql\n";
	$result2=mysql_query($sql);
	if($same_id_data=mysql_fetch_array($result2, MYSQL_ASSOC)){
	  //---------------------------------------------------------------------
	  //    Price  or name change, same units per case
	  // --------------------------------------------------------------------
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
	  $tag['product record type']='Normal';
	  $tag['product web state']='Online Auto';

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
	  // Different units that means niew id and new product part list
	  $this->new_id=true;
	  $tag['product id']=$this->new_id();
	  $tag['product most recent']='Yes';
	  
	  
	  $sql=sprintf("select * from `Product Dimension` where `Product Code`=%s and `Product Store Key`=%d  "
		       ,prepare_mysql($tag['product code'])
		       ,$tag['Product Store Key']
		       );
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
	  $tag['product record type']='Normal';
	  $tag['product web state']='Online Auto';
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
    
      


      
      
	$data=array(
		    'name'=>$tag['supplier name'],
		    'code'=>$tag['supplier code'],
		    'from'=>$tag['date'],
		    'to'=>$tag['date2'],
		    'editor'=>$this->editor
		    );


	$supplier=new Supplier('find create',$data);


	
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

  /*
Function: find
Busca elmproduct
   */
  function find($raw_data,$options){
    
    if(isset($raw_data['editor'])){
      foreach($raw_data['editor'] as $key=>$value){
	if(array_key_exists($key,$this->editor))
	  $this->editor[$key]=$value;
      }
    }
    
    $this->found_in_code=false;
    $this->found_in_id=false;
    $this->found_in_key=false;

    $create='';
    $update='';
    if(preg_match('/create/i',$options)){
      $create='create';
    }
    if(preg_match('/update/i',$options)){
      $update='update';
    }
    $data=$this->get_base_data();
    foreach($raw_data as $key=>$value){
      if(isset($data[strtolower($key)]))
	$data[strtolower($key)]=_trim($value);
    }

    if($data['product code']=='' or $data['product price'])
      return;
    if($data['product store key']=='')
      $data['product store key']=1;
    if($data['product name']=='')
      $data['product name']=$data['product code'];
    
    
    $sql=sprintf("select `Product Code` from `Product Same Code Dimension` where `Product Code`=%s  "
		 ,prepare_mysql($data['product code'])
		 ); 
    $result=mysql_query($sql);
    if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $this->found_in_code=true;
      

      
      $sql=sprintf("select `Product ID` from `Product Dimension` where `Product Code`=%s and `Product Units Per Case`=%f and `Product Unit Type`=%s  and  `Product Store Key`=%d "
		   ,prepare_mysql($data['product code'])
		   ,$data['product units per case']
		   ,prepare_mysql($tag['product unit type'])
		   ,$data['product store key']
		   ); 
      // print "$sql\n";
      $result2=mysql_query($sql);
      if($row2=mysql_fetch_array($result2)){
	$this->found_in_id=true;
	
	


      }


    }
  }


 /*
    Function: get
    Obtiene informacion de los diferentes precios de productos
 */
 // JFA
  
  function get($key='',$data=false){

    if(!$this->id)
      return;

    if(array_key_exists($key,$this->data))
      return $this->data[$key];
    

    switch($key){
    case('Margin'):
      return percentage($this->data["Product Cost"],$this->data["Product Price"]);break;
    case('RRP Margin'):
      return percentage($this->data["Product Price"],$this->data["Product RRP"]);break; 
    case('Same Code 1 Quarter WAVG Quantity Delivered'):
      return $this->data['Product Same Code 1 Quarter Acc Quantity Delivered']/12;
      break;

    case('Price Info'):
      $info=sprintf('<div class="ind_form"><span class="code">%s</span><br/><span class="name">%sx %s</span><br/><span class="price">%s</span><br/><span class="rrp">%s</span><br/>
</div>'
		    ,$this->data['Product Code']
		    ,$this->data['Product Units Per Case']
		    ,$this->data['Product Name'],$this->get('Price Formated'),$this->get('RRP Formated')
		    
		    
);
      return $info;
      break;
      
    case('Price Anonymous Info'):
  
      
      $info=sprintf('<div class="prod_info"><span >%s</span><br><span >%s</span></div>'
		    ,$this->get('Price Formated',$data)
		    ,$this->get('RRP Formated',$data)
		    
		    
);
      return $info;
      break;
    case('Price Subfamily Info'):
  if(isset($data['inside form']) and $data['inside form']){
    $info=sprintf('<tr class="prod_info"><td colspan=4><span >%s</span><br><span >%s</span><br><span >%s</span></td></tr>'
		  ,$this->data['Product Family Special Characteristic']
		  ,$this->get('Price Formated',$data)
		,$this->get('RRP Formated',$data)
		  );
  }else{
    $info=sprintf('<div class="prod_info"><span >%s</span><br><span >%s</span><br><span >%s</span></div>'
		,$this->data['Product Family Special Characteristic']
		  ,$this->get('Price Formated',$data)
		  ,$this->get('RRP Formated',$data)
		  );
  }
  return $info;
  break;

    case('Full Order Form'):


      if($this->locale=='de_DE'){
	$out_of_stock='nicht vorrätig';
	$discontinued='ausgelaufen';
      }elseif($this->locale=='fr_FR'){
	$out_of_stock='Rupture de stock';
	$discontinued='Rupture de stock';
      }else{
	$out_of_stock='Out of Stock';
	$discontinued='Discontinued';
      }

      if($this->data['Product Web State']=='Online Force Out of Stock'){
	$_form='<span style="color:red;font-weight:800">'.$out_of_stock.'</span>';
      }else{
      global $site_checkout_address_indv,$site_checkout_id,$site_url;
      $_form=sprintf('<form action="%s" method="post">
<input type="hidden" name="userid" value="%s">
<input type="hidden" name="product" value="%s %sx %s">
<input type="hidden" name="return" value="%s">
<input type="hidden" name="price" value="%.2f">
<input class="order" type="text" size="1" class="qty" name="qty" value="1">
<input class="submit" type="Submit" value="%s" style="cursor:pointer; font-size:12px;font-family:arial;" ></form>',
		     addslashes($site_checkout_address_indv)
		     ,addslashes($site_checkout_id)
		     ,addslashes($this->data['Product Code'])
		     ,addslashes($this->data['Product Units Per Case'])
		     ,clean_accents(addslashes($this->data['Product Name']))
		     ,$site_url.$_SERVER['PHP_SELF']
		    ,$this->data['Product Price']
		     ,$this->get('Order Msg')
		     );
      }
	

      $form=sprintf('<div style="font-size:11px;font-family:arial;" class="ind_form"><span class="code">%s</span><br/><span class="name">%sx %s</span><br/><span class="price">%s</span><br/><span class="rrp">%s</span><br/>%s</div>'
		    ,$this->data['Product Code']
		    ,$this->data['Product Units Per Case']
		      ,$this->data['Product Name']
		    ,$this->get('Price Formated'),$this->get('RRP Formated')
		    ,$_form

		      
);
	
      
	return $form;
      

	break;
    case('Order List Form'):
       if($this->locale=='de_DE'){
	$out_of_stock='nicht vorrätig';
	$discontinued='ausgelaufen';
      }elseif($this->locale=='fr_FR'){
	$out_of_stock='Rupture de stock';
	$discontinued='Rupture de stock';
      }else{
	$out_of_stock='Out of Stock';
	$discontinued='Discontinued';
      }

      $counter=$data['counter'];
      $options=$data['options'];
       $rrp='';
       if(isset($options['show individual rrp']) and $options['show individual rrp'] )
	$rrp=" <span class='rrp_in_list'>(".$this->get('RRP Formated').')</span>';
       

         if($this->data['Product Web State']=='Online Force Out of Stock'){
	   $form=sprintf('<tr><td class="first"><span class="price">%s</span>%s</td><td  colspan=2><span  style="color:red;font-weight:800">%s</span></td></tr>'
			 ,$this->get('Price')
			 ,$this->data['Product Code']
			 ,$out_of_stock
			 );
	 }
	 else{
	   $form=sprintf('<tr><td class="first"><span class="price">%s</span>%s</td><td class="qty"><input type="text"  class="qty" name="qty%d"  id="qty%d"    /><td><span class="desc">%s</span></td></tr><input type="hidden"  name="price%d"  value="%.2f"  ><input type="hidden"  name="product%d"  value="%s %dx %s" >'
			 ,$this->get('Price')
			 ,$this->data['Product Code']
			 ,$counter
			 ,$counter
			 ,$this->data['Product Special Characteristic'].$rrp
			 ,$counter
			 ,$this->data['Product Price']
			 ,$counter
			 ,$this->data['Product Code']
			 ,$this->data['Product Units Per Case']
			 ,clean_accents($this->data['Product Name'])
			 );
	 }

	return $form."\n";
      

	break;	

    case('Order Msg'):
      if($this->locale=='de_DE')
	return 'Bestellen';
      elseif($this->locale=='fr_FR')
	return 'Commander';
      else
	return 'Order';
    case('Formated Price'):
        return $this->money($this->data['Product Price']);;
      break;
    case('Price'):
     if(preg_match('/system/i',$data))
      return number($this->data['Product Price'],2,true);
      else
        return $this->number($this->data['Product Price'],2,true);
      break;
    case('Price Formated'):
      if($this->locale=='de_DE'){

	if(isset($data['price per unit text'])){
	$str=$data['price per unit text'].' '.$this->money($this->data['Product Price']);
	}else{
	  if($this->data['Product Units Per Case']>1)
	   $str=$this->money($this->data['Product Price']).'/'.$this->data['Product Units Per Case'].' ('.$this->money($this->data['Product Price']/$this->data['Product Units Per Case'])." pro Stück)";
	  else
	    $str=$this->money($this->data['Product Price'].' pro Stück');

	}

	if($data=='from')
	  return 'Preis ab '.$str;
	else
	  return 'Preis: '.$str;


      }elseif($this->locale=='fr_FR'){
   
	if( is_array($data) and isset($data['price per unit text'])  ){
	$str= $this->money($this->data['Product Price']).' '.$data['price per unit text'];
	}else{
	  if($this->data['Product Units Per Case']>1)
	    $str= $this->money($this->data['Product Price']).'/'.$this->data['Product Units Per Case'].' ('.$this->money($this->data['Product Price']/$this->data['Product Units Per Case'])." par unité)";
	  else
	    $str= $this->money($this->data['Product Price']).' par unité';

	}
	if($data=='from')
	  return 'Prix à partir de '.$str;
	else
	  return 'Prix: '.$str;
	
      }
    
   
      break;
    case('Price Per Unit Formated'):
    case('Formated Price Per Unit'):
   
      return $this->money($this->data['Product Price']/$this->data['Product Units Per Case']);
      break; 
    case('Price Per Unit'):
      if(preg_match('/system/i',$data))
      return number($this->data['Product Price']/$this->data['Product Units Per Case'],2,true);
      else
      return $this->number($this->data['Product Price']/$this->data['Product Units Per Case'],2,true);
      break; 

    case('Price Per Other'):
      if(is_numeric($arg) and $arg>0)
	return $this->money($this->data['Product Price']/$arg2);
      
      break; 
    case('RRP Per Outer Formated'):
	return $this->money($this->data['Product RRP']);
	break; 
	 case('RRP Per Outer'):
	 if(preg_match('/system/i',$data))
      return number($this->data['Product RRP'],2,true);
      else
	return $this->number($this->data['Product RRP'],2,true);
	break; 
	
    case('Formated RRP Per Unit'):
	return $this->money($this->data['Product RRP']/$this->data['Product Units Per Case']);
	break; 	
    case('RRP Per Unit'):
    if(preg_match('/system/i',$data))
      return number($this->data['Product RRP']/$this->data['Product Units Per Case'],2,true);
      else
	return $this->number($this->data['Product RRP']/$this->data['Product Units Per Case'],2,true);
	break; 	    
    case('Product RRP Per Unit'):
       if(preg_match('/system/i',$data))
      return $number($this->data['Product RRP']/$this->data['Product Units Per Case']);
      else
      return $this->number($this->data['Product RRP']/$this->data['Product Units Per Case']);
      break; 	
      


    case('RRP Formated'):
      if($this->locale=='de_DE')
	return 'UVP: '.$this->money($this->data['Product RRP']/$this->data['Product Units Per Case']).' pro Stück';
      elseif($this->locale=='fr_FR'){
	if(isset($data['rrp per unit text']))
	  return $this->money($this->data['Product RRP']/$this->data['Product Units Per Case']).' PVC '.$data['rrp per unit text'];
	else
	  return $this->money($this->data['Product RRP']/$this->data['Product Units Per Case']).'/unité PVC';
      }
      else
	return _('RRP').': '.$this->money($this->data['Product RRP']/$this->data['Product Units Per Case']).' '._('each');
      break;

    
    case('Profit'):
      return $this->money($this->data['Product RRP']-$this->data['Product Price']);
      break;

    
    case('Product Net Weight Per Unit'):
      return $this->data['Product Net Weight']/$this->data['Product Units Per Case'];
      break;
    case('Net Weight Per Unit Formated'):
        $weight_units=_('Kg');
      return $this->number($this->data['Product Net Weight']/$this->data['Product Units Per Case']).$weight_units;
      break;
    case('Net Weight Per Unit'):
      if(preg_match('/system/i',$data))
      return number($this->data['Product Net Weight']/$this->data['Product Units Per Case']);
      else
      return $this->number($this->data['Product Net Weight']/$this->data['Product Units Per Case']);
      break;
      
  case('Product Description MD5 Hash'):
    return md5($this->data['Product Description MD5 Hash']);
      break;
    case('Parts SKU'):
      $sql=sprintf("select `Part SKU` from `Product Part List` where `Product ID`=%d ;",$this->data['Product ID']);
      $result=mysql_query($sql);
      $parts=array();
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$parts[]=$row['Part SKU'];
      }
      return $parts;
      break;

    case('Unit Type'):
      return $this->data['Product Unit Type'];
    case('Unit Type Abbreviation'):
      if($this->data['Product Unit Type']=='Piece')
	return _('Pc');
      if($this->data['Product Unit Type']=='Grams')
	return _('g');
      if($this->data['Product Unit Type']=='Meter')
	return _('m');
      
      return $this->data['Product Unit Type'];

    case('Number of Parts'):
      if(!$this->parts)
	$this->load('part_list');
      return count($this->parts);
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
      preg_split('/x/i',$this->data['dim']);
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

 
 /*
    Function: money
    Proporciona la moneda dependiendo el pais
 */
 // JFA
function money($number){

if($this->system_format){
  return money($number,$this->data['Product Currency']);
  }


if(preg_match('/fr_FR|de_DE|es_ES/',$this->locale)){
    return $this->number($number,2).$this->data['Currency Symbol'];
}else
    return $this->data['Currency Symbol'].$this->number($number,2);

}

 /*
    Function: number
    Formatea el numero dependiendo el pais
 */
 // JFA
function number($number,$decimal_places=1){


  if($this->system_format){
    return number($number,$decimal_places);
  }

    $thousand_sep=',';
    $decimal_point='.';
    if(preg_match('/es_ES|de_DE/',$this->locale)){
    $thousand_sep='.';
    $decimal_point=',';
    }

  
    return number_format($number,$decimal_places,$decimal_point,$thousand_sep);
  
}
 /*
    Function: new_id
    Asigna un nuevo ID al registro de la tabla Product Dimension.
 */
 // JFA

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

 /*
    Function: new_part_list_id
    Asigna un nuevo ID al registro de la tabla Product Part List.
 */
 // JFA

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

 /*
    Function: valid_id
    Valida que el ID sea numerico y que este en el rango 0 - 9223372036854775807
 */
 // JFA


function valid_id($id){
  if(is_numeric($id) and $id>0 and $id<9223372036854775807)
    return true;
  else
    return false;
}



 /*
    Function: get_base_data
    Obtiene los diferentes valores de los atributos del producto
 */
 // JFA


 function get_base_data_history(){
 global $myconf;
$base_data=array(
		     'product history price'=>'',
		     'product history name'=>'',
		     'product history short description'=>'',
		     'product history xhtml short description'=>'',
		     'product history special characteristic'=>'',
		     'product history valid from'=>date("Y-m-d H:i:s"),
		     'product history valid to'=>date("Y-m-d H:i:s"),
		   
		   
		     );

 return $base_data;
 }


 /*
    Function: get_base_data
    Obtiene los diferentes valores de los atributos del producto
 */
 // JFA


 function get_base_data(){
 global $myconf;
$base_data=array(
		     'product sales state'=>'For sale',
		     'product type'=>'Normal',
		     'product record type'=>'In process',
		     'product web state'=>'Offline',
		     'product store key'=>1,
		     'product locale'=>$myconf['lang'].'_'.$myconf['country'],
		     'product currency'=>$myconf['currency_code'],
		     'product id'=>'',
		     'product code file as'=>'',
		     'product code'=>'',
		     'product price'=>'',
		     'product rrp'=>'',
		     'product name'=>'',
		     'product short description'=>'',
		     'product xhtml short description'=>'',
		     'product special characteristic'=>'',
		     'product family special characteristic'=>'',
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
		     'product most recent key'=>'',
		   
		     );

 return $base_data;
 }

 /*
    Function: get_base_data
    Obtiene los diferentes valores de los atributos del producto
 */
 // JFA


 function get_base_data_same_code(){
 global $myconf;
$base_data=array(
		     'product code file as'=>'',
		     'product code'=>'',
		     'product same code valid from'=>date("Y-m-d H:i:s"),
		     'product same code valid to'=>date("Y-m-d H:i:s"),
		    
		   
		     );

 return $base_data;
 }

 /*
    Method: create
    Crea o actualiza valores de la tabla Product Dimension
 */
 // JFA


  function create($data){

    //print_r($data);exit;

    global $myconf;
    $base_data=$this->get_base_data();
    foreach($data as $key=>$value){
      if(isset($base_data[strtolower($key)]))
	$base_data[strtolower($key)]=_trim($value);
    }
    $base_data_history=$this->get_base_data_history();
    foreach($data as $key=>$value){
      $key=strtolower($key);
      $key=preg_replace('/^product/','product history',$key);
      if(isset($base_data_history[$key]))
	$base_data_history[$key]=_trim($value);
    }
    $base_data_same_code=$this->get_base_data_same_code();
    foreach($data as $key=>$value){
       $key=strtolower($key);
       if($key=='product valid from')
	 $key='product same code valid from';
       else if($key=='product valid to')
	 $key='product same code valid to';

      if(isset($base_data_same_code[$key]))
	$base_data_same_code[$key]=_trim($value);
    }

    if(!$this->valid_id($base_data['product id'])  ){
       $base_data['product id']=$this->new_id();
     }
    $base_data_same_code['product code file as']=$this->normalize_code($base_data_same_code['product code']);
    $base_data['product code file as']=$this->normalize_code($base_data['product code']);
    
    if(!is_numeric($base_data['product units per case']) or $base_data['product units per case']<1)
      $base_data['product units per case']=1;

    $department=false;
    $has_department=false;
    $new_department=false;

    if(isset($base_data['product main department key'])){
      $department=new Department($base_data['product main department key']);
      if($department->id and $base_data['product store key']==$department->data['Product Department Store Key']){
	$has_department=true;
	$base_data['product main department key']=$department->id;
	$base_data['product main department code']=$department->data['Product Department Code'];
	$base_data['product main department name']=$department->data['Product Department Name'];
      }
	
    }
    
    //  print $base_data['product main department code']."\n";
    if($base_data['product main department code']!='' and !$has_department){

      $department=new Department('code_store',$base_data['product main department code'],$base_data['product store key']);
      
      if(!$department->id){

	$dept_data=array(
			'code'=>$base_data['product main department code'],
			'name'=>$base_data['product main department name'],
			'store_key'=>$base_data['product store key'],
			 );
	$department=new Department('create',$dept_data);
	if(!$department->new)
	  exit($department->msg);
	$new_department=true;
      }
      //exit("xxx");
      $has_department=true;
      $base_data['product main department key']=$department->id;
      $base_data['product main department code']=$department->data['Product Department Code'];
      $base_data['product main department name']=$department->data['Product Department Name'];
    }
  
    if(!$has_department){
      exit("Product Class: error no depaterment info provided can not create product\n");
    }

    $family=false;$new_family=false;
    $has_family=false;
    if(isset($base_data['product family key'])){
      $family=new Family($base_data['product family key']);
      if($family->id and $base_data['product store key']==$family->data['Product Family Store Key']){
	$has_family=true;
	$base_data['product family key']=$family->id;
	$base_data['product family code']=$family->data['Product Family Code'];
	$base_data['product family name']=$family->data['Product Family Name'];
      }
	
    }
    

   
    if($base_data['product family code']!='' and !$has_family){
      $family=new Family('code_store',$base_data['product family code'],$base_data['product store key']);
      if(!$family->id){
	$fam_data=array(
			'code'=>$base_data['product family code'],
			'name'=>$base_data['product family name'],
			'Product Family Main Department Key'=>$department->id
			);
	$family=new Family('create',$fam_data);
	if(!$family->new)
	  exit($family->msg);
	$new_family=true;
      }
      $has_family=true;
      $base_data['product family key']=$family->id;
      $base_data['product family code']=$family->data['Product Family Code'];
      $base_data['product family name']=$family->data['Product Family Name'];
    }
    if(!$has_family){
      exit("Product Class: error no family info provided can not create product\n");
    }

    
    $keys='(';$values='values(';
    foreach($base_data_history as $key=>$value){
      $keys.="`$key`,";
      $values.=prepare_mysql($value).",";
    }
    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    $sql=sprintf("insert into `Product History Dimension` %s %s",$keys,$values);
    if(mysql_query($sql)){
      $this->id = mysql_insert_id();
      

      $base_data['product most recent key']=$this->id;


      $keys='(';$values='values(';
      foreach($base_data as $key=>$value){
	$keys.="`$key`,";
	$values.=prepare_mysql($value).",";
      }
      $keys=preg_replace('/,$/',')',$keys);
      $values=preg_replace('/,$/',')',$values);
      $sql=sprintf("insert into `Product Dimension` %s %s",$keys,$values);
      //print "$sql\n";
      if(mysql_query($sql)){
	$this->pid = mysql_insert_id();

	$keys='(';$values='values(';
	foreach($base_data_same_code as $key=>$value){
	  $keys.="`$key`,";
	  $values.=prepare_mysql($value).",";
	}
      $keys=preg_replace('/,$/',')',$keys);
      $values=preg_replace('/,$/',')',$values);
      $sql=sprintf("insert into `Product Same Code Dimension` %s %s",$keys,$values);
      if(mysql_query($sql)){
	$this->code = $base_data['product code'];

      }else
	exit($sql);
      }else
	exit($sql);
    }else
      exit($sql);

    
    // if($base_data['product most recent']=='Yes'){
//       $sql=sprintf("update `Product Dimension` set `Product Most Recent`='No' where `Product ID`=%d  and `Product Key`!=%d",$base_data['product id'],$this->id);
//       mysql_query($sql);
	
// 	$sql=sprintf("update  `Product Dimension` set `Product Most Recent`='Yes',`Product Most Recent Key`=%d ,`Product Same Code Most Recent Key`=%d,`Product Same ID Most Recent Key`=%d  where `Product Key`=%d",$this->id,$this->id,$this->id,$this->id);
// 	mysql_query($sql);
//       }
//       $this->get_data('id',$this->id);
      
    
    $this->get_data('pid',$this->pid);
   
    $sql=sprintf("update  `Product Dimension` set `Product Short Description`=%s ,`Product XHTML Short Description`=%s where `Product ID`=%d"
		 ,prepare_mysql($this->get('short description'))
		 ,prepare_mysql($this->get('xhtml short description'))
		 ,$this->pid);
    mysql_query($sql);
    $sql=sprintf("update  `Product History Dimension` set `Product History Short Description`=%s ,`Product History XHTML Short Description`=%s ,`Product ID`=%d where `Product Key`=%d"
		 ,prepare_mysql($this->get('short description'))
		 ,prepare_mysql($this->get('xhtml short description'))
		 ,$this->pid
		 ,$this->id
		 );
       mysql_query($sql);
       //print $sql;
	      //exit;
       
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
      
      $this->get_data('pid',$this->pid);
      $this->msg='Product Created';
      $this->new=true;
      $family->load('products_info');
      if($new_family){
	$family->add_product($this->id,'principal');
	
	if(isset($department) and is_object($department) and $department->id)
	  $department->add_family($family->id,'principal noproducts');
      }
      
      if(isset($department) and  is_object($department) and $department->id){
	$department->add_product($this->id,'principal');
	$department->load('products_info');
      }
      

    //   $sql="select count(*) as num from `Product Department Bridge` where `Product Key`=".$this->id;
//       $result=mysql_query($sql);
//       if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
// 	$sql=sprintf("update  `Product Dimension` set `Product Department Degeneration`=%s where `Product Key`=%d",$row['num'],$this->id);
// 	mysql_query($sql);
//        }

//      $this->update_same_id_valid_dates();
      //     $this->update_same_code_valid_dates();


      $store=new Store($this->data['Product Store Key']);
      $store->load('products_info');

      
         


    //$family->add_product($this->id,'principal');
    //	$department->add_family($family->id,'principal noproducts');
    //  $department->add_product($this->id,'principal');
    //$this->fix_todotransaction();
    //$this->set_stock(true);
    //$this->set_sales(true);
  }

 /*
    Method: create_sibling
    Crea o actualiza valores de la tabla Product Department Bridge, Product Dimension
 */
 // 

  
  function create_sibling($data,$new_id=false,$most_recent=true){
    global $myconf;
    $base_data=$this->get_base_data();

    
    foreach($this->data as $key=>$value){

      if(isset($base_data[strtolower($key)]))
	$base_data[strtolower($key)]=_trim($value);
    }
    
    foreach($data as $key=>$value){
      if(isset($base_data[strtolower($key)]))
	$base_data[strtolower($key)]=_trim($value);
    }

    if($base_data['product web state']=='')
      $base_data['product web state']='Unknown';
    if($new_id)
      $base_data['product id']=$this->new_id();

    $date=date("Y-m-d H:i:s");
    $base_data['product valid from']=$date;
    $base_data['product valid to']=$date;

    if($most_recent){
      $base_data['product most recent']='Yes';
      $base_data['product same code most recent']='Yes';
      $base_data['product same id most recent']='Yes';
    }else{
      $base_data['product most recent']='No';
      $base_data['product same code most recent']='No';
      $base_data['product same id most recent']='No';
    }

    $keys='(';$values='values(';
    foreach($base_data as $key=>$value){
      $keys.="`$key`,";
      $values.=prepare_mysql($value).",";
    }


  

    $keys=preg_replace('/,$/',')',$keys);
    $values=preg_replace('/,$/',')',$values);
    $sql=sprintf("insert into `Product Dimension` %s %s",$keys,$values);

    //        }
    if(mysql_query($sql)){
      $this->new_id = mysql_insert_id();
      $this->new=true;
      if($most_recent){
	
	
	
	$sql=sprintf("update `Product Dimension` set  `Product Valid To`=%s where `Product Id`=%s and `Product Same ID Most Recent`='Yes' and `Product Key`!=%d"
		     ,prepare_mysql($date)
		     ,$base_data['product id']
		     ,$this->new_id);
	mysql_query($sql);
	$sql=sprintf("update `Product Dimension` set  `Product Same ID Valid To`=%s where `Product ID`=%s  and `Product Key`!=%d"
		     ,prepare_mysql($date)
		     ,$base_data['product id']
		     ,$this->new_id);
	mysql_query($sql);
	$sql=sprintf("update `Product Dimension` set  `Product Same Code Valid To`=%s where `Product Code`=%s  and `Product Key`!=%d"
		     ,prepare_mysql($date)
		     ,$base_data['product code']
		     ,$this->new_id);
	mysql_query($sql);




	$sql=sprintf("update `Product Dimension` set  `Product Same Code Most Recent`='No' where `Product Code`=%s  and `Product Key`!=%d"
		     ,prepare_mysql($base_data['product code'])
		     ,$this->new_id);
	mysql_query($sql);
	$sql=sprintf("update `Product Dimension` set `Product Same ID Most Recent`='No',`Product Most Recent`='No' where `Product ID`=%d  and `Product Key`!=%d",$base_data['product id'],$this->new_id);
	mysql_query($sql);
	print "$sql\n";
	$sql=sprintf("update  `Product Dimension` set `Product Most Recent Key`=%d ,`Product Same ID Most Recent Key`=%d,`Product Same Code Most Recent Key`=%d  where `Product Key`=%d"
		     ,$this->new_id
		     ,$this->new_id
		     ,$this->new_id
		     ,$this->new_id
		     );
	mysql_query($sql);
print "$sql\n";
      }
      $this->load('redundant data');
    
      
      // Departments
      $sql=sprintf("select * from `Product Department Bridge` where `Product Key`=%d",$this->id);
      $result=mysql_query($sql);
      while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
	$sql=sprintf("insert into `Product Department Bridge` (`Product Key`,`Product Department Key`) values (%d,%d)",$this->new_id,$row['Product Department Key']);
 	$department=new Department($row['Product Department Key']);
 	$department->load('products_info');
      }
      
      $family=new Family($this->data['Product Family Key']);
      $family->load('products_info');
      $store=new Store($this->data['Product Store Key']);
      $store->load('products_info');
      // Category


      
    }else{
      $this->new=false;
      print "$sql Error Product cannot be created\n";
      exit;
    }



  }
  

 /*
    Function: new_part_list
    Crea o actualiza valores de la tabla Product Part List
 */
 // JFA



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
      // print "$sql\n";exit;

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

 /*
    Function: normalize_code
    Da el formato correcto al codigo indicado.
 */
 // JFA


function normalize_code($code){
    $ncode=$code;
    $c=preg_split('/\-/',$code);
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
  

 /*
    Method: group_by
    Despliega informacion de la tabla Product Dimnsion de forma agrupada
 */
 // JFA


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

/*
    Method: load
    Carga y actualiza datos de la tabla Product Dimension, extrae información de Product Part List,Supplier Product Dimension,Part Dimension
*/

 function load($key){

   switch($key){
   case('redundant data'):
     $sql=sprintf("update  `Product Dimension` set `Product Short Description`=%s ,`Product XHTML Short Description`=%s where `Product Key`=%d",prepare_mysql($this->get('short description')),prepare_mysql($this->get('xhtml short description')),$this->id);
     mysql_query($sql);
     
     break;
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

   case('part_location_list'):
     
     $date=date("Y-m-d");
     $sql=sprintf("select PPL.`Part SKU`,ISF.`Location Key`,`Quantity On Hand`,`Parts Per Product`,`Location Code`   from `Product Part List` PPL left join `Inventory Spanshot Fact` ISF on (ISF.`Part SKU`=PPL.`Part SKU`) left join `Location Dimension` LD on (LD.`Location Key`=ISF.`Location Key`)  where `Product ID`=%d and `Date`=%s and `Product Part Most Recent`='Yes';",$this->data['Product ID'],prepare_mysql($date));
     //      print $sql;
     $result=mysql_query($sql);
     $this->parts_location=array();
     while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       $this->parts_location[$row['Part SKU']]=array(
					    'sku'=>$row['Part SKU']
					    ,'location_key'=>$row['Location Key']
					    ,'location_code'=>$row['Location Code']
					    ,'stock'=>$row['Quantity On Hand']
					    ,'parts_per_product'=>$row['Parts Per Product']
					  
						     );
     }

     break; 
   case('part_list'):
     $sql=sprintf("select IFNULL(`Part Days Available Forecast`,'UNK') as days,`Parts Per Product`,`Product Part Note`,PPL.`Part SKU`,`Part XHTML Description` from `Product Part List` PPL left join `Part Dimension` PD on (PD.`Part SKU`=PPL.`Part SKU`) where `Product ID`=%d and `Product Part Most Recent`='Yes';",$this->data['Product ID']);
     $result=mysql_query($sql);
     $this->parts=array();
     while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       $this->parts[$row['Part SKU']]=array(
					    'sku'=>$row['Part SKU']
					    ,'description'=>$row['Part XHTML Description']
					    ,'note'=>$row['Product Part Note']
					    ,'parts_per_product'=>$row['Parts Per Product']
					    ,'days_available'=>$row['days']
					    );
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

     if($mysql_where=='')
       $mysql_where=0;
       $supplied_by='';
       $sql=sprintf("select  (select `Supplier Product Code` from `Supplier Product Dimension` where `Supplier Product ID`=SPPL.`Supplier Product ID` and `Supplier Product Most Recent` limit 1) as `Supplier Product Code`,(select `Supplier Product Key` from `Supplier Product Dimension` where `Supplier Product ID`=SPPL.`Supplier Product ID` and `Supplier Product Most Recent` limit 1) as `Supplier Product Key` ,  SD.`Supplier Key`,`Supplier Code` from `Supplier Product Part List` SPPL   left join `Supplier Dimension` SD on (SD.`Supplier Key`=SPPL.`Supplier Key`)   where `Part SKU` in (%s) order by `Supplier Key`;",$mysql_where);
      $result=mysql_query($sql);
    
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



      $sql=sprintf("update `Product Dimension` set `Product XHTML Parts`=%s  , `Product XHTML Supplied By`=%s where `Product ID`=%d",prepare_mysql(_trim($parts)),prepare_mysql(_trim($supplied_by)),$this->pid);
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

     //    print "Stock: $stock\n";
     if(!$change or $stock_error)
       $stock='NULL';
     //print "Stock: $stock\n";
     if(is_numeric($stock) and $stock<0)
       $stock='NULL';
     // print "Stock: $stock\n";
     $sql=sprintf("update `Product Dimension` set `Product Availability`=%s where `Product key`=%d",$stock,$this->id);
     mysql_query($sql);
     $days_available='NULL';
     $avg_day_sales=0;


     
     switch($stock_forecast_method){
     case('basic1'):

       
       $this->load('part_list');
       $unk=false;
       $min_days=-1;
       foreach($this->parts as $part){
	 if(!is_numeric($part['days_available']))
	   $unk=true;
	 else{
	   if($min_days<$part['days_available'])
	     $min_days=$part['days_available'];
	       }

       }
       if($unk or count($this->parts)==0 or $min_days<0)
	 $days_available='NULL';
       else
	 $days_available=$min_days;
       //print_r($this->parts);
       //exit;
       
       break;
     }
     
     
     //   print "State ".$this->data['Product Sales State']."\n";
     
     if($this->data['Product Sales State']=='Discontinued'){
       $stock=0;
       $tipo='No applicable';

       
       
     }else if($this->data['Product Sales State']=='For sale'){
       if(!is_numeric($stock)){
	 $tipo='Unknown';
       } elseif($stock<0){
	 $tipo='Unknown';
       }else if($stock==0){
	 $tipo='Out of Stock';
       }else{
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
     // and strtoupper($this->data['Product Code'])=='HMS-01'
    /*  if( preg_match('/hms-31/i',$this->data['Product Code'])){ */
     print $this->data['Product Code']." ".$this->data['Product Sales State']." $tipo $stock $days_available\n";
/*        //       print_r($this->data); */
/*          exit; */
/*         } */
     $sql=sprintf("update `Product Dimension` set `Product Availability State`=%s,`Product Available Days Forecast`=%s where `Product key`=%d",prepare_mysql($tipo),$days_available,$this->id);
     if(!mysql_query($sql))
       exit("can no update stock prod product.php l 1311\n");
     break;



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
     

     
     $sql=sprintf("update `Product Dimension` set `Product Total Days On Sale`=%f , `Product 1 Year Acc Days On Sale`=%f ,`Product 1 Quarter Acc Days On Sale`=%f ,`Product 1 Month Acc Days On Sale`=%f ,`Product 1 Week Acc Days On Sale`=%f  where `Product Key`=%d "
		  ,$tdays
		  ,$ydays
		  ,$qdays
		  ,$mdays
		  ,$wdays
	
		  
		  ,$this->id
		  );
     // print "$sql\n";
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
 // print $sql; 
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
   case('cost'):
     $cost=0;
     $unk=false;
     $change=false;
     $sql=sprintf(" select PD.`Part SKU`,`Part Current Stock Cost`,`Part Current Stock`,`Parts Per Product` from `Part Dimension` PD left join `Product Part List` PPL on (PD.`Part SKU`=PPL.`Part SKU`)  where `Product ID`=%s  and `Product Part Most Recent`='Yes' group by PD.`Part SKU`  ",prepare_mysql($this->data['Product ID']));
   
     $result=mysql_query($sql);
     while($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       $change=true;
       if(
	  is_numeric($row['Part Current Stock']) 
	  and is_numeric($row['Parts Per Product'])  
	  and $row['Parts Per Product']>0 
	  and is_numeric($row['Part Current Stock'])  
	  and $row['Part Current Stock']>0 
	  ) {

	 $cost+=$row['Part Current Stock Cost']/$row['Parts Per Product']/$row['Part Current Stock'];
       }elseif(is_numeric($row['Parts Per Product'])  and $row['Parts Per Product']>0){

	 $part=new Part($row['Part SKU']);
	 $estimated_cost=$part->data['Part Average Future Cost'];
	 //  print "-  $estimated_cost ------";
	  if(is_numeric($estimated_cost)){
	   $cost+=$estimated_cost/$row['Parts Per Product'];
	   //print "cost  $cost ------\n";

	  }else
	   $unk=true;
       }else
	  $unk=true;
       
     }

     if(!$change or $unk or !is_numeric($cost) ){
       $_cost='NULL';
       $this->data['Product Cost']='';
     }else{
       $this->data['Product Cost']=$cost;
       $_cost=$cost;
     }

     //print "****** $unk   $_cost\n";
     $sql=sprintf("update `Product Dimension` set `Product Cost`=%s  where `Product Key`=%d "
		  ,$_cost
		  ,$this->id
		  );
     if(!mysql_query($sql))
       exit("$sql\ncan not update product sales\n");
     
     

     break;
   case('sales'):
     $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact` where `Consolidated`='Yes' and `Product Key`=%d",$this->id);
     
       $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       $this->data['Product Total Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Total Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Total Invoiced Amount']=$row['gross']-$row['disc'];
       $this->data['Product Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       if($this->data['Product Total Invoiced Amount']!=0)
	 $this->data['Product Total Margin']=100*$this->data['Product Total Profit']/$this->data['Product Total Invoiced Amount'];
       else
	 $this->data['Product Total Margin']='NULL';
       $this->data['Product Total Quantity Ordered']=$row['ordered'];
       $this->data['Product Total Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Total Quantity Delivered']=$row['delivered'];
     }else{
       $this->data['Product Total Invoiced Gross Amount']=0;
       $this->data['Product Total Invoiced Discount Amount']=0;
       $this->data['Product Total Invoiced Amount']=0;
       $this->data['Product Total Profit']=0;
       $this->data['Product Total Margin']='NULL';

       $this->data['Product Total Quantity Ordered']=0;
       $this->data['Product Total Quantity Invoiced']=0;
       $this->data['Product Total Quantity Delivered']=0;
     }
     $sql=sprintf("update `Product Dimension` set `Product Total Invoiced Gross Amount`=%.2f,`Product Total Invoiced Discount Amount`=%.2f,`Product Total Invoiced Amount`=%.2f,`Product Total Profit`=%.2f,`Product Total Margin`=%s, `Product Total Quantity Ordered`=%s , `Product Total Quantity Invoiced`=%s,`Product Total Quantity Delivered`=%s  where `Product Key`=%d "
		  ,$this->data['Product Total Invoiced Gross Amount']
		  ,$this->data['Product Total Invoiced Discount Amount']
		  ,$this->data['Product Total Invoiced Amount']

		  ,$this->data['Product Total Profit']
		   ,$this->data['Product Total Margin']

		  ,prepare_mysql($this->data['Product Total Quantity Ordered'])
		  ,prepare_mysql($this->data['Product Total Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Total Quantity Delivered'])
		  ,$this->id
		  );
     if(!mysql_query($sql))
       exit("$sql\ncan not update product sales\n");

     
       $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF left join  `Product Dimension` PD on (OTF.`Product Key`=PD.`Product Key`) where `Consolidated`='Yes' and `Product ID`=%d",$this->data['Product ID']);
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       $this->data['Product Same ID Total Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Same ID Total Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Same ID Total Invoiced Amount']=$row['gross']-$row['disc'];
       $this->data['Product Same ID Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       
       $this->data['Product Same ID Total Quantity Ordered']=$row['ordered'];
       $this->data['Product Same ID Total Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Same ID Total Quantity Delivered']=$row['delivered'];
     }else{
       $this->data['Product Same ID Total Invoiced Gross Amount']=0;
       $this->data['Product Same ID Total Invoiced Discount Amount']=0;
       $this->data['Product Same ID Total Invoiced Amount']=0;
       $this->data['Product Same ID Total Profit']=0;
       $this->data['Product Same ID Total Margin']=0;

       $this->data['Product Same ID Total Quantity Ordered']=0;
       $this->data['Product Same ID Total Quantity Invoiced']=0;
       $this->data['Product Same ID Total Quantity Delivered']=0;
     }

 $sql=sprintf("update `Product Dimension` set `Product Same ID Total Invoiced Gross Amount`=%.2f,`Product Same ID Total Invoiced Discount Amount`=%.2f,`Product Same ID Total Invoiced Amount`=%.2f,`Product Same ID Total Profit`=%.2f, `Product Same ID Total Quantity Ordered`=%s , `Product Same ID Total Quantity Invoiced`=%s,`Product Same ID Total Quantity Delivered`=%s  where `Product Key`=%d "
		  ,$this->data['Product Same ID Total Invoiced Gross Amount']
		  ,$this->data['Product Same ID Total Invoiced Discount Amount']
		  ,$this->data['Product Same ID Total Invoiced Amount']

		  ,$this->data['Product Same ID Total Profit']
		  ,prepare_mysql($this->data['Product Same ID Total Quantity Ordered'])
		  ,prepare_mysql($this->data['Product Same ID Total Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Same ID Total Quantity Delivered'])
		  ,$this->id
		  );
     if(!mysql_query($sql))
       exit("$sql\ncan not update product sales same id \n");



     $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF left join  `Product Dimension` PD on (OTF.`Product Key`=PD.`Product Key`) where `Consolidated`='Yes' and `Product Code`=%s",prepare_mysql($this->data['Product Code']));
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       $this->data['Product Same Code Total Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Same Code Total Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Same Code Total Invoiced Amount']=$row['gross']-$row['disc'];
       $this->data['Product Same Code Total Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       $this->data['Product Same Code Total Quantity Ordered']=$row['ordered'];
       $this->data['Product Same Code Total Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Same Code Total Quantity Delivered']=$row['delivered'];
     }else{
       $this->data['Product Same Code Total Invoiced Gross Amount']=0;
       $this->data['Product Same Code Total Invoiced Discount Amount']=0;
       $this->data['Product Same Code Total Invoiced Amount']=0;
       $this->data['Product Same Code Total Profit']=0;
       $this->data['Product Same Code Total Quantity Ordered']=0;
       $this->data['Product Same Code Total Quantity Invoiced']=0;
       $this->data['Product Same Code Total Quantity Delivered']=0;
     }
     
  
     $sql=sprintf("update `Product Dimension` set `Product Same Code Total Invoiced Gross Amount`=%.2f,`Product Same Code Total Invoiced Discount Amount`=%.2f,`Product Same Code Total Invoiced Amount`=%.2f,`Product Same Code Total Profit`=%.2f, `Product Same Code Total Quantity Ordered`=%s , `Product Same Code Total Quantity Invoiced`=%s,`Product Same Code Total Quantity Delivered`=%s  where `Product Key`=%d "
		  ,$this->data['Product Same Code Total Invoiced Gross Amount']
		  ,$this->data['Product Same Code Total Invoiced Discount Amount']
		  ,$this->data['Product Same Code Total Invoiced Amount']

		  ,$this->data['Product Same Code Total Profit']
		  ,prepare_mysql($this->data['Product Same Code Total Quantity Ordered'])
		  ,prepare_mysql($this->data['Product Same Code Total Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Same Code Total Quantity Delivered'])
		  ,$this->id
		  );
     if(!mysql_query($sql))
       exit("$sql\ncan not update product sales same code\n");






     
     $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact` where `Product Key`=%d and `Invoice Date`>=%s ",$this->id,prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));
     
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



  $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF left join  `Product Dimension` PD on (OTF.`Product Key`=PD.`Product Key`) where `Product ID`=%d and `Invoice Date`>=%s ",$this->data['Product ID'],prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));
     //  print "$sql\n\n";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       
       $this->data['Product Same ID 1 Year Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Same ID 1 Year Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Same ID 1 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];

       $this->data['Product Same ID 1 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       $this->data['Product Same ID 1 Year Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Same ID 1 Year Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Same ID 1 Year Acc Quantity Delivered']=$row['delivered'];
     }else{
	 $this->data['Product Same ID 1 Year Acc Invoiced Gross Amount']=0;
	 $this->data['Product Same ID 1 Year Acc Invoiced Discount Amount']=0;
	 $this->data['Product Same ID 1 Year Acc Profit']=0;$this->data['Product Same ID 1 Year Acc Invoiced Amount']=0;
	 $this->data['Product Same ID 1 Year Acc Quantity Ordered']=0;
	 $this->data['Product Same ID 1 Year Acc Quantity Invoiced']=0;
	 $this->data['Product Same ID 1 Year Acc Quantity Delivered']=0;
       }
       
       $sql=sprintf("update `Product Dimension` set `Product Same ID 1 Year Acc Invoiced Gross Amount`=%.2f,`Product Same ID 1 Year Acc Invoiced Discount Amount`=%.2f,`Product Same ID 1 Year Acc Invoiced Amount`=%.2f,`Product Same ID 1 Year Acc Profit`=%.2f, `Product Same ID 1 Year Acc Quantity Ordered`=%s , `Product Same ID 1 Year Acc Quantity Invoiced`=%s,`Product Same ID 1 Year Acc Quantity Delivered`=%s  where `Product Key`=%d "
		    ,$this->data['Product Same ID 1 Year Acc Invoiced Gross Amount']
		    ,$this->data['Product Same ID 1 Year Acc Invoiced Discount Amount']
		    ,$this->data['Product Same ID 1 Year Acc Invoiced Amount']
		    ,$this->data['Product Same ID 1 Year Acc Profit']
		    ,prepare_mysql($this->data['Product Same ID 1 Year Acc Quantity Ordered'])
		    ,prepare_mysql($this->data['Product Same ID 1 Year Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Same ID 1 Year Acc Quantity Delivered'])
		    ,$this->id
		    );
       if(!mysql_query($sql))
	 exit("$sql\ncan not update product sales 1 yr acc same id\n");




       $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF left join  `Product Dimension` PD on (OTF.`Product Key`=PD.`Product Key`) where `Product Code`=%s and `Invoice Date`>=%s ",prepare_mysql($this->data['Product Code']),prepare_mysql(date("Y-m-d",strtotime("- 1 year"))));
     //  print "$sql\n\n";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       
       $this->data['Product Same Code 1 Year Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Same Code 1 Year Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Same Code 1 Year Acc Invoiced Amount']=$row['gross']-$row['disc'];

       $this->data['Product Same Code 1 Year Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       $this->data['Product Same Code 1 Year Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Same Code 1 Year Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Same Code 1 Year Acc Quantity Delivered']=$row['delivered'];
     }else{
	 $this->data['Product Same Code 1 Year Acc Invoiced Gross Amount']=0;
	 $this->data['Product Same Code 1 Year Acc Invoiced Discount Amount']=0;
	 $this->data['Product Same Code 1 Year Acc Profit']=0;$this->data['Product Same Code 1 Year Acc Invoiced Amount']=0;
	 $this->data['Product Same Code 1 Year Acc Quantity Ordered']=0;
	 $this->data['Product Same Code 1 Year Acc Quantity Invoiced']=0;
	 $this->data['Product Same Code 1 Year Acc Quantity Delivered']=0;
       }
       
       $sql=sprintf("update `Product Dimension` set `Product Same Code 1 Year Acc Invoiced Gross Amount`=%.2f,`Product Same Code 1 Year Acc Invoiced Discount Amount`=%.2f,`Product Same Code 1 Year Acc Invoiced Amount`=%.2f,`Product Same Code 1 Year Acc Profit`=%.2f, `Product Same Code 1 Year Acc Quantity Ordered`=%s , `Product Same Code 1 Year Acc Quantity Invoiced`=%s,`Product Same Code 1 Year Acc Quantity Delivered`=%s  where `Product Key`=%d "
		    ,$this->data['Product Same Code 1 Year Acc Invoiced Gross Amount']
		    ,$this->data['Product Same Code 1 Year Acc Invoiced Discount Amount']
		    ,$this->data['Product Same Code 1 Year Acc Invoiced Amount']
		    ,$this->data['Product Same Code 1 Year Acc Profit']
		    ,prepare_mysql($this->data['Product Same Code 1 Year Acc Quantity Ordered'])
		    ,prepare_mysql($this->data['Product Same Code 1 Year Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Same Code 1 Year Acc Quantity Delivered'])
		    ,$this->id
		    );
       if(!mysql_query($sql))
	 exit("$sql\ncan not update product sales 1 yr acc same code id\n");


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

 $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF left join  `Product Dimension` PD on (OTF.`Product Key`=PD.`Product Key`) where `Product ID`=%d and `Invoice Date`>=%s ",$this->data['Product ID'],prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));
     //  print "$sql\n\n";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       
       $this->data['Product Same ID 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Same ID 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Same ID 1 Quarter Acc Invoiced Amount']=$row['gross']-$row['disc'];

       $this->data['Product Same ID 1 Quarter Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       $this->data['Product Same ID 1 Quarter Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Same ID 1 Quarter Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Same ID 1 Quarter Acc Quantity Delivered']=$row['delivered'];
     }else{
	 $this->data['Product Same ID 1 Quarter Acc Invoiced Gross Amount']=0;
	 $this->data['Product Same ID 1 Quarter Acc Invoiced Discount Amount']=0;
	 $this->data['Product Same ID 1 Quarter Acc Profit']=0;$this->data['Product Same ID 1 Quarter Acc Invoiced Amount']=0;
	 $this->data['Product Same ID 1 Quarter Acc Quantity Ordered']=0;
	 $this->data['Product Same ID 1 Quarter Acc Quantity Invoiced']=0;
	 $this->data['Product Same ID 1 Quarter Acc Quantity Delivered']=0;
       }
       
       $sql=sprintf("update `Product Dimension` set `Product Same ID 1 Quarter Acc Invoiced Gross Amount`=%.2f,`Product Same ID 1 Quarter Acc Invoiced Discount Amount`=%.2f,`Product Same ID 1 Quarter Acc Invoiced Amount`=%.2f,`Product Same ID 1 Quarter Acc Profit`=%.2f, `Product Same ID 1 Quarter Acc Quantity Ordered`=%s , `Product Same ID 1 Quarter Acc Quantity Invoiced`=%s,`Product Same ID 1 Quarter Acc Quantity Delivered`=%s  where `Product Key`=%d "
		    ,$this->data['Product Same ID 1 Quarter Acc Invoiced Gross Amount']
		    ,$this->data['Product Same ID 1 Quarter Acc Invoiced Discount Amount']
		    ,$this->data['Product Same ID 1 Quarter Acc Invoiced Amount']
		    ,$this->data['Product Same ID 1 Quarter Acc Profit']
		    ,prepare_mysql($this->data['Product Same ID 1 Quarter Acc Quantity Ordered'])
		    ,prepare_mysql($this->data['Product Same ID 1 Quarter Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Same ID 1 Quarter Acc Quantity Delivered'])
		    ,$this->id
		    );
       if(!mysql_query($sql))
	 exit("$sql\ncan not update product sales 1 yr acc same id\n");




       $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact` OTF left join  `Product Dimension` PD on (OTF.`Product Key`=PD.`Product Key`) where `Product Code`=%s and `Invoice Date`>=%s ",prepare_mysql($this->data['Product Code']),prepare_mysql(date("Y-m-d",strtotime("- 3 month"))));
       // print "$sql\n\n";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       
       $this->data['Product Same Code 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Same Code 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Same Code 1 Quarter Acc Invoiced Amount']=$row['gross']-$row['disc'];

       $this->data['Product Same Code 1 Quarter Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       $this->data['Product Same Code 1 Quarter Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Same Code 1 Quarter Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Same Code 1 Quarter Acc Quantity Delivered']=$row['delivered'];
     }else{
	 $this->data['Product Same Code 1 Quarter Acc Invoiced Gross Amount']=0;
	 $this->data['Product Same Code 1 Quarter Acc Invoiced Discount Amount']=0;
	 $this->data['Product Same Code 1 Quarter Acc Profit']=0;$this->data['Product Same Code 1 Quarter Acc Invoiced Amount']=0;
	 $this->data['Product Same Code 1 Quarter Acc Quantity Ordered']=0;
	 $this->data['Product Same Code 1 Quarter Acc Quantity Invoiced']=0;
	 $this->data['Product Same Code 1 Quarter Acc Quantity Delivered']=0;
       }
       
       $sql=sprintf("update `Product Dimension` set `Product Same Code 1 Quarter Acc Invoiced Gross Amount`=%.2f,`Product Same Code 1 Quarter Acc Invoiced Discount Amount`=%.2f,`Product Same Code 1 Quarter Acc Invoiced Amount`=%.2f,`Product Same Code 1 Quarter Acc Profit`=%.2f, `Product Same Code 1 Quarter Acc Quantity Ordered`=%s , `Product Same Code 1 Quarter Acc Quantity Invoiced`=%s,`Product Same Code 1 Quarter Acc Quantity Delivered`=%s  where `Product Key`=%d "
		    ,$this->data['Product Same Code 1 Quarter Acc Invoiced Gross Amount']
		    ,$this->data['Product Same Code 1 Quarter Acc Invoiced Discount Amount']
		    ,$this->data['Product Same Code 1 Quarter Acc Invoiced Amount']
		    ,$this->data['Product Same Code 1 Quarter Acc Profit']
		    ,prepare_mysql($this->data['Product Same Code 1 Quarter Acc Quantity Ordered'])
		    ,prepare_mysql($this->data['Product Same Code 1 Quarter Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Same Code 1 Quarter Acc Quantity Delivered'])
		    ,$this->id
		    );
       if(!mysql_query($sql))
	 exit("$sql\ncan not update product sales 1 yr acc same code id\n");

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

 $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF left join  `Product Dimension` PD on (OTF.`Product Key`=PD.`Product Key`) where `Product ID`=%d and `Invoice Date`>=%s ",$this->data['Product ID'],prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));
     //  print "$sql\n\n";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       
       $this->data['Product Same ID 1 Month Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Same ID 1 Month Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Same ID 1 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];

       $this->data['Product Same ID 1 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       $this->data['Product Same ID 1 Month Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Same ID 1 Month Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Same ID 1 Month Acc Quantity Delivered']=$row['delivered'];
     }else{
	 $this->data['Product Same ID 1 Month Acc Invoiced Gross Amount']=0;
	 $this->data['Product Same ID 1 Month Acc Invoiced Discount Amount']=0;
	 $this->data['Product Same ID 1 Month Acc Profit']=0;$this->data['Product Same ID 1 Month Acc Invoiced Amount']=0;
	 $this->data['Product Same ID 1 Month Acc Quantity Ordered']=0;
	 $this->data['Product Same ID 1 Month Acc Quantity Invoiced']=0;
	 $this->data['Product Same ID 1 Month Acc Quantity Delivered']=0;
       }
       
       $sql=sprintf("update `Product Dimension` set `Product Same ID 1 Month Acc Invoiced Gross Amount`=%.2f,`Product Same ID 1 Month Acc Invoiced Discount Amount`=%.2f,`Product Same ID 1 Month Acc Invoiced Amount`=%.2f,`Product Same ID 1 Month Acc Profit`=%.2f, `Product Same ID 1 Month Acc Quantity Ordered`=%s , `Product Same ID 1 Month Acc Quantity Invoiced`=%s,`Product Same ID 1 Month Acc Quantity Delivered`=%s  where `Product Key`=%d "
		    ,$this->data['Product Same ID 1 Month Acc Invoiced Gross Amount']
		    ,$this->data['Product Same ID 1 Month Acc Invoiced Discount Amount']
		    ,$this->data['Product Same ID 1 Month Acc Invoiced Amount']
		    ,$this->data['Product Same ID 1 Month Acc Profit']
		    ,prepare_mysql($this->data['Product Same ID 1 Month Acc Quantity Ordered'])
		    ,prepare_mysql($this->data['Product Same ID 1 Month Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Same ID 1 Month Acc Quantity Delivered'])
		    ,$this->id
		    );
       if(!mysql_query($sql))
	 exit("$sql\ncan not update product sales 1 yr acc same id\n");




       $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF left join  `Product Dimension` PD on (OTF.`Product Key`=PD.`Product Key`) where `Product Code`=%s and `Invoice Date`>=%s ",prepare_mysql($this->data['Product Code']),prepare_mysql(date("Y-m-d",strtotime("- 1 month"))));
     //  print "$sql\n\n";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       
       $this->data['Product Same Code 1 Month Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Same Code 1 Month Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Same Code 1 Month Acc Invoiced Amount']=$row['gross']-$row['disc'];

       $this->data['Product Same Code 1 Month Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       $this->data['Product Same Code 1 Month Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Same Code 1 Month Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Same Code 1 Month Acc Quantity Delivered']=$row['delivered'];
     }else{
	 $this->data['Product Same Code 1 Month Acc Invoiced Gross Amount']=0;
	 $this->data['Product Same Code 1 Month Acc Invoiced Discount Amount']=0;
	 $this->data['Product Same Code 1 Month Acc Profit']=0;$this->data['Product Same Code 1 Month Acc Invoiced Amount']=0;
	 $this->data['Product Same Code 1 Month Acc Quantity Ordered']=0;
	 $this->data['Product Same Code 1 Month Acc Quantity Invoiced']=0;
	 $this->data['Product Same Code 1 Month Acc Quantity Delivered']=0;
       }
       
       $sql=sprintf("update `Product Dimension` set `Product Same Code 1 Month Acc Invoiced Gross Amount`=%.2f,`Product Same Code 1 Month Acc Invoiced Discount Amount`=%.2f,`Product Same Code 1 Month Acc Invoiced Amount`=%.2f,`Product Same Code 1 Month Acc Profit`=%.2f, `Product Same Code 1 Month Acc Quantity Ordered`=%s , `Product Same Code 1 Month Acc Quantity Invoiced`=%s,`Product Same Code 1 Month Acc Quantity Delivered`=%s  where `Product Key`=%d "
		    ,$this->data['Product Same Code 1 Month Acc Invoiced Gross Amount']
		    ,$this->data['Product Same Code 1 Month Acc Invoiced Discount Amount']
		    ,$this->data['Product Same Code 1 Month Acc Invoiced Amount']
		    ,$this->data['Product Same Code 1 Month Acc Profit']
		    ,prepare_mysql($this->data['Product Same Code 1 Month Acc Quantity Ordered'])
		    ,prepare_mysql($this->data['Product Same Code 1 Month Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Same Code 1 Month Acc Quantity Delivered'])
		    ,$this->id
		    );
       if(!mysql_query($sql))
	 exit("$sql\ncan not update product sales 1 yr acc same code id\n");


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

 $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF left join  `Product Dimension` PD on (OTF.`Product Key`=PD.`Product Key`) where `Product ID`=%d and `Invoice Date`>=%s ",$this->data['Product ID'],prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));
     //  print "$sql\n\n";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       
       $this->data['Product Same ID 1 Week Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Same ID 1 Week Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Same ID 1 Week Acc Invoiced Amount']=$row['gross']-$row['disc'];

       $this->data['Product Same ID 1 Week Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       $this->data['Product Same ID 1 Week Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Same ID 1 Week Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Same ID 1 Week Acc Quantity Delivered']=$row['delivered'];
     }else{
	 $this->data['Product Same ID 1 Week Acc Invoiced Gross Amount']=0;
	 $this->data['Product Same ID 1 Week Acc Invoiced Discount Amount']=0;
	 $this->data['Product Same ID 1 Week Acc Profit']=0;$this->data['Product Same ID 1 Week Acc Invoiced Amount']=0;
	 $this->data['Product Same ID 1 Week Acc Quantity Ordered']=0;
	 $this->data['Product Same ID 1 Week Acc Quantity Invoiced']=0;
	 $this->data['Product Same ID 1 Week Acc Quantity Delivered']=0;
       }
       
       $sql=sprintf("update `Product Dimension` set `Product Same ID 1 Week Acc Invoiced Gross Amount`=%.2f,`Product Same ID 1 Week Acc Invoiced Discount Amount`=%.2f,`Product Same ID 1 Week Acc Invoiced Amount`=%.2f,`Product Same ID 1 Week Acc Profit`=%.2f, `Product Same ID 1 Week Acc Quantity Ordered`=%s , `Product Same ID 1 Week Acc Quantity Invoiced`=%s,`Product Same ID 1 Week Acc Quantity Delivered`=%s  where `Product Key`=%d "
		    ,$this->data['Product Same ID 1 Week Acc Invoiced Gross Amount']
		    ,$this->data['Product Same ID 1 Week Acc Invoiced Discount Amount']
		    ,$this->data['Product Same ID 1 Week Acc Invoiced Amount']
		    ,$this->data['Product Same ID 1 Week Acc Profit']
		    ,prepare_mysql($this->data['Product Same ID 1 Week Acc Quantity Ordered'])
		    ,prepare_mysql($this->data['Product Same ID 1 Week Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Same ID 1 Week Acc Quantity Delivered'])
		    ,$this->id
		    );
       if(!mysql_query($sql))
	 exit("$sql\ncan not update product sales 1 yr acc same id\n");


       $sql=sprintf("select sum(`Cost Supplier`) as cost_sup,sum(`Invoice Transaction Gross Amount`) as gross ,sum(`Invoice Transaction Total Discount Amount`)as disc ,sum(`Shipped Quantity`) as delivered,sum(`Order Quantity`) as ordered,sum(`Invoice Quantity`) as invoiced  from `Order Transaction Fact`  OTF left join  `Product Dimension` PD on (OTF.`Product Key`=PD.`Product Key`) where `Product Code`=%s and `Invoice Date`>=%s ",prepare_mysql($this->data['Product Code']),prepare_mysql(date("Y-m-d",strtotime("- 1 week"))));
     //  print "$sql\n\n";
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)   ){
       
       $this->data['Product Same Code 1 Week Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Same Code 1 Week Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Same Code 1 Week Acc Invoiced Amount']=$row['gross']-$row['disc'];

       $this->data['Product Same Code 1 Week Acc Profit']=$row['gross']-$row['disc']-$row['cost_sup'];
       $this->data['Product Same Code 1 Week Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Same Code 1 Week Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Same Code 1 Week Acc Quantity Delivered']=$row['delivered'];
     }else{

	 $this->data['Product Same Code 1 Week Acc Invoiced Gross Amount']=0;
	 $this->data['Product Same Code 1 Week Acc Invoiced Discount Amount']=0;
	 $this->data['Product Same Code 1 Week Acc Profit']=0;$this->data['Product Same Code 1 Week Acc Invoiced Amount']=0;
	 $this->data['Product Same Code 1 Week Acc Quantity Ordered']=0;
	 $this->data['Product Same Code 1 Week Acc Quantity Invoiced']=0;
	 $this->data['Product Same Code 1 Week Acc Quantity Delivered']=0;
       }
       
       $sql=sprintf("update `Product Dimension` set `Product Same Code 1 Week Acc Invoiced Gross Amount`=%.2f,`Product Same Code 1 Week Acc Invoiced Discount Amount`=%.2f,`Product Same Code 1 Week Acc Invoiced Amount`=%.2f,`Product Same Code 1 Week Acc Profit`=%.2f, `Product Same Code 1 Week Acc Quantity Ordered`=%s , `Product Same Code 1 Week Acc Quantity Invoiced`=%s,`Product Same Code 1 Week Acc Quantity Delivered`=%s  where `Product Key`=%d "
		    ,$this->data['Product Same Code 1 Week Acc Invoiced Gross Amount']
		    ,$this->data['Product Same Code 1 Week Acc Invoiced Discount Amount']
		    ,$this->data['Product Same Code 1 Week Acc Invoiced Amount']
		    ,$this->data['Product Same Code 1 Week Acc Profit']
		    ,prepare_mysql($this->data['Product Same Code 1 Week Acc Quantity Ordered'])
		    ,prepare_mysql($this->data['Product Same Code 1 Week Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Same Code 1 Week Acc Quantity Delivered'])
		    ,$this->id
		    );
       if(!mysql_query($sql))
	 exit("$sql\ncan not update product sales 1 yr acc same code id\n");

     break;
   case('images'):
     
     $sql=sprintf("select ID.`Image Key`,`Image Caption`,`Image URL`,`Image Filename`,`Image Type`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Product Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Product Key`=%d",$this->id);

     $res=mysql_query($sql);
     $this->images_small=array();
     $this->images_thumb=array();
     $this->images_original=array();


     while($row=mysql_fetch_array($res)){
       if($row['Image Type']=='Small')
	 $this->images_small[$row['Image Key']]=$row;
       elseif($row['Image Type']=='Thumb')
	 $this->images_thumb[$row['Image Key']]=$row;
       elseif($row['Image Type']=='Original')
	 $this->images_original[$row['Image Key']]=$row;

     }
       

     break;



case('images_slideshow'):
       $sql=sprintf("select `Is Principal`,ID.`Image Key`,`Image Caption`,`Image URL`,`Image Filename`,`Image Type`,`Image File Size`,`Image File Checksum`,`Image Width`,`Image Height`,`Image File Format` from `Product Image Bridge` PIB left join `Image Dimension` ID on (PIB.`Image Key`=ID.`Image Key`) where `Product Key`=%d",$this->id);
       //       print $sql;
     $res=mysql_query($sql);
     $this->images_slideshow=array();


     while($row=mysql_fetch_array($res)){
       if($row['Image Type']=='Original')
	 if($row['Image Height']!=0)
	   $ratio=$row['Image Width']/$row['Image Height'];
	 else
	   $ratio=1;
       $this->images_slideshow[]=array('url'=>$row['Image URL'],'filename'=>$row['Image Filename'],'ratio'=>$ratio,'caption'=>$row['Image Caption'],'is_principal'=>$row['Is Principal'],'id'=>$row['Image Key']);

     }
       


       

     break;

   }
   

 }

/*
    Function: load_original_image
    Carga diferentes tipos de imagenes, Crea y actualiza datos de las tablas Product Image Bridge, Product Image, Image Dimension
*/
// JFA 


 function load_original_image($file,$args=''){
   global $tmp_images_dir;
   $principal='No';
   if(preg_match('/principal/i',$args))
     $principal='Yes';
   if(!$this->images)
     $this->load('images');
   
   $checksum=md5_file($file);
   $same_as_other=false;

   //print_r($this->images);
   
   foreach($this->images_original as $_key=>$_value){
     if($_value['Image File Checksum']==$checksum){
       $same_as_other=true;
       $same_as=$_value['Image Filename'];
       return;
     }
     
   }

   if($same_as_other){
     //     $res[$key]['msg']=_('Image already uploaded')." (".$same_as.")";
     //$res[$key]['ok']=false;
     unlink($file);
     //     exit;
     //   continue;
     
   }

   $code=$this->get('Product Code');
   $target_path = $tmp_images_dir;
   //print filesize($file)."-----Z\n";

   $im = @imagecreatefromjpeg($file);
   
   // print "-----------------";
   if ($im) {  
     
     $format='jpg';
     //print $tmp_images_dir.strtolower($this->data['Product Family Code']);
     if (!file_exists('../../'.$tmp_images_dir.strtolower($this->data['Product Family Code'])))
       mkdir('../../'.$tmp_images_dir.strtolower($this->data['Product Family Code']), 0700);
     $name=$tmp_images_dir.strtolower($this->data['Product Family Code']).'/'.strtolower('Original_'.$code.'_'.$this->id.'.'.$format);
     
     
     $news_imgfile = addslashes(fread(fopen($file, "r"), filesize($file)));
     
     
     $image_data=array(
		       'Image Width' => imagesx($im),
		       'Image Height' => imagesy($im),
		       'Image File Size'=>$s=filesize($file),
		       'Image File Checksum'=>$checksum,
		       'Image Caption'=>$this->data['Product Name'],
		       'Image Filename'=>$name,
		       'Image URL'=>'',
		       'Image File Format'=>$format,
		       'Image Type'=>'Original'
		       );
     //print_r($image_data);
     imagejpeg($im,'../../'.$name );
     $image_data['Image Data']=$news_imgfile;

   $keys='(';
   $values='values(';
   foreach($image_data as $key=>$value){
     $keys.="`$key`,";
     if(preg_match('/url/i',$key))
       $values.="'".addslashes($value)."',";
     else
       $values.=prepare_mysql($value).",";
   }
   $keys=preg_replace('/,$/',')',$keys);
   $values=preg_replace('/,$/',')',$values);
   $sql=sprintf("insert into `Image Dimension` %s %s",$keys,$values);
   //print $sql;
   if(mysql_query($sql)){
     $image_key=mysql_insert_id();

     if($principal=='Yes'){
        $sql=sprintf("update `Product Image Bridge` set `Is Princial`='No' where `Product Key`=%d",$this->id);
	mysql_query($sql);
     }

     if(count($this->images_original)==0)
       $principal='Yes';

     $sql=sprintf("insert into `Product Image Bridge` values (%d,%d,%s)",$this->id,$image_key,prepare_mysql($principal));
     //print $sql;
     mysql_query($sql);
     $url=sprintf('image.php?id=%d',$image_key);
     
     $sql=sprintf("update `Image Dimension` set `Image URL`=%s  where `Image Key`=%d",prepare_mysql($url),$image_key);
     //print $sql;
     mysql_query($sql);
   }

     
   }
   unlink($file);

 }

/*
    Function: update
    Permite actualizar valores en los registros de la tabla: Product Dimension, evitando duplicar valores.
*/
// JFA

 function update($key,$a1=false,$a2=false){
   $this->updated=false;
   $this->msg="Nothing to change $key ";
   global $myconf;
   switch($key){
      case('web_state'):
     
     if(
	$a1==_('Out of Stock') 
	or $a1==_('Auto')
	or $a1==_('Offline')
	or $a1==_('Hide')
	or $a1==_('Sale')
	){


        switch($a1){
	case(_('Out of Stock')):
	  $web_state='Online Force Out of Stock';
	  break;
	case(_('Auto')):
	  $web_state='Online Auto';
	  break;
	case(_('Offline')):
	  $web_state=_('Offline');
	  break;
	case(_('Hide')):
	  $web_state='Online Force Hide';	
	  break;
	case(_('Sale')):
	  $web_state='Online Force For Sale';	
	  break;
	  
	}
	
       $sql=sprintf("update `Product Dimension` set `Product Web State`=%s  where  `Product Key`=%d "
		    ,prepare_mysql($web_state)
		    ,$this->id
		    );
       if($this->DBquery($sql)){
	 $this->msg=_('Product Web State updated');
	 $this->updated=true;
	 
     $this->newvalue=$a1;
     return;
       }else{
       $this->msg=_("Error: Product web state could not be updated ");
       $this->updated=false;
       return;
       }
     }else
       $this->msg=_("Error: wrong value")." [Web State] ($a1)";
       $this->updated=false;
   break;

   case('sales_state'):
     
     if(
	$a1==_('For Sale') 
	or $a1==_('Discontinue')
	or $a1==_('Not For Sale')
	){


        switch($a1){
	case(_('For Sale')):
	  $web_state='For Sale';
	  break;
	case(_('Discontinue')):
	  $web_state='Discontinued';
	  break;
	case(_('Not For Sale')):
	  $web_state=_('Not for Sale');
	  break;
	}
	
       $sql=sprintf("update `Product Dimension` set `Product Sales State`=%s  where  `Product Key`=%d "
		    ,prepare_mysql($web_state)
		    ,$this->id
		    );
       if(mysql_query($sql)){
	 $this->msg=_('Product Sales State updated');
	 $this->updated=true;
	 
     $this->newvalue=$a1;
     return;
       }else{
       $this->msg=_("Error: Product sales state could not be updated ");
       $this->updated=false;
       return;
       }
     }else
       $this->msg=_("Error: wrong value")." [Sales State] ($a1)";
       $this->updated=false;
   break;
   case('processing'):

    if( $this->data['Product Record Type']=='Historic'  ){
	 $this->msg=_("Error: You can edit historic records");
	 $this->updated=false;
	 return;
   }

   if( $a1!=_('Editing') and $a1!=_('Live')  ){
	 $this->msg=_("Error: Wrong values ($a1)");
	 $this->updated=false;

	 return;


   }

   if( $a1==_('Editing')  ){
     //changing to editing mode
     if(  $this->data['Product Record Type']=='In Process'  or  $this->data['Product Record Type']=='New'){
       $this->updated=true;
       $this->newvalue=_('Editing');
       return;
     }
     
      $sql=sprintf("update `Product Dimension` set `Product Record Type`=%s  ,`Product Editing Price`=%f,`Product Editing RRP`=%s,`Product Editing Name`=%s,`Product Editing Special Characteristic`=%s ,`Product Editing Family Special Characteristic`=%s,`Product Editing Units Per Case`=%f ,`Product Editing Unit Type`=%s  where `Product Key`=%d "
		   ,prepare_mysql('In Process')
		    ,$this->data['Product Price']
		   ,($this->data['Product RRP']==''?'NULL':$this->data['Product RRP'])
		   ,prepare_mysql($this->data['Product Name'])
		   ,prepare_mysql($this->data['Product Special Characteristic'])
		   ,prepare_mysql($this->data['Product Family Special Characteristic'])
		    ,$this->data['Product Units Per Case']
		   ,prepare_mysql($this->data['Product Unit Type'])
		  ,$this->id
		  );
     if(mysql_query($sql)){
       $this->msg=_('Product Record Type updated');
       $this->updated=true;
       
       $this->newvalue=_('Editing');
       return;
     }else{
       $this->msg=_("Error: Product record type could not be updated"." $sql");
       $this->updated=false;
       return;
     }

   }else{
     // Change from editing to normal
     if( $this->data['Product Record Type']=='Normal' ){
       $this->updated=true;
       $this->newvalue=_('Live');
       return;
     }


     if($this->data['Product Record Type']=='New' ){

       $sql=sprintf("update `Product Dimension` set `Product Record Type`=%s  where `Product Key`=%d "
		    ,prepare_mysql('Normal')
		    ,$this->id
		    );
       if(mysql_query($sql)){
	 $this->msg=_('Product Record Type updated');
	 $this->updated=true;
	 
	 $this->newvalue=_('Live');
	 return;
     }else{
       $this->msg=_("Error: Product record state could not be updated");
       $this->updated=false;
       return;
     }

     }


     if($this->data['Product Editing Price']!=$this->data['Product Price'] or $this->data['Product Editing Units Per Case']!=$this->data['Product Units Per Case']  or $this->data['Product Editing Unit Type']!=$this->data['Product Unit Type'] ){
       // Have to create new ID for the product
       $this->create_new_id();
       if(!$this->new){
	  $this->msg=_("Error: Product record state could not be updated").' (no new)';
	 $this->updated=false;
       }
       
       $sql=sprintf("update `Product Dimension` set `Product Record Type`=%s, `Product Most Recent`='No' where `Product Key`=%d "
		    ,prepare_mysql('Historic')
		    ,$this->id
		    );
       
       if(mysql_query($sql)){
	 $this->msg=_('Product Record Type updated');
	 $this->updated=true;
	 $this->newvalue=_('Live');
       }else{
	  $this->msg=_("Error: Product record state could not be updated");
	  $this->updated=false;
       }
       return;



     }else{
       // No change in procce, or unis no necesiti of make a new product with different ID

       $sql=sprintf("update `Product Dimension` set `Product Record Type`=%s, `Product RRP`=%s,`Product Name`=%s,`Product Special Characteristic`=%s ,`Product Family Special Characteristic`=%s"
		    ,prepare_mysql('Normal')
		    ,($this->data['Product Editing RRP']==''?'NULL':$this->data['Product Editing RRP'])
		    ,prepare_mysql($this->data['Product Editing Name'])
		    ,prepare_mysql($this->data['Product Editing Special Characteristic'])
		    ,prepare_mysql($this->data['Product Editing Family Special Characteristic'])

		    ,$this->id
		    );
       if(mysql_query($sql)){
	 $this->msg=_('Product Record Type updated');
	 $this->updated=true;
	 $this->newvalue=_('Live');
       }else{
	 $this->msg=_("Error: Product record state could not be updated");
	 $this->updated=false;
       }
       return;
       
     }


   }



   break;
   case('Product Price'):
   case('Product Price Per Unit'):
   case('Product Margin'):

     
     
     
     if($key=='Product Margin'){
       if(!is_numeric($this->data['Product Cost'])){
	 $this->msg=_("Error: The product cost is unknown");
	 $this->updated=false;return;
       }
	 

       $margin=floatval(ereg_replace("[^-0-9\.]","",$a1));
       if(!is_numeric($margin)){
	  $this->msg=_("Error: Product margin should be a numeric value");
	  $this->updated=false;return;
       }elseif($margin==-100){
	 $this->msg=_("Error: Product margin can not have this value");
	  $this->updated=false;
	  return;
       }
       $amount=100*$this->data['Product Cost']/($margin+100);



     }else{
       list($currency,$amount)=parse_money($a1,$this->data['Product Currency']);
       
       if(!is_numeric($amount)){
	 $this->msg=_("Error: Product price should be a numeric value");
       $this->updated=false;
       }
       
       
   
     
     
     if($this->data['Product Currency']!=$currency){
       $amount=$amount*currency_conversion($currency,$this->data['Product Currency']);
     }
     
     }


      if($this->data['Product Record Type']=='In process'){

	if($key=='Product Price Per Unit')
	  $amount=$amount*$this->data['Product Editing Units Per Case'];
	
	
	if($amount==$this->data['Product Editing Price']){
	  $this->updated=true;
	  $this->newvalue=money($amount,$this->data['Product Currency']);
	  return;
	  
	}
	

	
	$sql=sprintf("update `Product Dimension` set `Product Editing Price`=%f where `Product Key`=%d "
		     ,$amount
		     ,$this->id
		     );
	if(mysql_query($sql)){
	  $this->msg=_('Product price updated');
	  $this->updated=true;

	  $this->data['Product Editing Price']=$amount;
	  
	  if($this->data['Product Editing Price']!=0 and is_numeric($this->data['Product Cost']))
	    $margin=number(100*($this->data['Product Editing Price']-$this->data['Product Cost'])/$this->data['Product Editing Price'],1).'%';
	  else
	    $margin=_('ND');

	  $this->newvalue=array(
				 'Product Price'=>money($amount,$this->data['Product Currency']),
				 'Product Price Per Unit'=>money($amount/$this->data['Product Editing Units Per Case'],$this->data['Product Currency']),
				 'Product Margin'=>$margin
				);
	  
	  
	  
	}else{
	  $this->msg=_("Error: Product price could not be updated");
	  
	  $this->updated=false;
	  
	}

         return;

       }else{
	// Live product wse should create new product with different ID
	
	if($key=='Product Price Per Unit')
	  $amount=$amount*$this->data['Product Units Per Case'];
	
	
	if($amount==$this->data['Product Price']){
	  $this->updated=true;
	  $this->newvalue=money($amount,$this->data['Product Currency']);
	  return;
	  
	}


	$new_id=false;
	$most_recent=true;
	$this->create_sibling(array('Product Price'=>$amount),$new_id,$most_recent);
	if(!$this->new){
	  $this->msg=_("Error: Product record state could not be updated").' (no new)';
	  $this->updated=false;
	}
	
	$sql=sprintf("update `Product Dimension` set `Product Record Type`=%s, `Product Most Recent`='No' where `Product Key`=%d "
		     ,prepare_mysql('Historic')
		     ,$this->id
		     );
	
	if(mysql_query($sql)){
	  $this->msg=_('Product Record Type updated');
	  $this->updated=true;
	  $this->newvalue=_('Live');
	}else{
	  $this->msg=_("Error: Product record state could not be updated");
	  $this->updated=false;
	}
	return;

       }


    break;
   case('rrp'):
   case('unit_rrp'):
     global $myconf;
     

     if($a1=='' or preg_match('/^(no|none|na|no for|nada)$/',$a1)){
       $amount='NULL';
     }else{
       list($currency,$amount)=parse_money($a1,$this->data['Product Currency']);
     
       if(!is_numeric($amount)){
	 $this->msg=_("Error: Product RRP should be a numeric value");
	 $this->updated=false;
       }
       
       
       if($key=='unit_rrp')
	 $amount=$amount*$this->data['Product Units Per Case'];
       
     
       
       //convert to the proper currency;
       if($this->data['Product Currency']!=$currency){
	 // print "$currency ".currency_conversion($currency,$this->data['Product Currency']);
	 $amount=$amount*currency_conversion($currency,$this->data['Product Currency']);
       }
     
     }

     
     if($amount==$this->data['Product RRP']){
       $this->updated=true;
       $this->newvalue=money($amount,$this->data['Product Currency']);
       return;
       
     }

     
     
     $sql=sprintf("update `Product Dimension` set `Product RRP`=%s where `Product Key`=%d "
		  ,$amount
		  ,$this->id
		  );
     if(mysql_query($sql)){
       $this->msg=_('Product RRP updated');
       $this->updated=true;
       
       if($amount=='NULL'){
	 $this->data['Product RRP']='';
	 $customer_margin='';
       }else{
	 $this->data['Product RRP']=$amount;
	 if($this->data['Product RRP']!=0 and is_numeric($this->data['Product RRP']))
	   $customer_margin=_('CM').' '.number(100*($this->data['Product RRP']-$this->data['Product Price'])/$this->data['Product RRP'],1).'%';
	 else
	   $customer_margin='';
       }
       $rrp_notes=$customer_margin;
       
       $this->newvalue=array(
				 'rrp'=>money($amount,$this->data['Product Currency']),
				 'unit_rrp'=>money($amount/$this->data['Product Units Per Case'],$this->data['Product Currency']),
				 'rrp_info'=>$rrp_notes
				);
	  
	  
	  
     }else{
       $this->msg=_("Error: Product price could not be updated");
       $this->updated=false;
     }

    

    break;


   case('code'):
     
     if($this->data['Product Record Type']!='In process'){
       $this->msg='This product can not changed';
       return;
     }

     if($a1==$this->data['Product Code']){
       $this->updated=true;
       $this->newvalue=$a1;
       return;
       
     }

     if($a1==''){
       $this->msg=_('Error: Wrong code (empty)');
       return;
     }
     $sql=sprintf("select count(*) as num from `Product Dimension` where `Product Store Key`=%d and `Product Code`=%s  COLLATE utf8_general_ci "
		,$this->data['Product Store Key']
		,prepare_mysql($a1)
		);
     $res=mysql_query($sql);
     $row=mysql_fetch_array($res);
     if($row['num']>0){
       $this->msg=_("Error: Another product with the same code");
       return;
     }
     
      $sql=sprintf("update `Product Dimension` set `Product Code`=%s where `Product Key`=%d "
		   ,prepare_mysql($a1)
		   ,$this->id
		);
      if(mysql_query($sql)){
	$this->msg=_('Product code updated');
	$this->updated=true;$this->newvalue=$a1;
      }else{
	$this->msg=_("Error: Product code could not be updated");

	$this->updated=false;
	
      }
      break;	
      
   case('name'):

     if($this->data['Product Record Type']=='In Process'){
       if($a1==$this->data['Product Editing Name']){
	 $this->updated=true;
	 $this->newvalue=$a1;
	 return;
       }
     }else{
       if($a1==$this->data['Product Name']){
	 $this->updated=true;
	 $this->newvalue=$a1;
	 return;
       }
     }

     if($a1==''){
       $this->msg=_('Error: Wrong name (empty)');
       return;
     }

     $sql=sprintf("select count(*) as num from `Product Dimension` where `Product Store Key`=%d and  ( `Product Name`=%s  COLLATE utf8_general_ci  or  `Product Editing Name`=%s  COLLATE utf8_general_ci   ) "
		,$this->data['Product Store Key']
		,prepare_mysql($a1)
		);
     $res=mysql_query($sql);
     $row=mysql_fetch_array($res);
     if($row['num']>0){
       $this->msg=_("Error: Another product with the same name");
       return;
     }
     
     if($this->data['Product Record Type']=='In Process')
       $edit_column='Product Editing Name';
     else
       $edit_column='Product Name';
     
     $sql=sprintf("update `Product Dimension` set `%s`=%s where `Product Key`=%d "
		  ,$edit_column
		  ,prepare_mysql($a1)
		  ,$this->id
		  );
     if(mysql_query($sql)){
       $this->msg=_('Product name updated');
       $this->updated=true;$this->newvalue=$a1;
     }else{
       $this->msg=_("Error: Product name could not be updated");
       
       $this->updated=false;
	
      }
      break;	
  case('sdescription'):

    if($this->data['Product Record Type']=='In Process'){
      if($a1==$this->data['Product Editing Special Characteristic']){
	$this->updated=true;
	$this->newvalue=$a1;
	return;
      }
      }else{
      
      if($a1==$this->data['Product Special Characteristic']){
	$this->updated=true;
	$this->newvalue=$a1;
	return;
      }
    }

     if($a1==''){
       $this->msg=_('Error: Wrong Product Special Characteristic (empty)');
       return;
     }



     if($this->data['Product Record Type']=='In Process')
       $sql=sprintf("select count(*) as num from `Product Dimension` where `Product Family Key`=%d and ( (`Product Editing Special Characteristic`=%s  COLLATE utf8_general_ci  and `Product Editing Family Special Characteristic`=%s) or (`Product Special Characteristic`=%s  COLLATE utf8_general_ci  and `Product Family Special Characteristic`=%s)  )  and `Product Key`!=%d"
		    ,$this->data['Product Family Key']
		    ,prepare_mysql($a1)
		    ,prepare_mysql($this->data['Product Editing Special Characteristic'])
		    ,prepare_mysql($a1)
		    ,prepare_mysql($this->data['Product Editing Special Characteristic'])
		    ,$this->id
		);
     else
          $sql=sprintf("select count(*) as num from `Product Dimension` where `Product Family Key`=%d and ( (`Product Editing Special Characteristic`=%s  COLLATE utf8_general_ci  and `Product Editing Family Special Characteristic`=%s) or (`Product Special Characteristic`=%s  COLLATE utf8_general_ci  and `Product Family Special Characteristic`=%s)  )  and `Product Key`!=%d"
		    ,$this->data['Product Family Key']
		    ,prepare_mysql($a1)
		    ,prepare_mysql($this->data['Product Special Characteristic'])
		    ,prepare_mysql($a1)
		    ,prepare_mysql($this->data['Product Special Characteristic'])
		    ,$this->id
		);



     $res=mysql_query($sql);
     $row=mysql_fetch_array($res);
     if($row['num']>0){
       $this->msg=_("Error: Another product with the same Product/Family Special Characteristic in this family");
       return;
     }
     if($this->data['Product Record Type']=='In Process')
       $editing_column='Product Editing Special Characteristic';
     else
       $editing_column='Product Special Characteristic';
       $sql=sprintf("update `Product Dimension` set `%s`=%s where `Product Key`=%d "
		    ,$editing_column
		    ,prepare_mysql($a1)
		    ,$this->id
		    );
       if(mysql_query($sql)){
	 $this->msg=_('Product Special Characteristic');
	 $this->updated=true;$this->newvalue=$a1;
       }else{
	 $this->msg=_("Error: Product Special Characteristic could not be updated");
	 
	 $this->updated=false;
	
      }
      break;	
   case('famsdescription'):
     
     if($this->data['Product Record Type']=='In Process'){
      if($a1==$this->data['Product Editing Family Special Characteristic']){
	$this->updated=true;
	$this->newvalue=$a1;
	return;
      }
      }else{
      
      if($a1==$this->data['Product Family Special Characteristic']){
	$this->updated=true;
	$this->newvalue=$a1;
	return;
      }
    }

     if($a1==''){
       $this->msg=_('Error: Wrong Product Family Special Characteristic (empty)');
       return;
     }

   if($this->data['Product Record Type']=='In Process')
       $sql=sprintf("select count(*) as num from `Product Dimension` where `Product Family Key`=%d and ( (`Product Editing Special Characteristic`=%s  COLLATE utf8_general_ci  and `Product Editing Family Special Characteristic`=%s) or (`Product Special Characteristic`=%s  COLLATE utf8_general_ci  and `Product Family Special Characteristic`=%s)  )  and `Product Key`!=%d"
		    ,$this->data['Product Family Key']
		    ,prepare_mysql($a1)
		    ,prepare_mysql($this->data['Product Editing Special Characteristic'])
		    ,prepare_mysql($a1)
		    ,prepare_mysql($this->data['Product Editing Special Characteristic'])
		    ,$this->id
		);
     else
          $sql=sprintf("select count(*) as num from `Product Dimension` where `Product Family Key`=%d and ( (`Product Editing Special Characteristic`=%s  COLLATE utf8_general_ci  and `Product Editing Family Special Characteristic`=%s) or (`Product Special Characteristic`=%s  COLLATE utf8_general_ci  and `Product Family Special Characteristic`=%s)  )  and `Product Key`!=%d"
		       ,$this->data['Product Family Key']
		       ,prepare_mysql($this->data['Product Special Characteristic'])
		       ,prepare_mysql($a1)
		       ,prepare_mysql($this->data['Product Special Characteristic'])
		       ,prepare_mysql($a1)
		       ,$this->id
		);

     $res=mysql_query($sql);
     $row=mysql_fetch_array($res);
     if($row['num']>0){
       $this->msg=_("Error: Another product with the same Product/Family Special Characteristic in this family");
       return;
     }
     
      if($this->data['Product Record Type']=='In Process')
       $editing_column='Product Editing Family Special Characteristic';
     else
       $editing_column='Product Family Special Characteristic';
       $sql=sprintf("update `Product Dimension` set `%s`=%s where `Product Key`=%d "
		    ,$editing_column
		    ,prepare_mysql($a1)
		    ,$this->id
		    );


      if(mysql_query($sql)){
	$this->msg=_('Product Family Special Characteristic');
	$this->updated=true;$this->newvalue=$a1;
      }else{
	$this->msg=_("Error: Product Family Special Characteristic could not be updated");

	$this->updated=false;
	
      }
      break;	
   }


 }

/*
    Function: selfsave
    Actualiza valores de la tabla Product Dimension.
*/
// JFA


 function selfsave(){
   $values='';
   foreach($this->data as $key=>$value){
     if(preg_match('/name|price|rrp|description|special|case|unit|^product code$|file as|store|family|department|state|tariff|package|volume|weight|availa|stock|recent|updated/i',$key))
       $values.="`$key`=".prepare_mysql($value).",";

   }
   //$keys=preg_replace('/,$/',')',$keys);
   $values=preg_replace('/,$/','',$values);
   $sql=sprintf("update `Product Dimension` set %s where `Product Key`=%d",$values,$this->id);
    if(!mysql_query($sql)){
      exit("error can not self save $sql\n");
    }
 }

/*
    Method: syncronize
    Sincroniza actualizaciones en las base de datos.
*/
// JFA

 function syncronize(){

   global $external_dns_host,$external_dns_user,$external_dns_pwd,$default_DB_link;
   $ext_link = mysql_connect($external_dns_host,$external_dns_user,$external_dns_pwd);
   
   $this->get('id',$this->id,false);
   mysql_select_db($external_dns_db, $ex_link);
   $sql="update";
   
   mysql_select_db($external_dns_db, $default_DB_link);

 }

/*
    Method: save_to_db
    Guarda cambios en la base de datos.
*/
// JFA

 function save_to_db($sql){

   mysql_query($sql);
   
 }

/*
    Function: removeaccents
    Remueve el acento de las vocales marcadas.
*/
// JFA

function removeaccents($string)
{
  return strtr($string,"é","e");
} 

/*
    Function: update_same_code_valid_dates
    Update Product Same Code Valid 
*/

function update_same_code_valid_dates($args='')
{
  $sql=sprintf("select * from `Product Dimension` where `Product Code`=%s order by `Product Valid From` limit 1",prepare_mysql($this->data['Product Code']));
  $result2=mysql_query($sql);
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
    $same_code_from=$row2['Product Valid From'];
  }
  $sql=sprintf("select * from `Product Dimension` where `Product Code`=%s order by `Product Valid To` desc",prepare_mysql($this->data['Product Code']));

  $most_recent='Yes';
  $result2=mysql_query($sql);
  //print "$sql\n";
  while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
    if($most_recent=='Yes'){
      $most_recent_key=$row2['Product Key'];
      $same_code_to=$row2['Product Valid To'];
    }
    $sql=sprintf("update `Product Dimension` set  `Product Same Code Valid From`=%s ,`Product Same Code Valid To`=%s   where `Product Key`=%s ",prepare_mysql($same_code_from),prepare_mysql($same_code_to),$this->id);
    //   print "$sql\n\n";
    mysql_query($sql);


    if(!preg_match('/no_most_recent/i',$args)){
        $sql=sprintf("update `Product Dimension` set `Product Same Code Most Recent Key`=%s,`Product Same Code Most Recent`=%s  where `Product Key`=%s "
		     ,$most_recent_key
		     ,prepare_mysql($most_recent)
		     ,$row2['Product Key']);
	mysql_query($sql);
     }


    if($most_recent=='Yes')
      $most_recent='No';
  }
}


/*
    Function: update_same_id_valid_dates
    Update Product Same Product ID Valid 
*/

function update_same_id_valid_dates($args='')
{
  $sql=sprintf("select * from `Product Dimension` where `Product ID`=%s order by `Product Valid From` limit 1",prepare_mysql($this->data['Product ID']));
  
  $result2=mysql_query($sql);
  if($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
    $same_code_from=$row2['Product Valid From'];
  }

  $sql=sprintf("select * from `Product Dimension` where `Product ID`=%d order by `Product Valid To` desc ",$this->data['Product ID']);
  $most_recent='Yes';
  $result2=mysql_query($sql);
  while($row2=mysql_fetch_array($result2, MYSQL_ASSOC)){
    if($most_recent=='Yes'){
      $most_recent_key=$row2['Product Key'];
      $same_code_to=$row2['Product Valid To'];
    }
    
    $sql=sprintf("update `Product Dimension` set  `Product Same ID Valid From`=%s ,`Product Same ID Valid To`=%s  where `Product Key`=%s ",prepare_mysql($same_code_from)
		 ,prepare_mysql($same_code_to)
		 ,$this->id);
    mysql_query($sql);

    if(!preg_match('/no_most_recent/i',$args)){
      $sql=sprintf("update `Product Dimension` set `Product Same ID Most Recent`=%s,`Product Same ID Most Recent Key`=%d where `Product Key`=%s "
		    ,prepare_mysql($most_recent)
		   ,$most_recent_key
		   ,$row2['Product Key']);
      mysql_query($sql);
       
    }
 if($most_recent=='Yes')
      $most_recent='No';


 }
 

}
/*
function: load_currency_data
Set the currency extra data in the $data array 
*/
function load_currency_data(){
    $sql=sprintf('select * from kbase.`Currency Dimension` where `Currency Code`=%s'
        ,prepare_mysql($this->data['Product Currency'])
        );
    $res=mysql_query($sql);
    if($row=mysql_fetch_array($res)){
        $this->data['Currency Symbol']=$row['Currency Symbol'];
        $this->data['Currency Name']=$row['Currency Name'];
    }
    mysql_free_result($res);
}

}
?>
