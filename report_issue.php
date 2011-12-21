<?php
include_once('common.php');


if(isset($_REQUEST['t']) and $_REQUEST['t']=='feature'){
$type='feature';

//$email='raul@ancientwisdom.biz';
}else{
$type='bug';
//$email='raul@ancientwisdom.biz';
}
$metadata="Client: ".$myconf['name']."\n";
$metadata.="User: ".$user->data['User Alias']." (".$user->id.")\n";
$metadata.="IP: ".ip()."\n";
$metadata.="Referrer: ".$_SERVER['HTTP_REFERER']."\n";
$metadata.="Agent: ".$_SERVER['HTTP_USER_AGENT']."\n";

$smarty->assign('type',$type);
$smarty->assign('metadata',$metadata);
//$smarty->assign('email',$email);

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
		$yui_path.'datatable/datatable-min.js',
		$yui_path.'container/container-min.js',
		$yui_path.'menu/menu-min.js',
		$yui_path.'calendar/calendar-min.js',
		'js/common.js',
		'report_issue.js.php'
		);


$smarty->assign('parent','home');
$smarty->assign('title', _('Issues'));
$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);




$smarty->display('report_issue.tpl');





?>

