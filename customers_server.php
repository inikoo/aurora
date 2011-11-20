<?php
/*
 File: customers.php

 UI customers page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('common.php');

if (!$user->can_view('customers')) {
    exit();
}


$smarty->assign('box_layout','yui-t0');

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
                'common.css',
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
              'js/edit_common.js','js/csv_common.js',
              'customers_server.js.php'
          );






//$smarty->assign('details',$_SESSION['state']['customers']['details']);
$smarty->assign('advanced_search',$_SESSION['state']['customers']['advanced_search']);



$smarty->assign('parent','customers');
$smarty->assign('title', _('Customers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('table_title',_('Customers List'));


$general_options_list=array();
$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');





//$tipo_filter=$_SESSION['state']['customers']['table']['f_field'];
//$smarty->assign('filter',$tipo_filter);
//$smarty->assign('filter_value',$_SESSION['state']['customers']['table']['f_value']);

//$filter_menu=array(
//	   'customer name'=>array('db_key'=>_('customer name'),'menu_label'=>'Customer Name','label'=>'Name'),
//		   'postcode'=>array('db_key'=>_('postcode'),'menu_label'=>'Customer Postcode','label'=>'Postcode'),
//		   'min'=>array('db_key'=>_('min'),'menu_label'=>'Mininum Number of Orders','label'=>'Min No Orders'),
//		   'max'=>array('db_key'=>_('min'),'menu_label'=>'Maximum Number of Orders','label'=>'Max No Orders'),

//		   );
//$smarty->assign('filter_menu0',$filter_menu);
//$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);


$smarty->assign('options_box_width','200px');


$smarty->assign('type',$_SESSION['state']['stores']['customers']['type']);
//$smarty->assign('view',$_SESSION['state']['customers']['table']['view']);


$csv_export_options0=array(
                         'description'=>array(
                                           'title'=>_('Description'),
                                           'rows'=>
                                                  array(
                                                      array(
                                                          'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['code']),
                                                          'name'=>array('label'=>_('Store Name'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['name']),
                                                          'total_customer_contacts'=>array('label'=>_('Total Customer Contacts'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['total_customer_contacts']),


                                                          'total_customer'=>array('label'=>_('Total Customers'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['total_customer_contacts'])

                                                      )
                                                  )
                                       ),

                         'customers_contacts'=>array('title'=>_('Other Customers Details'),
                                                     'rows'=>
                                                            array(
                                                                array(
                                                                    'new_customer_contacts'=>array('label'=>_('New Customers Contacts'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['new_customer_contacts']),

                                                                    'new_customer'=>array('label'=>_('New Customers'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['new_customer']),
                                                                    'active_customer'=>array('label'=>_('Active Customers'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['active_customer']),
                                                                    'lost_customer'=>array('label'=>_('Lost Customers'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['lost_customer']),
                                                                )
                                                            )
                                                    ),

                         'sales_all'=>array('title'=>_('Sales (All times)'),
                                            'rows'=>
                                                   array(
                                                       array(
                                                           'sales_all'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['sales_all']),
                                                           'profit_all'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['profit_all']),
                                                           array('label'=>''),
                                                           array('label'=>''),
                                                       )
                                                   )
                                           ),
                         'sales_1y'=>array('title'=>_('Sales (1 Year)'),
                                           'rows'=>
                                                  array(
                                                      array(
                                                          'sales_1y'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['sales_1y']),
                                                          'profit_1y'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['profit_1y']),
                                                          array('label'=>''),
                                                          array('label'=>''),
                                                      )
                                                  )
                                          ),
                         'sales_1q'=>array('title'=>_('Sales (1 Quarter)'),
                                           'rows'=>
                                                  array(
                                                      array(
                                                          'sales_1q'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['sales_1q']),
                                                          'profit_1q'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['profit_1q']),
                                                          array('label'=>''),
                                                          array('label'=>''),
                                                      )
                                                  )
                                          ),
                         'sales_1m'=>array('title'=>_('Sales (1 Month)'),
                                           'rows'=>
                                                  array(
                                                      array(
                                                          'sales_1m'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['sales_1m']),
                                                          'profit_1m'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['profit_1m']),
                                                          array('label'=>''),
                                                          array('label'=>''),
                                                      )
                                                  )
                                          ),
                         'sales_1w'=>array('title'=>_('Sales (1 Week)'),
                                           'rows'=>
                                                  array(
                                                      array(
                                                          'sales_1w'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['sales_1w']),
                                                          'profit_1w'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['stores']['customers']['csv_export']['profit_1w']),
                                                          array('label'=>''),
                                                          array('label'=>''),
                                                      )
                                                  )
                                          )
                     );
$smarty->assign('export_csv_table_cols0',7);
$smarty->assign('csv_export_options0',$csv_export_options0);
$smarty->display('customers_server.tpl');

?>
