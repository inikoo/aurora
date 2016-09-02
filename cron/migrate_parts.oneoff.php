<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 15 February 2016 at 10:45:44 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


$default_DB_link=@mysql_connect($dns_host, $dns_user, $dns_pwd );
if (!$default_DB_link) {
	print "Error can not connect with database server\n";
}
$db_selected=mysql_select_db($dns_db, $default_DB_link);
if (!$db_selected) {
	print "Error can not access the database\n";
	exit;
}
mysql_set_charset('utf8');
mysql_query("SET time_zone='+0:00'");



require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';

require_once 'class.Account.php';

require_once 'class.Customer.php';
require_once 'class.Store.php';
require_once 'class.Warehouse.php';
require_once 'class.Part.php';
require_once 'class.Material.php';

require_once 'class.Product.php';
include_once 'utils/parse_materials.php';
$editor=array(
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>'',
	'User Key'=>0,
	'Date'=>gmdate('Y-m-d H:i:s')
);

$account=new Account();

/*
create_part_data_dimension();
setup_part_families();
update_materials_stats();
migrate_part_fields();
create_families($db, $account);
setup_product_part_bridge();
set_valid_dates($db);
set_valid_dates_and_status_to_part_families($db);
fix_orphan_dn($db);
update_stock($db);
move_MSDS_attachments($db);
*/

set_unit_label($db);



function set_unit_label($db) {

	$sql=sprintf('select * from `Part Dimension`  ');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$part=new Part($row['Part SKU']);


			

			$num_uk_prod=0;
			$prod_uk=false;
			$products=array();
			$min_units=9999;
			$price='';
			$rrp='';

			foreach (get_product_ids($part) as $product_pid) {
				$product=new Product($product_pid);

				if ($product->id and $product->data['Product Record Type']=='Normal') {

					if ($product->data['Product Store Key']==1 and get_number_of_parts($db, $product)==1) {
						$products[]=array(
							'code'=>$product->data['Product Code'],
							// 'store'=>$product->data['Product Store Key'],
							// 'parts'=>get_number_of_parts($db, $product)
						);

						if ($product->data['Product Units Per Case']<$min_units) {
							$min_units=$product->data['Product Units Per Case'];
						}
						if ($product->get('Product Price')!='')
							$price=$product->get('Product Price')/$product->get('Product Units Per Case');
						if ($product->get('Product RRP')!='')
							$rrp=$product->get('Product RRP')/$product->get('Product Units Per Case');

						$num_uk_prod++;
						$prod_uk=$product;
					}
				}
			}

			if ($num_uk_prod==1) {

				//print_r($prod_uk);

				$part->update(array(
						// 'Part Units'=>$prod_uk->get('Product Units Per Case'),
						'Part Unit Label'=>$prod_uk->get('Product Unit Label'),
						

					), 'no_history');
			}








		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}


}


function set_valid_dates($db) {


	$sql=sprintf('select `Part SKU` from `Part Dimension` where `Part Valid From` is null or `Part Valid From`="" ');
	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$from_date_itf='';
			$part=new Part($row['Part SKU']);

			$sql=sprintf('select min(`Date`) as date from `Inventory Transaction Fact` where `Part SKU`=%d and `Date` is not null and `Date`!="0000-00-00 00:00:00" ', $part->sku);


			if ($result2=$db->query($sql)) {
				foreach ($result2 as $row2) {
					$from_date_itf=$row2['date'];
				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}




			if ( $from_date_itf=='') {
				$from_date=gmdate('Y-m-d H:i:s');
			}else {
				$from_date= $from_date_itf;
			}

			$sql=sprintf('update `Part Dimension` set `Part Valid From`=%s where `Part SKU`=%d',
				prepare_mysql($from_date),
				$part->sku
			);
			//print "$sql\n";
			$db->exec($sql);




		}

	}else {print_r($error_info=$db->errorInfo());
		print $sql;
		exit;}



	$sql=sprintf('select `Part SKU` from `Part Dimension` where `Part Valid To` is null or `Part Valid To`="" and `Part Status`="Not In Use" ');
	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$from_date_itf='';
			$part=new Part($row['Part SKU']);

			$sql=sprintf('select max(`Date`) as date from `Inventory Transaction Fact` where `Part SKU`=%d and `Date` is not null and `Date`!="0000-00-00 00:00:00" ', $part->sku);


			if ($result2=$db->query($sql)) {
				foreach ($result2 as $row2) {
					$from_date_itf=$row2['date'];
				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}




			if ( $from_date_itf=='') {
				$from_date=gmdate('Y-m-d H:i:s');
			}else {
				$from_date= $from_date_itf;
			}

			$sql=sprintf('update `Part Dimension` set `Part Valid To`=%s where `Part SKU`=%d',
				prepare_mysql($from_date),
				$part->sku
			);

			//print "$sql\n";
			$db->exec($sql);






		}

	}else {print_r($error_info=$db->errorInfo());
		print $sql;
		exit;}




}


function set_valid_dates_and_status_to_part_families($db) {


	//$sql=sprintf('select `Category Key` from `Category Dimension` where `Category Scope`="Part" and `Category Key`=11899  ');
	$sql=sprintf('select `Category Key` from `Category Dimension` where `Category Scope`="Part" ');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {

			$category=new Category($row['Category Key']);


			$part_skus='';
			$sql=sprintf('select group_concat(`Subject Key`) as part_skus ,`Subject` from `Category Bridge` where `Category Key`=%d and `Subject Key`>0 ', $category->id);

			if ($result=$db->query($sql)) {
				if ($row = $result->fetch()) {
					if ($row['Subject']=='Part') {
						$part_skus=$row['part_skus'];
					}elseif ($row['Subject']=='Category') {

						$sql=sprintf('select group_concat(`Subject Key`) as part_skus ,`Subject` from `Category Bridge` where `Category Key` in (%s) and `Subject Key`>0 ', $row['part_skus']);
						if ($result2=$db->query($sql)) {
							if ($row2 = $result2->fetch()) {
								$part_skus=$row2['part_skus'];

							}
						}else {
							print_r($error_info=$db->errorInfo());
							print $sql;
							exit;
						}


					}
				}
			}else {
				print_r($error_info=$db->errorInfo());
				exit;
			}
			$from_date_parts='';
			$from_date_itf='';
			if ($part_skus!='') {
				$sql=sprintf('select min(`Part Valid From`) as date from `Part Dimension` where `Part SKU` in (%s) and `Part Valid From` is not null and `Part Valid From`!="0000-00-00 00:00:00" ', $part_skus);


				if ($result2=$db->query($sql)) {
					foreach ($result2 as $row2) {
						$from_date_parts=$row2['date'];
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}

			}



			if ($part_skus!='') {
				$sql=sprintf('select min(`Date`) as date from `Inventory Transaction Fact` where `Part SKU` in (%s) and `Date` is not null and `Date`!="0000-00-00 00:00:00" ', $part_skus);


				if ($result2=$db->query($sql)) {
					foreach ($result2 as $row2) {
						$from_date_itf=$row2['date'];
					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}

			}


			if ($from_date_parts=='' and $from_date_itf=='') {
				$from_date=gmdate('Y-m-d H:i:s');
			}elseif ($from_date_parts=='' and $from_date_itf!='') {
				$from_date= $from_date_itf;
			}elseif ($from_date_parts!='' and $from_date_itf=='') {
				$from_date= $from_date_parts;
			}else {
				if (strtotime($from_date_parts)<strtotime($from_date_itf)) {
					$from_date= $from_date_parts;

				}else {
					$from_date= $from_date_itf;

				}
			}

			$sql=sprintf('update `Part Category Dimension` set `Part Category Valid From`=%s where `Part Category Key`=%d',
				prepare_mysql($from_date),
				$category->id
			);
			//print "$sql\n";
			$db->exec($sql);

			$category->update_part_category_status();

			if ($category->get('Part Category Status')=='NotInUse') {

				$to_date_parts='';
				$to_date_itf='';
				if ($part_skus!='') {
					$sql=sprintf('select max(`Part Valid To`) as date from `Part Dimension` where `Part SKU` in (%s) and `Part Valid To` is not null and `Part Valid To`!="0000-00-00 00:00:00" ', $part_skus);


					if ($result2=$db->query($sql)) {
						foreach ($result2 as $row2) {
							$to_date_parts=$row2['date'];
						}
					}else {
						print_r($error_info=$db->errorInfo());
						exit;
					}

				}



				if ($part_skus!='') {
					$sql=sprintf('select max(`Date`) as date from `Inventory Transaction Fact` where `Part SKU` in (%s) and `Date` is not null and `Date`!="0000-00-00 00:00:00" ', $part_skus);


					if ($result2=$db->query($sql)) {
						foreach ($result2 as $row2) {
							$to_date_itf=$row2['date'];
						}
					}else {
						print_r($error_info=$db->errorInfo());
						exit;
					}

				}


				if ($to_date_parts=='' and $to_date_itf=='') {
					$to_date=gmdate('Y-m-d H:i:s');
				}elseif ($to_date_parts=='' and $to_date_itf!='') {
					$to_date= $to_date_itf;
				}elseif ($to_date_parts!='' and $to_date_itf=='') {
					$to_date= $to_date_parts;
				}else {
					if (strtotime($to_date_parts)>strtotime($to_date_itf)) {
						$to_date= $to_date_parts;

					}else {
						$to_date= $to_date_itf;

					}
				}

				$sql=sprintf('update `Part Category Dimension` set `Part Category Valid To`=%s where `Part Category Key`=%d',
					prepare_mysql($to_date),
					$category->id
				);

				print "$sql\n";
				$db->exec($sql);
			}


		}

	}else {print_r($error_info=$db->errorInfo());
		print $sql;
		exit;}



}


function fix_orphan_dn() {
	global $db;

	include_once 'class.DeliveryNote.php';
	include_once 'class.PartLocation.php';

	$sql='
	select `Order Public ID`,`Order Date`,`Current Dispatching State`,I.`Delivery Note Key` from `Order Transaction Fact` O left join `Inventory Transaction Fact` I on (`Order Transaction Fact Key`=`Map To Order Transaction Fact Key`) where I.`Delivery Note Key`>0 and O.`Delivery Note Key` is NULL group by I.`Delivery Note Key`
	';
	if ($result=$db->query($sql)) {
		foreach ($result as $row) {


			$dn=new DeliveryNote($row['Delivery Note Key']);
			if ($dn->id) {
				$dn->delete();
			}else {
				$parts_to_update_stock=array();
				$sql=sprintf("select `Part SKU`,`Location Key` from  `Inventory Transaction Fact` where `Delivery Note Key`=%d  and `Inventory Transaction Type`='Order In Process'  ",
					$row['Delivery Note Key']);
				$res2=mysql_query($sql);
				while ($row2=mysql_fetch_assoc($res2)) {
					$parts_to_update_stock[]=$row2['Part SKU'].'_'.$row2['Location Key'];
				}

				$sql=sprintf("delete from  `Inventory Transaction Fact` where `Delivery Note Key`=%d  and `Inventory Transaction Type`='Order In Process'  ",
					$row['Delivery Note Key']);
				mysql_query($sql);

				foreach ($parts_to_update_stock as $part_to_update_stock) {
					$part_location=new PartLocation($part_to_update_stock);
					$part_location->update_stock();
				}





				$sql=sprintf("delete from  `Order Delivery Note Bridge` where `Delivery Note Key`=%d  ",
					$row['Delivery Note Key']);
				mysql_query($sql);

				if (in_array($dn->data['Delivery Note Type'], array('Replacement & Shortages', 'Replacement', 'Shortages'))) {
					$sql = sprintf("update `Order Post Transaction Dimension` set `State`=%s  where `Delivery Note Key`=%d   ",
						prepare_mysql('In Process'),
						$row['Delivery Note Key']
					);
					mysql_query($sql);


					$sql=sprintf("delete from `Order Transaction Fact` where `Delivery Note Key`=%d and `Order Transaction Type`='Resend'", $row['Delivery Note Key']);
					mysql_query($sql);

				}


			}
		}

	}


	$sql='select `Order Public ID`,`Order Date`,`Current Dispatching State`,I.`Delivery Note Key` from `Order Transaction Fact` O left join `Inventory Transaction Fact` I on (`Order Transaction Fact Key`=`Map To Order Transaction Fact Key`) where I.`Delivery Note Key`!=O.`Delivery Note Key` and O.`Delivery Note Key`>0 group by I.`Delivery Note Key`;';
	print "$sql\n";

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {

			print_r($row);
			$dn=new DeliveryNote($row['Delivery Note Key']);
			if ($dn->id) {
				$dn->delete();
			}else {

				$parts_to_update_stock=array();
				$sql=sprintf("select `Part SKU`,`Location Key` from  `Inventory Transaction Fact` where `Delivery Note Key`=%d   ",
					$row['Delivery Note Key']);
				$res2=mysql_query($sql);
				while ($row2=mysql_fetch_assoc($res2)) {
					$parts_to_update_stock[]=$row2['Part SKU'].'_'.$row2['Location Key'];
				}

				$sql=sprintf("delete from  `Inventory Transaction Fact` where `Delivery Note Key`=%d   ",
					$row['Delivery Note Key']);
				mysql_query($sql);

				print "$sql\n";
				foreach ($parts_to_update_stock as $part_to_update_stock) {
					$part_location=new PartLocation($part_to_update_stock);
					$part_location->update_stock();
				}


				$sql=sprintf("delete from  `Order Delivery Note Bridge` where `Delivery Note Key`=%d  ",
					$row['Delivery Note Key']);
				mysql_query($sql);

				if (in_array($dn->data['Delivery Note Type'], array('Replacement & Shortages', 'Replacement', 'Shortages'))) {
					$sql = sprintf("update `Order Post Transaction Dimension` set `State`=%s  where `Delivery Note Key`=%d   ",
						prepare_mysql('In Process'),
						$row['Delivery Note Key']
					);
					mysql_query($sql);


					$sql=sprintf("delete from `Order Transaction Fact` where `Delivery Note Key`=%d and `Order Transaction Type`='Resend'", $row['Delivery Note Key']);
					mysql_query($sql);

				}


			}
		}

	}

}


function update_stock() {

	global $db;
	print "updating stock\n";

	$sql=sprintf('select `Part SKU` from `Part Dimension`  ');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {


			$part=new Part($row['Part SKU']);

			$part->update_stock();
		}

	}
}


function create_part_data_dimension() {
	global $db;
	$sql=sprintf('select `Part SKU` from `Part Dimension`  ');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$sql="insert into `Part Data` (`Part SKU`) values(".$row['Part SKU'].");";
			$db->exec($sql);

			$part=new Part($row['Part SKU']);

			$part->update(
				array('Part Family Category Key'=>''), 'no_history'
			);
		}

	}
}


function setup_part_families() {
	global $db, $account;

	$sql=sprintf("select `Category Key`,`Subject Key` from `Category Bridge` where `Category Head Key`=`Category Key` and `Subject`='Part' ");
	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$part=new Part($row['Subject Key']);
			$part->update(
				array('Part Family Category Key'=>$row['Category Key']), 'no_history'
			);

		}
	}


	$sql=sprintf('select B.`Category Key` from `Category Bridge` B left join `Category Dimension` C on (B.`Category Key`=C.`Category Key`) where `Category Root Key`=%d and  `Category Head Key`=B.`Category Key` and `Subject`="Part" and `Subject Key`=%d',
		$account->get('Account Part Family Category Key'),
		$part->id
	);
	if ($result2=$db->query($sql)) {
		if ($row2 = $result2->fetch()) {
			$part->update(array('Part Family Category Key'=>$row2['Category Key']), 'no_history');

		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}


	$sql=sprintf("select `Category Key`,`Subject Key` from `Category Bridge` where  `Subject`='Part' ");
	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$category=new Category($row['Category Key']);
			$category->update_number_of_subjects();

		}
	}

}






function setup_product_part_bridge() {
	global $db;
	// Create product part bridge
	$sql=sprintf('select * from `Product Dimension` order by `Product ID` desc');

	if ($result=$db->query($sql)) {



		foreach ($result as $row) {
			$editor['Date']=gmdate('Y-m-d H:i:s');


			$product=new Product($row['Product ID']);


			$sql=sprintf('delete from `Product Part Bridge` where `Product Part Product ID`=%d',
				$row['Product ID']

			);

			$db->exec($sql);



			$parts_data=get_part_list($db, $row['Product ID']);
			$_parts_data=$parts_data;
			foreach ($parts_data as $part_data) {


				$part=$part_data['part'];

				$sql=sprintf('insert into `Product Part Bridge` (`Product Part Product ID`,`Product Part Part SKU`,`Product Part Ratio`,`Product Part Note`) values (%d,%d,%f,%s)',
					$product->id,
					$part->id,
					$part_data['Parts Per Product'],
					prepare_mysql($part_data['Product Part List Note'], false)
				);
				$db->exec($sql);

				if ($row['Product Use Part Properties']=='Yes') {
					$sql=sprintf("select `Product Part Linked Fields` from `Product Part Bridge` where `Product Part Product ID`=%d and `Product Part Part SKU`=%d ",
						$product->id,
						$part->id
					);


					if ($result2=$db->query($sql)) {
						if ($row2 = $result2->fetch()) {

							if ($row2['Product Part Linked Fields']=='') {
								$linked_fields=array();
							}else {
								$linked_fields=json_decode($row2['Product Part Linked Fields'], true);
							}

							$linked_fields['Part Unit Weight']='Product Unit Weight';



							if (count($_parts_data)==1) {
								$_key=key($_parts_data);


								if ($_parts_data[$_key]['Parts Per Product']==1) {

									$linked_fields['Part Unit Dimensions']='Product Unit Dimensions';

								}
							}




						}else {
							print_r($error_info=$db->errorInfo());
							print "$sql\n";
							exit;
						}

						$sql=sprintf("update `Product Part Bridge` set `Product Part Linked Fields`=%s where `Product Part Product ID`=%d and `Product Part Part SKU`=%d ",
							prepare_mysql(json_encode($linked_fields)),
							$product->id,
							$part->id
						);
						$db->exec($sql);

					}else {
						print_r($error_info=$db->errorInfo());
						exit;
					}

				}

				if ($row['Product Use Part Tariff Data']=='Yes') {

					$sql=sprintf("select `Product Part Linked Fields` as `Linked Fields` from `Product Part Bridge` where `Product Part Product ID`=%d and `Product Part Part SKU`=%d ",
						$product->id,
						$part->id
					);
					if ($result2=$db->query($sql)) {
						if ($row2 = $result2->fetch()) {

							if ($row2['Linked Fields']=='') {
								$linked_fields=array();
							}else {
								$linked_fields=json_decode($row2['Linked Fields'], true);
							}

							$linked_fields['Part Tarrif Code']='Product Tariff Code';
							$linked_fields['Part Duty Rate']='Product Duty Rate';

						}

						$sql=sprintf("update `Product Part Bridge` set `Product Part Linked Fields`=%s where `Product Part Product ID`=%d and `Product Part Part SKU`=%d ",
							prepare_mysql(json_encode($linked_fields)),
							$product->id,
							$part->id
						);
						$db->exec($sql);

					}else {
						print_r($error_info=$db->errorInfo());
						exit;
					}

				}

				if ($row['Product Use Part H and S']=='Yes') {

					$sql=sprintf("select `Product Part Linked Fields` as `Linked Fields` from `Product Part Bridge` where `Product Part Product ID`=%d and `Product Part Part SKU`=%d ",
						$product->id,
						$part->id
					);
					if ($result2=$db->query($sql)) {
						if ($row2 = $result2->fetch()) {

							if ($row2['Linked Fields']=='') {
								$linked_fields=array();
							}else {
								$linked_fields=json_decode($row2['Linked Fields'], true);

							}

							$linked_fields['Part UN Number']='Product UN Number';
							$linked_fields['Part UN Class']='Product UN Class';
							$linked_fields['Part Packing Group']='Product Packing Group';
							$linked_fields['Part Proper Shipping Name']='Product Proper Shipping Name';
							$linked_fields['Part Hazard Indentification Number']='Product Hazard Indentification Number';


						}

						$sql=sprintf("update `Product Part Bridge` set `Product Part Linked Fields`=%s where `Product Part Product ID`=%d and `Product Part Part SKU`=%d ",
							prepare_mysql(json_encode($linked_fields)),
							$product->id,
							$part->id
						);
						$db->exec($sql);

					}else {
						print_r($error_info=$db->errorInfo());
						exit;
					}

				}

			}


		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}


}


function update_materials_stats() {

	global $db;

	$sql=sprintf('select * from `Material Dimension`  ');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$material=new Material($row['Material Key']);

			$material->update_stats();




		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}
}




function migrate_part_fields() {
	global $db;

	$sql=sprintf('update  `Part Dimension` set `Part Package Description`=`Part Unit Description`;  ');
	$db->exec($sql);


	//$sql=sprintf('select * from `Part Dimension` where `Part SKU`=5285');
	$sql=sprintf('select * from `Part Dimension`  ');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$part=new Part($row['Part SKU']);


			$materials=get_materials($part->sku);

			if ($materials!='' ) {
				// if(!preg_match('/^\[\{\"name\"\:/', $row['Part Materials']) )
				$part->update(array('Part Materials'=>$materials), 'no_history');
			}


			$num_uk_prod=0;
			$prod_uk=false;
			$products=array();
			$min_units=9999;
			$price='';
			$rrp='';

			foreach (get_product_ids($part) as $product_pid) {
				$product=new Product($product_pid);

				if ($product->id and $product->data['Product Record Type']=='Normal') {

					if ($product->data['Product Store Key']==1 and get_number_of_parts($db, $product)==1) {
						$products[]=array(
							'code'=>$product->data['Product Code'],
							// 'store'=>$product->data['Product Store Key'],
							// 'parts'=>get_number_of_parts($db, $product)
						);

						if ($product->data['Product Units Per Case']<$min_units) {
							$min_units=$product->data['Product Units Per Case'];
						}
						if ($product->get('Product Price')!='')
							$price=$product->get('Product Price')/$product->get('Product Units Per Case');
						if ($product->get('Product RRP')!='')
							$rrp=$product->get('Product RRP')/$product->get('Product Units Per Case');

						$num_uk_prod++;
						$prod_uk=$product;
					}
				}
			}

			//print_r($products);

			if ($num_uk_prod==0) {
				$part->update(array(
						// 'Part Units'=>1,
						'Part Units Per Package'=>1,
						'Part Unit RRP'=>''

					), 'no_history');
			}else if ($num_uk_prod==1) {

				//print_r($prod_uk);

				$part->update(array(
						// 'Part Units'=>$prod_uk->get('Product Units Per Case'),
						'Part Units Per Package'=>$prod_uk->get('Product Units Per Case'),
						'Part Unit Description'=>$prod_uk->get('Product Name'),
						'Part Unit Price'=>$prod_uk->get('Product Price')/$prod_uk->get('Product Units Per Case'),
						'Part Unit RRP'=>($prod_uk->get('Product RRP')==''?'':$prod_uk->get('Product RRP')/$prod_uk->get('Product Units Per Case'))

					), 'no_history');
			}else {

				if ($part->data['Part Status']=='In Use') {

					$part->update(array(
							// 'Part Units'=>$min_units,
							'Part Units Per Package'=>$min_units,
							'Part Unit Price'=>$price,
							'Part Unit RRP'=>$rrp

						), 'no_history');

					//print "Cant retrieve units ".$part->sku." ".$part->get('Reference')."\n";
					//print_r($products );

					//exit;
				}
			}



			// print "part: ".$part->get('Reference')."  ".$part->id."\n";
			$dimensions=get_xhtml_dimensions($part, 'Unit');
			if ($dimensions!='') {
				$part->update(array('Part Unit Dimensions'=>$dimensions), 'no_history');
				//print "\npart:".$part->id.' '.$dimensions;
			}

			$dimensions=get_xhtml_dimensions($part, 'Package');
			if ($dimensions!='') {
				$part->update(array('Part Package Dimensions'=>$dimensions), 'no_history');
				// print "\npart:".$part->id.' '.$dimensions;
			}






		}

	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}



}


function get_xhtml_dimensions($part, $tag) {

	$locale='en_GB';

	$dimensions='';
	switch ($part->data["Part $tag Dimensions Type"]) {
	case 'Rectangular':
		if (!$part->data['Part '.$tag.' Dimensions Width Display'] or  !$part->data['Part '.$tag.' Dimensions Depth Display']  or  !$part->data['Part '.$tag.' Dimensions Length Display']) {
			$dimensions='';
		}else {
			$dimensions=number($part->data['Part '.$tag.' Dimensions Length Display']).'x'.number($part->data['Part '.$tag.' Dimensions Width Display']).'x'.number($part->data['Part '.$tag.' Dimensions Depth Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
		}
		break;
	case 'Cilinder':
		if ( !$part->data['Part '.$tag.' Dimensions Length Display']  or  !$part->data['Part '.$tag.' Dimensions Diameter Display']) {
			$dimensions='';
		}else {
			$dimensions='L:'.number($part->data['Part '.$tag.' Dimensions Length Display']).' &#8709;:'.number($part->data['Part '.$tag.' Dimensions Diameter Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
		}
		break;
	case 'Sphere':
		if (   !$part->data['Part '.$tag.' Dimensions Diameter Display']) {
			$dimensions='';
		}else {
			$dimensions='d:'.number($part->data['Part '.$tag.' Dimensions Diameter Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
		}
		break;
	case 'String':
		if (   !$part->data['Part '.$tag.' Dimensions Length Display']) {
			$dimensions='';
		}else {
			$dimensions='L:'.number($part->data['Part '.$tag.' Dimensions Length Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
		}
		break;
	case 'Sheet':
		if ( !$part->data['Part '.$tag.' Dimensions Width Display']  or  !$part->data['Part '.$tag.' Dimensions Length Display']) {
			$dimensions='';
		}else {
			$dimensions=number($part->data['Part '.$tag.' Dimensions Width Display']).'x'.number($part->data['Part '.$tag.' Dimensions Length Display']).' ('.$part->data['Part '.$tag.' Dimensions Display Units'].')';
		}
		break;
	default:
		$dimensions='';
	}

	return $dimensions;

}


function get_part_list($db, $product_id) {
	$part_list=array();

	$sql=sprintf("select *  from `Product Part Dimension` PPD left join  `Product Part List`       PPL   on (PPL.`Product Part Key`=PPD.`Product Part Key`) where `Product ID`=%d and  `Product Part Most Recent`='Yes' "
		, $product_id
	);

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {


			$part_list[$row['Part SKU']]=$row;

			$part_list[$row['Part SKU']]['part']=new Part($row['Part SKU']);
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}


	return $part_list;
}


function get_number_of_parts($db, $product) {
	$part_list=array();

	$sql=sprintf("select *  from `Product Part Dimension` PPD left join  `Product Part List`       PPL   on (PPL.`Product Part Key`=PPD.`Product Part Key`) where `Product ID`=%d and  `Product Part Most Recent`='Yes' "
		, $product->id
	);

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {
			$part_list[$row['Part SKU']]=$row['Part SKU'];
		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}


	return count($part_list);
}


function get_materials($sku) {



	$materials='';
	$xhtml_materials='';

	$sql=sprintf("select * from `Part Material Bridge` B left join `Material Dimension` MD on (MD.`Material Key`=B.`Material Key`) where `Part SKU`=%d order by `Part Material Key` ",
		$sku

	);
	$res=mysql_query($sql);

	while ($row=mysql_fetch_assoc($res)) {

		if ($row['May Contain']=='Yes') {
			$may_contain_tag='±';
		}else {
			$may_contain_tag='';
		}

		$materials.=sprintf(', %s%s', $may_contain_tag, $row['Material Name']);
		$xhtml_materials.=sprintf(', %s<a href="material.php?id=%d">%s</a>', $may_contain_tag, $row['Material Key'], $row['Material Name']);

		if ($row['Ratio']>0) {
			$materials.=sprintf(' (%s)', percentage($row['Ratio'], 1));
			$xhtml_materials.=sprintf(' (%s)', percentage($row['Ratio'], 1));
		}
	}

	$materials=preg_replace('/^\, /', '', $materials);
	$xhtml_materials=preg_replace('/^\, /', '', $xhtml_materials);
	return $materials;
	return array($materials, $xhtml_materials);

}


function create_families($db, $account) {

	$sql=sprintf("select `Part SKU` from `Part Dimension` ");

	$main_cat=new Category($account->get('Account Part Family Category Key'));

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {

			$part=new Part($row['Part SKU']);
			if (preg_match('/^([a-z0-9]+)\-/i', $part->get('Reference'), $match)) {
				$fam_code=$match[1];
				$sql=sprintf("select `Product Family Code`,`Product Family Name` from `Product Family Dimension` where `Product Family Code`=%s and `Product Family Store Key`=%d ",
					prepare_mysql($fam_code),
					1);


				if ($result2=$db->query($sql)) {
					if ($row2 = $result2->fetch()) {


						$data=array(
							'Category Parent Key'=>$main_cat->id,
							'Category Code'=>$row2['Product Family Code'],
							'Category Label'=>$row2['Product Family Name'],
							'Category Show Subject User Interface'=>'No',
							'Category Show Public New Subject'=>'No'
						);


						$cat=$main_cat->create_category($data);
						$cat->skip_update_sales=true;


						$cat->associate_subject($part->sku);

						$part->update(
							array('Part Family Category Key'=>$cat->id), 'no_history'
						);




					}
				}else {
					print_r($error_info=$db->errorInfo());
					exit;
				}






			}


		}
	}else {
		print_r($error_info=$db->errorInfo());
		exit;
	}




}


function get_product_ids($part) {
	$sql=sprintf("select `Product Part Dimension`.`Product ID` from `Product Part List` left join `Product Part Dimension` on (`Product Part List`.`Product Part Key`=`Product Part Dimension`.`Product Part Key`)   where `Part SKU`=%d and `Product Part Most Recent`='Yes' ", $part->sku);

	$result=mysql_query($sql);
	$product_ids=array();

	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
		$product_ids[$row['Product ID']]= $row['Product ID'];
	}
	return $product_ids;
}


function move_MSDS_attachments($db) {

	$sql=sprintf('select `Part SKU`,`Part MSDS Attachment Bridge Key` from `Part Dimension` where `Part MSDS Attachment Bridge Key`>0  ');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {

			print $row['Part SKU']."\n";
			$sql=sprintf('update `Attachment Bridge` set `Subject`="Part" , `Attachment Subject Type`="MSDS" ,`Attachment Public`="Yes" ,`Attachment Caption`=%s where `Attachment Bridge Key`=%s  ',
				prepare_mysql('MSDS file'),
				$row['Part MSDS Attachment Bridge Key']
			);
			$db->exec($sql);
		}

	}





}


?>
