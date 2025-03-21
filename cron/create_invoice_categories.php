<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 26 January 2016 at 0:13:00 GMT+8, Kuala Lumpur, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once __DIR__.'/cron_common.php';
require_once 'utils/natural_language.php';
require_once 'utils/date_functions.php';


require_once 'class.Store.php';
require_once 'class.Category.php';


$editor = array(
    'Author Name'  => '',
    'Author Alias' => '',
    'Author Type'  => '',
    'Author Key'   => '',
    'User Key'     => 0,
    'Date'         => gmdate('Y-m-d H:i:s')
);

$print_est = true;

print date('l jS \of F Y h:i:s A')."\n";


$account = new Account();


//$account->update_orders();

// for UK
$root = get_object('Category', 13879);


$data = array(
    'Category Code'      => 'Showroom',
    'Category Label'     => 'Showroom',
    'Category Scope'     => 'Invoice',
    'Category Subject'   => 'Invoice',
    'editor' => $editor
);
$root->create_category($data);

exit;


$sql = sprintf("SELECT * FROM `Store Dimension` where `Store Key` in (22,23)  ");
if ($result = $db->query($sql)) {
    foreach ($result as $row) {
        $store = new Store('id', $row['Store Key']);

        print $store->get('Store Code')."\n";

        $data = array(
            'Category Code'      => 'i'.$row['Store Code'],
            'Category Label'     => $row['Store Name'],
            'Category Scope'     => 'Invoice',
            'Category Subject'   => 'Invoice',
            'Category Store Key' => $row['Store Key'],

            'editor' => $editor

        );


        print_r($data);

        $root->create_category($data);


    }

}
exit;

$data = array(
    'Category Code'      => 'Faire',
    'Category Label'     => 'Faire',
    'Category Scope'     => 'Invoice',
    'Category Subject'   => 'Invoice',
    'editor' => $editor
);
$root->create_category($data);



$data = array(
    'Category Code'      => 'Ankor',
    'Category Label'     => 'Ankor',
    'Category Scope'     => 'Invoice',
    'Category Subject'   => 'Invoice',
    'editor' => $editor
);
$root->create_category($data);

exit;

/*

$root = get_object('Category', 4);
$root->editor=$editor;

$data = array(
    'Category Code'      => 'Faire',
    'Category Label'     => 'Faire',
    'Category Scope'     => 'Invoice',
    'Category Subject'   => 'Invoice',
    'editor' => $editor
);
$root->create_category($data);

exit;
*/

/*
$data = array(
    'Category Code'      => 'PGB',
    'Category Label'     => 'Partners GB',
    'Category Scope'     => 'Invoice',
    'Category Subject'   => 'Invoice',
    'editor' => $editor
);

$root->create_category($data);
*/

/*
$data = array(
    'Category Code'      => 'Ro',
    'Category Label'     => 'Romania',
    'Category Scope'     => 'Invoice',
    'Category Subject'   => 'Invoice',
    'editor' => $editor
);
$root->create_category($data);


$data = array(
    'Category Code'      => 'Zen',
    'Category Label'     => 'Zentrada',
    'Category Scope'     => 'Invoice',
    'Category Subject'   => 'Invoice',
    'editor' => $editor
);
$root->create_category($data);


*/

$data = array(
    'Category Code'      => 'PA',
    'Category Label'     => 'Partners',
    'Category Scope'     => 'Invoice',
    'Category Subject'   => 'Invoice',
    'editor' => $editor
);
$root->create_category($data);

/*

$data = array(
    'Category Code'      => 'ES',
    'Category Label'     => 'Domestic',
    'Category Scope'     => 'Invoice',
    'Category Subject'   => 'Invoice',
    'editor' => $editor
);
$root->create_category($data);

$data = array(
    'Category Code'      => 'Exports',
    'Category Label'     => 'Exports',
    'Category Scope'     => 'Invoice',
    'Category Subject'   => 'Invoice',
    'editor' => $editor
);
$root->create_category($data);
*/
/*
$data = array(
    'Category Code'      => 'DS',
    'Category Label'     => 'Dropshipping',
    'Category Scope'     => 'Invoice',
    'Category Subject'   => 'Invoice',
    'editor' => $editor
);
$root->create_category($data);
*/