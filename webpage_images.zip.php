<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 October 2017 at 13:10:14 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

/** @var PDO $db */


require_once 'common.php';
/** @var User $user */
if ($user->get('User View') != 'Staff') {
    exit;
}

if (empty($_REQUEST['parent']) or empty($_REQUEST['key'])) {
    exit;
}

$files = array();

$object = get_object($_REQUEST['parent'], $_REQUEST['key']);


$download_name = 'images_'.strtolower($object->get('Code'));


$counter = 1;
foreach ($object->get_images_slideshow() as $data) {
    $files[] = array(
        'name'      => strtolower($object->get('Code')).sprintf('_%02d', $counter).preg_replace('/^.*\./', '.', $data['name']),
        'image_key' => $data['id'],
        'folder'    => ''
    );
    $counter++;
}


if ($_REQUEST['parent'] == 'category' and $object->get('Category Subject') == 'Product') {
    $category = $object;
    $products = array();

    $sql = sprintf(
        "SELECT  P.`Product ID` FROM `Category Bridge` B  LEFT JOIN `Product Dimension` P ON (`Subject Key`=P.`Product ID`)  LEFT JOIN `Product Category Index` S ON (`Subject Key`=S.`Product Category Index Product ID` AND S.`Product Category Index Category Key`=B.`Category Key`)  WHERE  `Category Key`=%d  AND `Product Web State` IN  ('For Sale','Out of Stock')   ORDER BY `Product Web State`,   ifnull(`Product Category Index Stack`,99999999)",
        $category->id
    );


    $stack_index         = 0;
    $product_stack_index = 0;
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $product = get_object('Product', $row['Product ID']);


            $counter = 1;
            foreach ($product->get_images_slideshow() as $data) {
                $files[] = array(
                    'name'      => strtolower($product->get('Code')).sprintf('_%02d', $counter),
                    'image_key' => $data['id'],
                    'folder'    => 'products/'
                );
                $counter++;
            }
        }
    } else {
        print_r($error_info = $db->errorInfo());
        print "$sql\n";
        exit;
    }
}


$zip = new ZipArchive();

$tmp_file = tempnam('server_files/tmp/', 'webpage_images_zip_').'.zip';
$zip->open($tmp_file, ZipArchive::CREATE);


foreach ($files as $file) {
    $image = get_object('image', $file['image_key']);
    $zip->addFile($image->get('Image Path'), $file['folder'].basename($file['name'].'.'.$image->get('Image File Format')));
}


$zip->close();
header('Content-disposition: attachment; filename='.$download_name.'.zip');
header('Content-type: application/zip');
readfile($tmp_file);
unlink($tmp_file);




