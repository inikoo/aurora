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


$counter=0;

$editor       = array(
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'System (Script)',
    'Author Alias' => 'System (Script)',
    'v'            => 3


);
$store_old_ds = new Store('code', 'DS');
$store_new_ds = new Store('code', 'AWD');
$store_aw     = new Store('code', 'UK');

$new_ds_website=get_object('Website',$store_new_ds->get('Store Website Key'));

$fam_root = get_object('Category', 30960);


$sql = sprintf(
    'SELECT  `Category Code`,`Category Label`,`Category Key` FROM  `Category Dimension` C   LEFT JOIN `Page Store Dimension` P ON (P.`Webpage Scope Key`=C.`Category Key` AND `Webpage Scope`="Category Categories" ) WHERE   C.`Category Parent Key`=%d  and `Page Key` is not null order by `Webpage Name`  ',
    $store_aw->get('Store Department Category Key')
);

$cat = get_object('Category', 30961);
if ($result = $db->query($sql)) {
    foreach ($result as $row) {

        $old_department = get_object('Category', $row['Category Key']);
        $new_department = $cat->create_category(
            array(
                'Category Code'  => $row['Category Code'],
                'Category Label' => $row['Category Label'],
            )
        );


        $sql = sprintf(
            'SELECT  `Category Code`,`Category Label`,C.`Category Key` FROM `Category Bridge` B left join    `Category Dimension` C   on (B.`Subject Key`=C.`Category Key` and `Subject`="Category")  left join `Product Category Dimension` P on (P.`Product Category Key`=C.`Category Key`) left join `Product Category Data` D on (D.`Product Category Key`=C.`Category Key`) left join `Product Category DC Data` DC on (DC.`Product Category Key`=C.`Category Key`)
    left join `Page Store Dimension` on (`Webpage Scope Key`=B.`Subject Key` and `Webpage Scope`="Category Products"  ) WHERE    B.`Category Key`=%d   and `Product Category Public`="Yes"  and `Product Category Status`!="Discontinued"  and `Product Category Webpage Key`=`Page Key`   ', $old_department->id
        );




        if ($result2 = $db->query($sql)) {
            foreach ($result2 as $row2) {
                $old_family = get_object('Category', $row2['Category Key']);
                $old_family->update_product_category_products_data();
                if ($old_family->get('Product Category Public') == 'Yes') {

                    $new_family = $fam_root->create_category(
                        array(
                            'Category Code'  => $row2['Category Code'],
                            'Category Label' => $row2['Category Label'],
                            'editor'         => $editor

                        )
                    );
                    $new_family->update(
                        array(
                            'Product Category Department Category Key' => $new_department->id
                        )
                    );
                    //'Online Force Out of Stock','Online Auto','Offline','Online Force For Sale'


                    $sql = sprintf(
                        'Select * from `Category Bridge` left join  `Product Dimension` P on (`Subject Key`=`Product ID`) 
                        where P.`Product Type`=\'Product\' and`Subject`=\'Product\'  and `Product Status`="Active" and `Product Public`="Yes"  and `Product Web Configuration`!="Offline"    and  `Category Key`=%d  
                         and `Product Code` not like "%%-pst"   and `Product Code` not like "%%-gift" and `Product Code` not like "%%-st"  and `Product Code` not like "%%-gift" and   `Product Code` not like "awlabel-%%" 
                         ',
                        $old_family->id
                    );



                    if ($result3 = $db->query($sql)) {
                        foreach ($result3 as $row3) {



                            $old_product = get_object('Product', $row3['Product ID']);



                            $parts_data = $old_product->get_parts_data();


                            if(count($parts_data) != 1){
                                print '****     '.$old_product->get('Code')." parts !=1\n";
                            }
                            if($old_product->get('Product Price') <=0){
                                print  '****     '.$old_product->get('Code')." wrong price\n";
                            }elseif($old_product->get('Product Price') <=0.15){
                                print  '####    '.$old_product->get('Code')." wrong price\n";
                            }

                            if($old_product->get('Product Units Per Case') <=0){
                                print  '****     '.$old_product->get('Code')." wrong units\n";
                            }

                           // print $old_product->get('Code')." *\n";

                            if (count($parts_data) == 1 and $old_product->get('Product Price') > 0.15 and $old_product->get('Product Units Per Case') > 0) {

                                $ds_factor = 1 / $old_product->get('Product Units Per Case');

                                $price = round(1.3 * $ds_factor * $old_product->get('Product Price'), 2);




                                $part_data = array_pop($parts_data);


                               //print $row3['Product Code']."\n";

                                $product_data = array(


                                    'Product Code'            => $row3['Product Code'],
                                    // 'Product CPNP Number'     => $row3['Product CPNP Number'],
                                    'Product Parts'           => json_encode(
                                        array(
                                            array(
                                                'Part SKU' => $part_data['Part SKU'],
                                                'Ratio'    => $ds_factor * $part_data['Ratio'],
                                                'Note'     => '',
                                            )
                                        )
                                    ),
                                    'Family Category Code'    => $new_family->get('Category Code'),
                                    'Product Label in Family' => $old_product->get('Product Label in Family'),
                                    'Product Units Per Case'  => 1,
                                    'Product Unit Label'      => $old_product->get('Product Unit Label'),
                                    'Product Price'           => $price,
                                    'Product Name'            => $old_product->get('Product Name'),
                                    'Product Unit RRP'        => $old_product->get('Product Unit RRP'),
                                    // 'Product Unit Weight'     => $old_product->get('Product Unit Weight'),
                                    'Product Description'     => $old_product->get('Product Description'),
                                    'editor'                  => $editor,
                                );


                                $new_product = $store_new_ds->create_product($product_data);


                                if(is_object($new_product) and  $new_product->id) {

                                    $new_product->update(array('Product Family Category Key' => $new_family->id));


                                    $webpage_product = get_object('Webpage', $new_product->get('Product Webpage Key'));

                                    if ($webpage_product->id) {

                                        $webpage_product->editor = $editor;
                                        $new_content_data        = $webpage_product->get('Content Data');

                                        $old_webpage_product = get_object('Webpage', $old_product->get('Product Webpage Key'));
                                        $old_content_data    = $old_webpage_product->get('Content Data');

                                        $text = '';
                                        if (isset($old_content_data['blocks'])) {
                                            foreach ($old_content_data['blocks'] as $block) {
                                                if ($block['type'] == 'product') {
                                                    $text = $block['text'];
                                                }

                                            }

                                            $text = preg_replace('/\bWholesaler\b/', 'Dropshipper', $text);
                                            $text = preg_replace('/\bwholesaler\b/', 'dropshipper', $text);
                                            $text = preg_replace('/\bwholesale\b/', 'dropship', $text);
                                            $text = preg_replace('/\bwholesale\b/', 'dropship', $text);
                                            $text = preg_replace('/ancientwisdom.biz/i', 'aw-dropship.com', $text);

                                            if ($text != '') {
                                                if (isset($new_content_data['blocks'])) {

                                                    foreach ($new_content_data['blocks'] as $_key => $_block) {
                                                        if ($_block['type'] == 'product') {
                                                            $new_content_data['blocks'][$_key]['text'] = $text;
                                                        }

                                                    }


                                                    //print_r($new_content_data);
                                                    $webpage_product->update(array('Page Store Content Data' => json_encode($new_content_data)), 'no_history');
                                                    $webpage_product->publish();
                                                }

                                            }

                                        }
                                    }
                                }





                              //  print $new_product->get('Code')."\n";




                            }


                        }
                    } else {
                        print_r($error_info = $db->errorInfo());
                        print "$sql\n";
                        exit;
                    }

                    $counter++;

                    $new_family->update_product_category_products_data();


                    $products_in_family= $new_family->get('Product Category Active Products')+$new_family->get('Product Category Discontinuing Products');

                    print $new_family->get('Code')." with $products_in_family products\n";


                    if($products_in_family>0){
                        $webpage_key = $new_ds_website->create_category_webpage($new_family->id);
                        $webpage=get_object('Webpage',$webpage_key);
                        $webpage->publish();


                    }else{
                        $new_family->delete();
                    }


                   // if($counter>3){
                    //    exit;
                   // }


                    //if($new_family)





                }


            }
        }


        $products_in_department= $new_department->get('Product Category Active Products')+$new_department->get('Product Category Discontinuing Products');

        print 'Department '.$new_department->get('Code')." with $products_in_department products\n";


        if($products_in_department>0){
            $webpage_key = $new_ds_website->create_category_webpage($new_department->id);
            $webpage=get_object('Webpage',$webpage_key);
            $webpage->publish();


        }else{
           // $new_department->delete();
        }
        




    }
}

