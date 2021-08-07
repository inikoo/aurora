<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 16 August 2017 at 15:25:31 CEST , Tranava, Slovakia

 Copyright (c) 2014, Inikoo

 Version 2.0
*/

chdir('../');
require_once 'vendor/autoload.php';

$log_from_token=false;
if(isset($_REQUEST['sak'])){
    include 'keyring/key.php';
    include_once 'utils/general_functions.php';
    $key = md5('82$je&4WN1g2B^{|bRbcEdx!Nz$OAZDI3ZkNs[cm9Q1)8buaLN'.SKEY);
    $auth_data = json_decode(safeDecrypt(urldecode($_REQUEST['sak']), $key),true);
    if( !(isset($auth_data['auth_token']['logged_in']) and  $auth_data['auth_token']['logged_in']) ){
        unset($auth_data);
    }else{
        $log_from_token=true;
    }
}

require_once 'common.php';
/** @var User $user */
if ($user->get('User View') == 'Staff' or $log_from_token) {
    require_once 'invoice.pdf.common.php';
}



