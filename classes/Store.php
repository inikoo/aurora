<?
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
class store{


  var $id=false;


  function __construct($a1,$a2=false) {



    if(is_numeric($a1) and !$a2){
      $this->get_data('id',$a1);
    }
    else if(($a1=='new' or $a1=='create') and is_array($a2) ){
      $this->create($a2);
      
    }else
      $this->get_data($a1,$a2);

  }
  
// function get_unknown(){
//   $sql=sprintf("select * from `Store Dimension` where `Store Type`='unknown'");
//   $result=mysql_query($sql);
//   if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
//     $this->id=$this->data['Store Key'];
// }


  function get_data($tipo,$tag){

    if($tipo=='id')
      $sql=sprintf("select * from `Store Dimension` where `Store Key`=%d",$tag);
    elseif($tipo=='code')
      $sql=sprintf("select * from `Store Dimension` where `Store Code`=%s",prepare_mysql($tag));
    else
      return;

    // print $sql;
    $result=mysql_query($sql);
    if($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
      $this->id=$this->data['Store Key'];
    

  }


  

  

  function get($key=''){

    if(isset($this->data[$key]))
      return $this->data[$key];
    
    switch($key){
    case('code'):
      return $this->data['Store Code'];
      break;
    case('type'):
      return $this->data['Store Type'];
      break;
    case('Total Products'):
     return $this->data['Store For Sale Products']+$this->data['Store In Process Products']+$this->data['Store Not For Sale Products']+$this->data['Store Discontinued Products']+$this->data['Store Unknown Sales State Products'];
     break;


    }
    $_key=ucfirst($key);
    if(isset($this->data[$_key]))
      return $this->data[$_key];
    print "Error $key not found in get from Product\n";
    return false; 

  }


 function delete(){
   $this->deleted=false;
   $this->load('products_info');

   if($this->get('Total Products')==0){
     $sql=sprintf("delete from `Store Dimension` where `Store Key`=%d",$this->id);
     if(mysql_query($sql)){

       $this->deleted=true;
	  
     }else{

       $this->msg=_('Error: can not delete store');
       return;
     }     

     $this->deleted=true;
   }else{
     $this->msg=_('Store can not be deleted because it has some products');

   }
 }




 function load($tipo,$args=false){
   switch($tipo){
   case('products_info'):
      $sql=sprintf("select sum(if(`Product Record Type`='In process',1,0)) as in_process,sum(if(`Product Sales State`='Unknown',1,0)) as sale_unknown, sum(if(`Product Sales State`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales State`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales State`='For sale',1,0)) as for_sale,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from `Product Dimension` where  `Product Store Key`=%d",$this->id);
      // print "$sql\n\n\n";
 $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){

    $sql=sprintf("update `Store Dimension` set `Store In Process Products`=%d,`Store For Sale Products`=%d ,`Store Discontinued Products`=%d ,`Store Not For Sale Products`=%d ,`Store Unknown Sales State Products`=%d, `Store Optimal Availability Products`=%d , `Store Low Availability Products`=%d ,`Store Critical Availability Products`=%d ,`Store Out Of Stock Products`=%d,`Store Unknown Stock Products`=%d ,`Store Surplus Availability Products`=%d where `Store Key`=%d  ",
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

  $sql=sprintf("select count(*) as num from `Product Family Dimension`  where  `Product Family Store Key`=%d",$this->id);
  //print $sql;
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $this->data['Store Families']=$row['num'];
  }
  $sql=sprintf("select count(*) as num from `Product Department Dimension`  where  `Product Department Store Key`=%d",$this->id);
  $result=mysql_query($sql);
  if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $this->data['Store Departments']=$row['num'];
  }
  
 $sql=sprintf("update `Store Dimension` set `Store Families`=%d ,`Store Departments`=%d  where `Store Key`=%d  ",
	      $this->data['Store Families']
	      ,$this->data['Store Departments']
	      ,$this->id
	      );
 //  print "$sql\n";exit;
 mysql_query($sql);
 
 break;


     $this->getdata('id',$this->id);
     break;

   case('families'):
     $sql=sprintf("select * from `Product Family Dimension`  where  `Product Family Store Key`=%d",$this->id);
     //  print $sql;

     $this->families=array();
     $result=mysql_query($sql);
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->families[$row['family key']]=$row;
     }
     break;
  
   case('sales'):
     
     $on_sale_days=0;
     
     $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as tto, sum(if(`Product Sales State`='For sale',1,0)) as for_sale   from `Product Dimension` as P   where `Product Store Key`=".$this->id;

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
$sql="select sum(`Product Total Invoiced Amount`) as net,sum(`Product Total Invoiced Gross Amount`) as gross,sum(`Product Total Invoiced Discount Amount`) as disc, sum(`Product Total Profit`)as profit ,sum(`Product Total Quantity Delivered`) as delivered,sum(`Product Total Quantity Ordered`) as ordered,sum(`Product Total Quantity Invoiced`) as invoiced  from `Product Dimension` as P where `Product Store Key`=".$this->id;


// print "$sql\n\n";
// exit;
 $result=mysql_query($sql);
 
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->data['Store Total Invoiced Gross Amount']=$row['gross'];
       $this->data['Store Total Invoiced Discount Amount']=$row['disc'];
       $this->data['Store Total Invoiced Amount']=$row['net'];

       $this->data['Store Total Profit']=$row['profit'];
       $this->data['Store Total Quantity Ordered']=$row['ordered'];
       $this->data['Store Total Quantity Invoiced']=$row['invoiced'];
       $this->data['Store Total Quantity Delivered']=$row['delivered'];
       $this->data['Store Total Days On Sale']=$on_sale_days;
       $this->data['Store Valid From']=$_from;
       $this->data['Store Valid To']=$_to;
       $sql=sprintf("update `Store Dimension` set `Store Total Invoiced Gross Amount`=%s,`Store Total Invoiced Discount Amount`=%s,`Store Total Invoiced Amount`=%s,`Store Total Profit`=%s, `Store Total Quantity Ordered`=%s , `Store Total Quantity Invoiced`=%s,`Store Total Quantity Delivered`=%s ,`Store Total Days On Sale`=%f ,`Store Valid From`=%s,`Store Valid To`=%s where `Store Key`=%d "
		    ,prepare_mysql($this->data['Store Total Invoiced Gross Amount'])
		    ,prepare_mysql($this->data['Store Total Invoiced Discount Amount'])
		    ,prepare_mysql($this->data['Store Total Invoiced Amount'])

		    ,prepare_mysql($this->data['Store Total Profit'])
		    ,prepare_mysql($this->data['Store Total Quantity Ordered'])
		    ,prepare_mysql($this->data['Store Total Quantity Invoiced'])
		    ,prepare_mysql($this->data['Store Total Quantity Delivered'])
		    ,$on_sale_days
		    ,prepare_mysql($this->data['Store Valid From'])
		    ,prepare_mysql($this->data['Store Valid To'])
		    ,$this->id
		    );
     //  print "$sql\n";
     //  exit;
     if(!mysql_query($sql))
       exit("$sql\ncan not update dept sales\n");
     }
     // days on sale
     
   $on_sale_days=0;



 $sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales State`='For sale',1,0)) as for_sale   from `Product Dimension` as P   where `Product Store Key`=".$this->id;
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



 $sql="select sum(`Product 1 Year Acc Invoiced Gross Amount`) as net,sum(`Product 1 Year Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Year Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Year Acc Profit`)as profit ,sum(`Product 1 Year Acc Quantity Delivered`) as delivered,sum(`Product 1 Year Acc Quantity Ordered`) as ordered,sum(`Product 1 Year Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;
     
 $result=mysql_query($sql);

     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->data['Store 1 Year Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Store 1 Year Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Store 1 Year Acc Invoiced Amount']=$row['net'];
	      
       $this->data['Store 1 Year Acc Profit']=$row['profit'];
       $this->data['Store 1 Year Acc Quantity Ordered']=$row['ordered'];
       $this->data['Store 1 Year Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Store 1 Year Acc Quantity Delivered']=$row['delivered'];
      
        
     $sql=sprintf("update `Store Dimension` set `Store 1 Year Acc Invoiced Gross Amount`=%s,`Store 1 Year Acc Invoiced Discount Amount`=%s,`Store 1 Year Acc Invoiced Amount`=%s,`Store 1 Year Acc Profit`=%s, `Store 1 Year Acc Quantity Ordered`=%s , `Store 1 Year Acc Quantity Invoiced`=%s,`Store 1 Year Acc Quantity Delivered`=%s ,`Store 1 Year Acc Days On Sale`=%f  where `Store Key`=%d "
		  ,prepare_mysql($this->data['Store 1 Year Acc Invoiced Gross Amount'])
		  ,prepare_mysql($this->data['Store 1 Year Acc Invoiced Discount Amount'])
		  ,prepare_mysql($this->data['Store 1 Year Acc Invoiced Amount'])

		  ,prepare_mysql($this->data['Store 1 Year Acc Profit'])
		  ,prepare_mysql($this->data['Store 1 Year Acc Quantity Ordered'])
		  ,prepare_mysql($this->data['Store 1 Year Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Store 1 Year Acc Quantity Delivered'])
		  ,$on_sale_days
		  ,$this->id
		  );
     //  print "$sql\n";
     if(!mysql_query($sql))
       exit("$sql\ncan not update dept sales\n");
     }
     // exit;
      $on_sale_days=0;
      

$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales State`='For sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Store Key`=".$this->id;

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

$sql="select sum(`Product 1 Quarter Acc Invoiced Amount`) as net,sum(`Product 1 Quarter Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Quarter Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Quarter Acc Profit`)as profit ,sum(`Product 1 Quarter Acc Quantity Delivered`) as delivered,sum(`Product 1 Quarter Acc Quantity Ordered`) as ordered,sum(`Product 1 Quarter Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;


     $result=mysql_query($sql);
 
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->data['Store 1 Quarter Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Store 1 Quarter Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Store 1 Quarter Acc Invoiced Amount']=$row['net'];
	      
       $this->data['Store 1 Quarter Acc Profit']=$row['profit'];
       $this->data['Store 1 Quarter Acc Quantity Ordered']=$row['ordered'];
       $this->data['Store 1 Quarter Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Store 1 Quarter Acc Quantity Delivered']=$row['delivered'];
      
        
     $sql=sprintf("update `Store Dimension` set `Store 1 Quarter Acc Invoiced Gross Amount`=%s,`Store 1 Quarter Acc Invoiced Discount Amount`=%s,`Store 1 Quarter Acc Invoiced Amount`=%s,`Store 1 Quarter Acc Profit`=%s, `Store 1 Quarter Acc Quantity Ordered`=%s , `Store 1 Quarter Acc Quantity Invoiced`=%s,`Store 1 Quarter Acc Quantity Delivered`=%s  ,`Store 1 Quarter Acc Days On Sale`=%f where `Store Key`=%d "
		  ,prepare_mysql($this->data['Store 1 Quarter Acc Invoiced Gross Amount'])
		  ,prepare_mysql($this->data['Store 1 Quarter Acc Invoiced Discount Amount'])
		  ,prepare_mysql($this->data['Store 1 Quarter Acc Invoiced Amount'])

		  ,prepare_mysql($this->data['Store 1 Quarter Acc Profit'])
		  ,prepare_mysql($this->data['Store 1 Quarter Acc Quantity Ordered'])
		  ,prepare_mysql($this->data['Store 1 Quarter Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Store 1 Quarter Acc Quantity Delivered'])
		   ,$on_sale_days
		  ,$this->id
		  );
     // print "$sql\n";
     if(!mysql_query($sql))
       exit("$sql\ncan not update dept sales\n");
     }

$on_sale_days=0;

$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales State`='For sale',1,0)) as for_sale   from `Product Dimension` as P  where `Product Store Key`=".$this->id;
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

$sql="select  sum(`Product 1 Month Acc Invoiced Amount`) as net,sum(`Product 1 Month Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Month Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Month Acc Profit`)as profit ,sum(`Product 1 Month Acc Quantity Delivered`) as delivered,sum(`Product 1 Month Acc Quantity Ordered`) as ordered,sum(`Product 1 Month Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P  where `Product Store Key`=".$this->id;

    
     $result=mysql_query($sql);
 
     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->data['Store 1 Month Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Store 1 Month Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Store 1 Month Acc Invoiced Amount']=$row['net'];

       $this->data['Store 1 Month Acc Profit']=$row['profit'];
       $this->data['Store 1 Month Acc Quantity Ordered']=$row['ordered'];
       $this->data['Store 1 Month Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Store 1 Month Acc Quantity Delivered']=$row['delivered'];
      
        
     $sql=sprintf("update `Store Dimension` set `Store 1 Month Acc Invoiced Gross Amount`=%s,`Store 1 Month Acc Invoiced Discount Amount`=%s,`Store 1 Month Acc Invoiced Amount`=%s,`Store 1 Month Acc Profit`=%s, `Store 1 Month Acc Quantity Ordered`=%s , `Store 1 Month Acc Quantity Invoiced`=%s,`Store 1 Month Acc Quantity Delivered`=%s  ,`Store 1 Month Acc Days On Sale`=%f where `Store Key`=%d "
		  ,prepare_mysql($this->data['Store 1 Month Acc Invoiced Gross Amount'])
		  ,prepare_mysql($this->data['Store 1 Month Acc Invoiced Discount Amount'])
		  ,prepare_mysql($this->data['Store 1 Month Acc Invoiced Amount'])

		  ,prepare_mysql($this->data['Store 1 Month Acc Profit'])
		  ,prepare_mysql($this->data['Store 1 Month Acc Quantity Ordered'])
		  ,prepare_mysql($this->data['Store 1 Month Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Store 1 Month Acc Quantity Delivered'])
		   ,$on_sale_days
		  ,$this->id
		  );
     // print "$sql\n";
     if(!mysql_query($sql))
       exit("$sql\ncan not update dept sales\n");
     }

          $on_sale_days=0;
$sql="select count(*) as prods,min(`Product For Sale Since Date`) as ffrom ,max(`Product Last Sold Date`) as `to`, sum(if(`Product Sales State`='For sale',1,0)) as for_sale   from `Product Dimension` as P where `Product Store Key`=".$this->id;
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


  
$sql="select sum(`Product 1 Week Acc Invoiced Amount`) as net,sum(`Product 1 Week Acc Invoiced Gross Amount`) as gross,sum(`Product 1 Week Acc Invoiced Discount Amount`) as disc, sum(`Product 1 Week Acc Profit`)as profit ,sum(`Product 1 Week Acc Quantity Delivered`) as delivered,sum(`Product 1 Week Acc Quantity Ordered`) as ordered,sum(`Product 1 Week Acc Quantity Invoiced`) as invoiced  from `Product Dimension` as P   where `Product Store Key`=".$this->id;



   $result=mysql_query($sql);

     if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
       $this->data['Store 1 Week Acc Invoiced Gross Amount']=$row['gross'];
       $this->data['Store 1 Week Acc Invoiced Discount Amount']=$row['disc'];
       $this->data['Store 1 Week Acc Invoiced Amount']=$row['net'];
       $this->data['Store 1 Week Acc Profit']=$row['profit'];
       $this->data['Store 1 Week Acc Quantity Ordered']=$row['ordered'];
       $this->data['Store 1 Week Acc Quantity Invoiced']=$row['invoiced'];
       $this->data['Store 1 Week Acc Quantity Delivered']=$row['delivered'];
      
        
     $sql=sprintf("update `Store Dimension` set `Store 1 Week Acc Invoiced Gross Amount`=%s,`Store 1 Week Acc Invoiced Discount Amount`=%s,`Store 1 Week Acc Invoiced Amount`=%s,`Store 1 Week Acc Profit`=%s, `Store 1 Week Acc Quantity Ordered`=%s , `Store 1 Week Acc Quantity Invoiced`=%s,`Store 1 Week Acc Quantity Delivered`=%s ,`Store 1 Week Acc Days On Sale`=%f  where `Store Key`=%d "
		  ,prepare_mysql($this->data['Store 1 Week Acc Invoiced Gross Amount'])
		  ,prepare_mysql($this->data['Store 1 Week Acc Invoiced Discount Amount'])
		  ,prepare_mysql($this->data['Store 1 Week Acc Invoiced Amount'])
		  ,prepare_mysql($this->data['Store 1 Week Acc Profit'])
		  ,prepare_mysql($this->data['Store 1 Week Acc Quantity Ordered'])
		  ,prepare_mysql($this->data['Store 1 Week Acc Quantity Invoiced'])
		  ,prepare_mysql($this->data['Store 1 Week Acc Quantity Delivered'])
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


 function update($key,$a1=false,$a2=false){
   $this->updated=false;
   $this->msg='Nothing to change';
   
   switch($key){
   case('code'):

     if(_trim($a1)==$this->data['Store Code']){
       $this->updated=true;
       $this->newvalue=$a1;
       return;
       
     }

     if($a1==''){
       $this->msg=_('Error: Wrong code (empty)');
       return;
     }
     $sql=sprintf("select count(*) as num from `Store Dimension` where  `Store Code`=%s COLLATE utf8_general_ci  "
		,prepare_mysql($a1)
		);
     $res=mysql_query($sql);
     $row=mysql_fetch_array($res);
     if($row['num']>0){
       $this->msg=_("Error: There is another store with the same code");
       return;
     }
     
      $sql=sprintf("update `Store Dimension` set `Store Code`=%s where `Store Key`=%d  "
		   ,prepare_mysql($a1)
		   ,$this->id
		);
      if(mysql_query($sql)){
	$this->msg=_('Store code updated');
	$this->updated=true;$this->newvalue=$a1;
      }else{
	$this->msg=_("Error: Store code could not be updated");

	$this->updated=false;
	
      }
      break;	
      
   case('name'):
     
     if(_trim($a1)==$this->data['Store Name']){
       $this->updated=true;
       $this->newvalue=$a1;
       return;
       
     }
     
     if($a1==''){
       $this->msg=_('Error: Wrong name (empty)');
       return;
     }
     $sql=sprintf("select count(*) as num from `Store Dimension` where `Store Name`=%s COLLATE utf8_general_ci"
		,prepare_mysql($a1)
		);

     $res=mysql_query($sql);
     $row=mysql_fetch_array($res);
     if($row['num']>0){
       $this->msg=_("Error: Another store with the same name");
       return;
     }
     
      $sql=sprintf("update `Store Dimension` set `Store Name`=%s where `Store Key`=%d "
		   ,prepare_mysql($a1)
		   ,$this->id
		);
      if(mysql_query($sql)){
	$this->msg=_('Store name updated');
	$this->updated=true;$this->newvalue=$a1;
      }else{
	$this->msg=_("Error: Store name could not be updated");

	$this->updated=false;
	
      }
      break;	


   }


 }

 function create($data){


   if(isset($data['name']))
     $data['Store Name']=$data['name'];
    if(isset($data['code']))
     $data['Store Code']=$data['code'];

   $this->new=false;
   if(!isset($data['Store Code'])){
     $this->msg=_("Error: No store code provided");
     return;
   }

   if($data['Store Code']=='' ){
     $this->msg=_("Error: Wrong store code");
     return;
   }

   if(!isset($data['Store Name'])){
     $data['Store Name']=$data['Store Code'];
      $this->msg=_("Warning: No store name");
   }


   $sql=sprintf("select count(*) as num from `Store Dimension` where `Store Code`=%s "
		,prepare_mysql($data['Store Code'])
		);
   $res=mysql_query($sql);
   $row=mysql_fetch_array($res);
   if($row['num']>0){
     $this->msg=_("Error: Another store with the same code");
     return;
     
   }
   
   $sql=sprintf("select count(*) as num from `Store Dimension` where `Store Name`=%s "
		,prepare_mysql($data['Store Name'])
		);
   $res=mysql_query($sql);
   $row=mysql_fetch_array($res);
   if($row['num']>0){
     $this->msg=_("Warning: Wrong another store with the same name");

     
   }



   $sql=sprintf("insert into `Store Dimension` (`Store Code`,`Store Name`) values (%s,%s)"
		,prepare_mysql($data['Store Code'])
		,prepare_mysql($data['Store Name'])
		);



 if(mysql_query($sql)){
   $this->id = mysql_insert_id();
   $this->msg=_("Store Added");
   $this->get_data('id',$this->id);
   $this->new=true;
   return;
 }else{
   $this->msg=_("Error can not create store");

 }

 }
 }