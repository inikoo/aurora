<?php
/*
 File: insert_csv.php

 UI store page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
/*ini_set('display_errors',1);
error_reporting(E_ALL|E_STRICT|E_NOTICE);*/
include_once('common.php');
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'assets/skins/sam/autocomplete.css',
		 // $yui_path.'assets/skins/sam/autocomplete.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css',
		 'css/dropdown.css',
		 'css/import_data.css'
		 );
$js_files=array(
		$yui_path.'utilities/utilities.js',
		$yui_path.'json/json-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'dragdrop/dragdrop-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'uploader/uploader-debug.js',
		'js/php.default.min.js',
		'js/common.js',
		'js/table_common.js',
		'js/dropdown.js',
		'js/insert_csv.js'
        	);
        	
        	
        	


$smarty->assign('js_files',$js_files);
$smarty->assign('css_files',$css_files);

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

    foreach($raw as $record_key=>$record_data){
        if(array_key_exists($record_key,$records_ignored_by_user))
            continue;
        $parsed_record_data=array();    
        
        foreach($record_data as $field_key=>$field){
        $mapped_field_key=$map[$field_key];
       
            if($mapped_field_key)
            $parsed_record_data[$_SESSION['state']['import']['options_db_fields'][$mapped_field_key]]=$field;
        }
         $data_to_import[]=$parsed_record_data;
    }

print_r($data_to_import);


exit;

$smarty->display('insert_csv.tpl');
unset($_SESSION['getQueryString']);


?>
