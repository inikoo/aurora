<?php
include_once 'common.php';
require_once 'ar_edit_common.php';


if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
	echo json_encode($response);
	exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('add_dashboard'):
	$data=prepare_values($_REQUEST,array(
			'user_key'=>array('type'=>'key'),
		));
	new_dashboard($data);
	break;

case('delete_dashboard'):
	$data=prepare_values($_REQUEST,array(
			'dashboard_key'=>array('type'=>'key'),
			'user_key'=>array('type'=>'key'),
		));
	delete_dashboard($data);
	break;

case('set_default_dashboard'):
	$data=prepare_values($_REQUEST,array(
			'dashboard_key'=>array('type'=>'key'),
			'user_key'=>array('type'=>'key'),
		));
	set_default_dashboard($data);
	break;

case('list_widgets'):
	list_widgets();
	break;

case('delete_widget_list'):
	delete_widget();
	break;

case('add_widget_to_dashboard'):
	$data=prepare_values($_REQUEST,array(
			'dashboard_key'=>array('type'=>'key'),
			'widget_key'=>array('type'=>'key'),
		));
	add_widget_to_dashboard($data);
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

function add_widget_to_dashboard($data) {

	$dashboard_key=$data['dashboard_key'];
	$widget_key=$data['widget_key'];

	$sql=sprintf("select * from  `Widget Dimension` where `Widget Key`=%d", $widget_key);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

	}else {
		$response=array('state'=>400, 'msg'=>'Widget not found');
		echo json_encode($response);
		exit;

	}

	$sql=sprintf("select * from  `Dashboard Dimension` where `Dashboard Key`=%d", $dashboard_key);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

	}else {
		$response=array('state'=>400, 'msg'=>'Dashboard not found');
		echo json_encode($response);
		exit;

	}




	$sql=sprintf("insert into `Dashboard Widget Bridge` ( `Dashboard ID`,`Widget Key`) values (%d, %d)",
		$dashboard_key,
		$widget_key
	);

	// print $sql;

	if (mysql_query($sql)) {
		$response=array('state'=>200, 'action'=>'added', 'msg'=>'Widget Added');
		echo json_encode($response);
		exit;
	}
	else {
		$response=array('state'=>400, 'msg'=>_('Error Adding Widget'));
		echo json_encode($response);
		exit;
	}
}

function delete_widget() {
	$key=$_REQUEST['key'];

	$sql=sprintf("delete from `Dashboard Widget User Bridge` where `Dashboard Key`=%d", $key);
	if (mysql_query($sql)) {
		$response=array('state'=>200, 'action'=>_('deleted'));
		echo json_encode($response);
		exit;
	}
	else {
		$response=array('state'=>400, 'msg'=>_('Cannot Delete'));
		echo json_encode($response);
		exit;
	}




}

function view_widgets() {
	$dashboard_id=$_REQUEST['dashboard_id'];
	$user_id=$_REQUEST['user_id'];
	$sql=sprintf("select * from `Dashboard Widget Dimension`");
	$result=mysql_query($sql);

	$total=mysql_num_rows($result);
	$tableid=0;

	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
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

function list_widgets() {



	$conf=$_SESSION['state']['dashboards']['active_widgets'];

	if (isset( $_REQUEST['user_id']))
		$user_id=$_REQUEST['user_id'];
	else {
		return;
	}



	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
	} else
		$number_results=$conf['nr'];

	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$_SESSION['state']['dashboards']['active_widgets']['order']=$order;
	$_SESSION['state']['dashboards']['active_widgets']['order_dir']=$order_dir;
	$_SESSION['state']['dashboards']['active_widgets']['nr']=$number_results;
	$_SESSION['state']['dashboards']['active_widgets']['sf']=$start_from;
	$_SESSION['state']['dashboards']['active_widgets']['f_field']=$f_field;
	$_SESSION['state']['dashboards']['active_widgets']['f_value']=$f_value;


	$where=sprintf("where `User Key`=%d",$user_id);

	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Widget Name` like '".addslashes($f_value)."%'";
	elseif ($f_field=='description' and $f_value!='')
		$wheref.=" and  `Widget Description` like '%".addslashes($f_value)."%'";

	$sql="select count(*) as total from `Dashboard Widget Bridge`B left join `Dashboard Dimension` D on (B.`Dashboard Key`=D.`Dashboard Key`) left join `Widget Dimension` W on (B.`Widget Key`=W.`Widget Key`)   $where $wheref";
	// print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Dashboard Widget Bridge`B left join `Dashboard Dimension` D on (B.`Dashboard Key`=D.`Dashboard Key`) left join `Widget Dimension` W on (B.`Widget Key`=W.`Widget Key`) $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=$total_records." ".ngettext('widget','widgets',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any widget with description like ")." <b>".$f_value."*</b> ";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any widget with name like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('widgets with description like')." <b>".$f_value."*</b>";
			break;
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('widgets with name like')." <b>*".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;









	$adata=array();


	$sql=sprintf("select * from  `Dashboard Widget Bridge`B left join `Dashboard Dimension` D on (B.`Dashboard Key`=D.`Dashboard Key`) left join `Widget Dimension` W on (B.`Widget Key`=W.`Widget Key`)   $where $wheref  ");
	// print $sql;
	$result=mysql_query($sql);

	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {



		$adata[]=array(
			'id'=>$row['Dashboard Widget Key'],
			'name'=>$row['Widget Name'],
			//   'widget_block'=>$row['Widget Block'],
			//   'widget_dimesnion'=>$row['Widget Dimension'],
			'url'=>$row['Widget URL'],
			'description'=>$row['Widget Description'],
			'delete'=>'<img src="art/icons/cross.png"/>',
			//   'user_key'=>$_row['User key'],
			'dashboard_key'=>$row['Dashboard Key'],
			'widget_key'=>$row['Widget Key'],

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

function set_default_dashboard($data) {
	$user_key=$data['user_key'];
	$dashboard_key=$data['dashboard_key'];

	$sql=sprintf("update `User Dimension` SET `User Dashboard Key` = %d  where `User key`=%d",$dashboard_key, $user_key);
	mysql_query($sql);


	$response=array('state'=>200, 'msg'=>_('Dashboard Selected'));
	echo json_encode($response);
}

function delete_dashboard($data) {
	$user_key=$data['user_key'];
	$dashboard_key=$data['dashboard_key'];

	$sql=sprintf("select `Dashboard Key` from `Dashboard Dimension` where `User Key`=%d", $user_key);
	$result=mysql_query($sql);
	$count=mysql_num_rows($result);

	if ($count == 1) {
		$response=array('state'=>400, 'msg'=>_('Dashboard cannot be Deleted'));
		echo json_encode($response);
		exit;
	}





	$sql=sprintf("delete from `Dashboard Dimension` where  `Dashboard Key`=%d", $dashboard_key);
	mysql_query($sql);
	$sql=sprintf("delete from `Dashboard Widget Bridge` where  `Dashboard Key`=%d", $dashboard_key);
	mysql_query($sql);

	$_user=new User($user_key);
	if ($dashboard_key==$_user->data['User Dashboard Key']) {
		$sql=sprintf("select `Dashboard Key` from `Dashboard Dimension` where `User Key`=%d", $user_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$sql=sprintf("update `User Dimension` SET `User Dashboard Key` = %d  where `User key`=%d",$row['Dashboard Key'], $user_key);
			mysql_query($sql);
		}
	}

	$response=array('state'=>200, 'msg'=>_('Dashboard Deleted'));
	echo json_encode($response);
}

function new_dashboard($data) {

	$user_id=$_REQUEST['user_key'];

	$sql=sprintf("select `Dashboard Key` from `Dashboard Dimension` where `User Key`=%d ",$user_id);
	$result=mysql_query($sql);
	$number_dashboards=mysql_num_rows($result);

	if ($number_dashboards>=5) {
		$response=array('state'=>400, 'msg'=>_('Maximum Allowed Dashboards Exceeded'));
		echo json_encode($response);
		exit;
	}elseif ($number_dashboards==0) {
		$default_dashboard='Yes';
	}else {
		$default_dashboard='No';
	}


	$sql=sprintf("insert into `Dashboard Dimension` (`User Key`,`Dashboard Order`,`Dashboard Default`) values (%d, %d,%s)",
		$user_id,
		$number_dashboards+1,
		prepare_mysql($default_dashboard)
	);
	mysql_query($sql);

	$response=array('state'=>200);
	echo json_encode($response);
}
?>
