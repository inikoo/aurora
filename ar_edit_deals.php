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
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];


switch ($tipo) {

case('create_campaign'):
	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')
			
		));

	create_campaign($data);
	break;

case('update_deal_metadata'):
	$data=prepare_values($_REQUEST,array(
			'name'=>array('type'=>'string'),
			'terms'=>array('type'=>'string'),
			'allowances'=>array('type'=>'string'),
			'deal_metadata_key'=>array('type'=>'key'),
		));

	update_deal_metadata($data);
	break;
case('update_deal'):
	$data=prepare_values($_REQUEST,array(
			'name'=>array('type'=>'string'),
			'description'=>array('type'=>'string'),
			'deal_key'=>array('type'=>'key'),
		));

	update_deal($data);
	break;

case('update_deal_metadata_status'):
	$data=prepare_values($_REQUEST,array(
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


case('edit_deal'):
	edit_deal();
	break;


default:

	$response=array('state'=>404,'resp'=>_('Operation not found'));
	echo json_encode($response);

}



function update_deal_metadata($data) {

	require_once 'class.DealComponent.php';
		require_once 'class.DealCampaign.php';

	
	
	
	$deal_metadata=new DealComponent($data['deal_metadata_key']);
	$deal_metadata->update(array(
			'Deal Component Name'=>$data['name'],
			'Terms'=>$data['terms'],
			'Allowances'=>$data['allowances'])
	);
	if (!$deal_metadata->error) {
		$response= array('state'=>200,
		'deal_metadata_key'=>$deal_metadata->id,
		'deal_metadata_description'=>$deal_metadata->get('Description')
		);

	} else {
		$response= array('state'=>400,'msg'=>$deal_metadata->msg);
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
		$response= array('state'=>200,'newvalue'=>$deal_metadata->new_value,'key'=>$_REQUEST['key'],'description'=>$deal_metadata->get('Description'));

	} else {
		$response= array('state'=>400,'msg'=>$deal_metadata->msg,'key'=>$_REQUEST['key']);
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
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
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


	$_SESSION['state'][$parent]['campaigns']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);

	if ($parent=='store')
		$where=sprintf("where  `Store Key`=%d    ",$parent_id);
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


	$rtext=number($total_records)." ".ngettext('campaign','campaigns',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
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

		$sql=sprintf("select * from `Campaign Deal Schema`  where `Deal Key`=%d  ",$row['Deal Key']);
		$res2 = mysql_query($sql);
		$deals='<ul style="padding:10px 20px">';
		while ($row2=mysql_fetch_array($res2, MYSQL_ASSOC)) {
			$deals.=sprintf("<li style='list-style-type: circle' >%s</li>",$row2['Deal Component Name']);
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
	$_SESSION['state'][$parent]['edit_offers']['sf']=$number_results;
	$_SESSION['state'][$parent]['edit_offers']['f_field']=$f_field;
	$_SESSION['state'][$parent]['edit_offers']['f_value']=$f_value;



	if ($parent=='store')
		$where=sprintf("where `Deal Component Record Type`='Normal' and  DM.`Store Key`=%d and DM.`Deal Component Trigger`='Order'    ",$parent_key);
	elseif ($parent=='department')
		$where=sprintf("where   `Deal Component Record Type`='Normal' and DM.`Deal Component Trigger`='Department' and  DM.`Deal Component Trigger Key`=%d   ",$parent_key);
	elseif ($parent=='family')
		$where=sprintf("where  `Deal Component Record Type`='Normal' and  DM.`Deal Component Trigger`='Family' and  DM.`Deal Component Trigger Key`=%d   ",$parent_key);
	elseif ($parent=='product')
		$where=sprintf("where  `Deal Component Record Type`='Normal'  and DM.`Deal Component Trigger`='Product' and  DM.`Deal Component Trigger Key`=%d   ",$parent_key);
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


	$rtext=number($total_records)." ".ngettext('deal','deals',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
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


	$sql="select `Deal Number Active Components`,`Deal Component Expiration Date`,`Deal Description`,D.`Deal Key`,DM.`Deal Component Trigger`,`Deal Component Key`,DM.`Deal Component Name`,D.`Deal Name`
	from `Deal Component Dimension` DM left join `Deal Dimension`D  on (DM.`Deal Component Deal Key`=D.`Deal Key`)  $where    order by $order $order_direction limit $start_from,$number_results    ";
	//print $sql;
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
				,$form_data['Label']
				,$row['Deal Component Key']
				,$row['Deal Component Key']
				,($form_data['Lock Value']?'READONLY':'')
				,$form_data['Value Class']
				,$form_data['Value']
				,$form_data['Value']
				,$form_data['Lock Label']



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
					,$form_data['Label']
					,($form_data['Lock Value']?'READONLY':'')
					,$form_data['Value Class']
					,$form_data['Value']
					,$form_data['Lock Label']);
			} else {

				$input_term=sprintf('<td style="text-align:right;width:150px;padding-right:10px" >%s</td>
                                    <td style="width:15em"  style="text-align:left"><input id="deal_term%d" onKeyUp="deal_term_changed(%d)" %s class="%s" style="width:5em" value="%s" ovalue="%s" /> %s </td>'
					,$form_data['Label']
					,$row['Deal Component Key']
					,$row['Deal Component Key']
					,($form_data['Lock Value']?'READONLY':'')
					,$form_data['Value Class']
					,$form_data['Value']
					,$form_data['Value']
					,$form_data['Lock Label']

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

		if ($row['Deal Number Active Components']==1) {

			$name.=sprintf('<div class="buttons small left"><button id="fill_edit_deal_form%d" onClick="fill_edit_deal_form(%d)" >%s</buttons></div>',

				$row['Deal Key'],
				$row['Deal Key'],
				_('Edit')

			);

		}

		$status="<br/><span id='deal_state".$deal_metadata->id."' style='font-weight:800;padding:10px 0px'>".$deal_metadata->get_xhtml_status()."</span> <img style='cursor:pointer' onClick='deal_show_edit_state(".$deal_metadata->id.",\"".$deal_metadata->data['Deal Component Status']."\")'  src='art/icons/edit.gif'>";
		$status.= '<div style="margin-top:10px;margin-left:0px;display:none" id="suspend_deal_button'.$deal_metadata->id.'"  class="buttons small left"><button onClick="suspend_deal_metadata('.$deal_metadata->id.')"  class="negative" style="margin-left:0"> '._("Suspend").'</button></div>';
		$status.= '<div style="margin-top:10px;margin-left:0px;display:none" id="activate_deal_button'.$deal_metadata->id.'"   class="buttons small left"><button onClick="activate_deal_metadata('.$deal_metadata->id.')" class="positive"> '._("Activate").'</button></div>';

		//if ($row['Campaign Deal Schema Key']) {
		// $name.=sprintf('<br/><a style="text-decoration:underline" href="edit_campaign.php?id=%d">%s</a>',$row['Campaign Deal Schema Key'],$row['Deal Name']);
		//}
		$adata[]=array(
			'status'=>$status,
			'name'=>$name,
			'description'=>'<span id="deal_metadata_description_'.$deal_metadata->id.'" style="color:#777;font-style:italic">'.$deal_metadata->get('Description').'</span>'.'</span><br/><input onKeyUp="deal_metadata_description_changed('.$deal_metadata->id.')"  id="deal_metadata_description_input'.$deal_metadata->id.'" style="margin-top:5px;width:100%" value="'.$row['Deal Component Name'].'"  ovalue="'.$row['Deal Component Name'].'"  /><br/>'.$edit,
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
		$response=array(
			'state'=>200,
			'msg'=>'ok',
			'key'=>$deal_metadata->id,
			'status'=>$deal_metadata->get_xhtml_status()

		);
	}
	echo json_encode($response);
}

function update_deal($data) {

	$deal=new Deal($data['deal_key']);
	$deal->update(array('Deal Name'=>$data['name'],'Deal Description'=>$data['description']));


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

function create_campaign($data){

$store=new Store('id',$data['parent_key']);


if($store=>id){
$campaign_data=$data['values'];

$gold_camp=$store->add_campaign($campaign_data);

}else{
$response=array('state'=>404,'resp'=>'store_not_found');
	echo json_encode($response);
}

}


?>
