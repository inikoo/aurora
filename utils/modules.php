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
			'dashboard'=>array('type'=>'widgets', 'label'=>_('Home'), 'title'=>_('Home'), 'icon'=>'home',
				'tabs'=>array(
					'dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard')),

				)

			),
		)

	),
	'customers'=>array(
		'section'=>'customers',
		'parent'=>'store',
		'parent_type'=>'key',
		'sections'=>array(
			'dashboard'=>array('type'=>'navigation', 'label'=>_('Dashboard'), 'title'=>_("Customer's dashboard"), 'icon'=>'dashboard', 'reference'=>'customers/%d/dashboard',
				'tabs'=>array(
					'customers.dashboard'=>array()
				)
			),

			'customers'=>array(
				'type'=>'navigation', 'label'=>_('Customers'), 'title'=>_('Customers'), 'icon'=>'users', 'reference'=>'customers/%d',
				'tabs'=>array(
					'customers'=>array()
				)


			),
			'lists'=>array('type'=>'navigation', 'label'=>_('Lists'), 'title'=>_('Lists'), 'icon'=>'list', 'reference'=>'customers/%d/lists',
				'tabs'=>array(
					'customers.lists'=>array()
				)
			),
			'categories'=>array('type'=>'navigation', 'label'=>_('Categories'), 'title'=>_('Categories'), 'icon'=>'sitemap', 'reference'=>'customers/%d/categories',
				'tabs'=>array(
					'customers.categories'=>array()
				)
				,

			),
			'statistics'=>array('type'=>'navigation', 'label'=>_('Statistics'), 'title'=>_('Statistics'), 'icon'=>'line-chart', 'reference'=>'customers/%dstatistics',
				'tabs'=>array(
					'contacts'=>array('label'=>_('Contacts'), 'title'=>_('Contacts')),
					'customers'=>array('label'=>_('Customers'), 'title'=>_('Customers')),
					'orders'=>array('label'=>_('Orders'), 'title'=>_('Orders')),
					'data_integrity'=>array('label'=>_('Data Integrity'), 'title'=>_('Data Integrity')),
					'geo'=>array('label'=>_('Geographic Distribution'), 'title'=>_('Geographic Distribution')),
					'correlations'=>array('label'=>_('Correlations'), 'title'=>_('Correlations')),

				)

			),

			'list'=>array(
				'type'=>'object',
				'tabs'=>array(
					'customers.list'=>array()
				)



			),
			'category'=>array(
				'type'=>'object',

				'tabs'=>array(
					'category.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details')),
					'category.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'category.subjects'=>array('label'=>_('Customers'), 'title'=>_('Customers')),
					'category.subcategories'=>array('label'=>_('Subcategories'), 'title'=>_('Subcategories')),

				)

			),

			'customer'=>array(
				'type'=>'object',
				'label'=>_('Customer'),
				'title'=>_('Customer'),
				'icon'=>'user',
				'reference'=>'customer/%d',
				'subtabs_parent'=>array(
					'customer.marketing.overview'=>'customer.marketing',
					'customer.marketing.families'=>'customer.marketing',
					'customer.marketing.products'=>'customer.marketing',
					'customer.marketing.favourites'=>'customer.marketing',
					'customer.marketing.search'=>'customer.marketing',

				),
				'tabs'=>array(
					'customer.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details')),
					'customer.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'customer.orders'=>array('label'=>_('Orders'), 'title'=>_('Orders')),
					'customer.marketing'=>array('label'=>_('Interests'), 'title'=>_("Customer's interests"),
						'subtabs'=>array(
							'customer.marketing.overview'=>array('label'=>_('Overview'), 'title'=>_('Overview')),
							'customer.marketing.products'=>array('label'=>_('Products ordered'), 'title'=>_('Products ordered')),
							'customer.marketing.families'=>array('label'=>_('Families ordered'), 'title'=>_('Families ordered')),
							'customer.marketing.favourites'=>array('label'=>_('Favourite products'), 'title'=>_('Favourites products')),
							'customer.marketing.search'=>array('label'=>_('Search queries'), 'title'=>_('Search queries')),

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
			'customers'=>array('type'=>'navigation', 'label'=>_('Customers (All stores)'), 'title'=>_('Customers (All stores)'), 'icon'=>'', 'reference'=>'customers/all',
				'tabs'=>array(
					'customers_server'=>array()
				)
			),

		)

	),
	'orders'=>array(
		'section'=>'orders',
		'parent'=>'store',
		'parent_type'=>'key',
		'sections'=>array(
			'orders'=>array('type'=>'navigation', 'label'=>_('Orders'), 'title'=>_('Orders'), 'icon'=>'shopping-cart', 'reference'=>'orders/%d',
				'tabs'=>array(
					'orders'=>array()
				)
			),
			'invoices'=>array('type'=>'navigation', 'label'=>_('Invoices'), 'title'=>_('Invoices'), 'icon'=>'usd', 'reference'=>'invoices/%d',
				'tabs'=>array(
					'invoices'=>array()
				)
			),
			'delivery_notes'=>array('type'=>'navigation', 'label'=>_('Delivery Notes'), 'title'=>_('Delivery Notes'), 'icon'=>'truck', 'reference'=>'delivery_notes/%d',
				'tabs'=>array(
					'delivery_notes'=>array()
				)
			),
			'payments'=>array('type'=>'navigation', 'label'=>_('Payments'), 'title'=>_('Payments'), 'icon'=>'credit-card', 'reference'=>'payments/%d',
				'tabs'=>array(
					'payments'=>array()
				)
			),

			'order'=>array('type'=>'object',
				'tabs'=>array(


					'order.items'=>array('label'=>_('Items'), 'title'=>_('Items'), 'icon'=>'bars'),
					'order.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database'),
					'order.history'=>array('label'=>_('History'), 'title'=>_('History'), 'icon'=>'history'),
					'order.delivery_notes'=>array('label'=>_('Delivery notes'), 'title'=>_('Delivery notes'), 'icon'=>'truck'),
					'order.invoices'=>array('label'=>_('Invoices'), 'title'=>_('Invoices'), 'icon'=>'usd'),

				)

			),
			'invoice'=>array('type'=>'object',
				'tabs'=>array(


					'invoice.items'=>array('label'=>_('Items'), 'title'=>_('Items'), 'icon'=>'bars'),
					'invoice.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database'),
					'invoice.history'=>array('label'=>_('History'), 'title'=>_('History'), 'icon'=>'history'),
					'invoice.orders'=>array('label'=>_('Orders'), 'title'=>_('Orders'), 'icon'=>'usd'),
					'invoice.delivery_notes'=>array('label'=>_('Delivery notes'), 'title'=>_('Delivery notes'), 'icon'=>'truck'),
				)

			),
			'delivery_note'=>array('type'=>'object',
				'tabs'=>array(


					'delivery_note.items'=>array('label'=>_('Items'), 'title'=>_('Items'), 'icon'=>'bars'),
					'delivery_note.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database'),
					'delivery_note.history'=>array('label'=>_('History'), 'title'=>_('History'), 'icon'=>'history'),
					'delivery_note.orders'=>array('label'=>_('Orders'), 'title'=>_('Orders'), 'icon'=>'usd'),
					'delivery_note.invoices'=>array('label'=>_('Invoices'), 'title'=>_('Invoices'), 'icon'=>'usd'),
				)

			),
			'pick_aid'=>array('type'=>'object',
				'tabs'=>array(


					'pick_aid.items'=>array('label'=>_('Items'), 'title'=>_('Items'), 'icon'=>'bars'),
				)

			), 'pack_aid'=>array('type'=>'object',
				'tabs'=>array(


					'pack_aid.items'=>array('label'=>_('Items'), 'title'=>_('Items'), 'icon'=>'bars'),
				)

			),

		)
	),
	'orders_server'=>array(

		'parent'=>'none',
		'parent_type'=>'none',
		'section'=>'orders',
		'sections'=>array(
			'orders'=>array('type'=>'navigation', 'label'=>_('Orders'), 'title'=>_('Orders'), 'icon'=>'shopping-cart', 'reference'=>'orders/all',
				'tabs'=>array(
					'orders_server'=>array()
				)

			),

		)

	),
	'websites'=>array(
		'section'=>'dashboard',
		'parent'=>'website',
		'parent_type'=>'key',
		'sections'=>array(
			'websites'=>array('type'=>'navigation', 'label'=>_('Websites'), 'title'=>_('Websites'), 'icon'=>'globe', 'reference'=>'websites',
				'tabs'=>array(
					'websites'=>array()
				)

			),
			'website'=>array('type'=>'object', 'label'=>_('Website'), 'title'=>_('Website'), 'icon'=>'globe', 'reference'=>'website/%d',

				'subtabs_parent'=>array(
					'website.favourites.families'=>'website.favourites',
					'website.favourites.products'=>'website.favourites',
					'website.favourites.customers'=>'website.favourites',
					'website.search.queries'=>'website.search',
					'website.search.history'=>'website.search',
					'website.reminders.requests'=>'website.reminders',
					'website.reminders.customers'=>'website.reminders',
					'website.reminders.families'=>'website.reminders',
					'website.reminders.products'=>'website.reminders',

				),

				'tabs'=>array(


					'website.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Website dashboard'), 'icon'=>'dashboard'),
					'website.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database'),

					'website.pages'=>array('label'=>_('Pages'), 'title'=>_('Pages'), 'icon'=>'files-o'),
					'website.pageviews'=>array('label'=>_('Pageviews'), 'title'=>_('Pageviews'), 'icon'=>'eye'),
					'website.users'=>array('label'=>_('Users'), 'title'=>_('Users'), 'icon'=>'male'),
					'website.search'=>array('label'=>_('Queries'), 'title'=>_('Search Queries'), 'icon'=>'search',
						'subtabs'=>array(
							'website.search.queries'=>array('label'=>_('Queries'), 'title'=>_('Search queries goruped by keywords')),
							'website.search.history'=>array('label'=>_('Search History'), 'title'=>_('List of all search queries')),

						)

					),
					'website.favourites'=>array('label'=>_('Favourites'), 'title'=>_('Favourites'), 'icon'=>'heart-o',
						'subtabs'=>array(
							'website.favourites.families'=>array('label'=>_('Families'), 'title'=>_('Families')),
							'website.favourites.products'=>array('label'=>_('Products'), 'title'=>_('Products')),
							'website.favourites.customers'=>array('label'=>_('Customers'), 'title'=>_('Customers')),

						)

					),
					'website.reminders'=>array('label'=>_('OOS Reminders'), 'title'=>_('Out of stock reminders'), 'icon'=>'hand-paper-o',
						'subtabs'=>array(
							'website.reminders.requests'=>array('label'=>_('Requests'), 'title'=>_('Out of stock notifications requests')),
							'website.reminders.customers'=>array('label'=>_('Customers'), 'title'=>_('Customers who ask for a out of stock notification')),
							'website.reminders.families'=>array('label'=>_('Families'), 'title'=>_('Out of stock notifications grouped by famiy')),
							'website.reminders.products'=>array('label'=>_('Products'), 'title'=>_('Out of stock notifications grouped by product')),

						)

					),

				)
			),
			'page'=>array('type'=>'object',
				'tabs'=>array(


					'page.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Page dashboard'), 'icon'=>'dashboard'),
					'page.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database'),
					'page.pageviews'=>array('label'=>_('Pageviews'), 'title'=>_('Pageviews'), 'icon'=>'eye'),
					'page.users'=>array('label'=>_('Users'), 'title'=>_('Users'), 'icon'=>'male'),
				)
			),
			'website.user'=>array('type'=>'object',
				'tabs'=>array(
					'website.user.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details')),
					'website.user.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'website.user.login_history'=>array('label'=>_('Sessions'), 'title'=>_('Login history') , 'icon'=>'login'),
					'website.user.pageviews'=>array('label'=>_('Pageviews'), 'title'=>_('Pageviews') , 'icon'=>'eye'),

				)
			)




			//'categories'=>array('label'=>_('Categories'),'title'=>_('Categories'),'icon'=>'sitemap','reference'=>'orders/categories/%d'),
		)
	),
	'products'=>array(
		'section'=>'products',
		'sections'=>array(
			'products'=>array('type'=>'navigation', 'label'=>_('Products'), 'title'=>_("Products database"), 'icon'=>'cube', 'reference'=>'store',
				'tabs'=>array(
					'store.products'=>array()
				)


			),
			'categories'=>array('type'=>'navigation', 'label'=>_('Categories'), 'title'=>_("Products categories"), 'icon'=>'sitemap', 'reference'=>'store/categories'),
			'store'=>array(
				'type'=>'object',
				'label'=>_('Store'),
				'title'=>_('Store'),
				'icon'=>'',
				'reference'=>'store/%d',
				'tabs'=>array(
					'store.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard')),
					'store.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details')),
					'store.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'store.departments'=>array('label'=>_('Departments'), 'title'=>_('Departments')),
					'store.families'=>array('label'=>_('Families'), 'title'=>_('Families')),
					'store.products'=>array('label'=>_('Products'), 'title'=>_('Products')),

				)
			),
			'department'=>array(
				'type'=>'object',

				'tabs'=>array(
					'department.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard')),
					'department.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database'),
					'department.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'department.families'=>array('label'=>_('Families'), 'title'=>_('Families')),
					'department.products'=>array('label'=>_('Products'), 'title'=>_('Products')),

				)
			),
			'family'=>array(
				'type'=>'object',

				'tabs'=>array(
					'family.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard')),
					'family.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database'),
					'family.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'family.products'=>array('label'=>_('Products'), 'title'=>_('Products')),

				)
			),
			'product'=>array(
				'type'=>'object',
				'subtabs_parent'=>array(
					'product.sales.overview'=>'product.sales',
					'product.sales.history'=>'product.sales',
					'product.sales.calendar'=>'product.sales',
					'product.customers.customers'=>'product.customers',
					'product.customers.favourites'=>'product.customers',
					'product.website.webpage'=>'product.website',
					'product.website.pages'=>'product.website',
				),

				'tabs'=>array(
					'product.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard')),
					'product.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details')),
					'product.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'product.sales'=>array('label'=>_('Sales'), 'title'=>_('Sales'),
						'subtabs'=>array(
							'product.sales.overview'=>array('label'=>_('Overview'), 'title'=>_('Overview')),
							'product.sales.history'=>array('label'=>_('Sales history'), 'title'=>_('Sales history')),
							'product.sales.calendar'=>array('label'=>_('Calendar'), 'title'=>_('Sales calendar')),

						)
					),
					'product.orders'=>array('label'=>_('Orders'), 'title'=>_('Orders'),

					),
					'product.customers'=>array('label'=>_('Customers'), 'title'=>_('Customers'), 
						'subtabs'=>array(
							'product.customers.customers'=>array('label'=>_('Customers'), 'title'=>_('Customers')),
							'product.customers.favourites'=>array('label'=>_('Customers who favorited'), 'title'=>_('Customers who favorited')),

						)
					),
					'product.offers'=>array('label'=>_('Offers'), 'title'=>_('Offers')),

					'product.website'=>array('label'=>_('Website'), 'title'=>_('Website'),
						'subtabs'=>array(
							'product.website.webpage'=>array('label'=>_('Webpage'), 'title'=>_('Product webpage')),
							'product.sales.pages'=>array('label'=>_('Webpages'), 'title'=>_('Webpages where this product is on sale')),

						)
					),

				)
			),

		)
	),
	'products_server'=>array(

		'parent'=>'none',
		'parent_type'=>'none',
		'sections'=>array(
			'stores'=>array('type'=>'navigation', 'label'=>_('Stores'), 'title'=>_('Stores'), 'icon'=>'shopping-cart', 'reference'=>'stores',
				'tabs'=>array(
					'stores'=>array()
				)
			)

		)
	),
	'marketing'=>array(
		'section'=>'marketing',
		'parent'=>'store',
		'parent_type'=>'key',
		'sections'=>array(

			'deals'=>array(
				'type'=>'navigation', 'label'=>_('Deals'), 'title'=>_('Deals'), 'icon'=>'tag', 'reference'=>'marketing/%d/deals',
				'tabs'=>array(
					'campaigns'=>array('label'=>_('Campaigns'), 'title'=>_('Campaigns')),
					'offers'=>array('label'=>_('Offers'), 'title'=>_('Offers')),

				)


			),
			'enewsletters'=>array('type'=>'navigation', 'label'=>_('eNewsletters'), 'title'=>_('eNewsletters'), 'icon'=>'newspaper-o', 'reference'=>'marketing/%d/enewsletters',
				'tabs'=>array(
					'enewsletters'=>array()
				)
			),
			'mailshots'=>array('type'=>'navigation', 'label'=>_('Mailshots'), 'title'=>_('Mailshots'), 'icon'=>'at', 'reference'=>'marketing/%d/mailshots',
				'tabs'=>array(
					'mailshots'=>array()
				)),
			'marketing_post'=>array('type'=>'navigation', 'label'=>_('Marketing Post'), 'title'=>_('Marketing Post'), 'icon'=>'envelope-o', 'reference'=>'marketing/%d/marketing_post',
				'tabs'=>array(
					'marketing_post'=>array()
				)
			),
			'marketing_media'=>array('type'=>'navigation', 'label'=>_('Marketing Media'), 'title'=>_('Marketing Media'), 'icon'=>'google', 'reference'=>'marketing/%d/marketing_media',
				'tabs'=>array(
					'marketing_media'=>array()
				)
			),
			'ereminders'=>array('type'=>'navigation', 'label'=>_('eReminders'), 'title'=>_('eReminders'), 'icon'=>'bell-o', 'reference'=>'marketing/%d/ereminders',
				'tabs'=>array(
					'ereminders'=>array()
				)
			),





		)
	),
	'marketing_server'=>array(

		'parent'=>'none',
		'parent_type'=>'none',
		'section'=>'marketing',
		'sections'=>array(
			'marketing'=>array('type'=>'navigation', 'label'=>_('Marketing (All stores)'), 'title'=>_('Marketing (All stores)'), 'icon'=>'', 'reference'=>'marketing/all',
				'tabs'=>array(
					'marketing_server'=>array()
				)
			),

		)

	),



	'suppliers'=>array(
		'section'=>'suppliers',
		'parent'=>'store',
		'parent_type'=>'key',
		'sections'=>array(
			'dashboard'=>array('type'=>'navigation', 'label'=>_('Dashboard'), 'title'=>_("Supplier's dashboard"), 'icon'=>'dashboard', 'reference'=>'suppliers/%d/dashboard',
				'tabs'=>array(
					'suppliers.dashboard'=>array()
				)
			),

			'suppliers'=>array(

				'type'=>'navigation', 'label'=>_('Suppliers'), 'title'=>_('Suppliers'), 'icon'=>'industry', 'reference'=>'suppliers',
				'tabs'=>array(
					'suppliers'=>array()
				)



			),
			'lists'=>array('type'=>'navigation', 'label'=>_('Lists'), 'title'=>_('Lists'), 'icon'=>'list', 'reference'=>'suppliers/%d/lists',
				'tabs'=>array(
					'suppliers.lists'=>array()
				)

			),
			'categories'=>array('type'=>'navigation', 'label'=>_('Categories'), 'title'=>_('Categories'), 'icon'=>'sitemap', 'reference'=>'suppliers/%d/categories',
				'tabs'=>array(
					'suppliers.categories'=>array()
				)

			),

			'list'=>array(
				'type'=>'object',
				'tabs'=>array(
					'suppliers.list'=>array()
				)



			),
			'category'=>array(
				'type'=>'object',

				'tabs'=>array(
					'category.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details')),
					'category.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'category.subjects'=>array('label'=>_('Customers'), 'title'=>_('Customers')),
					'category.subcategories'=>array('label'=>_('Subcategories'), 'title'=>_('Subcategories')),

				)

			),

			'supplier'=>array(
				'type'=>'object',
				'label'=>_('Supplier'),
				'title'=>_('Supplier'),
				'icon'=>'industry',
				'reference'=>'supplier/%d',
				'tabs'=>array(
					'supplier.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details')),
					'supplier.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'supplier.orders'=>array('label'=>_('Orders'), 'title'=>_('Orders')),

				)
			)




		)
	),
	'warehouses_server'=>array(
		'sections'=>array(

			'warehouses'=>array(

				'type'=>'navigation', 'label'=>_('Warehouses'), 'title'=>_('Warehouses'), 'icon'=>'map-maker', 'reference'=>'warehouses', 'tabs'=>array(
					'warehouses'=>array()
				)
			)

		)
	),
	'inventory'=>array(
		'sections'=>array(
			'inventory'=>array(

				'type'=>'navigation', 'label'=>_('Inventory').' ('._('Parts').')', 'title'=>_('Inventory'), 'icon'=>'th', 'reference'=>'inventory/%d',
				'tabs'=>array(
					'inventory.parts'=>array('label'=>_('Parts'), 'title'=>_('Parts')),
					'inventory.families'=>array('label'=>_("Part's Families"), 'title'=>_("Part's families")),

				)
			),
			'categories'=>array('type'=>'navigation', 'label'=>_("Part's Categories"), 'title'=>_("Part's Categories"), 'icon'=>'sitemap', 'reference'=>'inventory/categories',

				'tabs'=>array(
					'inventory.categories'=>array('label'=>_("Part's Categories"), 'title'=>_("Part's Categories")),
				)
			),

			'part'=>array('type'=>'object',
				'subtabs_parent'=>array(
					'part.sales.overview'=>'part.sales',
					'part.sales.history'=>'part.sales',
					'part.sales.products'=>'part.sales',
					'part.stock.overview'=>'part.stock',
					'part.stock.transactions'=>'part.stock',
					'part.stock.history'=>'part.stock',
					'part.stock.availability'=>'part.stock',
					'part.purchase_orders.purchase_orders'=>'part.purchase_orders',
					'part.purchase_orders.delivery_notes'=>'part.purchase_orders',
					'part.purchase_orders.invoices'=>'part.purchase_orders',
				),



				'tabs'=>array(


					'part.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database'),
					'part.history'=>array('label'=>_('History/Notes'), 'title'=>_('History/Notes'), 'icon'=>'history'),
					'part.sales'=>array('label'=>_('Sales'), 'title'=>_('Sales'), 'icon'=>'money', 
						'subtabs'=>array(
							'part.sales.overview'=>array('label'=>_('Overview'), 'title'=>_('Overview')),
							'part.sales.history'=>array('label'=>_('Sales history'), 'title'=>_('Sales history')),
							'part.sales.products'=>array('label'=>_('Product sales breakdown'), 'title'=>_('Product sales breakdown')),

						)

					),
					'part.stock'=>array('label'=>_('Stock'), 'title'=>_('Stock'), 'icon'=>'th',
						'subtabs'=>array(
							'part.stock.overview'=>array('label'=>_('Overview'), 'title'=>_('Overview')),
							'part.stock.transactions'=>array('label'=>_('Transactions history'), 'title'=>_('Transactions history')),
							'part.stock.history'=>array('label'=>_('Stock history'), 'title'=>_('Stock history')),
							'part.stock.availability'=>array('label'=>_('Availability history'), 'title'=>_('Availability history')),

						)
					),
					'part.purchase_orders'=>array('label'=>_('Purchase Orders'), 'title'=>_('Purchase Orders'), 'icon'=>'ship', 
							'subtabs'=>array(
							'part.purchase_orders.purchase_orders'=>array('label'=>_('Purchase Orders'), 'title'=>_('Purchase Orders')),
							'part.purchase_orders.delivery_notes'=>array('label'=>_('Delivery Notes'), 'title'=>_("Supplier's delivery notes")),
							'part.purchase_orders.invoices'=>array('label'=>_('Invoices'), 'title'=>_("Supplier's invoices")),

						)

					),
					'part.products'=>array('label'=>_('Products'), 'title'=>_('Products'), 'icon'=>'square'),
				)
			),
			'transactions'=>array(
				'type'=>'navigation', 'label'=>_('Stock Movements'), 'title'=>_('Stock movements'), 'icon'=>'exchange', 'reference'=>'inventory/transactions',
				'tabs'=>array(
					'inventory.transactions'=>array('label'=>_('Stock movements'), 'title'=>_('Stock movements'))

				)
			),
			'stock_history'=>array(
				'type'=>'navigation', 'label'=>_('Stock History'), 'title'=>_('Stock History'), 'icon'=>'history', 'reference'=>'inventory/stock_history',
				'tabs'=>array(
					'inventory.stock_history.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard')),
					'inventory.stock_history.timeline'=>array('label'=>_('Timeline'), 'title'=>_('Timeline')),

				)
			),

		)
	),
	'warehouses'=>array(
		'sections'=>array(
			'warehouse'=>array(

				'type'=>'navigation', 'label'=>_('Warehouse'), 'title'=>_('Warehouse'), 'icon'=>'th-large', 'reference'=>'warehouse/%d',
				'tabs'=>array(
					'warehouse.details'=>array('label'=>_('Details'), 'title'=>_('Warehouse detais')),
					'warehouse.history'=>array('label'=>_('History/Notes'), 'title'=>_('History/Notes'), 'icon'=>'history'),
					'warehouse.locations'=>array('label'=>_('Locations'), 'title'=>_('Locations')),
					'warehouse.replenishments'=>array('label'=>_("Replenishments"), 'title'=>_("Replenishments")),
					'warehouse.parts'=>array('label'=>_('Part-Locations'), 'title'=>_('Part-Locations')),

				)

			),







		)
	),


	'reports'=>array(

		'sections'=>array(
			'reports'=>array('type'=>'navigation', 'label'=>_('Activity/Performance'), 'title'=>_("Activity/Performance"), 'icon'=>'thumbs-o-up', 'reference'=>'users',
				'tabs'=>array(
					'reports'=>array(),

				)

			),
			/*
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
*/


		)
	),



	'hr'=>array(

		'sections'=>array(
			'employees'=>array('type'=>'navigation', 'label'=>_('Employees'), 'title'=>_("Employees"), 'icon'=>'hand-rock-o', 'reference'=>'hr',
				'tabs'=>array(
					'employees'=>array('label'=>_('Employees'), 'title'=>_('Employees')),
					'timesheets'=>array('label'=>_('Timesheets'), 'title'=>_('Timesheets')),

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
					'employee.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details')),
					'employee.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'employee.timesheet'=>array('label'=>_('Timesheet'), 'title'=>_('Timesheet')),

				)

			),
			'new_timesheet_record'=>array(
				'type'=>'new', 'label'=>_('New timesheet record'), 'title'=>_('New timesheet record'), 'icon'=>'clock', 'reference'=>'hr/new_timesheet_record',
				'tabs'=>array(
					'timesheet_record.new'=>array('label'=>_('New timesheet record'), 'title'=>_('New timesheet record')),
					'timesheet_record.import'=>array('label'=>_('Import'), 'title'=>_('Import timesheet record')),
					'timesheet_record.api'=>array('label'=>_('API'), 'title'=>_('API')),
					'timesheet_record.cancel'=>array('class'=>'right','label'=>_('Cancel'), 'title'=>_('Cancel'),'icon'=>'sign-out fa-flip-horizontal'),

				)
				
			),

		)
	),

	'profile'=>array(


		'sections'=>array(
			'profile'=>array('type'=>'object', 'label'=>'', 'title'=>'', 'icon'=>'', 'reference'=>'',
				'tabs'=>array(
					'profile.details'=>array('label'=>_('Details'), 'title'=>_('My details')),
					'profile.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'profile.login_history'=>array('label'=>_('Login history'), 'title'=>_('Login history')),
				)
			),

		)

	),

	'account'=>array(


		'sections'=>array(
			'account'=>array('type'=>'navigation', 'label'=>_('Account'), 'title'=>_('Account'), 'icon'=>'star', 'reference'=>'account',
				'tabs'=>array(
					'account.details'=>array('label'=>_('Details'), 'title'=>_('Account details')),
					'payment_service_providers'=>array('label'=>_('Payment options'), 'title'=>_('Payment options')),
				)
			),

			'users'=>array('type'=>'navigation', 'label'=>_('Users'), 'title'=>('Users'), 'icon'=>'male', 'reference'=>'account/users',
				'tabs'=>array(
					'account.users'=>array('label'=>_('Details'), 'title'=>_('Account details')),
				)
			),
			'settings'=>array('type'=>'navigation', 'label'=>_('Settings'), 'title'=>('Settings'), 'icon'=>'cog', 'reference'=>'account/settings',
				'tabs'=>array(
					'account.settings'=>array('label'=>_('Settings'), 'title'=>_('Settings')),
				)
			),
			'staff'=>array('type'=>'object', 'label'=>_('Staff'), 'title'=>_("Staff users"), 'icon'=>'hand-rock-o', 'reference'=>'users',
				'tabs'=>array(
					'users.staff.users'=>array('label'=>_('Users'), 'title'=>_('Users')),
					'users.staff.groups'=>array('label'=>_("Groups"), 'title'=>_("Groups")),
					'users.staff.login_history'=>array('label'=>_('Login History'), 'title'=>_('Login History')),

				)

			),

			'suppliers'=>array(
				'type'=>'object', 'label'=>_('Suppliers'), 'title'=>_('Suppliers users'), 'icon'=>'industry', 'reference'=>'users/suppliers',
			),
			'warehouse'=>array(
				'type'=>'object', 'label'=>_('Warehouse'), 'title'=>_('Warehouse users'), 'icon'=>'th-large', 'reference'=>'users/warehouse',
			),
			'root'=>array(
				'type'=>'object', 'label'=>'Root', 'title'=>_('Root user'), 'icon'=>'dot-circle-o', 'reference'=>'suppliers',
			),
			'staff.user'=>array('type'=>'object',
				'tabs'=>array(
					'staff.user.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details')),
					'staff.user.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'staff.user.login_history'=>array('label'=>_('Login history'), 'title'=>_('Login history')),
					'staff.user.api_keys'=>array('label'=>_('API keys'), 'title'=>_('API keys')),

				)
			),
			'payment_service_provider'=>array('type'=>'object',
				'tabs'=>array(
					'payment_service_provider.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details')),
					'payment_service_provider.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'payment_service_provider.accounts'=>array('label'=>_('Accounts'), 'title'=>_('Payment accounts')),
					'payment_service_provider.payments'=>array('label'=>_('Payments'), 'title'=>_('Payments transactions')),

				)
			),
			'payment_account'=>array('type'=>'object',
				'tabs'=>array(
					'payment_account.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details')),
					'payment_account.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'payment_account.payments'=>array('label'=>_('Payments'), 'title'=>_('Payments transactions')),

				)
			),
			'payment'=>array('type'=>'object',
				'tabs'=>array(
					'payment.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details')),
					'payment.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o'),

				)
			)


		)

	),

	'utils'=>array(
		'sections'=>array(
			'forbidden'=>array('type'=>'object', 'label'=>_('Forbidden'), 'title'=>_('Forbidden'), 'icon'=>'shopping-cart', 'id'=>'forbidden',
				'tabs'=>array(
					'forbidden'=>array()
				)
			),
			'not_found'=>array('type'=>'object', 'label'=>_('Not found'), 'title'=>_('Not found'), 'icon'=>'file-o', 'id'=>'not_found',

				'tabs'=>array(
					'not_found'=>array(),
				)
			),
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
