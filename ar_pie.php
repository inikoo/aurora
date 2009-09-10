<?php
require_once 'common.php';
require_once 'class.TimeSeries.php';

if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
  }

$plot_type=$_REQUEST['tipo'];
switch($plot_type){
case('children_share'):
  $data=array('');
  if(!isset($_REQUEST['date']) or  !isset($_REQUEST['category']) or  !isset($_REQUEST['freq']) )
    return;
  if(!preg_match('/\d{4}-\d{2}-\d{2}/',$_REQUEST['date']))
    return;
  else
    $data['date']=$_REQUEST['date'];

  if(!preg_match('/(Yearly|Monthly|Quarterly|Weekly)/',$_REQUEST['freq']))
    exit("");
  else
    $data['freq']=$_REQUEST['freq'];
 
  if(!preg_match('/(PDS|PFS|PcodeS)/',$_REQUEST['category']))
    return;
  else
    $data['category']=$_REQUEST['category'];
  

  if(isset($_REQUEST['value_tipo']) and preg_match('/count/i',$_REQUEST['value_tipo']))
    $data['value_tipo']='count';
  else
    $data['value_tipo']='value';

 if(isset($_REQUEST['forecast']) and preg_match('/(yes|1)/i',$_REQUEST['forecast']))
    $data['forecast']=true;
  else
    $data['forecast']=false;


  $data['parent_keys']='';
  if(isset($_REQUEST['parent_keys'])){
    $parent_keys=$_REQUEST['parent_keys'];
    $test=preg_replace('/\d|\s|,/','',$parent_keys);
    if($test!='()')
      exit("error");
    $data['parent_keys']=$parent_keys;
  }

 

list_children_share($data);
 break;
default:
   $response=array('state'=>404,'resp'=>_('Operation not found'));
   echo json_encode($response);
   
 }


function list_children_share($conf_data){
  $_data=array();
  
  $column='Time Series Value';
  $where=sprintf('where `Time Series Frequency`=%s and `Time Series Date`=%s and `Time Series Name`=%s  '
		 ,prepare_mysql($conf_data['freq'])
		 ,prepare_mysql($conf_data['date'])
		 ,prepare_mysql($conf_data['category'])
		 );
  
  if($conf_data['forecast']){
    $where.=" and `Time Series Type`='Forecast'" ;
  }else
    $where.=" and `Time Series Type` in ('Data','First','Current') " ;
  
  if($conf_data['parent_keys']!=''){
    $where.=" and `Time Series Parent Key` in ".$conf_data['parent_keys'];
  }
  $sql=sprintf("select `Time Series Type`,`Time Series Value` as value, `Time Series Count` as count,`Time Series Label` from `Time Series Dimension` %s order by `%s` desc"
	     
	       ,$where
	       ,$column
	       );
  // print $sql." | ".$conf_data['value_tipo']." |";
  $res=mysql_query($sql);
  $data=array();
  while($row=mysql_fetch_array($res)){
    $data[]=array('label'=>$row['Time Series Label'],'value'=>$row[$conf_data['value_tipo']]);
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
