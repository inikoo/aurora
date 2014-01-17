<?php
include_once('common.php');
include_once('class.Warehouse.php');


if(isset($_REQUEST['warehouse_id']) and is_numeric($_REQUEST['warehouse_id']) ){
  $warehouse_id=$_REQUEST['warehouse_id'];

}else{
  header('Location: index.php?error_no_warehouse_key_a');
   exit;
}


$warehouse=new warehouse($warehouse_id);
if(!($user->can_view('warehouses') and in_array($warehouse_id,$user->warehouses)   ) ){
  header('Location: index.php');
   exit;
}



$warehouse=new Warehouse($warehouse_id);
$smarty->assign('warehouse',$warehouse);
$smarty->assign('warehouse_id',$warehouse->id);



$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'css/common.css',
               'css/button.css',
               'css/container.css',
               'css/table.css',
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
              $yui_path.'calendar/calendar-min.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
'js/edit_common.js',
              'js/parts_common.js',

'new_parts_list.js.php',
              
          );

$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');

$smarty->assign('parts_view',$_SESSION['state']['warehouse']['parts']['view']);
$smarty->assign('parts_period',$_SESSION['state']['warehouse']['parts']['period']);
$smarty->assign('parts_avg',$_SESSION['state']['warehouse']['parts']['avg']);


$_SESSION['state']['parts']['list']['where']='';
$smarty->assign('parent','parts');
$smarty->assign('title', _('Lists'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$have_options=array(
                  'email'=>array('name'=>_('Email')),
                  'tel'=>array('name'=>_('Telephone')),
                  'fax'=>array('name'=>_('Fax')),
                  'address'=>array('name'=>_('Address')),
              );
$smarty->assign('have_options',$have_options);

$dont_have_options=array(
                       'email'=>array('name'=>_('Email')),
                       'tel'=>array('name'=>_('Telephone')),
                       'fax'=>array('name'=>_('Fax')),
                       'address'=>array('name'=>_('Address')),
                   );
$smarty->assign('dont_have_options',$dont_have_options);

$condition=array(
                       'less'=>array('name'=>_('Less than')),
                       'equal'=>array('name'=>_('Equal')),
                       'more'=>array('name'=>_('More than')),
					   'between'=>array('name'=>_('Between'))
                   );
$smarty->assign('condition',$condition);

$web_state=array(
                       'online_force_out_of_stock'=>array('name'=>_('Online Force Out of Stock')),
                       'online_auto'=>array('name'=>_('Online Auto')),
                       'offline'=>array('name'=>_('Offline')),
					   'unknown'=>array('name'=>_('Unknown')),
					   'online_force_for_sale'=>array('name'=>_('Online Force For Sale'))			   
                   );
$smarty->assign('web_state',$web_state);

$availability_state=array(
                       'optimal'=>array('name'=>_('Optimal')),
                       'low'=>array('name'=>_('Low')),
                       'critical'=>array('name'=>_('Critical')),
					   'surplus'=>array('name'=>_('Surplus')),
					   'out_of_stock'=>array('name'=>_('Out of Stock')),
					   'unknown'=>array('name'=>_('Unknown')),		
					   'no_applicable'=>array('name'=>_('No applicable'))		   
                   );
$smarty->assign('availability_state',$availability_state);


$smarty->assign('view',$_SESSION['state']['customers']['customers']['view']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$tipo_filter1=$_SESSION['state']['world']['wregions']['f_field'];
$filter_menu1=array(
                  'wregion_code'=>array('db_key'=>'wregion_code','menu_label'=>_('World Region Code'),'label'=>_('Region Code')),
                  'continent_code'=>array('db_key'=>'continent_code','menu_label'=>_('Continent Code'),'label'=>_('Continent Code')),
              );
$smarty->assign('filter_name1',$filter_menu1[$tipo_filter1]['label']);
$smarty->assign('filter_menu1',$filter_menu1);
$smarty->assign('filter1',$tipo_filter1);
$smarty->assign('filter_value1',$_SESSION['state']['world']['wregions']['f_value']);



$tipo_filter2=$_SESSION['state']['world']['countries']['f_field'];
$filter_menu2=array(
                  'country_code'=>array('db_key'=>'country_code','menu_label'=>_('Country Code'),'label'=>_('Code')),
                  'wregion_code'=>array('db_key'=>'wregion_code','menu_label'=>_('World Region Code'),'label'=>_('Region Code')),
                  'continent_code'=>array('db_key'=>'continent_code','menu_label'=>_('Continent Code'),'label'=>_('Continent Code')),
              );
$smarty->assign('filter_name2',$filter_menu2[$tipo_filter2]['label']);
$smarty->assign('filter_menu2',$filter_menu2);
$smarty->assign('filter2',$tipo_filter2);
$smarty->assign('filter_value2',$_SESSION['state']['world']['countries']['f_value']);




$tipo_filter=$_SESSION['state']['warehouse']['parts']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['warehouse']['parts']['f_value']);
$filter_menu=array(
		   'sku'=>array('db_key'=>'sku','menu_label'=>_('Part SKU'),'label'=>'SKU'),
		   'used_in'=>array('db_key'=>'used_in','menu_label'=>_('Used in'),'label'=>_('Used in')),

		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);




$elements_number=array('Keeping'=>0,'LastStock'=>0,'Discontinued'=>0,'NotKeeping'=>0);
$sql=sprintf("select count(*) as num ,`Part Main State` from  `Part Dimension` P left join `Part Warehouse Bridge` B on (P.`Part SKU`=B.`Part SKU`)  where B.`Warehouse Key`=%d group by  `Part Main State`   ",
$warehouse->id);
//print_r($sql);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $elements_number[$row['Part Main State']]=$row['num'];
}




$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['warehouse']['parts']['elements']);



$smarty->display('new_parts_list.tpl');
?>
