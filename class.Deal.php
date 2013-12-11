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



		$hereditary_fields=array('Status','Name','Trigger','Trigger Key','Trigger XHTML Label','Terms Type');
		foreach ($hereditary_fields as $hereditary_field) {
			if (!array_key_exists('Deal Component '.$hereditary_field,$data)) {
				$data['Deal Component '.$hereditary_field]=
					$this->data['Deal '.$hereditary_field];
			}
		}

		$deal_component=new DealComponent('find create',$data);

		$this->update_number_components();
		$this->update_target_bridge();

		return $deal_component;
	}


	function update_target_bridge() {

		if ($this->data['Deal Status']=='Finished') {
			$sql=sprintf("delete from `Deal Target Bridge` where `Deal Key`=%d ",$this->id);
			mysql_query($sql);
		}else {
			$sql=sprintf("select `Deal Component Allowance Target`,`Deal Component Allowance Target Key` from `Deal Component Dimension` where `Deal Component Deal Key`=%d and `Deal Component Status`!='Finished' ",$this->id);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {

				$sql=sprintf("insert into `Deal Target Bridge` values (%d,%s,%d) ",
					$this->id,
					prepare_mysql($row['Deal Component Allowance Target']),
					$row['Deal Component Allowance Target Key']

				);
				mysql_query($sql);

				if ($row['Deal Component Allowance Target']=='Family') {

					$sql=sprintf("select `Product ID` from `Product Dimension` where `Product Family Key`=%d and `Product Record Type`='Normal' ",$row['Deal Component Allowance Target Key']);
					$res2=mysql_query($sql);
					while ($row2=mysql_fetch_assoc($res2)) {

						$sql=sprintf("insert into `Deal Target Bridge` values (%d,%s,%d) ",
							$this->id,
							prepare_mysql('Product'),
							$row2['Product ID']

						);
						mysql_query($sql);
					}


				}
			}

		}
	}

	function add_component_from_schema_old($deal_schema,$additional_data=array()) {
		$this->component_created=false;

		$schema_data=$this->find_schema($deal_schema);
		if ($this->schema_found) {

			$data['Deal Component Allowance Target']=$schema_data['Deal Component Allowance Target'];
			if (array_key_exists('Deal Component Allowance Target Key',$additional_data))
				$data['Deal Component Allowance Target Key']=$additional_data['Deal Component Allowance Target Key'];
			else
				$data['Deal Component Allowance Target Key']=0;


			switch ($data['Deal Component Allowance Target']) {
			case('Charge'):
				$target=new Charge($additional_data['Deal Component Allowance Target Key']);
				break;
			case('Shipping'):
				$target=new Shipping($additional_data['Deal Component Allowance Target Key']);
				break;
			case('Family'):
				$target=new Family($additional_data['Deal Component Allowance Target Key']);
				break;
			case('Department'):
				$target=new Department($additional_data['Deal Component Allowance Target Key']);
				break;
			case('Store'):
				$target=new Store($additional_data['Deal Component Allowance Target Key']);
				break;
			case('Customer'):
				$target=new Customer($additional_data['Deal Component Allowance Target Key']);
				break;
			case('Product'):
				$target=new Product($additional_data['Deal Component Allowance Target Key']);
				break;
			default:
				exit("can not get target ".$data['Deal Component Allowance Target']."\n");
			}

			$schema_replaceable_columns=array('Deal Component Allowance Description','Deal Component Name');
			foreach ($schema_replaceable_columns as $schema_replaceable_column) {
				if (preg_match('/\[.+\]/',$schema_data[$schema_replaceable_column],$match)) {
					$tag=preg_replace('/\[/','\\[',$match[0]);
					$tag=preg_replace('/\]/','\\]',$tag);
					$column=preg_replace('/(\[|\])/','',$match[0]);
					if ($target->get($column)!='') {
						$column_data=$target->get($column);
						$schema_data[$schema_replaceable_column]=preg_replace("/$tag/",$column_data,$schema_data[$schema_replaceable_column]);
					}
				}
			}


			$data['Deal Store Key']=$this->data['Deal Store Key'];



			$data['Deal Component Trigger']=$schema_data['Deal Component Trigger'];


			$data['Campaign Deal Schema Key']=$schema_data['Deal Schema Key'];
			if (array_key_exists('Deal Component Trigger Key',$additional_data) and is_numeric($additional_data['Deal Component Trigger Key']) ) {
				$data['Deal Component Trigger Key']=$additional_data['Deal Component Trigger Key'];
			} else
				$data['Deal Component Trigger Key']=0;

			$data['Deal Component Begin Date']=$this->data['Deal Begin Date'];
			$data['Deal Component Expiration Date']=$this->data['Deal Expiration Date'];
			//print_r($schema_data);
			$data['Deal Component Allowance Type']=$schema_data['Deal Component Allowance Type'];
			$data['Deal Component Name']=$schema_data['Deal Component Name'];
			$data['Deal Component Allowance Lock']=$schema_data['Deal Component Allowance Lock'];
			if ($schema_data['Deal Component Allowance Lock']=='Yes') {
				$data['Deal Component Allowance Description']=$schema_data['Deal Component Allowance Description'];
				$data['Deal Component Allowance']=$schema_data['Deal Component Allowance'];


			} else {
				$data['Deal Component Allowance Description']=$additional_data['Deal Component Allowance Description'];
				$data['Deal Component Allowance']=Deal::parse_allowance_metadata($data['Deal Component Allowance Type'],$data['Deal Component Allowance Description']);


			}
			$data['Deal Component Terms Lock']=$this->data['Campaign Deal Component Terms Lock'];
			$data['Deal Component Terms Type']=$this->data['Campaign Deal Component Terms Type'];

			if ($this->data['Campaign Deal Component Terms Lock']=='Yes') {
				$data['Deal Component Terms Description']=$this->data['Campaign Deal Component Terms Description'];
				$data['Deal Component Terms']=$this->data['Campaign Deal Component Terms'];

			} else {
				$data['Deal Component Terms Description']=$additional_data['Deal Component Terms Description'];
				$data['Deal Component Terms']=Deal::parse_term_metadata($data['Deal Component Terms Type'],$data['Deal Component Terms Description']);
			}
			//print_r($data);
			// exit;
			$deal=new DealComponent('find create',$data);


		}
		else {
			$this->msg='Schema not found';
			$this->error=true;

		}

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

		$sql=sprintf("update `Deal Dimension` set `Deal Total Acc Used Orders`=%d, `Deal Total Acc Customers`=%d where `Deal Key`=%d",
			$orders,
			$customers,
			$this->id
		);
		mysql_query($sql);
		// print $sql;

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

		$sql=sprintf("update `Deal Dimension` set `Deal Number Active Compoments`=%d where `Deal Key`=%d",
			$number,
			$this->id
		);
		mysql_query($sql);
		$this->data['Deal Number Compoments']=$number;
	}


}

?>
