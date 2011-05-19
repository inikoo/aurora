<?php
/*
 File: ar_assets.php

 Ajax Server Anchor for the Product,Family,Department and Part Clases

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once('common.php');
require_once 'ar_edit_common.php';

if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('get_record_data'):


 $data=prepare_values($_REQUEST,array(
    'index'=>array('type'=>'numeric'),
     'scope'=>array('type'=>'string')
    ));
get_record_data($data);
break;
case('ignore_record'):
 $data=prepare_values($_REQUEST,array('index'=>array('type'=>'numeric')));
ignore_record($data);
break;
case('read_record'):
$data=prepare_values($_REQUEST,array('index'=>array('type'=>'numeric')));
read_record($data);
break;

default:

    $response=array('state'=>404,'msg'=>_('Operation not found'));
    echo json_encode($response);

}
function read_record($data){
unset($_SESSION['records_ignored_by_user'][$data['index']]);
 $response=array('state'=>200,'index'=>$data['index']);
    echo json_encode($response);
}


function ignore_record($data){
$_SESSION['records_ignored_by_user'][$data['index']]=1;
 $response=array('state'=>200,'index'=>$data['index']);
    echo json_encode($response);
}

function get_record_data($data)
{
$index=$data['index'];

    //-------------------- Used to ve removeResult.php
    
    //if(isset($_REQUEST['records_ignored_by_user']))
    //    $_SESSION['records_ignored_by_user'][$_REQUEST['records_ignored_by_user']]=1; 
    //if(isset($_REQUEST['records_unignored_by_user']))
    //    unset($_SESSION['records_ignored_by_user'][$_REQUEST['records_ignored_by_user']]);
	//$records_ignored_by_user = array();
	$records_ignored_by_user = $_SESSION['records_ignored_by_user'];
//	echo '<span style='color:white;'>@</span>';
//	$_SESSION['getQueryString'][] = $_GET['v'];
//	$result = array_unique($_SESSION['getQueryString']);
//	foreach($result as $key=>$value){
//		echo '<input type='hidden' name='hidden_array[]' value=''.$value.''>';
//	}


    //----------------------
	require_once 'csvparser.php';
	$csv = new CSV_PARSER;

	if(isset($_SESSION['file_path'])){$csv->load($_SESSION['file_path']);}

	//extracting the HEADERS
	$headers = $csv->getHeaders();
	$number_of_records = $csv->countRows();
    $ignore_record = array_key_exists($index,$records_ignored_by_user);
	$raw = $csv->getrawArray();
	
	$options=get_options($data['scope']);
	
	$result="<table class='recordList' border=0>
	<tr>
	<th class='list-column-left' style='text-align: left; width: 400px;'>"._('Assigned Field')."</th>
	<th class='list-column-left' style='text-align: left; width: 200px;'>"._('Record').' '.$index.' '._('of').' '.$number_of_records.' <span id="ignore_record_label" style="color:red;'.($ignore_record?'':'display:none').'">('._('Ignored').')</th>'."
	    <th style='width:100px'>";
	   
	        $result.="<span style='cursor:pointer;".($index > 0?'':'visibility:hidden')."' class='subtext' id='prev' onclick='get_record_data(".($index-1).")'>"._('Previous')."</span>";
	  
	   $result.="<span class='subtext' style=".($index > 0?'':'visibility:hidden')."> | </span>";
	    
	    $result.="<span  style='cursor:pointer;".($index < $number_of_records?'':'visibility:hidden')."'  class='subtext' id='next' onclick='get_record_data(".($index+1).")'>"._('Next')."</span>";
	 
	        $result.="</th><th style='width:100px'>";
	    $result.=sprintf('<span style="cursor:pointer;%s" onclick="ignore_record(%d)" id="ignore" class="subtext">%s</span>',(!$ignore_record?'':'display:none'),$index,_('Ignore Record'));
        $result.=sprintf('<span style="cursor:pointer;%s" onclick="read_record(%d)" id="unignore" class="subtext">%s</span>',($ignore_record?'':'display:none'),$index,_('Read Record'));
 $result.='</th></tr>';
	

foreach($headers as $key=>$value){

$select='<select>';

foreach($options as $option_key=>$option_label){
$select.=sprintf('<option value="%s" onChange="option_changed(%d)">%s</option>',$option_key,$key,$option_label);
}
$select.='</select>';

$result.=sprintf('<tr style="height:20px;border-top:1px solid #ccc"><td>%s</td><td colspan="3">%s</td></tr>',$select,$raw[$index][$key]);
}


$result.='</table>';

 $response=array('state'=>200,'result'=>$result);
    echo json_encode($response);
    exit;

}


function get_options($scope){

	switch($scope){
	
				case('customers_store'):
			
				
				$fields=array(
				'Ignore'=>_('Ignore'),
				    'Customer Company Name'=>_('Company Name'),
				    'Customer Tax Number'=>_('Tax Number'),
				    'Customer Main Contact Name'=>_('Contact Name'),
				    'Customer Main Plain Email'=>_('Email'),
				    'Customer Main Plain Telephone'=>_('Telephone'),
				    'Customer Main Plain Mobile'=>_('Mobile'),
				    'Customer Main Plain FAX'=>_('Fax'),
			            'Customer Address Line 1'=>_('Address Line 1'),
    'Customer Address Line 2'=>_('Address Line 2'),
    'Customer Address Line 3'=>_('Address Line 3'),
      'Customer Address Town Second Division'=>_('Town Second Division'),
     'Customer Address Town First Division'=>_('Town First Division'),
     
    'Customer Address Town'=>_('Town'),
    'Customer Address Postal Code'=>_('Postal Code'),
   
         'Customer Address Country Fifth Division'=>_('Country Fifth Division'),
 'Customer Address Country Forth Division'=>_('Country Forth Division'),
    'Customer Address Country Third Division'=>_('Country Third Division'),
    'Customer Address Country Second Division'=>_('Country Second Division'),
    'Customer Address Country First Division'=>_('Country First Division'),
    'Customer Address Country Name'=>_('Country'),
    'Customer Address Country Code'=>_('Country Code (XXX)'),
      'Customer Address Country 2 Alpha Code'=>_('Country Code (XX'),
   
   
 
			
			
				    );
				break;

				case('supplier_products'):
				$fields=array();
				break;

				case('staff'):
				$fields=array();
				break;

				case('positions'):
				$fields=array();
				break;

				case('areas'):
				$fields=array();
				break;

				case('departments'):
				$fields=array();
				break;

				default:
				$fields=array();
				}

return $fields;

}


?>
