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
 case('country_d1'):
 $data=prepare_values($_REQUEST,array(
			     'country_2acode'=>array('type'=>'string')
			     ,'query'=>array('type'=>'string')
			     ));
  list_country_d1($data);
 break;
}
function list_country_d1($data){
$sql=sprintf("select `Geography Key`,`Country First Division Country Primary Division`,`Country First Division Name` from kbase.`Country First Division Dimension`
                where `Country First Division 2 Alpha Country Code`=%s and `Country First Division Name` like '%s%%' limit 10" 
                ,prepare_mysql($data['country_2acode'])
                ,addslashes($data['query'])
                );
$res=mysql_query($sql);
$data=array();
while($row=mysql_fetch_array($res)){
    $data[]=array('name'=>$row['Country First Division Name'],'code'=>$row['Country First Division Country Primary Division']);
}
 $response=array('data'=>
		   $data
			
		   );

   echo json_encode($response);

}
?>