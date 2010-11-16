<?php
include_once('common.php');

$encrypted_secret_data=$_REQUEST['p'];  

if($secret_data= json_decode(AESDecryptCtr(base64_decode($encrypted_secret_data),$secret_key.$store_key,256),true)){
 
  $user_key=$secret_data['C'];
  $time=date('U')-substr($secret_data['D'],2);
  if($time>3600*24)
    $smarty->assign('expired',true);
  
  $user=new User($user_key);
  
   $_SESSION['logged_in']=true;
  $_SESSION['user_key']=$user->id;
    $_SESSION['customer_key']=$user->data['User Parent Key'];

$sql=sprintf("select `Order Key` from `Order Dimension` where `Order Customer Key`=%d and `Order Current Dispatch State`='In Process' ",$_SESSION['customer_key']);
$res=mysql_query($sql);
if($row=mysql_fetch_array($res)){
    $_SESSION['order_key']=$row['Order Key'];
}else{
    $_SESSION['order_key']=0;
  
   $_SESSION['order_data']=array(
    'items'=>0,
	       'shipping'=>0,
	        'shipping_and_handing'=>0,
	       'charges'=>0,
	       'discounts'=>0,
	       'total_net'=>0,
	       'tax'=>0,
	       'total'=>0,
  'amount_items'=>money(0),
  'amount_discounts'=>money(0),
  'amount_shipping'=>money(0),
   'amount_shipping_and_handing'=>money(0),
  'amount_charges'=>money(0),
  'amount_total_net'=>money(0),
  'amount_tax'=>money(0),
  'amount_total'=>money(0)
   
 
  );
  
  }
 
  
  
  
}else{
session_destroy();
unset($_SESSION);

}

header('Location: reset.php');
exit;


?>