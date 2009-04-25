<?
include_once('../../app_files/db/dns.php');
include_once('../../classes/Department.php');
include_once('../../classes/Family.php');
include_once('../../classes/Product.php');
include_once('../../classes/Supplier.php');
include_once('../../classes/Part.php');
include_once('../../classes/SupplierProduct.php');
include_once('../../classes/PartLocation.php');

error_reporting(E_ALL);



$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
  

require_once '../../common_functions.php';
mysql_query("SET time_zone ='UTC'");
mysql_query("SET NAMES 'utf8'");
require_once '../../myconf/conf.php';           
date_default_timezone_set('Europe/London');
$not_found=00;

$force_first_day=true;
$first_day_with_data=strtotime("2007-03-24");


$where='';
if(isset($argv[1]) and is_numeric($argv[1])  )
  $where=" where  `Part SKU`=". $argv[1];

$sql="select `Part Status`,`Part SKU`,`Part Valid From`,`Part Valid To`,`Part XHTML Currently Used In` from `Part Dimension` $where  ";

$resultx=mysql_query($sql);
$counter=1;
while($rowx=mysql_fetch_array($resultx, MYSQL_ASSOC)   ){
  $part= new Part($rowx['Part SKU']);
  $part->load('calculate_stock_history','complete');
 }


?>