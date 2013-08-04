<?php
/*
 UI external_csv_verify.php page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

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
               'css/common.css',
               'css/container.css',
               'css/button.css',
               'css/table.css',
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
              'external_csv_data.js.php',
              'js/dropdown.js'
          );

$_SESSION['state']['import']['type']=1;
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

               //$target_path = "app_files/uploads/";

                //$target_path = $target_path . basename( $_FILES['fileUpload']['name']);

             
				$sql=sprintf("select `Record` from `External Records` where `Store Key`=%d and `Scope`='%s' and `Read Status`='No'", $scope_key, $scope);
				//print $sql;
				
				$result=mysql_query($sql);
				
                $row = mysql_fetch_array($result);
				
				
				$headers = explode('#', $row[0]);
				//print_r($headers);	
					          
					$map=array();
                    foreach($headers as $header_key=>$header_value) {
                        $map[$header_key]=0;
                    }


                    include_once('class.ImportedRecords.php');

                    $imported_records_data=array(
                                               'Imported Records Checksum File'=>'',
                                               'Imported Records Creation Date'=>date('Y-m-d H:i:s'),
                                               'Imported Records Scope'=>$scope,
                                               'Imported Records Scope Key'=>$scope_key
                                           );
                    $imported_records=new ImportedRecords('find',$imported_records_data,'create');


                    $number_of_records = mysql_num_rows($result);

                    $imported_records->update(array(
                                                  'Original Records'=>$number_of_records
                                                                     ,'Ignored Records'=>0
                                              ));

                    $_SESSION['state']['import']['records_ignored_by_user']=array();
                    $_SESSION['state']['import']['key']=$imported_records->id;
                    $_SESSION['state']['import']['scope']=$scope;
                    $_SESSION['state']['import']['scope_key']=$scope_key;
                    $_SESSION['state']['import']['map']=$map;
                    list($_SESSION['state']['import']['options_db_fields'],$_SESSION['state']['import']['options_labels'])=get_options($scope,$scope_key);
                    $_SESSION['state']['import']['file_path'] = '';

                
            
        
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
$smarty->display('external_csv_verify.tpl');
?>
