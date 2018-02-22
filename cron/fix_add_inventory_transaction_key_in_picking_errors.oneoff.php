<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:22 February 2018 at 12:21:42 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

if (function_exists('mysql_connect')) {


    $default_DB_link = @mysql_connect($dns_host, $dns_user, $dns_pwd);
    if (!$default_DB_link) {
        print "Error can not connect with database server\n";
    }
    $db_selected = mysql_select_db($dns_db, $default_DB_link);
    if (!$db_selected) {
        print "Error can not access the database\n";
        exit;
    }
    mysql_set_charset('utf8');
    mysql_query("SET time_zone='+0:00'");
}

require_once 'utils/aes.php';


require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';

require_once 'class.Account.php';

require_once 'class.Customer.php';
require_once 'class.Store.php';
require_once 'class.Warehouse.php';
require_once 'class.Part.php';
require_once 'class.Material.php';
require_once 'class.Page.php';
require_once 'class.DeliveryNote.php';

require_once 'class.Product.php';
include_once 'utils/parse_materials.php';
include_once 'utils/object_functions.php';


$account = new Account();


$sql = sprintf(
    "SELECT OPTD.`Delivery Note Key`,`Order Post Transaction Key`,ITF.`Inventory Transaction Key`,`Reason` FROM `Order Post Transaction Dimension`  OPTD LEFT JOIN `Order Transaction Fact` OTF ON (OTF.`Order Transaction Fact Key`=OPTD.`Order Transaction Fact Key`) LEFT JOIN `Inventory Transaction Fact` ITF  ON (`Map To Order Transaction Fact Key`=OTF.`Order Transaction Fact Key`)   "
);

//print $sql;

if ($result3 = $db->query($sql)) {
    foreach ($result3 as $row3) {

        if($row3['Inventory Transaction Key']>0){

            $picker_fault=0;
            $packer_fault=0;

            $picker_dn_key='';
            $packer_dn_key='';

            //'Other','Damaged','Missing','Do Not Like','Unknown'
            if(in_array($row3['Reason'],array('Other','Missing','Unknown'))){
                $picker_fault=1;
                $picker_dn_key=$row3['Delivery Note Key'];
            }
            if(in_array($row3['Reason'],array('Other','Missing','Damaged','Unknown'))){
                $packer_fault=1;
                $packer_dn_key=$row3['Delivery Note Key'];
            }

            $sql = sprintf(
                'UPDATE `Order Post Transaction Dimension` SET `Inventory Transaction Key`=%d ,`Picker Fault`=%f ,`Packer Fault`=%f ,`Picker Fault Delivery Note Key`=%s ,`Packer Fault Delivery Note Key`=%s  WHERE `Order Post Transaction Key`=%d   ' ,
                $row3['Inventory Transaction Key'], $picker_fault,$packer_fault,
                prepare_mysql($picker_dn_key),
                prepare_mysql($packer_dn_key),
                $row3['Order Post Transaction Key']


            );
        }else{
            $sql = sprintf(
                'UPDATE `Order Post Transaction Dimension` SET `Inventory Transaction Key`=NULL  ,`Picker Fault`=0 ,`Packer Fault`=0 ,`Customer Services Fault`=0 WHERE `Order Post Transaction Key`=%d   ', $row3['Order Post Transaction Key']


            );
        }


        $db->exec($sql);

    }


} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}

?>