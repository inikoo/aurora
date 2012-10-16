<?php
include_once 'common.php';

if(!isset($user)  or !is_object($user)  or !$user->id ){
	exit;
}

if ($user->get('User Type')!='Customer') {
			exit;
		}

if (!isset($_REQUEST['tipo'])) {
	$response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
	echo json_encode($response);
	exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
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

case('assets_dispatched_to_customer'):
	list_assets_dispatched_to_customer();
	break;
default:

	$response=array('state'=>404,'msg'=>_('Operation not found'));
	echo json_encode($response);

}

function list_orders() {

global $user;


if(isset($_REQUEST['customer_key'])  and is_numeric($_REQUEST['customer_key']) ){
	$customer_key=$_REQUEST['customer_key'];
	
	}else{
	exit;
	}
	
	if ( $user->data['User Parent Key']!=$customer_key) {
			exit();
		}		
	
	$adata=array();

	$sql="select `Order Current Payment State`,`Order Current Dispatch State`,`Order Out of Stock Net Amount`,`Order Invoiced Total Net Adjust Amount`,`Order Invoiced Total Tax Adjust Amount`,FORMAT(`Order Invoiced Total Net Adjust Amount`+`Order Invoiced Total Tax Adjust Amount`,2) as `Order Adjust Amount`,`Order Out of Stock Net Amount`,`Order Out of Stock Tax Amount`,FORMAT(`Order Out of Stock Net Amount`+`Order Out of Stock Tax Amount`,2) as `Order Out of Stock Amount`,`Order Invoiced Balance Total Amount`,`Order Type`,`Order Currency Exchange`,`Order Currency`,`Order Key`,`Order Public ID`,`Order Customer Key`,`Order Customer Name`,`Order Last Updated Date`,`Order Date`,`Order Total Amount` ,`Order Current XHTML State` from `Order Dimension` where `Order Customer Key`=$customer_key order by `Order Date` desc";

	$res = mysql_query($sql);
	//print_r($sql);
	$total=mysql_num_rows($res);
	//print $sql;
	while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
		$mark_out_of_stock="<span style='visibility:hidden'>&otimes;</span>";
		$mark_out_of_credits="<span style='visibility:hidden'>&crarr;</span>";
		$mark_out_of_error="<span style='visibility:hidden'>&epsilon;</span>";
		$out_of_stock=false;
		$errors=false;
		$refunded=false;
		if ($row['Order Out of Stock Amount']!=0) {
			$out_of_stock=true;
			$info='';
			if ($row['Order Out of Stock Net Amount']!=0) {
				$info.=_('Net').': '.money($row['Order Out of Stock Net Amount'],$row['Order Currency'])."";
			}
			if ($row['Order Out of Stock Tax Amount']!=0) {
				$info.='; '._('Tax').': '.money($row['Order Out of Stock Tax Amount'],$row['Order Currency']);
			}
			$info=preg_replace('/^\;\s*/','',$info);
			$mark_out_of_stock="<span style='color:brown'  title='$info'  >&otimes;</span>";

		}

		if ($row['Order Adjust Amount']<-0.01 or $row['Order Adjust Amount']>0.01 ) {
			$errors=true;
			$info='';
			if ($row['Order Invoiced Total Net Adjust Amount']!=0) {
				$info.=_('Net').': '.money($row['Order Invoiced Total Net Adjust Amount'],$row['Order Currency'])."";
			}
			if ($row['Order Invoiced Total Tax Adjust Amount']!=0) {
				$info.='; '._('Tax').': '.money($row['Order Invoiced Total Tax Adjust Amount'],$row['Order Currency']);
			}
			$info=_('Errors').' '.preg_replace('/^\;\s*/','',$info);
			if ($row['Order Adjust Amount']<-1 or $row['Order Adjust Amount']>1 ) {
				$mark_out_of_error ="<span style='color:red' title='$info'>&epsilon;</span>";
			} else {
				$mark_out_of_error ="<span style='color:brown'  title='$info'>&epsilon;</span>";
			}
			//$mark_out_of_error.=$row['Order Adjust Amount'];
		}


		if (!$out_of_stock and !$refunded)
			$mark=$mark_out_of_error.$mark_out_of_stock.$mark_out_of_credits;
		elseif (!$refunded and $out_of_stock and $errors)
			$mark=$mark_out_of_stock.$mark_out_of_error.$mark_out_of_credits;
		else
			$mark=$mark_out_of_stock.$mark_out_of_credits.$mark_out_of_error;

		if ($row['Order Current Dispatch State']=='Unknown')
			continue;


		if ($row['Order Current Dispatch State']=='Dispatched') {
			$public_id=sprintf("<b><a href='profile.php?view=orders&order_id=%d'>%s</a></b>",$row['Order Key'],$row['Order Public ID']);


		}else {
			$public_id=$row['Order Public ID'];

		}


		$adata[]=array(
			'id'=>$public_id,
			//'id'=>$row['Order Public ID'],
			'state'=>$row['Order Current Dispatch State'],
			'date'=>strftime("%a %e %b %Y", strtotime($row['Order Date'].' UTC')) ,
			//  'total'=>money($row['Order Invoiced Balance Total Amount'],$row['Order Currency']).$mark,
			'total'=>money($row['Order Invoiced Balance Total Amount'],'GBP')

		);
	}
	mysql_free_result($res);

	$rtext=$total." Orders";
	$response=array('resultset'=>
		array('state'=>200,
			'data'=>$adata,
			'sort_key'=>'date',
			'sort_dir'=>'desc',
			'tableid'=>0,
			'filter_msg'=>'',
			'rtext'=>$rtext,
			'rtext_rpp'=>'',
			'total_records'=>$total,
			'records_offset'=>0,
			'records_perpage'=>25,
		)
	);
	echo json_encode($response);
}

function transactions_dipatched() {

global $user;

	if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])){
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
	//	if ($row['Page URL']) {
	//		$code=sprintf('<a href="http://%s">%s</a>',$row['Page URL'],$row['Product Code']);
	//	}else {
			$code=$row['Product Code'];
	//	}
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
		$wheref.=" and ( `Deal Metadata Terms Description` like '".addslashes($f_value)."%' or `Deal Metadata Allowance Description` like '".addslashes($f_value)."%'  )   ";
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


	$rtext=$total_records." ".ngettext($subject_label,$subject_label_plural,$total_records);
	if ($total_records>$number_results)
		$rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
	else
		$rtext_rpp=' ('._('Showing all').')';

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


?>
