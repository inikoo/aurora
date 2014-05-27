<?php

include_once('common.php');

if (!isset($_REQUEST['qty'])) {
  
    exit;
}

$_SESSION['basket']=array('qty'=>$_REQUEST['qty'],'sub'=>$_REQUEST['sub']);

print_r($_SESSION);

?>

