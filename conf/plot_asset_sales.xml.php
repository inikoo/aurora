<?php

chdir("../");
include_once('common.php');
if (!isset($_REQUEST['tipo'])) {

    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('store_sales'):

    $colors=array('0033CC','0099CC','00CC99','00CC33','CC9900');

    if (!isset($_REQUEST['store_key'])) {
        exit;
    }
    $tmp=preg_split('/\|/', $_REQUEST['store_key']);
    $stores_keys=array();
    foreach($tmp as $store_key) {

        if (is_numeric($store_key) and in_array($store_key, $user->stores)) {
            $stores_keys[]=$store_key;
        }
    }

    $use_corporate=0;

//print_r($tmp);
//print_r($user->stores);

//print_r($stores_keys);
    $staked=false;
    if (isset($_REQUEST['stacked']) and $_REQUEST['stacked'])$staked=true;



    $graphs_data=array();
    $gid=0;
    if ($staked) {
        $sql=sprintf("select `Store Name`,`Store Code`,`Store Currency Code` from `Store Dimension` where `Store Key` in (%s)",addslashes(join(',',$stores_keys)));
        $res=mysql_query($sql);

        while ($row=mysql_fetch_assoc($res)) {
            $graphs_data[]=array(
                               'gid'=>$gid,
                               'title'=>$row['Store Code'],
                               'currency_code'=>$corporate_currency,
                               'color'=>$colors[$gid]
                           );
            $gid++;
        }
        $data_args='tipo=stacked_store_sales&store_key='.join(',',$stores_keys);
        $template='plot_stacked_asset_sales.xml.tpl';

    } else {// no stakecked


        $sql=sprintf("select `Store Name`,`Store Code`,`Store Currency Code` from `Store Dimension` where `Store Key` in (%s)",addslashes(join(',',$stores_keys)));
        $res=mysql_query($sql);
        $title='';
        $currencies=array();
        while ($row=mysql_fetch_assoc($res)) {
            $title.=','.$row['Store Code'];


            $currency_code=$row['Store Currency Code'];
            $currencies[$currency_code]=1;

        }


        if (count($currencies)>1)
            $use_corporate=1;




        $graphs_data[]=array(
                           'gid'=>0,
                           'title'=>$title.' '._('Sales'),
                           'currency_code'=>($use_corporate?$corporate_currency:$currency_code)
                       );
        $data_args='tipo=store_sales&store_key='.join(',',$stores_keys).'&use_corporate='.$use_corporate;
        
        $template='plot_asset_sales.xml.tpl';

    }




    if (isset($_REQUEST['from'])) {
        $smarty->assign('from',$_REQUEST['from']);

        $data_args.=sprintf("&from=%s",$_REQUEST['from']);
    }
    if (isset($_REQUEST['to'])) {
        $smarty->assign('to',$_REQUEST['to']);

        $data_args.=sprintf("&to=%s",$_REQUEST['to']);
    }

    break;

}

$smarty->assign('locale_data',localeconv());
$smarty->assign('graphs_data',$graphs_data);
$smarty->assign('data_args',$data_args);
$smarty->display($template);
?>