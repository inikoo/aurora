<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>
 Created: 25 August 2015 12:55:36 GMT+8

 Copyright (c) 2015, Inikoo

 Version 3.0
*/


function get_sections($module,$parent_key=false) {

	$modules=array(

		'customers'=>array(

			'id'=>1,

			'sections'=>array(
				array('label'=>_('Statistics'),'title'=>_('Statistics'),'icon'=>'line-chart','url'=>'customers_stats.php?store='.$parent_key),
				array('label'=>_('Categories'),'title'=>_('Categories'),'icon'=>'cubes','url'=>'customer_categories.php?id=0&store_id='.$parent_key),
				array('label'=>_('Lists'),'title'=>_('Lists'),'icon'=>'list','url'=>'customers_lists.php?store='.$parent_key),
				array('label'=>_('Pending orders'),'title'=>_('Pending orders'),'icon'=>'clock-o','url'=>'store_pending_orders.php?id='.$parent_key),
				array('label'=>_('Customers'),'title'=>_('Customers'),'icon'=>'users','url'=>'customers.php?store='.$parent_key)
			)

		),
		'customers_server'=>array(

			'id'=>2,

			'sections'=>array(
				array('label'=>_('Pending orders (All stores)'),'title'=>_('Pending orders (All stores)'),'icon'=>'clock-o','url'=>'pending_orders.php'),
				array('label'=>_('Customers (All stores)'),'title'=>_('Customers (All stores)'),'icon'=>'bars','url'=>'customers_server.php')

			)

		)
		
	);

    $sections=  $modules[$module]['sections'];
    
    
    return $sections;

}

?>
