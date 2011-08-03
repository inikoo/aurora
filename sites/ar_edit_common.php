<?php
//require_once 'common.php';




function is_type($type,$value){

 switch($type){
 case('numeric'):
   if(!is_numeric($value))
     return false;
   break;
 case('key'):
   if(!is_numeric($value) or $value<=0 )
     return false;
   break;
 case('no empty string'):
 case('string with value'):
   if(!is_string($value) and $value!='')
     return false;
   break;

 case('array'):
   if(!is_array($value))
     return false;
   break;




 }
 
 return true;
}


function prepare_values($data,$value_names){
  
  if(!is_array($data))
    exit(json_encode(array('state'=>400,'msg'=>'Error wrong value 1')));
  
  // print_r($data);
  
  foreach($value_names as $value_name=>$extra_data){
    $optional=false;
    $optional=(isset($extra_data['optional'])  and  $extra_data['optional']?true:false ); 
    if(!isset($data[$value_name])   ){
      
      if(!$optional){
      $response=array('state'=>400,'msg'=>"Error no value  2 ");
      echo json_encode($response);
      exit;
      }else
	continue;
      

    }
    $spected_type=$extra_data['type'];
    
    switch($spected_type){
    case('no empty string'):
    case('string with value'):
    case('string'):
    case('key'):
    case('numeric'):
      if(!is_type($spected_type,$data[$value_name]))
	exit(json_encode(array('state'=>400,'msg'=>'Error wrong value 3')));
      
      $parsed_data[$value_name]=stripslashes(urldecode($data[$value_name]));
      break;
    case('enum'):
      if(!preg_match($extra_data['valid values regex'],$data[$value_name]))
	exit(json_encode(array('state'=>400,'msg'=>"Error wroxng value 4 ".$extra_data['valid values regex']."  ")));
      
      $parsed_data[$value_name]=$data[$value_name];
      break;
    case('json array'):

      $json=$data[$value_name];

      $tmp=preg_replace('/\\\"/','"',$json);
      $tmp=preg_replace('/\\\\\"/','"',$tmp);
      $tmp=preg_replace('/\'/',"\'",$tmp);

      //print ($tmp);
      $raw_data=json_decode($tmp, true);
      //       print ($raw_data."x");

      if(is_array($raw_data)){
	if(!isset($extra_data['required elements']))
	  $extra_data['required elements']=array();
	foreach($extra_data['required elements'] as $element_name=>$element_type){
	  if(!isset($raw_data[$element_name]) or !is_type($element_type,$raw_data[$element_name]) )
	    exit(json_encode(array('state'=>400,'msg'=>'Error wrong 5 value')));
	}
	foreach($raw_data as $key=>$value){
	  if(is_string($value)) 
	     $raw_data[$key]=preg_replace('/\\\\\'/',"'",$value);
	}

	$parsed_data[$value_name]=$raw_data;
      }else
	exit(json_encode(array('state'=>400,'msg'=>'Error wrong value 6')));
      break;
    default:
      $parsed_data[$value_name]=$data[$value_name];
    }
    
  }

  return $parsed_data;
}





?>
