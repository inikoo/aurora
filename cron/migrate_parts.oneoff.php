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




//$sql=sprintf('select * from `Product Dimension` where `Product ID`=25088');

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
			// print "$sql\n";
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


exit;


$sql=sprintf('update  `Part Dimension` set `Part Package Description`=`Part Unit Description`;  ');
$db->exec($sql);


$sql=sprintf('select `Part SKU` from `Part Dimension`  ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		$sql="insert into `Part Data` (`Part SKU`) values(".$row['Part SKU'].");";
		$db->exec($sql);


	}
}



$sql=sprintf('select * from `Part Dimension` where `Part SKU`=1182');

$sql=sprintf('select * from `Part Dimension`  ');

if ($result=$db->query($sql)) {
	foreach ($result as $row) {
		$part=new Part($row['Part SKU']);

		/*

		if (!($row['Part Barcode Data Source']=='Other' and $row['Part Barcode Data']=='')) {

			$barcode_data=array(
				'type'=>$row['Part Barcode Type'],
				'source'=>$row['Part Barcode Data Source'],
				'data'=>$row['Part Barcode Data']);


		}
		$part->update(array('Part Barcode'=>json_encode($barcode_data)), 'no_history');
*/
		if ($row['Part Materials']!='' and !preg_match('/^\[\{\"name\"\:/', $row['Part Materials'])  ) {
			//print $row['Part SKU'].' '.$row['Part Materials']."\n";



			$part->update(array('Part Materials'=>$row['Part Materials']), 'no_history');

		}


		$num_uk_prod=0;
		$prod_uk=false;

		$products=array();

		$min_units=9999;

		foreach ($part->get_product_ids() as $product_pid) {
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

					$num_uk_prod++;
					$prod_uk=$product;
				}
			}
		}

		//print_r($products);

		if ($num_uk_prod==0) {
			$part->update(array(
					'Part Units'=>1,

				), 'no_history');
		}else if ($num_uk_prod==1) {
			$part->update(array(
					'Part Units'=>$prod_uk->get('Product Units Per Case'),
					'Part Unit Description'=>$prod_uk->get('Product Name'),

				), 'no_history');
		}else {

			if ($part->data['Part Status']=='In Use') {

				$part->update(array(
						'Part Units'=>$min_units,

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



		$sql=sprintf('select B.`Category Key` from `Category Bridge` B left join `Category Dimension` C on (B.`Category Key`=C.`Category Key`) where `Category Root Key`=%d and  `Category Head Key`=B.`Category Key` and `Subject`="Part" and `Subject Key`=%d',
			$account->get('Account Part Family Category Key'),
			$part->id
		);
		if ($result2=$db->query($sql)) {
			if ($row2 = $result2->fetch()) {
				$part->update(array('Part Family Category Key'=>$row2['Category Key']), 'no_history');

			}
		}else {
			print_r($error_info=$this->db->errorInfo());
			exit;
		}


	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
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


?>
