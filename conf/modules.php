<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 25 August 2015 12:55:36 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_sections($module,$parent_key=false) {

	$modules=array(

		'customers'=>array(
			'sections'=>array(
				array('label'=>_('Statistics'),'title'=>_('Statistics'),'icon'=>'line-chart','url'=>'customers_stats.php?store='.$parent_key),
				array('label'=>_('Categories'),'title'=>_('Categories'),'icon'=>'sitemap','url'=>'customer_categories.php?id=0&store_id='.$parent_key),
				array('label'=>_('Lists'),'title'=>_('Lists'),'icon'=>'list','url'=>'customers_lists.php?store='.$parent_key),
				array('label'=>_('Pending orders'),'title'=>_('Pending orders'),'icon'=>'clock-o','url'=>'store_pending_orders.php?id='.$parent_key),
				array('label'=>_('Customers'),'title'=>_('Customers'),'icon'=>'users','url'=>'customers.php?store='.$parent_key)
			)
		),
		'customers_server'=>array(



			'sections'=>array(
				array('label'=>_('Pending orders (All stores)'),'title'=>_('Pending orders (All stores)'),'icon'=>'clock-o','url'=>'pending_orders.php'),
				array('label'=>_('Customers (All stores)'),'title'=>_('Customers (All stores)'),'icon'=>'bars','url'=>'customers_server.php')

			)

		),
		'orders'=>array(
			'sections'=>array(
				array('label'=>_('Categories'),'title'=>_('Categories'),'icon'=>'sitemap','id'=>'categories'),
				array('label'=>_('Payments'),'title'=>_('Payments'),'icon'=>'credit-card','id'=>'payments'),
				array('label'=>_('Delivery Notes'),'title'=>_('Delivery Notes'),'icon'=>'truck','id'=>'dn'),
				array('label'=>_('Invoices'),'title'=>_('Invoices'),'icon'=>'file-o','id'=>'invoices'),
				array('label'=>_('Orders'),'title'=>_('Orders'),'icon'=>'shopping-cart','id'=>'orders')
			)
		),

	);

	$sections=  $modules[$module]['sections'];


	return $sections;

}

?>
