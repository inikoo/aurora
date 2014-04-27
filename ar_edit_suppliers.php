<?php
require_once 'class.Timer.php';

require_once 'common.php';
require_once 'class.Company.php';
require_once 'class.Supplier.php';
require_once 'ar_edit_common.php';



if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}




$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case 'edit_supplier_product_part':
	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string'),
			'sppl_key'=>array('type'=>'key'),
			'key'=>array('type'=>'string'),
			'sp_id'=>array('type'=>'key'),
			'table_record_index'=>array('type'=>'numeric','optional'=>true)
		));
	edit_supplier_product_part($data);

break;
case('parts_in_supplier_product'):
	list_parts_in_supplier_product();
	break;

case('create_product'):

	$data=prepare_values($_REQUEST,array(
			'parent_key'=>array('type'=>'key'),
			'values'=>array('type'=>'json array')
		));

	create_product($data);
	break;
case('new_supplier'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array')

		));
	new_supplier($data);

	break;

case('supplier_products'):
	list_supplier_products();
	break;

	$data=prepare_values($_REQUEST,array(
			'pid'=>array('type'=>'key'),
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'okey'=>array('type'=>'string')
		));
	edit_supplier_product($data);
	break;

case('edit_supplier'):
case('edit_supplier_quick'):
	edit_supplier();
	break;
case('edit_supplier_product_supplier'):

case('edit_supplier_product_state'):
case('edit_supplier_product_unit'):
case('edit_supplier_product_description'):

case('edit_supplier_product_health_and_safety'):

case('edit_supplier_product'):
case('edit_product_description'):
case('edit_supplier_product_cost'):



	$data=prepare_values($_REQUEST,array(
			'sp_id'=>array('type'=>'key'),
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'okey'=>array('type'=>'string','optional'=>true),
			'table_record_index'=>array('type'=>'numeric','optional'=>true)

		));
	edit_supplier_product($data);
	break;
case('delete_MSDS_file'):
	require_once 'class.Attachment.php';
	$data=prepare_values($_REQUEST,array(
			'sp_id'=>array('type'=>'key'),
		));


	delete_MSDS_attachment($data);
	break;	
	
case('add_MSDS_file'):
	require_once 'class.Attachment.php';
	$data=prepare_values($_REQUEST,array(
			'sp_id'=>array('type'=>'key'),
		));
	$data['field']='Supplier Product MSDS Attachment Bridge Key';
	$data['caption']='';

	add_MSDS_attachment($data);
	break;	
case('edit_supplier_product_properties'):
	$data=prepare_values($_REQUEST,array(
			'values'=>array('type'=>'json array'),

			'sp_id'=>array('type'=>'key')
		));

	edit_supplier_product_properties($data);
		break;
case('complex_edit_supplier'):
	complex_edit_supplier();
	break;
case('edit_suppliers'):
	edit_suppliers();
	break;
case('delete_supplier_product'):
	$data=prepare_values($_REQUEST,array(
			'delete_type'=>array('type'=>'string')
			,'sp_id'=>array('type'=>'key')

		));

	delete_supplier_product($data);
	break;
default:
	$response=array('state'=>405,'resp'=>'Unknown Type');
	echo json_encode($response);

}

function edit_supplier() {
	global $editor;
	$key=$_REQUEST['key'];
	$okey=$key;

	$supplier=new supplier($_REQUEST['supplier_key']);

	$supplier->editor=$editor;

	if ($key=='Attach') {
		// print_r($_FILES);
		$note=stripslashes(urldecode($_REQUEST['newvalue']));
		$target_path = "uploads/".'attach_'.date('U');
		$original_name=$_FILES['testFile']['name'];
		$type=$_FILES['testFile']['type'];
		$data=array('Caption'=>$note,'Original Name'=>$original_name,'Type'=>$type);

		if (move_uploaded_file($_FILES['testFile']['tmp_name'],$target_path )) {
			$supplier->add_attach($target_path,$data);

		}
	}else {



		$key_dic=array(
			'name'=>'Supplier Company Name'
			,'code'=>'Supplier Code'
			,'contact'=>'Supplier Main Contact Name'
			,'email'=>'Supplier Main Plain Email'
			,'telephone'=>'Supplier Main Plain Telephone'
			,'fax'=>'Supplier Main Plain FAX'
			,'www'=>'Supplier Website'
			,"address"=>'Address'
			,"town"=>'Main Address Town'
			,"postcode"=>'Main Address Town'
			,"region"=>'Main Address Town'
			,"country"=>'Main Address Country'
			,"ship_address"=>'Main Ship To'
			,"ship_town"=>'Main Ship To Town'
			,"ship_postcode"=>'Main Ship To Postal Code'
			,"ship_region"=>'Main Ship To Country Region'
			,"ship_country"=>'Main Ship To Country'
			,"dispatch_time"=>'Supplier Average Delivery Days'

		);
		if (array_key_exists($_REQUEST['key'],$key_dic))
			$key=$key_dic[$_REQUEST['key']];

		$update_data=array($key=>stripslashes(urldecode($_REQUEST['newvalue'])));
		//print_r($update_data);
		$supplier->update($update_data);
	}

	if ($okey=='Supplier Products Origin Country Code') {
		$okey='origin';
	}


	if (!$supplier->error) {

		if ($supplier->updated)
			$response= array('state'=>200,'newvalue'=>$supplier->new_value,'key'=>$okey,'action'=>'updated');
		else
			$response= array('state'=>200,'newvalue'=>$supplier->get($key),'key'=>$okey,'action'=>'no_change');

	} else {
		$response= array('state'=>400,'msg'=>$supplier->msg,'key'=>$okey);
	}
	echo json_encode($response);

}

function edit_supplier_product_properties($data) {
	global $editor;

	$values=$data['values'];
	$supplier_product=new SupplierProduct('pid',$data['sp_id']);
	$supplier_product->editor=$editor;

	if (!$supplier_product->pid) {
		$response= array('state'=>400,'msg'=>'supplier product not found');
		echo json_encode($response);
		exit;
	}
	$response=array();

	// print_r($values);

	foreach ($values as $key=>$_data) {
		$_data['key']=$key;
		$_data['newvalue']=$_data['value'];
		$response[]=supplier_product_process_edit($supplier_product,$_data);
	}
	echo json_encode($response);
	exit;

}


function supplier_product_process_edit($supplier_product,$data) {

	$key_dic=array(
		'available_for_products_configuration'=>'Part Available for Products Configuration'
	);

	if (array_key_exists($data['key'],$key_dic))
		$key=$key_dic[$data['key']];
	else
		$key=$data['key'];

	$the_new_value=_trim($data['newvalue']);

	if (preg_match('/^custom_field_supplier_product/i',$key)) {
		$custom_id=preg_replace('/^custom_field_/','',$key);
		$supplier_product->update_custom_fields($key, $the_new_value);
	} else {
		//print "$key $the_new_value";
		$supplier_product->update(array($key=>$the_new_value));
	}

	if (!$supplier_product->error) {
		$response= array('state'=>200,'action'=>'updated','newvalue'=>$supplier_product->new_value,'key'=>$data['okey']);
		
	} else {
		$response= array('state'=>400,'msg'=>$supplier_product->msg,'key'=>$data['okey']);
	}

	return $response;


}



function edit_supplier_product($data) {
	$key=$data['key'];

	if (!isset($data['okey'])) {
		$data['okey']=$data['key'];
	}
	// if (isset($data['sph_key'])) {
	//  $supplier_product=new SupplierProduct('id',$data['sph_key']);
	// }elseif (isset($data['pid'])) {
	$supplier_product=new SupplierProduct('pid',$data['sp_id']);
	// }

	if (!$supplier_product->id) {
		$response= array('state'=>400,'msg'=>$supplier_product->msg,'key'=>$key);
		echo json_encode($response);
		exit;
	}



	global $editor;
	$supplier_product->editor=$editor;

	if ($key=='Attach') {
		// print_r($_FILES);
		$note=$data['newvalue'];
		$target_path = "uploads/".'attach_'.date('U');
		$original_name=$_FILES['testFile']['name'];
		$type=$_FILES['testFile']['type'];
		$data=array('Caption'=>$note,'Original Name'=>$original_name,'Type'=>$type);

		if (move_uploaded_file($_FILES['testFile']['tmp_name'],$target_path )) {
			$supplier_product->add_attach($target_path,$data);

		}
	}else {



		$key_dic=array(
			'name'=>'Supplier Product Name'
			,'code'=>'Supplier Product Code'
			,'description'=>'Supplier Product Description'
			,'unit_type'=>'Supplier Product Unit Type'
			,'units'=>'Supplier Product Units Per Case'
			,"cost"=>'SPH Case Cost'

			,"Supplier_Product_Supplier_Key"=>'supplier_key'
		);
		if (array_key_exists($key,$key_dic))
			$key=$key_dic[$key];


		$supplier_product->update(array($key=>$data['newvalue']));
	}


	if ($supplier_product->updated) {
	
	
	
		$response= array(
			'state'=>200,
			'newvalue'=>$supplier_product->new_value,
			'key'=>$data['okey'],
			'sp_current_key'=>$supplier_product->data['Supplier Product Current Key'],
			'sp_pid'=>$supplier_product->pid
			
		);
		if($key=='supplier_key'){
			$response['newdata']=$supplier_product->new_data;
		}


	} else {
		$response= array('state'=>400,'msg'=>$supplier_product->msg,'key'=>$key);
	}
	echo json_encode($response);

}




function complex_edit_supplier() {
	global $editor;
	if (!isset($_REQUEST['key']) ) {
		$response=array('state'=>400,'msg'=>'Error no key');
		echo json_encode($response);
		return;
	}
	if ( !isset($_REQUEST['newvalue']) ) {
		$response=array('state'=>400,'msg'=>'Error no value');
		echo json_encode($response);
		return;
	}
	if ( !isset($_REQUEST['id']) or !is_numeric($_REQUEST['id'])  ) {
		$supplier_key=$_SESSION['state']['supplier']['id'];
	}else
		$supplier_key=$_REQUEST['id'];

	$supplier=new Supplier($supplier_key);

	if (!$supplier->id) {
		$response=array('state'=>400,'msg'=>_('Supplier not found'));
		echo json_encode($response);
		return;
	}

	$translator=array(
		'name'=>'Supplier Name'
		,'fiscal_name'=>'Supplier Fiscal Name'
		,'tax_number'=>'Supplier Tax Number'
		,'registration_number'=>'Supplier Registration Number'


	);

	if (array_key_exists($_REQUEST['key'], $translator)) {
		$update_data=array(
			'editor'=>$editor
			,$translator[$_REQUEST['key']]=>stripslashes(urldecode($_REQUEST['newvalue']))
		);
		$supplier->update($update_data);

		if ($supplier->error_updated) {
			$response=array('state'=>200,'action'=>'error','msg'=>$supplier->msg_updated,'key'=>$_REQUEST['key']);
		}else {

			if ($supplier->updated) {
				$response=array('state'=>200,'action'=>'updated','msg'=>$supplier->msg_updated,'key'=>$_REQUEST['key'],'newvalue'=>$supplier->new_value);
			}else {
				$response=array('state'=>200,'action'=>'nochange','msg'=>$supplier->msg_updated,'key'=>$_REQUEST['key']);

			}

		}


	}else {
		$response=array('state'=>400,'msg'=>_('Key not in Supplier'));
	}
	echo json_encode($response);

}

function edit_suppliers() {
	global $myconf;

	$conf=$_SESSION['state']['suppliers']['edit_suppliers'];
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
	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field=$conf['f_field'];

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value=$conf['f_value'];
	if (isset( $_REQUEST['where']))
		$where=$_REQUEST['where'];
	else
		$where=$conf['where'];

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


	$_SESSION['state']['suppliers']['edit_suppliers']['order']=$order;
	$_SESSION['state']['suppliers']['edit_suppliers']['order_dir']=$order_direction;
	$_SESSION['state']['suppliers']['edit_suppliers']['nr']=$number_results;
	$_SESSION['state']['suppliers']['edit_suppliers']['sf']=$start_from;
	$_SESSION['state']['suppliers']['edit_suppliers']['f_field']=$f_field;
	$_SESSION['state']['suppliers']['edit_suppliers']['f_value']=$f_value;


	$_order=$order;
	$_dir=$order_direction;



	$wheref='';
	if ($f_field=='code'  and $f_value!='')
		$wheref.=" and `Supplier Code` like '".addslashes($f_value)."%'";
	if ($f_field=='name' and $f_value!='')
		$wheref.=" and  `Supplier Name` like '".addslashes($f_value)."%'";
	elseif ($f_field=='low' and is_numeric($f_value))
		$wheref.=" and lowstock>=$f_value  ";
	elseif ($f_field=='outofstock' and is_numeric($f_value))
		$wheref.=" and outofstock>=$f_value  ";


	$sql="select count(*) as total from `Supplier Dimension`    $where $wheref";
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0; $total_records=$total;
	}else {
		$sql="select count(*) as total from `Supplier Dimension` $where      ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}

	$rtext=number($total_records)." ".ngettext('supplier','suppliers',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';


	$filter_msg='';

	switch ($f_field) {
	case('code'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any supplier with code")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('suppliers with code')." <b>$f_value</b>*)";
		break;
	case('name'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any supplier with name")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('suppliers with name')." <b>$f_value</b>*)";
		break;
	case('low'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any supplier with more than ")." <b>".number($f_value)."</b> "._('low stock products');
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('Suppliers with')." <b><".number($f_value)."</b> "._('low stock products').")";
		break;
	case('outofstock'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any supplier with more than ")." <b>".number($f_value)."</b> "._('out of stock products');
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('Suppliers with')." <b><".number($f_value)."</b> "._('out of stock products').")";
		break;
	}



	$order='`Supplier Code`';
	if ($order=='id' or $order=='supplier_key')
		$order='`Supplier Key`';
	if ($order=='code')
		$order='`Supplier Code`';
	elseif ($order=='name')
		$order='`Supplier Name`';
	elseif ($order=='id')
		$order='`Supplier Key`';
	elseif ($order=='location')
		$order='`Supplier Location`';
	elseif ($order=='email')
		$order='`Supplier Main XHTML Email`';

	//    elseif($order='used_in')
	//        $order='Supplier Product XHTML Sold As';

	$sql="select *   from `Supplier Dimension` $where $wheref order by $order $order_direction limit $start_from,$number_results";
	// print $sql;
	$result=mysql_query($sql);
	$data=array();
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC) ) {



		$data[]=array(
			'supplier_key'=>$row['Supplier Key']
			,'id'=>''
			,'code'=>$row['Supplier Code']
			,'name'=>$row['Supplier Name']

			,'location'=>$row['Supplier Main Location']
			,'email'=>$row['Supplier Main Plain Email']
			,'go'=>sprintf("<a href='edit_supplier.php?id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Supplier Key'])

		);
	}


	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$data,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total
		)
	);
	echo json_encode($response);
}


function list_supplier_products() {



	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		exit;
	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		exit;


	if ($parent=='supplier') {
		$conf=$_SESSION['state']['supplier']['supplier_products'];
		$conf_table='supplier';
	}
	elseif ($parent=='none') {
		$conf=$_SESSION['state']['suppliers']['supplier_products'];
		$conf_table='suppliers';
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

	if (isset( $_REQUEST['elements_type']))
		$elements_type=$_REQUEST['elements_type'];
	else
		$elements_type=$conf['elements_type'];

	$elements=$conf['elements'];
	if (isset( $_REQUEST['elements_sp_state_Available'])) {
		$elements['state']['Available']=$_REQUEST['elements_sp_state_Available'];
	}
	if (isset( $_REQUEST['elements_sp_state_NoAvailable'])) {
		$elements['state']['NoAvailable']=$_REQUEST['elements_sp_state_NoAvailable'];
	}
	if (isset( $_REQUEST['elements_sp_state_Discontinued'])) {
		$elements['state']['Discontinued']=$_REQUEST['elements_sp_state_Discontinued'];
	}





	$filter_msg='';
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
	$_order=$order;
	$_dir=$order_direction;




	$_SESSION['state'][$conf_table]['supplier_products']['order']=$order;
	$_SESSION['state'][$conf_table]['supplier_products']['order_dir']=$order_dir;
	$_SESSION['state'][$conf_table]['supplier_products']['nr']=$number_results;
	$_SESSION['state'][$conf_table]['supplier_products']['sf']=$start_from;
	$_SESSION['state'][$conf_table]['supplier_products']['f_field']=$f_field;
	$_SESSION['state'][$conf_table]['supplier_products']['f_value']=$f_value;
	$_SESSION['state'][$conf_table]['supplier_products']['elements']=$elements;



	switch ($parent) {
	case 'none':
		$where=' where true ';
		break;
	case 'supplier':
		$where=sprintf(' where  `Supplier Key`=%d',$parent_key);
		break;
	}

	switch ($elements_type) {

	case('state'):
		$_elements='';
		$num_elements_checked=0;
		foreach ($elements['state'] as $_key=>$_value) {
			if ($_value) {
				$num_elements_checked++;

				$_elements.=", '$_key'";
			}
		}

		if ($_elements=='') {
			$where.=' and false' ;
		}elseif ($num_elements_checked<3) {
			$_elements=preg_replace('/^,/','',$_elements);
			$where.=' and `Supplier Product State` in ('.$_elements.')' ;
		}
		break;

	}



	$wheref='';


	if (($f_field=='code' ) and $f_value!='')
		$wheref.=" and  `Supplier Product XHTML Sold As` like '".addslashes($f_value)."%'";
	if ($f_field=='sup_code' and $f_value!='')
		$wheref.=" and  `Supplier Product Code` like '".addslashes($f_value)."%'";








	$sql="select count(*) as total from `Supplier Product Dimension`  $where $wheref ";


	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {

		$sql="select count(*) as total from `Supplier Product Dimension`  $where  ";

		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}




	$rtext=number($total_records)." ".ngettext('product','products',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';
	$filter_msg='';

	switch ($f_field) {
	case('p.code'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('products with code')." <b>".$f_value."*</b>)";
		break;
	case('sup_code'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with supplier code")." <b>".$f_value."*</b> ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('products with supplier code')." <b>".$f_value."*</b>)";
		break;

	}
	if ($order=='id')
		$order='`Supplier Product ID`';
	elseif ($order=='code')
		$order='`Supplier Product Code`';
	elseif ($order='usedin')
		$order='`Supplier Product XHTML Sold As`';

	$sql="select * from `Supplier Product Dimension` left join `Supplier Product History Dimension` H  on (`SPH Key`=`Supplier Product Current Key`)  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
	$data=array();

	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {






		switch ($row['Supplier Product State']) {
		case 'Available':
			$state=sprintf('<img src="art/icons/brick.png" title="%s">',_('Available'));
			break;
		case 'No Available':
			$state=sprintf('<img src="art/icons/brick_error.png" title="%s">',_('No Available'));


			break;
		case 'Discontined':
			$state=sprintf('<img src="art/icons/brick_none.png" title="%s">',_('Discontined'));


			break;
		default:
			$state='';
		}

		$data[]=array(
			'sp_id'=>$row['Supplier Product ID'],
			'sph_key'=>$row['Supplier Product Current Key'],
			'code'=>$row['Supplier Product Code'],
			'go'=>sprintf("<a href='edit_supplier_product.php?pid=%d'><img src='art/icons/page_go.png' alt='go'></a>",
				$row['Supplier Product ID']),


			'name'=>$row['Supplier Product Name'],
			'cost'=>money($row['SPH Case Cost']),
			'usedin'=>$row['Supplier Product XHTML Sold As'],
			'unit_type'=>$row['Supplier Product Unit Type'],
			'units'=>$row['Supplier Product Units Per Case'],

			'state'=>$state,
			'state_value'=>$row['Supplier Product State']


		);
	}


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data,
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

function new_supplier($data) {
	global $editor;
	$supplier_data=array();
	foreach ($data['values'] as $key=>$value) {
		$supplier_data[preg_replace('/^company /i','Supplier ',$key)]=$value;
	}

	$supplier_data['editor']=$editor;
	//print_r($supplier_data);exit;


	$supplier=new Supplier('find',$supplier_data,'create');
	if ($supplier->new) {
		$response= array('state'=>200,'action'=>'created','supplier_key'=>$supplier->id);
	}else {
		if ($supplier->found)
			$response= array('state'=>400,'action'=>'found','supplier_key'=>$supplier->found_key);
		else
			$response= array('state'=>400,'action'=>'error','supplier_key'=>0,'msg'=>$supplier->msg);
	}


	echo json_encode($response);

}

function create_product($data) {
	global $editor;

	$sp_data=$data['values'];


	$sp_data['editor']=$editor;
	$sp_data['Supplier Key']=$data['parent_key'];
	$sp_data['Supplier Key']=$data['parent_key'];
	$sp_data['Supplier Product Valid From']=gmdate("Y-m-d H:i:s");



	$supplier_product=new SupplierProduct('find',$sp_data,'create');


	if ($supplier_product->new) {
		$msg=_('Supplier Product logged');
		$response= array('state'=>200,'action'=>'created_','object_key'=>$supplier_product->id_,'msg'=>$msg);
	} else {
		if ($supplier_product->found)
			$response= array('state'=>400,'action'=>'found','object_key'=>$supplier_product->found_key,'msg'=>_('Product already in the database'));
		else
			$response= array('state'=>400,'action'=>'error','object_key'=>0,'msg'=>$supplier_product->msg);
	}


	echo json_encode($response);

}

function add_MSDS_attachment($data) {
	global $editor;

	$supplier_product=new SupplierProduct('pid',$data['sp_id']);
	if (!$supplier_product->pid) {
		$msg= "no sp found";
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

			$supplier_product->update_MSDS_attachment($attach,$file_data['name'],$data['caption']);

			$error=$supplier_product->error;

		}else {
			$error=true;
			$msg=$attach->msg;
		}


	}

	if ($error) {
		$response= array('state'=>400,'msg'=>_('Files could not be attached')."<br/>".$msg,'key'=>'attach');
	} else {
		$response= array('state'=>200,'newvalue'=>array('attach_key'=>$supplier_product->data['Supplier Product MSDS Attachment Bridge Key'],'attach_info'=>$supplier_product->data['Supplier Product MSDS Attachment XHTML Info']),'key'=>'attach','msg'=>$msg);
	}

	echo base64_encode(json_encode($response));
}
function delete_MSDS_attachment($data) {
	global $editor;

	$supplier_product=new SupplierProduct('pid',$data['sp_id']);
	if (!$supplier_product->pid) {
		$msg= "no sp found";
		$response= array('state'=>400,'msg'=>$msg);
		echo json_encode($response);
		exit;
	}



	$supplier_product->delete_MSDS_attachment();
	$msg=$supplier_product->msg;
	$error=$supplier_product->error;





	if ($error) {
		$response= array('state'=>400,'msg'=>$msg,'key'=>'attach');



	} else {
		$response= array('state'=>200);

	}

	echo json_encode($response);
}

function list_parts_in_supplier_product() {

	$conf=$_SESSION['state']['supplier_product']['parts'];


	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else {
		exit("");
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



	$_SESSION['state']['supplier_products']['parts']['order']=$order;
	$_SESSION['state']['supplier_products']['parts']['order_dir']=$order_direction;
	$_SESSION['state']['supplier_products']['parts']['nr']=$number_results;
	$_SESSION['state']['supplier_products']['parts']['sf']=$start_from;
	$_SESSION['state']['supplier_products']['parts']['f_field']=$f_field;
	$_SESSION['state']['supplier_products']['parts']['f_value']=$f_value;


	if ($parent_key) {

		$filter_msg='';

		$wheref='';
		$where=sprintf("where `Supplier Product Part Most Recent`='Yes' and  PP.`Supplier Product ID`=%d ",$parent_key);;

		if ($f_field=='code' and $f_value!='')
			$wheref.=sprintf(" and `Supplier Product Code` like '%s%%'   ",addslashes($f_value));



		$sql="select count(*) as total from `Supplier Product Part List`  L  left join `Supplier Product Part Dimension` PP on (L.`Supplier Product Part Key`=PP.`Supplier Product Part Key`) left join `Supplier Product Dimension` P on (P.`Supplier Product ID`=PP.`Supplier Product ID`)left join `Supplier Dimension` S on (P.`Supplier Key`=S.`Supplier Key`)  $where $wheref";
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
			$sql="select count(*) as total from `Supplier Product Part List`  L  left join `Supplier Product Part Dimension` PP on (L.`Supplier Product Part Key`=PP.`Supplier Product Part Key`) left join `Supplier Product Dimension` P on (P.`Supplier Product ID`=PP.`Supplier Product ID`)left join `Supplier Dimension` S on (P.`Supplier Key`=S.`Supplier Key`)  $where ";
			//print $sql;
			$result=mysql_query($sql);
			if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
				$total_records=$row['total'];
				$filtered=$total_records-$total;
			}
			mysql_free_result($result);

		}


		$rtext=number($total_records)." ".ngettext('supplier product','supplier products',$total_records);
		if ($total_records>$number_results)
			$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
		else
			$rtext_rpp=' ('._('Showing all').')';

		if ($total==0 and $filtered>0) {
			switch ($f_field) {
			case('code'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with this code ")." <b>".$f_value."*</b> ";
				break;
			case('description'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with description like ")." <b>".$f_value."*</b> ";
				break;
			}
		}
		elseif ($filtered>0) {
			switch ($f_field) {
			case('code'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('product with code like')." <b>".$f_value."*</b>";
				break;
			case('description'):
				$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('product with description like')." <b>".$f_value."*</b>";
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


		$order='PP.`Supplier Product ID`';


		$sql="select PP.`Supplier Product Part Key`,`Part Unit Description`,`Part Reference`,`Supplier Product Status`,`Supplier Product Part Most Recent`,`Supplier Product Part Valid To`,`Supplier Product Part Valid From`,P.`Supplier Product ID`,`Supplier Product Part List Key`,`Supplier Product Part In Use`,`Supplier Product Name`,`Supplier Product Units Per Part`,Pa.`Part SKU`,`Supplier Product Code` ,S.`Supplier Code`,S.`Supplier Key`
		from `Supplier Product Part List` L
		left join `Supplier Product Part Dimension` PP on (L.`Supplier Product Part Key`=PP.`Supplier Product Part Key`)
		left join `Supplier Product Dimension` P on (P.`Supplier Product ID`=PP.`Supplier Product ID`)
		left join `Part Dimension` Pa on (Pa.`Part SKU`=L.`Part SKU`)
		left join `Supplier Dimension` S on (P.`Supplier Key`=S.`Supplier Key`) $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
		//print $sql;
		$res = mysql_query($sql);
		$total=mysql_num_rows($res);
		$adata=array();
		while ($row=mysql_fetch_array($res, MYSQL_ASSOC) ) {
			// $meta_data=preg_split('/,/',$row['Deal Component Allowance']);

			if ($row['Supplier Product Part In Use']=='Yes') {
				$available_state=_('Available');
			} else {
				$available_state=_('No available');
			}

			if ($row['Supplier Product Part Most Recent']=='Yes') {

				if ($row['Supplier Product Part In Use']=='Yes') {
					$state=sprintf('<img style="vertical-align:-6px" src="art/icons/link.png" title="%s"> %s',_('Available'),_('Linked to part'));
					$state_value='Available';
				} else {
					$state=sprintf('<img src="art/icons/link.png" title="%s"> %s',_('No Available'),_('Linked to part'));
					$state_value='NoAvailable';
				}



			} else {
				$state=sprintf('<img src="art/icons/link_broken.png" title="%s"> %s',_('Discontined'),_('Remove link'));
				$state_value='Discontined';
			}









			$relation=$row['Supplier Product Units Per Part'].' &rarr; 1';
			$adata[]=array(
				'sppl_key'=>$row['Supplier Product Part Key'],
				'sku'=>$row['Part SKU'],

				'relation'=>$relation,
				
				'formated_sku'=>sprintf('<a href="part.php?sku=%d">SKU%05d</a>',$row['Part SKU'],$row['Part SKU']),
			'reference'=>sprintf('<a href="part.php?sku=%d">%s</a>',$row['Part SKU'],$row['Part Reference']),
				
				'name'=>$row['Part Unit Description'].'<br>'.$row['Supplier Product Part Valid From'].' &rarr; '.$row['Supplier Product Part Valid To'],
			//	'supplier'=>'<a href="supplier.php?id='.$row['Supplier Key'].'">'.$row['Supplier Code'].'</a>',
				'state'=>$state,
				'state_value'=>$state_value,
				'available_state'=>$available_state
				//'available'=>$row['Supplier Product Part In Use'],
				//'available_state'=>$available_state,
				//'status'=>$row['Supplier Product Part Most Recent'],
				//'formated_status'=>$formated_status
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

function edit_supplier_product_part($data) {

	//include_once('class.SupplierProduct.php');

	if ($data['key']=='state') {
		$value=$data['newvalue'];
		if (!in_array($value,array('Unlink','Link'))) {
			$msg='wrong Supplier Product Part State value: '.$value;
			
				$response= array('state'=>400,'msg'=>$msg,'key'=>$data['key']);
			echo json_encode($response);
			exit;
			return;
		}


		switch ($value) {
		case 'Unlink':
			$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Most Recent`='No',`Supplier Product Part In Use`='No' ,`Supplier Product Part Valid To`=%s  where `Supplier Product Part Key`=%d",

				prepare_mysql(gmdate('Y-m-d H:i:s')),
				$data['sppl_key']
			);
			mysql_query($sql);
//print $sql;
			$state=sprintf('<img src="art/icons/brick_none.png" title="%s"> %s',_('Discontined'),_('Discontined'));


			break;
	case 'Link':
		$state=sprintf('<img src="art/icons/link.png" title="%s"> %s',_('Link to part'),_('Linked to part'));

		}

		$sql=sprintf("select `Part SKU` from `Supplier Product Part List` where  `Supplier Product Part Key`=%d  ",
			$data['sppl_key']);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {

			$part=new Part($row['Part SKU']);
			$part->update_availability();
		}

		$sql=sprintf("select `Supplier Product ID` from `Supplier Product Part Dimension` where  `Supplier Product Part Key`=%d  ",
			$data['sppl_key']);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {

			$supplier_product=new SupplierProduct('pid',$row['Supplier Product ID']);
			$supplier_product->update_availability();
		}



		$response= array('state'=>200,'newvalue'=>$value,'key'=>$data['key'],'state_formated'=>$state,'state_value'=>$value);
		
		if(isset($data['table_record_index'])){
		$response['record_index']=(integer) $data['table_record_index'];
		}
		
		echo json_encode($response);
		exit;




	}


	elseif ($data['key']=='available') {

		if ($data['newvalue']=='Yes') {
			$available_state=_('Available');
		}
		elseif ($data['newvalue']=='No') {
			$available_state=_('No available');
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

		$sql=sprintf("select `Supplier Product ID` from `Supplier Product Part Dimension` where  `Supplier Product Part Key`=%d  ",
			$data['sppl_key']);
		$res=mysql_query($sql);

		while ($row=mysql_fetch_assoc($res)) {

			$supplier_product=new SupplierProduct('pid',$row['Supplier Product ID']);
			$supplier_product->update_availability();
		}



		$response= array('state'=>200,'newvalue'=>$data['newvalue'],'key'=>$data['key'],'available_state'=>$available_state);
		echo json_encode($response);
		exit;

	}

	else if ($data['key']=='status') {

			if ($data['newvalue']=='Yes') {
				$available_state=_('OK');
			}
			elseif ($data['newvalue']=='No') {
				$available_state=_('Remove');
			}
			else {
				$response= array('state'=>400,'msg'=>'not valid'.$data['newvalue'],'key'=>$data['key']);
				echo json_encode($response);
				exit;
			}


			$sql=sprintf("update `Supplier Product Part Dimension` set `Supplier Product Part Most Recent`=%s ,`Supplier Product Part Valid To`=%s  where `Supplier Product Part Key`=%d",
				prepare_mysql($data['newvalue']),
				prepare_mysql(gmdate('Y-m-d H:i:s')),
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

			$sql=sprintf("select `Supplier Product ID` from `Supplier Product Part Dimension` where  `Supplier Product Part Key`=%d  ",
				$data['sppl_key']);
			$res=mysql_query($sql);

			while ($row=mysql_fetch_assoc($res)) {

				$supplier_product=new SupplierProduct('pid',$row['Supplier Product ID']);
				$supplier_product->update_availability();
			}



			$response= array('state'=>200,'newvalue'=>$data['newvalue'],'key'=>$data['key'],'formated_status'=>$available_state);
			echo json_encode($response);
			exit;

		}
	else {
		$response= array('state'=>400,'msg'=>'not data ','key'=>$data['key']);
		echo json_encode($response);
		exit;

	}

}

?>
