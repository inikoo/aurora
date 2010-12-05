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
$sql=sprintf("select `Continent Code`,`Continent`,group_concat(`Country 2 Alpha Code`) as countries from kbase.`Country Dimension` group by `Continent Code`");
$res=mysql_query($sql);
$continent_data=array();
while($row=mysql_fetch_assoc($res)){
$continent_data[$row['Continent Code']]=array('countries'=>$row['countries'],'name'=>$row['Continent']);
}
$sql=sprintf("select `Continent Code`,`Country 2 Alpha Code`,`Country Name` from kbase.`Country Dimension` ");
$res=mysql_query($sql);
$countries_data=array();
while($row=mysql_fetch_assoc($res)){
$countries_data[]=array(
    'code'=>$row['Country 2 Alpha Code'],
    'url_code'=>$row['Continent Code'],
    'title'=>$continent_data[$row['Continent Code']]['name'],
    'link'=>$continent_data[$row['Continent Code']]['countries']
    );
}
$smarty->assign('map_data',$map_data);
$smarty->assign('view','continent');

$smarty->assign('countries_data',$countries_data);
$smarty->display('map_data_world_countries.tpl');

?>