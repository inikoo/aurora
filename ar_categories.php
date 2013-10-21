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
case('family_categories'):
	list_family_categories();
	break;
case('family_categories'):
list_family_categories();

break;
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



	$where="where `Category Parent Key`=0";

	switch ($parent) {
	case('customer_categories'):
		$where.=sprintf(" and `Category Subject`='Customer' and `Category Store Key`=%d  ",$parent_key);
		break;
	case('family_categories'):
		$where.=sprintf(" and `Category Subject`='Product' and `Category Store Key`=%d  ",$parent_key);
		break;
	case('family_categories'):
		$where.=sprintf(" and `Category Subject`='Family' and `Category Store Key`=%d  ",$parent_key);
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


function list_family_categories() {

	$conf=$_SESSION['state']['family_categories']['subcategories'];
	$conf2=$_SESSION['state']['family_categories'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];

	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

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
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];
/*

	if (isset( $_REQUEST['exchange_type'])) {
		$exchange_type=addslashes($_REQUEST['exchange_type']);
		$_SESSION['state']['family_categories']['exchange_type']=$exchange_type;
	} else
		$exchange_type=$conf2['exchange_type'];

	if (isset( $_REQUEST['exchange_value'])) {
		$exchange_value=addslashes($_REQUEST['exchange_value']);
		$_SESSION['state']['family_categories']['exchange_value']=$exchange_value;
	} else
		$exchange_value=$conf2['exchange_value'];

	if (isset( $_REQUEST['show_default_currency'])) {
		$show_default_currency=addslashes($_REQUEST['show_default_currency']);
		$_SESSION['state']['family_categories']['show_default_currency']=$show_default_currency;
	} else
		$show_default_currency=$conf2['show_default_currency'];

*/


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
		
			if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];
		$_SESSION['state']['family_categories']['period']=$period;
	} else
		$period=$_SESSION['state']['family_categories']['period'];

/*
	if (isset( $_REQUEST['percentages'])) {
		$percentages=$_REQUEST['percentages'];
		$_SESSION['state']['family_categories']['percentages']=$percentages;
	} else
		$percentages=$_SESSION['state']['family_categories']['percentages'];





	if (isset( $_REQUEST['avg'])) {
		$avg=$_REQUEST['avg'];
		$_SESSION['state']['family_categories']['avg']=$avg;
	} else
		$avg=$_SESSION['state']['family_categories']['avg'];

	if (isset( $_REQUEST['stores_mode'])) {
		$stores_mode=$_REQUEST['stores_mode'];
		$_SESSION['state']['family_categories']['stores_mode']=$stores_mode;
	} else
		$stores_mode=$_SESSION['state']['family_categories']['stores_mode'];

*/

$_SESSION['state']['family_categories']['categories']['order']=$order;
$_SESSION['state']['family_categories']['categories']['order_dir']=$order_direction;
$_SESSION['state']['family_categories']['categories']['nr']=$number_results;
$_SESSION['state']['family_categories']['categories']['sf']=$start_from;
$_SESSION['state']['family_categories']['categories']['f_field']=$f_field;
$_SESSION['state']['family_categories']['categories']['f_value']=$f_value;
$_SESSION['state']['family_categories']['categories']['period']=$period;


	if (isset( $_REQUEST['category'])) {
		$root_category=$_REQUEST['category'];
		$_SESSION['state']['family_categories']['category']=$root_category;
	} else
		$root_category=$_SESSION['state']['family_categories']['category_key'];




	$where=sprintf("where `Category Subject`='Family' and  `Category Parent Key`=%d ",$root_category);
	//  $where=sprintf("where `Category Subject`='Product'  ");

//	if ($stores_mode=='grouped')
//		$group=' group by `Category Key`';
//	else
		$group='';

	$filter_msg='';
	$wheref='';
	if ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Category Code` like '%".addslashes($f_value)."%'";




	$sql="select count(*) as total   from `Category Dimension`   $where $wheref";

	//$sql=" describe `Category Dimension`;";
	// $sql="select *  from `Category Dimension` where `Category Parent Key`=1 ";
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$total=$row['total'];
		//   print_r($row);
	}
	mysql_free_result($res);

	//exit;
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total  from `Category Dimension` S  left join `Product Family Category Dimension` PC on (`Category Key`=PC.`Product Category Key`)   $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=$total_records." ".ngettext('category','categories',$total_records);
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

	$period_tag=get_interval_db_name($period);

	if ($order=='families')
		$order='`Product Category Families`';
	elseif ($order=='departments')
		$order='`Product Category Departments`';
	elseif ($order=='code')
		$order='`Category Code`';
	elseif ($order=='todo')
		$order='`Product Category In Process Products`';
	elseif ($order=='discontinued')
		$order='`Product Category In Process Products`';
	elseif ($order=='profit') {
		
			$order='`Product Category '.$period_tag.' Acc Profit`';
	
	}elseif ($order=='sales') {
		
			$order='`Product Category '.$period_tag.' Acc Invoiced Amount`';
	
	}
	elseif ($order=='name')
		$order='`Category Code`';
	elseif ($order=='active')
		$order='`Product Category For Public Sale Products`';
	elseif ($order=='outofstock')
		$order='`Product Category Out Of Stock Products`';
	elseif ($order=='stock_error')
		$order='`Product Category Unknown Stock Products`';
	elseif ($order=='surplus')
		$order='`Product Category Surplus Availability Products`';
	elseif ($order=='optimal')
		$order='`Product Category Optimal Availability Products`';
	elseif ($order=='low')
		$order='`Product Category Low Availability Products`';
	elseif ($order=='critical')
		$order='`Product Category Critical Availability Products`';





	$sql="select *  from `Category Dimension` S  left join `Product Family Category Dimension` PC on (`Category Key`=PC.`Product Family Category Key`)   $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";
	// print $sql;
	$res = mysql_query($sql);

	$total=mysql_num_rows($res);
	$adata=array();
	$sum_sales=0;
	$sum_profit=0;
	$sum_outofstock=0;
	$sum_low=0;
	$sum_optimal=0;
	$sum_critical=0;
	$sum_surplus=0;
	$sum_unknown=0;
	$sum_departments=0;
	$sum_families=0;
	$sum_todo=0;
	$sum_discontinued=0;

//	$DC_tag='';
//	if ($exchange_type=='day2day' and $show_default_currency  )
//		$DC_tag=' DC';

	// print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		//$name=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Category Key'],$row['Product Category Code']);
		//$code=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Category Key'],$row['Product Category Code']);
/*
		if ($percentages) {
			if ($period=='all') {
				$tsall=percentage($row['Product Category DC Total Invoiced Amount'],$sum_total_sales,2);
				if ($row['Product Category DC Total Profit']>=0)
					$tprofit=percentage($row['Product Category DC Total Profit'],$sum_total_profit_plus,2);
				else
					$tprofit=percentage($row['Product Category DC Total Profit'],$sum_total_profit_minus,2);
			}
			elseif ($period=='year') {
				$tsall=percentage($row['Product Category DC 1 Year Acc Invoiced Amount'],$sum_total_sales,2);
				if ($row['Product Category DC 1 Year Acc Profit']>=0)
					$tprofit=percentage($row['Product Category DC 1 Year Acc Profit'],$sum_total_profit_plus,2);
				else
					$tprofit=percentage($row['Product Category DC 1 Year Acc Profit'],$sum_total_profit_minus,2);
			}
			elseif ($period=='quarter') {
				$tsall=percentage($row['Product Category DC 1 Quarter Acc Invoiced Amount'],$sum_total_sales,2);
				if ($row['Product Category DC 1 Quarter Acc Profit']>=0)
					$tprofit=percentage($row['Product Category DC 1 Quarter Acc Profit'],$sum_total_profit_plus,2);
				else
					$tprofit=percentage($row['Product Category DC 1 Quarter Acc Profit'],$sum_total_profit_minus,2);
			}
			elseif ($period=='month') {
				$tsall=percentage($row['Product Category DC 1 Month Acc Invoiced Amount'],$sum_total_sales,2);
				if ($row['Product Category DC 1 Month Acc Profit']>=0)
					$tprofit=percentage($row['Product Category DC 1 Month Acc Profit'],$sum_total_profit_plus,2);
				else
					$tprofit=percentage($row['Product Category DC 1 Month Acc Profit'],$sum_total_profit_minus,2);
			}
			elseif ($period=='week') {
				$tsall=percentage($row['Product Category DC 1 Week Acc Invoiced Amount'],$sum_total_sales,2);
				if ($row['Product Category DC 1 Week Acc Profit']>=0)
					$tprofit=percentage($row['Product Category DC 1 Week Acc Profit'],$sum_total_profit_plus,2);
				else
					$tprofit=percentage($row['Product Category DC 1 Week Acc Profit'],$sum_total_profit_minus,2);
			}


		} 
		else {






			if ($period=="all") {


				if ($avg=="totals")
					$factor=1;
				elseif ($avg=="month") {
					if ($row["Product Category".$DC_tag." Total Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Product Category".$DC_tag." Total Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="week") {
					if ($row["Product Category".$DC_tag." Total Acc Days On Sale"]>0)
						$factor=7/$row["Product Category".$DC_tag." Total Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month_eff") {
					if ($row["Product Category".$DC_tag." Total Acc Days Available"]>0)
						$factor=30.4368499/$row["Product Category".$DC_tag." Total Acc Days Available"];
					else
						$factor=0;
				}
				elseif ($avg=="week_eff") {
					if ($row["Product Category".$DC_tag." Total Acc Days Available"]>0)
						$factor=7/$row["Product Category".$DC_tag." Total Acc Days Available"];
					else
						$factor=0;
				}

				$tsall=($row["Product Category".$DC_tag." Total Invoiced Amount"]*$factor);
				$tprofit=($row["Product Category".$DC_tag." Total Profit"]*$factor);




			}
			elseif ($period=="year") {


				if ($avg=="totals")
					$factor=1;
				elseif ($avg=="month") {
					if ($row["Product Category".$DC_tag." 1 Year Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Product Category".$DC_tag." 1 Year Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month") {
					if ($row["Product Category".$DC_tag." 1 Year Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Product Category".$DC_tag." 1 Year Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="week") {
					if ($row["Product Category".$DC_tag." 1 Year Acc Days On Sale"]>0)
						$factor=7/$row["Product Category".$DC_tag." 1 Year Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month_eff") {
					if ($row["Product Category".$DC_tag." 1 Year Acc Days Available"]>0)
						$factor=30.4368499/$row["Product Category".$DC_tag." 1 Year Acc Days Available"];
					else
						$factor=0;
				}
				elseif ($avg=="week_eff") {
					if ($row["Product Category".$DC_tag." 1 Year Acc Days Available"]>0)
						$factor=7/$row["Product Category".$DC_tag." 1 Year Acc Days Available"];
					else
						$factor=0;
				}









				$tsall=($row["Product Category".$DC_tag." 1 Year Acc Invoiced Amount"]*$factor);
				$tprofit=($row["Product Category".$DC_tag." 1 Year Acc Profit"]*$factor);
			}
			elseif ($period=="quarter") {
				if ($avg=="totals")
					$factor=1;
				elseif ($avg=="month") {
					if ($row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month") {
					if ($row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="week") {
					if ($row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"]>0)
						$factor=7/$row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month_eff") {
					if ($row["Product Category".$DC_tag." 1 Quarter Acc Days Available"]>0)
						$factor=30.4368499/$row["Product Category".$DC_tag." 1 Quarter Acc Days Available"];
					else
						$factor=0;
				}
				elseif ($avg=="week_eff") {
					if ($row["Product Category".$DC_tag." 1 Quarter Acc Days Available"]>0)
						$factor=7/$row["Product Category".$DC_tag." 1 Quarter Acc Days Available"];
					else
						$factor=0;
				}


				$tsall=($row["Product Category".$DC_tag." 1 Quarter Acc Invoiced Amount"]*$factor);
				$tprofit=($row["Product Category".$DC_tag." 1 Quarter Acc Profit"]*$factor);
			}
			elseif ($period=="month") {
				if ($avg=="totals")
					$factor=1;
				elseif ($avg=="month") {
					if ($row["Product Category".$DC_tag." 1 Month Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Product Category".$DC_tag." 1 Month Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month") {
					if ($row["Product Category".$DC_tag." 1 Month Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Product Category".$DC_tag." 1 Month Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="week") {
					if ($row["Product Category".$DC_tag." 1 Month Acc Days On Sale"]>0)
						$factor=7/$row["Product Category".$DC_tag." 1 Month Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month_eff") {
					if ($row["Product Category".$DC_tag." 1 Month Acc Days Available"]>0)
						$factor=30.4368499/$row["Product Category".$DC_tag." 1 Month Acc Days Available"];
					else
						$factor=0;
				}
				elseif ($avg=="week_eff") {
					if ($row["Product Category".$DC_tag." 1 Month Acc Days Available"]>0)
						$factor=7/$row["Product Category".$DC_tag." 1 Month Acc Days Available"];
					else
						$factor=0;
				}


				$tsall=($row["Product Category".$DC_tag." 1 Month Acc Invoiced Amount"]*$factor);
				$tprofit=($row["Product Category".$DC_tag." 1 Month Acc Profit"]*$factor);
			}
			elseif ($period=="week") {
				if ($avg=="totals")
					$factor=1;
				elseif ($avg=="month") {
					if ($row["Product Category".$DC_tag." 1 Week Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Product Category".$DC_tag." 1 Week Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month") {
					if ($row["Product Category".$DC_tag." 1 Week Acc Days On Sale"]>0)
						$factor=30.4368499/$row["Product Category".$DC_tag." 1 Week Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="week") {
					if ($row["Product Category".$DC_tag." 1 Week Acc Days On Sale"]>0)
						$factor=7/$row["Product Category".$DC_tag." 1 Week Acc Days On Sale"];
					else
						$factor=0;
				}
				elseif ($avg=="month_eff") {
					if ($row["Product Category".$DC_tag." 1 Week Acc Days Available"]>0)
						$factor=30.4368499/$row["Product Category".$DC_tag." 1 Week Acc Days Available"];
					else
						$factor=0;
				}
				elseif ($avg=="week_eff") {
					if ($row["Product Category".$DC_tag." 1 Week Acc Days Available"]>0)
						$factor=7/$row["Product Category".$DC_tag." 1 Week Acc Days Available"];
					else
						$factor=0;
				}


				$tsall=($row["Product Category".$DC_tag." 1 Week Acc Invoiced Amount"]*$factor);
				$tprofit=($row["Product Category".$DC_tag." 1 Week Acc Profit"]*$factor);
			}



		}

		$sum_sales+=$tsall;
		$sum_profit+=$tprofit;
		$sum_low+=$row['Product Category Low Availability Products'];
		$sum_optimal+=$row['Product Category Optimal Availability Products'];
		$sum_low+=$row['Product Category Low Availability Products'];
		$sum_critical+=$row['Product Category Critical Availability Products'];
		$sum_surplus+=$row['Product Category Surplus Availability Products'];
		$sum_outofstock+=$row['Product Category Out Of Stock Products'];
		$sum_unknown+=$row['Product Category Unknown Stock Products'];
		$sum_departments+=$row['Product Category Departments'];
		$sum_families+=$row['Product Category Families'];
		$sum_todo+=$row['Product Category In Process Products'];
		$sum_discontinued+=$row['Product Category Discontinued Products'];


		if (!$percentages) {
			if ($show_default_currency) {
				$class='';
				if ($corporate_currency!=$row['Product Category Currency Code'])
					$class='currency_exchanged';


				$sales='<span class="'.$class.'">'.money($tsall).'</span>';
				$profit='<span class="'.$class.'">'.money($tprofit).'</span>';
			} else {
				$sales=money($tsall,$row['Product Category Currency Code']);
				$profit=money($tprofit,$row['Product Category Currency Code']);
			}
		} else {
			$sales=$tsall;
			$profit=$tprofit;
		}
		*/
		
		
					$label=sprintf('<a href="family_category.php?id=%d">%s</a>',$row['Category Key'],$row['Category Label']);
			$code=sprintf('<a href="family_category.php?id=%d">%s</a>',$row['Category Key'],$row['Category Code']);

		
		
//		if ($stores_mode=='grouped')
			$name=sprintf('<a href="family_categories.php?id=%d">%s</a>',$row['Category Key'],$row['Category Code']);

//		else
//			$name=$row['Product Category Key'].' '.$row['Category Code']." (".$row['Product Category Store Key'].")";
		$adata[]=array(
			//'go'=>sprintf("<a href='edit_category.php?edit=1&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Category Key']),
			'id'=>$row['Category Key'],
			'label'=>$label,
			'code'=>$code,
			'subjects'=>number($row['Category Number Subjects']),

			//'departments'=>number($row['Product Category Departments']),
			//'families'=>number($row['Product Category Families']),
			//'active'=>number($row['Product Category For Public Sale Products']),
			//'todo'=>number($row['Product Category In Process Products']),
			//'discontinued'=>number($row['Product Category Discontinued Products']),
			//'outofstock'=>number($row['Product Category Out Of Stock Products']),
			//'stock_error'=>number($row['Product Category Unknown Stock Products']),
			//'stock_value'=>money($row['Product Category Stock Value']),
			//'surplus'=>number($row['Product Category Surplus Availability Products']),
			//'optimal'=>number($row['Product Category Optimal Availability Products']),
			//'low'=>number($row['Product Category Low Availability Products']),
			//'critical'=>number($row['Product Category Critical Availability Products']),
			//'sales'=>$sales,
			//'profit'=>$profit


		);
	}
	mysql_free_result($res);

	/*  if ($percentages) { */
	/*         $sum_sales='100.00%'; */
	/*         $sum_profit='100.00%'; */
	/* //       $sum_low= */
	/* //   $sum_optimal=$row['Product Category Optimal Availability Products']; */
	/* //   $sum_low=$row['Product Category Low Availability Products']; */
	/* //   $sum_critical=$row['Product Category Critical Availability Products']; */
	/* //   $sum_surplus=$row['Product Category Surplus Availability Products']; */
	/*     } else { */
	/*         $sum_sales=money($sum_total_sales); */
	/*         $sum_profit=money($sum_total_profit); */
	/*     } */

	/*     $sum_outofstock=number($sum_outofstock); */
	/*     $sum_low=number($sum_low); */
	/*     $sum_optimal=number($sum_optimal); */
	/*     $sum_critical=number($sum_critical); */
	/*     $sum_surplus=number($sum_surplus); */
	/*     $sum_unknown=number($sum_unknown); */
	/*     $sum_departments=number($sum_departments); */
	/*     $sum_families=number($sum_families); */
	/*     $sum_todo=number($sum_todo); */
	/*     $sum_discontinued=number($sum_discontinued); */
	/*     $adata[]=array( */

	/*                  'name'=>_('Total'), */
	/*                  'active'=>number($sum_active), */
	/*                  'sales'=>$sum_sales, */
	/*                  'profit'=>$sum_profit, */
	/*                  'todo'=>$sum_todo, */
	/*                  'discontinued'=>$sum_discontinued, */
	/*                  'low'=>$sum_low, */
	/*                  'critical'=>$sum_critical, */
	/*                  'surplus'=>$sum_surplus, */
	/*                  'optimal'=>$sum_optimal, */
	/*                  'outofstock'=>$sum_outofstock, */
	/*                  'stock_error'=>$sum_unknown, */
	/*                  'departments'=>$sum_departments, */
	/*                  'families'=>$sum_families */
	/*              ); */


	// if($total<$number_results)
	//  $rtext=$total.' '.ngettext('store','stores',$total);
	//else
	//  $rtext='';

	//   $total_records=ceil($total_records/$number_results)+$total_records;

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
?>
