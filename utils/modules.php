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
			'dashboard'=>array('type'=>'widgets', 'label'=>_('Home'), 'icon'=>'home',
				'tabs'=>array(
					'dashboard'=>array('label'=>_('Dashboard')),

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
				'type'=>'navigation', 'label'=>_('Customers'), 'icon'=>'users', 'reference'=>'customers/%d',
				'tabs'=>array(
					'customers'=>array()
				)


			),
			'lists'=>array('type'=>'navigation', 'label'=>_('Lists'), 'icon'=>'list', 'reference'=>'customers/%d/lists',
				'tabs'=>array(
					'customers.lists'=>array()
				)
			),
			'categories'=>array('type'=>'navigation', 'label'=>_('Categories'), 'icon'=>'sitemap', 'reference'=>'customers/%d/categories',
				'tabs'=>array(
					'customers.categories'=>array()
				)
				,

			),
			'statistics'=>array('type'=>'navigation', 'label'=>_('Statistics'), 'icon'=>'line-chart', 'reference'=>'customers/%dstatistics',
				'tabs'=>array(
					'contacts'=>array('label'=>_('Contacts')),
					'customers'=>array('label'=>_('Customers')),
					'orders'=>array('label'=>_('Orders')),
					'data_integrity'=>array('label'=>_('Data Integrity')),
					'geo'=>array('label'=>_('Geographic Distribution')),
					'correlations'=>array('label'=>_('Correlations')),

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
					'category.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'category.history'=>array('label'=>_('History'), 'icon'=>'sticky-note-o'),
					'category.customers'=>array('label'=>_('Customers')),
					'category.categories'=>array('label'=>_('Subcategories')),

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
					'customer.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'customer.history'=>array('label'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'customer.orders'=>array('label'=>_('Orders')),
					'customer.marketing'=>array('label'=>_('Interests'), 'title'=>_("Customer's interests"),
						'subtabs'=>array(
							'customer.marketing.overview'=>array('label'=>_('Overview')),
							'customer.marketing.products'=>array('label'=>_('Products ordered')),
							'customer.marketing.families'=>array('label'=>_('Families ordered')),
							'customer.marketing.favourites'=>array('label'=>_('Favourite products')),
							'customer.marketing.search'=>array('label'=>_('Search queries')),

						)

					),

				)
			),


			'customer.new'=>array('type'=>'new_object',
				'tabs'=>array(
					'customer.new'=>array('label'=>_('New customer')),

				)

			),


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
			'orders'=>array('type'=>'navigation', 'label'=>_('Orders'), 'icon'=>'shopping-cart', 'reference'=>'orders/%d',
				'tabs'=>array(
					'orders'=>array()
				)
			),



			'order'=>array('type'=>'object',
				'tabs'=>array(


					'order.items'=>array('label'=>_('Items'), 'icon'=>'bars'),
					'order.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'order.history'=>array('label'=>_('History'), 'icon'=>'history'),
					'order.delivery_notes'=>array('label'=>_('Delivery notes'),  'icon'=>'truck'),
					'order.invoices'=>array('label'=>_('Invoices'),  'icon'=>'usd'),

				)

			),
			'delivery_note'=>array('type'=>'object',
				'tabs'=>array(


					'delivery_note.items'=>array('label'=>_('Items'), 'icon'=>'bars'),
					'delivery_note.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'delivery_note.history'=>array('label'=>_('History'), 'icon'=>'history'),
					'delivery_note.orders'=>array('label'=>_('Orders'), 'icon'=>'shopping-cart'),
					'delivery_note.invoices'=>array('label'=>_('Invoices'), 'icon'=>'usd'),
				)

			),
			'invoice'=>array('type'=>'object',
				'tabs'=>array(


					'invoice.items'=>array('label'=>_('Items'), 'icon'=>'bars'),
					'invoice.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'invoice.history'=>array('label'=>_('History'), 'icon'=>'history'),
					'invoice.orders'=>array('label'=>_('Orders'), 'icon'=>'shopping-cart'),
					'invoice.delivery_notes'=>array('label'=>_('Delivery notes'), 'icon'=>'truck'),
				)

			),

		)
	),
	'orders_server'=>array(

		'parent'=>'none',
		'parent_type'=>'none',
		'section'=>'orders',
		'sections'=>array(
			'orders'=>array('type'=>'navigation', 'label'=>_('Orders'), 'icon'=>'shopping-cart', 'reference'=>'orders/all',
				'tabs'=>array(
					'orders_server'=>array()
				)

			),

		)

	),
	'invoices'=>array(
		'section'=>'invoices',
		'parent'=>'store',
		'parent_type'=>'key',
		'sections'=>array(

			'invoices'=>array('type'=>'navigation', 'label'=>_('Invoices'), 'icon'=>'usd', 'reference'=>'invoices/%d',
				'tabs'=>array(
					'invoices'=>array('label'=>_('Invoices'), 'icon'=>'usd'),
				)
			),

			'categories'=>array('type'=>'navigation', 'label'=>_("Categories"), 'title'=>_("Invoice's categories"), 'icon'=>'sitemap', 'reference'=>'invoices/%d/categories',
				'tabs'=>array(
					'invoices.categories'=>array(),
				)
			),



			'order'=>array('type'=>'object',
				'tabs'=>array(


					'order.items'=>array('label'=>_('Items'), 'icon'=>'bars'),
					'order.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'order.history'=>array('label'=>_('History'), 'icon'=>'history'),
					'order.delivery_notes'=>array('label'=>_('Delivery notes'),  'icon'=>'truck'),
					'order.invoices'=>array('label'=>_('Invoices'),  'icon'=>'usd'),

				)

			),
			'delivery_note'=>array('type'=>'object',
				'tabs'=>array(


					'delivery_note.items'=>array('label'=>_('Items'), 'icon'=>'bars'),
					'delivery_note.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'delivery_note.history'=>array('label'=>_('History'), 'icon'=>'history'),
					'delivery_note.orders'=>array('label'=>_('Orders'), 'icon'=>'shopping-cart'),
					'delivery_note.invoices'=>array('label'=>_('Invoices'), 'icon'=>'usd'),
				)

			),
			'invoice'=>array('type'=>'object',
				'tabs'=>array(


					'invoice.items'=>array('label'=>_('Items'), 'icon'=>'bars'),
					'invoice.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'invoice.history'=>array('label'=>_('History'), 'icon'=>'history'),
					'invoice.orders'=>array('label'=>_('Orders'), 'icon'=>'shopping-cart'),
					'invoice.delivery_notes'=>array('label'=>_('Delivery notes'), 'icon'=>'truck'),
				)

			),

		)
	),
	'invoices_server'=>array(

		'parent'=>'none',
		'parent_type'=>'none',

		'sections'=>array(
			'invoices'=>array('type'=>'navigation', 'label'=>_('Invoices'), 'icon'=>'shopping-cart', 'reference'=>'invoices/all',
				'tabs'=>array(
					'invoices_server'=>array()
				)

			),

			'categories'=>array('type'=>'navigation', 'label'=>_("Categories"), 'title'=>_("Invoice's categories").' ('._('All stores').')', 'icon'=>'sitemap', 'reference'=>'invoices/all/categories',
				'tabs'=>array(
					'invoices_server.categories'=>array(),
				)
			),

			'category'=>array('type'=>'object',
				'tabs'=>array(

					'category.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'category.history'=>array('label'=>_('History'), 'icon'=>'clock'),
					'category.invoices'=>array('label'=>_('Invoices')),
					'category.categories'=>array('label'=>_('Subcategories')),

				)

			),

		)

	),
	'delivery_notes'=>array(
		'section'=>'delivery_notes',
		'parent'=>'store',
		'parent_type'=>'key',
		'sections'=>array(

			'delivery_notes'=>array('type'=>'navigation', 'label'=>_('Delivery notes'), 'icon'=>'truck fa-flip-horizontal', 'reference'=>'delivery_notes/%d',
				'tabs'=>array(
					'delivery_notes'=>array()
				)
			),


			'order'=>array('type'=>'object',
				'tabs'=>array(


					'order.items'=>array('label'=>_('Items'), 'icon'=>'bars'),
					'order.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'order.history'=>array('label'=>_('History'), 'icon'=>'history'),
					'order.delivery_notes'=>array('label'=>_('Delivery notes'),  'icon'=>'truck'),
					'order.invoices'=>array('label'=>_('Invoices'),  'icon'=>'usd'),

				)

			),
			'delivery_note'=>array('type'=>'object',
				'tabs'=>array(


					'delivery_note.items'=>array('label'=>_('Items'), 'icon'=>'bars'),
					'delivery_note.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'delivery_note.history'=>array('label'=>_('History'), 'icon'=>'history'),
					'delivery_note.orders'=>array('label'=>_('Orders'), 'icon'=>'shopping-cart'),
					'delivery_note.invoices'=>array('label'=>_('Invoices'), 'icon'=>'usd'),
				)

			),
			'invoice'=>array('type'=>'object',
				'tabs'=>array(


					'invoice.items'=>array('label'=>_('Items'), 'icon'=>'bars'),
					'invoice.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'invoice.history'=>array('label'=>_('History'), 'icon'=>'history'),
					'invoice.orders'=>array('label'=>_('Orders'), 'icon'=>'shopping-cart'),
					'invoice.delivery_notes'=>array('label'=>_('Delivery notes'), 'icon'=>'truck'),
				)

			),
			'pick_aid'=>array('type'=>'object',
				'tabs'=>array(


					'pick_aid.items'=>array('label'=>_('Items'), 'icon'=>'bars'),
				)

			), 'pack_aid'=>array('type'=>'object',
				'tabs'=>array(


					'pack_aid.items'=>array('label'=>_('Items'), 'icon'=>'bars'),
				)

			),

		)
	),
	'delivery_notes_server'=>array(

		'parent'=>'none',
		'parent_type'=>'none',
		'section'=>'orders',
		'sections'=>array(
			'delivery_notes'=>array('type'=>'navigation',
				'tabs'=>array(
					'delivery_notes_server'=>array()
				)

			),

		)

	),
	'payments'=>array(
		'section'=>'invoices',
		'parent'=>'store',
		'parent_type'=>'key',
		'sections'=>array(

			'payment_service_providers'=>array('type'=>'navigation', 'label'=>_('Payment Service Providers'), 'icon'=>'university', 'reference'=>'payment_service_providers',
				'tabs'=>array(
					'payment_service_providers'=>array('label'=>_('Payment Service Providers'), 'icon'=>'university'),
				)
			),

			'payment_accounts'=>array('type'=>'navigation', 'label'=>_("Payment Accounts"), 'icon'=>'cc', 'reference'=>'payment_accounts/%s',
				'tabs'=>array(
					'payment_accounts'=>array(),
				)
			),

			'payments'=>array('type'=>'navigation', 'label'=>_('Payments'), 'icon'=>'credit-card', 'reference'=>'payments/%s',
				'tabs'=>array(
					'payments'=>array()
				)
			),

			'payment_service_provider'=>array('type'=>'object',
				'tabs'=>array(
					'payment_service_provider.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'payment_service_provider.history'=>array('label'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'payment_service_provider.accounts'=>array('label'=>_('Accounts'), 'title'=>_('Payment accounts')),
					'payment_service_provider.payments'=>array('label'=>_('Payments'), 'title'=>_('Payments transactions')),

				)
			),
			'payment_account'=>array('type'=>'object',
				'tabs'=>array(
					'payment_account.details'=>array('label'=>_('Data'), 'icon'=>'database', 'title'=>_('Details')),
					'payment_account.history'=>array('label'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'payment_account.payments'=>array('label'=>_('Payments'), 'title'=>_('Payments transactions')),

				)
			),
			'payment'=>array('type'=>'object',
				'tabs'=>array(
					'payment.details'=>array('label'=>_('Data'), 'icon'=>'database', 'title'=>_('Details')),
					'payment.history'=>array('label'=>_('History, Notes'), 'icon'=>'sticky-note-o'),

				)
			)



		)
	),
	'websites'=>array(
		'section'=>'dashboard',
		'parent'=>'website',
		'parent_type'=>'key',
		'sections'=>array(
			'websites'=>array('type'=>'navigation', 'label'=>_('Websites'), 'icon'=>'globe', 'reference'=>'websites',
				'tabs'=>array(
					'websites'=>array()
				)

			),
			'website'=>array('type'=>'object', 'label'=>_('Website'),  'icon'=>'globe', 'reference'=>'website/%d',

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
					'website.details'=>array('label'=>_('Data'), 'icon'=>'database'),

					'website.pages'=>array('label'=>_('Pages'), 'icon'=>'files-o'),
					'website.pageviews'=>array('label'=>_('Pageviews'), 'icon'=>'eye'),
					'website.users'=>array('label'=>_('Users'), 'icon'=>'male'),
					'website.search'=>array('label'=>_('Queries'), 'title'=>_('Search Queries'), 'icon'=>'search',
						'subtabs'=>array(
							'website.search.queries'=>array('label'=>_('Queries'), 'title'=>_('Search queries goruped by keywords')),
							'website.search.history'=>array('label'=>_('Search History'), 'title'=>_('List of all search queries')),

						)

					),
					'website.favourites'=>array('label'=>_('Favourites'), 'title'=>_('Favourites'), 'icon'=>'heart-o',
						'subtabs'=>array(
							'website.favourites.products'=>array('label'=>_('Products')),
							'website.favourites.customers'=>array('label'=>_('Customers')),

						)

					),
					'website.reminders'=>array('label'=>_('OOS Reminders'), 'title'=>_('Out of stock reminders'), 'icon'=>'hand-paper-o',
						'subtabs'=>array(
							'website.reminders.requests'=>array('label'=>_('Requests'), 'title'=>_('Out of stock notifications requests')),
							'website.reminders.customers'=>array('label'=>_('Customers'), 'title'=>_('Customers who ask for a out of stock notification')),
							'website.reminders.products'=>array('label'=>_('Products'), 'title'=>_('Out of stock notifications grouped by product')),

						)

					),

				)
			),
			'page'=>array('type'=>'object',
				'tabs'=>array(


					'page.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Page dashboard'), 'icon'=>'dashboard'),
					'page.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'page.pageviews'=>array('label'=>_('Pageviews'), 'icon'=>'eye'),
					'page.users'=>array('label'=>_('Users'), 'icon'=>'male'),
				)
			),
			'website.user'=>array('type'=>'object',
				'tabs'=>array(
					'website.user.details'=>array('label'=>_('Data'), 'icon'=>'database', 'title'=>_('Details')),
					'website.user.history'=>array('label'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'website.user.login_history'=>array('label'=>_('Sessions'), 'title'=>_('Login history') , 'icon'=>'login'),
					'website.user.pageviews'=>array('label'=>_('Pageviews') , 'icon'=>'eye'),

				)
			)




			//'categories'=>array('label'=>_('Categories'),'title'=>_('Categories'),'icon'=>'sitemap','reference'=>'orders/categories/%d'),
		)
	),
	'products'=>array(
		'section'=>'products',
		'sections'=>array(
			'dashboard'=>array('type'=>'navigation', 'label'=>_('Dashboard'), 'title'=>_("Products's dashboard"), 'icon'=>'dashboard', 'reference'=>'store/%d/dashboard',
				'tabs'=>array(
					'store.dashboard'=>array()
				)
			),

			'store'=>array(
				'type'=>'navigation',
				'label'=>_('Store'),
				'title'=>_('Store'),
				'icon'=>'shopping-bag',
				'showcase'=>true,
				'reference'=>'store/%d',
				'tabs'=>array(
					'store.details'=>array('label'=>_('Data'), 'icon'=>'database', 'title'=>_('Details')),
					'store.history'=>array('label'=>_('History, Notes'), 'icon'=>'sticky-note-o'),


				)
			),

			'store.new'=>array('type'=>'new_object',
				'tabs'=>array(
					'store.new'=>array('label'=>_('New store')),

				)

			),
			'products'=>array('type'=>'navigation', 'label'=>_('Products'), 'title'=>_("Products database"), 'icon'=>'cube', 'reference'=>'products/%d',
				'tabs'=>array(
					'products'=>array()
				)


			),


			'categories'=>array('type'=>'navigation', 'label'=>_('Categories'), 'title'=>_("Products categories"), 'icon'=>'sitemap', 'reference'=>'products/%d/categories', 'showcase'=>'products_special_categories',
				'tabs'=>array(
					'products.categories'=>array()
				)

			),
			'category'=>array(
				'type'=>'object',

				'tabs'=>array(
					'category.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'category.history'=>array('label'=>_('History'), 'icon'=>'sticky-note-o'),
					'category.categories'=>array('label'=>_('Subcategories')),
					'category.subjects'=>array('label'=>''),

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
					'product.details'=>array('label'=>_('Data'), 'icon'=>'database', 'title'=>_('Details')),
					'product.history'=>array('label'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'product.sales'=>array('label'=>_('Sales'), 'title'=>_('Sales'),
						'subtabs'=>array(
							'product.sales.overview'=>array('label'=>_('Overview'), 'title'=>_('Overview')),
							'product.sales.history'=>array('label'=>_('Sales history'), 'title'=>_('Sales history')),
							'product.sales.calendar'=>array('label'=>_('Calendar'), 'title'=>_('Sales calendar')),

						)
					),
					'product.orders'=>array('label'=>_('Orders'),

					),
					'product.customers'=>array('label'=>_('Customers'),
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
			'product.new'=>array('type'=>'new_object',
				'tabs'=>array(
					'product.new'=>array('label'=>_('New product')),

				)

			),

		)
	),
	'products_server'=>array(

		'parent'=>'none',
		'parent_type'=>'none',
		'sections'=>array(
			'stores'=>array('type'=>'navigation', 'label'=>_('Stores'), 'icon'=>'shopping-bag', 'reference'=>'stores',
				'tabs'=>array(
					'stores'=>array()
				)
			),
			'products'=>array('type'=>'navigation', 'label'=>_('Products'), 'icon'=>'cube', 'reference'=>'products/all',
				'tabs'=>array(

					'products'=>array('label'=>_('Products'), 'icon'=>'cube'),

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
	'production'=>array(
		'section'=>'production',
		'parent'=>'account',
		'parent_type'=>'key',
		'sections'=>array(
			'dashboard'=>array('type'=>'navigation', 'label'=>_('Dashboard'), 'title'=>_("Manufacture dashboard"), 'icon'=>'dashboard', 'reference'=>'production',
				'tabs'=>array(
					'production.dashboard'=>array()
				)
			),

			'manufacture_tasks'=>array(
				'type'=>'navigation', 'label'=>_('Tasks/Products'), 'icon'=>'tasks', 'reference'=>'production/manufacture_tasks',
				'tabs'=>array(
					'manufacture_tasks'=>array('label'=>_('Tasks'))
				)



			),


			'manufacture_task'=>array(
				'type'=>'object', 'label'=>_('Task'), 'icon'=>'tasks',
				'tabs'=>array(
					'manufacture_task.details'=>array('label'=>_('Data')),
					'manufacture_task.batches'=>array('label'=>_('Batches'))

				)



			),

			'manufacture_task.new'=>array('type'=>'new_object',
				'tabs'=>array(
					'manufacture_task.new'=>array('label'=>_('New task')),

				)

			),

			'operatives'=>array(
				'type'=>'navigation', 'label'=>_('Operatives'), 'icon'=>'hand-rock-o', 'reference'=>'production/operatives',
				'tabs'=>array(
					'operatives'=>array('label'=>_('Operatives'))
				)



			),
			'batches'=>array(
				'type'=>'navigation', 'label'=>_('Batches'), 'icon'=>'clone', 'reference'=>'production/batches',
				'tabs'=>array(
					'batches'=>array('label'=>_('Batches'))
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

				'type'=>'navigation', 'label'=>_('Suppliers'), 'icon'=>'ship', 'reference'=>'suppliers',
				'tabs'=>array(
					'suppliers'=>array()
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
					'category.details'=>array('label'=>_('Data'), 'icon'=>'database', 'title'=>_('Details')),
					'category.history'=>array('label'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'category.subjects'=>array('label'=>_('Customers'), 'title'=>_('Customers')),
					'category.categories'=>array('label'=>_('Subcategories')),

				)

			),

			'supplier'=>array(
				'type'=>'object',
				'label'=>_('Supplier'),
				'icon'=>'ship',
				'reference'=>'supplier/%d',
				'tabs'=>array(
					'supplier.details'=>array('label'=>_('Data'), 'icon'=>'database', 'title'=>_('Details')),
					'supplier.history'=>array('label'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'supplier.supplier_parts'=>array('label'=>_('Parts'), 'icon'=>'stop'),
					'supplier.orders'=>array('label'=>_('Orders'), 'icon'=>'clipboard'),

				)
			),
			'supplier.new'=>array('type'=>'new_object',
				'tabs'=>array(
					'supplier.new'=>array('label'=>_('New supplier')),

				)

			),
			'supplier_part'=>array('type'=>'object',
				'subtabs_parent'=>array(
	
					'supplier_part.purchase_orders.purchase_orders'=>'supplier_part.purchase_orders',
					'supplier_part.purchase_orders.delivery_notes'=>'supplier_part.purchase_orders',
					'supplier_part.purchase_orders.invoices'=>'supplier_part.purchase_orders',
				),



				'tabs'=>array(


					'supplier_part.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'supplier_part.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'supplier_part.part.details'=>array('label'=>_('Part data'), 'icon'=>'database'),

					'supplier_part.history'=>array('label'=>_('History/Notes'), 'icon'=>'history'),
					
					'supplier_part.purchase_orders'=>array('label'=>_('Purchase Orders'), 'icon'=>'clipboard',
						'subtabs'=>array(
							'supplier_part.purchase_orders.purchase_orders'=>array('label'=>_('Purchase Orders')),
							'supplier_part.purchase_orders.delivery_notes'=>array('label'=>_('Delivery Notes')),
							'supplier_part.purchase_orders.invoices'=>array('label'=>_('Invoices')),

						)

					),
					'supplier_part.images'=>array('label'=>_('Images'), 'icon'=>'camera-retro'),
				)
			),

			'supplier_part.new'=>array('type'=>'new_object',
				'tabs'=>array(
					'supplier_part.new'=>array('label'=>_('New part')),

				)

			),


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

				'type'=>'navigation', 'label'=>_('Inventory').' ('._('Parts').')', 'icon'=>'th-large', 'reference'=>'inventory',
				'tabs'=>array(
					'inventory.parts'=>array('label'=>_('Parts')),

				)
			),
			'barcodes'=>array(
				'type'=>'navigation', 'label'=>_('Barcodes'), 'icon'=>'barcode', 'reference'=>'inventory/barcodes',
				'tabs'=>array(
					'inventory.barcodes'=>array('label'=>_('Barcodes'))

				)
			),
			'barcode'=>array(
				'type'=>'object', 
				'tabs'=>array(
					'barcode.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'barcode.assets'=>array('label'=>_('Products/Parts'), 'icon'=>'cube'),

				)
			),
			'categories'=>array('type'=>'navigation', 'label'=>_("Part's Categories"), 'icon'=>'sitemap', 'reference'=>'inventory/categories',

				'tabs'=>array(
					'inventory.categories'=>array('label'=>_("Part's Categories")),
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
					'part.supplier_parts'=>'part.purchase_orders'
				),



				'tabs'=>array(


					'part.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'part.history'=>array('label'=>_('History/Notes'), 'icon'=>'history'),
					'part.sales'=>array('label'=>_('Sales'), 'icon'=>'money',
						'subtabs'=>array(
							'part.sales.overview'=>array('label'=>_('Overview')),
							'part.sales.history'=>array('label'=>_('Sales history')),
							'part.sales.products'=>array('label'=>_('Product sales breakdown')),

						)

					),
					'part.stock'=>array('label'=>_('Stock'), 'icon'=>'th',
						'subtabs'=>array(
							'part.stock.overview'=>array('label'=>_('Overview')),
							'part.stock.transactions'=>array('label'=>_('Transactions history')),
							'part.stock.history'=>array('label'=>_('Stock history')),
							'part.stock.availability'=>array('label'=>_('Availability history')),

						)
					),
					'part.purchase_orders'=>array('label'=>_('Supplier & Purchase orders'), 'icon'=>'ship',
						'subtabs'=>array(
							'part.supplier_parts'=>array('label'=>_("Supplier's parts"),'icon'=>'stop'),

							'part.purchase_orders.purchase_orders'=>array('label'=>_('Purchase orders'),'icon'=>'clipboard'),
							'part.purchase_orders.delivery_notes'=>array('label'=>_("Supplier's delivery notes")),
							'part.purchase_orders.invoices'=>array('label'=>_("Supplier's invoices")),

						)

					),
					'part.products'=>array('label'=>_('Products'), 'icon'=>'cube'),
					'part.images'=>array('label'=>_('Images'), 'icon'=>'camera-retro'),
				)
			),

			'part.new'=>array('type'=>'new_object',
				'tabs'=>array(
					'part.new'=>array('label'=>_('New part')),

				)

			),
			'part.image'=>array('type'=>'object',



				'tabs'=>array(


					'part.image.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'part.image.history'=>array('label'=>_('History/Notes'), 'icon'=>'history'),

				)
			),
			
			'transactions'=>array(
				'type'=>'navigation', 'label'=>_('Stock Movements'), 'icon'=>'exchange', 'reference'=>'inventory/transactions',
				'tabs'=>array(
					'inventory.transactions'=>array('label'=>_('Stock movements'))

				)
			),
			'stock_history'=>array(
				'type'=>'navigation', 'label'=>_('Stock History'), 'icon'=>'history', 'reference'=>'inventory/stock_history',
				'tabs'=>array(
					'inventory.stock_history.dashboard'=>array('label'=>_('Dashboard')),
					'inventory.stock_history.timeline'=>array('label'=>_('Timeline')),

				)
			),

		)
	),
	'warehouses'=>array(
		'sections'=>array(
			'warehouse'=>array(

				'type'=>'navigation', 'label'=>_('Warehouse'), 'title'=>_('Warehouse'), 'icon'=>'th-large', 'reference'=>'warehouse/%d',
				'tabs'=>array(
					'warehouse.details'=>array('label'=>_('Data'), 'title'=>_('Warehouse detais')),
					'warehouse.history'=>array('label'=>_('History/Notes'), 'title'=>_('History/Notes'), 'icon'=>'history'),
					'warehouse.locations'=>array('label'=>_('Locations'), 'title'=>_('Locations')),
					'warehouse.replenishments'=>array('label'=>_("Replenishments"), 'title'=>_("Replenishments")),
					'warehouse.parts'=>array('label'=>_('Part-Locations'), 'title'=>_('Part-Locations')),

				)

			),
			'warehouse.new'=>array('type'=>'new_object',
				'tabs'=>array(
					'warehouse.new'=>array('label'=>_('New warehouse')),

				)

			),
			'location'=>array(

				'type'=>'object', 'label'=>_('Location'), 'icon'=>'map-sings', 'reference'=>'',
				'tabs'=>array(
					'location.details'=>array('label'=>_('Data'), 'title'=>_('Location detais'), 'icon'=>'database'),
					'warehouse.history'=>array('label'=>_('History/Notes'), 'title'=>_('History/Notes'), 'icon'=>'history'),

					'warehouse.parts'=>array('label'=>_('Parts'), 'icon'=>'square-o'),

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
			'billingregion_taxcategory'=>array('type'=>'',
				'tabs'=>array(
					'billingregion_taxcategory'=>array(),

				)

			),
			'billingregion_taxcategory.refunds'=>array('type'=>'',
				'tabs'=>array(
					'billingregion_taxcategory.refunds'=>array(),

				)

			),
			'billingregion_taxcategory.invoices'=>array('type'=>'',
				'tabs'=>array(
					'billingregion_taxcategory.invoices'=>array(),

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


				'subtabs_parent'=>array(
					'employees.uploads'=>'employees.history_uploads',
					'employees.history'=>'employees.history_uploads',


				),

				'tabs'=>array(
					'employees'=>array('label'=>_('Employees')),
					'employees.history_uploads'=>array('label'=>_('History'),
						'subtabs'=>array(
							'employees.history'=>array('label'=>_('History'), 'icon'=>'history'),
							'employees.uploads'=>array('label'=>_('Uploads'), 'icon'=>'upload'),

						)
					),

					'exemployees'=>array('label'=>_('Ex employees'), 'title'=>_('Ex Employees'), 'class'=>'right'),




				)

			),

			'contractors'=>array(
				'type'=>'navigation', 'label'=>_('Contractors'), 'icon'=>'hand-spock-o', 'reference'=>'hr/contractors',
				'tabs'=>array(
					'contractors'=>array('label'=>_('Employees')),

				)


			),
			'overtimes'=>array(
				'type'=>'navigation', 'label'=>_('Overtimes'), 'icon'=>'clock-o', 'reference'=>'hr/overtimes',
				'tabs'=>array(
					'overtimes'=>array('label'=>_('Overtimes')),

				)


			),
			'organization'=>array(
				'type'=>'navigation', 'label'=>_('Organization'), 'title'=>_('Organization'), 'icon'=>'sitemap', 'reference'=>'hr/organization',
				'tabs'=>array(
					'organization.areas'=>array('label'=>_('Working Areas')),
					'organization.departments'=>array('label'=>_('Company departments')),
					'organization.positions'=>array('label'=>_('Job positions')),
					'organization.organigram'=>array('label'=>_('Organizational chart')),


				)
			),
			'employee'=>array('type'=>'object',

				'subtabs_parent'=>array(
					'employee.timesheets'=>'employee.timesheets',
					'employee.timesheets.records'=>'employee.timesheets',


				),

				'tabs'=>array(
					'employee.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'employee.history'=>array('label'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'employee.attachments'=>array('label'=>_('Attachments'), 'icon'=>'paperclip'),
					'employee.timesheets'=>array('label'=>_('Timesheets'),
						'subtabs'=>array(
							'employee.timesheets'=>array('label'=>_('Timesheets')),
							'employee.timesheets.records'=>array('label'=>_('Clockings')),

						)

					),

				)

			),

			'employee.new'=>array('type'=>'new_object',
				'tabs'=>array(
					'employee.new'=>array('label'=>_('New employee')),

				)

			),

			'employee.attachment.new'=>array('type'=>'new_object',
				'tabs'=>array(
					'employee.attachment.new'=>array('label'=>_('New attachment')),

				)

			),
			'employee.user.new'=>array('type'=>'new_object',
				'tabs'=>array(
					'employee.user.new'=>array('label'=>_('New system user')),

				)

			),

			'employee.attachment'=>array('type'=>'object',
				'tabs'=>array(
					'employee.attachment.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'employee.attachment.history'=>array('label'=>_('History'), 'icon'=>'clock-o'),

				)

			),

			'contractor'=>array('type'=>'object',
				'tabs'=>array(
					'contractor.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'contractor.history'=>array('label'=>_('History, Notes'), 'icon'=>'sticky-note-o')

				)

			),

			'contractor.new'=>array('type'=>'new_object',
				'tabs'=>array(
					'contractor.new'=>array('label'=>_('New contractor')),

				)

			),

			'timesheet'=>array('type'=>'object',
				'tabs'=>array(
					'timesheet.records'=>array('label'=>_('Clockings')),

				)

			),

			'timesheets'=>array('type'=>'navigation', 'icon'=>'calendar-o', 'label'=>_('Calendar'), 'reference'=>'timesheets/day/'.date('Ymd'),
				'tabs'=>array(
					'timesheets.months'=>array('label'=>_('Months')),

					'timesheets.weeks'=>array('label'=>_('Weeks')),
					'timesheets.days'=>array('label'=>_('Days')),
					'timesheets.employees'=>array('label'=>_("Employes'")),

					'timesheets.timesheets'=>array('label'=>_('Timesheets')),

				)

			),


			'new_timesheet_record'=>array(
				'type'=>'new', 'label'=>_('New timesheet record'), 'title'=>_('New timesheet record'), 'icon'=>'clock', 'reference'=>'hr/new_timesheet_record',
				'tabs'=>array(
					'timesheet_record.new'=>array('label'=>_('New timesheet record'), 'title'=>_('New timesheet record')),
					'timesheet_record.import'=>array('label'=>_('Import'), 'title'=>_('Import timesheet record')),
					'timesheet_record.api'=>array('label'=>_('API'), 'title'=>_('API')),
					'timesheet_record.cancel'=>array('class'=>'right', 'label'=>_('Cancel'), 'title'=>_('Cancel'), 'icon'=>'sign-out fa-flip-horizontal'),

				)

			),
			'uploads'=>array('type'=>'',
				'tabs'=>array(
					'uploads'=>array('label'=>_('Uploads')),

				)

			),
			'upload'=>array('type'=>'object',
				'tabs'=>array(
					'upload.employees'=>array('label'=>_('Upload Records')),

				)

			),

		)
	),
	'profile'=>array(


		'sections'=>array(
			'profile'=>array('type'=>'object', 'label'=>'', 'title'=>'', 'icon'=>'', 'reference'=>'',
				'tabs'=>array(
					'profile.details'=>array('label'=>_('Data'), 'title'=>_('My details')),
					'profile.history'=>array('label'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'profile.login_history'=>array('label'=>_('Login history')),
				)
			),

		)

	),
	'account'=>array(


		'sections'=>array(

			'setup'=>array('type'=>'',
				'tabs'=>array(
					'account.setup'=>array('label'=>_('Account set up')),
				)
			),
			'setup_error'=>array('type'=>'',
				'tabs'=>array(
					'account.setup.error'=>array('label'=>''),
				)
			),
			'setup_root_user'=>array('type'=>'',
				'tabs'=>array(
					'account.setup.root_user'=>array('label'=>''),
				)
			),
			'setup_add_employees'=>array('type'=>'',
				'tabs'=>array(
					'account.setup.add_employees'=>array('label'=>''),
				)
			),
			'setup_add_employee'=>array('type'=>'',
				'tabs'=>array(
					'account.setup.add_employee'=>array('label'=>''),
				)
			),
			'setup_add_warehouse'=>array('type'=>'',
				'tabs'=>array(
					'account.setup.setup_add_warehouse'=>array('label'=>''),
				)
			),
			'setup_add_store'=>array('type'=>'',
				'tabs'=>array(
					'account.setup.add_store'=>array('label'=>''),
				)
			),

			'account'=>array('type'=>'navigation', 'label'=>_('Account'), 'icon'=>'star', 'reference'=>'account', 'showcase'=>true,
				'tabs'=>array(
					'account.details'=>array('label'=>_('Data'), 'title'=>_('Account details')),
				)
			),

			'users'=>array('type'=>'navigation', 'label'=>_('Users'), 'icon'=>'male', 'reference'=>'account/users',
				'tabs'=>array(
					'account.users'=>array('label'=>_('Users')),
				)
			),
			'orders_index'=>array('type'=>'', 'label'=>_("Order's Index"), 'icon'=>'bars', 'reference'=>'account/orders',
				'tabs'=>array(
					'orders_index'=>array('label'=>_("Order's Index")),
				)
			),
			'data_sets'=>array('type'=>'navigation', 'label'=>_('Data sets'), 'icon'=>'align-left', 'reference'=>'account/data_sets',
				'tabs'=>array(
					'data_sets'=>array('label'=>_('Data sets')),
				)
			),
			'isf'=>array('type'=>'',
				'tabs'=>array(
					'data_sets.isf'=>array('label'=>_('Order transactions timeseries')),
				)
			),
			'osf'=>array('type'=>'',
				'tabs'=>array(
					'data_sets.osf'=>array('label'=>_('Inventory transactions timeseries')),
				)
			),
			'images'=>array('type'=>'',
				'tabs'=>array(
					'data_sets.images'=>array('label'=>_('Images')),
				)
			),
			'attachments'=>array('type'=>'',
				'tabs'=>array(
					'data_sets.attachments'=>array('label'=>_('Attachments')),
				)
			),
			'timeseries'=>array('type'=>'',
				'tabs'=>array(
					'timeseries'=>array('label'=>_('Timeseries')),
				)
			),



			'timeserie'=>array('type'=>'',

				'subtabs_parent'=>array(
					'timeserie.records.annually'=>'timeserie.records',
					'timeserie.records.monthy'=>'timeserie.records',
					'timeserie.records.weekly'=>'timeserie.records',
					'timeserie.records.daily'=>'timeserie.records',

				),

				'tabs'=>array(
					'timeserie.plot'=>array('label'=>_('Plot')),
					'timeserie.records'=>array('label'=>_('Records'),
						'subtabs'=>array(
							'timeserie.records.annually'=>array('label'=>_('Annually')),
							'timeserie.records.monthy'=>array('label'=>_('Monthy')),
							'timeserie.records.weekly'=>array('label'=>_('Weekly')),
							'timeserie.records.daily'=>array('label'=>_('Daily')),


						)


					),

				)
			),
			'settings'=>array('type'=>'navigation', 'label'=>_('Settings'), 'icon'=>'cog', 'reference'=>'account/settings',
				'tabs'=>array(
					'account.settings'=>array('label'=>_('Settings')),
				)
			),



			'staff'=>array('type'=>'object', 'label'=>_('Staff'), 'title'=>_("Staff users"), 'icon'=>'hand-rock-o', 'reference'=>'users',
				'tabs'=>array(
					'users.staff.users'=>array('label'=>_('Users')),
					'users.staff.groups'=>array('label'=>_("Groups")),
					'users.staff.login_history'=>array('label'=>_('Login History')),

				)

			),

			'suppliers'=>array(
				'type'=>'object', 'label'=>_('Suppliers'), 'title'=>_('Suppliers users'), 'icon'=>'ship', 'reference'=>'users/suppliers',
			),
			'warehouse'=>array(
				'type'=>'object', 'label'=>_('Warehouse'), 'title'=>_('Warehouse users'), 'icon'=>'th-large', 'reference'=>'users/warehouse',
			),
			'root'=>array(
				'type'=>'object', 'label'=>'Root', 'title'=>_('Root user'), 'icon'=>'dot-circle-o', 'reference'=>'suppliers',
			),
			'staff.user'=>array('type'=>'object',
				'tabs'=>array(
					'staff.user.details'=>array('label'=>_('Data'), 'icon'=>'database', 'title'=>_('Details')),
					'staff.user.history'=>array('label'=>_('History, Notes'), 'icon'=>'sticky-note-o'),
					'staff.user.login_history'=>array('label'=>_('Login history'), 'title'=>_('Login history')),
					'staff.user.api_keys'=>array('label'=>_('API keys'), 'title'=>_('API keys')),

				)
			),
			'staff.user.api_key.new'=>array('type'=>'new_object',
				'tabs'=>array(
					'staff.user.api_key.new'=>array('label'=>_('New API')),

				)
			),
			'staff.user.api_key'=>array('type'=>'new_object',
				'tabs'=>array(
					'staff.user.api_key.details'=>array('label'=>_('Data'), 'icon'=>'database'),
					'staff.user.api_key.requests'=>array('label'=>_('Requests'), 'icon'=>'arrow-circle-right'),

				)
			),





		)

	),
	'utils'=>array(
		'sections'=>array(
			'forbidden'=>array('type'=>'object', 'label'=>_('Forbidden'), 'title'=>_('Forbidden'),  'id'=>'forbidden',
				'tabs'=>array(
					'forbidden'=>array()
				)
			),
			'not_found'=>array('type'=>'object', 'label'=>_('Not found'), 'title'=>_('Not found'), 'id'=>'not_found',

				'tabs'=>array(
					'not_found'=>array(),
				)
			),


			'fire'=>array('type'=>'object', 'label'=>_('Fire'), 'icon'=>'file-o', 'id'=>'not_found',

				'tabs'=>array(
					'fire'=>array(),
				)
			),

		)
	),
	'help'=>array(
		'sections'=>array(
			'help'=>array('type'=>'object', 'label'=>_('Help'), 'icon'=>'shopping-cart', 'id'=>'forbidden',
				'tabs'=>array(
					'help'=>array()
				)
			)
		),
		'about'=>array(
			'about'=>array('type'=>'object', 'label'=>_('About'), 'icon'=>'',
				'tabs'=>array(
					'about'=>array()
				)
			)
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
