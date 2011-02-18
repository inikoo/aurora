<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW
require_once 'common.php';
//require_once '_order.php';

//require_once '_contact.php';
require_once 'class.Customer.php';
require_once 'class.Timer.php';




if (!isset($_REQUEST['tipo']))  {
    $response=array('state'=>405,'resp'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('used_email'):
    used_email();

    break;
case('find_Company'):
case('find_company'):
    require_once 'ar_edit_common.php';
    $data=prepare_values($_REQUEST,array(
                             'scope'=>array('type'=>'json array')

                                     ,'values'=>array('type'=>'json array')
                         ));
    find_company($data);
    break;
case('find_Contact'):

case('find_contact'):
    require_once 'ar_edit_common.php';


    $data=prepare_values($_REQUEST,array(
                             'scope'=>array('type'=>'json array')
                                     ,'values'=>array('type'=>'json array')
                         ));
    find_contact($data);
    break;

case('is_company_department_code'):
    is_company_department_code();
    break;
case('is_company_department_name'):
    is_company_department_name();
    break;
case('is_department_code'):
    is_department_code();
    break;
case('is_department_name'):
    is_department_name();
    break;
case('is_position_code'):
    is_position_code();
    break;
case('is_position_name'):
    is_position_name();
    break;

case('find_company_area'):
    require_once 'ar_edit_common.php';
    $data=prepare_values($_REQUEST,array(
                             'parent_key'=>array('type'=>'number')
                                          ,'query'=>array('type'=>'string')
                         ));
    find_company_area($data);
    break;
case('find_company_department'):
    require_once 'ar_edit_common.php';
    $data=prepare_values($_REQUEST,array(
                             'parent_key'=>array('type'=>'number')
                                          ,'grandparent_key'=>array('type'=>'number')
                                                             ,'query'=>array('type'=>'string')
                         ));
    find_company_department($data);
    break;
case('contacts'):
    list_contacts();
    break;
case('companies'):
    list_companies();
    break;
case('staff'):
    list_staff();
    break;
case('customers_advanced_search'):
    customer_advanced_search();
    break;
case('customers'):
    if (!$user->can_view('customers'))
        exit();
    list_customers();
    break;
case('company_areas'):
    list_company_areas();
    break;
case('assets_dispatched_to_customer'):

    list_assets_dispatched_to_customer();
    break;
case('assets_in_process_customer'):

    list_assets_in_process_customer();
    break;
case('company_departments'):

    list_company_departments();
    break;
case('company_positions'):
case('positions'):

    list_company_positions();
    break;
case('plot_order_interval'):

    $now="'2008-04-18 08:30:00'";

    $sql="select count(*) as total from customer where order_interval>0    and  (order_interval*3)>DATEDIFF($now,last_order)   ";

    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total_sample=$row['total'];
    }
    $sql="select  CEIL(order_interval) as x ,count(*) as y from customer where order_interval>0 and order_interval<300    and  (order_interval*3)>DATEDIFF($now,last_order)     group by CEIL(order_interval)";
    //   print $sql;
    $data=array();

    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $data[]=array(
                    'x'=>$row['x'],
                    'y'=>$row['y']/$total_sample
                );
    }


    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                     )
                   );

    echo json_encode($response);
    break;
case('customer_orders'):
    if (!$user->can_view('orders'))
        exit();
    list_customer_orders();


    break;
case('customer_categories'):
    list_customer_categories();
    break;



default:
    $response=array('state'=>404,'resp'=>_('Operation not found'));
    echo json_encode($response);

}
function list_customer_orders() {
    $conf=$_SESSION['state']['customer']['assets'];
    if (isset( $_REQUEST['id']))
        $customer_id=$_REQUEST['id'];
    else
        $customer_id=$_SESSION['state']['customer']['id'];
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

    if (isset( $_REQUEST['from']))
        $from=$_REQUEST['from'];
    else
        $from=$conf['from'];
    if (isset( $_REQUEST['to']))
        $to=$_REQUEST['to'];
    else
        $to=$conf['to'];



    if (isset( $_REQUEST['type']))
        $type=$_REQUEST['type'];
    else
        $type=$conf['type'];

    if (isset( $_REQUEST['tid']))
        $tableid=$_REQUEST['tid'];
    else
        $tableid=0;


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_SESSION['state']['customer']['id']=$customer_id;
    $_SESSION['state']['customer']['assets']=array('type'=>$type,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'f_field'=>$f_field,'f_value'=>$f_value);
    $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
    if ($date_interval['error']) {
        $date_interval=prepare_mysql_dates($_SESSION['state']['customer']['table']['from'],$_SESSION['state']['customer']['table']['to']);
    } else {
        $_SESSION['state']['customer']['assets']['from']=$date_interval['from'];
        $_SESSION['state']['customer']['assets']['to']=$date_interval['to'];
    }

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



    // $where=sprintf("    where `Current Dispatching State` not in ('Cancelled','Dispatched',) and `Customer Key`=%d  ",$customer_id);
    $where=sprintf(" where true and `Order Customer Key`=%d  ",$customer_id);

//print "$f_field $f_value  " ;

    $wheref='';
    if ($f_field=='description' and $f_value!='')
        $wheref.=" and ( `Deal Terms Description` like '".addslashes($f_value)."%' or `Deal Allowance Description` like '".addslashes($f_value)."%'  )   ";
    elseif($f_field=='code' and $f_value!='') {
        switch ($type) {
        case('Family'):
            $wheref.=" and  `Product Family Code` like '".addslashes($f_value)."%'";
            break;
        case('Department'):
            $wheref.=" and  `Product Department Code` like '".addslashes($f_value)."%'";

            break;
        default:
            $wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";

        }



    }

    $sql=sprintf("select count(*) as total from `Order Dimension` $where" );
//print $sql;
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
        $sql="select count(*) as total from `Order Dimension` $where  $wheref ";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }

//print $sql;
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
    elseif($filtered>0) {
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

        $order='`Order Public ID`';


    }
    elseif($order=='last_update') {
        $order='`Order Last Updated Date`';
    }
    elseif($order=='current_state') {
        $order='`Order Current XHTML State`';
    }
    elseif($order=='order_date') {
        $order='`Order Date`';
    }
    elseif($order=='total_amount') {
        $order='`Order Balance Total Amount`';
    }






    $adata=array();
//  $sql=sprintf("select  count(distinct `Order Key`) as `Number of Orders`,sum(`Order Quantity`) as `Order Quantity`,`Current Dispatching State` ,`Product Code`,`Product Family Code`,PD.`Product Family Key`,PD.`Product Main Department Key`,D.`Product Department Code` ,`Product Family Name` , `Product XHTML Short Description` ,`Product Department Name` from `Order Transaction Fact` OTF left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` PD on (PD.`Product ID`=PHD.`Product ID`) left join `Product Department Dimension` D on (D.`Product Department Key`=`Product Main Department Key`)   $where " ,$customer_id);

// $sql.=" $wheref ";
// $sql.=sprintf("  group by `%s`   order by $order $order_direction limit $start_from,$number_results   ",$group_by);
    $sql="select `Order Balance Total Amount`,`Order Type`,`Order Currency Exchange`,`Order Currency`,`Order Key`,`Order Public ID`,`Order Customer Key`,`Order Customer Name`,`Order Last Updated Date`,`Order Date`,`Order Total Amount` ,`Order Current XHTML State` from `Order Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results ";

    $res = mysql_query($sql);

    $total=mysql_num_rows($res);
//print $sql;
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        if ($row['Order Balance Total Amount']!=$row['Order Total Amount']) {
            $mark='<span style="color:red">*</span>';
        } else {
            $mark='<span style="visibility:hidden">*</span>';
        }
        $adata[]=array(
                     'subject'=>$row['Order Public ID'],
                     'last_update'=>strftime("%a %e %b %Y %T", strtotime($row['Order Last Updated Date'].' UTC')) ,
                     'current_state'=>$row['Order Current XHTML State'],
                     'order_date'=>strftime("%a %e %b %Y", strtotime($row['Order Date'].' UTC')) ,
                     'total_amount'=>money($row['Order Balance Total Amount'],$row['Order Currency']).$mark,


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




function list_assets_dispatched_to_customer() {
    $conf=$_SESSION['state']['customer']['assets'];
    if (isset( $_REQUEST['id']))
        $customer_id=$_REQUEST['id'];
    else
        $customer_id=$_SESSION['state']['customer']['id'];
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

    if (isset( $_REQUEST['from']))
        $from=$_REQUEST['from'];
    else
        $from=$conf['from'];
    if (isset( $_REQUEST['to']))
        $to=$_REQUEST['to'];
    else
        $to=$conf['to'];



    if (isset( $_REQUEST['type']))
        $type=$_REQUEST['type'];
    else
        $type=$conf['type'];

    if (isset( $_REQUEST['tid']))
        $tableid=$_REQUEST['tid'];
    else
        $tableid=0;


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_SESSION['state']['customer']['id']=$customer_id;
    $_SESSION['state']['customer']['assets']=array('type'=>$type,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'f_field'=>$f_field,'f_value'=>$f_value);
    $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
    if ($date_interval['error']) {
        $date_interval=prepare_mysql_dates($_SESSION['state']['customer']['table']['from'],$_SESSION['state']['customer']['table']['to']);
    } else {
        $_SESSION['state']['customer']['assets']['from']=$date_interval['from'];
        $_SESSION['state']['customer']['assets']['to']=$date_interval['to'];
    }

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
        $wheref.=" and ( `Deal Terms Description` like '".addslashes($f_value)."%' or `Deal Allowance Description` like '".addslashes($f_value)."%'  )   ";
    elseif($f_field=='code' and $f_value!='') {
        switch ($type) {
        case('Family'):
            $wheref.=" and  `Product Family Code` like '".addslashes($f_value)."%'";
            break;
        case('Department'):
            $wheref.=" and  `Product Department Code` like '".addslashes($f_value)."%'";

            break;
        default:
            $wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";

        }



    }

    $sql=sprintf("select count(distinct `%s`)  as total  from `Order Transaction Fact` OTF left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` PD on (PD.`Product ID`=PHD.`Product ID`)  $where  ",$group_by);
//print $sql;
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
        $sql="select count(distinct `$group_by`)  as total   from `Order Transaction Fact` OTF left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` PD on (PD.`Product ID`=PHD.`Product ID`)  $where  $wheref ";
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
    elseif($filtered>0) {
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
    elseif($order=='description') {
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
    elseif($order=='dispatched') {
        $order='`Delivery Note Quantity`';
    }
    elseif($order=='orders') {
        $order='`Number of Orders`';
    }
    elseif($order=='ordered') {
        $order='`Order Quantity`';
    }

    $adata=array();
    $sql=sprintf("select  count(distinct `Order Key`) as `Number of Orders`,sum(`Order Quantity`) as `Order Quantity`,sum(`Delivery Note Quantity`) as `Delivery Note Quantity` ,`Product Code`,`Product Family Code`,PD.`Product Family Key`,PD.`Product Main Department Key`,D.`Product Department Code` ,`Product Family Name` , `Product XHTML Short Description` ,`Product Department Name` from `Order Transaction Fact` OTF left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` PD on (PD.`Product ID`=PHD.`Product ID`) left join `Product Department Dimension` D on (D.`Product Department Key`=`Product Main Department Key`)   $where " ,$customer_id);
    $sql.=" $wheref ";
    $sql.=sprintf("  group by `%s`   order by $order $order_direction limit $start_from,$number_results   ",$group_by);

    $res = mysql_query($sql);

    $total=mysql_num_rows($res);

    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        $adata[]=array(
                     'subject'=>$row[$subject],
                     'description'=>$row[$description],

                     'ordered'=>number($row['Order Quantity']),
                     'dispatched'=>number($row['Delivery Note Quantity']),
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

function list_assets_in_process_customer() {
    $conf=$_SESSION['state']['customer']['assets'];
    if (isset( $_REQUEST['id']))
        $customer_id=$_REQUEST['id'];
    else
        $customer_id=$_SESSION['state']['customer']['id'];
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

    if (isset( $_REQUEST['from']))
        $from=$_REQUEST['from'];
    else
        $from=$conf['from'];
    if (isset( $_REQUEST['to']))
        $to=$_REQUEST['to'];
    else
        $to=$conf['to'];



    if (isset( $_REQUEST['type']))
        $type=$_REQUEST['type'];
    else
        $type=$conf['type'];

    if (isset( $_REQUEST['tid']))
        $tableid=$_REQUEST['tid'];
    else
        $tableid=0;


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_SESSION['state']['customer']['id']=$customer_id;
    $_SESSION['state']['customer']['assets']=array('type'=>$type,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'f_field'=>$f_field,'f_value'=>$f_value);
    $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
    if ($date_interval['error']) {
        $date_interval=prepare_mysql_dates($_SESSION['state']['customer']['table']['from'],$_SESSION['state']['customer']['table']['to']);
    } else {
        $_SESSION['state']['customer']['assets']['from']=$date_interval['from'];
        $_SESSION['state']['customer']['assets']['to']=$date_interval['to'];
    }

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



    $where=sprintf("    where `Current Dispatching State` not in ('Cancelled','Dispatched',) and `Customer Key`=%d  ",$customer_id);


//print "$f_field $f_value  " ;

    $wheref='';
    if ($f_field=='description' and $f_value!='')
        $wheref.=" and ( `Deal Terms Description` like '".addslashes($f_value)."%' or `Deal Allowance Description` like '".addslashes($f_value)."%'  )   ";
    elseif($f_field=='code' and $f_value!='') {
        switch ($type) {
        case('Family'):
            $wheref.=" and  `Product Family Code` like '".addslashes($f_value)."%'";
            break;
        case('Department'):
            $wheref.=" and  `Product Department Code` like '".addslashes($f_value)."%'";

            break;
        default:
            $wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";

        }



    }

    $sql=sprintf("select count(distinct `%s`)  as total  from `Order Transaction Fact` OTF left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` PD on (PD.`Product ID`=PHD.`Product ID`)  $where  ",$group_by);
//print $sql;
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
        $sql="select count(distinct `$group_by`)  as total   from `Order Transaction Fact` OTF left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` PD on (PD.`Product ID`=PHD.`Product ID`)  $where  $wheref ";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }

//print $sql;
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
    elseif($filtered>0) {
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
    elseif($order=='description') {
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

    $adata=array();
    $sql=sprintf("select  count(distinct `Order Key`) as `Number of Orders`,sum(`Order Quantity`) as `Order Quantity`,`Current Dispatching State` ,`Product Code`,`Product Family Code`,PD.`Product Family Key`,PD.`Product Main Department Key`,D.`Product Department Code` ,`Product Family Name` , `Product XHTML Short Description` ,`Product Department Name` from `Order Transaction Fact` OTF left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` PD on (PD.`Product ID`=PHD.`Product ID`) left join `Product Department Dimension` D on (D.`Product Department Key`=`Product Main Department Key`)   $where " ,$customer_id);

    $sql.=" $wheref ";
    $sql.=sprintf("  group by `%s`   order by $order $order_direction limit $start_from,$number_results   ",$group_by);

    $res = mysql_query($sql);

    $total=mysql_num_rows($res);
//print $sql;
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        $adata[]=array(
                     'subject'=>$row[$subject],
                     'description'=>$row[$description],'product_code'=>$row['Product Code'],
                     'ordered'=>number($row['Order Quantity']),
                     'dispatched'=>$row['Current Dispatching State'],
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



function list_companies() {
    $conf=$_SESSION['state']['companies']['table'];
    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$_SESSION['state']['companies']['view'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (!is_numeric($start_from))
        $start_from=0;

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
    } else
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



    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


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




    if (isset( $_REQUEST['parent']))
        $parent=$_REQUEST['parent'];
    else
        $parent=$conf['parent'];

    if (isset( $_REQUEST['mode']))
        $mode=$_REQUEST['mode'];
    else
        $mode=$conf['mode'];

    if (isset( $_REQUEST['restrictions']))
        $restrictions=$_REQUEST['restrictions'];
    else
        $restrictions=$conf['restrictions'];




    $_SESSION['state']['companies']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
            ,'mode'=>$mode,'restrictions'=>'','parent'=>$parent
                                                  );




    $group='';


    switch ($restrictions) {
    case('forsale'):
        $where.=sprintf(" and `Product Sales State`='For Sale'  ");
        break;
    case('editable'):
        $where.=sprintf(" and `Product Sales State` in ('For Sale','In Process','Unknown')  ");
        break;
    case('notforsale'):
        $where.=sprintf(" and `Product Sales State` in ('Not For Sale')  ");
        break;
    case('discontinued'):
        $where.=sprintf(" and `Product Sales State` in ('Discontinued')  ");
        break;
    case('none'):

        break;
    }


    $filter_msg='';

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

    //  if(!is_numeric($start_from))
    //        $start_from=0;
    //      if(!is_numeric($number_results))
    //        $number_results=25;


    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';
    $wheref='';
    if ($f_field=='company name' and $f_value!='')
        $wheref.=" and  `Company Name` like '%".addslashes($f_value)."%'";
    elseif($f_field=='email' and $f_value!='')
    $wheref.=" and  `Company Main Plain Email` like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Company Dimension`  $where $wheref   ";
//print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Product Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }
    mysql_free_result($res);

    $rtext=$total_records." ".ngettext('contact','companies',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with name like')." <b>".$f_value."*</b>";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with email like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_order=$order;
    $_order_dir=$order_dir;

    if ($order=='name')
        $order='`Company File As`';
    elseif($order=='location')
    $order='`Company Main Location`';
    elseif($order=='email')
    $order='`Company Main Plain Email`';
    elseif($order=='telephone')
    $order='`Company Main Plain Telephone`';
    elseif($order=='mobile')
    $order='`Company Main Plain Mobile`';
    elseif($order=='fax')
    $order='`Company Main Plain FAX`';
    elseif($order=='town')
    $order='`Address Town`';
    elseif($order=='contact')
    $order='`Company Main Contact Name`';
    elseif($order=='address')
    $order='`Company Main Plain Address`';
    elseif($order=='postcode')
    $order='`Address Postal Code`';
    elseif($order=='region')
    $order='`Address Country First Division`';
    elseif($order=='country')
    $order='`Address Country Code`';


    $sql="select  * from `Company Dimension` P left join `Address Dimension` on (`Company Main Address Key`=`Address Key`)  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);
    $adata=array();

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        $id=sprintf('<a href="company.php?id=%d">%04d</a>',$row['Company Key'],$row['Company Key']);
        if ($row['Company Main Contact Key'])
            $contact=sprintf('<a href="company.php?id=%d">%s</a>',$row['Company Main Contact Key'],$row['Company Main Contact Name']);
        else
            $contact='';
        $adata[]=array(

                     'company_key'=>$id
                                   ,'id'=>$row['Company Key']
                                         ,'name'=>$row['Company Name']
                                                 ,'location'=>$row['Company Main Location']
                                                             ,'email'=>$row['Company Main XHTML Email']
                                                                      ,'telephone'=>$row['Company Main XHTML Telephone']
                                                                                   ,'fax'=>$row['Company Main XHTML FAX']
                                                                                          ,'contact'=>$contact
                                                                                                     ,'town'=>$row['Address Town']
                                                                                                             ,'postcode'=>$row['Address Postal Code']
                                                                                                                         ,'region'=>$row['Address Country First Division']
                                                                                                                                   ,'country'=>$row['Address Country Code']
                                                                                                                                              ,'address'=>$row['Company Main XHTML Address']
                 );
    }
    mysql_free_result($res);


    // $total_records=ceil($total_records/$number_results)+$total_records;

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
function list_company_history() {
    $conf=$_SESSION['state']['company']['table'];

    if (isset( $_REQUEST['id']))
        $company_id=$_REQUEST['id'];
    else
        $company_id=$_SESSION['state']['company']['id'];


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

    if (isset( $_REQUEST['details']))
        $details=$_REQUEST['details'];
    else
        $details=$conf['details'];


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

    if (isset( $_REQUEST['from']))
        $from=$_REQUEST['from'];
    else
        $from=$conf['from'];
    if (isset( $_REQUEST['to']))
        $to=$_REQUEST['to'];
    else
        $to=$conf['to'];

    $elements=$conf['elements'];
    if (isset( $_REQUEST['element_orden']))
        $elements['orden']=$_REQUEST['e_orden'];
    if (isset( $_REQUEST['element_h_cust']))
        $elements['h_cust']=$_REQUEST['e_orden'];
    if (isset( $_REQUEST['element_h_cont']))
        $elements['h_cont']=$_REQUEST['e_orden'];
    if (isset( $_REQUEST['element_note']))
        $elements['note']=$_REQUEST['e_orden'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;




    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_SESSION['state']['company']['id']=$company_id;
    $_SESSION['state']['company']['table']=array('details'=>$details,'elements'=>$elements,'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
    $date_interval=prepare_mysql_dates($from,$to,'date_index','only_dates');
    if ($date_interval['error']) {
        $date_interval=prepare_mysql_dates($_SESSION['state']['company']['table']['from'],$_SESSION['state']['company']['table']['to']);
    } else {
        $_SESSION['state']['company']['table']['from']=$date_interval['from'];
        $_SESSION['state']['company']['table']['to']=$date_interval['to'];
    }

    $where.=sprintf(' and (  (`Subject`="Company" and  `Subject Key`=%d) or (`Direct Object`="Company" and  `Direct Object key`=%d ) or (`Indirect Object`="Company" and  `Indirect Object key`=%d )         ) ',$company_id,$company_id,$company_id);
//   if(!$details)
//    $where.=" and display!='details'";
//  foreach($elements as $element=>$value){
//    if(!$value ){
//      $where.=sprintf(" and objeto!=%s ",prepare_mysql($element));
//    }
//  }

    $where.=$date_interval['mysql'];

    $wheref='';



    if ( $f_field=='notes' and $f_value!='' )
        $wheref.=" and   note like '%".addslashes($f_value)."%'   ";
    if ($f_field=='upto' and is_numeric($f_value) )
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date))<=".$f_value."    ";
    else if ($f_field=='older' and is_numeric($f_value))
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date))>=".$f_value."    ";
    elseif($f_field=='author' and $f_value!='') {
        if (is_numeric($f_value))
            $wheref.=" and   staff_id=$f_value   ";
        else {
            $wheref.=" and  handle like='".addslashes($f_value)."%'   ";
        }
    }











    $sql="select count(*) as total from  `History Dimension`   $where $wheref ";
// print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($where=='') {
        $filtered=0;
        $filter_total=0;
        $total_records=$total;
    } else {

        $sql="select count(*) as total from  `History Dimension`  $where";
        // print $sql;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $filtered=$row['total']-$total;
            $total_records=$row['total'];
        }

    }

    mysql_free_result($result);

    $rtext=$total_records." ".ngettext('record','records',$total_records);

    if ($total==0)
        $rtext_rpp='';
    elseif($total_records>$number_results)
    $rtext_rpp=sprintf('(%d%s)',$number_results,_('rpp'));
    else
        $rtext_rpp=_('Showing all');


//   print "$f_value $filtered  $total_records  $filter_total";
    $filter_msg='';
    if ($filtered>0) {
        switch ($f_field) {
        case('notes'):
            if ($total==0 and $filtered>0)
                $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record matching")." <b>$f_value</b> ";
            elseif($filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext($total,'record matching','records matching')." <b>$f_value</b>";
            break;
        case('older'):
            if ($total==0 and $filtered>0)
                $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record older than")." <b>$f_value</b> ".ngettext($f_value,'day','days');
            elseif($filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext($total,'record older than','records older than')." <b>$f_value</b> ".ngettext($f_value,'day','days');
            break;
        case('upto'):
            if ($total==0 and $filtered>0)
                $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any record in the last")." <b>$f_value</b> ".ngettext($f_value,'day','days');
            elseif($filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total ".ngettext($total,'record in the last','records inthe last')." <b>$f_value</b> ".ngettext($f_value,'day','days')."<span onclick=\"remove_filter($tableid)\" id='remove_filter$tableid' class='remove_filter'>"._('Show All')."</span>";
            break;


        }
    }



    $_order=$order;
    $_dir=$order_direction;
    if ($order=='date')
        $order='History Date';
    if ($order=='note')
        $order='History Abstract';
    if ($order=='objeto')
        $order='Direct Object';

    $sql="select * from `History Dimension`   $where $wheref  order by `$order` $order_direction limit $start_from,$number_results ";
    //  print $sql;
    $result=mysql_query($sql);
    $data=array();
    while ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


        $data[]=array(
                    'id'=>$row['History Key'],
                    'date'=>strftime("%a %e %b %Y", strtotime($row['History Date'])),
                    'time'=>strftime("%H:%M", strtotime($row['History Date'])),
                    'objeto'=>$row['Direct Object'],
                    'note'=>$row['History Abstract'],
                    'handle'=>$row['Author Name']
                );
    }
    mysql_free_result($result);
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                      'sort_key'=>$_order,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      //	 'records_returned'=>$start_from+$res->numRows(),
                                      'records_perpage'=>$number_results,
                                      'rtext'=>$rtext,
                                      'rtext_rpp'=>$rtext_rpp,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
}
function list_contacts() {
    $conf=$_SESSION['state']['contacts']['table'];
    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$_SESSION['state']['contacts']['view'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (!is_numeric($start_from))
        $start_from=0;

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
    } else
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



    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


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




    if (isset( $_REQUEST['parent']))
        $parent=$_REQUEST['parent'];
    else
        $parent=$conf['parent'];

    if (isset( $_REQUEST['mode']))
        $mode=$_REQUEST['mode'];
    else
        $mode=$conf['mode'];

    if (isset( $_REQUEST['restrictions']))
        $restrictions=$_REQUEST['restrictions'];
    else
        $restrictions=$conf['restrictions'];




    $_SESSION['state']['contacts']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
                                            ,'mode'=>$mode,'restrictions'=>'','parent'=>$parent
                                                 );






    switch ($parent) {
    case('company'):
        $where=sprintf(' where `Contact Company Key`=%d',$_SESSION['state']['company']['id']);
        break;
    case('supplier'):
        $where=sprintf(' left join `Contact Bridge` B on (P.`Contact Key`=B.`Contact Key`) where `Subject Type`="Supplier" and `Subject Key`=%d',$_SESSION['state']['supplier']['id']);
        break;
    case('customer'):
        $where=sprintf(' left join `Contact Bridge` B on (P.`Contact Key`=B.`Contact Key`) where `Subject Type`="Customer" and `Subject Key`=%d',$_SESSION['state']['customer']['id']);
        break;
    default:
        $where=sprintf(" where `Contact Fuzzy`='No' ");

    }
    $group='';
    /*    switch($mode){ */
    /*    case('same_code'): */
    /*      $where.=sprintf(" and `Product Same Code Most Recent`='Yes' "); */
    /*      break; */
    /*    case('same_id'): */
    /*      $where.=sprintf(" and `Product Same ID Most Recent`='Yes' "); */

    /*      break; */
    /*    } */

    switch ($restrictions) {
    case('forsale'):
        $where.=sprintf(" and `Product Sales State`='For Sale'  ");
        break;
    case('editable'):
        $where.=sprintf(" and `Product Sales State` in ('For Sale','In Process','Unknown')  ");
        break;
    case('notforsale'):
        $where.=sprintf(" and `Product Sales State` in ('Not For Sale')  ");
        break;
    case('discontinued'):
        $where.=sprintf(" and `Product Sales State` in ('Discontinued')  ");
        break;
    case('none'):

        break;
    }


    $filter_msg='';

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

    //  if(!is_numeric($start_from))
    //        $start_from=0;
    //      if(!is_numeric($number_results))
    //        $number_results=25;


    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Contact Name` like '%".addslashes($f_value)."%'";
    elseif($f_field=='email' and $f_value!='')
    $wheref.=" and  `Contact Main Plain Email` like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Contact Dimension`  $where $wheref   ";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Contact Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }

    mysql_fetch_array($res);
    $rtext=$total_records." ".ngettext('contact','contacts',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('contacts with name like')." <b>".$f_value."*</b>";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('contacts with email like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_order=$order;
    $_order_dir=$order_dir;
    $order='`Contact File As`';
    if ($_order=='name')
        $order='`Contact File As`';
    elseif($_order=='location')
    $order='`Contact Main Location`';
    elseif($_order=='email')
    $order='`Contact Main Plain Email`';
    elseif($_order=='telephone')
    $order='`Contact Main Plain Telephone`';
    elseif($_order=='mobile')
    $order='`Contact Main Plain Mobile`';
    elseif($_order=='fax')
    $order='`Contact Main Plain FAX`';
    elseif($_order=='town')
    $order='`Address Town`';
    elseif($_order=='company')
    $order='`Contact Company Name`';
    elseif($_order=='address')
    $order='`Contact Main Plain Address`';
    elseif($_order=='postcode')
    $order='`Address Postal Code`';
    elseif($_order=='region')
    $order='`Address Country First Division`';
    elseif($_order=='country')
    $order='`Address Country Code`';
    elseif($_order=='id')
    $order='`Contact Key`';


    $sql="select  * from `Contact Dimension` P left join `Address Dimension` on (`Contact Main Address Key`=`Address Key`)  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);
    $adata=array();

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        $id=sprintf('<a href="contact.php?id=%d">%04d</a>',$row['Contact Key'],$row['Contact Key']);
        if ($row['Contact Company Key'])
            $company=sprintf('<a href="company.php?id=%d">%s</a>',$row['Contact Company Key'],$row['Contact Company Name']);
        else
            $company='';
        $adata[]=array(

                     'id'=>$id
                          ,'name'=>$row['Contact Name']
                                  ,'location'=>$row['Contact Main Location']
                                              ,'email'=>$row['Contact Main XHTML Email']
                                                       ,'telephone'=>$row['Contact Main XHTML Telephone']
                                                                    ,'mobile'=>$row['Contact Main XHTML Mobile']
                                                                              ,'fax'=>$row['Contact Main XHTML FAX']
                                                                                     ,'company'=>$company
                                                                                                ,'town'=>$row['Address Town']
                                                                                                        ,'postcode'=>$row['Address Postal Code']
                                                                                                                    ,'region'=>$row['Address Country First Division']
                                                                                                                              ,'country'=>$row['Address Country Code']
                                                                                                                                         ,'address'=>$row['Contact Main XHTML Address']
                 );
    }
    mysql_fetch_array($res);


    // $total_records=ceil($total_records/$number_results)+$total_records;

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
function list_customers() {


    global $myconf;

    $conf=$_SESSION['state']['customers']['table'];
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

    if (isset( $_REQUEST['type']))
        $type=$_REQUEST['type'];
    else
        $type=$conf['type'];


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



        $awhere=$_REQUEST['where'];
    else
        $awhere=$conf['where'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    if (isset( $_REQUEST['store_id'])    ) {
        $store=$_REQUEST['store_id'];
        $_SESSION['state']['customers']['store']=$store;
    } else
        $store=$_SESSION['state']['customers']['store'];


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    /* $_SESSION['state']['customers']['table']=array(
     'order'=>$order,
     'order_dir'=>$order_direction,
     'nr'=>$number_results,
     'sf'=>$start_from,
     'where'=>$where,
     'type'=>$type,

     'f_field'=>$f_field,
     'f_value'=>$f_value
     );*/




    $_SESSION['state']['customers']['table']['order']=$order;
    $_SESSION['state']['customers']['table']['order_dir']=$order_direction;
    $_SESSION['state']['customers']['table']['nr']=$number_results;
    $_SESSION['state']['customers']['table']['sf']=$start_from;
    $_SESSION['state']['customers']['table']['where']=$awhere;
    $_SESSION['state']['customers']['table']['type']=$type;
    $_SESSION['state']['customers']['table']['f_field']=$f_field;
    $_SESSION['state']['customers']['table']['f_value']=$f_value;


    $table='`Customer Dimension` C ';

//print "-> $awhere <-";
    if ($awhere) {
    
   
        $awhere=preg_replace('/\\\"/','"',$awhere);
        //    print "$awhere";
        $awhere=json_decode($awhere,TRUE);

        
        $where='where true';



//print_r($awhere);


        $use_otf =false;

        if ($awhere['product_ordered1']!='') {
            if ($awhere['product_ordered1']!='∀') {
                $use_otf=true;
                $where_product_ordered1=extract_product_groups($awhere['product_ordered1']);
            } else
                $where_product_ordered1='true';
        } else{
            $where_product_ordered1='false';
        }
        
        if ($awhere['product_not_ordered1']!='') {
            if ($awhere['product_not_ordered1']!='ALL') {
                $use_otf=true;
                $where_product_not_ordered1=extract_product_groups($awhere['product_ordered1'],'P.`Product Code` not like','transaction.product_id not like','F.`Product Family Code` not like','P.`Product Family Key` like');
            } else
                $where_product_not_ordered1='false';
        } else
            $where_product_not_ordered1='true';

        if ($awhere['product_not_received1']!='') {
            if ($awhere['product_not_received1']!='∀') {
                $use_otf=true;
                $where_product_not_received1=extract_product_groups($awhere['product_ordered1'],'(ordered-dispatched)>0 and    product.code  like','(ordered-dispatched)>0 and  transaction.product_id not like','(ordered-dispatched)>0 and  product_group.name not like','(ordered-dispatched)>0 and  product_group.id like');
            } else {
                $use_otf=true;
                $where_product_not_received1=' ((ordered-dispatched)>0)  ';
            }
        } else
            $where_product_not_received1='true';

        $date_interval1=prepare_mysql_dates($awhere['from1'],$awhere['to1'],'`Invoice Date`','only_dates');
        if ($date_interval1['mysql']) {
            $use_otf=true;
        }


        if ($use_otf) {
            $table=' `Order Transaction Fact` OTF left join `Customer Dimension` C on (C.`Customer Key`=OTF.`Customer Key`) left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PHD.`Product ID`)  ';
        }

     




        $where='where ('.$where_product_ordered1.' and '.$where_product_not_ordered1.' and '.$where_product_not_received1.$date_interval1['mysql'].") ";

        foreach($awhere['dont_have'] as $dont_have) {
            switch ($dont_have) {
            case 'tel':
                $where.=sprintf(" and `Customer Main Telephone Key` IS NULL ");
                break;
            case 'email':
                $where.=sprintf(" and `Customer Main Email Key` IS NULL ");
                break;
            case 'fax':
                $where.=sprintf(" and `Customer Main Fax Key` IS NULL ");
                break;
            case 'address':
                $where.=sprintf(" and `Customer Main Address Incomplete`='Yes' ");
                break;
            }
        }
        foreach($awhere['have'] as $dont_have) {
            switch ($dont_have) {
            case 'tel':
                $where.=sprintf(" and `Customer Main Telephone Key` IS NOT NULL ");
                break;
            case 'email':
                $where.=sprintf(" and `Customer Main Email Key` IS NOT NULL ");
                break;
            case 'fax':
                $where.=sprintf(" and `Customer Main Fax Key` IS NOT NULL ");
                break;
            case 'address':
                $where.=sprintf(" and `Customer Main Address Incomplete`='No' ");
                break;
            }
        }


    } else {
        $where='where true ';
    }







    $filter_msg='';
    $wheref='';

    $currency='';
    if (is_numeric($store)) {
        $where.=sprintf(' and `Customer Store Key`=%d ',$store);
        $store=new Store($store);
        $currency=$store->data['Store Currency Code'];
    }



    if ($type=='all_customers') {
        $where.=sprintf(' and `Actual Customer`="Yes" ');
    }
    elseif($type=='active_customers') {
        $where.=sprintf(' and `Active Customer`="Yes" ');
    }

    //  print $f_field;


    if (($f_field=='customer name'     )  and $f_value!='') {
        $wheref="  and  `Customer Name` like '%".addslashes($f_value)."%'";
    }
    elseif(($f_field=='postcode'     )  and $f_value!='') {
        $wheref="  and  `Customer Main Postal Code` like '%".addslashes($f_value)."%'";
    }
    else if ($f_field=='id'  )
        $wheref.=" and  `Customer Key` like '".addslashes(preg_replace('/\s*|\,|\./','',$f_value))."%' ";
    else if ($f_field=='last_more' and is_numeric($f_value) )
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))>=".$f_value."    ";
    else if ($f_field=='last_less' and is_numeric($f_value) )
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(`Customer Last Order Date`))<=".$f_value."    ";
    else if ($f_field=='max' and is_numeric($f_value) )
        $wheref.=" and  `Customer Orders`<=".$f_value."    ";
    else if ($f_field=='min' and is_numeric($f_value) )
        $wheref.=" and  `Customer Orders`>=".$f_value."    ";
    else if ($f_field=='maxvalue' and is_numeric($f_value) )
        $wheref.=" and  `Customer Net Balance`<=".$f_value."    ";
    else if ($f_field=='minvalue' and is_numeric($f_value) )
        $wheref.=" and  `Customer Net Balance`>=".$f_value."    ";
    else if ($f_field=='country' and  $f_value!='') {
        if ($f_value=='UNK') {
            $wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
            $find_data=' '._('a unknown country');
        } else {

            $f_value=Address::parse_country($f_value);
            if ($f_value!='UNK') {
                $wheref.=" and  `Customer Main Country Code`='".$f_value."'    ";
                $country=new Country('code',$f_value);
                $find_data=' '.$country->data['Country Name'].' <img src="art/flags/'.$country->data['Country 2 Alpha Code'].'.png" alt="'.$country->data['Country Code'].'"/>';
            }

        }
    }



    $sql="select count(Distinct C.`Customer Key`) as total from $table  $where $wheref";
//print "$sql<br/>\n";
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total_without_filters from $table  $where ";
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


    $rtext=$total_records." ".ngettext('customer','customers',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=sprintf("Showing all customers");



    //if($total_records>$number_results)
    // $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('customer name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer like")." <b>$f_value</b> ";
            break;
        case('postcode'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with postcode like")." <b>$f_value</b> ";
            break;
        case('country'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer based in").$find_data;
            break;

        case('id'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any customer with ID like")." <b>$f_value</b> ";
            break;

        case('last_more'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."> <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
            break;
        case('last_more'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with last order")."< <b>".number($f_value)."</b> ".ngettext('day','days',$f_value);
            break;
        case('maxvalue'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."< <b>".money($f_value,$currency)."</b> ";
            break;
        case('minvalue'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("No customer with balance")."> <b>".money($f_value,$currency)."</b> ";
            break;


        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('customer name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with name like')." <b>*".$f_value."*</b>";
            break;
        case('id'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with ID  like')." <b>".$f_value."*</b>";
            break;
        case('postcode'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('with postcode like')." <b>".$f_value."*</b>";
            break;
        case('country'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('based in').$find_data;
            break;
        case('last_more'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."> ".number($f_value)."  ".ngettext('day','days',$f_value);
            break;
        case('last_less'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which last order')."< ".number($f_value)."  ".ngettext('day','days',$f_value);
            break;
        case('maxvalue'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."< ".money($f_value,$currency);
            break;
        case('minvalue'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ".ngettext('customer','customers',$total)." "._('which balance')."> ".money($f_value,$currency);
            break;
        }
    }
    else
        $filter_msg='';





    $_order=$order;
    $_dir=$order_direction;
    // if($order=='location'){
//      if($order_direction=='desc')
//        $order='country_code desc ,town desc';
//      else
//        $order='country_code,town';
//      $order_direction='';
//    }

//     if($order=='total'){
//       $order='supertotal';
//    }


    if ($order=='name')
        $order='`Customer File As`';
    elseif($order=='id')
    $order='`Customer Key`';
    elseif($order=='location')
    $order='`Customer Main Location`';
    elseif($order=='orders')
    $order='`Customer Orders`';
    elseif($order=='email')
    $order='`Customer Main Plain Email`';
    elseif($order=='telephone')
    $order='`Customer Main Plain Telephone`';
    elseif($order=='last_order')
    $order='`Customer Last Order Date`';
    elseif($order=='contact_name')
    $order='`Customer Main Contact Name`';
    elseif($order=='address')
    $order='`Customer Main Location`';
    elseif($order=='town')
    $order='`Customer Main Town`';
    elseif($order=='postcode')
    $order='`Customer Main Postal Code`';
    elseif($order=='region')
    $order='`Customer Main Country First Division`';
    elseif($order=='country')
    $order='`Customer Main Country`';
    //  elseif($order=='ship_address')
    //  $order='`customer main ship to header`';
    elseif($order=='ship_town')
    $order='`Customer Main Delivery Address Town`';
    elseif($order=='ship_postcode')
    $order='`Customer Main Delivery Address Postal Code`';
    elseif($order=='ship_region')
    $order='`Customer Main Delivery Address Country Region`';
    elseif($order=='ship_country')
    $order='`Customer Main Delivery Address Country`';
    elseif($order=='net_balance')
    $order='`Customer Net Balance`';
    elseif($order=='balance')
    $order='`Customer Outstanding Net Balance`';
    elseif($order=='total_profit')
    $order='`Customer Profit`';
    elseif($order=='total_payments')
    $order='`Customer Net Payments`';
    elseif($order=='top_profits')
    $order='`Customer Profits Top Percentage`';
    elseif($order=='top_balance')
    $order='`Customer Balance Top Percentage`';
    elseif($order=='top_orders')
    $order='``Customer Orders Top Percentage`';
    elseif($order=='top_invoices')
    $order='``Customer Invoices Top Percentage`';
    elseif($order=='total_refunds')
    $order='`Customer Total Refunds`';
    elseif($order=='contact_since')
    $order='`Customer First Contacted Date`';
    elseif($order=='activity')
    $order='`Customer Type by Activity`';
    else
        $order='`Customer File As`';
    $sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table   $where $wheref  order by $order $order_direction limit $start_from,$number_results";
    // print $sql;
    $adata=array();



    $result=mysql_query($sql);
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {


        $id="<a href='customer.php?p=cs&id=".$data['Customer Key']."'>".$myconf['customer_id_prefix'].sprintf("%05d",$data['Customer Key']).'</a>';
        if ($data['Customer Type']=='Person') {
            $name='<img src="art/icons/user.png" alt="('._('Person').')">';
        } else {
            $name='<img src="art/icons/building.png" alt="('._('Company').')">';

        }

        $name.=" <a href='customer.php?p=cs&id=".$data['Customer Key']."'>".$data['Customer Name'].'</a>';



        if ($data['Customer Orders']==0)
            $last_order_date='';
        else
            $last_order_date=strftime("%e %b %y", strtotime($data['Customer Last Order Date']." +00:00"));

        $contact_since=strftime("%e %b %y", strtotime($data['Customer First Contacted Date']." +00:00"));


        if ($data['Customer Billing Address Link']=='Contact')
            $billing_address='<i>'._('Same as Contact').'</i>';
        else
            $billing_address=$data['Customer XHTML Billing Address'];

        if ($data['Customer Delivery Address Link']=='Contact')
            $delivery_address='<i>'._('Same as Contact').'</i>';
        elseif($data['Customer Delivery Address Link']=='Billing')
        $delivery_address='<i>'._('Same as Billing').'</i>';
        else
            $delivery_address=$data['Customer XHTML Main Delivery Address'];

        $adata[]=array(
                     'id'=>$id,
                     'name'=>$name,
                     'location'=>$data['Customer Main Location'],
                     'orders'=>number($data['Customer Orders']),
                     'invoices'=>$data['Customer Orders Invoiced'],
                     'email'=>$data['Customer Main XHTML Email'],
                     'telephone'=>$data['Customer Main XHTML Telephone'],
                     'last_order'=>$last_order_date,
                     'contact_since'=>$contact_since,

                     'total_payments'=>money($data['Customer Net Payments'],$currency),
                     'net_balance'=>money($data['Customer Net Balance'],$currency),
                     'total_refunds'=>money($data['Customer Net Refunds'],$currency),
                     'total_profit'=>money($data['Customer Profit'],$currency),
                     'balance'=>money($data['Customer Outstanding Net Balance'],$currency),


                     'top_orders'=>number($data['Customer Orders Top Percentage']).'%',
                     'top_invoices'=>number($data['Customer Invoices Top Percentage']).'%',
                     'top_balance'=>number($data['Customer Balance Top Percentage']).'%',
                     'top_profits'=>number($data['Customer Profits Top Percentage']).'%',
                     'contact_name'=>$data['Customer Main Contact Name'],
                     'address'=>$data['Customer Main XHTML Address'],
                     'billing_address'=>$billing_address,
                     'delivery_address'=>$delivery_address,

                     //'town'=>$data['Customer Main Town'],
                     //'postcode'=>$data['Customer Main Postal Code'],
                     //'region'=>$data['Customer Main Country First Division'],
                     //'country'=>$data['Customer Main Country'],
                     //'ship_address'=>$data['customer main ship to header'],
                     //'ship_town'=>$data['Customer Main Delivery Address Town'],
                     //'ship_postcode'>$data['Customer Main Delivery Address Postal Code'],
                     //'ship_region'=>$data['Customer Main Delivery Address Region'],
                     //'ship_country'=>$data['Customer Main Delivery Address Country'],
                     'activity'=>$data['Customer Type by Activity']

                 );
///if(isset($_REQUEST['textValue'])&isset($_REQUEST['typeValue']))
///{
///	$list_name=$_REQUEST['textValue'];
///	$list_type=$_REQUEST['typeValue'];
///}
///$dataid[]=array('id'=>$id,'list_name'=>$list_name,'list_type'=>$list_type);//
   }
    mysql_free_result($result);

///print_r($dataid);//


    $response=array('resultset'=>
                                array('state'=>200,
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

function customer_advanced_search() {
    global $user;
    if (!$user->can_view('customers')) {
        exit();
    }

    $conf=$_SESSION['state']['customers']['list'];
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
//     if(isset( $_REQUEST['f_field']))
//      $f_field=$_REQUEST['f_field'];
//    else
//      $f_field=$conf['f_field'];

//   if(isset( $_REQUEST['f_value']))
//      $f_value=$_REQUEST['f_value'];
//    else
//      $f_value=$conf['f_value'];
    if (isset( $_REQUEST['where']))
        $awhere=$_REQUEST['where'];
    else
        $awhere=$conf['where'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    $filtered=0;
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;

    //print_r($_SESSION['state']['customers']['advanced_search']);
    $_SESSION['state']['customers']['list']['order']=$order;
    $_SESSION['state']['customers']['list']['order_dir']=$order_direction;
    $_SESSION['state']['customers']['list']['nr']=$number_results;
    $_SESSION['state']['customers']['list']['sf']=$start_from;
    $_SESSION['state']['customers']['list']['where']=$awhere;

    $filter_msg='';
    // $awhere='{"from1":"","from2":"","product_not_ordered1":"","product_not_ordered2":"","product_not_received1":"","product_not_received2":"","product_ordered1":"g(ob)","product_ordered2":"","to1":"","to2":""}';
    $awhere=preg_replace('/\\\"/','"',$awhere);
    //    print "$awhere";
    $awhere=json_decode($awhere,TRUE);
    // print_r($awhere);
    $where='where ';

    if ($awhere['product_ordered1']!='') {
        if ($awhere['product_ordered1']!='ANY') {
            $where_product_ordered1=extract_product_groups($awhere['product_ordered1']);
        } else
            $where_product_ordered1='true';
    } else
        $where_product_ordered1='false';

    if ($awhere['product_not_ordered1']!='') {
        if ($awhere['product_not_ordered1']!='ALL') {
            $where_product_not_ordered1=extract_product_groups($awhere['product_ordered1'],'P.`Product Code` not like','transaction.product_id not like','F.`Product Family Code` not like','P.`Product Family Key` like');
        } else
            $where_product_not_ordered1='false';
    } else
        $where_product_not_ordered1='true';

    if ($awhere['product_not_received1']!='') {
        if ($awhere['product_not_received1']!='ANY') {
            $where_product_not_received1=extract_product_groups($awhere['product_ordered1'],'(ordered-dispatched)>0 and    product.code  like','(ordered-dispatched)>0 and  transaction.product_id not like','(ordered-dispatched)>0 and  product_group.name not like','(ordered-dispatched)>0 and  product_group.id like');
        } else
            $where_product_not_received1=' ((ordered-dispatched)>0)  ';
    } else
        $where_product_not_received1='true';




    $date_interval1=prepare_mysql_dates($awhere['from1'],$awhere['to1'],'`Invoice Date`','only_dates');


    $geo_base='';
    if ($awhere['geo_base']=='home')
        $geo_base='and list_country.id='.$myconf['country_id'];
    elseif($awhere['geo_base']=='nohome')
    $geo_base='and list_country.id!='.$myconf['country_id'];
    $with_mail='';
    if ($awhere['mail'])
        $with_mail=' and `Customer Main Email Key`!=0 ';
    $with_tel='';
    if ($awhere['tel'])
        $with_tel=' and `Customer Main Telephone Key`!=0 ';





    $where='where ('.$where_product_ordered1.' and '.$where_product_not_ordered1.' and '.$where_product_not_received1.$date_interval1['mysql'].")  $geo_base $with_mail $with_tel";










    $sql="select count(distinct C.`Customer Key`) as total  from `Order Transaction Fact` OTF left join `Customer Dimension` C on (C.`Customer Key`=OTF.`Customer Key`) left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PHD.`Product ID`)   $where ";
//print $sql;

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total_records=$row['total'];
    } else
        $total_records=0;
    $total=$total_records;


    $rtext=$total_records." ".ngettext('customer found','customers found',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=sprintf("Showing all customers");


    if ($order=='name')
        $order='`Customer File As`';
    elseif($order=='id')
    $order='`Customer Key`';
    elseif($order=='location')
    $order='`Customer Main Location`';
    elseif($order=='orders')
    $order='`Customer Orders`';
    elseif($order=='email')
    $order='`Customer Email`';
    elseif($order=='telephone')
    $order='`Customer Main Telehone`';
    elseif($order=='last_order')
    $order='`Customer Last Order Date`';
    elseif($order=='contact_name')
    $order='`Customer Main Contact Name`';
    elseif($order=='address')
    $order='`Customer Main Location`';
    elseif($order=='town')
    $order='`Customer Main Town`';
    elseif($order=='postcode')
    $order='`Customer Main Postal Code`';
    elseif($order=='region')
    $order='`Customer Main Country First Division`';
    elseif($order=='country')
    $order='`Customer Main Country`';
    //  elseif($order=='ship_address')
    //  $order='`customer main ship to header`';
    elseif($order=='ship_town')
    $order='`Customer Main Delivery Address Town`';
    elseif($order=='ship_postcode')
    $order='`Customer Main Delivery Address Postal Code`';
    elseif($order=='ship_region')
    $order='`Customer Main Delivery Address Country Region`';
    elseif($order=='ship_country')
    $order='`Customer Main Delivery Address Country`';
    elseif($order=='net_balance')
    $order='`Customer Net Balance`';
    elseif($order=='balance')
    $order='`Customer Outstanding Net Balance`';
    elseif($order=='total_profit')
    $order='`Customer Profit`';
    elseif($order=='total_payments')
    $order='`Customer Net Payments`';
    elseif($order=='top_profits')
    $order='`Customer Profits Top Percentage`';
    elseif($order=='top_balance')
    $order='`Customer Balance Top Percentage`';
    elseif($order=='top_orders')
    $order='``Customer Orders Top Percentage`';
    elseif($order=='top_invoices')
    $order='``Customer Invoices Top Percentage`';
    elseif($order=='total_refunds')
    $order='`Customer Total Refunds`';





    $sql="select `Customer Last Order Date`,`Customer Name`,`Customer Orders`,C.`Customer Key`,`Customer Main Location`,`Customer Main XHTML Email`,`Customer Main XHTML Telephone` from `Order Transaction Fact` OTF left join `Customer Dimension` C on (C.`Customer Key`=OTF.`Customer Key`) left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PHD.`Product ID`)   $where  group by C.`Customer Key` order by $order $order_direction limit $start_from,$number_results    ";

    $res=mysql_query($sql);
    $adata=array();
    while ($data=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $id="<a href='customer.php?id=".$data['Customer Key']."'>".sprintf("%05d",$data['Customer Key']).'</a>';
        $name="<a href='customer.php?id=".$data['Customer Key']."'>".$data['Customer Name'].'</a>';



        $adata[]=array(
                     'id'=>$id
                          ,'name'=>$name
                                  ,'orders'=>$data['Customer Orders']
                                            ,'last_order'=>strftime("%e %b %Y", strtotime($data['Customer Last Order Date']))
                                                          ,'location'=>$data['Customer Main Location']
                                                                      ,'email'=>$data['Customer Main XHTML Email']
                                                                               ,'tel'=>$data['Customer Main XHTML Telephone']
                 );

    }
    $response=array('resultset'=>
                                array('state'=>200,
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

function list_staff() {
    global $myconf;

    $conf=$_SESSION['state']['hr']['staff'];
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

    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$_SESSION['state']['hr']['view'];




    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_order=$order;
    $_dir=$order_direction;



    $_SESSION['state']['hr']['staff']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
    $_SESSION['state']['hr']['view']=$view;


    $wheref='';
    if ($f_field=='name' and $f_value!=''  )
        $wheref.=" and  name like '%".addslashes($f_value)."%'    ";
    else if ($f_field=='position_id' or $f_field=='area_id'   and is_numeric($f_value) )
        $wheref.=sprintf(" and  $f_field=%d ",$f_value);


    switch ($view) {
    case('all'):
        break;
    case('staff'):
        $where.=" and `Staff Currently Working`='Yes'  ";
        break;
    case('exstaff'):
        $where.=" and `Staff Currently Working`='No' ";
        break;
    }

    $sql="select count(*) as total from `Staff Dimension` SD left join `Contact Dimension` CD on (`Contact Key`=`Staff Contact Key`) $where $wheref";


    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total from `Staff Dimension` SD left join `Contact Dimension` CD on (`Contact Key`=`Staff Contact Key`)   $where ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$row['total']-$total;
        }

    } else {
        $filtered=0;
        $total_records=$total;
    }

    mysql_free_result($res);

    $filter_msg='';


    $rtext=$total_records." ".ngettext('record','records',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=sprintf("Showing all records");

    switch ($f_field) {
    case('name'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with name")." <b>*".$f_value."*</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with name')." <b>*".$f_value."*</b>)";
        break;
    case('area_id'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff on area")." <b>".$f_value."</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff on area')." <b>".$f_value."</b>)";
        break;
    case('position_id'):
        if ($total==0 and $filtered>0)
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There is no staff with position")." <b>".$f_value."</b> ";
        elseif($filtered>0)
        $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total ("._('staff with position')." <b>".$f_value."</b>)";
        break;

    }


    if ($order=='name')
        $order='`Staff Name`';
    elseif($order=='position')
    $order='position';
    else
        $order='`Staff Name`';

    $sql="select (select GROUP_CONCAT(distinct `Company Position Title`) from `Company Position Staff Bridge` PSB  left join `Company Position Dimension` P on (`Company Position Key`=`Position Key`) where PSB.`Staff Key`= SD.`Staff Key`) as position, `Staff Alias`,`Staff Key`,`Staff Name` from `Staff Dimension` SD   $where $wheref order by $order $order_direction limit $start_from,$number_results";
    //print $sql;
    $adata=array();
    $res=mysql_query($sql);
    while ($data=mysql_fetch_array($res)) {


        $_id=$myconf['staff_prefix'].sprintf('%03d',$data['Staff Key']);
        $id=sprintf('<a href="staff.php?id=%d">%s</a>',$data['Staff Key'],$_id);

        $department='';
        $area='';
        $position=$data['position'];
        $adata[]=array(
                     'id'=>$id,
                     'alias'=>$data['Staff Alias'],
                     'name'=>$data['Staff Name'],
                     'department'=>$department,
                     'area'=>$area,
                     'position'=>$position

                 );
    }
    mysql_free_result($res);


    $response=array('resultset'=>
                                array('state'=>200,
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

function find_company($the_data) {

    $data=$the_data['values'];
    $scope=$the_data['scope']['scope'];
    if ($scope=='customer') {
        $scope='Customer';
        $store_key=$the_data['scope']['store_key'];
    }

    $candidates_data=array();
    // quick try to find the email


    if ($data['Company Main Plain Email']!='') {
        $sql=sprintf("select T.`Email Key`,`Subject Key` from `Email Dimension` T left join `Email Bridge` TB  on (TB.`Email Key`=T.`Email Key`) where `Email`=%s and `Subject Type`='Contact'  "
                     ,prepare_mysql($data['Company Main Plain Email'])
                    );


        $scope_found_key=0;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result)) {
            $contact=new Contact($row['Subject Key']);
            $company_key=$contact->company_key();
            if ($company_key) {
                $link='';
                $_company=new Company ($company_key);
                $subject_key=$company_key;
                if ($scope=='Customer') {
                    $parent_keys=$_company->get_parent_keys($scope);
                    $in_store=false;
                    $in_other_store=false;
                    foreach($parent_keys as $parent_key) {
                        $parent=new Customer($parent_key);
                        if ($parent->data['Customer Store Key']==$store_key) {
                            $in_store=true;
                            $scope_found_key=$parent->id;
                            $link.=sprintf('<br/><img src="art/icons/warning.png" alt="%s"/> %s <a href="customer.php?edit=%d">(%s)</a>'
                                           ,_('Warning')
                                           ,_('A customer found with similar data in this store')
                                           ,$parent->id
                                           ,_('Edit Customer')
                                          );
                        }
                        elseif(!$in_other_store) {
                            $link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
                                           ,_('A customer found with similar data in other store')
                                           ,$company->id
                                           ,_('Recollect Data')
                                          );
                            $in_other_store=true;

                        }

                    }


                }

                $candidates_data[]= array('link'=>$link,'card'=>$_company->display('card'),'score'=>1000,'key'=>$scope_found_key,'tipo'=>'company','found'=>1);
            } else {
                $subject_key=$contact->id;


                $link='';

                if ($scope=='Customer') {
                    $parent_keys=$contact->get_parent_keys($scope);
                    $in_store=false;
                    $in_other_store=false;
                    foreach($parent_keys as $parent_key) {
                        $parent=new Customer($parent_key);
                        if ($parent->data['Customer Store Key']==$store_key) {
                            $in_store=true;
                            $scope_found_key=$parent->id;
                            $link.=sprintf('<br/><img src="art/icons/warning.png" alt="%s"/> %s (%s)'
                                           ,_('Warning')
                                           ,_('A customer found with similar data in this store')
                                           ,$parent->id
                                           ,_('Edit Customer')
                                          );
                        }
                        elseif(!$in_other_store) {
                            $link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
                                           ,_('A customer found with similar data in other store')
                                           ,$company->id
                                           ,_('Recollect Data')
                                          );
                            $in_other_store=true;

                        }

                    }


                }



                $candidates_data[]= array('link'=>$link,'card'=>$contact->display('card'),'score'=>1000,'key'=>$scope_found_key,'tipo'=>'contact','found'=>1);

                $subject_key=$contact->key;
            }



            $response=array('candidates_data'=>$candidates_data,'action'=>'found_email','found_key'=>$scope_found_key);
            echo json_encode($response);
            return;
        }
    }



    $max_results=8;

    $company=new company('find fuzzy',$data);
    $found_key=0;
    if ($company->found) {
        $action='found';
        $found_key=$company->found_key;
    }
    elseif($company->number_candidate_companies>0)
    $action='found_candidates';
    else
        $action='nothing_found';


    $count=0;
    foreach($company->candidate_companies as $company_key=>$score) {
        if ($score<20)
            continue;
        if ($count>$max_results)
            break;
        $_company=new Company ($company_key);

        $link='';

        $scope_found_key=0;
        if ($scope=='Customer') {
            $parent_keys=$_company->get_parent_keys($scope);
            $in_store=false;
            $in_other_store=false;
            //  print_r($parent_keys);
            foreach($parent_keys as $parent_key) {
                $parent=new Customer($parent_key);
                if ($parent->data['Customer Store Key']==$store_key) {
                    $in_store=true;
                    $scope_found_key=$parent->id;
                    $link.=sprintf('<br/><img src="art/icons/exclamation.png" alt="%s"/> %s<br/>(<a href="customer.php?edit=%d">%s</a>)'
                                   ,_('Warning').":"
                                   ,_('Customer in this store')
                                   ,$parent->id
                                   ,_('Edit Customer')
                                  );
                }
                elseif(!$in_other_store) {
                    $link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
                                   ,_('Customer in another store')
                                   ,$company->id
                                   ,_('Recollect Data')
                                  );
                    $in_other_store=true;

                }

            }

            $parent_keys=$_company->get_parent_keys('Supplier');
            foreach($parent_keys as $parent_key) {
                $link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
                               ,_('Supplier')
                               ,$company->id
                               ,_('Recollect Data')
                              );
            }





        }




        $link=preg_replace('/^\<br\/\>/','',$link);


        $found=0;
        if ($company->found_key==$_company->id)
            $found=1;
        $candidates_data[]= array('link'=>$link,'card'=>$_company->display('card'),'score'=>$score,'key'=>$scope_found_key,'tipo'=>'company','found'=>$found);

        $count++;
    }
    //print_r($company->candidate_companies);

    $response=array('candidates_data'=>$candidates_data,'action'=>$action,'found_key'=>$found_key);
    echo json_encode($response);
}
function find_company_area($data) {
    $extra_where='';
    if ($data['parent_key']) {
        $extra_where=sprintf(' and `Company Key`=%d',$data['parent_key']);
    }

    $adata=array();
    $sql=sprintf("select `Company Area Key` ,`Company Area Code` ,`Company Area Name` from `Company Area Dimension`  where  (`Company Area Name` like '%%%s%%' or `Company Area Code` like '%s%%') %s limit 10"
                 ,addslashes($data['query'])
                 ,addslashes($data['query'])
                 ,$extra_where

                );
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result)) {


        $adata[]=array(

                     'key'=>$row['Company Area Key'],
                     'code'=>$row['Company Area Code'],
                     'name'=>$row['Company Area Name']
                 );
    }
    $response=array('data'=>$adata);
    echo json_encode($response);


}

function find_company_department($data) {
    $extra_where='';
    if ($data['parent_key']) {
        $extra_where.=sprintf(' and D.`Company Area Key`=%d',$data['parent_key']);
    }
    if ($data['grandparent_key']) {
        $extra_where.=sprintf(' and `Company Key`=%d',$data['grandparent_key']);
    }

    $adata=array();
    $sql=sprintf("select `Company Department Key` ,`Company Department Code` ,`Company Department Name`,D.`Company Area Key` ,`Company Area Code` ,`Company Area Name` from `Company Department Dimension` D left join `Company Area Dimension` A on (A.`Company Area Key`=D.`Company Area Key`)  where  (`Company Department Name` like '%%%s%%' or `Company Department Code` like '%s%%') %s limit 10"
                 ,addslashes($data['query'])
                 ,addslashes($data['query'])
                 ,$extra_where

                );
    $result=mysql_query($sql);
    while ($row=mysql_fetch_array($result)) {


        $adata[]=array(

                     'key'=>$row['Company Department Key'],
                     'code'=>$row['Company Department Code'],
                     'name'=>$row['Company Department Name'],
                     'area_key'=>$row['Company Area Key'],
                     'area_code'=>$row['Company Area Code'],
                     'area_name'=>$row['Company Area Name'],
                 );
    }
    $response=array('data'=>$adata);
    echo json_encode($response);


}




function find_contact($the_data) {
    $candidates_data=array();
    // print_r($data);

    $data=$the_data['values'];



    $scope=$the_data['scope']['scope'];
    if ($scope=='customer') {
        $scope='Customer';
        $store_key=$the_data['scope']['store_key'];
    }



    // quick try to find the email
    if ($data['Contact Main Plain Email']!='') {
        $sql=sprintf("select T.`Email Key`,`Subject Key` from `Email Dimension` T left join `Email Bridge` TB  on (TB.`Email Key`=T.`Email Key`) where `Email`=%s and `Subject Type`='Contact'  "
                     ,prepare_mysql($data['Contact Main Plain Email'])
                    );
        $scope_found_key=0;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result)) {
            $contact=new Contact($row['Subject Key']);
            $company_key=$contact->company_key();
            if ($company_key) {
                $link='';
                $_company=new Company ($company_key);
                $subject_key=$company_key;
                if ($scope=='Customer') {
                    $parent_keys=$_company->get_parent_keys($scope);
                    $in_store=false;
                    $in_other_store=false;
                    foreach($parent_keys as $parent_key) {
                        $parent=new Customer($parent_key);
                        if ($parent->data['Customer Store Key']==$store_key) {
                            $in_store=true;
                            $scope_found_key=$parent->id;
                            $link.=sprintf('<br/><img src="art/icons/warning.png" alt="%s"/> %s <a href="customer.php?edit=%d">(%s)</a>'
                                           ,_('Warning')
                                           ,_('A customer found with similar data in this store')
                                           ,$parent->id
                                           ,_('Edit Customer')
                                          );
                        }
                        elseif(!$in_other_store) {
                            $link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
                                           ,_('A customer found with similar data in other store')
                                           ,$company->id
                                           ,_('Recollect Data')
                                          );
                            $in_other_store=true;

                        }

                    }


                }

                $candidates_data[]= array('link'=>$link,'card'=>$_company->display('card'),'score'=>1000,'key'=>$scope_found_key,'tipo'=>'company','found'=>1);
            } else {
                $subject_key=$contact->id;


                $link='';

                if ($scope=='Customer') {
                    $parent_keys=$contact->get_parent_keys($scope);
                    $in_store=false;
                    $in_other_store=false;
                    foreach($parent_keys as $parent_key) {
                        $parent=new Customer($parent_key);
                        if ($parent->data['Customer Store Key']==$store_key) {
                            $in_store=true;
                            $scope_found_key=$parent->id;
                            $link.=sprintf('<br/><img src="art/icons/warning.png" alt="%s"/> %s (%s)'
                                           ,_('Warning')
                                           ,_('A customer found with similar data in this store')
                                           ,$parent->id
                                           ,_('Edit Customer')
                                          );
                        }
                        elseif(!$in_other_store) {
                            $link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
                                           ,_('A customer found with similar data in other store')
                                           ,$company->id
                                           ,_('Recollect Data')
                                          );
                            $in_other_store=true;

                        }

                    }


                }



                $candidates_data[]= array('link'=>$link,'card'=>$contact->display('card'),'score'=>1000,'key'=>$scope_found_key,'tipo'=>'contact','found'=>1);

                $subject_key=$contact->key;
            }



            $response=array('candidates_data'=>$candidates_data,'action'=>'found_email','found_key'=>$scope_found_key);
            echo json_encode($response);
            return;
        }



    }




    $max_results=8;

    $contact=new contact('find fuzzy',$data);
    $found_key=0;
    if ($contact->found) {
        $action='found';
        $found_key=$contact->found_key;
    } else
        $action='nothing_found';


    $count=0;
    foreach($contact->candidate as $contact_key=>$score) {
        $link='';

        if ($score<20)
            continue;


        if ($count>$max_results)
            break;
        $_contact=new Contact ($contact_key);



        $scope_found_key=0;
        if ($scope=='Customer') {
            $parent_keys=$_contact->get_parent_keys($scope);
            $in_store=false;
            $in_other_store=false;
            //  print_r($parent_keys);
            foreach($parent_keys as $parent_key) {
                $parent=new Customer($parent_key);
                if ($parent->data['Customer Store Key']==$store_key) {
                    $in_store=true;
                    $scope_found_key=$parent->id;
                    $link.=sprintf('<br/><img src="art/icons/exclamation.png" alt="%s"/> %s<br/>(<a href="customer.php?edit=%d">%s</a>)'
                                   ,_('Warning').":"
                                   ,_('Customer in this store')
                                   ,$parent->id
                                   ,_('Edit Customer')
                                  );
                }
                elseif(!$in_other_store) {
                    $link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
                                   ,_('Customer in another store')
                                   ,$company->id
                                   ,_('Recollect Data')
                                  );
                    $in_other_store=true;

                }

            }

            $parent_keys=$_contact->get_parent_keys('Supplier');
            foreach($parent_keys as $parent_key) {
                $link.=sprintf('<br/>%s (<span onClick="recollect_data_from_company(%s)">%s</span>)'
                               ,_('Supplier')
                               ,$company->id
                               ,_('Recollect Data')
                              );
            }





        }




        $link=preg_replace('/^\<br\/\>/','',$link);












        $found=0;
        if ($contact->found_key==$_contact->id)
            $found=1;
        //$candidates_data[]= array('card'=>$_contact->display('card'),'score'=>$score,'key'=>$_contact->id,'tipo'=>'contact','found'=>$found);
        $candidates_data[]= array('link'=>$link,'card'=>$_contact->display('card'),'score'=>$score,'key'=>$scope_found_key,'tipo'=>'company','found'=>$found);

        $count++;
    }
    //print_r($company->candidate_companies);

    $response=array('candidates_data'=>$candidates_data,'action'=>$action,'found_key'=>$found_key);
    echo json_encode($response);
}

function used_email() {

    $email=$_REQUEST('query');
    $sql=sprintf('select `Subject`,`Subject Key` from `Email Dimension` E left join `Email Bridge` EB on (E.`Email Key`=EB.`Email Key`) where `Email`=%s  '
                 ,prepare_mysql($email)
                );
    $result=mysql_query($sql);
    $num_rows = mysql_num_rows($result);
    if ($num_rows==0) {
        $response=array('state'=>200,'found'=>0);
        echo json_encode($response);
    }
    elseif($num_rows==1) {


    }



}



function list_company_areas() {





    $conf=$_SESSION['state']['company_areas']['table'];
    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$_SESSION['state']['company_areas']['view'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (!is_numeric($start_from))
        $start_from=0;

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
    } else
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



    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


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




    if (isset( $_REQUEST['parent']))
        $parent=$_REQUEST['parent'];
    else
        $parent=$conf['parent'];

    if (isset( $_REQUEST['mode']))
        $mode=$_REQUEST['mode'];
    else
        $mode=$conf['mode'];

    if (isset( $_REQUEST['restrictions']))
        $restrictions=$_REQUEST['restrictions'];
    else
        $restrictions=$conf['restrictions'];




    $_SESSION['state']['company_areas']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
            ,'mode'=>$mode,'restrictions'=>'','parent'=>$parent
                                                      );




    $group='';





    $filter_msg='';

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

    //  if(!is_numeric($start_from))
    //        $start_from=0;
    //      if(!is_numeric($number_results))
    //        $number_results=25;


    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';
    $wheref='';
    if ($f_field=='company name' and $f_value!='')
        $wheref.=" and  `Company Name` like '%".addslashes($f_value)."%'";
    elseif($f_field=='email' and $f_value!='')
    $wheref.=" and  `Company Main Plain Email` like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Company Area Dimension`  $where $wheref   ";
//print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Company Area Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }
    mysql_free_result($res);

    $rtext=$total_records." ".ngettext('company area','company areas',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with name like')." <b>".$f_value."*</b>";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with email like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_order=$order;
    $_order_dir=$order_dir;
    $order='`Company Area Name`';

    if ($order=='code')
        $order='`Company Area Code`';



    $sql="select  * from `Company Area Dimension` P  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);
    $adata=array();

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {



        $adata[]=array(




                     'code'=>sprintf('<a href="company_area.php?id=%d">%s</a>',$row['Company Area Key'],$row['Company Area Code'])
                            ,'name'=>sprintf('<a href="company_area.php?id=%d">%s</a>',$row['Company Area Key'],$row['Company Area Name'])

                 );
    }
    mysql_free_result($res);


    // $total_records=ceil($total_records/$number_results)+$total_records;

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


function is_company_department_code() {
    if (!isset($_REQUEST['query']) or !isset($_REQUEST['company_key']) ) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$_REQUEST['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $company_key=$_REQUEST['company_key'];

    $sql=sprintf("select `Company Department Key`,`Company Department Name`,`Company Department Code` from `Company Department Dimension` where `Company Key`=%d and `Company Department Code`=%s  "
                 ,$company_key
                 ,prepare_mysql($query)
                );
    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Company Department <a href="company_department.php?id=%d">%s</a> already has this code (%s)'
                     ,$data['Company Department Key']
                     ,$data['Company Department Name']
                     ,$data['Company Department Code']
                    );
        $response= array(
                       'state'=>200,
                       'found'=>1,
                       'msg'=>$msg
                   );
        echo json_encode($response);
        return;
    } else {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

}
function is_company_department_name() {
    if (!isset($_REQUEST['query']) or !isset($_REQUEST['company_key']) ) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$_REQUEST['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $company_key=$_REQUEST['company_key'];

    $sql=sprintf("select `Company Department Key`,`Company Department Name`,`Company Department Code` from `Company Department Dimension` where `Company Key`=%d and `Company Department Name`=%s  "
                 ,$company_key
                 ,prepare_mysql($query)
                );
    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Another Company Department <a href="company_department.php?id=%d">(%s)</a> already has this name'
                     ,$data['Company Department Key']
                     ,$data['Company Department Code']
                    );
        $response= array(
                       'state'=>200,
                       'found'=>1,
                       'msg'=>$msg
                   );
        echo json_encode($response);
        return;
    } else {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

}



function is_department_code() {
    if (!isset($_REQUEST['query']) or !isset($_REQUEST['department_key']) ) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$_REQUEST['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $department_key=$_REQUEST['department_key'];

    $sql=sprintf("select `Company Department Key`,`Company Department Name`,`Company Department Code` from `Company Department Dimension` where `Company Department Key`=%d and  `Company Department Code`=%s  "
                 ,$department_key,prepare_mysql($query)
                );
    $res=mysql_query($sql);
//print $sql;
    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Company Department <a href="company_department.php?id=%d">%s</a> already has this code (%s)'
                     ,$data['Company Department Key']
                     ,$data['Company Department Name']
                     ,$data['Company Department Code']
                    );
        $response= array(
                       'state'=>200,
                       'found'=>1,
                       'msg'=>$msg
                   );
        echo json_encode($response);
        return;
    } else {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

}
function is_department_name() {
    if (!isset($_REQUEST['query']) or !isset($_REQUEST['department_key']) ) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$_REQUEST['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $department_key=$_REQUEST['department_key'];

    $sql=sprintf("select `Company Department Key`,`Company Department Name`,`Company Department Code` from `Company Department Dimension` where `Company Department Key`=%d and `Company Department Name`=%s  "
                 ,$department_key
                 ,prepare_mysql($query)
                );
    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Another Company Department <a href="company_department.php?id=%d">(%s)</a> already has this name'
                     ,$data['Company Department Key']
                     ,$data['Company Department Code']
                    );
        $response= array(
                       'state'=>200,
                       'found'=>1,
                       'msg'=>$msg
                   );
        echo json_encode($response);
        return;
    } else {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

}


function list_company_departments() {

    $conf=$_SESSION['state']['company_departments']['table'];
    if (isset( $_REQUEST['parent'])) {
        $parent=$_REQUEST['parent'];
        $_SESSION['state']['company_departments']['parent']=$parent;
    } else
        $parent= $_SESSION['state']['company_departments']['parent'];

    if ($parent=='area') {
        $conf_table='company_area';

        $conf=$_SESSION['state']['company_area']['departments'];

    } else {
        $conf_table='company_departments';
        $conf=$_SESSION['state'][$conf_table]['table'];

    }

    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$_SESSION['state']['company_departments']['view'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (!is_numeric($start_from))
        $start_from=0;

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
    } else
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



    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


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






    if (isset( $_REQUEST['restrictions']))
        $restrictions=$_REQUEST['restrictions'];
    else
        $restrictions=$conf['restrictions'];


    if ($parent=='area') {
        $_SESSION['state']['company_area']['departments']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
                ,'restrictions'=>'','parent'=>$parent
                                                               );
    } else {
        $_SESSION['state']['company_departments']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
                ,'restrictions'=>'','parent'=>$parent
                                                                );
    }




    if ($parent=='area') {
        $where.=sprintf(' and A.`Company Area Key`=%d',$_SESSION['state']['company_area']['id']);
    }


    $group='';





    $filter_msg='';

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

    //  if(!is_numeric($start_from))
    //        $start_from=0;
    //      if(!is_numeric($number_results))
    //        $number_results=25;


    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';
    $wheref='';
    if ($f_field=='company name' and $f_value!='')
        $wheref.=" and  `Company Name` like '%".addslashes($f_value)."%'";
    elseif($f_field=='email' and $f_value!='')
    $wheref.=" and  `Company Main Plain Email` like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Company Department Dimension`  $where $wheref   ";
//print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Company Department Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }
    mysql_free_result($res);

    $rtext=$total_records." ".ngettext('company department','company departments',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with name like')." <b>".$f_value."*</b>";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with email like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_order=$order;
    $_order_dir=$order_dir;
    $order='`Company Department Name`';

    if ($order=='code')
        $order='`Company Department Code`';



    $sql="select  * from `Company Department Dimension` D left join `Company Area Department Bridge` on (`Department Key`=`Company Department Key`)     left join `Company Area Dimension` A on (`Area Key`=A.`Company Area Key`)  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);
    $adata=array();

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {



        $adata[]=array(



                     'area'=>sprintf('<a href="company_area.php?id=%d">%s</a>',$row['Company Area Key'],$row['Company Area Code'])

                            ,'code'=>sprintf('<a href="company_department.php?id=%d">%s</a>',$row['Company Department Key'],$row['Company Department Code'])
                                    ,'name'=>sprintf('<a href="company_department.php?id=%d">%s</a>',$row['Company Department Key'],$row['Company Department Name'])

                 );
    }
    mysql_free_result($res);


    // $total_records=ceil($total_records/$number_results)+$total_records;

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

function list_company_positions() {

    $conf=$_SESSION['state']['positions']['table'];
    if (isset( $_REQUEST['parent'])) {
        $parent=$_REQUEST['parent'];
        $_SESSION['state']['positions']['parent']=$parent;
    } else
        $parent= $_SESSION['state']['positions']['parent'];

    if ($parent=='area') {
        $conf_table='company_area';

        $conf=$_SESSION['state']['company_area']['positions'];

    } else {
        $conf_table='positions';
        $conf=$_SESSION['state'][$conf_table]['table'];

    }

    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$_SESSION['state']['positions']['view'];

    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];
    if (!is_numeric($start_from))
        $start_from=0;

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
    } else
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



    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


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






    if (isset( $_REQUEST['restrictions']))
        $restrictions=$_REQUEST['restrictions'];
    else
        $restrictions=$conf['restrictions'];


    if ($parent=='area') {
        $_SESSION['state']['company_area']['positions']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
                ,'restrictions'=>'','parent'=>$parent
                                                             );
    } else {
        $_SESSION['state']['positions']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
                ,'restrictions'=>'','parent'=>$parent
                                                      );
    }




    if ($parent=='area') {
        $where.=sprintf(' and A.`Company Area Key`=%d',$_SESSION['state']['company_area']['id']);
    }


    $group='';





    $filter_msg='';

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

    //  if(!is_numeric($start_from))
    //        $start_from=0;
    //      if(!is_numeric($number_results))
    //        $number_results=25;


    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';
    $wheref='';
    if ($f_field=='company name' and $f_value!='')
        $wheref.=" and  `Company Name` like '%".addslashes($f_value)."%'";
    elseif($f_field=='email' and $f_value!='')
    $wheref.=" and  `Company Main Plain Email` like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Company Position Dimension`  $where $wheref   ";
//print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Company Position Dimension`  $where   ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }
    mysql_free_result($res);

    $rtext=$total_records." ".ngettext('position','positions',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with name like ")." <b>".$f_value."*</b> ";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any contact with email like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with name like')." <b>".$f_value."*</b>";
            break;
        case('email'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('companies with email like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_order=$order;
    $_order_dir=$order_dir;
    $order='`Company Position Title`';

    if ($order=='code')
        $order='`Company Position Code`';
    elseif($order=='title')
    $order='`Company Position Title`';


    $sql="select  * from `Company Position Dimension` P left join `Company Department Position Bridge` on (`Position Key`=`Company Position Key`)     left join `Company Department Dimension` D on (`Department Key`=`Company Department Key`)  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);
    $adata=array();

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {



        $adata[]=array(



                     'department'=>sprintf('<a href="company_department.php?id=%d">%s</a>',$row['Company Department Key'],$row['Company Department Code'])

                                  ,'code'=>sprintf('<a href="company_department.php?id=%d">%s</a>',$row['Company Position Key'],$row['Company Position Code'])
                                          ,'title'=>sprintf('<a href="company_department.php?id=%d">%s</a>',$row['Company Position Key'],$row['Company Position Title'])

                 );
    }
    mysql_free_result($res);


    // $total_records=ceil($total_records/$number_results)+$total_records;

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



function is_position_code() {
    if (!isset($_REQUEST['query']) or !isset($_REQUEST['position_key']) ) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$_REQUEST['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $position_key=$_REQUEST['position_key'];

    $sql=sprintf("select `Company Position Key`,`Company Position Title`,`Company Position Code` from `Company Position Dimension` where `Company Position Key`=%d and  `Company Position Code`=%s  "
                 ,$position_key,prepare_mysql($query)
                );
    $res=mysql_query($sql);
//print $sql;
    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Company Position <a href="company_position.php?id=%d">%s</a> already has this code (%s)'
                     ,$data['Company Position Key']
                     ,$data['Company Position Title']
                     ,$data['Company Position Code']
                    );
        $response= array(
                       'state'=>200,
                       'found'=>1,
                       'msg'=>$msg
                   );
        echo json_encode($response);
        return;
    } else {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }
}
function is_position_name() {
    if (!isset($_REQUEST['query']) or !isset($_REQUEST['position_key']) ) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$_REQUEST['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $position_key=$_REQUEST['position_key'];

    $sql=sprintf("select `Company Position Key`,`Company Position Title`,`Company Position Code` from `Company Position Dimension` where `Company Position Key`=%d and `Company Position Title`=%s  "
                 ,$position_key
                 ,prepare_mysql($query)
                );
    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Another Company Position <a href="company_department.php?id=%d">(%s)</a> already has this name'
                     ,$data['Company Position Key']
                     ,$data['Company Position Code']
                    );
        $response= array(
                       'state'=>200,
                       'found'=>1,
                       'msg'=>$msg
                   );
        echo json_encode($response);
        return;
    } else {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

}

function list_customer_categories() {
    $conf=$_SESSION['state']['customer_categories']['subcategories'];
    $conf2=$_SESSION['state']['customer_categories'];
    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr'];
        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }

    } else
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
    if (isset( $_REQUEST['where']))
        $where=addslashes($_REQUEST['where']);
    else
        $where=$conf['where'];


    if (isset( $_REQUEST['exchange_type'])) {
        $exchange_type=addslashes($_REQUEST['exchange_type']);
        $_SESSION['state']['customer_categories']['exchange_type']=$exchange_type;
    } else
        $exchange_type=$conf2['exchange_type'];

    if (isset( $_REQUEST['exchange_value'])) {
        $exchange_value=addslashes($_REQUEST['exchange_value']);
        $_SESSION['state']['customer_categories']['exchange_value']=$exchange_value;
    } else
        $exchange_value=$conf2['exchange_value'];

    if (isset( $_REQUEST['show_default_currency'])) {
        $show_default_currency=addslashes($_REQUEST['show_default_currency']);
        $_SESSION['state']['customer_categories']['show_default_currency']=$show_default_currency;
    } else
        $show_default_currency=$conf2['show_default_currency'];




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


    if (isset( $_REQUEST['percentages'])) {
        $percentages=$_REQUEST['percentages'];
        $_SESSION['state']['customer_categories']['percentages']=$percentages;
    } else
        $percentages=$_SESSION['state']['customer_categories']['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];
        $_SESSION['state']['customer_categories']['period']=$period;
    } else
        $period=$_SESSION['state']['customer_categories']['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];
        $_SESSION['state']['customer_categories']['avg']=$avg;
    } else
        $avg=$_SESSION['state']['customer_categories']['avg'];

    if (isset( $_REQUEST['stores_mode'])) {
        $stores_mode=$_REQUEST['stores_mode'];
        $_SESSION['state']['customer_categories']['stores_mode']=$stores_mode;
    } else
        $stores_mode=$_SESSION['state']['product_categories']['stores_mode'];

    $_SESSION['state']['customer_categories']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
    // print_r($_SESSION['tables']['families_list']);

    //  print_r($_SESSION['tables']['families_list']);

    if (isset( $_REQUEST['category'])) {
        $root_category=$_REQUEST['category'];
        $_SESSION['state']['customer_categories']['category']=$avg;
    } else
        $root_category=$_SESSION['state']['customer_categories']['category_key'];



    $store_key=$_SESSION['state']['store']['id'];

    $where=sprintf("where `Category Subject`='Customer' and  `Category Parent Key`=%d and `Category Store Key`=%d",$root_category,$store_key);
    //  $where=sprintf("where `Category Subject`='Product'  ");

    if ($stores_mode=='grouped')
        $group=' group by S.`Category Key`';
    else
        $group='';

    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Category Name` like '%".addslashes($f_value)."%'";




    $sql="select count(*) as total   from `Category Dimension`   $where $wheref";

//$sql=" describe `Category Dimension`;";
// $sql="select *  from `Category Dimension` where `Category Parent Key`=1 ";
//print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_assoc($res)) {
        $total=$row['total'];
//   print_r($row);
    }
    mysql_free_result($res);

//exit;
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total  from `Category Dimension` S  left join `Category Bridge` CB on (CB.`Category Key`=S.`Category Key`)  left join `Customer Dimension` CD on (CD.`Customer Key`=CB.`Subject Key`) $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('category','categories',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {

        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any category with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {

        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('categories with name like')." <b>*".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;

    if ($order=='families')
        $order='`Product Category Families`';
    elseif($order=='departments')
    $order='`Product Category Departments`';
    elseif($order=='code')
    $order='`Product Category Code`';
    elseif($order=='todo')
    $order='`Product Category In Process Products`';
    elseif($order=='discontinued')
    $order='`Product Category In Process Products`';
    else if ($order=='profit') {
        if ($period=='all')
            $order='`Product Category Total Profit`';
        elseif($period=='year')
        $order='`Product Category 1 Year Acc Profit`';
        elseif($period=='quarter')
        $order='`Product Category 1 Quarter Acc Profit`';
        elseif($period=='month')
        $order='`Product Category 1 Month Acc Profit`';
        elseif($period=='week')
        $order='`Product Category 1 Week Acc Profit`';
    }
    elseif($order=='sales') {
        if ($period=='all')
            $order='`Product Category Total Invoiced Amount`';
        elseif($period=='year')
        $order='`Product Category 1 Year Acc Invoiced Amount`';
        elseif($period=='quarter')
        $order='`Product Category 1 Quarter Acc Invoiced Amount`';
        elseif($period=='month')
        $order='`Product Category 1 Month Acc Invoiced Amount`';
        elseif($period=='week')
        $order='`Product Category 1 Week Acc Invoiced Amount`';

    }
    elseif($order=='name')
    $order='`Category Name`';
    elseif($order=='active')
    $order='`Product Category For Public Sale Products`';
    elseif($order=='outofstock')
    $order='`Product Category Out Of Stock Products`';
    elseif($order=='stock_error')
    $order='`Product Category Unknown Stock Products`';
    elseif($order=='surplus')
    $order='`Product Category Surplus Availability Products`';
    elseif($order=='optimal')
    $order='`Product Category Optimal Availability Products`';
    elseif($order=='low')
    $order='`Product Category Low Availability Products`';
    elseif($order=='critical')
    $order='`Product Category Critical Availability Products`';





    $sql="select S.`Category Key`, `Category Name` from `Category Dimension` S  left join `Category Bridge` CB on (CB.`Category Key`=S.`Category Key`)  left join `Customer Dimension` CD on (CD.`Customer Key`=CB.`Subject Key`)  $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";
// print $sql;
    $res = mysql_query($sql);

    $total=mysql_num_rows($res);
    $adata=array();
    $sum_sales=0;
    $sum_profit=0;
    $sum_outofstock=0;
    $sum_low=0;
    $sum_optimal=0;
    $sum_critical=0;
    $sum_surplus=0;
    $sum_unknown=0;
    $sum_departments=0;
    $sum_families=0;
    $sum_todo=0;
    $sum_discontinued=0;

    $DC_tag='';
    if ($exchange_type=='day2day' and $show_default_currency  )
        $DC_tag=' DC';

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        //$name=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Category Key'],$row['Product Category Name']);
        //$code=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Category Key'],$row['Product Category Code']);

        /*       if ($percentages) {
                   if ($period=='all') {
                       $tsall=percentage($row['Product Category DC Total Invoiced Amount'],$sum_total_sales,2);
                       if ($row['Product Category DC Total Profit']>=0)
                           $tprofit=percentage($row['Product Category DC Total Profit'],$sum_total_profit_plus,2);
                       else
                           $tprofit=percentage($row['Product Category DC Total Profit'],$sum_total_profit_minus,2);
                   }
                   elseif($period=='year') {
                       $tsall=percentage($row['Product Category DC 1 Year Acc Invoiced Amount'],$sum_total_sales,2);
                       if ($row['Product Category DC 1 Year Acc Profit']>=0)
                           $tprofit=percentage($row['Product Category DC 1 Year Acc Profit'],$sum_total_profit_plus,2);
                       else
                           $tprofit=percentage($row['Product Category DC 1 Year Acc Profit'],$sum_total_profit_minus,2);
                   }
                   elseif($period=='quarter') {
                       $tsall=percentage($row['Product Category DC 1 Quarter Acc Invoiced Amount'],$sum_total_sales,2);
                       if ($row['Product Category DC 1 Quarter Acc Profit']>=0)
                           $tprofit=percentage($row['Product Category DC 1 Quarter Acc Profit'],$sum_total_profit_plus,2);
                       else
                           $tprofit=percentage($row['Product Category DC 1 Quarter Acc Profit'],$sum_total_profit_minus,2);
                   }
                   elseif($period=='month') {
                       $tsall=percentage($row['Product Category DC 1 Month Acc Invoiced Amount'],$sum_total_sales,2);
                       if ($row['Product Category DC 1 Month Acc Profit']>=0)
                           $tprofit=percentage($row['Product Category DC 1 Month Acc Profit'],$sum_total_profit_plus,2);
                       else
                           $tprofit=percentage($row['Product Category DC 1 Month Acc Profit'],$sum_total_profit_minus,2);
                   }
                   elseif($period=='week') {
                       $tsall=percentage($row['Product Category DC 1 Week Acc Invoiced Amount'],$sum_total_sales,2);
                       if ($row['Product Category DC 1 Week Acc Profit']>=0)
                           $tprofit=percentage($row['Product Category DC 1 Week Acc Profit'],$sum_total_profit_plus,2);
                       else
                           $tprofit=percentage($row['Product Category DC 1 Week Acc Profit'],$sum_total_profit_minus,2);
                   }


               } else {






                   if ($period=="all") {


                       if ($avg=="totals")
                           $factor=1;
                       elseif($avg=="month") {
                           if ($row["Product Category".$DC_tag." Total Days On Sale"]>0)
                               $factor=30.4368499/$row["Product Category".$DC_tag." Total Days On Sale"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="week") {
                           if ($row["Product Category".$DC_tag." Total Days On Sale"]>0)
                               $factor=7/$row["Product Category".$DC_tag." Total Days On Sale"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="month_eff") {
                           if ($row["Product Category".$DC_tag." Total Days Available"]>0)
                               $factor=30.4368499/$row["Product Category".$DC_tag." Total Days Available"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="week_eff") {
                           if ($row["Product Category".$DC_tag." Total Days Available"]>0)
                               $factor=7/$row["Product Category".$DC_tag." Total Days Available"];
                           else
                               $factor=0;
                       }

                       $tsall=($row["Product Category".$DC_tag." Total Invoiced Amount"]*$factor);
                       $tprofit=($row["Product Category".$DC_tag." Total Profit"]*$factor);




                   }
                   elseif($period=="year") {


                       if ($avg=="totals")
                           $factor=1;
                       elseif($avg=="month") {
                           if ($row["Product Category".$DC_tag." 1 Year Acc Days On Sale"]>0)
                               $factor=30.4368499/$row["Product Category".$DC_tag." 1 Year Acc Days On Sale"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="month") {
                           if ($row["Product Category".$DC_tag." 1 Year Acc Days On Sale"]>0)
                               $factor=30.4368499/$row["Product Category".$DC_tag." 1 Year Acc Days On Sale"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="week") {
                           if ($row["Product Category".$DC_tag." 1 Year Acc Days On Sale"]>0)
                               $factor=7/$row["Product Category".$DC_tag." 1 Year Acc Days On Sale"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="month_eff") {
                           if ($row["Product Category".$DC_tag." 1 Year Acc Days Available"]>0)
                               $factor=30.4368499/$row["Product Category".$DC_tag." 1 Year Acc Days Available"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="week_eff") {
                           if ($row["Product Category".$DC_tag." 1 Year Acc Days Available"]>0)
                               $factor=7/$row["Product Category".$DC_tag." 1 Year Acc Days Available"];
                           else
                               $factor=0;
                       }









                       $tsall=($row["Product Category".$DC_tag." 1 Year Acc Invoiced Amount"]*$factor);
                       $tprofit=($row["Product Category".$DC_tag." 1 Year Acc Profit"]*$factor);
                   }
                   elseif($period=="quarter") {
                       if ($avg=="totals")
                           $factor=1;
                       elseif($avg=="month") {
                           if ($row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"]>0)
                               $factor=30.4368499/$row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="month") {
                           if ($row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"]>0)
                               $factor=30.4368499/$row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="week") {
                           if ($row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"]>0)
                               $factor=7/$row["Product Category".$DC_tag." 1 Quarter Acc Days On Sale"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="month_eff") {
                           if ($row["Product Category".$DC_tag." 1 Quarter Acc Days Available"]>0)
                               $factor=30.4368499/$row["Product Category".$DC_tag." 1 Quarter Acc Days Available"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="week_eff") {
                           if ($row["Product Category".$DC_tag." 1 Quarter Acc Days Available"]>0)
                               $factor=7/$row["Product Category".$DC_tag." 1 Quarter Acc Days Available"];
                           else
                               $factor=0;
                       }


                       $tsall=($row["Product Category".$DC_tag." 1 Quarter Acc Invoiced Amount"]*$factor);
                       $tprofit=($row["Product Category".$DC_tag." 1 Quarter Acc Profit"]*$factor);
                   }
                   elseif($period=="month") {
                       if ($avg=="totals")
                           $factor=1;
                       elseif($avg=="month") {
                           if ($row["Product Category".$DC_tag." 1 Month Acc Days On Sale"]>0)
                               $factor=30.4368499/$row["Product Category".$DC_tag." 1 Month Acc Days On Sale"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="month") {
                           if ($row["Product Category".$DC_tag." 1 Month Acc Days On Sale"]>0)
                               $factor=30.4368499/$row["Product Category".$DC_tag." 1 Month Acc Days On Sale"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="week") {
                           if ($row["Product Category".$DC_tag." 1 Month Acc Days On Sale"]>0)
                               $factor=7/$row["Product Category".$DC_tag." 1 Month Acc Days On Sale"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="month_eff") {
                           if ($row["Product Category".$DC_tag." 1 Month Acc Days Available"]>0)
                               $factor=30.4368499/$row["Product Category".$DC_tag." 1 Month Acc Days Available"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="week_eff") {
                           if ($row["Product Category".$DC_tag." 1 Month Acc Days Available"]>0)
                               $factor=7/$row["Product Category".$DC_tag." 1 Month Acc Days Available"];
                           else
                               $factor=0;
                       }


                       $tsall=($row["Product Category".$DC_tag." 1 Month Acc Invoiced Amount"]*$factor);
                       $tprofit=($row["Product Category".$DC_tag." 1 Month Acc Profit"]*$factor);
                   }
                   elseif($period=="week") {
                       if ($avg=="totals")
                           $factor=1;
                       elseif($avg=="month") {
                           if ($row["Product Category".$DC_tag." 1 Week Acc Days On Sale"]>0)
                               $factor=30.4368499/$row["Product Category".$DC_tag." 1 Week Acc Days On Sale"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="month") {
                           if ($row["Product Category".$DC_tag." 1 Week Acc Days On Sale"]>0)
                               $factor=30.4368499/$row["Product Category".$DC_tag." 1 Week Acc Days On Sale"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="week") {
                           if ($row["Product Category".$DC_tag." 1 Week Acc Days On Sale"]>0)
                               $factor=7/$row["Product Category".$DC_tag." 1 Week Acc Days On Sale"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="month_eff") {
                           if ($row["Product Category".$DC_tag." 1 Week Acc Days Available"]>0)
                               $factor=30.4368499/$row["Product Category".$DC_tag." 1 Week Acc Days Available"];
                           else
                               $factor=0;
                       }
                       elseif($avg=="week_eff") {
                           if ($row["Product Category".$DC_tag." 1 Week Acc Days Available"]>0)
                               $factor=7/$row["Product Category".$DC_tag." 1 Week Acc Days Available"];
                           else
                               $factor=0;
                       }


                       $tsall=($row["Product Category".$DC_tag." 1 Week Acc Invoiced Amount"]*$factor);
                       $tprofit=($row["Product Category".$DC_tag." 1 Week Acc Profit"]*$factor);
                   }



               }

               $sum_sales+=$tsall;
               $sum_profit+=$tprofit;
               $sum_low+=$row['Product Category Low Availability Products'];
               $sum_optimal+=$row['Product Category Optimal Availability Products'];
               $sum_low+=$row['Product Category Low Availability Products'];
               $sum_critical+=$row['Product Category Critical Availability Products'];
               $sum_surplus+=$row['Product Category Surplus Availability Products'];
               $sum_outofstock+=$row['Product Category Out Of Stock Products'];
               $sum_unknown+=$row['Product Category Unknown Stock Products'];
               $sum_departments+=$row['Product Category Departments'];
               $sum_families+=$row['Product Category Families'];
               $sum_todo+=$row['Product Category In Process Products'];
               $sum_discontinued+=$row['Product Category Discontinued Products'];


               if (!$percentages) {
                   if ($show_default_currency) {
                       $class='';
                       if ($myconf['currency_code']!=$row['Product Category Currency Code'])
                           $class='currency_exchanged';


                       $sales='<span class="'.$class.'">'.money($tsall).'</span>';
                       $profit='<span class="'.$class.'">'.money($tprofit).'</span>';
                   } else {
                       $sales=money($tsall,$row['Product Category Currency Code']);
                       $profit=money($tprofit,$row['Product Category Currency Code']);
                   }
               } else {
                   $sales=$tsall;
                   $profit=$tprofit;
               }
        */

        if ($stores_mode=='grouped')
            $name=sprintf('<a href="customer_categories.php?id=%d">%s</a>',$row['Category Key'],$row['Category Name']);
        else
            $name=$row['Category Key'].' '.$row['Category Name']." (".$row['Category Store Key'].")";
        $adata[]=array(
                     //'go'=>sprintf("<a href='edit_category.php?edit=1&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Category Key']),
                     'id'=>$row['Category Key'],
                     'name'=>$name,

                     /*  'departments'=>number($row['Product Category Departments']),
                       'families'=>number($row['Product Category Families']),
                       'active'=>number($row['Product Category For Public Sale Products']),
                       'todo'=>number($row['Product Category In Process Products']),
                       'discontinued'=>number($row['Product Category Discontinued Products']),
                       'outofstock'=>number($row['Product Category Out Of Stock Products']),
                       'stock_error'=>number($row['Product Category Unknown Stock Products']),
                       'stock_value'=>money($row['Product Category Stock Value']),
                       'surplus'=>number($row['Product Category Surplus Availability Products']),
                       'optimal'=>number($row['Product Category Optimal Availability Products']),
                       'low'=>number($row['Product Category Low Availability Products']),
                       'critical'=>number($row['Product Category Critical Availability Products']),
                       'sales'=>$sales,
                       'profit'=>$profit*/


                 );
    }
    mysql_free_result($res);

    /*  if ($percentages) { */
    /*         $sum_sales='100.00%'; */
    /*         $sum_profit='100.00%'; */
    /* //       $sum_low= */
    /* //   $sum_optimal=$row['Product Category Optimal Availability Products']; */
    /* //   $sum_low=$row['Product Category Low Availability Products']; */
    /* //   $sum_critical=$row['Product Category Critical Availability Products']; */
    /* //   $sum_surplus=$row['Product Category Surplus Availability Products']; */
    /*     } else { */
    /*         $sum_sales=money($sum_total_sales); */
    /*         $sum_profit=money($sum_total_profit); */
    /*     } */

    /*     $sum_outofstock=number($sum_outofstock); */
    /*     $sum_low=number($sum_low); */
    /*     $sum_optimal=number($sum_optimal); */
    /*     $sum_critical=number($sum_critical); */
    /*     $sum_surplus=number($sum_surplus); */
    /*     $sum_unknown=number($sum_unknown); */
    /*     $sum_departments=number($sum_departments); */
    /*     $sum_families=number($sum_families); */
    /*     $sum_todo=number($sum_todo); */
    /*     $sum_discontinued=number($sum_discontinued); */
    /*     $adata[]=array( */

    /*                  'name'=>_('Total'), */
    /*                  'active'=>number($sum_active), */
    /*                  'sales'=>$sum_sales, */
    /*                  'profit'=>$sum_profit, */
    /*                  'todo'=>$sum_todo, */
    /*                  'discontinued'=>$sum_discontinued, */
    /*                  'low'=>$sum_low, */
    /*                  'critical'=>$sum_critical, */
    /*                  'surplus'=>$sum_surplus, */
    /*                  'optimal'=>$sum_optimal, */
    /*                  'outofstock'=>$sum_outofstock, */
    /*                  'stock_error'=>$sum_unknown, */
    /*                  'departments'=>$sum_departments, */
    /*                  'families'=>$sum_families */
    /*              ); */


    // if($total<$number_results)
    //  $rtext=$total.' '.ngettext('store','stores',$total);
    //else
    //  $rtext='';

    //   $total_records=ceil($total_records/$number_results)+$total_records;

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
