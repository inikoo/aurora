<?
require_once 'common.php';

if (!$LU or !$LU->isLoggedIn()) {
  $response=array('state'=>402,'resp'=>_('Forbidden'));
  echo json_encode($response);
  exit;
 }


if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$tipo=$_REQUEST['tipo'];
switch($tipo){

 case('product'):
 case('product_manage_stock'):
 case('edit_product'):
  if($tipo=='product_manage_stock')
    $target='product_manage_stock.php';
  else
   $target='product.php';

  $q=$_REQUEST['q'];
   $sql=sprintf("select id from product where code='%s' ",addslashes($q));
   $result =& $db->query($sql);
   if($found=$result->fetchRow()){
     $url=$target.'?id='. $found['id'];
     echo json_encode(array('state'=>200,'url'=>$url));
     break;
   }
   
   if($tipo=='product'){
   $sql=sprintf("select id from product_group where name='%s' ",addslashes($q));
   $result =& $db->query($sql);
   if($found=$result->fetchRow()){
     $url='family.php?id='. $found['id'];
     echo json_encode(array('state'=>200,'url'=>$url));
     break;
   }
   }
   // try to get similar results 
   //   if($myconf['product_code_separator']!=''){
   if(  ($myconf['product_code_separator']!='' and   preg_match('/'.$myconf['product_code_separator'].'/',$q)) or  $myconf['product_code_separator']==''  ){
     $sql=sprintf("select levenshtein(UPPER(%s),UPPER(code)) as dist1,    levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(code))) as dist2,        code,id from product  order by dist1,dist2 limit 1;",prepare_mysql($q),prepare_mysql($q));
     $result =& $db->query($sql);
     if($found=$result->fetchRow()){
       if($found['dist1']<3){
	 echo json_encode(array('state'=>400,'msg1'=>_('Did you mean'),'msg2'=>'<a href="'.$target.'?id='.$found['id'].'">'.$found['code'].'</a>'));
	 break;
       }
     }
    
     
   }elseif($tipo=='product'){
     // look on the family list
     $sql=sprintf("select levenshtein(UPPER(%s),UPPER(name)) as dist1,    levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(name))) as dist2, name ,id from product_group  order by dist1,dist2 limit 1;",prepare_mysql($q),prepare_mysql($q));
     $result =& $db->query($sql);
     if($found=$result->fetchRow()){
       if($found['dist1']<3){
	 echo json_encode(array('state'=>400,'msg1'=>_('Did you mean'),'msg2'=>'<a href="family.php?id='.$found['id'].'">'.$found['name'].'</a> '._('family') ));
	 break;
       }
     }
     
   }
   echo json_encode(array('state'=>500,'msg'=>_('Product not found')));
   break;
case('location'):
   $q=$_REQUEST['q'];
   $sql=sprintf("select id from location where name='%s' ",addslashes($q));
   $result =& $db->query($sql);
   if($found=$result->fetchRow()){
     $url='location.php?id='. $found['id'];
     echo json_encode(array('state'=>200,'url'=>$url));
     break;
   }

   $sql=sprintf("select levenshtein(UPPER(%s),UPPER(name)) as dist1,    levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(name))) as dist2,name,id from location  order by dist1,dist2 limit 1;",prepare_mysql($q),prepare_mysql($q));
   $result =& $db->query($sql);
   if($found=$result->fetchRow()){
     if($found['dist1']<3){
       echo json_encode(array('state'=>400,'msg1'=>_('Did you mean'),'msg2'=>'<a href="location.php?id='.$found['id'].'">'.$found['name'].'</a>'));
       break;
     }
   }
   echo json_encode(array('state'=>500,'msg'=>_('Location not found')));
   break;
 case('customer_name'):
   $target='customer.php';
   $q=$_REQUEST['q'];
   $sql=sprintf("select id,name from customer where name=%s ",prepare_mysql($q));
   $result =& $db->query($sql);
   
 $number_results=$result->numRows();
 if($number_results==1){
     if($found=$result->fetchRow()){
       $url=$target.'?id='. $found['id'];
       echo json_encode(array('state'=>200,'url'=>$url));
       break;
     }
   }elseif($number_results>1){
     $url='';
     while($found=$result->fetchRow()){
       $url.=sprintf('<href="%s?id=%d">%s (%d)</a><br>',$target,$found['name'],$found['id'],$found['id']);
       
     }
     
     echo json_encode(array('state'=>200,'url'=>$url));
     break;
     echo json_encode(array('state'=>400,'msg1'=>_('There are')." $number_results "._('customers with this name'),'msg2'=>$url));
     break;

   }





   // try to find aprox names
   
   $sql=sprintf("select levenshtein(UPPER(%s),UPPER(name))/LENGTH(name) as dist1,    levenshtein(UPPER(SOUNDEX(%s)),UPPER(SOUNDEX(name))) as dist2,name,id from customer  order by dist1,dist2 limit 5;",prepare_mysql($q),prepare_mysql($q));
   $result =& $db->query($sql);
   // print $sql;
   $msg2='';
   while($found=$result->fetchRow()){
     if($found['dist1']<.5){
       $msg2.=sprintf(', <a href="%s?id=%d">%s</a>',$target,$found['id'],$found['name']);
     }
   }
   if($msg2!=''){
     $msg2=preg_replace('/^\,\s*/','',$msg2);
     echo json_encode(array('state'=>400,'msg1'=>_('Did you mean').":",'msg2'=>$msg2));
     break;
   }

   echo json_encode(array('state'=>500,'msg'=>_('Customer not found')));
   break;




 default:
   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);

 }

?>