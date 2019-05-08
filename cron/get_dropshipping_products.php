<?php
//@author Raul Perusquia <rulovico@gmail.com>
//Copyright (c) 2009 LW


require_once 'common.php';

require_once 'class.Store.php';
require_once 'class.Category.php';
require_once 'class.Product.php';
require_once 'class.Part.php';


$db_drop = new PDO("mysql:host=$dns_host;dbname=drop;charset=utf8", $dns_user, $dns_pwd, array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';"));
$db_drop->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$store = new Store('code', 'DS');


$department_bridge = array();
$family_bridge     = array();


$sql = "SELECT * FROM `drop`.`catalog_product_entity` WHERE sku IS NOT NULL AND sku NOT IN ('EO-')  order by sku ";

if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $store_code    = $store->data['Store Code'];
        $order_data_id = $row['entity_id'];

        $sql = sprintf(
            "SELECT * FROM `Product Import Metadata` WHERE `Metadata`=%s AND `Import Date`>=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['updated_at'])

        );

        if ($resxx = $db->query($sql)) {
            if ($rowxx = $resxx->fetch()) {
               continue;
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $code = $row['sku'];

        //print "$code\n";


        $sql = sprintf("SELECT * FROM `drop`.`catalog_product_entity_varchar` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber('name', 4));
        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                $name = $row2['value'];
            } else {
                exit("error no name associated\n");
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf("SELECT * FROM `drop`.`catalog_product_entity_varchar` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber('awsku', 4));
        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                $sku = $row2['value'];
            } else {
                exit("error no sku associated\n");
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf("SELECT * FROM `drop`.`catalog_product_entity_varchar` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber('relate', 4));
        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                $parts_per_product = floatval($row2['value']);

                if (!is_numeric($parts_per_product) or $parts_per_product <= 0) {
                    print_r($row);
                    print_r($row2);
                    exit("wrong parts per product  ->$parts_per_product<- \n");
                }

            } else {
                exit("error no part_relation associated\n");
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }






        $sql = sprintf("SELECT * FROM `drop`.`catalog_product_entity_text` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber('description', 4));
        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                $description = $row2['value'];
            } else {
                exit("error no description associated\n");
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf("SELECT * FROM `drop`.`catalog_product_entity_decimal` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber('price', 4));
        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                $price = $row2['value'];
            } else {
                exit("error no price associated\n");
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf("SELECT * FROM `drop`.`catalog_product_entity_decimal` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row['entity_id'], getMagentoAttNumber('weight', 4));
        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {
                $weight = $row2['value'];
            } else {
                exit("error no weight associated\n");
            }
        } else {
            print_r($error_info = $db->errorInfo());
            print "$sql\n";
            exit;
        }


        $sql = sprintf("SELECT * FROM `drop`.`catalog_category_product` WHERE  `product_id` =%d   ", $row['entity_id']);
        if ($result2 = $db->query($sql)) {
            if ($row2 = $result2->fetch()) {

                $sql = sprintf("SELECT * FROM `drop`.`catalog_category_entity_varchar` WHERE  `entity_id` =%d  AND attribute_id=%d ", $row2['category_id'], getMagentoAttNumber('name', 3));


                if ($result3 = $db->query($sql)) {
                    if ($row3 = $result3->fetch()) {
                        $category_code = preg_replace('/\s/i', '', $row3['value']);
                        $category_code = preg_replace('/\'/i', '', $category_code);
                        $category_code = preg_replace('/\&/i', '', $category_code);
                        $category_code = substr($category_code, 0, 5);

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


        $weight = $weight / 1000;

        $editor['Date'] = $row['created_at'];


        $part = new Part('sku', $sku);

        if (!$part->sku) {

            print '** Error SKU not found '.$code."  $sku  \n";
            continue;
        }


        $product_data = array(


            'Product Code'            => $code,
            'Product CPNP Number'     => '',
            'Product Parts'           => json_encode(
                array(
                    array(
                        'Key'      => '',
                        'Part SKU' => $part->sku,
                        'Ratio'    => $parts_per_product,
                        'Note'     => '',
                    )
                )
            ),
            'Family Category Code'    => $category_code,
            'Product Label in Family' => '',
            'Product Units Per Case'  => 1,
            'Product Unit Label'      => 'piece',
            'Product Price'           => $price,
            'Product Name'            => $name,
            'Product Unit RRP'        => '',
            'Product Unit Weight'     => $weight,
            'Product Description'     => $description,
            'editor'                  => $editor,
        );


        $product = $store->create_product($product_data);

        if($store->new_product){
            print "New ".$product->get('Product Code')."\n";
        }


        if ($store->error) {


            if ($store->error_code == 'duplicate_product_code_reference') {


                $sql = sprintf(
                    "SELECT `Product ID` FROM `Product Dimension` WHERE  `Product Store Key`=%s AND `Product Code`=%s  AND `Product Status`!='Discontinued'  ", $store->id, prepare_mysql($product_data['Product Code'])
                );


                if ($resultxxx = $db->query($sql)) {
                    if ($rowxxx = $resultxxx->fetch()) {

                        $product = get_object('Product', $rowxxx['Product ID']);


                        $product->editor = $product_data['editor'];
                        unset($product_data['editor']);
                        $product->update_part_list($product_data['Product Parts']);
                        $product->update(
                            array(
                                'Product Price'       => $price,
                                'Product Name'        => $name,
                                'Product Unit Weight' => $weight,
                                'Product Description' => $description,


                            )
                        );

                        $product->fast_update(
                            array(
                                'Product Status' => 'Active',


                            )
                        );

                        $product->update_status_from_parts();


                        print "Updating ".$product->get('Product Code')."\n";


                        $sql = sprintf(
                            "INSERT INTO `Product Import Metadata` ( `Metadata`, `Import Date`) VALUES (%s,%s) ON DUPLICATE KEY UPDATE
		`Import Date`=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['updated_at']), prepare_mysql($row['updated_at'])
                        );
                        $db->exec($sql);

                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


            } else {
                print $store->msg."\n";
                print $store->error_code."\n";


            }


        } else {


            if ($product->id) {

                $sql = sprintf(
                    "INSERT INTO `Product Import Metadata` ( `Metadata`, `Import Date`) VALUES (%s,%s) ON DUPLICATE KEY UPDATE
		`Import Date`=%s", prepare_mysql($store_code.$order_data_id), prepare_mysql($row['updated_at']), prepare_mysql($row['updated_at'])
                );
                $db->exec($sql);
            }

        }


    }
} else {
    print_r($error_info = $db->errorInfo());
    print "$sql\n";
    exit;
}


function getMagentoAttNumber($attribute_code, $entity_type_id) {

    global $db;

    $sql = "SELECT `attribute_id` FROM `drop`.`eav_attribute` WHERE `attribute_code` LIKE '".$attribute_code."' AND `entity_type_id` =".$entity_type_id."  ";

    if ($result = $db->query($sql)) {
        if ($row = $result->fetch()) {
            $Att_Got = $row['attribute_id'];
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }


    return $Att_Got;

}



