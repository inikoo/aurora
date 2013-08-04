<?php
include_once('common.php');

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'editor/assets/skins/sam/editor.css',
               $yui_path.'assets/skins/sam/autocomplete.css',

               'css/text_editor.css',
               'css/common.css',
               'css/button.css',
               'css/container.css',
               'css/table.css',
               'css/customer.css',
                 'css/upload.css',
                                'css/edit.css',
                                'theme.css.php'
           );


$js_files=array(

                $yui_path.'utilities/utilities.js',
                $yui_path.'json/json-min.js',
                $yui_path.'paginator/paginator-min.js',
                $yui_path.'datasource/datasource-min.js',
                $yui_path.'autocomplete/autocomplete-min.js',
                $yui_path.'datatable/datatable.js',
                $yui_path.'container/container-min.js',
                $yui_path.'menu/menu-min.js',
                'js/common.js',
                'js/table_common.js',
                'js/search.js',
                'test_ape.js.php',
		'external_libs/APE_JSF/Build/uncompressed/apeClientJS.js'
                );



$smarty->assign('css_files',$css_files);

$smarty->assign('js_files',$js_files);
$smarty->display('test_ape.tpl');

?>
