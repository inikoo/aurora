<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 22 January 2016 at 18:04:24 GMT+8, Kuala Lumpur, Malaysia
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

require_once 'class.Supplier.php';
require_once 'class.Store.php';
require_once 'class.Address.php';
require_once 'class.SupplierProduct.php';
require_once 'class.Part.php';
require_once 'class.SupplierPart.php';


$sql=sprintf('select * from `Supplier Product Dimension`  order by `Supplier Product ID`  desc  ' );

if ($result2=$db->query($sql)) {
	foreach ($result2 as $row2) {
		$sp=new SupplierProduct('pid', $row2['Supplier Product ID']);
		$part_data=$sp->get_parts();


		if (count($part_data)>1) {

			foreach ($part_data as $_key=>$_part_data) {
				if ($_part_data['part']->data['Part Status']=='Not In Use') {

					unset($part_data[$_key]);
				}

			}


		}


		if (count($part_data)>1) {

			foreach ($part_data as $_part_data) {
				//print $_part_data['part']->sku.','.$_part_data['part']->data['Part Reference'].','.$_part_data['part']->data['Part Status'].','.$sp->id."\n";
			}


		}


		foreach ($part_data as $_part_data) {

			$sp_ref=preg_replace('/^\?/', '', $sp->data['Supplier Product Code']);
			if ($sp_ref=='') {
				$sp_ref=$_part_data['part']->get('Reference');
			}

			if ($sp->data['Supplier Product State']=='Available') {
				$status='Available';
			}else {
				$status='Discontinued';
			}

			if ( $status=='Discontinued') {
				$to=$sp->data['Supplier Product Valid To'];
			}else {
				$to='';
			}



			$sp_data=array(
				'Supplier Part Supplier Key'=>$sp->data['Supplier Key'],
				'Supplier Part Part SKU'=>$_part_data['part']->sku,
				'Supplier Part Reference'=>$sp_ref,
				'Supplier Part Status'=>$status,
				'Supplier Part From'=>$sp->data['Supplier Product Valid From'],
				'Supplier Part To'=>$to,
				'Supplier Part Unit Cost'=>$sp->data['Supplier Product Cost Per Case'],
				'Supplier Part Currency Code'=>$sp->data['Supplier Product Currency']


			);
			$spart=new SupplierPart('find', $sp_data, 'create');

			if ($spart->found) {
				print "Duplicate ".$spart->duplicated_field.": ".$spart->get('Reference')."   \n";
				$spart->update_historic_object();
				
				//print_r($sp_data);
			}else {

				if ($spart->error) {
					print "Error ".$spart->msg."\n";
					print_r($sp_data);
				}
			}

            
			//print $_part_data['part']->sku.','.$_part_data['part']->data['Part Reference'].','.$_part_data['part']->data['Part Status'].','.$sp->id."\n";
			break;
		}


	}

}else {
	print_r($error_info=$db->errorInfo());
	exit;
}







?>
