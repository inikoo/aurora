<?php
/*
 About: 
 Autor: Raul Perusquia <raul@inikoo.com>
 Created: 18 January 2014 14:09:57 GMT, Sheffield UK
 Copyright (c) 2009, Inikoo 
 
 Version 2.0
*/

function get_order_formated_dispatch_state($state,$order_key){
switch ($state) {
	case 'Packed Done':
			return _('Packed & Checked');
			break;
		default:
			$dispatch_state=$state;
		}
		return '<span id="dispatch_state_'.$order_key.'">'.$dispatch_state.'</span>';

}

function get_order_formated_payment_state($data){
switch ($data['Order Current Payment State']) {
case 'Not Invoiced':
			$payment_state='<span style="opacity:.3">'._('Not Invoiced').'</span>';
			break;
case 'Waiting Payment':
			$payment_state=_('Waiting Payment');

break;

		default:
			$payment_state=$data['Order Current Payment State'];
		}
		return '<span id="payment_state_'.$data['Order Key'].'">'.$payment_state.'</span>';
}
function get_dn_operations($row,$user,$class='left') {



	$operations='<div  id="operations'.$row['Delivery Note Key'].'">';
	if ($row['Delivery Note State']=='Ready to be Picked') {
		$operations.='<div class="buttons small '.$class.'">';

		if ($user->can_edit('assign_pp')) {
			$operations.='<button  class="first" onClick="assign_picker(this,'.$row['Delivery Note Key'].')"><img style="height:12px;width:12px" src="art/icons/user.png"> '._('Assign Picker')."</button>";
		}
		if ($user->can_edit('pick')) {
			$operations.=' <button  onClick="pick_it_fast(this,'.$user->get_staff_key().','.$row['Delivery Note Key'].')"><img id="pick_it_fast_img_'.$row['Delivery Note Key'].'" style="height:12px;width:12px" src="art/icons/paste_plain.png"> '._('Pick Order')."</button>";
		}
		if ($user->data['User Type']=='Warehouse') {
			$operations.=' <button  onClick="pick_it(this,'.$row['Delivery Note Key'].')">'._('Pick Order')."</button>";
		}
		$operations.='</div>';

	}
	elseif ($row['Delivery Note State']=='Picker Assigned') {
		$operations.='<div class="buttons small '.$class.'">';
		if ($user->can_edit('assign_pp')) {
			$operations.=' <button  style="cursor:pointer"  onClick="assign_picker(this,'.$row['Delivery Note Key'].')"><img src="art/icons/user_edit.png"/> '._('Change Picker').'</button>';
		}
		$operations.='</span>';
		if ($row['Delivery Note Assigned Picker Key']==$user->get_staff_key())
			$operations.='<button onClick="start_picking('.$row['Delivery Note Key'].','.$row['Delivery Note Assigned Picker Key'].')"  href="order_pick_aid.php?id='.$row['Delivery Note Key'].'"  ><img id="start_picking_img_'.$row['Delivery Note Key'].'" style="height:12px;width:12px" src="art/icons/paste_plain.png"> '._('Start Picking')."</button>";
		$operations.='</div>';
	}
	elseif ($row['Delivery Note State']=='Packer Assigned') {

		$operations.='<div class="buttons small '.$class.'">';
		$operations.='<span style="float:left;;margin-left:7px"><img style="height:12px;width:12px" src="art/icons/user_red.png" title="'._('Packing assigned to').'"/> <span style="font-weight:bold">'.$row['Delivery Note Assigned Packer Alias'].'</span>';
		if ($user->can_edit('assign_pp')) {
			$operations.=' <img src="art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_packer(this,'.$row['Delivery Note Key'].')">';
		}
		$operations.='</span>';
		if ($row['Delivery Note Assigned Packer Key']==$user->get_staff_key())
			$operations.='<button onClick="start_packing('.$row['Delivery Note Key'].','.$row['Delivery Note Assigned Packer Key'].')"  ><img id="start_packing_img_'.$row['Delivery Note Key'].'" style="height:12px;width:12px" src="art/icons/briefcase.png"> '._('Start Packing')."</button>";
		$operations.='</div>';

		// $operations.='<b>'.$row['Delivery Note Assigned Packer Alias'].'</b>   <a  href="order_pack_aid.php?id='.$row['Delivery Note Key'].'"  > '._('pack order')."</a>";
		//  $operations.=' <img src="art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_packer(this,'.$row['Delivery Note Key'].')">';
	}
	elseif ($row['Delivery Note State']=='Picking') {

		$operations.='<div class="buttons small '.$class.'">';
		
		
				if ($user->can_edit('assign_pp')) {
			$operations.=' <button  style="cursor:pointer"  onClick="assign_picker(this,'.$row['Delivery Note Key'].')"><img src="art/icons/user_edit.png"/> '._('Change Picker').'</button>';
		}
		
		$operations.='</span>';
		if ($row['Delivery Note Assigned Picker Key']==$user->get_staff_key()) {
			$operations.='<button  onClick="location.href=\'order_pick_aid.php?id='.$row['Delivery Note Key'].'\'"  ><img style="height:12px;width:12px" src="art/icons/paste_plain.png"> '._('Picking Aid')."</button>";
		}
		if ($user->can_edit('assign_pp') and $row['Delivery Note Assigned Packer Key']==0) {
			$operations.='<button  class="first" onClick="assign_packer(this,'.$row['Delivery Note Key'].')"><img style="height:12px;width:12px" src="art/icons/user_red.png"> '._('Assign Packer')."</button>";

		}

		$operations.='</div>';




		//$operations.=' | <b>'.$row['Delivery Note Assigned Picker Alias'].'</b>   <a  href="order_pick_aid.php?id='.$row['Delivery Note Key'].'"  > '._('picking order')."</a>";
		//$operations.=' <img src="art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_picker(this,'.$row['Delivery Note Key'].')">';

	}
	elseif ($row['Delivery Note State']=='Picked') {
		$operations.='<div class="buttons small '.$class.'">';
		if ($user->can_edit('assign_pp')) {
			$operations.='<button  class="first" onClick="assign_packer(this,'.$row['Delivery Note Key'].')"><img style="height:12px;width:12px" src="art/icons/user_red.png"> '._('Assign Packer')."</button>";

		}

		if ($user->can_edit('pack')) {
			$operations.='<button   onClick="pack_it_fast(this,'.$user->get_staff_key().','.$row['Delivery Note Key'].')"><img id="pack_it_fast_img_'.$row['Delivery Note Key'].'"  style="height:12px;width:12px" src="art/icons/briefcase.png"> '._('Start packing')."</button>";


		}
		$operations.='</div>';
	}
	elseif ($row['Delivery Note State']=='Packing') {

		$operations.='<div class="buttons small '.$class.'">';
		$operations.='<span style="float:left;margin-left:7px"> <img style="height:12px;width:12px" src="art/icons/user_red.png" title="'._('Packing by').'"/>  <span style="font-weight:bold">'.$row['Delivery Note Assigned Packer Alias'].'</span>';
		if ($user->can_edit('assign_pp')) {
			$operations.=' <img src="art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_packer(this,'.$row['Delivery Note Key'].')">';
		}
		$operations.='</span>';
		if ($row['Delivery Note Assigned Packer Key']==$user->get_staff_key()) {
			$operations.='<a   href="order_pack_aid.php?id='.$row['Delivery Note Key'].'"  ><img style="height:12px;width:12px" src="art/icons/briefcase.png"> '._('Packing Aid')."</a>";
		}
		$operations.='</div>';

	}
	elseif ($row['Delivery Note State']=='Packed') {

		$operations.='<div class="buttons small '.$class.'">';
		if ($user->can_edit('assign_pp')) {
			$operations.='<button   onClick="approve_packing('.$row['Delivery Note Key'].','.$user->get_staff_key().',\'warehouse_orders\')"><img id="approve_packing_img_'.$row['Delivery Note Key'].'"  style="height:12px;width:12px" src="art/icons/flag_green.png"> '._('Approve packing')."</button>";
		}else {
			$operations.='<span style="margin-left:7px">'._('Waiting for packing approval').'</span>';

		}

		$operations.='</div>';



	}elseif ($row['Delivery Note State']=='Picking & Packing') {
		$operations.='<b>'.$row['Delivery Note Assigned Picker Alias'].'</b>   <a  href="order_pick_aid.php?id='.$row['Delivery Note Key'].'"  > '._('picking order')."</a>";
		$operations.=' <img src="art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_picker(this,'.$row['Delivery Note Key'].')">';

		$operations.=' | <b>'.$row['Delivery Note Assigned Packer Alias'].'</b>   <a  href="order_pack_aid.php?id='.$row['Delivery Note Key'].'"  > '._('packing order')."</a>";
		$operations.=' <img src="art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_packer(this,'.$row['Delivery Note Key'].')">';


	}elseif ($row['Delivery Note State']=='Picking & Packing') {
		$operations.='<b>'.$row['Delivery Note Assigned Picker Alias'].'</b>   <a  href="order_pick_aid.php?id='.$row['Delivery Note Key'].'"  > '._('pick order')."</a>";
		$operations.=' <img src="art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_picker(this,'.$row['Delivery Note Key'].')">';

		$operations.=' | <b>'.$row['Delivery Note Assigned Packer Alias'].'</b>   <a  href="order_pack_aid.php?id='.$row['Delivery Note Key'].'"  > '._('pack order')."</a>";
		$operations.=' <img src="art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_packer(this,'.$row['Delivery Note Key'].')">';


	}elseif ($row['Delivery Note State']=='Packed Done') {
		$operations.='<span style="color:#777">'._('Waiting shipping approval').'</a>';
	}elseif ($row['Delivery Note State']=='Approved') {
		$operations.='<div class="buttons small '.$class.'">
		<button  onClick="set_as_dispatched('.$row['Delivery Note Key'].','.$user->get_staff_key().',\'warehouse_orders\')" ><img id="set_as_dispatched_img_'.$row['Delivery Note Key'].'" src="art/icons/lorry_go.png" alt=""> '._('Set as Dispatched')."</button>
		</div>";
	}
	else {
		$operations.='';
	}
	$operations.='</div>';

	return $operations;

}


?>