<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
include_once('../../class.Image.php');

date_default_timezone_set('Europe/London');

error_reporting(E_ALL);
$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if(!$con){print "Error can not connect with database server\n";exit;}
//$dns_db='dw';
$db=@mysql_select_db($dns_db, $con);
if (!$db){print "Error can not access the database\n";exit;}
require_once '../../common_functions.php';
require_once '../../class.User.php';

mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';           


mt_srand(make_seed());
chdir("../../external_libs/html2imagev3");

$handle='root';
$pwd=create_master_key($handle);
$filename='tn_tmp_'.rand().'.jpg';


$path='app_files/pics/';
$img_name=$path.$filename;
if (file_exists($img_name)) {
 unlink($img_name);
}
$url='http://tunder/ci/report_sales_server.php';
$url_args='?mk='.$handle.'h_Adkiseqto'.$pwd;
$command='export LD_LIBRARY_PATH=./;./html2image '.$url.$url_args." ../../$img_name";
print $command."\n";
exec($command);
chdir('../../');

$data=array('file'=>$filename);
$image=new Image('find',$data,'create');
print_r($image);




function create_master_key($handle){
mt_rand();mt_rand();
$pwd= sha1(generatePassword(100,10));
$pwd.=sha1(generatePassword(100,10));
$sql=sprintf("insert into `MasterKey Dimension` (`Handle`,`Key`,`Valid Until`)values (%s,%s,%s) "
	     ,prepare_mysql($handle)
	     ,prepare_mysql($pwd)
	     ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now +10 minute")))
	     );
//print $sql;
mysql_query($sql);
return $pwd;

}


function make_seed()
{
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float) $usec * 100000)+rand()-rand();
}

function generatePassword($length=9, $strength=0) {
	$vowels = 'aeuy'.md5(mt_rand());
	$consonants = 'bdghjmnpqrstvz'.md5(mt_rand());
	if ($strength & 1) {
		$consonants .= 'BDGHJLMNPQRSTVWXZlkjhgfduytrdqwertyuiopasdfghjklzxcvbnm';
	}
	if ($strength & 2) {
		$vowels .= "AEUY4,cmoewmpaeoi8m5390m4pomeotixcmpodim";
	}
	if ($strength & 4) {
		$consonants .= '2345678906789$%^&*(';
	}
	if ($strength & 8) {
		$consonants .= '!=/[]{}~|\<>$%^&*()_+@#.,)(*%%';
	}
 
	$password = '';
	$alt = time() % 2;
	for ($i = 0; $i < $length; $i++) {
		if ($alt == 1) {
			$password .= $consonants[(mt_rand() % strlen($consonants))];
			$alt = 0;
		} else {
			$password .= $vowels[(mt_rand() % strlen($vowels))];
			$alt = 1;
		}
	}
	return $password;
}


?>