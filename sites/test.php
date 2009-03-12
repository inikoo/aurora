<?
include_once('Product.php');
include_once('dns.php');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
require_once 'common_functions.php';
mysql_query("SET time_zone ='UTC'");
mysql_query("SET NAMES 'utf8'");
require_once 'conf.php';     
date_default_timezone_set('Europe/London');


$product=new product('code_store','fo-a1',1);
$product->locale='fr_FR';
print $product->get('Order Form');

?>