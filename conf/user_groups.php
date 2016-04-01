<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 14 November 2015 at 16:45:03 GMT, Sheffield UK

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

/*
to delete
	4=>

7=>array(
		'Key'=>7,
		'Name'=>_('Product Manager'),
		'View'=>'','Edit'=>''
	),
		12=>array(
		'Key'=>12,
		'Name'=>_('Warehouse Manager'),
		'View'=>'','Edit'=>''
	),
*/


$user_groups=array(

	1=>array(
		'Key'=>1,
		'Name'=>_('Administrator'),
		'View'=>'<i title="'._('Account').'" class="fa fa-star fa-fw"> <i title="'._('System users').'" class="fa fa-male fa-fw"> <i title="'._('Settings').'" class="fa fa-cog fa-fw"></i>',
		'Edit'=>'<i title="'._('Account').'" class="fa fa-star fa-fw"> <i title="'._('System users').'" class="fa fa-male fa-fw"> <i title="'._('Settings').'" class="fa fa-cog fa-fw"></i>',
		'Rights'=>array('AV', 'AE', 'AC', 'AD', 'UV', 'UE', 'UC', 'UD', 'EV', 'EC')
	),
	2=>array(
		'Key'=>2,
		'Name'=>_('Customer Services'),
		'View'=>'<i title="'._('Customers').'" class="fa fa-users fa-fw"> <i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw"> <i title="'._('Websites').'" class="fa fa-globe fa-fw"></i> <i  title="'._('Products').'" class="fa fa-square-o fa-fw"></i>',
		'Edit'=>'<i title="'._('Customers').'" class="fa fa-users fa-fw"> <i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw">',
		'Rights'=>array('CV', 'CE', 'CC', 'CD', 'SV', 'WV')

	),
	3=>array(
		'Key'=>3,
		'Name'=>_('Goods in (Stock control)'),
		'View'=>'<i  title="'._('Products').'" class="fa fa-square-o fa-fw"></i> <i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i  title="'._('Warehouse (Locations)').'" class="fa fa-th-large fa-fw"></i>',
		'Edit'=>'<i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i  title="'._('Warehouse (Locations)').'" class="fa fa-th-large fa-fw"></i>',
		'Rights'=>array('SV', 'LV', 'LC', 'LD', 'LE', 'PV', 'PC', 'PD', 'PE')

	),
	4=>array(
		'Key'=>4,
		'Name'=>_('Production'),
		'View'=>'<i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i  title="'._('Warehouse (Locations)').'" class="fa fa-th-large fa-fw"></i>',
		'Edit'=>'',
		'Rights'=>array('LV', 'PV', 'FV')

	),
	5=>array(
		'Key'=>5,
		'Name'=>_('Sales Intelligence'),
		'View'=>'<i title="'._('Customers').'" class="fa fa-users fa-fw"> <i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw"> <i title="'._('Websites').'" class="fa fa-globe fa-fw"></i> <i title="'._('Marketing').'" class="fa fa-bullhorn fa-fw"></i> <i  title="'._('Products').'" class="fa fa-square-o fa-fw"></i>',
		'Edit'=>'',
		'Rights'=>array()

	),
	6=>array(
		'Key'=>6,
		'Name'=>_('Human Resources'),
		'View'=>'<i title="'._('Manpower').'" class="fa fa-hand-rock-o fa-fw"></i> ',
		'Edit'=>'<i title="'._('Manpower').'"class="fa fa-hand-rock-o fa-fw"></i> ',
		'Rights'=>array('EV', 'EE', 'ED', 'EC')

	),
	8=>array(
		'Key'=>8,
		'Name'=>_('Buyer'),
		'View'=>' <i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw"> <i title="'._('Websites').'" class="fa fa-globe fa-fw"></i> <i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i title="'._('Suppliers').'" class="fa fa-industry fa-fw"></i>',
		'Edit'=>'<i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i title="'._('Suppliers').'" class="fa fa-industry fa-fw"></i>',
		'Rights'=>array()

	),
	9=>array(
		'Key'=>9,
		'Name'=>_('Marketing'),
		'View'=>'<i title="'._('Customers').'" class="fa fa-users fa-fw"> <i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw"> <i title="'._('Websites').'" class="fa fa-globe fa-fw"></i> <i title="'._('Marketing').'" class="fa fa-bullhorn fa-fw"></i> <i  title="'._('Products').'" class="fa fa-square-o fa-fw"></i> <i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i title="'._('Suppliers').'" class="fa fa-industry fa-fw"></i>',
		'Edit'=>'<i title="'._('Websites').'" class="fa fa-globe fa-fw"></i> <i title="'._('Marketing').'" class="fa fa-bullhorn fa-fw"></i> <i  title="'._('Products').'" class="fa fa-square-o fa-fw"></i>',
		'Rights'=>array('CV', 'SV')
	),


	10=>array(
		'Key'=>10,
		'Name'=>_('Webmaster'),
		'View'=>'<i title="'._('Websites').'" class="fa fa-globe fa-fw"></i>',
		'Edit'=>'<i title="'._('Websites').'" class="fa fa-globe fa-fw"></i>',
		'Rights'=>array('WV', 'WE', 'WD', 'WC', 'SV')

	),
	11=>array(
		'Key'=>11,
		'Name'=>_('Goods out'),
		'View'=>' <i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw"> <i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i  title="'._('Warehouse (Locations)').'" class="fa fa-th-large fa-fw"></i>',
		'Edit'=>'',
		'Rights'=>array('OV', 'PV', 'LV')

	),
	14=>array(
		'Key'=>14,
		'Name'=>_('Financial Intelligence'),
		'View'=>'<i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw"> <i title="'._('Marketing').'" class="fa fa-bullhorn fa-fw"></i> <i  title="'._('Products').'" class="fa fa-square-o fa-fw"></i> <i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i  title="'._('Warehouse (Locations)').'" class="fa fa-th-large fa-fw"></i> <i title="'._('Suppliers').'" class="fa fa-industry fa-fw"></i> <i title="'._('Manpower').'" class="fa fa-hand-rock-o fa-fw"></i>',
		'Edit'=>'',
		'Rights'=>array()
	),
	15=>array(
		'Key'=>15,
		'Name'=>_('Supply Intelligence'),
		'View'=>' <i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw"> <i  title="'._('Products').'" class="fa fa-square-o fa-fw"></i> <i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i  title="'._('Warehouse (Locations)').'" class="fa fa-th-large fa-fw"></i> <i title="'._('Suppliers').'" class="fa fa-industry fa-fw"></i>',
		'Edit'=>'',
		'Rights'=>array()

	),




);

?>
