<?php
/*
 File: Invoice.php

 This file contains the Invoice Class

 Each invoice has to be associated with a contact if no contac data is provided when the Invoice is

 eated an anonimous contact will be created as well.


 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once 'class.DB_Table.php';

include_once 'class.Order.php';
include_once 'class.Category.php';

include_once 'class.DeliveryNote.php';

/* class: Invoice
 Class to manage the *Invoice Dimension* table
*/


class Invoice extends DB_Table {

	/*
     Constructor: Invoice
     Initializes the class, trigger  Search/Load/Create for the data set

     If first argument is find it will try to match the data or create if not found

     Parameters:
     arg1 -    Tag for the Search/Load/Create Options *or* the Contact Key for a simple object key search
     arg2 -    (optional) Data used to search or create the object

     Returns:
     void

     Example:
     (start example)
     // Load data from `Invoice Dimension` table where  `Invoice Key`=3
     $key=3;
     $invoice = New Invoice($key);

     // Load data from `Invoice Dimension` table where  `Invoice`='raul@gmail.com'
     $invoice = New Invoice('raul@gmail.com');



    */
	function Invoice($arg1=false,$arg2=false,$arg3=false,$arg4=false) {

		$this->table_name='Invoice';
		$this->ignore_fields=array('Invoice Key');
		$this->update_customer=true;

		if (!$arg1 and !$arg2) {
			$this->error=true;
			$this->msg='No data provided';
			return;
		}
		if (is_numeric($arg1)) {
			$this->get_data('id',$arg1);
			return;
		}
		if (preg_match('/create.*refund/i',$arg1)) {
			$this->create_refund($arg2,$arg3,$arg4);
			return;
		}

		if (preg_match('/create|new/i',$arg1)) {
			$this->create($arg2);
			return;
		}
		//   if(preg_match('/find/i',$arg1)){
		//  $this->find($arg2,$arg1);
		//  return;
		// }
		$this->get_data($arg1,$arg2);
	}

	function get_data($tipo,$tag) {
		if ($tipo=='id')
			$sql=sprintf("select * from `Invoice Dimension` where  `Invoice Key`=%d",$tag);
		elseif ($tipo=='public_id' )
			$sql=sprintf("select * from `Invoice Dimension` where  `Invoice Public ID`=%s",prepare_mysql($tag));
		else
			return;
		//print $sql;

		$result=mysql_query($sql);
		if ($this->data=mysql_fetch_array($result, MYSQL_ASSOC)   )
			$this->id=$this->data['Invoice Key'];
	}



	private function find($raw_data,$options='') {

	}

	function next_public_id() {



		$sql=sprintf("UPDATE `Store Dimension` SET `Store Order Last Order ID` = LAST_INSERT_ID(`Store Order Last Order ID` + 1) where `Store Key`=%d"
			,$this->data['Invoice Store Key']);
		mysql_query($sql);




		$public_id=mysql_insert_id();


		$this->data['Invoice Public ID']=sprintf($this->public_id_format,$public_id);
		$this->data['Invoice File As']=$this->prepare_file_as($this->data['Invoice Public ID']);
	}

	function create_refund($invoice_data) {



		$this->data=$this->base_data();
		$this->data ['Invoice Type']='Refund';

		if (!isset($invoice_data['Invoice Date'])   ) {
			$this->data ['Invoice Date']=gmdate("Y-m-d H:i:s");
		}

		$customer=$this->set_data_from_customer($invoice_data['Invoice Customer Key'],$invoice_data['Invoice Store Key']);
		foreach ($invoice_data as $key=>$value) {
			if (array_key_exists($key,$this->data)) {
				$this->data[$key]=_trim($value);
			}
		}

		if (array_key_exists('Invoice Sales Representative Keys',$invoice_data)) {
			$this->data ['Invoice Sales Representative Keys']=$invoice_data['Invoice Sales Representative Keys'];
		}else {
			$this->data ['Invoice Sales Representative Keys'] =array($this->editor['User Key']);
		}

		if (array_key_exists('Invoice Processed By Keys',$invoice_data)) {
			$this->data ['Invoice Processed By Keys']=$invoice_data['Invoice Processed By Keys'];
		}else {
			$this->data ['Invoice Processed By Keys'] =array($this->editor['User Key']);
		}

		if (array_key_exists('Invoice Charged By Keys',$invoice_data)) {
			$this->data ['Invoice Charged By Keys']=$invoice_data['Invoice Charged By Keys'];
		}else {
			$this->data ['Invoice Charged By Keys'] =array($this->editor['User Key']);
		}

if (array_key_exists('Invoice Tax Number',$invoice_data)) {
			$this->data ['Invoice Tax Number'] =$invoice_data['Invoice Tax Number'];
		}
		if (array_key_exists('Invoice Tax Number Valid',$invoice_data)) {
			$this->data ['Invoice Tax Number Valid'] =$invoice_data['Invoice Tax Number Valid'];
		}
		if (array_key_exists('Invoice Tax Number Validation Date',$invoice_data)) {
			$this->data ['Invoice Tax Number Validation Date'] =$invoice_data['Invoice Tax Number Validation Date'];
		}
		if (array_key_exists('Invoice Tax Number Associated Name',$invoice_data)) {
			$this->data ['Invoice Tax Number Associated Name'] =$invoice_data['Invoice Tax Number Associated Name'];
		}
		if (array_key_exists('Invoice Tax Number Associated Address',$invoice_data)) {
			$this->data ['Invoice Tax Number Associated Address'] =$invoice_data['Invoice Tax Number Associated Address'];
		}

		if ( array_key_exists('Invoice Billing To Key',$invoice_data)) {
			$billing_to=new Billing_To($invoice_data['Invoice Billing To Key']);
		} else {
			$billing_to=$customer->get_billing_to($this->data ['Invoice Date']);
		}


		$this->data ['Invoice Billing To Key'] =$billing_to->id;
		$this->data ['Invoice XHTML Address'] =$billing_to->data['Billing To XHTML Address'];
		$this->data ['Invoice Billing Country 2 Alpha Code'] = ($billing_to->data['Billing To Country 2 Alpha Code']==''?'XX':$billing_to->data['Billing To Country 2 Alpha Code']);

		$this->data ['Invoice Billing Country Code']=($billing_to->data['Billing To Country Code']==''?'UNK':$billing_to->data['Billing To Country Code']);
		$this->data ['Invoice Billing World Region Code']=$billing_to->get('World Region Code');
		$this->data ['Invoice Billing Town']=$billing_to->data['Billing To Town'];
		$this->data ['Invoice Billing Postal Code']=$billing_to->data['Billing To Postal Code'];


		if (array_key_exists('Invoice Public ID',$invoice_data) and $this->data['Invoice Public ID']!='') {
			$this->data['Invoice File As']=$this->prepare_file_as($this->data['Invoice Public ID']);


		}else {
			$this->next_public_id();
		}


		$this->data ['Invoice Currency Exchange']=1;
		$sql=sprintf("select `Account Currency` from `Account Dimension`");
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$corporation_currency_code=$row['Account Currency'];
		} else {
			$corporation_currency_code='GBP';
		}
		if ($this->data ['Invoice Currency']!=$corporation_currency_code) {
			$currency_exchange = new CurrencyExchange($this->data ['Invoice Currency'].$corporation_currency_code,$this->data['Invoice Date']);
			$exchange= $currency_exchange->get_exchange();
			$this->data ['Invoice Currency Exchange']=$exchange;
		}


		$this->create_header();

		if (count( $this->data ['Invoice Sales Representative Keys'])==0) {
			$sql = sprintf( "insert into `Invoice Sales Representative Bridge` values (%d,0,1)", $this->id);
			mysql_query($sql);
		}else {
			$share=1/count( $this->data ['Invoice Sales Representative Keys']);
			foreach ( $this->data ['Invoice Sales Representative Keys'] as $sale_rep_key ) {
				$sql = sprintf( "insert into `Invoice Sales Representative Bridge` values (%d,%d,%f)", $this->id, $sale_rep_key ,$share);
				mysql_query($sql);
			}
		}




		if (isset( $invoice_data['Order Key']) and $invoice_data['Order Key']) {
			$sql = sprintf( "insert into `Order Invoice Bridge` values (%d,%d)", $invoice_data['Order Key'], $this->id );
			mysql_query( $sql );
			$this->update_xhtml_orders();
		}

		$this->update_xhtml_orders();
		$this->categorize();
		$this->update_title();
	}

	protected function create($invoice_data) {




		$this->data=$this->base_data();
		$customer=$this->set_data_from_customer($invoice_data['Invoice Customer Key'],$invoice_data['Invoice Store Key']);

		foreach ($invoice_data as $key=>$value) {
			if (array_key_exists($key,$this->data)) {
				$this->data[$key]=_trim($value);
			}
		}



		if (array_key_exists('Invoice Sales Representative Keys',$invoice_data)) {
			$this->data ['Invoice Sales Representative Keys']=$invoice_data['Invoice Sales Representative Keys'];
		}else {
			$this->data ['Invoice Sales Representative Keys'] =array($this->editor['User Key']);
		}

		if (array_key_exists('Invoice Processed By Keys',$invoice_data)) {
			$this->data ['Invoice Processed By Keys']=$invoice_data['Invoice Processed By Keys'];
		}else {
			$this->data ['Invoice Processed By Keys'] =array($this->editor['User Key']);
		}

		if (array_key_exists('Invoice Charged By Keys',$invoice_data)) {
			$this->data ['Invoice Charged By Keys']=$invoice_data['Invoice Charged By Keys'];
		}else {
			$this->data ['Invoice Charged By Keys'] =array($this->editor['User Key']);
		}

		if (array_key_exists('Invoice Tax Number',$invoice_data)) {
			$this->data ['Invoice Tax Number'] =$invoice_data['Invoice Tax Number'];
		}
		if (array_key_exists('Invoice Tax Number Valid',$invoice_data)) {
			$this->data ['Invoice Tax Number Valid'] =$invoice_data['Invoice Tax Number Valid'];
		}
		if (array_key_exists('Invoice Tax Number Validation Date',$invoice_data)) {
			$this->data ['Invoice Tax Number Validation Date'] =$invoice_data['Invoice Tax Number Validation Date'];
		}
		if (array_key_exists('Invoice Tax Number Associated Name',$invoice_data)) {
			$this->data ['Invoice Tax Number Associated Name'] =$invoice_data['Invoice Tax Number Associated Name'];
		}
		if (array_key_exists('Invoice Tax Number Associated Address',$invoice_data)) {
			$this->data ['Invoice Tax Number Associated Address'] =$invoice_data['Invoice Tax Number Associated Address'];
		}


		if ($invoice_data['Invoice Billing To Key']) {
			$billing_to=new Billing_To($invoice_data['Invoice Billing To Key']);
		} else {
			$billing_to=$customer->get_billing_to($this->data ['Invoice Date']);
		}


		$this->data ['Invoice Billing To Key'] =$billing_to->id;
		$this->data ['Invoice XHTML Address'] =$billing_to->data['Billing To XHTML Address'];
		$this->data ['Invoice Billing Country 2 Alpha Code'] = ($billing_to->data['Billing To Country 2 Alpha Code']==''?'XX':$billing_to->data['Billing To Country 2 Alpha Code']);

		$this->data ['Invoice Billing Country Code']=($billing_to->data['Billing To Country Code']==''?'UNK':$billing_to->data['Billing To Country Code']);
		$this->data ['Invoice Billing World Region Code']=$billing_to->get('World Region Code');
		$this->data ['Invoice Billing Town']=$billing_to->data['Billing To Town'];
		$this->data ['Invoice Billing Postal Code']=$billing_to->data['Billing To Postal Code'];



		if (!isset($this->data['Invoice Public ID']) or $this->data['Invoice Public ID']=='') {
			$this->next_public_id();
		}else {
			$this->data['Invoice File As']=$this->prepare_file_as($this->data['Invoice Public ID']);
		}


		$exchange=1;
		$sql=sprintf("select `Account Currency` from `Account Dimension`");
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$corporation_currency_code=$row['Account Currency'];
		} else {
			$corporation_currency_code='GBP';
		}
		if ($this->data ['Invoice Currency']!=$corporation_currency_code) {

			//take off this and only use curret exchenge whan get rid off excel
			$date_difference=date('U')-strtotime($this->data['Invoice Date'].' +0:00');
			if ($date_difference>3600) {
				$currency_exchange = new CurrencyExchange($this->data ['Invoice Currency'].$corporation_currency_code,$this->data['Invoice Date']);
				$exchange= $currency_exchange->get_exchange();
			}else {
				$exchange=currency_conversion($this->data ['Invoice Currency'],$corporation_currency_code,'now');
			}

		}



		$this->create_header();

		if (count( $this->data ['Invoice Sales Representative Keys'])==0) {
			$sql = sprintf( "insert into `Invoice Sales Representative Bridge` values (%d,0,1)", $this->id);
			mysql_query($sql);
		}else {
			$share=1/count( $this->data ['Invoice Sales Representative Keys']);
			foreach ( $this->data ['Invoice Sales Representative Keys'] as $sale_rep_key ) {
				$sql = sprintf( "insert into `Invoice Sales Representative Bridge` values (%d,%d,%f)", $this->id, $sale_rep_key ,$share);
				mysql_query($sql);
			}
		}


		$delivery_notes_ids=array();
		foreach (preg_split('/\,/',$invoice_data['Delivery Note Keys']) as $dn_key) {
			$delivery_notes_ids[$dn_key]=$dn_key;
		}
		$dn_keys=join(',',$delivery_notes_ids);
		$shipping_net=0;
		$shipping_tax=0;
		$charges_net=0;
		$charges_tax=0;
		$insurance_net=0;

		$insurance_tax=0;

		if ($dn_keys!='') {

			$tax_category=$this->data['Invoice Tax Code'];
			$sql=sprintf('select `Order Bonus Quantity`,`Product Key`,`Current Autorized to Sell Quantity`,`Transaction Tax Rate`,`Order Quantity`,`Delivery Note Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Order Transaction Fact Key`,`Product Key`,`Delivery Note Quantity` from `Order Transaction Fact` where `Delivery Note Key` in (%s) and ISNULL(`Invoice Key`)  '
				,$dn_keys);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {
				if ($row['Current Autorized to Sell Quantity']==0) {
					continue;
				}
				$factor_actually_packed=($row['Delivery Note Quantity']-$row['Order Bonus Quantity'])/$row['Current Autorized to Sell Quantity'];


				$gross=$row['Order Transaction Gross Amount']*$factor_actually_packed;
				$discount=$row['Order Transaction Total Discount Amount']*$factor_actually_packed;
				$net=$gross-$discount;
				$tax=round($net*$row['Transaction Tax Rate'],3);

				$sql=sprintf("update `Order Transaction Fact` set
					`Invoice Currency Exchange Rate`=%f,
					`Invoice Date`=%s,
					`Invoice Currency Code`=%s,
					`Invoice Key`=%d,
					`Invoice Public ID`=%s,
					`Invoice Quantity`=%f,
					`Invoice Transaction Gross Amount`=%.2f,
					`Invoice Transaction Total Discount Amount`=%.2f,
					`Invoice Transaction Item Tax Amount`=%.3f,
					`Invoice Transaction Outstanding Net Balance`=%.2f,
					`Invoice Transaction Outstanding Tax Balance`=%.2f


						where `Order Transaction Fact Key`=%d",
					($this->data['Invoice Currency Exchange']==''?1:$this->data['Invoice Currency Exchange']),
					prepare_mysql($this->data['Invoice Date']),
					prepare_mysql($this->data['Invoice Currency']),
					$this->id,
					prepare_mysql($this->data['Invoice Public ID']),
					$row['Delivery Note Quantity'],

					$gross,
					$discount,
					$tax,
					$net,
					$tax,
					$row['Order Transaction Fact Key']
				);
				mysql_query($sql);
				//  print "$sql\n";
			}



		





		}


		$_orders_ids=array();

		if (array_key_exists('Orders Keys',$invoice_data)) {

			foreach (preg_split('/\,/',$invoice_data['Orders Keys']) as $order_key) {
				$_orders_ids[$order_key]=$order_key;
			}
		}
print_r($invoice_data);
		if (count($_orders_ids)) {
			$orders_keys=join(',',$_orders_ids);
			
			
			
				$sql=sprintf('select `Order No Product Transaction Fact Key`,`Transaction Net Amount`,`Transaction Tax Amount`,`Transaction Type`  from `Order No Product Transaction Fact` where `Order Key` in (%s) and ISNULL(`Invoice Key`) '
				,$order_keys);
			$res=mysql_query($sql);
			
			print $sql;
			while ($row=mysql_fetch_assoc($res)) {
				print_r($row);
				$sql=sprintf("update `Order No Product Transaction Fact` set
				`Invoice Date`=%s,
				`Invoice Key`=%d,
				`Transaction Invoice Net Amount`=%.2f,
				`Transaction Invoice Tax Amount`=%.2f,
				`Transaction Outstanding Net Amount Balance`=%.2f,
				`Transaction Outstanding Tax Amount Balance`=%.2f where `Order No Product Transaction Fact Key`=%d",
					prepare_mysql($this->data['Invoice Date']),
					$this->id,
					$row['Transaction Net Amount'],
					$row['Transaction Tax Amount'],
					$row['Transaction Net Amount'],
					$row['Transaction Tax Amount'],
					$row['Order No Product Transaction Fact Key']
				);
				mysql_query($sql);


				if ($row['Transaction Type']=='Shipping') {

					$shipping_net+=$row['Transaction Net Amount'];
					$shipping_tax+=$row['Transaction Tax Amount'];
				}

				if ($row['Transaction Type']=='Charges') {

					$charges_net+=$row['Transaction Net Amount'];
					$charges_tax+=$row['Transaction Tax Amount'];
				}

				if ($row['Transaction Type']=='Insurance') {

					$insurance_net+=$row['Transaction Net Amount'];
					$insurance_tax+=$row['Transaction Tax Amount'];
				}

				//  print $sql;
			}
			
			
			
			
			$sql=sprintf('select *  from `Order No Product Transaction Fact` where `Order Key` in (%s) and ISNULL(`Invoice Key`) '
				,$orders_keys);
			$res=mysql_query($sql);
			while ($row=mysql_fetch_assoc($res)) {

				$sql=sprintf("update `Order No Product Transaction Fact` set `Invoice Date`=%s,`Invoice Key`=%d,`Transaction Invoice Net Amount`=%.2f,`Transaction Invoice Tax Amount`=%.2f,`Transaction Outstanding Net Amount Balance`=%.2f,`Transaction Outstanding Tax Amount Balance`=%.2f where `Order No Product Transaction Fact Key`=%d",
					prepare_mysql($this->data['Invoice Date']),
					$this->id,
					$row['Transaction Net Amount'],
					$row['Transaction Tax Amount'],
					$row['Transaction Net Amount'],
					$row['Transaction Tax Amount'],
					$row['Order No Product Transaction Fact Key']
				);

				//print $sql;
				mysql_query($sql);



			}

		}






		$sql=sprintf("update `Invoice Dimension` set `Invoice Charges Net Amount`=%f,`Invoice Charges Tax Amount`=%f where `Invoice Key`=%d",
			$charges_net,
			$charges_tax,
			$this->id
		);
		mysql_query($sql);
		$this->data['Invoice Charges Net Amount']=$charges_net;
		$this->data['Invoice Charges Tax Amount']=$charges_tax;

		$this->distribute_charges_over_the_otf();


		$sql=sprintf("update `Invoice Dimension` set `Invoice Shipping Net Amount`=%f,`Invoice Shipping Tax Amount`=%f where `Invoice Key`=%d",
			$shipping_net,
			$shipping_tax,
			$this->id
		);
		mysql_query($sql);
		$this->data['Invoice Shipping Net Amount']=$shipping_net;
		$this->data['Invoice Shipping Tax Amount']=$shipping_tax;

		$this->distribute_shipping_over_the_otf();


		$sql=sprintf("update `Invoice Dimension` set `Invoice Insurance Net Amount`=%f,`Invoice Insurance Tax Amount`=%f where `Invoice Key`=%d",
			$insurance_net,
			$insurance_tax,
			$this->id
		);
		mysql_query($sql);
		$this->data['Invoice Insurance Net Amount']=$insurance_net;
		$this->data['Invoice Insurance Tax Amount']=$insurance_tax;

		$this->distribute_insurance_over_the_otf();

		$this->update_totals();

		//$this->update_shipping(array('Amount'=>$shipping_net,'Tax'=>$shipping_tax),true);
		//$this->update_charges(array('Transaction Invoice Net Amount'=>$charges_net,'Invoice Charges Tax Amount'=>$charges_tax,'Transaction Description'=>_('Charges')),true);

		$this->update_refund_totals();

		foreach ($this->get_delivery_notes_objects() as $key=>$dn) {
			$sql = sprintf( "insert into `Invoice Delivery Note Bridge` values (%d,%d)",  $this->id,$key);
			mysql_query( $sql );
			$this->update_xhtml_delivery_notes();
			$dn->update(array('Delivery Note Invoiced'=>'Yes'));
			$dn->update_xhtml_invoices();
		}

		foreach ($this->get_orders_objects() as $key=>$order) {
			$sql = sprintf( "insert into `Order Invoice Bridge` values (%d,%d)", $key, $this->id );
			mysql_query( $sql );
			$this->update_xhtml_orders();
			$order->update_xhtml_invoices();
			$order->update_no_normal_totals();
			$order->set_as_invoiced();
		}




		$this->categorize();
		$this->update_title();
		$this->update_xhtml_sale_representatives();
		//$this->update_xhtml_processed_by();
		//$this->update_xhtml_charged_by();

	}


	function update_xhtml_sale_representatives() {

		$xhtml_sale_representatives='';
		$tag='&view=csr';
		$sql=sprintf("select S.`Staff Key`,`Staff Alias` from `Invoice Sales Representative Bridge` B  left join `Staff Dimension` S on (B.`Staff Key`=S.`Staff Key`) where `Invoice Key`=%s",
			$this->id
		);
		//print $sql;
		$result = mysql_query($sql) or die('xx1  Query failed: ' . mysql_error());
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$id=$row['Staff Key'];
			$ids[$id]=$id;

			$xhtml_sale_representatives.=sprintf(', <a href="staff.php?id=%d%s">%s</a>',$id,$tag,mb_ucwords($row['Staff Alias']));

		}
		$xhtml_sale_representatives=preg_replace("/^\,\s*/","",$xhtml_sale_representatives);
		if ($xhtml_sale_representatives=='')
			$xhtml_sale_representatives=_('Unknown');

		$sql=sprintf("update `Invoice Dimension` set `Invoice XHTML Sales Representative`=%s where `Invoice Key`=%d",
			prepare_mysql($xhtml_sale_representatives),
			$this->id
		);
		//print $sql;
		mysql_query($sql);
	}

	function update_xhtml_processed_by() {

		$xhtml_sale_representatives='';
		$tag='&view=csr';
		$sql=sprintf("select S.`Staff Key`,`Staff Alias` from `Invoice Processed By Bridge` B  left join `Staff Dimension` S on (B.`Staff Key`=S.`Staff Key`) where `Invoice Key`=%s",
			$this->id
		);
		//print $sql;
		$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$id=$row['Staff Key'];
			$ids[$id]=$id;

			$xhtml_sale_representatives.=sprintf(', <a href="staff.php?id=%d%s">%s</a>',$id,$tag,mb_ucwords($row['Staff Alias']));

		}
		$xhtml_sale_representatives=preg_replace("/^\,\s*/","",$xhtml_sale_representatives);
		if ($xhtml_sale_representatives=='')
			$xhtml_sale_representatives=_('Unknown');

		$sql=sprintf("update `Invoice Dimension` set `Invoice XHTML Processed By`=%s where `Invoice Key`=%d",
			prepare_mysql($xhtml_sale_representatives),
			$this->id
		);
		//print $sql;
		mysql_query($sql);
	}

	function update_xhtml_charged_by() {

		$xhtml_sale_representatives='';
		$tag='&view=csr';
		$sql=sprintf("select S.`Staff Key`,`Staff Alias` from `Invoice Charged By Bridge` B  left join `Staff Dimension` S on (B.`Staff Key`=S.`Staff Key`) where `Invoice Key`=%s",
			$this->id
		);
		//print $sql;
		$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$id=$row['Staff Key'];
			$ids[$id]=$id;

			$xhtml_sale_representatives.=sprintf(', <a href="staff.php?id=%d%s">%s</a>',$id,$tag,mb_ucwords($row['Staff Alias']));

		}
		$xhtml_sale_representatives=preg_replace("/^\,\s*/","",$xhtml_sale_representatives);
		if ($xhtml_sale_representatives=='')
			$xhtml_sale_representatives=_('Unknown');

		$sql=sprintf("update `Invoice Dimension` set `Invoice XHTML Charged By`=%s where `Invoice Key`=%d",
			prepare_mysql($xhtml_sale_representatives),
			$this->id
		);
		//print $sql;
		mysql_query($sql);
	}


	function get_tax_rate($item) {
		$rate=0;
		switch ($item) {
		case 'shipping':
			$sql=sprintf("select `Tax Category Rate` from `Tax Category Dimension` where `Tax Category Code`=%s",
				prepare_mysql($this->data['Invoice Tax Shipping Code'])
			);
			$res=mysql_query($sql);

			$rate=0;
			if ($row=mysql_fetch_assoc($res)) {
				$rate=$row['Tax Category Rate'];
			}


			break;
		case('charges'):
			$sql=sprintf("select `Tax Category Rate` from `Tax Category Dimension` where `Tax Category Code`=%s",
				prepare_mysql($this->data['Invoice Tax Charges Code'])
			);
			$res=mysql_query($sql);
			$rate=0;
			if ($row=mysql_fetch_assoc($res)) {
				$rate=$row['Tax Category Rate'];
			}

			break;
		default:
			if (is_numeric($item)) {
				$sql=sprintf("select `Transaction Tax Code`,`Transaction Tax Rate`from `Order Transaction Fact` where `Order Transaction Fact Key`=%s",
					$item
				);
				$res2=mysql_query($sql);
				if ($row2['Transaction Tax Code']=='UNK') {
					$rate=$row2['Transaction Tax Rate'];
				} else {
					$rate=0;
					if ($row2=mysql_fetch_assoc($res2)) {

						$sql=sprintf("select `Tax Category Rate` from `Tax Category Dimension` where `Tax Category Code`=%s",
							prepare_mysql($row2['Transaction Tax Code'])
						);
						$res=mysql_query($sql);
						$rate=0;
						if ($row=mysql_fetch_assoc($res)) {
							$rate=$row['Tax Category Rate'];
						}
					}
				}
			}
			break;
		}

		return $rate;
	}


	function update_totals() {


		$shipping_net=0;
		$shipping_tax=0;
		$charges_net=0;
		$charges_tax=0;
		$insurance_tax=0;
		$insurance_net=0;
		$items_gross=0;
		$items_discounts=0;
		$items_net=0;
		$items_tax=0;
		$items_refund_net=0;
		$items_refund_tax=0;
		$items_net_outstanding_balance=0;
		$items_tax_outstanding_balance=0;
		$items_refund_net_outstanding_balance=0;
		$items_refund_tax_outstanding_balance=0;
		$deal_credit_net=0;
		$deal_credit_tax=0;
		$adjust_tax=0;
		$adjust_net=0;




		$sql = sprintf("select `Invoice Transaction Gross Amount`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Outstanding Net Balance`,`Invoice Transaction Outstanding Tax Balance`,`Invoice Transaction Outstanding Refund Net Balance`,`Invoice Transaction Outstanding Refund Tax Balance`,`Invoice Transaction Net Refund Amount`,`Invoice Transaction Tax Refund Amount`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Invoice Transaction Charges Amount`,`Invoice Transaction Charges Tax Amount`,`Invoice Transaction Shipping Amount`,`Invoice Transaction Shipping Tax Amount`,`Order Transaction Fact Key`,`Invoice Transaction Shipping Tax Amount`,`Invoice Transaction Charges Tax Amount`,(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as item_net ,`Invoice Transaction Item Tax Amount`
                       from `Order Transaction Fact` where `Invoice Key`=%d   " ,
			$this->data ['Invoice Key']);

		//  print $sql;
		// print "$\n";
		$counter=0;
		$result = mysql_query( $sql );
		while ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
			$counter++;
			$items_net+=$row['item_net'];
			$items_tax+=$row['Invoice Transaction Item Tax Amount'];
			$items_net_outstanding_balance+=$row['Invoice Transaction Outstanding Net Balance'];
			$items_tax_outstanding_balance+=$row['Invoice Transaction Outstanding Tax Balance'];


			$items_gross+=$row['Invoice Transaction Gross Amount'];
			$items_discounts+=$row['Invoice Transaction Total Discount Amount'];
		}





		$sql=sprintf("select * from `Order No Product Transaction Fact` where `Invoice Key`=%d",$this->id);

		$result = mysql_query( $sql );
		while ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
			if ($row['Transaction Type']=='Shipping') {
				$shipping_net+=$row['Transaction Invoice Net Amount'];
				$shipping_tax+=$row['Transaction Invoice Tax Amount'];
			} elseif ($row['Transaction Type']=='Charges') {
				$charges_net+=$row['Transaction Invoice Net Amount'];
				$charges_tax+=$row['Transaction Invoice Tax Amount'];
			} elseif ($row['Transaction Type']=='Insurance') {
				$insurance_tax+=$row['Transaction Invoice Net Amount'];
				$insurance_tax+=$row['Transaction Invoice Tax Amount'];
			} elseif ($row['Transaction Type']=='Adjust') {
				$adjust_net+=$row['Transaction Invoice Net Amount'];
				$adjust_tax+=$row['Transaction Invoice Tax Amount'];
			}  elseif ($row['Transaction Type']=='Deal') {
				$deal_credit_net+=$row['Transaction Invoice Net Amount'];
				$deal_credit_tax+=$row['Transaction Invoice Tax Amount'];
			}  elseif ($row['Transaction Type']=='Credit') {

				$items_refund_net+=$row['Transaction Invoice Net Amount'];
				$items_refund_tax+=$row['Transaction Invoice Tax Amount'];
				$items_refund_net_outstanding_balance+=$row['Transaction Outstanding Net Amount Balance'];
				$items_refund_tax_outstanding_balance+=$row['Transaction Outstanding Tax Amount Balance'];

			} else {


			}
		}
		$this->data['Invoice Total Net Adjust Amount']= $adjust_net;
		$this->data['Invoice Total Tax Adjust Amount']= $adjust_tax;
		$this->data['Invoice Total Adjust Amount']= $adjust_tax+$adjust_net;
		$this->data['Invoice Refund Net Amount']=$items_refund_net;
		$this->data['Invoice Refund Tax Amount']=$items_refund_tax;
		$this->data['Invoice Shipping Tax Amount']= $shipping_tax;
		$this->data['Invoice Shipping Net Amount']= $shipping_net;
		$this->data['Invoice Charges Tax Amount']= $charges_tax;
		$this->data['Invoice Charges Net Amount']= $charges_net;
		$this->data['Invoice Insurance Tax Amount']= $insurance_tax;
		$this->data['Invoice Insurance Net Amount']= $insurance_net;



		$this->data['Invoice Items Tax Amount']= $items_tax;
		$this->data['Invoice Items Net Amount']= $items_net;
		$this->data['Invoice Deal Credit Tax Amount']= $deal_credit_tax;
		$this->data['Invoice Deal Credit Net Amount']= $deal_credit_net;

		$this->data['Invoice Items Gross Amount']=$items_gross;
		$this->data['Invoice Items Discount Amount']=$items_discounts;



		$this->data['Invoice Total Net Amount']=$this->data['Invoice Deal Credit Net Amount']+$this->data['Invoice Refund Net Amount']+$this->data['Invoice Total Net Adjust Amount']+$this->data['Invoice Shipping Net Amount']+$this->data['Invoice Items Net Amount']+$this->data['Invoice Charges Net Amount']+$this->data['Invoice Insurance Net Amount'];
		$this->data['Invoice Total Tax Amount']=round($this->data['Invoice Deal Credit Tax Amount']+$this->data['Invoice Refund Tax Amount']+$this->data['Invoice Shipping Tax Amount']+$this->data['Invoice Items Tax Amount']+$this->data['Invoice Charges Tax Amount']+$this->data['Invoice Insurance Tax Amount']+$this->data['Invoice Total Tax Adjust Amount'],2);

		$this->data['Invoice Outstanding Net Balance']=$items_net_outstanding_balance+$items_refund_net_outstanding_balance;
		$this->data['Invoice Outstanding Tax Balance']=$items_tax_outstanding_balance+$items_refund_tax_outstanding_balance;

		$this->data['Invoice Total Amount']=$this->data['Invoice Total Net Amount']+$this->data['Invoice Total Tax Amount'];
		$this->data['Invoice Outstanding Total Amount']=$this->data['Invoice Total Amount']-$this->data['Invoice Paid Amount'];


		$total_costs=0;
		$sql=sprintf("select ifnull(sum(`Cost Supplier`/`Invoice Currency Exchange Rate`),0) as `Cost Supplier`  ,ifnull(sum(`Cost Storing`/`Invoice Currency Exchange Rate`),0) as `Cost Storing`,ifnull(sum(`Cost Handing`/`Invoice Currency Exchange Rate`),0)  as  `Cost Handing`,ifnull(sum(`Cost Shipping`/`Invoice Currency Exchange Rate`),0) as `Cost Shipping` from `Order Transaction Fact` where `Invoice Key`=%d",$this->id);

		$this->data ['Invoice Total Profit']=0;
		$result = mysql_query( $sql );
		if ($row = mysql_fetch_array( $result, MYSQL_ASSOC )) {
			$total_costs=$row['Cost Supplier']+$row['Cost Storing']+$row['Cost Handing']+$row['Cost Shipping'];

		}
		$this->data ['Invoice Total Profit']= $this->data ['Invoice Total Net Amount']- $this->data ['Invoice Refund Net Amount']-$total_costs;



		$sql=sprintf("update  `Invoice Dimension` set `Invoice Outstanding Total Amount`=%f,`Invoice Refund Net Amount`=%f,`Invoice Refund Tax Amount`=%f,`Invoice Total Net Adjust Amount`=%f,`Invoice Total Tax Adjust Amount`=%f,`Invoice Total Adjust Amount`=%f,`Invoice Outstanding Net Balance`=%f,`Invoice Outstanding Tax Balance`=%f,`Invoice Items Gross Amount`=%f,`Invoice Items Discount Amount`=%f ,`Invoice Items Net Amount`=%f,`Invoice Shipping Net Amount`=%f ,`Invoice Charges Net Amount`=%f ,`Invoice Total Net Amount`=%f ,`Invoice Items Tax Amount`=%f ,`Invoice Shipping Tax Amount`=%f,`Invoice Charges Tax Amount`=%f ,`Invoice Total Tax Amount`=%f,`Invoice Total Amount`=%f ,`Invoice Total Profit`=%f where `Invoice Key`=%d",
			$this->data['Invoice Outstanding Total Amount'],
			$this->data['Invoice Refund Net Amount'],
			$this->data['Invoice Refund Tax Amount'],
			$this->data['Invoice Total Net Adjust Amount'],
			$this->data['Invoice Total Tax Adjust Amount'],
			$this->data['Invoice Total Adjust Amount'],
			$this->data['Invoice Outstanding Net Balance'],
			$this->data['Invoice Outstanding Tax Balance'],
			$this->data['Invoice Items Gross Amount'],
			$this->data['Invoice Items Discount Amount'],
			$this->data['Invoice Items Net Amount'],
			$this->data['Invoice Shipping Net Amount'],
			$this->data['Invoice Charges Net Amount'],
			$this->data['Invoice Total Net Amount'],
			$this->data['Invoice Items Tax Amount'],
			$this->data['Invoice Shipping Tax Amount'],
			$this->data['Invoice Charges Tax Amount'],
			$this->data['Invoice Total Tax Amount'],
			$this->data['Invoice Total Amount'],

			$this->data ['Invoice Total Profit'],
			$this->id
		);
		mysql_query($sql);

		//print "$sql\n<br>";
		$this->update_tax();


	}

	function update_tax() {


		$sql=sprintf("delete from `Invoice Tax Bridge` where `Invoice Key`=%d",$this->id);
		mysql_query($sql);


		$invoice_tax_fields=array();
		$result = mysql_query("SHOW COLUMNS FROM `Invoice Tax Dimension`");
		if (mysql_num_rows($result) > 0) {
			while ($row = mysql_fetch_assoc($result)) {
				if ($row['Field']!='Invoice Key') {
					$invoice_tax_fields[]=$row['Field'];
				}
			}
		}


		$_sql='';
		foreach ($invoice_tax_fields as $invoice_tax_field) {
			$_sql.=", `".$invoice_tax_field."`=NULL ";
		}
		$_sql=preg_replace('/^,/','',$_sql);
		$sql='update `Invoice Tax Dimension` set '.$_sql.sprintf(' where `Invoice Key`=%d',$this->id);

		$tax_sum_by_code=array();

		$sql=sprintf("select IFNULL(`Transaction Tax Code`,'UNK') as tax_code,sum(`Invoice Transaction Item Tax Amount`) as amount from `Order Transaction Fact`  where `Invoice Key`=%d  group by `Transaction Tax Code`",$this->id);
		//print "$sql\n";
		$result = mysql_query( $sql );
		while ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
			$tax_sum_by_code[$row['tax_code']]=$row['amount'];
		}


		$sql=sprintf("select IFNULL(`Tax Category Code`,'UNK') as tax_code,sum(`Transaction Invoice Tax Amount`) as amount from `Order No Product Transaction Fact` where `Invoice Key`=%d and `Transaction Type`!='Adjust'  group by `Tax Category Code`",$this->id);
		// print "$sql\n";
		$result = mysql_query( $sql );
		while ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
			if (array_key_exists($row['tax_code'],$tax_sum_by_code))
				$tax_sum_by_code[$row['tax_code']]+=$row['amount'];
			else
				$tax_sum_by_code[$row['tax_code']]=$row['amount'];
		}

		// print_r($tax_sum_by_code);


		foreach ($tax_sum_by_code as $tax_code=>$amount ) {
			$tax_category=new TaxCategory($tax_code);
			if ($tax_category->data['Composite']=='Yes') {

				$sql=sprintf("select `Tax Category Rate`,`Tax Category Code` from `Tax Category Dimension` where `Tax Category Key` in (%s) ",$tax_category->data['Composite Metadata']);
				$res=mysql_query($sql);

				if ($tax_category->data['Tax Category Rate']==0) {
					contunue;
				}
				$x=$amount/$tax_category->data['Tax Category Rate'];


				if ($tax_sum_by_code[$tax_code]==$amount) {
					unset($tax_sum_by_code[$tax_code]);
				} else {
					$tax_sum_by_code[$tax_code]=$tax_sum_by_code[$tax_code]-$amount;
				}


				while ($row=mysql_fetch_assoc($res)) {


					if (array_key_exists($row['Tax Category Code'],$tax_sum_by_code))
						$tax_sum_by_code[$row['Tax Category Code']]+=$x*$row['Tax Category Rate'];
					else
						$tax_sum_by_code[$row['Tax Category Code']]=$x*$row['Tax Category Rate'];
				}



			}


		}




		// print_r($tax_sum_by_code);
		// exit;
		foreach ($tax_sum_by_code as $tax_code=>$amount ) {

			$this->add_tax_item($tax_code,$amount);
		}

		//print "\n\End updatinf  tax\n";

	}



	function update_refund_totals() {
		$shipping_net=0;
		$shipping_tax=0;
		$charges_net=0;
		$charges_tax=0;

		$items_gross=0;
		$items_discounts=0;
		$items_net=0;
		$items_tax=0;
		$items_refund_net=0;
		$items_refund_tax=0;
		$items_net_outstanding_balance=0;
		$items_tax_outstanding_balance=0;
		$items_refund_net_outstanding_balance=0;
		$items_refund_tax_outstanding_balance=0;
		$sql = sprintf("select `Invoice Transaction Outstanding Net Balance`,`Invoice Transaction Outstanding Tax Balance`,`Invoice Transaction Outstanding Refund Net Balance`,`Invoice Transaction Outstanding Refund Tax Balance`,`Invoice Transaction Net Refund Amount`,`Invoice Transaction Tax Refund Amount`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,`Invoice Transaction Charges Amount`,`Invoice Transaction Charges Tax Amount`,`Invoice Transaction Shipping Amount`,`Invoice Transaction Shipping Tax Amount`,`Order Transaction Fact Key`,`Invoice Transaction Shipping Tax Amount`,`Invoice Transaction Charges Tax Amount`,(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`) as item_net ,(`Order Transaction Gross Amount`-`Order Transaction Total Discount Amount`)*`Transaction Tax Rate` as tax_item from `Order Transaction Fact` where `Refund Key`=%d" ,
			$this->data ['Invoice Key']);
		//print $sql;
		$result = mysql_query( $sql );
		while ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {

			$items_refund_net+=$row['Invoice Transaction Net Refund Amount'];
			$items_refund_tax+=$row['Invoice Transaction Tax Refund Amount'];
			$items_refund_net_outstanding_balance+=$row['Invoice Transaction Outstanding Refund Net Balance'];
			$items_refund_tax_outstanding_balance+=$row['Invoice Transaction Outstanding Refund Tax Balance'];

		}
		//print "::::$items_refund_net--->  \n";

		$sql = sprintf("select * from `Order No Product Transaction Fact` where `Refund Key`=%d" ,
			$this->data ['Invoice Key']);
		$result = mysql_query( $sql );
		while ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
			$items_refund_net+=$row['Transaction Invoice Net Amount'];
			$items_refund_tax+=$row['Transaction Invoice Tax Amount'];
			$items_refund_net_outstanding_balance+=$row['Transaction Outstanding Net Amount Balance'];
			$items_refund_tax_outstanding_balance+=$row['Transaction Outstanding Tax Amount Balance'];

		}

		$this->data['Invoice Items Tax Amount']+= $items_refund_tax;
		$this->data['Invoice Items Net Amount']+= $items_refund_net;
		$this->data['Invoice Items Gross Amount']+=$items_refund_net;

		$this->data['Invoice Total Net Amount']+=$items_refund_net;
		$this->data['Invoice Total Tax Amount']+=$items_refund_tax;
		$this->data['Invoice Outstanding Net Balance']+=$items_refund_net_outstanding_balance;
		$this->data['Invoice Outstanding Tax Balance']+=$items_refund_tax_outstanding_balance;

		$this->data['Invoice Total Amount']=$this->data['Invoice Total Net Amount']+$this->data['Invoice Total Tax Amount'];
		$this->data['Invoice Outstanding Total Amount']=$this->data['Invoice Outstanding Net Balance']+$this->data['Invoice Outstanding Tax Balance'];
		$sql=sprintf("update  `Invoice Dimension` set `Invoice Outstanding Total Amount`=%f,`Invoice Outstanding Net Balance`=%f,`Invoice Outstanding Tax Balance`=%f,`Invoice Items Gross Amount`=%f,`Invoice Items Discount Amount`=%f ,`Invoice Items Net Amount`=%f,`Invoice Shipping Net Amount`=%f ,`Invoice Charges Net Amount`=%f ,`Invoice Total Net Amount`=%f ,`Invoice Items Tax Amount`=%f ,`Invoice Shipping Tax Amount`=%f,`Invoice Charges Tax Amount`=%f ,`Invoice Total Tax Amount`=%f,`Invoice Total Amount`=%f where `Invoice Key`=%d",
			$this->data['Invoice Outstanding Total Amount'],
			$this->data['Invoice Outstanding Net Balance'],
			$this->data['Invoice Outstanding Tax Balance'],
			$this->data['Invoice Items Gross Amount'],
			$this->data['Invoice Items Discount Amount'],
			$this->data['Invoice Items Net Amount'],
			$this->data['Invoice Shipping Net Amount'],
			$this->data['Invoice Charges Net Amount'],
			$this->data['Invoice Total Net Amount'],
			$this->data['Invoice Items Tax Amount'],
			$this->data['Invoice Shipping Tax Amount'],
			$this->data['Invoice Charges Tax Amount'],
			$this->data['Invoice Total Tax Amount'],
			$this->data['Invoice Total Amount'],


			$this->id
		);
		mysql_query($sql);


	}


	

	function update_charges_old($charge_data) {

		//print_r($charge_data);

		//$this->update_charges(array('Transaction Invoice Net Amount'=>$charges_net,'Invoice Charges Tax Amount'=>$charges_tax),true);
		//print "caca ";

		$amount=$charge_data['Transaction Invoice Net Amount'];
		//if ($amount==$this->data['Invoice Charges Net Amount']) {
		// $this->msg='Nothing to change';
		// return;
		//}
		$this->data['Invoice Charges Net Amount']=$amount;
		$this->data['Invoice Charges Tax Amount']=$amount*($this->get_tax_rate('charges'));
		$old_charges_data=array();
		$sql=sprintf("select * from `Order No Product Transaction Fact` where `Transaction Type`='Charges' and `Invoice Key`=%d  ",$this->id);
		$result = mysql_query( $sql );
		$old_total=0;
		while ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
			$old_charges_data[$row['Order No Product Transaction Fact Key']]=array(
				'amount'=>$row['Transaction Net Amount'],
				'Order No Product Transaction Fact Key'=>$row['Order No Product Transaction Fact Key']
			);
		}
		if ($old_total!=0) {
			foreach ($old_charges_data as $key=> $charges_data) {
				$old_charges_data[$key]['factor']=$charges_data['amount']/$old_total;
			}
		} else {
			foreach ($old_charges_data as $key=> $charges_data) {
				$old_charges_data[$key]['factor']=1.0/count($old_charges_data);
			}
		}



		if (count($old_charges_data)==0) {


			$sql=sprintf("insert into `Order No Product Transaction Fact` (`Invoice Key`,`Invoice Date`,`Transaction Type`,`Transaction Description`,`Transaction Invoice Net Amount`,`Tax Category Code`,`Transaction Invoice Tax Amount`,`Transaction Outstanding Net Amount Balance`,`Transaction Outstanding Tax Amount Balance`,`Currency Code`,`Currency Exchange`,`Metadata`)
                         values (%d,%s,%s,%s,%.2f,%s,%.2f,%.2f,%.2f,%s,%.2f,%s)  ",
				$this->id,
				prepare_mysql($this->data['Invoice Date']),
				prepare_mysql('Charges'),

				prepare_mysql($charge_data['Transaction Description']),
				$this->data['Invoice Charges Net Amount'],
				prepare_mysql($this->data['Invoice Tax Charges Code']),
				$this->data['Invoice Charges Tax Amount'],
				$this->data['Invoice Charges Net Amount'],
				$this->data['Invoice Charges Tax Amount'],
				prepare_mysql($this->data['Invoice Currency']),
				$this->data['Invoice Currency Exchange'],
				prepare_mysql($this->data['Invoice Metadata'])
			);


			mysql_query($sql);


		}
		elseif (count($old_charges_data)==1) {
			$_tmp=array_pop($old_charges_data);
			$sql=sprintf("update  `Order No Product Transaction Fact` set `Transaction Invoice Net Amount`=%f,`Transaction Invoice Tax Amount`=%f,`Transaction Outstanding Net Amount Balance`=%f,`Transaction Outstanding Tax Amount Balance`=%.2f where `Order No Product Transaction Fact Key`=%d",
				$this->data['Invoice Charges Net Amount'],
				$this->data['Invoice Charges Tax Amount'],
				$this->data['Invoice Charges Net Amount'],
				$this->data['Invoice Charges Tax Amount'],
				$_tmp['Order No Product Transaction Fact Key']
			);
			mysql_query($sql);

		}
		else {
			foreach ($old_charges_data as $onptfk => $charges_data) {
				$net=$this->data['Invoice Charges Net Amount']*$charges_data['factor'];
				$tax=$this->data['Invoice Charges Tax Amount']*$charges_data['factor'];
				$sql=sprintf("update  `Order No Product Transaction Fact` set `Transaction Invoice Net Amount`=%f,`Transaction Invoice Tax Amount`=%f,`Transaction Outstanding Net Amount Balance`=%f,`Transaction Outstanding Tax Amount Balance`=%.2f where `Order No Product Transaction Fact Key`=%d",
					$net,
					$tax,
					$net,
					$tax,
					$onptfk
				);
				mysql_query($sql);

			}

		}


		$sql=sprintf("update `Invoice Dimension` set `Invoice Charges Net Amount`=%f,`Invoice Charges Tax Amount`=%f where `Invoice Key`=%d",
			$this->data['Invoice Charges Net Amount'],
			$this->data['Invoice Charges Tax Amount'],
			$this->id
		);
		mysql_query($sql);
		$this->distribute_charges_over_the_otf();

	}

	function distribute_insurance_over_the_otf() {
		$sql = sprintf("select `Order Transaction Fact Key`,`Order Transaction Gross Amount` from `Order Transaction Fact` where `Invoice Key`=%d" , $this->id);

		//print $sql;
		$result = mysql_query( $sql );

		$total_insurance = 0;
		$insurance_factor = array ();

		$items = 0;
		while ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
			//print_r($row);
			$items ++;
			$_insurance = $row ['Order Transaction Gross Amount'];
			$total_insurance += $_insurance;
			$insurance_factor [$row ['Order Transaction Fact Key']] = $_insurance;
		}
		if ($items==0)
			return;

		foreach ( $insurance_factor as $line_number => $factor ) {
			if ($total_insurance == 0) {
				$insurance = $this->data ['Invoice Insurance Net Amount'] * $factor / $items;
				$insurance_tax=$this->data ['Invoice Insurance Tax Amount'] * $factor / $items;
			} else {
				$insurance = $this->data ['Invoice Insurance Net Amount'] * $factor / $total_insurance;
				$insurance_tax=$this->data ['Invoice Insurance Tax Amount'] * $factor / $total_insurance;

			}



			$sql = sprintf( "update `Order Transaction Fact` set `Invoice Transaction Insurance Amount`=%.4f, `Invoice Transaction Insurance Tax Amount`=%.6f where `Order Transaction Fact Key`=%d ",
				$insurance ,
				$insurance_tax,
				$line_number
			);
			mysql_query( $sql );
			//print "$sql\n";
		}

	}


	function distribute_charges_over_the_otf() {
		$sql = sprintf("select `Order Transaction Fact Key`,`Order Transaction Gross Amount` from `Order Transaction Fact` where `Invoice Key`=%d" , $this->id);

		//print $sql;
		$result = mysql_query( $sql );

		$total_charge = 0;
		$charge_factor = array ();

		$items = 0;
		while ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
			//print_r($row);
			$items ++;
			$charge = $row ['Order Transaction Gross Amount'];
			$total_charge += $charge;
			$charge_factor [$row ['Order Transaction Fact Key']] = $charge;
		}
		if ($items==0)
			return;

		foreach ( $charge_factor as $line_number => $factor ) {
			if ($total_charge == 0) {
				$charges = $this->data ['Invoice Charges Net Amount'] * $factor / $items;
				$charge_tax=$this->data ['Invoice Charges Tax Amount'] * $factor / $items;
			} else {
				$charges = $this->data ['Invoice Charges Net Amount'] * $factor / $total_charge;
				$charge_tax=$this->data ['Invoice Charges Tax Amount'] * $factor / $total_charge;

			}



			$sql = sprintf( "update `Order Transaction Fact` set `Invoice Transaction Charges Amount`=%.4f, `Invoice Transaction Charges Tax Amount`=%.6f where `Order Transaction Fact Key`=%d ",
				$charges ,
				$charge_tax,
				$line_number
			);
			mysql_query( $sql );
			//print "$sql\n";
		}


	}



	function distribute_shipping_over_the_otf() {





		$sql = sprintf("select `Order Transaction Fact Key`,`Estimated Weight` from `Order Transaction Fact` where `Invoice Key`=%d", $this->id);
		$result = mysql_query( $sql );
		$total_weight = 0;
		$weight_factor = array ();

//print $this->data ['Invoice Shipping Net Amount']." <---   $sql\n\n";
		$items = 0;
		while ( $row = mysql_fetch_array( $result, MYSQL_ASSOC ) ) {
			$items ++;
			$weight = $row ['Estimated Weight'];
			$total_weight += $weight;
			$weight_factor [$row ['Order Transaction Fact Key']] = $weight;
		}
//print "i: $items  $w: \n\n";
		// TODO horrible hack when there is not stitamed weight in system, it should be not extimted weights in system!!!!!
		if ($total_weight==0) {
			foreach ($weight_factor as $_key=>$_value) {
				$weight_factor[$_key]=1;
			}

		}




		if ($items==0)
			return;
		foreach ( $weight_factor as $line_number => $factor ) {
			if ($total_weight == 0) {
				$shipping = $this->data ['Invoice Shipping Net Amount'] * $factor / $items;
				$shipping_tax=$this->data ['Invoice Shipping Tax Amount'] * $factor / $items;
			} else {
				$shipping = $this->data ['Invoice Shipping Net Amount'] * $factor / $total_weight;
				$shipping_tax=$this->data ['Invoice Shipping Tax Amount'] * $factor / $total_weight;
			}



			$sql = sprintf( "update `Order Transaction Fact` set `Invoice Transaction Shipping Amount`=%.4f, `Invoice Transaction Shipping Tax Amount`=%.6f where `Order Transaction Fact Key`=%d ",
				$shipping ,
				$shipping_tax,
				$line_number
			);
		//	print "$sql\n\n";
			mysql_query( $sql );
		}



	}




	function create_header() {

		//calculate the order total
		$this->data ['Invoice Gross Amount'] = 0;
		$this->data ['Invoice Discount Amount'] = 0;

		if (!isset($this->data ['Invoice Delivery Town'])) {
			$this->data ['Invoice Delivery Town']='';
		}
		if (!isset($this->data ['Invoice Delivery Postal Code'])) {
			$this->data ['Invoice Delivery Postal Code']='';
		}
		if (!isset($this->data ['Invoice Billing Town'])) {
			$this->data ['Invoice Billing Town']='';
		}
		if (!isset($this->data ['Invoice Billing Postal Code'])) {
			$this->data ['Invoice Billing Postal Code']='';
		}

		if (!isset($this->data ['Invoice Billing Country 2 Alpha Code'])) {
			$this->data ['Invoice Billing Country 2 Alpha Code']='XX';
			$this->data ['Invoice Billing Country Code']='UNK';
			$this->data ['Invoice Billing World Region Code']='UNKN';

		}
		if (!isset($this->data ['Invoice Delivery Country 2 Alpha Code'])) {
			$this->data ['Invoice Delivery Country 2 Alpha Code']='XX';
			$this->data ['Invoice Delivery World Region Code']='UNKN';
			$this->data ['Invoice Delivery Country Code']='UNK';

		}



		$sql = sprintf( "insert into `Invoice Dimension` (
		`Invoice Tax Number`,`Invoice Tax Number Valid`,`Invoice Tax Number Validation Date`,`Invoice Tax Number Associated Name`,`Invoice Tax Number Associated Address`,
		
		`Invoice Customer Level Type`,

                         `Invoice Tax Charges Code`,`Invoice Customer Contact Name`,`Invoice Currency`,
                         `Invoice Currency Exchange`,
                         `Invoice For`,`Invoice Date`,`Invoice Public ID`,`Invoice File As`,`Invoice Store Key`,`Invoice Store Code`,`Invoice Main Source Type`,`Invoice Customer Key`,`Invoice Customer Name`,`Invoice XHTML Ship Tos`,`Invoice Items Gross Amount`,`Invoice Items Discount Amount`,
                         `Invoice Charges Net Amount`,`Invoice Total Tax Amount`,`Invoice Refund Net Amount`,`Invoice Refund Tax Amount`,`Invoice Total Amount`,


                         `Invoice Metadata`,
                         `Invoice XHTML Address`,`Invoice XHTML Orders`,`Invoice XHTML Delivery Notes`,`Invoice XHTML Store`,`Invoice Has Been Paid In Full`,`Invoice Main Payment Method`
                         ,`Invoice Charges Tax Amount`,


                         `Invoice Billing Country 2 Alpha Code`,
                         `Invoice Billing Country Code`,
                         `Invoice Billing World Region Code`,
                         `Invoice Billing Town`,
                         `Invoice Billing Postal Code`,

                         `Invoice Delivery Country 2 Alpha Code`,
                         `Invoice Delivery Country Code`,
                         `Invoice Delivery World Region Code`,
                         `Invoice Delivery Town`,
                         `Invoice Delivery Postal Code`,

                         `Invoice Dispatching Lag`,`Invoice Taxable`,`Invoice Tax Code`,`Invoice Type`,`Invoice Outstanding Total Amount`) values
                         (
                          %s,%s,%s,%s,%s,
                         %s,
                         %s,%s,%s,
                         %f,
                         %s,%s,%s,%s,%s,%s,%s,%s,%s,%s,
                         %.2f,%.2f,%.2f,%.2f,%.2f,%.2f,%.2f,
                         %s,%s, %s, %s,%s,%s,%s,
                         %.2f,


                         %s, %s, %s, %s,%s,

                         %s, %s, %s, %s,%s,

                         %s,%s,%s,%s,%f)"

, prepare_mysql ( $this->data ['Invoice Tax Number'] )
, prepare_mysql ( $this->data ['Invoice Tax Number Valid'] )
, prepare_mysql ( $this->data ['Invoice Tax Number Validation Date'] )
, prepare_mysql ( $this->data ['Invoice Tax Number Associated Name'] )
, prepare_mysql ( $this->data ['Invoice Tax Number Associated Address'] )

			, prepare_mysql ( $this->data ['Invoice Customer Level Type'] )

			, prepare_mysql ( $this->data ['Invoice Tax Charges Code'] )
			, prepare_mysql ( $this->data ['Invoice Customer Contact Name'],false)
			, prepare_mysql ( $this->data ['Invoice Currency'] )

			, $this->data ['Invoice Currency Exchange']

			, prepare_mysql ( $this->data ['Invoice For'] )
			, prepare_mysql ( $this->data ['Invoice Date'] )
			, prepare_mysql ( $this->data ['Invoice Public ID'] )
			, prepare_mysql ( $this->data ['Invoice File As'] )
			, prepare_mysql ( $this->data ['Invoice Store Key'] )
			, prepare_mysql ( $this->data ['Invoice Store Code'] )
			, prepare_mysql ( $this->data ['Invoice Main Source Type'] )
			, prepare_mysql ( $this->data ['Invoice Customer Key'] ),
			prepare_mysql ( $this->data ['Invoice Customer Name'] ,false),
			prepare_mysql ( $this->data ['Invoice XHTML Ship Tos'] ),


			$this->data ['Invoice Items Gross Amount'],
			$this->data ['Invoice Items Discount Amount'],
			$this->data ['Invoice Charges Net Amount'],
			$this->data ['Invoice Total Tax Amount']
			, $this->data ['Invoice Refund Net Amount'],
			$this->data ['Invoice Refund Tax Amount']
			, $this->data ['Invoice Total Amount']

			, prepare_mysql ( $this->data ['Invoice Metadata'] )
			, prepare_mysql ( $this->data ['Invoice XHTML Address'] )
			, prepare_mysql ( $this->data ['Invoice XHTML Orders'] )
			, prepare_mysql(  $this->data ['Invoice XHTML Delivery Notes'] )
			, prepare_mysql ( $this->data ['Invoice XHTML Store'] )
			, prepare_mysql ( $this->data ['Invoice Has Been Paid In Full'] )
			, prepare_mysql ( $this->data ['Invoice Main Payment Method'] )

			, $this->data ['Invoice Charges Tax Amount']


			, prepare_mysql ( $this->data ['Invoice Billing Country 2 Alpha Code'] )
			, prepare_mysql ( $this->data ['Invoice Billing Country Code'] )
			, prepare_mysql ( $this->data ['Invoice Billing World Region Code'] )
			, prepare_mysql ( $this->data ['Invoice Billing Town'] )
			, prepare_mysql ( $this->data ['Invoice Billing Postal Code'] )


			, prepare_mysql ( $this->data ['Invoice Delivery Country 2 Alpha Code'] )
			, prepare_mysql ( $this->data ['Invoice Delivery Country Code'] )
			, prepare_mysql ( $this->data ['Invoice Delivery World Region Code'] )
			, prepare_mysql ( $this->data ['Invoice Delivery Town'] )
			, prepare_mysql ( $this->data ['Invoice Delivery Postal Code'] )

			, prepare_mysql ( $this->data ['Invoice Dispatching Lag'] )
			, prepare_mysql ( $this->data ['Invoice Taxable'] )
			, prepare_mysql ( $this->data ['Invoice Tax Code'] )
			, prepare_mysql ($this->data ['Invoice Type'])
			, $this->data ['Invoice Total Amount']
		);



		if (mysql_query( $sql )) {

			$this->data ['Invoice Key'] = mysql_insert_id();

			$this->id=$this->data ['Invoice Key'];
			$sql = sprintf("INSERT INTO `Invoice Tax Dimension` (`Invoice Key`) VALUES (%d)", $this->data ['Invoice Key']);

			mysql_query($sql);


		} else {

			exit ("$sql Error can not create order header");
		}

	}



	function update_field_switcher($field,$value,$options='') {

		switch ($field) {

		case('Invoice XHTML Orders'):
			$this->update_xhtml_orders();
			break;
		case('Invoice XHTML Delivery Notes'):
			$this->update_xhtml_delivery_notes();
			break;
		default:
			$base_data=$this->base_data();
			if (array_key_exists($field,$base_data)) {
				if ($value!=$this->data[$field]) {
					$this->update_field($field,$value,$options);
				}
			}
		}
	}



	function update_xhtml_orders() {
		$state='';
		$this->data ['Invoice XHTML Orders'] ='';
		$sql=sprintf("select O.`Order Key`,`Order Public ID` from `Order Invoice Bridge` B left join `Order Dimension` O on (O.`Order Key`=B.`Order Key`) where `Invoice Key`=%d",
			$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$this->data ['Invoice XHTML Orders'] .= sprintf( '%s <a href="order.php?id=%d">%s</a>, ', $state, $row['Order Key'], $row['Order Public ID'] );

		}
		$this->data ['Invoice XHTML Orders'] =_trim(preg_replace('/\, $/','',$this->data ['Invoice XHTML Orders']));

		$sql=sprintf("update `Invoice Dimension` set `Invoice XHTML Orders`=%s where `Invoice Key`=%d "
			,prepare_mysql($this->data['Invoice XHTML Orders'])
			,$this->id
		);
		mysql_query($sql);
	}

	function update_delivery_note_data($data) {
		$this->data['Invoice Delivery Country 2 Alpha Code']=$data['Invoice Delivery Country 2 Alpha Code'];
		$this->data['Invoice Delivery Country Code']=$data['Invoice Delivery Country Code'];
		$this->data['Invoice Delivery World Region Code']=$data['Invoice Delivery World Region Code'];
		$this->data['Invoice Delivery Town']=$data['Invoice Delivery Town'];
		$this->data['Invoice Delivery Postal Code']=$data['Invoice Delivery Postal Code'];


		$sql=sprintf("update `Invoice Dimension` set
                     `Invoice Delivery Country 2 Alpha Code`=%s ,
                     `Invoice Delivery Country Code`=%s,
                     `Invoice Delivery World Region Code`=%s,
                     `Invoice Delivery Town`=%s,
                     `Invoice Delivery Postal Code`=%s


                     where `Invoice Key`=%d "
			,prepare_mysql($this->data['Invoice Delivery Country 2 Alpha Code'])
			,prepare_mysql($this->data['Invoice Delivery Country Code'])
			,prepare_mysql($this->data['Invoice Delivery World Region Code'])
			,prepare_mysql($this->data['Invoice Delivery Town'])
			,prepare_mysql($this->data['Invoice Delivery Postal Code'])
			,$this->id
		);
		mysql_query($sql);

	}




	function update_xhtml_delivery_notes() {
		$prefix='';
		$this->data ['Invoice XHTML Delivery Notes'] ='';
		foreach ($this->get_delivery_notes_objects() as $delivery_note) {
			//  $this->data ['Invoice XHTML Delivery Notes'] .= sprintf( '%s <a href="dn.php?id=%d">%s</a>, ', $prefix, $delivery_note->data ['Delivery Note Key'], $delivery_note->data ['Delivery Note ID'] );
			// }
			// $this->data ['Invoice XHTML Delivery Notes'] =_trim(preg_replace('/\, $/','',$this->data ['Invoice XHTML Delivery Notes']));


			if ($delivery_note->get('Delivery Note State')=='Dispatched')
				$state='<img src="art/icons/lorry.png" style="height:14px">';

			elseif ($delivery_note->get('Delivery Note State')=='Packed Done')
				$state='<img src="art/icons/package.png" style="height:14px">';
			elseif ($delivery_note->get('Delivery Note State')=='Approved')
				$state='<img src="art/icons/package_green.png" style="height:14px">';
			else
				$state='<img src="art/icons/cart.png" style="width:14px">';

			$this->data ['Invoice XHTML Delivery Notes'] .= sprintf( '%s <a href="dn.php?id=%d">%s%s</a> <a href="dn.pdf.php?id=%d" target="_blank"><img style="height:10px;position:relative;bottom:2.5px" src="art/pdf.gif" alt=""></a><br/>',
				$state,
				$delivery_note->data ['Delivery Note Key'],
				$prefix,
				$delivery_note->data ['Delivery Note ID'], $delivery_note->data ['Delivery Note Key'] );
		}

		$this->data ['Invoice XHTML Delivery Notes'] =_trim(preg_replace('/\<br\/\>$/','',$this->data ['Invoice XHTML Delivery Notes']));


		$sql=sprintf("update `Invoice Dimension` set `Invoice XHTML Delivery Notes`=%s where `Invoice Key`=%d "
			,prepare_mysql($this->data['Invoice XHTML Delivery Notes'])
			,$this->id
		);
		mysql_query($sql);
	}




	function get($key) {

		switch ($key) {
		case('Items Gross Amount'):
		case('Items Discount Amount'):
		case('Items Net Amount'):
		case('Items Tax Amount'):
		case('Refund Net Amount'):
		case('Charges Net Amount'):
		case('Shipping Net Amount'):
		case('Total Net Amount'):
		case('Total Tax Amount'):
		case('Total Amount'):
		case('Total Net Adjust Amount'):
		case('Total Tax Adjust Amount'):
		case('Outstanding Total Amount'):

			return money($this->data['Invoice '.$key],$this->data['Invoice Currency']);
			break;
		case('Date'):
			return strftime("%a %e %b %Y %H:%M %Z",strtotime($this->data['Invoice Date'].' +0:00'));
			break;
		case('Payment Method'):

			switch ($this->data['Invoice Main Payment Method']) {
			case 'Credit Card':
				return _('Credit Card');
				break;
			case 'Cash':
				return _('Cash');
				break;
			case 'Paypal':
				return _('Paypal');
				break;
			case 'Check':
				return _('Check');
				break;
			case 'Bank Transfer':
				return _('Bank Transfer');
				break;
			case 'Other':
				return _('Other');
				break;
			case 'Unknown':
				return _('Unknown');
				break;



				break;
			default:
				return $this->data['Invoice Main Payment Method'];
				break;
			}
			break;
		case('Payment State'):
			return $this->get_xhtml_payment_state();
		}


		if (isset($this->data[$key]))
			return $this->data[$key];

		return false;
	}

	function get_xhtml_payment_state() {

		switch ($this->data['Invoice Paid']) {
		case 'Yes':
			return _('Paid in full');
			break;
		case 'No':
			return _('Not Paid');
			break;
		case 'Partially':
			return _('Partially Paid');
			break;
		default:
			return _('Unknown');

		}
	}


	function display($tipo='xml') {



		switch ($tipo) {

		default:
			return 'todo';

		}


	}


	function get_number_orders() {

		$number_orders=0;
		$sql=sprintf("select count(*) as num from `Order Invoice Bridge` where `Invoice Key`=%d ",$this->id);

		$res = mysql_query( $sql );

		if ($row = mysql_fetch_assoc( $res )) {
			$number_orders=$row['num'];
		}
		return $number_orders;
	}

	function get_number_delivery_notes() {

		$number_delivery_notes=0;
		$sql=sprintf("select count(*) as num from `Invoice Delivery Note Bridge` where `Invoice Key`=%d ",$this->id);

		$res = mysql_query( $sql );

		if ($row = mysql_fetch_assoc( $res )) {
			$number_delivery_notes=$row['num'];
		}
		return $number_delivery_notes;
	}



	function get_orders_ids() {
		$orders=array();
		$sql=sprintf("select `Order Key` from `Order Transaction Fact` where `Invoice Key`=%d  or  `Refund Key`=%d  group by `Order Key`",$this->id,$this->id);
		//print "$sql\n";
		$res = mysql_query( $sql );

		while ($row = mysql_fetch_assoc( $res )) {
			if ($row['Order Key']>0) {
				$orders[$row['Order Key']]=$row['Order Key'];
			}

		}

		$sql=sprintf("select `Order Key` from `Order No Product Transaction Fact` where `Invoice Key`=%d  or  `Refund Key`=%d  group by `Order Key`",$this->id,$this->id);
		//print "$sql\n";

		$res = mysql_query( $sql );

		while ($row = mysql_fetch_assoc( $res )) {
			if ($row['Order Key']>0) {
				$orders[$row['Order Key']]=$row['Order Key'];
			}

		}


		return $orders;

	}

	function get_orders_objects() {

		$orders=array();
		$orders_ids=$this->get_orders_ids();
		foreach ($orders_ids as $order_id) {
			$order=new Order($order_id);
			if ($order->id) {
				$orders[$order_id]=$order;
			}
		}
		return $orders;
	}
	function get_delivery_notes_ids() {
		$sql=sprintf("select `Delivery Note Key` from `Order Transaction Fact` where `Invoice Key`=%d  or  `Refund Key`=%d  group by `Delivery Note Key`",$this->id,$this->id);

		$res = mysql_query( $sql );
		$delivery_notes=array();
		while ($row = mysql_fetch_assoc( $res )) {
			if ($row['Delivery Note Key']) {
				$delivery_notes[$row['Delivery Note Key']]=$row['Delivery Note Key'];
			}

		}
		return $delivery_notes;

	}
	function get_delivery_notes_objects() {
		$delivery_notes=array();
		$delivery_notes_ids=$this->get_delivery_notes_ids();
		foreach ($delivery_notes_ids as $order_id) {
			$delivery_notes[$order_id]=new DeliveryNote($order_id);
		}
		return $delivery_notes;
	}

	function get_operations($user,$parent='order') {
		include_once 'order_common_functions.php';

		return get_invoice_operations($this->data,$user,$parent);
	}


	function update_payment_state() {


		$paid_amount=0;
		$sql=sprintf("select sum(`Amount`) as amount from `Invoice Payment Bridge` where `Invoice Key`=%d",
			$this->id
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$paid_amount=round($row['amount'],2);
		}

		$this->data['Invoice Paid Amount']=$paid_amount;
		$this->data['Invoice Outstanding Total Amount']=round($this->data['Invoice Total Amount']-$this->data['Invoice Paid Amount'],2);
		$this->data['Invoice Main Payment Method']=$this->get_main_payment_method();


		$sql=sprintf("update `Invoice Dimension`  set `Invoice Outstanding Total Amount`=%.2f, `Invoice Paid Amount`=%.2f,`Invoice Main Payment Method`=%s where `Invoice Key`=%d",
			$this->data['Invoice Outstanding Total Amount'],
			$this->data['Invoice Paid Amount'],
			prepare_mysql($this->data['Invoice Main Payment Method']),

			$this->id);
		mysql_query( $sql );

//print $this->data['Invoice Outstanding Total Amount'].'<-';
		if ($this->data['Invoice Total Amount']>=0) {

			if ($this->data['Invoice Paid Amount']==0) {
				$this->set_as_not_paid();

			}elseif ($this->data['Invoice Outstanding Total Amount']==0) {

				$this->set_as_full_paid();

			}elseif ($this->data['Invoice Paid Amount']>0) {
				$this->set_as_parcially_paid();
			}

		}
		else {//refund
			if ($this->data['Invoice Outstanding Total Amount']==0) {

				$this->set_as_full_paid();

			}elseif ($this->data['Invoice Paid Amount']<0) {
				$this->set_as_parcially_paid();
			}

		}



	}

	function apply_payment($payment) {



		if ($this->data['Invoice Outstanding Total Amount']>0) {

			//print $payment->data['Payment Balance'].'='.$this->data['Invoice Outstanding Total Amount']." xx";

			if ($payment->data['Payment Balance']>=$this->data['Invoice Outstanding Total Amount']) {

				$to_pay=$this->data['Invoice Outstanding Total Amount'];


				$payment_amount_not_used=round($payment->data['Payment Balance']-$to_pay,2);
				$payment_amount_used=round($to_pay,2);
			}else {
				$this->set_as_parcially_paid($payment);
				$payment_amount_not_used= round(0.00,2);
				$payment_amount_used=round($payment->data['Payment Balance'],2);
			}






		}
		else {// Refund

			if ($payment->data['Payment Balance']<=$this->data['Invoice Outstanding Total Amount']) {

				$to_pay=$this->data['Invoice Outstanding Total Amount'];


				$payment_amount_not_used=$payment->data['Payment Balance']-$to_pay;
				$payment_amount_used=$to_pay;
			}else {
				$this->set_as_parcially_paid($payment);
				$payment_amount_not_used= 0;
				$payment_amount_used=$payment->data['Payment Balance'];
			}


		}

		$payment_date_to_update=array(
			'Payment Invoice Key'=>$this->id,
			'Payment Balance'=>$payment_amount_not_used,
			'Payment Amount Invoiced'=>$payment_amount_used
		);
		//print_r($payment_date_to_update);
		$payment->update($payment_date_to_update);

		$sql=sprintf("insert into `Invoice Payment Bridge`  (`Invoice Key`,`Payment Key`,`Payment Account Key`,`Payment Service Provider Key`,`Amount`) values (%d,%d,%d,%d,%.2f) ",
			$this->id,
			$payment->id,
			$payment->data['Payment Account Key'],
			$payment->data['Payment Service Provider Key'],
			$payment_amount_used
		);
		mysql_query($sql);


		$this->update_payment_state();



		return $payment_amount_not_used;

	}

	function set_as_parcially_paid() {

		$this->data['Invoice Paid']='Partially';
		$this->data['Invoice Has Been Paid In Full']='No';

		$sql=sprintf("update `Invoice Dimension`  set `Invoice Paid`=%s,`Invoice Has Been Paid In Full`=%s where `Invoice Key`=%d",
			prepare_mysql($this->data['Invoice Paid']),
			prepare_mysql($this->data['Invoice Has Been Paid In Full'])
			,$this->id);
		mysql_query( $sql );


	}

	function set_as_not_paid() {

		$this->data['Invoice Paid']='No';
		$this->data['Invoice Has Been Paid In Full']='No';

		$sql=sprintf("update `Invoice Dimension`  set `Invoice Paid`=%s,`Invoice Has Been Paid In Full`=%s where `Invoice Key`=%d",
			prepare_mysql($this->data['Invoice Paid']),
			prepare_mysql($this->data['Invoice Has Been Paid In Full'])
			,$this->id);
		mysql_query( $sql );


	}


	function set_as_full_paid() {



		$this->data['Invoice Paid Date']=gmdate("Y-m-d H:i:s");
		$sql=sprintf("select `Invoice Currency Exchange Rate`,`Invoice Transaction Net Refund Items`,`Order Transaction Fact Key`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Gross Amount` from `Order Transaction Fact` where `Invoice Key`=%d  and `Consolidated`='No' ",
			$this->id);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$sql = sprintf( "update  `Order Transaction Fact`  set `Payment Method`=%s,`Invoice Transaction Outstanding Net Balance`=0,
			`Invoice Transaction Outstanding Tax Balance`=0,`Paid Factor`=1,`Current Payment State`='Paid',`Consolidated`='Yes',`Paid Date`=%s,`Invoice Transaction Outstanding Tax Balance`=0 ,`Invoice Transaction Outstanding Tax Balance`=0 where `Order Transaction Fact Key`=%d "
				,prepare_mysql($this->data['Invoice Main Payment Method'])
				,prepare_mysql($this->data['Invoice Paid Date'])
				,$row['Order Transaction Fact Key']);

			mysql_query( $sql );





			//print "$sql\n";
			$sql=sprintf( "update  `Inventory Transaction Fact`  set `Amount In`=%f where `Map To Order Transaction Fact Key`=%d "
				,$row['Invoice Currency Exchange Rate']*($row['Invoice Transaction Gross Amount']-$row['Invoice Transaction Total Discount Amount']-$row['Invoice Transaction Net Refund Items'])
				,$row['Order Transaction Fact Key']);

			mysql_query( $sql );
			//print "$sql\n";
		}

		$sql=sprintf("select `Order No Product Transaction Fact Key` from `Order No Product Transaction Fact` where `Invoice Key`=%d  and `Consolidated`='No' ",
			$this->id);

		$res=mysql_query($sql);
		//print "\n\n$sql\n";
		while ($row=mysql_fetch_assoc($res)) {
			$sql = sprintf( "update  `Order No Product Transaction Fact`  set `Payment Method`=%s,`Transaction Outstanding Net Amount Balance`=0,`Transaction Outstanding Tax Amount Balance`=0,`Paid Factor`=1,`Current Payment State`='Paid',`Consolidated`='Yes',`Paid Date`=%s where `Order No Product Transaction Fact Key`=%d "
				,prepare_mysql($this->data['Invoice Main Payment Method'])
				,prepare_mysql($this->data['Invoice Paid Date'])
				,$row['Order No Product Transaction Fact Key']);

			mysql_query( $sql );


		}

		$this->data['Invoice Paid']='Yes';
		$this->data['Invoice Has Been Paid In Full']='Yes';

		$sql=sprintf("update `Invoice Dimension`  set `Invoice Paid Date`=%s,`Invoice Paid`=%s,`Invoice Has Been Paid In Full`=%s where `Invoice Key`=%d",
			prepare_mysql($this->data['Invoice Paid Date']),
			prepare_mysql($this->data['Invoice Paid']),
			prepare_mysql($this->data['Invoice Has Been Paid In Full'])
			,$this->id);
		mysql_query( $sql );


	}





	// this function to be deleted (used by old read order from excel)
	function pay_full_amount($data) {
		$this->data['Invoice Paid Date']=$data['Invoice Paid Date'];
		$sql=sprintf("select `Invoice Currency Exchange Rate`,`Invoice Transaction Net Refund Items`,`Order Transaction Fact Key`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Gross Amount` from `Order Transaction Fact` where `Invoice Key`=%d  and `Consolidated`='No' ",
			$this->id);

		$res=mysql_query($sql);
		//print "$sql\n";
		while ($row=mysql_fetch_assoc($res)) {
			$sql = sprintf( "update  `Order Transaction Fact`  set `Payment Method`=%s,`Invoice Transaction Outstanding Net Balance`=0,
			`Invoice Transaction Outstanding Tax Balance`=0,`Paid Factor`=1,`Current Payment State`='Paid',`Consolidated`='Yes',`Paid Date`=%s,`Invoice Transaction Outstanding Tax Balance`=0 ,`Invoice Transaction Outstanding Tax Balance`=0 where `Order Transaction Fact Key`=%d "
				,prepare_mysql($data['Payment Method'])
				,prepare_mysql($this->data['Invoice Paid Date'])
				,$row['Order Transaction Fact Key']);

			mysql_query( $sql );





			//print "$sql\n";
			$sql=sprintf( "update  `Inventory Transaction Fact`  set `Amount In`=%f where `Map To Order Transaction Fact Key`=%d "
				,$row['Invoice Currency Exchange Rate']*($row['Invoice Transaction Gross Amount']-$row['Invoice Transaction Total Discount Amount']-$row['Invoice Transaction Net Refund Items'])
				,$row['Order Transaction Fact Key']);

			mysql_query( $sql );
			//print "$sql\n";
		}

		$sql=sprintf("select `Order No Product Transaction Fact Key` from `Order No Product Transaction Fact` where `Invoice Key`=%d  and `Consolidated`='No' ",
			$this->id);

		$res=mysql_query($sql);
		//print "\n\n$sql\n";
		while ($row=mysql_fetch_assoc($res)) {
			$sql = sprintf( "update  `Order No Product Transaction Fact`  set `Payment Method`=%s,`Transaction Outstanding Net Amount Balance`=0,`Transaction Outstanding Tax Amount Balance`=0,`Paid Factor`=1,`Current Payment State`='Paid',`Consolidated`='Yes',`Paid Date`=%s where `Order No Product Transaction Fact Key`=%d "
				,prepare_mysql($data['Payment Method'])
				,prepare_mysql($this->data['Invoice Paid Date'])
				,$row['Order No Product Transaction Fact Key']);

			mysql_query( $sql );


		}



		$sql=sprintf("update `Invoice Dimension`  set `Invoice Outstanding Total Amount`=0,`Invoice Paid Amount`=%f,`Invoice Paid Date`=%s ,`Invoice Paid`='Yes',`Invoice Has Been Paid In Full`='Yes' where `Invoice Key`=%d"
			,$this->data['Invoice Total Amount']
			,prepare_mysql($this->data['Invoice Paid Date'])

			,$this->id);
		mysql_query( $sql );

		$this->get_data('id',$this->id);



		$main_payment_method=$this->get_main_payment_method();

		$sql=sprintf("update `Invoice Dimension`  set `Invoice Main Payment Method`=%s,`Invoice Paid Date`=%s ,`Invoice Paid`='Yes',`Invoice Has Been Paid In Full`='Yes' where `Invoice Key`=%d"
			,prepare_mysql($main_payment_method)
			,prepare_mysql($this->data['Invoice Paid Date'])

			,$this->id);
		mysql_query( $sql );
		//print "$sql\n";
		$this->data['Invoice Main Payment Method']= $main_payment_method;


		$this->updated=true;

	}


	function get_main_payment_method() {

		$method='Unknown';

		$sql=sprintf("select `Payment Method`   from `Invoice Payment Bridge` B left join `Payment Dimension` P on (B.`Payment Key`=P.`Payment Key`) where `Invoice Key`=%d  group by `Payment Method` order by sum(ABS(`Amount`)) desc limit 1  ",
			$this->id);

		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$method=$row['Payment Method'];
		}




		return $method;

	}


	// this function has to go after retire excel
	function pay($tipo='full', $data) {

		if (!array_key_exists('Invoice Paid Date',$data) or !$data['Invoice Paid Date']  ) {
			$data['Invoice Paid Date']=gmdate('Y-m-d H:i:s');
		}

		if ($tipo=='full' or $data['amount']==$this->data['Invoice Outstanding Total Amount']) {
			$this->pay_full_amount($data);
		} else {
			$this->pay_partial_amount($data);
		}




		foreach ($this->get_orders_objects() as $key=>$order) {

			// print_r($order);
			//exit;

			$order->update_payment_state();
			$order->update_no_normal_totals();
			$order->update_full_search();
			$order->update_xhtml_invoices();
			if ($this->data['Invoice Type']=='Refund' or $this->data['Invoice Type']=='CreditNote') {
				$customer=new Customer($this->data['Invoice Customer Key']);
				$customer->add_history_order_refunded($this);

			}



		}
		foreach ($this->get_delivery_notes_objects() as $key=>$dn) {
			$dn->update_xhtml_invoices();

		}
	}


	function pay_partial_amount($data) {

	}


	function categorize($args='') {


		$sql=sprintf("select * from `Category Dimension` where `Category Subject`='Invoice' and `Category Store Key`=%d order by `Category Function Order`, `Category Key` ",
			$this->data['Invoice Store Key']);
		// print $sql;
		$res=mysql_query($sql);
		$function_code='';
		while ($row=mysql_fetch_assoc($res)) {
			if ($row['Category Function']!='') {
				$function_code.=sprintf("%s return %d;",$row['Category Function'],$row['Category Key']);
			}


		}
		$function_code.="return 0;";
		//print $function_code."\n";exit;
		$newfunc = create_function('$data',$function_code);

		// $this->data['Invoice Customer Level Type'];

		$category_key=$newfunc($this->data);

		//print "Cat $category_key\n";

		if ($category_key) {
			$category=new Category($category_key);

			if ($category->id) {
				//print "HOLA";
				$category->associate_subject($this->id);

			}
		}

	}







	function add_credit_no_product_transaction($credit_transaction_data) {


		$sql=sprintf("insert into `Order No Product Transaction Fact` (`Affected Order Key`,`Order Key`,`Order Date`,`Invoice Key`,`Invoice Date`,`Transaction Type`,`Transaction Description`,
		`Transaction Invoice Net Amount`,`Tax Category Code`,`Transaction Invoice Tax Amount`,`Transaction Outstanding Net Amount Balance`,`Transaction Outstanding Tax Amount Balance`,`Currency Code`,`Currency Exchange`,`Metadata`)
		values (%s,%s,%s,%d,%s,%s,%s,%.2f,%s,%.2f,%.2f,%.2f,%s,%.2f,%s)  ",
			prepare_mysql($credit_transaction_data['Affected Order Key']),
			prepare_mysql($credit_transaction_data['Order Key']),
			prepare_mysql($credit_transaction_data['Order Date']),
			$this->id,
			prepare_mysql($this->data['Invoice Date']),
			prepare_mysql($credit_transaction_data['Transaction Type']),
			prepare_mysql($credit_transaction_data['Transaction Description']),

			$credit_transaction_data['Transaction Invoice Net Amount'],
			prepare_mysql($credit_transaction_data['Tax Category Code']),
			$credit_transaction_data['Transaction Invoice Tax Amount'],
			$credit_transaction_data['Transaction Invoice Net Amount'],
			$credit_transaction_data['Transaction Invoice Tax Amount'],
			prepare_mysql($this->data['Invoice Currency']),
			$this->data['Invoice Currency Exchange'],
			prepare_mysql($credit_transaction_data['Metadata'])


		);
		mysql_query($sql);

		$this->update_totals();
		$this->update_refund_totals();
	}





	function add_orphan_refund_no_product_transaction($refund_transaction_data) {



		$sql=sprintf("insert into `Order No Product Transaction Fact` (`Order Key`,`Affected Order Key`,`Refund Key`,`Refund Date`,`Transaction Type`,`Transaction Description`,`Transaction Invoice Net Amount`,`Tax Category Code`,`Transaction Invoice Tax Amount`,`Transaction Outstanding Net Amount Balance`,`Transaction Outstanding Tax Amount Balance`,`Currency Code`,`Currency Exchange`,`Metadata`)   values (%s,%s,%d,%s,%s,%s,%.2f,%s,%.2f,%.2f,%.2f,%s,%.2f,%s)  ",
			prepare_mysql($refund_transaction_data['Order Key']),
			prepare_mysql($refund_transaction_data['Affected Order Key']),
			$this->id,
			prepare_mysql($this->data['Invoice Date']),
			prepare_mysql('Refund'),
			prepare_mysql($refund_transaction_data['Transaction Description']),
			$refund_transaction_data['Transaction Invoice Net Amount'],
			prepare_mysql($refund_transaction_data['Tax Category Code']),
			$refund_transaction_data['Transaction Invoice Tax Amount'],
			$refund_transaction_data['Transaction Invoice Net Amount'],
			$refund_transaction_data['Transaction Invoice Tax Amount'],
			prepare_mysql($this->data['Invoice Currency']),
			$this->data['Invoice Currency Exchange'],
			prepare_mysql($this->data['Invoice Metadata'])
		);
		mysql_query($sql);
		// print $sql;
		$this->update_refund_totals();
	}

	function add_refund_transaction($refund_transaction_data) {




		$sql=sprintf("update `Order Transaction Fact` set `Refund Metadata`=%s,`Refund Key`=%d,

                     `Invoice Transaction Net Refund Items`=%f,
                     `Invoice Transaction Net Refund Shipping`=%f,
                     `Invoice Transaction Net Refund Charges`=%f,
                     `Invoice Transaction Tax Refund Items`=%f,
                     `Invoice Transaction Tax Refund Shipping`=%f,
                     `Invoice Transaction Tax Refund Charges`=%f,

                     `Invoice Transaction Net Refund Amount`=%f,
                     `Invoice Transaction Tax Refund Amount`=%f  ,
                     `Invoice Transaction Outstanding Refund Net Balance`=%f ,`Invoice Transaction Outstanding Refund Tax Balance`=%f where `Order Transaction Fact Key`=%d ",
			prepare_mysql($refund_transaction_data['Refund Metadata']),
			$this->id,
			$refund_transaction_data['Invoice Transaction Net Refund Items'],
			$refund_transaction_data['Invoice Transaction Net Refund Shipping'],
			$refund_transaction_data['Invoice Transaction Net Refund Charges'],
			$refund_transaction_data['Invoice Transaction Tax Refund Items'],
			$refund_transaction_data['Invoice Transaction Tax Refund Shipping'],
			$refund_transaction_data['Invoice Transaction Tax Refund Charges'],


			$refund_transaction_data['Invoice Transaction Net Refund Amount'],
			$refund_transaction_data['Invoice Transaction Tax Refund Amount'],
			$refund_transaction_data['Invoice Transaction Net Refund Amount'],
			$refund_transaction_data['Invoice Transaction Tax Refund Amount'],
			$refund_transaction_data['Order Transaction Fact Key']

		);
		mysql_query($sql);
		//print $sql;
		//print "$sql\n";
		$this->update_refund_totals();
	}

	function add_tax_item($code='UNK',$amount=0,$is_base='Yes') {


		$sql=sprintf("update `Invoice Tax Dimension` set `%s`=%.2f where `Invoice Key`=%d",addslashes($code),$amount,$this->id );
		mysql_query($sql);
		// print "$sql\n";
		$sql=sprintf("insert into `Invoice Tax Bridge` values (%d,%s,%.2f,%s) on duplicate key update `Tax Amount`=%.2f, `Tax Base`=%s"
			,$this->id
			,prepare_mysql($code)
			,$amount
			,prepare_mysql($is_base)
			,$amount
			,prepare_mysql($is_base)

		);
		// print "$sql\n";
		mysql_query($sql);
	}



	function set_data_from_customer($customer_key,$store_key=false) {


		$customer=new Customer($customer_key);
		if (!$customer->id) {
			$customer= new Customer('create anonymous');
		} else
			$store_key=$customer->data['Customer Store Key'];



		$this->data['Invoice Customer Name']=$customer->get('Customer Name');
		$this->data['Invoice Customer Contact Name']=$customer->get('Customer Main Contact Name');



		$this->data['Invoice For Partner']='No';
		$this->data['Invoice For']='Customer';

		switch ($customer->data['Customer Level Type']) {
		case'Partner':
			$this->data['Invoice For Partner']='Yes';
			break;
		case'Staff':
			$this->data['Invoice For']='Staff';
			break;

		}

		$this->data['Invoice Customer Level Type']=$customer->data['Customer Level Type'];



		$this->data['Invoice Main Payment Method']=$customer->get('Customer Last Payment Method');

		//print_r($this->data);
		$this->set_data_from_store($store_key);


		return $customer;



	}
	function set_data_from_store($store_key) {
		$store=new Store($store_key);
		if (!$store->id) {
			$this->error=true;
			return;
		}



		$this->data['Invoice Currency']=$store->data['Store Currency Code'];
		$this->data['Invoice Store Code']=$store->data['Store Code'];
		$this->data['Invoice XHTML Store']=sprintf("<a href='store.php?id=%d'>%s</a>",$store->id,$store->get('Store Name'));

		$this->public_id_format=$store->data[ 'Store Order Public ID Format' ];





	}


	function prepare_file_as($number) {

		$number=strtolower($number);
		if (preg_match("/^\d+/",$number,$match)) {
			$part_number=$match[0];
			$file_as=preg_replace('/^\d+/',sprintf("%012d",$part_number),$number);

		}
		elseif (preg_match("/\d+$/",$number,$match)) {
			$part_number=$match[0];
			$file_as=preg_replace('/\d+$/',sprintf("%012d",$part_number),$number);

		}
		else {
			$file_as=$number;
		}

		return $file_as;
	}

	function update_title() {

		$this->data['Invoice Title']=$this->get_title();

		$sql=sprintf("update `Invoice Dimension` set `Invoice Title`=%s where `Invoice Key`=%d",
			prepare_mysql($this->data['Invoice Title']),
			$this->id
		);
		mysql_query($sql);
	}





	function get_title() {

		$orders=$this->get_orders_objects();

		$number_of_orders=count($orders);

		if ($number_of_orders==0) {
			if ($this->data['Invoice Type']=='Invoice') {
				$title=_("Invoice");
			}elseif ($this->data['Invoice Type']=='CreditNote') {
				$title=_("Credit Note");
			}else {
				$title=_("Refund");

			}
			return $title;
		}


		if ($this->data['Invoice Type']=='Invoice') {
			$title=ngettext("Invoice for order","Invoice for orders",$number_of_orders).' ';
		}elseif ($this->data['Invoice Type']=='CreditNote') {
			$title=ngettext("Credit note for order","Credit note for orders",$number_of_orders).' ';
		}else {
			$title=ngettext("Refund for order","Refund for orders",$number_of_orders).' ';

		}

		foreach ($orders as $order) {
			$title.=sprintf('<a class="id" href="order.php?id=%d">%s</a>, ',
				$order->id,
				$order->data['Order Public ID']
			);
		}

		$title=preg_replace('/\, $/','',$title);
		return $title;
	}



	function get_payment_keys($status='') {

		$payments=array();

		if ($status) {
			$where=' and `Payment Transaction Status`='.prepare_mysql($status);
		}else {
			$where='';
		}

		$sql=sprintf("select B.`Payment Key` from `Payment Dimension` PD left join `Invoice Payment Bridge` B on (B.`Payment Key`=PD.`Payment Key`)  where `Invoice Key`=%d %s",
			$this->id,
			$where
		);

		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$payments[$row['Payment Key']]=$row['Payment Key'];
		}
		return $payments;
	}


	function get_payment_objects($status='',$load_payment_account=false,$load_payment_service_provider=false) {


		$payments=array();

		if ($status) {
			$where=' and `Payment Transaction Status`='.prepare_mysql($status);
		}else {
			$where='';
		}

		$sql=sprintf("select `Payment Currency Code`,B.`Payment Key`,`Amount` from `Payment Dimension` PD left join `Invoice Payment Bridge` B on (B.`Payment Key`=PD.`Payment Key`)  where `Invoice Key`=%d %s",
			$this->id,
			$where
		);



		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {

			$payment=new Payment($row['Payment Key']);
			if ($load_payment_account)
				$payment->load_payment_account();
			if ($load_payment_service_provider)
				$payment->load_payment_service_provider();

			$payment->amount=$row['Amount'];
			$payment->formated_invoice_amount=money($row['Amount'],$row['Payment Currency Code']);



			$payments[$row['Payment Key']]=$payment;
		}
		return $payments;



	}

	function get_number_payments($status='') {


		return count($this->get_payment_keys($status));
	}

	function get_date($field) {
		return strftime("%e %b %Y",strtotime($this->data[$field].' +0:00'));
	}


	function delete() {


		$orders=$this->get_orders_objects();
		$dns=$this->get_delivery_notes_objects();

		$sql=sprintf("delete from `Order Invoice Bridge` where `Invoice Key`=%d   ",$this->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Tax Bridge` where `Invoice Key`=%d",$this->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Sales Representative Bridge`  where   `Invoice Key`=%d",$this->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Processed By Bridge`  where   `Invoice Key`=%d",$this->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Charged By Bridge`  where   `Invoice Key`=%d",$this->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Tax Dimension` where `Invoice Key`=%d",$this->id);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Delivery Note Bridge` where `Invoice Key`=%d   ",$this->id);
		mysql_query($sql)  ;

		$sql=sprintf("delete from `History Dimension`  where   `Direct Object`='Invoice' and `Direct Object Key`=%d",$this->id);
		mysql_query($sql);


		$payments=array();
		$sql=sprintf("select * from `Invoice Payment Bridge` where `Invoice Key`=%d",$this->id);
		$res=mysql_query($sql);
		while ($row=mysql_fetch_assoc($res)) {
			$payment=new Payment($row['Payment Key']);
			$payments[]=$payment;

		}



		$sql=sprintf("delete from `Invoice Payment Bridge`  where    `Invoice Key`=%d",$this->id);
		mysql_query($sql);


		$sql=sprintf("update `Payment Dimension`  set `Invoice Key`=NULL   where `Invoice Key`=%d",$this->id);
		mysql_query($sql);


		foreach ($payments as $payment) {
			$payment->update_balance();
		}


		$sql=sprintf("select `Category Key` from `Category Bridge`  where   `Subject`='Invoice' and `Subject Key`=%d",$this->id);
		$result_test_category_keys=mysql_query($sql);
		$_category_keys=array();
		while ($row_test_category_keys=mysql_fetch_array($result_test_category_keys, MYSQL_ASSOC)) {
			$_category_keys[$row_test_category_keys['Category Key']]=$row_test_category_keys['Category Key'];
		}
		$sql=sprintf("delete from `Category Bridge`  where   `Subject`='Invoice' and `Subject Key`=%d",$this->id);
		mysql_query($sql);

		foreach ($_category_keys as $_category_key) {
			$_category=new Category($_category_key);
			$_category->update_children_data();
			$_category->update_subjects_data();
		}


		$this->data ['Order Invoiced Balance Total Amount'] = 0;
		$this->data ['Order Invoiced Balance Net Amount'] = 0;
		$this->data ['Order Invoiced Balance Tax Amount'] = 0;
		$this->data ['Order Invoiced Outstanding Balance Total Amount'] = 0;
		$this->data ['Order Invoiced Outstanding Balance Net Amount'] = 0;
		$this->data ['Order Invoiced Outstanding Balance Tax Amount'] = 0;


		$sql=sprintf("delete from `Order Transaction Fact`  where    `Invoice Key`=%d  and (`Order Key`=0 or `Order Key` is NULL) ",$this->id);
		mysql_query($sql);

		$sql=sprintf("update  `Order Transaction Fact` set `Invoice Key`=NULL ,`Consolidated`='No'  where  `Invoice Key`=%d",$this->id);
		mysql_query($sql);


		$sql=sprintf("delete from `Order No Product Transaction Fact`  where    `Invoice Key`=%d  and (`Order Key`=0 or `Order Key` is NULL) ",$this->id);
		mysql_query($sql);

		$sql=sprintf("update `Order No Product Transaction Fact` set `Invoice Key`=NULL , `Consolidated`='No'   where  `Invoice Key`=%d",$this->id);
		mysql_query($sql);


		$sql=sprintf("delete from `Invoice Dimension`  where  `Invoice Key`=%d",$this->id);
		mysql_query($sql);

		foreach ($dns as $dn) {

			$dn->update_xhtml_invoices();


		}

		foreach ($orders as $order) {

			$order->update(array('Order Invoiced'=>'No'));

			$order->update_xhtml_invoices();
			$order->update_no_normal_totals();


		}

		$this->deleted=true;
	}




}
?>
