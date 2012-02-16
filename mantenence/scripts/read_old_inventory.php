<?php
include_once '../../app_files/db/dns.php';
include_once '../../class.Department.php';
include_once '../../class.Family.php';
include_once '../../class.Product.php';
include_once '../../class.Supplier.php';
include_once '../../class.Part.php';
include_once '../../class.SupplierProduct.php';
include_once '../../class.PartLocation.php';
include_once '../../class.User.php';
include_once '../../class.InventoryAudit.php';
include_once '../../class.Location.php';

error_reporting(E_ALL);
error_reporting(E_ALL);
date_default_timezone_set('UTC');
include_once '../../set_locales.php';
require '../../locale.php';
$_SESSION['locale_info'] = localeconv();


$con=@mysql_connect($dns_host,$dns_user,$dns_pwd );


if (!$con) {
	print "Error can not connect with database server\n";
	exit;
}
//$dns_db='dw_avant';
$db=@mysql_select_db($dns_db, $con);
if (!$db) {
	print "Error can not access the database\n";
	exit;
}
require_once '../../common_functions.php';
mysql_query("SET time_zone ='+0:00'");
mysql_query("SET NAMES 'utf8'");
require_once '../../conf/conf.php';
date_default_timezone_set('UTC');








print "Getting data from the old database\n";

$sql="INSERT INTO `Location Dimension` (`Location Key` ,`Location Warehouse Key` ,`Location Warehouse Area Key` ,`Location Code` ,`Location Mainly Used For` ,`Location Max Weight` ,`Location Max Volume` ,`Location Max Slots` ,`Location Distinct Parts` ,`Location Has Stock` ,`Location Stock Value`)VALUES ('1', '1', '1','Unknown', 'Picking', NULL , NULL , NULL , '0', 'Unknown', '0.00');";
$loc= new Location(1);
if (!$loc->id)
	mysql_query($sql);
$sql2="INSERT INTO  `Location Dimension` (`Location Key` ,`Location Warehouse Key` ,`Location Warehouse Area Key` ,`Location Code` ,`Location Mainly Used For` ,`Location Max Weight` ,`Location Max Volume` ,`Location Max Slots` ,`Location Distinct Parts` ,`Location Has Stock` ,`Location Stock Value`)VALUES ('2', '1', '1','LoadBay', 'Loading', NULL , NULL , NULL , '0', 'Unknown', '0.00');";
$loc= new Location(2);
if (!$loc->id)
	mysql_query($sql2);

$wa_data=array( 'Warehouse Area Name'=>'Unknown'
	,'Warehouse Area Code'=>'Unk'
	,'Warehouse Key'=>1
);

$wa=new WarehouseArea('find',$wa_data,'create');


$sql="delete  from `Inventory Transaction Fact`  where `Inventory Transaction Type` in ('In','Lost') ";
mysql_query($sql);
$sql="truncate `Inventory Audit Dimension`  ";
mysql_query($sql);

$sql="select (select handle from aw_old.liveuser_users where authuserid=aw_old.in_out.author) as user, code,product_id,aw_old.in_out.date,aw_old.in_out.tipo,aw_old.in_out.quantity ,aw_old.in_out.notes from aw_old.in_out left join aw_old.product on (product.id=product_id) where  in_out.date!='0000-00-00 00:00:00' and product.code is not null and (aw_old.in_out.tipo=2 or aw_old.in_out.tipo=1  or aw_old.in_out.tipo=3)    order by product.id,date ";
//print "$sql\n";
$result=mysql_query($sql);

while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {


	if (preg_match('/^pl/i',$row['notes']) and $row['tipo']==1)
		continue;
	if (preg_match('/italy/i',$row['notes']) and $row['tipo']==1)
		continue;
	if (preg_match('/replacement/i',$row['notes']) and $row['tipo']==1)
		continue;
	if (preg_match('/shortage/i',$row['notes']) and $row['tipo']==1)
		continue;

	//print $row['user']."\n";
	$user=new User('handle',$row['user'],'Staff');
	$user_key=$user->id;


	$date=$row['date'];
	$code=$row['code'];
	//   print $sql;

	$tipo=$row['tipo'];
	print $row['product_id']." $code     $tipo     \r";

	$qty=$row['quantity'];
	$notes=$row['notes'];
	$sql=sprintf("select `Product ID` from `Product Dimension` P where   `Product Code`=%s and `Product Valid From`<=%s and `Product Valid To`>=%s order by `Product Valid To` desc ",prepare_mysql($code),prepare_mysql($date),prepare_mysql($date));
	$result2=mysql_query($sql);
	// print "$sql\n";
	if ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {
		$product_ID=$row2['Product ID'];


		$sql=sprintf("select `Part SKU`,`Parts Per Product` from `Product Part Dimension` PPD left join `Product Part List` PPL on (PPD.`Product Part Key`=PPL.`Product Part Key`)where `Product ID`=%s  ",prepare_mysql($product_ID));
		// print "$sql\n";
		$result3=mysql_query($sql);
		$num = mysql_num_rows($result3);
		if ($num!=1) {
			print "\n$sql";
			exit ("no ideal product");

		}

		if ($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ) {
			$part_sku=$row3['Part SKU'];
			$parts_per_product=$row3['Parts Per Product'];
		}

		$part=new Part($part_sku);
		$cost_per_part=$part->get_unit_cost($date);

		if ($tipo==2) {


			//  print "Adding Audit\n";

			$data_inventory_audit=array(
				'Inventory Audit Date'=>$date
				,'Inventory Audit Part SKU'=>$part_sku
				,'Inventory Audit Location Key'=>1
				,'Inventory Audit Note'=>$notes
				,'Inventory Audit User Key'=>$user_key
				,'Inventory Audit Quantity'=>$qty*$parts_per_product
			);
			$audit=new InventoryAudit('find',$data_inventory_audit,'create');





		} elseif ($tipo==1) {


			$sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`,`History Type`) values (%s,%s,'In',%s,%s,%s,'','Normal')",prepare_mysql($date),prepare_mysql($part_sku),prepare_mysql($qty*$parts_per_product),prepare_mysql($cost_per_part*$qty*$parts_per_product),prepare_mysql($notes));
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql can into insert Inventory Transaction Fact ");


		}elseif ($tipo==3) {


			$sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`,`History Type`) values (%s,%s,'Lost',%s,%s,%s,'','Normal')",prepare_mysql($date),prepare_mysql($part_sku),prepare_mysql($qty*$parts_per_product),prepare_mysql($cost_per_part*$qty*$parts_per_product),prepare_mysql($notes));
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql can into insert Inventory Transaction Fact ");


		}

		continue;
	}
	// if the audit is ager the last





	$sql=sprintf("select `Product ID` from `Product Dimension` P where   `Product Code`=%s and `Product Valid To`<=%s order by `Product Valid To` desc ",prepare_mysql($code),prepare_mysql($date));
	$result2=mysql_query($sql);
	//print "$sql\n\n";
	if ($row2=mysql_fetch_array($result2, MYSQL_ASSOC)   ) {

		$product_ID=$row2['Product ID'];

		$sql=sprintf("select `Part SKU`,`Parts Per Product` from `Product Part Dimension` PPD left join `Product Part List` PPL on (PPD.`Product Part Key`=PPL.`Product Part Key`) where `Product ID`=%s  ",prepare_mysql($product_ID));
		// print "$sql\n";
		$result3=mysql_query($sql);
		$num = mysql_num_rows($result3);
		if ($num!=1)
			exit ("no ideal product");

		if ($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ) {
			$part_sku=$row3['Part SKU'];
			$parts_per_product=$row3['Parts Per Product'];
		}
		$part=new Part($part_sku);
		$cost_per_part=$part->get_unit_cost($date);

		// $cost_per_part=get_cost($part_sku,$date);
		//$sp_id=get_sp_id($part_sku,$date);


		if ($tipo==2) {
			//print "Adding Audit 2 \n";
			$data_inventory_audit=array(
				'Inventory Audit Date'=>$date
				,'Inventory Audit Part SKU'=>$part_sku
				,'Inventory Audit Location Key'=>1
				,'Inventory Audit Note'=>$notes
				,'Inventory Audit User Key'=>$user_key
				,'Inventory Audit Quantity'=>$qty*$parts_per_product
			);
			$audit=new InventoryAudit('find',$data_inventory_audit,'create');




		} elseif ($tipo==1) {


			$sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`note`,`Metadata`) values (%s,%s,'In',%s,%s,%s,'')",prepare_mysql($date),prepare_mysql($part_sku),prepare_mysql($qty*$parts_per_product),prepare_mysql($cost_per_part*$qty*$parts_per_product),prepare_mysql($notes));
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql can into insert Inventory Transaction Fact ");


		}elseif ($tipo==3) {


			$sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`,`History Type`) values (%s,%s,'Lost',%s,%s,%s,'','Normal')",prepare_mysql($date),prepare_mysql($part_sku),prepare_mysql($qty*$parts_per_product),prepare_mysql($cost_per_part*$qty*$parts_per_product),prepare_mysql($notes));
			// print "$sql\n";
			if (!mysql_query($sql))
				exit("$sql can into insert Inventory Transaction Fact ");


		}

		continue;
	}


	//extend range guess cust value


	$product=new Product('code_store',$code,1);
	if ($product->id) {
		$parts=$product->get('Parts SKU');



		if (count($parts)>=1) {
			$part=new Part($parts[0]);
			if ($part->sku) {
				//print_r($part);
				$part->update_valid_dates($date);
				$part_sku=$part->sku;

				$cost_per_part=$part->get("Unit Cost",$date);
				$parts_per_product=$part->items_per_product($product->pid);

				if ($tipo==2) {

					$sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`) values (%s,%s,'Audit',%s,%s,%s,'')",
						prepare_mysql($date),
						prepare_mysql($part_sku),
						prepare_mysql($qty*$parts_per_product),
						prepare_mysql($cost_per_part*$qty*$parts_per_product),
						prepare_mysql($notes,false));
					// print "$sql\n";
					//print "B: $sql\n";
					//      if(!mysql_query($sql))
					// exit("$sql can into insert Inventory Transaction Fact ");

					//print "Adding Audit 3 \n";

					$data_inventory_audit=array(
						'Inventory Audit Date'=>$date
						,'Inventory Audit Part SKU'=>$part_sku
						,'Inventory Audit Location Key'=>1
						,'Inventory Audit Note'=>$notes
						,'Inventory Audit User Key'=>$user_key
						,'Inventory Audit Quantity'=>$qty*$parts_per_product
					);
					//     print_r($data_inventory_audit);
					$audit=new InventoryAudit('find',$data_inventory_audit,'create');




				} elseif ($tipo==1) {

					$sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`note`,`Metadata`) values (%s,%s,'In',%s,%s,%s,'')",
						prepare_mysql($date),
						prepare_mysql($part_sku),
						prepare_mysql($qty*$parts_per_product),
						prepare_mysql($cost_per_part*$qty*$parts_per_product),
						prepare_mysql($notes,false));
					// print "$sql\n";
					if (!mysql_query($sql))
						exit("$sql can into insert Inventory Transaction Fact ");


				}elseif ($tipo==3) {


					$sql=sprintf("insert into `Inventory Transaction Fact` (`Date`,`Part SKU`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Inventory Transaction Amount`,`Note`,`Metadata`,`History Type`) values (%s,%s,'Lost',%s,%s,%s,'','Normal')",prepare_mysql($date),prepare_mysql($part_sku),prepare_mysql($qty*$parts_per_product),prepare_mysql($cost_per_part*$qty*$parts_per_product),prepare_mysql($notes));
					// print "$sql\n";
					if (!mysql_query($sql))
						exit("$sql can into insert Inventory Transaction Fact ");


				}

				continue;
			}

		}

	}




}
mysql_free_result($result);

// run now part_wrap_transactions.php




?>
