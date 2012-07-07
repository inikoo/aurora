<?php
require_once 'common.php';
require_once 'ar_edit_common.php';
require_once 'class.Order.php';
require_once 'class.Customer.php';
require_once 'class.User.php';



if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>407,'resp'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}


if (!$customer->id) {
	$response=array('state'=>400,'resp'=>'not customer');
	echo json_encode($response);
	exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case 'edit_order_transaction':
	$data=prepare_values($_REQUEST,array(
			'pid'=>array('type'=>'key'),
			'qty'=>array('type'=>'numeric'),

		));

	edit_order_transaction($data);


	break;
}




function edit_order_transaction($__data) {

	global $customer,$order;

	if (!$order) {
		$order=create_order();
	}

	$product_pid=$__data['pid'];
	$quantity=$__data['qty'];

	if (is_numeric($quantity) and $quantity>=0) {

		if (in_array($order->data['Order Current Dispatch State'],array('Ready to Pick','Picking & Packing','Packed')) ) {
			$dispatching_state='Ready to Pick';
		}else {

			$dispatching_state='In Process';
		}

		$payment_state='Waiting Payment';


		$product=new Product('pid',$product_pid);
		$data=array(
			'date'=>date('Y-m-d H:i:s'),
			'Product Key'=>$product->data['Product Current Key'],
			'Metadata'=>'',
			'qty'=>$quantity,
			'Current Dispatching State'=>$dispatching_state,
			'Current Payment State'=>$payment_state
		);

		$disconted_products=$order->get_discounted_products();
		$order->skip_update_after_individual_transaction=false;

		$transaction_data=$order->add_order_transaction($data);
		if (!$transaction_data['updated']) {
			$response= array('state'=>200,'newvalue'=>$_REQUEST['oldvalue'],'key'=>$_REQUEST['id']);
			echo json_encode($response);
			return;
		}

		$new_disconted_products=$order->get_discounted_products();
		foreach ($new_disconted_products as $key=>$value) {
			$disconted_products[$key]=$value;
		}

		$adata=array();

		if (count($disconted_products)>0) {

			$product_keys=join(',',$disconted_products);
			$sql=sprintf("select (select `Deal Info` from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info`,P.`Product ID`,`Product XHTML Short Description`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` from `Order Transaction Fact` OTF   left join `Product Dimension` P on (OTF.`Product ID`=P.`Product ID`) where OTF.`Order Key`=%d and OTF.`Product Key` in (%s)",
				$order->id,
				$product_keys);


			//print $sql;
			$res = mysql_query($sql);
			$adata=array();

			while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
				$deal_info='';
				if ($row['Deal Info']!='') {
					$deal_info=' <span class="deal_info">'.$row['Deal Info'].'</span>';
				}

				$adata[$row['Product ID']]=array(
					'pid'=>$row['Product ID'],
					'description'=>$row['Product XHTML Short Description'].$deal_info,
					'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'],$order->data['Order Currency'])
				);
			};
		}


		$updated_data=array(
			'order_items_gross'=>$order->get('Items Gross Amount'),
			'order_items_discount'=>$order->get('Items Discount Amount'),
			'order_items_net'=>$order->get('Items Net Amount'),
			'order_net'=>$order->get('Total Net Amount'),
			'order_tax'=>$order->get('Total Tax Amount'),
			'order_charges'=>$order->get('Charges Net Amount'),
			'order_credits'=>$order->get('Net Credited Amount'),
			'order_shipping'=>$order->get('Shipping Net Amount'),
			'order_total'=>$order->get('Total Amount'),
			'ordered_products_number'=>$order->get('Number Items'),
		);

		$response= array(
			'state'=>200,
			'quantity'=>$transaction_data['qty'],
			'product_pid'=>$product_pid,
			'description'=>$product->data['Product XHTML Short Description'],
			'discount_percentage'=>$transaction_data['discount_percentage'],
			'key'=>$order->id,
			'data'=>$updated_data,
			'to_charge'=>$transaction_data['to_charge'],
			'discount_data'=>$adata,
			'discounts'=>($order->data['Order Items Discount Amount']!=0?true:false),
			'charges'=>($order->data['Order Charges Net Amount']!=0?true:false)
		);
	} else
		$response= array('state'=>200);
	echo json_encode($response);

}


function create_order() {
	global $user,$customer,$order;
	$editor=array(
		'Author Name'=>$user->data['User Alias'],
		'Author Alias'=>$user->data['User Alias'],
		'Author Type'=>$user->data['User Type'],
		'Author Key'=>$user->data['User Parent Key'],
		'User Key'=>$user->id
	);

	$order_data=array(

		'Customer Key'=>$customer->id,
		'Order Original Data MIME Type'=>'application/inikoo',
		'Order Type'=>'Order',
		'Order Current Dispatch State'=>'In Process by Customer',
		'editor'=>$editor

	);


	$order=new Order('new',$order_data);

	$ship_to=$customer->get_ship_to();
	$order-> update_ship_to($ship_to->id);

	return $order;
}




?>
