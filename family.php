<?php
/*
 File: family.php

 UI family page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/


include_once('common.php');
include_once('class.Family.php');
include_once('class.Store.php');
include_once('class.Department.php');
include_once('assets_header_functions.php');

if (!isset($_REQUEST['id']) or !is_numeric($_REQUEST['id']))
    $family_id=$_SESSION['state']['family']['id'];
else
    $family_id=$_REQUEST['id'];
    
 $family=new Family($family_id);
 if(!$family->id){
   header('Location: stores.php');
    exit();
 
 }
 
    
$_SESSION['state']['family']['id']=$family_id;





//$tmp_page_data=$family->get_page_data();
//$page_data=array();
//foreach($tmp_page_data as $key=>$value) {
//    $page_data[preg_replace('/\s/','',$key)]=$value;
//}
//$smarty->assign('page_data',$page_data);



//print_r($page_data);

$_SESSION['state']['department']['id']=$family->data['Product Family Main Department Key'];
$_SESSION['state']['store']['id']=$family->data['Product Family Store Key'];



if (!( $user->can_view('stores') and in_array($family->data['Product Family Store Key'],$user->stores))) {
    header('Location: index.php');
    exit();
}

$store=new Store($family->data['Product Family Store Key']);
$department=new Department($family->get('Product Family Main Department Key'));

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$create=$user->can_create('product families');
$modify=$user->can_edit('stores');

if (isset($_REQUEST['edit'])) {
    header('Location: edit_department.php?id='.$department_id);
    exit();

}




$smarty->assign('view_parts',$user->can_view('parts'));

$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);

get_header_info($user,$smarty);


$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');

$block_view=$_SESSION['state']['family']['block_view'];
$smarty->assign('block_view',$block_view);




$smarty->assign('table_type',$_SESSION['state']['family']['products']['table_type']);
$general_options_list=array();


if ($modify)
    $general_options_list[]=array('tipo'=>'url','url'=>'edit_family.php?id='.$family->id,'label'=>_('Edit Family'));


//$smarty->assign('general_options_list',$general_options_list);
$show_only=$_SESSION['state']['family']['products']['show_only'];
$show_only_labels=array('forsale'=>_('For Sale Only'));

$_SESSION['state']['family']['products']['table']['restrictions']=$show_only;
//print_r($_SESSION['state']['family']['products']['table']);exit;

$smarty->assign('show_only',$show_only);
$smarty->assign('show_only_label',$show_only_labels[$show_only]);


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'autocomplete/assets/skins/sam/autocomplete.css',
               'common.css',
               'container.css',
               'button.css',
               'table.css',
               'css/dropdown.css',
			   'css/edit.css'
           );
		   

$css_files[]='theme.css.php';
$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'uploader/uploader.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-debug.js',
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
$js_files[]='family.js.php';



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);






//$_SESSION['state']['assets']['page']='department';
if (isset($_REQUEST['view'])) {
    $valid_views=array('sales','general','stoke');
    if (in_array($_REQUEST['view'], $valid_views))
        $_SESSION['state']['families']['products']['view']=$_REQUEST['view'];

}

$department_order=$_SESSION['state']['department']['products']['order'];
$department_period=$_SESSION['state']['department']['products']['period'];
$department_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));


$smarty->assign('department_period',$department_period);
$smarty->assign('department_period_title',$department_period_title[$department_period]);




if (isset($_REQUEST['department_id']) and $_REQUEST['department_id']>0) {
    $department_id=$_REQUEST['department_id'];
    $order=$_SESSION['state']['department']['families']['order'];
    if ($order=='per_tsall' or $order=='tsall')
        $order='total_sales';
    if ($order=='per_tsm' or $order=='tms')
        $order='month_sales';
    if ($order=='code')
        $order='Product Family Code';
    if ($order=='name')
        $order='Product Family Name';
    if ($order=='active')
        $order='Product Family For Sale Products';
    if ($order=='outofstock')
        $order='Product Family Out Of Stock Products';
    if ($order=='stockerror')
        $order='Product Family Unknown Stock Products';





    $sql=sprintf("select  F.`Product Family Key` as id, `Product Family Code` as code  from `Product Family Dimension`   F left join `Product Family Department Bridge` FD on (FD.`Product Family Key`=F.`Product Family Key`) where  `%s`<'%s' and `Product Department Key`=%d  order by `%s` desc  ",$order,$family->get($order),$department_id,$order);


    $res = mysql_query($sql);
    if (!$prev=mysql_fetch_array($res, MYSQL_ASSOC))
        $prev=array('id'=>0,'code'=>'');

    $sql=sprintf("select F.`Product Family Key` as id, `Product Family Code` as code   from `Product Family Dimension`   F left join `Product Family Department Bridge`  FD on (FD.`Product Family Key`=F.`Product Family Key`)  where  `%s`>'%s' and `Product Department Key`=%d order by `%s`   ",$order,$family->get($order),$department_id,$order);

    $res = mysql_query($sql);

    if (!$next=mysql_fetch_array($res, MYSQL_ASSOC))
        $next=array('id'=>0,'code'=>'');






    $smarty->assign('prev',$prev);
    $smarty->assign('next',$next);

}





$info_period_menu=array(
                      array("period"=>'week','label'=>_('Last Week'),'title'=> _('Last Week'))
                      ,array("period"=>'month','label'=>_('Last Month'),'title'=>_('Last Month'))
                      ,array("period"=>'quarter','label'=>_('Last Quarter'),'title'=>_('Last Quarter'))
                      ,array("period"=>'year','label'=>_('Last Year'),'title'=>_('Last Year'))
                      ,array("period"=>'all','label'=>_('All'),'title'=>_('All'))
                  );
$smarty->assign('info_period_menu',$info_period_menu);




$smarty->assign('parent','products');
$smarty->assign('title',$family->get('Product Family Code').' - '.$family->get('Product Family Name'));


$product_home="Products Home";
$smarty->assign('home',$product_home);




$smarty->assign('family',$family);
$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);

$smarty->assign('department',$department);



$smarty->assign('product_view',$_SESSION['state']['family']['products']['view']);
$smarty->assign('product_period',$_SESSION['state']['family']['products']['period']);
$smarty->assign('product_avg',$_SESSION['state']['family']['products']['avg']);

$tipo_filter=$_SESSION['state']['family']['products']['f_field'];
$smarty->assign('filter_name0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['family']['products']['f_value']);
$filter_menu=array(
        'code'=>array('db_key'=>'code','menu_label'=>_('Product code starting with <i>x</i>'),'label'=>_('Code')),
       'name'=>array('db_key'=>'name','menu_label'=>_('Product name containing <i>x</i>'),'label'=>_('Name'))


             );
             
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);




$table_title=_('List');
$smarty->assign('table_title',$table_title);

$info_period_menu=array(
                      array("period"=>'week','label'=>_('Last Week'),'title'=> _('Last Week'))
                      ,array("period"=>'month','label'=>_('last Month'),'title'=>_('last Month'))
                      ,array("period"=>'quarter','label'=>_('Last Quarter'),'title'=>_('Last Quarter'))
                      ,array("period"=>'year','label'=>_('Last Year'),'title'=>_('Last Year'))
                      ,array("period"=>'all','label'=>_('All'),'title'=>_('All'))
                  );
$smarty->assign('info_period_menu',$info_period_menu);



$smarty->assign('title',_('Family').': '.$family->get('Product Family Name'));
// -----------------------------------------------export csv code starts here------------------------
$csv_export_options=array(
                        'description'=>array(
                                          'title'=>_('Description'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'code'=>array('label'=>_('Code'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['code']),
                                                         'name'=>array('label'=>_('Name'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['name']),

                                                         'status'=>array('label'=>_('Status'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['status']),
                                                         'web'=>array('label'=>_('Web'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['web']),


                                                     )
                                                 )
                                      ),

                        'sales_all'=>array('title'=>_('Sales (All times)'),
                                           'rows'=>
                                                  array(
                                                      array(
                                                          'sales_all'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['sales_all']),
                                                          'profit_all'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['profit_all']),
                                                          array('label'=>''),
                                                          array('label'=>''),
                                                      )
                                                  )
                                          ),
                        'sales_1y'=>array('title'=>_('Sales (1 Year)'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'sales_1y'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['sales_1y']),
                                                         'profit_1y'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['profit_1y']),
                                                         array('label'=>''),
                                                         array('label'=>''),
                                                     )
                                                 )
                                         ),
                        'sales_1q'=>array('title'=>_('Sales (1 Quarter)'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'sales_1q'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['sales_1q']),
                                                         'profit_1q'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['profit_1q']),
                                                         array('label'=>''),
                                                         array('label'=>''),
                                                     )
                                                 )
                                         ),
                        'sales_1m'=>array('title'=>_('Sales (1 Month)'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'sales_1m'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['sales_1m']),
                                                         'profit_1m'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['profit_1m']),
                                                         array('label'=>''),
                                                         array('label'=>''),
                                                     )
                                                 )
                                         ),
                        'sales_1w'=>array('title'=>_('Sales (1 Week)'),
                                          'rows'=>
                                                 array(
                                                     array(
                                                         'sales_1w'=>array('label'=>_('Sales'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['sales_1w']),
                                                         'profit_1w'=>array('label'=>_('Profit'),'selected'=>$_SESSION['state']['family']['products']['csv_export']['profit_1w']),
                                                         array('label'=>''),
                                                         array('label'=>''),
                                                     )
                                                 )
                                         )
                    );
$smarty->assign('export_csv_table_cols',6);
$smarty->assign('csv_export_options',$csv_export_options);
// -----------------------------------------------export csv code ends here------------------------

$family->load_images_slidesshow();
$images=$family->images_slideshow;
$smarty->assign('div_img_width',190);
$smarty->assign('img_width',190);
$smarty->assign('images',$images);
$smarty->assign('num_images',count($images));


$elements_number=array('Historic'=>0,'Discontinued'=>0,'NoSale'=>0,'Sale'=>0,'Private'=>0);
$sql=sprintf("select count(*) as num,`Product Main Type` from  `Product Dimension` where `Product Family Key`=%d group by `Product Main Type`",$family->id);
$res=mysql_query($sql);
while ($row=mysql_fetch_assoc($res)) {
    $elements_number[$row['Product Main Type']]=$row['num'];
}
$smarty->assign('elements_number',$elements_number);
$smarty->assign('elements',$_SESSION['state']['family']['products']['elements']);


$smarty->display('family.tpl');


?>
