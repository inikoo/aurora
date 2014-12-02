<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
include_once '../../conf/dns.php';
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

mysql_set_charset('utf8');
require_once '../../conf/conf.php';


$sql=sprintf("select `Page Store Key`,`Page Store Image Key`,P.`Page Key`,`Page Store Section` from `Page Dimension` P  left join `Page Store Dimension` PS on (P.`Page Key`=PS.`Page Key`)  where `Page Type`='Store' and `Page Store Source`!='' and `Page Store Section Type`='Family' ");
$res=mysql_query($sql);
while ($row=mysql_fetch_array($res)) {
	$page=new Page($row['Page Key']);




	$source=$page->data['Page Store Source'];

	


	$pattern='/alt="http.*"/';
	$replacement='alt=""';
	$source=preg_replace($pattern,$replacement,$source);

	$pattern='/alt=".*\/.*"/';
	$replacement='alt=""';
	$source=preg_replace($pattern,$replacement,$source);

/*

<div style="position:absolute; left:735px; top:963px; width:221px; height:221px;">
<a href="https://www.ancientwisdom.biz/user_files/pics/iwood-12.jpg " target="_blank"><img src="images/0892720.png" width="221" height="221" border="0" title="" alt="pp2371a26a.png"></a></div>

*/



	// print $source;
	$pattern='/<a href="https?:\/\/www.ancientwisdom.biz\/.*pics\/(.+)\.jpg\s*"\s*target="_blank""?><img src="images\/(.+)" .* alt="(.+)" >/';
	$pattern='/<a href="https?:\/\/www.ancientwisdom.biz\/.*pics\/(.+)\.jpg\s*"\s*target="_blank""?><img src="images\/(.+)" .* alt="(.+)"\s*>/';

	preg_match_all($pattern, $source, $matches);



	foreach ($matches[1] as $key=>$possible_code) {


		$possible_code=preg_replace('/_[a-z]+$/','',$possible_code);



		$code=false;
		$sql=sprintf("select `Product Code`,`Product Name` from `Product Dimension` where `Product Code`=%s and `Product Store Key`=%d",
			prepare_mysql($possible_code),
			$row['Page Store Key']
		);

		
		$res2=mysql_query($sql);
		while ($row2=mysql_fetch_array($res2)) {
			$code=$row2['Product Code'];
			$description=$row2['Product Code'].', '.$row2['Product Name'];
		}
		//print "$sql\n";
		if (!$code) {
			$sql=sprintf("select `Product Family Code`,`Product Family Name` from  `Product Family Dimension`  where `Product Family Code`=%s and `Product Family Store Key`=%d",
				prepare_mysql($possible_code),
				$row['Page Store Key']
			);
			$res2=mysql_query($sql);
			while ($row2=mysql_fetch_array($res2)) {
				$code=$row2['Product Family Code'];
				$description=$row2['Product Family Code'].', '.$row2['Product Family Name'];
			}
		}
		if (!$code) {
			$sql=sprintf("select `Product Department Code`,`Product Department Name`  from `Product Department Dimension`  where `Product Department Code`=%s and `Product Department Store Key`=%d",
				prepare_mysql($possible_code),
				$row['Page Store Key']
			);
			$res2=mysql_query($sql);
			while ($row2=mysql_fetch_array($res2)) {
				$code=$row2['Product Department Code'];
				$description=$row2['Product Department Code'].', '.$row2['Product Department Name'];
			}
		}


		if (!$code) {
			$code=$possible_code;
			$description=$possible_code;

		}


		$pattern='/<a href="https?:\/\/www.ancientwisdom.biz\/(.*)pics\/('.$matches[1][$key].')\.jpg\s*"\s*target="_blank""?><img src="images\/('.$matches[2][$key].')"(.*) title=".*" alt="'.$matches[3][$key].'"\s*>/';
		$pattern='/<a href="https?:\/\/www.ancientwisdom.biz\/(.*)pics\/('.$matches[1][$key].')\.jpg\s*"\s*target="_blank""?><img src="images\/('.$matches[2][$key].')"(.*) title=".*" alt="'.$matches[3][$key].'"\s*>/';

		$replacement='<a href="https://www.ancientwisdom.biz/${1}pics/${2}.jpg" target="_blank"><img src="images/${3}"${4} title="'.str_replace('"','',$description).'" alt="'.str_replace('"','',$code).'">';

		
		$source=preg_replace($pattern,$replacement,$source);
		
	}


	$pattern='/href="(.+)\s*"\s*target="_blank""/';
	$replacement='href="${1}" target="_blank"';
	$source=preg_replace($pattern,$replacement,$source);

	$pattern='/target="_blank"\s*"=""/';
	$replacement='target="_blank"';
	$source=preg_replace($pattern,$replacement,$source);


	$pattern='/href="\s*(.+)\s*"/';
	$replacement='href="${1}"';
	$source=preg_replace($pattern,$replacement,$source);



	$sql=sprintf("update `Page Store Dimension` set `Page Store Source`=%s where `Page Key`=%d",
		prepare_mysql($source),
		$page->id
	);
	mysql_query($sql);

}





?>
