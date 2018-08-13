<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:6 August 2018 at 13:21:57 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/

$categorize_invoices_functions=array();
$categorize_invoices_functions['level_type'] = function ($data,$aux) {
    if ($data["Invoice Customer Level Type"] == $aux) {
        return true;
    } else {
        return false;
    }
};
$categorize_invoices_functions['country'] = function ($data,$aux) {
    if ($data["Invoice Customer Level Type"] ==$aux) {
        return true;
    } else {
        return false;
    }
};
$categorize_invoices_functions['not_in_country'] = function ($data,$aux) {
    if ($data["Invoice Customer Level Type"] !=$aux) {
        return true;
    } else {
        return false;
    }
};

$categorize_invoices_functions['store'] = function ($data,$aux) {


    if ($data["Invoice Store Key"] == $aux) {
        return true;
    } else {
        return false;
    }
};


$categorize_invoices_functions['other'] = function ($data,$aux) {

        return true;

};


?>
