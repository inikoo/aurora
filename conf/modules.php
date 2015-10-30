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
					'dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard'), 'reference'=>'home'),

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
					'contacts'=>array('label'=>_('Contacts'), 'title'=>_('Contacts'), 'reference'=>'customers/statistics/contacts'),
					'customers'=>array('label'=>_('Customers'), 'title'=>_('Customers'), 'reference'=>'customers/statistics/customers'),
					'orders'=>array('label'=>_('Orders'), 'title'=>_('Orders'), 'reference'=>'customers/statistics/orders'),
					'data_integrity'=>array('label'=>_('Data Integrity'), 'title'=>_('Data Integrity'), 'reference'=>'customers/statistics/data_integrity'),
					'geo'=>array('label'=>_('Geographic Distribution'), 'title'=>_('Geographic Distribution'), 'reference'=>'customers/statistics/geo'),
					'correlations'=>array('label'=>_('Correlations'), 'title'=>_('Correlations'), 'reference'=>'customers/statistics/correlations'),

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
					'category.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details'), 'reference'=>'category/%d/details'),
					'category.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o', 'category'=>'customer/%d/history'),
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
					'customer.marketing.overview'=>'customer.marketing',
					'customer.marketing.families'=>'customer.marketing',
					'customer.marketing.products'=>'customer.marketing',
					'customer.marketing.favourites'=>'customer.marketing',
					'customer.marketing.search'=>'customer.marketing',

				),
				'tabs'=>array(
					'customer.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details'), 'reference'=>'customer/%d/details'),
					'customer.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o', 'reference'=>'customer/%d/notes'),
					'customer.orders'=>array('label'=>_('Orders'), 'title'=>_('Orders'), 'reference'=>'customer/%d/orders'),
					'customer.marketing'=>array('label'=>_('Interests'), 'title'=>_("Customer's interests"), 'reference'=>'customer/%d/marketing',
						'subtabs'=>array(
							'customer.marketing.overview'=>array('label'=>_('Overview'), 'title'=>_('Overview'), 'reference'=>'customer/%d/marketing/overview'),
							'customer.marketing.products'=>array('label'=>_('Products ordered'), 'title'=>_('Products ordered'), 'reference'=>'customer/%d/marketing/products'),
							'customer.marketing.families'=>array('label'=>_('Families ordered'), 'title'=>_('Families ordered'), 'reference'=>'customer/%d/marketing/families'),
							'customer.marketing.favourites'=>array('label'=>_('Favourite products'), 'title'=>_('Favourites products'), 'reference'=>'customer/%d/marketing/favourites'),
							'customer.marketing.search'=>array('label'=>_('Search queries'), 'title'=>_('earch querie'), 'reference'=>'customer/%d/marketing/favorites'),

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


					'order.items'=>array('label'=>_('Items'), 'title'=>_('Items'), 'icon'=>'bars', 'reference'=>'order/%d/items'),
					'order.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database', 'reference'=>'order/%d/details'),
					'order.history'=>array('label'=>_('History'), 'title'=>_('History'), 'icon'=>'history', 'reference'=>'order/%d/history'),
					'order.delivery_notes'=>array('label'=>_('Delivery notes'), 'title'=>_('Delivery notes'), 'icon'=>'truck', 'reference'=>'order/%d/delivery_notes'),
					'order.invoices'=>array('label'=>_('Invoices'), 'title'=>_('Invoices'), 'icon'=>'usd', 'reference'=>'order/%d/invoices'),

				)

			),
			'invoice'=>array('type'=>'object',
				'tabs'=>array(


					'invoice.items'=>array('label'=>_('Items'), 'title'=>_('Items'), 'icon'=>'bars', 'reference'=>'invoice/%d/items'),
					'invoice.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database', 'invoice'=>'website/%d/details'),
					'invoice.history'=>array('label'=>_('History'), 'title'=>_('History'), 'icon'=>'history', 'invoice'=>'website/%d/history'),
					'invoice.orders'=>array('label'=>_('Orders'), 'title'=>_('Orders'), 'icon'=>'usd', 'reference'=>'invoice/%d/orders'),
					'invoice.delivery_notes'=>array('label'=>_('Delivery notes'), 'title'=>_('Delivery notes'), 'icon'=>'truck', 'reference'=>'invoice/%d/delivery_notes'),
				)

			),
			'delivery_note'=>array('type'=>'object',
				'tabs'=>array(


					'delivery_note.items'=>array('label'=>_('Items'), 'title'=>_('Items'), 'icon'=>'bars', 'reference'=>'delivery_note/%d/items'),
					'delivery_note.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database', 'reference'=>'delivery_note/%d/details'),
					'delivery_note.history'=>array('label'=>_('History'), 'title'=>_('History'), 'icon'=>'history', 'reference'=>'delivery_note/%d/history'),
					'delivery_note.orders'=>array('label'=>_('Orders'), 'title'=>_('Orders'), 'icon'=>'usd', 'reference'=>'delivery_note/%d/orders'),
					'delivery_note.invoices'=>array('label'=>_('Invoices'), 'title'=>_('Invoices'), 'icon'=>'usd', 'reference'=>'delivery_note/%d/invoices'),
				)

			),
			'pick_aid'=>array('type'=>'object',
				'tabs'=>array(


					'pick_aid.items'=>array('label'=>_('Items'), 'title'=>_('Items'), 'icon'=>'bars', 'reference'=>'pick_aid/%d/items'),
				)

			),'pack_aid'=>array('type'=>'object',
				'tabs'=>array(


					'pack_aid.items'=>array('label'=>_('Items'), 'title'=>_('Items'), 'icon'=>'bars', 'reference'=>'pack_aid/%d/items'),
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


					'website.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Website dashboard'), 'icon'=>'dashboard', 'reference'=>'website/%d/dashboard'),
					'website.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database', 'reference'=>'website/%d/details'),

					'website.pages'=>array('label'=>_('Pages'), 'title'=>_('Pages'), 'icon'=>'files-o', 'reference'=>'website/%d/pages'),
					'website.pageviews'=>array('label'=>_('Pageviews'), 'title'=>_('Pageviews'), 'icon'=>'eye', 'reference'=>'website/%d/pageviews'),
					'website.users'=>array('label'=>_('Users'), 'title'=>_('Users'), 'icon'=>'male', 'reference'=>'website/%d/users'),
					'website.search'=>array('label'=>_('Queries'), 'title'=>_('Search Queries'), 'icon'=>'search', 'reference'=>'website/%d/search',
						'subtabs'=>array(
							'website.search.queries'=>array('label'=>_('Queries'), 'title'=>_('Search queries goruped by keywords'), 'reference'=>'website/%d/search/queries'),
							'website.search.history'=>array('label'=>_('Search History'), 'title'=>_('List of all search queries'), 'reference'=>'website/%d/search/history'),

						)

					),
					'website.favourites'=>array('label'=>_('Favourites'), 'title'=>_('Favourites'), 'icon'=>'heart-o', 'reference'=>'website/%d/favourites',
						'subtabs'=>array(
							'website.favourites.families'=>array('label'=>_('Families'), 'title'=>_('Families'), 'reference'=>'website/%d/favourites/families'),
							'website.favourites.products'=>array('label'=>_('Products'), 'title'=>_('Products'), 'reference'=>'website/%d/favourites/products'),
							'website.favourites.customers'=>array('label'=>_('Customers'), 'title'=>_('Customers'), 'reference'=>'website/%d/favourites/customers'),

						)

					),
					'website.reminders'=>array('label'=>_('OOS Reminders'), 'title'=>_('Out of stock reminders'), 'icon'=>'hand-paper-o', 'reference'=>'website/%d/reminders',
						'subtabs'=>array(
							'website.reminders.requests'=>array('label'=>_('Requests'), 'title'=>_('Out of stock notifications requests'), 'reference'=>'website/%d/reminders/requests'),
							'website.reminders.customers'=>array('label'=>_('Customers'), 'title'=>_('Customers who ask for a out of stock notification'), 'reference'=>'website/%d/reminders/customers'),
							'website.reminders.families'=>array('label'=>_('Families'), 'title'=>_('Out of stock notifications grouped ny famiy'), 'reference'=>'website/%d/reminders/families'),
							'website.reminders.products'=>array('label'=>_('Products'), 'title'=>_('Out of stock notifications grouped ny product'), 'reference'=>'website/%d/reminders/products'),

						)

					),

				)
			),
			'page'=>array('type'=>'object',
				'tabs'=>array(


					'page.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Page dashboard'), 'icon'=>'dashboard', 'reference'=>'page/%d/dashboard'),
					'page.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database', 'reference'=>'page/%d/details'),
					'page.pageviews'=>array('label'=>_('Pageviews'), 'title'=>_('Pageviews'), 'icon'=>'eye', 'reference'=>'page/%d/pageviews'),
					'page.users'=>array('label'=>_('Users'), 'title'=>_('Users'), 'icon'=>'male', 'reference'=>'page/%d/users'),
				)
			),
			'website.user'=>array('type'=>'object',
				'tabs'=>array(
					'website.user.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details'), 'reference'=>'website.user/%d/details'),
					'website.user.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o',  'reference'=>'website.user/%d/history'),
					'website.user.login_history'=>array('label'=>_('Sessions'), 'title'=>_('Login history') , 'icon'=>'login', 'reference'=>'website.user/%d/login_history'),
					'website.user.pageviews'=>array('label'=>_('Pageviews'), 'title'=>_('Pageviews') , 'icon'=>'eye', 'reference'=>'website.user/%d/pageviews'),

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
					'store.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard'), 'reference'=>'store/%d/store'),
					'store.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details'), 'reference'=>'store/%d/details'),
					'store.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o', 'reference'=>'store/%d/notes'),
					'store.departments'=>array('label'=>_('Departments'), 'title'=>_('Departments'), 'reference'=>'store/%d/departments'),
					'store.families'=>array('label'=>_('Families'), 'title'=>_('Families'), 'reference'=>'store/%d/families'),
					'store.products'=>array('label'=>_('Products'), 'title'=>_('Products'), 'reference'=>'store/%d/products'),

				)
			),
			'department'=>array(
				'type'=>'object',

				'tabs'=>array(
					'department.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard'), 'reference'=>'department/%d/dashboard'),
					'department.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database', 'reference'=>'department/%d/details'),
					'department.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o', 'reference'=>'department/%d/notes'),
					'department.families'=>array('label'=>_('Families'), 'title'=>_('Families'), 'reference'=>'dashboard/%d/families'),
					'department.products'=>array('label'=>_('Products'), 'title'=>_('Products'), 'reference'=>'dashboard/%d/products'),

				)
			),
			'family'=>array(
				'type'=>'object',

				'tabs'=>array(
					'family.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard'), 'reference'=>'family/%d/dashboard'),
					'family.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database', 'reference'=>'department/%d/details'),
					'family.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o',  'reference'=>'department/%d/notes'),
					'family.products'=>array('label'=>_('Products'), 'title'=>_('Products'), 'reference'=>'dashboard/%d/products'),

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
					'product.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard'), 'reference'=>'product/%d/dashboard'),
					'product.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details'), 'reference'=>'product/%d/details'),
					'product.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o', 'reference'=>'product/%d/notes'),
					'product.sales'=>array('label'=>_('Sales'), 'title'=>_('Sales'), 'reference'=>'product/%d/sales',
						'subtabs'=>array(
							'product.sales.overview'=>array('label'=>_('Overview'), 'title'=>_('Overview'), 'reference'=>'product/%d/sales/overview'),
							'product.sales.history'=>array('label'=>_('Sales history'), 'title'=>_('Sales history'), 'reference'=>'product/%d/sales/history'),
							'product.sales.calendar'=>array('label'=>_('Calendar'), 'title'=>_('Sales calendar'), 'reference'=>'product/%d/sales/calendar'),

						)
					),
					'product.orders'=>array('label'=>_('Orders'), 'title'=>_('Orders'), 'reference'=>'product/%d/orders',

					),
					'product.customers'=>array('label'=>_('Customers'), 'title'=>_('Customers'), 'reference'=>'product/%d/sales',
						'subtabs'=>array(
							'product.customers.customers'=>array('label'=>_('Customers'), 'title'=>_('Customers'), 'reference'=>'product/%d/customers/customers'),
							'product.customers.favourites'=>array('label'=>_('Customers who favorited'), 'title'=>_('Customers who favorited'), 'reference'=>'product/%d/customers/favourites'),

						)
					),
					'product.offers'=>array('label'=>_('Offers'), 'title'=>_('Offers'), 'reference'=>'product/%d/offers'),

					'product.website'=>array('label'=>_('Website'), 'title'=>_('Website'), 'reference'=>'product/%d/website',
						'subtabs'=>array(
							'product.website.webpage'=>array('label'=>_('Webpage'), 'title'=>_('Product webpage'), 'reference'=>'product/%d/website/webpage'),
							'product.sales.pages'=>array('label'=>_('Webpages'), 'title'=>_('Webpages where this product is on sale'), 'reference'=>'product/%d/website/pages'),

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
					'campaigns'=>array('label'=>_('Campaigns'), 'title'=>_('Campaigns'), 'reference'=>'marketing/%d/campaigns'),
					'offers'=>array('label'=>_('Offers'), 'title'=>_('Offers'), 'reference'=>'marketing/%d/offers'),

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
					'category.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details'), 'reference'=>'category/%d/details'),
					'category.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o', 'category'=>'supplier/%d/history'),
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
					'supplier.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details'), 'reference'=>'supplier/%d/details'),
					'supplier.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o', 'reference'=>'supplier/%d/notes'),
					'supplier.orders'=>array('label'=>_('Orders'), 'title'=>_('Orders'), 'reference'=>'supplier/%d/orders'),

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
					'inventory.parts'=>array('label'=>_('Parts'), 'title'=>_('Parts'), 'reference'=>'parts/%d'),
					'inventory.families'=>array('label'=>_("Part's Families"), 'title'=>_("Part's families"), 'reference'=>'inventory/%d/families'),

				)
			),
			'categories'=>array('type'=>'navigation', 'label'=>_("Part's Categories"), 'title'=>_("Part's Categories"), 'icon'=>'sitemap', 'reference'=>'inventory/categories',

				'tabs'=>array(
					'inventory.categories'=>array('label'=>_("Part's Categories"), 'title'=>_("Part's Categories"), 'reference'=>'inventory/categories'),
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


					'part.details'=>array('label'=>_('Details'), 'title'=>_('Details'), 'icon'=>'database', 'reference'=>'part/%d/details'),
					'part.history'=>array('label'=>_('History/Notes'), 'title'=>_('History/Notes'), 'icon'=>'history', 'reference'=>'part/%d/history'),
					'part.sales'=>array('label'=>_('Sales'), 'title'=>_('Sales'), 'icon'=>'money', 'reference'=>'part/%d/sales',
						'subtabs'=>array(
							'part.sales.overview'=>array('label'=>_('Overview'), 'title'=>_('Overview'), 'reference'=>'part/%d/sales/overview'),
							'part.sales.history'=>array('label'=>_('Sales history'), 'title'=>_('Sales history'), 'reference'=>'part/%d/sales/history'),
							'part.sales.products'=>array('label'=>_('Product sales breakdown'), 'title'=>_('Product sales breakdown'), 'reference'=>'part/%d/sales/products'),

						)

					),
					'part.stock'=>array('label'=>_('Stock'), 'title'=>_('Stock'), 'icon'=>'th', 'reference'=>'part/%d/stock',
						'subtabs'=>array(
							'part.stock.overview'=>array('label'=>_('Overview'), 'title'=>_('Overview'), 'reference'=>'part/%d/stock/overview'),
							'part.stock.transactions'=>array('label'=>_('Transactions history'), 'title'=>_('Transactions history'), 'reference'=>'part/%d/stock/transactions'),
							'part.stock.history'=>array('label'=>_('Stock history'), 'title'=>_('Stock history'), 'reference'=>'part/%d/stock/history'),
							'part.stock.availability'=>array('label'=>_('Availability history'), 'title'=>_('Availability history'), 'reference'=>'part/%d/stock/availability'),

						)
					),
					'part.purchase_orders'=>array('label'=>_('Purchase Orders'), 'title'=>_('Purchase Orders'), 'icon'=>'ship', 'reference'=>'part/%d/purchase_orders',
						'subtabs'=>array(
							'part.purchase_orders.purchase_orders'=>array('label'=>_('Purchase Orders'), 'title'=>_('Purchase Orders'), 'reference'=>'part/%d/purchase_orders/purchase_orders'),
							'part.purchase_orders.delivery_notes'=>array('label'=>_('Delivery Notes'), 'title'=>_("Supplier's delivery notes"), 'reference'=>'part/%d/purchase_orders/delivery_notes'),
							'part.purchase_orders.invoices'=>array('label'=>_('Invoices'), 'title'=>_("Supplier's invoices"), 'reference'=>'part/%d/purchase_orders/invoices'),

						)

					),
					'part.products'=>array('label'=>_('Products'), 'title'=>_('Products'), 'icon'=>'square', 'reference'=>'part/%d/products'),
				)
			),
			'transactions'=>array(
				'type'=>'navigation', 'label'=>_('Stock Movements'), 'title'=>_('Stock movements'), 'icon'=>'exchange', 'reference'=>'inventory/transactions',
				'tabs'=>array(
					'inventory.transactions'=>array('label'=>_('Stock movements'), 'title'=>_('Stock movements'), 'reference'=>'locations/transactions'),

				)
			),
			'stock_history'=>array(
				'type'=>'navigation', 'label'=>_('Stock History'), 'title'=>_('Stock History'), 'icon'=>'history', 'reference'=>'inventory/stock_history',
				'tabs'=>array(
					'inventory.stock_history.dashboard'=>array('label'=>_('Dashboard'), 'title'=>_('Dashboard'), 'reference'=>'inventory/stock_history/dashboard'),
					'inventory.stock_history.timeline'=>array('label'=>_('Timeline'), 'title'=>_('Timeline'), 'reference'=>'inventory/stock_history/timeline'),

				)
			),

		)
	),
	'warehouses'=>array(
		'sections'=>array(
			'warehouse'=>array(

				'type'=>'navigation', 'label'=>_('Warehouse'), 'title'=>_('Warehouse'), 'icon'=>'th-large', 'reference'=>'warehouse/%d',
				'tabs'=>array(
					'warehouse.details'=>array('label'=>_('Details'), 'title'=>_('Warehouse detais'), 'reference'=>'warehouse/%d/details'),
					'warehouse.history'=>array('label'=>_('History/Notes'), 'title'=>_('History/Notes'), 'icon'=>'history', 'reference'=>'warehouse/%d/history'),
					'warehouse.locations'=>array('label'=>_('Locations'), 'title'=>_('Locations'), 'reference'=>'locations/%d'),
					'warehouse.replenishments'=>array('label'=>_("Replenishments"), 'title'=>_("Replenishments"), 'reference'=>'locations/%d/replenishments'),
					'warehouse.parts'=>array('label'=>_('Part-Locations'), 'title'=>_('Part-Locations'), 'reference'=>'locations/%d/parts'),

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
					'employee.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details'), 'reference'=>'employee/%d/details'),
					'employee.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o', 'reference'=>'employee/%d/notes'),
					'employee.timesheet'=>array('label'=>_('Timesheet'), 'title'=>_('Timesheet'), 'reference'=>'employee/%d/timesheet'),

				)

			),

		)
	),

	'profile'=>array(


		'sections'=>array(
			'profile'=>array('type'=>'object', 'label'=>'', 'title'=>'', 'icon'=>'', 'reference'=>'',
				'tabs'=>array(
					'profile.details'=>array('label'=>_('Details'), 'title'=>_('My details'), 'reference'=>'profile/details'),
					'profile.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o', 'reference'=>'staff.user/%d/history'),
					'profile.login_history'=>array('label'=>_('Login history'), 'title'=>_('Login history'), 'reference'=>'staff.user/%d/login_history'),
				)
			),

		)

	),

	'account'=>array(


		'sections'=>array(
			'account'=>array('type'=>'navigation', 'label'=>_('Account'), 'title'=>_('Account'), 'icon'=>'star', 'reference'=>'account',
				'tabs'=>array(
					'account.details'=>array('label'=>_('Details'), 'title'=>_('Account details'), 'reference'=>'account/details'),
					'account.payment_options'=>array('label'=>_('Details'), 'title'=>_('Account details'), 'reference'=>'account/details'),
				)
			),

			'users'=>array('type'=>'navigation', 'label'=>_('Users'), 'title'=>('Users'), 'icon'=>'male', 'reference'=>'account/users',
				'tabs'=>array(
					'account.users'=>array('label'=>_('Details'), 'title'=>_('Account details'), 'reference'=>'account/users'),
				)
			),
			'settings'=>array('type'=>'navigation', 'label'=>_('Settings'), 'title'=>('Settings'), 'icon'=>'cog', 'reference'=>'account/settings',
				'tabs'=>array(
					'account.settings'=>array('label'=>_('Settings'), 'title'=>_('Settings'), 'reference'=>'account/settings'),
				)
			),
			'staff'=>array('type'=>'object', 'label'=>_('Staff'), 'title'=>_("Staff users"), 'icon'=>'hand-rock-o', 'reference'=>'users',
				'tabs'=>array(
					'users.staff.users'=>array('label'=>_('Users'), 'title'=>_('Users'), 'reference'=>'users'),
					'users.staff.groups'=>array('label'=>_("Groups"), 'title'=>_("Groups"), 'reference'=>'users/'),
					'users.staff.login_history'=>array('label'=>_('Login History'), 'title'=>_('Login History'), 'reference'=>'locations/%d/parts'),

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
					'staff.user.details'=>array('label'=>_('Details'), 'icon'=>'database', 'title'=>_('Details'), 'reference'=>'staff.user/%d/details'),
					'staff.user.history'=>array('label'=>_('History, Notes'), 'title'=>_('History, Notes'), 'icon'=>'sticky-note-o', 'reference'=>'staff.user/%d/history'),
					'staff.user.login_history'=>array('label'=>_('Login history'), 'title'=>_('Login history'), 'reference'=>'staff.user/%d/login_history'),

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
