<?
include_once('common.php');

$sql="select (select count(*) from liveuser_groups) as number_groups ,( select count(*) from liveuser_users) as number_users ";
$result = mysql_query($sql);
if(!$user=mysql_fetch_array($result, MYSQL_ASSOC))
  exit;

$smarty->assign('box_layout','yui-t4');

$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 'common.css',
		 'container.css',
		 'button.css',
		 'table.css'
		 );
$js_files=array(
		'js/passwordmeter.js.php',
		'js/sha256.js.php',
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
		'js/users.js.php'
		);

$smarty->assign('parent','users.php');
$smarty->assign('title', _('Users'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$sql="select id from lang";
$newuser_langs=array();
$res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
while($row=$res->fetchRow()) {
  $newuser_langs[$row['id']]=$_lang[$row['id']];
 }
$smarty->assign('newuser_langs',$newuser_langs);

$sql="select group_id as id from liveuser_groups";
$newuser_groups=array();
$res = $db->query($sql); if (PEAR::isError($res) and DEBUG ){die($res->getMessage());}
while($row=$res->fetchRow()) {
  $newuser_groups[$row['id']]=$_group[$row['id']];
 }
$smarty->assign('newuser_groups',$newuser_groups);



$smarty->display('users.tpl');
?>