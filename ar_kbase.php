<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
require_once 'common.php';
require_once 'ar_edit_common.php';

if(!isset($_REQUEST['tipo']))  {
  $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
  echo json_encode($response);
  exit;
}

$tipo=$_REQUEST['tipo'];
switch($tipo){

case('add_european_union_countries'):

  $data=prepare_values($_REQUEST,array(
				       'current_countries'=>array('type'=>'string')
				       )
		       );
  add_european_union_countries($data);
  break;
case('country_d1'):
  $data=prepare_values($_REQUEST,array(
				       'query'=>array('type'=>'string')
				       ,'country_2acode'=>array('type'=>'string','optional'=>true)
				       )
		       );
  list_country_d1($data);
  break;
case('country_d2'):
  $data=prepare_values($_REQUEST,array('query'=>array('type'=>'string')
				       ,'country_2acode'=>array('type'=>'string','optional'=>true)
				       ,'country_d1_code'=>array('type'=>'string','optional'=>true)
				       
				       )
		       );
  list_country_d2($data);
  break;
case('country_d3'):
  $data=prepare_values($_REQUEST,array('query'=>array('type'=>'string')
				         ,'country_2acode'=>array('type'=>'string','optional'=>true)
				       ,'country_d1_code'=>array('type'=>'string','optional'=>true)
				       ,'country_d2_code'=>array('type'=>'string','optional'=>true)
				       ));
  list_country_d3($data);
  break;
case('country_d4'):
  $data=prepare_values($_REQUEST,array('query'=>array('type'=>'string')
				       ,'country_2acode'=>array('type'=>'string','optional'=>true)
				       ,'country_d1_code'=>array('type'=>'string','optional'=>true)
				       ,'country_d2_code'=>array('type'=>'string','optional'=>true)
				       ,'country_d3_code'=>array('type'=>'string','optional'=>true)
				       ));
  list_country_d4($data);
  break;

case('town'):
  $data=prepare_values($_REQUEST,array(
				       'query'=>array('type'=>'string')
				       ,'country_2acode'=>array('type'=>'string','optional'=>true)
				       ,'country_d1_code'=>array('type'=>'string','optional'=>true)
				       ,'country_d2_code'=>array('type'=>'string','optional'=>true)
				       ,'country_d3_code'=>array('type'=>'string','optional'=>true)
				       ,'country_d4_code'=>array('type'=>'string','optional'=>true)
				       )
		       
		       );
  list_town($data);
  break;
case('currency'):
  $data=prepare_values($_REQUEST,array(
				       'query'=>array('type'=>'string')
  
				       )
		       );
  list_currencies($data);

  break;


}

function list_currencies($data){
 $sql=sprintf("select * from kbase.`Currency Dimension`
                where `Currency Code` like '%s%%' or `Currency Name` like '%%%s%%'   or `Currency Symbol`=%s limit 10" 
	      ,addslashes($data['query'])
	      ,addslashes($data['query'])
	      ,addslashes($data['query'])
	       );
  $res=mysql_query($sql);
  $data=array();
  while($row=mysql_fetch_array($res)){
    $data[]=array('name'=>$row['Country Name'],'code'=>$row['Currercy Code']);
  }
  $response=array('data'=>$data);
  echo json_encode($response);
}


function list_country_d1($data){
  $extra_where='';
  if(isset($data['country_2acode']))
    $extra_where.=sprintf(" and  `Country 2 Alpha Code`=%s",prepare_mysql($data['country_2acode']));
  $sql=sprintf("select `Geography Key`,`Country First Division Code`,`Country First Division Name` from kbase.`Country First Division Dimension`
                where `Country First Division Name` like '%s%%' %s limit 10" 
	       ,addslashes($data['query'])
	       ,$extra_where
	       );
	       
	     //  print $sql;
  $res=mysql_query($sql);
  $data=array();
  while($row=mysql_fetch_array($res)){
    $data[]=array('name'=>$row['Country First Division Name'],'code'=>$row['Country First Division Code']);
  }
  $response=array('data'=>$data);
  echo json_encode($response);
}
function list_country_d2($data){
  $extra_where='';
  if(isset($data['country_2acode']))
    $extra_where.=sprintf(" and  `Country 2 Alpha Code`=%s",prepare_mysql($data['country_2acode']));
  if(isset($data['country_d1_code']) and $data['country_d1_code']!='')
    $extra_where.=sprintf(" and  `Country First Division Code`=%s",prepare_mysql($data['country_d1_code']));
  
  $sql=sprintf("select `Geography Key`,`Country Second Division Code`,`Country Second Division Name` from kbase.`Country Second Division Dimension`
                where `Country Second Division Name` like '%s%%' %s limit 10" 
	       ,addslashes($data['query'])
	       ,$extra_where
	       );
  // print $sql;
  $res=mysql_query($sql);
  $data=array();
  while($row=mysql_fetch_array($res)){
    $data[]=array('name'=>$row['Country Second Division Name'],'code'=>$row['Country Second Division Code']);
  }
  $response=array('data'=>$data);
  echo json_encode($response);
}




function list_town($data){

  $extra_where='';
  if(isset($data['country_2acode']) and $data['country_2acode']!='')
    $extra_where.=sprintf(" and  `Country 2 Alpha Code`=%s",prepare_mysql($data['country_2acode']));
  if(isset($data['country_d1_code']) and $data['country_d1_code']!='')
    $extra_where.=sprintf(" and  `Country First Division Code`=%s",prepare_mysql($data['country_d1_code']));
  if(isset($data['country_d2_code']) and $data['country_d2_code']!='')
    $extra_where.=sprintf(" and  `Country Second Division Code`=%s",prepare_mysql($data['country_d2_code']));
  if(isset($data['country_d3_code']) and $data['country_d3_code']!='')
    $extra_where.=sprintf(" and  `Country Third Division Code`=%s",prepare_mysql($data['country_d3_code']));
  if(isset($data['country_d4_code']) and $data['country_d4_code']!='')
    $extra_where.=sprintf(" and  `Country Forth Division Code`=%s",prepare_mysql($data['country_d4_code']));


  $sql=sprintf("select `Geography Key`,`Town Name` from kbase.`Town Dimension`
                where `Town Name` like '%s%%'  %s order by `Town Name` desc  limit 10" 
	       ,addslashes($data['query'])
	       ,$extra_where
	       );
  //print $sql;
  $res=mysql_query($sql);
  $data=array();
  while($row=mysql_fetch_array($res)){
    $data[]=array('name'=>$row['Town Name']);
  }
  $response=array('data'=>
		  $data
			
		  );

  echo json_encode($response);

}

function add_european_union_countries($data){
	
	$value='';
	$sql=sprintf("select `Country Code` from kbase.`Country Dimension`  where `European Union`='Yes'  ");
  	$res=mysql_query($sql);
  	$data=array();
  	while($row=mysql_fetch_array($res)){
    	$value.=','.$row['Country Code'];
  	}
	$value=preg_replace('/^\,/','',$value);
	
	
	
	$response=array('state'=>200,'geo_constraints'=>$value);
    	echo json_encode($response);
	
	
}

?>