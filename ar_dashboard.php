<?php
	include_once('common.php');
	/*
	$name       = addSlashes($_POST['name']);
	$email      = $_POST['email'];
	$comment    = addSlashes($_POST['comment']);
	//$date_added = time();

	$check = mysql_query("insert into `Comment Dimension`(`Name`,`Email`,`Comment`) values('$name','$email','$comment')");
	
	//$date_added = date("l j F Y, g:i a",time());
	
	$sel = "select * from `Comment Dimension`";
	$res = mysql_query($sel);
	$rr = mysql_fetch_array($res);

 	$date_added = $rr['Date Added'];
	
	if($check)
		echo $date_added;
	else
		echo "0";
*/
if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('add_dashboard'):
    addDashboard();
    break;

case('delete_dashboard'):
    deleteDashboard();
    break;

case('set_default_dashboard'):
    set_default_dashboard();
    break;

case('list_widgets'):
    list_widgets();
    break;

case('delete_widget_list'):
    delete_widget();
    break;

case('add_widget'):
    add_widget();
    break;

case('view_widgets'):
    view_widgets();
    break;

case('add_widget_list'):
    add_widget();
    break;

default:

    $response=array('state'=>404,'msg'=>_('Operation not found'));
    echo json_encode($response);

}

function add_widget(){
	$user_id=$_REQUEST['user_id'];
	$dashboard_id=$_REQUEST['dashboard_id'];
	$widget_id=$_REQUEST['id'];

	$sql=sprintf("select * from `Dashboard Widget Dimension` where `dashboard Widget Key`=%d", $widget_id);
	$result=mysql_query($sql);
	if($row=mysql_fetch_array($result, MYSQL_ASSOC)){
		$dashboard_class=$row['Widget Block'];
		$dashboard_url=$row['Widget URL'];
		$dashboard_height=$row['Widget Dimension'];
	}

	
	$sql=sprintf("select `Dashboard Active` from `Dashboard User Bridge` where `User key`=%d and `Dashboard ID`=%d order by `Dashboard Key`", $user_id, $dashboard_id);
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	$dashboard_active=$row['Dashboard Active'];

	$sql=sprintf("select `Dashboard Key` from `Dashboard User Bridge` where `Dashboard Widget Key`=%d and `User Key`=%d and `Dashboard ID`=%d", 0, $user_id, $dashboard_id);
	$result=mysql_query($sql);
	$num_res=mysql_num_rows($result);
	if($num_res > 0){
		$row=mysql_fetch_assoc($result);
		$sql=sprintf("delete from `Dashboard User Bridge` where `Dashboard Key`=%d", $row['Dashboard Key']);
		mysql_query($sql);
	}
	

	$sql=sprintf("insert into `Dashboard User Bridge` (`User key`, `Dashboard ID`, `Dashboard Class`, `Dashboard URL`, `Dashboard Widget Key`, `Dashboard Height`, `Dashboard Active`) values (%d, %d, %s, %s, %d, %d, %s)", $user_id, $dashboard_id, prepare_mysql($dashboard_class), prepare_mysql($dashboard_url), $widget_id, $dashboard_height, prepare_mysql($dashboard_active));

//	print $sql;

	if(mysql_query($sql)){
		$response=array('state'=>200, 'action'=>_('deleted'), 'msg'=>'Widget Added');
		echo json_encode($response);
		exit;	
	}
	else{
		$response=array('state'=>400, 'msg'=>_('Error Adding Widget'));
		echo json_encode($response);	
		exit;	
	}
}

function delete_widget(){
	$key=$_REQUEST['key'];

	$sql=sprintf("delete from `Dashboard User Bridge` where `Dashboard Key`=%d", $key);
	if(mysql_query($sql)){
		$response=array('state'=>200, 'action'=>_('deleted'));
		echo json_encode($response);
		exit;
	}
	else{
		$response=array('state'=>400, 'msg'=>_('Cannot Delete'));
		echo json_encode($response);	
		exit;	
	}
	


	
}

function view_widgets(){
	$dashboard_id=$_REQUEST['dashboard_id'];
	$user_id=$_REQUEST['user_id'];
	$sql=sprintf("select * from `Dashboard Widget Dimension`");
	$result=mysql_query($sql);

	$total=mysql_num_rows($result);
	$tableid=0;

	while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
        $adata[]=array(
                     'id'=>$row['Dashboard Widget Key'],
                     'name'=>$row['Widget Name'],
                     'widget_block'=>$row['Widget Block'],
                     'widget_dimesnion'=>$row['Widget Dimension'],
                     'url'=>$row['Widget URL'],
                     'description'=>$row['Widget Description'],
		     'add'=>'<img src="art/icons/add.png"/>',
		     'user_id'=>$user_id,
             	     'dashboard_id'=>$dashboard_id

                 );

	}
	mysql_free_result($result);	
    $response=array('resultset'=>
                                array(
                                    'state'=>200,
                                    'data'=>$adata,
                                    'rtext'=>'',
                                    'rtext_rpp'=>'',
                                    'sort_key'=>'',
                                    'sort_dir'=>'',
                                    'tableid'=>$tableid,
                                    'filter_msg'=>'',
                                    'total_records'=>$total,
                                    'records_offset'=>'',
                                    'records_perpage'=>'10',
                                    'records_order'=>'',
                                    'records_order_dir'=>'',
                                    'filtered'=>''
                                )
                   );
    echo json_encode($response);
			
	
}

function list_widgets(){
	$user_id=$_REQUEST['user_id'];
	$sql=sprintf("select `Dashboard ID` from `Dashboard User Bridge` where `User Key`=%d and `Dashboard Active`='Yes'", $user_id);
	//print $sql;
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	$default_dashboard=$row['Dashboard ID'];
	

	if (isset( $_REQUEST['dashboard']))
		$dashboard=$_REQUEST['dashboard'];
	else {
		$dashboard=$default_dashboard;
	}

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	$sql=sprintf("select count(Distinct `Dashboard Widget Key`) as total from `Dashboard User Bridge` where `User Key`=%d and `Dashboard ID`=%d", $user_id, $dashboard);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}

	$rtext_rpp=_("Showing all widgets");
	$rtext=$total." ".ngettext('widget','widgets',$total);


	$sql=sprintf("select * from `Dashboard User Bridge` where `User Key`=%d and `Dashboard ID`=%d and `Dashboard Widget Key`!=%d", $user_id, $dashboard, 0);
	//	print $sql;

	$result=mysql_query($sql);

	$adata=array();
	while($_row=mysql_fetch_array($result, MYSQL_ASSOC)){
	
	$widget_id=$_row['Dashboard Widget Key'];
	
	$sql=sprintf("select * from `Dashboard Widget Dimension` where `Dashboard Widget Key`=%d", $widget_id);
	//print $sql;
	$_result=mysql_query($sql);
	$row=mysql_fetch_assoc($_result);
	

        $adata[]=array(
                     'id'=>$row['Dashboard Widget Key'],
                     'name'=>$row['Widget Name'],
                     'widget_block'=>$row['Widget Block'],
                     'widget_dimesnion'=>$row['Widget Dimension'],
                     'url'=>$row['Widget URL'],
                     'description'=>$row['Widget Description'],
		     'delete'=>'<img src="art/icons/cross.png"/>',
		     'user_id'=>$_row['User key'],
             	     'dashboard_id'=>$_row['Dashboard ID'],
		     'key'=>$_row['Dashboard Key']

                 );

	}
	mysql_free_result($result);

//print_r($adata);


    $response=array('resultset'=>
                                array(
                                    'state'=>200,
                                    'data'=>$adata,
                                    'rtext'=>'',
                                    'rtext_rpp'=>'',
                                    'sort_key'=>'',
                                    'sort_dir'=>'',
                                    'tableid'=>$tableid,
                                    'filter_msg'=>'',
                                    'total_records'=>$total,
                                    'records_offset'=>'',
                                    'records_perpage'=>'5',
                                    'records_order'=>'',
                                    'records_order_dir'=>'',
                                    'filtered'=>''
                                )
                   );
    echo json_encode($response);

	
}

function set_default_dashboard(){
	$user_id=$_REQUEST['user_id'];
	$dashboard_id=$_REQUEST['dashboard_id'];

	$sql=sprintf("update `Dashboard User Bridge` set `Dashboard Active`='No' where `User key`=%d", $user_id);
	mysql_query($sql);

	$sql=sprintf("update `Dashboard User Bridge` set `Dashboard Active`='Yes' where `User key`=%d and `Dashboard ID`=%d", $user_id, $dashboard_id);
	//print $sql;
	mysql_query($sql);

	$response=array('state'=>200, 'msg'=>_('Dashboard Selected'));
	echo json_encode($response);
}

function deleteDashboard(){
	$user_id=$_REQUEST['user_id'];
	$dashboard_id=$_REQUEST['dashboard_id'];

	$sql=sprintf("select * from `Dashboard User Bridge` where `User Key`=%d group by `Dashboard ID`", $user_id);

	//print $sql;
	$result=mysql_query($sql);
	$count=mysql_num_rows($result);
	
	if($count == 1){
		$response=array('state'=>400, 'msg'=>_('Dashboard cannot be Deleted'));
		echo json_encode($response);
		exit;
	}

	$sql=sprintf("delete from `Dashboard User Bridge` where `User Key`=%d and `Dashboard ID`=%d", $user_id, $dashboard_id);
	
	mysql_query($sql);
	
	$response=array('state'=>200, 'msg'=>_('Dashboard Deleted'));
	echo json_encode($response);
}

function addDashboard(){

	$user_id=$_REQUEST['id'];
	$sql=sprintf("select * from `Dashboard User Bridge` where `User Key`=%d order by `Dashboard ID` DESC",$user_id);
	//print $sql;exit;
	$res=mysql_query($sql);
	$row=mysql_fetch_assoc($res);
	$last_dashboard_id=$row['Dashboard ID'];

	$sql=sprintf("select count(*) from `Dashboard User Bridge` where `User Key`=%d group by `Dashboard ID` DESC",$user_id);
	$result=mysql_query($sql);
	$no_dashboards=mysql_num_rows($result);
	

	if($no_dashboards>=5){
		$response=array('state'=>400, 'msg'=>_('Maximum Allowed Dashboards Exceeded'));
		echo json_encode($response);
		exit;
	}
	
	//print $last_dashboard_id;
	$number_of_modules=3;
	$sql=sprintf("insert into `Dashboard User Bridge` (`User Key`,`Dashboard ID`) values (%d, %d)", $user_id, $last_dashboard_id+1);
	//for($i=0; $i<$number_of_modules; $i++){
		mysql_query($sql);
	//}
	
	$response=array('state'=>200, 'msg'=>_('Dashboard Added'));
	echo json_encode($response);
}
?>
