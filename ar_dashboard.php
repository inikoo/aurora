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


case('list_widgets'):
	list_widgets();
	break;


default:

	$response=array('state'=>404,'msg'=>_('Operation not found'));
	echo json_encode($response);

}


function list_widgets() {



	$conf=$_SESSION['state']['dashboards']['widgets'];



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


	$_SESSION['state']['dashboards']['widgets']['order']=$order;
	$_SESSION['state']['dashboards']['widgets']['order_dir']=$order_dir;
	$_SESSION['state']['dashboards']['widgets']['nr']=$number_results;
	$_SESSION['state']['dashboards']['widgets']['sf']=$start_from;
	$_SESSION['state']['dashboards']['widgets']['f_field']=$f_field;
	$_SESSION['state']['dashboards']['widgets']['f_value']=$f_value;


	$where='';

	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Widget Name` like '".addslashes($f_value)."%'";
	elseif ($f_field=='description' and $f_value!='')
		$wheref.=" and  `Widget Description` like '%".addslashes($f_value)."%'";

	$sql="select count(*) as total from  `Widget Dimension`  $where $wheref";
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
		$sql="select count(*) as total from `Widget Dimension`$where ";

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


	$sql=sprintf("select * from  `Widget Dimension`   $where $wheref  ");
	// print $sql;
	$result=mysql_query($sql);

	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {



		$adata[]=array(
			'id'=>$row['Widget Key'],
			'name'=>$row['Widget Name'],
			'widget_block'=>$row['Widget Block'],
			'widget_dimension'=>$row['Widget Dimension'],
			'url'=>$row['Widget URL'],
			'description'=>$row['Widget Description'],



		);

	}
	mysql_free_result($result);

	//print_r($adata);


	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);


}
