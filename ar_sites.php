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
	$response=array('state'=>405,'msg'=>'Non acceptable request (t)');
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
case ('users_in_site'):
	list_users_in_site();
	break;
case ('queries'):
	list_queries();
	break;
case ('query_history'):
	list_query_history();
	break;
case('users'):
	list_users_requesting();
	break;
case('requests'):
	list_requests();
	break;
case('page_stats'):
	list_page_stats();
	break;
case('is_page_store_code'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key'),
			'query'=>array('type'=>'string')
		));
	is_page_store_code($data);
	break;
case('is_site_code'):
	$data=prepare_values($_REQUEST,array(
			'query'=>array('type'=>'string')
		));
	$data['scope']='Code';	
	is_a_site_with($data);
	break;
case('is_site_url'):
	$data=prepare_values($_REQUEST,array(
			'query'=>array('type'=>'string')
		));
	$data['scope']='URL';		
	is_a_site_with($data);
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
	elseif ($parent=='product') {
		$conf=$_SESSION['state']['product']['pages'];
		$conf_table='product';
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

	if (isset( $_REQUEST['elements_System'])) {
		$elements['System']=$_REQUEST['elements_System'];
	}
	if (isset( $_REQUEST['elements_Info'])) {
		$elements['Info']=$_REQUEST['elements_Info'];
	}
	if (isset( $_REQUEST['elements_Department'])) {
		$elements['Department']=$_REQUEST['elements_Department'];
	}
	if (isset( $_REQUEST['elements_Family'])) {
		$elements['Family']=$_REQUEST['elements_Family'];
	}
	if (isset( $_REQUEST['elements_Product'])) {
		$elements['Product']=$_REQUEST['elements_Product'];
	}
	if (isset( $_REQUEST['elements_FamilyCategory'])) {
		$elements['FamilyCategory']=$_REQUEST['elements_FamilyCategory'];
	}
	if (isset( $_REQUEST['elements_ProductCategory'])) {
		$elements['ProductCategory']=$_REQUEST['elements_ProductCategory'];
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

	$table='`Page Store Dimension` PS left join `Page Dimension` P on (P.`Page Key`=PS.`Page Key`) left join `Site Dimension` S on (S.`Site Key`=`Page Site Key`) ';

	switch ($parent) {
	case('store'):
		$where.=sprintf(' and `Page Store Key`=%d   ',$parent_key);
		break;
	case('site'):
		$where.=sprintf(' and `Page Site Key`=%d',$parent_key);
		break;
	case('department'):
		$where.=sprintf('  and `Page Parent Key`=%d  and `Page Store Section`="Department Catalogue"  ',$parent_key);
		break;
	case('product'):
		$where.=sprintf('  and `Page Parent Key`=%d  and `Page Store Section`="Product Description"  ',$parent_key);
		break;
	case('family'):
		$where.=sprintf('  and `Page Parent Key`=%d  and `Page Store Section`="Family Catalogue"  ',$parent_key);
		break;
	case('product_form'):
		$where.=sprintf('  and `Product ID`=%d   ',$parent_key);
		$table.=' left join `Page Product Dimension` PPD on (PPD.`Page Key`=P.`Page Key`)';
		break;
	default:


	}

	$group='';

	if ($parent=='site') {

		$_elements='';
		$count_elements=0;
		foreach ($elements as $_key=>$_value) {
			if ($_value) {
				$_elements.=',"'.$_key.'"';
				$count_elements++;
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ($count_elements<7) {
			$where.=' and `Page Store Section Type` in ('.$_elements.')' ;
		}
		//print count($count_elements);

	}



	$wheref='';
	if ($f_field=='code'  and $f_value!='')
		$wheref.=" and `Page Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='title' and $f_value!='')
		$wheref.=" and  `Page Store Title` like '".addslashes($f_value)."%'";



	$sql="select count(*) as total from $table $where $wheref";
	//print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from $table $where";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}

	$rtext=number($total_records)." ".ngettext('page','pages',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';







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
	elseif ($order=='users') {
		$order="`Page Store $interval_db Acc Users`";
	}elseif ($order=='visitors') {
		$order="`Page Store $interval_db Acc Visitors`";
	}elseif ($order=='sessions') {
		$order="`Page Store $interval_db Acc Sessions`";
	}elseif ($order=='requests') {
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



	$sql="select *,`Site Code`,S.`Site Key`,`Page Short Title`,`Page Preview Snapshot Image Key`,`Page Store Section`,`Page Parent Code`,`Page Parent Key`,`Page URL`,P.`Page Key`,`Page Store Title`,`Page Code`  from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$result=mysql_query($sql);
	$data=array();
	//print $sql;
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

		//'Front Page Store','Search','Product Description','Information','Category Catalogue','Family Catalogue','Department Catalogue','Unknown','Store Catalogue','Registration','Client Section','Checkout','Login','Welcome','Not Found','Reset','Basket'
		switch ($row['Page Store Section']) {
		case 'Department Catalogue':
			$type=sprintf("d(<a href='department.php?id=%d'>%s</a>)",$row['Page Parent Key'],$row['Page Parent Code']);
			break;
		case 'Family Catalogue':
			$type=sprintf("f(<a href='family.php?id=%d'>%s</a>)",$row['Page Parent Key'],$row['Page Parent Code']);
			break;
		case 'Welcome':
			$type=_('Welcome');
			break;
		case 'Login':
			$type=_('Login');
			break;
		case 'Information':
			$type=_('Information');
			break;
		case 'Checkout':
			$type=_('Checkout');
			break;
		case 'Reset':
			$type=_('Reset');
			break;
		case 'Registration':
			$type=_('Registration');
			break;
		case 'Not Found':
			$type=_('Not Found');
			break;
		case 'Client Section':
			$type=_('Client Section');
			break;
		case 'Client Section':
			$type=_('Client Section');
			break;
		case 'Front Page Store':
			$type=_('Home');
			break;
		case 'Basket':
			$type=_('Basket');
			break;
		default:
			$type=_('Other').' '.$row['Page Store Section'];
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
		$conf_table='sites';
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


	$_SESSION['state'][$conf_table]['sites']['order']=$order;
	$_SESSION['state'][$conf_table]['sites']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['sites']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['sites']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['sites']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['sites']['f_value']=$f_value;
	$_SESSION['state'][$conf_table]['sites']['percentages']=$percentages;
	$_SESSION['state'][$conf_table]['sites']['period']=$period;

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

	$rtext=number($total_records)." ".ngettext('website','websites',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';







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
	
		$data[]=array(
			'id'=>$row['Site Key'],
			'name'=>$name,
			'code'=>$code,
			'url'=>$row['Site URL'],

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




function list_page_stats() {



	global $user;
	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent='none';

	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		$parent_key='';

	if (isset( $_REQUEST['group_by']))
		$group_by=$_REQUEST['group_by'];
	else
		$group_by='';

	if ($parent=='store') {
		$conf=$_SESSION['state']['store']['sites'];
		$conf_table='store';
	}
	elseif ($parent=='none') {
		$conf=$_SESSION['state']['sites']['sites'];
		$conf_table='department';
	}
	elseif ($parent=='page') {
		$conf=$_SESSION['state']['sites']['pages'];
		$conf_table='sites';
		$conf_var='pages';
	}
	elseif ($parent=='user') {
		$conf=$_SESSION['state']['site_user']['visit_pages'];
		$conf_table='site_user';
		$conf_var='visit_pages';
	}
	elseif ($parent=='site') {
		$conf=$_SESSION['state']['site_user']['visit_pages'];
		$conf_table='site';
		$conf_var='pages';
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
	/*
	if (isset( $_REQUEST['period']))
		$period=$_REQUEST['period'];
	else
		$period=$conf['period'];

	if (isset( $_REQUEST['percentages']))
		$percentages=$_REQUEST['percentages'];
	else
		$percentages=$conf['percentages'];
*/
	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state'][$conf_table][$conf_var]['order']=$order;
	$_SESSION['state'][$conf_table][$conf_var]['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table][$conf_var]['nr']=$number_results;
	$_SESSION['state'][$conf_table][$conf_var]['sf']=$start_from;
	$_SESSION['state'][$conf_table][$conf_var]['f_field']=$f_field;
	$_SESSION['state'][$conf_table][$conf_var]['f_value']=$f_value;
	//$_SESSION['state'][$conf_table][$conf_var]['percentages']=$percentages;
	//$_SESSION['state'][$conf_table][$conf_var]['period']=$period;

	$_order=$order;
	$_dir=$order_direction;

	if (count($user->websites)==0) {
		$where='where false ';
	}else {
		$where='where true ';
	}

	switch ($parent) {
	case('page'):
	case('site_hits'):
		$where.=sprintf(' and URD.`Page Key`=%d',$parent_key);
		break;
	case('user'):
		$where.=sprintf(' and URD.`User Key`=%d',$parent_key);
		break;
	case('site'):
		$where.=sprintf(' and PSD.`Page Site Key`=%d',$parent_key);
		break;
	default:
		$where.=sprintf(' and `Site Key` in (%s)',join(',',$user->websites));


		break;

	}

	switch ($group_by) {
	case('users'):
		$group=sprintf('URD.`User Key`');
		break;
	case('pages'):
	case('site_hits'):
		$group=sprintf('URD.`Page Key`');
		break;
	default:
		$group=true;
		break;

	}



	$wheref='';
	if ($f_field=='name'  and $f_value!='')
		$wheref.=" and `Site Name` like '".addslashes($f_value)."%'";
	elseif ($f_field=='url' and $f_value!='')
		$wheref.=" and  `Site URL` like '%".addslashes($f_value)."%'";

	switch ($group_by) {
	case('users'):
	case('pages'):
		$sql="select count(*) as total from (select count($group) as tot from `User Request Dimension` URD $where $wheref group by $group) as temp";
		break;
	case('hits'):
		$sql="select count(*) as total from `User Request Dimension` URD $where $wheref";
		break;
	case('site_hits'):
		$sql="select count(*) as total from (select count($group) as tot from `User Request Dimension` URD left join `Page Store Dimension` PSD on (URD.`Page Key`=PSD.`Page Key`) $where $wheref group by $group) as temp";
		break;
	default:
		$sql="";
		break;
	}

	//$sql="select count(*) as total from "


	//print $sql; exit;

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

	switch ($group_by) {
	case('users'):
		$rtext=number($total_records)." ".ngettext('user','users',$total_records);
		break;
	case('hits'):
	case('site_hits'):
		$rtext=number($total_records)." ".ngettext('hit','hits',$total_records);
		break;
	case('pages'):
		$rtext=number($total_records)." ".ngettext('page','pages',$total_records);
		break;
	default:
		$rtext=number($total_records)." ".ngettext('record','records',$total_records);
		break;

	}



	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


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

	$order=true;
	/*
	switch($order){
		//if($parent=='')
		case 'email':
		$order='CD.`Customer Main Plain Email` ';
		case 'visits':
		$order='total ';
		case 'name':
		$order='CD.`Customer Name` ';
		case 'code':
		$order='CD.`Customer Key` ';
	}
*/
	switch ($group_by) {
	case('users'):
		$sql="select `Customer Name`, `Customer Key`, `Customer Main Plain Email`, `Page Title`, count(*) as total from `User Request Dimension` URD left join `User Dimension` UD on (URD.`User Key` = UD.`User Key`) left join `Customer Dimension` CD on (UD.`User Parent Key` = CD.`Customer Key`) left join `Page Dimension` PD on (URD.`Page Key` = PD.`Page Key`) $where $wheref group by $group order by $order $order_direction limit $start_from,$number_results ";
		break;
	case('hits'):
		$sql=sprintf("select CD.`Customer Name`, CD.`Customer Key`, CD.`Customer Main Plain Email`, URD.`Page Key` as current_page_key, URD.`Previous Page Key` previous_page_key, PD.`Page Title` as prev_page_title, URD.`Date`, URD.`IP` from `User Request Dimension` URD left join `User Dimension` UD on (URD.`User Key` = UD.`User Key`) left join `Customer Dimension` CD on (UD.`User Parent Key` = CD.`Customer Key`) left join `Page Dimension` PD on (URD.`Previous Page Key`=PD.`Page Key`) $where $wheref order by $order $order_direction limit $start_from,$number_results ");
		break;
	case('pages'):
		$sql=sprintf("select count(*) as total_visits, PD.`Page Title`, PD.`Page Key` from `User Request Dimension` URD left join `Page Dimension` PD on (URD.`Page Key` = PD.`Page Key`) $where $wheref group by $group order by $order $order_direction limit $start_from, $number_results ");
		break;
	case('site_hits'):
		$sql=sprintf("select CD.`Customer Name`, CD.`Customer Key`, CD.`Customer Main Plain Email`, URD.`Page Key` as current_page_key, URD.`Previous Page Key` previous_page_key, PD.`Page Title` as prev_page_title, URD.`Date`, URD.`IP` from `User Request Dimension` URD left join `User Dimension` UD on (URD.`User Key` = UD.`User Key`) left join `Customer Dimension` CD on (UD.`User Parent Key` = CD.`Customer Key`) left join `Page Dimension` PD on (URD.`Previous Page Key`=PD.`Page Key`) left join `Page Store Dimension` PSD on (URD.`Page Key`=PSD.`Page Site Key`) $where $wheref order by $order $order_direction limit $start_from,$number_results ");
		break;
	default:
		$sql='';
		break;

	}



	//$sql=sprintf("select * from `User Request Dimension` URD left join `Page Store Dimension` PSD on (URD.`Page Key`=PSD.`Page Site Key`) left join `User Dimension` U on (URD.`User Key`=U.`User Key`) left join `Customer Dimension` C on (C.`Customer Key`=U.`User Parent Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results ");

	// print $sql; exit;

	$result=mysql_query($sql);


	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {

		switch ($group_by) {
		case('users'):
			$name="<a href='customer.php?id=".$row['Customer Key']."'>".$row['Customer Name']."</a>";
			//$code="<a href='site.php?id=".$row['Site Key']."'>".$row['Site Code']."</a>";
			if ($row['Customer Name']=='') {
				continue;
			}
			$data[]=array(
				'code'=>$row['Customer Key'],//$row['Site Key'],
				'name'=>$name,
				'email'=>$row['Customer Main Plain Email'],
				'visits'=>$row['total'],//$row['Site URL'],
			);
			break;
		case('hits'):
		case('site_hits'):
			$name="<a href='customer.php?id=".$row['Customer Key']."'>".$row['Customer Name']."</a>";
			$prev_page="<a href='page.php?id=".$row['previous_page_key']."'>".$row['prev_page_title']."</a>";

			if ($row['Customer Name']=='') {
				$name='Not Registered';
			}
			if ($row['prev_page_title']=='') {
				$prev_page='N/A';
			}
			$data[]=array(
				'code'=>$row['Customer Key'],//$row['Site Key'],
				'name'=>$name,
				'email'=>$row['Customer Main Plain Email'],
				'previous_page'=>$prev_page,
				'ip'=>$row['IP'],
				'date'=>$row['Date']
			);
			break;
		case('pages'):
			$page="<a href='page.php?id=".$row['Page Key']."'>".$row['Page Title']."</a>";
			$code="<a href='page.php?id=".$row['Page Key']."'>".$row['Page Key']."</a>";
			$data[]=array(
				'code'=>$code,
				'total_visits'=>$row['total_visits'],
				'page'=>$page,
			);
			break;
		}
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


function list_users_in_site() {

	global $user;


	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		exit("error no parent key");

	$conf=$_SESSION['state']['site']['users'];
	$conf_table='site';
	$conf_var='users';


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


	/*
	if (isset( $_REQUEST['period']))
		$period=$_REQUEST['period'];
	else
		$period=$conf['period'];

	if (isset( $_REQUEST['percentages']))
		$percentages=$_REQUEST['percentages'];
	else
		$percentages=$conf['percentages'];
*/
	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state'][$conf_table][$conf_var]['order']=$order;
	$_SESSION['state'][$conf_table][$conf_var]['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table][$conf_var]['nr']=$number_results;
	$_SESSION['state'][$conf_table][$conf_var]['sf']=$start_from;
	$_SESSION['state'][$conf_table][$conf_var]['f_field']=$f_field;
	$_SESSION['state'][$conf_table][$conf_var]['f_value']=$f_value;
	//$_SESSION['state'][$conf_table][$conf_var]['percentages']=$percentages;
	//$_SESSION['state'][$conf_table][$conf_var]['period']=$period;

	$_order=$order;
	$_dir=$order_direction;

	if (count($user->websites)==0) {
		$where='where false ';
	}else {
		$where=sprintf(' where `User Site Key`=%d  and `User Type`="Customer" and `User Login Count`>0',$parent_key);

	}


	$wheref='';
	if ($f_field=='handle' and $f_value!='')
		$wheref.=" and  `User Handle` like '".addslashes($f_value)."%'";
	elseif ($f_field=='customer' and $f_value!='')
		$wheref=sprintf('  and  `User Alias`  REGEXP "[[:<:]]%s" ',addslashes($f_value));



	$sql="select  count(*) as total from `User Dimension`  $where $wheref  ";
	// print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select  count( *) as total_without_filters from `User Dimension`  $where     ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);





	$rtext=number($total_records)." ".ngettext('user','users',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';







	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	$filter_msg='';

	switch ($f_field) {
	case('handle'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any handle like")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total (".ngettext('query handle','handles like',$total)." <b>$f_value</b>*)";
		break;
	case('customer'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer like")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total (".ngettext('query customer','customers like',$total)." <b>$f_value</b>*)";
		break;

	}

	$_order=$order;
	$_dir=$order_direction;

	if ($order=='customer')
		$order='`User Alias`';
	elseif ($order=='handle')
		$order='`User Handle`';
	elseif ($order=='requests')
		$order='`User Requests Count`';
	elseif ($order=='last_visit')
		$order='`User Last Request`';
	elseif ($order=='logins')
		$order='`User Login Count`';
	else
		$order='`User Alias`';

	$sql="select `User Inactive Note`,`User Active`,`User Login Count`,`User Key`,`User Parent Key`,`User Alias`,`User Handle`,`User Last Request`,`User Requests Count` from  `User Dimension` U   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
	//print $sql;

	$result=mysql_query($sql);


	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {
		$customer="<a href='customer.php?id=".$row['User Parent Key']."'>".$row['User Alias']."</a>";

		if ($row['User Active']=='Yes')
			$handle="<a href='site_user.php?id=".$row['User Key']."'>".$row['User Handle']."</a>";
		else
			$handle="<a style='color:#777;font-style:italic' href='site_user.php?id=".$row['User Key']."'>".$row['User Inactive Note']."</a>";


		$data[]=array(
			'customer'=>$customer,
			'handle'=>$handle,
			'logins'=>number($row['User Login Count']),

			'requests'=>number($row['User Requests Count']),
			'last_visit'=>strftime("%a %e %b %y %H:%M", strtotime($row['User Last Request']." +00:00")),

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

function list_users_requesting() {

	global $user;
	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent='none';

	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		$parent_key='';

	if (isset( $_REQUEST['group_by']))
		$group_by=$_REQUEST['group_by'];
	else
		$group_by='';

	if ($parent=='store') {
		exit;
		$conf=$_SESSION['state']['store']['users'];
		$conf_table='store';
	}
	elseif ($parent=='none') {
		$conf=$_SESSION['state']['sites']['users'];
		$conf_table='department';
	}
	elseif ($parent=='page') {
		$conf=$_SESSION['state']['page']['users'];
		$conf_table='sites';
		$conf_var='users';
	}
	elseif ($parent=='site') {
		$conf=$_SESSION['state']['site']['users'];
		$conf_table='site';
		$conf_var='users';
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


	/*
	if (isset( $_REQUEST['period']))
		$period=$_REQUEST['period'];
	else
		$period=$conf['period'];

	if (isset( $_REQUEST['percentages']))
		$percentages=$_REQUEST['percentages'];
	else
		$percentages=$conf['percentages'];
*/
	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state'][$conf_table][$conf_var]['order']=$order;
	$_SESSION['state'][$conf_table][$conf_var]['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table][$conf_var]['nr']=$number_results;
	$_SESSION['state'][$conf_table][$conf_var]['sf']=$start_from;
	$_SESSION['state'][$conf_table][$conf_var]['f_field']=$f_field;
	$_SESSION['state'][$conf_table][$conf_var]['f_value']=$f_value;
	//$_SESSION['state'][$conf_table][$conf_var]['percentages']=$percentages;
	//$_SESSION['state'][$conf_table][$conf_var]['period']=$period;

	$_order=$order;
	$_dir=$order_direction;

	if (count($user->websites)==0) {
		$where='where false ';
	}else {
		$where='where URD.`Is User`="Yes" ';
	}

	switch ($parent) {


	case('page'):
		$where.=sprintf(' and URD.`Page Key`=%d',$parent_key);
		break;
	case('site'):
		$where.=sprintf(' and URD.`Site Key`=%d',$parent_key);
		break;
	default:
		$where.=sprintf(' and `Site Key` in (%s)',join(',',$user->websites));


		break;

	}



	$wheref='';
	// if ($f_field=='name'  and $f_value!='')
	//  $wheref.=" and `Site Name` like '".addslashes($f_value)."%'";
	// elseif ($f_field=='url' and $f_value!='')
	//  $wheref.=" and  `Site URL` like '%".addslashes($f_value)."%'";





	$sql="select  count( Distinct `User Key`) as total from `User Request Dimension` URD  $where  $wheref  ";

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
		$sql="select  count( Distinct `User Key`) as total from `User Request Dimension` URD $where    ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}

	$rtext=number($total_records)." ".ngettext('user','users',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';





	/*
	$result=mysql_query($sql);
	$total=mysql_num_rows($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select  URD.`User Key` from `User Request Dimension` URD left join `User Dimension` UD on (URD.`User Key` = UD.`User Key`) left join `Customer Dimension` CD on (UD.`User Parent Key` = CD.`Customer Key`) left join `Page Dimension` PD on (URD.`Page Key` = PD.`Page Key`) $where $wheref group by   URD.`User Key`  ";
		$result=mysql_query($sql);
		$total_records=mysql_num_rows($result);
		$filtered=$row['total']-$total;


	}


	switch ($group_by) {
	case('users'):
		$rtext=number($total_records)." ".ngettext('user','users',$total_records);
		break;
	case('hits'):
	case('site_hits'):
		$rtext=number($total_records)." ".ngettext('hit','hits',$total_records);
		break;
	case('pages'):
		$rtext=number($total_records)." ".ngettext('page','pages',$total_records);
		break;
	default:
		$rtext=number($total_records)." ".ngettext('','',$total_records);
		break;

	}
*/


	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


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


	$_order=$order;
	$_dir=$order_direction;

	if ($order=='customer')
		$order='`Customer Name`';
	elseif ($order=='handle')
		$order='`User Handle`';
	elseif ($order=='visits')
		$order='visits';
	elseif ($order=='last_visit')
		$order='last_visit';


	$sql=sprintf("select `Customer Key`,`Customer Name`,`User Handle`,count(*) visits, max(`Date`) last_visit from `User Request Dimension` URD left join `User Dimension` U on (URD.`User Key`=U.`User Key`) left join `Customer Dimension` C on (C.`Customer Key`=U.`User Parent Key`)  $where $wheref group by URD.`User Key` order by $order $order_direction limit $start_from,$number_results ");

	// print $sql; exit;

	$result=mysql_query($sql);


	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {
		$customer="<a href='customer.php?id=".$row['Customer Key']."'>".$row['Customer Name']."</a>";
		$data[]=array(
			'customer'=>$customer,
			'handle'=>$row['User Handle'],
			'visits'=>$row['visits'],
			'last_visit'=>strftime("%a %e %b %y %H:%M", strtotime($row['last_visit']." +00:00")),

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


function list_requests() {

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
		$conf=$_SESSION['state']['store']['users'];
		$conf_table='store';
	}
	elseif ($parent=='none') {
		$conf=$_SESSION['state']['sites']['users'];
		$conf_table='department';
	}
	elseif ($parent=='page') {
		$conf=$_SESSION['state']['page']['requests'];
		$conf_table='page';
		$conf_var='requests';
	}
	elseif ($parent=='site') {
		$conf=$_SESSION['state']['site']['users'];
		$conf_table='site';
		$conf_var='pages';
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


	/*
	if (isset( $_REQUEST['period']))
		$period=$_REQUEST['period'];
	else
		$period=$conf['period'];

	if (isset( $_REQUEST['percentages']))
		$percentages=$_REQUEST['percentages'];
	else
		$percentages=$conf['percentages'];
*/
	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state'][$conf_table][$conf_var]['order']=$order;
	$_SESSION['state'][$conf_table][$conf_var]['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table][$conf_var]['nr']=$number_results;
	$_SESSION['state'][$conf_table][$conf_var]['sf']=$start_from;
	$_SESSION['state'][$conf_table][$conf_var]['f_field']=$f_field;
	$_SESSION['state'][$conf_table][$conf_var]['f_value']=$f_value;
	//$_SESSION['state'][$conf_table][$conf_var]['percentages']=$percentages;
	//$_SESSION['state'][$conf_table][$conf_var]['period']=$period;

	$_order=$order;
	$_dir=$order_direction;

	if (count($user->websites)==0) {
		$where='where false ';
	}else {
		$where='where true ';
	}

	switch ($parent) {
	case('store'):
		$where.=sprintf(' and URD.`Page Key`=%d',$parent_key);
		break;

	case('page'):
		$where.=sprintf(' and URD.`Page Key`=%d',$parent_key);
		break;
	case('site'):
		$where.=sprintf(' and URD.`Site Key`=%d',$parent_key);
		break;
	default:
		$where.=sprintf(' and `Site Key` in (%s)',join(',',$user->websites));


		break;

	}



	$wheref='';
	if ($f_field=='name'  and $f_value!='')
		$wheref.=" and `Site Name` like '".addslashes($f_value)."%'";
	elseif ($f_field=='url' and $f_value!='')
		$wheref.=" and  `Site URL` like '%".addslashes($f_value)."%'";



	$sql="select  URD.`User Key` from `User Request Dimension` URD left join `User Dimension` UD on (URD.`User Key` = UD.`User Key`) left join `Customer Dimension` CD on (UD.`User Parent Key` = CD.`Customer Key`) left join `Page Dimension` PD on (URD.`Page Key` = PD.`Page Key`) $where   ";



	$result=mysql_query($sql);
	$total=mysql_num_rows($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select  URD.`User Key` from `User Request Dimension` URD left join `User Dimension` UD on (URD.`User Key` = UD.`User Key`) left join `Customer Dimension` CD on (UD.`User Parent Key` = CD.`Customer Key`) left join `Page Dimension` PD on (URD.`Page Key` = PD.`Page Key`) $where $wheref   ";
		$result=mysql_query($sql);
		$total_records=mysql_num_rows($result);
		$filtered=$row['total']-$total;


	}


	$rtext=number($total_records)." ".ngettext('request','requests',$total_records);





	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


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

	// $order=true;
	/*
	switch($order){
		//if($parent=='')
		case 'email':
		$order='CD.`Customer Main Plain Email` ';
		case 'visits':
		$order='total ';
		case 'name':
		$order='CD.`Customer Name` ';
		case 'code':
		$order='CD.`Customer Key` ';
	}
*/

	$_order=$order;
	$_dir=$order_direction;

	$order='`Date`';


	$sql=sprintf("select `URL`,PSD.`Page Store Section`, PP.`Page Code` previous_code ,PP.`Page Key` previous_page_key  ,`IP`,`Previous Page`,`Previous Page Key`,`Customer Key`,`Customer Name`,`User Handle`, `Date` from `User Request Dimension` URD left join `Page Store Dimension` PSD on (URD.`Page Key`=PSD.`Page Key`) left join `Page Store Dimension` PP on (URD.`Previous Page Key`=PP.`Page Key`)  left join `User Dimension` U on (URD.`User Key`=U.`User Key`) left join `Customer Dimension` C on (C.`Customer Key`=U.`User Parent Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results ");

	//print $sql; exit;

	$result=mysql_query($sql);


	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {
		if ($row['Customer Key'])
			$customer="<a href='customer.php?id=".$row['Customer Key']."'>".$row['Customer Name']."</a>";
		else
			$customer='<span style="color:#777;font-style:italic">'.$row['IP'].'</span>';

		$previous_page=$row['Previous Page'];
		if ($row['previous_page_key']) {
			$previous_page=sprintf('<a href="page.php?id=%d">%s</a>',$row['previous_page_key'],$row['previous_code']);
		}


		if ($row['Page Store Section']=='Not Found') {
			$previous_page='<b>'.$row['URL'].'</b> '.$previous_page;
		}
		$data[]=array(
			'customer'=>$customer,
			'handle'=>$row['User Handle'],
			'date'=>strftime("%a %e %b %y %H:%M:%S", strtotime($row['Date']." +00:00")),
			'ip'=>$row['IP'],
			'previous_page'=>$previous_page

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


function list_queries() {

	global $user;


	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		exit("error no parent key");

	$conf=$_SESSION['state']['site']['queries'];
	$conf_table='site';
	$conf_var='queries';


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



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state'][$conf_table][$conf_var]['order']=$order;
	$_SESSION['state'][$conf_table][$conf_var]['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table][$conf_var]['nr']=$number_results;
	$_SESSION['state'][$conf_table][$conf_var]['sf']=$start_from;
	$_SESSION['state'][$conf_table][$conf_var]['f_field']=$f_field;
	$_SESSION['state'][$conf_table][$conf_var]['f_value']=$f_value;

	$_order=$order;
	$_dir=$order_direction;

	if (count($user->websites)==0) {
		$where='where false ';
	}else {
		$where=sprintf(' where `Site Key`=%d  ',$parent_key);

	}


	$wheref='';
	if ($f_field=='query'  and $f_value!='')
		$wheref.=" and `Query` like '".addslashes($f_value)."%'";


	$sql="select  count(distinct `Query`) as total from `Page Store Search Query Dimension` $where $wheref   ";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select  count(distinct `Query`) as total_without_filters from `Page Store Search Query Dimension`  Q   $where     ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);





	$rtext=number($total_records)." ".ngettext('query','queries',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';







	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	$filter_msg='';

	switch ($f_field) {
	case('query'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any query like")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total (".ngettext('query like','queries like',$total)." <b>$f_value</b>*)";
		break;

	}


	$_order=$order;
	$_dir=$order_direction;

	if ($order=='multiplicity')
		$order='`Multiplicity`';
	elseif ($order=='users')
		$order='users';
	elseif ($order=='no_users')
		$order='no_users';
	elseif ($order=='query')
		$order='`Query`';
	elseif ($order=='date')
		$order='`Date`';
	elseif ($order=='results')
		$order='`Number Results`';

	$sql="select `Query`,avg(`Number Results`) as `Number Results`,max(`Date`) as `Date`,count(*) as `Multiplicity` ,sum(`User Key`=0) no_users,sum(`User Key`>0) users  from   `Page Store Search Query Dimension` Q   $where $wheref  group by `Query` order by $order $order_direction limit $start_from,$number_results ";
	//print $sql;

	$result=mysql_query($sql);


	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {

		$data[]=array(

			'multiplicity'=>number($row['Multiplicity']),
			'users'=>number($row['users']),
			'no_users'=>number($row['no_users']),
			'results'=>number($row['Number Results']),
			'query'=>$row['Query'],

			'date'=>strftime("%a %e %b %y %H:%M:%S %Z", strtotime($row['Date']." +00:00")),

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

function list_query_history() {

	global $user;


	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		exit("error no parent key");

	$conf=$_SESSION['state']['site']['query_history'];
	$conf_table='site';
	$conf_var='query_history';


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



	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state'][$conf_table][$conf_var]['order']=$order;
	$_SESSION['state'][$conf_table][$conf_var]['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table][$conf_var]['nr']=$number_results;
	$_SESSION['state'][$conf_table][$conf_var]['sf']=$start_from;
	$_SESSION['state'][$conf_table][$conf_var]['f_field']=$f_field;
	$_SESSION['state'][$conf_table][$conf_var]['f_value']=$f_value;

	$_order=$order;
	$_dir=$order_direction;

	if (count($user->websites)==0) {
		$where='where false ';
	}else {
		$where=sprintf(' where `Site Key`=%d  ',$parent_key);

	}


	$wheref='';
	if ($f_field=='query'  and $f_value!='')
		$wheref.=" and `Query` like '".addslashes($f_value)."%'";
	elseif ($f_field=='handle' and $f_value!='')
		$wheref.=" and  `User Handle` like '".addslashes($f_value)."%'";
	elseif ($f_field=='customer' and $f_value!='')
		$wheref=sprintf('  and  `User Alias`  REGEXP "[[:<:]]%s" ',addslashes($f_value));



	$sql="select  count(*) as total from `Page Store Search Query Dimension` Q left join  `User Dimension` U  on (U.`User Key`=Q.`User Key`)  $where $wheref  ";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select  count( *) as total_without_filters from `Page Store Search Query Dimension`  Q   $where     ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);





	$rtext=number($total_records)." ".ngettext('query','queries',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';







	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	$filter_msg='';

	switch ($f_field) {
	case('query'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any query like")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total (".ngettext('query like','queries like',$total)." <b>$f_value</b>*)";
		break;
	case('handle'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any handle like")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total (".ngettext('query handle','handles like',$total)." <b>$f_value</b>*)";
		break;
	case('customer'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer like")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total (".ngettext('query customer','customers like',$total)." <b>$f_value</b>*)";
		break;

	}


	$_order=$order;
	$_dir=$order_direction;

	if ($order=='customer')
		$order='`User Alias`';
	elseif ($order=='handle')
		$order='`User Handle`';
	elseif ($order=='query')
		$order='`Query`';
	elseif ($order=='date')
		$order='`Date`';
	elseif ($order=='results')
		$order='`Number Results`';

	$sql="select `User Inactive Note`,`User Active`,Q.`User Key`,`User Parent Key`,`User Alias`,`User Handle`,`Query`,`Number Results`,`Date` from   `Page Store Search Query Dimension` Q left join  `User Dimension` U on  (U.`User Key`=Q.`User Key`)  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
	//print $sql;

	$result=mysql_query($sql);


	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {

		if ($row['User Key']==0) {
			$customer='';
			$handle='';
		}else {

			$customer="<a href='customer.php?id=".$row['User Parent Key']."'>".$row['User Alias']."</a>";

			if ($row['User Active']=='Yes')
				$handle="<a href='site_user.php?id=".$row['User Key']."'>".$row['User Handle']."</a>";
			else
				$handle="<a style='color:#777;font-style:italic' href='site_user.php?id=".$row['User Key']."'>".$row['User Inactive Note']."</a>";
		}

		$data[]=array(
			'customer'=>$customer,
			'handle'=>$handle,
			'results'=>number($row['Number Results']),
			'query'=>$row['Query'],
			'date'=>strftime("%a %e %b %y %H:%M:%S %Z", strtotime($row['Date']." +00:00")),

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


function is_a_site_with($data) {

	if (!isset($data['query'])) {
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


	switch($data['scope']){
	case 'Code':
	$scope_field='Site Code';
	$msg=_('already has this code');
	break;
	case 'URL':
	$scope_field='Site URL';
		$msg=_('already has this URL');

	break;	
	case 'Name':
	$scope_field='Site Name';
	$msg=_('already has this name');

	break;		
	default:
	$response= array(
			'state'=>400,
			'msg'=>'Error'
		);
		echo json_encode($response);
		return;
	}


	$sql=sprintf("select `Site Key`,`Site Name`,`Site Code` from `Site Dimension` where  `%s`=%s  ",
		$scope_field,
		prepare_mysql($query)
	);
	$res=mysql_query($sql);

	if ($data=mysql_fetch_array($res)) {
		$msg=sprintf('Site <a href="site.php?id=%d">%s</a> %s (%s)'
			,$data['Site Key']
			,$data['Site Name']
			,$msg
			,$data['Site Code']
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

?>
