<?php
include_once('../../conf/dns.php');
include_once('../../class.Contact.php');

require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::singleton($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
  
require_once '../../myconf/conf.php';           
mysql_query("SET time_zone ='+0:00'");
date_default_timezone_set('UTC');
// $product=new Product(1);
// $product->load('first_date','save');
// exit;
$sql="select id from contact ";
//print "$sql\n";
$res=mysql_query($sql);if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  $id=$row['id'];
  $contact=new contact($id);
  //  print $contact->id." ** \n";
    
     $email_id=$contact->data['main_email'];
     if($email_id==''){
       $sql="select id from email where contact_id=".$contact->id;
       $res2 = mysql_query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
	if($row2=$res2->fetchRow()) {
 	  $data[]=array('key'=>'main_email','value'=>$row2['id']);
 	  $r=$contact->update($data);
 	  if($r['main_email']['ok']){
 	    $contact->save('main_email');
	    
	    printf("Contact: $id  -> main email normalized  \r");
	  }
 	}
     }

 }

?>