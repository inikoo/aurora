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
			'dashboard'=>array('type'=>'widgets', 'label'=>_('Home'), 'title'=>_('Home'), 'icon'=>'home'),
		)

	),
	'customers'=>array(
		'section'=>'customers',
		'parent'=>'store',
		'parent_type'=>'key',
		'sections'=>array(
			'dashboard'=>array('type'=>'navigation', 'label'=>_('Dashboard'), 'title'=>_("Customer's dashboard"), 'icon'=>'dashboard', 'reference'=>'customers/%d/dashboard'),

			'customers'=>array(
				'tab'=>'customers',
				'type'=>'navigation', 'label'=>_('Customers'), 'title'=>_('Customers'), 'icon'=>'users', 'reference'=>'customers/%d',



			),
			'lists'=>array('type'=>'navigation', 'label'=>_('Lists'), 'title'=>_('Lists'), 'icon'=>'list', 'reference'=>'customers/%d/lists'),
			'categories'=>array('type'=>'navigation', 'label'=>_('Categories'), 'title'=>_('Categories'), 'icon'=>'sitemap', 'reference'=>'customers/%d/categories'),
			'statistics'=>array('type'=>'navigation', 'label'=>_('Statistics'), 'title'=>_('Statistics'), 'icon'=>'line-chart', 'reference'=>'customers/%dstatistics',
				'tabs'=>array(
					'contacts'=>array('label'=>_('Contacts'), 'title'=>_('Contacts'), 'reference'=>'customers/statistics/contacts'),
					'customers'=>array('label'=>_('Customers'), 'title'=>_('Customers'), 'reference'=>'customers/statistics/customers'),
					'orders'=>array('label'=>_('Orders'), 'title'=>_('Orders'), 'reference'=>'customers/statistics/orders'),
					'data_integrity'=>array('label'=>_('Data Integrity'), 'title'=>_('Data Integrity'), 'reference'=>'customers/statistics/data_integrity'),
					'geo'=>array('label'=>_('Geographic Distribution'), 'title'=>_('Geographic Distribution'), 'reference'=>'customers/statistics/geo'),
					'correlations'=>array('label'=>_('Correlations'), 'title'=>_('Correlations'), 'reference'=>'customers/statistics/correlations'),

				)

			),

			'list'=>array(
				'type'=>'object'



			),
			'category'=>array(
				'type'=>'object',

				'tabs'=>array(
					'category.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'reference'=>'category/%d/details'),
					'category.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'category'=>'customer/%d/history'),
					'category.subjects'=>array('label'=>_('Customers'), 'title'=>_('Customers'), 'reference'=>'category/%d/subjects'),
					'category.subcategories'=>array('label'=>_('Subcategories'), 'title'=>_('Subcategories'), 'reference'=>'customer/%d/subcategories'),

				)

			),

			'customer'=>array(
				'type'=>'object',
				'label'=>_('Customer'),
				'title'=>_('Customer'),
				'icon'=>'user',
				'reference'=>'customer/%d',
				'subtabs_parent'=>array(
					'customer.products.overview'=>'customer.products',
					'customer.products.products'=>'customer.products',
					'customer.products.favorites'=>'customer.products',
					'customer.products.families'=>'customer.products',

				),
				'tabs'=>array(
					'customer.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'reference'=>'customer/%d/details'),
					'customer.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'reference'=>'customer/%d/notes'),
					'customer.orders'=>array('label'=>_('Orders'), 'title'=>_('Orders'), 'reference'=>'customer/%d/orders'),
					'customer.products'=>array('label'=>_('Products'), 'title'=>_('Products'), 'reference'=>'customer/%d/products',
						'subtabs'=>array(
							'customer.products.overview'=>array('label'=>_('Overview'), 'title'=>_('Overview'), 'reference'=>'customer/%d/products/overview'),
							'customer.products.products'=>array('label'=>_('Products'), 'title'=>_('Products'), 'reference'=>'customer/%d/products/products'),
							'customer.products.favorites'=>array('label'=>_('Favourites'), 'title'=>_('Favousrites'), 'reference'=>'customer/%d/products/favorites'),
							'customer.products.families'=>array('label'=>_('Families'), 'title'=>_('Families'), 'reference'=>'customer/%d/products/families'),

						)

					),

				)
			)




		)
	),

	'customers_server'=>array(

		'parent'=>'none',
		'parent_type'=>'none',
		'section'=>'customers',
		'sections'=>array(
			'customers'=>array('type'=>'navigation', 'label'=>_('Customers (All stores)'), 'title'=>_('Customers (All stores)'), 'icon'=>'', 'reference'=>'customers/all'),

		)

	),
	'orders'=>array(
		'section'=>'orders',
		'parent'=>'store',
		'parent_type'=>'key',
		'sections'=>array(
			'orders'=>array('type'=>'navigation', 'label'=>_('Orders'), 'title'=>_('Orders'), 'icon'=>'shopping-cart', 'reference'=>'orders/%d'),
			'invoices'=>array('type'=>'navigation', 'label'=>_('Invoices'), 'title'=>_('Invoices'), 'icon'=>'file-o', 'reference'=>'invoices/%d'),
			'delivery_notes'=>array('type'=>'navigation', 'label'=>_('Delivery Notes'), 'title'=>_('Delivery Notes'), 'icon'=>'truck', 'reference'=>'delivery_notes/%d'),
			'payments'=>array('type'=>'navigation', 'label'=>_('Payments'), 'title'=>_('Payments'), 'icon'=>'credit-card', 'reference'=>'payments/%d'),

			'order'=>array('type'=>'object',
			    'tabs'=>array(


					'order.items'=>array('label'=>_('Items'), 'title'=>_('Items'), 'icon'=>'bars', 'reference'=>'website/%d/dashboard'),
					'order.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database', 'reference'=>'website/%d/details'),
					'order.history'=>array('label'=>_('History'), 'title'=>_('History'), 'icon'=>'history', 'reference'=>'website/%d/pages'),
				)
			
			),


		)
	),
	'orders_server'=>array(

		'parent'=>'none',
		'parent_type'=>'none',
		'section'=>'orders',
		'sections'=>array(
			'orders'=>array('type'=>'navigation', 'label'=>_('Orders'), 'title'=>_('Orders'), 'icon'=>'shopping-cart', 'reference'=>'orders/all'),

		)

	),
	'websites'=>array(
		'section'=>'dashboard',
		'parent'=>'website',
		'parent_type'=>'key',
		'sections'=>array(
			'websites'=>array('type'=>'navigation', 'label'=>_('Websites'), 'title'=>_('Websites'), 'icon'=>'globe', 'reference'=>'websites',


			),
			'website'=>array('type'=>'object', 'label'=>_('Website'), 'title'=>_('Website'), 'icon'=>'globe', 'reference'=>'website/%d',

				'tabs'=>array(


					'website.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Website dashboard'), 'icon'=>'dashboard', 'reference'=>'website/%d/dashboard'),
					'website.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database', 'reference'=>'website/%d/details'),

					'website.pages'=>array('label'=>_('Pages'), 'title'=>_('Pages'), 'icon'=>'files-o', 'reference'=>'website/%d/pages'),
					'website.pageviews'=>array('label'=>_('Pageviews'), 'title'=>_('Pageviews'), 'icon'=>'eye', 'reference'=>'website/%d/pageviews'),
					'website.users'=>array('label'=>_('Users'), 'title'=>_('Users'), 'icon'=>'male', 'reference'=>'website/%d/users'),
				)
			),
'page'=>array('type'=>'object',
				'tabs'=>array(


					'page.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Page dashboard'), 'icon'=>'dashboard', 'reference'=>'website/%d/dashboard'),
					'page.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database', 'reference'=>'website/%d/details'),
					'website.pageviews'=>array('label'=>_('Pageviews'), 'title'=>_('Pageviews'), 'icon'=>'eye', 'reference'=>'website/%d/pageviews'),
					'website.users'=>array('label'=>_('Users'), 'title'=>_('Users'), 'icon'=>'male', 'reference'=>'website/%d/users'),
				)
			),



			//'categories'=>array('label'=>_('Categories'),'title'=>_('Categories'),'icon'=>'sitemap','reference'=>'orders/categories/%d'),
		)
	),
	'products'=>array(
		'section'=>'products',
		'sections'=>array(
			'products'=>array('type'=>'navigation', 'label'=>_('Products'), 'title'=>_("Products database"), 'icon'=>'cube', 'reference'=>'store'),
			'categories'=>array('type'=>'navigation', 'label'=>_('Categories'), 'title'=>_("Products categories"), 'icon'=>'sitemap', 'reference'=>'store/categories'),
			'store'=>array(
				'type'=>'object',
				'label'=>_('Store'),
				'title'=>_('Store'),
				'icon'=>'',
				'reference'=>'store/%d',
				'tab'=>'store_dashboard',
				'tabs'=>array(
					'store.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard'), 'reference'=>'store/%d/store'),
					'store.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'reference'=>'store/%d/details'),
					'store.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'reference'=>'store/%d/notes'),
					'store.departments'=>array('label'=>_('Departments'), 'title'=>_('Departments'), 'reference'=>'store/%d/departments'),
					'store.families'=>array('label'=>_('Families'), 'title'=>_('Families'), 'reference'=>'store/%d/families'),
					'store.products'=>array('label'=>_('Products'), 'title'=>_('Products'), 'reference'=>'store/%d/products'),

				)
			),
			'department'=>array(
				'type'=>'object',
				
				'tabs'=>array(
					'department.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard'), 'reference'=>'department/%d/dashboard'),
					'department.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'reference'=>'department/%d/details'),
					'department.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'reference'=>'department/%d/notes'),
					'department.families'=>array('label'=>_('Families'), 'title'=>_('Families'), 'reference'=>'dashboard/%d/families'),
					'department.products'=>array('label'=>_('Products'), 'title'=>_('Products'), 'reference'=>'dashboard/%d/products'),

				)
			),
			'family'=>array(
				'type'=>'object',
				
				'tabs'=>array(
					'family.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard'), 'reference'=>'family/%d/dashboard'),
					'family.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'reference'=>'department/%d/details'),
					'family.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'reference'=>'department/%d/notes'),
					'family.products'=>array('label'=>_('Products'), 'title'=>_('Products'), 'reference'=>'dashboard/%d/products'),

				)
			),
			'product'=>array(
				'type'=>'object',
				
				'tabs'=>array(
					'product.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard'), 'reference'=>'department/%d/dashboard'),
					'product.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'reference'=>'department/%d/details'),
					'product.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'reference'=>'department/%d/notes'),

				)
			)
		)
	),
	'products_server'=>array(

		'parent'=>'none',
		'parent_type'=>'none',
		'sections'=>array()

	),
	'marketing'=>array(
		'section'=>'marketing',
		'parent'=>'store',
		'parent_type'=>'key',
		'sections'=>array(

			'deals'=>array(
				'type'=>'navigation', 'label'=>_('Deals'), 'title'=>_('Deals'), 'icon'=>'tag', 'reference'=>'marketing/%d/deals',
				'tabs'=>array(
					'campaigns'=>array('label'=>_('Campaigns'), 'title'=>_('Campaigns'), 'reference'=>'marketing/%d/campaigns'),
					'offers'=>array('label'=>_('Offers'), 'title'=>_('Offers'), 'reference'=>'marketing/%d/offers'),

				)


			),
			'enewsletters'=>array('type'=>'navigation', 'label'=>_('eNewsletters'), 'title'=>_('eNewsletters'), 'icon'=>'newspaper-o', 'reference'=>'marketing/%d/enewsletters'),
			'mailshots'=>array('type'=>'navigation', 'label'=>_('Mailshots'), 'title'=>_('Mailshots'), 'icon'=>'at', 'reference'=>'marketing/%d/mailshots'),
			'marketing_post'=>array('type'=>'navigation', 'label'=>_('Marketing Post'), 'title'=>_('Marketing Post'), 'icon'=>'envelope-o', 'reference'=>'marketing/%d/marketing_post'),
			'marketing_media'=>array('type'=>'navigation', 'label'=>_('Marketing Media'), 'title'=>_('Marketing Media'), 'icon'=>'google', 'reference'=>'marketing/%d/marketing_media'),
			'ereminders'=>array('type'=>'navigation', 'label'=>_('eReminders'), 'title'=>_('eReminders'), 'icon'=>'bell-o', 'reference'=>'marketing/%d/ereminders'),





		)
	),
	'marketing_server'=>array(

		'parent'=>'none',
		'parent_type'=>'none',
		'section'=>'marketing',
		'sections'=>array(
			'marketing'=>array('type'=>'navigation', 'label'=>_('Marketing (All stores)'), 'title'=>_('Marketing (All stores)'), 'icon'=>'', 'reference'=>'marketing/all'),

		)

	),



	'suppliers'=>array(
		'section'=>'suppliers',
		'parent'=>'store',
		'parent_type'=>'key',
		'sections'=>array(
			'dashboard'=>array('type'=>'navigation', 'label'=>_('Dashboard'), 'title'=>_("Supplier's dashboard"), 'icon'=>'dashboard', 'reference'=>'suppliers/%d/dashboard'),

			'suppliers'=>array(
				'tab'=>'suppliers',
				'type'=>'navigation', 'label'=>_('Suppliers'), 'title'=>_('Suppliers'), 'icon'=>'industry', 'reference'=>'suppliers',



			),
			'lists'=>array('type'=>'navigation', 'label'=>_('Lists'), 'title'=>_('Lists'), 'icon'=>'list', 'reference'=>'suppliers/%d/lists'),
			'categories'=>array('type'=>'navigation', 'label'=>_('Categories'), 'title'=>_('Categories'), 'icon'=>'sitemap', 'reference'=>'suppliers/%d/categories'),

			'list'=>array(
				'type'=>'object'



			),
			'category'=>array(
				'type'=>'object',

				'tabs'=>array(
					'category.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'reference'=>'category/%d/details'),
					'category.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'category'=>'supplier/%d/history'),
					'category.subjects'=>array('label'=>_('Customers'), 'title'=>_('Customers'), 'reference'=>'category/%d/subjects'),
					'category.subcategories'=>array('label'=>_('Subcategories'), 'title'=>_('Subcategories'), 'reference'=>'supplier/%d/subcategories'),

				)

			),

			'supplier'=>array(
				'type'=>'object',
				'label'=>_('Supplier'),
				'title'=>_('Supplier'),
				'icon'=>'industry',
				'reference'=>'supplier/%d',
				'tabs'=>array(
					'supplier.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'reference'=>'supplier/%d/details'),
					'supplier.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'reference'=>'supplier/%d/notes'),
					'supplier.orders'=>array('label'=>_('Orders'), 'title'=>_('Orders'), 'reference'=>'supplier/%d/orders'),

				)
			)




		)
	),
	'warehouses_server'=>array(
		'sections'=>array(

			'warehouses'=>array(
				'tab'=>'warehouses',
				'type'=>'navigation', 'label'=>_('Warehouses'), 'title'=>_('Warehouses'), 'icon'=>'map-maker', 'reference'=>'warehouses',
			)

		)
	),
	'warehouses'=>array(
		'sections'=>array(
			'warehouse'=>array(
				'tab'=>'areas',
				'type'=>'navigation', 'label'=>_('Warehouse'), 'title'=>_('Warehouse'), 'icon'=>'th-large', 'reference'=>'warehouse/%d',
			),
			'inventory'=>array(
				'tab'=>'parts',
				'type'=>'navigation', 'label'=>_('Inventory'), 'title'=>_('Inventory'), 'icon'=>'th', 'reference'=>'inventory/%d',
				'tabs'=>array(
					'inventory.parts'=>array('label'=>_('Parts'), 'title'=>_('Parts'), 'reference'=>'parts/%d'),
					'inventory.families'=>array('label'=>_("Part's Families"), 'title'=>_("Part's families"), 'reference'=>'inventory/%d/families'),

				)
			),
			'locations'=>array(
				'tab'=>'locations',
				'type'=>'navigation', 'label'=>_('Locations'), 'title'=>_('Locations'), 'icon'=>'map-marker', 'reference'=>'locations/%d',
				'tabs'=>array(
					'locations'=>array('label'=>_('Locations'), 'title'=>_('Locations'), 'reference'=>'locations/%d'),
					'locations.replenishments'=>array('label'=>_("Replenishments"), 'title'=>_("Replenishments"), 'reference'=>'locations/%d/replenishments'),
					'locations.parts'=>array('label'=>_('Part-Locations'), 'title'=>_('Part-Locations'), 'reference'=>'locations/%d/parts'),

				)
			),
			'stock_transactions'=>array(
				'type'=>'navigation', 'label'=>_('Stock Transactions'), 'title'=>_('Stock Transactions'), 'icon'=>'exchange', 'reference'=>'locations/%d',
				'tabs'=>array(
					'locations'=>array('label'=>_('Locations'), 'title'=>_('Locations'), 'reference'=>'locations/%d'),
					'locations.replenishments'=>array('label'=>_("Replenishments"), 'title'=>_("Replenishments"), 'reference'=>'locations/%d/replenishments'),
					'locations.parts'=>array('label'=>_('Part-Locations'), 'title'=>_('Part-Locations'), 'reference'=>'locations/%d/parts'),

				)
			),
			'stock_histotry'=>array(
				'type'=>'navigation', 'label'=>_('Stock History'), 'title'=>_('Stock Transactions'), 'icon'=>'history', 'reference'=>'locations/%d',
				'tabs'=>array(
					'locations'=>array('label'=>_('Locations'), 'title'=>_('Locations'), 'reference'=>'locations/%d'),
					'locations.replenishments'=>array('label'=>_("Replenishments"), 'title'=>_("Replenishments"), 'reference'=>'locations/%d/replenishments'),
					'locations.parts'=>array('label'=>_('Part-Locations'), 'title'=>_('Part-Locations'), 'reference'=>'locations/%d/parts'),

				)
			)



		)
	),


	'reports'=>array(

		'sections'=>array(
			'performance'=>array('type'=>'navigation', 'label'=>_('Activity/Performance'), 'title'=>_("Activity/Performance"), 'icon'=>'thumbs-o-up', 'reference'=>'users',
				'tabs'=>array(
					'report.pp'=>array('label'=>_('Pickers & Packers'), 'title'=>_('Pickers & Packers Report'), 'reference'=>'users'),
					'report.outofstock'=>array('label'=>_("Out of Stock"), 'title'=>_("Out of Stock"), 'reference'=>'users/'),
					'report.top_customers'=>array('label'=>_('Top Customers'), 'title'=>_('Top Customers'), 'reference'=>'locations/%d/parts'),

				)

			),
			'sales'=>array('type'=>'navigation', 'label'=>_('Sales'), 'title'=>_("Sales"), 'icon'=>'money', 'reference'=>'users',
				'tabs'=>array(
					'report.sales'=>array('label'=>_('Pickers & Packers'), 'title'=>_('Pickers & Packers Report'), 'reference'=>'users'),
					'report.geosales'=>array('label'=>_("Geographic Sales"), 'title'=>_("Geographic Sales Report"), 'reference'=>'users/'),
					'report.components'=>array('label'=>_('Sales Components'), 'title'=>_('Top Customers'), 'reference'=>'locations/%d/parts'),

				)

			),
			'tax'=>array('type'=>'navigation', 'label'=>_('Tax Reports'), 'title'=>_("Tax Reports"), 'icon'=>'legal', 'reference'=>'users',
				'tabs'=>array(
					'report.notax'=>array('label'=>_('No Tax'), 'title'=>_('No Tax Report'), 'reference'=>'users'),
					'report.intrastat'=>array('label'=>_("Intrastat"), 'title'=>_("Intrastat"), 'reference'=>'users/'),

				)

			)



		)
	),



	'hr'=>array(

		'sections'=>array(
			'employees'=>array('type'=>'navigation', 'label'=>_('Employees'), 'title'=>_("Employees"), 'icon'=>'hand-rock-o', 'reference'=>'hr',
				'tabs'=>array(
					'employees'=>array('label'=>_('Employees'), 'title'=>_('Employees'), 'reference'=>'hr'),

				)

			),

			'contractors'=>array(
				'type'=>'navigation', 'label'=>_('Contractors'), 'title'=>_('Contractors'), 'icon'=>'hand-spock-o', 'reference'=>'hr/contractors',
			),
			'organization'=>array(
				'type'=>'navigation', 'label'=>_('Organization'), 'title'=>_('Organization'), 'icon'=>'sitemap', 'reference'=>'hr/organization',
			),
			'employee'=>array('type'=>'object',
				'tabs'=>array(
					'employee.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'reference'=>'employee/%d/details'),
					'employee.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'reference'=>'employee/%d/notes'),
					'employee.timesheet'=>array('label'=>_('Timesheet'), 'title'=>_('Timesheet'), 'reference'=>'employee/%d/timesheet'),

				)

			),

		)
	),
	'users'=>array(

		'sections'=>array(
			'staff'=>array('type'=>'navigation', 'label'=>_('Staff'), 'title'=>_("Staff users"), 'icon'=>'hand-rock-o', 'reference'=>'users',
				'tabs'=>array(
					'users.staff.users'=>array('label'=>_('Users'), 'title'=>_('Users'), 'reference'=>'users'),
					'users.staff.groups'=>array('label'=>_("Groups"), 'title'=>_("Groups"), 'reference'=>'users/'),
					'users.staff.login_history'=>array('label'=>_('Login History'), 'title'=>_('Login History'), 'reference'=>'locations/%d/parts'),

				)

			),

			'suppliers'=>array(
				'type'=>'navigation', 'label'=>_('Suppliers'), 'title'=>_('Suppliers users'), 'icon'=>'industry', 'reference'=>'users/suppliers',
			),
			'warehouse'=>array(
				'type'=>'navigation', 'label'=>_('Warehouse'), 'title'=>_('Warehouse users'), 'icon'=>'th-large', 'reference'=>'users/warehouse',
			),
			'root'=>array(
				'type'=>'navigation', 'label'=>'Root', 'title'=>_('Root user'), 'icon'=>'dot-circle-o', 'reference'=>'suppliers',
			),
			'staff.user'=>array('type'=>'object',
				'tabs'=>array(
					'staff.user.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'reference'=>'store/%d/details'),
					'staff.user.login_history'=>array('label'=>_('Login History'), 'title'=>_('Login History'), 'reference'=>'store/%d/notes'),

				)
			)


		)
	),


	'utils'=>array(
		'sections'=>array(
			'forbidden'=>array('type'=>'object', 'label'=>_('Forbidden'), 'title'=>_('Forbidden'), 'icon'=>'shopping-cart', 'id'=>'forbidden'),
			'not_found'=>array('type'=>'object', 'label'=>_('Not found'), 'title'=>_('Not found'), 'icon'=>'file-o', 'id'=>'not_found'),
		)
	)

);



function get_sections($module, $parent_key=false) {
	global $modules;

	$sections=array();
	foreach ($modules[$module]['sections'] as $key=>$value) {
		if ($value['type']=='navigation') {
			if ($parent_key) {
				$value['reference']=sprintf($value['reference'], $parent_key);
			}

			$sections[$key]=$value;
		}
	}



	return $sections;

}


?>
