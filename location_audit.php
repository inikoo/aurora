<?php

    
    include_once('common.php');
    include_once('common_import.php');
    
    $css_files=array(
                     $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
                     $yui_path.'menu/assets/skins/sam/menu.css',
                     $yui_path.'button/assets/skins/sam/button.css',
                     $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
                     'common.css',
                     'css/container.css',
                     'button.css',
                     'table.css',
                     //         'css/import_data.css',
                     'theme.css.php'
                     
                     
                     
                     
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
                    'js/search.js',
                    'js/table_common.js',
                    'location_audit.js.php',
                    
                    );
    
    
    if (isset($_POST['submit'])) {
        if ($_FILES['fileUpload']['name']=='') {
            header("location:warehouse.php");
        }
        $filesize = '2097152'; // in bytes eqv. to 2MB
        
        if (($_FILES["fileUpload"]["size"]) >= $filesize) {
            $_SESSION['state']['import']['error'] = 'Uploading Error : too large file to upload';
            header("location:warehouse.php");
            exit();
        } else {
            if (  ($_FILES["fileUpload"]["type"] == "text/plain")|| ($_FILES["fileUpload"]["type"] == "application/vnd.ms-excel")   || ($_FILES["fileUpload"]["type"] == "text/csv")  || ($_FILES["fileUpload"]["type"] == "application/csv")   || ($_FILES["fileUpload"]["type"] == "application/octet-stream")         ) {
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
                        print_r($headers);exit;

                        

                    }
                }
            } else {
                
                //header("location:import_csv.php?subject=$scope&subject_key=$scope_args&error=Invalid File Type ".$_FILES["fileUpload"]["type"]);
            }
        }
    }

    $smarty->assign('js_files',$js_files);
    $smarty->assign('css_files',$css_files);
    $smarty->display('location_audit.tpl');
    ?>
