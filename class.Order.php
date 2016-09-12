<?php
/*
  File: Ordes.php

  This file contains the Order Class

  About:
  Autor: Raul Perusquia <rulovico@gmail.com>

  Copyright (c) 2009, Inikoo

  Version 2.0
*/
include_once 'class.DB_Table.php';


include_once 'class.Customer.php';
include_once 'class.Store.php';
include_once 'class.Ship_To.php';
include_once 'class.Billing_To.php';

include_once 'class.Invoice.php';

include_once 'class.DeliveryNote.php';
include_once 'class.TaxCategory.php';
include_once 'class.CurrencyExchange.php';
require_once 'utils/order_functions.php';
require_once 'utils/natural_language.php';


class Order extends DB_Table {
	//Public $data = array ();
	// Public $items = array ();
	// Public $status_names = array ();
	// Public $id = false;
	// Public $tipo;
	// Public $staus = 'new';

	var $amount_off_allowance_data=false;
	var $ghost_order=false;
	var $update_stock=true;
	public $skip_update_product_sales=false;
	var $skip_update_after_individual_transaction=true;
	function __construct($arg1 = false, $arg2 = false) {

		global $db;
		$this->db=$db;


		$this->table_name='Order';
		$this->ignore_fields=array('Order Key');
		$this->update_customer=true;

		$this->status_names = array (0 => 'new' );
		if (preg_match( '/new/i', $arg1 )) {
			$this->create_order ( $arg2 );

			return;
		}
		if (is_numeric( $arg1 )) {
			$this->get_data ( 'id', $arg1 );
			return;
		}
		$this->get_data ( $arg1, $arg2 );

	}


	/*   function set_adjust_amounts($tipo,$amount){ */
	/*     $this->adjusts[$tipo]=$amount; */
	/*   } */
	/*   function get_adjust_amounts($tipo,$amount){ */
	/*     if(array_key_exists($tipo,$this->adjusts)) */
	/*       return $this->adjusts[$tipo]; */
	/*     else */
	/*       false; */
	/*   } */


	function add_credit_no_product_transaction($credit_transaction_data) {

		$order_date=$this->data['Order Date'];

		$sql=sprintf("insert into `Order No Product Transaction Fact` (
  					`Transaction Gross Amount`,`Transaction Net Amount`,`Transaction Tax Amount`,
  					`Affected Order Key`,`Order Key`,`Order Date`,`Transaction Type`,`Transaction Description`,`Tax Category Code`,`Currency Code`)
  				values (%f,%f,%s,%d,%s,%s,%s,%s,%s) ",

			$credit_transaction_data['Transaction Net Amount'],
			$credit_transaction_data['Transaction Net Amount'],
			$credit_transaction_data['Transaction Tax Amount'],
			prepare_mysql($credit_transaction_data['Affected Order Key']),
			$this->id,
			prepare_mysql($order_date),
			prepare_mysql('Credit'),
			prepare_mysql($credit_transaction_data['Transaction Description']),

			prepare_mysql($credit_transaction_data['Tax Category Code']),

			prepare_mysql($this->data['Order Currency'])

		);
		//print $sql;
		mysql_query($sql);
		$this->update_totals();
	}


	function update_credit_no_product_transaction($credit_transaction_data) {


		$sql=sprintf("update `Order No Product Transaction Fact` set `Transaction Outstanding Net Amount Balance`=%f,`Transaction Outstanding Tax Amount Balance`=%f,`Transaction Net Amount`=%f,`Transaction Tax Amount`=%f,`Transaction Description`=%s,`Tax Category Code`=%s where `Order No Product Transaction Fact Key`=%d and `Order Key`=%d ",
			$credit_transaction_data['Transaction Net Amount'],
			$credit_transaction_data['Transaction Tax Amount'],
			$credit_transaction_data['Transaction Net Amount'],
			$credit_transaction_data['Transaction Tax Amount'],
			prepare_mysql($credit_transaction_data['Transaction Description']),

			prepare_mysql($credit_transaction_data['Tax Category Code']),
			$credit_transaction_data['Order No Product Transaction Fact Key'],
			$this->id


		);
		mysql_query($sql);
		$this->update_totals();
	}


	function delete_credit_transaction($transaction_key) {
		$sql=sprintf("delete from `Order No Product Transaction Fact`  where `Order No Product Transaction Fact Key`=%d and `Order Key`=%d ",
			$transaction_key,
			$this->id


		);
		//print $sql;
		mysql_query($sql);
		$this->update_totals();
	}


	function create_refund($data=false) {


		$store=new Store($this->data['Order Store Key']);


		$invoice_public_id='';


		if ($store->data['Store Refund Public ID Method']=='Same Invoice ID') {

			foreach ($this->get_invoices_objects() as $_invoice) {
				if ($_invoice->data['Invoice Type']=='Invoice') {
					$invoice_public_id=$_invoice->data['Invoice Public ID'];
				}
			}



			if ($invoice_public_id=='') {
				//Next Invoice ID





				if ($store->data['Store Next Invoice Public ID Method']=='Invoice Public ID') {

					$sql=sprintf("UPDATE `Store Dimension` SET `Store Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Store Invoice Last Invoice Public ID` + 1) where `Store Key`=%d"
						, $this->data['Order Store Key']);
					mysql_query($sql);
					$invoice_public_id=sprintf($store->data['Store Invoice Public ID Format'], mysql_insert_id());

				}elseif ($store->data['Store Next Invoice Public ID Method']=='Order ID') {

					$sql=sprintf("UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) where `Store Key`=%d"
						, $this->data['Order Store Key']);
					mysql_query($sql);
					$invoice_public_id=mysql_insert_id();
					$invoice_public_id=sprintf($store->data['Store Order Public ID Format'], mysql_insert_id());


				}else {

					$sqla=sprintf("UPDATE `Account Dimension` SET `Account Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Account Invoice Last Invoice Public ID` + 1) where `Account Key`=1");
					mysql_query($sqla);
					$public_id=mysql_insert_id();
					include_once 'class.Account.php';
					$account=new Account();
					$invoice_public_id=sprintf($account->data['Account Invoice Public ID Format'], $public_id);

				}


			}


		}
		elseif ($store->data['Store Refund Public ID Method']=='Account Wide Own Index') {
			include_once 'class.Account.php';
			$account=new Account();
			$sql=sprintf("UPDATE `Account Dimension` SET `Account Invoice Last Refund Public ID` = LAST_INSERT_ID(`Account Invoice Last Refund Public ID` + 1) where `Account Key`=1");
			mysql_query($sql);
			$invoice_public_id=sprintf($account->data['Account Refund Public ID Format'], mysql_insert_id());


		}
		elseif ($store->data['Store Refund Public ID Method']=='Store Own Index') {

			$sql=sprintf("UPDATE `Store Dimension` SET `Store Invoice Last Refund Public ID` = LAST_INSERT_ID(`Store Invoice Last Refund Public ID` + 1) where `Store Key`=%d"
				, $this->data['Order Store Key']);
			mysql_query($sql);
			$invoice_public_id=sprintf($store->data['Store Refund Public ID Format'], mysql_insert_id());


		}
		else { //Next Invoice ID





			if ($store->data['Store Next Invoice Public ID Method']=='Invoice Public ID') {

				$sql=sprintf("UPDATE `Store Dimension` SET `Store Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Store Invoice Last Invoice Public ID` + 1) where `Store Key`=%d"
					, $this->data['Order Store Key']);
				mysql_query($sql);
				$invoice_public_id=sprintf($store->data['Store Invoice Public ID Format'], mysql_insert_id());

			}elseif ($store->data['Store Next Invoice Public ID Method']=='Order ID') {

				$sql=sprintf("UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) where `Store Key`=%d"
					, $this->data['Order Store Key']);
				mysql_query($sql);
				$invoice_public_id=mysql_insert_id();
				$invoice_public_id=sprintf($store->data['Store Order Public ID Format'], mysql_insert_id());


			}else {

				$sqla=sprintf("UPDATE `Account Dimension` SET `Account Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Account Invoice Last Invoice Public ID` + 1) where `Account Key`=1");
				mysql_query($sqla);
				$public_id=mysql_insert_id();
				include_once 'class.Account.php';
				$account=new Account();
				$invoice_public_id=sprintf($account->data['Account Invoice Public ID Format'], $public_id);

			}

		}

		if ($invoice_public_id!='') {
			$invoice_public_id= $this->get_refund_public_id($invoice_public_id.$store->data['Store Refund Suffix']);
		}

		$refund_data=array(
			'Invoice Customer Key'=>$this->data['Order Customer Key'],
			'Invoice Store Key'=>$this->data['Order Store Key'],
			'Order Key'=>$this->id

		);


		if ($invoice_public_id!='') {
			$refund_data['Invoice Public ID']= $invoice_public_id;
		}




		if (!$data)$data=array();

		if (array_key_exists('Invoice Metadata', $data))$refund_data['Invoice Metadata']=$data['Invoice Metadata'];
		if (array_key_exists('Invoice Date', $data))$refund_data['Invoice Date']=$data['Invoice Date'];
		if (array_key_exists('Invoice Tax Code', $data))$refund_data['Invoice Tax Code']=$data['Invoice Tax Code'];

		$refund=new Invoice('create refund', $refund_data);




		return $refund;
	}


	function create_order($data) {

		global $account;

		if (isset($data['editor'])) {
			foreach ($data['editor'] as $key=>$value) {
				if (array_key_exists($key, $this->editor))
					$this->editor[$key]=$value;

			}
		}
		$this->editor=$data ['editor'];


		$this->data ['Order Type'] = $data ['Order Type'];
		if (isset($data['Order Date'])) {
			$this->data ['Order Date'] =$data['Order Date'];

		}else {
			$this->data ['Order Date'] = gmdate('Y-m-d H:i:s');

		}
		$this->data ['Order Created Date']=$this->data ['Order Date'];


		$this->data['Order Tax Code']='';
		$this->data['Order Tax Rate']=0;
		$this->data['Order Tax Name']='';
		$this->data['Order Tax Operations']='';
		$this->data['Order Tax Selection Type']='';

		$this->set_data_from_customer($data['Customer Key']);


		if (isset($data['Order Tax Code'])) {

			$tax_cat=new TaxCategory('code', $data['Order Tax Code']);
			if ($tax_cat->id) {
				$this->data['Order Tax Code']=$tax_cat->data['Tax Category Code'];
				$this->data['Order Tax Rate']=$tax_cat->data['Tax Category Rate'];
				$this->data['Order Tax Name']=$tax_cat->data['Tax Category Name'];
				$this->data['Order Tax Operations']='';
				$this->data['Order Tax Selection Type']='set';
			}else {
				$this->error=true;
				$this->msg='Tax code not found';
				exit();
			}
		}else {
			$tax_code_data=$this->get_tax_data();

			$this->data['Order Tax Code']= $tax_code_data['code'];
			$this->data['Order Tax Rate']= $tax_code_data['rate'];
			$this->data['Order Tax Name']=$tax_code_data['name'];
			$this->data['Order Tax Operations']=$tax_code_data['operations'];
			$this->data['Order Tax Selection Type']='';





		}


		if (isset($data['Order Current Dispatch State']) and $data['Order Current Dispatch State']=='In Process by Customer') {
			$this->data ['Order Current Dispatch State'] = 'In Process by Customer';
			$this->data ['Order Current XHTML Payment State'] = _('Waiting for payment');
		}else {
			$this->data ['Order Current Dispatch State'] = 'In Process';
			$this->data ['Order Current XHTML Payment State'] = _('Waiting for payment');
		}


		if (isset($data['Order Apply Auto Customer Account Payment'])) {
			$this->data ['Order Apply Auto Customer Account Payment'] =$data['Order Apply Auto Customer Account Payment'];
		}else {
			$this->data ['Order Apply Auto Customer Account Payment']='Yes';
		}

		if (isset($data['Order Payment Method'])) {
			$this->data ['Order Payment Method'] =$data['Order Payment Method'];
		}else {
			$this->data ['Order Payment Method'] ='Unknown';
		}

		$this->data ['Order Current Payment State'] = 'Waiting Payment';

		if (array_key_exists('Order Sales Representative Keys', $data)) {
			$this->data ['Order Sales Representative Keys']=$data['Order Sales Representative Keys'];
		}else {
			$this->data ['Order Sales Representative Keys'] =array($this->editor['User Key']);
		}

		$this->data ['Order For'] = 'Customer';

		$this->data ['Order Customer Message']='';


		if (isset($data['Order Original Data MIME Type']))
			$this->data ['Order Original Data MIME Type']=$data['Order Original Data MIME Type'];
		else
			$this->data ['Order Original Data MIME Type']='none';

		if (isset($data['Order Original Metadata']))
			$this->data ['Order Original Metadata']=$data['Order Original Metadata'];
		else
			$this->data ['Order Original Metadata']='';

		if (isset($data['Order Original Data Source']))
			$this->data ['Order Original Data Source']=$data['Order Original Data Source'];
		else
			$this->data ['Order Original Data Source']='Other';


		if (isset($data['Order Original Data Filename']))
			$this->data ['Order Original Data Filename']=$data['Order Original Data Filename'];
		else
			$this->data ['Order Original Data Filename']='Other';



		$this->data ['Order Currency Exchange']=1;








		if ($this->data ['Order Currency']!=$account->get('Account Currency')) {


			//take off this and only use curret exchenge whan get rid off excel
			$date_difference=date('U')-strtotime($this->data['Order Date'].' +0:00');
			if ($date_difference>3600) {
				$currency_exchange = new CurrencyExchange($this->data ['Order Currency'].$account->get('Account Currency'), $this->data['Order Date']);
				$exchange= $currency_exchange->get_exchange();
			}else {
				include_once 'utils/currency_functions.php';

				$exchange=currency_conversion($this->db, $this->data ['Order Currency'], $account->get('Account Currency'), 'now');
			}
			$this->data ['Order Currency Exchange']=$exchange;
		}

		$this->data ['Order Main Source Type']='Call';
		if (isset($data['Order Main Source Type']) and preg_match('/^(Internet|Call|Store|Unknown|Email|Fax)$/i'))
			$this->data ['Order Main Source Type']=$data['Order Main Source Type'];

		if (isset($data ['Order Public ID'])) {
			$this->data ['Order Public ID'] = $data ['Order Public ID'];
			$this->data ['Order File As'] = $this->prepare_file_as($data ['Order Public ID']);
		} else {
			$this->next_public_id();
		}



		$this->create_order_header ();



		if (count( $this->data ['Order Sales Representative Keys'])==0) {
			$sql = sprintf( "insert into `Order Sales Representative Bridge` values (%d,0,1)", $this->id);
			$this->db->exec($sql);
		}else {
			$share=1/count( $this->data ['Order Sales Representative Keys']);
			foreach ( $this->data ['Order Sales Representative Keys'] as $sale_rep_key ) {
				$sql = sprintf( "insert into `Order Sales Representative Bridge` values (%d,%d,%f)", $this->id, $sale_rep_key , $share);
				$this->db->exec($sql);
			}
		}


		$this->get_data('id', $this->id);
		$this->update_xhtml_sale_representatives();
		$this->update_charges();

		if ($this->data['Order Shipping Method']=='Calculated') {
			$this->update_shipping();

		}

		if (!$this->ghost_order) {
			$this->get_data('id', $this->id);

			$this->update_totals();

			$this->apply_payment_from_customer_account();
		}


		$sql=sprintf("update `Deal Component Dimension` set `Deal Component Allowance Target Key`=%d where `Deal Component Terms Type`='Next Order' and  `Deal Component Trigger`='Customer' and `Deal Component Trigger Key`=%d and `Deal Component Allowance Target Key`=0 and `Deal Component Status`='Active' ",
			$this->id,
			$this->data['Order Customer Key']
		);

		$this->db->exec($sql);


		$history_data=array(
			'History Abstract'=>_('Order created'),
			'History Details'=>'',
			'Action'=>'created'
		);
		$this->add_subject_history($history_data, true, 'No', 'Changes', $this->get_object_name(), $this->get_main_id());

	}


	function get_sales_representative_keys() {
		$sales_representative_keys=array();
		$sql=sprintf("select `Staff Key` from `Order Sales Representative Bridge` where `Order Key`=%s",
			$this->id
		);
		$result = mysql_query($sql) or die('aa0 Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$sales_representative_keys[]=$row['Staff Key'];
		}
		return $sales_representative_keys;
	}


	function update_xhtml_sale_representatives() {

		$xhtml_sale_representatives='';
		$tag='&view=csr';
		$sql=sprintf("select S.`Staff Key`,`Staff Alias` from `Order Sales Representative Bridge` B  left join `Staff Dimension` S on (B.`Staff Key`=S.`Staff Key`) where `Order Key`=%s",
			$this->id
		);
		//print $sql;
		$result = mysql_query($sql) or die('aa1 Query failed: ' . mysql_error());
		while ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$id=$row['Staff Key'];
			$ids[$id]=$id;

			$xhtml_sale_representatives.=sprintf(', <a href="staff.php?id=%d%s">%s</a>', $id, $tag, mb_ucwords($row['Staff Alias']));

		}
		$xhtml_sale_representatives=preg_replace("/^\,\s*/", "", $xhtml_sale_representatives);
		if ($xhtml_sale_representatives=='')
			$xhtml_sale_representatives=_('Unknown');

		$sql=sprintf("update `Order Dimension` set `Order XHTML Sales Representative`=%s where `Order Key`=%d",
			prepare_mysql($xhtml_sale_representatives),
			$this->id
		);
		//print $sql;
		mysql_query($sql);
	}




	function checkout_cancel_payment() {

		$date=gmdate("Y-m-d H:i:s");

		if (!($this->data['Order Current Dispatch State']=='In Process by Customer' or $this->data['Order Current Dispatch State']=='Waiting for Payment Confirmation')) {
			$this->error=true;
			$this->msg='Order is not in process by customer xx';
			return;

		}

		$this->data['Order Current Dispatch State']='In Process by Customer';
		//TODO make it using $this->calculate_state();
		$this->data['Order Current XHTML Dispatch State']=_('In Process by Customer');
		//TODO make it using $this->calculate_state(); or calculate_payments new functuon

		// 'Not Invoiced','Waiting Payment','Paid','Partially Paid','Unknown','No Applicable'



		$sql=sprintf("update `Order Dimension` set `Order Submitted by Customer Date`=NULL,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s where `Order Key`=%d"

			, prepare_mysql($this->data['Order Current Dispatch State'])
			, prepare_mysql($this->data['Order Current XHTML Dispatch State'])


			, $this->id
		);

		mysql_query($sql);

		$this->update_payment_state();

	}


	function checkout_submit_payment() {

		$date=gmdate("Y-m-d H:i:s");

		if (!($this->data['Order Current Dispatch State']=='In Process by Customer'  or $this->data['Waiting for Payment Confirmation'] ) ) {
			$this->error=true;
			$this->msg='Order is not in process by customer';


			return;

		}

		$this->data['Order Current Dispatch State']='Waiting for Payment Confirmation';
		//TODO make it using $this->calculate_state();
		$this->data['Order Current XHTML Dispatch State']=_('Waiting for Payment Confirmation');
		//TODO make it using $this->calculate_state(); or calculate_payments new functuon

		// 'Not Invoiced','Waiting Payment','Paid','Partially Paid','Unknown','No Applicable'


		$sql=sprintf("update `Order Dimension` set `Order Checkout Submitted Payment Date`=%s,`Order Date`=%s,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s where `Order Key`=%d"
			, prepare_mysql($date)
			, prepare_mysql($date)
			, prepare_mysql($this->data['Order Current Dispatch State'])
			, prepare_mysql($this->data['Order Current XHTML Dispatch State'])


			, $this->id
		);

		mysql_query($sql);
		$this->update_payment_state();
	}


	function send_to_basket() {



		$sql=sprintf("select `Order Key`,`Order Public ID`,`Order Current Dispatch State` from `Order Dimension` where `Order Customer Key`=%d and `Order Current Dispatch State` in ('In Process by Customer','Waiting for Payment Confirmation') ",
			$this->data['Order Customer Key']

		);
		$orders_data='';
		$res=mysql_query($sql);
		include_once 'utils/order_functions.php';
		while ($row=mysql_fetch_assoc($res)) {
			$orders_data.=', '.sprintf('%s (%s)',
				sprintf('<a href="order.php?id=%d">%s</a>', $row['Order Key'], $row['Order Public ID']),
				get_order_formatted_dispatch_state($row['Order Current Dispatch State'], $row['Order Key'])
			);

		}
		$orders_data=preg_replace('/^, /', '', $orders_data);

		if ($orders_data!='') {
			$this->error=true;
			$this->msg=_('There is already a order in the basket').' '.$orders_data;
			return;
		}


		$date=gmdate("Y-m-d H:i:s");

		if (!($this->data['Order Current Dispatch State']=='In Process')) {
			$this->error=true;
			$this->msg='Order is not in process'.$this->id.' '.$this->data['Order Current Dispatch State'];
			return;

		}


		$this->data['Order Current Dispatch State']='In Process by Customer';
		$this->data['Order Current XHTML Dispatch State']=_('In basket');




		$sql=sprintf("update `Order Dimension` set `Order Last Updated Date`=%s,`Order Date`=%s,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s  where `Order Key`=%d"
			, prepare_mysql($date)
			, prepare_mysql($date)
			, prepare_mysql($this->data['Order Current Dispatch State'])
			, prepare_mysql($this->data['Order Current XHTML Dispatch State'])

			, $this->id
		);
		mysql_query($sql);


		$sql=sprintf("update `Order Transaction Fact` set `Current Dispatching State`='In Process by Customer' where  `Current Dispatching State`='In Process by Customer' and `Order Key`=%d",
			$this->id
		);
		mysql_query($sql);


		$history_data=array(
			'History Abstract'=>_('Order moved to customer basket'),
			'History Details'=>'',
		);
		$this->add_subject_history($history_data);





	}


	function set_as_in_process() {

		$date=gmdate("Y-m-d H:i:s");

		if (!($this->data['Order Current Dispatch State']=='In Process by Customer' or $this->data['Order Current Dispatch State']=='Waiting for Payment Confirmation')) {
			$this->error=true;
			$this->msg='Order is not in process by customer: xx  '.$this->id.' '.$this->data['Order Current Dispatch State'];
			return;

		}
		$this->data['Order Current Dispatch State']='In Process';
		$this->data['Order Current XHTML Dispatch State']='In Process';




		$sql=sprintf("update `Order Dimension` set `Order Last Updated Date`=%s,`Order Date`=%s,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s  where `Order Key`=%d"
			, prepare_mysql($date)
			, prepare_mysql($date)
			, prepare_mysql($this->data['Order Current Dispatch State'])
			, prepare_mysql($this->data['Order Current XHTML Dispatch State'])

			, $this->id
		);



		mysql_query($sql);
		$this->update_payment_state();



		$history_data=array(
			'History Abstract'=>_('Order moved from basket to customer services process tray'),
			'History Details'=>'',
		);
		$this->add_subject_history($history_data);



	}



	function checkout_submit_order() {

		$date=gmdate("Y-m-d H:i:s");

		if (!($this->data['Order Current Dispatch State']=='In Process by Customer' or $this->data['Order Current Dispatch State']=='Waiting for Payment Confirmation')) {
			$this->error=true;
			$this->msg='Order is not in process by customer: xx  '.$this->id.' '.$this->data['Order Current Dispatch State'];
			return;

		}
		$this->data['Order Current Dispatch State']='Submitted by Customer';
		$this->data['Order Current XHTML Dispatch State']='Submitted by Customer';




		$sql=sprintf("update `Order Dimension` set `Order Submitted by Customer Date`=%s,`Order Date`=%s,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s  where `Order Key`=%d"
			, prepare_mysql($date)
			, prepare_mysql($date)
			, prepare_mysql($this->data['Order Current Dispatch State'])
			, prepare_mysql($this->data['Order Current XHTML Dispatch State'])

			, $this->id
		);



		mysql_query($sql);
		$this->update_payment_state();



		$history_data=array(
			'History Abstract'=>_('Order submited from basket'),
			'History Details'=>'',
		);
		$this->add_subject_history($history_data);

	}


	function send_to_warehouse($date=false, $extra_data=false) {


		if (!$date)
			$date=gmdate('Y-m-d H:i:s');

		if (!($this->data['Order Current Dispatch State']=='In Process' or $this->data['Order Current Dispatch State']=='Submitted by Customer'    or $this->data['Order Current Dispatch State']=='In Process by Customer' )) {
			$this->error=true;
			$this->msg='Order is not in process';
			return false;

		}

		if ($this->data['Order Current Dispatch State']=='In Process by Customer') {
			$this->update_field_switcher('Order Date', $date, 'no_history');
			$this->data['Order Date']=$date;
		}


		if ($this->data['Order For Collection']=='Yes') {
			$dispatch_method='Collection';
		} else {
			$dispatch_method='Dispatch';
		}
		$data_dn=array(
			'Delivery Note Date Created'=>$date,
			'Delivery Note Order Date Placed'=>$this->data['Order Date'],
			'Delivery Note ID'=>$this->data['Order Public ID'],
			'Delivery Note File As'=>$this->data['Order File As'],
			'Delivery Note Type'=>$this->data['Order Type'],
			'Delivery Note Dispatch Method'=>$dispatch_method,
			'Delivery Note Title'=>_('Delivery Note for').' '.$this->data['Order Type'].' <a class="id" href="order.php?id='.$this->id.'">'.$this->data['Order Public ID'].'</a>',
			'Delivery Note Customer Key'=>$this->data['Order Customer Key'],
			'Delivery Note Metadata'=>$this->data['Order Original Metadata'],
			'Delivery Note Customer Contact Name'=>$this->data['Order Customer Contact Name'],
			'Delivery Note Telephone'=>$this->data['Order Telephone'],
			'Delivery Note Email'=>$this->data['Order Email']

		);
		$sql=sprintf("insert into debugtable (`text`,`date`) values (%s,NOW())", prepare_mysql('new DN'.$this->id));mysql_query($sql);


		$dn=new DeliveryNote('create', $data_dn, $this);
		$dn->update_stock=$this->update_stock;

		if (isset($this->date_create_inventory_transaction_fact)) {
			$date=$this->date_create_inventory_transaction_fact;
		}



		$dn->create_inventory_transaction_fact($this->id, $date, $extra_data);


		$this->data['Order Current Dispatch State']='Ready to Pick';
		$this->data['Order Current XHTML Dispatch State']=_('Ready to Pick');

		// $sql=sprintf("insert into debugtable (`text`,`date`) values (%s,NOW())",prepare_mysql('end creating DN'.$this->id));mysql_query($sql);


		$sql=sprintf("update `Order Dimension` set `Order Send to Warehouse Date`=%s,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s  where `Order Key`=%d"
			, prepare_mysql($date)
			, prepare_mysql($this->data['Order Current Dispatch State'])
			, prepare_mysql($this->data['Order Current XHTML Dispatch State'])
			, $this->id
		);

		// $sqlx=sprintf("insert into debugtable (`text`,`date`) values (%s,NOW())",prepare_mysql($sql));mysql_query($sqlx);

		mysql_query($sql);

		$this->update_delivery_notes();
		$this->update_full_search();

		$history_data=array(
			'History Abstract'=>_('Order send to warehouse'),
			'History Details'=>'',
		);
		$this->add_subject_history($history_data);

		return $dn;
	}




	function send_post_action_to_warehouse($date=false, $type=false, $metadata='') {
		if (!$date)
			$date=gmdate('Y-m-d H:i:s');

		if (!$this->data['Order Current Dispatch State']=='Dispatched') {
			$this->error=true;
			$this->msg='Order is not already dispatched';
			return;

		}
		if (!$type) {
			$type='Replacement & Shortages';
		}


		$type_formatted=$type;
		$title="Delivery Note for $type of ".$this->data['Order Type'].' <a href="order.php?id='.$this->id.'">'.$this->data['Order Public ID'].'</a>';

		if ($this->data['Order For Collection']=='Yes')
			$dispatch_method='Collection';
		else
			$dispatch_method='Dispatch';

		if ($type=='Replacement')
			$suffix='rpl';
		elseif ($type=='Missing') {
			$suffix='sh';
			$type='Shortages';
		}else
			$suffix='r';



		$dn_id= $this->get_replacement_public_id($this->data['Order Public ID'].$suffix);




		$data_dn=array(
			'Delivery Note Date Created'=>$date,
			'Delivery Note ID'=>$dn_id,
			'Delivery Note File As'=>$dn_id,
			'Delivery Note Type'=>$type,
			'Delivery Note Title'=>$title,
			'Delivery Note Dispatch Method'=>$dispatch_method,
			'Delivery Note Metadata'=>$metadata,
			'Delivery Note Customer Key'=>$this->data['Order Customer Key']

		);






		$dn=new DeliveryNote('create', $data_dn, $this);
		$dn->create_post_order_inventory_transaction_fact($this->id, $date);
		$this->update_delivery_notes('save');
		//TODO!!!
		//$this->update_post_dispatch_state();

		$this->update_full_search();

		$customer=new Customer($this->data['Order Customer Key']);
		$customer->add_history_post_order_in_warehouse($dn, $type);
		return $dn;
	}


	function cancel_by_customer($note) {
		$this->cancel($note, false, false, $by_customer=true);
	}


	function cancel($note='', $date=false, $force=false, $by_customer=false) {

		$this->cancelled=false;
		if (preg_match('/Dispatched/', $this->data ['Order Current Dispatch State'])) {
			$this->msg=_('Order can not be cancelled, because has already been dispatched');

		}
		if (preg_match('/Cancelled/', $this->data ['Order Current Dispatch State'])) {
			$this->msg=_('Order is already cancelled');

		}
		else {

			$current_amount_in_customer_account_payments=0;
			$sql=sprintf("select B.`Payment Key`,`Amount`,`Payment Transaction Status` from `Order Payment Bridge` B left join `Payment Dimension` P on (P.`Payment Key`=B.`Payment Key`) where `Is Account Payment`='Yes' and `Order Key`=%d ",
				$this->id

			);

			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {

				$current_amount_in_customer_account_payments+=$row['Amount'];

				if ($row['Payment Transaction Status']=='Pending') {
					$sql=sprintf("delete  from `Order Payment Bridge` where `Payment Key`=%d ",
						$row['Payment Key']

					);
					mysql_query($sql);

					$sql=sprintf("delete  from `Payment Dimension` where `Payment Key`=%d ",
						$row['Payment Key']

					);
					mysql_query($sql);

				}else {

					$payment=new Payment($row['Payment Key']);
					$data_to_update=array(

						'Payment Completed Date'=>'',
						'Payment Last Updated Date'=>gmdate('Y-m-d H:i:s'),
						'Payment Cancelled Date'=>gmdate('Y-m-d H:i:s'),
						'Payment Transaction Status'=>'Cancelled',
						'Payment Transaction Status Info'=>_('Cancelled by user'),


					);
					$payment->update($data_to_update);



				}


			}



			$this->update_payment_state();


			if ($by_customer) {
				$state = 'Cancelled by Customer';

			}else {
				$state  = 'Cancelled';
			}

			if (!$date)
				$date=gmdate('Y-m-d H:i:s');
			$this->data ['Order Cancelled Date'] = $date;

			$this->data ['Order Cancel Note'] = $note;

			$this->data ['Order Current Payment State'] = 'No Applicable';


			$this->data ['Order Current Dispatch State'] = $state;

			$this->data ['Order Current XHTML Dispatch State'] = _('Cancelled');
			$this->data ['Order Current XHTML Payment State'] = _( 'Order cancelled' );
			$this->data ['Order XHTML Invoices'] = '';
			$this->data ['Order XHTML Delivery Notes'] = '';
			$this->data ['Order Invoiced Balance Total Amount'] = 0;
			$this->data ['Order Invoiced Balance Net Amount'] = 0;
			$this->data ['Order Invoiced Balance Tax Amount'] = 0;
			$this->data ['Order Invoiced Outstanding Balance Total Amount'] = 0;
			$this->data ['Order Invoiced Outstanding Balance Net Amount'] = 0;
			$this->data ['Order Invoiced Outstanding Balance Tax Amount'] = 0;
			$this->data ['Order Balance Net Amount'] = 0;
			$this->data ['Order Balance Tax Amount'] = 0;
			$this->data ['Order Balance Total Amount'] = 0;

			$this->data ['Order To Pay Amount'] =round($this->data ['Order Balance Total Amount']-$this->data['Order Payments Amount'], 2);

			$sql = sprintf( "update `Order Dimension` set  `Order Cancelled Date`=%s, `Order Current Payment State`=%s,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s,`Order Current XHTML Payment State`=%s,
				`Order XHTML Invoices`='',`Order XHTML Delivery Notes`=''
				,`Order Invoiced Balance Net Amount`=0,`Order Invoiced Balance Tax Amount`=0,`Order Invoiced Balance Total Amount`=0 ,`Order Invoiced Outstanding Balance Net Amount`=0,`Order Invoiced Outstanding Balance Tax Amount`=0,`Order Invoiced Outstanding Balance Total Amount`=0,`Order Invoiced Profit Amount`=0,`Order Cancel Note`=%s
				,`Order Balance Net Amount`=0,`Order Balance tax Amount`=0,`Order Balance Total Amount`=0,`Order To Pay Amount`=%.2f
				where `Order Key`=%d"
				//     ,$no_shipped
				, prepare_mysql ( $this->data ['Order Cancelled Date'] )
				, prepare_mysql ( $this->data ['Order Current Payment State'] )
				, prepare_mysql ( $this->data ['Order Current Dispatch State'] )
				, prepare_mysql ( $this->data ['Order Current XHTML Dispatch State'] )
				, prepare_mysql ( $this->data ['Order Current XHTML Payment State'] )
				, prepare_mysql ( $this->data ['Order Cancel Note'] )
				, $this->data ['Order To Pay Amount']
				, $this->id );
			if (! mysql_query( $sql ))
				exit ( "$sql error can not update cancel\n" );

			$sql = sprintf( "update `Order Transaction Fact` set  `Delivery Note Key`=NULL,  `Delivery Note ID`=NULL,`Invoice Key`=NULL, `Invoice Public ID`=NULL,`Picker Key`=NULL,`Picker Key`=NULL, `Consolidated`='Yes',`Current Dispatching State`=%s where `Order Key`=%d ",
				prepare_mysql($state),
				$this->id );
			mysql_query( $sql );

			$sql = sprintf( "update `Order Transaction Fact` set  `Picking Factor`=0,  `Picking Factor`=0,`Picked Quantity`=0, `Estimated Dispatched Weight`=0,`Delivery Note Quantity`=0,`Shipped Quantity`=0, `No Shipped Due Out of Stock`=0,`No Shipped Due No Authorized`=0,`No Shipped Due Not Found`=0,`No Shipped Due Other`=0,`Order Out of Stock Lost Amount`=0,`Invoice Quantity`=0 where `Order Key`=%d ",

				$this->id );
			mysql_query( $sql );


			$sql = sprintf( "update `Order No Product Transaction Fact` set `Delivery Note Date`=NULL,`Delivery Note Key`=NULL,`State`=%s ,`Consolidated`='Yes' where `Order Key`=%d ",
				prepare_mysql($state),
				$this->id );
			mysql_query( $sql );



			foreach ($this->get_delivery_notes_objects() as $dn) {
				$dn->cancel($note, $date, $force);

				$sql=sprintf("delete from  `Order Delivery Note Bridge` where `Order Key`=%d and `Delivery Note Key`=%d",
					$this->id,
					$dn->id
				);
				mysql_query( $sql );
			}



			if (!isset($_SESSION ['lang']))
				$lang=0;
			else
				$lang=$_SESSION ['lang'];

			switch ($lang) {
			default :
				$note = sprintf( 'Order <a href="order.php?id=%d">%s</a> (Cancelled)', $this->data ['Order Key'], $this->data ['Order Public ID'] );
				if ($this->editor['Author Alias']!='' and $this->editor['Author Key'] ) {
					$details = sprintf( _('%s cancel (%s) order %s'),

						sprintf('<a href="staff.php?id=%d">%s</a>', $this->editor['Author Key'], $this->editor['Author Alias']),

						sprintf('<a href="customer.php?id=%d">%s</a>', $this->data['Order Customer Key'], $this->data['Order Customer Name']),
						sprintf('<a href="order.php?id=%d">%s</a>', $this->data ['Order Key'], $this->data ['Order Public ID'])
					);
				} elseif ($this->editor['Author Alias']=='System Cron'  and !$this->editor['Author Key']) {
					$details = sprintf( _('A cron job cancel (%s) order %s'),
						sprintf('<a href="customer.php?id=%d">%s</a>', $this->data['Order Customer Key'], $this->data['Order Customer Name']),
						sprintf('<a href="order.php?id=%d">%s</a>', $this->data ['Order Key'], $this->data ['Order Public ID'])
					);

				}else {
					$details = sprintf( _('Someone cancel (%s) order %s'),
						sprintf('<a href="customer.php?id=%d">%s</a>', $this->data['Order Customer Key'], $this->data['Order Customer Name']),
						sprintf('<a href="order.php?id=%d">%s</a>', $this->data ['Order Key'], $this->data ['Order Public ID'])
					);
				}


				if ($this->data ['Order Cancel Note']!='')
					$details.='<div> Note: '.$this->data ['Order Cancel Note'].'</div>';


			}

			if ($this->editor['Author Alias']=='System Cron'  and !$this->editor['Author Key']) {
				$subject='System';
				$subject_key=0;
			}else {
				$subject='Staff';
				$subject_key=$this->editor['Author Key'];

			}

			$history_data=array(
				'Date'=>$this->data ['Order Cancelled Date'],
				'Subject'=>$subject,
				'Subject Key'=>$subject_key,
				'Direct Object'=>'Order',
				'Direct Object Key'=>$this->data ['Order Key'],
				'History Details'=>$details,
				'History Abstract'=>$note,
				'Metadata'=>'Cancelled'

			);




			$history_key=$this->add_subject_history($history_data);



			$customer=new Customer($this->data['Order Customer Key']);
			$customer->editor=$this->editor;
			$customer->add_history_order_cancelled($history_key);
			$customer->update_orders();

			$customer->update(
				array(
					'Customer Account Balance'=>round($customer->data['Customer Account Balance']+$current_amount_in_customer_account_payments, 2)
				), 'no_history');


			$store=new Store($this->data['Order Store Key']);
			$store->update_orders();

			$this->update_deals_usage();
			$this->cancelled=true;





		}



	}


	function undo_cancel() {


		if (!preg_match('/Cancelled/', $this->data ['Order Current Dispatch State'])) {
			$this->msg=_('Order is not cancelled');
			$this->error=true;
			return;
		}


		$state  = 'In Process';

		$date=gmdate('Y-m-d H:i:s');
		$this->data ['Order Cancelled Date'] = '';

		$this->data ['Order Cancel Note'] = '';

		$this->data ['Order Current Payment State'] = 'No Applicable';


		$this->data ['Order Current Dispatch State'] = $state;

		$this->data ['Order Current XHTML Dispatch State'] = _('In Process');
		$this->data ['Order Current XHTML Payment State'] = '';
		$this->data ['Order XHTML Invoices'] = '';
		$this->data ['Order XHTML Delivery Notes'] = '';
		$this->data ['Order Invoiced Balance Total Amount'] = 0;
		$this->data ['Order Invoiced Balance Net Amount'] = 0;
		$this->data ['Order Invoiced Balance Tax Amount'] = 0;
		$this->data ['Order Invoiced Outstanding Balance Total Amount'] = 0;
		$this->data ['Order Invoiced Outstanding Balance Net Amount'] = 0;
		$this->data ['Order Invoiced Outstanding Balance Tax Amount'] = 0;
		$this->data ['Order Balance Net Amount'] = 0;
		$this->data ['Order Balance Tax Amount'] = 0;
		$this->data ['Order Balance Total Amount'] = 0;

		$this->data ['Order To Pay Amount'] =round($this->data ['Order Balance Total Amount']-$this->data['Order Payments Amount'], 2);

		$sql = sprintf( "update `Order Dimension` set    `Order Cancelled Date`=%s, `Order Current Payment State`=%s,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s,`Order Current XHTML Payment State`=%s,
		`Order XHTML Invoices`='',`Order XHTML Delivery Notes`=''
		,`Order Invoiced Balance Net Amount`=0,`Order Invoiced Balance Tax Amount`=0,`Order Invoiced Balance Total Amount`=0 ,`Order Invoiced Outstanding Balance Net Amount`=0,`Order Invoiced Outstanding Balance Tax Amount`=0,`Order Invoiced Outstanding Balance Total Amount`=0,`Order Invoiced Profit Amount`=0,`Order Cancel Note`=%s
		,`Order Balance Net Amount`=0,`Order Balance tax Amount`=0,`Order Balance Total Amount`=0,`Order To Pay Amount`=%.2f
		where `Order Key`=%d"
			//     ,$no_shipped
			, prepare_mysql ( $this->data ['Order Cancelled Date'] )
			, prepare_mysql ( $this->data ['Order Current Payment State'] )
			, prepare_mysql ( $this->data ['Order Current Dispatch State'] )
			, prepare_mysql ( $this->data ['Order Current XHTML Dispatch State'] )
			, prepare_mysql ( $this->data ['Order Current XHTML Payment State'] )
			, prepare_mysql ( $this->data ['Order Cancel Note'] )
			, $this->data ['Order To Pay Amount']
			, $this->id );
		if (! mysql_query( $sql ))
			exit ( "$sql error can not update cancel\n" );


		$this->update_payment_state();

		$sql = sprintf( "update `Order Transaction Fact` set `Delivery Note Key`=NULL,  `Delivery Note ID`=NULL,`Invoice Key`=NULL, `Invoice Public ID`=NULL,`Picker Key`=NULL,`Picker Key`=NULL, `Consolidated`='No',`Current Dispatching State`=%s where `Order Key`=%d ",
			prepare_mysql($state),

			$this->id );

		//print $sql;

		mysql_query( $sql );



		$sql = sprintf( "update `Order No Product Transaction Fact` set `State`=%s ,`Consolidated`='No' where `Order Key`=%d ",
			prepare_mysql($state),
			$this->id );
		mysql_query( $sql );


		$sql=sprintf("delete from `Order Transaction Deal Bridge` where `Order Key` =%d ", $this->id);
		mysql_query($sql);




		$this->update_number_products();
		$this->update_insurance();

		$this->update_discounts_items();
		$this->update_totals();



		$this->update_shipping(false, false);
		$this->update_charges(false, false);
		$this->update_discounts_no_items();


		$this->update_deal_bridge();

		$this->update_deals_usage();

		$this->update_totals();

		$this->update_number_products();

		$this->apply_payment_from_customer_account();


		$customer=new Customer($this->data['Order Customer Key']);
		$customer->update_orders();

		$store=new Store($this->data['Order Store Key']);
		$store->update_orders();

		$this->update_deals_usage();
		//$this->cancelled=true;

	}





	function activate($date=false) {


		if (!preg_match('/Suspended/', $this->data ['Order Current Dispatch State'])) {
			$this->msg=_('Order is not suspended');

		}
		else {

			if (!$date)
				$date=gmdate('Y-m-d H:i:s');
			$this->data ['Order Suspended Date'] = $date;

			$this->data ['Order Suspend Note'] = $note;

			$this->data ['Order Current Payment State'] = 'No Applicable';
			$this->data ['Order Current Dispatch State'] = 'Suspended';
			$this->data ['Order Current XHTML Dispatch State'] = _('Suspended');
			$this->data ['Order Current XHTML Payment State'] = _( 'Order Suspended' );
			$this->data ['Order XHTML Invoices'] = '';
			$this->data ['Order XHTML Delivery Notes'] = '';
			$this->data ['Order Invoiced Balance Total Amount'] = 0;
			$this->data ['Order Invoiced Balance Net Amount'] = 0;
			$this->data ['Order Invoiced Balance Tax Amount'] = 0;
			$this->data ['Order Invoiced Outstanding Balance Total Amount'] = 0;
			$this->data ['Order Invoiced Outstanding Balance Net Amount'] = 0;
			$this->data ['Order Invoiced Outstanding Balance Tax Amount'] = 0;



			$sql = sprintf( "update `Order Dimension` set `Order Suspended Date`=%s, `Order Current Payment State`=%s,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s,`Order Current XHTML Payment State`=%s,`Order XHTML Invoices`='',`Order XHTML Delivery Notes`='' ,`Order Invoiced Balance Net Amount`=0,`Order Invoiced Balance Tax Amount`=0,`Order Invoiced Balance Total Amount`=0 ,`Order Invoiced Outstanding Balance Net Amount`=0,`Order Invoiced Outstanding Balance Tax Amount`=0,`Order Invoiced Outstanding Balance Total Amount`=0,`Order Invoiced Profit Amount`=0,`Order Suspend Note`=%s  where `Order Key`=%d"
				, prepare_mysql ( $this->data ['Order Suspended Date'] )
				, prepare_mysql ( $this->data ['Order Current Payment State'] )
				, prepare_mysql ( $this->data ['Order Current Dispatch State'] )
				, prepare_mysql ( $this->data ['Order Current XHTML Dispatch State'] )
				, prepare_mysql ( $this->data ['Order Current XHTML Payment State'] )
				, prepare_mysql ( $this->data ['Order Suspend Note'] )

				, $this->id );
			mysql_query( $sql );

			$sql = sprintf( "update `Order Transaction Fact` set `Current Dispatching State`='Suspended',`Current Payment State`='No Applicable' where `Order Key`=%d ", $this->id );
			mysql_query( $sql );
			$sql = sprintf( "update `Order No Product Transaction Fact` set `State`='Suspended'  where `Order Key`=%d ", $this->id );
			mysql_query( $sql );

			foreach ($this->get_delivery_notes_objects() as $dn) {
				$dn->suspend($note, $date);
			}

			$customer=new Customer($this->data['Order Customer Key']);
			$customer->editor=$this->editor;
			$customer->add_history_order_activate($this);//<--- Not done yet
			$this->suspended=true;

			$history_data=array(
				'History Abstract'=>_('Order activated'),
				'History Details'=>'',
			);
			$this->add_subject_history($history_data);

		}



	}


	function suspend($note='', $date=false) {

		$this->suspended=false;
		if (preg_match('/Dispatched/', $this->data ['Order Current Dispatch State'])) {
			$this->msg=_('Order can not be suspended, because has already been dispatched');

		}
		elseif (preg_match('/Suspended/', $this->data ['Order Current Dispatch State'])) {
			$this->msg=_('Order is cancelled');

		}
		elseif (preg_match('/Suspended/', $this->data ['Order Current Dispatch State'])) {
			$this->msg=_('Order is already suspended');

		}
		else {

			if (!$date)
				$date=gmdate('Y-m-d H:i:s');
			$this->data ['Order Suspended Date'] = $date;

			$this->data ['Order Suspend Note'] = $note;

			$this->data ['Order Current Payment State'] = 'No Applicable';
			$this->data ['Order Current Dispatch State'] = 'Suspended';
			$this->data ['Order Current XHTML Dispatch State'] = _('Suspended');
			$this->data ['Order Current XHTML Payment State'] = _( 'Order Suspended' );
			$this->data ['Order XHTML Invoices'] = '';
			$this->data ['Order XHTML Delivery Notes'] = '';
			$this->data ['Order Invoiced Balance Total Amount'] = 0;
			$this->data ['Order Invoiced Balance Net Amount'] = 0;
			$this->data ['Order Invoiced Balance Tax Amount'] = 0;
			$this->data ['Order Invoiced Outstanding Balance Total Amount'] = 0;
			$this->data ['Order Invoiced Outstanding Balance Net Amount'] = 0;
			$this->data ['Order Invoiced Outstanding Balance Tax Amount'] = 0;



			$sql = sprintf( "update `Order Dimension` set `Order Suspended Date`=%s, `Order Current Payment State`=%s,`Order Current Dispatch State`=%s,`Order Current XHTML Dispatch State`=%s,`Order Current XHTML Payment State`=%s,`Order XHTML Invoices`='',`Order XHTML Delivery Notes`='' ,`Order Invoiced Balance Net Amount`=0,`Order Invoiced Balance Tax Amount`=0,`Order Invoiced Balance Total Amount`=0 ,`Order Invoiced Outstanding Balance Net Amount`=0,`Order Invoiced Outstanding Balance Tax Amount`=0,`Order Invoiced Outstanding Balance Total Amount`=0,`Order Invoiced Profit Amount`=0,`Order Suspend Note`=%s  where `Order Key`=%d"
				, prepare_mysql ( $this->data ['Order Suspended Date'] )
				, prepare_mysql ( $this->data ['Order Current Payment State'] )
				, prepare_mysql ( $this->data ['Order Current Dispatch State'] )
				, prepare_mysql ( $this->data ['Order Current XHTML Dispatch State'] )
				, prepare_mysql ( $this->data ['Order Current XHTML Payment State'] )
				, prepare_mysql ( $this->data ['Order Suspend Note'] )

				, $this->id );
			mysql_query( $sql );

			$sql = sprintf( "update `Order Transaction Fact` set `Current Dispatching State`='Suspended',`Current Payment State`='No Applicable' where `Order Key`=%d ", $this->id );
			mysql_query( $sql );
			$sql = sprintf( "update `Order No Product Transaction Fact` set `State`='Suspended'  where `Order Key`=%d ", $this->id );
			mysql_query( $sql );

			foreach ($this->get_delivery_notes_objects() as $dn) {
				$dn->suspend($note, $date);
			}

			$customer=new Customer($this->data['Order Customer Key']);
			$customer->editor=$this->editor;
			$customer->add_history_order_suspended($this);
			$store=new Store($this->data['Order Store Key']);
			$store->update_orders();
			$this->suspended=true;
			$history_data=array(
				'History Abstract'=>_('Order suspended'),
				'History Details'=>'',
			);
			$this->add_subject_history($history_data);

		}



	}


	function create_invoice($date=false) {
		// intended to be used in services

		if (!$date)
			$date=gmdate("Y-m-d H:i:s");

		$tax_code='UNK';
		$orders_ids='';

		$tax_code=$this->data['Order Tax Code'];





		$delivery_note_keys='';
		foreach ($this->get_delivery_notes_ids()as $dn_key) {

			$delivery_note_keys=$dn_key.',';

		}
		$delivery_note_keys=preg_replace('/\,$/', '', $delivery_note_keys);




		$store=new Store($this->data['Order Store Key']);
		if ($store->data['Store Next Invoice Public ID Method']=='Order ID') {
			$invoice_public_id=$this->data['Order Public ID'];
		}elseif ($store->data['Store Next Invoice Public ID Method']=='Invoice Public ID') {

			$sqla=sprintf("UPDATE `Store Dimension` SET `Store Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Store Invoice Last Invoice Public ID` + 1) where `Store Key`=%d"
				, $this->data['Order Store Key']);
			mysql_query($sqla);
			$public_id=mysql_insert_id();

			$invoice_public_id=sprintf($store->data['Store Invoice Public ID Format'], $public_id);

		}else {

			$sqla=sprintf("UPDATE `Account Dimension` SET `Account Invoice Last Invoice Public ID` = LAST_INSERT_ID(`Account Invoice Last Invoice Public ID` + 1) where `Account Key`=1");
			mysql_query($sqla);
			$public_id=mysql_insert_id();

			include_once 'class.Account.php';
			$account=new Account();
			$invoice_public_id=sprintf($account->data['Account Invoice Public ID Format'], $public_id);
		}


		$data_invoice=array(
			'Invoice Date'=>$date,
			'Invoice Type'=>'Invoice',
			'Invoice Public ID'=>$invoice_public_id,
			'Delivery Note Keys'=>$delivery_note_keys,
			'Order Key'=>$this->id,
			'Invoice Store Key'=>$this->data['Order Store Key'],
			'Invoice Customer Key'=>$this->data['Order Customer Key'],
			'Invoice Tax Code'=>$tax_code,
			'Invoice Tax Shipping Code'=>$tax_code,
			'Invoice Tax Charges Code'=>$tax_code,
			'Invoice Sales Representative Keys'=>$this->get_sales_representative_keys(),
			'Invoice Metadata'=>$this->data['Order Original Metadata'],
			'Invoice Billing To Key'=>$this->data['Order Billing To Key To Bill'],
			'Invoice Tax Number'=>$this->data['Order Tax Number'],
			'Invoice Tax Number Valid'=>$this->data['Order Tax Number Valid'],
			'Invoice Tax Number Validation Date'=>$this->data['Order Tax Number Validation Date'],
			'Invoice Tax Number Associated Name'=>$this->data['Order Tax Number Associated Name'],
			'Invoice Tax Number Associated Address'=>$this->data['Order Tax Number Associated Address'],
			'Invoice Net Amount Off'=>$this->data['Order Deal Amount Off']


		);




		$invoice=new Invoice ('create', $data_invoice);


		$this->update_totals();



		return $invoice;
	}


	function no_payment_applicable() {




		$this->data ['Order Current Payment State'] = 'No Applicable';
		$this->data ['Order Current Dispatch State'] = 'Dispatched';

		$dn_txt=_('Dispatched');
		if ($this->data ['Order Type'] == 'Order') {
			$dn_txt = _("No value order, Dispatched");
		}



		$sql = sprintf( "update `Order Dimension` set `Order Current XHTML Payment State`=%s where `Order Key`=%d", prepare_mysql ( $dn_txt ), $this->id );
		if (! mysql_query( $sql ))
			exit ( "arror can not update no_payment_applicable\n" );


		$sql = sprintf( "update `Order Dimension` set `Order Current Payment State`=%s ,`Order Current Dispatch State`=%s where `Order Key`=%d", prepare_mysql ( $this->data ['Order Current Payment State'] ), prepare_mysql ( $this->data ['Order Current Dispatch State'] ), $this->id );
		if (! mysql_query( $sql ))
			exit ( "arror can not update no_payment_applicable\n" );

		$sql = sprintf( "update `Order Transaction Fact` set `Consolidated`='Yes',`Current Payment State`=%s ,`Current Dispatching State`=%s where `Order Key`=%d", prepare_mysql ( $this->data ['Order Current Payment State'] ), prepare_mysql ( $this->data ['Order Current Dispatch State'] ), $this->id );
		if (! mysql_query( $sql ))
			exit ( "error can not update no_payment_applicabl 3e\n" );

	}


	function delete_transaction($otf_key) {
		$sql=sprintf("delete from `Order Transaction Fact` where `Order Transaction Fact Key`=%d", $otf_key);
		mysql_query($sql);


		$sql=sprintf("delete from `Inventory Transaction Fact` where `Map To Order Transaction Fact Key`=%d", $otf_key);
		mysql_query($sql);

	}





	function add_order_transaction($data, $historic=false) {

		if (!isset($data ['ship to key'])) {
			$ship_to_keys=preg_split('/,/', $this->data['Order Ship To Keys']);
			$ship_to_key=$ship_to_keys[0];

		} else {
			$ship_to_key=$data ['ship to key'];
		}

		if (!isset($data ['billing to key'])) {
			$billing_to_keys=preg_split('/,/', $this->data['Order Billing To Keys']);
			$billing_to_key=$billing_to_keys[0];

		} else {
			$billing_to_key=$data ['billing to key'];
		}

		$tax_code=$this->data['Order Tax Code'];
		$tax_rate=$this->data['Order Tax Rate'];
		if (array_key_exists('tax_code', $data))
			$tax_code=$data['tax_code'];
		if (array_key_exists('tax_rate', $data))
			$tax_rate=$data['tax_rate'];

		if (isset($data['Order Type']))
			$order_type=$data['Order Type'];
		else
			$order_type=$this->data['Order Type'];

		if (array_key_exists('qty', $data)) {
			$quantity=$data ['qty'];
			$quantity_set=true;

		} else {
			$quantity=0;
			$quantity_set=false;
		}

		if (array_key_exists('qty', $data)) {
			$quantity=$data ['qty'];
			$quantity_set=true;

		} else {
			$quantity=0;
			$quantity_set=false;
		}



		if (array_key_exists('bonus qty', $data)) {
			$bonus_quantity=$data ['bonus qty'];
			$bonus_quantity_set=true;
		} else {
			$bonus_quantity=0;
			$bonus_quantity_set=false;

		}

		$gross_discounts=0;

		$delta_qty=$quantity;


		if ($historic) {

			$old_quantity=0;
			$old_bonus_quantity=0;
			$old_net_amount=0;

			$total_quantity=$quantity+$bonus_quantity;
			if ($total_quantity==0) {
				return array(
					'updated'=>false
				);

			}


			if ($quantity==0) {
				$data ['Current Payment State']='No Applicable';

			}


			$product=new Product('historic_key', $data['Product Key']);
			$gross=$quantity*$product->data['Product History Price'];
			$estimated_weight=$total_quantity*$product->data['Product Package Weight'];
			$gross_discounts=0;
			$sql = sprintf( "insert into `Order Transaction Fact` (`Order Bonus Quantity`,`Order Transaction Type`,`Transaction Tax Rate`,`Transaction Tax Code`,`Order Currency Code`,`Estimated Weight`,`Order Date`,`Order Last Updated Date`,
			`Product Key`,`Product ID`,`Product Code`,`Product Family Key`,`Product Department Key`,
			`Current Dispatching State`,`Current Payment State`,`Customer Key`,`Order Key`,`Order Public ID`,`Order Quantity`,`Ship To Key`,`Billing To Key`,
			`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Amount`,`Metadata`,`Store Key`,`Units Per Case`,`Customer Message`)
values (%f,%s,%f,%s,%s,%s,%s,%s,
	%d,%d,%s,%d,%d,
	%s,%s,%s,%s,%s,%s,%s,%s,
	%.2f,%.2f,%.2f,%s,%s,%f,'') ",
				$bonus_quantity,
				prepare_mysql($order_type),
				$tax_rate,
				prepare_mysql ($tax_code),
				prepare_mysql ( $this->data ['Order Currency'] ),
				$estimated_weight,
				prepare_mysql ( $data ['date'] ),
				prepare_mysql ( $data ['date'] ),
				$product->historic_id,
				$product->data['Product ID'],
				prepare_mysql($product->data['Product Code']),
				$product->data['Product Family Key'],
				$product->data['Product Main Department Key'],
				prepare_mysql ( $data ['Current Dispatching State'] ),
				prepare_mysql ( $data ['Current Payment State'] ),
				prepare_mysql ( $this->data['Order Customer Key' ] ),
				prepare_mysql ( $this->data ['Order Key'] ),
				prepare_mysql ( $this->data ['Order Public ID'] ),
				$quantity,
				prepare_mysql ( $ship_to_key ),
				prepare_mysql ( $billing_to_key ),
				$gross,
				$gross_discounts,
				$gross-$gross_discounts,
				prepare_mysql ( $data ['Metadata'] , false),
				prepare_mysql ( $this->data ['Order Store Key'] ),
				(isset($data ['units_per_case'])?$data ['units_per_case']:'')

			);
			mysql_query( $sql );

			$otf_key=mysql_insert_id();

			//print "Otf $otf_key \n";

		}
		else {


			if (!in_array($this->data['Order Current Dispatch State'], array('In Process by Customer', 'In Process', 'Submitted by Customer', 'Ready to Pick', 'Picking & Packing', 'Packed', 'Packed Done', 'Packing')) ) {
				return array(
					'updated'=>false,

				);
			}



			if (in_array($this->data['Order Current Dispatch State'], array('Ready to Pick', 'Picking & Packing', 'Packed', 'Packed Done', 'Packing')) ) {


				$dn_keys=$this->get_delivery_notes_ids();
				$dn_key=array_pop($dn_keys);
				$dn=new DeliveryNote($dn_key);


			}else {
				$dn_key=0;
			}




			$sql=sprintf("select `Order Bonus Quantity`,`Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Fact Key` from `Order Transaction Fact` OTF where `Order Key`=%d and `Product Key`=%d ",
				$this->id,
				$data ['Product Key']);


			if ($dn_key) {
				$sql.=sprintf(' and `Delivery Note Key`=%d', $dn_key);
			}

			$res=mysql_query($sql);

			if ($row=mysql_fetch_array($res)) {

				$old_quantity=$row['Order Quantity'];
				$old_bonus_quantity=$row['Order Bonus Quantity'];
				$old_net_amount=$row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'];

				$delta_qty-=$old_quantity;

				if (!$quantity_set) {
					$quantity=$old_quantity;
				}

				//if (!$bonus_quantity_set) {
				// $bonus_quantity=$old_bonus_quantity;
				//}
				$total_quantity=$quantity+$bonus_quantity;


				//   print "\n**** $old_quantity $old_bonus_quantity   ;  ($quantity_set,$bonus_quantity_set) ; QTY    $quantity ==     $total_quantity\n";
				$product=new Product('historic_key', $data['Product Key']);
				if ($total_quantity==0) {

					$this->delete_transaction($row['Order Transaction Fact Key']);
					$otf_key=0;
					$gross=0;
					$gross_discounts=0;

				}
				else {




					$estimated_weight=$total_quantity*$product->data['Product Package Weight'];
					$gross=$quantity*$product->data['Product History Price'];




					$sql = sprintf( "update`Order Transaction Fact` set  `Estimated Weight`=%s,`Order Quantity`=%f,`Order Bonus Quantity`=%f,`Order Last Updated Date`=%s,`Order Transaction Gross Amount`=%.2f ,`Order Transaction Total Discount Amount`=%.2f,`Order Transaction Amount`=%.2f,`Current Dispatching State`=%s  where `Order Transaction Fact Key`=%d ",
						$estimated_weight ,
						$quantity,
						$bonus_quantity,
						prepare_mysql ( $data ['date'] ),
						$gross,
						$gross_discounts,
						$gross-$gross_discounts,
						prepare_mysql ( $data ['Current Dispatching State'] ),
						$row['Order Transaction Fact Key']

					);
					mysql_query($sql);
					if (mysql_affected_rows()) {
						$this->update_field('Order Last Updated Date', gmdate('Y-m-d H:i:s'), 'no_history');
					}
					if ($dn_key) {

						$sql = sprintf("update  `Order Transaction Fact` set `Delivery Note ID`=%s,`Delivery Note Key`=%d ,`Destination Country 2 Alpha Code`=%s where `Order Transaction Fact Key`=%d",

							prepare_mysql ($dn->data ['Delivery Note ID']),
							$dn_key,
							prepare_mysql($dn->data ['Delivery Note Country 2 Alpha Code']),
							$row['Order Transaction Fact Key']

						);
						mysql_query($sql);
					}


					$otf_key=$row['Order Transaction Fact Key'];





					//   print "$sql  $otf_key  \n";
					//    exit;
				}

			}
			else {

				$old_quantity=0;
				$old_bonus_quantity=0;
				$old_net_amount=0;


				$total_quantity=$quantity+$bonus_quantity;

				if ($total_quantity==0) {
					return array(
						'updated'=>false,
						'qty'=>$quantity,
						'bonus qty'=>$bonus_quantity,
						'otf_key'=>0,
						'delta_qty'=>0,
						'delta_net_amount'=>0,
						'net_amount'=>0

					);
				}

				$product=new Product('historic_key', $data['Product Key']);
				$gross=$quantity*$product->data['Product History Price'];
				$estimated_weight=$total_quantity*$product->data['Product Package Weight'];

				$sql = sprintf( "insert into `Order Transaction Fact` (`Order Bonus Quantity`,`Order Transaction Type`,`Transaction Tax Rate`,`Transaction Tax Code`,`Order Currency Code`,`Estimated Weight`,`Order Date`,`Order Last Updated Date`,
			`Product Key`,`Product ID`,`Product Code`,`Product Family Key`,`Product Department Key`,
			`Current Dispatching State`,`Current Payment State`,`Customer Key`,`Order Key`,`Order Public ID`,`Order Quantity`,`Ship To Key`,`Billing To Key`,
			`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Amount`,`Metadata`,`Store Key`,`Units Per Case`,`Customer Message`,`Delivery Note Key`)
values (%f,%s,%f,%s,%s,%s,%s,%s,
	%d,%d,%s,%d,%d,
	%s,%s,%s,%s,%s,%s,%s,%s,
	%.2f,%.2f,%.2f,%s,%s,%f,'',%s)   ",

					$bonus_quantity,
					prepare_mysql($order_type),
					$tax_rate,
					prepare_mysql ($tax_code),
					prepare_mysql ( $this->data ['Order Currency'] ),
					$estimated_weight ,
					prepare_mysql ( $data ['date'] ),

					prepare_mysql ( $data ['date'] ),
					$product->historic_id,
					$product->data['Product ID'],
					prepare_mysql($product->data['Product Code']),
					$product->data['Product Family Key'],
					$product->data['Product Main Department Key'],
					prepare_mysql ( $data ['Current Dispatching State'] ),
					prepare_mysql ( $data ['Current Payment State'] ),
					prepare_mysql ( $this->data['Order Customer Key' ] ),
					prepare_mysql ( $this->data ['Order Key'] ),
					prepare_mysql ( $this->data ['Order Public ID'] ),
					$quantity,
					prepare_mysql ( $ship_to_key ),
					prepare_mysql ( $billing_to_key ),
					$gross,
					$gross_discounts,
					$gross-$gross_discounts,
					prepare_mysql ( $data ['Metadata'] , false),
					prepare_mysql ( $this->data ['Order Store Key'] ),
					$product->data['Product Units Per Case'],
					prepare_mysql($dn_key)
				);

				mysql_query( $sql );

				$otf_key=mysql_insert_id();
				//print $sql;
				if (!$otf_key) {
					print "Error xxx";
				}



				if ($dn_key) {

					$sql = sprintf("update  `Order Transaction Fact` set `Estimated Weight`=%f,`Delivery Note ID`=%s,`Delivery Note Key`=%d ,`Destination Country 2 Alpha Code`=%s where `Order Transaction Fact Key`=%d",
						$estimated_weight,
						prepare_mysql ($dn->data ['Delivery Note ID']),
						$dn_key,
						prepare_mysql($dn->data ['Delivery Note Country 2 Alpha Code']),
						$otf_key

					);
					mysql_query($sql);
				}




			}

			if ($dn_key) {
				$dn->update_inventory_transaction_fact($otf_key, $quantity);

				$dn->update_item_totals();
				$dn->update_picking_percentage();
				$dn->update_packing_percentage();
			}

			$this->update_field('Order Last Updated Date', gmdate('Y-m-d H:i:s'), 'no_history');

			if (in_array($this->data['Order Current Dispatch State'], array('In Process by Customer', 'In Process'))) {
				$this->update_field('Order Date', gmdate('Y-m-d H:i:s'), 'no_history');


			}else {
				$history_abstract='';
				if ($delta_qty>0) {
					$history_abstract=sprintf(_('%1$s %2$s added'), $delta_qty, sprintf('<a href="product.php?pid=%d">%s</a>', $product->id, $product->data['Product Code']));
				}elseif ($delta_qty<0) {

					if ($quantity==0) {
						$history_abstract=sprintf(_('%s %s removed, none in the order anymore'), -$delta_qty, sprintf('<a href="product.php?pid=%d">%s</a>', $product->id, $product->data['Product Code']));

					}else {

						$history_abstract=sprintf(_('%s %s removed'), -$delta_qty, sprintf('<a href="product.php?pid=%d">%s</a>', $product->id, $product->data['Product Code']));
					}
				}

				if ($history_abstract!='') {

					$history_data=array(
						'History Abstract'=>$history_abstract,
						'History Details'=>''
					);
					$this->add_subject_history($history_data);
				}
			}


		}


		if (array_key_exists('Supplier Metadata', $data)) {

			$sql = sprintf( "update`Order Transaction Fact` set  `Supplier Metadata`=%s  where `Order Transaction Fact Key`=%d ",
				prepare_mysql($data['Supplier Metadata']),
				$otf_key

			);
			//        print "$sql\n";
			mysql_query($sql);
		}


		if (!$this->skip_update_after_individual_transaction) {


			$this->update_number_products();
			$this->update_insurance();

			$this->update_discounts_items();
			$this->update_totals();



			$this->update_shipping($dn_key, false);
			$this->update_charges($dn_key, false);
			$this->update_discounts_no_items($dn_key);


			$this->update_deal_bridge();

			//$this->update_deals_usage();now forked

			$this->update_totals();


			$this->update_number_products();

			$this->apply_payment_from_customer_account();

		}

		//print "xx $gross $gross_discounts ";



		$net_amount=$gross-$gross_discounts;
		return array(
			'updated'=>true,
			'otf_key'=>$otf_key,
			'to_charge'=>money($net_amount, $this->data['Order Currency']),
			'net_amount'=>$net_amount,
			'delta_net_amount'=>$net_amount-$old_net_amount,
			'qty'=>$quantity,
			'delta_qty'=>$quantity-$old_quantity,
			'bonus qty'=>$bonus_quantity,
			'discount_percentage'=>($gross_discounts>0?percentage($gross_discounts, $gross, $fixed=1, $error_txt='NA', $psign=''):'')
		);

		//  print "$sql\n";


	}



	function create_order_header() {





		//calculate the order total
		$this->data ['Order Items Gross Amount'] = 0;
		$this->data ['Order Items Discount Amount'] = 0;




		$sql = sprintf( "insert into `Order Dimension` (
		`Order Show in Warehouse Orders`,`Order Telephone`,`Order Customer Fiscal Name`,`Order Email`,		`Order Apply Auto Customer Account Payment`,`Order Tax Number`,`Order Tax Number Valid`,`Order Created Date`,`Order Payment Method`,`Order Customer Order Number`,
		`Order Tax Code`,`Order Tax Rate`,`Order Customer Contact Name`,`Order For`,`Order File As`,`Order Date`,`Order Last Updated Date`,`Order Public ID`,`Order Store Key`,`Order Main Source Type`,`Order Customer Key`,`Order Customer Name`,`Order Current Dispatch State`,`Order Current Payment State`,`Order Current XHTML Payment State`,`Order Customer Message`,`Order Original Data MIME Type`,
		`Order Items Gross Amount`,`Order Items Discount Amount`,`Order Original Metadata`,`Order Type`,`Order Currency`,`Order Currency Exchange`,`Order Original Data Filename`,`Order Original Data Source`,`Order Tax Name`,`Order Tax Operations`,`Order Tax Selection Type`) values
		(%s,%s, %s,%s,%s,%s,%s,%s,%s,%d,
		%s,%f,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s,%s ,
		%.2f,%.2f,%s,%s,%s,   %f,%s,%s,%s,%s,%s)",
			prepare_mysql ( $this->data ['Order Show in Warehouse Orders'] ),
			prepare_mysql ( $this->data ['Order Telephone'] ),
			prepare_mysql ( $this->data ['Order Customer Fiscal Name'] ),
			prepare_mysql ( $this->data ['Order Email'] ),
			prepare_mysql ( $this->data ['Order Apply Auto Customer Account Payment'] ),
			prepare_mysql ( $this->data ['Order Tax Number'] ),
			prepare_mysql ( $this->data ['Order Tax Number Valid'] ),
			prepare_mysql ( $this->data ['Order Created Date'] ),
			prepare_mysql ($this->data ['Order Payment Method'] ),

			$this->data ['Order Customer Order Number'],
			prepare_mysql ($this->data ['Order Tax Code'], false ),
			$this->data ['Order Tax Rate'],




			prepare_mysql ( $this->data ['Order Customer Contact Name'], false ),
			prepare_mysql ( $this->data ['Order For'] ),
			prepare_mysql ( $this->data ['Order File As'] ),
			prepare_mysql ( $this->data ['Order Date'] ),
			prepare_mysql ( $this->data ['Order Date'] ),
			prepare_mysql ( $this->data ['Order Public ID'] ),
			prepare_mysql ( $this->data ['Order Store Key'] ),

			prepare_mysql ( $this->data ['Order Main Source Type'] ),
			prepare_mysql ( $this->data ['Order Customer Key'] ),
			prepare_mysql ( $this->data ['Order Customer Name'] , false),
			prepare_mysql ( $this->data ['Order Current Dispatch State'] ),
			prepare_mysql ( $this->data ['Order Current Payment State'] ),
			prepare_mysql ( $this->data ['Order Current XHTML Payment State'] ),
			prepare_mysql ( $this->data ['Order Customer Message'] ),
			prepare_mysql ( $this->data ['Order Original Data MIME Type'] ),


			$this->data ['Order Items Gross Amount'],
			$this->data ['Order Items Discount Amount'],
			prepare_mysql ( $this->data ['Order Original Metadata'] ),
			prepare_mysql ( $this->data ['Order Type'] ),
			prepare_mysql( $this->data ['Order Currency'] ),
			$this->data ['Order Currency Exchange'],
			prepare_mysql( $this->data ['Order Original Data Filename'] ),
			prepare_mysql( $this->data ['Order Original Data Source'] ),
			prepare_mysql( $this->data ['Order Tax Name'] ),
			prepare_mysql( $this->data ['Order Tax Operations'] ),
			prepare_mysql( $this->data ['Order Tax Selection Type'] )
		)

		;

		if (mysql_query( $sql )) {
			$this->id = mysql_insert_id();
			$this->data ['Order Key'] = $this->id;
		}
		else {
			exit ( "\n\n$sql\n\n  Error coan not create order header");
		}

	}



	function get_data($key, $id) {
		if ($key == 'id') {
			$sql = sprintf( "select * from `Order Dimension` where `Order Key`=%d", $id );
			$result = mysql_query( $sql );
			if ($this->data = mysql_fetch_array( $result, MYSQL_ASSOC )) {
				$this->id = $this->data ['Order Key'];
			}


		}
		elseif ($key == 'public id' or $key == 'public_id') {
			$sql = sprintf( "select * from `Order Dimension` where `Order Public ID`=%s", prepare_mysql ( $id ) );
			$result = mysql_query( $sql );
			//print "$sql\n";
			if ($this->data = mysql_fetch_array( $result, MYSQL_ASSOC )) {
				$this->id = $this->data ['Order Key'];
			}




		}


		if ($this->id) {
			$this->set_display_currency($this->data['Order Currency'], 1.0);
		}

	}


	function set_display_currency($currency_code, $exchange) {
		$this->currency_code=$currency_code;
		$this->exchange=$exchange;

	}


	function formatted_net() {
		return money($this->data['Order Total Net Amount']-$this->data['Order Out of Stock Net Amount']-$this->data['Order No Authorized Net Amount']-$this->data['Order Not Found Net Amount']-$this->data['Order Not Due Other Net Amount'], $this->data['Order Currency']);
	}


	function formatted_tax() {
		return money($this->data['Order Total Tax Amount']-$this->data['Order Out of Stock Tax Amount']-$this->data['Order No Authorized Tax Amount']-$this->data['Order Not Found Tax Amount']-$this->data['Order Not Due Other Tax Amount'], $this->data['Order Currency']);

	}


	function formatted_total() {
		return money($this->data['Order Total Net Amount']+$this->data['Order Total Tax Amount']-$this->data['Order Out of Stock Net Amount']-$this->data['Order No Authorized Net Amount']-$this->data['Order Not Found Net Amount']-$this->data['Order Not Due Other Net Amount']-$this->data['Order Out of Stock Tax Amount']-$this->data['Order No Authorized Tax Amount']-$this->data['Order Not Found Tax Amount']-$this->data['Order Not Due Other Tax Amount'], $this->data['Order Currency']);

	}


	function get($key = '') {


		if (array_key_exists( $key, $this->data ))
			return $this->data [$key];


		if ($key=='Shipping Net Amount' and $this->data['Order Shipping Method']=='TBC') {
			return _('TBC');
		}

		if (preg_match('/^(Balance (Total|Net|Tax)|Invoiced Total Net Adjust|Invoiced Total Tax Adjust|Invoiced Refund Net|Invoiced Refund Tax|Total|Items|Invoiced Items|Invoiced Tax|Invoiced Net|Invoiced Charges|Payments|To Pay|Invoiced Shipping|Invoiced Insurance |(Shipping |Charges |Insurance )?Net).*(Amount)$/', $key)) {
			$amount='Order '.$key;
			return money($this->exchange*$this->data[$amount], $this->currency_code);
		}
		if (preg_match('/^Number (Items|Products)$/', $key)) {

			$amount='Order '.$key;

			return number($this->data[$amount]);
		}


		switch ($key) {


		case ('State Index'):
			//'In Process by Customer','Waiting for Payment Confirmation','In Process','Submitted by Customer','Ready to Pick',
			//'Picking & Packing','Packing','Packed','Packed Done','Ready to Ship','Dispatched','Cancelled','Suspended','Cancelled by Customer'

			switch ($this->data['Order Current Dispatch State']) {
			case 'In Process':
				return 10;
				break;
			case 'In Process by Customer':
				return 20;
				break;
			case 'Waiting for Payment Confirmation':
				return 25;
				break;
			case 'Submitted by Customer':
				return 30;
				break;
			case 'Ready to Pick':
				return 40;
				break;
			case 'Picking & Packing':
				return 50;
				break;
			case 'Packing':
				return 60;
				break;
			case 'Packed':
				return 70;
				break;
			case 'Packed Done':
				return 80;
				break;
			case 'Ready to Ship':
				return 90;
				break;
			case 'Dispatched':
				return 100;
				break;
			case 'Cancelled':
				return -10;
				break;
			case 'Cancelled by Customer':
				return -8;
				break;
			case 'Suspended':
				return -5;
				break;

			default:
				return 0;
				break;
			}

			break;

		case('Corporate Currency Invoiced Total Amount'):

			global $corporate_currency;
			$_key=preg_replace('/Corporate Currency /', '', $key);
			return money(($this->data['Order Invoiced Net Amount']+$this->data['Order Invoiced Tax Amount']) *$this->data['Order Currency Exchange'], $corporate_currency);
			break;
		case('Corporate Currency Balance Total Amount'):
			global $corporate_currency;
			$_key=preg_replace('/Corporate Currency /', '', $key);
			return money($this->data['Order '.$_key]*$this->data['Order Currency Exchange'], $corporate_currency);
			break;

		case("Sticky Note"):
			return nl2br($this->data['Order Sticky Note']);
			break;
		case('Deal Amount Off'):
			return money(-1*$this->data['Order Deal Amount Off'], $this->currency_code);
		case('Items Gross Amount After No Shipped'):
			return money($this->data['Order Items Gross Amount']-$this->data['Order Out of Stock Net Amount'], $this->currency_code);
		case('Tax Rate'):
			return percentage($this->data['Order Tax Rate'], 1);
			break;
		case('Order Out of Stock Amount'):
			return $this->data['Order Out of Stock Net Amount']+$this->data['Order Out of Stock Tax Amount'];
		case('Out of Stock Amount'):
			return money(-1*($this->data['Order Out of Stock Net Amount']+$this->data['Order Out of Stock Tax Amount']), $this->data['Order Currency']);
		case('Invoiced Total Tax Amount'):
			return money($this->data['Order Invoiced Tax Amount'], $this->data['Order Currency']);
			break;
		case('Out of Stock Net Amount'):
			return money(-1*$this->data['Order Out of Stock Net Amount'], $this->data['Order Currency']);
			break;
		case('Not Found Net Amount'):
			return money(-1*$this->data['Order Not Found Net Amount'], $this->data['Order Currency']);
			break;
		case('Not Due Other Net Amount'):
			return money(-1*$this->data['Order Not Due Other Net Amount'], $this->data['Order Currency']);
			break;
		case('No Authorized Net Amount'):
			return money(-1*$this->data['Order No Authorized Net Amount'], $this->data['Order Currency']);
			break;
		case('Invoiced Total Net Amount'):
			return money($this->data['Order Invoiced Net Amount'], $this->data['Order Currency']);
			break;
		case('Invoiced Total Amount'):
			return money($this->data['Order Invoiced Net Amount']+$this->data['Order Invoiced Tax Amount'], $this->data['Order Currency']);
			break;
		case ('Invoiced Refund Total Amount'):
			return money($this->data['Order Invoiced Refund Net Amount']+$this->data['Order Invoiced Refund Tax Amount'], $this->data['Order Currency']);

			break;
		case('Shipping And Handing Net Amount'):
			return money($this->data['Order Shipping Net Amount']+$this->data['Order Charges Net Amount']);
			break;
		case('Date'):
		case('Last Updated Date'):
		case('Cancelled Date'):
		case('Created Date'):
		case('Send to Warehouse Date'):
		case('Suspended Date'):
		case('Checkout Submitted Payment Date'):
		case('Checkout Completed Payment Date'):
		case('Submitted by Customer Date'):
		case('Dispatched Date'):
		case('Post Transactions Dispatched Date'):
		case('Packed Done Date'):

			return strftime("%e %b %Y %H:%M", strtotime($this->data['Order '.$key].' +0:00'));
			break;
		case('Submitted by Customer Interval'):
			if ($this->data['Order Submitted by Customer Date']=='') {
				return '';
			}
			include_once 'common_natural_language.php';
			return seconds_to_string(
				gmdate('U', strtotime($this->data['Order Submitted by Customer Date']))-gmdate('U', strtotime($this->data['Order Created Date']))
			);
			break;
		case('Send to Warehouse Interval'):
			if ($this->data['Order Submitted by Customer Date']=='' or $this->data['Order Send to Warehouse Date']=='') {
				return '';
			}
			include_once 'common_natural_language.php';
			return seconds_to_string(
				gmdate('U', strtotime($this->data['Order Send to Warehouse Date']))-gmdate('U', strtotime($this->data['Order Submitted by Customer Date']))
			);
			break;
		case('Packed Done Interval'):
			if ($this->data['Order Send to Warehouse Date']=='' or $this->data['Order Packed Done Date']=='') {
				return '';
			}
			include_once 'common_natural_language.php';
			return seconds_to_string(
				gmdate('U', strtotime($this->data['Order Packed Done Date']))-gmdate('U', strtotime($this->data['Order Send to Warehouse Date']))
			);
			break;
		case('Dispatched Interval'):
			if ($this->data['Order Packed Done Date']=='' or $this->data['Order Dispatched Date']=='') {
				return '';
			}
			include_once 'common_natural_language.php';
			return seconds_to_string(
				gmdate('U', strtotime($this->data['Order Dispatched Date']))-gmdate('U', strtotime($this->data['Order Packed Done Date']))
			);
			break;

		case ('Order Main Ship To Key') :
			$sql = sprintf( "select `Ship To Key`,count(*) as  num from `Order Transaction Fact` where `Order Key`=%d group by `Ship To Key` order by num desc limit 1", $this->id );
			$res = mysql_query( $sql );
			if ($row2 = mysql_fetch_array( $res, MYSQL_ASSOC )) {
				return $row2 ['Ship To Key'];
			} else
				return '';

			break;
		case ('Order Main Billing To Key') :
			$sql = sprintf( "select `Billing To Key`,count(*) as  num from `Order Transaction Fact` where `Order Key`=%d group by `Billing To Key` order by num desc limit 1", $this->id );
			$res = mysql_query( $sql );
			if ($row2 = mysql_fetch_array( $res, MYSQL_ASSOC )) {
				return $row2 ['Billing To Key'];
			} else
				return '';

			break;



		case ('Weight'):

			include_once 'utils/natural_language.php';

			if ($this->data['Order Current Dispatch State']=='Dispatched') {
				if ($this->data['Order Weight']=='')
					return "&#8494;" .weight($this->data['Order Dispatched Estimated Weight']);
				else
					return weight($this->data['Order Weight']);
			} else {
				return "&#8494;" .weight($this->data['Order Estimated Weight']);
			}
			break;


		case ('State'):
			//'In Process by Customer','In Process','Submitted by Customer','Ready to Pick','Picking & Packing','Ready to Ship','Dispatched','Unknown','Packing','Packed','Cancelled','Suspended'  case('Current Dispatch State'):
			switch ($this->data['Order Current Dispatch State']) {
			case 'In Process':
				return _('In Process');
				break;
			case 'In Process by Customer':
				return _('In Process by Customer');
				break;
			case 'Submitted by Customer':
				return _('Submitted by Customer');
				break;
			case 'Ready to Pick':
				return _('Ready to Pick');
				break;
			case 'Picking & Packing':
				return _('Picking & Packing');
				break;
			case 'Packed Done':
				return _('Packed & Checked');
				break;
			case 'Ready to Ship':
				return _('Ready to Ship');
				break;
			case 'Dispatched':
				return _('Dispatched');
				break;
			case 'Unknown':
				return _('Unknown');
				break;
			case 'Packing':
				return _('Packing');
				break;
			case 'Cancelled':
				return _('Cancelled');
				break;
			case 'Suspended':
				return _('Suspended');
				break;

			default:
				return $this->data['Order Current Dispatch State'];
				break;
			}

			break;

		case 'Number Items':
		case 'Number Items Out of Stock':
		case 'Number Items Returned':
		case 'Number Items with Deals':

			return number($this->data['Order '.$key]);
			break;


		}
		$_key = ucwords( $key );
		if (array_key_exists( $_key, $this->data ))
			return $this->data [$_key];

		return false;
	}


	function get_deliveries($scope='keys') {

		if ($scope=='objects') {
			include_once 'class.DeliveryNote.php';
		}


		$deliveries=array();
		$sql=sprintf("select `Delivery Note Key` from `Order Transaction Fact` where `Order Key`=%d  group by `Delivery Note Key`",
			$this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				if ($row['Delivery Note Key']=='')continue;

				if ($scope=='objects') {

					$deliveries[$row['Delivery Note Key']]=new DeliveryNote($row['Delivery Note Key']);

				}else {
					$deliveries[$row['Delivery Note Key']]=$row['Delivery Note Key'];
				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		return $deliveries;

	}


	function get_invoices($scope='keys') {

		if ($scope=='objects') {
			include_once 'class.Invoice.php';
		}


		$invoices=array();
		$sql=sprintf("select `Invoice Key` from `Order Transaction Fact` where `Order Key`=%d  group by `Invoice Key`",
			$this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				if ($row['Invoice Key']=='')continue;

				if ($scope=='objects') {

					$invoices[$row['Invoice Key']]=new Invoice($row['Invoice Key']);

				}else {
					$invoices[$row['Invoice Key']]=$row['Invoice Key'];
				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		return $invoices;

	}


	function get_payments($scope='keys') {

		if ($scope=='objects') {
			include_once 'class.Payment.php';
		}


		$payments=array();
		$sql=sprintf("select `Payment Key` from `Order Payment Bridge` where `Order Key`=%d  ",
			$this->id);

		if ($result=$this->db->query($sql)) {
			foreach ($result as $row) {
				if ($row['Payment Key']=='')continue;

				if ($scope=='objects') {

					$payments[$row['Payment Key']]=new Payment($row['Payment Key']);

				}else {
					$payments[$row['Payment Key']]=$row['Payment Key'];
				}
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		return $payments;

	}



	function get_delivery_notes_ids() {
		$sql=sprintf("select `Delivery Note Key` from `Order Delivery Note Bridge` where `Order Key`=%d ", $this->id);
		//print "$sql\n";
		$res = mysql_query( $sql );
		$delivery_notes=array();
		while ($row = mysql_fetch_array( $res, MYSQL_ASSOC )) {
			if ($row['Delivery Note Key']) {
				$delivery_notes[$row['Delivery Note Key']]=$row['Delivery Note Key'];
			}

		}
		return $delivery_notes;

	}


	function get_delivery_notes_objects() {
		$delivery_notes=array();
		$delivery_notes_ids=$this->get_delivery_notes_ids();
		foreach ($delivery_notes_ids as $delivery_notes_id) {
			$delivery_notes[$delivery_notes_id]=new DeliveryNote($delivery_notes_id);
		}
		return $delivery_notes;
	}


	function get_invoices_to_pay_abs_amount() {
		$invoices_to_pay_abs_amount=0;
		$sql=sprintf("select sum(abs(`Invoice Outstanding Total Amount`)) as amount from `Order Invoice Bridge` B left join `Invoice Dimension` I on (I.`Invoice Key`=B.`Invoice Key`) where `Order Key`=%d",
			$this->id
		);
		$res = mysql_query( $sql );
		while ($row = mysql_fetch_array( $res, MYSQL_ASSOC )) {
			$invoices_to_pay_abs_amount=$row['amount'];
		}
		return $invoices_to_pay_abs_amount;
	}


	function get_invoices_ids() {

		$invoices=array();

		$sql=sprintf("select `Invoice Key` from `Order Invoice Bridge` where `Order Key`=%d ", $this->id);

		//print "$sql\n";
		$res = mysql_query( $sql );
		while ($row = mysql_fetch_array( $res, MYSQL_ASSOC )) {
			if ($row['Invoice Key']) {
				$invoices[$row['Invoice Key']]=$row['Invoice Key'];
			}

		}



		return $invoices;

	}


	function get_number_invoices() {

		return count($this->get_invoices_ids());
	}


	function get_invoices_objects() {
		$invoices=array();
		$invoices_ids=$this->get_invoices_ids();
		foreach ($invoices_ids as $order_id) {
			$invoices[$order_id]=new Invoice($order_id);
		}
		return $invoices;
	}





	function auto_account_payments($value, $options='') {

		$this->update_field('Order Apply Auto Customer Account Payment', $value, $options);


		if ($value=='Yes') {
			$this->apply_payment_from_customer_account();
		}else {


		}

	}



	function update_field_switcher($field, $value, $options='', $metadata='') {

		switch ($field) {

		case 'auto_account_payments':
			$this->auto_account_payments($value, $options);
			break;
		case('Order Tax Number'):
			$this->update_field($field, $value, $options);
			$this->update_tax();
			break;
		case('Order XHTML Invoices'):
			$this->update_xhtml_invoices();
			break;
		case('Order XHTML Delivery Notes'):
			$this->update_xhtml_delivery_notes();
			break;
		case('Sticky Note'):
			$this->update_field('Order '.$field, $value, 'no_null');
			$this->new_value=html_entity_decode($this->new_value);
			break;
		default:
			$base_data=$this->base_data();


			if (array_key_exists($field, $base_data)) {
				// print "xxx-> $field : $value -> ".$this->data[$field]." \n";

				if ($value!=$this->data[$field]) {

					$this->update_field($field, $value, $options);
				}
			}
		}

	}



	function update_xhtml_invoices() {
		$prefix='';
		$this->data ['Order XHTML Invoices'] ='';
		foreach ($this->get_invoices_objects() as $invoice) {

			if ($invoice->get('Invoice Paid')=='Yes')
				$state='<img src="/art/icons/money.png" style="height:14px">';

			else

				$state='<img src="/art/icons/money_bw.png" style="width:14px">';

			$this->data ['Order XHTML Invoices'] .= sprintf( ' %s <a href="invoice.php?id=%d">%s%s</a> <a href="invoice.pdf.php?id=%d" target="_blank"><img style="height:10px;position:relative;bottom:2.5px" src="/art/pdf.gif" alt=""></a><br/>',
				$state,
				$invoice->data ['Invoice Key'],
				$prefix,
				$invoice->data ['Invoice Public ID'],
				$invoice->data ['Invoice Key'] );
		}
		$this->data ['Order XHTML Invoices'] =_trim(preg_replace('/\<br\/\>$/', '', $this->data ['Order XHTML Invoices']));
		$sql=sprintf("update `Order Dimension` set `Order XHTML Invoices`=%s where `Order Key`=%d "
			, prepare_mysql($this->data['Order XHTML Invoices'])
			, $this->id
		);
		mysql_query($sql);
	}


	function update_xhtml_delivery_notes() {
		$prefix='';
		$this->data ['Order XHTML Delivery Notes'] ='';
		foreach ($this->get_delivery_notes_objects() as $delivery_note) {
			//'Picker & Packer Assigned','Picking & Packing','Packer Assigned','Ready to be Picked','Picker Assigned','Picking','Picked','Packing','Packed','Approved','Dispatched','Cancelled','Cancelled to Restock','Packed Done'

			//print $delivery_note->get('Delivery Note State');

			if ($delivery_note->get('Delivery Note State')=='Dispatched')
				$state='<img src="/art/icons/lorry.png" style="height:14px">';
			else if ($delivery_note->get('Delivery Note State')=='Packed Done')
				$state='<img src="/art/icons/package.png" style="height:14px">';
			else if ($delivery_note->get('Delivery Note State')=='Approved')
				$state='<img src="/art/icons/package_green.png" style="height:14px">';
			else

				$state='<img src="/art/icons/cart.png" style="width:14px">';

			$this->data ['Order XHTML Delivery Notes'] .= sprintf( '%s <a href="dn.php?id=%d">%s%s</a> <a href="dn.pdf.php?id=%d" target="_blank"><img style="height:10px;position:relative;bottom:2.5px" src="/art/pdf.gif" alt=""></a><br/>',
				$state,
				$delivery_note->data ['Delivery Note Key'],
				$prefix,
				$delivery_note->data ['Delivery Note ID'], $delivery_note->data ['Delivery Note Key'] );
		}
		$this->data ['Order XHTML Delivery Notes'] =_trim(preg_replace('/\<br\/\>$/', '', $this->data ['Order XHTML Delivery Notes']));

		$sql=sprintf("update `Order Dimension` set `Order XHTML Delivery Notes`=%s where `Order Key`=%d "
			, prepare_mysql($this->data['Order XHTML Delivery Notes'])
			, $this->id
		);
		mysql_query($sql);
	}


	function cutomer_rankings() {
		$sql = sprintf( "select `Customer Key` as id,`Customer Orders` as orders, (select count(*) from `Customer Dimension` as TC where TC.`Customer Orders`<C.`Customer Orders`) as better,(select count(DISTINCT `Customer Key` ) from `Customer Dimension`) total  from `Customer Dimension` as C order by `Customer Orders` desc ;" );

		$orders = - 99999;
		$position = 0;

		$result = mysql_query( $sql );
		while ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {

			if ($row ['orders'] != $orders) {
				$position ++;
				$orders = $row ['orders'];
			}
			$better_than = $row ['better'];
			$total = $row ['total'];
			if ($total > 0)
				$top = sprintf( "%f", 100 * (1.0 - ($better_than / $total)) );
			else
				$top = 'null';
			$id = $row ['id'];
			$sql = sprintf( "update `Customer Dimension` set `Customer Orders Top Percentage`=%s,`Customer Orders Position`=%d,`Customer Has More Orders Than`=%d where `Customer Key`=%d", $top, $position, $better_than, $id );
			// print "$sql\n";
			mysql_query( $sql );
		}
	}


	function compare_addresses($cdata) {

		//check if the addresses are the same:
		$diff_result = array_diff( $cdata ['address_data'], $cdata ['shipping_data'] );

		if (count( $diff_result ) == 0) {

			$this->same_address = true;
			$this->same_contact = true;
			$this->same_company = true;

			$this->same_telephone = true;

		} else {


			$percentage = array ('address1' => 1, 'town' => 1, 'country' => 1, 'country_d1' => 1, 'postcode' => 1 );
			$percentage_address = array ();

			foreach ( $diff_result as $key => $value ) {
				similar_text( $cdata ['shipping_data'] [$key], $cdata ['address_data'] [$key], $p );
				$percentage [$key] = $p / 100;
				if (preg_match( '/address1|town|^country$|postcode|country_d1/i', $key ))
					$percentage_address [$key] = $p / 100;
			}
			if (count( $percentage ) == 0)
				$avg_percentage = 1;
			else
				$avg_percentage = average ( $percentage );

			if (count( $percentage_address ) == 0)
				$avg_percentage_address = 1;
			else
				$avg_percentage_address = average ( $percentage_address );

			//   print "AVG DIFF O:$avg_percentage A:$avg_percentage_address \n";


			if ($cdata ['shipping_data'] ['name'] == '' or ! array_key_exists( 'name', $diff_result ))
				$this->same_contact = true;
			else {
				$_max = 1000000;
				$irand = mt_rand( 0, 1000000 );
				$rand = $irand / $_max;
				if ($rand < $percentage ['name'] and $percentage ['name'] > .90) {
					$this->same_contact = true;

				} else
					$this->same_contact = false;
			}
			if ($cdata ['shipping_data'] ['company'] == '' or ! array_key_exists( 'company', $diff_result ))
				$this->same_company = true;
			else {
				$_max = 1000000;
				$irand = mt_rand( 0, 1000000 );
				$rand = $irand / $_max;

				if ($rand < $percentage ['company'] and $percentage ['company'] > .90) {
					$this->same_company = true;
				} else
					$this->same_company = false;
			}

			if (array_key_exists( 'telephone', $diff_result ))
				$this->same_telephone = false;
			else
				$this->same_telephone = true;

			if ($avg_percentage_address == 1)
				$this->same_address = true;
			else
				$this->same_address = false;


		}

	}


	function update_product_sales() {
		return;
		if ($this->skip_update_product_sales)
			return;




		$stores=array();
		$family=array();
		$departments=array();
		$sql = "select OTF.`Product Key` ,`Product Family Key`,`Product Store Key` from `Order Transaction Fact` OTF left join `Product Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)where `Order Key`=" . $this->data ['Order Key']." group by OTF.`Product Key`";
		$result = mysql_query( $sql );
		//   print $sql;
		if ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
			$product=new Product($row['Product Key']);
			$product->update_sales();
			$family[$row['Product Family Key']]=true;
			$store[$row['Product Store Key']]=true;
		}
		foreach ($family as $key=>$val) {
			$family=new Family($key);
			$family->update_sales_data();
			$sql = sprintf("select `Product Department Key`  from `Product Family Department Bridge` where `Product Family Key`=%d" , $key);
			$result = mysql_query( $sql );
			while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
				$departments[$row['Product Department Key']]=true;
			}

		}
		foreach ($departments as $key=>$val) {
			$department=new Department($key);
			$department->update_sales_data();
		}


		foreach ($store as $key=>$val) {
			$store=new Store($key);
			$store->update_sales();
		}

	}



	function get_items_totals_by_adding_transactions() {

		global $account;

		$sql = sprintf("select
		sum(`Order Out of Stock Lost Amount`) as out_of_stock_net,
		sum(`Order Out of Stock Lost Amount`*`Transaction Tax Rate`) as out_of_stock_tax,
		sum(if(`Order Quantity`>0, `No Shipped Due No Authorized`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_authorized_net,
		sum(if(`Order Quantity`>0, `No Shipped Due No Authorized`*`Transaction Tax Rate`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_authorized_tax,
		sum(if(`Order Quantity`>0, `No Shipped Due Not Found`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_found_net,
		sum(if(`Order Quantity`>0, `No Shipped Due Not Found`*`Transaction Tax Rate`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_found_tax,
		sum(if(`Order Quantity`>0, `No Shipped Due Other`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_due_other_net,
		sum(if(`Order Quantity`>0, `No Shipped Due Other`*`Transaction Tax Rate`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_due_other_tax,


		sum(`Estimated Dispatched Weight`) as disp_estimated_weight,sum(`Estimated Weight`) as estimated_weight,sum(`Weight`) as weight,
		sum(`Transaction Tax Rate`*(`Order Transaction Amount`)) as tax,
		sum(`Order Transaction Gross Amount`) as gross,
		sum(`Order Transaction Total Discount Amount`) as discount,
		sum(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as total_items_net,
		sum(`Invoice Transaction Shipping Amount`) as shipping,
		sum(`Invoice Transaction Charges Amount`) as charges    from `Order Transaction Fact` where
		`Order Key`=%d" , $this->id);

		$result = mysql_query( $sql );
		if ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {




			$total_not_dispatch_net=$row['out_of_stock_net']+$row['not_authorized_net']+$row['not_found_net']+$row['not_due_other_net'];
			$net=round($row ['total_items_net']-$total_not_dispatch_net, 2);

			$total_not_dispatch_tax=$row['out_of_stock_tax']+$row['not_authorized_tax']+$row['not_found_tax']+$row['not_due_other_tax'];
			$tax= round($row ['tax']-$total_not_dispatch_tax, 2);



			$this->data ['Order Items Gross Amount'] = round($row ['gross'], 2);
			$this->data ['Order Items Discount Amount'] =  round($row ['discount'], 2);
			$this->data ['Order Items Net Amount'] =  $net;
			$this->data ['Order Items Tax Amount']= $tax;
			$this->data ['Order Items Total Amount']= $this->data ['Order Items Net Amount'] +$this->data ['Order Items Tax Amount'];
			$this->data ['Order Estimated Weight']= $row ['estimated_weight'];
			$this->data ['Order Dispatched Estimated Weight']= $row ['disp_estimated_weight'];



		}


	}



	function accept() {
		$this->data['Order Invoiced Balance Net Amount']=$this->data ['Order Items Net Amount'];
		$this->data['Order Invoiced Balance Tax Amount']=$this->data ['Order Items Tax Amount'];
		$this->data['Order Invoiced Balance Total Amount']=$this->data ['Order Items Total Amount'];

	}



	function update_invoices($args='') {
		global $myconf;
		$sql=sprintf("select `Invoice Key` from `Order Transaction Fact` where `Order Key`=%d group by `Invoice Key`", $this->id);

		$res = mysql_query( $sql );
		$this->invoices=array();
		while ($row = mysql_fetch_array( $res, MYSQL_ASSOC )) {
			if ($row['Invoice Key']) {
				$invoice=new Invoice($row['Invoice Key']);
				$this->invoices[$row['Invoice Key']]=$invoice;
			}

		}
		//update no normal fields
		$this->data ['Order XHTML Invoices'] ='';
		foreach ($this->invoices as $invoice) {
			$this->data ['Order XHTML Invoices'] .= sprintf( '<a href="invoice.php?id=%d">%s</a>, ', $invoice->data ['Invoice Key'], $invoice->data ['Invoice Public ID'] );

		}
		$this->data ['Order XHTML Invoices'] =_trim(preg_replace('/\, $/', '', $this->data ['Order XHTML Invoices']));
		//$where_dns=preg_replace('/\,$/',')',$where_dns);

		if (!preg_match('/no save/i', $args)) {
			$sql=sprintf("update `Order Dimension`  set `Order XHTML Invoices`=%s where `Order Key`=%d"
				, prepare_mysql($this->data ['Order XHTML Invoices'])
				, $this->id
			);

			mysql_query($sql);

		}

	}


	function update_delivery_notes($args='') {


		$this->update_xhtml_delivery_notes();


	}




	function update_customer_history() {
		//print $this->data['Order Current Dispatch State']."\n";
		$customer=new Customer ($this->data['Order Customer Key']);
		switch ($this->data['Order Current Dispatch State']) {

		case ('Picking & Packing'):
		case('Ready to Pick'):
		case('Ready to Ship'):
		case('Dispatched'):
			$customer->update_history_order_in_warehouse($this);
			break;
		default:

			break;
		}



	}



	function update_estimated_weight() {

		$sql=sprintf("select `Order Transaction Fact Key`, `Product Package Weight`, P.`Product ID`,`Order Bonus Quantity`,`Order Quantity` from `Order Transaction Fact` OTF left join `Product Dimension` P on (OTF.`Product ID`=P.`Product ID`)  where `Order Key`=%d ",
			$this->id);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)   ) {

			if ($row['Product ID']) {
				$estimated_weight=$row['Product Package Weight']*($row['Order Bonus Quantity']+$row['Order Quantity']);
				$sql=sprintf("update `Order Transaction Fact` set `Estimated Weight`=%f where `Order Transaction Fact Key`=%d",
					$estimated_weight,
					$row['Order Transaction Fact Key']
				);

			}

		}


		$this->update_totals();


		foreach ($this->get_delivery_notes_objects() as $dn) {
			$dn->update_item_totals();
		}

	}



	function update_dispatch_state($force=false) {


		//Line below has to be replaced, the calling functions have to decide instead, but to lazy now to do it
		if ( $this->data['Order Current Dispatch State']=='Dispatched'  and $this->data['Order Item Actions Taken']!='None') {
			$this->update_post_dispatch_state();
			return;
		}

		if (!$force) {
			if (  in_array($this->data['Order Current Dispatch State'], array('In Process by Customer', 'Submitted by Customer', 'Dispatched', 'Cancelled', 'Suspended')) )
				return;
		}

		$old_dispatch_state=$this->data['Order Current Dispatch State'];

		$xhtml_dispatch_state='';

		$dispatch_state='Unknown';

		//

		$sql=sprintf("select `Delivery Note XHTML State`,`Delivery Note State`,DN.`Delivery Note Key`,DN.`Delivery Note ID`,`Delivery Note Fraction Picked`,`Delivery Note Assigned Picker Alias`,`Delivery Note Fraction Packed`,`Delivery Note Assigned Packer Alias` from `Order Transaction Fact` B  left join `Delivery Note Dimension` DN  on (DN.`Delivery Note Key`=B.`Delivery Note Key`)
		where `Order Key`=%d group by B.`Delivery Note Key`  order by Field (`Delivery Note State`,  'Dispatched','Cancelled','Cancelled to Restock','Approved' ,'Packed Done' , 'Packed','Ready to be Picked','Picker Assigned','Packer Assigned','Picker & Packer Assigned','Picked','Picking' ,'Packing' ,'Picking & Packing') ", $this->id);

		$res = mysql_query( $sql );
		$delivery_notes=array();
		while ($row = mysql_fetch_array( $res, MYSQL_ASSOC )) {


			//print_r($row);
			if ($row['Delivery Note Key']) {
				if ($row['Delivery Note State']=='Ready to be Picked') {
					$dispatch_state='Ready to Pick';
				}elseif (in_array($row['Delivery Note State'], array('Picker & Packer Assigned', 'Picking & Packing', 'Packer Assigned', 'Ready to be Picked', 'Picker Assigned', 'Picking', 'Picked', 'Packing', 'Packed')) ) {
					$dispatch_state='Picking & Packing';

				}elseif ($row['Delivery Note State']=='Packed Done') {
					$dispatch_state='Packed Done';
				}elseif ($row['Delivery Note State']=='Approved') {
					$dispatch_state='Ready to Ship';
				}elseif ($row['Delivery Note State']=='Dispatched') {
					$dispatch_state='Dispatched';
				}else {
					$dispatch_state='Unknown';
				}

				$status=$row['Delivery Note XHTML State'];




				//$xhtml_dispatch_state.=sprintf('<a href="dn.php?id=%d">%s</a> %s',$row['Delivery Note Key'],$row['Delivery Note ID'],$status);
			}

		}
		$this->data['Order Current XHTML Dispatch State']=$xhtml_dispatch_state;

		//print $xhtml_dispatch_state;



		$sql=sprintf("update `Order Dimension` set `Order Current XHTML Dispatch State`=%s where `Order Key`=%d",
			prepare_mysql($xhtml_dispatch_state, false),
			$this->id
		);
		mysql_query($sql);


		$this->data['Order Current Dispatch State']=$dispatch_state;


		if ($old_dispatch_state!=$this->data['Order Current Dispatch State']) {

			$sql=sprintf("update `Order Dimension` set `Order Current Dispatch State`=%s where `Order Key`=%d"
				, prepare_mysql($this->data['Order Current Dispatch State'])

				, $this->id
			);

			mysql_query($sql);
			$this->update_customer_history();
			$this->update_full_search();
		}

	}


	function update_post_dispatch_state() {


		//print "update_post_dispatch_state\n";

		$old_dispatch_state=$this->data['Order Current Post Dispatch State'];

		$xhtml_dispatch_state='';

		$dispatch_state='NA';

		//

		$sql=sprintf("select `Delivery Note XHTML State`,`Delivery Note State`,DN.`Delivery Note Key`,DN.`Delivery Note ID`,`Delivery Note Fraction Picked`,`Delivery Note Assigned Picker Alias`,`Delivery Note Fraction Packed`,`Delivery Note Assigned Packer Alias` from `Order Post Transaction Dimension` B  left join `Delivery Note Dimension` DN  on (DN.`Delivery Note Key`=B.`Delivery Note Key`) where `Order Key`=%d group by B.`Delivery Note Key`  order by Field (`Delivery Note State`,  'Dispatched','Cancelled','Cancelled to Restock','Approved' ,'Packed Done' , 'Packed','Ready to be Picked','Picker Assigned','Packer Assigned','Picker & Packer Assigned','Picked','Picking' ,'Packing' ,'Picking & Packing') ",
			$this->id);

		$res = mysql_query( $sql );
		$delivery_notes=array();


		//print $sql;
		//exit;

		while ($row = mysql_fetch_array( $res, MYSQL_ASSOC )) {


			//print_r($row);
			if ($row['Delivery Note Key']) {
				if ($row['Delivery Note State']=='Ready to be Picked') {
					$dispatch_state='Ready to Pick';
				}elseif (in_array($row['Delivery Note State'], array('Picker & Packer Assigned', 'Picking & Packing', 'Packer Assigned', 'Ready to be Picked', 'Picker Assigned', 'Picking', 'Picked', 'Packing', 'Packed')) ) {
					$dispatch_state='Picking & Packing';

				}elseif ($row['Delivery Note State']=='Packed Done') {
					$dispatch_state='Packed Done';
				}elseif ($row['Delivery Note State']=='Approved') {
					$dispatch_state='Ready to Ship';
				}elseif ($row['Delivery Note State']=='Dispatched') {
					$dispatch_state='Dispatched';
				}else {
					$dispatch_state='Unknown';
				}

				$status=$row['Delivery Note XHTML State'];




				//$xhtml_dispatch_state.=sprintf('<a href="dn.php?id=%d">%s</a> %s',$row['Delivery Note Key'],$row['Delivery Note ID'],$status);
			}

		}
		//$this->data['Order Current XHTML Dispatch State']=$xhtml_dispatch_state;


		//print $dispatch_state;


		$sql=sprintf("update `Order Dimension` set `Order Current XHTML Post Dispatch State`=%s where `Order Key`=%d",
			prepare_mysql($xhtml_dispatch_state, false),
			$this->id
		);
		mysql_query($sql);



		$this->data['Order Current Post Dispatch State']=$dispatch_state;

		if ($old_dispatch_state!=$this->data['Order Current Dispatch State']) {

			$sql=sprintf("update `Order Dimension` set `Order Current Post Dispatch State`=%s  where `Order Key`=%d"
				, prepare_mysql($this->data['Order Current Post Dispatch State'])

				, $this->id
			);
			//print $sql;
			mysql_query($sql);
			//$this->update_customer_history();
			//$this->update_full_search();
		}



	}


	function set_order_as_dispatched($date) {

		// TODO dont set as dispatched until all the DN are dispatched (no inclide post transactions)

		$this->data['Order Current Dispatch State']='Dispatched';
		$this->data['Order Current XHTML Dispatch State']=_('Dispatched');

		$sql=sprintf("update `Order Dimension` set `Order Dispatched Date`=%s , `Order Current XHTML Dispatch State`=%s ,`Order Current Dispatch State`=%s where `Order Key`=%d"
			, prepare_mysql($date)
			, prepare_mysql($this->data['Order Current XHTML Dispatch State'])
			, prepare_mysql($this->data['Order Current Dispatch State'])
			, $this->id
		);
		mysql_query($sql);

		$this->update_customer_history();
		$this->update_full_search();
		$customer=new Customer($this->data['Order Customer Key']);
		$customer->update_orders();

		$history_data=array(
			'History Abstract'=>_('Order dispatched'),
			'History Details'=>'',
		);
		$this->add_subject_history($history_data);



	}


	function set_order_as_completed($date) {

		// TODO dont set as dispatched until all the DN are dispatched (no inclide post transactions)

		$this->data['Order Current Dispatch State']='Dispatched';
		$this->data['Order Current XHTML Dispatch State']=_('Dispatched');

		$sql=sprintf("update `Order Dimension` set `Order Dispatched Date`=%s , `Order Current XHTML Dispatch State`=%s ,`Order Current Dispatch State`=%s where `Order Key`=%d"
			, prepare_mysql($date)
			, prepare_mysql($this->data['Order Current XHTML Dispatch State'])
			, prepare_mysql($this->data['Order Current Dispatch State'])
			, $this->id
		);
		mysql_query($sql);
		//print "$sql\n";
		$this->update_customer_history();
		$this->update_full_search();

		$customer=new Customer($this->data['Order Customer Key']);
		$customer->update_orders();

	}











	function calculate_state($invoice_extra_info='') {

		$payment_state='';
		$dispatch_state='';
		switch ($this->data['Order Current Dispatch State']) {
		case 'In Process by Customer':
			$dispatch_state=_('In Process by Customer');
			break;
		case 'In Process by Customer':
			$dispatch_state=_('In Process by Customer');
			break;
		case 'In Process':
			$dispatch_state=_('In Process');
			break;
		case 'Submitted by Customer':
			$dispatch_state=_('Submitted by Customer');
			break;
		case 'Ready to Pick':
			$dispatch_state=_('Ready to Pick');
			break;
		case 'Picking & Packing':
			$dispatch_state=_('Picking & Packing');
			break;
		case 'Ready to Ship':
			$dispatch_state=_('Ready to Ship');
			break;
		case 'Dispatched':
			$dispatch_state=_('Dispatched');
			break;
		case 'Packing':
			$dispatch_state=_('Packing');
			break;
		case 'Packed':
			$dispatch_state=_('Packed');
			break;
		case 'Cancelled':
			$dispatch_state=_('Cancelled');
			break;
		case 'Suspended':
			$dispatch_state=_('Suspended');
			break;
		default:
			$dispatch_state=$this->data['Order Current Dispatch State'];
		}

		$state=$dispatch_state;

		/*
		if ($this->data['Order Invoiced']=='Yes') {
			$payment_state=_('Invoiced');
			if ($invoice_extra_info) {
				$payment_state.=' '.$invoice_extra_info;
			}
			switch ($this->data['Order Current Payment State']) {
			case 'Waiting Payment':
				$payment_state.=' ('._('Waiting Payment').')';
				break;
			case 'In Process by Customer':
				$payment_state.=' ('._('Partially Paid').')';
				break;

			default:

			}

		}


		if ($state!='' and $payment_state!='') {
			$state.=', '.$payment_state;

		}
*/
		return $state;
	}



	function update_item_totals_from_order_transactions() {
		if ($this->ghost_order or !$this->data ['Order Key'])
			return;
		$this->get_items_totals_by_adding_transactions();
		$sql = sprintf( "update `Order Dimension` set `Order Items Gross Amount`=%.2f, `Order Items Discount Amount`=%.2f, `Order Items Net Amount`=%.2f , `Order Items Tax Amount`=%.2f where  `Order Key`=%d "
			, $this->data ['Order Items Gross Amount']
			, $this->data ['Order Items Discount Amount']
			, $this->data ['Order Items Net Amount']
			, $this->data ['Order Items Tax Amount']
			, $this->data ['Order Key']
		);

		mysql_query( $sql );



	}


	function update_totals() {

		$number_items=0;
		$number_with_deals=0;
		$number_with_out_of_stock=0;
		$number_with_problems=0;

		$sql=sprintf("select
		count(*) as number_items,
		sum(if(`Order Transaction Total Discount Amount`!=0,1,0)) as number_with_deals ,
		sum(if(`No Shipped Due Out of Stock`!=0,1,0)) as number_with_out_of_stock
		from `Order Transaction Fact` where `Order Key`=%d  ",
			$this->id);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_items=$row['number_items'];
				$number_with_deals=$row['number_with_deals'];
				$number_with_out_of_stock=$row['number_with_out_of_stock'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}

		$sql=sprintf("select
		count(Distinct `Order Transaction Fact Key`) as number_with_problems
		from `Order Post Transaction Dimension` where `Order Key`=%d  ",
			$this->id);


		if ($result=$this->db->query($sql)) {
			if ($row = $result->fetch()) {
				$number_with_problems=$row['number_with_problems'];
			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


		$this->update(
			array(
				'Order Number Items'=>$number_items,
				'Order Number Items with Deals'=>$number_with_deals,
				'Order Number Items Out of Stock'=>$number_with_out_of_stock,
				'Order Number Items Returned'=>$number_with_problems
			)
		);




		include_once 'class.Account.php';

		$account=new Account($this->db);



		if ($account->data['Apply Tax Method']=='Per Item') {
			$this->update_item_totals_from_order_transactions();
			$this->update_totals_from_order_transactions_per_item_method();
			$this->update_no_normal_totals_per_item_method();
		}else {
			$this->update_item_totals_from_order_transactions();
			$this->update_totals_from_order_transactions_per_totals_method();
			$this->update_no_normal_totals_per_totals_method();

		}


	}



	function update_no_normal_totals_per_totals_method($args='') {

		$costs=0;

		$this->data['Order Balance Net Amount']=0;
		$this->data['Order Balance Tax Amount']=0;
		$this->data['Order Balance Total Amount']=0;
		$this->data['Order Outstanding Balance Net Amount']=0;
		$this->data['Order Outstanding Balance Tax Amount']=0;
		$this->data['Order Outstanding Balance Total Amount']=0;

		$this->data['Order Invoiced Balance Net Amount']=0;
		$this->data['Order Invoiced Balance Tax Amount']=0;
		$this->data['Order Invoiced Balance Total Amount']=0;
		$this->data['Order Invoiced Outstanding Balance Net Amount']=0;
		$this->data['Order Invoiced Outstanding Balance Tax Amount']=0;
		$this->data['Order Invoiced Outstanding Balance Total Amount']=0;
		$this->data['Order Invoiced Refund Net Amount']=0;
		$this->data['Order Invoiced Refund Tax Amount']=0;
		$this->data['Order Invoiced Refund Notes']='';

		$this->data['Order Tax Credited Amount']=0;
		$this->data['Order Net Credited Amount']=0;
		$this->data['Order Tax Refund Amount']=0;
		$this->data['Order Net Refund Amount']=0;

		$sql = "select count(*) as number_otfs,
	sum(IFNULL(`Cost Supplier`,0)+IFNULL(`Cost Storing`,0)+IFNULL(`Cost Handing`,0)+IFNULL(`Cost Shipping`,0))as costs,
	sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as net,
	sum(`Invoice Transaction Item Tax Amount`) as tax,
	sum(`Order Transaction Amount`) as original_net,
	sum(`Order Transaction Amount`*`Transaction Tax Rate`) as original_tax,
	sum(`Invoice Transaction Net Refund Items`) as ref_net,
	sum(`Invoice Transaction Tax Refund Items`) as ref_tax,
	sum(`Invoice Transaction Outstanding Net Balance`) as ob_net ,
	sum(`Invoice Transaction Outstanding Tax Balance`) as ob_tax ,
	sum(`Invoice Transaction Outstanding Refund Net Balance`) as ref_ob_net ,
	sum(`Invoice Transaction Outstanding Refund Tax Balance`) as ref_ob_tax ,

	sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as inv_items,
	sum(`Invoice Transaction Item Tax Amount`) as inv_items_tax,

	sum(`Invoice Transaction Shipping Amount`) as inv_shp,
	sum(`Invoice Transaction Shipping Tax Amount`) as inv_shp_tax,

	sum(`Invoice Transaction Charges Amount`) as inv_charges,
	sum(`Invoice Transaction Charges Tax Amount`) as inv_charges_tax,


	sum(`Invoice Transaction Insurance Amount`) as inv_insurance,
	sum(`Invoice Transaction Insurance Tax Amount`) as inv_insurance_tax,

	sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`+`Invoice Transaction Shipping Amount`+`Invoice Transaction Charges Amount`+`Invoice Transaction Net Adjust`+`Invoice Transaction Insurance Amount`) as inv_net,
	sum(`Invoice Transaction Item Tax Amount`+`Invoice Transaction Shipping Tax Amount`+`Invoice Transaction Charges Tax Amount`+`Invoice Transaction Insurance Tax Amount`+`Invoice Transaction Tax Adjust`) as inv_tax,

	sum(`Order Out of Stock Lost Amount`) as out_of_stock_net,
	sum(`Order Out of Stock Lost Amount`*`Transaction Tax Rate`) as out_of_stock_tax,
	sum(if(`Order Quantity`>0, `No Shipped Due No Authorized`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_authorized_net,
	sum(if(`Order Quantity`>0, `No Shipped Due No Authorized`*`Transaction Tax Rate`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_authorized_tax,
	sum(if(`Order Quantity`>0, `No Shipped Due Not Found`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_found_net,
	sum(if(`Order Quantity`>0, `No Shipped Due Not Found`*`Transaction Tax Rate`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_found_tax,
	sum(if(`Order Quantity`>0, `No Shipped Due Other`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_due_other_net,
	sum(if(`Order Quantity`>0, `No Shipped Due Other`*`Transaction Tax Rate`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_due_other_tax



	from `Order Transaction Fact`    where  `Order Key`=" . $this->id;

		$result = mysql_query( $sql );
		//print "\n$sql\n";
		if ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {

			//print_r($row);
			$costs=$row['costs'];
			$number_otfs=$row['number_otfs'];

			$this->data['Order Invoiced Balance Net Amount']=$row['net']+$row['ref_net'];
			$this->data['Order Invoiced Outstanding Balance Net Amount']=$row['ob_net']+$row['ref_ob_net'];



			$this->data['Order Invoiced Items Amount']=$row['inv_items'];
			$this->data['Order Invoiced Shipping Amount']=$row['inv_shp'];
			$this->data['Order Invoiced Charges Amount']=$row['inv_charges'];
			$this->data['Order Invoiced Insurance Amount']=$row['inv_insurance'];

			$this->data['Order Invoiced Items Tax Amount']=$row['inv_items_tax'];
			$this->data['Order Invoiced Shipping Tax Amount']=$row['inv_shp_tax'];
			$this->data['Order Invoiced Charges Tax Amount']=$row['inv_charges_tax'];
			$this->data['Order Invoiced Insurance Tax Amount']=$row['inv_insurance_tax'];




			$this->data['Order Invoiced Net Amount']=$row['inv_net'];
			$this->data['Order Invoiced Tax Amount']=$row['inv_tax'];
			$this->data['Order Invoiced Refund Net Amount']=round($row['ref_net'], 2);
			$this->data['Order Invoiced Refund Tax Amount']=round($row['ref_tax'], 2);


			$this->data['Order Out of Stock Net Amount']=$row['out_of_stock_net'];
			$this->data['Order Out of Stock Tax Amount']=$row['out_of_stock_tax'];

			$this->data['Order No Authorized Net Amount']=$row['not_authorized_net'];
			$this->data['Order No Authorized Tax Amount']=$row['not_authorized_tax'];

			$this->data['Order Not Found Net Amount']=$row['not_found_net'];
			$this->data['Order Not Found Tax Amount']=$row['not_found_tax'];

			$this->data['Order Not Due Other Net Amount']=$row['not_due_other_net'];
			$this->data['Order Not Due Other Tax Amount']=$row['not_due_other_tax'];


		}

		//  sum(`Order Transaction Gross Amount`) as gross,sum(`Order Transaction Total Discount Amount`) as discount,


		$net=0;
		$tax=0;
		$gross=0;
		$out_of_stock_amount=0;
		$discounts=0;
		$refund_net=0;
		$refund_tax=0;


		$sql = sprintf("select `Order Transaction Total Discount Amount`,`Order Transaction Gross Amount`,
	ifnull((select max(ifnull(`Fraction Discount`,0)) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`),0 ) as discount_fraction,
		  `Invoice Transaction Net Refund Items`,`Invoice Transaction Tax Refund Items`,`Product History Price`,`No Shipped Due Other`,`No Shipped Due Not Found`,`No Shipped Due No Authorized`,`No Shipped Due Out of Stock`,OTF.`Order Quantity`,`Order Transaction Amount`,`Transaction Tax Rate`,
		`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`


			from `Order Transaction Fact` OTF left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`)
			where OTF.`Order Key`=%d", $this->id);

		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			//print_r($row);

			$ordered=$row['Order Quantity'];
			$no_dispatched=$row['No Shipped Due Out of Stock']+$row['No Shipped Due No Authorized']+$row['No Shipped Due Not Found']+$row['No Shipped Due Other'];

			$chargeable_qty=$ordered-$no_dispatched;






			$discount_fraction=0;

			if ($row['Order Transaction Gross Amount']>0) {
				$discount_fraction=$row['Order Transaction Total Discount Amount']/$row['Order Transaction Gross Amount'];
			}


			// $discount_fraction=$row['discount_fraction'];

			$gross_chargeable_amount=$chargeable_qty*$row['Product History Price'];

			$discount=round($gross_chargeable_amount*$discount_fraction, 2);

			$chargeable_amount=$gross_chargeable_amount-$discount;
			$gross+=$row['Order Quantity']*$row['Product History Price'];
			$out_of_stock_amount+=($row['No Shipped Due Out of Stock'])*$row['Product History Price'];
			$discounts+=$discount;
			$chargeable_amount=round($chargeable_amount, 2);



			$_refund_tax=round($row['Invoice Transaction Tax Refund Items'], 2);
			$_refund_net=round($row['Invoice Transaction Net Refund Items'], 2);



			$tax+=round($_refund_tax+($chargeable_amount*$row['Transaction Tax Rate']), 2);
			$net+=round($chargeable_amount+$_refund_net, 2);


		}


		//print "$net ";



		$this->data['Order Balance Net Amount']=$net-$this->data['Order Deal Amount Off'];





		$this->data['Order Outstanding Balance Net Amount']=$this->data['Order Balance Net Amount']-$this->data['Order Invoiced Balance Net Amount']+$this->data['Order Invoiced Outstanding Balance Net Amount'];






		$sql = sprintf("select * from `Order No Product Transaction Fact` where `Order Key`=%d" , $this->data ['Order Key']);
		//print "$sql\n";
		$result = mysql_query( $sql );
		while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
			//print_r($row);
			$this->data['Order Invoiced Balance Net Amount']+=$row['Transaction Invoice Net Amount'];
			$this->data['Order Invoiced Outstanding Balance Net Amount']+=$row['Transaction Outstanding Net Amount Balance'];

			$this->data['Order Invoiced Refund Net Amount']+=round($row['Transaction Refund Net Amount'], 2);
			$this->data['Order Invoiced Refund Tax Amount']+=round($row['Transaction Refund Tax Amount'], 2);

			// print "xx ".$row['Transaction Net Amount']." \n";
			$this->data['Order Balance Net Amount']+=round($row['Transaction Net Amount']+$row['Transaction Refund Net Amount'], 2);

			$this->data['Order Outstanding Balance Net Amount']+=$row['Transaction Net Amount']-$row['Transaction Invoice Net Amount']+$row['Transaction Outstanding Net Amount Balance'];


			if ( $row['Transaction Type']=='Adjust') {

				$this->data['Order Invoiced Net Amount']+=$row['Transaction Invoice Net Amount'];

			}


			if ( $row['Transaction Type']=='Credit') {
				$this->data['Order Tax Credited Amount']+=$row['Transaction Tax Amount'];
				$this->data['Order Net Credited Amount']+=$row['Transaction Net Amount'];
				$this->data['Order Invoiced Refund Net Amount']+=$row['Transaction Invoice Net Amount'];
				$this->data['Order Invoiced Refund Tax Amount']+=$row['Transaction Invoice Tax Amount'];

			}elseif ($row['Transaction Type']=='Refund') {
				$this->data['Order Tax Refund Amount']+=$row['Transaction Tax Amount'];
				$this->data['Order Net Refund Amount']+=$row['Transaction Net Amount'];
				$this->data['Order Invoiced Refund Net Amount']+=$row['Transaction Refund Net Amount'];
				$this->data['Order Invoiced Refund Tax Amount']+=$row['Transaction Refund Tax Amount'];
			}  elseif ($row['Transaction Type']=='Adjust') {
				$this->data['Order Invoiced Total Net Adjust Amount']+=$row['Transaction Invoice Net Amount'];
				$this->data['Order Invoiced Total Tax Adjust Amount']+=$row['Transaction Invoice Tax Amount'];
			}

		}






		//print_r($this->data);
		$oustanding_invoiced_refund_net=0;
		$oustanding_invoiced_refund_tax=0;

		$sql = sprintf("select * from `Order No Product Transaction Fact` where `Transaction Type` in ('Refund','Credit') and `Affected Order Key`=%d and `Order Key`!=%d" , $this->id, $this->id);

		$result = mysql_query( $sql );
		while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {

			$this->data['Order Invoiced Refund Net Amount']+=$row['Transaction Invoice Net Amount'];
			$this->data['Order Invoiced Refund Tax Amount']+=$row['Transaction Invoice Tax Amount'];
			$oustanding_invoiced_refund_net+=$row['Transaction Outstanding Net Amount Balance'];
			$oustanding_invoiced_refund_tax+=$row['Transaction Outstanding Tax Amount Balance'];
			if ($row['Transaction Description']!='')
				$this->data['Order Invoiced Refund Notes'].='<br/>'.$row['Transaction Description'];
		}


		if ($number_otfs==0) {
			$net=0;$tax=0;
			$sql = sprintf("select sum(`Transaction Net Amount`) as amount,sum(`Transaction Tax Amount`) as tax from `Order No Product Transaction Fact` where `Transaction Type`='Shipping'  and `Order Key`=%d" , $this->data ['Order Key']);

			$result = mysql_query( $sql );
			while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {

				$this->data['Order Invoiced Shipping Amount']=$row['amount'];
				$net+=$row['amount'];
				$tax+=$row['tax'];

			}
			$sql = sprintf("select sum(`Transaction Net Amount`) as amount,sum(`Transaction Tax Amount`) as tax  from `Order No Product Transaction Fact` where `Transaction Type`='Charges'  and `Order Key`=%d" , $this->data ['Order Key']);
			//print "$sql\n";
			$result = mysql_query( $sql );
			while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
				//print_r($row);
				$this->data['Order Invoiced Charges Amount']=$row['amount'];


				$this->data['Order Charges Net Amount']=$row['amount'];
				$this->data['Order Charges Tax Amount']=$row['tax'];




				$net+=$row['amount'];
				$tax+=$row['tax'];

			}

			$sql = sprintf("select sum(`Transaction Net Amount`) as amount,sum(`Transaction Tax Amount`) as tax  from `Order No Product Transaction Fact` where `Transaction Type`='Insurance'  and `Order Key`=%d" , $this->data ['Order Key']);
			//print "$sql\n";
			$result = mysql_query( $sql );
			while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
				// print_r($row);
				$this->data['Order Invoiced Charges Amount']=$row['amount'];


				$this->data['Order Insurance Net Amount']=$row['amount'];
				$this->data['Order Insurance Tax Amount']=$row['tax'];




				$net+=$row['amount'];
				$tax+=$row['tax'];

			}


			$this->data['Order Invoiced Net Amount']=$net;
			$this->data['Order Invoiced Tax Amount']=$tax;


		}
		if ($this->data['Order Out of Stock Net Amount']>0) {
			$this->data['Order with Out of Stock']='Yes';
		}else {
			$this->data['Order with Out of Stock']='No';
		}


		$this->data['Order Invoiced Refund Notes']=preg_replace('/<br\/>/', '', $this->data['Order Invoiced Refund Notes']);



		$this->data['Order Invoiced Net Amount']-=$this->data['Order Deal Amount Off'];



		$this->data['Order Balance Tax Amount']=round($this->data['Order Balance Net Amount']*$this->data['Order Tax Rate'], 2);


		//print ($this->data['Order Balance Net Amount']*$this->data['Order Tax Rate']);

		$this->data['Order Balance Total Amount']=$this->data['Order Balance Net Amount']+$this->data['Order Balance Tax Amount'];

		$this->data['Order Outstanding Balance Tax Amount']=round($this->data['Order Outstanding Balance Net Amount']*$this->data['Order Tax Rate'], 2);
		$this->data['Order Outstanding Balance Total Amount']=$this->data['Order Balance Total Amount']-$this->data['Order Invoiced Balance Total Amount']+$this->data['Order Invoiced Outstanding Balance Total Amount'];

		$this->data['Order Invoiced Tax Amount']=round($this->data['Order Invoiced Net Amount']*$this->data['Order Tax Rate'], 2);
		$this->data['Order Invoiced Total Amount']=$this->data['Order Invoiced Net Amount']+$this->data['Order Invoiced Tax Amount'];

		$this->data['Order Invoiced Balance Tax Amount']=round($this->data['Order Invoiced Balance Net Amount']*$this->data['Order Tax Rate'], 2);
		$this->data['Order Invoiced Balance Total Amount']=$this->data['Order Invoiced Balance Net Amount']+$this->data['Order Invoiced Balance Tax Amount'];

		$this->data['Order Invoiced Outstanding Balance Tax Amount']=round($this->data['Order Invoiced Outstanding Balance Net Amount']*$this->data['Order Tax Rate'], 2);
		$this->data['Order Invoiced Outstanding Balance Total Amount']=$this->data['Order Invoiced Outstanding Balance Net Amount']+$this->data['Order Invoiced Outstanding Balance Tax Amount'];


		$this->data['Order To Pay Amount']=round($this->data['Order Balance Total Amount']-$this->data['Order Payments Amount'], 2);

		$this->data['Order Invoiced Profit Amount']= $this->data['Order Invoiced Balance Net Amount']-$this->data['Order Invoiced Outstanding Balance Net Amount']- $row['costs'];
		$this->data['Order Profit Amount']= $this->data['Order Balance Net Amount']-$this->data['Order Outstanding Balance Net Amount']- $row['costs'];



		$sql=sprintf("update `Order Dimension` set

			`Order To Pay Amount`=%.2f,
			`Order Payments Amount`=%.2f,

			`Order Invoiced Balance Net Amount`=%.2f,
			`Order Invoiced Balance Tax Amount`=%.2f,
			`Order Invoiced Balance Total Amount`=%.2f,
			`Order Invoiced Outstanding Balance Net Amount`=%.2f,
			`Order Invoiced Outstanding Balance Tax Amount`=%.2f,
			`Order Invoiced Outstanding Balance Total Amount`=%.2f,
			`Order Tax Refund Amount`=%.2f,
			`Order Net Refund Amount`=%.2f,
			`Order Tax Credited Amount`=%.2f,`Order Net Credited Amount`=%.2f,
			`Order Invoiced Profit Amount`=%.2f,
			`Order Invoiced Items Amount`=%.2f,
			`Order Invoiced Shipping Amount`=%.2f,
			`Order Invoiced Charges Amount`=%.2f,
			`Order Invoiced Insurance Amount`=%.2f,
			`Order Invoiced Net Amount`=%.2f,
			`Order Invoiced Tax Amount`=%.2f,
			`Order Out of Stock Net Amount`=%.2f,
			`Order Out of Stock Tax Amount`=%.2f,
			`Order No Authorized Net Amount`=%.2f,
			`Order No Authorized Tax Amount`=%.2f,
			`Order Not Found Net Amount`=%.2f,
			`Order Not Found Tax Amount`=%.2f,
			`Order Not Due Other Net Amount`=%.2f,
			`Order Not Due Other Tax Amount`=%.2f,

			`Order Invoiced Refund Net Amount`=%.2f,
			`Order Invoiced Refund Tax Amount`=%.2f,
			`Order Invoiced Refund Notes`=%s,
			`Order Invoiced Total Net Adjust Amount`=%.2f,
			`Order Invoiced Total Tax Adjust Amount`=%.2f,
			`Order Balance Net Amount`=%.2f,
			`Order Balance Tax Amount`=%.2f,
			`Order Balance Total Amount`=%.2f,
			`Order Outstanding Balance Net Amount`=%.2f,
			`Order Outstanding Balance Tax Amount`=%.2f,
			`Order Outstanding Balance Total Amount`=%.2f,
			`Order Profit Amount`=%.2f,
			`Order with Out of Stock`=%s,

			`Order Invoiced Items Tax Amount`=%.2f,
			`Order Invoiced Shipping Tax Amount`=%.2f,
			`Order Invoiced Charges Tax Amount`=%.2f,
			`Order Invoiced Insurance Tax Amount`=%.2f




			where `Order Key`=%d",
			$this->data['Order To Pay Amount'],

			$this->data['Order Payments Amount'],
			$this->data['Order Invoiced Balance Net Amount'],
			$this->data['Order Invoiced Balance Tax Amount'],
			$this->data['Order Invoiced Balance Total Amount'],

			$this->data['Order Invoiced Outstanding Balance Net Amount'],
			$this->data['Order Invoiced Outstanding Balance Tax Amount'],
			$this->data['Order Invoiced Outstanding Balance Total Amount'],

			$this->data['Order Tax Refund Amount'],
			$this->data['Order Net Refund Amount'],
			$this->data['Order Tax Credited Amount'],
			$this->data['Order Net Credited Amount'],

			$this->data['Order Invoiced Profit Amount'],

			$this->data['Order Invoiced Items Amount'],
			$this->data['Order Invoiced Shipping Amount'],
			$this->data['Order Invoiced Charges Amount'],
			$this->data['Order Invoiced Insurance Amount'],


			$this->data['Order Invoiced Net Amount'],
			$this->data['Order Invoiced Tax Amount'],

			$this->data['Order Out of Stock Net Amount'],
			$this->data['Order Out of Stock Tax Amount'],
			$this->data['Order No Authorized Net Amount'],
			$this->data['Order No Authorized Tax Amount'],
			$this->data['Order Not Found Net Amount'],
			$this->data['Order Not Found Tax Amount'],
			$this->data['Order Not Due Other Net Amount'],
			$this->data['Order Not Due Other Tax Amount'],

			$this->data['Order Invoiced Refund Net Amount'],
			$this->data['Order Invoiced Refund Tax Amount'],
			prepare_mysql($this->data['Order Invoiced Refund Notes']),
			$this->data['Order Invoiced Total Net Adjust Amount'],
			$this->data['Order Invoiced Total Tax Adjust Amount'],
			$this->data['Order Balance Net Amount'],
			$this->data['Order Balance Tax Amount'],
			$this->data['Order Balance Total Amount'],
			$this->data['Order Outstanding Balance Net Amount'],
			$this->data['Order Outstanding Balance Tax Amount'],
			$this->data['Order Outstanding Balance Total Amount'],
			$this->data['Order Profit Amount'],
			prepare_mysql($this->data['Order with Out of Stock']),





			$this->data['Order Invoiced Items Tax Amount'],
			$this->data['Order Invoiced Shipping Tax Amount'],
			$this->data['Order Invoiced Charges Tax Amount'],
			$this->data['Order Invoiced Insurance Tax Amount'],



			$this->id
		);

		mysql_query($sql);





	}


	function update_no_normal_totals_per_item_method($args='') {

		$this->data['Order Balance Net Amount']=0;
		$this->data['Order Balance Tax Amount']=0;
		$this->data['Order Balance Total Amount']=0;
		$this->data['Order Outstanding Balance Net Amount']=0;
		$this->data['Order Outstanding Balance Tax Amount']=0;
		$this->data['Order Outstanding Balance Total Amount']=0;

		$this->data['Order Invoiced Balance Net Amount']=0;
		$this->data['Order Invoiced Balance Tax Amount']=0;
		$this->data['Order Invoiced Balance Total Amount']=0;
		$this->data['Order Invoiced Outstanding Balance Net Amount']=0;
		$this->data['Order Invoiced Outstanding Balance Tax Amount']=0;
		$this->data['Order Invoiced Outstanding Balance Total Amount']=0;
		$this->data['Order Invoiced Refund Net Amount']=0;
		$this->data['Order Invoiced Refund Tax Amount']=0;
		$this->data['Order Invoiced Refund Notes']='';

		$this->data['Order Tax Credited Amount']=0;
		$this->data['Order Net Credited Amount']=0;
		$this->data['Order Tax Refund Amount']=0;
		$this->data['Order Net Refund Amount']=0;

		$sql = "select count(*) as number_otfs,
	sum(IFNULL(`Cost Supplier`,0)+IFNULL(`Cost Storing`,0)+IFNULL(`Cost Handing`,0)+IFNULL(`Cost Shipping`,0))as costs,
	sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as net,
	sum(`Invoice Transaction Item Tax Amount`) as tax,
	sum(`Order Transaction Amount`) as original_net,
	sum(`Order Transaction Amount`*`Transaction Tax Rate`) as original_tax,
	sum(`Invoice Transaction Net Refund Items`) as ref_net,
	sum(`Invoice Transaction Tax Refund Items`) as ref_tax,
	sum(`Invoice Transaction Outstanding Net Balance`) as ob_net ,
	sum(`Invoice Transaction Outstanding Tax Balance`) as ob_tax ,
	sum(`Invoice Transaction Outstanding Refund Net Balance`) as ref_ob_net ,
	sum(`Invoice Transaction Outstanding Refund Tax Balance`) as ref_ob_tax ,

	sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as inv_items,
	sum(`Invoice Transaction Item Tax Amount`) as inv_items_tax,

	sum(`Invoice Transaction Shipping Amount`) as inv_shp,
	sum(`Invoice Transaction Shipping Tax Amount`) as inv_shp_tax,

	sum(`Invoice Transaction Charges Amount`) as inv_charges,
	sum(`Invoice Transaction Charges Tax Amount`) as inv_charges_tax,


	sum(`Invoice Transaction Insurance Amount`) as inv_insurance,
	sum(`Invoice Transaction Insurance Tax Amount`) as inv_insurance_tax,

	sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`+`Invoice Transaction Shipping Amount`+`Invoice Transaction Charges Amount`+`Invoice Transaction Net Adjust`+`Invoice Transaction Insurance Amount`) as inv_net,
	sum(`Invoice Transaction Item Tax Amount`+`Invoice Transaction Shipping Tax Amount`+`Invoice Transaction Charges Tax Amount`+`Invoice Transaction Insurance Tax Amount`+`Invoice Transaction Tax Adjust`) as inv_tax,

	sum(`Order Out of Stock Lost Amount`) as out_of_stock_net,
	sum(`Order Out of Stock Lost Amount`*`Transaction Tax Rate`) as out_of_stock_tax,
	sum(if(`Order Quantity`>0, `No Shipped Due No Authorized`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_authorized_net,
	sum(if(`Order Quantity`>0, `No Shipped Due No Authorized`*`Transaction Tax Rate`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_authorized_tax,
	sum(if(`Order Quantity`>0, `No Shipped Due Not Found`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_found_net,
	sum(if(`Order Quantity`>0, `No Shipped Due Not Found`*`Transaction Tax Rate`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_found_tax,
	sum(if(`Order Quantity`>0, `No Shipped Due Other`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_due_other_net,
	sum(if(`Order Quantity`>0, `No Shipped Due Other`*`Transaction Tax Rate`*(`Order Transaction Amount`)/`Order Quantity`,0)) as not_due_other_tax



	from `Order Transaction Fact`    where  `Order Key`=" . $this->id;

		$result = mysql_query( $sql );
		//print "\n$sql\n";
		if ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {

			//print_r($row);

			$number_otfs=$row['number_otfs'];

			$this->data['Order Invoiced Balance Net Amount']=$row['net']+$row['ref_net'];
			$this->data['Order Invoiced Balance Tax Amount']=$row['tax']+$row['ref_tax'];
			$this->data['Order Invoiced Balance Total Amount']=$this->data['Order Invoiced Balance Net Amount']+$this->data['Order Invoiced Balance Tax Amount'];
			$this->data['Order Invoiced Outstanding Balance Net Amount']=$row['ob_net']+$row['ref_ob_net'];
			$this->data['Order Invoiced Outstanding Balance Tax Amount']=$row['ob_tax']+$row['ref_ob_tax'];
			$this->data['Order Invoiced Outstanding Balance Total Amount']=$this->data['Order Invoiced Outstanding Balance Net Amount']+$this->data['Order Invoiced Outstanding Balance Tax Amount'];




			$this->data['Order Invoiced Items Amount']=$row['inv_items'];
			$this->data['Order Invoiced Shipping Amount']=$row['inv_shp'];
			$this->data['Order Invoiced Charges Amount']=$row['inv_charges'];
			$this->data['Order Invoiced Insurance Amount']=$row['inv_insurance'];

			$this->data['Order Invoiced Items Tax Amount']=$row['inv_items_tax'];
			$this->data['Order Invoiced Shipping Tax Amount']=$row['inv_shp_tax'];
			$this->data['Order Invoiced Charges Tax Amount']=$row['inv_charges_tax'];
			$this->data['Order Invoiced Insurance Tax Amount']=$row['inv_insurance_tax'];




			$this->data['Order Invoiced Net Amount']=$row['inv_net'];
			$this->data['Order Invoiced Tax Amount']=$row['inv_tax'];
			$this->data['Order Invoiced Refund Net Amount']=round($row['ref_net'], 2);
			$this->data['Order Invoiced Refund Tax Amount']=round($row['ref_tax'], 2);


			$this->data['Order Out of Stock Net Amount']=$row['out_of_stock_net'];
			$this->data['Order Out of Stock Tax Amount']=$row['out_of_stock_tax'];

			$this->data['Order No Authorized Net Amount']=$row['not_authorized_net'];
			$this->data['Order No Authorized Tax Amount']=$row['not_authorized_tax'];

			$this->data['Order Not Found Net Amount']=$row['not_found_net'];
			$this->data['Order Not Found Tax Amount']=$row['not_found_tax'];

			$this->data['Order Not Due Other Net Amount']=$row['not_due_other_net'];
			$this->data['Order Not Due Other Tax Amount']=$row['not_due_other_tax'];

			$this->data['Order Invoiced Profit Amount']= $this->data['Order Invoiced Balance Net Amount']-$this->data['Order Invoiced Outstanding Balance Net Amount']- $row['costs'];
			$this->data['Order Profit Amount']= $this->data['Order Balance Net Amount']-$this->data['Order Outstanding Balance Net Amount']- $row['costs'];

		}

		//  sum(`Order Transaction Gross Amount`) as gross,sum(`Order Transaction Total Discount Amount`) as discount,


		$net=0;
		$tax=0;
		$gross=0;
		$out_of_stock_amount=0;
		$discounts=0;
		$refund_net=0;
		$refund_tax=0;
		$sql = sprintf("select `Invoice Transaction Net Refund Items`,`Invoice Transaction Tax Refund Items`,IFNULL(`Fraction Discount`,0) as `Fraction Discount` ,`Product History Price`,`No Shipped Due Other`,`No Shipped Due Not Found`,`No Shipped Due No Authorized`,`No Shipped Due Out of Stock`,OTF.`Order Quantity`,`Order Transaction Amount`,`Transaction Tax Rate`
			from `Order Transaction Fact` OTF left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`)  left join `Order Transaction Deal Bridge` OTDB on (OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`)
			where OTF.`Order Key`=%d", $this->id);


		$sql = sprintf("select

		(`Order Transaction Total Discount Amount`/`Order Transaction Gross Amount`) as discount_fraction_bis,
		  `Invoice Transaction Net Refund Items`,`Invoice Transaction Tax Refund Items`,`Product History Price`,`No Shipped Due Other`,`No Shipped Due Not Found`,`No Shipped Due No Authorized`,`No Shipped Due Out of Stock`,OTF.`Order Quantity`,`Order Transaction Amount`,`Transaction Tax Rate`,
		`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`


			from `Order Transaction Fact` OTF left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`)
			where OTF.`Order Key`=%d", $this->id);

		$sql = sprintf("select `Order Transaction Total Discount Amount`,`Order Transaction Gross Amount`,
	ifnull((select max(ifnull(`Fraction Discount`,0)) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`),0 ) as discount_fraction,
		  `Invoice Transaction Net Refund Items`,`Invoice Transaction Tax Refund Items`,`Product History Price`,`No Shipped Due Other`,`No Shipped Due Not Found`,`No Shipped Due No Authorized`,`No Shipped Due Out of Stock`,OTF.`Order Quantity`,`Order Transaction Amount`,`Transaction Tax Rate`,
		`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`


			from `Order Transaction Fact` OTF left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`)
			where OTF.`Order Key`=%d", $this->id);

		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			//print_r($row);

			$ordered=$row['Order Quantity'];
			$no_dispatched=$row['No Shipped Due Out of Stock']+$row['No Shipped Due No Authorized']+$row['No Shipped Due Not Found']+$row['No Shipped Due Other'];

			$chargeable_qty=$ordered-$no_dispatched;






			$discount_fraction=0;

			if ($row['Order Transaction Gross Amount']>0) {
				$discount_fraction=$row['Order Transaction Total Discount Amount']/$row['Order Transaction Gross Amount'];
			}


			// $discount_fraction=$row['discount_fraction'];

			$gross_chargeable_amount=$chargeable_qty*$row['Product History Price'];

			$discount=round($gross_chargeable_amount*$discount_fraction, 2);

			$chargeable_amount=$gross_chargeable_amount-$discount;
			$gross+=$row['Order Quantity']*$row['Product History Price'];
			$out_of_stock_amount+=($row['No Shipped Due Out of Stock'])*$row['Product History Price'];
			$discounts+=$discount;
			$chargeable_amount=round($chargeable_amount, 2);



			$_refund_tax=round($row['Invoice Transaction Tax Refund Items'], 2);
			$_refund_net=round($row['Invoice Transaction Net Refund Items'], 2);



			$tax+=round($_refund_tax+($chargeable_amount*$row['Transaction Tax Rate']), 2);
			$net+=round($chargeable_amount+$_refund_net, 2);


		}


		//print "$net ";



		$this->data['Order Balance Net Amount']=$net-$this->data['Order Deal Amount Off'];
		$this->data['Order Balance Tax Amount']=$tax-round($this->data['Order Deal Amount Off']*$this->data['Order Tax Rate'], 2);


		// print "Tax : ".$this->data['Order Balance Tax Amount'].' '.$tax." ".round($this->data['Order Deal Amount Off']*$this->data['Order Tax Rate'],2)."  \n";


		$this->data['Order Balance Total Amount']=$this->data['Order Balance Net Amount']+$this->data['Order Balance Tax Amount'];

		$this->data['Order Outstanding Balance Net Amount']=$this->data['Order Balance Net Amount']-$this->data['Order Invoiced Balance Net Amount']+$this->data['Order Invoiced Outstanding Balance Net Amount'];
		$this->data['Order Outstanding Balance Tax Amount']=$this->data['Order Balance Tax Amount']-$this->data['Order Invoiced Balance Tax Amount']+$this->data['Order Invoiced Outstanding Balance Tax Amount'];
		$this->data['Order Outstanding Balance Total Amount']=$this->data['Order Balance Total Amount']-$this->data['Order Invoiced Balance Total Amount']+$this->data['Order Invoiced Outstanding Balance Total Amount'];


		//print $this->data['Order Balance Net Amount'].' '.$this->data['Order Balance Tax Amount']." ".$this->data['Order Balance Total Amount']." \n";




		$sql = sprintf("select * from `Order No Product Transaction Fact` where `Order Key`=%d" , $this->data ['Order Key']);
		//print "$sql\n";
		$result = mysql_query( $sql );
		while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
			//print_r($row);
			$this->data['Order Invoiced Balance Net Amount']+=$row['Transaction Invoice Net Amount'];
			$this->data['Order Invoiced Balance Tax Amount']+=$row['Transaction Invoice Tax Amount'];
			$this->data['Order Invoiced Balance Total Amount']+=$row['Transaction Invoice Net Amount']+$row['Transaction Invoice Tax Amount'];
			$this->data['Order Invoiced Outstanding Balance Net Amount']+=$row['Transaction Outstanding Net Amount Balance'];
			$this->data['Order Invoiced Outstanding Balance Tax Amount']+=$row['Transaction Outstanding Tax Amount Balance'];
			$this->data['Order Invoiced Outstanding Balance Total Amount']+=$row['Transaction Outstanding Net Amount Balance']+$row['Transaction Outstanding Tax Amount Balance'];
			$this->data['Order Invoiced Refund Net Amount']+=round($row['Transaction Refund Net Amount'], 2);
			$this->data['Order Invoiced Refund Tax Amount']+=round($row['Transaction Refund Tax Amount'], 2);

			// print "xx ".$row['Transaction Net Amount']." \n";
			$this->data['Order Balance Net Amount']+=round($row['Transaction Net Amount']+$row['Transaction Refund Net Amount'], 2);
			$this->data['Order Balance Tax Amount']+=round($row['Transaction Tax Amount']+$row['Transaction Refund Tax Amount'], 2);

			//print "Tax : ".$this->data['Order Balance Tax Amount'].' '.round($row['Transaction Tax Amount']+$row['Transaction Refund Tax Amount'],2)." tax ship\n";
			$this->data['Order Balance Total Amount']+=$row['Transaction Net Amount']+$row['Transaction Tax Amount']+$row['Transaction Refund Net Amount']+$row['Transaction Refund Tax Amount'];
			$this->data['Order Outstanding Balance Net Amount']+=$row['Transaction Net Amount']-$row['Transaction Invoice Net Amount']+$row['Transaction Outstanding Net Amount Balance'];
			$this->data['Order Outstanding Balance Tax Amount']+=$row['Transaction Tax Amount']-$row['Transaction Invoice Tax Amount']+$row['Transaction Outstanding Tax Amount Balance'];
			$this->data['Order Outstanding Balance Total Amount']+=$row['Transaction Net Amount']-$row['Transaction Invoice Net Amount']+$row['Transaction Outstanding Net Amount Balance']+$row['Transaction Tax Amount']-$row['Transaction Invoice Tax Amount']+$row['Transaction Outstanding Tax Amount Balance'];


			if ( $row['Transaction Type']=='Adjust') {

				$this->data['Order Invoiced Net Amount']+=$row['Transaction Invoice Net Amount'];
				$this->data['Order Invoiced Tax Amount']+=$row['Transaction Invoice Tax Amount'];

			}


			if ( $row['Transaction Type']=='Credit') {
				$this->data['Order Tax Credited Amount']+=$row['Transaction Tax Amount'];
				$this->data['Order Net Credited Amount']+=$row['Transaction Net Amount'];
				$this->data['Order Invoiced Refund Net Amount']+=$row['Transaction Invoice Net Amount'];
				$this->data['Order Invoiced Refund Tax Amount']+=$row['Transaction Invoice Tax Amount'];

			}elseif ($row['Transaction Type']=='Refund') {
				$this->data['Order Tax Refund Amount']+=$row['Transaction Tax Amount'];
				$this->data['Order Net Refund Amount']+=$row['Transaction Net Amount'];
				$this->data['Order Invoiced Refund Net Amount']+=$row['Transaction Refund Net Amount'];
				$this->data['Order Invoiced Refund Tax Amount']+=$row['Transaction Refund Tax Amount'];
			}  elseif ($row['Transaction Type']=='Adjust') {
				$this->data['Order Invoiced Total Net Adjust Amount']+=$row['Transaction Invoice Net Amount'];
				$this->data['Order Invoiced Total Tax Adjust Amount']+=$row['Transaction Invoice Tax Amount'];
			}

		}






		//print_r($this->data);
		$oustanding_invoiced_refund_net=0;
		$oustanding_invoiced_refund_tax=0;

		$sql = sprintf("select * from `Order No Product Transaction Fact` where `Transaction Type` in ('Refund','Credit') and `Affected Order Key`=%d and `Order Key`!=%d" , $this->id, $this->id);

		$result = mysql_query( $sql );
		while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {

			$this->data['Order Invoiced Refund Net Amount']+=$row['Transaction Invoice Net Amount'];
			$this->data['Order Invoiced Refund Tax Amount']+=$row['Transaction Invoice Tax Amount'];
			$oustanding_invoiced_refund_net+=$row['Transaction Outstanding Net Amount Balance'];
			$oustanding_invoiced_refund_tax+=$row['Transaction Outstanding Tax Amount Balance'];
			if ($row['Transaction Description']!='')
				$this->data['Order Invoiced Refund Notes'].='<br/>'.$row['Transaction Description'];
		}


		if ($number_otfs==0) {
			$net=0;$tax=0;
			$sql = sprintf("select sum(`Transaction Net Amount`) as amount,sum(`Transaction Tax Amount`) as tax from `Order No Product Transaction Fact` where `Transaction Type`='Shipping'  and `Order Key`=%d" , $this->data ['Order Key']);

			$result = mysql_query( $sql );
			while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {

				$this->data['Order Invoiced Shipping Amount']=$row['amount'];
				$net+=$row['amount'];
				$tax+=$row['tax'];

			}
			$sql = sprintf("select sum(`Transaction Net Amount`) as amount,sum(`Transaction Tax Amount`) as tax  from `Order No Product Transaction Fact` where `Transaction Type`='Charges'  and `Order Key`=%d" , $this->data ['Order Key']);
			//print "$sql\n";
			$result = mysql_query( $sql );
			while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
				//print_r($row);
				$this->data['Order Invoiced Charges Amount']=$row['amount'];


				$this->data['Order Charges Net Amount']=$row['amount'];
				$this->data['Order Charges Tax Amount']=$row['tax'];




				$net+=$row['amount'];
				$tax+=$row['tax'];

			}

			$sql = sprintf("select sum(`Transaction Net Amount`) as amount,sum(`Transaction Tax Amount`) as tax  from `Order No Product Transaction Fact` where `Transaction Type`='Insurance'  and `Order Key`=%d" , $this->data ['Order Key']);
			//print "$sql\n";
			$result = mysql_query( $sql );
			while ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
				// print_r($row);
				$this->data['Order Invoiced Charges Amount']=$row['amount'];


				$this->data['Order Insurance Net Amount']=$row['amount'];
				$this->data['Order Insurance Tax Amount']=$row['tax'];




				$net+=$row['amount'];
				$tax+=$row['tax'];

			}


			$this->data['Order Invoiced Net Amount']=$net;
			$this->data['Order Invoiced Tax Amount']=$tax;


		}
		if ($this->data['Order Out of Stock Net Amount']>0) {
			$this->data['Order with Out of Stock']='Yes';
		}else {
			$this->data['Order with Out of Stock']='No';
		}


		$this->data['Order Invoiced Net Amount']=$this->data['Order Invoiced Net Amount']-$this->data['Order Deal Amount Off'];
		$this->data['Order Invoiced Tax Amount']=$this->data['Order Invoiced Tax Amount']-round($this->data['Order Deal Amount Off']*$this->data['Order Tax Rate'], 2);



		$this->data['Order Invoiced Refund Notes']=preg_replace('/<br\/>/', '', $this->data['Order Invoiced Refund Notes']);


		$this->data['Order To Pay Amount']=round($this->data['Order Balance Total Amount']-$this->data['Order Payments Amount'], 2);




		$sql=sprintf("update `Order Dimension` set

			`Order To Pay Amount`=%.2f,
			`Order Payments Amount`=%.2f,

			`Order Invoiced Balance Net Amount`=%.2f,`Order Invoiced Balance Tax Amount`=%.2f,`Order Invoiced Balance Total Amount`=%.2f,
			`Order Invoiced Outstanding Balance Net Amount`=%.2f,`Order Invoiced Outstanding Balance Tax Amount`=%.2f,`Order Invoiced Outstanding Balance Total Amount`=%.2f,
			`Order Tax Refund Amount`=%.2f,`Order Net Refund Amount`=%.2f,
			`Order Tax Credited Amount`=%.2f,`Order Net Credited Amount`=%.2f,
			`Order Invoiced Profit Amount`=%.2f,
			`Order Invoiced Items Amount`=%.2f,`Order Invoiced Shipping Amount`=%.2f,`Order Invoiced Charges Amount`=%.2f,`Order Invoiced Insurance Amount`=%.2f,
			`Order Invoiced Net Amount`=%.2f,`Order Invoiced Tax Amount`=%.2f,
			`Order Out of Stock Net Amount`=%.2f,
			`Order Out of Stock Tax Amount`=%.2f,
			`Order No Authorized Net Amount`=%.2f,
			`Order No Authorized Tax Amount`=%.2f,
			`Order Not Found Net Amount`=%.2f,
			`Order Not Found Tax Amount`=%.2f,
			`Order Not Due Other Net Amount`=%.2f,
			`Order Not Due Other Tax Amount`=%.2f,

			`Order Invoiced Refund Net Amount`=%.2f,
			`Order Invoiced Refund Tax Amount`=%.2f,
			`Order Invoiced Refund Notes`=%s,
			`Order Invoiced Total Net Adjust Amount`=%.2f,
			`Order Invoiced Total Tax Adjust Amount`=%.2f,
			`Order Balance Net Amount`=%.2f,
			`Order Balance Tax Amount`=%.2f,
			`Order Balance Total Amount`=%.2f,
			`Order Outstanding Balance Net Amount`=%.2f,`Order Outstanding Balance Tax Amount`=%.2f,`Order Outstanding Balance Total Amount`=%.2f,
			`Order Profit Amount`=%.2f,
			`Order with Out of Stock`=%s,

			`Order Invoiced Items Tax Amount`=%.2f,
			`Order Invoiced Shipping Tax Amount`=%.2f,
			`Order Invoiced Charges Tax Amount`=%.2f,
			`Order Invoiced Insurance Tax Amount`=%.2f




			where `Order Key`=%d",
			$this->data['Order To Pay Amount'],

			$this->data['Order Payments Amount'],
			$this->data['Order Invoiced Balance Net Amount'],
			$this->data['Order Invoiced Balance Tax Amount'],
			$this->data['Order Invoiced Balance Total Amount'],

			$this->data['Order Invoiced Outstanding Balance Net Amount'],
			$this->data['Order Invoiced Outstanding Balance Tax Amount'],
			$this->data['Order Invoiced Outstanding Balance Total Amount'],

			$this->data['Order Tax Refund Amount'],
			$this->data['Order Net Refund Amount'],
			$this->data['Order Tax Credited Amount'],
			$this->data['Order Net Credited Amount'],

			$this->data['Order Invoiced Profit Amount'],

			$this->data['Order Invoiced Items Amount'],
			$this->data['Order Invoiced Shipping Amount'],
			$this->data['Order Invoiced Charges Amount'],
			$this->data['Order Invoiced Insurance Amount'],


			$this->data['Order Invoiced Net Amount'],
			$this->data['Order Invoiced Tax Amount'],

			$this->data['Order Out of Stock Net Amount'],
			$this->data['Order Out of Stock Tax Amount'],
			$this->data['Order No Authorized Net Amount'],
			$this->data['Order No Authorized Tax Amount'],
			$this->data['Order Not Found Net Amount'],
			$this->data['Order Not Found Tax Amount'],
			$this->data['Order Not Due Other Net Amount'],
			$this->data['Order Not Due Other Tax Amount'],

			$this->data['Order Invoiced Refund Net Amount'],
			$this->data['Order Invoiced Refund Tax Amount'],
			prepare_mysql($this->data['Order Invoiced Refund Notes']),
			$this->data['Order Invoiced Total Net Adjust Amount'],
			$this->data['Order Invoiced Total Tax Adjust Amount'],
			$this->data['Order Balance Net Amount'],
			$this->data['Order Balance Tax Amount'],
			$this->data['Order Balance Total Amount'],
			$this->data['Order Outstanding Balance Net Amount'],
			$this->data['Order Outstanding Balance Tax Amount'],
			$this->data['Order Outstanding Balance Total Amount'],
			$this->data['Order Profit Amount'],
			prepare_mysql($this->data['Order with Out of Stock']),





			$this->data['Order Invoiced Items Tax Amount'],
			$this->data['Order Invoiced Shipping Tax Amount'],
			$this->data['Order Invoiced Charges Tax Amount'],
			$this->data['Order Invoiced Insurance Tax Amount'],



			$this->id
		);
		///print $this->data['Order Balance Tax Amount'];

		mysql_query($sql);
		//print $sql."\n";





	}



	function update_totals_from_order_transactions_per_totals_method() {
		if ($this->ghost_order or !$this->id)
			return;

		$this->data['Order Shipping Net Amount']=0;
		$this->data['Order Shipping Tax Amount']=0;

		$sql=sprintf("select sum(`Transaction Net Amount`) as net , sum(`Transaction Tax Amount`) as tax from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`='Shipping' ",
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			//print_r($row);
			$this->data['Order Shipping Net Amount']=($row['net']==''?0:$row['net']);
			$this->data['Order Shipping Tax Amount']=($row['tax']==''?0:$row['tax']);
		}

		$sql=sprintf("update `Order Dimension` set `Order Shipping Net Amount`=%.2f ,`Order Shipping Tax Amount`=%.2f where `Order Key`=%d"
			, $this->data['Order Shipping Net Amount']
			, $this->data['Order Shipping Tax Amount']
			, $this->id
		);
		mysql_query($sql);

		//print "$sql\n";

		$this->data['Order Charges Net Amount']=0;
		$this->data['Order Charges Tax Amount']=0;

		$sql=sprintf("select sum(`Transaction Net Amount`) as net , sum(`Transaction Tax Amount`) as tax from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`='Charges' ",
			$this->id
		);
		//print "$sql\n";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Order Charges Net Amount']=($row['net']==''?0:$row['net']);
			$this->data['Order Charges Tax Amount']=($row['tax']==''?0:$row['tax']);
		}

		$sql=sprintf("update `Order Dimension` set `Order Charges Net Amount`=%.2f ,`Order Charges Tax Amount`=%.2f where `Order Key`=%d"
			, $this->data['Order Charges Net Amount']
			, $this->data['Order Charges Tax Amount']
			, $this->id
		);
		mysql_query($sql);



		$this->data['Order Insurance Net Amount']=0;
		$this->data['Order Insurance Tax Amount']=0;

		$sql=sprintf("select sum(`Transaction Net Amount`) as net , sum(`Transaction Tax Amount`) as tax from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`='Insurance' ",
			$this->id
		);
		//print "$sql\n";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Order Insurance Net Amount']=($row['net']==''?0:$row['net']);
			$this->data['Order Insurance Tax Amount']=($row['tax']==''?0:$row['tax']);
		}

		$sql=sprintf("update `Order Dimension` set `Order Insurance Net Amount`=%.2f ,`Order Insurance Tax Amount`=%.2f where `Order Key`=%d"
			, $this->data['Order Insurance Net Amount']
			, $this->data['Order Insurance Tax Amount']
			, $this->id
		);
		mysql_query($sql);



		//print_r($this->data);

		//print $this->data ['Order Items Net Amount'].' '.$this->data ['Order Shipping Net Amount'];

		$this->data ['Order Total Net Amount']=$this->data ['Order Items Net Amount']+  ($this->data ['Order Shipping Net Amount']==''?0:$this->data ['Order Shipping Net Amount'])+  $this->data ['Order Charges Net Amount']+  $this->data ['Order Insurance Net Amount']- $this->data ['Order Deal Amount Off'];

		//TODO we need to do a Order Tax Bridge and put the tax compnents there and add them up to do this (similar to the stuff in invoice)
		$tax=round($this->data ['Order Total Net Amount']*$this->data['Order Tax Rate'], 2);
		$this->data ['Order Total Tax Amount'] =$tax;

		$this->data ['Order Total Amount'] = $this->data ['Order Total Tax Amount'] + $this->data ['Order Total Net Amount'];

		$this->data ['Order Items Adjust Amount']=0;


		// print_r($this->data);

		$sql = sprintf( "update `Order Dimension` set
			`Order Total Net Amount`=%.2f,
			`Order Total Tax Amount`=%.2f ,
			`Order Total Amount`=%.2f,
			`Order Estimated Weight`=%f,
			`Order Dispatched Estimated Weight`=%f

			where  `Order Key`=%d "
			, $this->data ['Order Total Net Amount']
			, $this->data ['Order Total Tax Amount']


			, $this->data ['Order Total Amount']
			, $this->data ['Order Estimated Weight']
			, $this->data ['Order Dispatched Estimated Weight']
			, $this->id
		);


		$this->db->exec($sql);





	}


	function update_totals_from_order_transactions_per_item_method() {
		if ($this->ghost_order or !$this->id)
			return;

		$this->data['Order Shipping Net Amount']=0;
		$this->data['Order Shipping Tax Amount']=0;

		$sql=sprintf("select sum(`Transaction Net Amount`) as net , sum(`Transaction Tax Amount`) as tax from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`='Shipping' ",
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			//print_r($row);
			$this->data['Order Shipping Net Amount']=($row['net']==''?0:$row['net']);
			$this->data['Order Shipping Tax Amount']=($row['tax']==''?0:$row['tax']);
		}

		$sql=sprintf("update `Order Dimension` set `Order Shipping Net Amount`=%.2f ,`Order Shipping Tax Amount`=%.2f where `Order Key`=%d"
			, $this->data['Order Shipping Net Amount']
			, $this->data['Order Shipping Tax Amount']
			, $this->id
		);
		$this->db->exec($sql);

		//print "$sql\n";

		$this->data['Order Charges Net Amount']=0;
		$this->data['Order Charges Tax Amount']=0;

		$sql=sprintf("select sum(`Transaction Net Amount`) as net , sum(`Transaction Tax Amount`) as tax from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`='Charges' ",
			$this->id
		);
		//print "$sql\n";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Order Charges Net Amount']=($row['net']==''?0:$row['net']);
			$this->data['Order Charges Tax Amount']=($row['tax']==''?0:$row['tax']);
		}

		$sql=sprintf("update `Order Dimension` set `Order Charges Net Amount`=%.2f ,`Order Charges Tax Amount`=%.2f where `Order Key`=%d"
			, $this->data['Order Charges Net Amount']
			, $this->data['Order Charges Tax Amount']
			, $this->id
		);
		$this->db->exec($sql);



		$this->data['Order Insurance Net Amount']=0;
		$this->data['Order Insurance Tax Amount']=0;

		$sql=sprintf("select sum(`Transaction Net Amount`) as net , sum(`Transaction Tax Amount`) as tax from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`='Insurance' ",
			$this->id
		);
		//print "$sql\n";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$this->data['Order Insurance Net Amount']=($row['net']==''?0:$row['net']);
			$this->data['Order Insurance Tax Amount']=($row['tax']==''?0:$row['tax']);
		}

		$sql=sprintf("update `Order Dimension` set `Order Insurance Net Amount`=%.2f ,`Order Insurance Tax Amount`=%.2f where `Order Key`=%d"
			, $this->data['Order Insurance Net Amount']
			, $this->data['Order Insurance Tax Amount']
			, $this->id
		);
		$this->db->exec($sql);



		//print_r($this->data);

		//print $this->data ['Order Items Net Amount'].' '.$this->data ['Order Shipping Net Amount'];

		$this->data ['Order Total Net Amount']=$this->data ['Order Items Net Amount']+  ($this->data ['Order Shipping Net Amount']==''?0:$this->data ['Order Shipping Net Amount'])+  $this->data ['Order Charges Net Amount']+  $this->data ['Order Insurance Net Amount']- $this->data ['Order Deal Amount Off'];

		$tax_rate=0;


		// print_r($this->data);

		$tax_category=new TaxCategory($this->data['Order Tax Code']);
		$tax_rate=$tax_category->data['Tax Category Rate'];



		$this->data ['Order Total Tax Amount'] = $this->data ['Order Items Tax Amount'] + $this->data ['Order Shipping Tax Amount']+  $this->data ['Order Charges Tax Amount']+  $this->data ['Order Insurance Tax Amount'] -round( $tax_rate* $this->data ['Order Deal Amount Off'], 2) ;

		$this->data ['Order Total Amount'] = $this->data ['Order Total Tax Amount'] + $this->data ['Order Total Net Amount'];

		$this->data ['Order Items Adjust Amount']=0;


		// print_r($this->data);

		$sql = sprintf( "update `Order Dimension` set
			`Order Total Net Amount`=%.2f
			,`Order Total Tax Amount`=%.2f ,
			`Order Total Amount`=%.2f
			,`Order Estimated Weight`=%f
			,`Order Dispatched Estimated Weight`=%f

			where  `Order Key`=%d "
			, $this->data ['Order Total Net Amount']
			, $this->data ['Order Total Tax Amount']


			, $this->data ['Order Total Amount']
			, $this->data ['Order Estimated Weight']
			, $this->data ['Order Dispatched Estimated Weight']
			, $this->id
		);


		mysql_query( $sql );





	}




	function use_calculated_shipping() {

		$this->update_shipping_method('Calculated');
		$this->update_shipping();
		$this->updated=true;
		$this->update_totals();
		$this->apply_payment_from_customer_account();
		$this->new_value=$this->data['Order Shipping Net Amount'];

	}


	function use_calculated_items_charges() {

		$this->update_charges();
		$this->updated=true;
		$this->update_totals();
		$this->apply_payment_from_customer_account();
		$this->new_value=$this->data['Order Charges Net Amount'];

	}




	function update_shipping_amount($value, $dn_key=false) {
		$value=sprintf("%.2f", $value);

		$this->update_shipping_method('Set');
		$this->data['Order Shipping Net Amount']=$value;
		$this->update_shipping($dn_key);

		$this->updated=true;
		$this->new_value=$value;

		$this->update_totals();
		$this->apply_payment_from_customer_account();

	}


	function has_products_without_parts() {
		$has_products_without_parts=false;

		$sql=sprintf("select count(*) as products_with_out_parts	from `Order Transaction Fact` OTF  left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P on (PHD.`Product ID`=P.`Product ID`)  where `Order Key`=%d and `Product Number of Parts`=0  ",
			$this->id
		);
		$res=mysql_query($sql);

		if ($row=mysql_fetch_assoc($res)) {
			if ($row['products_with_out_parts']>0) {
				$has_products_without_parts=true;
			}
		}

		return $has_products_without_parts;
	}



	function update_charges_amount($charge_data) {

		//print_r($charge_data);

		if ($charge_data['Charge Net Amount']!=$this->data['Order Charges Net Amount']) {

			$this->data['Order Charges Net Amount']=$charge_data['Charge Net Amount'];

			$sql=sprintf('delete from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`="Charges" and `Consolidated`="No"',
				$this->id
			);
			mysql_query($sql);
			// print "$sql\n";

			$total_charges_net=$charge_data['Charge Net Amount'];
			$total_charges_tax=$charge_data['Charge Tax Amount'];
			if ($charge_data['Charge Tax Amount']!=0 or $charge_data['Charge Net Amount']!=0) {
				$sql=sprintf("insert into `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`,`Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,
					`Currency Code`,`Currency Exchange`,`Metadata`)  values (%d,%s,%s,%d,%s,%.2f,%.2f,%s,%.2f,%s,%.2f,%s)  ",
					$this->id,
					prepare_mysql($this->data['Order Date']),
					prepare_mysql('Charges'),
					$charge_data['Charge Key'],
					prepare_mysql($charge_data['Charge Description']),
					$charge_data['Charge Net Amount'],
					$charge_data['Charge Net Amount'],
					prepare_mysql($this->data['Order Tax Code']),
					$charge_data['Charge Tax Amount'],

					prepare_mysql($this->data['Order Currency']),
					$this->data['Order Currency Exchange'],
					prepare_mysql($this->data['Order Original Metadata'])
				);

				// print ("$sql\n");
				mysql_query($sql);
			}




			$this->data['Order Charges Net Amount']=$total_charges_net;
			$this->data['Order Charges Tax Amount']=$total_charges_tax;


			$sql=sprintf("update `Order Dimension` set `Order Charges Net Amount`=%.2f ,`Order Charges Tax Amount`=%.2f where `Order Key`=%d"
				, $this->data['Order Charges Net Amount']
				, $this->data['Order Charges Tax Amount']
				, $this->id
			);
			mysql_query($sql);
			//print "*a $sql\n";

			// exit;

			$this->updated=true;
			$this->new_value=$this->data['Order Charges Net Amount'];

			$this->update_totals();
			$this->apply_payment_from_customer_account();



		}




	}







	// function get_ship_to_from_customer($customer_key){
	//  return $customer->get_ship_to($this->data['Order Date']);
	// }


	function set_data_from_customer($customer_key) {


		$customer=new Customer($customer_key);

		$store_key=$customer->get('Store Key');




		$this->data ['Order Customer Key'] = $customer->id;
		$this->data ['Order Customer Name'] = $customer->data[ 'Customer Name' ];
		$this->data ['Order Customer Contact Name'] = $customer->data ['Customer Main Contact Name'];
		$this->data ['Order Tax Number'] = $customer->data ['Customer Tax Number'];
		$this->data ['Order Tax Number Valid'] = $customer->data ['Customer Tax Number Valid'];
		$this->data ['Order Customer Fiscal Name'] = $customer->get('Fiscal Name');
		$this->data ['Order Email'] = $customer->data ['Customer Main Plain Email'];
		$this->data ['Order Telephone'] = $customer->data ['Customer Main XHTML Telephone'];



		$this->data['Order Invoice Address Recipient']=$customer->get('Customer Invoice Address Recipient');
		$this->data['Order Invoice Address Organization']=$customer->get('Customer Invoice Address Organization');
		$this->data['Order Invoice Address Line 1']=$customer->get('Customer Invoice Address Line 1');
		$this->data['Order Invoice Address Line 2']=$customer->get('Customer Invoice Address Line 2');
		$this->data['Order Invoice Address Sorting Code']=$customer->get('Customer Invoice Address Sorting Code');
		$this->data['Order Invoice Address Postal Code']=$customer->get('Customer Invoice Address Postal Code');
		$this->data['Order Invoice Address Dependent Locality']=$customer->get('Customer Invoice Address Dependent Locality');
		$this->data['Order Invoice Address Locality']=$customer->get('Customer Invoice Address Locality');
		$this->data['Order Invoice Address Administrative Area']=$customer->get('Customer Invoice Address Administrative Area');
		$this->data['Order Invoice Address Country 2 Alpha Code']=$customer->get('Customer Invoice Address Country 2 Alpha Code');
		$this->data['Order Invoice Address Checksum']=$customer->get('Customer Invoice Address Recipient');
		$this->data['Order Invoice Address Formatted']=$customer->get('Customer Invoice Address Formatted');
		$this->data['Order Invoice Address Postal Label']=$customer->get('Customer Invoice Address Postal Label');


		$this->data['Order Delivery Address Recipient']=$customer->get('Customer Delivery Address Recipient');
		$this->data['Order Delivery Address Organization']=$customer->get('Customer Delivery Address Organization');
		$this->data['Order Delivery Address Line 1']=$customer->get('Customer Delivery Address Line 1');
		$this->data['Order Delivery Address Line 2']=$customer->get('Customer Delivery Address Line 2');
		$this->data['Order Delivery Address Sorting Code']=$customer->get('Customer Delivery Address Sorting Code');
		$this->data['Order Delivery Address Postal Code']=$customer->get('Customer Delivery Address Postal Code');
		$this->data['Order Delivery Address Dependent Locality']=$customer->get('Customer Delivery Address Dependent Locality');
		$this->data['Order Delivery Address Locality']=$customer->get('Customer Delivery Address Locality');
		$this->data['Order Delivery Address Administrative Area']=$customer->get('Customer Delivery Address Administrative Area');
		$this->data['Order Delivery Address Country 2 Alpha Code']=$customer->get('Customer Delivery Address Country 2 Alpha Code');
		$this->data['Order Delivery Address Checksum']=$customer->get('Customer Delivery Address Recipient');
		$this->data['Order Delivery Address Formatted']=$customer->get('Customer Delivery Address Formatted');
		$this->data['Order Delivery Address Postal Label']=$customer->get('Customer Delivery Address Postal Label');





		$this->data ['Order Customer Order Number']=$customer->get_number_of_orders()+1;

		$this->set_data_from_store($store_key);
	}


	function set_data_from_store($store_key) {
		$store=new Store($store_key);
		if (!$store->id) {
			$this->error=true;
			return;
		}

		$this->data ['Order Store Key'] = $store->id;
		$this->data ['Order Currency']=$store->get( 'Store Currency Code' );
		$this->data['Order Show in Warehouse Orders']=$store->data['Store Show in Warehouse Orders'];

		$this->public_id_format=$store->get('Store Order Public ID Format');



	}






	function next_public_id() {

		$sqla=sprintf("UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) where `Store Key`=%d"
			, $this->data['Order Store Key']);
		mysql_query($sqla);
		$public_id=mysql_insert_id();

		$this->data['Order Public ID']=sprintf($this->public_id_format, $public_id);
		$this->data['Order File As']=$this->prepare_file_as($this->data['Order Public ID']);
	}


	function get_next_line_number() {

		$sql=sprintf("select count(*) as num_lines from `Order Transaction Fact` where `Order Key`=%d ", $this->id);
		$res=mysql_query($sql);

		$line_number=1;
		if ($row=mysql_fetch_array($res))
			$line_number+=$row['num_lines'];
		return $line_number;


	}






	function update_tax($tax_category_code=false) {




		$old_tax_code=$this->data['Order Tax Code'];

		if ($tax_category_code) {
			$tax_category=new TaxCategory('code', $tax_category_code);
			if (!$tax_category->id) {
				$this->msg='Invalid tax code';
				$this->error=true;
				return;
			}else {

				$this->data['Order Tax Code']=$tax_category->data['Tax Category Code'];
				$this->data['Order Tax Rate']=$tax_category->data['Tax Category Rate'];
				$this->data['Order Tax Name']=$tax_category->data['Tax Category Name'];
				$this->data['Order Tax Operations']='';
				$this->data['Order Tax Selection Type']='set';

			}


		}else {

			$tax_data=$this->get_tax_data();

			//print_r($tax_data);
			$this->data['Order Tax Code']=$tax_data['code'];
			$this->data['Order Tax Rate']=$tax_data['rate'];
			$this->data['Order Tax Name']=$tax_data['name'];
			$this->data['Order Tax Operations']=$tax_data['operations'];
			$this->data['Order Tax Selection Type']=$tax_data['state'];

		}


		$sql=sprintf("update `Order Transaction Fact` set `Transaction Tax Rate`=%f,`Transaction Tax Code`=%s where `Order Key`=%d and `Consolidated`='No' and `Transaction Tax Code`=%s  ",
			$this->data['Order Tax Rate'],
			prepare_mysql($this->data['Order Tax Code']),
			$this->id,
			prepare_mysql($old_tax_code)

		);
		mysql_query($sql);
		$sql=sprintf("select `Tax Category Code`,`Transaction Type`,`Order No Product Transaction Fact Key`,`Transaction Net Amount` from `Order No Product Transaction Fact`  where `Order Key`=%d and `Consolidated`='No'",
			$this->id
		);


		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			if ($row['Transaction Type']=='Insurance') {
				// this to be removed!!!!


				$_transaction_tax_category=new TaxCategory('code', 'EX');
				$sql=sprintf("update `Order No Product Transaction Fact` set `Transaction Tax Amount`=%f,`Tax Category Code`=%s where `Order No Product Transaction Fact Key`=%d",
					$row['Transaction Net Amount']*$_transaction_tax_category->data['Tax Category Rate'],
					prepare_mysql($_transaction_tax_category->data['Tax Category Code']),
					$row['Order No Product Transaction Fact Key']
				);
				// print $sql;
				mysql_query($sql);
			}elseif ($row['Tax Category Code']==$old_tax_code) {

				$sql=sprintf("update `Order No Product Transaction Fact` set `Transaction Tax Amount`=%f,`Tax Category Code`=%s where `Order No Product Transaction Fact Key`=%d",
					$row['Transaction Net Amount']*$this->data['Order Tax Rate'],
					prepare_mysql($this->data['Order Tax Code']),
					$row['Order No Product Transaction Fact Key']
				);
				// print $sql;
				mysql_query($sql);

			}
		}

		$sql=sprintf("update `Order Dimension` set `Order Tax Code`=%s ,`Order Tax Rate`=%f,`Order Tax Name`=%s,`Order Tax Operations`=%s,`Order Tax Selection Type`=%s where `Order Key`=%d",
			prepare_mysql($this->data['Order Tax Code']),
			$this->data['Order Tax Rate'],
			prepare_mysql($this->data['Order Tax Name']),
			prepare_mysql($this->data['Order Tax Operations'], false),
			prepare_mysql($this->data['Order Tax Selection Type']),
			$this->id
		);

		mysql_query($sql);

		$this->update_totals();
		$this->apply_payment_from_customer_account();

	}






	function update_shipping($dn_key=false, $order_picked=true) {

		if (!$dn_key)$dn_key='';


		if ($dn_key and $order_picked) {
			list($shipping, $shipping_key, $shipping_method)=$this->get_shipping($dn_key);
		} else {
			list($shipping, $shipping_key, $shipping_method)=$this->get_shipping();
		}


		//print "$shipping,$shipping_key,$shipping_method";
		if (!is_numeric($shipping)) {

			$this->data['Order Shipping Net Amount']=0;
			$this->data['Order Shipping Tax Amount']=0;
		} else {

			$this->data['Order Shipping Net Amount']=$shipping;
			$this->data['Order Shipping Tax Amount']=$shipping*$this->data['Order Tax Rate'];
		}



		$this->update_shipping_method($shipping_method);


		if (!$dn_key) {

			$sql=sprintf('delete from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`="Shipping" ',
				$this->id
			);
		} else {
			$sql=sprintf('delete from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`="Shipping"  and `Delivery Note Key`=%d and `Invoice Key` IS NULL',
				$this->id,
				$dn_key
			);


		}




		//print $sql;
		mysql_query($sql);



		if (!($this->data['Order Shipping Net Amount']==0 and $this->data['Order Shipping Tax Amount']==0)) {
			$sql=sprintf("insert into `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`,
				`Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,
				`Currency Code`,`Currency Exchange`,`Metadata`,`Delivery Note Key`)  values (%d,%s,%s,%d,%s,%.2f,%.2f,%s,%.2f,%s,%.2f,%s,%s)  ",
				$this->id,
				prepare_mysql($this->data['Order Date']),
				prepare_mysql('Shipping'),
				$shipping_key,
				prepare_mysql(_('Shipping')),
				$this->data['Order Shipping Net Amount'],
				$this->data['Order Shipping Net Amount'],
				prepare_mysql($this->data['Order Tax Code']),
				$this->data['Order Shipping Tax Amount'],


				prepare_mysql($this->data['Order Currency']),
				$this->data['Order Currency Exchange'],
				prepare_mysql($this->data['Order Original Metadata']),
				prepare_mysql($dn_key)

			);

			//print ("$sql\n");
			mysql_query($sql);
		}



		$this->update_totals();

		$this->apply_payment_from_customer_account();

	}



	function update_charges($dn_key=false, $order_picked=true) {

		if (!$dn_key) {
			$dn_key='';
			$sql=sprintf('delete from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`="Charges" and `Delivery Note Key` IS NULL and `Invoice Key` IS NULL',
				$this->id
			);
		} else {
			$sql=sprintf('delete from `Order No Product Transaction Fact` where `Order Key`=%d and `Transaction Type`="Charges" and `Delivery Note Key`=%d and `Invoice Key` IS NULL',
				$this->id,
				$dn_key
			);


		}
		//print $sql;
		mysql_query($sql);


		if ($dn_key and $order_picked)
			$charges_array=$this->get_charges($dn_key);
		else
			$charges_array=$this->get_charges();


		$total_charges_net=0;
		$total_charges_tax=0;
		foreach ($charges_array as $charge_data) {
			$total_charges_net+=$charge_data['Charge Net Amount'];
			$total_charges_tax+=$charge_data['Charge Tax Amount'];

			if (!($charge_data['Charge Net Amount']==0 and $charge_data['Charge Tax Amount']==0)) {
				$sql=sprintf("insert into `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`,`Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,`Currency Code`,`Currency Exchange`,`Metadata`,`Delivery Note Key`)

					values (%d,%s,%s,%d,%s,%.2f,%.2f,%s,%.2f,%s,%.2f,%s,%s)  ",
					$this->id,
					prepare_mysql($this->data['Order Date']),
					prepare_mysql('Charges'),
					$charge_data['Charge Key'],
					prepare_mysql($charge_data['Charge Description']),
					$charge_data['Charge Net Amount'],
					$charge_data['Charge Net Amount'],
					prepare_mysql($this->data['Order Tax Code']),
					$charge_data['Charge Tax Amount'],

					prepare_mysql($this->data['Order Currency']),
					$this->data['Order Currency Exchange'],
					prepare_mysql($this->data['Order Original Metadata']),
					prepare_mysql($dn_key)

				);

				mysql_query($sql);



			}

		}



		$this->data['Order Charges Net Amount']=$total_charges_net;
		$this->data['Order Charges Tax Amount']=$total_charges_tax;


		$sql=sprintf("update `Order Dimension` set `Order Charges Net Amount`=%s ,`Order Charges Tax Amount`=%.2f where `Order Key`=%d"
			, $this->data['Order Charges Net Amount']
			, $this->data['Order Charges Tax Amount']
			, $this->id
		);
		mysql_query($sql);
		// print "* $sql\n";


	}


	function update_insurance($dn_key=false) {
		$valid_insurances=$this->get_insurances($dn_key);

		$sql=sprintf("select `Transaction Type Key`,`Order No Product Transaction Fact Key`  from `Order No Product Transaction Fact` where `Order Key`=%d  and `Transaction Type`='Insurance' ",
			$this->id

		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			if (!array_key_exists($row['Transaction Type Key'], $valid_insurances)) {

				$sql=sprintf("delete from `Order No Product Transaction Fact` where `Order No Product Transaction Fact Key`=%d ",
					$row['Order No Product Transaction Fact Key']
				);
				mysql_query($sql);
			}

		}
		$this->update_totals();
		$this->apply_payment_from_customer_account();

	}


	function remove_insurance($onptf_key) {

		$sql=sprintf("delete from `Order No Product Transaction Fact` where `Order No Product Transaction Fact Key`=%d and `Order Key`=%d",
			$onptf_key,
			$this->id
		);
		mysql_query($sql);

		$this->update_totals();
		$this->apply_payment_from_customer_account();
	}


	function add_insurance($insurance_key, $dn_key=false) {

		$valid_insurances=$this->get_insurances($dn_key);

		if (array_key_exists($insurance_key, $valid_insurances)) {

			if (!$valid_insurances[$insurance_key]['Order No Product Transaction Fact Key']) {




				$sql=sprintf("insert into `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Transaction Type`,`Transaction Type Key`,`Transaction Description`
					,`Transaction Gross Amount`,`Transaction Net Amount`,`Tax Category Code`,`Transaction Tax Amount`,`Currency Code`,`Currency Exchange`,`Metadata`,`Delivery Note Key`)
				values (%d,%s,%s,%d,%s,%.2f,%.2f,%s,%.2f,%s,%.2f,%s,%s)  ",
					$this->id,
					prepare_mysql(gmdate("Y-m-d H:i:s")),
					prepare_mysql('Insurance'),
					$insurance_key,
					prepare_mysql($valid_insurances[$insurance_key]['Insurance Description']),
					$valid_insurances[$insurance_key]['Insurance Net Amount'],
					$valid_insurances[$insurance_key]['Insurance Net Amount'],
					prepare_mysql($valid_insurances[$insurance_key]['Insurance Tax Code']),
					$valid_insurances[$insurance_key]['Insurance Tax Amount'],

					prepare_mysql($this->data['Order Currency']),
					$this->data['Order Currency Exchange'],
					prepare_mysql($this->data['Order Original Metadata']),
					prepare_mysql($dn_key)

				);
				mysql_query($sql);

				$onptf_key=mysql_insert_id();

				$this->update_totals();

				$this->apply_payment_from_customer_account();
			}else {
				$onptf_key=$valid_insurances[$insurance_key]['Order No Product Transaction Fact Key'];
			}

		}else {
			$onptf_key=0;
		}

		return $onptf_key;
	}


	function get_insurances($dn_key=false) {
		$insurances=array();
		if ($this->data['Order Number Items']==0) {

			return $insurances;
		}


		$sql=sprintf("select * from `Insurance Dimension` where `Insurance Trigger`='Order' and (`Insurance Trigger Key`=%d  or `Insurance Trigger Key` is null) and `Insurance Store Key`=%d"
			, $this->id
			, $this->data['Order Store Key']
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {



			$apply_insurance=false;

			$order_amount=$this->data[$row['Insurance Terms Type']];



			if ($dn_key) {
				switch ($row['Insurance Terms Type']) {

				case 'Order Items Net Amount':

					$sql=sprintf("select sum(`Order Transaction Net Amount`*(`Delivery Note Quantity`/`Order Quantity`)) as amount from `Order Transaction Fact` where `Order Key`=%d and `Delivery Note Key`=%d and `Order Quantity`!=0",
						$this->id,
						$dn_key
					);
					$res=mysql_query($sql);
					if ($row2=mysql_fetch_assoc($res)) {
						$order_amount=$row2['amount'];
					} else {
						$order_amount=0;
					}
					break;



				case 'Order Items Gross Amount':
				default:
					$sql=sprintf("select sum(`Order Transaction Gross Amount`*(`Delivery Note Quantity`/`Order Quantity`)) as amount from `Order Transaction Fact` where `Order Key`=%d and `Delivery Note Key`=%d and `Order Quantity`!=0",
						$this->id,
						$dn_key
					);
					$res=mysql_query($sql);
					if ($row2=mysql_fetch_assoc($res)) {
						$order_amount=$row2['amount'];
					} else {
						$order_amount=0;
					}
					break;
				}
			}







			$terms_components=preg_split('/;/', $row['Insurance Terms Metadata']);
			$operator=$terms_components[0];
			$amount=$terms_components[1];

			//print_r($order_amount);


			switch ($operator) {
			case('<'):
				if ($order_amount<$amount)
					$apply_insurance=true;
				break;
			case('>'):
				if ($order_amount>$amount)
					$apply_insurance=true;
				break;
			case('<='):
				if ($order_amount<=$amount)
					$apply_insurance=true;
				break;
			case('>='):
				if ($order_amount>=$amount)
					$apply_insurance=true;
				break;
			}


			if ($row['Insurance Tax Category Code']=='') {
				$tax_category_code=$this->data['Order Tax Code'];
				$tax_rate=$this->data['Order Tax Rate'];
			}else {
				$tax_category=new TaxCategory($row['Insurance Tax Category Code']);
				$tax_category_code=$tax_category->data['Tax Category Code'];
				$tax_rate=$tax_category->data['Tax Category Rate'];

			}



			if ($row['Insurance Type']=='Amount') {
				$charge_net_amount=$row['Insurance Metadata'];





				$charge_tax_amount=$row['Insurance Metadata']*$tax_rate;
			}else {

				exit("still to do");
			}


			$sql=sprintf("select `Order No Product Transaction Fact Key`  from `Order No Product Transaction Fact` where `Order Key`=%d  and `Transaction Type`='Insurance' and `Transaction Type Key`=%d ",
				$this->id,
				$row['Insurance Key']
			);
			$res2=mysql_query($sql);
			if ($row2=mysql_fetch_assoc($res2)) {
				$onptf_key=$row2['Order No Product Transaction Fact Key'];
			}else {
				$onptf_key=0;
			}

			if ($apply_insurance)
				$insurances[$row['Insurance Key']]=array(
					'Insurance Net Amount'=>$charge_net_amount,
					'Insurance Tax Amount'=>$charge_tax_amount,
					'Insurance Formatted Net Amount'=>money($this->exchange*$charge_net_amount, $this->currency_code),
					'Insurance Formatted Tax Amount'=>money($this->exchange*$charge_tax_amount, $this->currency_code),
					'Insurance Tax Code'=>$tax_category_code,
					'Insurance Key'=>$row['Insurance Key'],
					'Insurance Description'=>$row['Insurance Name'],
					'Order No Product Transaction Fact Key'=>$onptf_key
				);



		}
		return $insurances;

	}




	function get_charges($dn_key=false) {
		$charges=array();;
		if ($this->data['Order Number Items']==0) {

			return $charges;
		}


		$sql=sprintf("select * from `Charge Dimension` where `Charge Trigger`='Order' and (`Charge Trigger Key`=%d  or `Charge Trigger Key` is null) and `Store Key`=%d"
			, $this->id
			, $this->data['Order Store Key']
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {



			$apply_charge=false;

			$order_amount=$this->data[$row['Charge Terms Type']];



			if ($dn_key) {
				switch ($row['Charge Terms Type']) {

				case 'Order Items Net Amount':

					$sql=sprintf("select sum(`Order Transaction Net Amount`*(`Delivery Note Quantity`/`Order Quantity`)) as amount from `Order Transaction Fact` where `Order Key`=%d and `Delivery Note Key`=%d and `Order Quantity`!=0",
						$this->id,
						$dn_key
					);
					$res=mysql_query($sql);
					if ($row2=mysql_fetch_assoc($res)) {
						$order_amount=$row2['amount'];
					} else {
						$order_amount=0;
					}
					break;



				case 'Order Items Gross Amount':
				default:
					$sql=sprintf("select sum(`Order Transaction Gross Amount`*(`Delivery Note Quantity`/`Order Quantity`)) as amount from `Order Transaction Fact` where `Order Key`=%d and `Delivery Note Key`=%d and `Order Quantity`!=0",
						$this->id,
						$dn_key
					);
					$res=mysql_query($sql);
					if ($row2=mysql_fetch_assoc($res)) {
						$order_amount=$row2['amount'];
					} else {
						$order_amount=0;
					}
					break;
				}
			}







			$terms_components=preg_split('/;/', $row['Charge Terms Metadata']);
			$operator=$terms_components[0];
			$amount=$terms_components[1];

			//print_r($order_amount);


			switch ($operator) {
			case('<'):
				if ($order_amount<$amount)
					$apply_charge=true;
				break;
			case('>'):
				if ($order_amount>$amount)
					$apply_charge=true;
				break;
			case('<='):
				if ($order_amount<=$amount)
					$apply_charge=true;
				break;
			case('>='):
				if ($order_amount>=$amount)
					$apply_charge=true;
				break;
			}




			if ($row['Charge Type']=='Amount') {
				$charge_net_amount=$row['Charge Metadata'];
				$charge_tax_amount=$row['Charge Metadata']*$this->data['Order Tax Rate'];
			}else {

				exit("still to do");
			}


			if ($apply_charge)
				$charges[]=array(
					'Charge Net Amount'=>$charge_net_amount,
					'Charge Tax Amount'=>$charge_tax_amount,
					'Charge Key'=>$row['Charge Key'],
					'Charge Description'=>$row['Charge Name']
				);



		}
		return $charges;

	}


	function get_shipping($dn_key=false) {


		if ($this->data['Order Number Items']==0) {
			return array(0, 0, 'No Applicable');
		}


		if ($this->data['Order For Collection']=='Yes')
			return array(0, 0, 'No Applicable');

		if ($this->data['Order Shipping Method']=='Set') {

			//print $this->data['Order Shipping Net Amount'].'xx';
			return array(($this->data['Order Shipping Net Amount']==''?0:$this->data['Order Shipping Net Amount']), 0, 'Set');
		}




		if (in_array($this->data['Order Ship To Country Code'], array('GBR', 'JEY', 'GGY', 'IMN'))) {
			include_once 'utils/geography_functions.php';

			$postcode = gbr_postcode_first_part($this->data['Order Ship To Postal Code']);
		}else {
			$postcode =$this->data['Order Ship To Postal Code'];
		}






		$sql=sprintf("select `Shipping Destination Metadata`,`Shipping Key`,`Shipping Metadata`,`Shipping Price Method`  from `Shipping Dimension`  where (select %s like `Shipping Destination Metadata` ) and  `Shipping Destination Type`='Country' and `Shipping Destination Code`=%s  and `Shipping Secondary Destination Check`='Post Code' and `Store Key`=%d "
			, prepare_mysql($postcode)
			, prepare_mysql($this->data['Order Ship To Country Code'])
			, $this->data['Order Store Key']

		);



		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {



			list($shipping, $method)=$this->get_shipping_from_method($row['Shipping Price Method'], $row['Shipping Metadata'], $dn_key);
			return array($shipping, $row['Shipping Key'], $method);
		}


		$sql=sprintf("select `Shipping Key`,`Shipping Metadata`,`Shipping Price Method` from `Shipping Dimension` where  `Shipping Destination Type`='Country' and `Shipping Destination Code`=%s  and   `Shipping Secondary Destination Check`='None'  and `Store Key`=%d  "
			, prepare_mysql($this->data['Order Ship To Country Code'])
			, $this->data['Order Store Key']
		);


		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			list($shipping, $method)=$this->get_shipping_from_method($row['Shipping Price Method'], $row['Shipping Metadata'], $dn_key);
			return array($shipping, $row['Shipping Key'], $method);
		}


		return array(0, 0, 'TBC');


	}




	function get_shipping_from_method($type, $metadata, $dn_key=false) {



		switch ($type) {

		case('Step Order Items Net Amount'):
			return $this->get_shipping_Step_Order_Items_Net_Amount($metadata, $dn_key);
			break;

		case('Step Order Items Gross Amount'):
			return $this->get_shipping_Step_Order_Items_Gross_Amount($metadata, $dn_key);
			break;
		case('On Request'):
			return array(0, 'TBC');
			break;

		}

	}


	function get_shipping_Step_Order_Items_Net_Amount($metadata, $dn_key=false) {

		if ($dn_key) {
			$sql=sprintf("select sum( `Order Transaction Amount`*(`Delivery Note Quantity`/`Order Quantity`)  ) as amount from `Order Transaction Fact` where `Order Key`=%d and `Delivery Note Key`=%d and `Order Quantity`!=0",
				$this->id,
				$dn_key
			);
			//print $sql;
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$amount=$row['amount'];
			} else {
				$amount=0;
			}
		} else {
			$amount=$this->data['Order Items Net Amount'];
		}

		if ($amount==0) {

			return array(0, 'Calculated');

		}
		$data=preg_split('/\;/', $metadata);

		foreach ($data as $item) {

			list($min, $max, $value)=preg_split('/\,/', $item);
			//print "$min,$max,$value\n";
			if ($min=='') {
				if ($amount<$max)
					return array($value, 'Calculated');
			}
			elseif ($max=='') {
				if ($amount>=$min)
					return array($value, 'Calculated');
			}
			elseif ($amount<$max and $amount>=$min) {
				return array($value, 'Calculated');

			}


		}
		return array(0, 'TBC');

	}





	function update_transaction_discount_percentage($otf_key, $percentage) {
		$sql=sprintf('select `Product Key`,`Order Transaction Fact Key`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` from  `Order Transaction Fact`  where `Order Transaction Fact Key`=%d ',
			$otf_key
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {



			$discount_amount=round(($row['Order Transaction Gross Amount'])*$percentage/100, 2);


			return $this->update_transaction_discount_amount($otf_key, $discount_amount);
		}else {
			$this->error=true;
			$this->msg='otf not found';
		}

	}


	function update_transaction_discount_amount($otf_key, $discount_amount, $deal_campaign_key=0, $deal_key=0, $deal_component_key=0) {

		$deal_info='';

		$sql=sprintf('select `Order Transaction Amount`,OTF.`Product Family Key`,OTF.`Product ID`,`Product XHTML Short Description`,`Order Quantity`,`Product Key`,`Order Transaction Fact Key`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` from  `Order Transaction Fact` OTF left join `Product Dimension` P on  (P.`Product ID`=OTF.`Product ID`) where `Order Transaction Fact Key`=%d ',
			$otf_key
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {



			if ($discount_amount==$row['Order Transaction Total Discount Amount'] or $row['Order Transaction Gross Amount']==0) {
				$this->msg='Nothing to Change';
				$return_data= array(
					'updated'=>true,
					'otf_key'=>$otf_key,
					'description'=>$row['Product XHTML Short Description'].' <span class="deal_info">'.$deal_info.'</span>',
					'discount_percentage'=>percentage($discount_amount, $row['Order Transaction Gross Amount'], $fixed=1, $error_txt='NA', $psign=''),
					'to_charge'=>money($row['Order Transaction Amount'], $this->data['Order Currency']),
					'qty'=>$row['Order Quantity'],
					'bonus qty'=>0
				);
				return $return_data;
			}


			$sql=sprintf("delete from `Order Transaction Deal Bridge` where `Order Transaction Fact Key` =%d", $otf_key);
			mysql_query($sql);


			$sql=sprintf('update `Order Transaction Fact` OTF set `Order Transaction Amount`=%.2f, `Order Transaction Total Discount Amount`=%f where `Order Transaction Fact Key`=%d ',
				$row['Order Transaction Gross Amount']-$discount_amount,
				$discount_amount,
				$otf_key
			);
			mysql_query($sql);
			//print "$sql\n";

			$deal_info='';
			if ($discount_amount>0  ) {

				$deal_info=sprintf(_('%s off'), percentage($discount_amount, $row['Order Transaction Gross Amount']));

				$sql=sprintf("insert into `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Product Family Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bunus Quantity`) values (%d,%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,0)",
					$row['Order Transaction Fact Key'],
					$this->id,
					$row['Product Key'],
					$row['Product ID'],
					$row['Product Family Key'],
					$deal_campaign_key,
					$deal_key,
					$deal_component_key,
					prepare_mysql($deal_info, false),
					$discount_amount,
					($discount_amount/$row['Order Transaction Gross Amount'])
				);

				mysql_query($sql);
				$this->updated=true;
			}


			$this->update_totals();
			$this->apply_payment_from_customer_account();

			return array(
				'updated'=>true,
				'otf_key'=>$otf_key,
				'description'=>$row['Product XHTML Short Description'].' <span class="deal_info">'.$deal_info.'</span>',
				'discount_percentage'=>percentage($discount_amount, $row['Order Transaction Gross Amount'], $fixed=1, $error_txt='NA', $psign=''),
				'to_charge'=>money($row['Order Transaction Gross Amount']-$discount_amount, $this->data['Order Currency']),
				'qty'=>$row['Order Quantity'],
				'bonus qty'=>0
			);





		}
		else {
			$this->error=true;
			$this->msg='otf not found';
		}


	}


	function update_discounts_items() {


		$this->allowance=array('Percentage Off'=>array(), 'Get Free'=>array(), 'Order Get Free'=>array() );
		$this->deals=array('Family'=>array('Deal'=>false, 'Terms'=>false, 'Deal Multiplicity'=>0, 'Terms Multiplicity'=>0));

		$sql=sprintf('update `Order Transaction Fact` set `Order Transaction Total Discount Amount`=0 , `Order Transaction Amount`=`Order Transaction Gross Amount` where `Order Key`=%d  '
			, $this->id
		);
		mysql_query($sql);
		$sql=sprintf("delete from `Order Transaction Deal Bridge` where `Order Key` =%d and `Deal Component Key`!=0  ", $this->id);
		mysql_query($sql);


		$this->get_allowances_from_order_trigger();
		$this->get_allowances_from_department_trigger();
		$this->get_allowances_from_family_trigger();
		$this->get_allowances_from_product_trigger();
		$this->get_allowances_from_customer_trigger();

		$this->apply_items_discounts() ;

	}


	function apply_items_discounts() {

		//print_r($this->allowance);
		foreach ($this->allowance['Percentage Off'] as $otf_key=>$allowance_data) {



			$sql=sprintf("insert into `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Product Family Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bunus Quantity`) values
			(%d,%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,0)",
				$otf_key,
				$this->id,

				$allowance_data['Product Key'],
				$allowance_data['Product ID'],
				$allowance_data['Product Family Key'],
				$allowance_data['Deal Campaign Key'],
				$allowance_data['Deal Key'],
				$allowance_data['Deal Component Key'],

				prepare_mysql($allowance_data['Deal Info']),
				$allowance_data['Order Transaction Gross Amount']*$allowance_data['Percentage Off'],
				$allowance_data['Percentage Off']
			);
			mysql_query($sql);
			//print "$sql\n";

		}

		foreach ($this->allowance['Order Get Free'] as $allowance_data) {


			//print_r($allowance_data);
			$sql=sprintf('select `Product Family Key`,`Product ID`,OTF.`Product Key`,`Order Transaction Fact Key`,`Order Transaction Gross Amount` from  `Order Transaction Fact` OTF  where `Order Key`=%d and `Product ID`=%d '
				, $this->id
				, $allowance_data['Product ID']
			);

			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {


				$amount_discount=0;
				$fraction_discount=0;

				$sql=sprintf("insert into `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Product Family Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bunus Quantity`) values (%d,%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,%d)"
					, $row['Order Transaction Fact Key']
					, $this->id

					, $row['Product Key']
					, $row['Product ID']
					, $row['Product Family Key']
					, $allowance_data['Deal Campaign Key']
					, $allowance_data['Deal Key']
					, $allowance_data['Deal Component Key']

					, prepare_mysql($allowance_data['Deal Info'])
					, $amount_discount
					, $fraction_discount
					, $allowance_data['Get Free']
				);
				mysql_query($sql);
				// print "$sql\n";
			}
		}



		$sql=sprintf("select * from `Order Transaction Deal Bridge` where `Order Key`=%d  ", $this->id);
		$res=mysql_query($sql);
		//print $sql;
		while ($row=mysql_fetch_assoc($res)) {
			if ( $row['Fraction Discount']>0  ) {
				$sql=sprintf('update `Order Transaction Fact` OTF  set  `Order Transaction Total Discount Amount`=`Order Transaction Gross Amount`*%f where `Order Transaction Fact Key`=%d '
					, $row['Fraction Discount']
					, $row['Order Transaction Fact Key']
				);
				//print $sql;
				mysql_query($sql);

				$sql=sprintf('update `Order Transaction Fact` OTF  set  `Order Transaction Amount`=`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount` where `Order Transaction Fact Key`=%d '
					, $row['Order Transaction Fact Key']
				);
				//print $sql;
				mysql_query($sql);

			}

			if ( $row['Bunus Quantity']>0  ) {
				$sql=sprintf('update `Order Transaction Fact` OTF  set  `Order Bonus Quantity`=%f where `Order Transaction Fact Key`=%d '
					, $row['Bunus Quantity']
					, $row['Order Transaction Fact Key']
				);
				//print $sql;
				mysql_query($sql);
			}


		}





	}


	function update_discounts_no_items($dn_key=false) {



		if ($dn_key) {
			return;
		}

		$this->allowance=array('Percentage Off'=>array(), 'Get Free'=>array(), 'Order Get Free'=>array(), 'Get Same Free'=>array(), 'Credit'=>array(), 'No Item Transaction'=>array());
		$this->deals=array(
			'Order'=>array('Deal'=>false, 'Terms'=>false, 'Deal Multiplicity'=>0, 'Terms Multiplicity'=>0)

		);

		$this->update(array('Order Deal Amount Off'=>0));

		$sql=sprintf('update `Order No Product Transaction Fact` set `Transaction Total Discount Amount`=0 , `Transaction Net Amount`=`Transaction Gross Amount` where `Order Key`=%d  '
			, $this->id
		);
		mysql_query($sql);


		$sql=sprintf("delete from `Order No Product Transaction Deal Bridge` where `Order Key` =%d and `Deal Component Key`!=0  ", $this->id);
		mysql_query($sql);


		$sql=sprintf("select `Bonus Order Transaction Fact Key` from `Order Meta Transaction Deal Dimension` where `Order Key` =%d and `Deal Component Key`!=0  ", $this->id);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {


			$sql=sprintf("delete from `Order Transaction Fact` where `Order Transaction Fact Key`=%d", $row['Bonus Order Transaction Fact Key']);
			mysql_query($sql);
			//print $sql;
		}

		$sql=sprintf("delete from `Order Meta Transaction Deal Dimension` where `Order Key` =%d and `Deal Component Key`!=0  ", $this->id);
		mysql_query($sql);

		$this->get_allowances_from_order_trigger($no_items=true);
		$this->get_allowances_from_customer_trigger($no_items=true);

		$this->apply_no_items_discounts() ;

	}


	function apply_no_items_discounts() {
		//print "****\n";
		//print_r($this->allowance);
		foreach ($this->allowance['Percentage Off'] as $otf_key=>$allowance_data) {


			$sql=sprintf("select `Fraction Discount` from `Order Transaction Deal Bridge` where `Order Transaction Fact Key`=%d and `Fraction Discount`>0", $otf_key);
			$res=mysql_query($sql);

			if ($row=mysql_fetch_assoc($res)) {

				//print_r($row);
				if ($row['Fraction Discount']>$allowance_data['Percentage Off']) {
					continue;
				}else {
					$sql=sprintf("delete from `Order Transaction Deal Bridge` where `Order Transaction Fact Key`=%d and `Fraction Discount`>0", $otf_key);
					//print $sql;
					mysql_query($sql);
				}
			}



			$sql=sprintf("insert into `Order Transaction Deal Bridge` (`Order Transaction Fact Key`,`Order Key`,`Product Key`,`Product ID`,`Product Family Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`,`Bunus Quantity`) values
			(%d,%d,%d,%d,%d,%d,%d,%d,%s,%f,%f,0)",
				$otf_key,
				$this->id,

				$allowance_data['Product Key'],
				$allowance_data['Product ID'],
				$allowance_data['Product Family Key'],
				$allowance_data['Deal Campaign Key'],
				$allowance_data['Deal Key'],
				$allowance_data['Deal Component Key'],

				prepare_mysql($allowance_data['Deal Info']),
				$allowance_data['Order Transaction Gross Amount']*$allowance_data['Percentage Off'],
				$allowance_data['Percentage Off']
			);



			mysql_query($sql);





			$sql=sprintf('update `Order Transaction Fact`   set  `Order Transaction Total Discount Amount`=`Order Transaction Gross Amount`*%f where `Order Transaction Fact Key`=%d '
				, $allowance_data['Percentage Off']
				, $otf_key
			);
			// print $sql;
			mysql_query($sql);

			$sql=sprintf('update `Order Transaction Fact` OTF  set  `Order Transaction Amount`=`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount` where `Order Transaction Fact Key`=%d '
				, $otf_key
			);

			mysql_query($sql);


		}

		//print_r($this->allowance);
		foreach ($this->allowance['No Item Transaction'] as $type=>$allowance_data) {



			switch ($type) {
			case 'Amount Off':



				$this->update(array('Order Deal Amount Off'=>$allowance_data['Amount Off']));


				$this->amount_off_allowance_data=$allowance_data;


				break;
			case 'Charge':
				//print_r($allowance_data);

				if ($type=='Charge')$_type='Charges';
				else
					$_type=$type;

				$sql=sprintf('select *,`Order No Product Transaction Fact Key`,`Transaction Net Amount` from  `Order No Product Transaction Fact` OTF  where `Order Key`=%d and `Transaction Type`=%s '
					, $this->id
					, prepare_mysql($_type)
				);

				$res=mysql_query($sql);
				while ($row=mysql_fetch_assoc($res)) {
					//print_r($row);
					$sql=sprintf("insert into `Order No Product Transaction Deal Bridge` (`Order No Product Transaction Fact Key`,`Order Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`)
					values (%d,%d,%d,%d,%d,%s,%f,%f)"
						, $row['Order No Product Transaction Fact Key']
						, $this->id


						, $allowance_data['Deal Campaign Key']
						, $allowance_data['Deal Key']
						, $allowance_data['Deal Component Key']

						, prepare_mysql($allowance_data['Deal Info'])
						, $row['Transaction Gross Amount']*$allowance_data['Percentage Off']
						, $allowance_data['Percentage Off']
					);
					mysql_query($sql);
					// print "$sql\n";
				}
				break;
			case 'Shipping':
				//print_r($allowance_data);

				if ($type=='Shipping')$_type='Shipping';
				else
					$_type=$type;

				$sql=sprintf('select *,`Order No Product Transaction Fact Key`,`Transaction Net Amount` from  `Order No Product Transaction Fact` OTF  where `Order Key`=%d and `Transaction Type`=%s '
					, $this->id
					, prepare_mysql($_type)
				);

				$res=mysql_query($sql);
				while ($row=mysql_fetch_assoc($res)) {
					//print_r($row);
					$sql=sprintf("insert into `Order No Product Transaction Deal Bridge` (`Order No Product Transaction Fact Key`,`Order Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,`Amount Discount`,`Fraction Discount`)
					values (%d,%d,%d,%d,%d,%s,%f,%f)"
						, $row['Order No Product Transaction Fact Key']
						, $this->id


						, $allowance_data['Deal Campaign Key']
						, $allowance_data['Deal Key']
						, $allowance_data['Deal Component Key']

						, prepare_mysql($allowance_data['Deal Info'])
						, $row['Transaction Gross Amount']*$allowance_data['Percentage Off']
						, $allowance_data['Percentage Off']
					);
					mysql_query($sql);
					// print "$sql\n";
				}
				break;
			}


		}

		foreach ($this->allowance['Get Free'] as $type=>$allowance_data) {
			if (in_array($this->data['Order Current Dispatch State'], array('Ready to Pick', 'Picking & Packing', 'Packed', 'Packed Done', 'Packing')) ) {
				$dispatching_state='Ready to Pick';
			}else {

				$dispatching_state='In Process';
			}

			$payment_state='Waiting Payment';


			$data=array(
				'date'=>gmdate('Y-m-d H:i:s'),
				'Product Key'=>$allowance_data['Product Key'],
				'Metadata'=>'',
				'qty'=>0,
				'bonus qty'=>$allowance_data['Get Free'],
				'Current Dispatching State'=>$dispatching_state,
				'Current Payment State'=>$payment_state
			);

			$this->skip_update_after_individual_transaction=true;

			$transaction_data=$this->add_order_transaction($data);
			$this->skip_update_after_individual_transaction=false;

			$sql=sprintf("insert into `Order Meta Transaction Deal Dimension` (`Order Meta Transaction Deal Type`,`Order Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,
				`Amount Discount`,`Fraction Discount`,`Bonus Quantity`,
				`Bonus Product Key`,`Bonus Product ID`,`Bonus Product Family Key`,`Bonus Order Transaction Fact Key`
				)
			values (%s,%d, %d,%d,%d,%s,%f,%f,%f,%d,%d,%d,%d)  "
				, prepare_mysql('Order Get Free')
				, $this->id


				, $allowance_data['Deal Campaign Key']
				, $allowance_data['Deal Key']
				, $allowance_data['Deal Component Key']
				, prepare_mysql($allowance_data['Deal Info'])
				, 0
				, 0
				, $allowance_data['Get Free']
				, $allowance_data['Product Key']
				, $allowance_data['Product ID']
				, $allowance_data['Product Family Key']
				, $transaction_data['otf_key']

			);
			mysql_query($sql);


		}

		foreach ($this->allowance['Order Get Free'] as $type=>$allowance_data) {


			if (in_array($this->data['Order Current Dispatch State'], array('Ready to Pick', 'Picking & Packing', 'Packed', 'Packed Done', 'Packing')) ) {
				$dispatching_state='Ready to Pick';
			}else {

				$dispatching_state='In Process';
			}

			$payment_state='Waiting Payment';


			$data=array(
				'date'=>gmdate('Y-m-d H:i:s'),
				'Product Key'=>$allowance_data['Product Key'],
				'Metadata'=>'',
				'qty'=>0,
				'bonus qty'=>$allowance_data['Get Free'],
				'Current Dispatching State'=>$dispatching_state,
				'Current Payment State'=>$payment_state
			);

			$this->skip_update_after_individual_transaction=true;

			$transaction_data=$this->add_order_transaction($data);
			$this->skip_update_after_individual_transaction=false;

			$sql=sprintf("select `Order Meta Transaction Deal Key` from `Order Meta Transaction Deal Dimension`  where `Order Key`=%d and `Deal Component Key`=%d",
				$this->id,
				$allowance_data['Deal Component Key']
			);
			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {
				$sql=sprintf("update  `Order Meta Transaction Deal Dimension`  set `Bonus Quantity`=%f,`Bonus Product Key`=%d,`Bonus Product ID`=%d ,`Bonus Product Family Key`=%d ,`Bonus Order Transaction Fact Key`=%d where `Order Meta Transaction Deal Key`=%d",

					$allowance_data['Get Free'],
					$allowance_data['Product Key'],
					$allowance_data['Product ID'],
					$allowance_data['Product Family Key'],
					$transaction_data['otf_key'],
					$row['Order Meta Transaction Deal Key']
				);
				mysql_query($sql);
			}
			else {

				$sql=sprintf("insert into `Order Meta Transaction Deal Dimension` (`Order Meta Transaction Deal Type`,`Order Key`,`Deal Campaign Key`,`Deal Key`,`Deal Component Key`,`Deal Info`,
				`Amount Discount`,`Fraction Discount`,`Bonus Quantity`,
				`Bonus Product Key`,`Bonus Product ID`,`Bonus Product Family Key`,`Bonus Order Transaction Fact Key`
				)
			values (%s,%d, %d,%d,%d,%s,%f,%f,%f,%d,%d,%d,%d)  "
					, prepare_mysql('Order Get Free')
					, $this->id


					, $allowance_data['Deal Campaign Key']
					, $allowance_data['Deal Key']
					, $allowance_data['Deal Component Key']
					, prepare_mysql($allowance_data['Deal Info'])
					, 0
					, 0
					, $allowance_data['Get Free']
					, $allowance_data['Product Key']
					, $allowance_data['Product ID']
					, $allowance_data['Product Family Key']
					, $transaction_data['otf_key']

				);
				mysql_query($sql);


			}











		}


		$sql=sprintf("select * from `Order No Product Transaction Deal Bridge` B left join `Order No Product Transaction Fact`OTF on (OTF.`Order No Product Transaction Fact Key`=B.`Order No Product Transaction Fact Key`)  where B.`Order Key`=%d  ", $this->id);
		$res=mysql_query($sql);
		//print $sql;
		while ($row=mysql_fetch_assoc($res)) {
			if ( $row['Fraction Discount']>0  ) {
				$sql=sprintf('update `Order No Product Transaction Fact` OTF  set `Transaction Total Discount Amount`=%.2f ,`Transaction Net Amount`=%.2f,`Transaction Tax Amount`=%.2f  where `Order No Product Transaction Fact Key`=%d '
					, $row['Amount Discount']
					, $row['Transaction Net Amount']*(1-$row['Fraction Discount'])
					, $row['Transaction Tax Amount']*(1-$row['Fraction Discount'])

					, $row['Order No Product Transaction Fact Key']
				);
				// print "$sql\n";
				mysql_query($sql);



			}




		}



	}


	function has_deal_with_bonus() {
		$has_deal_with_bonus=false;
		$sql=sprintf("select  count(*) as num  from `Order Meta Transaction Deal Dimension` where  `Order Key`=%d and `Order Meta Transaction Deal Type`='Order Get Free'  ",
			$this->id
		);
		//print $sql;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$has_deal_with_bonus=$row['num'];
		}
		return $has_deal_with_bonus;

	}


	function get_deal_bonus_items() {
		$deal_bonus_items=array();
		$sql=sprintf("select  `Deal Info`,`Bonus Product ID`,`Deal Component Allowance Target Key`,B.`Deal Component Key`,`Deal Component Allowance`,`Deal Component Allowance Target`,`Deal Component Allowance Target` from `Order Meta Transaction Deal Dimension` B left join `Deal Component Dimension` DC  on (DC.`Deal Component Key`=B.`Deal Component Key`) where  `Order Key`=%d and `Order Meta Transaction Deal Type`='Order Get Free'   ",
			$this->id
		);
		//print $sql;

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$deal_bonus_items[$row['Deal Component Key']]=array();

			if ($row['Deal Component Allowance Target']=='Family') {

				$family_key=$row['Deal Component Allowance Target Key'];
				$sql=sprintf("select `Product Family Key`,`Product Current Key`,`Product Code`,`Product ID`,`Product Name` from `Product Dimension` where `Product Main Type`='Sale' and `Product Family Key`=%d order by `Product Code File As`",
					$family_key

				);

				$res2=mysql_query($sql);
				$items=array();
				while ($row2=mysql_fetch_assoc($res2)) {

					$items[]=array(

						'pid'=>$row2['Product ID'],
						'product_key'=>$row2['Product Current Key'],
						'family_key'=>$row2['Product Family Key'],
						'code'=>$row2['Product Code'],
						'name'=>$row2['Product Name'],
						'selected'=>($row2['Product ID']==$row['Bonus Product ID']?true:false),
						'deal_info'=>$row['Deal Info'],

					);

				}

				$deal_bonus_items[$row['Deal Component Key']]=array(
					'type'=>'choose_from_family',
					'items'=>$items
				);

			}
			elseif ($row['Deal Component Allowance Target']=='Product') {

				$product_pid=$row['Deal Component Allowance Target Key'];
				$sql=sprintf("select `Product Family Key`,`Product Current Key`,`Product Code`,`Product ID`,`Product Name` from `Product Dimension` where  `Product ID`=%d ",
					$product_pid

				);

				$res2=mysql_query($sql);
				if ($row2=mysql_fetch_assoc($res2)) {

					$deal_bonus_items[$row['Deal Component Key']]=array(
						'type'=>'product',
						'item'=>array(
							'pid'=>$row2['Product ID'],
							'product_key'=>$row2['Product Current Key'],
							'family_key'=>$row2['Product Family Key'],
							'code'=>$row2['Product Code'],
							'name'=>$row2['Product Name'],
							'deal_info'=>$row['Deal Info'],
						)
					);

				}


			}


		}
		return $deal_bonus_items;
	}


	function get_allowances_from_order_trigger($no_items=false) {



		$deals_component_data=array();

		if ($no_items) {
			$where=sprintf("and `Deal Component Allowance Target Type`='No Items'");
		}else {
			$where=sprintf("and `Deal Component Allowance Target Type`='Items'");

		}

		$sql=sprintf("select * from `Deal Component Dimension` left join `Deal Dimension` D on (D.`Deal Key`=`Deal Component Deal Key`)  where `Deal Component Trigger`='Order'  and `Deal Component Status`='Active'  and `Deal Component Store Key`=%d  and `Deal Component Terms Type` not in ('Voucher AND Order Interval','Voucher AND Order Number','Voucher AND Amount','Voucher')  $where",
			$this->data['Order Store Key']
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$deals_component_data[$row['Deal Component Key']]=$row;
		}


		foreach ($deals_component_data as $deal_component_data ) {

			$terms_ok=false;
			$this->deals['Order']['Deal']=true;

			if (isset($this->deals['Order']['Deal Multiplicity'])) {
				$this->deals['Order']['Deal Multiplicity']++;
			}else {
				$this->deals['Order']['Deal Multiplicity']=1;
			}

			if (isset($this->deals['Order']['Terms Multiplicity'])) {
				$this->deals['Order']['Terms Multiplicity']++;
			}else {
				$this->deals['Order']['Terms Multiplicity']=1;
			}


			$this->test_deal_terms($deal_component_data);




		}

		$deals_component_data=array();
		$sql=sprintf("select * from `Deal Component Dimension` DC  left join `Deal Dimension` D on (D.`Deal Key`=`Deal Component Deal Key`)  left join `Voucher Order Bridge` V on (V.`Deal Key`=DC.`Deal Component Deal Key`)   where   `Deal Component Status`='Active'  and `Order Key`=%d    $where",
			$this->id
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$deals_component_data[$row['Deal Component Key']]=$row;
		}


		foreach ($deals_component_data as $deal_component_data ) {

			$terms_ok=false;
			$this->deals['Order']['Deal']=true;

			if (isset($this->deals['Order']['Deal Multiplicity'])) {
				$this->deals['Order']['Deal Multiplicity']++;
			}else {
				$this->deals['Order']['Deal Multiplicity']=1;
			}

			if (isset($this->deals['Order']['Terms Multiplicity'])) {
				$this->deals['Order']['Terms Multiplicity']++;
			}else {
				$this->deals['Order']['Terms Multiplicity']=1;
			}


			$this->test_deal_terms($deal_component_data);




		}




	}


	function get_allowances_from_family_trigger() {

		$sql=sprintf("select `Product Family Key` from `Order Transaction Fact` where `Order Key`=%d group by `Product Family Key`",
			$this->id);
		$res_lines=mysql_query($sql);
		while ($row_lines=mysql_fetch_array($res_lines)) {

			$family_key=$row_lines['Product Family Key'];



			$deals_component_data=array();
			$discounts=0;

			$sql=sprintf("select * from `Deal Component Dimension`  left join `Deal Dimension` D on (D.`Deal Key`=`Deal Component Deal Key`)  where `Deal Component Trigger`='Family' and `Deal Component Trigger Key` =%d  and `Deal Component Status`='Active' ",
				$family_key
			);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$deals_component_data[$row['Deal Component Key']]=$row;
			}

			foreach ($deals_component_data as $deal_component_data ) {



				$terms_ok=false;
				$this->deals['Family']['Deal']=true;
				if (isset($this->deals['Family']['Deal Multiplicity'])) {
					$this->deals['Family']['Deal Multiplicity']++;
				}else {
					$this->deals['Family']['Deal Multiplicity']=1;
				}

				if (isset($this->deals['Family']['Terms Multiplicity'])) {
					$this->deals['Family']['Terms Multiplicity']++;
				}else {
					$this->deals['Family']['Terms Multiplicity']=1;
				}


				$this->test_deal_terms($deal_component_data);



			}



		}
	}


	function get_allowances_from_product_trigger() {

		$sql=sprintf("select `Product ID` from `Order Transaction Fact` where `Order Key`=%d group by `Product ID`",
			$this->id);
		$res_lines=mysql_query($sql);
		while ($row_lines=mysql_fetch_array($res_lines)) {


			$deals_component_data=array();
			$discounts=0;

			$sql=sprintf("select * from `Deal Component Dimension`  left join `Deal Dimension` D on (D.`Deal Key`=`Deal Component Deal Key`)  where `Deal Component Trigger`='Product' and `Deal Component Trigger Key` =%d  and `Deal Component Status`='Active' ",
				$row_lines['Product ID']
			);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$deals_component_data[$row['Deal Component Key']]=$row;
			}

			foreach ($deals_component_data as $deal_component_data ) {

				$terms_ok=false;
				$this->deals['Product']['Deal']=true;
				if (isset($this->deals['Product']['Deal Multiplicity'])) {
					$this->deals['Product']['Deal Multiplicity']++;
				}else {
					$this->deals['Product']['Deal Multiplicity']=1;
				}

				if (isset($this->deals['Product']['Terms Multiplicity'])) {
					$this->deals['Product']['Terms Multiplicity']++;
				}else {
					$this->deals['Product']['Terms Multiplicity']=1;
				}

				$this->test_deal_terms($deal_component_data);



			}



		}



	}


	function get_allowances_from_customer_trigger($no_items=false) {


		$deals_component_data=array();
		$discounts=0;

		if ($no_items) {
			$where=sprintf("and `Deal Component Allowance Target Type`='No Items'");
		}else {
			$where=sprintf("and `Deal Component Allowance Target Type`='Items'");

		}


		$sql=sprintf("select * from `Deal Component Dimension` where `Deal Component Trigger`='Customer' and `Deal Component Trigger Key` =%d  and `Deal Component Status`='Active' $where",
			$this->data['Order Customer Key']
		);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$deals_component_data[$row['Deal Component Key']]=$row;
		}

		$sql=sprintf("select * from `Deal Component Dimension`  left join `Deal Dimension` D on (D.`Deal Key`=`Deal Component Deal Key`)  left join  `List Customer Bridge` on (`List Key`=`Deal Component Trigger Key`) where `Deal Component Trigger`='Customer List' and `Customer Key` =%d  and `Deal Component Status`='Active' $where",
			$this->data['Order Customer Key']
		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$deals_component_data[$row['Deal Component Key']]=$row;
		}

		foreach ($deals_component_data as $deal_component_data ) {

			$terms_ok=false;
			$this->deals['Customer']['Deal']=true;
			if (isset($this->deals['Customer']['Deal Multiplicity'])) {
				$this->deals['Customer']['Deal Multiplicity']++;
			}else {
				$this->deals['Customer']['Deal Multiplicity']=1;
			}

			if (isset($this->deals['Customer']['Terms Multiplicity'])) {
				$this->deals['Customer']['Terms Multiplicity']++;
			}else {
				$this->deals['Customer']['Terms Multiplicity']=1;
			}



			$this->test_deal_terms($deal_component_data);


		}



	}


	function get_allowances_from_department_trigger() {

		$sql=sprintf("select `Product Department Key` from `Order Transaction Fact` where `Order Key`=%d group by `Product Department Key`",
			$this->id);
		$res_lines=mysql_query($sql);
		while ($row_lines=mysql_fetch_array($res_lines)) {

			$department_key=$row_lines['Product Department Key'];



			$deals_component_data=array();
			$discounts=0;

			$sql=sprintf("select * from `Deal Component Dimension`  left join `Deal Dimension` D on (D.`Deal Key`=`Deal Component Deal Key`)  where `Deal Component Trigger`='Department' and `Deal Component Trigger Key` =%d  and `Deal Component Status`='Active' ",
				$department_key
			);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				$deals_component_data[$row['Deal Component Key']]=$row;
			}



			foreach ($deals_component_data as $deal_component_data ) {

				$terms_ok=false;
				$this->deals['Department']['Deal']=true;
				if (isset($this->deals['Department']['Deal Multiplicity'])) {
					$this->deals['Department']['Deal Multiplicity']++;
				}else {
					$this->deals['Department']['Deal Multiplicity']=1;
				}

				if (isset($this->deals['Department']['Terms Multiplicity'])) {
					$this->deals['Department']['Terms Multiplicity']++;
				}else {
					$this->deals['Department']['Terms Multiplicity']=1;
				}

				$this->test_deal_terms($deal_component_data);



			}




		}
	}


	function test_deal_terms($deal_component_data) {



		switch ($deal_component_data['Deal Component Terms Type']) {

		case('Order Number'):

			$order_number_term=$deal_component_data['Deal Component Terms']-1;




			$sql=sprintf("select count(*) as num from `Order Dimension` where `Order Customer Key`=%d and `Order Key`!=%d and  `Order Current Dispatch State` not in ('Cancelled','Suspended','Cancelled by Customer') ",
				$this->data['Order Customer Key'],
				$this->id
			);

			$res3=mysql_query($sql);
			if ($__row=mysql_fetch_assoc($res3)) {


				if ($__row['num']==$order_number_term) {
					$this->deals['Order']['Terms']=true;
					$this->get_allowances_from_deal_component_data($deal_component_data);
				}
			}




			break;

		case('Voucher AND Order Number'):
			$terms=preg_split('/;/', $deal_component_data['Deal Component Terms']);

			$order_number_term=$terms[1]-1;


			$sql=sprintf("select count(*) as num from `Voucher Order Bridge` where `Deal Key`=%d and `Order Key`=%d ",
				$deal_component_data['Deal Component Deal Key'],
				$this->id
			);

			$res2=mysql_query($sql);
			if ($_row=mysql_fetch_assoc($res2)) {


				if ($_row['num']>0) {

					$sql=sprintf("select count(*) as num from `Order Dimension` where `Order Customer Key`=%d and `Order Key`!=%d and  `Order Current Dispatch State` not in ('Cancelled','Suspended','Cancelled by Customer') ",
						$this->data['Order Customer Key'],
						$this->id
					);

					$res3=mysql_query($sql);
					if ($__row=mysql_fetch_assoc($res3)) {


						if ($__row['num']==$order_number_term) {
							$this->deals['Order']['Terms']=true;
							$this->get_allowances_from_deal_component_data($deal_component_data);
						}
					}


				}
			}
			break;


		case('Voucher AND Amount'):

			$sql=sprintf("select count(*) as num from `Voucher Order Bridge` where `Deal Key`=%d and `Order Key`=%d ",
				$deal_component_data['Deal Component Deal Key'],
				$this->id
			);
			$res2=mysql_query($sql);
			if ($_row=mysql_fetch_assoc($res2)) {
				if ($_row['num']>0) {
					$terms=preg_split('/;/', $deal_component_data['Deal Component Terms']);
					$amount_term=$terms[1];
					$amount_type=$terms[2];

					if ($this->data[$amount_type]>=$amount_term) {
						$this->deals['Order']['Terms']=true;
						$this->get_allowances_from_deal_component_data($deal_component_data);
					}
				}
			}
			break;


		case('Voucher'):

			$sql=sprintf("select count(*) as num from `Voucher Order Bridge` where `Deal Key`=%d and `Order Key`=%d ",
				$deal_component_data['Deal Component Deal Key'],
				$this->id
			);

			$res2=mysql_query($sql);
			if ($_row=mysql_fetch_assoc($res2)) {


				if ($_row['num']>0) {

					$this->deals['Order']['Terms']=true;
					$this->get_allowances_from_deal_component_data($deal_component_data);

				}
			}
			break;


		case('Order Interval'):

			$sql=sprintf("select count(*) as num from `Order Dimension` where `Order Customer Key`=%d and `Order Key`!=%d and `Order Dispatched Date`>=%s and `Order Current Dispatch State`='Dispatched' and `Order Invoiced`='Yes'",
				$this->data['Order Customer Key'],
				$this->id,
				prepare_mysql(date('Y-m-d', strtotime($this->data['Order Date']." -".$deal_component_data['Deal Component Terms'])).' 00:00:00')
			);
			//print $sql;
			$res2=mysql_query($sql);
			if ($_row=mysql_fetch_assoc($res2)) {


				if ($_row['num']>0) {
					$this->deals['Order']['Terms']=true;
					// print_r($deal_component_data);
					$this->get_allowances_from_deal_component_data($deal_component_data);
				}
			}
			break;

		case('Amount'):
			$terms=preg_split('/;/', $deal_component_data['Deal Component Terms']);
			$amount_term=$terms[0];
			$amount_type=$terms[1];

			if ($this->data[$amount_type]>=$amount_term) {
				$this->get_allowances_from_deal_component_data($deal_component_data);
			}


			break;
		case('Amount AND Order Interval'):

			$terms=preg_split('/;/', $deal_component_data['Deal Component Terms']);
			$amount_term=$terms[0];
			$amount_type=$terms[1];
			$interval_term=$terms[2];

			$interval_term_ok=false;
			$amount_term_ok=false;


			$deal_component_data['Deal Component Terms'];
			//print_r($terms);

			if ($this->data[$amount_type]>=$amount_term) {
				$amount_term_ok=true;

			}



			if ($amount_term_ok) {

				$sql=sprintf("select count(*) as num from `Order Dimension` where `Order Customer Key`=%d and `Order Key`!=%d and `Order Dispatched Date`>=%s and `Order Current Dispatch State`='Dispatched' and `Order Invoiced`='Yes'",
					$this->data['Order Customer Key'],
					$this->id,
					prepare_mysql(date('Y-m-d', strtotime($this->data['Order Date']." -".$interval_term)).' 00:00:00')
				);
				// print $deal_component_data['Deal Component Terms'];
				$res2=mysql_query($sql);
				if ($_row=mysql_fetch_assoc($res2)) {


					if ($_row['num']>0) {
						$interval_term_ok=true;
					}
				}
			}


			if ($amount_term_ok and $interval_term_ok) {


				$this->get_allowances_from_deal_component_data($deal_component_data);
			}
		case('Amount AND Order Number'):



			$terms=preg_split('/;/', $deal_component_data['Deal Component Terms']);



			$amount_term=$terms[0];
			$amount_type=$terms[1];

			$order_number_term=$terms[2]-1;

			$order_number_term_ok=false;
			$amount_term_ok=false;

			if ($this->data[$amount_type]>=$amount_term) {
				$amount_term_ok=true;

			}



			if ($amount_term_ok) {




				$sql=sprintf("select count(*) as num from `Order Dimension` where `Order Customer Key`=%d and `Order Key`!=%d and  `Order Current Dispatch State` not in ('Cancelled','Suspended','Cancelled by Customer') ",
					$this->data['Order Customer Key'],
					$this->id
				);

				$res2=mysql_query($sql);
				if ($_row=mysql_fetch_assoc($res2)) {


					if ($_row['num']==$order_number_term) {
						$order_number_term_ok=true;
					}
				}
			}


			if ($amount_term_ok and $order_number_term_ok) {


				$this->get_allowances_from_deal_component_data($deal_component_data);
			}



			break;


		case('Department Quantity Ordered'):

			$qty_department=0;
			$sql=sprintf('select sum(`Order Quantity`) as qty  from `Order Transaction Fact` OTF where `Order Key`=%d and `Product Department Key`=%d ',
				$this->id,
				$deal_component_data['Deal Component Allowance Target Key']
			);
			$res2=mysql_query($sql);
			if ($deal_component_data2=mysql_fetch_array($res2)) {
				$qty_department=$deal_component_data2['qty'];
			}
			if ($qty_department>=$deal_component_data['Deal Component Terms']) {
				$terms_ok=true;
				$this->deals['Department']['Terms']=true;
				$this->get_allowances_from_deal_component_data($deal_component_data);
			}



			break;
		case ('Department For Every Quantity Ordered'):


			$sql=sprintf('select `Order Quantity`as qty,`Product ID`   from `Order Transaction Fact` OTF where `Order Key`=%d and `Product Department Key`=%d ',
				$this->id,
				$deal_component_data['Deal Component Allowance Target Key']
			);

			$res2=mysql_query($sql);
			while ($deal_component_data2=mysql_fetch_array($res2)) {
				$qty=$deal_component_data2['qty'];

				if ($qty>=$deal_component_data['Deal Component Terms']) {
					$terms_ok=true;;
					$this->deals['Department']['Terms']=true;

					$deal_component_product_data=$deal_component_data;
					if ($deal_component_data['Deal Component Terms']!=0) {
						$deal_component_product_data['Deal Component Allowance']=$deal_component_product_data['Deal Component Allowance']*floor( $qty / $deal_component_product_data['Deal Component Terms']);
						$deal_component_product_data['Deal Component Allowance Type']='Get Free';
						$deal_component_product_data['Deal Component Allowance Target']='Product';
						$deal_component_product_data['Deal Component Allowance Target Key']=$deal_component_data2['Product ID'];
						$this->get_allowances_from_deal_component_data($deal_component_product_data);

					}
				}

			}


			break;


		case('Family Quantity Ordered'):
			$qty_family=0;
			$sql=sprintf('select sum(`Order Quantity`) as qty  from `Order Transaction Fact` OTF where `Order Key`=%d and `Product Family Key`=%d ',
				$this->id,
				$deal_component_data['Deal Component Allowance Target Key']
			);

			$res2=mysql_query($sql);
			if ($deal_component_data2=mysql_fetch_array($res2)) {
				$qty_family=$deal_component_data2['qty'];
			}
			if ($qty_family>=$deal_component_data['Deal Component Terms']) {
				$terms_ok=true;
				$this->deals['Family']['Terms']=true;
				$this->get_allowances_from_deal_component_data($deal_component_data);
			}



			break;




		case ('Family For Every Quantity Ordered'):
			$sql=sprintf('select `Order Quantity`as qty,`Product ID`   from `Order Transaction Fact` OTF where `Order Key`=%d and `Product Family Key`=%d ',
				$this->id,
				$deal_component_data['Deal Component Allowance Target Key']
			);


			$res2=mysql_query($sql);
			while ($deal_component_data2=mysql_fetch_array($res2)) {
				$qty=$deal_component_data2['qty'];

				if ($qty>=$deal_component_data['Deal Component Terms']) {
					$terms_ok=true;;
					$this->deals['Family']['Terms']=true;

					$deal_component_product_data=$deal_component_data;
					if ($deal_component_data['Deal Component Terms']!=0) {
						$deal_component_product_data['Deal Component Allowance']=$deal_component_product_data['Deal Component Allowance']*floor( $qty / $deal_component_product_data['Deal Component Terms']);
						$deal_component_product_data['Deal Component Allowance Type']='Get Free';
						$deal_component_product_data['Deal Component Allowance Target']='Product';
						$deal_component_product_data['Deal Component Allowance Target Key']=$deal_component_data2['Product ID'];
						$this->get_allowances_from_deal_component_data($deal_component_product_data);

					}
				}

			}
			break;

		case ('Family For Every Quantity Any Product Ordered'):
			$sql=sprintf('select sum(`Order Quantity`) as qty from `Order Transaction Fact` OTF where `Order Key`=%d and `Product Family Key`=%d ',
				$this->id,
				$deal_component_data['Deal Component Allowance Target Key']
			);


			$res2=mysql_query($sql);
			while ($deal_component_data2=mysql_fetch_array($res2)) {

				$qty=$deal_component_data2['qty'];

				if ($qty>=$deal_component_data['Deal Component Terms']) {
					$terms_ok=true;;
					$this->deals['Family']['Terms']=true;


					$deal_component_data['Deal Component Allowance']=$deal_component_data['Deal Component Allowance']*floor( $qty / $deal_component_data['Deal Component Terms']);

					$this->get_allowances_from_deal_component_data($deal_component_data);

				}

			}
			break;

		case('Product Quantity Ordered'):
			$qty=0;
			$sql=sprintf('select sum(`Order Quantity`) as qty  from `Order Transaction Fact` OTF where `Order Key`=%d and `Product ID`=%d ',
				$this->id,
				$deal_component_data['Deal Component Allowance Target Key']
			);

			$res2=mysql_query($sql);
			if ($deal_component_data2=mysql_fetch_assoc($res2)) {



				$qty=$deal_component_data2['qty'];
			}

			if ($qty>=$deal_component_data['Deal Component Terms']) {
				$terms_ok=true;
				// print "xxxx\n";
				$this->get_allowances_from_deal_component_data($deal_component_data);
				//  print "----\n";
			}

			break;

		case('Product For Every Quantity Ordered'):

			$qty_product=0;
			$sql=sprintf('select sum(`Order Quantity`) as qty  from `Order Transaction Fact` OTF where `Order Key`=%d and `Product ID`=%d ',
				$this->id,
				$deal_component_data['Deal Component Allowance Target Key']
			);

			$res2=mysql_query($sql);
			if ($deal_component_data2=mysql_fetch_array($res2)) {
				if ($deal_component_data2['qty']=='')
					$qty_product=0;
				else
					$qty_product=$deal_component_data2['qty'];
			}


			//print_r($deal_component_data);

			//print "** $qty_product  -> ".$deal_component_data['Deal Component Terms']."   **\n";


			if ($qty_product>0 and $qty_product>=$deal_component_data['Deal Component Terms']) {
				$terms_ok=true;;
				$this->deals['Family']['Terms']=true;

				// i dont underestad below thing maybe it is wrong
				if ($deal_component_data['Deal Component Terms']!=0) {
					$deal_component_data['Deal Component Allowance']=$deal_component_data['Deal Component Allowance']*floor( $qty_product / $deal_component_data['Deal Component Terms']);
				}

				$this->get_allowances_from_deal_component_data($deal_component_data);
			}



			break;

		case('Every Order'):
			$terms_ok=true;
			$this->deals['Customer']['Terms']=true;
			$this->get_allowances_from_deal_component_data($deal_component_data);
			break;


		}

	}


	function get_allowances_from_deal_component_data($deal_component_data) {


		if (isset($deal_component_data['Deal Label'])) {

			$deal_info=sprintf("%s: %s, %s",
				($deal_component_data['Deal Label']==''?_('Offer'):$deal_component_data['Deal Label']),
				(isset($deal_component_data['Deal XHTML Terms Description Label'])?$deal_component_data['Deal XHTML Terms Description Label']:''),
				$deal_component_data['Deal Component XHTML Allowance Description Label']

			);
		}else {
			$deal_info='Discount';
		}

		switch ($deal_component_data['Deal Component Allowance Type']) {

		case('Amount Off'):


			if (isset($this->allowance['No Item Transaction']['Amount Off'])) {
				if ($this->allowance['No Item Transaction']['Amount Off']['Amount Off']<$deal_component_data['Deal Component Allowance']) {

					$this->allowance['No Item Transaction']['Amount Off']=array(
						'Amount Off'=>$deal_component_data['Deal Component Allowance'],
						'Deal Campaign Key'=>$deal_component_data['Deal Component Campaign Key'],
						'Deal Component Key'=>$deal_component_data['Deal Component Key'],
						'Deal Key'=>$deal_component_data['Deal Component Deal Key'],
						'Deal Info'=>$deal_info
					);

				}
			}else {

				$this->allowance['No Item Transaction']['Amount Off']=array(
					'Amount Off'=>$deal_component_data['Deal Component Allowance'],
					'Deal Campaign Key'=>$deal_component_data['Deal Component Campaign Key'],
					'Deal Component Key'=>$deal_component_data['Deal Component Key'],
					'Deal Key'=>$deal_component_data['Deal Component Deal Key'],
					'Deal Info'=>$deal_info
				);
			}
			break;
		case('Percentage Off'):
			switch ($deal_component_data['Deal Component Allowance Target']) {

			case('Order'):
				$where=sprintf("where `Order Key`=%d", $this->id);
				break;
			case('Department'):
				$where=sprintf("where `Order Key`=%d and `Product Department Key`=%d", $this->id, $deal_component_data['Deal Component Allowance Target Key']);
				break;
			case('Family'):
				$where=sprintf("where `Order Key`=%d and `Product Family Key`=%d", $this->id, $deal_component_data['Deal Component Allowance Target Key']);
				break;
			case('Product'):
				$where=sprintf("where `Order Key`=%d and `Product ID`=%d", $this->id, $deal_component_data['Deal Component Allowance Target Key']);
				break;
			default:
				$where=' where false';

			}
			$percentage=$deal_component_data['Deal Component Allowance'];


			$sql=sprintf("select `Product ID`,OTF.`Product Key`,`Order Transaction Fact Key`,`Product Family Key`,`Order Transaction Gross Amount` from  `Order Transaction Fact` OTF  $where");

			//print $sql;

			$res=mysql_query($sql);
			while ($row=mysql_fetch_array($res)) {
				$otf_key=$row['Order Transaction Fact Key'];
				if (isset($this->allowance['Percentage Off'][$otf_key])) {
					if ($this->allowance['Percentage Off'][$otf_key]['Percentage Off']<=$percentage) {
						$this->allowance['Percentage Off'][$otf_key]['Percentage Off']=$percentage;
						$this->allowance['Percentage Off'][$otf_key]['Deal Campaign Key']=$deal_component_data['Deal Component Campaign Key'];
						$this->allowance['Percentage Off'][$otf_key]['Deal Component Key']=$deal_component_data['Deal Component Key'];
						$this->allowance['Percentage Off'][$otf_key]['Deal Key']=$deal_component_data['Deal Component Deal Key'];
						$this->allowance['Percentage Off'][$otf_key]['Deal Info']=$deal_info;
					}
				}
				else {
					$this->allowance['Percentage Off'][$otf_key]=array(
						'Percentage Off'=>$percentage,
						'Deal Campaign Key'=>$deal_component_data['Deal Component Campaign Key'],
						'Deal Component Key'=>$deal_component_data['Deal Component Key'],
						'Deal Key'=>$deal_component_data['Deal Component Deal Key'],
						'Deal Info'=>$deal_info,
						'Product Key'=>$row['Product Key'],
						'Product ID'=>$row['Product ID'],
						'Product Family Key'=>$row['Product Family Key'],
						'Order Transaction Gross Amount'=>$row['Order Transaction Gross Amount']

					);
				}

			}


			//   print_r($this->allowance['Percentage Off']);


			break;


		case('Get Free'):
			switch ($deal_component_data['Deal Component Allowance Target']) {

			case('Charge'):
			case('Shipping'):
				$this->allowance['No Item Transaction'][$deal_component_data['Deal Component Allowance Target']]=array(
					'Percentage Off'=>1,
					'Deal Campaign Key'=>$deal_component_data['Deal Component Campaign Key'],
					'Deal Component Key'=>$deal_component_data['Deal Component Key'],
					'Deal Key'=>$deal_component_data['Deal Component Deal Key'],
					'Deal Info'=>$deal_info
				);

				break;



			case('Family'):



				$family_key=$deal_component_data['Deal Component Allowance Target Key'];
				$get_free_allowance=preg_split('/;/', $deal_component_data['Deal Component Allowance']);

				$sql=sprintf("select `Preference Metadata` from `Deal Component Customer Preference Bridge`  where `Deal Component Key`=%d and `Customer Key`=%d ",
					$deal_component_data['Deal Component Key'],
					$this->data['Order Customer Key']
				);
				$res=mysql_query($sql);
				if ($row=mysql_fetch_assoc($res)) {

					$product_code=$row['Preference Metadata'];

					$sql=sprintf("select `Product ID` from `Product Dimension` where `Product Store Key`=%s and `Product Code`=%s and `Product Main Type`='Sale'",
						$this->data['Order Store Key'],
						prepare_mysql($product_code)
					);
					$res2=mysql_query($sql);
					if ($row2=mysql_fetch_assoc($res2)) {
						$product_pid=$row2['Product ID'];

					}else {
						$product_pid=0;
					}

					if ($product_pid) {

						$sql=sprintf("select count(*) as num from `Product Dimension` where `Product Main Type`='Sale' and `Product Family Key`=%d and `Product ID`=%d",
							$family_key,
							$product_pid
						);

						$res2=mysql_query($sql);
						if ($row2=mysql_fetch_assoc($res2)) {
							if ($row2['num']==0) {
								$product_pid=$get_free_allowance[1];
								$sql=sprintf("delete from `Deal Component Customer Preference Bridge`  where `Deal Component Key`=%d and `Customer Key`=%d ",
									$deal_component_data['Deal Component Key'],
									$this->data['Order Customer Key']
								);
								mysql_query($sql);
							}

						}
					}
				}
				else {

					$sql=sprintf("select `Product ID` from `Product Dimension` where `Product Store Key`=%s and `Product Code`=%s and `Product Main Type`='Sale'",
						$this->data['Order Store Key'],
						prepare_mysql($get_free_allowance[1])
					);
					$res2=mysql_query($sql);
					if ($row2=mysql_fetch_assoc($res2)) {
						$product_pid=$row2['Product ID'];

					}else {
						$product_pid=0;
					}


				}


				$sql=sprintf("select count(*) as num from `Product Dimension` where `Product Main Type`='Sale' and `Product Family Key`=%d and `Product ID`=%d",
					$family_key,
					$product_pid
				);

				$res2=mysql_query($sql);
				if ($row2=mysql_fetch_assoc($res2)) {
					if ($row2['num']==0) {
						$product_pid=0;
					}


				}

				if ($deal_component_data['Deal Component Trigger']=='Order')
					$allowance_index='Order Get Free';
				else
					$allowance_index='Get Free';

				if (!$product_pid) {

					$this->allowance[$allowance_index][$product_pid]=array(
						'Product ID'=>0,
						'Product Key'=>0,
						'Product Family Key'=>0,
						'Get Free'=>0,
						'Deal Campaign Key'=>$deal_component_data['Deal Component Campaign Key'],
						'Deal Component Key'=>$deal_component_data['Deal Component Key'],
						'Deal Key'=>$deal_component_data['Deal Component Deal Key'],
						'Deal Info'=>$deal_info
					);
				}
				else {

					if (isset($this->allowance[$allowance_index][$product_pid])) {
						$this->allowance[$allowance_index][$product_pid]['Get Free']+=$get_free_allowance[0];
					} else {

						$product=new Product('id', $product_pid);


						$this->allowance[$allowance_index][$product_pid]=array(
							'Product ID'=>$product->id,
							'Product Key'=>$product->historic_id,
							'Product Family Key'=>$product->data['Product Family Key'],
							'Get Free'=>$get_free_allowance[0],
							'Deal Campaign Key'=>$deal_component_data['Deal Component Campaign Key'],
							'Deal Component Key'=>$deal_component_data['Deal Component Key'],
							'Deal Key'=>$deal_component_data['Deal Component Deal Key'],
							'Deal Info'=>$deal_info
						);
					}
				}




				break;

			case('Product'):
				$product_pid=$deal_component_data['Deal Component Allowance Target Key'];

				$product=new Product('id', $deal_component_data['Deal Component Allowance Target Key']);

				$get_free_allowance=$deal_component_data['Deal Component Allowance'];

				//print_r($deal_component_data);

				if (isset($this->allowance['Order Get Free'][$product_pid])) {
					$this->allowance['Order Get Free'][$product_pid]['Get Free']+=$get_free_allowance;
				} else {
					$this->allowance['Order Get Free'][$product_pid]=array(
						'Product ID'=>$product->id,
						'Product Key'=>$product->historic_id,
						'Product Family Key'=>$product->data['Product Family Key'],
						'Get Free'=>$get_free_allowance,
						'Deal Campaign Key'=>$deal_component_data['Deal Component Campaign Key'],
						'Deal Component Key'=>$deal_component_data['Deal Component Key'],
						'Deal Key'=>$deal_component_data['Deal Component Deal Key'],
						'Deal Info'=>$deal_info
					);
				}

				//print_r($this->allowance);
				break;
			}

			break;


		case('Get Cheapest Free'):
			//print_r($deal_component_data);

			switch ($deal_component_data['Deal Component Allowance Target']) {
			case 'Department':
				$where=sprintf(' and `Product Department Key`=%d', $deal_component_data['Deal Component Allowance Target Key']);
				break;
			case 'Family':
				$where=sprintf(' and `Product Family Key`=%d', $deal_component_data['Deal Component Allowance Target Key']);
				break;
			default:
				$where=' and false';
				break;
			}

			$number_free_outers=$deal_component_data['Deal Component Allowance'];
			$sql=sprintf('select `Order Transaction Fact Key`,`Order Quantity`,OTF.`Product Key`,OTF.`Product ID`,`Product Family Key`,`Order Transaction Gross Amount` from `Order Transaction Fact` OTF left join `Product History Dimension` P on (OTF.`Product Key`=P.`Product Key`) where `Order Key`=%d %s order by `Product History Price`,`Order Quantity`',
				$this->id,
				$where
			);
			// print "$sql\n";
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				// print_r($row);
				if ($row['Order Quantity']<=$number_free_outers) {
					$percentage=1;

				}else {
					$percentage=$number_free_outers/$row['Order Quantity'];
				}

				$number_free_outers-=$row['Order Quantity'];


				$otf_key=$row['Order Transaction Fact Key'];
				if (isset($this->allowance['Percentage Off'][$otf_key])) {
					if ($this->allowance['Percentage Off'][$otf_key]['Percentage Off']<=$percentage) {
						$this->allowance['Percentage Off'][$otf_key]['Percentage Off']=$percentage;
						$this->allowance['Percentage Off'][$otf_key]['Deal Campaign Key']=$deal_component_data['Deal Component Campaign Key'];
						$this->allowance['Percentage Off'][$otf_key]['Deal Component Key']=$deal_component_data['Deal Component Key'];
						$this->allowance['Percentage Off'][$otf_key]['Deal Key']=$deal_component_data['Deal Component Deal Key'];
						$this->allowance['Percentage Off'][$otf_key]['Deal Info']=$deal_info;
					}
				}
				else {
					$this->allowance['Percentage Off'][$otf_key]=array(
						'Percentage Off'=>$percentage,
						'Deal Campaign Key'=>$deal_component_data['Deal Component Campaign Key'],
						'Deal Component Key'=>$deal_component_data['Deal Component Key'],
						'Deal Key'=>$deal_component_data['Deal Component Deal Key'],
						'Deal Info'=>$deal_info,
						'Product Key'=>$row['Product Key'],
						'Product ID'=>$row['Product ID'],
						'Product Family Key'=>$row['Product Family Key'],
						'Order Transaction Gross Amount'=>$row['Order Transaction Gross Amount']

					);
				}

				if ($number_free_outers<=0) {
					break;
				}

			}


			break;

		}



	}


	function get_discounted_products() {
		$sql=sprintf('select  `Product Key` from   `Order Transaction Deal Bridge`   where `Order Key`=%d  group by `Product Key` '
			, $this->id
		);
		//print "$sql\n";
		$res=mysql_query($sql);
		$disconted_products=array();
		while ($row=mysql_fetch_array($res)) {
			$disconted_products[$row['Product Key']]=$row['Product Key'];
		}
		return $disconted_products;

	}


	function update_deal_bridge() {
		$sql=sprintf("delete from `Order Deal Bridge` where `Order Key`=%d",
			$this->id);
		mysql_query($sql);

		$sql=sprintf("select `Deal Campaign Key`,`Deal Component Key`, `Deal Key` from  `Order Transaction Deal Bridge`  where`Order Key`=%d",
			$this->id);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$sql=sprintf("insert into `Order Deal Bridge` values(%d,%d,%d,%d,'Yes','Yes') ON DUPLICATE KEY UPDATE `Used`='Yes'",
				$this->id,
				$row['Deal Campaign Key'],
				$row['Deal Key'],
				$row['Deal Component Key']
			);
			mysql_query($sql);
		}

		$sql=sprintf("select `Deal Campaign Key`,`Deal Component Key`, `Deal Key` from  `Order No Product Transaction Deal Bridge`  where`Order Key`=%d",
			$this->id);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$sql=sprintf("insert into `Order Deal Bridge` values(%d,%d,%d,%d,'Yes','Yes') ON DUPLICATE KEY UPDATE `Used`='Yes'",
				$this->id,
				$row['Deal Campaign Key'],
				$row['Deal Key'],
				$row['Deal Component Key']
			);
			mysql_query($sql);
		}



		if ($this->amount_off_allowance_data) {



			$sql=sprintf("insert into `Order Deal Bridge` values(%d,%d,%d,%d,'Yes','Yes') ON DUPLICATE KEY UPDATE `Used`='Yes'",
				$this->id,
				$this->amount_off_allowance_data['Deal Campaign Key'],
				$this->amount_off_allowance_data['Deal Key'],
				$this->amount_off_allowance_data['Deal Component Key']
			);
			mysql_query($sql);

		}


	}


	function update_deal_bridge_from_assets_deals() {


		$sql=sprintf("select B.`Deal Key` from  `Order Deal Bridge` B  left join `Deal Dimension` D on (D.`Deal Key`=B.`Deal Key`) where `Deal Trigger` in ('Department','Family','Product') and `Order Key`=%d",
			$this->id);
		// exit("$sql\n");
		$res=mysql_query($sql);
		$deal_keys=array();
		while ($row=mysql_fetch_assoc($res)) {
			$deal_keys[]=$row['Deal Key'];
		}
		if (count($deal_keys)) {
			$sql=sprintf("delete from `Order Deal Bridge` where `Order Key`=%d and `Deal Key` in (%s)   ", $this->id, join(',', $deal_keys));
			mysql_query($sql);
		}

		$sql=sprintf("select `Deal Campaign Key`,`Deal Component Key`, `Deal Key` from  `Order Transaction Deal Bridge`  where`Order Key`=%d and `Deal Component Key`!=0",
			$this->id);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$sql=sprintf("insert into `Order Deal Bridge` values(%d,%d,%d,%d,'Yes','Yes') ON DUPLICATE KEY UPDATE `Used`='Yes'",
				$this->id,
				$row['Deal Campaign Key'],
				$row['Deal Key'],
				$row['Deal Component Key']
			);
			mysql_query($sql);
		}







	}


	function update_deals_usage() {

		include_once 'class.DealCampaign.php';
		include_once 'class.DealComponent.php';



		$deals=array();
		$campaigns=array();
		$sql=sprintf("select `Deal Component Key`,`Deal Key`,`Deal Campaign Key` from  `Order Deal Bridge` where `Order Key`=%d", $this->id);
		// exit("$sql\n");
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {
			$component=new DealComponent($row['Deal Component Key']);
			$component->update_usage();
			$deals[$row['Deal Key']]=$row['Deal Key'];
			$campaigns[$row['Deal Campaign Key']]=$row['Deal Campaign Key'];
		}



		foreach ($deals as $deal_key) {
			$deal=new Deal($deal_key);
			$deal->update_usage();
		}

		foreach ($campaigns as $campaign_key) {
			$campaign=new DealCampaign($campaign_key);
			$campaign->update_usage();
		}

	}


	function update_shipping_method($value) {

		$sql=sprintf("update `Order Dimension` set `Order Shipping Method`=%s where `Order Key`=%d"
			, prepare_mysql($value)
			, $this->id
		);
		mysql_query($sql);

		$this->data['Order Shipping Method']=$value;

	}


	function update_order_is_for_collection($value) {

		if ($value!='Yes')
			$value='No';

		$old_value=$this->data['Order For Collection'];
		if ($old_value!=$value) {

			if ($value=='Yes') {
				$store=new Store($this->data['Order Store Key']);
				$collection_address=new Address($store->data['Store Collection Address Key']);
				if ($collection_address->id) {
					$store_2_alpha_country_code=$collection_address->data['Address Country 2 Alpha Code'];
					$store_country_code=$collection_address->data['Address Country Code'];
					$store_town_code=$collection_address->data['Address Town'];
					$store_world_region_code=$collection_address->get('Address World Region Code');
					$store_postal_code=$collection_address->data['Address Postal Code'];
					$store_address = '<div style="font-weight:800">'._('For collection').'</div>'.$collection_address->display('xhtml');


				} else {

					include_once 'class.Country.php';
					$country=new Country('2alpha', $store->data['Store Home Country Code 2 Alpha']);

					$store_2_alpha_country_code=$country->data['Country 2 Alpha Code'];
					$store_country_code=$country->data['Country Code'];
					$store_town_code='';
					$store_world_region_code=$country->data['World Region Code'];
					$store_postal_code='';
					$store_address = '<div style="font-weight:800">'._('For collection').'</div>';





				}
				$sql=sprintf("update `Order Dimension` set `Order For Collection`='Yes' ,
				`Order Ship To Country Code`=%s,
				`Order Ship To Country 2 Alpha Code`=%s,
				`Order Ship To World Region Code`=%s,
				`Order Ship To Town`=%s,
				`Order Ship To Postal Code`=%s,
				`Order XHTML Ship Tos`=%s,
				`Order Ship To Keys`=''
				where `Order Key`=%d"
					, prepare_mysql($store_country_code)
					, prepare_mysql($store_2_alpha_country_code)
					, prepare_mysql($store_world_region_code)
					, prepare_mysql($store_town_code)
					, prepare_mysql($store_postal_code)
					, prepare_mysql($store_address)
					, $this->id
				);
				mysql_query($sql);


			}
			else {
				$customer=new Customer($this->data['Order Customer Key']);

				$ship_to= $customer->set_current_ship_to('return object');





				$sql=sprintf("update `Order Dimension` set `Order For Collection`='No' ,
				`Order Ship To Country 2 Alpha Code`=%s,
				`Order Ship To Country Code`=%s,`Order XHTML Ship Tos`=%s,`Order Ship To Keys`=%s  ,`Order Ship To World Region Code`=%s,`Order Ship To Town`=%s,`Order Ship To Postal Code`=%s      where `Order Key`=%d"
					, prepare_mysql($ship_to->data['Ship To Country 2 Alpha Code'])
					, prepare_mysql($ship_to->data['Ship To Country Code'])
					, prepare_mysql($ship_to->data['Ship To XHTML Address'])
					, prepare_mysql($ship_to->id)
					, prepare_mysql($ship_to->get('World Region Code'))
					, prepare_mysql($ship_to->data['Ship To Town'])
					, prepare_mysql($ship_to->data['Ship To Postal Code'])
					, $this->id
				);
				mysql_query($sql);
			}
			$this->get_data('id', $this->id);
			$this->new_value=$value;
			$this->updated=true;

			$this->update_shipping();
			$this->update_tax();
			$this->update_totals();

			$this->apply_payment_from_customer_account();



		} else {
			$this->msg=_('Nothing to change');

		}


	}


	function update_ship_to($ship_to_key=false) {

		if (!$ship_to_key) {
			$customer=new Customer($this->data['Order Customer Key']);
			$ship_to= $customer->set_current_ship_to('return object');
		} else {

			$ship_to=new Ship_To($ship_to_key);




		}






		$sql=sprintf("update `Order Dimension` set `Order For Collection`='No' ,`Order Ship To Key To Deliver`=%d,  `Order Ship To Country Code`=%s,`Order XHTML Ship Tos`=%s,`Order Ship To Keys`=%s  ,`Order Ship To World Region Code`=%s,`Order Ship To Town`=%s,`Order Ship To Postal Code`=%s   where `Order Key`=%d"
			, $ship_to->id
			, prepare_mysql($ship_to->data['Ship To Country Code'])
			, prepare_mysql($ship_to->data['Ship To XHTML Address'])
			, prepare_mysql($ship_to->id)
			, prepare_mysql($ship_to->get('World Region Code'))
			, prepare_mysql($ship_to->data['Ship To Town'])
			, prepare_mysql($ship_to->data['Ship To Postal Code'])

			, $this->id

		);
		mysql_query($sql);
		if (mysql_affected_rows()>0) {
			$this->get_data('id', $this->id);
			$this->updated=true;
			$this->new_value=$ship_to->data['Ship To XHTML Address'];
		} else {
			$this->msg=_('Nothing to change');
		}

		$this->update_shipping();
		if ($this->data['Order Tax Selection Type']!='set') {
			$this->update_tax();
		}

	}


	function add_ship_to($ship_to_key) {
		$order_ship_to_keys=preg_split('/\s*\,\s*/', $this->data ['Order Ship To Keys']);
		if (!in_array($ship_to_key, $order_ship_to_keys)) {
			$ship_to=new Ship_To($ship_to_key);
			if ($this->data ['Order Ship To Keys']=='') {
				$this->data ['Order Ship To Keys']=$ship_to_key;
				$this->data ['Order XHTML Ship Tos']='<div>'.$ship_to->display('xhtml').'</div>';
				$this->data ['Order Ship To Country Code']=$ship_to->data['Ship To Country Code'];
				$this->data ['Order Ship To Country 2 Alpha Code']=$ship_to->data['Ship To Country 2 Alpha Code'];
				$this->data ['Order Ship To World Region Code']=$ship_to->get('World Region Code');
				$this->data ['Order Ship To Town']=$ship_to->data['Ship To Town'];
				$this->data ['Order Ship To Postal Code']=$ship_to->data['Ship To Postal Code'];
			} else {
				$this->data ['Order Ship To Keys'].=','.$ship_to_key;
				$this->data ['Order XHTML Ship Tos'].='<div>'.$ship_to->display('xhtml').'</div>';
			}
		}
	}



	function update_billing_to($billing_to_key=false) {

		$old_billing_country_2alpha_code=$this->data['Order Billing To Country 2 Alpha Code'];

		if (!$billing_to_key) {
			$customer=new Customer($this->data['Order Customer Key']);
			$billing_to= $customer->set_current_billing_to('return object');
		} else {

			$billing_to=new Billing_To($billing_to_key);




		}



		$sql=sprintf("update `Order Dimension` set `Order Billing To Key To Bill`=%d,  `Order Billing To Country Code`=%s, `Order Billing To Country 2 Alpha Code`=%s,`Order XHTML Billing Tos`=%s,`Order Billing To Keys`=%s  ,`Order Billing To World Region Code`=%s,`Order Billing To Town`=%s,`Order Billing To Postal Code`=%s   where `Order Key`=%d",
			$billing_to->id,
			prepare_mysql($billing_to->data['Billing To Country Code']),
			prepare_mysql($billing_to->data['Billing To Country 2 Alpha Code']),
			prepare_mysql($billing_to->data['Billing To XHTML Address']),
			prepare_mysql($billing_to->id),
			prepare_mysql($billing_to->get('World Region Code')),
			prepare_mysql($billing_to->data['Billing To Town']),
			prepare_mysql($billing_to->data['Billing To Postal Code']),

			$this->id

		);
		mysql_query($sql);

		$this->get_data('id', $this->id);

		$sql=sprintf("update `Order Transaction Fact` set `Billing To Key`=%d where `Order Key`=%d",
			$billing_to->id,
			$this->id
		);
		mysql_query($sql);

		if (mysql_affected_rows()>0) {
			$this->get_data('id', $this->id);
			$this->updated=true;
			$this->new_value=$billing_to->data['Billing To XHTML Address'];
		} else {
			$this->msg=_('Nothing to change');
		}
		if ($this->data['Order Tax Selection Type']!='set') {


			if ($this->data['Order Billing To Country 2 Alpha Code']!=$old_billing_country_2alpha_code) {
				include_once 'utils/tax_number_functions.php';
				$tax_number_data=check_tax_number($this->data['Order Tax Number'], $this->data['Order Billing To Country 2 Alpha Code']);




				$this->update(
					array(
						'Order Tax Number'=>$this->data['Order Tax Number'],
						'Order Tax Number Valid'=>$tax_number_data['Tax Number Valid'],
						'Order Tax Number Validation Date'=>$tax_number_data['Tax Number Validation Date'],
						'Order Tax Number Associated Name'=>$tax_number_data['Tax Number Associated Name'],
						'Order Tax Number Associated Address'=>$tax_number_data['Tax Number Associated Address'],
					)
				);

			}
			$this->update_tax();
		}


	}


	function add_billing_to($billing_to_key) {
		$order_billing_to_keys=preg_split('/\s*\,\s*/', $this->data ['Order Billing To Keys']);
		if (!in_array($billing_to_key, $order_billing_to_keys)) {
			$billing_to=new Billing_To($billing_to_key);
			if ($this->data ['Order Billing To Keys']=='') {
				$this->data ['Order Billing To Keys']=$billing_to_key;
				$this->data ['Order XHTML Billing Tos']='<div>'.$billing_to->display('xhtml').'</div>';
				$this->data ['Order Billing To Country Code']=$billing_to->data['Billing To Country Code'];
				$this->data ['Order Billing To World Region Code']=$billing_to->get('World Region Code');
				$this->data ['Order Billing To Town']=$billing_to->data['Billing To Town'];
				$this->data ['Order Billing To Postal Code']=$billing_to->data['Billing To Postal Code'];
			} else {
				$this->data ['Order Billing To Keys'].=','.$billing_to_key;
				$this->data ['Order XHTML Billing Tos'].='<div>'.$billing_to->display('xhtml').'</div>';
			}
		}
	}


	function update_full_search() {

		$first_full_search=$this->data['Order Public ID'].' '.$this->data['Order Customer Name'].' '.strftime("%d %b %B %Y", strtotime($this->data['Order Date']));
		$second_full_search=strip_tags(preg_replace('/\<br\/\>/', ' ', $this->data['Order XHTML Ship Tos'])).' '.$this->data['Order Customer Contact Name'];
		$img='';

		$amount='';
		if ($this->data['Order Current Payment State']=='Waiting Payment' or $this->data['Order Current Payment State']=='Partially Paid') {
			$amount=' '.money($this->data['Order Total Amount'], $this->data['Order Currency']);
		}
		elseif ($this->data['Order Current Payment State']=='Paid' or $this->data['Order Current Payment State']=='Payment Refunded') {
			$amount=' '.money($this->data['Order Invoiced Balance Total Amount'], $this->data['Order Currency']);
		}

		$show_description=$this->data['Order Customer Name'].' ('.strftime("%e %b %Y", strtotime($this->data['Order Date'])).') '.$this->data['Order Current XHTML Payment State'].$amount;

		$description1='<b><a href="order.php?id='.$this->id.'">'.$this->data['Order Public ID'].'</a></b>';
		$description='<table ><tr style="border:none;"><td  class="col1"'.$description1.'</td><td class="col2">'.$show_description.'</td></tr></table>';


		$sql=sprintf("insert into `Search Full Text Dimension` (`Store Key`,`Subject`,`Subject Key`,`First Search Full Text`,`Second Search Full Text`,`Search Result Name`,`Search Result Description`,`Search Result Image`) values  (%s,'Order',%d,%s,%s,%s,%s,%s) on duplicate key
		update `First Search Full Text`=%s ,`Second Search Full Text`=%s ,`Search Result Name`=%s,`Search Result Description`=%s,`Search Result Image`=%s"
			, $this->data['Order Store Key']
			, $this->id
			, prepare_mysql($first_full_search)
			, prepare_mysql($second_full_search, false)
			, prepare_mysql($this->data['Order Public ID'], false)
			, prepare_mysql($description, false)
			, prepare_mysql($img, false)
			, prepare_mysql($first_full_search)
			, prepare_mysql($second_full_search, false)
			, prepare_mysql($this->data['Order Public ID'], false)
			, prepare_mysql($description, false)


			, prepare_mysql($img, false)
		);
		mysql_query($sql);





	}


	function prepare_file_as($number) {

		$number=strtolower($number);
		if (preg_match("/^\d+/", $number, $match)) {
			$part_number=$match[0];
			$file_as=preg_replace('/^\d+/', sprintf("%012d", $part_number), $number);

		}
		elseif (preg_match("/\d+$/", $number, $match)) {
			$part_number=$match[0];
			$file_as=preg_replace('/\d+$/', sprintf("%012d", $part_number), $number);

		}
		else {
			$file_as=$number;
		}

		return $file_as;
	}


	function get_number_post_order_transactions() {


		$sql=sprintf("select count(*) as num from `Order Post Transaction Dimension` where `Order Key`=%d  ", $this->id);
		$res=mysql_query($sql);
		$number=0;
		if ($row=mysql_fetch_assoc($res)) {
			$number=$row['num'];
		}
		return $number;
	}


	function get_number_products() {
		$sql=sprintf("select count(*) as num from `Order Transaction Fact` where `Order Key`=%d  ", $this->id);
		$res=mysql_query($sql);
		$number=0;
		if ($row=mysql_fetch_assoc($res)) {
			$number=($row['num']==''?0:$row['num']);
		}
		return $number;
	}


	function update_number_products() {
		$this->data['Order Number Products']=$this->get_number_products();
		$sql=sprintf("update `Order Dimension` set `Order Number Products`=%d where `Order Key`=%d",
			$this->data['Order Number Products'],
			$this->id
		);
		mysql_query($sql);
	}







	function mark_all_transactions_for_refund_to_be_deleted($data) {


		$sql=sprintf("delete from `Order Post Transaction Dimension` where `Order Key`=%d  and `State`='In Process'  and ",
			$this->id
		);
		mysql_query($sql);


		$sql=sprintf("select `Order Transaction Fact Key`, `Invoice Quantity`,`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as value  from  `Order Transaction Fact` OTF left join `Order Post Transaction Dimension` POT  on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) where `Invoice Quantity`>0 and OTF.`Order Key`=%d ",
			$this->id

		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$sql=sprintf("insert into `Order Post Transaction Dimension` (`Order Transaction Fact Key`,`Order Key`,`Quantity`,`Operation`,`Reason`,`To Be Returned`,`Customer Key`,'Credit') values (%d,%d,%f,%s,%s,%s,%d,%f)",
				$row['Order Transaction Fact Key'],
				$this->id,
				$row['Invoice Quantity'],
				prepare_mysql('Refund'),
				prepare_mysql($data['Reason']),
				prepare_mysql($data['To Be Returned']),
				$this->data['Order Customer Key'],
				$row['value']
			);
			mysql_query($sql);


		}

	}


	function get_post_transactions_in_process_data() {
		$data=array(
			'Refund'=>array(
				'In_Process_Products'=>0,

				'Amount'=>0,
				'Tax_Amount'=>0,
				'Other_Items_Amount'=>$this->data['Order Invoiced Items Amount'],


				'Net_Amount'=>0,
				'Tax_Amount'=>0,
				'Formatted_Net_Amount'=>money(0, $this->data['Order Currency']),
				'Formatted_Tax_Amount'=>money(0, $this->data['Order Currency']),
				'Formatted_Zero_Amount'=>money(0, $this->data['Order Currency']),

				'Refunded_Products'=>0,
				'Refunded_No_Products'=>0,
				'Refunded_Transactions'=>0,
				'Refunded_Net_Amount'=>0,
				'Refunded_Tax_Amount'=>0,
				'Refunded_Total_Amount'=>0,
				'Refunded_Formatted_Net_Amount'=>money(0, $this->data['Order Currency']),
				'Refunded_Formatted_Tax_Amount'=>money(0, $this->data['Order Currency']),
				'Refunded_Formatted_Total_Amount'=>money(0, $this->data['Order Currency'])

			),
			'Resend'=>array('In_Process_Products'=>0, 'Distinct_Products'=>0, 'Market_Value'=>0, 'Formatted_Market_Value'=>money(0, $this->data['Order Currency']), 'state'=>''),
			// 'Saved_Credit'=>array('Distinct_Products'=>0,'Amount'=>0,'Formatted_Amount'=>money(0,$this->data['Order Currency']),'State'=>'')



		);




		$sql=sprintf("select `Invoice Currency Code`,
		 sum(`Quantity`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)/`Invoice Quantity`) as net_value,
		 sum(`Quantity`*(`Invoice Transaction Item Tax Amount`)/`Invoice Quantity`) as tax_value,
          count(DISTINCT OTF.`Product Key` ) as num from `Order Post Transaction Dimension` POT left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) where `Invoice Quantity`>0 and POT.`Order Key`=%d and   `Operation`='Refund'  and `State`='In Process'",
			$this->id

		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($row['num']>0) {
				$data['Refund']['In_Process_Products']=$row['num'];
				$data['Refund']['Net_Amount']=$row['net_value'];
				$data['Refund']['Tax_Amount']=$row['tax_value'];

				$data['Refund']['Formatted_Net_Amount']=money($row['net_value'], $row['Invoice Currency Code']);
				$data['Refund']['Formatted_Tax_Amount']=money($row['tax_value'], $row['Invoice Currency Code']);

			}
		}




		$sql=sprintf("select `Invoice Currency Code`,
		 sum(OTF.`Invoice Transaction Net Refund Items`) as net_value,
		 sum(OTF.`Invoice Transaction Tax Refund Items`) as tax_value,
          count(DISTINCT OTF.`Product Key` ) as num from `Order Post Transaction Dimension` POT left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) where   POT.`Order Key`=%d and   `Operation`='Refund'  and `State`!='In Process'",
			$this->id

		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($row['num']>0) {
				$currency=$row['Invoice Currency Code'];
				$data['Refund']['Refunded_Products']=$row['num'];
				$data['Refund']['Refunded_Transactions']=$row['num'];

				$data['Refund']['Refunded_Net_Amount']=$row['net_value'];
				$data['Refund']['Refunded_Tax_Amount']=$row['tax_value'];
				$data['Refund']['Refunded_Total_Amount']=$row['net_value']+$row['tax_value'];

			}
		}


		$sql=sprintf("select `Currency Code`,
		 sum(`Transaction Refund Net Amount`) as net_value,
		 sum(`Transaction Refund Tax Amount`) as tax_value,
          count(*) as num from `Order No Product Transaction Fact`    where (`Transaction Refund Net Amount`!=0 or `Transaction Refund Tax Amount`!=0 ) and  `Order Key`=%d ",
			$this->id

		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($row['num']>0) {
				$currency=$row['Currency Code'];

				$data['Refund']['Refunded_No_Products']=$row['num'];
				$data['Refund']['Refunded_Transactions']+=$row['num'];

				$data['Refund']['Refunded_Net_Amount']+=$row['net_value'];
				$data['Refund']['Refunded_Tax_Amount']+=$row['tax_value'];
				$data['Refund']['Refunded_Total_Amount']+=$row['net_value']+$row['tax_value'];

			}
		}

		if ($data['Refund']['Refunded_Transactions']>0) {
			$data['Refund']['Refunded_Formatted_Net_Amount']=money($data['Refund']['Refunded_Net_Amount'], $currency);
			$data['Refund']['Refunded_Formatted_Tax_Amount']=money($data['Refund']['Refunded_Tax_Amount'], $currency);
			$data['Refund']['Refunded_Formatted_Total_Amount']=money($data['Refund']['Refunded_Total_Amount'], $currency);
		}

		/*
		$sql=sprintf("select `Invoice Currency Code`, sum(`Quantity`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)/`Invoice Quantity`) as value, count(DISTINCT OTF.`Product Key` ) as num from `Order Post Transaction Dimension` POT left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) where `Invoice Quantity`>0 and POT.`Order Key`=%d and   `Operation`='Credit'",
			$this->id
		);


		$sql=sprintf("select `Invoice Currency Code`, sum(POT.`Credit`) as value, count(DISTINCT OTF.`Product Key` ) as num from `Order Post Transaction Dimension` POT left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) where   POT.`Order Key`=%d and   `Operation`='Credit' and `State`='Saved'  ",
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$data['Saved_Credit']['Distinct_Products']=$row['num'];
			$data['Saved_Credit']['Amount']=$row['value'];
			$data['Saved_Credit']['Formatted_Amount']=money($row['value'],$row['Invoice Currency Code']);
		}



		$sql=sprintf("select `Invoice Currency Code`, sum(POT.`Credit`) as value, count(DISTINCT OTF.`Product Key` ) as num from `Order Post Transaction Dimension` POT left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) where   POT.`Order Key`=%d and   `Operation`='Credit' and `State`='In Process'  ",
			$this->id
		);


		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$data['Credit']['Distinct_Products']=$row['num'];
			$data['Credit']['Amount']=$row['value'];
			$data['Credit']['Formatted_Amount']=money($row['value'],$row['Invoice Currency Code']);
		}
*/
		$sql=sprintf("select  `State`,`Product Currency`,sum(`Quantity`*`Product History Price`) as value,  count(DISTINCT OTF.`Product Key` ) as num from `Order Post Transaction Dimension` POT left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) left join `Product History Dimension` PH on (OTF.`Product Key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)  where `Operation`='Resend' and POT.`Order Key`=%d ",
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$data['Resend']['Distinct_Products']=$row['num'];
			$data['Resend']['State']=$row['State'];

			$data['Resend']['Market_Value']=$row['value'];
			$data['Resend']['Formatted_Market_Value']=money($row['value'], $row['Product Currency']);

		}


		$sql=sprintf("select  count(DISTINCT OTF.`Product Key` ) as num from `Order Post Transaction Dimension` POT left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) where `Operation`='Resend' and (POT.`Delivery Note Key`=0 or POT.`Delivery Note Key` is NULL)  and POT.`Order Key`=%d ",
			$this->id
		);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$data['Resend']['In_Process_Products']=$row['num'];

		}


		$data['Refund']['Other_Items_Amount']-=$data['Refund']['Amount'];
		$data['Refund']['Formatted_Other_Items_Amount']=money($data['Refund']['Other_Items_Amount'], $this->data['Order Currency']);


		return $data;

	}



	function cancel_post_transactions_in_process() {
		$this->deleted_post_transactions=0;
		$sql=sprintf("delete from `Order Post Transaction Dimension` where `Order Key`=%d and `State`='In Process' ",
			$this->id
		);
		mysql_query($sql);
		$this->deleted_post_transactions=mysql_affected_rows();



	}




	function cancel_submited_credits() {
		$sql=sprintf("delete  from `Order Post Transaction Dimension` where `Order Key`=%d and `State`='Saved' and `Operation`='Credit'",
			$this->id
		);
		mysql_query($sql);

	}


	function submit_credits() {
		$sql=sprintf("update `Order Post Transaction Dimension` set `Credit Saved`=`Credit` , `State`='Saved'  where `Order Key`=%d and `State`='In Process' and `Operation`='Credit'",
			$this->id
		);
		mysql_query($sql);

	}


	function create_post_transaction_in_process($otf_key, $key, $values) {



		if (!preg_match('/^(Quantity|Operation|Reason|To Be Returned)$/', $key)) {
			$this->error=true;
			return;
		}



		$this->deleted_post_transaction=false;
		$this->update_post_transaction=false;
		$this->created_post_transaction=false;
		$this->updated=false;
		$sql=sprintf('select * from `Order Post Transaction Dimension` where `Order Transaction Fact Key`=%d', $otf_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($row['Order Key']!=$this->id) {
				$this->error=true;

				return;
			}

			if ($key=='Quantity' and $values[$key]<=0) {
				$sql=sprintf("delete from `Order Post Transaction Dimension` where `Order Post Transaction Key`=%d ",
					$row['Order Post Transaction Key']
				);
				mysql_query($sql);
				if (mysql_affected_rows()>0) {
					$this->update_post_transaction=true;
					$this->updated=true;

					$opt_key=$row['Order Post Transaction Key'];
					$this->deleted_post_transaction=true;
				}
			} else {


				$sql=sprintf("update `Order Post Transaction Dimension` set `%s`=%s where `Order Post Transaction Key`=%d ",
					$key,
					prepare_mysql($values[$key]),
					$row['Order Post Transaction Key']
				);
				mysql_query($sql);
				$affected_rows=mysql_affected_rows();
				if ($key=='Quantity' and $row['Operation']=='Credit') {
					$sql=sprintf("select `Invoice Currency Code`, (`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)/`Invoice Quantity` as value,OTF.`Order Transaction Fact Key` from  `Order Transaction Fact`  OTF where OTF.`Order Transaction Fact Key`=%d",
						$otf_key
					);



					$res2=mysql_query($sql);
					if ($row2=mysql_fetch_assoc($res2)) {
						$sql=sprintf("update `Order Post Transaction Dimension` set `Credit`=%.2f where `Order Post Transaction Key`=%d ",
							$row2['value']*$values[$key],
							$row['Order Post Transaction Key']
						);
						mysql_query($sql);
					}



				}


				if ($key=='Operation' ) {
					$sql=sprintf("select `Invoice Currency Code`, (`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)/`Invoice Quantity` as value,OTF.`Order Transaction Fact Key` from  `Order Transaction Fact`  OTF where OTF.`Order Transaction Fact Key`=%d",
						$otf_key
					);


					$qty=0;
					if (is_numeric($row['Quantity'])) {
						$qty=$row['Quantity'];
					}

					$res2=mysql_query($sql);
					if ($row2=mysql_fetch_assoc($res2)) {
						$sql=sprintf("update `Order Post Transaction Dimension` set `Credit`=%.2f where `Order Post Transaction Key`=%d ",
							$row2['value']*$qty,
							$row['Order Post Transaction Key']
						);
						mysql_query($sql);
					}



				}

				if ($affected_rows>0) {



					$this->update_post_transaction=true;
					$this->updated=true;
					$opt_key=$row['Order Post Transaction Key'];



				}
			}

		}
		else {
			$sql=sprintf("insert into `Order Post Transaction Dimension` (`Order Transaction Fact Key`,`Order Key`,`Quantity`,`Operation`,`Reason`,`To Be Returned`,`Customer Key`) values (%d,%d,%f,%s,%s,%s,%d)",
				$otf_key,
				$this->id,
				$values['Quantity'],
				prepare_mysql($values['Operation']),
				prepare_mysql($values['Reason']),
				prepare_mysql($values['To Be Returned']),
				$this->data['Order Customer Key']
			);

			mysql_query($sql);
			if (mysql_affected_rows()>0) {
				$this->created_post_transaction=true;
				$this->updated=true;
				$opt_key=mysql_insert_id();



				if ($values['Operation']='Credit') {
					$sql=sprintf("select `Invoice Currency Code`, sum(`Quantity`*(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`)/`Invoice Quantity`) as value from `Order Post Transaction Dimension` POT left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) where `Invoice Quantity`>0 and OTF.`Order Transaction Fact Key`=%d and  `Operation`='Credit' and `State`='In Process'",
						$otf_key
					);
					$res2=mysql_query($sql);
					if ($row2=mysql_fetch_assoc($res2)) {
						$sql=sprintf("update `Order Post Transaction Dimension` set `Credit`=%.2f where `Order Post Transaction Key`=%d ",
							$row2['value'],
							$opt_key
						);
						mysql_query($sql);
					}



				}


			}

		}
		$transaction_data=array();



		$sql=sprintf('select `Order Key`,`State`,`Operation`,`Reason`,`Quantity`,`To Be Returned` from `Order Post Transaction Dimension` where `Order Transaction Fact Key`=%d',
			$otf_key);
		$res2=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res2)) {
			$transaction_data['Quantity']=$row['Quantity'];
			$transaction_data['Operation']=$row['Operation'];
			$transaction_data['Reason']=$row['Reason'];
			$transaction_data['State']=$row['State'];
			$transaction_data['To Be Returned']=$row['To Be Returned'];
			$transaction_data['Order Key']=$row['Order Key'];
		}

		if ($this->created_post_transaction or $this->update_post_transaction) {

			$transaction_data['Order Post Transaction Key']=$opt_key;
		}
		if ($this->deleted_post_transaction) {
			$transaction_data['Quantity']='';
			$transaction_data['Operation']='';
			$transaction_data['Reason']='';
			$transaction_data['State']='';
			$transaction_data['To Be Returned']='';
			$transaction_data['Order Key']='';
		}


		return $transaction_data;

	}


	function add_post_order_transactions($data) {
		$otf_key=array();
		$sql=sprintf("select `Order Post Transaction Key`,OTF.`Product ID`,`Product Package Weight`,`Quantity`,`Product Units Per Case` from `Order Post Transaction Dimension` POT  left join `Order Transaction Fact` OTF on (OTF.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`) left join `Product History Dimension`  PH on (PH.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)   where POT.`Order Key`=%d  and `State`='In Process' ",
			$this->id);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$order_key=$this->id;
			$order_date=gmdate('Y-m-d H:i:s');
			$order_public_id=$this->data['Order Public ID'];

			$product=new Product('id', $row['Product ID']);

			$bonus_quantity=0;
			$sql = sprintf( "insert into `Order Transaction Fact` (`Order Date`,`Order Key`,`Order Public ID`,`Delivery Note Key`,`Delivery Note ID`,`Order Bonus Quantity`,`Order Transaction Type`,`Transaction Tax Rate`,`Transaction Tax Code`,`Order Currency Code`,`Estimated Weight`,`Order Last Updated Date`,
			`Product Key`,`Product ID`,`Product Code`,`Product Family Key`,`Product Department Key`,
			`Current Dispatching State`,`Current Payment State`,`Customer Key`,`Delivery Note Quantity`,`Ship To Key`,`Billing To Key`,
			`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Amount`,`Metadata`,`Store Key`,`Units Per Case`,`Customer Message`)
values (%s,%s,%s,%d,%s,%f,%s,%f,%s,%s,%s,  %s,
	%d,%d,%s,%d,%d,
	%s,%s,%d,%s,%s,%s,
	%.2f,%.2f,%.2f,%s,%s,%f,'') ",
				prepare_mysql($order_date),
				prepare_mysql($order_key),
				prepare_mysql($order_public_id),

				0,
				prepare_mysql(''),

				$bonus_quantity,
				prepare_mysql('Resend'),
				$data['Order Tax Rate'],
				prepare_mysql ($data['Order Tax Code']),
				prepare_mysql ( $this->data['Order Currency'] ),
				$row['Product Package Weight']*$row['Quantity'],

				prepare_mysql($order_date),
				$product->historic_id,
				$product->data['Product ID'],
				prepare_mysql($product->data['Product Code']),
				$product->data['Product Family Key'],
				$product->data['Product Main Department Key'],

				prepare_mysql ( 'In Process' ),
				prepare_mysql ( $data ['Current Payment State'] ),
				prepare_mysql ( $this->data['Order Customer Key' ] ),

				$row['Quantity'],
				prepare_mysql ( $data['Ship To Key'] ),
				prepare_mysql ( $data['Billing To Key'] ),
				$data['Gross'],
				0,
				$data['Gross'],
				prepare_mysql ( $data ['Metadata'] , false),
				prepare_mysql ( $this->data['Order Store Key'] ),
				$row['Product Units Per Case']

			);

			if (! mysql_query( $sql ))
				exit ( "$sql can not update xx orphan transaction\n" );
			$otf_key=mysql_insert_id();

			$sql=sprintf("update  `Order Post Transaction Dimension` set `Order Post Transaction Fact Key`=%d where `Order Post Transaction Key`=%d   ", $otf_key, $row['Order Post Transaction Key']);
			mysql_query( $sql );
			//print $sql;
		}

		if (array_key_exists('Supplier Metadata', $data)) {

			$sql = sprintf( "update`Order Transaction Fact` set  `Supplier Metadata`=%s  where `Order Transaction Fact Key`=%d ",
				prepare_mysql($data['Supplier Metadata']),
				$otf_key

			);
			//        print "$sql\n";
			mysql_query($sql);
		}

		return array('otf_key'=>$otf_key);

	}


	function get_notes() {

		$notes='';
		if ($this->data['Order Customer Sevices Note']!='')
			$notes.="<div><div style='color:#777;font-size:90%;padding-bottom:5px'>"._('Note').":</div>".$this->data['Order Customer Sevices Note']."</div>";
		if ($this->data['Order Customer Message']!='')
			$notes.="<div><div style='color:#777;font-size:90%;padding-bottom:5px'>"._('Customer Note').":</div>".$this->data['Order Customer Message']."</div>";

		return $notes;

	}


	function get_currency_symbol() {
		return currency_symbol($this->data['Order Currency']);
	}


	function get_formatted_tax_info() {
		$selection_type=$this->data['Order Tax Selection Type'];
		$formatted_tax_info='<span title="'.$selection_type.'">'.$this->data['Order Tax Name'].'</span>';
		return $formatted_tax_info;
	}


	function get_formatted_tax_info_with_operations() {
		$operations=$this->data['Order Tax Operations'];
		$selection_type=$this->data['Order Tax Selection Type'];
		$formatted_tax_info='<span title="'.$selection_type.'">'.$this->data['Order Tax Name'].'</span>'.$operations;
		return $formatted_tax_info;
	}


	function get_formatted_dispatch_state() {
		return get_order_formatted_dispatch_state($this->data['Order Current Dispatch State'], $this->id);

	}


	function get_formatted_payment_state() {
		return get_order_formatted_payment_state($this->data);

	}





	function set_as_invoiced($invoice) {


		$sql=sprintf("update `Order Dimension` set `Order Invoiced`='Yes'   where `Order Key`=%d ",
			$this->id
		);

		mysql_query($sql);

		$this->data['Order Invoiced']='Yes';

		$customer=new Customer($this->data['Order Customer Key']);

		$customer->update_orders();


		$invoice_link=sprintf('<a href="invoice.php?id=%d">%s</a>', $invoice->id, $invoice->data['Invoice Public ID']);
		$history_data=array(
			'History Abstract'=>sprintf(_('Order invoiced (%s)'), $invoice_link),
			'History Details'=>'',
		);
		$this->add_subject_history($history_data);


	}


	function get_no_product_deal_info($type) {
		$deal_info='';
		$sql=sprintf("select `Deal Info` from `Order No Product Transaction Deal Bridge` B left join `Order No Product Transaction Fact` OTF on (OTF.`Order No Product Transaction Fact Key`=B.`Order No Product Transaction Fact Key`) where B.`Order Key`=%d and `Transaction Type`=%s",
			$this->id,
			prepare_mysql($type)
		);

		$res=mysql_query($sql);

		if ($row=mysql_fetch_assoc($res)) {
			$deal_info=$row['Deal Info'];
		}

		return $deal_info;
	}


	function get_vouchers_info() {
		$vouchers_info=array();
		$sql=sprintf('select V.`Voucher Key`,`Voucher Code`,D.`Deal Key`,`Deal Name`,`Deal Description` from `Voucher Order Bridge` B left join `Deal Dimension` D on (B.`Deal Key`=D.`Deal Key`) left join `Voucher Dimension` V on (B.`Voucher Key`=V.`Voucher Key`) where `Order Key`=%d',
			$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$vouchers_info[]=array(
				'key'=>$row['Voucher Key'],
				'code'=>$row['Voucher Code'],
				//   'state'=>$row['State'],
				'deal_key'=>$row['Deal Key'],
				'deal_name'=>$row['Deal Name'],
				'deal_description'=>$row['Deal Description']

			);
		}

		return $vouchers_info;

	}


	function get_deals_info() {
		$deals_info=array();
		$sql=sprintf('select B.`Deal Key`,`Deal Name`,`Deal Description`,`Deal Term Allowances` from `Order Deal Bridge` B left join `Deal Dimension` D on (B.`Deal Key`=D.`Deal Key`) where `Order Key`=%d  and `Deal Trigger` in ("Order","Customer") and `Deal Terms Type`!="Voucher" group by B.`Deal Key`',
			$this->id);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$deals_info[]=array(
				'key'=>$row['Deal Key'],
				'name'=>$row['Deal Name'],
				'description'=>$row['Deal Description'],
				'terms_allowances'=>$row['Deal Term Allowances'],
			);
		}

		return $deals_info;

	}


	function get_tax_data() {


		include_once 'utils/geography_functions.php';

		$store=new Store($this->data['Order Store Key']);
		$customer=new Customer($this->data['Order Customer Key']);




		switch ($store->data['Store Tax Country Code']) {
		case 'ESP':


			$sql=sprintf("select `Tax Category Code`,`Tax Category Type`,`Tax Category Name`,`Tax Category Rate` from `Tax Category Dimension`  where `Tax Category Country Code`='ESP' and `Tax Category Active`='Yes'");
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {


				switch ($row['Tax Category Name']) {
				case 'Exento':
					$tax_category_name=_('Exempt');
					break;
				case 'IVA 21%':
					$tax_category_name=_('VAT').' 21%';
					break;
				case 'RE (5,2%)':
					$tax_category_name='RE (5,2%)';
					break;
				case 'IVA+RE (26,2%)':
					$tax_category_name='IVA+RE (26,2%)';
					break;



				default:
					$tax_category_name=$row['Tax Category Name'];
				}



				$tax_category[$row['Tax Category Type']]= array(
					'code'=>$row['Tax Category Code'],
					'name'=>$tax_category_name,
					'rate'=>$row['Tax Category Rate']);



			}


			if ( $this->data['Order Delivery Address Country 2 Alpha Code']=='ES' and  $this->data['Order Invoice Address Country 2 Alpha Code']=='ES'
				and preg_match('/^(35|38|51|52)/', $this->data['Order Delivery Address Postal Code'])
				and preg_match('/^(35|38|51|52)/', $this->data['Order Invoice Address Postal Code'])
			) {

				return array(
					'code'=>$tax_category['Excluded']['code'],
					'name'=>$tax_category['Excluded']['name'],
					'rate'=>$tax_category['Excluded']['rate'],
					'state'=>'ouside EC',
					'operations'=>'<div>'._('Outside EC fiscal area').'</div>'

				);
			}

			// new rule seems that is valid to ESP, E.g. billing to Madrid and shipping to canarias
			if ( $this->data['Order Delivery Address Country 2 Alpha Code']=='ES' and  $this->data['Order Invoice Address Country 2 Alpha Code']=='ES'
				and preg_match('/^(35|38|51|52)/', $this->data['Order Delivery Address Postal Code'])
			) {

				return array(
					'code'=>$tax_category['Excluded']['code'],
					'name'=>$tax_category['Excluded']['name'],
					'rate'=>$tax_category['Excluded']['rate'],
					'state'=>'ouside EC',
					'operations'=>'<div>'._('Outside EC fiscal area').'</div>'

				);
			}




			if (in_array($this->data['Order Delivery Address Country 2 Alpha Code'], array('ES', 'XX'))) {

				if ($customer->data['Recargo Equivalencia']=='Yes') {

					return array(
						'code'=>$tax_category['IVA+RE']['code'],
						'name'=>$tax_category['IVA+RE']['name'],
						'rate'=>$tax_category['IVA+RE']['rate'],
						'state'=>'delivery to ESP with RE',
						'operations'=>' <div class="buttons small"><button id="remove_recargo_de_equivalencia" title="Quitar Recargo de equivalencia" style="margin:0px" onClick="update_recargo_de_equivalencia(\'No\')"><img src="/art/icons/delete.png"> RE</button></div>'

					);

				}else {

					return array(
						'code'=>$tax_category['IVA']['code'],
						'name'=>$tax_category['IVA']['name'],
						'rate'=>$tax_category['IVA']['rate'],
						'state'=>'delivery to ESP',
						'operations'=>' <div class="buttons small"><button id="add_recargo_de_equivalencia" title="Añade Recargo de equivalencia" style="margin:0px" onClick="update_recargo_de_equivalencia(\'Yes\')"><img src="/art/icons/add.png"> RE (5,2%)</button></div>'

					);

				}




			}
			elseif (in_array($this->data['Order Invoice Address Country 2 Alpha Code'], array('ES', 'XX'))) {

				if ($customer->data['Recargo Equivalencia']=='Yes') {

					return array(
						'code'=>$tax_category['IVA+RE']['code'],
						'name'=>$tax_category['IVA+RE']['name'],
						'rate'=>$tax_category['IVA+RE']['rate'],
						'state'=>'billing to ESP with RE',
						'operations'=>' <div class="buttons small"><button id="remove_recargo_de_equivalencia" title="Quitar Recargo de equivalencia" style="margin:0px" onClick="update_recargo_de_equivalencia(\'No\')"><img src="/art/icons/delete.png"> RE</button></div>'

					);

				}else {

					return array(
						'code'=>$tax_category['IVA']['code'],
						'name'=>$tax_category['IVA']['name'],
						'rate'=>$tax_category['IVA']['rate'],
						'state'=>'billing to ESP',
						'operations'=>' <div class="buttons small"><button id="add_recargo_de_equivalencia" title="Añade Recargo de equivalencia" style="margin:0px" onClick="update_recargo_de_equivalencia(\'Yes\')"><img src="/art/icons/add.png"> RE (5,2%)</button></div>'

					);

				}



			}
			elseif ( in_array($this->data['Order Invoice Address Country 2 Alpha Code'], get_countries_EC_Fiscal_VAT_area($this->db))) {



				if ($this->data['Order Tax Number Valid']=='Yes') {


					$response= array(
						'code'=>$tax_category['Excluded']['code'],
						'name'=>$tax_category['Excluded']['name'].'<div>'._('Valid tax number').'<br>'.$this->data['Order Tax Number'].'</div>',
						'rate'=>$tax_category['Excluded']['rate'],
						'state'=>'EC with valid tax number',
						'operations'=>''

					);

				}
				else {

					if ($this->data['Order Tax Number']=='') {



						$response= array(
							'code'=>$tax_category['IVA']['code'],
							'name'=>$tax_category['IVA']['name'],
							'rate'=>$tax_category['IVA']['rate'],
							'state'=>'EC no tax number' ,
							'operations'=>'<div><img  style="width:12px;position:relative:bottom:2px" src="/art/icons/information.png"/><span style="font-size:90%"> '._('VAT might be exempt with a valid tax number').'</span> <div class="buttons small"><button id="set_tax_number" style="margin:0px" onClick="show_set_tax_number_dialog()">'._('Set up tax number').'</button></div></div>'

						);

					}
					else {


						$response= array(
							'code'=>$tax_category['IVA']['code'],
							'name'=>$tax_category['IVA']['name'],
							'rate'=>$tax_category['IVA']['rate'],
							'state'=>'EC with invalid tax number',

							'operations'=>'<div>
					<img style="width:12px;position:relative;bottom:-1px" src="/art/icons/error.png">
					<span style="font-size:90%;"  >'._('Invalid tax number').'</span>
					<img style="cursor:pointer;position:relative;top:4px"  onClick="check_tax_number_from_tax_info()"  id="check_tax_number" src="/art/validate.png" alt="('._('Validate').')" title="'._('Validate').'">
					<br/>
					<img id="set_tax_number" style="width:14px;cursor:pointer;position:relative;top:2px" src="/art/icons/edit.gif"  onClick="show_set_tax_number_dialog()" title="'._('Edit tax number').'"/>

					<span id="tax_number">'.$this->data['Order Tax Number'].'</span>
				</div>'

						);



					}

				}

				return $response;
			}
			else {


				if ( in_array($this->data['Order Delivery Address Country 2 Alpha Code'], get_countries_EC_Fiscal_VAT_area($this->db))) {


					return array(
						'code'=>$tax_category['IVA']['code'],
						'name'=>$tax_category['IVA']['name'],
						'rate'=>$tax_category['IVA']['rate'],
						'state'=>'delivery to EC with no EC billing',
						'operations'=>''

					);

				}else {
					return array(
						'code'=>$tax_category['Excluded']['code'],
						'name'=>$tax_category['Excluded']['name'],
						'rate'=>$tax_category['Excluded']['rate'],
						'state'=>'ouside EC',
						'operations'=>'<div>'._('Outside EC fiscal area').'</div>'

					);

				}

			}
			break;
		case 'GBR':

			$tax_category=array();

			$sql=sprintf("select `Tax Category Code`,`Tax Category Type`,`Tax Category Name`,`Tax Category Rate` from `Tax Category Dimension`  where `Tax Category Country Code`='GBR' and `Tax Category Active`='Yes'");



			if ($result=$this->db->query($sql)) {
				foreach ($result as $row) {



					switch ($row['Tax Category Name']) {
					case 'Outside the scope of VAT':
						$tax_category_name=_('Outside the scope of VAT');
						break;
					case 'VAT 17.5%':
						$tax_category_name=_('VAT').' 17.5%';
						break;
					case 'VAT 20%':
						$tax_category_name=_('VAT').' 20%';
						break;
					case 'VAT 15%':
						$tax_category_name=_('VAT').' 15%';
						break;
					case 'No Tax':
						$tax_category_name=_('No Tax');
						break;
					case 'Exempt from VAT':
						$tax_category_name=_('Exempt from VAT');
						break;


					default:
						$tax_category_name=$row['Tax Category Name'];
					}



					$tax_category[$row['Tax Category Type']]= array(
						'code'=>$row['Tax Category Code'],
						'name'=>$tax_category_name,
						'rate'=>$row['Tax Category Rate']);




				}
			}else {
				print_r($error_info=$this->db->errorInfo());
				exit;
			}







			if (in_array($this->data['Order Delivery Address Country 2 Alpha Code'], array('GB', 'XX', 'IM'))) {

				return array(
					'code'=>$tax_category['Standard']['code'],
					'name'=>$tax_category['Standard']['name'],
					'rate'=>$tax_category['Standard']['rate'],
					'state'=>'delivery to GBR',
					'operations'=>''

				);
			}
			elseif (in_array($this->data['Order Invoice Address Country 2 Alpha Code'], array('GBR', 'UNK', 'IM'))) {

				return array(
					'code'=>$tax_category['Standard']['code'],
					'name'=>$tax_category['Standard']['name'],
					'rate'=>$tax_category['Standard']['rate'],
					'state'=>'billing to GBR',
					'operations'=>''
				);
			}
			elseif ( in_array($this->data['Order Invoice Address Country 2 Alpha Code'], get_countries_EC_Fiscal_VAT_area($this->db))) {



				if ($this->data['Order Tax Number Valid']=='Yes') {


					$response= array(
						'code'=>$tax_category['Outside']['code'],
						'name'=>$tax_category['Outside']['name'].'<div>'._('Valid tax number').'<br>'.$this->data['Order Tax Number'].'</div>',
						'rate'=>$tax_category['Outside']['rate'],
						'state'=>'EC with valid tax number',
						'operations'=>''

					);

				}
				else {

					if ($this->data['Order Tax Number']=='') {



						$response= array(
							'code'=>$tax_category['Standard']['code'],
							'name'=>$tax_category['Standard']['name'],
							'rate'=>$tax_category['Standard']['rate'],
							'state'=>'EC no tax number' ,
							'operations'=>'<div><img  style="width:12px;position:relative:bottom:2px" src="/art/icons/information.png"/><span style="font-size:90%"> '._('VAT might be exempt with a valid tax number').'</span> <div class="buttons small"><button id="set_tax_number" style="margin:0px" onClick="show_set_tax_number_dialog()">'._('Set up tax number').'</button></div></div>'

						);

					}
					else {


						$response= array(
							'code'=>$tax_category['Standard']['code'],
							'name'=>$tax_category['Standard']['name'],
							'rate'=>$tax_category['Standard']['rate'],
							'state'=>'EC with invalid tax number',

							'operations'=>'<div>
					<img style="width:12px;position:relative;bottom:-1px" src="/art/icons/error.png">
					<span style="font-size:90%;"  >'._('Invalid tax number').'</span>
					<img style="cursor:pointer;position:relative;top:4px"  onClick="check_tax_number_from_tax_info()"  id="check_tax_number" src="/art/validate.png" alt="('._('Validate').')" title="'._('Validate').'">
					<br/>
					<img id="set_tax_number" style="width:14px;cursor:pointer;position:relative;top:2px" src="/art/icons/edit.gif"  onClick="show_set_tax_number_dialog()" title="'._('Edit tax number').'"/>

					<span id="tax_number">'.$this->data['Order Tax Number'].'</span>
				</div>'

						);



					}

				}



				return $response;

			}
			else {


				if ( in_array($this->data['Order Delivery Address Country 2 Alpha Code'], get_countries_EC_Fiscal_VAT_area($this->db))) {


					return array(
						'code'=>$tax_category['Standard']['code'],
						'name'=>$tax_category['Standard']['name'],
						'rate'=>$tax_category['Standard']['rate'],
						'state'=>'delivery to EC with no EC billing',
						'operations'=>''

					);

				}else {
					return array(
						'code'=>$tax_category['Outside']['code'],
						'name'=>$tax_category['Outside']['name'],
						'rate'=>$tax_category['Outside']['rate'],
						'state'=>'ouside EC',
						'operations'=>'<div>'._('Outside EC fiscal area').'</div>'

					);

				}

			}






			break;
		}





	}


	function get_payment_keys($status='') {

		$payments=array();

		if ($status) {
			if ($status=='Pending')
				$where=sprintf(' and `Payment Transaction Status`=%s  and `Payment Method`!="Account" ', prepare_mysql($status));

			else
				$where=sprintf(' and `Payment Transaction Status`=%s', prepare_mysql($status));
		}else {
			$where='';
		}

		$sql=sprintf("select `Payment Key` from `Payment Dimension` where `Payment Order Key`=%d %s",
			$this->id,
			$where
		);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$payments[$row['Payment Key']]=$row['Payment Key'];
		}
		return $payments;
	}


	function get_payment_objects($status='', $load_payment_account=false, $load_payment_service_provider=false) {

		include_once 'class.Payment.php';
		include_once 'class.Payment_Account.php';
		include_once 'class.Payment_Service_Provider.php';



		$payments=array();


		foreach ($this->get_payment_keys($status) as $payment_key) {
			$payment=new Payment($payment_key);
			$payment->formatted_amount=money($payment->data['Payment Amount'], $payment->data['Payment Currency Code']);


			if ($load_payment_account)
				$payment->load_payment_account();
			if ($load_payment_service_provider)
				$payment->load_payment_service_provider();
			$payments[$payment_key]=$payment;
		}


		return $payments;
	}


	function get_number_payments($status='') {


		return count($this->get_payment_keys($status));
	}


	function add_basket_history($data) {

		$sql=sprintf("insert into `Order Basket History Dimension`  (
		`Date`,`Order Transaction Key`,`Site Key`,`Store Key`,`Customer Key`,`Order Key`,`Page Key`,`Product ID`,`Quantity Delta`,`Quantity`,`Net Amount Delta`,`Net Amount`,`Page Store Section Type`)
	value (%s,%s,%d,%d,%d,%d,%d,%d,
		%f,%f,%.2f,%.2f,%s
		) ",
			prepare_mysql(gmdate('Y-m-d H:i:s')),
			prepare_mysql($data['otf_key']),
			$this->data['Order Site Key'],
			$this->data['Order Store Key'],
			$this->data['Order Customer Key'],
			$this->id,
			$data['Page Key'],
			$data['Product ID'],
			$data['Quantity Delta'],
			$data['Quantity'],
			$data['Net Amount Delta'],
			$data['Net Amount'],
			prepare_mysql($data['Page Store Section Type'])



		);
		//print $sql;
		mysql_query($sql);

	}


	function get_last_basket_page() {
		$page_key=0;
		$sql=sprintf("select `Page Key` from `Order Basket History Dimension` where `Order Key`=%d and `Page Store Section Type`!='System' order by `Date` desc limit 1 ",
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$page_key=$row['Page Key'];
		}
		return $page_key;
	}


	function get_items_info() {
		$items_info=array();
		$sql=sprintf("select (select `Page Key` from `Page Product Dimension` B  where B.`State`='Online' and  B.`Product ID`=OTF.`Product ID` limit 1 ) `Page Key`,(select `Page URL` from `Page Product Dimension` B left join `Page Dimension`  PA  on (PA.`Page Key`=B.`Page Key`) where B.`State`='Online' and  B.`Product ID`=OTF.`Product ID` limit 1 ) `Page URL`,`Order Last Updated Date`,`Order Date`,`Order Bonus Quantity`,`Order Quantity`,`Order Transaction Gross Amount`,`Order Currency Code`,`Order Transaction Total Discount Amount`,OTF.`Product ID`,OTF.`Product Code`,`Product XHTML Short Description`,`Product Tariff Code`,(select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info` from `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)  where `Order Key`=%d order by OTF.`Product Code` ",
			$this->id

		);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			if ($row['Page URL']!='') {
				$code=sprintf('<a href="%s">%s</a>', $row['Page URL'], $row['Product Code']);
				$code=sprintf('<a href="page.php?id=%d">%s</a>', $row['Page Key'], $row['Product Code']);
			}else {
				$code=$row['Product Code'];
			}

			if ($row['Deal Info']) {
				$deal_info='<br/><span style="font-style:italics;color:#555555;font-size:90%">'.$row['Deal Info'].($row['Order Transaction Total Discount Amount']?', <span style="font-weight:800">-'.money($row['Order Transaction Total Discount Amount'], $row['Order Currency Code']).'</span>':'').'</span>';
			}else {
				$deal_info='';
			}


			$qty=number($row['Order Quantity']);
			if ($row['Order Bonus Quantity']!=0) {
				if ($row['Order Quantity']!=0) {
					$qty.='<br/> +'.number($row['Order Bonus Quantity']).' '._('free');
				}else {
					$qty=number($row['Order Bonus Quantity']).' '._('free');
				}
			}

			$items_info[]=array(
				'pid'=>$row['Product ID'],
				'code'=>$code,
				'code_plain'=>$row['Product Code'],
				'description'=>$row['Product XHTML Short Description'].$deal_info,
				'tariff_code'=>$row['Product Tariff Code'],
				'quantity'=>$qty,
				'gross'=>money($row['Order Transaction Gross Amount'], $row['Order Currency Code']),
				'discount'=>money($row['Order Transaction Total Discount Amount'], $row['Order Currency Code']),
				'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'], $row['Order Currency Code']),
				'created'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($row['Order Date'].' +0:00')),
				'last_updated'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($row['Order Last Updated Date'].' +0:00'))

			);

		}

		return $items_info;
	}


	function get_name_for_grettings() {

		if ($this->data['Order Customer Name']=='' and $this->data['Order Customer Contact Name']=='')
			return _('Customer');
		$greeting=$this->data['Order Customer Contact Name'];
		if ($greeting and $this->data['Order Customer Name']!=$this->data['Order Customer Contact Name']) {
			$greeting.=', '.$this->data['Order Customer Name'];
		}


		return $greeting;
	}



	function update_payment_state() {
		$payments_amount=0;
		$payments_info='';
		$number_payments=0;
		$sql=sprintf("select * from `Payment Dimension` P left join `Payment Service Provider Dimension` PSPD on (P.`Payment Service Provider Key`=PSPD.`Payment Service Provider Key`) where `Payment Order Key`=%d and `Payment Transaction Status`='Completed'", $this->id);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			//print_r($row);
			$number_payments++;
			$payments_amount+=$row['Payment Amount'];

			$payments_info.=sprintf('<div>%s (%s)',

				$row['Payment Service Provider Name'],
				money($row['Payment Amount'], $row['Payment Currency Code'])

			);
			if ($row['Payment Transaction ID']!='')
				$payments_info.=sprintf(', %s: %s',
					_('Reference'),
					$row['Payment Transaction ID']

				);
			$payments_info.='</div>';

		}
		// print $payments_amount.' '.$this->data['Order Balance Total Amount'].' XXXm';


		$sql=sprintf("select * from `Payment Dimension` P left join `Payment Service Provider Dimension` PSPD on (P.`Payment Service Provider Key`=PSPD.`Payment Service Provider Key`) where `Payment Order Key`=%d and `Payment Transaction Status`='Pending' and P.`Payment Method`='Account'", $this->id);
		//print $sql;
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$number_payments++;
			$payments_amount+=$row['Payment Amount'];

			$payments_info.=sprintf('<div>%s (%s)',

				$row['Payment Service Provider Name'],
				money($row['Payment Amount'], $row['Payment Currency Code'])

			);
			if ($row['Payment Transaction ID']!='')
				$payments_info.=sprintf(', %s: %s',
					_('Reference'),
					$row['Payment Transaction ID']

				);
			$payments_info.='</div>';

		}


		$payments_amount=round($payments_amount, 2);

		$this->data['Order Balance Total Amount']=round($this->data['Order Balance Total Amount'], 2);

		//  print $payments_amount.' '.$this->data['Order Balance Total Amount'].' XXXm';



		if ($payments_amount==$this->data['Order Balance Total Amount']) {
			$payment_state='Paid';
		}elseif ($payments_amount<$this->data['Order Balance Total Amount']) {
			$payment_state='Partially Paid';

		}elseif ($payments_amount>$this->data['Order Balance Total Amount']) {
			$payment_state='Paid';

		}


		if (!$number_payments) {
			$payment_state='Waiting Payment';

			$payments_info=_('Waiting payment');

		}

		$this->data['Order Current Payment State']=$payment_state;
		$this->data['Order Current XHTML Payment State']=$payments_info;

		$this->data['Order Payments Amount']=$payments_amount;


		$this->data['Order To Pay Amount']=round($this->data['Order Balance Total Amount']-$payments_amount, 2);


		$sql=sprintf("update `Order Dimension` set `Order Current Payment State`=%s ,`Order Current XHTML Payment State`=%s , `Order Payments Amount`=%.2f ,`Order To Pay Amount`=%.2f where `Order Key`=%d  "
			, prepare_mysql($this->data['Order Current Payment State'])
			, prepare_mysql($this->data['Order Current XHTML Payment State'])
			, $this->data['Order Payments Amount']
			, $this->data['Order To Pay Amount']
			, $this->id);
		mysql_query($sql);

		$sql = sprintf( "update `Order Transaction Fact` set `Current Payment State`=%s where `Order Key`=%d ",
			prepare_mysql($this->data['Order Current Payment State']),
			$this->id );
		mysql_query( $sql );


		//  print "$sql\n";

	}



	function get_pending_payment_amount_from_account_balance() {
		$pending_amount=0;
		$sql=sprintf("select `Amount` from `Order Payment Bridge` where `Is Account Payment`='Yes' and `Order Key`=%d ",
			$this->id

		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$pending_amount=$row['Amount'];

		}
		return $pending_amount;
	}


	function get_formatted_pending_payment_amount_from_account_balance() {
		return money($this->get_pending_payment_amount_from_account_balance(), $this->data['Order Currency']);
	}


	function apply_payment_from_customer_account() {

		include_once 'class.Payment_Account.php';
		include_once 'class.Payment.php';


		$order_amount=$this->data['Order Balance Total Amount'];
		$sql=sprintf("select sum(`Amount`) as amount  from `Order Payment Bridge` B left join `Payment Dimension` P on (P.`Payment Key`=B.`Payment Key`)   where  `Payment Transaction Status`='Completed'  and `Order Key`=%d ",
			$this->id

		);
		$current_amount_completed_payments=0.00;
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {

			$current_amount_completed_payments=$row['amount'];

		}



		$order_amount=round($order_amount-$current_amount_completed_payments, 2);

		if ($order_amount<=0) {

			$current_amount_in_customer_account_payments=0;
			$sql=sprintf("select P.`Payment Key`,`Amount` from `Order Payment Bridge` B left join `Payment Dimension` P on (P.`Payment Key`=B.`Payment Key`)   where  `Payment Transaction Status`='Pending' and  `Is Account Payment`='Yes' and `Order Key`=%d ",
				$this->id

			);

			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {

				$_payment_key=$row['Payment Key'];
				$current_amount_in_customer_account_payments=$row['Amount'];

			}else {


				return;
			}

			$_balance=$order_amount+$current_amount_in_customer_account_payments;


			$payment=new Payment($_payment_key);
			$customer = new Customer($payment->data['Payment Customer Key']);
			$customer->update_field_switcher('Customer Account Balance', round($customer->data['Customer Account Balance']+$payment->data['Payment Amount'], 2));

			$sql=sprintf("delete from `Payment Dimension` where `Payment Key`=%d", $payment->id);
			mysql_query($sql);
			$sql=sprintf("delete from `Order Payment Bridge` where `Payment Key`=%d", $payment->id);
			mysql_query($sql);

			$this->update_totals();
			$this->update_payment_state();








			return;
		}




		if ($this->data['Order Apply Auto Customer Account Payment']=='Yes') {


			$customer=new Customer($this->data['Order Customer Key']);
			$original_customer_balance=$customer->data['Customer Account Balance'];


			$sql=sprintf("select `Amount` from `Order Payment Bridge` B left join `Payment Dimension` P on (P.`Payment Key`=B.`Payment Key`)   where  `Payment Transaction Status`='Pending' and  `Is Account Payment`='Yes' and `Order Key`=%d ",
				$this->id

			);

			$res=mysql_query($sql);
			if ($row=mysql_fetch_assoc($res)) {

				$current_amount_in_customer_account_payments=$row['Amount'];

			}else {

				$current_amount_in_customer_account_payments=0;
			}



			$customer_account_available_amount=round($current_amount_in_customer_account_payments+$original_customer_balance, 2);

			if ($customer_account_available_amount) {

				//print "CAA: $customer_account_available_amount  $order_amount \n";
				if ($customer_account_available_amount==$order_amount) {
					$payment_amount=$order_amount;

				}
				elseif ($customer_account_available_amount>$order_amount) {
					$payment_amount=$order_amount;

				}
				else {

					$payment_amount=$customer_account_available_amount;
				}


				$store=new Store($this->data['Order Store Key']);
				$payment_account_key=$store->data['Store Customer Payment Account Key'];
				$payment_account=new Payment_Account($payment_account_key);

				$payment_key=0;
				$sql=sprintf("select B.`Payment Key` from `Order Payment Bridge` B left join `Payment Dimension` P   on (P.`Payment Key`=B.`Payment Key`)  where  `Payment Transaction Status`='Pending' and  `Is Account Payment`='Yes' and `Order Key`=%d ",
					$this->id

				);
				$res=mysql_query($sql);
				if ($row=mysql_fetch_assoc($res)) {
					$payment_key=$row['Payment Key'];

				}



				if ($payment_key) {
					$payment=new Payment($payment_key);

					$data_to_update=array(
						'Payment Created Date'=>gmdate('Y-m-d H:i:s'),
						'Payment Last Updated Date'=>gmdate('Y-m-d H:i:s'),
						'Payment Balance'=>$payment_amount,
						'Payment Amount'=>$payment_amount

					);

					// print_r($data_to_update);

					$payment->update($data_to_update);

				}
				else {
					$payment_data=array(
						'Payment Account Key'=>$payment_account->id,
						'Payment Account Code'=>$payment_account->data['Payment Account Code'],

						'Payment Service Provider Key'=>$payment_account->data['Payment Service Provider Key'],
						'Payment Order Key'=>$this->id,
						'Payment Store Key'=>$this->data['Order Store Key'],
						'Payment Site Key'=>$this->data['Order Site Key'],
						'Payment Customer Key'=>$this->data['Order Customer Key'],

						'Payment Balance'=>$payment_amount,
						'Payment Amount'=>$payment_amount,
						'Payment Refund'=>0,
						'Payment Method'=>'Account',
						'Payment Currency Code'=>$this->data['Order Currency'],
						'Payment Created Date'=>gmdate('Y-m-d H:i:s'),
						'Payment Random String'=>md5(mt_rand().date('U')),
						'Payment Submit Type'=>'AutoCredit',
						'Payment User Key'=>''

					);

					$payment=new Payment('create', $payment_data);

					// print_r($payment);
					//exit;
				}

				$sql=sprintf("insert into `Order Payment Bridge` values (%d,%d,%d,%d,%.2f,'Yes') ON DUPLICATE KEY UPDATE `Amount`=%.2f ",
					$this->id,
					$payment->id,
					$payment_account->id,
					$payment_account->data['Payment Service Provider Key'],
					$payment->data['Payment Amount'],
					$payment->data['Payment Amount']
				);
				mysql_query($sql);
				// print $sql;
				//  exit;


				$this->update(
					array(
						'Order Payments Amount'=>$payment->data['Payment Amount']


					));


				$customer->update(
					array(
						'Customer Account Balance'=>round($customer_account_available_amount-$payment->data['Payment Amount'], 2)


					), 'no_history');




				$this->update_payment_state();


			}



		}else {



		}

	}


	function get_date($field) {
		return strftime("%e %b %Y", strtotime($this->data[$field].' +0:00'));
	}


	function get_replacement_public_id($dn_id, $suffix_counter='') {
		$sql=sprintf("select `Delivery Note ID` from `Delivery Note Dimension` where `Delivery Note Store Key`=%d and `Delivery Note ID`=%s ",
			$this->data['Order Store Key'],
			prepare_mysql($dn_id.$suffix_counter)
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($suffix_counter>100) {
				return $dn_id.$suffix_counter;
			}

			if (!$suffix_counter) {
				$suffix_counter=2;
			}else {
				$suffix_counter++;
			}

			return $this->get_replacement_public_id($dn_id, $suffix_counter);

		}else {
			return $dn_id.$suffix_counter;
		}

	}


	function get_refund_public_id($refund_id, $suffix_counter='') {
		$sql=sprintf("select `Invoice Public ID` from `Invoice Dimension` where `Invoice Store Key`=%d and `Invoice Public ID`=%s ",
			$this->data['Order Store Key'],
			prepare_mysql($refund_id.$suffix_counter)
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			if ($suffix_counter>100) {
				return $refund_id.$suffix_counter;
			}

			if (!$suffix_counter) {
				$suffix_counter=2;
			}else {
				$suffix_counter++;
			}

			return $this->get_refund_public_id($refund_id, $suffix_counter);

		}else {
			return $refund_id.$suffix_counter;
		}

	}


	function get_to_refund_amount() {
		$to_refund_amount=0;

		foreach ($this->get_invoices_objects() as $invoice) {
			$current_invoice_key=$invoice->id;

			if ($invoice->data['Invoice Type']=='Refund') {
				$to_refund_amount+=$invoice->data['Invoice Outstanding Total Amount'];
			}

		}

		return $to_refund_amount;

	}




	function remove_out_of_stocks_from_basket($product_pid) {




		$sql=sprintf("select `Order Transaction Fact Key`,`Order Quantity`,`Product Key`,`Product ID`,`Order Transaction Amount` from `Order Transaction Fact` where `Current Dispatching State`='In Process by Customer' and  `Product ID`=%d and `Order Key`=%d ",
			$product_pid,
			$this->id
		);

		$res=mysql_query($sql);

		$affected_rows=0;

		while ($row=mysql_fetch_assoc($res)) {


			$sql=sprintf('insert into `Order Transaction Out of Stock in Basket Bridge` (`Order Transaction Fact Key`,`Date`,`Store Key`,`Order Key`,`Product Key`,`Product ID`,`Quantity`,`Amount`) values (%d,%s,%d,%d,%d,%d,%f,%.2f)',
				$row['Order Transaction Fact Key'],
				prepare_mysql(gmdate('Y-m-d H:i:s')),
				$this->data['Order Store Key'],
				$this->id,
				$row['Product Key'],
				$row['Product ID'],
				$row['Order Quantity'],
				$row['Order Transaction Amount']
			);

			mysql_query($sql);



			$sql=sprintf('update `Order Transaction Fact` set `Current Dispatching State`=%s,`Order Quantity`=0,`Order Bonus Quantity`=0 ,`Order Transaction Gross Amount`=0 ,`Order Transaction Total Discount Amount`=0,`Order Transaction Amount`=0 where `Order Transaction Fact Key`=%d   ',
				prepare_mysql('Out of Stock in Basket'),
				$row['Order Transaction Fact Key']
			);
			mysql_query($sql);

			$affected_rows++;

		}

		if ($affected_rows) {
			$dn_key=0;

			$this->update_number_products();
			$this->update_insurance();

			$this->update_discounts_items();
			$this->update_totals();



			$this->update_shipping($dn_key, false);
			$this->update_charges($dn_key, false);
			$this->update_discounts_no_items($dn_key);


			$this->update_deal_bridge();

			$this->update_deals_usage();

			$this->update_totals();


			$this->update_number_products();

			$this->apply_payment_from_customer_account();
		}



	}


	function restore_back_to_stock_to_basket($product_pid) {

		if ($this->data['Order Current Dispatch State']!='In Process by Customer') {
			return;
		}

$affected_rows=0;;

		$sql=sprintf("select `Order Transaction Fact Key`,`Quantity` from `Order Transaction Out of Stock in Basket Bridge` where  `Product ID`=%d and `Order Key`=%d ",
			$product_pid,
			$this->id
		);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$product=new Product('id', $product_pid);

			$gross=$row['Quantity']*$product->data['Product Price'];

			$sql=sprintf('update `Order Transaction Fact` set `Current Dispatching State`=%s,`Order Quantity`=%d,`No Shipped Due Out of Stock`=0,`Order Transaction Gross Amount`=%.2f ,`Order Transaction Total Discount Amount`=%.2f,`Order Transaction Amount`=%.2f  where `Order Transaction Fact Key`=%d   ',
				prepare_mysql('In Process by Customer'),
				$row['Quantity'],
				$gross,
				0,
				$gross,
				$row['Order Transaction Fact Key']
			);

			mysql_query($sql);

			$sql=sprintf('delete from `Order Transaction Out of Stock in Basket Bridge` where `Order Transaction Fact Key`=%d',
				$row['Order Transaction Fact Key']
			);
			mysql_query($sql);
			
			$affected_rows++;
		}


		$dn_key=0;

		$this->update_number_products();
		$this->update_insurance();

		$this->update_discounts_items();
		$this->update_totals();



		$this->update_shipping($dn_key, false);
		$this->update_charges($dn_key, false);
		$this->update_discounts_no_items($dn_key);


		$this->update_deal_bridge();

		$this->update_deals_usage();

		$this->update_totals();


		$this->update_number_products();

		$this->apply_payment_from_customer_account();




	}




}


?>
