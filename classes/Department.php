<?
include_once('Family.php');



class department{
 var $id=false;

 function __construct($a1=false,$a2=false,$a3=false) {
   //    $this->db =MDB2::singleton();
    
    if(is_numeric($a1) and !$a2  and $a1>0 )
      $this->getdata('id',$a1);
    else if( preg_match('/new|create/i',$a1)){
      $this->create($a2);
    }elseif($a2!='')
       $this->getdata($a1,$a2,$a3);
    
 }

 function create($data){


   if(isset($data['name']))
     $data['Product Department Name']=$data['name'];
    if(isset($data['code']))
     $data['Product Department Code']=$data['code'];
    if(isset($data['store_key']))
     $data['Product Department Store Key']=$data['store_key'];


   $this->new=false;
   if(!isset($data['Product Department Code'])){
     $this->msg=_("Error: No department code provided");
     return;
   }

   if($data['Product Department Code']=='' ){
     $this->msg=_("Error: Wrong department code");
     return;
   }

   if(!isset($data['Product Department Name'])){
     $data['Product Department Name']=$data['Product Department Code'];
      $this->msg=_("Warning: No department name");
   }

   if(!isset($data['Product Department Store Key']) or !is_numeric($data['Product Department Store Key']) or $data['Product Department Store Key']<=0 ){
     $data['Product Department Store Key']=1;
     $this->msg=_("Warning: Incorrect Store Key");
     $store=new Store($data['Product Department Store Key']);
   }
   $sql=sprintf("select count(*) as num from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Code`=%s "
		,$data['Product Department Store Key']
		,prepare_mysql($data['Product Department Code'])
		);
   $res=mysql_query($sql);
   $row=mysql_fetch_array($res);
   if($row['num']>0){
     $this->msg=_("Error: Another department with the same code");
     return;
     
   }
   
   $sql=sprintf("select count(*) as num from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Name`=%s "
		,$data['Product Department Store Key']
		,prepare_mysql($data['Product Department Name'])
		);
   $res=mysql_query($sql);
   $row=mysql_fetch_array($res);
   if($row['num']>0){
     $this->msg=_("Warning: Wrong another department with the same name");

     
   }



   $sql=sprintf("insert into `Product Department Dimension` (`Product Department Code`,`Product Department Name`,`Product Department Store Key`) values (%s,%s,%d)"
		,prepare_mysql($data['Product Department Code'])
		,prepare_mysql($data['Product Department Name'])
		,$data['Product Department Store Key']
		);



 if(mysql_query($sql)){
   $this->id = mysql_insert_id();
   $this->msg=_("Department Added");
   $this->getdata('id',$this->id);
   $this->new=true;
   $store=new Store($data['Product Department Store Key']);
   $store->load('product_info');
   return;
 }else{
   $this->msg=_("Error can not create department");

 }

 }
 
 function getdata($tipo,$tag,$tag2=false){
   
   switch($tipo){
   case('id'):
     $sql=sprintf("select * from `Product Department Dimension` where `Product Department Key`=%d ",$tag);
     break;
   case('code'):
     $sql=sprintf("select * from `Product Department Dimension` where `Product Department Code`=%s and `Product Department Most Recent`='Yes'",prepare_mysql($tag));
     break;
    default:
      $sql=sprintf("select * from `Product Department Dimension` where `Product Department Type`='Unknown' ");



   }
   //  print "$sql\n";
   
   $result=mysql_query($sql);
   if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
     $this->id=$this->data['Product Department Key'];
   
   
 }


 function update($key,$a1=false,$a2=false){
   $this->updated=false;
   $this->msg='Nothing to change';
   
   switch($key){
   case('code'):

     if($a1==$this->data['Product Department Code']){
       $this->updated=true;
       $this->newvalue=$a1;
       return;
       
     }

     if($a1==''){
       $this->msg=_('Error: Wrong code (empty)');
       return;
     }
     $sql=sprintf("select count(*) as num from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Code`=%s  COLLATE utf8_general_ci"
		,$this->data['Product Department Store Key']
		,prepare_mysql($a1)
		);
     $res=mysql_query($sql);
     $row=mysql_fetch_array($res);
     if($row['num']>0){
       $this->msg=_("Error: Another department with the same code");
       return;
     }
     
      $sql=sprintf("update `Product Department Dimension` set `Product Department Code`=%s where `Product Department Key`=%d "
		   ,prepare_mysql($a1)
		   ,$this->id
		);
      if(mysql_query($sql)){
	$this->msg=_('Department code updated');
	$this->updated=true;$this->newvalue=$a1;
      }else{
	$this->msg=_("Error: Department code could not be updated");

	$this->updated=false;
	
      }
      break;	
      
   case('name'):

     if($a1==$this->data['Product Department Name']){
       $this->updated=true;
       $this->newvalue=$a1;
       return;
       
     }

     if($a1==''){
       $this->msg=_('Error: Wrong name (empty)');
       return;
     }
     $sql=sprintf("select count(*) as num from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Name`=%s  COLLATE utf8_general_ci"
		,$this->data['Product Department Store Key']
		,prepare_mysql($a1)
		);
     $res=mysql_query($sql);
     $row=mysql_fetch_array($res);
     if($row['num']>0){
       $this->msg=_("Error: Another department with the same name");
       return;
     }
     
      $sql=sprintf("update `Product Department Dimension` set `Product Department Name`=%s where `Product Department Key`=%d "
		   ,prepare_mysql($a1)
		   ,$this->id
		);
      if(mysql_query($sql)){
	$this->msg=_('Department name updated');
	$this->updated=true;$this->newvalue=$a1;
      }else{
	$this->msg=_("Error: Department name could not be updated");

	$this->updated=false;
	
      }
      break;	


   }


 }


 function delete(){
   $this->deleted=false;
   $this->load('products_info');

   if($this->get('Total Products')==0){
     $store=new Store($this->data['Product Department Store Key']);
     $sql=sprintf("delete from `Product Department Dimension` where `Product Department Key`=%d",$this->id);
     if(mysql_query($sql)){

       $this->deleted=true;
	  
     }else{

       $this->msg=_('Error: can not delete department');
       return;
     }     

     $this->deleted=true;
   }else{
     $this->msg=_('Department can not be deleted because it has assosiated some products');

   }
 }



 
 function load($tipo,$args=false){
   switch($tipo){
   case('products_info'):
      $sql=sprintf("select sum(if(`Product Sales State`='Unknown',1,0)) as sale_unknown, sum(if(`Product Sales State`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales State`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales State`='For sale',1,0)) as for_sale,sum(if(`Product Sales State`='In Process',1,0)) as in_process,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension` P left join  `Product Department Bridge` B on (P.`Product Key`=B.`Product Key`) where `Product Department Key`=%d",$this->id);
      // print "$sql\n\n\n";
 $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){

       $sql=sprintf("update `Product Department Dimension` set `Product Department For Sale Products`=%d ,`Product Department Discontinued Products`=%d ,`Product Department Not For Sale Products`=%d ,`Product Department Unknown Sales State Products`=%d, `Product Department Optimal Availability Products`=%d , `Product Department Low Availability Products`=%d ,`Product Department Critical Availability Products`=%d ,`Product Department Out Of Stock Products`=%d,`Product Department Unknown Stock Products`=%d ,`Product Department Surplus Availability Products`=%d where `Product Department Key`=%d  ",
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
  
  $sql=sprintf("select count(*) as num from `Product Family Dimension` PFD  left join `Product Family Department Bridge` as B on (B.`Product Family Key`=PFD.`Product Family Key`) where `Product Department Key`=%d",$this->id);
  //print $sql;
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $this->datas['Product Department Families']=$row['num'];
    $sql=sprintf("update `Product Department Dimension` set `Product Department Families`=%d  where `Product Department Key`=%d  ",
		 $this->datas['Product Department Families'],
		 $this->id
		 );
    //  print "$sql\n";
    mysql_query($sql);


  }
  





  $this->getdata('id',$this->id);
  break;
  //   case('products'):
//      $sql=sprintf("select * from `Product Dimension` where `Product Department Key`=%d",$this->id);
//      // print $sql;
//      $this->products=array();
//      $result=mysql_query($sql);
//      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       
//        $this->products[$row['product key']]=$row;
//      }
//      break;
//    case('number of products same code'):
//      $sql=sprintf("select count(DISTINCT `Product Code`) as num from `Product Dimension` as P left join `Product Department Bridge` as B on (B.`Product key`=P.`Product Key`)  where `Product Department Key`=%d ",$this->id);
//      // print $sql;
//      $this->products=array();
//      $result=mysql_query($sql);
//      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       
//        $this->data[]=$row['num'];
//      }
     

//      break;
   case('families'):
     $sql=sprintf("select * from `Product Family Dimension` PFD  left join `Product Family Department Bridge` as B on (B.`Product Family Key`=PFD.`Product Family Key`) where `Product Deparment Key`=%d",$this->id);
     //  print $sql;

     $this->families=array();
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->families[$row['Product Family Key']]=$row;
     }
     break;
  
   case('sales'):
     
     $on_sale_days=0;
     
     $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as tto, sum(if(`Product Sales State`='For sale',1,0)) as for_sale   from `Product Dimension` as P left join `Product Department Bridge` as B on (B.`Product Key`=P.`Product Key`)  where `Product Department Key`=".$this->id;

      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	$from=strtotime($row['ffrom']);
	$_from=date("Y-m-d H:i:s",$from);
	if($row['for_sale']>0){
	 $to=strtotime('today');
	 $_to=date("Y-m-d H:i:s");
	}else{
	 $to=strtotime($row['tto']);
	 $_to=date("Y-m-d H:i:s",$to);
	}
	 $on_sale_days=($to-$from)/ (60 * 60 * 24);

       if($row['prods']==0)
	 $on_sale_days=0;

      }
$sql="select sum(`Product Total Invoiced Amount`) as net,sum(`Product Total Invoiced Gross Amount`) as gross,sum(`Product Total Invoiced Discount Amount`) as disc, sum(`Product Total Profit`)as profit ,sum(`Product Total Quantity Delivered`) as delivered,sum(`Product Total Quantity Ordered`) as ordered,sum(`Product Total Quantity Invoiced`) as invoiced  from `Product Dimension` as P left join `Product Department Bridge` as B on (B.`Product Key`=P.`Product Key`)  where `Product Department Key`=".$this->id;


// print "$sql\n\n";
// exit;
 $result=mysql_query($sql);
 
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->data['Product Department Total Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Department Total Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Department Total Invoiced Amount']=$row['net'];

       $this->data['Product Department Total Profit']=$row['profit'];
       $this->data['Product Department Total Quantity Ordered']=$row['ordered'];
       $this->data['Product Department Total Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Department Total Quantity Delivered']=$row['delivered'];
       $this->data['Product Department Total Days On Sale']=$on_sale_days;
       $this->data['Product Department Valid From']=$_from;
       $this->data['Product Department Valid To']=$_to;
       $sql=sprintf("update `Product Department Dimension` set `Product Department Total Invoiced Gross Amount`=%s,`Product Department Total Invoiced Discount Amount`=%s,`Product Department Total Invoiced Amount`=%s,`Product Department Total Profit`=%s, `Product Department Total Quantity Ordered`=%s , `Product Department Total Quantity Invoiced`=%s,`Product Department Total Quantity Delivered`=%s ,`Product Department Total Days On Sale`=%f ,`Product Department Valid From`=%s,`Product Department Valid To`=%s where `Product Department Key`=%d "
		    ,prepare_mysql($this->data['Product Department Total Invoiced Gross Amount'])
		    ,prepare_mysql($this->data['Product Department Total Invoiced Discount Amount'])
		    ,prepare_mysql($this->data['Product Department Total Invoiced Amount'])

		    ,prepare_mysql($this->data['Product Department Total Profit'])
		    ,prepare_mysql($this->data['Product Department Total Quantity Ordered'])
		    ,prepare_mysql($this->data['Product Department Total Quantity Invoiced'])
		    ,prepare_mysql($this->data['Product Department Total Quantity Delivered'])
		    ,$on_sale_days
		    ,prepare_mysql($this->data['Product Department Valid From'])
		    ,prepare_mysql($this->data['Product Department Valid To'])
		    ,$this->id
		    );
     //  print "$sql\n";
     //  exit;
     if(!mysql_query($sql))
       exit("$sql\ncan not update dept sales\n");
     }
     // days on sale
     
   $on_sale_days=0;



 $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales State`='For sale',1,0)) as for_sale   from `Product Dimension` as P left join `Product Department Bridge` as B on (B.`Product Key`=P.`Product Key`)  where `Product Department Key`=".$this->id;
 // print "$sql\n\n";
 $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	if($row['prods']==0)
	 $on_sale_days=0;
	else{
	

	  if($row['for_sale']>0)
	    $to=strtotime('today');
	  else
	    $to=strtotime($row['to']);
	  // print "*** ".$row['to']." T:$to  ".strtotime('today')."  ".strtotime('today -1 year')."  \n";
	  // print "*** T:$to   ".strtotime('today -1 year')."  \n";
	  if($to>strtotime('today -1 year')){
	    //print "caca";
	    $from=strtotime($row['ffrom']);
	    if($from<strtotime('today -1 year'))
	      $from=strtotime('today -1 year');
	    
	    //	    print "*** T:$to F:$from\n";
	    $on_sale_days=($to-$from)/ (60 * 60 * 24);
	  }else{
	    //   print "pipi";
	    $on_sale_days=0;

	  }
	}
      }



 $sql="select sum(`Product 1 Year Acc Invoiced Gross Amount`) as net,sum(`Product 1 Year Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Year Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Year Acc Profit`)as profit ,sum(`Product 1 Year Acc Quantity Delivered`) as delivered,sum(`Product 1 Year Acc Quantity Ordered`) as ordered,sum(`Product 1 Year Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P left join `Product Department Bridge` as B on (B.`Product Key`=P.`Product Key`)  where `Product Department Key`=".$this->id;
     
 $result=mysql_query($sql);

     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->data['Product Department 1 Year Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Department 1 Year Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Department 1 Year Acc Invoiced Amount']=$row['net'];
	      
       $this->data['Product Department 1 Year Acc Profit']=$row['profit'];
       $this->data['Product Department 1 Year Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Department 1 Year Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Department 1 Year Acc Quantity Delivered']=$row['delivered'];
      
        
     $sql=sprintf("update `Product Department Dimension` set `Product Department 1 Year Acc Invoiced Gross Amount`=%s,`Product Department 1 Year Acc Invoiced Discount Amount`=%s,`Product Department 1 Year Acc Invoiced Amount`=%s,`Product Department 1 Year Acc Profit`=%s, `Product Department 1 Year Acc Quantity Ordered`=%s , `Product Department 1 Year Acc Quantity Invoiced`=%s,`Product Department 1 Year Acc Quantity Delivered`=%s ,`Product Department 1 Year Acc Days On Sale`=%f  where `Product Department Key`=%d "
		  ,prepare_mysql($this->data['Product Department 1 Year Acc Invoiced Gross Amount'])
		  ,prepare_mysql($this->data['Product Department 1 Year Acc Invoiced Discount Amount'])
		  ,prepare_mysql($this->data['Product Department 1 Year Acc Invoiced Amount'])

		  ,prepare_mysql($this->data['Product Department 1 Year Acc Profit'])
		  ,prepare_mysql($this->data['Product Department 1 Year Acc Quantity Ordered'])
		  ,prepare_mysql($this->data['Product Department 1 Year Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Department 1 Year Acc Quantity Delivered'])
		  ,$on_sale_days
		  ,$this->id
		  );
     //  print "$sql\n";
     if(!mysql_query($sql))
       exit("$sql\ncan not update dept sales\n");
     }
     // exit;
      $on_sale_days=0;
      

$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales State`='For sale',1,0)) as for_sale   from `Product Dimension` as P left join `Product Department Bridge` as B on (B.`Product Key`=P.`Product Key`)  where `Product Department Key`=".$this->id;

 $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	if($row['prods']==0)
	 $on_sale_days=0;
	else{
	

	  if($row['for_sale']>0)
	    $to=strtotime('today');
	  else
	    $to=strtotime($row['to']);
	  if($to>strtotime('today -3 month')){
	    
	    $from=strtotime($row['ffrom']);
	    if($from<strtotime('today -3 month'))
	      $from=strtotime('today -3 month');
	    
	    
	    $on_sale_days=($to-$from)/ (60 * 60 * 24);
	  }else
	    $on_sale_days=0;
	}
      }

$sql="select sum(`Product 1 Quarter Acc Invoiced Amount`) as net,sum(`Product 1 Quarter Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Quarter Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Quarter Acc Profit`)as profit ,sum(`Product 1 Quarter Acc Quantity Delivered`) as delivered,sum(`Product 1 Quarter Acc Quantity Ordered`) as ordered,sum(`Product 1 Quarter Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P left join `Product Department Bridge` as B on (B.`Product Key`=P.`Product Key`)  where `Product Department Key`=".$this->id;


     $result=mysql_query($sql);
 
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->data['Product Department 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Department 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Department 1 Quarter Acc Invoiced Amount']=$row['net'];
	      
       $this->data['Product Department 1 Quarter Acc Profit']=$row['profit'];
       $this->data['Product Department 1 Quarter Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Department 1 Quarter Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Department 1 Quarter Acc Quantity Delivered']=$row['delivered'];
      
        
     $sql=sprintf("update `Product Department Dimension` set `Product Department 1 Quarter Acc Invoiced Gross Amount`=%s,`Product Department 1 Quarter Acc Invoiced Discount Amount`=%s,`Product Department 1 Quarter Acc Invoiced Amount`=%s,`Product Department 1 Quarter Acc Profit`=%s, `Product Department 1 Quarter Acc Quantity Ordered`=%s , `Product Department 1 Quarter Acc Quantity Invoiced`=%s,`Product Department 1 Quarter Acc Quantity Delivered`=%s  ,`Product Department 1 Quarter Acc Days On Sale`=%f where `Product Department Key`=%d "
		  ,prepare_mysql($this->data['Product Department 1 Quarter Acc Invoiced Gross Amount'])
		  ,prepare_mysql($this->data['Product Department 1 Quarter Acc Invoiced Discount Amount'])
		  ,prepare_mysql($this->data['Product Department 1 Quarter Acc Invoiced Amount'])

		  ,prepare_mysql($this->data['Product Department 1 Quarter Acc Profit'])
		  ,prepare_mysql($this->data['Product Department 1 Quarter Acc Quantity Ordered'])
		  ,prepare_mysql($this->data['Product Department 1 Quarter Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Department 1 Quarter Acc Quantity Delivered'])
		   ,$on_sale_days
		  ,$this->id
		  );
     // print "$sql\n";
     if(!mysql_query($sql))
       exit("$sql\ncan not update dept sales\n");
     }

$on_sale_days=0;

$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales State`='For sale',1,0)) as for_sale   from `Product Dimension` as P left join `Product Department Bridge` as B on (B.`Product Key`=P.`Product Key`)  where `Product Department Key`=".$this->id;
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	if($row['prods']==0)
	 $on_sale_days=0;
	else{
	

	  if($row['for_sale']>0)
	    $to=strtotime('today');
	  else
	    $to=strtotime($row['to']);
	  if($to>strtotime('today -1 month')){
	    
	    $from=strtotime($row['ffrom']);
	    if($from<strtotime('today -1 month'))
	      $from=strtotime('today -1 month');
	    
	    
	    $on_sale_days=($to-$from)/ (60 * 60 * 24);
	  }else
	    $on_sale_days=0;
	}
      }

$sql="select  sum(`Product 1 Month Acc Invoiced Amount`) as net,sum(`Product 1 Month Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Month Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Month Acc Profit`)as profit ,sum(`Product 1 Month Acc Quantity Delivered`) as delivered,sum(`Product 1 Month Acc Quantity Ordered`) as ordered,sum(`Product 1 Month Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P left join `Product Department Bridge` as B on (B.`Product Key`=P.`Product Key`)  where `Product Department Key`=".$this->id;

    
     $result=mysql_query($sql);
 
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->data['Product Department 1 Month Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Department 1 Month Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Department 1 Month Acc Invoiced Amount']=$row['net'];

       $this->data['Product Department 1 Month Acc Profit']=$row['profit'];
       $this->data['Product Department 1 Month Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Department 1 Month Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Department 1 Month Acc Quantity Delivered']=$row['delivered'];
      
        
     $sql=sprintf("update `Product Department Dimension` set `Product Department 1 Month Acc Invoiced Gross Amount`=%s,`Product Department 1 Month Acc Invoiced Discount Amount`=%s,`Product Department 1 Month Acc Invoiced Amount`=%s,`Product Department 1 Month Acc Profit`=%s, `Product Department 1 Month Acc Quantity Ordered`=%s , `Product Department 1 Month Acc Quantity Invoiced`=%s,`Product Department 1 Month Acc Quantity Delivered`=%s  ,`Product Department 1 Month Acc Days On Sale`=%f where `Product Department Key`=%d "
		  ,prepare_mysql($this->data['Product Department 1 Month Acc Invoiced Gross Amount'])
		  ,prepare_mysql($this->data['Product Department 1 Month Acc Invoiced Discount Amount'])
		  ,prepare_mysql($this->data['Product Department 1 Month Acc Invoiced Amount'])

		  ,prepare_mysql($this->data['Product Department 1 Month Acc Profit'])
		  ,prepare_mysql($this->data['Product Department 1 Month Acc Quantity Ordered'])
		  ,prepare_mysql($this->data['Product Department 1 Month Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Department 1 Month Acc Quantity Delivered'])
		   ,$on_sale_days
		  ,$this->id
		  );
     // print "$sql\n";
     if(!mysql_query($sql))
       exit("$sql\ncan not update dept sales\n");
     }

          $on_sale_days=0;
$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales State`='For sale',1,0)) as for_sale   from `Product Dimension` as P left join `Product Department Bridge` as B on (B.`Product Key`=P.`Product Key`)  where `Product Department Key`=".$this->id;
      $result=mysql_query($sql);
      if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
	if($row['prods']==0)
	 $on_sale_days=0;
	else{
	

	  if($row['for_sale']>0)
	    $to=strtotime('today');
	  else
	    $to=strtotime($row['to']);
	  if($to>strtotime('today -1 week')){
	    
	    $from=strtotime($row['ffrom']);
	    if($from<strtotime('today -1 week'))
	      $from=strtotime('today -1 week');
	    
	    
	    $on_sale_days=($to-$from)/ (60 * 60 * 24);
	  }else
	    $on_sale_days=0;
	}
      }


  
$sql="select sum(`Product 1 Week Acc Invoiced Amount`) as net,sum(`Product 1 Week Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Week Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Week Acc Profit`)as profit ,sum(`Product 1 Week Acc Quantity Delivered`) as delivered,sum(`Product 1 Week Acc Quantity Ordered`) as ordered,sum(`Product 1 Week Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P left join `Product Department Bridge` as B on (B.`Product Key`=P.`Product Key`)  where `Product Department Key`=".$this->id;



   $result=mysql_query($sql);

     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->data['Product Department 1 Week Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Product Department 1 Week Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Product Department 1 Week Acc Invoiced Amount']=$row['net'];
       $this->data['Product Department 1 Week Acc Profit']=$row['profit'];
       $this->data['Product Department 1 Week Acc Quantity Ordered']=$row['ordered'];
       $this->data['Product Department 1 Week Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Product Department 1 Week Acc Quantity Delivered']=$row['delivered'];
      
        
     $sql=sprintf("update `Product Department Dimension` set `Product Department 1 Week Acc Invoiced Gross Amount`=%s,`Product Department 1 Week Acc Invoiced Discount Amount`=%s,`Product Department 1 Week Acc Invoiced Amount`=%s,`Product Department 1 Week Acc Profit`=%s, `Product Department 1 Week Acc Quantity Ordered`=%s , `Product Department 1 Week Acc Quantity Invoiced`=%s,`Product Department 1 Week Acc Quantity Delivered`=%s ,`Product Department 1 Week Acc Days On Sale`=%f  where `Product Department Key`=%d "
		  ,prepare_mysql($this->data['Product Department 1 Week Acc Invoiced Gross Amount'])
		  ,prepare_mysql($this->data['Product Department 1 Week Acc Invoiced Discount Amount'])
		  ,prepare_mysql($this->data['Product Department 1 Week Acc Invoiced Amount'])
		  ,prepare_mysql($this->data['Product Department 1 Week Acc Profit'])
		  ,prepare_mysql($this->data['Product Department 1 Week Acc Quantity Ordered'])
		  ,prepare_mysql($this->data['Product Department 1 Week Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Product Department 1 Week Acc Quantity Delivered'])
		  ,$on_sale_days
		  ,$this->id
		  );
     // print "$sql\n";
     if(!mysql_query($sql))
       exit("$sql\ncan not update dept sales\n");
     
     }
     
     break;
  

   }
   
 }

 function save($tipo){
   switch($tipo){
   case('first_date'):

     if(is_numeric($this->data['first_date'])){
       $sql=sprintf("update product_department set first_date=%s where id=%d",
		    prepare_mysql(
				  date("Y-m-d H:i:s",strtotime('@'.$this->data['first_date'])))
		    ,$this->id);
     }else
       $sql=sprintf("update product_group set first_date=NULL where id=%d",$this->id);
     
     //     print "$sql;\n";
     mysql_query($sql);
     
     break;
   case('sales'):
       $sql=sprintf("select id from sales where tipo='dept' and tipo_id=%d",$this->id);
      $res = $this->db->query($sql); 
      if ($row=$res->fetchRow()) {
	$sales_id=$row['id'];
      }else{
	$sql=sprintf("insert into sales (tipo,tipo_id) values ('dept',%d)",$this->id);
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


 function get($key){

   if(array_key_exists($key,$this->data))
      return $this->data[$key];


   switch($key){

   case('Total Products'):
     return $this->data['Product Department For Sale Products']+$this->data['Product Department In Process Products']+$this->data['Product Department Not For Sale Products']+$this->data['Product Department Discontinued Products']+$this->data['Product Department Unknown Sales State Products'];
     break;

 //   case('weeks'):
//      $_diff_seconds=date('U')-$this->data['first_date'];
//      $day_diff=$_diff_seconds/24/3600;
//      $weeks=$day_diff/7;
//      return $weeks;
    }

 }
 
function add_product($product_id,$args=false){


   $product=New Product($product_id);
   if($product->id){
     $sql=sprintf("insert into `Product Department Bridge` (`Product Key`,`Product Department Key`) values (%d,%d)",$product->id,$this->id);
     mysql_query($sql);
     $this->load('products_info');

    //  $sql=sprintf("select sum(if(`Product Sales State`='Unknown',1,0)) as sale_unknown, sum(if(`Product Sales State`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales State`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales State`='For sale',1,0)) as for_sale,sum(if(`Product Sales State`='In Process',1,0)) as in_process,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension` P left join  `Product Department Bridge` B on (P.`Product Key`=B.`Product Key`) where `Product Department Key`=%d",$this->id);
//      //  print $sql;
//      $res = $this->db->query($sql);
//      if($row = $res->fetchrow()) {

//        $sql=sprintf("update `Product Department Dimension` set `Product Department For Sale Products`=%d ,`Product Department Discontinued Products`=%d ,`Product Department Not For Sale Products`=%d ,`Product Department Unknown Sales State Products`=%d, `Product Department Optimal Availability Products`=%d , `Product Department Low Availability Products`=%d ,`Product Department Critical Availability Products`=%d ,`Product Department Out Of Stock Products`=%d,`Product Department Unknown Stock Products`=%d ,`Product Department Surplus Availability Products`=%d where `Product Department Key`=%d  ",
// 		    $row['for_sale'],
// 		    $row['discontinued'],
// 		    $row['not_for_sale'],
// 		    $row['sale_unknown'],
// 		    $row['availability_optimal'],
// 		    $row['availability_low'],
// 		    $row['availability_critical'],
// 		    $row['availability_outofstock'],
// 		    $row['availability_unknown'],
// 		    $row['availability_surplus'],
// 		    $this->id
// 	    );
//        //  print "$sql\n";exit;
//        $this->db->exec($sql);

    
//      }


     
     if(preg_match('/principal/',$args)){
       $sql=sprintf("update  `Product Dimension` set `Product Main Department Key`=%d ,`Product Main Department Code`=%s,`Product Main Department Name`=%s where `Product Key`=%s    "
		    ,$this->id
		    ,prepare_mysql($this->get('Product Department Code'))
		    ,prepare_mysql($this->get('Product Department Name'))
		    ,$product->id);

       mysql_query($sql);
     }
   }
 }

function add_family($family_id,$args=false){
   $family=New Family($family_id);
   if($family->id){
     $sql=sprintf("insert into `Product Family Department Bridge` (`Product Family Key`,`Product Department Key`) values (%d,%d)",$family->id,$this->id);
     mysql_query($sql);


     $sql=sprintf("select count(*) as num from `Product Family Department Bridge`  where `Product Department Key`=%d",$this->id);
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $sql=sprintf("update `Product Department Dimension` set `Product Department Families`=%d   where `Product Department Key`=%d  ",
		    $row['num'],
		    $this->id
		    );
       //  print "$sql\n";exit;
       mysql_query($sql);
     }
     if(!preg_match('/noproduct/i',$args) ){
       foreach($family->get('products') as $key => $value){
	 $this->add_product($key,$args);
       }
     }
   

     if(preg_match('/principal/',$args)){
       $sql=sprintf("update  `Product Family Dimension` set `Product Family Main Department Key`=%d ,`Product Family Main Department Code`=%s,`Product Family Main Department Name`=%s where `Product Family Key`=%s    "
		    ,$this->id
		    ,prepare_mysql($this->get('Product Department Code'))
		    ,prepare_mysql($this->get('Product Department Name'))
		    ,$family->id);
       mysql_query($sql);
     }
   }
 }


 }

?>