<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 2 April 2019 at 14:01:58 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';

require_once 'utils/get_addressing.php';
require_once 'utils/parse_natural_language.php';

require_once 'class.Page.php';
require_once 'class.Website.php';
require_once 'class.Product.php';
require_once 'class.Store.php';
require_once 'class.Public_Product.php';
require_once 'class.Category.php';
require_once 'class.Webpage_Type.php';
require_once 'class.Part.php';

$editor = array(


    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s'),
    'Subject'      => 'System',
    'Subject Key'  => 0,
    'Author Name'  => 'Script (set discontinued products webpages offline)',
    'Author Alias' => 'Script (set discontinued products webpages offline)',
);

$contador = 0;

$sql = "select `Store Code`,`Webpage Code`,`Page Key` from `Page Store Dimension`  left join `Category Dimension` on (`Webpage Scope Key`=`Category Key`)  
left join `Store Dimension` on (`Webpage Store Key`=`Store Key`)  
      where  `Webpage Scope`='Category Products' and `Webpage State`='Online'   ;";

$stmt = $db->prepare($sql);
if ($stmt->execute()) {
    while ($row = $stmt->fetch()) {

        $page             = get_object('Webpage', $row['Page Key']);
        $page->editor     = $editor;
        $category         = get_object('Category', $page->get('Webpage Scope Key'));
        $category->editor = $editor;
        $category->update_product_category_products_data();

        $category->get_products_subcategories_status_numbers();


        if ($category->get('Product Category Public') == 'No') {
            $contador++;
            $page->unpublish();
            print "$contador ".$row['Store Code'].' '.$row['Webpage Code']." no_public \n";


        } else {
            if ($category->get('Category Scope') == 'Product') {

                if ((gmdate('U') - strtotime($category->get('Product Category Valid From'))) > 3600 * 24 * 14) {
                    if ($category->get('Product Category Status') == 'Discontinued' and $category->get('Product Category In Process Products') == 0 and $category->get('Product Category Active Products') == 0) {
                        $contador++;

                        $page->unpublish();


                        print "$contador ".$row['Store Code'].' '.$row['Webpage Code']." x\n";

                        //  print_r($category);
                        // exit;

                    }
                }
            }
        }


    }
} else {
    print_r($error_info = $this->db->errorInfo());
    exit();
}
