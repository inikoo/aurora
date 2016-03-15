<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 13 March 2016 at 10:25:00 GMT+8, Kaula Lumpur, Malaysia

 Copyright (c) 2016, Inikoo

 Version 3.0
*/



$user_rights=array(

'UV'=>array( 'Right Type' => 'View', 'Right Name' => 'users'),
'UE'=>array( 'Right Type' => 'Edit', 'Right Name' => 'users'),
'UD'=>array( 'Right Type' => 'Delete', 'Right Name' => 'users'),
'UC'=>array( 'Right Type' => 'Create', 'Right Name' => 'users'),
'AV'=>array( 'Right Type' => 'View', 'Right Name' => 'account'),
'AE'=>array( 'Right Type' => 'Edit', 'Right Name' => 'account'),
'AD'=>array( 'Right Type' => 'Delete', 'Right Name' => 'account'),
'AC'=>array( 'Right Type' => 'Create', 'Right Name' => 'account'),
'EV'=>array( 'Right Type' => 'View', 'Right Name' => 'staff'),
'EC'=>array( 'Right Type' => 'Create', 'Right Name' => 'staff'),

);

/*
function get_user_rights() {


	$rights = array(
		array('Right Key' => '68', 'Right Type' => 'View', 'Right Name' => 'account', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '14', 'Right Type' => 'View', 'Right Name' => 'contacts', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '9', 'Right Type' => 'View', 'Right Name' => 'customers', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '37', 'Right Type' => 'View', 'Right Name' => 'locations', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '60', 'Right Type' => 'View', 'Right Name' => 'marketing', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '10', 'Right Type' => 'View', 'Right Name' => 'orders', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '54', 'Right Type' => 'View', 'Right Name' => 'parts', 'Right Access' => 'None', 'Right Access Keys' => ''),
		array('Right Key' => '22', 'Right Type' => 'View', 'Right Name' => 'product departments', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '26', 'Right Type' => 'View', 'Right Name' => 'product families', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '1', 'Right Type' => 'View', 'Right Name' => 'product sales', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '30', 'Right Type' => 'View', 'Right Name' => 'product stock', 'Right Access' => 'None', 'Right Access Keys' => ''),
		array('Right Key' => '3', 'Right Type' => 'View', 'Right Name' => 'products', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '41', 'Right Type' => 'View', 'Right Name' => 'reports', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '64', 'Right Type' => 'View', 'Right Name' => 'sites', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '46', 'Right Type' => 'View', 'Right Name' => 'staff', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '18', 'Right Type' => 'View', 'Right Name' => 'stores', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '58', 'Right Type' => 'View', 'Right Name' => 'supplier sales', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '59', 'Right Type' => 'View', 'Right Name' => 'supplier stock', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '50', 'Right Type' => 'View', 'Right Name' => 'suppliers', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '42', 'Right Type' => 'View', 'Right Name' => 'users', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '33', 'Right Type' => 'View', 'Right Name' => 'warehouses', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '69', 'Right Type' => 'Edit', 'Right Name' => 'account', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '74', 'Right Type' => 'Edit', 'Right Name' => 'assign_pp', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '15', 'Right Type' => 'Edit', 'Right Name' => 'contacts', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '8', 'Right Type' => 'Edit', 'Right Name' => 'customers', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '38', 'Right Type' => 'Edit', 'Right Name' => 'locations', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '61', 'Right Type' => 'Edit', 'Right Name' => 'marketing', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '11', 'Right Type' => 'Edit', 'Right Name' => 'orders', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '76', 'Right Type' => 'Edit', 'Right Name' => 'pack', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '55', 'Right Type' => 'Edit', 'Right Name' => 'parts', 'Right Access' => 'None', 'Right Access Keys' => ''),
		array('Right Key' => '75', 'Right Type' => 'Edit', 'Right Name' => 'pick', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '23', 'Right Type' => 'Edit', 'Right Name' => 'product departments', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '27', 'Right Type' => 'Edit', 'Right Name' => 'product families', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '31', 'Right Type' => 'Edit', 'Right Name' => 'product stock', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '2', 'Right Type' => 'Edit', 'Right Name' => 'products', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '65', 'Right Type' => 'Edit', 'Right Name' => 'sites', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '47', 'Right Type' => 'Edit', 'Right Name' => 'staff', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '19', 'Right Type' => 'Edit', 'Right Name' => 'stores', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '51', 'Right Type' => 'Edit', 'Right Name' => 'suppliers', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '43', 'Right Type' => 'Edit', 'Right Name' => 'users', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '34', 'Right Type' => 'Edit', 'Right Name' => 'warehouses', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '73', 'Right Type' => 'Delete', 'Right Name' => 'account', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '16', 'Right Type' => 'Delete', 'Right Name' => 'contacts', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '7', 'Right Type' => 'Delete', 'Right Name' => 'customers', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '39', 'Right Type' => 'Delete', 'Right Name' => 'locations', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '62', 'Right Type' => 'Delete', 'Right Name' => 'marketing', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '12', 'Right Type' => 'Delete', 'Right Name' => 'orders', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '56', 'Right Type' => 'Delete', 'Right Name' => 'parts', 'Right Access' => 'None', 'Right Access Keys' => ''),
		array('Right Key' => '24', 'Right Type' => 'Delete', 'Right Name' => 'product departments', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '28', 'Right Type' => 'Delete', 'Right Name' => 'product families', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '32', 'Right Type' => 'Delete', 'Right Name' => 'product stock', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '4', 'Right Type' => 'Delete', 'Right Name' => 'products', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '66', 'Right Type' => 'Delete', 'Right Name' => 'sites', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '48', 'Right Type' => 'Delete', 'Right Name' => 'staff', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '20', 'Right Type' => 'Delete', 'Right Name' => 'stores', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '52', 'Right Type' => 'Delete', 'Right Name' => 'suppliers', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '44', 'Right Type' => 'Delete', 'Right Name' => 'users', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '35', 'Right Type' => 'Delete', 'Right Name' => 'warehouses', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '71', 'Right Type' => 'Create', 'Right Name' => 'account', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '17', 'Right Type' => 'Create', 'Right Name' => 'contacts', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '6', 'Right Type' => 'Create', 'Right Name' => 'customers', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '40', 'Right Type' => 'Create', 'Right Name' => 'locations', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '63', 'Right Type' => 'Create', 'Right Name' => 'marketing', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '13', 'Right Type' => 'Create', 'Right Name' => 'orders', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '57', 'Right Type' => 'Create', 'Right Name' => 'parts', 'Right Access' => 'None', 'Right Access Keys' => ''),
		array('Right Key' => '25', 'Right Type' => 'Create', 'Right Name' => 'product departments', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '29', 'Right Type' => 'Create', 'Right Name' => 'product families', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '5', 'Right Type' => 'Create', 'Right Name' => 'products', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '67', 'Right Type' => 'Create', 'Right Name' => 'sites', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '49', 'Right Type' => 'Create', 'Right Name' => 'staff', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '21', 'Right Type' => 'Create', 'Right Name' => 'stores', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '53', 'Right Type' => 'Create', 'Right Name' => 'suppliers', 'Right Access' => 'None', 'Right Access Keys' => ''),
		array('Right Key' => '45', 'Right Type' => 'Create', 'Right Name' => 'users', 'Right Access' => 'All', 'Right Access Keys' => ''),
		array('Right Key' => '36', 'Right Type' => 'Create', 'Right Name' => 'warehouses', 'Right Access' => 'All', 'Right Access Keys' => '')
	);
	return $rights;

}
*/

?>
