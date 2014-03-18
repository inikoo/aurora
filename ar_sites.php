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

case('get_interval_requests_elements_numbers'):
	$data=prepare_values($_REQUEST,array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));
	get_interval_requests_elements_numbers($data);
	break;

case 'number_email_reminders_in_interval':
	$data=prepare_values($_REQUEST,array(
			'trigger'=>array('type'=>'string'),
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));
	number_email_reminders_in_interval($data);
	break;
case 'number_scopes_email_reminders_in_interval':
	$data=prepare_values($_REQUEST,array(
			'trigger'=>array('type'=>'string'),
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key'),
			'from'=>array('type'=>'string'),
			'to'=>array('type'=>'string')
		));
	number_scopes_email_reminders_in_interval($data);
	break;
case('email_reminder'):
	list_email_reminder();
	break;
case('customers_email_reminder'):
	list_customers_email_reminder();
	break;
case('products_email_reminder'):
	list_products_email_reminder();
	break;
case('pages_state_timeline'):
	list_pages_state_timeline();
	break;
case('pages'):
	list_pages();
	break;
case('deleted_pages'):
	list_deleted_pages();
	break;
case('page_changelog'):
	list_page_changelog();
	break;
case('product_changelog'):
	list_product_changelog();
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


	if (isset( $_REQUEST['elements_type']))
		$elements_type=$_REQUEST['elements_type'];
	else {
		$elements_type=$conf['elements_type'];
	}


	$elements_section=$conf['elements']['section'];

	if (isset( $_REQUEST['elements_System'])) {
		$elements_section['System']=$_REQUEST['elements_System'];
	}
	if (isset( $_REQUEST['elements_Info'])) {
		$elements_section['Info']=$_REQUEST['elements_Info'];
	}
	if (isset( $_REQUEST['elements_Department'])) {
		$elements_section['Department']=$_REQUEST['elements_Department'];
	}
	if (isset( $_REQUEST['elements_Family'])) {
		$elements_section['Family']=$_REQUEST['elements_Family'];
	}
	if (isset( $_REQUEST['elements_Product'])) {
		$elements_section['Product']=$_REQUEST['elements_Product'];
	}
	if (isset( $_REQUEST['elements_FamilyCategory'])) {
		$elements_section['FamilyCategory']=$_REQUEST['elements_FamilyCategory'];
	}
	if (isset( $_REQUEST['elements_ProductCategory'])) {
		$elements_section['ProductCategory']=$_REQUEST['elements_ProductCategory'];
	}
	$elements_state=$conf['elements']['state'];

	if (isset( $_REQUEST['page_state_elements_Online'])) {
		$elements_state['Online']=$_REQUEST['page_state_elements_Online'];
	}
	if (isset( $_REQUEST['page_state_elements_Offline'])) {
		$elements_state['Offline']=$_REQUEST['page_state_elements_Offline'];
	}


	$elements_flags=$conf['elements']['flags'];


	if (isset( $_REQUEST['page_flags_elements_Yellow'])) {
		$elements_flags['Yellow']=$_REQUEST['page_flags_elements_Yellow'];
	}
	if (isset( $_REQUEST['page_flags_elements_Red'])) {
		$elements_flags['Red']=$_REQUEST['page_flags_elements_Red'];
	}
	if (isset( $_REQUEST['page_flags_elements_Purple'])) {
		$elements_flags['Purple']=$_REQUEST['page_flags_elements_Purple'];
	}
	if (isset( $_REQUEST['page_flags_elements_Pink'])) {
		$elements_flags['Pink']=$_REQUEST['page_flags_elements_Pink'];
	}
	if (isset( $_REQUEST['page_flags_elements_Orange'])) {
		$elements_flags['Orange']=$_REQUEST['page_flags_elements_Orange'];
	}
	if (isset( $_REQUEST['page_flags_elements_Green'])) {
		$elements_flags['Green']=$_REQUEST['page_flags_elements_Green'];
	}
	if (isset( $_REQUEST['page_flags_elements_Blue'])) {
		$elements_flags['Blue']=$_REQUEST['page_flags_elements_Blue'];
	}



	$_SESSION['state'][$conf_table]['pages']['order']=$order;
	$_SESSION['state'][$conf_table]['pages']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['pages']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['pages']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['pages']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['pages']['f_value']=$f_value;
	$_SESSION['state'][$conf_table]['pages']['percentages']=$percentages;
	$_SESSION['state'][$conf_table]['pages']['period']=$period;
	$_SESSION['state'][$conf_table]['pages']['elements']['section']=$elements_section;
	$_SESSION['state'][$conf_table]['pages']['elements']['flags']=$elements_flags;
	$_SESSION['state'][$conf_table]['pages']['elements']['state']=$elements_state;
	$_SESSION['state'][$conf_table]['pages']['elements_type']=$elements_type;


	//print_r($_SESSION['state'][$conf_table]['pages']);


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


	switch ($elements_type) {
	case 'sections':


		$_elements='';
		$count_elements=0;
		foreach ($elements_section as $_key=>$_value) {
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


		break;
	case 'state':


		$_elements='';
		$count_elements=0;
		foreach ($elements_state as $_key=>$_value) {
			if ($_value) {
				$_elements.=',"'.$_key.'"';
				$count_elements++;
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ($count_elements<2) {
			$where.=' and `Page State` in ('.$_elements.')' ;
		}
		//print count($count_elements);

		break;
	case 'flags':


		$_elements='';
		$count_elements=0;
		foreach ($elements_flags as $_key=>$_value) {
			if ($_value) {
				$count_elements++;
				if ($_key=='Blue') {
					$_elements.=",'Blue'";
				}
				elseif ($_key=='Green') {
					$_elements.=",'Green'";
				}
				elseif ($_key=='Orange') {
					$_elements.=",'Orange'";
				}
				elseif ($_key=='Pink') {
					$_elements.=",'Pink'";
				}
				elseif ($_key=='Purple') {
					$_elements.=",'Purple'";
				}
				elseif ($_key=='Red') {
					$_elements.=",'Red'";
				}
				elseif ($_key=='Yellow') {
					$_elements.=",'Yellow'";
				}
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		} elseif ($count_elements<7) {
			$where.=' and `Site Flag` in ('.$_elements.')' ;
		}


		break;

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
	case('title'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any page with title")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('pages with title')." <b>$f_value</b>*)";
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
	elseif ($order=='products')
		$order='`Page Store Number Products`';
	elseif ($order=='list_products')
		$order='`Page Store Number List Products`';
	elseif ($order=='button_products')
		$order='`Page Store Number Button Products`';
	elseif ($order=='products_out_of_stock')
		$order='`Page Store Number Out of Stock Products`';
	elseif ($order=='products_sold_out')
		$order='`Page Store Number Sold Out Products`';
	elseif ($order=='percentage_products_out_of_stock')
		$order='percentage_out_of_stock ';

	elseif ($order=='flag')
		$order='`Site Flag`';
	else {
		$order='`Page Code`';
	}
	//print $order;
	//    elseif($order='used_in')
	//        $order='Supplier Product XHTML Sold As';



	$sql="select *,(`Page Store Number Out of Stock Products`/`Page Store Number Products`) as percentage_out_of_stock,`Site Code`,S.`Site Key`,`Page Short Title`,`Page Preview Snapshot Image Key`,`Page Store Section`,`Page Parent Code`,`Page Parent Key`,`Page URL`,P.`Page Key`,`Page Store Title`,`Page Code`  from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$result=mysql_query($sql);
	$data=array();
	//print $sql;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {
		$code="<a href='page.php?id=".$row['Page Key']."'>".$row['Page Code']."</a>";


		$visitors=number($row["Page Store $interval_db Acc Visitors"]);
		$sessions=number($row["Page Store $interval_db Acc Sessions"]);
		$requests=number($row["Page Store $interval_db Acc Requests"]);
		$users=number($row["Page Store $interval_db Acc Users"]);


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


		switch ($row['Site Flag']) {
		case 'Blue': $flag="<img  src='art/icons/flag_blue.png' title='".$row['Site Flag']."' />"; break;
		case 'Green':  $flag="<img  src='art/icons/flag_green.png' title='".$row['Site Flag']."' />";break;
		case 'Orange': $flag="<img src='art/icons/flag_orange.png' title='".$row['Site Flag']."'  />"; break;
		case 'Pink': $flag="<img  src='art/icons/flag_pink.png' title='".$row['Site Flag']."'/>"; break;
		case 'Purple': $flag="<img src='art/icons/flag_purple.png' title='".$row['Site Flag']."'/>"; break;
		case 'Red':  $flag="<img src='art/icons/flag_red.png' title='".$row['Site Flag']."'/>";break;
		case 'Yellow':  $flag="<img src='art/icons/flag_yellow.png' title='".$row['Site Flag']."'/>";break;
		default:
			$flag='';

		}

		switch ($row['Page State']) {
		case 'Online':
			$state='<img src="art/icons/world.png" alt='._('Online').'/>';
			break;
		case 'Offline':
			$state='<img src="art/icons/world_bw.png" alt='._('Offline').'/>';
			break;
		default:
			$state='';
		}


		$products=number($row['Page Store Number Products']);
		$products_out_of_stock=number($row['Page Store Number Out of Stock Products']);
		$products_sold_out=number($row['Page Store Number Sold Out Products']);
		$percentage_products_out_of_stock=percentage($row['Page Store Number Out of Stock Products'],$row['Page Store Number Products']);
		$list_products=number($row['Page Store Number List Products']);
		$button_products=number($row['Page Store Number Button Products']);

		if ($row['Page State']=='Offline') {
			$products='<span style="color:#777;font-style:italic">'.$products.'</span>';
			$products_out_of_stock='<span style="color:#777;font-style:italic">'.$products_out_of_stock.'</span>';
			$products_sold_out='<span style="color:#777;font-style:italic">'.$products_sold_out.'</span>';
			$percentage_products_out_of_stock='<span style="color:#777;font-style:italic">'.$percentage_products_out_of_stock.'</span>';
			$list_products='<span style="color:#777;font-style:italic">'.$list_products.'</span>';
			$button_products='<span style="color:#777;font-style:italic">'.$button_products.'</span>';

		}

		$site="<a href='site.php?id=".$row['Site Key']."'>".$row['Site Code']."</a>";
		$data[]=array(
			'flag'=>$flag,
			'state'=>$state,
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
			'products'=>$products,
			'products_out_of_stock'=>$products_out_of_stock,
			'products_sold_out'=>$products_sold_out,
			'percentage_products_out_of_stock'=>$percentage_products_out_of_stock,
			'list_products'=>$list_products,
			'button_products'=>$button_products,




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


function list_deleted_pages() {

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent='none';

	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		$parent_key='';

	if ($parent=='store') {
		$conf=$_SESSION['state']['store']['deleted_pages'];
		$conf_table='store';
	}
	elseif ($parent=='department') {
		$conf=$_SESSION['state']['department']['deleted_pages'];
		$conf_table='department';
	}
	elseif ($parent=='family') {
		$conf=$_SESSION['state']['family']['deleted_pages'];
		$conf_table='family';
	}
	elseif ($parent=='none') {
		$conf=$_SESSION['state']['sites']['deleted_pages'];
		$conf_table='stores';
	}
	elseif ($parent=='site') {
		$conf=$_SESSION['state']['site']['deleted_pages'];
		$conf_table='site';
	}
	elseif ($parent=='product') {
		$conf=$_SESSION['state']['product']['deleted_pages'];
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






	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');





	$_SESSION['state'][$conf_table]['deleted_pages']['order']=$order;
	$_SESSION['state'][$conf_table]['deleted_pages']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['deleted_pages']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['deleted_pages']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['deleted_pages']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['deleted_pages']['f_value']=$f_value;



	$_order=$order;
	$_dir=$order_direction;



	$where='where true ';

	$table='`Page Store Deleted Dimension` SDD left join `Site Dimension` S on (S.`Site Key`=SDD.`Site Key`) ';

	switch ($parent) {
	case('store'):
		$where.=sprintf(' and SDD.`Store Key`=%d   ',$parent_key);
		break;
	case('site'):
		$where.=sprintf(' and SDD.`Site Key`=%d',$parent_key);
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






	$wheref='';
	if ($f_field=='code'  and $f_value!='')
		$wheref.=" and `Page Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='title' and $f_value!='')
		$wheref.=" and  `Page Title` like '".addslashes($f_value)."%'";



	$sql="select count(*) as total from $table $where $wheref";

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
	case('title'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any page with title")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('pages with title')." <b>$f_value</b>*)";
		break;

	}

	if ($order=='code')
		$order='`Page Code`';
	elseif ($order=='url')
		$order='`Page URL`';
	elseif ($order=='date') {
		$order="`Page Valid To`";
	}




	elseif ($order=='title')
		$order='`Page Title`';
	elseif ($order=='link_title')
		$order='`Page Short Title`';

	else {
		$order='`Page Code`';
	}


	$sql="select `Page Valid To`,`Site Code`,S.`Site Key`,`Page Short Title`,`Page Snapshot Image Key`,`Page Store Section`,`Page Parent Code`,`Page Parent Key`,`Page URL`,`Page Key`,`Page Title`,`Page Code`  from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$result=mysql_query($sql);
	$data=array();
	//print $sql;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {
		$code="<a href='page.php?id=".$row['Page Key']."'>".$row['Page Code']."</a>";




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
			'title'=>$row['Page Title'],
			'link_title'=>$row['Page Title'],
			'type'=>$type,
			'url'=>$row['Page URL'],
			'site'=>$site,
			'image'=>'image.php?size=small&id='.$row['Page Snapshot Image Key'],
			'item_type'=>'item',
			'date'=>strftime("%a %e %b %Y %H:%M:%S %Z", strtotime($row['Page Valid To']." +00:00")),





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

	$sql="select `Site Number Back in Stock Reminder Customers`,`Site Number Back in Stock Reminder Products`,`Site Number Back in Stock Reminder Waiting`,`Site Number Back in Stock Reminder Ready`,`Site Number Back in Stock Reminder Sent`,`Site Number Back in Stock Reminder Cancelled`,`Site Number Products`,`Site Number Out of Stock Products`,`Site Number Pages with Out of Stock Products`,`Site Number Pages with Products`,`Site Number Pages`,`Site Total Acc Requests`,`Site Total Acc Sessions`,`Site Total Acc Visitors`,`Site Total Acc Users`,`Site Code`,`Site Name`,`Site Key`,`Site URL`   from `Site Dimension` $where $wheref order by $order $order_direction limit $start_from,$number_results";

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
			'users'=>number($row['Site Total Acc Users']),
			'visitors'=>number($row['Site Total Acc Visitors']),
			'requests'=>number($row['Site Total Acc Requests']),
			'sessions'=>number($row['Site Total Acc Sessions']),
			'pages'=>number($row['Site Number Pages']),
			'pages_products'=>number($row['Site Number Pages with Products']),
			'pages_out_of_stock'=>number($row['Site Number Pages with Out of Stock Products']),
			'pages_out_of_stock_percentage'=>percentage($row['Site Number Pages with Out of Stock Products'],$row['Site Number Pages with Products']),
			'products'=>number($row['Site Number Products']),
			'out_of_stock'=>number($row['Site Number Out of Stock Products']),
			'out_of_stock_percentage'=>percentage($row['Site Number Out of Stock Products'],$row['Site Number Products']),
			'email_reminders_customers'=>number($row['Site Number Back in Stock Reminder Customers']),
			'email_reminders_products'=>number($row['Site Number Back in Stock Reminder Products']),
			'email_reminders_waiting'=>number($row['Site Number Back in Stock Reminder Waiting']),
			'email_reminders_ready'=>number($row['Site Number Back in Stock Reminder Ready']),
			'email_reminders_sent'=>number($row['Site Number Back in Stock Reminder Sent']),
			'email_reminders_cancelled'=>number($row['Site Number Back in Stock Reminder Cancelled'])


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
		$conf=$_SESSION['state']['store']['requests'];
		$conf_table='store';
	}
	elseif ($parent=='none') {
		$conf=$_SESSION['state']['sites']['requests'];
		$conf_table='department';
	}
	elseif ($parent=='page') {
		$conf=$_SESSION['state']['page']['requests'];
		$conf_table='page';
		$conf_var='requests';
	}
	elseif ($parent=='site') {
		$conf=$_SESSION['state']['site']['requests'];
		$conf_table='site';
		$conf_var='requests';
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

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$_SESSION['state'][$conf_table]['from'];



	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$_SESSION['state'][$conf_table]['to'];

	if (isset( $_REQUEST['inteval_period']))
		$inteval_period=$_REQUEST['inteval_period'];
	else
		$inteval_period=$_SESSION['state'][$conf_table]['period'];


	$elements=$conf['elements'];

	if (isset( $_REQUEST['requests_elements_User'])) {
		$elements['User']=$_REQUEST['requests_elements_User'];
	}
	if (isset( $_REQUEST['requests_elements_NoUser'])) {
		$elements['NoUser']=$_REQUEST['requests_elements_NoUser'];
	}




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
		$_SESSION['state'][$conf_table][$conf_var]['elements']=$elements;

	$_SESSION['state'][$conf_table]['from']=$from;
	$_SESSION['state'][$conf_table]['to']=$to;
	$_SESSION['state'][$conf_table]['period']=$inteval_period;
	
	

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

	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';
	$where_interval=prepare_mysql_dates($from,$to,'`Date`');
	$where.=$where_interval['mysql'];




	$count_elements=0;
	$_key='';
	foreach ($elements as $key=>$_value) {
		if ($_value) {

			if ($key=='User') {
				$_key='Yes';
			}else {
				$_key='No';
			}

			$count_elements++;
		}
	}

	if ($count_elements==0) {
		$where.=' and false' ;
	}elseif ($count_elements<2) {
		
		$where.=sprintf(" and `Is User`=%s ",
		prepare_mysql($_key)
		);
	}


	$wheref='';
	if ($f_field=='name'  and $f_value!='')
		$wheref.=" and `Customer Name` like '".addslashes($f_value)."%'";
	elseif ($f_field=='url' and $f_value!='')
		$wheref.=" and  `URL` like '%".addslashes($f_value)."%'";




	$sql="select  count(*) as total from `User Request Dimension` URD $where   ";

	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select  count(*) as total_without_filters from `User Request Dimension` URD left join `User Dimension` UD on (URD.`User Key` = UD.`User Key`) left join `Customer Dimension` CD on (UD.`User Parent Key` = CD.`Customer Key`) left join `Page Dimension` PD on (URD.`Page Key` = PD.`Page Key`) $where  $wheref ";


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

	if($order=='page'){
		$order='`Page Code`';
	}else{

	$order='`Date`';
}

	$sql=sprintf("select URD.`Page Key` ,PSD.`Page Code`,`URL`,PSD.`Page Store Section`, PP.`Page Code` previous_code ,PP.`Page Key` previous_page_key  ,`IP`,`Previous Page`,`Previous Page Key`,`Customer Key`,`Customer Name`,`User Handle`, `Date` from `User Request Dimension` URD left join `Page Store Dimension` PSD on (URD.`Page Key`=PSD.`Page Key`) left join `Page Store Dimension` PP on (URD.`Previous Page Key`=PP.`Page Key`)  left join `User Dimension` U on (URD.`User Key`=U.`User Key`) left join `Customer Dimension` C on (C.`Customer Key`=U.`User Parent Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results ");



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
			'date'=>strftime("%a %e %b %y %H:%M:%S %Z", strtotime($row['Date']." +00:00")),
			'ip'=>$row['IP'],
			'previous_page'=>$previous_page,
			'page'=>sprintf('<a href="page.php?id=%d">%s</a>',$row['Page Key'],$row['Page Code']),
			'url'=>$row['URL']

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


	switch ($data['scope']) {
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

function list_email_reminder() {


	if (isset( $_REQUEST['scope'])) {
		$scope=$_REQUEST['scope'];
	}else {
		exit("no scope");
	}

	if (isset( $_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];
	}else {
		exit("no parent");
	}


	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	}else {
		exit("no parent key");
	}

	if ($parent=='site') {
		$conf=$_SESSION['state']['site']['email_reminders'];
		$conf_table='site';
	}else {

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

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$elements_back_in_stock=$conf['elements']['back_in_stock'];

	if (isset( $_REQUEST['elements_back_in_stock_email_reminders_Waiting'])) {
		$elements_back_in_stock['Waiting']=$_REQUEST['elements_back_in_stock_email_reminders_Waiting'];
	}
	if (isset( $_REQUEST['elements_back_in_stock_email_reminders_Ready'])) {
		$elements_back_in_stock['Ready']=$_REQUEST['elements_back_in_stock_email_reminders_Ready'];
	}
	if (isset( $_REQUEST['elements_back_in_stock_email_reminders_Sent'])) {
		$elements_back_in_stock['Sent']=$_REQUEST['elements_back_in_stock_email_reminders_Sent'];
	}
	if (isset( $_REQUEST['elements_back_in_stock_email_reminders_Cancelled'])) {
		$elements_back_in_stock['Cancelled']=$_REQUEST['elements_back_in_stock_email_reminders_Cancelled'];
	}




	$_SESSION['state'][$conf_table]['email_reminders']['order']=$order;
	$_SESSION['state'][$conf_table]['email_reminders']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['email_reminders']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['email_reminders']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['email_reminders']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['email_reminders']['f_value']=$f_value;

	$_SESSION['state'][$conf_table]['email_reminders']['elements']['back_in_stock']=$elements_back_in_stock;

	//print_r($_SESSION['state'][$conf_table]['email_reminders']['elements']['back_in_stock']);


	$_order=$order;
	$_dir=$order_direction;



	$where='where true ';

	$table='`Email Site Reminder Dimension`';

	switch ($parent) {

	case('site'):
		$where.=sprintf(' and `Site Key`=%d',$parent_key);
		break;

	default:


	}

	$group='';

	if ($scope=='back_in_stock') {

		$_elements='';
		$count_elements=0;
		foreach ($elements_back_in_stock as $_key=>$_value) {
			if ($_value) {
				$_elements.=',"'.$_key.'"';
				$count_elements++;
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ($count_elements<4) {
			$where.=' and `Email Site Reminder State` in ('.$_elements.')' ;
		}
		//print count($count_elements);

	}



	$wheref='';
	if ($f_field=='subject_name'  and $f_value!='')
		$wheref.=" and `Customer Name` like '".addslashes($f_value)."%'";



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

	$rtext=number($total_records)." ".ngettext('reminder','reminders',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';




	$filter_msg='';

	switch ($f_field) {
	case('subject_name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with name")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('customers with name')." <b>$f_value</b>*)";
		break;


	}



	if ($order=='subject_name')
		$order='`Customer Name`';
	elseif ($order=='date')
		$order='`Creation Date`';
	elseif ($order=='product') {
		$order="`Trigger Scope Name`";
	}elseif ($order=='state') {
		$order="`Email Site Reminder State`";
	}elseif ($order=='finish_date') {
		$order="`Finish Date`";
	}






	$sql="select *  from $table $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$result=mysql_query($sql);
	$data=array();
	// print $sql;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {

		switch ($row['Email Site Reminder State']) {
		case 'Waiting':
			$state=_('Waiting');
			break;
		case 'Ready':
			$state=_('Ready');
			break;
		case 'Sent':
			$state=_('Sent');
			break;
		case 'Cancelled':
			$state=_('Cancelled');
			break;

		default:
			$state=$row['Email Site Reminder State'];
			break;
		}
		$subject_name="<a href='customer.php?id=".$row['Customer Key']."'>".$row['Customer Name']."</a>";
		$product="<a href='product.php?pid=".$row['Trigger Scope Key']."'>".$row['Trigger Scope Name']."</a>";

		$data[]=array(
			'id'=>$row['Email Site Reminder Key'],
			'subject_name'=>$subject_name,
			'product'=>$product,
			'state'=>$state,
			'date'=>strftime("%a %e %b %y %H:%M %Z", strtotime($row['Creation Date']." +00:00")),
			'finish_date'=>($row['Finish Date']?strftime("%a %e %b %y %H:%M %Z", strtotime($row['Finish Date']." +00:00")):''),





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

function list_customers_email_reminder() {


	if (isset( $_REQUEST['scope'])) {
		$scope=$_REQUEST['scope'];
	}else {
		exit("no scope");
	}

	if (isset( $_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];
	}else {
		exit("no parent");
	}


	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	}else {
		exit("no parent key");
	}

	if ($parent=='site') {
		$conf=$_SESSION['state']['site']['email_reminders_customers'];
		$conf_table='site';
	}else {

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

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$elements_back_in_stock=$conf['elements']['back_in_stock'];

	if (isset( $_REQUEST['customers_elements_back_in_stock_email_reminders_Pending'])) {
		$elements_back_in_stock['Pending']=$_REQUEST['customers_elements_back_in_stock_email_reminders_Pending'];
	}
	if (isset( $_REQUEST['customers_elements_back_in_stock_email_reminders_Done'])) {
		$elements_back_in_stock['Done']=$_REQUEST['customers_elements_back_in_stock_email_reminders_Done'];
	}



	$_SESSION['state'][$conf_table]['email_reminders_customers']['order']=$order;
	$_SESSION['state'][$conf_table]['email_reminders_customers']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['email_reminders_customers']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['email_reminders_customers']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['email_reminders_customers']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['email_reminders_customers']['f_value']=$f_value;

	$_SESSION['state'][$conf_table]['email_reminders_customers']['elements']['back_in_stock']=$elements_back_in_stock;

	//print_r($_SESSION['state'][$conf_table]['email_reminders']['elements']['back_in_stock']);


	$_order=$order;
	$_dir=$order_direction;

	$group_by=' group by `Customer Key`';

	$where='where true ';

	$table='`Email Site Reminder Dimension`';

	switch ($parent) {

	case('site'):
		$where.=sprintf(' and `Site Key`=%d',$parent_key);
		break;

	default:


	}



	if ($scope=='back_in_stock') {

		$_elements='';
		$count_elements=0;
		foreach ($elements_back_in_stock as $_key=>$_value) {
			if ($_value) {

				if ($_key=='Done') {
					$_key='No';
				}elseif ($_key=='Pending') {
					$_key='Yes';
				}

				$_elements.=',"'.$_key.'"';
				$count_elements++;
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ($count_elements<2) {

			$where.=' and `Email Site Reminder In Process` in ('.$_elements.')' ;
		}
		//print count($count_elements);

	}



	$wheref='';
	if ($f_field=='name'  and $f_value!='')
		$wheref.=" and `Customer Name` like '".addslashes($f_value)."%'";



	$sql="select count(distinct `Customer Key`) as total from $table $where $wheref ";
	//print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(distinct `Customer Key`) as total from $table $where";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}

	$rtext=number($total_records)." ".ngettext('customer','customers',$total_records);
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
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with name")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('customers with name')." <b>$f_value</b>*)";
		break;


	}



	if ($order=='name')
		$order='`Customer Name`';

	elseif ($order=='products') {
		$order='products';
	}elseif ($order=='first_created') {
		$order="first_created";
	}elseif ($order=='last_finish') {
		$order="last_finish";
	}






	$sql="select max(`Finish Date`) last_finish , min(`Creation Date`) first_created ,`Customer Name`,`Customer Key`,count(Distinct `Trigger Scope Key`) as products from $table $where $wheref $group_by order by $order $order_direction  limit $start_from,$number_results";

	$result=mysql_query($sql);
	$data=array();
	// print $sql;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {


		$name="<a href='customer.php?id=".$row['Customer Key']."'>".$row['Customer Name']."</a>";

		$data[]=array(
			'name'=>$name,
			'products'=>number($row['products']),
			'first_created'=>strftime("%a %e %b %y %H:%M %Z", strtotime($row['first_created']." +00:00")),
			'last_finish'=>($row['last_finish']?
				strftime("%a %e %b %y %H:%M %Z", strtotime($row['last_finish']." +00:00")):''
			),





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

function list_products_email_reminder() {


	if (isset( $_REQUEST['scope'])) {
		$scope=$_REQUEST['scope'];
	}else {
		exit("no scope");
	}

	if (isset( $_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];
	}else {
		exit("no parent");
	}


	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	}else {
		exit("no parent key");
	}

	if ($parent=='site') {
		$conf=$_SESSION['state']['site']['email_reminders_products'];
		$conf_table='site';
	}else {

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

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$elements_back_in_stock=$conf['elements']['back_in_stock'];

	if (isset( $_REQUEST['products_elements_back_in_stock_email_reminders_Pending'])) {
		$elements_back_in_stock['Pending']=$_REQUEST['products_elements_back_in_stock_email_reminders_Pending'];
	}
	if (isset( $_REQUEST['products_elements_back_in_stock_email_reminders_Done'])) {
		$elements_back_in_stock['Done']=$_REQUEST['products_elements_back_in_stock_email_reminders_Done'];
	}



	$_SESSION['state'][$conf_table]['email_reminders_products']['order']=$order;
	$_SESSION['state'][$conf_table]['email_reminders_products']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['email_reminders_products']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['email_reminders_products']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['email_reminders_products']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['email_reminders_products']['f_value']=$f_value;

	$_SESSION['state'][$conf_table]['email_reminders_products']['elements']['back_in_stock']=$elements_back_in_stock;

	//print_r($_SESSION['state'][$conf_table]['email_reminders']['elements']['back_in_stock']);


	$_order=$order;
	$_dir=$order_direction;

	$group_by=' group by `Trigger Scope Key`';

	$where='where true ';

	$table='`Email Site Reminder Dimension` ESRD left join `Product Dimension` on (`Product ID`=`Trigger Scope Key`) ';

	switch ($parent) {

	case('site'):
		$where.=sprintf(' and `Site Key`=%d',$parent_key);
		break;

	default:


	}



	if ($scope=='back_in_stock') {

		$_elements='';
		$count_elements=0;
		foreach ($elements_back_in_stock as $_key=>$_value) {
			if ($_value) {

				if ($_key=='Done') {
					$_key='No';
				}elseif ($_key=='Pending') {
					$_key='Yes';
				}

				$_elements.=',"'.$_key.'"';
				$count_elements++;
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ($count_elements<2) {

			$where.=' and `Email Site Reminder In Process` in ('.$_elements.')' ;
		}
		//print count($count_elements);

	}



	$wheref='';
	if ($f_field=='code'  and $f_value!='')
		$wheref.=" and `Trigger Scope Name` like '".addslashes($f_value)."%'";



	$sql="select count(distinct `Trigger Scope Key`) as total from $table $where $wheref ";

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(distinct `Trigger Scope Key`) as total from $table $where";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}

	$rtext=number($total_records)." ".ngettext('product','products',$total_records);
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
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('products with code')." <b>$f_value</b>*)";
		break;


	}



	if ($order=='code')
		$order='`Trigger Scope Name`';

	elseif ($order=='customers') {
		$order='customers';
	}elseif ($order=='first_created') {
		$order="first_created";
	}elseif ($order=='last_finish') {
		$order="last_finish";
	}elseif ($order=='formated_web_configuration') {
		$order="`Product Web State`,`Product Web Configuration`";
	}elseif ($order=='expected') {
		$order="`Product Next Supplier Shipment`";
	}






	$sql="select `Product Next Supplier Shipment`,`Product Web State`,`Product Web Configuration`,max(`Finish Date`) last_finish , min(`Creation Date`) first_created ,`Trigger Scope Name`,`Trigger Scope Key`,count(DISTINCT `Customer Key`) as customers from $table $where $wheref $group_by order by $order $order_direction  limit $start_from,$number_results";

	$result=mysql_query($sql);
	$data=array();
	// print $sql;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {


		$web_configuration='';
		switch ($row['Product Web State']) {

		case('For Sale'):
			if ($row['Product Web Configuration']=='Online Force For Sale')
				$web_configuration='('._('forced').')';

			$formated_web_configuration='<span >'._('Online')." $web_configuration</span>";
			break;
		case('Offline'):
			if ($row['Product Web Configuration']=='Offline')
				$web_configuration='('._('forced').')';
			if ($row['Product Web Configuration']=='Online Auto')
				$web_configuration='('._('auto').')';

			$formated_web_configuration='<span >'._('Offline')." $web_configuration</span>";
			break;
		case('Out of Stock'):
			if ($row['Product Web Configuration']=='Online Force Out of Stock')
				$web_configuration='('._('forced').')';
			$formated_web_configuration='<span >'._('Out of Stock')." $web_configuration</span>";
			break;
		case('Discontinued'):
			$formated_web_configuration='<span >'._('Discontinued')." $web_configuration</span>";
			break;
		default:
			$formated_web_configuration=$row['Product Web State'];

		}


		$code="<a href='product.php?pid=".$row['Trigger Scope Key']."'>".$row['Trigger Scope Name']."</a>";

		$data[]=array(
			'code'=>$code,
			'formated_web_configuration'=>$formated_web_configuration,
			'customers'=>number($row['customers']),
			'first_created'=>strftime("%a %e %b %y %H:%M %Z", strtotime($row['first_created']." +00:00")),
			'last_finish'=>($row['last_finish']?strftime("%a %e %b %y %H:%M %Z", strtotime($row['last_finish']." +00:00")):''),
			'expected'=>($row['Product Next Supplier Shipment']?strftime("%a %e %b %y", strtotime($row['Product Next Supplier Shipment']." +00:00")):'<span style="color:#777;font-stype:italic">?</span>')	
				
				
				
		

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

function number_email_reminders_in_interval($data) {

	$otrigger=$data['trigger'];
	$parent=$data['parent'];
	$parent_key=$data['parent_key'];
	$from=$data['from'];
	$to=$data['to'];

	$elements_number=array('Waiting'=>0,'Ready'=>0,'Sent'=>0,'Cancelled'=>0);

	$trigger_traslation=array('back_in_stock'=>'Back in Stock');
	if (!array_key_exists($otrigger,$trigger_traslation)) {
		echo json_encode(array('state'=>200,'trigger'=>$otrigger,'elements_numbers'=>$elements_number));

		return;

	}
	$trigger=$trigger_traslation[$otrigger];

	$where=sprintf(' where `Trigger Scope`=%s ',
		prepare_mysql($trigger)
	);

	switch ($parent) {
	case 'site':
		$where.=sprintf(" and `Site Key`=%d",$parent_key);

		break;

	default:
		exit();
	}

	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';
	$where_interval=prepare_mysql_dates($from,$to,'`Start Date`');
	$where_interval=$where_interval['mysql'];



	$sql=sprintf("select count(*)  as num  ,`Email Site Reminder State`   from  `Email Site Reminder Dimension`   %s  group by `Email Site Reminder State`   ",
		$where
	);

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		if ($row['Email Site Reminder State']!='')
			$elements_number[$row['Email Site Reminder State']]=number($row['num']);
	}
	echo json_encode(array('state'=>200,'trigger'=>$otrigger,'elements_numbers'=>$elements_number));
}

function number_scopes_email_reminders_in_interval($data) {

	$otrigger=$data['trigger'];
	$parent=$data['parent'];
	$parent_key=$data['parent_key'];
	$from=$data['from'];
	$to=$data['to'];

	$customers_elements_numbers=array('Done'=>0,'Pending'=>0);
	$products_elements_number=array('Done'=>0,'Pending'=>0);

	$trigger_traslation=array('back_in_stock'=>'Back in Stock');
	if (!array_key_exists($otrigger,$trigger_traslation)) {
		echo json_encode(array('state'=>200,'trigger'=>$otrigger,'customers_elements_numbers'=>$customers_elements_numbers,'products_elements_numbers'=>$products_elements_numbers));
		return;

	}
	$trigger=$trigger_traslation[$otrigger];

	$where=sprintf(' where `Trigger Scope`=%s ',
		prepare_mysql($trigger)
	);

	switch ($parent) {
	case 'site':
		$where.=sprintf(" and `Site Key`=%d",$parent_key);

		break;

	default:
		exit();
	}

	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';
	$where_interval=prepare_mysql_dates($from,$to,'`Start Date`');
	$where_interval=$where_interval['mysql'];



	$sql=sprintf("select count(distinct `Customer Key`)  as customers  , count(distinct `Trigger Scope Key`)  as products   from  `Email Site Reminder Dimension`   %s  and `Email Site Reminder In Process`='Yes'   ",
		$where
	);

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$customers_elements_numbers['Pending']=$row['customers'];
		$products_elements_numbers['Pending']=$row['products'];

	}
	$sql=sprintf("select count(distinct `Customer Key`)  as customers  , count(distinct `Trigger Scope Key`)  as products   from  `Email Site Reminder Dimension`   %s  and `Email Site Reminder In Process`='No'   ",
		$where
	);

	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$customers_elements_numbers['Done']=$row['customers'];
		$products_elements_numbers['Done']=$row['products'];

	}

	echo json_encode(array('state'=>200,'trigger'=>$otrigger,'customers_elements_numbers'=>$customers_elements_numbers,'products_elements_numbers'=>$products_elements_numbers));
}


function list_pages_state_timeline() {

	global $user;
	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent='none';

	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		$parent_key='';

	$conf_var='page_changelog';

	if ($parent=='store') {
		$conf=$_SESSION['state']['store']['page_changelog'];
		$conf_table='store';
	}
	elseif ($parent=='none') {
		$conf=$_SESSION['state']['sites']['page_changelog'];
		$conf_table='department';
	}
	elseif ($parent=='page') {
		$conf=$_SESSION['state']['page']['page_changelog'];
		$conf_table='page';

	}
	elseif ($parent=='site') {
		$conf=$_SESSION['state']['site']['page_changelog'];
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
		$where='where true ';
	}

	switch ($parent) {
	case('store'):
		$where.=sprintf(' and PST.`Page Key`=%d',$parent_key);
		break;

	case('page'):
		$where.=sprintf(' and PST.`Page Key`=%d',$parent_key);
		break;
	case('site'):
		$where.=sprintf(' and PST.`Site Key`=%d',$parent_key);
		break;
	default:
		$where.=sprintf(' and PST.`Site Key` in (%s)',join(',',$user->websites));


		break;

	}



	$wheref='';
	if ($f_field=='code'  and $f_value!='')
		$wheref.=" and `Page Code` like '".addslashes($f_value)."%'";
	elseif ($f_field=='title_label' and $f_value!='')
		$wheref.=" and  `Page Short Title` like '%".addslashes($f_value)."%'";



	$sql="select  PST.`Page State Key` from `Page State Timeline` PST  $where   ";



	$result=mysql_query($sql);
	$total=mysql_num_rows($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select  PST.`Page State Key` from `Page State Timeline` PST left join `Page Dimension` PD on (PST.`Page Key` = PD.`Page Key`) left join `Page Store Dimension` PSD on (PST.`Page Key` = PSD.`Page Key`) $where $wheref  ";
		$result=mysql_query($sql);
		$total_records=mysql_num_rows($result);
		$filtered=$row['total']-$total;


	}


	$rtext=number($total_records)." ".ngettext('change','changes',$total_records);





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
	case('title_label'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any page with label")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('pages with label')." <b>$f_value</b>*)";
		break;

	}



	$_order=$order;
	$_dir=$order_direction;


	if ($order=='code') {
		$order='`Page Code`';
	}if ($order=='title_label') {
		$order='`Page Short Title`';
	}if ($order=='operation') {
		$order='`Operation`';
	}if ($order=='state') {
		$order='`State`';
	}else {



		$order='`Date`';
	}

	$sql=sprintf("select  * from `Page State Timeline` PST left join `Page Dimension` PD on (PST.`Page Key` = PD.`Page Key`) left join `Page Store Dimension` PSD on (PST.`Page Key` = PSD.`Page Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results ");

	//print $sql; exit;

	$result=mysql_query($sql);


	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {

		switch ($row['State']) {
		case 'Online':
			$state=_('Online');
			break;
		case 'Offline':
			$state=_('Offline');
			break;
		default:
			$state=$row['State'];
		}



		switch ($row['Operation']) {
		case 'Created':
			$operation=_('Created');
			break;
		case 'Changed':
			$operation=_('Changed');
		case 'Deleted':
			$operation=_('Deleted');
			break;
		default:
			$operation=$row['Operation'];
		}

		$data[]=array(
			'code'=>sprintf("<a href='page.php?id=%d'>%s</a>",$row['Page Key'],$row['Page Code']),
			'title_label'=>$row['Page Short Title'],
			'date'=>strftime("%a %e %b %y %H:%M:%S %Z", strtotime($row['Date']." +00:00")),
			'state'=>$state,
			'operation'=>$operation


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

function get_interval_requests_elements_numbers($data) {

	$parent=$data['parent'];
	$parent_key=$data['parent_key'];
	$from=$data['from'];
	$to=$data['to'];

	switch ($parent) {
	case('store'):
		$where=sprintf('  URD.`Store Key`=%d',$parent_key);
		break;

	case('page'):
		$where=sprintf('  URD.`Page Key`=%d',$parent_key);
		break;
	case('site'):
		$where=sprintf('  URD.`Site Key`=%d',$parent_key);
		break;
	default:
		$where=sprintf('   URD.`Site Key` in (%s)',join(',',$user->websites));


		break;

	}


	$elements_number=array('User'=>0,'NoUser'=>0);


	if ($from)$from=$from.' 00:00:00';
	if ($to)$to=$to.' 23:59:59';
	$where_interval=prepare_mysql_dates($from,$to,'`Date`');
	$where_interval=$where_interval['mysql'];


	$sql=sprintf("select count(*)  as num  ,`Is User`   from  `User Request Dimension` URD    where %s %s  group by `Is User`   ",
		$where,$where_interval);

	$res=mysql_query($sql);
	
	//print $sql;
	while ($row=mysql_fetch_assoc($res)) {

		if ($row['Is User']=='Yes')
			$_key='User';
		else
			$_key='NoUser';

		$elements_number[$_key]=number($row['num']);
	}




	echo json_encode(array('state'=>200,'elements_numbers'=>$elements_number));

}

?>
