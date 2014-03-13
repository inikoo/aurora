<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

require_once 'common.php';
require_once 'class.Site.php';
require_once 'class.Image.php';
require_once 'class.PageHeader.php';
require_once 'class.PageFooter.php';
include_once 'class.SendEmail.php';
require_once 'ar_edit_common.php';

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'msg'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}



$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('edit_page_flags'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),
			'okey'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'newvalue'=>array('type'=>'string')
		));
	edit_page_flags($data);
	break;
case 'update_see_also_quantity':
$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),
						'operation'=>array('type'=>'string')

		));
	update_see_also_quantity($data);
break;
case ('update_sitemap'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key')
		));
	update_sitemap($data);
	break;
case('add_redirect'):
	$data=prepare_values($_REQUEST,array(
			'url'=>array('type'=>'string'),
			'page_key'=>array('type'=>'key'),
		));

	add_redirect($data);
	break;
case('delete_redirect'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),
			'site_key'=>array('type'=>'key'),
			'page_key'=>array('type'=>'key')
		));

	delete_redirect($data);
	break;
case('edit_email_credentials_inikoo_mail'):
case('edit_email_credentials_other'):
case('edit_email_credentials_direct_mail'):
case('edit_email_credentials_MadMimi'):
case('edit_email_credentials'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')
		));

	edit_email_credentials($data,CKEY);
	break;
case('change_template'):
	$data=prepare_values($_REQUEST,array(
			'page_key'=>array('type'=>'key'),
			'template'=>array('type'=>'string'),
			'display_type'=>array('type'=>'string'),

		));

	change_template($data);
	break;
case('add_template'):
	$data=prepare_values($_REQUEST,array(
			'page_key'=>array('type'=>'key'),
			'template'=>array('type'=>'string'),
			'display_type'=>array('type'=>'string'),

		));

	add_template($data);
	break;
case('add_row'):
	$data=prepare_values($_REQUEST,array(
			'page_key'=>array('type'=>'key'),
			'template_id'=>array('type'=>'key')
		));

	add_row($data);
	break;
case('split_row'):
	$data=prepare_values($_REQUEST,array(
			'page_key'=>array('type'=>'key'),
			'template_id'=>array('type'=>'key')
		));

	split_row($data);
	break;
case('test_email_credentials'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')
		));
	test_email_credentials($data);
	break;
case('delete_email_credentials'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key')
		));
	delete_email_credentials($data);
	break;
case('delete_page'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key')
		));
	delete_page($data);
	break;
case('update_page_height'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),
			'footer'=>array('type'=>'numeric'),
			'header'=>array('type'=>'numeric'),
			'content'=>array('type'=>'numeric'),

		));
	update_page_height($data);
	break;
	break;
case('update_preview_snapshot'):
	$data=prepare_values($_REQUEST,array(
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'key')
		));
	update_preview_snapshot($data);
	break;


case('edit_page_product_list'):
	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'id'=>array('type'=>'key'),
		));

	edit_page_product_list($data);
	break;



case('page_product_lists'):
	list_page_product_lists_for_edition();
	break;
case('page_product_buttons'):
	list_page_product_buttons_for_edition();
	break;
case('set_default_header'):
	$data=prepare_values($_REQUEST,array(
			'header_key'=>array('type'=>'key'),
			'site_key'=>array('type'=>'key'),
		));
	set_default_header($data);
	break;
case('set_header'):
	$data=prepare_values($_REQUEST,array(
			'header_key'=>array('type'=>'key'),
			'page_key'=>array('type'=>'key'),
		));

	set_header($data);
	break;

case('set_default_footer'):
	$data=prepare_values($_REQUEST,array(
			'footer_key'=>array('type'=>'key'),
			'site_key'=>array('type'=>'key'),
		));
	set_default_footer($data);
	break;
case('set_footer'):
	$data=prepare_values($_REQUEST,array(
			'footer_key'=>array('type'=>'key'),
			'page_key'=>array('type'=>'key'),
		));

	set_footer($data);
	break;


case('delete_page_header'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),

		));
	delete_page_header($data);
	break;
case('delete_page_footer'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),

		));
	delete_page_footer($data);
	break;
case('page_headers'):
	list_headers_for_edition();
	break;
case('page_footers'):
	list_footers_for_edition();
	break;
case('add_see_also_page'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),
			'see_also_key'=>array('type'=>'key')

		));

	add_see_also_page($data);


	break;
case('edit_site_menu'):
case('edit_site_search'):
case('edit_email_forgot'):
case('edit_email_welcome'):
case('edit_welcome_message'):
case('edit_site_ftp'):
case('edit_site_includes'):
case('edit_site_client_area'):
case('edit_site_properties'):
case('edit_site_checkout'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')

		));

	edit_site($data);
	break;
case('edit_checkout_method'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key'),
			'store_key'=>array('type'=>'key'),
			'site_checkout_method'=>array('type'=>'string'),

		));

	edit_checkout_method($data);


	break;

case('edit_registration_method'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key'),
			'store_key'=>array('type'=>'key'),
			'site_registration_method'=>array('type'=>'string'),

		));

	edit_registration_method($data);


	break;
case('edit_locale_method'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key'),
			'store_key'=>array('type'=>'key'),
			'site_locale'=>array('type'=>'string'),

		));

	edit_locale($data);


	break;

case('edit_ftp_method'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key'),
			'store_key'=>array('type'=>'key'),
			'site_ftp'=>array('type'=>'string'),

		));

	edit_ftp_method($data);


	break;

case('edit_ftp_passive'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key'),
			'store_key'=>array('type'=>'key'),
			'site_passive'=>array('type'=>'string'),

		));

	edit_ftp_passive($data);


	break;
case('edit_show_badges'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key'),
			'store_key'=>array('type'=>'key'),
			'site_badges'=>array('type'=>'string'),

		));

	edit_show_badges($data);


	break;
case('edit_show_facebook'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key'),
			'store_key'=>array('type'=>'key'),
			'site_facebook'=>array('type'=>'string'),

		));

	edit_show_facebook($data);


	break;
case('edit_show_twitter'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key'),
			'store_key'=>array('type'=>'key'),
			'site_twitter'=>array('type'=>'string'),

		));

	edit_show_twitter($data);


	break;
case('delete_see_also_page'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),
			'see_also_key'=>array('type'=>'key')

		));

	delete_see_also_page($data);


	break;
case('add_found_in_page'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),
			'found_in_key'=>array('type'=>'key')

		));

	add_found_in_page($data);


	break;
case('delete_found_in_page'):
	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),
			'found_in_key'=>array('type'=>'key')

		));

	delete_found_in_page($data);


	break;
case('delete_page_store'):

	$data=prepare_values($_REQUEST,array(
			'id'=>array('type'=>'key'),

		));

	delete_page_store($data);
	break;
case('new_page'):
	$data=prepare_values($_REQUEST,array(
			'site_key'=>array('type'=>'key'),
			'parent'=>array('type'=>'string'),
			'parent_key'=>array('type'=>'number')
		));

	new_page($data);
	break;

case('edit_page_layout'):
	edit_page_layout();
	break;
case('edit_page_html_head'):
case('edit_page_header'):
case('edit_page_footer'):
case('edit_page_state'):

case('edit_page_content'):
case('edit_page_properties'):
case('edit_page_flag'):

	require_once 'class.Family.php';


	//print_r($_REQUEST);

	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'okey'=>array('type'=>'string'),
			'id'=>array('type'=>'key'),
			'table_record_index'=>array('type'=>'numeric','optional'=>true)
		));

	edit_page($data);
	break;

case('edit_family_page_parent_key'):
case('edit_family_page_html_head'):
case('edit_family_page_header'):
case('edit_family_page_content'):
case('edit_family_page_properties'):
	require_once 'class.Family.php';


	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'id'=>array('type'=>'key')
		));

	edit_page($data);
	break;

case('edit_store_page_html_head'):
case('edit_store_page_header'):
case('edit_store_page_content'):
case('edit_store_page_properties'):
	require_once 'class.Store.php';


	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'id'=>array('type'=>'key')
		));

	edit_page($data);
	break;

case('edit_department_page_html_head'):
case('edit_department_page_header'):
case('edit_department_page_content'):
case('edit_department_page_properties'):
	require_once 'class.Department.php';


	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'id'=>array('type'=>'key')
		));

	edit_page($data);
	break;
	break;
case('family_page_list'):
case('department_page_list'):
case('store_pages'):
case('pages'):
	list_pages_for_edition();
	break;
case('sites'):
	list_sites_for_edition();
	break;
case('create_site'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array'),
			'parent_key'=>array('type'=>'key')
		));
	create_site($data);
	break;	
default:

	$response=array('state'=>404,'msg'=>'Operation not found');
	echo json_encode($response);

}

function update_see_also_quantity($data) {

	include_once 'class.Family.php';
	global $editor;
	$page=new Page($data['id']);
	$page->editor=$editor;

	if ($page->data['Number See Also Links']==0 and $data['operation']=='remove') {
		$response= array('state'=>401,'msg'=>$page->msg);
		echo json_encode($response);
		return;
	}


	if ($data['operation']=='remove') {
		$quantity=$page->data['Number See Also Links']-1;
	} else {
		$quantity=$page->data['Number See Also Links']+1;
	}
	$page->update_field_switcher('Number See Also Links',$quantity);

	if ($page->updated) {
		$page->update_see_also();
		//$page->update_preview_snapshot();
		$response= array('state'=>200,'newvalue'=>$page->new_value,'page_key'=>$page->id);
	} else {
		$response= array('state'=>400,'msg'=>$page->msg,'page_key'=>$page->id);
	}
	echo json_encode($response);


}

function edit_page($data) {

	global $editor;
	$page=new Page($data['id']);
	$page->editor=$editor;

	if (!array_key_exists('okey',$data)) {
		$data['okey']=$data['key'];
	}

	$value=stripslashes(urldecode($data['newvalue']));

	if ($data['key']=='Page Store Source') {
		$value=preg_replace("/\{(.*)\}/e",'"{".html_entity_decode(\'$1\')."}"', $value);
		$page->update_field_switcher($data['key'],$value,'no_history');
	} else {

		//print $data['key'];
		$page->update_field_switcher($data['key'],$value);
	}


	if ($page->updated) {

		$response= array('state'=>200,'key'=>$data['okey'],'newvalue'=>$page->new_value,'page_key'=>$page->id);
		
		if ($data['okey']=='page_state') {
			$response['formated_state']=$page->get_formated_state();
		}else if ($data['okey']=='Site Flag Key') {

			$sql=sprintf("select * from  `Site Flag Dimension` where `Site Flag Key`=%d",$page->new_value);

			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {



				switch ($row['Site Flag Color']) {
				case 'Blue': $flag="<img  src='art/icons/flag_blue.png' title='".$row['Site Flag Color']."' />"; break;
				case 'Green':  $flag="<img  src='art/icons/flag_green.png' title='".$row['Site Flag Color']."' />";break;
				case 'Orange': $flag="<img src='art/icons/flag_orange.png' title='".$row['Site Flag Color']."'  />"; break;
				case 'Pink': $flag="<img  src='art/icons/flag_pink.png' title='".$row['Site Flag Color']."'/>"; break;
				case 'Purple': $flag="<img src='art/icons/flag_purple.png' title='".$row['Site Flag Color']."'/>"; break;
				case 'Red':  $flag="<img src='art/icons/flag_red.png' title='".$row['Site Flag Color']."'/>";break;
				case 'Yellow':  $flag="<img src='art/icons/flag_yellow.png' title='".$row['Site Flag Color']."'/>";break;
				default:
					$flag='';

				}

				$response['flag_label']=$row['Site Flag Label'];
				$response['flag_icon']="flag_".strtolower($row['Site Flag Color']).".png";
				$response['flag']=$flag;
				$response['flag_value']=$row['Site Flag Color'];

			}
		}
		
		if(array_key_exists('table_record_index', $data)){
		$response['record_index']=(int) $data['table_record_index'];
		}
		
	} else {
		$response= array('state'=>400,'msg'=>$page->msg,'key'=>$data['key'],'page_key'=>$page->id);
	}
	echo json_encode($response);

}


function delete_page($data) {

	global $editor;

	include_once 'class.PageDeleted.php';

	$page=new Page($data['id']);
	$page->editor=$editor;


	$page->delete();



	if ($page->deleted) {

		$response= array('state'=>200,'page_key'=>$page->new_value);
	} else {
		$response= array('state'=>400,'msg'=>$page->msg,'page_key'=>$page->id);
	}
	echo json_encode($response);

}


function edit_page_layout() {
	$page_key=$_REQUEST['page_key'];
	$layout=$_REQUEST['layout'];
	$value=$_REQUEST['newvalue'];

	$page=new Page($page_key);
	$page->update_show_layout($layout,$value);

	if ($page->updated) {
		$response= array('state'=>200,'newvalue'=>$page->new_value,'page_key'=>$page->id);

	} else {
		$response= array('state'=>400,'msg'=>$page->msg,'page_key'=>$page->id);
	}
	echo json_encode($response);


}


function new_page($data) {

	$site=new Site($data['site_key']);
	$page_data=array();

	switch ($data['parent']) {
	case'site':
		$site->add_store_page($page_data);
		break;
	case 'department':
		$site->add_department_page($data['parent_key'],$page_data);
		break;
	case 'family':
		$site->add_family_page($data['parent_key'],$page_data);
		break;
	case 'family_category':
		$site->add_family_category_page($data['parent_key'],$page_data);
		break;
	case 'product':
		$site->add_product_page($data['parent_key'],$page_data);
		break;
	case 'product_category':
		$site->add_product_category_page($data['parent_key'],$page_data);
		break;
	}

if ($site->new_page) {
		$response= array('state'=>200,'action'=>'created','page_key'=>$site->new_page_key);

	} else {
		$response= array('state'=>400,'msg'=>$site->msg);

	}
	echo json_encode($response);

}





function list_pages_for_edition() {

	$parent= $_REQUEST['parent'];
	$parent_key=$_REQUEST['parent_key'];

	$conf=$_SESSION['state'][$parent]['edit_pages'];




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



	$_SESSION['state'][$parent]['edit_pages']['order']=$order;
	$_SESSION['state'][$parent]['edit_pages']['order_dir']=$order_direction;
	$_SESSION['state'][$parent]['edit_pages']['nr']=$number_results;
	$_SESSION['state'][$parent]['edit_pages']['sf']=$start_from;
	$_SESSION['state'][$parent]['edit_pages']['f_field']=$f_field;
	$_SESSION['state'][$parent]['edit_pages']['f_value']=$f_value;


	if ($parent=='site') {
		$where=sprintf(' where `Page Type`="Store" and `Page Site Key`=%d ',$parent_key);

	} else if ($parent=='store')
			$where=sprintf("where  `Page Store Section`  not in ('Department Catalogue','Product Description','Family Catalogue') and `Page Store Key`=%d ",$parent_key);
		else if ($parent=='family')
				$where=sprintf("where `Page Store Section`='Family Catalogue'   and `Page Parent Key`=%d ",$parent_key);
			else if ($parent=='department')
					$where=sprintf("where `Page Store Section`='Department Catalogue'   and `Page Parent Key`=%d ",$parent_key);

				$filter_msg='';
			$wheref='';

		if ($f_field=='code'  and $f_value!='')
			$wheref.=" and `Page Code` like '".addslashes($f_value)."%'";
		elseif ($f_field=='title' and $f_value!='')
			$wheref.=" and  `Page Store Title` like '".addslashes($f_value)."%'";







		$sql="select count(*) as total from `Page Dimension` P left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)  $where $wheref";
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
		$sql="select count(*) as total from `Page Dimension` P left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)   $where ";
		//print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('page','pages',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif($total_records>0)
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

	$_dir=$order_direction;
	$_order=$order;


	if ($order=='title')
		$order='`Page Title`';
	if ($order=='link_title')
		$order='`Page Short Title`';
	if ($order=='store_title')
		$order='`Page Store Title`';
	if ($order=='page_title')
		$order='`Page Title`';

	if ($order=='code')
		$order='`Page Code`';
	else
		$order='`Page Section`';


	$adata=array();
	$sql="select *  from `Page Dimension`  P left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`) left join `Site Dimension` S on (`Site Key`=`Page Site Key`)  $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);

	$total=mysql_num_rows($res);

	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {







		$adata[]=array(
			'id'=>$row['Page Key'],
			'section'=>$row['Page Section'],
			'code'=>$row['Page Code'],
			'site'=>sprintf('<a href="site.php?id=%d">%s</a>',$row['Page Site Key'],$row['Site Code']),
			'store_title'=>$row['Page Store Title'],
			'link_title'=>$row['Page Short Title'],
			'url'=>$row['Page URL'],
			'page_title'=>$row['Page Title'],
			'page_description'=>$row['Page Store Resume'],


			'go'=>sprintf("<a href='edit_page.php?id=%d&referral=%s&referral_key=%s&content_view=overview'><img src='art/icons/page_go.png' alt='go'></a>",$row['Page Key'],$parent,$parent_key),

			'delete'=>"<img src='art/icons/cross.png'  alt='"._('Delete')."'  title='"._('Delete')."' />"

		);
	}
	mysql_free_result($res);



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

function list_sites_for_edition() {



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



	$sql="select `Site Code`,`Site Name`,`Site Key`,`Site URL`   from `Site Dimension` $where $wheref order by $order $order_direction limit $start_from,$number_results";

	$result=mysql_query($sql);
	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {

		


		$data[]=array(
			'id'=>$row['Site Key'],
			'name'=>$row['Site Name'],
			'code'=>$row['Site Code'],
			'url'=>$row['Site URL'],
		'go'=>sprintf("<a href='edit_site.php?id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Site Key']),

			'delete'=>"<img src='art/icons/cross.png'  alt='"._('Delete')."'  title='"._('Delete')."' />"

		
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


function delete_page_store($data) {

	$page=new Page($data['id']);
	$page->delete();

	if ($page->deleted) {
		$response= array('state'=>200,'action'=>'deleted');

	} else {
		$response= array('state'=>400,'msg'=>$page->msg);

	}
	echo json_encode($response);

}

function add_found_in_page($data) {

	$page_key=$data['id'];
	$found_in_key=$data['found_in_key'];
	$page=new Page($page_key);
	$page->add_found_in_link($found_in_key);

	if ($page->updated) {
		$response= array('state'=>200,'action'=>'created','page_key'=>$page_key);
	}else {
		$response= array('state'=>400,'msg'=>$page->msg);
	}

	echo json_encode($response);

}


function delete_found_in_page($data) {

	$page_key=$data['id'];
	$found_in_key=$data['found_in_key'];
	$page=new Page($page_key);
	$page->remove_found_in_link($found_in_key);

	if ($page->updated) {
		$response= array('state'=>200,'action'=>'deleted','page_key'=>$page_key);
	}else {
		$response= array('state'=>400,'msg'=>$page->msg);
	}

	echo json_encode($response);


}

function add_see_also_page($data) {

	$page_key=$data['id'];
	$see_also_key=$data['see_also_key'];
	$sql=sprintf("insert into `Page Store See Also Bridge` values (%d,%d,'Manual',null)  ",
		$page_key,
		$see_also_key);

	mysql_query($sql);
	$response= array('state'=>200,'action'=>'created','page_key'=>$page_key);
	echo json_encode($response);

}


function delete_see_also_page($data) {

	$page_key=$data['id'];
	$see_also_key=$data['see_also_key'];
	$sql=sprintf("delete from  `Page Store See Also Bridge` where `Page Store Key`=%d and `Page Store See Also Key`=%d   ",
		$page_key,
		$see_also_key);
	mysql_query($sql);
	$response= array('state'=>200,'action'=>'deleted','page_key'=>$page_key);
	echo json_encode($response);

}
function delete_redirect($data) {

	$sql=sprintf("select * from `Page Redirection Dimension` where `Page Redirection Key`=%d", $data['id']);
	$result=mysql_query($sql);
	if ($row=mysql_fetch_assoc($result)) {


		$sql=sprintf("delete from  `Page Redirection Dimension` where `Page Redirection Key`=%d",
			$data['id']);
		// print $sql;
		mysql_query($sql);
		$site=new Site($data['site_key']);
		$site->upload_redirections($row['Source Host'],$row['Source Path']);

		$response= array('state'=>200,'action'=>'deleted','page_key'=>$data['page_key']);
		echo json_encode($response);

	}
}
function add_redirect($data) {

	$page=new Page($data['page_key']);

	$url=_trim($data['url']);

	$url=preg_replace('|^https?:\/\/|','',$url);

	$page->add_redirect($url);
	if ($page->error) {
		$response= array('state'=>400,'msg'=>$page->msg);
		echo json_encode($response);

	}else {
		$response= array('state'=>200,'page_key'=>$data['page_key']);
		echo json_encode($response);
	}

}

function edit_checkout_method($data) {
	//print_r($data);
	$site = new Site($data['site_key']);
	if (!$site) {
		$response= array('state'=>400,'msg'=>'Site not found','key'=>$data['site_key']);
		echo json_encode($response);

		exit;
	}
	//print_r($site);

	$method=$data['site_checkout_method'];
	//print $method;
	$response=$site->update(array('Site Checkout Method'=>$method));
	if ($site->updated) {
		$response= array('state'=>200,'action'=>'updated','msg'=>$site->msg, 'new_value'=>$site->new_value);
	} else
		$response= array('state'=>400,'msg'=>$site->msg);

	echo json_encode($response);
}

function edit_registration_method($data) {
	$site = new Site($data['site_key']);
	if (!$site) {
		$response= array('state'=>400,'msg'=>'Site not found','key'=>$data['site_key']);
		echo json_encode($response);

		exit;
	}





	if (!in_array($data['site_registration_method'],array('Simple','Wholesale','None'))) {
		$response= array('state'=>400,'msg'=>'wrong value '.$data['site_registration_method'],'key'=>$data['site_key']);
		echo json_encode($response);

		exit;

	}


	$response=$site->update(array('Site Registration Method'=>$data['site_registration_method']));
	if ($site->updated) {
		$response= array('state'=>200,'action'=>'updated','msg'=>$site->msg, 'new_value'=>strtolower($site->new_value));
	} else
		$response= array('state'=>400,'msg'=>$site->msg);

	echo json_encode($response);
}

function edit_locale($data) {
	$site = new Site($data['site_key']);
	if (!$site) {
		$response= array('state'=>400,'msg'=>'Site not found','key'=>$data['site_key']);
		echo json_encode($response);

		exit;
	}





	if (!in_array($data['site_locale'],array("en_GB","de_DE","fr_FR","es_ES","pl_PL","it_IT"))) {
		$response= array('state'=>400,'msg'=>'wrong value '.$data['site_locale'],'key'=>$data['site_key']);
		echo json_encode($response);

		exit;

	}


	$response=$site->update(array('Site Locale'=>$data['site_locale']));
	if ($site->updated) {
		$response= array('state'=>200,'action'=>'updated','msg'=>$site->msg, 'new_value'=>($site->new_value));
	} else
		$response= array('state'=>400,'msg'=>$site->msg);

	echo json_encode($response);
}


function edit_ftp_passive($data) {
	$site = new Site($data['site_key']);
	if (!$site) {
		$response= array('state'=>400,'msg'=>'Site not found','key'=>$data['site_key']);
		echo json_encode($response);

		exit;
	}





	if (!in_array($data['site_passive'],array("No","Yes"))) {
		$response= array('state'=>400,'msg'=>'wrong value '.$data['site_passive'],'key'=>$data['site_key']);
		echo json_encode($response);

		exit;

	}


	$response=$site->update(array('Site FTP Passive'=>$data['site_passive']));
	if ($site->updated) {
		$response= array('state'=>200,'action'=>'updated','msg'=>$site->msg, 'new_value'=>($site->new_value));
	} else
		$response= array('state'=>400,'msg'=>$site->msg);

	echo json_encode($response);
}


function edit_show_badges($data) {
	$site = new Site($data['site_key']);
	if (!$site) {
		$response= array('state'=>400,'msg'=>'Site not found','key'=>$data['site_key']);
		echo json_encode($response);

		exit;
	}





	if (!in_array($data['site_badges'],array("No","Yes"))) {
		$response= array('state'=>400,'msg'=>'wrong value '.$data['site_badges'],'key'=>$data['site_key']);
		echo json_encode($response);

		exit;

	}


	$response=$site->update(array('Show Site Badges'=>$data['site_badges']));
	if ($site->updated) {
		$response= array('state'=>200,'action'=>'updated','msg'=>$site->msg, 'new_value'=>($site->new_value));
	} else
		$response= array('state'=>400,'msg'=>$site->msg);

	echo json_encode($response);
}

function edit_show_facebook($data) {
	$site = new Site($data['site_key']);
	if (!$site) {
		$response= array('state'=>400,'msg'=>'Site not found','key'=>$data['site_key']);
		echo json_encode($response);

		exit;
	}





	if (!in_array($data['site_facebook'],array("No","Yes"))) {
		$response= array('state'=>400,'msg'=>'wrong value '.$data['site_facebook'],'key'=>$data['site_key']);
		echo json_encode($response);

		exit;

	}


	$response=$site->update(array('Site Show Facebook'=>$data['site_facebook']));
	if ($site->updated) {
		$response= array('state'=>200,'action'=>'updated','msg'=>$site->msg, 'new_value'=>($site->new_value));
	} else
		$response= array('state'=>400,'msg'=>$site->msg);

	echo json_encode($response);
}

function edit_show_twitter($data) {
	$site = new Site($data['site_key']);
	if (!$site) {
		$response= array('state'=>400,'msg'=>'Site not found','key'=>$data['site_key']);
		echo json_encode($response);

		exit;
	}





	if (!in_array($data['site_twitter'],array("No","Yes"))) {
		$response= array('state'=>400,'msg'=>'wrong value '.$data['site_twitter'],'key'=>$data['site_key']);
		echo json_encode($response);

		exit;

	}


	$response=$site->update(array('Site Show Twitter'=>$data['site_twitter']));
	if ($site->updated) {
		$response= array('state'=>200,'action'=>'updated','msg'=>$site->msg, 'new_value'=>($site->new_value));
	} else
		$response= array('state'=>400,'msg'=>$site->msg);

	echo json_encode($response);
}
function edit_ftp_method($data) {
	$site = new Site($data['site_key']);
	if (!$site) {
		$response= array('state'=>400,'msg'=>'Site not found','key'=>$data['site_key']);
		echo json_encode($response);

		exit;
	}





	if (!in_array($data['site_ftp'],array("FTP","SFTP","FTPS"))) {
		$response= array('state'=>400,'msg'=>'wrong value '.$data['site_ftp'],'key'=>$data['site_key']);
		echo json_encode($response);

		exit;

	}


	$response=$site->update(array('Site FTP Protocol'=>$data['site_ftp']));
	if ($site->updated) {
		$response= array('state'=>200,'action'=>'updated','msg'=>$site->msg, 'new_value'=>($site->new_value));
	} else
		$response= array('state'=>400,'msg'=>$site->msg);

	echo json_encode($response);
}

function edit_site($data) {
	$site=new Site($data['site_key']);
	if (!$site->id) {
		$response= array('state'=>400,'msg'=>'Site not found','key'=>$data['key']);
		echo json_encode($response);

		exit;
	}
	$values=array();
	foreach ($data['values'] as $value_key=>$value_data) {
		if ($value_data['value']=='') {
			$values[$value_key]=$value_data;
			unset($data['values'][$value_key]);
		}
	}

	foreach ($data['values'] as $value_key=>$value_data) {

		$values[$value_key]=$value_data;

	}

	//   print_r($values);

	$responses=array();
	foreach ($values as $key=>$values_data) {

		$responses[]=edit_site_field($site->id,$key,$values_data);
	}

	echo json_encode($responses);


}

function update_sitemap($data) {
	$site=new Site($data['site_key']);
	if (!$site->id) {
		$response= array('state'=>400,'msg'=>'Site not found','key'=>$data['key']);
		echo json_encode($response);
		exit;
	}
	$site->update_sitemap();

	//$client= new GearmanClient();
	//$client->addServer('127.0.0.1');
	//$msg=$client->doBackground("update_sitemap", $fork_metadata);

	$response= array('state'=>200,'sitemap_last_update'=>$site->get('Sitemap Last Update'));
	echo json_encode($response);
	exit;


}

function edit_site_field($site_key,$key,$value_data) {

	//print $value_data;
	//print "$site_key,$key,$value_data ***";
	$site=new site($site_key);

	global $editor;
	$site->editor=$editor;

	$key_dic=array(
		'slogan'=>'Site Slogan',
		'name'=>'Site Name',
		'url'=>'Site URL',
		'checkout_method'=>'Site Checkout Method',
		'ftp'=>'Site FTP Credentials',
		'welcome_subject'=>'Site Welcome Email Subject',
		'welcome_body_html'=>'Site Welcome Email HTML Body',
		'welcome_body_plain'=>'Site Welcome Email Plain Body',
		'forgot_subject'=>'Site Forgot Password Email Subject',
		'forgot_body_html'=>'Site Forgot Password Email HTML Body',
		'forgot_body_plain'=>'Site Forgot Password Email Plain Body',
		'welcome_source'=>'Site Welcome Source',
		'newsletter_label'=>'Site Newsletter Custom Label',
		'site_search_method'=>'Site Search Method',
		'email_marketing_label'=>'Site Email Marketing Custom Label',
		'postal_marketing_label'=>'Site Postal Marketing Custom Label',
		'facebook_url'=>'Site Facebook URL',
		'twitter_url'=>'Site Twitter URL',
		'skype_url'=>'Site Skype URL',
		'flickr_url'=>'Site Flickr URL',
		'blog_url'=>'Site Blog URL',
		'digg_url'=>'Site Digg URL',
		'linkedin_url'=>'Site LinkedIn URL',
		'google_url'=>'Site Google URL',
		'youtube_url'=>'Site Youtube URL',
		'rss_url'=>'Site RSS URL',
		'registration_disclaimer'=>'Site Registration Disclaimer'
	);

	if (array_key_exists($key,$key_dic))
		$key=$key_dic[$key];

	$the_new_value=_trim($value_data['value']);
	//print "$key: $the_new_value";

	$site->update(array($key=>$the_new_value));

	if ($site->updated) {
		$response= array('state'=>200,'action'=>'updated','msg'=>$site->msg, 'newvalue'=>strtolower($site->new_value),'key'=>$value_data['okey']);
	} else
		$response= array('state'=>400,'msg'=>$site->msg,'key'=>$value_data['okey']);


	//$response=array();
	return $response;

}


function delete_page_header($data) {
	$page_header=new PageHeader($data['id']);
	$page_header->delete();
	if ($page_header->deleted) {
		$response= array('state'=>200,'action'=>'deleted');

	} else {
		$response= array('state'=>400,'msg'=>$page_header->msg);

	}
	echo json_encode($response);
}

function delete_page_footer($data) {
	$page_footer=new Pagefooter($data['id']);
	$page_footer->delete();
	if ($page_footer->deleted) {
		$response= array('state'=>200,'action'=>'deleted');

	} else {
		$response= array('state'=>400,'msg'=>$page_footer->msg);

	}
	echo json_encode($response);
}




function list_headers_for_edition() {
	if (isset( $_REQUEST['parent']) and in_array($_REQUEST['parent'],array('site','page')) ) {
		$parent=$_REQUEST['parent'];

	} else {
		return;
	}

	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];

	} else {
		return;
	}


	$conf=$_SESSION['state'][$parent]['edit_headers'];




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



	$_SESSION['state'][$parent]['edit_headers']['order']=$order;
	$_SESSION['state'][$parent]['edit_headers']['order_dir']=$order_direction;
	$_SESSION['state'][$parent]['edit_headers']['nr']=$number_results;
	$_SESSION['state'][$parent]['edit_headers']['sf']=$start_from;
	$_SESSION['state'][$parent]['edit_headers']['f_field']=$f_field;
	$_SESSION['state'][$parent]['edit_headers']['f_value']=$f_value;
	//    $_SESSION['state'][$parent]['edit_headers']['parent_key']=$parent_key;
	//    $_SESSION['state'][$parent]['edit_headers']['parent']=$parent;



	switch ($parent) {
	case 'site':
		$table='  `Page Header Dimension` H   ';
		$where=sprintf(' where `Site Key`=%d',$parent_key);
		break;

	case 'page':
		$table='  `Page Header Dimension`   ';

		$page=new Page($parent_key);
		$where=sprintf(' where `Site Key`=%d',$page->data['Page Site Key']);
		break;

	default:

		break;
	}



	$filter_msg='';
	$wheref='';

	if ($f_field=='name'  and $f_value!='')
		$wheref.=" and `Page Header Name` like '".addslashes($f_value)."%'";
	//    elseif ($f_field=='title' and $f_value!='')
	//    $wheref.=" and  `Page Store Title` like '".addslashes($f_value)."%'";



	$sql="select count(*) as total from $table  $where $wheref";
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
		$sql="select count(*) as total from $table  $where  ";
		//print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('header','headers',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
else
$rtext_rpp='';



	$filter_msg='';

	switch ($f_field) {

	case('name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any header with name")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('headers with name')." <b>$f_value</b>*)";
		break;

	}

	$_dir=$order_direction;
	$_order=$order;


	if ($order=='pages')
		$order='`Number Pages`';
	else
		$order='`Page Header Name`';


	$adata=array();
	$sql="select * from $table $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);

	$total=mysql_num_rows($res);


	if ($parent=='site') {

		$site=new Site($parent_key);
		$default_header_key=$site->data['Site Default Header Key'];
		$selected_header_key=0;
	}else {
		$default_header_key=0;
		$selected_header_key=$page->data['Page Header Key'];
	}


	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		if ($default_header_key==$row['Page Header Key']) {
			$is_default=true;
			$default=_('Default');
		} else {
			$default='<div class="buttons small"><button class="positive" onClick="set_default_header('.$row['Page Header Key'].')">'._('Set as default').'</button></div>';
			$is_default=false;


		}

		if ($selected_header_key==$row['Page Header Key']) {
			$selected=_('Current Header');
		}else {

			$selected='<div class="buttons small"><button class="positive" onClick="set_header('.$row['Page Header Key'].')">'._('Use this header').'</button></div>';

		}


		$adata[]=array(
			'id'=>$row['Page Header Key'],
			'name'=>$row['Page Header Name'],
			'pages'=>number($row['Number Pages']),
			'image'=>'<img alt="preview" style="width:300px" src="image.php?id='.$row['Page Header Preview Image Key'].'"/>',
			'default'=>$default,
			'selected'=>$selected,
			'go'=>sprintf("<a href='edit_page_splinter.php?type=header&id=%d&referral=%s&referral_key=%s'><img src='art/icons/page_go.png' alt='go'></a>",$row['Page Header Key'],$parent,$parent_key),

			'delete'=>(($row['Number Pages'] or $is_default)?'':"<img src='art/icons/cross.png'  alt='"._('Delete')."'  title='"._('Delete')."' />")

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


function list_footers_for_edition() {
	if (isset( $_REQUEST['parent']) and in_array($_REQUEST['parent'],array('page','site','department','family','product')) ) {
		$parent=$_REQUEST['parent'];

	} else {
		return;
	}

	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];

	} else {
		return;
	}


	$conf=$_SESSION['state'][$parent]['edit_footers'];




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



	$_SESSION['state'][$parent]['edit_footers']['order']=$order;
	$_SESSION['state'][$parent]['edit_footers']['order_dir']=$order_direction;
	$_SESSION['state'][$parent]['edit_footers']['nr']=$number_results;
	$_SESSION['state'][$parent]['edit_footers']['sf']=$start_from;
	$_SESSION['state'][$parent]['edit_footers']['f_field']=$f_field;
	$_SESSION['state'][$parent]['edit_footers']['f_value']=$f_value;
	//    $_SESSION['state'][$parent]['edit_footers']['parent_key']=$parent_key;
	//    $_SESSION['state'][$parent]['edit_footers']['parent']=$parent;



	switch ($parent) {
	case 'site':
		$table='  `Page Footer Dimension` H   ';
		$where=sprintf(' where `Site Key`=%d',$parent_key);
		break;

	case 'page':
		$table='  `Page Footer Dimension`   ';

		$page=new Page($parent_key);
		$where=sprintf(' where `Site Key`=%d',$page->data['Page Site Key']);
		break;

	default:

		break;
	}





	$filter_msg='';
	$wheref='';

	if ($f_field=='name'  and $f_value!='')
		$wheref.=" and `Page Footer Name` like '".addslashes($f_value)."%'";
	//    elseif ($f_field=='title' and $f_value!='')
	//    $wheref.=" and  `Page Store Title` like '".addslashes($f_value)."%'";



	$sql="select count(*) as total from $table  $where $wheref";
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
		$sql="select count(*) as total from $table  $where  ";
		//print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('footer','footers',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
else
$rtext_rpp='';



	$filter_msg='';

	switch ($f_field) {

	case('name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any footer with name")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('footers with name')." <b>$f_value</b>*)";
		break;

	}

	$_dir=$order_direction;
	$_order=$order;


	if ($order=='pages')
		$order='`Number Pages`';
	else
		$order='`Page Footer Name`';


	$adata=array();
	$sql="select *  from $table $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);

	$total=mysql_num_rows($res);


	if ($parent=='site') {

		$site=new Site($parent_key);
		$default_footer_key=$site->data['Site Default Footer Key'];
		$selected_footer_key=0;
	}else {
		$default_footer_key=0;
		$selected_footer_key=$page->data['Page Footer Key'];
	}


	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		if ($default_footer_key==$row['Page Footer Key']) {
			$is_default=true;
			$default=_('Default');
		} else {
			$default='<div class="buttons small"><button class="positive" onClick="set_default_footer('.$row['Page Footer Key'].')">'._('Set as default').'</button></div>';
			$is_default=false;


		}

		if ($selected_footer_key==$row['Page Footer Key']) {
			$selected=_('Current Footer');
		}else {

			$selected='<div class="buttons small"><button class="positive" onClick="set_footer('.$row['Page Footer Key'].')">'._('Use this footer').'</button></div>';

		}




		$adata[]=array(
			'id'=>$row['Page Footer Key'],
			'name'=>$row['Page Footer Name'],
			'pages'=>number($row['Number Pages']),
			'image'=>'<img alt="preview" style="width:300px;" src="image.php?id='.$row['Page Footer Preview Image Key'].'"/>',
			'default'=>$default,
			'go'=>sprintf("<a href='edit_page_splinter.php?type=footer&id=%d&referral=%s&referral_key=%s'><img src='art/icons/page_go.png' alt='go'></a>",$row['Page Footer Key'],$parent,$parent_key),
			'selected'=>$selected,
			'delete'=>(($row['Number Pages'] or $is_default)?'':"<img src='art/icons/cross.png'  alt='"._('Delete')."'  title='"._('Delete')."' />")

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


function set_default_header($data) {
	$site=new Site($data['site_key']);
	if (!$site->id) {
		$response= array('state'=>400,'msg'=>'Site not found');
		echo json_encode($response);

		exit;
	}
	$header=new PageHeader($data['header_key']);
	if (!$header->id) {
		$response= array('state'=>400,'msg'=>'Header not found');
		echo json_encode($response);

		exit;
	}

	if ($header->data['Site Key']!=$site->id) {
		$response= array('state'=>400,'msg'=>'Hader not in Site');
		echo json_encode($response);

		exit;
	}

	$site->set_default_header($data['header_key']);
	if ($site->updated) {
		$response= array('state'=>200,'action'=>'updated');
	} else
		$response= array('state'=>400,'msg'=>$site->msg);


	//$response=array();
	echo json_encode($response);

}

function set_default_footer($data) {
	$site=new Site($data['site_key']);
	if (!$site->id) {
		$response= array('state'=>400,'msg'=>'Site not found');
		echo json_encode($response);

		exit;
	}
	$footer=new PageFooter($data['footer_key']);
	if (!$footer->id) {
		$response= array('state'=>400,'msg'=>'Footer not found');
		echo json_encode($response);

		exit;
	}

	if ($footer->data['Site Key']!=$site->id) {
		$response= array('state'=>400,'msg'=>'Hader not in Site');
		echo json_encode($response);

		exit;
	}

	$site->set_default_footer($data['footer_key']);
	if ($site->updated) {
		$response= array('state'=>200,'action'=>'updated');
	} else
		$response= array('state'=>400,'msg'=>$site->msg);


	//$response=array();
	echo json_encode($response);


}



function list_page_product_lists_for_edition() {
	if (isset( $_REQUEST['parent']) and in_array($_REQUEST['parent'],array('site','page')) ) {
		$parent=$_REQUEST['parent'];

	} else {
		return;
	}

	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];

	} else {
		return;
	}


	$conf=$_SESSION['state'][$parent]['edit_product_list'];




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



	$_SESSION['state'][$parent]['edit_product_list']['order']=$order;
	$_SESSION['state'][$parent]['edit_product_list']['order_dir']=$order_direction;
	$_SESSION['state'][$parent]['edit_product_list']['nr']=$number_results;
	$_SESSION['state'][$parent]['edit_product_list']['sf']=$start_from;
	$_SESSION['state'][$parent]['edit_product_list']['f_field']=$f_field;
	$_SESSION['state'][$parent]['edit_product_list']['f_value']=$f_value;
	//    $_SESSION['state'][$parent]['edit_headers']['parent_key']=$parent_key;
	//    $_SESSION['state'][$parent]['edit_headers']['parent']=$parent;



	switch ($parent) {
	case 'site':
		$table='  `Page Product List Dimension` L  left join `Page Store Dimension` P on (P.`Page Key`=L.`Page Key`)  ';
		$where=sprintf(' where `Site Key`=%d',$parent_key);
		break;
	case 'page':
		$table='  `Page Product List Dimension` L  left join `Page Store Dimension` P on (P.`Page Key`=L.`Page Key`)  ';
		$where=sprintf(' where L.`Page Key`=%d',$parent_key);
		break;
	default:

		break;
	}



	$filter_msg='';
	$wheref='';

	if ($f_field=='code'  and $f_value!='')
		$wheref.=" and `Page Product List Code` like '".addslashes($f_value)."%'";
	//    elseif ($f_field=='title' and $f_value!='')
	//    $wheref.=" and  `Page Store Title` like '".addslashes($f_value)."%'";



	$sql="select count(*) as total from $table  $where $wheref";
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
		$sql="select count(*) as total from $table  $where  ";
		//print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('record','records',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
else
$rtext_rpp='';



	$filter_msg='';

	switch ($f_field) {

	case('code'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record with code")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('records with code')." <b>$f_value</b>*)";
		break;

	}

	$_dir=$order_direction;
	$_order=$order;


	//if ($order=='pages')
	//    $order='`Number Pages`';
	//else
	$order='`Page Product List Code`';


	$adata=array();
	$sql="select *  from $table $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);

	$total=mysql_num_rows($res);





	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		switch ($row['List Order']) {
		case 'Code':
			$list_order=_('Code');
			break;
		case 'Name':
			$list_order=_('Name');
			break;
		case 'Special Characteristic':
			$list_order=_('Description');
			break;
		case 'Price':
			$list_order=_('Price');
			break;
		case 'RRP':
			$list_order=_('RRP');
			break;
		case 'Sales':
			$list_order=_('Sales');
			break;
		case 'Date':
			$list_order=_('Date');


		default:
			$list_order=$row['List Order'];
			break;
		}

		switch ($row['List Product Description']) {
		case 'Units Name':
			$description=_('<i>units</i> x <i>name</i>');
			break;
		case 'Units Special Characteristic':
			$description=_('<i>units</i> x <i>description</i>');
			break;
		case 'Units Name RRP':
			$description=_('<i>units</i> x <i>name</i> RRP');
			break;
		case 'Units Special Characteristic RRP':
			$description=_('<i>units</i> x <i>description</i> RRP');
			break;

		default:
			$description=$row['List Product Description'];
			break;
		}



		$go=sprintf("<div class='buttons small'><button onClick='window.location=\"edit_family.php?id=".$row['Page Product List Parent Key']."\"'>"._('Edit Items')."</button></div>");

		$adata[]=array(
			'id'=>$row['Page Product List Key'],
			'code'=>$row['Page Product List Code'],
			'products'=>number($row['Page Product List Number Products']),
			'type'=>($row['Page Product List Type']=='CustomList'?_('Custom'):_('Family')).' ('.number($row['Page Product List Number Products']).')',
			'order'=>$row['List Order'],
			'order_formated'=>$list_order,
			'description'=>$row['List Product Description'],
			'range'=>$row['Range'],
			'description_formated'=>$description,
			'max'=>$row['List Max Items'],
			//'go'=>sprintf("<div class='buttons small'><button onClick='show_edit_product_list_dialog(".$row['Page Product List Key'].")'>"._('Edit Items')."</button></div>"),
			'go'=>$go


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

function list_page_product_buttons_for_edition() {
	if (isset( $_REQUEST['parent']) and in_array($_REQUEST['parent'],array('site','page')) ) {
		$parent=$_REQUEST['parent'];

	} else {
		return;
	}

	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];

	} else {
		return;
	}


	$conf=$_SESSION['state'][$parent]['edit_product_button'];




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



	$_SESSION['state'][$parent]['edit_product_button']['order']=$order;
	$_SESSION['state'][$parent]['edit_product_button']['order_dir']=$order_direction;
	$_SESSION['state'][$parent]['edit_product_button']['nr']=$number_results;
	$_SESSION['state'][$parent]['edit_product_button']['sf']=$start_from;
	$_SESSION['state'][$parent]['edit_product_button']['f_field']=$f_field;
	$_SESSION['state'][$parent]['edit_product_button']['f_value']=$f_value;
	//    $_SESSION['state'][$parent]['edit_headers']['parent_key']=$parent_key;
	//    $_SESSION['state'][$parent]['edit_headers']['parent']=$parent;



	switch ($parent) {
	case 'site':
		$table='  `Page Product Button Dimension` B  left join `Page Store Dimension` P on (P.`Page Key`=B.`Page Key`)  left join `Product Dimension` PD on (B.`Product ID`=PD.`Product ID`)  ';
		$where=sprintf(' where `Site Key`=%d',$parent_key);
		break;
	case 'page':
		$table='  `Page Product Button Dimension` B  left join `Page Store Dimension` P on (P.`Page Key`=B.`Page Key`)  left join `Product Dimension` PD on (B.`Product ID`=PD.`Product ID`) ';
		$where=sprintf(' where P.`Page Key`=%d',$parent_key);
		break;
	default:

		break;
	}



	$filter_msg='';
	$wheref='';

	if ($f_field=='code'  and $f_value!='')
		$wheref.=" and `Product Code` like '".addslashes($f_value)."%'";
	//    elseif ($f_field=='title' and $f_value!='')
	//    $wheref.=" and  `Page Store Title` like '".addslashes($f_value)."%'";



	$sql="select count(*) as total from $table  $where $wheref";
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
		$sql="select count(*) as total from $table  $where  ";
		//print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('record','records',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
else
$rtext_rpp='';



	$filter_msg='';

	switch ($f_field) {

	case('code'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record with code")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('records with code')." <b>$f_value</b>*)";
		break;

	}

	$_dir=$order_direction;
	$_order=$order;


	//if ($order=='pages')
	//    $order='`Number Pages`';
	//else
	$order='`Product Code`';


	$adata=array();
	$sql="select *  from $table $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);

	$total=mysql_num_rows($res);





	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		switch ($row['Product Web State']) {
		case('Out of Stock'):
			$web_state='<span class=="out_of_stock">['._('Out of Stock').']</span>';
			break;
		case('For Sale'):
			$web_state='';
			break;
		case('Discontinued'):
			$web_state=_('Discontinued');
		case('Offline'):
			$web_state=_('Offline');
		default:
			$web_state=$row['Product Web State'];


			break;


		}

		$code=sprintf("<a href='edit_product.php?pid=%d'>%s</a>",$row['Product ID'],$row['Product Code']);
		$web_configuration='';
		switch ($row['Product Web State']) {

		case('For Sale'):
			if ($row['Product Web Configuration']=='Online Force For Sale')
				$web_configuration='('._('forced').')';

			$formated_web_configuration='<span class="web_online">'._('Online')." $web_configuration</span>";
			break;
		case('Offline'):
			if ($row['Product Web Configuration']=='Offline')
				$web_configuration='('._('forced').')';
			if ($row['Product Web Configuration']=='Online Auto')
				$web_configuration='('._('auto').')';

			$formated_web_configuration='<span class="web_offline">'._('Offline')." $web_configuration</span>";
			break;
		case('Out of Stock'):
			if ($row['Product Web Configuration']=='Online Force Out of Stock')
				$web_configuration='('._('forced').')';
			$formated_web_configuration='<span class="web_out_of_stock">'._('Out of Stock')." $web_configuration</span>";
			break;
		case('Discontinued'):
			$formated_web_configuration='<span class="web_discontinued">'._('Discontinued')." $web_configuration</span>";
			break;
		default:
			$formated_web_configuration=$row['Product Web State'];

		}





		$adata[]=array(
			'id'=>$row['Page Product Button Key'],
			'smallname'=>$row['Product XHTML Short Description'].' <span class="stock">'._('Stock').': '.number($row['Product Availability']).'</span> <span class="web_state">'.$web_state.'</span>',

			'code'=>$code,
			'pid'=>$row['Product ID'],
			'go'=>sprintf("<div class='buttons small'><button onClick='show_edit_product_button_dialog(".$row['Page Product Button Key'].")'>"._('Edit')."</button></div>"),
			'web_configuration'=>$web_configuration,
			'formated_web_configuration'=>$formated_web_configuration,

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




function edit_page_product_list($data) {

	$page_product_list_key=$data['id'];

	$sql=sprintf("select * from `Page Product List Dimension` where `Page Product List Key`=%d",$page_product_list_key);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {


		$key_translation=array(
			'order'=>'List Order',
			'show_rrp'=>'Show RRP',
			'max'=>'List Max Items',
			'range'=>'Range',
			'code'=>'Page Product List Code',
			'description'=>'List Product Description'
		);


		if (array_key_exists($data['key'],$key_translation)) {


			if ($data['key']=='range') {
				if (!(preg_match('/^[a-z0-9](\-)[a-z0-9]$/',$data['newvalue']) or $data['newvalue']=='')) {
					$response= array('state'=>400,'msg'=>_('Wrong value, range should have the following format: a-b'),'key'=>$data['key']);
					echo json_encode($response);
					return;
				}

			}


			$sql=sprintf("update `Page Product List Dimension` set `%s`=%s where `Page Product List Key`=%d",
				$key_translation[$data['key']],
				prepare_mysql($data['newvalue']),
				$page_product_list_key);

			mysql_query($sql);

			$response= array('state'=>200,'action'=>'updated','msg'=>'', 'newvalue'=>$data['newvalue'],'key'=>$data['key'],'newdata'=>array());
			if ($data['key']=='description') {
				switch ($data['newvalue']) {
				case 'Units Name':
					$description=_('<i>units</i> x <i>name</i>');
					break;
				case 'Units Special Characteristic':
					$description=_('<i>units</i> x <i>description</i>');
					break;
				case 'Units Name RRP':
					$description=_('<i>units</i> x <i>name</i> RRP');
					break;
				case 'Units Special Characteristic RRP':
					$description=_('<i>units</i> x <i>description</i> RRP');
					break;

				default:
					$description=$data['newvalue'];
					break;
				}

				$response['newdata']=array('description_formated'=>$description);
			}

			if ($data['key']=='description') {
				switch ($data['newvalue']) {
				case 'Units Name':
					$description=_('<i>units</i> x <i>name</i>');
					break;
				case 'Units Special Characteristic':
					$description=_('<i>units</i> x <i>description</i>');
					break;
				case 'Units Name RRP':
					$description=_('<i>units</i> x <i>name</i> RRP');
					break;
				case 'Units Special Characteristic RRP':
					$description=_('<i>units</i> x <i>description</i> RRP');
					break;

				default:
					$description=$data['newvalue'];
					break;
				}

				$response['newdata']=array('description_formated'=>$description);
			}


			if ($data['key']=='order') {

				switch ($data['newvalue']) {
				case 'Code':
					$list_order=_('Code');
					break;
				case 'Name':
					$list_order=_('Name');
					break;
				case 'Special Characteristic':
					$list_order=_('Description');
					break;
				case 'Price':
					$list_order=_('Price');
					break;
				case 'RRP':
					$list_order=_('RRP');
					break;
				case 'Sales':
					$list_order=_('Sales');
					break;
				case 'Date':
					$list_order=_('Date');


				default:
					$list_order=$row['List Order'];
					break;
				}

				$response['newdata']=array('order_formated'=>$list_order);
			}






		} else {

			$response= array('state'=>400,'msg'=>'Error 1','key'=>$data['key']);
		}

	} else {
		$response= array('state'=>400,'msg'=>'Error 2','key'=>$data['key']);

	}
	echo json_encode($response);

}

function update_preview_snapshot($data) {
	include_once 'class.Image.php';



	switch ($data['parent']) {
	case 'Page':
		$scope=new Page($data['parent_key']);
		break;
	case 'Header':

		$scope=new PageHeader($data['parent_key']);
		break;
	case 'Footer':
		$scope=new PageFooter($data['parent_key']);
		break;

	}





	if ($scope->id) {
		$scope->update_preview_snapshot();
	}

	$response= array('state'=>200,'image_key'=>$scope->get_preview_snapshot_image_key(),'formated_date'=>$scope->get_preview_snapshot_date());
	echo json_encode($response);
}

function set_header($data) {

	$page=new Page($data['page_key']);
	if ($page->id) {
		$page->update_field_switcher('Page Header Key',$data['header_key']);
		$header=new PageHeader($data['header_key']);
		$header->update_number_pages();
		$response= array('state'=>200);
	}else {
		$response= array('state'=>400,'msg'=>'page not found');
	}
	echo json_encode($response);
}

function set_footer($data) {

	$page=new Page($data['page_key']);
	if ($page->id) {
		$page->update_field_switcher('Page Footer Key',$data['footer_key']);
		$footer=new PageFooter($data['footer_key']);
		$footer->update_number_pages();
		$response= array('state'=>200);
	}else {
		$response= array('state'=>400,'msg'=>'page not found');
	}
	echo json_encode($response);
}




function update_page_height($data) {

	$page=new Page($data['id']);
	if ($page->id) {
		$page->update_field_switcher('Page Footer Height',$data['footer'],'no_history');
		$page->update_field_switcher('Page Header Height',$data['header'],'no_history');
		$page->update_field_switcher('Page Content Height',$data['content'],'no_history');
		//print $page->xxx;
	}
$page->get_data('id',$page->id);

	$response= array('state'=>200,'content'=>$page->data['Page Content Height']);
	echo json_encode($response);
}

function edit_email_credentials($data,$CKEY) {

	//print_r($data['values']);exit;
	$site=new Site($data['site_key']);


	global $editor;
	$site->editor=$editor;

	include_once 'class.EmailCredentials.php';
	$site_email_credentials_key=$site->get_email_credential_key();
	if ($site_email_credentials_key) {
		$email_credentials=new EmailCredentials($site_email_credentials_key);
		$email_credentials->delete();
	}


	$key_dic=array(
		'email_provider'=>'Email Provider',
		'email'=>'Email Address Gmail',
		'password'=>'Password Gmail',
		'email_other'=>'Email Address Other',
		'login'=>'Login Other',
		'password_other'=>'Password Other',
		'incoming_server'=>'Incoming Mail Server',
		'outgoing_server'=>'Outgoing Mail Server',
		'email_direct_mail'=>'Email Address Direct Mail',
		'email_inikoo_mail'=>'Email Address Amazon Mail',
		'api_email_MadMimi'=>'API Email Address MadMimi',
		'api_key_MadMimi'=>'API Key MadMimi',
		'email_MadMimi'=>'Email Address MadMimi'
	);

	$inv_key_dic=array(
		'Email Provider'=>'email_provider',
		'Email Address Gmail'=>'email',
		'Password Gmail'=>'password',
		'Email Address Other'=>'email_other',
		'Login Other'=>'login',
		'Password Other'=>'password_other',
		'Incoming Mail Server'=>'incoming_server',
		'Outgoing Mail Server'=>'outgoing_server',
		'Email Address Direct Mail'=>'email_direct_mail',
		'Email Address Amazon Mail'=>'email_inikoo_mail',
		'API Email Address MadMimi'=>'api_email_MadMimi',
		'API Key MadMimi'=>'api_key_MadMimi',
		'Email Address MadMimi'=>'email_MadMimi'

	);



	//print_r($email_credential_data);//exit;
	$email_credentials=new EmailCredentials();

	$email_credential_data=array();
	foreach ($data['values'] as $key=>$value) {
		if (array_key_exists($key,$key_dic)) {
			if ($key=='password' || $key=='password_other')
				$value['value']=$email_credentials->encrypt_password($value['value']);
			$email_credential_data[$key_dic[$key]]=$value['value'];
		}
	}

	$email_credentials->create($email_credential_data,$CKEY);
	$site->associate_email_credentials($email_credentials->id);



	$responses=array();
	$credentials_data=$site->get_email_credentials();
	//print_r($credentials_data);exit;

	switch ($credentials_data['Email Provider']) {
	case 'PHPMail':
		$responses[]=array('state'=>200, 'newvalue'=>$credentials_data['Email Address Direct Mail'],'key'=>'email_direct_mail','action'=>'', 'msg'=>'');
		break;
	case 'Gmail':
		$responses[]=array('state'=>200, 'newvalue'=>$credentials_data['Email Address Gmail'],'key'=>'email','action'=>'', 'msg'=>'');
		$responses[]=array('state'=>200, 'newvalue'=>$credentials_data['Password Gmail'],'key'=>'password','action'=>'', 'msg'=>'');
		break;
	case 'Other':
		$responses[]=array('state'=>200, 'newvalue'=>$credentials_data['Email Address Other'],'key'=>'email_other','action'=>'', 'msg'=>'');
		$responses[]=array('state'=>200, 'newvalue'=>$credentials_data['Login Other'],'key'=>'login','action'=>'', 'msg'=>'');
		$responses[]=array('state'=>200, 'newvalue'=>$credentials_data['Password Other'],'key'=>'password_other','action'=>'', 'msg'=>'');
		$responses[]=array('state'=>200, 'newvalue'=>$credentials_data['Incoming Mail Server'],'key'=>'incoming_server','action'=>'', 'msg'=>'');
		$responses[]=array('state'=>200, 'newvalue'=>$credentials_data['Outgoing Mail Server'],'key'=>'outgoing_server','action'=>'', 'msg'=>'');
		break;
	case 'Inikoo':
		$responses[]=array('state'=>200, 'newvalue'=>$credentials_data['Email Address Amazon Mail'],'key'=>'email_inikoo_mail','action'=>'', 'msg'=>'');
		break;
	case 'MadMimi':
		$responses[]=array('state'=>200, 'newvalue'=>$credentials_data['API Email Address MadMimi'],'key'=>'api_email_MadMimi','action'=>'', 'msg'=>'');
		$responses[]=array('state'=>200, 'newvalue'=>$credentials_data['API Key MadMimi'],'key'=>'api_key_MadMimi','action'=>'', 'msg'=>'');
		$responses[]=array('state'=>200, 'newvalue'=>$credentials_data['Email Address MadMimi'],'key'=>'email_MadMimi','action'=>'', 'msg'=>'');
		break;
	}

	echo json_encode($responses);
	return;

}



function delete_email_credentials($data) {
	//print_r($data);
	$site=new Site($data['site_key']);

	if (!$site->get_email_credentials()) {
		$response= array('state'=>400, 'msg'=>'No Credentials Exist');
		echo json_encode($response);
		return;
	}
	$credentials=$site->get_email_credentials();
	$sql=sprintf("delete from `Email Credentials Dimension` where `Email Credentials Key`=%d", $credentials['Email Credentials Key']);
	mysql_query($sql);

	$sql=sprintf("delete from `Email Credentials Scope Bridge` where `Email Credentials Key`=%d", $credentials['Email Credentials Key']);
	mysql_query($sql);

	$sql=sprintf("delete from `Email Credentials Site Bridge` where `Email Credentials Key`=%d", $credentials['Email Credentials Key']);
	mysql_query($sql);

	$response= array('state'=>200, 'msg'=>'Deleted');
	echo json_encode($response);
	return;

}

function test_email_credentials($data) {

	if ($data['values']['to']=='') {
		$response=array('state'=>400,'msg'=>_('You must specify the receiver email'));
		echo json_encode($response);
		exit;
	}

	if ($data['values']['email_type']=='') {
		$response=array('state'=>400,'msg'=>_('You must specify the email type'));
		echo json_encode($response);
		exit;
	}

	$site=new Site($data['site_key']);
	$credentials=$site->get_email_credentials();
	$from_name=$site->data['Site Name'];




	$message_data['type']=$data['values']['email_type'];//'HTML';
	$message_data['to']=$data['values']['to'];
	$message_data['subject']=_('Test');
	$message_data['html']=_('Test Message HTML');
	$message_data['html'].='<a href="http://www.inikoo.com"><img src="http://www.inikoo.com/images/inikoo_logo.png"/></a>';
	$message_data['html'].='<a href="http://www.inikoo.com?key=skdjflksdjflkjdflsdkjflksdf"/>http://www.inikoo.com?key=skdjflksdjflkjdflsdkjflksdf</a>';
	$message_data['plain']=_('Test Message Plain');
	$message_data['from_name']=$from_name;

	$message_data['email_placeholders']=array(
		'some_placeholder' => 'some content here' // This will replace "{some_placeholder}" in your promotion with "some content here".
		,'greeting' => 'Hello', 'name' => 'TestName', 'date1'=>'test date 1', 'date2'=>'test date 2'
	);

	$message_data['email_placeholders']=array(
		'greeting' => 'Hello','greetings' => 'greetings', 'live_masterkey_link' => '<a href="xxxxxxx" >'._('Change Password').'</a>', 'masterkey_link'=>'xxxx'
	);



	$message_data['promotion_name']=$_REQUEST['promotion_name'];


	$message_data['email_credentials_key']=$credentials['Email Credentials Key'];
	$message_data['email_matter']='Test Email';
	$message_data['email_matter_parent_key']=0;
	$message_data['email_matter_key']=0;
	$message_data['recipient_type']='Other';
	$message_data['recipient_key']=0;
	$message_data['email_key']=0;
	if (isset($message_data['plain']) && $message_data['plain']) {
		$message_data['plain']=$message_data['plain'];
	}
	else
		$message_data['plain']='';

	$send_email=new SendEmail();
	$send_email->secret_key=CKEY;
	$send_email->track=false;

	$send_email->set($message_data);
	$send_result=$send_email->send();

	//print_r($send_result);
	echo json_encode($send_result);

}

function change_template($data) {
	//print_r($data);
	global $editor;
	$page=new Page($data['page_key']);
	$page->editor=$editor;

	if (!$page->id) {
		$response= array('state'=>400,'msg'=>$page->msg);
		echo json_encode($response);
		return;
	}

	if ($data['display_type'] == 'Template') {
		$page->update_field_switcher('display_type','Template');
		$page->update_field_switcher('filename',$data['template']);

		$history_data=array(
			'History Abstract'=>_('Page content uploaded'),
			'History Details'=>'',

			'Indirect Object'=>'Page Store Source',
			'Indirect Object Key'=>''
		);
		$page->add_history($history_data);

		$page->update_button_products('Parent');
		$page->update_list_products();

	}
	else if ($data['display_type']=='Source') {
			$page->update_field_switcher('display_type','Source');
			$page->update_field_switcher('filename','');
		}

	if ($page->updated) {
		$response= array('state'=>200,'newvalue'=>$page->new_value);
	} else {
		$response= array('state'=>400,'msg'=>$page->msg);
	}
	echo json_encode($response);
}

function add_template($data) {
	//print_r($data);
	global $editor;
	$page=new Page($data['page_key']);
	$page->editor=$editor;

	if (!$page->id) {
		$response= array('state'=>400,'msg'=>$page->msg);
		echo json_encode($response);
		return;
	}


	$sql=sprintf("insert into `Template Dimension` (`Template Name`, `Page Key`) values ('%s', %d)", 'test name', $page->id);
	mysql_query($sql);

	$response= array('state'=>200,'page_key'=>$page->id, 'template_id'=>mysql_insert_id());

	echo json_encode($response);
}


function add_row($data) {
	//print_r($data);
	global $editor;
	$page=new Page($data['page_key']);
	$page->editor=$editor;
	$template_id=$data['template_id'];


	if (!$page->id) {
		$response= array('state'=>400,'msg'=>$page->msg);
		echo json_encode($response);
		return;
	}

	$default_width=900;
	$default_height=100;

	$sql=sprintf("select * from `Template Block Dimension` where `Template ID`=%d order by `Row ID` DESC limit 0,1", $template_id);

	$result=mysql_query($sql);
	//$block_count=mysql_num_rows($result);
	if (mysql_num_rows($result) > 0) {
		$row=mysql_fetch_assoc($result);
		$row_id=$row['Row ID'] + 1;
	}
	else {
		$row_id=1;
	}

	//print $row_id;

	$sql=sprintf("insert into `Template Block Dimension` (`Template ID`, `Page Key`, `Row ID`, `Column ID`, `Height`, `Width`) values (%d, %d, %d, %d, %d, %d)", $template_id, $page->id, $row_id,1, $default_height, $default_width);
	mysql_query($sql);

	$response= array('state'=>200,'page_key'=>$page->id);

	echo json_encode($response);
}

function split_row($data) {
	//print_r($data);
	global $editor;
	$page=new Page($data['page_key']);
	$page->editor=$editor;
	$template_id=$data['template_id'];
	$row_id=$_REQUEST['row'];
	$column_id=$_REQUEST['column'];

	if (!$page->id) {
		$response= array('state'=>400,'msg'=>$page->msg);
		echo json_encode($response);
		return;
	}
	$sql=sprintf("select * from `Template Block Dimension` where `Template ID`=%d and `Row ID`=%d", $template_id, $row_id);

	$result=mysql_query($sql);
	if (mysql_num_rows($result) >= 3) {
		$response= array('state'=>200,'msg'=>'Maximum number of columns reached');
		echo json_encode($response);
		exit;
	}



	$sql=sprintf("select * from `Template Block Dimension` where `Template ID`=%d and `Row ID`=%d order by `Column ID` DESC limit 0,1", $template_id, $row_id);
	$result=mysql_query($sql);
	$row=mysql_fetch_assoc($result);
	$next_column_id=$row['Column ID']+1;
	$block_id=$row['Template Block ID'];

	$height=$_REQUEST['height'];

	$new_width=$row['Width']-$_REQUEST['width'];

	// print $next_column_id; exit;
	$sql=sprintf("insert into `Template Block Dimension` (`Template ID`,`Page Key`,`Row ID`, `Column ID`, `Height`, `Width`) values (%d, %d, %d, %d, %d, %d)", $template_id, $page->id, $row_id, $next_column_id, $height, $_REQUEST['width']);
	$result=mysql_query($sql);


	$sql=sprintf("update `Template Block Dimension` set `Width`=%d where `Template Block ID`=%d", $new_width, $block_id);
	//print $sql;
	$result=mysql_query($sql);

	$response= array('state'=>200,'page_key'=>$page->id);

	echo json_encode($response);
}


function create_site($data) {
	global $editor;

	if (array_key_exists('Site Name',$data['values'])
		and  array_key_exists('Site Code',$data['values'])
		and  array_key_exists('Site URL',$data['values'])
		and  array_key_exists('Site Locale',$data['values'])

	) {
		$store_key=$data['parent_key'];



		$store=new Store($store_key);
		$store->editor=$editor;

 $site=$store->create_site($data['values']);

	
		if (!$site->new) {

			$response=array('state'=>200,'msg'=>$site->msg,'action'=>'found','object_key'=>$site->id);
		} else {

			$response=array('state'=>200,'msg'=>$site->msg,'action'=>'created','object_key'=>$site->id);
		}




	} else
		$response=array('state'=>400,'msg'=>_('Error'));
	echo json_encode($response);
}

function edit_page_flags($data) {
	$site=new site($data['id']);

	if (!$site->id) {
		$response=array('state'=>400,'action'=>'nochange','msg'=>'site not found');
		echo json_encode($response);
		return;
	}

	global $editor;
	$site->editor=$editor;
	if (preg_match('/\d+$/', $data['okey'],$match)) {
		$flag_key=$match[0];
		$field=$data['key'];
		$value=$data['newvalue'];
		$site->update_flag($flag_key,$field,$value);

		if (!$site->error) {
			$response= array('state'=>200,'newvalue'=>$site->new_value,'key'=>$data['okey']);
			echo json_encode($response);
			return;
		}else {
			$response=array('state'=>400,'action'=>'nochange','msg'=>$site->msg);
			echo json_encode($response);
			return;
		}


	}else {
		$response=array('state'=>400,'action'=>'nochange','msg'=>'no flag key');
		echo json_encode($response);
		return;
	}
}


?>
