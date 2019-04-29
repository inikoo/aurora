<?php
/*
 About:
 Author: Raul Perusquia <raul@inikoo.com>
 Created:6 August 2018 at 13:21:57 GMT+8, Kuala Lumpur Malaysia
 Copyright (c) 2018, Inikoo

 Version 3

*/


function get_categorize_invoices_functions() {

    $categorize_invoices_functions                   = array();
    $categorize_invoices_functions['in_level_type']     = function ($data, $aux) {

        $aux=json_decode($aux,true);
        
        if ( in_array($data["Invoice Customer Level Type"] ,$aux)) {
            return true;
        } else {
            return false;
        }
    };

    $categorize_invoices_functions['not_in_level_type']     = function ($data, $aux) {
        $aux=json_decode($aux,true);

        if (!in_array($data["Invoice Customer Level Type"] ,$aux)) {
            return true;
        } else {
            return false;
        }
    };
    $categorize_invoices_functions['in_counties']        = function ($data, $aux) {
        $aux=json_decode($aux,true);

        if (in_array($data["Invoice Customer Level Type"] , $aux)) {
            return true;
        } else {
            return false;
        }
    };
    $categorize_invoices_functions['not_in_counties'] = function ($data, $aux) {
        $aux=json_decode($aux,true);

        if (!in_array($data["Invoice Customer Level Type"] , $aux)) {
            return true;
        } else {
            return false;
        }
    };


    $categorize_invoices_functions['country'] = function ($data, $aux) {



        if ($data["Invoice Address Country 2 Alpha Code"] ==$aux) {
            return true;
        } else {
            return false;
        }
    };
    $categorize_invoices_functions['not_in_country'] = function ($data, $aux) {


        if ($data["Invoice Address Country 2 Alpha Code"] !=$aux) {
            return true;
        } else {
            return false;
        }
    };

    $categorize_invoices_functions['store'] = function ($data, $aux) {


        if ($data["Invoice Store Key"] ==$aux) {
            return true;
        } else {
            return false;
        }
    };

    $categorize_invoices_functions['stores'] = function ($data, $aux) {

        $aux=json_decode($aux,true);

        if (in_array($data["Invoice Store Key"] , $aux)) {
            return true;
        } else {
            return false;
        }
    };
    $categorize_invoices_functions['not_in_stores'] = function ($data, $aux) {

        $aux=json_decode($aux,true);

        if (!in_array($data["Invoice Store Key"] , $aux)) {
            return true;
        } else {
            return false;
        }
    };

    $categorize_invoices_functions['other'] = function ($data, $aux) {

        return true;

    };

    return $categorize_invoices_functions;
}

