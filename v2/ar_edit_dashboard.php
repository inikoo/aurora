<?php
include_once 'common.php';
require_once 'ar_edit_common.php';


if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'msg'=>'Non acceptable request (t)');
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
case('edit_widget'):
	edit_widget();
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

case('list_widgets_in_dashboard'):
	list_widgets_in_dashboard();
	break;

case('delete_widget_in_dashboard'):
	$data=prepare_values($_REQUEST,array(
			'subject_key'=>array('type'=>'key'),
			'table_id'=>array('type'=>'numeric','optional'=>true),
			'recordIndex'=>array('type'=>'numeric','optional'=>true),
		));



	delete_widget_in_dashboard($data);
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
case('widget_order_down'):
	widget_order_down();
	break;
case('widget_order_up'):
	widget_order_up();
	break;

default:

	$response=array('state'=>404,'msg'=>"Operation not found $tipo");
	echo json_encode($response);

}

function add_widget_to_dashboard($data) {

	$dashboard_key=$data['dashboard_key'];
	$widget_key=$data['widget_key'];

	$sql=sprintf("select * from  `Widget Dimension` where `Widget Key`=%d", $widget_key);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$widget_height=$row['Widget Dimension'];
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

	$sql=sprintf("select count(*) as num from  `Dashboard Widget Bridge` where `Dashboard Key`=%d", $dashboard_key);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$number_widgets_in_dashboard=$row['num'];
	}

	$sql=sprintf("insert into `Dashboard Widget Bridge` ( `Dashboard Key`,`Widget Key`,`Dashboard Widget Order`, `Dashboard Widget Height`) values (%d, %d,%d, %d)",
		$dashboard_key,
		$widget_key,
		($number_widgets_in_dashboard+1),
		$widget_height
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

function delete_widget_in_dashboard($data) {
	$dashboard_widget_key=$data['subject_key'];

	$sql=sprintf("select `Dashboard Key` from  `Dashboard Widget Bridge` where `Dashboard Widget Key`=%d", $dashboard_widget_key);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$dashboard_key=$row['Dashboard Key'];
	}else {
		$response=array('state'=>200, 'action'=>'not_found');
		echo json_encode($response);
		exit;
	}

	$sql=sprintf("delete from `Dashboard Widget Bridge` where `Dashboard Widget Key`=%d", $dashboard_widget_key);
	if (mysql_query($sql)) {
		update_widgets_in_dashboard_order($dashboard_key);
		$response=array('state'=>200, 'action'=>'deleted');

		if (isset($data['table_id'])) {
			$response['table_id']=$data['table_id'];
		}
		if (isset($data['recordIndex'])) {
			$response['recordIndex']=$data['recordIndex'];

		}



		echo json_encode($response);
		exit;
	}
	else {
		$response=array('state'=>400, 'msg'=>_('Can not Delete'));
		if (isset($data['table_id'])) {
			$response['table_id']=$data['table_id'];
		}
		if (isset($data['recordIndex'])) {
			$response['recordIndex']=$data['recordIndex'];

		}

		echo json_encode($response);
		exit;
	}





}



function list_widgets_in_dashboard() {

	if (!isset($_REQUEST['parent']) or !isset($_REQUEST['parent_key'])) {
		$response=array('state'=>400, 'msg'=>'no parent info');
		echo json_encode($response);
		exit;
	}

	if ($_REQUEST['parent']=='dashboards') {
		$parent=$_REQUEST['parent'];
		$parent_key=$_REQUEST['parent_key'];

	}elseif ($_REQUEST['parent']=='dashboard') {
		$parent=$_REQUEST['parent'];
		$parent_key=$_REQUEST['parent_key'];
	}

	$conf=$_SESSION['state'][$parent]['active_widgets'];





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


	$_SESSION['state'][$parent]['active_widgets']['order']=$order;
	$_SESSION['state'][$parent]['active_widgets']['order_dir']=$order_dir;
	$_SESSION['state'][$parent]['active_widgets']['nr']=$number_results;
	$_SESSION['state'][$parent]['active_widgets']['sf']=$start_from;
	$_SESSION['state'][$parent]['active_widgets']['f_field']=$f_field;
	$_SESSION['state'][$parent]['active_widgets']['f_value']=$f_value;


	if ($parent=='dashboards') {
		$where=sprintf("where `User Key`=%d",$parent_key);
	}elseif ($parent=='dashboard') {
		$where=sprintf("where B.`Dashboard Key`=%d",$parent_key);
	}else {
		$where=' where false ';
	}


	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Widget Name` like '".addslashes($f_value)."%'";
	elseif ($f_field=='description' and $f_value!='')
		$wheref.=" and  `Widget Description` like '%".addslashes($f_value)."%'";

	$sql="select count(*) as total from `Dashboard Widget Bridge`B left join `Dashboard Dimension` D on (B.`Dashboard Key`=D.`Dashboard Key`) left join `Widget Dimension` W on (B.`Widget Key`=W.`Widget Key`)   $where $wheref";
	//print $sql;
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


	$rtext=number($total_records)." ".ngettext('widget','widgets',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


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



	if ($order=='widget_order') {
		$order='`Dashboard Widget Order`';
	}elseif ($order=='name') {
		$order='`Widget Name`';
	}elseif ($order=='widget_height') {
		$order='`Dashboard Widget Height`';
	} else {
		$order='`Dashboard Widget Key`';
	}

	$number_widgets_in_dashboard=0;
	if ($parent=='dashboard') {
		$sql=sprintf("select count(*) as num from  `Dashboard Widget Bridge` where `Dashboard Key`=%d", $parent_key);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$number_widgets_in_dashboard=$row['num'];
		}
	}

	$adata=array();


	$sql=sprintf("select * from  `Dashboard Widget Bridge`B left join `Dashboard Dimension` D on (B.`Dashboard Key`=D.`Dashboard Key`) left join `Widget Dimension` W on (B.`Widget Key`=W.`Widget Key`)   $where $wheref order by  $order $order_direction ");
	// print $sql;
	$result=mysql_query($sql);

	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		switch ($row['Widget Block']) {
		case('block_1'):
			$widget_block='1 '.ngettext('Column','Columns',1);
			break;
		case('block_2'):
			$widget_block='2 '.ngettext('Column','Columns',2);
			break;
		case('block_3'):
			$widget_block='2 '.ngettext('Column','Columns',3);
			break;
		default:
			$widget_block=$row['Widget Block'];
		}

		$adata[]=array(
			'id'=>$row['Dashboard Widget Key'],
			'dashboard_widget_key'=>$row['Dashboard Widget Key'],
			'name'=>$row['Widget Name'],
			'subject_data'=>$row['Widget Name'],
			'widget_block'=>$widget_block,
			'widget_height'=>$row['Dashboard Widget Height'],
			'url'=>$row['Widget URL'],
			'description'=>$row['Widget Description'],
			'delete'=>'<img style="cursor:pointer" src="art/icons/cross.png"/>',
			'widget_order'=>$row['Dashboard Widget Order'],
			'dashboard_key'=>$row['Dashboard Key'],
			'widget_key'=>$row['Widget Key'],
			'widget_order_up'=>($row['Dashboard Widget Order']!=1?'<span style="cursor:pointer" onClick="widget_order_up('.$row['Dashboard Widget Key'].')" >&#8593;':''),
			'widget_order_down'=>($row['Dashboard Widget Order']<$number_widgets_in_dashboard?'<span style="cursor:pointer" onClick="widget_order_down('.$row['Dashboard Widget Key'].')" >&#8595;':''),

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

	$sql=sprintf("update `User Staff Settings Dimension` SET `User Dashboard Key` = %d  where `User key`=%d",$dashboard_key, $user_key);
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
			$sql=sprintf("update `User Staff Settings Dimension` SET `User Dashboard Key` = %d  where `User key`=%d",$row['Dashboard Key'], $user_key);
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

function update_widgets_in_dashboard_order($dashboard_key) {

	$dashboard_widget_order_array=array();
	$counter=1;
	$sql=sprintf("select `Dashboard Widget Key` from  `Dashboard Widget Bridge` where `Dashboard Key`=%d order by `Dashboard Widget Order`", $dashboard_key);
	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$dashboard_widget_order_array[$row['Dashboard Widget Key']]=$counter;
		$counter++;
	}

	foreach ($dashboard_widget_order_array as $dashboard_widget_key=>$dashboard_widget_order) {
		$sql=sprintf("update `Dashboard Widget Bridge` SET `Dashboard Widget Order` = %d  where `Dashboard Widget Key`=%d",$dashboard_widget_order, $dashboard_widget_key);
		mysql_query($sql);
	}

}

function edit_widget() {
	//ar_edit_dashboard.php?tipo=edit_widget&key=widget_height&newvalue=40&oldvalue=405&dashboard_key=8&widget_key=2&dashboard_widget_key=15
	$key=$_REQUEST['key'];

	$sql=sprintf("update `Dashboard Widget Bridge` set `Dashboard Widget Height`=%d where `Dashboard Widget Key`=%d and `Dashboard Key`=%d and `Widget Key`=%d", $_REQUEST['newvalue'], $_REQUEST['dashboard_widget_key'], $_REQUEST['dashboard_key'], $_REQUEST['widget_key'] );

	//print $sql;

	if ($result=mysql_query($sql)) {
		$response=array('state'=>200, 'newvalue'=>$_REQUEST['newvalue']);
		echo json_encode($response);
	}
	else {
		$response=array('state'=>400);
		echo json_encode($response);
	}




}

function widget_order_down() {
	$sql=sprintf("select * from `Dashboard Widget Bridge` where `Dashboard Widget Key`=%d", $_REQUEST['dashboard_widget_key']);

	$result=mysql_query($sql);
	if ($row=mysql_fetch_assoc($result)) {
		$order=$row['Dashboard Widget Order'];
	}

	$mover=$_REQUEST['dashboard_widget_key'];

	$sql=sprintf("select * from `Dashboard Widget Bridge` where `Dashboard Widget Order`=%d", $order+1);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_assoc($result)) {
		$move_around=$row['Dashboard Widget Key'];
	}
	else
		$move_around=$mover;





	move_dwk($mover, $move_around,'down');

}


function widget_order_up() {
	$sql=sprintf("select * from `Dashboard Widget Bridge` where `Dashboard Widget Key`=%d", $_REQUEST['dashboard_widget_key']);

	$result=mysql_query($sql);
	if ($row=mysql_fetch_assoc($result)) {
		$order=$row['Dashboard Widget Order'];
	}

	$mover=$_REQUEST['dashboard_widget_key'];

	$sql=sprintf("select * from `Dashboard Widget Bridge` where `Dashboard Widget Order`=%d", $order-1);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_assoc($result)) {
		$move_around=$row['Dashboard Widget Key'];
	}
	else
		$move_around=$mover;





	move_dwk($mover, $move_around,'up');

}

function move_before_dwk($mover, $move_around) {
	$sql=sprintf("select * form `Dashboard Widget Bridge` where `Dashboard Widget Key`=%d", $dw1);

}

function move_dwk($mover, $move_around, $move_direction) {

	if ($mover==$move_around) {
		$response=array('state'=>200, 'msg'=>'Nothing to be updated');
		echo json_encode($response);
		return;
	}

	$sql=sprintf("select * from `Dashboard Widget Bridge` where `Dashboard Widget Key`=%d", $mover);

	$result=mysql_query($sql);
	if ($row=mysql_fetch_assoc($result)) {
		$dashboard_key=$row['Dashboard Key'];
	}

	if ($move_direction=='up') {

		if (1 == $mover) {
			$response=array('state'=>400, 'msg'=>'Can not move UP');
			echo json_encode($response);
			return;
		}
	}
	/*
else{

		$sql=sprintf("select * from `Dashboard Widget Bridge` where `Dashboard Key`=%d order by `Dashboard Widget Order` DESC", $dashboard_key);
		$result=mysql_query($sql);
		if($row=mysql_fetch_assoc($result)){
			$last_widget=$row['Dashboard Widget Key'];
		}

		if($last_widget == $mover){
			$response=array('state'=>200, 'msg'=>'Can not move Down');
			echo json_encode($response);
			return;
		}
	}
*/

	$sql=sprintf("select * from `Dashboard Widget Bridge` where `Dashboard Widget Key`=%d", $mover);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_assoc($result)) {
		$mover_order=$row['Dashboard Widget Order'];
	}
	$sql=sprintf("select * from `Dashboard Widget Bridge` where `Dashboard Widget Key`=%d", $move_around);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_assoc($result)) {
		$move_around_order=$row['Dashboard Widget Order'];
	}

	$current_positions=array();
	$sql=sprintf("select * from `Dashboard Widget Bridge` where `Dashboard Key`=%d order by `Dashboard Widget Order`", $dashboard_key);
	$result=mysql_query($sql);
	while ($row=mysql_fetch_assoc($result)) {
		$current_positions[$row['Dashboard Widget Key']]=$row['Dashboard Widget Order'];
	}

	//print_r($current_positions);

	if ($move_direction=='up') {
		$current_positions[$mover]=$current_positions[$move_around];
		foreach ($current_positions as $dashboard_widget_key=>$dasboard_widget_order) {

			if ($dasboard_widget_order >= $move_around_order) {
				//print "before $dashboard_widget_key -- $dasboard_widget_order \n";
				if ($dashboard_widget_key!=$mover) {
					$current_positions[$dashboard_widget_key]=($dasboard_widget_order+1);
					//print "after $dashboard_widget_key -- ".($dasboard_widget_order+1)." \n";
				}

			}

		}


	}


	if ($move_direction=='down') {
		$current_positions[$mover]=$current_positions[$move_around]+1;
		foreach ($current_positions as $dashboard_widget_key=>$dasboard_widget_order) {

			if ($dasboard_widget_order > $move_around_order) {
				//print "before $dashboard_widget_key -- $dasboard_widget_order \n";
				if ($dashboard_widget_key!=$mover) {
					$current_positions[$dashboard_widget_key]=($dasboard_widget_order+1);
					//print "after $dashboard_widget_key -- ".($dasboard_widget_order+1)." \n";
				}

			}

		}


	}
	//print_r($current_positions);


	foreach ($current_positions as $dashboard_widget_key=>$dasboard_widget_order) {
		$sql=sprintf("update `Dashboard Widget Bridge` set `Dashboard Widget Order`=%d where `Dashboard Widget Key`=%d", $dasboard_widget_order,$dashboard_widget_key);
		mysql_query($sql);
		//print "$sql\n";
	}

	update_widgets_in_dashboard_order($dashboard_key);
	$response=array('state'=>200, 'msg'=>'Updated');
	echo json_encode($response);
	return;
}


?>
