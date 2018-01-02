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

/*

$sql=sprintf('Select `Purchase Order Transaction Fact Key`,`Metadata` from `Purchase Order Transaction Fact`  ');
if ($result=$db->query($sql)) {
		foreach ($result as $row) {
           if($row['Metadata']!=''){
               $metadata= json_decode($row['Metadata'],true);
               foreach($metadata['placement_data'] as $_data){
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

*/

// now we are going to make the approximation that current part cost in warehouse is correct and has been the amount paid to suppliers in previous POs
// only safe for sk

$sql=sprintf('select `Part SKU` from `Part Dimension`  ');
if ($result=$db->query($sql)) {
		foreach ($result as $row) {
		    $part=get_object('Part',$row['Part SKU']);
		    if($part->get('Part Cost in Warehouse')>0){
                $sql=sprintf('update `Inventory Transaction Fact` set `Inventory Transaction Amount`=`Inventory Transaction Quantity`*%f where `Part SKU`=%s and `Inventory Transaction Type`="In" ',$part->get('Part Cost in Warehouse'),$part->id);
                $db->exec($sql);
            }else{

		        $sql=sprintf('select count(*) as num from   `Inventory Transaction Fact` where `Part SKU`=%s and `Inventory Transaction Type`="In" ',$part->id);

		        if ($result2=$db->query($sql)) {
		            if ($row2 = $result2->fetch()) {
		                if($row2['num']>0){
                            print $part->get('Part Reference')."\n";
                        }

		        	}
		        }else {
		        	print_r($error_info=$db->errorInfo());
		        	print "$sql\n";
		        	exit;
		        }





            }


		}
}else {
		print_r($error_info=$db->errorInfo());
		print "$sql\n";
		exit;
}






?>
