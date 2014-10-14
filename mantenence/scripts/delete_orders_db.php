<?php
error_reporting(E_ALL);

include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Order.php');
include_once('../../class.Invoice.php');
include_once('../../class.DeliveryNote.php');
include_once('../../class.Email.php');
include_once('../../class.TimeSeries.php');
include_once('../../class.CurrencyExchange.php');
include_once('../../class.TaxCategory.php');

include_once('common_read_orders_functions.php');


function microtime_float() {
    list($utime, $time) = explode(" ", microtime());
    return ((float)$utime + (float)$time);
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

mysql_set_charset('utf8');
require_once 'timezone.php';
date_default_timezone_set(TIMEZONE) ;

include_once('../../set_locales.php');

require_once '../../conf/conf.php';
require('../../locale.php');
$_SESSION['locale_info'] = localeconv();

$currency='GBP';
$_SESSION['lang']=1;

include_once('local_map.php');
include_once('map_order_functions.php');
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







?>
