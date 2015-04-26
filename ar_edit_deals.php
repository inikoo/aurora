<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>

 Copyright (c) 2014, Inikoo
 Created: 13 May 2014 16:49:31 BST Sheffield, UK

 Version 2.0
*/


require_once 'common.php';

require_once 'class.Deal.php';


require_once 'ar_edit_common.php';
if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405, 'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];


switch ($tipo) {
case('remove_voucher_from_order'):
	$data=prepare_values($_REQUEST, array(
			'order_key'=>array('type'=>'key'),
			'voucher_key'=>array('type'=>'string')

		));

	remove_voucher_from_order($data);
	break;
case('add_voucher_to_order'):
	$data=prepare_values($_REQUEST, array(
			'order_key'=>array('type'=>'key'),
			'voucher'=>array('type'=>'string')

		));

	add_voucher_to_order($data);
	break;
case('create_deal'):
	$data=prepare_values($_REQUEST, array(
			'parent_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')
		));
	create_deal($data);
	break;
case('create_allowance'):
	$data=prepare_values($_REQUEST, array(
			'parent_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')
		));
	create_deal_allowance($data);
	break;	
case('create_campaign'):
	$data=prepare_values($_REQUEST, array(
			'parent_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')
		));
	create_campaign($data);
	break;

case('edit_campaign_description'):
	$data=prepare_values($_REQUEST, array(
			'campaign_key'=>array('type'=>'key'),
			'okey'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'newvalue'=>array('type'=>'string')

		));
	update_campaign($data);
	break;

case('edit_campaign_state'):
	$data=prepare_values($_REQUEST, array(
			'campaign_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array'),


		));

	update_campaign_state($data);
	break;

case('update_deal_metadata'):
	$data=prepare_values($_REQUEST, array(
			'terms_label'=>array('type'=>'string'),
			'allowances_label'=>array('type'=>'string'),
			'terms'=>array('type'=>'string'),
			'allowances'=>array('type'=>'string'),
			'deal_metadata_key'=>array('type'=>'key'),
		));

	update_deal_metadata($data);
	break;
case('update_deal'):

	$data=prepare_values($_REQUEST, array(
			'name'=>array('type'=>'string'),
			'description'=>array('type'=>'string'),
			'deal_key'=>array('type'=>'key'),
		));

	update_deal_description($data);
	break;

case('edit_deal_status'):
case('edit_deal_description'):
case('edit_deal_dates'):

	$data=prepare_values($_REQUEST, array(
			'key'=>array('type'=>'string'),
			'okey'=>array('type'=>'string'),
			'newvalue'=>array('type'=>'string'),
			'deal_key'=>array('type'=>'key'),
		));

	update_deal($data);
	break;
case('update_deal_status'):
	$data=prepare_values($_REQUEST, array(
			'value'=>array('type'=>'string'),
			'deal_key'=>array('type'=>'key'),
		));

	update_deal_status($data);
	break;

case('update_deal_metadata_status'):
	$data=prepare_values($_REQUEST, array(
			'value'=>array('type'=>'string'),
			'deal_metadata_key'=>array('type'=>'key'),
		));

	update_deal_metadata_status($data);
	break;


case('edit_campaigns'):
	list_campaigns_for_edition();
	break;
case('edit_deals'):
	list_deals_for_edition();
	break;
case('deals'):
	list_deals();
	break;


case('deal_components'):
	list_deal_components_for_edition();
	break;

case('edit_deal'):
	edit_deal();
	break;
case('delete_campaign'):
	$data=prepare_values($_REQUEST, array(
			'id'=>array('type'=>'key')

		));

	delete_campaign($data);
	break;

default:

	$response=array('state'=>404, 'resp'=>_('Operation not found'));
	echo json_encode($response);

}


function list_deal_components_for_edition() {

	if ( isset($_REQUEST['parent']))
		$parent= $_REQUEST['parent'];
	else {
		exit("no parent arg");
	}

	if ( isset($_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		exit("no parent key arg");


	$conf=$_SESSION['state']['deal']['edit_components'];

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
	$order_direction=(preg_match('/desc/', $order_dir)?'desc':'');



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


	$_SESSION['state']['deal']['edit_components']['order']=$order;
	$_SESSION['state']['deal']['edit_components']['order_dir']=$order_direction;
	$_SESSION['state']['deal']['edit_components']['nr']=$number_results;
	$_SESSION['state']['deal']['edit_components']['sf']=$start_from;
	$_SESSION['state']['deal']['edit_components']['f_field']=$f_field;
	$_SESSION['state']['deal']['edit_components']['f_value']=$f_value;





	if ($parent=='deal')
		$where=sprintf("where `Deal Component Deal Key`=%d   ", $parent_key);
	else
		$where=sprintf("where false ");;



	// print "$parent $where";
	$filter_msg='';
	$wheref='';
	if ($f_field=='description' and $f_value!='')
		$wheref.=" and ( `Deal Component Description` like '".addslashes($f_value)."%' or `Deal Component Allowance Description` like '".addslashes($f_value)."%'  )   ";
	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Deal Component Name` like '".addslashes($f_value)."%'";





	$sql="select count(*) as total from `Deal Component Dimension` $where $wheref";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(*) as total_without_filters from `Deal Dimension` $where ";


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


	$rtext=number($total_records)." ".ngettext(_('allowance'), _('allowances'), $total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)", $number_results, _('rpp'));
	else
		$rtext_rpp=_("Showing all");







	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this name ")." <b>".$f_value."*</b> ";
			break;
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this code ")." <b>*".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with name like')." <b>".$f_value."*</b>";
			break;
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with code like')." <b>*".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;

	if ($order=='name')
		$order='`Deal Component Name`';
	elseif ($order=='orders')
		$order='`Deal Component Total Acc Used Orders`';
	elseif ($order=='customers')
		$order='`Deal Component Total Acc Used Customers`';
	else
		$order='`Deal Component Name`';


	$sql="select * from `Deal Component Dimension` $where  $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	//print $sql;
	$res = mysql_query($sql);

	$total=mysql_num_rows($res);
	$adata=array();
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		$orders=number($row['Deal Component Total Acc Used Orders']);
		$customers=number($row['Deal Component Total Acc Used Customers']);

		$duration='';
		if ($row['Deal Component Expiration Date']=='' and $row['Deal Component Begin Date']=='') {
			$duration=_('Permanent');
		}else {

			if ($row['Deal Component Begin Date']!='') {
				$duration=strftime("%x", strtotime($row['Deal Component Begin Date']." +00:00"));

			}
			$duration.=' - ';
			if ($row['Deal Component Expiration Date']!='') {
				$duration.=strftime("%x", strtotime($row['Deal Component Expiration Date']." +00:00"));

			}else {
				$duration.=_('Present');
			}

		}

		switch ($row['Deal Component Status']) {
		case 'Active':
		case 'Waiting':
			$edit_status=sprintf('<div id="component_state_edit_%d" class="buttons small"><button class="negative" onClick="edit_component_state(%d,\'Suspended\')">%s</button></div>',
				$row['Deal Component Key'],
				$row['Deal Component Key'],
				_('Suspend')
			);
			break;
		case 'Suspended':
			$edit_status=sprintf('<div  id="component_state_edit_%d" class="buttons small"><button class="positive" onClick="edit_component_state(%d,\'Active\')">%s</button></div>',
				$row['Deal Component Key'],
				$row['Deal Component Key'],
				_('Activate'));
			break;
		default:
			$edit_status=$row['Deal Component Status'];
		}


		switch ($row['Deal Component Status']) {
		case 'Waiting':
			$state=sprintf('<span id="component_state_%d"><img src="art/icons/bullet_orange.png" alt="%s" title="%s"></span>', $row['Deal Component Key'], _('Waiting'), _('Offer waiting'));
			break;
		case 'Active':
			$state=sprintf('<span id="component_state_%d"><img src="art/icons/bullet_green.png" alt="%s" title="%s"></span>', $row['Deal Component Key'], _('Active'), _('Offer active'));
			break;
		case 'Suspended':
			$state=sprintf('<span id="component_state_%d"><img src="art/icons/bullet_red.png" alt="%s" title="%s"></span>', $row['Deal Component Key'], _('Suspended'), _('Offer suspended'));
			break;
		case 'Finish':
			$state=sprintf('<span id="component_state_%d"><img src="art/icons/bullet_grey.png" alt="%s" title="%s"></span>', $row['Deal Component Key'], _('Finished'), _('Offer finished'));
			break;
		default:
			$state=sprintf('<span id="component_state_%d"></span>', $row['Deal Component Key']);

			$state=$row['Deal Status'];
		}


		switch ($row['Deal Component Allowance Target']) {

		default:
			$allowance_target=$row['Deal Component Allowance Target'];
		}



		$allowance=$row['Deal Component Allowance Description'];
		if ($row['Deal Component Allowance Target XHTML Label']!='') {
			$allowance.=' ('.$allowance_target.' '.$row['Deal Component Allowance Target XHTML Label'].')';
		}


		$adata[]=array(
			'name'=>$row['Deal Component Name'],
			'terms'=>$row['Deal Component Terms Description'],
			'allowance'=>$allowance,

			'target'=>$row['Deal Component Allowance Target'],

			'duration'=>$duration,
			'edit_status'=>$edit_status,
			'state'=>$state

		);
	}
	mysql_free_result($res);



	// if($total<$number_results)
	//  $rtext=$total.' '.ngettext('store','stores',$total);
	//else
	//  $rtext='';

	//   $total_records=ceil($total_records/$number_results)+$total_records;

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


function update_deal_metadata($data) {

	require_once 'class.DealComponent.php';
	require_once 'class.DealCampaign.php';




	$deal_metadata=new DealComponent($data['deal_metadata_key']);

	$deal_metadata->update_field_switcher('Deal Component XHTML Allowance Description Label', $data['allowances_label']);
	$deal_metadata->update_field_switcher('Deal Component XHTML Terms Description Label', $data['terms_label']);




	$deal_metadata->update_terms_allowances(array(
			'Terms'=>$data['terms'],
			'Allowances'=>$data['allowances'])
	);
	if (!$deal_metadata->error) {
	
	
		switch ($deal_metadata->data['Deal Component Allowance Target']) {
		    case 'Family':
		        $sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Family Catalogue' and `Page Parent Key`=%d  ",
		        $deal_metadata->data['Deal Component Allowance Target Key']
		        );
		        $res=mysql_query($sql);
		        while($row=mysql_fetch_assoc($res)){
		        	$page=new Page($row['Page Key']);
		        	$page->refresh_cache();
		        }
		        
		        
		        break;
		    default:
		        
		        break;
		}

	
	
		$response= array('state'=>200,
			'updated'=>$deal_metadata->updated,
			'deal_metadata_key'=>$deal_metadata->id,
			'deal_metadata_description'=>$deal_metadata->get('Description'),
			'deal_metadata_name'=>$deal_metadata->get('Deal Component Name'),
			'deal_key'=>$deal_metadata->get('Deal Component Deal Key'),

		);

	} else {
		$response= array('state'=>400, 'msg'=>$deal_metadata->msg);
	}
	echo json_encode($response);



}

function update_campaign($data) {

	require_once 'class.DealCampaign.php';

	$campaign=new DealCampaign($data['campaign_key']);
	$campaign->update(array($data['key']=>$data['newvalue']));

	if (!$campaign->error) {
		$response= array('state'=>200,
			'updated'=>$campaign->updated,
			'newvalue'=>$campaign->new_value,
			'key'=>$data['okey'],
		);

	} else {
		$response= array('state'=>400, 'msg'=>$deal_metadata->msg);
	}
	echo json_encode($response);
}

function update_campaign_state($data) {

	require_once 'class.DealCampaign.php';

	$campaign=new DealCampaign($data['campaign_key']);

	if (isset($data['values']['state_to']) and $data['values']['state_to']['value']=='Finish') {
		$data['values']['Deal Campaign Valid To']=array('okey'=>'from', 'value'=>gmdate('Y-m-d H:i:s'));
	}

	if (isset($data['values']['state_to']) and $data['values']['state_to']['value']=='Permanent') {
		$data['values']['Deal Campaign Valid To']=array('okey'=>'from', 'value'=>'');
	}

	if (isset($data['values']['state_from']) and $data['values']['state_from']['value']=='Start') {
		$data['values']['Deal Campaign Valid From']=array('okey'=>'from', 'value'=>gmdate('Y-m-d H:i:s'));

	}


	//print_r($data['values']);
	foreach ($data['values'] as $_key=>$_data) {



		if (in_array($_key, array('Deal Campaign Valid To', 'Deal Campaign Valid From') )) {

			if ($_key=='Deal Campaign Valid From') {
				$dates=prepare_mysql_dates($_data['value'], $_data['value'], '', 'only_dates');
				$_data['value']=$dates['mysql_from'].' 00:00:00';
			}
			if ($_key=='Deal Campaign Valid To') {
				$dates=prepare_mysql_dates($_data['value'], $_data['value'], '', 'only_dates');
				if ($dates['mysql_from']!='') {
					$_data['value']=$dates['mysql_from'].' 23:59:59';
				}
			}


			$campaign->update(array($_key=>$_data['value']));
		}

	}
	$campaign->update_status_from_dates();


	if (!$campaign->error) {
		$response= array(array('state'=>200,
				'updated'=>$campaign->updated,
				'key'=>'state_to'

			));

	} else {
		$response= array('state'=>400, 'msg'=>$deal_metadata->msg);
	}
	echo json_encode($response);
}




function old_edit_deal() {

	require_once 'class.DealComponent.php';


	//print_r($_REQUEST);

	$deal_metadata=new DealComponent($_REQUEST['deal_key']);
	global $editor;
	$deal_metadata->editor=$editor;
	$deal_metadata->update(array($_REQUEST['key']=>stripslashes(urldecode($_REQUEST['newvalue']))));


	if ($deal_metadata->updated) {
		$response= array('state'=>200, 'newvalue'=>$deal_metadata->new_value, 'key'=>$_REQUEST['key'], 'description'=>$deal_metadata->get('Description'));

	} else {
		$response= array('state'=>400, 'msg'=>$deal_metadata->msg, 'key'=>$_REQUEST['key']);
	}
	echo json_encode($response);
}

function list_campaigns_for_edition() {


	$parent='store';

	if ( isset($_REQUEST['parent']))
		$parent= $_REQUEST['parent'];

	if ($parent=='store')
		$parent_id=$_SESSION['state']['store']['id'];
	else
		return;

	$conf=$_SESSION['state'][$parent]['campaigns'];


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
	$order_direction=(preg_match('/desc/', $order_dir)?'desc':'');
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


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


	$_SESSION['state'][$parent]['campaigns']=array('order'=>$order, 'order_dir'=>$order_direction, 'nr'=>$number_results, 'sf'=>$start_from, 'where'=>$where, 'f_field'=>$f_field, 'f_value'=>$f_value);

	if ($parent=='store')
		$where=sprintf("where  `Store Key`=%d    ", $parent_id);
	else
		$where=sprintf("where true ");;

	$filter_msg='';
	$wheref='';
	if ($f_field=='description' and $f_value!='')
		$wheref.=" and  `Deal Description` like '".addslashes($f_value)."%'";
	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Deal Name` like '".addslashes($f_value)."%'";

	$sql="select count(*) as total from `Deal Dimension`   $where $wheref";
	//  print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total `Deal Dimension`   $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('campaign', 'campaigns', $total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)", $number_results, _('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any campaign with this name ")." <b>".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any campaign with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('campaigns with name like')." <b>".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('campaigns with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;

	if ($order=='name')
		$order='`Deal Name`';
	elseif ($order=='description')
		$order='`Deal Description`';
	else
		$order='`Deal Name`';


	$sql="select *  from `Deal Dimension` $where    order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);

	$total=mysql_num_rows($res);

	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$sql=sprintf("select * from `Campaign Deal Schema`  where `Deal Key`=%d  ", $row['Deal Key']);
		$res2 = mysql_query($sql);
		$deals='<ul style="padding:10px 20px">';
		while ($row2=mysql_fetch_array($res2, MYSQL_ASSOC)) {
			$deals.=sprintf("<li style='list-style-type: circle' >%s</li>", $row2['Deal Component Name']);
		}
		$deals.='</ul>';
		$adata[]=array(
			'name'=>$row['Deal Name'],
			'description'=>$row['Deal Description'].$deals


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

function list_deals_for_edition() {


	if (!isset($_REQUEST['parent']) or !isset($_REQUEST['parent_key'])) {

		exit("no parent");
	}

	$parent= $_REQUEST['parent'];
	$parent_key=$_REQUEST['parent_key'];




	$conf=$_SESSION['state'][$parent]['edit_offers'];


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
	$order_direction=(preg_match('/desc/', $order_dir)?'desc':'');



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


	$_SESSION['state'][$parent]['edit_offers']['order']=$order;
	$_SESSION['state'][$parent]['edit_offers']['order_dir']=$order_direction;
	$_SESSION['state'][$parent]['edit_offers']['sf']=$number_results;
	$_SESSION['state'][$parent]['edit_offers']['f_field']=$f_field;
	$_SESSION['state'][$parent]['edit_offers']['f_value']=$f_value;



	if ($parent=='store')
		$where=sprintf("where `Deal Component Record Type`='Normal' and  DM.`Store Key`=%d and DM.`Deal Component Trigger`='Order'    ", $parent_key);
	elseif ($parent=='department')
		$where=sprintf("where   `Deal Component Record Type`='Normal' and DM.`Deal Component Trigger`='Department' and  DM.`Deal Component Trigger Key`=%d   ", $parent_key);
	elseif ($parent=='family')
		$where=sprintf("where  `Deal Component Record Type`='Normal' and  DM.`Deal Component Trigger`='Family' and  DM.`Deal Component Trigger Key`=%d   ", $parent_key);
	elseif ($parent=='product')
		$where=sprintf("where  `Deal Component Record Type`='Normal'  and DM.`Deal Component Trigger`='Product' and  DM.`Deal Component Trigger Key`=%d   ", $parent_key);
	else
		$where=sprintf("where `Deal Component Record Type`='Normal' ");;



	$filter_msg='';
	$wheref='';

	if ($f_field=='description' and $f_value!='')
		$wheref.=" and ( `Deal Component Terms Description` like '".addslashes($f_value)."%' or `Deal Component Allowance Description` like '".addslashes($f_value)."%'  )   ";

	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Deal Component Name` like '".addslashes($f_value)."%'";

	$sql="select count(*) as total from `Deal Component Dimension` DM   $where $wheref";
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
		$sql="select count(*) as total `Deal Component Dimension`  DM  $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('deal', 'deals', $total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)", $number_results, _('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this name ")." <b>".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with name like')." <b>".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;

	if ($order=='name')
		$order='DM.`Deal Component Name`';
	elseif ($order=='description')
		$order='`Deal Component Terms Description`,`Deal Component Allowance Description`';
	else
		$order='DM.`Deal Component Name`';


	$sql="select DM.`Deal Component XHTML Allowance Description Label`,DM.`Deal Component XHTML Terms Description Label`,`Deal Term Allowances Label`,`Deal Code`,`Deal Number Active Components`,`Deal Component Expiration Date`,`Deal Description`,D.`Deal Key`,DM.`Deal Component Trigger`,`Deal Component Key`,DM.`Deal Component Name`,D.`Deal Name`
 	from `Deal Component Dimension` DM left join `Deal Dimension`D  on (DM.`Deal Component Deal Key`=D.`Deal Key`)  $where    order by $order $order_direction limit $start_from,$number_results    ";
//	print $sql;
	$res = mysql_query($sql);
	$total=mysql_num_rows($res);
	$adata=array();
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		// $meta_data=preg_split('/,/',$row['Deal Component Allowance']);

		$deal_metadata=new DealComponent($row['Deal Component Key']);
		$input_allowance='';
		foreach ($deal_metadata->allowance_input_form() as $form_data) {
			$input_allowance.=sprintf('<td style="text-align:right;width:150px;padding-right:10px" >%s</td>
 				<td style="width:15em"  style="text-align:left">
 					<input id="deal_allowance%d" onKeyUp="deal_allowance_changed(%d)" %s class="%s" style="width:5em" value="%s" ovalue="%s" /> %s

 				</td>'
				, $form_data['Label']
				, $row['Deal Component Key']
				, $row['Deal Component Key']
				, ($form_data['Lock Value']?'READONLY':'')
				, $form_data['Value Class']
				, $form_data['Value']
				, $form_data['Value']
				, $form_data['Lock Label']



			);
		}
		$input_term='';
		foreach ($deal_metadata->terms_input_form() as $form_data) {

			if ($form_data['Value Class']=='country') {
				$input_term=sprintf('<td style="text-align:right;width:150px;padding-right:10px" >%s</td>
 					<td style="width:15em"  style="text-align:left"><div style="margin-top:1px"><input id="country_code" value="" type="hidden">
 						<input id="country" %s class="%s"style="width:15em" value="%s" /><div id="country_container" style="" ></div></div> %s

 						<script type="text/javascript">
 							var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
 							Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a"]}
 							var Countries_AC = new YAHOO.widget.AutoComplete("country", "country_container", Countries_DS);
 							Countries_AC.useShadow = true;
 							Countries_AC.resultTypeList = false;
 							Countries_AC.formatResult = country_formatResult;
 							Countries_AC.itemSelectEvent.subscribe(onCountrySelected);
 						</script>
 					</td>'
					, $form_data['Label']
					, ($form_data['Lock Value']?'READONLY':'')
					, $form_data['Value Class']
					, $form_data['Value']
					, $form_data['Lock Label']);
			} else {

				$input_term=sprintf('<td style="text-align:right;width:150px;padding-right:10px" >%s</td>
		<td style="width:15em"  style="text-align:left"><input id="deal_term%d" onKeyUp="deal_term_changed(%d)" %s class="%s" style="width:5em" value="%s" ovalue="%s" /> %s </td>'
					, $form_data['Label']
					, $row['Deal Component Key']
					, $row['Deal Component Key']
					, ($form_data['Lock Value']?'READONLY':'')
					, $form_data['Value Class']
					, $form_data['Value']
					, $form_data['Value']
					, $form_data['Lock Label']

				);

			}

		}


		if ($row['Deal Component Expiration Date']=='') {
			$valid_to='<span style="font-style:italic">'._('Permanent').'</span> ';
		}else {
			$valid_to='<input style="width:65px" value=""/>';
		}


		$edit='<table style="margin:10px"><tr style="border:none">'.$input_allowance.'</tr><tr style="border:none">'.$input_term.'</tr>
<tr style="border:none">
	<td style="text-align:right;padding-right:10px">'._('Valid to').':</td><td>'.$valid_to.'</td></tr>
	<tr style="border:none"><td colspan=2><div class="buttons small"><button onClick="save_metadata_deal('.$row['Deal Component Key'].')" id="save_metadata_deal'.$row['Deal Component Key'].'" class="disabled positive">'._('Save').'</button><button onClick="cancel_metadata_deal('.$row['Deal Component Key'].')" id="cancel_metadata_deal'.$row['Deal Component Key'].'" class="disabled negative">'._('Reset').'</button></div></td></tr>

</table>';





		$name=sprintf('<a href="deal.php?id=%d" id="deal_name%d">%s</a><br/><div  id="deal_description%d" style="margin-top:5px;margin-bottom:5px">%s</div>',
			$row['Deal Key'],
			$row['Deal Key'],
			$row['Deal Name'],
			$row['Deal Key'],
			$row['Deal Description']

		);

//		if ($row['Deal Number Active Components']==1) {

			$name.=sprintf('<div class="buttons small left"><button id="fill_edit_deal_form%d" onClick="fill_edit_deal_form(%d)" >%s</buttons></div>',

				$row['Deal Key'],
				$row['Deal Key'],
				_('Edit')

			);

	//	}
	
	if ($row['Deal Number Active Components']>1) {
			$name.=sprintf('<img src="art/icons/bullet_error.png"  title="%s" />',_('Editing this will affect other allowances'));
	}

		$status="<br/><span id='deal_state".$deal_metadata->id."' style='font-weight:800;padding:10px 0px'>".$deal_metadata->get_xhtml_status()."</span> <img style='cursor:pointer' onClick='deal_show_edit_state(this,".$deal_metadata->id.",\"".$deal_metadata->data['Deal Component Status']."\")'  src='art/icons/edit.gif'>";
		$status.= '<div style="margin-top:10px;margin-left:0px;display:none" id="suspend_deal_button'.$deal_metadata->id.'"  class="buttons small left"><button onClick="suspend_deal_metadata('.$deal_metadata->id.')"  class="negative" style="margin-left:0"> '._("Suspend").'</button></div>';
		$status.= '<div style="margin-top:10px;margin-left:0px;display:none" id="activate_deal_button'.$deal_metadata->id.'"   class="buttons small left"><button onClick="activate_deal_metadata('.$deal_metadata->id.')" class="positive"> '._("Activate").'</button></div>';

		//if ($row['Campaign Deal Schema Key']) {
		// $name.=sprintf('<br/><a style="text-decoration:underline" href="edit_campaign.php?id=%d">%s</a>',$row['Campaign Deal Schema Key'],$row['Deal Name']);
		//}
		$adata[]=array(
			'status'=>$status,
			'code'=>$row['Deal Code'],
			'term_allowances_label'=>$row['Deal Term Allowances Label'],
			'name'=>$name,
			'description'=>'<span id="deal_metadata_description_'.$deal_metadata->id.'" style="color:#777;font-style:italic">'.$deal_metadata->get('Description').'</span>'.'</span><br/>
			<table>
			<tr><td>'._('Terms').': </td><td><input onKeyUp="deal_metadata_description_changed(\'terms\','.$deal_metadata->id.')"  id="deal_metadata_terms_label_input'.$deal_metadata->id.'" style="margin-top:5px;width:100%" value="'.$row['Deal Component XHTML Terms Description Label'].'"  ovalue="'.$row['Deal Component XHTML Terms Description Label'].'"  /></td></tr>
			<tr><td>'._('Allowances').': </td><td><input onKeyUp="deal_metadata_description_changed(\'allowances\','.$deal_metadata->id.')"  id="deal_metadata_allowances_label_input'.$deal_metadata->id.'" style="margin-top:5px;width:100%" value="'.$row['Deal Component XHTML Allowance Description Label'].'"  ovalue="'.$row['Deal Component XHTML Allowance Description Label'].'"  /></td></tr>
			</table>
			'.$edit,
			'dates'=>''


		);
	}
	mysql_free_result($res);



	// if($total<$number_results)
	//  $rtext=$total.' '.ngettext('store','stores',$total);
	//else
	//  $rtext='';

	//   $total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function update_deal_metadata_status($data) {



	require_once 'class.DealCampaign.php';

	$deal_metadata=new DealComponent($data['deal_metadata_key']);
	$deal_metadata->update_status($data['value']);


	if ($deal_metadata->error) {
		$response=array(
			'state'=>400,
			'msg'=>$deal_metadata->msg
		);
	}
	else {



		switch ($deal_metadata->data['Deal Component Status']) {
		case 'Active':
		case 'Waiting':
			$edit_status=sprintf('<div id="component_state_edit_%d" class="buttons small"><button class="negative" onClick="edit_component_state(%d,\'Suspended\')">%s</button></div>',
				$deal_metadata->data['Deal Component Key'],
				$deal_metadata->data['Deal Component Key'],
				_('Suspend')
			);
			break;
		case 'Suspended':
			$edit_status=sprintf('<div  id="component_state_edit_%d" class="buttons small"><button class="positive" onClick="edit_component_state(%d,\'Active\')">%s</button></div>',
				$deal_metadata->data['Deal Component Key'],
				$deal_metadata->data['Deal Component Key'],
				_('Activate'));
			break;
		default:
			$edit_status=$deal_metadata->data['Deal Component Status'];
		}


		switch ($deal_metadata->data['Deal Component Status']) {
		case 'Waiting':
			$state=sprintf('<span id="component_state_%d"><img src="art/icons/bullet_orange.png" alt="%s" title="%s"></span>', $deal_metadata->data['Deal Component Key'], _('Waiting'), _('Offer waiting'));
			break;
		case 'Active':
			$state=sprintf('<span id="component_state_%d"><img src="art/icons/bullet_green.png" alt="%s" title="%s"></span>', $deal_metadata->data['Deal Component Key'], _('Active'), _('Offer active'));
			break;
		case 'Suspended':
			$state=sprintf('<span id="component_state_%d"><img src="art/icons/bullet_red.png" alt="%s" title="%s"></span>', $deal_metadata->data['Deal Component Key'], _('Suspended'), _('Offer suspended'));
			break;
		case 'Finish':
			$state=sprintf('<span id="component_state_%d"><img src="art/icons/bullet_grey.png" alt="%s" title="%s"></span>', $deal_metadata->data['Deal Component Key'], _('Finished'), _('Offer finished'));
			break;
		default:
			$state=sprintf('<span id="component_state_%d"></span>', $row['Deal Component Key']);

			$state=$row['Deal Status'];
		}


		switch ($deal_metadata->data['Deal Component Allowance Target']) {
		    case 'Family':
		        $sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Family Catalogue' and `Page Parent Key`=%d  ",
		        $deal_metadata->data['Deal Component Allowance Target Key']
		        );
		        $res=mysql_query($sql);
		        while($row=mysql_fetch_assoc($res)){
		        	$page=new Page($row['Page Key']);
		        	$page->refresh_cache();
		        }
		        
		        
		        break;
		    default:
		        
		        break;
		}


		$response=array(
			'state'=>200,
			'msg'=>'ok',
			'key'=>$deal_metadata->id,
			'status'=>$deal_metadata->get_xhtml_status(),
			'button_edit_status'=>$edit_status,
			'status_icon'=>$state

		);
	}
	echo json_encode($response);
}


function update_deal_status($data) {



	require_once 'class.DealCampaign.php';

	$deal=new Deal($data['deal_key']);
	$deal->update_status($data['value']);


	foreach ($deal->get_deal_component_keys() as $deal_component_key) {
		$deal_compoment=new DealComponent($deal_component_key);
		$deal_compoment->update_status($data['value']);
	}





	if ($deal->error) {
		$response=array(
			'state'=>400,
			'msg'=>$deal->msg
		);
	}
	else {



		switch ($deal->data['Deal Status']) {
		case 'Active':
		case 'Waiting':
			$edit_status=sprintf('<div id="deal_state_edit_%d" class="buttons small"><button class="negative" onClick="edit_deal_state(%d,\'Suspended\')">%s</button></div>',
				$deal->data['Deal Key'],
				$deal->data['Deal Key'],
				_('Suspend')
			);
			break;
		case 'Suspended':
			$edit_status=sprintf('<div  id="deal_state_edit_%d" class="buttons small"><button class="positive" onClick="edit_deal_state(%d,\'Active\')">%s</button></div>',
				$deal->data['Deal Key'],
				$deal->data['Deal Key'],
				_('Activate'));
			break;
		default:
			$edit_status=$deal->data['Deal Status'];
		}


		switch ($deal->data['Deal Status']) {
		case 'Waiting':
			$state=sprintf('<span id="deal_state_%d"><img src="art/icons/bullet_orange.png" alt="%s" title="%s"></span>',$deal->data['Deal Key'],_('Waiting'),_('Offer waiting'));
			break;
		case 'Active':
			$state=sprintf('<span id="deal_state_%d"><img src="art/icons/bullet_green.png" alt="%s" title="%s"></span>',$deal->data['Deal Key'],_('Active'),_('Offer active'));
			break;
		case 'Suspended':
			$state=sprintf('<span id="deal_state_%d"><img src="art/icons/bullet_red.png" alt="%s" title="%s"></span>',$deal->data['Deal Key'],_('Suspended'),_('Offer suspended'));
			break;
		case 'Finish':
			$state=sprintf('<span id="deal_state_%d"><img src="art/icons/bullet_grey.png" alt="%s" title="%s"></span>',$deal->data['Deal Key'],_('Finished'),_('Offer finished'));
			break;
		default:
			$state=sprintf('<span id="deal_state_%d">%s</span>',$row['Deal Key'],$row['Deal Status']);
		}






		$response=array(
			'state'=>200,
			'msg'=>'ok',
			'key'=>$deal->id,
			'status'=>$deal->get_xhtml_status(),
			'button_edit_status'=>$edit_status,
			'status_icon'=>$state

		);
	}
	echo json_encode($response);
}


function update_deal($data) {


	require_once 'class.Deal.php';


	$deal=new Deal($data['deal_key']);


	if ($data['key']=='Deal Begin Date') {
		$dates=prepare_mysql_dates($data['newvalue'], $data['newvalue'], '', 'only_dates');
		$data['newvalue']=$dates['mysql_from'].' 00:00:00';
	}

	if ($data['key']=='Deal Expiration Date') {
		if ($data['newvalue']=='NOW') {
			$data['newvalue']=gmdate('Y-m-d H:i:s');
		}else if ($data['newvalue']!='') {
				$dates=prepare_mysql_dates($data['newvalue'], $data['newvalue'], '', 'only_dates');
				$data['newvalue']=$dates['mysql_from'].' 23:59:59';
			}
	}


	if ($data['key']=='Deal Status') {
		$deal->update_status($data['newvalue']);
		foreach ($deal->get_deal_component_keys() as $deal_component_key) {
			$deal_compoment=new DealComponent($deal_component_key);
			$deal_compoment->update_status($value);
		}



	}else {
		$deal->update(array($data['key']=>$data['newvalue']));

	}



	if (!$deal->error) {


		if ($data['key']=='Deal Begin Date') {
			$new_value=$deal->get_from_date();
		}if ($data['key']=='Deal Expiration Date') {
			$new_value=$deal->get_to_date();
		}else {
			$new_value=$deal->new_value;
		}

		$response= array('state'=>200,
			'updated'=>$deal->updated,
			'newvalue'=>$new_value,
			'key'=>$data['okey'],

		);

	} else {
		$response= array('state'=>400, 'msg'=>$campaign->msg);
	}
	echo json_encode($response);



}


function update_deal_description($data) {

	$deal=new Deal($data['deal_key']);
	$deal->update(array('Deal Name'=>$data['name'], 'Deal Description'=>$data['description']));


	if ($deal->error) {
		$response=array(
			'state'=>400,
			'msg'=>$deal->msg
		);
	}
	else {
		$response=array(
			'state'=>200,
			'msg'=>'ok',
			'key'=>$deal->id,
			'name'=>$deal->data['Deal Name'],
			'description'=>$deal->data['Deal Description']

		);
	}
	echo json_encode($response);
}

function create_campaign($data) {

	include_once 'class.Store.php';
	include_once 'class.DealCampaign.php';

	$store=new Store('id', $data['parent_key']);


	if ($store->id) {

		$dates=prepare_mysql_dates($data['values']['Deal Campaign Valid From'], $data['values']['Deal Campaign Valid To'], '', 'only_dates');


		if ($dates['mysql_from']==date('Y-m-d')) {
			$data['values']['Deal Campaign Valid From']=gmdate('Y-m-d H:i:s');
		}else {

			$date = new DateTime($dates['mysql_from'].' 00:00:00', new DateTimeZone($store->data['Store Timezone']));
			$data['values']['Deal Campaign Valid From']=gmdate('Y-m-d H:i:s',$date->format('U'));
		}

		$date = new DateTime($dates['mysql_to'].' 23:59:59', new DateTimeZone($store->data['Store Timezone']));


		$data['values']['Deal Campaign Valid To']=($dates['mysql_to']!=''?  gmdate('Y-m-d H:i:s',$date->format('U')):''   );



		$campaign_data=$data['values'];
		$campaign=$store->add_campaign($campaign_data);

		if ($campaign->new) {
			$response=array('state'=>200, 'campaign_key'=>$campaign->id, 'action'=>'created');

		}elseif ($store->id) {
			$response=array('state'=>400, 'campaign_key'=>$campaign->id, 'action'=>'found');

		}else {
			$response=array('state'=>400, 'msg'=>$campaign->msg);

		}
		echo json_encode($response);

	}else {
		$response=array('state'=>404, 'resp'=>'store_not_found');
		echo json_encode($response);
	}
}

function create_deal($data) {

	global $smarty;

	include_once 'class.Store.php';
	include_once 'class.Deal.php';
	include_once 'class.DealCampaign.php';

	$store=new Store('id', $data['parent_key']);

	putenv('LC_ALL='.$store->data['Store Locale'].'.UTF-8');
	//setlocale(LC_ALL,$store->data['Store Locale'].'.UTF-8');
	bindtextdomain("inikoo", "./locales");
	textdomain("inikoo");
	if ($store->id) {

		if (is_numeric($data['values']['Deal Campaign Key'])) {
			$campaign=new DealCampaign($data['values']['Deal Campaign Key']);
			if (!$campaign->id) {
				$response=array(
					'state'=>404,
					'resp'=>'campaign_not_found',
					'msg'=>_('Campaign not found')
				);
				echo json_encode($response);
				exit;
			}

		}else {

			$dates=prepare_mysql_dates($data['values']['Deal Campaign Valid From'], $data['values']['Deal Campaign Valid To'], '', 'only_dates');


			if ($dates['mysql_from']==date('Y-m-d')) {
				$data['values']['Deal Campaign Valid From']=gmdate('Y-m-d H:i:s');
			}else {

				$date = new DateTime($dates['mysql_from'].' 00:00:00', new DateTimeZone($store->data['Store Timezone']));
				$data['values']['Deal Campaign Valid From']=gmdate('Y-m-d H:i:s',$date->format('U'));
			}

			$date = new DateTime($dates['mysql_to'].' 23:59:59', new DateTimeZone($store->data['Store Timezone']));


			$data['values']['Deal Campaign Valid To']=($dates['mysql_to']!=''?  gmdate('Y-m-d H:i:s',$date->format('U')):''   );


			$campaign_data=$data['values'];
			$campaign=$store->add_campaign($campaign_data);
		}

		if (!$campaign->id) {
			$response=array('state'=>404, 'resp'=>'campaign_not_found');
			echo json_encode($response);

		}



		$deal_data=$data['values'];
		$deal_data['Deal Store Key']=$store->id;

		//print_r($deal_data);
		$no_items=true;
		switch ($deal_data['Deal Trigger']) {
		case 'Department':
			$department=new Department($deal_data['Deal Trigger Key']);
			$deal_data['Deal Trigger XHTML Label']=sprintf('<a href="department.php?id=%d">%s</a>',
				$department->id,
				$department->data['Product Department Code']
			);
			$no_items=false;
			break;
		case 'Family':
			$family=new Family($deal_data['Deal Trigger Key']);
			$deal_data['Deal Trigger XHTML Label']=sprintf('<a href="family.php?id=%d">%s</a>',
				$family->id,
				$family->data['Product Family Code']
			);
			$no_items=false;
			break;
		case 'Product':
			$product=new Product('pid', $deal_data['Deal Trigger Key']);
			$deal_data['Deal Trigger XHTML Label']=sprintf('<a href="product.php?pid=%d">%s</a>',
				$product->pid,
				$product->data['Product Code']
			);
			$no_items=false;
			break;
		case 'Customer':
			$customer=new Customer($deal_data['Deal Trigger Key']);
			$deal_data['Deal Trigger XHTML Label']=sprintf('<a href="customer.php?id=%d">%s</a>',
				$customer->id,
				$customer->data['Customer Name']
			);
			break;
		case 'Customer Category':
			$category=new Category($deal_data['Deal Trigger Key']);
			$deal_data['Deal Trigger XHTML Label']=sprintf('<a href="category.php?id=%d">%s</a>',
				$category->id,
				$category->data['Category Label']
			);
			$no_items=false;
			break;
		case 'Customer List':
			include_once 'class.List.php';
			$list=new SubjectList($deal_data['Deal Trigger Key']);
			$deal_data['Deal Trigger XHTML Label']=sprintf('<a href="list.php?id=%d">%s</a>',
				$list->id,
				$list->data['List Name']
			);
			$no_items=false;
			break;
		}

		$deal=$campaign->add_deal($deal_data);
		if (!$deal->new) {

			$msg=sprintf('%s <a href="deal.php?id=%d">%s</a>',
				_('Another deal already has this code'),
				$deal->data['Deal Key'],
				$deal->data['Deal Code']
			);

			$response=array(
				'state'=>404,
				'resp'=>'deal_found',
				'msg'=>$msg
			);
			echo json_encode($response);
			exit;

		}

		$voucher_key='';

		switch ($deal_data['Deal Terms Type']) {
		case 'Department Quantity Ordered':
		case 'Family Quantity Ordered':
		case 'Product Quantity Ordered':
			$terms='order '.$deal_data['if_order_more'];
			$terms_label=_('Buy').' '.$deal_data['if_order_more'];


			$deal_data['Deal Component Allowance Target']=$deal_data['Deal Trigger'];
			$deal_data['Deal Component Allowance Target Key']=$deal_data['Deal Trigger Key'];


			break;
		case 'Department For Every Quantity Any Product Ordered':
		case 'Family For Every Quantity Any Product Ordered':

			$terms='for every '.$deal_data['for_every_ordered'];
			$terms_label=_('For every').' '.number($deal_data['for_every_ordered']).' '._('you buy');
			$terms_label=sprintf(_('buy %1$s'),number($deal_data['for_every_ordered']));

			$deal_data['Deal Component Allowance Target']=$deal_data['Deal Trigger'];
			$deal_data['Deal Component Allowance Target Key']=$deal_data['Deal Trigger Key'];

			break;

		case 'Department For Every Quantity Ordered':
		case 'Family For Every Quantity Ordered':
		case 'Product For Every Quantity Ordered':
			$terms='for every '.$deal_data['for_every_ordered'];
			$terms_label=sprintf(_('For every %1$s you buy'),number($deal_data['for_every_ordered']));

			$deal_data['Deal Component Allowance Target']=$deal_data['Deal Trigger'];
			$deal_data['Deal Component Allowance Target Key']=$deal_data['Deal Trigger Key'];

			break;

		case 'Voucher AND Amount':
			$no_items=true;
			if ($deal_data['voucher_code_type']=='Random') {
				$voucher_code=get_vocher_code($store->id);

			}else {
				$voucher_code=$deal_data['voucher_code'];

			}
			include_once 'class.Voucher.php';
			$voucher_data=array(
				'Voucher Code'=>$voucher_code,
				'Voucher Store Key'=>$store->id,
				'Voucher Subject Type'=>($deal_data['Deal Trigger']=='Customer'?'Customer':$deal_data['voucher_type']),
				'Voucher Deal Campaign Key'=>$campaign->id,
				'Voucher Deal Key'=>$deal->id,
				'Voucher Subject Key Metadata'=>($deal_data['Deal Trigger']=='Customer'?$customer->id:''),
				'Voucher Usage Limit per Customer'=>($deal_data['voucher_type']=='Private' and $deal_data['Deal Trigger']!='Customer'?'':1)
			);

			$voucher=new Voucher('create', $voucher_data);
			$voucher_key=$voucher->id;



			switch ($deal_data['amount_type']) {
			case 'Order Total Amount':
				$amount_type='Total';
				$amount_type_formated=_('Total');
				break;
			case 'Order Total Net Amount':
				$amount_type='Net';
				$amount_type_formated=_('Net');
				break;
			case 'Order Items Net Amount':
				$amount_type='Items Net';
				$amount_type_formated=_('Items Net');
				break;
			}


			$terms='voucher '.$voucher->data['Voucher Code'].' '.($deal_data['voucher_type']=='Private'?'(Private) ':'').'& '.money($deal_data['amount'], $store->data['Store Currency Code']).' '.$amount_type;
			$terms_label=_('Voucher').': <b>'.$voucher->data['Voucher Code'].'</b>'.($deal_data['voucher_type']=='Private'?' ('._('Internal use only').')':'').' & +'.money($deal_data['amount'], $store->data['Store Currency Code']).' '.$amount_type_formated;

			$deal_data['Deal Component Terms']=$voucher->data['Voucher Code'].';'.$deal_data['amount'].';'.$deal_data['amount_type'];
			break;
		case 'Voucher AND Order Number':

			if ($deal_data['voucher_code_type']=='Random') {
				$voucher_code=get_vocher_code($store->id);

			}else {
				$voucher_code=$deal_data['voucher_code'];

			}
			include_once 'class.Voucher.php';
			$voucher_data=array(
				'Voucher Code'=>$voucher_code,
				'Voucher Store Key'=>$store->id,
				'Voucher Subject Type'=>($deal_data['Deal Trigger']=='Customer'?'Customer':$deal_data['voucher_type']),
				'Voucher Deal Campaign Key'=>$campaign->id,
				'Voucher Deal Key'=>$deal->id,
				'Voucher Subject Key Metadata'=>($deal_data['Deal Trigger']=='Customer'?$customer->id:''),
				'Voucher Usage Limit per Customer'=>($deal_data['voucher_type']=='Private' and $deal_data['Deal Trigger']!='Customer'?'':1)
			);
			$voucher=new Voucher('create', $voucher_data);

			$voucher_key=$voucher->id;



			$deal_data['Deal Component Terms']=$voucher->data['Voucher Code'].';'.$deal_data['order_number'];

			$terms='voucher '.$voucher->data['Voucher Code'].' & '.$deal_data['order_number'].' th order';
			$terms_label=_('Voucher').': <b>'.$voucher->data['Voucher Code'].'</b>'.($deal_data['voucher_type']=='Private'?' ('._('Internal use only').')':'').' & ';

			switch ($deal_data['order_number']) {
			case 1:
				$terms_label.=_('first order');
				break;
			case 2:
				$terms_label.=_('second order');
				break;
			case 3:
				$terms_label.=_('third order');
				break;
			case 4:
				$terms_label.=_('forth order');
				break;
			default:
				$terms_label=number($deal_data['order_number']).'th '._('Order');
			}

			$deal_data['Deal Component Terms']=$voucher->data['Voucher Code'].';'.$deal_data['order_number'];

			break;

		case 'Voucher AND Order Interval':

			if ($deal_data['voucher_code_type']=='Random') {
				$voucher_code=get_vocher_code($store->id);

			}else {
				$voucher_code=$deal_data['voucher_code'];

			}
			include_once 'class.Voucher.php';
			$voucher_data=array(
				'Voucher Code'=>$voucher_code,
				'Voucher Store Key'=>$store->id,
				'Voucher Subject Type'=>($deal_data['Deal Trigger']=='Customer'?'Customer':$deal_data['voucher_type']),
				'Voucher Deal Campaign Key'=>$campaign->id,
				'Voucher Deal Key'=>$deal->id,
				'Voucher Subject Key Metadata'=>($deal_data['Deal Trigger']=='Customer'?$customer->id:''),
				'Voucher Usage Limit per Customer'=>($deal_data['voucher_type']=='Private' and $deal_data['Deal Trigger']!='Customer'?'':1)
			);

			$voucher=new Voucher('create', $voucher_data);
			$voucher_key=$voucher->id;

			$terms='voucher '.$voucher->data['Voucher Code'].' & '.$deal_data['order_interval'].' days since last order';
			$terms_label=_('Voucher').': <b>'.$voucher->data['Voucher Code'].'</b>'.($deal_data['voucher_type']=='Private'?' ('._('Internal use only').')':'').' & '.number($deal_data['order_interval']).' '._('days');
			$deal_data['Deal Component Terms']=$voucher->data['Voucher Code'].';'.$deal_data['order_interval'];


			break;

		case 'Voucher':

			if ($deal_data['voucher_code_type']=='Random') {
				$voucher_code=get_vocher_code($store->id);

			}else {
				$voucher_code=$deal_data['voucher_code'];

			}

			include_once 'class.Voucher.php';
			$voucher_data=array(
				'Voucher Code'=>$voucher_code,
				'Voucher Store Key'=>$store->id,
				'Voucher Subject Type'=>($deal_data['Deal Trigger']=='Customer'?'Customer':$deal_data['voucher_type']),
				'Voucher Deal Campaign Key'=>$campaign->id,
				'Voucher Deal Key'=>$deal->id,
				'Voucher Subject Key Metadata'=>($deal_data['Deal Trigger']=='Customer'?$customer->id:''),
				'Voucher Usage Limit per Customer'=>(($deal_data['voucher_type']=='Private' and $deal_data['Deal Trigger']!='Customer')?'':1)
			);


			$voucher=new Voucher('create', $voucher_data);
			$voucher_key=$voucher->id;

			$terms='voucher '.$voucher->data['Voucher Code'];
			$terms_label=_('Voucher').': <b>'.$voucher->data['Voucher Code'].'</b>'.($deal_data['voucher_type']=='Private'?' ('._('Internal use only').')':'');
			$deal_data['Deal Component Terms']=$voucher->data['Voucher Code'];
			break;
		case 'Amount':
			$no_items=true;

			switch ($deal_data['amount_type']) {
			case 'Order Total Amount':
				$amount_type='Total';
				$amount_type_formated=_('Total');
				break;
			case 'Order Total Net Amount':
				$amount_type='Net';
				$amount_type_formated=_('Net');
				break;
			case 'Order Items Net Amount':
				$amount_type='Items Net';
				$amount_type_formated=_('Items Net');
				break;
			}


			$terms=money($deal_data['amount'], $store->data['Store Currency Code']).' '.$amount_type;
			$terms_label='+'.money($deal_data['amount'], $store->data['Store Currency Code']).' '.$amount_type_formated;
			$deal_data['Deal Component Terms']=$deal_data['amount'].';'.$deal_data['amount_type'];
			break;
		case 'Every Order':
			$terms='every order';
			$terms_label=_('Every Order');
			break;
		case 'Amount AND Order Number':
			$no_items=true;
			switch ($deal_data['amount_type']) {
			case 'Order Total Amount':
				$amount_type='Total';
				$amount_type_formated=_('Total');
				break;
			case 'Order Total Net Amount':
				$amount_type='Net';
				$amount_type_formated=_('Net');
				break;
			case 'Order Items Net Amount':
				$amount_type='Items Net';
				$amount_type_formated=_('Items Net');
				break;
			}

			$terms=money($deal_data['amount'], $store->data['Store Currency Code']).' '.$amount_type.' & '.number($deal_data['order_number']).' th order';
			$terms_label='+'.money($deal_data['amount'], $store->data['Store Currency Code']).' & ';

			switch ($deal_data['order_number']) {
			case 1:
				$terms_label.=_('first order');
				break;
			case 2:
				$terms_label.=_('second order');
				break;
			case 3:
				$terms_label.=_('third order');
				break;
			case 4:
				$terms_label.=_('forth order');
				break;
			default:
				$terms_label.=sprintf(_('%1$sth order'),number($deal_data['order_number']));


			}



			$deal_data['Deal Component Terms']=$deal_data['amount'].';'.$deal_data['amount_type'].';'.$deal_data['order_number'];
			break;
		case 'Amount AND Order Interval':
			$no_items=true;

			switch ($deal_data['amount_type']) {
			case 'Order Total Amount':
				$amount_type='Total';
				$amount_type_formated=_('Total');
				break;
			case 'Order Total Net Amount':
				$amount_type='Net';
				$amount_type_formated=_('Net');
				break;
			case 'Order Items Net Amount':
				$amount_type='Items Net';
				$amount_type_formated=_('Items Net');
				break;
			}

			$terms=money($deal_data['amount'], $store->data['Store Currency Code']).' '.$amount_type.' & '.number($deal_data['order_interval']).' days since last order';
			$terms=$deal_data['amount'].';'.$deal_data['amount_type'].';'.$deal_data['order_interval'];
			$terms_label='+'.money($deal_data['amount'], $store->data['Store Currency Code']).' & '.sprintf(_('%1$s days from last order',number($deal_data['order_interval'])));
			$deal_data['Deal Component Terms']=$deal_data['amount'].';'.$deal_data['amount_type'].';'.$deal_data['order_interval'];
			break;

		case 'Order Interval':
			$terms=number($deal_data['order_interval']).' days since last order';
			$terms_label=sprintf(_('%1$s days from last order'),number($deal_data['order_interval']));
			$deal_data['Deal Component Terms']=$deal_data['order_interval'];
			break;
		case 'Order Number':
			$terms=number($deal_data['order_number']).' th order';
			switch ($deal_data['order_number']) {
			case 1:
				$terms_label=_('first order');
				break;
			case 2:
				$terms_label=_('second order');
				break;
			case 3:
				$terms_label=_('third order');
				break;
			case 4:
				$terms_label=_('forth order');
				break;
			default:
				$terms_label=sprintf(_('%1$sth order',number($deal_data['order_number'])));
			}

			$deal_data['Deal Component Terms']=$deal_data['order_number'];
			break;
		default:
			exit('Unknown terms >'.$deal_data['Deal Terms Type'].'<');
		}


		$deal->update(array('Deal Voucher Key'=>$voucher_key),'no_history');


		if ($deal_data['Deal Component Allowance Type']=='Clone') {


			$sql=sprintf("select * from `Deal Component Dimension` where `Deal Component Deal Key`=%d",
				$deal_data['Deal Component Allowance Target Key']);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$deal_component_data=$deal_data;
				$deal_component_data['Deal Component Allowance Type']=$row['Deal Component Allowance Type'];
				$deal_component_data['Deal Component Allowance Target']=$row['Deal Component Allowance Target'];
				$deal_component_data['Deal Component Allowance Target Key']=$row['Deal Component Allowance Target Key'];
				$deal_component_data['Deal Component Allowance Target XHTML Label']=$row['Deal Component Allowance Target XHTML Label'];
				$deal_component_data['Deal Component Allowance Description']=$row['Deal Component Allowance Description'];
				$deal_component_data['Deal Component XHTML Allowance Description Label']=$row['Deal Component XHTML Allowance Description Label'];
				$deal_component_data['Deal Component Allowance']=$row['Deal Component Allowance'];
				$deal_component_data['Deal Component Allowance Lock']=$row['Deal Component Allowance Lock'];
				$deal_component_data['Deal Component Terms Description']=$terms;
				$deal_component_data['Deal Component XHTML Terms Description Label']=$terms_label;
				$deal_component_data['Deal Component Allowance Target Type']=($no_items?'No Items':'Items');
				$deal_component_data['Deal Component Mirror Metadata']=$row['Deal Component Key'];


				$component=$deal->add_component($deal_component_data);


			}


		}
		else {
			switch ($deal_data['Deal Component Allowance Target']) {
			case 'Department':
				$department=new Department($deal_data['Deal Component Allowance Target Key']);
				$deal_data['Deal Component Allowance Target XHTML Label']=sprintf('<a href="department.php?id=%d">%s</a>',
					$department->id,
					$department->data['Product Department Code']
				);
				break;
			case 'Family':
				$family=new Family($deal_data['Deal Component Allowance Target Key']);
				$deal_data['Deal Component Allowance Target XHTML Label']=sprintf('<a href="family.php?id=%d">%s</a>',
					$family->id,
					$family->data['Product Family Code']
				);
				break;
			case 'Product':

				$product=new Product('pid', $deal_data['Deal Component Allowance Target Key']);
				$deal_data['Deal Component Allowance Target XHTML Label']=sprintf('<a href="product.php?pid=%d">%s</a>',
					$product->pid,
					$product->data['Product Code']
				);
				break;
			case 'Shipping':
				$deal_data['Deal Component Allowance Target XHTML Label']='';
				break;
			case 'Charge':
				$deal_data['Deal Component Allowance Target XHTML Label']='';
				break;
			case 'Order':
				$deal_data['Deal Component Allowance Target XHTML Label']='';
				break;

			default:
				exit('Unknown target: >'.$deal_data['Deal Component Allowance Target'].'<');
			}

			switch ($deal_data['Deal Component Allowance Type']) {
			case 'Department Percentage Off':
			case 'Family Percentage Off':
			case 'Product Percentage Off':
				$deal_data['Deal Component Allowance Type']='Percentage Off';
				$allowances=$deal_data['percentage_off'].'% off';
				$allowances_label=$deal_data['percentage_off']._('% off');
				break;

			case 'Percentage Off':
				$allowances=$deal_data['percentage_off'].'% off';
				$allowances_label=$deal_data['percentage_off']._('% off');
				break;
			case 'Get Same Free':
				$allowances=$deal_data['get_same_free'].' free';
				$allowances_label=', '.$deal_data['get_same_free'].' '._('free');
				$allowances_label=' '.sprintf(_('get %1$s free bonus'),number($deal_data['get_same_free']));

				break;
			case 'Get Cheapest Free':
				$allowances='cheapest '.$deal_data['get_same_free'].' free';
				$allowances_label=' '.sprintf(_('get %1$s free'),number($deal_data['get_same_free']));
				$deal_data['Deal Component Terms']=$deal_data['for_every_ordered'];
				$deal_data['Deal Component Allowance']=$deal_data['get_same_free'];
				break;
			case 'Free Shipping':

				$deal_data['Deal Component Allowance Type']='Get Free';
				$deal_data['Deal Component Allowance Target']='Shipping';

				$allowances='free shipping';
				$allowances_label=_('free shipping');
				break;
			case 'Free Charges':
				$deal_data['Deal Component Allowance Type']='Get Free';
				$deal_data['Deal Component Allowance Target']='Charge';
				$allowances='free charges';
				$allowances_label=_('free charges');
				break;

			case 'Clone':
				$deal->update(array('Deal Mirror Metadata'=>$deal_data['Deal Component Allowance Target Key']), 'no_history');
				break;
			case 'Amount Off':
				$deal_data['Deal Component Allowance']=$deal_data['amount_off'];
				$allowances=$deal_data['amount_off']." off";
				$allowances_label=money($deal_data['amount_off'], $store->data['Store Currency Code']).' '._('amount off');

				break;
			case 'Bonus Product From Family':
				$deal_data['Deal Component Allowance Type'] = 'Get Free';
				$deal_data['Deal Component Allowance Target'] = 'Family';
				$deal_data['Deal Component Allowance'] = '1;'.$deal_data['default_free_product_from_family'];
				$allowances = 'get one form family '.$family->data['Product Family Code'];
				$allowances_label=sprintf(_('get one form family %1$s'),$deal_data['Deal Component Allowance Target XHTML Label']);

				break;
			case 'Bonus Product':
				$deal_data['Deal Component Allowance Type']='Get Free';
				$deal_data['Deal Component Allowance Target']='Product';
				$deal_data['Deal Component Allowance'] = '1';
				$allowances = 'get one '.$product->data['Product Code'];
				$allowances_label=sprintf(_('get one %1$s'),$deal_data['Deal Component Allowance Target XHTML Label']);
				break;

			default:
				exit('Unknown Allowance: '.$deal_data['Deal Component Allowance Type']);
			}




			$deal_component_data=array(
				'Deal Component Name'=>$deal_data['Deal Name'],
				'Deal Component XHTML Name Label'=>$deal_data['Deal Name'],
				'Deal Component Terms Description'=>$terms,
				'Deal Component XHTML Terms Description Label'=>$terms_label,
				'Deal Component Allowance Description'=>$allowances,
				'Deal Component XHTML Allowance Description Label'=>$allowances_label,
				'Deal Component Public'=>'Yes',
				'Deal Component Allowance Target Type'=>($no_items?'No Items':'Items')


			);
			$deal_component_data=array_merge($deal_component_data, $deal_data);






			//print_r($deal_component_data);

			$component=$deal->add_component($deal_component_data);

		}

		$deal->update_term_allowances();
		$smarty->assign('deal', $deal);
		$new_deal_message=$smarty->fetch('ar_messages/new_deal.tpl');

		$response=array('state'=>200, 'action'=>'created', 'deal_key'=>$deal->id, 'message'=>$new_deal_message);
		echo json_encode($response);

	}else {
		$response=array('state'=>404, 'resp'=>'store_not_found');
		echo json_encode($response);
	}
}

function create_allowance($data) {

	global $smarty;

	include_once 'class.Store.php';
	include_once 'class.Deal.php';
	include_once 'class.DealCampaign.php';

	$deal=new Deal('id', $data['parent_key']);

	putenv('LC_ALL='.$store->data['Store Locale'].'.UTF-8');
	//setlocale(LC_ALL,$store->data['Store Locale'].'.UTF-8');
	bindtextdomain("inikoo", "./locales");
	textdomain("inikoo");
	
	
	if ($deal->id) {

		

		$deal_data=$data['values'];
		$deal_data['Deal Store Key']=$store->id;

		

		

		if ($deal_data['Deal Component Allowance Type']=='Clone') {


			$sql=sprintf("select * from `Deal Component Dimension` where `Deal Component Deal Key`=%d",
				$deal_data['Deal Component Allowance Target Key']);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$deal_component_data=$deal_data;
				$deal_component_data['Deal Component Allowance Type']=$row['Deal Component Allowance Type'];
				$deal_component_data['Deal Component Allowance Target']=$row['Deal Component Allowance Target'];
				$deal_component_data['Deal Component Allowance Target Key']=$row['Deal Component Allowance Target Key'];
				$deal_component_data['Deal Component Allowance Target XHTML Label']=$row['Deal Component Allowance Target XHTML Label'];
				$deal_component_data['Deal Component Allowance Description']=$row['Deal Component Allowance Description'];
				$deal_component_data['Deal Component XHTML Allowance Description Label']=$row['Deal Component XHTML Allowance Description Label'];
				$deal_component_data['Deal Component Allowance']=$row['Deal Component Allowance'];
				$deal_component_data['Deal Component Allowance Lock']=$row['Deal Component Allowance Lock'];
				$deal_component_data['Deal Component Terms Description']=$terms;
				$deal_component_data['Deal Component XHTML Terms Description Label']=$terms_label;
				$deal_component_data['Deal Component Allowance Target Type']=($no_items?'No Items':'Items');
				$deal_component_data['Deal Component Mirror Metadata']=$row['Deal Component Key'];


				$component=$deal->add_component($deal_component_data);


			}


		}
		else {
			switch ($deal_data['Deal Component Allowance Target']) {
			case 'Department':
				$department=new Department($deal_data['Deal Component Allowance Target Key']);
				$deal_data['Deal Component Allowance Target XHTML Label']=sprintf('<a href="department.php?id=%d">%s</a>',
					$department->id,
					$department->data['Product Department Code']
				);
				break;
			case 'Family':
				$family=new Family($deal_data['Deal Component Allowance Target Key']);
				$deal_data['Deal Component Allowance Target XHTML Label']=sprintf('<a href="family.php?id=%d">%s</a>',
					$family->id,
					$family->data['Product Family Code']
				);
				break;
			case 'Product':

				$product=new Product('pid', $deal_data['Deal Component Allowance Target Key']);
				$deal_data['Deal Component Allowance Target XHTML Label']=sprintf('<a href="product.php?pid=%d">%s</a>',
					$product->pid,
					$product->data['Product Code']
				);
				break;
			case 'Shipping':
				$deal_data['Deal Component Allowance Target XHTML Label']='';
				break;
			case 'Charge':
				$deal_data['Deal Component Allowance Target XHTML Label']='';
				break;
			case 'Order':
				$deal_data['Deal Component Allowance Target XHTML Label']='';
				break;

			default:
				exit('Unknown target: >'.$deal_data['Deal Component Allowance Target'].'<');
			}

			switch ($deal_data['Deal Component Allowance Type']) {
			case 'Department Percentage Off':
			case 'Family Percentage Off':
			case 'Product Percentage Off':
				$deal_data['Deal Component Allowance Type']='Percentage Off';
				$allowances=$deal_data['percentage_off'].'% off';
				$allowances_label=$deal_data['percentage_off']._('% off');
				break;

			case 'Percentage Off':
				$allowances=$deal_data['percentage_off'].'% off';
				$allowances_label=$deal_data['percentage_off']._('% off');
				break;
			case 'Get Same Free':
				$allowances=$deal_data['get_same_free'].' free';
				$allowances_label=', '.$deal_data['get_same_free'].' '._('free');
				$allowances_label=' '.sprintf(_('get %1$s free bonus'),number($deal_data['get_same_free']));

				break;
			case 'Get Cheapest Free':
				$allowances='cheapest '.$deal_data['get_same_free'].' free';
				$allowances_label=' '.sprintf(_('get %1$s free'),number($deal_data['get_same_free']));
				$deal_data['Deal Component Terms']=$deal_data['for_every_ordered'];
				$deal_data['Deal Component Allowance']=$deal_data['get_same_free'];
				break;
			case 'Free Shipping':

				$deal_data['Deal Component Allowance Type']='Get Free';
				$deal_data['Deal Component Allowance Target']='Shipping';

				$allowances='free shipping';
				$allowances_label=_('free shipping');
				break;
			case 'Free Charges':
				$deal_data['Deal Component Allowance Type']='Get Free';
				$deal_data['Deal Component Allowance Target']='Charge';
				$allowances='free charges';
				$allowances_label=_('free charges');
				break;

			case 'Clone':
				$deal->update(array('Deal Mirror Metadata'=>$deal_data['Deal Component Allowance Target Key']), 'no_history');
				break;
			case 'Amount Off':
				$deal_data['Deal Component Allowance']=$deal_data['amount_off'];
				$allowances=$deal_data['amount_off']." off";
				$allowances_label=money($deal_data['amount_off'], $store->data['Store Currency Code']).' '._('amount off');

				break;
			case 'Bonus Product From Family':
				$deal_data['Deal Component Allowance Type'] = 'Get Free';
				$deal_data['Deal Component Allowance Target'] = 'Family';
				$deal_data['Deal Component Allowance'] = '1;'.$deal_data['default_free_product_from_family'];
				$allowances = 'get one form family '.$family->data['Product Family Code'];
				$allowances_label=sprintf(_('get one form family %1$s'),$deal_data['Deal Component Allowance Target XHTML Label']);

				break;
			case 'Bonus Product':
				$deal_data['Deal Component Allowance Type']='Get Free';
				$deal_data['Deal Component Allowance Target']='Product';
				$deal_data['Deal Component Allowance'] = '1';
				$allowances = 'get one '.$product->data['Product Code'];
				$allowances_label=sprintf(_('get one %1$s'),$deal_data['Deal Component Allowance Target XHTML Label']);
				break;

			default:
				exit('Unknown Allowance: '.$deal_data['Deal Component Allowance Type']);
			}




			$deal_component_data=array(
				'Deal Component Name'=>$deal_data['Deal Name'],
				'Deal Component XHTML Name Label'=>$deal_data['Deal Name'],
				'Deal Component Terms Description'=>$terms,
				'Deal Component XHTML Terms Description Label'=>$terms_label,
				'Deal Component Allowance Description'=>$allowances,
				'Deal Component XHTML Allowance Description Label'=>$allowances_label,
				'Deal Component Public'=>'Yes',
				'Deal Component Allowance Target Type'=>($no_items?'No Items':'Items')


			);
			$deal_component_data=array_merge($deal_component_data, $deal_data);






			//print_r($deal_component_data);

			$component=$deal->add_component($deal_component_data);

		}

		$deal->update_term_allowances();
		$smarty->assign('deal', $deal);
		$new_deal_message=$smarty->fetch('ar_messages/new_deal.tpl');

		$response=array('state'=>200, 'action'=>'created', 'deal_key'=>$deal->id, 'message'=>$new_deal_message);
		echo json_encode($response);

	}else {
		$response=array('state'=>404, 'resp'=>'store_not_found');
		echo json_encode($response);
	}
}


function get_vocher_code($store_key, $count=0) {
	if ($count<3) {
		$code=generatePassword(3, 0).'-'.generatePassword(3, 0);
	}elseif ($count<10) {
		$code=generatePassword(4, 1).'-'.generatePassword(4, 1);
	}elseif ($count<100) {
		$code=generatePassword(5, 1).'-'.generatePassword(5, 1);
	}elseif ($count<200) {
		$code=generatePassword(5, 1).'-'.generatePassword(5, 1);
	}elseif ($count<300) {
		$code=generatePassword(4, 1).'-'.generatePassword(4, 1).'-'.generatePassword(4, 1);
	}elseif ($count<5000) {
		$code=generatePassword(4, 4).'-'.generatePassword(4, 1).'-'.generatePassword(4, 1);
	}else {

		return false;
	}

	$sql=sprintf("select count(*) as num from `Voucher Dimension` where `Voucher Store Key`=%d and `Voucher Code`=%s",
		$store_key,
		prepare_mysql($code)
	);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		if ($row['num']==0) {
			return $code;
		}else {
			get_vocher_code($store_key, $count++);

		}

	}else {

		return false;

	}

}

function add_voucher_to_order($data) {
	global $smarty;
	include_once 'class.Voucher.php';

	$order=new Order($data['order_key']);

	$data['voucher']=trim($data['voucher']);

	if ($data['voucher']=='') {
		$response=array('state'=>400, 'msg'=>_('Please, write the voucher code'));
		echo json_encode($response);
		return;
	}

	$voucher=new Voucher('code_store', $data['voucher'], $order->data['Order Store Key']);


	if (!$voucher->id) {
		$response=array('state'=>400, 'msg'=>_('Voucher').': <b>'.$data['voucher'].'</b> '._('can not be found'));
		echo json_encode($response);
		return;
	}

	if ($voucher->data['Voucher Subject Type']=='Customer' and $voucher->data['Voucher Subject Key Metadata']!=$order->data['Order Customer Key']) {
		$response=array('state'=>400, 'msg'=>_('Voucher').': '.$data['voucher'].' '._('can not be used with this customer'));
		echo json_encode($response);
		return;
	}

	$sql=sprintf("select `Voucher Key` from `Voucher Order Bridge` where `Order Key`=%d and `Voucher Key`=%d ", $order->id, $voucher->id);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$response=array('state'=>400, 'msg'=>_('Voucher').': '.$data['voucher'].' '._('already assigned to this order'));
		echo json_encode($response);
		return;
	}
	$deal=new Deal($voucher->data['Voucher Deal Key']>0);


	if ($voucher->data['Voucher Usage Limit per Customer']) {
		$sql=sprintf("select count(*) as num from `Voucher Order Bridge` where `Customer Key`=%d and `Voucher Key`=%d and `State`!='Cancelled'", $order->data['Order Customer Key'], $voucher->id);
		$res=mysql_query($sql);

		if ($row=mysql_fetch_assoc($res)) {
			if ($row['num']>=$voucher->data['Voucher Usage Limit per Customer']) {

				if ($voucher->data['Voucher Usage Limit per Customer']==1) {
					$response=array('state'=>400, 'msg'=>_('Voucher').': '.$data['voucher'].' '._('already have been used by the customer'));

				}else {
					$response=array('state'=>400, 'msg'=>_('Voucher').': '.$data['voucher'].' '._('usage limit by customer exceded').' ('.number($voucher->data['Voucher Usage Limit per Customer']).')');

				}

				echo json_encode($response);
				return;
			}
		}
	}

	$sql=sprintf("insert into `Voucher Order Bridge` (`Voucher Key`,`Deal Key`,`Order Key`,`Customer Key`,`Date`) values (%d,%d,%d,%d,%s)",
		$voucher->id,
		$voucher->data['Voucher Deal Key'],
		$order->id,
		$order->data['Order Customer Key'],
		prepare_mysql(gmdate('Y-m-d H:i:s'))

	);
	mysql_query($sql);
	$disconted_products=$order->get_discounted_products();

	$voucher->update_usage();
	$order->update_discounts_items();

	$order->update_totals();
	$order->update_discounts_no_items();
	$order->update_totals();
	
	$order->update_number_items();
	$order->update_number_products();

	$order->apply_payment_from_customer_account();



	$new_disconted_products=$order->get_discounted_products();
	foreach ($new_disconted_products as $key=>$value) {
		$disconted_products[$key]=$value;
	}

	$adata=array();

	if (count($disconted_products)>0) {

		$product_keys=join(',', $disconted_products);
		$sql=sprintf("select (select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key` group by  OTDB.`Order Transaction Fact Key`) as `Deal Info`,P.`Product ID`,`Product XHTML Short Description`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Quantity`,`Order Bonus Quantity` from `Order Transaction Fact` OTF   left join `Product Dimension` P on (OTF.`Product ID`=P.`Product ID`) where OTF.`Order Key`=%d and OTF.`Product Key` in (%s)",
			$order->id,
			$product_keys);


		//print $sql;
		$res = mysql_query($sql);
		$adata=array();

		while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


			if ($row['Deal Info']) {



				$deal_info='<br/><span style="font-style:italics;color:#555555;font-size:90%">'.$row['Deal Info'].($row['Order Transaction Total Discount Amount']>0?', <span style="font-weight:800">-'.money($row['Order Transaction Total Discount Amount'], $order->data['Order Currency']).'</span>':'').'</span>';


			}else {
				$deal_info='';
			}
			$qty=number($row['Order Quantity']);
			if ($row['Order Bonus Quantity']!=0) {
				if ($row['Order Quantity']!=0) {
					$qty.='<br/> +'.number($row['Order Bonus Quantity']).' '._('free');
				}else {
					$qty=number($row['Order Bonus Quantity']).' '._('free');
				}
			}

			$adata[$row['Product ID']]=array(
				'pid'=>$row['Product ID'],
				'quantity'=>$qty,
				'ordered_quantity'=>$row['Order Quantity'],
				'description'=>''.$row['Product XHTML Short Description'].$deal_info,
				'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'], $order->data['Order Currency'])
			);
		};
	}


	$updated_data=array(
		'order_items_gross'=>$order->get('Items Gross Amount'),
		'order_items_discount'=>$order->get('Items Discount Amount'),
		'order_items_net'=>$order->get('Items Net Amount'),
		'order_net'=>$order->get('Total Net Amount'),
		'order_tax'=>$order->get('Total Tax Amount'),
		'order_charges'=>$order->get('Charges Net Amount'),
		'order_credits'=>$order->get('Net Credited Amount'),
		'order_shipping'=>$order->get('Shipping Net Amount'),
		'order_total'=>$order->get('Total Amount'),
		'order_total_paid'=>$order->get('Payments Amount'),
		'order_total_to_pay'=>$order->get('To Pay Amount'),
		'order_insurance'=>$order->get('Insurance Net Amount'),
		'ordered_products_number'=>$order->get('Number Products')


	);

	$payments_data=array();
	foreach ($order->get_payment_objects('', true, true) as $payment) {
		$payments_data[$payment->id]=array(
			'date'=>$payment->get('Created Date'),
			'amount'=>$payment->get('Amount'),
			'status'=>$payment->get('Payment Transaction Status')
		);
	}

	$smarty->assign('order', $order);
	$payments_list=$smarty->fetch('order_payments_splinter.tpl');
	$vouchers_list=$smarty->fetch('order_vouchers_splinter.tpl');

	$response=array('state'=>200,
		'result'=>'updated',
		'action'=>'added',

		'order_for_collection'=>$order->data['Order For Collection'],
		'order_shipping_method'=>$order->data['Order Shipping Method'],
		'data'=>$updated_data,
		'shipping'=>money($order->new_value),
		'shipping_amount'=>$order->data['Order Shipping Net Amount'],
		'ship_to'=>$order->get('Order XHTML Ship Tos'),
		'tax_info'=>$order->get_formated_tax_info_with_operations(),
		'payments_data'=>$payments_data,
		'order_total_paid'=>$order->data['Order Payments Amount'],
		'order_total_to_pay'=>$order->data['Order To Pay Amount'],
		'payments_list'=>$payments_list,
		'vouchers_list'=>$vouchers_list,
		'discount_data'=>$adata,
		'discounts'=>($order->data['Order Items Discount Amount']!=0?true:false),
		'charges'=>($order->data['Order Charges Net Amount']!=0?true:false),
		'voucher_key'=>$voucher->id,
		'voucher_code'=>$voucher->data['Voucher Code'],
		'deal_code'=>$deal->data['Deal Code'],
		'deal_name'=>$deal->data['Deal Name'],
		'deal_description'=>$deal->data['Deal Description']

	);



	echo json_encode($response);

}

function remove_voucher_from_order($data) {
	global $smarty;
	include_once 'class.Voucher.php';

	$order=new Order($data['order_key']);

	$voucher=new Voucher($data['voucher_key']);
	$deal=new Deal($voucher->data['Voucher Deal Key']);

	$disconted_products=$order->get_discounted_products();

	$sql=sprintf("delete from `Voucher Order Bridge` where `Order Key`=%d and `Voucher Key`=%d",
		$data['order_key'],
		$data['voucher_key']
	);

	mysql_query($sql);

	$voucher->update_usage();
	$order->update_discounts_items();

		$order->update_totals();

	$order->update_discounts_no_items();
		$order->update_totals();

	$order->update_number_items();
	$order->update_number_products();

	$order->apply_payment_from_customer_account();

	$new_disconted_products=$order->get_discounted_products();
	foreach ($new_disconted_products as $key=>$value) {
		$disconted_products[$key]=$value;
	}

	$adata=array();

	if (count($disconted_products)>0) {

		$product_keys=join(',', $disconted_products);
		$sql=sprintf("select (select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key` group by  OTDB.`Order Transaction Fact Key`) as `Deal Info`,P.`Product ID`,`Product XHTML Short Description`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Quantity`,`Order Bonus Quantity` from `Order Transaction Fact` OTF   left join `Product Dimension` P on (OTF.`Product ID`=P.`Product ID`) where OTF.`Order Key`=%d and OTF.`Product Key` in (%s)",
			$order->id,
			$product_keys);


		//print $sql;
		$res = mysql_query($sql);
		$adata=array();

		while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


			if ($row['Deal Info']) {



				$deal_info='<br/><span style="font-style:italics;color:#555555;font-size:90%">'.$row['Deal Info'].($row['Order Transaction Total Discount Amount']>0?', <span style="font-weight:800">-'.money($row['Order Transaction Total Discount Amount'], $order->data['Order Currency']).'</span>':'').'</span>';


			}else {
				$deal_info='';
			}
			$qty=number($row['Order Quantity']);
			if ($row['Order Bonus Quantity']!=0) {
				if ($row['Order Quantity']!=0) {
					$qty.='<br/> +'.number($row['Order Bonus Quantity']).' '._('free');
				}else {
					$qty=number($row['Order Bonus Quantity']).' '._('free');
				}
			}

			$adata[$row['Product ID']]=array(
				'pid'=>$row['Product ID'],
				'quantity'=>$qty,
				'ordered_quantity'=>$row['Order Quantity'],
				'description'=>''.$row['Product XHTML Short Description'].$deal_info,
				'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'], $order->data['Order Currency'])
			);
		};
	}


	$updated_data=array(
		'order_items_gross'=>$order->get('Items Gross Amount'),
		'order_items_discount'=>$order->get('Items Discount Amount'),
		'order_items_net'=>$order->get('Items Net Amount'),
		'order_net'=>$order->get('Total Net Amount'),
		'order_tax'=>$order->get('Total Tax Amount'),
		'order_charges'=>$order->get('Charges Net Amount'),
		'order_credits'=>$order->get('Net Credited Amount'),
		'order_shipping'=>$order->get('Shipping Net Amount'),
		'order_total'=>$order->get('Total Amount'),
		'order_total_paid'=>$order->get('Payments Amount'),
		'order_total_to_pay'=>$order->get('To Pay Amount'),
		'order_insurance'=>$order->get('Insurance Net Amount'),
		'ordered_products_number'=>$order->get('Number Products'),


	);

	$payments_data=array();
	foreach ($order->get_payment_objects('', true, true) as $payment) {
		$payments_data[$payment->id]=array(
			'date'=>$payment->get('Created Date'),
			'amount'=>$payment->get('Amount'),
			'status'=>$payment->get('Payment Transaction Status')
		);
	}

	$smarty->assign('order', $order);
	$payments_list=$smarty->fetch('order_payments_splinter.tpl');
	$vouchers_list=$smarty->fetch('order_vouchers_splinter.tpl');

	$response=array('state'=>200,
		'result'=>'updated',
		'action'=>'removed',

		'order_for_collection'=>$order->data['Order For Collection'],
		'order_shipping_method'=>$order->data['Order Shipping Method'],
		'data'=>$updated_data,
		'shipping'=>money($order->new_value),
		'shipping_amount'=>$order->data['Order Shipping Net Amount'],
		'ship_to'=>$order->get('Order XHTML Ship Tos'),
		'tax_info'=>$order->get_formated_tax_info_with_operations(),
		'payments_data'=>$payments_data,
		'order_total_paid'=>$order->data['Order Payments Amount'],
		'order_total_to_pay'=>$order->data['Order To Pay Amount'],
		'payments_list'=>$payments_list,
		'vouchers_list'=>$vouchers_list,
		'discount_data'=>$adata,
		'discounts'=>($order->data['Order Items Discount Amount']!=0?true:false),
		'charges'=>($order->data['Order Charges Net Amount']!=0?true:false),
		'voucher_key'=>$voucher->id,
		'voucher_code'=>$voucher->data['Voucher Code'],
		'deal_code'=>$deal->data['Deal Code'],
		'deal_name'=>$deal->data['Deal Name'],
		'deal_description'=>$deal->data['Deal Description'],
		'voucher_key'=>$voucher->id, 'order_key'=>$order->id,

	);



	echo json_encode($response);


}

function delete_campaign($data) {

	require_once 'class.DealCampaign.php';

	$campaign=new DealCampaign($data['id']);
	$campaign->delete();


	if (!$campaign->error) {
		$response= array('state'=>200
		);

	} else {
		$response= array('state'=>400, 'msg'=>$deal_metadata->msg);
	}
	echo json_encode($response);



}

function list_deals() {



	if ( isset($_REQUEST['parent']))
		$parent= $_REQUEST['parent'];
	else {
		exit("no parent arg");
	}

	if ( isset($_REQUEST['parent_key']))
		$parent_key= $_REQUEST['parent_key'];
	else {
		exit("no parent key arg");
	}


	if ( isset($_REQUEST['referrer']))
		$referrer= $_REQUEST['referrer'];
	else {
		$referrer='marketing';
	}


	$conf=$_SESSION['state'][$parent]['edit_offers'];



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


	$_SESSION['state'][$parent]['edit_offers']['order']=$order;
	$_SESSION['state'][$parent]['edit_offers']['order_dir']=$order_direction;
	$_SESSION['state'][$parent]['edit_offers']['nr']=$number_results;
	$_SESSION['state'][$parent]['edit_offers']['sf']=$start_from;
	$_SESSION['state'][$parent]['edit_offers']['f_field']=$f_field;
	$_SESSION['state'][$parent]['edit_offers']['f_value']=$f_value;





	if ($parent=='store') {
		$where=sprintf("where  `Deal Trigger`='Order' and  `Deal Store Key`=%d     ",$parent_key);



	}elseif ($parent=='campaign') {
		$where=sprintf("where  `Deal Campaign Key`=%d     ",$parent_key);
	}
	elseif ($parent=='department')
		$where=sprintf("where    `Deal Trigger`='Department' and  `Deal Trigger Key`=%d     ",$parent_key);
	elseif ($parent=='family')
		$where=sprintf("where    `Deal Trigger`='Family' and  `Deal Trigger Key`=%d   ",$parent_key);
	elseif ($parent=='product')
		$where=sprintf("where    `Deal Trigger`='Product' and  `Deal Trigger Key`=%d   ",$parent_key);
	elseif ($parent=='customer')
		$where=sprintf("where  `Deal Trigger`='Customer' and  `Deal Trigger Key`=%d   ",$parent_key);
	elseif ($parent=='customer_categories')
		$where=sprintf("where    `Deal Trigger`='Customer Category' and  `Deal Trigger Key`=%d   ",$parent_key);
	elseif ($parent=='customers_list')
		$where=sprintf("where    `Deal Trigger`='Customer List' and  `Deal Trigger Key`=%d   ",$parent_key);
	elseif ($parent=='marketing')
		$where=sprintf("where   `Deal Store Key`=%d     ",$parent_key);
	else
		$where=sprintf("where false ");;





	// print "$parent $where";
	$filter_msg='';
	$wheref='';
	if ($f_field=='description' and $f_value!='')
		$wheref.=" and ( `Deal Terms Description` like '".addslashes($f_value)."%' or `Deal Allowance Description` like '".addslashes($f_value)."%'  )   ";
	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Deal Name` like '".addslashes($f_value)."%'";
	elseif ($f_field=='code' and $f_value!='')
		$wheref.=" and  `Deal Code` like '%".addslashes($f_value)."%'";







	$sql="select count( distinct `Deal Key`) as total from `Deal Dimension` left join `Deal Component Dimension` on (`Deal Component Deal Key`=`Deal Key`)  $where $wheref";
	//print $sql;
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count( distinct `Deal Key`) as total_without_filters from `Deal Dimension` left join `Deal Component Dimension` on (`Deal Component Deal Key`=`Deal Key`)  $where ";


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


	$rtext=number($total_records)." ".ngettext('offer','offers',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records>=10)
		$rtext_rpp='('._("Showing all").')';
	else
		$rtext_rpp='';






	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this name ")." <b>".$f_value."*</b> ";
			break;
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this code ")." <b>*".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with name like')." <b>".$f_value."*</b>";
			break;
		case('code'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with code like')." <b>*".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;

	if ($order=='code')
		$order='`Deal Code`';
	elseif ($order=='description')
		$order='`Deal Name`,`Deal Description`';
	elseif ($order=='orders')
		$order='`Deal Total Acc Used Orders`';
	elseif ($order=='customers')
		$order='`Deal Total Acc Used Customers`';
	elseif ($order=='store')
		$order='`Store Code`';
	elseif ($order=='state')
		$order='`Deal Status`';
	else
		$order='`Deal Name`';


	$sql="select *  from `Deal Dimension` left join `Deal Component Dimension` on (`Deal Component Deal Key`=`Deal Key`)  left join `Store Dimension` S on (S.`Store Key`=`Deal Store Key`)  $where  $wheref  group by `Deal Key` order by $order $order_direction limit $start_from,$number_results    ";
	//print $sql;
	$res = mysql_query($sql);

	$total=mysql_num_rows($res);
	$adata=array();
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		$name=sprintf('<a href="deal.php?id=%d&referrer=%s">%s</a>',$row['Deal Key'],$referrer,$row['Deal Name']);
		$code=sprintf('<a href="deal.php?id=%d&referrer=%s">%s</a>',$row['Deal Key'],$referrer,$row['Deal Code']);

		$orders=number($row['Deal Total Acc Used Orders']);
		$customers=number($row['Deal Total Acc Used Customers']);


		switch ($row['Deal Status']) {
		case 'Waiting':
			$state=sprintf('<span id="deal_state_%d"><img src="art/icons/bullet_orange.png" alt="%s" title="%s"></span>',$row['Deal Key'],_('Waiting'),_('Offer waiting'));
			break;
		case 'Active':
			$state=sprintf('<span id="deal_state_%d"><img src="art/icons/bullet_green.png" alt="%s" title="%s"></span>',$row['Deal Key'],_('Active'),_('Offer active'));
			break;
		case 'Suspended':
			$state=sprintf('<span id="deal_state_%d"><img src="art/icons/bullet_red.png" alt="%s" title="%s"></span>',$row['Deal Key'],_('Suspended'),_('Offer suspended'));
			break;
		case 'Finish':
			$state=sprintf('<span id="deal_state_%d"><img src="art/icons/bullet_grey.png" alt="%s" title="%s"></span>',$row['Deal Key'],_('Finished'),_('Offer finished'));
			break;
		default:
			$state=sprintf('<span id="deal_state_%d">%s</span>',$row['Deal Key'],$row['Deal Status']);
		}



		$duration='';
		if ($row['Deal Expiration Date']=='' and $row['Deal Begin Date']=='') {
			$duration=_('Permanent');
		}else {

			if ($row['Deal Begin Date']!='') {
				$duration=strftime("%x", strtotime($row['Deal Begin Date']." +00:00"));

			}
			$duration.=' - ';
			if ($row['Deal Expiration Date']!='') {
				$duration.=strftime("%x", strtotime($row['Deal Expiration Date']." +00:00"));

			}else {
				$duration.=_('Present');
			}

		}

		switch ($row['Deal Status']) {
		case 'Active':
		case 'Waiting':
			$edit_status=sprintf('<div id="deal_state_edit_%d" class="buttons small"><button class="negative" onClick="edit_deal_state(%d,\'Suspended\')">%s</button></div>',
				$row['Deal Key'],
				$row['Deal Key'],
				_('Suspend')
			);
			break;
		case 'Suspended':
			$edit_status=sprintf('<div  id="deal_state_edit_%d" class="buttons small"><button class="positive" onClick="edit_deal_state(%d,\'Active\')">%s</button></div>',
				$row['Deal Key'],
				$row['Deal Key'],
				_('Activate'));
			break;
		default:
			$edit_status=$row['Deal Status'];
		}



		$store=sprintf("<a href='marketing.php?store=%d'>%s</a>",$row['Deal Store Key'],$row['Store Code']);


		$adata[]=array(
			'store'=>$store,
			'code'=>$code,
			'name'=>$name,
			'description'=>sprintf('<b title="%s">%s</b> %s<br/>%s',$row['Deal Description'],$row['Deal Code'],$row['Deal Name'],$row['Deal Term Allowances']),
			'orders'=>$orders,
			'customers'=>$customers,
			'duration'=>$duration,
			'state'=>$state,
			'edit_status'=>$edit_status,
			'term_allowances_label'=>$row['Deal Component XHTML Terms Description Label']


		);
	}
	mysql_free_result($res);



	// if($total<$number_results)
	//  $rtext=$total.' '.ngettext('store','stores',$total);
	//else
	//  $rtext='';

	//   $total_records=ceil($total_records/$number_results)+$total_records;

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


?>
