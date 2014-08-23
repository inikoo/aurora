<?php
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Location.php');

require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
  
mysql_query("SET time_zone ='+0:00'");
require_once '../../myconf/conf.php';           
date_default_timezone_set('UTC');

$update_product_data=false;
$update_old_locations=false;
  $add_products=true;
  if($update_product_data){
    $sql="select code,id from aw.product ";
    $res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $code=$row['code'];
      $id=$row['id'];
      // print "updating $code\n";
      $sql=sprintf("select * from aw_old.product where code like '%s'",$code);
      $res2 = mysql_query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
      if($row2=$res2->fetchRow()) {
	$old_stock=$row2['stock']*$row2['units'];
	$product=new Product($id);
	$product->update(
		     array(
			   array('key'=>'description','value'=>$row2['description']),
			   array('key'=>'units','value'=>$row2['units']),
			   array('key'=>'price','value'=>$row2['price'])
			   )
		     ,'save');


      }
    }
  }

if($update_old_locations){
    $sql="select code,id from aw.product ";
    $res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
    while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
      $code=$row['code'];
      $id=$row['id'];
      // print "updating $code\n";
      $sql=sprintf("select * from aw_old.product where code like '%s'",$code);
      $res2 = mysql_query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
      if($row2=$res2->fetchRow()) {
	$old_stock=$row2['stock']*$row2['units'];
	$product=new Product($id);
	$product->update_location(array('tipo'=>'delete_all'));

     $sql="select * from aw_old.location  where product_id=".$row2['id']." order by tipo" ;
     $resloc = mysql_query($sql); 
     $has_location=false;
     $primary=true;
     while($rowloc=$resloc->fetchRow()) {
       
       $has_location=true;
       $loc_code=$rowloc['code'];
       if(!preg_match('/^(\d+[abcbdefgh]\d+|\d+\-\d+\-\d+|UPSTAIRS|Production|canteen)$/i',$loc_code)){
	 print "$code $old_stock Wrong location $loc_code\n";
	 // add stock to unknown
	 if($old_stock>0){
	     $data=array(
		       'location_name'=>'_UNK',
		       'is_primary'=>true,
		       'user_id'=>0,
		       'can_pick'=>true,
		       'tipo'=>'associate_location'
	       );
	   $_res=$product->update_location($data);
	   //   print_r($_res);
	      $data=array(
		 'p2l_id'=>$product->get('pl2_id',array('id'=>1)),
		 'qty'=>$old_stock,
		 'msg'=>'Value taken from old database',
		 'user_id'=>0,
		 'tipo'=>'change_qty'
		 );

	   $product->update_location($data);
	 }elseif($old_stock<0 and false){
	   
	   
	   $data=array(
		       'location_name'=>'_WHL',
		       'is_primary'=>true,
		       'user_id'=>0,
		       'can_pick'=>true,
		       'tipo'=>'associate_location'
	       );
	   $product->update_location($data);
	   
	      $data=array(
		 'p2l_id'=>$product->get('pl2_id',array('id'=>2)),
		 'qty'=>$old_stock,
		 'msg'=>'Value taken from old database',
		 'user_id'=>0,
		 'tipo'=>'change_qty'
		 );

	   $product->update_location($data);
	 }


       }else{
	 // Check if the location already exist
	 $location=new Location('name',$loc_code);
	 if($location->id){
	   // print "location found in new database\n";
	 }else{
	   //print "Creating location $loc_code\n";

	   if(preg_match('/^(\d+\-\d+\-\d+)$/i',$loc_code))
	     $_tipo='storing';
	   else
	     $_tipo='picking';
	   $location=new Location('new',array('name'=>$loc_code,'tipo'=>$_tipo));
	   
	   //  exit;
	 }
	   
	   
	 if($primary){
	   //  print "$old_stock units  found in $loc_code\n";
	   $data=array(
		       'location_name'=>$location->get('name'),
		       'is_primary'=>true,
		       'user_id'=>0,
		       'can_pick'=>true,
		       'tipo'=>'associate_location'
	       );
	   $product->update_location($data);
	   
	   $p2l_id=$product->get('pl2_id',array('id'=>$location->id));
	   
	   
	   $data=array(
		 'p2l_id'=>$p2l_id,
		 'qty'=>$old_stock,
		 'msg'=>'Value taken from old database',
		 'user_id'=>0,
		 'tipo'=>'change_qty'
		 );

	   $product->update_location($data);



	 }

	 else{
	   $data=array(
		       'location_name'=>$location->get('name'),
		       'is_primary'=>false,
		       'user_id'=>0,
		       'can_pick'=>false,
		       'tipo'=>'associate_location'
	       );
	   // print_r($data);
	   $product->update_location($data);
	 }
	 $primary=false;
       }
     }
       

     
     
     if(!$has_location){
        if($old_stock>0){
	  
	     $data=array(
		       'location_name'=>'_UNK',
		       'is_primary'=>true,
		       'user_id'=>0,
		       'can_pick'=>true,
		       'tipo'=>'associate_location'
	       );
	   $product->update_location($data);
	   
	      $data=array(
			  'p2l_id'=>$product->get('pl2_id',array('id'=>1)),
		 'qty'=>$old_stock,
		 'msg'=>'Value taken from old database',
		 'user_id'=>0,
		 'tipo'=>'change_qty'
		 );
	      // print_r($data);
	      $product->update_location($data);

	      //xit("caca\n");
	 }elseif($old_stock<0 and false){
	   
	   
	   $data=array(
		       'location_name'=>'_WHL',
		       'is_primary'=>true,
		       'user_id'=>0,
		       'can_pick'=>true,
		       'tipo'=>'associate_location'
	       );
	   $_res=$product->update_location($data);
	   // print_r($_res);
	      $data=array(
		 'p2l_id'=>$product->get('pl2_id',array('id'=>2)),
		 'qty'=>$old_stock,
		 'msg'=>'Value taken from old database',
		 'user_id'=>0,
		 'tipo'=>'change_qty'
		 );

	   $_res=$product->update_location($data);
	   //  print_r($data);
	   // print_r($_res);
	   //exit;
	}
     }
  }

    


 

  //print " $id\r";
 }



  }

if( $add_products){
  $sql="select * from aw_old.product ";
  $res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
    $code=$row['code'];
    $id=$row['id'];
    $group_id=$row['group_id'];
    $sql="select * from aw_old.product_group where id=$group_id ";
    $res3 = mysql_query($sql); if (PEAR::isError($res3) and DEBUG ){die($res3->getMessage());}
    $fam_data=$res3->fetchRow();
    
    $deparment_id=$fam_data['department_id'];
    
    $sql="select name as code,id from aw_old.product_department where id=$deparment_id ";
    //print "$sql\n";
    $res4 = mysql_query($sql); if (PEAR::isError($res4) and DEBUG ){die($res4->getMessage());}
    $dept_data=$res4->fetchRow();
    // print_r($dept_data);
    
    $sql=sprintf("select id  from aw.product where code like '%s'",$code);
    $res2 = mysql_query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
    if($row2=$res2->fetchRow()) {
      // print $row2['id']." $id $code\n";
    }else{
      print "Product $code not found\n";
      //print "Fam name->".$fam_data['name']."<-\n";
      $family=new Family('name',$fam_data['name']);
      if(!$family->id){
	//print "Dept code->".$dept_data['code']."<-\n";
	$dept=new department('code',$dept_data['code']);
	if(!$department->id){
	  $data=array(
		      'name'=>$dept_data['code'],
		      'code'=>$dept_data['code']
		      );
	  $dept->create($data);
	}
	$department_id=$dept->id;
	$msg=$family->create(array(
				   'name'=>$fam_data['name'],
				   'description'=>$fam_data['description'],
			    'department_id'=>$department_id
				   ));
	//  print_r($msg);
      }
      $group_id=$family->id;
      $datos=array(
		   'code'=>$row['code'],
		   'sale_status'=>($row['condition']==0?'normal':'discontinued'),
		   'rrp'=>$row['rrp'],
		   'price'=>$row['price'],
		   'units'=>$row['units'],
		   'description'=>$row['description'],
		   'sdescription'=>$row['sdescription'],
		   'group_id'=>$group_id
		   );
      print_r($datos);
      $product=new product('new',$datos);
      
      print "new  ".$row2['id']." $id $code\n";
      print_r($product->msg);

    }
    //  print " $id\r";
  }
 }



?>