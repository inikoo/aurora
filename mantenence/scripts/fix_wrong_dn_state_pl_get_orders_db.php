<?php
//include("../../external_libs/adminpro/adminpro_config.php");
error_reporting(E_ALL);

include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.PartLocation.php';
include_once '../../class.Deal.php';

include_once '../../class.Supplier.php';
include_once '../../class.Order.php';
include_once '../../class.Invoice.php';
include_once '../../class.DeliveryNote.php';
include_once '../../class.Email.php';
include_once '../../class.CurrencyExchange.php';
include_once 'common_read_orders_functions.php';


$store_code='P';
$__currency_code='PLN';

$calculate_no_normal_every =500;
$to_update=array(
	'products'=>array(),
	'products_id'=>array(),
	'products_code'=>array(),
	'families'=>array(),
	'departments'=>array(),
	'stores'=>array(),
	'parts'=>array()

);

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}

//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}
date_default_timezone_set('UTC');
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;

include_once '../../set_locales.php';

require_once '../../conf/conf.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();


$_SESSION['lang']=1;
print "->Start.(GO PL) ".date("r")."\n";

$shipping_transactions=array();



include_once 'pl_local_map.php';
include_once 'pl_map_order_functions.php';
$myconf['country_code']='POL';
$myconf['country_2acode']='PL';

$myconf['home_id']='162';
$myconf['country_id']='162';
$software='Get_Orders_DB.php';
$version='V 1.0';//75693

$Data_Audit_ETL_Software="$software $version";
srand(12111);



$store=new Store("code","PL");
$store_key=$store->id;



$dept_no_dept=new Department('code','ND_PL',$store_key);
$dept_no_dept_key=$dept_no_dept->id;
$dept_promo=new Department('code','Promo_PL',$store_key);
$dept_promo_key=$dept_promo->id;


$fam_no_fam=new Family('code','PND_PL',$store_key);
$fam_no_fam_key=$fam_no_fam->id;
$fam_promo=new Family('code','Promo_PL',$store_key);
$fam_promo_key=$fam_promo->id;

$sql="select * from  pl_orders_data.orders  where   deleted='Yes'    ";
$res=mysql_query($sql);
while ($row2=mysql_fetch_array($res, MYSQL_ASSOC)) {
	$order_data_id=$row2['id'];
	delete_old_data();
}

$orders_data_id='';
$sql="select `Invoice Metadata` from `Invoice Dimension` where `Invoice Paid`='Parcially'  and `Invoice Store Key`=7";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$xxx=preg_replace('/P/','',$row['Invoice Metadata']);
	if($xxx!='')
	$orders_data_id.=','.$xxx;
	
}


$orders_data_id='';
$sql="select * from `Delivery Note Dimension` where `Delivery Note State`=''  and `Delivery Note Store Key`=7";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$xxx=preg_replace('/P/','',$row['Delivery Note Metadata']);
	if($xxx!='')
	$orders_data_id.=','.$xxx;
	
}

$sql="select * from `Order Dimension` where  `Order Cancel Note` LIKE 'Order automatically cancelled'  and `Order Store Key`=7";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$xxx=preg_replace('/P/','',$row['Order Original Metadata']);
	if($xxx!='')
	$orders_data_id.=','.$xxx;
	
}

$sql="select * from `Order Dimension` where  `Order Suspend Note` LIKE 'Order automatically suspended'  and `Order Store Key`=7";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$xxx=preg_replace('/P/','',$row['Order Original Metadata']);
	if($xxx!='')
	$orders_data_id.=','.$xxx;
	
}
$sql="select * from `Order Dimension` where  `Order Current Dispatch State`='In Process' and `Order Store Key`=7";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$xxx=preg_replace('/P/','',$row['Order Original Metadata']);
	if($xxx!='')
	$orders_data_id.=','.$xxx;
	
}

$orders_data_id=preg_replace('/^,/','',$orders_data_id);



$sql="select * from  pl_orders_data.orders  where   deleted='No'   and  id in (".$orders_data_id.")   order by filename  ";


$contador=0;

$res=mysql_query($sql);

while ($row2=mysql_fetch_array($res, MYSQL_ASSOC)) {
	$discounts_with_order_as_term=array();
	$customer_key_from_order_data=$row2['customer_id'];
	$customer_key_from_excel_order=0 ;

	$sql="select * from pl_orders_data.data where id=".$row2['id'];


	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		if ($row['header']=='')
			continue;

		//           echo "                                                          Memory: ".memory_get_usage(true) . "\n";

		$order_data_id=$row2['id'];
		$filename=$row2['filename'];
		$contador++;
		$total_credit_value=0;

		// check if it is already readed
		$update=false;
		$old_order_key=0;
		$sql=sprintf("select count(*) as num  from `Order Dimension`  where `Order Original Metadata`=%s ",prepare_mysql($store_code.$order_data_id));
		$result_test=mysql_query($sql);
		if ($row_test=mysql_fetch_array($result_test, MYSQL_ASSOC)) {
			if ($row_test['num']==0) {
				$sql=sprintf("select count(*) as num  from `Invoice Dimension`  where `Invoice Metadata`=%s "
					,prepare_mysql($store_code.$order_data_id));
				$result_test2=mysql_query($sql);
				if ($row_test2=mysql_fetch_array($result_test2, MYSQL_ASSOC)) {
					if ($row_test2['num']==0) {
						$sql=sprintf("select count(*) as num  from `Delivery Note Dimension`  where `Delivery Note Metadata`=%s "
							,prepare_mysql($store_code.$order_data_id));
						$result_test3=mysql_query($sql);
						if ($row_test3=mysql_fetch_array($result_test3, MYSQL_ASSOC)) {
							if ($row_test3['num']==0) {
								print "NEW $contador $order_data_id $filename ";
							} else {
								$update=true;
								print "UPD $contador $order_data_id $filename ";
							}
						}
					} else {
						$update=true;
						print "UPD $contador $order_data_id $filename ";
					}
				}
			} else {
				$update=true;
				print "UPD $contador $order_data_id $filename ";
			}
		}
		mysql_free_result($result_test);

		$header=mb_unserialize($row['header']);




		$products=mb_unserialize($row['products']);


		$filename_number=str_replace('.xls','',str_replace($row2['directory'],'',$row2['filename']));
		$map_act=$_map_act;
		$map=$_map;
		$y_map=$_y_map;

		// tomando en coeuntas diferencias en la posicion de los elementos


		$prod_map=$y_map;



		list($act_data,$header_data)=read_header($header,$map_act,$y_map,$map,false);

		//print_r($header_data);
		//   exit;


		$header_data=filter_header($header_data);
		round_header_data_totals();





		list($tipo_order,$parent_order_id,$header_data)=get_tipo_order($header_data['ltipo'],$header_data);





		if (preg_match('/^PL\d{4}sh$/i',$filename_number)) {
			$tipo_orPLr=7;
			$parent_orPLr_id=preg_replace('/sh/i','',$filename_number);
		}
		if (preg_match('/^PL\d{4}sht$/i',$filename_number)) {
			$tipo_orPLr=7;
			$parent_orPLr_id=preg_replace('/sht/i','',$filename_number);
		}

		if (preg_match('/^PL\d{4}rpl$/i',$filename_number)) {
			$tipo_orPLr=6;
			$parent_orPLr_id=preg_replace('/rpl/i','',$filename_number);

		}
		if (preg_match('/^PL\d{4,5}r$|^PL\d{4,5}ref$|^PL\d{4,5}\s?refund$|^PL\d{4,5}rr$|^PL\d{4,5}ra$|^PL\d{4,5}r2$|^PL\d{4,5}\-2ref$|^PL\d{5}rfn$/i',$filename_number)) {
			$tipo_orPLr=9;
			$parent_orPLr_id=preg_replace('/r$|ref$|refund$|rr$|ra$|r2$|\-2ref$|rfn$/i','',$filename_number);


		}



		//if($tipo_order==2 or $tipo_order==1){
		//  print "\n";
		//  continue;
		// }

		list($date_index,$date_order,$date_inv)=get_dates_pl($row2['timestamp'],$header_data,$tipo_order,true);

		$editor=array(
			'Date'=>$date_order,
			'Author Name'=>'',
			'Author Alias'=>'',
			'Author Type'=>'',
			'Author Key'=>0,
			'User Key'=>0,
		);


		$data['editor']=$editor;

		if ($tipo_order==9) {
			if ( $date_inv=='NULL' or  strtotime($date_order)>strtotime($date_inv)) {
				$date_inv=$date_order;
			}
		}


		if ( $date_inv!='NULL' and  strtotime($date_order)>strtotime($date_inv)) {


			//$date2=date("Y-m-d H:i:s",strtotime($date_order.' +1 hour'));
			print "Warning (Fecha Factura anterior Fecha Orden) $filename $date_order  $date_inv\n  ".strtotime($date_order).' > '.strtotime($date_inv)."\n";
			$date_inv=date("Y-m-d H:i:s",strtotime($date_order.' +1 hour'));

			// print "new date: ".$date2."\n";

		}


		if ($date_order=='')
			$date_index2=$date_index;
		else
			$date_index2=$date_order;

		if ($tipo_order==2  or $tipo_order==6 or $tipo_order==7 or $tipo_order==9 ) {
			$date2=$date_inv;
		}
		elseif ($tipo_order==4  or   $tipo_order==5 or    $tipo_order==8  )
			$date2=$date_index;
		else
			$date2=$date_order;

		$header_data['Order Main Source Type']='Unknown';
		$header_data['Delivery Note Dispatch Method']='Unknown';
		$header_data['staff sale key']=0;;
		$header_data['collection']='No';
		$header_data['shipper_code']='';
		$header_data['staff sale']='No';
		$header_data['showroom']='No';
		$header_data['staff sale name']='';



		if (!$header_data['notes']) {
			$header_data['notes']='';
		}
		if (!$header_data['notes2'] or preg_match('/^vat|Special Instructions$/i',_trim($header_data['notes2']))) {
			$header_data['notes2']='';
		}

		if (preg_match('/^(Int Freight|Intl Freight|Internation Freight|Intl FreightInternation Freight|International Frei.*|International Freigth|Internation Freight|Internatinal Freight|nternation(al)? Frei.*|Internationa freight|International|International freight|by sea)$/i',$header_data['notes']))
			$header_data['notes']='International Freight';

		//delete no data notes

		$header_data=is_to_be_collected($header_data);

		$header_data=is_shipping_supplier($header_data);
		$header_data=is_staff_sale($header_data);



		if (preg_match('/^(|International Freight)$/',$header_data['notes'])) {
			$header_data['notes']='';

		}
		//  print "N1: ".$header_data['notes']."\n";
		//  print "N2: ".$header_data['notes2']."\n\n";
		// }
		// if(!preg_match('/^(|0|\s*)$/',$header_data['notes2']))

		$header_data=get_tax_number($header_data);
		$header_data=get_customer_msg($header_data);

		if ($header_data['notes']!='' and $header_data['notes2']!='') {
			$header_data['notes2']=_trim($header_data['notes'].', '.$header_data['notes2']);
			$header_data['notes']='';
		}
		elseif ($header_data['notes']!='') {
			$header_data['notes2']=$header_data['notes'];
			$header_data['notes']='';
		}

		$header_data=get_customer_msg($header_data);

		if (preg_match('/^(IE 9575910F|85 467 757 063|ie 7214743D|ES B92544691|IE-7251185|SE556670-257601|x5686842-t)$/',$header_data['notes2'])) {
			$data['tax_number']=$header_data['notes2'];
			$header_data['notes']='';
		}




		$transactions=read_products($products,$prod_map);
		unset($products);
		//   echo "Memory: ".memory_get_usage(true) . "x\n";
		//     echo "Memory: ".memory_get_usage() . "x\n";
		$_customer_data=setup_contact($act_data,$header_data,$date_index2);
		list($_customer_data['type'],$_customer_data['company_name'],$_customer_data['contact_name'])=parse_company_person($_customer_data['company_name'],$_customer_data['contact_name']);

		$customer_data=array();

		if (isset($header_data['tax_number']) and $header_data['tax_number']!='') {
			$customer_data['Customer Tax Number']=$header_data['tax_number'];
		}

		//    print_r($_customer_data);
		foreach ($_customer_data as $_key =>$value) {
			$key=$_key;
			if ($_key=='type')
				$key=preg_replace('/^type$/','Customer Type',$_key);
			if ($_key=='contact_name')
				$key=preg_replace('/^contact_name$/','Customer Main Contact Name',$_key);
			if ($_key=='company_name')
				$key=preg_replace('/^company_name$/','Customer Company Name',$_key);
			if ($_key=='email')
				$key=preg_replace('/^email$/','Customer Main Plain Email',$_key);
			if ($_key=='telephone')
				$key=preg_replace('/^telephone$/','Customer Main Plain Telephone',$_key);
			if ($_key=='fax')
				$key=preg_replace('/^fax$/','Customer Main Plain FAX',$_key);
			if ($_key=='mobile')
				$key=preg_replace('/^mobile$/','Customer Main Plain Mobile',$_key);
			// if($_key=='tax_number')
			// $key=preg_replace('/^tax_number$/','Customer Tax Number',$_key);
			$customer_data[$key]=$value;

		}
		$customer_data['Customer Store Key']=$store_key;
		if ($customer_data['Customer Type']=='Company')
			$customer_data['Customer Name']=$customer_data['Customer Company Name'];
		else
			$customer_data['Customer Name']=$customer_data['Customer Main Contact Name'];
		if (isset($_customer_data['address_data'])) {
			$customer_data['Customer Address Line 1']=$_customer_data['address_data']['address1'];
			$customer_data['Customer Address Line 2']=$_customer_data['address_data']['address2'];
			$customer_data['Customer Address Line 3']=$_customer_data['address_data']['address3'];
			$customer_data['Customer Address Town']=$_customer_data['address_data']['town'];
			$customer_data['Customer Address Postal Code']=$_customer_data['address_data']['postcode'];
			$customer_data['Customer Address Country Name']=$_customer_data['address_data']['country'];
			$customer_data['Customer Address Country First Division']=$_customer_data['address_data']['country_d1'];
			$customer_data['Customer Address Country Second Division']=$_customer_data['address_data']['country_d2'];
			unset($customer_data['address_data']);
		}
		$customer_data['Customer Delivery Address Link']='Contact';


		$shipping_addresses=array();
		if (isset($_customer_data['address_data']) and $_customer_data['has_shipping']) {
			if (!is_same_address($_customer_data)) {
				$customer_data['Customer Delivery Address Link']='None';
			}

			$shipping_addresses['Address Line 1']=$_customer_data['shipping_data']['address1'];
			$shipping_addresses['Address Line 2']=$_customer_data['shipping_data']['address2'];
			$shipping_addresses['Address Line 3']=$_customer_data['shipping_data']['address3'];
			$shipping_addresses['Address Town']=$_customer_data['shipping_data']['town'];
			$shipping_addresses['Address Postal Code']=$_customer_data['shipping_data']['postcode'];
			$shipping_addresses['Address Country Name']=$_customer_data['shipping_data']['country'];
			$shipping_addresses['Address Country First Division']=$_customer_data['shipping_data']['country_d1'];
			$shipping_addresses['Address Country Second Division']=$_customer_data['shipping_data']['country_d2'];
			unset($customer_data['shipping_data']);
		}

		//  print_r($transactions);

		if (strtotime($date_order)>strtotime($date2)) {



			//$date2=date("Y-m-d H:i:s",strtotime($date_order.' +1 hour'));
			print "Warning (Fecha Factura anterior Fecha Orden) $filename $date_order  $date2 \n";
			$date2=date("Y-m-d H:i:s",strtotime($date_order.' +8 hour'));

			print "new date: ".$date2."\n";

		}

		if (strtotime($date_order)>strtotime('now')   ) {

			print "ERROR (Fecha en el futuro) $filename  $date_order   \n ";

			continue;
		}

		if (strtotime($date_order)<strtotime($myconf['data_from'])  ) {

			print "ERROR (Fecha sospechosamente muy  antigua) $filename $date_order \n";

			continue;
		}

		$customer_data['Customer First Contacted Date']=$date_order;
		$extra_shipping=0;

		$data=array();
		$data['editor']=array('Date'=>$date_order);

		$data['order date']=$date_order;
		$data['order id']=$header_data['order_num'];
		$data['order customer message']=$header_data['notes2'];
		if ($data['order customer message']==0)
			$data['order customer message']='';



		$tmp_filename=preg_replace('/\/mnt\/z\/Orders-germany\//',"\\\\\\networkspace1\\openshare\\Orders\\",$row2['filename']);
		$tmp_filename=preg_replace('/\//',"\\",$tmp_filename);

		$data['Order Original Data Filename']=$tmp_filename;
		$data['order original data mime type']='application/vnd.ms-excel';
		$data['order original data']='';
		$data['order original data source']='Excel File';

		$data['Order Original Metadata']=$store_code.$row2['id'];


		$products_data=array();
		$data_invoice_transactions=array();
		$data_dn_transactions=array();
		$data_bonus_transactions=array();

		$credits=array();

		$total_credit_value=0;
		$estimated_w=0;
		//echo "Memory: ".memory_get_usage(true) . "\n";
		//    print_r($transactions);
		// exit;

		foreach ($transactions as $transaction) {

			$transaction['code']=_trim($transaction['code']);

			if (preg_match('/credit|refund/i',$transaction['code'])) {


				if (preg_match('/^Credit owed for order no\.\:\d{4,5}$/',$transaction['description'])) {
					$credit_parent_public_id=preg_replace('/[^\d]/','',$transaction['description']);
					$credit_value=$transaction['credit'];
					$credit_description=$transaction['description'];
					$total_credit_value+=$credit_value;
				}
				elseif (preg_match('/^(Credit owed for order no\.\:|Credit for damage item|Refund for postage .paid by customer)$/i',$transaction['description'])) {
					$credit_parent_public_id='';
					$credit_value=$transaction['credit'];
					$credit_description=$transaction['description'];
					$total_credit_value+=$credit_value;

				}
				else {
					$credit_parent_public_id='';
					$credit_value=$transaction['credit'];
					$credit_description=$transaction['description'];
					$total_credit_value+=$credit_value;


				}
				$_parent_key='NULL';
				$_parent_order_date='';
				if ($credit_parent_public_id!='') {
					$credit_parent=new Order('public id',$credit_parent_public_id);
					if ($credit_parent->id) {
						$_parent_key=$credit_parent->id;
						$_parent_order_date=$credit_parent->data['Order Date'];
					}
				}

				$credits[]=array(
					'parent_key'=>$_parent_key
					,'value'=>$credit_value
					,'description'=>$credit_description
					,'parent_date'=>$_parent_order_date
				);

				//print_r($transaction);
				//print_r($credits);
				//exit;
				// $credit[]=array()
				continue;
			}

			if (preg_match('/Freight|^frc-|Postage/i',$transaction['code'])) {

				$extra_shipping+=$transaction['price'];
				continue;

			}
			if (preg_match('/^cxd-|^wsl$|^eye$|^\d$|2009promo/i',$transaction['code']))
				continue;
			if (preg_match('/difference in prices|Diff.in price for|difference in prices/i',$transaction['description']))
				continue;

			$__code=strtolower($transaction['code']);

			if (   preg_match('/\-pack$/i',$__code)  or    preg_match('/\-pst$/i',$__code)  or    preg_match('/\-kit2$/i',$__code)  or  preg_match('/\-kit1$/i',$__code)  or preg_match('/\-st$/i',$__code)  or  preg_match('/Bag-02Mx|Bag-04mx|Bag-05mx|Bag-06mix|Bag-07MX|Bag-12MX|Bag-13MX|FishP-Mix|IncIn-ST|IncB-St|LLP-ST|L\&P-ST|EO-XST|AWRP-ST/i',$__code) or         $__code=='eo-st' or $__code=='mol-st' or  $__code=='jbb-st' or $__code=='lwheat-st' or  $__code=='jbb-st'
				or $__code=='scrub-st' or $__code=='eye-st' or $__code=='tbm-st' or $__code=='tbc-st' or $__code=='tbs-st'
				or $__code=='gemd-st' or $__code=='cryc-st' or $__code=='gp-st'  or $__code=='dc-st'
			) {
				continue;

			}


			if (preg_match('/-\st$/i',$__code)) {
				continue;
			}
			if (preg_match('/-\minst$/i',$__code)) {
				continue;
			}

			$transaction['code']=preg_replace('/L\&P\-/','LLP-',$transaction['code']);



			$transaction['description']=preg_replace('/\s*\(\s*replacements?\s*\)\s*$/i','',$transaction['description']);
			$transaction['description']=preg_replace('/\s*(\-|\/)\s*replacements?\s*$/i','',$transaction['description']);
			$transaction['description']=preg_replace('/\s*(\-|\/)\s*SHOWROOM\s*$/i','',$transaction['description']);
			$transaction['description']=preg_replace('/\s*(\-|\/)\s*to.follow\/?\s*$/i','',$transaction['description']);
			$transaction['description']=preg_replace('/\s*(\-|\/)\s*missing\s*$/i','',$transaction['description']);
			$transaction['description']=preg_replace('/\/missed off prev.order$/i','',$transaction['description']);
			$transaction['description']=preg_replace('/\(missed off on last order\)$/i','',$transaction['description']);
			$transaction['description']=preg_replace('/\/from prev order$/i','',$transaction['description']);
			$transaction['description']=preg_replace('/\s*\(owed from prev order\)$/i','',$transaction['description']);
			$transaction['description']=preg_replace('/\s*\/prev order$/i','',$transaction['description']);
			$transaction['description']=preg_replace('/\s*\-from prev order$/i','',$transaction['description']);
			$transaction['description']=preg_replace('/TO FOLLOW$/','',$transaction['description']);

			if (preg_match('/^sg\-$|^SG\-mix$|^sg-xx$/i',$transaction['code']) ) {
				$transaction['code']='SG-mix';
				$transaction['description']='Simmering Granules Mixed Box';
			}
			if (preg_match('/SG-Y2/i',$transaction['code'])   and preg_match('/mix/i',$transaction['description'])) {
				$transaction['code']='SG-mix';
				$transaction['description']='Simmering Granules Mixed Box';
			}
			if (preg_match('/sg-bn/i',$transaction['code']) ) {
				$transaction['code']='SG-BN';
				$transaction['description']='Simmering Granules Mixed Box';
			}
			if (preg_match('/^sg$/i',$transaction['code']) and preg_match('/^(Mixed Simmering Granules|Mixed Simmering Granuels|Random Mix Simmering Granules)$/i',$transaction['description']) ) {
				$transaction['code']='SG-mix';
				$transaction['description']='Simmering Granules Mixed Box';
			}
			if (preg_match('/^(sg|salt)$/i',$transaction['code']) and preg_match('/25/i',$transaction['description']) ) {
				$transaction['code']='SG';
				$transaction['description']='25Kg Hydrosoft Granular Salt';
			}
			if (preg_match('/^(salty)$/i',$transaction['code']) and preg_match('/25/i',$transaction['description']) ) {
				$transaction['code']='SG';
			}

			if (preg_match('/^(salt|salt-xx|salt-11w|Salt-Misc)$/i',$transaction['code']) and preg_match('/fit/i',$transaction['description']) ) {
				$transaction['code']='Salt-Fitting';
				$transaction['description']='Spare Fitting for Salt Lamp';
			}


			if ((preg_match('/^(salt-11w)$/i',$transaction['code']) and preg_match('/^Wood Base|^Bases/i',$transaction['description'])) or preg_match('/Salt-11 bases/i',$transaction['code']) or  preg_match('/Black Base for Salt Lamp/i',$transaction['description']) ) {
				$transaction['code']='Salt-Base';
				$transaction['description']='Spare Base for Salt Lamp';
			}

			if (preg_match('/^wsl-320$/i',$transaction['code'])) {
				$transaction['description']='Two Tone Palm Wax Candles Sml';
			}
			if (preg_match('/^wsl-631$/i',$transaction['code'])) {
				$transaction['description']='Pewter Pegasus & Ball with LED';
			}



			if (preg_match('/^wsl-848$/i',$transaction['code'])   and preg_match('/wsl-848, simple message candle/i',$transaction['description'])) {
				$transaction['description']='Simple Message Candle 3x6';
				$transaction['code']='wsl-877';
			}

			if (preg_match('/^bot-01$/i',$transaction['code'])   and preg_match('/10ml Amber Bottles.*tamper.*ap/i',$transaction['description']))
				$transaction['description']='10ml Amber Bottles & Tamper Proof Caps';
			if (preg_match('/^81992$/i',$transaction['code'])   and preg_match('/Amber Bottles/i',$transaction['description']))
				$transaction['code']='Bot-02';



			if (preg_match('/^bot-01$/i',$transaction['code'])   and preg_match('/10ml Amber Bottles.*only/i',$transaction['description']))
				$transaction['description']='10ml Amber Bottles Only';
			if (preg_match('/^bot-01$/i',$transaction['code'])   and preg_match('/10ml Amber Bottles.already supplied/i',$transaction['description']))
				$transaction['description']='10ml Amber Bottles';
			if (preg_match('/^bag-07$/i',$transaction['code'])   and preg_match('/^Mini Bag.*mix|^Mixed Mini Bag$/i',$transaction['description']))
				$transaction['description']='Mini Bag - Mix';
			if (preg_match('/^bag-07$/i',$transaction['code'])   and preg_match('/Mini Bag .replacement/i',$transaction['description']))
				$transaction['description']='Mini Bag';
			if (preg_match('/^bag-07$/i',$transaction['code'])   and preg_match('/Mini Organza Bags Mixed|Organza Mini Bag . Mix/i',$transaction['description']))
				$transaction['description']='Organza Mini Bag - Mixed';
			if (preg_match('/^bag-02$/i',$transaction['code']))
				$transaction['description']='Organza Bags';
			if (preg_match('/^bag-02a$/i',$transaction['code'])   and preg_match('/gold/i',$transaction['description']))
				$transaction['description']='Organza Bag - Gold';
			if (preg_match('/^bag-02a$/i',$transaction['code'])   and preg_match('/misc|mix|showroom/i',$transaction['description']))
				$transaction['description']='Organza Bag - Mix';
			if (preg_match('/^bag-07a$/i',$transaction['code'])   and preg_match('/misc|mix|showroom/i',$transaction['description']))
				$transaction['description']='Organza Mini Bag - Mix';
			if (preg_match('/^bag$/i',$transaction['code']) )
				$transaction['description']='Organza Bag - Mix';
			if (preg_match('/^eid-04$/i',$transaction['code']) )
				$transaction['description']='Nag Champa 15g';
			if (preg_match('/^ish-13$/i',$transaction['code'])   and preg_match('/Smoke Boxes Natural/i',$transaction['description'])   )
				$transaction['description']='Smoke Boxes Natural';
			if (preg_match('/^asoap-09$/i',$transaction['code'])   and preg_match('/maychang-orange tint old showrooms/i',$transaction['description'])   )
				$transaction['description']='May Chang - Orange -EO Soap Loaf';
			if (preg_match('/^asoap-02$/i',$transaction['code'])   and preg_match('/old showrooms/i',$transaction['description'])   )
				$transaction['description']='Tea Tree - Green -EO Soap Loaf';

			if (preg_match('/^wsl-1039$/i',$transaction['code'])     )
				$transaction['description']='Arty Coffee Twist Candle 24cm';
			if (preg_match('/^joie-01$/i',$transaction['code'])   and preg_match('/assorted/i',$transaction['description'])    )
				$transaction['description']='Joie Boxed - Assorted';
			if (preg_match('/^wsl-01$/i',$transaction['code'])   and preg_match('/Mixed packs of Incense.Shipp.cost covered./i',$transaction['description'])    )
				$transaction['description']='Mixed packs of Incense';
			if (preg_match('/^gp-01$/i',$transaction['code'])   and preg_match('/^(Glass Pebbles Assorted|Glass Pebbles mixed colours|Glass Pebbles-Mixed)$/i',$transaction['description'])    )
				$transaction['description']='Glass Pebbles mixed colours';

			if (preg_match('/^HemM-01$/i',$transaction['code'])   and preg_match('/Pair of Hermatite Magnets/i',$transaction['description'])    )
				$transaction['description']='Pair of Hematite Magnets';

			if (preg_match('/Box of 6 Nightlights -Flower Garden/i',$transaction['description'])    )
				$transaction['description']='Box of 6 Nightlights - Flower Garden';


			if (preg_match('/Grip Seal Bags 4 x 5.5 inch/i',$transaction['description'])    )
				$transaction['description']='Grip Seal Bags 4x5.5inch';

			if (preg_match('/^FW-01$/i',$transaction['code'])  ) {
				if (preg_match('/gift|alter/i',$transaction['description'])) {
					$transaction['code']='FW-04';
				}
				elseif (preg_match('/white/i',$transaction['description'])) {
					$transaction['code']='FW-02';
					$transaction['description']='Promo Wine White';
				}
				elseif (preg_match('/rose/i',$transaction['description'])) {
					$transaction['code']='FW-03';
					$transaction['description']='Promo Wine Rose';
				}
				elseif (preg_match('/red/i',$transaction['description'])) {
					$transaction['description']='Promo Wine Red';
				}
				elseif (preg_match('/Veuve/i',$transaction['description'])) {
					$transaction['description']='Veuve Clicquote Champagne';
				}
				elseif (preg_match('/champagne/i',$transaction['description'])) {
					$transaction['description']='Champagne';
				}
			}
			if (preg_match('/^FW-02$/i',$transaction['code'])  ) {
				if (preg_match('/gift|alter/i',$transaction['description'])) {
					$transaction['code']='FW-04';
				}
				elseif (preg_match('/white/i',$transaction['description'])) {
					$transaction['code']='FW-02';
					$transaction['description']='Promo Wine White';
				}
				elseif (preg_match('/rose/i',$transaction['description'])) {
					$transaction['code']='FW-03';
					$transaction['description']='Promo Wine Rose';
				}
				elseif (preg_match('/red/i',$transaction['description'])) {
					$transaction['code']='FW-01';
					$transaction['description']='Promo Wine Red';

				}
				elseif (preg_match('/Veuve/i',$transaction['description'])) {
					$transaction['code']='FW-01';
					$transaction['description']='Veuve Clicquote Champagne';
				}
				elseif (preg_match('/champagne/i',$transaction['description'])) {
					$transaction['code']='FW-01';
					$transaction['description']='Champagne';
				}
			}
			if (preg_match('/^FW-03$/i',$transaction['code'])  ) {
				if (preg_match('/gift|alter/i',$transaction['description'])) {
					$transaction['code']='FW-04';
				}
				elseif (preg_match('/white/i',$transaction['description'])) {
					$transaction['code']='FW-02';
					$transaction['description']='Promo Wine White';
				}
				elseif (preg_match('/rose/i',$transaction['description'])) {
					$transaction['code']='FW-03';
					$transaction['description']='Promo Wine Rose';
				}
				elseif (preg_match('/red/i',$transaction['description'])) {
					$transaction['code']='FW-01';
					$transaction['description']='Promo Wine Red';

				}
				elseif (preg_match('/Veuve/i',$transaction['description'])) {
					$transaction['code']='FW-01';
					$transaction['description']='Veuve Clicquote Champagne';
				}
				elseif (preg_match('/champagne/i',$transaction['description'])) {
					$transaction['code']='FW-01';
					$transaction['description']='Champagne';
				}
			}

			if (preg_match('/^FW-04$/i',$transaction['code'])  ) {
				$transaction['description']=preg_replace('/^Alternative Gift\s*\/\s*/i','Alternative Gift to Wine: ',$transaction['description']);
				$transaction['description']=preg_replace('/^Gift\s*(\:|\-)\*/i','Alternative Gift to Wine: ',$transaction['description']);
				$transaction['description']=preg_replace('/^Alternative Gift to Wine(\-|\/)/i','Alternative Gift to Wine: ',$transaction['description']);
				$transaction['description']=preg_replace('/^Alternative Gift\s*(\:|\-)\s*/i','Alternative Gift to Wine: ',$transaction['description']);
				$transaction['description']=preg_replace('/^Alternative Gift to Wine\s*(\-)\*/i','Alternative Gift to Wine: ',$transaction['description']);
				$transaction['description']=preg_replace('/Alternative Gift to Wine (\:|\-)/i','Alternative Gift to Wine: ',$transaction['description']);

				if (preg_match('/sim|Alternative Gift to Wine. 1x sg mixed box|SG please|Mix SG/i',$transaction['description'])) {
					$transaction['description']='Alternative Gift to Wine: 1 box of simmering granules';
				}
				if (preg_match('/^(gift|Promo Alternative to wine|Alternative|Alternative Gift|Alternative Gift .from prev order)$|order/i',$transaction['description'])) {
					$transaction['description']='Alternative Gift to Wine';
				}



			}
			if (!is_numeric($transaction['units']))
				$transaction['units']=1;
			if ($transaction['price']>0) {
				$margin=$transaction['supplier_product_cost']*$transaction['units']/$transaction['price'];
				if ($margin>1 or $margin<0.01) {
					$transaction['supplier_product_cost']=0.4*$transaction['price']/$transaction['units'];
				}
			}
			$supplier_product_cost=sprintf("%.4f",$transaction['supplier_product_cost']);
			// print_r($transaction);





			$transaction['supplier_product_code']=_trim($transaction['supplier_product_code']);
			$transaction['supplier_product_code']=preg_replace('/^\"\s*/','',$transaction['supplier_product_code']);
			$transaction['supplier_product_code']=preg_replace('/\s*\"$/','',$transaction['supplier_product_code']);


			if (preg_match('/\d+ or more|\d|0.10000007|0.050000038|0.150000076|0.8000006103|1.100000610|1.16666666|1.650001220|1.80000122070/i',$transaction['supplier_product_code']))
				$transaction['supplier_product_code']='';
			if (preg_match('/^(\?|new|0.25|0.5|0.8|8.0600048828125|0.8000006103|01 Glass Jewellery Box|1|0.1|0.05|1.5625|10|\d{1,2}\s?\+\s?\d{1,2}\%)$/i',$transaction['supplier_product_code']))
				$transaction['supplier_product_code']='';
			if ($transaction['supplier_product_code']=='same')
				$transaction['supplier_product_code']=$transaction['code'];




			if ($transaction['supplier_product_code']=='')
				$transaction['supplier_product_code']='?'.$transaction['code'];

			if ($transaction['supplier_product_code']=='SSK-452A' and $transaction['supplier_code']=='Smen')
				$transaction['supplier_product_code']='SSK-452A bis';

			if (preg_match('/^(StoneM|Smen)$/i',$transaction['supplier_code'])) {
				$transaction['supplier_code']='StoneM';
			}

			if (preg_match('/Ackerman|Ackerrman|Akerman/i',$transaction['supplier_code'])) {
				$transaction['supplier_code']='Ackerman';
			}

			if (preg_match('/Ashoke/i',$transaction['supplier_code'])) {
				$transaction['supplier_code']='Asoke';
			}


			if ( preg_match('/\d/',$transaction['supplier_code']) ) {
				$transaction['supplier_code'] ='';
				$supplier_product_cost='';
			}
			if (preg_match('/^(SG|FO|EO|PS|BO)\-/i',$transaction['code']))
				$transaction['supplier_code'] ='AW';
			if ($transaction['supplier_code']=='AW')
				$transaction['supplier_product_code']=$transaction['code'];
			if ($transaction['supplier_code']=='' or preg_match('/\d/',$transaction['supplier_code']) )
				$transaction['supplier_code']='Unknown';
			$unit_type='Piece';
			$description=_trim($transaction['description']);
			$description=str_replace("\\\"","\"",$description);
			if (preg_match('/Joie/i',$description) and preg_match('/abpx-01/i',$transaction['code']))
				$description='2 boxes joie (replacement due out of stock)';



			// print_r($transaction);

			if (is_numeric($transaction['w'])) {

				if ($transaction['w']<0.001 and $transaction['w']>0)
					$w=0.001*$transaction['units'];
				else
					$w=sprintf("%.3f",$transaction['w']*$transaction['units']);
			} else
				$w='';
			$transaction['supplier_product_code']=_trim($transaction['supplier_product_code']);


			if ($transaction['supplier_product_code']=='' or $transaction['supplier_product_code']=='0')
				$sup_prod_code='?'._trim($transaction['code']);
			else
				$sup_prod_code=$transaction['supplier_product_code'];


			if (preg_match('/GP-\d{2}/i',$transaction['code']) and $transaction['units']==1200) {
				$transaction['units']=1;
				$w=6;
				$supplier_product_cost=4.4500;
				$transaction['rrp']=60;
			}



			if ($transaction['units']=='' or $transaction['units']<=0) {
				print "Warning, no units data\n";
				$transaction['units']=1;
			}
			$transaction['original_price']=$transaction['price'];
			if (!is_numeric($transaction['price']) or $transaction['price']<=0) {
				//       print "Price Zero ".$transaction['code']."\n";
				$transaction['price']=0;
			}

			if (!is_numeric($supplier_product_cost)  or $supplier_product_cost<=0 ) {

				if (preg_match('/Catalogue/i',$description)) {
					$supplier_product_cost=.25;
				}
				elseif ($transaction['price']==0) {
					$supplier_product_cost=.20;
				}
				else {
					$supplier_product_cost=0.4*$transaction['price']/$transaction['units'];
					//print_r($transaction);
					//  print $transaction['code']." assuming supplier cost of 40% $supplier_product_cost **\n";
				}



			}

			$fam_key=$fam_no_fam_key;
			$dept_key=$dept_no_dept_key;
			if (preg_match('/^pi-|catalogue|^info|Mug-26x|OB-39x|SG-xMIXx|wsl-1275x|wsl-1474x|wsl-1474x|wsl-1479x|^FW-|^MFH-XX$|wsl-1513x|wsl-1487x|wsl-1636x|wsl-1637x/i',_trim($transaction['code']))) {
				$fam_key=$fam_promo_key;
				$dept_key=$dept_promo_key;
			}


			$__code=preg_split('/-/',_trim($transaction['code']));
			$__code=$__code[0];
			$sql=sprintf('select * from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s'
				,$store_key
				,prepare_mysql($__code));
			$result=mysql_query($sql);
			if ( ($__row=mysql_fetch_array($result, MYSQL_ASSOC))) {
				$fam_key=$__row['Product Family Key'];
				$dept_key=$__row['Product Family Main Department Key'];
			}

			$code=_trim($transaction['code']);


			//      print_r($transaction);


			//creamos una supplier parrt nueva
			$scode=$sup_prod_code;
			$scode=_trim($scode);
			$scode=preg_replace('/^\"\s*/','',$scode);
			$scode=preg_replace('/\s*\"$/','',$scode);
			if (preg_match('/\d+ or more|0.10000007|8.0600048828125|0.050000038|0.150000076|0.8000006103|1.100000610|1.16666666|1.650001220|1.80000122070/i',$scode))
				$scode='';
			if (preg_match('/^(\?|new|\d|0.25|0.5|0.8|0.8000006103|01 Glass Jewellery Box|1|0.1|0.05|1.5625|10|\d{1,2}\s?\+\s?\d{1,2}\%)$/i',$scode))
				$scode='';
			if ($scode=='same')
				$scode=$code;
			if ($scode=='' or $scode=='0')
				$scode='?'.$code;



			$product_data=array(
				'Product Store Key'=>$store_key
				,'Product Main Department Key'=>$dept_key
				,'product sales type'=>'Not for Sale'
				,'product locale'=>'pl_PL'
				,'Product Currency'=>'PLN'
				,'product type'=>'Normal'
				,'product record type'=>'Normal'
				,'Product Web Configuration'=>'Offline'
				,'Product Family Key'=>$fam_key
				,'product code'=>$code
				,'product name'=>$description
				,'product unit type'=>$unit_type
				,'product units per case'=>$transaction['units']
				,'product net weight'=>$w
				,'product gross weight'=>$w
				,'part gross weight'=>$w
				,'product rrp'=>sprintf("%.2f",$transaction['rrp']*$transaction['units'])
				,'product price'=>sprintf("%.2f",$transaction['price'])
				,'supplier code'=>_trim($transaction['supplier_code'])
				,'supplier name'=>_trim($transaction['supplier_code'])
				,'supplier product cost'=>$supplier_product_cost
				,'supplier product code'=>$sup_prod_code
				,'supplier product name'=>$description
				,'auto_add'=>true
				,'product valid from'=>$date_order
				,'product valid to'=>$date2
				,'editor'=>array('Date'=>$date_order)
			);


			// print "$code\n";
			$product=new Product('find',$product_data,'create');
			if (!$product->id) {
				print_r($product_data);
				print "Error inserting a product\n";
				print "->End.(GO PL) ".date("r")."\n";
				exit;
			}


			if (!$product->found_in_code or !$product->found_in_store) {
				$sql=sprintf("update `Product Dimension` set `Product Record Type`='Normal',`Product Availability Type`='Discontinued' , `Product Sales Type`='Not for Sale' where `Product ID`=%d ",$product->pid);
			}
			elseif ($product->found_in_id) {
				$sql=sprintf("update `Product Dimension` set `Product Record Type`='Historic' , `Product Sales Type`='Public Sale' where `Product ID`=%d ",$product->pid);

			}
			elseif ($product->found_in_key) {

			} else {
				$sql=sprintf("update `Product Dimension` set  `Product Record Type`='Normal',`Product Availability Type`='Discontinued' , `Product Sales Type`='Not for Sale' where `Product ID`=%d ",$product->pid);

			}



			$supplier_code=_trim($transaction['supplier_code']);
			if ($supplier_code=='' or $supplier_code=='0' or  preg_match('/^costa$/i',$supplier_code))
				$supplier_code='Unknown';
			$supplier=new Supplier('code',$supplier_code);
			if (!$supplier->id) {
				$the_supplier_data=array(
					'Supplier Name'=>$supplier_code,
					'Supplier Code'=>$supplier_code,
					'editor'=>$editor
				);

				if ( $supplier_code=='Unknown'  ) {
					$the_supplier_data=array(
						'Supplier Name'=>'Unknown Supplier',
						'Supplier Code'=>$supplier_code,
						'editor'=>$editor
					);
				}

				$supplier=new Supplier('find',$the_supplier_data,'create update');
			}

			$part_list=array();

			$parts_per_product=1;


			$part_list=$product->get_all_part_skus();
		$number_parts=count($part_list);
			
			if ($number_parts==0 ) {

				$uk_product=new Product('code_store',$code,1);
				$parts=$uk_product->get('Parts SKU');


				if (isset($parts[0])) {
					// print "found part \n";
					$part=new Part('sku',$parts[0]);


					$part_list[]=array(

						'Part SKU'=>$part->get('Part SKU'),

						'Parts Per Product'=>$parts_per_product,
						'Product Part Type'=>'Simple'

					);
					$product_part_header=array(
						'Product Part Valid From'=>$date_order,
						'Product Part Valid To'=>$date2,
						'Product Part Most Recent'=>'Yes',
						'Product Part Type'=>'Simple'

					);





				}
				else {

					//creamos una parte nueva
					$part_data=array(
						'Part Status'=>'Not In Use',
						'Part XHTML Currently Supplied By'=>sprintf('<a href="supplier.php?id=%d">%s</a>',$supplier->id,$supplier->get('Supplier Code')),
						'Part XHTML Currently Used In'=>sprintf('<a href="product.php?id=%d">%s</a>',$product->id,$product->get('Product Code')),
						'Part Unit Description'=>strip_tags(preg_replace('/\(.*\)\s*$/i','',$product->get('Product XHTML Short Description'))),
						'part valid from'=>$date_order,
						'part valid to'=>$date2,
						'Part Gross Weight'=>$w
					);
					$part=new Part('new',$part_data);
					$parts_per_product=1;
					$part_list=array();

					$part_list[]=array(

						'Part SKU'=>$part->get('Part SKU'),

						'Parts Per Product'=>$parts_per_product,
						'Product Part Type'=>'Simple'

					);
					$product_part_header=array(
						'Product Part Valid From'=>$date_order,
						'Product Part Valid To'=>$date2,
						'Product Part Most Recent'=>'Yes',
						'Product Part Type'=>'Simple'

					);


				}
				// print_r($part_list);
				$product->new_historic_part_list($product_part_header,$part_list);
				$used_parts_sku=array($part->sku => array('parts_per_product'=>$parts_per_product,'unit_cost'=>$supplier_product_cost*$transaction['units']));
				$sp_data=array(
					'Supplier Key'=>$supplier->id,
					'Supplier Product Status'=>'Not In Use',
					'Supplier Product Code'=>$scode,
					'SPH Case Cost'=>sprintf("%.2f",$supplier_product_cost),
					'Supplier Product Name'=>$description,
					'Supplier Product Description'=>$description,
					'Supplier Product Valid From'=>$date_order,
					'Supplier Product Valid To'=>$date2
				);

				$supplier_product=new SupplierProduct('find',$sp_data,'create');
				$spp_header=array(
					'Supplier Product Part Type'=>'Simple',
					'Supplier Product Part Most Recent'=>'Yes',
					'Supplier Product Part Valid From'=>$date_order,
					'Supplier Product Part Valid To'=>$date2,
					'Supplier Product Part In Use'=>'Yes',
					'Supplier Product Part Metadata'=>''
				);

				$spp_list=array(
					array(
						'Part SKU'=>$part->data['Part SKU'],
						'Supplier Product Units Per Part'=>$transaction['units'],
						'Supplier Product Part Type'=>'Simple'
					)
				);
				$supplier_product->new_historic_part_list($spp_header,$spp_list);
			} else {


				$sql=sprintf("select `Part SKU`,`Parts Per Product` from `Product Part List` PPL left join `Product Part Dimension` PPD on (PPL.`Product Part Key`=PPD.`Product Part Key`)where  `Product ID`=%d  ",$product->pid);
				$res_x=mysql_query($sql);
				if ($row_x=mysql_fetch_array($res_x)) {
					$part_sku=$row_x['Part SKU'];
					$parts_per_product=$row_x['Parts Per Product'];
				} else {
					print_r($product);
					print "error: $sql";
					print "->End.(GO PL) ".date("r")."\n";
					exit();
				}
				mysql_free_result($res_x);

				$part=new Part('sku',$part_sku);
				$part->update_valid_dates($date_order);
				$part->update_valid_dates($date2);
				
				$part_list=array();
				$part_list[]=array(

					'Part SKU'=>$part->sku,

					'Parts Per Product'=>$parts_per_product,
					'Product Part Type'=>'Simple'

				);
				//print_r($part_list);
				$product_part_key=$product->find_product_part_list($part_list);
				if (!$product_part_key) {
					print "Error can not find product part list (get_orders_db)\n";
					print "->End.(GO PL) ".date("r")."\n";

					exit();
				}

				$product->update_product_part_list_historic_dates($product_part_key,$date_order,$date2);

				$used_parts_sku=array($part->sku=>array('parts_per_product'=>$parts_per_product,'unit_cost'=>$supplier_product_cost*$transaction['units']));


			}




			$sp_data=array(
				'Supplier Key'=>$supplier->id,
				'Supplier Product Status'=>'Not In Use',
				'Supplier Product Code'=>$scode,
				'SPH Case Cost'=>sprintf("%.2f",$supplier_product_cost),
				'Supplier Product Name'=>$description,
				'Supplier Product Description'=>$description,
				'Supplier Product Valid From'=>$date_order,
				'Supplier Product Valid To'=>$date2
			);

			$supplier_product=new SupplierProduct('find',$sp_data);

			$used_parts_sku[$part->sku]['supplier_product_key']=$supplier_product->id;
			$used_parts_sku[$part->sku]['supplier_product_pid']=$supplier_product->pid;
			create_dn_invoice_transactions($transaction,$product,$used_parts_sku);

		}



		$data['Order For']='Customer';

		$data['Order Main Source Type']='Unknown';
		if (  $header_data['showroom']=='Yes')
			$data['Order Main Source Type']='Store';

		$data['Delivery Note Dispatch Method']='Shipped';

		if ($header_data['collection']=='Yes') {
			$data['Delivery Note Dispatch Method']='Collected';
		}
		elseif ($header_data['shipper_code']!='') {
			$data['Delivery Note Dispatch Method']='Shipped';
		}
		elseif ($header_data['shipping']>0 or  $header_data['shipping']=='FOC') {
			$data['Delivery Note Dispatch Method']='Shipped';
		}


		if ($header_data['shipper_code']=='_OWN')
			$data['Delivery Note Dispatch Method']='Collected';

		if ($header_data['staff sale']=='Yes') {

			$data['Order For']='Staff';

		}


		if ($data['Delivery Note Dispatch Method']=='Collected') {
			$_customer_data['has_shipping']=false;
			$shipping_addresses=array();
		}

		// print_r($_customer_data);

		if (array_empty($shipping_addresses)) {
			$data['Delivery Note Dispatch Method']='Collected';
			$_customer_data['has_shipping']=false;
			$shipping_addresses=array();
		}




		if ($customer_data['Customer Delivery Address Link']=='Contact') {
			$_customer_data['has_shipping']=true;
			$shipping_addresses=array();
		}




		//  print_r($data);
		$data['staff sale']=$header_data['staff sale'];
		$data['staff sale key']=$header_data['staff sale key'];





		$data['products']=$products_data;
		$data['Customer Data']=$customer_data;
		$data['Shipping Address']=$shipping_addresses;
		// $data['metadata_id']=$order_data_id;
		$data['tax_rate']=.15;
		if (strtotime($date_order)<strtotime('2008-11-01'))
			$data['tax_rate']=.175;
		$currency=$__currency_code;

		chdir('../../');


		//print "$date_order $date_inv\n";

		if ($date_inv!='NULL') {
			$currency_exchange = new CurrencyExchange($__currency_code.'GBP',$date_inv);
			$exchange= $currency_exchange->get_exchange();
		}

		$currency_exchange = new CurrencyExchange($__currency_code.'GBP',$date_order);
		$exchange= $currency_exchange->get_exchange();

		if ($tipo_order==2 or $tipo_order==9)
			$exchange_date=$date_inv;
		else
			$exchange_date=$date_order;

		$currency_exchange = new CurrencyExchange($__currency_code.'GBP',$exchange_date);
		//print "=============".$__currency_code.'GBP'."->  $exchange_date ================\n";
		$exchange= $currency_exchange->get_exchange();
		chdir('mantenence/scripts/');

		if ($exchange==0) {
			print "->End.(GO PL) ".date("r")."\n";
			print "error exhange is zero for $exchange_date\n";
			exit();
		}
		list($parcels,$parcel_type)=parse_parcels($header_data['parcels']);

		//print_r($header_data);print "$tipo_order\n";

		// print_r($products_data);
		// exit;

		//Tipo order
		// 1 DELIVERY NOTE
		// 2 INVOICE
		// 3 CANCEL
		// 4 SAMPLE
		// 5 donation
		// 6 REPLACEMENT
		// 7 MISSING
		// 8 follow
		// 9 refund
		// 10 crdit
		// 11 quote


		if ($update) {
			delete_old_data();
		}


		if (count($products_data)==0 and count($credits)>0) {

			$tipo_order=9;
			if ($header_data['date_inv']=='1899-12-30' or $header_data['date_inv']=='1970-01-01') {
				$header_data['date_inv']=$header_data['date_order'];
				$date_inv=$header_data['date_inv'];
			}
		}

		/*
        $data['Order Currency']=$currency;
        $data['Order Currency Exchange']=$exchange;
        $sales_rep_data=get_user_id($header_data['takenby'],true,'&view=processed',$header_data['order_num'],$editor);
        $data['Order XHTML Sale Reps']=$sales_rep_data['xhtml'];
        $data['Order Customer Contact Name']=$customer_data['Customer Main Contact Name'];
        $data['Order Sale Reps IDs']=$sales_rep_data['id'];
        $data['Order Currency']=$currency;
        $data['Order Currency Exchange']=$exchange;
        */



		$data['editor']=$editor;

		get_data($header_data);
		$tax_category_object=get_tax_code($store_code,$header_data);
		$data['Customer Data']['Customer Tax Category Code']=$tax_category_object->data['Tax Category Code'];
		$data['Customer Data']['editor']=$data['editor'];
		$data['Customer Data']['editor']['Date']=date("Y-m-d H:i:s",strtotime($data['Customer Data']['editor']['Date']." -1 second"));


		$customer_done=false;
		$customer_posible_key=0;
		if ($customer_key_from_order_data) {
			print "use prev ";
			$customer_posible_key=$customer_key_from_order_data;
			$customer = new Customer($customer_key_from_order_data);
			$customer_done=true;
		}
		else if (isset($act_data['customer_id_from_inikoo'])  and $act_data['customer_id_from_inikoo'] and (strtotime($date_order)>strtotime('2011-04-01')) ) {
				$customer_posible_key=$act_data['act'];
				$customer = new Customer($act_data['act']);
				$customer_done=true;
			}

		if ($customer_posible_key) {
			if (!$customer->id) {
				$sql=sprintf("select * from `Customer Merge Bridge` where `Merged Customer Key`=%d",$customer_posible_key);
				$res2=mysql_query($sql);
				if ($row2=mysql_fetch_assoc($res2)) {
					$customer=new Customer($row2['Customer Key']);
					$customer_done=true;
				}
			}
		}


		if (!$customer_done or !$customer->id) {

			$customer = new Customer ( 'find', $data['Customer Data'] );
		}

		if (!$customer->id) {
			$customer = new Customer ( 'find create', $data['Customer Data'] );
		}






		if (!$customer->id) {
			print "Error !!!! customer not found\n";
			continue;
		}

		if ($customer->data['Customer Store Key']!=$store->id) {
			print "Error !!!! customer from another store\n";
			continue;
		}

		$sql=sprintf("update pl_orders_data.orders set customer_id=%d where id=%d",$customer->id,$order_data_id);
		mysql_query($sql);


		if ($customer_data['Customer Delivery Address Link']=='None') {
			$shipping_addresses['Address Input Format']='3 Line';
			//print_r($shipping_addresses);
			$address=new Address('find in customer '.$customer->id." create update",$shipping_addresses);
			$customer->create_delivery_address_bridge($address->id);
		}


		$country=new Country('find',$data['Customer Data']['Customer Address Country Name']);

		$shipping_addresses['Ship To Line 1']=$data['Customer Data']['Customer Address Line 1'];
		$shipping_addresses['Ship To Line 2']=$data['Customer Data']['Customer Address Line 2'];
		$shipping_addresses['Ship To Line 3']=$data['Customer Data']['Customer Address Line 3'];
		$shipping_addresses['Ship To Town']=$data['Customer Data']['Customer Address Town'];
		$shipping_addresses['Ship To Postal Code']=$data['Customer Data']['Customer Address Postal Code'];
		$shipping_addresses['Ship To Country Code']=$country->data['Country Code'];
		$shipping_addresses['Ship To Country Name']=$country->data['Country Name'];
		$shipping_addresses['Ship To Country Key']=$country->id;
		$shipping_addresses['Ship To Country 2 Alpha Code']=$country->data['Country 2 Alpha Code'];
		$shipping_addresses['Ship To Country First Division']=$data['Customer Data']['Customer Address Country First Division'];
		$shipping_addresses['Ship To Country Second Division']=$data['Customer Data']['Customer Address Country Second Division'];

		$ship_to= new Ship_To('find create',$shipping_addresses);

		if ($ship_to->id) {

			$customer->associate_ship_to_key($ship_to->id,$date_order,false);
			$data['Order Ship To Key']=$ship_to->id;

		} else {
			print "->End.(GO PL) ".date("r")."\n";
			print "no ship tp in de_get_otders shit\n";
			exit();
		}

		$data['Order Customer Key']=$customer->id;
		$customer_key=$customer->id;

		switch ($tipo_order) {
		case 1://Delivery Note
			print "DN";
			$data['Order Type']='Order';
			create_order($data);
			if (strtotime('today -6 month')>strtotime($date_order)) {
				$order->suspend(_('Order automatically suspended'),date("Y-m-d H:i:s",strtotime($date_order." +6 month")));
			}
			//if (strtotime('today -6 month')>strtotime($date_order)) {
		//		$order->cancel(_('Order automatically cancelled'),date("Y-m-d H:i:s",strtotime($date_order." +6 month")));
		//	}

			break;
		case 2://Invoice
		case 8: //follow
			print "INV";
			$data['Order Type']='Order';

			create_order($data);

			send_order($data,$data_dn_transactions);
			break;
		case 3://Cancel
			print "Cancel";
			$data['Order Type']='Order';
			create_order($data);
			$order->cancel('',$date_order);
			break;
		case 4://Sample
			print "Sample";

			$data['Order Type']='Sample';
			create_order($data);
			send_order($data,$data_dn_transactions);
			break;
		case 5://Donation
			print "Donation";

			$data['Order Type']='Donation';
			create_order($data);
			send_order($data,$data_dn_transactions);
			break;
		case(6)://REPLACEMENT
		case(7)://MISSING
			print "RPL/MISS ";
			create_post_order($data,$data_dn_transactions);
			send_order($data,$data_dn_transactions);

			break;
		case(9)://Refund
			print "Refund ";

			create_refund($data,$header_data, $data_dn_transactions);
			break;
		default:
			print "Unknown ".$header_data['ltipo'];

			break;
		}


		$customer->update_orders();
		$store->update_customer_activity_interval();
		$customer->update_activity();
		$customer->update_is_new();
		$store->update_orders();
		$store->update_customers_data();

		$store->update_up_today_sales();
		$store->update_last_period_sales();
		$store->update_interval_sales();




		print "\n";
		$sql="update pl_orders_data.orders set last_transcribed=NOW() where id=".$order_data_id;
		mysql_query($sql);






		if ($contador % $calculate_no_normal_every  == 0) {
			update_data($to_update);
			$to_update=array(
				'products'=>array(),
				'products_id'=>array(),
				'products_code'=>array(),
				'families'=>array(),
				'departments'=>array(),
				'stores'=>array(),
				'parts'=>array()

			);


		}
	}
	mysql_free_result($result);
}
mysql_free_result($res);
update_data($to_update);

print "->End.(GO PL) ".date("r")."\n";

//  print_r($data);
//print "\n$tipo_order\n";

function update_data($to_update) {

	if (false) {
		$tm=new TimeSeries(array('q','invoices'));
		$tm->to_present=true;
		$tm->get_values();
		$tm->save_values();
		$tm=new TimeSeries(array('m','invoices'));

		$tm->to_present=true;
		$tm->get_values();
		$tm->save_values();

		$tm=new TimeSeries(array('y','invoices'));
		$tm->to_present=true;
		$tm->get_values();
		$tm->save_values();


		foreach ($to_update['products'] as $key=>$value) {
			$product=new Product($key);
			$product->load('sales');


		}

	}

	if (false) {
		foreach ($to_update['products_id'] as $key=>$value) {

			$tm=new TimeSeries(array('m','product id ('.$key.') sales'));
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('y','product id ('.$key.') sales'));
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('q','product id ('.$key.') sales'));
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('m','product id ('.$key.') profit'));
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('y','product id ('.$key.') profit'));
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('q','product id ('.$key.') profit'));
			$tm->get_values();
			$tm->save_values();


		}
	}



	foreach ($to_update['families'] as $key=>$value) {
		$product=new Family($key);
		$product->load('sales');
		if (false) {
			// $tm=new TimeSeries(array('m','family ('.$key.') sales'));
			// $tm->get_values();
			// $tm->save_values();
			// $tm=new TimeSeries(array('y','family ('.$key.') sales'));
			// $tm->get_values();
			// $tm->save_values();
			// $tm=new TimeSeries(array('q','family ('.$key.') sales'));
			// $tm->get_values();
			// $tm->save_values();
			$tm=new TimeSeries(array('m','family ('.$key.') profit'));
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('y','family ('.$key.') profit'));
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('q','family ('.$key.') profit'));
			$tm->get_values();
			$tm->save_values();
		}
	}
	foreach ($to_update['departments'] as $key=>$value) {
		$product=new Department($key);
		$product->load('sales');
		if (false) {
			$tm=new TimeSeries(array('m','department ('.$key.') sales'));
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('y','department ('.$key.') sales'));
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('q','department ('.$key.') sales'));
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('m','department ('.$key.') profit'));
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('y','department ('.$key.') profit'));
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('q','department ('.$key.') profit'));
			$tm->get_values();
			$tm->save_values();
		}
	}
	foreach ($to_update['stores'] as $key=>$value) {
		$product=new Store($key);
		$product->load('sales');
		if (false) {
			$tm=new TimeSeries(array('m','store ('.$key.') sales'));
			$tm->to_present=true;
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('y','store ('.$key.') sales'));
			$tm->to_present=true;
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('q','store ('.$key.') sales'));
			$tm->to_present=true;
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('m','store ('.$key.') profit'));
			$tm->to_present=true;
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('y','store ('.$key.') profit'));
			$tm->to_present=true;
			$tm->get_values();
			$tm->save_values();
			$tm=new TimeSeries(array('q','store ('.$key.') profit'));
			$tm->to_present=true;
			$tm->get_values();
			$tm->save_values();
		}
	}
	foreach ($to_update['parts'] as $key=>$value) {
		$product=new Part('sku',$key);
		$product->load('sales');
	}

	printf("updated P:%d F%d D%d S%d\n"
		,count($to_update['products'])
		,count($to_update['families'])
		,count($to_update['departments'])
		,count($to_update['stores'])

	);
}



function is_same_address($data) {
	$address1=$data['address_data'];
	$address2=$data['shipping_data'];
	unset($address1['telephone']);
	unset($address2['telephone']);
	unset($address2['email']);
	unset($address1['company']);
	unset($address2['company']);
	unset($address1['name']);
	unset($address2['name']);
	//  print_r($address1);
	//print_r($address2);

	if ($address1==$address2)
		return true;
	else
		return false;







}






?>
