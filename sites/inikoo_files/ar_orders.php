<?php
include_once 'common.php';
require_once 'ar_edit_common.php';
if (!isset($user)  or !is_object($user)  or !$user->id ) {
	exit;
}

if ($user->get('User Type')!='Customer') {
	exit;
}

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'msg'=>'Non acceptable request (t)');
	echo json_encode($response);
	exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('get_tax_info'):
	$data=prepare_values($_REQUEST,array(
			'order_key'=>array('type'=>'key')
		));
	get_tax_info($data);
	break;
case('list_orders'):
	list_orders();
	break;
case('transactions_dipatched'):
	transactions_dipatched();
	break;
case('transactions_invoice'):
	list_transactions_in_invoice();
	break;
case('transactions_in_process_in_dn'):
	list_transactions_in_process_in_dn();
	break;
case('transactions_cancelled'):
	transactions_cancelled();
	break;
case('transactions_to_process'):
	transactions_to_process();
	break;
case('transactions'):
	list_transactions_in_order();
	break;

case('assets_dispatched_to_customer'):
	list_assets_dispatched_to_customer();
	break;



default:

	$response=array('state'=>404,'msg'=>_('Operation not found'));
	echo json_encode($response);

}

function list_orders() {

	global $user,$customer;

	
	$conf=$_SESSION['state']['customer']['orders'];



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
		

	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;




	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');




		$_SESSION['state']['customer']['orders']['order']=$order;
		$_SESSION['state']['customer']['orders']['order_dir']=$order_dir;
		$_SESSION['state']['customer']['orders']['nr']=$number_results;
		$_SESSION['state']['customer']['orders']['sf']=$start_from;
	

	//print_r($_SESSION['state']['customer']['orders']);
	
	
	
	$wheref='';
	
	$where=sprintf(" where `Order Customer Key`=%d and `Order Current Dispatch State` not in ('In Process by Customer','In Process','Waiting for Payment Confirmation','Cancelled by Customer')",
	$customer->id
	);
	
	
	
	
		$sql="select count(Distinct O.`Order Key`) as total from `Order Dimension` O  $where $wheref ";


	$res=mysql_query($sql);
	if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		$total=$row['total'];
	}
	if ($wheref!='') {
		$sql="select count(Distinct O.`Order Key`) as total_without_filters from `Order Dimension`  $where  ";
		$res=mysql_query($sql);
		if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

			$total_records=$row['total_without_filters'];
			$filtered=$row['total_without_filters']-$total;
		}

	} else {
		$filtered=0;
		$filter_total=0;
		$total_records=$total;
	}
	mysql_free_result($res);


	$rtext=number($total_records)." ".ngettext('order','orders',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
	elseif ($total_records>20)
		$rtext_rpp=' ('._("Showing all").')';
	else
		$rtext_rpp='';
	
		$_order=$order;
	$_dir=$order_direction;


	$filter_msg='';

	if ($order=='id')
		$order='`Order File As`';
	elseif ( $order=='date')
		$order='O.`Order Date`';

	elseif ($order=='state')
		$order='O.`Order Current Dispatch State`';
	elseif ($order=='total')
		$order='O.`Order Balance Total Amount`';
	
	else
		$order='`Order File As`';

	

	$sql="select `Order Balance Total Amount`,`Order Current Payment State`,`Order Current Dispatch State`,`Order Out of Stock Net Amount`,`Order Invoiced Total Net Adjust Amount`,`Order Invoiced Total Tax Adjust Amount`,FORMAT(`Order Invoiced Total Net Adjust Amount`+`Order Invoiced Total Tax Adjust Amount`,2) as `Order Adjust Amount`,`Order Out of Stock Net Amount`,`Order Out of Stock Tax Amount`,FORMAT(`Order Out of Stock Net Amount`+`Order Out of Stock Tax Amount`,2) as `Order Out of Stock Amount`,`Order Invoiced Balance Total Amount`,`Order Type`,`Order Currency Exchange`,`Order Currency`,`Order Key`,`Order Public ID`,`Order Customer Key`,`Order Customer Name`,`Order Last Updated Date`,`Order Date`,`Order Total Amount` ,`Order Current XHTML Payment State` from `Order Dimension` O  $where   order by $order $order_direction limit $start_from,$number_results  ";

	


	$res = mysql_query($sql);
	

	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		
		switch($row['Order Current Dispatch State']){
		case 'Submitted by Customer':
			$state=_('In Process');
			break;
			case 'Cancelled':
			$state=_('Cancelled');
			break;	
		default:
		$state=$row['Order Current Dispatch State'];
		}
		
		$public_id=sprintf('<a href="profile.php?view=order&id=%d">%s</a>',$row['Order Key'],$row['Order Public ID']);
		
		$adata[]=array(
			'id'=>$public_id,
			
			'state'=>$state,
			'date'=>strftime("%a %e %b %Y", strtotime($row['Order Date'].' UTC')) ,
			'total'=>money($_SESSION['set_currency_exchange']*$row['Order Balance Total Amount'],$_SESSION['set_currency'])

		);
	}
	mysql_free_result($res);

	
	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'total_records'=>$total,
			'records_offset'=>$start_from,
			'records_perpage'=>$number_results,
			'records_order'=>$order,
			'records_order_dir'=>$order_dir,
			'filtered'=>$filtered
		)
		
	);
	echo json_encode($response);
}

function transactions_dipatched() {

	global $user;

	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
		$order_id=$_REQUEST['id'];
		$tmp=new Order($order_id);
		if ( $user->data['User Parent Key']!=$tmp->data['Order Customer Key']) {
			exit();
		}
	}else {
		return;
	}




	$where=' where `Order Transaction Type` not in ("Resend")  and  O.`Order Key`='.$order_id;

	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$data=array();

	$order=' order by O.`Product Code`';

	$sql="select `Page URL`,O.`Order Transaction Fact Key`,`Deal Info`,`Operation`,`Quantity`,`Order Currency Code`,`Order Quantity`,`Order Bonus Quantity`,`No Shipped Due Out of Stock`,P.`Product ID` ,P.`Product Code`,`Product XHTML Short Description`,`Shipped Quantity`,(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as amount
         from `Order Transaction Fact` O left join `Product Dimension` P on (P.`Product ID`=O.`Product ID`)
         left join `Order Post Transaction Dimension` POT on (O.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`)
         left join `Order Transaction Deal Bridge` DB on (DB.`Order Transaction Fact Key`=O.`Order Transaction Fact Key`)
			 left join `Page Store Dimension` PSD on (P.`Product Family Key`=PSD.`Page Parent Key` and `Page Store Section` ='Family Catalogue')
			 left join `Page Dimension` PaD on (PaD.`Page Key`=PSD.`Page Key`)
         $where $order  ";

	$sql="select O.`Order Transaction Fact Key`,`Deal Info`,`Operation`,`Quantity`,`Order Currency Code`,`Order Quantity`,`Order Bonus Quantity`,`No Shipped Due Out of Stock`,P.`Product ID` ,P.`Product Code`,`Product XHTML Short Description`,`Shipped Quantity`,(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as amount
         from `Order Transaction Fact` O left join `Product Dimension` P on (P.`Product ID`=O.`Product ID`)
         left join `Order Post Transaction Dimension` POT on (O.`Order Transaction Fact Key`=POT.`Order Transaction Fact Key`)
         left join `Order Transaction Deal Bridge` DB on (DB.`Order Transaction Fact Key`=O.`Order Transaction Fact Key`)

         $where $order  ";

	//  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";

	//print $sql;

	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

		$ordered='';
		if ($row['Order Quantity']!=0)
			$ordered.=number($row['Order Quantity']);
		if ($row['Order Bonus Quantity']>0) {
			$ordered='<br/>'._('Bonus').' +'.number($row['Order Bonus Quantity']);
		}
		if ($row['No Shipped Due Out of Stock']>0) {
			$ordered.='<br/> '._('No Stk').' -'.number($row['No Shipped Due Out of Stock']);
		}
		$ordered=preg_replace('/^<br\/>/','',$ordered);
		//$code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
		// if ($row['Page URL']) {
		//  $code=sprintf('<a href="http://%s">%s</a>',$row['Page URL'],$row['Product Code']);
		// }else {
		$code=$row['Product Code'];
		// }
		$dispatched=number($row['Shipped Quantity']);

		if ($row['Quantity']>0  and $row['Operation']=='Resend') {
			$dispatched.='<br/> '._('Resend').' +'.number($row['Quantity']);
		}

		$data[]=array(

			'code'=>$code
			,'description'=>$row['Product XHTML Short Description'].' <span class="discount_label">'.$row['Deal Info'].'</span>'

			,'ordered'=>$ordered
			,'dispatched'=>$dispatched
			,'invoiced'=>money($row['amount'],$row['Order Currency Code'])
		);
	}





	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function list_transactions_in_invoice() {

	exit;


	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
		$order_id=$_REQUEST['id'];
	else
		$order_id=$_SESSION['state']['invoice']['id'];




	$where=' where `Invoice Quantity`!=0 and  `Invoice Key`='.$order_id;
	$where2=' where  `Invoice Key`='.$order_id;
	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$data=array();
	$sql="select * from `Order Transaction Fact` O   left join  `Product Dimension` P on (O.`Product ID`=P.`Product ID`) $where order by O.`Product Code`  ";

	//  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
	//   print $sql;
	$result=mysql_query($sql);
	$total_gross=0;
	$total_discount=0;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		//   $total_charged+=$row['charge'];
		//      $total_discounts+=$ndiscount;
		//      $total_picks+=$row['dispatched'];
		$total_discount+=$row['Invoice Transaction Total Discount Amount'];
		$total_gross+=$row['Invoice Transaction Gross Amount'];
		//$code=sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);
		$code=$row['Product Code'];
		if ($row['Invoice Transaction Total Discount Amount']==0)
			$discount='';
		else
			$discount=money($row['Invoice Transaction Total Discount Amount'],$row['Invoice Currency Code']);

		$data[]=array(

			'code'=>$code,
			'description'=>$row['Product XHTML Short Description'],
			'tariff_code'=>$row['Product Tariff Code'],
			'quantity'=>number($row['Invoice Quantity']),
			'gross'=>money($row['Invoice Transaction Gross Amount'],$row['Invoice Currency Code']),
			'discount'=>$discount,
			'to_charge'=>money($row['Invoice Transaction Gross Amount']-$row['Invoice Transaction Total Discount Amount'],$row['Invoice Currency Code'])
		);
	}


	$sql="select * from `Order No Product Transaction Fact` $where2  ";
	//print $sql;
	//  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";
	//   print $sql;
	$result=mysql_query($sql);
	$total_gross=0;
	$total_discount=0;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		//   $total_charged+=$row['charge'];
		//      $total_discounts+=$ndiscount;
		//      $total_picks+=$row['dispatched'];
		//$total_discount+=$row['Invoice Transaction Total Discount Amount'];
		//$total_gross+=$row['Invoice Transaction Gross Amount'];
		//$code=sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);


		$data[]=array(

			'code'=>'',
			'description'=>$row['Transaction Description'],
			'tariff_code'=>'',
			'quantity'=>'',
			'gross'=>money($row['Transaction Invoice Net Amount'],$row['Currency Code']),
			'discount'=>'',
			'to_charge'=>money($row['Transaction Invoice Net Amount']+$row['Transaction Invoice Tax Amount'],$row['Currency Code'])
		);
	}


	/*
        $invoice=new Invoice($order_id);



        if ($invoice->data['Invoice Shipping Net Amount']!=0) {

            $data[]=array(

                        'code'=>'',
                        'description'=>_('Shipping'),
                        'tariff_code'=>'',
                        'quantity'=>'',
                        'gross'=>money($invoice->data['Invoice Shipping Net Amount'],$invoice->data['Invoice Currency']),
                        'discount'=>'',
                        'to_charge'=>money($invoice->data['Invoice Shipping Net Amount'],$invoice->data['Invoice Currency'])
                    );

        }
        if ($invoice->data['Invoice Charges Net Amount']!=0) {
            $data[]=array(

                        'code'=>'',
                        'description'=>_('Charges'),
                        'tariff_code'=>'',
                        'quantity'=>'',
                        'gross'=>money($invoice->data['Invoice Charges Net Amount'],$invoice->data['Invoice Currency']),
                        'discount'=>'',
                        'to_charge'=>money($invoice->data['Invoice Charges Net Amount'],$invoice->data['Invoice Currency'])
                    );
        }
        if ($invoice->data['Invoice Total Tax Amount']!=0) {
            $data[]=array(

                        'code'=>'',
                        'description'=>_('Tax'),
                        'tariff_code'=>'',
                        'quantity'=>'',
                        'gross'=>money($invoice->data['Invoice Total Tax Amount'],$invoice->data['Invoice Currency']),
                        'discount'=>'',
                        'to_charge'=>money($invoice->data['Invoice Total Tax Amount'],$invoice->data['Invoice Currency'])
                    );
        }

        $data[]=array(

                    'code'=>'',
                    'description'=>_('Total'),
                    'tariff_code'=>'',
                    'quantity'=>'',
                    'gross'=>'',
                    'discount'=>'',
                    'to_charge'=>'<b>'.money($invoice->data['Invoice Total Amount'],$invoice->data['Invoice Currency']).'</b>'
                );

             */


	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function list_transactions_in_process_in_dn() {
	exit;

	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
		$order_id=$_REQUEST['id'];
	else
		$order_id=$_SESSION['state']['dn']['id'];




	$where=sprintf(' where   `Delivery Note Key`=%d',$order_id);

	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$data=array();

	$sql=sprintf("select `Required`,`Part Unit Description`,`Part XHTML Currently Used In`,ITF.`Part SKU` from `Inventory Transaction Fact` as ITF left join `Part Dimension` P on (P.`Part SKU`=ITF.`Part SKU`)$where");

	$result=mysql_query($sql);
	$total_gross=0;
	$total_discount=0;
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


		$data[]=array(

			'part'=>sprintf('SKU%05d</a>',$row['Part SKU'])
			,'description'=>$row['Part Unit Description']
			,'used_in'=>$row['Part XHTML Currently Used In']
			,'quantity'=>number($row['Required'])

		);
	}



	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function transactions_cancelled() {

	exit;

	if (isset( $_REQUEST['order_key']) and is_numeric( $_REQUEST['order_key']))
		$order_id=$_REQUEST['order_key'];
	else
		return;




	$where=' where `Order Key`='.$order_id;

	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$data=array();
	$sql="select * from `Order Transaction Fact` O left join `Product Dimension` P on (P.`Product ID`=O.`Product ID`)  $where   ";

	//  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";





	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		//   $total_charged+=$row['charge'];
		//      $total_discounts+=$ndiscount;
		//      $total_picks+=$row['dispatched'];
		//$code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
		$code=$row['Product Code'];
		$data[]=array(

			'code'=>$code,
			'description'=>$row['Product XHTML Short Description'],
			'tariff_code'=>$row['Product Tariff Code'],
			'quantity'=>number($row['Order Quantity']),
			'gross'=>money($row['Order Transaction Gross Amount'],$row['Order Currency Code']),
			'discount'=>money($row['Order Transaction Total Discount Amount'],$row['Order Currency Code']),
			'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'],$row['Order Currency Code'])
		);
	}





	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}

function transactions_to_process() {
	exit;

	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
		$order_id=$_REQUEST['id'];
	else
		$order_id=$_SESSION['state']['order']['id'];




	$where=' where `Order Key`='.$order_id;

	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$data=array();
	$sql="select * from `Order Transaction Fact` O left join `Product History Dimension` PH on (O.`Product key`=PH.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PH.`Product ID`)  $where   ";

	//  $sql="select  p.id as id,p.code as code ,product_id,p.description,units,ordered,dispatched,charge,discount,promotion_id    from transaction as t left join product as p on (p.id=product_id)  $where    ";





	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		//   $total_charged+=$row['charge'];
		//      $total_discounts+=$ndiscount;
		//      $total_picks+=$row['dispatched'];
		//$code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
		$code=$row['Product Code'];
		$data[]=array(

			'code'=>$code
			,'description'=>$row['Product XHTML Short Description']
			,'tariff_code'=>$row['Product Tariff Code']
			,'quantity'=>number($row['Order Quantity'])
			,'gross'=>money($row['Order Transaction Gross Amount'],$row['Order Currency Code'])
			,'discount'=>money($row['Order Transaction Total Discount Amount'],$row['Order Currency Code'])
			,'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'],$row['Order Currency Code'])
		);
	}





	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$data
			//     'total_records'=>$total,
			//     'records_offset'=>$start_from,
			//     'records_returned'=>$start_from+$res->numRows(),
			//     'records_perpage'=>$number_results,
			//     'records_text'=>$rtext,
			//     'records_order'=>$order,
			//     'records_order_dir'=>$order_dir,
			//     'filtered'=>$filtered
		)
	);
	echo json_encode($response);
}


function list_assets_dispatched_to_customer() {

	global $user;


	if (isset( $_REQUEST['customer_key'])) {
		$customer_id=$_REQUEST['customer_key'];

		if ($user->data['User Parent Key']!=$customer_id) {
			exit;
		}


	} else {
		exit;
	}

	$start_from=0;
	$number_results=5000;
	$order='subject';
	$order_dir='';
	$f_field='';
	$f_value='';
	$to='';
	$from='';
	$type='Family';
	$tableid=0;




	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');




	switch ($type) {
	case('Family'):
		$group_by='Product Family Key';
		$subject='Product Family Code';
		$description='Product Family Name';
		$subject_label='family';
		$subject_label_plural='families';
		break;
	case('Department'):
		$group_by='Product Department Key';
		$description='Product Department Name';

		$subject='Product Department Code';
		$subject_label='department';
		$subject_label_plural='departments';
		break;
	default:
		$group_by='Product Code';
		$subject='Product Code';
		$description='Product XHTML Short Description';

		$subject_label='product';
		$subject_label_plural='products';
	}



	$where=sprintf("    where `Current Dispatching State` not in ('Cancelled') and `Customer Key`=%d  ",$customer_id);

	//print "$f_field $f_value  " ;

	$wheref='';
	if ($f_field=='description' and $f_value!='')
		$wheref.=" and ( `Deal Component Terms Description` like '".addslashes($f_value)."%' or `Deal Component Allowance Description` like '".addslashes($f_value)."%'  )   ";
	elseif ($f_field=='code' and $f_value!='') {
		switch ($type) {
		case('Family'):
			$wheref.=" and  `Product Family Code` like '".addslashes($f_value)."%'";
			break;
		case('Department'):
			$wheref.=" and  `Product Department Code` like '".addslashes($f_value)."%'";

			break;
		default:
			$wheref.=" and  OTF.`Product Code` like '".addslashes($f_value)."%'";

		}



	}

	$sql=sprintf("select count(distinct OTF.`%s`)  as total  from `Order Transaction Fact` OTF  left join `Product Dimension` PD on (PD.`Product ID`=OTF.`Product ID`)  $where  ",$group_by);

	$total=0;
	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	mysql_free_result($result);

	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(distinct OTF.`$group_by`)  as total   from `Order Transaction Fact` OTF  left join `Product Dimension` PD on (PD.`Product ID`=OTF.`Product ID`)  $where  $wheref ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$total_records-$total;
		}
		mysql_free_result($result);

	}


	$rtext=number($total_records)." ".ngettext($subject_label,$subject_label_plural,$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	elseif ($total_records>0)
		$rtext_rpp=' ('._('Showing all').')';
	else
		$rtext_rpp='';


	if ($total==0 and $filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with this name ")." <b>".$f_value."*</b> ";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any deal with description like ")." <b>".$f_value."*</b> ";
			break;
		}
	}
	elseif ($filtered>0) {
		switch ($f_field) {
		case('name'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with name like')." <b>".$f_value."*</b>";
			break;
		case('description'):
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('deals with description like')." <b>".$f_value."*</b>";
			break;
		}
	}
	else
		$filter_msg='';

	$_dir=$order_direction;
	$_order=$order;

	if ($order=='subject') {
		switch ($type) {
		case('Family'):
			$order='`Product Family Code`';
			break;
		case('Department'):
			$order='`Product Department Code`';
			break;
		default:
			$order='`Product Code`';
		}

	}
	elseif ($order=='description') {
		switch ($type) {
		case('Family'):
			$order='`Product Family Name`';
			break;
		case('Department'):
			$order='`Product Department Name`';
			break;
		default:
			$order='`Product XHTML Short Description`';
		}


	}
	elseif ($order=='dispatched') {
		$order='`Delivery Note Quantity`';
	}
	elseif ($order=='orders') {
		$order='`Number of Orders`';
	}
	elseif ($order=='ordered') {
		$order='`Order Quantity`';
	}

	$adata=array();
	$sql=sprintf("select `Page URL`, count(distinct `Order Key`) as `Number of Orders`,sum(`Order Quantity`) as `Order Quantity`,sum(`Delivery Note Quantity`) as `Delivery Note Quantity` ,OTF.`Product Code`,`Product Family Code`,OTF.`Product Family Key`,OTF.`Product Department Key`,D.`Product Department Code` ,`Product Family Name` , `Product XHTML Short Description` ,`Product Department Name`
    from `Order Transaction Fact` OTF left join `Product Dimension` PD on (PD.`Product ID`=OTF.`Product ID`)
    left join `Product Department Dimension` D on (D.`Product Department Key`=OTF.`Product Department Key`)
     left join `Page Store Dimension` PSD on (PD.`Product Family Key`=PSD.`Page Parent Key` and `Page Store Section` ='Family Catalogue')
			 left join `Page Dimension` PaD on (PaD.`Page Key`=PSD.`Page Key`)

    $where " ,$customer_id);
	$sql.=" $wheref ";
	$sql.=sprintf("  group by `%s`   order by $order $order_direction limit $start_from,$number_results   ",$group_by);
	//  print $sql;
	$res = mysql_query($sql);

	$total=mysql_num_rows($res);


	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

		if ($row['Page URL']) {
			$_subject=sprintf('<a href="http://%s">%s</a>',$row['Page URL'],$row[$subject]);
		}else {

			$_subject=$row[$subject];
		}
		$adata[]=array(
			'subject'=>$_subject,
			'description'=>$row[$description],

			'ordered'=>(float) $row['Order Quantity'],
			'dispatched'=>(float) $row['Delivery Note Quantity'],
			'orders'=>number($row['Number of Orders']),


		);
	}
	mysql_free_result($res);
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


function list_transactions_in_order() {


	if (isset( $_REQUEST['parent_key']))
		$parent_key=$_REQUEST['parent_key'];
	else
		exit("x");

	if (isset( $_REQUEST['parent']))
		$parent=$_REQUEST['parent'];
	else
		exit("x2");




	if (isset( $_REQUEST['sf']))
		$start_from=$_REQUEST['sf'];
	else
		$start_from=0;
	if (isset( $_REQUEST['nr']))
		$number_results=$_REQUEST['nr'];
	else
		$number_results=500;
	if (isset( $_REQUEST['o']))
		$order=$_REQUEST['o'];
	else
		$order='code';
	if (isset( $_REQUEST['od']))
		$order_dir=$_REQUEST['od'];
	else
		$order_dir='';

	if (isset( $_REQUEST['f_field']))
		$f_field=$_REQUEST['f_field'];
	else
		$f_field='';

	if (isset( $_REQUEST['f_value']))
		$f_value=$_REQUEST['f_value'];
	else
		$f_value='';






	if (isset( $_REQUEST['tableid']))
		$tableid=$_REQUEST['tableid'];
	else
		$tableid=0;
	$order_direction=(preg_match('/desc/',$order_dir)?'desc':'');




	$_order=$order;
	$_dir=$order_direction;



	$where=sprintf(' where `Order Key`=%d and `Order Quantity`>0',$parent_key);






	$wheref='';
	if ($f_field=='code'  and $f_value!='')
		$wheref.=" and OTF.`Product Code` like '".addslashes($f_value)."%'";



	$sql="select count(*) as total from `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) $where $wheref";

	$result=mysql_query($sql);
	if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		$total=$row['total'];
	}
	if ($wheref=='') {
		$filtered=0;
		$total_records=$total;
	} else {
		$sql="select count(*) as total from `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`) $where      ";
		$result=mysql_query($sql);
		if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
			$total_records=$row['total'];
			$filtered=$row['total']-$total;
		}

	}

	$rtext=number($total_records)." ".ngettext('Product','Products',$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));

	else
		$rtext_rpp='';







	$filter_msg='';

	switch ($f_field) {
	case('code'):
		if ($total==0 and $filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code")." <b>$f_value</b>* ";
		elseif ($filtered>0)
			$filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('products with code')." <b>$f_value</b>*)";
		break;


	}


	if ($order=='code')
		$order='OTF.`Product Code`';
	elseif ($order=='created')
		$order='`Order Date`';

	elseif ($order=='last_updated')
		$order='`Order Last Updated Date`';

	else {
		$order='OTF.`Product Code`';
	}






	$total_charged=0;
	$total_discounts=0;
	$total_picks=0;

	$adata=array();
	$sql="select `Order Bonus Quantity`,`Product Name`,`Product Price`,`Product Units Per Case`,(select `Page Key` from `Page Product Dimension` B  where B.`State`='Online' and  B.`Product ID`=OTF.`Product ID` limit 1 ) `Page Key`,(select `Page URL` from `Page Product Dimension` B left join `Page Dimension`  PA  on (PA.`Page Key`=B.`Page Key`) where B.`State`='Online' and  B.`Product ID`=OTF.`Product ID` limit 1 ) `Page URL`,`Order Last Updated Date`,`Order Date`,`Order Quantity`,`Order Transaction Gross Amount`,`Order Currency Code`,`Order Transaction Total Discount Amount`,OTF.`Product ID`,OTF.`Product Code`,`Product XHTML Short Description`,`Product Tariff Code`,(select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info` from `Order Transaction Fact` OTF left join `Product Dimension` P on (P.`Product ID`=OTF.`Product ID`)  $where  order by $order $order_direction limit $start_from,$number_results ";

	//print $sql;



	$result=mysql_query($sql);
	while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
		if ($row['Page URL']!='') {
			$code=sprintf('<a href="%s">%s</a>',$row['Page URL'],$row['Product Code']);
			$code=sprintf('<a href="page.php?id=%d">%s</a>',$row['Page Key'],$row['Product Code']);
		}else {
			$code=$row['Product Code'];
		}

		if ($row['Deal Info']) {
			$deal_info='<br/><span style="font-style:italics;color:#555555;font-size:90%">'.$row['Deal Info'].($row['Order Transaction Total Discount Amount']>0?', <span style="font-weight:800">'._('You save').':  '.money($_SESSION['set_currency_exchange']*$row['Order Transaction Total Discount Amount'],$_SESSION['set_currency']).'</span>':'').'</span>';
		}else {
			$deal_info='';
		}

		$qty=number($row['Order Quantity']);
		if($row['Order Bonus Quantity']!=0){
		$qty.='<br/> +'.number($row['Order Bonus Quantity']).' '._('free');
		}


		$adata[]=array(
			'pid'=>$row['Product ID'],
			'code'=>$code,
			'description'=>$row['Product Units Per Case'].'x '.$row['Product Name'].$deal_info,
			'price_per_outer'=>money($_SESSION['set_currency_exchange']*$row['Product Price'],$_SESSION['set_currency']),
			'tariff_code'=>$row['Product Tariff Code'],
			'ordered_quantity'=>$row['Order Quantity'],
			//'quantity'=>'<img style="float:left;height:12px" src="art/less.png"> '.number($row['Order Quantity']).' <img style="height:12px" src="art/add.png">',

			'quantity'=>$qty,
			'gross'=>money($_SESSION['set_currency_exchange']*$row['Order Transaction Gross Amount'],$_SESSION['set_currency']),
			'discount'=>money($_SESSION['set_currency_exchange']*$row['Order Transaction Total Discount Amount'],$_SESSION['set_currency']),
			'to_charge'=>money($_SESSION['set_currency_exchange']*($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount']),$_SESSION['set_currency']),
			'created'=>strftime("%a %e %b %Y %H:%M %Z",strtotime($row['Order Date'].' +0:00')),
			'last_updated'=>strftime("%a %e %b %Y %H:%M %Z",strtotime($row['Order Last Updated Date'].' +0:00'))

		);
	}





	$response=array('resultset'=>
		array(
			'state'=>200,
			'data'=>$adata,
			'sort_key'=>$_order,
			'sort_dir'=>$_dir,
			'tableid'=>$tableid,
			'filter_msg'=>$filter_msg,
			'rtext'=>$rtext,
			'rtext_rpp'=>$rtext_rpp,
			'total_records'=>$total_records,
			'records_offset'=>$start_from+1,
			'records_perpage'=>$number_results,
		)
	);
	echo json_encode($response);
}

function get_tax_info($data) {
	$order=new Order($data['order_key']);
	$order->set_display_currency($_SESSION['set_currency'],$_SESSION['set_currency_exchange']);

	$tax_info=$order->get_formated_tax_info();
	
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
			'ordered_products_number'=>$order->get('Number Products'),
		);
	
	$response=
		array(
			'state'=>200,
			'data'=>$updated_data,
			'tax_info'=>$tax_info,

		
	);
	echo json_encode($response);


}


?>
