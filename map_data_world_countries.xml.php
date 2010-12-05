<?php
include_once('common.php');

$map_data=array('map_file'=>"maps/world.swf",
'tl_long'=>"-168.49",
'tl_lat'=>"83.63",
'br_long'=>"190.3",
'br_lat'=>"-55.58",
'zoom_x'=>"0%",
'zoom_y'=>"0%",
'zoom'=>"100%");

$sql=sprintf("select `Country 2 Alpha Code`,`Country Name` from kbase.`Country Dimension`");
$res=mysql_query($sql);
$countries_data=array();
while($row=mysql_fetch_assoc($res)){
$countries_data[]=array('code'=>$row['Country 2 Alpha Code'],'title'=>$row['Country Name'],'');
}
$smarty->assign('map_data',$map_data);

$smarty->assign('countries_data',$countries_data);
$smarty->display('map_data_world_countries.tpl');

?>