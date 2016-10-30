<?php

/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 7 January 2016 at 16:18:05 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

require_once 'class.Timeserie.php';
require_once 'class.Store.php';
require_once 'class.Invoice.php';
require_once 'class.Category.php';
require_once 'class.Supplier.php';

require_once 'utils/date_functions.php';
require_once 'conf/timeseries.php';

$editor=array(
	'Author Name'=>'',
	'Author Alias'=>'',
	'Author Type'=>'',
	'Author Key'=>'',
	'User Key'=>0,
	'Date'=>gmdate('Y-m-d H:i:s')
);

families();
part_families();
suppliers();
stores();


function suppliers() {

	global $db, $editor, $timeseries;

	$sql=sprintf('select `Supplier Key` from `Supplier Dimension`  ');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {

			$supplier=new Supplier($row['Supplier Key']);


			$timeseries_data=$timeseries['Supplier'];

			foreach ($timeseries_data as $timeserie_data) {

				$editor['Date']=gmdate('Y-m-d H:i:s');
				$timeserie_data['editor']=$editor;
				$supplier->create_timeseries($timeserie_data);

			}
		}

	}else {print_r($error_info=$db->errorInfo());exit($sql);}


}


function families() {

	global $db, $editor, $timeseries;

	$sql=sprintf('select `Category Key` from `Category Dimension` where `Category Scope`="Product" and `Category Key`=14797  ');
	$sql=sprintf('select `Category Key` from `Category Dimension` where `Category Scope`="Product" ');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {

			$category=new Category($row['Category Key']);

			if (!array_key_exists($category->get('Category Scope').'Category', $timeseries))
				continue;

			$timeseries_data=$timeseries[$category->get('Category Scope').'Category'];
			print "creating ".$category->get('Code')." category \n";

			foreach ($timeseries_data as $timeserie_data) {

				$editor['Date']=gmdate('Y-m-d H:i:s');
				$timeserie_data['editor']=$editor;
				$category->create_timeseries($timeserie_data);

			}
		}

	}else {print_r($error_info=$db->errorInfo());
		print $sql;
		exit;}
}


function stores() {

	global $db, $editor, $timeseries;
	$sql=sprintf('select `Store Key` from `Store Dimension` ');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {

			$store=new Store($row['Store Key']);

			$timeseries_data=$timeseries['Store'];

			foreach ($timeseries_data as $timeserie_data) {

				$editor['Date']=gmdate('Y-m-d H:i:s');
				$timeserie_data['editor']=$editor;
				$store->create_timeseries($timeserie_data);

			}
		}

	}else {print_r($error_info=$db->errorInfo());exit;}

}


function part_families() {

	global $db, $editor, $timeseries;

	$sql=sprintf('select `Category Key` from `Category Dimension` where `Category Scope`="Part" and `Category Key`=11899  ');
	$sql=sprintf('select `Category Key` from `Category Dimension` where `Category Scope`="Part" order by  `Category Key` desc');

	if ($result=$db->query($sql)) {
		foreach ($result as $row) {

			$category=new Category($row['Category Key']);

		


			if (!array_key_exists($category->get('Category Scope').'Category', $timeseries))
				continue;

			$timeseries_data=$timeseries[$category->get('Category Scope').'Category'];
			print "creating ".$category->get('Code')." category \n";
			foreach ($timeseries_data as $timeserie_data) {

				$editor['Date']=gmdate('Y-m-d H:i:s');
				$timeserie_data['editor']=$editor;

				$category->create_timeseries($timeserie_data);

			}
		}

	}else {
		print_r($error_info=$db->errorInfo());
		print $sql;
		exit;
	}

}


?>
