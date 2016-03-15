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
         'Rights'=>array('AV','AE','AC','AD','UV','UE','UC','UD','EV','EC')   
	),
	2=>array(
		'Key'=>2,
		'Name'=>_('Customer Services'),
		'View'=>'<i title="'._('Customers').'" class="fa fa-users fa-fw"> <i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw"> <i title="'._('Websites').'" class="fa fa-globe fa-fw"></i> <i  title="'._('Products').'" class="fa fa-square-o fa-fw"></i>',
		'Edit'=>'<i title="'._('Customers').'" class="fa fa-users fa-fw"> <i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw">'
	),
	9=>array(
		'Key'=>9,
		'Name'=>_('Marketing'),
		'View'=>'<i title="'._('Customers').'" class="fa fa-users fa-fw"> <i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw"> <i title="'._('Websites').'" class="fa fa-globe fa-fw"></i> <i title="'._('Marketing').'" class="fa fa-bullhorn fa-fw"></i> <i  title="'._('Products').'" class="fa fa-square-o fa-fw"></i> <i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i title="'._('Suppliers').'" class="fa fa-industry fa-fw"></i>',
		'Edit'=>'<i title="'._('Websites').'" class="fa fa-globe fa-fw"></i> <i title="'._('Marketing').'" class="fa fa-bullhorn fa-fw"></i> <i  title="'._('Products').'" class="fa fa-square-o fa-fw"></i>'
	),
	8=>array(
		'Key'=>8,
		'Name'=>_('Buyer'),
		'View'=>' <i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw"> <i title="'._('Websites').'" class="fa fa-globe fa-fw"></i> <i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i title="'._('Suppliers').'" class="fa fa-industry fa-fw"></i>',
		'Edit'=>'<i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i title="'._('Suppliers').'" class="fa fa-industry fa-fw"></i>'
	),
	3=>array(
		'Key'=>3,
		'Name'=>_('Goods in (Stock control)'),
		'View'=>'<i  title="'._('Products').'" class="fa fa-square-o fa-fw"></i> <i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i  title="'._('Warehouse (Locations)').'" class="fa fa-th-large fa-fw"></i>',
		'Edit'=>'<i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i  title="'._('Warehouse (Locations)').'" class="fa fa-th-large fa-fw"></i>',
	),
	11=>array(
		'Key'=>11,
		'Name'=>_('Goods out'),
		'View'=>' <i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw"> <i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i  title="'._('Warehouse (Locations)').'" class="fa fa-th-large fa-fw"></i>',
		'Edit'=>''
	),
	10=>array(
		'Key'=>10,
		'Name'=>_('Webmaster'),
		'View'=>'<i title="'._('Websites').'" class="fa fa-globe fa-fw"></i>',
		'Edit'=>'<i title="'._('Websites').'" class="fa fa-globe fa-fw"></i>'
	),
	6=>array(
		'Key'=>6,
		'Name'=>_('Human Resources'),
		'View'=>'<i title="'._('Manpower').'" class="fa fa-hand-rock-o fa-fw"></i> ',
		'Edit'=>'<i title="'._('Manpower').'"class="fa fa-hand-rock-o fa-fw"></i> '
	),
	15=>array(
		'Key'=>15,
		'Name'=>_('Supply Intelligence'),
		'View'=>' <i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw"> <i  title="'._('Products').'" class="fa fa-square-o fa-fw"></i> <i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i  title="'._('Warehouse (Locations)').'" class="fa fa-th-large fa-fw"></i> <i title="'._('Suppliers').'" class="fa fa-industry fa-fw"></i>',
		'Edit'=>''
	),
	14=>array(
		'Key'=>14,
		'Name'=>_('Financial Intelligence'),
		'View'=>'<i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw"> <i title="'._('Marketing').'" class="fa fa-bullhorn fa-fw"></i> <i  title="'._('Products').'" class="fa fa-square-o fa-fw"></i> <i  title="'._('Inventory').'" class="fa fa-square fa-fw"></i> <i  title="'._('Warehouse (Locations)').'" class="fa fa-th-large fa-fw"></i> <i title="'._('Suppliers').'" class="fa fa-industry fa-fw"></i> <i title="'._('Manpower').'" class="fa fa-hand-rock-o fa-fw"></i>',
		'Edit'=>''
	),
	5=>array(
		'Key'=>5,
		'Name'=>_('Sales Intelligence'),
		'View'=>'<i title="'._('Customers').'" class="fa fa-users fa-fw"> <i title="'._('Orders').'" class="fa fa-shopping-cart fa-fw"> <i title="'._('Websites').'" class="fa fa-globe fa-fw"></i> <i title="'._('Marketing').'" class="fa fa-bullhorn fa-fw"></i> <i  title="'._('Products').'" class="fa fa-square-o fa-fw"></i>',
		'Edit'=>''
	),









);

/*

function get_user_groups() {


	$User_Group_Dimension = array(
		array('User Group Key' => '1', 'User Group Name' => _('Administrator'), 'User Group Description' => _('Administrator account should be used only to configure system wide preferences and manage other users')),
		array('User Group Key' => '2', 'User Group Name' => _('Customer Services'), 'User Group Description' => _('Customer service is a series of activities designed to enhance the level of customer satisfaction – that is, the feeling that a product or service has met the customer expectation')),
		array('User Group Key' => '3', 'User Group Name' => _('Stock Controller'), 'User Group Description' => _('Stock control is used to evaluate how much stock is used. It is also used to know what is needed to be ordered. Stock control can only happen if a stock take has taken place. Stock rotation must be put into use with stock control by using the oldest products before the newer products')),
		array('User Group Key' => '4', 'User Group Name' => _('Export Services'), 'User Group Description' => _('Specialized Customer Service for export orders')),
		array('User Group Key' => '5', 'User Group Name' => _('Sales Intelligence'), 'User Group Description' => _('The term Sales intelligence (SI) refers to technologies, applications and practices for the collection, integration, analysis, and presentation of Sales information. The purpose of Sales intelligence is to support better business decision making by sales people')),
		array('User Group Key' => '6', 'User Group Name' => _('Human Resources'), 'User Group Description' => _("Human resource management's objective, on the other hand, is to maximize the return on investment from the organization's human capital and minimize financial risk. It is the responsibility of human resource managers in a corporate context to conduct these activities in an effective, legal, fair, and consistent manner")),
		array('User Group Key' => '7', 'User Group Name' => _('Product Manager'), 'User Group Description' => _("A product manager researches, selects, develops, and places a company's products")),
		array('User Group Key' => '8', 'User Group Name' => _('Buyer'), 'User Group Description' => _("A buyer's primary responsibility is obtaining the highest quality goods at the lowest cost. This usually requires research, writing requests for bids, proposals or quotes, and evaluation information received")),
		array('User Group Key' => '9', 'User Group Name' => _('Marketing'), 'User Group Description' => _('Marketing is the process of performing market research, selling products and/or services to customers and promoting them via advertising to further enhance sales')),
		array('User Group Key' => '10', 'User Group Name' => _('Webmaster'), 'User Group Description' => _('Management of content, advertising, marketing, and order fulfillment for the website')),
		array('User Group Key' => '11', 'User Group Name' => _('Pickers & Packers'), 'User Group Description' => _('Warehouse Opereatives')),
		array('User Group Key' => '12', 'User Group Name' => _('Warehouse Manager'), 'User Group Description' => _('Can assign pickers & packers'))
	);

	return $User_Group_Dimension;

}


function get_group_rights() {

	$User_Group_Rights_Bridge = array(
		array('Group Key' => '1', 'Right Key' => '42'),
		array('Group Key' => '1', 'Right Key' => '43'),
		array('Group Key' => '1', 'Right Key' => '44'),
		array('Group Key' => '1', 'Right Key' => '45'),
		array('Group Key' => '1', 'Right Key' => '68'),
		array('Group Key' => '1', 'Right Key' => '69'),
		array('Group Key' => '1', 'Right Key' => '71'),
		array('Group Key' => '1', 'Right Key' => '73'),
		array('Group Key' => '2', 'Right Key' => '1'),
		array('Group Key' => '2', 'Right Key' => '3'),
		array('Group Key' => '2', 'Right Key' => '6'),
		array('Group Key' => '2', 'Right Key' => '7'),
		array('Group Key' => '2', 'Right Key' => '8'),
		array('Group Key' => '2', 'Right Key' => '9'),
		array('Group Key' => '2', 'Right Key' => '10'),
		array('Group Key' => '2', 'Right Key' => '11'),
		array('Group Key' => '2', 'Right Key' => '12'),
		array('Group Key' => '2', 'Right Key' => '13'),
		array('Group Key' => '2', 'Right Key' => '14'),
		array('Group Key' => '2', 'Right Key' => '15'),
		array('Group Key' => '2', 'Right Key' => '16'),
		array('Group Key' => '2', 'Right Key' => '17'),
		array('Group Key' => '2', 'Right Key' => '18'),
		array('Group Key' => '2', 'Right Key' => '22'),
		array('Group Key' => '2', 'Right Key' => '26'),
		array('Group Key' => '3', 'Right Key' => '30'),
		array('Group Key' => '3', 'Right Key' => '31'),
		array('Group Key' => '3', 'Right Key' => '32'),
		array('Group Key' => '3', 'Right Key' => '33'),
		array('Group Key' => '3', 'Right Key' => '34'),
		array('Group Key' => '3', 'Right Key' => '35'),
		array('Group Key' => '3', 'Right Key' => '36'),
		array('Group Key' => '3', 'Right Key' => '37'),
		array('Group Key' => '3', 'Right Key' => '38'),
		array('Group Key' => '3', 'Right Key' => '39'),
		array('Group Key' => '3', 'Right Key' => '40'),
		array('Group Key' => '3', 'Right Key' => '54'),
		array('Group Key' => '3', 'Right Key' => '55'),
		array('Group Key' => '3', 'Right Key' => '56'),
		array('Group Key' => '3', 'Right Key' => '57'),
		array('Group Key' => '3', 'Right Key' => '58'),
		array('Group Key' => '3', 'Right Key' => '59'),
		array('Group Key' => '5', 'Right Key' => '41'),
		array('Group Key' => '6', 'Right Key' => '46'),
		array('Group Key' => '6', 'Right Key' => '47'),
		array('Group Key' => '6', 'Right Key' => '48'),
		array('Group Key' => '6', 'Right Key' => '49'),
		array('Group Key' => '7', 'Right Key' => '1'),
		array('Group Key' => '7', 'Right Key' => '2'),
		array('Group Key' => '7', 'Right Key' => '3'),
		array('Group Key' => '7', 'Right Key' => '4'),
		array('Group Key' => '7', 'Right Key' => '5'),
		array('Group Key' => '7', 'Right Key' => '18'),
		array('Group Key' => '7', 'Right Key' => '19'),
		array('Group Key' => '7', 'Right Key' => '20'),
		array('Group Key' => '7', 'Right Key' => '21'),
		array('Group Key' => '7', 'Right Key' => '22'),
		array('Group Key' => '7', 'Right Key' => '23'),
		array('Group Key' => '7', 'Right Key' => '24'),
		array('Group Key' => '7', 'Right Key' => '25'),
		array('Group Key' => '7', 'Right Key' => '26'),
		array('Group Key' => '7', 'Right Key' => '27'),
		array('Group Key' => '7', 'Right Key' => '28'),
		array('Group Key' => '7', 'Right Key' => '29'),
		array('Group Key' => '8', 'Right Key' => '50'),
		array('Group Key' => '8', 'Right Key' => '51'),
		array('Group Key' => '8', 'Right Key' => '52'),
		array('Group Key' => '8', 'Right Key' => '53'),
		array('Group Key' => '9', 'Right Key' => '60'),
		array('Group Key' => '9', 'Right Key' => '61'),
		array('Group Key' => '9', 'Right Key' => '62'),
		array('Group Key' => '9', 'Right Key' => '63'),
		array('Group Key' => '10', 'Right Key' => '64'),
		array('Group Key' => '10', 'Right Key' => '65'),
		array('Group Key' => '10', 'Right Key' => '66'),
		array('Group Key' => '10', 'Right Key' => '67'),
		array('Group Key' => '11', 'Right Key' => '33'),
		array('Group Key' => '11', 'Right Key' => '37'),
		array('Group Key' => '11', 'Right Key' => '54'),
		array('Group Key' => '11', 'Right Key' => '75'),
		array('Group Key' => '11', 'Right Key' => '76'),
		array('Group Key' => '12', 'Right Key' => '33'),
		array('Group Key' => '12', 'Right Key' => '37'),
		array('Group Key' => '12', 'Right Key' => '54'),
		array('Group Key' => '12', 'Right Key' => '74'),
		array('Group Key' => '12', 'Right Key' => '75'),
		array('Group Key' => '12', 'Right Key' => '76')
	);

	return $User_Group_Rights_Bridge ;

}

*/
?>
