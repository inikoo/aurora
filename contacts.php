<?
include_once('common.php');
$_SESSION['views']['assets']='index';

$_SESSION['new_contact']=array();

$sql="select count(*) as numberof from product";
$result =& $db->query($sql);
if(!$products=$result->fetchRow())
  exit;


$smarty->assign('box_layout','yui-t4');


$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'tabview/assets/skins/sam/tabview.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'editor/assets/skins/sam/simpleeditor.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'tabview.css',
		 'table.css'
		 
		 );
$js_files=array(
		$yui_path.'yahoo-dom-event/yahoo-dom-event.js',
		$yui_path.'element/element-beta-min.js',
		$yui_path.'utilities/utilities.js',
		$yui_path.'tabview/tabview-min.js',
		$yui_path.'container/container.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'button/button.js',
		$yui_path.'editor/simpleeditor-beta.js',
		$yui_path.'autocomplete/autocomplete.js',
		$yui_path.'datasource/datasource-beta.js',
		$yui_path.'datatable/datatable-beta.js',
		$yui_path.'json/json-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/contacts.js.php'
		);

$sql="select id from country order by name";
$result =& $db->query($sql);
while($row=$result->fetchRow()){
  $country[$row['id']]=$_country[$row['id']];
 }




$smarty->assign('default_country',$default_country);
$smarty->assign('default_country_encoded',urlencode($default_country));


$smarty->assign('default_country_id',$default_country_id);
$smarty->assign('email_tipo',$_tipo_email);
$smarty->assign('address_tipo',$_tipo_address);
$smarty->assign('tel_tipo',$_tipo_tel);
$smarty->assign('prefix',$_prefix);

$smarty->assign('country',$country);

$smarty->assign('parent','customers.php');
$smarty->assign('title', _('Contacts'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$smarty->assign('table_title',_('Contacts'));
$smarty->display('contacts.tpl');



?>