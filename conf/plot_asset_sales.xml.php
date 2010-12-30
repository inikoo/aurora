<?php
chdir("../");
include_once('common.php');
if (!isset($_REQUEST['tipo'])) {

    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('store_sales'):
if (!isset($_REQUEST['store_key'])) {
    exit;
}

$sql=sprintf("select `Store Name`,`Store Code` from `Store Dimension` where `Store Key` in (%s)",addslashes($_REQUEST['store_key']));
$res=mysql_query($sql);
$graphs_data=array();
$gid=0;
while($row=mysql_fetch_assoc($res)){
$graphs_data[]=array(
                    'gid'=>$gid,
                    'title'=>$row['Store Name'].' '._('Sales')
                    );
$gid++;
}
$data_args='tipo=store_sales&store_key='.$_REQUEST['store_key'];
break;

}
$smarty->assign('graphs_data',$graphs_data);

$smarty->assign('data_args',$data_args);

$smarty->display('plot_asset_sales.xml.tpl');
?>