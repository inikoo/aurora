<?
include_once('Product.php');

class family{

 var $products=false;
 var $id=false;

 function __construct($a1=false,$a2=false) {

    
    if(is_numeric($a1) and !$a2  )
      $this->getdata('id',$a1);
    else if(preg_match('/new|create/',$a1) ){
      $this->msg=$this->create($a2);
    }elseif($a2!='')
       $this->getdata($a1,$a2);
    
 }

 function create($data){
   
   

   $sql=sprintf("insert into `Product Family Dimension` (`Product Family Code`,`Product Family Name`) values (%s,%s)"
		,prepare_mysql($data['code'])
		,prepare_mysql($data['name'])
		);
   
   // print_r($data);
   //print "$sql\n";
 if(mysql_query($sql)){
   $this->id = mysql_insert_id();
   $this->getdata('id',$this->id);
   return array('ok'=>true);
 }else{
   print "Error can not create family\n";exit;
   
 }   


 }
 
 function getdata($tipo,$tag){

   switch($tipo){
   case('id'):
     $sql=sprintf("select *  from `Product Family Dimension` where `Product Family Key`=%d ",$tag);
     break;
   case('code'):
     $sql=sprintf("select *  from `Product Family Dimension` where `Product Family Code`=%s and `Product Family Most Recent`='Yes'",prepare_mysql($tag));
     break;
   }
   //   print "S:$tipo $sql\n";

   $result=mysql_query($sql);
   if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
     $this->id=$this->data['Product Family Key'];

 }




 function load($tipo,$args=false){
   switch($tipo){
   case('products_data'):
   case('products_info'):
     
  $sql=sprintf("select sum(if(`Product Sales State`='Unknown',1,0)) as sale_unknown, sum(if(`Product Sales State`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales State`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales State`='For sale',1,0)) as for_sale,sum(if(`Product Sales State`='In Process',1,0)) as in_process,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal
,sum(if(`Product Availability State`='Low',1,0)) as availability_low
,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus

,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension` where `Product Family Key`=%d",$this->id);
     //  print $sql;
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $sql=sprintf("update `Product Family Dimension` set `Product Family For Sale Products`=%d ,`Product Family Discontinued Products`=%d ,`Product Family Not For Sale Products`=%d ,`Product Family Unknown Sales State Products`=%d, `Product Family Optimal Availability Products`=%d , `Product Family Low Availability Products`=%d ,`Product Family Critical Availability Products`=%d ,`Product Family Out Of Stock Products`=%d,`Product Family Unknown Stock Products`=%d ,`Product Family Surplus Availability Products`=%d where `Product Family Key`=%d  ",
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

     $this->getdata('id',$this->id);

     break;
   case('products'):
     $sql=sprintf("select * from `Product Dimension` where `Product Family Key`=%d ",$this->id);
     //    print $sql;

     $this->products=array();
     $result=mysql_query($sql);
     while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->products[$row['product key']]=$row;
     }
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




   if(array_key_exists($key,$this->data))
     return $this->data[$key];



   switch($key){
   case('Full Order Form'):
     global $site_checkout_address,$site_checkout_id,$site_url;

 if($this->locale=='de_DE'){
   $order_txt='Bestellen';
   $reset_txt='Löschen';
 }elseif($this->locale=='fr_FR'){
   $order_txt='Commander';
   $reset_txt='Annuler';
 }else{
   $order_txt='Order';
   $reset_txt='Reset';
   
 }


 
 $max_code_len=0;
 $max_desc_len=0;
 $info='';
 foreach($this->products as $key => $value){




   $code_len=strlen($value['Product Code']);
   if($code_len>$max_code_len)
   $max_code_len=$code_len;
   $desc_len=strlen($value['Product Special Characteristic']);
   if($desc_len>$max_desc_len)
     $max_desc_len=$desc_len;
 }
 
//  print $max_desc_len;
//  $first=$max_code_len;
 
 

 $style=sprintf('<link rel="stylesheet" type="text/css" href="../order.css" /><link rel="stylesheet" type="text/css" href="order.css" /><style type="text/css">table.order {width:%sem}td.first{width:%fem}table.order {font-size:11px;font-family:arial;}span.price{float:right;margin-right:5px}span.desc{margin-left:5px}span.outofstock{color:red;font-weight:800;float:right;margin-right:5px;}input.qty{width:100%%}td.qty{width:3em}</style>
<style type="text/css">.prod_info{text-align:left;} .prod_info span{magin:0;color:red;font-family:arial;;font-weight:800;font-size:12px}</style>',$max_desc_len,$max_code_len);

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
     foreach($this->products as $key => $value){



       $product=new Product($key);
       $product->locale=$this->locale;

       if($i==1){
	 // print_r($options);
	 $info=$product->get('Price Anonymous Info',$options);

       }

       

       $form.=$product->get('Order List Form',$i);

       $i++;
     }
     $form.=sprintf('<tr id="submit_tr"><td id="submit_td" colspan="3" ><input name="Submit" type="submit" class="text" value="%s"> <input name="Reset" type="reset" class="text"  id="Reset" value="%s"></td></tr></form></table>',$order_txt,$reset_txt);

     return $info.$form;

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