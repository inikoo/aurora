<?php
/*
 

 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

require_once 'common.php';
require_once 'class.Site.php';
require_once 'ar_common.php';

if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}

$tipo=$_REQUEST['tipo'];
switch ($tipo) {

case('is_page_store_code'):
  $data=prepare_values($_REQUEST,array(
                             'site_key'=>array('type'=>'key'),
                             'query'=>array('type'=>'string')
                         ));
is_page_store_code($data);
break;
default:

    $response=array('state'=>404,'msg'=>_('Operation not found'));
    echo json_encode($response);

}


function is_page_store_code($data) {


    if (!isset($data['query']) or !isset($data['site_key']) ) {
        $response= array(
                       'state'=>400,
                       'msg'=>'Error'
                   );
        echo json_encode($response);
        return;
    } else
        $query=$data['query'];
    if ($query=='') {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

    $site_key=$data['site_key'];

    $sql=sprintf("select PS.`Page Code`,PS.`Page Key`,`Page URL`  from `Page Store Dimension` PS left join `Page Dimension` P  on (PS.`Page Key`=P.`Page Key`) where `Page Site Key`=%d and `Page Code`=%s  "
                 ,$site_key
                 ,prepare_mysql($query)
                );

    $res=mysql_query($sql);

    if ($data=mysql_fetch_array($res)) {
        $msg=sprintf('A page in this site (<a href="%s">%s</a>) already has this code (%s)'
            
                     ,$data['Page URL']
                     ,$data['Page URL']
                      ,$data['Page Code']
                    );
        $response= array(
                       'state'=>200,
                       'found'=>1,
                       'msg'=>$msg
                   );
        echo json_encode($response);
        return;
    } else {
        $response= array(
                       'state'=>200,
                       'found'=>0
                   );
        echo json_encode($response);
        return;
    }

}

