<?php
include_once('../../app_files/db/dns.php');
include_once('../../class.Department.php');
include_once('../../class.Family.php');
include_once('../../class.Product.php');
include_once('../../class.Supplier.php');
include_once('../../class.Part.php');
include_once('../../class.SupplierProduct.php');
include_once('../../class.Image.php');
include_once('../../class.Page.php');

date_default_timezone_set('UTC');

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

$root_url='';
$root_url=$argv[1];
if(!$root_url){
  exit("root page as argument please\n");
}

$sql="select * from `Page Dimension` P  left join `Page Internal Dimension`  I on (P.`Page Key`=I.`Page Key`) where `Page Type`='Internal' and `Page Section`='Reports'";

$res=mysql_query($sql);
while($row=mysql_fetch_array($res)){
chdir("../../external_libs/html2imagev3");
$handle='root';
$pwd=create_master_key($handle);
$filename='tn_tmp_'.rand().'.jpg';


$path='app_files/pics/tmp/';
$img_name=$path.$filename;
$img_name_tmp=$path.'tmp_'.$filename;


if (file_exists($img_name)) {
 unlink($img_name);
}
$url='http://'.$root_url.'/'.$row['Page URL'];


$url_args='?mk='.$handle.'h_Adkiseqto'.$pwd;
//print "$url$url_args\n";

$command='export LD_LIBRARY_PATH=./;./html2image '.$url.$url_args." ../../$img_name_tmp -d 750 ;rm core.*; convert -resize 120 ../../$img_name_tmp ../../$img_name;rm ../../$img_name_tmp    ";
//print $command."\n";
exec($command);
chdir('../../');

$data=array(
	    'file'=>$filename
	    ,'path'=>'pages/'
	    ,'name'=>preg_replace('/[^a-z]/i','',$row['Page Title'])
	    ,'caption'=>$row['Page Title']
	    );

//print_r($data);
$image=new Image('find',$data,'create');
if($image->found_key){
  $image->delete();
  $image=new Image('find',$data,'create');
  
}
  
$page=new Page($row['Page Key']);
//print_r($image);
if($image->error){
  print $image->msg."\n";
}else{
  print print "updating " .$image->id."\n";
  $page->update_thumbnail_key($image->id);
}
chdir('mantenence/scripts/');


}

//$sql="insert into `Image Bridge` (`Subject`,`Subject Key`,`Image Key`) values('Website',%d,%d)",$page_key,





function create_master_key($handle){
mt_rand();mt_rand();
$pwd= sha1(generatePassword(100,10));
$pwd.=sha1(generatePassword(100,10));
$sql=sprintf("insert into `MasterKey Dimension` (`Handle`,`Key`,`Valid Until`)values (%s,%s,%s) "
	     ,prepare_mysql($handle)
	     ,prepare_mysql($pwd)
	     ,prepare_mysql(date("Y-m-d H:i:s",strtotime("now +1 minute")))
	     );
//print $sql;
mysql_query($sql);
return $pwd;

}


function make_seed(){
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