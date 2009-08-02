<?php
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

 case('change_front'):
   if(isset($_REQUEST['value'])){
     $value=$_REQUEST['value'];
     $_SESSION['views']['reports_front']=$value;


   }
   break;
case('change_front_plot'):
   if(isset($_REQUEST['value'])  ){
     $value=$_REQUEST['value'];

     $_SESSION['views']['reports_front_plot'][$_SESSION['views']['reports_front']]=$value;


   }
   break;
default:

   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 }



?>