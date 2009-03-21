<?
include_once('common.php');
include_once('stock_functions.php');



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
		'js/passwordmeter.js.php',
		'js/sha256.js.php',
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
		'js/editcontact.js.php'
		
		);




$tipo='person';
if(isset($_REQUEST['tipo']) and $_REQUEST['tipo']=='company')
  $tipo=$_REQUEST['tipo'];
$parent='contacts';
if(isset($_REQUEST['from']) and $_REQUEST['from']='supplier' ) 
  $parent=$_REQUEST['from'];

   
switch($tipo){
 case('person'):
   $title=_('New Contact');
   $ftipo=_('Contact (Person)');
   break;
 case('company'):
   $title=_('New Contact');
   $ftipo=_('New Contact (Company)');


 }

$smarty->assign('parent',$parent);
$smarty->assign('title', $title);
$smarty->assign('tipo', $tipo);
$smarty->assign('ftipo', $ftipo);

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);


$smarty->display('new_contact.tpl');





?>

