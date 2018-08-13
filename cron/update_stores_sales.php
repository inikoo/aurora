

<?php

/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created: 27 October 2016 at 12:26:07 GMT+8, Cyberjaya, Malaysia
 Copyright (c) 2016, Inikoo

 Version 3

*/

require_once 'common.php';
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



$sql = sprintf(
    'SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Invoice"  '
);

if ($result = $db->query($sql)) {
    foreach ($result as $row) {





        $category = new Category($row['Category Key']);

        if($category->get('Category Branch Type')!='Root'){

            $category->update_invoice_category_sales('Total');


            $category->update_invoice_category_sales('Year To Day');
            $category->update_invoice_category_sales('Quarter To Day');
            $category->update_invoice_category_sales('Month To Day');
            $category->update_invoice_category_sales('Week To Day');

            $category->update_invoice_category_sales('Last Month');
            $category->update_invoice_category_sales('Last Week');

            $category->update_invoice_category_sales('Yesterday');
            $category->update_invoice_category_sales('Today');

            $category->update_invoice_previous_years_data();
            $category->update_invoice_previous_quarters_data();
        }


        //$category->update_invoice_category_sales('Today');
        //continue;




    }

} else {
    print_r($error_info = $db->errorInfo());
    exit;
}


exit;



update_sales($db, $print_est);

update_products($db, $print_est);

update_orders($db, $print_est);

function update_orders($db, $print_est) {

    $sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $store = new Store('id', $row['Store Key']);

            $store->load_acc_data();
            $store->update_orders();


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }
}


function update_products($db, $print_est) {

    $sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $store = new Store('id', $row['Store Key']);

            $store->load_acc_data();
            $store->update_new_products();
            $store->update_product_data();
        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }
}


function update_sales($db, $print_est) {



    $account=new Account($db);
    $account->load_acc_data();

    $account->update_sales_from_invoices('Total');

    $account->update_sales_from_invoices('1 Year');
    $account->update_sales_from_invoices('1 Quarter');
    $account->update_sales_from_invoices('1 Month');
    $account->update_sales_from_invoices('1 Week');

    $account->update_sales_from_invoices('Year To Day');
    $account->update_sales_from_invoices('Quarter To Day');
    $account->update_sales_from_invoices('Month To Day');
    $account->update_sales_from_invoices('Week To Day');

    $account->update_sales_from_invoices('Last Month');
    $account->update_sales_from_invoices('Last Week');

    $account->update_sales_from_invoices('Yesterday');
    $account->update_sales_from_invoices('Today');

    $account->update_previous_years_data();
    $account->update_previous_quarters_data();

    $sql = sprintf(
        'SELECT `Category Key` FROM `Category Dimension` WHERE `Category Scope`="Invoice"  '
    );

    if ($result = $db->query($sql)) {
        foreach ($result as $row) {

            $category = new Category($row['Category Key']);


            //$category->update_invoice_category_sales('Today');
            //continue;


            $category->update_invoice_category_sales('Total');


            $category->update_invoice_category_sales('Year To Day');
            $category->update_invoice_category_sales('Quarter To Day');
            $category->update_invoice_category_sales('Month To Day');
            $category->update_invoice_category_sales('Week To Day');

            $category->update_invoice_category_sales('Last Month');
            $category->update_invoice_category_sales('Last Week');

            $category->update_invoice_category_sales('Yesterday');
            $category->update_invoice_category_sales('Today');

            $category->update_invoice_previous_years_data();
            $category->update_invoice_previous_quarters_data();


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }

    //exit;
    $sql = sprintf("SELECT `Store Key` FROM `Store Dimension`");
    if ($result = $db->query($sql)) {
        foreach ($result as $row) {
            $store = new Store('id', $row['Store Key']);

            $store->load_acc_data();




            $store->update_sales_from_invoices('Total');

            $store->update_sales_from_invoices('1 Year');
            $store->update_sales_from_invoices('1 Quarter');
            $store->update_sales_from_invoices('1 Month');
            $store->update_sales_from_invoices('1 Week');

            $store->update_sales_from_invoices('Year To Day');
            $store->update_sales_from_invoices('Quarter To Day');
            $store->update_sales_from_invoices('Month To Day');
            $store->update_sales_from_invoices('Week To Day');

            $store->update_sales_from_invoices('Last Month');
            $store->update_sales_from_invoices('Last Week');

            $store->update_sales_from_invoices('Yesterday');
            $store->update_sales_from_invoices('Today');

            $store->update_previous_years_data();
            $store->update_previous_quarters_data();


        }

    } else {
        print_r($error_info = $db->errorInfo());
        exit;
    }


}



?>
