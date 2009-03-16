<?
require_once 'conf.php';

include_once('../classes/Family.php');
include_once('../dns.php');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if(!$con){print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
require_once '../common_functions.php';
mysql_query("SET time_zone ='UTC'");
mysql_query("SET NAMES 'utf8'");

date_default_timezone_set('Europe/London');


$family=new Family('code','chill');
$family->locale='fr_FR';
$family->load('products_store',3);

$options=array();
print $family->get('Full Order Form',$options);

?>