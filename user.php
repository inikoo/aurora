<?
include_once('common.php');
include_once ('header.php');
if(!(isset($_REQUEST['id']) and is_numeric($_REQUEST['id']))){exit();}
$id=$_REQUEST['id'];
$sql="select * from liveuser_users as u where u.authuserid=".$id;
$result = mysql_query($sql);
if($user=mysql_fetch_array($result, MYSQL_ASSOC)){
  
  
  
  $rightmenu='';
  $content='';
 

$page = new HTML_Page2();
$page->setTitle($myconf['sname'].' '._('User'));
$page->setBodyAttributes(array('class'=>' yui-skin-sam'));
$page->addStyleSheet('yui/2.5.0/build/reset-fonts-grids/reset-fonts-grids.css');
$page->addStyleSheet('yui/2.5.0/build/menu/assets/skins/sam/menu.css');
$page->addStyleSheet('common.css');





$page->addScript('yui/2.5.0/build/yahoo-dom-event/yahoo-dom-event.js');
$page->addScript('yui/2.5.0/build/utilities/utilities.js');
$page->addScript('yui/2.5.0/build/container/container.js');
$page->addScript('yui/2.5.0/build/menu/menu.js');

$page->addScript('js/common.js');

$page->addBodyContent(
'
<div id="doc4"  class="yui-t6">
   <div id="hd" >'.$header.'</div> 
   <div id="bd" >
<div id="yui-main">
<div class="yui-b">'.$content.'</div>
</div>
<div id="rmenu" class="yui-b">'.$rightmenu.'</div>
</div> 
<div id="ft">'.footer($start_timer).'</div> 
</div>'.$lang_menu

);


$page->display();
 }
?>