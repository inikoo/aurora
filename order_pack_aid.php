<?php
include_once 'common.php';
include_once 'class.CurrencyExchange.php';

include_once 'class.Order.php';
include_once 'class.PartLocation.php';

if ( !$user->can_view( 'parts' ) ) {
	header( 'Location: index.php' );
	exit;
}

if ( !isset( $_REQUEST['id'] ) or !is_numeric( $_REQUEST['id'] ) ) {
	header( 'Location: warehouse_orders.php?msg=wrong_id' );
	exit;
}


$dn_id=$_REQUEST['id'];

$dn=new DeliveryNote( $dn_id );
if ( !$dn->id ) {
	header( 'Location: warehouse_orders.php?msg=order_not_found' );
	exit;

}


if ( isset( $_REQUEST['order_key'] )  and $_REQUEST['order_key']) {
	$order_key=$_REQUEST['order_key'];
}else {
    $order_key-

	$order_key=false;
}
$smarty->assign('order_key',$order_key);


if ($dn->data['Delivery Note Assigned Packer Alias']=='') {
	header( 'Location: order_pick_aid.php?id='.$dn->id.'&order_key='.$order_key );
	exit;
}

if ( isset( $_REQUEST['refresh'] ) ) {
	$dn->actualize_inventory_transaction_facts();

}

$dn->update_packing_percentage();


if ( isset( $_REQUEST['order_key'] ) ) {
	$order_key=$_REQUEST['order_key'];
}else {
	$order_key=false;
}
$smarty->assign('order_key',$order_key);




$warehouse= new Warehouse( $dn->data['Delivery Note Warehouse Key'] );
$smarty->assign( 'warehouse', $warehouse );



$smarty->assign('search_parent_key',$warehouse->id);
$smarty->assign('search_parent','warehouse');

$smarty->assign('search_scope','orders_warehouse');
$smarty->assign('search_label',_('Deliveries'));



//$dn->start_packing(1);

//print_r($dn);

$number_transactions=$dn->get_number_transactions();
$number_packed_transactions=$dn->get_number_packed_transactions();
$number_picked_transactions=$dn->get_number_picked_transactions();

$smarty->assign( 'packed', ( $number_packed_transactions>=$number_transactions?true:false ) );
$smarty->assign( 'number_transactions', $number_transactions );
$smarty->assign( 'number_packed_transactions', $number_packed_transactions );
$smarty->assign( 'number_picked_transactions', $number_picked_transactions );



$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'css/common.css',
	'css/container.css',
	'css/button.css',
	'css/table.css',
	'css/edit.css',
	'theme.css.php'
);
$js_files=array(
	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'datatable/datatable.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/php.default.min.js',
	'js/common.js',
	'js/search.js',
	'js/table_common.js',
	'js/edit_common.js',
	'js/common_assign_picker_packer.js',
	'js/common_edit_delivery_note.js',
	'js/order_pack_aid.js',
);




$template='order_pack_aid.tpl';

$customer=new Customer( $dn->data['Delivery Note Customer Key'] );

$smarty->assign( 'delivery_note', $dn );
$smarty->assign( 'customer', $customer );



$smarty->assign( 'parent', 'orders' );
$smarty->assign( 'title', _( 'Packing Aid Sheet' ).' '.$dn->get( 'Delivery Note ID' ) );
$smarty->assign( 'css_files', $css_files );
$smarty->assign( 'js_files', $js_files );




$tipo_filter=$_SESSION['state']['packing_aid']['items']['f_field'];
$smarty->assign( 'filter0', $tipo_filter );
$smarty->assign( 'filter_value0', $_SESSION['state']['packing_aid']['items']['f_value'] );
$filter_menu=array(
	'sku'=>array( 'db_key'=>'SKU', 'menu_label'=>_( 'SKU' ), 'label'=>_( 'SKU' ) ),
	'reference'=>array( 'db_key'=>'Reference', 'menu_label'=>_( 'Reference' ), 'label'=>_( 'Reference' ) ),

);
$smarty->assign( 'filter_menu0', $filter_menu );
$smarty->assign( 'filter_name0', $filter_menu[$tipo_filter]['label'] );
$paginator_menu=array( 10, 25, 50, 100, 500 );
$smarty->assign( 'paginator_menu0', $paginator_menu );



$tipo_filter2='alias';
$filter_menu2=array(
	'alias'=>array('db_key'=>'alias','menu_label'=>_('Alias'),'label'=>_('Alias')),
	'name'=>array('db_key'=>'name','menu_label'=>_('Name'),'label'=>_('Name')),
);
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2','');
$paginator_menu=array( 10, 25, 50, 100, 500 );
$smarty->assign( 'paginator_menu2', $paginator_menu );



$parcels=$dn->get_formated_parcels();
$weight=$dn->data['Delivery Note Weight'];
$consignment=$dn->data['Delivery Note Shipper Consignment'];

//print "_>$parcels<_";

$smarty->assign( 'parcels', $parcels);
$smarty->assign( 'weight', ($weight?$dn->get('Weight'):'') );
$smarty->assign( 'consignment', ($consignment?$dn->get('Consignment'):'') );


$shipper_data=array();

$sql=sprintf("select `Shipper Key`,`Shipper Code`,`Shipper Name` from `Shipper Dimension` where `Shipper Active`='Yes' order by `Shipper Name` ");
$result=mysql_query($sql);
while ($row=mysql_fetch_assoc($result)) {
	$shipper_data[$row['Shipper Key']]=array(
		'shipper_key'=>$row['Shipper Key'],
		'code'=>$row['Shipper Code'],
		'name'=>$row['Shipper Name'],
		'selected'=>($dn->data['Delivery Note Shipper Code']==$row['Shipper Code']?1:0)
	);


}
$smarty->assign( 'shipper_data', $shipper_data );

$session_data=base64_encode(json_encode(array(
			'label'=>array(
				'Price'=>_('Price'),
				'Reference'=>_('Reference'),
				'Description'=>_('Description'),
				'Packed'=>_('Packed'),
				'Picked'=>_('Picked'),
				'Notes'=>_('Notes'),

				'Page'=>_('Page'),
				'of'=>_('of')
			),
			'state'=>array(
				'packing_aid'=>$_SESSION['state']['packing_aid']
			)
		)));
$smarty->assign('session_data',$session_data);


$smarty->display( $template );
?>
