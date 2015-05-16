<?php

include_once 'common.php';
include_once 'class.Warehouse.php';
include_once 'class.Supplier.php';
include_once 'class.PurchaseOrder.php';
include_once 'class.CompanyArea.php';




if (isset($_REQUEST['id'])) {



	$po=new PurchaseOrder($_REQUEST['id']);
	if (!$po->id)
		exit("Error po can no be found");
	$supplier=new Supplier('id',$po->data['Purchase Order Supplier Key']);
} elseif (isset($_REQUEST['new'])
	and isset($_REQUEST['supplier_id'])
	and is_numeric($_REQUEST['supplier_id'])
	and $_REQUEST['supplier_id']>0
) {

include_once 'class.Staff.php';

	$warehouse=new Warehouse(1);

	$supplier=new Supplier('id',$_REQUEST['supplier_id']);
	$editor=array(
		'Author Name'=>$user->data['User Alias'],
		'Author Type'=>$user->data['User Type'],
		'Author Key'=>$user->data['User Parent Key'],
		'User Key'=>$user->id
	);
	

	$staff=new Staff($user->data['User Parent Key']);

	$data=array(
		'Purchase Order Supplier Key'=>$supplier->id,
		'Purchase Order Supplier Name'=>$supplier->data['Supplier Name'],
		'Purchase Order Supplier Code'=>$supplier->data['Supplier Code'],
		'Purchase Order Supplier Contact Name'=>$supplier->data['Supplier Main Contact Name'],
		'Purchase Order Supplier Email'=>$supplier->data['Supplier Main Plain Email'],
		'Purchase Order Supplier Telephone'=>$supplier->data['Supplier Main XHTML Telephone'],
		'Purchase Order Supplier Address'=>$supplier->data['Supplier Main XHTML Address'],

		'Purchase Order Currency Code'=>$supplier->data['Supplier Default Currency'],

		'Purchase Order Incoterm'=>$supplier->data['Supplier Default Incoterm'],
		'Purchase Order Port of Import'=>$supplier->data['Supplier Default Port of Import'],
		'Purchase Order Port of Export'=>$supplier->data['Supplier Default Port of Export'],


		'Purchase Order Warehouse Key'=>$warehouse->data['Warehouse Key'],
		'Purchase Order Warehouse Code'=>$warehouse->data['Warehouse Code'],
		'Purchase Order Warehouse Name'=>$warehouse->data['Warehouse Name'],
		'Purchase Order Warehouse Address'=>$warehouse->data['Warehouse Address'],
		'Purchase Order Warehouse Company Name'=>$warehouse->data['Warehouse Company Name'],
		'Purchase Order Warehouse Company Number'=>$warehouse->data['Warehouse Company Number'],
		'Purchase Order Warehouse VAT Number'=>$warehouse->data['Warehouse VAT Number'],
		'Purchase Order Warehouse Telephone'=>$warehouse->data['Warehouse Telephone'],
		'Purchase Order Warehouse Email'=>$warehouse->data['Warehouse Email'],

		'Purchase Order Terms and Conditions'=>$supplier->data['Supplier Default PO Terms and Conditions'],
		'Purchase Order Main Buyer Key'=>$staff->id,
		'Purchase Order Main Buyer Name'=>$staff->data['Staff Name'],
		'editor'=>$editor
	);





	if ($supplier->data['Supplier Show Warehouse TC in PO']=='Yes') {

		if ($data['Purchase Order Terms and Conditions']!='')$data['Purchase Order Terms and Conditions'].='<br><br>';
		$data['Purchase Order Terms and Conditions'].=$warehouse->data['Warehouse Default PO Terms and Conditions'];
	}





	$po=new PurchaseOrder('new',$data);
	if ($po->error)
		exit('error');



	$_SESSION['state']['porder']['products']['display']='ordered_products';

	header('Location: porder.php?id='.$po->id);
	exit;


} else {
	exit("error");

}



$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/part_locations.css',
	'css/edit.css',
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
	'js/search.js',
	'js/common.js',
	'js/fz.shadow.js',
	'js/fz.js',
	'js/imgpop.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/notes.js',
	'js/porder.js',
);


$po_id = $po->id;

$smarty->assign('po',$po);
$smarty->assign('supplier',$supplier);
$smarty->assign('supplier_id',$supplier->id);
$smarty->assign('supplier_key',$supplier->id);
$smarty->assign('corporate_currency',$corporate_currency);

$smarty->assign('search_label',_('Search'));
$smarty->assign('search_scope','supplier_products');



$smarty->assign('title',_('Purchase Order').': '.$po->data['Purchase Order Public ID']);
$smarty->assign('view',$_SESSION['state']['porder']['view']);

$tipo_filter=$_SESSION['state']['porder']['products']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['porder']['products']['f_value']);
$filter_menu=array(
	'p.code'=>array('db_key'=>'p.code','menu_label'=>_('Our Product Code'),'label'=>_('Code')),
	'code'=>array('db_key'=>'code','menu_label'=>_('Supplier Product Code'),'label'=>_('Supplier Code')),
);

$smarty->assign('currency',$myconf['currency_symbol']);
$smarty->assign('decimal_point',$myconf['decimal_point']);
$smarty->assign('thousand_sep',$myconf['thousand_sep']);

$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('parent','suppliers');

$smarty->assign('date',date("Y-m-d"));
$smarty->assign('time',date("H:i"));

$elements_number=array('Notes'=>0,'Changes'=>0,'Attachments'=>0);
$sql=sprintf("select count(*) as num , `Type` from  `Purchase Order History Bridge` where `Purchase Order Key`=%d group by `Type`",$po->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$elements_number[$row['Type']]=$row['num'];
}
$smarty->assign('elements_po_history_number',$elements_number);
$smarty->assign('elements_po_history',$_SESSION['state']['porder']['history']['elements']);


$smarty->assign('number_attachments',$po->get_number_attachments_formated());



$filter_menu=array(
	'notes'=>array('db_key'=>'notes','menu_label'=>_('Records with  notes *<i>x</i>*'),'label'=>_('Notes')),
	//   'author'=>array('db_key'=>'author','menu_label'=>'Done by <i>x</i>*','label'=>_('Done by')),
	'upto'=>array('db_key'=>'upto','menu_label'=>_('Records up to <i>n</i> days'),'label'=>_('Up to (days)')),
	'older'=>array('db_key'=>'older','menu_label'=>_('Records older than  <i>n</i> days'),'label'=>_('Older than (days)'))
);
$tipo_filter=$_SESSION['state']['porder']['history']['f_field'];
$filter_value=$_SESSION['state']['porder']['history']['f_value'];

$smarty->assign('filter_value3',$filter_value);
$smarty->assign('filter_menu3',$filter_menu);
$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);



$sdns_data=array();
foreach ($po->get_sdn_objects() as $sdn) {
	$current_sdn_key=$sdn->id;

	$sdns_data[]=array(
		'key'=>$sdn->id,
		'number'=>$sdn->data['Supplier Delivery Note Public ID'],
		'state'=>$sdn->data['Supplier Delivery Note Current State'],

	);

}
$number_dsns=count($sdns_data);
if ($number_dsns!=1) {
	$current_sdn_key='';
}
$smarty->assign('current_sdn_key',$current_sdn_key);
$smarty->assign('number_dsns',$number_dsns);
$smarty->assign('sdns_data',$sdns_data);




if ($po->data['Purchase Order State']=='In Process') {



	if ($po->data['Purchase Order Number Items']==0) {
		$products_display_type='all_products';
	}else {
		$products_display_type='ordered_products';
	}

	$_SESSION['state']['porder']['products']['display']=$products_display_type;
	$smarty->assign('products_display_type',$products_display_type);










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

	$smarty->assign('user_alias',$user->data['User Alias']);
	$smarty->assign('user_staff_key',$user->data['User Parent Key']);




	$css_files[]='css/porder_in_process.css';

	$js_files[]='js/porder_in_process.js';



	$company_area=new CompanyArea('code','WAH');

	$buyers=$company_area->get_current_staff_with_position_code('BUY');
	$number_cols=5;
	$row=0;
	$buyers_data=array();
	$contador=0;
	foreach ($buyers as $buyer) {
		if (fmod($contador,$number_cols)==0 and $contador>0)
			$row++;
		$tmp=array();
		foreach ($buyer as $key=>$value) {
			$tmp[preg_replace('/\s/','',$key)]=$value;
		}
		$buyers_data[$row][]=$tmp;
		$contador++;
	}

	$smarty->assign('buyers',$buyers_data);
	$smarty->assign('number_buyers',count($buyers_data));


	$tipo_filter2='alias';
	$filter_menu2=array(
		'alias'=>array('db_key'=>'alias','menu_label'=>_('Alias'),'label'=>_('Alias')),
		'name'=>array('db_key'=>'name','menu_label'=>_('Name'),'label'=>_('Name')),
	);
	$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
	$smarty->assign('filter_menu2',$filter_menu2);
	$smarty->assign('filter2',$tipo_filter2);
	$smarty->assign('filter_value2','');



	$tipo_filter6='code';
	$filter_menu6=array(
		'code'=>array('db_key'=>'code','menu_label'=>_('Incoterm Code'),'label'=>_('Code')),
		'name'=>array('db_key'=>'name','menu_label'=>_('Incoterm Name'),'label'=>_('Name')),
	);
	$smarty->assign('filter_name6',$filter_menu6[$tipo_filter6]['label']);
	$smarty->assign('filter_menu6',$filter_menu6);
	$smarty->assign('filter5',$tipo_filter6);
	$smarty->assign('filter_value6','');
	$paginator_menu=array(10,25,50,100,500);
	$smarty->assign('paginator_menu6',$paginator_menu);
	$template='porder_in_process.tpl';


}
elseif ($po->data['Purchase Order State']=='Submitted' or $po->data['Purchase Order State']=='Confirmed') {


	$_SESSION['state']['porder']['show_all']=false;

	$js_files[]='js/porder_submitted.js';
	$css_files[]='css/porder_submitted.css';



	$_SESSION['state']['porder']['products']['display']='ordered_products';
	$smarty->assign('products_display_type',$_SESSION['state']['porder']['products']['display']);



	$template='porder_submitted.tpl';
}elseif ($po->data['Purchase Order State']=='In Warehouse' ) {


	$_SESSION['state']['porder']['show_all']=false;

	$js_files[]='js/porder_in_warehouse.js';
	$css_files[]='css/porder_in_warehouse.css';



	$_SESSION['state']['porder']['products']['display']='ordered_products';
	$smarty->assign('products_display_type',$_SESSION['state']['porder']['products']['display']);



	$template='porder_in_warehouse.tpl';
}
elseif ($po->data['Purchase Order State']=='Cancelled') {

	$js_files[]='porder_cancelled.js.php';


	$template='porder_cancelled.tpl';

}


$session_data=base64_encode(json_encode(array(
			'label'=>array(
				'Code'=>_('Code'),
				'Name'=>_('Name'),
				'Reference'=>_('Parts'),
				'Parts_Info'=>_('Parts Info'),
				'Description'=>_('Supplier Carton Description'),
				'SDN'=>_('Delivery (SDN)'),
				'Qty'=>_('Cartons'),
				'PO_Qty'=>_('PO Qty'),
				'SDN_Qty'=>_('SDN Qty'),
				'Qty_Received'=>_('Received'),
				'Qty_Damaged'=>_('Damaged'),
				'Qty_to_Stock'=>_('to Stock'),
				'Net_Cost'=>_('Net Cost'),
				'Unit'=>_('Unit'),
				'Transport_type'=>_('Transport type'),
				'Page'=>_('Page'),
				'of'=>_('of'),
				'SDN_number_required'=>_('Delivery note number required')
			),
			'state'=>array(
				'porder'=>$_SESSION['state']['porder']
			)
		)));
$smarty->assign('session_data',$session_data);


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->display($template);

?>
