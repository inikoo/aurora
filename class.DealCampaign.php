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

		if ($tipo=='id')
			$sql=sprintf("select * from `Deal Campaign Dimension` where `Deal Campaign Key`=%d",$tag);
		elseif ($tipo=='code_store')
			$sql=sprintf("select * from `Deal Campaign Dimension` where `Deal Campaign Code`=%s and `Deal Campaign Store Key`=%d",
				prepare_mysql($tag),
				$tag2
			);


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



		$sql=sprintf("select `Deal Campaign Key` from `Deal Campaign Dimension` where  `Deal Campaign Code`=%s and `Deal Campaign Store Key`=%d ",
			prepare_mysql($data['Deal Campaign Code']),
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

		$keys='(';
		$values='values(';
		foreach ($data as $key=>$value) {
			$keys.="`$key`,";
			if ($key=='Deal Campaign Description') {
				$values.=prepare_mysql($value,false).",";
			}else {
				$values.=prepare_mysql($value).",";
			}
		}
		$keys=preg_replace('/,$/',')',$keys);
		$values=preg_replace('/,$/',')',$values);



		// print_r($data);
		$sql=sprintf("insert into `Deal Campaign Dimension` %s %s",$keys,$values);
		// print "$sql\n";
		if (mysql_query($sql)) {
			$this->id = mysql_insert_id();
			$this->get_data('id',$this->id);
			$this->new=true;

			$store=new Store('id',$this->data['Deal Campaign Store Key']);
			$store->update_campaings_data();

		} else {
			print "Error can not create campaign  $sql\n";
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



	function add_deal($data) {

		$data['Deal Campaign Key']=$this->id;
		$data['Deal Store Key']=$this->data['Deal Campaign Store Key'];


		$deal=new Deal('find create',$data);


		return $deal;
	}


	function activate() {

		if ($this->data['Deal Campaign Valid From']=='') {
			$this->data['Deal Campaign Valid From']=gmdate("Y-m-d H:i:s");
		}else {
			if (strtotime($this->data['Deal Campaign Valid From'].' +0:00')>time()  ) {
				$this->data['Deal Campaign Valid From']=gmdate("Y-m-d H:i:s");
			}

		}

		$sql=sprintf("update `Deal Campaign Dimension` set `Deal Campaign Status`='Active' ,`Deal Campaign Valid From`=%s",
			$this->data['Deal Campaign Valid From']
		);
		mysql_query($sql);
		
		$store=new DealCampaign('id',$this->data['Deal Campaign Store Key']);
		$store->update_campaings_data();

	}




	function update_status_from_deals() {

		$state='Waiting';


		$sql=sprintf("select count(*) as num   from `Deal Dimension`where `Deal Campaign Key`=%d and `Deal Status`='Finish'",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($row['num']>0)
				$state='Finish';
		}


		$sql=sprintf("select count(*) as num   from `Deal Dimension`where `Deal Campaign Key`=%d and `Deal Status`='Suspended'",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($row['num']>0)
				$state='Suspended';
		}


		$sql=sprintf("select count(*) as num   from `Deal Dimension`where `Deal Campaign Key`=%d and `Deal Status`='Active'",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($row['num']>0)
				$state='Active';
		}


		$sql=sprintf("update `Deal Campaign Dimension` set `Deal Campaign Status`=%s where `Deal Campaign Key`=%d",
			prepare_mysql($state),
			$this->id
		);
		mysql_query($sql);
		
		$store=new Store($this->data['Deal Campaign Store Key']);
		$store->update_campaings_data();
		$store->update_deals_data();
		
		
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
//print "$sql\n";
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
	// print "$sql\n";

	}

}

?>
