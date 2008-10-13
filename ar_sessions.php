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
 case('update'):
   $keys=split('-',$_REQUEST['keys']);
   switch(count($keys)){
   case 1:
     $value=$_REQUEST['value'];
     $_SESSION['state'][$keys[0]]=$value;
     break;
   case 2:
     $value=$_REQUEST['value'];
     $_SESSION['state'][$keys[0]][$keys[1]]=$value;
     break;
   }
   break;

 }



?>