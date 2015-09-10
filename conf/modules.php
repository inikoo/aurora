<?php
/*

 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 25 August 2015 12:55:36 GMT+8 Singapore

 Copyright (c) 2015, Inikoo

 Version 3.0
*/

$modules=array(
	'dashboard'=>array(
		'section'=>'dashboard',
		'parent'=>'none',
		'parent_type'=>'none',
		'sections'=>array(
			'dashboard'=>array('label'=>_('Home'),'title'=>_('Home'),'icon'=>'home'),
		)

	),


	'customers'=>array(
		'section'=>'customers',
		'parent'=>'store',
		'parent_type'=>'key',
		'sections'=>array(
			'dashboard'=>array('type'=>'navigation','label'=>_('Dashboard'),'title'=>_("Customer's dashboard"),'icon'=>'dashboard','reference'=>'customers/dashboard'),

			'customers'=>array('type'=>'navigation','label'=>_('Customers'),'title'=>_('Customers'),'icon'=>'users','reference'=>'customers/%d',



			),
			'lists'=>array('type'=>'navigation','label'=>_('Lists'),'title'=>_('Lists'),'icon'=>'list','reference'=>'customers/lists/%d'),
			'categories'=>array('type'=>'navigation','label'=>_('Categories'),'title'=>_('Categories'),'icon'=>'sitemap','reference'=>'customers/categories/%d'),
			'statistics'=>array('type'=>'navigation','label'=>_('Statistics'),'title'=>_('Statistics'),'icon'=>'line-chart','reference'=>'customers/statistics/%d',
				'tabs'=>array(
					'contacts'=>array('label'=>_('Contacts'),'title'=>_('Contacts'),'reference'=>'customers/statistics/contacts'),
					'customers'=>array('label'=>_('Customers'),'title'=>_('Customers'),'reference'=>'customers/statistics/customers'),
					'orders'=>array('label'=>_('Orders'),'title'=>_('Orders'),'reference'=>'customers/statistics/orders'),
					'data_integrity'=>array('label'=>_('Data Integrity'),'title'=>_('Data Integrity'),'reference'=>'customers/statistics/data_integrity'),
					'geo'=>array('label'=>_('Geographic Distribution'),'title'=>_('Geographic Distribution'),'reference'=>'customers/statistics/geo'),
					'correlations'=>array('label'=>_('Correlations'),'title'=>_('Correlations'),'reference'=>'customers/statistics/correlations'),

				)

			),
			'pending_orders'=>array('type'=>'navigation','label'=>_('Pending orders'),'title'=>_('Pending orders'),'icon'=>'clock-o','reference'=>'customers/pending_orders/%d'),



			'customer'=>array(
				'type'=>'object',
				'label'=>_('Customer'),
				'title'=>_('Customer'),
				'icon'=>'user',
				'reference'=>'customer/%d',
				'tabs'=>array(
					'details'=>array('label'=>_('Details'),'title'=>_('Details'),'reference'=>'customer/%d/details'),
					'history'=>array('label'=>_('History, Notes'),'title'=>_('History, Notes'),'reference'=>'customer/%d/notes'),
					'orders'=>array('label'=>_('Orders'),'title'=>_('Orders'),'reference'=>'customer/%d/orders'),

				)
			)




		)
	),
	'customers_server'=>array(

		'parent'=>'none',
		'parent_type'=>'none',
		'section'=>'customers',
		'sections'=>array(
			'customers'=>array('type'=>'navigation','label'=>_('Customers (All stores)'),'title'=>_('Customers (All stores)'),'icon'=>'','reference'=>'customers/all'),
			'pending_orders'=>array('type'=>'navigation','label'=>_('Pending orders (All stores)'),'title'=>_('Pending orders (All stores)'),'icon'=>'','reference'=>'pending_orders/all'),

		)

	),
	'orders'=>array(
		'section'=>'orders',
		'parent'=>'store',
		'parent_type'=>'key',
		'sections'=>array(
			'orders'=>array('type'=>'navigation','label'=>_('Orders'),'title'=>_('Orders'),'icon'=>'shopping-cart','reference'=>'orders/%d'),
			'invoices'=>array('type'=>'navigation','label'=>_('Invoices'),'title'=>_('Invoices'),'icon'=>'file-o','reference'=>'invoices/%d'),
			'dn'=>array('type'=>'navigation','label'=>_('Delivery Notes'),'title'=>_('Delivery Notes'),'icon'=>'truck','reference'=>'dn/%d'),
			'payments'=>array('type'=>'navigation','label'=>_('Payments'),'title'=>_('Payments'),'icon'=>'credit-card','reference'=>'payments/%d'),

			//'categories'=>array('label'=>_('Categories'),'title'=>_('Categories'),'icon'=>'sitemap','reference'=>'orders/categories/%d'),
		)
	),

	'websites'=>array(
		'section'=>'dashboard',
		'parent'=>'website',
		'parent_type'=>'key',
		'sections'=>array(
			'websites'=>array('type'=>'navigation','label'=>_('Websites'),'title'=>_('Websites'),'icon'=>'globe','reference'=>'websites',

				'tabs'=>array(


					'dashboard'=>array('label'=>_('Website dashboard'),'title'=>_('Website dashboard'),'icon'=>'dashboard','reference'=>'website/%d'),
					'pages'=>array('label'=>_('Pages'),'title'=>_('Pages'),'icon'=>'files-o','reference'=>'pages/%d'),
					'pageviews'=>array('label'=>_('Pageviews'),'title'=>_('Pageviews'),'icon'=>'eye','reference'=>'pageviews/%d'),
					'users'=>array('label'=>_('Users'),'title'=>_('Users'),'icon'=>'male','reference'=>'website/users/%d'),
				)
			),
			'website'=>array('type'=>'object','label'=>_('Website'),'title'=>_('Website'),'icon'=>'globe','reference'=>'website/%d',

				'tabs'=>array(


					'dashboard'=>array('label'=>_('Website dashboard'),'title'=>_('Website dashboard'),'icon'=>'dashboard','reference'=>'website/%d'),
					'pages'=>array('label'=>_('Pages'),'title'=>_('Pages'),'icon'=>'files-o','reference'=>'pages/%d'),
					'pageviews'=>array('label'=>_('Pageviews'),'title'=>_('Pageviews'),'icon'=>'eye','reference'=>'pageviews/%d'),
					'users'=>array('label'=>_('Users'),'title'=>_('Users'),'icon'=>'male','reference'=>'website/users/%d'),
				)
			),




			//'categories'=>array('label'=>_('Categories'),'title'=>_('Categories'),'icon'=>'sitemap','reference'=>'orders/categories/%d'),
		)
	),




	'utils'=>array(
		'sections'=>array(
			'forbidden'=>array('type'=>'object','label'=>_('Forbidden'),'title'=>_('Forbidden'),'icon'=>'shopping-cart','id'=>'forbidden'),
			'not_found'=>array('type'=>'object','label'=>_('Not found'),'title'=>_('Not found'),'icon'=>'file-o','id'=>'not_found'),
		)
	)

);



function get_sections($module,$parent_key=false) {
	global $modules;

	$sections=array();
	foreach ($modules[$module]['sections'] as $key=>$value) {
		if ($value['type']=='navigation') {
			if ($parent_key) {
				$value['reference']=sprintf($value['reference'],$parent_key);
			}

			$sections[]=$value;
		}
	}



	return $sections;

}

?>
