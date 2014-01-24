<?php

$report_data=array('ES1'=>array('umbral'=>3000,'year'=>date('Y')-1));


$myconf=array(
	'splinters'=>array('top_customers','top_products','messages','sales','out_of_stock'),
	'tax_rates'=>array(),
	'data_from'=>"2003-06-01 09:00:00",
	'order_id_type'=>'Order Header Numeric ID',
	'customer_min_number_zeros_id'=>4,
	'contact_min_number_zeros_id'=>4,
	'company_min_number_zeros_id'=>4,
	'supplier_min_number_zeros_id'=>3,
	'staff_min_number_zeros_id'=>3,

	'max_session_time'=>36000,
	'name'=>'AW',
	'sname'=>'AW',
	'country'=>'UK',
	'country_code'=>'GBR',
	'country_2acode'=>'GB',
	'lang'=>'en',
	'country_id'=>30,
	'home_id'=>30,
	'extended_home_id'=>array(30,241,240,242),
	'extended_home_2acode'=>array('GB','GG','JE','IM'),
	'region_id'=>array(75,30,241,240,242),
	'region_2acode'=>array('GB','GG','JE','IM'),
	'org_id'=>array(80,21,33,208,108,201,228,193,165,177,104,216,75,78,110,116,117,126,2,162,160,169,188,189,47,171,30),
	'org_2acode'=>array('NL','BE','GB','BG','ES','IE','IT','AT','GR','CY','LV','LT','LU','MT','PT','PL','FR','RO','SE','DE','SK','SI','FI','DK','CZ','HU','EE'),
	'tax_obligatory'=>array('GB'),

	'tax_conditional0'=>array(80,21,33,208,108,201,228,193,165,177,104,216,75,78,110,116,117,126,2,162,160,169,188,189,47,171),
	'tax_conditional0_2acode'=>array('NL','BE','BG','ES','IE','IT','AT','GR','CY','LV','LT','LU','MT','PT','PL','FR','RO','SE','DE','SK','SI','FI','DK','CZ','HU','EE'),
	'continent_id'=>array(228,110,116,242,241,240,30,75,33,188,135,169,208,215,216,226,162,221,70,171,193,149,53,76,201,181,243,189,160,78,47,86,104,105,27,121,126,7,224,4,58,21,136,2,80,117,177,115,165,196),
	'continent_2acode'=>array('NL','AL','AD','BE','BA','GB','BG','ES','FO','GI','SJ','IE','IS','IT','AT','YU','GR','HR','LV','LI','LT','LU','MK','MT','MD','MC','NO','PT','PL','FR','RO','SE','DE','SM','SK','SI','FI','CH','DK','CZ','UA','HU','BY','VA','RU','EE','GG','JE','IM'),

	'home'=>'United Kingdom',
	'_home'=>'UK',

	's_extended_home'=>'UK,CI&IM',
	'extended_home'=>'United Kigndom, Channel Islands & Isle of Man',


	's_extended_home_nohome'=>'CI&IM',
	'extended_home_nohome'=>'Channel Islands & Isle of Man',
	'region'=>'British Isles',
	'org'=>'European Union',
	's_org'=>'EU',
	'continent'=>'Europe',
	'outside'=>'Ouside Europe',
	's_outside'=>'Rest',
	'encoding'=>'UTF-8',
	'currency_symbol'=>'£',

	'currency_code'=>'GBP',
	'currency'=>'Pound',
	'decimal_point'=>'.',
	'thousand_sep'=>',',

	'theme'=>'yui-skin-sam',
	'template_dir'=>'templates',
	'compile_dir'=> 'server_files/smarty/templates_c',
	'cache_dir' => 'server_files/smarty/cache',
	'config_dir' => 'server_files/smarty/configs',
	'images_dir' => 'server_files/images/',
	'yui_version'=>'2.9',
	'staff_prefix'=>'SF',
	'supplier_id_prefix'=>'S',
	'po_id_prefix'=>'PO',
	'invoice_id_prefix'=>'I',

	'customer_id_prefix'=>'C',
	'contact_id_prefix'=>'p',
	'company_id_prefix'=>'B',
	'dn_id_prefix'=>'NE',
	'order_id_prefix'=>'',
	'data_since'=>'14-06-2004',
	'product_code_separator'=>'-',
	'unknown_company'=>'Unknown Company',
	'unknown_contact'=>'Unknown Contact',
	'unknown_customer'=>'Unknown Customer',
	'unknown_supplier'=>'Unknown Supplier',
	'unknown_informal_greting'=>'Hello',
	'unknown_formal_greting'=>'Dear Sir or Madam'
);




$default_state=array(

	'home'=>array(
		'display'=>'sales',
		'splinters'=>array(
			'top_products'=>array('type'=>'products','nr'=>20,'period'=>'ytd','order'=>'net_sales','order_dir'=>'desc','f_field'=>'code','f_value'=>''),
			'top_customers'=>array('nr'=>20,'period'=>'ytd','order'=>'net_balance','order_dir'=>'desc','f_field'=>'name','f_value'=>''),
			'sales'=>array('type'=>'invoice_categories','period'=>'ytd','currency'=>'corporate'),
			'out_of_stock'=>array('period'=>'1m','from'=>'','to'=>''),

			'orders_in_process'=>array(
				'store_keys'=>'all',
				'sf'=>0,
				'nr'=>50,
				'f_value'=>'',
				'f_show'=>false,
				'f_field'=>'customer',
				'from'=>'',
				'to'=>'',
				'order'=>'date',
				'order_dir'=>'',
				'where'=>''
			),
			'messages'=>array()
		)
	),

	'customers'=>array(
		'store'=>false,

		'block_view'=>'contacts',
		'stats_view'=>'population',
		'pending_orders'=>array(
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Packed'=>1,'InWarehouse'=>1,'SubmittedbyCustomer'=>1,'InProcess'=>1,'InProcessbyCustomer'=>1),

		),
		'correlations'=>array(
			'order'=>'correlation',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'where'=>'',
			'f_field'=>'name_a',
			'f_value'=>''),
		'users'=>array(
			'display'=>'all',
			'order'=>'last_request',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,

			'f_field'=>'customer_name',
			'f_value'=>''
		),
		'customers'=>array(
			'order'=>'id',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'view'=>'general',
			'orders_type'=>'all_contacts',
			'elements_type'=>'activity',
			'elements'=>array(
				'activity'=>array('Active'=>true,'Losing'=>true,'Lost'=>true),
				'level_type'=>array('Normal'=>true,'VIP'=>true,'Partner'=>true,'Staff'=>true),
				'location'=>array('Domestic'=>true,'Export'=>true)
			),
			'where'=>'',
			'f_field'=>'customer name',
			'f_value'=>''


		),
		'edit_customers'=>array(
			'order'=>'id',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'view'=>'general',
			'orders_type'=>'all_contacts',
			'elements_type'=>'activity',
			'elements'=>array(
				'activity'=>array('Active'=>true,'Losing'=>true,'Lost'=>true),
				'level_type'=>array('Normal'=>true,'VIP'=>true,'Partner'=>true,'Staff'=>true),
				'location'=>array('Domestic'=>true,'Export'=>true)
			),
			'where'=>'',
			'f_field'=>'customer name',
			'f_value'=>''


		),
		'advanced_search'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'',
			'f_field'=>'',
			'f_value'=>'',
			'view'=>'general'
		),
		'list'=>array(
			'block_view'=>'user_created',
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'',
			'f_field'=>'name',
			'f_value'=>'',
			'view'=>'general'
		),
		'imported_records'=>array(
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'where'=>'',
			'f_field'=>'name',
			'f_value'=>'',
			'view'=>'general'
		),
		'pending_post'=>array(
			'order'=>'id',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'view'=>'general',
			'f_field'=>'name',
			'f_value'=>'',
			'elements'=>array('Send'=>1,'ToSend'=>1)

		)



	),
	'customer_categories'=>array(

		'period'=>'year',
		'percentages'=>0,
		'mode'=>'all',
		'avg'=>'totals',
		'view'=>'sales',
		'root_block_view'=>'subcategories',
		'node_block_view'=>'subcategories',
		'head_block_view'=>'subjects',
		'from'=>'',
		'to'=>'',
		'edit'=>'description',
		'show_history'=>false,

		'orders_type'=>'all_contacts',
		'elements_type'=>'activity',
		'elements'=>array(
			'activity'=>array('Active'=>true,'Losing'=>true,'Lost'=>true),
			'level_type'=>array('Normal'=>true,'VIP'=>true,'Partner'=>true,'Staff'=>true),
			'location'=>array('Domestic'=>true,'Export'=>true)
		)
		,


		'edit_categories'=>array(

			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'elements'=>array('Root'=>1,'Node'=>0,'Head'=>0)

		),
		'subcategories'=>array(

			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
		),
		'main_categories'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
		),
		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Changes'=>1,'Assign'=>0)
		),
		'customers'=>array(
			'order'=>'id',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>20,
			'view'=>'general',
			'f_field'=>'customer name',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'percentage'=>false,
			'f_value'=>'',
			'orders_type'=>'all_contacts',
			'elements_type'=>'activity',
			'elements'=>array(
				'activity'=>array('Active'=>true,'Losing'=>true,'Lost'=>true),
				'level_type'=>array('Normal'=>true,'VIP'=>true,'Partner'=>true,'Staff'=>true),
				'location'=>array('Domestic'=>true,'Export'=>true)
			),

		),
		'edit_customers'=>array(

			'order'=>'id',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'customer name',
			'f_value'=>'',
			'f_show'=>false,
			'checked_all'=>false

		),
		'no_assigned_customers'=>array(

			'order'=>'id',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>100,
			'f_field'=>'customer name',
			'f_value'=>'','f_show'=>false,
			'checked_all'=>false,
			'orders_type'=>'all_contacts',
			'elements_type'=>'activity',
			'elements'=>array(
				'activity'=>array('Active'=>true,'Losing'=>true,'Lost'=>true),
				'level_type'=>array('Normal'=>true,'VIP'=>true,'Partner'=>true,'Staff'=>true),
				'location'=>array('Domestic'=>true,'Export'=>true)
			)

		),


	),
	'customers_list'=>array(




		'customers'=>array(


			'order'=>'id',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'view'=>'general',
			'orders_type'=>'all_contacts',
			'elements_type'=>'activity',
			'elements'=>array(
				'activity'=>array('Active'=>true,'Losing'=>true,'Lost'=>true),
				'level_type'=>array('Normal'=>true,'VIP'=>true,'Partner'=>true,'Staff'=>true),
				'location'=>array('Domestic'=>true,'Export'=>true)
			),

			'where'=>'',
			'f_field'=>'customer name',
			'f_value'=>'',


		),

		'edit_table'=>array(
			'order'=>'name',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,


			'where'=>'',
			'f_field'=>'customer name',
			'f_value'=>''


		),





	),

	'report_data'=>$report_data,

	'supplier_dn'=>array(
		'id'=>'',
		'show_all'=>false,
		'supplier_key'=>0,
		'pos'=>'',
		'view'=>'used_in',
		'products'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>''
		)
	),
	'supplier_invoice'=>array(
		'id'=>'',
		'show_all'=>false,
		'supplier_key'=>0,
		'pos'=>'',
		'view'=>'used_in',
		'products'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>''
		)
	),


	'dn'=>array(
		'id'=>''),
	'order'=>array(
		'id'=>'',

		'store_key'=>0,

		'products'=>array(
			'view'=>'general',
			'order'=>'code',
			'order_dir'=>'',
			'f_field'=>'code',
			'f_value'=>'',
			'display'=>'ordered_products'

		),

		'post_transactions'=>array(
			'operation'=>'Resend',
			'reason'=>'Other',
			'to_be_returned'=>'No',
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,




		),
		'ordered_products'=>array(
			'sf'=>0,
			'nr'=>500,
		),
		'all_products'=>array(
			'sf'=>0,
			'nr'=>25,
		)
	),


	'reports'=>array(
		'view'=>'sales',



		'sales'=>array(
			'plot'=>'total_sales_month',
			'store_key'=>1,
			'tipo'=>'y',
			'y'=>date('Y'),
			'm'=>date('m'),
			'd'=>date('d'),
			'w'=>date('W'),
		)
		,'stock'=>array(
			'plot'=>'total_outofstock_month'
		)
		,'geosales'=>array(
			'level'=>'region',
			'region'=>'world',
			'map_exclude'=>'',
			'table'=>array(
				'order'=>'country_code',
				'order_dir'=>'',
				'sf'=>0,
				'nr'=>25,
				'where'=>'where true',
				'f_field'=>'country',
				'f_value'=>'','f_show'=>false,
				'from'=>'',
				'to'=>''

			),
		)

	),

	'dashboards'=>array(
		'active_widgets'=>array(
			'nr'=>20,
			'sf'=>0,
			'order'=>'name',
			'order_dir'=>'desc',
			'f_field'=>'description',
			'f_value'=>''
		),'widgets'=>array(
			'nr'=>20,
			'sf'=>0,
			'order'=>'name',
			'order_dir'=>'desc',
			'f_field'=>'description',
			'f_value'=>''
		)
	),
	'dashboard'=>array(
		'active_widgets'=>array(
			'nr'=>20,
			'sf'=>0,
			'order'=>'name',
			'order_dir'=>'desc',
			'f_field'=>'description',
			'f_value'=>''
		)
	),
	'orders'=>array(
		'details'=>false,
		'view'=>'orders',

		'from'=>'',
		'to'=>'',
		'period'=>'all',

		'orders'=>array(
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'public_id',
			'f_value'=>'',
			'f_show'=>false,

			'elements_type'=>'dispatch',

			'elements'=>array(
				'source'=>array('Internet'=>1,'Call'=>1,'Store'=>1,'Other'=>1,'Email'=>1,'Fax'=>1),
				'payment'=>array('Paid'=>1,'PartiallyPaid'=>1,'Unknown'=>1,'WaitingPayment'=>1,'NA'=>1),
				'dispatch'=>array('InProcessCustomer'=>1,'InProcess'=>1,'Warehouse'=>1,'Dispatched'=>1,'Cancelled'=>1,'Suspended'=>1),
				'type'=>array('Order'=>1,'Sample'=>1,'Donation'=>1,'Other'=>1)
			)
		),
		'invoices'=>array(
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,

			'elements_type'=>'type',
			'elements'=>array(
				'payment'=>array('Yes'=>1,'No'=>1,'Partially'=>1),
				'type'=>array('Invoice'=>1,'Refund'=>1)
			)

		),
		'dn'=>array(

			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			'elements_type'=>'dispatch',
			'elements'=>array(
				'dispatch'=>array('Ready'=>1,'Picking'=>1,'Packing'=>1,'Done'=>1,'Send'=>1,'Returned'=>1),
				'type'=>array('Order'=>1,'Sample'=>1,'Donation'=>1,'Replacements'=>1,'Shortages'=>1)
			)

		)
		,'warehouse_orders'=>array(
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array('ReadytoPick'=>1,'ReadytoPack'=>1,'ReadytoShip'=>0,'PickingAndPacking'=>1,'ReadytoRestock'=>1,'Done'=>0),

		)


	),
	'orders_lists'=>array(

		'store'=>'',
		'view'=>'orders',

		'orders'=>array(
			'order'=>'name',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',







		),
		'invoices'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,

			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array(),

		),
		'dn'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'dn_state_type'=>'all',
			'elements'=>array(),

		)



	),
	'porder'=>array(
		'details'=>false,
		'store'=>'',
		'view'=>'orders',
		'only'=>'',
		'from'=>'',
		'to'=>'',
		'id'=>'',
		
		'parent'=>'supplier',
		'parent_key'=>0,

		'products'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'display'=>'ordered_products',
			'f_field'=>'p.code',
			'f_value'=>'',
			'f_show'=>false,
			


		),
		'porder_invoices'=>array(
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'invoice_type'=>'all',
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array()

		),
		'porder_dn'=>array(
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'dn_state_type'=>'all',
			'elements'=>array()

		)



	),
	'product_categories'=>array(

		'period'=>'all',
		'sales_sub_block_tipo'=>'plot_products_sales',
		'percentages'=>0,
		'mode'=>'all',
		'avg'=>'totals',
		'view'=>'sales',
		'root_block_view'=>'subcategories',
		'node_block_view'=>'subcategories',
		'head_block_view'=>'subjects',
		'from'=>'',
		'to'=>'',
		'edit'=>'description',
		'show_history'=>false,
		'edit_categories'=>array(
			'view'=>'category',
			'order'=>'sku',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'elements'=>array('Root'=>1,'Node'=>0,'Head'=>0)

		),
		'subcategories'=>array(

			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'elements_type'=>'use',
			'elements'=>array('use'=>array('InUse'=>1,'NotInUse'=>0))
		),
		'main_categories'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
		),
		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Changes'=>1,'Assign'=>0)
		),
		'products'=>array(
			'table_type'=>'list',
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'exchange_type'=>'day2day',
			'exchange_value'=>1,
			'show_default_currency'=>false,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,

			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'restrictions'=>'',
			'elements_type'=>'type',
			'elements_stock_aux'=>'InWeb',
			'elements'=>array(
				'type'=>array('Historic'=>0,'Discontinued'=>0,'Private'=>0,'NoSale'=>0,'Sale'=>1),
				'web'=>array('ForSale'=>1,'OutofStock'=>1,'Discontinued'=>1,'Offline'=>0),
				'stock'=>array('Excess'=>1,'Normal'=>1,'Low'=>1,'VeryLow'=>1,'OutofStock'=>1,'Error'=>1),
			)
		),
		'edit_products'=>array(

			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'checked_all'=>false

		),
		'no_assigned_products'=>array(

			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'checked_all'=>false

		),


	),
	'family_categories'=>array(

		'period'=>'all',
		'sales_sub_block_tipo'=>'plot_products_sales',
		'percentages'=>0,
		'mode'=>'all',
		'avg'=>'totals',
		'view'=>'sales',
		'root_block_view'=>'subcategories',
		'node_block_view'=>'subcategories',
		'head_block_view'=>'subjects',
		'from'=>'',
		'to'=>'',
		'edit_block'=>'description',
		'edit_description_block'=>'description',
		'show_history'=>false,
		'edit_categories'=>array(
			'view'=>'category',
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'elements'=>array('Root'=>1,'Node'=>0,'Head'=>0)

		),
		'subcategories'=>array(

			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'elements_type'=>'use',
			'elements'=>array('use'=>array('InUse'=>1,'NotInUse'=>0))
		),
		'main_categories'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
		),
		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Changes'=>1,'Assign'=>0)
		),
		'families'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'restrictions'=>'',
			'elements'=>array('Discontinued'=>0,'Normal'=>1,'Discontinuing'=>1,'InProcess'=>0,'NoSale'=>0)


		),
		'edit_families'=>array(

			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'checked_all'=>false

		),
		'no_assigned_families'=>array(

			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'checked_all'=>false

		),


	),
	'supplier_categories'=>array(
		'period'=>'ytd',
		'percentages'=>0,
		'mode'=>'all',
		'avg'=>'totals',
		'view'=>'sales',
		'sales_block'=>'supplier_timeseries',
		'root_block_view'=>'subcategories',
		'node_block_view'=>'subcategories',
		'head_block_view'=>'subjects',
		'from'=>'',
		'to'=>'',
		'edit'=>'description',
		'show_history'=>false,
		'edit_categories'=>array(

			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'elements'=>array('Root'=>1,'Node'=>0,'Head'=>0)

		),
		'subcategories'=>array(

			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
		),
		'main_categories'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
		),
		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Changes'=>1,'Assign'=>0)
		),
		'suppliers'=>array(
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'',
			'avg'=>'totals',
			'view'=>'general',
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'',


		),
		'edit_suppliers'=>array(

			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'checked_all'=>false

		),
		'no_assigned_suppliers'=>array(

			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'checked_all'=>false

		),
		'sales_history'=>array(
			'timeline_group'=>'week',
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,

		),
		'supplier_product_sales'=>array(


			'order'=>'sales',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>100,
			'f_field'=>'code',
			'f_value'=>'',



		),
	),
	'invoice_categories'=>array(

		'period'=>'all',
		'percentages'=>0,
		'mode'=>'all',
		'avg'=>'totals',
		'view'=>'sales',
		'root_block_view'=>'subcategories',
		'node_block_view'=>'subcategories',
		'head_block_view'=>'subjects',
		'from'=>'',
		'to'=>'',
		'edit'=>'description',
		'show_history'=>false,

		'elements_type'=>'type',
		'elements'=>array(
			'payment'=>array('Yes'=>1,'No'=>1,'Partially'=>1),
			'type'=>array('Invoice'=>1,'Refund'=>1)
		),



		'edit_categories'=>array(

			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'elements'=>array('Root'=>1,'Node'=>0,'Head'=>0)

		),
		'subcategories'=>array(

			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
		),
		'main_categories'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
		),
		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Changes'=>1,'Assign'=>0)
		),
		'invoices'=>array(
			'order'=>'id',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>20,
			'view'=>'general',
			'f_field'=>'invoice name',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'percentage'=>false,
			'f_value'=>'',
			'elements_type'=>'type',
			'elements'=>array(
				'payment'=>array('Yes'=>1,'No'=>1,'Partially'=>1),
				'type'=>array('Invoice'=>1,'Refund'=>1)
			)

		),
		'edit_invoices'=>array(

			'order'=>'id',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'invoice name',
			'f_value'=>'',
			'f_show'=>false,
			'checked_all'=>false,
			'elements_type'=>'type',
			'elements'=>array(
				'payment'=>array('Yes'=>1,'No'=>1,'Partially'=>1),
				'type'=>array('Invoice'=>1,'Refund'=>1)
			)

		),
		'no_assigned_invoices'=>array(

			'order'=>'id',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>100,
			'f_field'=>'invoice name',
			'f_value'=>'','f_show'=>false,
			'checked_all'=>false,
			'elements_type'=>'type',
			'elements'=>array(
				'payment'=>array('Yes'=>1,'No'=>1,'Partially'=>1),
				'type'=>array('Invoice'=>1,'Refund'=>1)
			)

		),


	),
	'products'=>array(
		'details'=>false,
		'store'=>'1',
		'percentages'=>false,
		'view'=>'general',
		'from'=>'',
		'to'=>'',
		'period'=>'year',
		'percentage'=>0,
		'mode'=>'same_code',//same_code,same_id,all
		'parent'=>'none',//store,dement,family,none
		'restrictions'=>'forsale',
		'avg'=>'totals',
		'list'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'',
			'f_field'=>'',
			'f_value'=>'',
			'view'=>'general'
		),
		'table'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array(),
			'mode'=>'same_code',//same_code,same_id,all
			'parent'=>'none',//store,department,family,none
			'restrictions'=>'forsale',
			'family_code'=>''
		),
	),

	'supplier_product'=>array(
		
		'period'=>'all',
		'from'=>'',
		'to'=>'',
		'editing'=>'prices',
		'block_view'=>'details',
				'block_view'=>'details',
'sales_block'=>'supplier_product_sales_timeseries',
				'stock_history_block'=>'stock_history_list',

		'porders'=>array(
			'order'=>'date',
			'view'=>'general',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'',
			'f_show'=>false,
			'from'=>'',
			'to'=>''

		),
		'history'=>array(
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'',
			'f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Notes'=>1,'Changes'=>1,'Attachments'=>1)
		),
				'stock_history'=>array(
			'show_chart'=>1,
			'chart_output'=>'stock',
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'timeline_group'=>'week',
			'where'=>'where true',
			'f_field'=>'location',
			'f_value'=>'',
			'f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array()
		),
		'transactions'=>array(
			'view'=>'all_transactions',
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'where'=>'where true',
			'f_field'=>'note',
			'f_value'=>'',
			'f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array()
		),
			'sales_history'=>array(
			'timeline_group'=>'week',
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'',
			'f_value'=>'',
			'f_show'=>false,

		),
		
	),
	'report_sales'=>array(
		'period'=>'mtd',
		'from'=>'',
		'to'=>'',
		'block'=>'stores',
		'stores_subblock'=>'sales',
		'categories_subblock'=>'sales',
		'history_subblock'=>'list',
		'sales_currency'=>'corporate',

		'stores'=>array(
			'stores'=>array(
				'f_field'=>'name',
				'f_value'=>'',
				'f_show'=>false,
				'order'=>'store_key',
				'order_dir'=>'',
				'sf'=>0,
				'nr'=>25),
			'sales_history'=>array(

				'timeline_group'=>'week',
				'order'=>'date',
				'order_dir'=>'',
				'sf'=>0,
				'nr'=>25,
				'f_field'=>'code',
				'f_value'=>'',
				'f_show'=>false


			)

		),
		'categories'=>array(
'categories'=>array(
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'category_key',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
)
		),



	),
	'report_sales_components'=>array(
		'period'=>'mtd',
		'block_view'=>'stores',
		'from'=>'',
		'to'=>'',
		'activity'=>array('compare'=>'last_year','period'=>'week'),

		'store_keys'=>'all',

		'order'=>'date',
		'order_dir'=>'desc',
		'currency'=>'stores',
		'view'=>'invoices',
		'sf'=>0,
		'nr'=>25,
		'plot'=>'plot_all_stores',
		'plot_data'=>array(
		),

		'stores'=> array(
			'order'=>'code',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>200,
			'f_field'=>'code',
			'f_value'=>'',

			'view'=>'',
			'type'=>'amount'

		)

	),
	'report_pending_orders'=>array(
		'period'=>'all',
		'from'=>'',
		'to'=>'',
		'block_view'=>'stores',
		
		'stores'=> array(
			'order'=>'name',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>200,
			'f_field'=>'name',
			'f_value'=>'',
			'currency'=>'stores',
			'view'=>'',
			'type'=>'amount'

		)

	),
	'report_intrastat'=>array(
	
		'period'=>'last_m',
		'from'=>'',
		'to'=>'',
		'order'=>'country_2alpha_code',
		'order_dir'=>'',
		'sf'=>0,
		'nr'=>500,
		'f_field'=>'tariff_code',
		'f_value'=>''
	),
	'report_sales_week'=>array(
		'store'=>'',
		'invoices'=> array(
			'days'=>array(1,2,3,4,5,6,7),
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>200,
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'',
			'from'=>'',
			'to'=>'',
			'store'=>'',
			'view'=>'',
			'invoice_type'=>''

		)

	),
	'report_geo_sales'=>array(
		'store_keys'=>'all',
		'period'=>'mtd',
		'from'=>'',
		'to'=>'',
		'mode'=>'world',
		'mode_key'=>'',

		'f_value'=>'',
		'f_show'=>false,
		'f_field'=>'customer_name',
		'from'=>'',
		'to'=>'',

		'world'=>array(
			'view'=>'countries',
			'map_links'=>'countries'),

		'continent'=>array('view'=>'countries',
			'map_links'=>'countries'),
		'wregion'=>array(
			'view'=>'countries',
			'map_links'=>'countries',
			'plot_tipo'=>'plot_all_stores'),
		'country'=>array('view'=>'overview',
			'plot_tipo'=>'plot_all_stores'),


		'countries'=>array(
			'display'=>'all',
			'order'=>'name',
			'order_dir'=>'asc',
			'sf'=>0,
			'nr'=>20,
			'where'=>'where true',
			'f_field'=>'country_code',
			'f_value'=>'',
		),
		'wregions'=>array(
			'wregion_code'=>'',
			'display'=>'all',
			'order'=>'wregion_name',
			'order_dir'=>'asc',
			'sf'=>0,
			'nr'=>20,
			'where'=>'where true',
			'f_field'=>'wregion_code',
			'f_value'=>'',
		),
		'continents'=>array(
			'continent_code'=>'',
			'display'=>'all',
			'order'=>'continent_name',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>20,
			'where'=>'where true',
			'f_field'=>'continent_code',
			'f_value'=>'',
		)





	),
	'report_customers'=>array(
		'period'=>'ytd',
		'from'=>'',
		'to'=>'',
		'store_keys'=>'all',
		'view'=>'general',
		'top'=>100,
		'criteria'=>'net_balance',
		'f_value'=>'',
		'f_show'=>false,
		'f_field'=>'customer_name',
		'from'=>'',
		'to'=>''
	),
	'report_part_out_of_stock'=>array(
		'period'=>'mtd',

		'store_keys'=>'all',
		'block'=>'transactions',
		'from'=>'',
		'to'=>'',
		'transactions'=>array(

			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'f_value'=>'',
			'f_show'=>false,
			'f_field'=>'product',

			'view'=>''
		),
		'orders'=>array(

			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'f_value'=>'',
			'f_show'=>false,
			'f_field'=>'public_id',

			'view'=>''
		),
		'parts'=>array(

			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'f_value'=>'',
			'f_show'=>false,
			'f_field'=>'reference',
			'from'=>'',
			'to'=>'',
			'view'=>''
		),
		'customers'=>array(

			'order'=>'customer',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_value'=>'',
			'f_show'=>false,
			'f_field'=>'name',
			'from'=>'',
			'to'=>'',
			'view'=>''
		),

	),

	'report_first_order'=>array(
		'period'=>'ytd',
		'from'=>'',
		'to'=>'',
		'department_key'=>false,
		'share'=>.8,
		'from'=>'',
		'to'=>'',
		'products'=>array(
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>30,
			'where'=>'where true',
			'f_field'=>'',
			'f_value'=>'',
			'f_show'=>false,

			'elements'=>array()
		)


	),
	'report_pp'=>array(
		'view'=>'pickers',
		'employee_view'=>'overview',
		'period'=>'mtd',
		'from'=>'',
		'to'=>'',
		'pickers'=>array(
			'order'=>'alias',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,

			'where'=>'where true',
			'f_field'=>'',
			'f_value'=>'','f_show'=>false,
			'elements'=>array()

		),
		'packers'=>array(
			'order'=>'alias',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,

			'where'=>'where true',
			'f_field'=>'',
			'f_value'=>'','f_show'=>false,
			'elements'=>array()

		),
		'picked_dns'=>array(
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',

		),
		'packed_dns'=>array(
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',

		)
		
		


	),
	'report_sales_with_no_tax'=>array(
		'period'=>'mtd',
		'from'=>'',
		'to'=>'',
		'stores'=>false,
		'currency_type'=>'original',
		'view'=>'overview',
		'country'=>'GB',
		'GB'=>array(
			'tax_category'=>array(),
			'regions'=>array('GBIM'=>1,'EU'=>1,'NOEU'=>1)
		),
		'ES'=>array(
			'tax_category'=>array(),
			'regions'=>array('ES'=>1,'EU'=>1,'NOEU'=>1)
		),

		'overview'=>array(
			'order'=>'date',
			'order_dir'=>'',

			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'',
			'f_show'=>false,


		),
		'invoices'=>array(
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'',
			'f_show'=>false,


		),
		'customers'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'customer',
			'f_value'=>'',
			'f_show'=>false,


		),



	),


	'marketing'=>array(
		'view'=>'deals',
		'deals_block_view'=>'campaigns',
		'email_campaigns'=>array(
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'view'=>'general'
		),
		'postal_campaigns'=>array(
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'view'=>'general'
		),
		'media_campaigns'=>array(
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'view'=>'general'
		),
		'newsletters'=>array(
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'view'=>'general'
		),

		'reminders'=>array(
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'view'=>'general'
		),
		'campaigns'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,

			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'elements'=>array()
		),
		'offers'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'elements'=>array('Order'=>1,'Department'=>1,'Family'=>1,'Product'=>1)
		)
	),
	'campaign'=>array(
		'view'=>'details',

		'orders'=>array(
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'',
			'f_show'=>false,

		),
		'customers'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'elements'=>array()
		),

		'offers'=>array(
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
			'elements'=>array('Order'=>1,'Department'=>1,'Family'=>1,'Product'=>1)
		),
	),

	'deal'=>array(
		'view'=>'details',

		'edit_block_view'=>'state',
		'orders'=>array(
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'',
			'f_show'=>false,
			'elements'=>array()
		),
		'customers'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'elements'=>array()
		),
		'edit_deal_components'=>array(
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
		),
		'components'=>array(
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
		),
	),

	'warehouse'=>array(

		'edit'=>'description',
		'edit_parts_block'=>'parts',
		'view'=>'locations',
		'parts_view'=>'parts',
		'show_stock_history_chart'=>false,
		'stock_history_block'=>'list',
		'period'=>'all',
		'from'=>'',
		'to'=>'',
		'parts'=>array(
			'order'=>'sku',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>20,
			'view'=>'general',
			'where'=>'where true',
			'f_field'=>'reference',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'percentage'=>false,
			'f_value'=>'',
			'elements_type'=>'use',
			'elements'=>array(
				'use'=>array('InUse'=>1,'NotInUse'=>0),
				'state'=>array('Keeping'=>1,'LastStock'=>1,'Discontinued'=>0,'NotKeeping'=>0),
				'stock_state'=>array('Excess'=>1,'Normal'=>1,'Low'=>1,'VeryLow'=>1,'OutofStock'=>1,'Error'=>1)

			),
			//'elements'=>array('Keeping'=>1,'LastStock'=>1,'Discontinued'=>0,'NotKeeping'=>0),


		),
		'parts_lists'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>20,
			'f_field'=>'name',
			'f_value'=>'',


		),
		'stock_history'=>array(
			'show_chart'=>0,
			'chart_output'=>'value',
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'timeline_group'=>'week',
			'where'=>'where true',
			'f_field'=>'location',
			'f_value'=>'',
			'f_show'=>false,
			'from'=>'',
			'to'=>'',
			'period'=>'all',
			'elements'=>array()
		),
		'transactions'=>array(
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'where'=>'where true',
			'f_field'=>'note',
			'f_value'=>'',
			'f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array(),
			'view'=>'all_transactions'
		),
		'locations'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'elements'=>array('Blue'=>1,'Green'=>1,'Orange'=>1,'Pink'=>1,'Purple'=>1,'Red'=>1,'Yellow'=>1)

		),
		'replenishments'=>array(
			'order'=>'location',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
			'where'=>'where true',
			'f_field'=>'location',
			'f_value'=>'','f_show'=>false

		),
		'edit_locations'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'elements'=>array('Blue'=>1,'Green'=>1,'Orange'=>1,'Pink'=>1,'Purple'=>1,'Red'=>1,'Yellow'=>1)
		),
		'edit_parts'=>array(
			'order'=>'sku',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>20,
			'view'=>'general',
			'where'=>'where true',
			'f_field'=>'reference',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'percentage'=>false,
			'f_value'=>'',
			'elements_type'=>'use',
			'elements'=>array(
				'use'=>array('InUse'=>1,'NotInUse'=>0),
				'state'=>array('Keeping'=>1,'LastStock'=>1,'Discontinued'=>0,'NotKeeping'=>0),
				'stock_state'=>array('Excess'=>1,'Normal'=>1,'Low'=>1,'VeryLow'=>1,'OutofStock'=>1,'Error'=>1)

			),
			//'elements'=>array('Keeping'=>1,'LastStock'=>1,'Discontinued'=>0,'NotKeeping'=>0),


		),
		'part_locations'=>array(
			'order'=>'location',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'location',
			'f_value'=>'','f_show'=>false
		),

		'warehouse_areas'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'elements'=>array()
		)




	),
	'part_categories'=>array(

		'period'=>'all',
		'sales_sub_block_tipo'=>'plot_parts_sales',
		'percentages'=>0,
		'mode'=>'all',
		'avg'=>'totals',
		'view'=>'sales',
		'root_block_view'=>'subcategories',
		'node_block_view'=>'subcategories',
		'head_block_view'=>'subjects',
		'from'=>'',
		'to'=>'',
		'edit'=>'description',
		'show_history'=>false,
		'edit_categories'=>array(
			'view'=>'category',
			'order'=>'sku',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'elements'=>array('Root'=>1,'Node'=>0,'Head'=>0)

		),
		'subcategories'=>array(

			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'elements_type'=>'use',
			'elements'=>array('use'=>array('InUse'=>1,'NotInUse'=>0))
		),
		'main_categories'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
		),
		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Changes'=>1,'Assign'=>0)
		),
		'parts'=>array(
			'order'=>'sku',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>20,
			'view'=>'general',
			'where'=>'where true',
			'f_field'=>'reference',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'percentage'=>false,
			'f_value'=>'',
			'elements_type'=>'use',
			'elements'=>array(
				'use'=>array('InUse'=>1,'NotInUse'=>0),
				'state'=>array('Keeping'=>1,'LastStock'=>1,'Discontinued'=>0,'NotKeeping'=>0),
				'stock_state'=>array('Excess'=>1,'Normal'=>1,'Low'=>1,'VeryLow'=>1,'OutofStock'=>1,'Error'=>1)
			)

		),
		'edit_parts'=>array(

			'order'=>'sku',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'sku',
			'f_value'=>'',
			'f_show'=>false,
			'checked_all'=>false

		),
		'no_assigned_parts'=>array(

			'order'=>'sku',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
			'where'=>'where true',
			'f_field'=>'sku',
			'f_value'=>'','f_show'=>false,
			'checked_all'=>false

		),


	),
	'parts_list'=>array(

		'period'=>'all',
		'percentages'=>0,
		'mode'=>'all',
		'avg'=>'totals',
		'view'=>'parts',

		'show_history'=>false,

		'parts'=>array(
			'order'=>'sku',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>20,
			'view'=>'general',
			'where'=>'where true',
			'f_field'=>'reference',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'percentage'=>false,
			'f_value'=>'',
			'elements_type'=>'use',
			'elements'=>array(
				'use'=>array('InUse'=>1,'NotInUse'=>0),
				'state'=>array('Keeping'=>1,'LastStock'=>1,'Discontinued'=>0,'NotKeeping'=>0),
				'stock_state'=>array('Excess'=>1,'Normal'=>1,'Low'=>1,'VeryLow'=>1,'OutofStock'=>1,'Error'=>1)
			)

		),
		'edit_parts'=>array(

			'order'=>'sku',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'sku',
			'f_value'=>'',
			'f_show'=>false,
			'checked_all'=>false

		),



	),
	'stock_history'=>array(

		'block_view'=>'parts',

		'parts'=>array(
			'order'=>'sku',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>20,
			'view'=>'general',
			'where'=>'where true',
			'f_field'=>'reference',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'percentage'=>false,
			'f_value'=>'',
			'elements'=>array('Keeping'=>1,'LastStock'=>1,'Discontinued'=>0,'NotKeeping'=>0),


		),

		'transactions'=>array(
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'where'=>'where true',
			'f_field'=>'note',
			'f_value'=>'',
			'f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array(),
			'view'=>'all_transactions'
		)


	),




	'shelfs'=>array(
		'parent'=>'none',
		'table'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'elements'=>array()
		)

	),
	'warehouses'=>array(
		'view'=>'warehouses',

		'warehouses'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'elements'=>array()

		),
		'warehouse_areas'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'elements'=>array()
		),
		'shelfs'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'elements'=>array()
		),
		'locations'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'elements'=>array()
		), 'avg'=>'totals',
		'parts'=>array(
			'order'=>'sku',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>20,
			'where'=>'where true',
			'f_field'=>'reference',
			'f_value'=>''


		)



	),

	'warehouse_area'=>array(

		'view'=>'description',
		'edit'=>'description',
		'on_creation'=>'go_back',
		'locations'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'elements'=>array('Blue'=>1,'Green'=>1,'Orange'=>1,'Pink'=>1,'Purple'=>1,'Red'=>1,'Yellow'=>1)

		),
		'edit_locations'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'elements'=>array('Blue'=>1,'Green'=>1,'Orange'=>1,'Pink'=>1,'Purple'=>1,'Red'=>1,'Yellow'=>1)
		),
		'parts'=>array(
			'order'=>'sku',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>50,
			'where'=>'where true',
			'f_field'=>'sku',
			'f_value'=>''
		),


	),
	'shelf_types'=>array(
		'view'=>'general',
		'table'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'','f_show'=>false,
			'elements'=>array()
		)
	),
	'shelf_location_types'=>array(

		'table'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'','f_show'=>false,
			'elements'=>array()
		)
	),
	'customer_store_configuration'=>array(
		'view'=>'new_custom_fields'
	),

	'customer'=>array(
		'id'=>0,
		'action_after_create'=>'continue',
		'edit'=>'details',
		'details'=>false,
		'view'=>'history',
		'assets'=>array(
			'order'=>'subject',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'type'=>'Family'

		),
		'orders'=>array(
			'order'=>'last_update',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>''


		),
		'history'=>array(
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>10,
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'details'=>0,
			'elements'=>array('Notes'=>1,'Orders'=>1,'Changes'=>1,'Attachments'=>1,'Emails'=>1,'WebLog'=>0)
		)
	),

	'suppliers'=>array(
		'block_view'=>'suppliers',
		'edit'=>'suppliers',
		'period'=>'1y',
		'from'=>'',
			'to'=>'',

		
		'supplier_products'=>array(
			'percentages'=>false,
			'view'=>'general',
			'from'=>'',
			'to'=>'',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'',
			'avg'=>'totals',
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'sup_code',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array()
		),
		'suppliers'=>array(
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'',
			'avg'=>'totals',
			'view'=>'general',
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>''


		),
		'edit_suppliers'=>array(


			'view'=>'general',
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'',


		),
	
		'porders'=>array(
			'order'=>'date',
			'view'=>'general',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			


		
	),

		'supplier_invoices'=>array(
			'order'=>'date',
			'view'=>'general',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			
	
			),
		'supplier_dns'=>array(
	
	
			'order'=>'date',
			'view'=>'general',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			
		
	),

		
		
	),



	'hr'=>array(
		'block'=>'employees',
		'employees'=>array('id'=>'',
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'f_field'=>'name',
			'f_value'=>'',
			'elements'=>array('Working'=>1,'NotWorking'=>0)
		),
		'areas'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'f_field'=>'name',
			'f_value'=>''
		),
		'departments'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'f_field'=>'name',
			'f_value'=>''
		),
		'positions'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'f_field'=>'name',
			'f_value'=>''
		)
	),
	'company_area'=>array(
		'block'=>'employees',
		'edit_block'=>'details',
		'show_history'=>false,
		'employees'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'f_field'=>'name',
			'f_value'=>'',
			'elements'=>array('Working'=>1,'NotWorking'=>0)

		),
		'departments'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'f_field'=>'name',
			'f_value'=>''
		),
		'positions'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'f_field'=>'name',
			'f_value'=>''
		),
		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>''
		),
	
	),
	'company_department'=>array(
		'block'=>'employees',
		'employees'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'f_field'=>'name',
			'f_value'=>'',

			'elements'=>array('Working'=>1,'NotWorking'=>0)

		),
		'positions'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'f_field'=>'name',
			'f_value'=>''
		),
	
	),	
	'position'=>array(
		'block'=>'employees',
		'action_after_create'=>'continue',
		'edit'=>'details',
		'employees'=>array(
			'order'=>'code',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>10,
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'details'=>0,
			'elements'=>array('h_comp'=>1,'h_cont'=>1,'note'=>1)
		),
		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>''
		),


	),
	'employee'=>array(
		'block'=>'details',
		'edit_block'=>'description',
				'edit_description_block'=>'id',

		'history'=>array(
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'',
			'f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Notes'=>1,'Changes'=>1,'Attachments'=>1)
		),
				'working_hours'=>array(
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'f_field'=>'hours_worked',
			'f_value'=>'',
			'f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Notes'=>1,'Changes'=>1,'Attachments'=>1)
		),
	),
	
	'site_user'=>array(
		'block_view'=>'login_history',
		'login_history'=>array(
			'display'=>'all',
			'order'=>'login_date',
			'order_dir'=>'',
			'type'=>'',
			'sf'=>0,
			'nr'=>50,
			'where'=>'where true',
			'f_field'=>'ip',
			'f_value'=>''
		),
		'visit_pages'=>array(
			'display'=>'all',
			'order'=>'login_date',
			'order_dir'=>'',
			'type'=>'',
			'sf'=>0,
			'nr'=>50,
			'where'=>'where true',
			'f_field'=>'ip',
			'f_value'=>''
		),
	),


	'staff_user'=>array(
		'block_view'=>'login_history',
		'login_history'=>array(
			'display'=>'all',
			'order'=>'login_date',
			'order_dir'=>'',
			'type'=>'',
			'sf'=>0,
			'nr'=>50,
			'where'=>'where true',
			'f_field'=>'ip',
			'f_value'=>''
		),
	),


	'users'=>array(
		'staff'=>array(

			'block_view'=>'users',
			'display'=>'active',
			'order'=>'alias',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'where'=>'where true',
			'f_field'=>'alias',
			'f_value'=>'',
			'view'=>'general',
			'state'=>array('Active'=>1,'Inactive'=>0),
			'elements'=>array('Working'=>1,'NotWorking'=>1)


		),
		'supplier'=>array(
			'display'=>'all',
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>''
		),
		'site'=>array(
			'display'=>'all',
			'order'=>'handle',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'handle',
			'f_value'=>''
		),
		'login_history'=>array(
			'display'=>'all',
			'order'=>'login_date',
			'order_dir'=>'desc',
			'type'=>'',
			'sf'=>0,
			'nr'=>50,
			'where'=>'where true',
			'f_field'=>'handle',
			'f_value'=>''
		),
		'groups'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>''
		),

	),
	'picking_aid'=>array(

		'type'=>'dynamic',
		'items'=>array(
			'where'=>'where true',
			'f_field'=>'sku',
			'f_value'=>'','f_show'=>false,
			'order'=>'sku',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>''

		)
	),
	'packing_aid'=>array(

		'type'=>'dynamic',
		'items'=>array(
			'where'=>'where true',
			'f_field'=>'sku',
			'f_value'=>'','f_show'=>false,
			'order'=>'sku',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>''

		)
	),

	'page'=>array(
		'id'=>0,
		'view'=>'details',
		'editing'=>'content',
		'editing_content_block'=>'header',
		'show_history'=>false,
		'users'=>array(
			'f_field'=>'handle',
			'f_value'=>'',
			'order'=>'last_visit',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>50,
			'view'=>'general',

		),
		'requests'=>array(
			'f_field'=>'handle',
			'f_value'=>'',
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>50,
			'view'=>'general',

		),


		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>''
		),
		'edit_product_list'=>array(
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>''
		),
		'edit_product_button'=>array(
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>''
		),

		'edit_headers'=>array(
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'view'=>'overview'

		),
		'edit_footers'=>array(
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'view'=>'overview'

		),

	),
	'sites'=>array(
		'block_view'=>'sites',


		'sites'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'type'=>'list',



		),
		'pages'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'type'=>'list',
			'elements'=>array(
				'ProductDescription'=>1,
				'FamilyCatalogue'=>1,
				'DepartmentCatalogue'=>1,
				'Other'=>1,

			)


		),
		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>''
		)

	),
	'preferences'=>array(
		'view'=>'color'
	),
	'site'=>array(

		'view'=>'details',
		'search_queries_block'=>'queries',
		'period'=>'day',
		'percentage'=>0,
		'mode'=>'all',
		'avg'=>'totals',
		'details'=>true,
		'show_history'=>false,
		'id'=>false,
		'editing'=>'general',
		'editing_components'=>'headers',
		'editing_style'=>'background',
		'editing_general'=>'website_properties',
			'editing_users'=>'registration',
		
		
		'edit_pages'=>array(
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'view'=>'page_properties',
			'elements'=>array(
				'ProductDescription'=>1,
				'FamilyCatalogue'=>1,
				'DepartmentCatalogue'=>1,
				'Other'=>1,

			)
		),

		'edit_headers'=>array(
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'view'=>'overview'

		),

		'edit_footers'=>array(
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'view'=>'overview'

		),
		'pages'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'type'=>'list',
			'elements'=>array(
				'System'=>1, 'Info'=>1, 'Department'=>1, 'Family'=>1, 'Product'=>1, 'FamilyCategory'=>1, 'ProductCategory' =>1

			)


		),
		'hits'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'type'=>'list',
			'elements'=>array(
				'ProductDescription'=>1,
				'FamilyCatalogue'=>1,
				'DepartmentCatalogue'=>1,
				'Other'=>1,

			)


		),
		'page'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			//'avg'=>'totals',
			//'type'=>'list'


		),
		'overview'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'total_visits',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'total_visits',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>5,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			//'avg'=>'totals',
			//'type'=>'list'


		),
		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>''
		),

		'users'=>array(
			'f_field'=>'handle',
			'f_value'=>'',
			'order'=>'last_visit',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>50,
			'view'=>'general',

		),
		'requests'=>array(
			'f_field'=>'handle',
			'f_value'=>'',
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>50,
			'view'=>'general',

		),
		'queries'=>array(
			'f_field'=>'query',
			'f_value'=>'',
			'order'=>'multiplicity',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>50,
			'view'=>'general',

		),
		'query_history'=>array(
			'f_field'=>'query',
			'f_value'=>'',
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>50,
			'view'=>'general',

		),


	),
	'email_campaign'=>array(
		'id'=>false,
		'mailing_list'=>array(
			'where'=>'where true',
			'f_field'=>'email',
			'f_value'=>'','f_show'=>false,
			'order'=>'email',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'view'=>'general'
		),
		'objectives'=>array(
			'f_field'=>'email',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'view'=>'general'
		)

	),
	'account'=>array(
		'block_view'=>'details',
		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Changes'=>1)
		),
		'custom_fields'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,

			'f_field'=>'name',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Packed'=>1,'InWarehouse'=>1,'SubmittedbyCustomer'=>1,'InProcess'=>1,'InProcessbyCustomer'=>1),

		),


	),
	'stores'=>array(
		'block_view'=>'stores',
		'edit_block_view'=>'stores',
		 'marketing_block_view'=>'stores',
		'orders_view'=>'orders',
		'stats_view'=>'sales',
		'stores'=>array(
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'exchange_type'=>'day2day',
			'exchange_value'=>1,
			'show_default_currency'=>false,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,


		),
		'departments'=>array(
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'exchange_type'=>'day2day',
			'exchange_value'=>1,
			'show_default_currency'=>false,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,

			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',


		),
		'families'=>array(
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'exchange_type'=>'day2day',
			'exchange_value'=>1,
			'show_default_currency'=>false,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,

			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'restrictions'=>'',
			'elements'=>array('NoSale'=>0,'Discontinued'=>0,'Normal'=>1,'Discontinuing'=>1,'InProcess'=>0),



		),
		'products'=>array(
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'exchange_type'=>'day2day',
			'exchange_value'=>1,
			'show_default_currency'=>false,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,

			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'restrictions'=>'',
			'elements_type'=>'type',
			'elements_stock_aux'=>'InWeb',
			'elements'=>array(
				'type'=>array('Historic'=>0,'Discontinued'=>0,'Private'=>0,'NoSale'=>0,'Sale'=>1),
				'web'=>array('ForSale'=>1,'OutofStock'=>1,'Discontinued'=>1,'Offline'=>0),
				'stock'=>array('Excess'=>1,'Normal'=>1,'Low'=>1,'VeryLow'=>1,'OutofStock'=>1,'Error'=>1),
			)
		),
		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>''
		),
		'orders'=>array(
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,





		),
		'invoices'=>array(
			'percentages'=>false,
			'view'=>'general',
			'invoice_type'=>'all',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,


			'list'=>array(
				'order'=>'date',
				'order_dir'=>'',
				'sf'=>0,
				'nr'=>25,
				'where'=>'',
				'f_field'=>'',
				'f_value'=>'',
				'view'=>'general'
			),


		),
		'delivery_notes'=>array(
			'percentages'=>false,
			'dn_state'=>'all',
			'view'=>'dn_state',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,

		),
		'customers'=>array(
			'percentages'=>false,
			'type'=>'all_contacts',
			'view'=>'general',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,

		),
		'marketing'=>array(
			'store'=>0,
			'percentages'=>false,
			'view'=>'metrics',
			'period'=>'year',
			'mode'=>'all',
			'avg'=>'totals',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,

		),
		'sites'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'day',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals'


		),
		'campaigns'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,

			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'elements'=>array()
		),
		'offers'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'elements'=>array('Order'=>1,'Department'=>1,'Family'=>1,'Product'=>1)
		),
		

	),
	'store'=>array(
		'block_view'=>'departments',
		'deals_block_view'=>'campaigns',
		'websites_block_view'=>'pages',
		'sales_sub_block_tipo'=>'plot_store_sales',
		'show_history'=>false,
		'period'=>'all',
		'from'=>'',
		'to'=>'',
		'plot'=>'store',
		'edit'=>'description',
		'edit_pages'=>array(
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'view'=>'page_properties',
			'elements'=>array(
				'Product Description'=>1,
				'Family Catalogue'=>1,
				'Product Catalogue'=>1,
				'Other'=>1,

			)
		),
		'pages'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'elements'=>array(
				'Product Description'=>1,
				'Family Catalogue'=>1,
				'Product Catalogue'=>1,
				'Other'=>1,

			)


		),
		'sites'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'day',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals'


		),
		'edit_sites'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'day',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals'


		),
		'departments'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals'


		),
		'edit_departments'=>array(
		
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,

			'view'=>'general',
	


		),
		'families'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'restrictions'=>'',
			'elements'=>array('Discontinued'=>0,'Normal'=>1,'Discontinuing'=>1,'InProcess'=>0,'NoSale'=>0)


		),
		'products'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'restrictions'=>'',
			'elements_type'=>'type',
			'elements_stock_aux'=>'InWeb',
			'elements'=>array(
				'type'=>array('Historic'=>0,'Discontinued'=>0,'Private'=>0,'NoSale'=>0,'Sale'=>1),
				'web'=>array('ForSale'=>1,'OutofStock'=>1,'Discontinued'=>1,'Offline'=>0),
				'stock'=>array('Excess'=>1,'Normal'=>1,'Low'=>1,'VeryLow'=>1,'OutofStock'=>1,'Error'=>1),
			)


		),
		'list'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'',
			'f_field'=>'',
			'f_value'=>'',
			'view'=>'general'
		),
		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Notes'=>1,'Changes'=>1,'Attachments'=>1)
		),
		'edit_charges'=>array(
		
			'f_field'=>'description',
			'f_value'=>'','f_show'=>false,
			'order'=>'description',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
		),
		'shipping_country'=>array(
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
		),
		'shipping_world_region'=>array(
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
		),
		'customers'=>array(
			'order'=>'id',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'view'=>'general',
			'elements'=>array(
				'all_contacts'=>array('Active'=>true,'Losing'=>true,'Lost'=>true),
				'contacts_with_orders'=>array('Active'=>true,'Losing'=>true,'Lost'=>true)
			),

			'where'=>'',
			'f_field'=>'customer name',
			'f_value'=>''


		),
		'department_sales'=>array(
			'order'=>'sales',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>100,
			'f_field'=>'code',
			'f_value'=>'',
		),
		'family_sales'=>array(
			'order'=>'sales',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>100,
			'f_field'=>'code',
			'f_value'=>'',

		),
		'product_sales'=>array(
			'order'=>'sales',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>100,
			'f_field'=>'code',
			'f_value'=>'',

		),
		'sales_history'=>array(
			'timeline_group'=>'week',
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,

		),
		'campaigns'=>array(
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,

			'f_field'=>'name',
			'f_value'=>'',
			'f_show'=>false,
			'elements'=>array()
		),
		'offers'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'elements'=>array('Order'=>1,'Department'=>1,'Family'=>1,'Product'=>1)
		),
		'edit_offers'=>array(
			'f_field'=>'name',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
		)

	),

	'department'=>array(
		'block_view'=>'families',
		'sales_sub_block_tipo'=>'plot_department_sales',

		'view'=>'general',
		'period'=>'all',
		'from'=>'',
		'to'=>'',
		'percentage'=>0,
		'mode'=>'all',
		'avg'=>'totals',
		'show_history'=>false,
		'editing'=>'details',
		'table_type'=>'list',
		'family_sales'=>array(

			'order'=>'sales',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>100,
			'f_field'=>'code',
			'f_value'=>'',



		),
		'product_sales'=>array(


			'order'=>'sales',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>100,
			'f_field'=>'code',
			'f_value'=>'',



		),
		'edit_pages'=>array(
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'view'=>'page_properties',
			'elements'=>array(
				'Product Description'=>1,
				'Family Catalogue'=>1,
				'Product Catalogue'=>1,
				'Other'=>1,

			)
		),
		'families'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',

			'elements'=>array('NoSale'=>0,'Discontinued'=>0,'Normal'=>1,'Discontinuing'=>1,'InProcess'=>0),



		),
		'products'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'restrictions'=>'',
			'elements_type'=>'type',
			'elements_stock_aux'=>'InWeb',
			'elements'=>array(
				'type'=>array('Historic'=>0,'Discontinued'=>0,'Private'=>0,'NoSale'=>0,'Sale'=>1),
				'web'=>array('ForSale'=>1,'OutofStock'=>1,'Discontinued'=>1,'Offline'=>0),
				'stock'=>array('Excess'=>1,'Normal'=>1,'Low'=>1,'VeryLow'=>1,'OutofStock'=>1,'Error'=>1),
			)



		),
		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Notes'=>1,'Changes'=>1,'Attachments'=>1)
		),
	
		'pages'=>array(
			'table_type'=>'list',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'elements'=>array(
				'Product Description'=>0,
				'Family Catalogue'=>0,
				'Product Catalogue'=>1,
				'Other'=>0,

			)


		),
		'sales_history'=>array(
			'timeline_group'=>'week',
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,

		),
		 'offers'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false),
		'edit_offers'=>array(
			'f_field'=>'name',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
		),
	),
	'family'=>array(
		'block_view'=>'products',
		'sales_sub_block_tipo'=>'plot_family_sales',
		'editing'=>'details',
		'edit_details_subtab'=>'code',
		'period'=>'all',
		'from'=>'',
		'to'=>'',
		'show_history'=>false,
		'edit_pages'=>array(
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'view'=>'page_properties',
			'elements'=>array(
				'Product Description'=>1,
				'Family Catalogue'=>1,
				'Product Catalogue'=>1,
				'Other'=>1,

			)
		),
		'edit_products'=>array(


			'view'=>'view_state',

			'table_type'=>'list',
			'show_only'=>'forsale',
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'restrictions'=>'',
			'elements_type'=>'type',
			'elements_stock_aux'=>'InWeb',
			'elements'=>array(
				'type'=>array('Historic'=>0,'Discontinued'=>0,'Private'=>0,'NoSale'=>0,'Sale'=>1),
				'web'=>array('ForSale'=>1,'OutofStock'=>1,'Discontinued'=>1,'Offline'=>0),
				'stock'=>array('Excess'=>1,'Normal'=>1,'Low'=>1,'VeryLow'=>1,'OutofStock'=>1,'Error'=>1),
			)



		),
		'products'=>array(
			'table_type'=>'list',
			'percentages'=>false,
			'view'=>'general',


			'id'=>1,
			'period'=>'all',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'edit'=>'details',
			'table_type'=>'list',
			'show_only'=>'forsale',
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'restrictions'=>'',
			'elements_type'=>'type',
			'elements_stock_aux'=>'InWeb',
			'elements'=>array(
				'type'=>array('Historic'=>0,'Discontinued'=>0,'Private'=>0,'NoSale'=>0,'Sale'=>1),
				'web'=>array('ForSale'=>1,'OutofStock'=>1,'Discontinued'=>1,'Offline'=>0),
				'stock'=>array('Excess'=>1,'Normal'=>1,'Low'=>1,'VeryLow'=>1,'OutofStock'=>1,'Error'=>1),
			),
			'history_type'=>'week'


		),

		'sales_history'=>array(
			'timeline_group'=>'week',
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,

		),

		'product_sales'=>array(

			'order'=>'sales',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>100,
			'f_field'=>'code',
			'f_value'=>'',



		),
		'history'=>array(
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Notes'=>1,'Changes'=>1,'Attachments'=>1)
		),
		'deals'=>array(
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
		),
		'pages'=>array(
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'elements'=>array(
				'Product Description'=>0,
				'Family Catalogue'=>1,
				'Product Catalogue'=>0,
				'Other'=>0,

			)


		),
		'offers'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false),
		'edit_offers'=>array(
			'f_field'=>'name',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
		),

	),
	'product_delete'=>array(
		'details'=>false,
		'view'=>'general',
		'percentages'=>false,
		'period'=>'year',
		'mode'=>'all',
		'avg'=>'totals',
		'mode'=>'all',//same_code,same_id,all
		'parent'=>'none',//store,department,family,none
		'restrictions'=>'forsale',
		'table'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>20,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'mode'=>'all',//same_code,same_id,all
			'parent'=>'none',//store,department,family,none
			'restrictions'=>'forsale'


		), ),
	'product'=>array(
		'block_view'=>'details',
		'sales_sub_block_tipo'=>'plot_product_sales',
		'period'=>'all',
		'from'=>'',
		'to'=>'',
		'show_history'=>false,

		'mode'=>'pid',
		'tag'=>1,
		'edit'=>'description',
		'edit_description_block'=>'properties',
		'display'=>array('details'=>0,'plot'=>1,'orders'=>1,'customers'=>1,'stock_history'=>0),
		'server'=>array(
			'tag'=>'',
			'order'=>'store',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>15,
			'where'=>'where true',
			'f_field'=>'id',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>''
		),
		'orders'=>array(
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>15,
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>''
		),
		'code_timeline'=>array(
			'code'=>'',
			'order'=>'from',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'where'=>'where true',
			'f_field'=>'description',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>''
		),
		'customers'=>array(
			'order'=>'dispatched',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>''
		),
		'parts'=>array(
			'order'=>'sku',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>50,
			'where'=>'where true',
			'f_field'=>'sku',
			'f_value'=>''
		),
		'history'=>array(
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Notes'=>1,'Changes'=>1,'Attachments'=>1)
		),
		'stock_history'=>array(
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>15,
			'where'=>'where true',
			'f_field'=>'id',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array()
		),
		'pages'=>array(
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'percentages'=>false,
			'view'=>'general',
			'period'=>'year',
			'percentage'=>0,
			'mode'=>'all',
			'avg'=>'totals',
			'elements'=>array(
				'Product Description'=>0,
				'Family Catalogue'=>1,
				'Product Catalogue'=>0,
				'Other'=>0,

			)


		),
		'sales_history'=>array(
			'timeline_group'=>'week',
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,

		),
 		'offers'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false),
		'edit_offers'=>array(
			'f_field'=>'name',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>100,
		),

	),
	'deals'=>array(
		'where'=>'where true',
		'f_field'=>'name',
		'f_value'=>'','f_show'=>false,
		'order'=>'name',
		'order_dir'=>'',
		'sf'=>0,
		'nr'=>25,
	),
	'part'=>array(

		'sales_sub_block_tipo'=>'plot_part_sales',
		'period'=>'all',
		'from'=>'',
		'to'=>'',
		'show_history'=>0,
		'edit'=>'description',
		'edit_description_block'=>'properties',
		'view'=>'description',
		'stock_history_block'=>'list',
		'product_breakdown'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'',
			'f_value'=>'','f_show'=>false,

		),
	
		'history'=>array(
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'',
			'f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Notes'=>1,'Changes'=>1,'Attachments'=>1,'Products'=>1)
		),
		'products'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'view'=>'links',
			'sf'=>0,
			'nr'=>50,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Notes'=>1,'Changes'=>1,'Attachments'=>1)

		),
		'supplier_products'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array()
		),
		'stock_history'=>array(
			'show_chart'=>1,
			'chart_output'=>'stock',
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'timeline_group'=>'week',
			'where'=>'where true',
			'f_field'=>'location',
			'f_value'=>'',
			'f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array()
		),
		'transactions'=>array(
			'view'=>'all_transactions',
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'where'=>'where true',
			'f_field'=>'note',
			'f_value'=>'',
			'f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array()
		),
			'sales_history'=>array(
			'timeline_group'=>'week',
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'',
			'f_value'=>'',
			'f_show'=>false,

		),
		'dn'=>array(

			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			'elements_type'=>'dispatch',
			'elements'=>array(
				'dispatch'=>array('Ready'=>1,'Picking'=>1,'Packing'=>1,'Done'=>1,'Send'=>1,'Returned'=>1),
				'type'=>array('Order'=>1,'Sample'=>1,'Donation'=>1,'Replacements'=>1,'Shortages'=>1)
			)

		)

	),


	'po'=>array(
		'id'=>'',
		'new'=>'',
		'new_data'=>array('num_items'=>0,'name'=>'','total'=>0),

		'new_timestamp'=>'',
		'items'=>array(
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'p.code',
			'f_value'=>'','f_show'=>false,
			'all_products'=>false,
			'all_products_supplier'=>false
		),
	),
	'location'=>array(
		'location'=>false,
		'edit'=>'description',
		'id'=>1,
		'view'=>'parts',
		'parts'=>array(
			'order'=>'sku',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'sku',
			'f_value'=>'','f_show'=>false,
			'elements'=>array()
		),
		'stock_history'=>array(
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>25,
			'from'=>'',
			'to'=>'',
			'where'=>'where true',
			'f_field'=>'author',
			'f_value'=>'','f_show'=>false,
			'elements'=>array()
		)
	),
	'report_to_delete'=>array(
		'tipo'=>'m',
		'y'=>date('Y'),
		'm'=>date('m'),
		'd'=>date('d'),
		'w'=>1,
		'activity'=>array('compare'=>'last_year','period'=>'week'),

		'sales'=>array(
			'store_keys'=>'all',
			'from'=>'',
			'to'=>'',
			'period'=>'',
			'order'=>'date',
			'order_dir'=>'desc',
			'invoice_type'=>'all',
			'dn_state'=>'all',
			'sf'=>0,
			'nr'=>25,
			'plot'=>'per_store',
			'plot_data'=>array('per_store'=>array(
					'category'=>'sales',
					'page'=>'plot.php',
					'period'=>'m'

				)
				,'per_category'=>array(
					'category'=>'sales',
					'page'=>'plot.php',
					'period'=>'m'
				)
			),
		),


		'products'=>array('store_keys'=>'all',
			'top'=>100,
			'criteria'=>'net_sales',
			'f_value'=>'',
			'f_show'=>false,
			'f_field'=>'code',
			'from'=>'',
			'to'=>''
		),
		'orders_in_process'=>array(
			'store_keys'=>'all',
			'sf'=>0,
			'nr'=>50,
			'f_value'=>'',
			'f_show'=>false,
			'f_field'=>'customer',
			'from'=>'',
			'to'=>'',
			'order'=>'date',
			'order_dir'=>'',
			'where'=>''
		)



	),


	'supplier'=>array(

		'sales_sub_block_tipo'=>'plot_supplier_sales',
		'period'=>'all',
		'details'=>false,
		'edit'=>'details',
		'action_after_create'=>'continue',
		'plot'=>'sales_month',
		'orders_view'=>'pos',
		'block_view'=>'products',
		'show_history'=>false,
		'from'=>'','to'=>'',
		'display'=>array('details'=>0,'history'=>0,'products'=>1,'po'=>0),
		'plot_options'=>array('weeks'=>'','from'=>'','to'=>'','months'=>''),
		'supplier_product_sales'=>array(


			'order'=>'sales',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>100,
			'f_field'=>'code',
			'f_value'=>'',



		),
		'supplier_product_purchases'=>array(
			'show_chart'=>1,
			'chart_output'=>'stock',
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'type'=>'week',
			'where'=>'where true',
			'f_field'=>'code',
			'f_value'=>'',
			'f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array()
		),
		'sales_history'=>array(
			'timeline_group'=>'week',
			'order'=>'date',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'',
			'f_value'=>'','f_show'=>false,

		),
		'supplier_products'=>array(
			'view'=>'general',
			'percentage'=>0,
			'period'=>'year',
			'order'=>'code',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>15,
			'where'=>'where true',
			'f_field'=>'p.code',
			'f_value'=>'','f_show'=>false,
			'from'=>'',
			'to'=>''
		),
		'porders'=>array(
			'order'=>'date',
			'view'=>'general',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			


		
	),
		'supplier_invoices'=>array(
			'order'=>'date',
			'view'=>'general',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			
	
			),
		'supplier_dns'=>array(
	
	
			'order'=>'date',
			'view'=>'general',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,
			'where'=>'where true',
			'f_field'=>'public_id',
			'f_value'=>'','f_show'=>false,
			
		
	),
		'history'=>array(
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>15,
			'where'=>'where true',
			'f_field'=>'notes',
			'f_value'=>'',
			'f_show'=>false,
			'from'=>'',
			'to'=>'',
			'elements'=>array('Notes'=>1,'Orders'=>1,'Changes'=>1,'Attachments'=>1,'Emails'=>1,'WebLog'=>0)
		)
	),

	


	'deals'=>array('table'=>array(
			'where'=>'where true',
			'f_field'=>'name',
			'f_value'=>'','f_show'=>false,
			'order'=>'name',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>25,



		)),
	

	'world'=>array(
		'view'=>'countries',
		'countries'=>array(
			'display'=>'all',
			'order'=>'name',
			'order_dir'=>'asc',
			'sf'=>0,
			'nr'=>20,
			'where'=>'where true',
			'f_field'=>'country_code',
			'f_value'=>'',
		),
		'wregions'=>array(
			'wregion_code'=>'',
			'display'=>'all',
			'order'=>'wregion_name',
			'order_dir'=>'asc',
			'sf'=>0,
			'nr'=>20,
			'where'=>'where true',
			'f_field'=>'wregion_code',
			'f_value'=>'',
		),
		'continents'=>array(
			'continent_code'=>'',
			'display'=>'all',
			'order'=>'continent_name',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>20,
			'where'=>'where true',
			'f_field'=>'continent_code',
			'f_value'=>'',
		),




	),




	'continent'=>array(
		'code'=>'',
		'wregions'=>array(
			'wregion_code'=>'',
			'display'=>'all',
			'order'=>'wregion_name',
			'order_dir'=>'asc',
			'sf'=>0,
			'nr'=>20,
			'where'=>'where true',
			'f_field'=>'wregion_code',
			'f_value'=>'',
		),
		'countries'=>array(
			'display'=>'all',
			'order'=>'country_name',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>20,
			'where'=>'where true',
			'f_field'=>'country_code',
			'f_value'=>'',
		),
	),

	'wregion'=>array(
		'code'=>'',

		'countries'=>array(
			'display'=>'all',
			'order'=>'name',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>20,
			'where'=>'where true',
			'f_field'=>'country_code',
			'f_value'=>'',
		),
	),
	'categories'=>array(

		'edit'=>'description',
		'parent_key'=>0,
		'subject'=>'',
		'subject_key'=>0,
		'store_key'=>0,
		'table'=>array(

			'sf'=>0,
			'nr'=>50,
			'f_value'=>'',
			'f_show'=>true,
			'f_field'=>'name',
			'from'=>'',
			'to'=>'',
			'order'=>'name',
			'order_dir'=>'',
			'where'=>''
		),
		'history'=>array(

			'sf'=>0,
			'nr'=>50,
			'f_value'=>'',
			'f_show'=>true,
			'f_field'=>'abstract',
			'from'=>'',
			'to'=>'',
			'order'=>'date',
			'order_dir'=>'',
			'where'=>''
		)
	),
	'search'=>array(

		'table'=>array(
			'order'=>'score',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>50,
			'where'=>'',
			'f_field'=>'subject',
			'f_value'=>'','f_show'=>false,
			'elements'=>array()
		)


	),
	'part_movements'=>array(
		'view'=>'movements'
	),

	'imported_records'=>array(
		'view'=>'upload_file',
		'imported_records'=>array(
			'f_field'=>'filename',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'date',
			'order_dir'=>'desc',
			'sf'=>0,
			'nr'=>50,

			'view'=>'general',

			'elements'=>array(
				'Uploading'=>1,'Review'=>1,'Queued'=>1,'InProcess'=>1,'Finished'=>1,'Cancelled'=>1

			)),
		'records'=>array(
			'f_field'=>'note',
			'f_value'=>'',
			'f_show'=>false,
			'order'=>'index',
			'order_dir'=>'',
			'sf'=>0,
			'nr'=>50,

			'view'=>'general',

			'elements'=>array(
				'Ignored'=>1,'Waiting'=>1,'Importing'=>1,'Imported'=>1,'Error'=>1,'Cancelled'=>1

			)),



		'customers'=>array('view'=>'overview')

	)

);


$yui_path="external_libs/yui/".$myconf['yui_version']."/build/";
$tmp_images_dir='app_files/pics/';

$customers_ids[0]='Id';
$customers_ids[1]='Act Id';
$customers_ids[2]='Post Code';

//overwrite configuration


$keys = array(
	"PATH_INFO",
	"PATH_TRANSLATED",
	"PHP_SELF",
	"REQUEST_URI",
	"SCRIPT_FILENAME",
	"SCRIPT_NAME",
	"QUERY_STRING"
);

// Works in linux
$file=preg_replace('/conf.php/','myconf.php',__FILE__);

if (file_exists($file)) {
	include_once 'myconf.php';
	if (isset($_myconf))
		foreach ($_myconf as $key=>$value) {
			if (array_key_exists($key,$myconf))
				$myconf[$key]=$value;
		}
}


?>
