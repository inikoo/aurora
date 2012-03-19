<?php
/*


 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

require_once 'common.php';
require_once 'class.Site.php';
require_once 'ar_common.php';

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
	echo json_encode($response);
	exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('pages'):
	list_pages();
	break;
case('sites'):
	list_sites();
	break;
case('is_page_store_code'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key'),
			'query'=>array('type'=>'string')
		));
	is_page_store_code($data);
	break;
default:

	$response=array('state'=>404,'msg'=>_('Operation not found'));
	echo json_encode($response);

}


function is_page_store_code($data) {


	if (!isset($data['query']) or !isset($data['site_key']) ) {
		$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	} else
		$query=$data['query'];
	if ($query=='') {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

	$site_key=$data['site_key'];

	$sql=sprintf("select PS.`Page Code`,PS.`Page Key`,`Page URL`  from `Page Store Dimension` PS left join `Page Dimension` P  on (PS.`Page Key`=P.`Page Key`) where `Page Site Key`=%d and `Page Code`=%s  "
		,$site_key
		,prepare_mysql($query)
	);

	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('A page in this site %s already has this code (%s)'

			,$data['Page URL']

			,$data['Page Code']
		);
		$response= array(
			'state'=>200,
			'found'=>1,
			'msg'=>$msg
		);
		echo json_encode($response);
		return;
	} else {
		$response= array(
			'state'=>200,
			'found'=>0
		);
		echo json_encode($response);
		return;
	}

}

function list_pages() {

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent='none';

	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		$parent_key='';

	if ($parent=='store') {
		$conf=$_SESSION['state']['store']['pages'];
		$conf_table='store';
	}
	elseif ($parent=='department') {
		$conf=$_SESSION['state']['department']['pages'];
		$conf_table='department';
	}
	elseif ($parent=='family') {
		$conf=$_SESSION['state']['family']['pages'];
		$conf_table='family';
	}
	elseif ($parent=='none') {
		$conf=$_SESSION['state']['sites']['pages'];
		$conf_table='stores';
	}
	elseif ($parent=='site') {
		$conf=$_SESSION['state']['site']['pages'];
		$conf_table='site';
	}
	else {

		exit;
	}



	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];

	if (isset( $_REQUEST['where']))
		$where=$_REQUEST['where'];
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['period']))
		$period=$_REQUEST['period'];
	else
		$period=$conf['period'];


	if (isset( $_REQUEST['percentages']))
		$percentages=$_REQUEST['percentages'];
	else
		$percentages=$conf['percentages'];





	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');




	$elements=$conf['elements'];

	if (isset( $_REQUEST['elements_product_description'])) {
		$elements['ProductDescription']=$_REQUEST['elements_product_description'];

	}
	if (isset( $_REQUEST['elements_family_catalogue'])) {
		$elements['FamilyCatalogue']=$_REQUEST['elements_family_catalogue'];
	}
	if (isset( $_REQUEST['elements_department_catalogue'])) {
		$elements['DepartmentCatalogue']=$_REQUEST['elements_department_catalogue'];
	}


	if (isset( $_REQUEST['elements_other'])) {
		$elements['Other']=$_REQUEST['elements_other'];
	}






	$_SESSION['state'][$conf_table]['pages']['order']=$order;
	$_SESSION['state'][$conf_table]['pages']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['pages']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['pages']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['pages']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['pages']['f_value']=$f_value;
	$_SESSION['state'][$conf_table]['pages']['percentages']=$percentages;
	$_SESSION['state'][$conf_table]['pages']['period']=$period;
	$_SESSION['state'][$conf_table]['pages']['elements']=$elements;

	$_order=$order;
	$_dir=$order_direction;



	$where='where true ';



	switch ($parent) {
	case('store'):
		$where.=sprintf(' and `Page Store Key`=%d  and  `Page Store Section`  not in ("Department Catalogue","Product Description","Family Catalogue")  ',$parent_key);
		break;
	case('site'):
		$where.=sprintf(' and `Page Site Key`=%d',$parent_key);
		break;
	case('department'):
		$where.=sprintf('  and `Page Parent Key`=%d  and `Page Store Section`="Department Catalogue"  ',$parent_key);
		break;
	case('Product'):
		$where.=sprintf('  and `Page Parent Key`=%d  and `Page Store Section`="Product Description"  ',$parent_key);
		break;
	case('family'):
		$where.=sprintf('  and `Page Parent Key`=%d  and `Page Store Section`="Family Catalogue"  ',$parent_key);

		break;
	default:


	}


	$group='';

	if ($parent=='site') {

		$_elements='';
		foreach ($elements as $_key=>$_value) {
			if ($_value) {
				if ($_key=='Other') {
					$_key="'Front Page Store','Search','Information','Category Catalogue','Unknown','Store Catalogue','Registration','Client Section','Check Out'";
					$_elements.=','.$_key;

				}
				elseif ($_key=='ProductDescription') {
					$_elements.=",'Product Description'";
				}
				elseif ($_key=='FamilyCatalogue') {
					$_elements.=",'Family Catalogue'";
				}
				elseif ($_key=='DepartmentCatalogue') {
					$_elements.=",'Department Catalogue'";
				}
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		} else {
			$where.=' and `Page Store Section` in ('.$_elements.')' ;
		}


	}



	$wheref='';
	if ($f_field=='code'  and $f_value!='')
		$wheref.=" and `Page Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='title' and $f_value!='')
		$wheref.=" and  `Page Store Title` like '".addslashes($f_value)."%'";



	$sql="select count(*) as total from `Page Store Dimension` PS left join `Page Dimension` P on (P.`Page Key`=PS.`Page Key`)  $where $wheref";
	//print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Page Store Dimension` PS left join `Page Dimension` P on (P.`Page Key`=PS.`Page Key`) $where      ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}

	$rtext=$total_records." ".ngettext('page','pages',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';






	$filter_msg='';

	switch ($f_field) {
	case('code'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any page with code")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('pages with code')." <b>$f_value</b>*)";
		break;
	case('name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any page with name")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('pages with name')." <b>$f_value</b>*)";
		break;

	}
$interval_db= get_interval_db_name($period);

	if ($order=='code')
		$order='`Page Code`';
	elseif ($order=='url')
		$order='`Page URL`';
	elseif ($order=='users'){
		$order="`Page Store $interval_db Acc Users`";
	}elseif ($order=='visitors'){
		$order="`Page Store $interval_db Acc Visitors`";
	}elseif ($order=='sessions'){
		$order="`Page Store $interval_db Acc Sessions`";
	}elseif ($order=='requests'){
		$order="`Page Store $interval_db Acc Requests`";
	}		
	


	elseif ($order=='title')
		$order='`Page Store Title`';
	elseif ($order=='link_title')
		$order='`Page Short Title`';
	else {
		$order='`Page Code`';
	}
	//print $order;
	//    elseif($order='used_in')
	//        $order='Supplier Product XHTML Sold As';

	$sql="select *,`Site Code`,`Site Key`,`Page Short Title`,`Page Preview Snapshot Image Key`,`Page Store Section`,`Page Parent Code`,`Page Parent Key`,`Page URL`,P.`Page Key`,`Page Store Title`,`Page Code`   from `Page Store Dimension` PS left join `Page Dimension` P on (P.`Page Key`=PS.`Page Key`) left join `Site Dimension` on (`Site Key`=`Page Site Key`) $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$result=mysql_query($sql);
	$data=array();

	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {
		$code="<a href='page.php?id=".$row['Page Key']."'>".$row['Page Code']."</a>";

		switch ($period) {
		case 'three_year':
			$visitors=number($row['Page Store 3 Year Acc Visitors']);
			$sessions=number($row['Page Store 3 Year Acc Sessions']);
			$requests=number($row['Page Store 3 Year Acc Requests']);
			$users=number($row['Page Store 3 Year Acc Users']);
			break;
		case 'year':
			$visitors=number($row['Page Store 1 Year Acc Visitors']);
			$sessions=number($row['Page Store 1 Year Acc Sessions']);
			$requests=number($row['Page Store 1 Year Acc Requests']);
			$users=number($row['Page Store 1 Year Acc Users']);
			break;
		case 'quarter':
			$visitors=number($row['Page Store 1 Quarter Acc Visitors']);
			$sessions=number($row['Page Store 1 Quarter Acc Sessions']);
			$requests=number($row['Page Store 1 Quarter Acc Requests']);
			$users=number($row['Page Store 1 Quarter Acc Users']);
			break;

		case 'six_month':
			$visitors=number($row['Page Store 6 Month Acc Visitors']);
			$sessions=number($row['Page Store 6 Month Acc Sessions']);
			$requests=number($row['Page Store 6 Month Acc Requests']);
			$users=number($row['Page Store 6 Month Acc Users']);
			break;
		case 'month':
			$visitors=number($row['Page Store 1 Month Acc Visitors']);
			$sessions=number($row['Page Store 1 Month Acc Sessions']);
			$requests=number($row['Page Store 1 Month Acc Requests']);
			$users=number($row['Page Store 1 Month Acc Users']);
			break;
		case 'ten_day':
			$visitors=number($row['Page Store 10 Day Acc Visitors']);
			$sessions=number($row['Page Store 10 Day Acc Sessions']);
			$requests=number($row['Page Store 10 Day Acc Requests']);
			$users=number($row['Page Store 10 Day Acc Users']);
			break;
		case 'week':
			$visitors=number($row['Page Store 1 Week Acc Visitors']);
			$sessions=number($row['Page Store 1 Week Acc Sessions']);
			$requests=number($row['Page Store 1 Week Acc Requests']);
			$users=number($row['Page Store 1 Week Acc Users']);
			break;
		case 'yeartoday':
			$visitors=number($row['Page Store Year To Day Acc Visitors']);
			$sessions=number($row['Page Store Year To Day Acc Sessions']);
			$requests=number($row['Page Store Year To Day Acc Requests']);
			$users=number($row['Page Store Year To Day Acc Users']);
			break;
		case 'monthtoday':
			$visitors=number($row['Page Store Month To Day Acc Visitors']);
			$sessions=number($row['Page Store Month To Day Acc Sessions']);
			$requests=number($row['Page Store Month To Day Acc Requests']);
			$users=number($row['Page Store Month To Day Acc Users']);
			break;
		case 'weektoday':
			$visitors=number($row['Page Store Week To Day Acc Visitors']);
			$sessions=number($row['Page Store Week To Day Acc Sessions']);
			$requests=number($row['Page Store Week To Day Acc Requests']);
			$users=number($row['Page Store Week To Day Acc Users']);
			break;
		case 'day':
			$visitors=number($row['Page Store 1 Day Acc Visitors']);
			$sessions=number($row['Page Store 1 Day Acc Sessions']);
			$requests=number($row['Page Store 1 Day Acc Requests']);
			$users=number($row['Page Store 1 Day Acc Users']);
			break;
		case 'hour':
			$visitors=number($row['Page Store 1 Hour Acc Visitors']);
			$sessions=number($row['Page Store 1 Hour Acc Sessions']);
			$requests=number($row['Page Store 1 Hour Acc Requests']);
			$users=number($row['Page Store 1 Hour Acc Users']);
			break;
case 'all':
			$visitors=number($row['Page Store Total Acc Visitors']);
			$sessions=number($row['Page Store Total Acc Sessions']);
			$requests=number($row['Page Store Total Acc Requests']);
			$users=number($row['Page Store Total Acc Users']);
			break;

		default:
			exit("error $period");
			$visitors=number($row['Page Store Total Acc Visitors']);
			$sessions=number($row['Page Store Total Acc Sessions']);
			$requests=number($row['Page Store Total Acc Requests']);
			$users=number($row['Page Store Total Acc Users']);
			break;
		}


		switch ($row['Page Store Section']) {
		case 'Department Catalogue':
			$type=sprintf("d(<a href='department.php?id=%d'>%s</a>)",$row['Page Parent Key'],$row['Page Parent Code']);
			break;
		case 'Family Catalogue':
			$type=sprintf("f(<a href='family.php?id=%d'>%s</a>)",$row['Page Parent Key'],$row['Page Parent Code']);
			break;
		default:
			$type=_('Other');
			break;
		}
		$site="<a href='site.php?id=".$row['Site Key']."'>".$row['Site Code']."</a>";
		$data[]=array(
			'id'=>$row['Page Key'],
			'code'=>$code,
			'title'=>$row['Page Store Title'],
			'link_title'=>$row['Page Short Title'],
			'type'=>$type,
			'url'=>$row['Page URL'],
			'site'=>$site,
			'image'=>'image.php?size=small&id='.$row['Page Preview Snapshot Image Key'],
			'item_type'=>'item',
			'visitors'=>$visitors,
			'sessions'=>$sessions,
			'requests'=>$requests,
			'users'=>$users,




		);
	}


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'sort_key'=>$_order,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function list_sites() {
	global $user;
	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent='none';



	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		$parent_key='';

	if ($parent=='store') {
		$conf=$_SESSION['state']['store']['sites'];
		$conf_table='store';
	}
	elseif ($parent=='none') {
		$conf=$_SESSION['state']['sites']['sites'];
		$conf_table='department';
	}

	else {

		exit;
	}



	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];

	if (isset( $_REQUEST['where']))
		$where=$_REQUEST['where'];
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['period']))
		$period=$_REQUEST['period'];
	else
		$period=$conf['period'];


	if (isset( $_REQUEST['percentages']))
		$percentages=$_REQUEST['percentages'];
	else
		$percentages=$conf['percentages'];





	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');








	$_SESSION['state'][$conf_table]['pages']['order']=$order;
	$_SESSION['state'][$conf_table]['pages']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['pages']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['pages']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['pages']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['pages']['f_value']=$f_value;
	$_SESSION['state'][$conf_table]['pages']['percentages']=$percentages;
	$_SESSION['state'][$conf_table]['pages']['period']=$period;

	$_order=$order;
	$_dir=$order_direction;





	if (count($user->websites)==0) {
		$where='where false ';
	}else {
		$where='where true ';
	}

	switch ($parent) {
	case('store'):
		$where.=sprintf(' and `Site Store Key`=%d and `Site Key` in (%s)',$parent_key,join(',',$user->websites));


		break;
	default:
		$where.=sprintf(' and `Site Key` in (%s)',join(',',$user->websites));


		break;

	}


	$group='';



	$wheref='';
	if ($f_field=='name'  and $f_value!='')
		$wheref.=" and `Site Name` like '".addslashes($f_value)."%'";
	elseif ($f_field=='url' and $f_value!='')
		$wheref.=" and  `Site URL` like '%".addslashes($f_value)."%'";


	$sql="select count(*) as total from `Site Dimension` $where $wheref";

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Site Dimension` $where      ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}

	$rtext=$total_records." ".ngettext('website','websites',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';






	$filter_msg='';

	switch ($f_field) {
	case('name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any website with name")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('websites with name')." <b>$f_value</b>*)";
		break;
	case('url'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any website with address")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('websites with address')." <b>$f_value</b>*)";
		break;

	}


	if ($order=='name')
		$order='`Site Name`';
	elseif ($order=='url')
		$order='`Site URL`';

	elseif ($order=='code')
		$order='`Site Code`';
	elseif ($order=='sales') {

		switch ($period) {
		case 'three_year':
			$order='`Supplier 3 Year Acc Parts Sold Amount`';
			break;
		case 'year':
			$order='`Supplier 1 Year Acc Parts Sold Amount`';
			break;
		case 'quarter':
			$order='`Supplier 1 Quarter Acc Parts Sold Amount`';
			break;

		case 'six_month':
			$order='`Supplier 6 Month Acc Parts Sold Amount`';

			break;
		case 'month':
			$order='`Supplier 1 Month Acc Parts Sold Amount`';
			break;
		case 'ten_day':
			$order='`Supplier 10 Day Acc Parts Sold Amount`';
			break;
		case 'week':
			$order='`Supplier 1 Week Acc Parts Sold Amount`';
			break;
		case 'yeartoday':
			$order='`Supplier Year To Day Acc Parts Sold Amount`';
			break;
		case 'monthtoday':
			$order='`Supplier Month To Day Acc Parts Sold Amount`';
			break;
		case 'weektoday':
			$order='`Supplier Week To Day Acc Parts Sold Amount`';
			break;
		default:
			$order='`Supplier Total Acc Parts Sold Amount`';
			break;
		}




	}

	elseif ($order=='pending_pos') {
		$order='`Supplier Open Purchase Orders`';

	}
	elseif ($order=='margin') {

		switch ($period) {
		case 'three_year':
			$order='`Supplier 3 Year Acc Parts Margin`';
			break;
		case 'year':
			$order='`Supplier 1 Year Acc Parts Margin`';
			break;
		case 'quarter':
			$order='`Supplier 1 Quarter Acc Parts Margin`';
			break;

		case 'six_month':
			$order='`Supplier 6 Month Acc Parts Margin`';

			break;
		case 'month':
			$order='`Supplier 1 Month Acc Parts Margin`';
			break;
		case 'ten_day':
			$order='`Supplier 10 Day Acc Parts Margin`';
			break;
		case 'week':
			$order='`Supplier 1 Week Acc Parts Margin`';
			break;
		case 'yeartoday':
			$order='`Supplier Year To Day Acc Parts Margin`';
			break;
		case 'monthtoday':
			$order='`Supplier Month To Day Acc Parts Margin`';
			break;
		case 'weektoday':
			$order='`Supplier Week To Day Acc Parts Margin`';
			break;
		default:
			$order='`Supplier Total Acc Parts Margin`';
			break;
		}
	}
	elseif ($order=='cost') {

		switch ($period) {
		case 'three_year':
			$order='`Supplier 3 Year Acc Parts Cost`';
			break;
		case 'year':
			$order='`Supplier 1 Year Acc Parts Cost`';
			break;
		case 'quarter':
			$order='`Supplier 1 Quarter Acc Parts Cost`';
			break;

		case 'six_month':
			$order='`Supplier 6 Month Acc Parts Cost`';

			break;
		case 'month':
			$order='`Supplier 1 Month Acc Parts Cost`';
			break;
		case 'ten_day':
			$order='`Supplier 10 Day Acc Parts Cost`';
			break;
		case 'week':
			$order='`Supplier 1 Week Acc Parts Cost`';
			break;
		case 'yeartoday':
			$order='`Supplier Year To Day Acc Parts Cost`';
			break;
		case 'monthtoday':
			$order='`Supplier Month To Day Acc Parts Cost`';
			break;
		case 'weektoday':
			$order='`Supplier Week To Day Acc Parts Cost`';
			break;
		default:
			$order='`Supplier Total Acc Parts Cost`';
			break;
		}
	}

	elseif ($order=='profit_after_storing') {

		switch ($period) {
		case 'three_year':
			$order='`Supplier 3 Year Acc Parts Profit After Storing`';
			break;
		case 'year':
			$order='`Supplier 1 Year Acc Parts Profit After Storing`';
			break;
		case 'quarter':
			$order='`Supplier 1 Quarter Acc Parts Profit After Storing`';
			break;

		case 'six_month':
			$order='`Supplier 6 Month Acc Parts Profit After Storing`';

			break;
		case 'month':
			$order='`Supplier 1 Month Acc Parts Profit After Storing`';
			break;
		case 'ten_day':
			$order='`Supplier 10 Day Acc Parts Profit After Storing`';
			break;
		case 'week':
			$order='`Supplier 1 Week Acc Parts Profit After Storing`';
			break;
		case 'yeartoday':
			$order='`Supplier Year To Day Acc Parts Profit After Storing`';
			break;
		case 'monthtoday':
			$order='`Supplier Month To Day Acc Parts Profit After Storing`';
			break;
		case 'weektoday':
			$order='`Supplier Week To Day Acc Parts Profit After Storing`';
			break;
		default:
			$order='`Supplier Total Acc Parts Profit After Storing`';
			break;
		}
	}

	elseif ($order=='profit') {

		switch ($period) {
		case 'three_year':
			$order='`Supplier 3 Year Acc Parts Profit`';
			break;
		case 'year':
			$order='`Supplier 1 Year Acc Parts Profit`';
			break;
		case 'quarter':
			$order='`Supplier 1 Quarter Acc Parts Profit`';
			break;

		case 'six_month':
			$order='`Supplier 6 Month Acc Parts Profit`';

			break;
		case 'month':
			$order='`Supplier 1 Month Acc Parts Profit`';
			break;
		case 'ten_day':
			$order='`Supplier 10 Day Acc Parts Profit`';
			break;
		case 'week':
			$order='`Supplier 1 Week Acc Parts Profit`';
			break;
		case 'yeartoday':
			$order='`Supplier Year To Day Acc Parts Profit`';
			break;
		case 'monthtoday':
			$order='`Supplier Month To Day Acc Parts Profit`';
			break;
		case 'weektoday':
			$order='`Supplier Week To Day Acc Parts Profit`';
			break;
		default:
			$order='`Supplier Total Acc Parts Profit`';
			break;
		}
	}
	else {
		$order=`Site Code`;
	}

	//print $order;
	//    elseif($order='used_in')
	//        $order='Supplier Product XHTML Sold As';

	$sql="select `Site Code`,`Site Name`,`Site Key`,`Site URL`   from `Site Dimension` $where $wheref order by $order $order_direction limit $start_from,$number_results";

	//print $sql;

	$result=mysql_query($sql);
	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {

		$name="<a href='site.php?id=".$row['Site Key']."'>".$row['Site Name']."</a>";
		$code="<a href='site.php?id=".$row['Site Key']."'>".$row['Site Code']."</a>";
		/*
                switch ($period) {
                case 'three_year':
                    $sales=money($row['Supplier 3 Year Acc Parts Sold Amount']);
                    $profit=money($row['Supplier 3 Year Acc Parts Profit']);
                    $profit_after_storing=money($row['Supplier 3 Year Acc Parts Profit After Storing']);
                    $cost=money($row['Supplier 3 Year Acc Parts Cost']);
                    $margin=percentage($row['Supplier 3 Year Acc Parts Margin'],1);
                    break;
                case 'year':
                    $sales=money($row['Supplier 1 Year Acc Parts Sold Amount']);
                    $profit=money($row['Supplier 1 Year Acc Parts Profit']);
                    $profit_after_storing=money($row['Supplier 1 Year Acc Parts Profit After Storing']);
                    $cost=money($row['Supplier 1 Year Acc Parts Cost']);
                    $margin=percentage($row['Supplier 1 Year Acc Parts Margin'],1);
                    break;
                case 'quarter':
                    $sales=money($row['Supplier 1 Quarter Acc Parts Sold Amount']);
                    $profit=money($row['Supplier 1 Quarter Acc Parts Profit']);
                    $profit_after_storing=money($row['Supplier 1 Quarter Acc Parts Profit After Storing']);
                    $cost=money($row['Supplier 1 Quarter Acc Parts Cost']);
                    $margin=percentage($row['Supplier 1 Quarter Acc Parts Margin'],1);
                    break;

                case 'six_month':
                    $sales=money($row['Supplier 6 Month Acc Parts Sold Amount']);
                    $profit=money($row['Supplier 6 Month Acc Parts Profit']);
                    $profit_after_storing=money($row['Supplier 6 Month Acc Parts Profit After Storing']);
                    $cost=money($row['Supplier 6 Month Acc Parts Cost']);
                    $margin=percentage($row['Supplier 6 Month Acc Parts Margin'],1);
                    break;
                case 'month':
                    $sales=money($row['Supplier 1 Month Acc Parts Sold Amount']);
                    $profit=money($row['Supplier 1 Month Acc Parts Profit']);
                    $profit_after_storing=money($row['Supplier 1 Month Acc Parts Profit After Storing']);
                    $cost=money($row['Supplier 1 Month Acc Parts Cost']);
                    $margin=percentage($row['Supplier 1 Month Acc Parts Margin'],1);
                    break;
                case 'ten_day':
                    $sales=money($row['Supplier 10 Day Acc Parts Sold Amount']);
                    $profit=money($row['Supplier 10 Day Acc Parts Profit']);
                    $profit_after_storing=money($row['Supplier 10 Day Acc Parts Profit After Storing']);
                    $cost=money($row['Supplier 10 Day Acc Parts Cost']);
                    $margin=percentage($row['Supplier 10 Day Acc Parts Margin'],1);
                    break;
                case 'week':
                    $sales=money($row['Supplier 1 Week Acc Parts Sold Amount']);
                    $profit=money($row['Supplier 1 Week Acc Parts Profit']);
                    $profit_after_storing=money($row['Supplier 1 Week Acc Parts Profit After Storing']);
                    $cost=money($row['Supplier 1 Week Acc Parts Cost']);
                    $margin=percentage($row['Supplier 1 Week Acc Parts Margin'],1);
                    break;
                case 'yeartoday':
                    $sales=money($row['Supplier Year To Day Acc Parts Sold Amount']);
                    $profit=money($row['Supplier Year To Day Acc Parts Profit']);
                    $profit_after_storing=money($row['Supplier Year To Day Acc Parts Profit After Storing']);
                    $cost=money($row['Supplier Year To Day Acc Parts Cost']);
                    $margin=percentage($row['Supplier Year To Day Acc Parts Margin'],1);
                    break;
                case 'monthtoday':
                    $sales=money($row['Supplier Month To Day Acc Parts Sold Amount']);
                    $profit=money($row['Supplier Month To Day Acc Parts Profit']);
                    $profit_after_storing=money($row['Supplier Month To Day Acc Parts Profit After Storing']);
                    $cost=money($row['Supplier Month To Day Acc Parts Cost']);
                    $margin=percentage($row['Supplier Month To Day Acc Parts Margin'],1);
                    break;
                case 'weektoday':
                    $sales=money($row['Supplier Week To Day Acc Parts Sold Amount']);
                    $profit=money($row['Supplier Week To Day Acc Parts Profit']);
                    $profit_after_storing=money($row['Supplier Week To Day Acc Parts Profit After Storing']);
                    $cost=money($row['Supplier Week To Day Acc Parts Cost']);
                    $margin=percentage($row['Supplier Week To Day Acc Parts Margin'],1);
                    break;
                default:
                    $sales=money($row['Supplier Total Acc Parts Sold Amount']);
                    $profit=money($row['Supplier Total Acc Parts Profit']);
                    $profit_after_storing=money($row['Supplier Total Acc Parts Profit After Storing']);
                    $cost=money($row['Supplier Total Acc Parts Cost']);
                    $margin=percentage($row['Supplier Total Acc Parts Margin'],1);
                    break;
                }
        */



		$data[]=array(
			'id'=>$row['Site Key'],
			'name'=>$name,
			'code'=>$code,
			'url'=>$row['Site URL'],

			/*
                    'for_sale'=>number($row['Supplier For Sale Products']),
                    'low'=>number($row['Supplier Low Availability Products']),
                    'outofstock'=>number($row['Supplier Out Of Stock Products']),
                    'location'=>$row['Supplier Main Location'],
                    'email'=>$row['Supplier Main XHTML Email'],
                    'tel'=>$row['Supplier Main XHTML Telephone'],
                    'contact'=>$row['Supplier Main Contact Name'],
                    'sales'=>$sales,
                    'profit'=>$profit,
                    'profit_after_storing'=>$profit_after_storing,
                    'cost'=>$cost,
                    'pending_pos'=>number($row['Supplier Open Purchase Orders']),
                    'margin'=>$margin

                    */
		);
	}


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
			'sort_key'=>$_order,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_text'=>$rtext,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}
