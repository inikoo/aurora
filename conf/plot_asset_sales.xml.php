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

    $sql=sprintf("select `Store Name`,`Store Code`,`Store Currency Code` from `Store Dimension` where `Store Key` in (%s)",addslashes($_REQUEST['store_key']));
    $res=mysql_query($sql);
    $graphs_data=array();
    $gid=0;
    while ($row=mysql_fetch_assoc($res)) {
        $graphs_data[]=array(
                           'gid'=>$gid,
                           'title'=>$row['Store Name'].' '._('Sales'),
                           'currency_code'=>$row['Store Currency Code']
                       );
        $gid++;
    }
    $data_args='tipo=store_sales&store_key='.$_REQUEST['store_key'];
   
   if (isset($_REQUEST['from'])) {
        $smarty->assign('from',$_REQUEST['from']);

        //$data_args.=sprintf("&from=%s",$_REQUEST['from']);
    }
    if (isset($_REQUEST['to'])) {
        $smarty->assign('to',$_REQUEST['to']);

        //$data_args.=sprintf("&to=%s",$_REQUEST['to']);
    }
    
    break;

}

$smarty->assign('locale_data',localeconv());
$smarty->assign('graphs_data',$graphs_data);
$smarty->assign('data_args',$data_args);
$smarty->display('plot_asset_sales.xml.tpl');
?>