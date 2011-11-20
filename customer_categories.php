<?php
include_once('class.Store.php');

include_once('class.Category.php');

include_once('common.php');
include_once('assets_header_functions.php');



if (!$user->can_view('stores')  ) {
    header('Location: index.php');
    exit;
}
$view_sales=$user->can_view('product sales');
$view_stock=$user->can_view('product stock');
$smarty->assign('view_parts',$user->can_view('parts'));
$smarty->assign('view_sales',$view_sales);
$smarty->assign('view_stock',$view_stock);
//$modify=false;
$modify=$user->can_edit('customers');

get_header_info($user,$smarty);
$general_options_list=array();


$smarty->assign('view',$_SESSION['state']['customer_categories']['view']);

$smarty->assign('search_label',_('Customers'));
$smarty->assign('search_scope','customers');


$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
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
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-min.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'js/edit_category_common.js',
              'common_customers.js.php',
              'external_libs/ammap/ammap/swfobject.js'
          );


$smarty->assign('options_box_width','200px');

if (isset($_REQUEST['id'])) {
    $category_key=$_REQUEST['id'];
} else {
    $category_key=$_SESSION['state']['customer_categories']['category_key'];
}

if (!$category_key) {

    if (isset($_REQUEST['store_id']) and is_numeric($_REQUEST['store_id']) ) {
        $store_id=$_REQUEST['store_id'];

    } else {
        $store_id=$_SESSION['state']['store']['id'];
    }

    $store=new Store($store_id);


    $js_files[]='customer_categories_base.js.php';
    $tpl_file='customer_categories_base.tpl';

} else {

    $category=new Category($category_key);
    if (!$category->id) {
        header('Location: customer_categories.php?id=0&error=cat_not_found');
        exit;

    }

    $store_id=$category->data['Category Store Key'];
    if (isset($_REQUEST['store_id']) and is_numeric($_REQUEST['store_id']) ) {
        $store_id=$_REQUEST['store_id'];

    } else {
        $store_id=$_SESSION['state']['store']['id'];
    }

    $store=new Store($store_id);

    $category_key=  $category->id;

    $general_options_list[]=array('tipo'=>'url','url'=>'customers_lists.php?store='.$store->id,'label'=>_('Lists'));
    $general_options_list[]=array('tipo'=>'url','url'=>'search_customers.php?store='.$store->id,'label'=>_('Advanced Search'));
    $general_options_list[]=array('tipo'=>'url','url'=>'customers_stats.php','label'=>_('Stats'));
    $general_options_list[]=array('tipo'=>'url','url'=>'customers.php?store='.$store->id,'label'=>_('Customers'));

    if ($modify) {
        $general_options_list[]=array('class'=>'edit','tipo'=>'js','id'=>'new_category','label'=>_('Add Subcategory'));
        $general_options_list[]=array('class'=>'edit','tipo'=>'url','url'=>'edit_customer_category.php?&id='.$category->id,'label'=>_('Edit Category'));

    }

    $block_view=$_SESSION['state']['customer_categories']['block_view'];


    if ($category->data['Category Children Deep']==0) {
        $block_view='subjects';
    }
    $smarty->assign('block_view',$block_view);
    $smarty->assign('category',$category);

    if ($category->data['Category Deep']>1) {
        $parent_category=new Category($category->data['Category Parent Key']);
        $smarty->assign('parent_category',$parent_category);
    }


    $js_files[]='customer_categories.js.php';
    $tpl_file='customer_category.tpl';

//print_r($category->data);

}


$_SESSION['state']['customer_categories']['category_key']=$category_key;


$store=new Store($store_id);

if (!$store->id) {

    exit("Error wrong store");
}

$_SESSION['state']['store']['id']=$store->id;
$smarty->assign('store',$store);
$smarty->assign('store_id',$store->id);


$smarty->assign('subject','Customer');
//$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('category_key',$category_key);
$smarty->assign('store_id',$store_id);
$smarty->assign('options_box_width','600px');

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);
$smarty->display($tpl_file);
?>
