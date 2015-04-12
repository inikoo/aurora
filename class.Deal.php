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




	function Deal($a1,$a2=false) {

		$this->table_name='Deal';
		$this->ignore_fields=array('Deal Key');

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id',$a1);
		} else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
				$this->find($a2,'create');

			}
		elseif (preg_match('/find/i',$a1))
			$this->find($a2,$a1);
		else
			$this->get_data($a1,$a2);

	}

	function get_data($tipo,$tag) {

		if ($tipo=='id')
			$sql=sprintf("select * from `Deal Dimension` where `Deal Key`=%d",$tag);
		elseif ($tipo=='code')
			$sql=sprintf("select * from `Deal Dimension` where `Deal Code`=%s",prepare_mysql($tag));


		$result=mysql_query($sql);

		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)  ) {
			$this->id=$this->data['Deal Key'];
		}

		if ($this->data['Deal Remainder Email Campaign Key']>0) {
			include_once 'class.EmailCampaign.php';
			$this->remainder_email_campaign=new EmailCampaign($this->data['Deal Remainder Email Campaign Key']);

		}


	}






	function find($raw_data,$options) {

		if (isset($raw_data['editor']) and is_array($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}

		$this->candidate=array();
		$this->found=false;
		$this->found_key=0;
		$create='';
		$update='';
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}

		$data=$this->base_data();


		foreach ($raw_data as $key=>$value) {

			if (array_key_exists($key,$data))
				$data[$key]=$value;

		}



		$sql=sprintf("select `Deal Key` from `Deal Dimension` where  `Deal Code`=%s and `Deal Store Key`=%d ",
			prepare_mysql($data['Deal Code']),
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
			$this->get_data('id',$this->found_key);
		}


		if ($create and !$this->found) {
			$this->create($data);

		}


	}


	function create($data) {

		$keys='';
		$values='';

		foreach ($data as $key=>$value) {
			$keys.="`$key`,";
			if ($key=='Deal Trigger XHTML Label') {
				$values.=prepare_mysql($value,false).",";

			}else {
				$values.=prepare_mysql($value).",";
			}
		}
		$keys=preg_replace('/,$/','',$keys);
		$values=preg_replace('/,$/','',$values);



		// print_r($data);
		$sql=sprintf("insert into `Deal Dimension` (%s) values (%s)",$keys,$values);
		// print "$sql\n";
		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->get_data('id',$this->id);
			$this->new=true;


			$store=new Store('id',$this->data['Deal Store Key']);
			$store->update_deals_data();

		} else {
			print "Error can not create deal  $sql\n";
			exit;

		}
	}


	function get($key='') {



		if (isset($this->data[$key]))
			return $this->data[$key];

		switch ($key) {
		
				case 'Used Orders':
		case 'Used Customers':
		case 'Applied Orders':
		case 'Applied Customers':
		
			
			return number($this->data['Deal Total Acc '.$key]);

		
		case 'Duration':
			$duration='';
			if ($this->data['Deal Expiration Date']=='' and $this->data['Deal Begin Date']=='') {
				$duration=_('Permanent');
			}else {

				if ($this->data['Deal Begin Date']!='') {
					$duration=strftime("%x", strtotime($this->data['Deal Begin Date']." +00:00"));

				}
				$duration.=' - ';
				if ($this->data['Deal Expiration Date']!='') {
					$duration.=strftime("%x", strtotime($this->data['Deal Expiration Date']." +00:00"));

				}else {
					$duration.=_('Present');
				}

			}

			return $duration;
			break;
		}

		return false;
	}

	function get_formated_status() {

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

	function get_formated_terms() {
		$terms='';
		$sql=sprintf("select `Deal Component Allowance Target`,`Deal Component Allowance Type`,`Deal Component XHTML Terms Description Label`,`Deal Component Terms Description`,`Deal Component Terms Type`,`Deal Component Allowance Target XHTML Label`  from `Deal Component Dimension` where `Deal Component Deal Key`=%d group by `Deal Component Terms Description`",
			$this->id
		);
		$res=mysql_query($sql);

		$count=0;
		while ($row=mysql_fetch_assoc($res)) {
			$count++;
			if ($count==1) {
				$terms.=$row['Deal Component Terms Description'];


				if ($row['Deal Component XHTML Terms Description Label']!=''


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

	function get_terms() {
		$terms='';
		$sql=sprintf("select `Deal Component Allowance Target`,`Deal Component Allowance Type`,`Deal Component XHTML Terms Description Label`,`Deal Component Terms Description`,`Deal Component Terms Type`,`Deal Component Allowance Target XHTML Label`  from `Deal Component Dimension` where `Deal Component Deal Key`=%d group by `Deal Component Terms Description`",
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

	function get_formated_allowances() {

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

					and !($row['Deal Component Allowance Type']=='Get Free' and in_array($row['Deal Component Allowance Target'],array('Product','Family')))

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
		$allowances=preg_replace('/^\, /','',$allowances);

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

					and !($row['Deal Component Allowance Type']=='Get Free' and in_array($row['Deal Component Allowance Target'],array('Product','Family')))

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
		$allowances=preg_replace('/^\, /','',$allowances);

		// print $allowances;

		return $allowances;


	}


	function get_applied_vouchers(){
		
		$sql=sprintf("select count(*) as num from `Voucher Order Bridge` where `Deal Key`=%d",
		$this->id
		);
		$res=mysql_query($sql);
		if($row=mysql_fetch_assoc($res)){
			return $row['num'];
		}else{
			return 0;
		}
	
	
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
		$this->update_field_switcher('Deal Term Allowances',$this->get_terms().$trigger.' &#8594; '.$this->get_allowances(),'no_history');

		$this->update_field_switcher('Deal Term Allowances Label',$this->get_formated_terms().$trigger.' &#8594; '.$this->get_formated_allowances(),'no_history');
	}



	function update_field_switcher($field,$value,$options='') {

		switch ($field) {
		
		case('Deal Begin Date'):
			$this->update_begin_date($value,$options);
			break;
		case('Deal Expiration Date'):
			$this->update_expitation_date($value,$options);
			break;
		default:
			$base_data=$this->base_data();

			if (array_key_exists($field,$base_data)) {
				$this->update_field($field,$value,$options);
			}
		}
	}


	function update_begin_date($value,$options) {
		$this->updated=false;

		if ($this->data['Deal Status']=='Waiting') {

			$this->update_field('Deal Begin Date',$value,$options);

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


	function update_expitation_date($value,$options) {

		if ($this->data['Deal Status']=='Finish') {
			$this->error=true;
			$this->msg='Deal already finished';
		}else {
			$this->update_field('Deal Expiration Date',$value,$options);
			$this->updated=true;


		}

		$sql=sprintf('select `Deal Component Key` from `Deal Component Dimension` where `Deal Component Status`!="Finish" and `Deal Component Deal Key`=%d',
			$this->id
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$deal_compoment=new DealComponent($row['Deal Component Key']);
			$deal_compoment->update(array('Deal Component Expiration Date'=>$value));
		}


		$this->update_status_from_dates();


	}

	function add_component($data) {

		$data['Deal Component Deal Key']=$this->id;
		$data['Deal Component Store Key']=$this->data['Deal Store Key'];
		$data['Deal Component Campaign Key']=$this->data['Deal Campaign Key'];
		$data['Deal Component Begin Date']=$this->data['Deal Begin Date'];
		$data['Deal Component Expiration Date']=$this->data['Deal Expiration Date'];
		$data['Deal Component Status']=$this->data['Deal Status'];



		$hereditary_fields=array('Status','Name','Trigger','Trigger Key','Trigger XHTML Label','Terms Type');
		foreach ($hereditary_fields as $hereditary_field) {
			if (!array_key_exists('Deal Component '.$hereditary_field,$data)) {
				$data['Deal Component '.$hereditary_field]=
					$this->data['Deal '.$hereditary_field];
			}
		}

		$old_components=$this->get_components();

		$deal_component=new DealComponent('find create',$data);
		$deal_component->update_status($this->data['Deal Status']);
		$this->update_number_components();
		$deal_component->update_target_bridge();

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
		$sql=sprintf("select `Deal Component Key` from `Deal Component Dimension` where `Deal Component Deal Key`=%d ",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$deal_component_keys[]=$row['Deal Component Key'];
		}
		return $deal_component_keys;
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
				,prepare_mysql($value)
				,$this->id
			);
			mysql_query($sql);
			$this->data['Deal Status']=$value;
			
			
		
		}else {


			$this->update_status_from_dates($force=true);
		}

	


	}


	function update_status_from_dates($force=false) {




		if ($this->data['Deal Expiration Date']!='' and  strtotime($this->data['Deal Expiration Date'].' +0:00')<=strtotime('now +0:00')) {
			$this->update_field_switcher('Deal Status','Finish','no_history');
			return;
		}


		if (!$force and $this->data['Deal Status']=='Suspended') {
			return;
		}

		if ( strtotime($this->data['Deal Begin Date'].' +0:00')>=strtotime('now +0:00')) {
			$this->update_field_switcher('Deal Status','Waiting','no_history');
		}


		if (strtotime($this->data['Deal Begin Date'].' +0:00')<=strtotime('now +0:00')) {
		
		
		
			$this->update_field_switcher('Deal Status','Active','no_history');
		}






		
	}

	function get_from_date() {
		if ($this->data['Deal Begin Date']=='') {
			return '';
		}else {
			return gmdate('d-m-Y',strtotime($this->data['Deal Begin Date'].' +0:00' ));
		}
	}

	function get_to_date() {
		if ($this->data['Deal Expiration Date']=='') {
			return '';
		}else {
			return gmdate('d-m-Y',strtotime($this->data['Deal Expiration Date'].' +0:00' ));
		}
	}

	function is_voucher(){
		if(in_array($this->data['Deal Terms Type'],array(
		'Voucher AND Order Interval','Voucher AND Order Number','Voucher AND Amount','Voucher'
		))){
			return true;
		}else{
			return false;
		}
	}


}

?>
