<?
include_once('../../conf/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Order.php');
error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}

require_once '../../common_functions.php';

mysql_set_charset('utf8');

require_once 'timezone.php'; 
date_default_timezone_set(TIMEZONE) ;
require('../../external_libs/Smarty/Smarty.class.php');
$smarty = new Smarty();

require_once '../../conf/conf.php';           
include_once('../../set_locales.php');
date_default_timezone_set('UTC');
$_SESSION['lang']=1;

setlocale(LC_ALL,'UTF-8');

$row = 1;
$handle = fopen("allCountries.txt", "r");
//$handle = fopen("BG.txt2", "r");

while (($data = fgetcsv($handle, 10000,"\t")) !== FALSE) {
  if(count($data)!=19){
    print_r($data);
    exit;
  }
  $values='';
  foreach($data as $dat){
    $values.=','.prepare_mysql($dat);
  }
  $values=preg_replace('/^,/','',$values);
  
  $sql=sprintf("insert into `Geography Dimension` values(%s)",$values);
  if(!mysql_query($sql)){
    print_r($data);
    exit("$sql\n");
  
  }
}
fclose($handle);

?>