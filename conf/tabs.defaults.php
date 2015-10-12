<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 22 September 2015 12:09:42 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$default_rrp_options=array(500, 100, 50, 20);

$orders_elements=array(
	'source'=>array(
		'label'=>_('Source'),
		'items'=>array(
			'Internet'=>array('label'=>_('Website'), 'selected'=>true),
			'Call'=>array('label'=>_('Telephone'), 'selected'=>true),
			'Store'=>array('label'=>_('Showroom'), 'selected'=>true),
			'Email'=>array('label'=>_('Email'), 'selected'=>true),
			'Fax'=>array('label'=>_('Fax'), 'selected'=>true),
			'Other'=>array('label'=>_('Other'), 'selected'=>true)
		),

	)
	,
	'payment'=>array(
		'label'=>_('Payment'),
		'items'=>array(
			'Paid'=>array('label'=>_('Paid'), 'selected'=>true),
			'PartiallyPaid'=>array('label'=>_('Partially Paid'), 'selected'=>true),
			'Unknown'=>array('label'=>_('Unknown'), 'selected'=>true),
			'WaitingPayment'=>array('label'=>_('Waiting Payment'), 'selected'=>true),
			'NA'=>array('label'=>_('NA'), 'selected'=>true),
		)
	),
	'dispatch'=>array(
		'label'=>_('Dispatch state'),
		'items'=>array(
			'InProcessCustomer'=>array('label'=>_('Basket'), 'selected'=>true),
			'InProcess'=>array('label'=>_('In process'), 'selected'=>true),
			'Warehouse'=>array('label'=>_('Warehouse'), 'selected'=>true),
			'Dispatched'=>array('label'=>_('Dispatched'), 'selected'=>true),
			'Cancelled'=>array('label'=>_('Cancelled'), 'selected'=>false),
			'Suspended'=>array('label'=>_('Suspended'), 'selected'=>false)),
	),
	'type'=>array('label'=>_('Payment'),
		'items'=>array(
		'Order'=>array('label'=>_('Order'), 'selected'=>true),
		 'Sample'=>array('label'=>_('Sample'), 'selected'=>true),
		 'Donation'=>array('label'=>_('Donation'), 'selected'=>true), 
		 'Other'=>array('label'=>_('Other'), 'selected'=>true),
		 )
	)
);

$tab_defaults=array(

	'customers'=>array(
		'view'=>'overview',
		'sort_key'=>'formated_id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'name'
	),
	'customers.lists'=>array(
		'view'=>'overview',
		'sort_key'=>'creation_date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'name'
	),
	'customers.categories'=>array(
		'view'=>'overview',
		'sort_key'=>'code',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code'
	),
	'customer.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	),
	'customer.orders'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'number',
		'from'=>'',
		'to'=>'',
		'period'=>'all',
		'elements_type'=>''
	),
	'customers_server'=>array(
		'view'=>'overview',
		'sort_key'=>'code',
		'sort_order'=>-1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'percentages'=>0
	),
	'orders'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'customer',
		'from'=>'',
		'to'=>'',
		'period'=>'ytd',
		'elements_type'=>'dispatch',
		'elements'=>$orders_elements

	),
	'order.items'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>1000,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',

	),
	'order.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	),
	'invoices'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'customer',
		'from'=>'',
		'to'=>'',
		'period'=>'ytd',
		'elements_type'=>''
	),
	'delivery_notes'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'customer',
		'from'=>'',
		'to'=>'',
		'period'=>'ytd',
		'elements_type'=>''
	),
	'orders_server'=>array(
		'view'=>'overview',
		'sort_key'=>'code',
		'sort_order'=>-1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'percentages'=>0
	),
	'stores'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

	), 'store.departments'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

	), 'store.families'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

	), 'department.families'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

	), 'store.products'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

	), 'department.products'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

	), 'family.products'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

	), 'family.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	), 'product.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	),
	'product.orders'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'customer',
		'from'=>'',
		'to'=>'',
		'period'=>'ytd',
		'elements_type'=>''
	), 'websites'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code'
	), 'website.pages'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

	), 'marketing_server'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',
	),
	'suppliers'=>array(
		'view'=>'overview',
		'sort_key'=>'formated_id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'name',
		'f_period'=>'ytd',
	),
	'suppliers.lists'=>array(
		'view'=>'overview',
		'sort_key'=>'creation_date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'name'
	),
	'suppliers.categories'=>array(
		'view'=>'overview',
		'sort_key'=>'code',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code'
	), 'warehouses'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
	), 'part.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	),
	'warehouse.locations'=>array(
		'view'=>'overview',
		'sort_key'=>'code',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code'
	),
	'warehouse.replenishments'=>array(
		'view'=>'overview',
		'sort_key'=>'location',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'location'
	), 'warehouse.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	),
	'inventory.parts'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'reference'
	), 'employees'=>array(
		'view'=>'employees',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'name'
	),
	'reports'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
	),
	'users.staff.users'=>array(
		'view'=>'privilegies',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'handle'
	), 'staff.user.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	), 'staff.user.login_history'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'ip',
		'f_period'=>'all',

	), 'users.staff.login_history'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'handle',
		'f_period'=>'all',

	)

);


$tab_defaults_alias=array(
	'customers.list'=>'customers'
);




?>
