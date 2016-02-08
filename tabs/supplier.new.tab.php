<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 8 February 2016 at 19:13:16 GMT+8, Kuala Lumpur , Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once 'utils/country_functions.php';

include_once 'utils/invalid_messages.php';
include_once 'class.Supplier.php';


$supplier=new Supplier(0);

$options_valid_tax_number=array(
	'Yes'=>_('Valid'), 'No'=>_('Not Valid'), 'Unknown'=>_('Unknown'), 'Auto'=>_('Check online'),
);

$options_yn=array(
	'Yes'=>_('Yes'), 'No'=>_('No')
);



asort($options_yn);

$object_fields=array(
	array(
		'label'=>_('Name, Ids'),
		'show_title'=>true,
		'fields'=>array(
			array(
				'id'=>'Supplier_Company_Name',
				'edit'=>'string',
				'value'=>htmlspecialchars($supplier->get('Supplier Company Name')),
				'formatted_value'=>$supplier->get('Company Name'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Company Name')),
				'required'=>false,
				'type'=>'value'
			),

			array(

				'id'=>'Supplier_Main_Contact_Name',
				'edit'=>'string',
				'value'=>htmlspecialchars($supplier->get('Supplier Main Contact Name')),
				'formatted_value'=>$supplier->get('Main Contact Name'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Main Contact Name')),
				'required'=>true,
				'type'=>'value'
			),
			array(
				'id'=>'Supplier_Registration_Number',
				'edit'=>'string',
				'value'=>$supplier->get('Supplier Registration Number'),
				'formatted_value'=>$supplier->get('Registration Number'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Registration Number')),
				'required'=>false,
				'type'=>'value'
			),
			array(
				'id'=>'Supplier_Tax_Number',
				'edit'=>'string',
				'value'=>$supplier->get('Supplier Tax Number'),
				'formatted_value'=>$supplier->get('Tax Number'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Tax Number')),
				'required'=>false,
				'type'=>'value'

			)
			

		)
	)
	
	,array(
		'label'=>_('Email'),
		'show_title'=>false,
		'fields'=>array(

			array(
				'id'=>'Supplier_Main_Plain_Email',
				'edit'=>'email',
				'value'=>$supplier->get('Supplier Main Plain Email'),
				'formatted_value'=>$supplier->get('Main Plain Email'),
				'server_validation'=>'check_for_duplicates',
				'label'=>ucfirst($supplier->get_field_label('Supplier Main Plain Email')),
				'invalid_msg'=>get_invalid_message('email'),
				'required'=>true,
				'type'=>'value'
			)

		)
	),

	array(
		'label'=>_('Telephones'),
		'show_title'=>false,
		'fields'=>array(

			array(
				'id'=>'Supplier_Main_Plain_Mobile',
				'edit'=>'telephone',
				'value'=>$supplier->get('Supplier Main Plain Mobile'),
				'formatted_value'=>$supplier->get('Main Plain Mobile'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Main Plain Mobile')),
				'invalid_msg'=>get_invalid_message('telephone'),
				'required'=>false,
				'type'=>'value'
			),
			array(



				'id'=>'Supplier_Main_Plain_Telephone',
				'edit'=>'telephone',
				'value'=>$supplier->get('Supplier Main Plain Telephone'),
				'formatted_value'=>$supplier->get('Main Plain Telephone'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Main Plain Telephone')),
				'invalid_msg'=>get_invalid_message('telephone'),
				'required'=>false,
				'type'=>'value'

			),
			array(
				'id'=>'Supplier_Main_Plain_FAX',
				'edit'=>'telephone',
				'value'=>$supplier->get('Supplier Main Plain FAX'),
				'formatted_value'=>$supplier->get('Main Plain FAX'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Main Plain FAX')),
				'invalid_msg'=>get_invalid_message('telephone'),
				'required'=>false,
				'type'=>'value'
			),

		)
	),

	array(
		'label'=>_('Address'),
		'show_title'=>false,
		'fields'=>array(

			array(
				'id'=>'Supplier_Contact_Address',
				'edit'=>'address',
				'value'=>htmlspecialchars($supplier->get('Supplier Contact Address')),
				'formatted_value'=>$supplier->get('Contact Address'),
				'label'=>ucfirst($supplier->get_field_label('Supplier Contact Address')),
				'invalid_msg'=>get_invalid_message('address'),
				'required'=>false,
				'type'=>'value'
			),


			

		)
	),



);
$smarty->assign('state', $state);
$smarty->assign('object', $supplier);


$smarty->assign('object_name', $supplier->get_object_name());


$smarty->assign('object_fields', $object_fields);
//$smarty->assign('new_object_label', _('View new employee'));
//$smarty->assign('new_object_request','employee/__key__');

//$store=new Store($state['parent_key']);
$smarty->assign('default_country', $account->get('Account Country 2 Alpha Code'));
$smarty->assign('preferred_countries', '"'.join('", "', preferred_countries($account->get('Account Country 2 Alpha Code'))).'"');


//$smarty->assign('js_code', file_get_contents('js/employee.new.js'));

$html=$smarty->fetch('new_object.tpl');

?>
