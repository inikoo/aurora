<?php
/*
 File: ar_assets.php

 Ajax Server Anchor for the Product,Family,Department and Part Clases

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

require_once 'common.php';
//require_once 'stock_functions.php';
require_once 'class.Store.php';

require_once 'class.Product.php';
require_once 'class.Department.php';
require_once 'class.Family.php';

require_once 'class.Order.php';
require_once 'class.Location.php';
require_once 'class.PartLocation.php';
//require_once 'common_functions.php';
require_once 'ar_common.php';

if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('customers_who_order_product'):
    list_customers_who_order_product();
    break;
case('products_lists'):
    list_products_lists();
    break;
case('parts_lists'):
    list_parts_lists();
    break;
case('new_list'):

    $data=prepare_values($_REQUEST,array(
                             'awhere'=>array('type'=>'json array'),
                             'store_id'=>array('type'=>'key'),
                             'list_name'=>array('type'=>'string'),
                             'list_type'=>array('type'=>'enum',
                                                'valid values regex'=>'/static|Dynamic/i'
                                               )
                         ));


    new_products_list($data);
    break;
case('new_parts_list'):

    $data=prepare_values($_REQUEST,array(
                             'awhere'=>array('type'=>'json array'),
                             //'store_id'=>array('type'=>'key'),
                             'list_name'=>array('type'=>'string'),
                             'list_type'=>array('type'=>'enum',
                                                'valid values regex'=>'/static|Dynamic/i'
                                               )
                         ));


    new_parts_list($data);
    break;
case('is_valid_family_code'):


    $family_code=$_REQUEST['code'];

    if (isset($_REQUEST['code'])!="") {
        $sql=sprintf("select * from `Product Family Dimension` where `Product Family Code`=%s  order by `Product Family Key`  ",prepare_mysql($family_code));
        //print($sql);

        $res=mysql_query($sql);
        $count=mysql_num_rows($res);
        if ($count==0) {
            $response= array('state'=>400,'found'=>'no','msg'=>_("You have entered unexisting family"));
            echo json_encode($response);
            exit;

        } else {
            $response= array('state'=>200,'found'=>'yes','msg'=>_("Family found"));
            echo json_encode($response);
            exit;
        }
    }
    break;


case('is_store_code'):
    $data=prepare_values($_REQUEST,array(
                             'query'=>array('type'=>'string')
                         ));
    is_store_code($data);
    break;
case('is_department_code'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                             'query'=>array('type'=>'string')
                         ));
    is_department_code($data);
    break;
case('is_family_code'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                             'query'=>array('type'=>'string')
                         ));
    is_family_code($data);
    break;
case('is_store_name'):
    $data=prepare_values($_REQUEST,array(
                             'query'=>array('type'=>'string')
                         ));
    is_store_name($data);
    break;
case('is_family_name'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                             'query'=>array('type'=>'string')
                         ));
    is_family_name($data);
    break;
case('is_department_name'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                             'query'=>array('type'=>'string')
                         ));
    is_department_name($data);
    break;
case('is_family_special_char'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                             'query'=>array('type'=>'string')
                         ));
    is_family_special_char($data);
    break;

case('is_product_code'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                             'query'=>array('type'=>'string')
                         ));
    is_product_code($data);
    break;
case('is_product_name'):
    $data=prepare_values($_REQUEST,array(
                             'store_key'=>array('type'=>'key'),
                             'query'=>array('type'=>'string')
                         ));
    is_product_name($data);
    break;

case('is_product_special_char'):
    $data=prepare_values($_REQUEST,array(
                             'family_key'=>array('type'=>'key'),
                             'query'=>array('type'=>'string')
                         ));
    is_product_special_char($data);
    break;

case('charges'):
    list_charges();
    break;
case('campaigns'):
    list_campaigns();
    break;
case('deals'):
    list_deals();
    break;
case('product_server'):
    list_products_with_same_code();
    break;
case('customers_per_store'):
    list_customers_per_store();
    break;
case('marketing_per_store'):
    list_marketing_per_store();
    break;
case('orders_per_store'):
    list_orders_per_store();
    break;
case('invoices_per_store'):
    list_invoices_per_store();
    break;
case('delivery_notes_per_store'):
    list_delivery_notes_per_store();
    break;
case('product_code_timeline'):
    product_code_timeline();
    break;
case('product_categories'):
    list_product_categories();
    break;
case('part_transactions'):
    part_transactions();
    break;
case('find_part'):
    find_part();
    break;
case('families'):
    list_families();
    break;
case('stores'):
    list_stores();
    break;
case('departments'):
    list_departments();
    break;
case('product'):
case('products'):
    list_products();
    break;
case('parts'):
    list_parts();
    break;
case('plot_daily_part_stock_history'):
    plot_daily_part_stock_history();
    break;
case('part_stock_history'):
case('stock_history'):
    part_stock_history();
    break;
case('part_location_info'):
    $data=prepare_values($_REQUEST,array(
                             'sku'=>array('type'=>'key'),
                         ));
    part_location_info($data);
    break;
default:

    $response=array('state'=>404,'msg'=>_('Operation not found'));
    echo json_encode($response);

}

function plot_daily_part_stock_history() {

    if (isset($_REQUEST['sku'])) {
        $part_sku=$_REQUEST['sku'];
    } else
        $part_sku=$_SESSION['state']['part']['sku'];
    $sql=sprintf("select `Quantity Sold`,IFNULL(`Quantity On Hand`,-1) as `Quantity On Hand`,`Date` from `Inventory Spanshot Fact` where `Part SKU`=%d order by `Date`  ",$part_sku);
    $res = mysql_query($sql);
    $data=array();
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        if ($row['Quantity On Hand']<0)
            $_stock=_('Unknown');
        else
            $_stock=number($row['Quantity On Hand']);
        $data[]=array(
                    'sales'=>(float)$row['Quantity Sold']
                            ,'tip_sales'=>''
                                         ,'stock'=>(float)$row['Quantity On Hand']
                                                  ,'tip_stock'=>strftime("%e %b %Y", strtotime($row['Date']))."\n"._('Stock').":$_stock "._('Units')."\n"._('Sold').":".number($row['Quantity Sold'])." "._('Units')
                                                               ,'date'=>strftime("%e %b %Y", strtotime($row['Date']))
                );
    }
    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                     )
                   );

    echo json_encode($response);
}

function list_departments() {
//     $conf_table='store';
//       $conf=$_SESSION['state']['store']['table'];
//       $conf2=$_SESSION['state']['store'];
    global $user;

    if (isset( $_REQUEST['store']) and  is_numeric( $_REQUEST['store']))
        $store_id=$_REQUEST['store'];
    else
        $store_id=$_SESSION['state']['store']['id'];


    if (isset( $_REQUEST['parent']))
        $parent=$_REQUEST['parent'];
    else
        $parent='none';


    if ($parent=='store') {
        $conf=$_SESSION['state']['store']['departments'];

        $conf_table='store';
    }
    elseif ($parent=='none') {

        $conf=$_SESSION['state']['stores']['departments'];

        $conf_table='stores';
    }
    else {

        exit;
    }



    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr']-1;

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
    } else
        $percentages=$conf['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];
    } else
        $period=$conf['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];
    } else
        $avg=$conf['avg'];





    $_SESSION['state'][$conf_table]['departments']['order']=$order;
    $_SESSION['state'][$conf_table]['departments']['order_dir']=$order_dir;
    $_SESSION['state'][$conf_table]['departments']['nr']=$number_results;
    $_SESSION['state'][$conf_table]['departments']['sf']=$start_from;
    $_SESSION['state'][$conf_table]['departments']['where']=$where;
    $_SESSION['state'][$conf_table]['departments']['f_field']=$f_field;
    $_SESSION['state'][$conf_table]['departments']['f_value']=$f_value;

    $_SESSION['state'][$conf_table]['departments']['percentages']=$percentages;
    $_SESSION['state'][$conf_table]['departments']['period']=$period;
    $_SESSION['state'][$conf_table]['departments']['avg']=$avg;

    if (count($user->stores)==0)
        $where="where false";
    else {

        switch ($parent) {
        case('store'):
            if (in_array($store_id,$user->stores))
                $where=sprintf("where  `Product Department Store Key`=%d",$store_id);
            else
                $where=sprintf("where  false");
            break;
        default:

            $where=sprintf("where `Product Department Store Key` in (%s)",join(',',$user->stores));

        }
    }

    $filter_msg='';
    $wheref=wheref_departments($f_field,$f_value);



    $sql="select count(*) as total from `Product Department Dimension`   $where $wheref";

    $res = mysql_query($sql);
    if ($row=mysql_fetch_array($res)) {
        $total=$row['total'];
    }
    mysql_free_result($res);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Product Department Dimension`   $where ";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
            mysql_free_result($result);
        }

    }

    $rtext=$total_records." ".ngettext('department','departments',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';




    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any department with code like")." <b>".$f_value."*</b> ";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any department with this description").": <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('department with code like')." <b>".$f_value."*</b>";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('department with this description')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';




    $_dir=$order_direction;
    $_order=$order;





    //    print $period;

    $order='`Product Department Code`';
    if ($_order=='families')
        $order='`Product Department Families`';
    if ($_order=='todo')
        $order='`Product Department In Process Products`';
    if ($_order=='aws_p') {

        if ($period=='all')
            $order='`Product Department Total Avg Week Sales Per Product`';
        elseif($period=='year')
        $order='`Product Department 1 Year Acc Avg Week Sales Per Product`';
        elseif($period=='quarter')
        $order='`Product Department 1 Quarter Acc Avg Week Sales Per Product`';
        elseif($period=='month')
        $order='`Product Department 1 Month Acc Avg Week Sales Per Product`';
        elseif($period=='week')
        $order='`Product Department 1 Week Acc Avg Week Sales Per Product`';
    }
    if ($_order=='awp_p') {

        if ($period=='all')
            $order='`Product Department Total Avg Week Profit Per Product`';
        elseif($period=='year')
        $order='`Product Department 1 Year Acc Avg Week Profit Per Product`';
        elseif($period=='quarter')
        $order='`Product Department 1 Quarter Acc Avg Week Profit Per Product`';
        elseif($period=='month')
        $order='`Product Department 1 Month Acc Avg Week Profit Per Product`';
        elseif($period=='week')
        $order='`Product Department 1 Week Acc Avg Week Profit Per Product`';
    }

    if ($_order=='profit') {
        if ($period=='all')
            $order='`Product Department Total Profit`';
        elseif($period=='year')
        $order='`Product Department 1 Year Acc Profit`';
        elseif($period=='quarter')
        $order='`Product Department 1 Quarter Acc Profit`';
        elseif($period=='month')
        $order='`Product Department 1 Month Acc Profit`';
        elseif($period=='week')
        $order='`Product Department 1 Week Acc Profit`';
    }
    elseif($_order=='sales') {
        if ($period=='all')
            $order='`Product Department Total Invoiced Amount`';
        elseif($period=='year')
        $order='`Product Department 1 Year Acc Invoiced Amount`';
        elseif($period=='quarter')
        $order='`Product Department 1 Quarter Acc Invoiced Amount`';
        elseif($period=='month')
        $order='`Product Department 1 Month Acc Invoiced Amount`';
        elseif($period=='week')
        $order='`Product Department 1 Week Acc Invoiced Amount`';
// ----------------------------------------added for 3 year,yeartoday,6 month,10 day-----------------------
        elseif($period=='three_year')
        $order='`Product Department 3 Year Acc Avg Week Sales Per Product`';
        elseif($period=='yeartoday')
        $order='`Product Department YearToDay Acc Avg Week Sales Per Product`';
        elseif($period=='six_month')
        $order='`Product Department 6 Month Acc Avg Week Sales Per Product`';
        elseif($period=='ten_day')
        $order='`Product Department 10 Day Acc Avg Week Sales Per Product`';
// --------------------------------------------------------------------------------------------------------------------
    }
    elseif($_order=='name')
    $order='`Product Department Name`';
    elseif($_order=='code')
    $order='`Product Department Code`';
    elseif($_order=='active')
    $order='`Product Department For Public Sale Products`';
    elseif($_order=='outofstock')
    $order='`Product Department Out Of Stock Products`';
    elseif($_order=='stock_error')
    $order='`Product Department Unknown Stock Products`';
    elseif($_order=='surplus')
    $order='`Product Department Surplus Availability Products`';
    elseif($_order=='optimal')
    $order='`Product Department Optimal Availability Products`';
    elseif($_order=='low')
    $order='`Product Department Low Availability Products`';
    elseif($_order=='critical')
    $order='`Product Department Critical Availability Products`';
    elseif('descontinued')
    $order='`Product Department Discontinued Products`';





    $sum_families=0;
    $sum_active=0;
    $sum_discontinued=0;

    $sum_todo=0;
    $sum_outofstock=0;
    $sum_stock_error=0;
    $sum_stock_value=0;
    $sum_surplus=0;
    $sum_optimal=0;
    $sum_low=0;
    $sum_critical=0;






    $sql="select sum(`Product Department Out Of Stock Products`) outofstock,sum(`Product Department Unknown Stock Products`)stock_error,
         sum(`Product Department Stock Value`)stock_value,sum(`Product Department Surplus Availability Products`)surplus,sum(`Product Department Optimal Availability Products`) optimal,
         sum(`Product Department Low Availability Products`) low,sum(`Product Department Critical Availability Products`) critical,
         sum(`Product Department In Process Products`) as todo,sum(`Product Department For Public Sale Products`) as sum_active, sum(`Product Department Discontinued Products`) as sum_discontinued,sum(`Product Department Families`) as sum_families  from `Product Department Dimension` $where  $wheref ";
    $result=mysql_query($sql);
//print $sql;
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $sum_families=$row['sum_families'];
        $sum_active=$row['sum_active'];
        $sum_discontinued=$row['sum_discontinued'];
        $sum_todo=$row['todo'];
        $sum_outofstock=$row['outofstock'];
        $sum_stock_error=$row['stock_error'];
        $sum_stock_value=$row['stock_value'];
        $sum_surplus=$row['surplus'];
        $sum_optimal=$row['optimal'];
        $sum_low=$row['low'];
        $sum_critical=$row['critical'];
    }

    if ($period=='all') {

        //$aws_p=money($row['Product Department Total Avg Week Sales Per Product']);
        // $awp_p=money($row['Product Department Total Avg Week Profit Per Product']);

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select   max(`Product Department Total Days Available`) as 'Product Department Total Days Available',max(`Product Department Total Days On Sale`) as 'Product Department Total Days On Sale', sum(if(`Product Department Total Profit`<0,`Product Department Total Profit`,0)) as total_profit_minus,sum(if(`Product Department Total Profit`>=0,`Product Department Total Profit`,0)) as total_profit_plus,sum(`Product Department Total Invoiced Amount`) as sum_total_sales  from `Product Department Dimension` $where $wheref  ";
//print $sql;
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {



            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];

            if ($avg=='totals')
                $factor=1;
            elseif($avg=='month') {
                if ($row['Product Department Total Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department Total Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='week') {
                if ($row['Product Department Total Days On Sale']>0)
                    $factor=7/$row['Product Department Total Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month_eff') {
                if ($row['Product Department Total Days Available']>0)
                    $factor=30.4368499/$row['Product Department Total Days Available'];
                else
                    $factor=0;
            }
            elseif($avg=='week_eff') {
                if ($row['Product Department Total Days Available']>0)
                    $factor=7/$row['Product Department Total Days Available'];
                else
                    $factor=0;
            }
            $sum_total_sales=$row['sum_total_sales']*$factor;
            $sum_total_profit=$sum_total_profit*$factor;

        }
        mysql_free_result($result);
    }

// -----------------------------------------------------start for 3 year----------------------------------------------------------------------
    elseif($period=='three_year') {
        //$aws_p=money($data['Product Department 3 Year Acc Avg Week Sales Per Product']);
        //$awp_p=money($data['Product Department 3 Year Acc Avg Week Profit Per Product']);
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select max(`Product Department 3 Year Acc Days Available`) as 'Product Department 3 Year Acc Days Available',max(`Product Department 3 Year Acc Days On Sale`) as 'Product Department 3 Year Acc Days On Sale', sum(if(`Product Department 3 Year Acc Profit`<0,`Product Department 3 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Product Department 3 Year Acc Profit`>=0,`Product Department 3 Year Acc Profit`,0)) as total_profit_plus,sum(`Product Department For Public Sale Products`) as sum_active,sum(`Product Department 3 Year Acc Invoiced Amount`) as sum_total_sales  from `Product Department Dimension`  $where  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            if ($avg=='totals')
                $factor=1;
            elseif($avg=='month') {
                if ($row['Product Department 3 Year Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department 1 Year Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month') {
                if ($row['Product Department 3 Year Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department 3 Year Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='week') {
                if ($row['Product Department 3 Year Acc Days On Sale']>0)
                    $factor=7/$row['Product Department 3 Year Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month_eff') {
                if ($row['Product Department 3 Year Acc Days Available']>0)
                    $factor=30.4368499/$row['Product Department 3 Year Acc Days Available'];
                else
                    $factor=0;
            }
            elseif($avg=='week_eff') {
                if ($row['Product Department 3 Year Acc Days Available']>0)
                    $factor=7/$row['Product Department 3 Year Acc Days Available'];
                else
                    $factor=0;
            }
            $sum_total_sales=$factor*$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$factor*($row['total_profit_plus']-$row['total_profit_minus']);
        }
        mysql_free_result($result);
    }
// -----------------------------------------------------End for 3 year----------------------------------------------------



    elseif($period=='year') {
        //$aws_p=money($data['Product Department 1 Year Acc Avg Week Sales Per Product']);
        //$awp_p=money($data['Product Department 1 Year Acc Avg Week Profit Per Product']);
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select max(`Product Department 1 Year Acc Days Available`) as 'Product Department 1 Year Acc Days Available',max(`Product Department 1 Year Acc Days On Sale`) as 'Product Department 1 Year Acc Days On Sale', sum(if(`Product Department 1 Year Acc Profit`<0,`Product Department 1 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Product Department 1 Year Acc Profit`>=0,`Product Department 1 Year Acc Profit`,0)) as total_profit_plus,sum(`Product Department For Public Sale Products`) as sum_active,sum(`Product Department 1 Year Acc Invoiced Amount`) as sum_total_sales  from `Product Department Dimension`  $where  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            if ($avg=='totals')
                $factor=1;
            elseif($avg=='month') {
                if ($row['Product Department 1 Year Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department 1 Year Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month') {
                if ($row['Product Department 1 Year Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department 1 Year Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='week') {
                if ($row['Product Department 1 Year Acc Days On Sale']>0)
                    $factor=7/$row['Product Department 1 Year Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month_eff') {
                if ($row['Product Department 1 Year Acc Days Available']>0)
                    $factor=30.4368499/$row['Product Department 1 Year Acc Days Available'];
                else
                    $factor=0;
            }
            elseif($avg=='week_eff') {
                if ($row['Product Department 1 Year Acc Days Available']>0)
                    $factor=7/$row['Product Department 1 Year Acc Days Available'];
                else
                    $factor=0;
            }



            $sum_total_sales=$factor*$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$factor*($row['total_profit_plus']-$row['total_profit_minus']);












        }
        mysql_free_result($result);
    }


// -----------------------------------------------------start for yeartoday----------------------------------------------------------------------
    elseif($period=='yeartoday') {
        //$aws_p=money($data['Product Department YearToDay Acc Avg Week Sales Per Product']);
        //$awp_p=money($data['Product Department YearToDay Acc Avg Week Profit Per Product']);
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select max(`Product Department YearToDay Acc Days Available`) as 'Product Department YearToDay Acc Days Available',max(`Product Department YearToDay Acc Days On Sale`) as 'Product Department YearToDay Acc Days On Sale', sum(if(`Product Department YearToDay Acc Profit`<0,`Product Department YearToDay Acc Profit`,0)) as total_profit_minus,sum(if(`Product Department YearToDay Acc Profit`>=0,`Product Department YearToDay Acc Profit`,0)) as total_profit_plus,sum(`Product Department For Public Sale Products`) as sum_active,sum(`Product Department YearToDay Acc Invoiced Amount`) as sum_total_sales  from `Product Department Dimension`  $where  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            if ($avg=='totals')
                $factor=1;
            elseif($avg=='month') {
                if ($row['Product Department YearToDay Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department YearToDay Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month') {
                if ($row['Product Department YearToDay Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department YearToDay Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='week') {
                if ($row['Product Department YearToDay Acc Days On Sale']>0)
                    $factor=7/$row['Product Department YearToDay Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month_eff') {
                if ($row['Product Department YearToDay Acc Days Available']>0)
                    $factor=30.4368499/$row['Product Department YearToDay Acc Days Available'];
                else
                    $factor=0;
            }
            elseif($avg=='week_eff') {
                if ($row['Product Department YearToDay Acc Days Available']>0)
                    $factor=7/$row['Product Department YearToDay Acc Days Available'];
                else
                    $factor=0;
            }
            $sum_total_sales=$factor*$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$factor*($row['total_profit_plus']-$row['total_profit_minus']);
        }
        mysql_free_result($result);
    }
// -----------------------------------------------------End for yeartoday----------------------------------------------------


// -----------------------------------------------------start for 6 month----------------------------------------------------------------------
    elseif($period=='six_month') {
        //$aws_p=money($data['Product Department 6 Month Acc Avg Week Sales Per Product']);
        //$awp_p=money($data['Product Department 6 Month Acc Avg Week Profit Per Product']);
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select max(`Product Department 6 Month Acc Days Available`) as 'Product Department 6 Month Acc Days Available',max(`Product Department 6 Month Acc Days On Sale`) as 'Product Department 6 Month Acc Days On Sale', sum(if(`Product Department 6 Month Acc Profit`<0,`Product Department 6 Month Acc Profit`,0)) as total_profit_minus,sum(if(`Product Department 6 Month Acc Profit`>=0,`Product Department 6 Month Acc Profit`,0)) as total_profit_plus,sum(`Product Department For Public Sale Products`) as sum_active,sum(`Product Department 6 Month Acc Invoiced Amount`) as sum_total_sales  from `Product Department Dimension`  $where  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            if ($avg=='totals')
                $factor=1;
            elseif($avg=='month') {
                if ($row['Product Department 6 Month Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department 6 Month Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month') {
                if ($row['Product Department 6 Month Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department 6 Month Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='week') {
                if ($row['Product Department 6 Month Acc Days On Sale']>0)
                    $factor=7/$row['Product Department 6 Month Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month_eff') {
                if ($row['Product Department 6 Month Acc Days Available']>0)
                    $factor=30.4368499/$row['Product Department 6 Month Acc Days Available'];
                else
                    $factor=0;
            }
            elseif($avg=='week_eff') {
                if ($row['Product Department 6 Month Acc Days Available']>0)
                    $factor=7/$row['Product Department 6 Month Acc Days Available'];
                else
                    $factor=0;
            }
            $sum_total_sales=$factor*$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$factor*($row['total_profit_plus']-$row['total_profit_minus']);
        }
        mysql_free_result($result);
    }
// -----------------------------------------------------End for 6 month----------------------------------------------------





    elseif($period=='quarter') {
        // $aws_p=money($row['Product Department 1 Quarter Acc Avg Week Sales Per Product']);
        //$awp_p=money($row['Product Department 1 Quarter Acc Avg Week Profit Per Product']);
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select max(`Product Department 1 Quarter Acc Days Available`) as 'Product Department 1 Quarter Acc Days Available',max(`Product Department 1 Quarter Acc Days On Sale`) as 'Product Department 1 Quarter Acc Days On Sale',sum(if(`Product Department 1 Quarter Acc Profit`<0,`Product Department 1 Quarter Acc Profit`,0)) as total_profit_minus,sum(if(`Product Department 1 Quarter Acc Profit`>=0,`Product Department 1 Quarter Acc Profit`,0)) as total_profit_plus,sum(`Product Department For Public Sale Products`) as sum_active,sum(`Product Department 1 Quarter Acc Invoiced Amount`) as sum_total_sales   from `Product Department Dimension`  $where  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            if ($avg=='totals')
                $factor=1;
            elseif($avg=='month') {
                if ($row['Product Department 1 Quarter Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department 1 Quarter Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month') {
                if ($row['Product Department 1 Quarter Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department 1 Quarter Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='week') {
                if ($row['Product Department 1 Quarter Acc Days On Sale']>0)
                    $factor=7/$row['Product Department 1 Quarter Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month_eff') {
                if ($row['Product Department 1 Quarter Acc Days Available']>0)
                    $factor=30.4368499/$row['Product Department 1 Quarter Acc Days Available'];
                else
                    $factor=0;
            }
            elseif($avg=='week_eff') {
                if ($row['Product Department 1 Quarter Acc Days Available']>0)
                    $factor=7/$row['Product Department 1 Quarter Acc Days Available'];
                else
                    $factor=0;
            }



            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
        mysql_free_result($result);
    }





    elseif($period=='month') {
        //$aws_p=money($row['Product Department 1 Month Acc Avg Week Sales Per Product']);
        //$awp_p=money($row['Product Department 1 Month Acc Avg Week Profit Per Product']);

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select max(`Product Department 1 Month Acc Days Available`) as 'Product Department 1 Month Acc Days Available',max(`Product Department 1 Month Acc Days On Sale`) as 'Product Department 1 Month Acc Days On Sale',sum(if(`Product Department 1 Month Acc Profit`<0,`Product Department 1 Month Acc Profit`,0)) as total_profit_minus,sum(if(`Product Department 1 Month Acc Profit`>=0,`Product Department 1 Month Acc Profit`,0)) as total_profit_plus,sum(`Product Department For Public Sale Products`) as sum_active,sum(`Product Department 1 Month Acc Invoiced Amount`) as sum_total_sales   from `Product Department Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {


            if ($avg=='totals')
                $factor=1;
            elseif($avg=='month') {
                if ($row['Product Department 1 Month Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department 1 Month Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month') {
                if ($row['Product Department 1 Month Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department 1 Month Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='week') {
                if ($row['Product Department 1 Month Acc Days On Sale']>0)
                    $factor=7/$row['Product Department 1 Month Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month_eff') {
                if ($row['Product Department 1 Month Acc Days Available']>0)
                    $factor=30.4368499/$row['Product Department 1 Month Acc Days Available'];
                else
                    $factor=0;
            }
            elseif($avg=='week_eff') {
                if ($row['Product Department 1 Month Acc Days Available']>0)
                    $factor=7/$row['Product Department 1 Month Acc Days Available'];
                else
                    $factor=0;
            }

            $sum_total_sales=$factor*$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$factor*($row['total_profit_plus']-$row['total_profit_minus']);
        }
        mysql_free_result($result);
    }


// -----------------------------------------------------start for 10 days----------------------------------------------------------------------
    elseif($period=='ten_day') {
        //$aws_p=money($data['Product Department 10 Day Acc Avg Week Sales Per Product']);
        //$awp_p=money($data['Product Department 10 Day Acc Avg Week Profit Per Product']);
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select max(`Product Department 10 Day Acc Days Available`) as 'Product Department 10 Day Acc Days Available',max(`Product Department 10 Day Acc Days On Sale`) as 'Product Department 10 Day Acc Days On Sale', sum(if(`Product Department 10 Day Acc Profit`<0,`Product Department 10 Day Acc Profit`,0)) as total_profit_minus,sum(if(`Product Department 10 Day Acc Profit`>=0,`Product Department 10 Day Acc Profit`,0)) as total_profit_plus,sum(`Product Department For Public Sale Products`) as sum_active,sum(`Product Department 10 Day Acc Invoiced Amount`) as sum_total_sales  from `Product Department Dimension`  $where  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            if ($avg=='totals')
                $factor=1;
            elseif($avg=='month') {
                if ($row['Product Department 10 Day Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department 10 Day Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month') {
                if ($row['Product Department 10 Day Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department 10 Day Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='week') {
                if ($row['Product Department 10 Day Acc Days On Sale']>0)
                    $factor=7/$row['Product Department 10 Day Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month_eff') {
                if ($row['Product Department 10 Day Acc Days Available']>0)
                    $factor=30.4368499/$row['Product Department 10 Day Acc Days Available'];
                else
                    $factor=0;
            }
            elseif($avg=='week_eff') {
                if ($row['Product Department 10 Day Acc Days Available']>0)
                    $factor=7/$row['Product Department 10 Day Acc Days Available'];
                else
                    $factor=0;
            }
            $sum_total_sales=$factor*$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$factor*($row['total_profit_plus']-$row['total_profit_minus']);
        }
        mysql_free_result($result);
    }
// -----------------------------------------------------End for 10 days------------------------------------------------




    elseif($period=='week') {
        //$aws_p=money($row['Product Department 1 Week Acc Avg Week Sales Per Product']);
        //$awp_p=money($row['Product Department 1 Week Acc Avg Week Profit Per Product']);
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select max(`Product Department 1 Week Acc Days Available`) as 'Product Department 1 Week Acc Days Available',max(`Product Department 1 Week Acc Days On Sale`) as 'Product Department 1 Week Acc Days On Sale',sum(if(`Product Department 1 Week Acc Profit`<0,`Product Department 1 Week Acc Profit`,0)) as total_profit_minus,sum(if(`Product Department 1 Week Acc Profit`>=0,`Product Department 1 Week Acc Profit`,0)) as total_profit_plus,sum(`Product Department For Public Sale Products`) as sum_active,sum(`Product Department 1 Week Acc Invoiced Amount`) as sum_total_sales   from `Product Department Dimension`  $where  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {



            if ($avg=='totals')
                $factor=1;
            elseif($avg=='month') {
                if ($row['Product Department 1 Week Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department 1 Week Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month') {
                if ($row['Product Department 1 Week Acc Days On Sale']>0)
                    $factor=30.4368499/$row['Product Department 1 Week Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='week') {
                if ($row['Product Department 1 Week Acc Days On Sale']>0)
                    $factor=7/$row['Product Department 1 Week Acc Days On Sale'];
                else
                    $factor=0;
            }
            elseif($avg=='month_eff') {
                if ($row['Product Department 1 Week Acc Days Available']>0)
                    $factor=30.4368499/$row['Product Department 1 Week Acc Days Available'];
                else
                    $factor=0;
            }
            elseif($avg=='week_eff') {
                if ($row['Product Department 1 Week Acc Days Available']>0)
                    $factor=7/$row['Product Department 1 Week Acc Days Available'];
                else
                    $factor=0;
            }


            $sum_total_sales=$factor*$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$factor*($row['total_profit_plus']-$row['total_profit_minus']);
        }
        mysql_free_result($result);
    }



    $sql="select *  from `Product Department Dimension` $where $wheref order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);
    $adata=array();
//  print "$sql";
    global $myconf;
    $currency_code=$myconf['currency_code'];
    $sum_active=0;
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $currency_code=$row['Product Department Currency Code'];
        $code=sprintf('<a href="department.php?id=%d">%s</a>',$row['Product Department Key'],$row['Product Department Code']);
        $name=sprintf('<a href="department.php?id=%d">%s</a>',$row['Product Department Key'],$row['Product Department Name']);
        $store=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Department Store Key'],$row['Product Department Store Code']);

        switch ($row['Product Department Sales Type']) {
        case 'Public Sale':
            $sales_type=_('Public Sale');
            break;
        case 'Private Sale':
            $sales_type=_('Private Sale');
            break;
        case 'Not for Sale':
            $sales_type=_('Not for Sale');
            break;
        }



        if ($period=='all') {
            $aws_p=money($row['Product Department Total Avg Week Sales Per Product']);
            $awp_p=money($row['Product Department Total Avg Week Profit Per Product']);
        }
        elseif($period=='year') {
            $aws_p=money($row['Product Department 1 Year Acc Avg Week Sales Per Product']);
            $awp_p=money($row['Product Department 1 Year Acc Avg Week Profit Per Product']);
        }
        elseif($period=='quarter') {
            $aws_p=money($row['Product Department 1 Quarter Acc Avg Week Sales Per Product']);
            $awp_p=money($row['Product Department 1 Quarter Acc Avg Week Profit Per Product']);
        }
        elseif($period=='month') {
            $aws_p=money($row['Product Department 1 Month Acc Avg Week Sales Per Product']);
            $awp_p=money($row['Product Department 1 Month Acc Avg Week Profit Per Product']);
        }
        elseif($period=='week') {
            $aws_p=money($row['Product Department 1 Week Acc Avg Week Sales Per Product']);
            $awp_p=money($row['Product Department 1 Week Acc Avg Week Profit Per Product']);
        }
// -----------------------------------Added for 3 year, yeartoday, 6 Month, 10 Days-------------------
        elseif($period=='three_year') {
            $aws_p=money($row['Product Department Total Avg Week Sales Per Product']);
            $awp_p=money($row['Product Department Total Avg Week Profit Per Product']);
        }
        elseif($period=='yeartoday') {
            $aws_p=money($row['Product Department 1 Year Acc Avg Week Sales Per Product']);
            $awp_p=money($row['Product Department 1 Year Acc Avg Week Profit Per Product']);
        }
        elseif($period=='six_month') {
            $aws_p=money($row['Product Department 1 Quarter Acc Avg Week Sales Per Product']);
            $awp_p=money($row['Product Department 1 Quarter Acc Avg Week Profit Per Product']);
        }
        elseif($period=='ten_day') {
            $aws_p=money($row['Product Department 1 Week Acc Avg Week Sales Per Product']);
            $awp_p=money($row['Product Department 1 Week Acc Avg Week Profit Per Product']);
        }
// ------------------------------------------------------------------------------------------------------------


        if ($percentages) {
            $families=percentage($row['Product Department Families'],$sum_families);

            $todo=percentage($row['Product Department In Process Products'],$sum_todo);
            $active=percentage($row['Product Department For Public Sale Products'],$sum_active);
            $discontinued=percentage($row['Product Department Discontinued Products'],$sum_discontinued);
            $outofstock=percentage($row['Product Department Out Of Stock Products'],$sum_outofstock);
            $stock_error=percentage($row['Product Department Unknown Stock Products'],$sum_stock_error);
            $stock_value=money($row['Product Department Stock Value'],$sum_stock_value);
            $surplus=percentage($row['Product Department Surplus Availability Products'],$sum_surplus);
            $optimal=percentage($row['Product Department Optimal Availability Products'],$sum_optimal);
            $low=percentage($row['Product Department Low Availability Products'],$sum_low);
            $critical=percentage($row['Product Department Critical Availability Products'],$sum_critical);




            if ($period=='all') {
                $tsall=percentage($row['Product Department Total Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Department Total Profit']>=0)
                    $tprofit=percentage($row['Product Department Total Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Department Total Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='year') {
                $tsall=percentage($row['Product Department 1 Year Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Department 1 Year Acc Profit']>=0)
                    $tprofit=percentage($row['Product Department 1 Year Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Department 1 Year Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='quarter') {
                $tsall=percentage($row['Product Department 1 Quarter Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Department 1 Quarter Acc Profit']>=0)
                    $tprofit=percentage($row['Product Department 1 Quarter Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Department 1 Quarter Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='month') {
                $tsall=percentage($row['Product Department 1 Month Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Department 1 Month Acc Profit']>=0)
                    $tprofit=percentage($row['Product Department 1 Month Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Department 1 Month Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='week') {
                $tsall=percentage($row['Product Department 1 Week Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Department 1 Week Acc Profit']>=0)
                    $tprofit=percentage($row['Product Department 1 Week Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Department 1 Week Acc Profit'],$sum_total_profit_minus,2);
            }

// --------------------------------------------------Added for 3 year,yeartoday,6 month, 10 days---------------

            elseif($period=='three_year') {
                $tsall=percentage($row['Product Department 3 Year Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Department 3 Year Acc Profit']>=0)
                    $tprofit=percentage($row['Product Department 3 Year Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Department 3 Year Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='yeartoday') {
                $tsall=percentage($row['Product Department YearToDay Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Department YearToDay Acc Profit']>=0)
                    $tprofit=percentage($row['Product Department YearToDay Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Department YearToDay Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='six_month') {
                $tsall=percentage($row['Product Department 6 Month Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Department 6 Month Acc Profit']>=0)
                    $tprofit=percentage($row['Product Department 6 Month Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Department 6 Month Acc Profit'],$sum_total_profit_minus,2);
            }

            elseif($period=='ten_day') {
                $tsall=percentage($row['Product Department 10 Day Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Department 10 Day Acc Profit']>=0)
                    $tprofit=percentage($row['Product Department 10 Day Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Department 10 Day Acc Profit'],$sum_total_profit_minus,2);
            }
// ----------------------------------------------------------------------------------------------------------------------------



        } else {// totals
            if ($period=='all') {
                if ($avg=='totals') {
                    $factor=1;
                }
                elseif($avg=='month') {
                    if ($row['Product Department Total Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department Total Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department Total Days On Sale']>0)
                        $factor=7/$row['Product Department Total Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department Total Days Available']>0)
                        $factor=30.4368499/$row['Product Department Total Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department Total Days Available']>0)
                        $factor=7/$row['Product Department Total Days Available'];
                    else
                        $factor=0;
                }

                $tsall=$row['Product Department Total Invoiced Amount']*$factor;
                $tprofit=$row['Product Department Total Profit']*$factor;

//print ($row['Product Department Total Days On Sale']/30/12)."\n";


            }



// -------------------------------------------------- start for 3 year--------------------------------------------
            elseif($period=='three_year') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department 3 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 3 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department 3 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 3 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department 3 Year Acc Days On Sale']>0)
                        $factor=7/$row['Product Department 3 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department 3 Year Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department 3 Year Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department 3 Year Acc Days Available']>0)
                        $factor=7/$row['Product Department 3 Year Acc Days Available'];
                    else
                        $factor=0;
                }
                $tsall=$row['Product Department 3 Year Acc Invoiced Amount']*$factor;
                $tprofit=$row['Product Department 3 Year Acc Profit']*$factor;
            }
// --------------------------------------------------- end for 3 year-------------------------------------------




            elseif($period=='year') {


                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department 1 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department 1 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department 1 Year Acc Days On Sale']>0)
                        $factor=7/$row['Product Department 1 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department 1 Year Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department 1 Year Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department 1 Year Acc Days Available']>0)
                        $factor=7/$row['Product Department 1 Year Acc Days Available'];
                    else
                        $factor=0;
                }









                $tsall=$row['Product Department 1 Year Acc Invoiced Amount']*$factor;
                $tprofit=$row['Product Department 1 Year Acc Profit']*$factor;
            }


// -------------------------------------------------- start for yeartoday--------------------------------------------
            elseif($period=='yeartoday') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department YearToDay Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department YearToDay Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department YearToDay Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department YearToDay Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department YearToDay Acc Days On Sale']>0)
                        $factor=7/$row['Product Department YearToDay Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department YearToDay Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department YearToDay Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department YearToDay Acc Days Available']>0)
                        $factor=7/$row['Product Department YearToDay Acc Days Available'];
                    else
                        $factor=0;
                }
                $tsall=$row['Product Department YearToDay Acc Invoiced Amount']*$factor;
                $tprofit=$row['Product Department YearToDay Acc Profit']*$factor;
            }
// --------------------------------------------------- end for yeartoday-------------------------------------------
// -------------------------------------------------- start for 6 month--------------------------------------------
            elseif($period=='six_month') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department 6 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 6 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department 6 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 6 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department 6 Month Acc Days On Sale']>0)
                        $factor=7/$row['Product Department 6 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department 6 Month Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department 6 Month Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department 6 Month Acc Days Available']>0)
                        $factor=7/$row['Product Department 6 Month Acc Days Available'];
                    else
                        $factor=0;
                }
                $tsall=$row['Product Department 6 Month Acc Invoiced Amount']*$factor;
                $tprofit=$row['Product Department 6 Month Acc Profit']*$factor;
            }
// --------------------------------------------------- end for 6 month-------------------------------------------




            elseif($period=='quarter') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department 1 Quarter Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Quarter Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department 1 Quarter Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Quarter Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department 1 Quarter Acc Days On Sale']>0)
                        $factor=7/$row['Product Department 1 Quarter Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department 1 Quarter Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department 1 Quarter Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department 1 Quarter Acc Days Available']>0)
                        $factor=7/$row['Product Department 1 Quarter Acc Days Available'];
                    else
                        $factor=0;
                }


                $tsall=$row['Product Department 1 Quarter Acc Invoiced Amount']*$factor;
                $tprofit=$row['Product Department 1 Quarter Acc Profit']*$factor;
            }






            elseif($period=='month') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department 1 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department 1 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department 1 Month Acc Days On Sale']>0)
                        $factor=7/$row['Product Department 1 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department 1 Month Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department 1 Month Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department 1 Month Acc Days Available']>0)
                        $factor=7/$row['Product Department 1 Month Acc Days Available'];
                    else
                        $factor=0;
                }


                $tsall=$row['Product Department 1 Month Acc Invoiced Amount']*$factor;
                $tprofit=$row['Product Department 1 Month Acc Profit']*$factor;
            }


// -------------------------------------------------- start for 10 days--------------------------------------------
            elseif($period=='ten_day') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department 10 Day Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 10 Day Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department 10 Day Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 10 Day Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department 10 Day Acc Days On Sale']>0)
                        $factor=7/$row['Product Department 10 Day Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department 10 Day Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department 10 Day Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department 10 Day Acc Days Available']>0)
                        $factor=7/$row['Product Department 10 Day Acc Days Available'];
                    else
                        $factor=0;
                }
                $tsall=$row['Product Department 10 Day Acc Invoiced Amount']*$factor;
                $tprofit=$row['Product Department 10 Day Acc Profit']*$factor;
            }
// --------------------------------------------------- end for 10 days-------------------------------------------





            elseif($period=='week') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Department 1 Week Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Week Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Department 1 Week Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Department 1 Week Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Department 1 Week Acc Days On Sale']>0)
                        $factor=7/$row['Product Department 1 Week Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Department 1 Week Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Department 1 Week Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Department 1 Week Acc Days Available']>0)
                        $factor=7/$row['Product Department 1 Week Acc Days Available'];
                    else
                        $factor=0;
                }


                $tsall=$row['Product Department 1 Week Acc Invoiced Amount']*$factor;
                $tprofit=$row['Product Department 1 Week Acc Profit']*$factor;
            }



        }
        $sum_active+=$row['Product Department For Public Sale Products'];
        if (!$percentages) {
            $tsall=money($tsall,$row['Product Department Currency Code']);
            $tprofit=money($tprofit,$row['Product Department Currency Code']);
            $families=number($row['Product Department Families']);
            $todo=number($row['Product Department In Process Products']);
            $active=number($row['Product Department For Public Sale Products']);
            $discontinued=number($row['Product Department Discontinued Products']);
            $outofstock=number($row['Product Department Out Of Stock Products']);
            $stock_error=number($row['Product Department Unknown Stock Products']);
            $stock_value=money($row['Product Department Stock Value']);
            $surplus=number($row['Product Department Surplus Availability Products']);
            $optimal=number($row['Product Department Optimal Availability Products']);
            $low=number($row['Product Department Low Availability Products']);
            $critical=number($row['Product Department Critical Availability Products']);

        }
        $adata[]=array(
                     'code'=>$code,
                     'name'=>$name,
                     'store'=>$store,
                     'families'=>$families,
                     'active'=>$active,
                     'todo'=>$todo,
                     'discontinued'=>$discontinued,

                     'outofstock'=>$outofstock,
                     'stock_error'=>$stock_error,
                     'stock_value'=>$stock_value,
                     'surplus'=>$surplus,
                     'optimal'=>$optimal,
                     'low'=>$low,
                     'critical'=>$critical,
                     'sales_type'=>$sales_type,

                     'sales'=>$tsall,
                     'profit'=>$tprofit,
                     'aws_p'=>$aws_p,
                     'awp_p'=>$awp_p

                 );


    }
    mysql_free_result($res);

    if ($total<=$number_results and $total>1) {

        if ($percentages) {

            if ($tsall!=0)$tsall='100.00%';
            else$tsall='';
            if ($tprofit!=0)$tprofit='100.00%';
            else$tprofit='';
            if ($sum_families!=0)$tfamilies='100.00%';
            else$tfamilies='';

            if ($sum_outofstock!=0)$outofstock='100.00%';
            else$outofstock='';
            if ($sum_stock_error!=0)$stock_error='100.00%';
            else$stock_error='';

            if ($sum_surplus!=0)$surplus='100.00%';
            else$surplus='';
            if ($sum_optimal!=0)$optimal='100.00%';
            else$optimal='';
            if ($sum_low!=0)$low='100.00%';
            else$low='';
            if ($sum_critical!=0)$critical='100.00%';
            else$critical='';

            if ($sum_active!=0)$active='100.00%';
            else$active='';
            if ($sum_discontinued!=0)$discontinued='100.00%';
            else$discontinued='';
        } else {
            $tsall=money($sum_total_sales,$currency_code);
            $tprofit=money($sum_total_profit,$currency_code);
            $tfamilies=number($sum_families);
            $outofstock=number($sum_outofstock);
            $stockerror=number($sum_stock_error);

            $surplus=number($sum_surplus);
            $optimal=number($sum_optimal);
            $low=number($sum_low);
            $critical=number($sum_critical);
            $active=number($sum_active);
            $discontinued=number($sum_discontinued);

        }

        $adata[]=array(

                     'code'=>_('Total'),
                     'families'=>$tfamilies,
                     'active'=>number($sum_active),
                     'sales'=>$tsall,
                     'profit'=>$tprofit,
                     'discontinued'=>number($sum_discontinued),
                     'sales_type'=>'',
                     'outofstock'=>$outofstock,
                     'stock_error'=>$stock_error,
                     'stock_value'=>$stock_value,
                     'surplus'=>$surplus,
                     'optimal'=>$optimal,
                     'low'=>$low,
                     'critical'=>$critical,
                     'sales_type'=>$sales_type,
                     'active'=>$active,
                     'discontinued'=>$discontinued



                 );

    } else {
        $adata[]=array();

    }
    $total_records=ceil($total/$number_results)+$total;
    $number_results++;

    if ($start_from==0)
        $record_offset=0;
    else
        $record_offset=$start_from+1;

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
                                      'records_offset'=>$record_offset,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);
}

function list_products() {

    global $user;
    $display_total=false;

    if (isset( $_REQUEST['list_key']))
        $list_key=$_REQUEST['list_key'];
    else
        $list_key=false;



    if (isset( $_REQUEST['parent']))
        $parent=$_REQUEST['parent'];
    else
        $parent='none';


    if ($parent=='store') {
        $conf=$_SESSION['state']['store']['products'];
        $conf_table='store';
    }
    elseif ($parent=='department') {
        $conf=$_SESSION['state']['department']['products'];
        $conf_table='department';
    }
    elseif ($parent=='family') {
        $conf=$_SESSION['state']['family']['products'];
        $conf_table='family';
    }
    elseif ($parent=='none') {
        $conf=$_SESSION['state']['stores']['products'];
        $conf_table='stores';
    }
    else {

        exit;
    }





    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$conf['view'];



    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr']-1;

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
        $awhere=addslashes($_REQUEST['where']);
    else
        $awhere=$conf['where'];




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
    } else
        $percentages=$conf['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];
    } else
        $period=$conf['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];
    } else
        $avg=$conf['avg'];

    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];
    } else {
        $period=$conf['period'];
    }
    if (isset( $_REQUEST['parent']))
        $parent=$_REQUEST['parent'];
    else {
        $parent='store';//$conf['parent'];
    }
    if (isset( $_REQUEST['mode']))
        $mode=$_REQUEST['mode'];
    else
        $mode=$conf['mode'];

    if (isset( $_REQUEST['elements']))
        $elements=$_REQUEST['elements'];
    else
        $elements=$conf['elements'];


    if (isset( $_REQUEST['elements_discontinued'])) {
        $elements['Discontinued']=$_REQUEST['elements_discontinued'];

    }
    if (isset( $_REQUEST['elements_nosale'])) {
        $elements['NoSale']=$_REQUEST['elements_nosale'];
    }
    if (isset( $_REQUEST['elements_sale'])) {
        $elements['Sale']=$_REQUEST['elements_sale'];
    }


    if (isset( $_REQUEST['elements_private'])) {
        $elements['Private']=$_REQUEST['elements_private'];
    }
    if (isset( $_REQUEST['elements_historic'])) {
        $elements['Historic']=$_REQUEST['elements_historic'];
    }









    if (isset( $_REQUEST['store_id'])    ) {
        $store=$_REQUEST['store_id'];
        $_SESSION['state']['products']['store']=$store;
    } else
        $store=$_SESSION['state']['products']['store'];


    //$_SESSION['state'][$conf_table]['table']['exchange_type']=$exchange_type;
    //$_SESSION['state'][$conf_table]['table']['exchange_value']=$exchange_value;
    //$_SESSION['state'][$conf_table]['table']['show_default_currency']=$show_default_currency;
    $_SESSION['state'][$conf_table]['products']['order']=$order;
    $_SESSION['state'][$conf_table]['products']['order_dir']=$order_dir;
    $_SESSION['state'][$conf_table]['products']['nr']=$number_results;
    $_SESSION['state'][$conf_table]['products']['sf']=$start_from;
    $_SESSION['state'][$conf_table]['products']['where']=$awhere;
    $_SESSION['state'][$conf_table]['products']['f_field']=$f_field;
    $_SESSION['state'][$conf_table]['products']['f_value']=$f_value;
    $_SESSION['state'][$conf_table]['products']['percentages']=$percentages;
    $_SESSION['state'][$conf_table]['products']['avg']=$avg;
    $_SESSION['state'][$conf_table]['products']['period']=$period;
    $_SESSION['state'][$conf_table]['products']['elements']=$elements;
    $_SESSION['state'][$conf_table]['products']['mode']=$mode;



    //$_SESSION['state'][$conf_table]['period']=$period;

    //$_SESSION['state'][$conf_table]['restrictions']=$restrictions;
    // $_SESSION['state'][$conf_table]['parent']=$parent;

    $table='`Product Dimension`';
    $where_type='';
    $where_interval='';
    $where='where true';

    /*
    if(count($user->stores)==0)
    $where="where false";
    else{
    	$where.=sprintf(" and `Product Store Key` in (%s) ",join(',',$user->stores));
    }
    */
    if ($awhere) {

        $tmp=preg_replace('/\\\"/','"',$awhere);
        $tmp=preg_replace('/\\\\\"/','"',$tmp);
        $tmp=preg_replace('/\'/',"\'",$tmp);

        $raw_data=json_decode($tmp, true);
        $raw_data['store_key']=$store;
        list($where,$table)=product_awhere($raw_data);

        $where_type='';
        $where_interval='';
    }

    if ($list_key) {
        $sql=sprintf("select * from `List Dimension` where `List Key`=%d",$_REQUEST['list_key']);

        $res=mysql_query($sql);
        if ($customer_list_data=mysql_fetch_assoc($res)) {
            $awhere=false;
            if ($customer_list_data['List Type']=='Static') {

                $table='`List Product Bridge` PB left join `Product Dimension` P  on (PB.`Product ID`=P.`Product ID`)';
                $where_type=sprintf(' and `List Key`=%d ',$_REQUEST['list_key']);

            } else {// Dynamic by DEFAULT



                $tmp=preg_replace('/\\\"/','"',$customer_list_data['List Metadata']);
                $tmp=preg_replace('/\\\\\"/','"',$tmp);
                $tmp=preg_replace('/\'/',"\'",$tmp);

                $raw_data=json_decode($tmp, true);

                $raw_data['store_key']=$store;
                list($where,$table)=product_awhere($raw_data);
            }

        } else {
            exit("error");
        }
    }

    $where.=$where_type;


    switch ($parent) {
    case('store'):
        $where.=sprintf(' and `Product Store Key`=%d',$_SESSION['state']['products']['store']);
        break;
    case('department'):
        $where.=sprintf('  and `Product Main Department Key`=%d',$_SESSION['state']['department']['id']);
        break;
    case('family'):
        if (isset($_REQUEST['parent_key']))
            $parent_key=$_REQUEST['parent_key'];
        else
            $parent_key=$_SESSION['state']['family']['id'];

        $where.=sprintf(' and `Product Family Key`=%d',$parent_key);
        break;
    default:


    }


    $group='';

    $_elements='';
    foreach($elements as $_key=>$_value) {
        if ($_value)
            $_elements.=','.prepare_mysql($_key);
    }
    $_elements=preg_replace('/^\,/','',$_elements);
    if ($_elements=='') {
        $where.=' and false' ;
    } else {
        $where.=' and `Product Main Type` in ('.$_elements.')' ;
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
    if ($f_field=='code' and $f_value!='')
        $wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";
    elseif($f_field=='description' and $f_value!='')
    $wheref.=" and  `Product Name` like '%".addslashes($f_value)."%'";

    $sql="select count(*) as total from $table  $where $wheref";
    //print $sql;
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total_without_filters from `Product Dimension`  $where ";
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

    if ($order=='stock')
        $order='`Product Availability`';
    if ($order=='code' or $order=='codename')
        $order='`Product Code File As`';
    else if ($order=='name')
        $order='`Product Name`';
    else if ($order=='available_for')
        $order='`Product Available Days Forecast`';
    else if ($order=='shortname')
        $order='`Product Available Days Forecast`';

    if ($order=='profit') {
        if ($period=='all')
            $order='`Product Total Profit`';
        elseif($period=='year')
        $order='`Product 1 Year Acc Profit`';
        elseif($period=='quarter')
        $order='`Product 1 Quarter Acc Profit`';
        elseif($period=='month')
        $order='`Product 1 Month Acc Profit`';
        elseif($period=='week')
        $order='`Product 1 Week Acc Profit`';
    }
    elseif($order=='sales') {
        if ($period=='all')
            $order='`Product Total Invoiced Amount`';
        elseif($period=='year')
        $order='`Product 1 Year Acc Invoiced Amount`';
        elseif($period=='quarter')
        $order='`Product 1 Quarter Acc Invoiced Amount`';
        elseif($period=='month')
        $order='`Product 1 Month Acc Invoiced Amount`';
        elseif($period=='week')
        $order='`Product 1 Week Acc Invoiced Amount`';
// -----------------------------------------Start Product's 3Y,YTD,6M,10D---------------------------------------------
        elseif($period=='three_year')
        $order='`Product 3 Year Acc Invoiced Amount`';
        elseif($period=='yeartoday')
        $order='`Product YearToDay Acc Invoiced Amount`';
        elseif($period=='six_month')
        $order='`Product 6 Month Acc Invoiced Amount`';
        elseif($period=='ten_day')
        $order='`Product 10 Day Acc Invoiced Amount`';
// -----------------------------------------End Product's 3Y,YTD,6M,10D---------------------------------------------
    }
    elseif($order=='margin') {
        if ($period=='all')
            $order='`Product Total Margin`';
        elseif($period=='year')
        $order='`Product 1 Year Acc Margin`';
        elseif($period=='quarter')
        $order='`Product 1 Quarter Acc Margin`';
        elseif($period=='month')
        $order='`Product 1 Month Acc Margin`';
        elseif($period=='week')
        $order='`Product 1 Week Acc Margin`';

    }
    elseif($order=='sold') {
        if ($period=='all')
            $order='`Product Total Quantity Invoiced`';
        elseif($period=='year')
        $order='`Product 1 Year Acc Quantity Invoiced`';
        elseif($period=='quarter')
        $order='`Product 1 Quarter Acc Quantity Invoiced`';
        elseif($period=='month')
        $order='`Product 1 Month Acc Quantity Invoiced`';
        elseif($period=='week')
        $order='`Product 1 Week Acc Quantity Invoiced`';

    }
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
        $order='`Product Sales Type`';
    }
    elseif($order=='web') {
        $order='`Product Web Configuration`';
    }
    elseif($order=='stock_state') {
        $order='`Product Availability State`';
    }
    elseif($order=='stock_forecast') {
        $order='`Product Available Days Forecast`';
    }
    elseif($order=='formated_record_type') {
        $order='`Product Record Type`';
    }
    elseif($order=='store') {
        $order='`Store Code`';
    }





    $sum_total_sales=0;
    $sum_total_profit=0;
    $sum_total_stock_value=0;


    if ($percentages) {

        $sum_total_stock_value=0;
        $sql="select sum(`Product Stock Value`) as sum_stock_value  from `Product Dimension` $where $wheref     ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $sum_total_stock_value=$row['sum_stock_value'];
        }

        if ($period=='all') {


            $sum_total_sales=0;
            $sum_month_sales=0;
            $sql="select sum(if(`Product Total Profit`<0,`Product Total Profit`,0)) as total_profit_minus,sum(if(`Product Total Profit`>=0,`Product Total Profit`,0)) as total_profit_plus,sum(`Product Total Invoiced Amount`) as sum_total_sales ,sum(`Product Stock Value`) as sum_stock_value  from `Product Dimension` $where $wheref     ";

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales=$row['sum_total_sales'];

                $sum_total_profit_plus=$row['total_profit_plus'];
                $sum_total_profit_minus=$row['total_profit_minus'];
                $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];

            }
        }
// ---------------------------------Start for Product's 3 year------------------------------------------
        elseif($period=='three_year') {
            $sum_total_sales=0;
            $sum_month_sales=0;
            $sql="select sum(if(`Product 3 Year Acc Profit`<0,`Product 3 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Product 3 Year Acc Profit`>=0,`Product 3 Year Acc Profit`,0)) as total_profit_plus,sum(`Product 3 Year Acc Invoiced Amount`) as sum_total_sales  from `Product Dimension` $where $wheref   ";

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales=$row['sum_total_sales'];

                $sum_total_profit_plus=$row['total_profit_plus'];
                $sum_total_profit_minus=$row['total_profit_minus'];
                $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
            }
        }
// ---------------------------------End for Product's 3 year------------------------------------------
        elseif($period=='year') {

            $sum_total_sales=0;
            $sum_month_sales=0;
            $sql="select sum(if(`Product 1 Year Acc Profit`<0,`Product 1 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Product 1 Year Acc Profit`>=0,`Product 1 Year Acc Profit`,0)) as total_profit_plus,sum(`Product 1 Year Acc Invoiced Amount`) as sum_total_sales  from `Product Dimension` $where $wheref   ";

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales=$row['sum_total_sales'];

                $sum_total_profit_plus=$row['total_profit_plus'];
                $sum_total_profit_minus=$row['total_profit_minus'];
                $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
            }
        }
// ---------------------------------Start for Product's yeartoday------------------------------------------
        elseif($period=='yeartoday') {
            $sum_total_sales=0;
            $sum_month_sales=0;
            $sql="select sum(if(`Product YearToDay Acc Profit`<0,`Product YearToDay Acc Profit`,0)) as total_profit_minus,sum(if(`Product YearToDay Acc Profit`>=0,`Product YearToDay Acc Profit`,0)) as total_profit_plus,sum(`Product YearToDay Acc Invoiced Amount`) as sum_total_sales  from `Product Dimension` $where $wheref   ";

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales=$row['sum_total_sales'];

                $sum_total_profit_plus=$row['total_profit_plus'];
                $sum_total_profit_minus=$row['total_profit_minus'];
                $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
            }
        }
// ---------------------------------End for Product's yeartoday------------------------------------------
// ---------------------------------Start for Product's 6 month------------------------------------------
        elseif($period=='six_month') {
            $sum_total_sales=0;
            $sum_month_sales=0;
            $sql="select sum(if(`Product 6 Month Acc Profit`<0,`Product 6 Month Acc Profit`,0)) as total_profit_minus,sum(if(`Product 6 Month Acc Profit`>=0,`Product 6 Month Acc Profit`,0)) as total_profit_plus,sum(`Product 6 Month Acc Invoiced Amount`) as sum_total_sales  from `Product Dimension` $where $wheref   ";

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales=$row['sum_total_sales'];

                $sum_total_profit_plus=$row['total_profit_plus'];
                $sum_total_profit_minus=$row['total_profit_minus'];
                $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
            }
        }
// ---------------------------------End for Product's 6 month------------------------------------------
        elseif($period=='quarter') {

            $sum_total_sales=0;
            $sum_month_sales=0;
            $sql="select sum(if(`Product 1 Quarter Acc Profit`<0,`Product 1 Quarter Acc Profit`,0)) as total_profit_minus,sum(if(`Product 1 Quarter Acc Profit`>=0,`Product 1 Quarter Acc Profit`,0)) as total_profit_plus,sum(`Product 1 Quarter Acc Invoiced Amount`) as sum_total_sales   from `Product Dimension`   $where $wheref   ";

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales=$row['sum_total_sales'];

                $sum_total_profit_plus=$row['total_profit_plus'];
                $sum_total_profit_minus=$row['total_profit_minus'];
                $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
            }
        }


        elseif($period=='month') {

            $sum_total_sales=0;
            $sum_month_sales=0;
            $sql="select sum(if(`Product 1 Month Acc Profit`<0,`Product 1 Month Acc Profit`,0)) as total_profit_minus,sum(if(`Product 1 Month Acc Profit`>=0,`Product 1 Month Acc Profit`,0)) as total_profit_plus,sum(`Product 1 Month Acc Invoiced Amount`) as sum_total_sales   from `Product Dimension`  $where $wheref    ";

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales=$row['sum_total_sales'];

                $sum_total_profit_plus=$row['total_profit_plus'];
                $sum_total_profit_minus=$row['total_profit_minus'];
                $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
            }
        }

// ---------------------------------Start for Product's 10 day------------------------------------------
        elseif($period=='ten_day') {
            $sum_total_sales=0;
            $sum_month_sales=0;
            $sql="select sum(if(`Product 10 Day Acc Profit`<0,`Product 10 Day Acc Profit`,0)) as total_profit_minus,sum(if(`Product 10 Day Acc Profit`>=0,`Product 10 Day Acc Profit`,0)) as total_profit_plus,sum(`Product 10 Day Acc Invoiced Amount`) as sum_total_sales  from `Product Dimension` $where $wheref   ";
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
                $sum_total_sales=$row['sum_total_sales'];
                $sum_total_profit_plus=$row['total_profit_plus'];
                $sum_total_profit_minus=$row['total_profit_minus'];
                $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
            }
        }
// ---------------------------------End for Product's 10 day------------------------------------------
        elseif($period=='week') {
            $sum_families=0;
            $sum_total_sales=0;
            $sum_month_sales=0;
            $sql="select sum(if(`Product 1 Week Acc Profit`<0,`Product 1 Week Acc Profit`,0)) as total_profit_minus,sum(if(`Product 1 Week Acc Profit`>=0,`Product 1 Week Acc Profit`,0)) as total_profit_plus,sum(`Product 1 Week Acc Invoiced Amount`) as sum_total_sales   from `Product Dimension`  $where $wheref    ";

            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales=$row['sum_total_sales'];

                $sum_total_profit_plus=$row['total_profit_plus'];

                $sum_total_profit_minus=$row['total_profit_minus'];
                $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
            }
        }

    }

    if ($list_key) {}
    //$table='';
    else
        $table="`Product Dimension` P left join `Store Dimension` S on (`Product Store Key`=`Store Key`)";

    $sql="select  * from  $table $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";



    //print $sql;exit;
    $res = mysql_query($sql);
    $adata=array();

    $counter=0;
    $total_units=0;

    $sum_unitary_price=0;
    $counter_unitary_price=0;
    $sum_sold=0;
    $sum_units=0;
    $sum_sales=0;
    $sum_profit=0;
    $count_margin=0;
    $sum_margin=0;

    //  print "P:$period $avg $sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $counter++;




        $counter_unitary_price++;
        $sum_unitary_price+=$row['Product Price']/$row['Product Units Per Case'];




        $code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);
        //$store=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Store Key'],$row['Store Code']);
        $store=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Store Key'],$row['Product Store Key']);

        if ($percentages) {
            if ($period=='all') {
                $tsall=percentage($row['Product Total Invoiced Amount'],$sum_total_sales,2);

                if ($row['Product Total Profit']>=0)
                    $tprofit=percentage($row['Product Total Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Total Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='year') {
                $tsall=percentage($row['Product 1 Year Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product 1 Year Acc Profit']>=0)
                    $tprofit=percentage($row['Product 1 Year Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product 1 Year Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='quarter') {
                $tsall=percentage($row['Product 1 Quarter Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product 1 Quarter Acc Profit']>=0)
                    $tprofit=percentage($row['Product 1 Quarter Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product 1 Quarter Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='month') {
                $tsall=percentage($row['Product 1 Month Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product 1 Month Acc Profit']>=0)
                    $tprofit=percentage($row['Product 1 Month Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product 1 Month Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='week') {
                $tsall=percentage($row['Product 1 Week Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product 1 Week Acc Profit']>=0)
                    $tprofit=percentage($row['Product 1 Week Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product 1 Week Acc Profit'],$sum_total_profit_minus,2);
            }
// ---------------------------------------start product's for 3Y,YTD,6M,10D------------------------------------
            elseif($period=='three_year') {
                $tsall=percentage($row['Product 3 Year Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product 3 Year Acc Profit']>=0)
                    $tprofit=percentage($row['Product 3 Year Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product 3 Year Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='yeartoday') {
                $tsall=percentage($row['Product YearToDay Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product YearToDay Acc Profit']>=0)
                    $tprofit=percentage($row['Product YearToDay Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product YearToDay Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='six_month') {
                $tsall=percentage($row['Product 6 Month Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product 6 Month Acc Profit']>=0)
                    $tprofit=percentage($row['Product 6 Month Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product 6 Month Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='ten_day') {
                $tsall=percentage($row['Product 10 Day Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product 10 Day Acc Profit']>=0)
                    $tprofit=percentage($row['Product 10 Day Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product 10 Day Acc Profit'],$sum_total_profit_minus,2);
            }
// ---------------------------------------end product's for 3Y,YTD,6M,10D------------------------------------

        } else {






            if ($period=='all') {


                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Total Days On Sale']>0)
                        $factor=30.4368499/$row['Product Total Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week') {
                    if ($row['Product Total Days On Sale']>0)
                        $factor=7/$row['Product Total Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Total Days Available']>0)
                        $factor=30.4368499/$row['Product Total Days Available'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Total Days Available']>0)
                        $factor=7/$row['Product Total Days Available'];
                    else
                        $factor='ND';
                }
                if ($factor=='ND') {
                    $tsall=_('ND');
                    $tprofit=_('ND');
                    $sold=_('ND');
                } else {

                    $tsall=($row['Product Total Invoiced Amount']*$factor);
                    $tprofit=($row['Product Total Profit']*$factor);
                    $sold=$row['Product Total Quantity Invoiced']*$factor;
                }


                $margin=$row['Product Total Margin'];



            }
            elseif($period=='three_year') {


                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product 3 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 3 Year Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month') {
                    if ($row['Product 3 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 3 Year Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week') {
                    if ($row['Product 3 Year Acc Days On Sale']>0)
                        $factor=7/$row['Product 3 Year Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month_eff') {
                    if ($row['Product 3 Year Acc Days Available']>0)
                        $factor=30.4368499/$row['Product 3 Year Acc Days Available'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week_eff') {
                    if ($row['Product 3 Year Acc Days Available']>0)
                        $factor=7/$row['Product 3 Year Acc Days Available'];
                    else
                        $factor='ND';
                }
                if ($factor=='ND') {
                    $tsall=_('ND');
                    $tprofit=_('ND');
                    $sold=_('ND');
                } else {
                    $sold=($row['Product 3 Year Acc Quantity Invoiced']*$factor);
                    $tsall=($row['Product 3 Year Acc Invoiced Amount']*$factor);
                    $tprofit=($row['Product 3 Year Acc Profit']*$factor);
                }
                $margin=$row['Product 3 Year Acc Margin'];
            }
            elseif($period=='year') {


                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product 1 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Year Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month') {
                    if ($row['Product 1 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Year Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week') {
                    if ($row['Product 1 Year Acc Days On Sale']>0)
                        $factor=7/$row['Product 1 Year Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month_eff') {
                    if ($row['Product 1 Year Acc Days Available']>0)
                        $factor=30.4368499/$row['Product 1 Year Acc Days Available'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week_eff') {
                    if ($row['Product 1 Year Acc Days Available']>0)
                        $factor=7/$row['Product 1 Year Acc Days Available'];
                    else
                        $factor='ND';
                }
                if ($factor=='ND') {
                    $tsall=_('ND');
                    $tprofit=_('ND');
                    $sold=_('ND');
                } else {
                    $sold=($row['Product 1 Year Acc Quantity Invoiced']*$factor);
                    $tsall=($row['Product 1 Year Acc Invoiced Amount']*$factor);
                    $tprofit=($row['Product 1 Year Acc Profit']*$factor);
                }
                $margin=$row['Product 1 Year Acc Margin'];
            }
            elseif($period=='yeartoday') {


                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product YearToDay Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product YearToDay Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month') {
                    if ($row['Product YearToDay Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product YearToDay Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week') {
                    if ($row['Product YearToDay Acc Days On Sale']>0)
                        $factor=7/$row['Product YearToDay Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month_eff') {
                    if ($row['Product YearToDay Acc Days Available']>0)
                        $factor=30.4368499/$row['Product YearToDay Acc Days Available'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week_eff') {
                    if ($row['Product YearToDay Acc Days Available']>0)
                        $factor=7/$row['Product YearToDay Acc Days Available'];
                    else
                        $factor='ND';
                }
                if ($factor=='ND') {
                    $tsall=_('ND');
                    $tprofit=_('ND');
                    $sold=_('ND');
                } else {
                    $sold=($row['Product YearToDay Acc Quantity Invoiced']*$factor);
                    $tsall=($row['Product YearToDay Acc Invoiced Amount']*$factor);
                    $tprofit=($row['Product YearToDay Acc Profit']*$factor);
                }
                $margin=$row['Product YearToDay Acc Margin'];
            }
            elseif($period=='six_month') {


                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product 6 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 6 Month Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month') {
                    if ($row['Product 6 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 6 Month Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week') {
                    if ($row['Product 6 Month Acc Days On Sale']>0)
                        $factor=7/$row['Product 6 Month Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month_eff') {
                    if ($row['Product 6 Month Acc Days Available']>0)
                        $factor=30.4368499/$row['Product 6 Month Acc Days Available'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week_eff') {
                    if ($row['Product 6 Month Acc Days Available']>0)
                        $factor=7/$row['Product 6 Month Acc Days Available'];
                    else
                        $factor='ND';
                }
                if ($factor=='ND') {
                    $tsall=_('ND');
                    $tprofit=_('ND');
                    $sold=_('ND');
                } else {
                    $sold=($row['Product 6 Month Acc Quantity Invoiced']*$factor);
                    $tsall=($row['Product 6 Month Acc Invoiced Amount']*$factor);
                    $tprofit=($row['Product 6 Month Acc Profit']*$factor);
                }
                $margin=$row['Product 6 Month Acc Margin'];
            }
            elseif($period=='quarter') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product 1 Quarter Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Quarter Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month') {
                    if ($row['Product 1 Quarter Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Quarter Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week') {
                    if ($row['Product 1 Quarter Acc Days On Sale']>0)
                        $factor=7/$row['Product 1 Quarter Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month_eff') {
                    if ($row['Product 1 Quarter Acc Days Available']>0)
                        $factor=30.4368499/$row['Product 1 Quarter Acc Days Available'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week_eff') {
                    if ($row['Product 1 Quarter Acc Days Available']>0)
                        $factor=7/$row['Product 1 Quarter Acc Days Available'];
                    else
                        $factor='ND';
                }

                if ($factor=='ND') {
                    $tsall=_('ND');
                    $tprofit=_('ND');
                    $sold=_('ND');
                } else {
                    $sold=($row['Product 1 Quarter Acc Quantity Invoiced']*$factor);
                    $tsall=($row['Product 1 Quarter Acc Invoiced Amount']*$factor);
                    $tprofit=($row['Product 1 Quarter Acc Profit']*$factor);
                }
                $margin=$row['Product 1 Quarter Acc Margin'];

            }
            elseif($period=='month') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product 1 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Month Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month') {
                    if ($row['Product 1 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Month Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week') {
                    if ($row['Product 1 Month Acc Days On Sale']>0)
                        $factor=7/$row['Product 1 Month Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month_eff') {
                    if ($row['Product 1 Month Acc Days Available']>0)
                        $factor=30.4368499/$row['Product 1 Month Acc Days Available'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week_eff') {
                    if ($row['Product 1 Month Acc Days Available']>0)
                        $factor=7/$row['Product 1 Month Acc Days Available'];
                    else
                        $factor='ND';
                }

                if ($factor=='ND') {
                    $tsall=_('ND');
                    $tprofit=_('ND');
                    $sold=_('ND');
                } else {
                    $tsall=$row['Product 1 Month Acc Invoiced Amount']*$factor;
                    $tprofit=$row['Product 1 Month Acc Profit']*$factor;
                    $sold=$row['Product 1 Month Acc Quantity Invoiced']*$factor;
                }
                $margin=$row['Product 1 Month Acc Margin'];
            }
            elseif($period=='ten_day') {


                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product 10 Day Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 10 Day Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month') {
                    if ($row['Product 10 Day Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 10 Day Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week') {
                    if ($row['Product 10 Day Acc Days On Sale']>0)
                        $factor=7/$row['Product 10 Day Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month_eff') {
                    if ($row['Product 10 Day Acc Days Available']>0)
                        $factor=30.4368499/$row['Product 10 Day Acc Days Available'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week_eff') {
                    if ($row['Product 10 Day Acc Days Available']>0)
                        $factor=7/$row['Product 10 Day Acc Days Available'];
                    else
                        $factor='ND';
                }
                if ($factor=='ND') {
                    $tsall=_('ND');
                    $tprofit=_('ND');
                    $sold=_('ND');
                } else {
                    $sold=($row['Product 10 Day Acc Quantity Invoiced']*$factor);
                    $tsall=($row['Product 10 Day Acc Invoiced Amount']*$factor);
                    $tprofit=($row['Product 10 Day Acc Profit']*$factor);
                }
                $margin=$row['Product 10 Day Acc Margin'];
            }
            elseif($period=='week') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product 1 Week Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Week Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month') {
                    if ($row['Product 1 Week Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product 1 Week Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week') {
                    if ($row['Product 1 Week Acc Days On Sale']>0)
                        $factor=7/$row['Product 1 Week Acc Days On Sale'];
                    else
                        $factor='ND';
                }
                elseif($avg=='month_eff') {
                    if ($row['Product 1 Week Acc Days Available']>0)
                        $factor=30.4368499/$row['Product 1 Week Acc Days Available'];
                    else
                        $factor='ND';
                }
                elseif($avg=='week_eff') {
                    if ($row['Product 1 Week Acc Days Available']>0)
                        $factor=7/$row['Product 1 Week Acc Days Available'];
                    else
                        $factor='ND';
                }
                if ($factor=='ND') {
                    $tsall=_('ND');
                    $tprofit=_('ND');
                    $sold=_('ND');
                } else {
                    $tsall=$row['Product 1 Week Acc Invoiced Amount']*$factor;
                    $sold=$row['Product 1 Week Acc Quantity Invoiced']*$factor;
                    $tprofit=$row['Product 1 Week Acc Profit']*$factor;


                }
                $margin=$row['Product 1 Week Acc Margin'];

            }
        }

        if (is_numeric($row['Product Availability']))
            $stock=number($row['Product Availability']);
        else
            $stock='?';

        $sum_sold+=$sold;
        $sum_units+=$sold*$row['Product Units Per Case'];

        $sum_sales+=$tsall;
        $sum_profit+=$tprofit;

        if ($margin=='') {
            if ($sold==0)
                $margin=_('ND');
            else
                $margin=_('NA');

        } else {
            $count_margin++;
            $sum_margin+=$margin;
            $margin=number($margin,1)."%";
        }

        $type=$row['Product Sales Type'];
        if ($row['Product Stage']=='In Process')
            $type.='<span style="color:red">*</span>';



        switch ($row['Product Main Type']) {
        case('Historic'):
            $main_type=_('Historic');
            break;
        case('Private'):
            $main_type=_('Private');
            break;
        case('NoSale'):
            $main_type=_('Not for Sale');
        case('Discontinued'):
            $main_type=_('Discontinued');
            break;
        case('Sale'):
            $main_type=_('For Sale');
            break;
        default:
            $main_type=$row['Product Main Type'];

        }

$web_configuration='';
        switch ($row['Product Web State']) {

        case('For Sale'):
        if($row['Product Web Configuration']='Online Force For Sale')
            $web_configuration='('._('forced').')';
        
            $formated_web_configuration='<span class="web_online">'._('Online')." $web_configuration</span>";
            break;
        case('Offline'):
         if($row['Product Web Configuration']='Offline')
            $web_configuration='('._('forced').')';
            $formated_web_configuration='<span class="web_offline">'._('Offline')." $web_configuration</span>";
            break;
        case('Out of Stock'):
        if($row['Product Web Configuration']='Online Force Out of Stock')
            $web_configuration='('._('forced').')';
            $formated_web_configuration='<span class="web_out_of_stock">'._('Out of Stock')." $web_configuration</span>";
            break;
        case('Discontinued'):
            $formated_web_configuration='<span class="web_discontinued">'._('Discontinued')." $web_configuration</span>";
            break;
        default:
            $formated_web_configuration=$row['Product Web State'];

        }









        include_once('locale.php');
        global $locale_product_record_type;

        $stock_state=$row['Product Availability State'];
        $stock_forecast=interval($row['Product Available Days Forecast']);



        $record_type=$row['Product Record Type'];

        $adata[]=array(
                     'store'=>$store,
                     'code'=>$code,
                     'name'=>$row['Product XHTML Short Description'],
                     'smallname'=>'<span style="font-size:70%;">'.$row['Product XHTML Short Description'].'</span>',
                     'formated_record_type'=>$record_type,
                     'record_type'=>$row['Product Record Type'],
                     'stock_state'=>$stock_state,
                     'stock_forecast'=>$stock_forecast,
                     'family'=>$row['Product Family Name'],
                     'dept'=>$row['Product Main Department Name'],
                     //'expcode'=>$row['Product Tariff Code'],
                     'parts'=>$row['Product XHTML Parts'],
                     'supplied'=>$row['Product XHTML Supplied By'],
                     'gmroi'=>$row['Product GMROI'],
                     'stock_value'=>money($row['Product Stock Value']),
                     'stock'=>$stock,
                     'sales'=>money($tsall),
                     'profit'=>money($tprofit),
                     'margin'=>$margin,
                     'sold'=>(is_numeric($sold)?number($sold):$sold),
                     'state'=>$type,
                     'web'=>$formated_web_configuration,
                     'image'=>$row['Product Main Image'],
                     'type'=>'item',
                     'name_only'=>$row['Product Name'],
                     'units'=>$row['Product Units Per Case']."x"

                 );
    }
    mysql_free_result($res);

    if ($total<=$number_results) {

        if ($percentages) {
            $tsall='100.00%';
            $tprofit='100.00%';
            $tstock_value='100.00%';
        } else {
            $tsall=money($sum_total_sales);
            $tprofit=money($sum_total_profit);
            $tstock_value=money($sum_total_stock_value);

        }


        $total_title='Total';
        if ($view=='sales')
            $total_title=_('Total');

        if ($counter_unitary_price>0)
            $average_unit_price=$sum_unitary_price/$counter_unitary_price;
        else
            $average_unit_price=_('ND');
        if ($count_margin>0)
            $avg_margin='&lang;'.number($sum_margin/$count_margin,1)."%&rang;";
        else
            $avg_margin=_('ND');
        $adata[]=array(

                     'code'=>$total_title,
                     'name'=>'',
                     'shortname'=>number($sum_units).'x',
                     'stock_value'=>$tstock_value,
                     'sold'=>number($sum_sold),
                     'sales'=>money($sum_sales),
                     'profit'=>money($sum_profit),
                     'margin'=>$avg_margin,
                     'type'=>'total'
                 );


        // $total_records=ceil($total_records/$number_results)+$total_records;
    } else {
        $adata[]=array();

    }

    $total_records=ceil($total/$number_results)+$total;
    $number_results++;

    if ($start_from==0)
        $record_offset=0;
    else
        $record_offset=$start_from+1;


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

function list_parts() {
    $conf=$_SESSION['state']['warehouse']['parts'];
    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];

    else
        $view=$_SESSION['state']['warehouse']['parts']['view'];
    $_SESSION['state']['warehouse']['parts']['view']=$view;
    if (isset( $_REQUEST['list_key']))
        $list_key=$_REQUEST['list_key'];
    else
        $list_key=false;


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



    if (!is_numeric($number_results))
        $number_results=25;

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
        $awhere=addslashes($_REQUEST['where']);
    else
        $awhere=$conf['where'];


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

    if (isset( $_REQUEST['avg']))
        $avg=$_REQUEST['avg'];
    else
        $avg=$_SESSION['state']['warehouse']['parts']['avg'];
    $_SESSION['state']['warehouse']['parts']['avg']=$avg;


    if (isset( $_REQUEST['period']))
        $period=$_REQUEST['period'];
    else
        $period=$_SESSION['state']['warehouse']['parts']['period'];
    $_SESSION['state']['warehouse']['parts']['period']=$period;


    if (isset( $_REQUEST['percentage']))
        $percentage=$_REQUEST['percentage'];
    else
        $percentage=$_SESSION['state']['warehouse']['parts']['percentage'];
    $_SESSION['state']['warehouse']['parts']['percentage']=$percentage;




    $elements=$conf['elements'];
    if (isset( $_REQUEST['elements_Keeping'])) {
        $elements['Keeping']=$_REQUEST['elements_Keeping'];
    }
    if (isset( $_REQUEST['elements_NotKeeping'])) {
        $elements['NotKeeping']=$_REQUEST['elements_NotKeeping'];
    }

    if (isset( $_REQUEST['elements_Discontinued'])) {
        $elements['Discontinued']=$_REQUEST['elements_Discontinued'];
    }
    if (isset( $_REQUEST['elements_LastStock'])) {
        $elements['LastStock']=$_REQUEST['elements_LastStock'];
    }




    //$_SESSION['state']['parts']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);

    $_SESSION['state']['warehouse']['parts']['order']=$order;
    $_SESSION['state']['warehouse']['parts']['order_dir']=$order_direction;
    $_SESSION['state']['warehouse']['parts']['nr']=$number_results;
    $_SESSION['state']['warehouse']['parts']['sf']=$start_from;
    $_SESSION['state']['warehouse']['parts']['where']=$awhere;
    $_SESSION['state']['warehouse']['parts']['f_field']=$f_field;
    $_SESSION['state']['warehouse']['parts']['f_value']=$f_value;
    $_SESSION['state']['warehouse']['parts']['elements']=$elements;


    $filter_msg='';

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');

    if (!is_numeric($start_from))
        $start_from=0;
    if (!is_numeric($number_results))
        $number_results=25;

    $where="where true  ";
    $table="`Part Dimension` P";

    if ($awhere) {

        $tmp=preg_replace('/\\\"/','"',$awhere);
        $tmp=preg_replace('/\\\\\"/','"',$tmp);
        $tmp=preg_replace('/\'/',"\'",$tmp);

        $raw_data=json_decode($tmp, true);
        //$raw_data['store_key']=$store;
        //print_r($raw_data);exit;
        list($where,$table)=parts_awhere($raw_data);

        $where_type='';
        $where_interval='';
    }


    if ($list_key) {

        $sql=sprintf("select * from `List Dimension` where `List Key`=%d",$_REQUEST['list_key']);
        //print $sql;exit;
        $res=mysql_query($sql);
        if ($customer_list_data=mysql_fetch_assoc($res)) {
            $awhere=false;
            if ($customer_list_data['List Type']=='Static') {

                $table='`List Part Bridge` PB left join `Part Dimension` P  on (PB.`Part SKU`=P.`Part SKU`)';
                $where_type=sprintf(' and `List Key`=%d ',$_REQUEST['list_key']);

            } else {// Dynamic by DEFAULT



                $tmp=preg_replace('/\\\"/','"',$customer_list_data['List Metadata']);
                $tmp=preg_replace('/\\\\\"/','"',$tmp);
                $tmp=preg_replace('/\'/',"\'",$tmp);

                $raw_data=json_decode($tmp, true);

                //$raw_data['store_key']=$store;
                list($where,$table)=parts_awhere($raw_data);
            }

        } else {
            exit("error");
        }
    }

    $_elements='';
    foreach($elements as $_key=>$_value) {
        if ($_value)
            $_elements.=','.prepare_mysql($_key);
    }
    $_elements=preg_replace('/^\,/','',$_elements);
    if ($_elements=='') {
        $where.=' and false' ;
    } else {
        $where.=' and `Part Main State` in ('.$_elements.')' ;
    }








    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';
    $wheref='';
    if ($f_field=='used_in' and $f_value!='')
        $wheref.=" and  `Part XHTML Currently Used In` like '%".addslashes($f_value)."%'";
    elseif($f_field=='description' and $f_value!='')
    $wheref.=" and  `Part Unit Description` like '%".addslashes($f_value)."%'";
    elseif($f_field=='supplied_by' and $f_value!='')
    $wheref.=" and  `Part XHTML Currently Supplied By` like '%".addslashes($f_value)."%'";
    elseif($f_field=='sku' and $f_value!='')
    $wheref.=" and  `Part SKU` ='".addslashes($f_value)."'";

    $sql="select count(*) as total from $table  $where $wheref";

    //print $sql;exit;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total_without_filters from `Part Dimension`  $where ";


        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {

            $total_records=$row['total_without_filters'];
            $filtered=$row['total_without_filters']-$total;
        }

    }




    $rtext=$total_records." ".ngettext('part','parts',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' '._('(Showing all)');
    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('sku'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part with ")." <b>".sprintf("SKU%05d",$f_value)."*</b> ";
            break;

        case('used_in'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part used in ")." <b>".$f_value."*</b> ";
            break;
        case('suppiled_by'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part supplied by ")." <b>".$f_value."*</b> ";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any part with description like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {


        switch ($f_field) {
        case('sku'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts with')." <b>".sprintf("SKU%05d",$f_value)."*</b>";
            break;

        case('used_in'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts used in')." <b>".$f_value."*</b>";
            break;
        case('supplied_by'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts supplied by')." <b>".$f_value."*</b>";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('parts with description like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';




    $_order=$order;
    $_order_dir=$order_dir;

    if ($order=='stock')
        $order='`Part Current Stock`';
    if ($order=='sku')
        $order='`Part SKU`';
    else if ($order=='description')
        $order='`Part XHTML Description`';
    else if ($order=='available_for')
        $order='`Part Available Days Forecast`';
    else if ($order=='supplied_by')
        $order='`Part XHTML Currently Supplied By`';
    else if ($order=='used_in')
        $order='`Part XHTML Currently Used In`';

    else if ($order=='margin') {
        if ($period=='all')
            $order=' `Part Total Acc Margin` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc Margin` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc Margin` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc Margin` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc Margin` ';
        elseif($period=='three_year')
        $order=' `Part 3 Year Acc Margin` ';
        elseif($period=='yeartoday')
        $order=' `Part YearToDay Acc Margin` ';
        elseif($period=='six_month')
        $order=' `Part 6 Month Acc Margin` ';
        elseif($period=='ten_day')
        $order=' `Part 10 Day Acc Margin` ';

    } else if ($order=='sold') {
        if ($period=='all')
            $order=' `Part Total Acc Sold` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc Sold` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc Sold` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc Sold` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc Sold` ';
        elseif($period=='three_year')
        $order=' `Part 3 Year Acc Sold` ';
        elseif($period=='yeartoday')
        $order=' `Part YearToDay Acc Sold` ';
        elseif($period=='six_month')
        $order=' `Part 6 Month Acc Sold` ';
        elseif($period=='ten_day')
        $order=' `Part 10 Day Acc Sold` ';

    } else if ($order=='money_in') {
        if ($period=='all')
            $order=' `Part Total Acc Sold Amount` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc Sold Amount` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc Sold Amount` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc Sold Amount` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc Sold Amount` ';
        elseif($period=='three_year')
        $order=' `Part 3 Year Acc Sold Amount` ';
        elseif($period=='yeartoday')
        $order=' `Part YearToDay Acc Sold Amount` ';
        elseif($period=='six_month')
        $order=' `Part 6 Month Acc Sold Amount` ';
        elseif($period=='ten_day')
        $order=' `Part 10 Day Acc Sold Amount` ';
    } else if ($order=='profit_sold') {
        if ($period=='all')
            $order=' `Part Total Acc Profit` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc Profit` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc Profit` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc Profit` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc Profit` ';
        elseif($period=='three_year')
        $order=' `Part 3 Year Acc Profit` ';
        elseif($period=='yeartoday')
        $order=' `Part YearToDay Acc Profit` ';
        elseif($period=='six_month')
        $order=' `Part 6 Month Acc Profit` ';
        elseif($period=='ten_day')
        $order=' `Part 10 Day Acc Profit` ';
    } else if ($order=='avg_stock') {
        if ($period=='all')
            $order=' `Part Total Acc AVG Stock` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc AVG Stock` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc AVG Stock` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc AVG Stock` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc AVG Stock` ';

    } else if ($order=='avg_stockvalue') {
        if ($period=='all')
            $order=' `Part Total Acc AVG Stock Value` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc AVG Stock Value` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc AVG Stock Value` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc AVG Stock Value` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc AVG Stock Value` ';

    } else if ($order=='keep_days') {
        if ($period=='all')
            $order=' `Part Total Acc Keeping Days` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc Keeping Days` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc Keeping Days` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc Keeping Days` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc Keeping Days` ';
        elseif($period=='three_year')
        $order=' `Part 3 Year Acc Keeping Days` ';
        elseif($period=='yeartoday')
        $order=' `Part YearToDay Acc Keeping Days` ';
        elseif($period=='six_month')
        $order=' `Part 6 Month Acc Keeping Days` ';
        elseif($period=='ten_day')
        $order=' `Part 10 Day Acc Keeping Days` ';
    } else if ($order=='outstock_days') {
        if ($period=='all')
            $order=' `Part Total Acc Out of Stock Days` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc Out of Stock Days` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc Out of Stock Days` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc Out of Stock Days` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc Out of Stock Days` ';

    } else if ($order=='unknown_days') {
        if ($period=='all')
            $order=' `Part Total Acc Unknown Stock Days` ';
        elseif($period=='year')
        $order=' `Part 1 Year Unknown Stock Days` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc Unknown Stock Days` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc Unknown Stock Days` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc Unknown Stock Days` ';

    } else if ($order=='gmroi') {
        if ($period=='all')
            $order=' `Part Total Acc GMROI` ';
        elseif($period=='year')
        $order=' `Part 1 Year Acc GMROI` ';
        elseif($period=='quarter')
        $order=' `Part 1 Quarter Acc GMROI` ';
        elseif($period=='month')
        $order=' `Part 1 Month Acc GMROI` ';
        elseif($period=='week')
        $order=' `Part 1 Week Acc GMROI` ';

    }

    $order='P.'.$order;



    $sql="select * from $table  $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
    //print $sql; exit;
    $adata=array();
    $result=mysql_query($sql);

    // print "$period $avg";

    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)   ) {



        if ($period=='all') {
            if ($avg=='totals') {
                $sold=number($data['Part Total Acc Sold']);
                $given=number($data['Part Total Acc Given']);
                $sold_amount=money($data['Part Total Acc Sold Amount']);
                $abs_profit=money($data['Part Total Acc Profit']);
                $profit_sold=money($data['Part Total Acc Profit']);
            } else {

                if ($avg=='month')
                    $factor=$data['Part Total Acc Keeping Days']/30.4368499;
                elseif($avg=='month_eff')
                $factor=($data['Part Total Acc Keeping Days']-$data['Part Total Acc Out of Stock Days'])/30.4368499;
                elseif($avg=='week')
                $factor=$data['Part Total Acc Keeping Days']/7;
                elseif($avg=='week_eff')
                $factor=($data['Part Total Acc Keeping Days']-$data['Part Total Acc Out of Stock Days'])/7;
                else
                    $factor=1;
                if ($factor==0) {
                    $sold=0;
                    $given=0;
                    $sold_amount=money(0);
                    $abs_profit=money(0);
                    $profit_sold=money(0);
                } else {
                    $sold=number($data['Part Total Acc Sold']/$factor);
                    $given=number($data['Part Total Acc Given']/$factor);
                    $sold_amount=money($data['Part Total Acc Sold Amount']/$factor);
                    $abs_profit=money($data['Part Total Acc Profit']/$factor);
                    $profit_sold=money($data['Part Total Acc Profit']/$factor);
                }
            }

            if ($given!=0)
                $sold="$sold ($given)";
            $margin=percentage($data['Part Total Acc Margin'],1);
            $avg_stock=number($data['Part Total Acc AVG Stock']);
            $avg_stockvalue=money($data['Part Total Acc AVG Stock Value']);
            $keep_days=number($data['Part Total Acc Keeping Days'],0);
            $outstock_days=percentage($data['Part Total Acc Out of Stock Days'],$data['Part Total Acc Keeping Days']);
            $unknown_days=percentage($data['Part Total Acc Unknown Stock Days'],$data['Part Total Acc Keeping Days']);
            $gmroi=number($data['Part Total Acc GMROI'],0);

        }
        elseif($period=='three_year') {


            if ($avg=='totals') {
                $sold=number($data['Part 3 Year Acc Sold']);
                $given=number($data['Part 3 Year Acc Given']);
                $sold_amount=money($data['Part 3 Year Acc Sold Amount']);
                $abs_profit=money($data['Part 3 Year Acc Profit']);
                $profit_sold=money($data['Part 3 Year Acc Profit']);
            } else {

                if ($avg=='month')
                    $factor=$data['Part 3 Year Acc Keeping Days']/30.4368499;
                elseif($avg=='month_eff')
                $factor=($data['Part 3 Year Acc Keeping Days']-$data['Part 3 Year Acc Out of Stock Days'])/30.4368499;
                elseif($avg=='week')
                $factor=$data['Part 3 Year Acc Keeping Days']/7;
                elseif($avg=='week_eff')
                $factor=($data['Part 3 Year Acc Keeping Days']-$data['Part 3 Year Acc Out of Stock Days'])/7;
                else
                    $factor=1;
                if ($factor==0) {
                    $sold=0;
                    $given=0;
                    $sold_amount=money(0);
                    $abs_profit=money(0);
                    $profit_sold=money(0);
                } else {
                    $sold=number($data['Part 3 Year Acc Sold']/$factor);
                    $given=number($data['Part 3 Year Acc Given']/$factor);
                    $sold_amount=money($data['Part 3 Year Acc Sold Amount']/$factor);
                    $abs_profit=money($data['Part 3 Year Acc Profit']/$factor);
                    $profit_sold=money($data['Part 3 Year Acc Profit']/$factor);
                }
            }

            if ($given!=0)
                $sold="$sold ($given)";


            $margin=percentage($data['Part 3 Year Acc Margin'],1);
            $avg_stock=number($data['Part 3 Year Acc AVG Stock']);
            $avg_stockvalue=money($data['Part 3 Year Acc AVG Stock Value']);
            $keep_days=number($data['Part 3 Year Acc Keeping Days'],0);
            $outstock_days=percentage($data['Part 3 Year Acc Out of Stock Days'],$data['Part 3 Year Acc Keeping Days']);
            $unknown_days=percentage($data['Part 3 Year Acc Unknown Stock Days'],$data['Part 3 Year Acc Keeping Days']);
            $gmroi=number($data['Part 3 Year Acc GMROI'],0);



        }
        elseif($period=='year') {


            if ($avg=='totals') {
                $sold=number($data['Part 1 Year Acc Sold']);
                $given=number($data['Part 1 Year Acc Given']);
                $sold_amount=money($data['Part 1 Year Acc Sold Amount']);
                $abs_profit=money($data['Part 1 Year Acc Profit']);
                $profit_sold=money($data['Part 1 Year Acc Profit']);
            } else {

                if ($avg=='month')
                    $factor=$data['Part 1 Year Acc Keeping Days']/30.4368499;
                elseif($avg=='month_eff')
                $factor=($data['Part 1 Year Acc Keeping Days']-$data['Part 1 Year Acc Out of Stock Days'])/30.4368499;
                elseif($avg=='week')
                $factor=$data['Part 1 Year Acc Keeping Days']/7;
                elseif($avg=='week_eff')
                $factor=($data['Part 1 Year Acc Keeping Days']-$data['Part 1 Year Acc Out of Stock Days'])/7;
                else
                    $factor=1;
                if ($factor==0) {
                    $sold=0;
                    $given=0;
                    $sold_amount=money(0);
                    $abs_profit=money(0);
                    $profit_sold=money(0);
                } else {
                    $sold=number($data['Part 1 Year Acc Sold']/$factor);
                    $given=number($data['Part 1 Year Acc Given']/$factor);
                    $sold_amount=money($data['Part 1 Year Acc Sold Amount']/$factor);
                    $abs_profit=money($data['Part 1 Year Acc Profit']/$factor);
                    $profit_sold=money($data['Part 1 Year Acc Profit']/$factor);
                }
            }

            if ($given!=0)
                $sold="$sold ($given)";


            $margin=percentage($data['Part 1 Year Acc Margin'],1);
            $avg_stock=number($data['Part 1 Year Acc AVG Stock']);
            $avg_stockvalue=money($data['Part 1 Year Acc AVG Stock Value']);
            $keep_days=number($data['Part 1 Year Acc Keeping Days'],0);
            $outstock_days=percentage($data['Part 1 Year Acc Out of Stock Days'],$data['Part 1 Year Acc Keeping Days']);
            $unknown_days=percentage($data['Part 1 Year Acc Unknown Stock Days'],$data['Part 1 Year Acc Keeping Days']);
            $gmroi=number($data['Part 1 Year Acc GMROI'],0);



        }
        elseif($period=='yeartoday') {


            if ($avg=='totals') {
                $sold=number($data['Part YearToDay Acc Sold']);
                $given=number($data['Part YearToDay Acc Given']);
                $sold_amount=money($data['Part YearToDay Acc Sold Amount']);
                $abs_profit=money($data['Part YearToDay Acc Profit']);
                $profit_sold=money($data['Part YearToDay Acc Profit']);
            } else {

                if ($avg=='month')
                    $factor=$data['Part YearToDay Acc Keeping Days']/30.4368499;
                elseif($avg=='month_eff')
                $factor=($data['Part YearToDay Acc Keeping Days']-$data['Part YearToDay Acc Out of Stock Days'])/30.4368499;
                elseif($avg=='week')
                $factor=$data['Part YearToDay Acc Keeping Days']/7;
                elseif($avg=='week_eff')
                $factor=($data['Part YearToDay Acc Keeping Days']-$data['Part YearToDay Acc Out of Stock Days'])/7;
                else
                    $factor=1;
                if ($factor==0) {
                    $sold=0;
                    $given=0;
                    $sold_amount=money(0);
                    $abs_profit=money(0);
                    $profit_sold=money(0);
                } else {
                    $sold=number($data['Part YearToDay Acc Sold']/$factor);
                    $given=number($data['Part YearToDay Acc Given']/$factor);
                    $sold_amount=money($data['Part YearToDay Acc Sold Amount']/$factor);
                    $abs_profit=money($data['Part YearToDay Acc Profit']/$factor);
                    $profit_sold=money($data['Part YearToDay Acc Profit']/$factor);
                }
            }

            if ($given!=0)
                $sold="$sold ($given)";


            $margin=percentage($data['Part YearToDay Acc Margin'],1);
            $avg_stock=number($data['Part YearToDay Acc AVG Stock']);
            $avg_stockvalue=money($data['Part YearToDay Acc AVG Stock Value']);
            $keep_days=number($data['Part YearToDay Acc Keeping Days'],0);
            $outstock_days=percentage($data['Part YearToDay Acc Out of Stock Days'],$data['Part YearToDay Acc Keeping Days']);
            $unknown_days=percentage($data['Part YearToDay Acc Unknown Stock Days'],$data['Part YearToDay Acc Keeping Days']);
            $gmroi=number($data['Part YearToDay Acc GMROI'],0);



        }
        elseif($period=='six_month') {


            if ($avg=='totals') {
                $sold=number($data['Part 6 Month Acc Sold']);
                $given=number($data['Part 6 Month Acc Given']);
                $sold_amount=money($data['Part 6 Month Acc Sold Amount']);
                $abs_profit=money($data['Part 6 Month Acc Profit']);
                $profit_sold=money($data['Part 6 Month Acc Profit']);
            } else {

                if ($avg=='month')
                    $factor=$data['Part 6 Month Acc Keeping Days']/30.4368499;
                elseif($avg=='month_eff')
                $factor=($data['Part 6 Month Acc Keeping Days']-$data['Part 6 Month Acc Out of Stock Days'])/30.4368499;
                elseif($avg=='week')
                $factor=$data['Part 6 Month Acc Keeping Days']/7;
                elseif($avg=='week_eff')
                $factor=($data['Part 6 Month Acc Keeping Days']-$data['Part 6 Month Acc Out of Stock Days'])/7;
                else
                    $factor=1;
                if ($factor==0) {
                    $sold=0;
                    $given=0;
                    $sold_amount=money(0);
                    $abs_profit=money(0);
                    $profit_sold=money(0);
                } else {
                    $sold=number($data['Part 6 Month Acc Sold']/$factor);
                    $given=number($data['Part 6 Month Acc Given']/$factor);
                    $sold_amount=money($data['Part 6 Month Acc Sold Amount']/$factor);
                    $abs_profit=money($data['Part 6 Month Acc Profit']/$factor);
                    $profit_sold=money($data['Part 6 Month Acc Profit']/$factor);
                }
            }

            if ($given!=0)
                $sold="$sold ($given)";


            $margin=percentage($data['Part 6 Month Acc Margin'],1);
            $avg_stock=number($data['Part 6 Month Acc AVG Stock']);
            $avg_stockvalue=money($data['Part 6 Month Acc AVG Stock Value']);
            $keep_days=number($data['Part 6 Month Acc Keeping Days'],0);
            $outstock_days=percentage($data['Part 6 Month Acc Out of Stock Days'],$data['Part 6 Month Acc Keeping Days']);
            $unknown_days=percentage($data['Part 6 Month Acc Unknown Stock Days'],$data['Part 6 Month Acc Keeping Days']);
            $gmroi=number($data['Part 6 Month Acc GMROI'],0);



        }
        elseif($period=='quarter') {


            if ($avg=='totals') {
                $sold=number($data['Part 1 Quarter Acc Sold']);
                $given=number($data['Part 1 Quarter Acc Given']);
                $sold_amount=money($data['Part 1 Quarter Acc Sold Amount']);
                $abs_profit=money($data['Part 1 Quarter Acc Profit']);
                $profit_sold=money($data['Part 1 Quarter Acc Profit']);
            } else {

                if ($avg=='month')
                    $factor=$data['Part 1 Quarter Acc Keeping Days']/30.4368499;
                elseif($avg=='month_eff')
                $factor=($data['Part 1 Quarter Acc Keeping Days']-$data['Part 1 Quarter Acc Out of Stock Days'])/30.4368499;
                elseif($avg=='week')
                $factor=$data['Part 1 Quarter Acc Keeping Days']/7;
                elseif($avg=='week_eff')
                $factor=($data['Part 1 Quarter Acc Keeping Days']-$data['Part 1 Quarter Acc Out of Stock Days'])/7;
                else
                    $factor=1;
                if ($factor==0) {
                    $sold=0;
                    $given=0;
                    $sold_amount=money(0);
                    $abs_profit=money(0);
                    $profit_sold=money(0);
                } else {
                    $sold=number($data['Part 1 Quarter Acc Sold']/$factor);
                    $given=number($data['Part 1 Quarter Acc Given']/$factor);
                    $sold_amount=money($data['Part 1 Quarter Acc Sold Amount']/$factor);
                    $abs_profit=money($data['Part 1 Quarter Acc Profit']/$factor);
                    $profit_sold=money($data['Part 1 Quarter Acc Profit']/$factor);
                }
            }



            if ($given!=0)
                $sold="$sold ($given)";
            $margin=percentage($data['Part 1 Quarter Acc Margin'],1);
            $avg_stock=number($data['Part 1 Quarter Acc AVG Stock']);
            $avg_stockvalue=money($data['Part 1 Quarter Acc AVG Stock Value']);
            $keep_days=number($data['Part 1 Quarter Acc Keeping Days'],0);
            $outstock_days=percentage($data['Part 1 Quarter Acc Out of Stock Days'],$data['Part 1 Quarter Acc Keeping Days']);
            $unknown_days=percentage($data['Part 1 Quarter Acc Unknown Stock Days'],$data['Part 1 Quarter Acc Keeping Days']);
            $gmroi=number($data['Part 1 Quarter Acc GMROI'],0);

        }
        elseif($period=='month') {




            if ($avg=='totals') {
                $sold=number($data['Part 1 Month Acc Sold']);
                $given=number($data['Part 1 Month Acc Given']);
                $sold_amount=money($data['Part 1 Month Acc Sold Amount']);
                $abs_profit=money($data['Part 1 Month Acc Profit']);
                $profit_sold=money($data['Part 1 Month Acc Profit']);
            } else {

                if ($avg=='month')
                    $factor=$data['Part 1 Month Acc Keeping Days']/30.4368499;
                elseif($avg=='month_eff')
                $factor=($data['Part 1 Month Acc Keeping Days']-$data['Part 1 Month Acc Out of Stock Days'])/30.4368499;
                elseif($avg=='week')
                $factor=$data['Part 1 Month Acc Keeping Days']/7;
                elseif($avg=='week_eff')
                $factor=($data['Part 1 Month Acc Keeping Days']-$data['Part 1 Month Acc Out of Stock Days'])/7;
                else
                    $factor=1;
                if ($factor==0) {
                    $sold=0;
                    $given=0;
                    $sold_amount=money(0);
                    $abs_profit=money(0);
                    $profit_sold=money(0);
                } else {
                    $sold=number($data['Part 1 Month Acc Sold']/$factor);
                    $given=number($data['Part 1 Month Acc Given']/$factor);
                    $sold_amount=money($data['Part 1 Month Acc Sold Amount']/$factor);
                    $abs_profit=money($data['Part 1 Month Acc Profit']/$factor);
                    $profit_sold=money($data['Part 1 Month Acc Profit']/$factor);
                }
            }


            if ($given!=0)
                $sold="$sold ($given)";

            $margin=percentage($data['Part 1 Month Acc Margin'],1);

            $avg_stock=number($data['Part 1 Month Acc AVG Stock']);
            $avg_stockvalue=money($data['Part 1 Month Acc AVG Stock Value']);
            $keep_days=number($data['Part 1 Month Acc Keeping Days'],0);
            $outstock_days=percentage($data['Part 1 Month Acc Out of Stock Days'],$data['Part 1 Month Acc Keeping Days']);
            $unknown_days=percentage($data['Part 1 Month Acc Unknown Stock Days'],$data['Part 1 Month Acc Keeping Days']);
            $gmroi=number($data['Part 1 Month Acc GMROI'],0);


        }
        elseif($period=='ten_day') {


            if ($avg=='totals') {
                $sold=number($data['Part 10 Day Acc Sold']);
                $given=number($data['Part 10 Day Acc Given']);
                $sold_amount=money($data['Part 10 Day Acc Sold Amount']);
                $abs_profit=money($data['Part 10 Day Acc Profit']);
                $profit_sold=money($data['Part 10 Day Acc Profit']);
            } else {

                if ($avg=='month')
                    $factor=$data['Part 10 Day Acc Keeping Days']/30.4368499;
                elseif($avg=='month_eff')
                $factor=($data['Part 10 Day Acc Keeping Days']-$data['Part 10 Day Acc Out of Stock Days'])/30.4368499;
                elseif($avg=='week')
                $factor=$data['Part 10 Day Acc Keeping Days']/7;
                elseif($avg=='week_eff')
                $factor=($data['Part 10 Day Acc Keeping Days']-$data['Part 10 Day Acc Out of Stock Days'])/7;
                else
                    $factor=1;
                if ($factor==0) {
                    $sold=0;
                    $given=0;
                    $sold_amount=money(0);
                    $abs_profit=money(0);
                    $profit_sold=money(0);
                } else {
                    $sold=number($data['Part 10 Day Acc Sold']/$factor);
                    $given=number($data['Part 10 Day Acc Given']/$factor);
                    $sold_amount=money($data['Part 10 Day Acc Sold Amount']/$factor);
                    $abs_profit=money($data['Part 10 Day Acc Profit']/$factor);
                    $profit_sold=money($data['Part 10 Day Acc Profit']/$factor);
                }
            }

            if ($given!=0)
                $sold="$sold ($given)";


            $margin=percentage($data['Part 10 Day Acc Margin'],1);
            $avg_stock=number($data['Part 10 Day Acc AVG Stock']);
            $avg_stockvalue=money($data['Part 10 Day Acc AVG Stock Value']);
            $keep_days=number($data['Part 10 Day Acc Keeping Days'],0);
            $outstock_days=percentage($data['Part 10 Day Acc Out of Stock Days'],$data['Part 10 Day Acc Keeping Days']);
            $unknown_days=percentage($data['Part 10 Day Acc Unknown Stock Days'],$data['Part 10 Day Acc Keeping Days']);
            $gmroi=number($data['Part 10 Day Acc GMROI'],0);



        }
        elseif($period=='week') {


            if ($avg=='totals') {
                $sold=number($data['Part 1 Week Acc Sold']);
                $given=number($data['Part 1 Week Acc Given']);
                $sold_amount=money($data['Part 1 Week Acc Sold Amount']);
                $abs_profit=money($data['Part 1 Week Acc Profit']);
                $profit_sold=money($data['Part 1 Week Acc Profit']);
            } else {

                if ($avg=='week')
                    $factor=$data['Part 1 Week Acc Keeping Days']/30.4368499;
                elseif($avg=='week_eff')
                $factor=($data['Part 1 Week Acc Keeping Days']-$data['Part 1 Week Acc Out of Stock Days'])/30.4368499;
                elseif($avg=='week')
                $factor=$data['Part 1 Week Acc Keeping Days']/7;
                elseif($avg=='week_eff')
                $factor=($data['Part 1 Week Acc Keeping Days']-$data['Part 1 Week Acc Out of Stock Days'])/7;
                else
                    $factor=1;
                if ($factor==0) {
                    $sold=0;
                    $given=0;
                    $sold_amount=money(0);
                    $abs_profit=money(0);
                    $profit_sold=money(0);
                } else {
                    $sold=number($data['Part 1 Week Acc Sold']/$factor);
                    $given=number($data['Part 1 Week Acc Given']/$factor);
                    $sold_amount=money($data['Part 1 Week Acc Sold Amount']/$factor);
                    $abs_profit=money($data['Part 1 Week Acc Profit']/$factor);
                    $profit_sold=money($data['Part 1 Week Acc Profit']/$factor);
                }
            }



            if ($given!=0)
                $sold="$sold ($given)";
            $margin=percentage($data['Part 1 Week Acc Margin'],1);
            $avg_stock=number($data['Part 1 Week Acc AVG Stock']);
            $avg_stockvalue=money($data['Part 1 Week Acc AVG Stock Value']);
            $keep_days=number($data['Part 1 Week Acc Keeping Days'],0);
            $outstock_days=percentage($data['Part 1 Week Acc Out of Stock Days'],$data['Part 1 Week Acc Keeping Days']);
            $unknown_days=percentage($data['Part 1 Week Acc Unknown Stock Days'],$data['Part 1 Week Acc Keeping Days']);
            $gmroi=number($data['Part 1 Week Acc GMROI'],0);

        }



        $adata[]=array(
                     'sku'=>sprintf(
                               '<a href="part.php?id=%d">%06d</a>',$data['Part SKU'],$data['Part SKU']),
                     'description'=>$data['Part XHTML Description'],
                     'used_in'=>$data['Part XHTML Currently Used In'],
                     'supplied_by'=>$data['Part XHTML Currently Supplied By'],
                     'stock'=>number($data['Part Current Stock']),
                     'available_for'=>interval($data['Part XHTML Available For Forecast']),
                     'stock_value'=>money($data['Part Current Value']),
                     'sold'=>$sold,
                     'given'=>$given,
                     'money_in'=>$sold_amount,
                     'profit'=>$abs_profit,
                     'profit_sold'=>$profit_sold,
                     'margin'=>$margin,
                     'avg_stock'=>$avg_stock,
                     'avg_stockvalue'=>$avg_stockvalue,
                     'keep_days'=>$keep_days,
                     'outstock_days'=>$outstock_days,
                     'unknown_days'=>$unknown_days,
                     'gmroi'=>$gmroi
                 );
    }
    /*
        $total_title=_('Total');

        $adata[]=array(

                     'sku'=>$total_title,
                 );

        $total_records=ceil($total_records/$number_results)+$total_records;
    */
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
                                    'records_offset'=>$start_from,
                                    'records_perpage'=>$number_results,
                                )
                   );
    echo json_encode($response);
}

function list_products_with_same_code() {
    $conf=$_SESSION['state']['product']['server'];
    $tableid=0;
    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];


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






    $code=$_SESSION['state']['product']['server']['tag'];
    $where=sprintf('where `Product Code`=%s  ',prepare_mysql($code));
    $wheref='';

    $order_direction=$order_dir;
    $_order=$order;
    $_dir=$order_direction;
    if ($order=='store')
        $order='`Store Name`';

    $sql="select *  from `Product Dimension` left join `Store Dimension` S  on (`Store Key`=`Product Store Key`) $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
    // print $sql;
    $res = mysql_query($sql);
    $number_results=mysql_num_rows($res);

    $adata=array();
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $id=sprintf("<a href='product.php?pid=%d'>%05d</a>",$row['Product ID'],$row['Product ID']);
        $store=sprintf("<a href='product.php?pid=%d'>%s</a>",$row['Product Store Key'],$row['Store Code']);
        $adata[]=array(
                     'id'=>$id,
                     'description'=>$row['Product XHTML Short Description'],
                     'store'=>$store,
                     'parts'=>$row['Product XHTML Parts']
                 );

    }
    mysql_free_result($res);
    $rtext=number($number_results).' '._('products with the same code');
    $rtext_rpp='';
    $filter_msg='';
    $total_records=$number_results;

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

function find_part() {

    if (!isset($_REQUEST['query']) or $_REQUEST['query']=='') {
        $response= array(
                       'state'=>400,
                       'data'=>array()
                   );
        echo json_encode($response);
        return;
    }


    if (isset($_REQUEST['except']) and  isset($_REQUEST['except_id'])  and   is_numeric($_REQUEST['except_id']) and $_REQUEST['except']=='location' ) {

        $sql=sprintf("select `Part SKU`,`Part XHTML Description`,`Part Currently Used In` from `Part Dimension` where  (`Part SKU`=%d or `Part XHTML Currently Used In` like '%%%s%%' ) limit 20 ",$_REQUEST['query'],addslashes($_REQUEST['query']));

    } else {
        $sql=sprintf("select `Part SKU`,`Part XHTML Description`,`Part Currently Used In` from `Part Dimension` where  (`Part SKU`=%d or `Part XHTML Currently Used In` like '%%%s%%' ) limit 20",$_REQUEST['query'],addslashes($_REQUEST['query']));

    }
    //print $sql;

    $_data=array();
    $res=mysql_query($sql);

    //  $qty_on_hand=0;
//        $location_key=$_REQUEST['except_id'];

    while ($data=mysql_fetch_array($res)) {
        //$loc_sku=$location_key.'_'.$data['Part SKU'];


        $_data[]= array(

                      'info'=>sprintf("%s%05d - %s",_('SKU'),$data['Part SKU'],$data['Part XHTML Description'])
                             ,'info_plain'=>sprintf("%s%05d - %s",_('SKU'),$data['Part SKU'],strip_tags($data['Part XHTML Description']))

                                           ,'sku'=>$data['Part SKU']
                                                  ,'formated_sku'=>sprintf("%s%05d",_('SKU'),$data['Part SKU'])
                                                                  ,'description'=>$data['Part XHTML Description']
                                                                                 ,'usedin'=>$data['Part Currently Used In']

                                                                                           //	 'sku'=>sprintf('<a href="part.php?sku=%d">%s</a>',$data['Part SKU'],$data['Part SKU'])
                                                                                           // ,'description'=>$data['Part XHTML Description']
                                                                                           //  ,'current_qty'=>sprintf('<span  used="0"  value="%s" id="s%s"  onclick="fill_value(%s,%d,%d)">%s</span>',$qty_on_hand,$loc_sku,$qty_on_hand,$location_key,$data['Part SKU'],number($qty_on_hand))
                                                                                           // 			 ,'changed_qty'=>sprintf('<span   used="0" id="cs%s"  onclick="change_reset(\'%s\',%d)"   ">0</span>',$loc_sku,$loc_sku,$data['Part SKU'])
// 			 ,'new_qty'=>sprintf('<span  used="0"  value="%s" id="ns%s"  onclick="fill_value(%s,%d,%d)">%s</span>',$qty_on_hand,$loc_sku,$qty_on_hand,$location_key,$data['Part SKU'],number($qty_on_hand))
// 			 ,'_qty_move'=>'<input id="qm'.$loc_sku.'" onchange="qty_changed(\''.$loc_sku.'\','.$data['Part SKU'].')" type="text" value="" size=3>'
// 			 ,'_qty_change'=>'<input id="qc'.$loc_sku.'" onchange="qty_changed(\''.$loc_sku.'\','.$data['Part SKU'].')" type="text" value="" size=3>'
// 			 ,'_qty_damaged'=>'<input id="qd'.$loc_sku.'" onchange="qty_changed(\''.$loc_sku.'\','.$data['Part SKU'].')" type="text" value="" size=3>'
// 			 ,'note'=>'<input  id="n'.$loc_sku.'" type="text" value="" style="width:100px">'
// 			 ,'delete'=>($qty_on_hand==0?'<img onclick="remove_prod('.$location_key.','.$data['Part SKU'].')" style="cursor:pointer" title="'._('Remove').' '.$data['Part SKU'].'" alt="'._('Desassociate Product').'" src="art/icons/cross.png".>':'')
// 			 ,'part_sku'=>$data['Part SKU']

                  );
    }
    $response= array(
                   'state'=>200,
                   'data'=>$_data
               );
    echo json_encode($response);


}

function list_families() {

    global $user;
    if (isset( $_REQUEST['parent']))
        $parent=$_REQUEST['parent'];
    else
        $parent='none';

    if ($parent=='department') {

        $conf=$_SESSION['state']['department']['families'];
        $conf_table='department';

    }
    elseif ($parent=='store') {

        $conf=$_SESSION['state']['store']['families'];
        $conf_table='store';

    }
    elseif ($parent=='none') {

        $conf=$_SESSION['state']['stores']['families'];
        $conf_table='stores';
    }
    else {

        return;
    }



    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['sf'];

    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr']-1;

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

    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
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



    if (isset( $_REQUEST['percentages'])) {
        $percentages=$_REQUEST['percentages'];

    } else
        $percentages=$conf['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];

    } else
        $period=$conf['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];

    } else
        $avg=$conf['avg'];




    if (isset( $_REQUEST['mode']))
        $mode=$_REQUEST['mode'];
    else
        $mode=$conf['mode'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    if (isset( $_REQUEST['elements']))
        $elements=$_REQUEST['elements'];
    else
        $elements=$conf['elements'];



    if (isset( $_REQUEST['elements_family_discontinued'])) {
        $elements['Discontinued']=$_REQUEST['elements_family_discontinued'];
    }
    if (isset( $_REQUEST['elements_family_discontinuing'])) {
        $elements['Discontinuing']=$_REQUEST['elements_family_discontinuing'];
    }
    if (isset( $_REQUEST['elements_family_normal'])) {
        $elements['Normal']=$_REQUEST['elements_family_normal'];
    }
    if (isset( $_REQUEST['elements_family_inprocess'])) {
        $elements['InProcess']=$_REQUEST['elements_family_inprocess'];
    }

    if (isset( $_REQUEST['elements_family_nosale'])) {
        $elements['NoSale']=$_REQUEST['elements_family_nosale'];
    }



    $filter_msg='';



    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    //print_r($_SESSION['state']['department']);

    $_SESSION['state'][$conf_table]['families']['order']=$order;
    $_SESSION['state'][$conf_table]['families']['order_dir']=$order_dir;
    $_SESSION['state'][$conf_table]['families']['nr']=$number_results;
    $_SESSION['state'][$conf_table]['families']['sf']=$start_from;
    $_SESSION['state'][$conf_table]['families']['where']=$where;
    $_SESSION['state'][$conf_table]['families']['f_field']=$f_field;
    $_SESSION['state'][$conf_table]['families']['f_value']=$f_value;
    $_SESSION['state'][$conf_table]['families']['period']=$period;
    $_SESSION['state'][$conf_table]['families']['avg']=$avg;

    $_SESSION['state'][$conf_table]['families']['mode']=$mode;
    $_SESSION['state'][$conf_table]['families']['elements']=$elements;
    $_SESSION['state'][$conf_table]['families']['parent']=$parent;

    //  $where.=" and `Product Department Key`=".$id;
//print $conf_table;
//print_r($_SESSION['state'][$conf_table]['families']);


    switch ($parent) {
    case('store'):

        $where.=sprintf(' and `Product Family Store Key`=%d',$_SESSION['state']['store']['id']);
        break;
    case('department'):

        $where.=sprintf(' and `Product Family Main Department Key`=%d',$_SESSION['state']['department']['id']);
        break;
    default:
        if (count($user->stores)==0)
            $where="where false";
        else {

            $where=sprintf("where `Product Family Store Key` in (%s) ",join(',',$user->stores));
        }

    }


    $_elements='';



    foreach($elements as $_key=>$_value) {
        if ($_value)
            $_elements.=','.prepare_mysql($_key);
    }
    $_elements=preg_replace('/^\,/','',$_elements);
    if ($_elements=='') {
        $where.=' and false' ;
    } else {
        $where.=' and `Product Family Record Type` in ('.$_elements.')' ;
    }









    $filter_msg='';
    $wheref='';
    if ($f_field=='code' and $f_value!='')
        $wheref.=" and `Product Family Code`  like '".addslashes($f_value)."%'";
    if ($f_field=='description' and $f_value!='')
        $wheref.=" and `Product Family Name`  like '%".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Product Family Dimension`      $where $wheref";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total  from `Product Family Dimension`    $where ";
        $res=mysql_query($sql);
        if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }

    }
    $rtext=$total_records." ".ngettext('family','families',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';



    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any family with code like")." <b>".$f_value."*</b> ";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any family with this description").": <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('families with code like')." <b>".$f_value."*</b>";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('families with this description')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';






    $_order=$order;
    $_dir=$order_direction;
    // $order='`Product Family Code`';
    if ($order=='profit') {
        if ($period=='all')
            $order='`Product Family Total Profit`';
        elseif($period=='year')
        $order='`Product Family 1 Year Acc Profit`';
        elseif($period=='quarter')
        $order='`Product Family 1 Quarter Acc Profit`';
        elseif($period=='month')
        $order='`Product Family 1 Month Acc Profit`';
        elseif($period=='week')
        $order='`Product Family 1 Week Acc Profit`';
    }
    elseif($order=='sales') {
        if ($period=='all')
            $order='`Product Family Total Invoiced Amount`';
        elseif($period=='year')
        $order='`Product Family 1 Year Acc Invoiced Amount`';
        elseif($period=='quarter')
        $order='`Product Family 1 Quarter Acc Invoiced Amount`';
        elseif($period=='month')
        $order='`Product Family 1 Month Acc Invoiced Amount`';
        elseif($period=='week')
        $order='`Product Family 1 Week Acc Invoiced Amount`';
// --------------------------------Start for families' 3Y,YTD,6M,10D------------------------------------------------
        elseif($period=='three_year')
        $order='`Product Family 3 Year Acc Invoiced Amount`';
        elseif($period=='yeartoday')
        $order='`Product Family YearToDay Acc Invoiced Amount`';
        elseif($period=='six_month')
        $order='`Product Family 6 Month Acc Invoiced Amount`';
        elseif($period=='ten_day')
        $order='`Product Family 10 Day Acc Invoiced Amount`';
// --------------------------------Ends for families' 3Y,YTD,6M,10D------------------------------------------------
    }
    elseif($order=='code')
    $order='`Product Family Code`';
    elseif($order=='stock_value')
    $order='`Product Family Stock Value`';
    elseif($order=='name')
    $order='`Product Family Name`';
    elseif($order=='active')
    $order='`Product Family For Public Sale Products`';
    elseif($order=='discontinued')
    $order='`Product Family Discontinued Products`';
    elseif($order=='todo')
    $order='`Product Family In Process Products`';
    elseif($order=='notforsale')
    $order='`Product Family Not For Sale Products`';

    elseif($order=='outofstock')
    $order='`Product Family Out Of Stock Products`';
    elseif($order=='stock_error')
    $order='`Product Family Unknown Stock Products`';
    elseif($order=='surplus')
    $order='`Product Family Surplus Availability Products`';
    elseif($order=='optimal')
    $order='`Product Family Optimal Availability Products`';
    elseif($order=='low')
    $order='`Product Family Low Availability Products`';
    elseif($order=='critical')
    $order='`Product Family Critical Availability Products`';
    else
        $order='`Product Family Code`';



    $sum_active=0;
    $sum_discontinued=0;
    $sum_new=0;
    $sum_todo=0;
    $sql="select sum(`Product Family In Process Products`) as sum_todo,sum(`Product Family For Public Sale Products`) as sum_active, sum(`Product Family Discontinued Products`) as sum_discontinued  from `Product Family Dimension`  $where $wheref ";

    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $sum_discontinued=$row['sum_discontinued'];
        $sum_active=$row['sum_active'];
        $sum_todo=$row['sum_todo'];
    }



    if ($period=='all') {


        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Product Family Total Profit`<0,`Product Family Total Profit`,0)) as total_profit_minus,sum(if(`Product Family Total Profit`>=0,`Product Family Total Profit`,0)) as total_profit_plus,sum(`Product Family Total Invoiced Amount`) as sum_total_sales   from `Product Family Dimension` $where $wheref   ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
    }
    elseif($period=='three_year') {
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Product Family 3 Year Acc Profit`<0,`Product Family 3 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Product Family 3 Year Acc Profit`>=0,`Product Family 3 Year Acc Profit`,0)) as total_profit_plus,sum(`Product Family 3 Year Acc Invoiced Amount`) as sum_total_sales  from `Product Family Dimension`  $where $wheref   ";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $sum_total_sales=$row['sum_total_sales'];
            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
    }
    elseif($period=='yeartoday') {
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Product Family YearToDay Acc Profit`<0,`Product Family YearToDay Acc Profit`,0)) as total_profit_minus,sum(if(`Product Family YearToDay Acc Profit`>=0,`Product Family YearToDay Acc Profit`,0)) as total_profit_plus,sum(`Product Family YearToDay Acc Invoiced Amount`) as sum_total_sales  from `Product Family Dimension`  $where $wheref   ";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $sum_total_sales=$row['sum_total_sales'];
            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
    }
    elseif($period=='year') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Product Family 1 Year Acc Profit`<0,`Product Family 1 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Product Family 1 Year Acc Profit`>=0,`Product Family 1 Year Acc Profit`,0)) as total_profit_plus,sum(`Product Family 1 Year Acc Invoiced Amount`) as sum_total_sales  from `Product Family Dimension`  $where $wheref   ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
    }
    elseif($period=='six_month') {
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Product Family 6 Month Acc Profit`<0,`Product Family 6 Month Acc Profit`,0)) as total_profit_minus,sum(if(`Product Family 6 Month Acc Profit`>=0,`Product Family 6 Month Acc Profit`,0)) as total_profit_plus,sum(`Product Family 6 Month Acc Invoiced Amount`) as sum_total_sales  from `Product Family Dimension`  $where $wheref   ";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $sum_total_sales=$row['sum_total_sales'];
            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
    }
    elseif($period=='quarter') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Product Family 1 Quarter Acc Profit`<0,`Product Family 1 Quarter Acc Profit`,0)) as total_profit_minus,sum(if(`Product Family 1 Quarter Acc Profit`>=0,`Product Family 1 Quarter Acc Profit`,0)) as total_profit_plus,sum(`Product Family 1 Quarter Acc Invoiced Amount`) as sum_total_sales   from `Product Family Dimension`  $where $wheref   ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
    }
    elseif($period=='month') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Product Family 1 Month Acc Profit`<0,`Product Family 1 Month Acc Profit`,0)) as total_profit_minus,sum(if(`Product Family 1 Month Acc Profit`>=0,`Product Family 1 Month Acc Profit`,0)) as total_profit_plus,sum(`Product Family 1 Month Acc Invoiced Amount`) as sum_total_sales   from `Product Family Dimension`  $where $wheref   ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
    }
    elseif($period=='ten_day') {
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Product Family 10 Day Acc Profit`<0,`Product Family 10 Day Acc Profit`,0)) as total_profit_minus,sum(if(`Product Family 10 Day Acc Profit`>=0,`Product Family 10 Day Acc Profit`,0)) as total_profit_plus,sum(`Product Family 10 Day Acc Invoiced Amount`) as sum_total_sales  from `Product Family Dimension`  $where $wheref   ";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $sum_total_sales=$row['sum_total_sales'];
            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
    }
    elseif($period=='week') {
        $sum_families=0;
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Product Family 1 Week Acc Profit`<0,`Product Family 1 Week Acc Profit`,0)) as total_profit_minus,sum(if(`Product Family 1 Week Acc Profit`>=0,`Product Family 1 Week Acc Profit`,0)) as total_profit_plus,sum(`Product Family 1 Week Acc Invoiced Amount`) as sum_total_sales   from `Product Family Dimension`  $where $wheref   ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
    }



    $sql="select *  from `Product Family Dimension`  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);
    $adata=array();
    //print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $code=sprintf('<a href="family.php?id=%d">%s</a>',$row['Product Family Key'],$row['Product Family Code']);
        if ($percentages) {
            if ($period=='all') {
                $tsall=percentage($row['Product Family Total Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Family Total Profit']>=0)
                    $tprofit=percentage($row['Product Family Total Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Family Total Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='year') {
                $tsall=percentage($row['Product Family 1 Year Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Family 1 Year Acc Profit']>=0)
                    $tprofit=percentage($row['Product Family 1 Year Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Family 1 Year Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='quarter') {
                $tsall=percentage($row['Product Family 1 Quarter Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Family 1 Quarter Acc Profit']>=0)
                    $tprofit=percentage($row['Product Family 1 Quarter Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Family 1 Quarter Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='month') {
                $tsall=percentage($row['Product Family 1 Month Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Family 1 Month Acc Profit']>=0)
                    $tprofit=percentage($row['Product Family 1 Month Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Family 1 Month Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='week') {
                $tsall=percentage($row['Product Family 1 Week Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Product Family 1 Week Acc Profit']>=0)
                    $tprofit=percentage($row['Product Family 1 Week Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Product Family 1 Week Acc Profit'],$sum_total_profit_minus,2);
            }


        } else {






            if ($period=='all') {


                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Family Total Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family Total Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Family Total Days On Sale']>0)
                        $factor=7/$row['Product Family Total Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Family Total Days Available']>0)
                        $factor=30.4368499/$row['Product Family Total Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Family Total Days Available']>0)
                        $factor=7/$row['Product Family Total Days Available'];
                    else
                        $factor=0;
                }

                $tsall=money($row['Product Family Total Invoiced Amount']*$factor);
                $tprofit=money($row['Product Family Total Profit']*$factor);




            }

// ---------------------------------------Start for families 3 year-----------------------------------------
            elseif($period=='three_year') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Family 3 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 3 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Family 3 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 3 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Family 3 Year Acc Days On Sale']>0)
                        $factor=7/$row['Product Family 3 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Family 3 Year Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Family 3 Year Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Family 3 Year Acc Days Available']>0)
                        $factor=7/$row['Product Family 3 Year Acc Days Available'];
                    else
                        $factor=0;
                }
                $tsall=money($row['Product Family 3 Year Acc Invoiced Amount']*$factor);
                $tprofit=money($row['Product Family 3 Year Acc Profit']*$factor);
            }
// ---------------------------------------Ends for families 3 year-------------------------------------------

            elseif($period=='year') {


                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Family 1 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Family 1 Year Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Family 1 Year Acc Days On Sale']>0)
                        $factor=7/$row['Product Family 1 Year Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Family 1 Year Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Family 1 Year Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Family 1 Year Acc Days Available']>0)
                        $factor=7/$row['Product Family 1 Year Acc Days Available'];
                    else
                        $factor=0;
                }









                $tsall=money($row['Product Family 1 Year Acc Invoiced Amount']*$factor);
                $tprofit=money($row['Product Family 1 Year Acc Profit']*$factor);
            }

// ---------------------------------------Start for families YearToday-----------------------------------------
            elseif($period=='yeartoday') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Family YearToDay Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family YearToDay Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Family YearToDay Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family YearToDay Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Family YearToDay Acc Days On Sale']>0)
                        $factor=7/$row['Product Family YearToDay Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Family YearToDay Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Family YearToDay Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Family YearToDay Acc Days Available']>0)
                        $factor=7/$row['Product Family YearToDay Acc Days Available'];
                    else
                        $factor=0;
                }
                $tsall=money($row['Product Family YearToDay Acc Invoiced Amount']*$factor);
                $tprofit=money($row['Product Family YearToDay Acc Profit']*$factor);
            }
// ---------------------------------------Ends for families YearToDay-------------------------------------------
// ---------------------------------------Start for families 6 month-----------------------------------------
            elseif($period=='six_month') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Family 6 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 6 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Family 6 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 6 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Family 6 Month Acc Days On Sale']>0)
                        $factor=7/$row['Product Family 6 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Family 6 Month Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Family 6 Month Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Family 6 Month Acc Days Available']>0)
                        $factor=7/$row['Product Family 6 Month Acc Days Available'];
                    else
                        $factor=0;
                }
                $tsall=money($row['Product Family 6 Month Acc Invoiced Amount']*$factor);
                $tprofit=money($row['Product Family 6 Month Acc Profit']*$factor);
            }
// ---------------------------------------Ends for families 6 month-------------------------------------------

            elseif($period=='quarter') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Family 1 Quarter Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Quarter Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Family 1 Quarter Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Quarter Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Family 1 Quarter Acc Days On Sale']>0)
                        $factor=7/$row['Product Family 1 Quarter Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Family 1 Quarter Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Family 1 Quarter Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Family 1 Quarter Acc Days Available']>0)
                        $factor=7/$row['Product Family 1 Quarter Acc Days Available'];
                    else
                        $factor=0;
                }


                $tsall=money($row['Product Family 1 Quarter Acc Invoiced Amount']*$factor);
                $tprofit=money($row['Product Family 1 Quarter Acc Profit']*$factor);
            }



            elseif($period=='month') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Family 1 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Family 1 Month Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Family 1 Month Acc Days On Sale']>0)
                        $factor=7/$row['Product Family 1 Month Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Family 1 Month Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Family 1 Month Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Family 1 Month Acc Days Available']>0)
                        $factor=7/$row['Product Family 1 Month Acc Days Available'];
                    else
                        $factor=0;
                }


                $tsall=money($row['Product Family 1 Month Acc Invoiced Amount']*$factor);
                $tprofit=money($row['Product Family 1 Month Acc Profit']*$factor);
            }

// ---------------------------------------Start for families 10 days-----------------------------------------
            elseif($period=='ten_day') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Family 10 Day Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 10 Day Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Family 10 Day Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 10 Day Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Family 10 Day Acc Days On Sale']>0)
                        $factor=7/$row['Product Family 10 Day Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Family 10 Day Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Family 10 Day Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Family 10 Day Acc Days Available']>0)
                        $factor=7/$row['Product Family 10 Day Acc Days Available'];
                    else
                        $factor=0;
                }
                $tsall=money($row['Product Family 10 Day Acc Invoiced Amount']*$factor);
                $tprofit=money($row['Product Family 10 Day Acc Profit']*$factor);
            }
// ---------------------------------------Ends for families 10 days-------------------------------------------


            elseif($period=='week') {
                if ($avg=='totals')
                    $factor=1;
                elseif($avg=='month') {
                    if ($row['Product Family 1 Week Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Week Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month') {
                    if ($row['Product Family 1 Week Acc Days On Sale']>0)
                        $factor=30.4368499/$row['Product Family 1 Week Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='week') {
                    if ($row['Product Family 1 Week Acc Days On Sale']>0)
                        $factor=7/$row['Product Family 1 Week Acc Days On Sale'];
                    else
                        $factor=0;
                }
                elseif($avg=='month_eff') {
                    if ($row['Product Family 1 Week Acc Days Available']>0)
                        $factor=30.4368499/$row['Product Family 1 Week Acc Days Available'];
                    else
                        $factor=0;
                }
                elseif($avg=='week_eff') {
                    if ($row['Product Family 1 Week Acc Days Available']>0)
                        $factor=7/$row['Product Family 1 Week Acc Days Available'];
                    else
                        $factor=0;
                }


                $tsall=money($row['Product Family 1 Week Acc Invoiced Amount']*$factor);
                $tprofit=money($row['Product Family 1 Week Acc Profit']*$factor);
            }



        }
        $store=sprintf('<a href="store.php?id=%d">%s</a>',$row['Product Family Store Key'],$row['Product Family Store Code']);
        $department=sprintf('<a href="department.php?id=%d">%s</a>',$row['Product Family Main Department Key'],$row['Product Family Main Department Code']);

        $adata[]=array(

                     'code'=>$code,
                     'name'=>$row['Product Family Name'],
                     'active'=>number($row['Product Family For Public Sale Products']),
                     'todo'=>number($row['Product Family In Process Products']),
                     'discontinued'=>number($row['Product Family Discontinued Products']),
                     'notforsale'=>number($row['Product Family Not For Sale Products']),

                     'outofstock'=>number($row['Product Family Out Of Stock Products']),
                     'stock_error'=>number($row['Product Family Unknown Stock Products']),
                     'stock_value'=>money($row['Product Family Stock Value']),
                     'store'=>$store,
                     'department'=>$department,
                     'sales'=>$tsall,
                     'profit'=>$tprofit,
                     'surplus'=>number($row['Product Family Surplus Availability Products']),
                     'optimal'=>number($row['Product Family Optimal Availability Products']),
                     'low'=>number($row['Product Family Low Availability Products']),
                     'critical'=>number($row['Product Family Critical Availability Products']),
                     'image'=>$row['Product Family Main Image'],
                     'type'=>'item'

                 );
    }
    mysql_free_result($res);

    if ($total<=$number_results and $total>1) {


        if ($percentages) {
            $tsall='100.00%';
            $tprofit='100.00%';
        } else {
            $tsall=money($sum_total_sales);
            $tprofit=money($sum_total_profit);
        }

        $adata[]=array(

                     'code'=>_('Total'),
                     'name'=>'',
                     'active'=>number($sum_active),
                     'discontinued'=>number($sum_discontinued),
                     'todo'=>number($sum_todo),

// 		 'outofstock'=>number($row['product family out of stock products']),
// 		 'stockerror'=>number($row['product family unknown stock products']),
// 		 'stock_value'=>money($row['product family stock value']),
                     'sales'=>$tsall,
                     'profit'=>$tprofit

                 );

    } else {
        $adata[]=array();
    }
    $total_records=ceil($total/$number_results)+$total;
    $number_results++;

    if ($start_from==0)
        $record_offset=0;
    else
        $record_offset=$start_from+1;

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

function list_stores() {
    global $user;
    $conf=$_SESSION['state']['stores']['stores'];

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


    if (isset( $_REQUEST['exchange_type']))
        $exchange_type=addslashes($_REQUEST['exchange_type']);
    else
        $exchange_type=$conf['exchange_type'];

    if (isset( $_REQUEST['exchange_value']))
        $exchange_value=addslashes($_REQUEST['exchange_value']);
    else
        $exchange_value=$conf['exchange_value'];

    if (isset( $_REQUEST['show_default_currency']))
        $show_default_currency=addslashes($_REQUEST['show_default_currency']);
    else
        $show_default_currency=$conf['show_default_currency'];




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
        $_SESSION['state']['stores']['stores']['percentages']=$percentages;
    } else
        $percentages=$_SESSION['state']['stores']['stores']['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];
        $_SESSION['state']['stores']['stores']['period']=$period;
    } else
        $period=$_SESSION['state']['stores']['stores']['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];
        $_SESSION['state']['stores']['stores']['avg']=$avg;
    } else
        $avg=$_SESSION['state']['stores']['stores']['avg'];


    $_SESSION['state']['stores']['stores']['exchange_type']=$exchange_type;
    $_SESSION['state']['stores']['stores']['exchange_value']=$exchange_value;
    $_SESSION['state']['stores']['stores']['show_default_currency']=$show_default_currency;
    $_SESSION['state']['stores']['stores']['order']=$order;
    $_SESSION['state']['stores']['stores']['order_dir']=$order_dir;
    $_SESSION['state']['stores']['stores']['nr']=$number_results;
    $_SESSION['state']['stores']['stores']['sf']=$start_from;
    $_SESSION['state']['stores']['stores']['where']=$where;
    $_SESSION['state']['stores']['stores']['f_field']=$f_field;
    $_SESSION['state']['stores']['stores']['f_value']=$f_value;

    if (count($user->stores)==0)
        $where="where false";
    else
        $where=sprintf("where S.`Store Key` in (%s)",join(',',$user->stores));
    $filter_msg='';
    $wheref=wheref_stores($f_field,$f_value);

    $sql="select count(*) as total from `Store Dimension`  S $where $wheref";
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Store Dimension` S  $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('store','stores',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;
    $order='`Store Code`';
    if ($order=='families')
        $order='`Store Families`';
    elseif($order=='departments')
    $order='`Store Departments`';
    elseif($order=='code')
    $order='`Store Code`';
    elseif($order=='todo')
    $order='`Store In Process Products`';
    elseif($order=='discontinued')
    $order='`Store In Process Products`';
    else if ($order=='profit') {
        if ($period=='all')
            $order='`Store Total Profit`';
        elseif($period=='year')
        $order='`Store 1 Year Acc Profit`';
        elseif($period=='quarter')
        $order='`Store 1 Quarter Acc Profit`';
        elseif($period=='month')
        $order='`Store 1 Month Acc Profit`';
        elseif($period=='week')
        $order='`Store 1 Week Acc Profit`';
    }
    elseif($order=='sales') {
        if ($period=='all')
            $order='`Store Total Invoiced Amount`';
        elseif($period=='year')
        $order='`Store 1 Year Acc Invoiced Amount`';
        elseif($period=='quarter')
        $order='`Store 1 Quarter Acc Invoiced Amount`';
        elseif($period=='month')
        $order='`Store 1 Month Acc Invoiced Amount`';
        elseif($period=='week')
        $order='`Store 1 Week Acc Invoiced Amount`';
        elseif($period=='yeartoday')
        $order='`Store YearToDay Acc Invoiced Amount`';
        elseif($period=='three_year')
        $order='`Store 3 Year Acc Invoiced Amount`';
        elseif($period=='six_month')
        $order='`Store 6 Month Acc Invoiced Amount`';
        elseif($period=='ten_day')
        $order='`Store 10 Day Acc Invoiced Amount`';


    }
    elseif($order=='name')
    $order='`Store Name`';
    elseif($order=='active')
    $order='`Store For Public Sale Products`';
    elseif($order=='outofstock')
    $order='`Store Out Of Stock Products`';
    elseif($order=='stock_error')
    $order='`Store Unknown Stock Products`';
    elseif($order=='surplus')
    $order='`Store Surplus Availability Products`';
    elseif($order=='optimal')
    $order='`Store Optimal Availability Products`';
    elseif($order=='low')
    $order='`Store Low Availability Products`';
    elseif($order=='critical')
    $order='`Store Critical Availability Products`';
    elseif($order=='new')
    $order='`Store New Products`';


    $sql="select sum(`Store For Public Sale Products`) as sum_active,sum(`Store Families`) as sum_families  from `Store Dimension` S $where $wheref   ";
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $sum_families=$row['sum_families'];
        $sum_active=$row['sum_active'];
    }
    mysql_free_result($result);

    global $myconf;

    if ($period=='all') {


        $sum_total_sales=0;
        $sum_month_sales=0;
        $sum_total_profit_plus=0;
        $sum_total_profit_minus=0;
        $sum_total_profit=0;
        if ($exchange_type=='day2day') {
            $sql=sprintf("select sum(if(`Store DC Total Profit`<0,`Store DC Total Profit`,0)) as total_profit_minus,sum(if(`Store DC Total Profit`>=0,`Store DC Total Profit`,0)) as total_profit_plus,sum(`Store DC Total Invoiced Amount`) as sum_total_sales  from `Store Default Currency`  S  left join `Store Dimension` SD on (`SD`.`Store Key`=`S`.`Store Key`)  %s %s",$where,$wheref);
            //  print $sql;
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales+=$row['sum_total_sales'];

                $sum_total_profit_plus=+$row['total_profit_plus'];
                $sum_total_profit_minus=+$row['total_profit_minus'];
                $sum_total_profit+=$row['total_profit_plus']-$row['total_profit_minus'];
            }
            mysql_free_result($result);
        } else {
            $sql=sprintf("select sum(if(`Store Total Profit`<0,`Store Total Profit`,0)) as total_profit_minus,sum(if(`Store Total Profit`>=0,`Store Total Profit`,0)) as total_profit_plus,sum(`Store Total Invoiced Amount`) as sum_total_sales  from `Store Dimension`  S   %s %s and `Store Currency Code`!= %s ",$where,$wheref,prepare_mysql($myconf['currency_code']));
            //print $sql;
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales+=$row['sum_total_sales']*$exchange_value;

                $sum_total_profit_plus+=$row['total_profit_plus']*$exchange_value;
                $sum_total_profit_minus+=$row['total_profit_minus']*$exchange_value;
                $sum_total_profit+=$row['total_profit_plus']-$row['total_profit_minus'];
            }
            mysql_free_result($result);

        }



    }
    elseif($period=='year') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sum_total_profit_plus=0;
        $sum_total_profit_minus=0;
        $sum_total_profit=0;



        if ($exchange_type=='day2day') {
            $sql=sprintf("select sum(if(`Store DC 1 Year Acc Profit`<0,`Store DC 1 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Store DC 1 Year Acc Profit`>=0,`Store DC 1 Year Acc Profit`,0)) as total_profit_plus,sum(`Store DC 1 Year Acc Invoiced Amount`) as sum_total_sales  from `Store Default Currency`  S left join `Store Dimension` SD on (SD.`Store Key`=S.`Store Key`)  %s %s",$where,$wheref);
            //print $sql;
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales+=$row['sum_total_sales'];

                $sum_total_profit_plus=+$row['total_profit_plus'];
                $sum_total_profit_minus=+$row['total_profit_minus'];
                $sum_total_profit+=$row['total_profit_plus']-$row['total_profit_minus'];
            }
            mysql_free_result($result);
        } else {
            $sql=sprintf("select sum(if(`Store 1 Year Acc Profit`<0,`Store 1 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Store 1 Year Acc Profit`>=0,`Store 1 Year Acc Profit`,0)) as total_profit_plus,sum(`Store 1 Year Acc Invoiced Amount`) as sum_total_sales  from `Store Dimension`  S   %s %s and `Store Currency Code`!= %s ",$where,$wheref,prepare_mysql($myconf['currency_code']));
            //print $sql;
            $result=mysql_query($sql);
            if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

                $sum_total_sales+=$row['sum_total_sales']*$exchange_value;

                $sum_total_profit_plus+=$row['total_profit_plus']*$exchange_value;
                $sum_total_profit_minus+=$row['total_profit_minus']*$exchange_value;
                $sum_total_profit+=$row['total_profit_plus']-$row['total_profit_minus'];
            }
            mysql_free_result($result);

        }





    }
    elseif($period=='quarter') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Store 1 Quarter Acc Profit`<0,`Store 1 Quarter Acc Profit`,0)) as total_profit_minus,sum(if(`Store 1 Quarter Acc Profit`>=0,`Store 1 Quarter Acc Profit`,0)) as total_profit_plus,sum(`Store For Public Sale Products`) as sum_active,sum(`Store 1 Quarter Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension` S $where $wheref   ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
        mysql_free_result($result);

    }
    elseif($period=='month') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Store 1 Month Acc Profit`<0,`Store 1 Month Acc Profit`,0)) as total_profit_minus,sum(if(`Store 1 Month Acc Profit`>=0,`Store 1 Month Acc Profit`,0)) as total_profit_plus,sum(`Store For Public Sale Products`) as sum_active,sum(`Store 1 Month Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension` S $where $wheref   ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
        mysql_free_result($result);

    }
    elseif($period=='week') {

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Store 1 Week Acc Profit`<0,`Store 1 Week Acc Profit`,0)) as total_profit_minus,sum(if(`Store 1 Week Acc Profit`>=0,`Store 1 Week Acc Profit`,0)) as total_profit_plus,sum(`Store For Public Sale Products`) as sum_active,sum(`Store 1 Week Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension` S   $where $wheref  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
        mysql_free_result($result);

    }
    elseif($period=='yeartoday') {
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Store YearToDay Acc Profit`<0,`Store YearToDay Acc Profit`,0)) as total_profit_minus,sum(if(`Store YearToDay Acc Profit`>=0,`Store YearToDay Acc Profit`,0)) as total_profit_plus,sum(`Store For Public Sale Products`) as sum_active,sum(`Store YearToDay Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension` S   $where $wheref  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
        mysql_free_result($result);
    }
    elseif($period=='three_year') {
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Store 3 Year Acc Profit`<0,`Store 3 Year Acc Profit`,0)) as total_profit_minus,sum(if(`Store 3 Year Acc Profit`>=0,`Store 3 Year Acc Profit`,0)) as total_profit_plus,sum(`Store For Public Sale Products`) as sum_active,sum(`Store 3 Year Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension` S   $where $wheref  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
        mysql_free_result($result);
    }
    elseif($period=='six_month') {
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Store 6 Month Acc Profit`<0,`Store 6 Month Acc Profit`,0)) as total_profit_minus,sum(if(`Store 6 Month Acc Profit`>=0,`Store 6 Month Acc Profit`,0)) as total_profit_plus,sum(`Store For Public Sale Products`) as sum_active,sum(`Store 6 Month Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension` S   $where $wheref  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
        mysql_free_result($result);
    }
    elseif($period=='ten_day') {
        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select sum(if(`Store 10 Day Acc Profit`<0,`Store 10 Day Acc Profit`,0)) as total_profit_minus,sum(if(`Store 10 Day Acc Profit`>=0,`Store 10 Day Acc Profit`,0)) as total_profit_plus,sum(`Store For Public Sale Products`) as sum_active,sum(`Store 10 Day Acc Invoiced Amount`) as sum_total_sales   from `Store Dimension` S   $where $wheref  ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $sum_total_sales=$row['sum_total_sales'];

            $sum_total_profit_plus=$row['total_profit_plus'];
            $sum_total_profit_minus=$row['total_profit_minus'];
            $sum_total_profit=$row['total_profit_plus']-$row['total_profit_minus'];
        }
        mysql_free_result($result);
    }



    $sql="select *  from `Store Dimension` S  left join `Store Default Currency` DC on DC.`Store Key`=S.`Store Key`   $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
    //print $sql;
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
    $sum_new=0;
    $DC_tag='';
    if ($exchange_type=='day2day' and $show_default_currency  )
        $DC_tag=' DC';

    // print "$sql";
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $name=sprintf('<a href="store.php?id=%d">%s</a>',$row['Store Key'],$row['Store Name']);
        $code=sprintf('<a href="store.php?id=%d">%s</a>',$row['Store Key'],$row['Store Code']);

        if ($percentages) {
            if ($period=='all') {
                $tsall=percentage($row['Store DC Total Invoiced Amount'],$sum_total_sales,2);
                if ($row['Store DC Total Profit']>=0)
                    $tprofit=percentage($row['Store DC Total Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Store DC Total Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='year') {
                $tsall=percentage($row['Store DC 1 Year Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Store DC 1 Year Acc Profit']>=0)
                    $tprofit=percentage($row['Store DC 1 Year Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Store DC 1 Year Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='quarter') {
                $tsall=percentage($row['Store DC 1 Quarter Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Store DC 1 Quarter Acc Profit']>=0)
                    $tprofit=percentage($row['Store DC 1 Quarter Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Store DC 1 Quarter Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='month') {
                $tsall=percentage($row['Store DC 1 Month Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Store DC 1 Month Acc Profit']>=0)
                    $tprofit=percentage($row['Store DC 1 Month Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Store DC 1 Month Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='week') {
                $tsall=percentage($row['Store DC 1 Week Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Store DC 1 Week Acc Profit']>=0)
                    $tprofit=percentage($row['Store DC 1 Week Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Store DC 1 Week Acc Profit'],$sum_total_profit_minus,2);
            }


            elseif($period=='yeartoday') {
                $tsall=percentage($row['Store DC YearToDay Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Store DC YearToDay Acc Profit']>=0)
                    $tprofit=percentage($row['Store DC YearToDay Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Store DC YearToDay Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='three_year') {
                $tsall=percentage($row['Store DC 3 Year Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Store DC 3 Year Acc Profit']>=0)
                    $tprofit=percentage($row['Store DC 3 Year Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Store DC 3 Year Acc Profit'],$sum_total_profit_minus,2);
            }
            elseif($period=='six_month') {
                $tsall=percentage($row['Store DC 6 Month Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Store DC 6 Month Acc Profit']>=0)
                    $tprofit=percentage($row['Store DC 6 Month Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Store DC 6 Month Acc Profit'],$sum_total_profit_minus,2);
            }

            elseif($period=='ten_day') {
                $tsall=percentage($row['Store DC 10 Day Acc Invoiced Amount'],$sum_total_sales,2);
                if ($row['Store DC 10 Day Acc Profit']>=0)
                    $tprofit=percentage($row['Store DC 10 Day Acc Profit'],$sum_total_profit_plus,2);
                else
                    $tprofit=percentage($row['Store DC 10 Day Acc Profit'],$sum_total_profit_minus,2);
            }




        } else {






            if ($period=="all") {


                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Store Total Days On Sale"]>0)
                        $factor=30.4368499/$row["Store Total Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Store Total Days On Sale"]>0)
                        $factor=7/$row["Store Total Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Store Total Days Available"]>0)
                        $factor=30.4368499/$row["Store Total Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Store Total Days Available"]>0)
                        $factor=7/$row["Store Total Days Available"];
                    else
                        $factor=0;
                }

                $tsall=($row["Store".$DC_tag." Total Invoiced Amount"]*$factor);
                $tprofit=($row["Store".$DC_tag." Total Profit"]*$factor);




            }
            elseif($period=="year") {


                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Store 1 Year Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store 1 Year Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Store 1 Year Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store 1 Year Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Store 1 Year Acc Days On Sale"]>0)
                        $factor=7/$row["Store 1 Year Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Store 1 Year Acc Days Available"]>0)
                        $factor=30.4368499/$row["Store 1 Year Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Store 1 Year Acc Days Available"]>0)
                        $factor=7/$row["Store 1 Year Acc Days Available"];
                    else
                        $factor=0;
                }









                $tsall=($row["Store".$DC_tag." 1 Year Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Store".$DC_tag." 1 Year Acc Profit"]*$factor);
            }
            elseif($period=="quarter") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Store 1 Quarter Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store 1 Quarter Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Store 1 Quarter Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store 1 Quarter Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Store 1 Quarter Acc Days On Sale"]>0)
                        $factor=7/$row["Store 1 Quarter Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Store 1 Quarter Acc Days Available"]>0)
                        $factor=30.4368499/$row["Store 1 Quarter Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Store 1 Quarter Acc Days Available"]>0)
                        $factor=7/$row["Store 1 Quarter Acc Days Available"];
                    else
                        $factor=0;
                }


                $tsall=($row["Store".$DC_tag." 1 Quarter Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Store".$DC_tag." 1 Quarter Acc Profit"]*$factor);
            }
            elseif($period=="month") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Store 1 Month Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store 1 Month Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Store 1 Month Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store 1 Month Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Store 1 Month Acc Days On Sale"]>0)
                        $factor=7/$row["Store 1 Month Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Store 1 Month Acc Days Available"]>0)
                        $factor=30.4368499/$row["Store 1 Month Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Store 1 Month Acc Days Available"]>0)
                        $factor=7/$row["Store 1 Month Acc Days Available"];
                    else
                        $factor=0;
                }


                $tsall=($row["Store".$DC_tag." 1 Month Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Store".$DC_tag." 1 Month Acc Profit"]*$factor);
            }
            elseif($period=="week") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Store 1 Week Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store 1 Week Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Store 1 Week Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store 1 Week Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Store 1 Week Acc Days On Sale"]>0)
                        $factor=7/$row["Store 1 Week Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Store 1 Week Acc Days Available"]>0)
                        $factor=30.4368499/$row["Store 1 Week Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Store 1 Week Acc Days Available"]>0)
                        $factor=7/$row["Store 1 Week Acc Days Available"];
                    else
                        $factor=0;
                }


                $tsall=($row["Store".$DC_tag." 1 Week Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Store".$DC_tag." 1 Week Acc Profit"]*$factor);
            }


            elseif($period=="yeartoday") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Store YearToDay Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store YearToDay Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Store YearToDay Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store YearToDay Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Store YearToDay Acc Days On Sale"]>0)
                        $factor=7/$row["Store YearToDay Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Store YearToDay Acc Days Available"]>0)
                        $factor=30.4368499/$row["Store YearToDay Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Store YearToDay Acc Days Available"]>0)
                        $factor=7/$row["Store YearToDay Acc Days Available"];
                    else
                        $factor=0;
                }

                $tsall=($row["Store".$DC_tag." YearToDay Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Store".$DC_tag." YearToDay Acc Profit"]*$factor);
            }
            elseif($period=="three_year") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Store 3 Year Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store 3 Year Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Store 3 Year Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store 3 Year Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Store 3 Year Acc Days On Sale"]>0)
                        $factor=7/$row["Store 3 Year Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Store 3 Year Acc Days Available"]>0)
                        $factor=30.4368499/$row["Store 3 Year Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Store 3 Year Acc Days Available"]>0)
                        $factor=7/$row["Store 3 Year Acc Days Available"];
                    else
                        $factor=0;
                }

                $tsall=($row["Store".$DC_tag." 3 Year Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Store".$DC_tag." 3 Year Acc Profit"]*$factor);
            }
            elseif($period=="six_month") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Store 6 Month Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store 6 Month Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Store 6 Month Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store 6 Month Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Store 6 Month Acc Days On Sale"]>0)
                        $factor=7/$row["Store 6 Month Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Store 6 Month Acc Days Available"]>0)
                        $factor=30.4368499/$row["Store 6 Month Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Store 6 Month Acc Days Available"]>0)
                        $factor=7/$row["Store 6 Month Acc Days Available"];
                    else
                        $factor=0;
                }

                $tsall=($row["Store".$DC_tag." 6 Month Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Store".$DC_tag." 6 Month Acc Profit"]*$factor);
            }
            elseif($period=="ten_day") {
                if ($avg=="totals")
                    $factor=1;
                elseif($avg=="month") {
                    if ($row["Store 10 Day Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store 10 Day Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month") {
                    if ($row["Store 10 Day Acc Days On Sale"]>0)
                        $factor=30.4368499/$row["Store 10 Day Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="week") {
                    if ($row["Store 10 Day Acc Days On Sale"]>0)
                        $factor=7/$row["Store 10 Day Acc Days On Sale"];
                    else
                        $factor=0;
                }
                elseif($avg=="month_eff") {
                    if ($row["Store 10 Day Acc Days Available"]>0)
                        $factor=30.4368499/$row["Store 10 Day Acc Days Available"];
                    else
                        $factor=0;
                }
                elseif($avg=="week_eff") {
                    if ($row["Store 10 Day Acc Days Available"]>0)
                        $factor=7/$row["Store 10 Day Acc Days Available"];
                    else
                        $factor=0;
                }

                $tsall=($row["Store".$DC_tag." 10 Day Acc Invoiced Amount"]*$factor);
                $tprofit=($row["Store".$DC_tag." 10 Day Acc Profit"]*$factor);
            }


        }

        $sum_sales+=$tsall;
        $sum_profit+=$tprofit;
        $sum_new+=$row['Store New Products'];

        $sum_low+=$row['Store Low Availability Products'];
        $sum_optimal+=$row['Store Optimal Availability Products'];
        $sum_low+=$row['Store Low Availability Products'];
        $sum_critical+=$row['Store Critical Availability Products'];
        $sum_surplus+=$row['Store Surplus Availability Products'];
        $sum_outofstock+=$row['Store Out Of Stock Products'];
        $sum_unknown+=$row['Store Unknown Stock Products'];
        $sum_departments+=$row['Store Departments'];
        $sum_families+=$row['Store Families'];
        $sum_todo+=$row['Store In Process Products'];
        $sum_discontinued+=$row['Store Discontinued Products'];


        if (!$percentages) {
            if ($show_default_currency) {
                $class='';
                if ($myconf['currency_code']!=$row['Store Currency Code'])
                    $class='currency_exchanged';


                $sales='<span class="'.$class.'">'.money($tsall).'</span>';
                $profit='<span class="'.$class.'">'.money($tprofit).'</span>';
                $margin='<span class="'.$class.'">'.percentage($tprofit,$tsall).'</span>';
            } else {
                $sales=money($tsall,$row['Store Currency Code']);
                $profit=money($tprofit,$row['Store Currency Code']);

                $margin=percentage($tprofit,$tsall);
            }
        } else {
            $sales=$tsall;
            $profit=$tprofit;
            $margin=percentage($profit,$sales);
        }

        $adata[]=array(
                     'code'=>$code,
                     'name'=>$name,
                     'departments'=>number($row['Store Departments']),
                     'families'=>number($row['Store Families']),
                     'active'=>number($row['Store For Public Sale Products']),
                     'new'=>number($row['Store New Products']),
                     'discontinued'=>number($row['Store Discontinued Products']),
                     'outofstock'=>number($row['Store Out Of Stock Products']),
                     'stock_error'=>number($row['Store Unknown Stock Products']),
                     'stock_value'=>money($row['Store Stock Value']),
                     'surplus'=>number($row['Store Surplus Availability Products']),
                     'optimal'=>number($row['Store Optimal Availability Products']),
                     'low'=>number($row['Store Low Availability Products']),
                     'critical'=>number($row['Store Critical Availability Products']),
                     'sales'=>$sales,
                     'profit'=>$profit,
                     'margin'=>$margin
                 );
    }
    mysql_free_result($res);


    if ($total<=$number_results) {

        if ($percentages) {
            $sum_sales='100.00%';
            $sum_profit='100.00%';
            $margin=percentage($sum_total_profit,$sum_total_sales);

        } else {
            $sum_sales=money($sum_total_sales);
            $sum_profit=money($sum_total_profit);
            $margin=percentage($sum_total_profit,$sum_total_sales);
        }
        $sum_new=number($sum_new);
        $sum_outofstock=number($sum_outofstock);
        $sum_low=number($sum_low);
        $sum_optimal=number($sum_optimal);
        $sum_critical=number($sum_critical);
        $sum_surplus=number($sum_surplus);
        $sum_unknown=number($sum_unknown);
        $sum_departments=number($sum_departments);
        $sum_families=number($sum_families);
        $sum_todo=number($sum_todo);
        $sum_discontinued=number($sum_discontinued);
        $adata[]=array(
                     'name'=>'',
                     'code'=>_('Total'),
                     'active'=>number($sum_active),
                     'sales'=>$sum_sales,
                     'profit'=>$sum_profit,
                     'margin'=>$margin,
                     'todo'=>$sum_todo,
                     'discontinued'=>$sum_discontinued,
                     'low'=>$sum_low,
                     'new'=>$sum_new,
                     'critical'=>$sum_critical,
                     'surplus'=>$sum_surplus,
                     'optimal'=>$sum_optimal,
                     'outofstock'=>$sum_outofstock,
                     'stock_error'=>$sum_unknown,
                     'departments'=>$sum_departments,
                     'families'=>$sum_families
                 );
        $total_records++;
        $number_results++;
    }

    // if($total<$number_results)
    //  $rtext=$total.' '.ngettext('store','stores',$total);
    //else
    //  $rtext='';

    //$total_records=ceil($total_records/$number_results)+$total_records;
//$total_records=$total_records;
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
                                      'records_returned'=>$start_from+$total,
                                      'records_perpage'=>$number_results,
                                      'records_text'=>$rtext,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
}

function list_charges() {


    $parent='store';

    if ( isset($_REQUEST['parent']))
        $parent= $_REQUEST['parent'];

    if ($parent=='store')
        $parent_id=$_SESSION['state']['store']['id'];
    else
        return;

    $conf=$_SESSION['state'][$parent]['charges'];




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





    $_SESSION['state'][$parent]['charges']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
    // print_r($_SESSION['tables']['families_list']);

    //  print_r($_SESSION['tables']['families_list']);
    if ($parent=='store')
        $where=sprintf("where  `Store Key`=%d ",$parent_id);
    else
        $where=sprintf("where true ");

    $filter_msg='';
    $wheref='';
    if ($f_field=='description' and $f_value!='')
        $wheref.=" and  CONCAT(`Charge Description`,' ',`Charge Terms Description`) like '".addslashes($f_value)."%'";
    elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Charge Name` like '".addslashes($f_value)."%'";








    $sql="select count(*) as total from `Charge Dimension`   $where $wheref";
    // print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total `Charge Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('charge','charges',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any charge with this name ")." <b>".$f_value."*</b> ";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any charge with description like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('charges with name like')." <b>".$f_value."*</b>";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('charges with description like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;

    if ($order=='name')
        $order='`Charge Name`';
    elseif($order=='description')
    $order='`Charge Description`,`Charge Terms Description`';
    else
        $order='`Charge Name`';


    $sql="select *  from `Charge Dimension` $where    order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);

    $total=mysql_num_rows($res);

    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        $adata[]=array(
                     'name'=>$row['Charge Name'],
                     'description'=>$row['Charge Description'].' '.$row['Charge Terms Description'],


                 );
    }
    mysql_free_result($res);



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

function list_campaigns() {


    $parent='store';

    if ( isset($_REQUEST['parent']))
        $parent= $_REQUEST['parent'];

    if ($parent=='store')
        $parent_id=$_SESSION['state']['store']['id'];
    else
        return;

    $conf=$_SESSION['state'][$parent]['campaigns'];


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


    $_SESSION['state'][$parent]['campaigns']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);

    if ($parent=='store')
        $where=sprintf("where  `Store Key`=%d    ",$parent_id);
    else
        $where=sprintf("where true ");;

    $filter_msg='';
    $wheref='';
    if ($f_field=='description' and $f_value!='')
        $wheref.=" and  `Campaign Description` like '".addslashes($f_value)."%'";
    elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Campaign Name` like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Campaign Dimension`   $where $wheref";
    //  print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total `Campaign Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('campaign','campaigns',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any campaign with this name ")." <b>".$f_value."*</b> ";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any campaign with description like ")." <b>".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('campaigns with name like')." <b>".$f_value."*</b>";
            break;
        case('description'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('campaigns with description like')." <b>".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;

    if ($order=='name')
        $order='`Campaign Name`';
    elseif($order=='description')
    $order='`Campaign Description`';
    else
        $order='`Campaign Name`';


    $sql="select *  from `Campaign Dimension` $where    order by $order $order_direction limit $start_from,$number_results    ";

    $res = mysql_query($sql);

    $total=mysql_num_rows($res);

    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $sql=sprintf("select * from `Campaign Deal Schema`  where `Campaign Key`=%d  ",$row['Campaign Key']);
        $res2 = mysql_query($sql);
        $deals='<ul style="padding:10px 20px">';
        while ($row2=mysql_fetch_array($res2, MYSQL_ASSOC)) {
            $deals.=sprintf("<li style='list-style-type: circle' >%s</li>",$row2['Deal Name']);
        }
        $deals.='</ul>';
        $adata[]=array(
                     'name'=>$row['Campaign Name'],
                     'description'=>$row['Campaign Description'].$deals


                 );
    }
    mysql_free_result($res);



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

function list_deals() {


    $parent='store';

    if ( isset($_REQUEST['parent']))
        $parent= $_REQUEST['parent'];

    if ($parent=='store' or $parent=='store_with_children')
        $parent_id=$_SESSION['state']['store']['id'];
    elseif($parent=='department')
    $parent_id=$_SESSION['state']['department']['id'];
    elseif($parent=='family')
    $parent_id=$_SESSION['state']['family']['id'];
    elseif($parent=='product')
    $parent_id=$_SESSION['state']['product']['pid'];
    else
        return;
    if ($parent=='store_with_children') {
        $conf=$_SESSION['state']['deals']['table'];

    } else {
        $conf=$_SESSION['state'][$parent]['deals'];
    }

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

    if ($parent=='store_with_children') {
        $_SESSION['state']['deals']['table']['order']=$order;
        $_SESSION['state']['deals']['table']['order_dir']=$order_direction;
        $_SESSION['state']['deals']['table']['nr']=$number_results;
        $_SESSION['state']['deals']['table']['sf']=$start_from;
        $_SESSION['state']['deals']['table']['where']=$where;
        $_SESSION['state']['deals']['table']['f_field']=$f_field;
        $_SESSION['state']['deals']['table']['f_value']=$f_value;


    }
    // $conf=$_SESSION['state']['deals']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
    else
        $_SESSION['state'][$parent]['deals']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);






    if ($parent=='store')
        $where=sprintf("where  (`Store Key`=%d and `Deal Trigger`='Order')     ",$parent_id);
    if ($parent=='store_with_children')
        $where=sprintf("where  `Store Key`=%d     ",$parent_id);
    elseif($parent=='department')
    $where=sprintf("where    `Deal Trigger`='Department' and  `Deal Trigger Key`=%d     ",$parent_id);
    elseif($parent=='family')
    $where=sprintf("where    `Deal Trigger`='Family' and  `Deal Trigger Key`=%d   ",$parent_id);
    elseif($parent=='product')
    $where=sprintf("where    `Deal Trigger`='Product' and  `Deal Trigger Key`=%d   ",$parent_id);
    else
        $where=sprintf("where true ");;
    // print "$parent $where";
    $filter_msg='';
    $wheref='';
    if ($f_field=='description' and $f_value!='')
        $wheref.=" and ( `Deal Terms Description` like '".addslashes($f_value)."%' or `Deal Allowance Description` like '".addslashes($f_value)."%'  )   ";
    elseif($f_field=='name' and $f_value!='')
    $wheref.=" and  `Deal Name` like '".addslashes($f_value)."%'";

    $sql="select count(*) as total from `Deal Dimension`   $where $wheref";
    //  print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total `Deal Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('deal','deals',$total_records);
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

    if ($order=='name')
        $order='`Deal Name`';
    elseif($order=='description')
    $order='`Deal Terms Description`,`Deal Allowance Description`';
    else
        $order='`Deal Name`';


    $sql="select *  from `Deal Dimension` $where    order by $order $order_direction limit $start_from,$number_results    ";
//print $sql;
    $res = mysql_query($sql);

    $total=mysql_num_rows($res);

    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {



        $adata[]=array(
                     'name'=>$row['Deal Name'],
                     'description'=>$row['Deal Terms Description'].' &rArr; '.$row['Deal Allowance Description'],
                     'trigger'=>$row['Deal Trigger'],
                     'target'=>$row['Deal Allowance Target']


                 );
    }
    mysql_free_result($res);



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

function list_customers_per_store() {

    global $user;

    $conf=$_SESSION['state']['stores']['customers'];

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

    } else
        $percentages=$_SESSION['state']['stores']['customers']['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];

    } else
        $period=$_SESSION['state']['stores']['customers']['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];

    } else
        $avg=$_SESSION['state']['stores']['customers']['avg'];



    $_SESSION['state']['stores']['customers']['percentage']=$percentages;
    $_SESSION['state']['stores']['customers']['period']=$period;
    $_SESSION['state']['stores']['customers']['avg']=$avg;
    $_SESSION['state']['stores']['customers']['order']=$order;
    $_SESSION['state']['stores']['customers']['order_dir']=$order_dir;
    $_SESSION['state']['stores']['customers']['nr']=$number_results;
    $_SESSION['state']['stores']['customers']['sf']=$start_from;
    $_SESSION['state']['stores']['customers']['where']=$where;
    $_SESSION['state']['stores']['customers']['f_field']=$f_field;
    $_SESSION['state']['stores']['customers']['f_value']=$f_value;
    // print_r($_SESSION['tables']['families_list']);

    //  print_r($_SESSION['tables']['families_list']);

    if (count($user->stores)==0)
        $where="where false";
    else {

        $where=sprintf("where `Store Key` in (%s)",join(',',$user->stores));
    }
    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Store Name` like '%".addslashes($f_value)."%'";
    if ($f_field=='code'  and $f_value!='')
        $wheref.=" and  `Store Code` like '".addslashes($f_value)."%'";




    $sql="select count(*) as total from `Store Dimension`   $where $wheref";
//print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Store Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('store','stores',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;


    if ($order=='code')
        $order='`Store Code`';
    elseif($order=='name')
    $order='`Store Name`';
    elseif($order=='contacts')
    $order='`Store Contacts`';
    elseif($order=='active_contacts')
    $order='active';
    elseif($order=='new_contacts')
    $order='`Store New Contacts`';
    elseif($order=='lost_contacts')
    $order='`Store Lost Contacts`';
    elseif($order=='losing_contacts')
    $order='`Store Losing Contacts`';

    elseif($order=='contacts_with_orders')
    $order='`Store Contacts`';
    elseif($order=='active_contacts_with_orders')
    $order='active';
    elseif($order=='new_contacts_with_orders')
    $order='`Store New Contacts`';
    elseif($order=='lost_contacts_with_orders')
    $order='`Store Lost Contacts`';
    elseif($order=='losing_contacts_with_orders')
    $order='`Store Losing Contacts`';

    else
        $order='`Store Code`';




    $sql="select `Store Key`,`Store Name`,`Store Code`,`Store Contacts`,`Store Total Users`, (`Store Active Contacts`+`Store Losing Contacts`) as active,`Store New Contacts`,`Store Lost Contacts`,`Store Losing Contacts`,
         `Store Contacts With Orders`,(`Store Active Contacts With Orders`+`Store Losing Contacts With Orders`)as active_with_orders,`Store New Contacts With Orders`,`Store Lost Contacts With Orders`,`Store Losing Contacts With Orders` from  `Store Dimension`    $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";




    $res = mysql_query($sql);

    $total=mysql_num_rows($res);


    $total_contacts=0;
    $total_active_contacts=0;
    $total_new_contacts=0;
    $total_lost_contacts=0;
    $total_losing_contacts=0;
    $total_contacts_with_orders=0;
    $total_active_contacts_with_orders=0;
    $total_new_contacts_with_orders=0;
    $total_lost_contacts_with_orders=0;
    $total_losing_contacts_with_orders=0;



    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $name=sprintf('<a href="customers.php?store=%d">%s</a>',$row['Store Key'],$row['Store Name']);
        $code=sprintf('<a href="customers.php?store=%d">%s</a>',$row['Store Key'],$row['Store Code']);

        $total_contacts+=$row['Store Contacts'];

        $total_active_contacts+=$row['active'];
        $total_new_contacts+=$row['Store New Contacts'];
        $total_lost_contacts+=$row['Store Lost Contacts'];
        $total_losing_contacts+=$row['Store Losing Contacts'];
        $total_contacts_with_orders+=$row['Store Contacts With Orders'];
        $total_active_contacts_with_orders+=$row['active_with_orders'];
        $total_new_contacts_with_orders+=$row['Store New Contacts With Orders'];
        $total_lost_contacts_with_orders+=$row['Store Lost Contacts With Orders'];
        $total_losing_contacts_with_orders+=$row['Store Losing Contacts With Orders'];




        $contacts=number($row['Store Contacts']);
        $new_contacts=number($row['Store New Contacts']);
        $active_contacts=number($row['active']);
        $losing_contacts=number($row['Store Losing Contacts']);
        $lost_contacts=number($row['Store Lost Contacts']);
        $contacts_with_orders=number($row['Store Contacts With Orders']);
        $new_contacts_with_orders=number($row['Store New Contacts With Orders']);
        $active_contacts_with_orders=number($row['active_with_orders']);
        $losing_contacts_with_orders=number($row['Store Losing Contacts With Orders']);
        $lost_contacts_with_orders=number($row['Store Lost Contacts With Orders']);
        $total_users=$row['Store Total Users'];

        //  $contacts_with_orders=number($row['contacts_with_orders']);
        // $active_contacts=number($row['active_contacts']);
        // $new_contacts=number($row['new_contacts']);
        // $lost_contacts=number($row['lost_contacts']);
        // $new_contacts_with_orders=number($row['new_contacts']);


        /*
                if ($percentages) {
                    $contacts_with_orders=percentage($row['contacts_with_orders'],$total_contacts_with_orders);
                    $active_contacts=percentage($row['active_contacts'],$total_active);
                    $new_contacts=percentage($row['new_contacts'],$total_new);
                    $lost_contacts=percentage($row['los_contactst'],$total_lost);
                    $contacts=percentage($row['contacts'],$total_contacts);
                    $new_contacts_with_orders=percentage($row['new_contacts'],$total_new_contacts);

                } else {
                    $contacts_with_orders=number($row['contacts_with_orders']);
                    $active_contacts=number($row['active_contacts']);
                    $new_contacts=number($row['new_contacts']);
                    $lost_contacts=number($row['lost_contacts']);
                    $contacts=number($row['contacts']);
                    $new_contacts_with_orders=number($row['new_contacts']);

                }
        */
        $adata[]=array(
                     'code'=>$code,
                     'name'=>$name,
                     'contacts'=>$contacts,
                     'active_contacts'=>$active_contacts,
                     'new_contacts'=>$new_contacts,
                     'lost_contacts'=>$lost_contacts,
                     'losing_contacts'=>$losing_contacts,
                     'contacts_with_orders'=>$contacts_with_orders,
                     'active_contacts_with_orders'=>$active_contacts_with_orders,
                     'new_contacts_with_orders'=>$new_contacts_with_orders,
                     'lost_contacts_with_orders'=>$lost_contacts_with_orders,
                     'losing_contacts_with_orders'=>$losing_contacts_with_orders,
                     'users'=>$total_users


                 );

    }
    mysql_free_result($res);




    if ($percentages) {
        $sum_total='100.00%';
        $sum_active='100.00%';
        $sum_new='100.00%';
        $sum_lost='100.00%';
        $sum_contacts='100.00%';
        $sum_new_contacts='100.00%';
    } else {
        $total_contacts=number($total_contacts);
        $total_active_contacts=number($total_active_contacts);
        $total_new_contacts=number($total_new_contacts);
        $total_lost_contacts=number($total_lost_contacts);
        $total_losing_contacts=number($total_losing_contacts);
        $total_contacts_with_orders=number($total_contacts_with_orders);
        $total_active_contacts_with_orders=number($total_active_contacts_with_orders);
        $total_new_contacts_with_orders=number($total_new_contacts_with_orders);
        $total_lost_contacts_with_orders=number($total_lost_contacts_with_orders);
        $total_losing_contacts_with_orders=number($total_losing_contacts_with_orders);

        // $sum_total=number($total_contacts_with_orders);
        // $sum_active=number($total_active_contacts);
        // $sum_new=number($total_new_contacts);
        // $sum_lost=number($total_lost_contacts);
        // $sum_contacts=number($total_contacts);
        // $sum_new_contacts=number($total_new_contacts);
    }


    $adata[]=array(
                 'name'=>'',
                 'code'=>_('Total'),
                 'contacts'=>$total_contacts,
                 'active_contacts'=>$total_active_contacts,
                 'new_contacts'=>$total_new_contacts,
                 'lost_contacts'=>$total_lost_contacts,
                 'losing_contacts'=>$total_losing_contacts,
                 'contacts_with_orders'=>$total_contacts_with_orders,
                 'active_contacts_with_orders'=>$total_active_contacts_with_orders,
                 'new_contacts_with_orders'=>$total_new_contacts_with_orders,
                 'lost_contacts_with_orders'=>$total_lost_contacts_with_orders,
                 'losing_contacts_with_orders'=>$total_losing_contacts_with_orders,
                 'users'=>$total_users

                         //               'customers'=>$sum_total,
                         //             'active'=>$sum_active,
                         //           'new'=>$sum_new,
                         //         'lost'=>$sum_lost,
                         //
                         //     'new_contacts'=>$sum_new_contacts
             );


    // if($total<$number_results)
    //  $rtext=$total.' '.ngettext('store','stores',$total);
    //else
    //  $rtext='';

    $total_records=ceil($total_records/$number_results)+$total_records;

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

function list_marketing_per_store() {

    global $user;

    $conf=$_SESSION['state']['stores']['marketing'];

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

    } else
        $percentages=$_SESSION['state']['stores']['marketing']['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];

    } else
        $period=$_SESSION['state']['stores']['marketing']['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];

    } else
        $avg=$_SESSION['state']['stores']['marketing']['avg'];



    $_SESSION['state']['stores']['marketing']['percentage']=$percentages;
    $_SESSION['state']['stores']['marketing']['period']=$period;
    $_SESSION['state']['stores']['marketing']['avg']=$avg;
    $_SESSION['state']['stores']['marketing']['order']=$order;
    $_SESSION['state']['stores']['marketing']['order_dir']=$order_dir;
    $_SESSION['state']['stores']['marketing']['nr']=$number_results;
    $_SESSION['state']['stores']['marketing']['sf']=$start_from;
    $_SESSION['state']['stores']['marketing']['where']=$where;
    $_SESSION['state']['stores']['marketing']['f_field']=$f_field;
    $_SESSION['state']['stores']['marketing']['f_value']=$f_value;
    // print_r($_SESSION['tables']['families_list']);

    //  print_r($_SESSION['tables']['families_list']);

    if (count($user->stores)==0)
        $where="where false";
    else {
        $where=sprintf("where `Store Key` in (%s)",join(',',$user->stores));
    }


    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Store Name` like '%".addslashes($f_value)."%'";
    if ($f_field=='code'  and $f_value!='')
        $wheref.=" and  `Store Code` like '".addslashes($f_value)."%'";




    $sql="select count(*) as total from `Store Dimension`   $where $wheref";
//print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Store Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('store','stores',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;


    if ($order=='code')
        $order='`Store Code`';
    elseif($order=='name')
    $order='`Store Name`';
    elseif($order=='contacts')
    $order='contacts';
    elseif($order=='active')
    $order='active';
    elseif($order=='new')
    $order='new';
    elseif($order=='new_contacts')
    $order='new_contacts';
    elseif($order=='customers')
    $order='customers';
    elseif($order=='lost')
    $order='lost';
    else
        $order='`Store Code`';

    $total_customers=0;





    $sql="select `Store Newsletters`,`Store Email Campaigns`,`Store Name`,`Store Code`,`Store Key` from  `Store Dimension`    $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
    $res = mysql_query($sql);

    $total=mysql_num_rows($res);



    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $name=sprintf('<a href="marketing.php?store=%d">%s</a>',$row['Store Key'],$row['Store Name']);
        $code=sprintf('<a href="marketing.php?store=%d">%s</a>',$row['Store Key'],$row['Store Code']);

        $adata[]=array(
                     'code'=>$code,
                     'name'=>$name,
                     'ecampaigns'=>number($row['Store Email Campaigns']) ,
                     'newsletters'=>number($row['Store Newsletters'])


                 );
    }
    mysql_free_result($res);
    /*
        if ($percentages) {
            $sum_total='100.00%';
            $sum_active='100.00%';
            $sum_new='100.00%';
            $sum_lost='100.00%';
            $sum_contacts='100.00%';
            $sum_new_contacts='100.00%';
        } else {
            $sum_total=number($total_customers);
            $sum_active=number($total_active);
            $sum_new=number($total_new);
            $sum_lost=number($total_lost);
            $sum_contacts=number($total_contacts);
            $sum_new_contacts=number($total_new_contacts);
        }

    */
    $adata[]=array(
                 'name'=>'',
                 'code'=>_('Total'),


             );


    // if($total<$number_results)
    //  $rtext=$total.' '.ngettext('store','stores',$total);
    //else
    //  $rtext='';

    $total_records=ceil($total_records/$number_results)+$total_records;

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

function list_orders_per_store() {
    global $user;
    $conf=$_SESSION['state']['stores']['orders'];

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

    } else {
        $number_results=$conf['nr'];
    }




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
    else {
        $where=$conf['where'];
    }


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

    } else {
        $percentages=$_SESSION['state']['stores']['orders']['percentages'];
    }


    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];

    } else {
        $period=$_SESSION['state']['stores']['orders']['period'];
    }
    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];

    } else {
        $avg=$_SESSION['state']['stores']['orders']['avg'];
    }

    $_SESSION['state']['stores']['orders']['percentages']=$percentages;
    $_SESSION['state']['stores']['orders']['period']=$period;
    $_SESSION['state']['stores']['orders']['avg']=$avg;
    $_SESSION['state']['stores']['orders']['order']=$order;
    $_SESSION['state']['stores']['orders']['order_dir']=$order_direction;
    $_SESSION['state']['stores']['orders']['nr']=$number_results;
    $_SESSION['state']['stores']['orders']['sf']=$start_from;
    $_SESSION['state']['stores']['orders']['where']=$where;
    $_SESSION['state']['stores']['orders']['f_field']=$f_field;
    $_SESSION['state']['stores']['orders']['f_value']=$f_value;

    // print_r($_SESSION['tables']['families_list']);

    //  print_r($_SESSION['tables']['families_list']);
    if (count($user->stores)==0)
        $where="where false";
    else {
        $where=sprintf("where `Store Key` in (%s)",join(',',$user->stores));
    }
    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Store Name` like '%".addslashes($f_value)."%'";
    if ($f_field=='code'  and $f_value!='')
        $wheref.=" and  `Store Code` like '".addslashes($f_value)."%'";




    $sql="select count(*) as total from `Store Dimension`   $where $wheref";
//print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Store Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('store','stores',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;


    if ($order=='code')
        $order='`Store Code`';
    elseif($order=='name')
    $order='`Store Name`';
    elseif($order=='orders')
    $order='orders';
    elseif($order=='cancelled')
    $order='cancelled';
    elseif($order=='unknown')
    $order='unknown';
    elseif($order=='paid')
    $order='paid';
    elseif($order=='pending')
    $order='todo';
    else


        $order='`Store Code`';


    $total_orders=0;
    $total_unknown=0;
    $total_dispatched=0;
    $total_cancelled=0;
    $total_todo=0;
    $total_paid=0;
    $total_suspended=0;
    $sql="select  sum(`Store Total Orders`) as orders,sum(`Store Unknown Orders`) as unknown,sum(`Store Suspended Orders`) as suspended,sum(`Store Dispatched Orders`) as dispatched,sum(`Store Cancelled Orders`) cancelled,sum(`Store Orders In Process`) as todo   from `Store Dimension`  $where     ";
    // print $sql;
    $res = mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total_orders=$row['orders'];
        $total_unknown=$row['unknown'];
        $total_dispatched=$row['dispatched'];
        $total_cancelled=$row['cancelled'];
        $total_todo=$row['todo'];
        $total_suspended=$row['suspended'];


    }





    $sql="select `Store Name`,`Store Code`,`Store Key`,`Store Total Orders` as orders,`Store Suspended Orders` as suspended, `Store Total Orders` as orders,`Store Unknown Orders` as unknown,`Store Dispatched Orders` as dispatched,`Store Cancelled Orders` cancelled,`Store Orders In Process` as todo from   `Store Dimension` $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
    //print $sql;
    $res = mysql_query($sql);

    $total=mysql_num_rows($res);



    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $name=sprintf('<a href="orders.php?store=%d&view=orders">%s</a>',$row['Store Key'],$row['Store Name']);
        $code=sprintf('<a href="orders.php?store=%d&view=orders">%s</a>',$row['Store Key'],$row['Store Code']);

        $todo=$row['todo'];
        if ($percentages) {
            $orders=percentage($row['orders'],$total_orders);
            $cancelled=percentage($row['cancelled'],$total_cancelled);
            $unknown=percentage($row['unknown'],$total_unknown);
            $todo=percentage($todo,$total_todo);
            $dispatched=percentage($row['dispatched'],$total_dispatched);
            $suspended=percentage($row['suspended'],$total_suspended);

        } else {
            $orders=number($row['orders']);
            $cancelled=number($row['cancelled']);
            $unknown=number($row['unknown']);
            $todo=number($todo);
            $dispatched=number($row['dispatched']);
            $suspended=number($row['suspended']);
        }
        if ($row['unknown']>0)
            $unknown=sprintf('(<a href="orders.php?store=%d&view=orders&dispatch=unknown">%s</a>) ',$row['Store Key'],$unknown);
        else
            $unknown='';
        $orders=sprintf('<a href="orders.php?store=%d&view=orders&dispatch=all_orders">%s</a>',$row['Store Key'],$orders);
        $cancelled=sprintf('<a href="orders.php?store=%d&view=orders&dispatch=cancelled">%s</a>',$row['Store Key'],$cancelled);
        $dispatched=sprintf('<a href="orders.php?store=%d&view=orders&dispatch=dispatched">%s</a>',$row['Store Key'],$dispatched);
        $todo=$unknown.sprintf('<a href="orders.php?store=%d&view=orders&dispatch=in_process">%s</a>',$row['Store Key'],$todo);
        $suspended=sprintf('<a href="orders.php?store=%d&view=orders&dispatch=suspended">%s</a>',$row['Store Key'],$suspended);



        $adata[]=array(
                     'code'=>$code,
                     'name'=>$name,
                     'orders'=>$orders,
                     'unknown'=>$unknown,
                     'cancelled'=>$cancelled,
                     'dispatched'=>$dispatched,
                     'pending'=>$todo,
                     'suspended'=>$suspended

                 );
    }
    mysql_free_result($res);

    if ($percentages) {
        $sum_orders='100.00%';
        $sum_cancelled='100.00%';
        $sum_paid='100.00%';
        $sum_unknown='';
        $sum_suspended='100.00%';
    } else {
        $sum_orders=number($total_orders);
        $sum_cancelled=number($total_cancelled);
        $sum_paid=number($total_paid);
        if ($total_unknown>0)
            $sum_unknown="(".number($total_unknown).") ";
        else
            $sum_unknown='';
        $sum_todo=number($total_todo);
        $sum_dispatched=number($total_dispatched);
        $sum_suspended=number($total_suspended);
    }


    $adata[]=array(
                 'name'=>'',
                 'code'=>_('Total'),
                 'orders'=>$sum_orders,
                 'unknown'=>$sum_unknown,
                 'paid'=>$sum_paid,
                 'cancelled'=>$sum_cancelled,
                 'dispatched'=>$sum_dispatched,
                 'pending'=>$sum_unknown.$sum_todo,
                 'suspended'=>$sum_suspended
             );


    $total_records=ceil($total_records/$number_results)+$total_records;

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

function list_invoices_per_store() {

    $conf=$_SESSION['state']['stores']['invoices'];

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

    } else
        $percentages=$_SESSION['state']['stores']['invoices']['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];

    } else
        $period=$_SESSION['state']['stores']['invoices']['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];

    } else
        $avg=$_SESSION['state']['stores']['invoices']['avg'];


    $_SESSION['state']['stores']['invoices']['percentages']=$percentages;
    $_SESSION['state']['stores']['invoices']['period']=$period;
    $_SESSION['state']['stores']['invoices']['avg']=$avg;
    $_SESSION['state']['stores']['invoices']['order']=$order;
    $_SESSION['state']['stores']['invoices']['order_dir']=$order_direction;
    $_SESSION['state']['stores']['invoices']['nr']=$number_results;
    $_SESSION['state']['stores']['invoices']['sf']=$start_from;
    $_SESSION['state']['stores']['invoices']['where']=$where;
    $_SESSION['state']['stores']['invoices']['f_field']=$f_field;
    $_SESSION['state']['stores']['invoices']['f_value']=$f_value;


    $where="where true  ";

    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Store Name` like '%".addslashes($f_value)."%'";
    if ($f_field=='code'  and $f_value!='')
        $wheref.=" and  `Store Code` like '".addslashes($f_value)."%'";




    $sql="select count(*) as total from `Store Dimension`   $where $wheref";
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Store Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('store','stores',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;


    if ($order=='code')
        $order='`Store Code`';
    elseif($order=='name')
    $order='`Store Name`';
    elseif($order=='invoices')
    $order='invoices';
    elseif($order=='invoicess_paid')
    $order='invoices_paid';
    elseif($order=='invoices_to_be_paid')
    $order='invoices_to_be_paid';
    elseif($order=='refunds')
    $order='refunds';
    elseif($order=='refundss_paid')
    $order='refunds_paid';
    elseif($order=='refunds_to_be_paid')
    $order='refunds_to_be_paid';
    else
        $order='`Store Code`';


    $total_invoices=0;
    $total_invoices_paid=0;
    $total_invoices_to_be_paid=0;
    $total_refunds=0;
    $total_refunds_paid=0;
    $total_refunds_to_be_paid=0;

    $sql="select  `Store Invoices` as invoices,`Store Refunds` as refunds,`Store Total Invoices` as total_invoices,`Store Paid Invoices` as invoices_paid,`Store Invoices`-`Store Paid Invoices` as invoices_to_be_paid,`Store Paid Refunds` as refunds_paid,`Store Refunds`-`Store Paid Refunds` as refunds_to_be_paid from `Store Dimension`  $where     ";
    //print $sql;
    $res = mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total_invoices=$row['invoices'];
        $total_invoices_paid=$row['invoices_paid'];
        $total_invoices_to_be_paid=$row['invoices_to_be_paid'];
        $total_refunds=$row['refunds'];
        $total_refunds_paid=$row['refunds_paid'];
        $total_refunds_to_be_paid=$row['refunds_to_be_paid'];


    }





    $sql="select `Store Name`,`Store Code`,`Store Key`,`Store Invoices` as invoices,`Store Refunds` as refunds,`Store Total Invoices` as total_invoices,`Store Paid Invoices` as invoices_paid,`Store Invoices`-`Store Paid Invoices` as invoices_to_be_paid,`Store Paid Refunds` as refunds_paid,`Store Refunds`-`Store Paid Refunds` as refunds_to_be_paid  from   `Store Dimension` $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
    //print $sql;
    $res = mysql_query($sql);

    $total=mysql_num_rows($res);



    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $name=sprintf('<a href="orders.php?store=%d&view=invoices">%s</a>',$row['Store Key'],$row['Store Name']);
        $code=sprintf('<a href="orders.php?store=%d&view=invoices">%s</a>',$row['Store Key'],$row['Store Code']);


        if ($percentages) {
            $invoices=percentage($row['invoices'],$total_invoices);
            $invoices_paid=percentage($row['invoices_paid'],$total_invoices_paid);
            $invoices_to_be_paid=percentage($row['invoices_to_be_paid'],$total_invoices_to_be_paid);
            $refunds=percentage($row['refunds'],$total_refunds);
            $refunds_paid=percentage($row['refunds_paid'],$total_refunds_paid);
            $refunds_to_be_paid=percentage($row['refunds_to_be_paid'],$total_refunds_to_be_paid);


        } else {
            $invoices=number($row['invoices']);
            $invoices_paid=number($row['invoices_paid']);
            $invoices_to_be_paid=number($row['invoices_to_be_paid']);
            $refunds=number($row['refunds']);
            $refunds_paid=number($row['refunds_paid']);
            $refunds_to_be_paid=number($row['refunds_to_be_paid']);

        }

        $invoices=sprintf('<a href="orders.php?store=%d&view=invoices&invoice_type=invoices">%s</a>',$row['Store Key'],$invoices);
        $invoices_paid=sprintf('<a href="orders.php?store=%d&view=invoices&invoice_type=paid">%s</a>',$row['Store Key'],$invoices_paid);
        $invoices_to_be_paid=sprintf('<a href="orders.php?store=%d&view=invoices&invoice_type=to_paid">%s</a>',$row['Store Key'],$invoices_to_be_paid);
        $refunds=sprintf('<a href="orders.php?store=%d&view=invoices&invoice_type=refunds">%s</a>',$row['Store Key'],$refunds);
        $refunds_paid=sprintf('<a href="orders.php?store=%d&view=invoices&invoice_type=refunds">%s</a>',$row['Store Key'],$refunds_paid);
        $refunds_to_be_paid=sprintf('<a href="orders.php?store=%d&view=invoices&invoice_type=refunds">%s</a>',$row['Store Key'],$refunds_to_be_paid);

        $adata[]=array(
                     'code'=>$code,
                     'name'=>$name,
                     'invoices'=>$invoices,
                     'invoices_paid'=>$invoices_paid,
                     'invoices_to_be_paid'=>$invoices_to_be_paid,
                     'refunds'=>$refunds,
                     'refunds_paid'=>$refunds_paid,
                     'refunds_to_be_paid'=>$refunds_to_be_paid,
                 );
    }
    mysql_free_result($res);

    if ($percentages) {
        $total_invoices='100.00%';
        $total_invoices_paid='100.00%';
        $total_invoices_to_be_paid='100.00%';
        $total_refunds='100.00%';
        $total_refunds_paid='100.00%';
        $total_refunds_to_be_paid='100.00%';


    } else {
        $total_invoices=number($total_invoices);
        $total_invoices_paid=number($total_invoices_paid);
        $total_invoices_to_be_paid=number($total_invoices_to_be_paid);
        $total_refunds=number($total_refunds);
        $total_refunds_paid=number($total_refunds_paid);
        $total_refunds_to_be_paid=number($total_refunds_to_be_paid);

    }


    $adata[]=array(
                 'name'=>'',
                 'code'=>_('Total'),
                 'invoices'=>$total_invoices,
                 'invoices_paid'=>$total_invoices_paid,
                 'invoices_to_be_paid'=>$total_invoices_to_be_paid,
                 'refunds'=>$total_refunds,
                 'refunds_paid'=>$total_refunds_paid,
                 'refunds_to_be_paid'=>$total_refunds_to_be_paid,

             );


    $total_records=ceil($total_records/$number_results)+$total_records;

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

function list_delivery_notes_per_store() {
    global $user;
    $conf=$_SESSION['state']['stores']['delivery_notes'];

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



    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['f_value'];

    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$conf['view'];

    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    if (isset( $_REQUEST['percentages'])) {
        $percentages=$_REQUEST['percentages'];

    } else
        $percentages=$_SESSION['state']['stores']['delivery_notes']['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];

    } else
        $period=$_SESSION['state']['stores']['delivery_notes']['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];

    } else
        $avg=$_SESSION['state']['stores']['delivery_notes']['avg'];



    $_SESSION['state']['stores']['delivery_notes']['percentages']=$percentages;
    $_SESSION['state']['stores']['delivery_notes']['period']=$period;
    $_SESSION['state']['stores']['delivery_notes']['avg']=$avg;
    $_SESSION['state']['stores']['delivery_notes']['order']=$order;
    $_SESSION['state']['stores']['delivery_notes']['order_dir']=$order_direction;
    $_SESSION['state']['stores']['delivery_notes']['nr']=$number_results;
    $_SESSION['state']['stores']['delivery_notes']['sf']=$start_from;
    $_SESSION['state']['stores']['delivery_notes']['where']=$where;
    $_SESSION['state']['stores']['delivery_notes']['view']=$view;

    $_SESSION['state']['stores']['delivery_notes']['f_field']=$f_field;
    $_SESSION['state']['stores']['delivery_notes']['f_value']=$f_value;

    // print_r($_SESSION['tables']['families_list']);

    //  print_r($_SESSION['tables']['families_list']);
    //  $where="where true  ";

    if (count($user->stores)==0)
        $where="where false";
    else {
        $where=sprintf("where `Store Key` in (%s)",join(',',$user->stores));
    }


    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Store Name` like '%".addslashes($f_value)."%'";
    if ($f_field=='code'  and $f_value!='')
        $wheref.=" and  `Store Code` like '".addslashes($f_value)."%'";




    $sql="select count(*) as total from `Store Dimension`   $where $wheref";
//print $sql;
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    mysql_free_result($result);

    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Store Dimension`   $where ";

        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($result);

    }


    $rtext=$total_records." ".ngettext('store','stores',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';

    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with code like ")." <b>".$f_value."*</b> ";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any store with name like ")." <b>*".$f_value."*</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('code'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with code like')." <b>".$f_value."*</b>";
            break;
        case('name'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('stores with name like')." <b>*".$f_value."*</b>";
            break;
        }
    }
    else
        $filter_msg='';

    $_dir=$order_direction;
    $_order=$order;


    if ($order=='code')
        $order='`Store Code`';
    elseif($order=='name')
    $order='`Store Name`';


    $total_dn=0;
    $total_dn_ready_to_pick=0;
    $total_dn_packing=0;
    $total_dn_picking=0;
    $total_dn_ready=0;
    $total_dn_send=0;
    $total_dn_returned=0;
    $total_dn_orders=0;
    $total_dn_shortages=0;
    $total_dn_replacements=0;
    $total_dn_donations=0;
    $total_dn_samples=0;


    $sql="select `Store Delivery Notes For Shortages` as dn_shortages,`Store Delivery Notes For Replacements` as dn_replacements, `Store Delivery Notes For Donations` as dn_donations, `Store Delivery Notes For Samples` as dn_samples, `Store Delivery Notes For Orders` as dn_orders, `Store Total Delivery Notes` as dn,`Store Ready to Pick Delivery Notes` as dn_ready_to_pick,`Store Picking Delivery Notes` as dn_picking,`Store Packing Delivery Notes` as dn_packing,`Store Ready to Dispatch Delivery Notes` as dn_ready,`Store Dispatched Delivery Notes` as dn_send, `Store Returned Delivery Notes`as dn_returned from `Store Dimension`  $where     ";
    $res = mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $total_dn=$row['dn'];
        $total_dn_ready_to_pick=$row['dn_ready_to_pick'];
        $total_dn_picking=$row['dn_picking'];
        $total_dn_packing=$row['dn_packing'];
        $total_dn_ready=$row['dn_ready'];
        $total_dn_send=$row['dn_send'];
        $total_dn_returned=$row['dn_returned'];
        $total_dn_orders=$row['dn_orders'];
        $total_dn_shortages=$row['dn_shortages'];
        $total_dn_replacements=$row['dn_replacements'];
        $total_dn_donations=$row['dn_donations'];
        $total_dn_samples=$row['dn_samples'];

    }





    $sql="select `Store Name`,`Store Code`,`Store Key`,`Store Delivery Notes For Shortages` as dn_shortages,`Store Delivery Notes For Replacements` as dn_replacements, `Store Delivery Notes For Donations` as dn_donations, `Store Delivery Notes For Samples` as dn_samples, `Store Delivery Notes For Orders` as dn_orders, `Store Total Delivery Notes` as dn,`Store Ready to Pick Delivery Notes` as dn_ready_to_pick,`Store Picking Delivery Notes` as dn_picking,`Store Packing Delivery Notes` as dn_packing,`Store Ready to Dispatch Delivery Notes` as dn_ready,`Store Dispatched Delivery Notes` as dn_send,`Store Returned Delivery Notes`as dn_returned from   `Store Dimension` $where $wheref   order by $order $order_direction limit $start_from,$number_results    ";
    //print $sql;
    $res = mysql_query($sql);

    $total=mysql_num_rows($res);



    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $name=sprintf('<a href="orders.php?store=%d&view=dn">%s</a>',$row['Store Key'],$row['Store Name']);
        $code=sprintf('<a href="orders.php?store=%d&view=dn">%s</a>',$row['Store Key'],$row['Store Code']);


        if ($percentages) {
            $dn=percentage($row['dn'],$total_dn);
            $dn_ready_to_pick=percentage($row['dn_ready_to_pick'],$total_dn_ready_to_pick);
            $dn_picking=percentage($row['dn_picking'],$total_dn_picking);
            $dn_packing=percentage($row['dn_packing'],$total_dn_packing);
            $dn_ready=percentage($row['dn_ready'],$total_dn_ready);
            $dn_send=percentage($row['dn_send'],$total_dn_send);
            $dn_returned=percentage($row['dn_returned'],$total_dn_returned);
            $dn_orders=percentage($row['dn_orders'],$total_dn_orders);
            $dn_shortages=percentage($row['dn_shortages'],$total_dn_shortages);
            $dn_replacements=percentage($row['dn_replacements'],$total_dn_replacements);
            $dn_donations=percentage($row['dn_donations'],$total_dn_donations);
            $dn_samples=percentage($row['dn_samples'],$total_dn_samples);
        } else {
            $dn=number($row['dn']);
            $dn_ready_to_pick=number($row['dn_ready_to_pick']);
            $dn_picking=number($row['dn_picking']);
            $dn_packing=number($row['dn_packing']);
            $dn_ready=number($row['dn_ready']);
            $dn_send=number($row['dn_send']);
            $dn_returned=number($row['dn_returned']);
            $dn_orders=number($row['dn_orders']);
            $dn_shortages=number($row['dn_shortages']);
            $dn_replacements=number($row['dn_replacements']);
            $dn_donations=number($row['dn_donations']);
            $dn_samples=number($row['dn_samples']);


        }

        $adata[]=array(
                     'code'=>$code,
                     'name'=>$name,
                     'dn'=>$dn,
                     'dn_ready_to_pick'=>$dn_ready_to_pick,
                     'dn_picking'=>$dn_picking,
                     'dn_packing'=>$dn_packing,
                     'dn_ready'=>$dn_ready,
                     'dn_send'=>$dn_send,
                     'dn_returned'=>$dn_returned,
                     'dn_orders'=>$dn_orders,
                     'dn_shortages'=>$dn_shortages,
                     'dn_replacements'=>$dn_replacements,
                     'dn_donations'=>$dn_donations,
                     'dn_samples'=>$dn_samples
                 );
    }
    mysql_free_result($res);

    if ($percentages) {
        $total_dn='100.00%';
        $total_dn_ready_to_pick='100.00%';
        $total_dn_packing='100.00%';
        $total_dn_picking='100.00%';
        $total_dn_ready='100.00%';
        $total_dn_send='100.00%';
        $total_dn_returned='100.00%';
        $total_dn_orders='100.00%';
        $total_dn_shortages='100.00%';
        $total_dn_replacements='100.00%';
        $total_dn_donations='100.00%';
        $total_dn_samples='100.00%';
    } else {
        $total_dn=number($total_dn);
        $total_dn_ready_to_pick=number($total_dn_ready_to_pick);
        $total_dn_packing=number($total_dn_packing);
        $total_dn_picking=number($total_dn_picking);
        $total_dn_ready=number($total_dn_ready);
        $total_dn_send=number($total_dn_send);
        $total_dn_returned=number($total_dn_returned);
        $total_dn_orders=number($total_dn_orders);
        $total_dn_shortages=number($total_dn_shortages);
        $total_dn_replacements=number($total_dn_replacements);
        $total_dn_donations=number($total_dn_donations);
        $total_dn_samples=number($total_dn_samples);


    }


    $adata[]=array(
                 'name'=>'',
                 'code'=>_('Total'),
                 'dn'=>$total_dn,
                 'dn_ready_to_pick'=>$total_dn_ready_to_pick,
                 'dn_picking'=>$total_dn_picking,
                 'dn_packing'=>$total_dn_packing,
                 'dn_ready'=>$total_dn_ready,
                 'dn_send'=>$total_dn_send,
                 'dn_returned'=>$total_dn_returned,
                 'dn_orders'=>$total_dn_orders,
                 'dn_shortages'=>$total_dn_shortages,
                 'dn_replacements'=>$total_dn_replacements,
                 'dn_donations'=>$total_dn_donations,
                 'dn_samples'=>$total_dn_samples

             );


    $total_records=ceil($total_records/$number_results)+$total_records;

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

function product_code_timeline() {






    $conf=$_SESSION['state']['product']['code_timeline'];
    //print_r($conf);
    $tableid=0;
    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];


    if (isset( $_REQUEST['code']))
        $code=$_REQUEST['code'];
    else
        $code=$conf['code'];


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



    $where=sprintf('where `Product Code`=%s  ',prepare_mysql($code));

    $wheref='';

    $order_direction=$order_dir;
    $_order=$order;
    $_dir=$order_direction;
    if ($order=='pid')
        $order='`Product ID`';
    if ($order=='from')
        $order='`Product History Valid From`';
    if ($order=='to')
        $order='`Product History Valid To`';
    else
        $order='`Product History Valid From`';


    $sql="select * from `Product History Dimension` PH left join `Product Dimension`  P on (P.`Product ID`=PH.`Product ID`)  $where $wheref  order by $order $order_direction limit $start_from,$number_results    ";
    // print $sql;
    $res = mysql_query($sql);
    $number_results=mysql_num_rows($res);

    $adata=array();
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {
        $id=sprintf("<a href='product.php?pid=%d'>%05d</a> <a href='product.php?key=%d'>(%05d)</a>"
                    ,$row['Product ID'],$row['Product ID']
                    ,$row['Product Key'] ,$row['Product Key']

                   );
        $adata[]=array(
                     'pid'=>$id
                           ,'description'=>$row['Product History XHTML Short Description']

                                          ,'parts'=>$row['Product XHTML Parts']
                                                   ,'from'=>strftime("%e %b %Y", strtotime($row['Product History Valid From']))
                                                           ,'to'=>strftime("%e %b %Y", strtotime($row['Product History Valid To']))
                                                                 ,'sales'=>money($row['Product History Total Invoiced Amount'],$row['Product Currency'])
                 );

    }
    mysql_free_result($res);
    $rtext=number($number_results).' '._('products with the same code');
    $rtext_rpp='';
    $filter_msg='';
    $total_records=$number_results;

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

function list_product_categories() {
    $conf=$_SESSION['state']['product_categories']['subcategories'];
    $conf2=$_SESSION['state']['product_categories'];
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
        $_SESSION['state']['product_categories']['exchange_type']=$exchange_type;
    } else
        $exchange_type=$conf2['exchange_type'];

    if (isset( $_REQUEST['exchange_value'])) {
        $exchange_value=addslashes($_REQUEST['exchange_value']);
        $_SESSION['state']['product_categories']['exchange_value']=$exchange_value;
    } else
        $exchange_value=$conf2['exchange_value'];

    if (isset( $_REQUEST['show_default_currency'])) {
        $show_default_currency=addslashes($_REQUEST['show_default_currency']);
        $_SESSION['state']['product_categories']['show_default_currency']=$show_default_currency;
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
        $_SESSION['state']['product_categories']['percentages']=$percentages;
    } else
        $percentages=$_SESSION['state']['product_categories']['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];
        $_SESSION['state']['product_categories']['period']=$period;
    } else
        $period=$_SESSION['state']['product_categories']['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];
        $_SESSION['state']['product_categories']['avg']=$avg;
    } else
        $avg=$_SESSION['state']['product_categories']['avg'];

    if (isset( $_REQUEST['stores_mode'])) {
        $stores_mode=$_REQUEST['stores_mode'];
        $_SESSION['state']['product_categories']['stores_mode']=$stores_mode;
    } else
        $stores_mode=$_SESSION['state']['product_categories']['stores_mode'];

    $_SESSION['state']['product_categories']['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value);
    // print_r($_SESSION['tables']['families_list']);

    //  print_r($_SESSION['tables']['families_list']);

    if (isset( $_REQUEST['category'])) {
        $root_category=$_REQUEST['category'];
        $_SESSION['state']['product_categories']['category']=$avg;
    } else
        $root_category=$_SESSION['state']['product_categories']['category_key'];



    $store_key=$_SESSION['state']['store']['id'];

    $where=sprintf("where `Category Subject`='Product' and  `Category Parent Key`=%d and `Category Store Key`=%d",$root_category,$store_key);
    //  $where=sprintf("where `Category Subject`='Product'  ");

    if ($stores_mode=='grouped')
        $group=' group by `Category Key`';
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
        $sql="select count(*) as total  from `Category Dimension` S  left join `Product Category Dimension` PC on (`Category Key`=PC.`Product Category Key`)   $where ";

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





    $sql="select *  from `Category Dimension` S  left join `Product Category Dimension` PC on (`Category Key`=PC.`Product Category Key`)   $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ";
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

        if ($percentages) {
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
        if ($stores_mode=='grouped')
            $name=sprintf('<a href="product_categories.php?id=%d">%s</a>',$row['Category Key'],$row['Category Name']);
        else
            $name=$row['Product Category Key'].' '.$row['Category Name']." (".$row['Product Category Store Key'].")";
        $adata[]=array(
                     //'go'=>sprintf("<a href='edit_category.php?edit=1&id=%d'><img src='art/icons/page_go.png' alt='go'></a>",$row['Category Key']),
                     'id'=>$row['Category Key'],
                     'name'=>$name,

                     'departments'=>number($row['Product Category Departments']),
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
                     'profit'=>$profit


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

function is_store_code($data) {

    if (!isset($data['query'])) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$data['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }



    $sql=sprintf("select `Store Key`,`Store Name`,`Store Code` from `Store Dimension` where  `Store Code`=%s  "
                 ,prepare_mysql($query)
                );
    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Store <a href="store.php?id=%d">%s</a> already has this code (%s)'
                     ,$data['Store Key']
                     ,$data['Store Name']
                     ,$data['Store Code']
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

function is_store_name($data) {
    if (!isset($data['query'])) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$data['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }



    $sql=sprintf("select `Store Key`,`Store Code` from `Store Dimension` where  `Store Name`=%s  "
                 ,prepare_mysql($query)
                );
    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Another store (<a href="store.php?id=%d">%s</a>) already has this name'
                     ,$data['Store Key']
                     ,$data['Store Code']
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

function is_department_name($data) {
    if (!isset($data['query']) or !isset($data['store_key'])) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$data['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $store_key=$data['store_key'];

    $sql=sprintf("select `Product Department Key`,`Product Department Code` from `Product Department Dimension` where  `Product Department Store Key`=%d and  `Product Department Name`=%s  "
                 ,$store_key
                 ,prepare_mysql($query)
                );
    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Another department (<a href="department.php?id=%d">%s</a>) already has this name'
                     ,$data['Product Department Key']
                     ,$data['Product Department Code']
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

function is_department_code($data) {
    if (!isset($data['query']) or !isset($data['store_key']) ) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$data['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $store_key=$data['store_key'];

    $sql=sprintf("select `Product Department Key`,`Product Department Name`,`Product Department Code` from `Product Department Dimension` where `Product Department Store Key`=%d and `Product Department Code`=%s  "
                 ,$store_key
                 ,prepare_mysql($query)
                );
    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Department <a href="department.php?id=%d">%s</a> already has this code (%s)'
                     ,$data['Product Department Key']
                     ,$data['Product Department Name']
                     ,$data['Product Department Code']
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

function is_family_name($data) {
    if (!isset($data['query']) or !isset($data['store_key'])) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$data['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $store_key=$data['store_key'];

    $sql=sprintf("select `Product Family Key`,`Product Family Code` from `Product Family Dimension` where  `Product Family Store Key`=%d and  `Product Family Name`=%s  "
                 ,$store_key
                 ,prepare_mysql($query)
                );
    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Another family (<a href="family.php?id=%d">%s</a>) already has this name'
                     ,$data['Product Family Key']
                     ,$data['Product Family Code']
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

function is_family_code($data) {


    if (!isset($data['query']) or !isset($data['store_key']) ) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$data['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $store_key=$data['store_key'];

    $sql=sprintf("select `Product Family Key`,`Product Family Name`,`Product Family Code` from `Product Family Dimension` where `Product Family Store Key`=%d and `Product Family Code`=%s  "
                 ,$store_key
                 ,prepare_mysql($query)
                );
    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Family <a href="family.php?id=%d">%s</a> already has this code (%s)'
                     ,$data['Product Family Key']
                     ,$data['Product Family Name']
                     ,$data['Product Family Code']
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

function is_family_special_char($data) {
    if (!isset($data['query']) or !isset($data['store_key'])) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$data['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $store_key=$data['store_key'];

    $sql=sprintf("select `Product Family Key`,`Product Family Code` from `Product Family Dimension` where  `Product Family Store Key`=%d and  `Product Family Special Characteristic`=%s  "
                 ,$store_key
                 ,prepare_mysql($query)
                );
    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Another family (<a href="family.php?id=%d">%s</a>) has the same special characteristic'
                     ,$data['Product Family Key']
                     ,$data['Product Family Code']
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

function part_transactions() {
    $conf=$_SESSION['state']['part']['transactions'];
    $part_sku=$_SESSION['state']['part']['sku'];
    if (isset( $_REQUEST['elements']))
        $elements=$_REQUEST['elements'];
    else
        $elements=$conf['elements'];

    if (isset( $_REQUEST['from']))
        $from=$_REQUEST['from'];
    else
        $from=$conf['from'];
    if (isset( $_REQUEST['to']))
        $to=$_REQUEST['to'];
    else
        $to=$conf['to'];
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

    if (isset( $_REQUEST['view']))
        $view=$_REQUEST['view'];
    else
        $view=$conf['view'];

    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;


    list($date_interval,$error)=prepare_mysql_dates($from,$to);
    if ($error) {
        list($date_interval,$error)=prepare_mysql_dates($conf['from'],$conf['to']);
    } else {
        $_SESSION['state']['part']['transactions']['from']=$from;
        $_SESSION['state']['part']['transactions']['to']=$to;
    }

    $_SESSION['state']['part']['transactions']=
        array(
            'view'=>$view,
            'order'=>$order,
            'order_dir'=>$order_direction,
            'nr'=>$number_results,
            'sf'=>$start_from,
            'where'=>$where,
            'f_field'=>$f_field,
            'f_value'=>$f_value,
            'from'=>$from,
            'to'=>$to,
            'elements'=>$elements,
            'f_show'=>$_SESSION['state']['part']['transactions']['f_show']
        );
    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';

    $wheref='';

    if ($f_field=='note' and $f_value!='') {
        // $wheref.=" and  `Note` like '%".addslashes($f_value)."%'  or  `Note` REGEXP '[[:<:]]".$f_value."'  ";
        $wheref.=" and  `Note` like '".addslashes($f_value)."%'  ";

    }

    $where=$where.sprintf(" and `Part SKU`=%d ",$part_sku);

    switch ($view) {
    case 'oip_transactions':
        $where.=" and `Inventory Transaction Type`='Order In Process' ";
        break;
    case('in_transactions'):
        $where.=" and `Inventory Transaction Type` in ('In') ";
        break;
    case('move_transactions'):
        $where.=" and `Inventory Transaction Type` in ('Move In','Move Out') ";
        break;
    case('out_transactions'):
        $where.=" and `Inventory Transaction Type` in ('Sale','Broken','Lost') ";
        break;
    case('audit_transactions'):
        $where.="and `Inventory Transaction Type` in ('Not Found','No Dispatched','Associate','Disassociate','Adjust') ";
        break;
    default:
        $where.="and `Inventory Transaction Type`!='Audit' ";
        break;
        break;
    }



    $sql="select count(*) as total from `Inventory Transaction Fact`     $where $wheref";
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $total=$row['total'];
    }
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Inventory Transaction Fact`   $where ";
        $result=mysql_query($sql);
        if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
            $total_records=$row['total'];
            $filtered=$row['total']-$total;
        }

    }



    $rtext=$total.' '.ngettext('stock operation','stock operations',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';



    if ($total_records==0) {
        $rtext=_('No stock movements');
        $rtext_rpp='';
    }




    $rtext=$total_records." ".ngettext('stock operation','stock operations',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';



    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('note'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._("There isn't any note like")." <b>".$f_value."*</b> ";
            break;

        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('note'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/> '._('Showing')." $total "._('notes with')." <b>".$f_value."*</b>";
            break;

        }
    }
    else
        $filter_msg='';



    $order=' `Date` desc , `Inventory Transaction Key` desc ';
    $order_direction=' ';

    $sql="select  `Required`,`Picked`,`Packed`,`Note`,`Inventory Transaction Type`,`Inventory Transaction Quantity`,`Date`,ITF.`Location Key`,`Location Code` ,ITF.`Inventory Transaction Key` from `Inventory Transaction Fact` ITF left join `Location Dimension` L on (ITF.`Location key`=L.`Location key`)  $where $wheref order by $order $order_direction limit $start_from,$number_results ";


    //print $sql;
    $result=mysql_query($sql);
    $adata=array();
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

        $qty=$data['Inventory Transaction Quantity'];



        if ($qty>0) {
            $qty='+'.$qty;
        }
        elseif($qty==0) {
            $qty='';
        }

        switch ($data['Inventory Transaction Type']) {
        case 'Order In Process':
            $transaction_type='OIP';
            $qty.='('.(-1*$data['Required']).')';
            break;
        default:
            $transaction_type=$data['Inventory Transaction Type'];
            break;
        }


        $location=sprintf('<a href="location.php?id=%d">%s</a>',$data['Location Key'],$data {'Location Code'});
        $adata[]=array(

                     'type'=>$transaction_type,
                     'change'=>$qty,
                     'date'=>strftime("%c", strtotime($data['Date'])),
                     'note'=>$data['Note'],
                     'location'=>$location

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

function part_stock_history() {
    $conf=$_SESSION['state']['part']['stock_history'];






    if (isset( $_REQUEST['part_sku']))
        $part_sku=$_REQUEST['part_sku'];
    else
        $part_sku=$_SESSION['state']['part']['id'];


    if (isset( $_REQUEST['elements']))
        $elements=$_REQUEST['elements'];
    else
        $elements=$conf['elements'];

    if (isset( $_REQUEST['from']))
        $from=$_REQUEST['from'];
    else
        $from=$conf['from'];
    if (isset( $_REQUEST['to']))
        $to=$_REQUEST['to'];
    else
        $to=$conf['to'];
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


    if (isset( $_REQUEST['type']))
        $type=$_REQUEST['type'];
    else
        $type=$conf['type'];




    list($date_interval,$error)=prepare_mysql_dates($from,$to);
    if ($error) {
        list($date_interval,$error)=prepare_mysql_dates($conf['from'],$conf['to']);
    } else {
        $_SESSION['state']['part']['stock_history']['from']=$from;
        $_SESSION['state']['part']['stock_history']['to']=$to;
    }

    $_SESSION['state']['part']['stock_history']=
        array(
            'order'=>$order,
            'type'=>$type,
            'order_dir'=>$order_direction,
            'nr'=>$number_results,
            'sf'=>$start_from,
            'where'=>$where,
            'f_field'=>$f_field,
            'f_value'=>$f_value,
            'from'=>$from,
            'to'=>$to,
            'elements'=>$elements,
            'f_show'=>$_SESSION['state']['part']['stock_history']['f_show']
        );
    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';

    $wheref='';




    switch ($type) {
    case 'month':
        $group=' group by DATE_FORMAT(`Date`,"%Y%m")   ';
        break;
    case 'day':
        $group=' group by `Date`   ';
        break;
    default:
        $group=' group by YEARWEEK(`Date`)   ';
        break;
    }




    $where=$where.sprintf(" and `Part SKU`=%d ",$part_sku);
    $sql="select count(*) as total from `Inventory Spanshot Fact`     $where $wheref $group";

    $result=mysql_query($sql);
    $total=mysql_num_rows($result);





    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(*) as total from `Inventory Spanshot Fact`   $where  $group";



        $total_records=$result;
        $filtered=$total_records-$total;

    }




    switch ($type) {
    case 'month':
        $rtext=$total_records.' '.ngettext('month','months',$total);
        break;
    case 'day':
        $rtext=$total_records.' '.ngettext('days','days',$total);
        break;
    default:
        $rtext=$total_records.' '.ngettext('week','weeks',$total);
        break;
    }



    if ($total_records>$number_results)
        $rtext_rpp=sprintf("(%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=' ('._('Showing all').')';



    if ($total_records==0) {
        $rtext=_('No stock history');
        $rtext_rpp='';
    }


    $order='`Date`';

    $sql=sprintf("select  GROUP_CONCAT(distinct '<a href=\"location.php?id=',ISF.`Location Key`,'\">',`Location Code`,'<a/>') as locations,`Date`, ( select  sum(`Quantity On Hand`) from `Inventory Spanshot Fact` OISF where `Part SKU`=%d and OISF.`Date`=ISF.`Date`  )as `Quantity On Hand`, ( select  sum(`Value At Cost`) from `Inventory Spanshot Fact` OISF where `Part SKU`=%d and OISF.`Date`=ISF.`Date`  )as `Value At Cost`,sum(`Sold Amount`) as `Sold Amount`,sum(`Value Comercial`) as `Value Comercial`,sum(`Storing Cost`) as `Storing Cost`,sum(`Quantity Sold`) as `Quantity Sold`,sum(`Quantity In`) as `Quantity In`,sum(`Quantity Lost`) as `Quantity Lost`  from `Inventory Spanshot Fact` ISF left join `Location Dimension` L on (ISF.`Location key`=L.`Location key`)  $where $wheref   %s order by $order $order_direction  limit $start_from,$number_results "
                 ,$part_sku
                 ,$part_sku
                 ,$group
                );



    $result=mysql_query($sql);
    $adata=array();
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {



        switch ($type) {
        case 'month':
            $date=strftime("%m/%Y", strtotime($data['Date']));
            break;
        case 'day':
            $date=strftime("%a %d/%m/%Y", strtotime($data['Date']));
            break;
        default:
            $date=_('Week').' '.strftime("%V %Y", strtotime($data['Date']));
            break;
        }

        $adata[]=array(

                     'date'=>$date,
                     'locations'=>$data['locations'],
                     'quantity'=>number($data['Quantity On Hand']),
                     'value'=>money($data['Value At Cost']),
                     'sold_qty'=>number($data['Quantity Sold']),
                     'in_qty'=>number($data['Quantity In']),
                     'lost_qty'=>number($data['Quantity Lost'])
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
                                      'total_records'=>$total_records,
                                      'records_offset'=>$start_from,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);
}

function new_products_list($data) {
    $list_name=$data['list_name'];
    $store_id=$data['store_id'];

    $sql=sprintf("select * from `List Dimension`  where `List Name`=%s and `List Store Key`=%d ",
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
    $table='`Product Dimension` P ';


//   $where=customers_awhere($awhere);
    list($where,$table)=product_awhere($awhere);

    $where.=sprintf(' and `Product Store Key`=%d ',$store_id);


    $sql="select count(Distinct P.`Product ID`) as total from $table  $where";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        if ($row['total']==0) {
            $response=array('resultset'=>
                                        array(
                                            'state'=>400,
                                            'msg'=>_('No products match this criteria')
                                        )
                           );
            echo json_encode($response);
            return;

        }


    }
    mysql_free_result($res);

    $list_sql=sprintf("insert into `List Dimension` (`List Scope`,`List Store Key`,`List Name`,`List Type`,`List Metadata`,`List Creation Date`) values ('Product',%d,%s,%s,%s,NOW())",
                      $store_id,
                      prepare_mysql($list_name),
                      prepare_mysql($list_type),
                      prepare_mysql(json_encode($data['awhere']))

                     );
    mysql_query($list_sql);
    $customer_list_key=mysql_insert_id();

    if ($list_type=='Static') {


        $sql="select P.`Product ID` from $table  $where group by P.`Product ID`";
        //   print $sql;
        $result=mysql_query($sql);
        while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $customer_key=$data['Product ID'];
            $sql=sprintf("insert into `List Product Bridge` (`List Key`,`Product ID`) values (%d,%d)",
                         $customer_list_key,
                         $customer_key
                        );
            mysql_query($sql);

        }
        mysql_free_result($result);




    }




    $response=array(
                  'state'=>200,
                  'customer_list_key'=>$customer_list_key

              );
    echo json_encode($response);

}

function new_parts_list($data) {
//print 'xx';exit;
    $list_name=$data['list_name'];
    //$store_id=$data['store_id'];

    $sql=sprintf("select * from `List Dimension`  where `List Name`=%s",
                 prepare_mysql($list_name)
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
    $table='`Product Dimension` P ';


//   $where=customers_awhere($awhere);
    list($where,$table)=parts_awhere($awhere);

    //$where.=sprintf(' and `Product Store Key`=%d ',$store_id);


    $sql="select count(Distinct P.`Part SKU`) as total from $table  $where";

    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        if ($row['total']==0) {
            $response=array('resultset'=>
                                        array(
                                            'state'=>400,
                                            'msg'=>_('No products match this criteria')
                                        )
                           );
            echo json_encode($response);
            return;

        }


    }
    mysql_free_result($res);

    $list_sql=sprintf("insert into `List Dimension` (`List Scope`,`List Store Key`,`List Name`,`List Type`,`List Metadata`,`List Creation Date`) values ('Part',%d,%s,%s,%s,NOW())",
                      0,
                      prepare_mysql($list_name),
                      prepare_mysql($list_type),
                      prepare_mysql(json_encode($data['awhere']))

                     );
    mysql_query($list_sql);
    $customer_list_key=mysql_insert_id();

    if ($list_type=='Static') {


        $sql="select P.`Part SKU` from $table  $where group by P.`Part SKU`";
        //print $sql;exit;
        $result=mysql_query($sql);
        while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {

            $customer_key=$data['Part SKU'];
            $sql=sprintf("insert into `List Part Bridge` (`List Key`,`Part SKU`) values (%d,%d)",
                         $customer_list_key,
                         $customer_key
                        );
            mysql_query($sql);

        }
        mysql_free_result($result);




    }




    $response=array(
                  'state'=>200,
                  'customer_list_key'=>$customer_list_key

              );
    echo json_encode($response);

}

function list_products_lists() {

    global $user;

    $conf=$_SESSION['state']['products']['list'];
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



        $awhere=$_REQUEST['where'];
    else
        $awhere=$conf['where'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;

    if (isset( $_REQUEST['store_id'])    ) {
        $store=$_REQUEST['store_id'];
        $_SESSION['state']['products']['store']=$store;
    } else
        $store=$_SESSION['state']['products']['store'];


    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    $_SESSION['state']['products']['list']['order']=$order;
    $_SESSION['state']['products']['list']['order_dir']=$order_direction;
    $_SESSION['state']['products']['list']['nr']=$number_results;
    $_SESSION['state']['products']['list']['sf']=$start_from;
    $_SESSION['state']['products']['list']['where']=$awhere;
    $_SESSION['state']['products']['list']['f_field']=$f_field;
    $_SESSION['state']['products']['list']['f_value']=$f_value;



    $where=' where `List Scope`="Product"';
    if (in_array($store,$user->stores)) {
        $where.=sprintf(' and   `List Store Key`=%d  ',$store);

    }

    $wheref='';

    $sql="select count(distinct `List Key`) as total from `List Dimension`  $where  ";
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total_without_filters from `List Dimension` $where $wheref ";
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


    $rtext=$total_records." ".ngettext('List','Lists',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=_("Showing all Lists");




    $filter_msg='';





    $_order=$order;
    $_dir=$order_direction;


    if ($order=='name')
        $order='`List Name`';
    elseif($order=='creation_date')
    $order='`List Creation Date`';
    elseif($order=='product_list_type')
    $order='`List Type`';

    else
        $order='`List Key`';


    $sql="select  CLD.`List key`,CLD.`List Name`,CLD.`List Store Key`,CLD.`List Creation Date`,CLD.`List Type` from `List Dimension` CLD $where  order by $order $order_direction limit $start_from,$number_results";

    $adata=array();



    $result=mysql_query($sql);
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {





        $cusomer_list_name=" <a href='products_list.php?id=".$data['List key']."'>".$data['List Name'].'</a>';
        switch ($data['List Type']) {
        case 'Static':
            $product_list_type=_('Static');
            break;
        default:
            $product_list_type=_('Dynamic');
            break;

        }

        $adata[]=array(


                     'product_list_type'=>$product_list_type,
                     'name'=>$cusomer_list_name,
                     'key'=>$data['List key'],
                     'creation_date'=>strftime("%a %e %b %y %H:%M", strtotime($data['List Creation Date']." +00:00")),
                     'add_to_email_campaign_action'=>'<span class="state_details" onClick="add_to_email_campaign('.$data['List key'].')">'._('Add List').'</span>',
                     'delete'=>'<img src="art/icons/cross.png"/>'


                 );

    }


    mysql_free_result($result);


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


function list_parts_lists() {

    global $user;

    $conf=$_SESSION['state']['products']['list'];
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



        $awhere=$_REQUEST['where'];
    else
        $awhere=$conf['where'];


    if (isset( $_REQUEST['tableid']))
        $tableid=$_REQUEST['tableid'];
    else
        $tableid=0;
    /*
        if (isset( $_REQUEST['store_id'])    ) {
            $store=$_REQUEST['store_id'];
            $_SESSION['state']['products']['store']=$store;
        } else
            $store=$_SESSION['state']['products']['store'];
    */

    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');



    $_SESSION['state']['parts']['list']['order']=$order;
    $_SESSION['state']['parts']['list']['order_dir']=$order_direction;
    $_SESSION['state']['parts']['list']['nr']=$number_results;
    $_SESSION['state']['parts']['list']['sf']=$start_from;
    $_SESSION['state']['parts']['list']['where']=$awhere;
    $_SESSION['state']['parts']['list']['f_field']=$f_field;
    $_SESSION['state']['parts']['list']['f_value']=$f_value;



    $where=' where `List Scope`="Part"';


    $wheref='';

    $sql="select count(distinct `List Key`) as total from `List Dimension`  $where  ";
    $res=mysql_query($sql);
    if ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {

        $total=$row['total'];
    }
    if ($wheref!='') {
        $sql="select count(*) as total_without_filters from `List Dimension` $where $wheref ";
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


    $rtext=$total_records." ".ngettext('List','Lists',$total_records);
    if ($total_records>$number_results)
        $rtext_rpp=sprintf(" (%d%s)",$number_results,_('rpp'));
    else
        $rtext_rpp=_("Showing all Lists");




    $filter_msg='';





    $_order=$order;
    $_dir=$order_direction;


    if ($order=='name')
        $order='`List Name`';
    elseif($order=='creation_date')
    $order='`List Creation Date`';
    elseif($order=='product_list_type')
    $order='`List Type`';

    else
        $order='`List Key`';


    $sql="select  CLD.`List key`,CLD.`List Name`,CLD.`List Store Key`,CLD.`List Creation Date`,CLD.`List Type` from `List Dimension` CLD $where  order by $order $order_direction limit $start_from,$number_results";

    $adata=array();



    $result=mysql_query($sql);
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {





        $cusomer_list_name=" <a href='parts_list.php?id=".$data['List key']."'>".$data['List Name'].'</a>';
        switch ($data['List Type']) {
        case 'Static':
            $product_list_type=_('Static');
            break;
        default:
            $product_list_type=_('Dynamic');
            break;

        }

        $adata[]=array(


                     'product_list_type'=>$product_list_type,
                     'name'=>$cusomer_list_name,
                     'key'=>$data['List key'],
                     'creation_date'=>strftime("%a %e %b %y %H:%M", strtotime($data['List Creation Date']." +00:00")),
                     'add_to_email_campaign_action'=>'<span class="state_details" onClick="add_to_email_campaign('.$data['List key'].')">'._('Add List').'</span>',
                     'delete'=>'<img src="art/icons/cross.png"/>'


                 );

    }


    mysql_free_result($result);


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



function part_location_info($data) {

    $part=new Part($data['sku']);


    $data=array(
              'description'=>'<span class="id">'.$part->get_sku().'</span><br/>'.$part->data['Part XHTML Description'].'<br/>'._('Sold as').': '.$part->data['Part XHTML Currently Used In']
          );

    $response= array('state'=>200,'data'=>$data);
    echo json_encode($response);
    return;




}

function is_product_name($data) {
    if (!isset($data['query']) or !isset($data['store_key'])) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$data['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $store_key=$data['store_key'];

    $sql=sprintf("select `Product ID`,`Product Code` from `Product Dimension` where  `Product Store Key`=%d and  `Product Name`=%s  "
                 ,$store_key
                 ,prepare_mysql($query)
                );
    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Another product (<a href="product.php?pid=%d">%s</a>) already has this name'
                     ,$data['Product ID']
                     ,$data['Product Code']
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

function is_product_code($data) {


    if (!isset($data['query']) or !isset($data['store_key']) ) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$data['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $store_key=$data['store_key'];

    $sql=sprintf("select `Product ID`,`Product Name`,`Product Code` from `Product Dimension` where `Product Store Key`=%d and `Product Code`=%s  "
                 ,$store_key
                 ,prepare_mysql($query)
                );
    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Product <a href="product.php?pid=%d">%s</a> already has this code (%s)'
                     ,$data['Product ID']
                     ,$data['Product Name']
                     ,$data['Product Code']
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

function is_product_special_char($data) {
    if (!isset($data['query']) or !isset($data['family_key'])) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$data['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $family_key=$data['family_key'];

    $sql=sprintf("select `Product ID`,`Product Code` from `Product Dimension` where  `Product Family Key`=%d and  `Product Special Characteristic`=%s  "
                 ,$family_key
                 ,prepare_mysql($query)
                );
    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('Another product (<a href="product.php?pid=%d">%s</a>) has the same special characteristic'
                     ,$data['Product ID']
                     ,$data['Product Code']
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


function list_customers_who_order_product() {
    $conf=$_SESSION['state']['product']['customers'];

    if (isset( $_REQUEST['code'])) {
        $tag=$_REQUEST['code'];
        $mode='code';
    } else if (isset( $_REQUEST['id'])) {
        $tag=$_REQUEST['id'];
        $mode='id';
    } else if (isset( $_REQUEST['key'])) {
        $tag=$_REQUEST['key'];
        $mode='key';
    } else {
        $tag=$_SESSION['state']['product']['tag'];
        $mode=$_SESSION['state']['product']['mode'];
    }

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
    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');
    $_SESSION['state']['product']['custumers']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'tag'=>$tag,'mode'=>$mode);
    $_order=$order;
    $_dir=$order_direction;
    $filter_msg='';

    $table='`Order Transaction Fact` OTF left join `Product History Dimension`  PD on (PD.`Product Key`=OTF.`Product Key`)  ';

    if ($mode=='code') {
        $where=$where.sprintf(" and P.`Product Code`=%s ",prepare_mysql($tag));
        $table='`Order Transaction Fact` OTF left join `Product History Dimension` PD  on (PD.`Product Key`=OTF.`Product Key`) left join `Product Dimension` P  on (PD.`Product ID`=P.`Product ID`)  ';

    }
    elseif($mode=='pid')
    $where=$where.sprintf(" and OTF.`Product ID`=%d ",$tag);
    elseif($mode=='key')
    $where=$where.sprintf(" and OTF.`Product Key`=%d ",$tag);


    $wheref="";

    if ($f_field=='max' and is_numeric($f_value) )
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))<=".$f_value."    ";
    else if ($f_field=='min' and is_numeric($f_value) )
        $wheref.=" and  (TO_DAYS(NOW())-TO_DAYS(date_index))>=".$f_value."    ";
    elseif($f_field=='customer_name'  and $f_value!='')
    $wheref.=" and  ".$f_field." like '".addslashes($f_value)."%'";


    $sql="select count(distinct `Customer Key`) as total from  $table  $where $wheref";
    // print $mode.' '.$sql;
    $res = mysql_query($sql);
    if ($row=mysql_fetch_array($res)) {
        $total=$row['total'];
    }
    mysql_free_result($res);
    if ($wheref=='') {
        $filtered=0;
        $total_records=$total;
    } else {
        $sql="select count(distinct `Customer Key`) as total from  $table  $where      ";

        $res = mysql_query($sql);
        if ($row=mysql_fetch_array($res)) {
            $total_records=$row['total'];
            $filtered=$total_records-$total;
        }
        mysql_free_result($res);
    }
    $rtext=$total_records." ".ngettext('customer','customers',$total_records);
    if ($total_records>$number_results)
        $rtext.=sprintf(" <span class='rtext_rpp'>(%d%s)</span>",$number_results,_('rpp'));


    $filter_msg='';
    if ($total==0 and $filtered>0) {
        switch ($f_field) {
        case('public_id'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._("There isn't any order starting with")." <b>$f_value</b> ";
            break;
        }
    }
    elseif($filtered>0) {
        switch ($f_field) {
        case('public_id'):
            $filter_msg='<img style="vertical-align:bottom" src="art/icons/exclamation.png"/>'._('Showing')." $total "._('only orders starting with')." <b>$f_value</b>";
            break;
        }
    }


    $_order=$order;
    $_dir=$order_direction;

    if ($order=='dispatched')
        $order='dispatched';
    elseif($order=='orders')
    $order='orders';
    elseif($order=='charged')
    $order='charged';
    elseif($order=='to_dispatch')
    $order='to_dispatch';
    elseif($order=='dispatched')
    $order='dispatched';
    elseif($order=='nodispatched')
    $order='nodispatched';
    else
        $order='`Customer Name`';


    $sql=sprintf("select   CD.`Customer Key` as customer_id,`Customer Name`,`Customer Main Location`,sum(`Invoice Transaction Gross Amount`-`Invoice Transaction Total Discount Amount`-`Invoice Transaction Net Refund Amount`) as charged ,count(distinct `Order Key`) as orders ,sum(`Shipped Quantity`) as dispatched,sum(`Current Manufacturing Quantity`+`Current On Shelf Quantity`+`Current On Box Quantity`) as to_dispatch,sum(`No Shipped Due Out of Stock`+`No Shipped Due No Authorized`+`No Shipped Due Not Found`) as nodispatched from     `Order Transaction Fact` OTF left join `Customer Dimension` CD on (OTF.`Customer Key`=CD.`Customer Key`)  left join `Product History Dimension` PD on (PD.`Product Key`=OTF.`Product Key`)       left join `Product Dimension` P  on (PD.`Product ID`=P.`Product ID`)     $where $wheref  group by CD.`Customer Key`    order by $order $order_direction  limit $start_from,$number_results "
                );

    $data=array();

    $res = mysql_query($sql);
    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {


        $data[]=array(
                    'customer'=>sprintf('<a href="customer.php?id=%d"><b>%s</b></a>, %s',$row['customer_id'],$row['Customer Name'],$row['Customer Main Location']),
                    'charged'=>money($row['charged']),
                    'orders'=>number($row['orders']),
                    'to_dispatch'=>number($row['to_dispatch']),
                    'dispatched'=>number($row['dispatched']),
                    'nodispatched'=>number($row['nodispatched'])

                );
    }
    mysql_free_result($res);

    $response=array('resultset'=>
                                array('state'=>200,
                                      'data'=>$data,
                                      'sort_key'=>$_order,
                                      'rtext'=>$rtext,
                                      'sort_dir'=>$_dir,
                                      'tableid'=>$tableid,
                                      'filter_msg'=>$filter_msg,
                                      'total_records'=>$total,
                                      'records_offset'=>$start_from,
                                      'records_returned'=>$start_from+$total,
                                      'records_perpage'=>$number_results,
                                      'records_text'=>$rtext,
                                      'records_order'=>$order,
                                      'records_order_dir'=>$order_dir,
                                      'filtered'=>$filtered
                                     )
                   );
    echo json_encode($response);
}




?>
