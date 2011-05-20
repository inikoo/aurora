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
require_once 'common_import.php';

if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {
case('insert_data'):
insert_data();
break;
case('change_option'):
    $data=prepare_values($_REQUEST,array(
                             'key'=>array('type'=>'numeric'),
                             'option_key'=>array('type'=>'numeric')
                         ));
    change_option($data);
    break;
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
function read_record($data) {
    unset($_SESSION['state']['import']['records_ignored_by_user'][$data['index']]);
    $response=array('state'=>200,'index'=>$data['index']);
    echo json_encode($response);
}

function change_option($data) {
    $_SESSION['state']['import']['map'][$data['key']]=$data['option_key'];
}

function ignore_record($data) {
    $_SESSION['state']['import']['records_ignored_by_user'][$data['index']]=1;
    $response=array('state'=>200,'index'=>$data['index']);
    echo json_encode($response);
}

function get_record_data($data) {
    $index=$data['index'];

    $records_ignored_by_user = $_SESSION['state']['import']['records_ignored_by_user'];

    require_once 'csvparser.php';
    $csv = new CSV_PARSER;

    if (isset($_SESSION['state']['import']['file_path'])) {
        $csv->load($_SESSION['state']['import']['file_path']);
    }

    //extracting the HEADERS
    $headers = $csv->getHeaders();
    $number_of_records = $csv->countRows();
    $ignore_record = array_key_exists($index,$records_ignored_by_user);
    $raw = $csv->getrawArray();

   // $options=$_SESSION['state']['import']['options'];
    

    $result="<table class='recordList' border=0>
            <tr>
            <th class='list-column-left' style='text-align: left; width: 300px;'>"._('Assigned Field')."</th>
            <th class='list-column-left' style='text-align: left; width: 300px;'>"._('Record').' '.$index.' '._('of').' '.$number_of_records.' <span id="ignore_record_label" style="color:red;'.($ignore_record?'':'display:none').'">('._('Ignored').')</th>'."
            <th style='width:100px'>";
    $result.="<span style='cursor:pointer;".($index > 0?'':'visibility:hidden')."' class='subtext' id='prev' onclick='get_record_data(".($index-1).")'>"._('Previous')."</span>";

    $result.="<span class='subtext' style=".($index > 0?'':'visibility:hidden')."> | </span>";
    $result.="<span  style='cursor:pointer;".($index < $number_of_records?'':'visibility:hidden')."'  class='subtext' id='next' onclick='get_record_data(".($index+1).")'>"._('Next')."</span>";
    $result.="</th><th style='width:100px'>";
    $result.=sprintf('<span style="cursor:pointer;%s" onclick="ignore_record(%d)" id="ignore" class="subtext">%s</span>',(!$ignore_record?'':'display:none'),$index,_('Ignore Record'));
    $result.=sprintf('<span style="cursor:pointer;%s" onclick="read_record(%d)" id="unignore" class="subtext">%s</span>',($ignore_record?'':'display:none'),$index,_('Read Record'));
    $result.='</th></tr>';


    foreach($headers as $key=>$value) {

        $select='<select onChange="option_changed(this.options[this.selectedIndex].value,this.selectedIndex)">';
        
        foreach($_SESSION['state']['import']['options_labels'] as $option_key=>$option_label) {

            $selected='';
            if ($_SESSION['state']['import']['map'][$key]==$option_key)
                $selected='selected="selected"';

            $select.=sprintf('<option %s value="%d"  >%s</option>',$selected,$key,$option_label);
            
        }
        $select.='</select>';

        $result.=sprintf('<tr style="height:20px;border-top:1px solid #ccc"><td>%s</td><td colspan="3">%s</td></tr>',$select,$raw[$index][$key]);
    }


    $result.='</table>';

    $response=array('state'=>200,'result'=>$result);
    echo json_encode($response);
    exit;

}


function insert_data(){


switch($_SESSION['state']['import']['map']['scope']){
case('customers_store'):
insert_customers();
}

function insert_customers(){
    $records_ignored_by_user = $_SESSION['state']['import']['records_ignored_by_user'];
    require_once 'csvparser.php';
    $csv = new CSV_PARSER;
    if (isset($_SESSION['state']['import']['file_path'])) {
        $csv->load($_SESSION['state']['import']['file_path']);
    }
    $headers = $csv->getHeaders();
    $number_of_records = $csv->countRows();
    $ignore_record = array_key_exists($index,$records_ignored_by_user);
    $raw = $csv->getrawArray();

}


}





?>
