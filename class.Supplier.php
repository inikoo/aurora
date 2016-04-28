<?php
/*
  File: Supplier.php

  This file contains the Supplier Class

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/
include_once 'class.Subject.php';


class supplier extends Subject {



	var $new=false;
	public $locale='en_GB';
	function Supplier($arg1=false, $arg2=false, $arg3=false) {


		global $db;
		$this->db=$db;

		$this->table_name='Supplier';
		$this->ignore_fields=array('Supplier Key', 'Supplier 1 Year Acc Parts Profit'
			, 'Supplier 1 Year Acc Parts Profit After Storing'
			, 'Supplier 1 Year Acc Cost'
			, 'Supplier 1 Year Acc Parts Sold Amount'
			, 'Supplier 1 Quarter Acc Parts Profit'
			, 'Supplier Total Parts Profit'
			, 'Supplier Total Parts Profit After Storing'
			, 'Supplier Total Cost'
			, 'Supplier Total Parts Sold Amount'
			, 'Supplier 1 Quarter Acc Parts Profit After Storing'
			, 'Supplier 1 Quarter Acc Cost'
			, 'Supplier 1 Quarter Acc Parts Sold Amount'
			, 'Supplier 1 Month Acc Parts Profit'
			, 'Supplier 1 Month Acc Parts Profit After Storing'
			, 'Supplier 1 Month Acc Cost'
			, 'Supplier 1 Month Acc Parts Sold Amount'
			, 'Supplier 1 Month Acc Parts Broken'
			, 'Supplier 1 Week Acc Parts Profit'
			, 'Supplier 1 Week Acc Parts Profit After Storing'
			, 'Supplier 1 Week Acc Cost'
			, 'Supplier 1 Week Acc Parts Sold Amount'
			, 'Supplier Stock Value'
			, 'Supplier Active Company Products'
			, 'Supplier Discontinued Company Products'
			, 'Supplier Surplus Availability Products'
			, 'Supplier Optimal Availability Products'
			, 'Supplier Low Availability Products'
			, 'Supplier Critical Availability Products'
			, 'Supplier Out Of Stock Products'
			, 'Supplier For Sale Products'
			, 'Supplier Not For Sale Products'
			, 'Supplier To Be Discontinued Products'
			, 'Supplier Discontinued Products'

		);


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




		if ($tipo=='id' or $tipo=='key')
			$sql=sprintf("select * from `Supplier Dimension` where `Supplier Key`=%d", $id);
		elseif ($tipo=='code') {
			if ($id=='')
				$id=_('Unknown');

			$sql=sprintf("select * from `Supplier Dimension` where `Supplier Code`=%s ", prepare_mysql($id));


		}else {
			return;
		}
		if ($this->data = $this->db->query($sql)->fetch()) {
			$this->id=$this->data['Supplier Key'];
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
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				$this->found=true;
				$this->found_key=$row['Supplier Key'];

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
		$sql=sprintf("select `Category Root Key`,`Other Note`,`Category Label`,`Category Code`,`Is Category Field Other` from `Category Bridge` B left join `Category Dimension` C on (C.`Category Key`=B.`Category Key`) where  `Category Branch Type`='Head'  and B.`Subject Key`=%d and B.`Subject`='Supplier'", $this->id);

		$category_data=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_assoc($result)) {



			$sql=sprintf("select `Category Label`,`Category Code` from `Category Dimension` where `Category Key`=%d", $row['Category Root Key']);

			$res=mysql_query($sql);
			if ($row2=mysql_fetch_assoc($res)) {
				$root_label=$row2['Category Label'];
				$root_code=$row2['Category Code'];
			}


			if ($row['Is Category Field Other']=='Yes' and $row['Other Note']!='') {
				$value=$row['Other Note'];
			}
			else {
				$value=$row['Category Label'];
			}
			$category_data[]=array('root_label'=>$root_label, 'root_code'=>$root_code, 'label'=>$row['Category Label'], 'label'=>$row['Category Code'], 'value'=>$value);
		}

		return $category_data;
	}





	function get($key) {


		if (!$this->id)return false;

		list($got, $result)=$this->get_subject_common($key);
		if ($got)return $result;




		switch ($key) {

		case('Valid From'):
		case('Valid To'):
			if ($this->data['Supplier '.$key]=='') {
				return '';
			}else {
				return strftime("%a, %e %b %y", strtotime($this->data['Supplier '.$key].' +0:00'));
			}
			break;
		case ('Default Currency'):

			if ($this->data['Supplier Default Currency Code']!='') {



				$options_currencies=array();
				$sql=sprintf("select `Currency Code`,`Currency Name`,`Currency Symbol` from kbase.`Currency Dimension` where `Currency Code`=%s",
					prepare_mysql($this->data['Supplier Default Currency Code']));



				if ($result=$this->db->query($sql)) {
					if ($row = $result->fetch()) {
						return sprintf("%s (%s)", $row['Currency Name'], $row['Currency Code']);
					}else {
						return $this->data['Supplier Default Currency Code'];
					}
				}else {
					print_r($error_info=$this->db->errorInfo());
					exit;
				}




			}else {
				return '';
			}

			break;
		case 'Average Delivery Days':
		    if($this->data['Supplier Average Delivery Days']=='')return '';
			return number($this->data['Supplier Average Delivery Days']);
			break;
		case 'Delivery Time':
			include_once 'utils/natural_language.php';
			return seconds_to_string(24*3600*$this->data['Supplier Average Delivery Days']);
			break;


		case 'Products Origin Country Code':
			if ($this->data['Supplier Products Origin Country Code']) {
				include_once 'class.Country.php';
				$country=new Country('code', $this->data['Supplier Products Origin Country Code']);
				return _($country->get('Country Name')).' ('.$country->get('Country Code').')';
			}else {
				return '';
			}

			break;


		case('Purchase Orders'):
		case('Open Purchase Orders'):
		case('Delivery Notes'):
		case('Invoices'):
			return number($this->data['Supplier '.$key]);
			break;

		case('Formatted ID'):
		case("ID"):
			return $this->get_formatted_id();
		case('Total Acc Parts Sold Amount'):
			return money($this->data['Supplier Total Acc Parts Sold Amount']);
			break;
		case('Total Acc Parts Profit'):
			return money($this->data['Supplier Total Acc Parts Profit After Storing']);
			break;
		case('Stock Value'):

			if (!is_numeric($this->data['Supplier Stock Value']))
				return _('Unknown');
			else
				return money($this->data['Supplier Stock Value']);
			break;
		default;

			if (array_key_exists($key, $this->data))
				return $this->data[$key];

			if (array_key_exists('Supplier '.$key, $this->data))
				return $this->data['Supplier '.$key];

		}

		return '';

	}


	function get_formatted_number_products_to_buy() {
		$formatted_number_products_to_buy=0;
		$sql=sprintf("select count(*) as total from `Supplier Product Dimension` PD where `Supplier Key`=%d",
			$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$formatted_number_products_to_buy=$row['total'];
		}
		return $formatted_number_products_to_buy;
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





		$this->data['Supplier ID']=$this->new_id();
		$this->data['Supplier Code']=$this->check_repair_code($this->data['Supplier Code']);
		$this->data['Supplier Valid From']=gmdate('Y-m-d H:i:s');



		$keys='';
		$values='';
		foreach ($this->data as $key=>$value) {
			$keys.=",`".$key."`";

			if (in_array($key, array('Supplier Average Delivery Days','Supplier Default Incoterm','Supplier Default Port of Export','Supplier Default Port of Import','Supplier Valid To'))) {
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
				'History Abstract'=>_('Supplier Created')
				, 'History Details'=>_trim(_('New supplier')." \"".$this->data['Supplier Name']."\"  "._('added'))
				, 'Action'=>'created'
			);
			$this->add_history($history_data);
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
		$this->update_sales('Total');
		$this->update_sales('Today');
		$this->update_sales('Week To Day');
		$this->update_sales('Month To Day');
		$this->update_sales('Year To Day');
	}


	function update_last_period_sales() {

		$this->update_sales('Yesterday');
		$this->update_sales('Last Week');
		$this->update_sales('Last Month');
	}


	function update_interval_sales() {
		$this->update_sales('3 Year');
		$this->update_sales('1 Year');
		$this->update_sales('6 Month');
		$this->update_sales('1 Quarter');
		$this->update_sales('1 Month');
		$this->update_sales('10 Day');
		$this->update_sales('1 Week');
	}


	function update_previous_years_data() {

		$sales_data=$this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-1 year')), date('Y-01-01 00:00:00'));
		$this->data['Supplier 1 Year Ago Sales Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-2 year')), date('Y-01-01 00:00:00', strtotime('-1 year')));
		$this->data['Supplier 2 Year Ago Sales Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-3 year')), date('Y-01-01 00:00:00', strtotime('-2 year')));
		$this->data['Supplier 3 Year Ago Sales Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-4 year')), date('Y-01-01 00:00:00', strtotime('-3 year')));
		$this->data['Supplier 4 Year Ago Sales Amount']=$sales_data['sold_amount'];


		$sql=sprintf("update `Supplier Data` set `Supplier 1 Year Ago Sales Amount`=%.2f, `Supplier 2 Year Ago Sales Amount`=%.2f,`Supplier 3 Year Ago Sales Amount`=%.2f, `Supplier 4 Year Ago Sales Amount`=%.2f where `Supplier Key`=%d ",

			$this->data["Supplier 1 Year Ago Sales Amount"],
			$this->data["Supplier 2 Year Ago Sales Amount"],
			$this->data["Supplier 3 Year Ago Sales Amount"],
			$this->data["Supplier 4 Year Ago Sales Amount"],

			$this->id

		);

		$this->db->exec($sql);


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


	function update_sales($interval) {

		include_once 'utils/date_functions.php';


		list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb)=calculate_interval_dates($interval);
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



	function create_code($name) {
		$code=preg_replace('/[!a-z]/i', '', $name);
		$code=preg_replace('/^(the|el|la|les|los|a)\s+/i', '', $name);
		$code=preg_replace('/\s+(plc|inc|co|ltd)$/i', '', $name);
		$code=preg_split('/\s*/', $name);
		$code=$code[0];
		$code=$this->check_repair_code($code);

		return $code;
	}


	protected function check_repair_code($code) {



		$code=_trim($code);
		if (!$this->is_valid_code($code)) {
			if ($code=='') {
				$code='sup';
				if ($this->is_valid_code($code))
					return $code;
			}
			if (preg_match('/\d+$/', $code, $match[0]))
				$index=(int)$match[0]+1 ;
			else
				$index=2;
			$_code=$code;
			$ok=false;
			while ($ok or $index<100) {
				$code=$_code.$index;

				if ($this->is_valid_code($code))
					return $code;
				$index++;
			}
			exit("Error can no create code");
		} else
			return $code;

	}


	public static function is_valid_code($code) {
		//  print "------------ $code\n";
		$code=_trim($code);
		if ($code=='')
			return false;
		$sql=sprintf("select `Supplier Key`  from `Supplier Dimension` where `Supplier Code`=%s", prepare_mysql($code));

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			return false;
		} else {
			return true;
		}
	}



	function new_id() {
		$sql="select max(`Supplier ID`) as id from `Supplier Dimension`";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$id=$row['id']+1;
		} else {
			$id=1;
		}


		return $id;
	}


	function valid_id($id) {
		if (is_numeric($id) and $id>0 and $id<9223372036854775807)
			return true;
		else
			return false;
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
		case('Supplier Valid To'):
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


			// case('Supplier Products Origin Country Code'):
			// $this->update_field($field, $value, $options);
			//$this->update_field('Supplier Products Origin', $this->get('Products Origin'), $options);

			/*
			$sql=sprintf("select `Supplier Product ID` from `Supplier Product Dimension` where `Supplier Key`=%d", $this->id);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$supplier_product=new SupplierProduct('pid', $row['Supplier Product ID']);
				$supplier_product->update(array('Supplier Product Origin Country Code'=>$value));
			}
			*/
			// break;
			/*
		case('Supplier Average Delivery Days'):
			$this->update_field($field, $value, $options);

			include_once 'class.SupplierProduct.php';

			$sql=sprintf("select `Supplier Product ID` from `Supplier Product Dimension` where `Supplier Key`=%d", $this->id);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$supplier_product=new SupplierProduct('pid', $row['Supplier Product ID']);

				$supplier_product->update(array('Supplier Product Delivery Days'=>$value));
			}
			break;
			*/
		default:

			$this->update_field($field, $value, $options);
		}


	}



	function update_default_currency($currency, $modify_products, $ratio) {

		$this->update_field_switcher('Supplier Default Currency Code', $currency);

		if ($modify_products=='Yes') {

			$sql=sprintf("select `Supplier Product ID` from `Supplier Product Dimension` where `Supplier Key`=%d ",
				$this->id
			);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$supplier_product=new SupplierProduct('pid', $row['Supplier Product ID']);

				$amount=$supplier_product->data['Supplier Product Cost Per Case']*$ratio;

				$supplier_product->update_sph($amount, $supplier_product->data['Supplier Product Units Per Case'], $currency);

			}
		}


	}


	function post_add_history($history_key, $type=false) {

		if (!$type) {
			$type='Changes';
		}

		$sql=sprintf("insert into  `Supplier History Bridge` (`Supplier Key`,`History Key`,`Type`) values (%d,%d,%s)",
			$this->id,
			$history_key,
			prepare_mysql($type)
		);
		mysql_query($sql);

	}




	function normalize_purchase_orders_old($po_keys=false) {

		return;

		if (!is_array($po_keys)) {
			$sql=sprintf("select `Purchase Order Key` from `Purchase Order Dimension` where `Purchase Order Supplier Key`=%d and `Purchase Order State` in ('In Process','Submitted')  ", $this->id);
			$res=mysql_query($sql);
			$po_keys=array();
			while ($row=mysql_fetch_array($res)) {
				$po_keys[$row['Purchase Order Key']]=$row['Purchase Order Key'];
			}
		}


		if (count($po_keys)==1) {
			$sql=sprintf("update  `Purchase Order Transaction Fact` set `Purchase Order Normalized Quantity`=`Purchase Order Quantity` ,`Purchase Order Normalized Quantity Type`=`Purchase Order Quantity Type` where  `Purchase Order Key`=%d",
				join('', $po_keys)

			);
			mysql_query($sql);
			//print $sql;
			return;
		}
		$supplier_product_keys=array();
		foreach ($po_keys as $po_key) {
			$sql=sprintf("select `Purchase Order Transaction Fact Key`,`Supplier Product ID`,`Purchase Order Quantity`,`Purchase Order Quantity Type` from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d and `Supplier Key`=%d "

				, $po_key
				, $this->id
			);

			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {

				if (array_key_exists($row['Supplier Product ID'], $supplier_product_keys)) {
					$line= $supplier_product_keys[$row['Supplier Product ID']];

					if ($items[$line]['Purchase Order Quantity Type']!=$row['Purchase Order Quantity Type']) {
						$supplier_product=new SupplierProduct($row['Supplier Product ID']);
						$row['Purchase Order Quantity']=$row['Purchase Order Quantity'] *$supplier_product->units_convertion_factor($row['Purchase Order Quantity Type'], $items[$line]['Purchase Order Quantity Type']);
						$row['Purchase Order Quantity Type']=$items[$line]['Purchase Order Quantity Type'];
					}


				}


				$supplier_product_keys[$row['Supplier Product ID']]=$row['Purchase Order Line'];
				$items[$row['Purchase Order Line']]=array(
					'Supplier Product ID'=>$row['Supplier Product ID'],
					'Purchase Order Quantity'=>$row['Purchase Order Quantity'],
					'Purchase Order Quantity Type'=>$row['Purchase Order Quantity Type'],
					'Purchase Order Line'=>$row['Purchase Order Line'],
					'Purchase Order Key'=>$po_key
				);

			}

		}


		foreach ($items as $item) {
			$sql=sprintf("update  `Purchase Order Transaction Fact` set `Purchase Order Normalized Quantity`=%f ,`Purchase Order Normalized Quantity Type`=%s where  `Purchase Order Key`=%d and `Purchase Order Line`=%d"

				, $item['Purchase Order Quantity']
				, prepare_mysql($item['Purchase Order Quantity Type'])
				, $item['Purchase Order Key']
				, $item['Purchase Order Line']
			);
			mysql_query($sql);
			//  print $sql;
		}


	}


	function update_orders() {
		$number_purchase_orders=0;
		$number_open_purchase_orders=0;
		$number_delivery_notes=0;
		$number_invoices=0;

		$sql=sprintf("select count(*) as num from `Purchase Order Dimension` where `Purchase Order Supplier Key`=%d", $this->id);
		$res=mysql_query($sql);

		if ($row=mysql_fetch_array($res)) {
			$number_purchase_orders=$row['num'];
		}

		$sql=sprintf("select count(*) as num from `Purchase Order Dimension` where `Purchase Order Supplier Key`=%d and `Purchase Order State` not in ('Done','Cancelled')", $this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$number_open_purchase_orders=$row['num'];
		}

		$sql=sprintf("select count(*) as num from `Supplier Delivery Note Dimension` where `Supplier Delivery Note Supplier Key`=%d", $this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$number_delivery_notes=$row['num'];
		}

		$sql=sprintf("select count(*) as num from `Supplier Invoice Dimension` where `Supplier Invoice Supplier Key`=%d", $this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$number_invoices=$row['num'];
		}


		$sql=sprintf("update `Supplier Dimension` set `Supplier Purchase Orders`=%d,`Supplier Open Purchase Orders`=%d ,`Supplier Delivery Notes`=%d ,`Supplier Invoices`=%d where `Supplier Key`=%d"
			, $number_purchase_orders
			, $number_open_purchase_orders
			, $number_delivery_notes
			, $number_invoices
			, $this->id);
		mysql_query($sql);

	}




	function get_image_src() {
		return '';
	}



	function create_supplier_part_record($data) {

		$data['Supplier Part Supplier Key']=$this->id;
		if ($data['Supplier Part Minimum Carton Order']=='') {
			$data['Supplier Part Minimum Carton Order']=1;
		}else {
			$data['Supplier Part Minimum Carton Order']=ceil($data['Supplier Part Minimum Carton Order']);
		}



		$supplier_part= new SupplierPart('find', $data, 'create');


		if ($supplier_part->id) {
			$this->new_object_msg=$supplier_part->msg;

			if ($supplier_part->new) {
				$this->new_object=true;
				$this->update_supplier_parts_data();


				$part=new Part('find', $data, 'create');
				if ($supplier_part->new) {
					$supplier_part->update(array('Supplier Part Part SKU'=>$part->sku));
					$supplier_part->get_data('id', $supplier_part->id);
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


	function update_supplier_parts_data() {

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

		default:
			$label=$field;

		}

		return $label;

	}


}


?>
