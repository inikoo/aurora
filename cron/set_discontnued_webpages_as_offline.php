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

        $page         = get_object('Webpage', $row['Page Key']);
        $page->editor = $editor;
        /**
         * @var \ProductCategory $category
         */
        $category = get_object('Category', $page->get('Webpage Scope Key'));


        if ($category->id) {
            $category->editor = $editor;
            $category->update_product_category_products_data();

            $category->get_products_subcategories_status_numbers();


            if ($category->get('Product Category Public') == 'No') {
                $contador++;
                $page->unpublish();
                print "$contador ".$row['Store Code'].' '.$row['Webpage Code']." no_public \n";


            } else {
                if ($category->get('Category Scope') == 'Product') {

                    if ((gmdate('U') - strtotime($category->get('Product Category Valid To'))) > 3600 * 24 * 30 * 3) {
                        if ($category->get('Product Category Status') == 'Discontinued' and $category->get('Product Category In Process Products') == 0 and $category->get('Product Category Active Products') == 0) {
                            $contador++;

                            $page->unpublish();


                            print "$contador ".$row['Store Code'].' '.$row['Webpage Code']." ".$category->get('Product Category Valid To')."\n";

                           //   print_r($category);
                            //exit;

                        }
                    }
                }
            }
        } else {
            $contador++;
            $page->unpublish();
            $page->delete();
            print "$contador ".$row['Store Code'].' '.$row['Webpage Code']." error not category found \n";
        }

    }
} else {
    print_r($error_info = $this->db->errorInfo());
    exit();
}


$sql  = "select  `Store Code`,`Webpage Code`,`Page Key` from `Page Store Dimension` left join `Store Dimension` on (`Webpage Store Key`=`Store Key` ) ;";
$stmt = $db->prepare($sql);
$stmt->execute(
    array()
);
while ($row = $stmt->fetch()) {
    $webpage = get_object('Webpage', $row['Page Key']);
    $ok=true;

    if ($webpage->get('Webpage Scope') == 'Category Products' or $webpage->get('Webpage Scope') == 'Category Categories') {


        $category = get_object('Category', $webpage->get('Webpage Scope Key'));
        if(!$category->id){
            $contador++;
            $webpage->unpublish();
            $webpage->delete();
            print "$contador ".$row['Store Code'].' '.$row['Webpage Code']." error not category found \n";
            $ok=false;

        }

    }elseif ($webpage->get('Webpage Scope') == 'Product') {


        $product    = get_object('Product', $webpage->get('Webpage Scope Key'));
        if(!$product->id){
            $contador++;
            $webpage->unpublish();
            $webpage->delete();
            print "$contador ".$row['Store Code'].' '.$row['Webpage Code']." error not product found \n";
            $ok=false;
        }

    }

    if($ok){
        $content=$webpage->get('Content Data');

        if(empty($content['blocks']) or !is_array($content['blocks'])){
            print "$contador ".$row['Store Code'].' '.$row['Webpage Code']." error no blocks \n";

        }


    }

}
