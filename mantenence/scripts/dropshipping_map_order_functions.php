<?php


function parse_payment_method($method) {


	$method=_trim($method);
	//  print "$method\n";
	if ($method=='' or $method=='0')
		return 0;
	if (preg_match('/^(Card Credit|credit  card|Debit card|Crredit Card|Credit Card|Solo|Cr Card|Switch|visa|electron|mastercard|card|credit Card0|Visa Electron|Credi Card|Credit crad)$/i',$method))
		return 'Credit Card';

	//  print "$method\n";
	if (preg_match('/^(Cheque receiv.|APC|\*Cheque on Delivery\s*|Cheque|APC to Collect|chq|PD CHQ|APC collect CHQ|APC to coll CHQ|APC collect cheque)$/i',$method))
		return 'Check';
	if (preg_match('/^(Account|7 Day A.C|Pay into a.c|pay into account)$/i',$method))
		return 'Other';
	if (preg_match('/^(cash|casg|casn)$/i',$method))
		return 'Cash';
	if (preg_match('/^(Paypal|paypall|pay pal)$/i',$method))
		return 'Paypal';
	if (preg_match('/^(bacs|Bank Transfer|Bank Transfert|Direct Bank)$/i',$method))
		return 'Bank Transfer';
	if (preg_match('/^(draft|bank draft|bankers draft)$/i',$method))
		return 'Other';
	if (preg_match('/^(postal order)$/i',$method))
		return 'Other';
	if (preg_match('/^(Moneybookers)$/i',$method))
		return 'Other';


	return 'Unknown';

}
function get_payment_method($method) {


	$method=_trim($method);
	//  print "$method\n";
	if ($method=='' or $method=='0')
		return 0;
	if (preg_match('/^(Card Credit|credit  card|Debit card|Crredit Card|Credit Card|Solo|Cr Card|Switch|visa|electron|mastercard|card|credit Card0|Visa Electron|Credi Card|Credit crad)$/i',$method))
		return 2;

	//  print "$method\n";
	if (preg_match('/^(Cheque receiv.|APC|\*Cheque on Delivery\s*|Cheque|APC to Collect|chq|PD CHQ|APC collect CHQ|APC to coll CHQ|APC collect cheque)$/i',$method))
		return 4;
	if (preg_match('/^(Account|7 Day A.C|Pay into a.c|pay into account)$/i',$method))
		return 5;
	if (preg_match('/^(cash|casg|casn)$/i',$method))
		return 1;
	if (preg_match('/^(Paypal|paypall|pay pal)$/i',$method))
		return 6;
	if (preg_match('/^(bacs|Bank Transfer|Bank Transfert|Direct Bank)$/i',$method))
		return 3;
	if (preg_match('/^(draft|bank draft|bankers draft)$/i',$method))
		return 7;
	if (preg_match('/^(postal order)$/i',$method))
		return 8;
	if (preg_match('/^(Moneybookers)$/i',$method))
		return 9;

	print "Warning: unnkown pay method $method \n";
	return 0;

}


function get_tax_number($data) {
	global $myconf;
	$data['tax_number']='';
	$note='';

	if (!$data['dn_country_code'] or $data['dn_country_code']=='0')
		$data['dn_country_code']='';
	//print $data['dn_country_code']."xxx";
	if (
		(in_array(strtoupper($data['dn_country_code']),$myconf['tax_conditional0_2acode'])  and ($data['tax1']==0 or $data['tax1']=='' or !$data['tax1'])  and $data['notes2']!='' )
		or ($data['dn_country_code']=='' and ($data['tax1']==0 or $data['tax1']=='' or !$data['tax1']) and $data['notes2']!=''  )
	) {



		if (preg_match('/CUSTOMER VAT 75732 Company : 680602=4840/i',$data['notes2'])) {

			$note='';
			$tax_number='680602-4840';

		}else {


			$tax_number=$data['notes2'];
			$regex='/ - do not change shipping cost - fix price.| \, 15\/30|if oos inform customer, no incense for alt. gift|, CARRIAGE BETWEEN .* AND 110|If oos, send email\!|deliver after 10:30 am|Contact Customer for payment details \!\!\!|Check order CAREFULLY|CHARGE BEFORE PICKING|Please phone 087 652 5769 before delivery\!|Deliveries accepted Tue - Sat 1000-1700 Contac Nic|Daughter: Cristina Viana|if oos inform customer, no more bath sets|pls contact cust if any probs with paym|no carriage, |Please contact customer for out of stock items.|See customer.s note regarding SSC.|delivery to Ireland - |Deliveries accepted Tue to Sat 10 \- 17\:30|deliver after 10am|Delivery after 10.00 am| P 24\/06 via frans mass|see note of 5th FEB|see note of 09\/06\/20009|always quote customer| --- Shipping FOC promotion|Kaym_Whelan@yahoo.ie| - checked see note of 19\/05\!/i';

			if (preg_match($regex, $tax_number,$match)) {

				$note=$match[0];
				$tax_number=preg_replace($regex,'',$tax_number);
			}
		}
		//print "OTN: $tax_number\n";
		// print "tax number: $tax_number\n";
		$tax_number=_trim($tax_number);
		// print "$tax_number\n";
		$tax_number=preg_replace('/tax id\s*:?\s*-?\s*/i','',$tax_number);
		$tax_number=preg_replace('/V\.a\.t\. N.*:\s*-?\s*/i','',$tax_number);

		$tax_number=preg_replace('/VAT NO\s*-\s*/i','',$tax_number);
		$tax_number=preg_replace('/^VAT No\.\:\s*/i','',$tax_number);
		$tax_number=preg_replace('/^vat no\s*(\.|:)?\s*/i','',$tax_number);
		$tax_number=preg_replace('/^vat\s*(\:|\-)?\s*/i','',$tax_number);
		$tax_number=preg_replace('/^vat\s*reg\*(\:|\-)?\s*/i','',$tax_number);
		$tax_number=preg_replace('/\-?\s*Checked and Valid$/i','',$tax_number);
		$tax_number=preg_replace('/\-?\s*valid and checked$/i','',$tax_number);
		$tax_number=preg_replace('/tax\s*:?\s*/i','',$tax_number);

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

		if (preg_match('/EL137399039 checkedEL-137399039/i',$tax_number))
			$tax_number='EL137399039';
		if (preg_match('/PT:503958271, validPT-503958271/i',$tax_number))
			$tax_number='PT-503958271';
		if (preg_match('/NL060484305B02 validNL060484305B02 valid/i',$tax_number))
			$tax_number='NL060484305B02';
		if (preg_match('/^IE : 3756781C$/i',$tax_number))
			$tax_number='IE3756781C';

		$tax_number=_trim($tax_number);
		// print "TN: $tax_number\n";
		if (
			preg_match('/^[a-z]{1,2}\s*\-?\s*[a-z0-9]{8,12}\s*$/i',$tax_number)
			or preg_match('/^[a-z]{0,2}\s*\d{6,16}\s*[a-z]\.?\d{0,10}$/i',$tax_number)
			or preg_match('/^\d{3} \d{4}\-?\d/i',$tax_number)
			or preg_match('/[a-z]-\d{6,10}-[a-z]/i',$tax_number)
			or preg_match('/[a-z]{2}\s*\d{3}\.\d{3}\.\d{3}/i',$tax_number)
			or preg_match('/\d{3}.\d{3,4}.\d{3,4}/i',$tax_number)
			or preg_match('/680602-4840/i',$tax_number)
			or preg_match('/[a-z]{2}\s*\d{2,4}\s*\d{2,3}\s*\d{2,4}\s*[a-z]?\d{2,4}/i',$tax_number)
			or preg_match('/NL 8132 54 097 B01/i',$tax_number)
			or preg_match('/n-\d{8} S/i',$tax_number)
			or preg_match('/tf 2134041/i',$tax_number)




		) {
			$tax_number=preg_replace('/\s/','',$tax_number);
			if (!($tax_number[2]=='-'  or $tax_number[1]=='-')) {

				if (preg_match('/^[a-z]{2}\d/i',$tax_number)) {
					$t1=substr($tax_number,0,2);
					$t2=substr($tax_number,2);
					$tax_number=$t1.'-'.$t2;
				}elseif (preg_match('/^[a-z]\d/i',$tax_number)) {
					$t1=substr($tax_number,0,1);
					$t2=substr($tax_number,1);
					$tax_number=$t1.'-'.$t2;
				}


			}
			$data['tax_number']=$tax_number;
			$data['notes2']=$note;
			// print "$tax_number\n";
			// return $tax_number;
		}elseif (preg_match('/^\d{7,12}$/i',$tax_number)) {
			// print "$tax_number\n";
			// return $tax_number;
			$data['tax_number']=$tax_number;
			$data['notes2']=$note;
		}
	}elseif (preg_match('/^vat\s\d{11}$/i',_trim($data['notes2']))) {
		$data['tax_number']=$data['notes2'];
		$data['notes2']='';
	}elseif (preg_match('/SA VAT NO 9116\/677\/16\/3/i',_trim($data['notes2']))) {
		$data['tax_number']=preg_replace('/^SA VAT NO /','',$data['notes2']);
		$data['notes2']='';
	}elseif (preg_match('/^tax : tf \d{7}/i',_trim($data['notes2']))) {
		$data['tax_number']=preg_replace('/^tax : /','',$data['notes2']);
		$data['notes2']='';
	}elseif (preg_match('/^tax id \d{5,}/i',_trim($data['notes2']))) {
		$data['tax_number']=preg_replace('/^tax id /','',$data['notes2']);
		$data['notes2']='';
	}elseif (preg_match('/^(Customer)?\s*tax id\s*:?\s*[a-z]?\d{5,}[a-z]?/i',_trim($data['notes2']))) {
		$data['tax_number']=preg_replace('/^(Customer)?\s*tax id\s*:?\s*/','',$data['notes2']);
		$data['notes2']='';
	}elseif (preg_match('/^tax : tf 2134041?/i',_trim($data['notes2']))) {
		$data['tax_number']='tf 2134041';
		$data['notes2']='';
	}elseif (preg_match('/^Tax 85 467 757 063?/i',_trim($data['notes2']))) {
		$data['tax_number']='85467757063';
		$data['notes2']='';
	}elseif (preg_match('/^EL 046982660 valid?/i',_trim($data['notes2']))) {
		$data['tax_number']='EL-046982660';
		$data['notes2']='';
	}elseif (preg_match('/^EL-377 187 83?/i',_trim($data['notes2']))) {
		$data['tax_number']='EL-37718783';
		$data['notes2']='';
	}elseif (preg_match('/^FI1622254-8 checked by customs?/i',_trim($data['notes2']))) {
		$data['tax_number']='FI-1622254-8';
		$data['notes2']='';
	}elseif (preg_match('/^IE-7251185?/i',_trim($data['notes2']))) {
		$data['tax_number']='IE-7251185';
		$data['notes2']='';
	}elseif (preg_match('/^SE556670-257601$/i',_trim($data['notes2']))) {
		$data['tax_number']='SE556670-257601';
		$data['notes2']='';
	}elseif (preg_match('/^IE5493347N$/i',_trim($data['notes2']))) {
		$data['tax_number']='IE5493347N';
		$data['notes2']='';
	}elseif (preg_match('/^ES-B92544691$/i',_trim($data['notes2']))) {
		$data['tax_number']='ES-B92544691';
		$data['notes2']='';
	}

	return $data;

}




function read_products($raw_product_data,$y_map) {

	if (isset($y_map['no_reorder']) and $y_map['no_reorder'])
		$re_order=false;
	else
		$re_order=true;

	if (isset($y_map['no_price_bonus']) and $y_map['no_price_bonus'])
		$no_price_bonus=true;
	else
		$no_price_bonus=false;


	$transactions=array();
	foreach ($raw_product_data as $raw_data) {
		foreach ($y_map as $key=>$value) {
			$_data=$raw_data[$value];
			if (preg_match('/order|reorder|bonus/',$key))
				if ($_data=='')$_data=0;

				if (!$re_order and ($key=='reorder' or $key=='rrp')  )
					$_data=0;

				if ($no_price_bonus) {
					if ($key=='order' and $transaction['price']==0)
						$_data=0;
					if ($key=='bonus' and $transaction['price']==0)
						$_data=$_data+ $raw_data[$y_map['order']]  ;


				}
			if ($key=='supplier_product_code' and $raw_data[$y_map['supplier_code']]=='AW'   ) {
				$_data=$raw_data[$y_map['code']];
			}

			$transaction[$key]=$_data;
		}


		if ($transaction['units']==1 or $transaction['units']=='')
			$transaction['name']=$transaction['description'];
		else
			$transaction['name']=trim($transaction['units'].'x '.$transaction['description']);


		$transaction['fob']=$raw_data['fob'];
		$transactions[]=$transaction;
	}
	// print_r($transactions);
	return $transactions;
}

function read_header($data) {

	$header_data=get_empty_header();

	$header_data['date_order']=$data['created_at'];
	$header_data['weight']=$data['weight'];
	$header_data['total_topay']=$data['grand_total'];
	$header_data['tax1']=$data['tax_amount'];
	$header_data['total_net']=$data['subtotal']+$data['shipping_amount'];
	$header_data['shipping']=$data['shipping_amount'];
	$header_data['notes']=$data['customer_note'];







	//print_r($header_data);

	return $header_data;


}


function get_empty_header() {
	$header_data=array(
		'stipo'=>'',
		'ltipo'=>'',
		'pickedby'=>'',
		'parcels'=>'',
		'packedby'=>'',
		'weight'=>'',
		'trade_name'=>'',
		'takenby'=>'',
		'customer_num'=>'',
		'order_num'=>'',
		'date_order'=>'',
		'date_inv'=>'',
		'pay_method'=>'',
		'address1'=>'',
		'history'=>'',
		'address2'=>'',
		'notes'=>'',
		'total_net'=>'',
		'gold'=>'',
		'address3'=>'',
		'charges'=>'',
		'tax1'=>0,
		'city'=>'',
		'total_topay'=>'',
		'tax2'=>0,
		'postcode'=>'',
		'notes2'=>'',
		'shipping'=>'',
		'customer_contact'=>'',
		'phone'=>'',
		'total_order'=>'',
		'total_reorder'=>'',
		'total_bonus'=>'',
		'total_items_charge_value'=>'',
		'total_rrp'=>'',
		'feedback'=>'',
		'source_tipo'=>'',
		'extra_id1'=>'',
		'extra_id2'=>'',
		'dn_country_code'=>'',
		'collection'=>'No'
	);

	$header_data['Order Main Source Type']='Unknown';
	$header_data['Delivery Note Dispatch Method']='Unknown';
	$header_data['staff sale key']=0;;
	$header_data['collection']='No';
	$header_data['shipper_code']='';
	$header_data['staff sale']='No';
	$header_data['showroom']='No';
	$header_data['staff sale name']='';


	return $header_data;
}


function get_customer_msg($data) {
	$data['customer_msg']='';
	if (preg_match('/^(DO NOT SEND WINE-SEND ALTERNATIVE|PLEASE HOLD UNTIL Bag-01 IN STOCK|corner of Marine Parade and Graystone Road|Friday \d{1,2}pm|NO WINE\!|Give to Kara|open 10 am to 5 pm|entrance from.*Street|del tue or thu|If Not In Leave In Cupboard By Door Please|if noone in leave with neighbour or in garage|closed on Wednesdays|Shop open 10am-5pm. Closed Wednesdays.|Leave at rear if out|no wine\!?|Look 4 Multi-Storey Carpark|Not open untill? \d{1,2}.\d{1,2}(AM|PM))$/i',_trim($data['notes2']))
		or preg_match('/carefully|pls pack|pls pick|9am sharp|email cust on|if any|if cust|notify if|call |access via|contact cust|give wine|call on |pls pick today|can only del|Check order CAREFULLY|CHECK CARRIAGE|contact cust if out of stock|drink so give something else as bonus|WEDNESDAY|DESP TODAY AND PACK CAREFULLY|please pack bath bombs very|If closed with|call if|IF ITEMS OUT OF STOCK CONTACT CUSTOMER|Tuesday|No Substitution please|Thursday|friday|can be left|deluvery |please |closed on|Subs OK|NO WINE alternative gift please 1 box of SG|if out can be left |Please call if|contact cust if something out of stock|if out put|Alternative gift to WINE|Add Catalogu|Call if out of stock|Call if if out of stock|Leave outside|Closed between|Not before|Let (her|me|him) know|oppocite|opposite|Behind|Must go out on|Deliver before|if not there|nobody|Leave in|Deliver|If no-one|Leave at|Deliver on|closed at|Please ring customer before delivery |Delivery Between|nobody|porch |close |Open |Shop open|Shop closed|if out Deliver|Leave at|if not there|next door|delivery before|deliver to|in shed|leave around|leave with|leave on|garage|shop|if noone|if not|despatch|dispatch/i',$data['notes2'])
	) {
		$data['customer_msg']=$data['notes2'];
		$data['notes2']='';

	}



	return $data;
}




function is_to_be_collected($data) {
	if (preg_match('/^(collecting|To be collect by cust.|To be collect|For Collection|To be collection|COLLECT|Collection|Collect .*|Collection.*|to be collected|to collect|collected|customer to collect|to be collect by cust|to be collected.*|will collec.*|to collect.*|to collect today)$/i',_trim($data['notes']))) {

		$data['shipper_code']='NA';
		$data['collection']='Yes';

		if (preg_match('/^(collecting|To be collect by cust.|To be collect|For Collection|To be collection|COLLECT|Collection|to be collected|to collect|collected|customer to collect|to be collect by cust)$/i',_trim($data['notes']))) {
			$data['notes']='';
		}

	}

	if (preg_match('/^(collecting|To be collect by cust.|To be collect|For Collection|To be collection|COLLECT|Collection|Collect .*|Collection.*|to be collected|to collect|collected|customer to collect|to be collect by cust|to be collected.*|will collec.*|to collect.*|to collect today)$/i',_trim($data['notes']))) {

		$data['shipper_code']='NA';
		$data['collection']='Yes';

		if (preg_match('/^(collecting|To be collect by cust.|To be collect|For Collection|To be collection|COLLECT|Collection|to be collected|to collect|collected|customer to collect|to be collect by cust)$/i',_trim($data['notes2']))) {
			$data['notes2']='';
		}


	}


	return $data;

}

function is_showroom($str) {
	if (preg_match('/^(showrooms?|Showrooom)$/i',_trim($str)))
		return true;
	else
		return false;

}

function is_staff_sale($data) {
	$data['staff sale']='no';
	$data['staff sale name']='';

	if (preg_match('/^(staff sale\s+[a-z]+)$/i',_trim($data['notes']))) {

		$data['staff sale']='yes';
		$data['staff sale name']=preg_replace('/staff sale\s+/','',$data['notes']);
		$data['notes']='';

	}

	if (preg_match('/^(staff sale|staff)$/i',_trim($data['notes']))) {
		$data['notes']='';
		$data['staff sale']='yes';

	}


	return $data;

}








?>
