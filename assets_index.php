<?

//header('Location: http://www.example.com/');


include_once('common.php');

$q='';
if(isset($_REQUEST['search']) and $_REQUEST['search']!=''  ){
  // SEARCH!!!!!!!!!!!!
  $q=$_REQUEST['search'];
  //  print "$q";
  $sql=sprintf("select id from product where code='%s' ",addslashes($q));
  $result =& $db->query($sql);
  if($found=$result->fetchRow()){
    header('Location: assets_product.php?id='. $found['id']);
    exit;
  }
  
//   $sql=sprintf("select id from product_group where code='%s' ",addslashes($q));
//   $result =& $db->query($sql);
//   if($found=$result->fetchRow()){
//     header('Location: assets_family.php?id='. $found['id']);
//     exit;
//   }
  $_SESSION['tables']['pindex_list'][5]='p.code';
  $_SESSION['tables']['pindex_list'][6]=$q;

 }



$_SESSION['views']['assets']='index';




print_r($_SESSION['tables']['pindex_list']);





$sql="select count(*) as numberof from product";
$result =& $db->query($sql);
if(!$products=$result->fetchRow())
  exit;


$smarty->assign('box_layout','yui-t0');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'container.css',
		 'table.css'
		 );
$js_files=array(
		$yui_path.'yahoo-dom-event/yahoo-dom-event.js',
		$yui_path.'element/element-beta-min.js',
		$yui_path.'utilities/utilities.js',
		$yui_path.'container/container.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'button/button.js',
		$yui_path.'autocomplete/autocomplete.js',
		$yui_path.'datasource/datasource-beta.js',
		$yui_path.'datatable/datatable-beta.js',
		$yui_path.'json/json-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/assets_index.js.php?products='.$products['numberof']
		);




$smarty->assign('parent','assets_tree.php');
$smarty->assign('title', _('Product Index'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$product_home="Products Home";
$smarty->assign('home',$product_home);
$smarty->assign('filter','p.code');
$smarty->assign('filter_name',_('Product code'));


$smarty->assign('total_products',$products['numberof']);
//$smarty->assign('rpp',$_SESSION['tables']['pindex_list'][2]);

//$smarty->assign('products_perpage',$_SESSION['tables']['pindex_list'][2]);



$tipo_filter=($_SESSION['tables']['pindex_list'][5]);
$smarty->assign('filter',$tipo_filter);
$smarty->assign('filter_value',$_SESSION['tables']['pindex_list'][6]);

switch($tipo_filter){
 case('p.code'):
   $filter_text=_('Product Code');
   break;
 case('g.name'):
   $filter_text=_('Family Code');
   break;
 case('d.code'):
   $filter_text=_('Department Name');
   break;
 case('p.description'):
   $filter_text=_('Description');
   break;
 default:
   $filter_text='?';
 }





$smarty->display('assets_index.tpl');
?>