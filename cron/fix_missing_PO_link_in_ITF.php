<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 December 2017 at 13:01:18 GMT, Sheffield, UK
 Copyright (c) 2017, Inikoo

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

require_once 'class.Product.php';
include_once 'utils/parse_materials.php';
include_once 'utils/object_functions.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$account = new Account();


$sql=sprintf('Select `Purchase Order Transaction Fact Key`,`Metadata` from `Purchase Order Transaction Fact`  ');
if ($result=$db->query($sql)) {
		foreach ($result as $row) {
           if($row['Metadata']!=''){
               $metadata= json_decode($row['Metadata'],true);

               foreach($metadata['placement_data'] as $_data){
                  // print $_data['oif_key']."\n";




                   $sql=sprintf('update `Inventory Transaction Fact` set `Metadata`=%d where `Inventory Transaction Key`=%d  ',$row['Purchase Order Transaction Fact Key'],$_data['oif_key']);


                   $db->exec($sql);

               }

           }


		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}


?>
