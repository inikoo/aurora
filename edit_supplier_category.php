<?php
include_once('class.Category.php');

include_once('common.php');
include_once('assets_header_functions.php');



if (!$user->can_view('stores')  ) {
    header('Location: index.php');
    exit;
}




$modify=$user->can_edit('stores');
if (!$modify) {
    header('Location: supplier_categories.php');
}

get_header_info($user,$smarty);
$general_options_list=array();

$view=$_SESSION['state']['categories']['edit'];
$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'button/assets/skins/sam/button.css',
               
               'css/container.css',
               'button.css'

           );

$css_files[]='theme.css.php';



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
              'search.js',
              'js/edit_common.js',
              'js/dropdown.js',
              'js/edit_category_common.js?subject="Supplier"'
          );
$smarty->assign('css_files',$css_files);

$smarty->assign('subject','Supplier');

if (isset($_REQUEST['id'])) {
    $category_key=$_REQUEST['id'];


} else {
    $category_key=$_SESSION['state']['supplier_categories']['category_key'];
}
$_SESSION['state']['supplier_categories']['category_key']=$category_key;


if (!$category_key) {
    $category_key=0;
    $view='subcategory';
    $_SESSION['state']['categories']['edit']=$view;


if (isset($_REQUEST['store_id']) and is_numeric($_REQUEST['store_id']) ) {
    $store_id=$_REQUEST['store_id'];

} else {
    $store_id=$_SESSION['state']['store']['id'];
}



    $general_options_list[]=array('tipo'=>'url','url'=>'supplier_categories.php?store_id='.$store_id.'&id=0','label'=>_('Exit Edit'));
    $general_options_list[]=array('tipo'=>'js','id'=>'new_category','label'=>_('Add Category'));



} else {



    $category=new Category($category_key);
    if (!$category->id) {
        header('Location: supplier_categories.php?id=0&error=cat_not_found');
        exit;

    }
    $category_key=$category->id;


    if ($modify) {
        $general_options_list[]=array('tipo'=>'url','url'=>'supplier_categories.php?id='.$category->id,'label'=>_('Exit Edit'));
        $general_options_list[]=array('tipo'=>'js','id'=>'new_category','label'=>_('Add Subcategory'));

    }



    $smarty->assign('category',$category);


    $tpl_file='supplier_category.tpl';
$store_id=$category->data['Category Store Key'];


}




$_SESSION['state']['categories']['subject']='Supplier';

$_SESSION['state']['categories']['parent_key']=$category_key;
$_SESSION['state']['categories']['subject_key']=false;


$js_files[]='edit_supplier_category.js.php?key='.$category_key;
$smarty->assign('js_files',$js_files);
$smarty->assign('category_key',$category_key);

$smarty->assign('general_options_list',$general_options_list);
$smarty->assign('edit',$view);

$smarty->display('edit_supplier_category.tpl');
?>
