<?php
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

$count=0;
//$agent_types=array();
$sql=sprintf("select * from kbase.`User Agent Dimension` where `User Agent Type` is null");
$result=mysql_query($sql);
//$agent_types[]='aa';
while($row=mysql_fetch_assoc($result)){
	//print_r($row);exit;
	$string=urlencode($row['User Agent String']);
	$api_string="http://www.useragentstring.com/?uas=$string&getText=all";
	//$content = file_get_contents('http://www.useragentstring.com/?uas=Baiduspider+%28+http://www.baidu.com/search/spider.htm%29&getText=all');
	//echo $content;
	//print_r($row['User Agent String']); exit;

	//print_r($api_string);exit;
	$content = file_get_contents($api_string);

	//print_r($content);exit;


	$info=array();
	
	$info=explode(";", $content);
	$_info=$info;
	array_shift($_info);
	array_shift($_info);
	$agent_description='';
	foreach($_info as $i){
		$agent_description.=$i.";";
	}

	$name=explode("=", $info[1]);
	$agent_name=$name[1];
	$type=explode("=", $info[0]);

	switch($type[1]){
		case 'Browser':
		case 'Librarie':
		case 'Cloud Platform':
		case 'Feed Reader':
		case 'Offline Browser':
		case 'Console':
		case 'LineChecker':
		case 'Mobile Browser':
		case 'Validator':
		case 'E-Mail Collector':
			$agent_type=$type[1];
			break;
		case 'Crawler':
			$agent_type='Bot';
			break;
		default:
			$agent_type='Other';
			break;
	}


	$sql=sprintf("update kbase.`User Agent Dimension` set `User Agent Name`=%s, `User Agent Description`=%s, `User Agent Type`=%s where `User Agent Key`=%d"
			,prepare_mysql($agent_name)
			,prepare_mysql($agent_description)
			,prepare_mysql($agent_type)
			,$row['User Agent Key']);

	//print $sql;exit;
	mysql_query($sql);
	
	if($agent_type!='Other'){
		$count++;
	}
	//print $sql;exit;
/*
	$flag=false;
		foreach($agent_types as $agent){
		//print "$agent: $type[1]\n";
			if($agent==$type[1]){
				//print 'No';exit;
				$flag=true;
				break;
			}

		}
	if(!$flag){
		$agent_types[]=$type[1];
		//$flag=false;
		//echo "$type[1]\n";//exit;
	}
	print_r($agent_types);
	print "\n\n";
*/
}

print $count;

//print_r($agent_types);



?>