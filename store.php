<?php
/*
 File: store.php

 UI store page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('common.php');

include_once('class.Store.php');
include_once('assets_header_functions.php');
$page='store';
$smarty->assign('page',$page);
if (isset($_REQUEST['id']) and is_numeric($_REQUEST['id']) ) {
    $store_id=$_REQUEST['id'];

} else {
    $store_id=$_SESSION['state'][$page]['id'];
}

if (isset($_REQUEST['edit'])) {
    header('Location: edit_store.php?id='.$store_id);

    exit("E2");
}


if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
    header('Location: index.php');
    exit;
}



$store=new Store($store_id);
$_SESSION['state'][$page]['id']=$store->id;
$smarty->assign('store_key',$store->id);

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product departments');

$modify=$user->can_edit('stores');

$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);


$stores_order=$_SESSION['state']['stores']['stores']['order'];
$stores_period=$_SESSION['state']['stores']['stores']['period'];
$stores_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));

$smarty->assign('stores_period',$stores_period);
$smarty->assign('stores_period_title',$stores_period_title[$stores_period]);

$block_view=$_SESSION['state'][$page]['block_view'];
$smarty->assign('block_view',$block_view);


get_header_info($user,$smarty);




$general_options_list=array();

if ($modify)
    $general_options_list[]=array('tipo'=>'url','url'=>'edit_store.php?id='.$store->id,'label'=>_('Edit Store'));
    $general_options_list[]=array('tipo'=>'url','url'=>'product_categories.php','label'=>_('Categories'));
 $general_options_list[]=array('tipo'=>'url','url'=>'products_lists.php?store='.$store->id,'label'=>_('Products Lists'));

//$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');

$css_files=array(
      $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'container.css',
               'button.css',
               'table.css',
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
                'js/php.default.min.js',
                'js/common.js',
                'js/table_common.js',
	         'js/edit_common.js',
                'js/csv_common.js',
                'js/dropdown.js',
                'js/assets_common.js'
          );


$js_files[]='js/search.js';
$js_files[]='common_plot.js.php?page='.$page;

$js_files[]='store.js.php';

//$js_files=array();

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('plot_tipo',$_SESSION['state']['store']['plot']);


$_SESSION['state']['assets']['page']=$page;
if (isset($_REQUEST['view'])) {
    $valid_views=array('sales','general','stoke');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state'][$page]['view']=$_REQUEST['view'];

}
$smarty->assign('department_view',$_SESSION['state']['store']['departments']['view']);
$smarty->assign('department_show_percentages',$_SESSION['state']['store']['departments']['percentages']);
$smarty->assign('department_avg',$_SESSION['state']['store']['departments']['avg']);
$smarty->assign('department_period',$_SESSION['state']['store']['departments']['period']);


$q='';
$tipo_filter=($q==''?$_SESSION['state']['store']['departments']['f_field']:'code');
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',($q==''?$_SESSION['state']['store']['departments']['f_value']:addslashes($q)));
$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>'Store starting with  <i>x</i>','label'=>'Code')
             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('departments',$store->data['Store Departments']);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->assign('family_view',$_SESSION['state']['store']['families']['view']);
$smarty->assign('family_show_percentages',$_SESSION['state']['store']['families']['percentages']);
$smarty->assign('family_avg',$_SESSION['state']['store']['families']['avg']);
$smarty->assign('family_period',$_SESSION['state']['store']['families']['period']);

$q='';
$tipo_filter=($q==''?$_SESSION['state']['store']['families']['f_field']:'code');
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',($q==''?$_SESSION['state']['store']['families']['f_value']:addslashes($q)));
$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>'Family starting with  <i>x</i>','label'=>'Code')
             );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('families',$store->data['Store Families']);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);

$smarty->assign('product_view',$_SESSION['state']['store']['products']['view']);
$smarty->assign('product_show_percentages',$_SESSION['state']['store']['products']['percentages']);
$smarty->assign('product_avg',$_SESSION['state']['store']['products']['avg']);
$smarty->assign('product_period',$_SESSION['state']['store']['products']['period']);

$q='';
$tipo_filter=($q==''?$_SESSION['state']['store']['products']['f_field']:'code');
$smarty->assign('filter2',$tipo_filter);
$smarty->assign('filter_value2',($q==''?$_SESSION['state']['store']['products']['f_value']:addslashes($q)));
$filter_menu=array(
                 'code'=>array('db_key'=>'code','menu_label'=>'Product starting with  <i>x</i>','label'=>'Code')
             );
$smarty->assign('filter_menu2',$filter_menu);
$smarty->assign('products',$store->data['Store For Public Sale Products']);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);


$info_period_menu=array(
                      array("period"=>'week','label'=>_('Last Week'),'title'=> _('Last Week'))
                      ,array("period"=>'month','label'=>_('Last Month'),'title'=>_('Last Month'))
                      ,array("period"=>'quarter','label'=>_('Last Quarter'),'title'=>_('Last Quarter'))
                      ,array("period"=>'year','label'=>_('Last Year'),'title'=>_('Last Year'))
                      ,array("period"=>'all','label'=>_('All'),'title'=>_('All'))
                  );
$smarty->assign('info_period_menu',$info_period_menu);


$subject_id=$store_id;


$smarty->assign($page,$store);

$smarty->assign('parent','products');
$smarty->assign('title', $store->data['Store Name']);



 $csv_export_options=array(
                            'description'=>array(
                                              'title'=>_('Description'),
                                              'rows'=>
                                                     array(
                                                         array(
                                                             'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['code']),
                                                             'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['name']),
                                                            
                                                             'families'=>array('label'=>_('Families'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['families']),
                                                             'products'=>array('label'=>_('Products'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['products']),
                                                   
                                                             'discontinued'=>array('label'=>_('Discontinued'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['discontinued']),
                                                            
                                                     
                                                         )
                                                     )
                                          ),
                            'stock'=>array(
                                        'title'=>_('Stock'),
                                        'rows'=>
                                               array(
                                                   array(
                                                       'surplus'=>array('label'=>_('Surplus'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['surplus']),
                                                       'ok'=>array('label'=>_('Ok'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['ok']),
                                                       'low'=>array('label'=>_('Low'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['low']),
                                                       'critical'=>array('label'=>_('Critical'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['critical']),
                                                       'gone'=>array('label'=>_('Gone'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['gone']),
                                                
                                                       'unknown'=>array('label'=>_('Unknown'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['unknown']),
                                                             array('label'=>''),
                                                       

                                                   )
                                               )
                                    ),
                            'sales_all'=>array('title'=>_('Sales (All times)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_all'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['sales_all']),
                                                       'profit_all'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['profit_all']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1y'=>array('title'=>_('Sales (1 Year)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1y'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['sales_1y']),
                                                       'profit_1y'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['profit_1y']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1q'=>array('title'=>_('Sales (1 Quarter)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1q'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['sales_1q']),
                                                       'profit_1q'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['profit_1q']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1m'=>array('title'=>_('Sales (1 Month)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1m'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['sales_1m']),
                                                       'profit_1m'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['profit_1m']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
                            'sales_1w'=>array('title'=>_('Sales (1 Week)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1w'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['sales_1w']),
                                                       'profit_1w'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['store']['departments']['csv_export']['profit_1w']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            )
                        );
$smarty->assign('export_csv_table_cols',7);
$smarty->assign('csv_export_options',$csv_export_options);
$smarty->assign('options_box_width','550px');



$elements_number=array('InProcess'=>0,'Discontinued'=>0,'Normal'=>0,'Discontinuing'=>0,'NoSale'=>0);
$sql=sprintf("select count(*) as num ,`Product Family Record Type` from  `Product Family Dimension` where `Product Family Store Key`=%d group by  `Product Family Record Type`   ",$store->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $elements_number[$row['Product Family Record Type']]=$row['num'];
}
$smarty->assign('elements_family_number',$elements_number);
//print_r($_SESSION['state']['store']['families']);
$smarty->assign('elements_family',$_SESSION['state']['store']['families']['elements']);

$elements_number=array('Historic'=>0,'Discontinued'=>0,'NoSale'=>0,'Sale'=>0,'Private'=>0);
$sql=sprintf("select count(*) as num,`Product Main Type` from  `Product Dimension` where `Product Store Key`=%d group by `Product Main Type`",$store->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $elements_number[$row['Product Main Type']]=$row['num'];
}
$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['store']['products']['elements']);


$number_sites=$store->get_number_sites();
$smarty->assign('number_sites',$number_sites);


$smarty->display('store.tpl');

?>
