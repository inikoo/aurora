<?
/*
 File: Family.php 

 This file contains the Contact Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyright (c) 2009, Kaktus 
 
 Version 2.0
*/
include_once('Product.php');

class Family{

 var $products=false;
 var $id=false;

 function __construct($a1=false,$a2=false,$a3=false) {

    
    if(is_numeric($a1) and !$a2  )
      $this->getdata('id',$a1,false);
    else if(preg_match('/new|create/',$a1) ){
      $this->create($a2);
    }elseif($a2!='')
       $this->getdata($a1,$a2,$a3);
    
 }

 function create($data){
   $this->new=false;
   
   
   if(isset($data['name'])){
     $data['Product Family Name']=$data['name'];
     unset($data['name']);
   }
   if(isset($data['code'])){
     $data['Product Family Code']=$data['code'];
     unset($data['code']);
   }

   if(!isset($data['Product Family Code'])){
     $this->msg=_("Error: No family code provided");
     return;
   }
   if(!isset($data['Product Family Name'])){
     $this->msg=_("Error: No family name provided");
     return;
  }
   
   if($data['Product Family Code']==''){
     $this->msg=_("Error: Wrong family code");
     return;
   }
   if($data['Product Family Name']==''){
     $this->msg=_("Error: Wrong family name");
     return;
   }

   if(!isset($data['Product Family Main Department Key'])){
     $department=new Department(0);
   }else
     $department=new Department($data['Product Family Main Department Key']);

   if(!$department->id){
     $this->msg=_("Error: Can not find department");
     return;
   }
   $store=new Store($department->data['Product Department Store Key']);
   
   

   $sql=sprintf("select count(*) as num from `Product Family Dimension` where `Product Family Store Key`=%s  and `Product Family Code`=%s COLLATE utf8_general_ci "
		,$store->id
		,prepare_mysql($data['Product Family Code'])
		);
   //   print $sql;
   $res=mysql_query($sql);
   $row=mysql_fetch_array($res);
   if($row['num']>0){
     $this->msg=_("Error: Another family with the same code");
     return;
     
   }




   $data['Product Family Main Department Key']=$department->id;
   $data['Product Family Main Department Code']=$department->get('Product Department Code');
   $data['Product Family Main Department Name']=$department->get('Product Department Name');
   $data['Product Family Store Key']=$store->id;
   $data['Product Family Store Code']=$store->get('Store Code');

   $base_data=array(
		    'Product Family Code'=>'',
		    'Product Family Name'=>'',
		    'Product Family Description'=>'',
		    'Product Family Store Key'=>'',
		    'Product Family Store Code'=>'',
		    'Product Family Main Department Key'=>'',
		    'Product Family Main Department Code'=>'',
		    'Product Family Main Department Name'=>'',
		     );


   foreach($data as $key=>$value){
      if(isset($base_data[$key]))
	$base_data[$key]=_trim($value);
    }
   
   $keys='(';$values='values(';
   foreach($base_data as $key=>$value){
     $keys.="`$key`,";
     if(preg_match('/Product Family Description/',$key))
       $values.="'".addslashes($value)."',";
     else
       $values.=prepare_mysql($value).",";
   }
   $keys=preg_replace('/,$/',')',$keys);
   $values=preg_replace('/,$/',')',$values);
   $sql=sprintf("insert into `Product Family Dimension` %s %s",$keys,$values);
   
   // print_r($data);

   if(mysql_query($sql)){
     $this->id = mysql_insert_id();
     $this->getdata('id',$this->id,false);
     $this->msg=_("Family Added");
     
     $sql=sprintf("insert into `Product Family Department Bridge` values (%d,%d)",$this->id,$department->id);

     mysql_query($sql);
     $department->load('products_info');
     $store->load('products_info');
     $this->new=true;

   }else{
     $this->msg=_("Error can not create the family");

   
   }   


 }
 
 function getdata($tipo,$tag,$tag2){

   switch($tipo){
   case('id'):
     $sql=sprintf("select *  from `Product Family Dimension` where `Product Family Key`=%d ",$tag);
     break;
   case('code'):
     $sql=sprintf("select *  from `Product Family Dimension` where `Product Family Code`=%s and `Product Family Most Recent`='Yes'",prepare_mysql($tag));
   case('code_store'):
     $sql=sprintf("select *  from `Product Family Dimension` where `Product Family Code`=%s and `Product Family Store Key`=%d ",prepare_mysql($tag),$tag2);

     break;
   }

   // print $sql;
   $result=mysql_query($sql);
   if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
     $this->id=$this->data['Product Family Key'];

 }

function update($key,$a1=false,$a2=false){
   $this->updated=false;
   $this->msg='Nothing to change';
   
   switch($key){
   case('code'):

     if($a1==$this->data['Product Family Code']){
       $this->updated=true;
       $this->newvalue=$a1;
       return;
       
     }

     if($a1==''){
       $this->msg=_('Error: Wrong code (empty)');
       return;
     }
     $sql=sprintf("select count(*) as num from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s  COLLATE utf8_general_ci "
		,$this->data['Product Family Store Key']
		,prepare_mysql($a1)
		);
     $res=mysql_query($sql);
     $row=mysql_fetch_array($res);
     if($row['num']>0){
       $this->msg=_("Error: Another family with the same code");
       return;
     }
     
      $sql=sprintf("update `Product Family Dimension` set `Product Family Code`=%s where `Product Family Key`=%d "
		   ,prepare_mysql($a1)
		   ,$this->id
		);
      if(mysql_query($sql)){
	$this->msg=_('Family code updated');
	$this->updated=true;$this->newvalue=$a1;
      }else{
	$this->msg=_("Error: Family code could not be updated");

	$this->updated=false;
	
      }
      break;	
      
   case('name'):

     if($a1==$this->data['Product Family Name']){
       $this->updated=true;
       $this->newvalue=$a1;
       return;
       
     }

     if($a1==''){
       $this->msg=_('Error: Wrong name (empty)');
       return;
     }
     $sql=sprintf("select count(*) as num from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Name`=%s  COLLATE utf8_general_ci"
		,$this->data['Product Family Store Key']
		,prepare_mysql($a1)
		);
     $res=mysql_query($sql);
     $row=mysql_fetch_array($res);
     if($row['num']>0){
       $this->msg=_("Error: Another family with the same name");
       return;
     }
     
      $sql=sprintf("update `Product Family Dimension` set `Product Family Name`=%s where `Product Family Key`=%d "
		   ,prepare_mysql($a1)
		   ,$this->id
		);
      if(mysql_query($sql)){
	$this->msg=_('Family name updated');
	$this->updated=true;$this->newvalue=$a1;
      }else{
	$this->msg=_("Error: Family name could not be updated");

	$this->updated=false;
	
      }
      break;	


   }


 }


 function delete(){
   $this->deleted=false;
   $this->load('products_info');

   if($this->get('Total Products')==0){
     $store=new Store($this->data['Product Family Store Key']);
     $this->load('Department Key List');
     $sql=sprintf("delete from `Product Family Dimension` where `Product Family Key`=%d",$this->id);

     if(mysql_query($sql)){

       $sql=sprintf("delete from `Product Family Department Bridge` where `Product Family Key`=%d",$this->id);
       mysql_query($sql);
       foreach($this->department_keys as $dept_key){

	 $department=new Department($dept_key);
	 $department->load('products_info');
       }
       $store->load('products_info');
       $this->deleted=true;
	  
     }else{

       $this->msg=_('Error: can not delete family');
       return;
     }     

     $this->deleted=true;
   }else{
     $this->msg=_('Family can not be deleted because it has some products');

   }
 }


 function load($tipo,$args=false){
   switch($tipo){
   case('products_data'):
   case('products_info'):
     
  $sql=sprintf("select sum(if(`Product Record Type`='In process',1,0)) as in_process,sum(if(`Product Sales State`='Unknown',1,0)) as sale_unknown, sum(if(`Product Sales State`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales State`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales State`='For sale',1,0)) as for_sale,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal
,sum(if(`Product Availability State`='Low',1,0)) as availability_low
,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus

,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension` where `Product Family Key`=%d",$this->id);
     //  print $sql;
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $sql=sprintf("update `Product Family Dimension` set `Product Family In Process Products`=%d,`Product Family For Sale Products`=%d ,`Product Family Discontinued Products`=%d ,`Product Family Not For Sale Products`=%d ,`Product Family Unknown Sales State Products`=%d, `Product Family Optimal Availability Products`=%d , `Product Family Low Availability Products`=%d ,`Product Family Critical Availability Products`=%d ,`Product Family Out Of Stock Products`=%d,`Product Family Unknown Stock Products`=%d ,`Product Family Surplus Availability Products`=%d where `Product Family Key`=%d  ",
		    $row['in_process'],
		    $row['for_sale'],
		    $row['discontinued'],
		    $row['not_for_sale'],
		    $row['sale_unknown'],
		    $row['availability_optimal'],
		    $row['availability_low'],
		    $row['availability_critical'],
		    $row['availability_outofstock'],
		    $row['availability_unknown'],
		    $row['availability_surplus'],
		    $this->id
	    );
       //  print "$sql\n";exit;
       mysql_query($sql);

    
     }

  $this->getdata('id',$this->id,false);

     break;
   case('Department Key List');
   $this->department_keys=array();
   $sql=sprintf("Select `Product Department Key` from `Product Family Department Bridge` where `Product Family Key`=%d",$this->id);
   $res=mysql_query($sql);
   while($row=mysql_fetch_array($res)){
     $this->department_keys[]=$row['Product Department Key'];
   }
   break;
   case('products'):


     $this->products=array();
     if(!$this->id)
       return;
     $order='`Product Family Special Characteristic` ,`Product Code`';
     if(preg_match('/order by sales/i',$args))
       $order='`Product Family Special Characteristic`,`Product Same Code 1 Year Acc Invoiced Amount`,`Product Code`';
     if(preg_match('/order by name/i',$args))
       $order='`Product Family Special Characteristic`,`Product Special Characteristic`';
     if(preg_match('/order by code/i',$args))
       $order='`Product Code File As`';
     

     //     print $args;
      $limit='';
      if(preg_match('/limit\s+\d*\s*\,*\s*\d*/i',$args,$match)){
	//print $match[0];
	$limit_qty=preg_replace('/[^(\d|\,)]/','',$match[0]);
	$limit='limit '.$limit_qty;
       
      }
      $between='';

      if(preg_match('/between\s+\(.*\)/i',$args,$match)){

	$between_tmp=preg_replace('/.*\(/','',$match[0]);
	$between_tmp=preg_replace('/\).*/','',$between_tmp);

	$between_tmp=preg_split('/,|-/',$between_tmp);

	if(count($between_tmp)==2 and $between_tmp[0]!='' and $between_tmp[1]!='')
	  $between='and `Product Special Characteristic` between '.prepare_mysql($between_tmp[0]).' and '.prepare_mysql($between_tmp[1].'zzzzzz');
       
      }


       $family_key=$this->id;
       $sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d %s order by %s %s",$family_key,$between,$order,$limit);
       // print "$sql\n";
       $this->products=array();
       $result=mysql_query($sql);
       while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	 $this->products[]=$row;
       }
       // print "ca";
       break;
       

   case('products_store'):
     $sql=sprintf("select * from `Product Dimension` where `Product Sales State`='For Sale' and `Product Most Recent`='Yes' and `Product Family Key`=%d and `Product Store Key`=%d",$this->id,$args);
     //  print $sql;
     
     $this->products=array();
     $result=mysql_query($sql);
     while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->products[$row['Product Key']]=$row;
     }
     break;


 //   case('first_date'):
//      $first_date=date('U');
//      $changed=false;
//      $this->load('products');
//      foreach($this->products as $product_id=>$product_data){
//        $product=new Product($product_id);
//        $_date=$product->data['first_date'];
//        //   print "$_date\n";
//        if(is_numeric($_date)){
// 	 // print "hola $product_id   $_date   $first_date  \n";
// 	 if($_date < $first_date){
// 	   $first_date=$_date;
// 	   $changed=true;
// 	 }
//        }
//      }
//      //  print "$first_dat\n";
//      if($changed){
//        $this->data['first_date']=$first_date;
//        if(preg_match('/save/i',$args))
// 	 $this->save($tipo);
//      }

//      break;
   case('sales'):

     $sql="select  sum(`Product Total Invoiced Amount`) as net,sum(`Product Total Invoiced Gross Amount`) as gross,sum(`Product Total Invoiced Discount Amount`) as disc, sum(`Product Total Profit`)as profit ,sum(`Product Total Quantity Delivered`) as delivered,sum(`Product Total Quantity Ordered`) as ordered,sum(`Product Total Quantity Invoiced`) as invoiced  from `Product Dimension` where `Product Family Key`=".$this->id;
     $result=mysql_query($sql);
     
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->data['Product Family Total Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Family Total Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Family Total Invoiced Amount']=$row['net'];
       $this->data['Product Family Total Profit']=$row['profit'];
       $this->data['Product Family Total Quantity Ordered']=$row['ordered'];
       $this->data['Product Family Total Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Family Total Quantity Delivered']=$row['delivered'];
     }else{
       $this->data['Product Family Total Invoiced Gross Amount']=0;
       $this->data['Product Family Total Invoiced Discount Amount']=0;
       $this->data['Product Family Total Invoiced Amount']=0;
       $this->data['Product Family Total Profit']=0;
       $this->data['Product Family Total Quantity Ordered']=0;
       $this->data['Product Family Total Quantity Invoiced']=0;
       $this->data['Product Family Total Quantity Delivered']=0;
     }       
       
     $sql=sprintf("update `Product Family Dimension` set `Product Family Total Invoiced Gross Amount`=%.2f,`Product Family Total Invoiced Discount Amount`=%.2f,`Product Family Total Invoiced Amount`=%.2f,`Product Family Total Profit`=%.2f, `Product Family Total Quantity Ordered`=%f , `Product Family Total Quantity Invoiced`=%f,`Product Family Total Quantity Delivered`=%f  where `Product Family Key`=%d "
		  ,$this->data['Product Family Total Invoiced Gross Amount']
		  ,$this->data['Product Family Total Invoiced Discount Amount']
		  ,$this->data['Product Family Total Invoiced Amount']
		  ,$this->data['Product Family Total Profit']
		  ,$this->data['Product Family Total Quantity Ordered']
		  ,$this->data['Product Family Total Quantity Invoiced']
		  ,$this->data['Product Family Total Quantity Delivered']
		  ,$this->id
		  );
     
     if(!mysql_query($sql))
       exit("$sql\ncan not update fam sales total\n");
     

   $sql="select  sum(`Product 1 Year Acc Invoiced Amount`) as net,sum(`Product 1 Year Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Year Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Year Acc Profit`)as profit ,sum(`Product 1 Year Acc Quantity Delivered`) as delivered,sum(`Product 1 Year Acc Quantity Ordered`) as ordered,sum(`Product 1 Year Acc Quantity Invoiced`) as invoiced  from `Product Dimension` where `Product Family Key`=".$this->id;
     $result=mysql_query($sql);
     
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->data['Product Family 1 Year Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Family 1 Year Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Family 1 Year Acc Invoiced Amount']=$row['net'];
       $this->data['Product Family 1 Year Acc Profit']=$row['profit'];
       $this->data['Product Family 1 Year Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Family 1 Year Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Family 1 Year Acc Quantity Delivered']=$row['delivered'];
     }else{
       $this->data['Product Family 1 Year Acc Invoiced Gross Amount']=0;
       $this->data['Product Family 1 Year Acc Invoiced Discount Amount']=0;
       $this->data['Product Family 1 Year Acc Invoiced Amount']=0;
       $this->data['Product Family 1 Year Acc Profit']=0;
       $this->data['Product Family 1 Year Acc Quantity Ordered']=0;
       $this->data['Product Family 1 Year Acc Quantity Invoiced']=0;
       $this->data['Product Family 1 Year Acc Quantity Delivered']=0;
     }       
       
     $sql=sprintf("update `Product Family Dimension` set `Product Family 1 Year Acc Invoiced Gross Amount`=%.2f,`Product Family 1 Year Acc Invoiced Discount Amount`=%.2f,`Product Family 1 Year Acc Invoiced Amount`=%.2f,`Product Family 1 Year Acc Profit`=%.2f, `Product Family 1 Year Acc Quantity Ordered`=%f , `Product Family 1 Year Acc Quantity Invoiced`=%f,`Product Family 1 Year Acc Quantity Delivered`=%f  where `Product Family Key`=%d "
		  ,$this->data['Product Family 1 Year Acc Invoiced Gross Amount']
		  ,$this->data['Product Family 1 Year Acc Invoiced Discount Amount']
		  ,$this->data['Product Family 1 Year Acc Invoiced Amount']
		  ,$this->data['Product Family 1 Year Acc Profit']
		  ,$this->data['Product Family 1 Year Acc Quantity Ordered']
		  ,$this->data['Product Family 1 Year Acc Quantity Invoiced']
		  ,$this->data['Product Family 1 Year Acc Quantity Delivered']
		  ,$this->id
		  );
     
     if(!mysql_query($sql))
       exit("$sql\ncan not update fam sales 1 year\n");
   

 $sql="select  sum(`Product 1 Quarter Acc Invoiced Amount`) as net,sum(`Product 1 Quarter Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Quarter Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Quarter Acc Profit`)as profit ,sum(`Product 1 Quarter Acc Quantity Delivered`) as delivered,sum(`Product 1 Quarter Acc Quantity Ordered`) as ordered,sum(`Product 1 Quarter Acc Quantity Invoiced`) as invoiced  from `Product Dimension` where `Product Family Key`=".$this->id;
     $result=mysql_query($sql);
     
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->data['Product Family 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Family 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Family 1 Quarter Acc Invoiced Amount']=$row['net'];
       $this->data['Product Family 1 Quarter Acc Profit']=$row['profit'];
       $this->data['Product Family 1 Quarter Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Family 1 Quarter Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Family 1 Quarter Acc Quantity Delivered']=$row['delivered'];
     }else{
       $this->data['Product Family 1 Quarter Acc Invoiced Gross Amount']=0;
       $this->data['Product Family 1 Quarter Acc Invoiced Discount Amount']=0;
       $this->data['Product Family 1 Quarter Acc Invoiced Amount']=0;
       $this->data['Product Family 1 Quarter Acc Profit']=0;
       $this->data['Product Family 1 Quarter Acc Quantity Ordered']=0;
       $this->data['Product Family 1 Quarter Acc Quantity Invoiced']=0;
       $this->data['Product Family 1 Quarter Acc Quantity Delivered']=0;
     }       
       
     $sql=sprintf("update `Product Family Dimension` set `Product Family 1 Quarter Acc Invoiced Gross Amount`=%.2f,`Product Family 1 Quarter Acc Invoiced Discount Amount`=%.2f,`Product Family 1 Quarter Acc Invoiced Amount`=%.2f,`Product Family 1 Quarter Acc Profit`=%.2f, `Product Family 1 Quarter Acc Quantity Ordered`=%f , `Product Family 1 Quarter Acc Quantity Invoiced`=%f,`Product Family 1 Quarter Acc Quantity Delivered`=%f  where `Product Family Key`=%d "
		  ,$this->data['Product Family 1 Quarter Acc Invoiced Gross Amount']
		  ,$this->data['Product Family 1 Quarter Acc Invoiced Discount Amount']
		  ,$this->data['Product Family 1 Quarter Acc Invoiced Amount']
		  ,$this->data['Product Family 1 Quarter Acc Profit']
		  ,$this->data['Product Family 1 Quarter Acc Quantity Ordered']
		  ,$this->data['Product Family 1 Quarter Acc Quantity Invoiced']
		  ,$this->data['Product Family 1 Quarter Acc Quantity Delivered']
		  ,$this->id
		  );
     
     if(!mysql_query($sql))
       exit("$sql\ncan not update fam sales 1 quarter\n");

 $sql="select  sum(`Product 1 Month Acc Invoiced Amount`) as net,sum(`Product 1 Month Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Month Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Month Acc Profit`)as profit ,sum(`Product 1 Month Acc Quantity Delivered`) as delivered,sum(`Product 1 Month Acc Quantity Ordered`) as ordered,sum(`Product 1 Month Acc Quantity Invoiced`) as invoiced  from `Product Dimension` where `Product Family Key`=".$this->id;
     $result=mysql_query($sql);
     
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->data['Product Family 1 Month Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Family 1 Month Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Family 1 Month Acc Invoiced Amount']=$row['net'];
       $this->data['Product Family 1 Month Acc Profit']=$row['profit'];
       $this->data['Product Family 1 Month Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Family 1 Month Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Family 1 Month Acc Quantity Delivered']=$row['delivered'];
     }else{
       $this->data['Product Family 1 Month Acc Invoiced Gross Amount']=0;
       $this->data['Product Family 1 Month Acc Invoiced Discount Amount']=0;
       $this->data['Product Family 1 Month Acc Invoiced Amount']=0;
       $this->data['Product Family 1 Month Acc Profit']=0;
       $this->data['Product Family 1 Month Acc Quantity Ordered']=0;
       $this->data['Product Family 1 Month Acc Quantity Invoiced']=0;
       $this->data['Product Family 1 Month Acc Quantity Delivered']=0;
     }       
       
     $sql=sprintf("update `Product Family Dimension` set `Product Family 1 Month Acc Invoiced Gross Amount`=%.2f,`Product Family 1 Month Acc Invoiced Discount Amount`=%.2f,`Product Family 1 Month Acc Invoiced Amount`=%.2f,`Product Family 1 Month Acc Profit`=%.2f, `Product Family 1 Month Acc Quantity Ordered`=%f , `Product Family 1 Month Acc Quantity Invoiced`=%f,`Product Family 1 Month Acc Quantity Delivered`=%f  where `Product Family Key`=%d "
		  ,$this->data['Product Family 1 Month Acc Invoiced Gross Amount']
		  ,$this->data['Product Family 1 Month Acc Invoiced Discount Amount']
		  ,$this->data['Product Family 1 Month Acc Invoiced Amount']
		  ,$this->data['Product Family 1 Month Acc Profit']
		  ,$this->data['Product Family 1 Month Acc Quantity Ordered']
		  ,$this->data['Product Family 1 Month Acc Quantity Invoiced']
		  ,$this->data['Product Family 1 Month Acc Quantity Delivered']
		  ,$this->id
		  );
     
     if(!mysql_query($sql))
       exit("$sql\ncan not update fam sales 1 month\n");

 $sql="select  sum(`Product 1 Week Acc Invoiced Amount`) as net,sum(`Product 1 Week Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Week Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Week Acc Profit`)as profit ,sum(`Product 1 Week Acc Quantity Delivered`) as delivered,sum(`Product 1 Week Acc Quantity Ordered`) as ordered,sum(`Product 1 Week Acc Quantity Invoiced`) as invoiced  from `Product Dimension` where `Product Family Key`=".$this->id;
     $result=mysql_query($sql);
     
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->data['Product Family 1 Week Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Family 1 Week Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Family 1 Week Acc Invoiced Amount']=$row['net'];
       $this->data['Product Family 1 Week Acc Profit']=$row['profit'];
       $this->data['Product Family 1 Week Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Family 1 Week Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Family 1 Week Acc Quantity Delivered']=$row['delivered'];
     }else{
       $this->data['Product Family 1 Week Acc Invoiced Gross Amount']=0;
       $this->data['Product Family 1 Week Acc Invoiced Discount Amount']=0;
       $this->data['Product Family 1 Week Acc Invoiced Amount']=0;
       $this->data['Product Family 1 Week Acc Profit']=0;
       $this->data['Product Family 1 Week Acc Quantity Ordered']=0;
       $this->data['Product Family 1 Week Acc Quantity Invoiced']=0;
       $this->data['Product Family 1 Week Acc Quantity Delivered']=0;
     }       
       
     $sql=sprintf("update `Product Family Dimension` set `Product Family 1 Week Acc Invoiced Gross Amount`=%.2f,`Product Family 1 Week Acc Invoiced Discount Amount`=%.2f,`Product Family 1 Week Acc Invoiced Amount`=%.2f,`Product Family 1 Week Acc Profit`=%.2f, `Product Family 1 Week Acc Quantity Ordered`=%f , `Product Family 1 Week Acc Quantity Invoiced`=%f,`Product Family 1 Week Acc Quantity Delivered`=%f  where `Product Family Key`=%d "
		  ,$this->data['Product Family 1 Week Acc Invoiced Gross Amount']
		  ,$this->data['Product Family 1 Week Acc Invoiced Discount Amount']
		  ,$this->data['Product Family 1 Week Acc Invoiced Amount']
		  ,$this->data['Product Family 1 Week Acc Profit']
		  ,$this->data['Product Family 1 Week Acc Quantity Ordered']
		  ,$this->data['Product Family 1 Week Acc Quantity Invoiced']
		  ,$this->data['Product Family 1 Week Acc Quantity Delivered']
		  ,$this->id
		  );
     
     if(!mysql_query($sql))
       exit("$sql\ncan not update fam sales 1 week\n");


  break;

   }
 }
     

 function save($tipo){
   switch($tipo){
   case('first_date'):
     
     $sql=sprintf("update product_group set first_date=%s where id=%d",
		  prepare_mysql(
				date("Y-m-d H:i:s",strtotime('@'.$this->data['first_date'])))
		  ,$this->id);
     //     print "$sql;";
     mysql_query($sql);

     break;
   case('sales'):
     $sql=sprintf("select id from sales where tipo='fam' and tipo_id=%d",$this->id);
      $res = $this->db->query($sql); 
      if ($row=$res->fetchRow()) {
	$sales_id=$row['id'];
      }else{
	$sql=sprintf("insert into sales (tipo,tipo_id) values ('fam',%d)",$this->id);
	$this->db->exec($sql);
	$sales_id=$this->db->lastInsertID();
	
      }
      foreach($this->data['sales'] as $key=>$value){
	if(preg_match('/^aw/',$key)){
	  if(is_numeric($value))
	    $sql=sprintf("update sales set %s=%f where id=%d",$key,$value,$sales_id);
	  else
	    $sql=sprintf("update sales set %s=NULL where id=%d",$key,$sales_id);
	  $this->db->exec($sql);

	}
	if(preg_match('/^ts/',$key)){
	  $sql=sprintf("update sales set %s=%.2f where id=%d",$key,$value,$sales_id);
	  // print "$sql\n";
	  $this->db->exec($sql);
	}  
	
      }
     break;
   }
   
 }


 function get($key,$options=false){

   if(!$this->id)
     return '';

   if(array_key_exists($key,$this->data))
     return $this->data[$key];



   switch($key){
   case('Price From Info'):
     $min=99999999;
     $product_id='';
     $changed=false;
     foreach($this->products as $key => $value){
       if($value['Product Price']<$min and $value['Product Price']>0){
	 $min=$value['Product Price'];
	 $product_id=$value['Product Key'];
	 $changed=true;
       }
      }

     if($changed){
       $product=new Product($product_id);
       return '<div class="prod_info">'.$product->get('Price Formated','from').'</div>';
     }else
       return '';


     break;
   case('Full Order Form'):
     global $site_checkout_address,$site_checkout_id,$site_url;
     
     if($this->locale=='de_DE'){
       $order_txt='Bestellen';
       $reset_txt='Löschen';
       $price_from='ab';
       $lenght_factor=1.5;
     }elseif($this->locale=='fr_FR'){
       $order_txt='Commander';
       $reset_txt='Annuler';
       $price_from='à partir de';
       $lenght_factor=1.0;
       
     }else{
       $order_txt='Order';
       $reset_txt='Reset';
       $price_from='Prices from';

       $lenght_factor=1.5;
     }
     
     
     
     $max_code_len=0;
     $max_desc_len=0;
     $info='';

     $min_desc_len=15;
     $min_code_len=8;
     
     foreach($this->products as $key => $value){
   



   $code_len=strlen($value['Product Code']);
   if($code_len>$max_code_len)
   $max_code_len=$code_len;
   $desc_len=strlen($value['Product Special Characteristic']);
   if($desc_len>$max_desc_len)
     $max_desc_len=$desc_len;
 }
  $desc_len=$max_desc_len*1.5*$lenght_factor;
  $code_len=$max_code_len*1.1*$lenght_factor;
  
  if($desc_len<$min_desc_len)
    $desc_len=$min_desc_len;
  if($code_len<$min_code_len)
    $code_len=$min_code_len;





//  print $max_desc_len;
//  $first=$max_code_len;
 
 

 $style=sprintf('<link rel="stylesheet" type="text/css" href="../order.css" /><link rel="stylesheet" type="text/css" href="order.css" /><style type="text/css">table.order {width:%sem}td.first{width:%fem}table.order {font-size:11px;font-family:arial;}span.price{float:right;margin-right:5px}span.desc{margin-left:5px}span.outofstock{color:red;font-weight:800;float:right;margin-right:5px;}input.qty{width:100%%}td.qty{width:3em}</style>
<style type="text/css">.prod_info{text-align:left;} .prod_info span{magin:0;color:red;font-family:arial;;font-weight:800;font-size:12px}</style>',$desc_len,$code_len);

 //$style=sprintf('<style type="text/css"> span.info_price{font-size:20px}</style>');
 // $style='';
 $form=sprintf('%s<table class="Order"><FORM METHOD="POST" ACTION="%s"><INPUT TYPE="HIDDEN" NAME="userid" VALUE="%s"><input type="hidden" name="return" value="%s">'
	       ,$style
	       ,addslashes($site_checkout_address)
	       ,addslashes($site_checkout_id)
	       ,$site_url.$_SERVER['PHP_SELF']
	       );
 
 $form.="\n";


     $i=1;

     $filter=false;
     if(isset($options['filter']))
       $filter=true;

     $until=false;
     if(isset($options['until']) and is_numeric($options['until']))
       $until=true;


       $header='normal';
       if(isset($options['header'])){
	 //	 print $options['header'];
	 switch($options['header']){
	 case 'none':
	   //case 0:
	   case false:
	   case '':
	   $header='nonec';
	   break;
	 case ('subfamilies'):
	 case ('groups'):
	   $header='subfamilies';
	   break;
	 case('price from'):
	 case('prices from'):
	   $header='price from';
	   break;
	 }

       }

       // print $header;
       
     foreach($this->products as $key => $value){

       if($filter and !preg_match('/'.$options['filter'].'/i',$value['Product Name']))
	 continue;
       if($until and $i>$options['until'])
	 break;
       
       $product=new Product($value['Product Key']);
       $product->locale=$this->locale;
       

     


       if($i==1 ){

	 if($header=='normal')
	   $info=$product->get('Price Anonymous Info',$options);
	 elseif($header=='price from')
	   $info=$this->get('Price From Info');
	 else if($header=='subfamilies')
	   $info=$product->get('Price Subfamily Info',$options);
       }else if($header=='subfamilies' and $current_famsdescription!=$product->data['Product Family Special Characteristic']){
	 $options['inside form']=true;
	 $form.=$product->get('Price Subfamily Info',$options);
       }
       
       $current_famsdescription=$product->data['Product Family Special Characteristic'];
       
       $form.=$product->get('Order List Form',array('counter'=>$i,'options'=>$options));

       $i++;
     }
     $form.=sprintf('<tr id="submit_tr"><td id="submit_td" colspan="3" ><input name="Submit" type="submit" class="text" value="%s"> <input name="Reset" type="reset" class="text"  id="Reset" value="%s"></td></tr></form></table>',$order_txt,$reset_txt);

     return $info.$form;

     break;
   case('Total Products'):
     return $this->data['Product Family For Sale Products']+$this->data['Product Family In Process Products']+$this->data['Product Family Not For Sale Products']+$this->data['Product Family Discontinued Products']+$this->data['Product Family Unknown Sales State Products'];
     break;

   case('products'):
     if(!$this->products)
       $this->load('products');
     return $this->products;
     
     break;
   case('weeks'):
      if(is_numeric($this->data['first_date'])){
     	$date1=date('d-m-Y',strtotime('@'.$this->data['first_date']));
	$day1=date('N')-1;
	$date2=date('d-m-Y');
	$days=datediff('d',$date1,$date2);
	$weeks=number_weeks($days,$day1);
      }else
	$weeks=0;
      return $weeks;
   }

 }
 
 function add_product($product_id,$args=false){
   
   $product=New Product($product_id);
   if($product->id){
     $sql=sprintf("update  `Product Dimension` set `Product Family Key`=%d ,`Product Family Code`=%s,`Product Family Name`=%s where `Product Key`=%s    "
		  ,$this->id                
		  ,prepare_mysql($this->get('Product Family Code'))
		  ,prepare_mysql($this->get('Product Family Name'))
		  ,$product->id);
     mysql_query($sql);
     $this->load('products_info');
     // print "$sql\n";
   }
 }

}

?>