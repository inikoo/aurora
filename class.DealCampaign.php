<?php
/*

 This file contains the Campaign Class

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2013, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

class DealCampaign extends DB_Table {


	function DealCampaign($a1,$a2=false,$a3=false) {

		$this->table_name='Deal Campaign';
		$this->ignore_fields=array('Deal Campaign Key');

		if (is_numeric($a1) and !$a2) {
			$this->get_data('id',$a1);
		} else if (($a1=='new' or $a1=='create') and is_array($a2) ) {
				$this->find($a2,'create');

			}
		elseif (preg_match('/find/i',$a1))
			$this->find($a2,$a1);
		else
			$this->get_data($a1,$a2,$a3);

	}

	function get_data($tipo,$tag,$tag2=false) {

		if ($tipo=='id') {
			$sql=sprintf("select * from `Deal Campaign Dimension` where `Deal Campaign Key`=%d",$tag);
		}
		elseif ($tipo=='name_store') {
			$sql=sprintf("select * from `Deal Campaign Dimension` where `Deal Campaign Name`=%s and `Deal Campaign Store Key`=%d",
				prepare_mysql($tag),
				$tag2
			);
		}else {
			$sql=sprintf("select * from `Deal Campaign Dimension` where false");
		}


		$result=mysql_query($sql);

		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)  ) {
			$this->id=$this->data['Deal Campaign Key'];
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

		$sql=sprintf("select `Deal Campaign Key` from `Deal Campaign Dimension` where  `Deal Campaign Name`=%s and `Deal Campaign Store Key`=%d ",
			prepare_mysql($data['Deal Campaign Name']),
			$data['Deal Campaign Store Key']
		);

		$result=mysql_query($sql);
		$num_results=mysql_num_rows($result);
		if ($num_results==1) {
			$row=mysql_fetch_array($result, MYSQL_ASSOC);
			$this->found=true;
			$this->found_key=$row['Deal Campaign Key'];

		}
		if ($this->found) {
			$this->get_data('id',$this->found_key);
		}


		if ($create and !$this->found) {
			$this->create($data);

		}


	}


	function create($data) {

		$keys="";

		$values="";
		foreach ($data as $key=>$value) {
			$keys.="`$key`,";
			if ($key=='Deal Campaign Description') {
				$values.=prepare_mysql($value,false).",";
			}else {
				$values.=prepare_mysql($value).",";
			}
		}
		$keys=preg_replace('/,$/','',$keys);
		$values=preg_replace('/,$/','',$values);



		// print_r($data);
		$sql=sprintf("insert into `Deal Campaign Dimension` (%s) values(%s)",$keys,$values);

		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->get_data('id',$this->id);
			$this->new=true;

			$store=new Store('id',$this->data['Deal Campaign Store Key']);
			$store->update_campaings_data();
			$this->update_status_from_dates();

		} else {
			print "Error can not create campaign  $sql\n";
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
		
			
			return number($this->data['Deal Campaign Total Acc '.$key]);
		
			break;
		case 'Interval':
		case 'Duration':
			if (!$this->data['Deal Campaign Valid To'] ) {
				$duration=_('Permanent');
			} else {
				if ($this->data['Deal Campaign Valid From']) {
					$duration=strftime("%a %e %b %Y", strtotime($this->data['Deal Campaign Valid From']." +00:00")).' - ';
				}else {
					$duration='? -';
				}
				$duration.=strftime("%a %e %b %Y", strtotime($this->data['Deal Campaign Valid To']." +00:00"));
			}
			return $duration;

		}

		return false;
	}

	function get_formated_status() {

		switch ($this->data['Deal Campaign Status']) {
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
			return $this->data['Deal Campaign Status'];
		}

	}



	function get_from_date() {
		if ($this->data['Deal Campaign Valid From']=='') {
			return '';
		}else {
			return gmdate('d-m-Y',strtotime($this->data['Deal Campaign Valid From'].' +0:00' ));
		}
	}

	function get_to_date() {
		if ($this->data['Deal Campaign Valid To']=='') {
			return '';
		}else {
			return gmdate('d-m-Y',strtotime($this->data['Deal Campaign Valid To'].' +0:00' ));
		}
	}


	function add_deal($data) {

		$data['Deal Campaign Key']=$this->id;
		$data['Deal Store Key']=$this->data['Deal Campaign Store Key'];


        if(strtotime($this->data['Deal Campaign Valid From'])>strtotime('now')){
        		$data['Deal Begin Date']=$this->data['Deal Campaign Valid From'];

        }else{
        		$data['Deal Begin Date']=gmdate('Y-m-d H:i:s');

        }

		$data['Deal Expiration Date']=$this->data['Deal Campaign Valid To'];
		$data['Deal Status']=$this->data['Deal Campaign Status'];



		$deal=new Deal('find create',$data);
		$deal->update_status_from_dates();

		return $deal;
	}





	function update_status_from_dates() {


		if ($this->data['Deal Campaign Status']=='Waiting' and strtotime($this->data['Deal Campaign Valid From'].' +0:00')<strtotime('now +0:00')) {
			$this->update_field_switcher('Deal Campaign Status','Active','no_history');
		}



		if ($this->data['Deal Campaign Valid To']!='' and  strtotime($this->data['Deal Campaign Valid To'].' +0:00')<strtotime('now +0:00')) {

			$this->update_field_switcher('Deal Campaign Status','Finish','no_history');

		}
		/*

		foreach ($this->get_deal_keys() as $deal_key) {
			$deal=new Deal($deal_key);
			$deal->update_status_from_dates();



		}

		foreach ($this->get_deal_component_keys() as $deal_component_key) {
			$deal_compoment=new DealComponent($deal_component_key);
			$deal_compoment->update_status_from_dates();
		}
*/

	}


	function get_deal_keys() {
		$deal_keys=array();
		$sql=sprintf("select `Deal Key` from `Deal Dimension` where `Deal Campaign Key`=%d ",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$deal_keys[]=$row['Deal Key'];
		}
		return $deal_keys;

	}

	function get_number_deals() {
		$number_deals=0;
		$sql=sprintf("select count(*) as num from `Deal Dimension` where `Deal Campaign Key`=%d ",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$number_deals=$row['num'];
		}
		return $number_deals;
	}

	function get_deal_component_keys() {
		$deal_component_keys=array();
		$sql=sprintf("select `Deal Component Key` from `Deal Component Dimension` where `Deal Component Campaign Key`=%d ",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$deal_component_keys[]=$row['Deal Component Key'];
		}
		return $deal_component_keys;

	}

	function update_usage() {




		$sql=sprintf("select count( distinct O.`Order Key`) as orders,count( distinct `Order Customer Key`) as customers from `Order Deal Bridge` B left  join `Order Dimension` O on (O.`Order Key`=B.`Order Key`) where B.`Deal Campaign Key`=%d and `Applied`='Yes' and `Order Current Dispatch State`!='Cancelled' ",
			$this->id

		);
		$res=mysql_query($sql);
		$orders=0;
		$customers=0;
		if ($row=mysql_fetch_assoc($res)) {
			$orders=$row['orders'];
			$customers=$row['customers'];
		}

		$sql=sprintf("update `Deal Campaign Dimension` set `Deal Campaign Total Acc Applied Orders`=%d, `Deal Campaign Total Acc Applied Customers`=%d where `Deal Campaign Key`=%d",
			$orders,
			$customers,
			$this->id
		);
		mysql_query($sql);
		$sql=sprintf("select count( distinct O.`Order Key`) as orders,count( distinct `Order Customer Key`) as customers from `Order Deal Bridge` B left  join `Order Dimension` O on (O.`Order Key`=B.`Order Key`) where B.`Deal Campaign Key`=%d and `Used`='Yes' and `Order Current Dispatch State`!='Cancelled' ",
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

		$sql=sprintf("update `Deal Campaign Dimension` set `Deal Campaign Total Acc Used Orders`=%d, `Deal Campaign Total Acc Used Customers`=%d where `Deal Campaign Key`=%d",
			$orders,
			$customers,
			$this->id
		);
		mysql_query($sql);



		$store=new Store($this->data['Deal Campaign Store Key']);
		$store->update_campaings_data();
		$store->update_deals_data();



	}

	function delete() {

		if ($this->get_number_deals()>0 and $this->data['Deal Campaign Status']!='Waiting') {
			$this->msg='can not delete';
			return;
		}


		$sql=sprintf("delete from `Deal Campaign Dimension` where `Deal Campaign Key`=%d",
			$this->id
		);
		mysql_query($sql);

		$sql=sprintf("delete from `Deal Dimension` where `Deal Campaign Key`=%d",
			$this->id
		);
		mysql_query($sql);

		$sql=sprintf("delete from `Deal Compoment Dimension` where `Deal Compoment Campaign Key`=%d",
			$this->id
		);
		mysql_query($sql);




	}




}

?>
