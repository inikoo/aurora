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
case('picking_aid_sheet'):

picking_aid_sheet();
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
case('transactions_to_process'):
    transactions_to_process();
    break;
case('edit_new_order_shipping_type'):
    edit_new_order_shipping_type();
    break;
case('set_order_shipping'):
    set_order_shipping();
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
        $order->update_shipping_type($value);
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

function set_order_shipping() {

    $order_key=$_REQUEST['order_key'];

    $value=$_REQUEST['value'];

    $order=new Order($order_key);
    if ($order->id) {
        $order->update_shipping_amount($value);
        if ($order->updated) {


            $updated_data=array(
                              'order_items_gross'=>$order->get('Items Gross Amount')
                                                  ,'order_items_discount'=>$order->get('Items Discount Amount')
                                                                          ,'order_items_net'=>$order->get('Items Net Amount')
                                                                                             ,'order_net'=>$order->get('Total Net Amount')
                                                                                                          ,'order_tax'=>$order->get('Total Tax Amount')
                                                                                                                       ,'order_charges'=>$order->get('Charges Net Amount')
                                                                                                                                        ,'order_credits'=>$order->get('Net Credited Amount')
                                                                                                                                                         ,'order_shipping'=>$order->get('Shipping Net Amount')
                                                                                                                                                                           ,'order_total'=>$order->get('Total Amount')

                          );



            $response=array('state'=>200,'result'=>'updated','new_value'=>$order->new_value,'data'=>$updated_data,'shipping'=>money($order->new_value));






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

        $gross=$quantity*$product->data['Product Price'];
        $estimated_weight=$quantity*$product->data['Product Gross Weight'];

        $data=array(
                  'Estimated Weight'=>$estimated_weight
                                     ,'date'=>date('Y-m-d H:i:s')
                                             ,'Product Key'=>$product->data['Product Current Key']
                                                            ,'line_number'=>$order->get_next_line_number()
                                                                           ,'gross_amount'=>$gross
                                                                                           ,'discount_amount'=>0
                                                                                                              ,'metadata'=>''
                                                                                                                          ,'qty'=>$quantity
                                                                                                                                 ,'units_per_case'=>$product->data['Product Units Per Case']
                                                                                                                                                   ,'Current Dispatching State'=>'In Process'
                                                                                                                                                                                ,'Current Payment State'=>'Waiting Payment'

              );

        $disconted_products=$order->get_discounted_products();
        $order->skip_update_after_individual_transaction=false;
        $transaction_data=$order->add_order_transaction($data);



        $new_disconted_products=$order->get_discounted_products();
        foreach($new_disconted_products as $key=>$value) {
            $disconted_products[$key]=$value;
        }
        //print_r($disconted_products);

        $adata=array();

        if (count($disconted_products)>0) {

            $product_keys=join(',',$disconted_products);
            $sql=sprintf("select (select `Deal Info` from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info`,P.`Product ID`,`Product XHTML Short Description`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount` from `Order Transaction Fact` OTF  left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P on (PHD.`Product ID`=P.`Product ID`) where OTF.`Order Key`=%d and OTF.`Product Key` in (%s)",$order->id,$product_keys);


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
                          'order_items_gross'=>$order->get('Items Gross Amount')
                                              ,'order_items_discount'=>$order->get('Items Discount Amount')
                                                                      ,'order_items_net'=>$order->get('Items Net Amount')
                                                                                         ,'order_net'=>$order->get('Total Net Amount')
                                                                                                      ,'order_tax'=>$order->get('Total Tax Amount')
                                                                                                                   ,'order_charges'=>$order->get('Charges Net Amount')
                                                                                                                                    ,'order_credits'=>$order->get('Net Credited Amount')
                                                                                                                                                     ,'order_shipping'=>$order->get('Shipping Net Amount')
                                                                                                                                                                       ,'order_total'=>$order->get('Total Amount')

                      );



        $response= array(
                       'state'=>200
                               ,'quantity'=>$transaction_data['qty']
                                           ,'key'=>$_REQUEST['key'],'data'=>$updated_data
                                                                           ,'to_charge'=>$transaction_data['to_charge'],'discount_data'=>$adata
                                                                                   ,'discounts'=>($order->data['Order Items Discount Amount']!=0?true:false)
                                                                                                ,'charges'=>($order->data['Order Charges Net Amount']!=0?true:false)
                   );
    } else
        $response= array('state'=>200,'newvalue'=>$_REQUEST['oldvalue'],'key'=>$_REQUEST['key']);
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
    $conf=$_SESSION['state']['products']['table'];
    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (!is_numeric($start_from))
        $start_from=0;

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];

        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }

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


    if (isset( $_REQUEST['show_all']) and preg_match('/^(yes|no)$/',$_REQUEST['show_all'])  ) {

        if ($_REQUEST['show_all']=='yes')
            $show_all=true;
        else
            $show_all=false;
        $_SESSION['state']['order']['show_all']=$show_all;
    } else
        $show_all=$_SESSION['state']['order']['show_all'];




    //    print_r($_SESSION['state']['order']);


    $_SESSION['state']['products']['table']=array(
                                                'order'=>$order
                                                        ,'order_dir'=>$order_direction
                                                                     ,'nr'=>$number_results
                                                                           ,'sf'=>$start_from
                                                                                 //						 ,'where'=>$where
                                                                                 ,'f_field'=>$f_field
                                                                                            ,'f_value'=>$f_value
                                            );







    if (!$show_all) {
        $start_from=0;
        $number_results=1000;

    }






    if (!$show_all) {

        $table='  `Order Transaction Fact` OTF  left join `Product History Dimension` PHD on (PHD.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P on (PHD.`Product ID`=P.`Product ID`)  ';
        $where=sprintf(' where `Order Quantity`>0 and `Order Key`=%d',$order_id);
        $sql_qty=', `Order Quantity`,`Order Transaction Gross Amount`,`Order Transaction Total Discount Amount`,(select GROUP_CONCAT(`Deal Info`) from `Order Transaction Deal Bridge` OTDB where OTDB.`Order Key`=OTF.`Order Key` and OTDB.`Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`) as `Deal Info`';
    } else {
        $table=' `Product Dimension` P ';
        $where=sprintf('where `Product Store Key`=%d  and `Product Record Type` not in ("Discontinued","In Process","Historic") ',$store_key);
        $sql_qty=sprintf(',IFNULL((select sum(`Order Quantity`) from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=%d),0) as `Order Quantity`, IFNULL((select sum(`Order Transaction Total Discount Amount`) from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=%d),0) as `Order Transaction Total Discount Amount`, IFNULL((select sum(`Order Transaction Gross Amount`) from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=%d),0) as `Order Transaction Gross Amount` ,(  select GROUP_CONCAT(`Deal Info`) from  `Order Transaction Deal Bridge` OTDB  where OTDB.`Product Key`=`Product Current Key` and OTDB.`Order Key`=%d )  as `Deal Info` ',$order_id,$order_id,$order_id,$order_id);


    }




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
    //     print $sql;
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
        $order='`Product Web State`';
    }



    $sql="select  `Product Availability`,`Product Record Type`,P.`Product ID`,`Product Code`,`Product XHTML Short Description`,`Product Price`,`Product Units Per Case`,`Product Record Type`,`Product Web State`,`Product Family Name`,`Product Main Department Name`,`Product Tariff Code`,`Product XHTML Parts`,`Product GMROI`,`Product XHTML Parts`,`Product XHTML Supplied By`,`Product Stock Value`  $sql_qty from $table   $where $wheref order by $order $order_direction limit $start_from,$number_results    ";

// print $sql;

    $res = mysql_query($sql);

    $adata=array();

    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        if (is_numeric($row['Product Availability']))
            $stock=number($row['Product Availability']);
        else
            $stock='?';
        $type=$row['Product Record Type'];
        if ($row['Product Record Type']=='In Process')
            $type.='<span style="color:red">*</span>';
        switch ($row['Product Web State']) {
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
            $web_state=$row['Product Web State'];
        }


        $deal_info='';
        if ($row['Deal Info']!='') {
            $deal_info=' <span class="deal_info">'.$row['Deal Info'].'</span>';
        }

        $code=sprintf('<a href="product.php?pid=%d">%s</a>',$row['Product ID'],$row['Product Code']);
        $adata[]=array(
                     'pid'=>$row['Product ID'],
                     'code'=>$code,
                     'description'=>$row['Product XHTML Short Description'].$deal_info,
                     'shortname'=>number($row['Product Units Per Case']).'x @'.money($row['Product Price']/$row['Product Units Per Case']).' '._('ea'),
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
                     'to_charge'=>money($row['Order Transaction Gross Amount']-$row['Order Transaction Total Discount Amount'])

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




    $_SESSION['state']['orders']['ready_to_pick_dn']=array(
                'order'=>$order,
                'order_dir'=>$order_direction,
                'nr'=>$number_results,
                'sf'=>$start_from,
                'where'=>$where,
                'f_field'=>$f_field,
                'f_value'=>$f_value,


            );










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
        $rtext_rpp=sprintf("Showing all delivery notes");

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


    if ($order=='id')
        $order='`Delivery Note File As`';
    else if ($order=='customer')
        $order='`Delivery Note Customer Name`';
    else if ($order=='status')
        $order='`Delivery Note State`';
    else
        $order='`Delivery Note Date Created`';



    $sql="select  `Delivery Note Faction Picked`,`Delivery Note Assigned Picker Key`,`Delivery Note Assigned Picker Alias`, `Delivery Note Date Created`,`Delivery Note Key`,`Delivery Note Customer Name`,`Delivery Note Estimated Weight`,`Delivery Note Distinct Items`,`Delivery Note State`,`Delivery Note ID`,`Delivery Note Estimated Weight`,`Delivery Note Distinct Items`  from `Delivery Note Dimension`   $where $wheref  order by $order $order_direction limit $start_from,$number_results ";
//print $sql;
    global $myconf;

    $data=array();

    $res = mysql_query($sql);
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
        }
        elseif($row['Delivery Note State']=='Picker Assigned') {
            $status='<div id="dn_state'.$row['Delivery Note Key'].'">'._('Picker Assigned').'</div>';
            $operations.='<span style="cursor:pointer"  onClick="pick_it(this,'.$row['Delivery Note Key'].','.$row['Delivery Note Assigned Picker Key'].')"> <b>'.$row['Delivery Note Assigned Picker Alias'].'</b> '._('pick order')."</span>";
            $operations.=' <img src="art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_picker(this,'.$row['Delivery Note Key'].')">';
        }elseif($row['Delivery Note State']=='Packer Assigned') {
            $status='<div id="dn_state'.$row['Delivery Note Key'].'">'._('(Picked) Packer Assigned').'</div>';
            $operations.='<span style="cursor:pointer"  onClick="pack_it(this,'.$row['Delivery Note Key'].','.$row['Delivery Note Assigned Packer Key'].')"> <b>'.$row['Delivery Note Assigned Packer Alias'].'</b> '._('pack order')."</span>";
            $operations.=' <img src="art/icons/edit.gif" alt="'._('edit').'" style="cursor:pointer"  onClick="assign_packer(this,'.$row['Delivery Note Key'].')">';
        }elseif($row['Delivery Note State']=='Picking') {
            $status='<div id="dn_state'.$row['Delivery Note Key'].'">'._('Picking').'('.percentage($row['Delivery Note Faction Picked'],1,0).') <b>'.$row['Delivery Note Assigned Picker Alias'].'</b> </div>';
            $operations.='<span style="cursor:pointer"  onClick="assign_packer(this,'.$row['Delivery Note Key'].')">'._('Assign Packer')."</span>";;
            $operations.=' | <span style="cursor:pointer"  onClick="pack_it(this,'.$row['Delivery Note Key'].')">'._('Start packing')."</span>";
        }elseif($row['Delivery Note State']=='Picked') {
            $status='<div id="dn_state'.$row['Delivery Note Key'].'">'._('Picked').'</div>';
            $operations.='<span style="cursor:pointer"  onClick="assign_packer(this,'.$row['Delivery Note Key'].')">'._('Assign Packer')."</span>";;
            $operations.=' | <span style="cursor:pointer"  onClick="pack_it(this,'.$row['Delivery Note Key'].')">'._('Start packing')."</span>";
        }
        else {
            $operations.='';
            $status=$row['Delivery Note State'];
        }
        $operations.='</div>';

        //$packer='';

        $data[]=array(
                    'id'=>$row['Delivery Note Key']
                         ,'public_id'=>sprintf("<a href='dn.php?id=%d'>%s</a>",$row['Delivery Note Key'],$row['Delivery Note ID'])
                                      ,'customer'=>$row['Delivery Note Customer Name']
                                                  // ,'wating_lap'=>$lap
                                                  ,'weight'=>$w
                                                            ,'picks'=>$picks
                                                                     ,'date'=>$row['Delivery Note Date Created']
                                                                             ,'operations'=>$operations
                                                                                           ,'status'=>$status
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

function picking_aid_sheet(){
 if(isset( $_REQUEST['id']) and is_numeric( $_REQUEST['id']))
     $order_id=$_REQUEST['id'];
   else
     $order_id=$_SESSION['state']['dn']['id'];
   
//print_r($_SESSION['state']['dn']);


   $where=' where `Delivery Note Key`='.$order_id;

   $total_charged=0;
   $total_discounts=0;
   $total_picks=0;

   $data=array();
   $sql="select `Part XHTML Currently Used In`,Part.`Part SKU`,`Part XHTML Description`,sum(`Required`) as qty ,`Part XHTML Picking Location` from `Inventory Transaction Fact` ITF  left join  `Part Dimension` Part on  (Part.`Part SKU`=ITF.`Part SKU`)  $where  group by Part.`Part SKU` ";
  // print $sql;
   $result=mysql_query($sql);
   while($row=mysql_fetch_array($result, MYSQL_ASSOC)){
  
     $sku=sprintf('<a href="part.php?sku=%d">SKU%05d</a>',$row['Part SKU'],$row['Part SKU']);
     $data[]=array(

		   'sku'=>$sku
		   ,'description'=>$row['Part XHTML Description']
		   ,'used_in'=>$row['Part XHTML Currently Used In']
		   ,'quantity'=>number($row['qty'])
		   ,'location'=>$row['Part XHTML Picking Location']
		  
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
