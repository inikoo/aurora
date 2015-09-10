<?php
/*
file: map.php
 */
include_once('common.php');
$ammap_path='external_libs/ammap_2.5.5';
$smarty->assign('ammap_path',$ammap_path);




if (isset($_REQUEST['country']))
    $tipo='country';
if (isset($_REQUEST['world']))
    $tipo='world';


$smarty->assign('path',$ammap_path);
switch ($tipo) {
case('world'):

$data_file='maps/world_countries.xml';
if(isset($_REQUEST['view'])){
if($_REQUEST['view']=='wregions')
$data_file='maps/world_wregions.xml';
}
 $smarty->assign('settings_file','conf/world_map_settings.xml');
    $smarty->assign('data_file',$data_file);
    $smarty->display('map.tpl');

    break;

case('country'):

    $code=$_REQUEST['country'];
    $opt='';
    if (isset($_REQUEST['opt'])) {
        $opt=$_REQUEST['opt'];
    }

    
    
    $smarty->assign('settings_file','conf/country_map_settings.xml');
    $smarty->assign('data_file',$ammap_path.'/data/country/'.$code.$opt.'.xml');
    $smarty->display('map.tpl');


    break;

}



?>
