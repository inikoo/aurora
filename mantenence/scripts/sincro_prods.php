<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');

require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);  
$db->query("SET time_zone ='UTC'");
require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');



$sql="select code,id from aw.product ";
$res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
while($row=$res->fetchRow()) {
  $code=$row['code'];
  $id=$row['id'];

  $sql=sprintf("select * from aw_old.product where code like '%s'",$code);
  $res2 = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  if($row2=$res2->fetchRow()) {
    $product=new Product($id);
    $product->update(
		     array(
			   array('key'=>'description','value'=>$row2['description']),
			   array('key'=>'units','value'=>$row2['units']),
			   array('key'=>'price','value'=>$row2['price'])
			   )
		     ,'save');
    $product->update_location(array('tipo'=>'delete_all'));

    $sql="select * from aw_old.location where product_id=".$row2['id'];
    $resloc = $db->query($sql); 
    if($rowloc=$resloc->fetchRow()) {
     $loc_code=$rowloc['code'];
     if(!preg_match('/^(\d+[abcbdefgh]\d+|\d+\-\d+\-\d+)$/i',$loc_code))
       print "$loc_code\n";
    }
    
  }
  //print " $id\r";
 }




$sql="select * from aw_old.product ";
$res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
while($row=$res->fetchRow()) {
  $code=$row['code'];
  $id=$row['id'];
  $group_id=$row['group_id'];
  $sql="select * from aw_old.product_group where id=$group_id ";
  $res3 = $db->query($sql); if (PEAR::isError($res3) and DEBUG ){die($res3->getMessage());}
  $fam_data=$res3->fetchRow();

  $deparment_id=$fam_data['department_id'];

  $sql="select name as code,id from aw_old.product_department where id=$deparment_id ";
  //print "$sql\n";
  $res4 = $db->query($sql); if (PEAR::isError($res4) and DEBUG ){die($res4->getMessage());}
  $dept_data=$res4->fetchRow();
  // print_r($dept_data);

  $sql=sprintf("select id  from aw.product where code like '%s'",$code);
  $res2 = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
  if($row2=$res2->fetchRow()) {
    // print $row2['id']." $id $code\n";
  }else{
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




?>