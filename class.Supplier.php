<?php
/*
  File: Supplier.php

  This file contains the Supplier Class

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/
include_once 'class.DB_Table.php';

include_once 'class.Company.php';
include_once 'class.Contact.php';
include_once 'class.Telecom.php';
include_once 'class.Email.php';
include_once 'class.Address.php';



class supplier extends DB_Table {



	var $new=false;

	function Supplier($arg1=false,$arg2=false,$arg3=false) {

		$this->table_name='Supplier';
		$this->ignore_fields=array('Supplier Key','Supplier 1 Year Acc Parts Profit'
			,'Supplier 1 Year Acc Parts Profit After Storing'
			,'Supplier 1 Year Acc Cost'
			,'Supplier 1 Year Acc Parts Sold Amount'
			,'Supplier 1 Quarter Acc Parts Profit'
			,'Supplier Total Parts Profit'
			,'Supplier Total Parts Profit After Storing'
			,'Supplier Total Cost'
			,'Supplier Total Parts Sold Amount'
			,'Supplier 1 Quarter Acc Parts Profit After Storing'
			,'Supplier 1 Quarter Acc Cost'
			,'Supplier 1 Quarter Acc Parts Sold Amount'
			,'Supplier 1 Month Acc Parts Profit'
			,'Supplier 1 Month Acc Parts Profit After Storing'
			,'Supplier 1 Month Acc Cost'
			,'Supplier 1 Month Acc Parts Sold Amount'
			,'Supplier 1 Month Acc Parts Broken'
			,'Supplier 1 Week Acc Parts Profit'
			,'Supplier 1 Week Acc Parts Profit After Storing'
			,'Supplier 1 Week Acc Cost'
			,'Supplier 1 Week Acc Parts Sold Amount'
			,'Supplier Stock Value'
			,'Supplier Active Company Products'
			,'Supplier Discontinued Company Products'
			,'Supplier Surplus Availability Products'
			,'Supplier Optimal Availability Products'
			,'Supplier Low Availability Products'
			,'Supplier Critical Availability Products'
			,'Supplier Out Of Stock Products'
			,'Supplier For Sale Products'
			,'Supplier Not For Sale Products'
			,'Supplier To Be Discontinued Products'
			,'Supplier Discontinued Products'

		);


		if (is_numeric($arg1)) {
			$this->get_data('id',$arg1);
			return ;
		}
		if (preg_match('/^find/i',$arg1)) {

			$this->find($arg2,$arg3);
			return;
		}

		if (preg_match('/create|new/i',$arg1)) {
			ioioioi();
			$this->find($arg2,'create');
			return;
		}
		$this->get_data($arg1,$arg2);

	}


	function get_data($tipo,$id) {
		$this->data=$this->base_data();



		if ($tipo=='id' or $tipo=='key')
			$sql=sprintf("select * from `Supplier Dimension` where `Supplier Key`=%d",$id);
		elseif ($tipo=='code') {
			if ($id=='')
				$id=_('Unknown');

			$sql=sprintf("select * from `Supplier Dimension` where `Supplier Code`=%s ",prepare_mysql($id));


		}


		// print "$sql\n";
		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
			$this->id=$this->data['Supplier Key'];


	}


	function find($raw_data,$options) {
		// print "$options\n";
		//print_r($raw_data);

		if (isset($raw_data['editor'])) {
			foreach ($raw_data['editor'] as $key=>$value) {

				if (array_key_exists($key,$this->editor))
					$this->editor[$key]=$value;

			}
		}




		$create='';
		$update='';
		if (preg_match('/create/i',$options)) {
			$create='create';
		}
		if (preg_match('/update/i',$options)) {
			$update='update';
		}
		if (isset($raw_data['name']))
			$raw_data['Supplier Name']=$raw_data['name'];
		if (isset($raw_data['code']))
			$raw_data['Supplier Code']=$raw_data['code'];
		if (isset($raw_data['Supplier Code']) and $raw_data['Supplier Code']=='') {
			$this->get_data('id',1);
			return;
		}


		$data=$this->base_data();

		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$data)) {
				$data[$key]=_trim($value);
			}
			elseif (preg_match('/^Supplier Address/',$key)) {
				$data[$key]=_trim($value);
			}
		}

		$data['Supplier Code']=mb_substr($data['Supplier Code'], 0, 16);


		if ($data['Supplier Code']!='') {
			$sql=sprintf("select `Supplier Key` from `Supplier Dimension` where `Supplier Code`=%s ",prepare_mysql($data['Supplier Code']));
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
				$this->found=true;
				$this->found_key=$row['Supplier Key'];

			}
		}

		if ($this->found) {
			$this->get_data('id',$this->found_key);
		}


		if ($create) {

			if (!$this->found)
				$this->create($data);
		}


		if ($update) {

			if ($this->found)
				$this->update($data);
		}



	}
	function get_name() {
		return $this->data['Supplier Name'];
	}

	function get($key) {



		if (array_key_exists($key,$this->data))
			return $this->data[$key];

		switch ($key) {
		case('Purchase Orders'):
		case('Open Purchase Orders'):
		case('Delivery Notes'):
		case('Invoices'):
			return number($this->data['Supplier '.$key]);
			break;

		case('Formated ID'):
		case("ID"):
			return $this->get_formated_id();
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

		}


		if (array_key_exists('Supplier '.$key,$this->data))
			return $this->data['Supplier '.$key];

		print "Error $key not found in get from supplier\n";
		return false;

	}


	function get_formated_number_products_to_buy() {
		$formated_number_products_to_buy=0;
		$sql=sprintf("select count(*) as total from `Supplier Product Dimension` PD where `Supplier Key`=%d",
			$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$formated_number_products_to_buy=$row['total'];
		}
		return $formated_number_products_to_buy;
	}

	function create($raw_data) {

		//print_r($raw_data);

		$main_email_key=false;
		$main_telephone_key=false;
		$main_fax_key=false;
		$this->data=$this->base_data();
		foreach ($raw_data as $key=>$value) {
			if (array_key_exists($key,$this->data)) {
				$this->data[$key]=_trim($value);
			}
		}



		if ($this->data['Supplier Name']=='') {
			$this->data['Supplier Code']=$this->create_code('UNK');
		}
		if ($this->data['Supplier Code']=='') {
			$this->data['Supplier Code']=$this->create_code($this->data['Supplier Code']);
		}


		$this->data['Supplier ID']=$this->new_id();
		$this->data['Supplier Code']=$this->check_repair_code($this->data['Supplier Code']);

		$this->data['Supplier Main Plain Telephone']='';
		$this->data['Supplier Main Plain FAX']='';
		$this->data['Supplier Main Plain Email']='';
		$this->data['Supplier Main XHTML Telephone']='';
		$this->data['Supplier Main XHTML FAX']='';
		$this->data['Supplier Main XHTML Email']='';

		$keys='';
		$values='';
		foreach ($this->data as $key=>$value) {
			$keys.=",`".$key."`";
			$values.=','.prepare_mysql($value,false);
		}
		$values=preg_replace('/^,/','',$values);
		$keys=preg_replace('/^,/','',$keys);

		$sql="insert into `Supplier Dimension` ($keys) values ($values)";
		//print $sql;

		if (mysql_query($sql)) {

			$this->id=mysql_insert_id();

			if (!$this->data['Supplier Company Key']) {
				$raw_data['editor']=$this->editor;
				$company=new company('find in supplier create update',$raw_data);
				//print_r($company->data);

			} else {
				$company=new company('id',$this->data['Supplier Company Key']);
				$company->editor=$this->editor;
			}

			$company_key=$company->id;

			if ($company->last_associated_contact_key)
				$contact=new Contact($company->last_associated_contact_key);
			else
				$contact=new Contact($company->data['Company Main Contact Key']);
			$contact->editor=$this->editor;

			$this->associate_company($company->id);
			$this->associate_contact($contact->id);
			//print_r($company->data);
			// print "=================\n";


			//$company->update_parents_principal_address_keys($company->data['Company Main Address Key']);


			$address=new Address($company->data['Company Main Address Key']);
			$address->editor=$this->editor;

			$this->create_contact_address_bridge($address->id);


			//$address->update_parents();
			$address->update_parents_principal_telecom_keys('Telephone',($this->new?false:true));
			$address->update_parents_principal_telecom_keys('FAX',($this->new?false:true));


			$tel=new Telecom($address->get_principal_telecom_key('Telephone'));
			$tel->editor=$this->editor;
			if ($tel->id)
				$tel->update_parents(($this->new?false:true));
			$fax=new Telecom($address->get_principal_telecom_key('FAX'));
			$fax->editor=$this->editor;
			if ($fax->id)
				$fax->update_parents(($this->new?false:true));

			$contact->update_parents_principal_email_keys();
			$email=new Email($contact->get_principal_email_key());
			$email->editor=$this->editor;
			if ($email->id)
				$email->update_parents(($this->new?false:true));
			$this->get_data('id',$this->id);

			$this->update_company($company->id,true);
			$history_data=array(
				'History Abstract'=>_('Supplier Created')
				,'History Details'=>_trim(_('New supplier')." \"".$this->data['Supplier Name']."\"  "._('added'))
				,'Action'=>'created'
			);
			$this->add_history($history_data);
			$this->new=true;







		} else {
			// print "Error can not create supplier $sql\n";
		}






	}


	function update_products_info() {
		$this->data['Supplier Active Supplier Products']=0;
		$this->data['Supplier Discontinued Supplier Products']=0;
		$sql=sprintf("select sum(if(`Supplier Product Buy State`='Ok',1,0)) as buy_ok, sum(if(`Supplier Product Buy State`='Discontinued',1,0)) as discontinued from `Supplier Product Dimension` where  `supplier key`=%d",$this->id);

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data['Supplier Active Supplier Products']=$row['buy_ok'];
			$this->data['Supplier Discontinued Supplier Products']=$row['discontinued'];

			$sql=sprintf("update `Supplier Dimension` set `Supplier Active Supplier Products`=%d ,`Supplier Discontinued Supplier Products`=%d where `Supplier Key`=%d  ",
				$row['buy_ok'],
				$row['discontinued'],
				$this->id
			);
			mysql_query($sql);
		}

		$sql=sprintf("select   sum(if(`Product Record Type`='Discontinued',1,0)) as discontinued,sum(if(`Product Sales Type`='Not for sale',1,0)) as not_for_sale,sum(if(`Product Sales Type`='Public Sale',1,0)) as for_sale,sum(if(`Product Record Type`='In Process',1,0)) as in_process,sum(if(`Product Availability State`='Unknown',1,0)) as availability_unknown,sum(if(`Product Availability State`='Optimal',1,0)) as availability_optimal,sum(if(`Product Availability State`='Low',1,0)) as availability_low,sum(if(`Product Availability State`='Critical',1,0)) as availability_critical,sum(if(`Product Availability State`='Surplus',1,0)) as availability_surplus,sum(if(`Product Availability State`='Out Of Stock',1,0)) as availability_outofstock from
                         `Supplier Product Dimension` SPD
                         left join `Supplier Product Part Dimension` SPPD on (SPD.`Supplier Product ID`=SPPD.`Supplier Product ID` )
                         left join `Supplier Product Part List` SPPL on (SPPD.`Supplier Product Part Key`=SPPL.`Supplier Product Part Key` )
                         left join `Product Part List` PPL on (SPPL.`Part SKU`=PPL.`Part SKU`)
                         left join `Product Part Dimension` PPD on (PPD.`Product Part Key`=PPL.`Product Part Key`)
                         left join `Product Dimension` PD on (PD.`Product ID`=PPD.`Product ID`)
                         where SPD.`Supplier Key`=%d ;",
			$this->id);
		// print "$sql\n";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$sql=sprintf("update `Supplier Dimension` set `Supplier For Sale Products`=%d ,`Supplier Discontinued Products`=%d ,`Supplier Not For Sale Products`=%d , `Supplier Optimal Availability Products`=%d , `Supplier Low Availability Products`=%d ,`Supplier Critical Availability Products`=%d ,`Supplier Out Of Stock Products`=%d,`Supplier Unknown Stock Products`=%d ,`Supplier Surplus Availability Products`=%d where `Supplier Key`=%d  ",
				$row['for_sale'],
				$row['discontinued'],
				$row['not_for_sale'],
				// $row['sale_unknown'],
				$row['availability_optimal'],
				$row['availability_low'],
				$row['availability_critical'],
				$row['availability_outofstock'],
				$row['availability_unknown'],
				$row['availability_surplus'],
				$this->id
			);
			//print "$sql\n";
			mysql_query($sql);
		}
		$this->get_data('id',$this->id);

	}


	function load($key='') {
		switch ($key) {

		case('contacts'):
		case('contact'):
			$this->contact=new Contact($this->data['Supplier Main Contact Key']);
			if ($this->contact->id) {
				//$this->contact->load('telecoms');
				//$this->contact->load('contacts');
			}

		case('products_info'):
			$this->update_products_info();


			break;

		case('sales'):

			$this->update_sales();


			break;
		}

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

		$sales_data=$this->get_sales_data(date('Y-01-01 00:00:00',strtotime('-1 year')),date('Y-01-01 00:00:00'));
		$this->data['Supplier 1 Year Ago Sales Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_sales_data(date('Y-01-01 00:00:00',strtotime('-2 year')),date('Y-01-01 00:00:00',strtotime('-1 year')));
		$this->data['Supplier 2 Year Ago Sales Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_sales_data(date('Y-01-01 00:00:00',strtotime('-3 year')),date('Y-01-01 00:00:00',strtotime('-2 year')));
		$this->data['Supplier 3 Year Ago Sales Amount']=$sales_data['sold_amount'];

		$sales_data=$this->get_sales_data(date('Y-01-01 00:00:00',strtotime('-4 year')),date('Y-01-01 00:00:00',strtotime('-3 year')));
		$this->data['Supplier 4 Year Ago Sales Amount']=$sales_data['sold_amount'];


		$sql=sprintf("update `Supplier Dimension` set `Supplier 1 Year Ago Sales Amount`=%.2f, `Supplier 2 Year Ago Sales Amount`=%.2f,`Supplier 3 Year Ago Sales Amount`=%.2f, `Supplier 4 Year Ago Sales Amount`=%.2f where `Supplier Key`=%d ",

			$this->data["Supplier 1 Year Ago Sales Amount"],
			$this->data["Supplier 2 Year Ago Sales Amount"],
			$this->data["Supplier 3 Year Ago Sales Amount"],
			$this->data["Supplier 4 Year Ago Sales Amount"],

			$this->id

		);

		mysql_query($sql);


	}


	function get_sales_data($from_date,$to_date) {

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
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Sale' and `Supplier Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);


		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$sales_data['sold_amount']=$row['sold_amount'];
			$sales_data['sold']=$row['sold'];
			$sales_data['dispatched']=-1.0*$row['dispatched'];
			$sales_data['required']=$row['required'];
			$sales_data['no_dispatched']=$row['no_dispatched'];



		}

		return $sales_data;
	}


	function update_sales($interval) {




		list($db_interval,$from_date,$to_date,$from_date_1yb,$to_date_1yb)=calculate_inteval_dates($interval);
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
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);

		$result=mysql_query($sql);


		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$this->data["Supplier $db_interval Acc Parts Profit"]=$row['profit'];
			$this->data["Supplier $db_interval Acc Parts Profit After Storing"]=$this->data["Supplier $db_interval Acc Parts Profit"]-$row['cost_storing'];

		}

		$sql=sprintf("select sum(`Inventory Transaction Amount`) as cost, sum(`Inventory Transaction Quantity`) as bought
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='In'  and `Supplier Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data["Supplier $db_interval Acc Parts Cost"]=$row['cost'];
			$this->data["Supplier $db_interval Acc Parts Bought"]=$row['bought'];

		}

		$sql=sprintf("select sum(`Amount In`) as sold_amount,
                     sum(`Inventory Transaction Quantity`) as dispatched,
                     sum(`Required`) as required,
                     sum(`Given`) as given,
                     sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,
                     sum(-`Given`-`Inventory Transaction Quantity`) as sold
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Sale' and `Supplier Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);

		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data["Supplier $db_interval Acc Parts Sold Amount"]=$row['sold_amount'];
			$this->data["Supplier $db_interval Acc Parts Sold"]=$row['sold'];
			$this->data["Supplier $db_interval Acc Parts Dispatched"]=-1.0*$row['dispatched'];
			$this->data["Supplier $db_interval Acc Parts Required"]=$row['required'];
			$this->data["Supplier $db_interval Acc Parts No Dispatched"]=$row['no_dispatched'];


		}

		$sql=sprintf("select sum(`Inventory Transaction Quantity`) as broken
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Broken' and `Supplier Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);

		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data["Supplier $db_interval Acc Parts Broken"]=-1.*$row['broken'];

		}


		$sql=sprintf("select sum(`Inventory Transaction Quantity`) as lost
                     from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Lost' and `Supplier Key`=%d %s %s" ,
			$this->id,
			($from_date?sprintf('and  `Date`>=%s',prepare_mysql($from_date)):''),

			($to_date?sprintf('and `Date`<%s',prepare_mysql($to_date)):'')

		);
		$result=mysql_query($sql);
		//print "$sql\n";
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$this->data["Supplier $db_interval Acc Parts Lost"]=-1.*$row['lost'];

		}




		if ($this->data["Supplier $db_interval Acc Parts Sold Amount"]!=0)
			$margin=$this->data["Supplier $db_interval Acc Parts Profit After Storing"]/$this->data["Supplier $db_interval Acc Parts Sold Amount"];
		else
			$margin=0;
		$this->data["Supplier $db_interval Acc Parts Margin"]=$margin;

		$sql=sprintf("update `Supplier Dimension` set
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

		mysql_query($sql);


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
				($from_date_1yb?sprintf('and  `Date`>=%s',prepare_mysql($from_date_1yb)):''),

				($to_date_1yb?sprintf('and `Date`<%s',prepare_mysql($to_date_1yb)):'')

			);
			$result=mysql_query($sql);

			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$this->data["Supplier $db_interval Acc 1YB Parts Profit"]=$row['profit'];
				$this->data["Supplier $db_interval Acc 1YB Parts Profit After Storing"]=$this->data["Supplier $db_interval Acc 1YB Parts Profit"]-$row['cost_storing'];

			}

			$sql=sprintf("select sum(`Inventory Transaction Amount`) as cost, sum(`Inventory Transaction Quantity`) as bought
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='In'  and `Supplier Key`=%d %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s',prepare_mysql($from_date_1yb)):''),

				($to_date_1yb?sprintf('and `Date`<%s',prepare_mysql($to_date_1yb)):'')

			);
			$result=mysql_query($sql);

			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$this->data["Supplier $db_interval Acc 1YB Parts Cost"]=$row['cost'];
				$this->data["Supplier $db_interval Acc 1YB Parts Bought"]=$row['bought'];

			}

			$sql=sprintf("select sum(`Amount In`) as sold_amount,
                         sum(`Inventory Transaction Quantity`) as dispatched,
                         sum(`Required`) as required,
                         sum(`Given`) as given,
                         sum(`Required`-`Inventory Transaction Quantity`) as no_dispatched,
                         sum(-`Given`-`Inventory Transaction Quantity`) as sold
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Sale' and `Supplier Key`=%d %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s',prepare_mysql($from_date_1yb)):''),

				($to_date_1yb?sprintf('and `Date`<%s',prepare_mysql($to_date_1yb)):'')

			);
			$result=mysql_query($sql);
			//print "$sql\n";
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$this->data["Supplier $db_interval Acc 1YB Parts Sold Amount"]=$row['sold_amount'];
				$this->data["Supplier $db_interval Acc 1YB Parts Sold"]=$row['sold'];
				$this->data["Supplier $db_interval Acc 1YB Parts Dispatched"]=-1.0*$row['dispatched'];
				$this->data["Supplier $db_interval Acc 1YB Parts Required"]=$row['required'];
				$this->data["Supplier $db_interval Acc 1YB Parts No Dispatched"]=$row['no_dispatched'];


			}

			$sql=sprintf("select sum(`Inventory Transaction Quantity`) as broken
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Broken' and `Supplier Key`=%d %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s',prepare_mysql($from_date_1yb)):''),

				($to_date_1yb?sprintf('and `Date`<%s',prepare_mysql($to_date_1yb)):'')

			);
			$result=mysql_query($sql);
			//print "$sql\n";
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$this->data["Supplier $db_interval Acc 1YB Parts Broken"]=-1.*$row['broken'];

			}


			$sql=sprintf("select sum(`Inventory Transaction Quantity`) as lost
                         from `Inventory Transaction Fact` ITF  where `Inventory Transaction Type`='Lost' and `Supplier Key`=%d %s %s" ,
				$this->id,
				($from_date_1yb?sprintf('and  `Date`>=%s',prepare_mysql($from_date_1yb)):''),

				($to_date_1yb?sprintf('and `Date`<%s',prepare_mysql($to_date_1yb)):'')

			);
			$result=mysql_query($sql);
			//print "$sql\n";
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

				$this->data["Supplier $db_interval Acc 1YB Parts Lost"]=-1.*$row['lost'];

			}

			if ($this->data["Supplier $db_interval Acc 1YB Parts Sold Amount"]!=0)
				$margin=$this->data["Supplier $db_interval Acc 1YB Parts Profit After Storing"]/$this->data["Supplier $db_interval Acc 1YB Parts Sold Amount"];
			else
				$margin=0;

			$this->data["Supplier $db_interval Acc 1YB Parts Margin"]=$margin;

			$sql=sprintf("update `Supplier Dimension` set
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

			mysql_query($sql);


		}


	}



	function create_code($name) {
		$code=preg_replace('/[!a-z]/i','',$name);
		$code=preg_replace('/^(the|el|la|les|los|a)\s+/i','',$name);
		$code=preg_replace('/\s+(plc|inc|co|ltd)$/i','',$name);
		$code=preg_split('/\s*/',$name);
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
			if (preg_match('/\d+$/',$code,$match[0]))
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
		$sql=sprintf("select `Supplier Key`  from `Supplier Dimension` where `Supplier Code`=%s",prepare_mysql($code));

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

	function update_field_switcher($field,$value,$options='') {
		//print "$field";
		switch ($field) {
		case('Supplier ID'):
		case('Supplier Main Contact Key'):
		case('Supplier Average Delivery Days'):
		case('Supplier Valid From'):
		case('Supplier Valid To'):
		case('Supplier Stock Value'):
		case('Supplier Company Key'):
		case('Supplier Accounts Payable Contact Key'):
		case('Supplier Sales Contact Key'):
		case('Supplier Main Email Key'):
		case('Supplier Main Telephone Key'):



			break;
		case('Supplier Main Plain Telephone'):
		case('Supplier Main Plain FAX'):

			if ($field=='Supplier Main Plain Telephone')
				$type='Telephone';
			else
				$type='FAX';


			$subject=new Company($this->data['Supplier Company Key']);
			$subject->editor=$this->editor;
			$subject_type='Company';


			$subject->update(array($subject_type.' Main Plain '.$type=>$value));
			$this->updated=$subject->updated;
			$this->msg=$subject->msg;
			$this->new_value=$subject->new_value;

			break;
		case('Supplier Company Name'):
			$this->update_company_name($value,$options);
			break;
		case('Supplier Main Contact Name'):
			$this->update_child_main_contact_name($value);
			break;
		case('Supplier Main Plain Email'):
			$contact=new Contact($this->data['Supplier Main Contact Key']);
			$contact->update(array('Contact Main Plain Email'=>$value));
			$this->updated=$contact->updated;
			$this->msg=$contact->msg;
			$this->new_value=$contact->new_value;
		default:
			$this->update_field($field,$value,$options);
		}


	}

	function update_company_name($value,$options) {

		$company=new Company($this->data['Supplier Company Key']);
		$company->editor=$this->editor;
		$company->update(array('Company Name'=>$value));

		if ($company->updated) {

			$this->updated=true;
			$this->new_value=$company->new_value;
		}

	}


	function get_formated_id_link() {
		return sprintf('<a href="supplier.php?id=%d">%s</a>',$this->id, $this->get_formated_id());

	}

	function get_formated_id() {
		global $myconf;

		$sql="select count(*) as num from `Supplier Dimension`";
		$res=mysql_query($sql);
		$min_number_zeros=4;
		if ($row=mysql_fetch_array($res)) {
			if (strlen($row['num'])-1>$min_number_zeros)
				$min_number_zeros=strlen($row['num'])-01;
		}
		if (!is_numeric($min_number_zeros))
			$min_number_zeros=4;

		return sprintf("%s%0".$min_number_zeros."d",$myconf['supplier_id_prefix'], $this->data['Supplier ID']);

	}




	function update_company($company_key=false) {
		$this->associated=false;
		if (!$company_key)
			return;
		$company=new company($company_key);
		if (!$company->id) {
			$this->msg='company not found';
			return;

		}


		$old_company_key=$this->data['Supplier Company Key'];

		if ($old_company_key  and $old_company_key!=$company_key   ) {
			$this->remove_company();
		}
		if ($old_company_key!=$company_key) {
			$sql=sprintf("insert into `Company Bridge` values (%d,'Supplier',%d,'Yes','Yes')",
				$company->id,
				$this->id
			);
			mysql_query($sql);
			if (mysql_affected_rows()) {
				$this->associated=true;

			}
		}


		$old_name=$this->data['Supplier Name'];
		if ($old_name!=$company->data['Company Name']) {


			if ($this->data['Supplier Name']!=$company->data['Company Name']) {
				$old_supplier_name=$this->data['Supplier Name'];
				$this->data['Supplier Name']=$company->data['Company Name'];
				$this->data['Supplier File As']=$company->data['Company File As'];
				$sql=sprintf("update `Supplier Dimension` set `Supplier Name`=%d,`Supplier File As`=%s where `Supplier Key`=%d"
					,prepare_mysql($this->data['Supplier Name'])
					,prepare_mysql($this->data['Supplier File As'])
					,$this->id
				);
				mysql_query($sql);
				$note=_('Company name changed');
				$details=_('Supplier Name changed from')." \"".$old_supplier_name."\" "._('to')." \"".$this->data['Supplier Name']."\"";
				$history_data=array(
					'Indirect Object'=>'Supplier Name'
					,'History Details'=>$details
					,'History Abstract'=>$note
					,'Action'=>'edited'
				);
				$this->add_history($history_data);

			}

			$this->data['Supplier Company Key']=$company->id;
			$this->data['Supplier Company Name']=$company->data['Company Name'];
			$sql=sprintf("update `Supplier Dimension` set `Supplier Company Key`=%d,`Supplier Fiscal Name`=%s where `Supplier Key`=%d"

				,$this->data['Supplier Company Key']
				,prepare_mysql($company->data['Company Fiscal Name'])
				,$this->id
			);
			mysql_query($sql);



			$this->updated=true;






			$note=_('Supplier company name changed');
			if ($old_company_key) {
				$details=_('Supplier company name changed from')." \"".$old_name."\" "._('to')." \"".$this->data['Supplier Company Name']."\"";
			} else {
				$details=_('Supplier company set to')." \"".$this->data['Supplier Company Name']."\"";
			}

			$history_data=array(
				'Indirect Object'=>'Supplier Company Name'

				,'History Details'=>$details
				,'History Abstract'=>$note
				,'Action'=>'edited'
			);
			$this->add_history($history_data);

		}


		if ($this->associated) {
			$note=_('Company name changed');
			$details=_('Company')." ".$company->data['Company Name']." (".$company->get_formated_id_link().") "._('associated with Supplier:')." ".$this->data['Supplier Name']." (".$this->get_formated_id_link().")";
			$history_data=array(
				'Indirect Object'=>'Supplier Name'
				,'History Details'=>$details
				,'History Abstract'=>$note
				,'Action'=>'edited',
				'deep'=>2
			);
			$this->add_history($history_data,true);
		}

		$this->update_contact($company->data['Company Main Contact Key']);

	}



	function add_tel($data,$args='principal') {

		$principal=false;
		if (preg_match('/not? principal/',$args) ) {
			$principal=false;
		}
		elseif ( preg_match('/principal/',$args)) {
			$principal=true;
		}




		if (is_numeric($data)) {
			$tmp=$data;
			unset($data);
			$data['Telecom Key']=$tmp;
		}

		if (isset($data['Telecom Key'])) {
			$telecom=new Telecom('id',$data['Telecom Key']);
		}

		if (!isset($data['Telecom Type'])  or $data['Telecom Type']!='Contact Fax' )
			$data['Telecom Type']='Contact Telephone';



		if ($data['Telecom Type']=='Contact Telephone') {
			$field='Supplier Main XHTML Telephone';
			$field_key='Supplier Main Telephone Key';
			$field_plain='Supplier Main Plain Telephone';
			$old_principal_key=$this->data['Supplier Main Telephone Key'];
			$old_value=$this->data['Supplier Main XHTML Telephone']." (Id:".$this->data['Supplier Main Telephone Key'].")";
		} else {
			$field='Supplier Main XHTML FAX';
			$field_key='Supplier Main FAX Key';
			$field_plain='Supplier Main Plain FAX';
			$old_principal_key=$this->data['Supplier Main FAX Key'];
			$old_value=$this->data['Supplier Main XHTML FAX']." (Id:".$this->data['Supplier Main FAX Key'].")";
		}



		if ($telecom->id) {

			// print "$principal $old_principal_key ".$telecom->id."  \n";


			if ($principal and $old_principal_key!=$telecom->id) {
				$sql=sprintf("update `Telecom Bridge`  set `Is Main`='No' where `Subject Type`='Supplier' and  `Subject Key`=%d  ",
					$this->id
					,$telecom->id
				);
				mysql_query($sql);

				$sql=sprintf("update `Supplier Dimension` set `%s`=%s , `%s`=%d  , `%s`=%s  where `Supplier Key`=%d"
					,$field
					,prepare_mysql($telecom->display('html'))
					,$field_key
					,$telecom->id
					,$field_plain
					,prepare_mysql($telecom->display('plain'))
					,$this->id
				);
				mysql_query($sql);
				$history_data=array(
					'History Abstract'=>$field." "._('Changed')
					,'History Details'=>$field." "._('changed')." "
					.$old_value." -> ".$telecom->display('html')
					." (Id:"
					.$telecom->id
					.")"
					,'Action'=>'created'
				);
				if (!$this->new)
					$this->add_history($history_data);


			}



			$sql=sprintf("insert into  `Telecom Bridge` (`Telecom Key`, `Subject Key`,`Subject Type`,`Telecom Type`,`Is Main`) values (%d,%d,'Supplier',%s,%s)  ON DUPLICATE KEY UPDATE `Telecom Type`=%s ,`Is Main`=%s  "
				,$telecom->id
				,$this->id
				,prepare_mysql($data['Telecom Type'])
				,prepare_mysql($principal?'Yes':'No')
				,prepare_mysql($data['Telecom Type'])
				,prepare_mysql($principal?'Yes':'No')
			);
			mysql_query($sql);






		}

	}
	function update_child_main_contact_name($value) {

		if ($value=='') {
			$this->error=true;
			$this->msg=_('Invalid Contact Name');
			return;
		}

		$contact=new Contact($this->data['Supplier Main Contact Key']);
		$contact->editor=$this->editor;
		$contact->update(array('Contact Name'=>$value));


		if ($contact->updated) {

			$this->updated=true;
			$this->new_value=$contact->new_value;
		}

	}


	function update_contact($contact_key=false) {


		$this->associated=false;
		if (!$contact_key) {
			$this->msg='contact key not found';
			return;

		}

		$contact=new contact($contact_key);
		$contact->editor=$this->editor;
		if (!$contact->id) {
			$this->msg='contact not found';
			return;

		}


		$old_contact_key=$this->data['Supplier Main Contact Key'];

		if ($old_contact_key  and $old_contact_key!=$contact_key   ) {
			$this->remove_contact();
		}
		if ($old_contact_key!=$contact_key) {
			$sql=sprintf("insert into `Contact Bridge` values (%d,'Supplier',%d,'Yes','Yes')",
				$contact->id,
				$this->id
			);
			mysql_query($sql);
			if (mysql_affected_rows()) {
				$this->associated=true;

			}

		}

		$old_name=$this->data['Supplier Main Contact Name'];
		if ($old_name!=$contact->display('name')) {



			$this->data['Supplier Main Contact Key']=$contact->id;
			$this->data['Supplier Main Contact Name']=$contact->display('name');
			$sql=sprintf("update `Supplier Dimension` set `Supplier Main Contact Key`=%d,`Supplier Main Contact Name`=%s where `Supplier Key`=%d"

				,$this->data['Supplier Main Contact Key']
				,prepare_mysql($this->data['Supplier Main Contact Name'])
				,$this->id
			);
			mysql_query($sql);
			//print $sql;


			$this->updated=true;






			$note=_('Supplier contact name changed');
			if ($old_contact_key) {
				$details=_('Supplier contact name changed from')." \"".$old_name."\" "._('to')." \"".$this->data['Supplier Main Contact Name']."\"";
			} else {
				$details=_('Supplier contact set to')." \"".$this->data['Supplier Main Contact Name']."\"";
			}

			$history_data=array(
				'Indirect Object'=>'Supplier Main Contact Name'

				,'History Details'=>$details
				,'History Abstract'=>$note
				,'Action'=>'edited'
			);
			$this->add_history($history_data);

		}


		if ($this->associated) {
			$note=_('Contact name changed');
			$details=_('Contact')." ".$contact->display('name')." (".$contact->get_formated_id_link().") "._('associated with Supplier:')." ".$this->data['Supplier Name']." (".$this->get_formated_id_link().")";
			$history_data=array(
				'Indirect Object'=>'Supplier Name'
				,'History Details'=>$details
				,'History Abstract'=>$note
				,'Action'=>'edited',
				'Deep'=>2
			);
			$this->add_history($history_data,true);
		}

	}





	function remove_company($company_key=false) {


		if (!$company_key) {
			$company_key=$this->data['Supplier Company Key'];
		}


		$company=new company($company_key);
		if (!$company->id) {
			$this->error=true;
			$this->msg='Wrong company key when trying to remove it';
			$this->msg_updated='Wrong company key when trying to remove it';
		}

		$company->set_scope('Supplier',$this->id);
		if ( $company->associated_with_scope) {

			$sql=sprintf("delete `Company Bridge`  where `Subject Type`='Supplier' and  `Subject Key`=%d  and `Company Key`=%d",
				$this->id

				,$this->data['Supplier Company Key']
			);
			mysql_query($sql);

			if ($company->id==$this->data['Supplier Company Key']) {
				$sql=sprintf("update `Supplier Dimension` set `Supplier Company Name`='' , `Supplier Company Key`=''  where `Supplier Key`=%d"
					,$this->id
				);

				mysql_query($sql);
				if ($this->data['Supplier Type']=='Company') {
					$sql=sprintf("update `Supplier Dimension` set `Supplier Name`='' , `Supplier File As`=''  where `Supplier Key`=%d"
						,$this->id
					);

					mysql_query($sql);

				}


			}
		}
	}

	function update_telephone($telecom_key) {

		$old_telecom_key=$this->data['Supplier Main Telephone Key'];

		$telecom=new Telecom($telecom_key);
		if (!$telecom->id) {
			$this->error=true;
			$this->msg='Telecom not found';
			$this->msg_updated.=',Telecom not found';
			return;
		}
		$old_value=$this->data['Supplier Main XHTML Telephone'];
		$sql=sprintf("update `Supplier Dimension` set `Supplier Main XHTML Telephone`=%s ,`Supplier Main Plain Telephone`=%s  ,`Supplier Main Telephone Key`=%d where `Supplier Key`=%d "
			,prepare_mysql($telecom->display('xhtml'))
			,prepare_mysql($telecom->display('plain'))
			,$telecom->id
			,$this->id
		);
		mysql_query($sql);
		if (mysql_affected_rows()) {

			$this->updated;
			if ($old_value!=$telecom->display('xhtml'))
				$history_data=array(
					'Indirect Object'=>'Supplier Main XHTML Telephone'
					,'History Abstract'=>_('Supplier Main XHTML Telephone Changed')
					,'History Details'=>_('Supplier Main XHTML Telephone changed from')." ".$old_value." "._('to').' '.$telecom->display('xhtml')
				);
			$this->add_history($history_data);
		}

	}

	function update_fax($telecom_key) {


		$old_telecom_key=$this->data['Supplier Main FAX Key'];

		$telecom=new Telecom($telecom_key);
		if (!$telecom->id) {
			$this->error=true;
			$this->msg='Telecom not found';
			$this->msg_updated.=',Telecom not found';
			return;
		}
		$old_value=$this->data['Supplier Main XHTML FAX'];
		$sql=sprintf("update `Supplier Dimension` set `Supplier Main XHTML FAX`=%s ,`Supplier Main Plain FAX`=%s  ,`Supplier Main Plain FAX`=%d where `Supplier Key`=%d "
			,prepare_mysql($telecom->display('xhtml'))
			,prepare_mysql($telecom->display('plain'))
			,$telecom->id
			,$this->id
		);
		mysql_query($sql);
		if (mysql_affected_rows()) {
			$this->updated;
			if ($old_value!=$telecom->display('xhtml'))
				$history_data=array(
					'Indirect Object'=>'Supplier Main XHTML FAX'
					,'History Abstract'=>_('Supplier Main XHTML FAX Changed')
					,'History Details'=>_('Supplier Main XHTML FAX changed from')." ".$old_value." "._('to').' '.$telecom->display('xhtml')

				);
			$this->add_history($history_data);
		}

	}


	function post_add_history($history_key,$type=false) {

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
			$sql=sprintf("select `Purchase Order Key` from `Purchase Order Dimension` where `Purchase Order Supplier Key`=%d and `Purchase Order Current Dispatch State` in ('In Process','Submitted')  ",$this->id);
			$res=mysql_query($sql);
			$po_keys=array();
			while ($row=mysql_fetch_array($res)) {
				$po_keys[$row['Purchase Order Key']]=$row['Purchase Order Key'];
			}
		}


		if (count($po_keys)==1) {
			$sql=sprintf("update  `Purchase Order Transaction Fact` set `Purchase Order Normalized Quantity`=`Purchase Order Quantity` ,`Purchase Order Normalized Quantity Type`=`Purchase Order Quantity Type` where  `Purchase Order Key`=%d",
				join('',$po_keys)

			);
			mysql_query($sql);
			//print $sql;
			return;
		}
		$supplier_product_keys=array();
		foreach ($po_keys as $po_key) {
			$sql=sprintf("select `Purchase Order Transaction Fact Key`,`Supplier Product ID`,`Purchase Order Quantity`,`Purchase Order Quantity Type` from `Purchase Order Transaction Fact` where `Purchase Order Key`=%d and `Supplier Key`=%d "

				,$po_key
				,$this->id
			);

			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {

				if (array_key_exists($row['Supplier Product ID'],$supplier_product_keys)) {
					$line= $supplier_product_keys[$row['Supplier Product ID']];

					if ($items[$line]['Purchase Order Quantity Type']!=$row['Purchase Order Quantity Type']) {
						$supplier_product=new SupplierProduct($row['Supplier Product ID']);
						$row['Purchase Order Quantity']=$row['Purchase Order Quantity'] *$supplier_product->units_convertion_factor($row['Purchase Order Quantity Type'],$items[$line]['Purchase Order Quantity Type']);
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

				,$item['Purchase Order Quantity']
				,prepare_mysql($item['Purchase Order Quantity Type'])
				,$item['Purchase Order Key']
				,$item['Purchase Order Line']
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

		$sql=sprintf("select count(*) as num from `Purchase Order Dimension` where `Purchase Order Supplier Key`=%d",$this->id);
		$res=mysql_query($sql);

		if ($row=mysql_fetch_array($res)) {
			$number_purchase_orders=$row['num'];
		}

		$sql=sprintf("select count(*) as num from `Purchase Order Dimension` where `Purchase Order Supplier Key`=%d and `Purchase Order Current Dispatch State` not in ('Done','Cancelled')",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$number_open_purchase_orders=$row['num'];
		}

		$sql=sprintf("select count(*) as num from `Supplier Delivery Note Dimension` where `Supplier Delivery Note Supplier Key`=%d",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$number_delivery_notes=$row['num'];
		}

		$sql=sprintf("select count(*) as num from `Supplier Invoice Dimension` where `Supplier Invoice Supplier Key`=%d",$this->id);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$number_invoices=$row['num'];
		}


		$sql=sprintf("update `Supplier Dimension` set `Supplier Purchase Orders`=%d,`Supplier Open Purchase Orders`=%d ,`Supplier Delivery Notes`=%d ,`Supplier Invoices`=%d where `Supplier Key`=%d"
			,$number_purchase_orders
			,$number_open_purchase_orders
			,$number_delivery_notes
			,$number_invoices
			,$this->id);
		mysql_query($sql);

	}

	function get_email_keys() {
		$sql=sprintf("select `Email Key` from `Email Bridge` where  `Subject Type`='Supplier' and `Subject Key`=%d "
			,$this->id );

		$emails=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$emails[$row['Email Key']]= $row['Email Key'];
		}
		return $emails;

	}

	function get_telecom_keys($type='Telephone') {


		$sql=sprintf("select TB.`Telecom Key` from `Telecom Bridge` TB   left join `Telecom Dimension` T on (T.`Telecom Key`=TB.`Telecom Key`)  where  `Telecom Type`=%s and     `Subject Type`='Supplier' and `Subject Key`=%d  group by `Telecom Key` order by `Is Main` desc  "
			,prepare_mysql($type)
			,$this->id);
		$address_keys=array();
		$result=mysql_query($sql);

		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$address_keys[$row['Telecom Key']]= $row['Telecom Key'];
		}
		return $address_keys;

	}

	function get_contacts($args='only active') {

		$extra_args='';
		if (preg_match('/only active|active only/i',$args))
			$extra_args=" and `Is Active`='Yes'";
		if (preg_match('/only main|main only/i',$args))
			$extra_args=" and `Is Main`='Yes'";
		if (preg_match('/only not? active/i',$args))
			$extra_args=" and `Is Active`='No'";
		if (preg_match('/only not? main/i',$args))
			$extra_args=" and `Is Main`='No'";





		$sql=sprintf("select CB.`Contact Key` from `Contact Bridge` CB left join `Contact Dimension` C on (CB.`Contact Key`=C.`Contact Key`)
                     where  `Subject Type`='Supplier' and `Subject Key`=%d %s order by `Is Main`, `Contact File As`  ",$this->id,$extra_args);

		//print $sql;
		$contacts=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$contact=new Contact($row['Contact Key']);
			$contact->set_scope('Supplier',$this->id);
			$contacts[]=$contact;

		}
		return $contacts;
	}

	function associate_company($company_key) {
		$company_keys=$this->get_company_keys();
		if (!array_key_exists($company_key,$company_keys)) {
			$this->create_company_bridge($company_key);

		}
	}

	function create_contact_bridge($contact_key) {
		$sql=sprintf("insert into  `Contact Bridge` (`Contact Key`, `Subject Type`,`Subject Key`,`Is Main`) values (%d,%s,%d,'No')  "
			,$contact_key
			,prepare_mysql('Supplier')
			,$this->id

		);
		mysql_query($sql);
		if (!$this->get_principal_contact_key()) {
			$this->update_principal_contact($contact_key);
		}



	}

	function create_contact_address_bridge($address_key) {
		$sql=sprintf("insert into `Address Bridge` (`Subject Type`,`Address Function`,`Subject Key`,`Address Key`) values ('Supplier','Contact',%d,%d)  ",
			$this->id,
			$address_key
		);
		mysql_query($sql);
		if (
			!$this->get_principal_contact_address_key()
		) {
			$this->update_principal_address($address_key);
		}
	}

	function create_company_bridge($company_key) {
		$sql=sprintf("insert into  `Company Bridge` (`Company Key`, `Subject Type`,`Subject Key`,`Is Main`) values (%d,%s,%d,'No')  "
			,$company_key
			,prepare_mysql('Supplier')
			,$this->id

		);
		mysql_query($sql);
		if (!$this->get_principal_company_key()) {
			$this->update_principal_company($company_key);
		}



	}

	function update_principal_contact_address($address_key) {
		$this->update_principal_address($address_key);
	}

	function update_principal_address($address_key) {


		$parent='Supplier';
		$sql=sprintf("update `Address Bridge`  set `Is Main`='No' where `Subject Type`='$parent' and  `Subject Key`=%d  and `Address Key`=%d",
			$this->id
			,$address_key
		);
		mysql_query($sql);
		$sql=sprintf("update `Address Bridge`  set `Is Main`='Yes' where `Subject Type`='$parent' and  `Subject Key`=%d  and `Address Key`=%d",
			$this->id
			,$address_key
		);
		mysql_query($sql);

		$sql=sprintf("update `Supplier Dimension` set `Supplier Main Address Key`=%d where `Supplier Key`=%d"
			,$address_key
			,$this->id
		);
		mysql_query($sql);


		$address=new Address($address_key);
		$address->editor=$this->editor;
		$address->update_parents('Supplier',($this->new?false:true));
		$this->updated=true;
		$this->new_value=$address_key;
		/*
            $this->data['Supplier Main Address Key']=$address_key;
            $this->data['Supplier Main XHTML Address']=$address->display('xhtml');
            $this->data['Supplier Main Plain Address']=$address->display('plain');
            $this->data['Supplier Main Country Key']=$address->data['Address Country Key'];
            $this->data['Supplier Main Country Code']=$address->data['Address Country Code'];
            $this->data['Supplier Main Country']=$address->data['Address Country Name'];
            $this->data['Supplier Main Location']=$address->display('location');


            $sql=sprintf("update `Supplier Dimension` set `Supplier Main Address Key`=%d,`Supplier Main XHTML Address`=%s ,`Supplier Main Plain Address`=%s,`Supplier Main Country Key`=%d,`Supplier Main Country Code`=%s,`Supplier Main Country`=%s,`Supplier Main Location`=%s where `Supplier Key`=%d",
                         $this->data['Supplier Main Address Key'],
                         prepare_mysql($this->data['Supplier Main XHTML Address']),
                         prepare_mysql($this->data['Supplier Main Plain Address']),
                         $this->data['Supplier Main Country Key'],
                         prepare_mysql($this->data['Supplier Main Country Code']),
                         prepare_mysql($this->data['Supplier Main Country']),
                         prepare_mysql($this->data['Supplier Main Location']),

                         $this->id

                        );
            mysql_query($sql);

            //  $this->get_data('id',$this->id);
            $this->updated=true;
            //$this->msg=$subject->msg;
            $this->new_value=$address_key;
            */

	}



	function update_principal_company($company_key) {
		$main_company_key=$this->get_principal_company_key();

		if ($main_company_key!=$company_key) {
			$company=new Company($company_key);
			$company->editor=$this->editor;
			$sql=sprintf("update `Company Bridge`  set `Is Main`='No' where `Subject Type`='Supplier' and  `Subject Key`=%d  and `Company Key`=%d",
				$this->id
				,$company_key
			);
			mysql_query($sql);
			$sql=sprintf("update `Company Bridge`  set `Is Main`='Yes' where `Subject Type`='Supplier' and  `Subject Key`=%d  and `Company Key`=%d",
				$this->id
				,$company_key
			);
			mysql_query($sql);

			$sql=sprintf("update `Supplier Dimension` set  `Supplier Company Key`=%d where `Supplier Key`=%d",$company->id,$this->id);
			mysql_query($sql);


			$this->data['Supplier Company Key']=$company->id;
			$company->update_parents(($this->new?false:true));

		}

	}


	function update_principal_contact($contact_key) {
		$main_contact_key=$this->get_principal_contact_key();

		if ($main_contact_key!=$contact_key) {
			$contact=new Contact($contact_key);
			$contact->editor=$this->editor;
			$sql=sprintf("update `Contact Bridge`  set `Is Main`='No' where `Subject Type`='Supplier' and  `Subject Key`=%d  and `Contact Key`=%d",
				$this->id
				,$contact_key
			);
			mysql_query($sql);
			$sql=sprintf("update `Contact Bridge`  set `Is Main`='Yes' where `Subject Type`='Supplier' and  `Subject Key`=%d  and `Contact Key`=%d",
				$this->id
				,$contact_key
			);
			mysql_query($sql);

			$sql=sprintf("update `Supplier Dimension` set  `Supplier Main Contact Key`=%d where `Supplier Key`=%d",$contact->id,$this->id);
			mysql_query($sql);


			$this->data['Supplier Main Contact Key']=$contact->id;
			$contact->update_parents(($this->new?false:true));

		}

	}


	function associate_contact($contact_key) {
		$contact_keys=$this->get_contact_keys();
		if (!array_key_exists($contact_key,$contact_keys)) {
			$this->create_contact_bridge($contact_key);

		}
	}





	function get_principal_contact_key() {

		$sql=sprintf("select `Contact Key` from `Contact Bridge` where `Subject Type`='Supplier' and `Subject Key`=%d and `Is Main`='Yes'",$this->id );
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$main_contact_key=$row['Contact Key'];
		} else {
			$main_contact_key=0;
		}

		return $main_contact_key;
	}

	function get_principal_contact_address_key() {
		$main_address_key=0;
		$sql=sprintf("select `Address Key` from `Address Bridge` where `Subject Type`='Supplier' and `Address Function`='Contact' and `Subject Key`=%d and `Is Main`='Yes'",$this->id );
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$main_address_key=$row['Address Key'];
		}
		return $main_address_key;
	}

	function get_principal_company_key() {
		$sql=sprintf("select `Company Key` from `Company Bridge` where `Subject Type`='Supplier' and `Subject Key`=%d and `Is Main`='Yes'",$this->id );
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$main_company_key=$row['Company Key'];
		} else {
			$main_company_key=0;
		}

		return $main_company_key;
	}



	function get_contact_keys() {

		$sql=sprintf("select `Contact Key` from `Contact Bridge` where  `Subject Type`='Supplier' and `Subject Key`=%d   "
			,$this->id
		);
		$contacts=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$contacts[$row['Contact Key']]= $row['Contact Key'];
		}
		return $contacts;
	}


	function get_company_keys() {

		$sql=sprintf("select `Company Key` from `Company Bridge` where  `Subject Type`='Supplier' and `Subject Key`=%d   "
			,$this->id
		);
		$companies=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$companies[$row['Company Key']]= $row['Company Key'];
		}
		return $companies;
	}

	function get_address_keys() {


		$sql=sprintf("select * from `Address Bridge` CB where   `Subject Type`='Supplier' and `Subject Key`=%d  group by `Address Key` order by `Is Main` desc  ",$this->id);
		$address_keys=array();
		$result=mysql_query($sql);

		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$address_keys[$row['Address Key']]= $row['Address Key'];
		}
		return $address_keys;

	}
	function get_main_address_key() {
		return $this->data['Supplier Main Address Key'];
	}

	function get_image_src() {
		return '';
	}

	function get_main_email_user_key() {
		$user_key=0;
		$sql=sprintf("select `User Key` from  `User Dimension` where `User Handle`=%s and `User Type`='Supplier' and `User Parent Key`=%d "

			,prepare_mysql($this->data['Supplier Main Plain Email'])
			,$this->id
		);
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$user_key=$row['User Key'];
		}
		return $user_key;
	}




	function get_principal_email_comment() {
		$comment='';
		if ($this->data['Supplier Main Email Key']) {

			$sql=sprintf("select `Email Description` from `Email Bridge` B where `Email Key`=%d  and `Subject Type`='Supplier' and `Subject Key`=%d ",
				$this->data['Supplier Main Email Key'],
				$this->id
			);
			$result=mysql_query($sql);

			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$comment=$row['Email Description'];
			}
		}

		return $comment;
	}

	function get_principal_telecom_comment($type) {



		$comment='';
		if ($this->data['Supplier Main '.$type.' Key']) {

			$sql=sprintf("select `Telecom Description` from `Telecom Bridge` B where `Telecom Key`=%d  and `Subject Type`='Supplier' and `Subject Key`=%d ",
				$this->data['Supplier Main '.$type.' Key'],
				$this->id
			);
			$result=mysql_query($sql);
			//print $sql;
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$comment=$row['Telecom Description'];
			}
		}

		return $comment;
	}

	function get_other_emails_data() {



		$sql=sprintf("select B.`Email Key`,`Email`,`Email Description`,`User Key` from
        `Email Bridge` B  left join `Email Dimension` E on (E.`Email Key`=B.`Email Key`)
        left join `User Dimension` U on (`User Handle`=E.`Email` and `User Type`='Supplier' and `User Parent Key`=%d )
        where  `Subject Type`='Supplier' and `Subject Key`=%d "
			,$this->id
			,$this->id
		);

		$email_keys=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['Email Key']!=$this->data['Supplier Main Email Key'])
				$email_keys[$row['Email Key']]= array(
					'email'=>$row['Email'],
					'key'=>$row['Email Key'],
					'xhtml'=>'<a href="mailto:'.$row['Email'].'">'.$row['Email'].'</a>',
					'label'=>$row['Email Description'],
					'user_key'=>$row['User Key']
				);
		}
		return $email_keys;

	}

	function get_other_faxes_data() {
		return $this->get_other_telecoms_data('FAX');
	}

	function get_other_mobiles_data() {
		return $this->get_other_telecoms_data('Mobile');
	}
	function get_other_telephones_data() {
		return $this->get_other_telecoms_data('Telephone');
	}

	function get_other_telecoms_data($type='Telephone') {

		$sql=sprintf("select B.`Telecom Key`,`Telecom Description` from `Telecom Bridge` B left join `Telecom Dimension` T on (T.`Telecom Key`=B.`Telecom Key`) where `Telecom Type`=%s  and `Subject Type`='Supplier' and `Subject Key`=%d ",
			prepare_mysql($type),
			$this->id
		);
		//print $sql;
		$telecom_keys=array();
		$result=mysql_query($sql);
		while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			if ($row['Telecom Key']!=$this->data["Supplier Main $type Key"]) {

				$telecom=new Telecom($row['Telecom Key']);

				$telecom_keys[$row['Telecom Key']]= array(
					'number'=>$telecom->display('plain'),
					'xhtml'=>$telecom->display('xhtml'),
					'label'=>$row['Telecom Description']
				);

			}
		}
		return $telecom_keys;

	}

	function get_main_address_fuzzy_type() {
		$fuzzy_type='All';
		$address=new Address($this->data['Supplier Main Address Key']);
		if ($address->id) {
			$fuzzy_type=$address->data['Address Fuzzy Type'];
		}

		return $fuzzy_type;
	}

}


?>
