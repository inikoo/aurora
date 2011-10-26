<?php
require_once 'common.php';
require_once 'ar_edit_common.php';
require_once 'class.Order.php';




if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>407,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}


$tipo=$_REQUEST['tipo'];

switch ($tipo) {
case('import_transactions_mals_e'):
    $data=prepare_values($_REQUEST,array(
                             'order_key'=>array('type'=>'dn_key'),
                             'values'=>array('type'=>'json array')

                         ));
    import_transactions_mals_e($data);
    break;
case('set_picking_aid_sheet_pending_as_picked'):
    $data=prepare_values($_REQUEST,array(
                             'dn_key'=>array('type'=>'dn_key'),


                         ));
    set_picking_aid_sheet_pending_as_picked($data);
    break;

case('delete_order_list'):
    $data=prepare_values($_REQUEST,array(
                             'key'=>array('type'=>'key'),


                         ));
    delete_order_list($data);
    break;

case('delete_invoice_list'):
    $data=prepare_values($_REQUEST,array(
                             'key'=>array('type'=>'key'),


                         ));
    delete_invoice_list($data);
    break;

case('delete_dn_list'):
    $data=prepare_values($_REQUEST,array(
                             'key'=>array('type'=>'key'),


                         ));
    delete_dn_list($data);
    break;
case('new_list'):
    if (!$user->can_view('orders'))
        exit();

    $data=prepare_values($_REQUEST,array(
                             'awhere'=>array('type'=>'json array'),
                             'store_id'=>array('type'=>'key'),
                             'list_name'=>array('type'=>'string'),
                             'list_type'=>array('type'=>'enum',
                                                'valid values regex'=>'/static|Dynamic/i'
                                               )
                         ));


    new_orders_list($data);
    break;



case('new__invoice_list'):
    if (!$user->can_view('orders'))
        exit();

    $data=prepare_values($_REQUEST,array(
                             'awhere'=>array('type'=>'json array'),
                             'store_id'=>array('type'=>'key'),
                             'list_name'=>array('type'=>'string'),
                             'list_type'=>array('type'=>'enum',
                                                'valid values regex'=>'/static|Dynamic/i'
                                               )
                         ));


    new_invoices_list($data);
    break;


case('new_dn_list'):
    if (!$user->can_view('orders'))
        exit();

    $data=prepare_values($_REQUEST,array(
                             'awhere'=>array('type'=>'json array'),
                             'store_id'=>array('type'=>'key'),
                             'list_name'=>array('type'=>'string'),
                             'list_type'=>array('type'=>'enum',
                                                'valid values regex'=>'/static|Dynamic/i'
                                               )
                         ));


    new_dn_list($data);
    break;
case('update_no_dispatched'):
    $data=prepare_values($_REQUEST,array(
                             'dn_key'=>  array('type'=>'key'),
                             'itf_key'=>  array('type'=>'key'),
                             'out_of_stock'=>  array('type'=>'numeric'),
                             'not_found'=>array('type'=>'numeric'),
                             'no_picked_other'=>array('type'=>'numeric'),
                         ));
    update_no_dispatched($data);
    break;
case('pick_order'):
    $data=prepare_values($_REQUEST,array(
                             'dn_key'=>  array('type'=>'key'),
                             'picker_key'=>  array('type'=>'numeric'),
                             'itf_key'=>array('type'=>'key'),
                             'new_value'=>array('type'=>'numeric'),
                             'key'=>array('type'=>'string'),
                         ));
    pick_order($data);
    break;
case('update_ship_to_key'):
    $data=prepare_values($_REQUEST,array(
                             'order_key'=>array('type'=>'key'),
                             'ship_to_key'=>array('type'=>'numeric')
                         ));
    update_ship_to_key($data);
    break;

case('create_refund'):
    $data=prepare_values($_REQUEST,array(
                             'order_key'=>array('type'=>'key')
                         ));
    create_refund($data);
    break;
case('send_post_order_to_warehouse'):
    $data=prepare_values($_REQUEST,array(
                             'order_key'=>array('type'=>'key')
                         ));
    send_post_order_to_warehouse($data);
    break;
case('cancel_post_transactions'):
    $data=prepare_values($_REQUEST,array(

                             'order_key'=>array('type'=>'key')
                         ));

    cancel_post_transactions_in_process($data);
    break;
case('picking_aid_sheet'):
    picking_aid_sheet();
    break;
case('create_invoice'):
    create_invoice();
    break;
case('assign_picker'):

    $data=prepare_values($_REQUEST,array(
                             'dn_key'=>array('type'=>'key'),
                             'pin'=>array('type'=>'string'),
                             'staff_key'=>array('type'=>'key')
                         ));

    assign_picker($data);
    break;
case('pick_it'):
    $data=prepare_values($_REQUEST,array(
                             'dn_key'=>array('type'=>'key'),
                             'pin'=>array('type'=>'string'),
                             'staff_key'=>array('type'=>'key')
                         ));

    start_picking($data);
    break;
case('pack_it'):
    $data=prepare_values($_REQUEST,array(
                             'dn_key'=>array('type'=>'key'),
                             'pin'=>array('type'=>'string'),
                             'staff_key'=>array('type'=>'key')
                         ));

    start_packing($data);
    break;
case('ready_to_pick_orders'):
    ready_to_pick_orders();
    break;
    break;

case('cancel'):
    cancel_order();
    break;
case('send_to_warehouse'):
    if (isset($_REQUEST['order_key']) and is_numeric($_REQUEST['order_key']) )
        $order_key=$_REQUEST['order_key'];
    else
        $order_key=$_SESSION['state']['order']['id'];
    send_to_warehouse($order_key);
    break;
case('edit_new_order'):
    edit_new_order();
    break;
case('edit_new_post_order'):
    $data=prepare_values($_REQUEST,array(
                             'order_key'=>array('type'=>'key'),
                             'otf_key'=>array('type'=>'key'),
                             'key'=>array('type'=>'string'),
                             'new_value'=>array('type'=>'string')
                         ));
    edit_new_post_order($data);
    break;
case('transactions_to_process'):
    transactions_to_process();
    break;
case('post_transactions_to_process'):
    post_transactions_to_process();
    break;
case('edit_new_order_shipping_type'):
    edit_new_order_shipping_type();
    break;
case('set_order_shipping'):
    $data=prepare_values($_REQUEST,array(
                             'order_key'=>array('type'=>'key'),
                             'value'=>array('type'=>'string')
                         ));

    set_order_shipping($data);
    break;

case('use_calculated_shipping'):
    $data=prepare_values($_REQUEST,array(
                             'order_key'=>array('type'=>'key'),
                         ));

    use_calculated_shipping($data);
    break;
case('update_order'):

    $data=prepare_values($_REQUEST,array(
                             'order_key'=>array('type'=>'key')
                         ));				 
	update_order($data);
	break;
default:
    $response=array('state'=>404,'resp'=>_('Operation not found'));
    echo json_encode($response);

}



function cancel_order() {
    global $editor;
    $order_key=$_SESSION['state']['order']['id'];

    $order=new Order($order_key);
    $order->editor=$editor;
    if (isset($_REQUEST['note']))
        $note=stripslashes(urldecode($_REQUEST['note']));
    else
        $note='';

    $order->cancel($note);
    if ($order->cancelled) {
        $response=array('state'=>200,'order_key'=>$order->id);
        echo json_encode($response);
    } else {
        $response=array('state'=>400,'msg'=>$order->msg);
        echo json_encode($response);

    }

}



function send_to_warehouse($order_key) {
    include_once('class.PartLocation.php');
    $order=new Order($order_key);






    $order->send_to_warehouse();
    if (!$order->error) {
        $response=array('state'=>200,'order_key'=>$order->id);
        echo json_encode($response);
    } else {
        $response=array('state'=>400,'msg'=>$order->msg);
        echo json_encode($response);

    }

}


function edit_new_order_shipping_type() {

    $order_key=$_REQUEST['id'];

    $value=$_REQUEST['newvalue'];

    $order=new Order($order_key);
    if ($order->id) {
        $order->update_order_is_for_collection($value);
        if ($order->updated) {
            $response=array('state'=>200,'result'=>'updated','new_value'=>$order->new_value);

        } else {
            $response=array('state'=>200,'result'=>'no_change');

        }

    } else {
        $response=array('state'=>400,'msg'=>$order->msg);

    }
    echo json_encode($response);



}


function use_calculated_shipping($data) {
    $order_key=$data['order_key'];



    $order=new Order($order_key);
    if ($order->id) {

        $order->use_calculated_shipping();




        $updated_data=array(
                          'order_items_gross'=>$order->get('Items Gross Amount'),
                          'order_items_discount'=>$order->get('Items Discount Amount'),
                          'order_items_net'=>$order->get('Items Net Amount'),
                          'order_net'=>$order->get('Total Net Amount'),
                          'order_tax'=>$order->get('Total Tax Amount'),
                          'order_charges'=>$order->get('Charges Net Amount'),
                          'order_credits'=>$order->get('Net Credited Amount'),
                          'order_shipping'=>$order->get('Shipping Net Amount'),
                          'order_total'=>$order->get('Total Amount')

                      );
        $response=array('state'=>200,'result'=>'updated','new_value'=>$order->new_value,'order_shipping_method'=>$order->data['Order Shipping Method'],'data'=>$updated_data,'shipping'=>money($order->new_value),'shipping_amount'=>$order->data['Order Shipping Net Amount']);






    } else {
        $response=array('state'=>400,'msg'=>$order->msg);

    }
    echo json_encode($response);


}

function set_order_shipping($data) {

    $order_key=$data['order_key'];

    $value=$data['value'];

    $order=new Order($order_key);
    if ($order->id) {
        $order->update_shipping_amount($value);
        if ($order->updated) {


            $updated_data=array(
                              'order_items_gross'=>$order->get('Items Gross Amount'),
                              'order_items_discount'=>$order->get('Items Discount Amount'),
                              'order_items_net'=>$order->get('Items Net Amount'),
                              'order_net'=>$order->get('Total Net Amount'),
                              'order_tax'=>$order->get('Total Tax Amount'),
                              'order_charges'=>$order->get('Charges Net Amount'),
                              'order_credits'=>$order->get('Net Credited Amount'),
                              'order_shipping'=>$order->get('Shipping Net Amount'),
                              'order_total'=>$order->get('Total Amount')

                          );
            $response=array('state'=>200,'result'=>'updated','new_value'=>$order->new_value,'data'=>$updated_data,'shipping_amount'=>$order->data['Order Shipping Net Amount'],'shipping'=>money($order->new_value),'order_shipping_method'=>$order->data['Order Shipping Method']);






        } else {
            $response=array('state'=>200,'result'=>'no_change');

        }

    } else {
        $response=array('state'=>400,'msg'=>$order->msg);

    }
    echo json_encode($response);

}

function edit_new_order() {

    $order_key=$_REQUEST['id'];

    $product_pid=$_REQUEST['pid'];
    $quantity=$_REQUEST['newvalue'];

    if (is_numeric($quantity) and $quantity>=0) {

        $order=new Order($order_key);


        $product=new Product('pid',$product_pid);

        //$gross=$quantity*$product->data['Product Price'];
        //$estimated_weight=$quantity*$product->data['Product Gross Weight'];

        $data=array(
                  'date'=>date('Y-m-d H:i:s'),
                  'Product Key'=>$product->data['Product Current Key'],
                  'Metadata'=>'',
                  'qty'=>$quantity,
                  'Current Dispatching State'=>'In Process',
                  'Current Payment State'=>'Waiting Payment'
              );

        $disconted_products=$order->get_discounted_products();
        $order->skip_update_after_individual_transaction=false;
        $transaction_data=$order->add_order_transaction($data);
        $new_disconted_products=$order->get_discounted_products();
        foreach($new_disconted_products as $key=>$value) {
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
$_SESSION['basket']['total']=$updated_data['order_total'];
$_SESSION['basket']['items']=$updated_data['ordered_products_number'];
//print_r($updated_data);
//print "total: ".$_SESSION['basket']['total'];
//print " qty: ".$_SESSION['basket']['items'];

        $response= array(
                       'state'=>200,
                       'quantity'=>$transaction_data['qty'],
                       'description'=>$product->data['Product XHTML Short Description'],
                       'key'=>$_REQUEST['key'],
                       'data'=>$updated_data,
                       'to_charge'=>$transaction_data['to_charge'],
                       'discount_data'=>$adata,
                       'discounts'=>($order->data['Order Items Discount Amount']!=0?true:false),
                       'charges'=>($order->data['Order Charges Net Amount']!=0?true:false)
                   );
    } else
        $response= array('state'=>200,'newvalue'=>$_REQUEST['oldvalue'],'key'=>$_REQUEST['key']);
    echo json_encode($response);

}

function edit_new_post_order($data) {

    $order_key=$data['order_key'];
    $otf_key=$data['otf_key'];
    $value=$data['new_value'];
    $key=$data['key'];
    $quantity=0;
    $order=new Order($order_key);


    $transaction_data=array(
                          'Quantity'=>0,
                          'Operation'=>$_SESSION['state']['order']['post_transactions']['operation'],
                          'Reason'=>$_SESSION['state']['order']['post_transactions']['reason'],
                          'To Be Returned'=>$_SESSION['state']['order']['post_transactions']['to_be_returned'],
                      );

    if ($key=='quantity' and is_numeric($value) and $value>=0) {
        $transaction_data['Quantity']=$value;
        $_key='Quantity';

    }
    elseif($key=='operation') {
        $transaction_data['Operation']=$value;
        $_key='Operation';
        $_SESSION['state']['order']['post_transactions']['operation']=$value;
    }
    elseif($key=='reason') {
        $transaction_data['Reason']=$value;
        $_key='Reason';
        $_SESSION['state']['order']['post_transactions']['reason']=$value;

    }
    elseif($key=='to_be_returned') {
        $transaction_data['To Be Returned']=$value;
        $_key='To Be Returned';
        $_SESSION['state']['order']['post_transactions']['to_be_returned']=$value;
    }
    else {
        $response=array('state'=>400,'msg'=>$order->msg);
        echo json_encode($response);
        exit;
    }



    $transaction_data=$order->create_post_transaction_in_process($otf_key,$_key,$transaction_data);
    // print_r($transaction_data);
    if ($order->updated) {
        $response= array(
                       'state'=>200,
                       'result'=>'updated',
                       'quantity'=>$transaction_data['Quantity'],
                       'operation'=>$transaction_data['Operation'],
                       'reason'=>$transaction_data['Reason'],
                       'to_be_returned'=>$transaction_data['To Be Returned'],
                       'data'=>$order->get_post_transactions_in_process_data(),
                       'new_value'=>$transaction_data[$_key]
                   );
    } else {
        $response= array(
                       'state'=>200,
                       'result'=>'nochange'
                   );

    }
    echo json_encode($response);

}

function transactions_to_process() {
    if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
        $order_id=$_REQUEST['id'];
        $_SESSION['state']['order']['id']=$order_id;
    } else
        $order_id=$_SESSION['state']['order']['id'];

    if (isset( $_REQUEST['store_key']) and is_numeric( $_REQUEST['store_key'])) {
        $store_key=$_REQUEST['store_key'];
        $_SESSION['state']['order']['store_key']=$store_key;
    } else
        $store_key=$_SESSION['state']['order']['store_key'];


    $conf=$_SESSION['state']['order']['products'];


//print_r($conf);


    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];

    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    if (isset( $_REQUEST['display']))
        $display=$_REQUEST['display'];
    else
        $display=$conf['display'];




    if (isset( $_REQUEST['sf'])) {
        $start_from=$_REQUEST['sf'];
        $_SESSION['state']['order'][$display]['sf']=$start_from;

    } else
        $start_from=$_SESSION['state']['order'][$display]['sf'];



    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
        $_SESSION['state']['order'][$display]['nr']=$number_results;
    }      else
        $number_results=$_SESSION['state']['order'][$display]['nr'];





    $_SESSION['state']['order']['products']['order']=$order;
    $_SESSION['state']['order']['products']['order_dir']=$order_direction;

    $_SESSION['state']['order']['products']['f_field']=$f_field;
    $_SESSION['state']['order']['products']['f_value']=$f_value;
    $_SESSION['state']['order']['products']['display']=$display;



    $store=new Store($store_key);



    if ($display=='all_products') {
        $table=' `Product Dimension` P ';
        $where=sprintf('where `Product Store Key`=%d  and `Product Record Type`="Normal"    and `Product Main Type` in ("Private","Sale") ',$store_key);
        $sql_qty=sprintf(',IFNULL((select sum(`Order Quantity`) from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=%d),0) as `Order Quantity`, IFNULL((select sum(`Order Transaction Total Discount Amount`) from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=%d),0) as `Order Transaction Total Discount Amount`, IFNULL((select sum(`Order Transaction Gross Amount`) from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=%d),0) as `Order Transaction Gross Amount` ,(  select GROUP_CONCAT(`Deal Info`) from  `Order Transaction Deal Bridge` OTDB  where OTDB.`Product Key`=`Product Current Key` and OTDB.`Order Key`=%d )  as `Deal Info`,"" as `Current Dispatching State` ',$order_id,$order_id,$order_id,$order_id);
    } else if ($display=='ordered_products') {
        $table='  `Order Transaction Fact` OTF  left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P on (PHD.`Product ID`=P.`Product ID`)  ';
        $where=sprintf(' where `Order Quantity`>0 and `Order Key`=%d',$order_id);
        $sql_qty=', `Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,(select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info`,`Current Dispatching State`';
    } else {
        exit();
    }




    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';
    $wheref='';
    if ($f_field=='code' and $f_value!='')
        $wheref.=" and  P.`Product Code` like '".addslashes($f_value)."%'";
    elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  P.`Product Name` like '%".addslashes($f_value)."%'";

    $sql="select count(*) as total from $table   $where $wheref   ";

    // print_r($conf);exit;
//  print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from $table  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }


    $rtext=$total_records." ".ngettext('product','products',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_order=$order;
    $_order_dir=$order_dir;
    $order='`Product Code File As`';
    if ($order=='stock')
        $order='`Product Availability`';
    if ($order=='code')
        $order='`Product Code File As`';
    else if ($order=='name')
        $order='`Product Name`';
    else if ($order=='available_for')
        $order='`Product Available Days Forecast`';
    elseif($order=='family') {
        $order='`Product Family`Code';
    }
    elseif($order=='dept') {
        $order='`Product Main Department Code`';
    }
    elseif($order=='expcode') {
        $order='`Product Tariff Code`';
    }
    elseif($order=='parts') {
        $order='`Product XHTML Parts`';
    }
    elseif($order=='supplied') {
        $order='`Product XHTML Supplied By`';
    }
    elseif($order=='gmroi') {
        $order='`Product GMROI`';
    }
    elseif($order=='state') {
        $order='`Product Sales State`';
    }
    elseif($order=='web') {
        $order='`Product Web Configuration`';
    }



    $sql="select `Product Stage`, `Product Availability`,`Product Record Type`,P.`Product ID`,P.`Product Code`,`Product XHTML Short Description`,`Product Price`,`Product Units Per Case`,`Product Record Type`,`Product Web Configuration`,`Product Family Name`,`Product Main Department Name`,`Product Tariff Code`,`Product XHTML Parts`,`Product GMROI`,`Product XHTML Parts`,`Product XHTML Supplied By`,`Product Stock Value`  $sql_qty from $table   $where $wheref order by $order $order_direction limit $start_from,$number_results    ";
// print $sql;

    $res = mysql_query($sql);

    $adata=array();

    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        if (is_numeric($row['Product Availability']))
            $stock=number($row['Product Availability']);
        else
            $stock='?';
        $type=$row['Product Record Type'];

        if ($row['Product Stage']=='In Process')
            $type.='<span style="color:red">*</span>';

        switch ($row['Product Web Configuration']) {
        case('Online Force Out of Stock'):
            $web_state=_('Out of Stock');
            break;
        case('Online Auto'):
            $web_state=_('Auto');
            break;
        case('Unknown'):
            $web_state=_('Unknown');
        case('Offline'):
            $web_state=_('Offline');
            break;
        case('Online Force Hide'):
            $web_state=_('Hide');
            break;
        case('Online Force For Sale'):
            $web_state=_('Sale');
            break;
        default:
            $web_state=$row['Product Web Configuration'];
        }


        $deal_info='';
        if ($row['Deal Info']!='') {
            $deal_info=' <span class="deal_info">'.$row['Deal Info'].'</span>';
        }

switch ($row['Current Dispatching State']) {
    case 'In Process by Customer':
        $dispatching_status=_('In Process by Customer');
        break;
   case 'Submitted by Customer':
        $dispatching_status=_('Submitted by Customer');
        break;
     case 'In Process':
        $dispatching_status=_('In Process');
        break;      
      case 'Ready to Pick':
        $dispatching_status=_('Ready to Pick');
        break;
           case 'Picking':
        $dispatching_status=_('Picking');
        break;
           case 'Ready to Pack':
        $dispatching_status=_('Ready to Pack');
        break;
           case 'Ready to Ship':
        $dispatching_status=_('Ready to Ship');
        break;
           case 'Dispatched':
        $dispatching_status=_('Dispatched');
        break;
      case 'Unknown':
        $dispatching_status=_('Unknown');
        break;
   case 'Packing':
        $dispatching_status=_('Packing');
        break;
        
     case 'Cancelled':
        $dispatching_status=_('Cancelled');
        break;      
      case 'No Picked Due Out of Stock':
        $dispatching_status=_('No Picked Due Out of Stock');
        break;
           case 'No Picked Due No Authorised':
        $dispatching_status=_('No Picked Due No Authorised');
        break;
           case 'No Picked Due Not Found':
        $dispatching_status=_('No Picked Due Not Found');
        break;
           case 'No Picked Due Other':
        $dispatching_status=_('No Picked Due Other');
        break;
           case 'Suspended':
        $dispatching_status=_('Suspended');
        break;
   default:
        $dispatching_status=$row['Current Dispatching State'];
        break;
}


        $code=sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);
        $adata[]=array(
                     'pid'=>$row['Product ID'],
                     'code'=>$code,
                     'description'=>$row['Product XHTML Short Description'].$deal_info,
                     'shortname'=>number($row['Product Units Per Case']).'x @'.money($row['Product Price']/$row['Product Units Per Case'],$store->data['Store Currency Code']).' '._('ea'),
                     'family'=>$row['Product Family Name'],
                     'dept'=>$row['Product Main Department Name'],
                     'expcode'=>$row['Product Tariff Code'],
                     'parts'=>$row['Product XHTML Parts'],
                     'supplied'=>$row['Product XHTML Supplied By'],
                     'gmroi'=>$row['Product GMROI'],
                     //		  'stock_value'=>money($row['Product Stock Value']),
                     'stock'=>$stock,
                     'quantity'=>$row['Order Quantity'],
                     'state'=>$type,
                     'web'=>$web_state,
                     //		  'image'=>$row['Product Main Image'],
                     'type'=>'item',
                     'add'=>'+',
                     'remove'=>'-',
                     //'change'=>'<span onClick="quick_change("+",'.$row['Product ID'].')" class="quick_add">+</span> <span class="quick_add" onClick="quick_change("-",'.$row['Product ID'].')" >-</span>',
                     'to_charge'=>'<span onClick="change_discount(this)">'.money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'],$store->data['Store Currency Code']).'</span>',
                     'dispatching_status'=>$dispatching_status
                        
                 );


    }

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records-$filtered,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);


}
function post_transactions_to_process() {

    if (isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id'])) {
        $order_id=$_REQUEST['id'];
        $_SESSION['state']['order']['id']=$order_id;
    } else
        $order_id=$_SESSION['state']['order']['id'];

    if (isset( $_REQUEST['store_key']) and is_numeric( $_REQUEST['store_key'])) {
        $store_key=$_REQUEST['store_key'];
        $_SESSION['state']['order']['store_key']=$store_key;
    } else
        $store_key=$_SESSION['state']['order']['store_key'];


    $conf=$_SESSION['state']['order']['post_transactions'];
    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (!is_numeric($start_from))
        $start_from=0;

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];


    }      else
        $number_results=$conf['nr'];

    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['order'];

    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['order_dir'];
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    /*  if (isset( $_REQUEST['where'])) */
    /*         $where=addslashes($_REQUEST['where']); */
    /*     else */
    /*         $where=$conf['where']; */


    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    $_SESSION['state']['order']['post_transactions']['order']=$order;
    $_SESSION['state']['order']['post_transactions']['order_dir']=$order_direction;
    $_SESSION['state']['order']['post_transactions']['nr']=$number_results;
    $_SESSION['state']['order']['post_transactions']['sf']=$start_from;
    $_SESSION['state']['order']['post_transactions']['f_field']=$f_field;
    $_SESSION['state']['order']['post_transactions']['f_value']=$f_value;



    $table='  `Order Transaction Fact` OTF  left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P on (PHD.`Product ID`=P.`Product ID`) left join `Order Post Transaction Dimension` POT on (POT.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`)  ';
    $where=sprintf(' where `Order Quantity`>0 and OTF.`Order Key`=%d',$order_id);
    $sql_qty=', `Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,(select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info`';





    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';
    $wheref='';
    if ($f_field=='code' and $f_value!='')
        $wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";
    elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Product Name` like '%".addslashes($f_value)."%'";

    $sql="select count(*) as total from $table   $where $wheref   ";

    // print_r($conf);exit;
    //print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from $table  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }


    $rtext=$total_records." ".ngettext('product','products',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with code like ")." <b>".$f_value."*</b> ";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any product with name like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with code like')." <b>".$f_value."*</b>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('products with name like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_order=$order;
    $_order_dir=$order_dir;
    $order='`Product Code File As`';
    if ($order=='stock')
        $order='`Product Availability`';
    if ($order=='code')
        $order='`Product Code File As`';
    else if ($order=='name')
        $order='`Product Name`';
    else if ($order=='available_for')
        $order='`Product Available Days Forecast`';
    elseif($order=='family') {
        $order='`Product Family`Code';
    }
    elseif($order=='dept') {
        $order='`Product Main Department Code`';
    }
    elseif($order=='expcode') {
        $order='`Product Tariff Code`';
    }
    elseif($order=='parts') {
        $order='`Product XHTML Parts`';
    }
    elseif($order=='supplied') {
        $order='`Product XHTML Supplied By`';
    }
    elseif($order=='gmroi') {
        $order='`Product GMROI`';
    }
    elseif($order=='state') {
        $order='`Product Sales State`';
    }
    elseif($order=='web') {
        $order='`Product Web Configuration`';
    }



    $sql="select `Reason`,`To Be Returned`,`Operation`,IFNULL(`Quantity`,'') as Quantity,OTF.`Order Key`,OTF.`Order Transaction Fact Key`,`Invoice Currency Code`,(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`) as charged, `Delivery Note Quantity`,`Product Availability`,`Product Record Type`,P.`Product ID`,`Product Code`,`Product XHTML Short Description`,`Product Price`,`Product Units Per Case`,`Product Record Type`,`Product Web Configuration`,`Product Family Name`,`Product Main Department Name`,`Product Tariff Code`,`Product XHTML Parts`,`Product GMROI`,`Product XHTML Parts`,`Product XHTML Supplied By`,`Product Stock Value`  $sql_qty from $table   $where $wheref order by $order $order_direction limit $start_from,$number_results    ";
//print $sql;

    $res = mysql_query($sql);

    $adata=array();

    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        if (is_numeric($row['Product Availability']))
            $stock=number($row['Product Availability']);
        else
            $stock='?';
        $type=$row['Product Record Type'];
        if ($row['Product Stage']=='In Process')
            $type.='<span style="color:red">*</span>';
        switch ($row['Product Web Configuration']) {
        case('Online Force Out of Stock'):
            $web_state=_('Out of Stock');
            break;
        case('Online Auto'):
            $web_state=_('Auto');
            break;
        case('Unknown'):
            $web_state=_('Unknown');
        case('Offline'):
            $web_state=_('Offline');
            break;
        case('Online Force Hide'):
            $web_state=_('Hide');
            break;
        case('Online Force For Sale'):
            $web_state=_('Sale');
            break;
        default:
            $web_state=$row['Product Web Configuration'];
        }


        $deal_info='';
        if ($row['Deal Info']!='') {
            $deal_info=' <span class="deal_info">'.$row['Deal Info'].'</span>';
        }



        $code=sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);
        $adata[]=array(
                     'otf_key'=>$row['Order Transaction Fact Key'],
                     'order_key'=>$row['Order Key'],
                     'pid'=>$row['Product ID'],
                     'code'=>$code,
                     'description'=>$row['Product XHTML Short Description'].$deal_info,

                     'stock'=>$stock,
                     'ordered'=>$row['Delivery Note Quantity'].' ('.money($row['charged'],$row['Invoice Currency Code']).')',
                     'state'=>$type,
                     'max_resend'=>$row['Delivery Note Quantity'],
                     'max_refund'=>$row['charged'],
                     'add'=>'+',
                     'remove'=>'-',
                     'to_charge'=>'<span onClick="change_discount(this)">'.money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount']).'</span>',
                     'quantity'=>$row['Quantity'],
                     'operation'=>$row['Operation'],
                     'reason'=>$row['Reason'],
                     'to_be_returned'=>$row['To Be Returned'],
                 );


    }

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$adata,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records-$filtered,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);


}

function ready_to_pick_orders() {

    $conf=$_SESSION['state']['orders']['ready_to_pick_dn'];
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
    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];
    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
    else
        $where=$conf['where'];

    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;



    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

    $_SESSION['state']['orders']['ready_to_pick_dn']['order']=$order;
    $_SESSION['state']['orders']['ready_to_pick_dn']['order_dir']=$order_direction;
    $_SESSION['state']['orders']['ready_to_pick_dn']['nr']=$number_results;
    $_SESSION['state']['orders']['ready_to_pick_dn']['sf']=$start_from;
    $_SESSION['state']['orders']['ready_to_pick_dn']['where']=$where;
    $_SESSION['state']['orders']['ready_to_pick_dn']['f_field']=$f_field;
    $_SESSION['state']['orders']['ready_to_pick_dn']['f_value']=$f_value;

    $where.=' and `Delivery Note State` not in ("Dispatched","Cancelled") ';


    $wheref='';

    if ($f_field=='max' and is_numeric($f_value) )
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Delivery Note Date Created`))<=".$f_value."    ";
    else if ($f_field=='min' and is_numeric($f_value) )
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Delivery Note Date Created`))>=".$f_value."    ";
    elseif($f_field=='customer_name' and $f_value!='')
    $wheref.=" and  `Delivery Note Customer Name` like '".addslashes($f_value)."%'";
    elseif($f_field=='public_id' and $f_value!='')
    $wheref.=" and  `Delivery Note ID` like '".addslashes($f_value)."%'";


    $sql="select count(*) as total from `Delivery Note Dimension`   $where $wheref ";
// print $sql ;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($where=='') {
        $filtered=0;
        $total_records=$total;
    } else {

        $sql="select count(*) as total from `Delivery Note Dimension`  $where";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }
    mysql_free_result($result);

    $rtext=$total_records." ".ngettext('delivery note','delivery notes',$total_records);

    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._("Showing all").')';

    $filter_msg='';

    switch ($f_field) {
    case('public_id'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with number")." <b>".$f_value."*</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders starting with')." <b>$f_value</b>)";
        break;
    case('customer_name'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order with customer")." <b>".$f_value."*</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with customer')." <b>".$f_value."*</b>)";
        break;
    case('minvalue'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order minimum value of")." <b>".money($f_value)."</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('orders with min value of')." <b>".money($f_value)."*</b>)";
        break;

    case('max'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order older than")." <b>".number($f_value)."</b> "._('days');
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('last')." <b>".number($f_value)."</b> "._('days orders').")";
        break;
    }




    $_order=$order;
    $_dir=$order_direction;



    if ($order=='customer')
        $order='`Delivery Note Customer Name`';
    else if ($order=='public_id')
        $order='`Delivery Note File As`';
    else if ($order=='status')
        $order='`Delivery Note State`';
    else
        $order='`Delivery Note Date Created`';



    $sql="select  `Delivery Note Assigned Packer Alias`,`Delivery Note Faction Packed`,`Delivery Note Faction Picked`,`Delivery Note Assigned Picker Key`,`Delivery Note Assigned Picker Alias`, `Delivery Note Date Created`,`Delivery Note Key`,`Delivery Note Customer Name`,`Delivery Note Estimated Weight`,`Delivery Note Distinct Items`,`Delivery Note State`,`Delivery Note ID`,`Delivery Note Estimated Weight`,`Delivery Note Distinct Items`  from `Delivery Note Dimension`   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
//print $sql;
    global $myconf;

    $data=array();

    $res = mysql_query($sql);
    //print $sql;
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        //  if($row['Order Last Updated Date']=='')
        //   $lap='';
        // else
        //  $lap=RelativeTime(date('U',strtotime($row['Order Last Updated Date'])));

        $w=weight($row['Delivery Note Estimated Weight']);
        $picks=number($row['Delivery Note Distinct Items']);

        $operations='<div id="operations'.$row['Delivery Note Key'].'">';
        if ($row['Delivery Note State']=='Ready to be Picked') {
            $status='<div id="dn_state'.$row['Delivery Note Key'].'">'._('Ready to be Picked').'</div>';
            $operations.='<span style="cursor:pointer"  onClick="assign_picker(this,'.$row['Delivery Note Key'].')">'._('Assign Picker')."</span>";
            $operations.=' | <span style="cursor:pointer"  onClick="pick_it(this,'.$row['Delivery Note Key'].')">'._('Pick order')."</span>";
            $public_id=$row['Delivery Note ID'];
        }
        elseif($row['Delivery Note State']=='Picker Assigned') {
            $status='<div id="dn_state'.$row['Delivery Note Key'].'">'._('Picker Assigned').'</div>';
            $operations.='<span style="cursor:pointer"  onClick="pick_it(this,'.$row['Delivery Note Key'].','.$row['Delivery Note Assigned Picker Key'].')"> <b>'.$row['Delivery Note Assigned Picker Alias'].'</b> '._('pick order')."</span>";
            $operations.=' <img src="art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_picker(this,'.$row['Delivery Note Key'].')">';
            $public_id=sprintf("<a href='order_pick_aid.php?id=%d'>%s</a>",$row['Delivery Note Key'],$row['Delivery Note ID']);
        }
        elseif($row['Delivery Note State']=='Packer Assigned') {
            $status='<div id="dn_state'.$row['Delivery Note Key'].'">'._('(Picked) Packer Assigned').'</div>';
            $operations.='<span style="cursor:pointer"  onClick="pack_it(this,'.$row['Delivery Note Key'].','.$row['Delivery Note Assigned Packer Key'].')"> <b>'.$row['Delivery Note Assigned Packer Alias'].'</b> '._('pack order')."</span>";
            $operations.=' <img src="art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_packer(this,'.$row['Delivery Note Key'].')">';
            $public_id=sprintf("<a href='order_pick_aid.php?id=%d'>%s</a>",$row['Delivery Note Key'],$row['Delivery Note ID']);
        }
        elseif($row['Delivery Note State']=='Picking') {
            $status='<div id="dn_state'.$row['Delivery Note Key'].'">'._('Picking').'('.percentage($row['Delivery Note Faction Picked'],1,0).') <b>'.$row['Delivery Note Assigned Picker Alias'].'</b> </div>';
            $operations.='<span style="cursor:pointer"  onClick="assign_packer(this,'.$row['Delivery Note Key'].')">'._('Assign Packer')."</span>";;
            $operations.=' | <span style="cursor:pointer"  onClick="pack_it(this,'.$row['Delivery Note Key'].')">'._('Start packing')."</span>";
            $public_id=sprintf("<a href='order_pick_aid.php?id=%d'>%s</a>",$row['Delivery Note Key'],$row['Delivery Note ID']);
        }
        elseif($row['Delivery Note State']=='Picked') {
            $status='<div id="dn_state'.$row['Delivery Note Key'].'">'._('Picked').'</div>';
            $operations.='<span style="cursor:pointer"  onClick="assign_packer(this,'.$row['Delivery Note Key'].')">'._('Assign Packer')."</span>";;
            $operations.=' | <span style="cursor:pointer"  onClick="pack_it(this,'.$row['Delivery Note Key'].')">'._('Start packing')."</span>";
            $public_id=sprintf("<a href='order_pick_aid.php?id=%d'>%s</a>",$row['Delivery Note Key'],$row['Delivery Note ID']);
        }
        elseif($row['Delivery Note State']=='Packing') {
            $status='<div id="dn_state'.$row['Delivery Note Key'].'">'._('Packing').'('.percentage($row['Delivery Note Faction Packed'],1,0).') <b>'.$row['Delivery Note Assigned Packer Alias'].'</b> </div>';

            $public_id=sprintf("<a href='order_pack_aid.php?id=%d'>%s</a>",$row['Delivery Note Key'],$row['Delivery Note ID']);
        }
        elseif($row['Delivery Note State']=='Packed') {
            $status='<div id="dn_state'.$row['Delivery Note Key'].'">'._('Packed').'</div>';

            $public_id=sprintf("<a href='order_pick_aid.php?id=%d'>%s</a>",$row['Delivery Note Key'],$row['Delivery Note ID']);
        }
        else {
            $operations.='';
            $status=$row['Delivery Note State'];
            $public_id=sprintf("<a href='dn.php?id=%d'>%s</a>",$row['Delivery Note Key'],$row['Delivery Note ID']);
            $public_id=$row['Delivery Note ID'];
            $public_id=sprintf("<a href='order_pick_aid.php?id=%d'>%s</a>",$row['Delivery Note Key'],$row['Delivery Note ID']);
        }
        $operations.='</div>';

        //$packer='';

        $see_link=sprintf("<a href='order_pick_aid.php?id=%d'>%s</a>",$row['Delivery Note Key'],"See Picking Sheet");
        $data[]=array(
                    'id'=>$row['Delivery Note Key'],
                    'public_id'=>$public_id,
                    'customer'=>$row['Delivery Note Customer Name'],
                    'weight'=>$w,
                    'picks'=>$picks,
                    'date'=>$row['Delivery Note Date Created'],
                    'operations'=>$operations,
                    'status'=>$status,
                    'see_link'=>$see_link
                );
    }
    mysql_free_result($res);

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'total_records'=>$total_records-$filtered,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);



}

function assign_picker($data) {

    $dn=new DeliveryNote($data['dn_key']);
    if (!$dn->id) {
        $response=array(
                      'state'=>400,
                      'msg'=>'Unknown Delivery Note'
                  );
        echo json_encode($response);
        exit;
    }


    $dn->assign_picker($data['staff_key']);
    if ($dn->assigned) {
        $response=array(
                      'state'=>200,
                      'action'=>'updated',
                      'operations'=>$dn->operations,
                      'dn_state'=>$dn->dn_state
                  );



    } else if ($dn->error) {
        $response=array(
                      'state'=>400,
                      'msg'=>$dn->msg
                  );



    } else {
        $response=array(
                      'state'=>200,
                      'action'=>'uncharged',

                  );


    }
    echo json_encode($response);

}

function start_picking($data) {

    $dn=new DeliveryNote($data['dn_key']);
    if (!$dn->id) {
        $response=array(
                      'state'=>400,
                      'msg'=>'Unknown Delivery Note'
                  );
        echo json_encode($response);
        exit;
    }


    $dn->start_picking($data['staff_key']);
    if ($dn->assigned) {
        $response=array(
                      'state'=>200,
                      'action'=>'updated',
                      'operations'=>$dn->operations,
                      'dn_state'=>$dn->dn_state
                  );



    } else if ($dn->error) {
        $response=array(
                      'state'=>400,
                      'msg'=>$dn->msg
                  );



    } else {
        $response=array(
                      'state'=>200,
                      'action'=>'uncharged',

                  );


    }
    echo json_encode($response);

}

function start_packing($data) {

    $dn=new DeliveryNote($data['dn_key']);
    if (!$dn->id) {
        $response=array(
                      'state'=>400,
                      'msg'=>'Unknown Delivery Note'
                  );
        echo json_encode($response);
        exit;
    }


    $dn->start_packing($data['staff_key']);
    if ($dn->assigned) {
        $response=array(
                      'state'=>200,
                      'action'=>'updated',
                      'operations'=>$dn->operations,
                      'dn_state'=>$dn->dn_state
                  );



    } else if ($dn->error) {
        $response=array(
                      'state'=>400,
                      'msg'=>$dn->msg
                  );



    } else {
        $response=array(
                      'state'=>200,
                      'action'=>'uncharged',

                  );


    }
    echo json_encode($response);

}




function set_picking_aid_sheet_pending_as_picked($data) {
    $dn_key=$data['dn_key'];

    $where=sprintf(' where `Delivery Note Key`=%d',$order_id);
    $sql="select  `Picked`,IFNULL(`Out of Stock`,0) as `Out of Stock`,IFNULL(`Not Found`,0) as `Not Found`,IFNULL(`No Picked Other`,0) as `No Picked Other` ,`Inventory Transaction Key`,`Part XHTML Currently Used In`,Part.`Part SKU`,`Part XHTML Description`,`Required`,`Part XHTML Picking Location` from `Inventory Transaction Fact` ITF  left join  `Part Dimension` Part on  (Part.`Part SKU`=ITF.`Part SKU`)  $where  ";
    // print $sql;
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $todo=$row['Required']-$row['Picked']-$row['Out of Stock']-$row['Not Found']-$row['No Picked Other'];

        $data=array(
                  'dn_key'=>$dn_key

              );


    }

}

function assign_picker_temp($data) {

    $dn=new DeliveryNote($data['dn_key']);
    if (!$dn->id) {
        $response=array(
                      'state'=>400,
                      'msg'=>'Unknown Delivery Note'
                  );
        echo json_encode($response);
        exit;
    }


    $dn->assign_picker($data['staff_key']);
    if ($dn->assigned) {
        $response=array(
                      'state'=>200,
                      'action'=>'updated',
                      'operations'=>$dn->operations,
                      'dn_state'=>$dn->dn_state
                  );



    } else if ($dn->error) {
        $response=array(
                      'state'=>400,
                      'msg'=>$dn->msg
                  );



    } else {
        $response=array(
                      'state'=>200,
                      'action'=>'uncharged',

                  );


    }
    // echo json_encode($response);

}

function picking_aid_sheet() {
    if (isset( $_REQUEST['dn_key']) and is_numeric( $_REQUEST['dn_key']))
        $order_id=$_REQUEST['dn_key'];
    else {

        return;
    }

    $data=array('dn_key'=>$order_id, 'staff_key'=>1);
    assign_picker_temp($data);


    $where=sprintf(' where `Delivery Note Key`=%d',$order_id);

    $total_charged=0;
    $total_discounts=0;
    $total_picks=0;

    $data=array();
    $sql="select  `Location Code`,`Picking Note`,`Picked`,IFNULL(`Out of Stock`,0) as `Out of Stock`,IFNULL(`Not Found`,0) as `Not Found`,IFNULL(`No Picked Other`,0) as `No Picked Other` ,`Inventory Transaction Key`,`Part XHTML Currently Used In`,Part.`Part SKU`,`Part XHTML Description`,`Required`,`Part XHTML Picking Location` from `Inventory Transaction Fact` ITF  left join  `Part Dimension` Part on  (Part.`Part SKU`=ITF.`Part SKU`) left join  `Location Dimension` L on  (L.`Location Key`=ITF.`Location Key`) $where  ";
    //   print $sql;
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
//print_r($row);
        $formated_todo='';
        $todo=0;
        if ($row['Required']-$row['Picked']>0) {

            $todo=$row['Required']-$row['Picked']-$row['Out of Stock']-$row['Not Found']-$row['No Picked Other'];
            if ($todo==0)
                $formated_todo='';
            else
                $formated_todo=number($todo);
        }


        $notes='';
        if ($row['Out of Stock']!=0) {
            $notes.=_('Out of Stock').' '.number($row['Out of Stock']);
        }
        if ($row['Not Found']!=0) {
            $notes.='<br/>'._('Not Found').' '.number($row['Not Found']);
        }
        if ($row['No Picked Other']!=0) {
            $notes.='<br/>'._('Not picked (other)').' '.number($row['No Picked Other']);
        }
//$notes=preg_replace('/^\,/', '', $notes);


        $sku=sprintf('<a href="part.php?sku=%d">SKU%05d</a>',$row['Part SKU'],$row['Part SKU']);
        $data[]=array(
                    'itf_key'=>$row['Inventory Transaction Key'],
                    'sku'=>$sku,
                    'description'=>$row['Part XHTML Description'],
                    'used_in'=>$row['Part XHTML Currently Used In'],
                    'quantity'=>number($row['Required']),
                    'location'=>$row['Location Code'],
                    'check_mark'=>'&#x2713;',
                    'add'=>'+',
                    'remove'=>'-',
                    'picked'=>$row['Picked'],
                    'todo'=>$todo,
                    'formated_todo'=>$formated_todo,
                    'notes'=>$notes,
                    'picking_notes'=>$row['Picking Note'],
                    'required'=>$row['Required'],
                    'picked'=>$row['Picked'],
                    'out_of_stock'=>$row['Out of Stock'],
                    'not_found'=>$row['Not Found'],
                    'no_picked_other'=>$row['No Picked Other'],
                    'see_link'=>'xx<a href="xx">'._('pick aid sheet').'</a>'
                );
    }





    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data
// 			 'total_records'=>$total,
// 			 'records_offset'=>$start_from,
// 			 'records_returned'=>$start_from+$res->numRows(),
// 			 'records_perpage'=>$number_results,
// 			 'records_text'=>$rtext,
// 			 'records_order'=>$order,
// 			 'records_order_dir'=>$order_dir,
// 			 'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
}


function create_invoice($dn_notes) {
    $dn_notes=preg_split('/\,/',$dn_notes);
    foreach($dn_notes as $dn_key) {
        $dn=new DeliveryNote($dn_key);
        $invoice=$dn->create_invoice();
    }
}
function cancel_post_transactions_in_process($data) {
    $order=new Order($data['order_key']);
    $order->cancel_post_transactions_in_process();
    if (!$order->error) {
        $response=array('state'=>200,'order_key'=>$order->id);
        echo json_encode($response);
    } else {
        $response=array('state'=>400,'msg'=>$order->msg);
        echo json_encode($response);

    }

}


function send_post_order_to_warehouse($data) {

    $order=new Order($data['order_key']);
    $customer=new Customer ($order->data['Order Customer Key']);
    $ship_to=$customer->get_ship_to();

    $transaction_data=array(
                          'Metadata'=>'',
                          'Current Payment State'=>'No Applicable',
                          'Order Tax Rate'=>$order->data['Order Tax Rate'],
                          'Order Tax Code'=>$order->data['Order Tax Code'],
                          'Ship To Key'=>$ship_to->id,
                          'Gross'=>0,
                      );

    $order->add_post_order_transactions($transaction_data);



    $order->send_post_action_to_warehouse();
    if (!$order->error) {
        $response=array('state'=>200,'order_key'=>$order->id);
        echo json_encode($response);
    } else {
        $response=array('state'=>400,'msg'=>$order->msg);
        echo json_encode($response);

    }

}

function create_refund($data) {

    $date=date("Y-m-d H:i:s");
    $order=new Order($data['order_key']);

    $refund=$order->create_refund(array(
                                      'Invoice Metadata'=>'',
                                      'Invoice Date'=>$date
                                  )
                                 );



    if (!$order->error) {
        $response=array('state'=>200,'order_key'=>$order->id);
        echo json_encode($response);
    } else {
        $response=array('state'=>400,'msg'=>$order->msg);
        echo json_encode($response);

    }

}

function  update_ship_to_key($data) {

    $order=new Order($data['order_key']);
    $order->update_ship_to($data['ship_to_key']);
    if ($order->updated) {
        $response=array('state'=>200,'result'=>'updated','order_key'=>$order->id,'new_value'=>$order->new_value);
        echo json_encode($response);
    } else {
        $response=array('state'=>400,'msg'=>$order->msg);
        echo json_encode($response);

    }


}


function pick_order($data) {

    $dn=new DeliveryNote($data['dn_key']);
    if ($data['key']=='quantity') {


        $transaction_data=$dn->set_as_picked($data['itf_key'],round($data['new_value'],8),date("Y-m-d H:i:s"),$data['picker_key']);

        $dn->update_picking_percentage();
        if (!$dn->error) {

            $response=array('state'=>200,
                            'result'=>'updated',
                            'new_value'=>$transaction_data['Picked'],
                            'todo'=>$transaction_data['Pending'],
                            'formated_todo'=>number($transaction_data['Pending']),

                            'picked'=>$transaction_data['Picked'],
                            'percentage_picked'=>$dn->get('Faction Picked'),
                            'number_picked_transactions'=>$dn->get_number_picked_transactions(),
                            'number_transactions'=>$dn->get_number_transactions()
                           );
            echo json_encode($response);
        } else {
            $response=array('state'=>400,'msg'=>$dn->msg);
            echo json_encode($response);
        }
        return;
    }

}


function update_no_dispatched($data) {
    $dn=new DeliveryNote($data['dn_key']);
    if (!$dn->id) {
        $response=array('state'=>400,'msg'=>$dn->msg);
        echo json_encode($response);
    }
    $transaction_data=$dn->update_unpicked_transaction_data($data['itf_key'],array(
                          'Out of Stock'=>$data['out_of_stock'],
                          'Not Found'=>$data['not_found'],
                          'No Picked Other'=>$data['no_picked_other']
                      )
                                                           );
    $dn->update_picking_percentage();


    if (!$dn->error) {

        if ($dn->updated) {

            $formated_todo='';

            if ($transaction_data['Pending']>0) {
                $formated_todo=number($transaction_data['Pending']);
            }




            $notes='';
            if ($transaction_data['Out of Stock']!=0) {
                $notes.=_('Out of Stock').' '.number($transaction_data['Out of Stock']);
            }
            if ($transaction_data['Not Found']!=0) {
                $notes.='<br/>'._('Not Found').' '.number($transaction_data['Not Found']);
            }
            if ($transaction_data['No Picked Other']!=0) {
                $notes.='<br/>'._('Not picked (other)').' '.number($transaction_data['No Picked Other']);
            }


            $response=array('state'=>200,'result'=>'updated','new_value'=>$dn->new_value,
                            'todo'=>$transaction_data['Pending'],
                            'formated_todo'=>$formated_todo,
                            'notes'=>$notes,
                            'out_of_stock'=>$transaction_data['Out of Stock'],
                            'not_found'=>$transaction_data['Not Found'],
                            'no_picked_other'=>$transaction_data['No Picked Other'],

                            'picked'=>$transaction_data['Picked'],
                            'percentage_picked'=>$dn->get('Faction Picked'),
                            'number_picked_transactions'=>$dn->get_number_picked_transactions(),
                            'number_transactions'=>$dn->get_number_transactions()
                           );

        } else {
            $response=array('state'=>200,'result'=>'no_change');

        }

    } else {
        $response=array('state'=>400,'msg'=>$dn->msg);

    }
    echo json_encode($response);

}
function new_dn_list($data) {

    $list_name=$data['list_name'];
    $store_id=$data['store_id'];

    $sql=sprintf("select * from `List Dimension`  where `List Name`=%s and `List Store Key`=%d and `List Scope`='DN'",
                 prepare_mysql($list_name),
                 $store_id
                );
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $response=array('resultset'=>
                                    array(
                                        'state'=>400,
                                        'msg'=>_('Another list has the same name')
                                    )
                       );
        echo json_encode($response);
        return;
    }

    $list_type=$data['list_type'];

    $awhere=$data['awhere'];
    $table='`Delivery Note Dimension` D ';


//   $where=customers_awhere($awhere);
    list($where,$table)=dn_awhere($awhere);

    $where.=sprintf(' and `Delivery Note Store Key`=%d ',$store_id);

    $sql="select count(Distinct D.`Delivery Note Key`) as total from $table  $where";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        if ($row['total']==0) {
            $response=array('resultset'=>
                                        array(
                                            'state'=>400,
                                            'msg'=>_('No order match this criteria')
                                        )
                           );
            echo json_encode($response);
            return;

        }


    }
    mysql_free_result($res);

    $list_sql=sprintf("insert into `List Dimension` (`List Scope`,`List Store Key`,`List Name`,`List Type`,`List Metadata`,`List Creation Date`) values ('Delivery Note',%d,%s,%s,%s,NOW())",
                      $store_id,
                      prepare_mysql($list_name),
                      prepare_mysql($list_type),
                      prepare_mysql(json_encode($data['awhere']))

                     );
    mysql_query($list_sql);
    $order_list_key=mysql_insert_id();
    if ($list_type=='Static') {


        $sql="select D.`Delivery Note Key` from $table  $where group by D.`Delivery Note Key`";
        // print $sql;
        $result=mysql_query($sql);
        while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $order_key=$data['Delivery Note Key'];
            $sql=sprintf("insert into `List Delivery Note Bridge` (`List Key`,`Delivery Note Key`) values (%d,%d)",
                         $order_list_key,
                         $order_key
                        );
            mysql_query($sql);

        }
        mysql_free_result($result);




    }




    $response=array(
                  'state'=>200,
                  'customer_list_key'=>$order_list_key

              );
    echo json_encode($response);
    exit;
}

function new_invoices_list($data) {

    $list_name=$data['list_name'];
    $store_id=$data['store_id'];

    $sql=sprintf("select * from `List Dimension`  where `List Name`=%s and `List Store Key`=%d and `List Scope`='Invoice'",
                 prepare_mysql($list_name),
                 $store_id
                );
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $response=array('resultset'=>
                                    array(
                                        'state'=>400,
                                        'msg'=>_('Another list has the same name')
                                    )
                       );
        echo json_encode($response);
        return;
    }

    $list_type=$data['list_type'];

    $awhere=$data['awhere'];
    $table='`Invoice Dimension` I ';


//   $where=customers_awhere($awhere);
    list($where,$table)=invoices_awhere($awhere);

    $where.=sprintf(' and `Invoice Store Key`=%d ',$store_id);

    $sql="select count(Distinct I.`Invoice Key`) as total from $table  $where";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        if ($row['total']==0) {
            $response=array('resultset'=>
                                        array(
                                            'state'=>400,
                                            'msg'=>_('No order match this criteria')
                                        )
                           );
            echo json_encode($response);
            return;

        }


    }
    mysql_free_result($res);

    $list_sql=sprintf("insert into `List Dimension` (`List Scope`,`List Store Key`,`List Name`,`List Type`,`List Metadata`,`List Creation Date`) values ('Invoice',%d,%s,%s,%s,NOW())",
                      $store_id,
                      prepare_mysql($list_name),
                      prepare_mysql($list_type),
                      prepare_mysql(json_encode($data['awhere']))

                     );
    mysql_query($list_sql);
    $order_list_key=mysql_insert_id();
    if ($list_type=='Static') {


        $sql="select I.`Invoice Key` from $table  $where group by O.`Invoice Key`";
        //   print $sql;
        $result=mysql_query($sql);
        while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $order_key=$data['Invoice Key'];
            $sql=sprintf("insert into `List Invoice Bridge` (`List Key`,`Invoice Key`) values (%d,%d)",
                         $order_list_key,
                         $order_key
                        );
            mysql_query($sql);

        }
        mysql_free_result($result);




    }




    $response=array(
                  'state'=>200,
                  'customer_list_key'=>$order_list_key

              );
    echo json_encode($response);
    exit;
}


function new_orders_list($data) {

    $list_name=$data['list_name'];
    $store_id=$data['store_id'];

    $sql=sprintf("select * from `List Dimension`  where `List Name`=%s and `List Store Key`=%d and `List Scope`='Order'",
                 prepare_mysql($list_name),
                 $store_id
                );
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $response=array('resultset'=>
                                    array(
                                        'state'=>400,
                                        'msg'=>_('Another list has the same name')
                                    )
                       );
        echo json_encode($response);
        return;
    }

    $list_type=$data['list_type'];

    $awhere=$data['awhere'];
    $table='`Order Dimension` O ';


//   $where=customers_awhere($awhere);
    list($where,$table)=orders_awhere($awhere);

    $where.=sprintf(' and `Order Store Key`=%d ',$store_id);

    $sql="select count(Distinct O.`Order Key`) as total from $table  $where";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        if ($row['total']==0) {
            $response=array('resultset'=>
                                        array(
                                            'state'=>400,
                                            'msg'=>_('No order match this criteria')
                                        )
                           );
            echo json_encode($response);
            return;

        }


    }
    mysql_free_result($res);

    $list_sql=sprintf("insert into `List Dimension` (`List Scope`,`List Store Key`,`List Name`,`List Type`,`List Metadata`,`List Creation Date`) values ('Order',%d,%s,%s,%s,NOW())",
                      $store_id,
                      prepare_mysql($list_name),
                      prepare_mysql($list_type),
                      prepare_mysql(json_encode($data['awhere']))

                     );
    mysql_query($list_sql);
    $order_list_key=mysql_insert_id();
    if ($list_type=='Static') {


        $sql="select O.`Order Key` from $table  $where group by O.`Order Key`";
        //   print $sql;
        $result=mysql_query($sql);
        while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $order_key=$data['Order Key'];
            $sql=sprintf("insert into `List Order Bridge` (`List Key`,`Order Key`) values (%d,%d)",
                         $order_list_key,
                         $order_key
                        );
            mysql_query($sql);

        }
        mysql_free_result($result);




    }




    $response=array(
                  'state'=>200,
                  'customer_list_key'=>$order_list_key

              );
    echo json_encode($response);
    exit;
}

function delete_order_list($data) {
    global $user;
    $sql=sprintf("select `List Store Key`,`List Key` from `List Dimension` where `List Key`=%d",$data['key']);

    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {

        if (in_array($row['List Store Key'],$user->stores)) {
            $sql=sprintf("delete from  `List Order Bridge` where `List Key`=%d",$data['key']);
            mysql_query($sql);
            $sql=sprintf("delete from  `List Dimension` where `List Key`=%d",$data['key']);
            mysql_query($sql);
            $response=array('state'=>200,'action'=>'deleted');
            echo json_encode($response);
            return;



        } else {
            $response=array('state'=>400,'msg'=>_('Forbidden Operation'));
            echo json_encode($response);
            return;
        }



    } else {
        $response=array('state'=>400,'msg'=>'Error no order list');
        echo json_encode($response);
        return;

    }



}

function delete_invoice_list($data) {
    global $user;
    $sql=sprintf("select `List Store Key`,`List Key` from `List Dimension` where `List Key`=%d",$data['key']);

    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {

        if (in_array($row['List Store Key'],$user->stores)) {
            $sql=sprintf("delete from  `List Invoice Bridge` where `List Key`=%d",$data['key']);
            mysql_query($sql);
            $sql=sprintf("delete from  `List Dimension` where `List Key`=%d",$data['key']);
            mysql_query($sql);
            $response=array('state'=>200,'action'=>'deleted');
            echo json_encode($response);
            return;



        } else {
            $response=array('state'=>400,'msg'=>_('Forbidden Operation'));
            echo json_encode($response);
            return;
        }



    } else {
        $response=array('state'=>400,'msg'=>'Error no invoice list');
        echo json_encode($response);
        return;

    }



}

function delete_dn_list($data) {
    global $user;
    $sql=sprintf("select `List Store Key`,`List Key` from `List Dimension` where `List Key`=%d",$data['key']);

    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {

        if (in_array($row['List Store Key'],$user->stores)) {
            $sql=sprintf("delete from  `List Delivery Note Bridge` where `List Key`=%d",$data['key']);
            mysql_query($sql);
            $sql=sprintf("delete from  `List Dimension` where `List Key`=%d",$data['key']);
            mysql_query($sql);
            $response=array('state'=>200,'action'=>'deleted');
            echo json_encode($response);
            return;



        } else {
            $response=array('state'=>400,'msg'=>_('Forbidden Operation'));
            echo json_encode($response);
            return;
        }



    } else {
        $response=array('state'=>400,'msg'=>'Error no delivery note list');
        echo json_encode($response);
        return;

    }



}

function import_transactions_mals_e($_data) {
    $transactions_raw_data=$_data['values']['data'];
    $lines = preg_split ( '/\n/', $transactions_raw_data );

    $products_data = array ();


    foreach ( $lines as $line ) {

        $line = _trim ( $line );


        if (preg_match('/^.+ \: \d+ \: (\d|\.)+/',$line)) {
            $line_components=preg_split('/\:/',$line);
            if (count($line_components)==3) {
                if (preg_match('/^[a-z0-9\-\&\/]+\s/i',$line_components[0],$match)) {

                    $product_code=_trim($match[0]);
                    $quantity=(float)  $line_components[1];
                    if (array_key_exists($product_code,$products_data))
                        $products_data[$product_code]=$quantity+$products_data[$product_code];
                    else
                        $products_data[$product_code]=$quantity;
                }
            }
        }

    }

   



    $order_key=$_data['order_key'];
    $order=new Order($order_key);


    foreach($products_data as $product_code=>$quantity) {



        if (is_numeric($quantity) and $quantity>=0) {

            $product=new Product('code_store',$product_code,$order->data['Order Store Key']);
            $product->data['Product Code'];
            
            if ($product->id and ($product->data['Product Record Type']=='Normal'  ) ) {

                $data=array(
                          'date'=>date('Y-m-d H:i:s'),
                          'Product Key'=>$product->data['Product Current Key'],
                          'Metadata'=>'',
                          'qty'=>$quantity,
                          'Current Dispatching State'=>'In Process',
                          'Current Payment State'=>'Waiting Payment'
                      );


                $order->skip_update_after_individual_transaction=true;
                $order->add_order_transaction($data);

            }
        }
    }

    $order->update_discounts();
    $order->update_item_totals_from_order_transactions();

    $order->update_shipping();
    $order->update_charges();
    $order->update_item_totals_from_order_transactions();

    $order->update_no_normal_totals();
    $order->update_totals_from_order_transactions();
    $order->update_number_items();



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
                   'data'=>$updated_data,
               );

 echo json_encode($response);



}

function update_order($data){
	$order_key=$data['order_key'];
	$order=new Order($order_key);
        $updated_data=array(

                          'order_total'=>$order->get('Total Amount'),
                          'ordered_products_number'=>$order->get('Number Items'),
                      );
$_SESSION['basket']['total']=$updated_data['order_total'];
$_SESSION['basket']['items']=$updated_data['ordered_products_number'];
//print_r($updated_data);
//print "total: ".$_SESSION['basket']['total'];
//print " qty: ".$_SESSION['basket']['items'];

        $response= array(
                       'state'=>200,
                       'data'=>$updated_data

                   );

    echo json_encode($response);
}

?>