<?php
//error_reporting(E_ALL);
error_reporting(0);
ini_set( 'display_errors', 0 );
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Order.php';

include_once '../../class.Invoice.php';
include_once '../../class.DeliveryNote.php';
include_once '../../class.Email.php';
include_once '../../class.TimeSeries.php';
include_once '../../class.CurrencyExchange.php';
include_once '../../class.TaxCategory.php';
include_once '../../class.PartLocation.php';
include_once '../../class.Deal.php';

include_once 'common_read_orders_functions.php';


function microtime_float() {
	list($utime, $time) = explode(" ", microtime());
	return (float)$utime + (float)$time;
}


$myFile = "orders_time.txt";
$fh = fopen($myFile, 'w') or die("can't open file");
$time_data=array();
$orders_done=0;
$store_code='U';
$__currency_code='GBP';

$calculate_no_normal_every =10000;
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
	print "->End.(GO UK) ".date("r")."\n";
	exit;
}

//$dns_db='dw_avant2';


$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	print "->End.(GO UK) ".date("r")."\n";
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

$currency='GBP';
$_SESSION['lang']=1;

include_once 'local_map.php';
include_once 'map_order_functions.php';
print "->Start.(GO UK) ".date("r")."\n";

$software='Get_Orders_DB.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";
srand(12344);

$store=new Store("code","UK");
$store_key=$store->id;

$dept_no_dept=new Department('code','ND_UK',$store_key);
$dept_no_dept_key=$dept_no_dept->id;
$dept_promo=new Department('code','Promo_UK',$store_key);
$dept_promo_key=$dept_promo->id;



$fam_no_fam=new Family('code','PND_UK',$store_key);
$fam_no_fam_key=$fam_no_fam->id;
$fam_promo=new Family('code','Promo_UK',$store_key);
$fam_promo_key=$fam_promo->id;




$sql="select * from  orders_data.orders  where   deleted='Yes'    ";
$res=mysql_query($sql);
while ($row2=mysql_fetch_array($res, MYSQL_ASSOC)) {
	$order_data_id=$row2['id'];
	delete_old_data();
}
error_reporting(0);
ini_set( 'display_errors', 0 );


$orders_data_id='';
$sql="select `Invoice Metadata` from `Invoice Dimension` where `Invoice Paid`='Parcially'  and `Invoice Store Key`=1";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$xxx=preg_replace('/U/','',$row['Invoice Metadata']);
	if($xxx!='')
	$orders_data_id.=','.$xxx;
	
}


$orders_data_id='';
$sql="select * from `Delivery Note Dimension` where `Delivery Note State`=''  and `Delivery Note Store Key`=1";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$xxx=preg_replace('/U/','',$row['Delivery Note Metadata']);
	if($xxx!='')
	$orders_data_id.=','.$xxx;
	
}

$sql="select * from `Order Dimension` where  `Order Cancel Note` LIKE 'Order automatically cancelled'  and `Order Store Key`=1";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$xxx=preg_replace('/U/','',$row['Order Original Metadata']);
	if($xxx!='')
	$orders_data_id.=','.$xxx;
	
}
$sql="select * from `Order Dimension` where   `Order Suspend Note` LIKE 'Order automatically suspended'  and `Order Store Key`=1";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$xxx=preg_replace('/U/','',$row['Order Original Metadata']);
//	if($xxx!='')
//	$orders_data_id.=','.$xxx;
	
}


$sql="select * from `Order Dimension` where  `Order Current Dispatch State`='In Process' and `Order Store Key`=1";
$result=mysql_query($sql);
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
	$xxx=preg_replace('/U/','',$row['Order Original Metadata']);
	if($xxx!='')
	$orders_data_id.=','.$xxx;
	
}

$orders_data_id=preg_replace('/^,/','',$orders_data_id);


$sql="select *,replace(   replace(replace(replace(replace(replace(replace(replace(replace(filename,'r/Orders/','r/Orders/000'),'s/Orders/','s/Orders/00'),'y/Orders/','y/Orders/00'),'z/Orders/9','z/Orders/009'),'x/Orders/','x/Orders/00'),'t/Orders/','t/Orders/00'),'u/Orders/','u/Orders/00'),'z/Orders/8','z/Orders/008')     ,directory,'') as name from  orders_data.orders  where   deleted='No' and  id in (".$orders_data_id.")   order by name  ";
//print $sql;
//exit;
//and ( filename like '%/b/%.xls' or filename like '%/a/%.xls' or  filename like '%/c/%.xls') order by name  ";
//and ( filename like '%/b/%.xls' or filename like '%/a/%.xls' or  filename like '%/c/%.xls' )

//and ( filename like '%/b/%.xls' or filename like '%/a/%.xls' or  filename like '%/c/%.xls' )


//$sql="select * from  orders_data.orders  where    (last_transcribed is NULL  or last_read>last_transcribed) and deleted='No'  order by filename ";
//$sql="select * from  orders_data.orders where filename  like '%/137073.xls' order by filename";
//$sql="select * from  orders_data.orders where filename like '%/94090.xls'   order by filename";
//120239
//120217
//$sql="select * from  orders_data.orders where filename like '%/15165%.xls'   order by filename";

//$sql="select * from  orders_data.orders where filename like '%/%ref%.xls'   order by filename";
//$sql="select * from  orders_data.orders  where filename like '/mnt/%/Orders/93284.xls' order by filename";
//$sql="select * from  orders_data.orders  where (filename like '/mnt/%/Orders/7318.xls' )or(filename like '/mnt/%/Orders/7530.xls' )order by filename";

//$sql="select * from  orders_data.orders  where filename like '/mnt/%/Orders/15720.xls' or filename like '/mnt/%/Orders/60000.xls' or  filename like '/mnt/%/Orders/15sdfsd593.xls' order by filename";

//$sql="select *,orders_data.orders.id as id  from dw2.`Order Transaction Fact` X left join  orders_data.orders on (orders_data.orders.id=REPLACE(`Metadata`,'U',''))  where `Customer Key` in (729,11701) group by `Metadata` order by filename;";
$contador=0;
//print $sql;
$res=mysql_query($sql);
while ($row2=mysql_fetch_array($res, MYSQL_ASSOC)) {

	$discounts_with_order_as_term=array();

	$customer_key_from_order_data=$row2['customer_id'];
	$customer_key_from_excel_order=0 ;

	$sql="select * from orders_data.data where id=".$row2['id'];
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$order_data_id=$row2['id'];
		$filename=$row2['filename'];
		$contador++;
		$total_credit_value=0;
		$update=false;
		$old_order_key=0;


		//if($contador>50)
		//exit;

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

		if ($filename_number==19015) {
			$y_map['code']=4;
		}


		if ($filename_number<18803) {// Change map if the orders are old
			$y_map=$_y_map_old;
			foreach ($_map_old as $key=>$value)
				$map[$key]=$value;
		}
		$prod_map=$y_map;
		if ($filename_number==53378) {
			$prod_map['no_price_bonus']=true;
			$prod_map['no_reorder']=true;
			$prod_map['bonus']=11;
		}
		elseif ($filename_number==64607) {
			$prod_map['no_price_bonus']=true;
			$prod_map['no_reorder']=true;
			$prod_map['bonus']=11;
		}
		if ($filename_number==89175) {
			$prod_map['no_reorder']=true;
			$prod_map['no_price_bonus']=true;
		}

		$header_data=array();
		$data=array();
		list($act_data,$header_data)=read_header($header,$map_act,$y_map,$map);
		$header_data=filter_header($header_data);
		round_header_data_totals();
		//print_r($header_data);
		list($tipo_order,$parent_order_id,$header_data)=get_tipo_order($header_data['ltipo'],$header_data);

		if (!$tipo_order)
			continue;
		if (preg_match('/^1?\d{5}sh$/i',$filename_number)) {
			$tipo_order=7;
			$parent_order_id=preg_replace('/sh/i','',$filename_number);
		}
		if (preg_match('/^1?\d{5}sht$/i',$filename_number)) {
			$tipo_order=7;
			$parent_order_id=preg_replace('/sht/i','',$filename_number);
		}

		if (preg_match('/^1?\d{5}rpl$/i',$filename_number)) {
			$tipo_order=6;
			$parent_order_id=preg_replace('/rpl/i','',$filename_number);

		}
		if (preg_match('/^1?\d{4,5}r$|^1?\d{4,5}ref$|^1?\d{4,5}\s?refund$|^1?\d{4,5}rr$|^1?\d{4,5}ra$|^1?\d{4,5}r2$|^1?\d{4,5}\-2ref$|^1?\d{5}rfn$/i',$filename_number)) {
			$tipo_order=9;
			$parent_order_id=preg_replace('/r$|ref$|refund$|rr$|ra$|r2$|\-2ref$|rfn$/i','',$filename_number);
		}

		list($date_index,$date_order,$date_inv)=get_dates($row2['timestamp'],$header_data,$tipo_order,true);
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
			print "Warning (Fecha Factura anterior Fecha Orden) $filename $date_order  $date_inv\n  ".strtotime($date_order).' > '.strtotime($date_inv)."\n";
			$date_inv=date("Y-m-d H:i:s",strtotime($date_order.' +1 hour'));
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

		if ($header_data['gold']=='Gold Reward') {
			$_deal=new Deal('code','UK.GR');
			if ($_deal->id) {
				$discounts_with_order_as_term[]=$_deal->id;
			}
		}
		
		
		
	
		

		$header_data['Order Main Source Type']='Unknown';
		$header_data['Delivery Note Dispatch Method']='Unknown';

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
		$header_data=is_staff_sale($header_data,array('Date'=>$date_order));

		$header_data=is_showroom($header_data);



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

		if (preg_match('/^(x5686842-t|IE 9575910F|85 467 757 063|ie 7214743D|ES B92544691|IE-7251185|SE556670-257601|x5686842-t)$/',$header_data['notes2'])) {
			$header_data['tax_number']=$header_data['notes2'];
			$header_data['notes2']='';
		}
		if (preg_match('/^(x5686842-t|IE 9575910F|85 467 757 063|ie 7214743D|ES B92544691|IE-7251185|SE556670-257601|x5686842-t)$/',$header_data['notes'])) {
			$header_data['tax_number']=$header_data['notes'];
			$header_data['notes']='';
		}


		$transactions=read_products($products,$prod_map);
		// print_r($transactions);
		unset($products);
		$_customer_data=setup_contact($act_data,$header_data,$date_index2,$editor);


		list($_customer_data['type'],$_customer_data['company_name'],$_customer_data['contact_name'])=parse_company_person($_customer_data['company_name'],$_customer_data['contact_name']);

		$customer_data=array();

		if (isset($header_data['tax_number']) and $header_data['tax_number']!='') {
			$customer_data['Customer Tax Number']=$header_data['tax_number'];
		}

		foreach ($_customer_data as $_key =>$value) {
			$key=$_key;
			if ($_key=='type')
				$key=preg_replace('/^type$/','Customer Type',$_key);
			if ($_key=='other id')
				$key='Customer Old ID';
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

			$customer_data[$key]=$value;

		}




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
		$shipping_addresses=array();
		$customer_data['Customer Delivery Address Link']='Contact';
		$customer_data['Customer First Contacted Date']=$date_order;

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
		if (strtotime($date_order)>strtotime($date2)) {
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
		$base_time=microtime_float();
		$orders_done++;

		$extra_shipping=0;

		$data=array();


		$data['order date']=$date_order;
		$data['order id']=$header_data['order_num'];
		$data['order customer message']=$header_data['notes2'];
		if ($data['order customer message']==0)
			$data['order customer message']='';


		$tmp_filename=preg_replace('/\/mnt\/z\/Orders\//',"\\\\\\networkspace1\\openshare\\Orders\\",$row2['filename']);
		$tmp_filename=preg_replace('/\//',"\\",$tmp_filename);

		$data['Order Original Data Filename']=$tmp_filename;
		$data['order original data mime type']='application/vnd.ms-excel';
		$data['order original data']='';
		$data['order original data source']='Excel File';

		$data['Order Original Metadata']=$store_code.$row2['id'];

		//print_r($header_data);

		$products_data=array();
		$data_invoice_transactions=array();
		$data_dn_transactions=array();
		$data_bonus_transactions=array();

		$credits=array();
		$shipping_transactions=array();
		$total_credit_value=0;
		$estimated_w=0;
		//echo "Memory: ".memory_get_usage(true) . "\n";




		foreach ($transactions as $transaction) {
			// print_r($transaction);
			$transaction['code']=_trim($transaction['code']);


			if (preg_match('/Bonus-PARTY2011/i',$transaction['code'])) {


				$_deal=new Deal('code','UK.P2011');
				if ($_deal->id) {
					$discounts_with_order_as_term[]=$_deal->id;
				}
				continue;
			}


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

					$credit_parent->skip_update_product_sales=true;
					if ($credit_parent->id) {
						$_parent_key=$credit_parent->id;
						$_parent_order_date=$credit_parent->data['Order Date'];
					}
				}

				$credits[]=array(
					'parent_key'=>$_parent_key,
					'value'=>$credit_value,
					'description'=>$credit_description,
					'parent_date'=>$_parent_order_date
				);


				continue;
			}

			if (preg_match('/Freight|^frc-|Postage|shipping/i',$transaction['code'])) {
				$transaction['code']='Freight';
				$transaction['description']='Freight Services';
				//$shipping_transactions[]=$transaction;
				//$extra_shipping+=$transaction['price'];
				//continue;

			}
			if (preg_match('/^cxd-|^wsl$|^eye$|^\d$|2009promo/i',$transaction['code']))
				continue;
			if (preg_match('/difference in prices|Diff.in price for|difference in prices/i',$transaction['description']))
				continue;

			$__code=strtolower($transaction['code']);

			if (   preg_match('/\-kit1$/i',$__code) or  preg_match('/\-minst$/i',$__code) or  preg_match('/\-Starter$/i',$__code)   or  preg_match('/\-Starter\d$/i',$__code)   or   preg_match('/^bonus\-/i',$__code)  or   preg_match('/\-st\d$/i',$__code)  or   preg_match('/\-pack$/i',$__code)  or    preg_match('/\-pst$/i',$__code)  or    preg_match('/\-kit2$/i',$__code)  or  preg_match('/\-kit1$/i',$__code)  or preg_match('/\-st$/i',$__code)  or     preg_match('/Bag-02Mx|Bag-04mx|Bag-05mx|Bag-06mix|Bag-07MX|Bag-12MX|Bag-13MX|FishP-Mix|IncIn-ST|IncB-St|LLP-ST|L\&P-ST|EO-XST|AWRP-ST/i',$__code) or       $__code=='eo-st' or $__code=='mol-st' or  $__code=='jbb-st' or $__code=='lwheat-st' or  $__code=='jbb-st'
				or $__code=='DOT-St' or $__code=='scrub-st' or $__code=='eye-st' or $__code=='tbm-st' or $__code=='tbc-st' or $__code=='tbs-st'
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

			if (preg_match('/^JuteB-17C$/i',$transaction['code'])   and preg_match('/60x Carton/i',$transaction['description'])) {
				$transaction['code']='JuteB-17CC';
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


			if (preg_match('/\d+ or more|\d|0.10000007|0\.300000152587891|0.050000038|0.150000076|0.8000006103|1.100000610|1.16666666|1.650001220|1.80000122070/i',$transaction['supplier_product_code']))
				$transaction['supplier_product_code']='';
			if (preg_match('/^(\?|new|0.25|0.5|0.8|0\.300000152587891|8.0600048828125|0.8000006103|01 Glass Jewellery Box|1|0.1|0.05|1.5625|10|\d{1,2}\s?\+\s?\d{1,2}\%)$/i',$transaction['supplier_product_code']))
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
			if (preg_match('/Ashoke/i',$transaction['supplier_code'])) {
				$transaction['supplier_code']='Asoke';
			}

			if (preg_match('/Ackerman|Ackerrman|Akerman/i',$transaction['supplier_code'])) {
				$transaction['supplier_code']='Ackerman';
			}

			if ( preg_match('/\d/',$transaction['supplier_code']) ) {
				$transaction['supplier_code'] ='';
				$supplier_product_cost='';
			}
			if (preg_match('/^(SG|FO|EO|PS|BO|EOB|AM)\-/i',$transaction['code']))
				$transaction['supplier_code'] ='AW';
			if ($transaction['supplier_code']=='AW')
				$transaction['supplier_product_code']=$transaction['code'];
			if ($transaction['supplier_code']=='' or preg_match('/\d/',$transaction['supplier_code'])
				or $transaction['supplier_code']=='?' or   preg_match('/\"[0-9]{3}/',$transaction['supplier_code']) or preg_match('/disc 20\+/i',$transaction['supplier_code'])

			)
				$transaction['supplier_code']='UNK';
			$unit_type='Piece';
			$description=_trim($transaction['description']);
			$description=str_replace("\\\"","\"",$description);
			if (preg_match('/Joie/i',$description) and preg_match('/abpx-01/i',$transaction['code']))
				$description='2 boxes joie (replacement due out of stock)';



			//print_r($transaction);

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
			/*
             if (preg_match('/GP-\d{2}/i',$transaction['code']) and $transaction['units']==350) {
                $transaction['units']=1;
                $w=1.75;
                $supplier_product_cost=;

            }
            */



			if (preg_match('/^bag-02$/i',$transaction['code'])  and  $transaction['units']==30 ) {
				$transaction['order']=$transaction['order']*30/25;
				$transaction['reorder']=$transaction['reorder']*30/25;
				$transaction['bonus']=$transaction['bonus']*30/25;

			}
			if (preg_match('/^bag-07$/i',$transaction['code'])  and  $transaction['units']==23 ) {
				$transaction['order']=$transaction['order']*30/23;
				$transaction['reorder']=$transaction['reorder']*30/23;
				$transaction['bonus']=$transaction['bonus']*30/23;

			}

			if (preg_match('/^bag-02$/i',$transaction['code'])) {
				$transaction['units']=25;
			}
			if (preg_match('/^bag-01$/i',$transaction['code'])) {
				$transaction['units']=25;
			}
			if (preg_match('/^bag-04$/i',$transaction['code'])) {
				$transaction['description']='Decorative Organza Bag - MIX';
			}
			if (preg_match('/^bag-05$/i',$transaction['code'])) {
				$transaction['description']='Organza Heart Bag - MIX';
			}



			if ($transaction['units']=='' or $transaction['units']<=0)
				$transaction['units']=1;
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




			// try to get the family
			$fam_key=$fam_no_fam_key;
			$dept_key=$dept_no_dept_key;
			if (preg_match('/^pi-|catalogue|^info|Mug-26x|OB-39x|SG-xMIXx|wsl-1275x|wsl-1474x|wsl-1474x|wsl-1479x|^FW-|^MFH-XX$|wsl-1513x|wsl-1487x|wsl-1636x|wsl-1637x/i',_trim($transaction['code']))) {
				$fam_key=$fam_promo_key;
				$dept_key=$dept_promo_key;
			}


			$__code=preg_split('/-/',_trim($transaction['code']));
			$__code=$__code[0];
			$fam_sp='';
			$sql=sprintf('select * from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s'
				,$store_key
				,prepare_mysql($__code));
			$resultxxx=mysql_query($sql);
			// print $sql;
			if ( ($__row=mysql_fetch_array($resultxxx, MYSQL_ASSOC))) {
				$fam_key=$__row['Product Family Key'];
				$dept_key=$__row['Product Family Main Department Key'];
				$fam_sp=$__row['Product Family Special Characteristic'];
			}
			mysql_free_result($resultxxx);
			$code=_trim($transaction['code']);
			$special_char=$description;

			if ($fam_sp!='') {
				$_special_char=$special_char;
				$fam_sp=preg_replace('/[^a-z^0-9^\.^\-^"^\s]/i','',$fam_sp);
				$special_char=_trim(preg_replace("/$fam_sp/",'',$special_char));
				$fam_sp=preg_replace('/s$/i','',$fam_sp);
				$special_char=_trim(preg_replace("/$fam_sp/",'',$special_char));
				if ($special_char=='')
					$special_char=$_special_char;
				//print " ==> $special_char  \n";
			}



			if ($code=='Jhex-08')
				$description='Musk';
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
				'product sales type'=>'Not for Sale',
				'product type'=>'Normal',
				'Product Locale'=>'en_GB',
				'Product Currency'=>'GBP',
				'product record type'=>'Normal',
				'Product Web Configuration'=>'Offline',

				'product special characteristic'=>$special_char,
				'Product Store Key'=>$store_key,
				'Product Main Department Key'=>$dept_key,
				'Product Family Key'=>$fam_key,
				'product code'=>$code,
				'product name'=>$description,
				'product unit type'=>$unit_type,
				'product units per case'=>$transaction['units'],
				'product net weight'=>$w,
				'product gross weight'=>$w,
				'part gross weight'=>$w,
				'product rrp'=>sprintf("%.2f",$transaction['rrp']*$transaction['units']),
				'product price'=>sprintf("%.2f",$transaction['price']),
				'supplier code'=>_trim($transaction['supplier_code']),
				'supplier name'=>_trim($transaction['supplier_code']),
				'supplier product cost'=>$supplier_product_cost,
				'supplier product code'=>$sup_prod_code,
				'supplier product name'=>$description,
				'auto_add'=>true,
				'product valid from'=>$date_order,
				'product valid to'=>$date2,
				'editor'=>$editor
			);

			$product=new Product('find',$product_data,'create');


			if (!$product->id) {
				print_r($product_data);
				print "Error inserting a product\n";
				print "->End.(GO UK) ".date("r")."\n";

				exit;
			}


			$to_update['products'][$product->id]=1;

			$to_update['products_id'][$product->data['Product ID']]=1;
			$to_update['products_code'][$product->data['Product Code']]=1;
			$to_update['families'][$product->data['Product Family Key']]=1;
			$to_update['departments'][$product->data['Product Main Department Key']]=1;
			$to_update['stores'][$product->data['Product Store Key']]=1;

			$supplier_code=_trim($transaction['supplier_code']);
			if ($supplier_code=='' or $supplier_code=='0' or   $supplier_code=='?' or preg_match('/\"[0-9]{3}/',$supplier_code) or preg_match('/disc 20\+/i',$supplier_code)   or   preg_match('/^costa$/i',$supplier_code))
				$supplier_code='UNK';
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

			unset($part);
			if ($product->new_code ) {
				//creamos una parte nueva
				$part_data=array(

					'Part Status'=>'Not In Use',
					'Part Available'=>'No',
					'Part XHTML Currently Supplied By'=>sprintf('<a href="supplier.php?id=%d">%s</a>',$supplier->id,$supplier->get('Supplier Code')),
					'Part XHTML Currently Used In'=>sprintf('<a href="product.php?id=%d">%s</a>',$product->id,$product->get('Product Code')),
					'Part Unit Description'=>$transaction['units'].'x '.$description,
					'part valid from'=>$date_order,
					'part valid to'=>$date2,
					'Part Package Weight'=>$w
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
				$product->new_historic_part_list($product_part_header,$part_list);

				$used_parts_sku=array(
					$part->sku => array(
						'parts_per_product'=>$parts_per_product,
						'unit_cost'=>$supplier_product_cost*$transaction['units']

					)

				);

				//creamos una supplier parrt nueva


				// $scode= preg_replace('/\?/i','_unk',$scode);





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
				// print "-----$scode <-------------\n";
				//print_r($sp_data);
				$supplier_product=new SupplierProduct('find',$sp_data,'create update');









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





				$products=$part->get_product_ids();
				foreach ($products as $product_pid) {
					$product=new Product ('pid',$product_pid);
					$product->update_availability_type();

				}

			}
			else {//Old Product ID



				$sql=sprintf("select `Part SKU`,`Parts Per Product` from `Product Part List` PPL left join `Product Part Dimension` PPD on (PPL.`Product Part Key`=PPD.`Product Part Key`)where  `Product ID`=%d  ",$product->pid);
				$res_x=mysql_query($sql);
				$__num_parts = mysql_num_rows($res_x);

				if ($__num_parts==1) {
					if ($row_x=mysql_fetch_array($res_x)) {
						$part_sku=$row_x['Part SKU'];
						$parts_per_product=$row_x['Parts Per Product'];
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
						if ($product_part_key) {
							//print "->End.(GO UK) ".date("r")."\n";
							//print_r($product->data);
							//print_r($part_list);
							//exit("Error can not find product part list (get_orders_db)\n");
							$product->update_product_part_list_historic_dates($product_part_key,$date_order,$date2);
						}else {
							print "Warninf part per product not found (get_orders_db)\n";
						}



						$used_parts_sku=array($part->sku=>array('parts_per_product'=>$parts_per_product,'unit_cost'=>$supplier_product_cost*$transaction['units']));



					}
					else {
						print_r($product);
						print "  $sql  ->End.(GO xxx UK) ".date("r")."\n";
						exit("error: $sql");

					}
				}else {
					while ($row_x=mysql_fetch_array($res_x)) {
						$part_sku=$row_x['Part SKU'];
						$parts_per_product=$row_x['Parts Per Product'];
						$part=new Part('sku',$part_sku);
						$part->update_valid_dates($date_order);
						$part->update_valid_dates($date2);



					}

				}


			}






			$to_update['parts'][$part->sku]=1;


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
			// print "-----$scode <-------------\n";

			$supplier_product=new SupplierProduct('find',$sp_data);

			if (!$supplier_product->id) {


				$supplier_product=new SupplierProduct('find',$sp_data,'create update');


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


			}
			$used_parts_sku[$part->sku]['supplier_product_key']=$supplier_product->id;
			$used_parts_sku[$part->sku]['supplier_product_pid']=$supplier_product->pid;
			create_dn_invoice_transactions($transaction,$product,$used_parts_sku);

		}


		//echo "Memory: ".memory_get_usage(true) . "\n";

		//print_r($header_data)

		$data['Order For']='Customer';


		if (isset($customer_data['Customer Main Plain Email']) and $customer_data['Customer Main Plain Email']=='carlos@aw-regalos.com')
			$data['Order For']='Partner';


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




		$data['type']='direct_data_injection';
		$data['products']=$products_data;
		$data['Customer Data']=$customer_data;
		$data['Shipping Address']=$shipping_addresses;
		// $data['metadata_id']=$order_data_id;
		$data['tax_rate']=.15;




		if (strtotime($date_order)<strtotime('2008-11-01') or strtotime($date_order)>strtotime('2009-12-31'))
			$data['tax_rate']=.175;
		$currency=$__currency_code;






		if ($__currency_code=='GBP') {
			$exchange=1;
		} else {
			chdir('../../');

			if ($tipo_order==2 or $tipo_order==9)
				$exchange_date=$date_inv;
			else
				$exchange_date=$date_order;


			$currency_exchange = new CurrencyExchange($__currency_code.'GBP',$exchange_date);
			$exchange= $currency_exchange->get_exchange();
			chdir('mantenence/scripts/');

			if ($exchange==0) {
				print "->End.(GO UK) ".date("r")."\n";
				exit("error exhange is zero for $exchange_date\n");

			}
		}
		// print_r($products_data);


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
		$data['editor']=$editor;

		get_data($header_data);
		$tax_category_object=get_tax_code($store_code,$header_data);
		$data['Customer Data']['Customer Tax Category Code']=$tax_category_object->data['Tax Category Code'];
		$data['Customer Data']['editor']=$data['editor'];
		$data['Customer Data']['editor']['Date']=date("Y-m-d H:i:s",strtotime($data['Customer Data']['editor']['Date']." -1 second"));

		// print_r($data);
		//  print_r($data['Customer Data']);
		if ($data['staff sale']=='Yes' and $data['staff sale key']) {
			$staff=new Staff($data['staff sale key']);
			$data['Customer Data']['Customer Type']='Person';
			$data['Customer Data']['Customer Main Contact Key']=$staff->data['Staff Contact Key'];
			$data['Customer Data']['Customer Main Contact Name']='';
			$data['Customer Data']['Customer Company Name']='';
			$data['Customer Data']['Customer Staff']='Yes';
			$data['Customer Data']['Customer Name']='';
			$data['Customer Data']['Customer Address Line 1']='';
			$data['Customer Data']['Customer Address Line 2']='';
			$data['Customer Data']['Customer Address Line 3']='';



			$data['Customer Data']['Customer Staff Key']=$staff->id;
			$data['Customer Data']['has_shipping']=false;

			$data['Delivery Note Dispatch Method']='Collected';


			//exit;
			$customer = new Customer ( 'find staff create', $staff );
			// print_r($customer);exit;
		} else {

			if ($data['staff sale']=='Yes' ) {
				print "Warning staff not identified ";
			}

			//-----------------
			$customer_done=false;
			$customer_posible_key=0;
			$customer=false;


			if (isset($act_data['customer_id_from_inikoo'])  and $act_data['customer_id_from_inikoo'] and (strtotime($date_order)>strtotime('2011-04-01')) ) {
				print "inikko ";
				$customer_posible_key=$act_data['act'];
				$customer = new Customer($act_data['act']);
				$customer_done=true;
			}
			elseif ($customer_key_from_order_data) {
				print "use prev ";
				$customer_posible_key=$customer_key_from_order_data;
				$customer = new Customer($customer_key_from_order_data);
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
				print_r($act_data);
				print "Error !!!! customer not found\n";
				continue;
			}


			if ($customer->data['Customer Store Key']!=$store->id) {
				print "Error !!!! customer from another store\n";
				continue;
			}

			//------------------------



			$sql=sprintf("update orders_data.orders set customer_id=%d where id=%d",$customer->id,$order_data_id);
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
				print "->End.(GO UK) ".date("r")."\n";
				exit("no ship tp in de_get_otders shit\n");
			}

			$data['Order Customer Key']=$customer->id;
			$customer_key=$customer->id;






		}

		$data['Order Customer Key']=$customer->id;
		$customer_key=$customer->id;

		switch ($tipo_order) {
		case 1://Delivery Note
			print "DN";
			$data['Order Type']='Order';




			$order=create_order($data);

			if (strtotime('today -6 month')>strtotime($date_order)) {
				$order->suspend(_('Order automatically suspended'),date("Y-m-d H:i:s",strtotime($date_order." +6 month")));
				print " suspended ";
			}
			//if (strtotime('today -6 month')>strtotime($date_order)) {


				//$order->cancel(_('Order automatically cancelled'),date("Y-m-d H:i:s",strtotime($date_order." +6 month")));

				// print $order->msg;//216249
			//}



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
			print "Unknown Order $tipo_order\n";
			break;
		}
		//$store=new Store($store_key);
		//$store->update_orders();
		//$store->update_customers_data();
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
		$sql="update orders_data.orders set last_transcribed=NOW() where id=".$order_data_id;
		mysql_query($sql);


		$time_data[]=microtime_float()-$base_time;
		if (fmod($contador,50)==0) {
			list($min,$avg,$max)=get_time_averages($time_data);
			$stringData="$contador $min $avg $max\n";

			fwrite($fh, $stringData);

			$time_data=array();
		}



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
print "->End.(GO UK) ".date("r")."\n";
//  print_r($data);
//print "\n$tipo_order\n";

function update_data($to_update) {
	return;
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
			$product->update_sales();


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
		$product->update_sales();
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
		$product->update_sales();
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
		$product->update_sales();
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
		$product->update_sales();
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



fclose($fh);

function get_time_averages($data) {
	$bins=count($data);
	$min=9999999999;
	$max=-9999999999;
	$sum=0;
	foreach ($data as $value) {
		if ($value<$min)
			$min=$value;
		if ($value>$max)
			$max=$value;
		$sum+=$value;
	}
	return array($min,$sum/$bins,$max);

}



?>
