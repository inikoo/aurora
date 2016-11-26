<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 24 November 2016 at 17:17:11 GMT+8, Yiwu, China
 Copyright (c) 2016, Inikoo

 Version 3

*/

include_once('class.Product.php');

$logged=true;

$category=$state['_object'];

if($category->get('Category Scope')=='Product'){


    $category->create_stack_index(true);

    $webpage=$category->get_webpage();





    $products=array();

    $sql = sprintf("SELECT `Product Category Stack Product ID`,`Product Category Stack Category Key`,`Product Category Stack Index`, P.`Product ID`,`Product Code`,`Product Web State` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  LEFT JOIN `Product Category Stack Index` S ON (`Subject Key`=S.`Product Category Stack Product ID` AND S.`Product Category Stack Category Key`=B.`Category Key`) 
   WHERE  `Category Key`=%d  and `Product Web State` in  ('For Sale','Out of Stock')   ORDER BY `Product Web State`,   ifnull(`Product Category Stack Index`,99999999)",
                   $category->id);


  //  print $sql;

    $counter=0;
    $stack_index=0;
    if ($result=$db->query($sql)) {
    		foreach ($result as $row) {


                $product=new Product( $row['Product ID']);

$product->load_acc_data();



                $image_key=$product->get('Product Main Image Key');

                if ($image_key) {
                    $img='/image_root.php?size=small&id='.$image_key;
                    $normal_img='image_root.php?id='.$image_key;
                }else {
                    $img='art/nopic.png';
                    $normal_img='art/nopic.png';

                }

                $stack_index++;


                $product_data=array(
                    'stack_index'=>$stack_index,
                    'object'=>$product,
                    'code'=>$product->data['Product Code'],
                    'name'=>$product->data['Product Name'],
                    'id'=>$product->data['Product ID'],
                    'price'=>$product->data['Product Price'],
                    'special_char'=>$product->data['Product Special Characteristic'],
                    'img'=>$img,

                 //   'page_id'=>$row['Page Key'],
                );




                if ($counter==0) {
                    $product_data['first']=true;
                }else {
                    $product_data['first']=false;
                }

                $product_data['col']=fmod($counter, 4)+1;
                $counter++;
                $products[]=$product_data;
    		}
    }else {
    		print_r($error_info=$db->errorInfo());
    		print "$sql\n";
    		exit;
    }




  //  print_r($products);

$smarty->assign('_products',$products);
    $smarty->assign('category',$category);
    $smarty->assign('webpage',$webpage);



    $html=$smarty->fetch('category.webpage.preview.tpl');

}



?>
