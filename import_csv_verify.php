<?php
/*
 UI import_csv_verify.php page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/

include_once('common.php');
include_once('common_import.php');

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
              'import_csv_data.js.php',
              'js/dropdown.js'
          );


$_SESSION['state']['import']['records_ignored_by_user']=array();

$general_options_list[]=array('class'=>'edit','tipo'=>'js','id'=>'new_map','label'=>_('Add'));
$general_options_list[]=array('tipo'=>'js','id'=>'browse_maps','label'=>_('Pick a Map'));

$smarty->assign('general_options_list',$general_options_list);

if (!isset($_REQUEST['subject'])) {
    exit("to do a page where the user can choose the correct options");
}
if (!isset($_REQUEST['subject_key'])) {
    if ($_REQUEST['subject']!='staff' && $_REQUEST['subject']!='positions' && $_REQUEST['subject']!='areas' && $_REQUEST['subject']!='departments')
        exit("to do a page where the user can choose the correct options");
}
$scope=$_REQUEST['subject'];
$scope_key=$_REQUEST['subject_key'];


if (isset($_POST['submit'])) {
    if ($_FILES['fileUpload']['name']=='') {
        header("location:import_csv.php?subject=$scope&subject_key=$scope_args");
    }
    $filesize = '2097152'; // in bytes eqv. to 2MB

    if (($_FILES["fileUpload"]["size"]) >= $filesize) {
        $_SESSION['state']['import']['error'] = 'Uploading Error : too large file to upload';
        header("location:import_csv.php?subject=$scope&subject_key=$scope_args");
        exit();
    } else {
        if (  ($_FILES["fileUpload"]["type"] == "text/plain") || ($_FILES["fileUpload"]["type"] == "text/csv")  || ($_FILES["fileUpload"]["type"] == "application/csv")   || ($_FILES["fileUpload"]["type"] == "application/octet-stream")         ) {
            if ($_FILES["fileUpload"]["error"] > 0) {
                echo "Error: " . $_FILES["fileUpload"]["error"] . "<br />";
            } else {
                $target_path = "app_files/uploads/";

                $target_path = $target_path . basename( $_FILES['fileUpload']['name']);

                if (move_uploaded_file($_FILES['fileUpload']['tmp_name'], $target_path)) {
                    $vv=basename( $_FILES['fileUpload']['name']);
                    require_once 'csvparser.php';
                    $csv = new CSV_PARSER;
                    //loading the CSV File
                    $csv->load($target_path);

                    $headers = $csv->getHeaders();
					
					
					          
					$map=array();
                    foreach($headers as $header_key=>$header_value) {
                        $map[$header_key]=0;
                    }


                    include_once('class.ImportedRecords.php');

                    $imported_records_data=array(
                                               'Imported Records Checksum File'=>md5_file($target_path),
                                               'Imported Records Creation Date'=>date('Y-m-d H:i:s'),
                                               'Imported Records Scope'=>$scope,
                                               'Imported Records Scope Key'=>$scope_key
                                           );
                    $imported_records=new ImportedRecords('find',$imported_records_data,'create');


                    $number_of_records = $csv->countRows()+1;

                    $imported_records->update(array(
                                                  'Original Records'=>$number_of_records
                                                                     ,'Ignored Records'=>0
                                              ));

                    //$_SESSION['state']['import']=array();
                    $_SESSION['state']['import']['records_ignored_by_user']=array();
                    $_SESSION['state']['import']['key']=$imported_records->id;
                    $_SESSION['state']['import']['scope']=$scope;
                    $_SESSION['state']['import']['scope_key']=$scope_key;
                    $_SESSION['state']['import']['map']=$map;
					//print_r($map);
                    list($_SESSION['state']['import']['options_db_fields'],$_SESSION['state']['import']['options_labels'])=get_options($scope,$scope_key);
                    $_SESSION['state']['import']['file_path'] = $target_path;
                    //$r = $csv->connect();
                }
            }
        } else {
        
            header("location:import_csv.php?subject=$scope&subject_key=$scope_args&error=Invalid File Type ".$_FILES["fileUpload"]["type"]);
        }
    }
}

if (isset($_SESSION['state']['import']['error'])) {
    $smarty->assign('showerror',$_SESSION['state']['import']['error']);
}

$index = 0;

$smarty->assign('index',$index);
$smarty->assign('scope',$scope);


//$smarty->assign('subject',$scope);
$smarty->assign('scope_key',$scope_key);
$smarty->assign('js_files',$js_files);
$smarty->assign('css_files',$css_files);
$smarty->display('import_csv_verify.tpl');
?>
