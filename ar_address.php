<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
require_once 'common.php';
if (!$LU or !$LU->isLoggedIn()) {
  $response=array('state'=>402,'resp'=>_('Forbidden'));
  echo json_encode($response);
  exit;
 }


if(!isset($_REQUEST['tipo']))  {
    $response=array('state'=>405,'resp'=>'Non acceptable request (t)');
    echo json_encode($response);
    exit;
  }

$tipo=$_REQUEST['tipo'];
switch($tipo){
case('country'):
  $result=array();

  if(isset($_REQUEST['q']))
    $q=$_REQUEST['q'];
  else
    $q='';
  

  if($q){
    if(preg_match('/^[a-z]{3}$/i',$q)){
      $sql=sprintf("select `Country Key`,`Country Name`,`Country Code` from kbase.`Country Dimension` where `Country Code`=%s ",prepare_mysql($q));
      $res=mysql_query($sql);
      if($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
	$result[$row['Country Key']]=array('name'=>$row['Country Name'],'code'=>$row['Country Code']);
      }
    }

  }
  $_result=array();
  foreach($result as $country){
    $_result[]=$country;
  }

  echo json_encode($_result);
  break;

}


?>