<?php
chdir("../");
include_once('common.php');
if (!isset($_REQUEST['tipo'])) {

    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('site_requests'):
    if (!isset($_REQUEST['site_key'])) {
        exit;
    }

    $sql=sprintf("select *  from `Site Dimension` where `Site Key` in (%s)",addslashes($_REQUEST['site_key']));
    $res=mysql_query($sql);
    $graphs_data=array();
    $gid=0;
    while ($row=mysql_fetch_assoc($res)) {
        $graphs_data[]=array(
                           'gid'=>$gid,
                           'title'=>$row['Site Name'].' '._('Hits'),

                       );
        $gid++;
    }
    $data_args='tipo=site_requests&site_key='.$_REQUEST['site_key'];
    break;
case('number_of_contacts'):
 if (!isset($_REQUEST['store_key']) or $_REQUEST['store_key']=='') {
        exit;
    }
    $sql=sprintf("select *  from `Store Dimension` where `Store Key` in (%s)",addslashes($_REQUEST['store_key']));
    $res=mysql_query($sql);
    $graphs_data=array();
    $gid=0;
    if ($row=mysql_fetch_assoc($res)) {
        $graphs_data[]=array(
                           'gid'=>$gid,
                           'title'=>$row['Store Name'].' '._('Contacts'),

                       );
        $gid++;
    }else{
       exit;
    }
    $data_args='tipo=number_of_contacts&store_key='.$_REQUEST['store_key'];
break;
}


$smarty->assign('locale_data',localeconv());

$smarty->assign('graphs_data',$graphs_data);

$smarty->assign('data_args',$data_args);

$smarty->display('plot_general_timeseries.xml.tpl');
?>