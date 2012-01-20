<?php
include_once('common.php');
//include_once('stock_functions.php');
include_once('class.SupplierProduct.php');
include_once('class.Supplier.php');

$view_suppliers=$user->can_view('suppliers');


$product_supplier_key=(isset($_REQUEST['pid'])?$_REQUEST['pid']:$_SESSION['state']['supplier_product']['pid']);

if (!$product_supplier_key) {
    header('Location: suppliers.php?e');
    exit();
}


$supplier_product= new SupplierProduct('pid',$product_supplier_key);
if (!$supplier_product->id) {
    header('Location: suppliers.php');
    exit;

}

 
$supplier_key=$supplier_product->supplier_key;
if ($user->data['User Type']=='Supplier') {

    if (!in_array($supplier_key,$user->suppliers)) {
        header('Location: suppliers.php?e');
        exit();

    }

} else if (!$view_suppliers) {
    header('Location: index.php');
    exit();
}




$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'button/assets/skins/sam/button.css',
               $yui_path.'assets/skins/sam/autocomplete.css',
               'common.css',
               'css/container.css',
               'button.css',
               'table.css'
           );
$css_files[]='theme.css.php';
$js_files=array(
              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable-debug.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              'js/common.js',
              'external_libs/amstock/amstock/swfobject.js',
              'js/search.js',
              'js/table_common.js',
          );

$smarty->assign('display',$_SESSION['state']['supplier_product']['display']);

$smarty->assign('pid',$supplier_product->id);

$supplier_product_code=$supplier_product->code;
$supplier=new Supplier($supplier_product->data['Supplier Key']);

$_SESSION['state']['supplier_product']['code']=$supplier_product_code;
$_SESSION['state']['supplier_product']['supplier_key']=$supplier_key;
$_SESSION['state']['supplier_product']['pid']=$supplier_product->pid;
$_SESSION['state']['supplier_product']['id']=$supplier_product->id;



$smarty->assign('show_stock_history_chart',$_SESSION['state']['supplier_product']['show_stock_history_chart']);
$smarty->assign('stock_history_type',$_SESSION['state']['part']['stock_history']['type']);


$modify=$user->can_edit('suppliers');
$general_options_list=array();
if ($modify)
    $general_options_list[]=array('tipo'=>'url','url'=>'edit_supplier_product.php','label'=>_('Edit Supplier Product'));
$smarty->assign('general_options_list',$general_options_list);


$smarty->assign('search_label',_('Search'));
$smarty->assign('search_scope','supplier_products');
$smarty->assign('block_view',$_SESSION['state']['supplier_product']['block_view']);


$smarty->assign('supplier_product',$supplier_product);
$smarty->assign('supplier',$supplier);

$smarty->assign('parent','suppliers');
$smarty->assign('title',$supplier_product->get('Supplier Product Code'));



$js_files[]= 'js/search.js';
$js_files[]='supplier_product.js.php';



$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

//$parts=$product_suppliir->get_parts();


$part_skus=$supplier_product->get_part_skus();


$part_sku=false;
if ($supplier_product->data['Supplier Product Part Convertion']=='1:1') {
    $part_sku=array_pop($part_skus);
    $smarty->assign('part_sku',$part_sku);

}


$supplier_product->load_images_slidesshow();
$images=$supplier_product->images_slideshow;
$smarty->assign('div_img_width',190);
$smarty->assign('img_width',190);
$smarty->assign('images',$images);
$smarty->assign('num_images',count($images));

$smarty->assign('supplier_id',$supplier_key);


$tipo_filter=$_SESSION['state']['part']['stock_history']['f_field'];
$smarty->assign('filter_show1',$_SESSION['state']['part']['stock_history']['f_show']);
$smarty->assign('filter1',$tipo_filter);
$smarty->assign('filter_value1',$_SESSION['state']['part']['transactions']['f_value']);
$filter_menu=array(
                 'note'=>array('db_key'=>'note','menu_label'=>_('Note'),'label'=>_('Note')),
                 'location'=>array('db_key'=>'location','menu_label'=>_('Location'),'label'=>_('Location')),
             );
$smarty->assign('filter_menu1',$filter_menu);
$smarty->assign('filter_name1',$filter_menu[$tipo_filter]['label']);

$tipo_filter=$_SESSION['state']['supplier_product']['porders']['f_field'];
$smarty->assign('filter0',$tipo_filter);
$smarty->assign('filter_value0',$_SESSION['state']['supplier_product']['porders']['f_value']);
$filter_menu=array(
                 'public_id'=>array('db_key'=>'public_id','menu_label'=>_('Id'),'label'=>_('Id')),
             );
$smarty->assign('filter_menu0',$filter_menu);
$smarty->assign('filter_name0',$filter_menu[$tipo_filter]['label']);


$smarty->display('supplier_product.tpl');




?>
