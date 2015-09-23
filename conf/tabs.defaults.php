<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 22 September 2015 12:09:42 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

$default_rrp_options=array(500,100,50,20);

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
	'customers_server'=>array(
		'view'=>'overview',
		'sort_key'=>'code',
		'sort_order'=>-1,
		'rpp'=>20,
		'rpp_options'=>$default_rrp_options,
		'f_field'=>'code'
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
		'elements_type'=>''
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
);


$tab_defaults_alias=array(
	'customers.list'=>'customers'
);




?>
