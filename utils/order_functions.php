<?php
/*
 About:
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 18 January 2014 14:09:57 GMT, Sheffield UK
 Copyright (c) 2009, Inikoo

 Version 2.0
*/


function get_orders_operations($row,$user) {
	$operations='<div id="operations'.$row['Order Key'].'">';
	$class='right';


	if ($row['Order Current Dispatch State']=='Waiting for Payment Confirmation') {

		$operations.='<div class="buttons small '.$class.'">';
		$operations.=' <button class="negative" onClick="cancel_payment(this,'.$row['Order Key'].')">'._('Cancel Payment')."</button>";
		$operations.=' <button  class="positive"  onClick="conform_payment(this,'.$row['Order Key'].')">'._('Confirm Payment')."</button>";

		$operations.='</div>';

	}else if ($row['Order Current Dispatch State']=='In Process by Customer') {

			$operations.='<div class="buttons small '.$class.'">';
			$operations.=sprintf("<button onClick=\"open_cancel_dialog_from_list(this,%d,'%s, %s')\"><img style='height:12px;width:12px' src='art/icons/cross.png'> %s</button>",
				$row['Order Key'],
				$row['Order Public ID'],
				$row['Order Customer Name'],
				_('Delete')
			);
			$operations.=' <button   onClick="location.href=\`order.php?id='.$row['Order Key'].'&modify=1\`">'._('Modify Order in Basket')."</button>";

			$operations.='</div>';

		}elseif ($row['Order Current Dispatch State']=='Submitted by Customer') {
		$operations.='<div class="buttons small '.$class.'">';
		$operations.=sprintf("<button id=\"send_to_warehouse_button_%d\" class=\"%s\" onClick=\"create_delivery_note_from_list(this,%d)\"><img id=\"send_to_warehouse_img_%d\" style='height:12px;width:12px' src='art/icons/cart_go.png'> %s</button>",
			$row['Order Key'],
			($row['Order Number Items']==0?'disabled':''),
			$row['Order Key'],
			$row['Order Key'],
			_('Send Warehouse'));

		//$operations.=sprintf("<button onClick=\"location.href='order.php?id=%d&referral=store_pending_orders'\"><img style='height:12px;width:12px' src='art/icons/cart_edit.png'> %s</button>",$row['Order Key'],_('Edit Order'));
		$operations.=sprintf("<button onClick=\"open_cancel_dialog_from_list(this,%d,'%s, %s')\"><img style='height:12px;width:12px' src='art/icons/cross.png'> %s</button>",
			$row['Order Key'],
			$row['Order Public ID'],
			$row['Order Customer Name'],
			_('Cancel')
		);
		$operations.='</div>';

	}
	else if ($row['Order Current Dispatch State']=='In Process') {
			$operations.='<div class="buttons small '.$class.'">';
			$operations.=sprintf("<button id=\"send_to_warehouse_button_%d\" class=\"%s\" onClick=\"create_delivery_note_from_list(this,%d)\"><img id=\"send_to_warehouse_img_%d\" style='height:12px;width:12px' src='art/icons/cart_go.png'> %s</button>",
				$row['Order Key'],
				($row['Order Number Items']==0?'disabled':''),
				$row['Order Key'],
				$row['Order Key'],
				_('Send Warehouse'));

			//$operations.=sprintf("<button onClick=\"location.href='order.php?id=%d&referral=store_pending_orders'\"><img style='height:12px;width:12px' src='art/icons/cart_edit.png'> %s</button>",$row['Order Key'],_('Edit Order'));
			$operations.=sprintf("<button onClick=\"open_cancel_dialog_from_list(this,%d,'%s, %s')\"><img style='height:12px;width:12px' src='art/icons/cross.png'> %s</button>",
				$row['Order Key'],
				$row['Order Public ID'],
				$row['Order Customer Name'],
				_('Cancel')
			);
			$operations.='</div>';

		}
	elseif (in_array($row['Order Current Dispatch State'],array('Ready to Pick','Picking','Picked','Packing','Packed','Picking & Packing'))  ) {

		$operations.='<div class="buttons small '.$class.'">';
		$operations.=sprintf("<button onClick=\"open_cancel_dialog_from_list(this,%d,'%s, %s')\"><img style='height:12px;width:12px' src='art/icons/cross.png'> %s</button>",
			$row['Order Key'],
			$row['Order Public ID'],
			$row['Order Customer Name'],
			_('Cancel')
		);
		$operations.=sprintf("<button onClick=\"location.href='order.php?id=%d&referral=store_pending_orders&amend=1'\"><img style='height:12px;width:12px' src='art/icons/cart_edit.png'> %s</button>",$row['Order Key'],_('Amend Order'));

		$operations.='</div>';

	}
	elseif ($row['Order Current Dispatch State']=='Packed Done') {

		$operations.='<div class="buttons small '.$class.'">';
		if ($row['Order Invoiced']=='No') {
			$operations.='<button  onClick="create_invoice(this,'.$row['Order Key'].')"><img id="create_invoice_img_'.$row['Order Key'].'" style="height:12px;width:12px" src="/art/icons/money.png"> '._('Create Invoice')."</button>";;
		}else {
			$operations.='<button  onClick="approve_dispatching(this,'.$row['Order Key'].')"><img id="approve_dispatching_img_'.$row['Order Key'].'" style="height:12px;width:12px" src="/art/icons/package_green.png"> '._('Approve Dispatching')."</button>";;


		}
		$operations.='</div>';

	}elseif ($row['Order Current Dispatch State']=='Ready to Ship') {
		$operations.='<div class="buttons small '.$class.'">';
		$order=new Order($row['Order Key']);
		$dns=$order->get_delivery_notes_objects();
		if (count($dns)==1) {
			foreach ($dns as $dn) {

				$operations.='<button  onClick="set_as_dispatched('.$dn->data['Delivery Note Key'].','.$user->get_staff_key().',\'order\',\''.$row['Order Key'].'\')" ><img id="set_as_dispatched_img_'.$dn->data['Delivery Note Key'].'" src="/art/icons/lorry_go.png" alt=""> '._('Mark as Dispatched')."</button>";
			}
		}

		$operations.='</div>';

	}else {
		$operations.='';

		$public_id=sprintf("<a href='dn.php?id=%d'>%s</a>",$row['Order Key'],$row['Order Public ID']);
		$public_id=$row['Order Public ID'];
		$public_id=sprintf("<a href='order_pick_aid.php?id=%d'> %s</a>",$row['Order Key'],$row['Order Public ID']);
	}
	$operations.='</div>';

	return $operations;

}


function get_order_formatted_dispatch_state($state,$order_key) {
	switch ($state) {
	case 'In Process by Customer':
		$dispatch_state=_('In Website');
		break;
	case 'Submitted by Customer':
		$dispatch_state= _('Submitted');
		break;

	case 'Packed Done':
		return _('Packed & Checked');
		break;
	default:
		$dispatch_state=$state;
	}
	return '<span id="dispatch_state_'.$order_key.'">'.$dispatch_state.'</span>';

}

function get_order_formatted_payment_state($data) {




	switch ($data['Order Current Payment State']) {
	case 'No Applicable':
		///$payment_state='<span style="opacity:.6">'._('No Applicable').'</span>';
		$payment_state='';
		break;
	case 'Waiting Payment':

		if ($data['Order Current Dispatch State']=='In Process by Customer' or $data['Order Current Dispatch State']=='Cancelled' ) {
			$payment_state='';
		}else {

			$payment_state=_('Waiting Payment');
			//Credit Card','Cash','Paypal','Check','Bank Transfer','Cash on Delivery','Other','Unknown','Account
			switch ($data['Order Payment Method']) {
			case 'Bank Transfer':
				$payment_method=_('Bank Transfer');
				break;
			case 'Cash on Delivery':
				$payment_method=_('Cash on Delivery');
				break;
			case 'Other':
				$payment_method=_('Other');
				break;
			case 'Credit Card':
				$payment_method=_('Credit Card');
				break;
			case 'Unknown':
				$payment_method='';
				break;	
			default:
				$payment_method=$data['Order Payment Method'];
			}

            
			$payment_state=_('Waiting Payment');
            if($payment_method!=''){
                $payment_state.=' ('.$payment_method.')';
            }


		}
		break;
	case 'Overpaid':
		$payment_state=_('Overpaid');

		break;
	case 'Unknown':
		$payment_state='';

		break;
	case 'Paid':
		$payment_state=_('Paid');
		break;
	case 'Partially Paid':

		if ($data['Order Current Dispatch State']=='In Process by Customer') {
			$payment_state=_('Using Credit');
		}else {

			$payment_state=_('Partially Paid');
		}
		break;
	default:
		$payment_state=$data['Order Current Payment State'];
	}
	if($payment_state!=''){
	    $payment_state='<span id="payment_state_'.$data['Order Key'].'">'.$payment_state.'</span>';
	}
	
	return $payment_state;
}

function get_invoice_operations($row,$user,$parent='order',$parent_key='') {
	$operations='<div  id="operations'.$row['Invoice Key'].'">';


	$operations.='
		<button id="delete'.$row['Invoice Key'].'" onclick="show_delete_invoice_dialog('.$row['Invoice Key'].')"><img  src="/art/icons/delete.png"> '._('Delete').'</button>

	';

	$operations.='</div>';

}

function get_dn_operations($row,$user,$parent='order',$parent_key='') {

	if ($parent=='order')
		$class='right';

	else
		$class='left';



	//$operations='<div  id="operations'.$row['Delivery Note Key'].'">';
	$operations='<div  id="operations'.$row['Delivery Note Key'].'">';


	if ($row['Delivery Note State']=='Ready to be Picked') {

		$operations.='<div class="buttons small '.$class.'">';
		if ($parent=='order') {

			$operations.='<button style="display:none"  class="first" onClick="show_dialog_process_delivery_note(this,'.$row['Delivery Note Key'].')"><img style="height:12px;width:12px" src="/art/icons/lorry_go.png"> '._('Process Delivery Note')."</button>";

		}else {



			if ($user->can_edit('assign_pp')) {
				$operations.='<button  class="first" onClick="assign_picker(this,'.$row['Delivery Note Key'].')"><img style="height:12px;width:12px" src="/art/icons/user.png"> '._('Assign Picker')."</button>";
			}

			if ($user->data['User Type']=='Warehouse') {
				$operations.=' <button  onClick="pick_it(this,'.$row['Delivery Note Key'].')">'._('Pick Order')."</button>";
			}elseif ($user->can_edit('pick')) {
				$operations.=' <button  onClick="pick_it_fast(this,'.$user->get_staff_key().','.$row['Delivery Note Key'].')"><img id="pick_it_fast_img_'.$row['Delivery Note Key'].'" style="height:12px;width:12px" src="/art/icons/paste_plain.png"> '._('Start Picking')."</button>";

			}
		}
		$operations.='</div>';

	}
	elseif ($row['Delivery Note State']=='Picker Assigned') {
		$operations.='<div class="buttons small '.$class.'">';
		if ($user->can_edit('assign_pp')) {
			$operations.=' <button  style="cursor:pointer"  onClick="assign_picker(this,'.$row['Delivery Note Key'].')"><img src="/art/icons/user_edit.png"/> '._('Change Picker').'</button>';
		}
		$operations.='</span>';
		if ($row['Delivery Note Assigned Picker Key']==$user->get_staff_key())
			$operations.='<button onClick="start_picking('.$row['Delivery Note Key'].','.$row['Delivery Note Assigned Picker Key'].')"  href="order_pick_aid.php?id='.$row['Delivery Note Key'].'"  ><img id="start_picking_img_'.$row['Delivery Note Key'].'" style="height:12px;width:12px" src="/art/icons/paste_plain.png"> '._('Start Picking')."</button>";

		if ($user->data['User Type']=='Warehouse') {
			$operations.=' <button  onClick="pick_it(this,'.$row['Delivery Note Key'].')">'._('Pick Order')."</button>";
		}elseif ($parent!='order') {
			$operations.='<button  onClick="location.href=\'order_pick_aid.php?id='.$row['Delivery Note Key'].'\'"  ><img style="height:12px;width:12px" src="/art/icons/paste_plain.png"> '._('Picking Aid')."</button>";
		}


		$operations.='</div>';
	}
	elseif ($row['Delivery Note State']=='Packer Assigned') {

		$operations.='<div class="buttons small '.$class.'">';


		$operations.='<span style="float:left;;margin-left:7px"><img style="height:12px;width:12px" src="/art/icons/user_red.png" title="'._('Packing assigned to').'"/> <span style="font-weight:bold">'.$row['Delivery Note Assigned Packer Alias'].'</span>';
		if ($user->can_edit('assign_pp')) {
			$operations.=' <img src="/art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_packer(this,'.$row['Delivery Note Key'].')">';
		}



		$operations.='</span>';
		if ($row['Delivery Note Assigned Packer Key']==$user->get_staff_key())
			$operations.='<button  onClick="start_packing('.$row['Delivery Note Key'].','.$row['Delivery Note Assigned Packer Key'].')"  ><img id="start_packing_img_'.$row['Delivery Note Key'].'" style="height:12px;width:12px" src="/art/icons/briefcase.png"> '._('Start Packing')."</button>";


		if ($user->data['User Type']!='Warehouse'  and $parent!='order' ) {

			$operations.='<button  style="margin-left:5px" onClick="location.href=\'order_pack_aid.php?id='.$row['Delivery Note Key'].'\'"  ><img style="height:12px;width:12px" src="/art/icons/paste_plain.png"> '._('Packing Aid')."</button>";
		}
		$operations.='</div>';

		// $operations.='<b>'.$row['Delivery Note Assigned Packer Alias'].'</b>   <a  href="order_pack_aid.php?id='.$row['Delivery Note Key'].'"  > '._('pack order')."</a>";
		//  $operations.=' <img src="/art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_packer(this,'.$row['Delivery Note Key'].')">';
	}
	elseif ($row['Delivery Note State']=='Picking') {

		$operations.='<div class="buttons small '.$class.'">';

		if ($row['Delivery Note Assigned Picker Key']==$user->get_staff_key()  and $parent!='order'  ) {
			$operations.='<button  onClick="location.href=\'order_pick_aid.php?id='.$row['Delivery Note Key'].'\'"  ><img style="height:12px;width:12px" src="/art/icons/paste_plain.png"> '._('Picking Aid')."</button>";
		}
		if ($user->can_edit('assign_pp')) {
			$operations.=' <button  style="cursor:pointer"  onClick="assign_picker(this,'.$row['Delivery Note Key'].')"><img src="/art/icons/user_edit.png"/> '._('Change Picker').'</button>';
		}

		// $operations.='</span>';

		if ($user->can_edit('assign_pp') and $row['Delivery Note Assigned Packer Key']==0) {
			$operations.='<button  class="first" onClick="assign_packer(this,'.$row['Delivery Note Key'].')"><img style="height:12px;width:12px" src="/art/icons/user_red.png"> '._('Assign Packer')."</button>";

		}

		$operations.='</div>';




		//$operations.=' | <b>'.$row['Delivery Note Assigned Picker Alias'].'</b>   <a  href="order_pick_aid.php?id='.$row['Delivery Note Key'].'"  > '._('picking order')."</a>";
		//$operations.=' <img src="/art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_picker(this,'.$row['Delivery Note Key'].')">';

	}
	elseif ($row['Delivery Note State']=='Picked') {
		$operations.='<div class="buttons small '.$class.'">';
		if ($user->can_edit('assign_pp')) {
			$operations.='<button  class="first" onClick="assign_packer(this,'.$row['Delivery Note Key'].')"><img style="height:12px;width:12px" src="/art/icons/user_red.png"> '._('Assign Packer')."</button>";

		}

		if ($user->can_edit('pack')) {
			$operations.='<button   onClick="pack_it_fast(this,'.$user->get_staff_key().','.$row['Delivery Note Key'].')"><img id="pack_it_fast_img_'.$row['Delivery Note Key'].'"  style="height:12px;width:12px" src="/art/icons/briefcase.png"> '._('Start packing')."</button>";


		}
		$operations.='</div>';
	}
	elseif ($row['Delivery Note State']=='Packing') {

		$operations.='<div class="buttons small '.$class.'">';
		$operations.='<span style="float:left;margin-left:7px"> <img style="height:12px;width:12px" src="/art/icons/user_red.png" title="'._('Packing by').'"/>  <span style="font-weight:bold">'.$row['Delivery Note Assigned Packer Alias'].'</span>';
		if ($user->can_edit('assign_pp')) {
			$operations.=' <img src="/art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_packer(this,'.$row['Delivery Note Key'].')">';
		}
		$operations.='</span>';
		if ($row['Delivery Note Assigned Packer Key']==$user->get_staff_key() and $parent!='order' ) {
			$operations.='<a   href="order_pack_aid.php?id='.$row['Delivery Note Key'].'"  ><img style="height:12px;width:12px" src="/art/icons/briefcase.png"> '._('Packing Aid')."</a>";
		}
		$operations.='</div>';

	}
	elseif ($row['Delivery Note State']=='Packed') {

		$operations.='<div class="buttons small '.$class.'">';
		if ($user->can_edit('assign_pp')) {
			$operations.='<button   onClick="approve_packing('.$row['Delivery Note Key'].','.$user->get_staff_key().',\'warehouse_orders\')"><img id="approve_packing_img_'.$row['Delivery Note Key'].'"  style="height:12px;width:12px" src="/art/icons/flag_green.png"> '._('Approve packing')."</button>";
		}else {
			$operations.='<span style="margin-left:7px">'._('Waiting for packing approval').'</span>';

		}

		$operations.='</div>';



	}
	elseif ($row['Delivery Note State']=='Picking & Packing') {
		if ($user->data['User Type']!='Warehouse'  and $parent!='order' ) {
			$operations.='<b>'.$row['Delivery Note Assigned Picker Alias'].'</b>   <a  href="order_pick_aid.php?id='.$row['Delivery Note Key'].'"  > '._('picking order')."</a>";
			$operations.=' <img src="/art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_picker(this,'.$row['Delivery Note Key'].')">';

			$operations.=' | <b>'.$row['Delivery Note Assigned Packer Alias'].'</b>   <a  href="order_pack_aid.php?id='.$row['Delivery Note Key'].'"  > '._('packing order')."</a>";
			$operations.=' <img src="/art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_packer(this,'.$row['Delivery Note Key'].')">';
		}

	}
	elseif ($row['Delivery Note State']=='Packed Done') {

		$operations.='<div class="buttons small '.$class.'">';



		if ($user->can_edit('orders')) {

		
			$operations.=' <button '.($row['Delivery Note Invoiced']=='No' and  $row['Delivery Note Type']=='Order' ?' class="disabled" title="'._('Order not invoiced').'"':'') .' onclick="approve_dispatching('.$row['Delivery Note Key'].','.$user->get_staff_key().',\''.$parent.'\',\''.$parent_key.'\')" ><img id="approve_dispatching_img_'.$row['Delivery Note Key'].'" src="/art/icons/package_green.png" alt=""> '._('Approve Dispatching').'</button>';

			$operations.='</div>';
		}else {
			$operations.='</div>';
			$operations.='<span style="color:#777">'._('Waiting shipping approval').'</a>';
		}



	}
	elseif ($row['Delivery Note State']=='Approved') {
		$operations.='<div class="buttons small '.$class.'">
		<button  onClick="set_as_dispatched('.$row['Delivery Note Key'].','.$user->get_staff_key().',\''.$parent.'\',\''.$parent_key.'\')" ><img id="set_as_dispatched_img_'.$row['Delivery Note Key'].'" src="/art/icons/lorry_go.png" alt=""> '._('Mark as Dispatched')."</button>
		</div>";
	}
	elseif ($row['Delivery Note State']=='Dispatched') {
		$operations.='<div class="buttons small '.$class.'">
		<button id="undo_dispatch_'.$row['Delivery Note Key'].'" onclick="undo_dispatch('.$row['Delivery Note Key'].')"><img id="undo_dispatch_icon_'.$row['Delivery Note Key'].'" src="/art/icons/arrow_rotate_anticlockwise.png"> '._('Undo dispatch').'</button>

		</div>';
	}
	else {
		$operations.='';
	}
	$operations.='<div style="clear:both"></div></div>';
	return $operations;

}

function post_transaction_notes($data){
		$notes='';
		switch ($data['Operation']) {
		case 'Resend':		
			switch ($data['State']) {
			case 'In Process':
				$notes.=sprintf('<a href="new_post_order.php?id=%d">%s</a>',$data['Order Key'],_('Item to be resended in process'));
				break;
			case 'In Warehouse':
				$notes.=sprintf('%s (<a href="dn.php?id=%d">%s</a>)',_('In warehouse'),$data['Delivery Note Key'],$data['Delivery Note ID']);


				break;
			case 'Dispatched':
				$notes.=sprintf(',%s <a href="dn.php?id=%d">%s</a>',_('Dispatched'),$data['Delivery Note Key'],$data['Delivery Note ID']);
				break;
			default:
				$notes.='';

			}

			break;
		case 'Refund':
			$notes=_('Refund');
			break;
		case 'Credit':
			switch ($data['State']) {
			case 'In Process':
				$notes.=sprintf('<a href="new_post_order.php?id=%d">%s</a>',$data['Order Key'],_('Credit in process'));
				break;
			case 'Saved':
				$notes.=sprintf('<a href="customer.php?id=%d">%s</a>',$data['Customer Key'],_('Credit in customer file'));
				break;
			case 'Dispatched':
				$notes.=sprintf(',%s <a href="dn.php?id=%d">%s</a>',_('Dispatched'),$data['Delivery Note Key'],$data['Delivery Note ID']);
				break;
			default:
				$notes.='';

			}

			break;

		default:
			$notes='';
		}


		$notes=preg_replace('/^,/','',$notes);
		
		return $notes;
}

?>
