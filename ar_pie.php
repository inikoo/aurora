<?php
require_once 'common.php';
require_once 'class.TimeSeries.php';

if(!isset($_REQUEST['category']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$plot_type=$_REQUEST['category'];
switch($plot_type){
case('department'):
list_department_sales();
 break;
default:
   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 }


function list_department_sales(){
  $_data=array();
  
  $column='Time Series Value';
  $where=sprintf('where `Time Series Frequency`=%s and `Time Series Date`=%s and `Time Series Name`=%s  '
		 ,prepare_mysql('Monthly')
		 ,prepare_mysql('2009-01-01')
		 ,prepare_mysql('PDS')
		 );
  if(isset($_REQUEST['store_keys'])){
  
  $store_keys=$_REQUEST['store_keys'];
  
  $test=preg_replace('/\d|\s|,/','',$store_keys);
  if($test!='()')
    exit("error");
  
  $where.=" and `Time Series Name Second Key` in $store_keys";
  }
  
  $sql=sprintf("select `%s` as value,`Time Series Label` from `Time Series Dimension` %s order by `%s` desc"
	       ,$column
	       ,$where
	       ,$column
	       );
  // print $sql;
  $res=mysql_query($sql);
  $data=array();
  while($row=mysql_fetch_array($res)){
    $data[]=array('label'=>$row['Time Series Label'],'value'=>$row['value']);
  }
      
    
  $response=array('resultset'=>
		  array(
			'state'=>200,
			'data'=>$data
			)
		  );

  echo json_encode($response);
}



?>
