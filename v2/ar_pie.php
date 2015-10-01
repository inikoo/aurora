<?php
require_once 'common.php';
require_once 'class.TimeSeries.php';

if(!isset($_REQUEST['tipo']))
  {
    $response=array('state'=>405,'resp'=>'Non acceptable request (t)');
    echo json_encode($response);
    exit;
  }

$plot_type=$_REQUEST['tipo'];
switch($plot_type){
case('children_share'):
  $data=array('');
  if(!isset($_REQUEST['from']) or !isset($_REQUEST['to']) or !isset($_REQUEST['ts_name']) or  !isset($_REQUEST['freq']) ){
    exit('E1');
    }
  if(!preg_match('/^(\d{4}-\d{2}-\d{2}|)$/',$_REQUEST['from']) )
    exit('E2');
  else
    $data['from']=$_REQUEST['from'];
  if(!preg_match('/^(\d{4}-\d{2}-\d{2}|)$/',$_REQUEST['to']))
   exit('E3');
  else
    $data['to']=$_REQUEST['to'];


  if(!preg_match('/(Yearly|Monthly|Quarterly|Weekly|All)/',$_REQUEST['freq']))
    exit("E4");
  else
    $data['freq']=$_REQUEST['freq'];
 
  if(!preg_match('/(PDS|PFS|PcodeS|PDP|PFP|PcodeP)/',$_REQUEST['ts_name']))
    return;
  else
    $data['ts_name']=$_REQUEST['ts_name'];
  

  if(isset($_REQUEST['value_tipo']) and preg_match('/count/i',$_REQUEST['value_tipo']))
    $data['value_tipo']='count';
  else
    $data['value_tipo']='value';

 if(isset($_REQUEST['forecast']) and preg_match('/(yes|1)/i',$_REQUEST['forecast']))
    $data['forecast']=true;
  else
    $data['forecast']=false;

 if($data['freq']=='Weekly'){
 if(isset($_REQUEST['yearweek'])  and   preg_match('/^\d{6}$/',$_REQUEST['yearweek']) )
   $data['yearweek']=$_REQUEST['yearweek'];
  else
    return;
 
 }


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
//  if($conf_data['freq']=='All'){
//    $where=sprintf('where `Time Series Frequency`="Year" and `Time Series Name`=%s  '

//		 ,prepare_mysql($conf_data['ts_name'])
//		 );
 // }else if($conf_data['freq']=='Weekly'){
  //  $where=sprintf('where `Time Series Frequency`=%s and YEARWEEK(`Time Series Date`)=%s and `Time Series Name`=%s  '
	//	 ,prepare_mysql($conf_data['freq'])
//		 ,prepare_mysql($conf_data['yearweek'])
//		 ,prepare_mysql($conf_data['ts_name'])
//		 );
 // }else
 $from=$conf_data['from'];
  $to=$conf_data['to'];

       $where_from='';
if($from)
$where_from=sprintf('and `Time Series Date`>=%s ',prepare_mysql($from));
$where_to='';
if($to)
$where_to=sprintf('and `Time Series Date`<=%s ',prepare_mysql($to));




 
 
    $where=sprintf('where `Time Series Frequency`=%s  and `Time Series Name`=%s  %s %s'
		 ,prepare_mysql($conf_data['freq'])
		 ,prepare_mysql($conf_data['ts_name'])
		 ,$where_from
		 ,$where_to
		 );
  
  if($conf_data['forecast']){
    $where.=" and `Time Series Type`='Forecast'" ;
  }else
    $where.=" and `Time Series Type` in ('Data','First','Current') " ;
  
  
  if($conf_data['parent_keys']!=''){
    $where.=" and `Time Series Parent Key` in ".$conf_data['parent_keys'];
  }




 // if($conf_data['freq']=='All'){
    $sql=sprintf("select sum(`Time Series Value`) as value, sum(`Time Series Count`) as count,`Time Series Label` from `Time Series Dimension` %s group by `Time Series Name Key`   order by sum(`Time Series Value`)   desc"
	     
	       ,$where
	       ,$column
	       );
  //}else{
  //  $sql=sprintf("select `Time Series Type`,`Time Series Value` as value, `Time Series Count` as count,`Time Series Label` from `Time Series Dimension` %s order by `%s` desc"
	     
	//       ,$where
	//       ,$column
	//       );
 // }
 


//  print $sql." | ".$conf_data['value_tipo']." |";
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
