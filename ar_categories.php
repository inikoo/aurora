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
case('get_part_category_element_numbers'):
	$data=prepare_values($_REQUEST,array(
			'parent'  =>array('type'=>'string'),

			'parent_key'  =>array('type'=>'key'),
		));
	get_part_category_element_numbers($data);
	break;
case('get_branch_type_elements'):
	$data=prepare_values($_REQUEST,array(
			'subject'  =>array('type'=>'string'),
			'warehouse_key'  =>array('type'=>'key','optional'=>true),
			'store_key'  =>array('type'=>'key','optional'=>true),
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


	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		exit("error: no parent key");
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



	$_SESSION['state'][$parent]['main_categories']['order']=$order;
	$_SESSION['state'][$parent]['main_categories']['order_dir']=$order_direction;
	$_SESSION['state'][$parent]['main_categories']['nr']=$number_results;
	$_SESSION['state'][$parent]['main_categories']['sf']=$start_from;
	$_SESSION['state'][$parent]['main_categories']['f_field']=$f_field;
	$_SESSION['state'][$parent]['main_categories']['f_value']=$f_value;



	$where="where  `Category Parent Key`=0";

	switch ($parent) {
	case('customer_categories'):
		$where.=sprintf(" and `Category Subject`='Customer' and `Category Store Key`=%d  ",$parent_key);
		break;
	case('part_categories'):
		$where.=sprintf(" and `Category Subject`='Part' and `Category Warehouse Key`=%d  ",$parent_key);
		break;
	case('invoice_categories'):
		$where.=sprintf(" and `Category Subject`='Invoice' and `Category Store Key`=%d  ",$parent_key);
		break; 
	case('supplier_categories'):
		$where.=" and `Category Subject`='Supplier'";
		break;
	default:
		exit('error: unknown parent category: '.$parent);
	}








	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Category Code` like '%".addslashes($f_value)."%'";
	elseif ($f_field=='label' and $f_value!='')
		$wheref.=" and  `Category Label` like '%".addslashes($f_value)."%'";



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

		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with code like ")." <b>*".$f_value."*</b> ";
			break;
		case('label'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with label like ")." <b>*".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {

		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with code like')." <b>*".$f_value."*</b>";
			break;
		case('label'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with label like')." <b>*".$f_value."*</b>";
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



		$code=sprintf('<a href="category.php?id=%d">%s</a>',$row['Category Key'],$row['Category Code']);
		$label=sprintf('<a href="category.php?id=%d">%s</a>',$row['Category Key'],$row['Category Label']);

		switch ($row['Category Branch Type']) {
		case('Root'):
			$branch_type='<img src="art/icons/category_root'.($row['Category Can Have Other']=='Yes'?($row['Category Children Other']=='Yes'?'_with_other':'_can_other'):'').'.png" title="'.$row['Category Plain Branch Tree'].'" />';
			break;
		case('Node'):
			$branch_type='<img src="art/icons/category_node'.($row['Category Can Have Other']=='Yes'?($row['Category Children Other']=='Yes'?'_with_other':'_can_other'):'').'" />';
			break;
		case('Head'):
			if ($row['Is Category Field Other']=='No')
				$branch_type='<img src="art/icons/category_head.png" title="'.$row['Category Plain Branch Tree'].'" />';
			else
				$branch_type='<img src="art/icons/category_head_other.png" title="'.$row['Category Plain Branch Tree'].'" />';

		}



		if ($row['Category Show Subject User Interface']=='Yes') {
			if ($row['Category Show Public New Subject']=='Yes') {
				if ($row['Category Show Public Edit']=='Yes') {
					$image_tag='yyy';
				}else {
					$image_tag='yyn';
				}
			}
			else {
				if ($row['Category Show Public Edit']=='Yes') {
					$image_tag='yny';
				}else {
					$image_tag='ynn';
				}

			}


		}else {
			if ($row['Category Show Public New Subject']=='Yes') {

				if ($row['Category Show Public Edit']=='Yes') {
					$image_tag='nyy';
				}else {
					$image_tag='nyn';
				}



			}else {


				if ($row['Category Show Public Edit']=='Yes') {
					$image_tag='nny';
				}else {
					$image_tag='nnn';
				}


			}

		}

		$public_view_icon='<img src="art/icons/category_user_view_'.$image_tag.'.png" title="'._('Category View').'" /> ';

		if ($row['Category Subject']=='Customer') {
			$branch_type.=' '.$public_view_icon;
		}


		$adata[]=array(
			'id'=>$row['Category Key'],
			'code'=>$code,
			'label'=>$label,
			'branch_type'=>$branch_type,
			'public_view'=>$public_view_icon,
			'subjects'=>number($row['Category Number Subjects']),
			'children'=>number($row['Category Children']),
			'percentage_assigned'=>percentage($row['Category Number Subjects'],$row['Category Subjects Not Assigned']+$row['Category Number Subjects'])


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

	$other_where='';


	if (isset($data['warehouse_key'])) {
		$other_where.=sprintf(" and `Category Warehouse Key`=%d",$data['warehouse_key']);
	}
	if (isset($data['store_key'])) {
		$other_where.=sprintf(" and `Category Store Key`=%d",$data['store_key']);
	}

	$elements_number=array('Root'=>0,'Node'=>0,'Head'=>0);
	$sql=sprintf("select count(*) as num ,`Category Branch Type` from  `Category Dimension` where  `Category Subject`=%s %s group by  `Category Branch Type`   ",
		prepare_mysql($data['subject']),
		$other_where
	);
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



function get_part_category_element_numbers($data) {



	$elements_number=array('InUse'=>0,'NotInUse'=>0);

	if ($data['parent']=='warehouse') {

		$sql=sprintf("select count(*) as num ,`Part Category Status` from  `Part Category Dimension` where  `Part Category Warehouse Key`=%d group by  `Part Category Status`   ",
			$data['parent_key']
		);

	}else if ($data['parent']=='category') {

			$sql=sprintf("select count(*) as num ,`Part Category Status` from  `Part Category Dimension` PC  left join `Category Dimension` C on (`Category Key`=`Part Category Key`)  where  `Category Parent Key`=%d group by  `Part Category Status`   ",
				$data['parent_key']
			);

		}
	//print_r($sql);
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$elements_number[$row['Part Category Status']]=number($row['num']);
	}


	$response=array(
		'elements_number'=>$elements_number

	);
	echo json_encode($response);

}

?>
