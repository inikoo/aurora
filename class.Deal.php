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



		$keys='(';
		$values='values(';
		foreach ($data as $key=>$value) {
			$keys.="`$key`,";
			if ($key=='Deal Trigger XHTML Label') {
				$values.=prepare_mysql($value,false).",";

			}else {
				$values.=prepare_mysql($value).",";
			}
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);



		// print_r($data);
		$sql=sprintf("insert into `Deal Dimension` %s %s",$keys,$values);
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

		}

		return false;
	}

	function add_deal_schema($raw_data) {

		$base_data=array(
			'Deal Component Name'=>$this->data['Deal Name'],
			'Deal Component Allowance Description'=>'',
			'Deal Component Allowance Type'=>'',
			'Deal Component Allowance Target'=>'',
			'Deal Component Allowance Lock'=>'No',
			'Deal Component Allowance'=>'',
			'Deal Component Trigger'=>'',
			'Deal Component Replace Type'=>'none',
			'Deal Component Replace'=>''
		);
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$base_data))
				$base_data[$key]=$value;
		}
		$base_data['Deal Key']=$this->id;
		$this->schema_found=false;
		$fields=array();
		foreach ($base_data as $key=>$value) {
			if (!($key=='Deal Component Allowance'  or  $key=='Deal Component Replace'  ))
				$fields[]=$key;
		}

		$sql="select `Deal Schema Key` from `Campaign Deal Schema` where  true ";
		// print_r($fields);
		foreach ($fields as $field) {
			$sql.=sprintf(' and `%s`=%s',$field,prepare_mysql($base_data[$field],false));
		}
		//print "$sql\n";
		$result=mysql_query($sql);
		$num_results=mysql_num_rows($result);
		if ($num_results==1) {
			$row=mysql_fetch_array($result, MYSQL_ASSOC);
			$this->schema_found=true;


		}
		if (!$this->schema_found) {


			if ($base_data['Deal Component Allowance Lock']=='Yes')
				$base_data['Deal Component Allowance']=Deal::parse_allowance_metadata($base_data['Deal Component Allowance Type'],$base_data['Deal Component Allowance Description']);
			else
				$base_data['Deal Component Allowance']='';

			//print_r($base_data);

			$keys='(';
			$values='values(';
			foreach ($base_data as $key=>$value) {
				$keys.="`$key`,";
				//print "-> $key=>$value \n";
				if (
					($base_data['Deal Component Allowance Lock']=='No'  and  $key=='Deal Component Allowance') or
					($base_data['Deal Component Replace Type']=='none'  and  $key=='Deal Component Replace')
				)
					$values.=prepare_mysql($value,false).",";
				else
					$values.=prepare_mysql($value).",";
			}
			$keys=preg_replace('/,$/',')',$keys);
			$values=preg_replace('/,$/',')',$values);
			$sql=sprintf("insert into `Campaign Deal Schema` %s %s",$keys,$values);
			if (mysql_query($sql)) {
				$this->msg='Deal Schema Added';
			} else {
				print "Error can not add deal schema  $sql\n";
				exit;

			}

		} else {
			$this->msg='Deal Schema Found';
		}

	}

	function find_schema($arg) {
		$schema_data=array();
		$this->schema_found=false;
		if (is_string($arg)) {
			$sql=sprintf("select * from `Campaign Deal Schema` where `Deal Component Name`=%s",prepare_mysql($arg));
			//print "$sql\n";


			$res=mysql_query($sql);

			if ($schema_data=mysql_fetch_array($res)) {

				$this->schema_found=true;
			}
		}
		elseif (is_numeric($arg)) {
			$sql=sprintf("select * from `Campaign Deal Schema` where `Deal Schema Key`=%d",$arg);
			$res=mysql_query($sql);
			if ($schema_data=mysql_fetch_array($res)) {
				$this->schema_found=true;
			}
		}


		return $schema_data;
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

		$this->update_number_components();
		$deal_component->update_target_bridge();

		return $deal_component;
	}

	function get_components(){
		//to do
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

		include_once('class.DealCampaign.php');

		$deal_campaign=new DealCampaign($this->data['Deal Campaign Key']);
		$deal_campaign->update_usage();

	}


	/*
	function activate() {

		if ($this->data['Deal Begin Date']=='') {
			$this->data['Deal Begin Date']=gmdate("Y-m-d H:i:s");
		}else {
			if (strtotime($this->data['Deal Begin Date'].' +0:00')>time()  ) {
				$this->data['Deal Begin Date']=gmdate("Y-m-d H:i:s");
			}

		}

		$sql=sprintf("update `Deal Dimension` set `Deal Status`='Active' ,`Deal Begin Date`=%s",
			$this->data['Deal Begin Date']
		);
		mysql_query($sql);

		$store=new('id',$this->data['Deal Campaign Store Key']);
		$store->update_campaings_data();

		$sql=sprintf("select `Deal Companent Key` from ``Deal Component Dimension where `Deal Component Deal Key`=%d and `Deal COmponent Status`='Waiting'",
			$this_>id
		);
		$res=mysql_query($sql);
		while($row=mysql_fetch_assoc($res)){
			$deal_component=new DealComponent('id',$row['Deal Companent Key']);
			$deal_component->update_status('Active');
		}


		}
*/

	function update_status_from_components() {

		$state='Waiting';


		$sql=sprintf("select count(*) as num   from `Deal Component Dimension`where `Deal Component Deal Key`=%d and `Deal Component Status`='Finish'",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($row['num']>0)
				$state='Finish';
		}


		$sql=sprintf("select count(*) as num   from `Deal Component Dimension`where `Deal Component Deal Key`=%d and `Deal Component Status`='Suspended'",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($row['num']>0)
				$state='Suspended';
		}


		$sql=sprintf("select count(*) as num   from `Deal Component Dimension`where `Deal Component Deal Key`=%d and `Deal Component Status`='Active'",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($row['num']>0)
				$state='Active';
		}


		$sql=sprintf("update `Deal Dimension` set `Deal Status`=%s where `Deal Key`=%d",
			prepare_mysql($state),
			$this->id
		);
		mysql_query($sql);



		$campaign=new DealCampaign('id',$this->data['Deal Campaign Key']);
		$campaign->update_status_from_deals();


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


}

?>
