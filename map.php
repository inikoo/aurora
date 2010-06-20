<?php
/*
file: map.php
 */


require_once 'common.php';



if(isset($_REQUEST['country']))
  $tipo='country';




switch($tipo){
case('country'):
 
  $code=$_REQUEST['country'];
 
 $smarty->display('map.tpl');
  
  
  break;
  
  }
  
  
  
  ?>
  