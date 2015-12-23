<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 22 September 2015 12:09:42 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/


$default_rrp_options=array(500, 100, 50, 20);
$customers_elements=array(
	'orders'=>array(
		'label'=>_('Orders'),
		'items'=>array(
			'Yes'=>array('label'=>_('With orders'), 'selected'=>true),
			'No'=>array('label'=>_('Without orders'), 'selected'=>true),
		)


	),
	'activity'=>array(
		'label'=>_('Active/Lost'),
		'items'=>array(
			'Active'=>array('label'=>_('Active'), 'selected'=>true),
			'Losing'=>array('label'=>_('Losing'), 'selected'=>true),
			'Lost'=>array('label'=>_('Lost'), 'selected'=>true),
		)


	),
	'type'=>array(
		'label'=>_('Type'),
		'items'=>array(
			'Normal'=>array('label'=>_('Normal'), 'selected'=>true),
			'VIP'=>array('label'=>_('VIP'), 'selected'=>true),
			'Partner'=>array('label'=>_('Partner'), 'selected'=>true),
			'Staff'=>array('label'=>_('Staff'), 'selected'=>true),
		)
	),
	'location'=>array(
		'label'=>_('Location'),
		'items'=>array(
			'Domestic'=>array('label'=>_('Domestic'), 'selected'=>true),
			'Export'=>array('label'=>_('Export'), 'selected'=>true),

		)


	)
);
$customer_history_elements=array(
	'type'=>array(
		'label'=>_('Type'),
		'items'=>array(
			'Notes'=>array('label'=>_('Notes'), 'selected'=>true),
			'Orders'=>array('label'=>_('Orders'), 'selected'=>true),
			'Changes'=>array('label'=>_('Changes'), 'selected'=>true),
			'Attachments'=>array('label'=>_('Attachments'), 'selected'=>true),
			'WebLog'=>array('label'=>_('WebLog'), 'selected'=>true),
			'Emails'=>array('label'=>_('Emails'), 'selected'=>true)
		),

	)
);
$orders_elements=array(
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
	),
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

	),
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


);
$invoices_elements=array(
	'type'=>array(
		'label'=>_('Type'),
		'items'=>array(
			'Invoice'=>array('label'=>_('Invoice'), 'selected'=>true),
			'Refund'=>array('label'=>_('Refund'), 'selected'=>true),
		)
	),
	'payment_state'=>array('label'=>_('Payment state'),
		'items'=>array(
			'Yes'=>array('label'=>_('Paid'), 'selected'=>true),
			'Partially'=>array('label'=>_('Partially paid'), 'selected'=>true),
			'No'=>array('label'=>_('Waiting payment'), 'selected'=>true),
		)
	)

);

$delivery_notes_elements=array(
	'dispatch'=>array(
		'label'=>_('Dispatch state'),
		'items'=>array(
			'Ready'=>array('label'=>_('Ready'), 'selected'=>true),
			'Picking'=>array('label'=>_('Picking'), 'selected'=>true),
			'Packing'=>array('label'=>_('Packing'), 'selected'=>true),
			'Done'=>array('label'=>_('Done'), 'selected'=>true),
			'Send'=>array('label'=>_('Send'), 'selected'=>true),
			'Returned'=>array('label'=>_('Returned'), 'selected'=>true),
		)
	),
	'type'=>array('label'=>_('Type'),
		'items'=>array(
			'Order'=>array('label'=>_('Order'), 'selected'=>true),
			'Sample'=>array('label'=>_('Sample'), 'selected'=>true),
			'Donation'=>array('label'=>_('Donation'), 'selected'=>true),
			'Replacements'=>array('label'=>_('Replacements'), 'selected'=>true),
			'Shortages'=>array('label'=>_('Shortages'), 'selected'=>true),
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
		'f_field'=>'name',
		'elements_type'=>each($customers_elements)['key'],
		'elements'=>$customers_elements
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
		'f_field'=>'note',
		'elements_type'=>each($customer_history_elements)['key'],
		'elements'=>$customer_history_elements
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
		'elements_type'=>each($orders_elements)['key'],
		'elements'=>$orders_elements
	),
	'customer.marketing.favourites'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',
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
	'customer.marketing.products'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

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
		'period'=>'all',
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
	'order.invoices'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',

	),
	'order.delivery_notes'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',

	),
	'delivery_note.invoices'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',

	),
	'delivery_note.orders'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',

	),
	'delivery_note.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	),
	'delivery_note.items'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>1000,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',

	),
	'invoice.items'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>1000,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',

	),
	'invoice.orders'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',

	),
	'invoice.delivery_notes'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',

	),
	'invoice.history'=>array(
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
		'period'=>'all',
		'elements_type'=>each($invoices_elements)['key'],
		'elements'=>$invoices_elements
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
		'period'=>'all',
		'elements_type'=>each($delivery_notes_elements)['key'],
		'elements'=>$delivery_notes_elements
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

	),
	'store.departments'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

	),
	'store.families'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

	),
	'department.families'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

	),
	'store.products'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

	),
	'department.products'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

	),
	'family.products'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

	),
	'family.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	),
	'product.history'=>array(
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
		'elements_type'=>each($orders_elements)['key'],
		'elements'=>$orders_elements
	),
	'websites'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code'
	),
	'website.pages'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
		'f_period'=>'ytd',

	),
	'website.favourites.customers'=>array(
		'view'=>'overview',
		'sort_key'=>'formated_id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'name',
		'elements_type'=>each($customers_elements)['key'],
		'elements'=>$customers_elements
	),
	'website.search.queries'=>array(
		'view'=>'overview',
		'sort_key'=>'number',
		'sort_order'=>-1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'query',

	),
	'website.search.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>-1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'query',

	),
	'website.users'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'handle',

	),
	'page.users'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'handle',

	),
	'website.user.login_history'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'ip',
		'f_period'=>'all',

	),
	'website.user.pageviews'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'page',
		'f_period'=>'all',

	),
	'marketing_server'=>array(
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
	),
	'warehouses'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
	),
	'part.history'=>array(
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
	),
	'warehouse.history'=>array(
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
	),
	'operatives'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'name'
	),
	'batches'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'id'
	),
	'manufacture_tasks'=>array(
		'view'=>'overview',
		'sort_key'=>'name',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'name'
	),

	'overtimes'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'reference'
	),
	'overtime.timesheets'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'alias',

	),
	'overtime.employees'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'alias',

	),
	'overtimes'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'reference'
	),
	'employees'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'name'
	),
	'exemployees'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'name'
	),
	'contractors'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'name'
	),

	'timesheets.months'=>array(
		'view'=>'overview',
		'sort_key'=>'month',
		'sort_order'=>-1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',
		'year'=>strtotime('now'),

	),
	'timesheets.weeks'=>array(
		'view'=>'overview',
		'sort_key'=>'month',
		'sort_order'=>-1,
		'rpp'=>100,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',
		'year'=>strtotime('now'),

	),
	'timesheets.days'=>array(
		'view'=>'overview',
		'sort_key'=>'month',
		'sort_order'=>-1,
		'rpp'=>500,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',
		'year'=>strtotime('now'),

	),
	'timesheets.timesheets'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'alias',
	),
	'timesheets.employees'=>array(
		'view'=>'overview',
		'sort_key'=>'name',
		'sort_order'=>1,
		'rpp'=>100,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'name',

	),
	'fire'=>array(
		'view'=>'overview',
		'sort_key'=>'status',
		'sort_order'=>-1,
		'rpp'=>100,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'name',

	),

	'employees.timesheets'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'name',
		'from'=>'',
		'to'=>'',
		'period'=>'all',
	),
	'employees.timesheets.records'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',
		'from'=>'',
		'to'=>'',
		'period'=>'all',

	),
	'employee.timesheets.records'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',
		'from'=>'',
		'to'=>'',
		'period'=>'all',

	),
	'employee.timesheets'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',
		'from'=>'',
		'to'=>'',
		'period'=>'all',
	),
	'employee.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	),
	'employee.attachments'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'caption'
	),

	'timesheet.records'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',


	),
	'employee.attachment.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	),

	'contractor.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	),
	'reports'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code',
	),
	'account.users'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',
	),
	'users.staff.groups'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',
	),
	'payment_service_providers'=>array(
		'view'=>'overview',
		'sort_key'=>'code',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code'

	),
	'payments'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>-1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'reference'


	),
	'payment_service_provider.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	),
	'payment_service_provider.accounts'=>array(
		'view'=>'overview',
		'sort_key'=>'code',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code'
	),
	'payment_service_provider.payments'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>-1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'reference'
	),

	'payment_account.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	),
	'payment_account.payments'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>-1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'reference'
	),
	'payment.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	),

	'users.staff.users'=>array(
		'view'=>'privilegies',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'handle'
	),
	'staff.user.history'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'note'
	),
	'staff.user.login_history'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'ip',
		'from'=>'',
		'to'=>'',
		'period'=>'all',

	),
	'staff.user.api_keys'=>array(
		'view'=>'overview',
		'sort_key'=>'formated_id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',

	),
	'staff.user.api_key.requests'=>array(
		'view'=>'overview',
		'sort_key'=>'date',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',
		'from'=>'',
		'to'=>'',
		'period'=>'all',

	),
	'users.staff.login_history'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'handle',
		'f_period'=>'all',

	),
	'billingregion_taxcategory'=>array(
		'view'=>'overview',
		'sort_key'=>'billing_region',
		'sort_order'=>1,
		'rpp'=>100,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',
		'from'=>'',
		'to'=>'',
		'period'=>'last_m',
	),
	
	'billingregion_taxcategory.invoices'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',

	),
	'billingregion_taxcategory.refunds'=>array(
		'view'=>'overview',
		'sort_key'=>'id',
		'sort_order'=>1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'',

	),

);


$tab_defaults_alias=array(
	'customers.list'=>'customers'
);




?>
