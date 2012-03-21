<?php
/*
File: export_wizard.php

UI customer page

About:
Autor: Raul Perusquia <rulovico@gmail.com>

Copyright (c) 2009, Inikoo

Version 2.0
*/
/*ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT|E_NOTICE);*/
include_once('common.php');
include_once('class.Customer.php');


$css_files=array(
         $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
         $yui_path.'menu/assets/skins/sam/menu.css',
         $yui_path.'calendar/assets/skins/sam/calendar.css',
         $yui_path.'button/assets/skins/sam/button.css',
         $yui_path.'editor/assets/skins/sam/editor.css',
         $yui_path.'assets/skins/sam/autocomplete.css',
         'text_editor.css',
         'common.css',
         'button.css',
         'css/container.css',
         'table.css',
	 'css/export_wizard.css',
         'css/customer.css'
         );
$js_files=array(
        $yui_path.'utilities/utilities.js',
        $yui_path.'json/json-min.js',
        $yui_path.'paginator/paginator-min.js',
        $yui_path.'datasource/datasource-min.js',
        $yui_path.'autocomplete/autocomplete-min.js',
        $yui_path.'datatable/datatable-min.js',
        $yui_path.'container/container-min.js',
        $yui_path.'editor/editor-min.js',
        $yui_path.'menu/menu-min.js',
        $yui_path.'calendar/calendar-min.js',
        'external_libs/ampie/ampie/swfobject.js',
        'js/common.js',
        'js/table_common.js',
        'js/search.js',
        'js/edit_common.js',
	'js/export_wizard.js',
	'customer.js.php'
        );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
if(!$user->can_view('customers')){
  exit();
}

## To check whether the form has proper parameters in query string ##
if(!$_REQUEST['subject_key']){
	header('Location: customers_server.php');
	exit;
}

if(!$_REQUEST['subject']){
	header('Location: customers_server.php');
	exit;
}
$map_type = $_REQUEST['subject'];

## NOT BEING USED ##
//$arr=array();
//$arr=explode('.',basename($_SERVER['HTTP_REFERER']));
//$map_for=$arr[0];

## FOR CUSTOMER - Individual ##
if($map_type == 'customer'){
	if(isset($_REQUEST['subject_key']) and is_numeric($_REQUEST['subject_key'])){
	  $_SESSION['state']['customer']['id']=$_REQUEST['subject_key'];
	  $customer_id=$_REQUEST['subject_key'];
	}else{
	  $customer_id=$_SESSION['state']['customer']['id'];
	}
	$customer=new customer($customer_id);
	$customer_id = $customer->data['Customer Key'];
	$smarty->assign('subject_key',$customer_id);
	$smarty->assign('prev_path',"customer.php?p=cs&id=$customer_id");
	$smarty->assign('return_path',"customer.php?p=cs&id==$customer_id");
	$list=$customer->data;
}
## FOR CUSTOMERS - of a Store ##
elseif($map_type == 'customers'){
	if(isset($_REQUEST['subject_key']) and is_numeric($_REQUEST['subject_key'])){
	    $store_id=$_REQUEST['subject_key'];
	}
	$qry = mysql_query("SELECT * FROM `Customer Dimension` WHERE `Customer Store Key` = '$store_id' LIMIT 1");
	$list= mysql_fetch_assoc($qry);
	$smarty->assign('subject_key',$store_id);
	$smarty->assign('prev_path',"customers.php?store=$store_id");
	$smarty->assign('return_path',"customers_server.php");
}
## FOR CUSTOMERS STATIC LIST ##
elseif($map_type == 'customers_static_list'){
	if(isset($_REQUEST['subject_key']) and is_numeric($_REQUEST['subject_key'])){
	    $static_list_id=$_REQUEST['subject_key'];
	}

	$qry = mysql_query("SELECT * FROM `Customer Dimension` WHERE `Customer Key` = (SELECT `Customer Key` FROM `List Customer Bridge` WHERE `List Key` = '$static_list_id' LIMIT 1 )");
	$list= mysql_fetch_assoc($qry);
	$smarty->assign('subject_key', $static_list_id);
	$smarty->assign('prev_path',"customer_list_static.php?id=$static_list_id");
	$smarty->assign('return_path',"customers_lists.php");
}
## FOR CUSTOMERS DYNAMIC LIST ##
elseif($map_type == 'customers_dynamic_list'){
	if(isset($_REQUEST['subject_key']) and is_numeric($_REQUEST['subject_key'])){
	    $dynamic_list_id=$_REQUEST['subject_key'];
	}
	$qry = mysql_query("SELECT `List Metadata`,`List Parent Key` FROM `List Dimension` WHERE `List Key` = '$dynamic_list_id'");
	$rows= mysql_fetch_assoc($qry);
	$metadata = $rows['List Metadata'];
	$table='`Customer Dimension` C ';
	if ($metadata) {
        $metadata=preg_replace('/\\\"/','"',$metadata);
        $where_data=array(
        'product_ordered1'=>'∀',
        'product_not_ordered1'=>'',
        'product_not_received1'=>'',
        'from1'=>'',
        'to1'=>'',
        'dont_have'=>array(),
        'have'=>array(),
        'categories'=>''
        );       
        $metadata=json_decode($metadata,TRUE);
        foreach ($metadata as $key=>$item){
            $where_data[$key]=$item;
        }
        $where='where true';
	$use_categories =false;
        $use_otf =false;
        $where_categories='';
        if ($where_data['categories']!='') {
        $categories_keys=preg_split('/,/',$where_data['categories']);
        $valid_categories_keys=array();
        foreach ($categories_keys as $item) {
            if(is_numeric($item))
                $valid_categories_keys[]=$item;
        }
        $categories_keys=join($valid_categories_keys,',');
        if($categories_keys){
        $use_categories =true;
        $where_categories=sprintf(" and `Subject`='Customer' and `Category Key` in (%s)",$categories_keys);
        }
        }
        if ($where_data['product_ordered1']!=''){
            if ($where_data['product_ordered1']!='∀'){
                $use_otf=true;
                $where_product_ordered1=extract_product_groups($where_data['product_ordered1']);
            }else
                $where_product_ordered1='true';
        }else{
            $where_product_ordered1='false';
        }       
        if ($where_data['product_not_ordered1']!='') {
            if ($where_data['product_not_ordered1']!='ALL') {
                $use_otf=true;
                $where_product_not_ordered1=extract_product_groups($where_data['product_ordered1'],'P.`Product Code` not like','transaction.product_id not like','F.`Product Family Code` not like','P.`Product Family Key` like');
            } else
                $where_product_not_ordered1='false';
        } else
            $where_product_not_ordered1='true';

        if ($where_data['product_not_received1']!='') {
            if ($where_data['product_not_received1']!='∀') {
                $use_otf=true;
                $where_product_not_received1=extract_product_groups($where_data['product_ordered1'],'(ordered-dispatched)>0 and    product.code  like','(ordered-dispatched)>0 and  transaction.product_id not like','(ordered-dispatched)>0 and  product_group.name not like','(ordered-dispatched)>0 and  product_group.id like');
            } else {
                $use_otf=true;
                $where_product_not_received1=' ((ordered-dispatched)>0)  ';
            }
        } else
            $where_product_not_received1='true';
        $date_interval1=prepare_mysql_dates($where_data['from1'],$where_data['to1'],'`Invoice Date`','only_dates');
        if ($date_interval1['mysql']) {
            $use_otf=true;
        }      
        if ($use_otf) {
            $table=' `Order Transaction Fact` OTF left join `Customer Dimension` C on (C.`Customer Key`=OTF.`Customer Key`) left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PHD.`Product ID`)  ';
        }       
     if ($use_categories){
         $table.='  left join   `Category Bridge` CatB on (C.`Customer Key`=CatB.`Subject Key`)   ';
        }
        $where='where ('.$where_product_ordered1.' and '.$where_product_not_ordered1.' and '.$where_product_not_received1.$date_interval1['mysql'].") ".$where_categories;

        foreach($where_data['dont_have'] as $dont_have) {
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
        foreach($where_data['have'] as $dont_have) {
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
    $store=$rows['List Parent Key'];
    $currency='';
    if (is_numeric($store)) {
        $where.=sprintf(' and `Customer Store Key`=%d ',$store);
        $store=new Store($store);
        $currency=$store->data['Store Currency Code'];
    }
        $order='`Customer File As`';
    $sql="select   *,`Customer Net Refunds`+`Customer Tax Refunds` as `Customer Total Refunds` from  $table   $where $wheref  order by $order DESC ";
    $adata=array();
    $result=mysql_query($sql);
    while ($data=mysql_fetch_array($result, MYSQL_ASSOC)) {
        $id=$data['Customer Key'];
        array_push($adata, $id);
        }
        $qry2 = mysql_query("SELECT * FROM `Customer Dimension` WHERE `Customer Key` = '$adata[0]'");
	$list= mysql_fetch_assoc($qry2);

	$smarty->assign('subject_key', $dynamic_list_id);
	$smarty->assign('prev_path',"customer_list_dynamic.php?id=$dynamic_list_id");
	$smarty->assign('return_path',"customers_lists.php");
}
## IF NO PROPER DEFINATION FOUND ##
else{
	header('Location: customers_server.php');
	exit;
}

## WORKING WITH DATA AND DISPLAYING IN TEMPLATE ##

if(isset($_SESSION['list'])){
	unset($_SESSION['list']);
}
//print_r($list);
$smarty->assign('subject',$map_type);
$smarty->assign('param',count($list)-1);
$smarty->assign('list',$list);
$smarty->display('export_wizard.tpl');
?>
