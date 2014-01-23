<?php
error_reporting(E_ALL);

include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Order.php';
include_once '../../class.Invoice.php';
include_once '../../class.DeliveryNote.php';
include_once '../../class.Email.php';
include_once '../../class.TimeSeries.php';
include_once '../../class.CurrencyExchange.php';
include_once '../../class.TaxCategory.php';
include_once '../../class.PartLocation.php';
include_once '../../class.Deal.php';

include_once 'common_read_orders_functions.php';


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );
if (!$con) {
	print "Error can not connect with database server\n";
	print "->End.(GO FR) ".date("r")."\n";
	exit;
}

//$dns_db='dw_avant2';


$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	print "->End.(GO FR) ".date("r")."\n";
	exit;
}
date_default_timezone_set('UTC');
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/timezone.php';
date_default_timezone_set(TIMEZONE) ;

include_once '../../set_locales.php';

require_once '../../conf/conf.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();

$currency='GBP';
$_SESSION['lang']=1;


$editor=array(
	'Date'=>'',
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>0,
	'User Key'=>0,
);
include_once 'fr_local_map.php';
include_once 'fr_map_order_functions.php';
print "->Start.(GO UK) ".date("r")."\n";

$software='Get_Orders_DB.php';
$version='V 1.0';

$Data_Audit_ETL_Software="$software $version";
srand(12344);



$store=new Store("code","FR");
$store_key=$store->id;
$sql="select `Delivery Note Metadata` as id ,`Delivery Note Key` from `Delivery Note Dimension` where  `Delivery Note Store Key`=$store_key and ( `Delivery Note Assigned Picker Key` IS NULL) ";
$result3=mysql_query($sql);
while ($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ) {

	$id=preg_replace('/F/i','',$row3['id']);
	//print $id."\n";
	$sql=sprintf("select * from  fr_orders_data.orders  where fr_orders_data.orders.id =%d",$id);

	$res=mysql_query($sql);
	if ($row2=mysql_fetch_array($res, MYSQL_ASSOC)) {
	
	
	
		$sql="select * from fr_orders_data.data where id=".$row2['id'];
		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$header=mb_unserialize($row['header']);
			$products=mb_unserialize($row['products']);
			$filename_number=str_replace('.xls','',str_replace($row2['directory'],'',$row2['filename']));
			$map_act=$_map_act;
			$map=$_map;
			$y_map=$_y_map;



			$header_data=array();
			$data=array();
			list($act_data,$header_data)=read_header($header,$map_act,$y_map,$map);


			$picker_data=get_user_id($header_data['pickedby'],true,'&view=picks',$editor);


			if (count($picker_data['id'])==0)$picker_key=0;
			else {
				$picker_key=$picker_data['id'][0];
			}

			$picker=new Staff($picker_key);

			if ($picker->id) {

				$sql = sprintf("update `Delivery Note Dimension` set `Delivery Note Assigned Picker Key`=%d ,`Delivery Note Assigned Picker Alias`=%s,`Delivery Note Number Pickers`=1,`Delivery Note XHTML Pickers`=%s where `Delivery Note Key`=%d"
					,$picker->id
					,prepare_mysql ($picker->data['Staff Alias'])
					,prepare_mysql (sprintf('<a href="staff.php?id=%d">%s</a>',$picker->id,$picker->data['Staff Alias']))


					,$row3['Delivery Note Key']);
				mysql_query($sql);
				//print "$sql\n";

				$sql = sprintf("update `Order Transaction Fact` set `Picker Key`=%d  where `Delivery Note Key`=%d"
					,$picker->id
					,$row3['Delivery Note Key']);
				mysql_query($sql);
				//print "$sql\n";
				$sql = sprintf("update `Inventory Transaction Fact` set `Picker Key`=%d  where `Delivery Note Key`=%d"
					,$picker->id
					,$row3['Delivery Note Key']);
				mysql_query($sql);
				//print "$sql\n";
				
				print "Picker fixed ".$picker->data['Staff Alias']."\n";

			}
			else {
				if ($header_data['pickedby']!='')
					print "not found:".$header_data['pickedby']."\n";
			}

		

		}


	}

}


$sql="select `Delivery Note Metadata` as id ,`Delivery Note Key` from `Delivery Note Dimension` where  `Delivery Note Store Key`=$store_key and ( `Delivery Note Assigned Packer Key` IS NULL) ";
$result3=mysql_query($sql);
while ($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ) {

	$id=preg_replace('/F/i','',$row3['id']);
	//print $id."\n";
	$sql=sprintf("select * from  fr_orders_data.orders  where fr_orders_data.orders.id =%d",$id);

	$res=mysql_query($sql);
	if ($row2=mysql_fetch_array($res, MYSQL_ASSOC)) {
	
	
	
		$sql="select * from fr_orders_data.data where id=".$row2['id'];
		$result=mysql_query($sql);

		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$header=mb_unserialize($row['header']);
			$products=mb_unserialize($row['products']);
			$filename_number=str_replace('.xls','',str_replace($row2['directory'],'',$row2['filename']));
			$map_act=$_map_act;
			$map=$_map;
			$y_map=$_y_map;



			$header_data=array();
			$data=array();
			list($act_data,$header_data)=read_header($header,$map_act,$y_map,$map);



			$packer_data=get_user_id($header_data['packedby'],true,'&view=picks',$editor);


			if (count($packer_data['id'])==0)$packer_key=0;
			else {
				$packer_key=$packer_data['id'][0];
			}

			$packer=new Staff($packer_key);

			if ($packer->id) {

				$sql = sprintf("update `Delivery Note Dimension` set `Delivery Note Assigned Packer Key`=%d ,`Delivery Note Assigned Packer Alias`=%s,`Delivery Note Number Packers`=1,`Delivery Note XHTML Packers`=%s where `Delivery Note Key`=%d"
					,$packer->id
					,prepare_mysql ($packer->data['Staff Alias'])
					,prepare_mysql (sprintf('<a href="staff.php?id=%d">%s</a>',$packer->id,$packer->data['Staff Alias']))


					,$row3['Delivery Note Key']);
				mysql_query($sql);
				//print "$sql\n";

				$sql = sprintf("update `Order Transaction Fact` set `Packer Key`=%d  where `Delivery Note Key`=%d"
					,$packer->id
					,$row3['Delivery Note Key']);
				mysql_query($sql);
				//print "$sql\n";
				$sql = sprintf("update `Inventory Transaction Fact` set `Packer Key`=%d  where `Delivery Note Key`=%d"
					,$packer->id
					,$row3['Delivery Note Key']);
				mysql_query($sql);
				//print "$sql\n";
				
				
								print "Packer fixed ".$packer->data['Staff Alias']."\n";


			}
			else {
				if ($header_data['packedby']!='')
					print "not found:".$header_data['packedby']."\n";
			}


		}


	}

}



?>
