<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Customer.php');


require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::factory($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);  
$db->query("SET time_zone ='UTC'");
require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');

$sql="select customer_id,id,date_creation,date_processed,date_invoiced,date_dispached from orden ";
//print $sql;
$res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
while($row=$res->fetchRow()) {
  $id=$row['id'];
  $customer_id=$row['customer_id'];
  
  $date_creation=$row['date_creation'];
  $date_processed=$row['date_processed'];
  $date_invoiced=$row['date_invoiced'];
  $date_dispached=$row['date_dispached'];

  $customer=new Customer($customer_id);
  if($customer->id){


    if($date_creation!='' and $date_creation!=$date_processed){
      $data=array(
		  'action'=>'creation',
		  'date'=>$date_creation,
		  'order_id'=>$id
		  );
      $customer->save_history('order','','',$data);
    }
    
    if($date_processed!=''){
      $data=array(
		  'action'=>'processed',
		  'date'=>$date_processed,
		  'order_id'=>$id
		  );
      // print_r($data);
       $customer->save_history('order','','',$data);
       //print $customer->msg;
    }

    if($date_invoiced!=''){
      $data=array(
		  'action'=>'invoiced',
		  'date'=>$date_invoiced,
		  'order_id'=>$id
		  );
       $customer->save_history('order','','',$data);
    }
    

  }else{
    print "Error customer not found customer_id:$customer_id order id:$id";
  }

  
 }

?>