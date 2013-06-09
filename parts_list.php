<?php
include_once('common.php');
include_once('class.Warehouse.php');
if (!$user->can_view('customers') ) {
    header('Location: index.php');
    exit;
}
//$modify=$user->can_edit('staff');
$general_options_list=array();

if (isset($_REQUEST['id']))
    $id=$_REQUEST['id'];
else {
    header('Location: index.php?error=no_id_in_part_list');
    exit;

}


$sql=sprintf("select * from `List Dimension` where `List Key`=%d",$id);

$res=mysql_query($sql);
if (!$part_list_data=mysql_fetch_assoc($res)) {
    header('Location: index.php?error=id_in_part_list_not_found');
    exit;

}


$warehouse=new Warehouse($part_list_data['List Parent Key']);



$part_list_name=$part_list_data['List Name'];
$smarty->assign('part_list_name',$part_list_name);
$smarty->assign('part_list_id',$part_list_data['List Key']);



//$general_options_list[]=array('tipo'=>'js','id'=>'export_data','label'=>_('Export Data(CSV)'));
//$general_options_list[]=array('tipo'=>'url','url'=>'customers_address_label.pdf.php?label=l7159&scope=list&id='.$id,'label'=>_('Print Address Labels'));
//$general_options_list[]=array('tipo'=>'url','url'=>'parts_lists.php?store='.$store->id,'label'=>_('Parts Lists'));
//$general_options_list[]=array('tipo'=>'url','url'=>'inventory.php'.$store->id,'label'=>_('Parts'));

//$smarty->assign('general_options_list',$general_options_list);

//$smarty->assign('options_box_width','450px');


$css_files=array(
                 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
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
              'js/edit_common.js',
              'js/csv_common.js',
              'js/parts_common.js',
              'parts_list.js.php'
          );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','parts');
//$smarty->assign('sub_parent','areas');
$smarty->assign('view',$_SESSION['state']['orders']['view']);

$smarty->assign('parts_view',$_SESSION['state']['warehouse']['parts']['view']);
$smarty->assign('parts_period',$_SESSION['state']['warehouse']['parts']['period']);
$smarty->assign('parts_avg',$_SESSION['state']['warehouse']['parts']['avg']);

$smarty->assign('title', _('Part List').": ".$part_list_data['List Name']);
$smarty->assign('search_label',_('Parts'));
$smarty->assign('search_scope','parts');



$tipo_filter=$_SESSION['state']['warehouse']['parts']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['warehouse']['parts']['f_value']);
$filter_menu=array(
		   'sku'=>array('db_key'=>_('code'),'menu_label'=>'Part SKU','label'=>'SKU'),
		   'used_in'=>array('db_key'=>_('used_in'),'menu_label'=>'Used in','label'=>'Used in'),

		   );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



$smarty->assign('warehouse',$warehouse);
$smarty->assign('warehouse_id',$warehouse->id);


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




$smarty->display('parts_list.tpl');
?>
