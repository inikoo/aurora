<?
include_once('common.php');
$css_files=array(
		 $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
		 $yui_path.'menu/assets/skins/sam/menu.css',
		 //		 $yui_path.'datatable/assets/skins/sam/datatable.css',
		 // $yui_path.'container/assets/skins/sam/container.css',
		 $yui_path.'button/assets/skins/sam/button.css',
		 $yui_path.'build/assets/skins/sam/skin.css',
		 'common.css',
		 // 'container.css',
		 'table.css'
		 );
$js_files=array(
		$yui_path.'yahoo-dom-event/yahoo-dom-event.js',
		$yui_path.'connection/connection-min.js',
		$yui_path.'json/json-min.js',
		
		$yui_path.'element/element-beta-min.js',
		$yui_path.'paginator/paginator-min.js',
		$yui_path.'datasource/datasource-min.js',
		$yui_path.'autocomplete/autocomplete-min.js',
		$yui_path.'datatable/datatable.js',
		$yui_path.'utilities/utilities.js',
		$yui_path.'container/container-min.js',
		$yui_path.'button/button-min.js',
		$yui_path.'menu/menu-min.js',
		'js/common.js.php',
		'js/table_common.js.php',
		'js/users.js.php',
		'js/sha256.js.php',
		'js/passwordmeter.js.php',
		'js/edit_users.js.php'
		);
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);



$sql="select (select count(*) from liveuser_groups) as number_groups ,( select count(*) from liveuser_users) as number_users ";
$result = mysql_query($sql);
if(!$user=mysql_fetch_array($result, MYSQL_ASSOC))
  exit;

$smarty->assign('box_layout','yui-t4');



$smarty->assign('parent','users.php');
$smarty->assign('title', _('Users'));


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

//create user list
$sql=sprintf("select id,alias,(select count(*) from liveuser_users where tipo=1 and id_in_table=staff.id) as is_user from staff where active=1 order by alias");

$res = $db->query($sql);
$num_cols=5;
$staff=array();
while($row=$res->fetchrow()){
  $staff[]=array('alias'=>$row['alias'],'id'=>$row['id'],'is_user'=>$row['is_user']);
 }

//$staff= array_transverse($staff,$num_cols);
//print_r($staff);
foreach($staff as $key=>$_staff){
  $staff[$key]['mod']=fmod($key,$num_cols);
}


$smarty->assign('staff',$staff);
$smarty->assign('staff_cols',$num_cols);

$smarty->display('users.tpl');
?>