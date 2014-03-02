<?php


require_once 'common.php';

require_once 'class.Product.php';
require_once 'class.Department.php';
require_once 'class.Family.php';
require_once 'class.Category.php';
require_once 'class.Order.php';
require_once 'class.Location.php';
require_once 'class.PartLocation.php';
require_once 'class.Image.php';
require_once 'class.SupplierProduct.php';
require_once 'class.Supplier.php';
require_once 'class.Part.php';

require_once 'ar_edit_common.php';
if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];


switch ($tipo) {

case('delete_info_sheet_file'):
	require_once 'class.Attachment.php';
	$data=prepare_values($_REQUEST,array(
			'pid'=>array('type'=>'key'),
		));


	delete_info_sheet_attachment($data);
	break;
case('add_info_sheet_file'):
	require_once 'class.Attachment.php';
	$data=prepare_values($_REQUEST,array(
			'pid'=>array('type'=>'key'),
		));
	$data['field']='Part Info Sheet Attachment Bridge Key';
	$data['caption']='';

	add_info_sheet_attachment($data);
	break;

case('update_deal_metadata'):
	$data=prepare_values($_REQUEST,array(
			'name'=>array('type'=>'string'),
			'terms'=>array('type'=>'string'),
			'allowances'=>array('type'=>'string'),
			'deal_metadata_key'=>array('type'=>'key'),
		));

	update_deal_metadata($data);
	break;
case('update_deal'):
	$data=prepare_values($_REQUEST,array(
			'name'=>array('type'=>'string'),
			'description'=>array('type'=>'string'),
			'deal_key'=>array('type'=>'key'),
		));

	update_deal($data);
	break;

case('update_deal_metadata_status'):
	$data=prepare_values($_REQUEST,array(
			'value'=>array('type'=>'string'),
			'deal_metadata_key'=>array('type'=>'key'),
		));

	update_deal_metadata_status($data);
	break;

case('location_audit'):
	$data=prepare_values($_REQUEST,array(
			'scope'=>array('type'=>'string'),
			'scope_key'=>array('type'=>'key'),
		));

	location_audit($data);
	break;
case('new_parts_list'):

	$data=prepare_values($_REQUEST,array(
			'awhere'=>array('type'=>'json array'),
			'parent_key'=>array('type'=>'key'),
			'list_name'=>array('type'=>'string'),
			'list_type'=>array('type'=>'enum',
				'valid values regex'=>'/static|Dynamic/i'
			)
		));


	new_parts_list($data);
	break;
case('delete_part_location_transaction'):
	$data=prepare_values($_REQUEST,array(
			'subject_key'=>array('type'=>'key'),
			'table_id'=>array('type'=>'string','optional'=>true),
			'recordIndex'=>array('type'=>'string','optional'=>true),
		));
	delete_part_location_transaction($data);
	break;

case('part_transactions'):
	part_transactions();
	break;
case('edit_charge'):
	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'id'=>array('type'=>'key'),
		));
	edit_charge($data);
	break;


case('edit_location'):
	$data=prepare_values($_REQUEST,array(
			'location_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')

		));

	edit_location($data);
	break;

case('create_product'):

	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')

		));

	create_product($data);
	break;
case('delete_parts_list'):
	$data=prepare_values($_REQUEST,array(
			'key'=>array('type'=>'key'),


		));
	delete_parts_list($data);
	break;
case('edit_part_custom_field'):
	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'okey'=>array('type'=>'string'),
			'sku'=>array('type'=>'key'),
		));
	edit_part($data);


case('edit_part_list'):
	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'json array'),
			'key'=>array('type'=>'number'),
			'pid'=>array('type'=>'key'),
		));
	edit_part_list($data);
	break;




case('edit_part_new_product'):
	if (isset($_REQUEST['part_sku']))
		edit_part_new_product($_REQUEST['part_sku']);
	break;
case('delete_part_new_product'):
	if (isset($_REQUEST['part_sku']))
		delete_part_new_product($_REQUEST['part_sku']);
	break;

case('add_part_new_product'):

	if (isset($_REQUEST['sku']))
		add_part_new_product($_REQUEST['sku']);

	break;
case('part_list'):
	list_parts_in_product();
	break;
case('edit_charges'):
	list_charges_for_edition();
	break;
case('edit_campaigns'):
	list_campaigns_for_edition();
	break;
case('edit_deals'):
	list_deals_for_edition();
	break;



case('delete_family'):
	$data=prepare_values($_REQUEST,array(
			'delete_type'=>array('type'=>'string'),
			'family_key'=>array('type'=>'key')
		));
	delete_family($data);
	break;
case('delete_product'):
	delete_product();
	break;
case('delete_store'):
	$data=prepare_values($_REQUEST,array(
			'subject_key'=>array('type'=>'key'),
			'table_id'=>array('type'=>'numeric','optional'=>true),
			'recordIndex'=>array('type'=>'numeric','optional'=>true)
		));
	delete_store($data);
	break;
case('delete_department'):
	delete_department();
	break;
case('edit_family'):
case('edit_family_general_description'):

	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'id'=>array('type'=>'key')
		));

	edit_family($data);
	break;
case('edit_family_department'):
	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'id'=>array('type'=>'key')
		));

	edit_family_department($data);
	break;
case('edit_product_advanced'):
	edit_product_multi();
	break;
case('edit_product_price'):
case('edit_product_properties'):
case('edit_product_units'):
case('edit_product_description'):
case('edit_product'):
case('edit_product_health_and_safety'):
case('edit_product_general_description'):

	edit_product();
	break;

case('edit_department'):
	edit_department();
	break;
case('edit_store'):
case('edit_invoice'):
	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'id'=>array('type'=>'key')
		));
	edit_store($data);

	break;
case('edit_deal'):
	edit_deal();
	break;

case('create_store'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array'),
		));
	create_store($data);
	break;
case('new_department'):
	create_department();
	break;
case('create_family'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array'),
			'parent_key'=>array('type'=>'key')
		));
	create_family($data);
	break;
case('edit_departments'):
	list_departments_for_edition();

	break;
case('edit_stores_list'):

case('edit_stores'):
	list_stores_for_edition();

	break;
case('edit_families'):
	list_families_for_edition();

	break;
case('edit_products'):
	list_products_for_edition();
	break;
case('edit_supplier_product_part'):
	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'sppl_key'=>array('type'=>'key'),
			'key'=>array('type'=>'string')
		));
	edit_supplier_product_part($data);

	break;
default:

	$response=array('state'=>404,'resp'=>_('Operation not found'));
	echo json_encode($response);

}





function create_store($data) {
	global $editor;




	$locale=$data['values']['Store Locale'];
	$country=new Country('code',$data['values']['Country Code']);
	if (!$country->id) {
		$response=array('state'=>400,'msg'=>'wrong country');
		echo json_encode($response);
		exit;

	}




	$data['values']['Store Currency Code']=$country->data['Country Currency Code'];
	$data['values']['Store Tax Country Code']=$country->data['Country Code'];
	$data['values']['Store Home Country Code 2 Alpha']=$country->data['Country 2 Alpha Code'];
	$data['values']['Store Home Country Name']=$country->data['Country Name'];
	$data['values']['Store Valid From']=gmdate('Y-m-d H:i:s');




	$sql=sprintf("select `Tax Category Code` from `Tax Category Dimension` where  `Tax Category Default`='Yes' and `Tax Category Country Code`=%s ",
		prepare_mysql($country->data['Country Code']));
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		$data['values']['Store Tax Category Code']=$row['Tax Category Code'];
	}
	$data['values']['editor']=$editor;

	$store=new Store('find',$data['values'],'create');

	if ($store->new) {
		$response=array('state'=>200,'store_key'=>$store->id,'action'=>'created');

	}elseif ($store->id) {
		$response=array('state'=>400,'store_key'=>$store->id,'action'=>'found');

	}else {
		$response=array('state'=>400,'msg'=>$store->msg);

	}
	echo json_encode($response);

}

function create_department() {
	global $editor;
	if (isset($_REQUEST['name'])  and  isset($_REQUEST['code'])   ) {
		$store_key=$_SESSION['state']['store']['id'];
		$department=new Department('find',array(
				'Product Department Code'=>$_REQUEST['code']
				,'Product Department Name'=>$_REQUEST['name']
				,'Product Department Store Key'=>$store_key
				,'editor'=>$editor
			),'create');
		if (!$department->new) {
			$state='400';
		} else {
			$state='200';
		}
		$response=array('state'=>$state,'msg'=>$department->msg);
	} else
		$response=array('state'=>400,'resp'=>_('Error'));
	echo json_encode($response);
}


function create_family($data) {
	global $editor;

	if (array_key_exists('Product Family Name',$data['values'])
		and  array_key_exists('Product Family Code',$data['values'])
		and  array_key_exists('Product Family Special Characteristic',$data['values'])
		and  array_key_exists('Product Family Description',$data['values'])

	) {
		$department_key=$data['parent_key'];

		$department=new Department($department_key);

		$family=new Family('create',array(

				'Product Family Code'=>$data['values']['Product Family Code'],
				'Product Family Name'=>$data['values']['Product Family Name'],
				'Product Family Description'=>$data['values']['Product Family Description'],
				'Product Family Special Characteristic'=>$data['values']['Product Family Special Characteristic'],
				'Product Family Main Department Key'=>$department->id,
				'Product Family Store Key'=>$department->data['Product Department Store Key'],
				'editor'=>$editor
			));
		if (!$family->new) {

			$response=array('state'=>200,'msg'=>$family->msg,'action'=>'found','object_key'=>$family->id);
		} else {

			$response=array('state'=>200,'msg'=>$family->msg,'action'=>'created');
		}




	} else
		$response=array('state'=>400,'msg'=>_('Error'));
	echo json_encode($response);
}




function delete_part_new_product($sku) {

	unset($_SESSION['state']['new_product']['parts'][$sku]);
	print 'Ok';


}


function delete_family($data) {





	if (!isset($data['delete_type'])  or !($data['delete_type']=='delete' or $data['delete_type']=='discontinue'  )  ) {
		$response=array('state'=>400,'msg'=> 'Error: delete type no supplied');
		echo json_encode($response);
	}
	$id=$data['family_key'];
	$family=new Family($id);

	if ($data['delete_type']=='delete') {

		$family->delete();
	} else if ($data['delete_type']=='discontinue') {
			$family->discontinue();
		}
	if ($family->deleted) {
		$response=array('state'=>200,'msg'=>$family->msg,'action'=>'deleted');
	} else {
		$response=array('state'=>400,'msg'=>$family->msg);
	}
	echo json_encode($response);
}
function delete_store($data) {



	$store=new Store($data['subject_key']);
	$store->delete();
	if ($store->deleted) {
		$response=array('state'=>200,
			'action'=>'deleted',
			'table_id'=>(isset($data['table_id'])?$data['table_id']:''),
			'recordIndex'=>(isset($data['recordIndex'])?$data['recordIndex']:''),
		);
	}else {
		$response=array('state'=>400,'msg'=>$store->msg,);
	}
	echo json_encode($response);


}

function close_store($data) {

	$store=new Store($data['store_key']);
	$store->close();
	if ($store->closed) {
		$response=array('state'=>200,'action'=>'closed');
	}else {
		$response=array('state'=>400,'msg'=>$store->msg,);
	}
	echo json_encode($response);


}


function delete_department() {
	if (!isset($_REQUEST['id']))
		return 'Error: no department key';
	if (!is_numeric($_REQUEST['id']) or $_REQUEST['id']<=0 )
		return 'Error: wrong department id';
	if (!isset($_REQUEST['delete_type'])  or !($_REQUEST['delete_type']=='delete' or $_REQUEST['delete_type']=='discontinue'  )  )
		return 'Error: delete type no supplied';

	$id=$_REQUEST['id'];
	$department=new Department($id);

	if ($_REQUEST['delete_type']=='delete') {

		$department->delete();
	} else if ($_REQUEST['delete_type']=='discontinue') {
			$department->close();
		}
	if ($department->deleted) {
		$response=array('state'=>200,'msg'=>$department->msg,'action'=>'deleted');
	} else {
		$response=array('state'=>400,'msg'=>$department->msg);
	}
	echo json_encode($response);


}

function edit_charge($data) {
	include_once 'class.Charge.php';
	global $editor;
	$charge=new Charge($data['id']);




	$charge->editor=$editor;
	$translator=array(
		'name'=>'Charge Name',
		'description'=>'Charge Description',
		'charge'=>'Charge Metadata',
	);
	$key=$translator[$data['key']];

	$charge->update(array($key=>$data['newvalue']));

	if ($charge->updated) {
		$response= array('state'=>200,'newvalue'=>$charge->new_value,'key'=>$data['key']);

	} else {
		$response= array('state'=>400,'msg'=>$charge->msg,'key'=>$data['key']);
	}
	echo json_encode($response);
}

function edit_store($data) {

	$store=new Store($data['id']);
	global $editor;
	$store->editor=$editor;

	$key_dic=array(
		'vat_number'=>'Store VAT Number',
		'company_number'=>'Store Company Number',
		'company_name'=>'Store Company Name',
		'msg_header'=>'Store Invoice Message Header',
		'msg'=>'Store Invoice Message',
		'name'=>'Store Name',
		'code'=>'Store Code',
		'contact'=>'Store Contact Name',

	);



	if (array_key_exists($data['key'],$key_dic))
		$key=$key_dic[$data['key']];
	else
		$key=$data['key'];


	$store->update(array($key=>stripslashes(urldecode($data['newvalue']))));//,stripslashes(urldecode($_REQUEST['oldvalue'])));

	if ($store->updated) {
		$response= array('state'=>200,'newvalue'=>$store->new_value,'key'=>$data['key']);

	} else {
		$response= array('state'=>400,'msg'=>$store->msg,'key'=>$data['key']);
	}
	echo json_encode($response);
}
function edit_department() {
	$department=new Department($_REQUEST['id']);
	global $editor;
	$department->editor=$editor;

	$department->update($_REQUEST['key'],stripslashes(urldecode($_REQUEST['newvalue']))

	);

	//   $response= array('state'=>400,'msg'=>print_r($_REQUEST);
	//echo json_encode($response);
	// exit;
	if ($department->updated) {
		$response= array('state'=>200,'newvalue'=>$department->new_value,'key'=>$_REQUEST['key']);

	} else {
		$response= array('state'=>400,'msg'=>$department->msg,'key'=>$_REQUEST['key']);
	}
	echo json_encode($response);

}
function edit_product() {
	$product=new product('pid',$_REQUEST['pid']);
	global $editor;
	$product->editor=$editor;

	$key=$_REQUEST['key'];
	$_key=$key;
	$_key=preg_replace('/\s/','_',$_key);


	$newvalue=$_REQUEST['newvalue'];

	$translator=array(
		'name'=>'Product Name',
		'sdescription'=>'Product Special Characteristic',
		'special_characteristic'=>'Product Special Characteristic',
		'description'=>'Product Description',
		'Barcode_Type'=>'Product Barcode Type',
		'Barcode_Data_Source'=>'Product Barcode Data Source',
		'Barcode_Data'=>'Product Barcode Data',

		'price'=>'Product Price',
		'unit_price'=>'Product Unit Price',
		'margin'=>'Product Margin',
		'unit_rrp'=>'Product RRP Per Unit',
		'rrp'=>'Product RRP Per Unit',

		'sales_type'=>'Product Sales Type',

		'Product_Package_Type'=>'Product Package Type',
		'Product_XHTML_Unit_Weight'=>'Product XHTML Unit Weight',
		'Product_XHTML_Package_Weight'=>'Product XHTML Package Weight',
		'Product_XHTML_Unit_Dimensions'=>'Product XHTML Unit Dimensions',
		'Product_XHTML_Package_Dimensions'=>'Product XHTML Package Dimensions',

		'general_description'=>'Product Description',
		'family_key'=>'Product Family Key',
		'units_per_case'=>'Product Units Per Case',
		'unit_type'=>'Product Unit Type',
		'link_health_and_safety'=>'Product Use Part H and S',
		'link_tariff'=>'Product Use Part Tariff Data',
		'link_properties'=>'Product Use Part Properties',
		'link_pictures'=>'Product Use Part Pictures'
	);

	if (array_key_exists($key,$translator))
		$key=$translator[$key];
	else
		$key=$key;


	if ($key=='web_configuration' and  ($newvalue=='Private Sale'  or $newvalue=='Not For Sale' or $newvalue=='Public Sale') ) {

		$key='Product Sales Type';
	}


	$product->update(array($key=>stripslashes(urldecode($newvalue))));




	if ($product->updated) {
		$response= array('state'=>200,'newvalue'=>$product->new_value,'newdata'=>$product->new_data,'key'=>$_key);

		if ($_key=='Product_Use_Part_H_and_S') {
			$response['data']=array(
				'Product_UN_Number'=>$product->data['Product UN Number'],
				'Product_UN_Class'=>$product->data['Product UN Class'],
				'Product_Packing_Group'=>$product->data['Product Packing Group'],
				'Product_Proper_Shipping_Name'=>$product->data['Product Proper Shipping Name'],
				'Product_Hazard_Indentification_Number'=>$product->data['Product Hazard Indentification Number'],
				'product_health_and_safety'=>$product->data['Product Health And Safety']

			);

			$response['xhtml_part_links']=$product->get_xhtml_part_links('Product Use Part H and S');
		}
		elseif ($_key=='Product_Use_Part_Tariff_Data') {
			$response['data']=array(
				'Product_Tariff_Code'=>$product->data['Product Tariff Code'],
				'Product_Duty_Rate'=>$product->data['Product Duty Rate'],


			);

			$response['xhtml_part_links']=$product->get_xhtml_part_links('Product Use Part Tariff Data');
		}
		elseif ($_key=='Product_Use_Part_Properties') {
			$response['data']=array(
				'Product_XHTML_Package_Weight'=>$product->data['Product XHTML Package Weight'],
				'Product_XHTML_Unit_Weight'=>$product->data['Product XHTML Unit Weight'],
				'Product_XHTML_Package_Dimensions'=>$product->data['Product XHTML Package Dimensions'],
				'Product_XHTML_Unit_Dimensions'=>$product->data['Product XHTML Unit Dimensions'],
				'Product_Package_Type'=>$product->data['Product Package Type'],


			);
			$response['xhtml_part_links']=$product->get_xhtml_part_links('Product Use Part Properties');

			$response['ratio']=$product->data['Product Part Ratio'];
			$response['units_ratio']=$product->data['Product Part Units Ratio'];

		}

	} else {
		$response= array('state'=>400,'msg'=>$product->msg,'key'=>$key);
	}
	echo json_encode($response);
}

function edit_category() {
	$category=new Category('category_key',$_REQUEST['category_key']);
	global $editor;
	$category->editor=$editor;
	$key=$_REQUEST['key'];
	if ($key=='Attach') {
		// print_r($_FILES);
		$note=stripslashes(urldecode($_REQUEST['newvalue']));
		$target_path = "uploads/".'attach_'.date('U');
		$original_name=$_FILES['testFile']['name'];
		$type=$_FILES['testFile']['type'];
		$data=array('Caption'=>$note,'Original Name'=>$original_name,'Type'=>$type);

		if (move_uploaded_file($_FILES['testFile']['tmp_name'],$target_path )) {
			$category->add_attach($target_path,$data);

		}
	} else {



		$key_dic=array(
			'name'=>'Category Code'
			,'id'=>'Category Key'
			// ,'alias'=>'Staff Alias'
			// ,'type'=>'Staff Type'


		);
		if (array_key_exists($_REQUEST['key'],$key_dic))
			$key=$key_dic[$_REQUEST['key']];

		$update_data=array($key=>stripslashes(urldecode($_REQUEST['newvalue'])));
		$category->update($update_data);
	}


	if ($category->updated) {
		$response= array('state'=>200,'newvalue'=>$category->new_value,'key'=>$_REQUEST['key']);

	} else {
		$response= array('state'=>400,'msg'=>$category->msg,'key'=>$_REQUEST['key']);
	}
	echo json_encode($response);
}


function edit_subcategory() {
	$category_key=$_REQUEST['category_key'];

	$category=new Category('category_key',$_REQUEST['category_key']);
	global $editor;
	$category->editor=$editor;
	$key=$_REQUEST['key'];
	if ($key=='Attach') {
		$note=stripslashes(urldecode($_REQUEST['newvalue']));
		$target_path = "uploads/".'attach_'.date('U');
		$original_name=$_FILES['testFile']['name'];
		$type=$_FILES['testFile']['type'];
		$data=array('Caption'=>$note,'Original Name'=>$original_name,'Type'=>$type);

		if (move_uploaded_file($_FILES['testFile']['tmp_name'],$target_path )) {
			$category->add_attach($target_path,$data);

		}
	} else {



		$key_dic=array(
			'name'=>'Category Code'
			,'id'=>'Category Key'
			// ,'alias'=>'Staff Alias'
			// ,'type'=>'Staff Type'


		);
		if (array_key_exists($_REQUEST['key'],$key_dic))
			$key=$key_dic[$_REQUEST['key']];
		if ($key=='subcategory_name')$key='Category Code';
		echo "key=".$key;
		$update_data=array($key=>stripslashes(urldecode($_REQUEST['newvalue'])));
		echo " updte data=".$update_data;
		$category->update($update_data);
	}


	if ($category->updated) {
		$response= array('state'=>200,'newvalue'=>$category->new_value,'key'=>$_REQUEST['key']);

	} else {
		$response= array('state'=>400,'msg'=>$category->msg,'key'=>$_REQUEST['key']);
	}
	echo json_encode($response);
}





function edit_family_department($data) {
	//print $data['newvalue'];


	$family=new family($data['id']);
	global $editor;
	$family->editor=$editor;
	$family->update_department($data['newvalue']);


	if ($family->updated) {
		$response= array('state'=>200,'newvalue'=>$family->new_value,'key'=>$data['key'],'newdata'=>$family->new_data);

	} else {
		$response= array('state'=>400,'msg'=>$family->msg,'key'=>$_REQUEST['key']);
	}
	echo json_encode($response);
}


function edit_family($data) {
	//print $data['newvalue'];


	$family=new family($data['id']);
	global $editor;
	$family->editor=$editor;


	if ($data['key']=='Product Family Description') {
		$_key='Family_Description';
	}else {
		$_key=$data['key'];
	}


	$family->update(array($data['key']=>stripslashes(urldecode($data['newvalue']))));
	if ($family->updated) {
		$response= array('state'=>200,'newvalue'=>$family->new_value,'key'=>$_key);

	} else {
		$response= array('state'=>400,'msg'=>$family->msg,'key'=>$_key);
	}
	echo json_encode($response);
}






function update_deal_metadata($data) {

	require_once 'class.DealComponent.php';
	$deal_metadata=new DealComponent($data['deal_metadata_key']);
	$deal_metadata->update(array(
			'Deal Component Name'=>$data['name'],
			'Terms'=>$data['terms'],
			'Allowances'=>$data['allowances'])
	);
	if (!$deal_metadata->error) {
		$response= array('state'=>200);

	} else {
		$response= array('state'=>400,'msg'=>$deal_metadata->msg);
	}
	echo json_encode($response);



}

function old_edit_deal() {

	require_once 'class.DealComponent.php';


	//print_r($_REQUEST);

	$deal_metadata=new DealComponent($_REQUEST['deal_key']);
	global $editor;
	$deal_metadata->editor=$editor;
	$deal_metadata->update(array($_REQUEST['key']=>stripslashes(urldecode($_REQUEST['newvalue']))));


	if ($deal_metadata->updated) {
		$response= array('state'=>200,'newvalue'=>$deal_metadata->new_value,'key'=>$_REQUEST['key'],'description'=>$deal_metadata->get('Description'));

	} else {
		$response= array('state'=>400,'msg'=>$deal_metadata->msg,'key'=>$_REQUEST['key']);
	}
	echo json_encode($response);
}






function edit_product_multi() {

	if (!isset($_REQUEST['value'])  and isset($_REQUEST['newvalue']) )
		$_REQUEST['value']=$_REQUEST['newvalue'];
	if (!isset($_REQUEST['id']) or !isset($_REQUEST['key']) or  !isset($_REQUEST['value'])       ) {
		$response= array('state'=>400,'msg'=>'error','key'=>$_REQUEST['key']);
		echo json_encode($response);
		return;
	}

	$product=new product('pid',$_REQUEST['id']);
	$result=array();
	$updated=false;
	if ($_REQUEST['key']=='array') {
		$tmp=preg_replace('/\\\"/','"',$_REQUEST['value']);
		$tmp=preg_replace('/\\\\\"/','"',$tmp);
		$raw_data=json_decode($tmp, true);
		if (!is_array($raw_data)) {
			$response=array('state'=>400,'msg'=>'Wrong value');
			echo json_encode($response);
			return;
		}

		$result=array();
		//print_r($raw_data);
		foreach ($raw_data as $key=>$value) {
			$product->update($key,$value);
		}
	} else {

		$translator=array('name'=>'Product Name');

		if (array_key_exists($_REQUEST['key'],$translator))
			$key=$translator[$_REQUEST['key']];
		else
			$key=$_REQUEST['key'];
		$value=stripslashes(urldecode($_REQUEST['value']));
		$product->update($key,$value);
	}


	$response= array('state'=>200,'updated_fields'=>$product->updated_fields,'errors_while_updating'=>$product->errors_while_updating);
	echo json_encode($response);
}

function list_products_for_edition() {

	global $corporate_currency;

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent='none';


	if ($parent=='store') {
		$conf=$_SESSION['state']['store']['products'];
		$conf_table='store';
	}
	elseif ($parent=='department') {
		$conf=$_SESSION['state']['department']['products'];
		$conf_table='department';
	}
	elseif ($parent=='family') {
		$conf=$_SESSION['state']['family']['products'];
		$conf_table='family';
	}
	elseif ($parent=='none') {
		$conf=$_SESSION['state']['stores']['products'];
		$conf_table='stores';
	}
	else {

		exit;
	}





	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];

	if (isset( $_REQUEST['where']))
		$where=$_REQUEST['where'];
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];



	if (isset( $_REQUEST['percentages'])) {
		$percentages=$_REQUEST['percentages'];
		$_SESSION['state']['products']['percentages']=$percentages;
	} else
		$percentages=$_SESSION['state']['products']['percentages'];



	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];
		$_SESSION['state']['products']['period']=$period;
	} else
		$period=$_SESSION['state']['products']['period'];

	if (isset( $_REQUEST['avg'])) {
		$avg=$_REQUEST['avg'];
		$_SESSION['state']['products']['avg']=$avg;
	} else
		$avg=$_SESSION['state']['products']['avg'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		$parent=$conf['parent'];

	if (isset( $_REQUEST['mode']))
		$mode=$_REQUEST['mode'];
	else
		$mode=$conf['mode'];

	if (isset( $_REQUEST['elements_stock_aux']))
		$elements_stock_aux=$_REQUEST['elements_stock_aux'];
	else
		$elements_stock_aux=$conf['elements_stock_aux'];



	if (isset( $_REQUEST['elements_type']))
		$elements_type=$_REQUEST['elements_type'];
	else
		$elements_type=$conf['elements_type'];

	if (isset( $_REQUEST['elements']))
		$elements=$_REQUEST['elements'];
	else
		$elements=$conf['elements'];




	if (isset( $_REQUEST['elements_type_Historic'])) {
		$elements['type']['Historic']=$_REQUEST['elements_type_Historic'];
	}
	if (isset( $_REQUEST['elements_type_NoSale'])) {
		$elements['type']['NoSale']=$_REQUEST['elements_type_NoSale'];
	}
	if (isset( $_REQUEST['elements_type_Sale'])) {
		$elements['type']['Sale']=$_REQUEST['elements_type_Sale'];
	}
	if (isset( $_REQUEST['elements_type_Private'])) {
		$elements['type']['Private']=$_REQUEST['elements_type_Private'];
	}
	if (isset( $_REQUEST['elements_type_Discontinued'])) {
		$elements['type']['Discontinued']=$_REQUEST['elements_type_Discontinued'];
	}

	if (isset( $_REQUEST['elements_web_Offline'])) {
		$elements['web']['Offline']=$_REQUEST['elements_web_Offline'];
	}
	if (isset( $_REQUEST['elements_web_OutofStock'])) {
		$elements['web']['OutofStock']=$_REQUEST['elements_web_OutofStock'];
	}
	if (isset( $_REQUEST['elements_web_Online'])) {
		$elements['web']['Online']=$_REQUEST['elements_web_Online'];
	}

	if (isset( $_REQUEST['elements_web_Discontinued'])) {
		$elements['web']['Discontinued']=$_REQUEST['elements_web_Discontinued'];
	}


	if (isset( $_REQUEST['elements_stock_Error'])) {
		$elements['stock']['Error']=$_REQUEST['elements_stock_Error'];
	}
	if (isset( $_REQUEST['elements_stock_Excess'])) {
		$elements['stock']['Excess']=$_REQUEST['elements_stock_Excess'];
	}
	if (isset( $_REQUEST['elements_stock_Normal'])) {
		$elements['stock']['Normal']=$_REQUEST['elements_stock_Normal'];
	}
	if (isset( $_REQUEST['elements_stock_Low'])) {
		$elements['stock']['Low']=$_REQUEST['elements_stock_Low'];
	}
	if (isset( $_REQUEST['elements_stock_VeryLow'])) {
		$elements['stock']['VeryLow']=$_REQUEST['elements_stock_VeryLow'];
	}
	if (isset( $_REQUEST['elements_stock_OutofStock'])) {
		$elements['stock']['OutofStock']=$_REQUEST['elements_stock_OutofStock'];
	}



	if (isset( $_REQUEST['store_id'])    ) {
		$store=$_REQUEST['store_id'];
		$_SESSION['state']['products']['store']=$store;
	} else
		$store=$_SESSION['state']['products']['store'];



	$_SESSION['state'][$conf_table]['products']['order']=$order;
	$_SESSION['state'][$conf_table]['products']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['products']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['products']['sf']=$start_from;
	// $_SESSION['state'][$conf_table]['products']['where']=$awhere;
	$_SESSION['state'][$conf_table]['products']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['products']['f_value']=$f_value;
	$_SESSION['state'][$conf_table]['products']['percentages']=$percentages;
	$_SESSION['state'][$conf_table]['products']['avg']=$avg;
	$_SESSION['state'][$conf_table]['products']['period']=$period;

	$_SESSION['state'][$conf_table]['products']['mode']=$mode;

	$_SESSION['state'][$conf_table]['products']['elements']=$elements;
	$_SESSION['state'][$conf_table]['products']['elements_type']=$elements_type;
	$_SESSION['state'][$conf_table]['products']['elements_stock_aux']=$elements_stock_aux;



	$filter_msg='';



	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

	$where=' where true ';

	switch ($parent) {
	case('store'):
		$where.=sprintf(' and `Product Store Key`=%d',$_SESSION['state']['products']['store']);
		break;
	case('department'):
		$where.=sprintf('  and `Product Main Department Key`=%d',$_SESSION['state']['department']['id']);
		break;
	case('family'):
		if (isset($_REQUEST['parent_key']))
			$parent_key=$_REQUEST['parent_key'];
		else
			$parent_key=$_SESSION['state']['family']['id'];

		$where.=sprintf(' and `Product Family Key`=%d',$parent_key);
		break;
	default:


	}
	$elements_counter=0;
	switch ($elements_type) {
	case 'type':
		$_elements='';
		foreach ($elements['type'] as $_key=>$_value) {
			if ($_value) {
				$_elements.=','.prepare_mysql($_key);
				$elements_counter++;
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		} elseif ($elements_counter<5) {
			$where.=' and `Product Main Type` in ('.$_elements.')' ;
		}
		break;
	case 'web':
		$_elements='';
		foreach ($elements['web'] as $_key=>$_value) {
			if ($_value) {
				if ($_key=='OutofStock')
					$_key='Out of Stock';
				elseif ($_key=='ForSale')
					$_key='For Sale';
				$_elements.=','.prepare_mysql($_key);
				$elements_counter++;
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		} elseif ($elements_counter<4) {
			$where.=' and `Product Web State` in ('.$_elements.')' ;
		}
		break;

	case 'stock':


		switch ($elements_stock_aux) {
		case 'InWeb':
			$where.=' and `Product Web State`!="Offline" ' ;
			break;
		case 'ForSale':
			$where.=' and `Product Main Type`="Sale" ' ;
			break;
		}


		$_elements='';
		foreach ($elements['stock'] as $_key=>$_value) {
			if ($_value) {
				$_elements.=','.prepare_mysql($_key);
				$elements_counter++;
			}
		}
		$_elements=preg_replace('/^\,/','',$_elements);
		if ($_elements=='') {
			$where.=' and false' ;
		} elseif ($elements_counter<6) {
			$where.=' and `Product Availability State` in ('.$_elements.')' ;
		}
		break;


	}


	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";

	$sql="select count(*) as total from `Product Dimension`   P   $where $wheref";
	//print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total  from `Product Dimension`  P  $where ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$filtered=$row['total']-$total;
			$total_records=$row['total'];
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('product','products',$total_records);

	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp='';

	$_order=$order;
	$_dir=$order_direction;

	if ($order=='code')
		$order='`Product Code File As`';
	elseif ($order=='name')
		$order='`Product Name`';
	elseif ($order=='shortname')
		$order='`Product XHTML Short Description`';

	else
		$order='`Product Code`';

	$sql="select *  from `Product Dimension` P  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	//print $sql;
	$res = mysql_query($sql);
	$adata=array();
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		if ($row['Product Total Acc Quantity Ordered']==0 and  $row['Product Total Acc Quantity Invoiced']==0 and  $row['Product Total Acc Quantity Delivered']==0  ) {
			$delete='<img src="art/icons/delete.png" /> <span>'._('Delete').'<span>';
			$delete_type='delete';
		} else {
			$delete='<img src="art/icons/discontinue.png" /> <span>'._('Discontinue').'<span>';
			$delete_type='discontinue';
		}

		if ($row['Product RRP']!=0 and is_numeric($row['Product RRP']))
			$customer_margin=_('Margin').' '.percentage($row['Product RRP']-$row['Product Price'],$row['Product Price']);
		else
			$customer_margin=_('ND');

		if ($row['Product Price']!=0 and is_numeric($row['Product Cost']))
			$margin= percentage($row['Product Price']-$row['Product Cost'],$row['Product Cost']);
		else
			$margin=_('ND');
		global $myconf;
		$in_common_currency=$corporate_currency;
		$in_common_currency_price='';
		if ($row['Product Currency']!= $in_common_currency) {
			if (!isset($exchange[$row['Product Currency']])) {
				$exchange[$row['Product Currency']]=currency_conversion($row['Product Currency'],$in_common_currency);

			}
			$in_common_currency_price='('.money($exchange[$row['Product Currency']]*$row['Product Price']).') ';

		}


		if ($row['Product Stage']=='In Process') {

			if ($row['Product Editing Price']!=0 and is_numeric($row['Product Cost']))
				$margin∂=number(100*($row['Product Editing Price']-$row['Product Cost'])/$row['Product Editing Price'],1).'%';
			else
				$margin=_('ND');
			global $myconf;
			$in_common_currency=$corporate_currency;
			$in_common_currency_price='';
			if ($row['Product Currency']!= $in_common_currency) {
				if (!isset($exchange[$row['Product Currency']])) {
					$exchange[$row['Product Currency']]=currency_conversion($row['Product Currency'],$in_common_currency);

				}
				$in_common_currency_price='('.money($exchange[$row['Product Currency']]*$row['Product Editing Price']).') ';

			}



			$processing=_('Editing');
			$name=$row['Product Editing Name'];
			$sdescription=$row['Product Editing Special Characteristic'];
			$famsdescription=$row['Product Editing Family Special Characteristic'];
			$price=money($row['Product Editing Price'],$row['Product Currency']);
			if (is_numeric($row['Product Editing Units Per Case']) and $row['Product Editing Units Per Case']!=1) {
				$unit_price=money($row['Product Editing Price']/$row['Product Editing Units Per Case'],$row['Product Currency']);
			} else
				$unit_price='?';
			$units=$row['Product Editing Units Per Case'];
			$unit_type=$row['Product Editing Unit Type'];
			$units_info='';
		} else {

			if ($row['Product Price']!=0 and is_numeric($row['Product Cost']))
				$margin=number(100*($row['Product Price']-$row['Product Cost'])/$row['Product Price'],1).'%';
			else
				$margin=_('ND');
			global $myconf;
			$in_common_currency=$corporate_currency;
			$in_common_currency_price='';
			if ($row['Product Currency']!= $in_common_currency) {
				if (!isset($exchange[$row['Product Currency']])) {
					$exchange[$row['Product Currency']]=currency_conversion($row['Product Currency'],$in_common_currency);

				}
				$in_common_currency_price='('.money($exchange[$row['Product Currency']]*$row['Product Price']).') ';

			}


			$processing=_('Live');
			$name=$row['Product Name'];
			$sdescription=$row['Product Special Characteristic'];

			$price=money($row['Product Price'],$row['Product Currency']);
			$unit_price=money($row['Product Price']/$row['Product Units Per Case'],$row['Product Currency']);
			$units=$row['Product Units Per Case'];
			$unit_type=$row['Product Unit Type'];
			$units_info=number($row['Product Units Per Case']);
		}


		if ($row['Product Stage']=='New')
			$processing=_('Editing');

		switch ($row['Product Sales Type']) {
		case('Public Sale'):
			$sales_type=_('Public Sale');
			break;
		case('Private Sale'):
			$sales_type=_('Private Sale');
			break;
		case('Not for Sale'):
			$sales_type=_('Not For Sale');
			break;
		default:
			$sales_type=$row['Product Sales Type'];

		}


		switch ($row['Product Record Type']) {
		default:
			$record_type=$row['Product Record Type'];

		}


		switch ($row['Product Web Configuration']) {
		case('Online Force Out of Stock'):
			$formated_web_configuration=_('Force Out of Stock');
			break;
		case('Online Auto'):
			$formated_web_configuration=_('Link to part');
			break;
		case('Offline'):
			$formated_web_configuration=_('Offline');
			break;
		case('Online Force For Sale'):
			$formated_web_configuration=_('Force Online');
			break;
		default:
			$formated_web_configuration=$row['Product Web Configuration'];
		}

		switch ($row['Product Web State']) {
		case('Out of Stock'):
			$web_state='<span class=="out_of_stock">['._('Out of Stock').']</span>';
			break;
		case('For Sale'):
			$web_state='';
			break;
		case('Discontinued'):
			$web_state=_('Discontinued');
		case('Offline'):
			$web_state=_('Offline');
		default:
			$web_state=$row['Product Web State'];


			break;


		}

		if ($row['Product Sales Type']!='Public Sale') {
			$web_configuration=$row['Product Sales Type'];
			switch ($row['Product Sales Type']) {
			case 'Private Sale':
				$formated_web_configuration='<span style="color:#777;font-style:italic">'._('Private sale').' <img src="art/icons/lock.png" style="height:14px"/>';
				break;
			default:
				$formated_web_configuration='<span style="color:#777;font-style:italic">'._('Not for Sale').' <img src="art/icons/lock.png" style="height:14px"/>';
				break;
			}
		} else {

			$web_configuration=$row['Product Web Configuration'];
		}

		$checkbox_unchecked=sprintf('<img src="art/icons/checkbox_unchecked.png" style="width:14px;cursor:pointer" checked=0  id="assigned_subject_%d" onClick="check_assigned_subject(%d)"/>',
			$row['Product ID'],
			$row['Product ID']
		);
		$checkbox_checked=sprintf('<img src="art/icons/checkbox_checked.png" style="width:14px;cursor:pointer" checked=1  id="assigned_subject_%d" onClick="check_assigned_subject(%d)"/>',
			$row['Product ID'],
			$row['Product ID']
		);


		$adata[]=array(
			'checkbox'=>'',
			'checkbox_checked'=>$checkbox_checked,
			'checkbox_unchecked'=>$checkbox_unchecked,

			'pid'=>$row['Product ID'],
			'code'=>sprintf("<a href='edit_product.php?pid=%d'>%s</a>",$row['Product ID'],$row['Product Code']),
			'code_price'=>$row['Product Code'].($row['Product Units Per Case']!=1?' <span style="font-style: italic;">('.$row['Product Units Per Case'].'s)</span>':''),
			//'code_price'=>sprintf('%s <a href="edit_product.php?pid=%d&edit=prices"><img src="art/icons/external.png"/></a>',$row['Product Code'],$row['Product ID']),
			'smallname'=>$row['Product XHTML Short Description'].' <span class="stock">'._('Stock').': '.number($row['Product Availability']).'</span> <span class="web_state">'.$web_state.'</span>',

			'name'=>$row['Product Name'],
			'processing'=>$processing,
			'sales_type'=>$sales_type,
			'product_sales_type'=>$row['Product Sales Type'],
			'record_type'=>$record_type,

			'web_configuration'=>$web_configuration,
			'formated_web_configuration'=>$formated_web_configuration,
			'state_info'=>$sales_type,
			'sdescription'=>$sdescription,

			'units'=>$units,
			'units_info'=>$units_info,

			'unit_type'=>$unit_type,
			'price'=>$price,
			'unit_price'=>$unit_price,
			'margin'=>$margin,

			'price_info'=>"$units_info $unit_type $in_common_currency_price",

			'unit_rrp'=>money(($row['Product RRP']/$row['Product Units Per Case']),$row['Product Currency']),
			'rrp_info'=>$customer_margin,

			'delete'=>$delete,
			'delete_type'=>$delete_type,
			'go'=>sprintf("<a href='edit_product.php?pid=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Product ID'])

		);
	}
	// print $rtext;
	mysql_free_result($res);

	//  $rtext='21 records';
	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'filtered'=>$filtered
		)
	);

	echo json_encode($response);
}
function list_families_for_edition() {

	$conf=$_SESSION['state']['department']['families'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];

	// print_r($conf);
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];

	if (isset( $_REQUEST['where']))
		$where=$_REQUEST['where'];
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	/*
	if (isset( $_REQUEST['percentages'])) {
		$percentages=$_REQUEST['percentages'];
		$_SESSION['state']['families']['percentages']=$percentages;
	} else
		$percentages=$_SESSION['state']['families']['percentages'];



	if (isset( $_REQUEST['period'])) {
		$period=$_REQUEST['period'];
		$_SESSION['state']['families']['period']=$period;
	} else
		$period=$_SESSION['state']['families']['period'];

	if (isset( $_REQUEST['avg'])) {
		$avg=$_REQUEST['avg'];
		$_SESSION['state']['families']['avg']=$avg;
	} else
		$avg=$_SESSION['state']['families']['avg'];

*/
	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;

	if (isset( $_REQUEST['parent'])) {
		switch ($_REQUEST['parent']) {
		case('store'):
			$where=sprintf(' where `Product Family Store Key`=%d',$_SESSION['state']['store']['id']);
			break;
		case('department'):
			$where=sprintf('  where `Product Family Main Department Key`=%d',$_SESSION['state']['department']['id']);
			break;
		case('none'):
			$where=sprintf(' where true ');
			break;
		}
	}



	$filter_msg='';



	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	$_SESSION['state']['department']['families']['order']=$order;
	$_SESSION['state']['department']['families']['order_dir']=$order_dir;
	$_SESSION['state']['department']['families']['nr']=$number_results;
	$_SESSION['state']['department']['families']['sf']=$start_from;
	$_SESSION['state']['department']['families']['where']=$where;
	$_SESSION['state']['department']['families']['f_field']=$f_field;
	$_SESSION['state']['department']['families']['f_value']=$f_value;


	//  $where.=" and `Product Department Key`=".$id;



	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";

	$sql="select count(*) as total from `Product Family Dimension`   F   $where $wheref";

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total  from `Product Family Dimension`  F  $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$filtered=$row['total']-$total;
			$total_records=$row['total'];
		}
		mysql_free_result($result);
	}

	$rtext=sprintf(ngettext("%d family", "%d families", $total_records), $total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp='('._('Showing all').')';

	$_order=$order;
	$_dir=$order_direction;

	if ($order=='code')
		$order='`Product Family Code`';
	elseif ($order=='name')
		$order='`Product Family Name`';
	else
		$order='`Product Family Key`';


	$sql="select `Product Family Sales Type`,F.`Product Family Key`,`Product Family Code`,`Product Family Name`,`Product Family For Public Sale Products`+`Product Family In Process Products`+`Product Family Not For Sale Products`+`Product Family Discontinued Products`+`Product Family Unknown Sales State Products` as Products  from `Product Family Dimension` F  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
	//print $sql;
	$res = mysql_query($sql);
	$adata=array();
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		switch ($row['Product Family Sales Type']) {
		case 'Public Sale':
			$sales_type=_('Public Sale');
			break;
		case 'Private Sale':
			$sales_type=_('Private Sale');
			break;
		case 'Not for Sale':
			$sales_type=_('Not for Sale');
			break;
		}



		$adata[]=array(
			'id'=>$row['Product Family Key'],
			'edit'=>sprintf('<a href="edit_family.php?id=%d">%03d<a>',$row['Product Family Key'],$row['Product Family Key']),
			'code'=>$row['Product Family Code'],
			'name'=>$row['Product Family Name'],
			'sales_type'=>$sales_type,

			'go'=>sprintf("<a href='edit_family.php?id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Product Family Key'])

		);
	}
	mysql_free_result($res);
	$response=array('resultset'=>
		array(

			'state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,



		)
	);

	echo json_encode($response);
}
function list_stores_for_edition() {
	$conf=$_SESSION['state']['stores']['stores'];

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;







	$_SESSION['state']['stores']['table']['order']=$order;
	$_SESSION['state']['stores']['table']['order_dir']=$order_direction;
	$_SESSION['state']['stores']['table']['nr']=$number_results;
	$_SESSION['state']['stores']['table']['sf']=$start_from;
	$_SESSION['state']['stores']['table']['where']=$where;
	$_SESSION['state']['stores']['table']['_field']=$f_field;
	$_SESSION['state']['stores']['table']['f_value']=$f_value;


	$where=" ";

	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";






	$sql="select count(*) as total from `Store Dimension`   $where $wheref";

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total `Store Dimension`   $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$filtered=$row['total']-$total;
			$total_records=$row['total'];
		}
		mysql_free_result($result);
	}

	$rtext=number($total_records)." ".ngettext('store','stores',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp='';


	$_dir=$order_direction;
	$_order=$order;


	if ($order=='name')
		$order='`Store Name`';
	else if ($order=='code')
			$order='`Store Code`';
		else
			$order='`Store Code`';




		$sql="select *  from `Store Dimension`  order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);
	$adata=array();
	//   print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		if ($row['Store Contacts']>0) {
			$close='<img src="art/icons/delete.png" /> <span    style="cursor:pointer">'._('Close').'<span>';
			$delete='';
		} else {
			$delete='<img src="art/icons/discontinue.png" /> <span style="cursor:pointer" >'._('Delete').'<span>';
			$close='<img src="art/icons/delete.png" /> <span style="cursor:pointer">'._('Close').'<span>';
		}
		$adata[]=array(
			'id'=>$row['Store Key']
			,'code'=>$row['Store Code']
			,'name'=>$row['Store Name']
			,'delete'=>$delete
			,'close'=>$close
			,'subject_data'=>$row['Store Name']
			//,'delete_type'=>$delete_type
			,'go'=>sprintf("<a href='store.php?id=%d&edit=1'><img src='art/icons/page_go.png' alt='go'></a>",$row['Store Key'])
		);
	}


	$total=mysql_num_rows($res);
	mysql_free_result($res);
	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,

			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}
function list_departments_for_edition() {
	if (isset($_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		exit("xx");


	if (isset($_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		exit("xx");

	if ($parent=='store') {
		$conf=$_SESSION['state']['store']['edit_departments'];
		$conf_table='store';
	}else {
		exit();
	}

	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;









	$_SESSION['state'][$conf_table]['edit_departments']['order']=$order;
	$_SESSION['state'][$conf_table]['edit_departments']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['edit_departments']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['edit_departments']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['table']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['table']['f_value']=$f_value;


	//$where=$where.' '.sprintf(" and `Product Department Store Key`=%d",$store_id);

	$filter_msg='';
	$wheref='';
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


	switch ($parent) {
	case('store'):
		$where=sprintf(' where `Product Department Store Key`=%d',$parent_key);
		break;
	case('none'):
		$where=sprintf(' where true ');
		break;
	}


	$sql="select count(*) as total from `Product Department Dimension`   $where $wheref";
	// print $sql;
	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res)) {
		$total=$row['total'];
	}
	mysql_free_result($res);
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total `Product Department Dimension`   $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}

	$rtext=number($total_records)." ".ngettext('department','departments',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp='';


	$_dir=$order_direction;
	$_order=$order;

	if ($order=='name')
		$order='`Product Department Name`';
	elseif ($order=='code')
		$order='`Product Department Code`';
	elseif ($order=='sales_type')
		$order='`Product Department Sales Type`';
	else
		$order='`Product Department Name`';
	$sql="select D.`Product Department Sales Type`, D.`Product Department Key`,`Product Department Code`,`Product Department Name`,`Product Department For Public Sale Products`+`Product Department For Private Sale Products`+`Product Department In Process Products` as Products  from `Product Department Dimension` D  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);
	$adata=array();
	//print "$sql";
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		//      if($row['Products']>0){
		// $delete='<img src="art/icons/discontinue.png" /> <span  style="cursor:pointer">'._('Discontinue').'<span>';//
		// $delete_type='discontinue';
		//      }else{
		// $delete='<img src="art/icons/delete.png" /> <span  style="cursor:pointer">'._('Delete').'<span>';
		//      $delete_type='delete';
		//    }

		switch ($row['Product Department Sales Type']) {
		case 'Public Sale':
			$sales_type=_('Public Sale');
			break;
		case 'Private Sale':
			$sales_type=_('Private Sale');
			break;
		case 'Not for Sale':
			$sales_type=_('Not for Sale');
			break;
		}



		$adata[]=array(
			'id'=>$row['Product Department Key'],
			'name'=>$row['Product Department Name'],
			'code'=>$row['Product Department Code'],
			'sales_type'=>$sales_type,
			//'delete_type'=>$delete_type,
			'go'=>sprintf("<a href='department.php?id=%d&edit=1'><img src='art/icons/page_go.png' alt='go'></a>",$row['Product Department Key'])
		);
	}

	mysql_free_result($res);






	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}






function list_charges_for_edition() {

	if ( isset($_REQUEST['parent']))
		$parent= $_REQUEST['parent'];
	else
		exit;
	if ( isset($_REQUEST['parent_key']))
		$parent_key= $_REQUEST['parent_key'];
	else
		exit;


	$parent='store';

	if ( isset($_REQUEST['parent']))
		$parent= $_REQUEST['parent'];

	if ($parent=='store'){
		$conf=$_SESSION['state']['store']['edit_charges'];
		$conf_table='store';

	}else {
		exit;
	}




	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];


	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];


	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;






	$_SESSION['state'][$conf_table]['edit_charges']['order']=$order;
	$_SESSION['state'][$conf_table]['edit_charges']['order_dir']=$order_direction;
	$_SESSION['state'][$conf_table]['edit_charges']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['edit_charges']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['edit_charges']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['edit_charges']['f_value']=$f_value;




	if ($parent=='store')
		$where=sprintf("where  `Store Key`=%d ",$parent_key);
	else
		$where=sprintf("where true ");

	$filter_msg='';
	$wheref='';
	if ($f_field=='description' and $f_value!='')
		$wheref.=" and  CONCAT(`Charge Description`,' ',`Charge Terms Description`) like '".addslashes($f_value)."%'";
	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Charge Name` like '".addslashes($f_value)."%'";








	$sql="select count(*) as total from `Charge Dimension`   $where $wheref";
	// print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total `Charge Dimension`   $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('charge','charges',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any charge with this name ")." <b>".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any charge with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('charges with name like')." <b>".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('charges with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;

	if ($order=='name')
		$order='`Charge Name`';
	elseif ($order=='description')
		$order='`Charge Description`,`Charge Terms Description`';
	else
		$order='`Charge Name`';


	$sql="select *  from `Charge Dimension` $where    order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);

	$total=mysql_num_rows($res);

	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {




		$input_charge=sprintf('

        						<tr style="border:none">
        						<td >%s</td>
								</tr>
								<tr style="border:none">
                              <td><input id="charge%d" onKeyUp="charge_changed(%d)" %s class="%s" style="width:50px" value="%s" ovalue="%s" /> %s</td>
                              <td>
                              <div class="buttons small">
                              <span id="charge_saving" style="float:right;display:none"><img style="height:12px"src="art/loading.gif"/>'._('Saving').'</span>
                              <button id="charge_save%d" style="visibility:hidden" class="positive" onClick="charge_save(%d)">'._('Save').'</button>
                              <button id="charge_reset%d" style="visibility:hidden" style="margin-left:10px "class="negative"  onClick="charge_reset(%d)">'._('Reset').'</button>
                              </td>'
			,_('charge').":"
			,$row['Charge Key']
			,$row['Charge Key']
			,''
			,'input'
			,$row['Charge Metadata']
			,$row['Charge Metadata']
			,''
			,$row['Charge Key']
			,$row['Charge Key']
			,$row['Charge Key']
			,$row['Charge Key']


		);



		switch ($row['Charge Terms Type']) {
		case 'Order Items Gross Amount':
			$terms_components=preg_split('/;/',$row['Charge Terms Metadata']);
			$operator=$terms_components[0];

			$charge_term_amount=$terms_components[1];

			switch ($operator) {
			case('<'):
				$terms_label=_('when items gross amount is less than').':';
				break;
			case('>'):
				$terms_label=_('when items gross amount is more than').':';
				break;
			case('<='):
				$terms_label=_('when items gross amount is less or equal than').':';
				break;
			case('>='):
				$terms_label=_('when items gross amount is more or equal than').':';
				break;
			}



			break;
		default:
			$terms_label=_('when').' '.$row['Charge Terms Type'];
			break;
		}


		$input_term=sprintf('<tr style="border:none"> <td colspan=3 >%s</td></tr>
                            <tr style="border:none">

                            <td><input id="deal_allowance%d" onKeyUp="deal_allowance_changed(%d)" %s class="%s" style="width:50px" value="%s" ovalue="%s" /> %s</td>
                            <td colspan="2">
                            <div class="buttons small">
                            <button id="deal_allowance_save%d" style="visibility:hidden" class="positive" onClick="deal_allowance_save(%d)">'._('Save').'</button>
                            <button id="deal_allowance_reset%d" style="visibility:hidden" style="margin-left:10px "class="negative"  onClick="deal_allowance_reset(%d)">'._('Reset').'</button>
                            </td></tr>'
			,$terms_label
			,$row['Charge Key']
			,$row['Charge Key']
			,''
			,'input'
			,$charge_term_amount
			,$charge_term_amount
			,''
			,$row['Charge Key']
			,$row['Charge Key']
			,$row['Charge Key']
			,$row['Charge Key']


		);


		if ($row['Charge Active']=='Yes') {
			$activity_editor="<div class='buttons small'>
<button class='selected positive'>"._('Active')."</button>
<button class='negative'>"._('Suspend')."</button>
</div>";

		}else {
			$activity_editor="<div class='buttons small'>
<button class=' positive'>"._('Activate')."</button>
<button class='selected negative'>"._('Suspended')."</button>
</div>";

		}



		$editor='<table border=0 style="margin:0px">'.$input_charge.$input_term.'</table>';
		$adata[]=array(
			'id'=> $row['Charge Key'],
			'name'=>$row['Charge Name'],
			'description'=>$row['Charge Description'].' '.$row['Charge Terms Description'],
			'active'=>$activity_editor,
			'editor'=>$editor
		);
	}
	mysql_free_result($res);



	// if($total<$number_results)
	//  $rtext=$total.' '.ngettext('store','stores',$total);
	//else
	//  $rtext='';

	//   $total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}


function list_campaigns_for_edition() {


	$parent='store';

	if ( isset($_REQUEST['parent']))
		$parent= $_REQUEST['parent'];

	if ($parent=='store')
		$parent_id=$_SESSION['state']['store']['id'];
	else
		return;

	$conf=$_SESSION['state'][$parent]['campaigns'];


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];


	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$_SESSION['state'][$parent]['campaigns']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);

	if ($parent=='store')
		$where=sprintf("where  `Store Key`=%d    ",$parent_id);
	else
		$where=sprintf("where true ");;

	$filter_msg='';
	$wheref='';
	if ($f_field=='description' and $f_value!='')
		$wheref.=" and  `Deal Description` like '".addslashes($f_value)."%'";
	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Deal Name` like '".addslashes($f_value)."%'";

	$sql="select count(*) as total from `Deal Dimension`   $where $wheref";
	//  print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total `Deal Dimension`   $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('campaign','campaigns',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any campaign with this name ")." <b>".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any campaign with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('campaigns with name like')." <b>".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('campaigns with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;

	if ($order=='name')
		$order='`Deal Name`';
	elseif ($order=='description')
		$order='`Deal Description`';
	else
		$order='`Deal Name`';


	$sql="select *  from `Deal Dimension` $where    order by $order $order_direction limit $start_from,$number_results    ";

	$res = mysql_query($sql);

	$total=mysql_num_rows($res);

	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$sql=sprintf("select * from `Campaign Deal Schema`  where `Deal Key`=%d  ",$row['Deal Key']);
		$res2 = mysql_query($sql);
		$deals='<ul style="padding:10px 20px">';
		while ($row2=mysql_fetch_array($res2, MYSQL_ASSOC)) {
			$deals.=sprintf("<li style='list-style-type: circle' >%s</li>",$row2['Deal Component Name']);
		}
		$deals.='</ul>';
		$adata[]=array(
			'name'=>$row['Deal Name'],
			'description'=>$row['Deal Description'].$deals


		);
	}
	mysql_free_result($res);



	// if($total<$number_results)
	//  $rtext=$total.' '.ngettext('store','stores',$total);
	//else
	//  $rtext='';

	//   $total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}


function list_deals_for_edition() {


	if (!isset($_REQUEST['parent']) or !isset($_REQUEST['parent_key'])) {

		exit("no parent");
	}

	$parent= $_REQUEST['parent'];
	$parent_key=$_REQUEST['parent_key'];




	$conf=$_SESSION['state'][$parent]['edit_offers'];


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];


	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
		
		
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


$_SESSION['state'][$parent]['edit_offers']['order']=$order;
$_SESSION['state'][$parent]['edit_offers']['order_dir']=$order_direction;
$_SESSION['state'][$parent]['edit_offers']['sf']=$number_results;
$_SESSION['state'][$parent]['edit_offers']['f_field']=$f_field;
$_SESSION['state'][$parent]['edit_offers']['f_value']=$f_value;



	if ($parent=='store')
		$where=sprintf("where `Deal Component Record Type`='Normal' and  DM.`Store Key`=%d and DM.`Deal Component Trigger`='Order'    ",$parent_key);
	elseif ($parent=='department')
		$where=sprintf("where   `Deal Component Record Type`='Normal' and DM.`Deal Component Trigger`='Department' and  DM.`Deal Component Trigger Key`=%d   ",$parent_key);
	elseif ($parent=='family')
		$where=sprintf("where  `Deal Component Record Type`='Normal' and  DM.`Deal Component Trigger`='Family' and  DM.`Deal Component Trigger Key`=%d   ",$parent_key);
	elseif ($parent=='product')
		$where=sprintf("where  `Deal Component Record Type`='Normal'  and DM.`Deal Component Trigger`='Product' and  DM.`Deal Component Trigger Key`=%d   ",$parent_key);
	else
		$where=sprintf("where `Deal Component Record Type`='Normal' ");;



	$filter_msg='';
	$wheref='';

	if ($f_field=='description' and $f_value!='')
		$wheref.=" and ( `Deal Component Terms Description` like '".addslashes($f_value)."%' or `Deal Component Allowance Description` like '".addslashes($f_value)."%'  )   ";

	elseif ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Deal Component Name` like '".addslashes($f_value)."%'";

	$sql="select count(*) as total from `Deal Component Dimension` DM   $where $wheref";
	// print $sql;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total `Deal Component Dimension`  DM  $where ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext('deal','deals',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this name ")." <b>".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with name like')." <b>".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;

	if ($order=='name')
		$order='DM.`Deal Component Name`';
	elseif ($order=='description')
		$order='`Deal Component Terms Description`,`Deal Component Allowance Description`';
	else
		$order='DM.`Deal Component Name`';


	$sql="select `Deal Number Active Components`,`Deal Component Expiration Date`,`Deal Description`,D.`Deal Key`,DM.`Deal Component Trigger`,`Deal Component Key`,DM.`Deal Component Name`,D.`Deal Name`
	from `Deal Component Dimension` DM left join `Deal Dimension`D  on (DM.`Deal Component Deal Key`=D.`Deal Key`)  $where    order by $order $order_direction limit $start_from,$number_results    ";
	//print $sql;
	$res = mysql_query($sql);
	$total=mysql_num_rows($res);
	$adata=array();
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		// $meta_data=preg_split('/,/',$row['Deal Component Allowance']);

		$deal_metadata=new DealComponent($row['Deal Component Key']);
		$input_allowance='';
		foreach ($deal_metadata->allowance_input_form() as $form_data) {
			$input_allowance.=sprintf('<td style="text-align:right;width:150px;padding-right:10px" >%s</td>
                                      <td style="width:15em"  style="text-align:left">
                                      <input id="deal_allowance%d" onKeyUp="deal_allowance_changed(%d)" %s class="%s" style="width:5em" value="%s" ovalue="%s" /> %s

                                      </td>'
				,$form_data['Label']
				,$row['Deal Component Key']
				,$row['Deal Component Key']
				,($form_data['Lock Value']?'READONLY':'')
				,$form_data['Value Class']
				,$form_data['Value']
				,$form_data['Value']
				,$form_data['Lock Label']



			);
		}
		$input_term='';
		foreach ($deal_metadata->terms_input_form() as $form_data) {

			if ($form_data['Value Class']=='country') {
				$input_term=sprintf('<td style="text-align:right;width:150px;padding-right:10px" >%s</td>
                                    <td style="width:15em"  style="text-align:left"><div style="margin-top:1px"><input id="country_code" value="" type="hidden">
                                    <input id="country" %s class="%s"style="width:15em" value="%s" /><div id="country_container" style="" ></div></div> %s

                                    <script type="text/javascript">
                                    var Countries_DS = new YAHOO.util.FunctionDataSource(match_country);
                                    Countries_DS.responseSchema = {fields: ["id", "name", "code","code2a"]}
                                    var Countries_AC = new YAHOO.widget.AutoComplete("country", "country_container", Countries_DS);
                                    Countries_AC.useShadow = true;
                                    Countries_AC.resultTypeList = false;
                                    Countries_AC.formatResult = country_formatResult;
                                    Countries_AC.itemSelectEvent.subscribe(onCountrySelected);
                                    </script>
                                    </td>'
					,$form_data['Label']
					,($form_data['Lock Value']?'READONLY':'')
					,$form_data['Value Class']
					,$form_data['Value']
					,$form_data['Lock Label']);
			} else {

				$input_term=sprintf('<td style="text-align:right;width:150px;padding-right:10px" >%s</td>
                                    <td style="width:15em"  style="text-align:left"><input id="deal_term%d" onKeyUp="deal_term_changed(%d)" %s class="%s" style="width:5em" value="%s" ovalue="%s" /> %s </td>'
					,$form_data['Label']
					,$row['Deal Component Key']
					,$row['Deal Component Key']
					,($form_data['Lock Value']?'READONLY':'')
					,$form_data['Value Class']
					,$form_data['Value']
					,$form_data['Value']
					,$form_data['Lock Label']

				);

			}

		}


		if ($row['Deal Component Expiration Date']=='') {
			$valid_to='<span style="font-style:italic">'._('Permanent').'</span> ';
		}else {
			$valid_to='<input style="width:65px" value=""/>';
		}


		$edit='<table style="margin:10px"><tr style="border:none">'.$input_allowance.'</tr><tr style="border:none">'.$input_term.'</tr>
		<tr style="border:none">
		<td style="text-align:right;padding-right:10px">'._('Valid to').':</td><td>'.$valid_to.'</td></tr>
		<tr style="border:none"><td colspan=2><div class="buttons small"><button onClick="save_metadata_deal('.$row['Deal Component Key'].')" id="save_metadata_deal'.$row['Deal Component Key'].'" class="disabled positive">'._('Save').'</button><button onClick="cancel_metadata_deal('.$row['Deal Component Key'].')" id="cancel_metadata_deal'.$row['Deal Component Key'].'" class="disabled negative">'._('Reset').'</button></div></td></tr>

		</table>';





		$name=sprintf('<a href="deal.php?id=%d" id="deal_name%d">%s</a><br/><div  id="deal_description%d" style="margin-top:5px;margin-bottom:5px">%s</div>',
			$row['Deal Key'],
			$row['Deal Key'],
			$row['Deal Name'],
			$row['Deal Key'],
			$row['Deal Description']

		);

		if ($row['Deal Number Active Components']==1) {

			$name.=sprintf('<div class="buttons small left"><button id="fill_edit_deal_form%d" onClick="fill_edit_deal_form(%d)" >%s</buttons></div>',

				$row['Deal Key'],
				$row['Deal Key'],
				_('Edit')

			);

		}



		$status="<br/><span id='deal_state".$deal_metadata->id."' style='font-weight:800;padding:10px 0px'>".$deal_metadata->get_xhtml_status()."</span>";
		$status.= '<div id="suspend_deal_button'.$deal_metadata->id.'" style="margin-top:10px;'.(($deal_metadata->data['Deal Component Status']=='Active' or $deal_metadata->data['Deal Component Status']=='Waiting')?'':'display:none' ).'" class="buttons small left"><button onClick="suspend_deal_metadata('.$deal_metadata->id.')"  class="negative"> '._("Suspend").'</button></div>';
		$status.= '<div id="activate_deal_button'.$deal_metadata->id.'" style="margin-top:10px;'.($deal_metadata->data['Deal Component Status']=='Suspended'?'':'display:none' ).'"  class="buttons small left"><button onClick="activate_deal_metadata('.$deal_metadata->id.')" class="positive"> '._("Activate").'</button></div>';






		//if ($row['Campaign Deal Schema Key']) {
		// $name.=sprintf('<br/><a style="text-decoration:underline" href="edit_campaign.php?id=%d">%s</a>',$row['Campaign Deal Schema Key'],$row['Deal Name']);
		//}
		$adata[]=array(
			'status'=>$status,
			'name'=>$name,
			'description'=>'<span style="color:#777;font-style:italic">'.$deal_metadata->get('Description').'</span>'.'</span><br/><input onKeyUp="deal_metadata_description_changed('.$deal_metadata->id.')"  id="deal_metadata_description_input'.$deal_metadata->id.'" style="margin-top:5px;width:100%" value="'.$row['Deal Component Name'].'"  ovalue="'.$row['Deal Component Name'].'"  /><br/>'.$edit,
			'dates'=>''


		);
	}
	mysql_free_result($res);



	// if($total<$number_results)
	//  $rtext=$total.' '.ngettext('store','stores',$total);
	//else
	//  $rtext='';

	//   $total_records=ceil($total_records/$number_results)+$total_records;

	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_returned'=>$start_from+$total,
			'records_perpage'=>$number_results,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}



function list_parts_in_product() {

	$conf=$_SESSION['state']['product']['parts'];


	if (isset( $_REQUEST['product_id']))
		$product_id=$_REQUEST['product_id'];
	else
		$product_id=$_SESSION['state']['product']['id'];


	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];


	if (isset( $_REQUEST['nr'])) {
		$number_results=$_REQUEST['nr'];
		if ($start_from>0) {
			$page=floor($start_from/$number_results);
			$start_from=$start_from-$page;
		}

	} else
		$number_results=$conf['nr'];


	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	if (isset( $_REQUEST['where']))
		$where=addslashes($_REQUEST['where']);
	else
		$where=$conf['where'];


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];


	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	$_SESSION['state']['product']['parts']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);




	if ($product_id) {

		$filter_msg='';

		$wheref='';
		$where=sprintf("where `Product ID`=%d ",$product_id);;

		if ($f_field=='sku' and $f_value!='')
			$wheref.=sprintf(" and `Part SKU`=%d   ",$f_value);



		$sql="select count(*) as total from `Product Part List`   $where $wheref";
		//  print $sql;
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total=$row['total'];
		}
		mysql_free_result($result);

		if ($wheref=='') {
			$filtered=0;
			$total_records=$total;
		} else {
			$sql="select count(*) as total `Deal Part List`   $where ";

			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$total_records=$row['total'];
				$filtered=$total_records-$total;
			}
			mysql_free_result($result);

		}


		$rtext=number($total_records)." ".ngettext('part','parts',$total_records);
		if ($total_records>$number_results)
			$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
		else
			$rtext_rpp=' ('._('Showing all').')';

		if ($total==0 and $filtered>0) {
			switch ($f_field) {
			case('name'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this name ")." <b>".$f_value."*</b> ";
				break;
			case('description'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with description like ")." <b>".$f_value."*</b> ";
				break;
			}
		}
		elseif ($filtered>0) {
			switch ($f_field) {
			case('name'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with name like')." <b>".$f_value."*</b>";
				break;
			case('description'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with description like')." <b>".$f_value."*</b>";
				break;
			}
		}
		else
			$filter_msg='';
		/*  }else{//products parts for new product */

		/*      $total=count($_SESSION['state']['new_product']['parts']); */
		/*      $total_records=$total; */
		/*      $filtered=0; */
		/*    } */



		$_dir=$order_direction;
		$_order=$order;


		$order='`Part SKU`';


		$sql="select *  from `Product Part List` $where    order by $order $order_direction limit $start_from,$number_results    ";
		//print $sql;
		$res = mysql_query($sql);
		$total=mysql_num_rows($res);
		$adata=array();
		while ($row=mysql_fetch_array($res, MYSQL_ASSOC) ) {
			// $meta_data=preg_split('/,/',$row['Deal Component Allowance']);


			$adata[]=array(
				'sku'=>$row['Part SKU'],
				'description'=>'x',
				'picks'=>'c',
				'notes'=>'v'

			);
		}
		mysql_free_result($res);

	} else {
		$adata=array();
		if (isset($_SESSION['state']['new_product']['parts'])) {
			foreach ($_SESSION['state']['new_product']['parts'] as $values)
				$adata[]=$values;
		}
		$rtext=_('Choose or create a part');
		$rtext_rpp='';
		$total_records=count($adata);
		$filter_msg='';
		$_dir=$order_direction;
		$_order=$order;

		if ($total_records>0) {
			$rtext=number($total_records)." ".ngettext('part','parts',$total_records);
		}

	}



	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}




function add_part_new_product($sku) {

	$part=new Part('sku',$sku);
	if ($part->sku) {
		// $_SESSION['state']['new_product']['parts']=array();
		if (!isset($_SESSION['state']['new_product']['parts']))
			$_SESSION['state']['new_product']['parts']=array();
		$tmp=$_SESSION['state']['new_product']['parts'];
		if (array_key_exists($part->sku,$tmp)) {
			//$_SESSION['state']['new_product']['parts'][$part->sku]['picks']=$_SESSION['state']['new_product']['parts'][$part->sku]['picks']+1;
			$msg=_('Part already selected');
		} else {
			$_SESSION['state']['new_product']['parts'][$part->sku]=array(
				'part_sku'=>$part->sku
				,'sku'=>$part->get_sku()
				,'description'=>$part->data['Part Unit Description']
				,'picks'=>1
				,'notes'=>''
				,'delete'=>'<img src="art/icons/delete.png"/>'
			);
			$msg=_('Adding part to list');
		}
		$response=array('state'=>200,'msg'=>$msg);
		echo json_encode($response);

	} else {
		$response=array('state'=>400,'msg'=>'Part SKU not found');
		echo json_encode($response);

	}

}

function edit_part_new_product($sku) {
	if (isset($_SESSION['state']['new_product']['parts'])) {
		$tmp=$_SESSION['state']['new_product']['parts'];
		if (array_key_exists($sku,$tmp)) {
			switch ($_REQUEST['key']) {
			case('picks'):
				$picks=$_REQUEST['newvalue'];
				if (is_numeric($picks)) {
					$_SESSION['state']['new_product']['parts'][$sku]['picks']=$picks;
					$response=array('state'=>200,'newvalue'=>$picks);
					echo json_encode($response);
					return;
				}
				break;
			case('notes'):

				$_SESSION['state']['new_product']['parts'][$sku]['notes']=$_REQUEST['newvalue'];
				$response=array('state'=>200,'newvalue'=>$_REQUEST['newvalue']);
				echo json_encode($response);
				return;

				break;
			}


		}
		$response=array('state'=>200,'msg'=>_('Wrong value'));
		echo json_encode($response);
	}



}






function edit_part_list($data) {

	$product_part_key=$data['key'];
	$values=$data['newvalue'];

	$product=new Product('pid',$data['pid']);

	$part_list_data=array();
	foreach ($values as $key =>$value) {

		if (!$value['deleted']) {

			$part_list_data[$value['sku']]=array(
				'Product ID'=>$product->get('Product ID'),
				'Part SKU'=>$value['sku'],
				'Product Part Type'=>'Simple',
				'Parts Per Product'=>$value['ppp'],
				'Product Part List Note'=>$value['note']
			);
		}

	}
	$date=date('Y-m-d H:i:s');
	$header_data=array(
		'Product Part Valid From'=>$date,
		'Product Part Metadata'=>'',
		'Product Part Valid To'=>'',
		'Product Part Most Recent'=>'Yes'
	);







	if (count($product->get_current_part_list())==0)
		$value['confirm']='new';
	else
		$value['confirm']='';

	$product_part_key=$product->find_product_part_list($part_list_data);



	if (!$product_part_key and $value['confirm']=='') {




		foreach ($product->data as $key=>$val) {
			$data[strtolower($key)]=$val;
		}
		$data['product valid from']=$date;
		$product->create_key($data);
		$product->create_product_id($data);

		if (count($part_list_data)>0)
			$product->new_current_part_list($header_data,$part_list_data)  ;

		$product->set_duplicates_as_historic();





	} else {

		$product->new_current_part_list($header_data,$part_list_data);
	}


	$part=new Part($value['sku']);
	$part->update_used_in();


	//
	//if($product_part_key){
	///$this->update_product_part_list($product_part_key,$header_data,$list);
	//}else{
	//$product_part_key=$this->create_product_part_list($header_data,$list);
	//}
	//$this->set_part_list_as_current($product_part_key);



	if ($product->new_id) {
		$response= array('state'=>200,'new'=>true,'newvalue'=>$product->pid);
	}
	elseif ($product->updated) {
		$response= array('state'=>200,'changed'=>true,'newvalue'=>$product->new_value);
	}
	elseif ($product->error) {
		$response= array('state'=>400,'msg'=>$product->msg);
	}

	else {
		$response= array('state'=>200,'changed'=>false);
	}
	echo json_encode($response);

}

function edit_location($data) {
	//print_r($data);
	$location=new Location($data['location_key']);
	if (!$location->id) {
		$response= array('state'=>400,'msg'=>'Location not found','key'=>'');
		echo json_encode($response);

		exit;
	}

	$values=$data['values'];

	//print $values['okey'];
	//print $values['value'];

	$location->update($values['okey'],stripslashes(urldecode($values['value'])), $data['location_key']);


	if ($location->updated) {
		$response= array('state'=>200,'newvalue'=>$location->new_value, 'msg'=>$location->msg, 'new_data'=>$location->new_data);

	} else {
		$response= array('state'=>400,'msg'=>$location->msg,'key'=>'','msg'=>$location->msg);
	}
	echo json_encode($response);

}





function edit_supplier_product_part($data) {



	if ($data['key']=='available') {

		if ($data['newvalue']=='Yes') {
			$available_state='Available';
		}
		elseif ($data['newvalue']=='No') {
			$available_state='No available';
		}
		else {
			$response= array('state'=>400,'msg'=>'not valid'.$data['newvalue'],'key'=>$data['key']);
			echo json_encode($response);
			exit;
		}


		$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part In Use`=%s where `Supplier Product Part Key`=%d",
			prepare_mysql($data['newvalue']),
			$data['sppl_key']
		);
		mysql_query($sql);

		$sql=sprintf("select `Part SKU` from `Supplier Product Part List` where  `Supplier Product Part Key`=%d  ",
			$data['sppl_key']);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {

			$part=new Part($row['Part SKU']);
			$part->update_availability();
		}



		$response= array('state'=>200,'newvalue'=>$data['newvalue'],'key'=>$data['key'],'available_state'=>$available_state);
		echo json_encode($response);
		exit;

	}else {
		$response= array('state'=>400,'msg'=>'not data ','key'=>$data['key']);
		echo json_encode($response);
		exit;

	}

}

function create_product($data) {
	global $editor;

	if (array_key_exists('Product Name',$data['values'])
		and  array_key_exists('Product Code',$data['values'])
		and  array_key_exists('Product Units',$data['values'])
		and  array_key_exists('Product Name',$data['values'])
		and  array_key_exists('Product Price',$data['values'])
		and  array_key_exists('Product Part Metadata',$data['values'])
		and  array_key_exists('Product Store Key',$data['values'])

	) {
		$part_sku=$data['values']['Product Part Metadata'];
		$family=new Family($data['parent_key']);

		$department_key=$family->data['Product Family Main Department Key'];
		$store_key=$data['values']['Product Store Key'];

		if ($store_key!=$family->data['Product Family Store Key']) {
			$response=array('state'=>400,'msg'=>'Error store key family do not match');
			echo json_encode($response);
			return;
		}

		$store=new Store($store_key);




		$product=new Product('create',array(
				'product stage'=>'Normal',
				'product sales type'=>'Public Sale',
				'product type'=>'Normal',
				'Product stage'=>'Normal',
				'product record type'=>'Normal',
				'Product Web Configuration'=>'Online Auto',
				'product store key'=>$store->id,
				'product currency'=>$store->data['Store Currency Code'],
				'product locale'=>$store->data['Store Locale'],
				'product price'=>$data['values']['Product Price'],
				'product rrp'=>$data['values']['Product RRP'],
				'product units per case'=>$data['values']['Product Units'],
				'product family key'=>$family->id,

				'product valid from'=>$editor['Date'],
				'product valid to'=>$editor['Date'],
				'Product Code'=>$data['values']['Product Code'],
				'Product Name'=>$data['values']['Product Name'],
				'Product Description'=>$data['values']['Product Description'],
				'Product Special Characteristic'=>$data['values']['Product Special Characteristic'],
				'Product Main Department Key'=>$department_key,
				'editor'=>$editor,

				'Product Part Metadata'=>$data['values']['Product Part Metadata']
			));

		if ($product->id) {
			if ($part_sku != 0) {
				$part= new Part($part_sku);
				$part_list[]=array(
					'Part SKU'=>$part->get('Part SKU'),
					'Parts Per Product'=>1,
					'Product Part Type'=>'Simple'
				);



				$product->new_current_part_list(array(),$part_list);

				$product->update_parts();
				$product->update_cost();
			}
			$response=array('state'=>200,'msg'=>$product->msg,'action'=>'created','object_key'=>$product->pid);

		}else {
			$response=array('state'=>400,'msg'=>$product->msg);


		}


		/*
		if (!$product->new) {
			$part= new Part($part_sku);
			$part_list[]=array(
				'Part SKU'=>$part->get('Part SKU'),
				'Parts Per Product'=>1,
				'Product Part Type'=>'Simple'
			);



			$product->new_current_part_list(array(),$part_list);

			$product->update_parts();
			$product->update_cost();

			$response=array('state'=>200,'msg'=>$product->msg,'action'=>'found','object_key'=>$product->pid);
		} else {

			$response=array('state'=>200,'msg'=>$product->msg,'action'=>'created', 'object_key'=>$product->pid);
		}

*/


	} else {
		$response=array('state'=>400,'msg'=>_('Error'));
	}
	echo json_encode($response);
}



function delete_parts_list($data) {
	global $user;
	$sql=sprintf("select `List Parent Key`,`List Key` from `List Dimension` where `List Key`=%d",$data['key']);

	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {

		//if (in_array($row['List Parent Key'],$user->stores)) {
		$sql=sprintf("delete from  `List Order Bridge` where `List Key`=%d",$data['key']);
		mysql_query($sql);
		$sql=sprintf("delete from  `List Dimension` where `List Key`=%d",$data['key']);
		mysql_query($sql);
		$response=array('state'=>200,'action'=>'deleted');
		echo json_encode($response);
		return;



		//} else {
		//$response=array('state'=>400,'msg'=>_('Forbidden Operation'));
		// echo json_encode($response);
		// return;
		//}



	} else {
		$response=array('state'=>400,'msg'=>'Error no order list');
		echo json_encode($response);
		return;

	}



}


function part_transactions() {



	if (isset( $_REQUEST['parent'])) {
		$parent=$_REQUEST['parent'];
	}else {
		return;
	}


	if (isset( $_REQUEST['parent_key'])) {
		$parent_key=$_REQUEST['parent_key'];
	}else {
		return;
	}


	if ($parent=='part') {
		$conf=$_SESSION['state']['part']['transactions'];

	}elseif ($parent=='warehouse') {
		$conf=$_SESSION['state']['warehouse']['transactions'];
	}else {
		return;
	}

	if (isset( $_REQUEST['elements']))
		$elements=$_REQUEST['elements'];
	else
		$elements=$conf['elements'];

	if (isset( $_REQUEST['from']))
		$from=$_REQUEST['from'];
	else
		$from=$conf['from'];
	if (isset( $_REQUEST['to']))
		$to=$_REQUEST['to'];
	else
		$to=$conf['to'];
	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=$conf['sf'];
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=$conf['nr'];
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order=$conf['order'];
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir=$conf['order_dir'];
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];

	if (isset( $_REQUEST['view']))
		$view=$_REQUEST['view'];
	else
		$view=$conf['view'];

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;


	list($date_interval,$error)=prepare_mysql_dates($from,$to);
	if ($error) {
		list($date_interval,$error)=prepare_mysql_dates($conf['from'],$conf['to']);
	} else {


		if ($parent=='part') {
			$_SESSION['state']['part']['transactions']['from']=$from;
			$_SESSION['state']['part']['transactions']['to']=$to;

		}elseif ($parent=='warehouse') {
			$_SESSION['state']['warehouse']['transactions']['from']=$from;
			$_SESSION['state']['warehouse']['transactions']['to']=$to;
		}
	}

	if ($parent=='part') {
		$_SESSION['state']['part']['transactions']=
			array(
			'view'=>$view,
			'order'=>$order,
			'order_dir'=>$order_direction,
			'nr'=>$number_results,
			'sf'=>$start_from,
			'f_field'=>$f_field,
			'f_value'=>$f_value,
			'from'=>$from,
			'to'=>$to,
			'elements'=>$elements,
			'f_show'=>$_SESSION['state']['part']['transactions']['f_show']
		);
	}elseif ($parent=='warehouse') {
		$_SESSION['state']['warehouse']['transactions']=
			array(
			'view'=>$view,
			'order'=>$order,
			'order_dir'=>$order_direction,
			'nr'=>$number_results,
			'sf'=>$start_from,
			'f_field'=>$f_field,
			'f_value'=>$f_value,
			'from'=>$from,
			'to'=>$to,
			'elements'=>$elements,
			'f_show'=>$_SESSION['state']['warehouse']['transactions']['f_show']
		);
	}

	$_order=$order;
	$_dir=$order_direction;
	$filter_msg='';
	$where='where true ';
	$wheref='';

	if ($f_field=='note' and $f_value!='') {
		// $wheref.=" and  `Note` like '%".addslashes($f_value)."%'  or  `Note` REGEXP '[[:<:]]".$f_value."'  ";
		$wheref.=" and  `Note` like '".addslashes($f_value)."%'  ";

	}

	if ($parent=='part') {
		$where=$where.sprintf(" and `Part SKU`=%d ",$parent_key);
	}else if ($parent=='warehouse') {
			$where=$where.sprintf(" and `Warehouse Key`=%d ",$parent_key);
		}


	switch ($view) {
	case 'oip_transactions':
		$where.=" and `Inventory Transaction Type`='Order In Process' ";
		break;
	case('in_transactions'):
		$where.=" and `Inventory Transaction Type` in ('In') ";
		break;
	case('move_transactions'):
		$where.=" and `Inventory Transaction Type` in ('Move') ";
		break;
	case('out_transactions'):
		$where.=" and `Inventory Transaction Type` in ('Sale','Broken','Lost') ";
		break;
	case('audit_transactions'):
		$where.="and `Inventory Transaction Type` in ('Not Found','No Dispatched','Associate','Disassociate','Audit') ";
		break;
	default:
		//$where.="and `Inventory Transaction Type` not in ('Move In','Move Out') ";
		break;
		break;
	}



	$sql="select count(*) as total from `Inventory Transaction Fact`     $where $wheref";


	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Inventory Transaction Fact`   $where ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}



	$rtext=$total.' '.ngettext('stock operation','stock operations',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';




	if ($total_records==0) {
		$rtext=_('No stock movements');
		$rtext_rpp='';
	}




	$rtext=number($total_records)." ".ngettext('stock operation','stock operations',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';




	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('note'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._("There isn't any note like")." <b>".$f_value."*</b> ";
			break;

		}
	}
	else if ($filtered>0) {
			switch ($f_field) {
			case('note'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total "._('notes with')." <b>".$f_value."*</b>";
				break;

			}
		}
	else
		$filter_msg='';



	$order=' `Date` desc , `Inventory Transaction Key` desc ';
	$order=' `Date` desc  ';

	$order_direction=' ';

	if ($parent=='part') {
		$sql="select `Inventory Transaction Key`,`Relations`,`User Alias`, ITF.`User Key`,`Required`,`Picked`,`Packed`,`Note`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Date`,ITF.`Location Key`,`Location Code` ,ITF.`Inventory Transaction Key` from `Inventory Transaction Fact` ITF left join `Location Dimension` L on (ITF.`Location key`=L.`Location key`) left join `User Dimension` U on (ITF.`User Key`=U.`User Key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results ";
	}
	else if ($parent=='warehouse') {
			$sql="select  `User Alias`,ITF.`User Key`,`Required`,`Picked`,`Packed`,`Note`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Date`,ITF.`Location Key`,`Location Code` ,ITF.`Inventory Transaction Key` from `Inventory Transaction Fact` ITF left join `Location Dimension` L on (ITF.`Location key`=L.`Location key`) left join `User Dimension` U on (ITF.`User Key`=U.`User Key`)   $where $wheref limit $start_from,$number_results ";
		}



	$result=mysql_query($sql);
	$adata=array();
	while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$qty=$data['Inventory Transaction Quantity'];



		if ($qty>0) {
			$qty='+'.$qty;
		}
		else if ($qty==0) {
				$qty='';
			}

		switch ($data['Inventory Transaction Type']) {
		case 'Order In Process':
			$transaction_type='OIP';
			$qty.='('.(-1*$data['Required']).')';
			break;

		case 'Move':
			$transaction_type=_('Move');
			$qty='&harr;';
			break;

		default:
			$transaction_type=$data['Inventory Transaction Type'];
			break;
		}


		if (in_array($data['Inventory Transaction Type'],array('Sale','Order In Process','Adjust','Associate','Disassociate') )) {

			$edit="";
			$delete="";
		}else {
			$delete='<img src="art/icons/delete.gif" alt="'._('Delete').'">';
			$edit='<img src="art/icons/edit.gif" alt="'._('Edit').'">';

		}


		$location=sprintf('<a href="location.php?id=%d">%s</a>',$data['Location Key'],$data {'Location Code'});
		$adata[]=array(
			'transaction_key'=>$data['Inventory Transaction Key'],
			'id'=>$data['Inventory Transaction Key'],

			'type'=>$transaction_type,
			'change'=>$qty,
			'date'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Date'])),
			'subject_data'=>strftime("%a %e %b %Y %H:%M %Z", strtotime($data['Date'])),
			'note'=>$data['Inventory Transaction Key'].' -> '.$data['Relations'].'*'.$data['Note'],
			'location'=>$location,
			'user'=>$data['User Alias'],
			'delete'=>$delete,
			'edit'=>$edit

		);
	}

	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records-$filtered,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

function delete_part_location_transaction($data) {

	$deleted=false;
	$msg='';
	$sql=sprintf("select * from `Inventory Transaction Fact` where `Inventory Transaction Key`=%d",$data['subject_key']);
	$res=mysql_query($sql);
	if ($row=mysql_fetch_assoc($res)) {
		if (in_array($row['Inventory Transaction Type'],array('Move In','Move Out','Sale','Adjust','Associate','Disassociate','Order In Process','No Dispatched'))) {
			$response=array('state'=>400,'msg'=>'transaction type can not be deleted');
			echo json_encode($response);
			exit;
		}else {

			switch ($row['Inventory Transaction Type']) {
			case 'Audit':
			case 'In':
				$sql=sprintf("delete from `Inventory Transaction Fact` where `Inventory Transaction Key`=%d",$row['Relations']);
				mysql_query($sql);
				$sql=sprintf("delete from `Inventory Transaction Fact` where `Inventory Transaction Key`=%d",$data['subject_key']);
				mysql_query($sql);





				$part_location= new PartLocation($row['Part SKU'].'_'.$row['Location Key']);
				$part_location->redo_adjusts();

				post_edit_transaction_actions($row['Part SKU'],$row['Location Key']);
				$deleted=true;
				break;

			}

		}


	}else {
		$response=array('state'=>400,'msg'=>_('Transaction not found'));
		echo json_encode($response);
		exit;
	}



	if ($deleted) {
		$response=array('state'=>200,'msg'=>$msg,'action'=>'deleted',
			'table_id'=>(isset($data['table_id'])?$data['table_id']:''),
			'recordIndex'=>(isset($data['recordIndex'])?$data['recordIndex']:''),
		);
	} else {
		$response=array('state'=>400,'msg'=>$msg);
	}
	echo json_encode($response);
}


function post_edit_transaction_actions($part_sku,$location_key) {


	$sql=sprintf("select `Inventory Transaction Type`,`Date` from `Inventory Transaction Fact`  where `Inventory Transaction Type` in ('Associate','Disassociate') and  `Part SKU`=%d and `Location Key`=%d order by `Date` desc ",$part_sku,$location_key);
	//print "$sql\n";
	$result3=mysql_query($sql);

	if ($row3=mysql_fetch_array($result3, MYSQL_ASSOC)   ) {
		//print_r($row3);
		if ($row3['Inventory Transaction Type']=='Disassociate') {
			$sql=sprintf("delete from `Part Location Dimension`  where   `Part SKU`=%d and `Location Key`=%d  ",$part_sku,$location_key);
			mysql_query($sql);
		}else {
			$pl_data=array(
				'Part SKU'=>$part_sku,
				'Location Key'=>$location_key,
				'Date'=>$row3['Date']);
			//print_r($pl_data);
			$part_location=new PartLocation('find',$pl_data,'create');

		}



	}else {
		$sql=sprintf("delete from `Part Location Dimension`  where   `Part SKU`=%d and `Location Key`=%d  ",$part_sku,$location_key);
		mysql_query($sql);
	}


}

function new_parts_list($data) {
	//print 'xx';exit;
	$list_name=$data['list_name'];
	//$store_id=$data['store_id'];

	$sql=sprintf("select * from `List Dimension`  where `List Name`=%s  and `List Scope`='Part' and `List Parent Key`=%d ",
		prepare_mysql($list_name),
		$data['parent_key']
	);
	$res=mysql_query($sql);

	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$response=array('resultset'=>
			array(
				'state'=>400,
				'msg'=>_('Another list has the same name')
			)
		);
		echo json_encode($response);
		return;
	}

	$list_type=$data['list_type'];

	$awhere=$data['awhere'];


	list($where,$table,$sql_type)=parts_awhere($awhere);

	//$where.=sprintf(' and `Product Store Key`=%d ',$store_id);



	if ($sql_type=='part')
		$sql="select count(Distinct P.`Part SKU`) as total from $table  $where ";
	else
		$sql="select count(Distinct ITF.`Part SKU`) as total from $table  $where";


	//print $sql;



	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


		if ($row['total']==0) {
			$response=array('resultset'=>
				array(
					'state'=>400,
					'msg'=>_('No products match this criteria')
				)
			);
			echo json_encode($response);
			return;

		}


	}
	mysql_free_result($res);


	//ar_edit_assets.php?tipo=new_parts_list&list_name=xxx&list_type=Static&parent_key=1&awhere={"invalid_tariff_code":"No","tariff_code":"","part_dispatched_from":"01-03-2012","part_dispatched_to":"10-03-2012","geo_constraints":"","part_valid_from":"","part_valid_to":""}

	$list_sql=sprintf("insert into `List Dimension` (`List Scope`,`List Parent Key`,`List Name`,`List Type`,`List Metadata`,`List Creation Date`) values ('Part',%d,%s,%s,%s,NOW())",
		$data['parent_key'],
		prepare_mysql($list_name),
		prepare_mysql($list_type),
		prepare_mysql(json_encode($data['awhere']))

	);
	mysql_query($list_sql);
	$customer_list_key=mysql_insert_id();

	if ($list_type=='Static') {


		$sql="select P.`Part SKU` from $table  $where group by P.`Part SKU`";
		//print $sql;exit;
		$result=mysql_query($sql);
		while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

			$customer_key=$data['Part SKU'];
			$sql=sprintf("insert into `List Part Bridge` (`List Key`,`Part SKU`) values (%d,%d)",
				$customer_list_key,
				$customer_key
			);
			mysql_query($sql);

		}
		mysql_free_result($result);




	}




	$response=array(
		'state'=>200,
		'customer_list_key'=>$customer_list_key

	);
	echo json_encode($response);

}

function delete_product() {
	$product = new Product('pid',$_REQUEST['pid']);

	$product->delete();
	if ($product->id) {
		if ($product->delete) {
			$response=array(
				'state'=>200,
				'msg'=>$product->msg,
				'family_key'=>$product->data['Product Family Key']
			);
		}
		else {
			$response=array(
				'state'=>400,
				'msg'=>$product->msg
			);
		}
	}else {
		$response=array(
			'state'=>400,
			'msg'=>'product not found'
		);
	}
	echo json_encode($response);
}

function location_audit($data) {

	$error = false;
	if (  ($_FILES["fileUpload"]["type"] == "text/plain")|| ($_FILES["fileUpload"]["type"] == "application/vnd.ms-excel")   || ($_FILES["fileUpload"]["type"] == "text/csv")  || ($_FILES["fileUpload"]["type"] == "application/csv")   || ($_FILES["fileUpload"]["type"] == "application/octet-stream")         ) {
		if ($_FILES["fileUpload"]["error"] > 0) {
			echo "Error: " . $_FILES["fileUpload"]["error"] . "<br />";
		} else {
			$target_path = "app_files/uploads/";

			$target_path = $target_path . basename( $_FILES['fileUpload']['name']);

			if (move_uploaded_file($_FILES['fileUpload']['tmp_name'], $target_path)) {
				$vv=basename( $_FILES['fileUpload']['name']);

				$report=array();
				$handle_csv = fopen($target_path, "r");
				while ($_cols = fgetcsv($handle_csv)) {

					if ($_cols[0]!='' and $_cols[1]!='' and $_cols[2]!='' ) {
						if ($_cols[3]=='') {
							$date=date('Y-m-d H:i:s');
						}
						else {
							$date=$_cols[3];
						}
						//print_r($_cols);
						$used_for='Picking';
						$location_data=array(
							'Location Warehouse Key'=>1,
							'Location Warehouse Area Key'=>1,
							'Location Code'=>$_cols[0],
							'Location Mainly Used For'=>$used_for
						);

						$location=new Location('find',$location_data,'create');




						$product=new Product('code_store',$_cols[1],1);

						if ($product->id) {
							//print_r($product->id);exit;

							$parts=$product->get_part_list();


							$parts_data=array_pop($parts);
							$part_sku=$parts_data['Part SKU'];


							//$part_location=new PartLocation($part_sku,$location->id);



							$location_key=$location->id;



							$part_location_data=array(
								'Location Key'=>$location_key
								,'Part SKU'=>$part_sku

							);

							if ($part_sku) {

								$part_location=new PartLocation('find',$part_location_data,'create');
								$note='';
								$qty= (float) $_cols[2];
								$date=$_cols[3];
								//print "$qty\n";
								//
								//
								$part_location->audit($qty,$note,$date);

							}else {
								$report['msg'][] = "Error";
								$report['record'][] = $_cols;
								$error = true;

							}
							//exit;
							//$part_location->editor=$editor;
							//$part_location->audit($qty,$note,$editor['Date']);

						}



					}
					else {
						if ($_cols[0]=='') {
							$report['msg'][] = "No Location code";
							$report['record'][] = $_cols;
							$error = true;

						}
						if ($_cols[1]=='') {
							$report['msg'][] = "No SKU";
							$report['record'][] = $_cols;
							$error = true;
						}
					}

					//exit;
				}





			}
		}
	}

	if ($error) {
		$response=array(
			'state'=>400,
			'msg'=>$report
		);
	}
	else {
		$response=array(
			'state'=>200,
			'msg'=>'ok'
		);
	}
	echo json_encode($response);

}


function update_deal_metadata_status($data) {

	$deal_metadata=new DealComponent($data['deal_metadata_key']);
	$deal_metadata->update_status($data['value']);


	if ($deal_metadata->error) {
		$response=array(
			'state'=>400,
			'msg'=>$deal_metadata->msg
		);
	}
	else {
		$response=array(
			'state'=>200,
			'msg'=>'ok',
			'key'=>$deal_metadata->id,
			'status'=>$deal_metadata->get_xhtml_status()

		);
	}
	echo json_encode($response);
}

function update_deal($data) {

	$deal=new Deal($data['deal_key']);
	$deal->update(array('Deal Name'=>$data['name'],'Deal Description'=>$data['description']));


	if ($deal->error) {
		$response=array(
			'state'=>400,
			'msg'=>$deal->msg
		);
	}
	else {
		$response=array(
			'state'=>200,
			'msg'=>'ok',
			'key'=>$deal->id,
			'name'=>$deal->data['Deal Name'],
			'description'=>$deal->data['Deal Description']

		);
	}
	echo json_encode($response);
}


function add_info_sheet_attachment($data) {
	global $editor;

	$product=new Part('pid',$data['pid']);
	if (!$product->sku) {
		$msg= "no product found";
		$response= array('state'=>400,'msg'=>$msg);
		echo base64_encode(json_encode($response));
		exit;
	}


	$msg='';
	$error=false;


	if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) == 'post') { //catch file overload error...
		$postMax = ini_get('post_max_size'); //grab the size limits...
		$msg= "File can not be attached, please note files larger than {$postMax} will result in this error!, let's us know, an we will increase the size limits"; // echo out error and solutions...
		$response= array('state'=>400,'msg'=>_('Files could not be attached').".<br>".$msg,'key'=>'attach');
		echo base64_encode(json_encode($response));
		exit;

	}

	foreach ($_FILES as $file_data) {

		$msg='';
		if ($file_data['size']==0) {
			$msg= "This file seems that is empty, have a look and try again"; // echo out error and solutions...
			$response= array('state'=>400,'msg'=>$msg,'key'=>'attach');
			echo base64_encode(json_encode($response));
			exit;

		}

		if ($file_data['error']) {
			$msg=$file_data['error'];
			if ($file_data['error']==4) {
				$msg=' '._('please choose a file, and try again');

			}
			$response= array('state'=>400,'msg'=>_('Files could not be attached')."<br/>".$msg,'key'=>'attach');
			echo base64_encode(json_encode($response));
			exit;
		}
		$_data=array(
			'file'=>$file_data['tmp_name']
		);

		$attach=new Attachment('find',$_data,'create');
		if ($attach->id) {

			$product->update_info_sheet_attachment($attach,$file_data['name'],$data['caption']);

			$error=$product->error;

		}else {
			$error=true;
			$msg=$attach->msg;
		}


	}

	if ($error) {
		$response= array('state'=>400,'msg'=>_('Files could not be attached')."<br/>".$msg,'key'=>'attach');
	} else {
		$response= array('state'=>200,'newvalue'=>array('attach_key'=>$product->data['Part info_sheet Attachment Bridge Key'],'attach_info'=>$product->data['Part Info Sheet Attachment XHTML Info']),'key'=>'attach','msg'=>$msg);
	}

	echo base64_encode(json_encode($response));
}

function delete_info_sheet_attachment($data) {
	global $editor;

	$product=new Part($data['sku']);
	if (!$product->sku) {
		$msg= "no part found";
		$response= array('state'=>400,'msg'=>$msg);
		echo json_encode($response);
		exit;
	}



	$product->delete_info_sheet_attachment();
	$msg=$product->msg;
	$error=$product->error;





	if ($error) {
		$response= array('state'=>400,'msg'=>$msg,'key'=>'attach');



	} else {
		$response= array('state'=>200);

	}

	echo json_encode($response);
}


?>
