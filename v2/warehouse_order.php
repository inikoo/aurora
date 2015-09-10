<?php
include_once 'common.php';
include_once 'class.Warehouse.php';
include_once 'class.DeliveryNote.php';



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

if(in_array($dn->data['Delivery Note State'],array('Picker & Packer Assigned','Picking & Packing','Ready to be Picked','Picker Assigned','Picking','Picked'))){
	header( 'Location: order_pick_aid.php?id='.$dn->id );

}else if(in_array($dn->data['Delivery Note State'],array('Packer Assigned','Packing','Packed','Approved','Dispatched','Packed Done'))){

	header( 'Location: order_pack_aid.php?id='.$dn->id );

}else if(in_array($dn->data['Delivery Note State'],array('Cancelled','Cancelled to Restock'))){
	header( 'Location: order_restock_aid.php?id='.$dn->id );


}else{
	exit("error unknown state: ".$dn->data['Delivery Note State']);

}


?>
