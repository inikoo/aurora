<?php
/*

 This file contains the Deal Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class Deal extends DB_Table {




	function Deal($a1, $a2=false) {

		global $db;
		$this->db=$db;

		$this->table_name='Deal';
		$this->ignore_fields=array('Deal Key');

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id', $a1);
		} else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
			$this->find($a2, 'create');

		}
		elseif (preg_match('/find/i', $a1))
			$this->find($a2, $a1);
		else
			$this->get_data($a1, $a2);

	}


	function get_data($tipo, $tag) {

		if ($tipo=='id')
			$sql=sprintf("select * from `Deal Dimension` where `Deal Key`=%d", $tag);
		elseif ($tipo=='name')
			$sql=sprintf("select * from `Deal Dimension` where `Deal Name`=%s", prepare_mysql($tag));


		$result=mysql_query($sql);

		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)  ) {
			$this->id=$this->data['Deal Key'];
		}

		if ($this->data['Deal Remainder Email Campaign Key']>0) {
			include_once 'class.EmailCampaign.php';
			$this->remainder_email_campaign=new EmailCampaign($this->data['Deal Remainder Email Campaign Key']);

		}


	}






	function find($raw_data, $options) {

		if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;

			}
		}

		$this->candidate=array();
		$this->found=false;
		$this->found_key=0;
		$create='';
		$update='';
		if (preg_match('/create/i', $options)) {
			$create='create';
		}
		if (preg_match('/update/i', $options)) {
			$update='update';
		}

		$data=$this->base_data();


		foreach ($raw_data as $key=>$value) {

			if (array_key_exists($key, $data))
				$data[$key]=$value;

		}



		$sql=sprintf("select `Deal Key` from `Deal Dimension` where  `Deal Name`=%s and `Deal Store Key`=%d ",
			prepare_mysql($data['Deal Name']),
			$data['Deal Store Key']
		);



		$result=mysql_query($sql);
		$num_results=mysql_num_rows($result);
		if ($num_results==1) {
			$row=mysql_fetch_array($result, MYSQL_ASSOC);
			$this->found=true;
			$this->found_key=$row['Deal Key'];

		}
		if ($this->found) {
			$this->get_data('id', $this->found_key);
		}


		if ($create and !$this->found) {
			$this->create($data);

		}


	}


	function create($data) {

		$keys='';
		$values='';

		$data['Deal Label']=$data['Deal Name'];

		foreach ($data as $key=>$value) {
			$keys.="`$key`,";
			if ($key=='Deal Trigger XHTML Label') {
				$values.=prepare_mysql($value, false).",";

			}else {
				$values.=prepare_mysql($value).",";
			}
		}
		$keys=preg_replace('/,$/', '', $keys);
		$values=preg_replace('/,$/', '', $values);



		// print_r($data);
		$sql=sprintf("insert into `Deal Dimension` (%s) values (%s)", $keys, $values);
		// print "$sql\n";
		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->get_data('id', $this->id);
			$this->new=true;


			$store=new Store('id', $this->data['Deal Store Key']);
			$store->update_deals_data();

		} else {
			print "Error can not create deal  $sql\n";
			exit;

		}
	}


	function get($key='') {



		
		switch ($key) {

		case 'Used Orders':
		case 'Used Customers':
		case 'Applied Orders':
		case 'Applied Customers':


			return number($this->data['Deal Total Acc '.$key]);


		case 'Duration':
			$duration='';
			if ($this->data['Deal Expiration Date']=='' and $this->data['Deal Begin Date']=='') {
				$duration=_('permanent');
			}else {

				if ($this->data['Deal Begin Date']!='') {
					$duration=strftime("%x", strtotime($this->data['Deal Begin Date']." +00:00"));

				}
				$duration.=' - ';
				if ($this->data['Deal Expiration Date']!='') {
					$duration.=strftime("%x", strtotime($this->data['Deal Expiration Date']." +00:00"));

				}else {
					$duration.=_('permanent');
				}

			}

			return $duration;
			break;
		default:
		    if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Deal '.$key, $this->data))
				return $this->data['Deal '.$key];
			
			
		}

		
	}


	function get_formatted_status() {

		switch ($this->data['Deal Status']) {
		case 'Waiting':
			return _('Waiting');
			break;
		case 'Suspended':
			return _('Suspended');
			break;
		case 'Active':
			return _('Active');
			break;
		case 'Finish':
			return _('Finished');
			break;
		case 'Waiting':
			return _('Waiting');
			break;
		default:
			return $this->data['Deal Status'];
		}

	}


	function get_formatted_terms() {
		$terms=$this->data['Deal Terms Description'];

		if (in_array($this->data['Deal Terms Type'],
				array(
					'Department Quantity Ordered',
					'Department For Every Quantity Ordered',
					'Department For Every Quantity Any Product Ordered',
					'Family Quantity Ordered',
					'Family For Every Quantity Ordered',
					'Family For Every Quantity Any Product Ordered',
					'Product Quantity Ordered',
					'Product For Every Quantity Ordered'
				))) {


			$terms.=' '.$this->data['Deal Trigger XHTML Label'];


		}






		return $terms;
	}


	function get_terms() {
		$terms='';
		$sql=sprintf("select `Deal Component Allowance Target`,`Deal Component Allowance Type`,`Deal Component Terms Description`,`Deal Component Terms Type`,`Deal Component Allowance Target XHTML Label`  from `Deal Component Dimension` where `Deal Component Deal Key`=%d group by `Deal Component Terms Description`",
			$this->id
		);
		$res=mysql_query($sql);

		$count=0;
		while ($row=mysql_fetch_assoc($res)) {
			$count++;
			if ($count==1) {
				$terms.=$row['Deal Component Terms Description'];


				if ($row['Deal Component Allowance Target XHTML Label']!=''


					and in_array($row['Deal Component Terms Type'],
						array(
							'Department Quantity Ordered',
							'Department For Every Quantity Ordered',
							'Department For Every Quantity Any Product Ordered',
							'Family Quantity Ordered',
							'Family For Every Quantity Ordered',
							'Family For Every Quantity Any Product Ordered',
							'Product Quantity Ordered',
							'Product For Every Quantity Ordered'
						))) {
					$terms.=' ('.$row['Deal Component Allowance Target XHTML Label'].')';
				}
			}else {
				$terms.=', ...';
				break;
			}


		}

		return $terms;
	}


	function get_formatted_allowances() {

		$allowances='';
		$sql=sprintf("select `Deal Component XHTML Allowance Description Label`,`Deal Component Allowance Target`,`Deal Component Allowance Type`,`Deal Component Terms Type`,`Deal Component Trigger Key`,`Deal Component Trigger`,`Deal Component Allowance Description`,`Deal Component Allowance Target XHTML Label`,`Deal Component Allowance Target`,`Deal Component Allowance Target Key` from `Deal Component Dimension` where `Deal Component Deal Key`=%d group by `Deal Component Allowance Description`",
			$this->id
		);

		$res=mysql_query($sql);
		$count=0;
		while ($row=mysql_fetch_assoc($res)) {
			$count++;
			if ($count<=2) {

				//print $row['Deal Component Allowance Type'].' '.$row['Deal Component Allowance Target'];

				$allowances.=', '.$row['Deal Component XHTML Allowance Description Label'];
				if ($row['Deal Component Allowance Target XHTML Label']!=''

					and !($row['Deal Component Allowance Type']=='Get Free' and in_array($row['Deal Component Allowance Target'], array('Product', 'Family')))

					and !in_array($row['Deal Component Terms Type'],
						array(
							'Department Quantity Ordered',
							'Department For Every Quantity Ordered',
							'Department For Every Quantity Any Product Ordered',
							'Family Quantity Ordered',
							'Family For Every Quantity Ordered',
							'Family For Every Quantity Any Product Ordered',
							'Product Quantity Ordered',
							'Product For Every Quantity Ordered'

						))) {
					$allowances.=' ('.$row['Deal Component Allowance Target XHTML Label'].')';
				}
			}else {
				$allowances.=', ...';
				break;
			}


		}
		$allowances=preg_replace('/^\, /', '', $allowances);

		// print $allowances;

		return $allowances;


	}


	function get_allowances() {

		$allowances='';
		$sql=sprintf("select `Deal Component Allowance Target`,`Deal Component Allowance Type`,`Deal Component Terms Type`,`Deal Component Trigger Key`,`Deal Component Trigger`,`Deal Component Allowance Description`,`Deal Component Allowance Target XHTML Label`,`Deal Component Allowance Target`,`Deal Component Allowance Target Key` from `Deal Component Dimension` where `Deal Component Deal Key`=%d group by `Deal Component Allowance Description`",
			$this->id
		);

		$res=mysql_query($sql);
		$count=0;
		while ($row=mysql_fetch_assoc($res)) {
			$count++;
			if ($count<=2) {

				//print $row['Deal Component Allowance Type'].' '.$row['Deal Component Allowance Target'];

				$allowances.=', '.$row['Deal Component Allowance Description'];
				if ($row['Deal Component Allowance Target XHTML Label']!=''

					and !($row['Deal Component Allowance Type']=='Get Free' and in_array($row['Deal Component Allowance Target'], array('Product', 'Family')))

					and !in_array($row['Deal Component Terms Type'],
						array(
							'Department Quantity Ordered',
							'Department For Every Quantity Ordered',
							'Department For Every Quantity Any Product Ordered',
							'Family Quantity Ordered',
							'Family For Every Quantity Ordered',
							'Family For Every Quantity Any Product Ordered',
							'Product Quantity Ordered',
							'Product For Every Quantity Ordered'

						))) {
					$allowances.=' ('.$row['Deal Component Allowance Target XHTML Label'].')';
				}
			}else {
				$allowances.=', ...';
				break;
			}


		}
		$allowances=preg_replace('/^\, /', '', $allowances);

		// print $allowances;

		return $allowances;


	}


	function get_applied_vouchers() {

		$sql=sprintf("select count(*) as num from `Voucher Order Bridge` where `Deal Key`=%d",
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			return $row['num'];
		}else {
			return 0;
		}


	}


	function get_percentage_orders() {

		$total_orders=0;
		$dates=prepare_mysql_dates($this->data['Deal Begin Date'], $this->data['Deal Expiration Date'], '`Order Date`');
		$sql=sprintf("select  count(*) as num  from `Order Dimension` where true   %s", $dates['mysql']);
		//print $sql;
		//exit;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$total_orders=$row['num'];
		}

		return percentage($this->data['Deal Total Acc Used Orders'], $total_orders);

	}


	function get_percentage_applied_vouchers() {
		$total_orders=0;
		$dates=prepare_mysql_dates($this->data['Deal Begin Date'], $this->data['Deal Expiration Date'], '`Order Date`');
		$sql=sprintf("select  count(*) as num  from `Order Dimension` where true   %s", $dates['mysql']);
		//print $sql;
		//exit;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$total_orders=$row['num'];
		}

		return percentage($this->get_applied_vouchers(), $total_orders);

	}


	function update_term_allowances() {


		switch ($this->data['Deal Trigger']) {
		case 'Customer':
			$trigger=' (C: '.$this->data['Deal Trigger XHTML Label'].')';
			break;
		case 'Customer Category':
			$trigger=' (Cat: '.$this->data['Deal Trigger XHTML Label'].')';
			break;
		case 'Customer List':
			$trigger=' (L: '.$this->data['Deal Trigger XHTML Label'].')';
			break;
		default:
			$trigger='';
			break;
		}
		$this->update_field_switcher('Deal Term Allowances', $this->get_terms().$trigger.' &#8594; '.$this->get_allowances(), 'no_history');

		$this->update_field_switcher('Deal Term Allowances Label', $this->get_formatted_terms().$trigger.' &#8594; '.$this->get_formatted_allowances(), 'no_history');
	}



	function update_field_switcher($field, $value, $options='', $metadata='') {

		switch ($field) {

		case('Deal Begin Date'):
			$this->update_begin_date($value, $options);
			break;
		case('Deal Expiration Date'):
			$this->update_expitation_date($value, $options);
			break;
		default:
			$base_data=$this->base_data();

			if (array_key_exists($field, $base_data)) {
				$this->update_field($field, $value, $options);
			}
		}
	}


	function update_begin_date($value, $options) {
		$this->updated=false;

		if ($this->data['Deal Status']=='Waiting') {

			$this->update_field('Deal Begin Date', $value, $options);

			$sql=sprintf('select `Deal Component Key` from `Deal Component Dimension` where `Deal Component Status`="Waiting" and `Deal Component Deal Key`=%d',
				$this->id
			);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$deal_compoment=new DealComponent($row['Deal Component Key']);
				$deal_compoment->update(array('Deal Component Begin Date'=>$value));
			}

			$this->update_status_from_dates();
			$this->updated=true;
		}else {
			$this->error=true;
			$this->msg='Deal already started';
		}
	}


	function update_expitation_date($value, $options) {

		if ($this->data['Deal Status']=='Finish') {
			$this->error=true;
			$this->msg='Deal already finished';
		}else {
			$this->update_field('Deal Expiration Date', $value, $options);
			$this->updated=true;


		}

		$sql=sprintf('select `Deal Component Key` from `Deal Component Dimension` where `Deal Component Status`!="Finish" and `Deal Component Deal Key`=%d',
			$this->id
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$deal_compoment=new DealComponent($row['Deal Component Key']);
			$deal_compoment->update(array('Deal Component Expiration Date'=>$value));
			$deal_compoment->update_status_from_dates();
		}


		$this->update_status_from_dates();


	}




	function add_component($data) {

		$data['Deal Component Deal Key']=$this->id;
		$data['Deal Component Store Key']=$this->data['Deal Store Key'];
		$data['Deal Component Campaign Key']=$this->data['Deal Campaign Key'];




		$hereditary_fields=array('Expiration Date', 'Begin Date', 'Status', 'Name', 'Trigger', 'Trigger Key', 'Terms Type', 'Terms Description', 'XHTML Terms Description Label', 'Terms', 'Allowance Target Type');
		foreach ($hereditary_fields as $hereditary_field) {
			if (!array_key_exists('Deal Component '.$hereditary_field, $data)) {
				$data['Deal Component '.$hereditary_field]=$this->data['Deal '.$hereditary_field];
			}
		}



		$old_components=$this->get_components();

		$deal_component=new DealComponent('find create', $data);
		//$deal_component->update_status($this->data['Deal Status']);
		$this->update_number_components();
		$deal_component->update_target_bridge();


		$sql=sprintf("select `Deal Key` from `Deal Dimension` where `Deal Mirror Key`=%d  and `Deal Status`!='Finish' ",
			$this->id
		);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			$deal=new Deal($row['Deal Key']);
			$data['Deal Component Status']=$deal_component->data['Deal Component Status'];
			$data['Deal Component Mirror Key']=$deal_component->id;
			$deal->add_component($data);
		}

		return $deal_component;

	}


	function get_components() {
		$components=array();
	}


	function update_usage() {




		$sql=sprintf("select count( distinct O.`Order Key`) as orders,count( distinct `Order Customer Key`) as customers from `Order Deal Bridge` B left  join `Order Dimension` O on (O.`Order Key`=B.`Order Key`) where B.`Deal Key`=%d and `Applied`='Yes' and `Order Current Dispatch State`!='Cancelled' ",
			$this->id

		);
		$res=mysql_query($sql);
		$orders=0;
		$customers=0;
		if ($row=mysql_fetch_assoc($res)) {
			$orders=$row['orders'];
			$customers=$row['customers'];
		}

		$sql=sprintf("update `Deal Dimension` set `Deal Total Acc Applied Orders`=%d, `Deal Total Acc Applied Customers`=%d where `Deal Key`=%d",
			$orders,
			$customers,
			$this->id
		);
		//print "$sql\n";
		mysql_query($sql);
		$sql=sprintf("select count( distinct O.`Order Key`) as orders,count( distinct `Order Customer Key`) as customers from `Order Deal Bridge` B left  join `Order Dimension` O on (O.`Order Key`=B.`Order Key`) where B.`Deal Key`=%d and `Used`='Yes' and `Order Current Dispatch State`!='Cancelled' ",
			$this->id

		);
		$res=mysql_query($sql);
		$orders=0;
		$customers=0;
		//  print "$sql\n";
		if ($row=mysql_fetch_assoc($res)) {
			$orders=$row['orders'];
			$customers=$row['customers'];
		}

		$sql=sprintf("update `Deal Dimension` set `Deal Total Acc Used Orders`=%d, `Deal Total Acc Used Customers`=%d where `Deal Key`=%d",
			$orders,
			$customers,
			$this->id
		);
		mysql_query($sql);
	}


	function update_number_components() {
		$number=0;
		$sql=sprintf("select count(*) as number from `Deal Component Dimension` where `Deal Component Deal Key`=%d and `Deal Component Status`='Active' ",
			$this->id);
		$res=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_assoc($res)) {
			$number=$row['number'];
		}

		$sql=sprintf("update `Deal Dimension` set `Deal Number Active Components`=%d where `Deal Key`=%d",
			$number,
			$this->id
		);
		mysql_query($sql);
		$this->data['Deal Number Components']=$number;
	}


	function get_deal_component_keys() {
		$deal_component_keys=array();
		$sql=sprintf("select `Deal Component Key` from `Deal Component Dimension` where `Deal Component Deal Key`=%d ", $this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$deal_component_keys[]=$row['Deal Component Key'];
		}
		return $deal_component_keys;
	}


	function get_number_no_finished_components() {
		$number_no_finished_components=0;
		$sql=sprintf("select count(*) as num from `Deal Component Dimension` where `Deal Component Deal Key`=%d and  `Deal Component Status`!='Finish'", $this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$number_no_finished_components=$row['num'];
		}
		return $number_no_finished_components;
	}


	function get_xhtml_status() {
		switch ($this->data['Deal Status']) {
		case('Active'):
			return _("Active");
			break;
		case('Finish'):
			return _("Finished");
			break;
		case('Waiting'):
			return _("Waiting");
			break;
		case('Suspended'):
			return _("Suspended");
			break;


		}

	}


	function update_status($value) {


		if ($value=='Suspended') {
			$sql=sprintf("update `Deal Dimension` set `Deal Status`=%s where `Deal Key`=%d"
				, prepare_mysql($value)
				, $this->id
			);
			mysql_query($sql);
			$this->data['Deal Status']=$value;



		}else {


			$this->update_status_from_dates($force=true);
		}




	}


	function update_status_from_dates($force=false) {




		if ($this->data['Deal Expiration Date']!='' and  strtotime($this->data['Deal Expiration Date'].' +0:00')<=strtotime('now +0:00')) {
			$this->update_field_switcher('Deal Status', 'Finish', 'no_history');

			if ($this->data['Deal Voucher Key']) {
				$voucher=Voucher($this->data['Deal Voucher Key']);
				$voucher->update_field_switcher('Voucher Status', 'Finish', 'no_history');

			}

			return;
		}


		if (!$force and $this->data['Deal Status']=='Suspended') {
			return;
		}

		if ( strtotime($this->data['Deal Begin Date'].' +0:00')>=strtotime('now +0:00')) {
			$this->update_field_switcher('Deal Status', 'Waiting', 'no_history');
		}


		if (strtotime($this->data['Deal Begin Date'].' +0:00')<=strtotime('now +0:00')) {



			$this->update_field_switcher('Deal Status', 'Active', 'no_history');
		}







	}


	function get_mirrow_formatted_link() {
		$link='';

		if ($this->data['Deal Mirror Key']) {
			$deal=new Deal($this->data['Deal Mirror Key']);
			$link=sprintf('<a href="deal.php?id=%d">%s</a>',
				$deal->id,
				$deal->data['Deal Name']
			);
		}


		return $link;
	}


	function get_from_date() {
		if ($this->data['Deal Begin Date']=='') {
			return '';
		}else {
			return gmdate('d-m-Y', strtotime($this->data['Deal Begin Date'].' +0:00' ));
		}
	}


	function get_to_date() {
		if ($this->data['Deal Expiration Date']=='') {
			return '';
		}else {
			return gmdate('d-m-Y', strtotime($this->data['Deal Expiration Date'].' +0:00' ));
		}
	}


	function is_voucher() {
		if (in_array($this->data['Deal Terms Type'], array(
					'Voucher AND Order Interval', 'Voucher AND Order Number', 'Voucher AND Amount', 'Voucher'
				))) {
			return true;
		}else {
			return false;
		}
	}


	function get_allowances_label() {

		$component_keys=$this->get_deal_component_keys();
		$component=new DealComponent(array_pop($component_keys));


		$allowance_label=$component->data['Deal Component XHTML Allowance Description Label'];

		if ($this->data['Deal Number Active Components']>1) {
			$allowance_label='';
		}

		return $allowance_label;

	}


	function get_badge() {
		$badge='';
		if ( $this->data['Deal Number Active Components']>0) {


			$term_label=$this->data['Deal XHTML Terms Description Label'];

			$component_keys=$this->get_deal_component_keys();
			$component=new DealComponent(array_pop($component_keys));


			$allowance_label=$component->data['Deal Component XHTML Allowance Description Label'];
			$component_key=$component->id;
			if ($this->data['Deal Number Active Components']>1) {
				$allowance_label='';
				$component_key=0;
			}


			$badge=sprintf('<div id="badge_display_%d" component_key=%d class="offer"><div id="badge_name_display_%d" class="name">%s</div><div id="badge_allowances_display_%d" class="allowances">%s</div> <div id="badge_terms_display_%d" class="terms">%s</div></div>',
				$this->id,
				$component_key,
				$this->id,
				$this->data['Deal Label'],
				$this->id,
				$allowance_label,
				$this->id,
				$term_label

			);
		}
		return $badge;
	}


	function delete() {

		$this->update_usage();

		if ($this->data['Deal Total Acc Applied Orders']>0 ) {
			$this->msg=_('Can not delete the offer, because it has been applied to an order');
			$this->error=true;
			return;
		}

		$master_mirror=false;
		$sql=sprintf("select `Deal Key`,`Deal Name` from `Deal Dimension` where `Deal Mirror Key`=%d and `Deal Status`!='Finish'  ", $this->id);
		$res=mysql_query($sql);
		while ($res=mysql_fetch_assoc($res)) {
			$master_mirror=true;
			$msg=sprintf(', <a href="deal.php?id=%d">%s</a>', $row['Deal Key'], $row['Deal Name']);

		}
		if ($master_mirror) {
			$msg=preg_replace('/^, /', '', $msg);
			$this->msg=_('Can not delete the offer, because it is used for mirroing the following offers').': '.$msg;
			$this->error=true;
		}

		$sql=sprintf("delete from `Voucher Dimension` where `Voucher Key`=%d ", $this->data['Deal Voucher Key']);
		$res=mysql_query($sql);

		$sql=sprintf("delete from `Deal Component Dimension` where `Deal Component Deal Key`=%d ", $this->id);
		$res=mysql_query($sql);

		$sql=sprintf("delete from `Deal Dimension` where `Deal Key`=%d ", $this->id);
		$res=mysql_query($sql);

		$campaign=new DealCampaign($this->data['Deal Campaign Key']);
		$campaign->editor=$this->editor;

		$campaign->get_number_deals();


	}


}


?>
