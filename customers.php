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
include_once('class.Store.php');

if (!$user->can_view('customers')) {
    header('Location: index.php');
    exit();
}

if (isset($_REQUEST['store']) and is_numeric($_REQUEST['store']) ) {
    $store_id=$_REQUEST['store'];

} else {
    $store_id=$_SESSION['state']['customers']['store'];

}

if (!($user->can_view('stores') and in_array($store_id,$user->stores)   ) ) {
    header('Location: index.php');
    exit;
}

$store=new Store($store_id);
if($store->id){
$_SESSION['state']['customers']['store']=$store->id;
}else{
 header('Location: index.php?error=store_not_found');
    exit();
}

//print_r($_SESSION['state']['customers']);

$currency=$store->data['Store Currency Code'];
$currency_symbol=currency_symbol($currency);
$smarty->assign('store',$store);

$smarty->assign('store_id',$store->id);
$modify=$user->can_edit('customers');

if(preg_match('/es_es/i',  $lc_messages_locale)){

$overview_text=sprintf("Tenemos %s contactos de los cuales %s son contactos activos. La semana pasada adquirimos %s nuevos clientes. Ellos representan un %s de todos los contactos activos."
 ,$store->get('Contacts')
			 ,$store->get('Active Contacts')
			 ,percentage($store->data['Store Active Contacts'],$store->data['Store Contacts'])
			  ,percentage($store->data['Store New Contacts'],$store->data['Store Active Contacts'])
);
}else{
$s='';
if($store->data['Store Total Users'])
	$s=sprintf("%d (%s) customer are registered in the website.", $store->data['Store Total Users'] ,percentage($store->data['Store Total Users'],$store->get('Active Contacts')));

$overview_text=sprintf("We have had %s  contacts so far, %s of them still active (%s). Over the last week we acquired  %s new %s representing  %s of the total active customer base. %s",
			 $store->get('Contacts')
			 ,$store->get('Active Contacts')
			 ,percentage($store->data['Store Active Contacts'],$store->data['Store Contacts'])

			 ,$store->get('New Contacts')
		       ,ngettext('customer','customers',$store->data['Store New Contacts'])
		       ,percentage($store->data['Store New Contacts'],$store->data['Store Active Contacts'])
			   ,$s
		       )
;
}

 $smarty->assign('overview_text',$overview_text);
 
 
 




$general_options_list=array();




$general_options_list[]=array('tipo'=>'url','url'=>'customer_categories.php?store_id='.$store->id.'&id=0','label'=>_('Categories'));
$general_options_list[]=array('tipo'=>'url','url'=>'marketing_post.php?store='.$store->id,'label'=>_('Post'));
$general_options_list[]=array('tipo'=>'url','url'=>'customers_lists.php?store='.$store->id,'label'=>_('Lists'));
$general_options_list[]=array('tipo'=>'url','url'=>'search_customers.php?store='.$store->id,'label'=>_('Advanced Search'));
$general_options_list[]=array('tipo'=>'url','url'=>'customers_stats.php','label'=>_('Stats'));

//$general_options_list[]=array('tipo'=>'url','url'=>'customers.php?store='.$store->id,'label'=>_('Customers'));

if ($modify) {
	$general_options_list[]=array('class'=>'edit','tipo'=>'url','url'=>'customer_store_configuration.php','label'=>_('Customer Store Configuration'));
    $general_options_list[]=array('class'=>'edit','tipo'=>'url','url'=>'edit_customers.php','label'=>_('Edit'));
    $general_options_list[]=array('class'=>'edit','tipo'=>'js','id'=>'new_customer','label'=>_('Add'));
}


//$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');








$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
             
               'button.css',
               'container.css'
           );
include_once('Theme.php');

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
              'common_customers.js.php',
              'customers.js.php?store_key='.$store->id,
              'js/import_csv.js'
          );


//$smarty->assign('advanced_search',$_SESSION['state']['customers']['advanced_search']);


$smarty->assign('parent','customers');
$smarty->assign('title', _('Customers'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->assign('type',$_SESSION['state']['customers']['type']);
//print_r($_SESSION['state']['customers']['table']);
$tipo_filter=$_SESSION['state']['customers']['table']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['customers']['table']['f_value']);

$filter_menu=array(
                 'customer name'=>array('db_key'=>_('customer name'),'menu_label'=>_('Customer Name'),'label'=>_('Name')),
                 'postcode'=>array('db_key'=>_('postcode'),'menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
                 'country'=>array('db_key'=>_('country'),'menu_label'=>_('Customer Country'),'label'=>_('Country')),

                 'min'=>array('db_key'=>_('min'),'menu_label'=>_('Mininum Number of Orders'),'label'=>_('Min No Orders')),
                 'max'=>array('db_key'=>_('min'),'menu_label'=>_('Maximum Number of Orders'),'label'=>_('Max No Orders')),
                 'last_more'=>array('db_key'=>_('last_more'),'menu_label'=>_('Last order more than (days)'),'label'=>_('Last Order >(Days)')),
                 'last_less'=>array('db_key'=>_('last_more'),'menu_label'=>_('Last order less than (days)'),'label'=>_('Last Order <(Days)')),
                 'maxvalue'=>array('db_key'=>_('maxvalue'),'menu_label'=>_('Balance less than').' '.$currency_symbol  ,'label'=>_('Balance')." <($currency_symbol)"),
                 'minvalue'=>array('db_key'=>_('minvalue'),'menu_label'=>_('Balance more than').' '.$currency_symbol  ,'label'=>_('Balance')." >($currency_symbol)"),
             );


$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);
$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);

$smarty->assign('view',$_SESSION['state']['customers']['view']);


// $smarty->assign('export_text',$export_text);
// $smarty->assign('table_info',$total_customers.'  '.ngettext('identified customer','identified customers',$total_customers));



$csv_export_options=array(
                        'description'=>array(
                                          'title'=>_('Description'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'id'=>array('label'=>_('Id'),'selected'=>$_SESSION['state']['customers']['table']['csv_export']['id']),
                                                         'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['customers']['table']['csv_export']['name']),
                                                         'location'=>array('label'=>_('Location'),'selected'=>$_SESSION['state']['customers']['table']['csv_export']['location']),


                                                     )
                                                 )
                                      ),
                        'orders'=>array(
                                     'title'=>_('Orders'),
                                     'rows'=>
                                            array(
                                                array(
                                                    'last_orders'=>array('label'=>_('Last Orders'),'selected'=>$_SESSION['state']['customers']['table']['csv_export']['last_orders']),
                                                    'orders'=>array('label'=>_('Orders'),'selected'=>$_SESSION['state']['customers']['table']['csv_export']['orders']),
                                                    'status'=>array('label'=>_('Status'),'selected'=>$_SESSION['state']['customers']['table']['csv_export']['status'])

                                                )
                                            )
                                 )/*,
                            'sales_all'=>array('title'=>_('Sales (All times)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_all'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_all']),
                                                       'profit_all'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_all']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1y'=>array('title'=>_('Sales (1 Year)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1y'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1y']),
                                                       'profit_1y'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1y']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1q'=>array('title'=>_('Sales (1 Quarter)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1q'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1q']),
                                                       'profit_1q'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1q']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
'sales_1m'=>array('title'=>_('Sales (1 Month)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1m'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1m']),
                                                       'profit_1m'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1m']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            ),
                            'sales_1w'=>array('title'=>_('Sales (1 Week)'),
                            'rows'=>
                                               array(
                                                   array(
                                                       'sales_1w'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['sales_1w']),
                                                       'profit_1w'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['families']['table']['csv_export']['profit_1w']),
                                                        array('label'=>''),
                                                             array('label'=>''),
                                                   )
                            )
                            )*/
                    );
$smarty->assign('export_csv_table_cols',2);


$smarty->assign('csv_export_options',$csv_export_options);
//print_r($_SESSION['state']['customers']);
$smarty->assign('options_box_width','600px');

$smarty->display('customers.tpl');
?>
