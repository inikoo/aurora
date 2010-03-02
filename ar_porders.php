<?php
/*
 File: ar_porders.php 

 Ajax Server Anchor for the Order Class

 About: 
 Autor: Raul Perusquia <rulovico@gmail.com>
 
 Copyrigh (c) 2010, Kaktus 
 
 Version 2.0
*/
require_once 'common.php';
if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }


$tipo=$_REQUEST['tipo'];

switch($tipo){

default:
  $response=array('state'=>404,'resp'=>_('Operation not found'));
  echo json_encode($response);
}