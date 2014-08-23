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
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");

require_once '../../conf/timezone.php'; 
date_default_timezone_set(TIMEZONE) ;
require('../../external_libs/Smarty/Smarty.class.php');
$smarty = new Smarty();

require_once '../../conf/conf.php';           
include_once('../../set_locales.php');
date_default_timezone_set('UTC');
$_SESSION['lang']=1;

$row = 1;
$handle = fopen("country_data.txt", "r");
while (($data = fgetcsv($handle, 1000, "\t")) !== FALSE) {
  
  $code=$data[1];
  $numeric_code=$data[2];
  $capital=$data[5];
  $currency_code=$data[10];
  $currency_name=$data[11];
  $tld=$data[9];
  $postcode_format=$data[13];
  $postcode_regex=$data[14];
  $languages=$data[15];
  $neighbours=$data[17];
  $sql=sprintf("update `Country Dimension` set `Country Numeric Code`=%d , `Country Capital Name`=%s ,`Country Currency Code`=%s,`Country Currency Name`=%s ,`Country TLD`=%s ,`Country Postal Code Format`=%s,`Country Postal Code Regex`=%s ,`Country Languages`=%s ,`Country Neighbours`=%s where `Country Code`=%s "
	       ,$numeric_code
	       ,prepare_mysql($capital)
	       ,prepare_mysql($currency_code)
	       ,prepare_mysql($currency_name)
	       ,prepare_mysql($tld)
	       ,prepare_mysql($postcode_format,false)
	       ,prepare_mysql($postcode_regex,false)
	       ,prepare_mysql($languages)
	       ,prepare_mysql($neighbours,false)
	       ,prepare_mysql($code)
	       );
  //print "$sql\n";
  if(mysql_query($sql)){
    $affected=mysql_affected_rows();
    // printf ("Updated records: %d\n",$affected);
    if($affected==0){
      //print "------------------Not Found\n";
      //print_r($data);
    }
  }else{
    print "$sql\n------------------ERROR\n";
    print_r($data);
  }

  $sql=sprintf("select count(*) as num  from `Country Dimension` where  `Country Code`=%s ",prepare_mysql($code));
  $res=mysql_query($sql);
  if($row=mysql_fetch_array($res)){
    //if($row['num']==0)
      //  print_r($data);
  }


}
fclose($handle);

$sql=sprintf("select `Country Key` ,GROUP_CONCAT(Distinct `Country First Division Type`) as type  from `Country First Division Dimension` group by `Country Key` ",prepare_mysql($code));
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($res)){
    $sql=sprintf("update `Country Dimension` set `Country First Division Label`=%s where `Country Key`=%d",
		 prepare_mysql($row['type'])
		 ,$row['Country Key']
		 );
    if(!mysql_query($sql))
      print "$sql\n";
  }
$sql=sprintf("select `Country Key` ,GROUP_CONCAT(Distinct `Country Second Division Type`) as type  from `Country Second Division Dimension` group by `Country Key` ",prepare_mysql($code));
  $res=mysql_query($sql);
  while($row=mysql_fetch_array($res)){
    if($row['type']!=''){
    $sql=sprintf("update `Country Dimension` set `Country Second Division Label`=%s where `Country Key`=%d",
		 prepare_mysql($row['type'])
		 ,$row['Country Key']
		 );
    print "$sql\n";
    if(!mysql_query($sql))
      print "$sql\n";
    }
  }

?>