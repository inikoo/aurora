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
			'dashboard'=>array('type'=>'widgets','label'=>_('Home'),'title'=>_('Home'),'icon'=>'home'),
		)

	),


	'customers'=>array(
		'section'=>'customers',
		'parent'=>'store',
		'parent_type'=>'key',
		'sections'=>array(
			'dashboard'=>array('type'=>'navigation','label'=>_('Dashboard'),'title'=>_("Customer's dashboard"),'icon'=>'dashboard','reference'=>'customers/%d/dashboard'),

			'customers'=>array(
				'tab'=>'customers',
				'type'=>'navigation','label'=>_('Customers'),'title'=>_('Customers'),'icon'=>'users','reference'=>'customers/%d',



			),
			'lists'=>array('type'=>'navigation','label'=>_('Lists'),'title'=>_('Lists'),'icon'=>'list','reference'=>'customers/%d/lists'),
			'categories'=>array('type'=>'navigation','label'=>_('Categories'),'title'=>_('Categories'),'icon'=>'sitemap','reference'=>'customers/%d/categories'),
			'statistics'=>array('type'=>'navigation','label'=>_('Statistics'),'title'=>_('Statistics'),'icon'=>'line-chart','reference'=>'customers/%dstatistics',
				'tabs'=>array(
					'contacts'=>array('label'=>_('Contacts'),'title'=>_('Contacts'),'reference'=>'customers/statistics/contacts'),
					'customers'=>array('label'=>_('Customers'),'title'=>_('Customers'),'reference'=>'customers/statistics/customers'),
					'orders'=>array('label'=>_('Orders'),'title'=>_('Orders'),'reference'=>'customers/statistics/orders'),
					'data_integrity'=>array('label'=>_('Data Integrity'),'title'=>_('Data Integrity'),'reference'=>'customers/statistics/data_integrity'),
					'geo'=>array('label'=>_('Geographic Distribution'),'title'=>_('Geographic Distribution'),'reference'=>'customers/statistics/geo'),
					'correlations'=>array('label'=>_('Correlations'),'title'=>_('Correlations'),'reference'=>'customers/statistics/correlations'),

				)

			),

            'list'=>array(
				'type'=>'object'



			),
			'category'=>array(
				'type'=>'object',

                'tabs'=>array(
					'category.details'=>array('label'=>_('Details'),'title'=>_('Details'),'reference'=>'category/%d/details'),
					'category.history'=>array('label'=>_('History, Notes'),'title'=>_('History, Notes'),'category'=>'customer/%d/history'),
					'category.subjects'=>array('label'=>_('Customers'),'title'=>_('Customers'),'reference'=>'category/%d/subjects'),
					'category.subcategories'=>array('label'=>_('Subcategories'),'title'=>_('Subcategories'),'reference'=>'customer/%d/subcategories'),

				)

			),

			'customer'=>array(
				'type'=>'object',
				'label'=>_('Customer'),
				'title'=>_('Customer'),
				'icon'=>'user',
				'reference'=>'customer/%d',
				'tabs'=>array(
					'customer.details'=>array('label'=>_('Details'),'title'=>_('Details'),'reference'=>'customer/%d/details'),
					'customer.history'=>array('label'=>_('History, Notes'),'title'=>_('History, Notes'),'reference'=>'customer/%d/notes'),
					'customer.orders'=>array('label'=>_('Orders'),'title'=>_('Orders'),'reference'=>'customer/%d/orders'),

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

		)

	),
	'orders'=>array(
		'section'=>'orders',
		'parent'=>'store',
		'parent_type'=>'key',
		'sections'=>array(
			'orders'=>array('type'=>'navigation','label'=>_('Orders'),'title'=>_('Orders'),'icon'=>'shopping-cart','reference'=>'orders/%d'),
			'invoices'=>array('type'=>'navigation','label'=>_('Invoices'),'title'=>_('Invoices'),'icon'=>'file-o','reference'=>'invoices/%d'),
			'delivery_notes'=>array('type'=>'navigation','label'=>_('Delivery Notes'),'title'=>_('Delivery Notes'),'icon'=>'truck','reference'=>'delivery_notes/%d'),
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


					'website.dashboard'=>array('label'=>_('Website dashboard'),'title'=>_('Website dashboard'),'icon'=>'dashboard','reference'=>'websites/dashboard'),
					'website.websites'=>array('label'=>_('Websites'),'title'=>_('Websites'),'icon'=>'globe','reference'=>'websites/websites'),
				)
			),
			'website'=>array('type'=>'object','label'=>_('Website'),'title'=>_('Website'),'icon'=>'globe','reference'=>'website/%d',

				'tabs'=>array(


					'dashboard'=>array('label'=>_('Website dashboard'),'title'=>_('Website dashboard'),'icon'=>'dashboard','reference'=>'website/%d/dashboard'),
					'website.details'=>array('label'=>_('Details'),'title'=>_('Details'),'icon'=>'database','reference'=>'website/%d/details'),

					'website.pages'=>array('label'=>_('Pages'),'title'=>_('Pages'),'icon'=>'files-o','reference'=>'website/%d/pages'),
					'website.pageviews'=>array('label'=>_('Pageviews'),'title'=>_('Pageviews'),'icon'=>'eye','reference'=>'website/%d/pageviews'),
					'website.tusers'=>array('label'=>_('Users'),'title'=>_('Users'),'icon'=>'male','reference'=>'website/%d/users'),
				)
			),




			//'categories'=>array('label'=>_('Categories'),'title'=>_('Categories'),'icon'=>'sitemap','reference'=>'orders/categories/%d'),
		)
	),

	'products'=>array(
		'section'=>'products',
		'sections'=>array(
			'products'=>array('type'=>'navigation','label'=>_('Products'),'title'=>_("Products database"),'icon'=>'cube','reference'=>'store'),
			'categories'=>array('type'=>'navigation','label'=>_('Categories'),'title'=>_("Products categories"),'icon'=>'sitemap','reference'=>'store/categories'),
			'store'=>array(
				'type'=>'object',
				'label'=>_('Store'),
				'title'=>_('Store'),
				'icon'=>'',
				'reference'=>'store/%d',
				'tab'=>'store_dashboard',
				'tabs'=>array(
					'store.dashboard'=>array('label'=>_('Dashboard'),'title'=>_('Dashboard'),'reference'=>'store/%d/store'),
					'store.details'=>array('label'=>_('Details'),'title'=>_('Details'),'reference'=>'store/%d/details'),
					'store.history'=>array('label'=>_('History, Notes'),'title'=>_('History, Notes'),'reference'=>'store/%d/notes'),
					'store.departments'=>array('label'=>_('Departments'),'title'=>_('Departments'),'reference'=>'store/%d/departments'),
					'store.families'=>array('label'=>_('Families'),'title'=>_('Families'),'reference'=>'store/%d/families'),
					'store.products'=>array('label'=>_('Products'),'title'=>_('Products'),'reference'=>'store/%d/products'),

				)
			)
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

			$sections[$key]=$value;
		}
	}



	return $sections;

}

?>
