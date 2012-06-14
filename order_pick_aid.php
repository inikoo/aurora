<?php
include_once 'common.php';
include_once 'class.CurrencyExchange.php';
include_once 'class.Department.php';
include_once 'class.PartLocation.php';

include_once 'class.Order.php';
include_once 'class.PartLocation.php';
include_once('class.CompanyArea.php');

if ( !$user->can_view( 'parts' ) ) {
	header( 'Location: index.php' );
	exit;
}

if ( !isset( $_REQUEST['id'] ) or !is_numeric( $_REQUEST['id'] ) ) {
	header( 'Location: warehouse_orders.php?msg=wrong_id' );
	exit;
}


$dn_id=$_REQUEST['id'];
$_SESSION['state']['dn']['id']=$dn_id;
$dn=new DeliveryNote( $dn_id );
if ( !$dn->id ) {
	header( 'Location: warehouse_orders.php?msg=order_not_found' );
	exit;

}



if ( isset( $_REQUEST['refresh'] ) ) {
	$dn->actualize_inventory_transaction_facts();
}

$dn->update_picking_percentage();



$warehouse= new Warehouse( $dn->data['Delivery Note Warehouse Key'] );
$smarty->assign( 'warehouse', $warehouse );


$smarty->assign( 'search_label', _( 'Parts' ) );
$smarty->assign( 'search_scope', 'parts' );





//$dn->start_packing(1);

//print_r($dn);

$number_transactions=$dn->get_number_transactions();
$number_picked_transactions=$dn->get_number_picked_transactions();
$smarty->assign( 'picked', ( $number_picked_transactions>=$number_transactions?true:false ) );
$smarty->assign( 'number_transactions', $number_transactions );
$smarty->assign( 'number_picked_transactions', $number_picked_transactions );



$css_files=array(
	$yui_path.'reset-fonts-grids/reset-fonts-grids.css',
	$yui_path.'menu/assets/skins/sam/menu.css',
	$yui_path.'assets/skins/sam/autocomplete.css',
	$yui_path.'calendar/assets/skins/sam/calendar.css',
	'common.css',
	'css/container.css',
	'button.css',
	'table.css',
	'css/edit.css',
	'theme.css.php'
);
$js_files=array(

	$yui_path.'utilities/utilities.js',
	$yui_path.'json/json-min.js',
	$yui_path.'paginator/paginator-min.js',
	$yui_path.'datasource/datasource-min.js',
	$yui_path.'autocomplete/autocomplete-min.js',
	$yui_path.'datatable/datatable-min.js',
	$yui_path.'container/container-min.js',
	$yui_path.'menu/menu-min.js',
	$yui_path.'calendar/calendar-min.js',
	'js/common.js',
	'js/search.js',
	'js/table_common.js',
	'js/edit_common.js',
	'order_pick_aid.js.php?dn_key='.$dn->id,
	'edit_stock.js.php'
);

$warehouse_area=new CompanyArea('code','WAH');
$packers=$warehouse_area->get_current_staff_with_position_code('PACK');
$number_cols=5;
$row=0;
$packers_data=array();
$contador=0;
foreach($packers as $packer) {
    if (fmod($contador,$number_cols)==0 and $contador>0)
        $row++;
    $tmp=array();
    foreach($packer as $key=>$value) {
        $tmp[preg_replace('/\s/','',$key)]=$value;
    }
    $packers_data[$row][]=$tmp;
    $contador++;
}

$smarty->assign('packers',$packers_data);


$template='order_pick_aid.tpl';

$customer=new Customer( $dn->data['Delivery Note Customer Key'] );

$smarty->assign( 'delivery_note', $dn );
$smarty->assign( 'customer', $customer );



$smarty->assign( 'parent', 'orders' );
$smarty->assign( 'title', _( 'Picking Aid Sheet' ).' '.strip_tags($dn->get( 'Delivery Note Title' )) );
$smarty->assign( 'css_files', $css_files );
$smarty->assign( 'js_files', $js_files );




$tipo_filter=$_SESSION['state']['picking_aid']['items']['f_field'];
$smarty->assign( 'filter0', $tipo_filter );
$smarty->assign( 'filter_value0', $_SESSION['state']['picking_aid']['items']['f_value'] );
$filter_menu=array(
	'sku'=>array( 'db_key'=>'SKU', 'menu_label'=>_( 'SKU' ), 'label'=>_( 'SKU' ) ),
);
$smarty->assign( 'filter_menu0', $filter_menu );
$smarty->assign( 'filter_name0', $filter_menu[$tipo_filter]['label'] );
$paginator_menu=array( 10, 25, 50, 100, 500 );
$smarty->assign( 'paginator_menu0', $paginator_menu );

$modify_stock=$user->can_edit('product stock');
$smarty->assign('modify_stock',$modify_stock);


$smarty->display( $template );
?>
