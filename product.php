<?php
/*
 File: product.php

 UI product page

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2009, Inikoo

 Version 2.0
*/
include_once('common.php');
include_once('class.Location.php');

include_once('class.Product.php');
include_once('assets_header_functions.php');
$page='product';
$smarty->assign('page',$page);


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
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-debug.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              'js/php.default.min.js',
              'js/common.js',
              'js/table_common.js',

              'js/dropdown.js',
		'js/jquery.js',
		'js/jquery-1.6.1.min.js',
		'js/jquery.prettyPhoto.js',
		
          );




if (isset($_REQUEST['code'])) {
    $mode='code';
    $tag=$_REQUEST['code'];
}
elseif(isset($_REQUEST['pid'])) {
    $mode='pid';
    $tag=$_REQUEST['pid'];




}
elseif(isset($_REQUEST['key'])) {
    $mode='key';
    $tag=$_REQUEST['key'];
}
else {
    $tag=$_SESSION['state']['product']['tag'];
    $mode=$_SESSION['state']['product']['mode'];
}
$_SESSION['state']['product']['tag']=$tag;
$_SESSION['state']['product']['mode']=$mode;

$_SESSION['state']['product']['orders']['mode']=$mode;
$_SESSION['state']['product']['customers']['mode']=$mode;


if ($mode=='pid') {
    if (isset($_REQUEST['edit']) and $_REQUEST['edit']) {
        header('Location: edit_product.php?pid='.$tag);
        exit();
    }

}
elseif($mode=='code') {

$number_stores=$user->get_number_stores();
if($number_stores==0){
  header('Location: index.php');
    exit;
}elseif($number_stores==1){
	$store=array_pop($user->stores);
	  $smarty->assign('store',$store);
}

    $sql=sprintf("select `Product ID`  from `Product Dimension` where `Product Code`=%s  and `Product Store Key` in (%s)   ;"
                 ,prepare_mysql($tag)
                 ,join(',',$user->stores)
                );

    $result=mysql_query($sql);
    //print $sql;

    if (mysql_num_rows($result)>1) {
        $_SESSION['state']['product']['server']['tag']=$tag;
        $js_files[]= 'js/search.js';
        // $js_files[]='product.js.php';
        $js_files[]='product_server.js.php';
        $smarty->assign('css_files',$css_files);
        $smarty->assign('js_files',$js_files);
        $smarty->assign('code',$tag);


        $smarty->assign('search_label',_('Products'));
        $smarty->assign('search_scope','products');
        
        
        
        
        
        $tipo_filter=$_SESSION['state']['product']['server']['f_field'];
$smarty->assign('filter_name2',$tipo_filter);
$smarty->assign('filter_value2',$_SESSION['state']['product']['server']['f_value']);
$filter_menu=array(
  'id'=>array('db_key'=>'pid','menu_label'=>_('Product ID like<i>x</i>'),'label'=>_('Id')),
        'code'=>array('db_key'=>'code','menu_label'=>_('Product code starting with <i>x</i>'),'label'=>_('Code')),
       'name'=>array('db_key'=>'name','menu_label'=>_('Product name containing <i>x</i>'),'label'=>_('Name'))
             );
             
$smarty->assign('filter_menu2',$filter_menu);
$smarty->assign('filter_name2',$filter_menu[$tipo_filter]['label']);

$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu2',$paginator_menu);
        
        
        
        
        $smarty->display('product_server.tpl');
        mysql_free_result($result);
        exit;
    }
    elseif(mysql_num_rows($result)==0) {

        header('Location: index.php');
        exit;

    }
    else {



        $row=mysql_fetch_array($result, MYSQL_ASSOC);
        mysql_free_result($result);
        $tag=$row['Product ID'];
        $mode='pid';
        $_SESSION['state']['product']['tag']=$tag;
        $_SESSION['state']['product']['mode']=$mode;

    }

}



$product= new product($mode,$tag);




//exit;

if ($user->data['User Type']=='Supplier') {
    $data=array_pop($product->get_part_list());
    header('Location: part.php?sku='.$data['Part SKU']);
    exit;
}


$store= new store($product->data['Product Store Key']);


$smarty->assign('search_label',_('Products'));
$smarty->assign('search_scope','products');


$block_view=$_SESSION['state']['product']['block_view'];
$smarty->assign('block_view',$block_view);

$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$view_orders=$user->can_view('orders');

$create=$user->can_create('products');
$modify=$user->can_edit('products');
$modify_stock=$user->can_edit('product stock');
$smarty->assign('modify_stock',$modify_stock);
$view_suppliers=$user->can_view('suppliers');
$view_cust=$user->can_view('customers');

$smarty->assign('view_parts',$user->can_view('parts'));
$smarty->assign('view_suppliers',$view_suppliers);
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
$smarty->assign('create',$create);
$smarty->assign('modify',$modify);
$smarty->assign('view_orders',$view_orders);
$smarty->assign('view_customers',$view_cust);






get_header_info($user,$smarty);




$family_order=$_SESSION['state']['family']['products']['order'];
$family_period=$_SESSION['state']['family']['products']['period'];

//$family_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));
$smarty->assign('products_period',$family_period);
//$smarty->assign('family_period_title',$family_period_title[$family_period]);


// $_SESSION['views']['product_blocks'][5]=0;
// foreach($_SESSION['views']['product_blocks'] as $key=>$value){
//   $hide[$key]=($value==1?0:1);
// }
// //print_r($hide);


$smarty->assign('store',$store);
$smarty->assign('store_key',$store->id);
$smarty->assign('store_id',$store->id);

$display=$_SESSION['state']['product']['display'];

if ($product->data['Product First Sold Date']=='') {
// dont display_plot

}

$_SESSION['state']['product']['code_timeline']['code']=$product->data['Product Code'];

$product->load('part_location_list');
$smarty->assign('product',$product);
$smarty->assign('product_id',$product->data['Product Current Key']);
$smarty->assign('data',$product->data);

get_header_info($user,$smarty);


$web_status_error=false;
$web_status_error_title='';
if ($product->get('Product Web Configuration')=='Online For Sale') {
    if (!($product->get('Product Availability')>0)) {
        $web_status_error=true;
        $web_status_error_title=_('This product is out of stock');
    }
} else {
    if ($product->get('Product Availability')>0) {
        $web_status_error=true;
        $web_status_error_title=_('This product is not for sale on the webpage');
    }
}

$smarty->assign('web_status_error',$web_status_error);
$smarty->assign('web_status_error_title',$web_status_error_title);



$smarty->assign('parent','products');
$smarty->assign('title',$product->get('Product Code'));

$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('department',$product->get('Product Main Department Name'));
$smarty->assign('department_id',$product->get('Product Main Department Key'));
$smarty->assign('family',$product->get('Product Family Code'));
$smarty->assign('family_id',$product->get('Product Family Key'));

//$product->load_images_slidesshow();
//$images=$product->images_slideshow;
//$smarty->assign('div_img_width',190);
//$smarty->assign('img_width',190);
//$smarty->assign('images',$images);
//$smarty->assign('num_images',count($images));

$subject_id=$product->id;



//$smarty->assign('stock_table_options',array(_('Inv'),_('Pur'),_('Adj'),_('Sal'),_('P Sal')) );
//$smarty->assign('stock_table_options_tipo', $_SESSION['views']['stock_table_options'] );
$smarty->assign('table_title_orders',_('Orders'));
$smarty->assign('table_title_customers',_('Customers'));
$smarty->assign('table_title_stock',_('Stock History'));



$smarty->assign('key_filter_number',$regex['key_filter_number']);
$smarty->assign('key_filter_dimension',$regex['key_filter_dimension']);


$js_files[]= 'js/search.js';
$js_files[]= 'common_plot.js.php?page='.$page;

$js_files[]='product.js.php';


$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('web_status_menu',$_web_status);

$smarty->assign('display',$display);

$sql=sprintf("select * from `Product Page Bridge` where `Product ID`=%d", $product->pid);
//print $sql;
$result=mysql_query($sql);
if ($row=mysql_fetch_array($result)) {
    $page_key=$row['Page Key'];
    $type=$row['Type'];

    $sql=sprintf("select `Page URL` from `Page Dimension` where `Page Key`=%d", $page_key);
    $result=mysql_query($sql);
    if ($row=mysql_fetch_array($result))
        $url=$row['Page URL'];

    $web_site=array('url'=>$url, 'type'=>$type, 'available'=>true);

} else
    $web_site=array('available'=>false);

$smarty->assign('web_site',$web_site);


$tipo_filter=$_SESSION['state']['product']['customers']['f_field'];
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['product']['customers']['f_value']);
$filter_menu=array(
                 'name'=>array('db_key'=>'name','menu_label'=>_('Customer Name'),'label'=>_('Name')),
              //   'postcode'=>array('db_key'=>'postcode','menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
                 'country'=>array('db_key'=>'country','menu_label'=>_('Customer Country'),'label'=>_('Country')),


             );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);

$tipo_filter=$_SESSION['state']['product']['orders']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['product']['orders']['f_value']);
$filter_menu=array(
                 'public_id'=>array('db_key'=>'public_id','menu_label'=>_('Order Number'),'label'=>_('Number')),
              //   'postcode'=>array('db_key'=>'postcode','menu_label'=>_('Customer Postcode'),'label'=>_('Postcode')),
                 'customer_name'=>array('db_key'=>'customer_name','menu_label'=>_('Customer Name'),'label'=>_('Customer')),


             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$paginator_menu=array(10,25,50,100,500);
$smarty->assign('paginator_menu0',$paginator_menu);
$smarty->assign('paginator_menu1',$paginator_menu);
$smarty->assign('paginator_menu2',$paginator_menu);

$number_parts=$product->get_number_of_parts();
$smarty->assign('number_parts',$number_parts);

$order=$_SESSION['state']['family']['products']['order'];
if ($order=='code') {
    $order='`Product Code File As`';
    $order_label=_('Code');
} else {
     $order='`Product Code File As`';
    $order_label=_('Code');
}
$_order=preg_replace('/`/','',$order);
$sql=sprintf("select `Product ID` as id , `Product Code` as name from `Product Dimension`  where  `Product Family Key`=%d  and %s < %s  order by %s desc  limit 1",
             $product->data['Product Family Key'],
             $order,
             prepare_mysql($product->get($_order)),
             $order
            );

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $prev['link']='product.php?pid='.$row['id'];
    $prev['title']=$row['name'];
    $smarty->assign('prev',$prev);
}
mysql_free_result($result);


$sql=sprintf(" select `Product ID` as id , `Product Code` as name from `Product Dimension`  where  `Product Family Key`=%d    and  %s>%s  order by %s   ",
  $product->data['Product Family Key'],
             $order,
             prepare_mysql($product->get($_order)),
             $order
            );

$result=mysql_query($sql);
if ($row=mysql_fetch_array($result, MYSQL_ASSOC)) {
    $next['link']='product.php?pid='.$row['id'];
    $next['title']=$row['name'];
    $smarty->assign('next',$next);
}
mysql_free_result($result);



$smarty->assign('plot_tipo','store');

include_once('conf/period_tags.php');
unset($period_tags['hour']);
$smarty->assign('period_tags',$period_tags);

$family_order=$_SESSION['state']['family']['products']['order'];
$family_period=$_SESSION['state']['family']['products']['period'];

//$family_period_title=array('year'=>_('Last Year'),'quarter'=>_('Last Quarter'),'month'=>_('Last Month'),'week'=>_('Last Week'),'all'=>_('All'));
$smarty->assign('products_period',$family_period);



$smarty->display('product.tpl');
?>
