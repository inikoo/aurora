<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 15 February 2016 at 10:45:44 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';


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


require_once 'utils/get_addressing.php';

require_once 'class.Customer.php';
require_once 'class.Store.php';
require_once 'class.Address.php';
require_once 'class.Product.php';
require_once 'class.Part.php';
require_once 'class.Page.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);
/*
migrate_products($db, $editor);
update_number_of_parts($db)
create_categories($db,$editor);
fix_product_categories($db);
migrate_historic_products($db);
update_fields_from_parts($db);
update_number_of_parts($db);
update_web_configuration($db);
set_family_department_key($db);
migrate_page_related_products($db);
update_products_web_status($db);
update_cost($db);
create_data_tables($db);

*/

//fix_family_web_descriptions($db);
//update_product_category_status($db);

create_data_tables($db);


function update_product_category_status($db) {

    $sql = sprintf(
        'SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Product" AND `Category Code`="CCS"  '
    );
    $sql = sprintf(
        'SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Product" ORDER BY  `Category Key` DESC'
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $category = new Category($row['Category Key']);
            $category->update_product_category_status();
            $category->update_product_stock_status();


            $product_ids = $category->get_product_ids();
            if ($product_ids != '') {
                $sql = sprintf(
                    "SELECT min(`Order Date`) AS date FROM `Order Transaction Fact` WHERE `Product ID` IN (%s) AND `Order Date` IS NOT NULL AND `Order Date`!='0000-00-00 00:00:00'", $product_ids
                );


                //print "$sql\n";
                if ($result = $db->query($sql)) {
                    if ($row = $result->fetch()) {
                        if ($row['date'] != '' and (strtotime($row['date']) < strtotime(
                                    $category->get(
                                        'Product Category Valid From'
                                    )
                                ) or $category->get(
                                    'Product Category Valid From'
                                ) == '')
                        ) {

                            print "updating ".$category->get('Code')." from ".$row['date']."\n";

                            $category->update(
                                array('Product Category Valid From' => $row['date']), 'no_history'
                            );

                        }
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }


            }


            if ($category->get('Product Category Status') == 'Discontinued') {


                if ($product_ids != '') {
                    $sql = sprintf(
                        "SELECT max(`Order Date`) AS date FROM `Order Transaction Fact` WHERE `Product ID` IN (%s) AND `Order Date` IS NOT NULL AND `Order Date`!='0000-00-00 00:00:00'", $product_ids
                    );


                    if ($result = $db->query($sql)) {
                        if ($row = $result->fetch()) {
                            if ($row['date'] != '' and strtotime($row['date']) > strtotime(
                                    $category->get(
                                        'Product Category Valid From'
                                    )
                                )
                            ) {

                                print "updating ".$category->get('Code')." to ".$row['date']."\n";

                                $category->update(
                                    array('Product Category Valid To' => $row['date']), 'no_history'
                                );

                            }
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        exit;
                    }


                }


            }


        }

    } else {
        print_r($error_info = $db->errorInfo());
        print $sql;
        exit;
    }
}


function fix_family_web_descriptions($db) {


    $sql = sprintf(
        "SELECT `Category Key` FROM `Category Dimension` WHERE  `Category Scope`='Product' AND `Category Subject`='Product' "
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $category = new Category($row['Category Key']);


            $sql = sprintf(
                'SELECT `Product Family Store Key`,`Product Family Code`,`Product Family Description` FROM `Product Family Dimension` WHERE `Product Family Code`=%s AND  `Product Family Store Key`=%d ',
                prepare_mysql($category->get('Code')), $category->get('Category Store Key')

            );


            if ($result2 = $db->query($sql)) {
                if ($row2 = $result2->fetch()) {

                    if ($row2['Product Family Description'] != $category->get(
                            'Product Category Description'
                        )
                    ) {
                        print $category->id.' '.$category->get(
                                'Category Store Key'
                            ).'  '.$category->get('Code')."\n";

                        $category->update(
                            array('Product Category Description' => $row2['Product Family Description']), 'no_history'
                        );

                        exit;
                    } else {
                        // print 'OK '.$category->id.' '.$category->get('Code')."\n";
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


function create_data_tables($db) {


    $sql = sprintf('SELECT `Store Key` FROM `Store Dimension`  ');


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $sql = sprintf(
                "INSERT INTO `Store Data` (`Store Key`) VALUES (%d)", $row['Store Key']

            );

            $db->exec($sql);
            $sql = sprintf(
                "INSERT INTO `Store DC Data` (`Store Key`) VALUES (%d)", $row['Store Key']

            );
            $db->exec($sql);
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql = sprintf(
        'SELECT `Product ID` FROM `Product Dimension` ORDER BY `Product ID`  '
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $sql = sprintf(
                "INSERT INTO `Product Data` (`Product ID`) VALUES (%d)", $row['Product ID']

            );

            $db->exec($sql);
            $sql = sprintf(
                "INSERT INTO `Product DC Data` (`Product ID`) VALUES (%d)", $row['Product ID']

            );
            $db->exec($sql);
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql = sprintf(
        'SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Product"  '
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $category = new Category($row['Category Key']);

            $store = new Store($category->data['Category Store Key']);


            $sql = sprintf(
                "INSERT INTO `Product Category Dimension` (`Product Category Key`,`Product Category Store Key`,`Product Category Currency Code`,`Product Category Valid From`) VALUES (%d,%d,%s,%s)",
                $category->id, $store->id, prepare_mysql($store->get('Store Currency Code')), prepare_mysql(gmdate('Y-m-d H:i:s'))
            );
            $db->exec($sql);

            $sql = sprintf(
                "INSERT INTO `Product Category Data` (`Product Category Key`) VALUES (%d)", $category->id

            );
            $db->exec($sql);
            $sql = sprintf(
                "INSERT INTO `Product Category DC Data` (`Product Category Key`) VALUES (%d)", $category->id

            );
            $db->exec($sql);


            // $category->update_product_category_up_today_sales();


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql = sprintf(
        'SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Invoice"  '
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $category = new Category($row['Category Key']);

            $store = new Store($category->data['Category Store Key']);

            $sql = sprintf(
                "INSERT INTO `Invoice Category Dimension` (`Invoice Category Key`,`Invoice Category Store Key`,`Invoice Category Currency Code`,`Invoice Category Valid From`) VALUES (%d,%d,%s,%s)",
                $category->id, $store->id, prepare_mysql($store->get('Store Currency Code')), prepare_mysql(gmdate('Y-m-d H:i:s'))
            );
            $db->exec($sql);


            $sql = sprintf(
                "INSERT INTO `Invoice Category Data` (`Invoice Category Key`) VALUES (%d)", $category->id

            );
            $db->exec($sql);
            $sql = sprintf(
                "INSERT INTO `Invoice Category DC Data` (`Invoice Category Key`) VALUES (%d)", $category->id

            );
            $db->exec($sql);


            // $category->update_product_category_up_today_sales();


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


    $sql = sprintf(
        'SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Invoice" ORDER BY  `Category Key` DESC'
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $category = new Category($row['Category Key']);



                $sql = sprintf(
                    "SELECT min(`Invoice Date`) AS date FROM   `Category Bridge` left join   `Invoice Dimension` on (`Subject Key`=`Invoice Key`)  WHERE `Category Key` =%d AND `Invoice Date` IS NOT NULL AND `Invoice Date`!='0000-00-00 00:00:00'",
                    $category->id

                );


                print "$sql\n";
                if ($result2 = $db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        print_r($row2);

                        if ($row2['date'] != '') {

                            $category->update(
                                array('Invoice Category Valid From' => $row2['date']), 'no_history'
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
        print $sql;
        exit;
    }


}


function update_products_web_status($db) {

    $sql = sprintf('SELECT * FROM `Part Dimension`  ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Part SKU']);

            $part->update_products_web_status();

        }

    }
}


function update_cost($db) {

    $sql = sprintf(
        'SELECT `Product ID` FROM `Product Dimension` ORDER BY `Product ID`  '
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $product = new Product('id', $row['Product ID']);


            $product->update_cost();
            print $product->id."\r";
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


function set_family_department_key($db) {
    $sql = sprintf(
        'SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Product"  AND  `Category Subject`="Product" '
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $category = new Category($row['Category Key']);

            $store = new Store($category->data['Category Store Key']);


            $sql = sprintf(
                "SELECT B.`Category Key`,`Category Root Key`,`Other Note`,`Category Label`,`Category Code`,`Is Category Field Other` FROM `Category Bridge` B LEFT JOIN `Category Dimension` C ON (C.`Category Key`=B.`Category Key`) WHERE  `Category Branch Type`='Head'  AND B.`Subject Key`=%d AND B.`Subject`='Category'",
                $category->id
            );


            if ($result2 = $db->query($sql)) {
                foreach ($result2 as $row2) {

                    //  print_r($row2);
                    $category->update(
                        array('Product Category Department Category Key' => $row2['Category Key']), 'no_history'
                    );

                }

            }


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

}


function migrate_page_related_products($db) {

    $sql = "SELECT `Page Code`,`Page Key`,`Page Store Section`,`Page Store Key`,`Page Related Products List` FROM `Page Store Dimension` WHERE `Page Related Products List`!='';";

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {


            $page = new Page($row['Page Key']);

            $products_ids  = array();
            $product_codes = preg_split(
                '/,/', $row['Page Related Products List']
            );
            foreach ($product_codes as $code) {
                if ($code != '') {
                    $product = new Product(
                        'store_code', $row['Page Store Key'], $code
                    );
                    if ($product->id) {
                        $products_ids[] = $product->id;
                    }

                }
            }
            //print_r($row);
            // print_r($products_ids);


            $page->update(
                array('Related Products' => json_encode($products_ids)), 'no_history'
            );

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }
}


function update_web_configuration($db) {

    $sql = sprintf(
        'SELECT `Product ID` FROM `Product Dimension` ORDER BY `Product ID` DESC '
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $product = new Product('id', $row['Product ID']);


            if ($product->get('Product Web Configuration') == 'Online Auto') {

                if ($product->get('Product Number of Parts') == 1) {

                    $parts = $product->get_parts('objects');
                    $part  = array_pop($parts);


                    //'Online Force Out of Stock','Online Auto','Offline','Online Force For Sale'

                    if ($part->get('Part Available for Products Configuration') == 'Yes') {


                        $product->update(
                            array('Product Web Configuration' => 'Online Force For Sale'), 'no_fork no_history'
                        );
                        //print_r($part);
                        //     print_r($product);
                        //exit;

                    } elseif ($part->get(
                            'Part Available for Products Configuration'
                        ) == 'No'
                    ) {


                        $product->update(
                            array('Product Web Configuration' => 'Online Force Out of Stock'), 'no_fork no_history'
                        );
                        //print_r($part);
                        //     print_r($product);
                        //exit;

                    }


                }


            }

            //$product->update_part_numbers();
            //$product->update_web_state();
            print $product->id."\r";
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


function update_number_of_parts($db) {

    $sql = sprintf(
        'SELECT `Product ID` FROM `Product Dimension` ORDER BY `Product ID` DESC '
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $product = new Product('id', $row['Product ID']);

            $product->update_part_numbers();
            $product->update_web_state();
            print $product->id."\r";
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


function update_fields_from_parts($db) {

    $sql = sprintf(
        'SELECT `Part SKU` FROM `Part Dimension` ORDER BY `Part SKU` DESC '
    );


    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $part = new Part($row['Part SKU']);
            print $part->id."\r";
            $part->updated_linked_products();


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


function migrate_historic_products($db) {
    $sql = sprintf(
        'SELECT `Product Key`,H.`Product ID`,`Product Code`,`Product Units Per Case` FROM `Product History Dimension` H LEFT JOIN   `Product Dimension` P ON  (H.`Product ID`=P.`Product ID`)  '
    );
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            //print_r($row);

            $product = new Product($row['Product ID']);


            if (!$product->id) {

                continue;
            }
            /*
            //print $row['Product Key'].' '.$product->get('Code')."\n";
            $desc=$product->get('Product Units Per Case').'x '.$product->get('Product Name').' ('.$product->get('Price').')';

            $sql=sprintf('update `Product History Dimension` set `Product History Short Description`=%s ,`Product History XHTML Short Description`=%s,`Product History Special Characteristic`=%s  where `Product Key`=%s  ',
                prepare_mysql($desc),
                prepare_mysql($desc),
                prepare_mysql($product->get('Product Special Characteristic'),false),
                $row['Product Key']
            );
            $db->exec($sql);

            */

            $sql = sprintf(
                'UPDATE `Product History Dimension` SET `Product History Code`=%s ,`Product History Units Per Case`=%d WHERE `Product Key`=%s  ', prepare_mysql($row['Product Code']),
                $row['Product Units Per Case'], $row['Product Key']
            );
            $db->exec($sql);

            $product->update_historic_object();

        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


function fix_product_categories($db) {
    $sql = sprintf(
        'SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Product"'
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $category = new Category($row['Category Key']);

            $store = new Store($category->data['Category Store Key']);

            $sql = sprintf(
                "INSERT INTO `Product Category Dimension` (`Product Category Key`,`Product Category Store Key`,`Product Category Currency Code`,`Product Category Valid From`) VALUES (%d,%d,%s,%s)",
                $category->id, $store->id, prepare_mysql($store->get('Store Currency Code')), prepare_mysql(gmdate('Y-m-d H:i:s'))
            );
            $db->exec($sql);
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

}


function create_categories($db, $editor) {

    print "Deleting categories if exists\n";


    $sql = sprintf('SELECT `Store Key` FROM `Store Dimension` ');

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $store = new Store($row['Store Key']);

            $category = new Category($store->get('Store Family Category Key'));

            $category->delete();

            $category = new Category(
                $store->get('Store Department Category Key')
            );
            $category->delete();


            $store->update(
                array(
                    'Store Family Category Key'     => '',
                    'Store Department Category Key' => '',
                ), 'no_history'
            );

        }
    }


    print "Looping old dpt and fam\n";


    $sql = sprintf('SELECT `Store Key` FROM `Store Dimension` ');

    if ($result = $db->query($sql)) {


        foreach ($result as $row) {
            $store             = new Store($row['Store Key']);
            $category_fam_key  = create_main_category(
                $store, $editor, 'Families'
            );
            $category_dept_key = create_main_category(
                $store, $editor, 'Departments'
            );


            $store->update(
                array(
                    'Store Family Category Key'     => $category_fam_key,
                    'Store Department Category Key' => $category_dept_key,
                ), 'no_history'
            );


            $sql = sprintf(
                'SELECT * FROM `Product Department Dimension` WHERE `Product Department Store Key`=%d AND `Product Department Key`!=%d', $store->id, $store->get('Store No Products Department Key')
            );

            if ($result2 = $db->query($sql)) {
                foreach ($result2 as $row2) {
                    $departments = new Category($category_dept_key);
                    $data        = array(
                        'Category Store Key' => $store->id,
                        'Category Subject'   => 'Product',
                        'Category Label'     => $row2['Product Department Name']
                    );


                    $department = create_subcategory(
                        $departments, $editor, $data, $row2['Product Department Code']
                    );

                    if (!$department) {
                        $department = new Category(
                            'find', array(
                                      'Category Store Key'  => $store->id,
                                      'Category Parent Key' => $departments->id,
                                      'Category Code'       => $row2['Product Department Code']

                                  )
                        );
                    }

                    $sql = sprintf(
                        'UPDATE `Product Category Dimension` SET `Product Category Valid From`=%s,`Product Category Description`=%s WHERE `Product Category Key`=%d ',
                        prepare_mysql($row2['Product Department Valid From']), prepare_mysql($row2['Product Department Description']), $department->id

                    );
                    $db->exec($sql);


                    $sql = sprintf(
                        "SELECT * FROM `Image Bridge` WHERE `Subject Type`='Department' AND `Subject Key`=%d", $row2['Product Department Key']
                    );
                    if ($result3 = $db->query($sql)) {
                        foreach ($result3 as $row3) {
                            $sql = sprintf(
                                "INSERT INTO `Image Bridge` (`Subject Type`,`Subject Key`,`Image Key`,`Is Principal`,`Image Caption`) VALUES (%s,%d,%d,%s,%s)", prepare_mysql('Category'),
                                $department->id, $row3['Image Key'], prepare_mysql($row3['Is Principal']), prepare_mysql($row3['Image Caption'], false)

                            );
                            $db->exec($sql);
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


            $sql = sprintf(
                'SELECT `Product Family Key`,`Product Family Name`,`Product Family Code`,`Product Family Main Department Code`,`Product Family Main Department Code`,`Product Family Valid From`,`Product Family Description` FROM `Product Family Dimension` WHERE `Product Family Store Key`=%d AND `Product Family Key`!=%d',
                $store->id, $store->get('Store No Products Family Key')
            );

            if ($result2 = $db->query($sql)) {
                foreach ($result2 as $row2) {
                    $families = new Category($category_fam_key);
                    $data     = array(
                        'Category Store Key' => $store->id,
                        'Category Subject'   => 'Product',
                        'Category Label'     => $row2['Product Family Name']
                    );
                    $family   = create_subcategory(
                        $families, $editor, $data, $row2['Product Family Code']
                    );

                    if (!$family) {
                        $family = new Category(
                            'find', array(
                                      'Category Store Key'  => $store->id,
                                      'Category Parent Key' => $departments->id,
                                      'Category Code'       => $row2['Product Family Code']

                                  )
                        );
                    }


                    $sql = sprintf(
                        "SELECT * FROM `Image Bridge` WHERE `Subject Type`='Family' AND `Subject Key`=%d", $row2['Product Family Key']
                    );
                    if ($result3 = $db->query($sql)) {
                        foreach ($result3 as $row3) {
                            $sql = sprintf(
                                "INSERT INTO `Image Bridge` (`Subject Type`,`Subject Key`,`Image Key`,`Is Principal`,`Image Caption`) VALUES (%s,%d,%d,%s,%s)", prepare_mysql('Category'), $family->id,
                                $row3['Image Key'], prepare_mysql($row3['Is Principal']), prepare_mysql($row3['Image Caption'], false)

                            );
                            //print "$sql\n";
                            $db->exec($sql);
                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        exit;
                    }


                    $sql = sprintf(
                        'UPDATE `Product Category Dimension` SET `Product Category Valid From`=%s,`Product Category Description`=%s WHERE `Product Category Key`=%d ',
                        prepare_mysql($row2['Product Family Valid From']), prepare_mysql($row2['Product Family Description']), $family->id

                    );
                    $db->exec($sql);


                    $department = new Category(
                        'find', array(
                                  'Category Store Key'  => $store->id,
                                  'Category Parent Key' => $category_dept_key,
                                  'Category Code'       => $row2['Product Family Main Department Code']

                              )
                    );

                    if ($department->id) {
                        $department->associate_subject($family->id);
                    }

                    $sql = sprintf(
                        'SELECT * FROM `Product Dimension` WHERE  `Product Store Key`=%d AND `Product Family Key`=%d ', $store->id, $row2['Product Family Key']
                    );

                    if ($result3 = $db->query($sql)) {
                        foreach ($result3 as $row3) {
                            $product = new Product($row3['Product ID']);

                            if ($product->id) {

                                $family->associate_subject($product->id);
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

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


function migrate_products($db, $editor) {


    $sql = sprintf(
        'UPDATE `Product Dimension` SET  `Product Status`="Discontinued"  '
    );
    $db->exec($sql);

    $sql = sprintf('SELECT * FROM `Product Dimension` WHERE `Product ID`=2021');

    $sql = sprintf(
        'SELECT * FROM `Product Dimension` ORDER BY `Product ID` DESC'
    );

    if ($result = $db->query($sql)) {


        foreach ($result as $row) {
            $editor['Date'] = gmdate('Y-m-d H:i:s');

            $status = 'Active';


            if ($row['Product Record Type'] == 'Historic' or $row['Product Main Type'] == 'Discontinued' or $row['Product Sales Type'] == 'Not for Sale') {
                $status = 'Discontinued';
            }


            if ($status == 'Active') {
                $sql = sprintf(
                    'SELECT count(*) AS num FROM  `Product Dimension` WHERE `Product Store Key`=%d AND `Product Status`="Active" AND `Product Code`=%s ', $row['Product Store Key'],
                    prepare_mysql($row['Product Code'])

                );

                if ($result2 = $db->query($sql)) {
                    if ($row2 = $result2->fetch()) {
                        if ($row2['num'] > 0) {
                            $status = 'Discontinued';
                        }
                    }
                } else {
                    print_r($error_info = $db->errorInfo());
                    exit;
                }

            }


            $new_product = new Product($row['Product ID']);


            $new_product->update(
                array(
                    'Product Status' => $status
                ), 'no_history'
            );


            continue;


            /*
        $outer_name=$row['Product Name'];
        if ($row['Product Units Per Case']>1) {
            $outer_name=$row['Product Units Per Case'].'x '.$outer_name;
        }


        $unit_type=array();
        $unit_type[$row['Product Unit Type']]=_($row['Product Unit Type']);
        $unit_type=json_encode($unit_type);
        $data=array(
            'editor'=>$editor,

            'Store Product Status'=>$status,
            'Store Product Store Key'=>$row['Product Store Key'],
            'Store Product Code'=>$row['Product Code'],

            'Store Product Label in Family'=>$row['Product Special Characteristic'],
            'Store Product Valid From'=>$row['Product Valid From'],
            'Store Product Valid To'=>$row['Product Valid To'],
            'Store Product Price'=>$row['Product Price'],
            'Store Product Outer Description'=>$outer_name,
            'Store Product Outer Tariff Code'=>$row['Product Tariff Code'],
            'Store Product Outer Duty Rate'=>$row['Product Duty Rate'],
            'Store Product Outer Weight'=>($row['Product Package Weight']==0?'':$row['Product Package Weight']),

            'Store Product Outer Dimensions'=>$row['Product Package XHTML Dimensions'],
            'Store Product Outer UN Number'=>$row['Product UN Number'],
            'Store Product Outer UN Class'=>$row['Product UN Class'],
            'Store Product Outer Packing Group'=>$row['Product Packing Group'],
            'Store Product Outer Proper Shipping Name'=>$row['Product Proper Shipping Name'],
            'Store Product Outer Hazard Indentification Number'=>$row['Product Hazard Indentification Number'],
            'Store Product Units Per Outer'=>$row['Product Units Per Case'],
            'Store Product Unit Description'=>$row['Product Name'],
            'Store Product Unit Type'=>$unit_type,
            'Store Product Unit Weight'=>($row['Product Unit Weight']==0?'':$row['Product Unit Weight']),
            'Store Product Unit Dimensions'=>$row['Product Unit XHTML Dimensions'],

        );
        //print_r($data);
        $product=new Product('find', $data, 'create');


        $sql=sprintf('update `Store Product Dimension` set  `Store Product Key`=%d  where  `Store Product Key`=%d',
            $row['Product ID'],
            $product->pid
        );
        $db->exec($sql);

        $product=new Product($row['Product ID']);
*/
            //$product=new Product('pid', $row['Product ID']);


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}


function create_subcategory($category, $editor, $data, $code, $suffix = '') {


    $data['Category Code'] = $code.($suffix != '' ? '.'.$suffix : '');

    $subcategory = $category->create_category($data);
    if ($subcategory->new) {
        return $subcategory;

    } else {
        if ($suffix == '') {
            $suffix = 2;
        } else {
            $suffix++;
        }


        create_subcategory($category, $editor, $data, $code, $suffix);


    }


}


function create_main_category($store, $editor, $type, $suffix = '') {


    if ($type == 'Families') {
        $prefix  = 'Fam';
        $label   = _('Families');
        $subject = 'Product';
        $scope   = 'Product';
    } elseif ($type == 'Departments') {
        $prefix  = 'Dept';
        $label   = _('Departments');
        $subject = 'Category';
        $scope   = 'Product';

    } else {
        exit('wrong special product category type '.$type);
    }


    $data = array(
        'Category Code'           => $prefix.'.'.$store->get('Code').($suffix != '' ? '.'.$suffix : ''),
        'Category Label'          => $label,
        'Category Scope'          => $scope,
        'Category Subject'        => $subject,
        'Category Store Key'      => $store->id,
        'Category Can Have Other' => 'No',
        'Category Locked'         => 'Yes',
        'Category Branch Type'    => 'Root',
        'editor'                  => $editor

    );


    //print_r($data);

    $category = new Category('find create', $data);

    if ($category->error or !$category->id) {
        print_r($category);
        exit;

    }

    if (!$category->new) {
        //print "dup\n";
        //print_r($data);

    }


    return $category->id;
    /*
    if ($category->new) {
        return $category->id;

    }else {



        print_r($category);



        if ($suffix=='') {
            $suffix=2;
        }else {
            $suffix++;
        }


        create_main_category($store, $editor, $type, $suffix);


    }
*/


}


?>
