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

case('import_customer_csv_status'):
    import_customer_csv_status();
    break;
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

    $options=$_SESSION['state']['import']['todo']=$number_of_records+1;


    $result="<table class='recordList' border=0>
            <tr>
            <th class='list-column-left' style='text-align: left; width: 300px;'>"._('Assigned Field')."</th>
            <th class='list-column-left' style='text-align: left; width: 300px;'>"._('Record').' '.($index+1).' '._('of').' '.($number_of_records+1).' <span id="ignore_record_label" style="color:red;'.($ignore_record?'':'display:none').'">('._('Ignored').')</th>'."
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


function insert_data() {


    switch ($_SESSION['state']['import']['scope']) {
    case('customers_store'):
        insert_customers_from_csv();
    }
}










function insert_customers_from_csv() {
    global $editor;

    $error_log_file_name='customers_'.date('U');
    $fp = fopen("app_files/import_errors/$error_log_file_name.csv", 'w');


//    if ($_SESSION['state']['import']['in_progress'])
//       return;
    include_once('class.Customer.php');
    include_once('edit_customers_functions.php');

    $_SESSION['state']['import']['in_progress']=1;
    $_SESSION['state']['import']['error_file']=false;
    $store_key=$_SESSION['state']['import']['scope_key'];
    $customer_list_key=0;


    $records_ignored_by_user = $_SESSION['state']['import']['records_ignored_by_user'];
    $map = $_SESSION['state']['import']['map'];
//   $options = $_SESSION['state']['import']['options'];
    require_once 'csvparser.php';
    $csv = new CSV_PARSER;
    if (isset($_SESSION['state']['import']['file_path'])) {
        $csv->load($_SESSION['state']['import']['file_path']);
    }
    $headers = $csv->getHeaders();
    $number_of_records = $csv->countRows();

    $data_to_import=array();

    $raw = $csv->getrawArray();





    foreach($raw as $record_key=>$record_data) {
        if (array_key_exists($record_key,$records_ignored_by_user)) {
            $record_data[]='Ignored';
            //print_r($record_data);
            fputcsv($fp, $record_data);
            $_SESSION['state']['import']['ignored']++;
            continue;

        }


        $parsed_record_data=array('csv_key'=>$record_key);
        foreach($record_data as $field_key=>$field) {
            //$field['csv_key']=$field_key;
            $mapped_field_key=$map[$field_key];

            if ($mapped_field_key)
                $parsed_record_data[$_SESSION['state']['import']['options_db_fields'][$mapped_field_key]]=$field;
        }
        $data_to_import[]=$parsed_record_data;
    }

    $_SESSION['state']['import']['todo']=count($data_to_import);
//print_r($data_to_import);
//print_r($_SESSION['state']['import'][]);

   

    foreach($data_to_import as $_customer_data) {


 $customer_data=array(
                       'Customer Company Name'=>'',
                       'Customer Main Contact Name'=>'',
                       'Customer Type'=>'',
                       'Customer Store Key'=>$store_key,
                       'Customer Address Line 1'=>'',
                       'Customer Address Line 2'=>'',
                       'Customer Address Line 3'=>'',
                       'Customer Address Postal Code'=>'',
                       'Customer Address Country Name'=>'',
                       'Customer Address Country Code'=>'',
                       'Customer Address Country 2 Alpha Code'=>'',
                       'Customer Address Country First Division'=>'',
                       'Customer Address Country Second Division'=>'',
                       'editor'=>$editor
                   );


//print_r($_customer_data);
        foreach($_customer_data as $key=>$value) {
            $customer_data[$key]=$value;
        }


        if ($customer_data['Customer Main Contact Name']=='' and $customer_data['Customer Company Name']=='') {
            $_SESSION['state']['import']['errors']++;
            $_SESSION['state']['import']['todo']--;

            continue;
        }

//        print_r($customer_data);
        if (  !( $customer_data['Customer Type']=='Person' or  $customer_data['Customer Type']=='Company')    ) {
            list($customer_data['Customer Type'] ,$customer_data['Customer Company Name'],$customer_data['Customer Main Contact Name'])=parse_company_person($customer_data['Customer Company Name'],$customer_data['Customer Main Contact Name']);
        }

        if ($customer_data['Customer Type']=='Company')
            $customer_data['Customer Name']=$customer_data['Customer Company Name'];
        else
            $customer_data['Customer Name']=$customer_data['Customer Main Contact Name'];




        if ($customer_data['Customer Address Country 2 Alpha Code']!='') {
            $country=new Country('2alpha',$customer_data['Customer Address Country 2 Alpha Code']);
            $customer_data['Customer Address Country Code']=$country->data['Country Code'];
            unset($country);
        }
        elseif($customer_data['Customer Address Country Code']!='') {
            $country=new Country('code',$customer_data['Customer Address Country Code']);
            $customer_data['Customer Address Country Code']=$country->data['Country Code'];
            unset($country);
        }
        elseif($customer_data['Customer Address Country Name']!='') {
            $country=new Country('code',$customer_data['Customer Address Country Name']);
            $customer_data['Customer Address Country Code']=$country->data['Country Code'];
            unset($country);
        }
        else {
            $customer_data['Customer Address Country Code']='UNK';
        }

//exit;



        $customer=new Customer('find complete',$customer_data);

        // print_r($customer_data);

//print_r($customer);
        if (!$customer->found) {




            $response=add_customer($customer_data) ;
            //   print_r($response);


            if ($response['state']==200 and $response['action']=='created') {

                if (!$customer_list_key) {
                    $customer_list_key=new_imported_csv_customers_list($store_key);



                }

                $sql=sprintf("insert into `Customer List Customer Bridge` (`Customer List Key`,`Customer Key`) values (%d,%d)",
                             $customer_list_key,
                             $response['customer_key']
                            );
                mysql_query($sql);

                if ($_SESSION['state']['import']['done_comments']=='') {
                    $_SESSION['state']['import']['done_comments']=sprintf("<a href='customers_list.php?id=%d'>%s</a>",
                            $customer_list_key,
                            _('Imported customers list')
                                                                         );
                }

                $_SESSION['state']['import']['done']++;
                $_SESSION['state']['import']['todo']--;

            } else {
                $_SESSION['state']['import']['errors']++;
                $_SESSION['state']['import']['todo']--;
            }


        } else {

            $_record_data=$csv->getRow($_customer_data['csv_key']);
            $_record_data[]='Already in DB';;
            //print_r($record_data);
  n          fputcsv($fp, $_record_data);

            $_SESSION['state']['import']['errors']++;
            $_SESSION['state']['import']['todo']--;
        }
        unset($customer);
//exit;
    }

    fclose($fp);

}

function import_customer_csv_status() {



    $data=array(
              'todo'=>array('number'=>$_SESSION['state']['import']['todo'],'comments'=>$_SESSION['state']['import']['todo_comments']),
              'done'=>array('number'=>$_SESSION['state']['import']['done'],'comments'=>$_SESSION['state']['import']['done_comments']),
              'error'=>array('number'=>$_SESSION['state']['import']['errors'],'comments'=>$_SESSION['state']['import']['errors_comments']),
              'ignored'=>array('number'=>$_SESSION['state']['import']['ignored'],'comments'=>$_SESSION['state']['import']['ignored_comments'])

          );
    $response= array('state'=>200,'data'=>$data);
    echo json_encode($response);
}





?>
