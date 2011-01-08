<?php
chdir("../");
include_once('common.php');
if (!isset($_REQUEST['tipo'])) {

    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('number_of_customers'):
if (!isset($_REQUEST['store_key'])) {
    exit;
}

$sql=sprintf("select `Store Name`,`Store Code`,`Store Currency Code` from `Store Dimension` where `Store Key` in (%s)",addslashes($_REQUEST['store_key']));
$res=mysql_query($sql);
$graphs_data=array();
$gid=0;
while($row=mysql_fetch_assoc($res)){
$graphs_data[]=array(
                    'gid'=>$gid,
                    'title'=>$row['Store Name'].': '._('Number of Active Customers')
                   
                    );
$gid++;
}
$data_args='tipo=number_of_customers&store_key='.$_REQUEST['store_key'];
break;

}


$smarty->assign('locale_data',localeconv());

$smarty->assign('graphs_data',$graphs_data);

$smarty->assign('data_args',$data_args);

$smarty->display('plot_general_candlestick.xml.tpl');
?>