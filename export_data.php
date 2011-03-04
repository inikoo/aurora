<?php
/*
File: export_data.php

Data for export process

About:
Autor: Raul Perusquia <rulovico@gmail.com>

Copyright (c) 2010, Kaktus

Version 2.0
*/
/*ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT|E_NOTICE);*/
include_once('common.php');
include_once('class.Customer.php');
if(!$user->can_view('customers')){
  exit();
}

### To check whether the form has proper parameters in query string ###
if(!isset($_REQUEST['subject_key'])){
	header('Location: customers_server.php');
	exit;
}
if(!isset($_REQUEST['subject'])){
	header('Location: customers_server.php');
	exit;
}
$map_type = mysql_real_escape_string($_REQUEST['subject']);
if($map_type == 'customer' || $map_type == 'customers' || $map_type == 'customers_static_list' || $map_type == 'customers_dynamic_list' || $map_type == 'Customer'){
	$map_db_type = 'Customer';
}
$line = ''; $data = '';$header = '';
$my_exported_data=array();
$exported_data = array();//This will be the final array of selected and sorted fields - Now assigning as an empty array//

## FOR CUSTOMER - Individual ##
if($map_type == 'customer'){
	if(isset($_REQUEST['subject_key']) and is_numeric($_REQUEST['subject_key']) ){
	  $_SESSION['state']['customer']['id']=mysql_real_escape_string($_REQUEST['subject_key']);
	  $customer_id=mysql_real_escape_string($_REQUEST['subject_key']);
	}else{
	  $customer_id=$_SESSION['state']['customer']['id'];
	}
	$customer=new customer($customer_id);
}
## FOR CUSTOMERS - of a Store ##
elseif($map_type == 'customers'){
	if(isset($_REQUEST['subject_key']) and is_numeric($_REQUEST['subject_key'])){
	    $store_id=mysql_real_escape_string($_REQUEST['subject_key']);
	}
}
## FOR CUSTOMERS STATIC LIST ##
elseif($map_type == 'customers_static_list'){
	if(isset($_REQUEST['subject_key']) and is_numeric($_REQUEST['subject_key'])){
	    $static_list_id=mysql_real_escape_string($_REQUEST['subject_key']);
	}
}
## FOR CUSTOMERS DYNAMIC LIST ##
elseif($map_type == 'customers_dynamic_list'){
	if(isset($_REQUEST['subject_key']) and is_numeric($_REQUEST['subject_key'])){
	    $dynamic_list_id=mysql_real_escape_string($_REQUEST['subject_key']);
	}
}
/*## IF NO PROPER DEFINATION FOUND ##
else{
	header('Location: customers_server.php');
	exit;
}*/


### Load from saved maps ... Case: "Export Data (using last map)" & "Export from another map" ###
if(isset($_GET['source']) && $_GET['source'] =='db'){
$no_of_maps_saved = numExportMapData($map_db_type);
	## If maps exist in database ##
	if($no_of_maps_saved > 0){
	$exported_data = getExportMapData($map_db_type);
	//print_r(exported_data);
	}
	## If no map exists then assign "Default Export Fields" ##
	else{
		# Fields to be included in default export #
		if($map_db_type == 'Customer'){
		$included_data[0] = 'Customer Main Contact Name';
		$included_data[1] = 'Customer Main Plain Email';
		$included_data[2] = 'Customer Main Plain Telephone';
		//print_r($included_data);
		$exported_data = exportDefaultMap($included_data, 'Customer Dimension');
		//print_r($my_exported_data);
		}
	}
}
### Map is created and exported - Case: Export Wizard (new map)###
else{
	## To ensure whether the form is properly submitted ##
	if(!isset($_POST['SUBMIT'])){
	header('Location: index.php');
	exit;
	}
	## Catching values from session [processing through Wizard] ##
	$my_exported_data = $_SESSION['list'];
	if($map_type == 'customer'){
		$exported_data[]=$my_exported_data;
		//print_r($exported_data);
	}
	elseif($map_type == 'customers'){
		$exported_data = fetch_records_from_key($my_exported_data, 'Customer Dimension', 'Customer Store Key', $store_id);
		//print_r($exported_data);
	}
	elseif($map_type == 'customers_static_list'){
		$exported_data = fetch_records_from_static_list($my_exported_data, $static_list_id);
		//print_r($exported_data);
	}
	elseif($map_type == 'customers_dynamic_list'){
		$exported_data = fetch_records_from_dynamic_list($my_exported_data, $dynamic_list_id);
		//print_r($exported_data);
	}

	## Saving Map into Database ##
	if(isset($_POST['save']) && $_POST['save']=='save'){
		if(isset($_REQUEST['default']) && mysql_real_escape_string($_REQUEST['default']) == 'yes'){
			$default='yes';
		}else{
			$default='no';
		}
		$map_name = mysql_real_escape_string($_POST['map_name']) ;
		$map_desc = mysql_real_escape_string($_POST['map_desc']) ;
		if(isset($_POST['header']) && $_POST['header']=='header'){
			$map_header = 'yes';
		}else{
			$map_header = 'no';
		}
		$map_data = base64_encode(serialize($exported_data));
		$sql = "INSERT INTO `Export Map` (`Map Name` , `Map Description` , `Map Type` ,`Map Data` , `Export Header` , `Export Map Default` , `Exported Date`)
		VALUES ('$map_name', '$map_desc', '$map_db_type', '$map_data', '$map_header', '$default' , now())";
		$query = mysql_query($sql);
	}
}

### EXPORT PART ===== COMMON CODES FOR BOTH NEW MAP & LOAD MAP FROM DB ###
$header_flag=1;
for($i=0;$i<count($exported_data);$i++){
	foreach($exported_data[$i] as $key=>$value){
		if(!isset($value) || $value == ""){
				$value = ","; // Seperator Value
				if(getExportMapHeader($map_db_type) == 'yes' || (isset($_REQUEST['header']) && $_REQUEST['header']=='header')){
					if($header_flag==1){
						$header .= $key.",";
					}
				}
			}else{
				$value = str_replace('"', '""', $value);
				$value = $value.",";
				if(getExportMapHeader($map_db_type) == 'yes' || (isset($_REQUEST['header']) && $_REQUEST['header']=='header')){
					if($header_flag==1){
						$header .= $key.",";
					}
				}
			}
		$line .= $value;

	}
$header_flag++;
$data .= trim($line)."\n";
$line = '';
}

### Unseting unnecessary variables ###
unset($my_exported_data);
unset($exported_data);

### Processing Export file ###
$data = str_replace("\r", "", $data);

if ($data == "") {
  $data = "no matching records found";
}
$filename = mt_rand(11111,99999).'-'.time().'.csv'; // Define the way of your exported file name here //
if(trim($header)!=''){
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
	echo $header."\n".$data;
}else{
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$filename");
	header("Pragma: no-cache");
	header("Expires: 0");
	echo $header.$data;
}

### USER DEFINED FUNCTIONS ###
// COMMON USED //
function getExportMapData($subject){
	if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
		$id=mysql_real_escape_string($_REQUEST['id']);
		$s="SELECT `Map Data` FROM `Export Map` WHERE `Map Type` = '$subject' AND `Map Key` = '$id'";

	}else{
		$s="SELECT `Map Data` FROM `Export Map` WHERE `Map Type` = '$subject' ORDER BY `Export Map`.`Exported Date` DESC
LIMIT 0 , 1";
	}
	$q = mysql_query($s);
	$r = mysql_fetch_assoc($q);
	$data= unserialize(base64_decode($r['Map Data']));
	return $data;
}
function getExportMapHeader($subject){
	if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
		$id=mysql_real_escape_string($_REQUEST['id']);
		$s="SELECT `Export Header` FROM `Export Map` WHERE `Map Type` = '$subject' AND `Map Key` = '$id'";
	}else{
		$s="SELECT `Export Header` FROM `Export Map` WHERE `Map Type` = '$subject' ORDER BY `Export Map`.`Exported Date` DESC LIMIT 0 , 1";
	}
	$q = mysql_query($s);
	if(mysql_num_rows($q) != 0){
		$r = mysql_fetch_assoc($q);
		$data= $r['Export Header'];
	}else{
		$data = 'yes'; // If header is required in default export then write 'yes' else 'no' //
	}
	return $data;
}
function numExportMapData($subject){
	$q = mysql_query("SELECT `Map Key` FROM `Export Map` WHERE `Map Type` = '$subject'");
	$num = mysql_num_rows($q);
	return $num;
}
function final_array($assoc_arr, $num_arr){
	$final_arr = array();
	foreach($assoc_arr as $assoc_key => $assoc_val){
		if(in_array($assoc_key, $num_arr)){
			$final_arr[$assoc_key]=$assoc_val;
		}
	}
	return $final_arr;
}

// CONDITIONAL USED //
function exportDefaultMap($exported_data, $table_name){
// USED TO GET DEFAULT MAP
	$fields=''; $customers_data=array(); $row=array();
	foreach($exported_data as $key=>$value){
		$fields .= '`'.$value.'`,';
	}
		$fields = substr($fields,0,-1);
	$sql = "SELECT $fields FROM `$table_name`";
	$query=mysql_query($sql);
	while($row=mysql_fetch_assoc($query)){
		$customer_data[]=$row;
	}
	return $customer_data;
}

function fetch_records_from_key($exported_data, $table_name, $look_field, $id){
// USED TO GET RECORDS OF CUSTOMERS

	$fields=''; $customers_data=array(); $row=array();
	foreach($exported_data as $key=>$value){
		$fields .= '`'.$key.'`,';
	}
		$fields = substr($fields,0,-1);
	$sql = "SELECT $fields FROM `$table_name` WHERE `$look_field`='$id'";
	$query=mysql_query($sql);
	while($row=mysql_fetch_assoc($query)){
		$customer_data[]=$row;
	}
	return $customer_data;
}

function fetch_records_from_static_list($exported_data, $static_list_id){
// USED TO GET RECORDS OF A STATIC LIST

	$fields=''; $customers_data=array(); $id=array();
	foreach($exported_data as $key=>$value){
		$fields .= '`'.$key.'`,';
	}
		$fields = substr($fields,0,-1);

	$sql1= "SELECT `Customer Key` FROM `Customer List Customer Bridge` WHERE `Customer List Key` = '$static_list_id'";
	$query1=mysql_query($sql1);
	while($row1=mysql_fetch_assoc($query1)){
		$id[]=$row1['Customer Key'];
	}
	for($i=0;$i<count($id);$i++){
		$sql2 = "SELECT $fields FROM `Customer Dimension` WHERE `Customer Key`='$id[$i]'";
			$query2=mysql_query($sql2);
			while($row2=mysql_fetch_assoc($query2)){
			$customer_data[]=$row2;
		}
	}
	return $customer_data;
}

function fetch_records_from_dynamic_list($exported_data, $dynamic_list_id){
// USED TO GET RECORDS OF A DYNAMIC LIST

	$fields=''; $customers_data=array();
	foreach($exported_data as $key=>$value){
		$fields .= '`'.$key.'`,';
	}
		$fields = substr($fields,0,-1);

	$qry = mysql_query("SELECT `Customer List Metadata`,`Customer List Store Key` FROM `Customer List Dimension` WHERE `Customer List Key` = '$dynamic_list_id'");
	$rows= mysql_fetch_assoc($qry);
	$metadata = $rows['Customer List Metadata'];
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
            $table=' `Order Transaction Fact` OTF left join `Customer Dimension` C on (C.`Customer Key`=OTF.`Customer Key`) left join `Product History Dimension` PHD on (OTF.`Product Key`=PHD.`Product Key`) left join `Product Dimension` P on (P.`Product ID`=PHD.`Product ID`)';
        }
	if ($use_categories){
         $table.='  left join   `Category Bridge` CatB on (C.`Customer Key`=CatB.`Subject Key`)';
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
    $store=$rows['Customer List Store Key'];
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

	for($i=0;$i<count($adata);$i++){
		$sql2 = "SELECT $fields FROM `Customer Dimension` WHERE `Customer Key`='$adata[$i]'";
			$query2=mysql_query($sql2);
			while($row2=mysql_fetch_assoc($query2)){
			$customer_data[]=$row2;
		}
	}
	return $customer_data;
}


?>
