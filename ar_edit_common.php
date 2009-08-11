<?php

get_array_from_json($json){
  $tmp=preg_replace('/\\\"/','"',$json);
  $tmp=preg_replace('/\\\\\"/','"',$tmp);
  
  if(!is_array($raw_data)){
     $response=array('state'=>400,'msg'=>'Wrong value');
     echo json_encode($response);
     exit;
  }
  return json_decode($tmp, true);


}

check_requiered_values($values_names){
  foreach($value_namesa as $value_name=>$spected_type){
    if(!isset($_REQUEST[$value_name])){
      $response=array('state'=>400,'msg'=>'Error no value');
      echo json_encode($response);
      exit;
    }
    switch($spected_type){
    case('numeric'):
      if(!is_numeric($_REQUEST[$value_name])){
	$response=array('state'=>400,'msg'=>'Error wrong type');
      echo json_encode($response);
      exit;
      }
      break;
    case('valid key'):
      if(!is_numeric($_REQUEST[$value_name])  or $_REQUEST[$value_name] <=0   ){
	$response=array('state'=>400,'msg'=>'Error wrong type');
      echo json_encode($response);
      exit;
      }
      break;
    case('string'):
      if(!is_numeric($_REQUEST[$value_name])  or $_REQUEST[$value_name] <=0   ){
	$response=array('state'=>400,'msg'=>'Error wrong type');
      echo json_encode($response);
      exit;
      }
      break;
    case('json array'):
      $json=$_REQUEST[$value_name];
      $tmp=preg_replace('/\\\"/','"',$json);
      $tmp=preg_replace('/\\\\\"/','"',$tmp);
      $raw_data=json_decode($tmp, true);
      if(is_array($raw_data)){
	$_REQUEST[$value_name]=$raw_data;
      }else{
	$response=array('state'=>400,'msg'=>'Error wrong type');
	echo json_encode($response);
	exit;

      }


    }
}


check_data($data,$extra='';){
if( !isset($data) ){
    $response=array('state'=>400,'msg'=>'Error no value');
    echo json_encode($response);
    exit;
   }

if(preg_match('/valid key/i',$extra)){
  if( !is_numeric($data) or $data<=0  ){
    $response=array('state'=>400,'msg'=>'Error wrong valid key');
     echo json_encode($response);
     return;
   }
  
}


}


?>