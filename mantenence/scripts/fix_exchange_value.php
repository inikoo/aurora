<?php
//include("../../external_libs/adminpro/adminpro_config.php");
error_reporting(E_ALL);

include_once '../../conf/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Order.php';
include_once '../../class.Invoice.php';
include_once '../../class.PartLocation.php';
include_once '../../class.Deal.php';
include_once '../../class.SupplierProduct.php';
include_once '../../class.Staff.php';

include_once '../../class.DeliveryNote.php';
include_once '../../class.Email.php';
include_once '../../class.CurrencyExchange.php';
include_once 'common_read_orders_functions.php';


$encrypt=false;
$store_code='D';
$__currency_code='EUR';

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
	print "->End.(GO DE) ".date("r")."\n";
	exit;
}

//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	print "->End.(GO DE) ".date("r")."\n";
	exit;
}
date_default_timezone_set('UTC');
require_once '../../common_functions.php';

mysql_set_charset('utf8');
require_once 'timezone.php';
date_default_timezone_set(TIMEZONE) ;

include_once '../../set_locales.php';

require_once '../../conf/conf.php';
require '../../locale.php';

$_SESSION['locale_info'] = localeconv();


$_SESSION['lang']=1;

chdir('../../');

$corporation_currency_code='GBP';

$sql=sprintf("select `Invoice Public ID`,`Invoice Key`,`Invoice Date`,`Invoice Currency` from `Invoice Dimension` where (`Invoice Currency Exchange`=1  or `Invoice Currency Exchange`=0 ) and `Invoice Currency`!='GBP'");
$result=mysql_query($sql);
//print $sql;
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {



print $row['Invoice Currency'].$corporation_currency_code,$row['Invoice Date']."\n";

	$currency_exchange = new CurrencyExchange($row['Invoice Currency'].$corporation_currency_code,$row['Invoice Date']);
	$exchange= $currency_exchange->get_exchange();

	//print $row['Invoice Public ID'].' '.$row['Invoice Currency']."$corporation_currency_code ".$row['Invoice Date']."  \n";

	$sql=sprintf("update `Invoice Dimension` set `Invoice Currency Exchange`=%f where `Invoice Key`=%d ",
		$exchange,
		$row['Invoice Key']

	);
mysql_query($sql);
	print "$sql\n";

	$sql=sprintf("update `Order Transaction Fact` set `Invoice Currency Exchange Rate`=%f where `Invoice Key`=%d ",
		$exchange,
		$row['Invoice Key']

	);
	mysql_query($sql);
	

	
	print "$sql\n";
		$sql=sprintf("update `Order No Product Transaction Fact` set `Currency Exchange`=%f where `Invoice Key`=%d ",
		$exchange,
		$row['Invoice Key']

	);
	print "$sql\n";
mysql_query($sql);
}


$sql=sprintf("select `Order Public ID`,`Order Key`,`Order Date`,`Order Currency` from `Order Dimension` where (`Order Currency Exchange`=1  or `Order Currency Exchange`=0 ) and `Order Currency`!='GBP'");
$result=mysql_query($sql);
//print $sql;
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	$currency_exchange = new CurrencyExchange($row['Order Currency'].$corporation_currency_code,$row['Order Date']);
	$exchange= $currency_exchange->get_exchange();

//	print $row['Order Public ID'].' '.$row['Order Currency']."$corporation_currency_code ".$row['Order Date']."  \n";
print $row['Order Public ID'].' '.$row['Order Currency']."$corporation_currency_code ".$row['Order Date']."  \n";

	$sql=sprintf("update `Order Dimension` set `Order Currency Exchange`=%f where `Order Key`=%d ",
		$exchange,
		$row['Order Key']

	);
print "$sql\n";
	mysql_query($sql);
}


exit;

$sql="select  `Invoice Paid`,`Invoice Currency Exchange`,`Invoice Key`  from `Invoice Dimension` where `Invoice Currency Exchange`!=1;  ";

$result=mysql_query($sql);
//print $sql;
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

	$sql=sprintf("update `Order Transaction Fact` set `Invoice Currency Exchange Rate`=%f where `Invoice Key`=%d",$row['Invoice Currency Exchange'],$row['Invoice Key']);
	print "$sql\n";
	mysql_query($sql);

	if ($row['Invoice Paid']=='Yes') {



		$sql=sprintf("select `Invoice Currency Exchange Rate`,`Invoice Transaction Net Refund Items`,`Order Transaction Fact Key`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Gross Amount` from `Order Transaction Fact` where `Invoice Key`=%d  ",
			$row['Invoice Key']);

		$res2=mysql_query($sql);

		while ($row2=mysql_fetch_assoc($res2)) {


			$sql=sprintf( "update  `Inventory Transaction Fact`  set `Amount In`=%.2f where `Map To Order Transaction Fact Key`=%d "
				,$row2['Invoice Currency Exchange Rate']*($row2['Invoice Transaction Gross Amount']-$row2['Invoice Transaction Total Discount Amount']-$row2['Invoice Transaction Net Refund Items'])
				,$row2['Order Transaction Fact Key']);

			mysql_query( $sql );
			print "$sql\n";
		}



	}

	//exit;
}

?>
