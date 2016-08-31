<?php
/*
  File: Supplier.php

  This file contains the Supplier Class

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/
include_once 'class.SubjectSupplier.php';


class Supplier extends SubjectSupplier {



	var $new=false;
	public $locale='en_GB';

	function Supplier($arg1=false, $arg2=false, $arg3=false) {


		global $db;
		$this->db=$db;

		$this->table_name='Supplier';
		$this->ignore_fields=array('Supplier Key');


		if (is_numeric($arg1)) {
			$this->get_data('id', $arg1);
			return ;
		}

		if ($arg1=='new') {
			$this->find($arg2, $arg3 , 'create');
			return;
		}



		$this->get_data($arg1, $arg2);

	}


	function get_data($tipo, $id) {

		$this->data=$this->base_data();

		if ($tipo=='id' or $tipo=='key') {
			$sql=sprintf("select * from `Supplier Dimension` where `Supplier Key`=%d", $id);
		}elseif ($tipo=='code') {
			if ($id=='')
				$id=_('Unknown');

			$sql=sprintf("select * from `Supplier Dimension` where `Supplier Code`=%s ", prepare_mysql($id));


		}elseif ($tipo=='deleted') {
			$this->get_deleted_data($id);
			return;
		}else {
			return;
		}
		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Supplier Key'];
		}

	}


	function get_deleted_data( $tag) {

		$this->deleted=true;
		$sql=sprintf("select * from `Supplier Deleted Dimension` where `Supplier Deleted Key`=%d", $tag);
		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Supplier Deleted Key'];
			foreach (json_decode(gzuncompress($this->data['Supplier Deleted Metadata']), true) as $key=>$value) {
				$this->data[$key]=$value;
			}
		}
	}


	function load_acc_data() {
		$sql=sprintf("select * from `Supplier Data` where `Supplier Key`=%d", $this->id);

		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				foreach ($row as $key=>$value) {
					$this->data[$key]=$value;
				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


	}


	function find($raw_data, $address_raw_data, $options) {
		// print "$options\n";
		//print_r($raw_data);

		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;

			}
		}




		$create='';

		if (preg_match('/create/i', $options)) {
			$create='create';
		}

		if (isset($raw_data['name']))
			$raw_data['Supplier Name']=$raw_data['name'];
		if (isset($raw_data['code']))
			$raw_data['Supplier Code']=$raw_data['code'];
		if (isset($raw_data['Supplier Code']) and $raw_data['Supplier Code']=='') {
			$this->get_data('id', 1);
			return;
		}


		$data=$this->base_data();

		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key, $data)) {
				$data[$key]=_trim($value);
			}
			elseif (preg_match('/^Supplier Address/', $key)) {
				$data[$key]=_trim($value);
			}
		}

		$data['Supplier Code']=mb_substr($data['Supplier Code'], 0, 16);


		if ($data['Supplier Code']!='') {
			$sql=sprintf("select `Supplier Key` from `Supplier Dimension` where `Supplier Code`=%s ", prepare_mysql($data['Supplier Code']));

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {

					$this->found=true;
					$this->found_key=$row['Supplier Key'];


				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}

		}

		if ($this->found) {
			$this->get_data('id', $this->found_key);
		}


		if ($create) {

			if (!$this->found)
				$this->create($data, $address_raw_data);
		}






	}


	function get_category_data() {
		$sql=sprintf("select B.`Category Key`,`Category Root Key`,`Other Note`,`Category Label`,`Category Code`,`Is Category Field Other` from `Category Bridge` B left join `Category Dimension` C on (C.`Category Key`=B.`Category Key`) where  `Category Branch Type`='Head'  and B.`Subject Key`=%d and B.`Subject`='Supplier'", $this->id);

		$category_data=array();



		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {




				$sql=sprintf("select `Category Label`,`Category Code` from `Category Dimension` where `Category Key`=%d", $row['Category Root Key']);


				if ($result2=$this->db->query($sql)) {
					if ($row2 = $result2->fetch()) {
						$root_label=$row2['Category Label'];
						$root_code=$row2['Category Code'];
					}
				}else {
					print_r($error_info=$this->db->errorInfo());
					exit;
				}



				if ($row['Is Category Field Other']=='Yes' and $row['Other Note']!='') {
					$value=$row['Other Note'];
				}
				else {
					$value=$row['Category Label'];
				}
				$category_data[]=array('root_label'=>$root_label, 'root_code'=>$root_code, 'label'=>$row['Category Label'], 'label'=>$row['Category Code'], 'value'=>$value,'category_key'=>$row['Category Key']);

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		return $category_data;
	}


	function get($key) {


		if (!$this->id)return false;
		list($got, $result)=$this->get_subject_supplier_common($key);

		if ($got)return $result;




		switch ($key) {

		default;

			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Supplier '.$key, $this->data))
				return $this->data['Supplier '.$key];

		}

		return '';

	}


	function create($raw_data, $address_raw_data) {




		$this->data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key, $this->data)) {
				$this->data[$key]=_trim($value);
			}
		}



		if ($this->data['Supplier Main Plain Mobile']!='') {
			list($this->data['Supplier Main Plain Mobile'], $this->data['Supplier Main XHTML Mobile'])=$this->get_formatted_number($this->data['Supplier Main Plain Mobile']);
		}
		if ($this->data['Supplier Main Plain Telephone']!='') {
			list($this->data['Supplier Main Plain Telephone'], $this->data['Supplier Main XHTML Telephone'])=$this->get_formatted_number($this->data['Supplier Main Plain Telephone']);
		}
		if ($this->data['Supplier Main Plain FAX']!='') {
			list($this->data['Supplier Main Plain FAX'], $this->data['Supplier Main XHTML FAX'])=$this->get_formatted_number($this->data['Supplier Main Plain FAX']);
		}





		$this->data['Supplier Valid From']=gmdate('Y-m-d H:i:s');



		$keys='';
		$values='';
		foreach ($this->data as $key=>$value) {
			$keys.=",`".$key."`";

			if (in_array($key, array('Supplier Average Delivery Days', 'Supplier Default Incoterm', 'Supplier Default Port of Export', 'Supplier Default Port of Import', 'Supplier Valid To'))) {
				$values.=','.prepare_mysql($value, true);

			}else {
				$values.=','.prepare_mysql($value, false);

			}

		}
		$values=preg_replace('/^,/', '', $values);
		$keys=preg_replace('/^,/', '', $keys);

		$sql="insert into `Supplier Dimension` ($keys) values ($values)";

		if ($this->db->exec($sql)) {
			$this->id=$this->db->lastInsertId();


			$this->get_data('id', $this->id);


			$sql="insert into `Supplier Data` (`Supplier Key`) values(".$this->id.");";
			$this->db->exec($sql);

			if ($this->data['Supplier Company Name']!='') {
				$supplier_name=$this->data['Supplier Company Name'];
			}else {
				$supplier_name=$this->data['Supplier Main Contact Name'];
			}
			$this->update_field('Supplier Name', $supplier_name, 'no_history');

			$this->update_address('Contact', $address_raw_data);

			$history_data=array(
				'History Abstract'=>_('Supplier created'),
				'History Details'=>'',
				'Action'=>'created'
			);
			$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());
			$this->new=true;

		} else {
			// print "Error can not create supplier $sql\n";
		}






	}


	function update_supplier_parts() {

		$supplier_number_parts=0;
		$supplier_number_surplus_parts=0;
		$supplier_number_optimal_parts=0;
		$supplier_number_low_parts=0;
		$supplier_number_critical_parts=0;
		$supplier_number_out_of_stock_parts=0;
		
		$sql=sprintf('select count(*) as num 
		from `Supplier Part Dimension` SP  where `Supplier Part Supplier Key`=%d  ',
			$this->id
		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				//print_r($row);

				$supplier_number_parts=$row['num'];
				
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		

		$sql=sprintf('select count(*) as num ,
		sum(if(`Part Stock Status`="Surplus",1,0)) as surplus,
		sum(if(`Part Stock Status`="Optimal",1,0)) as optimal,
		sum(if(`Part Stock Status`="Low",1,0)) as low,
		sum(if(`Part Stock Status`="Critical",1,0)) as critical,
		sum(if(`Part Stock Status`="Out_Of_Stock",1,0)) as out_of_stock

		from `Supplier Part Dimension` SP  left join `Part Dimension` P on (P.`Part SKU`=SP.`Supplier Part Part SKU`)  where `Supplier Part Supplier Key`=%d  and `Part Status`="In Use" and `Supplier Part Status`!="Discontinued" ',
			$this->id
		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				//print_r($row);

				$supplier_number_parts=$row['num'];
				if ($row['num']>0) {
					$supplier_number_surplus_parts=$row['surplus'];
					$supplier_number_optimal_parts=$row['optimal'];
					$supplier_number_low_parts=$row['low'];
					$supplier_number_critical_parts=$row['critical'];
					$supplier_number_out_of_stock_parts=$row['out_of_stock'];
				}

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$this->update(array(
				'Supplier Number Parts'=>$supplier_number_parts,
				'Supplier Number Surplus Parts'=>$supplier_number_surplus_parts,
				'Supplier Number Optimal Parts'=>$supplier_number_optimal_parts,
				'Supplier Number Low Parts'=>$supplier_number_low_parts,
				'Supplier Number Critical Parts'=>$supplier_number_critical_parts,
				'Supplier Number Out Of Stock Parts'=>$supplier_number_out_of_stock_parts,

			), 'no_history');

	}




	function update_up_today_sales() {
		$this->update_period_sales('Total');
		$this->update_period_sales('Today');
		$this->update_period_sales('Week To Day');
		$this->update_period_sales('Month To Day');
		$this->update_period_sales('Year To Day');
	}


	function update_last_period_sales() {

		$this->update_period_sales('Yesterday');
		$this->update_period_sales('Last Week');
		$this->update_period_sales('Last Month');
	}


	function update_interval_sales() {
		$this->update_period_sales('3 Year');
		$this->update_period_sales('1 Year');
		$this->update_period_sales('6 Month');
		$this->update_period_sales('1 Quarter');
		$this->update_period_sales('1 Month');
		$this->update_period_sales('10 Day');
		$this->update_period_sales('1 Week');
	}



	function update_previous_years_data() {

		$data_1y_ago=$this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-1 year')), date('Y-01-01 00:00:00'));
		$data_2y_ago=$this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-2 year')), date('Y-01-01 00:00:00', strtotime('-1 year')));
		$data_3y_ago=$this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-3 year')), date('Y-01-01 00:00:00', strtotime('-2 year')));
		$data_4y_ago=$this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-4 year')), date('Y-01-01 00:00:00', strtotime('-3 year')));

		$sql=sprintf("update `Supplier Data` set `Supplier 1 Year Ago Sales Amount`=%.2f, `Supplier 2 Year Ago Sales Amount`=%.2f,`Supplier 3 Year Ago Sales Amount`=%.2f, `Supplier 4 Year Ago Sales Amount`=%.2f where `Supplier Key`=%d ",
			$data_1y_ago['sold_amount'],
			$data_2y_ago['sold_amount'],
			$data_3y_ago['sold_amount'],
			$data_4y_ago['sold_amount'],
			$this->id
		);

		$this->db->exec($sql);
		$this->load_acc_data();
	}


	function get_sales_data($from_date, $to_date) {

		$sales_data=array(
			'sold_amount'=>0,
			'sold'=>0,
			'dispatched'=>0,
			'required'=>0,
			'no_dispatched'=>0,

		);


		$sql=sprintf("select sum(`Amount In`) as sold_amount,
                     sum(`Inventory Transaction Quantity`) as dispatched,
                     sum(`Required`) as required,
                     sum(`Given`) as given,
                     sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,
                     sum(-`Given`-`Inventory Transaction Quantity`) as sold
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'Sale' and `Supplier Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s', prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s', prepare_mysql($to_date)):'')

		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$sales_data['sold_amount']=$row['sold_amount'];
				$sales_data['sold']=$row['sold'];
				$sales_data['dispatched']=-1.0*$row['dispatched'];
				$sales_data['required']=$row['required'];
				$sales_data['no_dispatched']=$row['no_dispatched'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		return $sales_data;
	}


	function update_period_sales($interval) {

		include_once 'utils/date_functions.php';


		list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb)=calculate_interval_dates($this->db, $interval);
		setlocale(LC_ALL, 'en_GB');


		$this->data["Supplier $db_interval Acc Parts Profit"]=0;
		$this->data["Supplier $db_interval Acc Parts Profit After Storing"]=0;
		$this->data["Supplier $db_interval Acc Parts Cost"]=0;
		$this->data["Supplier $db_interval Acc Parts Sold Amount"]=0;
		$this->data["Supplier $db_interval Acc Parts Bought"]=0;
		$this->data["Supplier $db_interval Acc Parts Required"]=0;
		$this->data["Supplier $db_interval Acc Parts Dispatched"]=0;
		$this->data["Supplier $db_interval Acc Parts No Dispatched"]=0;
		$this->data["Supplier $db_interval Acc Parts Sold"]=0;
		$this->data["Supplier $db_interval Acc Parts Lost"]=0;
		$this->data["Supplier $db_interval Acc Parts Broken"]=0;
		$this->data["Supplier $db_interval Acc Parts Returned"]=0;
		$this->data["Supplier $db_interval Acc Parts Margin"]=0;






		$sql=sprintf("select sum(`Amount In`+`Inventory Transaction Amount`) as profit,sum(`Inventory Transaction Storing Charge Amount`) as cost_storing
                     from `Inventory Transaction Fact` ITF   where `Supplier Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s', prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s', prepare_mysql($to_date)):'')

		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->data["Supplier $db_interval Acc Parts Profit"]=$row['profit'];
				$this->data["Supplier $db_interval Acc Parts Profit After Storing"]=$this->data["Supplier $db_interval Acc Parts Profit"]-$row['cost_storing'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$sql=sprintf("select sum(`Inventory Transaction Amount`) as cost, sum(`Inventory Transaction Quantity`) as bought
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'In'  and `Supplier Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s', prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s', prepare_mysql($to_date)):'')

		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$this->data["Supplier $db_interval Acc Parts Cost"]=$row['cost'];
				$this->data["Supplier $db_interval Acc Parts Bought"]=$row['bought'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$sql=sprintf("select sum(`Amount In`) as sold_amount,
                     sum(`Inventory Transaction Quantity`) as dispatched,
                     sum(`Required`) as required,
                     sum(`Given`) as given,
                     sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,
                     sum(-`Given`-`Inventory Transaction Quantity`) as sold
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'Sale' and `Supplier Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s', prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s', prepare_mysql($to_date)):'')

		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {

				$this->data["Supplier $db_interval Acc Parts Sold Amount"]=$row['sold_amount'];
				$this->data["Supplier $db_interval Acc Parts Sold"]=$row['sold'];
				$this->data["Supplier $db_interval Acc Parts Dispatched"]=-1.0*$row['dispatched'];
				$this->data["Supplier $db_interval Acc Parts Required"]=$row['required'];
				$this->data["Supplier $db_interval Acc Parts No Dispatched"]=$row['no_dispatched'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		$sql=sprintf("select sum(`Inventory Transaction Quantity`) as broken
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Broken' and `Supplier Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s', prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s', prepare_mysql($to_date)):'')

		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->data["Supplier $db_interval Acc Parts Broken"]=-1.*$row['broken'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		$sql=sprintf("select sum(`Inventory Transaction Quantity`) as lost
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'Lost' and `Supplier Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s', prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s', prepare_mysql($to_date)):'')

		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$this->data["Supplier $db_interval Acc Parts Lost"]=-1.*$row['lost'];

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		if ($this->data["Supplier $db_interval Acc Parts Sold Amount"]!=0)
			$margin=$this->data["Supplier $db_interval Acc Parts Profit After Storing"]/$this->data["Supplier $db_interval Acc Parts Sold Amount"];
		else
			$margin=0;
		$this->data["Supplier $db_interval Acc Parts Margin"]=$margin;

		$sql=sprintf("update `Supplier Data` set
                     `Supplier $db_interval Acc Parts Profit`=%.2f,
                     `Supplier $db_interval Acc Parts Profit After Storing`=%.2f,
                     `Supplier $db_interval Acc Parts Cost`=%.2f,
                     `Supplier $db_interval Acc Parts Sold Amount`=%.2f,
                     `Supplier $db_interval Acc Parts Sold`=%f,
                     `Supplier $db_interval Acc Parts Dispatched`=%f,
                     `Supplier $db_interval Acc Parts Required`=%f,
                     `Supplier $db_interval Acc Parts No Dispatched`=%f,
                     `Supplier $db_interval Acc Parts Broken`=%f,
                     `Supplier $db_interval Acc Parts Lost`=%f,
                     `Supplier $db_interval Acc Parts Returned`=%f,
                     `Supplier $db_interval Acc Parts Margin`=%f
                     where `Supplier Key`=%d ",

			$this->data["Supplier $db_interval Acc Parts Profit"],
			$this->data["Supplier $db_interval Acc Parts Profit After Storing"],
			$this->data["Supplier $db_interval Acc Parts Cost"],
			$this->data["Supplier $db_interval Acc Parts Sold Amount"],
			$this->data["Supplier $db_interval Acc Parts Sold"],
			$this->data["Supplier $db_interval Acc Parts Dispatched"],
			$this->data["Supplier $db_interval Acc Parts Required"],
			$this->data["Supplier $db_interval Acc Parts No Dispatched"],
			$this->data["Supplier $db_interval Acc Parts Broken"],
			$this->data["Supplier $db_interval Acc Parts Lost"],
			$this->data["Supplier $db_interval Acc Parts Returned"],
			$this->data["Supplier $db_interval Acc Parts Margin"],
			$this->id

		);

		$this->db->exec($sql);

		if ($from_date_1yb) {



			$this->data["Supplier $db_interval Acc 1YB Parts Profit"]=0;
			$this->data["Supplier $db_interval Acc 1YB Parts Profit After Storing"]=0;
			$this->data["Supplier $db_interval Acc 1YB Parts Cost"]=0;
			$this->data["Supplier $db_interval Acc 1YB Parts Sold Amount"]=0;
			$this->data["Supplier $db_interval Acc 1YB Parts Bought"]=0;
			$this->data["Supplier $db_interval Acc 1YB Parts Required"]=0;
			$this->data["Supplier $db_interval Acc 1YB Parts Dispatched"]=0;
			$this->data["Supplier $db_interval Acc 1YB Parts No Dispatched"]=0;
			$this->data["Supplier $db_interval Acc 1YB Parts Sold"]=0;
			$this->data["Supplier $db_interval Acc 1YB Parts Lost"]=0;
			$this->data["Supplier $db_interval Acc 1YB Parts Broken"]=0;
			$this->data["Supplier $db_interval Acc 1YB Parts Returned"]=0;
			$this->data["Supplier $db_interval Acc 1YB Parts Margin"]=0;



			$sql=sprintf("select sum(`Amount In`+`Inventory Transaction Amount`) as profit,sum(`Inventory Transaction Storing Charge Amount`) as cost_storing  from `Inventory Transaction Fact` ITF  where `Supplier Key`=%d %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s', prepare_mysql($from_date_1yb)):''),

				($to_date_1yb?sprintf('and `Date`<%s', prepare_mysql($to_date_1yb)):'')

			);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$this->data["Supplier $db_interval Acc 1YB Parts Profit"]=$row['profit'];
					$this->data["Supplier $db_interval Acc 1YB Parts Profit After Storing"]=$this->data["Supplier $db_interval Acc 1YB Parts Profit"]-$row['cost_storing'];

				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}


			$sql=sprintf("select sum(`Inventory Transaction Amount`) as cost, sum(`Inventory Transaction Quantity`) as bought
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'In'  and `Supplier Key`=%d %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s', prepare_mysql($from_date_1yb)):''),

				($to_date_1yb?sprintf('and `Date`<%s', prepare_mysql($to_date_1yb)):'')

			);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$this->data["Supplier $db_interval Acc 1YB Parts Cost"]=$row['cost'];
					$this->data["Supplier $db_interval Acc 1YB Parts Bought"]=$row['bought'];

				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}



			$sql=sprintf("select sum(`Amount In`) as sold_amount,
                         sum(`Inventory Transaction Quantity`) as dispatched,
                         sum(`Required`) as required,
                         sum(`Given`) as given,
                         sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,
                         sum(-`Given`-`Inventory Transaction Quantity`) as sold
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'Sale' and `Supplier Key`=%d %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s', prepare_mysql($from_date_1yb)):''),

				($to_date_1yb?sprintf('and `Date`<%s', prepare_mysql($to_date_1yb)):'')

			);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$this->data["Supplier $db_interval Acc 1YB Parts Sold Amount"]=$row['sold_amount'];
					$this->data["Supplier $db_interval Acc 1YB Parts Sold"]=$row['sold'];
					$this->data["Supplier $db_interval Acc 1YB Parts Dispatched"]=-1.0*$row['dispatched'];
					$this->data["Supplier $db_interval Acc 1YB Parts Required"]=$row['required'];
					$this->data["Supplier $db_interval Acc 1YB Parts No Dispatched"]=$row['no_dispatched'];

				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}




			$sql=sprintf("select sum(`Inventory Transaction Quantity`) as broken
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Broken' and `Supplier Key`=%d %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s', prepare_mysql($from_date_1yb)):''),

				($to_date_1yb?sprintf('and `Date`<%s', prepare_mysql($to_date_1yb)):'')

			);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$this->data["Supplier $db_interval Acc 1YB Parts Broken"]=-1.*$row['broken'];

				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}


			$sql=sprintf("select sum(`Inventory Transaction Quantity`) as lost
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type` like 'Lost' and `Supplier Key`=%d %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s', prepare_mysql($from_date_1yb)):''),

				($to_date_1yb?sprintf('and `Date`<%s', prepare_mysql($to_date_1yb)):'')

			);


			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$this->data["Supplier $db_interval Acc 1YB Parts Lost"]=-1.*$row['lost'];

				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}


			if ($this->data["Supplier $db_interval Acc 1YB Parts Sold Amount"]!=0)
				$margin=$this->data["Supplier $db_interval Acc 1YB Parts Profit After Storing"]/$this->data["Supplier $db_interval Acc 1YB Parts Sold Amount"];
			else
				$margin=0;

			$this->data["Supplier $db_interval Acc 1YB Parts Margin"]=$margin;

			$sql=sprintf("update `Supplier Data` set
                         `Supplier $db_interval Acc 1YB Parts Profit`=%.2f,
                         `Supplier $db_interval Acc 1YB Parts Profit After Storing`=%.2f,
                         `Supplier $db_interval Acc 1YB Parts Cost`=%.2f,
                         `Supplier $db_interval Acc 1YB Parts Sold Amount`=%.2f,
                         `Supplier $db_interval Acc 1YB Parts Sold`=%f,
                         `Supplier $db_interval Acc 1YB Parts Dispatched`=%f,
                         `Supplier $db_interval Acc 1YB Parts Required`=%f,
                         `Supplier $db_interval Acc 1YB Parts No Dispatched`=%f,
                         `Supplier $db_interval Acc 1YB Parts Broken`=%f,
                         `Supplier $db_interval Acc 1YB Parts Lost`=%f,
                         `Supplier $db_interval Acc 1YB Parts Returned`=%f,
                         `Supplier $db_interval Acc 1YB Parts Margin`=%f
                         where `Supplier Key`=%d ",

				$this->data["Supplier $db_interval Acc 1YB Parts Profit"],
				$this->data["Supplier $db_interval Acc 1YB Parts Profit After Storing"],
				$this->data["Supplier $db_interval Acc 1YB Parts Cost"],
				$this->data["Supplier $db_interval Acc 1YB Parts Sold Amount"],
				$this->data["Supplier $db_interval Acc 1YB Parts Sold"],
				$this->data["Supplier $db_interval Acc 1YB Parts Dispatched"],
				$this->data["Supplier $db_interval Acc 1YB Parts Required"],
				$this->data["Supplier $db_interval Acc 1YB Parts No Dispatched"],
				$this->data["Supplier $db_interval Acc 1YB Parts Broken"],
				$this->data["Supplier $db_interval Acc 1YB Parts Lost"],
				$this->data["Supplier $db_interval Acc 1YB Parts Returned"],
				$this->data["Supplier $db_interval Acc 1YB Parts Margin"],
				$this->id

			);

			$this->db->exec($sql);


		}


	}


	function update_field_switcher($field, $value, $options='', $metadata='') {



		if (is_string($value))
			$value=_trim($value);


		if ($this->update_subject_field_switcher($field, $value, $options, $metadata)) {
			return;
		}


		switch ($field) {

		case('Supplier ID'):
		case('Supplier Valid From'):
		case('Supplier Stock Value'):
		case('Supplier Company Key'):
		case('Supplier Accounts Payable Contact Key'):
			break;

		case('Supplier Sticky Note'):
			$this->update_field_switcher('Sticky Note', $value);
			break;
		case('Sticky Note'):
			$this->update_field('Supplier '.$field, $value, 'no_null');
			$this->new_value=html_entity_decode($this->new_value);
			break;
		case('Note'):
			$this->add_note($value);
			break;
		case('Attach'):
			$this->add_attach($value);
			break;
		case('Supplier Average Delivery Days'):
			$this->update_field($field, $value, $options);
			$this->update_metadata=array(
				'class_html'=>array(
					'Delivery_Time'=>$this->get('Delivery Time'),
				)

			);

			if ($value!='') {

				include_once 'class.SupplierPart.php';

				$sql=sprintf("select `Supplier Part Key` from `Supplier Part Dimension` where `Supplier Part Supplier Key`=%d  and  `Supplier Part Average Delivery Days` is NULL ", $this->id);
				if ($result=$this->db->query($sql)) {
					foreach ($result as $row) {
						$supplier_part=new SupplierPart( $row['Supplier Part Key']);

						$supplier_part->update(array('Supplier Part Average Delivery Days'=>$this->get('Supplier Average Delivery Days')), $options);
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}




			}

			break;
		case('Supplier Products Origin Country Code'):
			$this->update_field($field, $value, $options);

			include_once 'class.Part.php';

			$sql=sprintf("select  `Part SKU`  from `Supplier Part Dimension` left join `Part Dimension` on (`Part SKU`=`Supplier Part Part SKU`)  where `Supplier Part Supplier Key`=%d and  `Part Origin Country Code` is NULL", $this->id);


			if ($result=$this->db->query($sql)) {
				foreach ($result as $row) {
					$part=new Part($row['Part SKU']);

					$part->update(array('Part Origin Country Code'=>$value));
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}
			break;

		case 'Supplier Default Currency Code':

			$this->update_field($field, $value, $options);

			include_once 'class.SupplierPart.php';
			$sql=sprintf('select `Supplier Part Key` from `Supplier Part Dimension` where `Supplier Part Supplier Key`=%d ', $this->id);

			if ($result=$this->db->query($sql)) {
				foreach ($result as $row) {
					$supplier_part=new SupplierPart($row['Supplier Part Key']);

					$supplier_part->update(array('Supplier Part Currency Code'=>$this->get('Supplier Default Currency Code')), $options);
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}


			break;
		case 'unlink agent':


			include_once 'class.Agent.php';
			$agent=new Agent($value);

			$sql=sprintf('delete from `Agent Supplier Bridge` where `Agent Supplier Agent Key`=%d and `Agent Supplier Supplier Key`=%d',
				$value,
				$this->id
			);
			$this->db->exec($sql);

			$this->update_type('Free', 'no_history');
			$agent->update_supplier_parts() ;

			$history_data=array(
				'History Abstract'=>sprintf(_("Supplier %s inlinked from agent %s"), $this->data['Supplier Code'], $agent->get('Code')),
				'History Details'=>'',
				'Action'=>'edited'
			);

			$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());

			break;

		default:

			$this->update_field($field, $value, $options);
		}


	}


	function create_supplier_part_record($data) {

		$data['editor']=$this->editor;


		$data['Supplier Part Supplier Key']=$this->id;
		if ($data['Supplier Part Minimum Carton Order']=='') {
			$data['Supplier Part Minimum Carton Order']=1;
		}else {
			$data['Supplier Part Minimum Carton Order']=ceil($data['Supplier Part Minimum Carton Order']);
		}


		$data['Supplier Part Currency Code']=$this->data['Supplier Default Currency Code'];



		$data['Part Package Description']=$data['Supplier Part Package Description'];
		$data['Part Unit Description']=$data['Supplier Part Unit Description'];


		$supplier_part= new SupplierPart('find', $data, 'create');



		if ($supplier_part->id) {
			$this->new_object_msg=$supplier_part->msg;

			if ($supplier_part->new) {
				$this->new_object=true;
				$this->update_supplier_parts();


				$materials=$data['Part Materials'];
				$package_dimensions=$data['Part Package Dimensions'];
				$unit_dimensions=$data['Part Unit Dimensions'];


				unset($data['Part Materials']);
				unset($data['Part Package Dimensions']);
				unset($data['Part Unit Dimensions']);

				$part=new Part('find', $data, 'create');


				if ($part->new) {

					$part->update(
						array(
							'Part Materials'=>$materials,
							'Part Package Dimensions'=>$package_dimensions,
							'Part Unit Dimensions'=>$unit_dimensions,

						)
						, 'no_history'
					);

					$supplier_part->update(array('Supplier Part Part SKU'=>$part->sku));
					$supplier_part->get_data('id', $supplier_part->id);

					$supplier_part->update_historic_object();
					$part->update_cost();
				}else {

					$this->error=true;
					if ($part->found) {

						$this->error_code='duplicated_field';
						$this->error_metadata=json_encode(array($part->duplicated_field));

						if ($supplpartier_part->duplicated_field=='Part Reference') {
							$this->msg=_("Duplicated part reference");
						}else {
							$this->msg='Duplicated '.$part->duplicated_field;
						}


					}else {
						$this->msg=$part->msg;
					}

					$sql=sprintf('delete from `Supplier Part Dimension` where `Supplier Part Key`=%d', $supplier_part->id);
					$this->db->exec($sql);
					$sql=sprintf('select `History Key` from `Supplier Part History Bridge` where `Supplier Part Key`=%d', $supplier_part->id);
					if ($result=$this->db->query($sql)) {
						foreach ($result as $row) {
							$sql=sprintf('delete from `History Dimension` where `History Key`=%d  ', $row['History Key']);
							$this->db->exec($sql);
						}
					}else {
						print_r($error_info=$this->db->errorInfo());
						exit;
					}

					$sql=sprintf('delete from `Supplier Part Dimension` where `Supplier Part Key`=%d', $supplier_part->id);
					$this->db->exec($sql);
					$supplier_part=new SupplierPart(0);

				}




			}
			else {

				$this->error=true;
				if ($supplier_part->found) {

					$this->error_code='duplicated_field';
					$this->error_metadata=json_encode(array($supplier_part->duplicated_field));

					if ($supplier_part->duplicated_field=='Supplier Part Reference') {
						$this->msg=_("Duplicated supplier's part reference");
					}else {
						$this->msg='Duplicated '.$supplier_part->duplicated_field;
					}


				}else {
					$this->msg=$supplier_part->msg;
				}
			}
			return $supplier_part;
		}
		else {
			$this->error=true;

			if ($supplier_part->found) {
				$this->error_code='duplicated_field';
				$this->error_metadata=json_encode(array($supplier_part->duplicated_field));

				if ($supplier_part->duplicated_field=='Part Reference') {
					$this->msg=_("Duplicated part reference");
				}else {
					$this->msg='Duplicated '.$supplier_part->duplicated_field;
				}

			}else {



				$this->msg=$supplier_part->msg;
			}
		}

	}


	function get_field_label($field) {
		global $account;

		switch ($field) {

		case 'Supplier Code':
			$label=_('code');
			break;
		case 'Supplier Name':
			$label=_('name');
			break;
		case 'Supplier Location':
			$label=_('location');
			break;
		case 'Supplier Company Name':
			$label=_('company name');
			break;
		case 'Supplier Main Contact Name':
			$label=_('contact name');
			break;
		case 'Supplier Main Plain Email':
			$label=_('email');
			break;
		case 'Supplier Main Email':
			$label=_('main email');
			break;
		case 'Supplier Other Email':
			$label=_('other email');
			break;
		case 'Supplier Main Plain Telephone':
		case 'Supplier Main XHTML Telephone':
			$label=_('telephone');
			break;
		case 'Supplier Main Plain Mobile':
		case 'Supplier Main XHTML Mobile':
			$label=_('mobile');
			break;
		case 'Supplier Main Plain FAX':
		case 'Supplier Main XHTML Fax':
			$label=_('fax');
			break;
		case 'Supplier Other Telephone':
			$label=_('other telephone');
			break;
		case 'Supplier Preferred Contact Number':
			$label=_('main contact number');
			break;
		case 'Supplier Fiscal Name':
			$label=_('fiscal name');
			break;

		case 'Supplier Contact Address':
			$label=_('contact address');
			break;
		case 'Supplier Average Delivery Days':
			$label=_('delivery time (days)');
			break;
		case 'Supplier Default Currency Code':
			$label=_('currency');
			break;
		case 'Part Origin Country Code':
			$label=_('country of origin');
			break;
		case 'Supplier Default Incoterm':
			$label=_('Incoterm');
			break;
		case 'Supplier Default Port of Export':
			$label=_('Port of export');
			break;
		case 'Supplier Default Port of Import':
			$label=_('port of import');
			break;
		case 'Supplier Default PO Terms and Conditions':
			$label=_('T&C');
			break;
		case 'Supplier Show Warehouse TC in PO':
			$label=_('Include general T&C');
			break;
		case 'Supplier User Active':
			$label=_('active');
			break;
		case 'Supplier User Handle':
			$label=_('login');
			break;
		case 'Supplier User Password':
			$label=_('password');
			break;
		case 'Supplier User PIN':
			$label=_('PIN');
			break;

		default:
			$label=$field;

		}

		return $label;

	}




	function get_agents_data() {
		$agents_data=array();
		$sql=sprintf('select `Agent Code`,`Agent Key`,`Agent Name`  from `Agent Supplier Bridge` left join `Agent Dimension` on (`Agent Supplier Agent Key`=`Agent Key`)  where `Agent Supplier Supplier Key`=%d',
			$this->id
		);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$agents_data[]=array(
					'Agent Key'=>$row['Agent Key'],
					'Agent Code'=>$row['Agent Code'],
					'Agent Name'=>$row['Agent Name'],

				);
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}
		return $agents_data;

	}


	function archive() {

		$this->update_type('Archived', 'no_history');


		$history_data=array(
			'History Abstract'=>sprintf(_("Supplier %s archived"), $this->data['Supplier Code']),
			'History Details'=>'',
			'Action'=>'edited'
		);

		$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());



	}


	function unarchive() {

		$this->update_type('Free', 'no_history');


		$history_data=array(
			'History Abstract'=>sprintf(_("Supplier %s unarchived"), $this->data['Supplier Code']),
			'History Details'=>'',
			'Action'=>'edited'
		);

		$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());



	}


	function update_type($value, $options='') {

		$has_agent='No';
		$sql=sprintf('select count(*) as num from `Agent Supplier Bridge` where `Agent Supplier Supplier Key`=%d',
			$this->id
		);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				if ($row['num']>0) {
					$has_agent='Yes';
				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		if ($value!='Archived') {
			if ($has_agent=='Yes') {
				$value='Agent';
			}else {
				$value='Free';

			}

		}



		switch ($value) {
		case 'Free':
			$this->update(array(
					'Supplier Type'=>'Free',
					'Supplier Has Agent'=>$has_agent,
					'Supplier Valid To'=>''

				), 'no_history');
			break;
		case 'Agent':
			$this->update(array(
					'Supplier Type'=>'Agent',
					'Supplier Has Agent'=>$has_agent,
					'Supplier Valid To'=>''
				), 'no_history');

			break;
		case 'Archived':




			$this->update(array(
					'Supplier Type'=>'Archived',
					'Supplier Has Agent'=>$has_agent,
					'Supplier Valid To'=>gmdate('Y-m-d H:i:s')

				), 'no_history');

			break;
		default:
			$this->error=true;
			$this->msg='Not valid supplirt type value '.$value;
			break;
		}

	}


	function delete($metadata=false) {

		$this->load_acc_data();


		$sql=sprintf('insert into `Supplier Deleted Dimension`  (`Supplier Deleted Key`,`Supplier Deleted Code`,`Supplier Deleted Name`,`Supplier Deleted From`,`Supplier Deleted To`,`Supplier Deleted Metadata`) values (%d,%s,%s,%s,%s,%s) ',
			$this->id,
			prepare_mysql($this->get('Supplier Code')),
			prepare_mysql($this->get('Supplier Name')),
			prepare_mysql($this->get('Supplier Valid From')),
			prepare_mysql(gmdate('Y-m-d H:i:s')),
			prepare_mysql(gzcompress(json_encode($this->data), 9))

		);
		$this->db->exec($sql);

		//print $sql;


		$sql=sprintf('delete from `Supplier Dimension`  where `Supplier Key`=%d ',
			$this->id
		);
		$this->db->exec($sql);


		$history_data=array(
			'History Abstract'=>sprintf(_("Supplier record %s deleted"), $this->data['Supplier Name']),
			'History Details'=>'',
			'Action'=>'deleted'
		);

		$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());




		$this->deleted=true;


		$sql=sprintf('select `Supplier Part Key` from `Supplier Part Dimension` where `Supplier Part Supplier Key`=%d  ', $this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				$supplier_part=get_object('Supplier Part', $row['Supplier Part Key']);
				$supplier_part->delete();
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


	}


}


?>
