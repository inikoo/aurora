<?php
include_once('../../conf/dns.php');
include_once('../../class.Customer.php');

require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::singleton($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
  
require_once '../../myconf/conf.php';           

date_default_timezone_set('UTC');
// $product=new Product(1);
// $product->load('first_date','save');
// exit;
$sql="select id from customer";
//print "$sql\n";
$res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  $id=$row['id'];
  $customer=new customer($id);
  //  print $customer->id." ** \n";
    
     $email_id=$customer->data['main_email'];
      if($email_id==''){
	if(is_numeric($customer->data['contact_id']) and $customer->data['contact_id']>0){
	  $contact=new contact($customer->data['contact_id']);
	  if(is_numeric($contact->data['main_email']) and $contact->data['main_email']>0){
	    $email_id=$contact->data['main_email'];
	    //print "E: $email_id \n";
	    if(is_numeric($email_id) and $email_id>0){
	    
	      $data[]=array('key'=>'main_email','value'=>$email_id);
	      $r=$customer->update($data);
	      if($r['main_email']['ok']){
		$customer->save('main_email');
		printf(" Customer $id  -> main email normalized  \r");
	      }
	    }
	  }
	}
	
      }
      
 }

?>