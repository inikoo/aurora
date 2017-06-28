<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 13 June 2017 at 16:19:59 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2015, Inikoo

 Version 3

*/

require_once 'common.php';
require_once 'utils/ar_common.php';
require_once 'utils/table_functions.php';
require_once 'utils/date_functions.php';


if (!$user->can_view('stores')) {
    echo json_encode(
        array(
            'state' => 405,
            'resp'  => 'Forbidden'
        )
    );
    exit;
}


if (!isset($_REQUEST['tipo'])) {
    $response = array(
        'state' => 405,
        'resp'  => 'Non acceptable request (t)'
    );
    echo json_encode($response);
    exit;
}


$tipo = $_REQUEST['tipo'];

switch ($tipo) {
    case 'store_categories':

        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key'),
                         'type'=> array('type' => 'string'),
                         'page'=> array('type' => 'string'),
                     )
        );

        store_categories($data, $db, $user);
        break;
    case 'category_data':

        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key')
                     )
        );

        category_data($data, $db, $user);
        break;
    case 'product_data':

        $data = prepare_values(
            $_REQUEST, array(
                         'key' => array('type' => 'key')
                     )
        );

        product_data($data, $db, $user);
        break;

    default:
        $response = array(
            'state' => 405,
            'resp'  => 'Tipo not found '.$tipo
        );
        echo json_encode($response);
        exit;
        break;
}


function store_categories($data, $db, $user) {

    include_once('class.Public_Store.php');

    $store = new Public_Store($data['key']);

    $response = array(
        'state' => 200,
        'items'=>$store->get_categories($data['type'],$data['page'],'menu')
    );
    echo json_encode($response);

}


function category_data($data, $db, $user) {

    include_once('class.Category.php');
    include_once 'class.Public_Webpage.php';

    $category = new Category($data['key']);

    $subject_webpage = new Public_Webpage($category->get('Product Category Webpage Key'));
    $webpage_key=$subject_webpage->id;


    $response = array(
        'state' => 200,
        'data'  => array(
            'category_key'   => $category->id,
            'webpage_key'   => $webpage_key,
            'code'   => $category->get('Code'),
            'label'  => $category->get('Label'),
            'images' => $category->get_images_slidesshow(),
            'webpage_link'=>'website/'.$subject_webpage->get('Webpage Website Key').'/webpage/'.$webpage_key
        )
    );
    echo json_encode($response);

}


function product_data($data, $db, $user) {

    include_once('class.Product.php');
    include_once 'class.Public_Webpage.php';

    $product = new Product($data['key']);

    $subject_webpage = new Public_Webpage($product->get('Product Webpage Key'));
    $webpage_key=$subject_webpage->id;


    $response = array(
        'state' => 200,
        'data'  => array(
            'category_key'   => $product->id,
            'webpage_key'   => $webpage_key,
            'code'   => $product->get('Code'),
            'label'  => $product->get('Name'),
            'price'  => $product->get('Price'),
            'images' => $product->get_images_slidesshow(),
            'webpage_link'=>'website/'.$subject_webpage->get('Webpage Website Key').'/webpage/'.$webpage_key
        )
    );
    echo json_encode($response);

}


?>
