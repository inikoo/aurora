<?php
/*
 File: ar_assets.php

 Ajax Server Anchor for the Product,Family,Department and Part Clases

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Kaktus

 Version 2.0
*/

require_once 'common.php';
//require_once 'stock_functions.php';
require_once 'class.Product.php';
require_once 'class.Department.php';
require_once 'class.Family.php';

require_once 'class.Order.php';
require_once 'class.Location.php'
;
require_once 'class.PartLocation.php';
require_once 'common_store_functions.php';
//require_once 'ar_common.php';

require_once 'ar_edit_common.php';



if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('families'):
    list_families();


    break;
    //========================================================================================
case('stores'):
    list_stores();
    break;
    //=====================================================================================
case('departments'):
    list_departments();

    break;


case('products'):
    list_products();

    break;




default:

    $response=array('state'=>404,'msg'=>_('Operation not found'));
    echo json_encode($response);

}

function list_departments() {
//     $conf_table='store';
//       $conf=$_SESSION['state']['store']['table'];
//       $conf2=$_SESSION['state']['store'];


    if (isset( $_REQUEST['store']) and  is_numeric( $_REQUEST['store']))
        $store_id=$_REQUEST['store'];
    else
        $store_id=$_SESSION['state']['store']['id'];


    if (isset( $_REQUEST['parent']))
        $parent=$_REQUEST['parent'];
    else
        $parent='none';


    if ($parent=='store') {
        $conf=$_SESSION['state']['store']['table'];
        $conf2=$_SESSION['state']['store'];

        $conf_table='store';
    } else {

        $conf=$_SESSION['state']['store']['table'];
        $conf2=$_SESSION['state']['store'];
        $conf_table='departments';
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
        $percentages=$conf2['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];
    } else
        $period=$conf2['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];
    } else
        $avg=$conf2['avg'];





    $_SESSION['state'][$conf_table]['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value,'parent'=>$parent);
    $_SESSION['state'][$conf_table]['percentages']=$percentages;
    $_SESSION['state'][$conf_table]['period']=$period;
    $_SESSION['state'][$conf_table]['avg']=$avg;


    switch ($parent) {
    case('store'):
        $where=sprintf("where  `Product Department Store Key`=%d",$store_id);
        break;
    default:
        $where='where true';

    }


    $filter_msg='';
    $wheref='';
    if ($f_field=='name' and $f_value!='')
        $wheref.=" and  `Product Department Name` like '".addslashes($f_value)."%'";
    if ($f_field=='code' and $f_value!='')
        $wheref.=" and  `Product Department Code` like '".addslashes($f_value)."%'";





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


    $sum_families=0;
    $sum_active=0;
    $sum_discontinued=0;
    $sql="select sum(`Product Department For Public Sale Products`) as sum_active, sum(`Product Department Discontinued Products`) as sum_discontinued,sum(`Product Department Families`) as sum_families  from `Product Department Dimension` $where  $wheref ";
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $sum_families=$row['sum_families'];
        $sum_active=$row['sum_active'];
        $sum_discontinued=$row['sum_discontinued'];
    }

    if ($period=='all') {

        //$aws_p=money($row['Product Department Total Avg Week Sales Per Product']);
        // $awp_p=money($row['Product Department Total Avg Week Profit Per Product']);

        $sum_total_sales=0;
        $sum_month_sales=0;
        $sql="select   max(`Product Department Total Days Available`) as 'Product Department Total Days Available',max(`Product Department Total Days On Sale`) as 'Product Department Total Days On Sale', sum(if(`Product Department Total Profit`<0,`Product Department Total Profit`,0)) as total_profit_minus,sum(if(`Product Department Total Profit`>=0,`Product Department Total Profit`,0)) as total_profit_plus,sum(`Product Department Total Invoiced Amount`) as sum_total_sales  from `Product Department Dimension` $where   ";

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


        if ($percentages) {
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


        } else {






            if ($period=='all') {


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

                $tsall=$row['Product Department Total Invoiced Amount']*$factor;
                $tprofit=$row['Product Department Total Profit']*$factor;

//print ($row['Product Department Total Days On Sale']/30/12)."\n";


            }
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
        }
        $adata[]=array(
                     'code'=>$code,
                     'name'=>$name,
                     'families'=>number($row['Product Department Families']),
                     'active'=>number($row['Product Department For Public Sale Products']),
                     'todo'=>number($row['Product Department In Process Products']),
                     'discontinued'=>number($row['Product Department Discontinued Products']),

                     'outofstock'=>number($row['Product Department Out Of Stock Products']),
                     'stock_error'=>number($row['Product Department Unknown Stock Products']),
                     'stock_value'=>money($row['Product Department Stock Value']),
                     'surplus'=>number($row['Product Department Surplus Availability Products']),
                     'optimal'=>number($row['Product Department Optimal Availability Products']),
                     'low'=>number($row['Product Department Low Availability Products']),
                     'critical'=>number($row['Product Department Critical Availability Products']),


                     'sales'=>$tsall,

                     'profit'=>$tprofit,
                     'aws_p'=>$aws_p,
                     'awp_p'=>$awp_p

                 );


    }
    mysql_free_result($res);

    if ($total<=$number_results and $total>1) {

        if ($percentages) {
            $tsall='100.00%';
            $tprofit='100.00%';
        } else {
            $tsall=money($sum_total_sales,$currency_code);
            $tprofit=money($sum_total_profit,$currency_code);
        }

        $adata[]=array(

                     'code'=>_('Total'),
                     'families'=>number($sum_families),
                     'active'=>number($sum_active),
                     'sales'=>$tsall,
                     'profit'=>$tprofit,
                     'discontinued'=>number($sum_discontinued)
// 		 'outofstock'=>number($row['product department out of stock products']),
// 		 'stockerror'=>number($row['product department unknown stock products']),
// 		 'stock_value'=>money($row['product department stock value']),
// 		 'tsall'=>$tsall,'tprofit'=>$tprofit,
// 		 'per_tsall'=>percentage($row['product department total invoiced amount'],$sum_total_sales,2),
// 		 'tsm'=>money($row['product department 1 month acc invoiced amount']),
// 		 'per_tsm'=>percentage($row['product department 1 month acc invoiced amount'],$sum_month_sales,2),
                 );

    } else {
        $adata[]=array();

    }
    $total_records=ceil($total/$number_results)+$total;
    $number_results++;



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
                                      'records_offset'=>$start_from+1,
                                      'records_perpage'=>$number_results,
                                     )
                   );
    echo json_encode($response);
}
function list_products() {



    $conf=$_SESSION['state']['products']['table'];
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

if(!$number_results)
$number_results=100;




    //    if(!is_numeric($number_results)){
    // 	print $number_results."xx";
    // 	$number_results=25;

    //       }

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



    $_SESSION['state']['products']['table']=array(
    'order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
                                           ,'parent'=>$parent
                                                 );



    $db_table='`Product Dimension`';



    switch ($parent) {
    case('store'):
    
    
        $where=sprintf(' where `Product Store Key`=%d',$_SESSION['state']['store']['id']);
        break;
    case('department'):
        $where=sprintf(' left join `Product Department Bridge` B on (P.`Product Key`=B.`Product Key`) where `Product Department Key`=%d',$_SESSION['state']['department']['id']);
        break;
    case('family'):
        if(isset($_REQUEST['parent_key']))
        $parent_key=$_REQUEST['parent_key'];
        else
        $parent_key=$_SESSION['state']['family']['id'];

        $where=sprintf(' where `Product Family Key`=%d',$parent_key);
        break;
    default:
        $where=sprintf(' where true ');

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
    if ($f_field=='code' and $f_value!='')
        $wheref.=" and  `Product Code` like '".addslashes($f_value)."%'";
    elseif($f_field=='description' and $f_value!='')
    $wheref.=" and  `Product Name` like '%".addslashes($f_value)."%'";

    $sql="select count(*) as total from $db_table  $where $wheref   ";
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

$order='`Product Name`';
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
        $order='`Product Web State`';
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





    $sum_total_sales=0;
    $sum_total_profit=0;
    $sum_total_stock_value=0;

if(!isset($_SESSION['order_key']))
$order_key=0;
else
  $order_key=$_SESSION['order_key'];

    $sql=sprintf("select  `Product Current Key`,IFNULL((select sum(`Order Quantity`) from `Order Transaction Fact` where `Product Key`=`Product Current Key` and `Order Key`=%d),0) as `Order Quantity`,`Product Main Image`,`Product Name`,`Product RRP`,`Product Availability State`,`Product ID`,`Product Code`,`Product Availability`,`Product Price`,`Product Units Per Case`,`Product Currency`,`Product Unit Type` from `Product Dimension` P   $where $wheref $group order by $order $order_direction limit $start_from,$number_results    ",$order_key);
    //print $sql;
    $res = mysql_query($sql);
    $adata=array();

    while ($row=mysql_fetch_array($res, MYSQL_ASSOC)) {




        $code=sprintf('<a href="product.php?pid=%s">%s</a>',$row['Product ID'],$row['Product Code']);


     

        if (is_numeric($row['Product Availability']))
            $stock=number($row['Product Availability']);
        else
            $stock='?';

        $stock_state=$row['Product Availability State'];


        //		print_r($locale_product_record_type);
        //print $record_type;
        
        
         $price_data=array(
'Product Price'=>$row['Product Price'],
'Product Units Per Case'=>$row['Product Units Per Case'],
'Product Currency'=>$row['Product Currency'],
'Product Unit Type'=>$row['Product Unit Type'],

'locale'=>'');

         $rrp_data=array(
'Product RRP'=>$row['Product RRP'],
'Product Units Per Case'=>$row['Product Units Per Case'],
'Product Currency'=>$row['Product Currency'],
'Product Unit Type'=>$row['Product Unit Type'],

'locale'=>'');
        
        $adata[]=array(
            'key'=>$row['Product Current Key'],
                     'code'=>$code,
                     'id'=>$row['Product ID'],
                     'image'=>$row['Product Main Image'],
                     'type'=>'item',
                     'name'=>$row['Product Name'],
                     'units'=>$row['Product Units Per Case']."x",
                     'formated_price'=>formated_price($price_data),
                     'formated_rrp'=>formated_rrp($rrp_data),
                        'order_qty'=>$row['Order Quantity'],
                     'price'=>money($row['Product Price'],$row['Product Currency'])
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


function list_families() {


    if (isset( $_REQUEST['parent']))
        $parent=$_REQUEST['parent'];
    else
        $parent='none';

    if ($parent=='department') {

        $conf=$_SESSION['state']['department'];
        $conf_table='department';

    } else {

        $conf=$_SESSION['state']['families'];
        $conf_table='families';
    }


    if (isset( $_REQUEST['sf']))
        $start_from=$_REQUEST['sf'];
    else
        $start_from=$conf['table']['sf'];
    //  $start_from=0;
    if (isset( $_REQUEST['nr'])) {
        $number_results=$_REQUEST['nr']-1;

        if ($start_from>0) {
            $page=floor($start_from/$number_results);
            $start_from=$start_from-$page;
        }


    } else
        $number_results=$conf['table']['nr'];


    if (isset( $_REQUEST['o']))
        $order=$_REQUEST['o'];
    else
        $order=$conf['table']['order'];
    if (isset( $_REQUEST['od']))
        $order_dir=$_REQUEST['od'];
    else
        $order_dir=$conf['table']['order_dir'];

    if (isset( $_REQUEST['where']))
        $where=$_REQUEST['where'];
    else
        $where=$conf['table']['where'];

    if (isset( $_REQUEST['f_field']))
        $f_field=$_REQUEST['f_field'];
    else
        $f_field=$conf['table']['f_field'];

    if (isset( $_REQUEST['f_value']))
        $f_value=$_REQUEST['f_value'];
    else
        $f_value=$conf['table']['f_value'];



    if (isset( $_REQUEST['percentages'])) {
        $percentages=$_REQUEST['percentages'];
        $conf['percentages']=$percentages;
    } else
        $percentages=$conf['percentages'];



    if (isset( $_REQUEST['period'])) {
        $period=$_REQUEST['period'];
        $conf['period']=$period;
    } else
        $period=$conf['period'];

    if (isset( $_REQUEST['avg'])) {
        $avg=$_REQUEST['avg'];
        $conf['avg']=$avg;
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

    if (isset( $_REQUEST['restrictions']))
        $restrictions=$_REQUEST['restrictions'];
    else
        $restrictions=$conf['restrictions'];

    $filter_msg='';



    $order_direction=(preg_match('/desc/',$order_dir)?'desc':'');


    $_SESSION['state'][$conf_table]['table']=array('order'=>$order,'order_dir'=>$order_direction,'nr'=>$number_results,'sf'=>$start_from,'where'=>$where,'f_field'=>$f_field,'f_value'=>$f_value
            ,'mode'=>$mode,'restrictions'=>'','parent'=>$parent
                                                  );

    $_SESSION['state'][$conf_table]['period']=$period;
    $_SESSION['state'][$conf_table]['mode']=$mode;
    $_SESSION['state'][$conf_table]['restrictions']=$restrictions;
    $_SESSION['state'][$conf_table]['parent']=$parent;



    //  $where.=" and `Product Department Key`=".$id;
    switch ($parent) {
    case('store'):
        $where=sprintf(' where `Product Family Store Key`=%d',$_SESSION['state']['store']['id']);
        break;
    case('department'):
        $where=sprintf(' where `Product Family Main Department Key`=%d',$_SESSION['state']['department']['id']);
        break;
    default:
        $where=sprintf(' where true ');

    }

    switch ($restrictions) {
    case('for_sale'):
        $where.=sprintf(' and `Product Family Sales Type`="Public Sale" and `Product Family Record Type` in ("New","Normal","Discontinuing") ');
        break;
    case('for_sale_and_discontinued'):
        $where.=sprintf(' and `Product Family Sales Type`="Public Sale" and `Product Family Record Type`!="In Process" ');
        break;
    case('discontinued'):
        $where.=sprintf(' and `Product Family Sales Type`="Public Sale" and `Product Family Record Type`="Discontinued"  ');
        break;
    default:
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
    //  print "$sql";
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


    if ($total<=$number_results) {


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
                                      'records_offset'=>$start_from+1,
                                      'records_perpage'=>$number_results,
                                     )
                   );

    echo json_encode($response);

}




?>