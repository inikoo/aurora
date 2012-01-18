<?php
/*
 File: supplier.php

 UI supplier page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('class.Supplier.php');

if ($user->data['User Type']!='Supplier' and !$user->can_view('suppliers')) {
    $smarty->display('forbidden.tpl');
    exit;
}


$modify=$user->can_edit('suppliers');

if (isset($_REQUEST['edit']) and $_REQUEST['edit']) {
    header('Location: edit_suplier.php?id='.$_REQUEST['edit']);
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

$smarty->assign('block_view',$_SESSION['state']['supplier']['block_view']);


$supplier=new Supplier($supplier_id);
if (!$supplier->id) {
    header('Location: suppliers.php?msg=SNPF');
    exit;
}


$smarty->assign('search_label',_('Search'));
$smarty->assign('search_scope','supplier_products');


$general_options_list=array();


if ($modify) {
    // $general_options_list[]=array('tipo'=>'url','url'=>'new_import_csv.php?subject=supplier_products&subject_key='.$supplier_id,'label'=>_('Import (CSV)'));
    $general_options_list[]=array('tipo'=>'url','url'=>'edit_supplier.php?id='.$supplier_id,'label'=>_('Edit Supplier'));
    $general_options_list[]=array('tipo'=>'url','url'=>'supplier_invoice.php?new=1&supplier_id='.$supplier_id,'label'=>_('Input Invoice'));
    $general_options_list[]=array('tipo'=>'url','url'=>'supplier_delivery.php?new=1&supplier_id='.$supplier_id,'label'=>_('New Delivery'));
    $general_options_list[]=array('tipo'=>'url','url'=>'porder.php?new=1&supplier_id='.$supplier_id,'label'=>_('New Purchase Order'));

}




$smarty->assign('general_options_list',$general_options_list);



$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               'common.css',
               'css/container.css',
               'button.css',
               'table.css',
               'theme.css.php'
           );
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
              'js/search.js',
              'js/table_common.js',
              'js/edit_common.js',
              'js/csv_common.js',
              'js/suppliers_common.js',

          );





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
                 'p.code'=>array('db_key'=>'p.code','menu_label'=>_('Our Product Code'),'label'=>_('Code')),
                 'sup_code'=>array('db_key'=>'sup_code','menu_label'=>_('Supplier Product Code'),'label'=>_('Supplier Code')),
             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);



$js_files[]=sprintf('supplier.js.php');
$smarty->assign('display',$_SESSION['state']['supplier']['display']);

$smarty->assign('supplier_products_view',$_SESSION['state']['supplier']['supplier_products']['view']);
$smarty->assign('supplier_products_period',$_SESSION['state']['supplier']['supplier_products']['period']);
//print_r($_SESSION['state']['supplier']['supplier_products']);

//$smarty->assign('supplier_products_avg',$_SESSION['state']['supplier']['supplier_products']['avg']);



$tipo_filter=$_SESSION['state']['supplier']['po']['f_field'];
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['supplier']['po']['f_value']);

$filter_menu=array(
                 'id'=>array('db_key'=>'p.code','menu_label'=>'Purchase order','label'=>'Id'),
                 'minvalue'=>array('db_key'=>'minvalue','menu_label'=>'Orders with a minimum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Min Value ('.$myconf['currency_symbol'].')'),
                 'maxvalue'=>array('db_key'=>'maxvalue','menu_label'=>'Orders with a maximum value of <i>'.$myconf['currency_symbol'].'n</i>','label'=>'Max Value ('.$myconf['currency_symbol'].')'),
                 'max'=>array('db_key'=>'max','menu_label'=>'Orders from the last <i>n</i> days','label'=>'Last (days)')
             );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu1',$paginator_menu);

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$csv_export_options=array(
                        'description'=>array(
                                          'title'=>_('Description'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['code']),
                                                         'supplier'=>array('label'=>_('Supplier'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['supplier']),
                                                         'product_name'=>array('label'=>_('Product Name'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['product_name']),
                                                         'product_description'=>array('label'=>_('Product Description'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['product_description']),
                                                     )
                                                 )
                                      ),
                        'other_details'=>array(
                                            'title'=>_('Other Details'),
                                            'rows'=>
                                                   array(
                                                       array(
                                                           'unit_type'=>array('label'=>_('Product Unit Type'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['unit_type']),
                                                           'currency'=>array('label'=>_('Currency'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['currency']),
                                                           'valid_from'=>array('label'=>_('Product Valid From'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['valid_from']),
                                                           'valid_to'=>array('label'=>_('Product Valid To'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['valid_to']),
                                                           'buy_state'=>array('label'=>_('Buy State'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['buy_state']),

                                                       )
                                                   )
                                        ),
                        'cost_all'=>array('title'=>_('Cost (All times)'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'cost_all'=>array('label'=>_('Cost'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['cost_all']),
                                                         'profit_all'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['profit_all']),
                                                         array('label'=>''),
                                                         array('label'=>''),
                                                     )
                                                 )
                                         ),
                        'cost_1y'=>array('title'=>_('Sales (1 Year)'),
                                         'rows'=>
                                                array(
                                                    array(
                                                        'cost_1y'=>array('label'=>_('Cost'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['cost_1y']),
                                                        'profit_1y'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['profit_1y']),
                                                        array('label'=>''),
                                                        array('label'=>''),
                                                    )
                                                )
                                        ),
                        'cost_1q'=>array('title'=>_('Sales (1 Quarter)'),
                                         'rows'=>
                                                array(
                                                    array(
                                                        'cost_1q'=>array('label'=>_('Cost'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['cost_1q']),
                                                        'profit_1q'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['profit_1q']),
                                                        array('label'=>''),
                                                        array('label'=>''),
                                                    )
                                                )
                                        ),
                        'cost_1m'=>array('title'=>_('Sales (1 Month)'),
                                         'rows'=>
                                                array(
                                                    array(
                                                        'cost_1m'=>array('label'=>_('Cost'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['cost_1m']),
                                                        'profit_1m'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['profit_1m']),
                                                        array('label'=>''),
                                                        array('label'=>''),
                                                    )
                                                )
                                        ),
                        'cost_1w'=>array('title'=>_('Sales (1 Week)'),
                                         'rows'=>
                                                array(
                                                    array(
                                                        'cost_1w'=>array('label'=>_('Cost'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['cost_1w']),
                                                        'profit_1w'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['supplier']['supplier_products']['csv_export']['profit_1w']),
                                                        array('label'=>''),
                                                        array('label'=>''),
                                                    )
                                                )
                                        )
                    );
$smarty->assign('export_csv_table_cols',7);
$smarty->assign('csv_export_options',$csv_export_options);



$smarty->display('supplier.tpl');

?>
