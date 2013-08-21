<?php
require_once 'common.php';
//require_once '_order.php';

//require_once '_contact.php';
require_once 'class.Customer.php';
require_once 'class.Timer.php';



if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>'Non acceptable request (t)');
    echo json_encode($response);
    exit;
  }




$tipo=$_REQUEST['tipo'];
switch($tipo){


case('create_company_area'):
  create_company_area();
  break;
default:
   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
 }



function create_company_area(){
global $editor;
if( !isset($_REQUEST['values']) ){
    $response=array('state'=>400,'msg'=>'Error no value');
    echo json_encode($response);
    return;
   }
   $tmp=preg_replace('/\\\"/','"',$_REQUEST['values']);
   $tmp=preg_replace('/\\\\\"/','"',$tmp);
   $raw_data=json_decode($tmp, true);
   //print_r($raw_data);
   if(!is_array($raw_data)){
     $response=array('state'=>400,'msg'=>'Wrong value');
     echo json_encode($response);
     return;
   }
   if(!isset($raw_data['Warehouse Key'])  or !is_numeric($raw_data['Warehouse Key']) ){
     $response=array('state'=>400,'msg'=>'Wrong value');
     echo json_encode($response);
     return;
   }
   $area=new Company($raw_data['Warehouse Key']);
     if(!$warehouse->id){
     $response=array('state'=>400,'msg'=>'Wrong value');
     echo json_encode($response);
     return;
   }
   $raw_data['editor']=$editor;
   $warehouse->add_area($raw_data);
   if($warehouse->new_area){
     $response=array(
		     'state'=>200
		     ,'action'=>'created'
		     ,'msg'=>_('Area added to Warehouse')
		     );
     echo json_encode($response);
     return;
     
   }else{
     $response=array('state'=>200,'action'=>'nochange','msg'=>$warehouse->new_area_msg);
     echo json_encode($response);
     return;

   }
}






?>
