<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 19 October 2017 at 13:10:14 GMT+8, Kuala Lumpur, Malaysis
 Copyright (c) 2015, Inikoo

 Version 3

*/


require_once 'utils/object_functions.php';


if (empty($_REQUEST['scope']) or empty($_REQUEST['scope_key'])  or !in_array(strtolower($_REQUEST['scope']),['category','product'])  ) {
    header("HTTP/1.0 400 Bad Request");
    exit;
}


require __DIR__.'/keyring/dns.php';
$db = new PDO(
    "mysql:host=$dns_host;port=$dns_port;dbname=$dns_db;charset=utf8mb4", $dns_user, $dns_pwd, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+0:00';")
);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$redis = new Redis();
$redis->connect(REDIS_HOST, REDIS_PORT);

session_start();


if (empty($_SESSION['website_key'])) {
    include_once('utils/find_website_key.include.php');
    $_SESSION['website_key']=get_website_key_from_domain($redis);
}

$website=get_object('Website',$_SESSION['website_key']);

$files = array();

$object = get_object($_REQUEST['scope'], $_REQUEST['scope_key']);


if(!$object->id){
    header("HTTP/1.0 400 Bad Request");
    exit;
}


if($object->get('Store Key')!=$website->get('Website Store Key')){
    header("HTTP/1.1 403 Forbidden");
    exit;
}


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


if ($_REQUEST['scope'] == 'category' and $object->get('Category Subject') == 'Product') {
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
    $zip->addFile('../'.$image->get('Image Path'), $file['folder'].basename($file['name'].'.'.$image->get('Image File Format')));

}


$zip->close();


header('Content-disposition: attachment; filename='.$download_name.'.zip');
header('Content-type: application/zip');
readfile($tmp_file);
unlink($tmp_file);



