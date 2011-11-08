<?php
include_once('common.php');

$css_files=array(
               $yui_path.'reset-fonts-grids/reset-fonts-grids.css',
               $yui_path.'menu/assets/skins/sam/menu.css',
               $yui_path.'calendar/assets/skins/sam/calendar.css',
               $yui_path.'button/assets/skins/sam/button.css',
               'button.css',
               'container.css',


           );
		   
   

include_once('Theme.php');
$js_files=array(

              $yui_path.'utilities/utilities.js',
              $yui_path.'json/json-min.js',
              $yui_path.'paginator/paginator-min.js',
              $yui_path.'datasource/datasource-min.js',
              $yui_path.'autocomplete/autocomplete-min.js',
              $yui_path.'datatable/datatable.js',
              $yui_path.'container/container-min.js',
              $yui_path.'menu/menu-min.js',
              $yui_path.'calendar/calendar-min.js',
              'js/common.js',
              'js/table_common.js',
              'js/search.js',
              'external_libs/ampie/ampie/swfobject.js',

              //      'js/index_tools.js',
              'js/index.js',

              //    'js/index_sliding_tabs.js.php?slide='.$_SESSION['state']['home']['display'],
          );
		  

$smarty->assign('css_files',$css_files);
$smarty->assign('js_files',$js_files);

$page='Default';
if(isset($_REQUEST['page']) && $_REQUEST['page'])
	$page=$_REQUEST['page'];
$content='';
	
$sql=sprintf("select `Page Key` from `Page Dimension` where `Page URL`='%s'", $page);
$result=mysql_query($sql);
if($row=mysql_fetch_array($result)){
	$page_key=$row['Page Key'];
	
	$sql=sprintf("select `Page Help HTML Content` from `Page Help Dimension` where `Page Key`=%d", $page_key);
	$result=mysql_query($sql);
	if($row=mysql_fetch_Array($result)){
		$content=$row['Page Help HTML Content'];
	}
	
}
	

	
$smarty->assign('page',$page);
$smarty->assign('content',$content);
$smarty->display('help.tpl');

?>