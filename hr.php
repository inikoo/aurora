<?php
include_once('common.php');
if (!$user->can_view('staff')) {
    header('Location: index.php');
    exit;
}

$modify=$user->can_edit('staff');

$smarty->assign('modify',$modify);


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'css/common.css',
               'css/container.css',
               'css/button.css',
               'css/table.css',
               'theme.css.php'
           );

$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-min.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              'js/common.js','js/search.js',
              'js/table_common.js','js/edit_common.js',
              'hr.js.php'
          );
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->assign('parent','staff');
$smarty->assign('sub_parent','hr');
$smarty->assign('title', $account_label);

$smarty->assign('block_view',$_SESSION['state']['hr']['block']);
//$smarty->assign('staff_view',$_SESSION['state']['hr']['staff']['view']);

$tipo_filter=$_SESSION['state']['hr']['employees']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['hr']['employees']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'staff.alias','menu_label'=>_('Staff Name').' <i>*x*</i>','label'=>_('Name')),
                 'id'=>array('db_key'=>'staff_id','menu_label'=>_('Staff ID'),'label'=>_('Staff ID')),
                 'alias'=>array('db_key'=>'alias','menu_label'=>_('Alias'),'label'=>_('Alias')),
             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$tipo_filter=$_SESSION['state']['hr']['areas']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['hr']['areas']['f_value']);
$filter_menu=array(
                'name'=>array('db_key'=>'staff.alias','menu_label'=>_('Area Name'),'label'=>_('Name')),
                 'code'=>array('db_key'=>'alias','menu_label'=>_('Area Code'),'label'=>_('Code')),
             );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);

$tipo_filter=$_SESSION['state']['hr']['departments']['f_field'];
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['hr']['departments']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'staff.alias','menu_label'=>'Staff name <i>*x*</i>','label'=>'Name'),
                 'position_id'=>array('db_key'=>'position_id','menu_label'=>'Position Id','label'=>'Position Id'),
                 'area_id'=>array('db_key'=>'area_id','menu_label'=>'Area Id','label'=>'Area Id'),
             );
$smarty->assign('filter_menu2',$filter_menu);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);

$tipo_filter=$_SESSION['state']['hr']['positions']['f_field'];
$smarty->assign('filter3',$tipo_filter);
$smarty->assign('filter_value3',$_SESSION['state']['hr']['positions']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'staff.alias','menu_label'=>'Staff name <i>*x*</i>','label'=>'Name'),
                 'position_id'=>array('db_key'=>'position_id','menu_label'=>'Position Id','label'=>'Position Id'),
                 'area_id'=>array('db_key'=>'area_id','menu_label'=>'Area Id','label'=>'Area Id'),
             );
$smarty->assign('filter_menu3',$filter_menu);
$smarty->assign('filter_name3',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu3',$paginator_menu);



$smarty->assign('search_label',_('Staff'));
$smarty->assign('search_scope','staff');

$elements_number=array('Working'=>0,'NotWorking'=>0);
$sql=sprintf("select count(*) as num,`Staff Currently Working` from  `Staff Dimension`  group by `Staff Currently Working`");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
	$key=($row['Staff Currently Working']=='Yes'?'Working':'NotWorking');	

    $elements_number[$key]=$row['num'];
}


$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['hr']['employees']['elements']);


$smarty->display('hr.tpl');
?>
