<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 January 2018 at 16:05:44 GMT+8, Kuala Lumpur, Malaysia
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


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$account = new Account();


//fix_image_bridge($db);


//fix_forced_webstock($db);


//update_parts_next_supplier_shipment($db);


//fix_dates_in_part_families($db);
//fix_dn_quantity_on_otf($db);

fix_amount_in($db);

function fix_amount_in($db) {

    //  $this->data['Delivery Note State']='Dispatched';

    $sql = sprintf('SELECT `Delivery Note Key` FROM `Delivery Note Dimension`  WHERE  `Delivery Note State`="Dispatched" AND   `Delivery Note Key`=2112972');
    $sql = sprintf('SELECT `Delivery Note Key`,`Delivery Note ID` FROM `Delivery Note Dimension`  WHERE  `Delivery Note State`="Dispatched" and `Delivery Note Date`>=%s ORDER BY `Delivery Note Key` DESC',
        prepare_mysql(gmdate('Y-m-d 00:00:00'))
        );
        
        print $sql;
   

    if ($result4 = $db->query($sql)) {
        foreach ($result4 as $row4) {


            $dn = get_object('DeliveryNote', $row4['Delivery Note Key']);


            if ($dn->get('State Index') >= 90) {

                $sql = sprintf("SELECT `Map To Order Transaction Fact Key` FROM `Inventory Transaction Fact` WHERE `Delivery Note Key`=%d  GROUP BY `Map To Order Transaction Fact Key` ", $dn->id);


                if ($result3 = $db->query($sql)) {
                    foreach ($result3 as $row3) {

                        $sql = sprintf(
                            "SELECT `Invoice Currency Exchange Rate`,`Order Transaction Fact Key`,`Order Transaction Amount`,`Delivery Note Quantity` FROM `Order Transaction Fact` WHERE `Order Transaction Fact Key`=%d ", $row3['Map To Order Transaction Fact Key']
                        );


                        if ($result = $db->query($sql)) {
                            foreach ($result as $row) {

                                //print_r($row);

                                $itf_transfer_factor = array();
                                $sum_itfs            = 0;

                                $sql = sprintf(
                                    'SELECT `Inventory Transaction Key`,`Inventory Transaction Quantity`,`Part Cost in Warehouse` FROM `Inventory Transaction Fact` ITF LEFT JOIN `Part Dimension` P ON (P.`Part SKU`=ITF.`Part SKU`)  WHERE `Map To Order Transaction Fact Key`=%d ',
                                    $row['Order Transaction Fact Key']
                                );

                                if ($result2 = $db->query($sql)) {
                                    foreach ($result2 as $row2) {
                                        $itf_transfer_factor[$row2['Inventory Transaction Key']] = $row2['Part Cost in Warehouse'] * $row2['Inventory Transaction Quantity'];
                                        $sum_itfs                                                += $row2['Part Cost in Warehouse'] * $row2['Inventory Transaction Quantity'];
                                    }
                                } else {
                                    print_r($error_info = $db->errorInfo());
                                    print "$sql\n";
                                    exit;
                                }


                                $number_of_itf = count($itf_transfer_factor);

                                if ($number_of_itf == 1) {
                                    foreach ($itf_transfer_factor as $key => $value) {
                                        $itf_transfer_factor[$key] = 1;
                                    }
                                } else {

                                    if ($sum_itfs == 0 and $number_of_itf > 0) {
                                        foreach ($itf_transfer_factor as $key => $value) {
                                            $itf_transfer_factor[$key] = 1 / $number_of_itf;
                                        }
                                    } else {
                                        foreach ($itf_transfer_factor as $key => $value) {
                                            $itf_transfer_factor[$key] = $value / $sum_itfs;
                                        }
                                    }
                                }

                                $amount_in = $row['Invoice Currency Exchange Rate'] * $row['Order Transaction Amount'];


                                $old_amount_in=0;
                                $sql=sprintf('select `Amount In` from `Inventory Transaction Fact`  WHERE `Inventory Transaction Key`=%d ', $key);
                                if ($resultzz=$db->query($sql)) {
                                    if ($rowzz = $resultzz->fetch()) {
                                        $old_amount_in=$rowzz['Amount In'];
                                	}
                                }else {
                                	print_r($error_info=$db->errorInfo());
                                	print "$sql\n";
                                	exit;
                                }


                                if(round($amount_in,1)!=round($old_amount_in,1)){
                                    print $row4['Delivery Note ID']." $old_amount_in -> $amount_in\n";
                                   // print_r($row);
                                }

                                //print_r($itf_transfer_factor);

                                foreach ($itf_transfer_factor as $key => $value) {
                                    $sql = sprintf(
                                        "UPDATE  `Inventory Transaction Fact`  SET `Amount In`=%f WHERE `Inventory Transaction Key`=%d ", $amount_in * $value, $key
                                    );
                                   // print "$sql\n";
                                    $db->exec($sql);
                                    // exit;
                                    // mysql_query( $sql );
                                }


                            }
                        } else {
                            print_r($error_info = $db->errorInfo());
                            print "$sql\n";
                            exit;
                        }
                    }


                } else {
                    print_r($error_info = $db->errorInfo());
                    print "$sql\n";
                    exit;
                }


                //----


                //===

            }

        }
    }


}


function fix_dates_in_part_families($db) {


    //$sql=sprintf('select `Category Key` from `Category Dimension` where `Category Scope`="Part" and `Category Key`=11899  ');
    $sql = sprintf(
        'SELECT `Category Key`,`Category Code` FROM `Category Dimension`  LEFT JOIN `Part Category Dimension` ON (`Part Category Key`=`Category Key`)  WHERE `Category Scope`="Part" AND `Part Category Valid From` IS NULL '
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $category = new Category($row['Category Key']);

            print $row['Category Code']."\n";
            $part_skus = '';
            $sql       = sprintf(
                'SELECT group_concat(`Subject Key`) AS part_skus ,`Subject` FROM `Category Bridge` WHERE `Category Key`=%d AND `Subject Key`>0 ', $category->id
            );

            if ($result = $db->query($sql)) {
                if ($row = $result->fetch()) {
                    if ($row['Subject'] == 'Part') {
                        $part_skus = $row['part_skus'];
                    } elseif ($row['Subject'] == 'Category') {

                        $sql = sprintf(
                            'SELECT group_concat(`Subject Key`) AS part_skus ,`Subject` FROM `Category Bridge` WHERE `Category Key` IN (%s) AND `Subject Key`>0 ', $row['part_skus']
                        );
                        if ($result2 = $db->query($sql)) {
                            if ($row2 = $result2->fetch()) {
                                $part_skus = $row2['part_skus'];

                            }
                        } else {
                            print_r($error_info = $db->errorInfo());
                            print $sql;
                            exit;
                        }


                    }
                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }
            $from_date_parts = '';
            $from_date_itf   = '';
            if ($part_skus != '') {
                $sql = sprintf(
                    'SELECT min(`Part Valid From`) AS date FROM `Part Dimension` WHERE `Part SKU` IN (%s) AND `Part Valid From` IS NOT NULL AND `Part Valid From`!="0000-00-00 00:00:00" ', $part_skus
                );


                if ($result2 = $db->query($sql)) {
                    foreach ($result2 as $row2) {
                        $from_date_parts = $row2['date'];
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }

            }


            if ($part_skus != '') {
                $sql = sprintf(
                    'SELECT min(`Date`) AS date FROM `Inventory Transaction Fact` WHERE `Part SKU` IN (%s) AND `Date` IS NOT NULL AND `Date`!="0000-00-00 00:00:00" ', $part_skus
                );


                if ($result2 = $db->query($sql)) {
                    foreach ($result2 as $row2) {
                        $from_date_itf = $row2['date'];
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }

            }


            if ($from_date_parts == '' and $from_date_itf == '') {
                $from_date = gmdate('Y-m-d H:i:s');
            } elseif ($from_date_parts == '' and $from_date_itf != '') {
                $from_date = $from_date_itf;
            } elseif ($from_date_parts != '' and $from_date_itf == '') {
                $from_date = $from_date_parts;
            } else {
                if (strtotime($from_date_parts) < strtotime($from_date_itf)) {
                    $from_date = $from_date_parts;

                } else {
                    $from_date = $from_date_itf;

                }
            }

            $sql = sprintf(
                'UPDATE `Part Category Dimension` SET `Part Category Valid From`=%s WHERE `Part Category Key`=%d', prepare_mysql($from_date), $category->id
            );
            print "$sql\n";
            $db->exec($sql);

            $category->update_part_category_status();

            if ($category->get('Part Category Status') == 'NotInUse') {

                $to_date_parts = '';
                $to_date_itf   = '';
                if ($part_skus != '') {
                    $sql = sprintf(
                        'SELECT max(`Part Valid To`) AS date FROM `Part Dimension` WHERE `Part SKU` IN (%s) AND `Part Valid To` IS NOT NULL AND `Part Valid To`!="0000-00-00 00:00:00" ', $part_skus
                    );


                    if ($result2 = $db->query($sql)) {
                        foreach ($result2 as $row2) {
                            $to_date_parts = $row2['date'];
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        exit;
                    }

                }


                if ($part_skus != '') {
                    $sql = sprintf(
                        'SELECT max(`Date`) AS date FROM `Inventory Transaction Fact` WHERE `Part SKU` IN (%s) AND `Date` IS NOT NULL AND `Date`!="0000-00-00 00:00:00" ', $part_skus
                    );


                    if ($result2 = $db->query($sql)) {
                        foreach ($result2 as $row2) {
                            $to_date_itf = $row2['date'];
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        exit;
                    }

                }


                if ($to_date_parts == '' and $to_date_itf == '') {
                    $to_date = gmdate('Y-m-d H:i:s');
                } elseif ($to_date_parts == '' and $to_date_itf != '') {
                    $to_date = $to_date_itf;
                } elseif ($to_date_parts != '' and $to_date_itf == '') {
                    $to_date = $to_date_parts;
                } else {
                    if (strtotime($to_date_parts) > strtotime($to_date_itf)) {
                        $to_date = $to_date_parts;

                    } else {
                        $to_date = $to_date_itf;

                    }
                }

                $sql = sprintf(
                    'UPDATE `Part Category Dimension` SET `Part Category Valid To`=%s WHERE `Part Category Key`=%d', prepare_mysql($to_date), $category->id
                );

                print "$sql\n";
                $db->exec($sql);
            }


        }

    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
    }


}


function update_parts_next_supplier_shipment($db) {


    $sql = sprintf(
        'SELECT `Part SKU` FROM `Part Dimension`  ORDER BY `Part SKU`  DESC '
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Part SKU']);

            $part->update_next_deliveries_data();


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }
}


function fix_image_bridge($db) {

    $count = 0;

    $sql = sprintf('SELECT  * FROM `Image Subject Bridge` ');


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $sql = sprintf('SELECT `Image Key` FROM `Image Dimension` WHERE `Image Key`=%d ', $row['Image Subject Image Key']);

            if ($result2 = $db->query($sql)) {
                if ($row2 = $result2->fetch()) {

                } else {

                    $count++;
                    // print_r($row);

                    $sql = sprintf('DELETE FROM `Image Subject Bridge` WHERE `Image Subject Key`=%d   ', $row['Image Subject Key']);
                    $db->exec($sql);
                    //print "$sql\n";
                    print  "$count\r";

                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }

    print "===\n";

    $sql = sprintf('SELECT * FROM `Category Dimension`  ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $cat = new Category($row['Category Key']);
            $cat->reindex_order();
        }

    }

    $sql = sprintf('SELECT * FROM `Part Dimension`  ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Part SKU']);
            $part->reindex_order();
        }

    }


    $sql = sprintf('SELECT * FROM `Product Dimension`  ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $product = new Product($row['Product ID']);
            $product->reindex_order();
        }

    }


}


//delete_multiple_family_webpages($db);


function delete_multiple_family_webpages($db) {


    $sql = sprintf('SELECT `Store Family Category Key` FROM `Store Dimension` ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $sql = sprintf('SELECT `Category Key`,`Category Code` FROM `Category Dimension`  WHERE  `Category Root Key`=%d ', $row['Store Family Category Key']);

            if ($result2 = $db->query($sql)) {
                foreach ($result2 as $row2) {


                    $sql = sprintf(
                        'SELECT count(*) AS num FROM `Page Store Dimension` WHERE `Webpage Scope`="Category Products" AND `Webpage Scope Key`=%d AND `Page Store Content Template Filename`="products_showcase" AND `Page Store Content Display Type`="Template"   ',
                        $row2['Category Key']
                    );

                    if ($result4 = $db->query($sql)) {
                        if ($row4 = $result4->fetch()) {

                            if ($row4['num'] > 1) {


                                $sql = sprintf(
                                    'SELECT `Webpage State`,`Webpage Store Key`,`Page Key`,`Page Code`,`Page Store Content Template Filename` FROM `Page Store Dimension` WHERE `Webpage Scope`="Category Products" AND `Webpage Scope Key`=%d ORDER BY `Page Code` ',
                                    $row2['Category Key']
                                );


                                $counter = 0;

                                if ($result3 = $db->query($sql)) {
                                    foreach ($result3 as $row3) {
                                        if ($counter == 0) {
                                            print $row3['Page Code']." ".$row3['Webpage Store Key']." ".$row3['Webpage State']."  \n";

                                        } else {
                                            print $row3['Page Code']." ".$row3['Webpage Store Key']." ".$row3['Webpage State']." to delete  \n";
                                            $page = new Page($row3['Page Key']);
                                            $page->delete();


                                        }
                                        $counter++;

                                    }


                                } else {
                                    print_r($error_info = $db->errorInfo());
                                    print "$sql\n";
                                    exit;
                                }


                            }


                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }


                }
            } else {
                print_r($error_info = $db->errorInfo());
                print "$sql\n";
                exit;
            }


        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


}


function fix_forced_webstock($db) {

    // art (181,164,266,350,408)
    // wood (57,170,91,245,121)
    // jell (47,83,115,171,358,245)


    $sql = sprintf(
        "SELECT `Product Code`,`Product ID`,`Product Availability`,`Product Web Configuration`,`Product Web State` FROM `Product Dimension` WHERE  `Product Status`='Active' AND  `Product Web Configuration`  IN ('Online Force Out of Stock','Online Force For Sale')  AND `Product Main Department Key` IN (47,83,115,171,358,245)    ORDER BY `Product Code`  "
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $product = new Product('id', $row['Product ID']);
            $product->update(
                array('Product Web Configuration' => 'Online Auto')
            );

            print $row['Product Code'].' '.$row['Product ID'].','.$row['Product Web Configuration']."\n";
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


//fix_parts($db);
//fix_products($db);
//fix_itf_amount_in_for_starters($db);

//fix_set_as_automatic_products_from_supplier($db);
//fix_parts2($db);

//fix_out_of_stock_in_basket($db);
function fix_out_of_stock_in_basket($db) {

    $sql = sprintf(
        'SELECT `Order Key` FROM `Order Dimension` WHERE `Order Current Dispatch State`="In Process by Customer" ORDER BY `Order Key` DESC '
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $order = new Order($row['Order Key']);
            $sql   = sprintf(
                'SELECT `Product ID`,`Product Code` FROM `Order Transaction Fact` WHERE `Order Key`=%d ', $row['Order Key']
            );
            print $order->id."\n";
            if ($result2 = $db->query($sql)) {
                foreach ($result2 as $row2) {
                    $product = new Product('id', $row2['Product ID']);

                    print " ".$product->get('Code')."\n";


                    $web_availability = ($product->get_web_state() == 'For Sale' ? 'Yes' : 'No');
                    if ($web_availability == 'No') {
                        $order->remove_out_of_stocks_from_basket($product->id);
                    } else {
                        $order->restore_back_to_stock_to_basket($product->id);
                    }

                    if (!($product->get('Product Status') == 'Active' or $product->get('Product Status') == 'Discontinuing') or $product->get(
                            'Product Web Configuration'
                        ) == 'Offline') {
                        $_state = 'Offline';
                    } else {
                        $_state = 'Online';
                    }


                    foreach ($product->get_pages('objects') as $page) {
                        $page->update(
                            array('Page State' => $_state), 'no_history'
                        );
                    }

                }
            } else {
                print_r($error_info = $db->errorInfo());
                exit;
            }


        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


function fix_parts2($db) {

    $sql = sprintf('SELECT * FROM `Part Dimension`  ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Part SKU']);

            $part->update_stock_in_paid_orders();

        }

    }
}


function fix_set_as_automatic_products_from_supplier($db) {

    include_once 'class.Part.php';
    include_once 'class.Product.php';
    include_once 'class.SupplierPart.php';

    $counter = 1;
    $sql     = sprintf(
        "SELECT `Product ID`,`Product Availability` FROM `Product Dimension` WHERE  `Product Web Configuration`='Online Force For Sale'  AND `Product Availability`<=0 AND `Product Store Key`=1 ORDER BY `Product Code`  "
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $product = new Product('id', $row['Product ID']);

            //print sprintf("%05d   ", $counter).$product->get('Code')." ".$row['Product Availability']."  \n";
            print sprintf("%05d   ", $counter).$product->get('Code')." \n";

            $counter++;
            //$product->update(array('Product Web Configuration'=>'Offline'), 'no_history no_fork');
            //   exit;
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    exit;

    // production
    // $supplier_key=6472;
    /*
    $supplier_key=1;

    $sql=sprintf('select `Supplier Part Part SKU`,`Supplier Part Key` from `Supplier Part Dimension` where `Supplier Part Supplier Key`=%d ', $supplier_key);
    // $sql=sprintf('select `Supplier Part Part SKU` from `Supplier Part Dimension`');

    if ($result=$db->query($sql)) {
        foreach ($result as $row) {
            $supplier_part=new SupplierPart($row['Supplier Part Key'])   ;
            print $supplier_part->get('Reference')."\n";

            $supplier_part->update(array('Supplier Part On Demand'=>'Yes'), 'no_history');

        }
    }else {
        print_r($error_info=$db->errorInfo());
        exit;
    }

exit;
*/
    // production
    $supplier_key = 6472;
    //$supplier_key=3;

    $sql = sprintf(
        'SELECT `Supplier Part Part SKU` FROM `Supplier Part Dimension` WHERE `Supplier Part Supplier Key`=%d ', $supplier_key
    );
    // $sql=sprintf('select `Supplier Part Part SKU` from `Supplier Part Dimension`');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Supplier Part Part SKU']);

            foreach ($part->get_products('objects') as $product) {

                //'Online Force Out of Stock','Online Auto','Offline','Online Force For Sale'

                if ($product->get('Product Web Configuration') == 'Online Force For Sale') {

                    print $product->get('Store Code').' '.$product->get('Code').' '.$product->get('Product Web Configuration')."\n";
                    $product->update(
                        array('Product Web Configuration' => 'Online Auto'), 'no_history no_fork'
                    );

                }
                // $product->update(array('Product Web Configuration'=>'Online Auto'),'no_history');
            }

        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


function fix_remove_forced_out_of_stock() {


}


function fix_itf_amount_in_for_starters($db) {


    $sql = sprintf(
        "SELECT count(*) AS num FROM `Delivery Note Dimension` WHERE `Delivery Note State`='Dispatched'   "
    );
    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $total = $row['num'];
        } else {
            $total = 0;
        }
    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    $lap_time0 = date('U');
    $contador  = 0;

    $sql  = sprintf(
        "SELECT `Delivery Note Key` FROM `Delivery Note Dimension` WHERE `Delivery Note State`='Dispatched' ORDER BY `Delivery Note Date` DESC  "
    );
    $res4 = mysql_query($sql);
    while ($row4 = mysql_fetch_assoc($res4)) {

        $sql  = sprintf(
            "SELECT `Map To Order Transaction Fact Key` FROM `Inventory Transaction Fact` WHERE `Delivery Note Key`=%d  GROUP BY `Map To Order Transaction Fact Key` ", $row4['Delivery Note Key']
        );
        $res3 = mysql_query($sql);
        while ($row3 = mysql_fetch_assoc($res3)) {

            $sql = sprintf(
                "SELECT `Invoice Currency Exchange Rate`,`Invoice Transaction Net Refund Items`,`Order Transaction Fact Key`,`Invoice Transaction Total Discount Amount`,`Invoice Transaction Gross Amount` FROM `Order Transaction Fact` WHERE `Order Transaction Fact Key`=%d ",
                $row3['Map To Order Transaction Fact Key']
            );

            $res = mysql_query($sql);
            while ($row = mysql_fetch_assoc($res)) {


                $itf_transfer_factor = array();
                $sum_itfs            = 0;

                $sql = sprintf(
                    'SELECT ITF.`Part SKU`,`Inventory Transaction Key`,`Inventory Transaction Quantity`,`Part Cost` FROM `Inventory Transaction Fact` ITF LEFT JOIN `Part Dimension` P ON (P.`Part SKU`=ITF.`Part SKU`)  WHERE `Map To Order Transaction Fact Key`=%d ',
                    $row['Order Transaction Fact Key']
                );


                $res2 = mysql_query($sql);
                //print $sql;
                while ($row2 = mysql_fetch_assoc($res2)) {
                    //print_r($row2);
                    $itf_transfer_factor[$row2['Inventory Transaction Key']] = $row2['Part Cost'] * $row2['Inventory Transaction Quantity'];
                    $sum_itfs                                                += $row2['Part Cost'] * $row2['Inventory Transaction Quantity'];
                }


                $number_of_itf = count($itf_transfer_factor);

                if ($number_of_itf == 1) {
                    foreach ($itf_transfer_factor as $key => $value) {
                        $itf_transfer_factor[$key] = 1;
                    }
                } else {

                    if ($sum_itfs == 0 and $number_of_itf > 0) {
                        foreach ($itf_transfer_factor as $key => $value) {
                            $itf_transfer_factor[$key] = 1 / $number_of_itf;
                        }
                    } else {
                        foreach ($itf_transfer_factor as $key => $value) {
                            $itf_transfer_factor[$key] = $value / $sum_itfs;
                        }
                    }
                }

                $amount_in = $row['Invoice Currency Exchange Rate'] * ($row['Invoice Transaction Gross Amount'] - $row['Invoice Transaction Total Discount Amount'] - $row['Invoice Transaction Net Refund Items']);

                //print_r($itf_transfer_factor);

                foreach ($itf_transfer_factor as $key => $value) {
                    $sql = sprintf(
                        "UPDATE  `Inventory Transaction Fact`  SET `Amount In`=%f WHERE `Inventory Transaction Key`=%d ", $amount_in * $value, $key
                    );
                    // print "$sql\n";
                    mysql_query($sql);
                }

                //----


                //===

            }
        }


        $contador++;
        $lap_time1 = date('U');


        print 'Pa '.percentage($contador, $total, 3)."  lap time ".sprintf(
                "%.2f", ($lap_time1 - $lap_time0) / $contador
            )." EST  ".sprintf(
                "%.1f", (($lap_time1 - $lap_time0) / $contador) * ($total - $contador) / 60
            )."m  ($contador/$total) \r";


    }

}


function fix_parts($db) {

    $sql = sprintf('SELECT * FROM `Part Dimension`  ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Part SKU']);

            $part->discontinue_trigger();

        }

    }
}


function fix_products($db) {

    $sql = sprintf(
        'SELECT `Product ID` FROM `Product Dimension` WHERE `Product Store Key`!=9 ORDER BY `Product ID` DESC '
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $product = new Product('id', $row['Product ID']);

            $product->update_status_from_parts();

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


function fix_dn_quantity_on_otf($db) {

    $sql = sprintf('SELECT `Delivery Note Key` FROM `Delivery Note Dimension` where `Delivery Note Date`>="2018-01-01" ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $dn = get_object('DeliveryNote', $row['Delivery Note Key']);
            if ($dn->get('State Index') >= 80) {


                $sql = sprintf(
                    'SELECT `Packed`,`Required`,`Given`,`Map To Order Transaction Fact Key` FROM `Inventory Transaction Fact` WHERE  `Delivery Note Key`=%d ', $dn->id
                );


                if ($result=$db->query($sql)) {
                    foreach ($result as $row2) {






                        $to_pack = $row2['Required'] + $row2['Given'];

                        if ($to_pack == 0) {
                            $ratio_of_packing = 1;
                        } else {
                            $ratio_of_packing = $row2['Packed'] / $to_pack;
                        }

                        // todo make get  `Order Transaction Amount` and do it properly to have exact cents

                        $otf = $row2['Map To Order Transaction Fact Key'];

                        $sql = sprintf(
                            'UPDATE `Order Transaction Fact`  SET 
                            `Delivery Note Quantity`=(`Order Quantity`+`Order Bonus Quantity`)*%f ,
                            `Order Transaction Out of Stock Amount`=`Order Transaction Amount`*(1-%f) ,
                               `Order Transaction Amount`=`Order Transaction Amount`*%f 
                             WHERE `Order Transaction Fact Key`=%d ', $ratio_of_packing, $ratio_of_packing, $ratio_of_packing, $otf
                        );


                        //print "$sql\n";
                        $db->exec($sql);


                    }
                }





            }

        }
    } else {
        print_r($error_info = $this->db->errorInfo());
        print "$sql\n";
        exit;
    }


}



?>
