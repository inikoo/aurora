<?php
/*

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/

include_once('common.php');
include_once('class.Store.php');

$general_options_list=array();
$smarty->assign('general_options_list',$general_options_list);

if(!$user->can_view('users'))
{
	header('location:index.php?forbidden');
	exit();
}

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'container.css',
               'button.css',
               'table.css',
               'css/users.css',
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
              'users.js.php',

          );




$smarty->assign('search_scope','users');
$smarty->assign('search_label',_('Search'));

$smarty->assign('parent','users');
$smarty->assign('title', _('Users'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$sql=sprintf("select count(*) as num  ,  `User Type`  from `User Dimension` where `User Active`='Yes' group by `User Type`");
$res=mysql_query($sql);
$number_users=array('Administrator'=>0,'Customer'=>0,'Staff'=>0,'Supplier'=>0);
while($row=mysql_fetch_assoc($res)){
$number_users[$row['User Type']]=number($row['num']);

}
$smarty->assign('number_users',$number_users);





$sql=sprintf("select `Store Key` from `Store Dimension`");
$res=mysql_query($sql);
$stores=array();
$number_stores=0;
while($row=mysql_fetch_assoc($res)){
$number_stores++;
$stores[]=new Store($row['Store Key']);
}

$smarty->assign('number_stores',$number_stores);
$smarty->assign('stores',$stores);


$sql=sprintf("select count(*) as num  from `Supplier Dimension`");
$res=mysql_query($sql);
$number_suppliers=0;
while($row=mysql_fetch_assoc($res)){
$number_suppliers=$row['num'];
}
$smarty->assign('number_suppliers',$number_suppliers);


$sql=sprintf("select count(*) as num  from `Customer Dimension`");
$res=mysql_query($sql);
$number_custmers=0;
while($row=mysql_fetch_assoc($res)){
$number_custmers=$row['num'];

}
$smarty->assign('number_customers',$number_custmers);

$sql=sprintf("select count(*) as num  from `Staff Dimension` where `Staff Currently Working`='Yes'");
$res=mysql_query($sql);
$number_staff=0;
while($row=mysql_fetch_assoc($res)){
$number_staff=$row['num'];

}

$root=new User('Administrator');
$smarty->assign('root',$root);
$warehouse_user=new User('Warehouse');
if($warehouse_user->id)
$smarty->assign('warehouse_user',$warehouse_user);



$smarty->assign('number_staff',$number_staff);



$general_options_list=array();
$general_options_list[]=array('tipo'=>'url','url'=>'users_staff.php','label'=>_('Staff Users'));
$general_options_list[]=array('tipo'=>'url','url'=>'users_supplier.php','label'=>_('Supplier Users'));
$general_options_list[]=array('tipo'=>'url','url'=>'users_customer.php','label'=>_('Customer Users'));
$general_options_list[]=array('class'=>'edit','tipo'=>'url','url'=>'change_style.php','label'=>_('Manage Themes'));


//$smarty->assign('general_options_list',$general_options_list);


$smarty->display('users.tpl');
?>

