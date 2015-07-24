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
case('delete_deal'):
	$data=prepare_values($_REQUEST, array(
			'deal_key'=>array('type'=>'key')
		));
	delete_deal($data);
	break;
case('update_badge'):
	$data=prepare_values($_REQUEST, array(
			'values'=>array('type'=>'json array')
		));
	update_badge($data);
	break;
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
	create_allowance($data);
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
case ('edit_deal_component_field'):

	$data=prepare_values($_REQUEST, array(
			'key'=>array('type'=>'string'),
			//'okey'=>array('type'=>'string'),
			'newvalue'=>array('type'=>'string'),
			'deal_component_key'=>array('type'=>'key'),
		));

	edit_deal_component_field($data);
	break;
case('update_deal_status'):
	$data=prepare_values($_REQUEST, array(
			'value'=>array('type'=>'string'),
			'deal_key'=>array('type'=>'key'),
		));

	update_deal_status($data);
	break;

case('update_deal_component_status'):
	$data=prepare_values($_REQUEST, array(
			'value'=>array('type'=>'string'),
			'deal_component_key'=>array('type'=>'key'),
		));

	update_deal_component_status($data);
	break;
case('update_deal_component_finish'):
	$data=prepare_values($_REQUEST, array(
			'deal_key'=>array('type'=>'key'),
			'deal_component_key'=>array('type'=>'key'),
		));

	update_deal_component_finish($data);
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


	$elements=$conf['elements'];

	if (isset( $_REQUEST['deal_component_status_elements_Active'])) {
		$elements['Active']=$_REQUEST['deal_component_status_elements_Active'];
	}
	if (isset( $_REQUEST['deal_component_status_elements_Waiting'])) {
		$elements['Waiting']=$_REQUEST['deal_component_status_elements_Waiting'];
	}
	if (isset( $_REQUEST['deal_component_status_elements_Suspended'])) {
		$elements['Suspended']=$_REQUEST['deal_component_status_elements_Suspended'];
	}
	if (isset( $_REQUEST['deal_component_status_elements_Finish'])) {
		$elements['Finish']=$_REQUEST['deal_component_status_elements_Finish'];
	}


	$_SESSION['state']['deal']['edit_components']['order']=$order;
	$_SESSION['state']['deal']['edit_components']['order_dir']=$order_direction;
	$_SESSION['state']['deal']['edit_components']['nr']=$number_results;
	$_SESSION['state']['deal']['edit_components']['sf']=$start_from;
	$_SESSION['state']['deal']['edit_components']['f_field']=$f_field;
	$_SESSION['state']['deal']['edit_components']['f_value']=$f_value;
	$_SESSION['state']['deal']['edit_components']['elements']=$elements;





	if ($parent=='deal')
		$where=sprintf("where `Deal Component Deal Key`=%d   ", $parent_key);
	else
		$where=sprintf("where false ");;


	$_elements='';
	$count_elements=0;
	foreach ($elements as $_key=>$_value) {
		if ($_value) {
			$count_elements++;
			$_elements.=','.prepare_mysql($_key);

		}
	}
	$_elements=preg_replace('/^\,/','',$_elements);
	if ($_elements=='') {
		$where.=' and false' ;
	} elseif ($count_elements<4) {
		$where.=' and `Deal Component Status` in ('.$_elements.')' ;
	}




	
	$filter_msg='';
	$wheref='';
	if ($f_field=='allowances' and $f_value!='')
		$wheref.=" and  `Deal Component Allowance Plain Description` regexp '[[:<:]]".addslashes($f_value)."' ";






	$sql="select count(*) as total from `Deal Component Dimension` $where $wheref";
	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(*) as total_without_filters from `Deal Component Dimension` $where ";


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

		case('allowances'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any offer with allowances like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {

		case('allowances'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('offer with allowances like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';
	$_dir=$order_direction;
	$_order=$order;

	if ($order=='orders')
		$order='`Deal Component Total Acc Used Orders`';
	elseif ($order=='customers')
		$order='`Deal Component Total Acc Used Customers`';
	elseif ($order=='allowances')
		$order='`Deal Component Allowance Plain Description`';
	elseif ($order=='label')
		$order='`Deal Component XHTML Allowance Description Label`';
	elseif ($order=='state')
		$order='`Deal Component Status`';





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
			$edit_status=sprintf('<div id="component_status_edit_%d" class="buttons small"> <button style="margin:0px" class="negative" onClick="edit_component_status(%d,\'Suspended\')"><img id="component_status_edit_wait_%d" src="art/icons/stop.png"> %s</button></div>',
				$row['Deal Component Key'],
				$row['Deal Component Key'],
				$row['Deal Component Key'],
				_('Suspend')
			);
			break;
		case 'Suspended':
			$edit_status=sprintf('<div  id="component_status_edit_%d" class="buttons small"><button style="margin:0px" class="positive" onClick="edit_component_status(%d,\'Active\')"><img id="component_status_edit_wait_%d" src="art/icons/tick.png"> %s</button></div>',
				$row['Deal Component Key'],
				$row['Deal Component Key'],
				$row['Deal Component Key'],
				_('Activate'));
			break;
		default:
			$edit_status='';
		}


		switch ($row['Deal Component Status']) {
		case 'Finish':
			$edit_dates='';
			break;

		default:
			$edit_dates=sprintf('<div  id="component_finish_edit_%d" class="buttons small right"><button  style="margin:0px" onClick="edit_component_finish(%d)"><img id="component_finish_edit_wait_%d" src="art/icons/clock.png"> %s</button></div>',
				$row['Deal Component Key'],
				$row['Deal Component Key'],
				$row['Deal Component Key'],
				_('Finish now'));
		}








		switch ($row['Deal Component Status']) {
		case 'Waiting':
			$status=sprintf('<span id="component_status_%d"><img src="art/icons/bullet_orange.png" alt="%s" title="%s"></span>', $row['Deal Component Key'], _('Waiting'), _('Offer waiting'));
			break;
		case 'Active':
			$status=sprintf('<span id="component_status_%d"><img src="art/icons/bullet_green.png" alt="%s" title="%s"></span>', $row['Deal Component Key'], _('Active'), _('Offer active'));
			break;
		case 'Suspended':
			$status=sprintf('<span id="component_status_%d"><img src="art/icons/bullet_red.png" alt="%s" title="%s"></span>', $row['Deal Component Key'], _('Suspended'), _('Offer suspended'));
			break;
		case 'Finish':
			$status=sprintf('<span id="component_status_%d"><img src="art/icons/bullet_grey.png" alt="%s" title="%s"></span>', $row['Deal Component Key'], _('Finished'), _('Offer finished'));
			break;

			$status=$row['Deal Status'];
		}




		$adata[]=array(
			'deal_component_key'=>$row['Deal Component Key'],
			'terms'=>$row['Deal Component Terms Description'],
			'allowances'=>$row['Deal Component Allowance XHTML Description'],
			'label'=>$row['Deal Component XHTML Allowance Description Label'],

			'target'=>$row['Deal Component Allowance Target'],

			'duration'=>$duration,
			'edit_status'=>$edit_status,
			'edit_dates'=>$edit_dates,
			'status'=>$status

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




function delete_deal($data) {
	require_once 'class.DealCampaign.php';

	global $editor;

	$deal=new Deal($data['deal_key']);
	$deal->editor=$editor;

	$deal->delete();
	if (!$deal->error) {
		$response= array(
			'state'=>200,
			'campaign_key'=>$deal->data['Deal Campaign Key']
		);

	} else {
		$response= array('state'=>400, 'msg'=>$deal->msg);
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




function update_deal_component_status($data) {



	require_once 'class.DealCampaign.php';

	$deal_component=new DealComponent($data['deal_component_key']);
	$deal_component->update_status($data['value']);


	if ($deal_component->error) {
		$response=array(
			'state'=>400,
			'msg'=>$deal_component->msg
		);
	}
	else {



		switch ($deal_component->data['Deal Component Status']) {
		case 'Active':
		case 'Waiting':
			$edit_status=sprintf('<div id="component_state_edit_%d" class="buttons small"><button class="negative" onClick="edit_component_state(%d,\'Suspended\')"><img id="component_state_edit_wait_%d" src="art/icons/stop.png">  %s</button></div>',
				$deal_component->data['Deal Component Key'],
				$deal_component->data['Deal Component Key'],
				$deal_component->data['Deal Component Key'],
				_('Suspend')
			);
			break;
		case 'Suspended':
			$edit_status=sprintf('<div  id="component_state_edit_%d" class="buttons small"><button class="positive" onClick="edit_component_state(%d,\'Active\')"><img id="component_state_edit_wait_%d" src="art/icons/tick.png">  %s</button></div>',
				$deal_component->data['Deal Component Key'],
				$deal_component->data['Deal Component Key'],
				$deal_component->data['Deal Component Key'],
				_('Activate'));
			break;
		default:
			$edit_status=$deal_component->data['Deal Component Status'];
		}


		switch ($deal_component->data['Deal Component Status']) {
		case 'Waiting':
			$state=sprintf('<span id="component_state_%d"><img src="art/icons/bullet_orange.png" alt="%s" title="%s"></span>', $deal_component->data['Deal Component Key'], _('Waiting'), _('Offer waiting'));
			break;
		case 'Active':
			$state=sprintf('<span id="component_state_%d"><img src="art/icons/bullet_green.png" alt="%s" title="%s"></span>', $deal_component->data['Deal Component Key'], _('Active'), _('Offer active'));
			break;
		case 'Suspended':
			$state=sprintf('<span id="component_state_%d"><img src="art/icons/bullet_red.png" alt="%s" title="%s"></span>', $deal_component->data['Deal Component Key'], _('Suspended'), _('Offer suspended'));
			break;
		case 'Finish':
			$state=sprintf('<span id="component_state_%d"><img src="art/icons/bullet_grey.png" alt="%s" title="%s"></span>', $deal_component->data['Deal Component Key'], _('Finished'), _('Offer finished'));
			break;
		default:
			$state=sprintf('<span id="component_state_%d"></span>', $row['Deal Component Key']);

			$state=$row['Deal Status'];
		}


		switch ($deal_component->data['Deal Component Allowance Target']) {
		case 'Family':
			$sql=sprintf("select `Page Key` from `Page Store Dimension` where `Page Store Section`='Family Catalogue' and `Page Parent Key`=%d  ",
				$deal_component->data['Deal Component Allowance Target Key']
			);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
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
			'key'=>$deal_component->id,
			'status'=>$deal_component->get_xhtml_status(),
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
			'new_value'=>$deal->data['Deal Status'],
			'button_edit_status'=>$edit_status,
			'status_icon'=>$state

		);
	}
	echo json_encode($response);
}



function update_deal_component_finish($data) {


	require_once 'class.Deal.php';


	$deal=new Deal($data['deal_key']);

	$number_components=$deal->get_number_no_finished_components();

	if ($number_components==0) {
		$response=array(
			'state'=>400,
			'msg'=>'nothing to do'
		);


	}elseif ($number_components==1) {

		$deal->update(array('Deal Expiration Date'=>gmdate('Y-m-d H:i:s')));


		$response= array('state'=>200,
			'newvalue'=>$deal->get_to_date(),

			'deal_status'=>$deal->data['Deal Status'],
		);

	}else {

		$deal_compoment=new DealComponent($data['deal_component_key']);
		$deal_compoment->update(array('Deal Component Expiration Date'=>gmdate('Y-m-d H:i:s')));

		switch ($deal_compoment->data['Deal Component Status']) {
		case 'Waiting':
			$status=sprintf('<img src="art/icons/bullet_orange.png" alt="%s" title="%s">', $deal_compoment->data['Deal Component Key'], _('Waiting'), _('Offer waiting'));
			break;
		case 'Active':
			$status=sprintf('<img src="art/icons/bullet_green.png" alt="%s" title="%s">', $deal_compoment->data['Deal Component Key'], _('Active'), _('Offer active'));
			break;
		case 'Suspended':
			$status=sprintf('<img src="art/icons/bullet_red.png" alt="%s" title="%s">', $deal_compoment->data['Deal Component Key'], _('Suspended'), _('Offer suspended'));
			break;
		case 'Finish':
			$status=sprintf('<img src="art/icons/bullet_grey.png" alt="%s" title="%s">', $deal_compoment->data['Deal Component Key'], _('Finished'), _('Offer finished'));
			break;

			$status=$row['Deal Status'];
		}

		$response= array('state'=>200,
			'deal_component_status'=>$deal_compoment->data['Deal Component Status'],
			'deal_component_status_icon'=>$status,
			'deal_component_key'=>$deal_compoment->id,
			'deal_status'=>$deal->data['Deal Status'],
		);


	}

	echo json_encode($response);

}





function edit_deal_component_field($data) {


	require_once 'class.DealComponent.php';


	$deal_compoment=new DealComponent($data['deal_component_key']);



	$translate_fields=array('label'=>'Deal Component XHTML Allowance Description Label');

	if (isset($data['key'],$translate_fields)) {
		$key=$translate_fields[$data['key']];
	}else {
		$key=$data['key'].'c';
	}

	$deal_compoment->update(array($key=>$data['newvalue']));

	if (!$deal_compoment->error) {



		$new_value=$deal_compoment->new_value;
		$response= array('state'=>200,
			'updated'=>$deal_compoment->updated,
			'newvalue'=>$new_value,
			'key'=>$data['key'],

		);

	} else {
		$response= array('state'=>400, 'msg'=>$deal_compoment->msg);
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
			'deal_status'=>$deal->data['Deal Status'],
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

	//print_r($data);
	//exit;

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

		if (is_numeric($data['values']['Deal Campaign Key']) and $data['values']['Deal Campaign Key'] ) {


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

		}
		else {

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



		$voucher_key='';

		switch ($deal_data['Deal Terms Type']) {
		case 'Department Quantity Ordered':
		case 'Family Quantity Ordered':
		case 'Product Quantity Ordered':
			$terms='order '.$deal_data['if_order_more'];
			$terms_label=_('Buy').' '.$deal_data['if_order_more'];


			//$deal_data['Deal Component Allowance Target']=$deal_data['Deal Trigger'];
			//$deal_data['Deal Component Allowance Target Key']=$deal_data['Deal Trigger Key'];
			$deal_data['Deal Component Terms']=$deal_data['if_order_more'];


			if (in_array($deal_data['Deal Trigger'],array('Customer','Customer Category','Customer List'))) {

				switch ($deal_data['Deal Component Allowance Target']) {
				case 'Department':
					$department=new Department($deal_data['Deal Component Allowance Target Key']);
					$deal_data['Deal Trigger XHTML Label']=sprintf('<a href="department.php?id=%d">%s</a>',
						$department->id,
						$department->data['Product Department Code']
					).', '._('exclusive for ').' '.$deal_data['Deal Trigger XHTML Label'];
					break;
				case 'Family':
					$family=new Family($deal_data['Deal Component Allowance Target Key']);
					$deal_data['Deal Trigger XHTML Label']=sprintf('<a href="family.php?id=%d">%s</a>',
						$family->id,
						$family->data['Product Family Code']
					).', '._('exclusive for ').' '.$deal_data['Deal Trigger XHTML Label'];
					break;
				case 'Product':

					$product=new Product('pid', $deal_data['Deal Component Allowance Target Key']);
					$deal_data['Deal Trigger XHTML Label']=sprintf('<a href="product.php?pid=%d">%s</a>',
						$product->pid,
						$product->data['Product Code']
					).', '._('exclusive for ').' '.$deal_data['Deal Trigger XHTML Label'];
					break;



				}

			}


			break;
		case 'Department For Every Quantity Any Product Ordered':
		case 'Family For Every Quantity Any Product Ordered':

			$terms='for every '.$deal_data['for_every_ordered'];
			//$terms_label=_('For every').' '.number($deal_data['for_every_ordered']).' '._('you buy');
			$terms_label=sprintf(_('buy %1$s'),number($deal_data['for_every_ordered']));

			//$deal_data['Deal Component Allowance Target']=$deal_data['Deal Trigger'];
			//$deal_data['Deal Component Allowance Target Key']=$deal_data['Deal Trigger Key'];
			$deal_data['Deal Component Terms']=$deal_data['for_every_ordered'];

			break;

		case 'Department For Every Quantity Ordered':
		case 'Family For Every Quantity Ordered':
		case 'Product For Every Quantity Ordered':
			$terms='for every '.$deal_data['for_every_ordered'];
			$terms_label=sprintf(_('For every %1$s you buy'),number($deal_data['for_every_ordered']));
			$deal_data['Deal Component Terms']=$deal_data['for_every_ordered'];
			//$deal_data['Deal Component Allowance Target']=$deal_data['Deal Trigger'];
			//$deal_data['Deal Component Allowance Target Key']=$deal_data['Deal Trigger Key'];

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
				'Voucher Deal Key'=>0,
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
				'Voucher Deal Key'=>0,
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
				'Voucher Deal Key'=>0,
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
				'Voucher Deal Key'=>0,
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
			$deal_data['Deal Component Terms']='';
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

		if ($deal_data['Deal Component Allowance Type']=='Get Cheapest Free') {
			$deal_data['Deal Component Terms']=$deal_data['for_every_ordered'];

		}




		$deal_data['Deal Terms Description']=$terms;
		$deal_data['Deal XHTML Terms Description Label']=$terms_label;
		$deal_data['Deal Terms']=$deal_data['Deal Component Terms'];

		if ($deal_data['Deal Component Allowance Type']=='Clone') {
			$deal_data['Deal Terms Description']=$terms;
			$deal_data['Deal Mirror Key']=$deal_data['Deal Component Allowance Target Key'];
		}


		$deal=$campaign->add_deal($deal_data);
		if (!$deal->new) {

			$msg=sprintf('%s <a href="deal.php?id=%d">%s</a>',
				_('Another deal already has this name'),
				$deal->data['Deal Key'],
				$deal->data['Deal Name']
			);

			$response=array(
				'state'=>404,
				'resp'=>'deal_found',
				'msg'=>$msg
			);
			echo json_encode($response);
			exit;

		}

		$deal->update(array('Deal Voucher Key'=>$voucher_key),'no_history');

		if ($voucher_key) {
			$voucher->update(array('Voucher Deal Key'=>$deal->id),'no_history');

		}

		if ($deal_data['Deal Component Allowance Type']=='Clone') {


			$sql=sprintf("select * from `Deal Component Dimension` where `Deal Component Deal Key`=%d  and `Deal Component Status`!='Finish' ",
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
				$deal_component_data['Deal Component Allowance Target Type']=($no_items?'No Items':'Items');
				$deal_component_data['Deal Component Mirror Key']=$row['Deal Component Key'];
				$deal_component_data['Deal Component Status']=$row['Deal Component Status'];

				if ($row['Deal Component Status']=='Waiting') {
					$deal_component_data['Deal Component Begin Date']=$row['Deal Component Begin Date'];

				}


				$component=$deal->add_component($deal_component_data);

				$deal->update(array('Deal Mirror Key'=>$deal_data['Deal Component Allowance Target Key']),'no_history');


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
				'Deal Component Terms Description'=>$terms,
				'Deal Component Allowance Description'=>$allowances,
				'Deal Component XHTML Allowance Description Label'=>$allowances_label,
				'Deal Component Public'=>'Yes',
				'Deal Component Allowance Target Type'=>($no_items?'No Items':'Items')


			);
			$deal_component_data=array_merge($deal_component_data, $deal_data);





			//print_r($deal_component_data);

			$component=$deal->add_component($deal_component_data);

		}


		$deal->update(array('Deal Voucher Key'=>$voucher_key),'no_history');

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


	$store=new Store($deal->data['Deal Store Key']);

	putenv('LC_ALL='.$store->data['Store Locale'].'.UTF-8');
	//setlocale(LC_ALL,$store->data['Store Locale'].'.UTF-8');
	bindtextdomain("inikoo", "./locales");
	textdomain("inikoo");


	if ($deal->id) {



		$deal_data=$data['values'];
		$deal_data['Deal Store Key']=$store->id;




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
			$deal_data['Deal Component Allowance'] =$deal_data['percentage_off']/100;
			break;

		case 'Percentage Off':
			$allowances=$deal_data['percentage_off'].'% off';
			$allowances_label=$deal_data['percentage_off']._('% off');
			$deal_data['Deal Component Allowance'] =$deal_data['percentage_off']/100;

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

			'Deal Terms Lock'=>'Yes',
			'Deal Component Allowance Description'=>$allowances,
			'Deal Component XHTML Allowance Description Label'=>$allowances_label,
			'Deal Component Public'=>'Yes',
			'Deal Component Begin Date'=>gmdate('Y-m-d H:i:s'),

		);
		$deal_component_data=array_merge($deal_component_data, $deal_data);






		//print_r($deal_component_data);

		$component=$deal->add_component($deal_component_data);



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
	global $smarty,$editor;
	include_once 'class.Voucher.php';

	$order=new Order($data['order_key']);
	$order->editor=$editor;
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
	$voucher_link=sprintf('<a href="deal.php?id=%d">%s</a>',$deal->id,$voucher->data['Voucher Code']);
	$history_data=array(
		'History Abstract'=>sprintf(_('Voucher %s added to order'),$voucher_link),
		'History Details'=>'',
	);
	$order->add_subject_history($history_data);


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
		'order_amount_off'=>$order->get('Deal Amount Off'),
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
	$smarty->assign('modify_voucher', true);


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
		'deal_name'=>$deal->data['Deal Name'],
		'deal_description'=>$deal->data['Deal Description'],
		'amount_off'=>$order->data['Order Deal Amount Off']

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
		'order_amount_off'=>$order->get('Deal Amount Off')


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
	$smarty->assign('modify_voucher', true);

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
		'deal_name'=>$deal->data['Deal Name'],
		'deal_description'=>$deal->data['Deal Description'],
		'voucher_key'=>$voucher->id, 'order_key'=>$order->id,
		'amount_off'=>floatval($order->data['Order Deal Amount Off'])


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


	if ($order=='description')
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


		$go=sprintf('<a href="edit_deal.php?id=%d&referrer=%s"><img src="art/external_link.gif" style="position:relative;top:1px"></a>',$row['Deal Key'],$referrer);

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
			'go'=>$go,
			'store'=>$store,
			'name'=>$row['Deal Name'],
			'description'=>sprintf('<span title="%s">%s</span><br/>%s',$row['Deal Description'],$row['Deal Name'],$row['Deal Term Allowances']),
			'orders'=>$orders,
			'customers'=>$customers,
			'duration'=>$duration,
			'state'=>$state,
			'edit_status'=>$edit_status,
			'term_allowances_label'=>$row['Deal XHTML Terms Description Label']


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

function update_badge($data) {
	global $editor;


	$deal=new Deal($data['values']['deal_key']);
	if ($deal->id) {
		$deal->editor=$editor;
		$deal->update(
			array(
				'Deal Label'=>$data['values']['label'],
				'Deal XHTML Terms Description Label'=>$data['values']['terms']

			)
		);

		if ($data['values']['deal_component_key']) {
			$deal_component=new DealComponent($data['values']['deal_component_key']);
			$deal_component->editor=$editor;
			if ($deal_component->id) {
				$deal_component->update(
					array(
						'Deal Component XHTML Allowance Description Label'=>$data['values']['allowances']

					)
				);
				$allowances=$deal_component->data['Deal Component XHTML Allowance Description Label'];
			}else {
				$allowances='';
			}

		}else {
			$allowances='';
		}

		$response=array('state'=>200,
			'label'=>$deal->data['Deal Label'],
			'terms'=>$deal->data['Deal XHTML Terms Description Label'],
			'allowances'=>$allowances,
			'deal_key'=>$deal->id

		);
	}else {
		$response=array('state'=>400);
	}


	echo json_encode($response);


}

?>
