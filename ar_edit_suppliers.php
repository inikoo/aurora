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
case('edit_product_description'):
	$data=prepare_values($_REQUEST,array(
			'pid'=>array('type'=>'key'),
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string'),
			'okey'=>array('type'=>'string')
		));
	edit_supplier_product($data);
	break;
case('edit_product_supplier'):
	$data=prepare_values($_REQUEST,array(
			'sph_key'=>array('type'=>'key'),
			'newvalue'=>array('type'=>'string'),
			'key'=>array('type'=>'string')

		));
	edit_supplier_product($data);
	break;
case('edit_supplier'):
case('edit_supplier_quick'):
	edit_supplier();
	break;
case('edit_supplier_product'):

	$data=prepare_values($_REQUEST,array(
			'newvalue'=>array('type'=>'string')
			,'key'=>array('type'=>'string')
			,'sph_key'=>array('type'=>'key')

		));
	edit_supplier_product($data);
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
			,'sph_key'=>array('type'=>'key')

		));

	$data['delete_type']=preg_replace('/discontinue/','Discontinued',$data['delete_type']);
	$data['delete_type']=preg_replace('/delete/','Deleted',$data['delete_type']);

	$data['newvalue']=$data['delete_type'];
	$data['key']='Supplier Product Buy State';
	//print_r($data);
	edit_supplier_product($data);
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

	if($okey=='Supplier Products Origin Country Code'){
	$okey='origin';
	}


	if (!$supplier->error) {
		
		if($supplier->updated)
		$response= array('state'=>200,'newvalue'=>$supplier->new_value,'key'=>$okey,'action'=>'updated');
		else
		$response= array('state'=>200,'newvalue'=>$supplier->get($key),'key'=>$okey,'action'=>'no_change');

	} else {
		$response= array('state'=>400,'msg'=>$supplier->msg,'key'=>$okey);
	}
	echo json_encode($response);

}

function edit_supplier_product($data) {
	$key=$data['key'];

	if (!isset($data['okey'])) {
		$data['okey']=$data['key'];
	}
	if (isset($data['sph_key'])) {
		$supplier_product=new SupplierProduct('id',$data['sph_key']);
	}elseif (isset($data['pid'])) {
		$supplier_product=new SupplierProduct('pid',$data['pid']);
	}

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

		if ($key=='Supplier Product Buy State') {
			if ($supplier_product->new_value=='Discontinued') {
				$response['action']='discontinued';
				$response['delete']='<img src="art/icons/delete.png" title="'._('Discontinue').'"/>';
				$response['delete_type']='delete';
			}else if ($supplier_product->new_value=='Deleted') {
					$response['action']='deleted';
					$response['delete']='';
					$response['delete_type']='';

				}


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
	elseif($total_records)
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
	elseif($total_records)
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



		if ($row['Supplier Product Buy State']=='Ok') {
			$delete_type='discontinue';

			$delete='<img src="art/icons/discontinue.png" title="'._('Discontinue').'"/>';
		}elseif ($row['Supplier Product Buy State']=='Discontinued') {
			$delete='<img src="art/icons/delete.png"/>';
			$delete_type='delete';
		}else {
			$delete='';
			$delete_type='';
		}


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
			'sph_key'=>$row['Supplier Product Current Key']
			,'code'=>$row['Supplier Product Code']
			,'go'=>sprintf("<a href='edit_supplier_product.php?pid=%d'><img src='art/icons/page_go.png' alt='go'></a>"
				,$row['Supplier Product ID'])


			,'name'=>$row['Supplier Product Name']
			,'cost'=>money($row['SPH Case Cost'])
			,'usedin'=>$row['Supplier Product XHTML Sold As']
			,'unit_type'=>$row['Supplier Product Unit Type']
			,'units'=>$row['Supplier Product Units Per Case']
			,'delete'=>$delete
			,'delete_type'=>$delete_type
			,'state'=>$state


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

?>
