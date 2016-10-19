<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 17 May 2016 at 10:56:11 GMT+7, Bandung, Indonesia
 Copyright (c) 2016, Inikoo

 Version 3

*/


include_once 'class.Subject.php';


class SubjectSupplier extends Subject {

	function create_order($_data) {

		include_once 'class.Staff.php';
		include_once 'class.Warehouse.php';

		$staff=new Staff($_data['user']->get('User Parent Key'));
		$warehouse=new Warehouse($_data['warehouse_key']);

		$order_data=array(
			'Purchase Order Parent'=>$this->table_name,
			'Purchase Order Parent Key'=>$this->id,
			'Purchase Order Parent Name'=>$this->get('Name'),
			'Purchase Order Parent Code'=>$this->get('Code'),
			'Purchase Order Parent Contact Name'=>$this->get('Main Contact Name'),
			'Purchase Order Parent Email'=>$this->get('Main Plain Email'),
			'Purchase Order Parent Telephone'=>$this->get('Preferred Contact Number Formatted Number'),
			'Purchase Order Parent Address'=>$this->get('Contact Address Formatted'),

			'Purchase Order Currency Code'=>$this->get('Default Currency Code'),
			'Purchase Order Incoterm'=>$this->get('Default Incoterm'),
			'Purchase Order Port of Import'=>$this->get('Default Port of Import'),
			'Purchase Order Port of Export'=>$this->get('Default Port of Export'),


			'Purchase Order Warehouse Key'=>$warehouse->data['Warehouse Key'],
			'Purchase Order Warehouse Code'=>$warehouse->data['Warehouse Code'],
			'Purchase Order Warehouse Name'=>$warehouse->data['Warehouse Name'],
			'Purchase Order Warehouse Address'=>$warehouse->data['Warehouse Address'],
			'Purchase Order Warehouse Company Name'=>$warehouse->data['Warehouse Company Name'],
			'Purchase Order Warehouse Company Number'=>$warehouse->data['Warehouse Company Number'],
			'Purchase Order Warehouse VAT Number'=>$warehouse->data['Warehouse VAT Number'],
			'Purchase Order Warehouse Telephone'=>$warehouse->data['Warehouse Telephone'],
			'Purchase Order Warehouse Email'=>$warehouse->data['Warehouse Email'],
			'Purchase Order Account Number'=>$this->data['Supplier Account Number'],

			'Purchase Order Terms and Conditions'=>$this->get('Default PO Terms and Conditions'),
			'Purchase Order Main Buyer Key'=>$staff->id,
			'Purchase Order Main Buyer Name'=>$staff->get('Staff Name'),
			'editor'=>$this->editor
		);



		if ($this->get('Show Warehouse TC in PO')=='Yes') {

			if ($order_data['Purchase Order Terms and Conditions']!='') {
				$order_data['Purchase Order Terms and Conditions'].='<br><br>';
			}
			$order_data['Purchase Order Terms and Conditions'].=$warehouse->data['Warehouse Default PO Terms and Conditions'];
		}








		$order=new PurchaseOrder('new', $order_data);



		if ($order->error) {
			$this->error=true;
			$this->msg=$order->msg;

		}




		return $order;

	}


	function update_orders() {
		$number_purchase_orders=0;
		$number_open_purchase_orders=0;
		$number_delivery_notes=0;
		$number_invoices=0;

		$sql=sprintf("select count(*) as num from `Purchase Order Dimension` where `Purchase Order Parent`=%s and `Purchase Order Parent Key`=%d",
			prepare_mysql($this->table_name),
			$this->id);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_purchase_orders=$row['num'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$sql=sprintf("select count(*) as num from `Purchase Order Dimension` where `Purchase Order Parent`=%s and  `Purchase Order Parent Key`=%d and `Purchase Order State` not in ('Done','Cancelled')",
			prepare_mysql($this->table_name),
			$this->id);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_open_purchase_orders=$row['num'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$sql=sprintf("select count(*) as num from `Supplier Delivery Dimension` where `Supplier Delivery Parent`=%s and  `Supplier Delivery Parent Key`=%d",
			prepare_mysql($this->table_name),
			$this->id);
		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_delivery_notes=$row['num'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}




		$sql=sprintf("update `%s Dimension` set `%s Purchase Orders`=%d,`%s Open Purchase Orders`=%d ,`%s Supplier Deliveries`=%d  where `%s Key`=%d",
			$this->table_name,
			$this->table_name,
			$number_purchase_orders,
			$this->table_name,
			$number_open_purchase_orders,
			$this->table_name,
			$number_delivery_notes,
			$this->table_name,

			$this->table_name,
			$this->id);
		$this->db->exec($sql);

	}


	function get_user_data() {

		$sql=sprintf('select * from `User Dimension` where `User Type`=%s and `User Parent Key`=%d ',
			prepare_mysql($this->table_name),
			$this->id);
		if ($row = $this->db->query($sql)->fetch()) {

			foreach ($row as $key=>$value) {
				$this->data['Supplier '.$key]=$value;
			}
		}



	}


	function get_users($scope='keys') {

		if ($scope=='objects') {
			include_once 'class.User.php';
		}


		$users=array();
		$sql=sprintf("select `User Key` from `User Dimension` whereUser Type`=%s and `User Parent Key`=%d  ",
			prepare_mysql($this->table_name),
			$this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {

				if ($scope=='objects') {

					$users[$row['User Key']]=new User($row['User Key']);

				}else {
					$users[$row['User Key']]=$row['User Key'];
				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		return $users;

	}






	function create_user($data) {



		if (isset($this->data[$this->table_name.' User Key']) and $this->data[$this->table_name.' User Key']) {
			$this->create_user_error=true;
			if ($this->table_name=='Supplier')
				$this->create_user_msg=_('Supplier has already a system user');
			else
				$this->create_user_msg=_('Agent has already a system user');

			$this->user=false;
			return false;
		}


		$data['editor']=$this->editor;

		if (!array_key_exists('User Handle', $data) or $data['User Handle']=='' ) {
			$this->create_user_error=true;
			$this->create_user_msg=_('User login must be provided');
			$this->user=false;
			return false;

		}

		if (!array_key_exists('User Password', $data) or $data['User Password']=='' ) {
			include_once 'utils/password_functions.php';
			$data['User Password']=hash('sha256', generatePassword(8, 3));
		}

		$data['User Type']=$this->table_name;


		$data['User Parent Key']=$this->id;
		$data['User Alias']=$this->get('Name');
		$user= new User('find', $data, 'create');
		$this->get_user_data();
		$this->create_user_error=$user->error;
		$this->create_user_msg=$user->msg;
		$this->user=$user;

		return $user;


	}


	function get_subject_supplier_common($key) {

		global $account;
		
		if (!$this->id)return array(false, false);;

		list($got, $result)=$this->get_subject_common($key);
		if ($got)return array(true, $result);




		switch ($key) {


		case 'Supplier Number Todo Parts':
		case 'Agent Number Todo Parts':

			if ($this->table_name=='Supplier Production') {
				$table_name='Supplier';
			}else {
				$table_name=$this->table_name;
			}

			return array(true, $this->data[$table_name.' Number Critical Parts']+$this->data[$table_name.' Number Out Of Stock Parts']);
			breaak;
		case('Valid From'):
		case('Valid To'):
			if ($this->get($this->table_name.' '.$key)=='') {
				return array(true, '');
			}else {
				return array(true, strftime("%a, %e %b %y", strtotime($this->get($this->table_name.' '.$key).' +0:00')));
			}
			break;
		case ('Default Currency'):

			if ($this->data[$this->table_name.' Default Currency Code']!='') {



				$options_currencies=array();
				$sql=sprintf("select `Currency Code`,`Currency Name`,`Currency Symbol` from kbase.`Currency Dimension` where `Currency Code`=%s",
					prepare_mysql($this->data[$this->table_name.' Default Currency Code']));



				if ($result=$this->db->query($sql)) {
					if ($row = $result->fetch()) {
						return array(true, sprintf("%s (%s)", $row['Currency Name'], $row['Currency Code']));
					}else {
						return array(true, $this->data[$this->table_name.' Default Currency Code']);
					}
				}else {
					print_r($error_info=$this->db->errorInfo());
					exit;
				}
			}else {
				return array(true, '');
			}

			break;
		case 'Average Delivery Days':
			if ($this->data[$this->table_name.' Average Delivery Days']=='') return array(true, '');
			return array(true, number($this->data[$this->table_name.' Average Delivery Days']));
			break;
		case 'Delivery Time':


			include_once 'utils/natural_language.php';
			if ($this->get($this->table_name.' Average Delivery Days')=='') {
				return array(true, '<span class="italic very_discreet">'._('Unknown').'</span>');
			}else {
				return array(true, seconds_to_natural_string(24*3600*$this->get($this->table_name.' Average Delivery Days')));
			}
			break;


		case 'Products Origin Country Code':
			if ($this->get($this->table_name.' Products Origin Country Code')) {
				include_once 'class.Country.php';
				$country=new Country('code', $this->data[$this->table_name.' Products Origin Country Code']);
				return array(true, _($country->get('Country Name')).' ('.$country->get('Country Code').')');
			}else {
				return array(true, '');
			}

			break;


		case('Purchase Orders'):
		case('Open Purchase Orders'):
		case('Delivery Notes'):
		case('Invoices'):
			return array(true, number($this->data[$this->table_name.' '.$key]));
			break;

		case('Formatted ID'):
		case("ID"):
			return array(true, $this->get_formatted_id());
		case('Stock Value'):

			if (!is_numeric($this->data[$this->table_name.' Stock Value']))
				return array(true, _('Unknown'));
			else
				return array(true, money($this->data[$this->table_name.' Stock Value']));
			break;

		case('Parent Skip Inputting'):
		case('Parent Skip Mark as Dispatched'):
		case('Parent Skip Mark as Received'):
		case('Parent Skip Checking'):
		case('Parent Automatic Placement Location'):

			$field=preg_replace('/^Parent/', $this->table_name, $key);

			return array(true, $this->data[$field]);

		default;

			if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit)$/', $key)) {

				$field=$this->table_name.' '.$key;

				return array(true,money($this->data[$field], $account->get('Account Currency')));
			}
			if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Minify$/', $key)) {

				$field=$this->table_name.' '.preg_replace('/ Minify$/', '', $key);

				$suffix='';
				$fraction_digits='NO_FRACTION_DIGITS';
				if ($this->data[$field]>=10000) {
					$suffix='K';
					$_amount=$this->data[$field]/1000;
				}elseif ($this->data[$field]>100 ) {
					$fraction_digits='SINGLE_FRACTION_DIGITS';
					$suffix='K';
					$_amount=$this->data[$field]/1000;
				}else {
					$_amount=$this->data[$field];
				}

				$amount= money($_amount, $account->get('Account Currency'), $locale=false, $fraction_digits).$suffix;

				return array(true,$amount);
			}
			if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|4|2|Year To|Quarter To|Month To|Today|Week To).*(Amount|Profit) Soft Minify$/', $key)) {

				$field=$this->table_name.' '.preg_replace('/ Soft Minify$/', '', $key);


				$suffix='';
				$fraction_digits='NO_FRACTION_DIGITS';
				$_amount=$this->data[$field];

				$amount= money($_amount, $account->get('Account Currency'), $locale=false, $fraction_digits).$suffix;

				return array(true, $amount);
			}

			if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired)$/', $key) or $key=='Current Stock'  ) {

				$field=$this->table_name.' '.$key;

				return array(true,number($this->data[$field]));
			}


			if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired) Minify$/', $key) or $key=='Current Stock'  ) {

				$field=$this->table_name.' '.preg_replace('/ Minify$/', '', $key);

				$suffix='';
				$fraction_digits=0;
				if ($this->data[$field]>=10000) {
					$suffix='K';
					$_number=$this->data[$field]/1000;
				}elseif ($this->data[$field]>100 ) {
					$fraction_digits=1;
					$suffix='K';
					$_number=$this->data[$field]/1000;
				}else {
					$_number=$this->data[$field];
				}

				return array(true, number($_number, $fraction_digits).$suffix);
			}
			if (preg_match('/^(Last|Yesterday|Total|1|10|6|3|2|4|Year To|Quarter To|Month To|Today|Week To).*(Given|Lost|Required|Sold|Dispatched|Broken|Acquired) Soft Minify$/', $key) or $key=='Current Stock'  ) {

				$field=$this->table_name.' '.preg_replace('/ Soft Minify$/', '', $key);

				$suffix='';
				$fraction_digits=0;
				$_number=$this->data[$field];

				return array(true, number($_number, $fraction_digits).$suffix);
			}


		}

		return array(false, false);

	}


	function update_sales($interval) {

		include_once 'utils/date_functions.php';
		list($db_interval, $from_date, $to_date, $from_date_1yb, $to_date_1yb)=calculate_interval_dates($this->db, $interval);



		$sales_data=$this->get_sales_data($from_date, $to_date);


		$data_to_update=array(
			$this->table_name." $db_interval Acc Customers"=>$sales_data['customers'],
			$this->table_name." $db_interval Acc Repeat Customers"=>$sales_data['repeat_customers'],
			$this->table_name." $db_interval Acc Deliveries"=>$sales_data['deliveries'],
			$this->table_name." $db_interval Acc Profit"=>$sales_data['profit'],
			$this->table_name." $db_interval Acc Invoiced Amount"=>$sales_data['invoiced_amount'],
			$this->table_name." $db_interval Acc Required"=>$sales_data['required'],
			$this->table_name." $db_interval Acc Dispatched"=>$sales_data['dispatched'],
			$this->table_name." $db_interval Acc Keeping Days"=>$sales_data['keep_days'],
			$this->table_name." $db_interval Acc With Stock Days"=>$sales_data['with_stock_days'],
		);



		$this->update( $data_to_update, 'no_history');

		if ($from_date_1yb) {


			$sales_data=$this->get_sales_data($from_date_1yb, $to_date_1yb);


			$data_to_update=array(

				$this->table_name." $db_interval Acc 1YB Customers"=>$sales_data['customers'],
				$this->table_name." $db_interval Acc 1YB Repeat Customers"=>$sales_data['repeat_customers'],
				$this->table_name." $db_interval Acc 1YB Deliveries"=>$sales_data['deliveries'],
				$this->table_name." $db_interval Acc 1YB Profit"=>$sales_data['profit'],
				$this->table_name." $db_interval Acc 1YB Invoiced Amount"=>$sales_data['invoiced_amount'],
				$this->table_name." $db_interval Acc 1YB Required"=>$sales_data['required'],
				$this->table_name." $db_interval Acc 1YB Dispatched"=>$sales_data['dispatched'],
				$this->table_name." $db_interval Acc 1YB Keeping Days"=>$sales_data['keep_days'],
				$this->table_name." $db_interval Acc 1YB With Stock Days"=>$sales_data['with_stock_days'],

			);
			$this->update( $data_to_update, 'no_history');


		}


	}

	function update_previous_years_data() {

		$data_1y_ago=$this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-1 year')), date('Y-01-01 00:00:00'));
		$data_2y_ago=$this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-2 year')), date('Y-01-01 00:00:00', strtotime('-1 year')));
		$data_3y_ago=$this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-3 year')), date('Y-01-01 00:00:00', strtotime('-2 year')));
		$data_4y_ago=$this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-4 year')), date('Y-01-01 00:00:00', strtotime('-3 year')));
		$data_5y_ago=$this->get_sales_data(date('Y-01-01 00:00:00', strtotime('-5 year')), date('Y-01-01 00:00:00', strtotime('-4 year')));




		$data_to_update=array(
			$this->table_name." 1 Year Ago Customers"=>$data_1y_ago['customers'],
			$this->table_name." 1 Year Ago Repeat Customers"=>$data_1y_ago['repeat_customers'],
			$this->table_name." 1 Year Ago Deliveries"=>$data_1y_ago['deliveries'],
			$this->table_name." 1 Year Ago Profit"=>$data_1y_ago['profit'],
			$this->table_name." 1 Year Ago Invoiced Amount"=>$data_1y_ago['invoiced_amount'],
			$this->table_name." 1 Year Ago Required"=>$data_1y_ago['required'],
			$this->table_name." 1 Year Ago Dispatched"=>$data_1y_ago['dispatched'],
			$this->table_name." 1 Year Ago Keeping Day"=>$data_1y_ago['keep_days'],
			$this->table_name." 1 Year Ago With Stock Days"=>$data_1y_ago['with_stock_days'],

			$this->table_name." 2 Year Ago Customers"=>$data_2y_ago['customers'],
			$this->table_name." 2 Year Ago Repeat Customers"=>$data_2y_ago['repeat_customers'],
			$this->table_name." 2 Year Ago Deliveries"=>$data_2y_ago['deliveries'],
			$this->table_name." 2 Year Ago Profit"=>$data_2y_ago['profit'],
			$this->table_name." 2 Year Ago Invoiced Amount"=>$data_2y_ago['invoiced_amount'],
			$this->table_name." 2 Year Ago Required"=>$data_2y_ago['required'],
			$this->table_name." 2 Year Ago Dispatched"=>$data_2y_ago['dispatched'],
			$this->table_name." 2 Year Ago Keeping Day"=>$data_2y_ago['keep_days'],
			$this->table_name." 2 Year Ago With Stock Days"=>$data_2y_ago['with_stock_days'],

			$this->table_name." 3 Year Ago Customers"=>$data_3y_ago['customers'],
			$this->table_name." 3 Year Ago Repeat Customers"=>$data_3y_ago['repeat_customers'],
			$this->table_name." 3 Year Ago Deliveries"=>$data_3y_ago['deliveries'],
			$this->table_name." 3 Year Ago Profit"=>$data_3y_ago['profit'],
			$this->table_name." 3 Year Ago Invoiced Amount"=>$data_3y_ago['invoiced_amount'],
			$this->table_name." 3 Year Ago Required"=>$data_3y_ago['required'],
			$this->table_name." 3 Year Ago Dispatched"=>$data_3y_ago['dispatched'],
			$this->table_name." 3 Year Ago Keeping Day"=>$data_3y_ago['keep_days'],
			$this->table_name." 3 Year Ago With Stock Days"=>$data_3y_ago['with_stock_days'],

			$this->table_name." 4 Year Ago Customers"=>$data_4y_ago['customers'],
			$this->table_name." 4 Year Ago Repeat Customers"=>$data_4y_ago['repeat_customers'],
			$this->table_name." 4 Year Ago Deliveries"=>$data_4y_ago['deliveries'],
			$this->table_name." 4 Year Ago Profit"=>$data_4y_ago['profit'],
			$this->table_name." 4 Year Ago Invoiced Amount"=>$data_4y_ago['invoiced_amount'],
			$this->table_name." 4 Year Ago Required"=>$data_4y_ago['required'],
			$this->table_name." 4 Year Ago Dispatched"=>$data_4y_ago['dispatched'],
			$this->table_name." 4 Year Ago Keeping Day"=>$data_4y_ago['keep_days'],
			$this->table_name." 4 Year Ago With Stock Days"=>$data_4y_ago['with_stock_days'],

			$this->table_name." 5 Year Ago Customers"=>$data_5y_ago['customers'],
			$this->table_name." 5 Year Ago Repeat Customers"=>$data_5y_ago['repeat_customers'],
			$this->table_name." 5 Year Ago Deliveries"=>$data_5y_ago['deliveries'],
			$this->table_name." 5 Year Ago Profit"=>$data_5y_ago['profit'],
			$this->table_name." 5 Year Ago Invoiced Amount"=>$data_5y_ago['invoiced_amount'],
			$this->table_name." 5 Year Ago Required"=>$data_5y_ago['required'],
			$this->table_name." 5 Year Ago Dispatched"=>$data_5y_ago['dispatched'],
			$this->table_name." 5 Year Ago Keeping Day"=>$data_5y_ago['keep_days'],
			$this->table_name." 5 Year Ago With Stock Days"=>$data_5y_ago['with_stock_days'],


		);
		$this->update( $data_to_update, 'no_history');






	}

	function update_previous_quarters_data() {


		include_once 'utils/date_functions.php';


		foreach (range(1, 4) as $i) {
			$dates=get_previous_quarters_dates($i);
			$dates_1yb=get_previous_quarters_dates($i+4);


			$sales_data=$this->get_sales_data($dates['start'], $dates['end']);
			$sales_data_1yb=$this->get_sales_data($dates_1yb['start'], $dates_1yb['end']);

			$data_to_update=array(
				$this->table_name." $i Quarter Ago Customers"=>$sales_data['customers'],
				$this->table_name." $i Quarter Ago Repeat Customers"=>$sales_data['repeat_customers'],
				$this->table_name." $i Quarter Ago Deliveries"=>$sales_data['deliveries'],
				$this->table_name." $i Quarter Ago Profit"=>$sales_data['profit'],
				$this->table_name." $i Quarter Ago Invoiced Amount"=>$sales_data['invoiced_amount'],
				$this->table_name." $i Quarter Ago Required"=>$sales_data['required'],
				$this->table_name." $i Quarter Ago Dispatched"=>$sales_data['dispatched'],
				$this->table_name." $i Quarter Ago Keeping Day"=>$sales_data['keep_days'],
				$this->table_name." $i Quarter Ago With Stock Days"=>$sales_data['with_stock_days'],

				$this->table_name." $i Quarter Ago 1YB Customers"=>$sales_data_1yb['customers'],
				$this->table_name." $i Quarter Ago 1YB Repeat Customers"=>$sales_data_1yb['repeat_customers'],
				$this->table_name." $i Quarter Ago 1YB Deliveries"=>$sales_data_1yb['deliveries'],
				$this->table_name." $i Quarter Ago 1YB Profit"=>$sales_data_1yb['profit'],
				$this->table_name." $i Quarter Ago 1YB Invoiced Amount"=>$sales_data_1yb['invoiced_amount'],
				$this->table_name." $i Quarter Ago 1YB Required"=>$sales_data_1yb['required'],
				$this->table_name." $i Quarter Ago 1YB Dispatched"=>$sales_data_1yb['dispatched'],
				$this->table_name." $i Quarter Ago 1YB Keeping Day"=>$sales_data_1yb['keep_days'],
				$this->table_name." $i Quarter Ago 1YB With Stock Days"=>$sales_data_1yb['with_stock_days'],
			);
			$this->update( $data_to_update, 'no_history');
		}

	}

	function get_customers_total_data($part_skus) {

		$repeat_customers=0;


		$sql=sprintf('select count(`Customer Part Customer Key`) as num  from `Customer Part Bridge` where `Customer Part Delivery Notes`>1 and `Customer Part Part SKU` in (%s)    ',
			$part_skus
		);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$repeat_customers=$row['num'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}



		return $repeat_customers;

	}

	function get_sales_data($from_date, $to_date) {

		$sales_data=array(
			'invoiced_amount'=>0,
			'profit'=>0,
			'required'=>0,
			'dispatched'=>0,
			'deliveries'=>0,
			'customers'=>0,
			'repeat_customers'=>0,
			'keep_days'=>0,
			'with_stock_days'=>0,

		);

		$part_skus=$this->get_part_skus();

		if ($part_skus!='') {

			if ($from_date=='' and  $to_date=='') {
				$sales_data['repeat_customers']=$this->get_customers_total_data($part_skus);
			}



			$sql=sprintf("select count(distinct `Delivery Note Customer Key`) as customers, count( distinct ITF.`Delivery Note Key`) as deliveries, round(ifnull(sum(`Amount In`),0),2) as invoiced_amount,round(ifnull(sum(`Amount In`+`Inventory Transaction Amount`),0),2) as profit,round(ifnull(sum(`Inventory Transaction Quantity`),0),1) as dispatched,round(ifnull(sum(`Required`),0),1) as required from `Inventory Transaction Fact` ITF  left join `Delivery Note Dimension` DN on (DN.`Delivery Note Key`=ITF.`Delivery Note Key`) where `Inventory Transaction Type` like 'Sale' and `Part SKU` in (%s) %s %s" ,
				$part_skus,
				($from_date?sprintf('and  `Date`>=%s', prepare_mysql($from_date)):''),
				($to_date?sprintf('and `Date`<%s', prepare_mysql($to_date)):'')
			);

			//print "$sql\n";

			if ($result=$this->db->query($sql)) {
				if ($row = $result->fetch()) {
					$sales_data['customers']=$row['customers'];
					$sales_data['invoiced_amount']=$row['invoiced_amount'];
					$sales_data['profit']=$row['profit'];
					$sales_data['dispatched']=-1.0*$row['dispatched'];
					$sales_data['required']=$row['required'];
					$sales_data['deliveries']=$row['deliveries'];
				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}


		}

		return $sales_data;

	}



}


?>
