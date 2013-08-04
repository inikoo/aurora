<?php
/*

  About: 
  Autor: Migara Ekanayake
 
  Copyright (c) 2011, Inikoo 
 
  Version 2.0
*/

include_once('common.php');

if(!$user->can_view('contacts'))
  exit();

$modify=$user->can_edit('contacts');
$create=$user->can_create('contacts');

if(!$modify or!$create){
  exit();
}



$store_key=$_SESSION['state']['customers']['store'];
$smarty->assign('store_key',$store_key);
$store=new Store($store_key);
$smarty->assign('store',$store);



$smarty->assign('store_key',$store_key);
$smarty->assign('scope','customer');


$general_options_list=array();


$general_options_list[]=array('tipo'=>'url','url'=>'customers.php','label'=>_('Go Back'));

$smarty->assign('general_options_list',$general_options_list);


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
               'css/table.css'

		
		 );
$css_files[]='theme.css.php';
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
              'js/common.js',
              'js/table_common.js',
          
              'js/edit_common.js',
            'config.js.php'


	
		);

$splinters=array(
               'create_superuser'=>array('title'=>_('Create Superuser')),
               'new_company'=>array('title'=>_('Add Company')),
               'new_store'=>array('title'=>_('Add Store'))
           );

//print_r($splinters);		   
//$_SESSION['state']['home']['display'] = 'create_superuser';
$smarty->assign('splinters',$splinters);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('box_layout','yui-t0');
$smarty->assign('parent','customers');
$smarty->assign('display_block',$_SESSION['state']['home']['display']);
$smarty->assign('title','Creating New Customer');
$smarty->display('config.tpl');




?>

