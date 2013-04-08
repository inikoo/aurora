<?php

include_once '../../class.Category.php';



function genRandomString() {
	$length = 1;
	$characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	$string = '';

	for ($p = 0; $p < $length; $p++) {
		$string .= $characters[mt_rand(0, strlen($characters))];
	}

	return $string;
}



function encrypt_tel($value,$do_it=true) {
	if (!$do_it)
		return $value;

	$value=preg_replace('/3/','8',$value);
	$value=preg_replace('/5/','3',$value);
	$value=preg_replace('/6/','4',$value);
	$value=preg_replace('/2/','6',$value);

	return $value;
}

function encrypt_email($value,$do_it=true) {
	if (!$do_it)
		return $value;
	$value=preg_replace('/yahoo|hotmail|gmail/','mmm2',$value);
	$value=preg_replace('/bt/','mx',$value);
	$value=preg_replace('/mail/','mmm3',$value);
	$value=preg_replace('/m/','r',$value);

	$value=preg_replace('/e/','a',$value);
	$value=preg_replace('/i/','ie',$value);
	$value=preg_replace('/o/','u',$value);
	$value=preg_replace('/g/','j',$value);
	$value=preg_replace('/h/','x',$value);
	$value=preg_replace('/b/','y',$value);
	$value=preg_replace('/d/','z',$value);
	$value=preg_replace('/p/','t',$value);
	$value=preg_replace('/t/','k',$value);
	$value=preg_replace('/f/','g',$value);

	$value=preg_replace('/s/','zz',$value);
	$value=preg_replace('/l/','q',$value);
	$value=preg_replace('/r/','w',$value);
	$value=preg_replace('/1/','2',$value);
	$value=preg_replace('/2/','3',$value);
	$value=preg_replace('/3/','1',$value);
	$value=preg_replace('/4/','5',$value);
	$value=preg_replace('/5/','6',$value);
	$value=preg_replace('/6/','4',$value);
	$value=preg_replace('/7/','8',$value);
	$value=preg_replace('/8/','9',$value);
	$value=preg_replace('/9/','7',$value);
	$value=preg_replace('/0/','i',$value);
	$value=preg_replace('/\@/','x@',$value);
	return $value;
}


function create_dn_invoice_transactions($transaction,$product,$used_parts_sku) {
	global $date_order,$products_data,$data_invoice_transactions,$data_dn_transactions,$estimated_w;




	if ($transaction['order']>0) {


		if ($transaction['order']<$transaction['reorder'])
			$transaction['reorder']=$transaction['order'];

		$products_data[]=array(
			'Product Key'=>$product->id,
			'Estimated Weight'=>$product->data['Product Gross Weight']*$transaction['order'],
			'qty'=>$transaction['order'],
			'gross_amount'=>$transaction['order']*$transaction['price'],
			'discount_amount'=>$transaction['order']*$transaction['price']*$transaction['discount'],
			'units_per_case'=>$product->data['Product Units Per Case']
		);

		//print_r($transaction);

		$net_amount=round(($transaction['order']-$transaction['reorder'])*$transaction['price']*(1-$transaction['discount']),2 );
		$gross_amount=round(($transaction['order']-$transaction['reorder'])*$transaction['price'],2);
		$net_discount=-$net_amount+$gross_amount;

		if ($net_amount>0 ) {
			$product->update_last_sold_date($date_order);
			$product->update_first_sold_date($date_order);
			$product->update_for_sale_since(date("Y-m-d H:i:s",strtotime("$date_order -1 second")));


			if ($product->updated_field['Product For Sale Since Date']) {
				$_date_order=date("Y-m-d H:i:s",strtotime("$date_order -2 second"));
				$sql=sprintf("update `History Dimension` set `History Date`=%s  where `Action`='created' and `Direct Object`='Product' and `Direct Object Key`=%d  ",prepare_mysql($_date_order),$product->pid);
				mysql_query($sql);


			}

		}


		$data_invoice_transactions[]=array(
			'original_amount'=>round(($transaction['order']-$transaction['reorder'])*$transaction['original_price']*(1-$transaction['discount']),2 ),
			'Product Key'=>$product->id,
			'invoice qty'=>$transaction['order']-$transaction['reorder'],
			'gross amount'=>$gross_amount,
			'discount amount'=>$net_discount,
			'current payment state'=>'Paid',
			'description'=>$transaction['description'].($transaction['code']!=''?" (".$transaction['code'].")":''),
			'credit'=>$transaction['credit']


		);
		// print_r($data_invoice_transactions);
		$estimated_w+=$product->data['Product Gross Weight']*($transaction['order']-$transaction['reorder']);
		//print "$estimated_w ".$product->data['Product Gross Weight']." ".($transaction['order']-$transaction['reorder'])."\n";


		$data_dn_transactions[]=array(
			'otf_key'=>'',
			'Code'=>$product->code,
			'Product Key'=>$product->id,
			'Estimated Weight'=>$product->data['Product Gross Weight']*($transaction['order']-$transaction['reorder']),
			'Product ID'=>$product->data['Product ID'],
			'Delivery Note Quantity'=>$transaction['order']-$transaction['reorder'],
			'Current Autorized to Sell Quantity'=>$transaction['order'],
			'Shipped Quantity'=>$transaction['order']-$transaction['reorder'],
			'No Shipped Due Out of Stock'=>$transaction['reorder'],
			'Order Quantity'=>$transaction['order'],
			'No Shipped Due No Authorized'=>0,
			'No Shipped Due Not Found'=>0,
			'No Shipped Due Other'=>0,
			'amount in'=>(($transaction['order']-$transaction['reorder'])*$transaction['price'])*(1-$transaction['discount']),
			'given'=>0,
			'required'=>$transaction['order'],
			'discount_amount'=>$transaction['order']*$transaction['price']*$transaction['discount'],

			'pick_method'=>'historic',
			'pick_method_data'=>array(
				'parts_sku'=>$used_parts_sku
			)
		);


	}
	if ($transaction['bonus']>0) {
		$products_data[]=array(
			'Product Key'=>$product->id,
			'qty'=>0,
			'bonus qty'=>$transaction['bonus'],
			'gross_amount'=>0,
			'discount_amount'=>0,
			'Estimated Weight'=>0,
			'units_per_case'=>$product->data['Product Units Per Case']
		);
		$data_invoice_transactions[]=array(
			'Product Key'=>$product->id,
			'credit'=>0,
			'original_amount'=>0,
			'description'=>$transaction['description'].($transaction['code']!=''?" (".$transaction['code'].")":''),
			'invoice qty'=>$transaction['bonus'],
			'gross amount'=>($transaction['bonus'])*$transaction['price'],
			'discount amount'=>($transaction['bonus'])*$transaction['price'],
			'current payment state'=>'No Applicable'
		);

		$estimated_w+=$product->data['Product Gross Weight']*$transaction['bonus'];
		$data_dn_transactions[]=array(
			'otf_key'=>'',
			'Code'=>$product->code,
			'Product Key'=>$product->id,
			'Product ID'=>$product->data['Product ID'],
			'Delivery Note Quantity'=>$transaction['bonus'],
			'Current Autorized to Sell Quantity'=>$transaction['bonus'],
			'Shipped Quantity'=>$transaction['bonus'],
			'Order Quantity'=>0,
			'No Shipped Due Out of Stock'=>0,
			'No Shipped Due No Authorized'=>0,
			'No Shipped Due Not Found'=>0,
			'No Shipped Due Other'=>0,
			'Estimated Weight'=>$product->data['Product Gross Weight']*($transaction['bonus']),
			'amount in'=>0,
			'given'=>$transaction['bonus'],
			'discount_amount'=>0,
			'required'=>0,
			'pick_method'=>'historic',
			'pick_method_data'=>array(
				'parts_sku'=>$used_parts_sku
			)

		);




	}


	//print_r($data_dn_transactions);

}




function filter_header($data) {
	foreach ($data as $key=>$value) {
		$value=preg_replace("/\\\\\"/",'"',$value);

		$data[$key]=_trim($value);
	}

	if (preg_match('/\d{2}-\d{2}-\d{2}/',$data['notes2']))
		$data['notes2']='';
	return $data;
}


function round_header_data_totals() {
	global $header_data;

	if (is_numeric($header_data['charges'])) {
		$header_data['charges']=round($header_data['charges'],2);
	} else {
		$header_data['charges']=0.00;
	}

	if (is_numeric($header_data['shipping'])) {
		$header_data['shipping']=round($header_data['shipping'],2);
	} else {
		$header_data['shipping']=0.00;
	}

	$header_data['total_topay']=round($header_data['total_topay'],2);
	$header_data['total_net']=round($header_data['total_net'],2);

	$header_data['tax1']=round($header_data['tax1'],2);
	if ($header_data['tax2']=='') {
		$header_data['tax2']=0.00;
	} else {
		round($header_data['tax2'],2);
	};

}

function delete_old_data($delete_record=false) {
	global $store_code,$order_data_id;



	// Save picking/packing data

	if ($delete_record) {
		$sql=sprintf("delete `Order Import Metadata` where `Metadata`=%s",prepare_mysql($store_code.$order_data_id));
		mysql_query($sql);
	}else {


		$sql=sprintf("select *  from `Delivery Note Dimension`  where `Delivery Note Metadata`=%s  ",prepare_mysql($store_code.$order_data_id));


		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			//print_r($row);
			$sql=sprintf("INSERT INTO `Order Import Metadata` ( `Metadata`,`Name`, `Start Picking Date`, `Finish Picking Date`, `Start Packing Date`, `Finish Packing Date`, `Approve Date`, `Picker Keys`, `Packer Keys`) VALUES (%s,%s,%s,%s,%s,%s,%s,%s,%s) ON DUPLICATE KEY UPDATE
		`Name`=%s,`Start Picking Date`=%s, `Finish Picking Date`=%s, `Start Packing Date`=%s, `Finish Packing Date`=%s, `Approve Date`=%s, `Picker Keys`=%s, `Packer Keys`=%s",
				prepare_mysql($store_code.$order_data_id),
				prepare_mysql($row['Delivery Note ID']),
				prepare_mysql($row['Delivery Note Date Start Picking']),
				prepare_mysql($row['Delivery Note Date Finish Picking']),
				prepare_mysql($row['Delivery Note Date Start Packing']),
				prepare_mysql($row['Delivery Note Date Finish Packing']),
				prepare_mysql($row['Delivery Note Date Finish Packing']),

				prepare_mysql($row['Delivery Note Date Done Approved']),
				prepare_mysql($row['Delivery Note Assigned Packer Key']),
				prepare_mysql($row['Delivery Note ID']),
				prepare_mysql($row['Delivery Note Date Start Picking']),
				prepare_mysql($row['Delivery Note Date Finish Picking']),
				prepare_mysql($row['Delivery Note Date Start Packing']),
				prepare_mysql($row['Delivery Note Date Finish Packing']),
				prepare_mysql($row['Delivery Note Date Done Approved']),

				prepare_mysql($row['Delivery Note Assigned Picker Key']),
				prepare_mysql($row['Delivery Note Assigned Packer Key'])

			);

			mysql_query($sql);
			//print "$sql\n";
		}
	}



	//================


	$sql=sprintf("delete from `Order Post Transaction Dimension`  where   `Order Post Transaction Metadata`=%s  ",prepare_mysql($store_code.$order_data_id));
	mysql_query($sql);

	$sql=sprintf("select `Order Key`  from `Order Dimension`  where `Order Original Metadata`=%s  ",prepare_mysql($store_code.$order_data_id));


	$result_test=mysql_query($sql);
	while ($row_test=mysql_fetch_array($result_test, MYSQL_ASSOC)) {


		$sql=sprintf("select `History Key` from `History Dimension`  where   `Direct Object`='Order' and `Direct Object Key`=%d",$row_test['Order Key']);
		$result_test2=mysql_query($sql);
		while ($row_test2=mysql_fetch_array($result_test2, MYSQL_ASSOC)) {
			$sql=sprintf("delete from `Customer History Bridge`  where   `History Key`=%d",$row_test2['History Key']);
			mysql_query($sql);
		}

		$sql=sprintf("delete from `History Dimension`  where   `Direct Object`='Order' and `Direct Object Key`=%d",$row_test['Order Key']);
		mysql_query($sql);

		$sql=sprintf("delete from `Order Deal Bridge`  where   `Order Key`=%d",$row_test['Order Key']);
		mysql_query($sql);

		$sql=sprintf("delete from `Order Transaction Deal Bridge`  where   `Order Key`=%d",$row_test['Order Key']);
		mysql_query($sql);

		$sql=sprintf("delete from `Order Sales Representative Bridge`  where   `Order Key`=%d",$row_test['Order Key']);
		mysql_query($sql);

		$sql=sprintf("delete from `History Dimension` where `Direct Object Key`=%d and `Direct Object`='Sale'   ",$row_test['Order Key']);
		mysql_query($sql);

		$sql=sprintf("delete from `Search Full Text Dimension`  where   `Subject`='Order' and `Subject Key`=%d",$row_test['Order Key']);
		mysql_query($sql);


		$sql=sprintf("delete from `Order Invoice Bridge` where `Order Key`=%d   ",$row_test['Order Key']);
		mysql_query($sql);

		$sql=sprintf("delete from `Order Delivery Note Bridge` where `Order Key`=%d   ",$row_test['Order Key']);
		mysql_query($sql)  ;
	};
	$sql=sprintf("select `Invoice Key`  from `Invoice Dimension`  where `Invoice Metadata`=%s  ",prepare_mysql($store_code.$order_data_id));
	$result_test=mysql_query($sql);
	while ($row_test=mysql_fetch_array($result_test, MYSQL_ASSOC)) {
		$sql=sprintf("delete from `Order Invoice Bridge` where `Invoice Key`=%d   ",$row_test['Invoice Key']);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Tax Bridge` where `Invoice Key`=%d",$row_test['Invoice Key']);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Sales Representative Bridge`  where   `Invoice Key`=%d",$row_test['Invoice Key']);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Processed By Bridge`  where   `Invoice Key`=%d",$row_test['Invoice Key']);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Charged By Bridge`  where   `Invoice Key`=%d",$row_test['Invoice Key']);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Tax Dimension` where `Invoice Key`=%d",$row_test['Invoice Key']);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Delivery Note Bridge` where `Invoice Key`=%d   ",$row_test['Invoice Key']);
		mysql_query($sql)  ;

		$sql=sprintf("delete from `History Dimension`  where   `Direct Object`='Invoice' and `Direct Object Key`=%d",$row_test['Invoice Key']);
		mysql_query($sql);


		$sql=sprintf("select `Category Key` from `Category Bridge`  where   `Subject`='Invoice' and `Subject Key`=%d",$row_test['Invoice Key']);
		$result_test_category_keys=mysql_query($sql);
		$_category_keys=array();
		while ($row_test_category_keys=mysql_fetch_array($result_test_category_keys, MYSQL_ASSOC)) {
			$_category_keys[]=$row_test_category_keys['Category Key'];
		}
		$sql=sprintf("delete from `Category Bridge`  where   `Subject`='Invoice' and `Subject Key`=%d",$row_test['Invoice Key']);
		mysql_query($sql);

		foreach ($_category_keys as $_category_key) {
			$_category=new Category($_category_key);
			$_category->update_children_data();
			$_category->update_subjects_data();
		}


	};
	$sql=sprintf("select `Delivery Note Key`  from `Delivery Note Dimension`  where `Delivery Note Metadata`=%s  ",prepare_mysql($store_code.$order_data_id));
	$result_test=mysql_query($sql);
	while ($row_test=mysql_fetch_array($result_test, MYSQL_ASSOC)) {

		$sql=sprintf("select `History Key` from `History Dimension`  where   `Direct Object Key`=%d and `Direct Object` in ('Delivery Note','After Sale')   ",$row_test['Delivery Note Key']);
		$result_test2=mysql_query($sql);
		while ($row_test2=mysql_fetch_array($result_test2, MYSQL_ASSOC)) {
			$sql=sprintf("delete from `Customer History Bridge`  where   `History Key`=%d",$row_test2['History Key']);
			mysql_query($sql);
		}


		$sql=sprintf("delete from `Order Delivery Note Bridge` where `Delivery Note Key`=%d   ",$row_test['Delivery Note Key']);
		mysql_query($sql);

		$sql=sprintf("delete from `Invoice Delivery Note Bridge` where `Delivery Note Key`=%d   ",$row_test['Delivery Note Key']);
		mysql_query($sql)  ;


		$sql=sprintf("delete from `History Dimension` where `Direct Object Key`=%d and `Direct Object` in ('Delivery Note','After Sale')   ",$row_test['Delivery Note Key']);
		mysql_query($sql);

	};





	$sql=sprintf("delete from `Order No Product Transaction Fact` where `Metadata`=%s",prepare_mysql($store_code.$order_data_id));
	if (!mysql_query($sql))
		print "$sql Warning can no delete old order";

	//delete things
	$sql=sprintf("delete from `Order Dimension` where `Order Original Metadata`=%s",prepare_mysql($store_code.$order_data_id));
	//  print $sql;

	if (!mysql_query($sql))
		print "$sql Warning can no delete old order";
	$sql=sprintf("delete from `Invoice Dimension` where `Invoice Metadata`=%s",prepare_mysql($store_code.$order_data_id));

	if (!mysql_query($sql))
		print "$sql Warning can no delete old inv";
	$sql=sprintf("delete from `Delivery Note Dimension` where `Delivery Note Metadata`=%s",prepare_mysql($store_code.$order_data_id));
	if (!mysql_query($sql))
		print "$sql Warning can no delete old dn";


	$sql=sprintf("select GROUP_CONCAT(`Refund Metadata`) as `Refund_Metadata` from `Order Transaction Fact` where `Metadata`=%s group by `Refund Metadata`",
		prepare_mysql($store_code.$order_data_id));
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		foreach (preg_split('/\,/',$row['Refund_Metadata']) as $refund_metadata) {
			if (preg_match('/\d+/',$refund_metadata,$match)) {
				$refund_order_data_id=$match[0];
				$refund_metadata_store_code=preg_replace("/$refund_order_data_id/",'',$refund_metadata);
				switch ($refund_metadata_store_code) {
				case 'U':
					$sql=sprintf("update orders_data.orders set last_transcribed=null where id=%d",$refund_order_data_id);
					break;
				case 'F':
					$sql=sprintf("update fr_orders_data.orders set last_transcribed=null where id=%d",$refund_order_data_id);
					break;
				case 'D':
					$sql=sprintf("update de_orders_data.orders set last_transcribed=null where id=%d",$refund_order_data_id);
					break;
				case 'E':
					$sql=sprintf("update ci_orders_data.orders set last_transcribed=null where id=%d",$refund_order_data_id);
					break;

				}

			}
		}


	}

	$sql=sprintf("delete from `Order Transaction Fact` where `Metadata`=%s",prepare_mysql($store_code.$order_data_id));
	mysql_query($sql);
	$sql=sprintf("update `Order Transaction Fact`  `Invoice Transaction Net Refund Amount`=0,`Invoice Transaction Tax Refund Amount`=0,`Invoice Transaction Outstanding Refund Net Balance`=0 ,`Invoice Transaction Outstanding Refund Tax Balance`=0,`Refund Key`=NULL,`Refund Metadata`='' where `Refund Metadata`=%s   and `Order Key`>0  "
		,prepare_mysql($store_code.$order_data_id));
	mysql_query($sql);
	$sql=sprintf("delete from `Order Transaction Fact` where `Refund Metadata`=%s and `Order Key` IS NULL",prepare_mysql($store_code.$order_data_id));
	mysql_query($sql);
	$sql=sprintf("delete from `Order Transaction Fact` where `Refund Metadata`=%s and  `Order Key`=0",prepare_mysql($store_code.$order_data_id));
	mysql_query($sql);


	$parts_to_update_stock=array();
	$sql=sprintf("select `Part SKU`,`Location Key` from  `Inventory Transaction Fact` where `Metadata`=%s   ",prepare_mysql($store_code.$order_data_id));
	$res_q=mysql_query($sql);
	while ($row_q=mysql_fetch_assoc($res_q)) {
		$parts_to_update_stock[]=$row_q['Part SKU'].'_'.$row_q['Location Key'];
	}


	$sql=sprintf("delete from `Inventory Transaction Fact` where `Metadata`=%s   ",prepare_mysql($store_code.$order_data_id));
	// print "$sql\n";
	if (!mysql_query($sql))
		print "$sql Warning can no delete old inv";

	foreach ($parts_to_update_stock as $part_to_update_stock) {
		$part_location=new PartLocation($part_to_update_stock);
		$part_location->update_stock();
	}




	$sql=sprintf("delete from `Order No Product Transaction Fact` where `Metadata`=%s ",prepare_mysql($store_code.$order_data_id));
	if (!mysql_query($sql))
		print "$sql Warning can no delete oldhidt nio prod";


}


function adjust_invoice($invoice,$order,$continue=true) {
	global $header_data,$tax_category_object;

	$adjust_transactions=array();

	if ($header_data['tax2']=='') {
		$header_data['tax2']=0;
	}
	$tax=$header_data['tax1']+$header_data['tax2'];
	//print_r($header_data);
	//printf("\nInvoice Totals: %f + %f =%f\n",$invoice->data['Invoice Total Net Amount'],$invoice->data['Invoice Total Tax Amount'],$invoice->data['Invoice Total Amount']);
	//printf("Invoice Totals: %f + %f =%f\n",$header_data['total_net'],$tax,$header_data['total_topay']);

	$diff_net=round($header_data['total_net']-$invoice->data['Invoice Total Net Amount'],2);
	$diff_tax=round($tax-$invoice->data['Invoice Total Tax Amount'],2);
	$total_diff=round($header_data['total_topay']-$invoice->data['Invoice Total Amount'],2);

	if ($total_diff==0 and !$continue)
		return true;

	// printf("\nDiff Net %s Tax %s Total %s \n",$diff_net,$diff_tax,$total_diff);


	if ($diff_net==0 and  $diff_tax==0 and $total_diff==0) {
		return;
	}

	if ($diff_tax!=0) {
		$adjust_transactions[]=array('Net'=>0,'Tax'=>$diff_tax,'Transaction Description'=>_('Tax Adjustment'),'Tax Code'=>'');
	}
	if ($diff_net!=0) {
		$adjust_transactions[]=array('Net'=>$diff_net,'Tax'=>0,'Transaction Description'=>_('Net Adjustment'),'Tax Code'=>'');
	}
	if ($diff_tax==0 and $diff_net==0 and $total_diff!=0) {
		$adjust_transactions[]=array('Net'=>$total_diff,'Tax'=>0,'Transaction Description'=>_('Wrong Net+Tax Addition Adjustment'),'Tax Code'=>'');

	}

	//   print_r($adjust_transactions);




	foreach ($adjust_transactions as $adjust_data) {
		$sql=sprintf("insert into `Order No Product Transaction Fact` (`Order Key`,`Order Date`,`Invoice Key`,`Invoice Date`,`Transaction Type`,`Transaction Description`,`Transaction Invoice Net Amount`,`Tax Category Code`,`Transaction Invoice Tax Amount`,`Transaction Outstanding Net Amount Balance`,`Transaction Outstanding Tax Amount Balance`,`Currency Code`,`Currency Exchange`,`Metadata`)  values (%s,%s,%d,%s,%s,%s,%.2f,%s,%.2f,%.2f,%.2f,%s,%.2f,%s)  ",
			prepare_mysql($order->id),

			prepare_mysql($order->data['Order Date']),

			$invoice->id,
			prepare_mysql($invoice->data['Invoice Date']),
			prepare_mysql('Adjust'),

			prepare_mysql($adjust_data['Transaction Description']),
			$adjust_data['Net'],
			prepare_mysql($adjust_data['Tax Code']),
			$adjust_data['Tax'],
			$adjust_data['Net'],
			$adjust_data['Tax'],
			prepare_mysql($invoice->data['Invoice Currency']),
			$invoice->data['Invoice Currency Exchange'],
			prepare_mysql($invoice->data['Invoice Metadata'])
		);

		mysql_query($sql);
		//print "$continue $sql\n";
	}
	$invoice->update_totals();


	$diff_net=round($header_data['total_net']-$invoice->data['Invoice Total Net Amount'],2);
	$diff_tax=round($tax-$invoice->data['Invoice Total Tax Amount'],2);
	$total_diff=round($header_data['total_topay']-$invoice->data['Invoice Total Amount'],2);

	if (!$continue and $total_diff==0) {
		return true;
	}
	//print_r($invoice->data);
	// printf("\n***$tax  ".$invoice->data['Invoice Total Items']."   ".$invoice->data['Invoice Total Tax Amount']."\n

	//\nInvoice Totals: %f + %f =%f\n",$invoice->data['Invoice Total Net Amount'],$invoice->data['Invoice Total Tax Amount'],$invoice->data['Invoice Total Amount']);

	// printf("\nDiff Net %s Tax %s Total %s \n",$diff_net,$diff_tax,$total_diff);
	//  exit;



	if ($diff_net!=0 or  $diff_tax!=0 or $total_diff!=0 and $continue) {
		if (adjust_invoice($invoice,$order,false))
			return;
		$diff_net=round($header_data['total_net']-$invoice->data['Invoice Total Net Amount'],2);
		$diff_tax=round($tax-$invoice->data['Invoice Total Tax Amount'],2);
		$total_diff=round($header_data['total_topay']-$invoice->data['Invoice Total Amount'],2);
	}

	if ($diff_net!=0 or  $diff_tax!=0 or $total_diff!=0) {
		printf("\nAft Invoice Totals: %f + %f =%f\n",$invoice->data['Invoice Total Net Amount'],$invoice->data['Invoice Total Tax Amount'],$invoice->data['Invoice Total Amount']);
		printf("Aft  Invoice Totals: %f + %f =%f\n",$header_data['total_net'],$tax,$header_data['total_topay']);

		printf("Aft Diff Net %s Tax %s Total %s \n",$diff_net,$diff_tax,$total_diff);
		print("Error in adjust\n");
	}

}
function get_data($header_data) {
	global $shipping_net,$charges_net,$extra_shipping,$payment_method,$picker_data,$customer_service_rep_data,$packer_data,$parcels,$parcel_type,$editor;

	$shipping_net=round($header_data['shipping']+$extra_shipping,2);
	$charges_net=round($header_data['charges'],2);
	$payment_method=parse_payment_method($header_data['pay_method']);
	$picker_data=get_user_id($header_data['pickedby'],true,'&view=picks','',$editor);
	$packer_data=get_user_id($header_data['packedby'],true,'&view=packs','',$editor);
	$customer_service_rep_data=get_user_id($header_data['takenby'],true,'','',$editor);
	list($parcels,$parcel_type)=parse_parcels($header_data['parcels']);
}

function create_order($data) {
	global $customer_service_rep_data,$customer_key,$filename,$store_code,$order_data_id,$date_order,$shipping_net,$charges_net,$order,$dn,$tax_category_object,$header_data,$data_dn_transactions,$discounts_with_order_as_term;

	$order_data=array(
		'type'=>'system',
		'Customer Key'=>$customer_key,
		'Order Original Data MIME Type'=>'application/vnd.ms-excel',
		'Order Original Data Source'=>'Excel File',
		'Order Original Data Filename'=>$filename,
		'Order Type'=>$data['Order Type'],
		'Order Original Metadata'=>$store_code.$order_data_id,
		'editor'=>$data['editor'],
		'Order Public ID'=>$data['order id'],
		'Order Date'=>$date_order,
		'Order Tax Code'=>$tax_category_object->data['Tax Category Code'],
		'Order Sales Representative Keys'=>$customer_service_rep_data,

		//     'Order Ship To Key'=>(array_key_exists('Order Ship To Key',$data)?$data['Order Ship To Key']:false)
	);
	//print_r($order_data);
	//if(isset($data['Order Ship To Key']))
	//    $order_data['Order Ship To Key']=$data['Order Ship To Key'];


	//print "creating order\n";

	$order=new Order('new',$order_data);

	if ($header_data['collection']=='Yes') {
		$order->update_order_is_for_collection('Yes');
	} else {
		$order-> update_ship_to($data['Order Ship To Key']);

	}


	$discounts_map=array();


	//print_r($data_dn_transactions);


	foreach ($data_dn_transactions as $ddt_key=>$transaction) {

		if ($transaction['Order Quantity']>0) {



			$product=new Product('id',$transaction['Product Key']);

			$quantity=$transaction['Order Quantity'];
			$gross=$quantity*$product->data['Product History Price'];
			$estimated_weight=$quantity*$product->data['Product Gross Weight'];


			//print_r($transaction);

			$_supplier_metadata=array();

			if (is_array($transaction['pick_method_data']['parts_sku'])) {
				foreach ($transaction['pick_method_data']['parts_sku'] as $__key=>$__value) {
					$_supplier_metadata[$__key]=$__value;

				}
			}


			$data=array(
				'Estimated Weight'=>$estimated_weight,
				'date'=>$date_order,
				'Product Key'=>$product->id,

				'gross_amount'=>$gross,
				'discount_amount'=>0,
				'qty'=>$quantity,
				'bonus qty'=>0,
				'units_per_case'=>$product->data['Product Units Per Case'],
				'Current Dispatching State'=>'In Process',
				'Current Payment State'=>'Waiting Payment',
				'Metadata'=>$store_code.$order_data_id,
				'Supplier Metadata'=>serialize($_supplier_metadata)
			);

			//  print_r($data);
			$order->skip_update_after_individual_transaction=true;
			$transaction_data=$order->add_order_transaction($data,true);
			if ($transaction_data['updated'])
				$discounts_map[$transaction_data['otf_key']]=$transaction['discount_amount'];

			$data_dn_transactions[$ddt_key]['otf_key']=$transaction_data['otf_key'];



		}
		elseif ( $transaction['given']>0) {



			$product=new Product('id',$transaction['Product Key']);
			$quantity=$transaction['given'];
			$gross=0;
			$estimated_weight=$quantity*$product->data['Product Gross Weight'];

			$_supplier_metadata=array();
			foreach ($transaction['pick_method_data']['parts_sku'] as $__key=>$__value) {
				$_supplier_metadata[$__key]=$__value['supplier_product_pid'];

			}

			$data=array(
				'Estimated Weight'=>$estimated_weight,
				'date'=>$date_order,
				'Product Key'=>$product->data['Product Current Key'],

				'gross_amount'=>$gross,
				'discount_amount'=>0,
				'bonus qty'=>$quantity,
				'units_per_case'=>$product->data['Product Units Per Case'],
				'Current Dispatching State'=>'In Process',
				'Current Payment State'=>'Waiting Payment',
				'Metadata'=>$store_code.$order_data_id,
				'Supplier Metadata'=>serialize($_supplier_metadata)
			);
			//   print_r($data);

			$order->skip_update_after_individual_transaction=true;
			$transaction_data=$order->add_order_transaction($data,true);
			$data_dn_transactions[$ddt_key]['otf_key']=$transaction_data['otf_key'];

			//    print_r($transaction_data);

		}


	}

	foreach ($discounts_with_order_as_term as $_deal_key) {
		$sql=sprintf("insert into `Order Deal Bridge` values(%d,%d,'Yes','No') ON DUPLICATE KEY UPDATE `Used`='No'",$order->id,$_deal_key);
		mysql_query($sql);

	}

	$order->authorize_all();

	$order->update_order_discounts();
	$order->update_discounts();
	$order->update_item_totals_from_order_transactions();

	$order->update_shipping();
	$order->update_charges();
	$order->update_item_totals_from_order_transactions();

	$order->update_no_normal_totals();
	$order->update_totals_from_order_transactions();


	//print_r($discounts_map);

	foreach ($discounts_map as $otf_key=>$discount) {

		$sql=sprintf("select `Order Transaction Total Discount Amount` from `Order Transaction Fact` where `Order Transaction Fact Key`=%d",$otf_key);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$tmp=(float) $row['Order Transaction Total Discount Amount'];
			$tmp2=(float) round($discount,3);
			//print $tmp.' '.$tmp2."\n";
			if ($tmp!=$tmp2) {
				// print "xxx\n";
				$order->update_transaction_discount_amount($otf_key,$discount);

			}
		}
	}


	$order->update_deal_bridge_from_assets_deals();
	$order->update_deals_usage();
	$order->update_number_items();
	//$order->categorize();
	$order->update_shipping_amount($shipping_net);
	$charges_data=array(
		'Charge Net Amount'=>$charges_net,
		'Charge Tax Amount'=>$charges_net*$tax_category_object->data['Tax Category Rate'],
		'Charge Key'=>0,
		'Charge Description'=>'Charge'
	);
	$order->update_charges_amount($charges_data);


	//print "sending to warehouse\n";

	//$order->update_stock=false;


	if (count($data_dn_transactions)>0) {

		list($start_picking_date,$finish_picking_date,$start_packing_date,$finish_packing_date,$approve_date,$pickers_from_import,$packers_from_import)=get_pp_data($date_order,$store_code,$order_data_id);

		//print "$start_picking_date,$finish_picking_date,$start_packing_date,$finish_packing_date,$approve_date\n";
		$dn=$order->send_to_warehouse($finish_picking_date);
		if ($finish_picking_date!=$date_order) {
			print " Picked ";
			$sql=sprintf("update  `Inventory Transaction Fact` set `Date`=%s,`Date Created`=%s where `Delivery Note Key` =%d  ",
				prepare_mysql($date_order),
				prepare_mysql($date_order),
				$dn->id);
			mysql_query($sql);

			$sql=sprintf("update  `Delivery Note Dimension` set `Delivery Note Date`=%s,`Delivery Note Date Created`=%s where `Delivery Note Key` =%d  ",
				prepare_mysql($date_order),
				prepare_mysql($date_order),
				$dn->id);
			mysql_query($sql);

			send_order($data,$data_dn_transactions,$just_pick=true);

		}



	}
	return $order;

}


function get_pp_data($date_order,$store_code,$order_data_id) {
	$sql=sprintf("select * from  `Order Import Metadata` where `Metadata`=%s",prepare_mysql($store_code.$order_data_id));
	//print "$sql\n";
	$res=mysql_query($sql);
	if ($order_import_metadata=mysql_fetch_assoc($res)) {
		//print_r($order_import_metadata);
	}else {
		unset($order_import_metadata);
	}

	$index_date=$date_order;

	if (isset($order_import_metadata) and $order_import_metadata['Start Picking Date']!='') {
		$start_picking_date=$order_import_metadata['Start Picking Date'];
		$index_date=$start_picking_date;
	}else {
		$start_picking_date=$index_date;
	}
	if (isset($order_import_metadata) and $order_import_metadata['Finish Picking Date']!='') {
		$finish_picking_date=$order_import_metadata['Finish Picking Date'];
		$index_date=$finish_picking_date;
	}else {
		$finish_picking_date=$index_date;
	}

	if (isset($order_import_metadata) and $order_import_metadata['Start Packing Date']!='') {
		$start_packing_date=$order_import_metadata['Start Packing Date'];
		$index_date=$start_packing_date;
	}else {
		$start_packing_date=$index_date;
	}
	if (isset($order_import_metadata) and $order_import_metadata['Finish Packing Date']!='') {
		$finish_packing_date=$order_import_metadata['Finish Packing Date'];
		$index_date=$finish_packing_date;
	}else {
		$finish_packing_date=$index_date;
	}
	if (isset($order_import_metadata) and $order_import_metadata['Approve Date']!='') {
		$approve_date=$order_import_metadata['Approve Date'];
		$index_date=$approve_date;
	}else {
		$approve_date=$index_date;
	}

	$pickers=$order_import_metadata['Picker Keys'];
	$packers=$order_import_metadata['Packer Keys'];

	return array($start_picking_date,$finish_picking_date,$start_packing_date,$finish_packing_date,$approve_date,$pickers,$packers);

}

function send_order($data,$data_dn_transactions,$just_pick=false) {

	global $customer_key,$filename,$store_code,$order_data_id,$date_order,$shipping_net,$charges_net,$order,$dn,$invoice,$shipping_net;
	global $charges_net,$order,$dn,$payment_method,$date_inv,$extra_shipping,$parcel_type;
	global $customer_service_rep_data,$packer_data,$picker_data,$parcels,$credits,$tax_category_object,$tipo_order;


	list($start_picking_date,$finish_picking_date,$start_packing_date,$finish_packing_date,$approve_date,$pickers_from_import,$packers_from_import)=get_pp_data($date_order,$store_code,$order_data_id);

	if ($pickers_from_import) {
		$picker_staff_key=$pickers_from_import;
	}else {
		if (count($picker_data['id'])==0) {
			$picker_staff_key=0;
		}else {
			$picker_staff_key=$picker_data['id'][0];
		}
	}

	if ($packers_from_import) {
		$packer_staff_key=$packers_from_import;
	}else {
		if (count($packer_data['id'])==0) {
			$packer_staff_key=0;
		}else {
			$packer_staff_key=$packer_data['id'][0];
		}
	}
	if (!isset($dn)) {


		print " No transactions  ";


		$invoice=$order->create_invoice($date_inv);
		//print_r($invoice);

		foreach ($credits as $credit) {
			$credit_data=array(
				'Affected Order Key'=>$order->id,
				'Order Key'=>($credit['parent_key']=='NULL'?0:$credit['parent_key']),
				'Transaction Description'=>$credit['description'],
				'Tax Category Code'=>$tax_category_object->data['Tax Category Code'],
				'Transaction Invoice Net Amount'=>$credit['value'],
				'Transaction Invoice Tax Amount'=>$credit['value']*$tax_category_object->data['Tax Category Rate'],
				'Metadata'=>$store_code.$order_data_id
			);
			$invoice->add_credit_no_product_transaction($credit_data);
		}
		$_invoice_data=  array(
			'Invoice Metadata'=>$store_code.$order_data_id,
			'Invoice Shipping Net Amount'=>array(
				'Amount'=>$shipping_net,
				'tax_code'=>$tax_category_object->data['Tax Category Code']
			),
			'Invoice Charges Net Amount'=>array(
				'Transaction Invoice Net Amount'=> $charges_net,
				'Transaction Description'=>_('Charges')
			)


		);



		$invoice->update($_invoice_data);
		$invoice->update_totals();


		//adjust_invoice($invoice,$order);




		$invoice->pay('full',array(
				'Invoice Paid Date'=>$date_inv,
				'Payment Method'=>$payment_method
			));

		$order->get_data('id',$order->id);

		$order->update_xhtml_invoices();
		$order->update_no_normal_totals();
		$order->set_order_as_completed($date_inv);
		return;
	}






	$dn->start_picking($picker_staff_key,$start_picking_date);


	//print_r($data_dn_transactions);
	$skus_to_pick_data=array();

	//print_r($dn);
	$_picked_qty=array();
	$_out_of_stock_qty=array();

	// print_r($data_dn_transactions);

	foreach ($data_dn_transactions as $key=>$value) {

		//print_r($value);

		$shipped_quantity=round($value['Shipped Quantity'],8);
		$out_of_stock_quantity=round($value['No Shipped Due Out of Stock'],8);;
		//print_r($value);

		//   print $value['Code']."  ship ".$value['Shipped Quantity']."   given ".$value['given']." \n";
		//if($date_order!=$finish_picking_date){
		// $dn->actualize_inventory_transaction_facts($finish_picking_date);
		//}


		$sql=sprintf("select `Inventory Transaction Key`,`Required`,`Given`,`Map To Order Transaction Fact Metadata` from `Inventory Transaction Fact` where `Map To Order Transaction Fact Key` =%d order by `Inventory Transaction Key` ",$value['otf_key']);
		$res=mysql_query($sql);


		$num_rows = mysql_num_rows($res);

		if (!$num_rows) {

			if ($value['Code']=='Freight') {
				//print "Freight\n";
			}else {

				print_r($value);

				exit("==============\n  $key\n $sql  $date_inv  Error no itf-otf map1\n");
			}
		}

		while ($row=mysql_fetch_assoc($res)) {
			$itf=$row['Inventory Transaction Key'];

			$metadata=preg_split('/;/',$row['Map To Order Transaction Fact Metadata']);
			$parts_per_product=$metadata[1];
			$part_index=$metadata[0];
			$max_picks_in_this_location=$row['Required']+$row['Given'];



			if ($part_index==0) {
				$_shipped_qty=$shipped_quantity*$parts_per_product;
				$_outstock_qty=$out_of_stock_quantity*$parts_per_product;
			}


			if ($max_picks_in_this_location<$_shipped_qty) {
				$_picked_qty[$itf]=$max_picks_in_this_location;
				$_shipped_qty=$_shipped_qty-$max_picks_in_this_location;
				$still_required=0;
			} else {
				$_picked_qty[$itf]=$_shipped_qty;
				$still_required=$max_picks_in_this_location-$_shipped_qty;
				$_shipped_qty=0;
			}

			if ($still_required<$_outstock_qty) {
				$_out_of_stock_qty[$itf]=$still_required;
				$_outstock_qty=$_outstock_qty-$still_required;

			} else {
				$_out_of_stock_qty[$itf]=$_outstock_qty;
				$_outstock_qty=0;

			}
		}


	}
	$dn->update_stock=false;





	foreach ($_picked_qty as $itf=>$_qty) {

		$dn->set_as_picked($itf,$_qty,$finish_picking_date);
	}

	foreach ($_out_of_stock_qty as $itf=>$_qty) {

		$dn->set_as_out_of_stock($itf,$_qty,$finish_picking_date);
	}


	$dn->update_picking_percentage();


	if ($just_pick) {
		return;
	}


	$dn->start_packing($packer_staff_key,$start_packing_date);

	$_packed_qty=array();


	// print "\ncaca ".count($data_dn_transactions)." \n";
	// print_r($data_dn_transactions);

	foreach ($data_dn_transactions as $key=>$value) {


		$shipped_quantity=round($value['Shipped Quantity'],8);
		$sql=sprintf("select `Inventory Transaction Key`,`Required`,`Given`,`Map To Order Transaction Fact Metadata` from `Inventory Transaction Fact` where `Map To Order Transaction Fact Key` =%d order by `Inventory Transaction Key` ",$value['otf_key']);
		$res=mysql_query($sql);

		$num_rows = mysql_num_rows($res);

		if (!$num_rows) {

			//print_r($value);

			if ($value['Code']=='Freight') {
				print "Freight transaction \n";
			}else {
				print_r($value);
				exit("==============\n  $key\n $sql    Error (x) no itf-otf map\n");

			}
		}

		while ($row=mysql_fetch_assoc($res)) {
			$itf=$row['Inventory Transaction Key'];




			$metadata=preg_split('/;/',$row['Map To Order Transaction Fact Metadata']);
			$parts_per_product=$metadata[1];
			$part_index=$metadata[0];
			$max_picks_in_this_location=$row['Required']+$row['Given'];



			if ($part_index==0) {
				$_shipped_qty=$shipped_quantity*$parts_per_product;
			}


			if ($max_picks_in_this_location<$_shipped_qty) {
				$_packed_qty[$itf]=$max_picks_in_this_location;
				$_shipped_qty=$_shipped_qty-$max_picks_in_this_location;
				$still_required=0;
			} else {
				$_packed_qty[$itf]=$_shipped_qty;
				$still_required=$max_picks_in_this_location-$_shipped_qty;
				$_shipped_qty=0;
			}

		}


	}


	foreach ($_packed_qty as $itf=>$_qty) {
		$dn->set_as_packed($itf,$_qty,$finish_packing_date);
	}







	$dn->update_packing_percentage();

	$dn->approve_packed($approve_date);



	$dn->set_parcels($parcels,$parcel_type);

	$dn->update_stock=true;

	//print "----- rea ship\n";

	if (!($tipo_order==6 or $tipo_order==7)) {
		if ($order->data['Order Type']=='Order' or ((  ($order->data['Order Type']=='Sample'  or $order->data['Order Type']=='Donation') and $order->data['Order Total Amount']!=0 ))) {

			$invoice=$dn->create_invoice($date_inv);
			// print_r($invoice);

			foreach ($credits as $credit) {
				$credit_data=array(
					'Affected Order Key'=>$order->id,
					'Order Key'=>($credit['parent_key']=='NULL'?$order->id:$credit['parent_key']),
					'Transaction Description'=>$credit['description'],
					'Tax Category Code'=>$tax_category_object->data['Tax Category Code'],
					'Transaction Invoice Net Amount'=>$credit['value'],
					'Transaction Invoice Tax Amount'=>$credit['value']*$tax_category_object->data['Tax Category Rate'],
					'Metadata'=>$store_code.$order_data_id
				);
				//print_r($credit_data);
				$invoice->add_credit_no_product_transaction($credit_data);
			}
			$_invoice_data=  array(
				'Invoice Metadata'=>$store_code.$order_data_id,
				'Invoice Shipping Net Amount'=>array(
					'Amount'=>$shipping_net,
					'tax_code'=>$tax_category_object->data['Tax Category Code']
				),
				'Invoice Charges Net Amount'=>array(
					'Transaction Invoice Net Amount'=> $charges_net,
					'Transaction Description'=>_('Charges')
				)


			);



			$invoice->update($_invoice_data);
			$invoice->update_totals();


			//adjust_invoice($invoice,$order);




			$invoice->pay('full',array(
					'Invoice Paid Date'=>$date_inv,
					'Payment Method'=>$payment_method
				));

			$order->update_xhtml_invoices();
			$order->update_no_normal_totals();


		}

	}
	$dn->approved_for_shipping($date_inv);


	//print "CACA ==".$dn->data['Delivery Note Dispatch Method']."================\n";
	if ($dn->data['Delivery Note Dispatch Method']=='Dispatch')
		$dn->dispatch(array('Delivery Note Date'=>$date_inv));
	elseif ($dn->data['Delivery Note Dispatch Method']=='Collection') {
		$dn->set_as_collected(array('Delivery Note Date'=>$date_inv));

	}
	else {
		exit("Error unknown dispatch method\n");

	}


}

function find_otf_key_in_order($id,$data) {
	$otf_key=0;
	$sql=sprintf("select `Order Transaction Fact Key`,`Shipped Quantity` from `Order Transaction Fact` where `Product Key`=%d and `Order Key`=%d order by `Order Key`, `Shipped Quantity` desc",
		$data['Product Key'],
		$id);
	$res_lines=mysql_query($sql);
	// print "$sql\n";
	if ($row=mysql_fetch_array($res_lines)) {
		$otf_key=$row['Order Transaction Fact Key'];
	}
	return $otf_key;
}


function create_post_order($data,$data_dn_transactions) {
	global $customer_key,$filename,$store_code,$order_data_id,$date_order,$shipping_net,$charges_net,$order,$dn,$invoice,$shipping_net;
	global $charges_net,$order,$dn,$payment_method,$date_inv,$extra_shipping,$parcel_type,$date_order;
	global $packer_data,$picker_data,$parcels,$tipo_order,$parent_order_id,$customer,$tax_category_object,$customer_key,$data_dn_transactions;


	if ($tipo_order==6) {
		$data['Order Type']='Replacement';
		$reason='Damaged';
	} else {
		$data['Order Type']='Missing';
		$reason='Missing';
	}


	if ($parent_order_id) {
		$parent_order=new Order('public_id',$parent_order_id);
	} else {
		$order_id=$customer->get_last_order();
		if ($order_id) {
			$parent_order=new Order('id',$order_id);
			$parent_order->update_customer=false;
			print "Parent Order not given, using customer last order\n";
		} else {
			print "Given customer last order can not be found \n";

			create_post_order_with_out_order($data);
			return;
		}
	}




	if ($parent_order->id) {

		$customer=new Customer($parent_order->data['Order Customer Key']);
		$ship_to= new Ship_To($data['Order Ship To Key']);


		if (!$ship_to->id) {
			exit("terrible error customer dont have last ship to when processing a replacement\n");

		}



		$discounts_map=array();
		$transaction_not_found=0;
		$post_data=array();
		foreach ($data_dn_transactions as $dn_trans_key=>$transaction) {
			$product=new Product('id',$transaction['Product Key']);
			$quantity=$transaction['Order Quantity'];
			$data_transaction=array(
				'Product Key'=>$product->data['Product Current Key'],
				'qty'=>$quantity,

			);
			//if ($data['Order Type']=='Replacement')
			//    $result=$parent_order->set_transaction_as_shipped_damaged($data_transaction);
			//else
			//    $result=$parent_order->set_transaction_as_not_received($data_transaction);

			$otf_key=find_otf_key_in_order($parent_order->id,$data_transaction);

			$quantity=$quantity;
			$gross=$quantity*$product->data['Product History Price'];
			$estimated_weight=$quantity*$product->data['Product Gross Weight'];
			$post_data[]=array(
				'Order Transaction Fact Key'=>$otf_key,
				'Order Type'=> $data['Order Type'],
				'Order Tax Rate'=>$tax_category_object->data['Tax Category Rate'],
				'Order Tax Code'=>$tax_category_object->data['Tax Category Code'],
				'Order Currency'=>$parent_order->data['Order Currency'],
				'Estimated Weight'=>$estimated_weight,
				'Date'=>$date_order,
				'Product Key'=>$product->data['Product Current Key'],
				'Gross'=>$gross,
				'Ship To Key'=>$ship_to->id,
				'Quantity'=>$quantity,
				'Order Store Key'=>$parent_order->data['Order Store Key'],
				'Order Customer Key'=>$parent_order->data['Order Customer Key'],
				'units_per_case'=>$product->data['Product Units Per Case'],
				'Current Dispatching State'=>'In Process',
				'Current Payment State'=>'No Applicable',
				'Metadata'=>$store_code.$order_data_id,
				'Order Key'=>($otf_key?$parent_order->id:0),
				'Order Date'=>$date_order,
				'Order Public ID'=>$parent_order->data['Order Public ID'],
				'Order Transaction Type'=>$data['Order Type'],
				'dn_trans_key'=>$dn_trans_key,
				'Reason'=>$reason
			);




		}

		// exit("$store_code.$order_data_id\n");
		$_order_type=$data['Order Type'];

		$dn=$parent_order->send_post_action_to_warehouse($date_order,$_order_type,$store_code.$order_data_id);
		if ($parent_order->error) {
			print "Parent order found but still delivery note ";
			create_post_order_with_out_order($data);
			return;
		}


		foreach ($post_data as $post_transaction) {
			$transaction_data=$dn->add_orphan_transactions($post_transaction);
			$data_dn_transactions[$post_transaction['dn_trans_key']]['otf_key']=$transaction_data['otf_key'];
		}
		$dn->create_orphan_inventory_transaction_fact($date_order);
		//$dn->approved_for_shipping($date_inv);
		//$dn->dispatch(array('Delivery Note Date'=>$date_inv));
	} else {
		print "Parent order can not be found ";
		create_post_order_with_out_order($data);




	}
}


function create_post_order_with_out_order($data) {

	global $customer_key,$filename,$store_code,$order_data_id,$date_order,$shipping_net,$charges_net,$order,$dn,$invoice,$shipping_net;
	global $charges_net,$order,$dn,$payment_method,$date_inv,$extra_shipping,$parcel_type,$date_order;
	global $packer_data,$picker_data,$parcels,$tipo_order,$parent_order_id,$customer,$tax_category_object,$customer_key,$header_data,$data_dn_transactions;


	if ($tipo_order==6) {

		$reason='Damaged';
	} else {

		$reason='Missing';
	}



	$type=$data['Order Type'];
	$type_formated=$data['Order Type'];
	$title="Delivery Note for $type_formated for an unknown order of <a href='customer.php?id=".$customer->id."'>".$customer->data['Customer Name']."</a>";

	if ($header_data['collection']=='Yes')
		$dispatch_method='Collection';
	else
		$dispatch_method='Dispatch';
	if ($type=='Replacement')
		$suffix='rpl';
	elseif ($type=='Missing')
		$suffix='sh';
	else
		$suffix='';

	$data['order id']=preg_replace('/(sh|srt|miss|rpl|plt|repl|replc|shortages|short)$/i','',$data['order id']);

	if (preg_match('/[a-z]$/i',$data['order id'])) {
		$suffix='';
	}

	$data_dn=array(
		'Delivery Note Date Created'=>$date_order,
		'Delivery Note ID'=>$data['order id']."$suffix",
		'Delivery Note File As'=>Order::prepare_file_as($data['order id'])."$suffix",
		'Delivery Note Type'=>$type,
		'Delivery Note Title'=>$title,
		'Delivery Note Dispatch Method'=>$dispatch_method,
		'Delivery Note Metadata'=>$store_code.$order_data_id,
		'Delivery Note Customer Key'=>$customer_key

	);
	$dn=new DeliveryNote('create',$data_dn,false);


	$customer=new Customer($customer_key);

	$customer->add_history_post_order_in_warehouse($dn,$type);
	$store=new Store($customer->data['Customer Store Key']);

	foreach ($data_dn_transactions as $dn_trans_key=>$transaction) {
		$product=new Product('id',$transaction['Product Key']);
		$quantity=$transaction['Order Quantity'];

		$quantity=$quantity;
		$gross=$quantity*$product->data['Product History Price'];
		$estimated_weight=$quantity*$product->data['Product Gross Weight'];
		$post_data[]=array(
			'Order Type'=> $data['Order Type'],
			'Order Tax Rate'=>$tax_category_object->data['Tax Category Rate'],
			'Order Tax Code'=>$tax_category_object->data['Tax Category Code'],
			'Order Currency'=>$store->data[ 'Store Currency Code' ],
			'Estimated Weight'=>$estimated_weight,
			'Date'=>$date_order,
			'Product Key'=>$product->data['Product Current Key'],
			'Gross'=>$gross,
			'Ship To Key'=>$dn->data['Delivery Note Ship To Key'],
			'Quantity'=>$quantity,
			'Order Store Key'=>$store->id,
			'Order Customer Key'=>$customer->id,
			'units_per_case'=>$product->data['Product Units Per Case'],
			'Current Dispatching State'=>'In Process',
			'Current Payment State'=>'No Applicable',
			'Metadata'=>$store_code.$order_data_id,
			'Order Key'=>0,
			'Order Date'=>$date_order,
			'Order Public ID'=>'',
			'Order Transaction Type'=>$data['Order Type'],
			'dn_trans_key'=>$dn_trans_key,
			'Reason'=>$reason,
			'Order Transaction Fact Key'=>0,
		);
	}

	foreach ($post_data as $post_transaction) {
		$transaction_data=$dn->add_orphan_transactions($post_transaction);
		//  print_r($transaction_data);
		$data_dn_transactions[$post_transaction['dn_trans_key']]['otf_key']=$transaction_data['otf_key'];
	}
	$dn->create_orphan_inventory_transaction_fact($date_order);


}


function create_refund($data,$header_data,$data_dn_transactions) {
	global $customer_key,$filename,$store_code,$order_data_id,$date_order,$shipping_net,$charges_net,$order,$dn,$invoice,$shipping_net;
	global $charges_net,$order,$dn,$payment_method,$date_inv,$extra_shipping,$parcel_type;
	global $packer_data,$picker_data,$parcels,$tipo_order,$parent_order_id,$customer,$shipping_transactions,$data_invoice_transactions,$shipping_transactions,$tax_category_object;
	global $tax_category_object,$credits,$shipping_net,$charges_net;


	//$charges_net*$tax_category_object->data['Tax Category Rate']

	$data['Order Type']='Refund';

	$factor=1.0;
	if ($header_data['total_topay']>0)
		$factor=-1.0;


	if ($parent_order_id) {
		$parent_order=new Order('public_id',$parent_order_id);
		if (!$parent_order->id) {
			create_ghost_refund($data,$header_data,$data_dn_transactions);
			return;
		}

	} else {
		$order_id=$customer->get_last_order();
		if ($order_id) {
			$parent_order=new Order('id',$order_id);
			$parent_order->update_customer=false;
			print "Parent Order not given, using customer last order\n";
		} else {
			create_ghost_refund($data,$header_data,$data_dn_transactions);
			return;
		}
	}
	print "Matching Refund\n";

	foreach ($parent_order->get_invoices_objects() as $invoice) {
		//print $header_data['total_topay']." ".$invoice->data['Invoice Total Amount']."\n";
	}

	// print $date_inv;

	$refund=$parent_order->create_refund(array(
			'Invoice Metadata'=>$store_code.$order_data_id,
			'Invoice Date'=>$date_inv,
			'Invoice Tax Code'=>$tax_category_object->data['Tax Category Code']
		)
	);
	foreach ($data_invoice_transactions as $transaction) {

		$sql=sprintf("select * from `Order Transaction Fact` OTF   where `Order Key`=%d  and OTF.`Product Key`=%d ",
			$parent_order->id,
			$transaction['Product Key']
		);
		$res=mysql_query($sql);
		if ($row=mysql_fetch_assoc($res)) {
			$net=$factor*($transaction['gross amount']-$transaction['discount amount']);
			$tax=$net*$tax_category_object->data['Tax Category Rate'];

			$net_items=$net;
			$net_shipping=0;
			$net_charges=0;
			$tax_items=$tax;
			$tax_shipping=0;
			$tax_charges=0;

			$otf_component_items=$row['Invoice Transaction Gross Amount']-$row['Invoice Transaction Total Discount Amount'];
			$otf_component_shipping=$row['Invoice Transaction Shipping Amount'];
			$otf_component_charges=$row['Invoice Transaction Charges Amount'];
			$otf_component_sum=$otf_component_items+$otf_component_shipping+$otf_component_charges;
			if ($otf_component_sum!=0) {

				$net_items=round($net*($otf_component_items/$otf_component_sum),2);
				$net_shipping=round($net*($otf_component_shipping/$otf_component_sum),2);
				$net_charges=round($net*($otf_component_charges/$otf_component_sum),2);
			}

			$otf_component_items=$row['Invoice Transaction Item Tax Amount'];
			$otf_component_shipping=$row['Invoice Transaction Shipping Tax Amount'];
			$otf_component_charges=$row['Invoice Transaction Charges Tax Amount'];
			$otf_component_sum=$otf_component_items+$otf_component_shipping+$otf_component_charges;
			if ($otf_component_sum!=0) {

				$tax_items=round($tax*($otf_component_items/$otf_component_sum),2);
				$tax_shipping=round($tax*($otf_component_shipping/$otf_component_sum),2);
				$tax_charges=round($tax*($otf_component_charges/$otf_component_sum),2);
			}


			$refund_transaction_data=array(
				'Order Transaction Fact Key'=>$row['Order Transaction Fact Key'],
				'Invoice Transaction Net Refund Items'=>$net_items,
				'Invoice Transaction Net Refund Shipping'=>$net_shipping,
				'Invoice Transaction Net Refund Charges'=>$net_charges,
				'Invoice Transaction Net Refund Amount'=>$net,
				'Invoice Transaction Tax Refund Items'=>$tax_items,
				'Invoice Transaction Tax Refund Shipping'=>$tax_shipping,
				'Invoice Transaction Tax Refund Charges'=>$tax_charges,

				'Invoice Transaction Tax Refund Amount'=>$tax,
				'Refund Metadata'=>$store_code.$order_data_id

			);
			$refund->add_refund_transaction($refund_transaction_data);
		}
		else {

			if ($transaction['original_amount']!=0) {

				$net=$factor*($transaction['original_amount']);
				$tax=$net*$tax_category_object->data['Tax Category Rate'];
				$refund_transaction_data=array(
					'Order Key'=>$parent_order->id,
					'Affected Order Key'=>$parent_order->id,
					'Transaction Description'=>$transaction['description'],
					'Transaction Invoice Net Amount'=>-1*$net,
					'Transaction Invoice Tax Amount'=>-1*$tax,
					'Metadata'=>$store_code.$order_data_id,
					'Tax Category Code'=>$tax_category_object->data['Tax Category Code'],
				);
				$refund->add_orphan_refund_no_product_transaction($refund_transaction_data);
			}

		}
	}

	if ($shipping_net!=0) {
		//print "adding the shipping to the refund";

		$net=$factor*(-$shipping_net);
		$tax=$net*$tax_category_object->data['Tax Category Rate'];
		$refund_transaction_data=array(
			'Order Key'=>$parent_order->id,
			'Affected Order Key'=>$parent_order->id,
			'Transaction Description'=>'Refund Shipping',
			'Transaction Invoice Net Amount'=>-1*$net,
			'Transaction Invoice Tax Amount'=>-1*$tax,
			'Metadata'=>$store_code.$order_data_id,
			'Tax Category Code'=>$tax_category_object->data['Tax Category Code'],
		);


		$refund->add_orphan_refund_no_product_transaction($refund_transaction_data);

	}

	if ($charges_net!=0) {
		//print "adding the shipping to the refund";

		$net=$factor*(-$charges_net);
		$tax=$net*$tax_category_object->data['Tax Category Rate'];
		$refund_transaction_data=array(
			'Order Key'=>$parent_order->id,
			'Affected Order Key'=>$parent_order->id,
			'Transaction Description'=>'Refund Charges',
			'Transaction Invoice Net Amount'=>-1*$net,
			'Transaction Invoice Tax Amount'=>-1*$tax,
			'Metadata'=>$store_code.$order_data_id,
			'Tax Category Code'=>$tax_category_object->data['Tax Category Code'],
		);


		$refund->add_orphan_refund_no_product_transaction($refund_transaction_data);

	}


	foreach ($refund->get_delivery_notes_objects() as $key=>$_dn) {
		$sql = sprintf( "insert into `Invoice Delivery Note Bridge` values (%d,%d)" ,$refund->id ,$key);
		mysql_query( $sql );
		$refund->update_xhtml_delivery_notes();
		$_dn->update_xhtml_invoices();
	}

	foreach ($refund->get_orders_objects() as $key=>$_order) {
		$sql = sprintf( "insert into `Order Invoice Bridge` values (%d,%d)", $key, $refund->id );
		mysql_query( $sql );
		$refund->update_xhtml_orders();
		$_order->update_xhtml_invoices();
	}

	foreach ($shipping_transactions as $other_shipping) {
		if (!$other_shipping['discount'])
			$other_shipping['discount']=0;
		$net=$other_shipping['price']*(1-$other_shipping['discount'])*($other_shipping['order']-$other_shipping['reorder']+$other_shipping['bonus']);
		$tax=$net*$tax_category_object->data['Tax Category Rate'];
		$refund_transaction_data=array(
			'Order Key'=>$parent_order->id,
			'Affected Order Key'=>$parent_order->id,
			'Transaction Description'=>$other_shipping['description'],
			'Transaction Invoice Net Amount'=>-1*$net,
			'Transaction Invoice Tax Amount'=>-1*$tax,
			'Metadata'=>$store_code.$order_data_id,
			'Tax Category Code'=>$tax_category_object->data['Tax Category Code'],
		);

		$refund->add_orphan_refund_no_product_transaction($refund_transaction_data);


	}
	foreach ($credits as $credit) {

		$net=$factor*$credit['value'];
		$tax=$net*$tax_category_object->data['Tax Category Rate'];



		$refund_transaction_data=array(
			'Order Key'=>(is_numeric($credit['parent_key']) and $credit['parent_key']?$credit['parent_key']:''),
			'Affected Order Key'=>(is_numeric($credit['parent_key']) and $credit['parent_key']?$credit['parent_key']:''),
			'Transaction Description'=>$credit['description'],
			'Transaction Invoice Net Amount'=>$net,
			'Transaction Invoice Tax Amount'=>$tax,
			'Metadata'=>$store_code.$order_data_id,
			'Tax Category Code'=>$tax_category_object->data['Tax Category Code'],
		);
		$refund->add_orphan_refund_no_product_transaction($refund_transaction_data);

	}
	$refund->pay('full',array(
			'Invoice Paid Date'=>$date_inv,
			'Payment Method'=>$payment_method
		));

}


function create_ghost_refund($data,$header_data,$data_dn_transactions) {
	global $customer_key,$filename,$store_code,$order_data_id,$date_order,$shipping_net,$charges_net,$order,$dn,$invoice,$shipping_net;
	global $charges_net,$order,$dn,$payment_method,$date_inv,$extra_shipping,$parcel_type;
	global $packer_data,$picker_data,$parcels,$tipo_order,$parent_order_id,$customer,$shipping_transactions,$data_invoice_transactions,$shipping_transactions,$tax_category_object,$store_key;
	global $tax_category_object,$credits;

	$data['Order Type']='Refund';

	$factor=1.0;
	if ($header_data['total_topay']>0)
		$factor=-1.0;

	$name=$header_data['order_num'];
	if (preg_match('/^\d$/',$name)) {
		$name.='Ref';
	} else {
		$name=preg_replace('/\s*Refund$/i','Ref',$name);
		$name=preg_replace('/`*Ref$/i','Ref',$name);

	}

	$refund_data=array(
		'Invoice Customer Key'=>$customer_key,
		'Invoice Store Key'=>$store_key,
		'Invoice Metadata'=>$store_code.$order_data_id,
		'Invoice Date'=>$date_inv,
		'Invoice Public ID'=>$name
	);

	$refund=new Invoice ('create refund',$refund_data);
	foreach ($data_invoice_transactions as $transaction) {

		if ($transaction['original_amount']!=0) {
			$net=$factor*($transaction['original_amount']);
			$tax=$net*$tax_category_object->data['Tax Category Rate'];


			$refund_transaction_data=array(
				'Order Key'=>false,
				'Affected Order Key'=>false,
				'Transaction Description'=>$transaction['description'],
				'Transaction Invoice Net Amount'=>$net,
				'Transaction Invoice Tax Amount'=>$tax,
				'Metadata'=>$store_code.$order_data_id,
				'Tax Category Code'=>$tax_category_object->data['Tax Category Code'],
			);
			$refund->add_orphan_refund_no_product_transaction($refund_transaction_data);

		}
	}
	foreach ($shipping_transactions as $other_shipping) {
		if (!$other_shipping['discount'])
			$other_shipping['discount']=0;
		$net=$factor*$other_shipping['price']*(1-$other_shipping['discount'])*($other_shipping['order']-$other_shipping['reorder']+$other_shipping['bonus']);
		$tax=$net*$tax_category_object->data['Tax Category Rate'];
		$refund_transaction_data=array(
			'Order Key'=>false,
			'Affected Order Key'=>false,
			'Transaction Description'=>$other_shipping['description'],
			'Transaction Invoice Net Amount'=>$net,
			'Transaction Invoice Tax Amount'=>$tax,
			'Metadata'=>$store_code.$order_data_id,
			'Tax Category Code'=>$tax_category_object->data['Tax Category Code'],
		);
		$refund->add_orphan_refund_no_product_transaction($refund_transaction_data);

	}

	foreach ($credits as $credit) {
		$net=$factor*$credit['value'];
		$tax=$net*$tax_category_object->data['Tax Category Rate'];
		$refund_transaction_data=array(
			'Order Key'=>(is_numeric($credit['parent_key']) and $credit['parent_key']?$credit['parent_key']:''),
			'Affected Order Key'=>(is_numeric($credit['parent_key']) and $credit['parent_key']?$credit['parent_key']:''),
			'Transaction Description'=>$credit['description'],
			'Transaction Invoice Net Amount'=>$net,
			'Transaction Invoice Tax Amount'=>$tax,
			'Metadata'=>$store_code.$order_data_id,
			'Tax Category Code'=>$tax_category_object->data['Tax Category Code'],
		);
		$refund->add_orphan_refund_no_product_transaction($refund_transaction_data);

	}

	if ($refund->data['Invoice Total Net Amount']==0 and $refund->data['Invoice Items Tax Amount']==0) {


		$net=$factor*$header_data['total_net'];
		$tax=$net*$tax_category_object->data['Tax Category Rate'];
		$refund_transaction_data=array(
			'Order Key'=>'',
			'Affected Order Key'=>false,
			'Transaction Description'=>_('Refund'),
			'Transaction Invoice Net Amount'=>$net,
			'Transaction Invoice Tax Amount'=>$tax,
			'Metadata'=>$store_code.$order_data_id,
			'Tax Category Code'=>$tax_category_object->data['Tax Category Code'],
		);
		$refund->add_orphan_refund_no_product_transaction($refund_transaction_data);


	}
	$refund->pay('full',array(
			'Invoice Paid Date'=>$date_inv,
			'Payment Method'=>$payment_method
		));
}



function get_tax_code($type,$header_data) {



	switch ($type) {
	case 'E':
		$tax_cat_data=ci_get_tax_code($header_data);
		break;
	default:
		$tax_cat_data=uk_get_tax_code($header_data);
		break;
	}



	$tax_category=new TaxCategory('find',$tax_cat_data,'create');


	return $tax_category;
}


function ci_get_tax_code($header_data) {


	//print_r($header_data);

	$tax_rates=array();
	$tax_names=array();
	$sql=sprintf("select * from `Tax Category Dimension` ");
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$tax_rates[$row['Tax Category Code']]=$row['Tax Category Rate'];
		$tax_names[$row['Tax Category Code']]=$row['Tax Category Name'];
	}


	$tax_code='UNK';
	$tax_description='Unknown';
	$tax_rate=0;



	if ($header_data['total_net']==0) {
		$tax_code='EX';
		$tax_description='';
	}
	elseif ($header_data['total_net']!=0 and $header_data['tax1']+$header_data['tax2']==0 ) {

		$tax_code='EX';
		$tax_description='';
	}
	else {
		$tax_rate=($header_data['tax1']+$header_data['tax2'])/$header_data['total_net'];
		foreach ($tax_rates as $_tax_code=>$_tax_rate) {
			///print "$_tax_code => $_tax_rate --->$tax_rate\n ";
			$upper=1.02*$_tax_rate;
			$lower=0.98*$_tax_rate;

			//print " $_tax_rate  low($lower) $tax_rate up($upper)\n";
			if ($tax_rate>=$lower and $tax_rate<=$upper) {
				$tax_code=$_tax_code;
				$tax_description=$tax_names[$tax_code];
				$tax_rate=$tax_rates[$tax_code];
				break;
			}
		}
	}

	$data= array(
		'Tax Category Code'=>$tax_code,
		'Tax Category Name'=>$tax_description,
		'Tax Category Rate'=>$tax_rate
	);


	// print_r($data);

	return $data;



}

function uk_get_tax_code($header_data) {



	$tax_rates=array();
	$tax_names=array();
	$sql=sprintf("select * from `Tax Category Dimension` ");
	$res=mysql_query($sql);
	while ($row=mysql_fetch_assoc($res)) {
		$tax_rates[$row['Tax Category Code']]=$row['Tax Category Rate'];
		$tax_names[$row['Tax Category Code']]=$row['Tax Category Name'];
	}

	$tax_code='UNK';
	$tax_description='No Tax';
	$tax_rate=0;
	if ($header_data['total_net']==0) {
		$tax_code='EX';
		$tax_description='';
	}
	elseif ($header_data['total_net']!=0 and $header_data['tax1']+$header_data['tax2']==0 ) {

		$tax_code='EX';
		$tax_description='';
	}
	else {
		//  print "calcl tax coed";

		$tax_rate=($header_data['tax1']+$header_data['tax2'])/$header_data['total_net'];
		foreach ($tax_rates as $_tax_code=>$_tax_rate) {
			// print "$_tax_code => $_tax_rate $tax_rate\n ";
			$upper=1.02*$_tax_rate;
			$lower=0.98*$_tax_rate;
			if ($tax_rate>=$lower and $tax_rate<=$upper) {
				$tax_code=$_tax_code;
				$tax_description=$tax_names[$tax_code];
				$tax_rate=$tax_rates[$tax_code];
				break;
			}
		}
	}

	$data= array(
		'Tax Category Code'=>$tax_code,
		'Tax Category Name'=>$tax_description,
		'Tax Category Rate'=>$tax_rate
	);


	return $data;
}

function get_user_id($oname,$return_xhtml=false,$tag='',$order='',$editor=false) {
	if (!$editor) {
		$editor=array();
	}


	$ids=array();
	if ($oname=='' or is_numeric($oname)) {
		if ($return_xhtml)
			return array('id'=>array(0),'xhtml'=>_('Unknown'));
		else
			return array(0);

	}


	$_names=array();

	$_names=preg_split('/\s*(\+|\&|,+|\/|\-)\s*/',$oname);

	$xhtml='';

	foreach ($_names as $_name) {
		$original_part=$_name;
		$_name=_trim(strtolower($_name));
		if ($_name=='')
			continue;
		$original_name=$_name;

		$_name=preg_replace('/^\s*/','',$_name);
		$_name=preg_replace('/\s*$/','',$_name);
		if (preg_match('/^(michele|michell|mich)$/i',$_name)   )
			$_name='michelle';
		elseif ( $_name=='salvka' or    preg_match('/^slavka/i',$_name) or $_name=='slavke' or $_name=='slavla' )
			$_name='slavka';
		elseif (preg_match('/^malcom$/i',$_name)  )
			$_name='malcolm';

		elseif (preg_match('/katerina/i',$_name) or $_name=='katka]' or   $_name=='katk'  or   $_name=='(katka)'   or   $_name==': katka' )
			$_name='katka';
		elseif (preg_match('/richard w/i',$_name) or $_name=='rich')
			$_name='richard';
		elseif (preg_match('/david\s?(hardy)?/i',$_name))
			$_name='david';
		elseif (preg_match('/philip|phil/i',$_name))
			$_name='philippe';
		elseif (preg_match('/amanada|amand\s*$/i',$_name))
			$_name='amanda';
		elseif (preg_match('/janette/i',$_name) or $_name=='jqnet' or $_name==': janet' or $_name=='jw'  )
			$_name='janet';
		elseif (preg_match('/pete/i',$_name))
			$_name='peter';
		elseif (preg_match('/debra/i',$_name))
			$_name='debbie';
		elseif (preg_match('/vinnie/i',$_name))
			$_name='vinni';
		elseif (preg_match('/sam/i',$_name))
			$_name='samantha';
		elseif ($_name=='philip' or $_name=='ph' or $_name=='phi' or $_name=='philip'  )
			$_name='philippe';
		elseif (  $_name=='aqb' or $_name=='kj' or  $_name=='act' or $_name=='tr'  or    $_name=='other' or $_name=='?' or $_name=='bb') {
			if ($return_xhtml)
				return array('id'=>array(0),'xhtml'=>_('Unknown'));
			else
				return array(0);

		} elseif ($_name=='thomas' or $_name=='tomas belan' or $_name=='tb' or preg_match('/^\s*tomas\s*$/i',$_name) or $_name=='tom' )
			$_name='tomas';
		elseif ($_name=='alam' or $_name=='aw' or   $_name=='al' or   $_name=='al.'  or  $_name=='ala' )
			$_name='alan';
		elseif ($_name=='carol')
			$_name='carole';

		elseif ($_name=='dushan' or $_name=='duscan' or $_name=='dus')
			$_name='dusan';
		elseif ($_name=='eli' or $_name=='eilska' or $_name=='eilsk' or $_name=='elsika' or $_name=='elishka')
			$_name='eliska';
		elseif ($_name=='jiom' or $_name=='tim'  or $_name=='jimbob'    or  $_name=='jikm')
			$_name='jim';
		elseif ($_name=='beverley' or $_name=='ber'  or $_name=='bav')
			$_name='bev';
		elseif (   $_name=='albett' or  $_name=='alnert'  or    $_name=='alberft' or   $_name=='alberyt' or    $_name=='alabert'  or   $_name=='albet' or $_name=='albert ' or $_name=='albet ' or$_name=='alberto'  or $_name=='alb'  or $_name=='albery' or $_name=='alberty' or $_name=='ac'  or $_name=='albeert'  )
			$_name='albert';
		elseif ($_name=='ab' or $_name=='adr')
			$_name='adriana';
		elseif ($_name=='jamet' or $_name=='jante' or $_name=='jant' or $_name=='jnet' or $_name=='j' or $_name=='jenet'  or $_name=='jsnet'  )
			$_name='janet';
		elseif ($_name=='slvaka')
			$_name='slavka';
		elseif ($_name=='ct')
			$_name='craig';
		elseif ($_name=='k ' or $_name=='k' or $_name=='katerina2')
			$_name='katka';
		elseif ($_name=='daniella' or $_name=='daniella' or $_name=='dan' )
			$_name='daniela';

		elseif ($_name=='dani' or $_name=='daniel')
			$_name='danielle';

		elseif ($_name=='cc' or $_name==' cc')
			$_name='chris';
		elseif ($_name=='bret')
			$_name='brett';
		elseif ( $_name=='luc')
			$_name='lucie';
		elseif ($_name=='mat')
			$_name='matus';
		elseif ($_name=='ob' or $_name=='o.b.')
			$_name='olga';
		elseif ($_name=='stacy')
			$_name='stacey';
		elseif ($_name=='kkzoe' or $_name=='kzoe' or $_name==': zoe')
			$_name='zoe';
		elseif ($_name=='zoe h')
			$_name='zhilbert';


		elseif ($_name=='cph')
			$_name='caleb';
		elseif ($_name=='jenka' or  $_name=='len' or  $_name=='le'  or $_name=='lo' or  $_name=='lenka ondrisova'  )
			$_name='lenka';
		elseif ($_name=='jjanka' or $_name=='jan')
			$_name='janka';
		elseif ($_name=='jarina')
			$_name='jirina';
		elseif ($_name=='agh')
			$_name='agmet';
		elseif ($_name=='joanne' or $_name=='joanna')
			$_name='joana';
		elseif ($_name=='bryant' or $_name=='brayant')
			$_name='brian';
		elseif ($_name=='lisa r')
			$_name='lisa';
		elseif ($_name=='urszula baka')
			$_name='urszula';
		elseif ($_name=='ula')
			$_name='urszula';
		elseif ($_name=='kerrry' or $_name=='kerrys' or $_name=='kerru' )
			$_name='kerry';
		elseif ($_name=='kez')
			$_name='eric';
		elseif ($_name=='steff' or $_name=='sc' or $_name=='steffanie' or $_name=='steff cox' or $_name=='stef' or $_name=='steff cox')
			$_name='stephanie';
		elseif ($_name=='anthony' or $_name=='antony')
			$_name='anthony';
		elseif (preg_match('/martina otte/i',$_name))
			$_name='martina';
		elseif (preg_match('/staff? daniela/i',$_name))
			$_name='daniela';
		elseif (preg_match('/^lucy|lucy a$/i',$_name))
			$_name='lucy';
		elseif (preg_match('/^david hardy$/i',$_name))
			$_name='david';
		elseif (preg_match('/graige/i',$_name))
			$_name='craige';

		//







		$sql=sprintf("select `Staff Key`,`Staff Alias` from `Staff Dimension` where `Staff Alias`=%s",prepare_mysql($_name));
		// print "$sql\n";
		$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$id=$row['Staff Key'];
			$ids[$id]=$id;

			$xhtml.=sprintf(', <a href="staff.php?id=%d%s">%s</a>',$id,$tag,mb_ucwords($row['Staff Alias']));

		} else {

			// print "$original_name\n";
			$valid_names=array('david','keleb','lucy','sharka','stephanie','urszula','darna','jarina','mini','andrea','scott','mark','janka','peter','lyndsey','rebecca','micheal','samantha','richard','albert','debbie','chris','barry','donna','malcolm','michelle','adriana','daniela'
				,'stacey','matus','lucie','caleb','olga','bev','jim','eliska','carole','zoe','katka','urszula','dana','craig','tomas','eric','neil','slavka','anthony','anita','annetta','simon','stefanie','steve','agmet','nabil','brett','jirina','alan','janet','kerry','lenka','amanda','philippe','michael','martina','dusan','raul','craige','sarka','nassim');

			$contact_name=$_name;

			if ($_name=='slavka') {
				$contact_name='Slavka Hardy';
			}
			elseif ($_name=='katka') {
				$contact_name='Katka Buchy';
			}

			if (in_array($_name,$valid_names)) {
				$staff_data=array(
					'Staff Alias'=>ucwords($_name)
					,'Staff Name'=>ucwords($contact_name)
					,'editor'=>$editor
					,'Staff Currently Working'=>'No'
				);
				$staff=new Staff('find',$staff_data,'create');
				// print_r($staff);
				$id=$staff->id;
				$ids[$id]=$id;

				$xhtml.=sprintf(', <a href="staff.php?id=%d%s">%s</a>',$id,$tag,mb_ucwords($staff->data['Staff Alias']));


			} else {


				$sql=sprintf("insert into todo_users (name,order_name,tipo) values ('%s','%s','')",addslashes($original_name),$order);
				// print "$sql\n";
				mysql_query($sql);
				//print "Staff name not found $oname \n";
				$id=0;
				$ids[$id]=$id;

				$xhtml.=sprintf(', %s',$original_name);


			}
		}
	}
	$_ids=array();
	foreach ($ids as $values) {
		$_ids[]=$values;
	}

	$xhtml=preg_replace("/^\,\s*/","",$xhtml);

	if ($return_xhtml)
		return array('id'=>$_ids,'xhtml'=>$xhtml);
	else
		return $_ids;
}

function ci_get_user_id($oname,$return_xhtml=false,$tag='',$order='',$editor=false) {
	if (!$editor) {
		$editor=array();
	}


	$ids=array();
	if ($oname=='' or is_numeric($oname)) {
		if ($return_xhtml)
			return array('id'=>array(0),'xhtml'=>_('Unknown'));
		else
			return array(0);

	}


	$_names=array();

	$_names=preg_split('/\s*(\+|\&|,+|\/| y |\-)\s*/',$oname);

	$xhtml='';

	foreach ($_names as $_name) {
		$original_part=$_name;
		$_name=_trim(strtolower($_name));
		if ($_name=='')
			continue;
		$original_name=$_name;

		$_name=preg_replace('/^\s*/','',$_name);
		$_name=preg_replace('/\s*$/','',$_name);
		if (preg_match('/^(juani)$/i',$_name)   )
			$_name='juan';
		elseif (preg_match('/^(helen)$/i',$_name)  )
			$_name='helena';
		elseif (preg_match('/^(dani)$/i',$_name)  )
			$_name='dany';
		elseif (preg_match('/^(alex|ale)$/i',$_name)  )
			$_name='alejandro';



		$sql=sprintf("select `Staff Key`,`Staff Alias` from `Staff Dimension` where `Staff Alias`=%s",prepare_mysql($_name));
		// print "$sql\n";
		$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
		if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
			$id=$row['Staff Key'];
			$ids[$id]=$id;

			$xhtml.=sprintf(', <a href="staff.php?id=%d%s">%s</a>',$id,$tag,mb_ucwords($row['Staff Alias']));

		} else {

			// print "$original_name\n";
			$valid_names=array();

			$contact_name=$_name;

			if ($_name=='slavka') {
				$contact_name='Slavka Hardy';
			}
			elseif ($_name=='katka') {
				$contact_name='Katka Buchy';
			}

			if (in_array($_name,$valid_names)) {
				$staff_data=array(
					'Staff Alias'=>ucwords($_name)
					,'Staff Name'=>ucwords($contact_name)
					,'editor'=>$editor
					,'Staff Currently Working'=>'No'
				);
				$staff=new Staff('find',$staff_data,'create');
				// print_r($staff);
				$id=$staff->id;
				$ids[$id]=$id;

				$xhtml.=sprintf(', <a href="staff.php?id=%d%s">%s</a>',$id,$tag,mb_ucwords($staff->data['Staff Alias']));


			} else {


				$sql=sprintf("insert into todo_users (name,order_name,tipo) values ('%s','%s','')",addslashes($original_name),$order);
				// print "$sql\n";
				mysql_query($sql);
				//print "Staff name not found $oname \n";
				$id=0;
				$ids[$id]=$id;

				$xhtml.=sprintf(', %s',$original_name);


			}
		}
	}
	$_ids=array();
	foreach ($ids as $values) {
		$_ids[]=$values;
	}

	$xhtml=preg_replace("/^\,\s*/","",$xhtml);

	if ($return_xhtml)
		return array('id'=>$_ids,'xhtml'=>$xhtml);
	else
		return $_ids;
}


function get_dates($filedate,$header_data,$tipo_order,$new_file=true) {


	if ($header_data['date_inv']=='1899-12-30')
		$header_data['date_inv']='';
	if ($header_data['date_order']=='1899-12-30')
		$header_data['date_order']='';




	$datetime_updated=date("Y-m-d H:i:s",$filedate);
	$time_updated_menos30min=date("H:i:s",$filedate-1800);

	list($date_updated,$time_updated)=preg_split('/\s/',$datetime_updated);
	if ($new_file) {
		if ($tipo_order==2  or $tipo_order==6 or $tipo_order==7 or $tipo_order==9  or $tipo_order==8   ) {

			//print_r($header_data);
			if ($header_data['date_inv']=='' or $header_data['date_inv']=='1970-01-01')
				$header_data['date_inv']=$header_data['date_order'];

			if ($date_updated ==$header_data['date_inv']) {

				$date_charged=$date_updated." ".$time_updated;

				$date_processed=$header_data['date_order']." 09:30:00";
				if (strtotime($date_processed)>strtotime($date_charged))
					$date_processed=$header_data['date_order']." ".$time_updated_menos30min;

			} else {
				$date_charged=$header_data['date_inv']." 17:30:00";
				$date_processed=$header_data['date_order']." 09:30:00";
			}
			$date_index=$date_charged;
		} else {


			$date_charged="NULL";
			if ($header_data['date_order']!='') {


				if ($date_updated ==$header_data['date_order']) {
					// print $header_data['date_order']." xssssssssssssxx";
					$date_processed=$date_updated." ".$time_updated;
					// print "$date_processed  xssssssssssssxx\n";

				} else {


					$date_processed=$header_data['date_order']." 08:30:00";
				}

			} else {
				$date_processed='';
			}

			$date_index=$date_processed;
			//         print $date_index." xxx\n";

		}
	}

	if (($tipo_order==4 or $tipo_order==5 or $tipo_order==6 or $tipo_order==7 or $tipo_order==9 ) and  $date_charged=='NULL') {
		$date_charged=$date_processed;
	}

	//  print "$date_index,$date_processed,$date_charged\n";
	return array($date_index,$date_processed,$date_charged);

}

function act_transformations($act_data) {

	// $act_data['contact']=str_replace("\"",'',$act_data['contact']);
	//$act_data['name']=str_replace("\"",'',$act_data['name']);

	//act_data['contact']=preg_replace("/(\\\"|\\\')/",$act_data['contact'],'x');
	$act_data['name']=preg_replace("/\"/",' ',$act_data['name']);
	$act_data['contact']=preg_replace("/\"/",' ',$act_data['contact']);
	$act_data['name']=preg_replace("/\'\'/",' ',$act_data['name']);
	$act_data['contact']=preg_replace("/\'\'/",' ',$act_data['contact']);
	$act_data['contact']=_trim($act_data['contact']);
	$act_data['name']=_trim($act_data['name']);


	if ($act_data['name']=='Cleaners (Mrs Mop)' and $act_data['contact']=='' ) {
		$act_data['name']='Cleaners';
		$act_data['contact']='Mrs Mop';
	}
	if ($act_data['name']=='Michelle A(aromatherapy,indian Head Massage Therap') {
		$act_data['name']='Michelle Angus';
	}

	if ($act_data['name']=="'magpies'") {
		$act_data['name']='magpies';
	}

	if (preg_match('/Stinkers.*duglas laver/i',$act_data['name'])) {
		$act_data['name']='Stinkers';
		if ($act_data['contact']=='')
			$act_data['contact']='Duglas Laver';
	}

	// print_r($act_data);

	if (preg_match('/J.t Tools.*Mr.*a.*Hammans/i',$act_data['name'])) {
		//   print "yyy\n";
		$act_data['name']='J&t Tools';
		if ($act_data['contact']=='')
			$act_data['contact']='Anthiny Hammans';


	}

	if ($act_data['country']=='Norway'  and
		(
			$act_data['a1']=='Postboks 407'
			or $act_data['a2']=='Postboks 407'
			or $act_data['a3']=='Postboks 407')
	) {
		$act_data['town']='Straume';
		$act_data['postcode']='5343';
		$skip_del_address=true;
	}


	if ($act_data['country']=='Norway'  and
		(
			$act_data['a1']=='Straumsfjellsvegen 9'
			or $act_data['a3']=='Straumsfjellsvegen 9'
			or $act_data['a2']=='Straumsfjellsvegen 9')
	) {
		$skip_del_address=true;
		$act_data['town']='Straume';
		$act_data['postcode']='5343';

	}

	if (
		(
			preg_match('/^Via Bssa$|Via Bssa.*11/i',$act_data['a1'])
			or  preg_match('/^Via Bssa$|Via Bssa.*11/i',$act_data['a2'])
			or  preg_match('/^Via Bssa$|Via Bssa.*11/i',$act_data['a3'])
		)

	) {

		$act_data['town']='Mestre';
		$act_data['postcode']='30173';
		$act_data['country_d1']='';
		$act_data['country_d2']='';
		$act_data['a1']='Via Bssa, 11';
		$act_data['a2']='';
		$act_data['a3']='';
	}



	if ($act_data['town']=='Korea South' and $act_data['country']=='' ) {
		$act_data['country']='Korea South';
		$act_data['town']='';

		if ($act_data['a3']=='Seoul') {
			$act_data['town']='Seoul';
			$act_data['a3']='';
		}
	}

	if ($act_data['postcode']=='Korea South' and ($act_data['country']=='' or $act_data['country']=='Korea South' ) ) {
		$act_data['country']='Korea South';
		$act_data['postcode']='';

		if ($act_data['a3']=='Seoul') {
			$act_data['town']='Seoul';
			$act_data['a3']='';
		}
	}


	if (preg_match('/^eire$/i',$act_data['postcode'])) {
		$act_data['country']='Ireland';
		$act_data['postcode']='';

	}

	if (preg_match('/^524 95 Ljung$/i',$act_data['town']) and $act_data['postcode']='') {
		$act_data['town']='Ljung';
		$act_data['postcode']='52495';

	}

	if (preg_match('/^CH\s*-\s*\d+\s+/i',$act_data['town'],$match) and $act_data['postcode']='') {
		$act_data['town']=preg_replace('/^CH\s*-\s*\d+\s+','',$act_data['town']);
		$act_data['postcode']=_trim($match[0]);
	}
	if (preg_match('/^(d)?\d{4,}\s+/i',$act_data['town'],$match) and $act_data['postcode']='') {
		$act_data['town']=preg_replace('/^\d{4,}\s+/i','',$act_data['town']);
		$act_data['postcode']=_trim($match[0]);
	}



	if ($act_data['name']=='Incensed ! / Sarah Ismaeel') {
		$act_data['name']='Incensed';
	}
	if ($act_data['name']=="Wax N Wicca" or $act_data['name']=="Wax 'N' Wicca") {
		$act_data['name']="Wax 'n' Wicca";
		$act_data['act']='32279';
	}

	if ($act_data['name']=="Attah-Hicks" or $act_data['act']=="32437") {
		$act_data['act']='29980';
	}

	if ($act_data['name']=="Wax 'n' Wicca" and $act_data['contact']='P Lewis') {
		$act_data['contact']="Pam Lewis";
		$act_data['first_name']="Pam";
	}

	if (preg_match('/\(.+\)/i',$act_data['name'],$match)) {
		$_contact=preg_replace('/^\(|\)$/i','',$match[0]);
		// print "$_contact\n";
		if (strtolower($_contact)==strtolower($act_data['contact'])) {
			$act_data['name']=preg_replace('/\(.+\)/i','',$act_data['name']);
		}
	}
	if (preg_match('/^M/i',$act_data['postcode']) and $act_data['town']=='Manchester') {
		$act_data['country']='UK';
	}

	if ($act_data['a1']=='Sharn Brook' and $act_data['town']=='') {
		$act_data['a1']='NULL';
		$act_data['town']='Sharnbrook';
	}


	if ($act_data['a2']=='Dhahran' and $act_data['town']=='East Province') {
		$act_data['a2']='';
		$act_data['town']='Dhahran';



	}

	if ( preg_match('/^belfast\s*,/i',$act_data['town'])) {
		$act_data['town']='Belfast';
	}
	if ( preg_match('/^via cork$/i',$act_data['town'])) {
		$act_data['town']='';
	}


	if ( preg_match('/^co\.? (Westmeath|Meath)$/i',$act_data['town'])) {
		$act_data['town']='';
	}


	if ($act_data['country']=='') {


		if (preg_match('/spain\s*.\s*ibiza/i',$act_data['postcode'])) {
			$act_data['country']='Spain';
			$act_data['postcode']='';
			$act_data['country_d1']='Balearic Islands';
			$act_data['country_d2']='Balearic Islands';
		}



		$tmp_array=preg_split('/\s+/',$act_data['postcode']) ;

		if (count($tmp_array)==2 and !preg_match('/\d/',$act_data['postcode']) ) {
			$sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($tmp_array[0]),prepare_mysql($tmp_array[0]));


			$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$act_data['country']=$row['name'];
				$act_data['postcode']=$tmp_array[1];
			}

			$sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($tmp_array[1]),prepare_mysql($tmp_array[1]));


			$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$act_data['country']=$row['name'];
				$act_data['postcode']=$tmp_array[0];
			}
		}
		elseif (count($tmp_array)==1 and !preg_match('/\d/',$act_data['postcode']) and $act_data['postcode']!='') {

			$sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` where  `Country Name`=%s",prepare_mysql($tmp_array[0]));

			$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$act_data['country']=$row['name'];
				$act_data['postcode']='';
			} else {

				$sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s",prepare_mysql($tmp_array[0]));


				$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
				if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
					$act_data['country']=$row['name'];
					$act_data['postcode']='';
				}

			}



		}
	} else {
		//    print_r($act_data);
		if (strtolower(_trim($act_data['country']))==strtolower(_trim($act_data['postcode'])))
			$act_data['postcode']='';
	}

	if ($act_data['postcode']=='3742' and $act_data['town']=='SW Baarn') {
		$act_data['town']='Baarn';
		$act_data['postcode']='3742 SW';
	}




	if ($act_data['postcode']=="" and preg_match('/\s*\d{4,6}\s*/i',$act_data['town'],$match)) {

		if ($act_data['country']!="Netherlands") {
			$act_data['postcode']=_trim($match[0]);
			$act_data['town']=preg_replace('/\s*\d{4,6}\s*/','',$act_data['town']);
		}
	}


	if ($act_data['a2']=='Ascheffel') {
		$act_data['town']='Ascheffel';
		$act_data['a2']='';
	}
	if (preg_match('/South Afrika$|South Africa$/i',$act_data['postcode'])) {
		$act_data['country']='South Africa';
		$act_data['postcode']=_trim(preg_replace('/South Afrika$|South Africa$/i','',$act_data['postcode']));
	}

	if (preg_match('/Via Chiesanuova, 71/i',$act_data['a1']) and preg_match('/C.O .c.c. Futura2./i',$act_data['a2'])  ) {
		$act_data['a1']='C/O CC Futura';
		$act_data['a2']='Via Chiesanuova, 71';
	}

	if (preg_match('/United States$/i',$act_data['postcode'])) {
		$act_data['country']='USA';
		$act_data['postcode']=_trim(preg_replace('/United States$/i','',$act_data['postcode']));
	}


	if (preg_match('/Lewiston - Ny/i',$act_data['town'])) {
		$act_data['country']='USA';
		$act_data['town']='Lewiston';
		$act_data['country_d1']='NY';

	}
	//print_r($act_data);
	if (preg_match('/^101 Reykjavik$/i',$act_data['postcode'])) {
		$act_data['town']='Reykjavik';
		$act_data['country']='Iceland';
		$act_data['postcode']='101';
		// print_r($act_data);
	}
	if (preg_match('/^101 Reykjavik$/i',$act_data['town'])) {
		$act_data['town']='Reykjavik';
		$act_data['country']='Iceland';
		$act_data['postcode']='101';
		// print_r($act_data);
	}


	if (preg_match('/Fi\-\d{4,5}/i',$act_data['postcode'])  and $act_data['country_d1']=='') {

		$act_data['country']='Finland';
		// print_r($act_data);
	}


	if (preg_match('/Drogheda.*Co Louth/i',$act_data['town'])) {

		$act_data['town']='Drogheda';
		$act_data['country_d2']='Co Louth';

	}

	if (preg_match('/Tampa\s*.\s*Florida/i',$act_data['town'])) {

		$act_data['town']='Tampa';
		$act_data['country_d1']='Florida';

	}

	if ($act_data['country']=='USA' and   preg_match('/\-\s*ny/i',$act_data['town'])) {

		$act_data['town']=preg_replace('/\-\s*ny/i','',$act_data['town']);
		$act_data['country_d1']='New York';

	}



	if (preg_match('/alberta/i',$act_data['town'])  and  preg_match('/Onoway/i',$act_data['a3']) ) {
		$act_data['a3']='';
		$act_data['town']='Onoway';
		$act_data['country_d1']='Alberta';

	}




	if (preg_match('/alicante/i',$act_data['country_d2']) and $act_data['country']==''  ) {
		$act_data['country']='Spain';
		$act_data['country_d1']='Valencia';
	}

	if (preg_match('/Alfaz del Pi - Alicante/i',$act_data['town'])  ) {
		$act_data['town']='Alfaz del Pi';
		$act_data['country_d2']='Alicante';
		$act_data['country_d1']='Valencia';
	}


	if (preg_match('/Viterbo/i',$act_data['town'])  and preg_match('/Soriano Nel Cimino/i',$act_data['a2']) ) {
		$act_data['country']='Italy';
		$act_data['town']='Soriano Nel Cimino';
		$act_data['country_d1']='Lazio';
		$act_data['country_d2']='Viterbo';
		$act_data['a2']='';
		$act_data['postcode']='01028';
	}



	// Her we fix the distinct speciffic errors in the input fiels
	$act_data['town']=_trim($act_data['town']);

	if ($act_data['country']=='Cyprus') {
		if ($act_data['a3']=='1065 Nicosia') {
			$act_data['postcode']='1065';
			$act_data['town']='Nicosia';
			$act_data['a3']='';
		}
	}

	if ($act_data['postcode']=='Cyprus') {
		$act_data['country']='Cyprus';
		$act_data['postcode']='';
	}




	if ($act_data['postcode']=='' and preg_match('/\s*no\-\d{4}\s*/',$act_data['town'],$match) ) {
		$act_data['postcode']=_trim($match[0]);
		$act_data['town']=preg_replace('/\s*no\-\d{4}\s*/','',$act_data['town']);

	}

	// print_r($act_data);

	if ($act_data['country']=='Korea South' or $act_data['country']=='South Korea') {
		if ($act_data['a3']=='Seoul' ) {
			$act_data['town']='Seoul';
			$act_data['a3']='';
		}
		if (preg_match('/^Kangseo.Gu$/i',_trim($act_data['a2']))) {
			$act_data['town_d1']='Gangseo-gu';
			$act_data['a2']='';
		}
		if ($act_data['a1']=='105-207 Whagok-Dong') {
			$act_data['town_d2']='Hwagok-dong';
			$act_data['a1']='105-207';
		}

	}

	// print_r($act_data);

	if ($act_data['a2']=='Yokneam Ilit' ) {
		$act_data['a2']='';
		$act_data['town']='Yokneam Ilit';
	}
	if (preg_match('/Pyrgos.*Limassol/',$act_data['town'])) {

		$act_data['town']='Pyrgos';
	}


	if ($act_data['a1']=='Sharn Brook' and $act_data['town']=='Bedfordshire') {
		$act_data['a1']='NULL';
		$act_data['town']='Sharnbrook';
	}

	if ($act_data['a2']=='Upper Marlboro' and $act_data['town']=='Md') {
		$act_data['a2']='';
		$act_data['town']='Upper Marlboro';
		$act_data['country_d1']='MD';
	}



	if ($act_data['a2']=='55299 Nackenheim' and $act_data['town']=='Germany') {
		$act_data['a2']='';
		$act_data['country_d1']='';
		$act_data['postcode']='55299';
		$act_data['town']='Nackenheim';
	}

	if ($act_data['town']=='Siverstone - Oregon') {
		$act_data['town']='Siverstone';
		$act_data['country_d2']='Oregon';
		$act_data['country']='USA';
	}

	if ($act_data['town']=='5227 Nesttun') {
		$act_data['town']='Nesttun';
		$act_data['postcode']='5227';
	}
	if ($act_data['town']=='3960 Stathelle') {
		$act_data['town']='Stathelle';
		$act_data['postcode']='3960';
	}
	if ($act_data['town']=='45700 Kuusankoski') {
		$act_data['town']='Kuusankoski';
		$act_data['postcode']='45700';
	}
	if ($act_data['town']=='06880 Kärrby') {
		$act_data['town']='Kärrby';
		$act_data['postcode']='06880';
	}
	if ($act_data['town']=='2500 Valby') {
		$act_data['town']='Valby';
		$act_data['postcode']='2500';
	}

	if ($act_data['town']=='21442 Malmö') {
		$act_data['town']='Malmö';
		$act_data['postcode']='21442';
	}
	if ($act_data['town']=='11522 Stockholm') {
		$act_data['town']='Stockholm';
		$act_data['postcode']='11522';
	}

	if ($act_data['town']=='1191 Jm Ouderkerk A/d Amstel') {
		$act_data['town']='Ouderkerk aan de Amstel';
		$act_data['postcode']='1191JM';
	}
	if ($act_data['town']=='7823 Pm Emmen') {
		$act_data['town']='Emmen';
		$act_data['postcode']='7823PM';
	}
	if ($act_data['town']=='1092 Budapest') {
		$act_data['town']='Budapest';
		$act_data['postcode']='1092';
	}


	if ($act_data['town']=='Lanzarote, las Palmas' ) {
		$act_data['town']='';

		$act_data['country_d1']='Canary Islands';

		$act_data['country_d2']='Las Palmas';
		$act_data['town']='';

		if ( $act_data['a2']=='Costa Teguise') {
			$act_data['a2']='';
			$act_data['town']='Costa Teguise';
		}


	}


	if ($act_data['town']=='Zugena - Provincia Almeria') {
		$act_data['town']='Zurgena';
		$act_data['country_d2']='Almeria';
		$act_data['country_d1']='Adalucia';

	}

	if ($act_data['town']=='Alhama de Almeria, Almeria') {
		$act_data['town']='Alhama de Almeria';
		$act_data['country_d2']='Almeria';
		$act_data['country_d1']='Adalucia';

	}

	if ($act_data['town']=='Coulby Newham - Middlesbrough') {
		$act_data['country_d2']='Middlesbrough';
		$act_data['town']='Coulby Newham';
	}

	if ($act_data['town']=='Lerwick - Shetland Isles') {
		$act_data['country_d2']='Shetland Islands';
		$act_data['town']='Lerwick';
	}
	if ($act_data['town']=='Ollaberry - Shetland Islands') {
		$act_data['country_d2']='Shetland Islands';
		$act_data['town']='Ollaberry';
	}
	if ($act_data['town']=='Shetland - Shetland Islands' and $act_data['a1']=='Brae' ) {
		$act_data['country_d2']='Shetland Islands';
		$act_data['town']='Brae';
		$act_data['a1']='NULL';

	}

	if (preg_match('/$MK40.*1hs/i',$act_data['postcode']) ) {
		$act_data['country']='United Kingdom';

	}

	if (preg_match('/DH5.*9RS/i',$act_data['postcode'])  and $act_data['a1']=='Linden House' ) {
		$act_data['a1']='Linden House';
		$act_data['a2']='2 Heather Drive';
		$act_data['a3']='';
		$act_data['town']='Houghton Le Spring';
	}

	if ($act_data['town']=='Malaga' and $act_data['a2']=='Coin') {
		$act_data['town']='Coin';
		$act_data['country_d1']='Andalusia';
		$act_data['country_d2']='Malaga';
		$act_data['a2']='';
	}

	if ($act_data['town']=='Villasor Pr. Cagliari') {
		$act_data['town']='Villasor';
		$act_data['country_d2']='Cagliari';
	}

	if ($act_data['town']=='Leebotwood (nr Church Stretton') {
		$act_data['town']='Leebotwood Nr. Church Stretton';
	}
	if ($act_data['town']=='Nea Moudhania - Chalkidiki') {
		$act_data['town']='Nea Moudhania';
		$act_data['country_d2']='Chalkidiki';
	}

	if ($act_data['town']=='Cradley Heath, West Midlands') {
		$act_data['town']='Cradley Heath';
		$act_data['country_d2']='';
	}

	if ($act_data['town']=='Garswood, Ashton In Makerf') {
		$act_data['town']='Ashton-in-Makerfield';
		$act_data['town_d2']='Garswood';
	}
	if ($act_data['town']=='Boulogne Billancourt Cedex') {
		$act_data['town']='Boulogne Billancourt';
	}

	if ($act_data['town']=='Furzton - Milton Keynes') {
		$act_data['town']='Milton Keynes';
		$act_data['town_d2']='Furzton';
	}

	if ($act_data['town']=='Glenfield - Leicester') {
		$act_data['town']='Leicester';
		$act_data['town_d2']='Glenfield';
	}
	if ($act_data['town']=='Edinburgh - Midlothian') {
		$act_data['town']='Edinburgh';
	}

	if ($act_data['town']=='Killorglin - Co Kerry') {
		$act_data['town']='Killorglin';
	}
	if ($act_data['town']=='Castledawson - Co Derry') {
		$act_data['town']='Castledawson';
	}
	if ($act_data['town']=='Douglas, Isle of Man') {
		$act_data['town']='Douglas';
		$act_data['country']='Isle of Man';
	}

	if ($act_data['town']=='Aberdeen, Aberdeenshire') {
		$act_data['town']='Aberdeen';
	}

	if ($act_data['town']=='Elephant & Castle, London') {
		$act_data['town']='London';
		$act_data['town_d2']='Elephant & Castle';
	}
	if ($act_data['town']=='Muswell Hill, London') {
		$act_data['town']='London';
		$act_data['town_d2']='Muswell Hill';
	}

	if ($act_data['town']=='South Norwood, London') {
		$act_data['town']='London';
		$act_data['town_d2']='South Norwood';
	}

	if (preg_match('/Isle of Wight/i',$act_data['town']))
		$act_data['town']='';

	if ($act_data['town']=='Walkinstown - Dublin') {
		$act_data['town']='Dublin';
		$act_data['town_d2']='Walkinstown';
	}
	if ($act_data['town']=='Yarmouth - Isle of Wight') {
		$act_data['town']='Yarmouth';
		$act_data['country_d2']='Isle of Wight';
	}
	if ($act_data['town']=='New Port - Isle of Wight') {
		$act_data['town']='New Port';
		$act_data['country_d2']='Isle of Wight';
	}





	if ($act_data['town']=='Kingston-Upon Thames') {
		$act_data['town']='Kingston-Upon-Thames';
	}

	if ($act_data['town']=='Bradford - On - Avon') {
		$act_data['town']='Bradford-On-Avon';
	}

	if ($act_data['town']=='Tongham - Nr Farnham') {
		$act_data['town']='Tongham Nr. Farnham';
	}

	if ($act_data['town']=='Hornbæk - Sjælland') {
		$act_data['town']='Hornbæk';
		$act_data['country_d2']='Sjælland';
	}



	if ($act_data['town']=='7779de Overijssel') {
		$act_data['town']='Overijssel';
		$act_data['postcode']='7779DE';
	}
	if ($act_data['town']=='3015 Br Rotterdam') {
		$act_data['town']='Rotterdam';
		$act_data['postcode']='3015BR';
	}

	$act_data['postcode']=_trim(preg_replace('/the Netherlands/i','',$act_data['postcode']));

	if ( preg_match('/boggon/i',$act_data['name'])  and preg_match('/35617|48051/i',$act_data['act'])    ) {
		$act_data['name']='Temenos Academy';
	}
	if ( preg_match('/dudden/i',$act_data['name'])  and preg_match('/25124/i',$act_data['act'])    ) {
		$act_data['name']='Mr Jeff C Dudden';
	}


	if ( preg_match('/Spain.*Canary Island/i',$act_data['country'])  and preg_match('/Arguineguin/i',$act_data['a2'])    ) {
		$act_data['a2']='';
		$act_data['a3']='';
		$act_data['town']='Arguinegín';
		$act_data['country_d2']='Las Palmas';
		$act_data['country_d1']='Canary Islands';
		$act_data['country']='Spain';

	}


	if ( preg_match('/Spain.*Canary Island/i',$act_data['country'])      ) {
		$act_data['country_d1']='Canary Islands';
		$act_data['country']='Spain';
	}
	if ( preg_match('/^Tenerife.*Canary Island/i',$act_data['town'])      ) {
		$act_data['country_d1']='Canary Islands';
		$act_data['country']='Spain';
		$act_data['town']='Tenerife';

		if ($act_data['a2']=='Playa de las Americas' and $act_data['a3']=='Adeje') {
			$act_data['a2']='';
			$act_data['a3']='';
			$act_data['town']='Playa de las Americas';
		}

	}

	if (preg_match('/Northern Ireland/i',$act_data['town'])  ) {
		$act_data['town']=_trim(preg_replace('/\,?\-?\s*Northern Ireland/i','',$act_data['town']));
	}

	if ( preg_match('/^bfpo\s*\d/i',$act_data['town'])  and $act_data['postcode']=='' ) {
		$act_data['postcode']=strtoupper($act_data['town']);
		$act_data['town']='';
	}

	if ( preg_match('/^je\d/i',$act_data['postcode'])  and $act_data['country']=='' ) {
		$act_data['country']='Jersey';
		if ($act_data['town']=='Jersey')
			$act_data['town']='';
	}
	if ( preg_match('/^im\d/i',$act_data['postcode'])  and $act_data['country']=='' ) {
		$act_data['country']='Isle of Man';

		if ($act_data['a2']=='Ramsey') {
			$act_data['town']='Ramsey';
			$act_data['a2']='';
		}

		if (preg_match('/Isle of Man/i',$act_data['town'])  ) {
			$act_data['town']=_trim(preg_replace('/\,?\-?\s*Isle of Man/i','',$act_data['town']));

		}
	}

	if (preg_match('/^Norfolk$|^West Midlands$/i',$act_data['town']))
		$act_data['town']='';


	if ($act_data['town']=='St.pauls Bay')
		$act_data['town']='St Pauls Bay';

	if ($act_data['town']=='Outside the Royal Festival Hall') {
		$act_data['town']='London';
		$act_data['country_d2']='';
		$act_data['country_d1']='';
	}

	if ($act_data['town']=='Ashton Under Lyne, Tameside')
		$act_data['town']='Ashton Under Lyne';


	if (preg_match('/Las Palmas de Gran Canaria/i',$act_data['a2'])  ) {
		$act_data['a2']='';
		$act_data['country_d2']='Las Palmas';
		$act_data['country_d1']='Canary Islands';
		$act_data['country']='Spain';

	}

	if ($act_data['postcode']=='5260Demnark')
		$act_data['postcode']='DK-5260';


	if (preg_match('/ch6\s*5dz/i', $act_data['country'] )) {

		$act_data['country']='';
		$act_data['postcode']='ch6 5dz';
	}

	if ($act_data['country']=='Scotish Island' or $act_data['country']=='West Sussex' ) {
		$act_data['country']='';
	}





	if ( preg_match('/Mark Postage To France/i',$act_data['a1'])) {
		$act_data['a1']='';
	}

	if ( preg_match('/Spain.*Baleares/i',$act_data['country']) ) {
		$act_data['country_d1']='Balearic Islands';
		$act_data['country']='Spain';
	}

	if ($act_data['town']=='7182 Calvia - Mallorca') {
		$act_data['postcode']='07182';
		$act_data['town']='Calvia';
		$act_data['country_d1']='Balearic Islands';
		$act_data['country_d2']='Balearic Islands';
	}

	if ($act_data['town']=='Lefkosia (nicosia)')
		$act_data['town']='Nicosia';


	if ($act_data['town']=='07820 San Antonio - Ibiza')
		$act_data['postcode']='07820';

	if ($act_data['postcode']=='Co Cork, Ireland')
		$act_data['postcode']='';
	if ($act_data['town']=='Alicante - Spain')
		$act_data['town']='Alicante';
	if ( preg_match('/San Antonio.*Ibiza/i',$act_data['town']) ) {
		$act_data['town']='Sant Antoni de Portmany';

	}

	if ( preg_match('/Perth.*Western Autralia/i',$act_data['town']) ) {
		$act_data['town']='Perth';
		$act_data['country_d2']='Western Autralia';

	}
	if ($act_data['a1']=='Kerem Maharal,' ) {
		$act_data['a1']='NULL';
		$act_data['town']='Kerem Maharal';
	}

	if ( preg_match('/Bs37 7rb|S2 3eh/i',$act_data['town'])) {
		$act_data['town']='';
		$act_data['postcode']=strtoupper($act_data['town']);
	}

	if ( preg_match('/castle market/i',$act_data['a3'])  and $act_data['postcode']=='' and $act_data['town']=='Sheffield' ) {
		$act_data['postcode']='S1 2AD';
	}
	if ($act_data['town']=='Albox, Almeria') {
		$act_data['town']='Albox';
		$act_data['country_d1']='Andalucía';
		$act_data['country_d2']='Almería';
	}

	if ( preg_match('/^bfpo\s+\d+/i',$act_data['town'])) {
		$act_data['country']='United Kingdom';
		$act_data['town']='';
		$act_data['postcode']=strtoupper($act_data['town']);
	}

	if ($act_data['postcode']=='50004 Zaragoza') {
		$act_data['town']='Zaragoza';
		$act_data['postcode']='50004';
	}
	if ($act_data['postcode']=='08530 Barcelona') {
		$act_data['town']='Barcelona';
		$act_data['postcode']='08530';
	}
	if ($act_data['postcode']=='28300 Madrid') {
		$act_data['town']='Madrid';
		$act_data['postcode']='28300';
	}
	if ($act_data['postcode']=='28013 Madrid') {
		$act_data['town']='Madrid';
		$act_data['postcode']='28013';
	}

	if ($act_data['act']=='27821') {
		$act_data['act']='21179';
		$act_data['name']='Soap & Soak';
	}


	if (strtolower($act_data['town'])=='la romana (alicante)') {
		$act_data['town']='La Romana';
		$act_data['country_d2']='Alicante';
		$act_data['country_d1']='Valencia';
	}

	if ($act_data['town']=='Sax (alicante)') {
		$act_data['town']='Sax';
		$act_data['country_d2']='Alicante';
		$act_data['country_d1']='Valencia';
	}


	if ($act_data['postcode']=='30383 Cartagena') {
		$act_data['town']='Cartagena';
		$act_data['postcode']='30383';
	}
	if ($act_data['postcode']=='07760 Ciutadella') {
		$act_data['town']='Ciutadella';
		$act_data['postcode']='07760';
	}


	if ($act_data['town']=='Tucson Az') {
		$act_data['town']='Tucson';
		$act_data['country_d2']='Arizona';
	}

	if ($act_data['country']=='Ireland' and $act_data['a3']=='Castleblaney' ) {
		$act_data['town']='Castleblaney';
		$act_data['country_d2']='Monaghan';
		$act_data['a3']='';
	}


	if ($act_data['town']=='Port Angeles (wa)') {
		$act_data['town']='Port Angeles';
		$act_data['country_d2']='WA';
	}
	if ($act_data['town']=='Beverly Hills (ca)') {
		$act_data['town']='Beverly Hills';
		$act_data['country_d2']='California';
	}
	if ($act_data['town']=='Milwaukee, Wi') {
		$act_data['town']='Milwaukee';
		$act_data['country_d2']='Wi';
	}

	if ($act_data['town']=='Kingston, Ma') {
		$act_data['town']='Kingston';
		$act_data['country_d2']='Ma';
	}
	if ($act_data['town']=='Mcdonough, Ga') {
		$act_data['town']='Mcdonough';
		$act_data['country_d2']='Ga';
	}
	if ($act_data['town']=='Bridgewater, Nj') {
		$act_data['town']='Bridgewater';
		$act_data['country_d2']='NJ';
	}
	if ($act_data['town']=='Marietta, Ga') {
		$act_data['town']='Marietta';
		$act_data['country_d2']='Ga';
	}
	if ($act_data['town']=='Duluth - Ga') {
		$act_data['town']='Duluth';
		$act_data['country_d2']='Ga';
	}


	if ($act_data['town']=='Hoffman Estates - Il.') {
		$act_data['town']='Hoffman Estates';
		$act_data['country_d2']='Il';
	}
	if ($act_data['town']=='Shelton Ct') {
		$act_data['town']='Shelton';
		$act_data['country_d2']='Ct';
	}
	if ($act_data['town']=='Raton - Nm.') {
		$act_data['town']='Raton';
		$act_data['country_d2']='NM';
	}
	if ($act_data['town']=='Monett, Mo') {
		$act_data['town']='Monett';
		$act_data['country_d2']='MO';
	}
	if ($act_data['town']=='Alton, Il') {
		$act_data['town']='Alton';
		$act_data['country_d2']='Il';
	}
	if ($act_data['town']=='Zanesville, Ohio') {
		$act_data['town']='Zanesville';
		$act_data['country_d2']='Ohio';
	}
	if ($act_data['town']=='Pinola, Ms') {
		$act_data['town']='Pinola';
		$act_data['country_d2']='MS';
	}
	if ($act_data['town']=='Port Jefferson Station - Ny') {
		$act_data['town']='Port Jefferson Station';
		$act_data['country_d2']='NY';
	}
	if ($act_data['town']=='Houston - Texas') {
		$act_data['town']='Houston';
		$act_data['country_d2']='Texas';
	}
	if ($act_data['town']=='Cambell Hall - Ny') {
		$act_data['town']='Cambell Hall';
		$act_data['country_d2']='NY';
	}
	if ($act_data['postcode']=='04400 Almeria - SPAIN') {
		$act_data['postcode']='04400';
		$act_data['country_d1']='Andalucía';
		$act_data['country_d2']='Almería';
	}

	if ( preg_match('/Whaley Bridge, Derbyshire Sk23 7jg/i',$act_data['town'])) {
		$act_data['country']='United Kingdom';
		$act_data['town']='Whaley Bridge';
		$act_data['postcode']='SK23 7JG';
	}

	if ( preg_match('/Beirut\s*.\s*Lebanon/i',$act_data['country'])) {
		$act_data['country']='Lebanon';
		$act_data['town']='Beirut';
	}

	if ( preg_match('/01902 850 006|north ayrshire|stoke.on trent|Suffolk|Norfolk/i',$act_data['country']))
		$act_data['country']='';

	if ( preg_match('/Channel Islands/i',$act_data['country']) ) {

		if (preg_match('/^(jersey\s+)?\s*je/i',$act_data['postcode'])) {

			$act_data['postcode']=preg_replace('/\s*jersey\s*/i','',$act_data['postcode']);
			$act_data['country']='Jersey';



		}
	}


	if ( preg_match('/ireland/i',$act_data['country']) and preg_match('/^bt/i',$act_data['postcode']) )
		$act_data['country']='United Kingdom';

	if ( preg_match('/Co Kerry, Ireland/i',$act_data['country'])  )
		$act_data['country']='Ireland';


	if ($act_data['act']=='21808') {
		$act_data['name']='Luss Glass Studio';
		$act_data['contact']='Janine Smith';
	}

	if ($act_data['act']=='33387') {
		$act_data['act']='9050';
	}


	if ($act_data['name']=='Crocodile Antiques (1)')
		$act_data['name']='Crocodile Antiques';

	// print $act_data['mob'].'-'.$act_data['act']."-\n";
	if ($act_data['mob']=='01723 376447' and $act_data['act']=='26456') {
		$act_data['mob']='';
	}

	if ($act_data['contact']=='Thandi' and $act_data['act']=='21217') {
		$act_data['contact']='Thandi Viljoen';
	}



	if (preg_match('/G12 8aa/i',$act_data['country'])) {
		$act_data['country']='';
		$act_data['postcode']='G12 8AA';
	}


	$split_town=preg_split('/\s*,\s*/i',$act_data['town']);
	if (count($split_town)==2) {
		if (preg_match('/jersey/i',$split_town[1])) {
			$act_data['town']=$split_town[0];
			$act_data['country']='Jersey';
		}

	}
	if (check_email_address($act_data['country'])) {
		if ($act_data['email']=='')
			$act_data['email']=$act_data['country'];
		$act_data['country']='';
	}
	if (preg_match('/Clwyd/i',$act_data['country']))$act_data['country']='';

	if ($act_data['country']=='Harmelen (netherlands)') {
		$act_data['town']='Harmelen';
		$act_data['country']='netherlands';
	}

	if ($act_data['postcode']=='USA') {
		$act_data['postcode']='';
		$act_data['country']='United States';
	}

	if ($act_data['town']=='Fgura, Europe') {
		$act_data['town']='Fgura';
	}
	if ($act_data['town']=='3800 Limburg') {
		$act_data['town']='Limburg';
		$act_data['postcode']='3800';
	}
	if ($act_data['town']=='West Vlaanderen') {
		$act_data['town']='West Vlaanderen';
		$act_data['postcode']='8800';
	}



	if ($act_data['town']=='Nordrheinwestfalen' and $act_data['a2']=='Bochum') {
		$act_data['town']='Bochum';
		$act_data['country_d1']='Nordrhein-Westfalen';
		$act_data['a2']='';
	}

	if ($act_data['town']=='Schwaig, Bavaria') {
		$act_data['town']='Schwaig';
		$act_data['country_d1']='Bayern';
	}
	if ($act_data['town']=='Central Milton Keynes') {
		$act_data['town']='Milton Keynes';
	}
	if ($act_data['town']=='No-5353 Straume') {
		$act_data['town']='Straume';
		$act_data['postcode']='No-5353';
	}

	if ($act_data['a2']=='Vibrac' and $act_data['a3']=='Charente') {
		$act_data['a2']='';
		$act_data['a3']='';
		$act_data['town']='Vibrac';
		$act_data['country_d1']='Poitou-Charentes';
		$act_data['country_d2']='Charente';

	}



	if ($act_data['town']=='Tiefenau' and $act_data['postcode']=='1609') {
		$act_data['country_d1']='Sachsen';
		$act_data['postcode']='01609';
	}

	if ($act_data['town']=='Abingdon Oxfordshire')
		$act_data['town']='Abingdon';

	if ($act_data['town']=='Bromham, Chippenham')
		$act_data['town']='Bromham';
	if ($act_data['town']=='Buckinghamshire')
		$act_data['town']='';

	// print_r($act_data);
	if (preg_match('/\s*eire\*/i',$act_data['postcode'])) {
		$act_data['postcode']='';
		$act_data['country']='Ireland';
	}

	if (preg_match('/MO 63136/i',$act_data['postcode']) and  $act_data['country']='USA'  ) {
		$act_data['postcode']='63136';
		$act_data['country_d1']='MO';
	}



	if ($act_data['town']=='Halle' and $act_data['postcode']=='33790') {
		$act_data['country_d1']='Nordrhein-Westfalen';

	}


	if (preg_match('/^\s*\d{4,6}\s*$/',$act_data['town'])) {
		$act_data['postcode']=$act_data['town'];
		$act_data['town']='';
	}
	if ($act_data['town']=='Bilbao - Vizcaya') {
		$act_data['town']='Bilbao';
		$act_data['country_d2']='Vizcaya';
	}

	if ($act_data['country']=='Balearic Isles') {
		$act_data['country']='Spain';
		$act_data['country_d1']='Balearic Islands';

	}


	if ($act_data['country']=='Guernsey, C.i')
		$act_data['country']='Guernsey';

	if ($act_data['town']=='Guernsey, C.i') {
		$act_data['town']='Guernsey';
		$act_data['country']='Guernsey';
	}

	if ($act_data['town']=='South yorkshire') {
		$act_data['town']='';

		if ($act_data['a3']!='') {
			$act_data['town']=$act_data['a3'];
			$act_data['a3']='';
		} elseif ($act_data['a2']!='') {
			$act_data['town']=$act_data['a2'];
			$act_data['a2']='';
		}
	}
	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// fix contracts
	//  print_r($act_data);
	if ($act_data['name']=='Igneous Products' and $act_data['contact']=='Les') {
		$act_data['contact']='';

	}

	$extra_contact=false;
	if ($act_data['contact']!='') {

		$_contact=$act_data['contact'];
		$split_names=preg_split('/\s+and\s+|\&|\/|\s+or\s+/i',$act_data['contact']);
		if (count($split_names)==2) {
			$split_names1=preg_split('/\s+/i',$split_names[0]);
			$split_names2=preg_split('/\s+/i',$split_names[1]);
			if (count($split_names1)==1 and count($split_names2)==2 ) {
				$name1=$split_names1[0].' '.$split_names2[1];
				$name2=$split_names[1];
			} else {
				$name1=$split_names[0];
				$name2=$split_names[1];
			}
			$act_data['contact']=$name1;
			$extra_contact=$name2;
			if ($_contact==$act_data['name']) {
				$act_data['name']=preg_replace('/\s+and\s+|\&|\/|\s+or\s+/i',' & ',$act_data['name']);
			}

		}
		$there_is_contact=true;
	}

	if ($act_data['contact']==$act_data['name'] or  $act_data['name']=='' and $act_data['contact']!='') {
		// we dont hasve person name
		$there_is_contact=false;
		if (!preg_match('/C \& P Trading|Peter \& Paul Ltd|Health.*Beauty.*Salon|plant.*herb|Igneous Products/i',$act_data['contact'])) {
			$act_data['name']=$act_data['contact'];
			$act_data['contact']='';
		}

	}
	if (preg_match('/^c\/o/i',$act_data['a1'])) {
		$co=$act_data['a1'];
		$act_data['a1']='';
	}
	if (preg_match('/^c\/o/i',$act_data['a2'])) {
		$co=$act_data['a2'];
		$act_data['a2']='';
	}
	if (preg_match('/^c\/o/i',$act_data['a3'])) {
		$co=$act_data['a3'];
		$act_data['a3']='';
	}

	if (preg_match('/@/',$act_data['country']))
		$act_data['country']='';
	$act_data['tel']=preg_replace('/\[\d*\]/','',$act_data['tel']);
	$act_data['tel']=preg_replace('/\(/','',$act_data['tel']);
	$act_data['tel']=preg_replace('/\)/','',$act_data['tel']);
	$act_data['fax']=preg_replace('/\[\d*\]/','',$act_data['fax']);
	$act_data['fax']=preg_replace('/\(/','',$act_data['fax']);
	$act_data['fax']=preg_replace('/\)/','',$act_data['fax']);
	$act_data['mob']=preg_replace('/\[\d*\]/','',$act_data['mob']);
	$act_data['mob']=preg_replace('/\(/','',$act_data['mob']);
	$act_data['mob']=preg_replace('/\)/','',$act_data['mob']);
	return $act_data;

}


function check_email_address($email) {
	return Email::is_valid($email);
}
function guess_email($email,$contact='',$tipo=1) {
	if (check_email_address($email) ) {

		// if($contact=='')
		//  $contact=get_name($contact_id);
		$email_data=array ('email'=>$email,'contact'=>$contact,'tipo'=>$tipo);

		return $email_data;

	}

	else
		return false;
}

function get_address_raw() {

	$address1='';
	$address2='';
	$address3='';
	$town_d2='';
	$town_d1='';
	$town='';
	$country_d2='';
	$country_d1='';
	$postcode='';
	$country='';
	$town_d2_id=0;
	$town_d1_id=0;
	$town_id=0;
	$country_d2_id=0;
	$country_d1_id=0;
	$country_id=0;

	$address_data=array(
		'address1'=>$address1,
		'address2'=>$address2,
		'address3'=>$address3,
		'town_d2'=>$town_d2,
		'town_d1'=>$town_d1,
		'town'=>$town,
		'country_d2'=>$country_d2,
		'country_d1'=>$country_d1,
		'postcode'=>$postcode,
		'country'=>$country,
		'town_d2_id'=>$town_d2_id,
		'town_d1_id'=>$town_d1_id,
		'town_id'=>$town_id,
		'country_d2_id'=>$country_d2_id,
		'country_d1_id'=>$country_d1_id,
		'country'=>$country_id,

	);

	return $address_data;
}


function ci_act_transformations($act_data) {



	$act_data['name']=preg_replace('/\\\"/i',' ',$act_data['name']);
	$act_data['contact']=preg_replace('/\\\"/i',' ',$act_data['contact']);


	if ($act_data['name']=='Eujopa.s.l') {
		$act_data['name']='Eujopa S.L.';
	}
	if ($act_data['name']=='S. coop. mad. Los Apisquillos') {
		$act_data['name']='S. Coop. Mad. Los Apisquillos';
	}

	//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	// fix contracts
	if ($act_data['name']=='Antonio Laborda - Jabón Jabón') {
		$act_data['name']='Jabón Jabón';
		$act_data['contact']='Antonio Laborda';
	}



	if ($act_data['country']=='') {


		if (preg_match('/spain\s*.\s*ibiza/i',$act_data['postcode'])) {
			$act_data['country']='Spain';
			$act_data['postcode']='';
			$act_data['country_d1']='Balearic Islands';
			$act_data['country_d2']='Balearic Islands';
		}



		$tmp_array=preg_split('/\s+/',$act_data['postcode']) ;

		if (count($tmp_array)==2) {
			$sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($tmp_array[0]),prepare_mysql($tmp_array[0]));


			$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$act_data['country']=$row['name'];
				$act_data['postcode']=$tmp_array[1];
			}

			$sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s ",prepare_mysql($tmp_array[1]),prepare_mysql($tmp_array[1]));


			$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$act_data['country']=$row['name'];
				$act_data['postcode']=$tmp_array[0];
			}
		}
		elseif (count($tmp_array)==1) {
			$sql=sprintf("select `Country Name` as name from kbase.`Country Dimension` left join kbase.`Country Alias Dimension` on  (`Country Alias Code`=`Country Code`) where `Country Alias`=%s or `Country Name`=%s",prepare_mysql($tmp_array[0]),prepare_mysql($tmp_array[0]));


			$result = mysql_query($sql) or die('Query failed: ' . mysql_error());
			if ($row = mysql_fetch_array($result, MYSQL_ASSOC)) {
				$act_data['country']=$row['name'];
				$act_data['postcode']='';
			}


		}
	} else {
		//    print_r($act_data);
		if (strtolower(_trim($act_data['country']))==strtolower(_trim($act_data['postcode'])))
			$act_data['postcode']='';
	}





	if ($act_data['postcode']=="" and preg_match('/\s*\d{4,6}\s*/i',$act_data['town'],$match)) {

		if ($act_data['country']!="Netherlands") {
			$act_data['postcode']=_trim($match[0]);
			$act_data['town']=preg_replace('/\s*\d{4,6}\s*/','',$act_data['town']);
		}
	}


	if ($act_data['a2']=='Ascheffel') {
		$act_data['town']='Ascheffel';
		$act_data['a2']='';
	}



	if (preg_match('/alicante/i',$act_data['country_d2']) and $act_data['country']==''  ) {
		$act_data['country']='Spain';
		$act_data['country_d1']='Valencia';
	}

	if (preg_match('/Alfaz del Pi - Alicante/i',$act_data['town'])  ) {
		$act_data['town']='Alfaz del Pi';
		$act_data['country_d2']='Alicante';
		$act_data['country_d1']='Valencia';
	}





	if (preg_match('/Viterbo/i',$act_data['town'])  and preg_match('/Soriano Nel Cimino/i',$act_data['a2']) ) {
		$act_data['country']='Italy';
		$act_data['town']='Soriano Nel Cimino';
		$act_data['country_d1']='Lazio';
		$act_data['country_d2']='Viterbo';
		$act_data['a2']='';
		$act_data['postcode']='01028';
	}



	if (preg_match('/^granada$/i',$act_data['town']) and $act_data['country']=='') {
		$act_data['country']='Spain';
	}




	if ($act_data['name']=='La Tasca de Oscar' and $act_data['contact']=='') {
		$act_data['contact']='Rosa Amelia Fariña Rodriguez';

	}


	if ($act_data['name']=='Aventura2007s.c.p') {
		$act_data['name']='Aventura2007 S.C.P';
	}
	if ($act_data['contact']=='Aventura2007s.c.p') {
		$act_data['contact']='Aventura2007 S.C.P';
	}


	if ($act_data['name']=='0neida Beceira') {
		$act_data['name']='Oneida Beceira';
	}
	if ($act_data['contact']=='0neida Beceira') {
		$act_data['contact']='Oneida Beceira';
	}


	if ($act_data['name']=='Kalamazad A') {
		$act_data['name']='A Kalamazad';
	}
	if ($act_data['contact']=='Kalamazad A') {
		$act_data['contact']='A Kalamazad';
	}




	if ($act_data['name']=='0rganiza del principado S.L.') {
		$act_data['name']='Organiza del principado S.L.';
	}





	if ($act_data['name']=='Encarnación Jimenez Marquez' and $act_data['contact']=='0') {
		$act_data['name']='Encarnación Jimenez Marquez';
		$act_data['contact']='Encarnación Jimenez Marquez';
	}


	if (
		($act_data['name']=='Virginia Cabrera Rivera' and $act_data['contact']=='David GTX')
		or ($act_data['name']=='Marisa Gómez' and $act_data['contact']=='Naturalmente')
		or ($act_data['name']=='Ignacio Galán Olaizola' and $act_data['contact']=='Mandala')
		or ($act_data['name']=='Sandra Romay Naixes' and $act_data['contact']=='Tribu´s')
		or ($act_data['name']=='Soledad Martin Santos' and $act_data['contact']=='Tu Luz')
		or ($act_data['name']=='Mari Carmen de La Muela Vega' and $act_data['contact']=='Joyeria Caro')
		or ($act_data['name']=='Aniceto de Leon Viñoly' and $act_data['contact']=='La Caja Roja')
		or ($act_data['name']=='Mº del Carmen López Carreira' and $act_data['contact']=='Eiroa-2')
		or ($act_data['name']=='Fermin Gutierrez' and $act_data['contact']=='Fermin')

		or ($act_data['name']=='Sonsoles Luque Delgado' and $act_data['contact']=='Isla Web')
		or ($act_data['name']=='Mercedes Manito Mantero' and $act_data['contact']=='Mercedes')

		or ($act_data['name']=='Adriana Ramos Ruiz' and $act_data['contact']=='Jaboneria')
		or ($act_data['name']=='Francisca Castillo Gil' and $act_data['contact']=='Bis a Bis')
		or ($act_data['name']=='Judit Plana Rodriguez' and $act_data['contact']=='Solluna')
		or ($act_data['name']=='Sylvie Felten' and $act_data['contact']=='Aparte')
		or ($act_data['name']=='Rosa Maria Moraleda Sanchez' and $act_data['contact']=='Miro 13')
		or ($act_data['name']=='Alberto Markuerkiaga Santiso' and $act_data['contact']=='Dra!')
		or ($act_data['name']=='Susana Rodriguez Lozano' and $act_data['contact']=='Mr Ayudas')
		or ($act_data['name']=='Juan Carlos Mirabal' and $act_data['contact']=='C')
		or ($act_data['name']=='Laudelina Saavedra Montesdeoca' and $act_data['contact']=='Herbolario Aguamar')
		or (preg_match('/Gina Younis Hevia/i',$act_data['name'])  and preg_match('/Gong Marbella/i',$act_data['contact']) )
		or (preg_match('/Maria Josefa Aparicio Arrebol/i',$act_data['name'])  and preg_match('/Duna/i',$act_data['contact']) )
		or (preg_match('/Marisa R/i',$act_data['name'])  and preg_match('/Ilusiones/i',$act_data['contact']) )
		or (preg_match('/Burgui/i',$act_data['name'])  and preg_match('/Burbuja/i',$act_data['contact']) )
		or (preg_match('/teteria|Herbolario|Perfumeria|Jauja|Herboristeria|El Rincon del Papi|Commercial Fermer.n|Ochun y Yemaya S.C.P.|Pompitas de |Artesan(í|i)a|Esoterico?|Craft Market|Artterapia|Centro De Estetica|Artesano Grabador de Vidrio|Psicolodia Logopedia Montserrat Baulenas|Centro Tiempo Crista|Mais Festa|Pompas de Jab.n|Q.guay\!/i',$act_data['contact']) )
		or (preg_match('/^Asociaci.n |^tienda |joyeria |Papeleria|^bazar|^restaurant|^el |^las |^los |^la /i',$act_data['contact']) )
		or (preg_match('/^(rayas|papel|Artesano|Gipp|La Mar de Cosas|Jabón Jabón|Angelus|Pompas|Jaboneria|Arfin|Samadhi|Zig Zag|Style|Salem|Videotarot|El duende|Sensual|Ariestética|Burbujitas|Chucotattoo|La Misma|D.e|Dunes|Dulce Pina|Naturshop|Amanatur S L|Lady Of the Stones|Splash|Fragancias|Lima Limon)$/i',$act_data['contact']) )
		or (preg_match('/^Mª /i',$act_data['name']) and  $act_data['contact']!='')
		// or (preg_match('//i',$act_data['name'])  and preg_match('//i',$act_data['contact']) )
		//or ($act_data['name']=='' and $act_data['contact']=='')
	) {
		$_tmp=$act_data['name'];
		$act_data['name']=$act_data['contact'];
		$act_data['contact']=$_tmp;
	}








	$extra_contact=false;
	if ($act_data['contact']!='') {

		$_contact=$act_data['contact'];
		$split_names=preg_split('/\s+and\s+|\&|\/|\s+or\s+/i',$act_data['contact']);
		if (count($split_names)==2) {
			$split_names1=preg_split('/\s+/i',$split_names[0]);
			$split_names2=preg_split('/\s+/i',$split_names[1]);
			if (count($split_names1)==1 and count($split_names2)==2 ) {
				$name1=$split_names1[0].' '.$split_names2[1];
				$name2=$split_names[1];
			} else {
				$name1=$split_names[0];
				$name2=$split_names[1];
			}
			$act_data['contact']=$name1;
			$extra_contact=$name2;
			if ($_contact==$act_data['name']) {
				$act_data['name']=preg_replace('/\s+and\s+|\&|\/|\s+or\s+/i',' & ',$act_data['name']);
			}

		}
		$there_is_contact=true;
	} else {
		$there_is_contact=false;
		if (!preg_match('/C \& P Trading|Peter \& Paul Ltd|Health.*Beauty.*Salon|plant.*herb|Amanatur S L/i',$act_data['name']))
			$act_data['contact']=$act_data['name'];
		if (!preg_match('/^(pompas)$/i',$act_data['name']))
			$act_data['contact']=$act_data['name'];



	}






	if ($act_data['name']=='Jill Clare' and  $act_data['contact']=='Jill Clare') {
		$tipo_customer='Company';
		$act_data['contact']='';
	}





	$tmp_array=array('Burbujas Online S.L.','Sona Florida S.L.L.','Fisioglobal SCP','Naturshop','Amanatur S L');
	foreach ($tmp_array as $__name) {
		if ($act_data['name']==$__name and $act_data['contact']==$__name  ) {
			$tipo_customer='Company';
			$act_data['contact']='';

		}
	}






	$act_data['name']=preg_replace('/^m\.angeles /i','Mª Angeles ',$act_data['name']);

	$act_data['contact']=preg_replace('/^m\.angeles /i','Mª Angeles ',$act_data['contact']);


	$act_data['name']=preg_replace('/,? (S\s*L\.|S\.L\.|S\s*\.\s*L|SL)$/i',' S.L.',$act_data['name']);
	$act_data['name']=preg_replace('/\,? (Slu)$/i',' S.L.U.',$act_data['name']);
	// $act_data['name']=preg_replace('/\,? (Slu)$/i',' S.L.U.',$act_data['name']);

	$act_data['name']=preg_replace('/ (S\s*C\.|S\.C\.|S\.C|SC)$/i',' S.C.',$act_data['name']);
	$act_data['name']=preg_replace('/ (s\.L\s*L|SLL|S\s*L\.L\.|S\.L\.L\.|S\.LL)$/i',' S.L.L.',$act_data['name']);
	$act_data['name']=preg_replace('/ (S\s*a\.|S\.a\.|S\.a|Sa|s\.a)$/i',' S.A.',$act_data['name']);
	$act_data['name']=preg_replace('/ (C\s*B\.|C\.B\.|C\.B|CB)$/i',' C.B.',$act_data['name']);
	$act_data['name']=preg_replace('/,\s*(C\s*B\.|C\.B\.|C\.B|CB)$/i',' C.B.',$act_data['name']);
	$act_data['name']=preg_replace('/ (-?\s*L\.da|LDA|l\.d\.a)$/i',' L.D.A.',$act_data['name']);
	$act_data['name']=preg_replace('/,\s*(-?\s*L\.da|LDA|l\.d\.a)$/i',' L.D.A.',$act_data['name']);
	$act_data['name']=preg_replace('/ (s\.?\s*c\.?\s*p)$/i',' S.C.P.',$act_data['name']);
	$act_data['name']=preg_replace('/ S.l.n.e$/i',' S.L.N.E.',$act_data['name']);
	$act_data['name']=preg_replace('/ S\.?l\.?u\.?$/i',' S.L.U.',$act_data['name']);


	if ($act_data['name']==$act_data['contact'] and $act_data['contact']!='') {
		if (preg_match('/^Bazar |^Alta Bisuteria | shop$|^Perfumer.a |Sociedad Cooperativa|souvenirs|^supermercados |^bisuteria | hoteles?$|^hotels? |^eventos |^terra |Avenue de |\d|^equilibrio |^la estrella |^verde |complementos |^joyeria |^regalos |bisiter.a|est.tica|peluquer.a|yoga |el zoco|jabones|S\.L\.$|Ldª$| SL$|Herboristeria|Asoc\. |^Asociaci.n |^Centro |^FPH C\.B\.$|Fisioglobal|^Amigos de | S\.A\.$|Associació Cultural|Associaci.n Cultural| C\.B$|^Asociación [a-z]+$| S\.A\.$| S\.C\.?$|Sucrolotes SLL - La Guinda| C\.B\.?$|lenilunio S\.c\.a$|^Laboratorios |Burbujas Castellón|^Rama SC$| S\.L\.?$| S\.l\.n\.e\.?$| s\.c\.a\.?$|Tecnologias|^Papeleria | S\.L\.U\.$| L\.D\.A\.$| C\.B\.$| S\.L\.L\.$/i',$act_data['name'])) {
			$act_data['contact']='';

		}
		elseif (preg_match('/^(centro)\s+|Publicidad/i',$act_data['name'])) {
			$act_data['contact']='';
		}
		elseif (preg_match('/^(Fantasía.S|Neurona.S|Carisma|Trin-Tran|Turquesa|Tulsa|Txibiritak|Tza|N Ude|Vakaloka|Valle-Villa|vimes|xl|xena|waza|Terranova|Tierra|Tigal|Timanfaya|Sun Time|Tinasty|Trabalú|Treal|Traum|Fgdf|Tramuntana|Damco Trading|poeme|Populi|Minerales Porto Pi|Servi Print|Prince|Prysma|Carros Publicidad|Objetivo Publicidad|Publiexpress|Puerimueble|Plata Punto Com|puri|Que Punto|Expo Regalo|Expo Regalo|Don Regalo|Scruples|Scruples|seducir|Si Tu Me Dices Ven |Sol y Sol|sp|spiral|Britt-Inger St|Sthmuck|star|Dream Store|Struch|stylo|Sueños|Sunmarine|Supercien|Mai Tai|tayhe|tagore|tamy|tanisa|tauros?|aries|capricornio|Tayhe|Modas Teis|Temporada|Tendencia|they|La Tienda de Merche|Artemaniashop|arrumaco|Bolsos Arpel|arrels|Electro Aroche|Aroa y Maria del Mar|Armonia|Arlequin|Tele Arcos|archi|arco|Arantxa Bisuteria, Regalos y Complementos|Antiquo|Alhambra|Albutt|Alanb|Elemento Agua|Aguamarina|Acuario|Africa|Acuario|Acuarela|Accessorize|Accessoris|Molts Accesoris|Aires De Mexico|Al Tuntun|Al Tun Tun|Laboladecristal|Gretel|Garcivera|S Espay|Ambar Diseño|Concha y Carlos|amina|Amica|America|Ameica|ambar|Amas de Casa Virgen del Carmen |Altieri|Alternativa|Alquimia|signa|Shiam|Singular|Sol y Luna - La Tienda de Mayca|Soyzoe|Splin|Spleen|Etetica Suvita|para ti|thot|tgoreti|el tintero|la tinaja|de todo|top|toke|etnia|a tope|topaz|toque|Un Toque de Estilo|tosca|tasca|toten|totem|touch|Abalorios Trini|La Traperia de Hellin|utop.a|venus|verdi|Art I vi|tigre volador|Walkiria|Waleska|Watermelon|Xarxa|Xaica|Xacris|Whatever|Waza|HM Woman|Interbisu Xxi|Yoryera|zeppo|yerba|yesi|zeida|zaguan|azahar|zaloa|zaleos|yuca|zurron|Fengzhu Zhu|Zidarra|De Zeta|)$/i',$act_data['name'])) {
			$act_data['contact']='';
		}
		elseif (preg_match('/^(la|el|los|las|spa|tele|Bisuter.a|Accesorios) /i',$act_data['name'])) {
			$act_data['contact']='';
		}
		elseif (preg_match('/^Bisuter/i',$act_data['name'])) {
			$act_data['contact']='';
		}
		else {


			$_tmp=preg_split('/\s*/',$act_data['name']);
			if (count($_tmp)==1) {
				if (!Contact::is_surname($act_data['name']) and !Contact::is_givenname($act_data['name'])   )
					$act_data['contact']='';
			}



		}

	}



	//  print_r($act_data);

	// print_r($header_data);

	//-----------------------------------------
	if (!isset($act_data['town_d1']))
		$act_data['town_d1']='';
	if (!isset($act_data['town_d2']))
		$act_data['town_d2']='';

	if (preg_match('/^c\/o/i',$act_data['a1'])) {
		$co=$act_data['a1'];
		$act_data['a1']='';
	}
	if (preg_match('/^c\/o/i',$act_data['a2'])) {
		$co=$act_data['a2'];
		$act_data['a2']='';
	}
	if (preg_match('/^c\/o/i',$act_data['a3'])) {
		$co=$act_data['a3'];
		$act_data['a3']='';
	}



	return $act_data;
}


function parse_tax_number($tax_number) {

	$tax_number=_trim($tax_number);
	if (!$tax_number or $tax_number=='no vat' or $tax_number=='5 / 10' or preg_match('/@|PLS CHARGE ORDER BEFORE PICKING|not VAT registered|Monday|Tuesday|not registered/i',$tax_number))
		return '';



	// print "--->$tax_number<-\n";





	$tax_number=preg_replace('/tax id\s*:?\s*-?\s*/i','',$tax_number);
	$tax_number=preg_replace('/V\.a\.t\. N.*:\s*-?\s*/i','',$tax_number);

	$tax_number=preg_replace('/VAT NO\s*-\s*/i','',$tax_number);
	$tax_number=preg_replace('/^VAT No\.\:\s*/i','',$tax_number);
	$tax_number=preg_replace('/^vat no\s*(\.|:)?\s*/i','',$tax_number);
	$tax_number=preg_replace('/^vat\s*(\:|\-)?\s*/i','',$tax_number);
	$tax_number=preg_replace('/^vat\s*reg\*(\:|\-)?\s*/i','',$tax_number);
	$tax_number=preg_replace('/\-?\s*Checked and Valid$/i','',$tax_number);
	$tax_number=preg_replace('/\-?\s*valid and checked$/i','',$tax_number);

	$tax_number=preg_replace('/Customer |SA VAT NO |tva:\s*|TVA Intracom : /i','',$tax_number);
	$tax_number=preg_replace('/^tva fr/i','FR',$tax_number);


	$tax_number=preg_replace('/tax\s*:?\s*/i','',$tax_number);
	$tax_number=preg_replace('/not$/i','',$tax_number);

	$tax_number=preg_replace('/\-?\s*ok$/i','',$tax_number);
	$tax_number=preg_replace('/\-?\s*checked$/i','',$tax_number);
	$tax_number=preg_replace('/\s*ckecked$/i','',$tax_number);
	$tax_number=preg_replace('/\-?\s*checked\s+valid\.?$/i','',$tax_number);
	$tax_number=preg_replace('/\s*\-?\s*valid$/i','',$tax_number);
	$tax_number=preg_replace('/\s*\-?\s*verified$/i','',$tax_number);
	$tax_number=preg_replace('/\s*\-?\s*Checked\s*\!{0,5}$/i','',$tax_number);
	$tax_number=preg_replace('/\-?\s*\(checked\)$/i','',$tax_number);
	$tax_number=preg_replace('/\-?\s*\(check ok\)$/i','',$tax_number);
	$tax_number=preg_replace('/\-?\s*valid\s*\(HM\)$/i','',$tax_number);
	$tax_number=preg_replace('/\-?\s*checked by customs$/i','',$tax_number);
	$tax_number=preg_replace('/\s*\-\s*$/i','',$tax_number);
	$tax_number=preg_replace('/( - Valid by customs|need to)$/i','',$tax_number);

	if (preg_match('/EL137399039 checkedEL-137399039/i',$tax_number))
		$tax_number='EL137399039';
	if (preg_match('/PT:503958271, validPT-503958271/i',$tax_number))
		$tax_number='PT-503958271';
	if (preg_match('/NL060484305B02 validNL060484305B02 valid/i',$tax_number))
		$tax_number='NL060484305B02';
	if (preg_match('/^IE : 3756781C$/i',$tax_number))
		$tax_number='IE3756781C';





	if (preg_match('/^(es|cz|sk|si|se|ro|pl|mt|lv|lu|lt|gb|be|bg|ee|el|fi|cy|nl|mt|be|atu|hu|it|se|de|at|ch|dk|pt|fr|ie)(\s|\-|\d|\:)/i',$tax_number)) {
		$number=preg_replace('/^\s*(\-|\:)?\s*/','',substr($tax_number,2));
		$tax_number=strtoupper(substr($tax_number,0,2)).' '.$number;

	}


	if (preg_match('/^(es\s*x)/i',$tax_number)) {
		$number=preg_replace('/^\s*(\-|\:)?\s*/','',substr($tax_number,2));
		$tax_number='ES '.$number;

	}




	$tax_number=_trim($tax_number);

	return $tax_number;

}


function is_person($name) {
	$company_suffix="L\.?T\.?D\.?";
	$company_prefix="The";
	$company_words=array('Gifts','Chemist','Pharmacy','Company','Business','Associates','Enterprises','hotel','shop','aromatheraphy');
	$name=_trim($name);
	$probability=1;
	if (preg_match('/\d/',$name)) {
		$probability*=0.00001;
	}
	if (preg_match("/\s+".$company_suffix."$/",$name)) {
		$probability*=0.001;
	}
	if (preg_match("/\s+".$company_prefix."$/",$name)) {
		$probability*=0.001;
	}
	// print_r($company_words);
	foreach ($company_words as $word) {
		if (preg_match("/\b".$word."\b/i",$name)) {
			$probability*=0.01;
		}
	}




	if ($probability>1)$probability=1;
	return $probability;

}


function is_company($name,$locale='en_GB') {

	$name=_trim($name);
	//global $person_prefix;
	$probability=1;


	if ($locale='en_GB') {
		$person_prefixes=array("Mr","Miss","Ms");
		$common_company_suffixes=array("L\.?t\.?d\.?");
		$common_company_prefixes=array("the");

		$common_company_compoments=array("HQ","Limited");
	} else {
		$person_prefixes=array();
		$common_company_suffixes=array();
		$common_company_prefixes=array();

		$common_company_compoments=array();

	}

	foreach ($common_company_prefixes as $company_prefix) {
		if (preg_match("/^".$company_prefix."\s+/i",$name)) {
			$probability*=10;
			break;
		}
	}

	foreach ($common_company_suffixes as $company_suffix) {
		if (preg_match("/\s+".$company_suffix."$/i",$name)) {
			$probability*=10;
			break;
		}
	}


	foreach ($person_prefixes as $person_prefix) {
		if (preg_match("/^".$person_prefix."\s+/i",$name)) {
			$probability*=0.01;
		}
	}

	$components=preg_split('/\s/',$name);


	if (count($components)>1) {
		$has_sal=false;
		$saludation=preg_replace('/\./','',$components[0]);
		$sql=sprintf('select `Salutation Key` from kbase.`Salutation Dimension` where `Salutation`=%s  ',prepare_mysql($saludation));
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$probability*=0.9;
		}



	}



	if (count($components)==2) {
		$name_ok=false;
		$surname_ok=false;
		$sql=sprintf('select `First Name Key` from kbase.`First Name Dimension` where `First Name`=%s  ',prepare_mysql($components[0]));
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$name_ok=true;
		}
		$sql=sprintf('select `Surname Key` from kbase.`Surname Dimension` where `Surname`=%s  ',prepare_mysql($components[1]));
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$surname_ok=true;
		}
		if ($surname_ok and $name_ok) {
			$probability*=0.75;
		}
		if ($name_ok) {
			$probability*=0.95;
		}
		if ($surname_ok) {
			$probability*=0.95;
		}

		if (strlen($components[0])==1) {
			$probability*=0.95;
		}



	}
	elseif (count($components)==3) {

		$name_ok=false;
		$surname_ok=false;
		$sql=sprintf('select `First Name Key` from kbase.`First Name Dimension` where `First Name`=%s  ',prepare_mysql($components[0]));
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$name_ok=true;
		}
		$sql=sprintf('select `Surname Key` from kbase.`Surname Dimension` where `Surname`=%s  ',prepare_mysql($components[2]));
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res)) {
			$surname_ok=true;
		}
		if ($surname_ok and $name_ok) {
			$probability*=0.75;
		}
		if ($name_ok) {
			$probability*=0.95;
		}
		if ($surname_ok) {
			$probability*=0.95;
		}

		if (strlen($components[1])==1) {
			$probability*=0.95;
		}

		if (strlen($components[1])==1 and strlen($components[0])==1 ) {
			$probability*=0.99;
		}

	}

	if ($probability>1)$probability=1;

	return $probability;
}
function parse_company_person($posible_company_name,$posible_contact_name) {
	$company_name=$posible_company_name;
	$contact_name=$posible_contact_name;
	$person_person_factor=0;
	$person_company_factor=0;
	if ($posible_company_name!='' and $posible_contact_name!='') {
		$tipo_customer='Company';
		if ($posible_company_name==$posible_contact_name ) {
			$person_factor=is_person($posible_company_name);
			$company_factor=is_company($posible_company_name);
			if ($company_factor>$person_factor) {
				$tipo_customer='Company';
				$contact_name='';


			} else {
				$tipo_customer='Person';
				$company_name='';
			}

		} else {
			$company_person_factor=is_person($posible_company_name)+0.00001;
			$company_company_factor=is_company($posible_company_name)+0.00001;
			$person_company_factor=is_company($posible_contact_name)+0.00001;
			$person_person_factor=is_person($posible_contact_name)+0.00001;



			$company_ratio=$company_company_factor/$company_person_factor;
			$person_ratio=$person_person_factor/$person_company_factor;

			$ratio=($company_ratio+$person_ratio)/2;

			//print "** $company_ratio $person_ratio\n";

			if ($ratio<0.4)
				$swap=true;
			else
				$swap=false;



			if ($swap) {
				$_name=$posible_company_name;
				$company_name=$posible_contact_name;
				$contact_name=$_name;
			}



		}


	}
	elseif ($posible_company_name!='') {
		$tipo_customer='Company';
		$company_person_factor=is_person($posible_company_name);
		$company_company_factor=is_company($posible_company_name);

		if ( $company_person_factor>$company_company_factor) {
			$tipo_customer='Person';
			$_name=$posible_company_name;
			$company_name=$posible_contact_name;
			$contact_name=$_name;
		}


	}
	elseif ($posible_contact_name!='') {
		$tipo_customer='Person';
		$person_company_factor=is_company($posible_contact_name);
		$person_person_factor=is_person($posible_contact_name);

		if ($person_company_factor>$person_person_factor ) {
			$tipo_customer='Company';
			$_name=$posible_company_name;
			$company_name=$posible_contact_name;
			$contact_name=$_name;
		}


	}
	else {
		$tipo_customer='Person';

	}
	/*
    printf("Name: %s  ; Company: %s  \n is company a person %f is company a company %f\n is paerson a comapny %f  is person a person%f  \n$tipo_customer,\nName: $contact_name\nCompany:$company_name\n",
        $posible_contact_name,
            $posible_company_name,

     $company_person_factor,
                $company_company_factor,
                $person_company_factor,
                $person_person_factor



    );
    */
	return array($tipo_customer,$company_name,$contact_name);



}

function is_shipping_supplier($data) {
	global $editor;

	//  if(preg_match('/^(per post|Pacel Force|Airmail|Amtrak.*|1st Class Post|Amstrak|via Frans Maas|Fist class post|DBL|apc|post|interlink|parcel\s*force|ups|fedex|royal\s*mail|by post|printmovers|1st class|first class|frans mass|frans maas|apc to collect|post . standart parcel|post . 2 parcels.*|post office|schenker|parcel force worldwide|amtrak|percel porce|parceline|post 1st|dfds transport|dpd|dbl pallet|tnt|interlink\s*express?|amtrack|post 1at class|post \- sing for|dvs|.*royal mail.*|Parce Force|Parcel Force Wordwide|Roayl Mail|Post 1st Class|Parcel Line|dbl|POST SIGNED FOR|Parcelforce.*|AMRAK|Post Sign For|post .*|FedEx .*|dbl|Parcel Force .*|DSV pallet|Hastings Freight|Amtrak|apc .*|dpd .*|dbl|Parcel F orce Sat Del|Mc Grath Freigth|Parcel Porce)$/i',_trim($data['notes'])))
	if (preg_match('/^(Use his own shipper|Cust owns carrier|cust own courier|customer own carrier|customer own carrier|own transport|Own Courrier|own driver)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='_OWN';
	}
	elseif (preg_match('/^(Via Post Office|Send By Post|First Class Post|Sent by post|Royalmail.*|per post|Airmail|1st Class Post|Fist class post|post|royal\s*mail|by post|1st class|first class|post . standart parcel|post . 2 parcels.*|post office|post 1st|post 1at class|post \- sing for|.*royal mail.*|Roayl Mail|Post 1st Class|POST SIGNED FOR|Post Sign For|post .*)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='RoyalM';
	}
	elseif (preg_match('/^(ParcelForcel|Parcelforce.*|PacelForce|Parcel Force.*|Parcel Forcce|Pacel Force|parcel\s*force|parcel force worldwide|percel porce|Parce Force|Parcel Force Wordwide|Parcel F orce Sat Del|Parcel Porce|parcel force.*)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='PForce';
	}
	elseif (preg_match('/^(DSV.*|frans maas|frans mass|via Frans Maas)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='DSV';
	}
	elseif (preg_match('/^(Amtrac|Amstrak|Amtrak.*|amtrak|amtrack|AMRAK)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='DSV';
	}
	elseif (preg_match('/^(dpd|parcel line|dpd .*|Parceline)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='DPD';
	}
	elseif (preg_match('/^(interlink.*)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='ILink';
	}
	elseif (preg_match('/^(tnt)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='TNT';
	}
	elseif (preg_match('/^(fedex.*)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='Fedex';
	}
	elseif (preg_match('/^(ups)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='UPS';
	}
	elseif (preg_match('/^(dfds.*)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='DFDS';
	}
	elseif (preg_match('/^(Mc Grath Freigth)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='McGF';
	}
	elseif (preg_match('/^(Hastings Freight)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='HastFre';
	}
	elseif (preg_match('/^(apc|apc .*)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='APC';
	}
	elseif (preg_match('/^(dbl|dbl .*)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='APC';
	}
	elseif (preg_match('/^(Future Fowarding)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='FutFo';
	}
	elseif (preg_match('/^(Printmovers)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='PrintM';
	}
	elseif (preg_match('/^(Schenker)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['shipper_code']='Schenker';
	}
	elseif (preg_match('/^(shang|andy|andy to take( tomorrow)?|to be deliv. by Neil|Give to Malcom)$/i',_trim($data['notes']))) {

		$data['shipper_code']='_Other';
	}



	if (preg_match('/^(Use his own shipper|Cust owns carrier|cust own courier|customer own carrier|customer own carrier|own transport|Own Courrier|own driver)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='_OWN';
	}
	elseif (preg_match('/^(Via Post Office|Send By Post|First Class Post|Sent by post|Royalmail.*|per post|Airmail|1st Class Post|Fist class post|post|royal\s*mail|by post|1st class|first class|post . standart parcel|post . 2 parcels.*|post office|post 1st|post 1at class|post \- sing for|.*royal mail.*|Roayl Mail|Post 1st Class|POST SIGNED FOR|Post Sign For|post .*)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='RoyalM';
	}
	elseif (preg_match('/^(ParcelForcel|Parcelforce.*|PacelForce|Parcel Force.*|Parcel Forcce|Pacel Force|parcel\s*force|parcel force worldwide|percel porce|Parce Force|Parcel Force Wordwide|Parcel F orce Sat Del|Parcel Porce|parcel force.*)$/i',_trim($data['notes2']))) {
		// exit("s".$data['notes2']."xxxxxxx");
		$data['notes2']='';
		$data['shipper_code']='PForce';
	}
	elseif (preg_match('/^(DSV.*|frans maas|frans mass|via Frans Maas)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='DSV';
	}
	elseif (preg_match('/^(Amtrac|Amstrak|Amtrak.*|amtrak|amtrack|AMRAK)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='DSV';
	}
	elseif (preg_match('/^(dpd|parcel line|dpd .*|Parceline)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='DPD';
	}
	elseif (preg_match('/^(interlink.*)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='ILink';
	}
	elseif (preg_match('/^(tnt)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='TNT';
	}
	elseif (preg_match('/^(fedex.*)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='Fedex';
	}
	elseif (preg_match('/^(ups)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='UPS';
	}
	elseif (preg_match('/^(dfds.*)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='DFDS';
	}
	elseif (preg_match('/^(Mc Grath Freigth)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='McGF';
	}
	elseif (preg_match('/^(Hastings Freight)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='HastFre';
	}
	elseif (preg_match('/^(apc|apc .*)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='APC';
	}
	elseif (preg_match('/^(dbl|dbl .*)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='APC';
	}
	elseif (preg_match('/^(Future Fowarding)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='FutFo';
	}
	elseif (preg_match('/^(Printmovers)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='PrintM';
	}
	elseif (preg_match('/^(Schenker)$/i',_trim($data['notes2']))) {
		$data['notes2']='';
		$data['shipper_code']='Schenker';
	}
	elseif (preg_match('/^(shang|andy|andy to take( tomorrow)?|to be deliv. by Neil|Give to Malcom)$/i',_trim($data['notes2']))) {

		$data['shipper_code']='_Other';
	}












	$the_supplier_data=array(
		'editor'=>$editor
		,'Supplier Name'=>$data['shipper_code']
		,'Supplier Code'=>$data['shipper_code']
	);
	if ($data['shipper_code']!='' and $data['shipper_code']!='_OWN' and $data['shipper_code']!='_Other') {

		//print $data['shipper_code']."<---\n";
		$supplier=new Supplier('code',$data['shipper_code']);
		if (!$supplier->id) {

			$supplier=new Supplier('find',$the_supplier_data,'create');
		}
		//exit;
	}
	return $data;


}


?>
