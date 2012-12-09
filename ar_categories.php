<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2012 LW
require_once 'common.php';
require_once 'ar_common.php';

require_once 'class.Category.php';

if (!isset($output_type))
	$output_type='ajax';

if (!isset($_REQUEST['tipo'])) {
	if ($output_type=='ajax') {
		$response=array('state'=>405,'msg'=>'Non acceptable request (t)');
		echo json_encode($response);
	}
	return;
}


$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('get_branch_type_elements'):
	$data=prepare_values($_REQUEST,array(
			'subject'  =>array('type'=>'string'),
		));
	get_branch_type_elements($data);
	break;
case('main_categories'):
	list_main_categories();
	break;
default:
	$response=array('state'=>404,'resp'=>_('Operation not found'));
	echo json_encode($response);

}

function list_main_categories() {


	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else {
		exit("error: no parent");
	}


	$conf=$_SESSION['state'][$parent]['main_categories'];

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



	$_SESSION['state'][$parent]['part_categories']['order']=$order;
	$_SESSION['state'][$parent]['part_categories']['order_dir']=$order_direction;
	$_SESSION['state'][$parent]['part_categories']['nr']=$number_results;
	$_SESSION['state'][$parent]['part_categories']['sf']=$start_from;
	$_SESSION['state'][$parent]['part_categories']['f_field']=$f_field;
	$_SESSION['state'][$parent]['part_categories']['f_value']=$f_value;



	$where="where  `Category Parent Key`=0";

	switch ($parent) {
	case('part_categories'):
		$where.=" and `Category Subject`='Part'";
		break;
	default:
		exit('error: unknown parent category: '.$parent);
	}

	$where=sprintf("where `Category Subject`='Part' and  `Category Parent Key`=0");



	//  $where=sprintf("where `Category Subject`='Product'  ");



	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Category Code` like '%".addslashes($f_value)."%'";




	$sql="select count(*) as total   from `Category Dimension`   $where $wheref";


	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$total=$row['total'];

	}
	mysql_free_result($res);

	//exit;
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total  from `Category Dimension`  $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=$total_records." ".ngettext('main category','main categories',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';

	if ($total==0 and $filtered>0) {
		switch ($f_field) {

		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with name like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {

		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with name like')." <b>*".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;


	if ($order=='subjects')
		$order='`Category Number Subjects`';

	elseif ($order=='code')
		$order='`Category Code`';
	elseif ($order=='label')
		$order='`Category Label`';
	elseif ($order=='children' or $order=='percentage_assigned')
		$order='`Category Children Subjects Assigned`';



	$sql="select * from `Category Dimension` C $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);
	$adata=array();


	//print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {



		$code=sprintf('<a href="part_category.php?id=%d">%s</a>',$row['Category Key'],$row['Category Code']);
		$label=sprintf('<a href="part_category.php?id=%d">%s</a>',$row['Category Key'],$row['Category Label']);


		$adata[]=array(
			'id'=>$row['Category Key'],
			'code'=>$code,
			'label'=>$label,
			'subjects'=>number($row['Category Children Subjects Assigned']),
			'children'=>number($row['Category Children']),
			'percentage_assigned'=>percentage($row['Category Children Subjects Assigned'],$row['Category Children Subjects Not Assigned']+$row['Category Children Subjects Assigned'])


		);
	}
	mysql_free_result($res);



	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}



function get_branch_type_elements($data) {


	$elements_number=array('Root'=>0,'Node'=>0,'Head'=>0);
	$sql=sprintf("select count(*) as num ,`Category Branch Type` from  `Category Dimension` where  `Category Subject`=%s group by  `Category Branch Type`   ",
		prepare_mysql($data['subject']));
	//print_r($sql);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_number[$row['Category Branch Type']]=number($row['num']);
	}


	$response=array(
		'elements_number'=>$elements_number

	);
	echo json_encode($response);


}
