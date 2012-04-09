<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.Store.php';
include_once '../../class.Customer.php';
include_once '../../class.Site.php';
include_once '../../class.Image.php';

error_reporting(E_ALL);




date_default_timezone_set('UTC');

$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );

if (!$con) {print "Error can not connect with database server\n";exit;}
$db=@mysql_select_db($dns_db, $con);
if (!$db) {print "Error can not access the database\n";exit;}


require_once '../../common_functions.php';
require_once '../../common_detect_agent.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';

chdir('../../');




$xml = simplexml_load_file("allagents.xml");
$existing_count=0;
$new_count=0;

foreach($xml->children() as $child)
{
//   echo $child->String;
// exit;
$agent_type='';

switch($child->Type){
	case 'B':
		$agent_type='Browser';
		break;
	case 'S':
		$agent_type='Spam';
		break;
	case 'R':
		$agent_type='Bot';
		break;
	case 'P':
		$agent_type='Proxy';
		break;
	default:
		$agent_type='Other';
		break;
}
	


	$sql=sprintf("select * from kbase.`User Agent Dimension` where `User Agent String`=%s", prepare_mysql($child->String));
	$result=mysql_query($sql);

	if(mysql_num_rows($result) > 0){
		$sql=sprintf("update kbase.`User Agent Dimension` set `User Agent Name`=%s, `User Agent Description`=%s, `User Agent Type`=%s where `User Agent String`=%s"
		,prepare_mysql($child->ID)
		,prepare_mysql($child->Description)
		,prepare_mysql($agent_type)
		,prepare_mysql($child->String));

		$existing_count++;
	}
	else{
		$sql=sprintf("insert into kbase.`User Agent Dimension` (`User Agent Name`, `User Agent String`, `User Agent Description`, `User Agent Type`) values (%s, %s, %s, %s)"
		,prepare_mysql($child->ID)
		,prepare_mysql($child->String)
		,prepare_mysql($child->Description)
		,prepare_mysql($agent_type));

		$new_count++;
	}

	print $sql."\n";
	//print "<br/>";
	mysql_query($sql);
	

}

print "Existing: ".$existing_count."\n";

print "New: ".$new_count."\n";


?>