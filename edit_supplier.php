<?php
/*
 File: supplier.php

 UI supplier page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2010, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('class.Supplier.php');


if ($user->data['User Type']!='Supplier' and !$user->can_view('suppliers')) {
    $smarty->display('forbidden.tpl');
    exit;
}

if (!$user->can_edit('suppliers')) {
    $smarty->display('forbidden.tpl');
    exit;
}

if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))
    $supplier_id=$_REQUEST['id'];
else
    $supplier_id=$_SESSION['state']['supplier']['id'];


if ($user->data['User Type']=='Supplier' and !in_array($supplier_id,$user->suppliers)) {
    $smarty->display('forbidden.tpl');
    exit;
}

$_SESSION['state']['supplier']['id']=$supplier_id;
$smarty->assign('supplier_id',$supplier_id);

$smarty->assign('orders_view',$_SESSION['state']['supplier']['orders_view']);

$supplier=new Supplier($supplier_id);
if (!$supplier->id) {
    header('Location: suppliers.php?msg=SNPF');
    exit;
}


$show_details=$_SESSION['state']['supplier']['details'];
$smarty->assign('show_details',$show_details);

$general_options_list[]=array('class'=>'return','tipo'=>'url','url'=>'supplier.php?id='.$supplier_id,'label'=>_('Supplier').' &#8617;');
$smarty->assign('general_options_list',$general_options_list);



$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',

               'css/common.css',
               'css/button.css',
               'css/container.css',
               'css/table.css'
           );
$css_files[]='theme.css.php';
$js_files=array(

              $yui_path.'utilities/utilities.js',
              $yui_path.'connection/connection-debug.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'animation/animation-min.js',

              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              $yui_path.'calendar/calendar-min.js',
              'js/common.js',
              'js/table_common.js',
'js/search.js',
          );


$smarty->assign('search_label',_('Search'));
$smarty->assign('search_scope','supplier_products');


$company=new Company($supplier->data['Supplier Company Key']);
//$supplier->load('contacts');
$smarty->assign('supplier',$supplier);
$smarty->assign('company',$company);

$address=new address($company->data['Company Main Address Key']);
$smarty->assign('address',$address);



$smarty->assign('parent','suppliers');
$smarty->assign('title','Supplier: '.$supplier->get('Supplier Code'));


$tipo_filter=$_SESSION['state']['supplier']['supplier_products']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['supplier']['supplier_products']['f_value']);

$filter_menu=array(
                 'p.code'=>array('db_key'=>'p.code','menu_label'=>'Our Product Code','label'=>'Code'),
                 'sup_code'=>array('db_key'=>'sup_code','menu_label'=>'Supplier Product Code','label'=>'Supplier Code'),
             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



$sql=sprintf("select * from kbase.`Salutation Dimension` S left join kbase.`Language Dimension` L on S.`Language Code`=L.`Language ISO 639-1 Code`  where `Language Code`=%s limit 1000",prepare_mysql($myconf['lang']));
$result=mysql_query($sql);
$salutations=array();
while ($row=mysql_fetch_array($result, MYSQL_ASSOC)   ) {
    $salutations[]=array('txt'=>$row['Salutation'],'relevance'=>$row['Relevance'],'id'=>$row['Salutation Key']);
}
mysql_free_result($result);
$smarty->assign('prefix',$salutations);
$editing_block=$_SESSION['state']['supplier']['edit'];
$smarty->assign('edit',$editing_block);

$addresses=$company->get_addresses();
$smarty->assign('addresses',$addresses);
$number_of_addresses=count($addresses);
$smarty->assign('number_of_addresses',$number_of_addresses);

$contacts=$company->get_contacts();
$smarty->assign('contacts',$contacts);
$number_of_contacts=count($contacts);
$smarty->assign('number_of_contacts',$number_of_contacts);

$smarty->assign('scope','Supplier');

$js_files[]='js/edit_common.js';
$js_files[]='js/validate_telecom.js';

$js_files[]='edit_address.js.php';
$js_files[]='address_data.js.php?tipo=supplier&id='.$supplier->id;

 

$js_files[]='edit_contact_from_parent.js.php';
$js_files[]='js/edit_contact_telecom.js';
$js_files[]='edit_contact_name.js.php';
$js_files[]='edit_contact_email.js.php';

$js_files[]=sprintf('edit_company.js.php?edit=%s&id=%d',$editing_block,$company->id);
$js_files[]=sprintf('edit_supplier.js.php');

$css_files[]=$yui_path.'assets/skins/sam/autocomplete.css';
$css_files[]='css/edit_address.css';
$css_files[]='css/edit.css';

$smarty->assign('from','supplier');
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$units=array();
foreach(getEnumVals('`Supplier Product Dimension`','Supplier Product Unit Type') as $option){
$units[$option]=$option;
}
$smarty->assign('units_list',$units);
$smarty->assign('units_list_selected','ea');

$currencies=array();
$sql=sprintf("select `Currency Code`,`Currency Name` from kbase.`Currency Dimension");
$res=mysql_query($sql);
while($row=mysql_fetch_assoc($res)){
$currencies[$row['Currency Code']]=sprintf("(%s) %s",$row['Currency Code'],$row['Currency Name']);
}
$smarty->assign('currency_list',$currencies);
$smarty->assign('currency_selected',$supplier->data['Supplier Default Currency']);


$categories_value=array();
$categories=array();
$sql=sprintf("select `Category Key` from `Category Dimension` where `Category Subject`='Supplier' and `Category Deep`=1 ");
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $tmp=new Category($row['Category Key']);
    $selected_array=$tmp->sub_category_selected_by_subject($supplier->id);


    if (count($selected_array)==0) {
        $tmp_selected='';
    } else {
        $tmp_selected=array_pop($selected_array);
    }

    $categories[$row['Category Key']]=$tmp;
    $categories_value[$row['Category Key']]=$tmp_selected;

}

$smarty->assign('show_history',$_SESSION['state']['supplier']['show_history']);

$smarty->assign('categories',$categories);
$smarty->assign('categories_value',$categories_value);
$smarty->assign('default_country_2alpha','GB');

$tipo_filter100='code';
$filter_menu100=array(
                  'code'=>array('db_key'=>'code','menu_label'=>_('Country Code'),'label'=>_('Code')),
                	'name'=>array('db_key'=>'name','menu_label'=>_('Country Name'),'label'=>_('Name')),
                 'wregion'=>array('db_key'=>'wregion','menu_label'=>_('World Region Name'),'label'=>_('Region')),
              );
$smarty->assign('filter_name100',$filter_menu100[$tipo_filter100]['label']);
$smarty->assign('filter_menu100',$filter_menu100);
$smarty->assign('filter100',$tipo_filter100);
$smarty->assign('filter_value100','');

$smarty->display('edit_supplier.tpl');



?>
