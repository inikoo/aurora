<?php
/*
 About:
 Autor: Raul Perusquia <rulovico@gmail.com>

 Copyright (c) 2011, Inikoo

 Version 2.0
*/

equire_once 'common.php';
require_once 'class.Site.php';
require_once 'class.PageHeader.php';
require_once 'class.PageFooter.php';

require_once 'ar_edit_common.php';

if (!isset($_REQUEST['tipo'])) {
    $response=array('state'=>405,'msg'=>_('Non acceptable request').' (t)');
    echo json_encode($response);
    exit;
}



$tipo=$_REQUEST['tipo'];
switch ($tipo) {


default:

    $response=array('state'=>404,'msg'=>_('Operation not found'));
    echo json_encode($response);
}




function connect_ftp($ftp_server,$ftp_user,$ftp_pass,$ftp_directory='',$ftp_passive=true) {

    if ($conn_id = ftp_ssl_connect($ftp_server)) {
        if ($login_result = ftp_login($conn_id, $ftp_user, $ftp_pass)) {

            
            ftp_pasv( $conn_id, $ftp_passive );

        }
    }


}




?>