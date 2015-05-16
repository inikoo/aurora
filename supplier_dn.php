<?php
include_once 'common.php';
include_once 'class.Staff.php';

include_once 'class.Supplier.php';
include_once 'class.PurchaseOrder.php';
include_once 'class.SupplierDeliveryNote.php';
include_once 'class.CompanyArea.php';


$corporation=new Account();

///print_r($_REQUEST);

$po_keys=array();
if (isset($_REQUEST['id'])) {

	$supplier_delivery_note=new SupplierDeliveryNote($_REQUEST['id']);
	if (!$supplier_delivery_note->id)
		exit("Error supplier deliver note can no be found");
	$supplier=new Supplier('id',$supplier_delivery_note->data['Supplier Delivery Note Supplier Key']);


}
else if (isset($_REQUEST['new']) ) {

		$supplier_key=false;


		if (isset($_REQUEST['supplier_key']) and is_numeric($_REQUEST['supplier_key'])) {
			$supplier_key=$_REQUEST['supplier_key'];
		}


		if (isset($_REQUEST['po'])) {


			if (!isset($_REQUEST['number']) or $_REQUEST['number']=='') {
				exit('No Supplier Delivery Note Public ID');
			}


			$supplier_dn_public_id=stripslashes(urldecode($_REQUEST['number']));
			$dn_date='';
			if (isset($_REQUEST['date'])) {
				$_date=$_REQUEST['date'];



				$date_data=prepare_mysql_datetime($_date,'date');
				if ($date_data['ok']) {

					$dn_date=$date_data['mysql_date'];
				}

			}


			$po_keys=preg_split('/,/',$_REQUEST['po']);
			$po_objects=array();
			$po_array=array();
			$supplier_key=false;
			foreach ($po_keys as $po_key) {
				if (!is_numeric($po_key))
					continue;
				$po=new PurchaseOrder($po_key);
				if (!$po->id)
					continue;
				if (!$supplier_key)
					$supplier_key=$po->data['Purchase Order Supplier Key'];
				else {
					if ($supplier_key!=$po->data['Purchase Order Supplier Key'])
						continue;
				}


				if ($po->data['Purchase Order State']=='Submitted' or $po->data['Purchase Order State']=='In Process' or $po->data['Purchase Order State']=='Confirmed' ) {
					$po_objects[$po->id]=$po;
					$po_array[$po->id]=$po->id;
				}

			}



		}
		elseif (isset($_REQUEST['supplier_key']) and is_numeric($_REQUEST['supplier_key'])) {
			$supplier_key=$_REQUEST['supplier_key'];
		}else {
			exit('no po or supplier key');
		}

		$supplier=new Supplier($supplier_key);
		if (!$supplier->id) {
			exit("error supplier not found/supplier incorrect");
		}





		$editor=array(
			'Author Name'=>$user->data['User Alias'],
			'Author Type'=>$user->data['User Type'],
			'Author Key'=>$user->data['User Parent Key'],
			'User Key'=>$user->id
		);

		$data=array(
			'Supplier Delivery Note Supplier Key'=>$supplier->id
			,'Supplier Delivery Note Public ID'=>$supplier_dn_public_id
			,'Supplier Delivery Note Date'=>$dn_date

			,'editor'=>$editor
		);

		$supplier_delivery_note=new SupplierDeliveryNote('find',$data,'create');
		$supplier_delivery_note->update_pos($po_array);
		$supplier_delivery_note->creating_take_values_from_pos();
		if ($supplier_delivery_note->error or !$supplier_delivery_note->id) {
			print_r($supplier_delivery_note);
			exit('error when creating the supplier deliver note');
		}



		header('Location: supplier_dn.php?id='.$supplier_delivery_note->id);
		exit;


	} else {

	exit("error");
}


$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	$yui_path.'button/assets/skins/sam/button.css',
	$yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
	'css/common.css',
	'css/button.css',
	'css/container.css',
	'css/table.css',
	'css/porder.css',

	'theme.css.php'
);

$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/jquery.min.js',
	'js/php.default.min.js',
	'js/fz.shadow.js',
	'js/fz.js',
	'js/imgpop.js',
	'js/notes.js',
	'js/search.js',
	'js/common.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/supplier_dn.js'
);




$supplier_delivery_note_id = $supplier_delivery_note->id;

$smarty->assign('supplier_dn',$supplier_delivery_note);
$smarty->assign('supplier',$supplier);
$smarty->assign('supplier_id',$supplier->id);

$smarty->assign('search_label',_('Search'));
$smarty->assign('search_scope','supplier_products');

$smarty->assign('title',_('Supplier Delivery Note').': '.$supplier_delivery_note->data['Supplier Delivery Note Public ID']);
$smarty->assign('view',$_SESSION['state']['supplier_dn']['view']);

$tipo_filter=$_SESSION['state']['supplier_dn']['products']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['supplier_dn']['products']['f_value']);
$filter_menu=array(
	'p.code'=>array('db_key'=>'p.code','menu_label'=>_('Our Product Code'),'label'=>_('Code')),
	'code'=>array('db_key'=>'code','menu_label'=>_('Supplier Product Code'),'label'=>_('Supplier Code')),
);
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

//print $supplier_delivery_note->data['Supplier Delivery Note Current State'];

$smarty->assign('parent','suppliers');


$elements_number=array('Notes'=>0,'Changes'=>0,'Attachments'=>0);
$sql=sprintf("select count(*) as num , `Type` from  `Supplier Delivery Note History Bridge` where `Supplier Delivery Note Key`=%d group by `Type`",
	$supplier_delivery_note->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=$row['num'];
}
$smarty->assign('elements_po_history_number',$elements_number);
$smarty->assign('elements_po_history',$_SESSION['state']['supplier_dn']['history']['elements']);


$smarty->assign('number_attachments',$supplier_delivery_note->get_number_attachments_formated());



$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with  notes *<i>x</i>*'),'label'=>_('Notes')),
	//   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Done by')),
	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)'))
);
$tipo_filter=$_SESSION['state']['supplier_dn']['history']['f_field'];
$filter_value=$_SESSION['state']['supplier_dn']['history']['f_value'];

$smarty->assign('filter_value3',$filter_value);
$smarty->assign('filter_menu3',$filter_menu);
$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);



$pos_data=array();
foreach ($supplier_delivery_note->get_purchase_orders_objects() as $po) {
	$current_po_key=$po->id;

	$pos_data[]=array(
		'key'=>$po->id,
		'number'=>$po->data['Purchase Order Public ID'],
		'state'=>$po->data['Purchase Order State'],

	);

}
$number_pos=count($pos_data);
if ($number_pos!=1) {
	$current_po_key='';
}
$smarty->assign('current_po_key',$current_po_key);
$smarty->assign('number_pos',$number_pos);
$smarty->assign('pos_data',$pos_data);



switch ($supplier_delivery_note->data['Supplier Delivery Note Current State']) {
case('In Process'):



	$_SESSION['state']['supplier_dn']['products']['display']='ordered_products';
	$smarty->assign('products_display_type',$_SESSION['state']['supplier_dn']['products']['display']);




	$submit_method=array(
		'Internet'=>array('fname'=>_('Internet')),
		'Telephone'=>array('fname'=>_('Telephone')),
		'Fax'=>array('fname'=>_('Fax')),
		'In Person'=>array('fname'=>_('In Person')),
		'Email'=>array('fname'=>_('Email')),
		'Post'=>array('fname'=>_('Post')),
		'Other'=>array('fname'=>_('Other'),'selected'=>true)

	);
	$smarty->assign('default_submit_method','Other');
	$smarty->assign('submit_method',$submit_method);

	//$smarty->assign('user_alias',$user->data['User Alias']);
	//$smarty->assign('user_staff_key',$user->data['User Parent Key']);





	$js_files[]='js/supplier_dn_in_process.js';



	$template='supplier_dn_in_process.tpl';

	break;
case('Inputted'):

	$smarty->assign('products_display_type',$_SESSION['state']['supplier_dn']['products']['display']);




	$company_area=new CompanyArea('code','WAH');
	$operators=$company_area->get_current_staff_with_position_code('WAH.SK');
	$number_cols=5;
	$row=0;
	$operators_data=array();
	$contador=0;
	foreach ($operators as $operator) {
		if (fmod($contador,$number_cols)==0 and $contador>0) {
			$row++;
		}
		$tmp=array();
		foreach ($operator as $key=>$value) {
			$tmp[preg_replace('/\s/','',$key)]=$value;
		}
		$operators_data[$row][]=$tmp;
		$contador++;
	}

	$smarty->assign('operators',$operators_data);
	$smarty->assign('number_operators',count($operators_data));





	$default_loading_location_key=1;
	$default_loading_location_code=_('Unknown');
	$sql=sprintf("select `Location Key` ,`Location Code`    from `Location Dimension` where `Location Mainly Used For`='Loading'  limit 1 ");
	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$default_loading_location_key=$row['Location Key'];
		$default_loading_location_code=$row['Location Code'];
	}

	$smarty->assign('default_loading_location_key',$default_loading_location_key);
	$smarty->assign('default_loading_location_code',$default_loading_location_code);


	$number_cols=5;
	$loading_locations=array();
	$sql=sprintf("select `Location Key`,`Location Code` from `Location Dimension` where `Location Mainly Used For`='Loading'   ");
	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {



		$loading_locations[]=array(
			'key'=>$row['Location Key'],
			'code'=>$row['Location Code'],
			'mod'=>fmod($contador,$number_cols),
			'number_cols'=>$number_cols
		);
		$contador++;
	}




	$smarty->assign('loading_locations',$loading_locations);
	$smarty->assign('number_loading_locations',count($loading_locations));






	/******
	$loading_locations=array();

	$number_cols=5;
	$row=0;
	$contador=0;

	$sql=sprintf("select `Location Key`,`Location Code` from `Location Dimension` where `Location Mainly Used For`='Loading'   ");
	$res = mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		if (fmod($contador,$number_cols)==0 and $contador>0) {
			$row++;
		}

		$_loading_locations=array(
			'key'=>$row['Location Key'],
			'code'=>$row['Location Code'],
		);

		$contador++;

	}
	$smarty->assign('loading_locations',$loading_locations);

*****/


	$js_files[]='supplier_dn_inputted.js.php';


	$template='supplier_dn_inputted.tpl';

	break;
case('Received'):



	$pickers=$corporation->get_current_staff_with_position_code('PICK');
	$number_cols=5;
	$row=0;
	$pickers_data=array();
	$contador=0;
	foreach ($pickers as $picker) {
		if (fmod($contador,$number_cols)==0 and $contador>0)
			$row++;
		$tmp=array();
		foreach ($picker as $key=>$value) {
			$tmp[preg_replace('/\s/','',$key)]=$value;
		}
		$pickers_data[$row][]=$tmp;
		$contador++;
	}

	$smarty->assign('staff',$pickers_data);
	$smarty->assign('number_staff',count($pickers_data));






	$js_files[]='supplier_dn_received.js.php';


	$template='supplier_dn_received.tpl';


	break;

case('Checked'):




	$sql=sprintf("select `Staff Key`id,`Staff Alias` as alias ,`Staff Position Key` as position_id from `Staff Dimension` where `Staff Currently Working`='Yes' order by alias ");
	$res = mysql_query($sql);
	$num_cols=5;
	$staff=array();
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$staff[]=array('alias'=>$row['alias'],'id'=>$row['id'],'position_id'=>$row['position_id']);
	}
	foreach ($staff as $key=>$_staff) {
		$staff[$key]['mod']=fmod($key,$num_cols);
	}
	$smarty->assign('staff',$staff);
	$smarty->assign('staff_cols',$num_cols);
	$css_files[]=$yui_path.'autocomplete/assets/skins/sam/autocomplete.css';




	$js_files[]='supplier_dn_assing_locations.js.php';


	$template='supplier_dn_assing_locations.tpl';



	break;

case('Cancelled'):
	$js_files[]='supplier_dn_cancelled.js.php';


	$template='supplier_dn_cancelled.tpl';
	break;
}

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$session_data=base64_encode(json_encode(array(
			'label'=>array(
				'Code'=>_('Code'),
				'Name'=>_('Name'),
				'Parts'=>_('Parts'),
				'Parts_Info'=>_('Parts Info'),
				'Description'=>_('Supplier Carton Description'),
				'PO_Qty'=>_('Cartons PO'),
				'DN_Qty'=>_('Cartons DN'),
				'Unit'=>_('Unit'),
				'Transport_type'=>_('Transport type'),
				'Page'=>_('Page'),
				'of'=>_('of')
			),
			'state'=>array(
				'supplier_dn'=>$_SESSION['state']['supplier_dn']
			)
		)));
$smarty->assign('session_data',$session_data);

$smarty->display($template);




?>
