<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Contact.php');

require_once 'MDB2.php';            // PEAR Database Abstraction Layer
require_once '../../common_functions.php';
$db =& MDB2::singleton($dsn);       
if (PEAR::isError($db)){echo $db->getMessage() . ' ' . $db->getUserInfo();}
if(DEBUG)PEAR::setErrorHandling(PEAR_ERROR_RETURN);
$db->setFetchMode(MDB2_FETCHMODE_ASSOC);  
require_once '../../myconf/conf.php';           
$db->query("SET time_zone ='UTC'");
date_default_timezone_set('Europe/London');
// $product=new Product(1);
// $product->load('first_date','save');
// exit;
$sql="select id from contact ";
//print "$sql\n";
$res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
while($row=$res->fetchRow()) {
  $id=$row['id'];
  $contact=new contact($id);
  //  print $contact->id." ** \n";
    
     $email_id=$contact->data['main_email'];
     if($email_id==''){
       $sql="select id from email where contact_id=".$contact->id;
       $res2 = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
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